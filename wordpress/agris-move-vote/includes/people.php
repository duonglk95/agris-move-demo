<?php
/**
 * Employees, sign-in session and votes.
 *
 * Sign-in: the person types their name, MSNV and phone; all three must match one row of the list
 * uploaded from Excel. Phone numbers are never stored in clear: only a keyed hash of the last 9 digits.
 * The session is a signed cookie (no WordPress account needed).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const AMV_COOKIE = 'amv_voter';

function amv_name_key( $name ) {
	$name = wp_strip_all_tags( (string) $name );
	$name = function_exists( 'mb_strtolower' ) ? mb_strtolower( $name, 'UTF-8' ) : strtolower( $name );
	$name = remove_accents( $name );
	$name = preg_replace( '/[^a-z0-9 ]+/', ' ', $name );
	return trim( preg_replace( '/\s+/', ' ', $name ) );
}

function amv_msnv_key( $msnv ) {
	return strtoupper( preg_replace( '/\s+/', '', (string) $msnv ) );
}

/** Last 9 digits, so 0912…, +84912…, 84912… and 912… all match. */
function amv_phone_hash( $phone ) {
	$d = preg_replace( '/\D+/', '', (string) $phone );
	if ( strlen( $d ) < 9 ) {
		return '';
	}
	return hash_hmac( 'sha256', substr( $d, -9 ), wp_salt( 'auth' ) );
}

function amv_get_person( $id ) {
	global $wpdb;
	$t = amv_tables();
	return $wpdb->get_row( $wpdb->prepare( "SELECT id, msnv, name, unit FROM {$t['people']} WHERE id = %d", $id ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
}

function amv_people_count() {
	global $wpdb;
	$t = amv_tables();
	return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$t['people']}" ); // phpcs:ignore
}

/** Returns the person or null. */
function amv_check_identity( $name, $msnv, $phone ) {
	global $wpdb;
	$t   = amv_tables();
	$row = $wpdb->get_row( $wpdb->prepare( "SELECT id, msnv, name, name_key, phone_hash, unit FROM {$t['people']} WHERE msnv = %s", amv_msnv_key( $msnv ) ) ); // phpcs:ignore
	if ( ! $row ) {
		return null;
	}
	$ph = amv_phone_hash( $phone );
	if ( ! $ph || ! hash_equals( $row->phone_hash, $ph ) || $row->name_key !== amv_name_key( $name ) ) {
		return null;
	}
	return $row;
}

/** Name suggestions while typing: name and unit only, never MSNV or phone. */
function amv_find_names( $q, $limit = 8 ) {
	global $wpdb;
	$t   = amv_tables();
	$key = amv_name_key( $q );
	if ( strlen( $key ) < 2 ) {
		return array();
	}
	$like = '%' . $wpdb->esc_like( $key ) . '%';
	$rows = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT name, unit FROM {$t['people']} WHERE name_key LIKE %s ORDER BY name LIMIT %d", $like, $limit ) ); // phpcs:ignore
	return array_map( function ( $r ) {
		return array( 'name' => $r->name, 'unit' => $r->unit );
	}, $rows );
}

/* ---------- session cookie ---------- */

function amv_sign( $payload ) {
	return hash_hmac( 'sha256', $payload, wp_salt( 'secure_auth' ) );
}

function amv_set_session( $person_id ) {
	$exp     = time() + 45 * DAY_IN_SECONDS;
	$payload = $person_id . '|' . $exp;
	$value   = $payload . '|' . amv_sign( $payload );
	$opts    = array( 'expires' => $exp, 'path' => COOKIEPATH ? COOKIEPATH : '/', 'secure' => is_ssl(), 'httponly' => true, 'samesite' => 'Lax' );
	if ( COOKIE_DOMAIN ) {
		$opts['domain'] = COOKIE_DOMAIN;
	}
	setcookie( AMV_COOKIE, $value, $opts );
	$_COOKIE[ AMV_COOKIE ] = $value;
}

function amv_clear_session() {
	setcookie( AMV_COOKIE, '', array( 'expires' => time() - 3600, 'path' => COOKIEPATH ? COOKIEPATH : '/', 'secure' => is_ssl(), 'httponly' => true, 'samesite' => 'Lax' ) );
	unset( $_COOKIE[ AMV_COOKIE ] );
}

/** The signed-in voter, or null. */
function amv_current_person() {
	if ( empty( $_COOKIE[ AMV_COOKIE ] ) ) {
		return null;
	}
	$parts = explode( '|', sanitize_text_field( wp_unslash( $_COOKIE[ AMV_COOKIE ] ) ) );
	if ( 3 !== count( $parts ) ) {
		return null;
	}
	list( $id, $exp, $sig ) = $parts;
	if ( (int) $exp < time() || ! hash_equals( amv_sign( $id . '|' . $exp ), $sig ) ) {
		return null;
	}
	return amv_get_person( (int) $id );
}

/* ---------- request details ---------- */

function amv_client_ip() {
	return isset( $_SERVER['REMOTE_ADDR'] ) ? substr( sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ), 0, 64 ) : '';
}

/** Behind a proxy or CDN the visitor's address is in X-Forwarded-For; it is stored as given. */
function amv_forwarded_ip() {
	return isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ), 0, 191 ) : '';
}

/* ---------- voting window ---------- */

function amv_parse_local( $value ) {
	$d = DateTime::createFromFormat( 'Y-m-d H:i', (string) $value, wp_timezone() );
	return $d ? $d->getTimestamp() : 0;
}

/** 'before' | 'open' | 'closed' */
function amv_vote_state() {
	if ( '1' === get_option( 'amv_force_open' ) ) {
		return 'open';
	}
	$now   = time();
	$open  = amv_parse_local( get_option( 'amv_open' ) );
	$close = amv_parse_local( get_option( 'amv_close' ) );
	if ( $open && $now < $open ) {
		return 'before';
	}
	if ( $close && $now > $close ) {
		return 'closed';
	}
	return 'open';
}

/* ---------- votes ---------- */

function amv_count_for( $mover_id ) {
	global $wpdb;
	$t = amv_tables();
	return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$t['votes']} WHERE mover_id = %d", $mover_id ) ); // phpcs:ignore
}

/** mover_id => count, for all movers that have votes */
function amv_all_counts() {
	global $wpdb;
	$t   = amv_tables();
	$out = array();
	foreach ( $wpdb->get_results( "SELECT mover_id, COUNT(*) c FROM {$t['votes']} GROUP BY mover_id" ) as $r ) { // phpcs:ignore
		$out[ (int) $r->mover_id ] = (int) $r->c;
	}
	return $out;
}

function amv_voted_by( $person_id ) {
	global $wpdb;
	$t = amv_tables();
	return array_map( 'intval', $wpdb->get_col( $wpdb->prepare( "SELECT mover_id FROM {$t['votes']} WHERE employee_id = %d", $person_id ) ) ); // phpcs:ignore
}

/** Adds the vote, or takes it back if it was already there. Returns true when the vote now stands. */
function amv_toggle_vote( $person_id, $mover_id ) {
	global $wpdb;
	$t        = amv_tables();
	$existing = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$t['votes']} WHERE employee_id = %d AND mover_id = %d", $person_id, $mover_id ) ); // phpcs:ignore
	if ( $existing ) {
		$wpdb->delete( $t['votes'], array( 'id' => (int) $existing ), array( '%d' ) );
		return false;
	}
	$wpdb->insert( $t['votes'], array(
		'employee_id' => $person_id,
		'mover_id'    => $mover_id,
		'ip'          => amv_client_ip(),
		'forwarded'   => amv_forwarded_ip(),
		'created_at'  => current_time( 'mysql' ),
	), array( '%d', '%d', '%s', '%s', '%s' ) );
	return true;
}

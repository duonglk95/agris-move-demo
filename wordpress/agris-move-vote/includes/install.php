<?php
/**
 * Two tables: the employee list used to sign in, and one row per "love".
 * Data is kept when the plugin is deactivated or removed, so results are never lost by accident.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function amv_tables() {
	global $wpdb;
	return array(
		'people' => $wpdb->prefix . 'amv_employees',
		'votes'  => $wpdb->prefix . 'amv_votes',
	);
}

function amv_install() {
	global $wpdb;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	$t       = amv_tables();
	$charset = $wpdb->get_charset_collate();

	dbDelta( "CREATE TABLE {$t['people']} (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  msnv varchar(32) NOT NULL,
  name varchar(191) NOT NULL,
  name_key varchar(191) NOT NULL,
  phone_hash char(64) NOT NULL,
  unit varchar(191) NOT NULL DEFAULT '',
  PRIMARY KEY  (id),
  UNIQUE KEY msnv (msnv),
  KEY name_key (name_key)
) $charset;" );

	dbDelta( "CREATE TABLE {$t['votes']} (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  employee_id bigint(20) unsigned NOT NULL,
  mover_id bigint(20) unsigned NOT NULL,
  ip varchar(64) NOT NULL DEFAULT '',
  forwarded varchar(191) NOT NULL DEFAULT '',
  created_at datetime NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY voter_mover (employee_id,mover_id),
  KEY mover_id (mover_id)
) $charset;" );

	add_option( 'amv_open', '2027-03-01 00:00' );
	add_option( 'amv_close', '2027-04-30 23:59' );
	add_option( 'amv_force_open', '0' );
	add_option( 'amv_show_counts', '1' );
	update_option( 'amv_db_version', AMV_VER );

	amv_register_mover_type();
	flush_rewrite_rules();
}

function amv_maybe_upgrade() {
	if ( get_option( 'amv_db_version' ) !== AMV_VER ) {
		amv_install();
	}
}

<?php
/**
 * Small helpers shared by the templates.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function agm_img( $file ) {
	return get_template_directory_uri() . '/assets/img/' . $file;
}

/** Events still to come (or happening today), in the shape site.js expects. */
function agm_upcoming_events() {
	$q = new WP_Query( array(
		'post_type'      => 'su_kien',
		'post_status'    => 'publish',
		'posts_per_page' => 50,
		'meta_key'       => '_agm_at',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array( array(
			'key'     => '_agm_at',
			'value'   => wp_date( 'Y-m-d\T00:00', time() - DAY_IN_SECONDS ),
			'compare' => '>=',
		) ),
		'no_found_rows'  => true,
	) );
	$out = array();
	foreach ( $q->posts as $p ) {
		$ev = array(
			'at'    => get_post_meta( $p->ID, '_agm_at', true ),
			'title' => get_the_title( $p ),
		);
		if ( get_post_meta( $p->ID, '_agm_allday', true ) ) {
			$ev['allDay'] = true;
		}
		$place = get_post_meta( $p->ID, '_agm_place', true );
		if ( $place ) {
			$ev['place'] = $place;
		}
		$cd = get_post_meta( $p->ID, '_agm_cd', true );
		if ( $cd ) {
			$ev['cd'] = $cd;
		}
		$out[] = $ev;
	}
	return $out;
}

/** The page that uses the TOP 50 MOVERS template, if one exists. */
function agm_top50_url() {
	$custom = get_theme_mod( 'agm_top50_link' );
	if ( $custom ) {
		return $custom;
	}
	$pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'page-top50.php', 'number' => 1 ) );
	return $pages ? get_permalink( $pages[0] ) : home_url( '/' );
}

/** Link to a homepage section: a plain #anchor on the homepage, a full URL elsewhere. */
function agm_anchor( $id ) {
	return is_front_page() ? '#' . $id : home_url( '/#' . $id );
}

function agm_arrow( $type = 'line' ) {
	if ( 'chev' === $type ) {
		return '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 3l5 5-5 5"></path></svg>';
	}
	return '<svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 8h11M9 4l4 4-4 4"></path></svg>';
}

/** Fallback menu that mirrors the homepage sections. */
function agm_default_menu() {
	$items = array(
		'top'         => 'Về MOVE',
		'su-kien'     => 'Sự kiện',
		'movers'      => '50 Movers',
		'agris-thing' => 'AgriS-thing',
		'ban-tin'     => 'Bản tin',
	);
	foreach ( $items as $id => $label ) {
		echo '<a href="' . esc_url( agm_anchor( $id ) ) . '">' . esc_html( $label ) . '</a>';
	}
}

/** Strip <ul>/<li> from wp_nav_menu so links sit straight in the flex nav like the design. */
function agm_menu( $location ) {
	if ( has_nav_menu( $location ) ) {
		echo wp_kses_post( strip_tags( wp_nav_menu( array( 'theme_location' => $location, 'container' => false, 'echo' => false, 'depth' => 1 ) ), '<a>' ) );
	} else {
		agm_default_menu();
	}
}

/**
 * Events split for the homepage: [ 'live' => [...], 'soon' => [...] ], each item with
 * title, start, end (timestamps), place, url, img, count (thank-you notes, if any).
 */
function agm_event_groups() {
	$tz  = wp_timezone();
	$now = time();
	$out = array( 'live' => array(), 'soon' => array() );
	foreach ( get_posts( array( 'post_type' => 'su_kien', 'post_status' => 'publish', 'numberposts' => 100 ) ) as $p ) {
		$at = get_post_meta( $p->ID, '_agm_at', true );
		if ( ! $at ) { continue; }
		$start = ( new DateTime( $at, $tz ) )->getTimestamp();
		$end_s = get_post_meta( $p->ID, '_agm_end', true );
		if ( $end_s ) {
			$end = ( new DateTime( $end_s, $tz ) )->getTimestamp();
		} elseif ( get_post_meta( $p->ID, '_agm_allday', true ) ) {
			$end = ( new DateTime( substr( $at, 0, 10 ) . ' 23:59:59', $tz ) )->getTimestamp();
		} else {
			$end = $start + 3 * HOUR_IN_SECONDS;
		}
		if ( $end < $now ) { continue; }
		$page = (int) get_post_meta( $p->ID, '_agm_page', true );
		$ev   = array(
			'title' => get_the_title( $p ), 'start' => $start, 'end' => $end,
			'place' => get_post_meta( $p->ID, '_agm_place', true ),
			'url'   => $page ? get_permalink( $page ) : '',
			'img'   => get_the_post_thumbnail_url( $p, 'large' ),
			'count' => $page ? agm_thanks_count( $page ) : 0,
		);
		$out[ $start <= $now ? 'live' : 'soon' ][] = $ev;
	}
	usort( $out['live'], function ( $a, $b ) { return $a['end'] - $b['end']; } );
	usort( $out['soon'], function ( $a, $b ) { return $a['start'] - $b['start']; } );
	return $out;
}

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
/** Section switches (Tùy biến → AgriS MOVE): 'top50', 'wall'. Off by default. */
function agm_show( $what ) {
	return (bool) get_theme_mod( 'agm_show_' . $what, false );
}

function agm_podcast_url() {
	$u = get_post_type_archive_link( 'podcast' );
	return $u ? $u : home_url( '/agris-thing/' );
}

/** YouTube video id from a watch / youtu.be / shorts / embed link, or ''. */
function agm_yt_id( $url ) {
	if ( preg_match( '~(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/))([A-Za-z0-9_-]{11})~', (string) $url, $m ) ) {
		return $m[1];
	}
	return '';
}

/** Cover for a podcast entry: featured image, else YouTube thumbnail, else $fallback. */
function agm_pod_cover( $post, $fallback = '' ) {
	if ( $post && has_post_thumbnail( $post ) ) { return get_the_post_thumbnail_url( $post, 'agm-card' ); }
	$id = $post ? agm_yt_id( get_post_meta( $post->ID, '_agm_video', true ) ) : '';
	return $id ? 'https://i.ytimg.com/vi/' . $id . '/hqdefault.jpg' : $fallback;
}

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
	$hub = get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 1, 'meta_key' => '_wp_page_template', 'meta_value' => 'page-event-hub.php', 'fields' => 'ids' ) );
	$items = array(
		array( 'Vào vụ', $hub ? get_permalink( $hub[0] ) : agm_anchor( 'su-kien' ) ),
		array( 'Podcast', agm_podcast_url() ),
		array( 'Bản tin', agm_anchor( 'ban-tin' ) ),
	);
	if ( agm_show( 'top50' ) ) { array_splice( $items, 1, 0, array( array( 'TOP 50 MOVERS', agm_top50_url() ) ) ); }
	foreach ( $items as $i ) {
		echo '<a href="' . esc_url( $i[1] ) . '">' . esc_html( $i[0] ) . '</a>';
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
			'key'   => (bool) get_post_meta( $p->ID, '_agm_key', true ),
		);
		$out[ $start <= $now ? 'live' : 'soon' ][] = $ev;
	}
	usort( $out['live'], function ( $a, $b ) { return $a['end'] - $b['end']; } );
	usort( $out['soon'], function ( $a, $b ) { return $a['start'] - $b['start']; } );
	return $out;
}

/** URL of the first published page using a given template, or ''. */
function agm_page_by_template( $tpl ) {
	$p = get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 1, 'meta_key' => '_wp_page_template', 'meta_value' => $tpl, 'fields' => 'ids' ) );
	return $p ? get_permalink( $p[0] ) : '';
}

/** Navigation on sub-pages: homepage plus level-2 pages only (no homepage section anchors). */
function agm_sub_menu() {
	$hub   = get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 1, 'meta_key' => '_wp_page_template', 'meta_value' => 'page-event-hub.php', 'fields' => 'ids' ) );
	$items = array( array( 'Trang chủ', home_url( '/' ), false ) );
	if ( $hub ) { $items[] = array( 'Vào vụ', get_permalink( $hub[0] ), false ); }
	if ( agm_show( 'top50' ) ) { $items[] = array( 'TOP 50 MOVERS', agm_top50_url(), is_page_template( 'page-top50.php' ) || is_singular( 'mover' ) ); }
	$items[] = array( 'Podcast', agm_podcast_url(), is_post_type_archive( 'podcast' ) || is_singular( 'podcast' ) );
	$items[] = array( 'Bản tin', home_url( '/#ban-tin' ), false );
	foreach ( $items as $i ) {
		printf( '<a href="%s"%s>%s</a>', esc_url( $i[1] ), $i[2] ? ' aria-current="page"' : '', esc_html( $i[0] ) );
	}
}

/* Hidden sections: drop their links from any WP menu built in admin too. */
add_filter( 'wp_nav_menu_objects', function ( $items ) {
	if ( agm_show( 'top50' ) ) { return $items; }
	$t50 = untrailingslashit( agm_top50_url() );
	return array_filter( $items, function ( $it ) use ( $t50 ) {
		return untrailingslashit( $it->url ) !== $t50 && false === stripos( $it->title, 'TOP 50' );
	} );
} );

/** One-time: mark existing events that already have a featured image or sub-page as key events. */
add_action( 'init', function () {
	if ( get_option( 'agm_key_migrated' ) ) { return; }
	foreach ( get_posts( array( 'post_type' => 'su_kien', 'post_status' => 'any', 'numberposts' => -1 ) ) as $p ) {
		if ( has_post_thumbnail( $p ) || get_post_meta( $p->ID, '_agm_page', true ) ) { update_post_meta( $p->ID, '_agm_key', '1' ); }
	}
	update_option( 'agm_key_migrated', 1 );
} );

/** URL of the TOP 50 guide sub-page (falls back to the TOP 50 page). */
function agm_top50_guide_url() {
	$u = agm_page_by_template( 'page-top50-guide.php' );
	return $u ? $u : agm_top50_url();
}

/** Footer sitemap: one column per level-1 section with its level-2 pages. */
function agm_footer_sitemap() {
	$cols = array();
	$names = array( 'page-event-hub.php' => 'Giới thiệu', 'page-top50.php' => 'Giới thiệu' );
	$hubs = get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 10, 'meta_key' => '_wp_page_template', 'meta_value' => 'page-event-hub.php' ) );
	$t50  = ! agm_show( 'top50' ) ? array() : get_posts( array( 'post_type' => 'page', 'post_status' => 'publish', 'numberposts' => 1, 'meta_key' => '_wp_page_template', 'meta_value' => 'page-top50.php' ) );
	foreach ( array_merge( $hubs, $t50 ) as $h ) {
		$label = get_post_meta( $h->ID, 'menu_label', true ); $label = $label ? $label : get_the_title( $h );
		$links = array( array( 'Giới thiệu', get_permalink( $h ) ) );
		foreach ( agm_hub_children( $h->ID ) as $c ) {
			$tpl = get_page_template_slug( $c );
			if ( in_array( $tpl, array( 'page-thanks.php', 'page-postcard.php', 'page-avatar.php' ), true ) ) { continue; }
			$links[] = array( 'page-top50-apply.php' === $tpl ? 'Ứng cử' : get_the_title( $c ), get_permalink( $c ) );
		}
		if ( 'page-event-hub.php' === get_page_template_slug( $h ) ) {
			$add = array( array( 'Thư tay tiếp sức', get_permalink( $h ) . '#buu-thiep' ) );
			if ( agm_show( 'wall' ) ) { array_unshift( $add, array( 'Tiếp sức đội mình', get_permalink( $h ) . '#tiep-suc' ) ); }
			array_splice( $links, 1, 0, $add );
		}
		$cols[] = array( $label, $links );
	}
	$more = array( array( 'Trang chủ', home_url( '/' ) ) );
	if ( $ev = agm_page_by_template( 'page-events.php' ) ) { $more[] = array( 'Sự kiện', $ev ); }
	$more[] = array( 'Podcast', agm_podcast_url() );
	$more[] = array( 'Bản tin', home_url( '/#ban-tin' ) );
	$cols[] = array( 'Khám phá', $more );
	echo '<div class="foot-map">';
	foreach ( $cols as $c ) {
		echo '<nav class="foot-col" aria-label="' . esc_attr( $c[0] ) . '"><h4>' . esc_html( $c[0] ) . '</h4><ul>';
		foreach ( $c[1] as $l ) { echo '<li><a href="' . esc_url( $l[1] ) . '">' . esc_html( $l[0] ) . '</a></li>'; }
		echo '</ul></nav>';
	}
	echo '</div>';
}

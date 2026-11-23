<?php
/**
 * AgriS MOVE theme setup.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AGM_VER', '1.0.0' );

require get_template_directory() . '/inc/content-types.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/contact.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_image_size( 'agm-card', 900, 560, true );
	register_nav_menus( array(
		'primary' => 'Menu chính',
		'footer'  => 'Menu chân trang',
	) );
} );

add_action( 'wp_enqueue_scripts', function () {
	$uri = get_template_directory_uri() . '/assets';
	wp_enqueue_style( 'agm-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Plus+Jakarta+Sans:wght@700;800&family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap', array(), null );
	wp_enqueue_style( 'agm-site', $uri . '/css/site.css', array( 'agm-fonts' ), AGM_VER );
	wp_enqueue_style( 'agm-content', $uri . '/css/content.css', array( 'agm-site' ), AGM_VER );

	// ribbons, wave seams, scroll reveals and the event countdown
	wp_enqueue_script( 'agm-site', $uri . '/js/site.js', array(), AGM_VER, true );
	wp_localize_script( 'agm-site', 'AGM', array( 'events' => agm_upcoming_events(), 'ajax' => admin_url( 'admin-ajax.php' ), 'cfNonce' => wp_create_nonce( 'agm_contact' ) ) );

	if ( is_front_page() ) {
		wp_enqueue_script( 'agm-hero', $uri . '/js/hero.js', array(), AGM_VER, true );
		wp_add_inline_script( 'agm-hero', 'window.HERO_THEME = "B";', 'before' );
	}
	if ( is_page_template( 'page-top50.php' ) ) {
		wp_enqueue_style( 'agm-top50', $uri . '/css/top50.css', array( 'agm-site' ), AGM_VER );
		wp_enqueue_script( 'agm-top50', $uri . '/js/top50.js', array(), AGM_VER, true );
	}
} );

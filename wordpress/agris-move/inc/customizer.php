<?php
/**
 * Giao diện → Tuỳ biến → AgriS MOVE: the few homepage settings that change during the year.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'customize_register', function ( $wp ) {
	$wp->add_section( 'agm', array( 'title' => 'AgriS MOVE', 'priority' => 30 ) );

	$wp->add_setting( 'agm_quarter', array( 'default' => 'action', 'sanitize_callback' => 'sanitize_key' ) );
	$wp->add_control( 'agm_quarter', array(
		'section' => 'agm',
		'label'   => 'Chủ đề quý đang diễn ra (phát sáng trên dải chữ)',
		'type'    => 'select',
		'choices' => array( 'innovation' => 'MOVE INNOVATION', 'action' => 'MOVE ACTION', 'impact' => 'MOVE IMPACT', 'as-one' => 'MOVE AS ONE' ),
	) );

	$wp->add_setting( 'agm_top50_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp->add_control( new WP_Customize_Image_Control( $wp, 'agm_top50_image', array(
		'section' => 'agm',
		'label'   => 'Banner TOP 50 MOVERS (16:9)',
	) ) );

	$wp->add_setting( 'agm_top50_text', array(
		'default'           => 'Mỗi cá nhân đều là một “động lực tăng trưởng”. TOP 50 MOVERS vinh danh những con người không ngừng chủ động kiến tạo giá trị, đại diện cho văn hoá MOVE của AgriS.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp->add_control( 'agm_top50_text', array( 'section' => 'agm', 'label' => 'Đoạn giới thiệu trên banner', 'type' => 'textarea' ) );

	$wp->add_setting( 'agm_top50_link', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp->add_control( 'agm_top50_link', array(
		'section'     => 'agm',
		'label'       => 'Link "Khám phá câu chuyện"',
		'description' => 'Để trống để tự trỏ tới trang dùng mẫu TOP 50 MOVERS.',
		'type'        => 'url',
	) );

	$wp->add_setting( 'agm_contact_link', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp->add_control( 'agm_contact_link', array( 'section' => 'agm', 'label' => 'Link nút "Liên hệ chương trình" ở chân trang', 'type' => 'url' ) );
} );

function agm_top50_banner() {
	$img = get_theme_mod( 'agm_top50_image' );
	return $img ? $img : agm_img( 'top50-banner.jpg' );
}

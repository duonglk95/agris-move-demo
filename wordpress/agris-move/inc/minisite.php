<?php
/**
 * Event mini-sites. A page using the "Mini-site sự kiện" template is the hub; its child pages are the
 * activities (Lời tiếp sức, Bưu thiếp mùa vụ, Khoác áo Pit Crew, Khoảnh khắc Move Action) and appear in
 * the mini-site menu in page order.
 * Also: photo-contest entries (anh_du_thi) — published at once, checked afterwards by admins.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

const AGM_CENTERS  = array( 'HO', 'ComC', 'ProC', 'AgriC' );
const AGM_HASHTAGS = '#AGRIS #MOVEACTION #Niendo2627 #Khoanhkhacdoiminh';

/** The hub page of the mini-site the current page belongs to, or 0. */
function agm_hub_id( $id = 0 ) {
	$id = $id ? $id : ( is_page() ? get_queried_object_id() : 0 );
	if ( ! $id ) { return 0; }
	foreach ( array_merge( array( $id ), get_post_ancestors( $id ) ) as $pid ) {
		if ( 'page-event-hub.php' === get_page_template_slug( $pid ) ) { return (int) $pid; }
	}
	return 0;
}

function agm_hub_children( $hub ) {
	return get_pages( array( 'parent' => $hub, 'sort_column' => 'menu_order,post_title', 'post_status' => 'publish' ) );
}

function agm_hub_menu( $hub ) {
	$cur = get_queried_object_id();
	printf( '<a href="%s"%s>%s</a>', esc_url( get_permalink( $hub ) ), $cur === $hub ? ' aria-current="page"' : '', esc_html( get_post_meta( $hub, 'menu_label', true ) ? get_post_meta( $hub, 'menu_label', true ) : get_the_title( $hub ) ) );
	foreach ( agm_hub_children( $hub ) as $c ) {
		$tpl = get_page_template_slug( $c );
		if ( ! in_array( $tpl, array( 'page-pitcrew.php', 'page-contest.php' ), true ) ) { continue; }
		printf( '<a href="%s"%s>%s</a>', esc_url( get_permalink( $c ) ), $cur === $c->ID ? ' aria-current="page"' : '', esc_html( get_the_title( $c ) ) );
	}
}

/* ---------- photo contest entries ---------- */

add_action( 'init', function () {
	register_post_type( 'anh_du_thi', array(
		'label' => 'Ảnh dự thi', 'public' => false, 'show_ui' => true, 'menu_icon' => 'dashicons-format-image', 'menu_position' => 24,
		'supports' => array( 'title', 'editor', 'thumbnail' ), 'map_meta_cap' => true,
		'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		'labels' => array( 'name' => 'Ảnh dự thi', 'menu_name' => 'Ảnh dự thi', 'edit_item' => 'Xem ảnh dự thi' ),
	) );
} );
add_filter( 'amv_vote_types', function ( $t ) { $t[] = 'anh_du_thi'; return $t; } );
add_filter( 'amv_needs_assets', function ( $on ) { return $on || is_page_template( 'page-contest.php' ); } );

function agm_entries( $page_id, $order = 'new' ) {
	$posts = get_posts( array( 'post_type' => 'anh_du_thi', 'post_status' => 'publish', 'numberposts' => 300, 'meta_key' => '_agm_page', 'meta_value' => $page_id ) );
	if ( 'love' === $order && function_exists( 'amv_count_for' ) ) {
		usort( $posts, function ( $a, $b ) { return amv_count_for( $b->ID ) - amv_count_for( $a->ID ); } );
	}
	return $posts;
}

function agm_entry_data( $p ) {
	return array(
		'id'     => $p->ID,
		'title'  => get_the_title( $p ),
		'desc'   => $p->post_content,
		'author' => get_post_meta( $p->ID, '_agm_author', true ),
		'place'  => get_post_meta( $p->ID, '_agm_place', true ),
		'center' => get_post_meta( $p->ID, '_agm_center', true ),
		'framed' => get_the_post_thumbnail_url( $p, 'large' ),
		'raw'    => wp_get_attachment_image_url( (int) get_post_meta( $p->ID, '_agm_raw', true ), 'large' ),
	);
}

function agm_save_upload( $field, $parent ) {
	if ( empty( $_FILES[ $field ]['tmp_name'] ) || $_FILES[ $field ]['size'] > 6 * MB_IN_BYTES ) { return 0; }
	$check = wp_check_filetype_and_ext( $_FILES[ $field ]['tmp_name'], $_FILES[ $field ]['name'], array( 'jpg|jpeg' => 'image/jpeg', 'png' => 'image/png' ) );
	if ( empty( $check['type'] ) ) { return 0; }
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	$id = media_handle_upload( $field, $parent, array(), array( 'test_form' => false, 'mimes' => array( 'jpg|jpeg' => 'image/jpeg', 'png' => 'image/png' ) ) );
	return is_wp_error( $id ) ? 0 : $id;
}

function agm_contest_submit() {
	check_ajax_referer( 'agm_contest', 'nonce' );
	$ip  = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' );
	$key = 'agm_ct_' . md5( $ip );
	if ( get_transient( $key ) ) { wp_send_json_error( array( 'message' => 'Bạn vừa gửi một ảnh. Đợi một phút rồi gửi tiếp nhé.' ), 429 ); }
	$page   = absint( $_POST['page'] ?? 0 );
	$title  = sanitize_text_field( wp_unslash( $_POST['title'] ?? '' ) );
	$desc   = sanitize_textarea_field( wp_unslash( $_POST['desc'] ?? '' ) );
	$author = sanitize_text_field( wp_unslash( $_POST['author'] ?? '' ) );
	$place  = sanitize_text_field( wp_unslash( $_POST['place'] ?? '' ) );
	$center = sanitize_text_field( wp_unslash( $_POST['center'] ?? '' ) );
	if ( ! $page || 'page-contest.php' !== get_page_template_slug( $page ) || ! $title || ! $author || ! $place || ! in_array( $center, AGM_CENTERS, true ) ) {
		wp_send_json_error( array( 'message' => 'Bạn điền đủ tên ảnh, tên bạn, địa điểm và đơn vị nhé.' ), 400 );
	}
	$id = wp_insert_post( array( 'post_type' => 'anh_du_thi', 'post_status' => 'publish', 'post_title' => $title, 'post_content' => mb_substr( $desc, 0, 300 ) ) );
	$framed = agm_save_upload( 'framed', $id );
	$raw    = agm_save_upload( 'raw', $id );
	if ( ! $framed || ! $raw ) {
		wp_delete_post( $id, true );
		wp_send_json_error( array( 'message' => 'Ảnh chưa tải lên được. Bạn thử ảnh JPG hoặc PNG khác nhé.' ), 400 );
	}
	set_post_thumbnail( $id, $framed );
	foreach ( array( '_agm_page' => $page, '_agm_raw' => $raw, '_agm_author' => $author, '_agm_place' => $place, '_agm_center' => $center, '_agm_ip' => $ip ) as $k => $v ) {
		update_post_meta( $id, $k, $v );
	}
	set_transient( $key, 1, MINUTE_IN_SECONDS );
	$url = get_permalink( $page ) . '#anh-' . $id;
	wp_send_json_success( array(
		'url'   => $url,
		'share' => "Mình vừa gửi ảnh \"{$title}\" dự thi " . get_the_title( $page ) . ". Mọi người vào thả love ủng hộ mình nhé!\n{$url}\n" . AGM_HASHTAGS,
	) );
}
add_action( 'wp_ajax_agm_contest', 'agm_contest_submit' );
add_action( 'wp_ajax_nopriv_agm_contest', 'agm_contest_submit' );

add_filter( 'manage_anh_du_thi_posts_columns', function ( $c ) {
	return array( 'cb' => $c['cb'], 'agm_img' => 'Ảnh', 'title' => 'Tên ảnh', 'agm_who' => 'Tác giả', 'agm_love' => 'Love', 'date' => 'Ngày gửi' );
} );
add_action( 'manage_anh_du_thi_posts_custom_column', function ( $col, $id ) {
	if ( 'agm_img' === $col ) { echo get_the_post_thumbnail( $id, array( 80, 80 ) ); }
	if ( 'agm_who' === $col ) { echo esc_html( get_post_meta( $id, '_agm_author', true ) . ' · ' . get_post_meta( $id, '_agm_center', true ) . ' · ' . get_post_meta( $id, '_agm_place', true ) ); }
	if ( 'agm_love' === $col && function_exists( 'amv_count_for' ) ) { echo (int) amv_count_for( $id ); }
}, 10, 2 );
add_action( 'admin_notices', function () {
	$s = get_current_screen();
	if ( $s && 'edit-anh_du_thi' === $s->id ) {
		echo '<div class="notice notice-info"><p>Ảnh dự thi hiện ngay khi gửi. Ảnh không phù hợp: rê chuột vào dòng và chọn <strong>Thùng rác</strong> để gỡ khỏi trang.</p></div>';
	}
} );

/* frames: optional PNG overlays uploaded in Customizer; without them the built-in mock frame is drawn */
add_action( 'customize_register', function ( $wp ) {
	$wp->add_section( 'agm_frames', array( 'title' => 'Khung ảnh sự kiện', 'priority' => 45 ) );
	foreach ( array( 'agm_ct_time' => 'Cuộc thi ảnh – thời gian', 'agm_ct_limit' => 'Cuộc thi ảnh – số ảnh mỗi người', 'agm_ct_team' => 'Cuộc thi ảnh – điều kiện giải tập thể' ) as $k => $label ) {
		$wp->add_setting( $k, array( 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp->add_control( $k, array( 'label' => $label, 'section' => 'agm_frames', 'type' => 'text' ) );
	}
	foreach ( array( 'agm_frame_contest' => 'Khung ảnh dự thi (PNG nền trong, 1080×1350)', 'agm_frame_avatar' => 'Khung avatar (PNG nền trong, 1080×1080)' ) as $k => $label ) {
		$wp->add_setting( $k, array( 'sanitize_callback' => 'esc_url_raw' ) );
		$wp->add_control( new WP_Customize_Image_Control( $wp, $k, array( 'label' => $label, 'section' => 'agm_frames' ) ) );
	}
} );

add_action( 'wp_enqueue_scripts', function () {
	if ( is_page_template( array( 'page-contest.php', 'page-postcard.php', 'page-avatar.php', 'page-event-hub.php' ) ) ) {
		$uri = get_template_directory_uri() . '/assets';
		wp_enqueue_script( 'agm-studio', $uri . '/js/studio.js', array( 'agm-site' ), AGM_VER, true );
		wp_localize_script( 'agm-studio', 'AGS', array(
			'ajax'     => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'agm_contest' ),
			'tags'     => AGM_HASHTAGS,
			'event'    => agm_hub_id() ? get_the_title( agm_hub_id() ) : 'Vào vụ',
			'frameCt'  => get_theme_mod( 'agm_frame_contest', agm_img( 'frame-contest.png' ) ),
			'frameAv'  => get_theme_mod( 'agm_frame_avatar' ),
			'logo'     => agm_img( 'logo-agris-move.png' ),
			'stamps'   => array_map( function ( $i ) { return agm_img( "stamp-$i.webp" ); }, range( 1, 5 ) ),
		) );
	}
} );

/* ---------- postcard photo library (admin-curated, e.g. last season's photos) ---------- */
add_action( 'init', function () {
	register_post_type( 'anh_buu_thiep', array(
		'label' => 'Ảnh bưu thiếp', 'public' => false, 'show_ui' => true, 'show_in_menu' => 'edit.php?post_type=anh_du_thi',
		'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
		'labels' => array( 'name' => 'Ảnh bưu thiếp', 'add_new_item' => 'Thêm ảnh bưu thiếp', 'edit_item' => 'Sửa ảnh bưu thiếp', 'featured_image' => 'Ảnh', 'set_featured_image' => 'Chọn ảnh' ),
	) );
} );
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'agm_pc_credit', 'Thông tin ảnh', function ( $post ) {
		wp_nonce_field( 'agm_pc_save', 'agm_pc_nonce' );
		foreach ( array( '_agm_author' => 'Tác giả', '_agm_place' => 'Địa điểm / đơn vị' ) as $k => $label ) {
			printf( '<p><label><strong>%s</strong><br><input type="text" class="widefat" name="%s" value="%s"></label></p>', esc_html( $label ), esc_attr( $k ), esc_attr( get_post_meta( $post->ID, $k, true ) ) );
		}
		echo '<p class="description">Tiêu đề bài = tên ảnh. Ảnh đại diện = ảnh dùng làm bưu thiếp. Thứ tự (Order) nhỏ hiện trước.</p>';
	}, 'anh_buu_thiep', 'normal', 'high' );
} );
add_action( 'save_post_anh_buu_thiep', function ( $id ) {
	if ( ! isset( $_POST['agm_pc_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['agm_pc_nonce'] ) ), 'agm_pc_save' ) || ! current_user_can( 'edit_post', $id ) ) { return; }
	foreach ( array( '_agm_author', '_agm_place' ) as $k ) { update_post_meta( $id, $k, sanitize_text_field( wp_unslash( $_POST[ $k ] ?? '' ) ) ); }
} );
add_filter( 'manage_anh_buu_thiep_posts_columns', function ( $c ) { return array( 'cb' => $c['cb'], 'agm_img' => 'Ảnh', 'title' => 'Tên ảnh', 'agm_who' => 'Tác giả · địa điểm' ); } );
add_action( 'manage_anh_buu_thiep_posts_custom_column', function ( $col, $id ) {
	if ( 'agm_img' === $col ) { echo get_the_post_thumbnail( $id, array( 90, 90 ) ); }
	if ( 'agm_who' === $col ) { echo esc_html( trim( get_post_meta( $id, '_agm_author', true ) . ' · ' . get_post_meta( $id, '_agm_place', true ), ' ·' ) ); }
}, 10, 2 );

/** Photos for the postcard picker: library first, then approved contest photos. */
function agm_postcard_photos() {
	$out = array();
	foreach ( get_posts( array( 'post_type' => 'anh_buu_thiep', 'post_status' => 'publish', 'numberposts' => 60, 'orderby' => 'menu_order date', 'order' => 'ASC' ) ) as $p ) {
		$out[] = array( 'title' => get_the_title( $p ), 'author' => get_post_meta( $p->ID, '_agm_author', true ), 'place' => get_post_meta( $p->ID, '_agm_place', true ), 'raw' => get_the_post_thumbnail_url( $p, 'large' ) );
	}
	$hub     = agm_hub_id();
	$contest = $hub ? get_posts( array( 'post_type' => 'page', 'post_parent' => $hub, 'meta_key' => '_wp_page_template', 'meta_value' => 'page-contest.php', 'numberposts' => 1, 'fields' => 'ids' ) ) : array();
	if ( $contest ) { $out = array_merge( $out, array_map( 'agm_entry_data', agm_entries( $contest[0], 'love' ) ) ); }
	return array_values( array_filter( $out, function ( $p ) { return ! empty( $p['raw'] ); } ) );
}

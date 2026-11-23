<?php
/**
 * Lời cảm ơn: anyone can send one from a page using the "Lời cảm ơn" template; it waits as Pending
 * until an admin publishes it (Lời cảm ơn → tick → Bulk actions → Edit → Status: Published, or open and Publish).
 */
add_action( 'init', function () {
	register_post_type( 'loi_cam_on', array(
		'label' => 'Lời cảm ơn', 'public' => false, 'show_ui' => true, 'menu_icon' => 'dashicons-heart', 'menu_position' => 23,
		'supports' => array( 'title', 'editor' ), 'map_meta_cap' => true,
		'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		'labels' => array( 'name' => 'Lời cảm ơn', 'menu_name' => 'Lời cảm ơn', 'edit_item' => 'Duyệt lời cảm ơn' ),
	) );
} );

function agm_thanks_count( $page_id ) {
	global $wpdb;
	return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->posts} p JOIN {$wpdb->postmeta} m ON m.post_id = p.ID AND m.meta_key = '_agm_page' WHERE p.post_type = 'loi_cam_on' AND p.post_status = 'publish' AND m.meta_value = %d", $page_id ) );
}

function agm_thanks_list( $page_id, $n = 60 ) {
	return get_posts( array( 'post_type' => 'loi_cam_on', 'post_status' => 'publish', 'numberposts' => $n, 'meta_key' => '_agm_page', 'meta_value' => $page_id ) );
}

function agm_thanks_submit() {
	check_ajax_referer( 'agm_thanks', 'nonce' );
	$page = absint( $_POST['page'] ?? 0 );
	$to   = sanitize_text_field( wp_unslash( $_POST['to'] ?? '' ) );
	$from = sanitize_text_field( wp_unslash( $_POST['from'] ?? '' ) );
	$msg  = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
	if ( ! $page || 'page' !== get_post_type( $page ) || '' === $msg ) {
		wp_send_json_error( array( 'message' => 'Bạn viết lời cảm ơn trước đã nhé.' ), 400 );
	}
	$id = wp_insert_post( array( 'post_type' => 'loi_cam_on', 'post_status' => 'pending', 'post_title' => 'Gửi ' . ( $to ? $to : 'đồng đội' ) . ' – ' . ( $from ? $from : 'Ẩn danh' ), 'post_content' => mb_substr( $msg, 0, 600 ) ) );
	update_post_meta( $id, '_agm_page', $page );
	update_post_meta( $id, '_agm_to', $to );
	update_post_meta( $id, '_agm_from', $from );
	update_post_meta( $id, '_agm_ip', sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	wp_send_json_success( array( 'message' => 'Đã gửi! Lời cảm ơn sẽ lên tường sau khi ban tổ chức duyệt.' ) );
}
add_action( 'wp_ajax_agm_thanks', 'agm_thanks_submit' );
add_action( 'wp_ajax_nopriv_agm_thanks', 'agm_thanks_submit' );

add_filter( 'manage_loi_cam_on_posts_columns', function ( $c ) {
	return array( 'cb' => $c['cb'], 'title' => 'Người gửi / nhận', 'agm_msg' => 'Lời cảm ơn', 'agm_page' => 'Sự kiện', 'agm_st' => 'Trạng thái', 'date' => 'Ngày gửi' );
} );
add_action( 'manage_loi_cam_on_posts_custom_column', function ( $col, $id ) {
	if ( 'agm_msg' === $col ) { echo esc_html( wp_trim_words( get_post_field( 'post_content', $id ), 40 ) ); }
	if ( 'agm_page' === $col ) { echo esc_html( get_the_title( (int) get_post_meta( $id, '_agm_page', true ) ) ); }
	if ( 'agm_st' === $col ) { echo 'publish' === get_post_status( $id ) ? 'Đã lên tường' : '<strong style="color:#b26a00">Chờ duyệt</strong>'; }
}, 10, 2 );
add_filter( 'post_row_actions', function ( $a, $post ) {
	if ( 'loi_cam_on' === $post->post_type && 'pending' === $post->post_status ) {
		$a = array( 'agm_ok' => sprintf( '<a href="%s"><strong>Duyệt</strong></a>', esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=agm_thanks_ok&id=' . $post->ID ), 'agm_ok_' . $post->ID ) ) ) ) + $a;
	}
	return $a;
}, 10, 2 );
add_action( 'admin_post_agm_thanks_ok', function () {
	$id = absint( $_GET['id'] ?? 0 );
	check_admin_referer( 'agm_ok_' . $id );
	if ( current_user_can( 'edit_post', $id ) ) { wp_publish_post( $id ); }
	wp_safe_redirect( admin_url( 'edit.php?post_type=loi_cam_on' ) );
	exit;
} );
add_filter( 'bulk_actions-edit-loi_cam_on', function ( $a ) { return array( 'agm_ok' => 'Duyệt (lên tường)' ) + $a; } );
add_filter( 'handle_bulk_actions-edit-loi_cam_on', function ( $url, $action, $ids ) {
	if ( 'agm_ok' === $action ) { foreach ( $ids as $id ) { if ( current_user_can( 'edit_post', $id ) ) { wp_publish_post( $id ); } } }
	return $url;
}, 10, 3 );
add_action( 'admin_menu', function () {
	$n = (int) wp_count_posts( 'loi_cam_on' )->pending;
	if ( $n ) {
		global $menu;
		foreach ( $menu as $i => $m ) {
			if ( 'edit.php?post_type=loi_cam_on' === $m[2] ) { $menu[ $i ][0] .= ' <span class="awaiting-mod">' . $n . '</span>'; }
		}
	}
} );

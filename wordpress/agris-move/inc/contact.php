<?php
/**
 * "Liên hệ chương trình": messages stored as a private post type, listed in admin, exportable to CSV (Excel).
 */
add_action( 'init', function () {
	register_post_type( 'lien_he', array(
		'label' => 'Lời nhắn liên hệ', 'public' => false, 'show_ui' => true, 'menu_icon' => 'dashicons-email-alt',
		'supports' => array( 'title', 'editor' ), 'capability_type' => 'post',
		'capabilities' => array( 'create_posts' => 'do_not_allow' ), 'map_meta_cap' => true,
		'labels' => array( 'name' => 'Lời nhắn liên hệ', 'menu_name' => 'Lời nhắn liên hệ' ),
	) );
} );

function agm_contact_submit() {
	check_ajax_referer( 'agm_contact', 'nonce' );
	$name  = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$topic = sanitize_text_field( wp_unslash( $_POST['topic'] ?? '' ) );
	$msg   = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
	if ( '' === $topic || '' === $msg ) {
		wp_send_json_error( array( 'message' => 'Bạn chọn vấn đề và viết nội dung lời nhắn nhé.' ), 400 );
	}
	$id = wp_insert_post( array( 'post_type' => 'lien_he', 'post_status' => 'private', 'post_title' => $topic . ' – ' . ( $name ? $name : 'Ẩn danh' ), 'post_content' => $msg ) );
	update_post_meta( $id, '_agm_name', $name );
	update_post_meta( $id, '_agm_topic', $topic );
	update_post_meta( $id, '_agm_ip', sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	wp_send_json_success( array( 'message' => 'Đã gửi. Cảm ơn bạn, ban tổ chức sẽ đọc và phản hồi sớm.' ) );
}
add_action( 'wp_ajax_agm_contact', 'agm_contact_submit' );
add_action( 'wp_ajax_nopriv_agm_contact', 'agm_contact_submit' );

add_filter( 'manage_lien_he_posts_columns', function ( $c ) {
	return array( 'cb' => $c['cb'], 'title' => 'Lời nhắn', 'agm_msg' => 'Nội dung', 'date' => 'Ngày gửi' );
} );
add_action( 'manage_lien_he_posts_custom_column', function ( $col, $id ) {
	if ( 'agm_msg' === $col ) {
		echo esc_html( wp_trim_words( get_post_field( 'post_content', $id ), 30 ) );
	}
}, 10, 2 );

add_action( 'restrict_manage_posts', function ( $type ) {
	if ( 'lien_he' === $type ) {
		printf( '<a class="button" href="%s">Xuất Excel (CSV)</a>', esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=agm_contact_csv' ), 'agm_contact_csv' ) ) );
	}
} );
add_action( 'admin_post_agm_contact_csv', function () {
	if ( ! current_user_can( 'edit_posts' ) ) { wp_die( 'Không có quyền.' ); }
	check_admin_referer( 'agm_contact_csv' );
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="loi-nhan-lien-he-' . wp_date( 'Ymd' ) . '.csv"' );
	$out = fopen( 'php://output', 'w' );
	fwrite( $out, "\xEF\xBB\xBF" );
	fputcsv( $out, array( 'Ngày gửi', 'Tên người gửi', 'Vấn đề', 'Nội dung', 'IP' ) );
	foreach ( get_posts( array( 'post_type' => 'lien_he', 'post_status' => 'any', 'numberposts' => -1 ) ) as $p ) {
		fputcsv( $out, array( get_the_date( 'Y-m-d H:i', $p ), get_post_meta( $p->ID, '_agm_name', true ), get_post_meta( $p->ID, '_agm_topic', true ), $p->post_content, get_post_meta( $p->ID, '_agm_ip', true ) ) );
	}
	exit;
} );

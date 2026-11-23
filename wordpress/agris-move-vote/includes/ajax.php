<?php
/**
 * Front-end endpoints (admin-ajax.php). Every action works without a WordPress account.
 *  amv_find   – name suggestions while typing
 *  amv_login  – check name + MSNV + phone, start a session
 *  amv_logout – end the session
 *  amv_me     – who is signed in, what they have loved, current counts
 *  amv_vote   – love / take back a love for one MOVER
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function amv_ajax( $action, $cb ) {
	add_action( 'wp_ajax_' . $action, $cb );
	add_action( 'wp_ajax_nopriv_' . $action, $cb );
}

function amv_check_nonce() {
	if ( ! check_ajax_referer( 'amv', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => 'Phiên làm việc đã hết hạn. Hãy tải lại trang rồi thử lại.' ), 403 );
	}
}

function amv_me_payload( $person ) {
	$show = '1' === get_option( 'amv_show_counts', '1' );
	return array(
		'person' => $person ? array( 'name' => $person->name, 'unit' => $person->unit ) : null,
		'voted'  => $person ? amv_voted_by( $person->id ) : array(),
		'counts' => $show ? (object) amv_all_counts() : null,
		'state'  => amv_vote_state(),
	);
}

amv_ajax( 'amv_find', function () {
	amv_check_nonce();
	$q = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
	wp_send_json_success( amv_find_names( $q ) );
} );

amv_ajax( 'amv_login', function () {
	amv_check_nonce();
	$ip_key = 'amv_fail_' . md5( amv_client_ip() );
	$fails  = (int) get_transient( $ip_key );
	if ( $fails >= 10 ) {
		wp_send_json_error( array( 'message' => 'Bạn đã nhập sai nhiều lần. Hãy thử lại sau 15 phút.' ), 429 );
	}
	$name  = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$msnv  = isset( $_POST['msnv'] ) ? sanitize_text_field( wp_unslash( $_POST['msnv'] ) ) : '';
	$phone = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	if ( '' === $name || '' === $msnv || '' === $phone ) {
		wp_send_json_error( array( 'message' => 'Hãy nhập đủ họ tên, MSNV và số điện thoại.' ), 400 );
	}
	$person = amv_check_identity( $name, $msnv, $phone );
	if ( ! $person ) {
		set_transient( $ip_key, $fails + 1, 15 * MINUTE_IN_SECONDS );
		wp_send_json_error( array( 'message' => 'Thông tin chưa khớp với danh sách nhân viên. Hãy kiểm tra lại họ tên, MSNV và số điện thoại.' ), 401 );
	}
	delete_transient( $ip_key );
	amv_set_session( (int) $person->id );
	wp_send_json_success( amv_me_payload( $person ) );
} );

amv_ajax( 'amv_logout', function () {
	amv_check_nonce();
	amv_clear_session();
	wp_send_json_success( amv_me_payload( null ) );
} );

amv_ajax( 'amv_me', function () {
	amv_check_nonce();
	wp_send_json_success( amv_me_payload( amv_current_person() ) );
} );

amv_ajax( 'amv_vote', function () {
	amv_check_nonce();
	$person = amv_current_person();
	if ( ! $person ) {
		wp_send_json_error( array( 'message' => 'Hãy đăng nhập để bình chọn.', 'login' => true ), 401 );
	}
	$state = amv_vote_state();
	if ( 'open' !== $state ) {
		wp_send_json_error( array( 'message' => 'before' === $state ? 'Cổng bình chọn chưa mở.' : 'Cổng bình chọn đã đóng.' ), 403 );
	}
	$mover = isset( $_POST['mover'] ) ? absint( $_POST['mover'] ) : 0;
	if ( ! $mover || ! in_array( get_post_type( $mover ), (array) apply_filters( 'amv_vote_types', array( 'mover' ) ), true ) || 'publish' !== get_post_status( $mover ) ) {
		wp_send_json_error( array( 'message' => 'Không tìm thấy hồ sơ MOVER này.' ), 404 );
	}
	$on = amv_toggle_vote( (int) $person->id, $mover );
	$show = '1' === get_option( 'amv_show_counts', '1' );
	wp_send_json_success( array( 'on' => $on, 'count' => $show ? amv_count_for( $mover ) : null ) );
} );

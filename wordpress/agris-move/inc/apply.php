<?php
/**
 * TOP 50 MOVERS – candidacy form (page template "TOP 50 – Ứng cử").
 * Identity is checked against the HR list of the voting plugin; one dossier per MSNV.
 * Dossiers are kept as private posts (ho_so_mover); admins review, export CSV and turn a valid one into a MOVER draft.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

const AGM_SPIRITS  = array( 'Action', 'Innovation', 'Impact', 'As One' );
const AGM_HS_STAT  = array( 'new' => 'Chờ thẩm định', 'ok' => 'Hợp lệ', 'fix' => 'Cần bổ sung', 'no' => 'Không hợp lệ' );

function agm_apply_window() { return array( get_theme_mod( 'agm_apply_from', '2026-12-01' ), get_theme_mod( 'agm_apply_to', '2027-02-28' ) ); }
function agm_apply_open() {
	if ( '1' === get_option( 'amv_force_open' ) ) { return true; }
	list( $f, $t ) = agm_apply_window(); $d = current_time( 'Y-m-d' );
	return $d >= $f && $d <= $t;
}

add_action( 'init', function () {
	register_post_type( 'ho_so_mover', array(
		'label' => 'Hồ sơ ứng cử', 'public' => false, 'show_ui' => true, 'menu_icon' => 'dashicons-id-alt', 'menu_position' => 26,
		'supports' => array( 'title', 'editor' ), 'map_meta_cap' => true, 'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		'labels' => array( 'name' => 'Hồ sơ ứng cử', 'menu_name' => 'Hồ sơ ứng cử TOP 50', 'edit_item' => 'Thẩm định hồ sơ' ),
	) );
} );

add_action( 'customize_register', function ( $wp ) {
	$wp->add_section( 'agm_apply', array( 'title' => 'TOP 50 – Ứng cử', 'priority' => 47 ) );
	foreach ( array( 'agm_apply_from' => array( 'Mở nhận hồ sơ từ', '2026-12-01' ), 'agm_apply_to' => array( 'Hạn chót nộp hồ sơ', '2027-02-28' ) ) as $k => $v ) {
		$wp->add_setting( $k, array( 'default' => $v[1], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp->add_control( $k, array( 'type' => 'date', 'section' => 'agm_apply', 'label' => $v[0], 'description' => 'Bật "Cho phép bình chọn ngay" trong plugin bình chọn cũng mở form để thử.' ) );
	}
} );

function agm_hs_find( $msnv ) {
	$p = get_posts( array( 'post_type' => 'ho_so_mover', 'post_status' => 'private', 'numberposts' => 1, 'meta_key' => '_hs_msnv', 'meta_value' => $msnv ) );
	return $p ? $p[0] : null;
}
function agm_hs_code( $id ) { return 'HS-' . str_pad( (string) $id, 4, '0', STR_PAD_LEFT ); }

add_action( 'wp_ajax_agm_apply', 'agm_apply_submit' ); add_action( 'wp_ajax_nopriv_agm_apply', 'agm_apply_submit' );
function agm_apply_submit() {
	check_ajax_referer( 'agm_apply', 'nonce' );
	if ( ! agm_apply_open() ) { wp_send_json_error( array( 'message' => 'Chưa đến thời gian nhận hồ sơ.' ), 400 ); }
	if ( ! function_exists( 'amv_check_identity' ) ) { wp_send_json_error( array( 'message' => 'Chưa bật plugin danh sách nhân viên.' ), 500 ); }
	$g = function ( $k, $area = false ) { $v = wp_unslash( $_POST[ $k ] ?? '' ); return $area ? sanitize_textarea_field( $v ) : sanitize_text_field( $v ); };
	$row = amv_check_identity( $g( 'name' ), $g( 'msnv' ), $g( 'phone' ) );
	if ( ! $row ) { wp_send_json_error( array( 'message' => 'Họ tên, MSNV hoặc số điện thoại chưa khớp với danh sách HR. Bạn kiểm tra lại nhé.', 'field' => 'msnv' ), 403 ); }
	if ( $old = agm_hs_find( $row->msnv ) ) {
		wp_send_json_error( array( 'message' => 'Bạn đã nộp hồ sơ ' . agm_hs_code( $old->ID ) . '. Mỗi người một hồ sơ; cần sửa, bạn bấm Liên hệ chương trình ở cuối trang.' ), 409 );
	}
	$spirits = array_values( array_intersect( array_map( 'sanitize_text_field', wp_unslash( (array) ( $_POST['spirits'] ?? array() ) ) ), AGM_SPIRITS ) );
	$f = array( 'dept' => $g( 'dept' ), 'center' => $g( 'center' ), 'email' => sanitize_email( wp_unslash( $_POST['email'] ?? '' ) ), 'title' => $g( 'title' ),
		's' => $g( 's', true ), 't' => $g( 't', true ), 'a' => $g( 'a', true ), 'r' => $g( 'r', true ), 'video' => esc_url_raw( wp_unslash( $_POST['video'] ?? '' ) ), 'via' => $g( 'via' ) );
	if ( ! in_array( $f['center'], AGM_CENTERS, true ) || ! is_email( $f['email'] ) || ! $f['dept'] || ! $f['title'] || ! $f['s'] || ! $f['t'] || ! $f['a'] || ! $f['r'] || ! $spirits || empty( $_POST['consent'] ) ) {
		wp_send_json_error( array( 'message' => 'Bạn điền đủ các mục bắt buộc và đồng ý điều khoản nhé.' ), 400 );
	}
	$has_file = ! empty( $_FILES['video_file']['tmp_name'] );
	if ( ! $f['video'] && ! $has_file ) { wp_send_json_error( array( 'message' => 'Bạn gửi video bằng link hoặc tải file lên nhé.', 'field' => 'video' ), 400 ); }
	$body = "<h3>Bối cảnh</h3>\n" . wpautop( esc_html( $f['s'] ) ) . "<h3>Nhiệm vụ</h3>\n" . wpautop( esc_html( $f['t'] ) ) . "<h3>Hành động</h3>\n" . wpautop( esc_html( $f['a'] ) ) . "<h3>Kết quả</h3>\n" . wpautop( esc_html( $f['r'] ) );
	$id = wp_insert_post( array( 'post_type' => 'ho_so_mover', 'post_status' => 'private', 'post_title' => $row->name . ' – ' . $f['title'], 'post_content' => $body ) );
	require_once ABSPATH . 'wp-admin/includes/image.php'; require_once ABSPATH . 'wp-admin/includes/file.php'; require_once ABSPATH . 'wp-admin/includes/media.php';
	$base = $f['center'] . '_' . wp_date( 'Ymd-Hi' ) . '_' . sanitize_title( remove_accents( $row->name ) ) . '_' . $id;
	foreach ( array( 'photo' => array( 'jpg|jpeg|png' => 'image/jpeg', 'png' => 'image/png' ), 'video_file' => array( 'mp4' => 'video/mp4', 'mov' => 'video/quicktime' ) ) as $field => $mimes ) {
		if ( empty( $_FILES[ $field ]['tmp_name'] ) ) { continue; }
		$ext = strtolower( pathinfo( $_FILES[ $field ]['name'], PATHINFO_EXTENSION ) );
		$_FILES[ $field ]['name'] = $base . '_' . ( 'photo' === $field ? 'anh' : 'video' ) . '.' . $ext;
		$aid = media_handle_upload( $field, $id, array(), array( 'test_form' => false, 'mimes' => array( 'jpg|jpeg' => 'image/jpeg', 'png' => 'image/png', 'mp4' => 'video/mp4', 'mov' => 'video/quicktime' ) ) );
		if ( ! is_wp_error( $aid ) ) { update_post_meta( $id, 'photo' === $field ? '_hs_photo' : '_hs_video_file', $aid ); }
	}
	$meta = array( '_hs_msnv' => $row->msnv, '_hs_name' => $row->name, '_hs_unit' => $row->unit, '_hs_dept' => $f['dept'], '_hs_center' => $f['center'], '_hs_email' => $f['email'],
		'_hs_title' => $f['title'], '_hs_spirits' => $spirits, '_hs_video' => $f['video'], '_hs_via' => $f['via'], '_hs_status' => 'new', '_hs_ip' => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	foreach ( $meta as $k => $v ) { update_post_meta( $id, $k, $v ); }
	wp_send_json_success( array( 'code' => agm_hs_code( $id ), 'name' => $row->name ) );
}

/* ---------- admin ---------- */
add_filter( 'manage_ho_so_mover_posts_columns', function ( $c ) {
	return array( 'cb' => $c['cb'], 'hs_code' => 'Mã', 'title' => 'Ứng viên – câu chuyện', 'hs_info' => 'Đơn vị · tinh thần', 'hs_st' => 'Trạng thái', 'date' => 'Ngày nộp' );
} );
add_action( 'manage_ho_so_mover_posts_custom_column', function ( $col, $id ) {
	if ( 'hs_code' === $col ) { echo esc_html( agm_hs_code( $id ) ); }
	if ( 'hs_info' === $col ) { echo esc_html( get_post_meta( $id, '_hs_center', true ) . ' · ' . get_post_meta( $id, '_hs_dept', true ) . ' · ' . implode( ', ', (array) get_post_meta( $id, '_hs_spirits', true ) ) ); }
	if ( 'hs_st' === $col ) {
		$s = get_post_meta( $id, '_hs_status', true ) ?: 'new';
		$c = array( 'new' => '#b26a00', 'ok' => '#0b6b3a', 'fix' => '#1b6fb5', 'no' => '#888' )[ $s ];
		echo '<strong style="color:' . esc_attr( $c ) . '">' . esc_html( AGM_HS_STAT[ $s ] ) . '</strong>';
		if ( $m = get_post_meta( $id, '_hs_mover', true ) ) { echo '<br><a href="' . esc_url( get_edit_post_link( $m ) ) . '">Đã tạo MOVER</a>'; }
	}
}, 10, 2 );
add_filter( 'bulk_actions-edit-ho_so_mover', function ( $a ) { unset( $a['edit'] ); foreach ( AGM_HS_STAT as $k => $l ) { $a[ 'hs_' . $k ] = 'Đánh dấu: ' . $l; } $a['hs_mover'] = 'Tạo MOVER từ hồ sơ hợp lệ'; return $a; } );
add_filter( 'handle_bulk_actions-edit-ho_so_mover', function ( $url, $action, $ids ) {
	foreach ( $ids as $id ) {
		if ( ! current_user_can( 'edit_post', $id ) ) { continue; }
		if ( preg_match( '/^hs_(new|ok|fix|no)$/', $action, $m ) ) { update_post_meta( $id, '_hs_status', $m[1] ); }
		if ( 'hs_mover' === $action ) { agm_hs_to_mover( $id ); }
	}
	return $url;
}, 10, 3 );
function agm_hs_to_mover( $id ) {
	if ( 'ok' !== get_post_meta( $id, '_hs_status', true ) || get_post_meta( $id, '_hs_mover', true ) || ! post_type_exists( 'mover' ) ) { return; }
	$p = get_post( $id );
	$mid = wp_insert_post( array( 'post_type' => 'mover', 'post_status' => 'draft', 'post_title' => get_post_meta( $id, '_hs_name', true ), 'post_excerpt' => get_post_meta( $id, '_hs_title', true ), 'post_content' => $p->post_content ) );
	update_post_meta( $mid, '_amv_unit', trim( get_post_meta( $id, '_hs_dept', true ) . ' – ' . get_post_meta( $id, '_hs_center', true ), ' –' ) );
	update_post_meta( $mid, '_amv_msnv', get_post_meta( $id, '_hs_msnv', true ) );
	$v = get_post_meta( $id, '_hs_video', true ); $vf = get_post_meta( $id, '_hs_video_file', true );
	update_post_meta( $mid, '_amv_video', $v ? $v : ( $vf ? wp_get_attachment_url( $vf ) : '' ) );
	if ( $ph = get_post_meta( $id, '_hs_photo', true ) ) { set_post_thumbnail( $mid, $ph ); }
	update_post_meta( $id, '_hs_mover', $mid );
}
add_action( 'add_meta_boxes', function () { add_meta_box( 'hs_info', 'Thông tin hồ sơ', 'agm_hs_box', 'ho_so_mover', 'side', 'high' ); } );
function agm_hs_box( $post ) {
	wp_nonce_field( 'agm_hs_save', 'agm_hs_nonce' );
	$m = function ( $k ) use ( $post ) { return get_post_meta( $post->ID, $k, true ); };
	echo '<p><strong>' . esc_html( agm_hs_code( $post->ID ) ) . '</strong> · MSNV ' . esc_html( $m( '_hs_msnv' ) ) . '</p>';
	echo '<p>' . esc_html( $m( '_hs_dept' ) . ' · ' . $m( '_hs_center' ) ) . '<br>' . esc_html( $m( '_hs_email' ) ) . '</p>';
	echo '<p>Tinh thần: ' . esc_html( implode( ', ', (array) $m( '_hs_spirits' ) ) ) . '</p>';
	echo '<p>Hình thức: ' . esc_html( $m( '_hs_via' ) ) . '</p>';
	if ( $m( '_hs_video' ) ) { echo '<p><a href="' . esc_url( $m( '_hs_video' ) ) . '" target="_blank" rel="noopener">Xem video (link)</a></p>'; }
	if ( $m( '_hs_video_file' ) ) { echo '<p><a href="' . esc_url( wp_get_attachment_url( $m( '_hs_video_file' ) ) ) . '" target="_blank">Xem video (file)</a></p>'; }
	if ( $m( '_hs_photo' ) ) { echo wp_get_attachment_image( $m( '_hs_photo' ), array( 240, 240 ) ); }
	echo '<p><label>Trạng thái<br><select name="hs_status">';
	foreach ( AGM_HS_STAT as $k => $l ) { echo '<option value="' . esc_attr( $k ) . '"' . selected( $m( '_hs_status' ) ?: 'new', $k, false ) . '>' . esc_html( $l ) . '</option>'; }
	echo '</select></label></p><p class="description">Chọn "Hợp lệ" rồi dùng thao tác hàng loạt "Tạo MOVER" ở danh sách để đăng lên trang bình chọn.</p>';
}
add_action( 'save_post_ho_so_mover', function ( $id ) {
	if ( ! isset( $_POST['agm_hs_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['agm_hs_nonce'] ) ), 'agm_hs_save' ) || ! current_user_can( 'edit_post', $id ) ) { return; }
	$s = sanitize_key( $_POST['hs_status'] ?? 'new' ); if ( isset( AGM_HS_STAT[ $s ] ) ) { update_post_meta( $id, '_hs_status', $s ); }
} );
add_action( 'restrict_manage_posts', function ( $type ) {
	if ( 'ho_so_mover' === $type ) { printf( '<a class="button" href="%s">Xuất Excel (CSV)</a>', esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=agm_hs_csv' ), 'agm_hs_csv' ) ) ); }
} );
add_action( 'admin_post_agm_hs_csv', function () {
	if ( ! current_user_can( 'edit_posts' ) ) { wp_die( 'Không có quyền.' ); }
	check_admin_referer( 'agm_hs_csv' );
	header( 'Content-Type: text/csv; charset=utf-8' ); header( 'Content-Disposition: attachment; filename="ho-so-top50-' . wp_date( 'Ymd' ) . '.csv"' );
	$out = fopen( 'php://output', 'w' ); fwrite( $out, "\xEF\xBB\xBF" );
	fputcsv( $out, array( 'Mã', 'Họ tên', 'MSNV', 'Đơn vị (HR)', 'Phòng ban', 'Center', 'Email', 'Hình thức', 'Tên câu chuyện', 'Tinh thần', 'Video', 'Trạng thái', 'Ngày nộp' ) );
	foreach ( array_reverse( get_posts( array( 'post_type' => 'ho_so_mover', 'post_status' => 'private', 'numberposts' => -1 ) ) ) as $p ) {
		$m = function ( $k ) use ( $p ) { return get_post_meta( $p->ID, $k, true ); };
		fputcsv( $out, array( agm_hs_code( $p->ID ), $m( '_hs_name' ), $m( '_hs_msnv' ), $m( '_hs_unit' ), $m( '_hs_dept' ), $m( '_hs_center' ), $m( '_hs_email' ), $m( '_hs_via' ), $m( '_hs_title' ), implode( ', ', (array) $m( '_hs_spirits' ) ), $m( '_hs_video' ) ?: ( $m( '_hs_video_file' ) ? wp_get_attachment_url( $m( '_hs_video_file' ) ) : '' ), AGM_HS_STAT[ $m( '_hs_status' ) ?: 'new' ], get_the_date( 'Y-m-d H:i', $p ) ) );
	}
	exit;
} );

add_action( 'wp_enqueue_scripts', function () {
	if ( is_page_template( 'page-top50-apply.php' ) ) {
		wp_enqueue_script( 'agm-apply', get_template_directory_uri() . '/assets/js/apply.js', array( 'agm-site' ), AGM_VER, true );
		wp_localize_script( 'agm-apply', 'AGA', array( 'ajax' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'agm_apply' ), 'maxVideo' => min( wp_max_upload_size(), 200 * MB_IN_BYTES ) ) );
	}
} );

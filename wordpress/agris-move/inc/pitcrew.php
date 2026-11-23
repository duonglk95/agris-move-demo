<?php
/**
 * Pit Crew registration (page template "Đăng ký Pit Crew"). Identity is checked against the employee list of the
 * voting plugin (name + MSNV + phone). People pick one or both tours; admins choose who goes per tour.
 * Tours and deadline: Customizer → Pit Crew.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

const AGM_SIZES    = array( 'S', 'M', 'L', 'XL', 'XXL' );
const AGM_ROLES    = array( 'Booth tiếp sức', 'Hậu cần', 'Chụp ảnh / quay', 'Giao lưu, tiết mục' );
const AGM_PIT_STAT = array( 'wait' => 'Chờ ban tổ chức chọn', 'yes' => 'Được chọn', 'no' => 'Chưa được chọn lần này' );

/** Tours: [ key => [ name, from, to ] ] from the Customizer lines "Tên | dd/mm | dd/mm". */
function agm_pit_tours() {
	$raw = get_theme_mod( 'agm_pit_tours', "Tour Tây Ninh – Campuchia | 13/12 | 15/12\nTour miền Trung – Lào | 16/12 | 22/12" );
	$out = array();
	foreach ( array_filter( array_map( 'trim', explode( "\n", $raw ) ) ) as $i => $line ) {
		$p = array_map( 'trim', explode( '|', $line ) );
		$out[ 't' . ( $i + 1 ) ] = array( $p[0], $p[1] ?? '', $p[2] ?? '' );
	}
	return $out;
}
function agm_pit_deadline() { return get_theme_mod( 'agm_pit_deadline', '2026-12-05' ); }
function agm_pit_open() { return current_time( 'Y-m-d' ) <= agm_pit_deadline(); }
function agm_pit_count() { return (int) wp_count_posts( 'pit_crew' )->private; }

add_action( 'init', function () {
	register_post_type( 'pit_crew', array(
		'label' => 'Pit Crew', 'public' => false, 'show_ui' => true, 'menu_icon' => 'dashicons-groups', 'menu_position' => 25,
		'supports' => array( 'title' ), 'map_meta_cap' => true, 'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		'labels' => array( 'name' => 'Pit Crew', 'menu_name' => 'Pit Crew', 'edit_item' => 'Xét chọn Pit Crew' ),
	) );
} );

add_action( 'customize_register', function ( $wp ) {
	$wp->add_section( 'agm_pit', array( 'title' => 'Pit Crew', 'priority' => 46 ) );
	$wp->add_setting( 'agm_pit_tours', array( 'default' => "Tour Tây Ninh – Campuchia | 13/12 | 15/12\nTour miền Trung – Lào | 16/12 | 22/12", 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp->add_control( 'agm_pit_tours', array( 'type' => 'textarea', 'section' => 'agm_pit', 'label' => 'Các tour', 'description' => 'Mỗi dòng một tour: Tên | ngày đi | ngày về. Đổi tên được, đừng đổi thứ tự dòng khi đã có người đăng ký.' ) );
	$wp->add_setting( 'agm_pit_deadline', array( 'default' => '2026-12-05', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp->add_control( 'agm_pit_deadline', array( 'type' => 'date', 'section' => 'agm_pit', 'label' => 'Hạn đăng ký (hết ngày này)' ) );
} );

function agm_pit_find( $msnv ) {
	$p = get_posts( array( 'post_type' => 'pit_crew', 'post_status' => 'private', 'numberposts' => 1, 'meta_key' => '_pit_msnv', 'meta_value' => $msnv ) );
	return $p ? $p[0] : null;
}
function agm_pit_number( $post ) {
	global $wpdb;
	return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'pit_crew' AND post_status = 'private' AND ID <= %d", $post->ID ) );
}
function agm_pit_payload( $post ) {
	$tours = agm_pit_tours(); $st = (array) get_post_meta( $post->ID, '_pit_status', true ); $rows = array();
	foreach ( (array) get_post_meta( $post->ID, '_pit_tours', true ) as $k ) {
		if ( isset( $tours[ $k ] ) ) { $rows[] = array( 'tour' => $tours[ $k ][0] . ' · ' . $tours[ $k ][1] . '–' . $tours[ $k ][2], 'status' => AGM_PIT_STAT[ $st[ $k ] ?? 'wait' ], 'code' => $st[ $k ] ?? 'wait' ); }
	}
	$num = agm_pit_number( $post );
	return array(
		'number' => $num, 'name' => get_post_meta( $post->ID, '_pit_name', true ), 'center' => get_post_meta( $post->ID, '_pit_center', true ), 'tours' => $rows,
		'share'  => "Mình đã đăng ký Pit Crew #{$num} – cùng đoàn đi tiếp sức Vào vụ! Bạn muốn cùng ra trạm PIT thì đăng ký trước " . wp_date( 'd/m', strtotime( agm_pit_deadline() ) ) . " nhé:\n" . wp_get_referer() . "\n" . ( defined( 'AGM_HASHTAGS' ) ? AGM_HASHTAGS : '' ),
	);
}

function agm_pit_identity() {
	if ( ! function_exists( 'amv_check_identity' ) ) { wp_send_json_error( array( 'message' => 'Chưa bật plugin danh sách nhân viên.' ), 500 ); }
	$name  = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$msnv  = sanitize_text_field( wp_unslash( $_POST['msnv'] ?? '' ) );
	$phone = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$row   = amv_check_identity( $name, $msnv, $phone );
	if ( ! $row ) { wp_send_json_error( array( 'message' => 'Họ tên, MSNV hoặc số điện thoại chưa khớp với danh sách HR. Bạn kiểm tra lại nhé.' ), 403 ); }
	return $row;
}

add_action( 'wp_ajax_agm_pit', 'agm_pit_submit' ); add_action( 'wp_ajax_nopriv_agm_pit', 'agm_pit_submit' );
function agm_pit_submit() {
	check_ajax_referer( 'agm_pit', 'nonce' );
	$row = agm_pit_identity();
	if ( $old = agm_pit_find( $row->msnv ) ) { wp_send_json_success( array( 'again' => true ) + agm_pit_payload( $old ) ); }
	if ( ! agm_pit_open() ) { wp_send_json_error( array( 'message' => 'Đã hết hạn đăng ký Pit Crew.' ), 400 ); }
	$tours  = array_values( array_intersect( array_map( 'sanitize_key', (array) ( $_POST['tours'] ?? array() ) ), array_keys( agm_pit_tours() ) ) );
	$center = sanitize_text_field( wp_unslash( $_POST['center'] ?? '' ) );
	$size   = sanitize_text_field( wp_unslash( $_POST['size'] ?? '' ) );
	$roles  = array_values( array_intersect( array_map( 'sanitize_text_field', wp_unslash( (array) ( $_POST['roles'] ?? array() ) ) ), AGM_ROLES ) );
	$other = sanitize_text_field( wp_unslash( $_POST['role_other'] ?? '' ) );
	if ( ! empty( $_POST['role_other_on'] ) && '' !== $other ) { $roles[] = 'Khác: ' . mb_substr( $other, 0, 80 ); }
	if ( ! $tours || ! in_array( $center, AGM_CENTERS, true ) || ! in_array( $size, AGM_SIZES, true ) ) {
		wp_send_json_error( array( 'message' => 'Bạn chọn tour, đơn vị và size áo nhé.' ), 400 );
	}
	$id = wp_insert_post( array( 'post_type' => 'pit_crew', 'post_status' => 'private', 'post_title' => $row->name . ' (' . $row->msnv . ')' ) );
	$meta = array( '_pit_msnv' => $row->msnv, '_pit_name' => $row->name, '_pit_unit' => $row->unit, '_pit_center' => $center, '_pit_size' => $size, '_pit_roles' => $roles,
		'_pit_tours' => $tours, '_pit_status' => array_fill_keys( $tours, 'wait' ), '_pit_note' => sanitize_textarea_field( wp_unslash( $_POST['note'] ?? '' ) ), '_pit_ip' => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	foreach ( $meta as $k => $v ) { update_post_meta( $id, $k, $v ); }
	wp_send_json_success( agm_pit_payload( get_post( $id ) ) );
}

add_action( 'wp_ajax_agm_pit_check', 'agm_pit_check' ); add_action( 'wp_ajax_nopriv_agm_pit_check', 'agm_pit_check' );
function agm_pit_check() {
	check_ajax_referer( 'agm_pit', 'nonce' );
	$row = agm_pit_identity();
	$p   = agm_pit_find( $row->msnv );
	$p ? wp_send_json_success( agm_pit_payload( $p ) ) : wp_send_json_error( array( 'message' => 'Chưa thấy đăng ký Pit Crew của bạn.' ), 404 );
}

/* ---------- admin ---------- */
add_filter( 'manage_pit_crew_posts_columns', function ( $c ) {
	$cols = array( 'cb' => $c['cb'], 'pit_no' => '#', 'title' => 'Người đăng ký', 'pit_info' => 'Đơn vị · size · vai trò' );
	foreach ( agm_pit_tours() as $k => $t ) { $cols[ 'pit_' . $k ] = $t[0]; }
	return $cols + array( 'date' => 'Ngày đăng ký' );
} );
add_action( 'manage_pit_crew_posts_custom_column', function ( $col, $id ) {
	if ( 'pit_no' === $col ) { echo (int) agm_pit_number( get_post( $id ) ); return; }
	if ( 'pit_info' === $col ) { echo esc_html( get_post_meta( $id, '_pit_center', true ) . ' · ' . get_post_meta( $id, '_pit_size', true ) . ' · ' . implode( ', ', (array) get_post_meta( $id, '_pit_roles', true ) ) ); return; }
	$k = substr( $col, 4 );
	if ( in_array( $k, (array) get_post_meta( $id, '_pit_tours', true ), true ) ) {
		$s = ( (array) get_post_meta( $id, '_pit_status', true ) )[ $k ] ?? 'wait';
		$color = array( 'wait' => '#b26a00', 'yes' => '#0b6b3a', 'no' => '#888' )[ $s ];
		echo '<strong style="color:' . esc_attr( $color ) . '">' . esc_html( AGM_PIT_STAT[ $s ] ) . '</strong>';
	} else { echo '—'; }
}, 10, 2 );
add_filter( 'bulk_actions-edit-pit_crew', function ( $a ) {
	foreach ( agm_pit_tours() as $k => $t ) {
		$a[ "pit_yes_$k" ] = 'Chọn đi: ' . $t[0];
		$a[ "pit_no_$k" ]  = 'Không chọn: ' . $t[0];
		$a[ "pit_wait_$k" ] = 'Đưa về chờ: ' . $t[0];
	}
	unset( $a['edit'] );
	return $a;
} );
add_filter( 'handle_bulk_actions-edit-pit_crew', function ( $url, $action, $ids ) {
	if ( preg_match( '/^pit_(yes|no|wait)_(t\d+)$/', $action, $m ) ) {
		foreach ( $ids as $id ) {
			if ( ! current_user_can( 'edit_post', $id ) || ! in_array( $m[2], (array) get_post_meta( $id, '_pit_tours', true ), true ) ) { continue; }
			$st = (array) get_post_meta( $id, '_pit_status', true ); $st[ $m[2] ] = $m[1]; update_post_meta( $id, '_pit_status', $st );
		}
	}
	return $url;
}, 10, 3 );
add_action( 'add_meta_boxes', function () { add_meta_box( 'pit_info', 'Thông tin đăng ký', 'agm_pit_box', 'pit_crew', 'normal', 'high' ); } );
function agm_pit_box( $post ) {
	wp_nonce_field( 'agm_pit_save', 'agm_pit_nonce' );
	$st = (array) get_post_meta( $post->ID, '_pit_status', true );
	echo '<p><strong>#' . (int) agm_pit_number( $post ) . '</strong> · MSNV ' . esc_html( get_post_meta( $post->ID, '_pit_msnv', true ) ) . ' · ' . esc_html( get_post_meta( $post->ID, '_pit_unit', true ) ) . ' · ' . esc_html( get_post_meta( $post->ID, '_pit_center', true ) ) . '</p>';
	echo '<p>Size áo: <strong>' . esc_html( get_post_meta( $post->ID, '_pit_size', true ) ) . '</strong> · Vai trò: ' . esc_html( implode( ', ', (array) get_post_meta( $post->ID, '_pit_roles', true ) ) ) . '</p>';
	echo '<p>Lưu ý cá nhân: ' . esc_html( get_post_meta( $post->ID, '_pit_note', true ) ) . '</p><table class="form-table">';
	foreach ( agm_pit_tours() as $k => $t ) {
		if ( ! in_array( $k, (array) get_post_meta( $post->ID, '_pit_tours', true ), true ) ) { continue; }
		echo '<tr><th>' . esc_html( $t[0] ) . '</th><td><select name="pit_status[' . esc_attr( $k ) . ']">';
		foreach ( AGM_PIT_STAT as $sk => $label ) { echo '<option value="' . esc_attr( $sk ) . '"' . selected( $st[ $k ] ?? 'wait', $sk, false ) . '>' . esc_html( $label ) . '</option>'; }
		echo '</select></td></tr>';
	}
	echo '</table>';
}
add_action( 'save_post_pit_crew', function ( $id ) {
	if ( ! isset( $_POST['agm_pit_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['agm_pit_nonce'] ) ), 'agm_pit_save' ) || ! current_user_can( 'edit_post', $id ) ) { return; }
	$st = (array) get_post_meta( $id, '_pit_status', true );
	foreach ( (array) ( $_POST['pit_status'] ?? array() ) as $k => $v ) {
		$k = sanitize_key( $k ); $v = sanitize_key( $v );
		if ( isset( $st[ $k ] ) && isset( AGM_PIT_STAT[ $v ] ) ) { $st[ $k ] = $v; }
	}
	update_post_meta( $id, '_pit_status', $st );
} );
add_action( 'restrict_manage_posts', function ( $type ) {
	if ( 'pit_crew' === $type ) { printf( '<a class="button" href="%s">Xuất Excel (CSV)</a>', esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=agm_pit_csv' ), 'agm_pit_csv' ) ) ); }
} );
add_action( 'admin_post_agm_pit_csv', function () {
	if ( ! current_user_can( 'edit_posts' ) ) { wp_die( 'Không có quyền.' ); }
	check_admin_referer( 'agm_pit_csv' );
	$tours = agm_pit_tours();
	header( 'Content-Type: text/csv; charset=utf-8' ); header( 'Content-Disposition: attachment; filename="pit-crew-' . wp_date( 'Ymd' ) . '.csv"' );
	$out = fopen( 'php://output', 'w' ); fwrite( $out, "\xEF\xBB\xBF" );
	$head = array( '#', 'Họ tên', 'MSNV', 'Đơn vị (HR)', 'Center', 'Size áo', 'Vai trò', 'Lưu ý cá nhân', 'Ngày đăng ký' );
	foreach ( $tours as $t ) { $head[] = $t[0]; }
	fputcsv( $out, $head );
	foreach ( array_reverse( get_posts( array( 'post_type' => 'pit_crew', 'post_status' => 'private', 'numberposts' => -1 ) ) ) as $i => $p ) {
		$st = (array) get_post_meta( $p->ID, '_pit_status', true ); $mine = (array) get_post_meta( $p->ID, '_pit_tours', true );
		$r = array( $i + 1, get_post_meta( $p->ID, '_pit_name', true ), get_post_meta( $p->ID, '_pit_msnv', true ), get_post_meta( $p->ID, '_pit_unit', true ), get_post_meta( $p->ID, '_pit_center', true ), get_post_meta( $p->ID, '_pit_size', true ), implode( ', ', (array) get_post_meta( $p->ID, '_pit_roles', true ) ), get_post_meta( $p->ID, '_pit_note', true ), get_the_date( 'Y-m-d H:i', $p ) );
		foreach ( array_keys( $tours ) as $k ) { $r[] = in_array( $k, $mine, true ) ? AGM_PIT_STAT[ $st[ $k ] ?? 'wait' ] : ''; }
		fputcsv( $out, $r );
	}
	exit;
} );

add_action( 'wp_enqueue_scripts', function () {
	if ( is_page_template( 'page-pitcrew.php' ) ) {
		wp_enqueue_script( 'agm-pit', get_template_directory_uri() . '/assets/js/pitcrew.js', array( 'agm-site' ), AGM_VER, true );
		wp_localize_script( 'agm-pit', 'AGP', array( 'ajax' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'agm_pit' ), 'tags' => AGM_HASHTAGS ) );
	}
} );

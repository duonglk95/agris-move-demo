<?php
$_SERVER['HTTP_HOST'] = 'localhost:8080'; $_SERVER['REQUEST_URI'] = '/';
require '/home/claude/wptest/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
switch_theme( 'agris-move' );
$r = activate_plugin( 'agris-move-vote/agris-move-vote.php' ); if ( is_wp_error( $r ) ) { echo $r->get_error_message(), "\n"; }
wp_set_current_user( 1 );
function seed_img( $file, $parent = 0 ) {
	$tmp = wp_tempnam(); copy( $file, $tmp );
	$id = media_handle_sideload( array( 'name' => basename( $file ), 'tmp_name' => $tmp ), $parent );
	return is_wp_error( $id ) ? 0 : $id;
}
$img = '/home/claude/wp-build/agris-move/assets/img/';
// events
foreach ( array(
	array( 'Vào vụ 2026–2027', '2026-11-25T00:00', 1, 'Vào vụ tại KSK', 'ngày vào vụ' ),
	array( 'Vào vụ 2026–2027', '2026-11-28T00:00', 1, 'Vào vụ tại AGL', 'ngày vào vụ' ),
	array( 'Vào vụ 2026–2027', '2026-12-05T00:00', 1, 'Vào vụ tại ATN', 'ngày vào vụ' ),
) as $e ) {
	$id = wp_insert_post( array( 'post_type' => 'su_kien', 'post_status' => 'publish', 'post_title' => $e[0] ) );
	update_post_meta( $id, '_agm_at', $e[1] ); update_post_meta( $id, '_agm_allday', $e[2] ? '1' : '' ); update_post_meta( $id, '_agm_place', $e[3] ); update_post_meta( $id, '_agm_cd', $e[4] );
}
// posts
$cat = wp_insert_term( 'Đổi mới', 'category' );
foreach ( array( array( 'Cách làm mới trong công việc', 'demo-news-1.jpg' ), array( 'Kết nối sức mạnh đồng đội', 'demo-news-2.jpg' ), array( 'Những chuyển động tích cực', 'demo-news-3.jpg' ) ) as $i => $n ) {
	$id = wp_insert_post( array( 'post_status' => 'publish', 'post_title' => $n[0], 'post_content' => '<p>Nội dung minh hoạ cho bài Bản tin MOVE.</p>', 'post_date' => date( 'Y-m-d H:i:s', strtotime( "-$i day" ) ), 'post_category' => is_wp_error( $cat ) ? array() : array( $cat['term_id'] ) ) );
	set_post_thumbnail( $id, seed_img( $img . $n[1], $id ) );
}
// podcast
$id = wp_insert_post( array( 'post_type' => 'podcast', 'post_status' => 'publish', 'post_title' => 'Từ ý tưởng đến hành động', 'post_content' => '<p>Tập 1 của AgriS-thing.</p>' ) );
update_post_meta( $id, '_agm_duration', '24:16' ); set_post_thumbnail( $id, seed_img( $img . 'demo-pod-big.jpg', $id ) );
// top50 page
$p = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'TOP 50 MOVERS', 'post_name' => 'top-50-movers' ) );
update_post_meta( $p, '_wp_page_template', 'page-top50.php' );
// movers
foreach ( array( array( 'Nguyễn Văn An', 'Phòng Nông vụ – AgriC', 'demo-news-3.jpg' ), array( 'Trần Thị Bình', 'Nhà máy TTCA – ProC', 'demo-news-2.jpg' ), array( 'Lê Minh Châu', 'Phòng Kinh doanh – ComC', 'demo-pod-a.jpg' ) ) as $m ) {
	$id = wp_insert_post( array( 'post_type' => 'mover', 'post_status' => 'publish', 'post_title' => $m[0], 'post_excerpt' => 'Chủ động đề xuất cách ghi nhật ký đồng ruộng trên điện thoại, giúp cả tổ tiết kiệm hai giờ mỗi tuần.', 'post_content' => '<h3>Bối cảnh</h3><p>Nội dung minh hoạ.</p><h3>Hành động</h3><p>Nội dung minh hoạ.</p>' ) );
	update_post_meta( $id, '_amv_unit', $m[1] ); set_post_thumbnail( $id, seed_img( $img . $m[2], $id ) );
}
// employees via the same code path as the import
global $wpdb; $t = amv_tables();
foreach ( array( array( 'Phạm Thu Hà', 'AG00001', '0912 345 678', 'Phòng Truyền thông – HO' ), array( 'Đỗ Quang Huy', 'AG00002', '+84 987 654 321', 'Nhà máy TTCS – ProC' ) ) as $e ) {
	$wpdb->insert( $t['people'], array( 'msnv' => amv_msnv_key( $e[1] ), 'name' => $e[0], 'name_key' => amv_name_key( $e[0] ), 'phone_hash' => amv_phone_hash( $e[2] ), 'unit' => $e[3] ) );
}
update_option( 'amv_force_open', '1' );
flush_rewrite_rules();
echo "seeded; people=", amv_people_count(), "\n";

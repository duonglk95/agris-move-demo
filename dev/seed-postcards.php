<?php
// Import dev/postcard-photos into the "Ảnh bưu thiếp" library. Usage: php dev/seed-postcards.php /path/to/wp-load.php
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
require $argv[1] ?? '/home/claude/wptest/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php'; require_once ABSPATH . 'wp-admin/includes/file.php'; require_once ABSPATH . 'wp-admin/includes/media.php';
wp_set_current_user( 1 ); $dir = __DIR__ . '/postcard-photos/';
foreach ( json_decode( file_get_contents( $dir . 'photos.json' ), true ) as $p ) {
	$tmp = wp_tempnam(); copy( $dir . $p['file'], $tmp );
	$att = media_handle_sideload( array( 'name' => $p['file'], 'tmp_name' => $tmp ), 0 );
	$id  = wp_insert_post( array( 'post_type' => 'anh_buu_thiep', 'post_status' => 'publish', 'post_title' => $p['title'], 'menu_order' => $p['order'] ) );
	set_post_thumbnail( $id, $att ); update_post_meta( $id, '_agm_author', $p['author'] ); update_post_meta( $id, '_agm_place', $p['place'] );
}
echo "done\n";

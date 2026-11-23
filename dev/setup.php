<?php
$_SERVER['HTTP_HOST'] = 'localhost:8080'; $_SERVER['REQUEST_URI'] = '/'; $_SERVER['SERVER_NAME'] = 'localhost';
define( 'WP_INSTALLING', true );
require '/home/claude/wptest/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( ! is_blog_installed() ) {
	$r = wp_install( 'AgriS MOVE', 'admin', 'admin@example.com', true, '', 'Admin#12345', 'vi' );
	echo "installed\n";
}
update_option( 'siteurl', 'http://localhost:8080' ); update_option( 'home', 'http://localhost:8080' );
update_option( 'timezone_string', 'Asia/Ho_Chi_Minh' ); update_option( 'permalink_structure', '/%postname%/' );
echo "done\n";

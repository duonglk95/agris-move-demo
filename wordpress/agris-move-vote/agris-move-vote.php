<?php
/**
 * Plugin Name: AgriS MOVE – Bình chọn TOP 50 MOVERS
 * Description: Hồ sơ MOVER, danh sách nhân viên, đăng nhập bằng Họ tên + MSNV + SĐT, bình chọn “love” có ghi IP, thống kê kết quả và xuất Excel.
 * Version: 1.0.0
 * Requires at least: 6.2
 * Requires PHP: 7.4
 * Text Domain: agris-move-vote
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AMV_VER', '1.0.0' );
define( 'AMV_FILE', __FILE__ );
define( 'AMV_DIR', plugin_dir_path( __FILE__ ) );
define( 'AMV_URL', plugin_dir_url( __FILE__ ) );

require AMV_DIR . 'includes/install.php';
require AMV_DIR . 'includes/people.php';
require AMV_DIR . 'includes/movers.php';
require AMV_DIR . 'includes/ajax.php';
require AMV_DIR . 'includes/front.php';
if ( is_admin() ) {
	require AMV_DIR . 'includes/xlsx.php';
	require AMV_DIR . 'includes/admin.php';
}

register_activation_hook( __FILE__, 'amv_install' );
add_action( 'plugins_loaded', 'amv_maybe_upgrade' );

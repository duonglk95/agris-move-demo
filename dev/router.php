<?php
$path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$file = '/home/claude/wptest' . $path;
if ( $path !== '/' && file_exists( $file ) && ! is_dir( $file ) ) { return false; }
if ( is_dir( $file ) && file_exists( rtrim( $file, '/' ) . '/index.php' ) ) { chdir( $file ); require rtrim( $file, '/' ) . '/index.php'; return; }
$_SERVER['SCRIPT_NAME'] = '/index.php';
require '/home/claude/wptest/index.php';

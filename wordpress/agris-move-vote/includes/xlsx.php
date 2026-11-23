<?php
/**
 * Reads the first sheet of an .xlsx file into rows of strings, or a .csv file (comma or semicolon).
 * Enough for an employee list; no formulas, no formatting.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function amv_read_table( $path, $original_name ) {
	$ext = strtolower( pathinfo( $original_name, PATHINFO_EXTENSION ) );
	return 'xlsx' === $ext ? amv_read_xlsx( $path ) : amv_read_csv( $path );
}

function amv_read_csv( $path ) {
	$raw = file_get_contents( $path ); // phpcs:ignore
	if ( false === $raw ) {
		return new WP_Error( 'read', 'Không đọc được file.' );
	}
	$raw = preg_replace( '/^\xEF\xBB\xBF/', '', $raw );
	if ( ! preg_match( '//u', $raw ) ) {   // older Excel exports in Windows-1258/1252
		$raw = function_exists( 'mb_convert_encoding' ) ? mb_convert_encoding( $raw, 'UTF-8', 'Windows-1252' ) : $raw;
	}
	$first = strtok( $raw, "\n" );
	$delim = substr_count( $first, ';' ) > substr_count( $first, ',' ) ? ';' : ',';
	$rows  = array();
	$fh    = fopen( 'php://temp', 'r+' ); // phpcs:ignore
	fwrite( $fh, $raw ); // phpcs:ignore
	rewind( $fh );
	while ( ( $r = fgetcsv( $fh, 0, $delim ) ) !== false ) {
		$rows[] = array_map( 'trim', $r );
	}
	fclose( $fh ); // phpcs:ignore
	return $rows;
}

function amv_read_xlsx( $path ) {
	if ( ! class_exists( 'ZipArchive' ) ) {
		return new WP_Error( 'zip', 'Máy chủ thiếu ZipArchive. Hãy lưu file dưới dạng CSV UTF-8 rồi tải lên.' );
	}
	$zip = new ZipArchive();
	if ( true !== $zip->open( $path ) ) {
		return new WP_Error( 'zip', 'Không mở được file Excel.' );
	}
	$shared = array();
	$ss     = $zip->getFromName( 'xl/sharedStrings.xml' );
	if ( $ss ) {
		$x = simplexml_load_string( $ss );
		foreach ( $x->si as $si ) {
			if ( isset( $si->t ) ) {
				$shared[] = (string) $si->t;
			} else {
				$s = '';
				foreach ( $si->r as $r ) {
					$s .= (string) $r->t;
				}
				$shared[] = $s;
			}
		}
	}
	// first worksheet listed in the workbook
	$sheet = 'xl/worksheets/sheet1.xml';
	$rels  = $zip->getFromName( 'xl/_rels/workbook.xml.rels' );
	$wb    = $zip->getFromName( 'xl/workbook.xml' );
	if ( $rels && $wb && preg_match( '/<sheet[^>]*r:id="([^"]+)"/', $wb, $m ) && preg_match( '/Id="' . preg_quote( $m[1], '/' ) . '"[^>]*Target="([^"]+)"/', $rels, $t ) ) {
		$target = ltrim( $t[1], '/' );
		$sheet  = 0 === strpos( $target, 'xl/' ) ? $target : 'xl/' . $target;
	}
	$xml = $zip->getFromName( $sheet );
	$zip->close();
	if ( ! $xml ) {
		return new WP_Error( 'sheet', 'Không tìm thấy trang tính trong file.' );
	}
	$x    = simplexml_load_string( $xml );
	$rows = array();
	foreach ( $x->sheetData->row as $row ) {
		$cells = array();
		foreach ( $row->c as $c ) {
			$ref = (string) $c['r'];
			$col = 0;
			foreach ( str_split( preg_replace( '/\d+/', '', $ref ) ) as $ch ) {
				$col = $col * 26 + ( ord( $ch ) - 64 );
			}
			$type = (string) $c['t'];
			if ( 's' === $type ) {
				$val = isset( $shared[ (int) $c->v ] ) ? $shared[ (int) $c->v ] : '';
			} elseif ( 'inlineStr' === $type ) {
				$val = (string) $c->is->t;
			} else {
				$val = (string) $c->v;
				// phone numbers typed as numbers lose nothing here, but large ones may come as 9.1E+8
				if ( preg_match( '/^[\d.]+E\+\d+$/i', $val ) ) {
					$val = number_format( (float) $val, 0, '', '' );
				}
			}
			$cells[ $col - 1 ] = trim( $val );
		}
		if ( $cells ) {
			$max = max( array_keys( $cells ) );
			$line = array();
			for ( $i = 0; $i <= $max; $i++ ) {
				$line[] = isset( $cells[ $i ] ) ? $cells[ $i ] : '';
			}
			$rows[] = $line;
		}
	}
	return $rows;
}

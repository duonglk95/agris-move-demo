<?php
/**
 * wp-admin → Bình chọn MOVE: results, employee list import, settings, Excel exports.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function amv_cap() {
	return apply_filters( 'amv_capability', 'edit_others_posts' );
}

add_action( 'admin_menu', function () {
	add_menu_page( 'Bình chọn MOVE', 'Bình chọn MOVE', amv_cap(), 'amv', 'amv_page_results', 'dashicons-heart', 24 );
	add_submenu_page( 'amv', 'Kết quả bình chọn', 'Kết quả', amv_cap(), 'amv', 'amv_page_results' );
	add_submenu_page( 'amv', 'Danh sách nhân viên', 'Nhân viên', amv_cap(), 'amv-people', 'amv_page_people' );
	add_submenu_page( 'amv', 'Cài đặt bình chọn', 'Cài đặt', amv_cap(), 'amv-settings', 'amv_page_settings' );
} );

function amv_notice() {
	if ( empty( $_GET['amv_msg'] ) ) { // phpcs:ignore
		return;
	}
	$msg = sanitize_text_field( wp_unslash( $_GET['amv_msg'] ) ); // phpcs:ignore
	$err = ! empty( $_GET['amv_err'] ); // phpcs:ignore
	printf( '<div class="notice notice-%s is-dismissible"><p>%s</p></div>', $err ? 'error' : 'success', esc_html( $msg ) );
}

function amv_back( $page, $msg, $err = false ) {
	wp_safe_redirect( add_query_arg( array_filter( array( 'page' => $page, 'amv_msg' => rawurlencode( $msg ), 'amv_err' => $err ? 1 : 0 ) ), admin_url( 'admin.php' ) ) );
	exit;
}

function amv_guard( $action ) {
	if ( ! current_user_can( amv_cap() ) ) {
		wp_die( 'Bạn không có quyền thực hiện thao tác này.' );
	}
	check_admin_referer( $action );
}

/* ---------- results ---------- */

function amv_results() {
	$counts = amv_all_counts();
	$movers = get_posts( array( 'post_type' => 'mover', 'post_status' => array( 'publish', 'draft', 'pending', 'private' ), 'posts_per_page' => -1 ) );
	$rows   = array();
	foreach ( $movers as $m ) {
		$rows[] = array(
			'id'     => $m->ID,
			'name'   => get_the_title( $m ),
			'unit'   => get_post_meta( $m->ID, '_amv_unit', true ),
			'msnv'   => get_post_meta( $m->ID, '_amv_msnv', true ),
			'status' => $m->post_status,
			'votes'  => isset( $counts[ $m->ID ] ) ? $counts[ $m->ID ] : 0,
		);
	}
	usort( $rows, function ( $a, $b ) {
		return $b['votes'] <=> $a['votes'] ?: strcmp( $a['name'], $b['name'] );
	} );
	$rank = 0;
	$prev = null;
	foreach ( $rows as $i => &$r ) {
		if ( $r['votes'] !== $prev ) {
			$rank = $i + 1;
			$prev = $r['votes'];
		}
		$r['rank']  = $rank;
		$r['award'] = $rank <= 50 && $r['votes'] > 0 ? 'TOP 50' : ( $r['votes'] >= 100 ? 'AgriS’ Choice' : '' );
	}
	unset( $r );
	return $rows;
}

function amv_page_results() {
	global $wpdb;
	$t      = amv_tables();
	$rows   = amv_results();
	$total  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$t['votes']}" ); // phpcs:ignore
	$voters = (int) $wpdb->get_var( "SELECT COUNT(DISTINCT employee_id) FROM {$t['votes']}" ); // phpcs:ignore
	$state  = array( 'before' => 'Chưa mở', 'open' => 'Đang mở', 'closed' => 'Đã đóng' )[ amv_vote_state() ];
	if ( '1' === get_option( 'amv_force_open' ) ) {
		$state = 'Đang mở thử (bật trong Cài đặt)';
	}
	?>
	<div class="wrap">
	  <h1>Kết quả bình chọn TOP 50 MOVERS</h1>
	  <?php amv_notice(); ?>
	  <p>Cổng bình chọn: <strong><?php echo esc_html( $state ); ?></strong> (<?php echo esc_html( get_option( 'amv_open' ) . ' → ' . get_option( 'amv_close' ) ); ?>) ·
	     MOVER: <strong><?php echo esc_html( count( $rows ) ); ?></strong> ·
	     Lượt love: <strong><?php echo esc_html( number_format_i18n( $total ) ); ?></strong> ·
	     Người đã bình chọn: <strong><?php echo esc_html( number_format_i18n( $voters ) ); ?></strong> / <?php echo esc_html( number_format_i18n( amv_people_count() ) ); ?> nhân viên</p>
	  <p>
	    <a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=amv_export&type=results' ), 'amv_export' ) ); ?>">Xuất kết quả (Excel)</a>
	    <a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=amv_export&type=votes' ), 'amv_export' ) ); ?>">Xuất chi tiết từng lượt love kèm IP (Excel)</a>
	  </p>
	  <p class="description">Cột “Giải” tính tự động theo thể lệ: hạng 1–50 là TOP 50, ngoài TOP 50 từ 100 lượt là AgriS’ Choice. Trường hợp bằng lượt ở vị trí thứ 50, Ban Tổ chức xét theo quy định (từ 3 người trở xuống cùng vào TOP 50; từ 4 người trở lên ưu tiên người nộp hồ sơ sớm hơn).</p>
	  <table class="widefat striped">
	    <thead><tr><th style="width:60px">Hạng</th><th>MOVER</th><th>Đơn vị</th><th>MSNV</th><th style="width:110px">Lượt love</th><th style="width:130px">Giải</th></tr></thead>
	    <tbody>
	    <?php if ( ! $rows ) : ?>
	      <tr><td colspan="6">Chưa có hồ sơ MOVER nào. Thêm tại MOVER → Thêm MOVER.</td></tr>
	    <?php endif; ?>
	    <?php foreach ( $rows as $r ) : ?>
	      <tr>
	        <td><?php echo esc_html( $r['rank'] ); ?></td>
	        <td><a href="<?php echo esc_url( get_edit_post_link( $r['id'] ) ); ?>"><strong><?php echo esc_html( $r['name'] ); ?></strong></a><?php echo 'publish' !== $r['status'] ? ' <em>(chưa đăng)</em>' : ''; ?></td>
	        <td><?php echo esc_html( $r['unit'] ); ?></td>
	        <td><?php echo esc_html( $r['msnv'] ); ?></td>
	        <td><strong><?php echo esc_html( number_format_i18n( $r['votes'] ) ); ?></strong></td>
	        <td><?php echo esc_html( $r['award'] ); ?></td>
	      </tr>
	    <?php endforeach; ?>
	    </tbody>
	  </table>
	</div>
	<?php
}

/* ---------- exports ---------- */

function amv_csv( $filename, $header, $rows ) {
	nocache_headers();
	header( 'Content-Type: text/csv; charset=UTF-8' );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	$out = fopen( 'php://output', 'w' ); // phpcs:ignore
	fwrite( $out, "\xEF\xBB\xBF" ); // phpcs:ignore -- lets Excel read Vietnamese correctly
	fputcsv( $out, $header );
	foreach ( $rows as $r ) {
		fputcsv( $out, $r );
	}
	fclose( $out ); // phpcs:ignore
	exit;
}

add_action( 'admin_post_amv_export', function () {
	amv_guard( 'amv_export' );
	global $wpdb;
	$t     = amv_tables();
	$type  = isset( $_GET['type'] ) ? sanitize_key( $_GET['type'] ) : 'results';
	$stamp = wp_date( 'Ymd-Hi' );
	if ( 'votes' === $type ) {
		$data = $wpdb->get_results( "SELECT v.created_at, p.msnv, p.name, p.unit, v.mover_id, v.ip, v.forwarded FROM {$t['votes']} v LEFT JOIN {$t['people']} p ON p.id = v.employee_id ORDER BY v.created_at" ); // phpcs:ignore
		$titles = array();
		$rows   = array();
		foreach ( $data as $d ) {
			if ( ! isset( $titles[ $d->mover_id ] ) ) {
				$titles[ $d->mover_id ] = get_the_title( (int) $d->mover_id );
			}
			$rows[] = array( $d->created_at, $d->msnv ? $d->msnv : '(đã xoá khỏi danh sách)', $d->name, $d->unit, $titles[ $d->mover_id ], $d->ip, $d->forwarded );
		}
		amv_csv( "luot-binh-chon-{$stamp}.csv", array( 'Thời gian', 'MSNV người bình chọn', 'Họ tên', 'Đơn vị', 'MOVER được love', 'IP', 'X-Forwarded-For' ), $rows );
	}
	$rows = array_map( function ( $r ) {
		return array( $r['rank'], $r['name'], $r['unit'], $r['msnv'], $r['votes'], $r['award'] );
	}, amv_results() );
	amv_csv( "ket-qua-top50-{$stamp}.csv", array( 'Hạng', 'MOVER', 'Đơn vị', 'MSNV', 'Lượt love', 'Giải' ), $rows );
} );

/* ---------- employee list ---------- */

function amv_page_people() {
	$n = amv_people_count();
	?>
	<div class="wrap">
	  <h1>Danh sách nhân viên</h1>
	  <?php amv_notice(); ?>
	  <p>Đang có <strong><?php echo esc_html( number_format_i18n( $n ) ); ?></strong> nhân viên trong danh sách. Chỉ những người có trong danh sách mới đăng nhập bình chọn được.</p>
	  <div class="card" style="max-width:760px">
	    <h2>Tải lên từ Excel</h2>
	    <p>File <strong>.xlsx</strong> hoặc <strong>.csv</strong>, dòng đầu là tiêu đề, gồm 4 cột: <strong>Họ và tên · MSNV · Số điện thoại · Đơn vị</strong>. Hệ thống tự nhận cột theo tiêu đề; nếu không có tiêu đề thì đọc theo đúng thứ tự trên.
	       <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=amv_sample' ), 'amv_sample' ) ); ?>">Tải file mẫu</a>.</p>
	    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
	      <input type="hidden" name="action" value="amv_import">
	      <?php wp_nonce_field( 'amv_import' ); ?>
	      <p><input type="file" name="file" accept=".xlsx,.csv" required></p>
	      <p>
	        <label><input type="radio" name="mode" value="merge" checked> Thêm mới và cập nhật theo MSNV (giữ người không có trong file)</label><br>
	        <label><input type="radio" name="mode" value="replace"> Thay toàn bộ: người không có trong file sẽ bị xoá khỏi danh sách</label>
	      </p>
	      <p><button class="button button-primary">Tải lên</button></p>
	    </form>
	    <p class="description">Số điện thoại không được lưu ở dạng đọc được: hệ thống chỉ giữ mã băm của 9 số cuối để đối chiếu khi đăng nhập. Lượt bình chọn đã có không bị ảnh hưởng khi cập nhật danh sách.</p>
	  </div>
	</div>
	<?php
}

add_action( 'admin_post_amv_sample', function () {
	amv_guard( 'amv_sample' );
	amv_csv( 'mau-danh-sach-nhan-vien.csv', array( 'Họ và tên', 'MSNV', 'Số điện thoại', 'Đơn vị' ), array(
		array( 'Nguyễn Văn An', 'AG00123', '0912345678', 'Phòng Nông vụ – AgriC' ),
		array( 'Trần Thị Bình', 'AG00456', '0987654321', 'Nhà máy TTCA – ProC' ),
	) );
} );

add_action( 'admin_post_amv_import', function () {
	amv_guard( 'amv_import' );
	if ( empty( $_FILES['file']['tmp_name'] ) ) {
		amv_back( 'amv-people', 'Chưa chọn file.', true );
	}
	$name = sanitize_file_name( wp_unslash( $_FILES['file']['name'] ) ); // phpcs:ignore
	$rows = amv_read_table( $_FILES['file']['tmp_name'], $name ); // phpcs:ignore
	if ( is_wp_error( $rows ) ) {
		amv_back( 'amv-people', $rows->get_error_message(), true );
	}
	if ( ! $rows ) {
		amv_back( 'amv-people', 'File không có dữ liệu.', true );
	}

	// column positions from the header row, if there is one
	$map = array( 'name' => 0, 'msnv' => 1, 'phone' => 2, 'unit' => 3 );
	$head = array_map( 'amv_name_key', $rows[0] );
	$has_head = false;
	foreach ( $head as $i => $h ) {
		if ( preg_match( '/\b(msnv|ma nv|ma so|ma nhan vien|employee id)\b/', $h ) ) { $map['msnv'] = $i; $has_head = true; }
		elseif ( preg_match( '/(dien thoai|sdt|so dt|phone|mobile)/', $h ) ) { $map['phone'] = $i; $has_head = true; }
		elseif ( preg_match( '/(don vi|phong|bo phan|unit|department)/', $h ) ) { $map['unit'] = $i; $has_head = true; }
		elseif ( preg_match( '/(ho ten|ho va ten|\bten\b|name)/', $h ) ) { $map['name'] = $i; $has_head = true; }
	}
	if ( $has_head ) {
		array_shift( $rows );
	}

	global $wpdb;
	$t = amv_tables();
	$seen = array();
	$added = 0;
	$updated = 0;
	$skipped = 0;
	foreach ( $rows as $r ) {
		$nm   = isset( $r[ $map['name'] ] ) ? trim( $r[ $map['name'] ] ) : '';
		$ms   = isset( $r[ $map['msnv'] ] ) ? amv_msnv_key( $r[ $map['msnv'] ] ) : '';
		$ph   = isset( $r[ $map['phone'] ] ) ? amv_phone_hash( $r[ $map['phone'] ] ) : '';
		$unit = isset( $r[ $map['unit'] ] ) ? trim( $r[ $map['unit'] ] ) : '';
		if ( '' === $nm || '' === $ms || '' === $ph ) {
			$skipped++;
			continue;
		}
		$seen[ $ms ] = true;
		$data = array( 'name' => $nm, 'name_key' => amv_name_key( $nm ), 'phone_hash' => $ph, 'unit' => $unit );
		$id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$t['people']} WHERE msnv = %s", $ms ) ); // phpcs:ignore
		if ( $id ) {
			$wpdb->update( $t['people'], $data, array( 'id' => (int) $id ) );
			$updated++;
		} else {
			$wpdb->insert( $t['people'], $data + array( 'msnv' => $ms ) );
			$added++;
		}
	}
	$removed = 0;
	if ( isset( $_POST['mode'] ) && 'replace' === $_POST['mode'] && $seen ) {
		foreach ( $wpdb->get_col( "SELECT msnv FROM {$t['people']}" ) as $ms ) { // phpcs:ignore
			if ( ! isset( $seen[ $ms ] ) ) {
				$wpdb->delete( $t['people'], array( 'msnv' => $ms ) );
				$removed++;
			}
		}
	}
	$msg = sprintf( 'Đã thêm %d, cập nhật %d nhân viên.', $added, $updated );
	if ( $removed ) {
		$msg .= sprintf( ' Đã xoá %d người không có trong file.', $removed );
	}
	if ( $skipped ) {
		$msg .= sprintf( ' Bỏ qua %d dòng thiếu họ tên, MSNV hoặc số điện thoại.', $skipped );
	}
	amv_back( 'amv-people', $msg );
} );

/* ---------- settings ---------- */

function amv_page_settings() {
	$to_input = function ( $v ) {
		return str_replace( ' ', 'T', (string) $v );
	};
	?>
	<div class="wrap">
	  <h1>Cài đặt bình chọn</h1>
	  <?php amv_notice(); ?>
	  <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	    <input type="hidden" name="action" value="amv_settings">
	    <?php wp_nonce_field( 'amv_settings' ); ?>
	    <table class="form-table" role="presentation">
	      <tr><th scope="row"><label for="amv_open">Mở cổng bình chọn</label></th>
	          <td><input type="datetime-local" id="amv_open" name="amv_open" value="<?php echo esc_attr( $to_input( get_option( 'amv_open' ) ) ); ?>"> <span class="description">giờ <?php echo esc_html( wp_timezone_string() ); ?></span></td></tr>
	      <tr><th scope="row"><label for="amv_close">Đóng cổng bình chọn</label></th>
	          <td><input type="datetime-local" id="amv_close" name="amv_close" value="<?php echo esc_attr( $to_input( get_option( 'amv_close' ) ) ); ?>"></td></tr>
	      <tr><th scope="row">Mở thử</th>
	          <td><label><input type="checkbox" name="amv_force_open" value="1" <?php checked( get_option( 'amv_force_open' ), '1' ); ?>> Cho phép bình chọn ngay, bỏ qua thời gian ở trên (dùng để chạy thử; nhớ tắt và xoá lượt thử trước ngày mở chính thức)</label></td></tr>
	      <tr><th scope="row">Hiện số lượt love</th>
	          <td><label><input type="checkbox" name="amv_show_counts" value="1" <?php checked( get_option( 'amv_show_counts', '1' ), '1' ); ?>> Hiện số lượt love trên thẻ MOVER cho người xem</label></td></tr>
	    </table>
	    <?php submit_button( 'Lưu cài đặt' ); ?>
	  </form>
	  <hr>
	  <h2>Xoá toàn bộ lượt bình chọn</h2>
	  <p>Dùng sau khi chạy thử. Thao tác này không hoàn tác được.</p>
	  <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	    <input type="hidden" name="action" value="amv_reset_votes">
	    <?php wp_nonce_field( 'amv_reset_votes' ); ?>
	    <p><label><input type="checkbox" name="confirm" value="1" required> Tôi hiểu tất cả lượt love sẽ bị xoá vĩnh viễn</label></p>
	    <p><button class="button button-secondary">Xoá toàn bộ lượt bình chọn</button></p>
	  </form>
	</div>
	<?php
}

add_action( 'admin_post_amv_settings', function () {
	amv_guard( 'amv_settings' );
	foreach ( array( 'amv_open', 'amv_close' ) as $k ) {
		$v = isset( $_POST[ $k ] ) ? str_replace( 'T', ' ', sanitize_text_field( wp_unslash( $_POST[ $k ] ) ) ) : '';
		if ( preg_match( '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $v ) ) {
			update_option( $k, $v );
		}
	}
	update_option( 'amv_force_open', empty( $_POST['amv_force_open'] ) ? '0' : '1' );
	update_option( 'amv_show_counts', empty( $_POST['amv_show_counts'] ) ? '0' : '1' );
	amv_back( 'amv-settings', 'Đã lưu cài đặt.' );
} );

add_action( 'admin_post_amv_reset_votes', function () {
	amv_guard( 'amv_reset_votes' );
	if ( empty( $_POST['confirm'] ) ) {
		amv_back( 'amv-settings', 'Hãy tích ô xác nhận trước khi xoá.', true );
	}
	global $wpdb;
	$t = amv_tables();
	$wpdb->query( "DELETE FROM {$t['votes']}" ); // phpcs:ignore
	amv_back( 'amv-settings', 'Đã xoá toàn bộ lượt bình chọn.' );
} );

<?php
/**
 * What visitors see: the MOVER grid ([amv_movers]), the love button on each MOVER page,
 * the sign-in dialog and the voter bar.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function amv_enqueue() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;
	wp_enqueue_style( 'amv', AMV_URL . 'assets/vote.css', array(), AMV_VER );
	wp_enqueue_script( 'amv', AMV_URL . 'assets/vote.js', array(), AMV_VER, true );
	wp_localize_script( 'amv', 'AMV', array(
		'ajax'  => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'amv' ),
	) );
	add_action( 'wp_footer', 'amv_dialog' );
}

add_action( 'wp_enqueue_scripts', function () {
	if ( is_singular( 'mover' ) || is_post_type_archive( 'mover' ) || is_page_template( 'page-top50.php' ) || ( is_singular() && has_shortcode( (string) get_post_field( 'post_content', get_queried_object_id() ), 'amv_movers' ) ) ) {
		amv_enqueue();
	}
} );

function amv_heart() {
	return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7.5-4.6-9.6-9.3C.9 8.3 3 4.5 6.7 4.5c2.2 0 3.6 1.2 4.3 2.4h2c.7-1.2 2.1-2.4 4.3-2.4 3.7 0 5.8 3.8 4.3 7.2C19.5 16.4 12 21 12 21z"></path></svg>';
}

function amv_love_button( $mover_id, $count, $big = false ) {
	$show = '1' === get_option( 'amv_show_counts', '1' );
	return sprintf(
		'<button type="button" class="amv-love%s" data-mover="%d" aria-pressed="false" aria-label="%s">%s<span class="amv-n"%s>%s</span><span class="amv-l">%s</span></button>',
		$big ? ' big' : '',
		$mover_id,
		esc_attr( 'Love câu chuyện của ' . get_the_title( $mover_id ) ),
		amv_heart(),
		$show ? '' : ' hidden',
		$show ? esc_html( number_format_i18n( $count ) ) : '',
		$big ? 'Love câu chuyện này' : 'Love'
	);
}

function amv_bar() {
	$state = amv_vote_state();
	$msg   = array(
		'before' => 'Cổng bình chọn mở từ ' . esc_html( get_option( 'amv_open' ) ) . '.',
		'closed' => 'Cổng bình chọn đã đóng. Cảm ơn bạn đã tham gia.',
		'open'   => '',
	);
	return '<div class="amv-bar" data-state="' . esc_attr( $state ) . '">'
		. '<p class="amv-who" hidden></p>'
		. ( $msg[ $state ] ? '<p class="amv-note">' . $msg[ $state ] . '</p>' : '' )
		. '<button type="button" class="amv-signin">Đăng nhập để bình chọn</button>'
		. '<button type="button" class="amv-signout" hidden>Đăng xuất</button>'
		. '</div>';
}

/**
 * [amv_movers] – all published MOVER profiles as cards with a love button.
 * Attributes: search="1" shows the filter box (default on).
 */
add_shortcode( 'amv_movers', function ( $atts ) {
	$atts = shortcode_atts( array( 'search' => '1' ), $atts, 'amv_movers' );
	amv_enqueue();
	$movers = get_posts( array(
		'post_type'      => 'mover',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
	) );
	$counts = amv_all_counts();
	ob_start();
	echo '<div class="amv">';
	echo amv_bar(); // phpcs:ignore
	if ( ! $movers ) {
		echo '<p class="amv-empty">Hồ sơ MOVER sẽ được đăng tại đây sau khi qua vòng thẩm định.</p></div>';
		return ob_get_clean();
	}
	if ( '1' === $atts['search'] ) {
		echo '<div class="amv-filter"><label class="screen-reader-text" for="amv-q">Tìm MOVER</label><input id="amv-q" type="search" placeholder="Tìm theo tên hoặc đơn vị…" autocomplete="off"></div>';
	}
	echo '<ul class="amv-grid">';
	foreach ( $movers as $m ) {
		$unit = get_post_meta( $m->ID, '_amv_unit', true );
		$img  = get_the_post_thumbnail_url( $m, 'medium_large' );
		$sum  = has_excerpt( $m ) ? get_the_excerpt( $m ) : wp_trim_words( wp_strip_all_tags( $m->post_content ), 22 );
		printf(
			'<li class="amv-card" data-q="%s"><a class="amv-pic" href="%s">%s</a><div class="amv-body"><h3><a href="%s">%s</a></h3>%s<p>%s</p><div class="amv-foot">%s<a class="amv-read" href="%s">Đọc câu chuyện</a></div></div></li>',
			esc_attr( amv_name_key( get_the_title( $m ) . ' ' . $unit ) ),
			esc_url( get_permalink( $m ) ),
			$img ? '<img alt="" loading="lazy" src="' . esc_url( $img ) . '">' : '<span class="amv-initial" aria-hidden="true">' . esc_html( mb_substr( get_the_title( $m ), 0, 1 ) ) . '</span>',
			esc_url( get_permalink( $m ) ),
			esc_html( get_the_title( $m ) ),
			$unit ? '<span class="amv-unit">' . esc_html( $unit ) . '</span>' : '',
			esc_html( $sum ),
			amv_love_button( $m->ID, isset( $counts[ $m->ID ] ) ? $counts[ $m->ID ] : 0 ), // phpcs:ignore
			esc_url( get_permalink( $m ) )
		);
	}
	echo '</ul><p class="amv-none" hidden>Không tìm thấy MOVER phù hợp.</p></div>';
	return ob_get_clean();
} );

/* a MOVER page: portrait, unit, video, story, love button */
add_filter( 'the_content', function ( $content ) {
	if ( ! is_singular( 'mover' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$id    = get_the_ID();
	$unit  = get_post_meta( $id, '_amv_unit', true );
	$video = get_post_meta( $id, '_amv_video', true );
	$img   = get_the_post_thumbnail_url( $id, 'large' );
	$head  = '<div class="amv amv-single">' . amv_bar()
		. '<div class="amv-hero">'
		. ( $img ? '<img class="amv-portrait" alt="" src="' . esc_url( $img ) . '">' : '' )
		. '<div>' . ( $unit ? '<p class="amv-unit">' . esc_html( $unit ) . '</p>' : '' )
		. amv_love_button( $id, amv_count_for( $id ), true ) . '</div></div>';
	if ( $video ) {
		$embed = wp_oembed_get( $video );
		$head .= $embed ? '<div class="agm-video">' . $embed . '</div>' : '<p><a href="' . esc_url( $video ) . '" target="_blank" rel="noopener">Xem video của MOVER</a></p>';
	}
	$head .= '</div>';
	return $head . '<h2 class="amv-story">Câu chuyện MOVE</h2>' . $content;
}, 20 );

/* sign-in dialog, printed once */
function amv_dialog() {
	?>
	<dialog class="amv-dialog" id="amv-dialog" aria-labelledby="amv-dt">
	  <form method="dialog" class="amv-form" novalidate>
	    <button class="amv-x" value="cancel" aria-label="Đóng">×</button>
	    <h2 id="amv-dt">Đăng nhập để bình chọn</h2>
	    <p class="amv-help">Nhập đúng họ tên, mã số nhân viên và số điện thoại đã đăng ký với HR.</p>
	    <label for="amv-name">Họ và tên</label>
	    <input id="amv-name" name="name" autocomplete="off" list="amv-names" required>
	    <datalist id="amv-names"></datalist>
	    <label for="amv-msnv">Mã số nhân viên (MSNV)</label>
	    <input id="amv-msnv" name="msnv" autocomplete="off" required>
	    <label for="amv-phone">Số điện thoại</label>
	    <input id="amv-phone" name="phone" type="tel" inputmode="tel" autocomplete="off" required>
	    <p class="amv-err" role="alert" hidden></p>
	    <button type="submit" class="amv-go" value="default">Đăng nhập</button>
	    <p class="amv-fine">Chỉ dùng tài khoản của chính bạn. Mỗi lượt bình chọn được ghi lại kèm địa chỉ IP.</p>
	  </form>
	</dialog>
	<?php
}

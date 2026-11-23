<?php
/**
 * Content the team edits from wp-admin:
 *  - Sự kiện  (su_kien): date, all-day flag, place, countdown wording → the event card on the homepage
 *  - AgriS-thing (podcast): one entry per episode, with a video link and duration
 * Bản tin MOVE uses ordinary Posts.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type( 'su_kien', array(
		'labels'       => array(
			'name'          => 'Sự kiện',
			'singular_name' => 'Sự kiện',
			'add_new'       => 'Thêm sự kiện',
			'add_new_item'  => 'Thêm sự kiện mới',
			'edit_item'     => 'Sửa sự kiện',
			'all_items'     => 'Tất cả sự kiện',
			'menu_name'     => 'Sự kiện',
		),
		'public'       => false,
		'show_ui'      => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-calendar-alt',
		'menu_position'=> 21,
		'supports'     => array( 'title', 'thumbnail' ),
	) );

	register_post_type( 'podcast', array(
		'labels'       => array(
			'name'          => 'AgriS-thing',
			'singular_name' => 'Tập podcast',
			'add_new'       => 'Thêm tập',
			'add_new_item'  => 'Thêm tập podcast mới',
			'edit_item'     => 'Sửa tập podcast',
			'all_items'     => 'Tất cả các tập',
			'menu_name'     => 'AgriS-thing',
		),
		'public'       => true,
		'has_archive'  => 'agris-thing',
		'rewrite'      => array( 'slug' => 'agris-thing' ),
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-microphone',
		'menu_position'=> 22,
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
	) );
} );

/* ---------- meta boxes ---------- */

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'agm_event', 'Thời gian và địa điểm', 'agm_event_box', 'su_kien', 'normal', 'high' );
	add_meta_box( 'agm_podcast', 'Video và thời lượng', 'agm_podcast_box', 'podcast', 'normal', 'high' );
} );

function agm_event_box( $post ) {
	wp_nonce_field( 'agm_meta', 'agm_meta_nonce' );
	$at     = get_post_meta( $post->ID, '_agm_at', true );
	$allday = get_post_meta( $post->ID, '_agm_allday', true );
	$place  = get_post_meta( $post->ID, '_agm_place', true );
	$cd     = get_post_meta( $post->ID, '_agm_cd', true );
	?>
	<p><label for="agm_at"><strong>Ngày giờ bắt đầu</strong> (giờ Việt Nam)</label><br>
		<input type="datetime-local" id="agm_at" name="agm_at" value="<?php echo esc_attr( $at ); ?>" required></p>
	<p><label for="agm_end"><strong>Ngày giờ kết thúc</strong> (bỏ trống nếu chỉ diễn ra một lúc)</label><br>
		<input type="datetime-local" id="agm_end" name="agm_end" value="<?php echo esc_attr( get_post_meta( $post->ID, '_agm_end', true ) ); ?>"></p>
	<p><label for="agm_page"><strong>Trang con của sự kiện</strong> (để thẻ sự kiện bấm được)</label><br>
		<?php wp_dropdown_pages( array( 'name' => 'agm_page', 'id' => 'agm_page', 'selected' => (int) get_post_meta( $post->ID, '_agm_page', true ), 'show_option_none' => '— Không có —', 'option_none_value' => '0' ) ); ?>
		<span class="description">Ví dụ trang dùng mẫu "Lời cảm ơn" cho Vào vụ.</span></p>
	<p><label><input type="checkbox" name="agm_allday" value="1" <?php checked( $allday, '1' ); ?>> Sự kiện cả ngày (giữ trên trang chủ đến hết ngày)</label></p>
	<p><label for="agm_place"><strong>Địa điểm hoặc ghi chú</strong></label><br>
		<input type="text" id="agm_place" name="agm_place" class="widefat" value="<?php echo esc_attr( $place ); ?>" placeholder="Ví dụ: Khai vụ tại TTCA"></p>
	<p><label for="agm_cd"><strong>Chữ trong dòng đếm ngược</strong></label><br>
		Còn lại đến <input type="text" id="agm_cd" name="agm_cd" value="<?php echo esc_attr( $cd ); ?>" placeholder="sự kiện"> <span class="description">ví dụ: ngày khai vụ, ngày vào vụ</span></p>
	<p class="description">Trang chủ tự xếp sự kiện vào Sắp diễn ra hoặc Đang diễn ra theo thời gian, và ẩn sự kiện khi đã kết thúc. Ảnh đại diện dùng làm ảnh thẻ khi sự kiện đang diễn ra.</p>
	<?php
}

function agm_podcast_box( $post ) {
	wp_nonce_field( 'agm_meta', 'agm_meta_nonce' );
	$video = get_post_meta( $post->ID, '_agm_video', true );
	$dur   = get_post_meta( $post->ID, '_agm_duration', true );
	?>
	<p><label for="agm_video"><strong>Link video</strong> (YouTube, Vimeo, Microsoft Stream…)</label><br>
		<input type="url" id="agm_video" name="agm_video" class="widefat" value="<?php echo esc_attr( $video ); ?>" placeholder="https://"></p>
	<p><label for="agm_duration"><strong>Thời lượng</strong></label><br>
		<input type="text" id="agm_duration" name="agm_duration" value="<?php echo esc_attr( $dur ); ?>" placeholder="24:16"></p>
	<p class="description">Ảnh đại diện của tập dùng làm ảnh bìa trên trang chủ.</p>
	<?php
}

add_action( 'save_post', function ( $post_id ) {
	if ( ! isset( $_POST['agm_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['agm_meta_nonce'] ) ), 'agm_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$type = get_post_type( $post_id );
	if ( 'su_kien' === $type ) {
		$at = isset( $_POST['agm_at'] ) ? sanitize_text_field( wp_unslash( $_POST['agm_at'] ) ) : '';
		update_post_meta( $post_id, '_agm_at', preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $at ) ? $at : '' );
		$end = isset( $_POST['agm_end'] ) ? sanitize_text_field( wp_unslash( $_POST['agm_end'] ) ) : '';
		update_post_meta( $post_id, '_agm_end', preg_match( '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $end ) ? $end : '' );
		update_post_meta( $post_id, '_agm_page', isset( $_POST['agm_page'] ) ? absint( $_POST['agm_page'] ) : 0 );
		update_post_meta( $post_id, '_agm_allday', empty( $_POST['agm_allday'] ) ? '' : '1' );
		update_post_meta( $post_id, '_agm_place', isset( $_POST['agm_place'] ) ? sanitize_text_field( wp_unslash( $_POST['agm_place'] ) ) : '' );
		update_post_meta( $post_id, '_agm_cd', isset( $_POST['agm_cd'] ) ? sanitize_text_field( wp_unslash( $_POST['agm_cd'] ) ) : '' );
	} elseif ( 'podcast' === $type ) {
		update_post_meta( $post_id, '_agm_video', isset( $_POST['agm_video'] ) ? esc_url_raw( wp_unslash( $_POST['agm_video'] ) ) : '' );
		update_post_meta( $post_id, '_agm_duration', isset( $_POST['agm_duration'] ) ? sanitize_text_field( wp_unslash( $_POST['agm_duration'] ) ) : '' );
	}
} );

/* ---------- admin list columns for events ---------- */

add_filter( 'manage_su_kien_posts_columns', function ( $cols ) {
	$cols['agm_at'] = 'Thời gian';
	$cols['agm_place'] = 'Địa điểm';
	unset( $cols['date'] );
	return $cols;
} );
add_action( 'manage_su_kien_posts_custom_column', function ( $col, $id ) {
	if ( 'agm_at' === $col ) {
		$at = get_post_meta( $id, '_agm_at', true );
		echo $at ? esc_html( date_i18n( 'd/m/Y H:i', strtotime( $at ) ) ) : '—';
		if ( get_post_meta( $id, '_agm_allday', true ) ) {
			echo ' <em>(cả ngày)</em>';
		}
	} elseif ( 'agm_place' === $col ) {
		echo esc_html( get_post_meta( $id, '_agm_place', true ) );
	}
}, 10, 2 );

/* show the video above the text on a podcast page */
add_filter( 'the_content', function ( $content ) {
	if ( is_singular( 'podcast' ) && in_the_loop() && is_main_query() ) {
		$video = get_post_meta( get_the_ID(), '_agm_video', true );
		if ( $video ) {
			$embed = wp_oembed_get( $video );
			$content = ( $embed ? '<div class="agm-video">' . $embed . '</div>' : '<p><a class="btn" href="' . esc_url( $video ) . '" target="_blank" rel="noopener">Xem video</a></p>' ) . $content;
		}
	}
	return $content;
} );

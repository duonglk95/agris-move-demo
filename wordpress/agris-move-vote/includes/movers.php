<?php
/**
 * MOVER profiles: one entry per candidate whose story passed both review rounds.
 * Title = name, editor = MOVE story, featured image = portrait, excerpt = one-line summary on the card.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function amv_register_mover_type() {
	register_post_type( 'mover', array(
		'labels'       => array(
			'name'          => 'MOVER',
			'singular_name' => 'MOVER',
			'add_new'       => 'Thêm MOVER',
			'add_new_item'  => 'Thêm hồ sơ MOVER',
			'edit_item'     => 'Sửa hồ sơ MOVER',
			'all_items'     => 'Tất cả MOVER',
			'menu_name'     => 'MOVER',
			'featured_image'=> 'Ảnh chân dung',
			'set_featured_image' => 'Chọn ảnh chân dung',
		),
		'public'       => true,
		'has_archive'  => 'movers',
		'rewrite'      => array( 'slug' => 'mover' ),
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-awards',
		'menu_position'=> 23,
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
	) );
}
add_action( 'init', 'amv_register_mover_type' );

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'amv_mover', 'Thông tin MOVER', 'amv_mover_box', 'mover', 'normal', 'high' );
} );

function amv_mover_box( $post ) {
	wp_nonce_field( 'amv_mover', 'amv_mover_nonce' );
	$unit  = get_post_meta( $post->ID, '_amv_unit', true );
	$msnv  = get_post_meta( $post->ID, '_amv_msnv', true );
	$video = get_post_meta( $post->ID, '_amv_video', true );
	?>
	<p><label for="amv_unit"><strong>Phòng ban, đơn vị</strong></label><br>
		<input type="text" id="amv_unit" name="amv_unit" class="widefat" value="<?php echo esc_attr( $unit ); ?>" placeholder="Ví dụ: Phòng Nông vụ, AgriC"></p>
	<p><label for="amv_msnv"><strong>MSNV</strong> (chỉ hiện trong trang quản trị và file xuất kết quả)</label><br>
		<input type="text" id="amv_msnv" name="amv_msnv" value="<?php echo esc_attr( $msnv ); ?>"></p>
	<p><label for="amv_video"><strong>Link video 60 – 90 giây</strong> (YouTube, Vimeo, Microsoft Stream…)</label><br>
		<input type="url" id="amv_video" name="amv_video" class="widefat" value="<?php echo esc_attr( $video ); ?>" placeholder="https://"></p>
	<p class="description">Nội dung chính là câu chuyện MOVE. Đoạn tóm tắt (Excerpt) hiện trên thẻ ở danh sách MOVER. Số lượt love hiện tại: <strong><?php echo esc_html( amv_count_for( $post->ID ) ); ?></strong></p>
	<?php
}

add_action( 'save_post_mover', function ( $post_id ) {
	if ( ! isset( $_POST['amv_mover_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['amv_mover_nonce'] ) ), 'amv_mover' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, '_amv_unit', isset( $_POST['amv_unit'] ) ? sanitize_text_field( wp_unslash( $_POST['amv_unit'] ) ) : '' );
	update_post_meta( $post_id, '_amv_msnv', isset( $_POST['amv_msnv'] ) ? amv_msnv_key( sanitize_text_field( wp_unslash( $_POST['amv_msnv'] ) ) ) : '' );
	update_post_meta( $post_id, '_amv_video', isset( $_POST['amv_video'] ) ? esc_url_raw( wp_unslash( $_POST['amv_video'] ) ) : '' );
} );

/* vote count column in the MOVER list */
add_filter( 'manage_mover_posts_columns', function ( $cols ) {
	$new = array();
	foreach ( $cols as $k => $v ) {
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['amv_unit']  = 'Đơn vị';
			$new['amv_votes'] = 'Lượt love';
		}
	}
	return $new;
} );
add_action( 'manage_mover_posts_custom_column', function ( $col, $id ) {
	if ( 'amv_unit' === $col ) {
		echo esc_html( get_post_meta( $id, '_amv_unit', true ) );
	} elseif ( 'amv_votes' === $col ) {
		echo '<strong>' . esc_html( amv_count_for( $id ) ) . '</strong>';
	}
}, 10, 2 );

/* when a MOVER is deleted for good, its votes go with it */
add_action( 'before_delete_post', function ( $post_id ) {
	if ( 'mover' === get_post_type( $post_id ) ) {
		global $wpdb;
		$t = amv_tables();
		$wpdb->delete( $t['votes'], array( 'mover_id' => (int) $post_id ), array( '%d' ) );
	}
} );

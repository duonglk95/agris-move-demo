<?php
/**
 * Template Name: Cuộc thi ảnh (Khoảnh khắc)
 * Upload a photo, it is framed in the browser (frame + name, place, center, fixed hashtags), published at once,
 * then shown in the gallery with a love button (sign-in by MSNV, same as TOP 50).
 */
get_header();
while ( have_posts() ) : the_post();
	$pid   = get_the_ID();
	$order = ( isset( $_GET['xep'] ) && 'love' === $_GET['xep'] ) ? 'love' : 'new'; // phpcs:ignore
	$items = agm_entries( $pid, $order );
	?>
<main id="top" class="tk">
  <?php get_template_part( 'parts/mini-hero' ); ?>
  <section class="band">
    <div class="in st-grid">
      <form class="tk-form st-form" data-contest data-page="<?php echo esc_attr( $pid ); ?>">
        <h2>Gửi ảnh dự thi</h2>
        <label>Ảnh của bạn<input type="file" name="photo" accept="image/jpeg,image/png" required></label>
        <label>Tên ảnh<input name="title" maxlength="60" required placeholder="Bình minh trên ruộng mía"></label>
        <label>Mô tả ngắn <small>(không bắt buộc)</small><textarea name="desc" maxlength="300" placeholder="Khoảnh khắc này có gì đặc biệt?"></textarea></label>
        <label>Tên bạn<input name="author" maxlength="60" required autocomplete="name"></label>
        <label>Địa điểm chụp<input name="place" maxlength="60" required placeholder="Nông trường 3, Tây Ninh"></label>
        <label>Đơn vị<select name="center" required><option value="">Chọn đơn vị</option><?php foreach ( AGM_CENTERS as $c ) { echo '<option>' . esc_html( $c ) . '</option>'; } ?></select></label>
        <p class="tk-msg" role="status" hidden></p>
        <button class="btn" type="submit">Gửi ảnh dự thi</button>
      </form>
      <div class="st-preview"><p class="st-cap">Xem trước ảnh dự thi</p><canvas id="ctCanvas" width="1080" height="1350"></canvas></div>
    </div>
  </section>
  <section class="band" id="thu-vien">
    <div class="in">
      <div class="st-head"><h2 class="tk-wall-h">Thư viện ảnh dự thi <span><?php echo esc_html( count( $items ) ); ?></span></h2>
        <nav class="st-sort"><a href="?xep=new#thu-vien"<?php echo 'new' === $order ? ' aria-current="true"' : ''; ?>>Mới nhất</a><a href="?xep=love#thu-vien"<?php echo 'love' === $order ? ' aria-current="true"' : ''; ?>>Nhiều love nhất</a></nav></div>
      <?php if ( function_exists( 'amv_bar' ) ) { echo amv_bar(); } // phpcs:ignore ?>
      <?php if ( $items ) : ?>
      <ul class="st-gallery">
        <?php foreach ( $items as $p ) : $d = agm_entry_data( $p ); ?>
        <li id="anh-<?php echo esc_attr( $p->ID ); ?>">
          <button type="button" class="st-thumb" data-full="<?php echo esc_url( $d['framed'] ); ?>" data-desc="<?php echo esc_attr( $d['desc'] ); ?>" data-title="<?php echo esc_attr( $d['title'] ); ?>"><img src="<?php echo esc_url( get_the_post_thumbnail_url( $p, 'medium_large' ) ); ?>" alt="<?php echo esc_attr( $d['title'] ); ?>" loading="lazy"></button>
          <div class="st-meta"><h3><?php echo esc_html( $d['title'] ); ?></h3><p><?php echo esc_html( $d['author'] . ' · ' . $d['center'] ); ?></p>
          <?php if ( function_exists( 'amv_love_button' ) ) { echo amv_love_button( $p->ID, amv_count_for( $p->ID ) ); } // phpcs:ignore ?></div>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php else : ?><p class="tk-empty">Chưa có ảnh nào. Bạn mở màn nhé!</p><?php endif; ?>
    </div>
  </section>
  <dialog class="cf-dlg st-share" id="stShare"><form method="dialog">
    <h2>Đã gửi ảnh dự thi!</h2>
    <p>Sao chép đoạn dưới và đăng lên Viva Engage để rủ mọi người thả love cho bạn.</p>
    <textarea id="stShareTxt" readonly rows="5"></textarea>
    <p class="cf-msg" id="stCopied" hidden>Đã sao chép</p>
    <div class="cf-act"><button class="cf-x" value="close">Đóng</button><button type="button" class="btn" id="stCopy">Sao chép</button></div>
  </form></dialog>
  <dialog class="st-view" id="stView"><form method="dialog"><button class="cf-x st-x" aria-label="Đóng">×</button><img alt=""><h3></h3><p></p></form></dialog>
</main>
<?php endwhile; get_footer();

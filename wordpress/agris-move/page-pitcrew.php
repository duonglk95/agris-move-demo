<?php
/**
 * Template Name: Đăng ký Pit Crew
 */
get_header();
while ( have_posts() ) : the_post();
	$tours = agm_pit_tours(); $open = agm_pit_open();
	?>
<main id="top" class="tk">
  <?php get_template_part( 'parts/mini-hero' ); ?>
  <section class="band">
    <div class="in st-grid wide pit">
      <form class="tk-form st-form" data-pit<?php echo $open ? '' : ' hidden'; ?>>
        <h2>Đăng ký Pit Crew</h2>
        <p class="tk-note">Hạn đăng ký: hết ngày <?php echo esc_html( wp_date( 'd/m/Y', strtotime( agm_pit_deadline() ) ) ); ?>. Nhập đúng thông tin đã đăng ký với HR.</p>
        <label>Họ và tên<input name="name" required autocomplete="name"></label>
        <label>MSNV<input name="msnv" required autocomplete="off"></label>
        <label>Số điện thoại<input name="phone" required inputmode="tel" autocomplete="off"></label>
        <fieldset><legend>Chọn tour</legend>
          <?php foreach ( $tours as $k => $t ) : ?>
          <label class="pit-tour"><input type="checkbox" name="tours[]" value="<?php echo esc_attr( $k ); ?>"><span><b><?php echo esc_html( $t[0] ); ?></b><?php echo esc_html( $t[1] . ' – ' . $t[2] ); ?></span></label>
          <?php endforeach; ?>
        </fieldset>
        <fieldset><legend>Đơn vị</legend><div class="chips"><?php foreach ( AGM_CENTERS as $c ) { printf( '<label><input type="radio" name="center" value="%1$s"><span>%1$s</span></label>', esc_html( $c ) ); } ?></div></fieldset>
        <fieldset><legend>Bạn muốn góp sức ở đâu <small>(chọn nhiều)</small></legend><div class="chips"><?php foreach ( AGM_ROLES as $r ) { printf( '<label><input type="checkbox" name="roles[]" value="%1$s"><span>%1$s</span></label>', esc_html( $r ) ); } ?></div></fieldset>
        <fieldset><legend>Size áo Pit Crew</legend><div class="chips"><?php foreach ( AGM_SIZES as $s ) { printf( '<label><input type="radio" name="size" value="%1$s"><span>%1$s</span></label>', esc_html( $s ) ); } ?></div></fieldset>
        <label>Ghi chú <small>(không bắt buộc)</small><textarea name="note" maxlength="300" placeholder="Ăn chay, có bằng lái…"></textarea></label>
        <p class="tk-msg" role="status" hidden></p>
        <button class="btn" type="submit">Gửi đăng ký</button>
      </form>
      <div class="pit-side">
        <div class="pit-done" hidden>
          <p class="pit-ok">Đăng ký thành công</p>
          <h2 class="pit-num"></h2>
          <ul class="pit-st"></ul>
          <p class="tk-note">Ban tổ chức sẽ xét chọn và báo kết quả. Bạn tra cứu lại bằng MSNV và số điện thoại ở khung bên dưới.</p>
          <canvas id="pitCard" width="1080" height="1350"></canvas>
          <div class="pit-act"><button type="button" class="btn" id="pitDown">Tải thẻ Pit Crew</button><button type="button" class="btn ghost" id="pitCopy">Sao chép để share Engage</button></div>
          <p class="cf-msg" id="pitCopied" hidden>Đã sao chép</p>
        </div>
        <form class="tk-form pit-check" data-pitcheck>
          <h2>Tra cứu kết quả</h2>
          <?php if ( ! $open ) : ?><p class="tk-note">Đã hết hạn đăng ký. Bạn tra cứu kết quả xét chọn ở đây.</p><?php endif; ?>
          <label>Họ và tên<input name="name" required></label>
          <label>MSNV<input name="msnv" required autocomplete="off"></label>
          <label>Số điện thoại<input name="phone" required inputmode="tel" autocomplete="off"></label>
          <p class="tk-msg" role="status" hidden></p>
          <button class="btn ghost" type="submit">Tra cứu</button>
        </form>
      </div>
    </div>
  </section>
</main>
<?php endwhile; get_footer();

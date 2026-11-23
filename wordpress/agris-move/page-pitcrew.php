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
      <?php if ( ! $open ) : ?><div class="tk-form"><h2>Đã đóng đăng ký</h2><p class="tk-note">Cảm ơn bạn đã quan tâm. Ban tổ chức sẽ báo kết quả tới những bạn đã đăng ký.</p></div><?php endif; ?>
      <form class="tk-form st-form" data-pit<?php echo $open ? '' : ' hidden'; ?>>
        <h2>Đăng ký Pit Crew</h2>
        <label>Họ và tên<input name="name" required autocomplete="name"></label>
        <label>MSNV<input name="msnv" required autocomplete="off"></label>
        <fieldset><legend>Đơn vị</legend><div class="chips"><?php foreach ( AGM_CENTERS as $c ) { printf( '<label><input type="radio" name="center" value="%1$s"><span>%1$s</span></label>', esc_html( $c ) ); } ?></div></fieldset>
        <label>Số điện thoại<input name="phone" required inputmode="tel" autocomplete="off"></label>
        <fieldset><legend>Chọn tour</legend>
          <?php foreach ( $tours as $k => $t ) : ?>
          <label class="pit-tour"><input type="checkbox" name="tours[]" value="<?php echo esc_attr( $k ); ?>"><span><b><?php echo esc_html( $t[0] ); ?></b><?php echo esc_html( $t[1] . ' – ' . $t[2] ); ?></span></label>
          <?php endforeach; ?>
        </fieldset>
        <fieldset><legend>Bạn muốn góp sức ở khâu nào <small>(chọn nhiều)</small></legend><div class="chips"><?php foreach ( AGM_ROLES as $r ) { printf( '<label><input type="checkbox" name="roles[]" value="%1$s"><span>%1$s</span></label>', esc_html( $r ) ); } ?><label><input type="checkbox" name="role_other_on" value="1" data-other><span>Khác</span></label></div>
          <input name="role_other" maxlength="80" placeholder="Bạn muốn góp sức ở khâu nào khác?" hidden></fieldset>
        <fieldset><legend>Size áo Pit Crew</legend><div class="chips"><?php foreach ( AGM_SIZES as $s ) { printf( '<label><input type="radio" name="size" value="%1$s"><span>%1$s</span></label>', esc_html( $s ) ); } ?></div></fieldset>
        <label>Lưu ý cá nhân <small>(không bắt buộc) để BTC sắp xếp và hỗ trợ bạn chu đáo</small><textarea name="note" maxlength="300" placeholder="Say xe, ăn chay, dị ứng…"></textarea></label>
        <p class="tk-msg" role="status" hidden></p>
        <button class="btn" type="submit">Gửi đăng ký</button>
      </form>
      <div class="pit-side">
        <div class="pit-done" hidden>
          <p class="pit-ok">Đăng ký thành công</p>
          <h2 class="pit-num"></h2>
          <ul class="pit-st"></ul>
          <p class="tk-note">Ban tổ chức sẽ xét chọn và báo kết quả tới bạn.</p>
          <canvas id="pitCard" width="1080" height="1350"></canvas>
          <div class="pit-act"><button type="button" class="btn" id="pitDown">Tải thẻ Pit Crew</button><button type="button" class="btn ghost" id="pitCopy">Sao chép để share Engage</button></div>
          <p class="cf-msg" id="pitCopied" hidden>Đã sao chép</p>
        </div>
      </div>
    </div>
  </section>
</main>
<?php endwhile; get_footer();

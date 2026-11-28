<?php
/**
 * Template Name: Đăng ký Pit Crew
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post();
	$tours = agm_pit_tours(); $open = agm_pit_open();
	?>
<main id="top" class="tk">
  <?php get_template_part( 'parts/mini-hero' ); ?>
  <section class="band pit-sec">
    <div class="in pit pit-wrap">
      <div class="pit-art"><img src="<?php echo esc_url( agm_img( 'abi-pitcrew-tractor.webp' ) ); ?>" alt="" aria-hidden="true"></div>
      <div class="pit-main">
      <?php if ( ! $open ) : ?><div class="tk-form"><h2>Đã đóng đăng ký</h2><p class="tk-note">Cảm ơn bạn đã quan tâm. Ban tổ chức sẽ báo kết quả tới những bạn đã đăng ký.</p></div><?php endif; ?>
      <form class="tk-form st-form" data-pit<?php echo $open ? '' : ' hidden'; ?>>
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
    </div>
  </section>
  <div class="seam" data-next="#e9f6fb" data-h="90" data-tones="#d3eadb,#9bd8b1d0" data-line="lime"></div>
  <section class="band ct-rules" id="faq">
    <div class="in ct-faq">
      <div><h2 class="gt">Câu hỏi<br>thường gặp</h2><img src="<?php echo esc_url( agm_img( 'abi-flag.webp' ) ); ?>" alt="" aria-hidden="true"></div>
      <div class="ct-acc">
        <details open><summary>Pit Crew là gì?</summary><div><p>Pit Crew là nhóm CBNV tình nguyện đi cùng đoàn HO đến các nhà máy và vùng nguyên liệu trong mùa Vào vụ, để trực tiếp tiếp sức, động viên và giao lưu với đồng đội đang làm việc ở tuyến đầu.</p></div></details>
        <details><summary>Ai được đăng ký?</summary><div><p>Toàn thể CBNV AgriS thuộc HO, ComC, ProC và AgriC. Bạn nhập đúng họ tên, MSNV và số điện thoại đã đăng ký với HR để hệ thống xác nhận.</p></div></details>
        <details><summary>Tôi có thể đăng ký cả hai chuyến không?</summary><div><p>Có. Bạn chọn một hoặc cả hai chuyến; ban tổ chức xét chọn riêng cho từng chuyến.</p></div></details>
        <details><summary>Ban tổ chức xét chọn thế nào?</summary><div><p>Không giới hạn số lượng đăng ký. Nếu số người vượt khả năng sắp xếp của chuyến đi, những bạn đăng ký sớm hơn được ưu tiên. Số thứ tự Pit Crew bạn nhận sau khi đăng ký chính là thứ tự này.</p></div></details>
        <details><summary>Khi nào tôi biết kết quả?</summary><div><p>Sau khi đóng đăng ký (23:59 ngày <?php echo esc_html( wp_date( 'd/m/Y', strtotime( agm_pit_deadline() ) ) ); ?>), ban tổ chức sẽ thông báo kết quả trực tiếp đến bạn qua email hoặc Teams.</p></div></details>
        <details><summary>Tham gia có cần xin phép quản lý không?</summary><div><p>Bạn nên trao đổi trước với quản lý trực tiếp để sắp xếp công việc trong những ngày đi cùng đoàn. Ban tổ chức sẽ phối hợp với đơn vị khi xác nhận danh sách.</p></div></details>
        <details><summary>Chi phí đi lại, ăn ở thế nào?</summary><div><p>Chi phí đi lại, ăn ở trong chuyến đi do chương trình sắp xếp. Bạn ghi rõ nhu cầu riêng (say xe, ăn chay, dị ứng…) ở ô Lưu ý cá nhân để ban tổ chức chuẩn bị chu đáo.</p></div></details>
        <details><summary>Tôi muốn sửa thông tin đã đăng ký?</summary><div><p>Bạn bấm Liên hệ chương trình ở cuối trang và ghi MSNV cùng nội dung cần sửa, ban tổ chức sẽ cập nhật giúp bạn.</p></div></details>
      </div>
    </div>
  </section>
</main>
<?php endwhile; get_footer();

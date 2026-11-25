<?php
/**
 * Template Name: TOP 50 MOVERS
 * The award page. Text in the page editor shows as an announcement under the intro;
 * MOVER profiles and voting come from the "AgriS MOVE – Bình chọn" plugin.
 */
get_header();
while ( have_posts() ) :
	the_post();
	?>
<main id="top" class="t50">
  <figure class="banner" style="margin:0">
    <img src="<?php echo esc_url( agm_top50_banner() ); ?>" alt="AgriS TOP 50 MOVERS, linh vật ABI đứng trên bục vinh danh" width="1600" height="900">
    <p class="lede">Giải thưởng văn hoá thường niên của AgriS, do chính AgriS-ers bình chọn.</p>
  </figure>

  <div class="in">
    <section class="intro" aria-labelledby="t-intro">
      <div>
        <span class="kicker">TOP 50 MOVERS Awards · Mùa 1 · Niên độ 2026 – 2027</span>
        <h1 id="t-intro">Dám bước ra, tự tin thể hiện giá trị</h1>
        <p>Mỗi ngày, ở văn phòng, nhà máy hay vùng nguyên liệu, có rất nhiều AgriS-er đang lặng lẽ MOVE: chủ động nhận việc, thử một cách làm mới, hỗ trợ đồng nghiệp. TOP 50 MOVERS là giải thưởng văn hoá thường niên của AgriS, do chính AgriS-ers bình chọn, để những câu chuyện ấy được kể ra và được ghi nhận.</p>
        <div class="actions">
          <a class="btn main" id="ctaMain" href="<?php echo esc_url( agm_top50_guide_url() ); ?>">Xem hướng dẫn tham gia <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 8h11M9 4l4 4-4 4"></path></svg></a>
          <a class="btn ghost" href="#cac-mover">Xem các MOVER</a>
        </div>
      </div>
      <aside class="status" aria-live="polite">
        <p class="phase"><span id="phaseLabel">Mở nhận hồ sơ sau</span><b id="phaseTitle">Nộp hồ sơ từ 01/12/2026</b></p>
        <div class="tiles" role="timer" id="phaseTimer">
          <div><b id="cD">--</b><span>Ngày</span></div>
          <div><b id="cH">--</b><span>Giờ</span></div>
          <div><b id="cM">--</b><span>Phút</span></div>
          <div><b id="cS">--</b><span>Giây</span></div>
        </div>
      </aside>
    </section>
  </div>

  <?php if ( trim( get_the_content() ) ) : ?>
  <section class="sec" id="thong-bao">
    <div class="in entry"><?php the_content(); ?></div>
  </section>
  <?php endif; ?>





  <?php if ( shortcode_exists( 'amv_movers' ) ) : ?>
  <section class="sec mint" id="cac-mover" aria-labelledby="t-mover">
    <div class="in">
      <div class="sec-head">
        <h2 id="t-mover">MOVER tiêu biểu</h2>
        <p>Đọc câu chuyện MOVE của đồng nghiệp và thả “love” cho những câu chuyện bạn yêu thích.</p>
      </div>
      <?php echo do_shortcode( '[amv_movers]' ); // phpcs:ignore ?>
    </div>
  </section>
  <?php endif; ?>

  <section class="sec" id="tinh-than" aria-labelledby="t-move">
    <div class="in">
      <div class="sec-head"><h2 id="t-move">MOVER là ai?</h2><p>Là AgriS-er thể hiện ít nhất một trong bốn tinh thần MOVE qua một câu chuyện có hành động và kết quả cụ thể.</p></div>
      <div class="move">
        <div><b>Action</b><span>Chủ động hành động, tham gia các hoạt động nội bộ mà không đợi được giao hay nhắc.</span></div>
        <div><b>Innovation</b><span>Thử một cách làm mới hoặc khác trước, dù chỉ là cải tiến nhỏ.</span></div>
        <div><b>Impact</b><span>Tạo ra thay đổi cụ thể cho đội nhóm, khách hàng hoặc đơn vị.</span></div>
        <div><b>As One</b><span>Phối hợp, hỗ trợ, chia sẻ và lan toả đến đồng nghiệp, đơn vị khác.</span></div>
      </div>
      <div class="apply-cta">
        <a class="btn main" href="<?php echo esc_url( get_theme_mod( 'agm_apply_link' ) ? get_theme_mod( 'agm_apply_link' ) : agm_top50_guide_url() ); ?>">Ứng cử ngay <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 8h11M9 4l4 4-4 4"></path></svg></a>
        <span>Bạn là một MOVER? Kể câu chuyện của bạn và nhận sự ghi nhận từ đồng nghiệp.</span>
      </div>
    </div>
  </section>




  <div class="in contact">
    <div class="box">
      <h2>Liên hệ Ban Tổ chức</h2>
      <div class="meta"><span>Email: [email]</span><span>Điện thoại: [số điện thoại]</span><span>Website: www.agrismove.com</span></div>
    </div>
  </div>
</main>
	<?php
endwhile;
get_footer();

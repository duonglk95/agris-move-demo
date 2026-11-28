<?php
/**
 * Template Name: TOP 50 – Ứng cử
 * Candidacy form in four steps; dossier is checked against the HR list and stored for review.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post();
	$open = agm_apply_open(); list( $from, $to ) = agm_apply_window();
	$tips = array(
		's' => array( 'Bối cảnh', 'Chuyện xảy ra ở đâu, khi nào, khó khăn là gì?', 'Vụ 2025, khâu nhập liệu ghi nhận ruộng mía còn làm tay, mỗi tổ mất 2 giờ mỗi ngày…' ),
		't' => array( 'Nhiệm vụ', 'Bạn được giao hay tự nhận việc gì?', 'Tôi tự đề xuất số hoá phiếu ghi nhận để cả tổ nhập ngay trên điện thoại…' ),
		'a' => array( 'Hành động', 'Bạn đã làm gì, cụ thể từng bước?', 'Tôi dựng mẫu form, hướng dẫn từng người, thu góp ý và chỉnh lại sau 2 tuần…' ),
		'r' => array( 'Kết quả', 'Điều gì đã thay đổi? Có con số càng tốt.', 'Mỗi tổ tiết kiệm 2 giờ mỗi ngày, số liệu về văn phòng ngay trong ca…' ),
	);
	?>
<main id="top" class="tk g50 ap">
  <section class="tk-hero">
    <div class="in tk-hero-txt g50-hero">
      <p class="tk-kicker">TOP 50 MOVERS · Mùa 1</p>
      <h1 class="gt"><?php the_title(); ?></h1>
      <div class="tk-intro"><?php the_content(); ?></div>
      <p class="ap-when"><?php echo $open ? 'Đang nhận hồ sơ đến hết ' : 'Mở nhận hồ sơ từ ' . esc_html( wp_date( 'd/m/Y', strtotime( $from ) ) ) . ' đến '; ?><?php echo esc_html( wp_date( 'd/m/Y', strtotime( $to ) ) ); ?> · <a href="<?php echo esc_url( agm_top50_guide_url() ); ?>">Xem hướng dẫn và thể lệ</a></p>
    </div>
  </section>

  <section class="band g50-steps-sec">
    <div class="in ap-wrap">
      <?php if ( ! $open ) : ?>
      <div class="pc-step ap-closed"><h3><span>!</span>Chưa đến thời gian nhận hồ sơ</h3><p>Bạn xem trước các mục cần chuẩn bị bên dưới và quay lại từ <?php echo esc_html( wp_date( 'd/m/Y', strtotime( $from ) ) ); ?> nhé.</p></div>
      <?php endif; ?>
      <ol class="ap-progress" aria-hidden="true"><li class="on"><span>1</span>Thông tin</li><li><span>2</span>Câu chuyện</li><li><span>3</span>Video</li><li><span>4</span>Xác nhận</li></ol>
      <form class="ap-form" data-apply novalidate>
        <fieldset class="pc-step" <?php disabled( ! $open ); ?>>
          <h3><span>1</span>Thông tin của bạn</h3>
          <p class="ap-hint">Nhập đúng thông tin đã đăng ký với HR để hệ thống xác nhận.</p>
          <div class="ap-row">
            <label>Họ và tên<input name="name" required autocomplete="name"></label>
            <label>MSNV<input name="msnv" required autocomplete="off"></label>
          </div>
          <div class="ap-row">
            <label>Số điện thoại<input name="phone" required inputmode="tel" autocomplete="off"></label>
            <label>Email<input name="email" type="email" required autocomplete="email"></label>
          </div>
          <div class="ap-row">
            <label>Phòng ban / bộ phận<input name="dept" required placeholder="Phòng Nông vụ"></label>
            <label>Đơn vị<select name="center" required><option value="">Chọn đơn vị</option><?php foreach ( AGM_CENTERS as $c ) { echo '<option>' . esc_html( $c ) . '</option>'; } ?></select></label>
          </div>
          <fieldset class="ap-sub"><legend>Bạn tham gia theo hình thức</legend><div class="chips">
            <?php foreach ( array( 'Tự ứng cử', 'Được đồng nghiệp đề cử', 'Được đơn vị đề cử' ) as $i => $v ) { printf( '<label><input type="radio" name="via" value="%1$s"%2$s><span>%1$s</span></label>', esc_html( $v ), 0 === $i ? ' checked' : '' ); } ?>
          </div></fieldset>
        </fieldset>

        <fieldset class="pc-step" <?php disabled( ! $open ); ?>>
          <h3><span>2</span>Câu chuyện MOVE</h3>
          <p class="ap-hint">Kể theo bốn ý, nêu rõ bạn đã chủ động làm gì và điều gì đã thay đổi. Mỗi ý tối đa 600 ký tự.</p>
          <label>Tên câu chuyện<input name="title" required maxlength="90" placeholder="Số hoá phiếu ghi nhận ruộng mía"></label>
          <div class="ap-star">
            <?php foreach ( $tips as $k => $t ) : ?>
            <label><span class="ap-l"><b><?php echo esc_html( $t[0] ); ?></b> <small><?php echo esc_html( $t[1] ); ?></small><small class="ap-n" data-n="<?php echo esc_attr( $k ); ?>">0/600</small></span><textarea name="<?php echo esc_attr( $k ); ?>" required maxlength="600" rows="4" placeholder="<?php echo esc_attr( $t[2] ); ?>"></textarea></label>
            <?php endforeach; ?>
          </div>
          <fieldset class="ap-sub"><legend>Câu chuyện thể hiện tinh thần nào? <small>(chọn ít nhất một)</small></legend><div class="chips">
            <?php foreach ( AGM_SPIRITS as $v ) { printf( '<label><input type="checkbox" name="spirits[]" value="%1$s"><span>%1$s</span></label>', esc_html( $v ) ); } ?>
          </div></fieldset>
        </fieldset>

        <fieldset class="pc-step" <?php disabled( ! $open ); ?>>
          <h3><span>3</span>Video và ảnh</h3>
          <p class="ap-hint">Video 60 – 90 giây (bắt buộc): giới thiệu bản thân, điều bạn muốn thay đổi ở AgriS, hoặc kể chính câu chuyện MOVE.</p>
          <label>Link video <small>(OneDrive, Google Drive, YouTube… nhớ mở quyền xem)</small><input name="video" type="url" placeholder="https://"></label>
          <p class="ap-or"><span>hoặc</span></p>
          <label>Tải file video <small>(MP4/MOV, tối đa <span data-max></span>)</small><input name="video_file" type="file" accept="video/mp4,video/quicktime"></label>
          <label>Ảnh chân dung <small>(không bắt buộc, dùng làm ảnh đại diện MOVER)</small><input name="photo" type="file" accept="image/jpeg,image/png"></label>
        </fieldset>

        <fieldset class="pc-step ap-review" <?php disabled( ! $open ); ?>>
          <h3><span>4</span>Xem lại và nộp</h3>
          <dl class="ap-sum"></dl>
          <label class="ap-consent"><input type="checkbox" name="consent" value="1" required <?php disabled( ! $open ); ?>><span>Thông tin là sự thật. Tôi đồng ý để AgriS sử dụng câu chuyện, hình ảnh và video cho mục đích truyền thông nội bộ.</span></label>
        </fieldset>
        <div class="ap-nav"><button type="button" class="btn ghost" data-prev>← Quay lại</button><button type="button" class="btn" data-next>Tiếp tục →</button><button class="btn" type="submit" data-send <?php disabled( ! $open ); ?>>Nộp hồ sơ</button></div>
        <p class="tk-msg ap-msg" role="alert" hidden></p>
      </form>

      <div class="pc-step ap-done" hidden>
        <h3><span>✓</span>Đã nhận hồ sơ của bạn</h3>
        <p class="ap-code"></p>
        <p>Ban Tổ chức sẽ thẩm định qua hai vòng (HR đơn vị và Ban Tổ chức). Hồ sơ hợp lệ sẽ được đăng lên trang TOP 50 MOVERS; nếu cần bổ sung, bạn sẽ nhận email kèm gợi ý.</p>
        <a class="btn" href="<?php echo esc_url( agm_top50_url() ); ?>">Về trang TOP 50 MOVERS</a>
      </div>
    </div>
  </section>
</main>
<?php endwhile; get_footer();

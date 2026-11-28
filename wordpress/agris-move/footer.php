<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="seam" data-next="#dcefe3" data-h="100" data-tones="#eaf5ee,#cfe7d8"></div>
<footer class="foot">
  <div class="ribbon" data-ribbon></div>
  <div class="in foot-in">
    <div class="foot-brand">
      <a class="brand" href="<?php echo esc_url( is_front_page() ? '#top' : home_url( '/' ) ); ?>" aria-label="AgriS MOVE – Trang chủ"><img alt="AgriS MOVE" src="<?php echo esc_url( agm_img( 'logo-move.png' ) ); ?>"></a>
      <p>Website văn hoá nội bộ của AgriS-ers.</p>
      <button type="button" class="btn" data-cf-open>Liên hệ chương trình <?php echo agm_arrow( 'chev' ); // phpcs:ignore ?></button>
    </div>
    <?php agm_footer_sitemap(); ?>
    <small>© <?php echo esc_html( wp_date( 'Y' ) ); ?> AgriS</small>
  </div>
</footer>
<dialog class="cf-dlg" id="cfDlg" aria-labelledby="cfTitle">
  <form data-cf>
    <h2 id="cfTitle">Liên hệ chương trình</h2>
    <label>Tên người gửi <small>(không bắt buộc)</small><input name="name" maxlength="120" autocomplete="name"></label>
    <label>Vấn đề<select name="topic" required>
      <option value="">Chọn vấn đề</option><option>Góp ý chương trình</option><option>Hỏi về sự kiện</option><?php if ( agm_show( 'top50' ) ) : ?><option>TOP 50 MOVERS</option><?php endif; ?><option>Podcast</option><option>Khác</option>
    </select></label>
    <label>Nội dung lời nhắn<textarea name="message" required maxlength="3000"></textarea></label>
    <p class="cf-msg" role="status" hidden></p>
    <div class="cf-act"><button type="button" class="cf-x" data-cf-close>Đóng</button><button class="btn" type="submit">Gửi lời nhắn</button></div>
  </form>
</dialog>
<?php wp_footer(); ?>
</body>
</html>

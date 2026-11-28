<?php
/** Postcard studio: photo strip on top, then wish fields beside the live preview. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$photos = agm_postcard_photos();
?>
<form class="pc-studio" data-postcard>
  <div class="pc-step">
    <h3><span>1</span>Chọn một khoảnh khắc</h3>
    <?php if ( $photos ) : ?>
    <div class="pc-pick">
      <?php foreach ( array_slice( $photos, 0, 36 ) as $i => $ph ) : ?>
      <label class="pc-opt"><input type="radio" name="photo" value="<?php echo esc_url( $ph['raw'] ); ?>" data-credit="<?php echo esc_attr( implode( ' · ', array_filter( array( $ph['title'], $ph['author'], $ph['place'] ) ) ) ); ?>" <?php checked( 0, $i ); ?>><img src="<?php echo esc_url( $ph['raw'] ); ?>" alt="<?php echo esc_attr( $ph['title'] ); ?>" loading="lazy"><span><b><?php echo esc_html( $ph['title'] ); ?></b><?php echo esc_html( implode( ' · ', array_filter( array( $ph['author'], $ph['place'] ) ) ) ); ?></span></label>
      <?php endforeach; ?>
    </div>
    <?php else : ?><p class="tk-empty">Ảnh từ cuộc thi sẽ xuất hiện ở đây.</p><?php endif; ?>
  </div>
  <div class="pc-grid">
    <div class="tk-form pc-fields">
      <h3><span>2</span>Viết lời chúc</h3>
      <label>Từ <small>(tên bạn)</small><input name="from" maxlength="40" autocomplete="name"></label>
      <label>Gửi đến<input name="to" maxlength="50" placeholder="Đội xe Nông trường 3"></label>
      <label>Lời chúc <small data-count>0/220</small><textarea name="wish" maxlength="220" placeholder="Chúc cả đội một mùa vụ về đích an toàn…"></textarea></label>
    </div>
    <div class="pc-preview">
      <div class="pc-canvas-wrap"><canvas id="pcCanvas" width="1600" height="1100"></canvas>
        <div class="pc-hint" aria-hidden="true"><svg class="pc-hand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11V5a1.5 1.5 0 0 1 3 0v5m0-1a1.5 1.5 0 0 1 3 0v2m0-1a1.5 1.5 0 0 1 3 0v4c0 3.5-2.5 6-6 6h-1c-2 0-3.3-.8-4.4-2.3L4 15.5a1.5 1.5 0 0 1 2.3-1.9L9 16"/></svg><span><b>Giữ và kéo ảnh</b><br>để chọn góc đẹp nhất</span></div></div>
      <label class="pc-zoom">Phóng to ảnh<input type="range" name="pzoom" min="1" max="2.5" step="0.01" value="1"></label>
    </div>
  </div>
  <div class="pc-dl"><h3><span>3</span>Tải thư về và gửi đồng đội</h3><button class="btn" type="button" id="pcDown">Tải thư tay</button></div>
</form>

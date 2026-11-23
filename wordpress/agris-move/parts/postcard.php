<?php
/** Postcard studio: photo strip on top, then wish fields beside the live preview. */
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
      <canvas id="pcCanvas" width="1600" height="1100"></canvas>
      <button class="btn" type="button" id="pcDown">Tải bưu thiếp</button>
    </div>
  </div>
</form>

<?php
/** Postcard studio: photos come from the hub's contest page. */
$photos = agm_postcard_photos();
?>
      <form class="tk-form st-form" data-postcard>
        <h2>1. Chọn ảnh</h2>
        <?php if ( $photos ) : ?>
        <div class="pc-pick">
          <?php foreach ( array_slice( $photos, 0, 36 ) as $i => $ph ) : ?>
          <label class="pc-opt"><input type="radio" name="photo" value="<?php echo esc_url( $ph['raw'] ); ?>" data-credit="<?php echo esc_attr( implode( ' · ', array_filter( array( $ph['title'], $ph['author'], $ph['place'] ) ) ) ); ?>" <?php checked( 0, $i ); ?>><img src="<?php echo esc_url( $ph['raw'] ); ?>" alt="" loading="lazy"><span><b><?php echo esc_html( $ph['title'] ); ?></b><?php echo esc_html( implode( ' · ', array_filter( array( $ph['author'], $ph['place'] ) ) ) ); ?></span></label>
          <?php endforeach; ?>
        </div>
        <?php else : ?><p class="tk-empty">Ảnh từ cuộc thi sẽ xuất hiện ở đây.</p><?php endif; ?>
        <h2>2. Viết lời chúc</h2>
        <label>Từ <small>(tên bạn)</small><input name="from" maxlength="40" autocomplete="name"></label>
        <label>Gửi đến<input name="to" maxlength="50" placeholder="Đội xe Nông trường 3"></label>
        <label>Lời chúc<textarea name="wish" maxlength="220" placeholder="Chúc cả đội một mùa vụ về đích an toàn…"></textarea></label>
        <button class="btn" type="button" id="pcDown">Tải bưu thiếp</button>
      </form>
      <div class="st-preview"><p class="st-cap">Xem trước</p><canvas id="pcCanvas" width="1600" height="1100"></canvas></div>

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
<main id="top" class="tk ct">
  <section class="band ct-top">
    <div class="in ct-hero">
      <div class="ct-intro">
        <p class="eyebrow-c">Cuộc thi ảnh · Vào vụ 2026–2027</p>
        <h1 class="gt"><?php the_title(); ?></h1>
        <p class="ct-lede">Bắt lại khoảnh khắc đội mình cùng nhau làm nên mùa vụ, gửi ảnh dự thi và rủ đồng đội vote nhé!</p>
        <ol class="ct-pills"><li><span>1</span>Tải ảnh lên</li><li><span>2</span>Điền thông tin</li><li><span>3</span>Gửi &amp; rủ vote</li></ol>
        <form class="ct-fields" id="ctForm" data-contest data-page="<?php echo esc_attr( $pid ); ?>">
          <input type="file" name="photo" accept="image/jpeg,image/png" required hidden>
          <div class="ct-row"><label>Tên ảnh<input name="title" maxlength="60" required placeholder="Bình minh trên ruộng mía"></label>
            <label>Tên bạn<input name="author" maxlength="60" required autocomplete="name"></label></div>
          <div class="ct-row"><label>Địa điểm chụp<input name="place" maxlength="60" required placeholder="Nông trường 3, Tây Ninh"></label>
            <label>Đơn vị<select name="center" required><option value="">Chọn đơn vị</option><?php foreach ( AGM_CENTERS as $c ) { echo '<option>' . esc_html( $c ) . '</option>'; } ?></select></label></div>
          <label>Mô tả ngắn <small>(không bắt buộc)</small><textarea name="desc" maxlength="300" rows="2" placeholder="Khoảnh khắc này có gì đặc biệt?"></textarea></label>
          <p class="tk-msg" role="status" hidden></p>
        </form>
      </div>
      <div class="ct-card">
        <canvas id="ctCanvas" width="1080" height="1350"></canvas>
        <button type="button" class="btn" data-pick><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 16V4M7 9l5-5 5 5M4 20h16"/></svg> Tải ảnh lên</button>
        <button type="submit" class="btn" form="ctForm" data-send>Gửi ảnh dự thi</button>
      </div>
      <img class="ct-abi" src="<?php echo esc_url( agm_img( 'abi-camera-1.webp' ) ); ?>" alt="" aria-hidden="true">
    </div>
  </section>
  <div class="seam" data-next="#f3faf5" data-h="90" data-tones="#bfe6ccd0,#2faa5ab0" data-line="lime"></div>
  <section class="band ct-gal" id="thu-vien">
    <div class="in">
      <div class="st-head"><div><h2 class="gt">Bình chọn ảnh</h2><p class="ct-count"><b><?php echo esc_html( count( $items ) ); ?></b> ảnh dự thi · bấm tim để vote</p></div>
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
  <div class="seam" data-next="#e9f6fb" data-h="90" data-tones="#d3eadb,#9bd8b1d0" data-line="lime"></div>
  <section class="band ct-rules" id="the-le">
    <div class="in ct-faq">
      <div><p class="eyebrow-c">Đọc kỹ trước khi tham gia</p><h2 class="gt">Thể lệ<br>cuộc thi</h2><img src="<?php echo esc_url( agm_img( 'abi-camera-2.webp' ) ); ?>" alt="" aria-hidden="true"></div>
      <div class="ct-acc">
        <details open><summary>Giới thiệu cuộc thi</summary><div><?php the_content(); ?></div></details>
        <details><summary>Đối tượng, thời gian và số ảnh</summary><div><ul><li><b>Đối tượng:</b> Toàn thể CBNV AgriS</li><li><b>Thời gian:</b> <?php echo esc_html( get_theme_mod( 'agm_ct_time', '[ngày bắt đầu] – [ngày kết thúc]' ) ); ?></li><li><b>Số ảnh:</b> <?php echo esc_html( get_theme_mod( 'agm_ct_limit', 'Tối đa [số] ảnh mỗi người' ) ); ?></li></ul></div></details>
        <details><summary>Chủ đề ảnh</summary><div><p>Chụp ảnh thật trong lúc làm việc, theo một trong các chủ đề:</p><ul><li><b>Thu hoạch:</b> ruộng mía, xe mía, đội nông vụ</li><li><b>Sản xuất:</b> ép, chế biến, vận hành</li><li><b>Thị trường:</b> đội ngũ kinh doanh, sản phẩm đến tay khách hàng, kho vận</li><li><b>Đồng đội:</b> những lúc cả đội cùng ra tay, hỗ trợ và tiếp sức cho nhau</li><li><b>Mùa vụ đẹp:</b> bình minh, hoàng hôn, những góc nhìn khiến bạn tự hào</li></ul></div></details>
        <details><summary>Cách tham gia</summary><div><ol><li>Tải ảnh lên và điền tên ảnh, tên bạn, địa điểm, đơn vị. Ảnh được gắn sẵn khung và hashtag <b><?php echo esc_html( AGM_HASHTAGS ); ?></b>.</li><li>Bấm Gửi ảnh dự thi. Ảnh lên thư viện ngay.</li><li>Sao chép đoạn giới thiệu, đăng lên Viva Engage và rủ đồng đội vào trang này thả love.</li></ol></div></details>
        <details><summary>Tiêu chí chấm giải</summary><div><ul><li><b>Nội dung và thông điệp:</b> đúng chủ đề, thể hiện tinh thần Move Action</li><li><b>Chất lượng ảnh:</b> bố cục, ánh sáng, cảm xúc</li><li><b>Mức độ lan toả:</b> số love trên website</li></ul></div></details>
        <details><summary>Cơ cấu giải thưởng</summary><div class="ct-prizes">
          <div class="ct-pz"><h3>Giải cá nhân</h3><table><tr><th>Giải nhất</th><td>01</td><td>3.000.000đ</td></tr><tr><th>Giải nhì</th><td>01</td><td>2.000.000đ</td></tr><tr><th>Giải ba</th><td>03</td><td>1.000.000đ</td></tr><tr><th>Giải khuyến khích</th><td>30</td><td>500.000đ</td></tr></table></div>
          <div class="ct-pz team"><h3>Giải tập thể</h3><p><?php echo esc_html( get_theme_mod( 'agm_ct_team', 'Dành cho [đơn vị] có nhiều ảnh dự thi hợp lệ và tổng love cao nhất.' ) ); ?></p><table><tr><th>Giải nhất</th><td>01</td><td>10.000.000đ</td></tr><tr><th>Giải nhì</th><td>01</td><td>5.000.000đ</td></tr><tr><th>Giải ba</th><td>01</td><td>3.000.000đ</td></tr></table></div>
        </div></details>
      </div>
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

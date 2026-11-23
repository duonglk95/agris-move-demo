<?php
/**
 * Template Name: Mini-site sự kiện
 * One long page: Pit Crew banner, "Tiếp sức đội mình" wall (+ popup form), postcard, avatar, slideshow of
 * contest photos. Child pages (Đăng ký Pit Crew, Khoảnh khắc đội mình) appear in the mini-site menu.
 */
get_header();
while ( have_posts() ) : the_post();
	$hub   = get_the_ID();
	$kids  = array();
	foreach ( agm_hub_children( $hub ) as $c ) { $kids[ get_page_template_slug( $c ) ] = $c; }
	$notes = agm_thanks_list( $hub, 30 );
	$shots = isset( $kids['page-contest.php'] ) ? agm_entries( $kids['page-contest.php']->ID, 'love' ) : array();
	?>
<main id="top" class="tk">
  <?php get_template_part( 'parts/mini-hero' ); ?>

  <?php if ( isset( $kids['page-pitcrew.php'] ) && function_exists( 'agm_pit_open' ) ) :
    $pit = $kids['page-pitcrew.php'];
    $days = max( 0, (int) ceil( ( strtotime( agm_pit_deadline() . ' 23:59:59' ) - current_time( 'timestamp' ) ) / DAY_IN_SECONDS ) );
    $t = array_values( agm_pit_tours() ); ?>
  <section class="band hub-sec"><div class="in"><a class="pit-banner" href="<?php echo esc_url( get_permalink( $pit ) ); ?>">
    <div><span class="ev-pill live"><?php echo agm_pit_open() ? 'Đang tuyển · hạn ' . esc_html( wp_date( 'd/m', strtotime( agm_pit_deadline() ) ) ) : 'Đã đóng đăng ký'; ?></span>
      <h2>Gia nhập Pit Crew</h2>
      <p><?php echo esc_html( $pit->post_excerpt ? $pit->post_excerpt : 'Cùng đoàn HO ra tận trạm PIT, tiếp sức cho đội vào vụ.' ); ?><?php echo $t ? ' ' . esc_html( $t[0][1] . ' – ' . end( $t )[2] ) . '.' : ''; ?></p>
      <span class="btn"><?php echo agm_pit_open() ? 'Đăng ký Pit Crew' : 'Tra cứu kết quả'; ?> <?php echo agm_arrow( 'chev' ); // phpcs:ignore ?></span></div>
    <div class="pit-stats"><div><b><?php echo esc_html( agm_pit_count() ); ?></b><span>Pit Crew đã đăng ký</span></div>
      <div><b><?php echo agm_pit_open() ? esc_html( $days ) . ' ngày' : '—'; ?></b><span>đến hạn đăng ký</span></div></div>
  </a></div></section>
  <?php endif; ?>

  <section class="band hub-sec" id="tiep-suc">
    <div class="in">
      <div class="hub-head"><h2>Tiếp sức đội mình</h2><p>Những lời cảm ơn, động viên gửi tới đồng đội đang ở tuyến đầu mùa vụ.</p></div>
      <?php if ( $notes ) : ?>
      <div class="tk-wall wide">
        <?php foreach ( $notes as $n ) : $to = get_post_meta( $n->ID, '_agm_to', true ); $from = get_post_meta( $n->ID, '_agm_from', true ); ?>
        <figure class="tk-note-card"><blockquote><?php echo esc_html( $n->post_content ); ?></blockquote>
          <figcaption>Gửi <?php echo esc_html( $to ? $to : 'đồng đội' ); ?> · <?php echo esc_html( $from ? $from : 'Ẩn danh' ); ?></figcaption></figure>
        <?php endforeach; ?>
      </div>
      <?php else : ?><p class="tk-empty">Tường đang chờ lời tiếp sức đầu tiên. Bạn mở màn nhé!</p><?php endif; ?>
      <div class="hub-cta"><button type="button" class="btn" data-tk-open>Gửi lời cảm ơn <?php echo agm_arrow( 'chev' ); // phpcs:ignore ?></button>
        <span><?php echo esc_html( number_format_i18n( agm_thanks_count( $hub ) ) ); ?> lời tiếp sức đã lên tường</span></div>
    </div>
  </section>

  <section class="band hub-sec alt" id="buu-thiep">
    <div class="in">
      <div class="hub-head"><h2>Gửi bưu thiếp mùa vụ</h2><p>Chọn một khoảnh khắc đẹp của đội mình, viết lời chúc và tải bưu thiếp về gửi đồng đội.</p></div>
      <div class="st-grid wide"><?php get_template_part( 'parts/postcard' ); ?></div>
    </div>
  </section>

  <section class="band hub-sec" id="avatar">
    <div class="in">
      <div class="hub-head"><h2>Khoác áo Pit Crew</h2><p>Đổi avatar với khung Pitstop để cả đội cùng một màu áo.</p></div>
      <div class="st-grid"><?php get_template_part( 'parts/avatar' ); ?></div>
    </div>
  </section>

  <?php if ( isset( $kids['page-contest.php'] ) ) : $ct = $kids['page-contest.php']; ?>
  <section class="band hub-sec alt" id="khoanh-khac">
    <div class="in">
      <div class="hub-head row"><div><h2><?php echo esc_html( get_the_title( $ct ) ); ?></h2><p>Ảnh mùa vụ do chính AgriS-er chụp. Gửi ảnh của bạn và rủ đồng đội thả love.</p></div>
        <a class="btn" href="<?php echo esc_url( get_permalink( $ct ) ); ?>">Tham gia cuộc thi <?php echo agm_arrow( 'chev' ); // phpcs:ignore ?></a></div>
      <?php if ( $shots ) : ?>
      <div class="hub-slides" data-slides>
        <div class="hub-track">
          <?php foreach ( array_slice( $shots, 0, 12 ) as $p ) : $d = agm_entry_data( $p ); ?>
          <a class="hub-slide" href="<?php echo esc_url( get_permalink( $ct ) . '#anh-' . $p->ID ); ?>"><img src="<?php echo esc_url( get_the_post_thumbnail_url( $p, 'medium_large' ) ); ?>" alt="<?php echo esc_attr( $d['title'] ); ?>" loading="lazy"><span><b><?php echo esc_html( $d['title'] ); ?></b><?php echo esc_html( $d['author'] . ' · ' . $d['center'] ); ?></span></a>
          <?php endforeach; ?>
        </div>
        <button type="button" class="hub-arrow prev" aria-label="Ảnh trước">‹</button><button type="button" class="hub-arrow next" aria-label="Ảnh sau">›</button>
      </div>
      <?php else : ?><p class="tk-empty">Chưa có ảnh nào. <a href="<?php echo esc_url( get_permalink( $ct ) ); ?>">Gửi ảnh đầu tiên</a></p><?php endif; ?>
    </div>
  </section>
  <?php endif; ?>

  <dialog class="cf-dlg" id="tkDlg" aria-labelledby="tkT">
    <form class="tk-form" data-tk data-page="<?php echo esc_attr( $hub ); ?>">
      <h2 id="tkT">Gửi lời cảm ơn</h2>
      <label>Gửi tới <small>người hoặc đội</small><input name="to" maxlength="120" placeholder="Đội thu hoạch Nông trường 3"></label>
      <label>Lời nhắn<textarea name="message" required maxlength="600" placeholder="Cảm ơn anh chị đã…"></textarea></label>
      <label>Tên bạn <small>(không bắt buộc)</small><input name="from" maxlength="80" autocomplete="name"></label>
      <p class="tk-msg" role="status" hidden></p>
      <p class="tk-note">Lời nhắn sẽ lên tường sau khi ban tổ chức duyệt.</p>
      <div class="cf-act"><button type="button" class="cf-x" data-tk-close>Đóng</button><button class="btn" type="submit">Gửi lời cảm ơn</button></div>
    </form>
  </dialog>
</main>
<?php endwhile; get_footer();

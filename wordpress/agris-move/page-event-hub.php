<?php
/**
 * Template Name: Mini-site sự kiện
 * One long page: "Tiếp sức đội mình" wall (+ popup form), postcard, slideshow of
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


  <?php if ( agm_show( 'wall' ) ) : ?>
  <section class="hub-sec pitwall" id="tiep-suc">
    <div class="in">
      <div class="hub-head center"><img class="abi-side l" src="<?php echo esc_url( agm_img( 'abi-letter.webp' ) ); ?>" alt="" aria-hidden="true"><p class="eyebrow-c">Bức tường cảm ơn</p><h2 class="gt">Tiếp sức đội mình</h2><p>Cùng gửi lời cảm ơn, lời chúc hoặc vài dòng động viên đến đồng đội trong mùa vụ này nhé!</p></div>
    </div>
    <?php if ( $notes ) : $pages = array_chunk( $notes, 8 ); ?>
    <div class="in"><div class="tw-board" data-tw>
      <span class="tw-spark s1" aria-hidden="true"></span><span class="tw-spark s2" aria-hidden="true"></span><span class="tw-spark s3" aria-hidden="true"></span>
      <?php foreach ( $pages as $pi => $pg ) : ?>
      <div class="tw-page"<?php echo $pi ? ' hidden' : ''; ?>>
        <?php foreach ( $pg as $n ) : $to = get_post_meta( $n->ID, '_agm_to', true ); $from = get_post_meta( $n->ID, '_agm_from', true ); ?>
        <figure class="tw-note">
          <blockquote tabindex="0">“<?php echo esc_html( $n->post_content ); ?>”</blockquote>
          <figcaption><b><?php echo esc_html( $from ? $from : 'Ẩn danh' ); ?></b><span>gửi <?php echo esc_html( $to ? $to : 'đồng đội' ); ?></span></figcaption>
        </figure>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>
      <?php if ( count( $pages ) > 1 ) : ?>
      <div class="tw-pager"><button type="button" class="tw-prev" aria-label="Trang trước"><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M10 3 5 8l5 5"/></svg></button><span class="tw-num"><b>1</b> / <?php echo count( $pages ); ?></span><button type="button" class="tw-next" aria-label="Trang sau"><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="m6 3 5 5-5 5"/></svg></button></div>
      <?php endif; ?>
    </div></div>
    <?php else : ?>
    <div class="in"><p class="tk-empty">Tường đang chờ lời tiếp sức đầu tiên. Bạn mở màn nhé!</p></div>
    <?php endif; ?>
    <div class="in hub-cta center"><button type="button" class="btn" data-tk-open>Gửi lời cảm ơn <?php echo agm_arrow( 'chev' ); // phpcs:ignore ?></button>
      <span><?php echo esc_html( number_format_i18n( agm_thanks_count( $hub ) ) ); ?> lời tiếp sức đã lên tường</span></div>
  </section>
  <?php endif; ?>

  <?php if ( agm_show( 'wall' ) ) : ?><div class="divider-img mill" aria-hidden="true"><img src="<?php echo esc_url( agm_img( 'divider-mill.webp' ) ); ?>" alt="" loading="lazy"></div><?php endif; ?>
  <section class="band hub-sec alt" id="buu-thiep">
    <div class="in">
      <div class="hub-head center"><img class="abi-side r" src="<?php echo esc_url( agm_img( 'abi-postcard.webp' ) ); ?>" alt="" aria-hidden="true"><h2 class="gt">Thư tay tiếp sức</h2><p>Chọn một khoảnh khắc đẹp của đội mình, viết lời chúc và tải bưu thiếp về gửi đồng đội.</p></div>
      <?php get_template_part( 'parts/postcard' ); ?>
    </div>
  </section>


  <?php if ( isset( $kids['page-contest.php'] ) ) : $ct = $kids['page-contest.php']; ?>
  <section class="band hub-sec alt" id="khoanh-khac">
    <div class="in">
      <?php
      $pics = array();
      foreach ( $shots as $p ) { $d = agm_entry_data( $p ); if ( $d['raw'] ) { $pics[] = array( $d['raw'], $d['title'], get_permalink( $ct ) . '#anh-' . $p->ID ); } }
      foreach ( get_posts( array( 'post_type' => 'anh_buu_thiep', 'post_status' => 'publish', 'numberposts' => 40, 'orderby' => 'menu_order', 'order' => 'ASC' ) ) as $p ) { $u = get_the_post_thumbnail_url( $p, 'medium_large' ); if ( $u ) { $pics[] = array( $u, get_the_title( $p ), '' ); } }
      if ( $pics ) :
        $rows = array( array(), array() );
        foreach ( $pics as $i => $x ) { $rows[ $i % 2 ][] = $x; }
        foreach ( $rows as $ri => $row ) : if ( ! $row ) { continue; } while ( count( $row ) < 6 ) { $row = array_merge( $row, $row ); } ?>
    </div>
    <div class="ph-row<?php echo $ri ? ' rev' : ''; ?>"><div class="ph-track">
      <?php foreach ( array_merge( $row, $row ) as $k => $x ) : $tag = $x[2] ? 'a' : 'span'; ?>
      <<?php echo $tag; // phpcs:ignore ?> class="ph-item"<?php echo $x[2] ? ' href="' . esc_url( $x[2] ) . '"' : ''; ?><?php echo $k >= count( $row ) ? ' aria-hidden="true" tabindex="-1"' : ''; ?>><img src="<?php echo esc_url( $x[0] ); ?>" alt="<?php echo esc_attr( $x[1] ); ?>" loading="lazy"></<?php echo $tag; // phpcs:ignore ?>>
      <?php endforeach; ?>
    </div></div>
    <div class="in">
      <?php endforeach; else : ?><p class="tk-empty">Chưa có ảnh nào. <a href="<?php echo esc_url( get_permalink( $ct ) ); ?>">Gửi ảnh đầu tiên</a></p><?php endif; ?>
    </div>
  </section>
  <?php endif; ?>

  <div class="divider-img campus" aria-hidden="true"><img src="<?php echo esc_url( agm_img( 'divider-campus.webp' ) ); ?>" alt="" loading="lazy"></div>

  <?php if ( agm_show( 'wall' ) ) : ?>
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
  <?php endif; ?>
</main>
<?php endwhile; get_footer();

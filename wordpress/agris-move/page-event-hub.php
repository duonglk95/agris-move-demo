<?php
/**
 * Template Name: Mini-site sự kiện
 * Hub of an event mini-site (e.g. Vào vụ). Child pages become the activities in the menu and the cards below.
 */
get_header();
while ( have_posts() ) : the_post();
	$icons = array(
		'page-thanks.php'   => 'M12 21s-7.5-4.6-9.6-9.3C.9 8.3 3 4.5 6.7 4.5c2.2 0 3.6 1.2 4.3 2.4h2c.7-1.2 2.1-2.4 4.3-2.4 3.7 0 5.8 3.8 4.3 7.2C19.5 16.4 12 21 12 21z',
		'page-postcard.php' => 'M3 6h18v12H3zM3 7l9 6 9-6',
		'page-avatar.php'   => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21a8 8 0 0 1 16 0',
		'page-contest.php'  => 'M4 7h3l2-3h6l2 3h3v13H4zM12 17a4 4 0 1 0 0-8 4 4 0 0 0 0 8z',
	);
	?>
<main id="top" class="tk">
  <?php get_template_part( 'parts/mini-hero' ); ?>
  <section class="band">
    <?php
    $pit = get_posts( array( 'post_type' => 'page', 'post_parent' => get_the_ID(), 'post_status' => 'publish', 'meta_key' => '_wp_page_template', 'meta_value' => 'page-pitcrew.php', 'numberposts' => 1 ) );
    if ( $pit && function_exists( 'agm_pit_open' ) ) :
      $days = max( 0, (int) ceil( ( strtotime( agm_pit_deadline() . ' 23:59:59' ) - current_time( 'timestamp' ) ) / DAY_IN_SECONDS ) );
      $t    = array_values( agm_pit_tours() );
      ?>
    <div class="in"><a class="pit-banner" href="<?php echo esc_url( get_permalink( $pit[0] ) ); ?>">
      <div><span class="ev-pill live"><?php echo agm_pit_open() ? 'Đang tuyển · hạn ' . esc_html( wp_date( 'd/m', strtotime( agm_pit_deadline() ) ) ) : 'Đã đóng đăng ký'; ?></span>
        <h2>Gia nhập Pit Crew</h2>
        <p><?php echo esc_html( $pit[0]->post_excerpt ? $pit[0]->post_excerpt : 'Cùng đoàn HO ra tận trạm PIT, tiếp sức cho đội vào vụ.' ); ?><?php echo $t ? ' ' . esc_html( $t[0][1] . ' – ' . end( $t )[2] ) . '.' : ''; ?></p>
        <span class="btn"><?php echo agm_pit_open() ? 'Đăng ký Pit Crew' : 'Tra cứu kết quả'; ?> <?php echo agm_arrow( 'chev' ); // phpcs:ignore ?></span></div>
      <div class="pit-stats"><div><b><?php echo esc_html( agm_pit_count() ); ?></b><span>Pit Crew đã đăng ký</span></div>
        <div><b><?php echo agm_pit_open() ? esc_html( $days ) . ' ngày' : '—'; ?></b><span>đến hạn đăng ký</span></div></div>
    </a></div>
    <?php endif; ?>
    <div class="in hub-grid">
      <?php foreach ( agm_hub_children( get_the_ID() ) as $c ) : $tpl = get_page_template_slug( $c ); if ( 'page-pitcrew.php' === $tpl ) { continue; } ?>
      <a class="hub-card" href="<?php echo esc_url( get_permalink( $c ) ); ?>">
        <span class="hub-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="<?php echo esc_attr( $icons[ $tpl ] ?? 'M5 12h14M13 6l6 6-6 6' ); ?>"></path></svg></span>
        <h2><?php echo esc_html( get_the_title( $c ) ); ?></h2>
        <p><?php echo esc_html( $c->post_excerpt ? $c->post_excerpt : wp_trim_words( wp_strip_all_tags( $c->post_content ), 18 ) ); ?></p>
        <span class="more">Tham gia <?php echo agm_arrow(); // phpcs:ignore ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<?php endwhile; get_footer();

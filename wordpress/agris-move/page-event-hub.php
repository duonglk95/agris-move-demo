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
    <div class="in hub-grid">
      <?php foreach ( agm_hub_children( get_the_ID() ) as $c ) : $tpl = get_page_template_slug( $c ); ?>
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

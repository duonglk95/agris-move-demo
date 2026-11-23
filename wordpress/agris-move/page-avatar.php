<?php
/**
 * Template Name: Khung avatar
 * Put your photo in the event frame and download it. Done in the browser; nothing is uploaded.
 */
get_header();
while ( have_posts() ) : the_post(); ?>
<main id="top" class="tk">
  <?php get_template_part( 'parts/mini-hero' ); ?>
  <section class="band">
    <div class="in st-grid">
      <?php get_template_part( 'parts/avatar' ); ?>
    </div>
  </section>
</main>
<?php endwhile; get_footer();

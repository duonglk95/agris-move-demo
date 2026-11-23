<?php
/**
 * Template Name: Bưu thiếp
 * Pick a contest photo (credited), write a wish, download the postcard. Nothing is stored.
 */
get_header();
while ( have_posts() ) : the_post();
	?>
<main id="top" class="tk">
  <?php get_template_part( 'parts/mini-hero' ); ?>
  <section class="band">
    <div class="in st-grid wide">
      <?php get_template_part( 'parts/postcard' ); ?>
    </div>
  </section>
</main>
<?php endwhile; get_footer();

<?php
/**
 * Template Name: Sự kiện
 * Level-2 page listing events that are live or coming up.
 */
get_header();
?>
<main id="top" class="page-main">
  <section class="band band-glow" id="su-kien">
    <div class="in stack">
      <div class="event-head"><h1 class="page-title"><?php the_title(); ?></h1></div>
      <?php get_template_part( 'parts/events' ); ?>
    </div>
  </section>
</main>
<?php
get_footer();

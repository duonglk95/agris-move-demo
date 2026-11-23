<?php
/**
 * Ordinary pages (thể lệ, giới thiệu, form ứng cử…).
 */
get_header();
while ( have_posts() ) :
	the_post();
	?>
<main id="top" class="page-main">
  <article class="band page-band">
    <div class="in entry-wrap">
      <header class="entry-head"><h1 class="page-title"><?php the_title(); ?></h1></header>
      <div class="entry"><?php the_content(); ?></div>
    </div>
  </article>
</main>
	<?php
endwhile;
get_footer();

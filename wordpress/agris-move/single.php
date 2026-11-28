<?php
/**
 * One article, podcast episode or MOVER profile.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) :
	the_post();
	$type = get_post_type();
	$back = array(
		'post'    => array( get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : agm_anchor( 'ban-tin' ), 'Bản tin MOVE' ),
		'podcast' => array( get_post_type_archive_link( 'podcast' ), 'AgriS-thing' ),
		'mover'   => array( agm_top50_url() . '#cac-mover', 'TOP 50 MOVERS' ),
	);
	$crumb = isset( $back[ $type ] ) ? $back[ $type ] : array( home_url( '/' ), 'Trang chủ' );
	?>
<main id="top" class="page-main">
  <article class="band page-band">
    <div class="in entry-wrap">
      <a class="crumb" href="<?php echo esc_url( $crumb[0] ); ?>">‹ <?php echo esc_html( $crumb[1] ); ?></a>
      <header class="entry-head">
        <?php if ( 'post' === $type ) : $cats = get_the_category(); ?>
          <p class="entry-meta"><?php if ( $cats ) : ?><span class="chip-inline"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?><time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></time></p>
        <?php endif; ?>
        <h1 class="page-title"><?php the_title(); ?></h1>
        <?php if ( has_excerpt() && 'mover' !== $type ) : ?><p class="lede"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
      </header>
      <?php if ( has_post_thumbnail() && 'podcast' !== $type && 'mover' !== $type ) : ?>
        <figure class="entry-cover"><?php the_post_thumbnail( 'large' ); ?></figure>
      <?php endif; ?>
      <div class="entry"><?php the_content(); ?></div>
    </div>
  </article>
</main>
	<?php
endwhile;
get_footer();

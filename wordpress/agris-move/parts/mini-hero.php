<?php
/** Banner for mini-site pages: the hub's featured image, this page's title and intro (page content). */
$hub = agm_hub_id();
$img = get_the_post_thumbnail_url( get_the_ID(), 'full' );
$img = $img ? $img : ( $hub ? get_the_post_thumbnail_url( $hub, 'full' ) : '' );
$is_hub = $hub === get_the_ID();
?>
<section class="tk-hero<?php echo $is_hub ? ' is-hub' : ''; ?>">
  <?php if ( $img ) : ?><img src="<?php echo esc_url( $img ); ?>" alt=""><?php endif; ?>
  <div class="in tk-hero-txt">
    <?php if ( $hub && ! $is_hub ) : ?><p class="tk-kicker"><?php echo esc_html( get_the_title( $hub ) ); ?></p><?php else : ?><p class="tk-kicker">Sự kiện nội bộ AgriS</p><?php endif; ?>
    <h1 class="gt"><?php the_title(); ?></h1>
    <div class="tk-intro"><?php the_content(); ?></div>
  </div>
</section>

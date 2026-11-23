<?php
/** Banner for mini-site pages: the hub's featured image, this page's title and intro (page content). */
$hub = agm_hub_id();
$img = get_the_post_thumbnail_url( get_the_ID(), 'full' );
$img = $img ? $img : ( $hub ? get_the_post_thumbnail_url( $hub, 'full' ) : '' );
$is_hub = $hub === get_the_ID();
?>
<section class="tk-hero<?php echo $is_hub ? ' is-hub' : ''; ?>">
  <?php if ( $img && 'page-pitcrew.php' !== get_page_template_slug() ) : ?><img src="<?php echo esc_url( $img ); ?>" alt=""><?php endif; ?>
  <div class="in tk-hero-txt">
    <?php if ( $hub && ! $is_hub && ! in_array( get_page_template_slug(), array( 'page-contest.php', 'page-pitcrew.php' ), true ) ) : ?><p class="tk-kicker"><?php echo esc_html( get_the_title( $hub ) ); ?></p><?php endif; ?>
    <h1 class="gt"><?php the_title(); ?></h1>
    <div class="tk-intro"><?php the_content(); ?></div>
    <?php if ( $is_hub || 'page-pitcrew.php' === get_page_template_slug() ) : ?><img class="abi-hero" src="<?php echo esc_url( agm_img( 'abi-flag.webp' ) ); ?>" alt="" aria-hidden="true"><?php endif; ?>
  </div>
  <?php if ( 'page-pitcrew.php' === get_page_template_slug() && function_exists( 'agm_pit_deadline' ) && agm_pit_open() ) : ?>
  <div class="in pit-cd" data-deadline="<?php echo esc_attr( agm_pit_deadline() ); ?>T23:59:59+07:00">
    <p>Hạn đăng ký còn</p>
    <div class="pit-cd-t"><div><b data-d>--</b><span>Ngày</span></div><div><b data-h>--</b><span>Giờ</span></div><div><b data-m>--</b><span>Phút</span></div><div><b data-s>--</b><span>Giây</span></div></div>
    <small>Đóng đăng ký lúc 23:59 ngày <?php echo esc_html( wp_date( 'd/m/Y', strtotime( agm_pit_deadline() ) ) ); ?></small>
  </div>
  <?php endif; ?>
  <?php if ( 'page-pitcrew.php' !== get_page_template_slug() ) : ?><div class="cane-edge" aria-hidden="true"><img src="<?php echo esc_url( agm_img( 'divider-harvest.webp' ) ); ?>" alt=""></div><?php endif; ?>
</section>

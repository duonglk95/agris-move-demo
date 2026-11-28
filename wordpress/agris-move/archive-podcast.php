<?php
/**
 * Podcast / Video: grid of company YouTube videos (AgriS-thing entries).
 * Each entry = one "Tập podcast" in admin with a YouTube link. Click plays in place.
 */
get_header();
?>
<main id="top" class="page-main vid-page">
  <section class="band page-band">
    <div class="in stack">
      <div class="stack" style="gap:12px">
        <h1 class="page-title"><?php echo esc_html( get_theme_mod( 'agm_pod_title', 'Podcast' ) ); ?></h1>
        <p class="lede"><?php echo esc_html( get_theme_mod( 'agm_pod_lede', 'Những câu chuyện, chia sẻ và khoảnh khắc của người AgriS, xem ngay tại đây.' ) ); ?></p>
      </div>
      <?php if ( have_posts() ) : ?>
      <div class="vid-grid">
        <?php while ( have_posts() ) : the_post();
          $url = get_post_meta( get_the_ID(), '_agm_video', true );
          $yt  = agm_yt_id( $url );
          $dur = get_post_meta( get_the_ID(), '_agm_duration', true );
          $img = agm_pod_cover( get_post(), agm_img( 'demo-pod-a.jpg' ) );
          ?>
        <article class="vid-card">
          <?php if ( $yt ) : ?>
          <button type="button" class="vid-thumb" data-yt="<?php echo esc_attr( $yt ); ?>" aria-label="Phát video: <?php echo esc_attr( get_the_title() ); ?>">
          <?php else : ?>
          <a class="vid-thumb" href="<?php echo esc_url( $url ? $url : get_permalink() ); ?>"<?php echo $url ? ' target="_blank" rel="noopener"' : ''; ?> aria-label="Xem video: <?php echo esc_attr( get_the_title() ); ?>">
          <?php endif; ?>
            <img src="<?php echo esc_url( $img ); ?>" alt="" loading="lazy" width="480" height="270">
            <span class="playb" aria-hidden="true"></span>
            <?php if ( $dur ) : ?><span class="time"><?php echo esc_html( $dur ); ?></span><?php endif; ?>
          <?php echo $yt ? '</button>' : '</a>'; ?>
          <h2 class="vid-title"><?php the_title(); ?></h2>
          <?php if ( has_excerpt() ) : ?><p class="vid-desc"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
        </article>
        <?php endwhile; ?>
      </div>
      <?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => 'Trước', 'next_text' => 'Sau' ) ); ?>
      <?php else : ?>
      <p class="tk-empty">Video đang được cập nhật. Bạn quay lại sau nhé!</p>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php
get_footer();

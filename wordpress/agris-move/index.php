<?php
/**
 * Lists: Bản tin MOVE (blog), AgriS-thing archive, categories, search results.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

if ( is_search() ) {
	$heading = 'Kết quả tìm kiếm';
	$lede    = get_search_query() ? 'Cho từ khoá “' . get_search_query() . '”' : '';
} elseif ( is_post_type_archive( 'podcast' ) ) {
	$heading = 'AgriS-thing';
	$lede    = 'Tất cả các tập podcast AgriS-thing.';
} elseif ( is_post_type_archive( 'mover' ) ) {
	$heading = 'Các MOVER';
	$lede    = '';
} elseif ( is_archive() ) {
	$heading = wp_strip_all_tags( get_the_archive_title() );
	$lede    = wp_strip_all_tags( get_the_archive_description() );
} else {
	$heading = 'Bản tin MOVE';
	$lede    = 'Những chuyển động của toàn hệ thống AgriS.';
}
?>
<main id="top" class="page-main">
  <section class="band page-band">
    <div class="in stack">
      <div class="stack" style="gap:12px">
        <h1 class="page-title"><?php echo esc_html( $heading ); ?></h1>
        <?php if ( $lede ) : ?><p class="lede"><?php echo esc_html( $lede ); ?></p><?php endif; ?>
      </div>
      <?php if ( is_search() ) : ?>
        <form class="agm-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
          <label class="screen-reader-text" for="agm-s">Tìm kiếm</label>
          <input id="agm-s" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Tìm bài viết, podcast, MOVER…">
          <button class="btn" type="submit">Tìm</button>
        </form>
      <?php endif; ?>

      <?php if ( have_posts() ) : ?>
        <div class="news">
          <?php
          while ( have_posts() ) :
              the_post();
              $cats = get_the_category();
              ?>
              <article>
                <a class="thumb card" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                  <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'agm-card', array( 'alt' => '' ) ); else : ?><img alt="" src="<?php echo esc_url( agm_img( 'demo-news-1.jpg' ) ); ?>"><?php endif; ?>
                  <?php if ( $cats ) : ?><span class="chip"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
                </a>
                <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></time>
                <h3><?php the_title(); ?></h3>
                <a class="more" href="<?php the_permalink(); ?>">Xem thêm <?php echo agm_arrow(); // phpcs:ignore ?></a>
              </article>
          <?php endwhile; ?>
        </div>
        <nav class="agm-pages" aria-label="Phân trang"><?php echo wp_kses_post( paginate_links( array( 'prev_text' => '‹ Trước', 'next_text' => 'Sau ›' ) ) ); ?></nav>
      <?php else : ?>
        <p class="lede">Chưa có nội dung nào ở đây.</p>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php
get_footer();

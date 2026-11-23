<?php
/**
 * Template Name: Bưu thiếp
 * Pick a contest photo (credited), write a wish, download the postcard. Nothing is stored.
 */
get_header();
while ( have_posts() ) : the_post();
	$contest = get_posts( array( 'post_type' => 'page', 'post_parent' => agm_hub_id(), 'meta_key' => '_wp_page_template', 'meta_value' => 'page-contest.php', 'numberposts' => 1, 'fields' => 'ids' ) );
	$photos  = $contest ? array_map( 'agm_entry_data', agm_entries( $contest[0], 'love' ) ) : array();
	?>
<main id="top" class="tk">
  <?php get_template_part( 'parts/mini-hero' ); ?>
  <section class="band">
    <div class="in st-grid wide">
      <form class="tk-form st-form" data-postcard>
        <h2>1. Chọn ảnh</h2>
        <?php if ( $photos ) : ?>
        <div class="pc-pick">
          <?php foreach ( array_slice( $photos, 0, 24 ) as $i => $ph ) : ?>
          <label class="pc-opt"><input type="radio" name="photo" value="<?php echo esc_url( $ph['raw'] ); ?>" data-credit="<?php echo esc_attr( $ph['title'] . ' · ' . $ph['author'] . ' · ' . $ph['place'] ); ?>" <?php checked( 0, $i ); ?>><img src="<?php echo esc_url( $ph['raw'] ); ?>" alt="" loading="lazy"><span><b><?php echo esc_html( $ph['title'] ); ?></b><?php echo esc_html( $ph['author'] . ' · ' . $ph['place'] ); ?></span></label>
          <?php endforeach; ?>
        </div>
        <?php else : ?><p class="tk-empty">Ảnh từ cuộc thi sẽ xuất hiện ở đây.</p><?php endif; ?>
        <h2>2. Viết lời chúc</h2>
        <label>Gửi tới<input name="to" maxlength="50" placeholder="Đội xe Nông trường 3"></label>
        <label>Lời chúc<textarea name="wish" maxlength="220" placeholder="Chúc cả đội một mùa vụ về đích an toàn…"></textarea></label>
        <label>Từ<input name="from" maxlength="40" autocomplete="name"></label>
        <button class="btn" type="button" id="pcDown">Tải bưu thiếp</button>
      </form>
      <div class="st-preview"><p class="st-cap">Xem trước</p><canvas id="pcCanvas" width="1600" height="1100"></canvas></div>
    </div>
  </section>
</main>
<?php endwhile; get_footer();

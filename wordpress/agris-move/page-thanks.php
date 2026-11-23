<?php
/**
 * Template Name: Lời cảm ơn (bức tường)
 * Sub-page for an event such as Vào vụ: banner (featured image), send-a-thank-you form, wall of approved notes.
 * The page's own content shows under the title as the introduction.
 */
get_header();
while ( have_posts() ) :
	the_post();
	$pid   = get_the_ID();
	$notes = agm_thanks_list( $pid );
	?>
<main id="top" class="tk">
  <?php get_template_part( 'parts/mini-hero' ); ?>
  <section class="band">
    <div class="in tk-grid">
      <form class="tk-form" data-tk data-page="<?php echo esc_attr( $pid ); ?>">
        <h2>Gửi lời tiếp sức</h2>
        <label>Gửi tới <small>người hoặc đội</small><input name="to" maxlength="120" placeholder="Đội thu hoạch Nông trường 3"></label>
        <label>Lời nhắn<textarea name="message" required maxlength="600" placeholder="Cảm ơn anh chị đã…"></textarea></label>
        <label>Tên bạn <small>(không bắt buộc)</small><input name="from" maxlength="80" autocomplete="name"></label>
        <p class="tk-msg" role="status" hidden></p>
        <button class="btn" type="submit">Gửi lời tiếp sức</button>
        <p class="tk-note">Lời nhắn sẽ lên tường sau khi ban tổ chức duyệt.</p>
      </form>
      <div>
        <h2 class="tk-wall-h">Bức tường tiếp sức <span><?php echo esc_html( number_format_i18n( agm_thanks_count( $pid ) ) ); ?></span></h2>
        <?php if ( $notes ) : ?>
        <div class="tk-wall">
          <?php foreach ( $notes as $n ) : $to = get_post_meta( $n->ID, '_agm_to', true ); $from = get_post_meta( $n->ID, '_agm_from', true ); ?>
          <figure class="tk-note-card"><blockquote><?php echo esc_html( $n->post_content ); ?></blockquote>
            <figcaption>Gửi <?php echo esc_html( $to ? $to : 'đồng đội' ); ?> · <?php echo esc_html( $from ? $from : 'Ẩn danh' ); ?></figcaption></figure>
          <?php endforeach; ?>
        </div>
        <?php else : ?>
        <p class="tk-empty">Tường đang chờ lời tiếp sức đầu tiên. Bạn mở màn nhé!</p>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>
	<?php
endwhile;
get_footer();

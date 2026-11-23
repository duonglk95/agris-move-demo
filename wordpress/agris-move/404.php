<?php
get_header();
?>
<main id="top" class="page-main">
  <section class="band page-band">
    <div class="in entry-wrap">
      <h1 class="page-title">Không tìm thấy trang</h1>
      <p class="lede">Trang bạn tìm có thể đã được chuyển hoặc không còn nữa.</p>
      <p style="margin-top:24px"><a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">Về trang chủ <?php echo agm_arrow( 'chev' ); // phpcs:ignore ?></a></p>
    </div>
  </section>
</main>
<?php
get_footer();

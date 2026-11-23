<div class="seam" data-next="#dcefe3" data-h="100" data-tones="#eaf5ee,#cfe7d8"></div>
<footer class="foot">
  <div class="ribbon" data-ribbon></div>
  <div class="in">
    <a class="brand" href="<?php echo esc_url( is_front_page() ? '#top' : home_url( '/' ) ); ?>" aria-label="AgriS MOVE – Trang chủ"><img alt="AgriS MOVE" src="<?php echo esc_url( agm_img( 'logo-move.png' ) ); ?>"></a>
    <nav class="nav" aria-label="Điều hướng chân trang"><?php agm_menu( has_nav_menu( 'footer' ) ? 'footer' : 'primary' ); ?></nav>
    <?php $contact = get_theme_mod( 'agm_contact_link' ); ?>
    <a class="btn" href="<?php echo esc_url( $contact ? $contact : agm_anchor( 'top' ) ); ?>">Liên hệ chương trình <?php echo agm_arrow( 'chev' ); // phpcs:ignore ?></a>
    <small>© <?php echo esc_html( wp_date( 'Y' ) ); ?> AgriS</small>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>

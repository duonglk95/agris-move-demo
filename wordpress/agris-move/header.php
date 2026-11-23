<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="top">
  <div class="in">
    <a class="brand" href="<?php echo esc_url( is_front_page() ? '#top' : home_url( '/' ) ); ?>" aria-label="AgriS MOVE – Trang chủ"><img alt="AgriS MOVE – Create Forward" src="<?php echo esc_url( agm_img( 'logo-move.png' ) ); ?>"></a>
    <nav class="nav<?php echo agm_hub_id() ? " is-hub" : ""; ?>" aria-label="Điều hướng chính"><?php if ( is_front_page() ) { agm_menu( 'primary' ); } elseif ( agm_hub_id() ) { agm_hub_menu( agm_hub_id() ); } else { agm_sub_menu(); } ?></nav>
    <a class="search" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="Tìm kiếm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="11" cy="11" r="7"></circle><path d="M20 20l-4-4"></path></svg></a>
  </div>
</header>

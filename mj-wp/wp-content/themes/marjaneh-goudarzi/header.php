<!doctype html>
<html "<?php language_attributes(); ?>">
  <head>
    <meta charset="<?php bloginfo('chartset'); ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php bloginfo('name'); ?> | <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
    <link href="https://fonts.googleapis.com/css?family=Cormorant+Garamond:400,600|Roboto:300,300i,400" rel="stylesheet">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/foundation.css">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/font-awesome.css">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/hover.css">

    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/slick.css">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/slick-theme.css">

    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <?php wp_head(); ?>
  </head>
  <body>
    <header>
      <nav>
        <div class="row">
          <div class="large-4  columns header-title">
            <a href="<?php bloginfo('url') ?>"><h1>MARJANEH</h1></a>
            <a class="mobile-nav-icon js--nav-icon"><i class="fa fa-bars"></i></a>
          </div>
          <div class="large-8 large-text-right columns header-menu">

            <?php
            wp_nav_menu(array(
              'theme_location'  => 'primary',
              'menu_class'      => 'nav-top js--main-nav'
            ));
            ?>
          </div>
        </div>
      </nav>
    </header>
    <div class="">
      <?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

          $count = WC()->cart->cart_contents_count;
          ?><a class="cart-contents absolute-cart-box" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php
          if ( $count > 0 ) {
              ?>
              <span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
              <?php
          }

          $subtotal = WC()->cart->subtotal;
          ?><?php
          if ( $subtotal > 0 ) {
              ?>
              <span class="cart-contents-subtotal"><?php echo esc_html( $subtotal ); ?></span>
              <?php
          }
              ?></a>

      <?php } ?>
    </div>

<?php
  //Theme Support
  function theme_support() {
    //add featured image Support
    add_theme_support('post-thumbnails');
    // add_image_size('home-small', 455, 349);
    //nav menus
    register_nav_menus(array(
      'primary' => __('Primary Menu')
    ));
    add_theme_support( 'woocommerce' );
  }
  function wpdocs_excerpt_more( $more ) {
    return ' [...] ';
  }
  function wpdocs_custom_excerpt_length( $length ) {
    return 10;
  }

  add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );
  add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );
  add_action( 'after_setup_theme', 'theme_support');

  add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
  //add customizer functionality



  /**
 * Add Cart icon and count to header if WC is active
 */
 /**
  * Ensure cart contents update when products are added to the cart via AJAX
  */
 function my_header_add_to_cart_fragment( $fragments ) {

     ob_start();
     $count = WC()->cart->cart_contents_count;
     ?><a class="cart-contents absolute-cart-box fadein" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php
     if ( $count > 0 ) {
         ?>
         <span class="cart-contents-count"><?php echo esc_html( $count ); ?></span>
         <?php
     }
     $subtotal = WC()->cart->subtotal;
     if ( $subtotal > 0 ) {
         ?>
         <span class="cart-contents-subtotal"><?php echo esc_html( $subtotal ); ?></span>
         <?php
     }
         ?></a><?php

     $fragments['a.absolute-cart-box'] = ob_get_clean();

     return $fragments;
 }
 add_filter( 'woocommerce_add_to_cart_fragments', 'my_header_add_to_cart_fragment' );
 //add_filter( 'woocommerce_add_to_cart_fragments', 'my_header_add_to_cart_fragment_total' );


  /**
 * Register our sidebars and widgetized areas.
 *
 */
  function calendar_widgets_init() {

  	register_sidebar( array(
  		'name'          => 'Booking calendar',
  		'id'            => 'booking_calendar',
  		'before_widget' => '<div>',
  		'after_widget'  => '</div>',
  	) );

  }
  add_action( 'widgets_init', 'calendar_widgets_init' );

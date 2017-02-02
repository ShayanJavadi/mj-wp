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

  //add customizer functionality

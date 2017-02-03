<?php
/*
Plugin Name: 10to8 Online Booking Widget
Plugin URI: http://10to8.com?utm_source=wp_plugin&utm_medium=viral
Description: Embed a FREE <a href="https://10to8.com?utm_source=wp_plugin&utm_medium=viral">10to8 Online Booking Widget</a> into your website. Sign up for an account at 10to8.com and then visit Setup -> Buttons & Widget to access the code to set up the widget.
Version: 1.0.0
Author: 10to8
Author URI: http://10to8.com?utm_source=wp_plugin&utm_medium=viral
*/

function enqueue_init_script() {
  wp_register_script( '10to8-js',
    'https://d3saea0ftg7bjt.cloudfront.net/embed/js/embed.min.js',
    array(), false, true
  );
  wp_register_script( '10to8-js-init',
    plugins_url( '/init.js' , __FILE__ ),
    array(), false, true
  );
}

add_action( 'wp_enqueue_scripts', 'enqueue_init_script' );

function embed_10to8( $atts ) {
	extract( shortcode_atts( array(
		'organisation_id' => '',
	), $atts ) );

  wp_enqueue_script( '10to8-js' );
  wp_localize_script('10to8-js-init',
    'wordpressZembedConfig',
    array('organisationId' => $organisation_id)
  );
  wp_enqueue_script( '10to8-js-init' );

	return "<div id=\"TTE-$organisation_id\"></div>"
       . "<div style=\"font-size: 8px; text-align: right\">Powered by <a href=\"https://10to8.com/?utm_source=wp_plugin&utm_medium=viral\">10to8 Online Booking</a></div>";
}
add_shortcode( '10to8booking', 'embed_10to8' );

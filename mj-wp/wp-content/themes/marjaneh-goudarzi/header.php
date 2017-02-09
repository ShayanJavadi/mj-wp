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

      <div class="row">
        <div class="large-4  columns header-title">
          <a href="<?php bloginfo('url') ?>"><h1>MARJANEH</h1></a>
        </div>
        <div class="large-8 large-text-right columns header-menu">
          <?php
          wp_nav_menu(array(
            'theme_location'  => 'primary',
            'menu_class'      => 'nav-top'
          ));
          ?>
        </div>
      </div>
    </header>

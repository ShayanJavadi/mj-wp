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
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/animate.css">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/slick.css">
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/slick-theme.css">

    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <?php wp_head(); ?>
  </head>
  <body>
    <header>
      <div class="row">
        <div class="text-center">
          <!-- make this dynamic bloginfo('name'); -->
          <a href="<?php bloginfo('url') ?>"><h1>Marjaneh Goudarzi</h1></a>
        </div>
      </div>
      <div class="row">
        <div class="large-12 large-centered columns text-center">
          <?php
          wp_nav_menu(array(
            'theme_location'  => 'primary',
            'menu_class'      => 'nav-top'
          ));
          ?>
          <!-- <ul class="nav-top">
            <li class="fadein"><a href="index.html" class="current-page">Home</a></li>
            <li class="fadein"><a href="about.html">About</a></li>
            <li class="fadein"><a href="gallery.html">Gallery</a></li>
            <li class="fadein2-6"><a href="lessons.html">Lessons</a></li>
            <li class="fadein2-6"><a href="blog.html">Parties</a></li>
            <li class="fadein3"><a href="blog.html">Shop</a></li>
            <li class="fadein3"><a href="blog.html">Blog</a></li>
          </ul> -->
        </div>
      </div>
    </header>

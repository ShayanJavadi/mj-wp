<?php get_header(); ?>
  <?php $featured_query = new WP_Query(array(
    'category_name' => 'featured' )); ?>
    <div class="main">
      <section class="image-carousel">
        <div class="row">
          <div class="text-center fadein3">
            <img src="<?php bloginfo('template_url'); ?>/img/carimage-1.jpg" alt="">
          </div>
        </div>
      </section>
      <section>
        <div class="row">
          <div class="large-12 large-centered">
            <h3 class="text-center">&mdash; Blog &mdash;</3>
          </div>
        </div>
        <?php $i = 0; ?>
        <?php while($featured_query->have_posts() && $i < 4) :
          $featured_query->the_post(); ?>
          <?php $i++; ?>
          <?php
            //perform check so we can make a grid of new blog posts
            if($i % 2 != 0) :
           ?>
            <div class="row front-page-row">
              <a href="<?php the_permalink(); ?>" class="front-page-title">
                <div class="large-6 columns front-page-box">
                  <span class="text-left front-page-small-img"><?php the_post_thumbnail('home-small'); ?></span>
                  <h4 class="text-left front-page-title"><?php the_title(); ?></h4>
                  <p><?php the_excerpt(); ?></p>
                  <p class="front-page-date"><?php the_time('F j, Y'); ?></p>
                </div>
              </a>
        	<?php else : ?>
            <a href="<?php the_permalink(); ?>" class="front-page-title">
              <div class="large-6 columns front-page-box">
                <span class="text-left front-page-small-img"><?php the_post_thumbnail('home-small'); ?></span>
                <h4 class="text-left front-page-title"><?php the_title(); ?></h4>
                <p><?php the_excerpt(); ?></p>
                <p class="front-page-date"><?php the_time('F j, Y'); ?></p>
              </div>
              </a>
            </div>
        	<?php endif; ?>
      <?php endwhile; ?>
      </section>
      <section>
        <div class="row">
          <div class="large-6 columns">
            <h2 class="text-center">Another title </h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          </div>
          <div class="large-6 columns">
            <span class="text-center">
              <img src="<?php bloginfo('template_url'); ?>/img/small-img-1.jpg" alt="">
              <img  src="<?php bloginfo('template_url'); ?>/img/post2.jpg" alt="">
            </span>
          </div>
        </div>
      </section>
      <section>
        <div class="row">
          <div class="large-6 columns">
            <span class="text-center">
              <img src="<?php bloginfo('template_url'); ?>/img/small-img-2.jpg" alt="">
            </span>
          </div>
          <div class="large-6 columns">
            <h2 class="text-center">Some Title </h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          </div>
        </div>
      </section>
    </div>
    <?php get_footer(); ?>

<?php //<div class="text-center fadein3 carousel">
  //<img src="<?php bloginfo('template_url'); /img/carimage-1.jpg" alt="">
//</div> ?>

<?php get_header(); ?>

    <div class="main front-page-main">
      <section class="section-slide-show">
          <div class="slide-show large-12 large-centered  fadein">
            <div class="slide-1  fadein">
              <img src="<?php bloginfo('template_url'); ?>/img/lessons1.jpg" alt="">
                <a href="#">
                  <div class="slide-caption">
                    <h3 class="slide-caption-text">View my work</h3>
                    <a href="#" class="slide-caption-link">Visit Gallery</a>
                  </div>
                </a>
            </div>
            <div class="slide-2 ">
              <img src="<?php bloginfo('template_url'); ?>/img/lessons2.jpg" alt="">
              <a href="#">
                <div class="slide-caption">
                  <h3 class="slide-caption-text">Sign up for lessons</h3>
                  <a href="#" class="slide-caption-link">Book Now</a>
                </div>
              </a>
            </div>
            <div class="slide-3 ">
              <img src="<?php bloginfo('template_url'); ?>/img/lessons3.jpg" alt="">
              <a href="#">
                <div class="slide-caption">
                  <h3 class="slide-caption-text">Check out my blog</h3>
                  <a href="#" class="slide-caption-link">Visit Blog</a>
                </div>
              </a>
            </div>
        </div>
      </section>
      <section>
        <div class="row">
          <div class="large-4 columns front-page-box front-page-box-classes">
            <h3 class="text-center"> <i class="fa fa-paint-brush fa-big-icon text-center" aria-hidden="true"></i></h3>
            <h3 class="text-center">Kids Classes</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,.</p>
            <p class="text-center"><a href="#"><button class="button secondary-button">Book Classes</button></a></p>

          </div>
          <div class="large-4 columns front-page-box front-page-box-classes">
            <h3 class="text-center"><i class="fa fa-glass fa-big-icon text-center" aria-hidden="true"></i></h3>
            <h3 class="text-center">Fun Sip &amp; Painting</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, qui</p>
            <p class="text-center"><a href="#"><button class="button secondary-button">Learn More</button></a></p>

          </div>
          <div class="large-4 columns front-page-box front-page-box-classes">
            <h3 class="text-center"><i class="fa fa-birthday-cake fa-big-icon text-center" aria-hidden="true"></i></h3>
            <h3 class="text-center">Birthday Parties</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, </p>
            <p class="text-center"><a href="#"><button class="button secondary-button">Learn More </button></a></p>

          </div>
        </div>
      </section>
      <section>
        <?php $featured_query = new WP_Query(array(
          'category_name' => 'gallery' )); ?>
        <div class="row">
          <div class="large-12 large-centered">
            <h2 class="text-center"> Recent Works <h2>
          </div>
        </div>
        <?php $i = 0; ?>
        <?php while($featured_query->have_posts() && $i < 2) :
          $featured_query->the_post(); ?>
          <?php $i++; ?>
          <?php
            //perform check so we can make a grid of new blog posts
            if($i % 2 != 0) :
           ?>
            <div class="row front-page-row">
              <a href="<?php the_permalink(); ?>" class="front-page-title">
                  <div class="large-6 columns text-center front-page-box-gallery"><?php the_post_thumbnail('home-small'); ?></div>
              </a>
        	<?php else : ?>
            <a href="<?php the_permalink(); ?>" class="front-page-title">
                <div class="large-6 columns text-center front-page-box-gallery"><?php the_post_thumbnail('home-small'); ?></div>
            </a>
            </div>
        	<?php endif; ?>
        <?php endwhile;   wp_reset_postdata(); ?>
      </section>
      <section>
        <?php $featured_query = new WP_Query(array(
          'category_name' => 'featured' )); ?>
        <div class="row">
          <div class="large-12 large-centered">
            <h2 class="text-center"> Recent Blog Posts <h2>
          </div>
        </div>
        <?php $i = 0; ?>
        <?php while($featured_query->have_posts() && $i < 2) :
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
                  <h4 class="text-center front-page-title"><?php the_title(); ?></h4>
                  <p><?php the_excerpt(); ?></p>
                  <p class="front-page-date"><?php the_time('F j, Y'); ?></p>
                </div>
              </a>
        	<?php else : ?>
            <a href="<?php the_permalink(); ?>" class="front-page-title">
              <div class="large-6 columns front-page-box">
                <span class="text-left front-page-small-img"><?php the_post_thumbnail('home-small'); ?></span>
                <h4 class="text-center front-page-title"><?php the_title(); ?></h4>
                <p><?php the_excerpt(); ?></p>
                <p class="front-page-date"><?php the_time('F j, Y'); ?></p>
              </div>
              </a>
            </div>
        	<?php endif; ?>
        <?php endwhile; ?>
      </section>

      <section>
        <div class="row white-row">
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
        <div class="row white-row">
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

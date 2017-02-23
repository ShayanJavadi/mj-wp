<?php //<div class="text-center fadein3 carousel">
  //<img src="<?php bloginfo('template_url'); /img/carimage-1.jpg" alt="">
//</div> ?>

<?php get_header(); ?>

    <div class="main front-page-main">
      <div class="section-mobile-header-picture">
        <div class="front-page-double-links">
          <a href="#" class="slide-caption-link">Visit Gallery</a>
          <a href="#" class="slide-caption-link">Book Lessons</a>
        </div>
      </div>
      <section class="section-slide-show front-page-slide-show js--slider">
          <div class="slide-show large-12 large-centered  fadein">
            <div class="slide-1  fadein">
                <a href="#">
                  <div class="slide-caption">
                    <h3 class="slide-caption-text">View my art</h3>
                    <a href="#" class="slide-caption-link">Visit Gallery</a>
                  </div>
                </a>
            </div>
            <div class="slide-2 ">
              <a href="#">
                <div class="slide-caption">
                  <h3 class="slide-caption-text">Throw a painting party!</h3>
                  <a href="#" class="slide-caption-link">Book Now</a>
                </div>
              </a>
            </div>
            <div class="slide-3 ">
              <a href="#">
                <div class="slide-caption">
                  <h3 class="slide-caption-text">Something about kids</h3>
                  <a href="#" class="slide-caption-link">Book now</a>
                </div>
              </a>
            </div>
        </div>
      </section>
      <section>
        <div class="row front-page-classes-wrap">
          <div class="large-12 large-centered">
            <h2 class="text-center"> Art Events For Everyone! </h2>
          </div>
          <div class="large-4 columns front-page-box front-page-box-classes">
            <h3 class="text-center"> <i class="fa fa-paint-brush fa-big-icon text-center" aria-hidden="true"></i></h3>
            <h3 class="text-center">Kids Lesson</h3>
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
                  <div class="medium-6 columns text-center front-page-box-gallery"><?php the_post_thumbnail('home-small'); ?></div>
              </a>
        	<?php else : ?>
            <a href="<?php the_permalink(); ?>" class="front-page-title">
                <div class="medium-6 columns text-center front-page-box-gallery"><?php the_post_thumbnail('home-small'); ?></div>
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
                  <span class=" front-page-small-img"><?php the_post_thumbnail('home-small'); ?></span>
                  <h4 class="text-center front-page-title"><?php the_title(); ?></h4>
                  <p><?php the_excerpt(); ?></p>
                  <p class="front-page-date"><?php the_time('F j, Y'); ?></p>
                </div>
              </a>
        	<?php else : ?>
            <a href="<?php the_permalink(); ?>" class="front-page-title">
              <div class="large-6 columns front-page-box">
                <span class=" front-page-small-img"><?php the_post_thumbnail('home-small'); ?></span>
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
        <div class="large-12 large-centered">
          <h2 class="text-center"> Keep in touch  </h2>
        </div>
        <div class="row">
          <div class="large-8 medium-centered columns">
            <form action="<?php the_permalink(); ?>" method="post">
              <p><label for="name">Name: <span>*</span> <br><input type="text" name="message_name" value="<?php echo esc_attr($_POST['message_name']); ?>"></label></p>
              <p><label for="message_email">Email: <span>*</span> <br><input type="text" name="message_email" value="<?php echo esc_attr($_POST['message_email']); ?>"></label></p>
              <p><label for="message_text">Message: <span>*</span> <br><textarea type="text" name="message_text" rows="4" cols="40"><?php echo esc_textarea($_POST['message_text']); ?></textarea></label></p>
              <p><label for="message_human">Human Verification: <span>*</span> <br><input class="inline-block" type="text" style="width: 60px;" name="message_human"> + 3 = 5</label></p>
              <input type="hidden" name="submitted" value="1">
              <p><input type="submit" class="button secondary-button"></p>
            </form>
          </div>
        </div>
      </section>
    </div>
    <?php get_footer(); ?>

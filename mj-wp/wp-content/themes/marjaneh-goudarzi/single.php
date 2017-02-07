<?php get_header(); ?>
    <div class="main">
      <?php if (have_posts()) : ?>
      <?php while (have_posts()) : ?>
        <?php the_post(); ?>
        <?php if (get_the_category()[0]->slug === 'gallery') : ?>
          <section class="section-single-post">
            <div class="row">
              <h2 class="text-center"><?php the_title(); ?> </h2>
            </div>
            <div class="row fadein">
              <div class="large-6 columns fadein3">
                <?php the_post_thumbnail(); ?>
              </div>
              <div class="large-6 columns fadein2-6">
                <?php the_content(); ?>
              </div>
            </div>
          </section>
          </section>
        </div>
        <?php else : ?>
          <section class="image-carousel">
            <div class="row fadein3">
              <h2 class="text-center"><?php the_title(); ?> </h2>
              <p class="text-center front-page-date"><?php the_date(); ?></p>
              <div class="text-center single-post-div">
                <?php the_post_thumbnail(); ?>
              </div>
            </div>
            <div class="row">
              <div class="fadein3 single-post-div">
                <div class="row">
                  <div class="large-10 large-centered">
                    <?php the_content(); ?>
                  </div>
                </div>
              </div>
            </div>
          </section>
        <?php endif; ?>
      <?php endwhile; ?>
    <?php endif; ?>
    <?php get_footer(); ?>

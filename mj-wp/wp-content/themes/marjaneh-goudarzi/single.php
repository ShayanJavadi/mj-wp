<?php get_header(); ?>
    <div class="main">
      <?php if (have_posts()) : ?>
      <?php while (have_posts()) : ?>
        <?php the_post(); ?>
        <section class="image-carousel">
          <div class="row fadein3">
            <h2 class="text-center">&mdash; <?php the_title(); ?> &mdash;</h2>
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
      <?php endwhile; ?>
    <?php endif; ?>
    <?php get_footer(); ?>

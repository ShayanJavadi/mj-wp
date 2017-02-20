<?php get_header(); ?>
  <?php $featured_query = new WP_Query(array(
    'category_name' => 'gallery' )); ?>
    <div class="main front-page-main gallery-main">
      <section class="section-slide-show">
          <div class="slide-show large-12 large-centered  fadein">
            <div class="slide-gallery fadein">
              <div class="slide-caption slide-caption-pages">
                <h3 class="slide-caption-text ">Gallery</h3>
              </div>
            </div>
          </div>
      </section>
      <section class="gallery">

        <?php
          $i = 1;
        ?>
        <div class="row">

        <?php while($featured_query->have_posts()) :
          $featured_query->the_post(); ?>
          <?php if($i % 2 === 0) :?>
            <div class=" fadein">
          <?php else : ?>
            <div class=" fadein3">
          <?php endif; ?>
            <a href="<?php the_permalink(); ?>">
                <div class="gallery-small-img columns">
                  <?php the_post_thumbnail(); ?>
                </div>
              </a>
            </div>
        <?php $i++; ?>
      <?php endwhile; ?>
      </div>
      </section>
    </div>
    <?php get_footer(); ?>

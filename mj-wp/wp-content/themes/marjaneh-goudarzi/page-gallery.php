<?php get_header(); ?>
  <?php $featured_query = new WP_Query(array(
    'category_name' => 'gallery' )); ?>
    <div class="main front-page-main gallery-main">
      <section class="gallery">
        <div class="text-center single-title">
          <h2>Gallery</h2>
        </div>
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
                <div class="large-4 gallery-small-img columns">
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

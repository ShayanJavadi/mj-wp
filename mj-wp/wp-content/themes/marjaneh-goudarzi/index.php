<?php get_header(); ?>
      <?php
      function get_first_paragraph(){
         global $post;

         $str = wpautop( get_the_content() );
         $str = substr( $str, 0, strpos( $str, '</p>' ) + 4 );
         $str = strip_tags($str, '<a><strong><em>');
         return '<p>' . $str . ' [...] </p>';
         }
       ?>
       <?php $featured_query = new WP_Query(array(
         'category_name' => 'blog' )); ?>
        <?php $i = 0; ?>
        <?php while($featured_query->have_posts()) :
          $featured_query->the_post(); ?>
          <div class="main">
          <?php $i++; ?>
          <?php
            //perform check so we can make a grid of new blog posts
            if($i % 2 != 0) :
           ?>

           <a href="<?php the_permalink(); ?>">
             <section>
                 <div class="row fadein">
                   <h2 class="text-center index-blog-title">&mdash; <?php the_title(); ?> &mdash;</h2>
                   <p class=" text-center front-page-date"><?php the_date(); ?></p>
                 </div>
                 <div class="index-blog-thumbnail fadein">
                   <div class="row fadein">
                     <div class="large-6 columns">
                       <?php echo get_first_paragraph(); ?>
                     </div>
                     <div class="large-6 columns">
                       <?php the_post_thumbnail(); ?>
                     </div>
                   </div>
                 </div>
             </section>
           </a>
        	<?php else : ?>
          <a href="<?php the_permalink(); ?>">
            <section>
                <div class="row fadein">
                  <h2 class="text-center index-blog-title">&mdash; <?php the_title(); ?> &mdash;</h2>
                  <p class=" text-center front-page-date"><?php the_date(); ?></p>
                </div>
                <div class="index-blog-thumbnail">
                  <div class="row fadein">
                    <div class="large-6 columns">
                      <?php the_post_thumbnail(); ?>
                    </div>
                    <div class="large-6 columns">
                      <?php echo get_first_paragraph(); ?>
                    </div>
                  </div>
              </div>
            </section>
          </a>
        	<?php endif; ?>
          </div>
      <?php endwhile; ?>
    </div>
    <?php get_footer(); ?>

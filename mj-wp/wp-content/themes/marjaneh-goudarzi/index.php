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
        <section>

          <div class="main">
        <?php while($featured_query->have_posts()) :
          $featured_query->the_post(); ?>
          <?php $i++; ?>
          <?php
            //perform check so we can make a grid of new blog posts
            if($i % 2 != 0) :
           ?>
               <div class="index-blog-thumbnail fadein">
                 <div class="row fadein">
                   <a href="<?php the_permalink(); ?>">
                   <div class="large-6 columns">
                     <h2 class="text-left index-blog-title">&mdash; <?php the_title(); ?></h2>
                     <p class=" text-left front-page-date"><?php the_date(); ?></p>
                     <?php echo get_first_paragraph(); ?>
                   </div>
                   <div class="large-6 columns index-blog-odd-img">
                     <?php the_post_thumbnail(); ?>
                   </div>
                 </a>
                 </div>
               </div>
        	<?php else : ?>
                <div class="index-blog-thumbnail">
                  <div class="row fadein">
                    <a href="<?php the_permalink(); ?>">
                    <div class="large-6 columns ">
                      <?php the_post_thumbnail(); ?>
                    </div>
                    <div class="large-6 columns">
                      <h2 class="text-left index-blog-title">&mdash; <?php the_title(); ?></h2>
                      <p class=" text-left front-page-date"><?php the_date(); ?></p>
                      <?php echo get_first_paragraph(); ?>
                    </div>
                    </a>
                  </div>
              </div>
        	<?php endif; ?>
      <?php endwhile; ?>
    </div>
    </section>

    </div>
    <?php get_footer(); ?>

<?php get_header(); ?>
<?php
function get_first_paragraph(){
   global $post;

   $str = wpautop( get_the_content() );
   $str = substr( $str, 0, strpos( $str, '</p>' ) + 4 );
   $str = strip_tags($str, '<a><strong><em>');
   return '<p>' . $str . '</p>';
   }
 ?>
        <?php $i = 0; ?>
        <?php while(have_posts()) :
          the_post(); ?>
          <div class="main">
          <?php $i++; ?>
          <?php
            //perform check so we can make a grid of new blog posts
            if($i % 2 != 0) :
           ?>

           <a href="<?php the_permalink(); ?>">
             <section>
                 <div class="row">
                   <h2 class="text-center">&mdash; <?php the_title(); ?> &mdash;</h2>
                   <p class=" text-center front-page-date"><?php the_date(); ?></p>
                 </div>
                 <div class="index-blog-thumbnail">
                   <div class="row fadein js-lesson-1">
                     <div class="large-6 columns">
                       <h3 class="text-left">Some lesson</h3>
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
                <div class="row">
                  <h2 class="text-center">&mdash; <?php the_title(); ?> &mdash;</h2>
                  <p class=" text-center front-page-date"><?php the_date(); ?></p>
                </div>
                <div class="index-blog-thumbnail">
                  <div class="row fadein js-lesson-1">
                    <div class="large-6 columns">
                      <?php the_post_thumbnail(); ?>
                    </div>
                    <div class="large-6 columns">
                      <h3 class="text-left">Some lesson</h3>
                      <?php echo get_first_paragraph(); ?>
                    </div>
                  </div>
              </div>
            </section>
          </a>
        	<?php endif; ?>
      <?php endwhile; ?>
    </div>
    <?php get_footer(); ?>

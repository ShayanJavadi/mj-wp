<?php get_header(); ?>
  <div class="main front-page-main">
    <section>
      <div class="row ">
        <div class="large-12">
          <?php
    			while ( have_posts() ) : the_post();

    				get_template_part( 'template-parts/page/content', 'page' );

    				// If comments are open or we have at least one comment, load up the comment template.
    				if ( comments_open() || get_comments_number() ) :
    					comments_template();
    				endif;

    			endwhile; // End of the loop.
    			?>

        </div>
      </div>
    </section>
  </div>
<?php get_footer(); ?>

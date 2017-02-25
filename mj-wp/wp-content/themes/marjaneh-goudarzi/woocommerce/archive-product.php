<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
	//	do_action( 'woocommerce_before_main_content' );
	echo "<section>";
    woocommerce_output_content_wrapper();
	?>

		<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

      <section class="section-slide-show fadein">
          <div class="slide-show large-12 large-centered  ">
            <div class="slide-gallery ">
              <div class="slide-caption slide-caption-pages">
                <h3 class="slide-caption-text ">Shop</h3>
              </div>
            </div>
          </div>
      </section>
		<?php endif; ?>
	<div class="large-12">
		<ul class="tabs tabs-shop" data-tabs id="tabs">
			<li class="tabs-title tabs-title-shop is-active text-center"><a href="#panel1" aria-selected="true">Paintings</a></li>
			<li class="tabs-title tabs-title-shop text-center"><a href="#panel2">Commmissions</a></li>
		</ul>
		<div class="tabs-content" data-tabs-content="tabs">
			<div class="tabs-panel is-active" id="panel1">
				<div class="large-12">
					<h2 class="text-center">Paintings</h2>
				<?php
					/**
					 * woocommerce_archive_description hook.
					 *
					 * @hooked woocommerce_taxonomy_archive_description - 10
					 * @hooked woocommerce_product_archive_description - 10
					 */
					do_action( 'woocommerce_archive_description' );
					$args = array(
					'post_type' => 'product',
					'product_cat' => 'art',
					'posts_per_page' => 12
					);
					$loopProducts = new WP_Query( $args );
				?>

				<?php if ( $loopProducts->have_posts() ) : ?>


					<?php woocommerce_product_loop_start(); ?>

						<?php woocommerce_product_subcategories(); ?>

						<?php while ( $loopProducts->have_posts() ) : $loopProducts->the_post(); ?>

							<?php wc_get_template_part( 'content', 'product' ); ?>

						<?php endwhile; // end of the loop. ?>
						<?php wp_reset_query(); ?>
					<?php woocommerce_product_loop_end(); ?>

					<?php
						/**
						 * woocommerce_after_shop_loop hook.
						 *
						 * @hooked woocommerce_pagination - 10
						 */
						do_action( 'woocommerce_after_shop_loop' );
					?>

				<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

					<?php wc_get_template( 'loop/no-products-found.php' ); ?>

				<?php endif; ?>

				<?php
					/**
					 * woocommerce_after_main_content hook.
					 *
					 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
					 */
					do_action( 'woocommerce_after_main_content' );
				?>

			</div>
			<div class="tabs-panel" id="panel2">
				<div class="row">
					<div class="large-12">
						<h2 class="text-center">Commissions</h2>

						<?php
							/**
							 * woocommerce_archive_description hook.
							 *
							 * @hooked woocommerce_taxonomy_archive_description - 10
							 * @hooked woocommerce_product_archive_description - 10
							 */
							do_action( 'woocommerce_archive_description' );
							$args = array(
							'post_type' => 'product',
							'product_cat' => 'commissions',
							'posts_per_page' => 12
							);
							$loopProducts = new WP_Query( $args );
						?>

						<?php if ( $loopProducts->have_posts() ) : ?>


							<?php woocommerce_product_loop_start(); ?>

								<?php woocommerce_product_subcategories(); ?>

								<?php while ( $loopProducts->have_posts() ) : $loopProducts->the_post(); ?>

									<?php wc_get_template_part( 'content', 'product' ); ?>

								<?php endwhile; // end of the loop. ?>
								<?php wp_reset_query(); ?>
							<?php woocommerce_product_loop_end(); ?>

							<?php
								/**
								 * woocommerce_after_shop_loop hook.
								 *
								 * @hooked woocommerce_pagination - 10
								 */
								do_action( 'woocommerce_after_shop_loop' );
							?>

						<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

							<?php wc_get_template( 'loop/no-products-found.php' ); ?>

						<?php endif; ?>

						<?php
							/**
							 * woocommerce_after_main_content hook.
							 *
							 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
							 */
							do_action( 'woocommerce_after_main_content' );
							echo "</section>";

						?>

					</div>
				</div>
			</div>
			</div>

		</div>

	</div>
<?php get_footer( 'shop' ); ?>

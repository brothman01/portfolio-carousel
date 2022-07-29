<?php
/*
 * Template Name: Portfolio Item
 * Template Post Type: portfolio_item
 */

 get_header();  ?>

<div class="wrap" style="width: 80%; margin: 0px auto;">

	<h2 style="margin-bottom: 3%;"> <?php echo get_the_title(); ?></h2>
	<div class="bp_singlepage_portfolio_item_huge_image" style="background-position: center; background-image: url(<?php echo get_post_meta( get_the_ID(), 'Brothman_Portfolio_image1', true );?>);"></div>
	
				<?php
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/post/content', get_post_format() );

					?>
					<div style="overflow: hidden; margin-bottom: 5%;">
						<?php echo get_the_content(); ?>
					</div>
					<?php

					// If comments are open or we have at least one comment, load up the comment template.
					//if ( comments_open() ) {



				endwhile; // End of the loop.
			?>

	<div class="bp_singlepage_selector_image_row" style="width:100%; margin-bottom: 3%;">

		<div class="bp_singlepage_thumbnail_div">
			<img class="bp_portfolio_item_selector_image bp_portfolio_item_selected_image" src="<?php echo get_post_meta( get_the_ID(), 'Brothman_Portfolio_image1', true );?> " />
		</div>

		<div class="bp_singlepage_thumbnail_div">
			<img class="bp_portfolio_item_selector_image" src="<?php echo get_post_meta( get_the_ID(), 'Brothman_Portfolio_image2', true );?> " />
		</div>

		<div class="bp_singlepage_thumbnail_div">
			<img class="bp_portfolio_item_selector_image" src="<?php echo get_post_meta( get_the_ID(), 'Brothman_Portfolio_image3', true );?> " />
		</div>

		<div class="bp_singlepage_thumbnail_div">
			<img class="bp_portfolio_item_selector_image" src="<?php echo get_post_meta( get_the_ID(), 'Brothman_Portfolio_image4', true );?> " />
		</div>

		<div class="bp_singlepage_thumbnail_div">
			<img class="bp_portfolio_item_selector_image" src="<?php echo get_post_meta( get_the_ID(), 'Brothman_Portfolio_image5', true );?> " />
		</div>

	</div>





</div><!-- .wrap -->

<?php get_footer();

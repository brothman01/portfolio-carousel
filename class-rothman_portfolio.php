<?php
/**
 * Plugin Name: portfolio-carousel
 * Plugin URI:  https://wordpress.org/plugins/chatpress
 * Description: Display full portfolio on own page and/or all of them on a carousel.  Also adds a single portfolio item page template.
 * Version:     1.1.0
 * Author:      Ben Rothman
 * Author URI:  http://www.BenRothman.org
 * Text Domain: rportfolio
 * License:     GPL-2.0+
 **/

 // Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

class portfolio_carousel {

	public function __construct() {

		// register post type
		add_action( 'init', [ $this, 'bp_register_portfolio_item_cpt' ], 0 );

		// load and start CMB2
		if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
			require_once dirname( __FILE__ ) . '/cmb2/init.php';
		} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
			require_once dirname( __FILE__ ) . '/CMB2/init.php';
		}

		// // register and add the metaboxes to the portfolio item CPT
		add_action( 'cmb2_admin_init', [ $this, 'bp_register_metaboxes' ] );

		// // load styles
		 add_action( 'wp_enqueue_scripts', [ $this, 'rp_enqueue_styles' ] );

		// // register full portfolio  page shortcode
		add_shortcode( 'portfolio_page', [ $this, 'bp_portfolio_page_shortcode' ] );

		// register full portfolio  page shortcode
		add_shortcode( 'portfolio_carousel', [ $this, 'rp_portfolio_carousel_shortcode' ] );

		// // use my single template for single portfolio-item s
		// //add_filter('template_redirect', [ $this, 'my_custom_template' ] );
		 add_filter('template_include', [ $this, 'bp_include_template' ], 1);

		// load styles
		//add_action( 'wp_enqueue_scripts', [ $this, 'bp_enqueue_styles' ] );

		// // add support for home portfolio carousesl image size
		 add_action( 'init', [ $this, 'odevice_image_sizes' ] );

	}

	/**
	 * Register the Portfolio item CPT
	 *
	 * @since 0.1
	 */
	public function bp_register_portfolio_item_cpt() {

		$labels = array(
			'name'                  => _x( 'Portfolio Items', 'Post Type General Name', 'bportfolio_carousel' ),
			'singular_name'         => _x( 'Portfolio Item', 'Post Type Singular Name', 'bportfolio_carousel' ),
			'menu_name'             => __( 'Portfolio Item', 'bportfolio_carousel' ),
			'name_admin_bar'        => __( 'Portfolio Item', 'bportfolio_carousel' ),
			'archives'              => __( 'Portfolio Item Archives', 'bportfolio_carousel' ),
			'attributes'            => __( 'Portfolio Item Attributes', 'bportfolio_carousel' ),
			'parent_item_colon'     => __( 'Parent Item:', 'bportfolio_carousel' ),
			'all_items'             => __( 'Portfolio Items', 'bportfolio_carousel' ),
			'add_new_item'          => __( 'Add New Portfolio Item', 'bportfolio_carousel' ),
			'add_new'               => __( 'Add New', 'bportfolio_carousel' ),
			'new_item'              => __( 'New Item', 'bportfolio_carousel' ),
			'edit_item'             => __( 'Edit Item', 'bportfolio_carousel' ),
			'update_item'           => __( 'Update Item', 'bportfolio_carousel' ),
			'view_item'             => __( 'View Item', 'bportfolio_carousel' ),
			'view_items'            => __( 'View Items', 'bportfolio_carousel' ),
			'search_items'          => __( 'Search Item', 'bportfolio_carousel' ),
			'not_found'             => __( 'Not found', 'bportfolio_carousel' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'bportfolio_carousel' ),
			'featured_image'        => __( 'Featured Image', 'bportfolio_carousel' ),
			'set_featured_image'    => __( 'Set featured image', 'bportfolio_carousel' ),
			'remove_featured_image' => __( 'Remove featured image', 'bportfolio_carousel' ),
			'use_featured_image'    => __( 'Use as featured image', 'bportfolio_carousel' ),
			'insert_into_item'      => __( 'Insert into item', 'bportfolio_carousel' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'bportfolio_carousel' ),
			'items_list'            => __( 'Items list', 'bportfolio_carousel' ),
			'items_list_navigation' => __( 'Items list navigation', 'bportfolio_carousel' ),
			'filter_items_list'     => __( 'Filter items list', 'bportfolio_carousel' ),
		);
		$args = array(
			'label'                 => __( 'Portfolio Item', 'bportfolio_carousel' ),
			'description'           => __( 'Post Type Description', 'bportfolio_carousel' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'comments', 'thumbnail' ),
			'taxonomies'            => array( 'category', 'post_tag', ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'show_in_rest'					=> true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'portfolio_item', $args );}


	/**
	 * Add the metaboxes to the portfolio_item CPT
	 *
	 * @since 0.1
	 */
	public function bp_register_metaboxes() {
		$prefix = 'Bportfolio_carousel_';

		$bp_metabox = new_cmb2_box( array(
			'id'            => $prefix . 'metabox',
			'title'         => esc_html__( 'Portfolio Data', 'cmb2' ),
			'object_types'  => array( 'portfolio_item' ), // Post type
		) );


		$bp_metabox->add_field( array(
			'name' => esc_html__( 'Image 1', 'cmb2' ),
			'desc' => esc_html__( 'Upload an image or enter a URL.', 'cmb2' ),
			'id'   => $prefix . 'image1',
			'type'       => 'file',
		) );
		$bp_metabox->add_field( array(
			'name' => esc_html__( 'Image 1', 'cmb2' ),
			'desc' => esc_html__( 'Upload an image or enter a URL.', 'cmb2' ),
			'id'   => $prefix . 'image2',
			'type'       => 'file',
		) );

		$bp_metabox->add_field( array(
			'name' => esc_html__( 'Image 2', 'cmb2' ),
			'desc' => esc_html__( 'Upload an image or enter a URL.', 'cmb2' ),
			'id'   => $prefix . 'image3',
			'type'       => 'file',
		) );

		$bp_metabox->add_field( array(
			'name' => esc_html__( 'Image 3', 'cmb2' ),
			'desc' => esc_html__( 'Upload an image or enter a URL.', 'cmb2' ),
			'id'   => $prefix . 'image4',
			'type'       => 'file',
		) );

		$bp_metabox->add_field( array(
			'name' => esc_html__( 'Image 4', 'cmb2' ),
			'desc' => esc_html__( 'Upload an image or enter a URL.', 'cmb2' ),
			'id'   => $prefix . 'image5',
			'type'       => 'file',
		) );}

	/**
	 * Callback function to be executed when the portfolio page shortcode is used
	 *
	 * @since 0.1
	 */
	public function bp_portfolio_page_shortcode( $atts ) {

		$atts = shortcode_atts(
	       array(
	        'category' => 'Website',
	    ), $atts, '$atts');

		$the_category = $atts['category'];


	    $content = '<div class="portfolio_container" style="width: 80%!important; margin: 0px auto;">';

		$args = [ 'post_type' => 'portfolio_item',
		'orderby'=>'date',
		'order'=>'DESC',
		'posts_per_page' => -1,
		'category_name' => 'Website',
		'post_status' => 'publish',
		];

		$posts = new WP_Query( $args );

		// The Loop
		if ( $posts->have_posts() ) {
			$row_counter = 0;

			while ( $posts->have_posts() ) {
				$posts->the_post();

				if ( $row_counter % 4 == 0 ) {
					$content .= '<div class="bp_portfolio_row col-md-12">'; // OPEN PORTFOLIO ROW
				}


				$content .= '<div class="col-md-3 col-sm-12 bp_portfolio_item_cell" style="float: left; overflow-y: hidden; margin-bottom: 20px;">';

				$content .= '<a href="' . get_permalink() . '">';

					$content .= '<div id="portfolio_image_div">';
					$content .= get_the_post_thumbnail( get_the_ID(), 'home-size' );
					//$content .= '<img src="' . get_post_meta( get_the_ID(), 'Bportfolio_carousel_image1', true ) . '" width="240" height="150" >';
				$content .= '</div>';

				$content .= '<p style="text-align: center;">' . get_the_title() . '</p>';

				$content .= '</div></a>';

				if ( $row_counter % 4 == 3 ) {
					$content .= '</div>'; // CLOSE PORTFOLIO ROW
				}

				$row_counter++;}

			wp_reset_postdata();}

			$content .= '</div>';

		return $content;

	}

	/**
	* Function to create theme support to serve smaller images for portfolio carousel
	* @since 1.0
	*/
		public function odevice_image_sizes() {
		    add_image_size( 'home-size', 300, 100, true );
		}

	/**
	 * Callback function to be executed when the portfolio carousel shortcode is used
	 *
	 * @since 0.1
	 */
		public function rp_portfolio_carousel_shortcode() {


			$content = '<div id="wrapper">';
				$content .= '<div id="carousel">';

							$items = new WP_Query( [
							'post_type' => 'portfolio_item',
							'orderby'=>'date',
							'posts_per_page' => -1,
							'order'=>'DESC',
							'category_name' => 'Website',
							'post_status' => 'publish',
							] );

							if ( $items->have_posts() ) {
								while ( $items->have_posts() ) {
									$items->the_post();

									$content .= '<div>';
										$content .= '<a href="' . get_permalink() . '">';
											//$content .= get_the_post_thumbnail( get_the_ID() );
											// $content .= '<img src="' . get_post_meta( get_the_ID(), 'Bportfolio_carousel_image1', true ) . '" width="240" height="150" >';
											$content .= get_the_post_thumbnail( get_the_ID(), 'home-size' );
											//	$content .= '<a href="' . get_permalink() . '">';
										$content .= '</a>';
									$content .= '</div>';

								}
								/* Restore original Post Data */
								wp_reset_postdata();
							} else {
								// no posts found
							}

				$content .= '</div>';

				$content .= '<div id="nav">';
					$content .= '<a id="prev" href="#">&laquo; Prev</a>';
					$content .= '<a id="next" href="#">Next &raquo;</a>';
				$content .= '</div>';

			$content .= '</div>';

			return $content;
		}

		/**
	 * Callback function to enqueue stylesheets and scripts for this plugin
	 *
	 * @since 0.1
	 */
	public function rp_enqueue_styles() {

		// my custom styles.
		wp_enqueue_style( 'my-styles', plugins_url( 'library/css/portfolio.css', __FILE__ ) );

		// bportfolio_carousel script.
		wp_register_script( 'portfolio-script', plugins_url( 'library/js/bportfolio_carousel.js', __FILE__ ), [ 'jquery' ] );
		wp_enqueue_script( 'portfolio-script' );

		// CaroFredSel script.
		wp_register_script( 'CarouFredSel-Script', plugins_url('library/js/jquery.carouFredSel-6.1.0-packed.js', __FILE__), [ 'jquery' ] );
		wp_enqueue_script( 'CarouFredSel-Script' );

		// bportfolio_carousel script
		wp_register_script( 'bportfolio_carousel_carousel_init', plugins_url('library/js/bportfolio_carousel_init.js', __FILE__), [ 'jquery' ] );
		wp_enqueue_script( 'bportfolio_carousel_carousel_init' );

		wp_enqueue_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css');

	}

		/**
		 * Function used to force the site to use my custom templpate for the single 'portfolio_item' page.
		 *
		 * @since 0.1
		 */
		public function bp_include_template( $template_path ) {

			if( get_post_type() == 'portfolio_item' ) {
					$theme_file = plugin_dir_path( __FILE__ ) . 'single-portfolio_item.php';
					$template_path = $theme_file;
			}
			return $template_path;
		
			}

	/**
	* Function to create theme support to serve smaller images for portfolio carousel
	* @since 1.0
	*/
		// public function odevice_image_sizes() {
		//     add_image_size( 'home-size', 300, 100, true );
		// }

}

new portfolio_carousel();

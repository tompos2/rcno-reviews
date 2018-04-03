<?php

/**
 * Creates the shortcodes used for isotope grid.
 *
 * @link       https://wzymedia.com
 * @since      1.12.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public/shortcodes
 */

/**
 * Creates the shortcodes used for isotope grid.
 *
 * @since      1.12.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Isotope_Grid_Shortcode {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The Rcno_Template_Tags class
	 *
	 * @since    1.12.0
	 * @access   private
	 * @var      Rcno_Template_Tags $template The current version of this plugin.
	 */
	private $template;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->template = new Rcno_Template_Tags( $this->plugin_name, $this->version  );
	}

	public function rcno_do_isotope_grid_shortcode( $options ) {

		// Set default values for options not set explicitly.
		$options = shortcode_atts( array(
			'selectors' => 1,
			'width'     => 120,
			'height'    => 220
		), $options );

		// The actual rendering is done by a special function.
		$output = $this->rcno_render_isotope_grid( $options );

		// We are enqueuing the previously registered script.
		wp_enqueue_script( 'isotope-grid' );

		return do_shortcode( $output );
	}

	public function rcno_render_isotope_grid( $options ) {

		// Get an alphabetically ordered list of all reviews.
		$args  = array(
			'post_type'      => 'rcno_review',
			'post_status'    => 'publish',
			'orderby'        => 'post_title',
			'order'          => 'ASC',
			'posts_per_page' => - 1,
		);
		$query = new WP_Query( $args );
		$posts = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				global $post;
				$posts[] = $post;
			}
			wp_reset_postdata();
		}

		// Included once, as adding the shortcode twice to a page with case a PHP fatal error.
		include_once __DIR__ . '/layouts/isotope-grid.php';

		// Render the content using that file.
		$content = ob_get_contents();

		// Finish rendering.
		if ( ob_get_contents() ){
			ob_end_clean();
		}

		// Return the rendered content.
		return $content;
	}

	// Used in 'usort' to sort alphabetically by book title.
	private function cmp( $a, $b ) {
		return strcasecmp( $a['sorted_title'][0], $b['sorted_title'][0] );
	}
}
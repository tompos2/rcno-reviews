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
	 * The Rcno_Template_Tags class
	 *
	 * @since    1.12.0
	 * @access   private
	 * @var      Rcno_Template_Tags $template The current version of this plugin.
	 */
	private $template;

	/**
	 * An array of the variables used by the shortcode template
	 *
	 * @since    1.12.0
	 * @access   private
	 * @var      array $variables The current version of this plugin.
	 */
	private $variables;

	/**
	 * Rcno_Isotope_Grid_Shortcode constructor.
	 *
	 * @since 1.12.0
	 * @param $plugin_name
	 * @param $version
	 */
	public function __construct( $plugin_name, $version ) {

		$this->template = new Rcno_Template_Tags( $plugin_name, $version  );
		$this->variables = $this->rcno_get_shortcode_variables();
	}

	/**
	 * Fetches all the variables to be used in template and add them to an array.
	 *
	 * @since 1.12.0
	 * @return array
	 */
	private function rcno_get_shortcode_variables() {
		$variables = array();
		$variables['ignore_articles'] = (bool) Rcno_Reviews_Option::get_option( 'rcno_reviews_ignore_articles' );
		$variables['articles_list'] = str_replace( ',', '|', Rcno_Reviews_Option::get_option( 'rcno_reviews_ignored_articles_list', 'The,A,An' ) )
									. '|\d+';
		$variables['custom_taxonomies'] = explode( ',', Rcno_Reviews_Option::get_option( 'rcno_taxonomy_selection' ) );
		return $variables;
	}

	/**
	 * Return the actual shortcode code used the WP.
	 *
	 * @since 1.12.0
	 * @param $options
	 * @return string
	 */
	public function rcno_do_isotope_grid_shortcode( $options ) {

		// Set default values for options not set explicitly.
		$options = shortcode_atts( array(
			'selectors' => 1,
			'width'     => 85,
			'exclude'   => '',
			'rating'   => 1
		), $options, 'rcno-sortable-grid' );

		// The actual rendering is done by a special function.
		$output = $this->rcno_render_isotope_grid( $options );

		// We are enqueuing the previously registered script.
		wp_enqueue_script( 'isotope-grid' );

		return do_shortcode( $output );
	}

	/**
	 * * Do the render of the shortcode contents via the template file.
	 *
	 * @since 1.12.0
	 * @param $options
	 * @return string
	 */
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

		// This will check for a template file inside the theme folder.
		$template_path = get_query_template( 'rcno-isotope-grid' );

		// If no such file exists, use the default template for the shortcode.
		if ( empty( $template_path ) ) {
			$template_path = __DIR__ . '/layouts/rcno-isotope-grid.php';
		}

		include $template_path;

		// Render the content using that file.
		$content = ob_get_contents();

		// Finish rendering.
		if ( ob_get_contents() ){
			ob_end_clean();
		}

		// Return the rendered content.
		return $content;
	}

	/**
	 * Utility function used by `usort` to sort titles alphabetically
	 *
	 * @since 1.12.0
	 * @param $a
	 * @param $b
	 * @return int
	 */
	protected function sort_by_title( $a, $b ) {
		return strcasecmp( $a['sorted_title'][0], $b['sorted_title'][0] );
	}
}

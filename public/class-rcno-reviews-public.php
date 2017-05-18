<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Reviews_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rcno_Reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rcno_Reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rcno-reviews-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rcno_Reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rcno_Reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rcno-reviews-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add the 'rcno_review' CPT to the WP query.
	 *
	 * @since 1.0.0
	 *
	 * @param object $queryWP_Query object.
	 */
	public function rcno_review_query( WP_Query $query ) {
		// Don't change query on admin page
		if ( is_admin() ) {
			return;
		}

		// Check on all public pages.
		if ( ! is_admin() && $query->is_main_query() ) {
			// Post archive page:
			if ( is_post_type_archive( 'rcno_review' ) ) {
				// set post type to only reviews
				$query->set( 'post_type', 'rcno_review' );

				return;
			}
			// Add 'rcno_review' CPT to homepage if set in options.
			// @TODO: Create an option to add book reviews to homepage.
			if ( true === true ) {
				if ( is_home() || $query->is_home() || $query->is_front_page() ) {
					$this->rcno_add_review_to_query( $query );
				}
			}
			// Every other page.
			if ( is_category() || is_tag() || is_author() ) {
				$this->rcno_add_review_to_query( $query );

				return;
			}
		}
	}

	/**
	 * Change the query and add reviews to query object
	 *
	 * @since 1.0.0
	 *
	 * @param type WP_Query object.
	 *
	 * @return void
	 */
	private function rcno_add_review_to_query( WP_Query $query ) {
		// Add CPT to query.
		$post_type = $query->get( 'post_type' );

		if ( is_array( $post_type ) && ! array_key_exists( 'rcno_review', $post_type ) ) {
			$post_type[] = 'rcno_review';
		} else {
			$post_type = array( 'post', $post_type, 'rcno_review' );
		}

		$query->set( 'post_type', $post_type );

	}

	/**
	 * Adds the review CPT to the RSS Feed
	 *
	 * @since 1.0.0
	 *
	 * @param type array $query
	 *
	 * @return array $query
	 */
	public function rcno_add_reviews_to_rss_feed( array $query ) {

		if ( true === true ) { // @TODO: Create and option to enable/disable book reviews in RSS feed.
			if ( isset( $query['feed'] ) && ! isset( $query['post_type'] ) ) {
				$query['post_type'] = array( 'post', 'rcno_review' );
			}
		}

		return $query;
	}

	/**
	 * Get the rendered content of a review and forward it to the theme as the_content()
	 *
	 * @since 1.0.0
	 *
	 * @param string $content
	 *
	 * @return string $content
	 */
	public function rcno_get_review_content( $content ) {
		if ( ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		/* Only render specifically if we have a review */
		if ( get_post_type() === 'rcno_review' ) {
			// Remove the filter
			remove_filter( 'the_content', array( $this, 'rcno_get_review_content' ) );

			// Do the stuff
			$review_post          = get_post();
			$review               = get_post_custom( $review_post->ID );
			$GLOBALS['review_id'] = $review_post->ID;

			if ( is_single() || true == 'render full review on archive page' ) { // @TODO: Create and option to display full review on archive pages.
				$content = $this->rcno_render_review_content( $review_post );
				//$content = $content . $this->rcno_render_review_content( $review_post ); // @TODO: I am not sure about this.
			} else {
				// $content = $this->rcno_render_review_excerpt( $review_post ); // @TODO: Create 'rcno_render_review_excerpt' method.
			}

			// Add the filter again
			add_filter( 'the_content', array( $this, 'rcno_get_review_content' ), 10 );

			// return the rendered content
			return $content . '$$$';
		}

		return $content . '###';
	}

	/**
	 * Do the actual rendering using the review.php file provided by the layout
	 *
	 * @since 1.0.0
	 *
	 * @param object $review_post
	 *
	 * @return string $content
	 */
	public function rcno_render_review_content( $review_post ) {
		// Get the layout's include path
		$include_path = $this->rcno_get_the_layout() . 'review.php';

		if ( ! file_exists( $include_path ) ) {
			// If the layout does not provide a review template file, use the default one:
			// This NEVER should happen, but who knows...
			$include_path = plugin_dir_path( __FILE__ ) . 'templates/rcno_default/review.php';
		}

		// Get the book review data:
		$review = get_post_custom( $review_post->ID );
		//$content = $this->get_reviews_content($review_post);

		// Start rendering
		ob_start();

		// Include the book review template tags.
		require_once( __DIR__ . '/class-rcno-template-tags.php' ); //@TODO: Create template tags file.

		include( $include_path );
		// and render the content using that file:
		$content = ob_get_contents();

		// Finish rendering
		ob_end_clean();

		// return the rendered content:
		return $content;
	}

	/**
	 * Get the path to the layout file depending on the layout options
	 *
	 * @since 1.0.0
	 * @return string
	 */
	private function rcno_get_the_layout() {
		// Get the layout chosen:
		$layout = 'rcno_default'; // @TODO: Create and option to choose the book review template.
		// calculate the include path for the layout:
		// Check if a global or local layout should be used:
		if ( false !== strpos( $layout, 'local' ) ) {
			//Local layout
			$include_path = get_stylesheet_directory() . '/rcno-templates/' . preg_replace( '/^local\_/', '', $layout ) . '/';
		} else {
			//Global layout
			$include_path = plugin_dir_path( __FILE__ ) . 'templates/' . $layout . '/';
		}

		return $include_path;
	}

}

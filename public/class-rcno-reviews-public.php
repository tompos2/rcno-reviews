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
	 * Initialize the class and set its properties.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

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
	 * @param WP_Query $query
	 */
	public function rcno_review_query( WP_Query $query ) {
		// Don't change query on admin page.
		if ( is_admin() ) {
			return;
		}

		// Check on all public pages.
		if ( ! is_admin() && $query->is_main_query() ) {
			// Post archive page.
			if ( is_post_type_archive( 'rcno_review' ) ) {
				// set post type to only reviews.
				$query->set( 'post_type', 'rcno_review' );

				return;
			}

			$reviews_on_homepage = Rcno_Reviews_Option::get_option( 'rcno_reviews_on_homepage' );

			// Add 'rcno_review' CPT to homepage if set in options.
			if ( true === (bool) $reviews_on_homepage ) {
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

		$reviews_in_rss = Rcno_Reviews_Option::get_option( 'rcno_reviews_in_rss' );

		if ( true === (bool) $reviews_in_rss ) {
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

		// Only render specifically if we have a review.
		if ( 'rcno_review' === get_post_type() ) {
			// Remove the filter
			remove_filter( 'the_content', array( $this, 'rcno_get_review_content' ) );

			$review_post          = get_post();
			$review               = get_post_custom( $review_post->ID );
			$GLOBALS['review_id'] = $review_post->ID;
			$archive_display      = Rcno_Reviews_Option::get_option( 'rcno_reviews_archive' );

			if ( is_single() || 'archive_display_full' === $archive_display ) {
				$content = $this->rcno_render_review_content( $review_post );
			} else {
				$content = $this->rcno_render_review_excerpt( $review_post );
			}

			// Add the filter again.
			add_filter( 'the_content', array( $this, 'rcno_get_review_content' ), 10 );

		}

		// Return the rendered content.
		return $content;
	}

	/**
	 * Get the rendered excerpt of a book and forward it to the theme as the_excerpt()
	 * Same work is done by 'rcno_get_review_content', however some themes specifically include $post->excerpt,
	 * then content is rendered by this function
	 *
	 * @since 1.0.0
	 *
	 * @param string $content
	 *
	 * @return string $content
	 */
	public function rcno_get_review_excerpt( $content ) {
		if ( ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		// Only render specifically if we have a review.
		if ( 'rcno_review' === get_post_type() ) {

			remove_filter( 'get_the_excerpt', array( $this, 'rcno_get_review_excerpt' ), 10 );

			$review_post = get_post();

			$content = $this->rcno_render_review_excerpt( $review_post );

			add_filter( 'get_the_excerpt', array( $this, 'rcno_get_review_excerpt' ), 10 );

		} else {

			return $content;
		}
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
		// Get the layout's include path.
		$include_path = $this->rcno_get_the_layout() . 'review.php';

		if ( ! file_exists( $include_path ) ) {
			// If the layout does not provide a review template file, use the default one
			$include_path = plugin_dir_path( __FILE__ ) . 'templates/rcno_default/review.php';
		}

		// Get the book review data.
		$review = get_post_custom( $review_post->ID );
		//$content = $this->get_reviews_content($review_post);

		// Start rendering.
		ob_start();

		// Include the book review template tags.
		require_once( __DIR__ . '/class-rcno-template-tags.php' );

		include( $include_path );
		// and render the content using that file.
		$content = ob_get_contents();

		// Finish rendering.
		ob_end_clean();

		// return the rendered content.
		return $content;
	}


	/**
	 * Do the actual rendering using the excerpt.php file provided by the layout
	 *
	 * @since 1.0.0
	 *
	 * @param object $review_post
	 *
	 * @return string $content
	 */
	public function rcno_render_review_excerpt( $review_post ) {

		// Return if we are on a single post page AND post type is 'rcno_review'.
		if ( is_single() && 'rcno_review' === get_post_type() ) {
			return false;
		}
		// Get the layout's include path.
		$include_path = $this->rcno_get_the_layout() . 'excerpt.php';

		// Check if the layout file really exists.
		if ( ! file_exists( $include_path ) ) {
			// If the layout does not provide an excerpt file, use the default one.
			$include_path = plugin_dir_path( __FILE__ ) . 'templates/rcno_default/excerpt.php';
		}

		// Get the review data.
		$review = get_post_custom( $review_post->ID );
		//$content = $this->get_reviews_content($review_post);

		// Start rendering.
		ob_start();

		require_once( __DIR__ . '/class-rcno-template-tags.php' );
		// Include the excerpt file.
		include( $include_path );
		// and render the content using that file.
		$content = ob_get_contents();

		// Finish rendering.
		ob_end_clean();

		// return the rendered content.
		return $content;

	}


	/**
	 * Render a list of all terms of a taxonomy using the templates's 'taxonomy.php' file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $taxonomy
	 * @param bool   $headers
	 *
	 * @return string $content
	 */
	public function rcno_render_taxlist( $taxonomy, $headers = false ) {

		// Create empty output variable.
		$output = '';

		// Get the layout's include path
		$include_path = $this->rcno_get_the_layout() . 'taxonomy.php';

		if ( ! file_exists( $include_path ) ) {
			// If the layout does not provide an taxonomy file, use the default one:
			$include_path = plugin_dir_path( __FILE__ ) . 'templates/rcno_default/taxonomy.php';
		}

		// Set review_post to false for template tags
		$review_post = false;

		if ( 'n/a' !== $taxonomy && '' !== $taxonomy ) {
			// Get the terms of the selected taxonomy.
			$terms = get_terms( $taxonomy, array( 'orderby' => 'name', 'order' => 'ASC' ) );
		} else {
			// Set $terms to false for the layout and it's error messages
			$terms = false;
		}

		// Include the taxonomy file.
		include_once( dirname( __FILE__ ) . '/class-rcno-template-tags.php' );
		include( $include_path );

		// Render the content using that file.
		$content = ob_get_contents();

		// Finish rendering.
		ob_end_clean();

		// Return the rendered content.
		return $content;
	}


	/**
	 * Render a list of all reviews alphabetically using the layout's reviews_index.php file
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $headers
	 *
	 * @return string $content
	 */
	public function rcno_render_review_index( $headers = false ) {
		// Create empty output variable.
		$output = '';

		// Get the layout's include path.
		$include_path = $this->rcno_get_the_layout() . 'reviews_index.php';

		if ( ! file_exists( $include_path ) ) {
			// If the layout does not provide an taxonomy file, use the default one.
			$include_path = plugin_dir_path( __FILE__ ) . 'templates/rcno_default/reviews_index.php';
		}

		/**
		 * Set review_post to false for template tags.
		 */
		$review_post = false;

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

		if ( $query->have_posts() ) { //reviews found.
			while ( $query->have_posts() ) {
				$query->the_post();
				global $post;
				$posts[] = $post;
			}
		}

		// Include the taxonomy file.
		include_once( dirname( __FILE__ ) . '/class-rcno-template-tags.php' );
		include( $include_path );

		// Render the content using that file.
		$content = ob_get_contents();

		// Finish rendering.
		ob_end_clean();

		// Return the rendered content.
		return $content;
	}


	/**
	 * Get the path to the layout file depending on the layout options.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	private function rcno_get_the_layout() {

		// Get the layout chosen:
		$layout = Rcno_Reviews_Option::get_option( 'rcno_review_template' );
		// Calculate the include path for the layout.
		// Check if a global or local layout should be used.
		if ( false !== strpos( $layout, 'local' ) ) {
			//Local layout.
			$include_path = get_stylesheet_directory() . '/rcno-templates/' . preg_replace( '/^local\_/', '', $layout ) . '/';
		} else {
			//Global layout.
			$include_path = plugin_dir_path( __FILE__ ) . 'templates/' . $layout . '/';
		}

		return $include_path;
	}

	/**
	 * Filter the except length to 20 words.
	 *
	 * @since 1.0.0
	 *
	 * @param int $length Excerpt length.
	 *
	 * @return int modified excerpt length.
	 */
	public function rcno_reviews_excerpt_length( $length ) {
		return (int) Rcno_Reviews_Option::get_option( 'rcno_excerpt_word_count', '55' );
	}

	/**
	 * Filter the "read more" excerpt string link to the post.
	 *
	 * @since 1.0.0
	 *
	 * @param string $more "Read more" excerpt string.
	 *
	 * @return string modified "read more" excerpt string.
	 */
	function rcno_reviews_excerpt_more( $more ) {
		return sprintf( ' <a class="read-more" href="%1$s">%2$s</a>',
			get_permalink( get_the_ID() ),
			__( Rcno_Reviews_Option::get_option( 'rcno_excerpt_read_more', 'Read more' ), 'rcno-reviews' )
		);
	}

}

<?php

/**
 * Creates the shortcodes used for book reviews.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 */

/**
 * Creates the shortcodes used for book reviews.
 *
 *
 * @since      1.0.0
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Reviews_Shortcodes {

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
	 * An instance of the 'Rcno_Reviews_Public' class.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $public_methods;

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

		$this->public_methods = new Rcno_Reviews_Public( $this->plugin_name, $this->version );

	}

	/**
	 * Render an embedded review by evaluating the rcno-review shortcode.
	 *
	 * @since 1.0.0
	 * @param mixed $options
	 * @return string
	 */
	public function rcno_do_review_shortcode( $options ) {
		/**
		 * Set default values for options not set explicitly.
		 */
		$options = shortcode_atts( array(
			'id'      => 'n/a',
			'excerpt' => 0,
		), $options );

		/**
		 * Define variable for the post object
		 */
		$review_post = null;


		if ( $options['id'] !== 'n/a' ) {
			/**
			 * Get random post
			 */
			if ( $options['id'] === 'random' ) {

				$posts = get_posts( array(
					'post_type' => 'rcno_review',
					'nopaging' => true
				));

				$review_post = $posts[ array_rand( $posts ) ];
				/**
				 * Get post by id
				 */
			} else {
				$review_post = get_post( intval( $options['id'] ) );
			}

			if ( ! is_null( $review_post ) && $review_post->post_type === 'rcno_review' ) {
				$output               = '';
				$review               = get_post_custom( $review_post->ID );
				$GLOBALS['review_id'] = $review_post->ID;

				//$taxonomies = get_option('rpr_taxonomies', array());


				if ( $options['excerpt'] === 0 ) {
					// Embed complete review.
					$output = $this->public_methods->rcno_render_review_content( $review_post );
				} elseif ( $options['excerpt'] === 1 ) {
					// Embed excerpt only.
					//$output = $this->public_methods->rcno_render_review_excerpt( $review_post ); //@TODO: Create this function.
				}
			} else {
				$output = '';
			}

			return do_shortcode( $output );
		}
	}

}
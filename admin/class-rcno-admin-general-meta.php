<?php

/**
 * Saving the general meta information.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 */

/**
 * Saving the general meta information.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Admin_General_Meta {

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
	 *
	 * @param   string $version The version of this plugin.
	 */
	public function __construct( $version ) {
		$this->version = $version;

	}

	/**
	 * Add a metabox for the book description
	 *
	 * @since 1.0.0
	 */
	public function rcno_book_description_metabox() {
		// Add editor metabox for description
		add_meta_box(
			'rcno_book_description_metabox',
			__( 'Book Description', 'rcno-reviews' ),
			array( $this, 'do_rcno_book_description_metabox' ),
			'rcno_review',
			'normal',
			'high'
		);
	}

	public function do_rcno_book_description_metabox( $review ) {
		$description              = get_post_meta( $review->ID, 'rcno_book_description', true );
		$options                  = array(
			'textarea_rows' => 8,
		);
		$options['media_buttons'] = false;

		wp_editor( $description, 'rcno_book_description', $options );
	}

	/**
	 * Saves all the book data on the review edit screen.
	 *
	 * @since 1.0.0
	 */
	public function rcno_save_book_review_metadata( $review_id, $data, $review = null ) {

		// Saving description not only to the post_meta field but also to excerpt and content
		if ( isset( $data['rcno_book_description'] ) ) {

			$book_description = strip_tags( $data['rcno_book_description'] );
			update_post_meta( $review_id, 'rcno_book_description', $book_description );
			// Set Excerpt:
			//$review->post_content = $data['rcno_book_description'];
			//$review->post_excerpt = $data['rcno_book_description'];
			wp_update_post( $review );
		}
	}

}
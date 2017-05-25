<?php

/**
 * Saving a book for to use with in book reviews.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 */

/**
 * Saving a book for to use with in book reviews.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 * @author     wzyMedia <wzy@outlook.com>
 */

class Rcno_Admin_Book_Cover {

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
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Add a metabox for the book ISBN information.
	 *
	 * @since 1.0.0
	 */
	public function rcno_book_cover_metabox() {
		// Add editor metabox for the book cover.
		add_meta_box(
			'rcno_book_cover_metabox',
			__( 'Book Cover', 'rcno-reviews' ),
			array( $this, 'do_rcno_book_cover_metabox' ),
			'rcno_review',
			'side',
			'high'
		);
	}

	/**
	 * Builds and display the metabox UI.
	 * @param $review
	 */
	public function do_rcno_book_cover_metabox( $review ) {
		include __DIR__ . '/views/rcno-book-cover-metabox.php';
	}

	/**
	 * Saves all the book data on the review edit screen.
	 *
	 * @since 1.0.0
	 */
	public function rcno_save_book_cover_metadata( $review_id, $data, $review = null ) {

		// Saving description not only to the post_meta field but also to excerpt and content.
		if ( isset( $data['rcno_reviews_book_cover_src'] )  ) {
			$cover_src = sanitize_text_field( $data['rcno_reviews_book_cover_src'] );
			update_post_meta( $review_id, 'rcno_reviews_book_cover_src', $cover_src );
		}

		if ( isset( $data['rcno_reviews_book_cover_alt'] )  ) {
			$cover_alt = sanitize_text_field( $data['rcno_reviews_book_cover_alt'] );
			update_post_meta( $review_id, 'rcno_reviews_book_cover_alt', $cover_alt );
		}

		if ( isset( $data['rcno_reviews_book_cover_title'] )  ) {
			$cover_title = sanitize_text_field( $data['rcno_reviews_book_cover_title'] );
			update_post_meta( $review_id, 'rcno_reviews_book_cover_title', $cover_title );
		}

	}

}
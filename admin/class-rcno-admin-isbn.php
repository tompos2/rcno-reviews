<?php

/**
 * Saving the book ISBN meta information.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 */

/**
 * Saving the book ISBN meta information.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 * @author     wzyMedia <wzy@outlook.com>
 */

class Rcno_Admin_ISBN {
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
	 * Add a metabox for the book ISBN information.
	 *
	 * @since 1.0.0
	 */
	public function rcno_book_isbn_metabox() {
		// Add editor metabox for ISBN number.
		add_meta_box(
			'rcno_book_isbn_metabox',
			__( 'ISBN Number', 'rcno-reviews' ),
			array( $this, 'do_rcno_book_isbn_metabox' ),
			'rcno_review',
			'side',
			'high'
		);
	}

	/**
	 * Builds and display the metabox UI.
	 * @param $review
	 */
	public function do_rcno_book_isbn_metabox( $review ) {
		include __DIR__ . '/views/rcno-isbn-metabox.php';
	}

	/**
	 * Saves all the book data on the review edit screen.
	 *
	 * @since 1.0.0
	 */
	public function rcno_save_book_isbn_metadata( $review_id, $data, $review = null ) {

		// Saving description not only to the post_meta field but also to excerpt and content.
		if ( isset( $data['rcno_book_isbn'] ) && wp_verify_nonce( $data['rcno_isbn_nonce'], 'rcno_save_book_isbn_metadata' ) ) {

			$book_isbn = sanitize_text_field( $data['rcno_book_isbn'] );

			update_post_meta( $review_id, 'rcno_book_isbn', $book_isbn );

			wp_update_post( $review );
		}

	}

}
<?php

/**
 * Saving the general book meta information.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 */

/**
 * Saving the general book meta information.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 * @author     wzyMedia <wzy@outlook.com>
 */

class Rcno_Admin_General_Info {
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
	 * Add a metabox for the book meta information.
	 *
	 * @since 1.0.0
	 */
	public function rcno_book_general_info_metabox() {
		// Add editor metaboxes for general book information.
		add_meta_box(
			'rcno_book_general_info_metabox',
			__( 'General Information', 'rcno-reviews' ),
			array( $this, 'do_rcno_book_general_info_metabox' ),
			'rcno_review',
			'normal',
			'high'
		);
	}

	/**
	 * Builds and display the metaboxes UI.
	 * @param $review
	 */
	public function do_rcno_book_general_info_metabox( $review ) {
		include __DIR__ . '/views/rcno-general-info-metabox.php';
	}

	/**
	 * Saves all the book data on the review edit screen.
	 *
	 * @since 1.0.0
	 */
	public function rcno_save_book_general_info_metadata( $review_id, $data, $review = null ) {


		// Saving book publisher post_meta field.
		if ( isset( $data['rcno_book_publisher'] ) ) { //@TODO: Add book publisher nonce check.
			$book_publisher = sanitize_text_field( $data['rcno_book_publisher'] );
			update_post_meta( $review_id, 'rcno_book_publisher', $book_publisher );
		}

		// Saving book published date post_meta field.
		if ( isset( $data['rcno_book_pub_date'] ) ) { //@TODO: Add book pub date nonce check.
			$book_pub_date = sanitize_text_field( $data['rcno_book_pub_date'] );
			update_post_meta( $review_id, 'rcno_book_pub_date', $book_pub_date );
		}

		// Saving book published format post_meta field.
		if ( isset( $data['rcno_book_pub_format'] ) ) { //@TODO: Add book pub format nonce check.
			$book_pub_format = sanitize_text_field( $data['rcno_book_pub_format'] );
			update_post_meta( $review_id, 'rcno_book_pub_format', $book_pub_format );
		}

		// Saving book published format post_meta field.
		if ( isset( $data['rcno_book_page_count'] ) ) { //@TODO: Add book page count nonce check.
			$book_page_count = sanitize_text_field( $data['rcno_book_page_count'] );
			update_post_meta( $review_id, 'rcno_book_page_count', $book_page_count );
		}

		// Saving book published format post_meta field.
		if ( isset( $data['rcno_book_gr_review'] ) ) { //@TODO: Add book GR review value nonce check.
			$book_gr_review = sanitize_text_field( $data['rcno_book_gr_review'] );
			update_post_meta( $review_id, 'rcno_book_gr_review', $book_gr_review );
			//
		}

		wp_update_post( $review );

	}

}
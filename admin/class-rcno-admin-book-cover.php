<?php

/**
 * Saving a book cover for display in book reviews.
 *
 * Create the "Book Cover" metabox in the admin create/edit book review screen.
 * Also handles the saving and updating of the image source path, alt-text and title HTML attributes.
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
	 *
	 * @param   string $plugin_name     The ID of this plugin.
	 * @param   string $version         The current version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Add the book cover upload metabox.
	 *
	 * @since 1.0.0
	 * @uses  add_meta_meta_box()
	 * @return void
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
	 *
	 * @since 1.0.0
	 *
	 * @param object $review    The current post object.
	 *
	 * @return void
	 */
	public function do_rcno_book_cover_metabox( $review ) {
		include __DIR__ . '/views/rcno-book-cover-metabox.php';
	}

	/**
	 * Checks the presence of, sanitizes then saves all the book cover metadata on the review edit screen.
	 *
	 * @since 1.0.0
	 *
	 * @uses  update_post_meta()
	 * @uses  sanitize_text_field()
	 *
	 * @param int   $review_id The post ID of the review post.
	 * @param array $data      The data passed from the post custom metabox.
	 * @param mixed $review     The review object this data is being saved to.
	 *
	 * @return void
	 */
	public function rcno_save_book_cover_metadata( $review_id, $data, $review = null ) {

		// Saving description not only to the post_meta field but also to excerpt and content.
		if ( isset( $data['rcno_reviews_book_cover_src'] ) ) {
			$cover_src = sanitize_text_field( $data['rcno_reviews_book_cover_src'] );
			update_post_meta( $review_id, 'rcno_reviews_book_cover_src', $cover_src );
		}

		if ( isset( $data['rcno_reviews_book_cover_alt'] ) ) {
			$cover_alt = sanitize_text_field( $data['rcno_reviews_book_cover_alt'] );
			update_post_meta( $review_id, 'rcno_reviews_book_cover_alt', $cover_alt );
		}

		if ( isset( $data['rcno_reviews_book_cover_title'] ) ) {
			$cover_title = sanitize_text_field( $data['rcno_reviews_book_cover_title'] );
			update_post_meta( $review_id, 'rcno_reviews_book_cover_title', $cover_title );
		}

		if ( isset( $data['rcno_reviews_book_cover_id'] ) ) {
			$cover_id = absint( $data['rcno_reviews_book_cover_id'] );
			update_post_meta( $review_id, 'rcno_reviews_book_cover_id', $cover_id );
		}

		if ( isset( $data['rcno_reviews_book_cover_url'] ) ) {
			$cover_url = esc_url( $data['rcno_reviews_book_cover_url'] );
			update_post_meta( $review_id, 'rcno_reviews_book_cover_url', $cover_url );
		}

		/*if ( isset( $data['rcno_reviews_gr_cover_url'] ) && '' !== $data['rcno_reviews_gr_cover_url'] ) {

			$old_url = get_post_meta( $review_id, 'rcno_reviews_gr_cover_url', true );

			if ( $old_url === $data['rcno_reviews_gr_cover_url'] ) {
				return;
			}

			// Fetch and Store the Image.
			$image_url = esc_url( $data['rcno_reviews_gr_cover_url'] );
			$get       = wp_remote_get( $image_url, array( 'user-agent' => 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML like Gecko) Chrome/44.0.2403.155 Safari/537.36' ) );
			$type      = wp_remote_retrieve_header( $get, 'content-type' );
			$mirror    = wp_upload_bits( rawurldecode( basename( $image_url ) ), null, wp_remote_retrieve_body( $get ) );

			if ( false !== $mirror['error'] ) {
				return;
			}

			// Attachment options.
			$attachment = array(
				'post_title'     => basename( $image_url ),
				'post_mime_type' => $type,
			);

			// Add the image to your media library and set as featured image.
			$attach_id   = wp_insert_attachment( $attachment, $mirror['file'], $review_id );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $image_url );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			update_post_meta( $review_id, 'rcno_reviews_gr_cover_url', $image_url );
			set_post_thumbnail( $review_id, $attach_id );
		}*/

		do_action( 'rcno_save_admin_book_cover_metadata' );
	}

}

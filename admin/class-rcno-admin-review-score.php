<?php

/**
 * Saving the book score meta information.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 */

/**
 * Saving the book review score meta information.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 * @author     wzyMedia <wzy@outlook.com>
 */

class Rcno_Admin_Review_Score {
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
	public function rcno_book_review_score_metabox() {
		// Add editor metabox for ISBN number.
		add_meta_box(
			'rcno_book_review_score_metabox',
			__( 'Review Score', 'rcno-reviews' ),
			array( $this, 'do_rcno_book_review_score_metabox' ),
			'rcno_review',
			'normal',
			'high'
		);
	}

	/**
	 * Builds and display the metabox UI.
	 * @param $review
	 */
	public function do_rcno_book_review_score_metabox( $review ) {
		include __DIR__ . '/views/rcno-review-score-metabox.php';
	}

	/**
	 * Saves all the book data on the review edit screen.
	 *
	 * @since 1.0.0
	 */
	public function rcno_save_book_review_score_metadata( $review_id, $data, $review = null ) {

		$old = get_post_meta( $review_id, 'rcno_review_score_criteria', true);
		$new = array();

		$labels = isset( $data['label'] ) ? $data['label'] : array();
		$scores = isset( $data['score'] ) ? $data['score'] : array();

		$count = count( $labels );

		for ( $i = 0; $i < $count; $i++ ) {
			if ( $labels[$i] !== '' ) {
				$new[ $i ]['label'] = strip_tags( $labels[ $i ] );
				$new[ $i ]['score'] = strip_tags( $scores[ $i ] );
			}
		}
		if ( ! empty( $new ) && $new !== $old ) {
			update_post_meta( $review_id, 'rcno_review_score_criteria', $new );
		} elseif ( empty( $new ) && $old ) {
			delete_post_meta( $review_id, 'rcno_review_score_criteria', $old );
		}

		if( isset( $data['rcno_review_score_type'] ) ) {
			$review_score_type = $data['rcno_review_score_type'];
			update_post_meta( $review_id, 'rcno_review_score_type', $review_score_type );
		}

		if( isset( $data['rcno_review_score_position'] ) ) {
			$review_score_position = $data['rcno_review_score_position'];
			update_post_meta( $review_id, 'rcno_review_score_position', $review_score_position );
		}

		if( isset( $data['rcno_review_score_enable'] ) ) {
			$review_score_enable = $data['rcno_review_score_enable'];
			update_post_meta( $review_id, 'rcno_review_score_enable', $review_score_enable );
		}
	}

}
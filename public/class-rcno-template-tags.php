<?php
/**
 * The methods used to render the recipe components on the frontend.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public
 */

/**
 * The methods used to render the recipe components on the frontend.
 *
 * Defines all the methods used to render the various components of a recipe
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Template_Tags {


	/**
	 * Renders the book description. No output if description is empty.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	private function get_the_rcno_book_description() {
		// Get the review id
		if ( isset( $GLOBALS['review_id'] ) && $GLOBALS['review_id'] !== '' ) {
			$review_id = $GLOBALS['review_id'];
		} else {
			$review_id = get_post()->ID;
		}

		$review = get_post_custom( $review_id );

		// Create an empty output string
		$out = '';

		// Render the description only if it is not empty
		if ( isset( $review['rcno_book_description'] ) ) {
			if ( strlen( $review['rcno_book_description'][0] ) > 0 ) { // @TODO: This index does not exist.
				$out .= '<div class="rcno_book_description">';
				$out .= sanitize_post_field( 'rcno_book_description', $review['rcno_book_description'][0], $review_id );
				$out .= '</div>';
			}
		}

		// Return the rendered description
		return $out;
	}

	/**
	 * Outputs the rendered description
	 *
	 * @since 1.0.0
	 */
	public function the_rcno_book_description() {
		echo $this->get_the_rcno_book_description();
	}

}
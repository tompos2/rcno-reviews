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
	 * Renders the book description. An empty string if description is empty.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	static private function get_the_rcno_book_description() {
		// Get the review ID.
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
			if ( strlen( $review['rcno_book_description'][0] ) > 0 ) {
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
	static public function the_rcno_book_description() {
		echo self::get_the_rcno_book_description();
	}


	/**
	 * Calculates the review scores
	 *
	 * @param      $num
	 * @param      $type
	 * @param bool $star
	 *
	 * @return string
	 */
	static private function rcno_calc_review_score( $num, $type, $stars = false ) {

		$output = '';

		switch ( $type ) {

			case 'stars' :

				if ( false !== $stars ) {
					if ( $num <= 2 ) {
						$output = '<span class="badge-star" title="1 star"><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-empty"></i><i class="dashicons dashicons-star-empty"></i><i class="dashicons dashicons-star-empty"></i><i class="dashicons dashicons-star-empty"></i></span>';
					}
					if ( $num > 2 && $num <= 4 ) {
						$output = '<span class="badge-star" title="2 stars"><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-empty"></i><i class="dashicons dashicons-star-empty"></i><i class="dashicons dashicons-star-empty"></i></span>';
					}
					if ( $num > 4 && $num <= 6 ) {
						$output = '<span class="badge-star" title="3 stars"><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-empty"></i><i class="dashicons dashicons-star-empty"></i></span>';
					}
					if ( $num > 6 && $num <= 8 ) {
						$output = '<span class="badge-star" title="4 stars"><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-empty"></i></span>';
					}
					if ( $num > 8 && $num <= 10 ) {
						$output = '<span class="badge-star" title="5 stars"><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-filled"></i><i class="dashicons dashicons-star-filled"></i></span>';
					}
				} else {
					$output = $num;
				}

				break;

			case 'letter' :
				if ( $num <= 2 ) {
					$output = 'E';
				}
				if ( $num > 2 && $num <= 4 ) {
					$output = 'D';
				}
				if ( $num > 4 && $num <= 6 ) {
					$output = 'C';
				}
				if ( $num > 6 && $num <= 8 ) {
					$output = 'B';
				}
				if ( $num > 8 && $num <= 10 ) {
					$output = 'A';
				}
				break;

			case 'number';
				$output = $num;
				break;
		}

		return $output;
	}


	/**
	 * Creates the review box for the frontend.
	 *
	 * @param      $review_id
	 *
	 * @return string | null
	 */
	static private function rcno_the_review_box( $review_id ) {

		$rating_type           = get_post_meta( $review_id, 'rcno_review_score_type', true );
		$rating_criteria       = get_post_meta( $review_id, 'rcno_review_score_criteria', true );

		if ( '' === $rating_criteria  ) {
			return null; // We are not doing anything if a review score has not been set.
		}

		$rating_criteria_count = count( $rating_criteria );
		$review_author         = get_the_author();
		$review_date           = get_the_date();
		$review_summary        = 'The review summary goes here'; //@TODO: Create review summary.
		$review_box_title      = 'The review title goes here'; //@TODO: Create review title.

		$score_array = array();

		foreach ( $rating_criteria as $criteria ) {
			$score_array[] = $criteria['score'];
		}

		$final_score = array_sum( $score_array );
		$final_score = $final_score / $rating_criteria_count;
		$final_score = number_format( $final_score, 1, '.', '' );

		$output = '';
		$output .= '<div id="rcno-review-score-box" itemscope itemtype="http://data-vocabulary.org/Review">';
		$output .= '<span class="reviewer" itemprop="reviewer" style="display:none;">' . $review_author . '</span>';
		$output .= '<time class="dtreviewed" itemprop="dtreviewed" style="display:none;">' . $review_date . '</time>';
		$output .= '<span class="summary" itemprop="summary" style="display:none;">' . $review_summary . '</span>';
		$output .= '<span class="rating" style="display: none;" itemprop="rating">' . self::rcno_calc_review_score( $final_score, $rating_type, false );
		$output .= '</span>';
		$output .= '<span itemprop="worst" style="display: none;">1</span>';
		$output .= '<span itemprop="best" style="display: none;">10</span>';

		$output .= '<div class="review-summary">';
		$output .= '<div class="overall-score">';
		$output .= '<span class="overall">' . self::rcno_calc_review_score( $final_score, $rating_type, true ) . '</span>';
		$output .= '<span class="overall-text">' . __( 'Overall Score', 'rcno-reviews' ) . '</span>';
		$output .= '</div>';
		$output .= '<div class="review-text">';
		$output .= '<span itemprop="itemreviewed" class="review-title">' . $review_box_title . '</span>';
		$output .= '<p>' . $review_summary . '</p>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '<ul>';
		foreach ( $rating_criteria as $criteria ) {
			$percentage_score = $criteria['score'] * 10;

			if ( $criteria['label'] ) {
				$output .= '<li>';
			}
			$output .= '<div class="rcno-review-score-bar-container">';
			$output .= '<div class="review-score-bar" style="width:' . $percentage_score . '%">';
			$output .= '<span class="score-bar">' . $criteria['label'] . '</span>';
			$output .= '</div>';
			$output .= '<span class="right">';
			$output .= self::rcno_calc_review_score( $criteria['score'], $rating_type );
			$output .= '</span>';
			$output .= '</div>';
			$output .= '</li>';

		}
		$output .= '</ul>';

		$output .= '</div><!-- End #review-box -->';

		return $output;
	}

	/**
	 * Prints the review box on the frontend.
	 *
	 * @param $review_id
	 */
	static public function rcno_print_review_box( $review_id ) {
		echo self::rcno_the_review_box( $review_id );
	}




	/**
	 * Creates the review badge for the frontend.
	 *
	 * @param      $post_id
	 * @param bool $echo
	 *
	 * @return string | null
	 */
	static private function rcno_the_review_badge( $review_id ) {

		$rating_type           = get_post_meta( $review_id, 'rcno_review_score_type', true );
		$rating_criteria       = get_post_meta( $review_id, 'rcno_review_score_criteria', true );

		if ( '' === $rating_criteria  ) {
			return null; // We are not doing anything if a review score has not been set.
		}

		$rating_criteria_count = count( $rating_criteria );

		$output      = '';
		$score_array = array();

		if ( $rating_criteria ) {
			foreach ( $rating_criteria as $criteria ) {
				$score_array[] = $criteria['score'];
			}
		}

		$final_score = array_sum( $score_array );
		$final_score = $final_score / $rating_criteria_count;
		$final_score = number_format( $final_score, 1, '.', '' );

		$output .= '<div class="rcno-review-badge review-badge-' . $rating_type . '">';
		$output .= self::rcno_calc_review_score( $final_score, $rating_type, true );
		$output .= '</div>';

		return $output;
	}

	/**
	 * Prints the review badge on the frontend.
	 * @param $review_id
	 */
	static public function rcno_print_review_badge( $review_id ){
		echo self::rcno_the_review_badge( $review_id );
	}



}
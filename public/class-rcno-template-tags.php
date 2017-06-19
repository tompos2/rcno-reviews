<?php
/**
 * The methods used to render the review components on the frontend.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public
 */

/**
 * The methods used to render the review components on the frontend.
 *
 * Defines all the methods used to render the various components of a review.
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Template_Tags {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;


	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      object    $public_rating    An instance of the Public Rating class.
	 */
	protected $public_rating;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $private_score    An array of all the private scoring criteria.
	 */
	protected $private_score;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      int    $private_rating    The rating from the 5 star metabox.
	 */
	protected $private_rating;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}


	/**
	 * Includes the 'functions file' to be used by the book review template.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function include_functions_file() {

		// Get the layout chosen.
		$layout = Rcno_Reviews_Option::get_option( 'rcno_review_template' );

		// Calculate the include path for the layout: Check if a global or local layout should be used.
		if ( false !== strpos( $layout, 'local' ) ) {
			// Local layout.
			$include_path = get_stylesheet_directory() . '/rcno-templates/' . preg_replace('/^local\_/', '', $layout ) . '/functions.php';
		} else {
			// Global layout.
			$include_path = plugin_dir_path( __FILE__ )  . 'templates/' . $layout . '/functions.php';
		}

		// Check if the layout file really exists.
		if ( file_exists( $include_path ) ) {
			// Include the functions.php.
			include_once( $include_path );
		}
	}

	/** ****************************************************************************
	 * FULL BOOK DETAILS TEMPLATE TAGS
	 *******************************************************************************/

	/**
	 * Generates the book details box.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return string
	 */
	public function get_the_rcno_full_book_details( $review_id ) {
		$review = get_post_custom( $review_id );

		$out = '';
		$out .= '<div class="rcno-full-book">'
		;
		$out .= '<div class="rcno-full-book-cover">';
		$out .= $this->get_the_rcno_book_cover( $review_id );
		$out .= $this->get_the_rcno_admin_book_rating( $review_id );
		$out .= '</div>';

		$out .= '<div class="rcno-full-book-details">';
		$out .= '<div class="col-1">';
		$out .= $this->get_the_rcno_taxonomy_terms( $review_id, 'rcno_author', true );
		$out .= $this->get_the_rcno_taxonomy_terms( $review_id, 'rcno_genre', true );
		$out .= $this->get_the_rcno_taxonomy_terms( $review_id, 'rcno_series', true );
		$out .= '</div>';

		$out .= '<div class="col-2">';
		$out .= $this->get_the_rcno_taxonomy_terms( $review_id, 'rcno_publisher', true );
		$out .= $this->get_the_rcno_book_meta( $review_id, 'rcno_book_pub_date', 'div', true );
		$out .= $this->get_the_rcno_book_meta( $review_id, 'rcno_book_page_count', 'div', true );
		$out .= '</div>';
		$out .= '</div>';

		$out .= '<div class="rcno-full-book-description">';
		$out .= wp_trim_words( $this->get_the_rcno_book_description( $review_id ), 75 );
		$out .= '</div>';

		$out .= '</div>';

		return $out;
	}

	/**
	 * Prints the book details box.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return void
	 */
	public function the_rcno_full_book_details( $review_id ) {
		echo $this->get_the_rcno_full_book_details( $review_id );
	}

	/** ****************************************************************************
	 * ADMIN RATING TEMPLATE TAGS
	 *******************************************************************************/

	/**
	 * Generates the private 5 star rating given by the reviewer.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return bool|string
	 */
	private function get_the_rcno_admin_book_rating( $review_id ) { // @TODO: Use an SVG to avoid issues with none UTF-8 fonts.
		$review = get_post_custom( $review_id );

		if ( ! isset( $review['rcno_admin_rating'] ) ) {
			return false;
		}

		$book_rating = (int) $review['rcno_admin_rating'][0];
		$this->private_rating = $book_rating;

		switch ( $book_rating ) {
			case 5:
				return '<div class="rcno-admin-rating">
							<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
							</div>';
				break;

			case 4;
				return '<div class="rcno-admin-rating">
							<span>★</span><span>★</span><span>★</span><span>★</span><span>☆</span>
							</div>';
				break;

			case 3;
				return '<div class="rcno-admin-rating">
							<span>★</span><span>★</span><span>★</span><span>☆</span><span>☆</span>
							</div>';
				break;

			case 2;
				return '<div class="rcno-admin-rating">
							<span>★</span><span>★</span><span>☆</span><span>☆</span><span>☆</span>
							</div>';
				break;

			case 1;
				return '<div class="rcno-admin-rating">
							<span>★</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span>
							</div>';
				break;

			default:
				return '<div class="rcno-admin-rating">
							<span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span>
							</div>';
		}
	}

	/**
	 * Prints the private 5 star rating given by the reviewer.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return void
	 */
	public function the_rcno_admin_book_rating( $review_id ) {
		echo $this->get_the_rcno_admin_book_rating( $review_id );
	}



	/** ****************************************************************************
	 * BOOK COVER TEMPLATE TAGS
	 *******************************************************************************/

	/**
	 * Generates the book cover, or a default book cover if none is uploaded.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return bool|string
	 */
	public function get_the_rcno_book_cover( $review_id, $wrapper = true ) {
		$review = get_post_custom( $review_id );

		if ( ! isset( $review['rcno_reviews_book_cover_src'] ) ) {
			return false;
		}

		$book_src = $review['rcno_reviews_book_cover_src'][0];
		if ( '' === $book_src ) {
			$book_src = Rcno_Reviews_Option::get_option( 'rcno_default_cover', plugin_dir_url( __FILE__ ) . 'images/no-cover.jpg' );
		}

		if ( false === $wrapper) {
			return $book_src;
		}

		$book_title = $review['rcno_reviews_book_cover_title'][0];
		$book_alt = $review['rcno_reviews_book_cover_alt'][0];

		$out = '';
		$out .= '<img src="' . esc_attr( $book_src ) . '" ';
		$out .= 'title="' . esc_attr( $book_title ) . '" ';
		$out .= 'alt="' . esc_attr( $book_alt ) . '" ';
		$out .= 'class="rcno-book-cover"';
		$out .= '>';

		return $out;
	}

	/**
	 * Prints the book cover, or a default book cover if none is uploaded.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return void
	 */
	public function the_rcno_book_cover( $review_id ) {
		echo $this->get_the_rcno_book_cover( $review_id );
	}



	/** ****************************************************************************
	 * TAXONOMY RELATED TEMPLATE TAGS
	 *******************************************************************************/

	private function get_the_rcno_taxonomy_headline( $taxonomy ) {

		// Get the taxonomy.
		$tax = get_taxonomy( $taxonomy );

		$out = '';

		/**
		 * Add a H3 heading for embedded reviews or a H2
		 * heading for a standalone review.
		 */
		if ( $this->is_review_embedded() ) {
			$out .= '<h3>' . $tax->labels->name . '</h3>';
		} else {
			$out .= '<h2>' . $tax->labels->name . '</h2>';
		}

		return $out;
	}

	/**
	 * Print the taxonomy headline.
	 *
	 * @param $taxonomy
	 */
	public function the_rcno_taxonomy_headline( $taxonomy ) {
		echo $this->get_the_rcno_taxonomy_headline( $taxonomy );
	}


	/**
	 * @param        $taxonomy
	 * @param bool   $label
	 * @param string $sep
	 *
	 * @return null|string
	 */
	public function get_the_rcno_taxonomy_terms( $review_id, $taxonomy, $label = false, $sep = ', ' ) {

		$out = '';

		$terms = get_the_term_list( $review_id, $taxonomy, '<span class="rcno-tax-term">', $sep, '</span>' );
		$tax   = get_taxonomy( $taxonomy );

		if ( false === $tax || false === $terms ) {
			return null;
		}

		$counts = wp_get_post_terms( $review_id, $taxonomy ); // Get the number of terms in a category, per post.
		$tax_label = $tax->labels->name;

		if ( count( $counts ) === 1 ) { // If we have only 1 term singularize the label name.
			$tax_label = Rcno_Pluralize_Helper::singularize( $tax_label );
		}


		$prefix = '';

		if ( $label ) {
			$prefix = '<span class="rcno-tax-name">' . $tax_label . ': ' . '</span>';
		}

		if ( $terms && ! is_wp_error( $terms ) ) {
			$out .= sprintf(
				'<div class="rcno-term-list">%1s%2s</div>',
				$prefix,
				$terms
			);
			$out .= '';
		}

		return $out;
	}

	/**
	 * Print taxonomy terms.
	 *
	 * @param string $taxonomy
	 * @param bool   $label
	 * @param string $sep
	 */
	public function the_rcno_taxonomy_terms( $review_id, $taxonomy, $label = false, $sep = ', ' ) {
		echo $this->get_the_rcno_taxonomy_terms( $review_id, $taxonomy, $label, $sep );
	}


	/**
	 * @param $label
	 * @param $sep
	 *
	 * @return string
	 */
	private function get_the_rcno_taxonomy_list( $label, $sep ) {
		$out = '';

		$custom_taxonomies = Rcno_Reviews_Option::get_option( 'rcno_taxonomy_selection' );

		foreach ( $custom_taxonomies as $tax_key => $tax_value ) {
			$out .= $this->get_the_rcno_taxonomy_terms( 'rcno_' . $tax_key, $label, $sep );
		}

		return $out;
	}

	/**
	 * @param $label
	 * @param $sep
	 */
	public function the_rcno_taxonomy_list( $label, $sep ) {
		echo $this->get_the_rcno_taxonomy_list( $label, $sep );
	}


	/** ****************************************************************************
	 * REVIEW BOOK DESCRIPTION TEMPLATE TAGS
	 *******************************************************************************/

	/**
	 * Renders the book description. An empty string if description is empty.
	 *
	 * @param int $review_id
	 * @since 1.0.0
	 * @return string
	 */
	private function get_the_rcno_book_description( $review_id ) {

		$review = get_post_custom( $review_id );

		// Create an empty output string
		$out = '';

		// Render the description only if it is not empty
		if ( isset( $review['rcno_book_description'] ) ) {
			if ( strlen( $review['rcno_book_description'][0] ) > 0 ) {
				$out .= '<div class="rcno-book-description">';
				$out .= sanitize_post_field( 'rcno_book_description', $review['rcno_book_description'][0], $review_id );
				$out .= '</div>';
			}
		}

		// Return the rendered description.
		return $out;
	}

	/**
	 * Prints the book's description.
	 *
	 * @since 1.0.0
	 * @param $review_id
	 * @return void
	 */
	public function the_rcno_book_description( $review_id ) {
		echo $this->get_the_rcno_book_description( $review_id );
	}


	/** ****************************************************************************
	 * REVIEW BOOK REVIEW CONTENT TEMPLATE TAGS
	 *******************************************************************************/

	/**
	 * Generates the markup for the review content of a book review.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return string
	 */
	public function get_the_rcno_book_review_content( $review_id ) {

		$review_content = '';
		$review_content .= '<div class="rcno-book-review-content">';
		$review_content .= apply_filters( 'the_content', get_post_field( 'post_content', $review_id ) );
		$review_content .= '</div>';

		return $review_content;
	}

	/**
	 * Prints out the review content of a book review.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return void
	 */
	public function the_rcno_book_review_content( $review_id ) {
		echo $this->get_the_rcno_book_review_content( $review_id );
	}



	/** ****************************************************************************
	 * REVIEW BOOK META TEMPLATE TAGS
	 *******************************************************************************/

	/**
	 * Generated the required markup for the requested stored book metadata,
	 * accessed via specific meta-keys.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @param string $meta_key
	 * @param string $wrapper
	 * @param bool $label
	 *
	 * @return string|null
	 */
	public function get_the_rcno_book_meta( $review_id, $meta_key = '', $wrapper = '', $label = true ) {

		$review = get_post_custom( $review_id );

		$meta_keys = array(
			'rcno_book_publisher'   => 'Publisher',
			'rcno_book_pub_date'    => 'Published',
			'rcno_book_pub_format'  => 'Format',
			'rcno_book_pub_edition' => 'Edition',
			'rcno_book_page_count'  => 'Page Count',
			'rcno_book_gr_review'   => 'Goodreads Rating',
			'rcno_book_gr_id'       => 'Gr ID',
			'rcno_book_isbn13'      => 'ISBN13',
			'rcno_book_isbn'        => 'ISBN',
			'rcno_book_asin'        => 'ASIN',
			'rcno_book_gr_url'      => 'Gr URL',
			'rcno_book_title'       => 'Title',

		);

		$wrappers = array(
			'',
			'span',
			'div',
			'p',
			'h1',
			'h2',
			'h3',
		);

		if ( '' === $meta_key || ! array_key_exists( $meta_key, $meta_keys ) || ! in_array( $wrapper, $wrappers, true ) ) {
			return null;
		}

		if ( isset( $review[ $meta_key ] ) ) {
			if ( strlen( $review[ $meta_key ][0] ) > 0 ) {
				$out = '';
				if ( '' === $wrapper ) {
					$out .= '';
				} else {
					$out .= '<' . $wrapper . ' ' . 'class="' . sanitize_html_class( $meta_key ) . '"' . '>';
				}

				if ( $label ) {
					$out .= __( $meta_keys[ $meta_key ], 'rcno-reviews' ) . ': ';
				}

				$out .= sanitize_text_field( $review[ $meta_key ][0] );

				if ( '' === $wrapper ) {
					$out .= '';
				} else {
					$out .= '</' . $wrapper . '>';
				}

				return $out;
			} else {
				return '';
			}
		}

		return null;
	}


	/**
	 * Prints out the requested stored book metadata, accessed via specific meta-keys.
	 *
	 * @since 1.0.0
	 *
	 * @param int $review_id
	 * @param string $meta_key
	 * @param string $wrapper
	 * @param bool $label
	 *
	 * @return void
	 */
	public function the_rcno_book_meta( $review_id, $meta_key, $wrapper, $label ) {
		echo $this->get_the_rcno_book_meta( $review_id, $meta_key, $wrapper, $label );
	}

	/** ****************************************************************************
	 * REVIEW BOOK REVIEW SCORE TEMPLATE TAGS
	 *******************************************************************************/

	/**
	 * Calculates the review scores.
	 *
	 * @since 1.0.0
	 *
	 * @param int $num
	 * @param string $type
	 * @param bool $stars
	 *
	 * @return string
	 */
	private function rcno_calc_review_score( $num, $type, $stars = false ) {

		$color = Rcno_Reviews_Option::get_option( 'rcno_show_review_score_box_accent_2', '#ffd700' );

		$output = '';

		switch ( $type ) {

			case 'stars' :

				if ( false !== $stars ) {
					if ( $num <= 1 ) {
						$output = '<span class="badge-star" title="1 star" style="color:' . $color . '"><span>★</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span></span>';
					}
					if ( $num > 1 && $num <= 2 ) {
						$output = '<span class="badge-star" title="2 stars" style="color:' . $color . '"><span>★</span><span>★</span><span>☆</span><span>☆</span><span>☆</span></span>';
					}
					if ( $num > 2 && $num <= 3 ) {
						$output = '<span class="badge-star" title="3 stars" style="color:' . $color . '"><span>★</span><span>★</span><span>★</span><span>☆</span><span>☆</span></span>';
					}
					if ( $num > 3 && $num <= 4 ) {
						$output = '<span class="badge-star" title="4 stars" style="color:' . $color . '"><span>★</span><span>★</span><span>★</span><span>★</span><span>☆</span></span>';
					}
					if ( $num > 4 && $num <= 5 ) {
						$output = '<span class="badge-star" title="5 stars" style="color:' . $color . '"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></span>';
					}
				} else {
					$output = $num;
				}

				break;

			case 'letter' :
				if ( $num <= 1 ) {
					$output = 'E';
				}
				if ( $num > 1 && $num <= 2 ) {
					$output = 'D';
				}
				if ( $num > 2 && $num <= 3 ) {
					$output = 'C';
				}
				if ( $num > 3 && $num <= 4 ) {
					$output = 'B';
				}
				if ( $num > 4 && $num <= 5 ) {
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
	 * @since 1.0.0
	 * @param  int  $review_id
	 * @return string|null|false
	 */
	private function rcno_the_review_box( $review_id ) {

		// Disables the review score box displaying on frontend book reviews.
		if ( false === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_review_score_box' ) ) {
			return false;
		}

		$background = Rcno_Reviews_Option::get_option( 'rcno_show_review_score_box_background' );
		$accent = Rcno_Reviews_Option::get_option( 'rcno_show_review_score_box_accent' );

		$rating_type           = get_post_meta( $review_id, 'rcno_review_score_type', true );
		$rating_criteria       = get_post_meta( $review_id, 'rcno_review_score_criteria', true );

		if ( '' === $rating_criteria  ) {
			return null; // We are not doing anything if a review score has not been set.
		}

		$rating_criteria_count = count( $rating_criteria );
		$review_summary        = substr(wp_strip_all_tags($this->get_the_rcno_book_review_content( $review_id ), true), 0, 260) . '...';
		$review_box_title      = $this->get_the_rcno_book_meta( $review_id, 'rcno_book_title', '', false );

		$score_array = array();

		foreach ( $rating_criteria as $criteria ) {
			$score_array[] = $criteria['score'];
		}


		$final_score = array_sum( $score_array );
		$final_score = $final_score / $rating_criteria_count;
		$final_score = number_format( $final_score, 1, '.', '' );


		$output = '';
		$output .= '<div id="rcno-review-score-box" style="background:' . $background . '">';
		$output .= '<div class="review-summary">';
		$output .= '<div class="overall-score" style="background:' . $accent . '">';
		$output .= '<span class="overall">' . $this->rcno_calc_review_score( $final_score, $rating_type, true ) . '</span>';
		$output .= '<span class="overall-text">' . __( 'Overall Score', 'rcno-reviews' ) . '</span>';
		$output .= '</div>';
		$output .= '<div class="review-text">';
		$output .= '<h2 class="review-title">' . $review_box_title . '</h2>';
		$output .= '<p>' . $review_summary . '</p>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '<ul>';
		foreach ( $rating_criteria as $criteria ) {
			$percentage_score = ( $criteria['score'] / 5 ) * 100;

			if ( $criteria['label'] ) {
				$output .= '<li>';
			}
			$output .= '<div class="rcno-review-score-bar-container">';
			$output .= '<div class="review-score-bar" style="width:' . $percentage_score . '%; background:' . $accent .'">';
			$output .= '<span class="score-bar">' . $criteria['label'] . '</span>';
			$output .= '</div>';
			$output .= '<span class="right">';
			$output .= $this->rcno_calc_review_score( $criteria['score'], $rating_type, true );
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
	 * @since 1.0.0
	 * @param int $review_id
	 * @return void
	 */
	public function rcno_print_review_box( $review_id ) {
		echo $this->rcno_the_review_box( $review_id );
	}


  /**
   * Creates the review badge for the frontend.
   *
   * @since 1.0.0
   * @param int $review_id
   * @return string|null|false
   */
	private function rcno_the_review_badge( $review_id ) {

		// Disables the review score badge displaying on frontend book reviews.
		if ( false === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_review_score_box' ) ) {
			return false;
		}

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
		$output .= '<div class="score">';
		$output .= $this->rcno_calc_review_score( $final_score, $rating_type, true );
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Prints the review badge on the frontend.
	 *
	 * @since 1.0.0
	 * @param $review_id
	 * @return void
	 */
	public function rcno_print_review_badge( $review_id ){
		echo $this->rcno_the_review_badge( $review_id );
	}


	/** ****************************************************************************
	 * REVIEW BOOK REVIEW SCHEME DATA TAGS
	 *******************************************************************************/

	/**
	 * Gets the review criteria scores even if the box is disabled.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return array
	 */
	public function rcno_get_review_score( $review_id ) {
		$review_scores = get_post_meta( $review_id, 'rcno_review_score_criteria', true );
		$scores = array();

		if ( $review_scores ) {
			foreach ( $review_scores as $score ) {
				$scores[] = $score['score'];
			}
		}
		return $scores;
	}

	/**
	 * Generates the markup for the 'Book' schema type in the JSON+LD format.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return string
	 */
	public function get_the_rcno_book_schema_data( $review_id ) {

		$out = '';
		$out .= '<script type="application/ld+json">';
		$out .= '{';
		$out .= '"@context": "http://schema.org"';
		$out .= ', "@type": "Book"';
		$out .= ', "name": ' . '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_title', '', false ) .'"';
		$out .= ', "author": {'; // Begin author.
		$out .= '"@type": "Person"';
		$out .= ', "name": ' . '"' . wp_strip_all_tags( $this->get_the_rcno_taxonomy_terms( $review_id, 'rcno_author', false ) ) . '"';
		$out .= '}'; // End author.
		$out .= ', "url": ' . '"' . get_post_permalink( $review_id ) . '"'; // URL to this review page.
		$out .= ', "datePublished": ' . '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_pub_date', '', false ) . '"';
		$out .= ', "genre": ' . '"' . wp_strip_all_tags( $this->get_the_rcno_taxonomy_terms( $review_id, 'rcno_genre', false ) ) .'"';
		$out .= ', "publisher": '. '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_publisher', '', false ) . '"';
		$out .= ', "workExample": ['; // Begin workExample.
		$out .= '{'; // Begin First example.
		$out .= '"@type": "Book"';
		$out .= ', "isbn": ' . '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_isbn', '', false ) . '"';
		$out .= ', "bookEdition": ' . '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_pub_edition', '', false ) . '"';
		$out .= ', "bookFormat": "http://schema.org/' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_pub_format', '', false ) . '"';
		$out .= ', "numberOfPages": ' . (int) $this->get_the_rcno_book_meta( $review_id, 'rcno_book_page_count', '', false );

//		$out .= ', "potentialAction": {'; // Begin potentialAction.
//		$out .= '"@type": "ReadAction"';
//		$out .= ', "target": {'; // Begin target.
//		$out .= '"@type": "EntryPoint"';
//		$out .= ', "urlTemplate": "http://www.barnesandnoble.com/store/info/offer/0316769487?purchase=true"';
//		$out .= ', "actionPlatform": ['; // Begin actionPlatform.
//		$out .= '"http://schema.org/DesktopWebPlatform"';
//		$out .= ', "http://schema.org/IOSPlatform"';
//		$out .= ', "http://schema.org/AndroidPlatform"';
//		$out .= ']'; // End actionPlatform.
//		$out .= '}'; // End target.
//		$out .= '}'; // End potentialAction.

		$out .= '}'; // End First example.
		$out .= ']'; // End workExample.
		$out .= '}';
		$out .= '</script>';

		return $out;
	}

	/**
	 * Prints the 'Book' schema type in the JSON+LD format.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return void
	 */
	public function the_rcno_book_schema_data( $review_id ) {
		echo $this->get_the_rcno_book_schema_data( $review_id );
	}


	/**
	 * Generates the markup for the 'Review' schema type in the JSON+LD format.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return string
	 */
	public function get_the_rcno_review_schema_data( $review_id ) {

		$this->public_rating = new Rcno_Reviews_Public_Rating( $this->plugin_name, $this->version );
		$this->private_score = $this->rcno_get_review_score( $review_id );

		$out = '';
		$out .= '<script type="application/ld+json">';
		$out .= '{';

		$out .= '"@context": "http://schema.org"';
		$out .= ', "@type": "Review"';

		$out .= ', "author": {';
		$out .= '"@type": "Person"';
		$out .= ', "name": ' . '"' . get_the_author() . '"';
		$out .= ', "sameAs": ' . '"' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '"';
		$out .= '}';

		$out .= ', "url": ' . '"' . get_post_permalink( $review_id ) . '"';
		$out .= ', "datePublished": ' . '"' . get_the_date( 'F j, Y', $review_id ) . '"';

		$out .= ', "publisher": {';
		$out .= '"@type": "Organization"';
		$out .= ', "name": ' . '"' . get_bloginfo( 'name' ) . '"';
		$out .= ', "sameAs": ' . '"' . get_bloginfo( 'url' ) .'"';
		$out .= '}';

		$out .= ', "description": ' . '"' . substr(wp_strip_all_tags($this->get_the_rcno_book_review_content( $review_id ), true), 0, 199) . '"';
		$out .= ', "inLanguage": ' . '"' . get_bloginfo( 'language' ) . '"';

		$out .= ', "itemReviewed": {';
		$out .= '"@type":"Book"';
		$out .= ', "name": ' . '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_title', '', false ) . '"';
		$out .= ', "isbn": ' . '"'. $this->get_the_rcno_book_meta( $review_id, 'rcno_book_isbn', '',false ) . '"';

		$out .= ', "author": {';
		$out .= '"@type": "Person"';
		$out .= ', "name": ' . '"' . wp_strip_all_tags( $this->get_the_rcno_taxonomy_terms( $review_id, 'rcno_author', false ) ) .'"';
		$out .= ', "sameAs": "https://plus.google.com/114108465800532712602"'; // Social profile for book author.
		$out .= '}';

		$out .= ', "datePublished": ' . '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_pub_date', '', false ) . '"';

		$out .= '}';

		if ( $this->private_score ) {
			$out .= ', "reviewRating": {';
			$out .= '"@type": "Rating"';
			$out .= ', "worstRating": ' . min( $this->private_score );
			$out .= ', "bestRating": '  . max( $this->private_score );
			$out .= ', "ratingValue": ' . array_sum( $this->private_score ) / count( $this->private_score );
			$out .= '}';
		} else {
			$out .= ', "reviewRating": {';
			$out .= '"@type": "Rating"';
			$out .= ', "worstRating": ' . 1;
			$out .= ', "bestRating": '  . 5;
			$out .= ', "ratingValue": ' . $this->private_rating;
			$out .= '}';
		}

		if( $this->public_rating->rcno_rating_info( 'count' ) > 0 ) {
			$out .= ', "aggregateRating": {';
			$out .= '"@type":"AggregateRating"';
			$out .= ', "worstRating": ' . $this->public_rating->rcno_rating_info( 'min' );
			$out .= ', "bestRating": '  . $this->public_rating->rcno_rating_info( 'max' );
			$out .= ', "ratingValue": ' . $this->public_rating->rcno_rating_info( 'avg' );
			$out .= ', "reviewCount": ' . $this->public_rating->rcno_rating_info( 'count' );
			$out .= '}';
		}

		$out .= '}';
		$out .= '</script>';

		return $out;
	}

	/**
	 * Prints the 'Review' schema type in the JSON+LD format.
	 *
	 * @since 1.0.0
	 * @param int $review_id
	 * @return void
	 */
	public function the_rcno_review_schema_data( $review_id ) {
		echo $this->get_the_rcno_review_schema_data( $review_id );
	}


	/**
	 * Generates a navigation bar of letters of the alphabet.
	 *
	 * @since 1.0.0
	 * @param array $letters
	 * @return string
	 */
	public function get_the_rcno_alphabet_nav_bar( $letters = array() ) {

		// An array with the (complete) alphabet.
		$alphabet = range( 'A', 'Z' );

		$out = '';

		// Start the list.
		$out .= '<ul class="rcno-alphabet-navigation">';

		foreach ( $alphabet as $a ) {
			// loop through the alphabet.
			if ( $letters ) {
				if ( in_array( $a, $letters, true ) ) {
					// active letter, so we should set a link in the nav menu.
					$out .= '<li class="active"><a href="#' . $a . '">' . $a . '</a></li>';
				} else {
					// inactive letter, no link.
					$out .= '<li class="inactive">' . $a . '</li>';
				}
			} else {
				// each letter active.
				$out .= '<li class="active"><a href="#' . $a . '">' . $a . '</a></li>';
			}
		}

		// End the list.
		$out .= '</ul>';

		// return the rendered nav bar.
		return $out;
	}

	/**
	 * Prints a navigation bar of letters of the alphabet.
	 *
	 * @since 1.0.0
	 * @param array $letters
	 * @return void
	 */
	public function the_rcno_alphabet_nav_bar( $letters ) {
		echo $this->get_the_rcno_alphabet_nav_bar( $letters );
	}


	/**
	 * Check if the review is embedded in a post, page or another custom post type.
	 *
	 * @since 1.0.0
	 * @return boolean
	 */
	public function is_review_embedded() {
		if ( 'rcno_review' !== get_post_type() ) {
			return true;
		} else {
			return false;
		}
	}

}
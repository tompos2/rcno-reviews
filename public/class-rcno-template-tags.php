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

	private $rcno_review_score;

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
	 */
	public function include_functions_file() {
		// Get the layout chosen.
		$layout = 'rcno_default'; // @TODO: Create settings option for this.

		// Calculate the include path for the layout: Check if a global or local layout should be used.
		if ( strpos( $layout, 'local' ) !== false ) {
			// Local layout.
			$include_path = get_stylesheet_directory() . '/rcno_template/' . preg_replace('/^local\_/', '', $layout ) . '/functions.php';
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
	 * TAXONOMY RELATED TEMPLATE TAGS
	 *******************************************************************************/

	private function get_the_rcno_taxonomy_headline( $taxonomy ) {

		// Get the taxonomy.
		$tax = get_taxonomy( $taxonomy );

		$out = '';

		/**
		 * Add a H3 heading for embedded recipes or a H2
		 * heading for a standalone recipe
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
	private function get_the_rcno_taxonomy_terms( $taxonomy, $label = false, $sep = '/' ) {
		if ( isset( $GLOBALS['review_id'] ) && $GLOBALS['review_id'] !== '' ) { //Correctly get ID for embedded reviews.
			$review_id = $GLOBALS['review_id'];
		} else {
			$review_id = get_post()->ID;
		}

		$out = '';

		$terms = get_the_term_list( $review_id, $taxonomy, '<span class="rcno-tax-term">', __( $sep, 'rcno-reviews' ), '</span>' );
		$tax   = get_taxonomy( $taxonomy );

		if ( false === $tax || false === $terms ) {
			return null;
		}

		$prefix = '';

		if ( $label ) {
			$prefix = '<span class="rcno-tax-name">' . $tax->labels->name . ': ' . '</span>';
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
	 * @param        $taxonomy
	 * @param bool   $label
	 * @param string $sep
	 */
	public function the_rcno_taxonomy_terms( $taxonomy, $label = false, $sep = '/' ) {
		echo $this->get_the_rcno_taxonomy_terms( $taxonomy, $label, $sep );
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

		// Return the rendered description
		return $out;
	}

	/**
	 * Outputs the rendered description
	 *
	 * @param $review_id
	 * @since 1.0.0
	 */
	public function the_rcno_book_description( $review_id ) {
		echo $this->get_the_rcno_book_description( $review_id );
	}


	/** ****************************************************************************
	 * REVIEW BOOK REVIEW CONTENT TEMPLATE TAGS
	 *******************************************************************************/

	private function get_the_rcno_book_review_content( $review_id ) {

		$review_content = '';
		$review_content .= '<div class="rcno-book-review-content">';
		$review_content .= apply_filters( 'the_content', get_post_field( 'post_content', $review_id ) );
		$review_content .= '</div>';

		return $review_content;
	}

	/**
	 * @param $review_id
	 */
	public function the_rcno_book_review_content( $review_id ) {
		echo $this->get_the_rcno_book_review_content( $review_id );
	}



	/** ****************************************************************************
	 * REVIEW BOOK META TEMPLATE TAGS
	 *******************************************************************************/

	public function get_the_rcno_book_meta( $review_id, $meta_key = '', $wrapper = '' ) {

		$review = get_post_custom( $review_id );

		$meta_keys = array(
			'rcno_book_publisher',
			'rcno_book_pub_date',
			'rcno_book_pub_format',
			'rcno_book_pub_edition',
			'rcno_book_page_count',
			'rcno_book_gr_review',
			'rcno_book_gr_id',
			'rcno_book_isbn13',
			'rcno_book_isbn',
			'rcno_book_asin',
			'rcno_book_gr_url',
			'rcno_book_title',

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

		if ( '' === $meta_key || ! in_array( $meta_key, $meta_keys, true ) || ! in_array( $wrapper, $wrappers, true ) ) {
			return null;
		}

		if ( isset( $review[ $meta_key ] ) ) {
			if ( strlen( $review[ $meta_key ][0] ) > 0 ) {
				$out = '';
				if ( '' === $wrapper ) {
					$out .= '';
				} else {
					$out .= '<' . $wrapper . ' ' . 'class="' . sanitize_html_class( $meta_key ) . '"' . '>'; // @TODO: Sanitizing the HTML class is not necessary.
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



	/** ****************************************************************************
	 * REVIEW BOOK REVIEW SCORE TEMPLATE TAGS
	 *******************************************************************************/

	/**
	 * Calculates the review scores
	 *
	 * @param               $num
	 * @param   string      $type
	 * @param   bool        $stars
	 *
	 * @return string
	 */
	private function rcno_calc_review_score( $num, $type, $stars = false ) {

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
	 * @param  int  $review_id
	 *
	 * @return string|null
	 */
	private function rcno_the_review_box( $review_id ) {

		$rating_type           = get_post_meta( $review_id, 'rcno_review_score_type', true );
		$rating_criteria       = get_post_meta( $review_id, 'rcno_review_score_criteria', true );

		if ( '' === $rating_criteria  ) {
			return null; // We are not doing anything if a review score has not been set.
		}

		$rating_criteria_count = count( $rating_criteria );
		$review_summary        = 'The review summary goes here'; // @TODO: Create review summary.
		$review_box_title      = 'The review title goes here'; // @TODO: Create review title.

		$score_array = array();

		foreach ( $rating_criteria as $criteria ) {
			$score_array[] = $criteria['score'];
		}

		$final_score = array_sum( $score_array );
		$final_score = $final_score / $rating_criteria_count;
		$final_score = number_format( $final_score, 1, '.', '' );

		$this->rcno_review_score = $final_score;

		$output = '';
		$output .= '<div id="rcno-review-score-box">';
		$output .= '<div class="review-summary">';
		$output .= '<div class="overall-score">';
		$output .= '<span class="overall">' . $this->rcno_calc_review_score( $final_score, $rating_type, false ) . '</span>';
		$output .= '<span class="overall-text">' . __( 'Overall Score', 'rcno-reviews' ) . '</span>';
		$output .= '</div>';
		$output .= '<div class="review-text">';
		$output .= '<span class="review-title">' . $review_box_title . '</span>';
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
			$output .= $this->rcno_calc_review_score( $criteria['score'], $rating_type, false );
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
	 * @param int $review_id
	 */
	public function rcno_print_review_box( $review_id ) {
		echo $this->rcno_the_review_box( $review_id );
	}


  /**
	 * Creates the review badge for the frontend.
	 *
	 * @param   int     $review_id
	 *
	 * @return string|null
	 */
	private function rcno_the_review_badge( $review_id ) {

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
		$output .= $this->rcno_calc_review_score( $final_score, $rating_type, true );
		$output .= '</div>';

		return $output;
	}

	/**
	 * Prints the review badge on the frontend.
	 * @param $review_id
	 */
	public function rcno_print_review_badge( $review_id ){
		echo $this->rcno_the_review_badge( $review_id );
	}


	/** ****************************************************************************
	 * REVIEW BOOK REVIEW SCHEME DATA TAGS
	 *******************************************************************************/

	public function get_the_rcno_book_schema_data( $review_id ) {

		$out = '';
		$out .= '<script type="application/ld+json">';
		$out .= '{';
		$out .= '"@context": "http://schema.org"';
		$out .= ', "@type": "Book"';
		$out .= ', "name": ' . '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_title' ) .'"';
		$out .= ', "author": {'; // Begin author
		$out .= '"@type": "Person"';
		$out .= ', "name": ' . '"' . wp_strip_all_tags( $this->get_the_rcno_taxonomy_terms( 'rcno_author', false ) ) . '"';
		$out .= '}'; // End author
		$out .= ', "url": ' . '"' . get_post_permalink( $review_id ) . '"'; // URL to this review page
		$out .= ', "datePublished": ' . '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_pub_date' ) . '"';
		$out .= ', "genre": ' . '"' . wp_strip_all_tags( $this->get_the_rcno_taxonomy_terms( 'rcno_genre', false ) ) .'"';
		$out .= ', "publisher": '. '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_publisher' ) . '"';
		$out .= ', "workExample": ['; // Begin workExample
		$out .= '{'; // Begin First example
		$out .= '"@type": "Book"';
		$out .= ', "isbn": ' . '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_isbn' ) . '"';
		$out .= ', "bookEdition": ' . '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_pub_edition' ) . '"';
		$out .= ', "bookFormat": "http://schema.org/' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_pub_format' ) . '"';
		$out .= ', "numberOfPages": ' . (int) $this->get_the_rcno_book_meta( $review_id, 'rcno_book_page_count' );
		$out .= ', "potentialAction": {'; // Begin potentialAction
		$out .= '"@type": "ReadAction"';
		$out .= ', "target": {'; // Begin target
		$out .= '"@type": "EntryPoint"';
		$out .= ', "urlTemplate": "http://www.barnesandnoble.com/store/info/offer/0316769487?purchase=true"';
		$out .= ', "actionPlatform": ['; // Begin actionPlatform
		$out .= '"http://schema.org/DesktopWebPlatform"';
		$out .= ', "http://schema.org/IOSPlatform"';
		$out .= ', "http://schema.org/AndroidPlatform"';
		$out .= ']'; // End actionPlatform
		$out .= '}'; // End target
		$out .= '}'; // End potentialAction
		$out .= '}'; // End First example
		$out .= ']'; // End workExample
		$out .= '}';
		$out .= '</script>';

		return $out;
	}

	public function get_the_rcno_review_schema_data( $review_id ) {

		$out = '';
		$out .= '<script type="application/ld+json">';
		$out .= '{';

		$out .= '"@context": "http://schema.org"';
		$out .= ', "@type": "Review"';

		$out .= ', "author": {';
		$out .= '"@type": "Person"';
		$out .= ', "name": ' . '"' . get_the_author() . '"';
		$out .= ', "sameAs": "https://plus.google.com/114108465800532712602"'; // Social profile for review author.
		$out .= '}';

		$out .= ', "url": ' . '"' . get_post_permalink( $review_id ) . '"';
		$out .= ', "datePublished": ' . '"' . get_the_date( 'F j, Y', $review_id ) . '"';

		$out .= ', "publisher": {';
		$out .= '"@type": "Organization"';
		$out .= ', "name": ' . '"' . get_bloginfo( 'name' ) . '"';
		$out .= ', "sameAs": ' . '"' . get_bloginfo( 'url' ) .'"';
		$out .= '}';

		$out .= ', "description": "Great old fashioned steaks but the salads are sub par."'; // Review excerpt.
		$out .= ', "inLanguage": ' . '"' . get_bloginfo( 'language' ) . '"';

		$out .= ', "itemReviewed": {';
		$out .= '"@type":"Book"';
		$out .= ', "name": ' . '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_title' ) . '"';
		$out .= ', "isbn": ' . '"'. $this->get_the_rcno_book_meta( $review_id, 'rcno_book_isbn' ) . '"';

		$out .= ', "author": {';
		$out .= '"@type": "Person"';
		$out .= ', "name": ' . '"' . wp_strip_all_tags( $this->get_the_rcno_taxonomy_terms( 'rcno_author', false ) ) .'"';
		$out .= ', "sameAs": "https://plus.google.com/114108465800532712602"'; // Social profile for book author.
		$out .= '}';

		$out .= ', "datePublished": ' . '"' . $this->get_the_rcno_book_meta( $review_id, 'rcno_book_pub_date' ) . '"';

		$out .= '}';

		$out .= ', "reviewRating": {';
		$out .= '"@type":"Rating"';
		$out .= ', "worstRating": 1';
		$out .= ', "bestRating": 10';
		$out .= ', "ratingValue": ' . (float) $this->rcno_review_score;
		$out .= '}';


		$out .= '}';
		$out .= '</script>';

		return $out;
	}


	/**
	 * Check if the review is embedded into a post, page or another custom post type.
	 *
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
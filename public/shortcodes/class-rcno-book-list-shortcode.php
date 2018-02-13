<?php

/**
 * Creates the shortcodes used for book reviews.
 *
 * @link       https://wzymedia.com
 * @since      1.8.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 */

/**
 * Creates the shortcodes used for book reviews.
 *
 *
 * @since      1.8.0
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Book_List_Shortcode {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.8.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.8.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.8.0
	 * @access   public
	 * @var      Rcno_Template_Tags $template
	 */
	public $template;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since      1.8.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->template = new Rcno_Template_Tags( $this->plugin_name, $this->version );
	}

	/**
	 * Generates a list of books in a given taxonomy.
	 *
	 * @since 1.7.0
	 *
	 * @param int       $review_id      The post ID of the book review.
	 * @param string    $taxonomy       The taxonomy to fetch reviews for.
	 * @param bool      $number         Display the book series number.
	 * @param mixed     $header         The header title.
	 *
	 * @return string
	 */
	public function get_the_rcno_book_list( $review_id, $taxonomy, $number, $header ) {

		// If we are on not on a single review don't display this.
		if ( ! is_single()  ) {
			return false;
		}

		$out = '';
		$book_data = array();

		$tax = get_the_terms( $review_id, 'rcno_' . $taxonomy );

		if ( false === $tax || is_wp_error( $tax ) ) {
			$out .= '<div class="rcno-books-error">';
			$out .= '<p>';
			$out .= __('No entries found for the ', 'rcno-reviews');
			$out .= '<em>"' . $taxonomy . '"</em>';
			$out .= __( ' taxonomy.', 'rcno-reviews' );
			$out .= '</p>';
			$out .= '</div>';

			return $out;
		}

		$custom_args = array(
			'post_type' => 'rcno_review',
			'order'     => 'ASC',
			'tax_query' => array(
				array(
					'taxonomy' => $tax[0]->taxonomy,
					'field'    => 'slug',
					'terms'    => $tax[0]->slug,
				),
			),
			'posts_per_page' => 50,
		);

		$data = new WP_Query( $custom_args );

		if ( $data->have_posts() ) {
			while ( $data->have_posts() ) : $data->the_post();
				$book_data[]   = array(
					'ID'    => get_the_ID(),
					'title' => get_the_title(),
					'link'  => get_the_permalink(),
				);
			endwhile;
		}
		wp_reset_postdata();

		if ( ! empty( $book_data ) ) {

			// Set header to a false value to use the default string.
			$header = ! empty( $header ) ? $header : __( 'Books in this series ', 'rcno-reviews' ) . $taxonomy;

			$out .= '<div class="rcno-book-series-container">';
			$out .= '<h5>' . esc_attr( $header ) . '</h5>';
			$out .= '<div class="rcno-book-series-wrapper">';
			foreach ( $book_data as $_book_data ) {
				if ( $_book_data['ID'] !== $review_id ) {
					$out .= '<div class="rcno-book-series-book">';
					$out .= '<a href="' . esc_url( $_book_data['link'] ) . '">';
					$out .= $this->template->get_the_rcno_book_cover( $_book_data['ID'], 'rcno-book-cover-sm' );
					$out .= '</a>';
					if ( $number ) {
						$out .= $this->template->get_the_rcno_book_meta( $_book_data['ID'], 'rcno_book_series_number', 'span', false );
					}
					$out .= '</div>';
				}
			}
			$out .= '</div>';
			$out .= '</div>';

		}

		return $out;
	}

	/**
	 * Do the shortcode 'rcno-book-list' and render a list of all books
	 * in a custom taxonomy.
	 *
	 * @since 1.8.0
	 *
	 * @param   mixed $atts
	 * @return  string
	 */
	public function rcno_do_book_list_shortcode( $atts ) {

		$review_id = isset( $GLOBALS['review_id'] ) ? (int) $GLOBALS['review_id'] : get_the_ID();

		// Set default values for options not set explicitly.
		$atts = shortcode_atts( array(
			'id'        => $review_id,
			'taxonomy'  => 'series',
			'number'    => true,
			'header'    => __( 'Books in this series: ', 'rcno-reviews' )
		), $atts );

		// The actual rendering is done by a special function.
		$output = $this->get_the_rcno_book_list( $atts['id'], $atts['taxonomy'], $atts['number'], $atts['header'] );

		return do_shortcode( $output );
	}

}
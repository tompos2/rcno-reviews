<?php
/**
 *
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * The Settings definition of the plugin.
 *
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin/settings
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Reviews_Settings_Definition {

	public static $plugin_name = 'rcno-reviews';
	public static $template;

	public function __construct() {

	}

	/**
	 * [apply_tab_slug_filters description]
	 *
	 * @param  array $default_settings [description]
	 *
	 * @return array                   [description]
	 */
	private static function apply_tab_slug_filters( $default_settings ) {

		$extended_settings[] = array();
		$extended_tabs       = self::get_tabs();

		foreach ( $extended_tabs as $tab_slug => $tab_desc ) {

			$options = isset( $default_settings[ $tab_slug ] ) ? $default_settings[ $tab_slug ] : array();

			$extended_settings[ $tab_slug ] = apply_filters( 'rcno_reviews_settings_' . $tab_slug, $options );
		}

		return $extended_settings;
	}

	/**
	 * [get_default_tab_slug description]
	 *
	 * @return string
	 */
	public static function get_default_tab_slug() {
		return key( self::get_tabs() );
	}

	/**
	 * Retrieve settings tabs
	 *
	 * @since    1.0.0
	 * @return   array    $tabs    Settings tabs
	 */
	public static function get_tabs() {

		$tabs = array();
		$tabs[ 'general_tab' ]    = __( 'General', 'rcno-reviews' );
		$tabs[ 'taxonomy_tab' ]   = __( 'Taxonomies', 'rcno-reviews' );
		$tabs[ 'components_tab' ] = __( 'Components', 'rcno-reviews' );
		$tabs[ 'extras_tab' ]     = __( 'Extras', 'rcno-reviews' );
		$tabs[ 'templates_tab' ]  = __( 'Templates', 'rcno-reviews' );
		$tabs[ 'advanced_tab' ]   = __( 'Advanced', 'rcno-reviews' );

		return apply_filters( 'rcno_reviews_settings_tabs', $tabs );
	}


	/**
	 * 'Whitelisted' Rcno_Reviews settings, filters are provided for each settings
	 * section to allow extensions and other plugins to add their own settings
	 *
	 *
	 * @since    1.0.0
	 * @return    mixed    $value    Value saved / $default if key if not exist
	 */
	public static function get_settings() {

		require_once 'class-rcno-reviews-get-templates.php';
		self::$template = new Rcno_Template_Tags( RCNO_PLUGIN_NAME, RCNO_PLUGIN_VER );

		$settings[] = array();

		$settings = array(
			// Begin the General Tab.
			'general_tab'    => array(
				'general_tab_header_0'          => array(
					'name' => '<strong>' . __( 'General Settings', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_review_slug'              => array(
					'name' => __( 'Slug', 'rcno-reviews' ),
					'desc' => __( 'Book reviews will be available at:', 'rcno-reviews' ) .
					          ' <i>' . get_site_url() . '/' . '<b>slug</b>' . '/' . 'a-book-review' . '</i>',
					'std'  => 'review',
					'type' => 'text',
					'pattern' => '{2,}',
					'title' => __( 'Please only use only 1 lower-case word', 'rcno-reviews' ),
				),
				'rcno_review_slug_instructions' => array(
					'name' => __( '404s errors', 'rcno-reviews' ),
					'desc' => __( 'If you\'ve set up everything correctly here but now WordPress is giving you an 404 (not found) error,
					 try flushing your permalink settings. Visit Settings -> Permalinks and just save without changing anything.', 'rcno-reviews' ),
					'type' => 'instruction',
				),
				'rcno_reviews_on_homepage'      => array(
					'name' => __( 'Reviews on homepage?', 'rcno-reviews' ),
					'desc' => __( 'Should book reviews be shown on homepage?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_reviews_in_rss'           => array(
					'name' => __( 'Reviews in RSS?', 'rcno-reviews' ),
					'desc' => __( 'Should book reviews be shown in the RSS feed.?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_reviews_in_rest'          => array(
					'name' => __( 'Reviews in REST API?', 'rcno-reviews' ),
					'desc' => __( 'Enables basic support for reviews in the WordPress REST API.', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_reviews_archive'          => array(
					'name'    => __( 'Reviews archive page', 'rcno-reviews' ),
					'desc'    => __( 'Show full content or excerpt on archive pages.', 'rcno-reviews' ),
					'options' => array(
						'archive_display_full'    => __( 'The entire book review', 'rcno-reviews' ),
						'archive_display_excerpt' => __( 'Only excerpt of the review', 'rcno-reviews' ),
					),
					'type'    => 'select',
				),
				'spacer_0'                      => array(
					'name' => '',
					'type' => 'spacer',
				),

			),

			// Begin taxonomies tab.
			'taxonomy_tab'   => array(
				'taxonomy_tab_header_0'        => array(
					'name' => '<strong>' . __( 'Review Taxonomies', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_taxonomy_selection'      => array(
					'name' => __( 'Taxonomy Selection', 'rcno-reviews' ),
					'desc' => __( 'Create and delete book review taxonomies here.', 'rcno-reviews' ),
					'std'  => 'Author',
					'type' => 'text',
				),
				'spacer_1'                     => array(
					'name' => '',
					'type' => 'spacer',
				),
				'taxonomy_tab_header_1'        => array(
					'name' => '<strong>' . __( 'Builtin Taxonomies', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_enable_builtin_taxonomy' => array(
					'name' => __( 'Default WP Taxonomy', 'rcno-reviews' ),
					'desc' => __( 'Enable the builtin \'category\' and \'tags\' taxonomies.', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'spacer_2'                     => array(
					'name' => '',
					'type' => 'spacer',
				),

			),

			//Components tab.
			'components_tab' => array(
				'components_tab_header_0'               => array(
					'name' => '<strong>' . __( 'Book Review Components', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_show_isbn'                        => array(
					'name' => __( 'ISBN number', 'rcno-reviews' ),
					'desc' => __( 'Show the ISBN field for book reviews?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_isbn13'                      => array(
					'name' => __( 'ISBN13 number', 'rcno-reviews' ),
					'desc' => __( 'Show the ISBN13 field for book reviews?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_asin'                        => array(
					'name' => __( 'ASIN number', 'rcno-reviews' ),
					'desc' => __( 'Show the ASIN field for book reviews?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_gr_id'                       => array(
					'name' => __( 'Goodreads ID', 'rcno-reviews' ),
					'desc' => __( 'Show the Goodreads book ID field for book reviews?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_gr_url'                      => array(
					'name' => __( 'Book URL', 'rcno-reviews' ),
					'desc' => __( 'Show the book\'s URL field for book reviews?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_illustrator'                 => array(
					'name' => __( 'Illustrator', 'rcno-reviews' ),
					'desc' => __( 'Show the book\'s illustrator for reviews?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_pub_date'                    => array(
					'name' => __( 'Published Date', 'rcno-reviews' ),
					'desc' => __( 'Show the book\'s published date for reviews?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_pub_format'                  => array(
					'name' => __( 'Published Format', 'rcno-reviews' ),
					'desc' => __( 'Show the book\'s published format for reviews?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_pub_edition'                 => array(
					'name' => __( 'Published Edition', 'rcno-reviews' ),
					'desc' => __( 'Show the book\'s published edition for reviews?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_series_number'                  => array(
					'name' => __( 'Series Number', 'rcno-reviews' ),
					'desc' => __( 'Show the book\'s number in the series for reviews?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_page_count'                  => array(
					'name' => __( 'Page Count', 'rcno-reviews' ),
					'desc' => __( 'Show the book\'s page count for reviews?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_gr_rating'                   => array(
					'name' => __( 'Goodreads Rating', 'rcno-reviews' ),
					'desc' => __( 'Show the book\'s Goodreads rating for reviews?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'spacer2'                               => array(
					'name' => '',
					'type' => 'spacer',
				),

				// Purchase Links.
				'components_tab_header_3'               => array(
					'name' => '<strong>' . __( 'Book Purchase Links', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_enable_purchase_links'            => array(
					'name' => __( 'Enable Purchase Links', 'rcno-reviews' ),
					'desc' => __( 'Enable the use of book purchase links', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_store_purchase_links_label'       => array(
					'name' => __( 'Links Label', 'rcno-reviews' ),
					'desc' => __( 'Enter the label shown before purchase links.', 'rcno-reviews' ),
					'std'  => 'Purchase on: ',
					'type' => 'text',
				),
				'rcno_store_purchase_links'             => array(
					'name' => __( 'Store/Shop', 'rcno-reviews' ),
					'desc' => __( 'Enter the name of stores to purchase books from.', 'rcno-reviews' ),
					'std'  => 'Amazon,Barnes & Noble,Kobo',
					'type' => 'text',
				),
				'rcno_store_purchase_link_text_color'   => array(
					'name' => __( 'Text Color', 'rcno-reviews' ),
					'desc' => __( 'Text color for the purchase button.', 'rcno-reviews' ),
					'type' => 'color',
				),
				'rcno_store_purchase_link_background'   => array(
					'name' => __( 'Background Color', 'rcno-reviews' ),
					'desc' => __( 'Background color for the purchase button.', 'rcno-reviews' ),
					'type' => 'color',
				),
				'spacer'                                => array(
					'name' => '',
					'type' => 'spacer',
				),

				// 5 Star Rating box.
				'components_tab_header_4'               => array(
					'name' => '<strong>' . __( 'Book 5 Star Rating Box', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_enable_star_rating_box'           => array(
					'name' => __( '5 Star Rating', 'rcno-reviews' ),
					'desc' => __( 'Enable the 5 star rating box?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_star_rating_color'                => array(
					'name' => __( 'Star Rating Color', 'rcno-reviews' ),
					'desc' => __( 'The color of the 5 star rating.', 'rcno-reviews' ),
					'type' => 'color',
				),
				'rcno_star_background_color'            => array(
					'name' => __( 'Star Background Color', 'rcno-reviews' ),
					'desc' => __( 'Background color for the 5 star rating. (Delete existing value for transparent background)',
						'rcno-reviews' ),
					'type' => 'color',
				),
				'spacer5'                               => array(
					'name' => '',
					'type' => 'spacer',
				),


				// Review score box.
				'components_tab_header_2'               => array(
					'name' => '<strong>' . __( 'Book Review Score Box', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_show_review_score_box'            => array(
					'name' => __( 'Review Score Box', 'rcno-reviews' ),
					'desc' => __( 'Enable the review score box?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_review_score_box_background' => array(
					'name' => __( 'Background Color', 'rcno-reviews' ),
					'desc' => __( 'Background Color for the review score box?', 'rcno-reviews' ),
					'type' => 'color',
				),
				'rcno_show_review_score_box_accent'     => array(
					'name' => __( 'Accent Color 1', 'rcno-reviews' ),
					'desc' => __( 'The first accent color for the review score box?', 'rcno-reviews' ),
					'type' => 'color',
				),
				'rcno_show_review_score_box_accent_2'   => array(
					'name' => __( 'Accent Color 2', 'rcno-reviews' ),
					'desc' => __( 'The second accent color for the review score box?', 'rcno-reviews' ),
					'type' => 'color',
				),
				'spacer-1'                              => array(
					'name' => '',
					'type' => 'spacer',
				),


			),

			// Extras tab.
			'extras_tab'     => array(

				// Comment 5 star ratings.
				'extras_tab_header_0'             => array(
					'name' => '<strong>' . __( 'Reader Comment Rating', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_enable_comment_ratings'     => array(
					'name' => __( 'Comment Ratings', 'rcno-reviews' ),
					'desc' => __( 'Enable reader submitted ratings in the comment form', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_comment_rating_label'       => array(
					'name' => __( 'Rating Label', 'rcno-reviews' ),
					'desc' => __( 'Enter the label before the comment 5 star rating field', 'rcno-reviews' ),
					'std'  => __( 'Rate this review: ', 'rcno-reviews' ),
					'type' => 'text',
				),
				'rcno_comment_rating_star_color'  => array(
					'name' => __( 'Comment Star Color', 'rcno-reviews' ),
					'desc' => __( 'Background color for the reader comment 5 star rating', 'rcno-reviews' ),
					'type' => 'color',
				),
				'spacer-6'                        => array(
					'name' => '',
					'type' => 'spacer',
				),

				// Custom widgets.
				'extras_tab_header_1'             => array(
					'name' => '<strong>' . __( 'Book Review Widgets', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_show_book_slider_widget'    => array(
					'name' => __( 'Book Slider', 'rcno-reviews' ),
					'desc' => __( 'Use the Rcno Book Slider widget?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_book_grid_widget'      => array(
					'name' => __( 'Book Grid', 'rcno-reviews' ),
					'desc' => __( 'Use the Rcno Book Grid widget?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_recent_reviews_widget' => array(
					'name' => __( 'Recent Reviews', 'rcno-reviews' ),
					'desc' => __( 'Use the Rcno Recent Reviews widget?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_tag_cloud_widget'      => array(
					'name' => __( 'Tag Cloud', 'rcno-reviews' ),
					'desc' => __( 'Use the Rcno Tag Cloud widget?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_taxonomy_list_widget'  => array(
					'name' => __( 'Taxonomy List', 'rcno-reviews' ),
					'desc' => __( 'Use the Rcno Taxonomy List widget?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_currently_reading_widget'  => array(
					'name' => __( 'Currently Reading', 'rcno-reviews' ),
					'desc' => __( 'Use the Rcno Currently Reading widget?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_review_calendar_widget'  => array(
					'name' => __( 'Review Calendar', 'rcno-reviews' ),
					'desc' => __( 'Use the Rcno Review Calendar widget?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'spacer-7'                        => array(
					'name' => '',
					'type' => 'spacer',
				),
				'extras_tab_header_2'             => array(
					'name' => '<strong>' . __( 'Index Pages', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_reviews_index_headers'      => array(
					'name' => __( 'Navigation headers', 'rcno-reviews' ),
					'desc' => __( 'Display navigation header on index page?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_show_book_covers_index'      => array(
					'name' => __( 'Book covers', 'rcno-reviews' ),
					'desc' => __( 'Book covers on review index page?', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_reviews_ignore_articles'          => array(
					'name' => __( 'Ignore articles', 'rcno-reviews' ),
					'desc' => __( 'Ignore articles when sorting titles. (e.g. "The", "A", "An")', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_reviews_ignored_articles_list'          => array(
					'name' => __( 'Article List', 'rcno-reviews' ),
					'desc' => __( 'The list of articles to ignore in your language', 'rcno-reviews' ),
					'type' => 'text',
					'std'  => 'The,A,An'
				),
				'rcno_reviews_sort_names'          => array(
					'name'    => __( 'Sort author names', 'rcno-reviews' ),
					'desc'    => __( 'Sort names by first-name or last-name', 'rcno-reviews' ),
					'options' => array(
						'last_name_first_name' => __( 'LastName, FirstName', 'rcno-reviews' ),
						'first_name_last_name' => __( 'FirstName LastName', 'rcno-reviews' ),
					),
					'type'    => 'select',
				),
				'spacer-7-1'                        => array(
					'name' => '',
					'type' => 'spacer',
				),

			),

			// Templates tab.
			'templates_tab'  => array(

				'templates_tab_header_0'  => array(
					'name' => '<strong>' . __( 'Review Templates', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_review_template'    => array(
					'name'    => __( 'Book review template', 'rcno-reviews' ),
					'desc'    => __( 'Select how you want your book reviews to look.', 'rcno-reviews' ),
					'options' => layout_list(),
					'type'    => 'template',
				),
				'rcno_default_cover'      => array(
					'name' => __( 'Default Book Cover', 'rcno-reviews' ),
					'desc' => __( 'The default image to use when a book cover isn\'t uploaded', 'rcno-reviews' ),
					'type' => 'upload',
				),
				'rcno_excerpt_read_more'  => array(
					'name' => __( 'Read more text', 'rcno-reviews' ),
					'desc' => __( 'The review excerpt \'Read more\' tag.', 'rcno-reviews' ),
					'std'  => 'Read more',
					'type' => 'text',
				),
				'rcno_excerpt_word_count' => array(
					'name' => __( 'Review excerpt word count', 'rcno-reviews' ),
					'desc' => __( 'Max: 500, Min: 20', 'rcno-reviews' ),
					'max'  => 500,
					'min'  => 20,
					'step' => 1,
					'type' => 'number',
				),
				'rcno_reviews_embedded_title_links'          => array(
					'name' => __( 'Embedded Review Links', 'rcno-reviews' ),
					'desc' => __( 'Enable clickable title links on embedded book reviews.', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_book_details_meta'  => array(
					'name' => __( 'Book Detail Items', 'rcno-reviews' ),
					'desc' => __( 'Book details that should appear in book reviews. You also rearrange their order here', 'rcno-reviews' ),
					'std'  => implode( ',', self::$template->get_rcno_book_meta_keys( 'keys', 8 ) ),
					'type' => 'text',
				),
				'spacer-8'                => array(
					'name' => '',
					'type' => 'spacer',
				),
				 // Custom stylesheet.
				'templates_tab_header_1'  => array(
					'name' => '<strong>' . __( 'Reviews Custom Styling', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_custom_styling'                   => array(
					'name' => __( 'Custom styles', 'rcno-reviews' ),
					'desc' => __( 'Add your custom CSS here to fine-tune the look of your book reviews', 'rcno-reviews' ),
					'type' => 'cssbox',
				),
				'spacer-9'                => array(
					'name' => '',
					'type' => 'spacer',
				),

			),
			'advanced_tab'   => array(

				'advanced_tab_header_0'  => array(
					'name' => '<strong>' . __( 'Book APIs', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_external_book_api' => array(
					'name'    => __( 'External Book API', 'rcno-reviews' ),
					'desc'    => __( 'Select which 3rd party API is used to fetch book data.' . '<br />' . 'The corresponding option must also be enabled below.', 'rcno-reviews' ),
					'options' => array(
						'no-3rd-party' => __( 'None', 'rcno-reviews' ),
						'google-books' => __( 'Google Books® API', 'rcno-reviews' ),
						'good-reads'   => __( 'Goodreads® API', 'rcno-reviews' ),
					),
					'type'    => 'select',
				),
				'spacer-8'               => array(
					'name' => '',
					'type' => 'spacer',
				),

				'advanced_tab_header_1'   => array(
					'name' => '<strong>' . __( 'Google Books® Settings', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_enable_googlebooks' => array(
					'name' => __( 'Enable Google Books®', 'rcno-reviews' ),
					'desc' => __( 'Enable the external Google Books® API access.', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_googlebooks_key'    => array(
					'name' => __( 'Google Books® API Key', 'rcno-reviews' ),
					'desc' => __( 'Available at: ', 'rcno-reviews' ) . 'https://console.developers.google.com/apis/credentials',
					'type' => 'password',
				),
				'spacer-9'                => array(
					'name' => '',
					'type' => 'spacer',
				),

				'advanced_tab_header_2' => array(
					'name' => '<strong>' . __( 'Goodreads® Settings', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_enable_goodreads' => array(
					'name' => __( 'Enable Goodreads®', 'rcno-reviews' ),
					'desc' => __( 'Enable the external Goodreads® API access.', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_goodreads_key'    => array(
					'name' => __( 'Goodreads® API Key', 'rcno-reviews' ),
					'desc' => __( 'Available at: ', 'rcno-reviews' ) . 'https://www.goodreads.com/api/keys',
					'type' => 'password',
				),
				'rcno_goodreads_secret' => array(
					'name' => __( 'Goodreads® API Secret', 'rcno-reviews' ),
					'desc' => __( 'Available at: ', 'rcno-reviews' ) . 'https://www.goodreads.com/api/keys',
					'type' => 'password',
				),
				'spacer-10'             => array(
					'name' => '',
					'type' => 'spacer',
				),

				'advanced_tab_header_5' => array(
					'name' => '<strong>' . __( 'JSON-LD Schemas', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_disable_review_schema' => array(
					'name' => __( 'Disable Review Schema', 'rcno-reviews' ),
					'desc' => __( 'Disable the output of the review JSON-LD schema markup', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'rcno_disable_book_schema' => array(
					'name' => __( 'Disable Book Schema', 'rcno-reviews' ),
					'desc' => __( 'Disable the output of the book JSON-LD schema markup', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'spacer-13'             => array(
					'name' => '',
					'type' => 'spacer',
				),

				'advanced_tab_header_3' => array(
					'name' => '<strong>' . __( 'Uncountable Words', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_no_pluralization'      => array(
					'name' => __( 'Word List', 'rcno-reviews' ),
					'desc' => __( 'Create a list of words that have no plural or singular form e.g. "sheep, fish"', 'rcno-reviews' ),
					'type' => 'text',
				),
				'rcno_disable_pluralization' => array(
					'name' => __( 'Disable Pluralization', 'rcno-reviews' ),
					'desc' => __( 'Disable the automatic pluralization of words used in the settings', 'rcno-reviews' ),
					'type' => 'checkbox',
				),
				'spacer-11'             => array(
					'name' => '',
					'type' => 'spacer',
				),

				'advanced_tab_header_4' => array(
					'name' => '<strong>' . __( 'Import/Export Settings', 'rcno-reviews' ) . '</strong>',
					'type' => 'header',
				),
				'rcno_settings_import'      => array(
					'name' => __( 'Import Settings', 'rcno-reviews' ),
					'desc' => __( 'Import your previously saved plugin settings.', 'rcno-reviews' ),
					'type' => 'file',
					'accept' => '.json',
				),
				'rcno_settings_export'      => array(
					'name' => __( 'Export Settings', 'rcno-reviews' ),
					'desc' => __( 'Save the plugin settings to a file on your computer.', 'rcno-reviews' ),
					'type' => 'download',
				),
				'spacer-12'             => array(
					'name' => '',
					'type' => 'spacer',
				),

			),


		);

		$custom_taxonomies = new Rcno_Reviews_Admin( RCNO_PLUGIN_NAME, RCNO_PLUGIN_VER );
		$_custom_taxonomies = $custom_taxonomies->rcno_get_custom_taxonomies();
		foreach (  $_custom_taxonomies as $tax ) {
			foreach ( self::taxonomy_options( $tax['tax_settings'] ) as $key => $value ) {
				$settings['taxonomy_tab'][ strtolower( $key ) ] = $value;
			}
		}

		return self::apply_tab_slug_filters( $settings );
	}

	public static function taxonomy_options( $tax ) {

		$opts = array(
			'rcno_' . $tax['settings_key'] . '_header'       => array(
				'name' => '<strong>' . ucfirst( $tax['label'] ) . '</strong>',
				'type' => 'header',
			),
			'rcno_' . $tax['slug'] . '_label'         => array(
				'name' => __( 'Label', 'rcno-reviews' ),
				'desc' => __( 'Place the label of the ' . $tax['label'] . ' taxonomy here, as it should display on the frontend.', 'rcno-reviews' ),
				'std'  =>  ucfirst( $tax['label'] ),
				'type' => 'text',
			),
			'rcno_' . $tax['settings_key'] . '_slug'         => array(
				'name' => __( 'Slug', 'rcno-reviews' ),
				'desc' => __( 'Place the slug of the ' . $tax['slug'] . ' taxonomy here.', 'rcno-reviews' ),
				'std'  => strtolower( $tax['slug'] ),
				'type' => 'text',
				'size' => '20'
			),
			'rcno_' . $tax['settings_key'] . '_hierarchical' => array(
				'name' => __( 'Hierarchical', 'rcno-reviews' ),
				'desc' => __( 'Is this custom taxonomy hierarchical?', 'rcno-reviews' ),
				'type' => 'checkbox',
			),
			'rcno_' . $tax['settings_key'] . '_show'         => array(
				'name' => __( 'Show in table', 'rcno-reviews' ),
				'desc' => __( 'Show this custom taxonomy on the admin table', 'rcno-reviews' ),
				'type' => 'checkbox',
			),
			'spacer' . $tax['settings_key']                 => array(
				'name' => '',
				'type' => 'spacer',
			),
		);

		return $opts;
	}

}

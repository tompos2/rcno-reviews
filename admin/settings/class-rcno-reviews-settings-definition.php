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

	public function __construct() {

	}

	/**
	 * [apply_tab_slug_filters description]
	 *
	 * @param  array $default_settings [description]
	 *
	 * @return array                   [description]
	 */
	static private function apply_tab_slug_filters( $default_settings ) {

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
	 * @return [type] [description]
	 */
	static public function get_default_tab_slug() {
		return key( self::get_tabs() );
	}

	/**
	 * Retrieve settings tabs
	 *
	 * @since    1.0.0
	 * @return   array    $tabs    Settings tabs
	 */
	static public function get_tabs() {

		$tabs = array();
		//$tabs['default_tab']    = __( 'Default Tab', self::$plugin_name );
		//$tabs['second_tab']     = __( 'Second Tab', self::$plugin_name );
		$tabs['general_tab']    = __( 'General', self::$plugin_name );
		$tabs['taxonomy_tab']   = __( 'Taxonomies', self::$plugin_name );
		$tabs['components_tab'] = __( 'Components', self::$plugin_name );
		$tabs['extras_tab']     = __( 'Extras', self::$plugin_name );
		$tabs['templates_tab']  = __( 'Templates', self::$plugin_name );
		$tabs['advanced_tab']   = __( 'Advanced', self::$plugin_name );

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
	static public function get_settings() {

		require_once 'class-rcno-reviews-get-templates.php';

		$settings[] = array();

		$settings = array(
			'default_tab'    => array(
				'default_tab_settings'       => array(
					'name' => '<strong>' . __( 'Header', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),
				'missing_callback'           => array(
					'name' => '<strong>' . __( 'Missing Callback', self::$plugin_name ) . '</strong>',
					'type' => 'non-exist'
				),
				'checkbox'                   => array(
					'name' => __( 'Checkbox', self::$plugin_name ),
					'desc' => __( 'Checkbox', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'multicheck'                 => array(
					'name'    => __( 'Multicheck', self::$plugin_name ),
					'desc'    => __( 'Multicheck with 3 options', self::$plugin_name ),
					'options' => array(
						'wp-human'   => __( "I read the <a href='https://wphuman.com/blog/'>WP Human Blog</a>", self::$plugin_name ),
						'tang-rufus' => __( "<a href='http://tangrufus.com/'>Tang Rufus' Blog</a> looks great", self::$plugin_name ),
						'Filter'     => __( 'You can apply filters on this option!', self::$plugin_name )
					),
					'type'    => 'multicheck'
				),
				'multicheck_without_options' => array(
					'name' => __( 'Multicheck', self::$plugin_name ),
					'desc' => __( 'Multicheck without options', self::$plugin_name ),
					'type' => 'multicheck'
				),
				'radio'                      => array(
					'name'    => __( 'Radio', self::$plugin_name ),
					'desc'    => __( 'Radio with 3 options', self::$plugin_name ),
					'options' => array(
						'wp-human'   => __( "I read the <a href='https://wphuman.com/blog/'>WP Human Blog</a>", self::$plugin_name ),
						'tang-rufus' => __( "<a href='http://tangrufus.com/'>Tang Rufus' Blog</a> looks great", self::$plugin_name ),
						'Filter'     => __( 'You can apply filters on this option!', self::$plugin_name )
					),
					'type'    => 'radio'
				),
				'radio_without_options'      => array(
					'name' => __( 'Radio', self::$plugin_name ),
					'desc' => __( 'Radio without options', self::$plugin_name ),
					'type' => 'radio'
				),
				'text'                       => array(
					'name' => __( 'Text', self::$plugin_name ),
					'desc' => __( 'Text', self::$plugin_name ),
					'type' => 'text'
				),
				'text_with_std'              => array(
					'name' => __( 'Text with std', self::$plugin_name ),
					'desc' => __( 'Text with std', self::$plugin_name ),
					'std'  => __( 'std will be saved!', self::$plugin_name ),
					'type' => 'text'
				),
				'email'                      => array(
					'name' => __( 'Email', self::$plugin_name ),
					'desc' => __( 'Email', self::$plugin_name ),
					'type' => 'email'
				),
				'url'                        => array(
					'name' => __( 'URL', self::$plugin_name ),
					'desc' => __( 'By default, only http & https are allowed', self::$plugin_name ),
					'type' => 'url'
				),
				'password'                   => array(
					'name' => __( 'Password', self::$plugin_name ),
					'desc' => __( 'Password', self::$plugin_name ),
					'type' => 'password'
				),
				'number'                     => array(
					'name' => __( 'Number', self::$plugin_name ),
					'desc' => __( 'Number', self::$plugin_name ),
					'type' => 'number'
				),
				'number_with_attributes'     => array(
					'name' => __( 'Number', self::$plugin_name ),
					'desc' => __( 'Max: 1000, Min: 20, Step: 30', self::$plugin_name ),
					'max'  => 1000,
					'min'  => 20,
					'step' => 30,
					'type' => 'number'
				),
				'textarea'                   => array(
					'name' => __( 'Textarea', self::$plugin_name ),
					'desc' => __( 'Textarea', self::$plugin_name ),
					'type' => 'textarea'
				),
				'textarea_with_std'          => array(
					'name' => __( 'Textarea with std', self::$plugin_name ),
					'desc' => __( 'Textarea with std', self::$plugin_name ),
					'std'  => __( 'std will be saved!', self::$plugin_name ),
					'type' => 'textarea'
				),
				'select'                     => array(
					'name'    => __( 'Select', self::$plugin_name ),
					'desc'    => __( 'Select with 3 options', self::$plugin_name ),
					'options' => array(
						'wp-human'   => __( "I read the <a href='https://wphuman.com/blog/'>WP Human Blog</a>", self::$plugin_name ),
						'tang-rufus' => __( "<a href='http://tangrufus.com/'>Tang Rufus' Blog</a> looks great", self::$plugin_name ),
						'Filter'     => __( 'You can apply filters on this option!', self::$plugin_name )
					),
					'type'    => 'select'
				),
				'rich_editor'                => array(
					'name' => __( 'Rich Editor', self::$plugin_name ),
					'desc' => __( 'Rich Editor save as HTML markups', self::$plugin_name ),
					'type' => 'rich_editor'
				),
				'upload'                     => array(
					'name' => __( 'Upload', self::$plugin_name ),
					'desc' => __( 'Upload', self::$plugin_name ),
					'type' => 'upload'
				),
			),
			'second_tab'     => array(
				'extend_me' => array(
					'name' => 'Extend me',
					'desc' => __( 'You can extend me via hooks and filters.', self::$plugin_name ),
					'type' => 'text'
				)
			),

			// Begin the General Tab.
			'general_tab'    => array(
				'general_tab_header'            => array(
					'name' => '<strong>' . __( 'General Settings', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),
				'rcno_review_slug'              => array(
					'name' => __( 'Slug', self::$plugin_name ),
					'desc' => __( 'Book reviews will be available at:', self::$plugin_name ) .
					          ' <i>' . get_site_url() . '/' . '<b>slug</b>' . '/' . 'a-book-review' . '</i>',
					'std'  => 'review',
					'type' => 'text'
				),
				'rcno_review_slug_instructions' => array(
					'name' => __( '404s errors', self::$plugin_name ),
					'desc' => __( 'You\'ve set up everything correctly here but now WordPress is giving you an 404 (not found) error?
					 Try flushing your permalink settings. Visit Settings -> Permalinks and just save without changing anything.', self::$plugin_name ),
					'type' => 'instruction'
				),
				'rcno_reviews_on_homepage'      => array(
					'name' => __( 'Reviews on homepage?', self::$plugin_name ),
					'desc' => __( 'Should book reviews be shown on homepage?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_reviews_in_rss'           => array(
					'name' => __( 'Reviews in RSS?', self::$plugin_name ),
					'desc' => __( 'Should book reviews be shown in the RSS feed.?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_reviews_in_rest'          => array(
					'name' => __( 'Reviews in REST API?', self::$plugin_name ),
					'desc' => __( 'Enables basic support for reviews in the WordPress REST API.', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_reviews_archive'          => array(
					'name'    => __( 'Reviews archive page', self::$plugin_name ),
					'desc'    => __( 'Show full content or excerpt on archive pages.', self::$plugin_name ),
					'options' => array(
						'archive_display_full'    => __( 'The entire book review', self::$plugin_name ),
						'archive_display_excerpt' => __( 'Only excerpt of the review', self::$plugin_name )
					),
					'type'    => 'select'
				),

			),

			// Begin taxonomies tab.
			'taxonomy_tab'   => array(
				'taxonomy_tab_header'            => array(
					'name' => '<strong>' . __( 'Review Taxonomies', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),
				'rcno_taxonomy_selection_header' => array(
					'name' => '<h2 class="section-heading">' . __( 'Taxonomies', self::$plugin_name ) . '</h2>',
					'type' => 'header'
				),
				'rcno_taxonomy_selection'        => array(
					'name' => __( 'Taxonomy Selection', self::$plugin_name ),
					'desc' => __( 'Create and delete book review taxonomies here.', self::$plugin_name ),
					'std'  => 'Author',
					'type' => 'text'
				),

				'rcno_builtin_taxonomy_header' => array(
					'name' => '<h2 class="section-heading">' . __( 'Builtin Taxonomies', self::$plugin_name ) . '</h2>',
					'type' => 'header'
				),
				'rcno_enable_builtin_taxonomy' => array(
					'name' => __( 'Default WP Taxonomy', self::$plugin_name ),
					'desc' => __( 'Enable the builtin \'category\' and \'tags\' taxonomies.', self::$plugin_name ),
					'type' => 'checkbox'
				),

			),

			//Components tab.
			'components_tab' => array(
				'components_tab_header'                 => array(
					'name' => '<strong>' . __( 'Book Review Components', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),
				'rcno_show_isbn'                        => array(
					'name' => __( 'ISBN number', self::$plugin_name ),
					'desc' => __( 'Show the ISBN field for book reviews?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_isbn13'                      => array(
					'name' => __( 'ISBN13 number', self::$plugin_name ),
					'desc' => __( 'Show the ISBN13 field for book reviews?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_asin'                        => array(
					'name' => __( 'ASIN number', self::$plugin_name ),
					'desc' => __( 'Show the ASIN field for book reviews?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_gr_id'                       => array(
					'name' => __( 'Goodreads ID', self::$plugin_name ),
					'desc' => __( 'Show the Goodreads book ID field for book reviews?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_gr_url'                      => array(
					'name' => __( 'Goodreads URL', self::$plugin_name ),
					'desc' => __( 'Show the Goodreads book URL field for book reviews?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_publisher'                   => array(
					'name' => __( 'Publisher', self::$plugin_name ),
					'desc' => __( 'Show the book publisher for reviews?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_pub_date'                    => array(
					'name' => __( 'Published Date', self::$plugin_name ),
					'desc' => __( 'Show the book\'s published date for reviews?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_pub_format'                  => array(
					'name' => __( 'Published Format', self::$plugin_name ),
					'desc' => __( 'Show the book\'s published format for reviews?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_pub_edition'                 => array(
					'name' => __( 'Published Edition', self::$plugin_name ),
					'desc' => __( 'Show the book\'s published edition for reviews?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_page_count'                  => array(
					'name' => __( 'Page Count', self::$plugin_name ),
					'desc' => __( 'Show the book\'s page count for reviews?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_gr_rating'                   => array(
					'name' => __( 'Goodreads Rating', self::$plugin_name ),
					'desc' => __( 'Show the book\'s Goodreads rating for reviews?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'spacer2'               => array(
					'name' => '',
					'type' => 'spacer'
				),

				// Purchase Links.
				'components_tab_header_3'               => array(
					'name' => '<strong>' . __( 'Book Purchase Links', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),
				'rcno_enable_purchase_links'            => array(
					'name' => __( 'Enable Purchase Links', self::$plugin_name ),
					'desc' => __( 'Enable the use of book purchase links', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_store_purchase_links_label'             => array(
					'name' => __( 'Links Label', self::$plugin_name ),
					'desc' => __( 'Enter the name of stores to purchase books from.', self::$plugin_name ),
					'std'  => 'Purchase on: ',
					'type' => 'text'
				),
				'rcno_store_purchase_links'             => array(
					'name' => __( 'Store/Shop', self::$plugin_name ),
					'desc' => __( 'Enter the label shown before purchase links.', self::$plugin_name ),
					'std'  => 'Amazon,Barnes & Noble,Kobo',
					'type' => 'text'
				),
				'rcno_store_purchase_link_background'   => array(
					'name' => __( 'Background Color', self::$plugin_name ),
					'desc' => __( 'Background Color for the purchase button.', self::$plugin_name ),
					'type' => 'color'
				),
				'spacer'               => array(
					'name' => '',
					'type' => 'spacer'
				),

				// 5 Star Rating box.
				'components_tab_header_4'               => array(
					'name' => '<strong>' . __( 'Book 5 Star Rating Box', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),
				'rcno_enable_star_rating_box'            => array(
					'name' => __( '5 Star Rating', self::$plugin_name ),
					'desc' => __( 'Enable the 5 star rating box?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_star_rating_color' => array(
					'name' => __( 'Star Rating Color', self::$plugin_name ),
					'desc' => __( 'Background color for the 5 star rating?', self::$plugin_name ),
					'type' => 'color'
				),
				'spacer5'               => array(
					'name' => '',
					'type' => 'spacer'
				),


				// Review score box.
				'components_tab_header_2'               => array(
					'name' => '<strong>' . __( 'Book Review Score Box', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),
				'rcno_show_review_score_box'            => array(
					'name' => __( 'Review Score Box', self::$plugin_name ),
					'desc' => __( 'Enable the review score box?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_review_score_box_background' => array(
					'name' => __( 'Background Color', self::$plugin_name ),
					'desc' => __( 'Background Color for the review score box?', self::$plugin_name ),
					'type' => 'color'
				),
				'rcno_show_review_score_box_accent'     => array(
					'name' => __( 'Accent Color 1', self::$plugin_name ),
					'desc' => __( 'The first accent color for the review score box?', self::$plugin_name ),
					'type' => 'color'
				),
				'rcno_show_review_score_box_accent_2'   => array(
					'name' => __( 'Accent Color 2', self::$plugin_name ),
					'desc' => __( 'The second accent color for the review score box?', self::$plugin_name ),
					'type' => 'color'
				),
				'spacer-1'               => array(
					'name' => '',
					'type' => 'spacer'
				),


			),

			// Extras tab.
			'extras_tab' => array(

				// Comment 5 star ratings.
				'extras_tab_header_0'            => array(
					'name' => '<strong>' . __( 'Reader Comment Rating', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),
				'rcno_enable_comment_ratings'    => array(
					'name' => __( 'Comment Ratings', self::$plugin_name ),
					'desc' => __( 'Enable reader submitted ratings in the comment form', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_comment_rating_label'             => array(
					'name' => __( 'Rating Label', self::$plugin_name ),
					'desc' => __( 'Enter the label before the comment 5 star rating field', self::$plugin_name ),
					'std'  => __( 'Rate this review: ', self::$plugin_name ),
					'type' => 'text'
				),
				'rcno_comment_rating_star_color' => array(
					'name' => __( 'Comment Star Color', self::$plugin_name ),
					'desc' => __( 'Background color for the reader comment 5 star rating', self::$plugin_name ),
					'type' => 'color'
				),
				'spacer-6'                       => array(
					'name' => '',
					'type' => 'spacer'
				),

				// Custom widgets.
				'extras_tab_header_1'             => array(
					'name' => '<strong>' . __( 'Book Review Widgets', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),
				'rcno_show_book_slider_widget'    => array(
					'name' => __( 'Book Slider', self::$plugin_name ),
					'desc' => __( 'Use the Rcno Book Slider widget?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_recent_reviews_widget' => array(
					'name' => __( 'Recent Reviews', self::$plugin_name ),
					'desc' => __( 'Use the Rcno Recent Reviews widget?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_tag_cloud_widget'      => array(
					'name' => __( 'Tag Cloud', self::$plugin_name ),
					'desc' => __( 'Use the Rcno Tag Cloud widget?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_show_taxonomy_list_widget'  => array(
					'name' => __( 'Taxonomy List', self::$plugin_name ),
					'desc' => __( 'Use the Rcno Taxonomy List widget?', self::$plugin_name ),
					'type' => 'checkbox'
				),

			),

			// Templates tab.
			'templates_tab'  => array(

				'rcno_review_template'    => array(
					'name'    => __( 'Book review template', self::$plugin_name ),
					'desc'    => __( 'Select how you want your book reviews to look.', self::$plugin_name ),
					'options' => layout_list(),
					'type'    => 'template'
				),
				'rcno_default_cover'      => array(
					'name' => __( 'Default Book Cover', self::$plugin_name ),
					'desc' => __( 'The default image to use when a book cover isn\'t upload', self::$plugin_name ),
					'type' => 'upload'
				),
				'rcno_excerpt_read_more'  => array(
					'name' => __( 'Read more text', self::$plugin_name ),
					'desc' => __( 'The review excerpt \'Read more\' tag.', self::$plugin_name ),
					'std'  => 'Read more',
					'type' => 'text'
				),
				'rcno_excerpt_word_count' => array(
					'name' => __( 'Review excerpt word count', self::$plugin_name ),
					'desc' => __( 'Max: 500, Min: 20', self::$plugin_name ),
					'max'  => 500,
					'min'  => 20,
					'step' => 1,
					'type' => 'number'
				),

			),
			'advanced_tab'   => array(

				'rcno_external_book_api' => array(
					'name'    => __( 'External Book API', self::$plugin_name ),
					'desc'    => __( 'Select which 3rd party API is used to fetch book data.' . '<br />' . 'The corresponding option must also be enabled below.', self::$plugin_name ),
					'options' => array(
						'no-3rd-party' => __( 'None', self::$plugin_name ),
						'google-books' => __( 'Google Books® API', self::$plugin_name ),
						'good-reads'   => __( 'Goodreads® API', self::$plugin_name ),
					),
					'type'    => 'select'
				),

				'rcno_googlebooks_settings' => array(
					'name' => '<strong>' . __( 'Google Books® Settings', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),
				'rcno_enable_googlebooks'   => array(
					'name' => __( 'Enable Google Books®', self::$plugin_name ),
					'desc' => __( 'Enable the external Google Books® API access.', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_googlebooks_key'      => array(
					'name' => __( 'Google Books® API Key', self::$plugin_name ),
					'desc' => __( 'Available at: ', self::$plugin_name ) . 'https://www.goodreads.com/api/keys',
					'type' => 'password'
				),
				'rcno_goodreads_settings'   => array(
					'name' => '<strong>' . __( 'Goodreads® Settings', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),
				'rcno_enable_goodreads'     => array(
					'name' => __( 'Enable Goodreads®', self::$plugin_name ),
					'desc' => __( 'Enable the external Goodreads® API access.', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_goodreads_key'        => array(
					'name' => __( 'Goodreads® API Key', self::$plugin_name ),
					'desc' => __( 'Available at: ', self::$plugin_name ) . 'https://www.goodreads.com/api/keys',
					'type' => 'password'
				),
				'rcno_goodreads_secret'     => array(
					'name' => __( 'Goodreads® API Secret', self::$plugin_name ),
					'desc' => __( 'Available at: ', self::$plugin_name ) . 'https://www.goodreads.com/api/keys',
					'type' => 'password'
				),

			)


		);

		$taxes = explode( ",", Rcno_Reviews_Option::get_option( 'rcno_taxonomy_selection' ) );

		foreach ( $taxes as $tax ) {
			foreach ( self::taxonomy_options( $tax ) as $key => $value ) {
				$settings['taxonomy_tab'][ strtolower( $key ) ] = $value;
			}
		}

		return self::apply_tab_slug_filters( $settings );
	}

	public static function taxonomy_options( $tax ) {

		$opts = array(
			'rcno_' . $tax . '_header'       => array(
				'name' => '<h2 class="section-heading">' . __( ucfirst( $tax ), 'rcno-reviews' ) . '</h2>',
				'type' => 'header'
			),
			'rcno_' . $tax . '_slug'         => array(
				'name' => __( 'Slug', 'rcno-reviews' ),
				'desc' => __( 'Place the slug of the ' . $tax . ' taxonomy here.', 'rcno-reviews' ),
				'std'  => $tax,
				'type' => 'text'
			),
			'rcno_' . $tax . '_hierarchical' => array(
				'name' => __( 'Hierarchical', 'rcno-reviews' ),
				'desc' => __( 'Is this custom taxonomy hierarchical?', 'rcno-reviews' ),
				'type' => 'checkbox'
			),
			'rcno_' . $tax . '_show'         => array(
				'name' => __( 'Show in table', 'rcno-reviews' ),
				'desc' => __( 'Show this custom taxonomy on the admin table', 'rcno-reviews' ),
				'type' => 'checkbox'
			),
		);

		return $opts;
	}

}


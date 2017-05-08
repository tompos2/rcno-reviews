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

	// @TODO: change plugin-name
	public static $plugin_name = 'rcno-reviews';

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
	 * @return    array    $tabs    Settings tabs
	 */
	static public function get_tabs() {

		$tabs                = array();
		$tabs['default_tab'] = __( 'Default Tab', self::$plugin_name );
		$tabs['second_tab']  = __( 'Second Tab', self::$plugin_name );
		$tabs['taxonomy_tab']  = __( 'Taxonomies', self::$plugin_name );

		return apply_filters( 'rcno_reviews_settings_tabs', $tabs );
	}

	/**
	 * 'Whitelisted' Plugin_Name settings, filters are provided for each settings
	 * section to allow extensions and other plugins to add their own settings
	 *
	 *
	 * @since    1.0.0
	 * @return    mixed    $value    Value saved / $default if key if not exist
	 */
	static public function get_settings() {

		$settings[] = array();

		$settings = array(
			'default_tab' => array(
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
			),
			'second_tab'  => array(
				'extend_me' => array(
					'name' => 'Extend me',
					'desc' => __( 'You can extend me via hooks and filters.', self::$plugin_name ),
					'type' => 'text'
				)
			),
			// Begin taxonomies tab.
			'taxonomy_tab' => array(
				'taxonomy_tab_header'       => array(
					'name' => '<strong>' . __( 'Review Taxonomies', self::$plugin_name ) . '</strong>',
					'type' => 'header'
				),
				'rcno_taxonomy_selection_header' => array(
					'name' => '<h2 class="section-heading">' . __( 'Taxonomies', self::$plugin_name ) . '</h2>',
					'type' => 'header'
				),
				'rcno_taxonomy_selection'        => array(
					'name'    => __( 'Taxonomy Selection', self::$plugin_name ),
					'desc'    => __( 'Custom Taxonomy Selection with 3 options', self::$plugin_name ),
					'options' => array(
						'author'    => __( 'Author', self::$plugin_name ),
						'genre'     => __( 'Genre', self::$plugin_name ),
						'series'    => __( 'Series', self::$plugin_name ),
					),
					'type'    => 'multicheck'
				),
				// Book Review Author Taxonomy.
				'rcno_author_header'            => array(
					'name' => '<h2 class="section-heading">' . __( 'Author', self::$plugin_name ) . '</h2>',
					'type' => 'header'
				),
				'rcno_author_slug'              => array(
					'name' => __( 'Slug', self::$plugin_name ),
					'desc' => __( 'Place the slug of the author taxonomy here.', self::$plugin_name ),
					'std'  => 'author',
					'type' => 'text'
				),
				'rcno_author_hierarchical'                   => array(
					'name' => __( 'Hierarchical', self::$plugin_name ),
					'desc' => __( 'Is this custom taxonomy hierarchical?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_author_show'                   => array(
					'name' => __( 'Show in table', self::$plugin_name ),
					'desc' => __( 'Show this custom taxonomy on the admin table', self::$plugin_name ),
					'type' => 'checkbox'
				),
				// Book Review Genre Taxonomy.
				'rcno_genre_header'            => array(
					'name' => '<h2 class="section-heading">' . __( 'Genre', self::$plugin_name ) . '</h2>',
					'type' => 'header'
				),
				'rcno_genre_slug'              => array(
					'name' => __( 'Slug', self::$plugin_name ),
					'desc' => __( 'Place the slug of the genre taxonomy here.', self::$plugin_name ),
					'std'  => 'genre',
					'type' => 'text'
				),
				'rcno_genre_hierarchical'                   => array(
					'name' => __( 'Hierarchical', self::$plugin_name ),
					'desc' => __( 'Is this custom taxonomy hierarchical?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_genre_show'                   => array(
					'name' => __( 'Show in table', self::$plugin_name ),
					'desc' => __( 'Show this custom taxonomy on the admin table', self::$plugin_name ),
					'type' => 'checkbox'
				),
				// Book Review Series Taxonomy.
				'rcno_series_header'            => array(
					'name' => '<h2 class="section-heading">' . __( 'Series', self::$plugin_name ) . '</h2>',
					'type' => 'header'
				),
				'rcno_series_slug'              => array(
					'name' => __( 'Slug', self::$plugin_name ),
					'desc' => __( 'Place the slug of the series taxonomy here.', self::$plugin_name ),
					'std'  => 'series',
					'type' => 'text'
				),
				'rcno_series_hierarchical'                   => array(
					'name' => __( 'Hierarchical', self::$plugin_name ),
					'desc' => __( 'Is this custom taxonomy hierarchical?', self::$plugin_name ),
					'type' => 'checkbox'
				),
				'rcno_series_show'                   => array(
					'name' => __( 'Show in table', self::$plugin_name ),
					'desc' => __( 'Show this custom taxonomy on the admin table', self::$plugin_name ),
					'type' => 'checkbox'
				),

			)
		);

		return self::apply_tab_slug_filters( $settings );
	}
}

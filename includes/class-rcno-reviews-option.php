<?php

/**
 *
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 */

/**
 * The get_option functionality of the plugin.
 *
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Reviews_Option {

	/**
	 * Get an option
	 *
	 * Looks to see if the specified setting exists, returns default if not.
	 *
	 * @since 	1.0.0
	 * @param string $key
	 * @param mixed $default
	 * @return 	mixed 	$value 	Value saved / $default if key if not exist
	 */
	static public function get_option( $key, $default = false ) {

		if ( empty( $key ) ) {
			return $default;
		}

		$plugin_options = get_option( 'rcno_reviews_settings', array() );

		$value = isset( $plugin_options[ $key ] ) ? $plugin_options[ $key ] : $default;

		return $value;
	}

	/**
	 * Update an option
	 *
	 * Updates the specified option.
	 * This is for developers to update options outside the settings page.
	 *
	 * WARNING: Hooks and filters will be triggered!!
	 * @TODO: Trigger hooks & filters, pull requests welcomed
	 *
	 * @since 1.0.0
	 * @return true if the option was saved or false if not
	 */
	static public function update_option( $key, $value ) {

		if ( empty( $key ) ) {
			return false;
		}

		// Load the options.
		$plugin_options = get_option( 'rcno_reviews_settings', array() );

		// Update the specified value in the array.
		$plugin_options[ $key ] = $value;

		// Save the options back to the DB.
		return update_option( 'rcno_reviews_settings', $plugin_options );
	}

	/**
	 * Delete an option
	 *
	 * Deletes the specified option.
	 * This is for developers to delete options outside the settings page.
	 *
	 * WARNING: Hooks and filters will be triggered!!
	 * @TODO: Trigger hooks & filters, pull requests welcomed
	 *
	 * @since 1.0.0
	 * @return true if the option was deleted or false if not
	 */
	static public function delete_option( $key ) {

		if ( empty( $key ) ) {
			return false;
		}

		// Load the options.
		$plugin_options = get_option( 'rcno_reviews_settings', array() );

		// Delete the specified key.
		unset( $plugin_options[ $key ] );

		// Save the options back to the DB.
		return update_option( 'rcno_reviews_settings', $plugin_options );
	}

	static public function delete_all_options() {

		// Delete all the options.
		delete_option( 'rcno_reviews_settings' );
	}


	static public function reset_all_options() {

		$default_options = array(
			'rcno_settings_version'           => '1.0.0',
			'rcno_review_slug'                => 'review',
			'rcno_reviews_archive'            => 'archive_display_excerpt',
			'rcno_reviews_on_homepage'        => '1',
			'rcno_reviews_in_rss'             => '1',
			'rcno_taxonomy_selection'         => array(
				'author' => 'Author',
				'genre'  => 'Genre',
				'series' => 'Series',
			),
			'rcno_author_slug'                => 'author',
			'rcno_author_show'                => '1',
			'rcno_genre_slug'                 => 'genre',
			'rcno_genre_hierarchical'         => '1',
			'rcno_genre_show'                 => '1',
			'rcno_series_slug'                => 'series',
			'rcno_series_hierarchical'        => '1',
			'rcno_series_show'                => '1',
			'rcno_show_isbn'                  => '1',
			'rcno_show_isbn13'                => '1',
			'rcno_show_asin'                  => '1',
			'rcno_show_gr_id'                 => '1',
			'rcno_show_gr_url'                => '1',
			'rcno_show_publisher'             => '1',
			'rcno_show_pub_date'              => '1',
			'rcno_show_pub_format'            => '1',
			'rcno_show_pub_edition'           => '1',
			'rcno_show_page_count'            => '1',
			'rcno_show_gr_rating'             => '1',
			'rcno_show_book_slider_widget'    => '1',
			'rcno_show_recent_reviews_widget' => '1',
			'rcno_show_tag_cloud_widget'      => '1',
			'rcno_show_taxonomy_list_widget'  => '1',
			'rcno_review_template'            => 'rcno_default',
		);

		// Set the options to the defaults from the '$default_options' array.
		update_option( 'rcno_reviews_settings', $default_options );
	}


}

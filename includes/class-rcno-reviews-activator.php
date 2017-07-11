<?php

/**
 * Fired during plugin activation
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Reviews_Activator {

	/**
	 * Short Description. (use period).
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		flush_rewrite_rules();
	}

	public static function setup_rcno_settings() {
		$settings_version = get_option( 'rcno_reviews_settings', null );

		$default_options = array(
			'rcno_settings_version'                 => '1.0.0',
			'rcno_review_slug'                      => 'review',
			'rcno_reviews_archive'                  => 'archive_display_excerpt',
			'rcno_reviews_on_homepage'              => '1',
			'rcno_reviews_in_rss'                   => '1',
			'rcno_taxonomy_selection'               => 'Author,Genre,Series,Publisher',
			'rcno_author_slug'                      => 'author',
			'rcno_author_show'                      => '1',
			'rcno_genre_slug'                       => 'genre',
			'rcno_genre_hierarchical'               => '1',
			'rcno_genre_show'                       => '1',
			'rcno_series_slug'                      => 'series',
			'rcno_series_show'                      => '1',
			'rcno_show_isbn'                        => '1',
			'rcno_show_isbn13'                      => '1',
			'rcno_show_asin'                        => '1',
			'rcno_show_gr_id'                       => '1',
			'rcno_show_gr_url'                      => '1',
			'rcno_show_publisher'                   => '1',
			'rcno_show_pub_date'                    => '1',
			'rcno_show_pub_format'                  => '1',
			'rcno_show_pub_edition'                 => '1',
			'rcno_show_page_count'                  => '1',
			'rcno_show_gr_rating'                   => '1',
			'rcno_show_review_score_box'            => '1',
			'rcno_show_review_score_box_background' => '#ffffff',
			'rcno_show_review_score_box_accent'     => '#212121',
			'rcno_show_book_slider_widget'          => '1',
			'rcno_show_recent_reviews_widget'       => '1',
			'rcno_show_tag_cloud_widget'            => '1',
			'rcno_show_taxonomy_list_widget'        => '1',
			'rcno_review_template'                  => 'rcno_default',
			'rcno_excerpt_read_more'                => 'Read more',
			'rcno_excerpt_word_count'               => '55',
			'rcno_reviews_in_rest'                  => '1',
			'rcno_publisher_slug'                   => 'Publisher',
			'rcno_publisher_show'                   => '1',
			'rcno_show_illustrator'                 => '1',
			'rcno_store_purchase_links_label'       => 'Purchase on:',
			'rcno_store_purchase_links'             => 'Amazon,Barnes & Noble,Kobo',
			'rcno_enable_purchase_links'            => '1',
			'rcno_store_purchase_link_text_color'   => '#ffffff',
			'rcno_store_purchase_link_background'   => '#212121',
			'rcno_enable_star_rating_box'           => '1',
			'rcno_star_rating_color'                => '#ffffff',
			'rcno_star_background_color'            => '#212121',
			'rcno_show_review_score_box_accent_2'   => '#ffffff',
			'rcno_comment_rating_label'             => 'Rate this review:',
			'rcno_comment_rating_star_color'        => '#212121',
			'rcno_show_book_grid_widget'            => '1',
			'rcno_external_book_api'                => 'no-3rd-party',
		);

		if ( null !== $settings_version ) {
			$settings_version = $settings_version['rcno_settings_version'];
		}

		if ( '1.0.0' !== $settings_version ) {
			// Set the options to the defaults from the '$default_options' array.
			return update_option( 'rcno_reviews_settings', $default_options );
		}

		return true;
	}

}

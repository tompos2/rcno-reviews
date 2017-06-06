<?php

class Rcno_Reviews_Rest_API {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}


	/**
	 * Add the book review custom post type to the REST API.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function rcno_reviews_rest_support() {
		global $wp_post_types;

		$post_type_name = 'rcno_review';
		if( isset( $wp_post_types[ $post_type_name ] ) ) {
			$wp_post_types[$post_type_name]->show_in_rest = true;
			$wp_post_types[$post_type_name]->rest_base = 'review';
			$wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
		}
	}


	/**
	 * Add the book review taxonomies to the REST API.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function rcno_reviews_taxonomy_rest_support() {
		global $wp_taxonomies;

		$taxonomies = array_keys( Rcno_Reviews_Option::get_option( 'rcno_taxonomy_selection' ) );
		$tax_name = array();

		foreach ( $taxonomies as $tax ) {
			$tax_name[$tax] = 'rcno_' . $tax;
		}

		foreach ( $tax_name as $key => $value ) {
			if ( isset( $wp_taxonomies[ $value ] ) ) {
				$wp_taxonomies[ $value ]->show_in_rest = true;
				$wp_taxonomies[ $value ]->rest_base             = $key;
				$wp_taxonomies[ $value ]->rest_controller_class = 'WP_REST_Terms_Controller';
			}
		}
	}


	public function rcno_register_rest_fields(){

		register_rest_field( 'rcno_review', 'book_ISBN', array(
			'get_callback' => function( $object ) {
				return get_post_meta( $object['id'], 'rcno_book_isbn', true );
			},
			'update_callback' => 'null',
			'schema' => null,
		) );

		register_rest_field( 'rcno_review', 'book_ISBN13', array(
			'get_callback' => function( $object ) {
				return get_post_meta( $object['id'], 'rcno_book_isbn13', true );
			},
			'update_callback' => 'null',
			'schema' => null,
		) );

		register_rest_field( 'rcno_review', 'book_ASIN', array(
			'get_callback' => function( $object ) {
				return get_post_meta( $object['id'], 'rcno_book_asin', true );
			},
			'update_callback' => 'null',
			'schema' => null,
		) );

		register_rest_field( 'rcno_review', 'book_title', array(
			'get_callback' => function( $object ) {
				return get_post_meta( $object['id'], 'rcno_book_title', true );
			},
			'update_callback' => 'null',
			'schema' => null,
		) );

		register_rest_field( 'rcno_review', 'book_author', array(
			'get_callback' => function( $object ) {
				return 'Book Author';
			},
			'update_callback' => 'null',
			'schema' => null,
		) );

		register_rest_field( 'rcno_review', 'book_publisher', array(
			'get_callback' => function( $object ) {
				return get_post_meta( $object['id'], 'rcno_book_publisher', true );
			},
			'update_callback' => 'null',
			'schema' => null,
		) );

		register_rest_field( 'rcno_review', 'book_published_date', array(
			'get_callback' => function( $object ) {
				return get_post_meta( $object['id'], 'rcno_book_pub_date', true );
			},
			'update_callback' => 'null',
			'schema' => null,
		) );

		register_rest_field( 'rcno_review', 'book_published_date', array(
			'get_callback' => function( $object ) {
				return get_post_meta( $object['id'], 'rcno_book_pub_date', true );
			},
			'update_callback' => 'null',
			'schema' => null,
		) );

		register_rest_field( 'rcno_review', 'book_page_count', array(
			'get_callback' => function( $object ) {
				return get_post_meta( $object['id'], 'rcno_book_page_count', true );
			},
			'update_callback' => 'null',
			'schema' => null,
		) );

	}
}
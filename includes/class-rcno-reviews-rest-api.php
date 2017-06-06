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


	public function rcno_register_rest_fields(){

		register_rest_field( 'rcno_review', 'book_ISBN', array(
			'get_callback' => function( $object ) {
				return get_post_meta( $object['id'], 'rcno_book_isbn', true );
			},
			'update_callback' => function( $object, $value ) {
				if ( ! $value || ! is_string( $value ) ) {
					return false;
				}

				return update_post_meta( $object['id'], 'rcno_book_isbn', strip_tags( $value ) );
			},
			'schema' => null,
		) );
	}
}
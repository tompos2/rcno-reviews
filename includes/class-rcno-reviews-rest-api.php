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
			'get_callback' => function() {
				$book_ISBN = get_post_meta( get_post()->ID, 'rcno_book_isbn', true );
				return (string) $book_ISBN;
			},
			'update_callback' => function( $meta_value ) { // @TODO: Fix update callback.
				$ret = update_post_meta( get_post()->ID, 'rcno_book_isbn', $meta_value );
				if ( false === $ret ) {
					return new WP_Error( 'rest_book_ISBN_update_failed', __( 'Failed to update the book ISBN.' ), array( 'status' => 500 ) );
				}
				return true;
			},
			'schema' => array(
				'description' => __( 'Book ISBN Number' ),
				'type'        => 'string'
			),
		) );
	}
}
<?php

class Rcno_Reviews_Rest_API {

	public function __construct() {
		$this->rcno_register_rest_fields();
	}

	public function rcno_register_rest_fields(){
		add_action( 'rest_api_init', array( $this, 'do_register_rest_fields' ) );
	}

	public function do_register_rest_fields(){
		register_rest_field( 'post', 'book_ISBN', array(
			'get_callback' => function() {
				$book_ISBN = get_post_meta( 14, 'rcno_book_isbn', true );
				return (int) $book_ISBN;
			},
			'update_callback' => function( $karma, $comment_obj ) {
				$ret = wp_update_comment( array(
					'comment_ID'    => $comment_obj->comment_ID,
					'comment_karma' => $karma
				) );
				if ( false === $ret ) {
					return new WP_Error( 'rest_comment_karma_failed', __( 'Failed to update comment karma.' ), array( 'status' => 500 ) );
				}
				return true;
			},
			'schema' => array(
				'description' => __( 'Comment karma.' ),
				'type'        => 'integer'
			),
		) );
	}
}
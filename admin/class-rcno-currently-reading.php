<?php

/**
 * Encapsulates the methods related to the Currently Reading functionality.
 *
 * @link       https://wzymedia.com
 * @since      1.1.10
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 */

/**
 * The methods related to the currently reading functionality.
 *
 * @since      1.1.10
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Currently_Reading {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.1.10
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.1.10
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * The ID of the widget in the admin dashboard.
	 *
	 * @since    1.1.10
	 * @access   protected
	 * @var      string $version The widget ID.
	 */
	protected $widget_id;

	/**
	 * The default values for the currently reading values.
	 *
	 * @since    1.1.10
	 * @access   protected
	 * @var      string $version The widget ID.
	 */
	protected $default_progress;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since   1.1.10
	 *
	 * @param   string $plugin_name The name of this plugin.
	 * @param   string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->widget_id   = 'rcno_currently_reading';
		$this->default_progress = array(
			array(
				'book_cover'       => '',
				'book_title'       => '',
				'book_author'      => '',
				'current_page'     => 0,
				'num_of_pages'     => 1,
				'progress_comment' => '',
				'last_updated'     => '',
				'finished_book'    => 0,
			)
		);
	}

	/**
	 * Checks if we have enabled and chose Google Books as our external book API.
	 *
	 * @since 1.1.10
	 *
	 * @return void
	 */
	public function rcno_enqueue_currently_reading_scripts() {

		$screen = get_current_screen();

		if ( null !== $screen && 'dashboard' === $screen->id ) {

			wp_enqueue_script( $this->widget_id, plugin_dir_url( __DIR__ ) . 'admin/js/rcno-currently-reading.js',
				array( 'jquery' ), '1.5.1', true );
			wp_localize_script( $this->widget_id, 'currently_reading', array(
				'api' => array(
					'url'   => esc_url_raw( rest_url( 'rcno/v1/currently-reading' ) ),
					'nonce' => wp_create_nonce( 'wp_rest' ),
				),
				'strings' => array(
					'saved' => __( 'Progress updated', 'rcno-reviews' ),
					'error' => __( 'Error', 'rcno-reviews' )
				),
			) );

		}
	}

	/**
	 * Registers the current reading admin dashboard widget.
	 *
	 * @since   1.1.10
	 *
	 * @uses    wp_add_dashboard_widget
	 * @return  void
	 */
	public function rcno_register_currently_reading_dash_widget() {

		if ( ! Rcno_Reviews_Option::get_option( 'rcno_show_currently_reading_widget' ) ) {
			return;
		}

		wp_add_dashboard_widget(
			$this->widget_id,
			__( 'Currently Reading', 'rcno-reviews' ),
			array( $this, 'rcno_currently_reading_dashboard_widget' )
		);
	}

	/**
	 * Displays the actual reading admin dashboard widget form.
	 *
	 * @since   1.1.10
	 *
	 * @return  void
	 */
	public function rcno_currently_reading_dashboard_widget() {

		require plugin_dir_path( __DIR__ ) . 'admin/views/rcno-currently-reading-dash-widget.php';
	}

	/**
	 * Get the currently reading progress data.
	 *
	 * @since   1.1.10
	 *
	 * @return  array
	 */
	public function rcno_get_currently_reading_progress() {

		$current_progress = get_option( $this->widget_id, array() );

		if( ! is_array( $current_progress ) || empty( $current_progress ) ){
			return $this->default_progress;
		}

		return wp_parse_args( $current_progress, $this->default_progress );
	}

	/**
	 * Saves the currently reading progress.
	 *
	 * @since   1.1.10
	 *
	 * @param   array   $progress   The progress data sent from dash widget.
	 * @return  boolean
	 */
	public function rcno_save_currently_reading_progress( array $progress ) { // @TODO: Check for empty values.

		$_progress = get_option( $this->widget_id, array() );

		// Remove any non-allowed indexes before save.
		foreach ( $progress as $key => $value ){
			if ( ! array_key_exists( $key, $this->default_progress[0] ) ) {
				unset( $progress[ $key ] );
			}
		}
		if ( $progress['finished_book'] ) {
			return update_option( $this->widget_id, array() );
		}

		$_progress[] = $progress; // Append our most recent progress to the existing data.
		return update_option( $this->widget_id, $_progress );
	}

	/**
	 * Adds the REST routes for the currently reading feature.
	 */
	public function rcno_currently_rest_routes() {

		register_rest_route( 'rcno/v1', '/currently-reading',
			array(
				'methods'              => 'POST',
				'callback'             => array( $this, 'update_progress' ),
				'args'                 => array(
					'book_cover' => array(
						'type'              => 'string',
						'required'          => false,
						'sanitize_callback' => 'sanitize_text_field',
					),
					'book_title' => array(
						'type'              => 'string',
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					),
					'book_author' => array(
						'type'              => 'string',
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					),
					'current_page'   => array(
						'type'              => 'integer',
						'required'          => true,
						'sanitize_callback' => 'absint',
					),
					'num_of_pages'   => array(
						'type'              => 'integer',
						'required'          => true,
						'sanitize_callback' => 'absint',
					),
					'progress_comment' => array(
						'type'              => 'string',
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					),
					'last_updated' => array(
						'type'              => 'string',
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					),
					'finished_book' => array(
						'type'              => 'integer',
						'required'          => true,
						'sanitize_callback' => 'absint',
					),
				),
				'permission_callback' => array( $this, 'permissions' ),
			)
		);

		register_rest_route( 'rcno/v1', '/currently-reading',
			array(
				'methods'              => 'GET',
				'callback'             => array( $this, 'get_progress' ),
				'args'                 => array(),
				'permission_callback' => array( $this, 'permissions' ),
			)
		);
	}

	/**
	 * Check request permissions
	 *
	 * @return bool
	 */
	public function permissions() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Update currently reading progress.
	 *
	 * @param WP_REST_Request $request
	 * @return string
	 */
	public function update_progress( WP_REST_Request $request ) {

		$rest_nonce = $request->get_header('x_wp_nonce');

		if( ! $rest_nonce || ! wp_verify_nonce( $rest_nonce, 'wp_rest' ) ) {
			return new WP_REST_Response( new WP_Error( 'rest_security_invalid_nonce', __( 'Security nonce is invalid or not set' ),
				array( 'status' => 403 ) ), 403 );
		}

		$progress = array(
			'book_cover'       => $request->get_param( 'book_cover' ),
			'book_title'       => $request->get_param( 'book_title' ),
			'book_author'      => $request->get_param( 'book_author' ),
			'current_page'     => $request->get_param( 'current_page' ),
			'num_of_pages'     => $request->get_param( 'num_of_pages' ),
			'progress_comment' => $request->get_param( 'progress_comment' ),
			'last_updated'     => $request->get_param( 'last_updated' ),
			'finished_book'    => $request->get_param( 'finished_book' ),
		);

		$this->rcno_save_currently_reading_progress( $progress );

		return rest_ensure_response( $this->rcno_get_currently_reading_progress() );
	}

	/**
	 * Get currently reading progress via API.
	 *
	 * @param WP_REST_Request $request
	 * @return string
	 */
	public function get_progress( WP_REST_Request $request ) {

		return rest_ensure_response( $this->rcno_get_currently_reading_progress() );
	}
}

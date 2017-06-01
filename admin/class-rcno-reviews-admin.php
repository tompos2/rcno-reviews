<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Reviews_Admin {

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

	/**
	 * Instance of the general meta class handling all general information related functions
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public $description_meta;

	/**
	 * Instance of the ISBN class handling book ISBN number functions.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public $book_isbn;

	/**
	 * Instance of the Book_Cover class.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public $book_cover;

	/**
	 * Instance of the Rcno_Admin_General_Info class handling general book info functions.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public $book_general_info;

	/**
	 * Instance of the Rcno_Admin_Review_Score class handling general book info functions.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public $book_review_score;

	/**
	 * Instance of the Rcno_Admin_Review_Rating class handling simple 5 star rating functions.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public $book_review_rating;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		require_once __DIR__ . '/class-rcno-admin-description-meta.php';
		$this->description_meta = new Rcno_Admin_Description_Meta( $this->version );

		require_once __DIR__ . '/class-rcno-admin-isbn.php';
		$this->book_isbn = new Rcno_Admin_ISBN( $this->version );

		require_once __DIR__ . '/class-rcno-admin-book-cover.php';
		$this->book_cover = new Rcno_Admin_Book_Cover( $this->version, $this->version );

		require_once __DIR__ . '/class-rcno-admin-general-info.php';
		$this->book_general_info = new Rcno_Admin_General_Info( $this->version );

		require_once __DIR__ . '/class-rcno-admin-review-score.php';
		$this->book_review_score = new Rcno_Admin_Review_Score( $this->version );

		require_once __DIR__ . '/class-rcno-admin-review-rating.php';
		$this->book_review_rating = new Rcno_Admin_Review_Rating( $this->version );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rcno_Reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rcno_Reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rcno-reviews-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name . '-modal', plugin_dir_url( __FILE__ ) . '/css/rcno-reviews-modal.css', $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		global $post;

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rcno_Reviews_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rcno_Reviews_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_media();

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rcno-reviews-admin.js', array( 'jquery' ), $this->version, false );

		if ( $hook === 'post-new.php' || $hook === 'post.php' ) {
			if ( 'rcno_review' === $post->post_type ) {
				wp_localize_script( $this->plugin_name, 'my_script_vars', array(
					'reviewID' => $post->ID
				) );
			}
		}

		

	}

	/**
	 * Creates the book review custom post type.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @uses    register_post_type()
	 * @return  void
	 */
	public function rcno_review_posttype() {

		$cap_type = 'post';
		$cpt_name = 'rcno_review';

		$cpt_slug = Rcno_Reviews_Option::get_option( 'rcno_review_slug' );
		$plural   = ucfirst( Rcno_Pluralize_Helper::pluralize( $cpt_slug ) );
		$single   = ucfirst( Rcno_Pluralize_Helper::singularize( $cpt_slug ) );



		$opts['can_export']            = true;
		$opts['capability_type']       = $cap_type;
		$opts['description']           = '';
		$opts['exclude_from_search']   = false;
		$opts['has_archive']           = Rcno_Pluralize_Helper::pluralize( $cpt_slug );
		$opts['hierarchical']          = false;
		$opts['map_meta_cap']          = true;
		$opts['menu_icon']             = 'dashicons-book';
		$opts['menu_position']         = 5;
		$opts['public']                = true;
		$opts['publicly_querable']     = true;
		$opts['query_var']             = true;
		$opts['register_meta_box_cb']  = '';
		$opts['rewrite']               = false;
		$opts['show_in_admin_bar']     = true;
		$opts['show_in_menu']          = true;
		$opts['show_in_nav_menu']      = true;
		$opts['show_ui']               = true;
		$opts['show_in_rest']          = true;
		$opts['base_rest']             = $cpt_name;
		$opts['rest_controller_class'] = 'WP_REST_Posts_Controller';
		$opts['supports']              = array( 'title', 'editor', 'thumbnail', 'excerpt', 'featured', 'author', 'comments' );
		$opts['taxonomies']            = array();

		$opts['capabilities']['delete_others_posts']    = "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']            = "delete_{$cap_type}";
		$opts['capabilities']['delete_posts']           = "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']   = "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts'] = "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']      = "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']              = "edit_{$cap_type}";
		$opts['capabilities']['edit_posts']             = "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']     = "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']   = "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']          = "publish_{$cap_type}s";
		$opts['capabilities']['read_post']              = "read_{$cap_type}";
		$opts['capabilities']['read_private_posts']     = "read_private_{$cap_type}s";

		$opts['labels']['add_new']            = esc_html__( "New {$single}", 'rcno-reviews' );
		$opts['labels']['add_new_item']       = esc_html__( "Add New {$single}", 'rcno-reviews' );
		$opts['labels']['all_items']          = esc_html__( $plural, 'rcno-reviews' );
		$opts['labels']['edit_item']          = esc_html__( "Edit {$single}", 'rcno-reviews' );
		$opts['labels']['menu_name']          = esc_html__( $plural, 'rcno-reviews' );
		$opts['labels']['name']               = esc_html__( $plural, 'rcno-reviews' );
		$opts['labels']['name_admin_bar']     = esc_html__( $single, 'rcno-reviews' );
		$opts['labels']['new_item']           = esc_html__( "New {$single}", 'rcno-reviews' );
		$opts['labels']['not_found']          = esc_html__( "No {$plural} Found", 'rcno-reviews' );
		$opts['labels']['not_found_in_trash'] = esc_html__( "No {$plural} Found in Trash", 'rcno-reviews' );
		$opts['labels']['parent_item_colon']  = esc_html__( "Parent {$plural} :", 'rcno-reviews' );
		$opts['labels']['search_items']       = esc_html__( "Search {$plural}", 'rcno-reviews' );
		$opts['labels']['singular_name']      = esc_html__( $single, 'rcno-reviews' );
		$opts['labels']['view_item']          = esc_html__( "View {$single}", 'rcno-reviews' );

		$opts['rewrite']['ep_mask']    = EP_PERMALINK;
		$opts['rewrite']['feeds']      = false;
		$opts['rewrite']['pages']      = true;
		$opts['rewrite']['slug']       = __( $cpt_slug, 'rcno-reviews' );
		$opts['rewrite']['with_front'] = false;

		$opts = apply_filters( 'rcno_review_cpt_options', $opts );

		register_post_type( strtolower( $cpt_name ), $opts );

	}

	/**
	 * Creates a new course taxonomy for the book review post type
	 *
	 * @since    1.0.0
	 * @access    public
	 * @uses    register_taxonomy()
	 * @return  void
	 */
	public function rcno_custom_taxonomy() { // @TODO: Complete the custom taxonomy feature.

		$custom_taxonomies = Rcno_Reviews_Option::get_option( 'rcno_taxonomy_selection' );
		$keys = array_keys( $custom_taxonomies );
		$taxonomies = array();

		foreach ( $keys as $key ) {
			$taxonomies[] = array(
				'tax_settings'  => array(
					'slug'          => Rcno_Reviews_Option::get_option( "rcno_{$key}_slug" ),
					'hierarchy'     => Rcno_Reviews_Option::get_option( "rcno_{$key}_hierarchical" ),
					'show_in_table' => Rcno_Reviews_Option::get_option( "rcno_{$key}_show" ),
				) );
		}

		foreach ( $taxonomies as $tax ) {
			$plural   = ucfirst( Rcno_Pluralize_Helper::pluralize( $tax['tax_settings']['slug'] ) );
			$single   = ucfirst( Rcno_Pluralize_Helper::singularize( $tax['tax_settings']['slug'] ) );
			$tax_name = 'rcno_' . $tax['tax_settings']['slug'];

			$opts['hierarchical']      = $tax['tax_settings']['hierarchy'];
			// $opts['meta_box_cb'] 	   = '';
			$opts['public']            = true;
			$opts['query_var']         = $tax_name;
			$opts['show_admin_column'] = $tax['tax_settings']['show_in_table'];
			$opts['show_in_nav_menus'] = true;
			$opts['show_tag_cloud']    = true;
			$opts['show_ui']           = true;
			$opts['sort']              = '';
			// $opts['update_count_callback'] 	= '';

			$opts['capabilities']['assign_terms'] = 'edit_posts';
			$opts['capabilities']['delete_terms'] = 'manage_categories';
			$opts['capabilities']['edit_terms']   = 'manage_categories';
			$opts['capabilities']['manage_terms'] = 'manage_categories';

			$opts['labels']['add_new_item']               = esc_html__( "Add New {$single}", 'rcno-reviews' );
			$opts['labels']['add_or_remove_items']        = esc_html__( "Add or remove {$plural}", 'rcno-reviews' );
			$opts['labels']['all_items']                  = esc_html__( $plural, 'rcno-reviews' );
			$opts['labels']['choose_from_most_used']      = esc_html__( "Choose from most used {$plural}", 'rcno-reviews' );
			$opts['labels']['edit_item']                  = esc_html__( "Edit {$single}", 'rcno-reviews' );
			$opts['labels']['menu_name']                  = esc_html__( $plural, 'rcno-reviews' );
			$opts['labels']['name']                       = esc_html__( $plural, 'rcno-reviews' );
			$opts['labels']['new_item_name']              = esc_html__( "New {$single} Name", 'rcno-reviews' );
			$opts['labels']['not_found']                  = esc_html__( "No {$plural} Found", 'rcno-reviews' );
			$opts['labels']['parent_item']                = esc_html__( "Parent {$single}", 'rcno-reviews' );
			$opts['labels']['parent_item_colon']          = esc_html__( "Parent {$single}:", 'rcno-reviews' );
			$opts['labels']['popular_items']              = esc_html__( "Popular {$plural}", 'rcno-reviews' );
			$opts['labels']['search_items']               = esc_html__( "Search {$plural}", 'rcno-reviews' );
			$opts['labels']['separate_items_with_commas'] = esc_html__( "Separate {$plural} with commas", 'rcno-reviews' );
			$opts['labels']['singular_name']              = esc_html__( $single, 'rcno-reviews' );
			$opts['labels']['update_item']                = esc_html__( "Update {$single}", 'rcno-reviews' );
			$opts['labels']['view_item']                  = esc_html__( "View {$single}", 'rcno-reviews' );

			$opts['rewrite']['ep_mask']      = EP_NONE;
			$opts['rewrite']['hierarchical'] = false;

			// Pluralizing the rewrite slug to prevent clash with builtin author taxonomy and author custom taxonomy.
			$opts['rewrite']['slug']         = __( Rcno_Pluralize_Helper::pluralize( $tax['tax_settings']['slug'] ) );
			$opts['rewrite']['with_front']   = false;

			$opts = apply_filters( 'rcno_review_taxonomy_options', $opts );

			register_taxonomy( $tax_name, 'rcno_review', $opts );

		}
	}

	/**
	 * Registers new featured image sizes for the book review post type
	 *
	 * @since    1.0.0
	 * @access    public
	 * @uses    add_image_size()
	 * @return  void
	 */
	public function rcno_book_cover_sizes(){

		add_image_size( 'rcno-book-cover-lg', 381, 500 );

		add_filter( 'image_size_names_choose', function ( $sizes ){
			return array_merge( $sizes, array(
				'rcno-book-cover-lg' => __( 'Book Cover LG', 'rcno-reviews' ),
			) );
		} );
	}


	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since       1.0.0
	 * @return      void
	 */
	public function add_plugin_admin_menu() {

		add_menu_page(
			__( 'Recencio Book Reviews', $this->plugin_name ),
			__( 'Reviews', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' )
		);

		add_submenu_page(
			'edit.php?post_type=rcno_review',
			__( 'Recencio Book Reviews', $this->plugin_name ),
			__( 'Settings', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' )
		);

		remove_menu_page( $this->plugin_name );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 * @return   array 			Action links
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>'
			),
			$links
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {

		$tabs = Rcno_Reviews_Settings_Definition::get_tabs();

		$default_tab = Rcno_Reviews_Settings_Definition::get_default_tab_slug();

		$active_tab = isset( $_GET[ 'tab' ] ) && array_key_exists( $_GET['tab'], $tabs ) ? $_GET[ 'tab' ] : $default_tab;

		include_once( 'partials/' . $this->plugin_name . '-admin-display.php' );

	}

	/**
	 * Saves all the data from review meta boxes
	 *
	 * @param   int $review_id post ID of review being saved.
	 * @param   mixed $review the review post object.
	 * @see     https://developer.wordpress.org/reference/functions/wp_update_post/#user-contributed-notes
	 *
	 * @since   1.0.0
	 * @return  int|bool
	 */
	public function rcno_save_review( $review_id, $review = null ) {

		if ( ! wp_is_post_revision( $review_id ) ) { // 'save_post' is fired twice, once for revisions, then to save post.

			remove_action( 'save_post', array( $this, 'rcno_save_review' ) );

			$data = $_POST;

			if ( null !== $review && $review->post_type === 'rcno_review' ) {
				$errors = false;

				// Verify if this is an auto save routine. If it is our form has not been submitted, so we don't want to do anything.
				if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
					$errors = 'There was an error doing autosave';
				}

				// Check user permissions.
				if ( ! current_user_can( 'edit_post', $review_id ) ) {
					$errors = 'There was an error saving the review. Insufficient administrator rights.';
				}

				//If we have an error update the error_option and return.
				if ( $errors ) {
					update_option( 'rcno_admin_errors', $errors );
					return $review_id;
				}

				$this->description_meta->rcno_save_book_description_metadata( $review_id, $data, $review );
				$this->book_isbn->rcno_save_book_isbn_metadata( $review_id, $data, $review );
				$this->book_cover->rcno_save_book_cover_metadata( $review_id, $data, $review );
				$this->book_general_info->rcno_save_book_general_info_metadata( $review_id, $data, $review );
				$this->book_review_score->rcno_save_book_review_score_metadata( $review_id, $data, $review );
				$this->book_review_rating->rcno_save_book_review_rating_metadata( $review_id, $data, $review );

				add_action( 'save_post', array( $this, 'rcno_save_review' ) );
			}
		}
	}

	/**
	 * Function to display any errors in the backend
	 * @since 1.0.0
	 */
	public function rcno_admin_notice_handler() { // @TODO: Use the builtin error handler.

		$errors = get_option( 'rcno_admin_errors' );

		if ( $errors ) {
			echo '<div class="error"><p>' . $errors . '</p></div>';
		}

		// Reset the error option for the next error.
		update_option( 'rcno_admin_errors', false );
	}


	/**
	 * Adds book reviews to the 'Recent Activity' Dashboard widget
	 *
	 * @since 1.0.0
	 * @param array $query_args
	 */
	public function rcno_dashboard_recent_posts_widget( $query_args ) {
		$query_args =  array_merge( $query_args, array( 'post_type' => array( 'post', 'rcno_review' ) ) );
		return $query_args;
	}


	/**
	 * Adds book reviews to the 'At a Glance' Dashboard widget
	 *
	 * @since 1.0.0
	 * @param array $items
	 */
	public function rcno_add_reviews_glance_items( $items = array() ) {
		$num_reviews = wp_count_posts( 'rcno_review' );

		if( $num_reviews ) {
			$published = intval( $num_reviews->publish );
			$post_type = get_post_type_object( 'rcno_review' );

			$text = _n( '%s ' . $post_type->labels->singular_name, '%s ' . $post_type->labels->name, $published, 'rcno-reviews' );
			$text = sprintf( $text, number_format_i18n( $published ) );

			if ( current_user_can( $post_type->cap->edit_posts ) ) {
				$items[] = sprintf( '<a class="%1$s-count" href="edit.php?post_type=%1$s">%2$s</a>', 'rcno_review', $text ) . "\n";
			} else {
				$items[] = sprintf( '<span class="%1$s-count">%2$s</span>', 'rcno_review', $text ) . "\n";
			}
		}

		return $items;
	}

	/**
	 * Book review update messages.
	 * See /wp-admin/edit-form-advanced.php
	 * @param array $messages Existing post update messages.
	 * @return array Amended post update messages with new review update messages.
	 */
	function rcno_updated_review_messages( $messages ) {
		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );

		$messages['rcno_review'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Review updated.', 'rcno-reviews' ),
			2  => __( 'Custom field updated.', 'rcno-reviews' ),
			3  => __( 'Custom field deleted.', 'rcno-reviews' ),
			4  => __( 'Review updated.', 'rcno-reviews' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Review restored to revision from %s', 'rcno-reviews' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Review published.', 'rcno-reviews' ),
			7  => __( 'Review saved.', 'rcno-reviews' ),
			8  => __( 'Review submitted.', 'rcno-reviews' ),
			9  => sprintf(
				__( 'Review scheduled for: <strong>%1$s</strong>.', 'rcno-reviews' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i', 'rcno-reviews' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Review draft updated.', 'rcno-reviews' )
		);

		if ( $post_type_object->publicly_queryable && 'rcno_review' === $post_type ) {
			$permalink = get_permalink( $post->ID );

			$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View review', 'rcno-reviews' ) );
			$messages[ $post_type ][1] .= $view_link;
			$messages[ $post_type ][6] .= $view_link;
			$messages[ $post_type ][9] .= $view_link;

			$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
			$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview review', 'rcno-reviews' ) );
			$messages[ $post_type ][8]  .= $preview_link;
			$messages[ $post_type ][10] .= $preview_link;
		}
		return $messages;
	}


	/**
	 * Adds the book reviews post type to AMP.
	 */
	public function rcno_add_reviews_cpt_amp() {

		if ( ! defined( 'AMP_QUERY_VAR' ) ) {
			return; // do not add support if AMP plugin is not detected
		}

		add_post_type_support( 'rcno_review', AMP_QUERY_VAR );
	}

}

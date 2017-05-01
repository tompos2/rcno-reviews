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
	public $general_meta;

	/**
	 * Instance of the ISBN class handling book ISBN number functions.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public $book_isbn;

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

		require_once __DIR__ . '/class-rcno-admin-general-meta.php';
		$this->general_meta = new Rcno_Admin_General_Meta( $this->version );

		require_once __DIR__ . '/class-rcno-admin-isbn.php';
		$this->book_isbn = new Rcno_Admin_ISBN( $this->version );

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

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rcno-reviews-admin.js', array( 'jquery' ), $this->version, false );

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
		$plural   = 'Reviews';
		$single   = 'Review';
		$cpt_name = 'rcno_review';
		$cpt_slug = 'review'; // @TODO: Create an option to select recipe slug.

		$opts['can_export']            = true;
		$opts['capability_type']       = $cap_type;
		$opts['description']           = '';
		$opts['exclude_from_search']   = false;
		$opts['has_archive']           = true;
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
		$opts['supports'] = array( 'title', 'editor', 'thumbnail', 'excerpt', 'featured', 'author', 'revisions', 'comments' );
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
	 * Creates a new course taxonomy for the recipe post type
	 *
	 * @since    1.0.0
	 * @access    public
	 * @uses    register_taxonomy()
	 * @return  void
	 */
	public function rcto_custom_taxonomy() { // @TODO: Complete the custom taxonomy feature.

		//Setting a default for when all options are deselected.
		// $custom_taxonomies = Rcno_Review_Option::get_option( 'rcno_taxonomy_selection' );
		$custom_taxonomies = array( 'ingredient' => 'Ingredient' );

		//If the 'ingredient' key is not in the array, add it so it is always present.
		if ( ! in_array( 'ingredient', $custom_taxonomies, true ) ) {
			//'array_merge' because I want 'Ingredients' as the first taxonomy.
			$custom_taxonomies = array_merge(
				array( 'ingredient' => 'Ingredient' ), $custom_taxonomies
			);
		}

		$taxonomies = array();

		foreach ( $custom_taxonomies as $slug => $tax ) {
			$taxonomies[] = array(
				'tax_settings'  => array(
					'slug'          => Rcto_Recipes_Option::get_option( "rcto_{$slug}_slug" ),
					'hierarchy'     => Rcto_Recipes_Option::get_option( "rcto_{$slug}_hierarchical" ),
					'show_in_table' => Rcto_Recipes_Option::get_option( "rcto_{$slug}_show" ),
				) );
		}

		foreach ( $taxonomies as $value ) {
			$plural   = ucfirst( Rcto_Pluralize_Helper::pluralize( $value['tax_settings']['slug'] ) );
			$single   = ucfirst( Rcto_Pluralize_Helper::singularize( $value['tax_settings']['slug'] ) );
			$tax_name = 'rcto_' . $value['tax_settings']['slug'];

			$opts['hierarchical']      = $value['tax_settings']['hierarchy'];
			// $opts['meta_box_cb'] 	   = '';
			$opts['public']            = true;
			$opts['query_var']         = $tax_name;
			$opts['show_admin_column'] = $value['tax_settings']['show_in_table'];
			$opts['show_in_nav_menus'] = true;
			$opts['show_tag_cloud']    = true;
			$opts['show_ui']           = true;
			$opts['sort']              = '';
			// $opts['update_count_callback'] 	= '';

			$opts['capabilities']['assign_terms'] = 'edit_posts';
			$opts['capabilities']['delete_terms'] = 'manage_categories';
			$opts['capabilities']['edit_terms']   = 'manage_categories';
			$opts['capabilities']['manage_terms'] = 'manage_categories';

			$opts['labels']['add_new_item']               = esc_html__( "Add New {$single}", 'rcto-recipes' );
			$opts['labels']['add_or_remove_items']        = esc_html__( "Add or remove {$plural}", 'rcto-recipes' );
			$opts['labels']['all_items']                  = esc_html__( $plural, 'rcto-recipes' );
			$opts['labels']['choose_from_most_used']      = esc_html__( "Choose from most used {$plural}", 'rcto-recipes' );
			$opts['labels']['edit_item']                  = esc_html__( "Edit {$single}", 'rcto-recipes' );
			$opts['labels']['menu_name']                  = esc_html__( $plural, 'rcto-recipes' );
			$opts['labels']['name']                       = esc_html__( $plural, 'rcto-recipes' );
			$opts['labels']['new_item_name']              = esc_html__( "New {$single} Name", 'rcto-recipes' );
			$opts['labels']['not_found']                  = esc_html__( "No {$plural} Found", 'rcto-recipes' );
			$opts['labels']['parent_item']                = esc_html__( "Parent {$single}", 'rcto-recipes' );
			$opts['labels']['parent_item_colon']          = esc_html__( "Parent {$single}:", 'rcto-recipes' );
			$opts['labels']['popular_items']              = esc_html__( "Popular {$plural}", 'rcto-recipes' );
			$opts['labels']['search_items']               = esc_html__( "Search {$plural}", 'rcto-recipes' );
			$opts['labels']['separate_items_with_commas'] = esc_html__( "Separate {$plural} with commas", 'rcto-recipes' );
			$opts['labels']['singular_name']              = esc_html__( $single, 'rcto-recipes' );
			$opts['labels']['update_item']                = esc_html__( "Update {$single}", 'rcto-recipes' );
			$opts['labels']['view_item']                  = esc_html__( "View {$single}", 'rcto-recipes' );

			$opts['rewrite']['ep_mask']      = EP_NONE;
			$opts['rewrite']['hierarchical'] = false;
			$opts['rewrite']['slug']         = __( Rcto_Pluralize_Helper::singularize( $value['tax_settings']['slug'] ) );
			$opts['rewrite']['with_front']   = false;

			$opts = apply_filters( 'rcto-recipe-taxonomy-options', $opts );

			register_taxonomy( $tax_name, 'rcto_recipe', $opts );

		}
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
	 * Saves all the data from recipe meta boxes
	 *
	 * @param   int $recipe_id post ID of recipe being saved.
	 * @param   mixed $recipe the recipe post object.
	 *
	 * @since   1.0.0
	 */
	public function rcno_save_review( $review_id, $review = null ) {

		remove_action( 'save_post', array( $this, 'rcno_save_review' ) );

		$data = $_POST;

		if ( null !== $review && $review->post_type === 'rcno_review' ) {
			$errors = false;

			// Verify if this is an auto save routine. If it is our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				$errors = "There was an error doing autosave";
			}

			//Verify the nonces for the metaboxes @TODO: Check what should be the correct nonce here.
			//if ( isset( $data['rpr_save_recipe_meta_field'] ) && ! wp_verify_nonce( $data['rpr_save_recipe_meta_field'], 'rpr_save_recipe_meta' ) ) {
			//	$errors = "There was an error saving the recipe. Description nonce not verified";
			//}

			// Check user permissions
			if ( ! current_user_can( 'edit_post', $review_id ) ) {
				$errors = "There was an error saving the review. Insufficient administrator rights.";
			}

			//If we have an error update the error_option and return
			if ( $errors ) {
				update_option( 'rcno_admin_errors', $errors );

				return $review_id;
			}

			if ( null !== $review && $review->post_type === 'rcno_review' ) {

				$this->general_meta->rcno_save_book_review_metadata( $review_id, $data, $review );
				$this->book_isbn->rcno_save_book_isbn_metadata( $review_id, $data, $review );

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

}

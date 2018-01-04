<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/includes
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Reviews {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Rcno_Reviews_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'rcno-reviews';
		$this->version     = '1.5.0';

		$this->load_dependencies();
		$this->set_locale();

		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->define_taxonomy_hook();

		$this->define_template_hooks();
		$this->define_widget_hooks();

		$this->define_rest_hooks();
		$this->define_external_hooks();

		$this->define_shortcodes();

		$this->define_public_ratings();

		$this->define_currently_reading();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Rcno_Reviews_Loader. Orchestrates the hooks of the plugin.
	 * - Rcno_Reviews_i18n. Defines internationalization functionality.
	 * - Rcno_Reviews_Admin. Defines all hooks for the admin area.
	 * - Rcno_Reviews_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-rcno-reviews-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-rcno-reviews-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-rcno-reviews-admin.php';

		/**
		 * The class responsible for the pluralization and singularization of common nouns.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-rcno-pluralize-helper.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'public/class-rcno-reviews-public.php';
		require_once plugin_dir_path( __DIR__ ) . 'public/class-rcno-template-tags.php';
		require_once plugin_dir_path( __DIR__ ) . 'public/class-rcno-reviews-public-ratings.php';

		/**
		 * The class responsible for defining all actions that occur in the author taxonomy area.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'admin/class-rcno-author-taxonomy-metabox.php';

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-rcno-reviews-shortcodes.php';

		require_once plugin_dir_path( __DIR__ ) . 'public/widgets/class-rcno-reviews-tag-cloud.php';
		require_once plugin_dir_path( __DIR__ ) . 'public/widgets/class-rcno-reviews-taxonomy-list.php';
		require_once plugin_dir_path( __DIR__ ) . 'public/widgets/class-rcno-reviews-recent-reviews.php';
		require_once plugin_dir_path( __DIR__ ) . 'public/widgets/class-rcno-reviews-book-slider.php';
		require_once plugin_dir_path( __DIR__ ) . 'public/widgets/class-rcno-reviews-book-grid.php';
		require_once plugin_dir_path( __DIR__ ) . 'public/widgets/class-rcno-reviews-currently-reading.php';
		require_once plugin_dir_path( __DIR__ ) . 'public/widgets/class-rcno-reviews-calendar.php';

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-rcno-reviews-option.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/settings/class-rcno-reviews-callback-helper.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/settings/class-rcno-reviews-meta-box.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/settings/class-rcno-reviews-sanitization-helper.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/settings/class-rcno-reviews-settings-definition.php';
		require_once plugin_dir_path( __DIR__ ) . 'admin/settings/class-rcno-reviews-settings.php';

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-rcno-goodreads-api.php';
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-rcno-reviews-googlebooks.php';

		require_once plugin_dir_path( __DIR__ ) . 'includes/class-rcno-reviews-rest-api.php';

		require_once plugin_dir_path( __DIR__ ) . 'admin/class-rcno-currently-reading.php';

		$this->loader = new Rcno_Reviews_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Rcno_Reviews_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Rcno_Reviews_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Rcno_Reviews_Admin( $this->get_plugin_name(), $this->get_version() );

		// Creates the book review custom post type.
		$this->loader->add_action( 'init', $plugin_admin, 'rcno_review_posttype' );

		// Creates the book review custom taxonomy.
		$this->loader->add_action( 'init', $plugin_admin, 'rcno_custom_taxonomy' );

		// Creates date archive rewrite rules.
		$this->loader->add_action( 'generate_rewrite_rules', $plugin_admin, 'rcno_date_archives_rewrite_rules' );

		// Registers new featured image sizes for the book review post type.
		$this->loader->add_action( 'init', $plugin_admin, 'rcno_book_cover_sizes' );

		// Adds the book reviews post type to AMP.
		$this->loader->add_action( 'amp_init', $plugin_admin, 'rcno_add_reviews_cpt_amp' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add the options page and menu item.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( __DIR__ ) ) . $this->plugin_name . '.php' );
		$this->loader->add_action( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// Built the option page.
		$settings_callback     = new Rcno_Reviews_Callback_Helper( $this->plugin_name );
		$settings_sanitization = new Rcno_Reviews_Sanitization_Helper( $this->plugin_name );
		$plugin_settings       = new Rcno_Reviews_Settings( $this->get_plugin_name(), $settings_callback, $settings_sanitization );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'register_settings' );

		$plugin_meta_box = new Rcno_Reviews_Meta_Box( $this->get_plugin_name() );
		$this->loader->add_action( 'load-toplevel_page_' . $this->get_plugin_name(), $plugin_meta_box, 'add_meta_boxes' );

		// Load the 'Book Description' metabox on the review post edit screen.
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin->description_meta, 'rcno_book_description_metabox' );

		// Load the 'ISBN Number' metabox on the review post edit screen.
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin->book_isbn, 'rcno_book_isbn_metabox' );

		// Load the 'Book Cover' metabox on the review post edit screen.
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin->book_cover, 'rcno_book_cover_metabox' );

		// Load the 'General Information' metabox on the review post edit screen.
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin->book_general_info, 'rcno_book_general_info_metabox' );

		// Load the 'Review Score' metabox on the review post edit screen.
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin->book_review_score, 'rcno_book_review_score_metabox' );

		// Load the '5 Star Rating' metabox on the review post edit screen.
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin->book_review_rating, 'rcno_book_review_rating_metabox' );

		// Load the 'Purchase Links' metabox on the review post edit screen.
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin->buy_links, 'rcno_book_buy_links_metabox' );

		// Save book review.
		$this->loader->add_action( 'save_post', $plugin_admin, 'rcno_save_review', 10, 2 );

		// Display error messages in review edit screen.
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'rcno_admin_notice_handler' );

		// Add book reviews to Recent Activity widget.
		$this->loader->add_filter( 'dashboard_recent_posts_query_args', $plugin_admin, 'rcno_dashboard_recent_posts_widget' );

		// Add book reviews to 'At a Glance' widget.
		$this->loader->add_filter( 'dashboard_glance_items', $plugin_admin, 'rcno_add_reviews_glance_items' );

		// Add messages on the book review editor screen.
		$this->loader->add_filter( 'post_updated_messages', $plugin_admin, 'rcno_updated_review_messages' );

		// Add the help tab to the  review editor screen.
		$this->loader->add_filter( 'admin_head', $plugin_admin, 'rcno_reviews_help_tab' );
		$this->loader->add_action( 'contextual_help', $plugin_admin, 'rcno_add_help_text', 10, 3 );

		$this->loader->add_filter( 'manage_rcno_review_posts_columns', $plugin_admin, 'rcno_add_remove_admin_columns' );
		$this->loader->add_filter( 'manage_edit-rcno_review_sortable_columns', $plugin_admin, 'rcno_sort_admin_columns' );
		$this->loader->add_filter( 'manage_rcno_review_posts_custom_column', $plugin_admin, 'rcno_add_image_column_content', 10, 2 );
		$this->loader->add_filter( 'posts_clauses', $plugin_admin, 'rcno_query_admin_columns', 10, 2 );

		$this->loader->add_action( 'wp_ajax_reset_all_options', $plugin_admin, 'reset_all_options' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Rcno_Reviews_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Manipulate the query to include reviews to home page (if set).
		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'rcno_review_query' );

		// Adds the book review CPT to the RSS Feed.
		$this->loader->add_action( 'request', $plugin_public, 'rcno_add_reviews_to_rss_feed' );

		// Get the rendered content of a book review and forward it to the theme as the_content().
		$this->loader->add_filter( 'the_content', $plugin_public, 'rcno_get_review_content' );

		$this->loader->add_filter( 'excerpt_length', $plugin_public, 'rcno_reviews_excerpt_length', 10 );
		$this->loader->add_filter( 'excerpt_more', $plugin_public, 'rcno_reviews_excerpt_more', 10 );
	}

	/**
	 * Register all of the hooks related to the taxonomy edit page.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_taxonomy_hook() {

		$author_taxonomy = new Rcno_Author_Taxonomy_Metabox( $this->get_plugin_name(), $this->get_version() );

		// Load the 'Author' taxonomy metabox on the taxonomy edit screen.
		$this->loader->add_action( 'rcno_author_add_form_fields', $author_taxonomy, 'rcno_author_taxonomy_metabox' );
		$this->loader->add_action( 'rcno_author_edit_form_fields', $author_taxonomy, 'rcno_author_taxonomy_metabox' );

		// Save the author taxonomy metadata.
		$this->loader->add_action( 'created_rcno_author', $author_taxonomy, 'rcno_save_author_taxonomy_metadata' );
		$this->loader->add_action( 'edited_rcno_author', $author_taxonomy, 'rcno_save_author_taxonomy_metadata' );
	}

	/**
	 * Runs the 'include_functions_file' method used to include a functions.php n our custom review templates.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_template_hooks() {

		$template_hooks = new Rcno_Template_Tags( $this->get_plugin_name(), $this->get_version() );
		$template_hooks->include_functions_file();
	}

	/**
	 * Register all of the hooks related to the public-facing custom widgets.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_widget_hooks() {

		$tag_cloud = new Rcno_Reviews_Tag_Cloud();
		$this->loader->add_action( 'widgets_init', $tag_cloud, 'rcno_register_tag_cloud_widget' );

		$taxonomy_list = new Rcno_Reviews_Taxonomy_List();
		$this->loader->add_action( 'widgets_init', $taxonomy_list, 'rcno_register_taxonomy_list_widget' );

		$recent_reviews = new Rcno_Reviews_Recent_Reviews();
		$this->loader->add_action( 'widgets_init', $recent_reviews, 'rcno_register_recent_reviews_widget' );

		$book_slider = new Rcno_Reviews_Book_Slider();
		$this->loader->add_action( 'widgets_init', $book_slider, 'rcno_register_book_slider_widget' );

		$book_grid = new Rcno_Reviews_Book_Grid();
		$this->loader->add_action( 'widgets_init', $book_grid, 'rcno_register_book_grid_widget' );

		$reading = new Rcno_Reviews_Currently_Reading();
		$this->loader->add_action( 'widgets_init', $reading, 'rcno_register_currently_reading_widget' );

		$review_calendar = new Rcno_Reviews_Calendar();
		$this->loader->add_action( 'widgets_init', $review_calendar, 'rcno_register_review_calendar_widget' );
	}

	/**
	 * Register all of the hooks related to the public-facing WP REST API  functionalities.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_rest_hooks() {

		$plugin_rest = new Rcno_Reviews_Rest_API( $this->get_plugin_name(), $this->get_version() );

		// Registers the book reviews CPT and custom taxonomies with the WordPress REST API.
		$this->loader->add_action( 'init', $plugin_rest, 'rcno_enable_rest_support', 25 );

		$this->loader->add_action( 'rest_api_init', $plugin_rest, 'rcno_register_rest_fields' );
	}


	/**
	 * Register all of the hooks related to the external book data API.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_external_hooks() {

		$goodreads   = new Rcno_Goodreads_API();
		$googlebooks = new Rcno_Reviews_GoogleBooks_API( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $goodreads, 'rcno_enqueue_gr_scripts' );
		//$this->loader->add_action( 'wp_ajax_save_post_meta', $goodreads, 'gr_ajax_save_post_meta' );

		$this->loader->add_action( 'admin_enqueue_scripts', $googlebooks, 'rcno_enqueue_gb_scripts' );
	}


	/**
	 * Register all of the hooks related to the shortcodes functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shortcodes() {

		$plugin_shortcodes = new Rcno_Reviews_Shortcodes( $this->get_plugin_name(), $this->get_version() );

		add_shortcode( 'rcno-reviews', array( $plugin_shortcodes, 'rcno_do_review_shortcode' ) );
		add_shortcode( 'rcno-tax-list', array( $plugin_shortcodes, 'rcno_do_taxlist_shortcode' ) );
		add_shortcode( 'rcno-reviews-index', array( $plugin_shortcodes, 'rcno_do_reviews_index_shortcode' ) );

		$this->loader->add_action( 'media_buttons', $plugin_shortcodes, 'rcno_add_review_button_scr' );
		$this->loader->add_action( 'in_admin_footer', $plugin_shortcodes, 'rcno_load_in_admin_footer_scr' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_shortcodes, 'rcno_load_ajax_scripts_scr' );
		$this->loader->add_action( 'wp_ajax_rcno_get_results', $plugin_shortcodes, 'rcno_process_ajax_scr' );

		$this->loader->add_action( 'media_buttons', $plugin_shortcodes, 'rcno_add_button_scl' );
		$this->loader->add_action( 'in_admin_footer', $plugin_shortcodes, 'rcno_load_in_admin_footer_scl' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_shortcodes, 'rcno_load_ajax_scripts_scl' );
	}

	/**
	 * Register all of the hooks related to the public-facing comment reviews ratings functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_ratings() {

		$public_ratings = new Rcno_Reviews_Public_Rating( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $public_ratings, 'rcno_enqueue_public_ratings_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $public_ratings, 'rcno_enqueue_public_ratings_scripts' );

		$this->loader->add_action( 'wp_ajax_nopriv_rcno_rate_review', $public_ratings, 'rcno_rate_review' );
		$this->loader->add_action( 'wp_ajax_rcno_rate_review', $public_ratings, 'rcno_rate_review' );

		$this->loader->add_action( 'comment_post', $public_ratings, 'rcno_comment_post' );

		$this->loader->add_action( 'comment_form_before_fields', $public_ratings, 'rcno_comment_ratings_form' );
		$this->loader->add_action( 'comment_form_logged_in_after', $public_ratings, 'rcno_comment_ratings_form' );

		$this->loader->add_filter( 'comment_text', $public_ratings, 'display_comment_rating', 9 );
	}

	/**
	 * Register all of the hooks related to the currently reading widget functionality
	 * of the plugin.
	 *
	 * @since    1.1.10
	 * @access   private
	 */
	public function define_currently_reading() {

		$currently_reading = new Rcno_Currently_Reading( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_dashboard_setup', $currently_reading, 'rcno_register_currently_reading_dash_widget' );
		$this->loader->add_action( 'rest_api_init', $currently_reading, 'rcno_currently_rest_routes' );
		$this->loader->add_action( 'admin_enqueue_scripts', $currently_reading, 'rcno_enqueue_currently_reading_scripts' );



	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Rcno_Reviews_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

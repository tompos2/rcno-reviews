<?php
/**
 * This class displays the currently reading book on the site's frontend.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public
 */

/**
 * This class displays the currently reading book.
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Reviews_Currently_Reading extends WP_Widget {

	public $widget_options;
	public $control_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since   1.1.10
	 */
	public function __construct() {

		$this->set_widget_options();

		// Create the widget.
		parent::__construct(
			'rcno-reviews-currently-reading',
			__( 'Rcno Currently Reading', 'rcno-reviews' ),
			$this->widget_options,
			$this->control_options
		);

	}

	private function set_widget_options() {

		// Set up the widget options.
		$this->widget_options = array(
			'classname'   => 'current-reading',
			'description' => esc_html__( 'A widget to display your currently reading progress', 'rcno-reviews' ),
		);

		// Set up the widget control options.
		$this->control_options = array(
			'width'  => 325,
			'height' => 350,
		);

	}

	/**
	 * Enqueue the necessary scripts and styles if the widget is enabled.
	 */
	public function enqueue_scripts() {

	}

	/**
	 * Register our book slider widget and enqueue the relevant scripts.
	 */
	public function rcno_register_currently_reading_widget() {
		if ( (bool) Rcno_Reviews_Option::get_option( 'rcno_show_currently_reading_grid_widget', true ) ) {

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			register_widget( 'Rcno_Reviews_Currently_Reading' );
		}
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 1.1.10
	 *
	 * @param   array   $sidebar
	 * @param   array   $instance
	 * @return  string
	 */
	public function widget( $sidebar, $instance ) {
		extract( $sidebar );

		/* Set the $args for wp_tag_cloud() to the $instance array. */
		$args = $instance;

		/**
		 *  Get and parse the arguments, defaults have been set during saving (hopefully)
		 */
		extract( $args, EXTR_SKIP );

		// If there is an error, stop and return
		if ( ! empty( $instance[ 'error' ] ) ) {
			return;
		}


		// Output the theme's $before_widget wrapper.
		echo $before_widget;

		// Output the title (if we have any).
		if ( $instance && $instance[ 'title' ] ) {
			echo $before_title . sanitize_text_field( $instance[ 'title' ] ) . $after_title;
		}

		$progress = get_option( 'rcno_currently_reading', array() );
		$most_recent = end($progress);

		var_dump($most_recent);

		// Close the theme's widget wrapper.
		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 1.1.10
	 *
	 * @param   array $old_instance
	 * @param   array $new_instance
	 * @return  array
	 */
	public function update( $new_instance, $old_instance ) {
		// Fill current state with old data to be sure we not loose anything
		$instance = $old_instance;

		// Set the instance to the new instance.
		//$instance = $new_instance;

		// Check and sanitize all inputs.
		$instance['title']        = strip_tags( $new_instance['title'] );
		//$instance['review_count'] = absint( $new_instance['review_count'] );
		//$instance['order']        = strip_tags( $new_instance['order'] );


		// and now we return new values and wordpress do all work for you.
		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 1.1.10
	 *
	 * @param   array $instance
	 * @return  void
	 */
	public function form( $instance ) {

		$defaults = array(
			'title'        => '',
		);

		// Merge the user-selected arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Element options.
		$title        = sanitize_text_field( $instance[ 'title' ] );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?> ">
				<?php _e( 'Title (optional)', 'rcno-reviews' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ) ?>"/>
		</p>

		<?php
	}
}

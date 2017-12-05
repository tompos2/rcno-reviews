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
		if ( ! Rcno_Reviews_Option::get_option( 'rcno_show_currently_reading_widget' ) ) {
		    return;
        }

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        register_widget( 'Rcno_Reviews_Currently_Reading' );
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
		$percentage  = isset( $most_recent['num_of_pages'] ) ? round( ( $most_recent['current_page'] / $most_recent['num_of_pages'] ) * 100 ) : 0;
		?>

        <?php if( $most_recent['book_title'] && $most_recent['book_author']) : ?>

            <div class="currently-reading-widget-fe">
                <div class="book-cover">
			        <?php if( $most_recent['book_cover'] ) : ?>
                        <div class="progress-bar-container">
                            <img src="<?php echo $most_recent['book_cover'] ?>" alt="book-cover" />
                            <div class="progress-bar" style="height: <?php echo $percentage;?>%"></div>
                        </div>
				    <?php endif; ?>
                    <span><?php echo sprintf( '%s/%s', $most_recent['current_page'], $most_recent['num_of_pages'] )
                        ?></span>
                </div>

                <div class="book-progress">
                    <h3 class="book-title"><?php echo $most_recent['book_title'] ?></h3>
                    <p class="book-author"><?php echo sprintf( '%s %s', __( 'by', 'rcno-reviews' ),
                            $most_recent['book_author'] ); ?></p>
                    <p class="book-comment"><?php echo $most_recent['progress_comment'] ?></p>
                </div>
            </div>
            <div class="review-coming-soon">
                <h3><?php echo sanitize_text_field( $instance[ 'review_coming_soon' ] ); ?></h3>
            </div>

		<?php else : ?>

            <h3><?php echo sanitize_text_field( $instance[ 'no_currently_reading' ] ); ?></h3>

		<?php endif; ?>

		<?php
            echo $after_widget; // Close the theme's widget wrapper.
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
		$instance[ 'title' ]                = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'no_currently_reading' ] = strip_tags( $new_instance[ 'no_currently_reading' ] );
		$instance[ 'review_coming_soon' ]   = strip_tags( $new_instance[ 'review_coming_soon' ] );
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

		// Element options.
		$title                = ! empty( $instance[ 'title'] ) ? sanitize_text_field( $instance[ 'title' ] ) : '';
		$no_currently_reading = ! empty( $instance[ 'no_currently_reading'] ) ? sanitize_text_field( $instance[ 'no_currently_reading' ] ) : esc_html__( 'No currently reading book right now.', 'rcno-reviews' );
		$review_coming_soon   = ! empty( $instance[ 'review_coming_soon'] ) ? sanitize_text_field( $instance[ 'review_coming_soon' ] ) : esc_html__( 'Review coming soon!', 'rcno-reviews' );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?> ">
				<?php _e( 'Title (optional)', 'rcno-reviews' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>"/>
		</p>

        <p>
            <label for="<?php echo $this->get_field_id( 'no_currently_reading' ); ?> ">
				<?php _e( 'No currently reading book', 'rcno-reviews' ); ?>
            </label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'no_currently_reading' ); ?>"
                   name="<?php echo $this->get_field_name( 'no_currently_reading' ); ?>" value="<?php echo esc_attr( $no_currently_reading ) ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'review_coming_soon' ); ?> ">
				<?php _e( 'Review coming soon', 'rcno-reviews' ); ?>
            </label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'review_coming_soon' ); ?>"
                   name="<?php echo $this->get_field_name( 'review_coming_soon' ); ?>" value="<?php echo esc_attr( $review_coming_soon ) ?>"/>
        </p>

		<?php
	}
}

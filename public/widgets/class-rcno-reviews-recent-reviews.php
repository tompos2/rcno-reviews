<?php
/**
 * This class displays a list of recent book reviews.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public
 */

/**
 * This class displays a list of recent book reviews.
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Reviews_Recent_Reviews extends WP_Widget {

	public $widget_options;
	public $control_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct() {

		$this->set_widget_options();

		// Create the widget.
		parent::__construct(
			'rcno-reviews-recent-reviews',
			__( 'Rcno Recent Reviews', 'rcno-reviews' ),
			$this->widget_options,
			$this->control_options
		);

	}

	private function set_widget_options() {

		// Set up the widget options.
		$this->widget_options = array(
			'classname'   => 'recent-reviews',
			'description' => esc_html__( 'A widget to display the most recent book reviews.', 'rcno-reviews' )
		);

		// Set up the widget control options.
		$this->control_options = array(
			'width'  => 325,
			'height' => 350
		);

	}

	/**
	 * Register our widget, un-register the builtin widget.
	 */
	public function rcno_register_recent_reviews_widget() {
/*		if ( false === (bool) Rcno_Reviews_Option::get_option( 'rcno_reviews_taxonomy_list_widget' ) ) {
			return false;
		}*/
		register_widget( 'Rcno_Reviews_Recent_Reviews' );
		return true;
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 0.6.0
	 */
	function widget( $sidebar, $instance ) {
		extract( $sidebar );

		/* Set the $args for wp_tag_cloud() to the $instance array. */
		$args = $instance;

		/**
		 *  Get and parse the arguments, defaults have been set during saving (hopefully)
		 */
		extract( $args, EXTR_SKIP );

		 // If there is an error, stop and return
		if ( isset( $instance['error'] ) && $instance['error'] ) {
			return;
		}


		// Output the theme's $before_widget wrapper.
		echo $before_widget;

		 // Output the title (if we have any).
		if ( $instance['title'] ) {
			echo $before_title . sanitize_text_field( $instance['title'] ) . $after_title;
		}

		// Begin frontend output.
		$query_args = array(
			'post_type' => 'rcno_review',
			'posts_per_page' => 5,
			'orderby'=> 'rand',
		);
		$recent_reviews = new WP_Query( $query_args );

		if ( $recent_reviews->have_posts() ) {
			while ( $recent_reviews->have_posts() ) {

				$recent_reviews->the_post(); ?>
                <div class="rcno-recent-review">

                    <?php
                        $review_id = get_the_ID();
                        $review = new Rcno_Template_Tags( 'rcno-reviews', '1.0.0' );
                    ?>

                    <div class="widget-book-cover">
                    <?php $review->the_rcno_book_cover( $review_id ); ?>
                    <?php $review->the_rcno_admin_book_rating( $review_id ); ?>
                    </div>
                    <div class="widget-book-details">
                        <a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
                        <?php $review->the_rcno_taxonomy_terms( $review_id, 'rcno_author', true ); ?>
                        <?php echo $review->get_the_rcno_book_meta( $review_id, 'rcno_book_publisher', 'div', true ); ?>
                        <?php echo '<p>' . substr( wp_strip_all_tags( $review->get_the_rcno_book_review_content( $review_id ), true), 0, 150 ) . '</p>'; ?>
                        <div class="clear"></div>
                    </div>

                </div>

				<?php
			}
		}

		wp_reset_postdata();

		// Close the theme's widget wrapper.
		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 0.8.0
	 */
	function update( $new_instance, $old_instance ) {
		// Fill current state with old data to be sure we not loose anything
		$instance = $old_instance;

		// Set the instance to the new instance.
		//$instance = $new_instance;

		// Check and sanitize all inputs.
		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['review_count']     = absint( $new_instance['review_count'] );


		// and now we return new values and wordpress do all work for you
		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 0.8.0
	 */
	function form( $instance ) {
		/* Set up the default form values. */
		$defaults = array(
			'title'        => '',
			'review_count' => 5,
		);

		/* Merge the user-selected arguments with the defaults. */
		$instance = wp_parse_args( (array) $instance, $defaults );

		/* element options. */
		$title        = sanitize_text_field( $instance['title'] );
		$review_count = sanitize_key( $instance['review_count'] );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?> ">
				<?php _e( 'Title (optional)', 'rcno-reviews' ); ?>
			</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ) ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'review_count' ); ?>">
				<?php _e( 'Number of Reviews:', 'rcno-reviews' ); ?>
			</label>
			<input type="number" class="widefat" id="<?php echo $this->get_field_id( 'review_count' ); ?>"
			       name="<?php echo $this->get_field_name( 'review_count' ); ?>" value="<?php echo esc_attr( $review_count ); ?>"
			       style="width:50px;" min="1" max="100" pattern="[0-9]"/>
		</p>

		<?php
	}
}
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
	 * @since   1.0.0
	 * @version 1.0.0
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
			'description' => esc_html__( 'A widget to display the most recent book reviews.', 'rcno-reviews' ),
		);

		// Set up the widget control options.
		$this->control_options = array(
			'width'  => 325,
			'height' => 350,
		);
	}

	/**
	 * Register our widget, un-register the builtin widget.
	 */
	public function rcno_register_recent_reviews_widget() {
		if ( ! Rcno_Reviews_Option::get_option( 'rcno_show_recent_reviews_widget' ) ) {
			return false;
		}
		register_widget( 'Rcno_Reviews_Recent_Reviews' );

		return true;
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @uses \mb_substr()
	 * @see https://stackoverflow.com/questions/9087502/php-substr-function-with-utf-8-leaves-marks-at-the-end
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 * @since 0.6.0
	 */
	public function widget( $args, $instance ) {

		// If there is an error, stop and return.
		if ( ! empty( $instance['error'] ) ) {
			return;
		}

		// Output the theme's $before_widget wrapper.
		echo $args['before_widget'];

		// Output the title (if we have any).
		if ( isset( $instance['title'] ) ) {
			echo $args['before_title'] . sanitize_text_field( $instance['title'] ) . $args['after_title'];
		}

		// Begin frontend output.
		$char_count     = isset( $instance['char_count'] ) ? (int) $instance['char_count'] : 150;
		$query_args     = array(
			'post_type'      => ( isset( $instance['regular_posts'] ) && true === $instance['regular_posts'] ) ? array( 'post', 'rcno_review' ) : 'rcno_review',
			'posts_per_page' => isset( $instance['review_count'] ) ? (int) $instance['review_count'] : 5,
		);
		$recent_reviews = new WP_Query( $query_args );

		if ( $recent_reviews->have_posts() ) {
			while ( $recent_reviews->have_posts() ) {

				$recent_reviews->the_post(); ?>
				<div class="rcno-recent-review">

					<?php
					$review_id = get_the_ID();
					$review    = new Rcno_Template_Tags( 'rcno-reviews', '1.0.0' );
					?>

					<div class="widget-book-cover">
						<?php $review->the_rcno_book_cover( $review_id, 'rcno-book-cover-sm' ); ?>
						<?php $review->the_rcno_admin_book_rating( $review_id ); ?>
					</div>
					<div class="widget-book-details">
						<a href="<?php the_permalink(); ?>"><h3><?php the_title(); ?></h3></a>
						<?php $review->the_rcno_taxonomy_terms( $review_id, 'rcno_author', true ); ?>
						<?php echo $review->get_the_rcno_book_meta( $review_id, 'rcno_book_publisher', 'div', true ); ?>
						<?php echo '<p>' . mb_substr( wp_strip_all_tags( strip_shortcodes( $review->get_the_rcno_book_review_content( $review_id ) ), true ), 0, $char_count ) . '</p>'; ?>
						<div class="clear"></div>
					</div>

				</div>

				<?php
			}		}

		wp_reset_postdata();

		// Close the theme's widget wrapper.
		echo $args['after_widget'];
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 0.8.0
	 *
	 * @param object $new_instance
	 * @param object $old_instance
	 *
	 * @return object
	 */
	public function update( $new_instance, $old_instance ) {

		// Fill current state with old data to be sure we not loose anything
		$instance = $old_instance;

		// Check and sanitize all inputs.
		$instance['title']         = strip_tags( $new_instance['title'] );
		$instance['review_count']  = absint( $new_instance['review_count'] );
		$instance['char_count']    = isset( $new_instance['char_count'] ) ? (int) $new_instance['char_count'] : 150;
		$instance['regular_posts'] = isset( $new_instance['regular_posts'] ) ? (bool) $new_instance['regular_posts'] : false;

		// Now we return new values and WordPress do all work for you.
		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 0.8.0
	 *
	 * @param object $instance
	 *
	 * @return void
	 *
	 */
	public function form( $instance ) {
		// Set up the default form values.
		$defaults = array(
			'title'         => '',
			'review_count'  => 5,
			'char_count'    => 150,
			'regular_posts' => false,
		);

		// Merge the user-selected arguments with the defaults.
		$instance = wp_parse_args( $instance, $defaults );

		// Element options.
		$title         = sanitize_text_field( $instance['title'] );
		$review_count  = (int) $instance['review_count'];
		$char_count    = (int) $instance['char_count'];
		$regular_posts = (bool) $instance['regular_posts'];

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?> ">
				<?php _e( 'Title (optional)', 'rcno-reviews' ); ?>:
			</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
				name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'review_count' ); ?>">
				<?php _e( 'Number of Reviews', 'rcno-reviews' ); ?>:
			</label>
			<input type="number" class="widefat" id="<?php echo $this->get_field_id( 'review_count' ); ?>"
				name="<?php echo $this->get_field_name( 'review_count' ); ?>"
				value="<?php echo esc_attr( $review_count ); ?>"
				style="width:50px;" min="1" max="100" pattern="[0-9]"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'char_count' ); ?>">
				<?php _e( 'Character count', 'rcno-reviews' ); ?>:
			</label>
			<input type="number" class="widefat" id="<?php echo $this->get_field_id( 'char_count' ); ?>"
				   name="<?php echo $this->get_field_name( 'char_count' ); ?>"
				   value="<?php echo esc_attr( $char_count ); ?>"
				   style="width:50px;" min="50" max="1000" pattern="[0-9]"/>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'regular_posts' ); ?>">
				<?php _e( 'Show regular posts', 'rcno-reviews' ); ?>:
			</label>
			<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'regular_posts' ); ?>"
				name="<?php echo $this->get_field_name( 'regular_posts' ); ?>"
				value="1" <?php checked( '1', $regular_posts ); ?> />
		</p>

		<?php
	}
}

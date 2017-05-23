<?php
/**
 * The public-facing comments rating system of the plugin.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public
 */

/**
 * The public-facing comments rating system of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/public
 * @author     wzyMedia <wzy@outlook.com>
 */
class Rcno_Reviews_Public_Rating {

	public static $rating;
	public static $comment_count;
	public static $min_rating;
	public static $max_rating;

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	/**
	 * Enqueues the public facing stylesheet for the comment ratings.
	 */
	public function rcno_enqueue_public_ratings_styles() {

		wp_enqueue_style( 'rcno-public-ratings-styles',  plugin_dir_url( __FILE__ ) . 'css/rcno-reviews-public-ratings.css', array(), $this->version, 'all' );

	}


	/**
	 * Enqueues the public facing scripts for the comment ratings.
	 */
	public function rcno_enqueue_public_ratings_scripts() {

		wp_enqueue_script( 'rcno-public-ratings-scripts',  plugin_dir_url( __FILE__ ) . 'js/rcno-reviews-public-ratings-script.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( 'rcno-public-ratings-scripts', 'rcno_public_object',
			array(
				'public_ajax_url' => admin_url( 'admin-ajax.php' ),
				'public_ratings_nonce' => wp_create_nonce( 'rcno-ajax-public-ratings-nonce' ),
			) );

	}


	/**
	 * Saves the comment rating data on the 'comment_post' WP hook
	 * @param $id
	 * @return void
	 */
	public function rcno_comment_post( $id ) {

		if ( ! isset( $_POST['comment_karma'] ) ) {
			return;
		}

		$comment_karma = (int) $_POST['comment_karma'];

		if ( $comment_karma > 5 ) {
			$comment_karma = 5;
		} elseif ( $comment_karma <= 0 ) {
			$comment_karma = 1;
		} else {
			$comment_karma = absint( $comment_karma );
		}

		/*wp_update_comment( array(
			'comment_karma' => $comment_karma,
			'comment_ID' => $id,
		) );*/

		if ( ! update_comment_meta( $id, 'rcno_review_comment_rating', $comment_karma ) ) {
			die('Dead!');
		}

	}


	/**
	 * Display the star rating inside the comment form.
	 *
	 * @return string
	 */
	public function rcno_comment_ratings_form() {
		$star = '<li class="empty"><span class="l"></span><span class="r"></span></li>';
		return printf(
			'<div class="rating-container"><p class="rating-label">%s</p><ul class="rating form-rating">%s</ul></div>',
			__( 'Rate this review', 'rcno-reviews' ),
			str_repeat( $star, 5 )
		);
	}


	/**
	 * If a user submits a comment without leaving a rating
	 * use AJAX to send rating.
	 * @return void
	 */
	public function rcno_rate_review() {

		check_ajax_referer( 'rcno-ajax-public-ratings-nonce', 'security_nonce', true );

		$user = '';

		$comment_ID      = (int) $_POST['comment_ID'];
		$comment_post_ID = (int) $_POST['comment_post_ID'];
		$comment_karma   = (int) $_POST['rating'];

		$comment_author_cookie     = $_COOKIE[ 'comment_author_' . COOKIEHASH ];
		$comment_author_e_cookie   = $_COOKIE[ 'comment_author_email_' . COOKIEHASH ];
		$comment_author_url_cookie = $_COOKIE[ 'comment_author_url_' . COOKIEHASH ];


		if ( is_user_logged_in() ) {
			$user               = wp_get_current_user();
			$user->display_name = $user->user_login;
		}

		if ( is_user_logged_in() ) {
			$comment_author = esc_sql( $user->display_name );
		} elseif ( null !== $comment_author_cookie ) {
			$comment_author = $comment_author_cookie;
		} else {
			$comment_author = '';
		}

		if ( is_user_logged_in() ) {
			$comment_author_email = esc_sql( $user->user_email );
		} elseif ( null !== $comment_author_e_cookie ) {
			$comment_author_email = $comment_author_e_cookie;
		} else {
			$comment_author_email = '';
		}

		if ( is_user_logged_in() ) {
			$comment_author_url = esc_sql( $user->user_url );
		} elseif ( null !== $comment_author_url_cookie ) {
			$comment_author_url = $comment_author_url_cookie;
		} else {
			$comment_author_url = '';
		}

		if ( empty( $comment_author ) || empty( $comment_author_email ) ) {
			wp_die( __( "I don't know who you are", 'rcno-reviews' ) );
		}

		$comment_approved = 1;

		if ( $comment_ID > 0 ) {
			wp_update_comment( array(
				'comment_post_ID' => $comment_post_ID,
				'comment_author' => $comment_author,
				'comment_author_email' => $comment_author_email,
				'comment_author_url' => $comment_author_url,
				'comment_karma' => $comment_karma,
				'comment_approved' => $comment_approved,
				'comment_ID' => $comment_ID,
			) );
		} else {
			wp_update_comment( array(
				'comment_post_ID' => $comment_post_ID,
				'comment_author' => $comment_author,
				'comment_author_email' => $comment_author_email,
				'comment_author_url' => $comment_author_url,
				'comment_karma' => $comment_karma,
				'comment_approved' => $comment_approved,
			) );
		}

		wp_die();
	}


	/**
	 * Returns the current user either by 'wp_get_current_user' or stored cookie
	 *
	 * @return string
	 */
	public static function rcno_current_user() {
		global $current_user;
		if ( is_user_logged_in() ) {
			wp_get_current_user();

			return $current_user->user_login;
		} else {
			return $_COOKIE[ 'comment_author_' . COOKIEHASH ];
		}
	}


	/**
	 * Is this user known.
	 *
	 * @return bool
	 */
	public static function rcno_ratings_user_is_known() {
		return is_user_logged_in() || ! empty( $_COOKIE[ 'comment_author_' . COOKIEHASH ] );
	}


	/**
	 * Calculates the raw review score from the comment metadata.
	 *
	 * @param string $query
	 * @return bool|float|int|mixed
	 */
	public static function rcno_rating_info( $query ) {

		// Get the review ID.
		if ( isset( $GLOBALS['review_id'] ) && $GLOBALS['review_id'] !== '' ) {
			$review_id = $GLOBALS['review_id'];
		} else {
			$review_id = get_post()->ID;
		}

		switch ( $query ) {

			case 'avg':
				$avg = self::count_ratings_info( $review_id );
				if( null !== $avg ) {
					return self::$rating = array_sum( $avg ) / count( $avg );
				}
				return 0;
				break;

			case 'count':
				$count = self::count_ratings_info( $review_id );
				if( null !== $count ) {
					return self::$comment_count = (int) count( $count );
				}
				return 0;
				break;

			case 'min':
				$min = self::count_ratings_info( $review_id );
				if( null !== $min ) {
					return self::$min_rating = (int) min( $min );
				}
				return 0;
				break;

			case 'max':
				$max = self::count_ratings_info( $review_id );
				if( null !== $max ) {
					return self::$max_rating = (int) max( $max );
				}
				return 0;
				break;

			default:
				return false;
		}
	}


	/**
	 * Does the retrieval of public comment scores from the comment meta table.
	 *
	 * @param $review_id
	 * @return array
	 */
	private static function count_ratings_info( $review_id ) {

		$comments = get_comments( array(
			'post_id'   => $review_id,
			'meta_key'  => 'rcno_review_comment_rating',
		) );

		$comment_ids = array();
		$karma_scores = array();

		foreach( $comments as $comment ) {
			$comment_ids[] = $comment->comment_ID;
		}

		foreach( $comment_ids as $value ){
			$karma_scores[] = get_comment_meta( $value, 'rcno_review_comment_rating', true );
		}

		return $karma_scores ? (array) $karma_scores : null;
	}


	public static function rate_calculate( $id = 0, $is_comment = false ) {

		$post_id     = (int) $id > 0 ? $id : get_the_ID();
		$previous_id = 0;

		if ( $is_comment ) {
			$c            = $GLOBALS['comment'];
			self::$rating = (int) self::rcno_rating_info( 'avg' );
			$previous_id  = (int) $c->comment_ID;
		} else {
			self::$rating = self::rcno_rating_info( 'avg' );
		}

		self::$rating = number_format( self::$rating, 1, '.', '' );

		if ( self::$rating === 0.0 ) {
			$coerced_rating = 0.0;
		} elseif ( ( self::$rating * 10 ) % 5 !== 0 ) {
			$coerced_rating = round( self::$rating * 2.0, 0 ) / 2.0;
		} else {
			$coerced_rating = self::$rating;
		}

		$stars   = array( 0, 1, 2, 3, 4, 5, 6 );
		$classes = array( 'rating' );
		$format  = '<li class="%s"><span class="l"></span><span class="r"></span></li>';

		for ( $i = 1; $i <= 5; $i ++ ) {
			if ( $i <= $coerced_rating ) {
				$stars[ $i ] = sprintf( $format, 'whole' );
			} elseif ( $i - 0.5 === $coerced_rating ) {
				$stars[ $i ] = sprintf( $format, 'half' );
			} else {
				$stars[ $i ] = sprintf( $format, 'empty' );
			}
		}

		$user_meta = array();

		if ( self::rcno_ratings_user_is_known() ) {
			if ( $is_comment && ( self::$rating === 0 && ( self::rcno_current_user() === $c->comment_author ) ) ) {
				$classes[] = 'needs-rating';
			}
			$user_meta[] = sprintf( 'data-id="%d"', $post_id );
			if ( $previous_id > 0 ) {
				$user_meta[] = sprintf( 'data-comment-id="%d"', $previous_id );
			}
		}

		if ( self::$rating !== 0.0 ) {
			$stars[0] = sprintf(
				'<div class="star-ratings"><ul data-rating="%01.1f" class="%s" %s>',
				self::$rating,
				implode( ' ', $classes ),
				implode( ' ', $user_meta )
			);
			$stars[6] = '</ul></div>';
		}

		$stars = implode( '', $stars );

		return $stars;
	}

	/**
	 * Displays the recipe rating
	 * @param int $id
	 */
	public static function the_rating( $id = 0 ) {
		echo self::rate_calculate( $id );
	}

	/**
	 * Displays the comment rating
	 * @return string
	 */
	public static function the_comment_rating() {
		global $comment;
		return self::rate_calculate( $comment->comment_post_ID, true );
	}


	/**
	 * Add the star rating above the displayed comment.
	 * @param $content
	 * @return string
	 */
	public function display_comment_rating( $content ) {
		$out = '';
		$out .= self::the_comment_rating();
		$out .= $content;
		return $out;
	}

}
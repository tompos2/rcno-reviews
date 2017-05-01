<?php
/**
 * The ISBN metabox view of the plugin.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin/views
 */

/**
 * @TODO: create and check nonce in core!
 */
wp_nonce_field( 'rcno_save_review_meta', 'rcno_nonce' );

$isbn = get_post_meta( $review->ID, 'rcno_book_isbn', true );

?>

<div class="book-isbn-metabox">
	<input type="text" name="rcno_book_isbn" id="rcno_book_isbn" size="20" value="<?php echo sanitize_text_field( $isbn ); ?>" />
	<button href="#" class="button rpr-isbn-fetch" title="">Fetch</button>
</div>
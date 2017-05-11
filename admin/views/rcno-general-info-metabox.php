<?php
/**
 * The general book info metabox view of the plugin.
 *
 * @link       https://wzymedia.com
 * @since      1.0.0
 *
 * @package    Rcno_Reviews
 * @subpackage Rcno_Reviews/admin/views
 */


$publisher      = get_post_meta( $review->ID, 'rcno_book_publisher', true );
$pub_date       = get_post_meta( $review->ID, 'rcno_book_pub_date', true );
$pub_format     = get_post_meta( $review->ID, 'rcno_book_pub_format', true );
$page_count     = get_post_meta( $review->ID, 'rcno_book_page_count', true );
$gr_review      = get_post_meta( $review->ID, 'rcno_book_gr_review', true );
$gr_id          = get_post_meta( $review->ID, 'rcno_book_gr_id', true );
$isbn13         = get_post_meta( $review->ID, 'rcno_book_isbn13', true );
$asin           = get_post_meta( $review->ID, 'rcno_book_asin', true );
$gr_url         = get_post_meta( $review->ID, 'rcno_book_gr_url', true );

?>

<div class="book-general-info-metabox">

	<div class="publisher-info">
		<label class="rcno_book_publisher_label" for="rcno_book_publisher"><?php _e( 'Publisher', 'rcno-reviews' ) ?></label>
		<input type="text" name="rcno_book_publisher" id="rcno_book_publisher" size="20" value="<?php echo sanitize_text_field( $publisher ); ?>" />
		<?php wp_nonce_field( 'rcno_save_book_info_metadata', 'rcno_general_info_nonce' ); ?>
	</div>

	<div class="publication-date">
		<label class="rcno_book_publication_date" for="rcno_book_pub_date"><?php _e( 'Publication Date', 'rcno-reviews' ) ?></label>
		<input type="text" name="rcno_book_pub_date" id="rcno_book_pub_date" size="20" value="<?php echo sanitize_text_field( $pub_date ); ?>" />
		<?php wp_nonce_field( 'rcno_save_book_pub_date_metadata', 'rcno_general_pub_date_nonce' ); ?>
	</div>

	<div class="publication-format">
		<label class="rcno_book_publication_format" for="rcno_book_pub_format"><?php _e( 'Publication Format', 'rcno-reviews' ) ?></label>
		<input type="text" name="rcno_book_pub_format" id="rcno_book_pub_format" size="20" value="<?php echo sanitize_text_field( $pub_format ); ?>" />
		<?php wp_nonce_field( 'rcno_save_book_pub_format_metadata', 'rcno_general_pub_format_nonce' ); ?>
	</div>

	<div class="publication-page-count">
		<label class="rcno_book_page_count" for="rcno_book_page_count"><?php _e( 'Page Count', 'rcno-reviews' ) ?></label>
		<input type="text" name="rcno_book_page_count" id="rcno_book_page_count" size="20" value="<?php echo sanitize_text_field( $page_count ); ?>" />
		<?php wp_nonce_field( 'rcno_save_book_page_count_metadata', 'rcno_general_page_count_nonce' ); ?>
	</div>

    <div class="publication-gr-review">
        <label class="rcno_book_gr_review" for="rcno_book_gr_review"><?php _e( 'GoodReads Average', 'rcno-reviews' ) ?></label>
        <input type="text" name="rcno_book_gr_review" id="rcno_book_gr_review" size="20" value="<?php echo sanitize_text_field( $gr_review ); ?>" />
		<?php wp_nonce_field( 'rcno_save_book_gr_review_metadata', 'rcno_general_gr_review_nonce' ); ?>
    </div>

    <div class="publication-gr-id">
        <label class="rcno_book_gr_id" for="rcno_book_gr_id"><?php _e( 'GoodReads ID', 'rcno-reviews' ) ?></label>
        <input type="text" name="rcno_book_gr_id" id="rcno_book_gr_id" size="20" value="<?php echo sanitize_text_field( $gr_id ); ?>" />
		<?php wp_nonce_field( 'rcno_save_book_gr_id_metadata', 'rcno_general_gr_id_nonce' ); ?>
    </div>

    <div class="publication-isbn13">
        <label class="rcno_book_isbn13" for="rcno_book_isbn13"><?php _e( 'ISBN13', 'rcno-reviews' ) ?></label>
        <input type="text" name="rcno_book_isbn13" id="rcno_book_isbn13" size="20" value="<?php echo sanitize_text_field( $isbn13 ); ?>" />
		<?php wp_nonce_field( 'rcno_save_book_isbn13_metadata', 'rcno_general_isbn13_nonce' ); ?>
    </div>

    <div class="publication-asin">
        <label class="rcno_book_asin" for="rcno_book_asin"><?php _e( 'ASIN', 'rcno-reviews' ) ?></label>
        <input type="text" name="rcno_book_asin" id="rcno_book_asin" size="20" value="<?php echo sanitize_text_field( $asin ); ?>" />
		<?php wp_nonce_field( 'rcno_save_book_asin_metadata', 'rcno_general_asin_nonce' ); ?>
    </div>

    <div class="publication-gr-url">
        <label class="rcno_book_gr_url" for="rcno_book_gr_url"><?php _e( 'GoodReads URL', 'rcno-reviews' ) ?></label>
        <input type="text" name="rcno_book_gr_url" id="rcno_book_gr_url" size="20" value="<?php echo sanitize_text_field( $gr_url ); ?>" />
		<?php wp_nonce_field( 'rcno_save_book_gr_url_metadata', 'rcno_general_gr_url_nonce' ); ?>
    </div>

</div>

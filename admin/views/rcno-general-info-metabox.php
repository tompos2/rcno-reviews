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



?>

<div class="book-general-info-metaboxes">

    <div class="book-general-metabox-1">

	    <?php if ( true === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_publisher' ) ) : ?>
            <div class="book-title">
			    <?php $book_title= get_post_meta( $review->ID, 'rcno_book_title', true ); ?>
                <label class="rcno_book_title_label" for="rcno_book_title"><?php _e( 'Title', 'rcno-reviews' ) ?></label>
                <input type="text" name="rcno_book_title" id="rcno_book_title" value="<?php echo sanitize_text_field( $book_title ); ?>" />
	            <?php wp_nonce_field( 'rcno_save_book_title_metadata', 'rcno_general_title_nonce' ); ?>
            </div>
	    <?php endif; ?>

	    <?php if ( true === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_publisher' ) ) : ?>
            <div class="publisher-info">
                <?php $publisher = get_post_meta( $review->ID, 'rcno_book_publisher', true ); ?>
                <label class="rcno_book_publisher_label" for="rcno_book_publisher"><?php _e( 'Publisher', 'rcno-reviews' ) ?></label>
                <input type="text" name="rcno_book_publisher" id="rcno_book_publisher" size="20" value="<?php echo sanitize_text_field( $publisher ); ?>" />
                <?php wp_nonce_field( 'rcno_save_book_info_metadata', 'rcno_general_info_nonce' ); ?>
            </div>
	    <?php endif; ?>

	    <?php if ( true === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_pub_date' ) ) : ?>
            <div class="publication-date">
                <?php $pub_date = get_post_meta( $review->ID, 'rcno_book_pub_date', true ); ?>
                <label class="rcno_book_publication_date" for="rcno_book_pub_date"><?php _e( 'Publication Date', 'rcno-reviews' ) ?></label>
                <input type="text" name="rcno_book_pub_date" id="rcno_book_pub_date" size="20" value="<?php echo sanitize_text_field( $pub_date ); ?>" />
                <?php wp_nonce_field( 'rcno_save_book_pub_date_metadata', 'rcno_general_pub_date_nonce' ); ?>
            </div>
	    <?php endif; ?>

	    <?php if ( true === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_pub_format' ) ) : ?>
            <div class="publication-format">
                <?php $pub_format = get_post_meta( $review->ID, 'rcno_book_pub_format', true ); ?>
                <label class="rcno_book_publication_format" for="rcno_book_pub_format"><?php _e( 'Publication Format', 'rcno-reviews' ) ?></label>
                <input type="text" name="rcno_book_pub_format" id="rcno_book_pub_format" size="20" value="<?php echo sanitize_text_field( $pub_format ); ?>" />
                <?php wp_nonce_field( 'rcno_save_book_pub_format_metadata', 'rcno_general_pub_format_nonce' ); ?>
            </div>
	    <?php endif; ?>

	    <?php if ( true === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_pub_edition' ) ) : ?>
            <div class="publication-edition">
                <?php $pub_edition = get_post_meta( $review->ID, 'rcno_book_pub_edition', true ); ?>
                <label class="rcno_book_publication_edition" for="rcno_book_pub_edition"><?php _e( 'Edition', 'rcno-reviews' ) ?></label>
                <input type="text" name="rcno_book_pub_edition" id="rcno_book_pub_edition" size="20" value="<?php echo sanitize_text_field( $pub_edition ); ?>" />
                <?php wp_nonce_field( 'rcno_save_book_pub_edition_metadata', 'rcno_general_pub_edition_nonce' ); ?>
            </div>
	    <?php endif; ?>

	    <?php if ( true === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_page_count' ) ) : ?>
            <div class="publication-page-count">
                <?php $page_count = get_post_meta( $review->ID, 'rcno_book_page_count', true ); ?>
                <label class="rcno_book_page_count" for="rcno_book_page_count"><?php _e( 'Page Count', 'rcno-reviews' ) ?></label>
                <input type="text" name="rcno_book_page_count" id="rcno_book_page_count" size="20" value="<?php echo sanitize_text_field( $page_count ); ?>" />
                <?php wp_nonce_field( 'rcno_save_book_page_count_metadata', 'rcno_general_page_count_nonce' ); ?>
            </div>
	    <?php endif; ?>

    </div>

    <div class="book-general-metabox-2">

	    <?php if ( true === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_gr_id' ) ) : ?>
            <div class="publication-gr-id">
                <?php $gr_id = get_post_meta( $review->ID, 'rcno_book_gr_id', true ); ?>
                <label class="rcno_book_gr_id" for="rcno_book_gr_id"><?php _e( 'GoodReads ID', 'rcno-reviews' ) ?></label>
                <input type="text" name="rcno_book_gr_id" id="rcno_book_gr_id" size="20" value="<?php echo sanitize_text_field( $gr_id ); ?>" />
                <?php wp_nonce_field( 'rcno_save_book_gr_id_metadata', 'rcno_general_gr_id_nonce' ); ?>
            </div>
	    <?php endif; ?>

	    <?php if ( true === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_isbn13' ) ) : ?>
            <div class="publication-isbn13">
                <?php $isbn13 = get_post_meta( $review->ID, 'rcno_book_isbn13', true ); ?>
                <label class="rcno_book_isbn13" for="rcno_book_isbn13"><?php _e( 'ISBN13', 'rcno-reviews' ) ?></label>
                <input type="text" name="rcno_book_isbn13" id="rcno_book_isbn13" size="20" value="<?php echo sanitize_text_field( $isbn13 ); ?>" />
                <?php wp_nonce_field( 'rcno_save_book_isbn13_metadata', 'rcno_general_isbn13_nonce' ); ?>
            </div>
	    <?php endif; ?>

        <?php if ( true === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_asin' ) ) : ?>
            <div class="publication-asin">
                <?php $asin = get_post_meta( $review->ID, 'rcno_book_asin', true ); ?>
                <label class="rcno_book_asin" for="rcno_book_asin"><?php _e( 'ASIN', 'rcno-reviews' ) ?></label>
                <input type="text" name="rcno_book_asin" id="rcno_book_asin" size="20" value="<?php echo sanitize_text_field( $asin ); ?>" />
                <?php wp_nonce_field( 'rcno_save_book_asin_metadata', 'rcno_general_asin_nonce' ); ?>
            </div>
        <?php endif; ?>

	    <?php if ( true === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_gr_rating' ) ) : ?>
            <div class="publication-gr-review">
			    <?php $gr_review = get_post_meta( $review->ID, 'rcno_book_gr_review', true ); ?>
                <label class="rcno_book_gr_review" for="rcno_book_gr_review"><?php _e( 'Average', 'rcno-reviews' ) ?></label>
                <input type="text" name="rcno_book_gr_review" id="rcno_book_gr_review" size="20" value="<?php echo sanitize_text_field( $gr_review ); ?>" />
			    <?php wp_nonce_field( 'rcno_save_book_gr_review_metadata', 'rcno_general_gr_review_nonce' ); ?>
            </div>
	    <?php endif; ?>

	    <?php if ( true === (bool) Rcno_Reviews_Option::get_option( 'rcno_show_gr_url' ) ) : ?>
            <div class="publication-gr-url">
                <?php $gr_url = get_post_meta( $review->ID, 'rcno_book_gr_url', true ); ?>
                <label class="rcno_book_gr_url" for="rcno_book_gr_url"><?php _e( 'GoodReads URL', 'rcno-reviews' ) ?></label>
                <input type="text" name="rcno_book_gr_url" id="rcno_book_gr_url" size="20" value="<?php echo sanitize_text_field( $gr_url ); ?>" />
                <?php wp_nonce_field( 'rcno_save_book_gr_url_metadata', 'rcno_general_gr_url_nonce' ); ?>
            </div>
	    <?php endif; ?>

    </div>

</div>

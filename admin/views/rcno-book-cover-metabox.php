<?php { ?>

	<?php
	$cover_src   = esc_attr( get_post_meta( $review->ID, 'rcno_reviews_book_cover_src', true ) );
	$cover_alt   = esc_attr( get_post_meta( $review->ID, 'rcno_reviews_book_cover_alt', true ) );
	$cover_title = esc_attr( get_post_meta( $review->ID, 'rcno_reviews_book_cover_title', true ) );
	$cover_id    = absint( get_post_meta( $review->ID, 'rcno_reviews_book_cover_id', true ) );
	?>

    <div id="rcno-reviews-book-cover-container" class="hidden rcno-reviews-book-cover-container">
        <img src="<?php echo $cover_src; ?>"
             alt="<?php echo $cover_alt; ?>"
             title="<?php echo $cover_title; ?>"
             data-id="<?php echo $cover_id; ?>"
             style="max-width: 100%"/>
    </div>

    <p class="hide-if-no-js">
        <a title="<?php _e( 'Add Book Cover', 'rcno-reviews' ) ?>" href="javascript:" id="rcno-add-book-cover" class="rcno-reviews-book-cover">
			<?php _e( 'Add Book Cover', 'rcno-reviews' ) ?>
        </a>
    </p><!-- .hide-if-no-js -->

    <p class="hide-if-no-js hidden">
        <a title="<?php _e( 'Remove Book Cover', 'rcno-reviews' ) ?>" href="javascript:" id="rcno-remove-book-cover">
			<?php _e( 'Remove Book Cover', 'rcno-reviews' ) ?>
        </a>
    </p><!-- .hide-if-no-js -->

    <input type="hidden" id="rcno-reviews-book-cover-src" name="rcno_reviews_book_cover_src"
           value="<?php echo $cover_src; ?>"/>
    <input type="hidden" id="rcno-reviews-book-cover-alt" name="rcno_reviews_book_cover_alt"
           value="<?php echo $cover_alt; ?>"/>
    <input type="hidden" id="rcno-reviews-book-cover-title" name="rcno_reviews_book_cover_title"
           value="<?php echo $cover_title; ?>"/>
    <input type="hidden" id="rcno-reviews-book-cover-id" name="rcno_reviews_book_cover_id"
           value="<?php echo $cover_id; ?>"/>


<?php } ?>
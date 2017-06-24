<?php { ?>

	<?php
	$cover_src   = get_post_meta( $review->ID, 'rcno_reviews_book_cover_src', true );
	$cover_alt   = get_post_meta( $review->ID, 'rcno_reviews_book_cover_alt', true );
	$cover_title = get_post_meta( $review->ID, 'rcno_reviews_book_cover_title', true );
	?>

    <div id="rcno-reviews-book-cover-container" class="hidden">
        <img src="<?php echo $cover_src; ?>"
             alt="<?php echo $cover_alt; ?>"
             title="<?php echo $cover_title; ?>"
             style="max-width: 100%"/>
    </div>

    <p class="hide-if-no-js">
        <a href="javascript:" id="rcno-add-book-cover" class="rcno-reviews-book-cover">
			<?php _e( 'Add Book Cover', 'rcno-reviews' ) ?>
        </a>
    </p><!-- .hide-if-no-js -->

    <p class="hide-if-no-js hidden">
        <a title="Remove Footer Image" href="javascript:" id="rcno-remove-book-cover">
			<?php _e( 'Remove Book Cover', 'rcno-reviews' ) ?>
        </a>
    </p><!-- .hide-if-no-js -->

    <input type="hidden" id="rcno-reviews-book-cover-src" name="rcno_reviews_book_cover_src"
           value="<?php echo $cover_src; ?>"/>
    <input type="hidden" id="rcno-reviews-book-cover-alt" name="rcno_reviews_book_cover_alt"
           value="<?php echo $cover_alt; ?>"/>
    <input type="hidden" id="rcno-reviews-book-cover-title" name="rcno_reviews_book_cover_title"
           value="<?php echo $cover_title; ?>"/>


<?php } ?>
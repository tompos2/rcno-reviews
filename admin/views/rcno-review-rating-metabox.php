<?php
$book_rating = get_post_meta( $review->ID, 'rcno_admin_rating', true );
?>

<?php wp_nonce_field( 'rcno_review_rating_nonce', 'rcno_review_rating_nonce' ); ?>
<div id="rcno-star-rating" class="rcno-star-rating"></div>
<input type="hidden" id="rcno_admin_rating" name="rcno_admin_rating" value="<?php echo ( ! empty( $book_rating ) ? esc_attr( $book_rating ) : '' ); ?> "/>

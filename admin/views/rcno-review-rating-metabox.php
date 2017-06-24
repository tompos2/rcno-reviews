<?php
$book_rating = get_post_meta( $review->ID, 'rcno_admin_rating', true );
?>

<div class="rcno-rate">
    <input type="radio" id="star5" name="rcno_admin_rating" value="5" <?php checked( $book_rating, 5, true ); ?> />
    <label for="star5" title="5 stars">5 stars</label>

    <input type="radio" id="star4" name="rcno_admin_rating" value="4" <?php checked( $book_rating, 4, true ); ?> />
    <label for="star4" title="4 stars">4 stars</label>

    <input type="radio" id="star3" name="rcno_admin_rating" value="3" <?php checked( $book_rating, 3, true ); ?> />
    <label for="star3" title="3 stars">3 stars</label>

    <input type="radio" id="star2" name="rcno_admin_rating" value="2" <?php checked( $book_rating, 2, true ); ?> />
    <label for="star2" title="2 stars">2 stars</label>

    <input type="radio" id="star1" name="rcno_admin_rating" value="1" <?php checked( $book_rating, 1, true ); ?> />
    <label for="star1" title="1 star">1 star</label>
</div>

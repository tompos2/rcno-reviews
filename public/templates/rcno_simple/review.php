<?php
/*
Author: wzyMedia
Author Mail: kemory@wzymedia.com
Author URL: https://wzymedia.com
Layout Name: Rcno Simple
Version: 1.0.0
Description: The simple book review layout.
*/


// Get the review ID.
if ( isset( $GLOBALS['review_id'] ) && $GLOBALS['review_id'] !== '' ) {
	$review_id = $GLOBALS['review_id'];
} else {
	$review_id = get_post()->ID;
}

$plugin_name = 'rcno-reviews';
$version = '1.0.0';

$template = new Rcno_Template_Tags( $plugin_name, $version );
$review_score_enable = (bool) get_post_meta( $review_id, 'rcno_review_score_enable', true );
$review_score_position = get_post_meta( $review_id, 'rcno_review_score_position', true );

//$ratings = new Rcno_Reviews_Public_Rating( $plugin_name, $version );

?>

<?php if ( $template->is_review_embedded() ) : ?>
    <h2 class="rcno-review-title">
		<?php echo get_the_title( $review_id ) ?>
    </h2>
<?php endif; ?>

<div class="rcno-book-info">
    <div class="review-content">

	    <?php if ( true === $review_score_enable && 'top' === $review_score_position ) : ?>

            <div class="review-box-container">
			    <?php $template->rcno_print_review_box( $review_id ); ?>
            </div>

	    <?php endif; ?>

        <div class="book-cover-container">
            <div class="book-cover">
                <?php
                    // Prints the book cover.
                    $template->the_rcno_book_cover( $review_id );

                    // Prints the book's private 5 star rating.
                    $template->the_rcno_admin_book_rating( $review_id );
                ?>
            </div>
            <div class="book-info">
		        <?php
                    // Prints the book cover and book's meta data.
                    $template->the_rcno_book_meta( $review_id, 'rcno_book_title', 'h2', false );

                    $template->the_rcno_taxonomy_terms( $review_id, 'rcno_author', true );
                    $template->the_rcno_taxonomy_terms( $review_id, 'rcno_genre', true );
                    $template->the_rcno_taxonomy_terms( $review_id, 'rcno_series', true );

                    $template->the_rcno_book_meta( $review_id, 'rcno_book_publisher', 'p', true );
                    $template->the_rcno_book_meta( $review_id, 'rcno_book_pub_date', 'p', true );
                    $template->the_rcno_book_meta( $review_id, 'rcno_book_pub_format', 'p', true );
                    $template->the_rcno_book_meta( $review_id, 'rcno_book_isbn', 'p', true );
		        ?>
            </div>
        </div>

	    <?php
            // Prints the book review content.
            $template->the_rcno_book_review_content( $review_id );
	    ?>

        <?php if ( true === $review_score_enable && 'bottom' === $review_score_position ) : ?>

            <div class="review-box-container">
                <?php $template->rcno_print_review_box( $review_id ); ?>
            </div>

        <?php endif; ?>

    </div>

	<?php
	    // Prints the review and book's metadata in the JSON+LD format content.
        $template->the_rcno_book_schema_data( $review_id );
        $template->the_rcno_review_schema_data( $review_id );
        echo '<!--- Recencio Book Reviews --->';
	?>

</div>

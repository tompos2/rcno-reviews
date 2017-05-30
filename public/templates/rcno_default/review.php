<?php
/*
Author: wzyMedia
Author Mail: kemory@wzymedia.com
Author URL: https://wzymedia.com
Layout Name: Rcno Default
Version: 1.0.0
Description: The default layout.
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

$ratings = new Rcno_Reviews_Public_Rating( $plugin_name, $version );

?>

<?php if ( $template->is_review_embedded() ) : ?>
    <h2 class="rcno-review-title">
		<?php echo get_the_title( $review_id ) ?>
    </h2>
<?php endif; ?>



<div class="rcno-book-info">

	<?php

		$template->the_rcno_book_review_content( $review_id );

	    do_action( 'before_the_rcno_book_description' );

		//$template->the_rcno_book_description( $review_id );

		$template->rcno_print_review_box( $review_id );

		$template->rcno_print_review_badge( $review_id );


	echo $template->get_the_rcno_full_book_details( $review_id );

	echo $template->get_the_rcno_book_schema_data( $review_id );
	echo $template->get_the_rcno_review_schema_data( $review_id );

	echo '<!--- Recencio Book Reviews --->';




	?>

</div>

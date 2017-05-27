<?php

/**
 * Author: wzyMedia
 * Template Name: Rcno Default
 * Rcno Default Template - the excerpt
 */

// Get the review ID.
if ( isset( $GLOBALS['review_id'] ) && $GLOBALS['review_id'] !== '' ) {
	$review_id = $GLOBALS['review_id'];
} else {
	$review_id = get_post()->ID;
}

$plugin_name = 'rcno-reviews';
$version = '1.0.0';

$review = new Rcno_Template_Tags( $plugin_name, $version );

$recipe_title = get_the_title( $review_id );
?>


<?php
/**
 * Displaying the recipe title is normally done by the theme as post_title().
 * However, if the recipe is embedded, we need to do it here.
 */

if ( $review->is_review_embedded() ) { ?>
	<h2 class="rcno-review-title">
		<?php echo $recipe_title; ?>
	</h2>
<?php } ?>

<?php

	$review->the_rcno_book_review_content( $review_id );

?>




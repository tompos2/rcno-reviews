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
	$version     = '1.0.0';

	$review = new Rcno_Template_Tags( $plugin_name, $version );

?>


<?php

	$review->the_rcno_review_title( $review_id );

	$review->the_rcno_book_review_excerpt( $review_id );
	//echo $review->rcno_excerpt( $review_id );

	echo '<!--- Recencio Book Reviews --->';

?>

<?php

// Get the review ID.
if ( isset( $GLOBALS['review_id'] ) && $GLOBALS['review_id'] !== '' ) {
	$review_id = $GLOBALS['review_id'];
} else {
	$review_id = get_post()->ID;
}

$plugin_name = 'rcno-reviews';
$version = '1.0.0';

do_action( 'before_the_rcno_book_description' );

$template = new Rcno_Template_Tags( $plugin_name, $version ); ?>

<div class="rcno-book-info">

	<?php

		$template->the_rcno_book_review_content( $review_id );

		$template->the_rcno_book_description( $review_id );

		$template->rcno_print_review_box( $review_id );

		$template->rcno_print_review_badge( $review_id );

		/*$template->the_rcno_taxonomy_headline( 'rcno_author' );
		$template->the_rcno_taxonomy_headline( 'rcno_genre' );
		$template->the_rcno_taxonomy_headline( 'rcno_series' );*/

		/*$template->the_rcno_taxonomy_terms( 'rcno_author', false );
		echo '<br>';
		$template->the_rcno_taxonomy_terms( 'rcno_genre', true, ' · ' );
		echo '<br>';
		$template->the_rcno_taxonomy_terms( 'rcno_series', true, ' · ' );*/
		echo '<br>';
		$template->the_rcno_taxonomy_list( true, '/' );

	?>

</div>

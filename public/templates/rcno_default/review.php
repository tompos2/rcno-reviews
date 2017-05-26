<?php

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

var_dump( $template );

?>



<div class="rcno-book-info">

	<?php

		$template->the_rcno_book_review_content( $review_id );

	    do_action( 'before_the_rcno_book_description' );

		$template->the_rcno_book_description( $review_id );

		$template->rcno_print_review_box( $review_id );

		$template->rcno_print_review_badge( $review_id );

		/*$template->the_rcno_taxonomy_headline( 'rcno_author' );
		$template->the_rcno_taxonomy_headline( 'rcno_genre' );
		$template->the_rcno_taxonomy_headline( 'rcno_series' );*/

		echo $template->get_the_rcno_book_cover( $review_id );

		$template->the_rcno_taxonomy_terms( 'rcno_author', false );
		echo '<br>';
		$template->the_rcno_taxonomy_terms( 'rcno_genre', true, ' · ' );
		echo '<br>';
		$template->the_rcno_taxonomy_terms( 'rcno_series', true, ' · ' );
		echo '<br>';
		//$template->the_rcno_taxonomy_list( true, '/' );

		echo $template->get_the_rcno_book_schema_data( $review_id );
		echo $template->get_the_rcno_review_schema_data( $review_id );
	    echo '<br>';

	    echo $template->get_the_rcno_book_meta( $review_id, 'rcno_book_publisher', 'h1' );
	    echo $template->get_the_rcno_book_meta( $review_id, 'rcno_book_pub_date', 'h1' );
	    echo $template->get_the_rcno_book_meta( $review_id, 'rcno_book_gr_url', '' );

	    $ratings->the_rating( $review_id );

	    var_dump( $ratings->rcno_rating_info( 'min' ) );
	    var_dump( $ratings->rcno_rating_info( 'max' ) );
	    var_dump( $ratings->rcno_rating_info( 'count' ) );
	    var_dump( $ratings->rcno_rating_info( 'avg' ) );



	?>

</div>

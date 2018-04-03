<?php

/**
 * Render a list of all book reviews as an Isotope grid
 */

$ignore_articles = Rcno_Reviews_Option::get_option( 'rcno_reviews_ignore_articles' );
$articles_list   = explode( ',', Rcno_Reviews_Option::get_option( 'rcno_reviews_ignored_articles_list', 'The,A,An' ) );
$articles_list   = implode( '|', $articles_list ) . '|\d+'; // @TODO: Figure out a better way to handle this.
$custom_taxonomies = explode( ',', Rcno_Reviews_Option::get_option( 'rcno_taxonomy_selection' ) );

// Create an empty output variable.
$out = '';

if ( $posts && count( $posts ) > 0 ) {
	// Create an index i to compare the number in the list and check for first and last item.
	$i = 0;

	// Create an empty array to take the book details.
	$books = array();

	// Loop through each post, book title from post-meta and work on book title.
	foreach ( $posts as $book ) {
		$book_details = get_post_custom( $book->ID );
		$unsorted_title = $book_details['rcno_book_title'][0];
		$sorted_title = $unsorted_title;
		if ( $ignore_articles ) {
			$sorted_title = preg_replace( '/^('. $articles_list .') (.+)/', '$2, $1', $book_details['rcno_book_title'] );
		}
		$books[] = array(
			'ID'    => $book->ID,
			'sorted_title' => $sorted_title,
			'unsorted_title' => $unsorted_title,
		);
		usort( $books, array( $this, 'cmp' ) );
	}

	$select = '';
	if ( $options['selectors'] ) {
		$select .= '<div class="rcno-isotope-grid-select-container">';
		foreach ( $custom_taxonomies as $taxon ) {
			$terms = $categories = get_terms( 'rcno_' . strtolower( $taxon ), 'orderby=count&order=DESC&hide_empty=1' );
			$select .= '<div class="rcno-isotope-grid-select-wrapper">';
			$select .= '<label for="rcno-isotope-grid-select">' . $taxon . '</label>';
			$select .= '<select class="rcno-isotope-grid-select" name="rcno-isotope-grid-select">';
			$select .= '<option value="*">' . sprintf( '%s %s', __( 'Select', 'rcno-reviews' ), $taxon ) . '</option>';
			foreach ( $terms as $term ) {
				$select .= '<option value="'. '.' . $term->slug . '">' . $term->name . '</option>';
			}
			$select .= '</select>';
			$select .= '</div>';
		}
		$select .= '</div>';
	}



	$out .= '<div id="rcno-isotope-grid-container" class="rcno-isotope-grid-container">';

	// Walk through all the books to build alphabet navigation.
	foreach ( $books as $book ) {

		// Add the entry for the post.
		$out .= '<div class="rcno-isotope-grid-item ';
		$out .= implode( ' ', $this->template->get_rcno_review_html_classes( $book['ID'] ) );
		$out .= '" ';
		//$out .= 'style="width:' . $options['width'] . 'px; height:' . $options['height'] . 'px;"';
		$out .= 'style="width:' . $options['width'] . 'px;"';
		$out .= '>';
		$out .= '<a href="' . get_permalink( $book['ID'] ) . '">';
		$out .= $this->template->get_the_rcno_book_cover( $book['ID'], 'medium', true, true );
		$out .= '</a></div>';

		// Increment the counter.
		$i ++;
	}
	// Close the last list.
	$out .= '</div>';

	// This is where everything is printed.
	echo $select;
	echo $out;

} else {
	// No book reviews.
	esc_html_e( 'There are no book reviews to display.', 'rcno-reviews' );
}

<?php

/**
 * Render a list of all book reviews as an Isotope grid
 *
 * Available variables:
 * array $posts
 * array $options
 * Rcno_Isotope_Grid_Shortcode $this
 *
 * Available functions:
 * sort_by_title()
 */

// Create an empty output variable.
$out = '';

if ( $posts && count( $posts ) > 0 ) {
	// Create an index i to compare the number in the list and check for first and last item.
	$i = 0;

	// Create an empty array to take the book details.
	$books = array();

	// Loop through each post, book title from post-meta and work on book title.
	foreach ( $posts as $book ) {
		$book_details   = get_post_custom( $book->ID );
		$unsorted_title = $book_details['rcno_book_title'][0];
		$sorted_title   = $unsorted_title;
		if ( $this->variables['ignore_articles'] ) {
			$sorted_title = preg_replace( '/^(' . $this->variables['articles_list'] . ') (.+)/', '$2, $1', $book_details['rcno_book_title'] );
		}
		$books[] = array(
			'ID'             => $book->ID,
			'sorted_title'   => $sorted_title,
			'unsorted_title' => $unsorted_title,
		);
		usort( $books, array( $this, 'sort_by_title' ) );
	}

	$select = '';
	if ( $options['selectors'] ) {
		$exclude = explode( ',', strtolower( $options['exclude'] ) );
		$select .= '<div class="rcno-isotope-grid-select-container">';

		if ( $options['search'] ) {
			$select .= '<input type="text" class="rcno-isotope-grid-search rcno-isotope-grid-select-wrapper" placeholder="'
			              . __( 'Search...', 'rcno-reviews' ) . '" />';
		}

		$taxonomies = array_diff( $this->variables['custom_taxonomies'], $exclude );
		foreach ( $taxonomies as $taxonomy ) {
			$terms     = get_terms( 'rcno_' . strtolower( $taxonomy ), 'orderby=name&order=ASC&hide_empty=1' );
			$_taxonomy = get_taxonomy( 'rcno_' . strtolower( $taxonomy ) ); // Using the label to avoid i18n issues.
			$select   .= '<div class="rcno-isotope-grid-select-wrapper">';
			$select   .= '<label for="rcno-isotope-grid-select">' . $_taxonomy->label . '</label>';
			$select   .= '<select class="rcno-isotope-grid-select" name="rcno-isotope-grid-select">';
			$select   .= '<option value="*">' . sprintf( '%s %s', __( 'Select', 'rcno-reviews' ), $_taxonomy->label ) . '</option>';
			foreach ( $terms as $term ) {
				if ( is_object( $term ) ) {
					$select .= '<option value="' . '.' . $term->slug . '">' . $term->name . '</option>';
				}
			}
			$select .= '</select>';
			$select .= '</div>';
		}
		$select .= '<span class="rcno-isotope-grid-select reset">&olarr;</span>';
		$select .= '</div>';
	}

	$out .= '<div id="rcno-isotope-grid-container" class="rcno-isotope-grid-container">';

	// Walk through all the books to build alphabet navigation.
	foreach ( $books as $book ) {

		// Add the entry for the post.
		$out .= '<div class="rcno-isotope-grid-item ';
		$out .= sanitize_title( $this->template->get_the_rcno_book_meta( $book['ID'], 'rcno_book_title', '', false ) ) . ' ';
		$out .= implode( ' ', $this->template->get_rcno_review_html_classes( $book['ID'] ) ) . '" ';
		$out .= 'style="width:' . $options['width'] . 'px;"';
		$out .= '>';
		if ( $options['rating'] ) {
			$out .= $this->template->get_the_rcno_admin_book_rating( $book['ID'] );
		}
		$out .= '<a href="' . get_permalink( $book['ID'] ) . '">';
		// $size 'thumbnail', 'medium', 'full', 'rcno-book-cover-sm', 'rcno-book-cover-lg'.
		$out .= $this->template->get_the_rcno_book_cover( $book['ID'], 'rcno-book-cover-sm', true );
		$out .= '</a>';
		$out .= '</div>';

		// Increment the counter.
		$i ++;
	}
	// Close the last list.
	$out .= '</div>';

	// This is where everything is printed.
	// We are checking if we are on a page because the following error occurs in production, sometimes.
	// Warning: Cannot modify header information - headers already sent.
	if ( is_singular() ) {
		echo $select . $out;
	}
} else {
	// No book reviews.
	esc_html_e( 'There are no book reviews to display.', 'rcno-reviews' );
}

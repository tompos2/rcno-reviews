<?php

/**
 * Render a list of all book reviews.0
 * @todo: create a multi-column layout
 */

$template = new Rcno_Template_Tags( 'rcno-reviews', '1.0.0' );

// Create an empty output variable
$out = '';

if ( $posts && count( $posts ) > 0 ) {
	// Create an index i to compare the number in the list and check for first and last item
	$i = 0;

	// Create an empty array to take with the first letters of all headlines.
	$letters = array();

	// Walk through all the terms to build alphabet navigation.
	foreach ( $posts as $post ) {
		if ( $headers ) {
			// Add first letter headlines for easier navigation.

			// Get the first letter (without special chars).
			$first_letter = substr( $template->rcno_normalize_special_chars( $post->post_title ), 0, 1 );

			// Check if we've already had a headline.
			if ( ! in_array( $first_letter, $letters, true ) ) {
				// Close list of proceeding group.
				if ( $i != 0 ) {
					$out .= '</ul>';
				}
				// Create a headline.
				$out .= '<h2><a class="rcno-toplink" href="#top">&uarr;</a><a name="' . $first_letter . '"></a>';
				$out .= strtoupper( $first_letter );
				$out .= '</h2>';

				// Start new list.
				$out .= '<ul class="rcno-taxlist">';

				// Add the letter to the list.
				$letters[] = $first_letter;
			}
		} else {
			// Start list before first item.
			if ( $i === 0 ) {
				$out .= '<ul class="rcno-taxlist">';
			}
		}

		// Add the entry for the post.
		$out .= '<li><a href="' . get_permalink( $post->ID ) . '">';
		$out .= $post->post_title;
		$out .= '</a></li>';

		// increment the counter.
		$i ++;
	}
	// Close the last list.
	$out .= '</ul>';

	// Output the rendered list.
	echo '<a name="top"></a>';
	$template->the_rcno_alphabet_nav_bar( $letters );
	echo $out;
	$template->the_rcno_alphabet_nav_bar( $letters );
} else {
	// No book reviews.
	_e( 'There are no book reviews to display.', 'rcno-reviews' );
}

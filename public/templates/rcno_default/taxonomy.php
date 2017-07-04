<?php
/**
 * Render a list of all terms of this taxonomy
 */

$template = new Rcno_Template_Tags( 'rcno-reviews', '1.0.0' );

// Create an empty output variable.
$out = '';

if ( $terms ) {
	if ( count( $terms ) > 0 ) {

		if ( 'rcno_author' === $taxonomy ) {
			// If we are looking at authors, move the first name to last and separate by a comma.
			foreach ( $terms as $_term ) {
				$_term_name = $_term->name;
				$_term_name = explode( ' ', $_term_name );
				$_term_name = array_reverse( $_term_name );
				$_term_name = implode( ', ', $_term_name );

				$_term->name = $_term_name;
			}
		}

		if ( 'rcno_series' === $taxonomy ) {
			// If we are looking at book series, move all articles to the and of string and separate by a comma.
			foreach ( $terms as $_term ) {
				$_term_name = $_term->name;
				$_term_name = preg_replace( '/^(A|An|The) (.+)/', '$2, $1', $_term_name );

				$_term->name = $_term_name;
			}
		}

		foreach ( $terms as $key => $value ) {
			// Form the terms available we are only taking the 'name' and 'ID' and doing a natural sort.
			$_terms[ $value->term_id ] = $value->name;
			natcasesort( $_terms );
		}

		// Create an index i to compare the number in the list and check for first and last item.
		$i = 0;

		// Create an empty array to take with the first letters of all headlines.
		$letters = array();

		// Walk through all the terms to build alphabet navigation.
		foreach ( $_terms as $key => $value ) {
			// Get term meta data.
			$term_meta = get_term_meta( $key );

			$title = ucfirst( $value );

			if ( 'true' === $headers ) { // Add first letter headlines for easier navigation.

				// Get the first letter (without special chars).
				$first_letter = substr( remove_accents( $title ), 0, 1 );

				// Check if we've already had a headline.
				if ( ! in_array( $first_letter, $letters, true ) ) {
					// Close list of proceeding group.
					if ( 0 !== $i ) {
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
				if ( 0 === $i ) {
					$out .= '<ul class="rcno-taxlist">';
				}
			}

			// Add the entry for the term.
			$out .= '<li><a href="' . get_term_link( $key ) . '">';
			$out .= $title;
			$out .= '</a></li>';

			// Increment the counter.
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
		// No terms in this taxonomy.
		esc_html_e( 'There are no terms in this taxonomy.', 'rcno-reviews' );
	}

} else {
	// Error: no taxonomy set.
	esc_html_e( '<b>Error:</b> No taxonomy set for this list!', 'rcno-reviews' );
}

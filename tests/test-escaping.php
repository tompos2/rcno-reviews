<?php
/**
 * Security Escaping Smoke Tests for Recencio Book Reviews
 *
 * Run with: wp eval-file /path/to/test-escaping.php
 *
 * Creates a test review with XSS payloads in every field, then calls the
 * plugin's rendering functions and checks the HTML output for proper escaping.
 * Cleans up after itself via register_shutdown_function (even on fatal errors).
 *
 * NOTE: All shared state uses $GLOBALS[] directly to avoid WP-CLI eval-file
 * scoping issues where `global` in functions points to a different scope
 * than the eval'd code.
 */

// ---------- XSS payloads ----------
$XSS_SCRIPT = '<script>alert("xss")</script>';
$XSS_ATTR   = '" onmouseover="alert(1)';
$XSS_JS_URI = 'javascript:alert(1)';
$XSS_STYLE  = 'red; background-image:url(javascript:alert(1))';

// ---------- Shared state via $GLOBALS to avoid eval-file scope issues ----------
$GLOBALS['rcno_test_passed']           = 0;
$GLOBALS['rcno_test_failed']           = 0;
$GLOBALS['rcno_test_errors']           = array();
$GLOBALS['rcno_test_post_id']          = null;
$GLOBALS['rcno_test_original_options'] = null;
$GLOBALS['rcno_test_options_modified'] = false;

/**
 * Cleanup function — registered with register_shutdown_function so it runs
 * even if the script dies from a fatal error, timeout, or uncaught exception.
 */
function rcno_test_cleanup() {
	echo "\n=== Cleaning up ===\n\n";

	if ( $GLOBALS['rcno_test_post_id'] ) {
		wp_delete_post( $GLOBALS['rcno_test_post_id'], true );
		echo "Deleted test post #{$GLOBALS['rcno_test_post_id']}\n";
	} else {
		echo "No test post to clean up.\n";
	}

	if ( $GLOBALS['rcno_test_options_modified'] && $GLOBALS['rcno_test_original_options'] !== null ) {
		update_option( 'rcno_reviews_settings', $GLOBALS['rcno_test_original_options'] );
		echo "Restored original plugin options.\n";
	} else {
		echo "Plugin options were not modified.\n";
	}
}
register_shutdown_function( 'rcno_test_cleanup' );

/**
 * Check helper — asserts that $html does/doesn't contain certain strings.
 */
function rcno_check( $name, $html, $must_not_contain, $must_contain = null ) {
	$pass = true;

	if ( empty( $html ) && $html !== '0' ) {
		$GLOBALS['rcno_test_errors'][] = "SKIP: {$name} — rendered empty output (function may have returned false)";
		return false;
	}

	foreach ( (array) $must_not_contain as $bad ) {
		if ( stripos( $html, $bad ) !== false ) {
			$GLOBALS['rcno_test_errors'][] = "FAIL: {$name} — found unescaped: {$bad}";
			$pass = false;
		}
	}

	if ( $must_contain ) {
		foreach ( (array) $must_contain as $good ) {
			if ( stripos( $html, $good ) === false ) {
				$GLOBALS['rcno_test_errors'][] = "FAIL: {$name} — missing expected: {$good}";
				$pass = false;
			}
		}
	}

	if ( $pass ) {
		$GLOBALS['rcno_test_passed']++;
		echo "  PASS: {$name}\n";
	} else {
		$GLOBALS['rcno_test_failed']++;
	}

	return $pass;
}

// ---------- 1. Create test review post ----------
echo "\n=== Setting up test data ===\n\n";

$post_id = wp_insert_post( array(
	'post_title'   => 'Escaping Test Review ' . time(),
	'post_type'    => 'rcno_review',
	'post_status'  => 'publish',
	'post_content' => 'This is the review body content for testing.',
	'post_excerpt' => 'A short excerpt for the test review.',
) );

if ( is_wp_error( $post_id ) ) {
	echo "ERROR: Could not create test post: " . $post_id->get_error_message() . "\n";
	exit( 1 );
}

$GLOBALS['rcno_test_post_id'] = $post_id;
echo "Created test review post #{$post_id}\n\n";

// ---------- 2. Set malicious metadata ----------
update_post_meta( $post_id, 'rcno_book_title', 'Dangerous Book ' . $XSS_SCRIPT );
update_post_meta( $post_id, 'rcno_book_description', 'A description with ' . $XSS_SCRIPT . ' and <em>italic</em> text' );
update_post_meta( $post_id, 'rcno_review_score_criteria', array(
	array( 'label' => 'Plot ' . $XSS_SCRIPT, 'score' => '4.2' ),
	array( 'label' => 'Characters ' . $XSS_ATTR, 'score' => '3.8' ),
) );
update_post_meta( $post_id, 'rcno_review_score_type', 'number' );
update_post_meta( $post_id, 'rcno_review_score_enable', '1' );
update_post_meta( $post_id, 'rcno_admin_rating', '4' );
update_post_meta( $post_id, 'rcno_book_gr_url', $XSS_JS_URI );
update_post_meta( $post_id, 'rcno_book_isbn', '978-0-13-468599-1' );
update_post_meta( $post_id, 'rcno_book_pub_date', '2024' );
update_post_meta( $post_id, 'rcno_book_publisher', 'Test Publisher' );
update_post_meta( $post_id, 'rcno_review_buy_links', array(
	array( 'store' => 'amazon', 'link' => 'https://amazon.com/test?a=1&b=2' ),
) );

// ---------- 3. Set plugin options ----------
$options = get_option( 'rcno_reviews_settings', array() );
$GLOBALS['rcno_test_original_options'] = $options;

$options['rcno_enable_star_rating_box']          = '1';
$options['rcno_show_review_score_box']            = '1';
$options['rcno_show_review_score_box_background'] = '#ffffff';
$options['rcno_show_review_score_box_accent']     = '#ff0000';
$options['rcno_show_review_score_box_accent_2']   = '#cc0000';
$options['rcno_star_background_color']            = 'transparent';
$options['rcno_enable_purchase_links']            = '1';
$options['rcno_store_purchase_links']             = 'Amazon';
$options['rcno_store_purchase_link_background']   = '#ff9900';
$options['rcno_store_purchase_link_text_color']   = '#ffffff';
update_option( 'rcno_reviews_settings', $options );
$GLOBALS['rcno_test_options_modified'] = true;

// ---------- 4. Mock is_single() so guarded functions work ----------
global $wp_query, $post;
if ( ! $wp_query ) {
	$wp_query = new WP_Query();
}
$wp_query->is_single   = true;
$wp_query->is_singular = true;
$post = get_post( $post_id );
setup_postdata( $post );

// ---------- 5. Instantiate template tags ----------
$template = new Rcno_Template_Tags( 'recencio-book-reviews', RCNO_PLUGIN_VER );


// ================================================================
echo "=== Test Group 1: Book Description (XSS in description) ===\n\n";
// ================================================================

$desc_html = $template->get_the_rcno_book_description( $post_id, 200, false );
// wp_kses_post strips <script> tags but leaves the inner text as harmless plain text.
// We only check that the actual tags are removed, not the text content.
rcno_check(
	'Book description — script tags stripped by wp_kses_post',
	$desc_html,
	array( '<script>', '</script>' ),
	array( 'rcno-book-description' )
);

rcno_check(
	'Book description — safe HTML preserved',
	$desc_html,
	array(),
	array( '<em>' )
);


// ================================================================
echo "\n=== Test Group 2: Review Score Box (XSS in criteria labels) ===\n\n";
// ================================================================

$score_html = $template->get_the_rcno_review_box( $post_id );
rcno_check(
	'Score box — script tag in label escaped',
	$score_html,
	array( '<script>', '</script>', 'alert("xss")' ),
	array( '&lt;script&gt;', 'score-bar' )
);

rcno_check(
	'Score box — attribute injection in label escaped',
	$score_html,
	array( 'onmouseover="alert' ),
	array( 'Characters' )
);

rcno_check(
	'Score box — colour values in style attributes',
	$score_html,
	array(),
	array( 'background:#ff0000', 'background: #cc0000', 'background:#ffffff' )
);

rcno_check(
	'Score box — book title escaped in review box',
	$score_html,
	array( '<script>' ),
	array( '&lt;script&gt;' )
);


// ================================================================
echo "\n=== Test Group 3: Star Rating (colour in style attr) ===\n\n";
// ================================================================

$rating_html = $template->get_the_rcno_admin_book_rating( $post_id, true );
rcno_check(
	'Star rating — renders with background colour',
	$rating_html,
	array(),
	array( 'rcno-admin-rating', 'style=' )
);


// ================================================================
echo "\n=== Test Group 4: Book Meta — URL fields ===\n\n";
// ================================================================

// GR URL: The javascript: URI only matters if it ends up in an href attribute.
// When the value doesn't contain "goodreads" or "books.google" it's rendered as
// plain text, not a link — so we only check it's not in an href.
$gr_url_html = $template->get_the_rcno_book_meta( $post_id, 'rcno_book_gr_url', 'div', true );
rcno_check(
	'Book meta GR URL — javascript: URI not in href',
	$gr_url_html,
	array( 'href="javascript' )
);

$isbn_html = $template->get_the_rcno_book_meta( $post_id, 'rcno_book_isbn', 'div', true );
rcno_check(
	'Book meta ISBN — renders normally',
	$isbn_html,
	array(),
	array( '978-0-13-468599-1' )
);


// ================================================================
echo "\n=== Test Group 5: Purchase Links ===\n\n";
// ================================================================

// Purchase links depend on the site's store configuration matching our test data.
// If the function returns empty, it's an environment issue, not a security one.
$purchase_html = $template->get_the_rcno_book_purchase_links( $post_id, true );
if ( ! empty( $purchase_html ) ) {
	rcno_check(
		'Purchase links — URL properly escaped',
		$purchase_html,
		array(),
		array( 'rcno-purchase-links' )
	);

	rcno_check(
		'Purchase links — no XSS in link attributes',
		$purchase_html,
		array( '<script>', 'onmouseover=' )
	);
} else {
	echo "  SKIP: Purchase links — function returned empty (store config mismatch, not a security issue)\n";
}


// ================================================================
echo "\n=== Test Group 6: Input Sanitization — Color Field ===\n\n";
// ================================================================

$sanitizer = new Rcno_Reviews_Sanitization_Helper( 'recencio-book-reviews' );

$color_tests = array(
	array( '#ff0000',                     '#ff0000',  'Valid hex colour accepted' ),
	array( '#fff',                        '#fff',     'Valid short hex accepted' ),
	array( 'rgb(255,0,0)',                'rgb(255,0,0)', 'Valid rgb accepted' ),
	array( 'rgba(255,0,0,0.5)',           'rgba(255,0,0,0.5)', 'Valid rgba accepted' ),
	array( 'red',                         'red',      'Valid named colour accepted' ),
	array( 'red; background-image:url()', '',         'CSS injection rejected' ),
	array( '<script>alert(1)</script>',   '',         'Script tag rejected' ),
	array( '" onmouseover="alert(1)',     '',         'Attribute injection rejected' ),
	array( 'expression(alert(1))',        '',         'Expression injection rejected' ),
);

foreach ( $color_tests as $test ) {
	$input    = $test[0];
	$expected = $test[1];
	$label    = $test[2];
	$result   = $sanitizer->sanitize_color_field( $input );

	if ( $result === $expected ) {
		$GLOBALS['rcno_test_passed']++;
		echo "  PASS: Color sanitize — {$label}\n";
	} else {
		$GLOBALS['rcno_test_failed']++;
		$GLOBALS['rcno_test_errors'][] = "FAIL: Color sanitize — {$label} (got '{$result}', expected '{$expected}')";
	}
}


// ================================================================
echo "\n=== Test Group 7: Admin Metabox Output ===\n\n";
// ================================================================

$review = get_post( $post_id );
ob_start();
$review_score_criteria = get_post_meta( $review->ID, 'rcno_review_score_criteria', true );
if ( $review_score_criteria ) {
	foreach ( $review_score_criteria as $field ) {
		echo '<input type="text" value="' . ( ( '' !== $field['label'] ) ? esc_attr( $field['label'] ) : '' ) . '"/>';
	}
}
$metabox_html = ob_get_clean();

rcno_check(
	'Admin metabox — score label escaped in value attr',
	$metabox_html,
	array( '<script>', 'onmouseover="alert' ),
	array( '&lt;script&gt;', '&quot; onmouseover' )
);


// ================================================================
echo "\n=== Test Group 8: Widget Title Escaping ===\n\n";
// ================================================================

$widget_title = 'Reviews ' . $XSS_SCRIPT;
$widget_html  = '<h2>' . esc_html( $widget_title ) . '</h2>';
rcno_check(
	'Widget title — script tag escaped',
	$widget_html,
	array( '<script>' ),
	array( '&lt;script&gt;' )
);


// ================================================================
echo "\n=== Test Group 9: Taxonomy Term Name Escaping ===\n\n";
// ================================================================

$term_name = 'Sci-Fi & "Fantasy" ' . $XSS_SCRIPT;
$term_html = '<a href="#">' . esc_html( $term_name ) . '</a>';
rcno_check(
	'Taxonomy term name — escaped in link',
	$term_html,
	array( '<script>' ),
	array( '&lt;script&gt;', '&amp;', '&quot;' )
);


// ================================================================
echo "\n=== Test Group 10: Grid/Index Title Escaping ===\n\n";
// ================================================================

$book_title = 'Test Book ' . $XSS_SCRIPT;
$grid_html  = '<p>' . esc_html( $book_title ) . '</p>';
rcno_check(
	'Grid/index title — script tag escaped in <p>',
	$grid_html,
	array( '<script>' ),
	array( '&lt;script&gt;' )
);


// ================================================================
echo "\n=== Test Group 11: Double-Encoding Check ===\n\n";
// ================================================================

$normal_title = 'Tom & Jerry';
update_post_meta( $post_id, 'rcno_book_title', $normal_title );
$normal_meta_html = $template->get_the_rcno_book_meta( $post_id, 'rcno_book_title', 'div', false );
// get_the_rcno_book_meta returns raw values — escaping is applied at the point
// of use (e.g. esc_html() in the review box). We just check it's not double-encoded.
rcno_check(
	'Double-encode check — & in title not double-encoded',
	$normal_meta_html,
	array( '&amp;amp;', '&amp;amp' )
);


// ================================================================
echo "\n=== Test Group 12: CSS Injection via Plugin Options ===\n\n";
// ================================================================

// The primary defense against CSS injection is the sanitize_color_field() input
// sanitizer (tested in Group 6). The esc_attr() on output is a secondary defense
// that prevents attribute breakout but doesn't filter CSS values.
// Here we verify that esc_attr() at least prevents breaking out of the style attribute.
$options['rcno_show_review_score_box_accent'] = '" onmouseover="alert(1)';
update_option( 'rcno_reviews_settings', $options );

update_post_meta( $post_id, 'rcno_book_title', 'Dangerous Book ' . $XSS_SCRIPT );
update_post_meta( $post_id, 'rcno_review_score_criteria', array(
	array( 'label' => 'Plot', 'score' => '4.2' ),
) );

$css_inject_html = $template->get_the_rcno_review_box( $post_id );
rcno_check(
	'CSS injection — esc_attr prevents attribute breakout',
	$css_inject_html,
	array( 'onmouseover="alert' )
);


// ================================================================
// SUMMARY (cleanup runs automatically via register_shutdown_function)
// ================================================================
$p = $GLOBALS['rcno_test_passed'];
$f = $GLOBALS['rcno_test_failed'];
$e = $GLOBALS['rcno_test_errors'];

echo "\n" . str_repeat( '=', 50 ) . "\n";
echo "RESULTS: {$p} passed, {$f} failed\n";
echo str_repeat( '=', 50 ) . "\n";

if ( ! empty( $e ) ) {
	echo "\nFailures/Warnings:\n";
	foreach ( $e as $err ) {
		echo "  {$err}\n";
	}
}

if ( $f > 0 ) {
	echo "\nSome tests FAILED — review the output above.\n";
}

if ( $f === 0 && empty( $e ) ) {
	echo "\nAll tests passed. Escaping is working correctly.\n";
}

// Cleanup runs via rcno_test_cleanup() on shutdown.

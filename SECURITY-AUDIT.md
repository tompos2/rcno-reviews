# Security Audit Report: Recencio Book Reviews v1.66.0

**CVE:** CVE-2024-33648 (Stored XSS via Contributor+ users, CVSS 6.4)
**Date:** 16 February 2026
**Scope:** All 117 PHP files across admin/, public/, includes/, extensions/

---

## Executive Summary

The audit identified **48 security findings** across the codebase. The most critical are Stored XSS vulnerabilities where contributor+ users can inject JavaScript via book metadata fields that are rendered on the public frontend without escaping. This is the root cause of CVE-2024-33648.

**Breakdown:** 4 Critical, 16 High, 19 Medium, 9 Low

---

## CRITICAL FINDINGS

### C1. Unescaped Book Description -- Primary CVE Vector
**File:** `public/class-rcno-template-tags.php:664-681`
**Type:** Stored XSS

```php
$book_description = isset( $review['rcno_book_description'] ) ? $review['rcno_book_description'][0] : '';
// ... when strip_tags is false:
$out .= apply_filters( 'rcno_book_description', $book_description, $review_id );
```

Book description from `get_post_custom()` is output without escaping when `$strip_tags` is false. A contributor+ user sets a malicious description, every visitor gets XSS'd.

**Fix:** `$out .= wp_kses_post( apply_filters( 'rcno_book_description', $book_description, $review_id ) );`

---

### C2. Unsanitized Review Score Metadata Saves
**File:** `admin/class-rcno-admin-review-score.php:127-141`
**Type:** Stored XSS (input side)

```php
// @TODO: Below needs sanitization.
if ( isset( $data['rcno_review_score_type'] ) ) {
    $review_score_type = $data['rcno_review_score_type'];
    update_post_meta( $review_id, 'rcno_review_score_type', $review_score_type );
}
// Same for rcno_review_score_position and rcno_review_score_enable
```

The code itself has a `@TODO` acknowledging this. Three post meta fields saved raw from `$_POST`. These values are rendered on the frontend in score boxes.

**Fix:** Sanitize all three: `sanitize_text_field( $data['rcno_review_score_type'] )` etc.

---

### C3. Unescaped Review Score Criteria Labels
**File:** `public/class-rcno-template-tags.php:1406-1411`
**Also:** `public/shortcodes/class-rcno-score-box-shortcode.php:155`
**Type:** Stored XSS

```php
$output .= '<span class="score-bar">' . $criteria['label'] . '</span>';
```

Score criteria labels from `get_post_meta()` (unserialized array) rendered without escaping. Contributor+ user can inject JS via review score labels.

**Fix:** `$output .= '<span class="score-bar">' . esc_html( $criteria['label'] ) . '</span>';`

---

### C4. Commented-Out Nonce Verification in AJAX Handler
**File:** `public/shortcodes/class-rcno-isotope-grid-shortcode.php:181-185`
**Type:** CSRF

```php
public function more_filtered_reviews() {
    //  Nonce check
    /*if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rpr-filterable' ) ) {
        wp_die( __( 'Failed security check', 'recipepress-reloaded' ) );
    }*/
```

Nonce check is completely commented out. Also references wrong nonce action (`rpr-filterable` instead of `rcno-isotope`). The JS does send a nonce (line 241 of `rcno-isotope-html.php`), the server just never checks it.

**Fix:** Uncomment and correct:
```php
if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rcno-isotope' ) ) {
    wp_die( __( 'Failed security check', 'recencio-book-reviews' ) );
}
```

---

## HIGH FINDINGS

### H1. Unescaped Book Titles in Grid/Index Views
**Files:**
- `public/shortcodes/class-rcno-grid-shortcode.php:169`
- `public/templates/rcno_default/reviews_index.php:93`
- `public/templates/rcno_default/reviews_grid.php:54`

```php
$out .= '<p>' . $book['unsorted_title'] . '</p>';
```

**Fix:** `$out .= '<p>' . esc_html( $book['unsorted_title'] ) . '</p>';`

---

### H2. Unescaped Currently Reading Widget Data
**File:** `public/widgets/class-rcno-reviews-currently-reading.php:133,148-151`

```php
<img src="<?php echo $most_recent['book_cover']; ?>" alt="book-cover" />
<h3 class="book-title"><?php echo $most_recent['book_title']; ?></h3>
<p class="book-author"><?php echo sprintf( '%s %s', __( 'by', ... ), $most_recent['book_author'] ); ?></p>
<p v-if="is_loading" class="book-comment" key="default"><?php echo $most_recent['progress_comment']; ?></p>
```

**Fix:** Wrap each with `esc_url()`, `esc_html()` as appropriate.

---

### H3. SQL Injection via Taxonomy Sort
**File:** `admin/class-rcno-reviews-admin.php:865`

```php
$clauses['where'] .= "AND (taxonomy = '" . $taxonomy . "' OR taxonomy IS NULL)";
```

`$taxonomy` derived from `$wp_query->query['orderby']` via URL, concatenated directly into SQL.

**Fix:** `$clauses['where'] .= $wpdb->prepare( " AND (taxonomy = %s OR taxonomy IS NULL)", $taxonomy );`

---

### H4. SQL Injection -- Calendar Widget (6 raw queries)
**File:** `public/widgets/class-rcno-reviews-calendar.php:199,224,240-252,297,318-322,338-343`

Multiple `$wpdb->get_var()` and `$wpdb->get_results()` calls with direct string interpolation of `$thisyear`, `$thismonth`, `$post_types`, `$d`.

**Fix:** Rewrite all to use `$wpdb->prepare()`.

---

### H5. Missing Nonce -- Search Bar AJAX Handler
**File:** `public/widgets/class-rcno-reviews-search-bar.php:135-158`

```php
public function send_results() {
    $search = $_POST['search'];
```

No nonce check despite a nonce being generated on line 78. Also unsanitized `$_POST['search']`.

**Fix:** Add `check_ajax_referer( 'search-bar', 'nonce' );` and `$search = sanitize_text_field( $_POST['search'] );`

---

### H6. Missing Nonce -- Book Cover Save
**File:** `admin/class-rcno-admin-book-cover.php:92-121`

No nonce field in the metabox view, no `wp_verify_nonce()` in the save handler.

**Fix:** Add `wp_nonce_field()` in view and verify in save handler.

---

### H7. Missing Nonce -- Review Rating Save
**File:** `admin/class-rcno-admin-review-rating.php:100-104`

```php
if ( isset( $data['rcno_admin_rating'] ) ) { //TODO: This needs a nonce check.
```

Code itself acknowledges the missing nonce.

**Fix:** Add nonce field and verification.

---

### H8. Missing Nonce Verification -- Review Score Save
**File:** `admin/class-rcno-admin-review-score.php:105-141`

Nonce field exists in view (`rcno_repeatable_meta_box_nonce`) but `wp_verify_nonce()` is never called in the save handler.

**Fix:** Add `if ( ! wp_verify_nonce( $data['rcno_repeatable_meta_box_nonce'], 'rcno_repeatable_meta_box_nonce' ) ) { return; }`

---

### H9. Missing Nonce -- Book Description Save
**File:** `admin/class-rcno-admin-description-meta.php:109-116`

No nonce verification.

**Fix:** Add nonce field and verification.

---

### H10. Settings Import Bypasses Sanitization
**File:** `admin/class-rcno-reviews-admin.php:1327-1331`

```php
$settings = (array) json_decode( $settings );
if ( isset( $settings['rcno_settings_version'] ) ) {
    update_option( 'rcno_reviews_settings', $settings );
```

JSON import only checks one key exists, then writes all values to the database unsanitized.

**Fix:** Run each imported value through sanitization callbacks.

---

### H11. Unescaped Ratings Label on Every Review Page
**File:** `public/class-rcno-reviews-public-ratings.php:203-206`

```php
printf( '<div class="rating-container"><p class="rating-label">%s</p>...', $this->ratings_label, ... );
```

**Fix:** `esc_html( $this->ratings_label )`

---

### H12. Author Box -- Unescaped Author Name
**File:** `extensions/rcno-author-box/includes/author-box.php:23,66-67`

```php
$out .= '<h3>' . $this->get_setting( 'author_box_title' ) . ' ' . $author . '</h3>';
```

`$author` from `get_the_author()` can be set by contributor+ users.

**Fix:** Wrap both in `esc_html()`.

---

### H13. Custom User Metadata -- Unescaped in Admin
**File:** `extensions/rcno-custom-user-metadata/includes/custom-user-metadata-fields.php:16,22`

```php
<?php echo $custom_meta; ?>
value="<?php echo get_post_meta( ... ); ?>"
```

**Fix:** `esc_html( $custom_meta )` and `esc_attr( get_post_meta( ... ) )`

---

### H14. SSRF via Book Cover URL Fetch
**File:** `extensions/rcno-fetch-book-cover/rcno-fetch-book-cover.php:96-98`

```php
$image_url = esc_url_raw( $data['rcno_reviews_gr_cover_url'] );
$get = wp_remote_get( $image_url, ... );
```

Contributor+ can make the server fetch arbitrary URLs.

**Fix:** Use `wp_safe_remote_get()` which restricts to external hosts.

---

### H15. Unescaped Post Title in Recent Reviews Widget
**File:** `public/widgets/class-rcno-reviews-recent-reviews.php:117`

```php
$out .= '<h3>' . $review->post_title . '</h3>';
```

**Fix:** `$out .= '<h3>' . esc_html( $review->post_title ) . '</h3>';`

---

### H16. Unescaped Book Title in Review Box
**File:** `public/class-rcno-template-tags.php:1376,1398`

```php
$review_box_title = strip_tags( $this->get_the_rcno_book_meta( ... ) );
$output .= '<h2 class="review-title">' . $review_box_title . '</h2>';
```

`strip_tags()` is not a security function.

**Fix:** Use `esc_html()`.

---

## MEDIUM FINDINGS

### M1. Unescaped `$external_api` in CSS class
`admin/views/rcno-reviews-isbn-metabox.php:29` -- Fix: `esc_attr()`

### M2. ISBN value uses `sanitize_text_field()` instead of `esc_attr()` for output
`admin/views/rcno-reviews-isbn-metabox.php:27` -- Fix: `esc_attr()`

### M3. Unescaped taxonomy name/term in metabox
`admin/views/rcno-reviews-taxonomies-metabox.php:12-14` -- Fix: `esc_attr()`

### M4. Unescaped option value in listings modal
`admin/views/rcno-reviews-listings-modal.php:33` -- Fix: `esc_attr()`

### M5. Unescaped number value in settings callback
`admin/settings/class-rcno-reviews-callback-helper.php:434` -- Fix: `esc_attr()`

### M6. Unescaped select/multiselect options in settings
`admin/settings/class-rcno-reviews-callback-helper.php:518,551` -- Fix: `esc_attr()` / `esc_html()`

### M7. Unescaped template metadata (screenshot, title, author, version)
`admin/settings/class-rcno-reviews-callback-helper.php:273-283` -- Fix: `esc_url()` / `esc_html()`

### M8. `$args['desc']` output without escaping in settings callbacks
`admin/settings/class-rcno-reviews-callback-helper.php` (multiple callbacks) -- Fix: `wp_kses_post()`

### M9. Unescaped taxonomy data in isotope HTML
`public/shortcodes/layouts/rcno-isotope-html.php:71-78` -- Fix: `esc_attr()` / `esc_html()`

### M10. Unescaped term name + wrong `esc_url_raw()` in taxonomy list widget
`public/widgets/class-rcno-reviews-taxonomy-list.php:119-120` -- Fix: `esc_url()` + `esc_html()`

### M11. CSS injection via unescaped inline styles
`public/class-rcno-reviews-public.php:147-171` -- Fix: `sanitize_hex_color()` / `wp_strip_all_tags()`
`public/class-rcno-reviews-public-ratings.php:130-141` -- Fix: `sanitize_hex_color()`

### M12. Unescaped `$accent_2` in style attribute
`public/shortcodes/class-rcno-score-box-shortcode.php:142` -- Fix: `esc_attr()`
`public/class-rcno-template-tags.php:1395` -- Fix: `esc_attr()`

### M13. Unescaped `$background` in admin rating
`public/class-rcno-template-tags.php:282` -- Fix: `esc_attr()`

### M14. Unescaped shortcode heading attribute
`public/shortcodes/class-rcno-book-listing-shortcode.php:123` -- Fix: `esc_html()`

### M15. Unescaped book title in book listing shortcode
`public/shortcodes/class-rcno-book-listing-shortcode.php:143` -- Fix: `esc_html()`

### M16. Social media sharing -- unescaped title and button color
`extensions/rcno-social-media-sharing/includes/share-buttons.php:15,25-28` -- Fix: `esc_html()` / `esc_attr()`
`extensions/rcno-social-media-sharing/includes/all-buttons.php` (12 instances) -- Fix: `esc_attr()`

### M17. Widget titles not escaped
`public/widgets/class-rcno-reviews-calendar.php:79-81` -- Fix: `esc_html()`
`public/widgets/class-rcno-reviews-tag-cloud.php:101-102` -- Fix: `esc_html()`

### M18. Unsanitized `$_POST['search']`
`public/widgets/class-rcno-reviews-search-bar.php:137` -- Fix: `sanitize_text_field()`

### M19. Unsanitized `$_GET` access (post_type, s, taxonomy filter)
`admin/class-rcno-reviews-admin.php:882,893,904,919` -- Fix: `isset()` + `sanitize_text_field()`

---

## LOW FINDINGS

### L1. `_e()` used instead of `esc_html_e()` in admin views
Multiple admin view files -- Fix: Replace with `esc_html_e()`

### L2. Unescaped admin column image src
`admin/class-rcno-reviews-admin.php:829` -- Fix: `esc_url()`

### L3. `esc_attr()` used instead of `esc_html()` for HTML content
`public/class-rcno-template-tags.php:1098,1229` -- Fix: `esc_html()`

### L4. Unescaped `$_SERVER['HTTP_USER_AGENT']` access
`public/widgets/class-rcno-reviews-calendar.php:331` -- Fix: `sanitize_text_field( wp_unslash() )`

### L5. Extension button IDs not escaped (defense-in-depth)
`includes/abstracts/Abstract_Rcno_Extension.php:70+` (8 instances) -- Fix: `esc_attr()`

### L6. Extension settings modal titles not escaped
4 extension settings-page.php files -- Fix: `esc_html()`

### L7. Goodreads XML parsing without entity protection
`includes/class-rcno-goodreads-api.php:129` -- Fix: Add `LIBXML_NOENT | LIBXML_NONET`

### L8. Debug echo of XML data left in production
`includes/class-rcno-goodreads-api.php:277` -- Fix: Remove the echo

### L9. Unvalidated cookie access in ratings
`public/class-rcno-reviews-public-ratings.php:229-231` -- Fix: `isset()` + `sanitize_text_field()`

---

## PHP DEPRECATION WARNINGS

### D1. Required parameter after optional parameters
**File:** `public/class-rcno-template-tags.php:1132`

```php
public function the_rcno_books_in_series( $review_id, $taxonomy = 'rcno_series', $number = true, $header ) {
```

`$header` is required but follows optional parameters. PHP 8.0+ deprecation warning.

**Fix:** `$header = ''` (add default value)

---

## Root Cause Analysis: CVE-2024-33648

The CVE is "Stored XSS via Contributor+ users." The attack flow is:

1. A user with **contributor** role creates/edits a book review
2. They inject JavaScript into one of: book description (C1), review score criteria labels (C3), book title, or other post meta fields that are saved without sanitization (C2)
3. When any visitor views the review on the public site, the unsanitized content is rendered by template tags and shortcodes without `esc_html()` or `wp_kses_post()`
4. The injected script executes in the visitor's browser

The most likely specific vectors are **C1** (book description), **C3** (score criteria labels), and **H1** (book titles in grids) because these are the fields where:
- Contributors can write content
- The save handler does not sanitize
- The output handler does not escape

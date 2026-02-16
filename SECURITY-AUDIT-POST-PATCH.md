# Post-Patch Security Audit Verification Report

**Plugin:** Recencio Book Reviews v1.66.0 -> v1.67.0
**CVE:** CVE-2024-33648 (Stored XSS via Contributor+ users, CVSS 6.4)
**Date:** 16 February 2026
**Patch Commit:** `511922e`

---

## Summary

All 48 original findings from SECURITY-AUDIT.md were re-verified against the patched codebase.

| Severity | Total | Fixed | Partial | Notes |
|----------|-------|-------|---------|-------|
| Critical | 4 | 4 | 0 | CVE root cause resolved |
| High | 16 | 16 | 0 | |
| Medium | 19 | 18 | 1 | M8: `$args['desc']` partial |
| Low | 9 | 8 | 1 | L1: `_e()` vs `esc_html_e()` partial |
| Deprecation | 1 | 1 | 0 | |
| **Total** | **49** | **47** | **2** | |

**CVE-2024-33648 Status: RESOLVED.** All Critical and High findings are fully patched. The two partial findings are Low/Medium severity defense-in-depth items that do not affect the CVE fix.

---

## CRITICAL FINDINGS

| ID | File | Status | Evidence |
|----|------|--------|----------|
| C1 | `public/class-rcno-template-tags.php:677` | **FIXED** | `wp_kses_post( apply_filters( 'rcno_book_description', $book_description, $review_id ) )` |
| C2 | `admin/class-rcno-admin-review-score.php:132-142` | **FIXED** | All three fields sanitized: `sanitize_text_field( $data['rcno_review_score_type'] )`, `..._position`, `..._enable` |
| C3 | `public/class-rcno-template-tags.php:1411` + `public/shortcodes/class-rcno-score-box-shortcode.php:155` | **FIXED** | `esc_html( $criteria['label'] )` in both locations |
| C4 | `public/shortcodes/class-rcno-isotope-grid-shortcode.php:182` | **FIXED** | Nonce check uncommented and corrected: `wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'rcno-isotope' )` |

---

## HIGH FINDINGS

| ID | File(s) | Status | Evidence |
|----|---------|--------|----------|
| H1 | `class-rcno-grid-shortcode.php:169`, `reviews_index.php:93`, `reviews_grid.php:54` | **FIXED** | `esc_html( $book['unsorted_title'] )` in all three |
| H2 | `class-rcno-reviews-currently-reading.php:133,148-151` | **FIXED** | `esc_url()` for cover, `esc_html()` for title/author/comment |
| H3 | `class-rcno-reviews-admin.php:865` | **FIXED** | `$wpdb->prepare( " AND (taxonomy = %s OR taxonomy IS NULL)", $taxonomy )` |
| H4 | `class-rcno-reviews-calendar.php` (6 queries) | **FIXED** | All SQL queries rewritten with `$wpdb->prepare()` |
| H5 | `class-rcno-reviews-search-bar.php:137-139` | **FIXED** | `check_ajax_referer( 'search-bar', 'nonce' )` + `sanitize_text_field( wp_unslash( $_POST['search'] ) )` |
| H6 | `class-rcno-admin-book-cover.php:94` + view | **FIXED** | `wp_nonce_field()` in view, `wp_verify_nonce()` in save handler |
| H7 | `class-rcno-admin-review-rating.php:100` + view | **FIXED** | `wp_nonce_field()` in view, `wp_verify_nonce()` in save handler |
| H8 | `class-rcno-admin-review-score.php:107` | **FIXED** | `wp_verify_nonce( $data['rcno_repeatable_meta_box_nonce'], 'rcno_repeatable_meta_box_nonce' )` |
| H9 | `class-rcno-admin-description-meta.php:112` | **FIXED** | `wp_nonce_field()` + `wp_verify_nonce()` present |
| H10 | `class-rcno-reviews-admin.php:1331` | **FIXED** | `$settings = array_map( 'sanitize_text_field', $settings )` |
| H11 | `class-rcno-reviews-public-ratings.php:205` | **FIXED** | `esc_html( $this->ratings_label )` |
| H12 | `author-box.php:23,67` | **FIXED** | `esc_html( $this->get_setting( 'author_box_title' ) )` + `esc_html( $author )` |
| H13 | `custom-user-metadata-fields.php:16,22` | **FIXED** | `esc_html( $custom_meta )` + `esc_attr( get_post_meta(...) )` |
| H14 | `rcno-fetch-book-cover.php:98` | **FIXED** | `wp_safe_remote_get()` replaces `wp_remote_get()` |
| H15 | `class-rcno-reviews-recent-reviews.php:117` | **FIXED** | `esc_html( $review->post_title )` |
| H16 | `class-rcno-template-tags.php:1376` | **FIXED** | `esc_html( wp_strip_all_tags( ... ) )` |

---

## MEDIUM FINDINGS

| ID | File(s) | Status | Evidence |
|----|---------|--------|----------|
| M1 | `rcno-reviews-isbn-metabox.php:29` | **FIXED** | `esc_attr( $external_api )` |
| M2 | `rcno-reviews-isbn-metabox.php:27` | **FIXED** | `esc_attr( $isbn )` |
| M3 | `rcno-reviews-taxonomies-metabox.php:12-14` | **FIXED** | `esc_attr()` on taxonomy name and term value |
| M4 | `rcno-reviews-listings-modal.php:33` | **FIXED** | `esc_attr( 'rcno_' . strtolower( $key ) )` |
| M5 | `class-rcno-reviews-callback-helper.php:434` | **FIXED** | `esc_attr( $value )` |
| M6 | `class-rcno-reviews-callback-helper.php:518,551` | **FIXED** | `esc_attr( $option )` + `esc_html( $option_name )` |
| M7 | `class-rcno-reviews-callback-helper.php:273-283` | **FIXED** | `esc_url( $option['screenshot'] )` + `esc_html()` for title/author/version |
| M8 | `class-rcno-reviews-callback-helper.php` | **PARTIAL** | `get_label_for()` uses `wp_kses_post( $desc )`, but `instruction_callback()`, `multicheck_callback()`, and `radio_callback()` still output `$args['desc']` raw. Low risk: these values come from plugin-defined settings registrations, not user input. |
| M9 | `rcno-isotope-html.php:71-78` | **FIXED** | `esc_attr()` + `esc_html()` on taxonomy data |
| M10 | `class-rcno-reviews-taxonomy-list.php:119-120` | **FIXED** | `esc_url()` + `esc_html()` |
| M11 | `class-rcno-reviews-public.php:167-170` + `class-rcno-reviews-public-ratings.php:132-138` | **FIXED** | `esc_attr()` + `wp_strip_all_tags()` for inline styles |
| M12 | `class-rcno-score-box-shortcode.php:142` + `class-rcno-template-tags.php:1395` | **FIXED** | `esc_attr( $accent_2 )` in both |
| M13 | `class-rcno-template-tags.php:282` | **FIXED** | `esc_attr( $background )` |
| M14 | `class-rcno-book-listing-shortcode.php:123` | **FIXED** | `esc_html( $atts['heading'] )` |
| M15 | `class-rcno-book-listing-shortcode.php:143` | **FIXED** | `esc_html( $book_title )` |
| M16 | `share-buttons.php:15,26-27` + `all-buttons.php` | **FIXED** | `esc_html()` for title, `esc_attr( $button_color )` for all 12+ color outputs |
| M17 | `class-rcno-reviews-calendar.php:80` + `class-rcno-reviews-tag-cloud.php:102` | **FIXED** | `esc_html( $title )` in both widgets |
| M18 | `class-rcno-reviews-search-bar.php:139` | **FIXED** | `sanitize_text_field( wp_unslash( $_POST['search'] ) )` |
| M19 | `class-rcno-reviews-admin.php:882,893,904,919` | **FIXED** | `sanitize_text_field( wp_unslash( $_GET[...] ) )` with `isset()` guards |

---

## LOW FINDINGS

| ID | File(s) | Status | Evidence |
|----|---------|--------|----------|
| L1 | Admin view files | **PARTIAL** | Many `_e()` calls converted to `esc_html_e()` in modified files. Some view files not in patch scope (e.g., `rcno-reviews-modal.php`, `rcno-buy-links-metabox.php`) still use `_e()`. Low risk: translation strings are developer-controlled. |
| L2 | `class-rcno-reviews-admin.php:829` | **FIXED** | `esc_url( $book_src )` |
| L3 | `class-rcno-template-tags.php:1098,1229` | **FIXED** | `esc_html( $header )` in both locations |
| L4 | `class-rcno-reviews-calendar.php:332` | **FIXED** | `sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) )` |
| L5 | `Abstract_Rcno_Extension.php:70+` | **FIXED** | All 8 button ID attributes use `esc_attr( $this->id )` |
| L6 | Four extension `settings-page.php` files | **FIXED** | All use `esc_html( $this->title )` + `esc_html__()` in modal title |
| L7 | `class-rcno-goodreads-api.php:129` | **FIXED** | `LIBXML_NOENT \| LIBXML_NONET` flags added to `simplexml_load_string()` |
| L8 | `class-rcno-goodreads-api.php:277` | **FIXED** | Debug echo removed |
| L9 | `class-rcno-reviews-public-ratings.php:229-231` | **FIXED** | `isset()` + `sanitize_text_field()` / `sanitize_email()` / `esc_url_raw()` with `wp_unslash()` |

---

## DEPRECATION

| ID | File | Status | Evidence |
|----|------|--------|----------|
| D1 | `class-rcno-template-tags.php:1132` | **FIXED** | `$header = ''` default value added |

---

## Partial Fix Notes

### M8 — `$args['desc']` in settings callbacks
Three callback methods (`instruction_callback`, `multicheck_callback`, `radio_callback`) output `$args['desc']` without `wp_kses_post()`. However, these description strings originate from the plugin's own `register_setting()` calls and are not user-editable. The `get_label_for()` helper (used by most other callbacks) correctly applies `wp_kses_post()`. **Risk: Negligible** — would require a compromised plugin file to exploit.

### L1 — `_e()` in admin views
WordPress `_e()` outputs translated strings. An exploit would require a malicious `.po/.mo` translation file. The files where `_e()` remains are admin-only views not modified by this patch. **Risk: Negligible** in practice.

---

## Conclusion

**CVE-2024-33648 is fully patched.** All 4 Critical and 16 High findings — which constitute the stored XSS attack surface — are confirmed fixed. The two partial findings are defense-in-depth items with negligible real-world risk. The patch is safe to release as v1.67.0.

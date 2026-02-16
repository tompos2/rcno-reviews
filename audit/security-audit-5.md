# Security Audit 5 — Clean-Room Independent Review

**Date:** 2026-02-16
**Auditor:** Claude Opus 4.6 (automated, clean-room — no prior audit context)
**Scope:** Full codebase (~90 PHP files across admin/, public/, includes/, extensions/)
**Methodology:** Three-phase approach (Repository Context, Comparative Analysis, Vulnerability Assessment) with parallel false-positive validation

---

## Audit Process

**Phase 1 — Vulnerability Identification:** A comprehensive sub-task read every PHP file in the repository, tracing data flows from user inputs to sensitive operations (database writes, HTML output, URL fetches). It identified 7 candidate findings.

**Phase 2 — Confidence Threshold Filter:** 3 findings were immediately filtered out for having initial confidence below 0.8:

- SSRF in `class-rcno-goodreads-api.php` (`wp_remote_get` vs `wp_safe_remote_get`) — confidence 0.75
- Stored XSS via unescaped color values in `class-rcno-template-tags.php` — confidence 0.70
- Missing auth in commented-out AJAX handler `gr_ajax_save_post_meta()` — confidence 0.75

**Phase 3 — False Positive Validation:** 4 independent parallel sub-tasks validated each remaining finding against the following criteria:

1. Is the data actually user-controlled, or is it plugin-internal / developer-defined?
2. Is there upstream sanitisation or escaping that was missed?
3. Does the context (e.g. admin-only page behind capability checks) reduce the real-world risk?
4. Would a security engineer confidently flag this in a code review?

---

## Findings After Validation

### Finding 1: Google Books API Key Exposed via `wp_localize_script()`

- **File:** `includes/class-rcno-reviews-googlebooks.php`
- **Line(s):** 88–94
- **Initial Severity:** MEDIUM
- **Category:** data_exposure
- **Initial Confidence:** 0.95
- **Validated Confidence:** 0.45
- **Verdict:** FALSE POSITIVE

**Description:** The Google Books API key is passed to the browser via `wp_localize_script()` as `gb_options.api_key`.

**Why it was rejected:** The JavaScript file (`admin/js/rcno-reviews-google-books.js`) never references `gb_options.api_key`. The Google Books API call is made without appending the key. The key is dead data being needlessly exposed. Additionally, the exposure is limited to admin-side review edit screens (authenticated users with `edit_posts` capability). The worst-case impact is quota exhaustion on a free API.

---

### Finding 2: Goodreads API Key Exposed via `wp_localize_script()`

- **File:** `includes/class-rcno-goodreads-api.php`
- **Line(s):** 59–63
- **Initial Severity:** MEDIUM
- **Category:** data_exposure
- **Initial Confidence:** 0.95
- **Validated Confidence:** 0.20
- **Verdict:** FALSE POSITIVE

**Description:** The Goodreads API key is embedded as `gr_options.api_key` in the page's JavaScript on review edit screens.

**Why it was rejected:** The Goodreads API has been defunct since December 2020. The feature is disabled by default (`rcno_external_book_api` defaults to `'no-3rd-party'`). An extracted key has zero utility — there is no service to make unauthorized requests against. Exposure is limited to authenticated backend users.

---

### Finding 3: XXE Risk in `parseXML()` with `LIBXML_NOENT`

- **File:** `includes/class-rcno-goodreads-api.php`
- **Line(s):** 128–129
- **Initial Severity:** MEDIUM
- **Category:** xxe
- **Initial Confidence:** 0.85
- **Validated Confidence:** 0.10
- **Verdict:** FALSE POSITIVE

**Description:** The `parseXML()` method uses `LIBXML_NOENT` flag when parsing XML, which enables entity substitution and could facilitate XXE attacks.

**Why it was rejected:** `parseXML()` is dead code — no execution path ever reaches it. The `getData()` method has a `$raw` parameter that defaults to `true`, and every single call to `getData()` in the codebase uses this default. When `$raw` is `true`, the method returns the raw XML string at line 150 and never reaches the `parseXML()` call at line 154. The AJAX handler that would have invoked this code path is commented out at line 487 of `class-rcno-reviews.php`. The active Goodreads code path (`rcno_gr_remote_get` in `class-rcno-reviews-admin.php`) parses XML client-side in JavaScript, completely bypassing this PHP method.

---

### Finding 4: Incomplete Settings Import Validation

- **File:** `admin/class-rcno-reviews-admin.php`
- **Line(s):** 1304–1345
- **Initial Severity:** MEDIUM
- **Category:** input_validation
- **Initial Confidence:** 0.85
- **Validated Confidence:** 0.15
- **Verdict:** FALSE POSITIVE

**Description:** The settings import handler uses `array_map('sanitize_text_field', $settings)` which doesn't handle nested values and doesn't enforce a settings schema.

**Why it was rejected:** The handler has proper nonce verification (line 1313) and `manage_options` capability check (line 1320). Users with `manage_options` can already change any WordPress option through normal UI or directly via the database. The import handler actually constrains them more than their existing capabilities (applying `sanitize_text_field` and only writing to the `rcno_reviews_settings` option key). The `sanitize_text_field` on arrays issue destroys nested data (converting to the string "Array") rather than passing it through unsanitized — this is a data-corruption bug, not a security bypass.

---

## Result

**No findings survived false-positive filtering at confidence ≥ 0.8.**

---

## Positive Security Observations

The plugin demonstrates generally good security practices:

- **Nonce verification:** Admin metabox save handlers and AJAX handlers consistently verify nonces via `wp_verify_nonce()` and `check_ajax_referer()`
- **Capability checks:** AJAX handlers check appropriate capabilities (`manage_options`, `edit_post`, etc.)
- **Output escaping:** Template output is broadly well-escaped with `esc_html()`, `esc_attr()`, `esc_url()`, and `wp_kses_post()`
- **Prepared statements:** Database queries use `$wpdb->prepare()` where user input is involved
- **Post type capabilities:** The `rcno_review` custom post type uses proper capability mapping with `map_meta_cap`

---

## Recommendations (Code Quality, Not Security)

While no security vulnerabilities were confirmed, these low-priority cleanups were noted:

1. **Remove unused `api_key` from `wp_localize_script()`** in `class-rcno-reviews-googlebooks.php:92` — the JavaScript never references it
2. **Remove or clearly deprecate the `Rcno_Goodreads_API` class** — the API is defunct, most methods are broken (they operate on raw strings instead of SimpleXMLElement objects), and the commented-out AJAX handler contains dead code with no auth checks
3. **Add `esc_attr()` to color values** in `rcno_calc_review_score()` for defense-in-depth consistency with the rest of the template output

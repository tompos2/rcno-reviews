# Security Audit 3 — Recencio Book Reviews

**Date:** 2026-02-16
**Auditor:** Claude Opus 4.6 (clean-room automated audit)
**Scope:** All 117 PHP files in the plugin
**Methodology:** 5 parallel scanning agents + 5 parallel false-positive filtering agents

## Methodology

- **Phase 1**: Codebase exploration — mapped 117 PHP files, custom post type (`rcno_review`), 4 custom taxonomies, 11 AJAX handlers, 9 shortcode classes, 8 widget classes
- **Phase 2**: 5 parallel scanning agents audited all PHP files across AJAX/admin, shortcodes/widgets, metabox handlers, extensions/APIs, and template tags
- **Phase 3**: 5 parallel false-positive filtering agents independently verified each candidate finding against actual source code, evaluating: (1) Is data actually user-controlled? (2) Is there upstream sanitisation? (3) Does context reduce risk? (4) Would a security engineer confidently raise this?
- **Filtering**: Only HIGH/MEDIUM severity findings with post-verification confidence >= 0.8 are reported

**Raw findings identified**: 34 across all scanners
**After deduplication**: 19 unique findings
**After false-positive filtering**: 4 confirmed findings

---

### Finding 1 — Stored XSS via `javascript:` URI in book meta href

| Field | Value |
|-------|-------|
| **File** | `public/class-rcno-template-tags.php` |
| **Lines** | 899–910 |
| **Severity** | HIGH |
| **Category** | Stored XSS |
| **Confidence** | 0.85 |

**Description:** In `get_the_rcno_book_meta()`, post meta values containing URLs are placed into `<a href>` attributes using `sanitize_text_field()` instead of `esc_url()`. `sanitize_text_field()` strips HTML tags but does **not** validate URL schemes — `javascript:` URIs pass through intact. The `stripos()` check for "goodreads" or "books.google" can be trivially bypassed by including the substring anywhere in the payload (e.g. `javascript:alert(1)//goodreads`). The save handler at `admin/class-rcno-admin-general-info.php:192` also uses `sanitize_text_field()` instead of `esc_url_raw()`.

**Exploit scenario:** An Editor creates a review and sets the GoodReads URL field to `javascript:alert(document.cookie)//goodreads.com`. The value passes `sanitize_text_field()` on save, then the `stripos($value, 'goodreads')` check on output. The resulting HTML is `<a href="javascript:alert(document.cookie)//goodreads.com">GoodReads.com</a>`. Any visitor who clicks the link executes arbitrary JavaScript.

**Recommendation:** Replace `sanitize_text_field()` with `esc_url()` on output (lines 901, 907) and `esc_url_raw()` on save (`admin/class-rcno-admin-general-info.php:192`).

---

### Finding 2 — Missing authorization in post-to-review conversion AJAX

| Field | Value |
|-------|-------|
| **File** | `extensions/rcno-posts-to-reviews/rcno-posts-to-reviews.php` |
| **Lines** | 114–145 |
| **Severity** | HIGH |
| **Category** | Missing Authorization |
| **Confidence** | 0.90 |

**Description:** The `convert_post_review()` AJAX handler uses `check_admin_referer('convert-to-review', 'nonce')` for CSRF protection but has **no** `current_user_can()` check. The action is registered as `wp_ajax_convert_post_review` (authenticated only), but any logged-in user — including Subscribers — who obtains a valid nonce can call the endpoint. The method accepts an arbitrary `postID` via `$_POST['postID']` and calls `set_post_type()` with no per-post capability verification.

**Exploit scenario:** A Subscriber who obtains a valid nonce sends a POST request to `admin-ajax.php?action=convert_post_review` with any `postID`. This converts any post on the site to/from a review, altering site content without authorization.

**Recommendation:** Add `current_user_can( 'edit_post', $post_id )` before processing.

---

### Finding 3 — Missing authorization in extension activate/deactivate AJAX

| Field | Value |
|-------|-------|
| **File** | `includes/class-rcno-reviews-extensions.php` |
| **Lines** | 232–270 (activate), 279–307 (deactivate) |
| **Severity** | MEDIUM |
| **Category** | Missing Authorization |
| **Confidence** | 0.95 |

**Description:** Both `rcno_activate_extension_ajax()` and `rcno_deactivate_extension_ajax()` verify a nonce but contain **no** `current_user_can()` check. The AJAX actions are `wp_ajax_`-only, so authentication is required, but any logged-in user (Subscriber+) can invoke them. WordPress nonces are CSRF tokens — not capability gates.

**Exploit scenario:** A Subscriber-level user who obtains the nonce value sends a POST to `admin-ajax.php?action=rcno_activate_extension_ajax` with a chosen extension slug, activating or deactivating plugin extensions — an administrator-level operation.

**Recommendation:** Add `current_user_can( 'manage_options' )` at the top of both handlers.

---

### Finding 4 — Stored XSS via unescaped taxonomy term names in templates

| Field | Value |
|-------|-------|
| **File** | `public/templates/rcno_default/taxonomy.php` and `public/templates/rcno_metamor/taxonomy.php` |
| **Lines** | 138 (default), 140 (metamor) |
| **Severity** | MEDIUM |
| **Category** | Stored XSS |
| **Confidence** | 0.80 |

**Description:** Both taxonomy template files output taxonomy term names directly into HTML without `esc_html()`. The term name is assigned via `$title = ucfirst( $value['name'] )`, then output as `$out .= $title;` inside an `<a>` tag. WordPress's `sanitize_term()` with context `'db'` does **not** reliably strip HTML from the `name` field. Creating terms requires `manage_categories` (Editor+).

**Exploit scenario:** An Editor creates an Author taxonomy term named `Test<img src=x onerror=alert(document.cookie)>`. When any visitor views the taxonomy index page, the term name renders unescaped, executing the injected JavaScript.

**Recommendation:** Escape with `esc_html( $title )` at the point of output in both template files.

---

## Summary

| # | Title | Severity | Category | Confidence |
|---|-------|----------|----------|------------|
| 1 | `javascript:` URI in book meta href via `sanitize_text_field()` | HIGH | Stored XSS | 0.85 |
| 2 | Missing authorization in post-to-review conversion AJAX | HIGH | Missing Authorization | 0.90 |
| 3 | Missing authorization in extension activate/deactivate AJAX | MEDIUM | Missing Authorization | 0.95 |
| 4 | Unescaped taxonomy term names in template output | MEDIUM | Stored XSS | 0.80 |

## Notable Rejected Findings

| Finding | Reason for Rejection |
|---------|---------------------|
| XXE via `LIBXML_NOENT` flag | Requires MITM on HTTPS; PHP 8.0+ mitigates by default (confidence 0.75) |
| `</script>` breakout in table shortcode | Requires post-editing privileges; `wp_json_encode` provides partial mitigation (confidence 0.75) |
| Author description XSS in Author Box | WordPress core applies `wp_kses_data` for non-admin users (confidence 0.70) |
| Settings import shallow sanitization | Requires `manage_options` capability; hardcoded option key (confidence 0.55) |
| API keys exposed to client-side JS | Scripts are admin-only; API keys designed for client use |
| Book cover `javascript:` URI | `<img src>` does not execute `javascript:` URIs; `esc_attr()` on output |
| Custom metadata tag XSS | Shortcode registration commented out — dead code |
| Calendar widget unescaped URLs | All URL components server-controlled |

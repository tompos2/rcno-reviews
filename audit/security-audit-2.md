# Security Audit Report 2: Recencio Book Reviews Plugin (v1.67.0)

**Date:** 2026-02-16
**Scope:** Full codebase (~85 PHP files across admin/, public/, includes/, extensions/)
**Methodology:** Three parallel analysis passes covering admin, public, and includes/extensions directories. Each finding at confidence >= 0.8 independently verified by a dedicated false-positive filtering pass that re-read source code and evaluated exploitability.

---

## Verified Findings

### Finding 1: Missing Capability Check on SSRF-Capable AJAX Handler

- **File:** `admin/class-rcno-reviews-admin.php`
- **Line(s):** 1579-1598
- **Severity:** HIGH
- **Category:** ssrf, authorization_bypass
- **Confidence:** 0.85
- **Description:** The `rcno_gr_remote_get` AJAX handler accepts a URL from `$_POST['gr_url']` and fetches it via `wp_safe_remote_get()`, returning the full HTTP response body and headers to the caller. While it verifies a nonce, it performs **no `current_user_can()` check**. The nonce is generated inside `enqueue_scripts()` which is hooked to `admin_enqueue_scripts` without a screen or capability gate, meaning it is output on every admin page -- including `wp-admin/profile.php`, which Subscribers can access. Any authenticated user (including Subscriber role) can invoke this endpoint.
- **Exploit scenario:** A Subscriber-level user extracts the nonce from any admin page's source, then sends `POST admin-ajax.php` with `action=rcno_gr_remote_get&gr_url=https://attacker-controlled.example.com/probe`. The WordPress server fetches the URL and returns the full response. This turns the server into an open HTTP proxy usable for IP attribution attacks, probing external services from the server's IP, or exfiltrating information about the server's network position. `wp_safe_remote_get()` blocks private IP ranges, which mitigates internal network SSRF, but does not prevent external proxy abuse.
- **Recommendation:** Add a capability check immediately after nonce verification:
  ```php
  if ( ! current_user_can( 'manage_options' ) ) {
      wp_send_json_error();
  }
  ```
  Additionally, validate that `$_POST['gr_url']` matches an expected domain (e.g., Goodreads or Google Books) using `wp_parse_url()` and a domain allowlist.

---

### Finding 2: Insecure Direct Object Reference in Public Comment Rating Handler

- **File:** `public/class-rcno-reviews-public-ratings.php`
- **Line(s):** 219-270
- **Severity:** HIGH
- **Category:** authorization_bypass
- **Confidence:** 0.85
- **Description:** The `rcno_rate_review()` AJAX handler is registered on `wp_ajax_nopriv_rcno_rate_review`, meaning unauthenticated visitors can call it. The nonce is publicly embedded in the page source via `wp_localize_script()` for all visitors viewing a review page. The handler accepts `$_POST['comment_ID']` and directly calls `update_comment_meta()` to set the rating -- but never verifies that the requesting user authored the target comment. The only guard (lines 263-264) checks that comment author cookies exist, not that they match the comment being modified. These cookies are trivially forgeable by any visitor.
- **Exploit scenario:** An attacker visits any review page, extracts the `rcno-ajax-public-ratings-nonce` from the page source, then sends `POST admin-ajax.php` with `action=rcno_rate_review&comment_ID=<any_id>&rating=1`. This overwrites the rating metadata on any comment in the database, allowing systematic manipulation of public review scores without having authored any of those comments.
- **Recommendation:** After nonce verification, verify comment ownership. For logged-in users: `get_comment($comment_ID)->user_id === get_current_user_id()`. For cookie-based users: compare `$comment_author_cookie` against `get_comment($comment_ID)->comment_author`. Also validate that the comment belongs to an `rcno_review` post type.

---

### Finding 3: Stored XSS via Unescaped Book Title in Metamor Template (Reviews Index)

- **File:** `public/templates/rcno_metamor/reviews_index.php`
- **Line(s):** 88
- **Severity:** MEDIUM
- **Category:** xss
- **Confidence:** 0.85
- **Description:** The book title from post meta is output without escaping: `$out .= '<p>' . $book['title'] . '</p>';`. The `$book['title']` value originates from `get_post_custom()` reading `rcno_book_title`. The equivalent code in the `rcno_default` template at `public/templates/rcno_default/reviews_index.php:93` correctly uses `esc_html()`. This inconsistency confirms an unintentional omission. The save handler in `class-rcno-admin-general-info.php:104` applies `sanitize_text_field()`, which strips tags and provides significant input-side mitigation. However, if data enters via another path (REST API, direct DB modification, import), the missing output escaping becomes directly exploitable.
- **Exploit scenario:** If a book title containing JavaScript were stored via any path that bypasses `sanitize_text_field()` (e.g., database migration, REST API, or a future code change), it would execute in the browser of every visitor viewing the Metamor-template reviews index page. Even with current input sanitization, the defence-in-depth violation means a single upstream change could expose this to exploitation.
- **Recommendation:** Add `esc_html()` to match the `rcno_default` template:
  ```php
  $out .= '<p>' . esc_html( $book['title'] ) . '</p>';
  ```

---

### Finding 4: Stored XSS via Unescaped Book Title in Metamor Template (Reviews Grid)

- **File:** `public/templates/rcno_metamor/reviews_grid.php`
- **Line(s):** 54
- **Severity:** MEDIUM
- **Category:** xss
- **Confidence:** 0.85
- **Description:** Same class of vulnerability as Finding 3. The unsorted book title is output without escaping: `$out .= '<p>' . $book['unsorted_title'] . '</p>';`. The `rcno_default` template at `public/templates/rcno_default/reviews_grid.php:54` correctly uses `esc_html()`. The same input-side mitigation (`sanitize_text_field()` at save time) and the same defence-in-depth concerns apply.
- **Exploit scenario:** Identical to Finding 3 but affecting the Metamor grid view rather than the index view.
- **Recommendation:** Add `esc_html()`:
  ```php
  $out .= '<p>' . esc_html( $book['unsorted_title'] ) . '</p>';
  ```

---

## Summary Table

| # | Title | Severity | Category | Confidence |
|---|-------|----------|----------|------------|
| 1 | Missing capability check on SSRF-capable AJAX handler | HIGH | ssrf, auth_bypass | 0.85 |
| 2 | IDOR in public comment rating handler | HIGH | auth_bypass | 0.85 |
| 3 | Unescaped book title in Metamor reviews_index.php | MEDIUM | xss | 0.85 |
| 4 | Unescaped book title in Metamor reviews_grid.php | MEDIUM | xss | 0.85 |

---

## Findings Excluded After False-Positive Filtering

The following initial findings were **rejected** during verification:

| Initial Finding | Reason for Exclusion |
|---|---|
| SQL injection in Calendar Widget | `$post_types` is never user-controlled (hardcoded or from `get_post_types()`), validated via `post_type_exists()`, and escaped with `esc_sql()` |
| Missing capability on extension AJAX handlers | Nonce is only output on admin pages gated by `manage_options`, making it inaccessible to low-privilege users (confidence dropped to 0.65) |
| `gr_ajax_save_post_meta` (dead code) | Hook registration is commented out; method is completely unreachable |
| Settings import nested array sanitization | Requires `manage_options`; admins already have equivalent access to all settings |
| Settings import arbitrary keys | Same as above; stored in single plugin option, not arbitrary WP options |
| Isotope grid width/height XSS | Values come from shortcode attributes set by authors who already have HTML insertion capability |
| Author box unescaped description | WordPress core applies `wp_filter_kses()` on input for non-privileged users; extension is off by default (confidence 0.75) |
| Missing top-level nonce in `rcno_save_review()` | All 7 sub-handlers independently verify nonces; no data is written without nonce validation |
| Taxonomy metabox HTML in input value | `esc_attr()` correctly prevents XSS; no downstream processing found |
| API keys in client-side JavaScript | Admin-only pages; read-only API keys (Google Books is public by design; Goodreads API is defunct) |

---

## Positive Security Observations

The codebase demonstrates several strong security practices:

- All metabox save handlers individually verify nonces before writing data
- The settings export/import handlers properly check `manage_options` and nonces
- `wp_safe_remote_get()` is used instead of raw `file_get_contents()` for remote requests
- The `rcno_default` template consistently uses `esc_html()`, `esc_url()`, and `esc_attr()` for output
- Input sanitization via `sanitize_text_field()` is consistently applied across save handlers
- Database queries primarily use `WP_Query` rather than raw SQL
- The `more_filtered_reviews` AJAX handler properly verifies nonces

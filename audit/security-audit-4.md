# Security Audit 4 — Clean-Room Independent Review

**Date:** 2026-02-16
**Auditor:** Claude Opus 4.6 (automated, clean-room — no prior audit context)
**Scope:** Full codebase (108 PHP files)
**Plugin:** Recencio Book Reviews v1.69.0

## Methodology

- **Phase 1:** Codebase exploration — mapped all PHP files, identified custom post types, taxonomies, meta fields, settings, shortcodes, widgets, extensions, and security patterns.
- **Phase 2:** Deep security audit — read and analyzed all PHP files for SQL injection, XSS, CSRF, SSRF, authentication bypass, and data exposure. 10 initial findings identified.
- **Phase 3:** False positive validation — 6 parallel independent validators traced full data flows, checked upstream protections, and assessed real-world exploitability for each finding with initial confidence >= 0.8.

## Filtering Results

| # | Finding | Initial Confidence | Validation Result | Reason |
|---|---------|-------------------|-------------------|--------|
| 1 | Goodreads AJAX CSRF | 0.98 | **False Positive** | `add_action` hook is commented out (line 487, `class-rcno-reviews.php`) — dead code |
| 2 | Goodreads API key exposure | 0.90 | **False Positive** | Goodreads API defunct since 2020; admin-only; feature-gated |
| 3 | Google Books API key exposure | 0.90 | **False Positive** | Admin-only; browser keys by design; key not consumed by JS (dead code) |
| 4 | Stored XSS in purchase links | 0.85 | **True Positive (0.95)** | No sanitization on save, no escaping on output |
| 5 | Custom metadata missing nonce | 0.82 | **False Positive** | WordPress core `check_admin_referer()` provides upstream CSRF protection |
| 6 | Shortcode tag HTML injection | 0.80 | **False Positive** | `add_shortcode()` is commented out — dead code |
| 7-10 | Various LOW findings | < 0.80 | **Pre-filtered** | Below confidence threshold |

## Confirmed Findings

### Finding 1: Stored XSS via Unescaped Style Attributes in Purchase Links

- **File:** `public/class-rcno-template-tags.php`
- **Line(s):** 1008-1009
- **Severity:** MEDIUM
- **Category:** xss (stored)
- **Confidence:** 0.95

**Description:**
The `get_the_rcno_book_purchase_links()` method outputs `$background` and `$txt_color` plugin option values directly into HTML `style` attributes without `esc_attr()` escaping:

```php
$background = Rcno_Reviews_Option::get_option( 'rcno_store_purchase_link_background' );
$txt_color  = Rcno_Reviews_Option::get_option( 'rcno_store_purchase_link_text_color' );
// ...
. ' style="background:' . $background . '; color:' . $txt_color . '"'
```

Both fields are registered with `'type' => 'color'` in the settings definition, but **no `rcno_reviews_settings_sanitize_color` filter is registered** in the sanitization helper. The registered sanitizers cover `text`, `slug`, `email`, `checkbox`, `url`, `cssbox`, and `labels` — but not `color`. Values pass through unsanitized on save, are stored raw in the database, and are output without escaping.

**Exploit scenario:**
An attacker with admin access (or via CSRF against an admin) sets the background color to `red" onmouseover="alert(document.cookie)`. This produces:

```html
<a ... style="background:red" onmouseover="alert(document.cookie)"; color:..." ...>
```

The double-quote breaks out of the `style` attribute, injecting an arbitrary event handler. This persists across all frontend pages displaying purchase links. On WordPress multisite, site administrators lack `unfiltered_html`, making this a privilege escalation vector.

**Recommendation:**

1. **Output escaping** (immediate): Wrap both values with `esc_attr()` in the style attribute.
2. **Input sanitization** (defense-in-depth): Register a `color` type sanitizer in the sanitization helper that validates against `sanitize_hex_color()` or a regex for valid CSS color formats.

## Overall Assessment

The plugin follows generally good WordPress security practices. Metabox save handlers consistently verify nonces and sanitize input. The settings framework uses type-based sanitization. AJAX handlers check nonces and capabilities. REST API endpoints use `permission_callback`.

The single confirmed vulnerability is a MEDIUM-severity stored XSS from missing `esc_attr()` on color option output combined with a missing `color` type sanitizer.

Several disabled code paths (Goodreads AJAX handler, custom metadata shortcode) contain insecure patterns but are currently unreachable via commented-out registrations. These should be fixed before re-enabling.

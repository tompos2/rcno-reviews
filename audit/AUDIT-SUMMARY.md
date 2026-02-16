# Security Audit Summary — Recencio Book Reviews

**Plugin:** Recencio Book Reviews (WordPress)
**CVE:** CVE-2024-33648 (Stored XSS via Contributor+, CVSS 6.4)
**Auditor:** Claude Opus 4.6 (automated, iterative)
**Date:** 16 February 2026
**Versions:** v1.66.0 (start) → v1.69.0+ (final)

---

## Process Overview

Five sequential security audits were performed in an **audit-then-patch loop**: each audit scanned the full codebase, findings were patched, then the next audit re-scanned from scratch to catch anything missed or introduced. Each audit used parallel scanning agents and independent false-positive filtering to minimise noise.

| Audit | Codebase Version | Raw Candidates | Confirmed Findings | Post-Patch Status |
|-------|-----------------|----------------|--------------------|--------------------|
| 1 | v1.66.0 | 48+ | **49** (4C / 16H / 19M / 9L / 1D) | All 49 fixed |
| 2 | v1.67.0 | 10+ | **4** (2H / 2M) | All 4 fixed |
| 3 | v1.68.0 | 34 → 19 deduplicated | **4** (2H / 2M) | All 4 fixed |
| 4 | v1.69.0 | 10 | **1** (1M) | 1 fixed |
| 5 | v1.69.0+ | 7 | **0** | Clean — no findings |

**Total vulnerabilities found and fixed: 58**

---

## Vulnerability Trajectory

```
Audit 1  ████████████████████████████████████████████████░  49 findings
Audit 2  ████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░   4 findings
Audit 3  ████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░   4 findings
Audit 4  █░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░   1 finding
Audit 5  ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░   0 findings  ✓ CLEAN
```

---

## Findings by Category (All 58)

| Category | Count | Examples |
|----------|-------|---------|
| Stored XSS (output escaping) | 30 | Unescaped book titles, descriptions, labels, widget data, template outputs |
| Missing Nonce / CSRF | 6 | Commented-out nonce check, missing nonce fields in metaboxes |
| Missing Authorization | 4 | AJAX handlers with nonce but no `current_user_can()` |
| SQL Injection | 2 | Raw string interpolation in calendar widget, taxonomy sort |
| SSRF | 2 | Unrestricted URL fetch via `wp_remote_get()`, subscriber-accessible proxy |
| Input Sanitization | 5 | Raw `$_POST`/`$_GET` usage, unsanitized review score saves |
| CSS Injection | 3 | Unescaped inline style values from plugin options |
| IDOR | 1 | Comment rating handler allowing modification of any comment's rating |
| Insecure XML Parsing | 1 | Missing `LIBXML_NONET` flag |
| Data Exposure | 1 | Debug echo left in production |
| PHP Deprecation | 1 | Required parameter after optional parameter |
| Other (settings import, cookie validation) | 2 | Bypassed sanitization on settings import |

---

## Severity Breakdown (All 58)

| Severity | Audit 1 | Audit 2 | Audit 3 | Audit 4 | Audit 5 | Total |
|----------|---------|---------|---------|---------|---------|-------|
| Critical | 4 | — | — | — | — | **4** |
| High | 16 | 2 | 2 | — | — | **20** |
| Medium | 19 | 2 | 2 | 1 | — | **24** |
| Low | 9 | — | — | — | — | **9** |
| Deprecation | 1 | — | — | — | — | **1** |
| **Total** | **49** | **4** | **4** | **1** | **0** | **58** |

---

## What Each Audit Found

### Audit 1 — Initial Comprehensive Scan (49 findings)
The bulk of the work. Identified the root cause of CVE-2024-33648 (unescaped book description, score criteria labels, book titles) plus widespread missing output escaping, missing nonces in metabox save handlers, SQL injection in the calendar widget, SSRF via book cover fetch, and unsanitized `$_POST`/`$_GET` access. All 49 findings patched in commit `511922e`.

### Audit 2 — First Re-Scan (4 findings)
Found issues the first audit missed: a subscriber-accessible SSRF proxy (AJAX handler with nonce but no capability check), an IDOR in the public comment rating handler, and two unescaped book titles in the Metamor template (the first audit only checked the default template). All 4 patched in commit `2c19ad2`.

### Audit 3 — Second Re-Scan (4 findings)
Discovered a `javascript:` URI bypass in book meta href attributes (where `sanitize_text_field()` was used instead of `esc_url()`), missing authorization in the post-to-review conversion AJAX handler, missing authorization in extension activate/deactivate AJAX handlers, and unescaped taxonomy term names in both template sets.

### Audit 4 — Third Re-Scan (1 finding)
Found a stored XSS via unescaped color option values in purchase link style attributes, caused by a missing `color` type sanitizer in the settings sanitization framework. Fixed with `esc_attr()` on output and a new `sanitize_color_field()` method on input.

### Audit 5 — Final Clean-Room Scan (0 findings)
All 7 candidate findings were rejected during false-positive validation. The codebase now demonstrates consistent nonce verification, capability checks, output escaping, and prepared SQL statements. **No actionable vulnerabilities remain.**

---

## CVE-2024-33648 Resolution

The original CVE (Stored XSS via Contributor+ users) was fully resolved in Audit 1. The attack chain — contributor injects JavaScript into book metadata fields, which renders unescaped on the public frontend — was broken by:

1. **Input sanitization** on all metabox save handlers (`sanitize_text_field()`, `esc_url_raw()`)
2. **Output escaping** on all template rendering (`esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`)
3. **Nonce verification** on all save handlers that were missing it
4. **Capability checks** on all AJAX handlers

---

## Files in This Directory

| File | Description |
|------|-------------|
| `security-audit-1.md` | Initial audit — 49 findings across all severity levels |
| `security-audit-1-post-patch.md` | Verification that all 49 findings were fixed |
| `security-audit-2.md` | Second audit — 4 new findings (SSRF, IDOR, XSS) |
| `security-audit-2-post-patch.md` | Verification that all 4 findings were fixed |
| `security-audit-3.md` | Third audit — 4 new findings (javascript: URI, auth bypass, XSS) |
| `security-audit-3-post-patch.md` | Verification that all 4 findings were fixed |
| `security-audit-4.md` | Fourth audit — 1 finding (CSS injection via color options) |
| `security-audit-4-post-patch.md` | Verification that the finding was fixed |
| `security-audit-5.md` | Fifth and final audit — 0 confirmed findings (clean) |

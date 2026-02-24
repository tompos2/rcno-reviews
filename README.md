# Recencio Book Reviews

A WordPress plugin for managing and displaying book reviews. Custom post type, 7 taxonomies, 13 shortcodes, 7 widgets, 6 templates, 5 extensions. All data stored in standard WordPress post meta — no custom tables.

## Background

Recencio Book Reviews was created by **Kemory Grubb** (w33zy) and published on the WordPress plugin directory. Kemory passed away in 2024. The plugin was left unmaintained with a known stored XSS vulnerability ([CVE-2024-33648](https://www.cve.org/CVERecord?id=CVE-2024-33648)) and no path to a fix.

In 2026, Tom Stayte adopted the plugin. The original source was forked from [Kemory's GitLab repository](https://gitlab.com/w33zy/rcno-reviews), the CVE was patched, and a full security audit was conducted before deploying to production.

This repository is the continuation of that work. The plugin will be resubmitted to the WordPress plugin directory under new maintainership.

## Security

The plugin has undergone a comprehensive security overhaul across versions 1.67.0 through 1.70.0:

- **[CVE-2024-33648](https://patchstack.com/database/wordpress/plugin/recencio-book-reviews/vulnerability/wordpress-recencio-book-reviews-plugin-1-66-0-cross-site-scripting-xss-vulnerability)** — Authenticated (Contributor+) stored XSS. Patched in 1.67.0.
- **57 additional vulnerabilities** identified and fixed across 5 independent audit rounds covering ~90 PHP files.

The audit methodology:

1. **Audit 1** — Initial full-codebase audit. Found and patched 49 issues (nonce verification, `$wpdb->prepare()`, input sanitisation, output escaping). Post-patch verification confirmed all 49 resolved.
2. **Audit 2** — Second independent pass. Found and patched 4 additional issues. Post-patch verified.
3. **Audit 3** — Third independent pass. Found and patched 4 additional issues. Post-patch verified.
4. **Audit 4** — Fourth independent pass. Found and patched 1 additional stored XSS in purchase link style attributes. Post-patch verified.
5. **Audit 5** — Clean-room final review (no access to prior audit context). Zero findings at confidence threshold >= 0.8.

All findings are documented in the [`audit/`](audit/) directory.

Validated by [Patchstack](https://patchstack.com/database/wordpress/plugin/recencio-book-reviews).

All fixes preserve backwards compatibility. Existing installations can upgrade from 1.66.0 without data loss.

## Contributing

Report bugs or suggest features via [GitHub issues](https://github.com/tompos2/rcno-reviews/issues).

Pull requests are welcome. The codebase follows WordPress PHP coding standards.

## License

GPL-2.0-or-later. See [LICENSE.txt](LICENSE.txt).

Copyright 2014-2024 Kemory Grubb
Copyright 2026 Tom Stayte

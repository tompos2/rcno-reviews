> **Note:** The original developer of this plugin, Kemory Grubb, passed away in early 2024. This fork exists solely to patch [CVE-2024-33648](https://www.cve.org/CVERecord?id=CVE-2024-33648) and 57 additional security vulnerabilities discovered through five iterative security audits using Claude Opus 4.6 via Claude Code. It is intended as a drop-in replacement for existing Recencio installations. All original functionality, data structures, and settings are preserved. See the `audit/` directory for the full security audit trail.
>
> The original repository is at [gitlab.com/w33zy/rcno-reviews](gitlab.com/w33zy/rcno-reviews).

*Everything below was written by Kemory Grubb.*

---

# Recencio Book Reviews

**A powerful and flexible tool for managing your blog's book review collection.**

| Detail | Value |
|---|---|
| **Contributors** | w33zy |
| **Donate** | [paypal.me/wzymedia](https://paypal.me/wzymedia) |
| **Tags** | book reviews, reviews, book library, book ratings |
| **Requires WordPress** | 3.0.1+ |
| **Requires PHP** | 5.6.25+ |
| **Tested up to** | 6.3 |
| **Stable tag** | 1.66.0 |
| **License** | [GPLv2 or later](http://www.gnu.org/licenses/gpl-2.0.html) |

---

## Description

Recencio Book Reviews was designed with the book reviewer in mind and built to be fast, easy and customizable. The plugin adds a custom post type for book reviews to your site — you can publish book reviews as standalone posts or include them in your normal posts and pages.

Organize your book reviews however you like. The plugin comes with predefined taxonomies such as book author, genre, series, and publisher, and creating new taxonomies is effortless. Use listings embedded in pages to make your book reviews accessible by title or taxonomy, or use custom widgets to create tag clouds and top ten lists.

The plugin also provides book reviewers the opportunity to earn residual income via their own book purchase affiliate links.

Despite its simplicity, it gives book reviewers, administrators, designers, and developers all the freedom to adapt the plugin to their needs. Features you don't need can be deactivated and hidden from the UI. You can even create your own book review template files for complete control over look and feel.

Most importantly, all of the above is **SEO friendly** via validated JSON-LD metadata for the `Book` and `Review` schema markup.

### 🔗 Demo

**[recencio.com](https://recencio.com/)**

---

## Features

### Core
- Custom post type for book reviews
- 100% WordPress theme compatibility
- Automatically fetch book details via ISBN, or enter manually
- Custom backend for fast review entry
- Easily convert existing posts to reviews

### Ratings & Scoring
- 5-star rating system or criteria-based scoring
- Site visitor rating via the built-in comment form (can be disabled)

### Taxonomies & Organization
- Built-in taxonomies: Author, Genre, Series, Publisher
- Create any custom taxonomy you like
- Use default post categories and tags on book reviews (or disable them)
- Access reviews by alphabetical indices of title or taxonomy entries

### Display & Templates
- 4 built-in templates, or create your own custom layout
- 5 custom widgets (book cover slider, book cover grid, and more)
- Include book reviews in normal posts or pages using shortcodes
- Book reviews in your site's RSS feed

### SEO & Standards
- Schema.org `Review` and `Book` metadata as JSON-LD
- GDPR compliant
- RTL support
- Translation ready

### Developer Features
- Basic WP REST API support
- Extendable 3rd-party extension/module system
- Book purchase links with affiliate code support

---

## Languages

Fully available in:
- 🇬🇧 English
- 🇩🇪 German

Partially available in:
- 🇫🇷 French
- 🇪🇸 Spanish
- 🇭🇺 Hungarian

Help [translate the plugin](https://translate.wordpress.org/projects/wp-plugins/recencio-book-reviews/) — it's one of the best ways to contribute!

---

## Installation

1. Upload the `rcno-reviews` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress
3. Add new book reviews via the new **Reviews** option in your admin menu

---

## Frequently Asked Questions

### I'm getting a 404 page when I visit a book review

Try flushing your permalink settings: go to **Settings → Permalinks** and save without changing anything. Also, make sure you're using the *singular* form of your custom slug (e.g., "book" instead of "books").

### How can I get support?

Please open a new topic on the plugin's [WordPress.org support page](https://wordpress.org/support/plugin/recencio-book-reviews/).

---

## Screenshots

| # | Description |
|---|---|
| 1 | Rich snippet markup on Google search results |
| 2 | 4 built-in book review templates |
| 3 | Customizable list of purchase stores |
| 4 | Book review admin page |
| 5 | Custom WordPress widgets |
| 6 | Book authors index page |
| 7 | Book reviews index page |
| 8 | Book review post — default template |
| 9 | Criteria-based review score box |

---

## Sponsors

Thanks to JetBrains for providing [PhpStorm](https://www.jetbrains.com/?from=recencio-book-reviews) for developing this plugin.

---

## Changelog

### 1.66.0
Fixed an issue with the "Currently Reading" widget not allowing the user to select a book cover.

### 1.65.0
- Fixed JS error on non-Recencio WP admin pages
- Display notice if `[rcno-book-list]` is used outside of a book review
- New shortcode: `[rcno-book id=3723 detail="Book Description/Synopsis" wrapper="p" label="false"]`
- Remove unnecessary settings check on plugin activation
- Code cleanup

### 1.64.0
- Added "cover size" option to the "Recent Reviews" widget
- Refactored `[rcno-table]` shortcode for multiple taxonomy terms
- Refactored book cover to support custom URLs in all templates
- Code cleanup for PHP 8 compatibility

### 1.63.0
- Fixed Selectize library initialization bug
- Updated Selectize to v0.13.5
- Reorganized JS and CSS libraries on admin pages

### 1.62.0
- WordPress native responsive images for book covers
- Fixed book cover image display bugs
- Added filter to override the "no cover" image
- WordPress 6.0 compatibility
- Assorted bug fixes and refactoring

### 1.61.0
Fixed a bug with how book covers are resolved.

### 1.60.1
Minor bug fix.

### 1.60.0
Fixed a critical bug related to the Gutenberg post editor.

### 1.50.1
Fixed a JS bug with TinyMCE and Google Books.

### 1.50.0
Added option to use custom review taxonomies with regular posts.

### 1.49.0
- First iteration of the `[rcno-table]` shortcode
- Fixed custom template thumbnail not loading from child-theme
- Code cleanup

### 1.48.1
- Fixed star ratings not showing on fetched reviews in the Isotope grid shortcode
- Isotope grid shortcode now filters using the `AND` operator

### 1.48.0
- Updated CSSTidy library to v1.7.3 (resolves PHP warnings)
- Reworked "Isotope" grid shortcode with AJAX loading
- Fixed book synopsis field unselectable in Gutenberg editor
- Fixed Gutenberg adding extra `<p>` tags to review content

### 1.47.0
- Fixed unclosed divs in `[rcno-tax-list]` shortcode
- Display review ID on admin review list table
- Code cleanup

### 1.46.1
Fixed missing A11y markup.

### 1.46.0
- Fixed issues with the "Currently Reading" widget
- Fixed image filtering inside review content
- Added `rcno_taxonomy_strip_tags` filter

### 1.45.0
- New template: "Rcno Inverse"
- Bug fixes and code cleanup

### 1.44.0
- Fixed non-Latin character regression from 1.42.0
- Fixed "Currently Reading" widget bug
- Added anchor tag to book covers in "Recent Reviews" widget
- New shortcode parameters
- Refactored book information styling

### 1.43.0
Fixed longstanding issues with `the_content` filter usage.

### 1.42.2
- Fixed book cover display in "Currently Reading" admin widget
- Fixed YouTube URL processing in review content
- Cyrillic language adjustments

### 1.42.1
- Fixed warning related to registering `author` as REST base
- Fixed "Recent Reviews" widget bug

### 1.42.0
- ⚠️ **Action required:** After installing, visit the "Taxonomy" tab on the Recencio settings page and click "Save settings."
- Rewrote settings page for plural/singular label support (improved i18n)
- Automatic permalink flush on settings changes affecting public URLs
- New options for "Recent Reviews" widget
- Fixed curly quote conversion (see `wptexturize()`)
- Fixed PHP deprecation warning for `contextual_help` hook

### 1.41.0
- Refactored translation feature for better WordPress i18n compliance
- Fixed long-running translation bug on settings page
- Thanks to @chrillebln for the DE translation

### 1.40.0
- Purchase links now show on reviews embedded in pages
- Recencio errors only shown on relevant pages and dashboard
- Fixed missing jQuery reference on extension page

### 1.39.0
- New "Convert Posts to Reviews" extension
- Updated menu label for "All Reviews"
- Changed "Activate/Deactivate" to "Enable/Disable" on extensions page

### 1.38.0
- Removed "Currently Reading" widget for non-admin users
- Added `thumbnailUrl` property to JSON-LD output

### 1.37.0
Finally fixed the issue with incorrect rewrite rule flushing on plugin activation.

### 1.36.0
- Prevent displaying a review shortcode inside a review post type
- Fixed half-star review display

### 1.35.0
- Added filter to rename book metadata items via code
- New `[rcno-book-listing ids='']` shortcode

### 1.34.x
- New "Review Score Box" layout
- New `count` parameter for `[rcno-tax-list]`
- Jetpack "Publicize" support
- Various bug fixes

### 1.33.0
- Fixed wrong book cover size from 3rd-party API
- Fixed PHP warnings on site migration
- Added review template name to WP class for debugging
- New "Metamor" template (WIP)

### 1.32.0
- Fixed `�` character in truncated multi-byte strings
- New `width`, `height`, and `rating` parameters for grid shortcodes

### 1.31.0
- Fixed PHP warning in "Taxonomy List" widget
- Added Hungarian translation (thanks @gaborh)

### 1.30.x
- Fixed blank book cover file names
- Made review index more descriptive with error messages

### 1.29.0
Fixed JavaScript error when TinyMCE is disabled.

### 1.28.0
New extension: fetch and add book covers to reviews.

### 1.27.0
- Added option for fixed review criteria
- Rewrote book data fetching

### 1.26.x
- Fixed empty string instead of auto-excerpt for embedded reviews
- Fixed RSS feed excerpt length affecting non-reviews
- Fixed error with unrecognized custom taxonomy terms
- Fixed JS bug in "Currently Reading" widget

### 1.25.0
Fixed shortcodes not rendering correctly on pages.

### 1.24.x
- Added filter to force a specific book cover size
- Fixed fatal PHP error with `empty()` evaluation

### 1.23.x
- Rewrote "Currently Reading" widget using VueJS (supports multiple progress updates)
- "Recent Reviews" widget now strips shortcodes from descriptions
- Fixed `csstidy_print` class declaration conflict

### 1.22.0
Added reactive search bar to the sortable grid feature.

### 1.21.x
- New social sharing extension
- Added WP Codemirror for settings page CSS editor
- Fixed "Add Book Cover" button and 3rd-party JS loading bugs

### 1.20.0
- Fixed Micromodal init call
- Fixed `posts_clauses` filter bug
- Filterable book grid now lists items alphabetically

### 1.19.0
Added category limiting for index page shortcode: `[rcno-reviews-index category="review"]`

### 1.18.x
- Fixed image overlap in Isotope grids
- Added taxonomy drop-down filters to admin columns
- Widget improvements
- Added span tags around frontend book meta
- Settings page UX improvements

### 1.17.0
- New "Custom User Metadata" extension
- Page states feature for shortcode identification
- Fixed calendar widget regression and RSS feed bugs
- Removed rating stars from comment RSS feeds

### 1.16.0
- GDPR support and compliance
- Trimmed API keys to prevent whitespace issues
- Isotope grid uses taxonomy labels for i18n

### 1.15.x
- Custom links on book covers in single reviews
- Fixed comment form showing on shortcode pages
- Style fixes

### 1.14.0
- New "Extensions" feature for 3rd-party development
- New "Author Box" extension
- Social media profile fields on WordPress user profiles

### 1.12.x
- Sortable grid via Isotope JS library
- Book cover attachment ID stored in review meta for performance
- New Upgrader class for post-update functions
- RGBA color picker on settings page
- JSON-LD schema markup toggle
- Various fixes

### 1.11.x
- Selectable and reorderable book metadata display
- Improved review excerpt handling
- Fixed calendar widget and remote API fetch bugs

### 1.10.x
- Custom taxonomy auto-generation adjustments
- Translation file naming fixes
- Article sorting options for foreign languages
- Fatal PHP error prevention for duplicate shortcodes

### 1.9.x
- Import/export plugin settings
- Custom taxonomy labels
- Half-star rating support
- Disable automatic pluralization option
- Calendar and `the_content` filter fixes

### 1.8.x
- New shortcodes: `[rcno-book-list]`, `[rcno-review-box]`, `[rcno-purchase-links]`
- "After review" action hook
- Fixed best/worst rating calculation

### 1.7.x
- "Books in this Series" feature
- Redesigned taxonomy index pages with book covers
- Refactored URLs with post type slug
- Fixed CPT slug pluralization in foreign languages

### 1.6.x
- Masonry book review grid (MacyJS)
- Redesigned "Currently Reading" progress bar
- Fixed homepage content removal bug
- Refactored custom template functions

### 1.5.x
- New review calendar widget
- Book Grid widget defaults and Recent Reviews sorting fix

### 1.4.0
- Book covers on admin review column
- Fixed custom taxonomy removal from admin column

### 1.3.0
Added option to ignore articles when sorting titles.

### 1.2.11
- New "Currently Reading" widget
- Setting for 'uncountable' words in native languages

### 1.1.10
Added option to display book covers on the book reviews index page.

### 1.0.x
- Initial plugin release
- Various bug fixes for comments, scripts, rewrite rules, taxonomy slugs, REST API, templates, and star rating defaults

---

*Original plugin by Kemory Grubb (w33zy). Rest in peace.*

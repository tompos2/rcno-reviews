=== Recencio Book Reviews ===
Contributors: w33zy
Tags: book reviews, reviews, book library, book ratings
Requires at least: 3.0.1
Requires PHP: 5.6.25
Tested up to: 6.3
Stable tag: 1.70.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Recencio Book Reviews is a powerful and very flexible tool for managing your blog's book review collection. Designed with the book reviewer in mind — fast, easy, and customizable.

== Description ==

Recencio Book Reviews lets you publish and organize book reviews on your WordPress site. Add reviews as their own post type, or embed them inside regular posts and pages. The plugin comes with built-in categories for book author, genre, series, and publisher, and you can create as many of your own as you like.

This plugin was originally created by Kemory Grubb. Kemory passed away in 2024. Tom Stayte has since adopted the codebase and is working to continue its development.

= What's new since 1.66.0 =

Version 1.67.0 and above include a major security update. A known vulnerability (CVE-2024-33648) has been patched, along with 57 additional issues found during a thorough security review. All fixes are backwards compatible — your existing reviews, settings, and categories will carry over without any data loss.

= Features =

* Custom post type for book reviews
* Works with any WordPress theme
* Fetch book details automatically via ISBN, or enter them by hand
* Quick-entry backend for adding reviews fast
* Convert existing posts to reviews
* 5-star rating or criteria-based scoring
* Built-in categories: Author, Genre, Series, Publisher — plus your own custom ones
* Alphabetical index pages for browsing reviews and categories
* Embed reviews in posts or pages with shortcodes
* SEO-friendly JSON-LD markup for Book and Review schemas
* 4 built-in display templates, or create your own
* 5 widgets: book cover slider, cover grid, recent reviews, and more
* Let visitors rate books via the comment form
* Book purchase links with affiliate codes
* REST API support
* Extension system for adding new features
* RSS feed integration
* Translation ready, GDPR compliant, RTL support

== Installation ==

1. Upload the `rcno-reviews` folder to `/wp-content/plugins/`
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add book reviews via the new 'Reviews' menu in your admin sidebar

== Frequently Asked Questions ==

= I'm upgrading from version 1.66.0 or earlier. Will I lose my data? =

No. All updates since 1.66.0 are security fixes that preserve backwards compatibility. Your reviews, settings, and categories will not be affected.

= I am getting a 404 page when I visit a book review =

Try flushing your permalink settings. Go to Settings > Permalinks and click "Save Changes" without changing anything. Also make sure your custom slug uses the singular form (e.g. "book" not "books").

= How can I get support? =

Please open a topic on the plugin's WordPress.org support page.

== Screenshots ==

1. Rich snippet markup on Google search results
2. Choose from 4 built-in review templates, or create your own
3. Add or remove book purchase stores
4. Book review editing screen
5. Custom widgets
6. Book authors index page
7. Book reviews index page
8. A book review using the default template
9. Criteria-based review score box

== Changelog ==

= 1.70.0 =
* Fixed stored XSS in purchase link style attributes
* Fifth and final clean-room security audit confirmed no remaining issues

= 1.69.0 =
* Fixed 4 security issues from third independent audit

= 1.68.0 =
* Fixed 4 security issues from second independent audit

= 1.67.0 =
* Patched CVE-2024-33648 and 47 additional security issues across 38 files
* Comprehensive review of nonce verification, database queries, input handling, and output escaping

= 1.66.0 =
* Fixed an issue with the "Currently Reading" widget not allowing the user to select a book cover

= 1.65.0 =
* Fixed JS error on non-Recencio WP admin pages
* Display notice if `[rcno-book-list]` is used outside of a book review
* Add a new shortcode that displays specific details of a reviewed book
* Remove unnecessary settings check on plugin activation

= 1.64.0 =
* Add a "cover size" option to the "recent reviews" widget
* Refactored the [rcno-table] shortcode to support multiple taxonomy terms
* Refactored the book cover to support custom URLs in all templates
* PHP 8 compatibility

= 1.63.0 =
* Fixed a bug with the Selectize library not being initialized
* Updated the selectize library to v0.13.5

= 1.62.0 =
* Native responsive images for book covers
* Fixed bugs with book cover display
* Added a filter to override the "no cover" image
* WordPress 6.0 compatibility

= 1.61.0 =
* Fixed a bug with how book covers are resolved

= 1.60.1 =
* Minor bug fix

= 1.60.0 =
* Fix a critical bug related to the Gutenberg post editor

= 1.50.1 =
* Fix a JS bug with TinyMCE and Google Books

= 1.50.0 =
* Added an option to use custom review taxonomies with regular posts

= 1.49.0 =
* First iteration of the `[rcno-table]` shortcode feature
* Fixed an issue where custom template thumbnail is not loaded from child-theme

= 1.48.1 =
* Fixed a bug with star ratings not showing on fetched reviews in the Isotope grid shortcode

= 1.48.0 =
* Updated the CSSTidy library to v1.7.3 to resolve PHP warnings
* Re-worked the "isotope" grid shortcode to support AJAX loading
* Fixed Gutenberg compatibility issues

= 1.47.0 =
* Fixed a bug with unclosed divs in the `[rcno-tax-list]` shortcode
* Display the review ID on the WP admin review list table

= 1.46.1 =
* Fixed missing accessibility markup

= 1.46.0 =
* Fixed issues with the "Currently Reading" widget
* Fixed issues with the filtering of images inside the review content
* Added a filter to strip tags from custom taxonomies

= 1.45.0 =
* Added a new template for reviews: "Rcno Inverse"

= 1.44.0 =
* Fixed a regression with non-Latin characters introduced in 1.42.0
* Fixed bug in the "Currently Reading" widget
* Added anchor tag to book covers in the "Recent Reviews" widget

= 1.43.0 =
* Fixed longstanding issues with the usage of the `the_content` filter

= 1.42.2 =
* Fixed an issue where book cover not displaying in the "Currently Reading" admin widget
* Fixed an issue with YouTube URLs not processed inside review content
* Better support for Cyrillic languages

= 1.42.1 =
* Fixed a warning message related to registering `author` as a REST base
* Fixed a bug in the "Recent Reviews" widget

= 1.42.0 =
* Rewrote the settings page to accept both plural and singular form of labels for internationalization
* Automatic permalink flush on settings changes that affect public URLs
* New options for the "Recent Reviews" widget
* Fixed curly quotes conversion issue
* Fixed PHP deprecation warning for the `contextual_help` hook

= 1.41.0 =
* Improved translation system to better comply with WordPress i18n standards
* Fixed translations not showing on settings page

= 1.40.0 =
* Purchase links now show on reviews embedded in pages
* Plugin errors now only shown on relevant admin pages

= 1.39.0 =
* New "Convert Posts to Reviews" extension
* Updated menu labels

= 1.38.0 =
* Removed "Currently Reading" widget for non-admin users
* Added thumbnailUrl to JSON-LD output

= 1.37.0 =
* Fixed rewrite rules not flushing on plugin activation

= 1.36.0 =
* Prevent displaying a review shortcode inside a review post type
* Fixed half-star review display

= 1.35.0 =
* Added filter to rename book metadata items via code
* New `[rcno-book-listing ids='']` shortcode

= 1.34.2 =
* Fixed extensions being sent as object instead of array

= 1.34.1 =
* Fixed missing class method accessor

= 1.34.0 =
* New layout for "Review Score Box"
* New `count` parameter for `[rcno-tax-list]` shortcode
* Jetpack Publicize support

= 1.33.0 =
* Fixed wrong book cover size from 3rd party API
* Fixed PHP warnings on site migration
* New "Metamor" book review template (WIP)

= 1.32.0 =
* Fixed truncated strings showing garbled characters in multi-byte languages
* New width/height parameters for sortable grid and reviews index shortcodes
* Rating sort for sortable grid shortcode

= 1.31.0 =
* Fixed PHP warning in "Taxonomy List" widget
* Added Hungarian translation

= 1.30.1 =
* Improved review index error messages

= 1.30.0 =
* Fixed blank book cover file names
* Fixed reverted book cover view file

= 1.29.0 =
* Fixed JavaScript error when TinyMCE is disabled

= 1.28.0 =
* New extension to auto-fetch book covers

= 1.27.0 =
* Fixed review criteria options
* Rewrote book data fetching

= 1.26.3 =
* Fixed embedded review without excerpt returning empty string

= 1.26.2 =
* Fixed excerpt length only affecting reviews in RSS feed
* Fixed error with unrecognized custom taxonomy terms

= 1.26.1 =
* Fixed JS bug in "Currently Reading" widget

= 1.26.0 =
* Disabled Gutenberg editor support

= 1.25.0 =
* Fixed shortcodes not rendering correctly on pages

= 1.24.1 =
* Fixed fatal PHP error with `empty()` function evaluation

= 1.24.0 =
* Added filter to force a specific book cover size

= 1.23.2 =
* Fixed class redeclaration bug

= 1.23.0 =
* Rewrote "Currently Reading" widget using VueJS with multiple progress updates

= 1.22.0 =
* Added reactive search bar to "sortable grid" feature

= 1.21.0 =
* New social sharing extension
* Added WP Codemirror for CSS editor

= 1.20.0 =
* Fixed Micromodal initialization
* Fixed `posts_clauses` filter bug
* Alphabetical ordering in filterable book grid

= 1.19.0 =
* Category filter for reviews index shortcode

= 1.18.0 =
* Taxonomy filters on admin review list
* Currently Reading widget improvements
* UX improvements on settings page

= 1.17.0 =
* New "Custom User Metadata" extension
* Page states for Recencio shortcodes
* RSS feed and calendar widget fixes

= 1.16.0 =
* GDPR support and compliance
* Isotope grid i18n fix

= 1.15.0 =
* Custom links on book covers
* Comment form display fix

= 1.14.0 =
* New "Extensions" system for 3rd party features
* New "Author Box" extension

= 1.12.0 =
* Sortable grid via Isotope JS
* RGBA color picker on settings page
* JSON-LD schema improvements

= 1.11.0 =
* Reorderable book metadata display
* Improved review excerpts

= 1.10.0 =
* Custom taxonomy generation improvements
* Translation file formatting fix
* Article sorting for foreign languages

= 1.9.0 =
* Settings import/export
* Custom taxonomy labels
* Half-star ratings

= 1.8.0 =
* New shortcodes: [rcno-book-list], [rcno-review-box], [rcno-purchase-links]

= 1.7.0 =
* "Books in this Series" feature
* Redesigned taxonomy index pages

= 1.6.0 =
* Book review grid index (MasonryJS layout)

= 1.5.0 =
* Review calendar widget

= 1.4.0 =
* Book covers on admin review list

= 1.3.0 =
* Article-aware title sorting

= 1.2.11 =
* "Currently reading" widget

= 1.0.0 =
* Initial plugin release

== Upgrade Notice ==

= 1.70.0 =
Security release. Patches CVE-2024-33648 and 57 additional vulnerabilities found during a 5-round security audit. All fixes are backwards compatible — your reviews and settings will not be affected. Validated by Patchstack.

= 1.42.0 =
After installing this update, visit the "Taxonomy" tab on the Recencio settings page and click "Save settings".

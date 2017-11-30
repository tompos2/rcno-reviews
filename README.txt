=== Recencio Book Reviews ===
Contributors: Kemory Grubb
Donate link: https://wzymedia.com
Tags: book reviews, reviews, book library, book ratings, 5 star rating, book critique
Requires at least: 3.0.1
Tested up to: 4.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Recencio Book Reviews is a powerful and very flexible tool to manage your blog’s book review collection. It was designed with the book reviewer in mind and created for easy
and fast usage.

== Description ==

Recencio Book Reviews is a powerful and very flexible tool to manage your blog’s book review collection. It was designed with the book reviewer in mind and created for easy
and fast usage. The plugin even provides book reviewers the opportunity of earning residual income via adding their own book purchase affiliate links.
This plugin basically adds a custom post type for book reviews to your site. You can publish book reviews as standalone posts or include them in your normal posts and pages.
Organize your book reviews in any way you like. The plugin comes with some basic predefined taxonomies such as book author, genre, series, and publisher. As creating new book taxonomies is easy it’s up to
you which and how many taxonomies you want. Use listings embedded in pages to make your book reviews accessible by title or taxonomy name. Or use one of
the custom widgets to create tag clouds or top ten lists.
Of course you can use all the WordPress goodies you know from posts on book reviews as well: images, videos and comments.
Despite it’s simplicity, it’s giving book reviewer, administrators, designers and developers all the freedom to adapt the plugin to their needs. Features you don’t
need can be deactivated and are hidden from the UI. You can even create your own book review template files to gain complete control the look and feel of book reviews.

And, all of the above is SEO friendly by way of validated JSON+LD metadata for the 'Book' and 'Review' schema markup.

= Features =

* custom post type for book reviews
* 100% WordPress theme compatibility
* automatically fetch book details via its ISBN, or enter manually
* custom backend to enter book reviews fast
* your choice of a 5 star rating or a criteria-based scoring system
* builtin book taxonomies such as, Author, Genre, Series and Publisher come predefined, but you can create whatever taxonomy you like.
* use default post categories and tags on book reviews as well, or disable them if you want to
* access reviews by alphabetical indices of review title or taxonomy entries such as book author or book publisher
* include book reviews in your normal posts or pages using shortcodes
* search engine friendly book review output using schema.org‘s review and book metadata as JSON+LD
* choose between 4 different templates to determine how your book review should look or create your own custom layout
* 5 custom widgets such as a book cover slider and book cover grid
* site visitor rating system via the builtin comment form, or disable it if you want to
* basic support of the new WP REST API system
* have you book reviews show up in your site's RSS feed
* create book purchase links using your own affiliate code
* have your site visitors/reader leave a 5 rating via the comment form
* translation ready

== Installation ==

1. Upload `rcno-reviews` folder to your website's `/wp-content/plugins/` folder
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add new book reviews via the new 'Reviews' option on your site's admin menu

== Frequently Asked Questions ==

= I am getting a 404 page when I visit a book review =

Try flushing your permalink settings. Visit Settings -> Permalinks and just save without changing anything.

= How can I get support for this plugin =

Please open a new topic on the plugin's WordPress.org support page

== Screenshots ==

1. Choose from a choice of 4 builtin book review templates, or create your own inside your current theme
2. Enable or disable the user comment rating feature. Also an option to disable each custom widget
3. Remove or add your own list of stores to purchase books from
4. New book review admin page
5. Custom WordPress widgets
6. Book authors index page
7. Book reviews index page
8. Book review post using the default template
9. Book review post showing the criteria-based review score box

== Changelog ==

= 1.0.0 =
* Initial plugin release.

= 1.0.1 =
* Fixed an issue with white color stars on a white background on initial install.

= 1.0.2 =
* Fixed an issue where templates stop working before plugin folder name got changed.

= 1.0.3 =
* Generate a book author's URL if one is not provided by site owner
* Cleaned up the 'Rcno_Reviews_Rest_API' class

= 1.0.5 =
* fixed an error when fetching a taxonomy with an error, updated to v1.0.5

= 1.0.6 =
* Moved flush rewrite rule function to better handle plugin activation.
* Added flush rewrite rule function to run on plugin deactivation.
* Better handle taxonomy slugs.
* Better handle multiple user added taxonomies.
* Adjusted the styling of the recent book review widget

= 1.0.7 =
* Fixed issue with comments not showing when comment ratings is disabled.

= 1.0.8 =
* Moved all admin scripts to footer and added version numbers

= 1.0.9 =
* Fixed an error in the review shortcodes file cause by the differences between Windows and Unix use of '/'

= 1.0.10 =
* Fixed regression from last patch

= 1.1.10 =
* Added an option to display book covers on the book reviews index page

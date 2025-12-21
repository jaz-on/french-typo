=== French Typo ===
Contributors: jaz_on, audrasjb, juliobox
Tags: typography, french, typographie, francais, text-formatting
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://ko-fi.com/jasonrouet

Apply French typography rules to your WordPress content automatically.

== Description ==

French Typo automatically applies French typography rules to your content. The plugin adds non-breaking spaces before punctuation marks (`;`, `:`, `!`, `?`, `%`, `«`, `»`) and replaces `(c)` with `©` and `(r)` with `®`. You can choose between regular or thin non-breaking spaces.

Rules apply to all your content: posts, pages, excerpts, taxonomies, archives, comments, widgets, menus, RSS feeds, REST API, custom fields, breadcrumbs, and SEO metadata. Each area can be enabled or disabled individually in settings.

= Features =

* Non-breaking spaces before punctuation marks
* Special character replacements (`(c)` → `©`, `(r)` → `®`)
* Configurable: regular or thin non-breaking spaces
* Comprehensive coverage: all WordPress content areas
* Granular control: enable or disable each area individually
* SEO integration: Yoast SEO, Rank Math, SEOPress
* Custom fields support: ACF and Meta Box
* Respects HTML and shortcodes

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/french-typo`, or install through the WordPress plugins screen.
2. Activate the plugin.
3. Configure options in `Settings > French Typo`.

== Frequently Asked Questions ==

= Does this plugin modify existing content? =

No. Typography rules are applied on-the-fly when content is displayed, without modifying the original content in the database.

= What's the difference between regular and thin non-breaking spaces? =

Regular spaces (`&nbsp;`) are standard and prevent line breaks. Thin spaces (`&#8239;`) are narrower and may not display correctly depending on the font or browser.

= Can I disable certain features? =

Yes. You can disable non-breaking spaces or character replacements, and choose precisely which content areas should be processed.

== Screenshots ==

1. Plugin settings page

== Changelog ==

= 1.1.0 =
* Performance: Optimized filter processing with new generic wrapper function
* Performance: Reduced function calls and improved hook handling efficiency
* Performance: Enhanced static cache implementation for better memory usage
* Code Quality: Consolidated multiple wrapper functions into single optimized function
* Code Quality: Improved code organization and maintainability
* Compatibility: Tested up to WordPress 6.9
* Documentation: Updated technical architecture documentation

= 1.0.0 =
* Initial release
* Complete rewrite from the original French Typo plugin
* Support for non-breaking spaces (regular and thin)
* Support for special character replacements
* Configurable settings page with granular options
* Full Custom Post Types support
* Custom Fields support (ACF, Meta Box)
* Taxonomies support (categories, tags, custom taxonomies)
* Archives support (all types)
* Excerpts support
* Comments support (text and author names)
* Widgets and menus support
* RSS feeds support (titles, content, excerpts, comments)
* REST API support (posts, pages, attachments)
* User profiles support
* Breadcrumbs support (Yoast SEO, Rank Math, SEOPress)
* SEO meta descriptions support (Yoast SEO, Rank Math, SEOPress)
* Social tags support (Open Graph, Twitter Cards)
* Developer API: filter hook `french_typo_process_text` for custom content processing
* Performance optimizations: static cache for plugin options to reduce database queries
* Code quality: optimized regex patterns and early returns for better performance
* Admin interface: modern CSS with custom properties and simplified interactions
* Accessibility: improved color contrast (WCAG 2.1 AA compliance)
* Security: proper data sanitization and validation throughout
* WordPress Coding Standards: full compliance with WordPress-Extra standards

== Author & Credits ==

**Jason Rouet**
* Website: [jasonrouet.com](https://jasonrouet.com)
* Email: [bonjour@jasonrouet.com](mailto:bonjour@jasonrouet.com)
* WordPress.org: [profiles.wordpress.org/jaz_on/](https://profiles.wordpress.org/jaz_on/)

You can support this project on [Ko-fi](https://ko-fi.com/jasonrouet) or [GitHub Sponsors](https://github.com/sponsors/jaz-on).

This plugin is a fork of **French Typo** created by Gilles Marchand (master_shiva), completely rebuilt since March 2024 with the help of [Jean-Baptiste Audras](https://profiles.wordpress.org/audrasjb/). Inspired by [TypoFR](https://wordpress.org/plugins/typofr/), [Orthotypo](https://wordpress.org/plugins/orthotypo-orthotypographie-automatique/), and [Consistency](https://wordpress.org/plugins/consistency/).

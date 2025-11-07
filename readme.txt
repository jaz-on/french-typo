=== French Typo ===
Contributors: jaz_on, audrasjb
Tags: typography, french, typographie, francais, text-formatting
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 8.3
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://ko-fi.com/jasonrouet

Apply French typography rules to your WordPress content automatically.

== Description ==

French Typo is a WordPress plugin that automatically applies French typography rules to your content. It handles non-breaking spaces (espaces insécables) and special character replacements according to French typographic standards.

= Features =

* **Non-breaking spaces**: Automatically adds non-breaking spaces or thin non-breaking spaces before punctuation marks (`;`, `:`, `!`, `?`, `%`, `»`) and after opening guillemets (`«`)
* **Special characters**: Replaces `(c)` with `©` and `(r)` with `®`
* **Configurable**: Choose between regular non-breaking spaces (`&nbsp;` / `&#160;`) or thin non-breaking spaces (`&#8239;`)
* **Respects HTML**: The plugin carefully processes content to avoid breaking HTML tags and shortcodes
* **Comprehensive coverage**: Processes posts, pages, custom post types, taxonomies, archives, comments, widgets, menus, RSS feeds, REST API, user profiles, breadcrumbs, and SEO meta tags
* **Granular control**: Enable or disable processing for each content area individually
* **SEO integration**: Automatic support for Yoast SEO, Rank Math, and SEOPress (meta descriptions, Open Graph, Twitter Cards)
* **Custom fields support**: Works with ACF (Advanced Custom Fields) and Meta Box

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/french-typo` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Configure the plugin settings in `Settings > Réglages typographiques` (`Settings > French Typo`).

== Frequently Asked Questions ==

= What does this plugin do? =

This plugin automatically applies French typography rules to your WordPress content, including non-breaking spaces before punctuation marks and special character replacements.

= Which punctuation marks are handled? =

The plugin handles: `;`, `:`, `!`, `?`, `%`, `«`, and `»`.

= What's the difference between regular and thin non-breaking spaces? =

Regular non-breaking spaces (`&nbsp;` / `&#160;`) are standard spaces that prevent line breaks. Thin non-breaking spaces (`&#8239;`) are narrower spaces that may not display correctly depending on the font, browser, and operating system.

= Does this plugin modify existing content? =

No, the plugin applies typography rules on-the-fly when content is displayed, without modifying the original content in the database.

= Can I disable certain features? =

Yes, you can disable non-breaking spaces or special character replacements independently in the plugin settings. You can also choose precisely which content areas should be processed (titles, content, widgets, menus, taxonomies, archives, comments, RSS, REST API, etc.).

= Which content areas are covered? =

The plugin automatically processes:
* Post and page titles and content (including Custom Post Types)
* Excerpts
* Widgets and menus
* Taxonomies (categories, tags, custom taxonomies)
* Archives
* Comments
* Custom fields (ACF, Meta Box)
* RSS feeds
* REST API responses
* User profiles
* Breadcrumbs (Yoast, Rank Math, SEOPress)
* SEO meta descriptions and social tags (Open Graph, Twitter Cards)

All these areas can be enabled or disabled individually from the settings page.

== Screenshots ==

1. Plugin settings page

= Author & Sponsorship =

**Jason Rouet**
* Website: [jasonrouet.com](https://jasonrouet.com)
* Email: [bonjour@jasonrouet.com](mailto:bonjour@jasonrouet.com)
* WordPress.org: [profiles.wordpress.org/jaz_on/](https://profiles.wordpress.org/jaz_on/)

You can sponsor me on [Ko-fi](https://ko-fi.com/jasonrouet) or [GitHub Sponsors](https://github.com/sponsors/jaz-on). Any help is welcome: sharing the project, feedback, reporting issues, etc.

= History and credits =

== Fork and contribution ==

This plugin is a fork of the **French Typo** extension created by **Gilles Marchand** (master_shiva). The code has been completely rebuilt since March 2024 with the help of [Jean-Baptiste Audras](https://profiles.wordpress.org/audrasjb/). To see the original code, check commit [25940a7d11b08e1f02791812cbdcf840d97a4086](https://github.com/jaz-on/french-typo/commit/25940a7d11b08e1f02791812cbdcf840d97a4086).

== Inspirations ==

This plugin is also inspired by other projects:

* [TypoFR](https://wordpress.org/plugins/typofr/) — WordPress plugin for French typography (archived)
* [Orthotypo](https://wordpress.org/plugins/orthotypo-orthotypographie-automatique/) — Automatic orthotypography (inactive)
* [Consistency](https://wordpress.org/plugins/consistency/) — Typography Corrector for Gutenberg (active and a most advanced plugin with modern Gutenberg options)

== Changelog ==

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
* Comments support
* Widgets and menus support
* RSS feeds support
* REST API support
* User profiles support
* Breadcrumbs support (Yoast SEO, Rank Math, SEOPress)
* SEO meta descriptions support (Yoast SEO, Rank Math, SEOPress)
* Social tags support (Open Graph, Twitter Cards)
* Performance optimizations: static cache for plugin options to reduce database queries
* Code quality: optimized regex patterns and early returns for better performance
* Admin interface: modern CSS with custom properties and simplified interactions
* Accessibility: improved color contrast (WCAG 2.1 AA compliance)
* Security: proper data sanitization and validation throughout
* WordPress Coding Standards: full compliance with WordPress-Extra standards

== Upgrade Notice ==

= 1.0.0 =
Initial release. This is a complete rewrite of the original French Typo plugin.

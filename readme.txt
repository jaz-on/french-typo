=== Typographie française ===
Contributors: jaz_on, audrasjb
Tags: typography, french, typographie, francais, space, insécable, orthographe
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 8.3
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Apply French typography rules to your WordPress content automatically.

== Description ==

French Typo is a WordPress plugin that automatically applies French typography rules to your content. It handles non-breaking spaces (espaces insécables) and special character replacements according to French typographic standards.

= Features =

* **Non-breaking spaces**: Automatically adds non-breaking spaces or thin non-breaking spaces before punctuation marks (`;`, `:`, `!`, `?`, `%`, `»`) and after opening guillemets (`«`)
* **Special characters**: Replaces `(c)` with `©` and `(r)` with `®`
* **Configurable**: Choose between regular non-breaking spaces (`&nbsp;` / `&#160;`) or thin non-breaking spaces (`&#8239;`)
* **Respects HTML**: The plugin carefully processes content to avoid breaking HTML tags and shortcodes

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

Yes, you can disable non-breaking spaces or special character replacements independently in the plugin settings.

== Screenshots ==

1. Plugin settings page

= Author & Sponsorship =

**Jason Rouet**
* Website: [jasonrouet.com](https://jasonrouet.com)
* Email: [bonjour@jasonrouet.com](mailto:bonjour@jasonrouet.com)
* WordPress.org: [profiles.wordpress.org/jaz_on/](https://profiles.wordpress.org/jaz_on/)

You can sponsor me on [Ko-fi](https://ko-fi.com/jasonrouet) or [GitHub Sponsors](https://github.com/sponsors/jaz-on). Any help is welcome: sharing the project, feedback, reporting issues, etc.

= Credits =

This plugin is a complete rewrite (since March 2024) of the original French Typo plugin by Gilles Marchand (master_shiva). The code has been completely rebuilt since March 2024 with the help of [Jean-Baptiste Audras](https://profiles.wordpress.org/audrasjb/). The original code can be found in commit 25940a7d11b08e1f02791812cbdcf840d97a4086.

This plugin is also inspired by other active or archived/inactive plugins:
* [TypoFR](https://wordpress.org/plugins/typofr/)
* [Orthotypo](https://wordpress.org/plugins/orthotypo-orthotypographie-automatique/)
* [Consistency](https://wordpress.org/plugins/consistency/) - Typography Corrector for Gutenberg

== Changelog ==

= 1.0.0 =
* Initial release
* Complete rewrite from the original French Typo plugin
* Support for non-breaking spaces (regular and thin)
* Support for special character replacements
* Configurable settings page

== Upgrade Notice ==

= 1.0.0 =
Initial release. This is a complete rewrite of the original French Typo plugin.

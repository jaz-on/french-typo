=== French Typo ===
Contributors: jaz_on, audrasjb, juliobox, beryldlg
Tags: typography, french, typographie, francais, text-formatting
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.2.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://buymeacoffee.com/jasonrouet

Apply French typography rules to your WordPress content automatically.

== Description ==

French Typo applies French typography rules to your content **as it is displayed**. Your text stays exactly as you wrote it in the editor — only the rendered output is enriched.

**What it does:**

* Adds non-breaking spaces before `: ; ! ? %` and around `« »`
* Replaces `(c)` with `©`, `(r)` with `®`, `(tm)` / `(TM)` with `™`
* Optionally normalizes French ordinals: `1ère` → `1re`, `3ème` → `3e`, `n-ième` → `nième`
* Works across posts, pages, widgets, menus, comments, RSS, REST, ACF / Meta Box fields, and SEO output (Yoast, Rank Math, SEOPress)
* On multilingual sites, can apply rules to French content only (Polylang and WPML auto-detected)

**What it does not do:**

* Touch your raw HTML, code blocks, scripts, styles, or `<textarea>` content
* Modify what's stored in the database

= Settings highlights =

* Regular (`&nbsp;`) or thin (`&#8239;`) non-breaking spaces
* Enable / disable each content area (title, content, excerpt, widgets, RSS, REST, etc.)
* Language restriction: off (default), auto French, or pick specific locales

== Installation ==

1. Install through the WordPress plugins screen, or upload to `/wp-content/plugins/french-typo`.
2. Activate the plugin.
3. Configure in **Settings > French Typo**.

== Frequently Asked Questions ==

= Does this plugin modify my content? =

No. The text saved in the database is never altered. French Typo intercepts the output just before display and adds the typography rules there. Deactivate the plugin and your content comes back unchanged.

= Regular or thin non-breaking spaces? =

Regular (`&nbsp;`) is universally supported. Thin (`&#8239;`) is typographically purer for `: ;` but may render too narrow or as a missing glyph on older fonts and browsers.

= Can I limit rules to French content only? =

Yes. In **Settings > French Typo > Language restriction**, choose **Auto** to apply only to `fr_*` locales, or **Custom** to pick specific locales. Polylang and WPML are detected per post; otherwise the site locale is used.

= Will it run inside code blocks, scripts, or `<textarea>`? =

No. Typography is skipped inside `<script>`, `<style>`, `<pre>`, `<code>` (nested), `<textarea>`, and embedded CSS (e.g. inline SVG). Gutenberg's Verse block stays typographic unless it is also a Code block.

= Does it change English ordinals (`1st`, `2nd`) or non-standard forms like `1ème`? =

No. Only the French forms listed in the description are converted.

= My theme or editor already inserts non-breaking spaces. Will French Typo duplicate them? =

No (since 1.2.2). All non-breaking space variants — `&nbsp;`, `&#160;`, `&#xA0;`, `&#8239;`, `&#x202F;`, and literal U+00A0 / U+202F — are detected and collapsed to a single canonical entity.

== Screenshots ==

1. Plugin settings page

== Changelog ==

Full history for all versions: [CHANGELOG.md](https://github.com/jaz-on/french-typo/blob/main/CHANGELOG.md) on GitHub.

= 1.2.3 =
* Fixed: HTML entities were broken in plain-text titles. The entity-protection pre-pass only ran when the text contained tags or shortcodes, so plain titles like `Foo &#038; Bar` (produced by core's `convert_chars` filter on `the_title`) had the trailing `;` of their entities treated as French punctuation — a narrow no-break space was inserted, turning `&#038;` into `&#038 ;` and downstream `esc_html()` re-encoded the orphan `&`, producing visible `&#038;#038 ;` double-encoding (e.g. in Yoast breadcrumbs). Protection is now applied whenever the text contains `&`, regardless of markup presence. ([#10](https://github.com/jaz-on/french-typo/issues/10))

= 1.2.2 =
* Added: Language restriction modes (Disabled / Auto French / Custom) with Polylang and WPML auto-detection, falling back to `get_locale()`. ([#5](https://github.com/jaz-on/french-typo/issues/5))
* Added: Reference French translation (`languages/french-typo-fr_FR.po`).
* Changed: Donate link migrated from Ko-fi to Buy Me a Coffee (`https://buymeacoffee.com/jasonrouet`).
* Changed: README — `fr.wordpress.org` upgraded to HTTPS.
* Fixed: Duplicate non-breaking spaces with **Elementor + Advanced Editor Tools** — detects every NBSP variant (named/numeric/hex entities, literal U+00A0 / U+202F) and collapses runs into a single canonical NBSP. Idempotent across layered filters (`widget_text` + `the_content`). Thanks to Guy Declercq for the report. ([#8](https://github.com/jaz-on/french-typo/issues/8))

== Author & Credits ==

**Jason Rouet**
* Website: [jasonrouet.com](https://jasonrouet.com)
* Email: [bonjour@jasonrouet.com](mailto:bonjour@jasonrouet.com)
* WordPress.org: [profiles.wordpress.org/jaz_on/](https://profiles.wordpress.org/jaz_on/)

You can support this project on [Buy Me a Coffee](https://buymeacoffee.com/jasonrouet) or [GitHub Sponsors](https://github.com/sponsors/jaz-on).

This plugin is a fork of **French Typo** created by Gilles Marchand (master_shiva), completely rebuilt since March 2024 with the help of [Jean-Baptiste Audras](https://profiles.wordpress.org/audrasjb/). Inspired by [TypoFR](https://wordpress.org/plugins/typofr/), [Orthotypo](https://wordpress.org/plugins/orthotypo-orthotypographie-automatique/), and [Consistency](https://wordpress.org/plugins/consistency/).

=== French Typo ===
Contributors: jaz_on, audrasjb, juliobox, beryldlg
Tags: typography, french, typographie, francais, text-formatting
Requires at least: 6.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.2.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://ko-fi.com/jasonrouet

Apply French typography rules to your WordPress content automatically.

== Description ==

French Typo automatically applies French typography rules to your content. Choose regular or thin non-breaking spaces in Settings > French Typo and save to add spaces before punctuation (`;`, `:`, `!`, `?`, `%`, `«`, `»`); until you do, punctuation spacing stays off. It replaces `(c)` with `©`, `(r)` with `®`, and `(tm)` / `(TM)` with `™`. It can normalize common French ordinal abbreviations (`1ère` → `1re`, `3ème` → `3e`, etc.) when that option is enabled (on by default until you save settings without it).

Rules apply to posts, pages, excerpts, taxonomies, archives, comments, widgets, menus, RSS feeds, REST API, custom fields, breadcrumbs, and SEO metadata. Most areas can be enabled or disabled in settings. SEO titles, meta descriptions, and Open Graph/Twitter strings from Yoast SEO, Rank Math, or SEOPress are not gated by the same toggles as post title and content; breadcrumbs use their own option.

= Features =

* Non-breaking spaces before punctuation marks
* Optional French ordinal abbreviations (`1ère` → `1re`, `3ème` → `3e`, hyphenated “n-ième” → `nième`, etc.), with the same raw HTML boundaries as other rules
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

Yes. You can disable non-breaking spaces or character replacements, and choose which content areas to process (SEO plugin title/meta/social strings are separate from those checkboxes; see description).

= Does typography run inside code, scripts, or textareas? =

No. Narrow spaces, (c)/(r)/(tm) replacements, and optional ordinal abbreviations are skipped inside script, style, pre/code (nested), and textarea, and in embedded CSS (e.g. SVG). The Verse block stays typographic unless it is also a Code block. See the plugin documentation on GitHub for details.

= Does the plugin change English ordinals (1st, 2nd) or “1ème”? =

No. English-style ordinals and non-standard `1ème` are left as typed. Disable **Ordinal abbreviations** under Settings > French Typo if you prefer to keep forms like `3ème` in French text.

== Screenshots ==

1. Plugin settings page

== Changelog ==

Full history for all versions: [CHANGELOG.md](https://github.com/jaz-on/french-typo/blob/main/CHANGELOG.md) on GitHub.

= 1.2.1 =
* Fixed: Settings page HTML for narrow-space and special-character help — tag names in angle brackets are escaped so the form and save button render correctly (browsers no longer interpret `script` / `textarea` / etc. as live tags).
* Changed: Regenerated `languages/french-typo.pot` for those admin strings; dropped bundled `fr_FR` PO from the repo (translations on translate.wordpress.org).
* Documentation: WordPress compatibility note for this release; see GitHub PR [#7](https://github.com/jaz-on/french-typo/pull/7).
* Compatibility: Tested up to WordPress 7.0

= 1.2.0 =
* Added: Optional French ordinal abbreviations (`1ère` → `1re`, `3ème` → `3e`, `n-ième` → `nième`, etc.); see GitHub [issue #3](https://github.com/jaz-on/french-typo/issues/3) (idea from [Beryl](https://github.com/beryl-dlg) on [WordPress.org](https://profiles.wordpress.org/beryldlg/)). Included in PR [#6](https://github.com/jaz-on/french-typo/pull/6).
* Added: Plugins admin screen — row meta links for French Typo (GitHub, WordPress.org support, Ko-fi, documentation on GitHub, 5-star review).
* Added: Regenerated `languages/french-typo.pot` and French (`fr_FR`) translations for those meta link labels and settings strings.
* Added: Stack-based raw regions — typography skipped inside `<pre>`, `<code>`, `<script>`, `<style>`, and `<textarea>` (nested-safe). Gutenberg Verse stays typographic unless `wp-block-code` is on the same `<pre>`.
* Added: `(tm)` / `(TM)` → ™ with the same special-characters option as `(c)` / `(r)`.
* Added: Documentation — streamlined root `README.md`; `docs/test-post-content.md` for manual QA; `docs/configuration.md` (legacy `sanitized` option), `docs/faq.md` (where typography runs), `docs/architecture.md` (`textarea` in raw markup); admin copy aligned for Posts and pages, RSS/REST toggles, and raw HTML regions.
* Fixed: No narrow spaces or `(c)` / `(r)` / `(tm)` / `(TM)` replacements inside those raw regions (e.g. Elementor SVG `<style>`, code samples).
* Fixed: Cache key includes typography options to avoid stale output after a settings change.
* Fixed: Options sanitization no longer adds a stray `sanitized` flag or reuses a static cache across validate calls.
* Improved: Settings labels and help text (Posts and pages section, raw markup, RSS/REST combined toggles).
* Credits: Julio Potier (`juliobox`) and Beryl (`beryldlg`, [profile](https://profiles.wordpress.org/beryldlg/)) added to plugin contributors on WordPress.org; reflected in the Contributors header above.
* Removed: Obsolete root `TODO.md` (task tracking moved to other locations).
* Compatibility: Tested up to WordPress 7.0

== Author & Credits ==

**Jason Rouet**
* Website: [jasonrouet.com](https://jasonrouet.com)
* Email: [bonjour@jasonrouet.com](mailto:bonjour@jasonrouet.com)
* WordPress.org: [profiles.wordpress.org/jaz_on/](https://profiles.wordpress.org/jaz_on/)

You can support this project on [Ko-fi](https://ko-fi.com/jasonrouet) or [GitHub Sponsors](https://github.com/sponsors/jaz-on).

This plugin is a fork of **French Typo** created by Gilles Marchand (master_shiva), completely rebuilt since March 2024 with the help of [Jean-Baptiste Audras](https://profiles.wordpress.org/audrasjb/). Inspired by [TypoFR](https://wordpress.org/plugins/typofr/), [Orthotypo](https://wordpress.org/plugins/orthotypo-orthotypographie-automatique/), and [Consistency](https://wordpress.org/plugins/consistency/).

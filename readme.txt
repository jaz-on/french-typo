=== French Typo ===
Contributors: jaz_on, audrasjb, juliobox, beryldlg
Tags: typography, french, typographie, francais, text-formatting
Requires at least: 6.0
Tested up to: 7.1.4
Requires PHP: 7.4
Stable tag: 1.2.5
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

= 1.2.5 =
* Added: `uninstall.php`: deleting the plugin from the Plugins screen now removes its options (multisite included)
* Fixed: Punctuation spacing no longer touches smileys (`:)`, `;-)`), `std::vector`, `C:\path`, IPv6 addresses, and no longer swallows line breaks (`Ligne\n!` stays on its own line)
* Fixed: Raw-zone detection: a `<code>` or `<pre>` mentioned inside an HTML comment no longer disables typography for the rest of the text, and a raw tag that is never closed is ignored instead of silencing everything after it (a closing tag now also closes a raw tag left open inside it)
* Fixed: Text starting with a shortcode or a bracket (`[Podcast] Quoi de neuf ?`, Divi / WPBakery `[et_pb_text]Bonjour : toi ![/et_pb_text]`) is now typographed instead of skipped; shortcode tags, including nested brackets, are protected from the first character
* Fixed: Non-breaking spaces are now only normalised when glued to the targeted punctuation: `&nbsp;` and U+00A0 inside `<pre>`, `<code>`, `<script>`, `<textarea>` and HTML attributes, prices and thousands separators (`12,50&nbsp;€`, `1 000`) and `&nbsp;&nbsp;&nbsp;` spacers are left exactly as written, including in narrow-space mode 2
* Fixed: ACF / Meta Box fields whose whole value is a URL, a `tel:` / `mailto:` link, CSS or JSON are no longer typographed (`tel:+33123`, `color: red; width: 100%`, `{"a": "b"}`); prose fields are unchanged
* Fixed: Text containing invalid or truncated UTF-8 no longer disappears: when a processing step fails, the original text is now returned untouched instead of an empty string
* Fixed: Settings page: the "Show advanced options" toggle no longer carries an `aria-label` that differs from its visible text (WCAG 2.5.3, label in name); the visible text is now its accessible name
* Fixed: Multilingual-plugin notice: "Dismiss this notice" on a site that never saved its settings no longer switches every typography rule off. The flag now has its own option, and a flag stored by 1.2.2 to 1.2.4 is still honoured
* Fixed: Multilingual-plugin notice: saving the settings no longer brings a dismissed notice back
* Fixed: `(c)`, `(r)` and `(tm)` no longer produce a broken entity (`&#169&#160;;`) when a narrow space is enabled: the `;` of the symbol entity was mistaken for punctuation
* Fixed: The Biography field of the profile screen (admin) no longer receives the typographic entities, so saving a profile no longer stores them in the database. The biography is also processed once on the public site instead of twice
* Fixed: `?`, `!` and `%` glued to an HTML entity (typically the curly quotes `&#8220;Pourquoi ?&#8221;` or the ellipsis `?&#8230;` produced by wptexturize, or `«&#8239;Pourquoi ?&#8239;»` with narrow spaces) now get their non-breaking space: the internal placeholders started with a word character, which defeated the punctuation rules
* Changed: Development tooling, no change for site visitors: the test scripts are now a PHPUnit suite (#17), `tests/HooksTest.php` checks that every filter is really attached with the right priority and argument count (#13), PHPStan level 5 runs in CI with a baseline (#16), and PHPCS configuration lives in `phpcs.xml.dist` with PHP compatibility checks for the supported floor (#15).
* Changed: CI runs the test suite on PHP 7.4 to 8.5, which backs the `Requires PHP: 7.4` header (#15).
* Changed: Faster processing of long HTML content (identical output): quicker tag and ordinal checks, a stronger cache key, and no caching of texts above 64 KB
* Changed: Internal cleanup, no change for site visitors: removed the settings sections and fields registered but never rendered (the settings page is drawn by hand), the shared symbols / ordinals / non-breaking-space sequence of `french_typo_replace()` now lives in one helper, `register_setting()` uses its array form, and the ordinal regexes use the `i` modifier instead of an inline `(?i)`
* Changed: Settings validation: input that is not an array now gives the default settings, and unknown keys are dropped before saving
* Changed: PHPStan baseline reduced from five entries to two (the other three were a false positive and a wrong argument type)

= 1.2.4 =
* Fixed: Settings page — the "Ordinal abbreviations" option is visible and can be toggled again.
* Fixed: Settings page — clearer labels for the "Language restriction" fields.
* Fixed: Shortcode attributes (e.g. `[gallery caption="Merci !"]`) are no longer altered by typography rules before the shortcode runs.

== Author & Credits ==

**Jason Rouet**
* Website: [jasonrouet.com](https://jasonrouet.com)
* Email: [bonjour@jasonrouet.com](mailto:bonjour@jasonrouet.com)
* WordPress.org: [profiles.wordpress.org/jaz_on/](https://profiles.wordpress.org/jaz_on/)

You can support this project on [Buy Me a Coffee](https://buymeacoffee.com/jasonrouet) or [GitHub Sponsors](https://github.com/sponsors/jaz-on).

This plugin is a fork of **French Typo** created by Gilles Marchand (master_shiva), completely rebuilt since March 2024 with the help of [Jean-Baptiste Audras](https://profiles.wordpress.org/audrasjb/). Inspired by [TypoFR](https://wordpress.org/plugins/typofr/), [Orthotypo](https://wordpress.org/plugins/orthotypo-orthotypographie-automatique/), and [Consistency](https://wordpress.org/plugins/consistency/).

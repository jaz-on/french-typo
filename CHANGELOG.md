# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- (Placeholder for future changes)

### Changed
- (Placeholder for future changes)

### Fixed
- (Placeholder for future changes)

## [1.2.1]

### Fixed
- Settings screen: narrow-space and special-character help text inserted sprintf tag names inside angle brackets (`<script>`, `<style>`, `<textarea>`, etc.), which browsers parsed as real HTML and broke the settings form (missing controls, malformed layout). The same markup is now spelled with escaped brackets (`&lt;…&gt;`), consistent with the ordinal abbreviations help text.

### Changed
- i18n: regenerated [`languages/french-typo.pot`](languages/french-typo.pot) for the updated admin strings; removed committed `languages/french-typo-fr_FR.po` — locale files are maintained on [translate.wordpress.org](https://translate.wordpress.org/) only.
- i18n: settings help text refactored for translate.wordpress.org (raw-markup and Gutenberg snippets built in PHP; shorter translatable strings; RSS and REST guidance split into separate paragraphs), with [`languages/french-typo.pot`](languages/french-typo.pot) updated for the new msgids.
- Documentation: WordPress **Tested up to** messaging aligned with 7.0 for this release (see PR [#7](https://github.com/jaz-on/french-typo/pull/7)); development docs and Cursor i18n rule describe POT-only tracking in `languages/`.

### Compatibility
- Tested up to WordPress 7.0

## [1.2.0]

### Added
- French ordinal abbreviations ([issue #3](https://github.com/jaz-on/french-typo/issues/3), feedback [WordPress.org support thread](https://wordpress.org/support/topic/simple-efficent-and-very-useful-thanks/) via [Beryl](https://github.com/beryl-dlg)): optional normalization in running text (`1ère` → `1re`, `3ème` → `3e`, `n-ième` / `x-ième` with hyphen variants → `nième` / `xième`), same raw/stack boundaries as NBSP and `(c)` / `(r)` / `(tm)`. Settings section **Ordinal abbreviations**; option `ordinal_abbreviations` defaults to **on** when missing from saved options. English ordinals (`1st`, `2nd`) and non-standard `1ème` are left unchanged. Static cache key includes this toggle. Tests: `tests/french-typo-replace-test.php`, `tests/french-typo-replace-ordinal-off-test.php`, `tests/french-typo-replace-ordinal-only-test.php`. Shipped with release PR [#6](https://github.com/jaz-on/french-typo/pull/6).
- Plugins admin screen: row meta links (GitHub, WordPress.org support, Ko-fi, documentation on GitHub, 5-star review) for French Typo.
- i18n: regenerated [`languages/french-typo.pot`](languages/french-typo.pot); French ([`languages/french-typo-fr_FR.po`](languages/french-typo-fr_FR.po)) strings for those meta link labels and the 5-star `aria-label`.
- Stack-based raw-text boundaries for `<pre>`, `<code>`, `<script>`, `<style>`, and `<textarea>` (nested safe). Gutenberg Verse (`wp-block-verse`) remains typographic unless `wp-block-code` is also present on the same `<pre>`.
- Special characters: `(tm)` / `(TM)` → ™ (same option as © / ®).
- Documentation: streamlined root [README.md](README.md) and added [docs/test-post-content.md](docs/test-post-content.md) with copy-paste Gutenberg scenarios for manual QA (not an automated test suite).
- Documentation: [configuration.md](docs/configuration.md) troubleshooting for rare legacy `sanitized` option key; [FAQ](docs/faq.md) entry for where typography does not run; [architecture.md](docs/architecture.md) lists `textarea` in raw markup; admin-aligned copy in configuration for Posts and pages, RSS/REST toggles, and raw HTML regions.
- Credits: [Julio Potier](https://profiles.wordpress.org/juliobox/) (`juliobox`) added to plugin contributors on WordPress.org (`readme.txt`).

### Changed
- Settings screen: **Posts and pages** section label, help text for raw markup boundaries, NBSP until first save, special-character scope in raw regions, and RSS/REST combined-toggle guidance.

### Fixed
- Skip narrow non-breaking spaces **and** `(c)` / `(r)` / `(tm)` / `(TM)` replacements inside `<pre>`, `<code>`, `<script>`, `<style>`, and `<textarea>` (including inline SVG/CSS such as Elementor icon markup), so `:` / `;` in embedded CSS/JSON/JS and literals in code blocks stay unchanged; raw boundaries extended to `<textarea>`.
- Static cache key in `french_typo_replace()` now factors in narrow-space choice and special-character toggle to avoid stale output after settings change.
- Settings sanitization no longer persists a stray `sanitized` key or short-circuits validation via a static cache.

### Removed
- Root `TODO.md` file (outdated; task tracking lives elsewhere, e.g. `_todo/`).

### Compatibility
- Tested up to WordPress 7.0

## [1.1.0]

### Added
- New generic wrapper function `french_typo_replace_wrapper()` for optimized filter processing
- Enhanced static cache implementation with improved memory management
- Comprehensive hook mapping system for better performance

### Changed
- Consolidated multiple individual wrapper functions into single optimized function
- Improved code organization and maintainability
- Enhanced performance through reduced function calls and optimized hook handling
- Updated technical architecture documentation to reflect new optimizations

### Performance
- Significant reduction in function calls through consolidated wrapper approach
- Optimized static cache implementation for better memory usage
- Enhanced hook processing efficiency with static mapping array
- Improved overall plugin performance and resource utilization

### Compatibility
- Tested up to WordPress 6.9
- Maintained backward compatibility with all existing functionality
- Confirmed compatibility with PHP 7.4 through 8.3

### Code Quality
- Improved code organization with better separation of concerns
- Enhanced maintainability through consolidated function architecture
- Better documentation and inline comments for complex logic
- Maintained full compliance with WordPress Coding Standards

## [1.0.0]

### Added
- Initial release
- Complete rewrite from the original French Typo plugin
- Support for non-breaking spaces (regular and thin)
- Support for special character replacements (`(c)` → `©`, `(r)` → `®`)
- Configurable settings page with granular options
- Full Custom Post Types support
- Custom Fields support (ACF, Meta Box)
- Taxonomies support (categories, tags, custom taxonomies)
- Archives support (all types)
- Excerpts support
- Comments support (text and author names)
- Widgets and menus support
- RSS feeds support (titles, content, excerpts, comments)
- REST API support (posts, pages, attachments)
- User profiles support
- Breadcrumbs support (Yoast SEO, Rank Math, SEOPress)
- SEO meta descriptions support (Yoast SEO, Rank Math, SEOPress)
- Social tags support (Open Graph, Twitter Cards)
- Developer API: filter hook `french_typo_process_text` for custom content processing

### Changed
- Complete code rewrite from original plugin

### Fixed
- N/A (initial release)

### Security
- Proper data sanitization and validation throughout

### Performance
- Static cache for plugin options to reduce database queries
- Optimized regex patterns and early returns for better performance

### Accessibility
- Improved color contrast (WCAG 2.1 AA compliance)

### Code Quality
- WordPress Coding Standards: full compliance with WordPress-Extra standards
- Modern admin interface CSS with custom properties and simplified interactions


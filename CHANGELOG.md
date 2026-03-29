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

## [1.2.0]

### Added
- Stack-based raw-text boundaries for `<pre>`, `<code>`, `<script>`, and `<style>` (nested safe). Gutenberg Verse (`wp-block-verse`) remains typographic unless `wp-block-code` is also present on the same `<pre>`.

### Fixed
- Skip narrow non-breaking spaces **and** `(c)` / `(r)` replacements inside `<pre>`, `<code>`, `<script>`, and `<style>` (including inline SVG/CSS such as Elementor icon markup), so `:` / `;` in embedded CSS/JSON/JS and literals in code blocks stay unchanged.
- Static cache key in `french_typo_replace()` now factors in narrow-space choice and special-character toggle to avoid stale output after settings change.

### Removed
- Root `TODO.md` file (outdated; task tracking lives elsewhere, e.g. `_todo/`).

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


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

## [1.0.0] - 2024-01-15

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


# Technical Architecture

This document describes the technical architecture of the French Typo plugin, its structure, internal operation, and design decisions.

## Plugin Structure

The French Typo plugin follows a **monolithic** architecture: almost all runtime code lives in [`french-typo.php`](../french-typo.php). Admin styles load from [`admin.css`](../admin.css) on the settings screen (`settings_page_french-typo`).

### Main file responsibilities

- **`french-typo.php`**
  - Plugin header and constants
  - `french_typo_load_textdomain()` — text domain on `init` (priority 0)
  - `french_typo_hooks()` — registers WordPress filters and (in admin) menu, settings, assets, action links
  - Options: `french_typo_get_options()`, `french_typo_options_validate()`
  - Processing: `french_typo_replace()`, `french_typo_replace_wrapper()`, RSS/REST/breadcrumb/user-meta helpers
  - Markup helpers (v1.2.0): `french_typo_markup_*` — stack-aware boundaries for raw text in HTML
  - Administration UI callbacks and settings page

### Code organization (by function)

1. **Bootstrap** — `french_typo_load_textdomain`, `french_typo_hooks` (attached to `init`).
2. **Options** — `french_typo_get_options()` merges saved values with defaults via `wp_parse_args()` and normalizes the non-breaking space setting to an HTML entity or `false`. Boolean options include special characters, optional ordinal abbreviations (`ordinal_abbreviations`), and the various `apply_to_*` gates. A static variable avoids recomputing that merge on every call in the same request.
3. **Filtering** — `french_typo_replace_wrapper()` maps `current_filter()` to an `apply_to_*` option and calls `french_typo_replace()` only when enabled. RSS, REST, breadcrumbs, user meta, and custom fields use small dedicated functions with explicit checks.
4. **Core typography** — `french_typo_replace()` applies special-character replacements, optional French ordinal abbreviations (`french_typo_apply_ordinal_abbreviations()` in prose segments only), and/or narrow-space rules, with optional in-memory caching for longer strings; cache keys include the active narrow-space mode, special-character toggle, and ordinal setting so output cannot go stale after a settings change.
5. **Admin** — Settings API registration, validation, and `french_typo_admin_options()` output.

## Text processing flow

### `french_typo_replace()`

This is the core function. In order:

1. **Guards** — Ignore non-strings and very short strings.
2. **Options** — Read processed options (see caching below). If narrow spaces, special-character replacement, and ordinal abbreviations are all off, return unchanged.
3. **Caching** — For longer texts, a small static request-level cache may short-circuit repeated work; keys incorporate typography-related settings.
4. **Plain text vs markup** — If the string contains `<` or `[`, segments come from `wp_html_split()`. Typography runs only on text segments, not on tag tokens. Shortcode-like `[` segments are skipped.
5. **Raw markup** — Inside HTML, `script`, `style`, `pre`, `code`, and `textarea` regions are tracked with a stack so literals and embedded CSS/JS are not altered. Gutenberg Verse (`wp-block-verse` on `pre` without `wp-block-code`) is treated as normal prose. Details: [CHANGELOG.md](../CHANGELOG.md) (v1.2.0).

When there is no HTML/shortcode signal, processing uses a simpler path with the same punctuation rules.

### WordPress hooks

`french_typo_hooks()` registers all content filters.

**Wrapper (`french_typo_replace_wrapper`)** — Maps each hook name to one `apply_to_*` flag via a static array, loads options once per call path, and delegates to `french_typo_replace()` when enabled.

**Main content:** `the_title`, `the_content`, `the_excerpt`

**Widgets and menus:** `widget_text`, `widget_text_content`, `widget_block_content`, `widget_title`, `widget_text_title`, `widget_block_title`, `wp_nav_menu_items`

**Taxonomies:** `term_description`, `single_term_title`, `single_cat_title`, `single_tag_title`, `single_post_type_archive_title`

**Archives:** `get_the_archive_title`, `get_the_archive_description`

**Comments:** `comment_text`, `get_comment_author`

**RSS:** `the_title_rss`, `the_content_feed`, `the_excerpt_rss`, `comment_text_rss` (each checks `apply_to_rss` plus the relevant content flag)

**REST API:** `rest_prepare_post`, `rest_prepare_page`, `rest_prepare_attachment`

**User profiles:** `get_the_author_description`, `get_user_metadata` (description only; see `french_typo_user_meta()`)

**Breadcrumbs (when SEO plugin present):** `wpseo_breadcrumb_links`, `rank_math/frontend/breadcrumb/items`, `seopress_breadcrumbs_items` — gated by `apply_to_breadcrumbs`.

**SEO title, description, Open Graph, Twitter (when plugin present):** Yoast, Rank Math, SEOPress filters call `french_typo_replace` **directly** (not the wrapper), so they are **not** tied to the “Titles / Content” toggles. See [api.md](api.md).

**Custom integration:** `french_typo_process_text` → `french_typo_replace`.

### Third-party integrations

- **ACF** — `function_exists( 'get_field' )` — `acf/format_value` for `text`, `textarea`, `wysiwyg` via `french_typo_replace_custom_field`.
- **Meta Box** — `function_exists( 'rwmb_get_value' )` — `rwmb_the_value`.
- **Yoast / Rank Math / SEOPress** — Constants `WPSEO_VERSION`, `RANK_MATH_VERSION`, `SEOPRESS_VERSION` — breadcrumbs plus meta/social filters as above.

## Caching

- **`french_typo_get_options()`** — One static copy of merged, normalized options per HTTP request (rebuilt when the function runs again in the same process after a cold start; WordPress persists options via `get_option()`).
- **`french_typo_replace()`** — Optional small static cache for long strings; keys include settings that affect output so results stay consistent after option changes.

No separate “options version” option or invalidation hook is used.

## Design decisions

### Monolithic file

Single entry point, easy deployment, no autoload overhead, appropriate for the project size.

### Static in-request caches

Avoids repeated merging and repeated heavy transforms on duplicate long strings without external cache dependencies.

### HTML handling: split + stack, not a full DOM

`wp_html_split()` keeps tag boundaries stable; a stack tracks raw containers (`script`, `style`, `pre`, `code`) so typography does not corrupt code or SVG/CSS snippets. Regex alone is not enough for nested regions; the stack addresses that without pulling in a DOM parser.

## Processed content areas (overview)

Posts/pages/CPTs, taxonomies, archives, comments, widgets (classic and block), menus, RSS, REST, user bios, breadcrumbs (optional), ACF/Meta Box, and SEO plugin outputs where hooks are registered. Most areas respect their `apply_to_*` checkboxes; SEO title/description/social strings from Yoast, Rank Math, and SEOPress are an exception (see [api.md](api.md)).

## Where to look in code

Use `french_typo_hooks()`, `french_typo_replace()`, and `french_typo_get_options()` in [`french-typo.php`](../french-typo.php) as the main entry points; avoid citing line numbers in documentation because they drift.

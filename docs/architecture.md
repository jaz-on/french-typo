# Technical Architecture

This document describes the technical architecture of the French Typo plugin, its structure, internal operation, and design decisions.

## Plugin Structure

The French Typo plugin follows a **monolithic** architecture: all code is contained in a single main file `french-typo.php` (1345 lines).

### Main File

- **`french-typo.php`** — Single entry point of the plugin
  - Plugin header (lines 1-23)
  - Security check (line 26)
  - Hook initialization (line 35)
  - Text processing functions
  - Administration interface
  - Options management

### Code Organization

The code is organized into logical sections:

1. **Initialization** (lines 28-141)
   - `french_typo_hooks()` function — Registration of all WordPress hooks

2. **Options Management** (lines 143-197)
   - `french_typo_deactivate()` — Cleanup on plugin deactivation (line 150)
   - `french_typo_get_options()` — Options retrieval with static cache and versioning (line 169)
   - `french_typo_invalidate_options_cache()` — Cache invalidation via version increment (line 193)

3. **Processing Functions** (lines 198-725)
   - Wrapper functions for each content type (title, content, excerpt, widget, etc.)
   - Each wrapper checks if processing is enabled via options before applying rules
   - Default behavior: if option is not set, processing is enabled (backward compatibility)
   - Main function `french_typo_replace()` — Generic text processing (line 639)
   - RSS functions check both `apply_to_rss` and the specific content type option
   - REST API function processes title, content, and excerpt based on their respective options

4. **Administration Interface** (lines 727-1345)
   - Administration menu
   - Settings page
   - Options validation
   - Form display

## Text Processing Flow

### Main Function: `french_typo_replace()`

The `french_typo_replace()` function (line 639) is the core of the plugin. It applies French typography rules to text.

#### Processing Algorithm

1. **Preliminary Checks** (lines 641-643)
   - Immediate return if text is empty or very short (< 3 characters)

2. **Options Retrieval** (line 646)
   - Uses static cache with versioning to avoid repeated database queries
   - Only checks version number on each call (fast)
   - Reloads options only when version changes

3. **Non-breaking Space Type Determination** (lines 648-660)
   - `0` = disabled
   - `1` = regular non-breaking space (`&#160;`)
   - `2` = thin non-breaking space (`&#8239;`)

4. **Special Characters Check** (lines 662-663)
   - Enable/disable replacement of `(c)` → `©` and `(r)` → `®`

5. **Early Return if Everything is Disabled** (lines 665-668)
   - Optimization: avoids unnecessary processing

6. **Replacement Preparation** (lines 670-688)
   - Static replacements for special characters
   - Regex patterns for non-breaking spaces

7. **Markup Detection** (lines 690-692)
   - Checks for HTML tags or shortcodes
   - Optimization: avoids regex processing on plain text

8. **Text Division** (lines 694-701)
   - If markup present: split into segments (text, HTML tags, shortcodes)
   - If plain text: direct processing

9. **Segment-by-Segment Processing** (lines 704-723)
   - Processes only text segments (ignores HTML/shortcodes)
   - Applies replacements
   - Reconstructs final text

### WordPress Hooks Management

The `french_typo_hooks()` function (line 35) registers all necessary WordPress filters.

#### Hooks by Content Type

**Main Content:**
- `the_title` — Post/page titles
- `the_content` — Post/page content
- `the_excerpt` — Excerpts

**Widgets and Menus:**
- `widget_text` — Text widget content
- `widget_title` — Widget titles
- `wp_nav_menu_items` — Menu items

**Taxonomies:**
- `term_description` — Term descriptions
- `single_term_title`, `single_cat_title`, `single_tag_title` — Taxonomy titles
- `single_post_type_archive_title` — Post type archive titles

**Archives:**
- `get_the_archive_title` — Archive titles
- `get_the_archive_description` — Archive descriptions

**Comments:**
- `comment_text` — Comment text
- `get_comment_author` — Comment author names

**RSS:**
- `the_title_rss`, `the_content_feed`, `the_excerpt_rss`, `comment_text_rss`

**REST API:**
- `rest_prepare_post`, `rest_prepare_page`, `rest_prepare_attachment`

**User Profiles:**
- `get_the_author_description` — Author descriptions
- `get_user_meta` — User meta (only processes `description` meta key, line 586)

**Breadcrumbs (SEO):**
- `wpseo_breadcrumb_links` (Yoast SEO) — Processes `text` and `title` properties of breadcrumb items
- `rank_math/frontend/breadcrumb/items` (Rank Math) — Processes `text` and `title` properties
- `seopress_breadcrumbs_items` (SEOPress) — Processes `text` and `title` properties

**SEO Meta:**
- Yoast SEO, Rank Math, and SEOPress filters for meta descriptions and social tags

**Generic Hook:**
- `french_typo_process_text` — Custom hook for custom content processing

### Third-Party Plugin Support

The plugin automatically detects and integrates with:

#### Advanced Custom Fields (ACF)
- Detection: `function_exists( 'get_field' )` (line 64)
- Filters: `acf/format_value/type=text`, `acf/format_value/type=textarea`, `acf/format_value/type=wysiwyg`

#### Meta Box
- Detection: `function_exists( 'rwmb_get_value' )` (line 71)
- Filter: `rwmb_the_value`

#### Yoast SEO
- Detection: `defined( 'WPSEO_VERSION' )` (line 91)
- Filters: breadcrumbs, meta descriptions, Open Graph, Twitter Cards

#### Rank Math
- Detection: `defined( 'RANK_MATH_VERSION' )` (line 94)
- Filters: breadcrumbs, meta descriptions, Open Graph, Twitter Cards

#### SEOPress
- Detection: `defined( 'SEOPRESS_VERSION' )` (line 97)
- Filters: breadcrumbs, meta descriptions, Open Graph, Twitter Cards

## Caching System

### Static Options Cache with Versioning

The plugin uses a sophisticated static cache with versioning to avoid repeated database queries:

```php
function french_typo_get_options() {
    static $cached_options = null;
    static $cache_version  = 0;

    // Get current option version from database to detect changes.
    $current_version = get_option( 'french_typo_options_version', 0 );

    // If version changed or cache is empty, reload options.
    if ( null === $cached_options || $cache_version !== $current_version ) {
        $cached_options = get_option( 'french_typo_options', array() );
        $cache_version  = $current_version;
    }

    return $cached_options;
}
```

**Advantages:**
- Performance: single DB query per HTTP request (only checks version number)
- Simplicity: no external dependency
- Reliability: cache automatically invalidated via version increment
- Efficiency: version check is faster than full option retrieval

**Invalidation:**
- Automatic on options save via `french_typo_invalidate_options_cache()` (line 193)
- Increments `french_typo_options_version` option to trigger cache refresh
- Cache is checked on every call but only reloaded when version changes

## Design Decisions

### Why a Monolithic Architecture?

1. **Simplicity** — Single file to maintain
2. **Performance** — No multiple file loading
3. **Compatibility** — Works on all WordPress environments
4. **Project Size** — Plugin is small enough to remain monolithic

### Why Static Cache with Versioning Instead of External Caching System?

1. **Simplicity** — No dependency on complex caching systems
2. **Performance** — Version check is faster than full option retrieval, cache only reloads when needed
3. **Reliability** — Version-based invalidation ensures cache consistency across requests
4. **Compatibility** — Works on all environments, even without external cache
5. **Maintenance** — Less code to maintain than full caching solution

### Why Process HTML with Regex Instead of a Parser?

1. **Performance** — Regex is faster than a full DOM parser
2. **Simplicity** — No external dependency
3. **Reliability** — Code carefully avoids processing HTML tags
4. **Compatibility** — Works with all WordPress content types

## Processed Content Areas

The plugin automatically processes:

- **Main Content**: Posts, pages, Custom Post Types (titles, content, excerpts)
- **Taxonomies**: Categories, tags, custom taxonomies (titles and descriptions)
- **Archives**: All archive types (titles and descriptions)
- **Comments**: Text and author names
- **Widgets**: Text widget content and titles
- **Menus**: Navigation items
- **RSS**: RSS feeds (titles, content, excerpts, comments)
- **REST API**: API responses for posts, pages, and attachments
- **User Profiles**: User descriptions
- **Breadcrumbs**: Yoast SEO, Rank Math, SEOPress support
- **SEO**: Meta descriptions and titles (Yoast SEO, Rank Math, SEOPress)
- **Social Media**: Open Graph and Twitter Cards tags (Yoast SEO, Rank Math, SEOPress)
- **Custom Fields**: ACF and Meta Box

All these areas can be enabled or disabled individually from the settings page.

## Code References

- Main function: `french_typo_replace()` — line 639
- Hook initialization: `french_typo_hooks()` — line 35
- Options cache: `french_typo_get_options()` — line 169
- Cache invalidation: `french_typo_invalidate_options_cache()` — line 193
- Plugin deactivation: `french_typo_deactivate()` — line 150
- Admin interface: `french_typo_admin_options()` — line 1252

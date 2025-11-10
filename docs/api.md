# Public API

This document describes the public API of the French Typo plugin, available hooks for developers, and usage examples.

## Custom Hook: `french_typo_process_text`

The plugin exposes a WordPress filter hook that allows developers to apply French typography rules to custom content.

### Description

```php
apply_filters( 'french_typo_process_text', $text );
```

This hook allows processing any text with the French typography rules configured in the plugin.

### Parameters

- **`$text`** (string) — The text to process

### Return Value

- **`string`** — The processed text with French typography rules applied

### Usage Examples

#### Example 1: Process Custom Text

```php
// Text to process
$my_text = "Hello: how are you? It's great!";

// Apply French typography rules
$processed_text = apply_filters( 'french_typo_process_text', $my_text );

// Result: "Hello&#160;: how are you&#160;? It's great&#160;!"
// (with non-breaking spaces before : ? !)
```

#### Example 2: Process Custom Field

```php
// Get a custom field
$custom_value = get_post_meta( $post_id, 'my_custom_field', true );

// Apply typography rules
$processed_value = apply_filters( 'french_typo_process_text', $custom_value );

// Display the result
echo $processed_value;
```

#### Example 3: Process Content in Custom Template

```php
// In a theme template
$custom_content = "My content: it's important!";

// Apply typography rules
$processed_content = apply_filters( 'french_typo_process_text', $custom_content );

echo '<div class="my-content">' . wp_kses_post( $processed_content ) . '</div>';
```

#### Example 4: Process Multiple Texts in Loop

```php
$texts = array(
    "First text: important!",
    "Second text; also important?",
    "Third text (c) with copyright"
);

foreach ( $texts as $text ) {
    $processed_text = apply_filters( 'french_typo_process_text', $text );
    echo '<p>' . esc_html( $processed_text ) . '</p>';
}
```

### Integration with Other Plugins

The `french_typo_process_text` hook can be used by other plugins to process their own content:

```php
// In another plugin
add_filter( 'my_plugin_content', function( $content ) {
    // Apply French typography rules
    return apply_filters( 'french_typo_process_text', $content );
}, 10, 1 );
```

## WordPress Filters Used by the Plugin

The plugin integrates with WordPress using many native filters. Here is the complete list of filters used:

### Main Content

- `the_title` — Post and page titles
- `the_content` — Post and page content
- `the_excerpt` — Excerpts

### Widgets and Menus

- `widget_text` — Text widget content
- `widget_title` — Widget titles
- `wp_nav_menu_items` — Navigation menu items

### Taxonomies

- `term_description` — Taxonomy term descriptions
- `single_term_title` — Taxonomy term title
- `single_cat_title` — Category title
- `single_tag_title` — Tag title
- `single_post_type_archive_title` — Post type archive title

### Archives

- `get_the_archive_title` — Archive title
- `get_the_archive_description` — Archive description

### Comments

- `comment_text` — Comment text
- `get_comment_author` — Comment author name

### RSS

- `the_title_rss` — Title in RSS feeds (checks both `apply_to_rss` and `apply_to_titles`)
- `the_content_feed` — Content in RSS feeds (checks both `apply_to_rss` and `apply_to_content`)
- `the_excerpt_rss` — Excerpt in RSS feeds (checks both `apply_to_rss` and `apply_to_excerpts`)
- `comment_text_rss` — Comment text in RSS feeds (checks both `apply_to_rss` and `apply_to_comments`)

**Note**: RSS functions require both the RSS option and the specific content type option to be enabled.

### REST API

- `rest_prepare_post` — REST API response for posts (processes `title.rendered`, `content.rendered`, `excerpt.rendered`)
- `rest_prepare_page` — REST API response for pages (processes `title.rendered`, `content.rendered`, `excerpt.rendered`)
- `rest_prepare_attachment` — REST API response for attachments (processes `title.rendered`, `content.rendered`, `excerpt.rendered`)

**Note**: The REST API function checks both `apply_to_rest_api` and the specific content type options (`apply_to_titles`, `apply_to_content`, `apply_to_excerpts`).

### User Profiles

- `get_the_author_description` — Author description
- `get_user_meta` — User meta (only processes the `description` meta key, ignores other meta keys)

### Advanced Custom Fields (ACF)

If ACF is installed, the plugin uses:

- `acf/format_value/type=text` — ACF text fields
- `acf/format_value/type=textarea` — ACF textarea fields
- `acf/format_value/type=wysiwyg` — ACF WYSIWYG fields

### Meta Box

If Meta Box is installed, the plugin uses:

- `rwmb_the_value` — Meta Box values

### Yoast SEO

If Yoast SEO is installed, the plugin uses:

- `wpseo_breadcrumb_links` — Breadcrumb links
- `wpseo_metadesc` — Meta description
- `wpseo_title` — SEO title
- `wpseo_opengraph_title` — Open Graph title
- `wpseo_opengraph_desc` — Open Graph description
- `wpseo_twitter_title` — Twitter Card title
- `wpseo_twitter_description` — Twitter Card description

### Rank Math

If Rank Math is installed, the plugin uses:

- `rank_math/frontend/breadcrumb/items` — Breadcrumb items
- `rank_math/frontend/title` — SEO title
- `rank_math/frontend/description` — SEO description
- `rank_math/opengraph/title` — Open Graph title
- `rank_math/opengraph/description` — Open Graph description
- `rank_math/twitter/title` — Twitter Card title
- `rank_math/twitter/description` — Twitter Card description

### SEOPress

If SEOPress is installed, the plugin uses:

- `seopress_breadcrumbs_items` — Breadcrumb items
- `seopress_titles_title` — SEO title
- `seopress_titles_desc` — SEO description
- `seopress_social_og_title` — Open Graph title
- `seopress_social_og_desc` — Open Graph description
- `seopress_social_twitter_title` — Twitter Card title
- `seopress_social_twitter_desc` — Twitter Card description

## Advanced Use Cases

### Disable Processing for Specific Content

If you want to disable typography processing for specific content, you can use a custom filter:

```php
// Disable processing for a specific post
add_filter( 'french_typo_process_text', function( $text ) {
    if ( is_single( 123 ) ) { // Post ID
        return $text; // Return untreated text
    }
    return $text; // Otherwise, let the plugin process
}, 5 ); // Priority 5 to execute before the plugin (priority 10)
```

### Add Custom Typography Rules

You can add your own rules by filtering the text after the plugin's processing:

```php
// Add a custom rule
add_filter( 'french_typo_process_text', function( $text ) {
    // Replace "etc." with "etc."
    $text = str_replace( 'etc.', 'etc.', $text );
    return $text;
}, 20 ); // Priority 20 to execute after the plugin
```

### Process AJAX Content

```php
// In an AJAX request
add_action( 'wp_ajax_my_action', function() {
    $content = $_POST['content'];
    
    // Apply typography rules
    $processed_content = apply_filters( 'french_typo_process_text', $content );
    
    wp_send_json_success( array(
        'content' => $processed_content
    ) );
} );
```

## Code References

- Custom hook: line 131 in `french-typo.php`
- Processing function: `french_typo_replace()` — line 639
- Hook registration: `french_typo_hooks()` — line 35

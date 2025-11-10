# Frequently Asked Questions (FAQ)

This page answers the most frequently asked questions about the French Typo plugin.

## General Features

### What does this plugin do?

The French Typo plugin automatically applies French typography rules to your WordPress content. It adds non-breaking spaces before punctuation marks (`;`, `:`, `!`, `?`, `%`, `»`) and after opening quotation marks (`«`), and replaces special characters `(c)` with `©` and `(r)` with `®`.

### Which punctuation marks are handled?

The plugin handles the following punctuation marks:

- `;` (semicolon)
- `:` (colon)
- `!` (exclamation mark)
- `?` (question mark)
- `%` (percentage)
- `«` (opening French quotation mark)
- `»` (closing French quotation mark)

### What's the difference between regular and thin non-breaking spaces?

**Regular non-breaking spaces** (`&nbsp;` / `&#160;`):
- Standard spaces that prevent line breaks
- Compatible with all browsers and operating systems
- Recommended for most sites

**Thin non-breaking spaces** (`&#8239;`):
- Narrower spaces, aesthetically closer to French typography rules
- May not display correctly depending on the font, browser, and operating system used
- Use them only if you have tested their display on your site

### Does the plugin modify existing content in the database?

**No**, the plugin does not apply typography rules directly in the database. Rules are applied **on-the-fly** when content is displayed. This means:

- Your original content remains intact in the database
- Modifications are visible only on the front-end
- You can deactivate the plugin at any time without losing data
- Modifications are reversible

### Can I disable certain features?

Yes, you can independently disable:

- Non-breaking spaces (completely or choose between regular and thin)
- Special character replacements
- Each content area individually (titles, content, widgets, menus, taxonomies, archives, comments, RSS, REST API, etc.)

All these options are available in **Settings > French Typo**.

## Content Areas

### Which content areas are covered?

The plugin automatically processes:

- **Main Content**: Post and page titles and content (including Custom Post Types), excerpts
- **Taxonomies**: Categories, tags, and custom taxonomies (titles and descriptions)
- **Archives**: Titles and descriptions of all archive pages
- **Comments**: Comment text and author names
- **Widgets**: Text widget content and titles
- **Menus**: Navigation items
- **RSS**: RSS feeds (titles, content, excerpts, comments)
- **REST API**: REST API responses for posts, pages, and attachments
- **User Profiles**: User descriptions
- **Breadcrumbs**: Support for Yoast SEO, Rank Math, and SEOPress
- **SEO**: Meta descriptions and titles (Yoast SEO, Rank Math, SEOPress)
- **Social Media**: Open Graph and Twitter Cards tags (Yoast SEO, Rank Math, SEOPress)
- **Custom Fields**: Support for ACF (Advanced Custom Fields) and Meta Box

All these areas can be enabled or disabled individually from settings.

### Does the plugin work with Custom Post Types?

Yes, the plugin automatically processes all Custom Post Types. If you enable "Titles" and "Content" processing in settings, all your Custom Post Types will be processed.

### Does the plugin work with Custom Fields?

Yes, the plugin automatically supports:

- **ACF (Advanced Custom Fields)** — Automatically detected if installed
- **Meta Box** — Automatically detected if installed

Text, textarea, and WYSIWYG custom fields are automatically processed.

### Does the plugin work with SEO plugins?

Yes, the plugin automatically supports:

- **Yoast SEO** — Meta descriptions, titles, Open Graph, Twitter Cards, breadcrumbs
- **Rank Math** — Meta descriptions, titles, Open Graph, Twitter Cards, breadcrumbs
- **SEOPress** — Meta descriptions, titles, Open Graph, Twitter Cards, breadcrumbs

The plugin automatically detects these plugins if they are installed and activated.

## Compatibility

### Is the plugin compatible with my theme?

The plugin is compatible with all WordPress themes. It processes content via standard WordPress filters, so it works independently of the theme used.

### Is the plugin compatible with other typography plugins?

The plugin may conflict with other plugins that also modify typography. If you use multiple typography plugins, test them together to verify they don't overlap.

### Does the plugin work with page builders?

Yes, the plugin works with most page builders (Elementor, Beaver Builder, Gutenberg, etc.) because it processes content via standard WordPress filters. However, some page builders may have their own content processing system. Test on your site to verify.

### Does the plugin work with caching plugins?

Yes, the plugin works with caching plugins. Since processing is done on-the-fly when displayed, cached content will already contain applied typography rules.

If you modify plugin settings, remember to clear your site cache to see changes immediately.

## Common Issues

### Non-breaking Spaces Not Displaying

Verify that:

1. The "Non-breaking spaces" option is not disabled in settings
2. The concerned content area is enabled
3. You're using French punctuation marks (`:`, `;`, `!`, `?`, etc.)
4. If you're using thin non-breaking spaces, try regular spaces
5. Clear your site cache (if you use a caching plugin)

### Special Characters Not Replaced

Verify that:

1. The "Special characters" option is enabled
2. The concerned content area is enabled
3. You're using exactly `(c)` and `(r)` with normal parentheses (not special characters)

### Plugin Slowing Down My Site

The plugin is optimized for performance:

- Uses a static cache for options
- Processes only necessary content
- Avoids unnecessary processing (early returns)

If you notice slowdown:

1. Disable content areas you don't use
2. Verify that you don't have other plugins also processing content
3. Use a caching plugin to improve overall performance

### Plugin Not Processing Some Areas

Verify that:

1. The area is enabled in settings
2. For custom fields (ACF, Meta Box), plugins are installed and activated
3. For SEO plugins, Yoast SEO, Rank Math, or SEOPress is installed and activated
4. Content is displayed via standard WordPress filters

## Advanced Usage

### Can I use the plugin to process custom content?

Yes, the plugin exposes a `french_typo_process_text` hook that you can use in your code. See the [API Documentation](api.md) for more details and examples.

### Can I disable the plugin for certain content?

Yes, you can use custom WordPress filters to disable processing for certain content. See the [API Documentation](api.md) for examples.

### Does the plugin respect HTML and shortcodes?

Yes, the plugin is designed not to break HTML and shortcodes. It processes only text outside HTML tags and shortcodes.

## Support and Contribution

### Where can I report a bug?

You can create an [issue on GitHub](https://github.com/jaz-on/french-typo/issues) to report a bug or suggest an improvement.

### Can I contribute to the project?

Yes! Contributions are welcome. See the [Development Guide](development.md) for more information.

### Where can I get additional help?

- Consult the [Complete Documentation](../README.md)
- Consult the [Configuration Guide](configuration.md)
- Create an [issue on GitHub](https://github.com/jaz-on/french-typo/issues)

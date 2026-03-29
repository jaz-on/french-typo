# Configuration Guide

This guide explains how to configure the French Typo plugin according to your needs.

## Accessing the Settings Page

To access the plugin settings page:

1. Log in to WordPress administration
2. Go to **Settings > French Typo**
3. You can also click **Settings** in the plugins list

## Configuration Options

### Non-breaking Spaces

Non-breaking spaces prevent line breaks before French punctuation marks.

#### Available Options

- **Disabled** — No non-breaking space is added
- **Regular non-breaking spaces** — Uses HTML entity `&nbsp;` or `&#160;`
- **Thin non-breaking spaces** — Uses HTML entity `&#8239;` (narrower space)

#### Affected Punctuation Marks

Non-breaking spaces are added before:
- `;` (semicolon)
- `:` (colon)
- `!` (exclamation mark)
- `?` (question mark)
- `%` (percentage)
- `»` (closing French quotation mark)

And after:
- `«` (opening French quotation mark)

#### Raw HTML / code regions

Narrow **non-breaking spaces** are **not** inserted inside raw markup: `<script>`, `<style>`, nested `<pre>` / `<code>`, and `<textarea>` (stack-safe for nesting). The same applies to **special character** replacements (`(c)`, `(r)`, `(tm)`). Gutenberg **Verse** (`<pre class="… wp-block-verse …">`) is still typographed **unless** `wp-block-code` is also on the same `<pre>`.

#### Recommendation

- **Regular non-breaking spaces**: Recommended for most sites, better compatibility
- **Thin non-breaking spaces**: More aesthetic but may not display correctly depending on the font, browser, and operating system

### Special Characters

The plugin can automatically replace certain characters:

- `(c)` → `©` (copyright)
- `(r)` → `®` (registered trademark)
- `(tm)` / `(TM)` → `™` (trademark)

#### Available Options

- **Enabled** — Special characters are automatically replaced
- **Disabled** — No replacement is performed

### Content Areas

The plugin can process different areas of your WordPress site. You can enable or disable each area individually.

In **Settings > French Typo**, **Posts and pages** controls only **post and page titles** and **main content**. **Excerpts** and all other areas are under **Advanced options**.

#### Available Areas

**Posts and pages (settings screen):**
- **Titles** — Post and page titles
- **Content** — Post and page content

**Advanced options — main content-related:**
- **Excerpts** — Post excerpts

**Custom Post Types:**
- All custom content types are automatically processed if "Titles" and "Content" options are enabled

**Taxonomies:**
- **Taxonomy Titles** — Categories, tags, custom taxonomies
- **Taxonomy Descriptions** — Taxonomy term descriptions

**Archives:**
- **Archive Titles** — Titles of all archive pages
- **Archive Descriptions** — Archive descriptions

**Comments:**
- **Comment Text** — Comment content
- **Author Names** — Comment author names

**Widgets and Menus:**
- **Widget Content** — Text widget content
- **Widget Titles** — Widget titles
- **Menu Items** — Navigation items

**RSS:**
- **RSS Titles** — Titles in RSS feeds
- **RSS Content** — Content in RSS feeds
- **RSS Excerpts** — Excerpts in RSS feeds
- **RSS Comments** — Comments in RSS feeds

**REST API:**
- **REST API Responses** — REST API responses for posts, pages, and attachments

**User Profiles:**
- **User Descriptions** — User profile descriptions

**Breadcrumbs (SEO):**
- Automatic support for Yoast SEO, Rank Math, and SEOPress

**SEO:**
- **Meta Descriptions** — SEO descriptions (Yoast SEO, Rank Math, SEOPress)
- **SEO Titles** — SEO titles (Yoast SEO, Rank Math, SEOPress)

**Social Media:**
- **Open Graph** — Open Graph tags (Yoast SEO, Rank Math, SEOPress)
- **Twitter Cards** — Twitter Card tags (Yoast SEO, Rank Math, SEOPress)

**Custom Fields:**
- Automatic support for ACF (Advanced Custom Fields) and Meta Box

## Default Behavior

When **no options are saved yet**, `french_typo_get_options()` merges the empty (or partial) stored array with built-in defaults:

- **Special characters** (`(c)` / `(r)` / `(tm)`): **on**
- **Content-area toggles** (`apply_to_*`): **on** for titles, content, excerpts, widgets, menus, custom fields, taxonomies, archives, comments, RSS, REST API, user profiles, breadcrumbs
- **Non-breaking spaces**: **off** (`narrow_space` is not applied) until you choose “regular” or “thin” in settings and save (or until a saved option set includes that choice)

After the first **Save Changes**, the values stored in the database replace that merge behavior for whatever keys WordPress persists.

**RSS** and **REST API** require both their own toggles and the relevant content switches (for example: RSS feed titles need **RSS feeds** and **Titles**; RSS item body needs **RSS feeds** and **Post content**; REST fields follow the same pattern per field).

SEO plugin title, description, and social strings use a separate code path (see [api.md](api.md)): they are **not** controlled by the same `apply_to_*` checkboxes as front-end titles and content.

## Configuration Examples

### Default Configuration (Recommended)

- **Non-breaking spaces**: Regular (choose explicitly after activation; not applied until you save that choice)
- **Special characters**: Enabled
- **All areas**: Enabled (defaults before first save)

This is the usual target setup once you have saved settings; right after activation only, non-breaking spaces are not applied until configured.

### Minimal Configuration

- **Non-breaking spaces**: Regular
- **Special characters**: Disabled
- **Areas**: Titles and Content only

This configuration applies non-breaking spaces only to main titles and content.

### Configuration for Multilingual Site

If your site contains content in multiple languages:

- **Non-breaking spaces**: Disabled (or enabled only for French content)
- **Special characters**: Enabled
- **Areas**: Select only areas containing French content

> **Note**: The plugin currently processes all content the same way. For language-specific processing, you will need to use custom filters (see [API Documentation](api.md)).

### Configuration for High-Content Site

To optimize performance on a site with a lot of content:

- **Non-breaking spaces**: Regular
- **Special characters**: Enabled
- **Areas**: Disable areas you don't use (for example, RSS if you don't use feeds)

## Saving Settings

After modifying settings:

1. Click **Save Changes**
2. Settings are immediately applied to all content on your site
3. No page reload is necessary

## Resetting Settings

To return to default settings:

1. Disable all options
2. Re-enable desired options
3. Click **Save Changes**

> **Note**: There is no automatic "Reset" button. You must manually modify options.

## Settings Impact

### Performance

- The plugin uses a static cache for options, so configuration changes don't significantly impact performance
- Disabling content areas can slightly improve performance if you have a lot of content

### Compatibility

- Regular non-breaking spaces (`&nbsp;`) are compatible with all browsers and operating systems
- Thin non-breaking spaces (`&#8239;`) may not display correctly depending on the font used

### Existing Content

- Settings apply immediately to all existing content
- The plugin does not modify content in the database, rules are applied on-the-fly when displayed

## Troubleshooting

### Non-breaking Spaces Not Displaying

1. Verify that the "Non-breaking spaces" option is not disabled
2. Verify that the concerned content area is enabled
3. If you're using thin non-breaking spaces, try regular spaces
4. Clear your site cache (if you use a caching plugin)

### Special Characters Not Replaced

1. Verify that the "Special characters" option is enabled
2. Verify that the concerned content area is enabled
3. Ensure you're using exactly `(c)` and `(r)` (with parentheses)

### Some Areas Not Processed

1. Verify that the area is enabled in settings
2. For custom fields (ACF, Meta Box), verify that plugins are installed and activated
3. For SEO plugins, verify that Yoast SEO, Rank Math, or SEOPress is installed and activated

### Rare: stray `sanitized` key in saved options

Older plugin builds could persist a useless `sanitized` entry inside the `french_typo_options` array. **Save Settings** once on **Settings > French Typo** after updating to a fixed version so WordPress stores a clean option array. Alternatively, remove the `sanitized` key manually from the serialized option (advanced; backup first).

## Support

For more help:

- Consult the [FAQ](faq.md) for frequently asked questions
- Consult the [API Documentation](api.md) for advanced use cases
- Create an [issue on GitHub](https://github.com/jaz-on/french-typo/issues) if you encounter a problem

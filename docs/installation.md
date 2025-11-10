# Installation Guide

This guide explains how to install the French Typo plugin on your WordPress site.

## Prerequisites

Before installing the plugin, ensure your environment meets the following requirements:

- **WordPress**: version 6.0 or higher
- **PHP**: version 8.3 or higher
- **MySQL/MariaDB**: version 5.6 or higher (or equivalent)

## Installation Methods

### Method 1: Installation via WordPress Interface (Recommended)

If the plugin is available in the WordPress.org repository:

1. Log in to WordPress administration
2. Go to **Plugins > Add New**
3. Search for "French Typo" or "Typographie française"
4. Click **Install Now**
5. Click **Activate**

### Method 2: Manual Installation via ZIP

1. **Download** the latest version of the plugin from GitHub or WordPress.org
2. **Extract** the ZIP file
3. **Upload** the `french-typo` folder to `/wp-content/plugins/` on your WordPress site
4. **Activate** the plugin from **Plugins > Installed Plugins**

### Method 3: Installation via Git (for Developers)

If you use Git Updater or want to install from GitHub:

1. **Clone** the repository into `/wp-content/plugins/`:
   ```bash
   cd /wp-content/plugins/
   git clone https://github.com/jaz-on/french-typo.git
   ```

2. **Activate** the plugin from **Plugins > Installed Plugins**

### Method 4: Installation as MU-Plugin

To make the plugin mandatory on your site (Must-Use plugin):

1. **Copy** the `french-typo.php` file to `/wp-content/mu-plugins/`
2. The plugin will be automatically activated (no need to activate manually)
3. **Note**: MU-plugins cannot be deactivated from the WordPress interface

> **Note**: If you install as a MU-plugin, you must copy only the main `french-typo.php` file. Other files (like `admin.css`) must remain in a separate folder if needed.

## Git Updater Compatibility

The plugin is compatible with [Git Updater](https://github.com/afragen/git-updater), which allows automatic updates from GitHub.

### Git Updater Configuration

1. Install the Git Updater plugin
2. The French Typo plugin will be automatically detected
3. Updates will be available from **Plugins > Updates**

The necessary information is already present in the plugin header:
- `GitHub Plugin URI: jaz-on/french-typo`
- `GitHub Branch: main`
- `Primary Branch: main`

## Plugin Activation

After installation:

1. Go to **Plugins > Installed Plugins**
2. Find "French Typo" in the list
3. Click **Activate**

Once activated, the plugin immediately starts applying French typography rules to your content.

## Initial Configuration

After activation, it is recommended to configure the plugin:

1. Go to **Settings > French Typo**
2. Configure your preferences:
   - Non-breaking space type (regular or thin)
   - Special characters activation
   - Content areas to process
3. Click **Save Changes**

By default, the plugin is configured to process all content areas with regular non-breaking spaces and special characters enabled.

## Installation Verification

To verify that the plugin works correctly:

1. **Create or edit** a post/page with text containing French punctuation marks (`:`, `;`, `!`, `?`, etc.)
2. **View** the post/page on the front-end
3. **Verify** that non-breaking spaces are present before punctuation marks
4. **Test** special characters: `(c)` should become `©` and `(r)` should become `®`

## Uninstallation

To uninstall the plugin:

1. Go to **Plugins > Installed Plugins**
2. Find "French Typo"
3. Click **Deactivate** (optional, for testing)
4. Click **Delete** to completely remove the plugin

> **Note**: Uninstalling the plugin does not modify your existing content. The plugin applies typography rules on-the-fly when content is displayed, without modifying content in the database.

## Support

If you encounter problems during installation:

1. Verify that your environment meets the prerequisites
2. Consult the [FAQ](faq.md) for common issues
3. Create an [issue on GitHub](https://github.com/jaz-on/french-typo/issues) if the problem persists

## Next Steps

Once the plugin is installed and activated:

- Consult the [Configuration Guide](configuration.md) to customize settings
- Read the [FAQ](faq.md) for frequently asked questions
- Explore the [Developer Documentation](../README.md) if you want to contribute

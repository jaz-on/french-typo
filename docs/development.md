# Development Guide

This guide explains how to set up a local development environment to contribute to the French Typo plugin.

## Prerequisites

- **PHP 7.4 or higher** to match the plugin’s supported runtime (see `french-typo.php` header). GitHub Actions in this repo uses **PHP 8.3** for lint and PHPCS; using 8.3 locally matches CI.
- Composer (for development dependencies)
- Git
- A local WordPress environment (Local by Flywheel, MAMP, XAMPP, Docker, etc.)

## Local Environment Setup

### 1. Clone the Repository

```bash
git clone https://github.com/jaz-on/french-typo.git
cd french-typo
```

### 2. Install Dependencies

The plugin uses Composer to manage development dependencies (PHP CodeSniffer and WordPress Coding Standards):

```bash
composer install
```

This installs:
- `wp-coding-standards/wpcs` — WordPress coding standards
- `dealerdirect/phpcodesniffer-composer-installer` — Automatic standards installer

### 3. Configure WordPress

1. Create a local WordPress site
2. Copy the `french-typo` folder to `/wp-content/plugins/`
3. Activate the plugin from WordPress administration

### 4. Verify Installation

1. Go to `Settings > French Typo` in WordPress administration
2. Verify that the settings page displays correctly
3. Test the plugin functionality

## Code Standards

The plugin follows **WordPress Coding Standards** with the **WordPress-Extra** standard.

### Code Architecture Notes

- **Monolithic structure**: All code in a single file (`french-typo.php`)
- **In-request caches**: Merged options are cached in `french_typo_get_options()`; long strings may be cached inside `french_typo_replace()` with keys that reflect typography settings
- **Wrapper**: `french_typo_replace_wrapper()` maps the current filter to an `apply_to_*` option before calling `french_typo_replace()`
- **Default behavior**: Unsaved installs use merged defaults in `french_typo_get_options()` (most `apply_to_*` and special characters on; non-breaking spaces off until the user picks a mode or saved options include it)

### Code Verification

To verify that your code follows the standards:

```bash
# Run PHPCS
php -d memory_limit=512M vendor/bin/phpcs --standard=WordPress-Extra --extensions=php --ignore=vendor/ .
```

### Automatic Fixing

To automatically fix detected issues:

```bash
# Run PHPCBF (PHP Code Beautifier and Fixer)
php -d memory_limit=512M vendor/bin/phpcbf --standard=WordPress-Extra --extensions=php --ignore=vendor/ .
```

### PHP Validation

To verify PHP syntax:

```bash
# Check all PHP files
find . -type f -name "*.php" -not -path "./vendor/*" -print0 | xargs -0 -n1 -P4 php -l
```

## Git Workflow

### Branches

- **`main`** — Main branch, stable and tested code
- **Feature branches** — Created for each new feature or fix

### Contribution Process

1. **Create a branch** from `main`:
   ```bash
   git checkout -b my-feature
   ```

2. **Make your changes** and commit:
   ```bash
   git add .
   git commit -m "Description of the change"
   ```

3. **Push the branch**:
   ```bash
   git push origin my-feature
   ```

4. **Create a Pull Request** on GitHub

### Commit Messages

Follow commit message conventions:
- Use present indicative: "Add" rather than "Added"
- Be descriptive but concise
- Reference issues if applicable: "Fix #123"

Examples:
```
Add support for ACF custom fields
Fix non-breaking space processing in shortcodes
Improve options cache performance
```

## Testing and Validation

### Automated Tests via GitHub Actions

The project uses GitHub Actions to automatically validate code on each push and Pull Request:

1. **PHP Lint** — Checks PHP syntax of all files
2. **WordPress Coding Standards** — Verifies WordPress standards compliance
3. **readme.txt Validation** — Validates the readme.txt file format

See `.github/workflows/ci.yml` for details.

### Manual Testing

Before submitting a Pull Request, manually test:

1. **Activation/deactivation** of the plugin
2. **Configuration** of options in the settings page
3. **Content processing** on different post/page types
4. **Compatibility** with third-party plugins (ACF, Meta Box, Yoast SEO, etc.)
5. **Performance** — Verify that the plugin doesn't slow down the site

## File Structure

```
french-typo/
├── .github/
│   └── workflows/          # GitHub Actions workflows
│       ├── ci.yml          # Tests and validation
│       └── create-release-zip.yml  # Release creation
├── docs/                    # Documentation (this folder)
├── languages/               # Translation files
│   ├── french-typo.pot      # Translation template (commit)
│   └── french-typo-fr_FR.po # French strings (commit); compile locally with `wp i18n make-mo languages` (produces `*.mo`, gitignored)
├── vendor/                  # Composer dependencies (gitignored)
├── admin.css                # Administration interface styles
├── composer.json            # Composer dependencies
├── composer.lock             # Version lock
├── french-typo.php          # Main plugin file
├── readme.txt               # WordPress.org documentation
├── README.md                # Project overview
└── CHANGELOG.md             # Version history
```

## Release Process

### Creating a Release

Releases are created via GitHub Actions (`.github/workflows/create-release-zip.yml`):

1. **Create a tag** on GitHub (e.g., `1.0.0`)
2. **Create a release** associated with the tag
3. The automated workflow:
   - Verifies version consistency
   - Updates the Stable tag in `readme.txt`
   - Creates a distribution ZIP
   - Generates release notes from `CHANGELOG.md`

### Preparing a Release

Before creating a release:

1. **Update the version** in `french-typo.php` (plugin header)
2. **Update the Stable tag** in `readme.txt`
3. **Update CHANGELOG.md** with changes
4. **Test** all functionality
5. **Create the tag** and release on GitHub

### CHANGELOG Format

The project follows the [Keep a Changelog](https://keepachangelog.com/) standard:

```markdown
## [1.0.0] - 2024-01-01

### Added
- New feature

### Changed
- Existing modification

### Fixed
- Bug fix
```

## Contribution Guidelines

### Before Contributing

1. **Check existing issues** on GitHub
2. **Create an issue** if you want to discuss a feature
3. **Ensure** your code follows the standards

### Code Review

All contributions go through a Pull Request that will be reviewed:

- Code standards compliance
- Manual tests performed
- Documentation updated if necessary
- No regression introduced

### Documentation

If you add a feature:

1. **Document** in `docs/` if it's a developer feature
2. **Update** `readme.txt` if it's a user feature
3. **Add** usage examples if relevant

## Resources

- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Keep a Changelog](https://keepachangelog.com/)

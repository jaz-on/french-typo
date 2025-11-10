# GitHub Actions Workflows

This repository includes four GitHub Actions workflows for continuous integration, release management, and deployment.

## Release Drafter

The Release Drafter workflow automatically generates release notes from merged pull requests.

### Triggers

- Push to `main` branch
- Pull requests opened, updated, or closed on `main`

### How it works

1. Monitors pull requests merged into `main`
2. Automatically updates a draft release with categorized changes
3. Groups changes by labels:
   - `feature` / `enhancement` → 🚀 Features
   - `bug` / `fix` → 🐛 Bug Fixes
   - `breaking` → ⚠️ Breaking Changes
   - `documentation` / `docs` → 📚 Documentation
   - `maintenance` / `chore` / `refactor` → 🔧 Maintenance
4. Includes contributors list automatically

### Usage

1. Label your pull requests with appropriate labels (`feature`, `bug`, `fix`, etc.)
2. Merge PRs into `main`
3. Release Drafter automatically updates the draft release
4. When ready, publish the release (or let the Create Release ZIP workflow handle it)

**Note:** For the 1.0.0 release (commits directly on `main`), Release Drafter won't generate notes. The Create Release ZIP workflow will first try `CHANGELOG.md` (Keep a Changelog), and fall back to `readme.txt` if needed.

## CI

The CI workflow runs automated checks on every push to `main` and on pull requests.

### Triggers

- Push to `main` branch
- Pull requests (any branch)

### Jobs

#### PHP Lint

- Checks PHP syntax for all `.php` files (excluding `vendor/`)
- Uses PHP 8.3
- Fails if any PHP file has syntax errors

#### WordPress Coding Standards

- Runs PHPCS with `WordPress-Extra` standard
- Validates code against WordPress coding standards
- Excludes `vendor/` directory from analysis (dependencies are not checked)
- Uses PHP 8.3 with 512MB memory limit
- Requires Composer dependencies to be installed

#### Validate readme.txt

- Validates the WordPress.org `readme.txt` format
- Checks for required header fields (Plugin Name, Contributors, Tags, Requires at least, Tested up to, Requires PHP, Stable tag, License, License URI)
- Verifies required sections (Description, Installation, FAQ, Changelog)
- Validates version format for Stable tag, Requires at least, and Tested up to
- Checks changelog format and entries
- Fails if critical errors are found (warnings don't fail the build)

### Usage

The workflow runs automatically on push and pull requests. No manual action required.

## Create Release ZIP

This workflow automatically creates a clean distribution ZIP for each release, excluding development files according to `.distignore`.

### Triggers

The workflow runs when:
- A release is created (`release: created`)
- A release is edited (`release: edited`)
- It is manually dispatched (`workflow_dispatch`) with a specific tag

**Note:** Tag pushes do not trigger this workflow (by design).

### How it works

1. **Version verification:** Checks that the plugin header `Version` matches the release tag
2. **Checkout:** Checks out the code at the provided release tag
3. **Auto-sync Stable tag:** Automatically updates the `Stable tag:` in `readme.txt` to match the release tag (ZIP only, not committed to repo)
   - This ensures the ZIP always has the correct version, even if the tag was created before updating `readme.txt`
4. **Build ZIP:** Creates a clean ZIP using `.distignore` to exclude:
   - Git files (`.git/`, `.gitignore`, etc.)
   - Development docs (`README.md`, `TODO.md`, changelogs, etc.)
   - Build/test directories and scripts
   - Composer metadata and `vendor/`
   - The `.github/` directory and other CI/config files
5. **Verification:** Verifies the ZIP does not contain excluded files (composer files, dev docs, `.github/`, etc.)
6. **Upload artifact:** Uploads the ZIP as a GitHub Actions artifact (30 days retention) for debugging/download
7. **Generate release notes:** Automatically generates comprehensive release notes with the following priority:
   - **If Release Drafter notes exist:** Uses the automatically generated notes from merged PRs
   - **CHANGELOG.md (preferred):** Parses the Keep a Changelog section for the targeted version
   - **Fallback (readme.txt):** Extracts changelog from `readme.txt` for the version
   - **Additional content (if previous tag exists):**
     - Contributors list (unique authors from Git commits between tags)
     - Statistics (commits count, files changed, lines added/deleted)
     - Comparison link to previous release
   - **Sections are only included if they have content** (no empty sections)
8. **Upload release:** Uploads the ZIP as a release asset and updates release notes

### Expected ZIP contents (key items)

- `french-typo.php` — Main plugin file
- `readme.txt` — WordPress.org readme
- `LICENSE` — GPLv2 or later
- `admin.css` — Admin styles

The final contents are determined by what is not excluded by `.distignore`.

### Usage

To create a new release with an automatic ZIP:

1. Create a tag: `git tag -a 1.1.0 -m "Release 1.1.0"`
2. Push the tag: `git push origin 1.1.0`
3. Create or edit the release on GitHub (UI or `gh release create`)
4. The workflow will run and attach the clean ZIP to the release

Alternatively, run it manually (`workflow_dispatch`) and provide the `tag` input.

### Optional WordPress.org deployment

When manually dispatching, you can set `deploy_to_wporg` to `true` to trigger the deployment job. This job:
- Checks out the specified tag
- Verifies version consistency:
  - `Stable tag` in `readme.txt` must match the release tag
  - Plugin header `Version` must match the release tag
- Prepares for deployment (currently scaffolded, requires configuration)

**Note:** The actual deployment step is commented out and requires:
- SVN credentials (`SVN_USERNAME`, `SVN_PASSWORD` secrets)
- Plugin slug configuration
- Uncommenting the `10up/action-wordpress-plugin-deploy` step

### Verification

The workflow performs multiple checks:
- Plugin header `Version` matches the release tag
- `Stable tag` in `readme.txt` is automatically updated to match the tag (for ZIP only)
- ZIP does not contain excluded files (composer files, dev docs, `.github/`, etc.)
- ZIP verification ensures no development files are included
- If deployment is requested, both `Stable tag` in `readme.txt` and plugin header `Version` must match the release tag

If any check fails, the job fails with a clear error message.

## Deploy to WordPress.org (manual)

This workflow provides a manual deployment scaffold for WordPress.org.

### Triggers

- Manual dispatch only (`workflow_dispatch`)
- Requires a `version` input (tag name, e.g., `v1.0.0`)

### How it works

1. Validates that the provided tag exists
2. Shows deployment instructions and required secrets
3. Currently scaffolded — requires configuration to enable actual deployment

### Required secrets

To enable deployment, configure these secrets in GitHub repository settings:

- `SVN_USERNAME` — WordPress.org SVN username
- `SVN_PASSWORD` — WordPress.org SVN password (or use `SVN_TOKEN` instead)
- `SLUG` — Plugin slug on WordPress.org (e.g., `french-typo`)

### Assets structure

The workflow expects WordPress.org assets in `.wordpress-org/`:
- Plugin icons: `icon-128x128.png`, `icon-256x256.png`, `icon.svg`
- Screenshots: `screenshot-1.png` (and more if needed)

These will be automatically copied to `assets/` at SVN root level by the deploy action.

### Usage

1. Go to Actions → "Deploy to WordPress.org (manual)"
2. Click "Run workflow"
3. Enter the version/tag to deploy (e.g., `v1.0.0`)
4. Run the workflow

**Note:** This workflow is currently a scaffold. To enable actual deployment:
1. Configure the required secrets
2. Uncomment and configure the `10up/action-wordpress-plugin-deploy` step
3. See: https://github.com/10up/action-wordpress-plugin-deploy

### Alternative: Integrated deployment

The `create-release-zip.yml` workflow includes an optional `deploy-to-wporg` job that can be triggered when manually dispatching with `deploy_to_wporg: true`. This provides version verification before deployment.





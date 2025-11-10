# Labels for Release Drafter

This document lists the available labels for pull requests and their mapping to release notes categories (Keep a Changelog format).

## Available Labels

### Added (New Features)
- `feature` — New feature
- `enhancement` — Enhancement or extension of an existing feature

### Changed (Modifications)
- `breaking` — Breaking change
- `change` — Non-breaking change

### Fixed (Bug Fixes)
- `bug` — Bug fix
- `fix` — General fix

### Security
- `security` — Security improvement, vulnerability fix

### Performance
- `performance` — Performance optimization

### Accessibility
- `accessibility` — Accessibility improvement
- `a11y` — Alias for accessibility

### Code Quality
- `maintenance` — Code maintenance
- `chore` — Maintenance tasks
- `refactor` — Code refactoring
- `code-quality` — Code quality improvement

### Documentation
- `documentation` — Documentation
- `docs` — Alias for documentation

## Usage

1. **When creating a pull request**, add one or more appropriate labels
2. **Release Drafter** will automatically generate release notes in Keep a Changelog format
3. Notes will be organized by category according to the labels used

## Example

If a PR has the `feature` label, it will appear in the **### Added** section of the release notes.

If a PR has both `bug` and `security` labels, it will appear in both the **### Fixed** and **### Security** sections.

## Generated Format

The generated release notes follow the [Keep a Changelog](https://keepachangelog.com/) format, aligned with `CHANGELOG.md`:

```markdown
## What's Changed

### Added
- PR title (#123) by @author

### Fixed
- PR title (#124) by @author

## Contributors

@author1, @author2

**Full Changelog**: v1.0.0...v1.1.0
```


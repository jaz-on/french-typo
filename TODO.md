# TODO - Future Enhancements

## Completed (v1.0.0)

- [x] Performance optimizations: static cache for plugin options
- [x] Code quality: optimized regex patterns and early returns
- [x] Admin interface: modern CSS with custom properties
- [x] Accessibility: improved color contrast (WCAG 2.1 AA compliance)
- [x] Security: proper data sanitization and validation
- [x] WordPress Coding Standards: full compliance with WordPress-Extra
- [x] Documentation: updated README.md and readme.txt with complete changelog
- [x] Translations: POT file generated and up to date

## Deployment Files (to add after plugin approval)

Once the plugin is approved on WordPress.org, add these files:

- [ ] `.github/workflows/deploy-to-wordpress-org.yml` - GitHub Actions workflow for automated deployment
- [ ] `DEPLOYMENT.md` - Deployment documentation

These files will use the [10up WordPress Plugin Deploy Action](https://github.com/10up/action-wordpress-plugin-deploy).

### Setup Required

1. Add GitHub Secrets:
   - `SVN_USERNAME` - Your WordPress.org username
   - `SVN_PASSWORD` - Your WordPress.org password or app-specific password

2. The `.distignore` file is already in place and will be used by the deployment action.

---

## Typography Rules to Implement

### Character Replacements

#### 1. Ellipsis (High Priority)
**Impact:** Low | **Complexity:** Low

- [ ] Add settings option to enable/disable this feature
- [ ] Implement replacement of `...` with `…` (Unicode character U+2026)
- [ ] Handle cases where ellipsis already exists in content
- [ ] Avoid processing in URLs, HTML attributes, code, etc.
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases

**Example:** `He thought... then answered.` → `He thought… then answered.`

**Note:** If ellipsis replacement is enabled, do not apply spacing rule after ellipsis (see conflict resolution below).

#### 2. Typographic Apostrophes (High Priority)
**Impact:** Medium | **Complexity:** Low

- [ ] Add settings option to enable/disable this feature
- [ ] Implement replacement of straight apostrophe `'` with typographic apostrophe `'` (U+2019)
- [ ] Handle apostrophes in French contractions: `l'`, `d'`, `c'`, `m'`, `t'`, `s'`, `n'`
- [ ] Handle apostrophes in proper names: `O'Brien`, `d'Artagnan`, etc.
- [ ] Avoid processing in HTML attributes, code, etc.
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases

**Example:** `C'est l'été.` → `C'est l'été.`

#### 3. French Quotation Marks (High Priority)
**Impact:** Medium | **Complexity:** Medium (handle nested cases)

- [ ] Add settings option to enable/disable this feature
- [ ] Implement conversion of straight quotes `" "` to French quotes `« »`
- [ ] Handle nested quotes (use English quotes `" "` for inner levels)
- [ ] Detect context to determine correct opening/closing
- [ ] Avoid processing in HTML attributes, code, etc.
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases and nesting rules

**Example:** `He said "Hello" and left.` → `He said « Hello » and left.`

#### 4. Trademark Symbol (High Priority)
**Impact:** Low | **Complexity:** Low

- [ ] Add replacement of `(TM)` with `™` (trademark symbol U+2122)
- [ ] Integrate into existing "Special Characters" section
- [ ] Add option to enable/disable this specific replacement (or include in general option)
- [ ] Handle variants: `(tm)`, `(Tm)`, `(TM)`
- [ ] Avoid processing in HTML attributes, code, etc.
- [ ] Add tests to verify proper functionality
- [ ] Document this feature

**Example:** `WordPress(TM)` → `WordPress™`

### Spacing Rules

#### 5. Spacing with Measurement Units (High Priority)
**Impact:** High | **Complexity:** Moderate

- [ ] Add settings option to enable/disable this feature
- [ ] Implement detection of numbers followed by measurement units
- [ ] Handle common units:
  - Length: `km`, `m`, `cm`, `mm`, `dm`
  - Mass: `kg`, `g`, `mg`
  - Volume: `L`, `l`, `mL`, `ml`, `cl`, `dl`
  - Temperature: `°C`, `°F`, `K`
  - Time: `h`, `min`, `s`, `ms`
  - Speed: `km/h`, `m/s`
  - Area: `m²`, `km²`, `cm²`
  - Volume: `m³`, `cm³`
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases (avoid false positives in URLs, HTML attributes, etc.)

#### 6. Spacing with Currency Symbols (High Priority)
**Impact:** High | **Complexity:** Moderate

- [ ] Add settings option to enable/disable this feature
- [ ] Implement detection of amounts followed by currency symbols
- [ ] Handle symbols: `€`, `$`, `£`, `¥`
- [ ] Verify that `%` symbol is correctly handled (already implemented)
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases

#### 7. Spacing with Abbreviations (Medium Priority)
**Impact:** Moderate | **Complexity:** Variable

- [ ] Add settings option to enable/disable this feature
- [ ] Create list of common abbreviations:
  - Titles: `M.`, `Mme.`, `Mlle.`, `Dr.`, `Prof.`, `Pr.`
  - Others: `etc.`, `cf.`, `ex.`, `p.`, `pp.`, `vol.`, `n°`, `n°s`
- [ ] Implement detection of abbreviations followed by period and space
- [ ] Add non-breaking space after period
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases

#### 8. Spacing with Dashes (Low Priority)
**Impact:** Low | **Complexity:** Variable

- [ ] Add settings option to enable/disable this feature
- [ ] Distinguish em dashes (`—`) and en dashes (`–`) from hyphens (`-`)
- [ ] Implement non-breaking space before and after em/en dashes
- [ ] Avoid processing dashes in URLs, compound words, etc.
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases

#### 9. Spacing with Parentheses and Brackets (Low Priority)
**Impact:** Low | **Complexity:** Variable

- [ ] Add settings option to enable/disable this feature
- [ ] Implement non-breaking space before `(` and `[`
- [ ] Implement non-breaking space after `)` and `]`
- [ ] Add note in documentation indicating this rule is controversial
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases

#### 10. Spacing with Time (Future Consideration)
**Impact:** Low | **Complexity:** Moderate

- [ ] Add settings option to enable/disable this feature
- [ ] Implement detection of format "12 h 30" or "12h30"
- [ ] Add non-breaking spaces: `12&#160;h&#160;30`
- [ ] Handle variants: `12h`, `12h30`, `12 h 30 min`
- [ ] Avoid processing in HTML attributes, code, etc.
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases

**Example:** `12h30` → `12&#160;h&#160;30`

### Number Formatting

#### 11. Thousands Separator (Medium Priority)
**Impact:** Moderate | **Complexity:** Variable

- [ ] Add settings option to enable/disable this feature
- [ ] Implement detection of numbers with 4 or more digits
- [ ] Use thin non-breaking space (`&#8239;`) as separator
- [ ] Handle exceptions:
  - Do not apply to years (e.g., `2024` stays `2024`)
  - Do not apply to postal codes
  - Do not apply to phone numbers
  - Do not apply to numbers in URLs
  - Do not apply to HTML attributes
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases and exceptions

#### 12. Ordinal Numbers (Medium Priority)
**Impact:** Medium | **Complexity:** Moderate (regex)

- [ ] Add settings option to enable/disable this feature
- [ ] Implement conversion of ordinal numbers:
  - `1er`, `1ère` → `1<sup>er</sup>`, `1<sup>ère</sup>`
  - `2e`, `2ème`, `2nd`, `2nde` → `2<sup>e</sup>`, `2<sup>ème</sup>`, `2<sup>nd</sup>`, `2<sup>nde</sup>`
  - `3e`, `3ème` → `3<sup>e</sup>`, `3<sup>ème</sup>`
  - Handle numbers up to a certain threshold (e.g., 1-99) to avoid false positives
- [ ] Handle uppercase cases: `1er`, `1ER`, `1Er`
- [ ] Avoid processing in HTML attributes, code, URLs, etc.
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases and supported variants

**Example:** `The 1st of January is the 2nd day of the year.` → `The 1<sup>st</sup> of January is the 2<sup>nd</sup> day of the year.`

#### 13. Dimension Symbol (Multiplication) (Medium Priority)
**Impact:** Low | **Complexity:** Moderate (number detection)

- [ ] Add settings option to enable/disable this feature
- [ ] Implement replacement of `x` between numbers with `×` (multiplication symbol U+00D7)
- [ ] Detect patterns: number + space(s) + `x` + space(s) + number
- [ ] Handle cases with or without spaces: `12 x 123`, `12x123`, `12 x123`, `12x 123`
- [ ] Handle uppercase cases: `12 X 123` → `12 × 123`
- [ ] Avoid false positives:
  - Do not process in file dimensions (e.g., `1920x1080` for resolution)
  - Do not process in URLs, HTML attributes, code, etc.
  - Do not process if `x` is part of a word (e.g., `axe`, `exemple`)
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases and exceptions

**Example:** `A table 120 x 80 cm` → `A table 120 × 80 cm`

### Advanced Features

#### 14. Em Dashes for Dialogues (Future Consideration)
**Impact:** Low | **Complexity:** Moderate

- [ ] Add settings option to enable/disable this feature
- [ ] Detect dialogues (lines starting with a dash or after a line break)
- [ ] Convert hyphens to em dashes (`—`) for dialogues
- [ ] Add non-breaking space after em dash
- [ ] Avoid processing in lists, code, etc.
- [ ] Add tests to verify proper functionality
- [ ] Document edge cases

**Example:**
```
- Hello, he said.
→ — Hello, he said.
```

#### 15. Mathematical Symbols Spacing (Future Consideration)
**Impact:** Very Low | **Complexity:** Moderate

- [ ] Evaluate if this feature is relevant for a general WordPress plugin
- [ ] If yes, add option to enable/disable
- [ ] Handle symbols: `+`, `-`, `×`, `÷`, `=`, `≠`, `>`, `<`, `≥`, `≤`
- [ ] Add non-breaking spaces before and after these symbols
- [ ] Avoid processing in complex mathematical formulas (LaTeX, etc.)

**Note:** This feature is probably out of scope for a general plugin, but may be useful for scientific sites.

#### 16. Capital Letter Accentuation (Future Consideration)
**Impact:** Medium | **Complexity:** High (requires dictionary/contextual analysis)

- [ ] Evaluate technical feasibility
- [ ] Consider performance impact
- [ ] Determine if this feature is within plugin scope

**Note:** This rule is complex because it requires distinguishing capital letters that should be accented (e.g., "École", "À l'heure") from those that should not (foreign proper names, acronyms, etc.). May require a dictionary or advanced contextual analysis.

---

## Implementation Notes

### Suggested Options Structure

```php
$options = array(
    'narrow_space' => '1',           // Existing
    'special_characters' => '1',      // Existing (will include (TM) → ™)
    'ellipsis' => '1',                // New
    'curly_apostrophes' => '1',       // New
    'french_quotes' => '1',           // New
    'measurement_units' => '1',        // New
    'currency_symbols' => '1',        // New
    'abbreviations' => '0',           // New (disabled by default)
    'thousands_separator' => '0',     // New (disabled by default)
    'ordinal_numbers' => '0',         // New (disabled by default)
    'dimension' => '0',               // New (disabled by default)
    'dash_spacing' => '0',            // New (disabled by default)
    'parentheses_spacing' => '0',     // New (disabled by default)
);
```

### Key Considerations

1. **Performance:** Ensure that adding new rules does not slow down processing
2. **HTML Compatibility:** Continue to respect HTML tags and shortcodes
3. **Simplicity:** Maintain a simple and intuitive settings interface
4. **Testing:** Test each new rule on different content types
5. **Execution Order:** Define the order of rule application to avoid conflicts

### Recommended Execution Order

To avoid conflicts between rules, apply rules in this order:

1. **Character Replacements** (static):
   - `(c)` → `©`
   - `(r)` → `®`
   - `(TM)` → `™`
   - `...` → `…` (if enabled)
   - `'` → `'` (typographic apostrophe, if enabled)

2. **Quote Replacements**:
   - `" "` → `« »` (if enabled)

3. **Spacing with Abbreviations**:
   - `M. Dupont` → `M.&#160;Dupont` (if enabled)

4. **Spacing with Units and Symbols**:
   - Numbers + measurement units
   - Numbers + currency symbols
   - Numbers + percentages (already handled)

5. **Spacing with Punctuation**:
   - Before `;`, `:`, `!`, `?`, `%`, `»`
   - After `«`
   - After `…` (typographic ellipsis, if enabled)

6. **Number Formatting**:
   - Thousands separator (if enabled)
   - Ordinal numbers with `<sup>` (if enabled)
   - Dimension symbol `×` (if enabled)

7. **Spacing with Dashes**:
   - Em/en dashes (if enabled)

8. **Spacing with Parentheses/Brackets**:
   - Before `(` and `[`, after `)` and `]` (if enabled)

### Conflict Resolution

- [ ] **Ellipsis vs Spacing:** If ellipsis replacement (`...` → `…`) is enabled, do not apply spacing rule after ellipsis
- [ ] **Quotes vs Spacing:** If quote conversion is enabled, ensure spacing around French quotes is correct
- [ ] **Apostrophes vs Contractions:** Verify that typographic apostrophes do not break existing contractions
- [ ] **Execution Order:** Document and test execution order for each rule combination

---

## Testing & Validation

### Unit Tests

- [ ] Create unit test suite for each new feature
- [ ] Create regression tests to ensure new rules do not break existing ones
- [ ] Test with different content types:
  - [ ] Simple content (plain text)
  - [ ] Content with HTML
  - [ ] Content with shortcodes
  - [ ] Content with code (tags `<code>`, `<pre>`, etc.)
  - [ ] Content with URLs
  - [ ] Content with HTML attributes
  - [ ] RSS content
  - [ ] REST API content
- [ ] Test combinations of rules enabled simultaneously
- [ ] Test edge cases
- [ ] Validate performance with large content volumes

### Migration & Compatibility

- [ ] Create migration function for existing options if structure changes
- [ ] Ensure backward compatibility with previous versions
- [ ] Document option structure changes in changelog
- [ ] Test migration on existing installations

### General Improvements

- [ ] Optimize regex patterns to maintain good performance
- [ ] Update documentation (README.md, readme.txt) with new features
- [ ] Update translations (.po files) with new strings
- [ ] Verify compatibility with different WordPress contexts (RSS, REST API, etc.)
- [ ] Add examples in documentation for each new rule

### User Documentation

- [ ] Update README.md with new features
- [ ] Update readme.txt for WordPress.org
- [ ] Add concrete examples for each new rule
- [ ] Create detailed usage guide
- [ ] Document recommended and non-recommended use cases
- [ ] Add screenshots of updated settings page

---

## Identified Redundancies & Conflicts

### Resolved Redundancies

1. **Ellipsis/Spacing with Ellipsis:** 
   - **Original conflict:** Two separate entries (lines 101-106 and 125-135)
   - **Resolution:** Merged into single "Ellipsis" entry with note about spacing conflict

2. **Dash Spacing/Dialogue Dashes:**
   - **Original conflict:** Two separate entries (lines 85-91 and 305-320)
   - **Resolution:** Separated into "Spacing with Dashes" (existing em/en dashes) and "Em Dashes for Dialogues" (conversion feature)

### Notes

- All features are now organized by type (Character Replacements, Spacing Rules, Number Formatting, Advanced Features) rather than by priority/phase
- Priority levels are indicated within each feature entry
- Implementation notes consolidated into single section
- Testing and documentation tasks consolidated

# Security rules (WordPress plugin)

These are blocking in code review. Any change that violates them gets flagged.

## Escape every output

- Text → `esc_html()`
- HTML attributes → `esc_attr()`
- URLs (href, src, action) → `esc_url()`
- Rich content (WYSIWYG, RichText) → `wp_kses_post()`
- `<textarea>` content → `esc_textarea()`
- Inline JS → `esc_js()`

**Never** `echo $_GET['x']` or `echo $var`. Wrap or pre-escape.

## Sanitize every input

- Free text → `sanitize_text_field()`
- Email → `sanitize_email()`
- Integer → `absint()`
- Slug → `sanitize_key()` or `sanitize_title()`
- Rich content → `wp_kses_post()` or `wp_kses()` with a custom allowlist

Sanitize on save (settings page), escape on output (typography filters).

## Nonce every state-changing action

- Settings page (`options.php`) : passe déjà par `settings_fields()` /
  `register_setting()` — ne pas contourner avec un formulaire custom sans
  nonce.
- Toute action admin custom (dismiss notice, etc.) : `wp_nonce_field()` /
  `check_admin_referer()`, ou `wp_create_nonce()` / `check_ajax_referer()`
  côté AJAX.

## Prepare every SQL query

Ce plugin ne fait pas de requête SQL directe (`$wpdb`) au moment de ce
scaffold. Si une évolution en introduit une : `$wpdb->prepare()` avec
placeholders (`%s`, `%d`, `%f`), jamais d'interpolation de `$_GET`/`$_POST`
dans une chaîne de requête.

## Sortie affichée, jamais contenu réécrit

- Les règles typographiques (`french_typo_replace()` et filtres associés)
  s'appliquent **à l'affichage uniquement**. Ne jamais persister le contenu
  transformé en base — ça casserait la réversibilité et l'idempotence.

## Defense in depth

- `defined( 'ABSPATH' ) || die( ... );` en tête de chaque fichier PHP
  plugin.
- Fichiers sensibles bloqués par `.claude/settings.json`
  `permissions.deny` : `vendor/**`, `node_modules/**`, `wp-config*.php`,
  `dist/**`/`build/**`.

## Enforcement

- PHPCS (`WordPress-Extra`) attrape la plupart de ces points statiquement —
  `vendor/bin/phpcs --standard=WordPress-Extra --ignore=vendor/,tests/ .`
  avant de committer.
- Les scripts sous `tests/` couvrent l'idempotence et le comportement par
  locale — les lancer localement avant de déclarer un changement terminé.

# AGENTS.md — French Typo

Conventions du projet à respecter par tout agent (Claude Code, Cursor, Codex, etc.) ou contributeur humain. Ce fichier est la source unique ; il est exclu de la distribution WordPress.org via [`.distignore`](.distignore).

## Architecture

- **Monolithique** : presque tout le runtime vit dans [`french-typo.php`](french-typo.php). Pas d'autoload de code plugin. Ne pas éclater en sous-fichiers sans raison forte.
- Préfixe `french_typo_*` pour toutes les fonctions, hooks et clés d'options.
- Détails dans [`docs/architecture.md`](docs/architecture.md), [`docs/configuration.md`](docs/configuration.md), [`docs/faq.md`](docs/faq.md) — aligner code et nouveaux tests sur ces documents.

## Comportement typographique

- Règles appliquées **à l'affichage** (filtres), jamais en réécrivant le contenu stocké.
- Périmètre : NBSP avant `; : ! ? % « »`, abréviations ordinales optionnelles (`1ère` → `1re`, etc.), `(c)` / `(r)` / `(tm)` → ©/®/™.
- **Laisser inchangés** (par design) : ordinaux anglais (`1st`, `2nd`), `1ème` non standard.
- **Zones brutes** : typographie désactivée dans `<pre>`, `<code>`, `<script>`, `<style>`, `<textarea>` via stack imbriquée. Verse Gutenberg reste typographique sauf si `wp-block-code` est aussi sur le même `<pre>`. Ne pas « corriger » les littéraux dans ces régions.

## PHP / WordPress

- `defined( 'ABSPATH' ) || die( ... );` en tête de chaque fichier PHP plugin.
- **Coding standard** : CI exécute PHPCS `WordPress-Extra` sur le PHP du projet (hors `vendor/` et `tests/`). `composer install` puis `vendor/bin/phpcs`. Ne pas élargir les `phpcs:ignore` sans raison.
- **Sécurité** : sanitize on save, escape on output, nonces et capabilities. Pas de `$_GET`/`$_POST` brut.
- **Performance** : préserver les patterns existants (caches statiques, early returns, style d'enregistrement des hooks) — voir `docs/architecture.md` avant tout refactor.

## i18n

- **Text domain** : `french-typo` (doit matcher le `Text Domain:` du header). Toujours `__()`, `_e()`, `esc_html__()`, etc. avec ce domaine pour les chaînes user-facing.
- **`.pot` jamais édité à la main.** Le repo ne suit **que** [`languages/french-typo.pot`](languages/french-typo.pot). Les locales (`fr_FR`, etc.) vivent sur [translate.wordpress.org](https://translate.wordpress.org/), pas comme `.po`/`.mo` commités.
- Après modification de chaînes traduisibles : régénérer avec `wp i18n make-pot` (voir [`docs/development.md`](docs/development.md)). La CI échoue (`i18n-pot` job) si le POT n'est pas à jour.

## Tests

- **Runner** : `php` CLI direct (pas de suite PHPUnit complète en CI pour l'instant). Depuis la racine, la CI lance :
  - `php tests/french-typo-replace-test.php`
  - `php tests/french-typo-replace-ordinal-off-test.php`
  - `php tests/french-typo-replace-ordinal-only-test.php`
- `tests/bootstrap.php` charge les stubs et options ; `tests/wp-html-split-wpstub.php` fournit les helpers WP HTML minimaux.
- Lors d'une modification de `french_typo_replace()` ou des filtres associés : ajouter / étendre les scénarios dans le fichier de test approprié, et lancer les trois scripts localement avant de pousser.
- `tests/` est exclu du PHPCS principal — garder les fichiers lisibles, `phpcs:ignore` minimal seulement où les stubs l'exigent.

## Versions & release

- Cohérence à maintenir pour un release : `Version:` (header `french-typo.php`), `FRENCH_TYPO_VERSION` (constante), `Stable tag:` (`readme.txt`). La CI vérifie le ZIP de déploiement.
- **`readme.txt`** : doit garder ses headers requis (`Contributors`, `Tags`, `Requires at least`, `Tested up to`, `Requires PHP`, `Stable tag`, `License`, …) et les sections `== Description ==`, `== Installation ==`, `== Frequently Asked Questions ==`, `== Changelog ==`. Validation : `.github/workflows/ci.yml` job `validate-readme`.

## Changelog

- **`CHANGELOG.md` est la source unique de vérité.** Format [Keep a Changelog](https://keepachangelog.com/).
- **Versions publiées = immuables.** Une fois qu'une section `## [X.Y.Z]` est sortie (sur `main`, un tag, ou WordPress.org), ne pas modifier ses bullets, lignes de compatibilité, ordre ni formulation. Tout ajout va dans `## [Unreleased]` ou une **nouvelle** section `## [new.version]`. Exception : correction factuelle indiscutable (URL cassée, numéro d'issue erroné, faute sur un nom propre).
- **`readme.txt`** section `== Changelog ==` : **pas de changelog parallèle**. Mirror tendu de `CHANGELOG.md` pour la visibilité WordPress.org uniquement. Lien vers `CHANGELOG.md` sur GitHub pour l'historique complet.
- **Au plus les deux versions les plus récentes** dans `readme.txt` (plus récente en premier). En cas de divergence avec `CHANGELOG.md` : **`CHANGELOG.md` gagne**, on corrige `readme.txt`.

## Distribution

- `.distignore` exclut `.git`, `.github`, `.cursor/`, `AGENTS.md` (à ajouter), docs, vendor, tests, etc. du ZIP/SVN WordPress.org. Les règles d'agent ne sont jamais shippées au public.

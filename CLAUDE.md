# CLAUDE.md — French Typo

Conventions du projet à respecter par tout agent (Claude Code, ou tout autre outil lisant CLAUDE.md) ou contributeur humain. Ce fichier est la source unique ; il est exclu de la distribution WordPress.org via [`.distignore`](.distignore).

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
- **`.pot` jamais édité à la main.** Régénération obligatoire via `wp i18n make-pot . languages/french-typo.pot --slug=french-typo --domain=french-typo --exclude=vendor,.git` à **chaque commit qui touche aux chaînes traduisibles** (PHP `__()`, `_e()`, commentaires `translators:`, etc.). La CI a un job `i18n POT up to date` qui régénère et diff — il échoue le PR si le POT du repo dévie. L'outillage local doit suivre la version wp-cli utilisée par la CI (`brew install wp-cli` → 2.12.0).
- **Locale `.po`** : le repo embarque `languages/french-typo-fr_FR.po` comme traduction de référence. La traduction suit le skill [wp-fr-typo](https://github.com/thierrypigot/wp-fr-typo) (glossaire Polyglots FR + règles typographiques officielles). Le `.mo` compilé est ignoré par Git (`languages/*.mo` dans [`.gitignore`](.gitignore)) — régénération locale via `msgfmt languages/french-typo-fr_FR.po -o languages/french-typo-fr_FR.mo`. `.po` et `.mo` restent **exclus du ZIP WordPress.org** via [`.distignore`](.distignore) ; les language packs officiels sont servis par translate.wordpress.org.

## Tests

- **Runner** : PHPUnit 9.6 (`vendor/bin/phpunit`, config `phpunit.xml.dist`). `composer test` le lance ; `composer ci` enchaîne PHPCS, PHPStan et PHPUnit comme la CI. Un fichier `tests/*Test.php` est découvert automatiquement : plus aucune commande à ajouter dans `ci.yml`.
- `tests/bootstrap.php` charge les stubs WordPress et le plugin. `add_action`/`add_filter` y enregistrent réellement les callbacks (`$GLOBALS['french_typo_test_hooks']`), ce qui permet à `HooksTest` de vérifier chaque hook, sa priorité et son nombre d'arguments. Les options, la locale et Polylang se pilotent par globales `french_typo_test_*` (voir `FrenchTypoTestCase`, qui les remet à zéro avant et après chaque test). Les stubs Polylang (`tests/polylang-stub.php`) ne se chargent que dans `PolylangTest`, exécuté dans un processus séparé.
- La CI exécute PHPUnit sur PHP 7.4 à 8.5 : c'est ce qui justifie `Requires PHP: 7.4`. Ne pas relever ce plancher sans décision explicite.
- Lors d'une modification de `french_typo_replace()` ou des filtres associés : ajouter / étendre les scénarios dans le test approprié et lancer `composer test` avant de pousser. Un nouveau hook ajouté à `french_typo_hooks()` doit apparaître dans `HooksTest`.
- **PHPStan** (`composer stan`, niveau 5, `phpstan.neon.dist`) : la baseline `phpstan-baseline.neon` ne doit que diminuer. Ne pas y ajouter une erreur nouvelle, la corriger.
- `tests/` est exclu du PHPCS principal — garder les fichiers lisibles.

## Versions & release

- Cohérence à maintenir pour un release : `Version:` (header `french-typo.php`), `FRENCH_TYPO_VERSION` (constante), `Stable tag:` (`readme.txt`). La CI vérifie le ZIP de déploiement.
- **`readme.txt`** : doit garder ses headers requis (`Contributors`, `Tags`, `Requires at least`, `Tested up to`, `Requires PHP`, `Stable tag`, `License`, …) et les sections `== Description ==`, `== Installation ==`, `== Frequently Asked Questions ==`, `== Changelog ==`. Validation : `.github/workflows/ci.yml` job `validate-readme`.

## Changelog

- **`CHANGELOG.md` est la source unique de vérité.** Format [Keep a Changelog](https://keepachangelog.com/).
- **Versions publiées = immuables.** Une fois qu'une section `## [X.Y.Z]` est sortie (sur `main`, un tag, ou WordPress.org), ne pas modifier ses bullets, lignes de compatibilité, ordre ni formulation. Tout ajout va dans `## [Unreleased]` ou une **nouvelle** section `## [new.version]`. Exception : correction factuelle indiscutable (URL cassée, numéro d'issue erroné, faute sur un nom propre).
- **`readme.txt`** section `== Changelog ==` : **pas de changelog parallèle**. Mirror tendu de `CHANGELOG.md` pour la visibilité WordPress.org uniquement. Lien vers `CHANGELOG.md` sur GitHub pour l'historique complet.
- **Au plus les deux versions les plus récentes** dans `readme.txt` (plus récente en premier). En cas de divergence avec `CHANGELOG.md` : **`CHANGELOG.md` gagne**, on corrige `readme.txt`.

## Distribution

- `.distignore` exclut `.git`, `.github`, `.cursor/`, `CLAUDE.md`, docs, vendor, tests, etc. du ZIP/SVN WordPress.org. Les règles d'agent ne sont jamais shippées au public.

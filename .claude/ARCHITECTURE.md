# Architecture

Comment ce dépôt est organisé et pourquoi. Pour les commandes du quotidien,
voir `.claude/CLAUDE.md`.

## Repository layout

```text
.
├── .claude/                     # Config Claude Code
│   ├── CLAUDE.md                # Contexte de session (auto-chargé)
│   ├── ARCHITECTURE.md          # Ce fichier
│   ├── rules/                   # Toujours chargées : security, a11y
│   ├── hooks/                   # session-banner.sh, lint-edited.sh
│   ├── skills/                  # À la demande — aucune pour l'instant
│   └── settings.json            # Permissions allow/deny + wiring des hooks
├── AGENTS.md                    # Conventions détaillées (miroir historique,
│                                 # voir « Note historique » ci-dessous)
├── french-typo.php              # Runtime du plugin — quasi tout vit ici
├── admin.css                    # Styles de la page de réglages admin
├── docs/                        # architecture.md, configuration.md, faq.md, api.md, …
├── tests/                       # Scripts PHP autonomes (pas de suite PHPUnit)
├── languages/                   # french-typo.pot + french-typo-fr_FR.po/.mo (référence)
├── .wordpress-org/              # Assets de la fiche WordPress.org (screenshots, banner…)
├── .github/workflows/           # CI (lint, WPCS, POT, readme, release ZIP, wiki sync)
├── .distignore                  # Exclusions du ZIP/SVN WordPress.org
└── readme.txt                   # Fiche WordPress.org (miroir tendu de CHANGELOG.md)
```

## `french-typo.php` — monolithique

- Presque tout le runtime vit dans ce fichier unique. Pas d'autoload de code
  plugin, pas de découpage en `inc/`/`includes/`. Ne pas éclater en
  sous-fichiers sans raison forte (voir `docs/architecture.md` pour le détail
  des fonctions et hooks).
- Préfixe `french_typo_` pour toutes les fonctions, hooks et clés d'options.
- `defined( 'ABSPATH' ) || die( ... );` en tête de chaque fichier PHP plugin.

## Comportement typographique

- Règles appliquées **à l'affichage** (filtres), jamais en réécrivant le
  contenu stocké en base.
- Périmètre : NBSP avant `; : ! ? % « »`, abréviations ordinales optionnelles
  (`1ère` → `1re`, etc.), `(c)` / `(r)` / `(tm)` → ©/®/™.
- **Laissés inchangés par design** : ordinaux anglais (`1st`, `2nd`), `1ème`
  non standard.
- **Zones brutes** : typographie désactivée dans `<pre>`, `<code>`,
  `<script>`, `<style>`, `<textarea>` via une pile imbriquée. Verse Gutenberg
  reste typographique sauf si `wp-block-code` est aussi présent sur le même
  `<pre>`. Ne pas « corriger » les littéraux dans ces régions.

## i18n

- Text domain : `french-typo` (doit matcher le header `Text Domain:`).
  Toujours `__()`, `_e()`, `esc_html__()`, etc. avec ce domaine.
- **`.pot` jamais édité à la main.** Régénération obligatoire via
  `wp i18n make-pot . languages/french-typo.pot --slug=french-typo --domain=french-typo --exclude=vendor,.git`
  à chaque commit touchant des chaînes traduisibles. Le job CI
  `i18n POT up to date` régénère et diffe — il échoue le PR si le POT dévie.
- `languages/french-typo-fr_FR.po` est la traduction de référence (suit le
  skill `wp-fr-typo`). Le `.mo` compilé est ignoré par Git, régénéré
  localement via `msgfmt`. `.po`/`.pot`/`.mo` restent hors du ZIP
  WordPress.org (`.distignore`) ; les language packs officiels sont servis
  par translate.wordpress.org.

## Design constraints à préserver

- Filtres, jamais de réécriture du contenu stocké — ne pas transformer ça en
  sanitize-on-save sans discussion préalable (casserait la réversibilité).
- Caches statiques et early returns existants dans `french-typo.php` —
  performance déjà optimisée pour tourner sur `the_content` à chaque
  requête ; ne pas les retirer lors d'un refactor sans mesurer l'impact.
- Zones brutes (`<pre>`/`<code>`/`<script>`/`<style>`/`<textarea>`) : la pile
  imbriquée est la seule protection contre la corruption de littéraux — tout
  changement ici doit repasser par les tests d'idempotence.

## Tests

- Runner : scripts PHP autonomes exécutés en CLI directe (pas de suite
  PHPUnit complète pour l'instant). `tests/bootstrap.php` charge les stubs et
  options ; `tests/wp-html-split-wpstub.php` fournit les helpers WP HTML
  minimaux.
- Fichiers : `french-typo-replace-test.php`,
  `french-typo-replace-ordinal-off-test.php`,
  `french-typo-replace-ordinal-only-test.php`,
  `french-typo-polylang-test.php`, `french-typo-site-locale-test.php`,
  `french-typo-idempotence-test.php`.
- Toute modification de `french_typo_replace()` ou des filtres associés :
  étendre le scénario correspondant et lancer tous les scripts localement
  avant de pousser.
- `tests/` est exclu du PHPCS principal — garder les fichiers lisibles,
  `phpcs:ignore` minimal seulement où les stubs l'exigent.

## Versions & release

- Cohérence à maintenir : `Version:` (header `french-typo.php`),
  `FRENCH_TYPO_VERSION` (constante), `Stable tag:` (`readme.txt`). La CI
  vérifie le ZIP de déploiement (`create-release-zip.yml`).
- `readme.txt` doit garder ses headers requis et ses sections standard
  (`== Description ==`, `== Installation ==`, `== FAQ ==`, `== Changelog ==`)
  — validé par le job `validate-readme`.

## Changelog

- `CHANGELOG.md` est la source unique de vérité (format Keep a Changelog).
  Versions publiées immuables — tout ajout va dans `## [Unreleased]` ou une
  nouvelle section.
- `readme.txt` section `== Changelog ==` : mirror tendu de `CHANGELOG.md`
  pour la visibilité WordPress.org, au plus les deux versions les plus
  récentes. En cas de divergence, `CHANGELOG.md` gagne.

## CI/CD (`.github/workflows/`)

| Workflow | Rôle |
| --- | --- |
| `ci.yml` | `lint-php` (syntaxe + tests PHP), `wpcs` (PHPCS WordPress-Extra), `validate-readme`, `i18n-pot` (POT à jour) |
| `release-drafter.yml` | Prépare les notes de release à chaque push/PR sur `main` |
| `create-release-zip.yml` | Construit le ZIP de distribution sur tag/release et déploie sur WordPress.org |
| `deploy-wordpress-org.yml` | Déploiement manuel (`workflow_dispatch`) vers WordPress.org |
| `sync-docs-to-wiki.yml` | Synchronise `docs/` vers le wiki GitHub |

## Distribution

- `.distignore` exclut `.git`, `.github`, `.cursor/`, `.claude/`,
  `AGENTS.md`, `docs/`, `vendor/`, `tests/`, etc. du ZIP/SVN WordPress.org.
  Les règles d'agent ne sont jamais shippées au public.

## Note historique — `AGENTS.md` vs `.claude/`

Ce dépôt a un `AGENTS.md` à la racine (conventions partagées Claude Code /
Cursor / Codex), avec une PR ouverte (#20) qui le renomme en `CLAUDE.md` à
la racine — non fusionnée au moment où cette structure `.claude/` a été
ajoutée. Le pattern wpfr-2026 veut `CLAUDE.md` **dans** `.claude/`, pas à la
racine, d'où une duplication temporaire de contenu entre `AGENTS.md`
(racine) et `.claude/CLAUDE.md` + `.claude/ARCHITECTURE.md`. À trancher une
fois PR #20 mergée : soit `AGENTS.md` racine devient un pointeur court vers
`.claude/`, soit il est supprimé si les autres outils (Cursor, Codex)
acceptent de lire `.claude/CLAUDE.md` directement.

## Known pitfalls

- Le job `i18n-pot` échoue si l'outillage local wp-cli diverge de la version
  utilisée en CI (2.12.0) — vérifier la version avant de régénérer le POT
  localement.
- `tests/` n'a pas de runner PHPUnit — un `composer test` n'existe pas ; les
  scripts se lancent individuellement en CLI (voir `.claude/CLAUDE.md`).

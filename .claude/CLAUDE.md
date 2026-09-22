# CLAUDE.md

This file provides guidance to Claude Code when working with code in this repository.

## What this is

**French Typo** — plugin WordPress qui applique automatiquement des règles
de typographie française à l'affichage du contenu : espaces insécables
avant `; : ! ? % « »`, abréviations ordinales optionnelles (`1ère` → `1re`),
remplacement de `(c)`/`(r)`/`(tm)` par ©/®/™. Distribué sur WordPress.org.

## Stack

- WordPress 6.0+, PHP 7.4+.
- Pas de build JS (pas de `package.json`, pas de bundler).
- Monolithique : quasi tout le runtime dans `french-typo.php`.

## Commands

```bash
composer install                                                       # deps dev (phpcs)
vendor/bin/phpcs --standard=WordPress-Extra --ignore=vendor/,tests/ .  # lint WPCS
php tests/french-typo-replace-test.php                                 # + les autres scripts sous tests/
wp i18n make-pot . languages/french-typo.pot --slug=french-typo --domain=french-typo --exclude=vendor,.git
```

Lancer les scripts de `tests/` un par un (pas de suite PHPUnit) avant de
déclarer un changement sur `french_typo_replace()` terminé.

## Architecture

- Carte complète du repo, layout, contraintes de design à préserver : voir
  `.claude/ARCHITECTURE.md`.
- Préfixe fonctions/hooks/options : `french_typo_` — partout dans
  `french-typo.php`.
- Text-domain : `french-typo`.

## Files never to modify

- `languages/*.pot` à la main — toujours régénéré via `wp i18n make-pot`.
- Sections déjà publiées de `CHANGELOG.md` (immuables une fois sorties).
- `vendor/**` — géré par composer.
- `readme.txt` : ne pas dupliquer un changelog parallèle, `CHANGELOG.md`
  fait foi en cas de divergence.

## Git workflow

Default branch : `main`. Repo public, solo (Jason) — PR-as-you-go dès qu'un
chantier produit un commit destiné à devenir une PR (voir le CLAUDE.md
global pour la règle complète). Pas de règle issue-first ici (repo public).

## Pointers

- **Always loaded** : `.claude/rules/security.md`, `.claude/rules/a11y.md`
  — bloquantes en review.
- **Architecture** : `.claude/ARCHITECTURE.md`.
- **Skills** (à la demande) : aucune pour l'instant — en ajouter ici au fur
  et à mesure qu'elles sont écrites, sans dupliquer leur contenu dans ce
  fichier.
- **Historique** : `CLAUDE.md` (racine, ex-`AGENTS.md`) porte encore les
  conventions détaillées héritées (Cursor/Codex) — voir la note dans
  `.claude/ARCHITECTURE.md` sur la duplication avec ce fichier.

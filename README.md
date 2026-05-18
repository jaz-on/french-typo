# French Typo

> Typographie française automatique pour WordPress. [English below](#english).

French Typo applique les règles typographiques françaises **à l'affichage** de votre contenu. Votre texte reste exactement tel que vous l'avez écrit dans l'éditeur — seul le rendu visible est enrichi.

## Ce que fait l'extension

- Ajoute des espaces insécables avant `: ; ! ? %` et autour de `« »`
- Remplace `(c)` par `©`, `(r)` par `®`, `(tm)` / `(TM)` par `™`
- Convertit (en option) les ordinaux français : `1ère` → `1re`, `3ème` → `3e`, `n-ième` → `nième`
- S'applique aux articles, pages, widgets, menus, commentaires, RSS, API REST, champs ACF / Meta Box, et au SEO (Yoast, Rank Math, SEOPress)
- Sur un site multilingue, peut ne traiter que les contenus français (Polylang et WPML détectés automatiquement)

## Ce qu'elle ne fait pas

- Toucher au HTML brut, au code, aux scripts, aux styles, ou aux `<textarea>`
- Modifier le contenu en base

## Installation

1. Téléchargez et décompressez dans `/wp-content/plugins/french-typo` (ou installez via le menu Extensions).
2. Activez l'extension.
3. Configurez dans **Réglages > French Typo**.

> **Note** : Compatible avec Git Updater pour les mises à jour automatiques depuis GitHub.

## Questions fréquentes

**L'extension modifie-t-elle mon contenu ?**

Non. Le texte enregistré en base n'est jamais altéré. French Typo intercepte la sortie juste avant l'affichage et ajoute les règles typographiques à cet endroit. Désactivez l'extension et votre contenu revient à l'identique.

**Espaces insécables normales ou fines ?**

Normales (`&nbsp;`) : compatibilité maximale. Fines (`&#8239;`) : plus juste typographiquement pour `: ;`, mais peut s'afficher trop étroit ou comme glyphe manquant sur certaines polices anciennes ou navigateurs.

**Puis-je limiter les règles aux contenus français uniquement ?**

Oui. Dans **Réglages > French Typo > Restriction par langue**, choisissez **Auto** (locales `fr_*`) ou **Personnalisé** (sélectionner des locales précises). Polylang et WPML sont détectés par post ; sinon la locale du site est utilisée.

**La typographie s'applique-t-elle dans le code, les scripts ou les `<textarea>` ?**

Non. La typo n'est pas appliquée à l'intérieur de `<script>`, `<style>`, `<pre>`, `<code>` (imbriqués), `<textarea>`, ni au CSS embarqué (par exemple SVG inline). Le bloc Vers de Gutenberg reste traité sauf s'il est aussi bloc Code.

**Mon thème ou éditeur insère déjà des insécables. L'extension va-t-elle les doubler ?**

Non depuis la 1.2.2. Toutes les variantes (`&nbsp;`, `&#160;`, `&#xA0;`, `&#8239;`, `&#x202F;`, U+00A0 / U+202F littéraux) sont détectées et fusionnées en une seule.

## Auteur et crédits

**Jason Rouet** — [jasonrouet.com](https://jasonrouet.com) | [bonjour@jasonrouet.com](mailto:bonjour@jasonrouet.com) | [WordPress.org](https://profiles.wordpress.org/jaz_on/)

Vous pouvez soutenir ce projet sur [Buy Me a Coffee](https://buymeacoffee.com/jasonrouet) ou [GitHub Sponsors](https://github.com/sponsors/jaz-on).

Cette extension est un fork de **French Typo** créé par Gilles Marchand (master_shiva), entièrement refondu depuis mars 2024 avec l'aide de [Jean-Baptiste Audras](https://profiles.wordpress.org/audrasjb/).
Inspiré par [TypoFR](https://wordpress.org/plugins/typofr/), [Orthotypo](https://wordpress.org/plugins/orthotypo-orthotypographie-automatique/) et [Consistency](https://wordpress.org/plugins/consistency/).

## Documentation

* [Changelog](CHANGELOG.md) — Historique des versions
* [Documentation détaillée](docs/README.md) — Tutoriels d'utilisation et documentation technique

## Licence

[GPLv2 ou ultérieure](https://www.gnu.org/licenses/gpl-2.0.html)

---

<a name="english"></a>
## English

French Typo applies French typography rules to your content **as it is displayed**. Your text stays exactly as you wrote it in the editor — only the rendered output is enriched.

### What it does

- Adds non-breaking spaces before `: ; ! ? %` and around `« »`
- Replaces `(c)` with `©`, `(r)` with `®`, `(tm)` / `(TM)` with `™`
- Optionally normalizes French ordinals: `1ère` → `1re`, `3ème` → `3e`, `n-ième` → `nième`
- Works across posts, pages, widgets, menus, comments, RSS, REST, ACF / Meta Box fields, and SEO output (Yoast, Rank Math, SEOPress)
- On multilingual sites, can apply rules to French content only (Polylang and WPML auto-detected)

### What it does not do

- Touch your raw HTML, code blocks, scripts, styles, or `<textarea>` content
- Modify what's stored in the database

### Installation

1. Download and extract to `/wp-content/plugins/french-typo` (or install through the WordPress plugins screen).
2. Activate the plugin.
3. Configure in **Settings > French Typo**.

### Frequently Asked Questions

**Does this plugin modify my content?**

No. The text saved in the database is never altered. French Typo intercepts the output just before display and adds the typography rules there. Deactivate the plugin and your content comes back unchanged.

**Regular or thin non-breaking spaces?**

Regular (`&nbsp;`) is universally supported. Thin (`&#8239;`) is typographically purer for `: ;` but may render too narrow or as a missing glyph on older fonts and browsers.

**Can I limit rules to French content only?**

Yes. In **Settings > French Typo > Language restriction**, choose **Auto** to apply only to `fr_*` locales, or **Custom** to pick specific locales. Polylang and WPML are detected per post; otherwise the site locale is used.

**Will it run inside code blocks, scripts, or `<textarea>`?**

No. Typography is skipped inside `<script>`, `<style>`, `<pre>`, `<code>` (nested), `<textarea>`, and embedded CSS (e.g. inline SVG). Gutenberg's Verse block stays typographic unless it is also a Code block.

**My theme or editor already inserts non-breaking spaces. Will French Typo duplicate them?**

No (since 1.2.2). All non-breaking space variants — `&nbsp;`, `&#160;`, `&#xA0;`, `&#8239;`, `&#x202F;`, and literal U+00A0 / U+202F — are detected and collapsed to a single canonical entity.

### Author & Credits

**Jason Rouet** — [jasonrouet.com](https://jasonrouet.com) | [bonjour@jasonrouet.com](mailto:bonjour@jasonrouet.com) | [WordPress.org](https://profiles.wordpress.org/jaz_on/)

You can support this project on [Buy Me a Coffee](https://buymeacoffee.com/jasonrouet) or [GitHub Sponsors](https://github.com/sponsors/jaz-on).

This plugin is a fork of **French Typo** created by Gilles Marchand (master_shiva), completely rebuilt since March 2024 with the help of [Jean-Baptiste Audras](https://profiles.wordpress.org/audrasjb/). Inspired by [TypoFR](https://wordpress.org/plugins/typofr/), [Orthotypo](https://wordpress.org/plugins/orthotypo-orthotypographie-automatique/), and [Consistency](https://wordpress.org/plugins/consistency/).

### Documentation

* [Changelog](CHANGELOG.md) — Version history
* [Developer documentation](docs/README.md) — Detailed technical documentation

### License

[GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)

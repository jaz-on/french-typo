# French Typo (typographie française)

> <small>*English? [See section below](#english)*</small>

Extension WordPress qui applique automatiquement les règles typographiques françaises aux contenus que vous publiez sur un site propulsé par [WordPress](http://fr.wordpress.org/).

Le plugin peut ajouter des espaces insécables avant la ponctuation (`;`, `:`, `!`, `?`, `%`, `«`, `»`) : choisissez un mode normal ou fin dans `Réglages > French Typo` et enregistrez ; sans ce choix, la ponctuation n'est pas espacée. Il remplace aussi `(c)` par `©` et `(r)` par `®`.

Les règles couvrent articles, pages, extraits, taxonomies, archives, commentaires, widgets, menus, RSS, API REST, champs personnalisés, fil d'Ariane et SEO. La plupart des zones s'activent ou se désactivent au cas par cas dans les réglages. Les titres et méta SEO / balises sociales produits par Yoast SEO, Rank Math ou SEOPress ne suivent pas les mêmes cases que le titre ou le contenu d'article ; le fil d'Ariane SEO a une option dédiée.

> **Note** : Compatible avec Git Updater pour les mises à jour automatiques depuis GitHub.

## Installation

1. Téléchargez et décompressez le plugin dans `/wp-content/plugins/french-typo`.
2. Activez l'extension depuis le menu Extensions.
3. Configurez les options dans `Réglages > French Typo`.

## Questions fréquentes

**L'extension modifie-t-elle mon contenu existant ?**

Non. Les règles typographiques sont appliquées à la volée lors de l'affichage, sans modifier le contenu dans la base de données.

**Quelle est la différence entre espaces insécables normales et fines ?**

Les espaces normales (`&nbsp;`) sont standards et empêchent les retours à la ligne. Les espaces fines (`&#8239;`) sont plus étroites et peuvent ne pas s'afficher correctement selon la fonte ou le navigateur.

**Puis-je désactiver certaines fonctionnalités ?**

Oui. Vous pouvez désactiver les espaces insécables ou les remplacements de caractères, et choisir précisément quelles zones de contenu doivent être traitées (sauf sorties SEO des extensions citées ci-dessus, qui ne sont pas liées aux mêmes cases que le contenu affiché).

## Auteur et crédits

**Jason Rouet** — [jasonrouet.com](https://jasonrouet.com) | [bonjour@jasonrouet.com](mailto:bonjour@jasonrouet.com) | [WordPress.org](https://profiles.wordpress.org/jaz_on/)

Vous pouvez soutenir ce projet sur [Ko-fi](https://ko-fi.com/jasonrouet) ou [GitHub Sponsors](https://github.com/sponsors/jaz-on).

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

WordPress plugin that automatically applies French typography rules to your content.

The plugin can add non-breaking spaces before punctuation (`;`, `:`, `!`, `?`, `%`, `«`, `»`): pick regular or thin in `Settings > French Typo` and save; until you do, punctuation is not spaced. It also replaces `(c)` with `©` and `(r)` with `®`.

Rules cover posts, pages, excerpts, taxonomies, archives, comments, widgets, menus, RSS, REST API, custom fields, breadcrumbs, and SEO. Most areas can be toggled in settings. SEO titles, meta descriptions, and social tags from Yoast SEO, Rank Math, or SEOPress are not controlled by the same switches as visible post title and content; SEO breadcrumbs have their own setting.

## Installation

1. Download and extract the plugin to `/wp-content/plugins/french-typo`.
2. Activate the plugin from the Plugins menu.
3. Configure options in `Settings > French Typo`.

## Frequently Asked Questions

**Does this plugin modify existing content?**

No. Typography rules are applied on-the-fly when content is displayed, without modifying the original content in the database.

**What's the difference between regular and thin non-breaking spaces?**

Regular spaces (`&nbsp;`) are standard and prevent line breaks. Thin spaces (`&#8239;`) are narrower and may not display correctly depending on the font or browser.

**Can I disable certain features?**

Yes. You can disable non-breaking spaces or character replacements, and choose which content areas to process (SEO plugin title/meta/social output is separate from those toggles, as above).

## Author & Credits

**Jason Rouet** — [jasonrouet.com](https://jasonrouet.com) | [bonjour@jasonrouet.com](mailto:bonjour@jasonrouet.com) | [WordPress.org](https://profiles.wordpress.org/jaz_on/)

You can support this project on [Ko-fi](https://ko-fi.com/jasonrouet) or [GitHub Sponsors](https://github.com/sponsors/jaz-on).

This plugin is a fork of **French Typo** created by Gilles Marchand (master_shiva), completely rebuilt since March 2024 with the help of [Jean-Baptiste Audras](https://profiles.wordpress.org/audrasjb/). Inspired by [TypoFR](https://wordpress.org/plugins/typofr/), [Orthotypo](https://wordpress.org/plugins/orthotypo-orthotypographie-automatique/), and [Consistency](https://wordpress.org/plugins/consistency/).

## Documentation

* [Changelog](CHANGELOG.md) — Version history
* [Developer documentation](docs/README.md) — Detailed technical documentation

## License

[GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)

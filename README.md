# French Typo (typographie française)

> <small>*English? [See section below](#english)*</small>

Extension WordPress qui permet d'appliquer des règles typographiques de la langue française aux contenus que vous publiez sur un site propulsé par [WordPress](http://fr.wordpress.org/).

## Fonctionnalités

### Espaces insécables

Les [espaces insécables](http://fr.wikipedia.org/wiki/Espace_ins%C3%A9cable) sont gérées automatiquement pour les caractères `;`, `:`, `!`, `?`, `%`, `«` et `»`. Vous pouvez choisir entre :
* Des espaces insécables « normaux » (entité HTML `&nbsp;` ou `&#160;`)
* Des espaces fines insécables (entité HTML `&#8239;`)

### Caractères spéciaux

Les caractères `(c)` et `(r)` sont remplacés automatiquement par `©` et `®`.

## Installation

1. Téléchargez la dernière version de Typographie française.
2. Décompressez le contenu du fichier `zip` et ajoutez le dossier `french-typo` dans le répertoire `/wp-content/plugins/` (ou `/wp-content/mu-plugins/` si vous voulez la rendre obligatoire par défaut sur votre site par exemple).
3. Activez l'extension.
4. Configurez l'extension comme vous le désirez dans le menu `Réglages > Réglages typographiques` (`Settings > French Typo`) à partir du back-office de WordPress.

## Questions fréquentes

### Que fait ce plugin ?

Ce plugin applique automatiquement les règles typographiques françaises à vos contenus WordPress, notamment les espaces insécables avant les signes de ponctuation et le remplacement de caractères spéciaux.

### Quels signes de ponctuation sont gérés ?

Le plugin gère : `;`, `:`, `!`, `?`, `%`, `«` et `»`.

### Quelle est la différence entre les espaces insécables normaux et fins ?

Les espaces insécables normaux (`&nbsp;` / `&#160;`) sont des espaces standards qui empêchent les retours à la ligne. Les espaces fines insécables (`&#8239;`) sont des espaces plus étroites qui peuvent ne pas s'afficher correctement selon la fonte, le navigateur et le système d'exploitation utilisés.

### Ce plugin modifie-t-il le contenu existant ?

Non, le plugin applique les règles typographiques à la volée lors de l'affichage du contenu, sans modifier le contenu original dans la base de données.

### Puis-je désactiver certaines fonctionnalités ?

Oui, vous pouvez désactiver indépendamment les espaces insécables ou les remplacements de caractères spéciaux dans les réglages du plugin.

## Auteur et sponsoring

**Jason Rouet**

* Site internet: [jasonrouet.com](https://jasonrouet.com)
* E-mail: [bonjour@jasonrouet.com](mailto:bonjour@jasonrouet.com)
* Profil WordPress.org: [profiles.wordpress.org/jaz_on/](https://profiles.wordpress.org/jaz_on/)

Vous pouvez m'aider à financer mon travail opensource sur [Ko-fi](https://ko-fi.com/jasonrouet) ou [GitHub Sponsors](https://github.com/sponsors/jaz-on). Toute aide est la bienvenue et partager le projet, faire des retours, signaler des problèmes y participe également !

## Historique et crédits

### Fork et contribution

Ce plugin est un fork de l'extension **French Typo** créée par **Gilles Marchand** (master_shiva). Le code a été totalement refondu depuis mars 2024 avec l'aide de [Jean-Baptiste Audras](https://profiles.wordpress.org/audrasjb/). Pour voir le code d'origine, consultez le commit [25940a7d11b08e1f02791812cbdcf840d97a4086](https://github.com/jaz-on/french-typo/commit/25940a7d11b08e1f02791812cbdcf840d97a4086).

### Inspirations

Ce plugin s'inspire également d'autres extensions actives ou archivées/inactives :

* [TypoFR](https://wordpress.org/plugins/typofr/) — Plugin WordPress pour la typographie française
* [Orthotypo](https://wordpress.org/plugins/orthotypo-orthotypographie-automatique/) — Orthotypographie automatique
* [Consistency](https://wordpress.org/plugins/consistency/) — Typography Corrector for Gutenberg

## Licence

Ce plugin est sous licence [GPLv2 ou ultérieure](https://www.gnu.org/licenses/gpl-2.0.html).

## Changelog

### 1.0.0
* Version initiale
* Refonte complète depuis le plugin French Typo original
* Support des espaces insécables (normaux et fins)
* Support du remplacement de caractères spéciaux
* Page de réglages configurable

---

## English {#english}

WordPress plugin that allows you to apply French typography rules to the content you publish on a site powered by [WordPress](http://wordpress.org/).

### Features

#### Non-breaking spaces

[Non-breaking spaces](http://fr.wikipedia.org/wiki/Espace_ins%C3%A9cable) are automatically managed for the characters `;`, `:`, `!`, `?`, `%`, `«` and `»`. You can choose between:
* Regular non-breaking spaces (HTML entity `&nbsp;` or `&#160;`)
* Thin non-breaking spaces (HTML entity `&#8239;`)

#### Special characters

The characters `(c)` and `(r)` are automatically replaced by `©` and `®`.

### Installation

1. Download the latest version of Typographie française.
2. Extract the contents of the `zip` file and add the `french-typo` folder to the `/wp-content/plugins/` directory (or `/wp-content/mu-plugins/` if you want to make it mandatory by default on your site, for example).
3. Activate the plugin.
4. Configure the plugin as you wish in the `Settings > Réglages typographiques` (`Settings > French Typo`) menu from the WordPress back-office.

### Frequently Asked Questions

#### What does this plugin do?

This plugin automatically applies French typography rules to your WordPress content, including non-breaking spaces before punctuation marks and special character replacements.

#### Which punctuation marks are handled?

The plugin handles: `;`, `:`, `!`, `?`, `%`, `«`, and `»`.

#### What's the difference between regular and thin non-breaking spaces?

Regular non-breaking spaces (`&nbsp;` / `&#160;`) are standard spaces that prevent line breaks. Thin non-breaking spaces (`&#8239;`) are narrower spaces that may not display correctly depending on the font, browser, and operating system.

#### Does this plugin modify existing content?

No, the plugin applies typography rules on-the-fly when content is displayed, without modifying the original content in the database.

#### Can I disable certain features?

Yes, you can disable non-breaking spaces or special character replacements independently in the plugin settings.

### Author & Sponsorship

**Jason Rouet**

* Website: [jasonrouet.com](https://jasonrouet.com)
* Email: [bonjour@jasonrouet.com](mailto:bonjour@jasonrouet.com)
* WordPress.org: [profiles.wordpress.org/jaz_on/](https://profiles.wordpress.org/jaz_on/)

You can sponsor me on [Ko-fi](https://ko-fi.com/jasonrouet) or [GitHub Sponsors](https://github.com/sponsors/jaz-on). Any help is welcome: sharing the project, feedback, reporting issues, etc.

### History and credits

#### Fork and contribution

This plugin is a fork of the **French Typo** extension created by **Gilles Marchand** (master_shiva). The code has been completely rebuilt since March 2024 with the help of [Jean-Baptiste Audras](https://profiles.wordpress.org/audrasjb/). To see the original code, check commit [25940a7d11b08e1f02791812cbdcf840d97a4086](https://github.com/jaz-on/french-typo/commit/25940a7d11b08e1f02791812cbdcf840d97a4086).

#### Inspirations

This plugin is also inspired by other active or archived/inactive plugins:

* [TypoFR](https://wordpress.org/plugins/typofr/) — WordPress plugin for French typography
* [Orthotypo](https://wordpress.org/plugins/orthotypo-orthotypographie-automatique/) — Automatic orthotypography
* [Consistency](https://wordpress.org/plugins/consistency/) — Typography Corrector for Gutenberg

### License

This plugin is licensed under [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html).

### Changelog

#### 1.0.0
* Initial release
* Complete rewrite from the original French Typo plugin
* Support for non-breaking spaces (regular and thin)
* Support for special character replacements
* Configurable settings page

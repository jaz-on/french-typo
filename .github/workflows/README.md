# GitHub Actions Workflows

## Create Release ZIP

Ce workflow crée automatiquement un ZIP propre pour chaque release, en excluant les fichiers de développement selon le `.distignore`.

### Déclencheurs

Le workflow se déclenche automatiquement quand :
- Une release est **créée** (`release: created`)
- Une release est **éditée** (`release: edited`)
- Un tag est **poussé** (`push: tags`)

### Fonctionnement

1. **Checkout** du code au tag de la release
2. **Création d'un ZIP propre** en utilisant `.distignore` pour exclure :
   - Fichiers Git (`.git/`, `.gitignore`, etc.)
   - Fichiers de développement (`composer.json`, `README.md`, `TODO.md`, etc.)
   - Assets (`assets/`)
   - Fichiers de configuration (`.distignore`, etc.)
3. **Vérification** que le ZIP ne contient que les fichiers essentiels
4. **Upload** du ZIP comme asset de la release

### Fichiers inclus dans le ZIP

- `french-typo.php` - Fichier principal
- `readme.txt` - Readme WordPress.org
- `LICENSE` - Licence GPLv2
- `admin.css` - Styles admin
- `screenshots/screenshot-1.png` - Capture d'écran

### Utilisation

Pour créer une nouvelle release avec ZIP automatique :

1. Créer le tag : `git tag -a 1.1.0 -m "Version 1.1.0"`
2. Pousser le tag : `git push origin 1.1.0`
3. Créer la release sur GitHub (via l'interface ou `gh release create`)
4. Le workflow s'exécutera automatiquement et attachera le ZIP propre

**Note** : Si vous créez la release avant de pousser le tag, éditez simplement la release après pour déclencher le workflow.

### Vérification

Le workflow vérifie automatiquement que le ZIP ne contient pas de fichiers exclus. Si des fichiers indésirables sont détectés, le workflow échouera avec un message d'erreur.





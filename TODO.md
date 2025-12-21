# TODO

Idées au fil de l'eau pour améliorer le plugin.

À tester : vérifier que les URLs, attributs HTML, contenu `<code>`, `<pre>`, etc. ne sont pas modifiés.

## Court terme

### Points de suspension `...` → `…`

- Ajouter une option `ellipsis` (activée par défaut ?)
- Remplacer `...` par `…` (U+2026) dans le texte.
- Ne pas retraiter les `…` déjà présents.
- Attention : si la règle d’ellipsis est active, ne pas ajouter d’espace insécable supplémentaire après `…`.

Idée d’implémentation :

- étape de remplacement de caractères assez tôt dans le pipeline (après `(c)/(r)/(TM)`).
- prévoir un petit jeu de tests dans /docs/test-post-content.md

### Apostrophes typographiques

- Ajouter une option `curly_apostrophes`.
- Remplacer l’apostrophe droite `'` par l’apostrophe typographique `’` (U+2019).
- Gérer correctement les contractions françaises : `l'`, `d'`, `c'`, `m'`, `t'`, `s'`, `n'`, etc.
- Gérer les noms propres courants : `O'Brien`, `d'Artagnan`, etc.
- Ne pas casser les attributs HTML, le code, les shortcodes, ni les URLs.

Attention : vérifier que cette règle reste compatible avec la gestion des guillemets.

### Guillemets français

- Ajouter une option `french_quotes`.
- Transformer les guillemets droits `" "` en `« »` dans le contenu textuel.
- Gérer les niveaux de guillemets imbriqués :
  - premier niveau : `« … »` ;
  - niveau intérieur : conserver les guillemets droits `" "` ou basculer vers `' '` si besoin.
- Ne pas toucher aux guillemets dans les attributs HTML, le code, les shortcodes, etc.

### Symbole de marque `™`

- Étendre la logique « caractères spéciaux » existante pour gérer `(TM)` → `™` (U+2122).
- Gérer aussi les variantes `(tm)` et `(Tm)`.
- Ajouter un réglage spécifique ou inclure ça dans l’option `special_characters`.
- Toujours éviter HTML / URLs / code (mêmes garde‑fous que `(c)` et `(r)`).

## Long terme

### Unités de mesure

Idée : ajouter `measurement_units`.

- détecter les nombres suivis d’unités :
  - longueurs : `km`, `m`, `cm`, `mm`, `dm` ;
  - masses : `kg`, `g`, `mg` ;
  - volumes : `L`, `l`, `mL`, `ml`, `cl`, `dl` ;
  - températures : `°C`, `°F`, `K` ;
  - temps : `h`, `min`, `s`, `ms` ;
  - vitesses : `km/h`, `m/s` ;
  - surfaces : `m²`, `km²`, `cm²` ;
  - volumes : `m³`, `cm³`.
- ajouter l’espace insécable approprié entre le nombre et l’unité.
- ignorer les URLs, le code et les attributs HTML.

### Symboles monétaires

Idée : ajouter `currency_symbols`.

- gérer les montants suivis d’un symbole monétaire :
  - `€`, `$`, `£`, `¥` (au minimum).
- appliquer les conventions françaises :
  - `12 €`, `1 200 €`, etc. (avec l’espace insécable et séparateur de milliers plus tard).
- vérifier l’interaction avec la règle `%` (déjà gérée pour les espaces).

### Abréviations courantes

Idée : ajouter `abbreviations` (probablement désactivé par défaut).

- viser surtout les abréviations les plus fréquentes :
  - titres : `M.`, `Mme`, `Mlle`, `Dr`, `Prof`, `Pr` ;
  - autres : `etc.`, `cf.`, `ex.`, `p.`, `pp.`, `vol.`, `n°`, `n°s`.
- ajouter une espace insécable après l’abréviation quand elle est suivie d’un mot.
- bien filtrer pour éviter les faux positifs dans les URLs ou le code.

### Tirets (tirets cadratins / demi‑cadratins)

Idée : ajouter `dash_spacing`.

- distinguer :
  - tiret cadratin `—`,
  - tiret demi‑cadratin `–`,
  - simple `-` pour les mots composés.
- ajouter une espace insécable avant et après `—` / `–` en contexte de phrase.
- ignorer les dashes dans les URLs, les listes, et les mots composés (partie la plus délicate).

### Heures et durées

Idée : `time_spacing` ou équivalent (future option).

- viser des formats du type : `12h30`, `12 h 30`, `12 h 30 min`.
- normaliser en `12 h 30`, `12 h`, `12 h 30 min` (espaces insécables).
- même protection que d’habitude : pas d’impact sur URLs, code, attributs.

### Nombres ordinaux

Idée : `ordinal_numbers` (désactivée par défaut).

- transformer :
  - `1er`, `1ère` → `1<sup>er</sup>`, `1<sup>ère</sup>` ;
  - `2e`, `2ème`, `2nd`, `2nde` → variantes en `<sup>` ;
  - idem pour `3e`, `3ème`, etc., probablement jusqu’à 99 pour commencer.
- gérer les variantes en majuscules (`1ER`, `1Er`).
- comme toujours : ne rien faire dans les attributs, URLs, code, etc.

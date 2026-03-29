Titre : Cas pratiques pour tester French Typo

<!-- wp:paragraph -->
<p>Ce fichier regroupe des cas de test prêts à copier-coller dans WordPress pour vérifier à la fois les transformations typographiques et le comportement du plugin dans différents blocs.</p>
<!-- /wp:paragraph -->

<!-- wp:details -->
<details class="wp-block-details">
	<summary>Sommaire des tests</summary>
	<!-- wp:list -->
	<ul>
		<li><a href="#mode-demploi">Mode d’emploi</a></li>
		<li><a href="#tests-base">Tests de base : ponctuation et caractères</a>
			<ul>
				<li><a href="#test-1">Test 1 : ponctuation haute et espaces insécables</a></li>
				<li><a href="#test-2">Test 2 : guillemets français et ponctuation composée</a></li>
			</ul>
		</li>
		<li><a href="#tests-structure">Tests de contenu structuré</a>
			<ul>
				<li><a href="#test-3">Test 3 : listes, abréviations et unités</a></li>
			</ul>
		</li>
		<li><a href="#tests-riches">Tests de contenu riche</a>
			<ul>
				<li><a href="#test-4">Test 4 : citation avec guillemets français</a></li>
				<li><a href="#test-5">Test 5 : mélange de HTML</a></li>
			</ul>
		</li>
		<li><a href="#tests-medias">Tests sur les blocs médias et mise en page</a>
			<ul>
				<li><a href="#test-6">Test 6 : bloc image</a></li>
				<li><a href="#test-7">Test 7 : blocs Colonnes</a></li>
				<li><a href="#test-8">Test 8 : bloc Bouton</a></li>
				<li><a href="#test-9">Test 9 : bloc Table</a></li>
			</ul>
		</li>
		<li><a href="#tests-integration">Tests d’intégration et de navigation</a>
			<ul>
				<li><a href="#test-10">Test 10 : shortcodes et texte environnant</a></li>
				<li><a href="#test-11">Test 11 : flux RSS et réponses REST</a></li>
				<li><a href="#test-12">Test 12 : menus, widgets et fil d’Ariane</a></li>
			</ul>
		</li>
	</ul>
	<!-- /wp:list -->
</details>
<!-- /wp:details -->

<!-- wp:heading {"level":2} -->
<h2 id="mode-demploi">Mode d’emploi</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
	<li><strong>Étape 1</strong> : crée un nouvel article (ou brouillon) et active French Typo avec les options souhaitées.</li>
	<li><strong>Étape 2</strong> : colle ce bloc HTML dans l’éditeur en mode « Code ».</li>
	<li><strong>Étape 3</strong> : affiche l’article sur le front et vérifie les espaces insécables, les remplacements (c) / (r) et l’intégrité du HTML.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 id="tests-base">Tests de base : ponctuation et caractères</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 id="test-1">Test 1 : ponctuation haute et espaces insécables</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Objectif :</strong> vérifier les espaces insécables avant ; : ! ? % et le remplacement (c) / (r) dans un paragraphe simple.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Exemple à tester :</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><span style="color:#d63638;">Ce plugin est-il activé ? Génial ! Remise : 20 %.</span></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Résultat attendu (affichage) :</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><span style="color:#00a32a;">Ce plugin est-il activé ? Génial ! Remise : 20 %.</span></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 id="test-2">Test 2 : guillemets français et ponctuation composée</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Objectif :</strong> vérifier les espaces insécables après « et avant » ainsi que les combinaisons de ponctuation ?!.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Exemple à tester :</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><span style="color:#d63638;">Exemple de guillemets «bonjour» collés et d’espace avant « – sans oublier le duo “?!”. Autre phrase : «test de guillemets» !</span></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Résultat attendu (affichage) :</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><span style="color:#00a32a;">Exemple de guillemets « bonjour » collés et d’espace avant « – sans oublier le duo “?!”. Autre phrase : « test de guillemets » !</span></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 id="tests-structure">Tests de contenu structuré</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 id="test-3">Test 3 : listes, abréviations et unités</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Objectif :</strong> tester les listes, les abréviations et les pourcentages.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Exemple à tester :</h4>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Listes, abréviations et unités</h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
	<li><span style="color:#d63638;">Somme due : 1 250 € ; remise : 10 % ; total : 1 125 €.</span></li>
	<li><span style="color:#d63638;">Horaires : ouverture 09 h 00 ; fermeture 18 h 30.</span></li>
	<li><span style="color:#d63638;">Abréviation à tester : <abbr title="Monsieur">M.</abbr> Dupont s’exclame !</span></li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":4} -->
<h4>Résultat attendu (affichage) :</h4>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
	<li><span style="color:#00a32a;">Somme due : 1 250 € ; remise : 10 % ; total : 1 125 €.</span></li>
	<li><span style="color:#00a32a;">Horaires : ouverture 09 h 00 ; fermeture 18 h 30.</span></li>
	<li><span style="color:#00a32a;">Abréviation à tester : <abbr title="Monsieur">M.</abbr> Dupont s’exclame !</span></li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2 id="tests-riches">Tests de contenu riche</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 id="test-4">Test 4 : citation avec guillemets français</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Objectif :</strong> vérifier les guillemets français, la ponctuation haute et le rendu dans un bloc Citation.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Exemple à tester :</h4>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3>Bloc citation</h3>
<!-- /wp:heading -->

<!-- wp:quote -->
<blockquote class="wp-block-quote">
	<p><span style="color:#d63638;">«La ponctuation française est exigeante : il faut des espaces fines !»</span></p>
	<cite><span style="color:#d63638;">— Typographe anonyme</span></cite>
</blockquote>
<!-- /wp:quote -->

<!-- wp:heading {"level":4} -->
<h4>Résultat attendu (affichage) :</h4>
<!-- /wp:heading -->

<!-- wp:quote -->
<blockquote class="wp-block-quote">
	<p><span style="color:#00a32a;">« La ponctuation française est exigeante : il faut des espaces fines ! »</span></p>
	<cite><span style="color:#00a32a;">— Typographe anonyme</span></cite>
</blockquote>
<!-- /wp:quote -->

<!-- wp:heading {"level":3} -->
<h3 id="test-5">Test 5 : mélange de HTML (gras, italique, code)</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Objectif :</strong> vérifier que French Typo n’abîme pas les balises HTML tout en traitant la ponctuation du texte.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Exemple à tester :</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><span style="color:#d63638;">Mélange de HTML : <strong>Important !</strong> <em>Vraiment ?</em> Et une balise <code>&lt;code&gt;printf("Hello !");&lt;/code&gt;</code> à laisser intacte.</span></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Résultat attendu (affichage) :</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><span style="color:#00a32a;">Mélange de HTML : <strong>Important !</strong> <em>Vraiment ?</em> Et une balise <code>&lt;code&gt;printf("Hello !");&lt;/code&gt;</code> à laisser intacte.</span></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2 id="tests-medias">Tests sur les blocs médias et mise en page</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 id="test-6">Test 6 : bloc image (légende et contexte)</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Objectif :</strong> vérifier le comportement de French Typo dans la légende d’une image et dans le texte environnant.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Exemple à tester :</h4>
<!-- /wp:heading -->

<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://example.com/image-typo.jpg" alt="Affiche promo : 20 % de réduction !" /><figcaption><span style="color:#d63638;">Légende de l’image : Offre spéciale : 20 % de réduction ! Profitez-en maintenant !</span></figcaption></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":4} -->
<h4>Résultat attendu (affichage) :</h4>
<!-- /wp:heading -->

<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img src="https://example.com/image-typo.jpg" alt="Affiche promo : 20 % de réduction !" /><figcaption><span style="color:#00a32a;">Légende de l’image : Offre spéciale : 20 % de réduction ! Profitez-en maintenant !</span></figcaption></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 id="test-7">Test 7 : blocs Colonnes avec paragraphes</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Objectif :</strong> vérifier le rendu de la ponctuation dans plusieurs colonnes de texte.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Exemple à tester :</h4>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:paragraph -->
		<p><span style="color:#d63638;">Colonne 1 : première phrase ! Deuxième phrase ? Remise : 10 %.</span></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->

	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:paragraph -->
		<p><span style="color:#d63638;">Colonne 2 : «Texte aligné à droite» ; à vérifier aussi.</span></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:heading {"level":4} -->
<h4>Résultat attendu (affichage) :</h4>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:paragraph -->
		<p><span style="color:#00a32a;">Colonne 1 : première phrase ! Deuxième phrase ? Remise : 10 %.</span></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->

	<!-- wp:column -->
	<div class="wp-block-column">
		<!-- wp:paragraph -->
		<p><span style="color:#00a32a;">Colonne 2 : «Texte aligné à droite» ; à vérifier aussi.</span></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:heading {"level":3} -->
<h3 id="test-8">Test 8 : bloc Bouton</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Objectif :</strong> observer si le texte d’un bouton bénéficie des règles typographiques ou reste inchangé.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Exemple à tester :</h4>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
	<!-- wp:button -->
	<div class="wp-block-button"><a class="wp-block-button__link"><span style="color:#d63638;">Commander maintenant : 20 % !</span></a></div>
	<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

<!-- wp:heading {"level":4} -->
<h4>Résultat attendu (affichage) :</h4>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
	<!-- wp:button -->
	<div class="wp-block-button"><a class="wp-block-button__link"><span style="color:#00a32a;">Commander maintenant : 20 % !</span></a></div>
	<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

<!-- wp:heading {"level":3} -->
<h3 id="test-9">Test 9 : bloc Table</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Objectif :</strong> vérifier le comportement dans un tableau de données (titres de colonnes, cellules).</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Exemple à tester :</h4>
<!-- /wp:heading -->

<!-- wp:table -->
<figure class="wp-block-table">
<table>
	<thead>
		<tr>
			<th><span style="color:#d63638;">Produit</span></th>
			<th><span style="color:#d63638;">Prix : TTC</span></th>
			<th><span style="color:#d63638;">Remise : %</span></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><span style="color:#d63638;">Version Standard</span></td>
			<td><span style="color:#d63638;">49 €</span></td>
			<td><span style="color:#d63638;">10 %</span></td>
		</tr>
		<tr>
			<td><span style="color:#d63638;">Version Pro</span></td>
			<td><span style="color:#d63638;">99 €</span></td>
			<td><span style="color:#d63638;">20 %</span></td>
		</tr>
	</tbody>
</table>
</figure>
<!-- /wp:table -->

<!-- wp:heading {"level":4} -->
<h4>Résultat attendu (affichage) :</h4>
<!-- /wp:heading -->

<!-- wp:table -->
<figure class="wp-block-table">
<table>
	<thead>
		<tr>
			<th><span style="color:#00a32a;">Produit</span></th>
			<th><span style="color:#00a32a;">Prix : TTC</span></th>
			<th><span style="color:#00a32a;">Remise : %</span></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><span style="color:#00a32a;">Version Standard</span></td>
			<td><span style="color:#00a32a;">49 €</span></td>
			<td><span style="color:#00a32a;">10 %</span></td>
		</tr>
		<tr>
			<td><span style="color:#00a32a;">Version Pro</span></td>
			<td><span style="color:#00a32a;">99 €</span></td>
			<td><span style="color:#00a32a;">20 %</span></td>
		</tr>
	</tbody>
</table>
</figure>
<!-- /wp:table -->

<!-- wp:heading {"level":2} -->
<h2 id="tests-integration">Tests d’intégration et de navigation</h2>
<!-- /wp:heading -->

<!-- wp:heading {"level":3} -->
<h3 id="test-10">Test 10 : shortcodes et texte environnant</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Objectif :</strong> s’assurer que le shortcode lui‑même n’est pas cassé, tout en laissant French Typo travailler sur le texte autour.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Exemple à tester :</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><span style="color:#d63638;">Shortcode à surveiller (ne devrait pas être modifié en dehors du texte rendu) : [contact-form]Merci de répondre ![/contact-form].</span></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Résultat attendu (affichage) :</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><span style="color:#00a32a;">Shortcode à surveiller (ne devrait pas être modifié en dehors du texte rendu) : [contact-form]Merci de répondre ![/contact-form].</span></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 id="test-11">Test 11 : flux RSS et réponses REST</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Objectif :</strong> fournir un paragraphe riche en ponctuation pour observer le résultat dans les flux RSS et l’API REST.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Exemple à tester :</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><span style="color:#d63638;">Code brut pour RSS/REST : %s ; %d ; ! ; ? &amp; encore (c) (r). Cas REST : «API / endpoint v1» renvoie “Statut : OK !” quand French Typo est actif.</span></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Résultat attendu (affichage, avec © et ® si les codes (c) et (r) sont présents) :</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><span style="color:#00a32a;">Code brut pour RSS/REST : %s ; %d ; ! ; ? &amp; encore (c) (r). Cas REST : «API / endpoint v1» renvoie “Statut : OK !” quand French Typo est actif.</span></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 id="test-12">Test 12 : menus, widgets et fil d’Ariane</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>Objectif :</strong> réutiliser ce texte dans des widgets, titres de menus et breadcrumbs générés par un plugin SEO.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Exemple à tester :</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><span style="color:#d63638;">Breadcrumb fictif : Accueil &gt; Documentation &gt; Chapitre «Ponctuation : bonnes pratiques». Menus et widgets : ajoute ce même texte dans un widget titre «Widget test !» et observe.</span></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":4} -->
<h4>Résultat attendu (affichage) :</h4>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><span style="color:#00a32a;">Breadcrumb fictif : Accueil &gt; Documentation &gt; Chapitre «Ponctuation : bonnes pratiques». Menus et widgets : ajoute ce même texte dans un widget titre «Widget test !» et observe.</span></p>
<!-- /wp:paragraph -->

 
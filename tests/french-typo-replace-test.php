<?php
/**
 * Lightweight assertions for french_typo_replace() raw boundaries and Verse.
 *
 * @package French_Typo
 */

require __DIR__ . '/bootstrap.php';

/**
 * Print message and exit 1.
 *
 * @param string $message Message.
 */
function french_typo_test_fail( $message ) {
	fwrite( STDERR, $message . PHP_EOL );
	exit( 1 );
}

$nbsp = '&#160;';

// SVG: CSS inside <style> must stay unchanged; prose outside still typographed.
$html_svg = '<svg><style>.x{a:b;}</style></svg><p>Ok !</p>';
$out_svg  = french_typo_replace( $html_svg );
if ( false === strpos( $out_svg, '<style>.x{a:b;}</style>' ) ) {
	french_typo_test_fail( 'SVG style: expected CSS block unchanged.' );
}
if ( false === strpos( $out_svg, '<p>Ok' . $nbsp . '!</p>' ) ) {
	french_typo_test_fail( 'SVG style: expected narrow space before ! in <p>.' );
}
if ( false !== strpos( $out_svg, 'a' . $nbsp . ':b' ) ) {
	french_typo_test_fail( 'SVG style: narrow space must not be injected in CSS.' );
}

// Nested pre/code: no typo inside.
$html_code = '<pre><code>z ! z; (c) (r) (tm) (TM)</code></pre>';
$out_code  = french_typo_replace( $html_code );
$expected_inner = '<code>z ! z; (c) (r) (tm) (TM)</code>';
if ( false === strpos( $out_code, $expected_inner ) ) {
	french_typo_test_fail( 'pre/code: inner literals must stay unchanged.' );
}

// Gutenberg Verse: typography still applied inside pre.
$html_verse = '<pre class="wp-block-verse">Hi !</pre>';
$out_verse  = french_typo_replace( $html_verse );
if ( $out_verse !== '<pre class="wp-block-verse">Hi' . $nbsp . '!</pre>' ) {
	french_typo_test_fail( 'Verse: expected narrow space before ! inside wp-block-verse.' );
}

// textarea: raw text, no replacements inside.
$html_ta = '<textarea>q ! (c)</textarea>';
$out_ta  = french_typo_replace( $html_ta );
if ( $out_ta !== '<textarea>q ! (c)</textarea>' ) {
	french_typo_test_fail( 'textarea: inner content must not be typographed.' );
}

// Ordinal abbreviations (issue #3): plain text.
$plain_ord = '1ère 3ème 22ème n-ième x–ième 1ème 1st 2nd';
$out_ord   = french_typo_replace( $plain_ord );
$exp_ord   = '1re 3e 22e nième xième 1ème 1st 2nd';
if ( $out_ord !== $exp_ord ) {
	french_typo_test_fail( 'Ordinal plain: expected ' . $exp_ord . ' got: ' . $out_ord );
}

// Ordinal: HTML inner text only.
$html_ord = '<p>La 3ème fois</p>';
$out_pord = french_typo_replace( $html_ord );
if ( $out_pord !== '<p>La 3e fois</p>' ) {
	french_typo_test_fail( 'Ordinal HTML: expected <p>La 3e fois</p> got: ' . $out_pord );
}

// Ordinal: unchanged inside code.
$html_ocode = '<code>3ème</code>';
$out_ocode  = french_typo_replace( $html_ocode );
if ( $out_ocode !== '<code>3ème</code>' ) {
	french_typo_test_fail( 'Ordinal code: inner 3ème must stay unchanged.' );
}

// Ordinal + SVG: prose still normalized after SVG block.
$html_svg_ord = '<svg><style>.x{a:b;}</style></svg><p>2ème</p>';
$out_svg_ord  = french_typo_replace( $html_svg_ord );
if ( false === strpos( $out_svg_ord, '<p>2e</p>' ) ) {
	french_typo_test_fail( 'Ordinal SVG: expected 2ème → 2e in <p> after SVG.' );
}

// HTML entities in plain text (no tags, no shortcodes): the trailing `;`
// of an entity must NOT trigger the nbsp-before-`;` rule. Regression for
// titles like "Foo &#038; Bar" produced by core `convert_chars` filter.
$plain_entity = 'You Got Me (feat. Erykah Badu &#038; Eve)';
$out_entity   = french_typo_replace( $plain_entity );
if ( $out_entity !== $plain_entity ) {
	french_typo_test_fail( 'Entity plain: expected ' . $plain_entity . ' got: ' . $out_entity );
}

// Same entity rule, with mixed plain text containing real French punctuation:
// nbsp must appear before "?" but the entity stays intact.
$mixed_entity = 'Vraiment ? Earth &amp; Fire';
$out_mixed    = french_typo_replace( $mixed_entity );
$exp_mixed    = 'Vraiment' . $nbsp . '? Earth &amp; Fire';
if ( $out_mixed !== $exp_mixed ) {
	french_typo_test_fail( 'Entity mixed: expected ' . $exp_mixed . ' got: ' . $out_mixed );
}

// Named & numeric entities mixed.
$multi_entity = 'A &amp; B &#038; C &#x26; D';
$out_multi    = french_typo_replace( $multi_entity );
if ( $out_multi !== $multi_entity ) {
	french_typo_test_fail( 'Entity multi: expected ' . $multi_entity . ' got: ' . $out_multi );
}

// Shortcode (issue #11): attributes must not be typographed before do_shortcode()
// parses them — wp_html_split() only knows HTML tags, not shortcodes.
$plain_shortcode = 'Prix : 10€ [gallery caption="Merci !"] Fin.';
$out_shortcode   = french_typo_replace( $plain_shortcode );
$exp_shortcode   = 'Prix' . $nbsp . ': 10€ [gallery caption="Merci !"] Fin.';
if ( $out_shortcode !== $exp_shortcode ) {
	french_typo_test_fail( 'Shortcode plain: expected ' . $exp_shortcode . ' got: ' . $out_shortcode );
}

// Same shortcode, but inside an HTML segment (forces the wp_html_split() branch
// rather than the plain-text branch).
$html_shortcode = '<p>Prix : 10€ [gallery caption="Merci !"] Fin.</p>';
$out_html_sc    = french_typo_replace( $html_shortcode );
$exp_html_sc    = '<p>Prix' . $nbsp . ': 10€ [gallery caption="Merci !"] Fin.</p>';
if ( $out_html_sc !== $exp_html_sc ) {
	french_typo_test_fail( 'Shortcode HTML: expected ' . $exp_html_sc . ' got: ' . $out_html_sc );
}

fwrite( STDERR, "french_typo_replace() tests OK\n" );
exit( 0 );

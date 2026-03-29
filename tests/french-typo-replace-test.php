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

fwrite( STDERR, "french_typo_replace() tests OK\n" );
exit( 0 );

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

fwrite( STDERR, "french_typo_replace() tests OK\n" );
exit( 0 );

<?php
/**
 * Assert language restriction modes when Polylang is active.
 *
 * Each case mutates $GLOBALS['french_typo_test_options_override'] and
 * $GLOBALS['french_typo_test_polylang_post_locale'] then exercises
 * french_typo_replace() with a short input that exhibits the NBSP rule.
 *
 * @package French_Typo
 */

define( 'FRENCH_TYPO_TEST_STUB_POLYLANG', true );
define( 'FRENCH_TYPO_TEST_NO_OPTIONS_CACHE', true );
define( 'FRENCH_TYPO_TEST_NO_LOCALE_CACHE', true );

require __DIR__ . '/bootstrap.php';

/**
 * Print message and exit 1.
 *
 * @param string $message Message.
 */
function french_typo_polylang_fail( $message ) {
	fwrite( STDERR, $message . PHP_EOL );
	exit( 1 );
}

$nbsp     = '&#160;';
$input    = 'Bonjour : monde';
$expected = 'Bonjour' . $nbsp . ': monde';

// Sanity: ensure the stub is active.
if ( ! function_exists( 'pll_get_post_language' ) ) {
	french_typo_polylang_fail( 'Polylang stub was not registered.' );
}

$GLOBALS['french_typo_test_current_post_id'] = 42;

/**
 * Drive one case: set mode/locales + Polylang locale, then assert.
 *
 * @param string $label    Label for failure message.
 * @param string $mode     Restriction mode.
 * @param array  $locales  Custom locales (used when mode = custom).
 * @param string $loc      Polylang locale to inject for the post.
 * @param bool   $applies  Whether typography should run.
 */
function french_typo_polylang_case( $label, $mode, $locales, $loc, $applies ) {
	global $nbsp, $input, $expected;

	$GLOBALS['french_typo_test_options_override']    = array(
		'language_restriction_mode'    => $mode,
		'language_restriction_locales' => $locales,
	);
	$GLOBALS['french_typo_test_polylang_post_locale']    = $loc;
	$GLOBALS['french_typo_test_polylang_current_locale'] = $loc;

	$out      = french_typo_replace( $input, 42 );
	$expected_out = $applies ? $expected : $input;

	if ( $out !== $expected_out ) {
		french_typo_polylang_fail( $label . ': expected ' . var_export( $expected_out, true ) . ' got: ' . var_export( $out, true ) );
	}
}

french_typo_polylang_case( 'mode=off (backward compat) / en_US', 'off',    array(),                  'en_US', true );
french_typo_polylang_case( 'mode=auto_fr / fr_FR',                 'auto_fr', array(),                  'fr_FR', true );
french_typo_polylang_case( 'mode=auto_fr / en_US',                 'auto_fr', array(),                  'en_US', false );
french_typo_polylang_case( 'mode=auto_fr / fr_BE',                 'auto_fr', array(),                  'fr_BE', true );
french_typo_polylang_case( 'mode=custom / [fr_FR,fr_BE] / fr_FR',  'custom',  array( 'fr_FR', 'fr_BE' ), 'fr_FR', true );
french_typo_polylang_case( 'mode=custom / [fr_FR] / fr_BE',        'custom',  array( 'fr_FR' ),          'fr_BE', false );

fwrite( STDERR, "french_typo_replace() polylang tests OK\n" );
exit( 0 );

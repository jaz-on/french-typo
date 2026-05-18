<?php
/**
 * Assert language restriction fallback to site locale (get_locale()) when
 * no multilingual plugin is active.
 *
 * @package French_Typo
 */

define( 'FRENCH_TYPO_TEST_NO_OPTIONS_CACHE', true );
define( 'FRENCH_TYPO_TEST_NO_LOCALE_CACHE', true );

require __DIR__ . '/bootstrap.php';

/**
 * Print message and exit 1.
 *
 * @param string $message Message.
 */
function french_typo_site_locale_fail( $message ) {
	fwrite( STDERR, $message . PHP_EOL );
	exit( 1 );
}

$nbsp     = '&#160;';
$input    = 'Bonjour : monde';
$expected = 'Bonjour' . $nbsp . ': monde';

// Ensure Polylang functions are NOT defined (this is the no-plugin scenario).
if ( function_exists( 'pll_get_post_language' ) ) {
	french_typo_site_locale_fail( 'Site-locale test must run without Polylang stub.' );
}

// Case 1: auto_fr + fr_FR site locale → applies (fallback to get_locale()).
$GLOBALS['french_typo_test_site_locale']         = 'fr_FR';
$GLOBALS['french_typo_test_options_override']    = array(
	'language_restriction_mode'    => 'auto_fr',
	'language_restriction_locales' => array(),
);
$out = french_typo_replace( $input );
if ( $out !== $expected ) {
	french_typo_site_locale_fail( 'auto_fr + site fr_FR: expected ' . var_export( $expected, true ) . ' got: ' . var_export( $out, true ) );
}

// Case 2: auto_fr + en_US site locale → does not apply.
$GLOBALS['french_typo_test_site_locale'] = 'en_US';
$out = french_typo_replace( $input );
if ( $out !== $input ) {
	french_typo_site_locale_fail( 'auto_fr + site en_US: expected unchanged got: ' . var_export( $out, true ) );
}

fwrite( STDERR, "french_typo_replace() site-locale tests OK\n" );
exit( 0 );

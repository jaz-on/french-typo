<?php
/**
 * Assert french_typo_replace() runs when only ordinal_abbreviations is enabled.
 *
 * @package French_Typo
 */

define( 'FRENCH_TYPO_TEST_ORDINAL_ONLY', true );

require __DIR__ . '/bootstrap.php';

/**
 * Print message and exit 1.
 *
 * @param string $message Message.
 */
function french_typo_test_ordinal_only_fail( $message ) {
	fwrite( STDERR, $message . PHP_EOL );
	exit( 1 );
}

$out = french_typo_replace( 'La 3ème fois' );
if ( 'La 3e fois' !== $out ) {
	french_typo_test_ordinal_only_fail( 'ordinal only: expected La 3e fois, got: ' . $out );
}

fwrite( STDERR, "french_typo_replace() ordinal-only tests OK\n" );
exit( 0 );

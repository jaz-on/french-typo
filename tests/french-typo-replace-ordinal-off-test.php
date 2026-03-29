<?php
/**
 * Assert french_typo_replace() leaves ordinals unchanged when ordinal_abbreviations is off.
 *
 * @package French_Typo
 */

define( 'FRENCH_TYPO_TEST_ORDINAL_ABBREV_OFF', true );

require __DIR__ . '/bootstrap.php';

/**
 * Print message and exit 1.
 *
 * @param string $message Message.
 */
function french_typo_test_ordinal_off_fail( $message ) {
	fwrite( STDERR, $message . PHP_EOL );
	exit( 1 );
}

$out = french_typo_replace( 'La 3ème fois' );
if ( 'La 3ème fois' !== $out ) {
	french_typo_test_ordinal_off_fail( 'ordinal off: expected La 3ème fois unchanged, got: ' . $out );
}

fwrite( STDERR, "french_typo_replace() ordinal-off tests OK\n" );
exit( 0 );

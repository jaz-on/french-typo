<?php
/**
 * (c) / (r) / (tm) symbols stay intact next to the narrow-space punctuation rules.
 *
 * @package French_Typo
 */

class SymbolsTest extends FrenchTypoTestCase {

	/**
	 * @dataProvider symbol_provider
	 */
	public function test_symbols( int $narrow, string $input, string $expected ): void {
		$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => $narrow );
		$first = french_typo_replace( $input );
		$this->assertSame( $expected, $first );
		$this->assertSame( $expected, french_typo_replace( $first ), 'second pass must not change the output' );
	}

	public function symbol_provider(): array {
		$cases = array();
		$syms  = array(
			'(c)'  => '&#169;',
			'(r)'  => '&#174;',
			'(tm)' => '&#8482;',
		);
		foreach ( array(
			0 => ' ',
			1 => '&#160;',
			2 => '&#8239;',
		) as $mode => $nbs ) {
			foreach ( $syms as $code => $entity ) {
				$cases[ "plain $code mode $mode" ] = array( $mode, "Copyright $code 2025", "Copyright $entity 2025" );
				$cases[ "html $code mode $mode" ]  = array( $mode, "<p>Copyright $code 2025 !</p>", "<p>Copyright $entity 2025" . $nbs . '!</p>' );
			}
		}
		return $cases;
	}
}

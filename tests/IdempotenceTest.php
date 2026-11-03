<?php
/**
 * Every spacing variant canonicalises to the same output, and a second pass changes nothing.
 *
 * @package French_Typo
 */

class IdempotenceTest extends FrenchTypoTestCase {

	private const U00A0 = "\xC2\xA0";
	private const U202F = "\xE2\x80\xAF";

	/**
	 * @dataProvider variant_provider
	 */
	public function test_canonical_and_idempotent( string $input, string $expected ): void {
		$first = french_typo_replace( $input );
		$this->assertSame( $expected, $first, 'first pass: ' . bin2hex( $input ) );
		$this->assertSame( $expected, french_typo_replace( $first ), 'second pass must not change the output' );
	}

	public function variant_provider(): array {
		$n        = self::NBSP;
		$a        = self::U00A0;
		$f        = self::U202F;
		$expected = '<p>Bonjour' . $n . ': monde</p>';
		$guill    = '<p>Il dit «' . $n . 'salut' . $n . '» au revoir</p>';

		return array(
			'named entity preserved'          => array( '<p>Bonjour&nbsp;: monde</p>', $expected ),
			'numeric entity preserved'        => array( '<p>Bonjour&#160;: monde</p>', $expected ),
			'numeric entity padded'           => array( '<p>Bonjour&#0160;: monde</p>', $expected ),
			'hex entity preserved'            => array( '<p>Bonjour&#xA0;: monde</p>', $expected ),
			'hex entity padded'               => array( '<p>Bonjour&#x00A0;: monde</p>', $expected ),
			'fine numeric entity'             => array( '<p>Bonjour&#8239;: monde</p>', $expected ),
			'fine hex entity'                 => array( '<p>Bonjour&#x202F;: monde</p>', $expected ),
			'U+00A0 literal preserved'        => array( '<p>Bonjour' . $a . ': monde</p>', $expected ),
			'U+202F literal preserved'        => array( '<p>Bonjour' . $f . ': monde</p>', $expected ),
			'double named entity collapses'   => array( '<p>Bonjour&nbsp;&nbsp;: monde</p>', $expected ),
			'mixed named and numeric'         => array( '<p>Bonjour&nbsp;&#160;: monde</p>', $expected ),
			'double U+00A0 collapses'         => array( '<p>Bonjour' . $a . $a . ': monde</p>', $expected ),
			'U+00A0 then entity collapses'    => array( '<p>Bonjour' . $a . '&#160;: monde</p>', $expected ),
			'guillemets named entities'       => array( '<p>Il dit «&nbsp;salut&nbsp;» au revoir</p>', $guill ),
			'guillemets U+00A0 literals'      => array( '<p>Il dit «' . $a . 'salut' . $a . '» au revoir</p>', $guill ),
			'guillemets doubled NBSP'         => array( '<p>Il dit «&nbsp;&nbsp;salut&nbsp;&nbsp;» au revoir</p>', $guill ),
			'plain U+00A0 literal'            => array( 'Bonjour' . $a . ': monde', 'Bonjour' . $n . ': monde' ),
			'plain double U+00A0'             => array( 'Bonjour' . $a . $a . ': monde', 'Bonjour' . $n . ': monde' ),
			'plain no NBSP at all'            => array( 'Bonjour: monde', 'Bonjour' . $n . ': monde' ),
			'plain ASCII space'               => array( 'Bonjour : monde', 'Bonjour' . $n . ': monde' ),
		);
	}

	public function test_entities_away_from_punctuation_stay_intact(): void {
		$out = french_typo_replace( '<p>A&amp;B mots ' . self::U00A0 . ' au milieu</p>' );
		$this->assertStringContainsString( '&amp;', $out );
	}
}

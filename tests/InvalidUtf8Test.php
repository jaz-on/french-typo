<?php
/**
 * Invalid or truncated UTF-8 must never make the content disappear.
 *
 * @package French_Typo
 */

class InvalidUtf8Test extends FrenchTypoTestCase {

	/**
	 * @dataProvider invalid_provider
	 */
	public function test_invalid_utf8_is_returned_untouched( int $narrow, string $input ): void {
		$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => $narrow );
		$this->assertSame( $input, french_typo_replace( $input ) );
		// Second call: the static cache must neither freeze a wrong value nor hide the text.
		$this->assertSame( $input, french_typo_replace( $input ) );
	}

	public function invalid_provider(): array {
		$long  = str_repeat( 'Un texte valide : avec de la ponctuation ! Et 1ère, 2ème ? ', 4 );
		$texts = array(
			'plain'         => "Caf\xC3 : ok",
			'html'          => "<p>Caf\xC3 : ok !</p>",
			'long plain'    => $long . "Caf\xC3 : fin" . $long,
			'long html'     => '<p>' . $long . "Caf\xC3 : fin !</p><p>" . $long . '</p>',
			'entities'      => "Caf\xC3 &amp; thé : ok &#8217;",
			'ordinal only'  => "Caf\xC3 1ère",
		);
		$cases = array();
		foreach ( array( 0, 1, 2 ) as $mode ) {
			foreach ( $texts as $name => $text ) {
				$cases[ "$name mode $mode" ] = array( $mode, $text );
			}
		}
		return $cases;
	}

	/**
	 * @dataProvider mode_provider
	 */
	public function test_valid_neighbour_is_still_processed( int $narrow ): void {
		$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => $narrow );
		$bad = "Caf\xC3 : ok";
		$this->assertSame( $bad, french_typo_replace( $bad ) );
		$out = french_typo_replace( 'Bonjour : ok ! 1ère' );
		$this->assertNotSame( 'Bonjour : ok ! 1ère', $out );
		$this->assertStringContainsString( '1re', $out );
		$this->assertNotSame( '', french_typo_replace( 'Bonjour : ok' ) );
	}

	public function mode_provider(): array {
		return array( array( 0 ), array( 1 ), array( 2 ) );
	}
}

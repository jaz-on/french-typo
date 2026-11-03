<?php
/**
 * Punctuation that is not French prose (smileys, code, paths, IPv6, line breaks) gets no non-breaking space.
 *
 * @package French_Typo
 */

class PunctuationFalsePositivesTest extends FrenchTypoTestCase {

	/**
	 * @dataProvider unchanged_provider
	 */
	public function test_unchanged( string $input ): void {
		foreach ( array( 1, 2 ) as $mode ) {
			$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => $mode );
			$this->assertSame( $input, french_typo_replace( $input ), "mode $mode" );
		}
	}

	public function unchanged_provider(): array {
		return array(
			'smiley'            => array( 'Salut :) à tous' ),
			'winking smiley'    => array( 'Salut ;-) à tous' ),
			'cpp scope'         => array( 'Utiliser std::vector ici' ),
			'windows path'      => array( 'Dossier C:\\Windows ok' ),
			'ipv6'              => array( 'Adresse 2001:db8::1 ok' ),
			'newline before !'  => array( "Ligne\n!\nsuite" ),
			'newline before ?'  => array( "Ligne\r\n?\r\nsuite" ),
			'time'              => array( 'Rdv à 12:30' ),
			'ratio'             => array( 'Format 16:9' ),
		);
	}

	public function test_prose_punctuation_still_gets_a_space(): void {
		$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => 1 );
		$n = self::NBSP;
		$this->assertSame( 'Pourquoi' . $n . '?', french_typo_replace( 'Pourquoi ?' ) );
		$this->assertSame( 'Quoi' . $n . ': oui', french_typo_replace( 'Quoi : oui' ) );
		$this->assertSame( 'Oui' . $n . '!', french_typo_replace( 'Oui!' ) );
		$this->assertSame( "Ligne\n" . 'Oui' . $n . '!', french_typo_replace( "Ligne\nOui !" ) );
		$this->assertSame( '<b>Oui</b>' . $n . '!', french_typo_replace( '<b>Oui</b> !' ) );
	}
}

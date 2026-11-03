<?php
/**
 * Text starting with `[` (page-builder shortcodes, bracketed titles) is typographed; shortcode tags stay untouched.
 *
 * @package French_Typo
 */

class ShortcodeSegmentTest extends FrenchTypoTestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => 1 );
	}

	/**
	 * @dataProvider provider
	 */
	public function test_shortcodes( string $input, string $expected ): void {
		$first = french_typo_replace( $input );
		$this->assertSame( $expected, $first );
		$this->assertSame( $expected, french_typo_replace( $first ), 'second pass must not change the output' );
	}

	public function provider(): array {
		$n = self::NBSP;
		return array(
			'bracketed title'                  => array( '[Podcast] Quoi de neuf ?', '[Podcast] Quoi de neuf' . $n . '?' ),
			'divi text module'                 => array(
				'[et_pb_text]Bonjour : toi ![/et_pb_text]',
				'[et_pb_text]Bonjour' . $n . ': toi' . $n . '![/et_pb_text]',
			),
			'divi with html'                   => array(
				'[et_pb_text]<p>Bonjour : toi</p>[/et_pb_text]',
				'[et_pb_text]<p>Bonjour' . $n . ': toi</p>[/et_pb_text]',
			),
			'attributes untouched'             => array(
				'[gallery caption="Merci !" ids="1:2"] Texte : fin',
				'[gallery caption="Merci !" ids="1:2"] Texte' . $n . ': fin',
			),
			'nested brackets in attribute'     => array(
				'[chart title="Avant : après" data=\'[1,2]\'] fin !',
				'[chart title="Avant : après" data=\'[1,2]\'] fin' . $n . '!',
			),
			'symbol in attribute untouched'    => array(
				'[box legend="(c) Jo"] (c) Jo',
				'[box legend="(c) Jo"] &#169; Jo',
			),
			'unbalanced bracket is plain text' => array( '[ Voir ceci : oui', '[ Voir ceci' . $n . ': oui' ),
			'shortcode inside raw code'        => array( '<code>[a b="x : y"] z !</code>', '<code>[a b="x : y"] z !</code>' ),
		);
	}
}

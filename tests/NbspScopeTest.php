<?php
/**
 * Non-breaking spaces are only canonicalised next to the targeted punctuation, in text outside raw zones and attributes.
 *
 * @package French_Typo
 */

class NbspScopeTest extends FrenchTypoTestCase {

	private const A0 = "\xC2\xA0";
	private const T  = "\xE2\x80\xAF";

	/**
	 * @dataProvider untouched_provider
	 */
	public function test_untouched( string $input ): void {
		foreach ( array( 1, 2 ) as $mode ) {
			$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => $mode );
			$first = french_typo_replace( $input );
			$this->assertSame( $input, $first, "mode $mode" );
			$this->assertSame( $input, french_typo_replace( $first ), "mode $mode, second pass" );
		}
	}

	public function untouched_provider(): array {
		$a = self::A0;
		return array(
			'entity run used as spacer'  => array( '<td>&nbsp;&nbsp;&nbsp;x</td>' ),
			'entity in pre'              => array( '<pre>a&nbsp;b</pre>' ),
			'literal in code'            => array( '<code>a' . $a . 'b</code>' ),
			'literal in script'          => array( '<script>var s=\'a' . $a . 'b\';</script>' ),
			'entity in textarea'         => array( '<textarea>a&nbsp;&nbsp;b</textarea>' ),
			'entity in attribute'        => array( '<img alt="a&nbsp;b" src="x.png">' ),
			'literal in attribute'       => array( '<a title="a' . $a . 'b">x</a>' ),
			'price with entity'          => array( '<bdi>12,50&nbsp;<span>€</span></bdi>' ),
			'thousands literal'          => array( '1' . $a . '000' . $a . '€ au total' ),
			'thousands thin literal'     => array( '10' . self::T . '000 km' ),
			'thousands entity'           => array( '1&nbsp;000&nbsp;€' ),
		);
	}

	public function test_mode_2_still_canonicalises_next_to_punctuation(): void {
		$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => 2 );
		$this->assertSame( 'Quoi&#8239;? 1' . self::A0 . '000', french_typo_replace( 'Quoi&nbsp;? 1' . self::A0 . '000' ) );
		$this->assertSame( 'Quoi&#8239;?', french_typo_replace( 'Quoi' . self::A0 . '?' ) );
	}

	public function test_author_thin_space_next_to_punctuation_follows_the_configured_mode(): void {
		$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => 1 );
		$this->assertSame( 'Quoi&#160;?', french_typo_replace( 'Quoi' . self::T . '?' ) );
	}

	public function test_raw_zone_next_to_text_with_nbsp(): void {
		$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => 2 );
		$in  = '<p>Oui&nbsp;! <code>a&nbsp;b</code> 1' . self::A0 . '000</p>';
		$out = '<p>Oui&#8239;! <code>a&nbsp;b</code> 1' . self::A0 . '000</p>';
		$this->assertSame( $out, french_typo_replace( $in ) );
		$this->assertSame( $out, french_typo_replace( $out ) );
	}
}

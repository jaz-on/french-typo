<?php
/**
 * Ordinal abbreviations follow their own option, independently of the others.
 *
 * @package French_Typo
 */

class OrdinalOptionsTest extends FrenchTypoTestCase {

	public function test_ordinals_are_left_alone_when_the_option_is_off(): void {
		$GLOBALS['french_typo_test_options_override'] = array( 'ordinal_abbreviations' => false );
		$this->assertSame( 'La 3ème fois', french_typo_replace( 'La 3ème fois' ) );
	}

	public function test_ordinals_run_when_they_are_the_only_option_enabled(): void {
		$GLOBALS['french_typo_test_options_override'] = array(
			'narrow_space'          => 0,
			'special_characters'    => 0,
			'ordinal_abbreviations' => true,
		);
		$this->assertSame( 'La 3e fois', french_typo_replace( 'La 3ème fois' ) );
	}
}

<?php
/**
 * The sanitize callback of the settings: unknown keys and non-array input.
 *
 * @package French_Typo
 */

class OptionsValidateTest extends FrenchTypoTestCase {

	public function test_non_array_input_gives_the_defaults(): void {
		foreach ( array( '', 'narrow_space=2&apply_to_titles=1', null, 7 ) as $input ) {
			$out = french_typo_options_validate( $input );
			$this->assertSame( 0, $out['narrow_space'] );
			$this->assertFalse( $out['apply_to_titles'] );
			$this->assertFalse( $out['special_characters'] );
			$this->assertSame( 'off', $out['language_restriction_mode'] );
			$this->assertSame( array(), $out['language_restriction_locales'] );
		}
	}

	public function test_unknown_keys_are_dropped(): void {
		$out = french_typo_options_validate(
			array(
				'apply_to_titles' => '1',
				'evil'            => 'x',
				'0'               => 'y',
			)
		);
		$this->assertTrue( $out['apply_to_titles'] );
		$this->assertArrayNotHasKey( 'evil', $out );
		$this->assertArrayNotHasKey( 0, $out );
	}

	public function test_existing_bounds_are_unchanged(): void {
		$this->assertSame( 2, french_typo_options_validate( array( 'narrow_space' => '9' ) )['narrow_space'] );
		$this->assertSame( 1, french_typo_options_validate( array( 'narrow_space' => '1' ) )['narrow_space'] );
		$this->assertSame( 'off', french_typo_options_validate( array( 'language_restriction_mode' => 'bogus' ) )['language_restriction_mode'] );
		$this->assertSame( 'auto_fr', french_typo_options_validate( array( 'language_restriction_mode' => 'auto_fr' ) )['language_restriction_mode'] );
		$this->assertSame( array(), french_typo_options_validate( array( 'language_restriction_locales' => 'fr_FR' ) )['language_restriction_locales'] );
	}
}

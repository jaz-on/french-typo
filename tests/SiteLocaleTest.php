<?php
/**
 * Language restriction without Polylang falls back to the site locale.
 *
 * @package French_Typo
 */

class SiteLocaleTest extends FrenchTypoTestCase {

	private const INPUT = 'Bonjour : monde';

	protected function setUp(): void {
		parent::setUp();
		$this->assertFalse( function_exists( 'pll_get_post_language' ), 'This test must run without the Polylang stub.' );
		$GLOBALS['french_typo_test_options_override'] = array(
			'language_restriction_mode'    => 'auto_fr',
			'language_restriction_locales' => array(),
		);
	}

	public function test_french_site_locale_applies_the_rules(): void {
		$GLOBALS['french_typo_test_site_locale'] = 'fr_FR';
		$this->assertSame( 'Bonjour' . self::NBSP . ': monde', french_typo_replace( self::INPUT ) );
	}

	public function test_english_site_locale_leaves_the_text_alone(): void {
		$GLOBALS['french_typo_test_site_locale'] = 'en_US';
		$this->assertSame( self::INPUT, french_typo_replace( self::INPUT ) );
	}
}

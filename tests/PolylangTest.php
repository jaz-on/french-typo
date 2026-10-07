<?php
/**
 * Language restriction with Polylang. Runs in its own process because the Polylang
 * stubs define global functions that would change the no-Polylang tests.
 *
 * @package French_Typo
 */

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PolylangTest extends FrenchTypoTestCase {

	private const INPUT = 'Bonjour : monde';

	protected function setUp(): void {
		parent::setUp();
		require_once __DIR__ . '/polylang-stub.php';
		$GLOBALS['french_typo_test_current_post_id'] = 42;
	}

	/**
	 * @dataProvider restriction_provider
	 */
	public function test_restriction( string $mode, array $locales, string $post_locale, bool $applies ): void {
		$GLOBALS['french_typo_test_options_override']        = array(
			'language_restriction_mode'    => $mode,
			'language_restriction_locales' => $locales,
		);
		$GLOBALS['french_typo_test_polylang_post_locale']    = $post_locale;
		$GLOBALS['french_typo_test_polylang_current_locale'] = $post_locale;

		$this->assertSame(
			$applies ? 'Bonjour' . self::NBSP . ': monde' : self::INPUT,
			french_typo_replace( self::INPUT, 42 )
		);
	}

	public function restriction_provider(): array {
		return array(
			'off (backward compat) / en_US'      => array( 'off', array(), 'en_US', true ),
			'auto_fr / fr_FR'                    => array( 'auto_fr', array(), 'fr_FR', true ),
			'auto_fr / en_US'                    => array( 'auto_fr', array(), 'en_US', false ),
			'auto_fr / fr_BE'                    => array( 'auto_fr', array(), 'fr_BE', true ),
			'custom [fr_FR, fr_BE] / fr_FR'      => array( 'custom', array( 'fr_FR', 'fr_BE' ), 'fr_FR', true ),
			'custom [fr_FR] / fr_BE'             => array( 'custom', array( 'fr_FR' ), 'fr_BE', false ),
		);
	}
}

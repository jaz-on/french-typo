<?php
/**
 * Base test case: clears the per-test globals the bootstrap stubs read.
 *
 * @package French_Typo
 */

use PHPUnit\Framework\TestCase;

abstract class FrenchTypoTestCase extends TestCase {

	protected const NBSP = '&#160;';

	protected function setUp(): void {
		parent::setUp();
		$this->reset_globals();
	}

	protected function tearDown(): void {
		$this->reset_globals();
		parent::tearDown();
	}

	private function reset_globals(): void {
		foreach ( array(
			'french_typo_test_options_override',
			'french_typo_test_site_locale',
			'french_typo_test_available_languages',
			'french_typo_test_current_post_id',
			'french_typo_test_filter_results',
			'french_typo_test_polylang_post_locale',
			'french_typo_test_polylang_current_locale',
			'french_typo_test_polylang_languages',
			'french_typo_test_is_admin',
			'french_typo_test_hooks',
			'french_typo_test_store',
			'french_typo_test_writes',
			'french_typo_test_deleted',
			'french_typo_test_user_can',
			'french_typo_test_nonce_checked',
		) as $key ) {
			unset( $GLOBALS[ $key ] );
		}
	}
}

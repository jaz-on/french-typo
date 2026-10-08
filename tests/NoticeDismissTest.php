<?php
/**
 * Dismissing the multilingual notice, and saving the settings afterwards, must not touch other options.
 *
 * @package French_Typo
 */

class NoticeDismissTest extends FrenchTypoTestCase {

	protected function tearDown(): void {
		unset( $_GET['french_typo_dismiss_mlp_notice'] );
		parent::tearDown();
	}

	private function dismiss(): void {
		$GLOBALS['french_typo_test_is_admin']   = true;
		$_GET['french_typo_dismiss_mlp_notice'] = '1';
		french_typo_handle_mlp_notice_dismiss();
	}

	public function test_dismiss_checks_the_nonce_and_stores_the_flag_in_its_own_option(): void {
		$this->dismiss();

		$this->assertSame( array( 'french_typo_dismiss_mlp_notice' ), $GLOBALS['french_typo_test_nonce_checked'] );
		$this->assertTrue( $GLOBALS['french_typo_test_store']['french_typo_mlp_notice_dismissed'] );
	}

	/**
	 * On a site that never saved its settings the stored array is empty; rewriting it through the
	 * sanitize callback used to write every typography rule as off.
	 */
	public function test_dismiss_never_rewrites_the_settings_array(): void {
		$this->dismiss();

		$this->assertArrayNotHasKey( 'french_typo_options', $GLOBALS['french_typo_test_writes'] );
	}

	public function test_dismiss_is_refused_without_the_capability(): void {
		$GLOBALS['french_typo_test_user_can'] = false;
		$this->dismiss();

		$this->assertArrayNotHasKey( 'french_typo_mlp_notice_dismissed', $GLOBALS['french_typo_test_store'] ?? array() );
	}

	public function test_dismiss_does_nothing_without_the_query_argument(): void {
		$GLOBALS['french_typo_test_is_admin'] = true;
		french_typo_handle_mlp_notice_dismiss();

		$this->assertArrayNotHasKey( 'french_typo_mlp_notice_dismissed', $GLOBALS['french_typo_test_store'] ?? array() );
	}

	public function test_the_notice_counts_as_dismissed_from_either_location(): void {
		$this->assertFalse( french_typo_mlp_notice_dismissed( array( 'mlp_notice_dismissed' => false ) ) );
		$this->assertTrue( french_typo_mlp_notice_dismissed( array( 'mlp_notice_dismissed' => true ) ), 'flag stored before 1.2.5' );

		$GLOBALS['french_typo_test_store']['french_typo_mlp_notice_dismissed'] = true;
		$this->assertTrue( french_typo_mlp_notice_dismissed( array() ), 'flag stored since 1.2.5' );
	}

	/**
	 * The settings form has no field for the flag, so saving used to reset it and bring the notice back.
	 */
	public function test_saving_the_settings_keeps_a_flag_stored_before_1_2_5(): void {
		$GLOBALS['french_typo_test_options_override'] = array( 'mlp_notice_dismissed' => true );

		$saved = french_typo_options_validate( array( 'special_characters' => '1' ) );

		$this->assertTrue( $saved['mlp_notice_dismissed'] );
		$this->assertTrue( $saved['special_characters'] );
	}

	public function test_saving_the_settings_does_not_invent_the_flag(): void {
		$saved = french_typo_options_validate( array( 'special_characters' => '1' ) );

		$this->assertFalse( $saved['mlp_notice_dismissed'] );
	}
}

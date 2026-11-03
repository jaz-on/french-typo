<?php
/**
 * uninstall.php removes every option the plugin creates. Own process: it defines a constant.
 *
 * @package French_Typo
 */

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UninstallTest extends FrenchTypoTestCase {

	protected function setUp(): void {
		parent::setUp();
		if ( ! function_exists( 'is_multisite' ) ) {
			eval( 'function is_multisite() { return false; }' ); // phpcs:ignore Squiz.PHP.Eval.Discouraged
		}
	}

	public function test_it_does_nothing_when_not_called_by_wordpress(): void {
		$this->expectNotToPerformAssertions();
		// Without WP_UNINSTALL_PLUGIN the file exits; run it in a child PHP so the test process survives.
		$out = shell_exec( 'php -r ' . escapeshellarg( 'define("ABSPATH","/"); require ' . var_export( dirname( __DIR__ ) . '/uninstall.php', true ) . '; echo "REACHED";' ) );
		if ( false !== strpos( (string) $out, 'REACHED' ) ) {
			$this->fail( 'uninstall.php must exit when WP_UNINSTALL_PLUGIN is not defined.' );
		}
	}

	public function test_it_deletes_the_settings_and_the_notice_flag(): void {
		define( 'WP_UNINSTALL_PLUGIN', 'french-typo/french-typo.php' );
		$GLOBALS['french_typo_test_store'] = array(
			'french_typo_options'               => array( 'special_characters' => 1 ),
			'french_typo_mlp_notice_dismissed'  => true,
		);

		require dirname( __DIR__ ) . '/uninstall.php';

		$this->assertEqualsCanonicalizing(
			array( 'french_typo_options', 'french_typo_mlp_notice_dismissed' ),
			$GLOBALS['french_typo_test_deleted']
		);
		$this->assertSame( array(), $GLOBALS['french_typo_test_store'] );
	}
}

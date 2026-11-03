<?php
/**
 * The biography is typographed on the public site only, once, and never in the admin.
 *
 * @package French_Typo
 */

class UserMetaTest extends FrenchTypoTestCase {

	private const RAW = 'Bonjour : le monde !';

	private function read_bio() {
		$GLOBALS['french_typo_test_user_meta'] = array( 'description' => self::RAW );
		return french_typo_user_meta( null, 1, 'description', true, 'user' );
	}

	public function test_public_site_typographs_the_biography_once(): void {
		$expected = french_typo_replace( self::RAW );
		$this->assertNotSame( self::RAW, $expected );
		$this->assertSame( $expected, $this->read_bio() );
	}

	public function test_admin_leaves_the_biography_intact(): void {
		$GLOBALS['french_typo_test_is_admin'] = true;
		$this->assertNull( $this->read_bio(), 'null lets WordPress load the raw value' );
	}

	public function test_other_meta_keys_are_untouched(): void {
		$GLOBALS['french_typo_test_user_meta'] = array( 'nickname' => self::RAW );
		$this->assertNull( french_typo_user_meta( null, 1, 'nickname', true, 'user' ) );
	}
}

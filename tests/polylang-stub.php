<?php
/**
 * Polylang function stubs, loaded only by tests that need Polylang (run in a separate process).
 *
 * @package French_Typo
 */

if ( ! function_exists( 'pll_get_post_language' ) ) {
	/**
	 * @param int    $post_id Post id (ignored by the stub).
	 * @param string $field   'slug' | 'locale' | ...
	 * @return string
	 */
	function pll_get_post_language( $post_id, $field = 'slug' ) { // phpcs:ignore
		if ( 'locale' === $field && isset( $GLOBALS['french_typo_test_polylang_post_locale'] ) ) {
			return (string) $GLOBALS['french_typo_test_polylang_post_locale'];
		}
		return '';
	}
}
if ( ! function_exists( 'pll_current_language' ) ) {
	/**
	 * @param string $field 'slug' | 'locale' | ...
	 * @return string
	 */
	function pll_current_language( $field = 'slug' ) { // phpcs:ignore
		if ( 'locale' === $field && isset( $GLOBALS['french_typo_test_polylang_current_locale'] ) ) {
			return (string) $GLOBALS['french_typo_test_polylang_current_locale'];
		}
		return '';
	}
}
if ( ! function_exists( 'pll_languages_list' ) ) {
	/**
	 * @param array $args Polylang args.
	 * @return array
	 */
	function pll_languages_list( $args = array() ) { // phpcs:ignore
		return isset( $GLOBALS['french_typo_test_polylang_languages'] ) && is_array( $GLOBALS['french_typo_test_polylang_languages'] )
			? $GLOBALS['french_typo_test_polylang_languages']
			: array( 'fr_FR', 'en_US' );
	}
}

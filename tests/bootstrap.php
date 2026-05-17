<?php
/**
 * Minimal WordPress stubs to load french-typo.php and run replace() tests.
 *
 * @package French_Typo
 */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require __DIR__ . '/wp-html-split-wpstub.php';

if ( ! function_exists( 'add_action' ) ) {
	/**
	 * @param mixed ...$args Ignored.
	 */
	function add_action( ...$args ) { // phpcs:ignore
	}
}

if ( ! function_exists( 'add_filter' ) ) {
	/**
	 * @param mixed ...$args Ignored.
	 */
	function add_filter( ...$args ) { // phpcs:ignore
	}
}

if ( ! function_exists( 'get_option' ) ) {
	/**
	 * @param string $option  Option name.
	 * @param mixed  $default Default.
	 * @return mixed
	 */
	function get_option( $option, $default = false ) { // phpcs:ignore
		if ( 'french_typo_options' === $option ) {
			$opts = array(
				'narrow_space'          => 1,
				'special_characters'    => 1,
				'ordinal_abbreviations' => true,
			);
			if ( defined( 'FRENCH_TYPO_TEST_ORDINAL_ABBREV_OFF' ) && FRENCH_TYPO_TEST_ORDINAL_ABBREV_OFF ) {
				$opts['ordinal_abbreviations'] = false;
			}
			if ( defined( 'FRENCH_TYPO_TEST_ORDINAL_ONLY' ) && FRENCH_TYPO_TEST_ORDINAL_ONLY ) {
				$opts['narrow_space']          = 0;
				$opts['special_characters']    = 0;
				$opts['ordinal_abbreviations'] = true;
			}
			if ( isset( $GLOBALS['french_typo_test_options_override'] ) && is_array( $GLOBALS['french_typo_test_options_override'] ) ) {
				$opts = array_merge( $opts, $GLOBALS['french_typo_test_options_override'] );
			}
			return $opts;
		}
		return $default;
	}
}

if ( ! function_exists( 'get_locale' ) ) {
	/**
	 * @return string
	 */
	function get_locale() { // phpcs:ignore
		if ( isset( $GLOBALS['french_typo_test_site_locale'] ) ) {
			return (string) $GLOBALS['french_typo_test_site_locale'];
		}
		return 'fr_FR';
	}
}

if ( ! function_exists( 'get_available_languages' ) ) {
	/**
	 * @return array
	 */
	function get_available_languages() { // phpcs:ignore
		return isset( $GLOBALS['french_typo_test_available_languages'] ) && is_array( $GLOBALS['french_typo_test_available_languages'] )
			? $GLOBALS['french_typo_test_available_languages']
			: array();
	}
}

if ( ! function_exists( 'get_the_ID' ) ) {
	/**
	 * @return int
	 */
	function get_the_ID() { // phpcs:ignore
		return isset( $GLOBALS['french_typo_test_current_post_id'] )
			? (int) $GLOBALS['french_typo_test_current_post_id']
			: 0;
	}
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
	/**
	 * @param mixed $value Raw value.
	 * @return string
	 */
	function sanitize_text_field( $value ) { // phpcs:ignore
		return trim( wp_strip_all_tags_basic( (string) $value ) );
	}
	/**
	 * Minimal strip tags helper for the stub above.
	 *
	 * @param string $str Input.
	 * @return string
	 */
	function wp_strip_all_tags_basic( $str ) { // phpcs:ignore
		return preg_replace( '#<[^>]*>#', '', $str );
	}
}

if ( ! function_exists( 'apply_filters' ) ) {
	/**
	 * @param string $hook  Hook name.
	 * @param mixed  $value Value.
	 * @return mixed
	 */
	function apply_filters( $hook, $value = null ) { // phpcs:ignore
		// Allow tests to inject filter results via globals when needed.
		if ( isset( $GLOBALS['french_typo_test_filter_results'][ $hook ] ) ) {
			return $GLOBALS['french_typo_test_filter_results'][ $hook ];
		}
		return $value;
	}
}

// Polylang stubs: only registered when a test asks for them (so the absence-of-Polylang case stays clean).
if ( defined( 'FRENCH_TYPO_TEST_STUB_POLYLANG' ) && FRENCH_TYPO_TEST_STUB_POLYLANG ) {
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
}

if ( ! function_exists( 'wp_parse_args' ) ) {
	/**
	 * @param array|object $args    Arguments.
	 * @param array        $defaults Defaults.
	 * @return array
	 */
	function wp_parse_args( $args, $defaults = array() ) { // phpcs:ignore
		if ( is_array( $defaults ) ) {
			return array_merge( $defaults, (array) $args );
		}

		return (array) $args;
	}
}

require dirname( __DIR__ ) . '/french-typo.php';

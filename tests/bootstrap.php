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

// Cache-free runs: every test sees its own options/locale overrides.
define( 'FRENCH_TYPO_TEST_NO_OPTIONS_CACHE', true );
define( 'FRENCH_TYPO_TEST_NO_LOCALE_CACHE', true );

require_once __DIR__ . '/FrenchTypoTestCase.php';

if ( ! function_exists( 'add_filter' ) ) {
	/**
	 * Records the registration so tests can assert hooks, priorities and argument counts.
	 *
	 * @param string   $hook          Hook name.
	 * @param callable $callback      Callback.
	 * @param int      $priority      Priority.
	 * @param int      $accepted_args Accepted arguments.
	 * @return true
	 */
	function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) { // phpcs:ignore
		$GLOBALS['french_typo_test_hooks'][] = array(
			'hook'          => $hook,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		);
		return true;
	}
}

if ( ! function_exists( 'add_action' ) ) {
	/**
	 * Actions are filters in WordPress; same recording.
	 *
	 * @param string   $hook          Hook name.
	 * @param callable $callback      Callback.
	 * @param int      $priority      Priority.
	 * @param int      $accepted_args Accepted arguments.
	 * @return true
	 */
	function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) { // phpcs:ignore
		return add_filter( $hook, $callback, $priority, $accepted_args );
	}
}

if ( ! function_exists( 'is_admin' ) ) {
	/**
	 * @return bool
	 */
	function is_admin() { // phpcs:ignore
		return ! empty( $GLOBALS['french_typo_test_is_admin'] );
	}
}

if ( ! function_exists( 'plugin_basename' ) ) {
	/**
	 * @param string $file File path.
	 * @return string
	 */
	function plugin_basename( $file ) { // phpcs:ignore
		return 'french-typo/' . basename( $file );
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

// Hooks registered while the plugin file loads (before any test resets the recorder).
$GLOBALS['french_typo_test_load_hooks'] = isset( $GLOBALS['french_typo_test_hooks'] ) ? $GLOBALS['french_typo_test_hooks'] : array();

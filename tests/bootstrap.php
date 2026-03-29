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
	 * @param string $option    Option name.
	 * @param mixed  $default Default.
	 * @return mixed
	 */
	function get_option( $option, $default = false ) { // phpcs:ignore
		if ( 'french_typo_options' === $option ) {
			return array(
				'narrow_space'       => 1,
				'special_characters' => 1,
			);
		}
		return $default;
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

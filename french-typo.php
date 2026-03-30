<?php
/**
 * Plugin Name: French Typo
 * Plugin URI: https://github.com/jaz-on/french-typo
 * Description: Automatically applies French typography rules to your content: non-breaking spaces before punctuation marks (; : ! ? % « »), optional French ordinal abbreviations (e.g. 1ère → 1re, 3ème → 3e), and special character replacements ((c) → ©, (r) → ®, (tm)/(TM) → ™).
 * Version: 1.2.1
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Tested up to: 7.0
 * Author: Jason Rouet
 * Author URI: https://profiles.wordpress.org/jaz_on/
 * Contributors: jaz_on, audrasjb, juliobox, beryldlg
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: french-typo
 * Domain Path: /languages
 * GitHub Plugin URI: jaz-on/french-typo
 * GitHub Branch: main
 * Primary Branch: main
 * Release Asset: false
 *
 * @package French_Typo
 */

// Security check: prevent direct access to the file.
defined( 'ABSPATH' ) || die( 'Silence is golden.' );

define( 'FRENCH_TYPO_VERSION', '1.2.1' );

/**
 * Load plugin text domain for translations.
 *
 * @since 1.2.0
 */
function french_typo_load_textdomain() {
	load_plugin_textdomain(
		'french-typo',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'init', 'french_typo_load_textdomain', 0 );

/**
 * Initialize plugin hooks.
 *
 * Sets up filters for content processing and admin menu if in admin area.
 *
 * @since 1.0.0
 */
function french_typo_hooks() {
	// Apply typography rules to post titles and content.
	add_filter( 'the_title', 'french_typo_replace_wrapper' );
	add_filter( 'the_content', 'french_typo_replace_wrapper' );
	add_filter( 'the_excerpt', 'french_typo_replace_wrapper' );

	// Apply to widgets.
	add_filter( 'widget_text', 'french_typo_replace_wrapper' );
	add_filter( 'widget_text_content', 'french_typo_replace_wrapper' );
	add_filter( 'widget_block_content', 'french_typo_replace_wrapper' );
	add_filter( 'widget_title', 'french_typo_replace_wrapper' );
	add_filter( 'widget_text_title', 'french_typo_replace_wrapper' );
	add_filter( 'widget_block_title', 'french_typo_replace_wrapper' );

	// Apply to menu items.
	add_filter( 'wp_nav_menu_items', 'french_typo_replace_wrapper' );

	// Apply to taxonomies (categories, tags, custom taxonomies).
	add_filter( 'term_description', 'french_typo_replace_wrapper' );
	add_filter( 'single_term_title', 'french_typo_replace_wrapper' );
	add_filter( 'single_cat_title', 'french_typo_replace_wrapper' );
	add_filter( 'single_tag_title', 'french_typo_replace_wrapper' );
	add_filter( 'single_post_type_archive_title', 'french_typo_replace_wrapper' );

	// Apply to archive titles and descriptions.
	add_filter( 'get_the_archive_title', 'french_typo_replace_wrapper' );
	add_filter( 'get_the_archive_description', 'french_typo_replace_wrapper' );

	// Apply to comments.
	add_filter( 'comment_text', 'french_typo_replace_wrapper' );
	add_filter( 'get_comment_author', 'french_typo_replace_wrapper' );

	// Support for Advanced Custom Fields (ACF).
	if ( function_exists( 'get_field' ) ) {
		add_filter( 'acf/format_value/type=text', 'french_typo_replace_custom_field' );
		add_filter( 'acf/format_value/type=textarea', 'french_typo_replace_custom_field' );
		add_filter( 'acf/format_value/type=wysiwyg', 'french_typo_replace_custom_field' );
	}

	// Support for Meta Box plugin.
	if ( function_exists( 'rwmb_get_value' ) ) {
		add_filter( 'rwmb_the_value', 'french_typo_replace_custom_field' );
	}

	// Apply to RSS feeds.
	add_filter( 'the_title_rss', 'french_typo_replace_rss_title' );
	add_filter( 'the_content_feed', 'french_typo_replace_rss_content' );
	add_filter( 'the_excerpt_rss', 'french_typo_replace_rss_excerpt' );
	add_filter( 'comment_text_rss', 'french_typo_replace_rss_comment' );

	// Apply to REST API responses.
	add_filter( 'rest_prepare_post', 'french_typo_rest_api_post' );
	add_filter( 'rest_prepare_page', 'french_typo_rest_api_post' );
	add_filter( 'rest_prepare_attachment', 'french_typo_rest_api_post' );

	// Apply to user profiles.
	add_filter( 'get_the_author_description', 'french_typo_replace_wrapper' );
	add_filter( 'get_user_metadata', 'french_typo_user_meta', 10, 5 );

	// Apply to breadcrumbs (Yoast, Rank Math, SEOPress).
	if ( defined( 'WPSEO_VERSION' ) ) {
		add_filter( 'wpseo_breadcrumb_links', 'french_typo_breadcrumbs' );
	}
	if ( defined( 'RANK_MATH_VERSION' ) ) {
		add_filter( 'rank_math/frontend/breadcrumb/items', 'french_typo_breadcrumbs' );
	}
	if ( defined( 'SEOPRESS_VERSION' ) ) {
		add_filter( 'seopress_breadcrumbs_items', 'french_typo_breadcrumbs' );
	}

	// Support for SEO plugins meta descriptions.
	// Yoast SEO.
	if ( defined( 'WPSEO_VERSION' ) ) {
		add_filter( 'wpseo_metadesc', 'french_typo_replace' );
		add_filter( 'wpseo_title', 'french_typo_replace' );
		add_filter( 'wpseo_opengraph_title', 'french_typo_replace' );
		add_filter( 'wpseo_opengraph_desc', 'french_typo_replace' );
		add_filter( 'wpseo_twitter_title', 'french_typo_replace' );
		add_filter( 'wpseo_twitter_description', 'french_typo_replace' );
	}
	// Rank Math.
	if ( defined( 'RANK_MATH_VERSION' ) ) {
		add_filter( 'rank_math/frontend/title', 'french_typo_replace' );
		add_filter( 'rank_math/frontend/description', 'french_typo_replace' );
		add_filter( 'rank_math/opengraph/title', 'french_typo_replace' );
		add_filter( 'rank_math/opengraph/description', 'french_typo_replace' );
		add_filter( 'rank_math/twitter/title', 'french_typo_replace' );
		add_filter( 'rank_math/twitter/description', 'french_typo_replace' );
	}
	// SEOPress.
	if ( defined( 'SEOPRESS_VERSION' ) ) {
		add_filter( 'seopress_titles_title', 'french_typo_replace' );
		add_filter( 'seopress_titles_desc', 'french_typo_replace' );
		add_filter( 'seopress_social_og_title', 'french_typo_replace' );
		add_filter( 'seopress_social_og_desc', 'french_typo_replace' );
		add_filter( 'seopress_social_twitter_title', 'french_typo_replace' );
		add_filter( 'seopress_social_twitter_desc', 'french_typo_replace' );
	}

	// Generic filter for custom fields and other content.
	add_filter( 'french_typo_process_text', 'french_typo_replace' );

	// Only load admin functionality when in WordPress admin area.
	if ( is_admin() ) {
		add_action( 'admin_menu', 'french_typo_admin_menu' );
		add_action( 'admin_init', 'french_typo_admin_init' );
		add_action( 'admin_enqueue_scripts', 'french_typo_admin_enqueue_scripts' );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'french_typo_action_links' );
		add_filter( 'plugin_row_meta', 'french_typo_plugin_row_meta', 10, 2 );
	}
}
add_action( 'init', 'french_typo_hooks' );

/**
 * Get plugin options with static cache and processed values.
 *
 * Retrieves plugin options from database, processes them, and caches them in memory
 * to avoid repeated processing. WordPress already caches options via get_option().
 *
 * @since 1.0.0
 *
 * @return array Processed plugin options array with ready-to-use values.
 */
function french_typo_get_options() {
	static $cached_processed_options = null;

	// If cache is empty, load and process options.
	if ( null === $cached_processed_options ) {
		$raw_options = get_option( 'french_typo_options', array() );

		// Default values for all options.
		$defaults = array(
			'narrow_space'           => false,
			'special_characters'     => true,
			'apply_to_titles'        => true,
			'apply_to_content'       => true,
			'apply_to_excerpts'      => true,
			'apply_to_widgets'       => true,
			'apply_to_menus'         => true,
			'apply_to_custom_fields' => true,
			'apply_to_taxonomies'    => true,
			'apply_to_archives'      => true,
			'apply_to_comments'      => true,
			'apply_to_rss'           => true,
			'apply_to_rest_api'      => true,
			'apply_to_user_profiles' => true,
			'apply_to_breadcrumbs'   => true,
			'ordinal_abbreviations'  => true,
		);

		// Merge raw options with defaults using wp_parse_args().
		$cached_processed_options = wp_parse_args( $raw_options, $defaults );
		if ( $cached_processed_options['narrow_space'] ) {
			$cached_processed_options['narrow_space'] = 1 === $cached_processed_options['narrow_space'] ? '&#160;' : '&#8239;';
		} else {
			$cached_processed_options['narrow_space'] = false;
		}
	}
	return $cached_processed_options;
}

/**
 * Normalize HTML tag name to local lowercase (strip XML / SVG prefix).
 *
 * @since 1.2.0
 *
 * @param string $name Raw tag name from markup.
 * @return string Local tag name.
 */
function french_typo_markup_tag_local_name( $name ) {
	$name = strtolower( $name );
	if ( false !== strpos( $name, ':' ) ) {
		$parts = explode( ':', $name );
		return end( $parts );
	}
	return $name;
}

/**
 * Whether a pre opening tag is Gutenberg Verse without the Code block class.
 *
 * @since 1.2.0
 *
 * @param string $attr_region Text between the tag name and the closing '>'.
 * @return bool True when typography should still run inside (do not push pre on stack).
 */
function french_typo_markup_pre_is_verse_not_code( $attr_region ) {
	if ( ! preg_match( '#\bclass\s*=\s*("|\')([^"\']*)\1#iu', $attr_region, $m ) ) {
		return false;
	}
	$classes = $m[2];
	if ( ! preg_match( '#\bwp-block-verse\b#iu', $classes ) ) {
		return false;
	}
	if ( preg_match( '#\bwp-block-code\b#iu', $classes ) ) {
		return false;
	}
	return true;
}

/**
 * Update stack of raw-text elements (script, style, pre, code, textarea) from one wp_html_split() tag segment.
 *
 * Malformed HTML: closing tag pops only when it matches the stack top (strict LIFO).
 *
 * @since 1.2.0
 *
 * @param array  $stack   Stack of open raw tag local names (modified by reference).
 * @param string $segment Full tag segment starting with '<'.
 */
function french_typo_markup_stack_update( array &$stack, $segment ) {
	static $raw_flip  = null;
	static $void_flip = null;

	if ( null === $raw_flip ) {
		$raw_flip  = array_flip( array( 'script', 'style', 'pre', 'code', 'textarea' ) );
		$void_flip = array_flip(
			array(
				'area',
				'base',
				'br',
				'col',
				'embed',
				'hr',
				'img',
				'input',
				'link',
				'meta',
				'param',
				'source',
				'track',
				'wbr',
			)
		);
	}

	$events = array();

	if ( preg_match_all( '#</\s*([A-Za-z0-9:]+)\s*>#u', $segment, $m, PREG_OFFSET_CAPTURE ) ) {
		foreach ( $m[0] as $i => $hit ) {
			$nm = french_typo_markup_tag_local_name( $m[1][ $i ][0] );
			if ( isset( $raw_flip[ $nm ] ) ) {
				$events[ $hit[1] ] = array( 'close', $nm );
			}
		}
	}

	if ( preg_match_all( '#<\s*(?!/)([A-Za-z0-9:]+)([^>]*)>#u', $segment, $m, PREG_OFFSET_CAPTURE ) ) {
		foreach ( $m[0] as $i => $hit ) {
			$nm   = french_typo_markup_tag_local_name( $m[1][ $i ][0] );
			$rest = $m[2][ $i ][0];
			if ( ! isset( $raw_flip[ $nm ] ) ) {
				continue;
			}
			if ( isset( $void_flip[ $nm ] ) ) {
				continue;
			}
			if ( preg_match( '#/\s*$#u', $rest ) ) {
				continue;
			}
			if ( 'pre' === $nm && french_typo_markup_pre_is_verse_not_code( $rest ) ) {
				continue;
			}
			$events[ $hit[1] ] = array( 'open', $nm );
		}
	}

	ksort( $events, SORT_NUMERIC );
	foreach ( $events as $ev ) {
		if ( 'close' === $ev[0] ) {
			if ( ! empty( $stack ) && end( $stack ) === $ev[1] ) {
				array_pop( $stack );
			}
		} else {
			$stack[] = $ev[1];
		}
	}
}

/**
 * Generic wrapper function for French typo filters.
 * Uses current_filter() to determine which option to check.
 * Optimized for performance - no closures, single function for all simple filters.
 *
 * @since 1.1
 *
 * @param string $text The text to process.
 * @return string The processed text.
 */
function french_typo_replace_wrapper( $text ) {
	// Map hooks to option keys (static for performance).
	static $hook_option_map = array(
		'the_title'                      => 'apply_to_titles',
		'the_content'                    => 'apply_to_content',
		'the_excerpt'                    => 'apply_to_excerpts',
		'widget_text'                    => 'apply_to_widgets',
		'widget_text_content'            => 'apply_to_widgets',
		'widget_block_content'           => 'apply_to_widgets',
		'widget_title'                   => 'apply_to_widgets',
		'widget_text_title'              => 'apply_to_widgets',
		'widget_block_title'             => 'apply_to_widgets',
		'wp_nav_menu_items'              => 'apply_to_menus',
		'term_description'               => 'apply_to_taxonomies',
		'single_term_title'              => 'apply_to_taxonomies',
		'single_cat_title'               => 'apply_to_taxonomies',
		'single_tag_title'               => 'apply_to_taxonomies',
		'single_post_type_archive_title' => 'apply_to_taxonomies',
		'get_the_archive_title'          => 'apply_to_archives',
		'get_the_archive_description'    => 'apply_to_archives',
		'comment_text'                   => 'apply_to_comments',
		'get_comment_author'             => 'apply_to_comments',
		'get_the_author_description'     => 'apply_to_user_profiles',
	);
	// Get the option key for this hook.
	$option_key = $hook_option_map[ current_filter() ] ?? null;

	// Check if processing is enabled for this content type.
	$options = french_typo_get_options();
	if ( $options[ $option_key ] ) {
		return french_typo_replace( $text );
	}

	return $text;
}

/**
 * Apply French typography rules to custom fields.
 *
 * Wrapper function that checks if custom field processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param mixed  $value     The custom field value to process.
 * @return mixed The processed custom field value.
 */
function french_typo_replace_custom_field( $value ) {
	// Only process string values.
	if ( ! is_string( $value ) ) {
		return $value;
	}

	$options = french_typo_get_options();
	if ( $options['apply_to_custom_fields'] ) {
		return french_typo_replace( $value );
	}
	return $value;
}


/**
 * Apply French typography rules to RSS feed titles.
 *
 * Wrapper function that checks if RSS processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The RSS title text to process.
 * @return string The processed RSS title text.
 */
function french_typo_replace_rss_title( $text ) {
	$options = french_typo_get_options();
	if ( $options['apply_to_rss'] && $options['apply_to_titles'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to RSS feed content.
 *
 * Wrapper function that checks if RSS processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The RSS content text to process.
 * @return string The processed RSS content text.
 */
function french_typo_replace_rss_content( $text ) {
	$options = french_typo_get_options();
	if ( $options['apply_to_rss'] && $options['apply_to_content'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to RSS feed excerpts.
 *
 * Wrapper function that checks if RSS processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The RSS excerpt text to process.
 * @return string The processed RSS excerpt text.
 */
function french_typo_replace_rss_excerpt( $text ) {
	$options = french_typo_get_options();
	if ( $options['apply_to_rss'] && $options['apply_to_excerpts'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to RSS feed comments.
 *
 * Wrapper function that checks if RSS processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The RSS comment text to process.
 * @return string The processed RSS comment text.
 */
function french_typo_replace_rss_comment( $text ) {
	$options = french_typo_get_options();
	if ( $options['apply_to_rss'] && $options['apply_to_comments'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to REST API responses.
 *
 * Processes post/page/attachment data in REST API responses.
 *
 * @since 1.0.0
 *
 * @param WP_REST_Response $response  The response object.
 * @return WP_REST_Response Modified response object.
 */
function french_typo_rest_api_post( $response ) {
	// Parameters $_post and $_request are required by filter hooks but not used.
	$options = french_typo_get_options();

	if ( $options['apply_to_rest_api'] ) {
		$data = $response->get_data();

		// Process title if enabled.
		if ( isset( $data['title']['rendered'] ) && $options['apply_to_titles'] ) {
			$data['title']['rendered'] = french_typo_replace( $data['title']['rendered'] );
		}

		// Process content if enabled.
		if ( isset( $data['content']['rendered'] ) && $options['apply_to_content'] ) {
			$data['content']['rendered'] = french_typo_replace( $data['content']['rendered'] );
		}

		// Process excerpt if enabled.
		if ( isset( $data['excerpt']['rendered'] ) && $options['apply_to_excerpts'] ) {
			$data['excerpt']['rendered'] = french_typo_replace( $data['excerpt']['rendered'] );
		}

		$response->set_data( $data );
	}

	return $response;
}


/**
 * Apply French typography rules to user metadata (biography).
 *
 * Hooks `get_user_metadata`, fetches the raw value without recursion, then returns
 * the processed value when the biography (`description`) field is read.
 *
 * @since 1.0.0
 *
 * @param mixed  $pre_value  Value to return if short-circuiting, or null to continue.
 * @param int    $object_id  User ID.
 * @param string $meta_key   Meta key.
 * @param bool   $single     Whether to return a single value.
 * @param string $meta_type  Object type (must be `user`).
 * @return mixed Filtered meta, or $pre_value to let WordPress load meta normally.
 */
function french_typo_user_meta( $pre_value, $object_id, $meta_key, $single, $meta_type ) {
	if ( 'user' !== $meta_type || 'description' !== $meta_key ) {
		return $pre_value;
	}

	$options = french_typo_get_options();
	if ( ! $options['apply_to_user_profiles'] ) {
		return $pre_value;
	}

	remove_filter( 'get_user_metadata', 'french_typo_user_meta', 10 );
	$value = get_user_meta( $object_id, $meta_key, $single );
	add_filter( 'get_user_metadata', 'french_typo_user_meta', 10, 5 );

	if ( is_string( $value ) ) {
		return french_typo_replace( $value );
	}

	if ( is_array( $value ) ) {
		return array_map( 'french_typo_replace', $value );
	}

	return $pre_value;
}

/**
 * Apply French typography rules to breadcrumbs.
 *
 * Processes breadcrumb items from SEO plugins.
 *
 * @since 1.0.0
 *
 * @param array $items Array of breadcrumb items.
 * @return array Modified array of breadcrumb items.
 */
function french_typo_breadcrumbs( $items ) {
	$options = french_typo_get_options();
	if ( $options['apply_to_breadcrumbs'] ) {
		foreach ( $items as $key => $item ) {
			if ( isset( $item['text'] ) && is_string( $item['text'] ) ) {
				$items[ $key ]['text'] = french_typo_replace( $item['text'] );
			}
			if ( isset( $item['title'] ) && is_string( $item['title'] ) ) {
				$items[ $key ]['title'] = french_typo_replace( $item['title'] );
			}
		}
	}
	return $items;
}

/**
 * Normalize French ordinal abbreviations in a plain-text segment (1ère → 1re, 3ème → 3e, n-ième → nième).
 *
 * English forms (1st, 2nd) and non-standard 1ème are left unchanged. Uses UTF-8-safe patterns.
 *
 * @since 1.2.0
 *
 * @param string $segment Text segment (no HTML tags).
 * @return string Segment with ordinal abbreviations normalized.
 */
function french_typo_apply_ordinal_abbreviations( $segment ) {
	if ( '' === $segment || mb_strlen( $segment ) < 3 ) {
		return $segment;
	}

	// Indefinite ordinals: n-ième / x-ième (hyphen, non-breaking hyphen, en dash).
	$segment = preg_replace_callback(
		'#(?<![\p{L}\p{N}])([nNxX])([\-\x{2011}\x{2013}])(?i)ième(?![\p{L}\p{N}])#u',
		static function ( $m ) {
			return $m[1] . 'ième';
		},
		$segment
	);

	// Feminine first: 1ère → 1re (not 11ère, 21ère, etc.).
	$segment = preg_replace_callback(
		'#(?<![0-9])1(?![0-9])((?i)ère)#u',
		static function ( $m ) {
			$s       = $m[1];
			$first   = mb_substr( $s, 0, 1, 'UTF-8' );
			$last    = mb_substr( $s, 2, 1, 'UTF-8' );
			$r_upper = ( mb_strtoupper( $first, 'UTF-8' ) === $first && preg_match( '/\p{L}/u', $first ) );
			$r_char  = $r_upper ? 'R' : 'r';
			$e_char  = ( mb_strtoupper( $last, 'UTF-8' ) === $last && preg_match( '/\p{L}/u', $last ) ) ? 'E' : 'e';
			return '1' . $r_char . $e_char;
		},
		$segment
	);

	// Abbreviated ordinals ≥ 2: Nème → Ne for N in 2–999 (single digit 2–9 or 10–999).
	$segment = preg_replace_callback(
		'#(?<![0-9])((?:[2-9]|[1-9][0-9]{1,2}))(?i)(ème)(?![0-9])#u',
		static function ( $m ) {
			$num  = $m[1];
			$suf  = $m[2];
			$last = mb_substr( $suf, -1, 1, 'UTF-8' );
			$e_ch = ( mb_strtoupper( $last, 'UTF-8' ) === $last && preg_match( '/\p{L}/u', $last ) ) ? 'E' : 'e';
			return $num . $e_ch;
		},
		$segment
	);

	return $segment;
}

/**
 * Apply French typography rules to text content.
 *
 * Processes text to add non-breaking spaces before/after punctuation and replaces
 * special character sequences. Optionally normalizes French ordinal abbreviations (1ère → 1re, etc.).
 * Skips HTML tag segments, shortcode segments (leading `[`),
 * and raw text inside script, style, pre, code, and textarea (stack-aware, including nesting).
 *
 * @since 1.0.0
 *
 * @param string $text The text content to process.
 * @return string The processed text with French typography rules applied.
 */
function french_typo_replace( $text ) {
	static $cache = array();

	// Early return for empty or very short text to avoid unnecessary processing.
	if ( ! is_string( $text ) || mb_strlen( $text ) < 3 ) {
		return $text;
	}

	$cache_max_size  = 50; // Limit cache to 50 entries to prevent memory issues.
	$cache_threshold = 100; // Only cache texts longer than this.

	$text_length = mb_strlen( $text );
	$use_cache   = ( $text_length >= $cache_threshold );
	$cache_key   = null;

	// Get processed plugin options (already cached and ready to use).
	$options = french_typo_get_options();

	$has_typo = $options['narrow_space'] || $options['special_characters'] || ! empty( $options['ordinal_abbreviations'] );
	if ( ! $has_typo ) {
		return $text;
	}

	$narrow_key  = $options['narrow_space'] ? (string) crc32( (string) $options['narrow_space'] ) : '0';
	$special_key = $options['special_characters'] ? '1' : '0';
	$ordinal_key = ! empty( $options['ordinal_abbreviations'] ) ? '1' : '0';

	if ( $use_cache ) {
		$cache_key = crc32( $text ) . '_' . $text_length . '_' . $narrow_key . '_' . $special_key . '_' . $ordinal_key;

		if ( isset( $cache[ $cache_key ] ) ) {
			return $cache[ $cache_key ];
		}
	}

	static $static_replacements = array(
		'(TM)' => '&#8482;',
		'(c)'  => '&#169;',
		'(r)'  => '&#174;',
		'(tm)' => '&#8482;',
	);

	$nbs        = $options['narrow_space'] ? $options['narrow_space'] : '';
	$nbs_quoted = $options['narrow_space'] ? preg_quote( $options['narrow_space'], '#' ) : '';

	$has_markup = ( false !== strpos( $text, '<' ) || false !== strpos( $text, '[' ) );

	if ( $has_markup ) {
		// Protect HTML entities before narrow-space regexes (unchanged; only when NBSP rules run).
		$entities     = array();
		$placeholders = array();
		if ( $options['narrow_space'] && false !== strpos( $text, '&' ) ) {
			preg_match_all( '/&#?[a-zA-Z0-9]{1,31};/', $text, $matches );
			if ( ! empty( $matches[0] ) ) {
				$entities = array_unique( $matches[0] );
				foreach ( $entities as $index => $entity ) {
					$placeholders[] = '___FT_ENT_' . $index . '___';
				}
				$text = str_replace( $entities, $placeholders, $text );
			}
		}

		$segments  = wp_html_split( $text );
		$processed = '';
		$stack     = array();

		foreach ( $segments as $segment ) {
			if ( ! empty( $segment ) && '<' === $segment[0] ) {
				french_typo_markup_stack_update( $stack, $segment );
			}
			if ( ! empty( $segment ) && '<' !== $segment[0] && '[' !== $segment[0] ) {
				if ( empty( $stack ) ) {
					if ( $options['special_characters'] ) {
						$segment = strtr( $segment, $static_replacements );
					}
					if ( ! empty( $options['ordinal_abbreviations'] ) ) {
						$segment = french_typo_apply_ordinal_abbreviations( $segment );
					}
					if ( $options['narrow_space'] ) {
						// Add non-breaking space before punctuation (avoid if already exists).
						$segment = preg_replace( '#(?<!' . $nbs_quoted . ')\s*([?!:;%»])(?!\w)(?!/{2})#u', $nbs . '$1', $segment );
						// Add non-breaking space after « (avoid if already exists).
						$segment = preg_replace( '#([«])(?!' . $nbs_quoted . ')\s*#u', '$1' . $nbs, $segment );
					}
				}
			}
			$processed .= $segment;
		}
		$text = $processed;

		if ( ! empty( $entities ) ) {
			$text = str_replace( $placeholders, $entities, $text );
		}
	} else {
		if ( $options['special_characters'] ) {
			$text = strtr( $text, $static_replacements );
		}
		if ( ! empty( $options['ordinal_abbreviations'] ) ) {
			$text = french_typo_apply_ordinal_abbreviations( $text );
		}
		if ( $options['narrow_space'] ) {
			// Plain text: process directly without splitting.
			// Add non-breaking space before punctuation (avoid if already exists).
			$text = preg_replace( '#(?<!' . $nbs_quoted . ')\s*([?!:;%»])(?!\w)(?!/{2})#u', $nbs . '$1', $text );
			// Add non-breaking space after « (avoid if already exists).
			$text = preg_replace( '#([«])(?!' . $nbs_quoted . ')\s*#u', '$1' . $nbs, $text );
		}
	}

	if ( $use_cache ) {
		if ( count( $cache ) >= $cache_max_size ) {
			$cache = array_slice( $cache, 10, null, true );
		}

		$cache[ $cache_key ] = $text;
	}

	return $text;
}

/**
 * Add settings page to WordPress admin menu.
 *
 * @since 1.0.0
 */
function french_typo_admin_menu() {
	add_options_page(
		__( 'French Typo Settings', 'french-typo' ),
		__( 'French Typo', 'french-typo' ),
		'manage_options',
		'french-typo',
		'french_typo_admin_options'
	);
}

/**
 * Enqueue admin styles for settings page.
 *
 * @since 1.0.0
 *
 * @param string $hook_suffix Current admin page hook suffix.
 */
function french_typo_admin_enqueue_scripts( $hook_suffix ) {
	// Only load CSS on the plugin's settings page.
	if ( 'settings_page_french-typo' !== $hook_suffix ) {
		return;
	}

	$css_file = plugin_dir_path( __FILE__ ) . 'admin.css';
	$version  = file_exists( $css_file ) ? filemtime( $css_file ) : '1.0.0';

	wp_enqueue_style(
		'french-typo-admin',
		plugin_dir_url( __FILE__ ) . 'admin.css',
		array(),
		$version
	);
}

/**
 * Add settings link to plugin action links.
 *
 * @since 1.0.0
 *
 * @param array $links Existing plugin action links.
 * @return array Modified plugin action links.
 */
function french_typo_action_links( $links ) {
	$settings_link = sprintf(
		'<a href="%s">%s</a>',
		esc_url( admin_url( 'options-general.php?page=french-typo' ) ),
		esc_html__( 'Settings', 'french-typo' )
	);
	array_unshift( $links, $settings_link );
	return $links;
}

/**
 * Add GitHub, Support, Donate, documentation, and review links to the plugin meta row.
 *
 * @since 1.2.0
 *
 * @param array  $plugin_meta An array of plugin row meta links.
 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
 * @return array Plugin row meta links, possibly with French Typo entries appended.
 */
function french_typo_plugin_row_meta( $plugin_meta, $plugin_file ) {
	if ( plugin_basename( __FILE__ ) !== $plugin_file ) {
		return $plugin_meta;
	}

	$review_url = 'https://wordpress.org/support/plugin/french-typo/reviews/?filter=5';
	$star_span  = '<span class="dashicons dashicons-star-filled" style="font-size:16px;width:16px;height:16px" aria-hidden="true"></span>';
	$stars_html = wp_kses(
		str_repeat( $star_span, 5 ),
		array(
			'span' => array(
				'class'       => true,
				'style'       => true,
				'aria-hidden' => true,
			),
		)
	);

	$new_links = array(
		sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
			esc_url( 'https://github.com/jaz-on/french-typo' ),
			esc_html__( 'GitHub', 'french-typo' )
		),
		sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
			esc_url( 'https://wordpress.org/support/plugin/french-typo/' ),
			esc_html__( 'Support', 'french-typo' )
		),
		sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
			esc_url( 'https://ko-fi.com/jasonrouet' ),
			esc_html__( 'Donate', 'french-typo' )
		),
		sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
			esc_url( 'https://github.com/jaz-on/french-typo/tree/main/docs' ),
			esc_html__( 'Documentation', 'french-typo' )
		),
		sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer" style="color:#ffb900" aria-label="%2$s">%3$s</a>',
			esc_url( $review_url ),
			esc_attr__( 'Rate French Typo 5 stars on WordPress.org', 'french-typo' ),
			$stars_html
		),
	);

	return array_merge( $plugin_meta, $new_links );
}

/**
 * Register plugin settings and fields.
 *
 * @since 1.0.0
 */
function french_typo_admin_init() {
	register_setting( 'french_typo_settings', 'french_typo_options', 'french_typo_options_validate' );

	add_settings_section(
		'narrow_space_section',
		__( 'Non-breaking spaces', 'french-typo' ),
		'french_typo_narrow_space_text',
		'admin_options'
	);
	add_settings_field(
		'narrow_space_field',
		__( 'Automatic replacement', 'french-typo' ),
		'french_typo_narrow_space',
		'admin_options',
		'narrow_space_section'
	);

	add_settings_section(
		'special_characters_section',
		__( 'Special characters', 'french-typo' ),
		'french_typo_special_characters_text',
		'admin_options'
	);
	add_settings_field(
		'special_characters_field',
		__( 'Automatic replacement', 'french-typo' ),
		'french_typo_special_characters',
		'admin_options',
		'special_characters_section'
	);

	add_settings_section(
		'ordinal_abbreviations_section',
		__( 'Ordinal abbreviations', 'french-typo' ),
		'french_typo_ordinal_abbreviations_text',
		'admin_options'
	);
	add_settings_field(
		'ordinal_abbreviations_field',
		__( 'Automatic replacement', 'french-typo' ),
		'french_typo_ordinal_abbreviations',
		'admin_options',
		'ordinal_abbreviations_section'
	);

	add_settings_section(
		'content_types_section',
		__( 'Posts and pages', 'french-typo' ),
		'french_typo_content_types_text',
		'admin_options'
	);
	add_settings_field(
		'content_types_field',
		__( 'Apply to', 'french-typo' ),
		'french_typo_content_types',
		'admin_options',
		'content_types_section'
	);

	add_settings_section(
		'advanced_section',
		__( 'Advanced options', 'french-typo' ),
		'french_typo_advanced_text',
		'admin_options'
	);
	add_settings_field(
		'advanced_field',
		__( 'Additional content', 'french-typo' ),
		'french_typo_advanced',
		'admin_options',
		'advanced_section'
	);
}

/**
 * Display description text for the non-breaking spaces section.
 *
 * @since 1.0.0
 */
function french_typo_narrow_space_text() {
	?>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
			/* translators: %1$s and %2$s are URL attributes (escaped). %3$s is a list of punctuation characters. */
				__( 'This plugin automatically handles <a href="%1$s" target="_blank" rel="noopener noreferrer">non-breaking spaces</a> or <a href="%2$s" target="_blank" rel="noopener noreferrer">thin non-breaking spaces</a> for the characters %3$s.', 'french-typo' ),
				esc_url( 'https://en.wikipedia.org/wiki/Non-breaking_space' ),
				esc_url( 'https://en.wikipedia.org/wiki/Non-breaking_space#Narrow_non-breaking_space' ),
				wp_sprintf_l( '%l', array( '<code>;</code>', '<code>:</code>', '<code>!</code>', '<code>?</code>', '<code>%</code>', '<code>«</code>', '<code>»</code>' ) )
			)
		);
		?>
	</p>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
				/* translators: %1$s–%5$s are lowercase HTML tag names (script, style, pre, code, textarea). */
				__( 'Narrow spaces are <strong>not</strong> inserted inside raw markup: &lt;%1$s&gt;, &lt;%2$s&gt;, nested &lt;%3$s&gt;/&lt;%4$s&gt;, and &lt;%5$s&gt;. Gutenberg Verse (<code>pre</code> with <code>wp-block-verse</code>) is still typographed unless <code>wp-block-code</code> is on the same <code>pre</code>.', 'french-typo' ),
				'script',
				'style',
				'pre',
				'code',
				'textarea'
			)
		);
		?>
	</p>
	<?php
}

/**
 * Render the non-breaking spaces settings field.
 *
 * @since 1.0.0
 */
function french_typo_narrow_space() {
	$options = french_typo_get_options();
	?>
	<div class="french-typo-checkbox-group">
		<label>
			<input type="radio" name="french_typo_options[narrow_space]" value="0" <?php checked( $options['narrow_space'], false ); ?> />
			<?php esc_html_e( 'Disable', 'french-typo' ); ?>
		</label>
		<label>
			<input type="radio" name="french_typo_options[narrow_space]" value="1" <?php checked( $options['narrow_space'], '&#160;' ); ?> />
			<?php
			printf(
				/* translators: %s are both HTML entity codes */
				esc_html__( 'Enable and use regular non-breaking spaces (HTML entity %1$s or %2$s)', 'french-typo' ),
				'<code>&amp;nbsp;</code>',
				'<code>&amp;#160;</code>'
			);
			?>
		</label>
		<label>
			<input type="radio" name="french_typo_options[narrow_space]" value="2" <?php checked( $options['narrow_space'], '&#8239;' ); ?> />
			<?php
			printf(
				/* translators: %s is an HTML entity code */
				esc_html__( 'Enable and use thin non-breaking spaces (HTML entity %s)', 'french-typo' ),
				'<code>&amp;#8239;</code>'
			);
			?>
		</label>
	</div>
	<p class="description">
		<?php esc_html_e( 'Non-breaking spaces stay off until you pick regular or thin above and save your settings at least once (or they remain off if you keep disabling this feature).', 'french-typo' ); ?>
	</p>
	<p class="description">
		<?php esc_html_e( 'Note: The thin non-breaking space may not display correctly. This depends on the font, browser version, and operating system used.', 'french-typo' ); ?>
	</p>
	<?php
}

/**
 * Display description text for the special characters section.
 *
 * @since 1.0.0
 */
function french_typo_special_characters_text() {
	?>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
			/* translators: %1$s–%7$s are character or entity codes shown in the settings UI */
				__( 'Replaces %1$s with %2$s, %3$s with %4$s, and %5$s or %6$s with %7$s.', 'french-typo' ),
				'<code>(c)</code>',
				'<code>&#169;</code>',
				'<code>(r)</code>',
				'<code>&#174;</code>',
				'<code>(tm)</code>',
				'<code>(TM)</code>',
				'<code>&#8482;</code>'
			)
		);
		?>
	</p>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
				/* translators: %1$s–%5$s are lowercase HTML tag names (script, style, pre, code, textarea). */
				__( 'These replacements use the same raw-markup rules as narrow spaces: they do not run inside &lt;%1$s&gt;, &lt;%2$s&gt;, nested &lt;%3$s&gt;/&lt;%4$s&gt;, or &lt;%5$s&gt;.', 'french-typo' ),
				'script',
				'style',
				'pre',
				'code',
				'textarea'
			)
		);
		?>
	</p>
	<?php
}

/**
 * Render the special characters settings field.
 *
 * @since 1.0.0
 */
function french_typo_special_characters() {
	$options = french_typo_get_options();
	?>
	<div class="french-typo-checkbox-group">
		<label>
			<input type="checkbox" name="french_typo_options[special_characters]" value="1" <?php checked( $options['special_characters'], 1 ); ?> />
			<?php esc_html_e( 'Replace special characters', 'french-typo' ); ?>
		</label>
	</div>
	<?php
}

/**
 * Display description text for the ordinal abbreviations section.
 *
 * @since 1.2.0
 */
function french_typo_ordinal_abbreviations_text() {
	?>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
				/* translators: %1$s–%7$s: code examples (ordinal forms). */
				__( 'Normalizes common French ordinal abbreviations in running text (for example %1$s becomes %2$s, %3$s becomes %4$s, %5$s becomes %6$s). English forms such as 1st and 2nd are left as-is; non-standard %7$s is unchanged.', 'french-typo' ),
				'<code>1ère</code>',
				'<code>1re</code>',
				'<code>3ème</code>',
				'<code>3e</code>',
				'<code>n-ième</code>',
				'<code>nième</code>',
				'<code>1ème</code>'
			)
		);
		?>
	</p>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
			/* translators: %1$s–%5$s are lowercase HTML tag names (script, style, pre, code, textarea). */
				__( 'These rules use the same raw-markup boundaries as narrow spaces and special characters: they do not run inside &lt;%1$s&gt;, &lt;%2$s&gt;, nested &lt;%3$s&gt;/&lt;%4$s&gt;, or &lt;%5$s&gt;.', 'french-typo' ),
				'script',
				'style',
				'pre',
				'code',
				'textarea'
			)
		);
		?>
	</p>
	<p class="description">
		<?php esc_html_e( 'Enabled by default when the option has never been saved. After upgrading, turn this off here if you prefer to keep forms like 3ème in displayed text.', 'french-typo' ); ?>
	</p>
	<?php
}

/**
 * Render the ordinal abbreviations settings field.
 *
 * @since 1.2.0
 */
function french_typo_ordinal_abbreviations() {
	$options = french_typo_get_options();
	?>
	<div class="french-typo-checkbox-group">
		<label>
			<input type="checkbox" name="french_typo_options[ordinal_abbreviations]" value="1" <?php checked( ! empty( $options['ordinal_abbreviations'] ) ); ?> />
			<?php esc_html_e( 'Normalize French ordinal abbreviations', 'french-typo' ); ?>
		</label>
	</div>
	<?php
}

/**
 * Validate and sanitize plugin options before saving.
 *
 * @since 1.0.0
 *
 * @param array $input The input data from the form.
 * @return array Sanitized options array.
 */
function french_typo_options_validate( $input ) {
	// Default values for all options.
	$defaults = array(
		'narrow_space'           => false,
		'special_characters'     => false,
		'ordinal_abbreviations'  => false,
		'apply_to_titles'        => false,
		'apply_to_content'       => false,
		'apply_to_excerpts'      => false,
		'apply_to_widgets'       => false,
		'apply_to_menus'         => false,
		'apply_to_custom_fields' => false,
		'apply_to_taxonomies'    => false,
		'apply_to_archives'      => false,
		'apply_to_comments'      => false,
		'apply_to_rss'           => false,
		'apply_to_rest_api'      => false,
		'apply_to_user_profiles' => false,
		'apply_to_breadcrumbs'   => false,
	);

	// Merge input with defaults using wp_parse_args().
	$validated = wp_parse_args( $input, $defaults );

	// Remove narrow_space from the validated array, we don't need to validate it as a boolean.
	$narrow_space_value = $validated['narrow_space'];
	unset( $validated['narrow_space'] );

	// Convert all values to booleans.
	foreach ( $validated as $key => $value ) {
		$validated[ $key ] = (bool) $value;
	}

	// Validate and restore narrow_space as integer (must be 0, 1, or 2).
	$validated['narrow_space'] = min( 2, max( 0, absint( $narrow_space_value ) ) );

	return $validated;
}

/**
 * Display description text for the content types section.
 *
 * @since 1.0.0
 */
function french_typo_content_types_text() {
	?>
	<p><?php esc_html_e( 'Turn typography on or off for post and page titles and main content. Excerpts and all other areas are controlled under advanced options below.', 'french-typo' ); ?></p>
	<?php
}

/**
 * Display description text for the advanced options section.
 *
 * @since 1.0.0
 */
function french_typo_advanced_text() {
	?>
	<p><?php esc_html_e( 'Typography rules also apply to many other areas: widgets, menus, excerpts, custom fields, taxonomies, archives, comments, RSS feeds, REST API, user profiles, and breadcrumbs.', 'french-typo' ); ?></p>
	<p><?php esc_html_e( 'RSS feeds only run typography when RSS is enabled plus the matching area: titles (RSS + titles), full content (RSS + post content), excerpts (RSS + excerpts), comments (RSS + comments). REST API responses require REST API enabled plus titles/content/excerpts as appropriate for each field.', 'french-typo' ); ?></p>
	<p><?php esc_html_e( 'Meta descriptions and social tags (Open Graph, Twitter Cards) from SEO plugins (Yoast SEO, Rank Math, SEOPress) are processed automatically when those integrations are enabled.', 'french-typo' ); ?></p>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
			/* translators: %s is a code example. */
				__( 'You can process custom text in PHP with the filter %s.', 'french-typo' ),
				'<code>apply_filters( \'french_typo_process_text\', $text )</code>'
			)
		);
		?>
	</p>
	<?php
}

/**
 * Render the advanced options settings field.
 *
 * @since 1.0.0
 */
function french_typo_advanced() {
	$options = french_typo_get_options();
	?>
	<details class="french-typo-advanced-details">
		<summary class="french-typo-advanced-toggle" aria-label="<?php esc_attr_e( 'Toggle advanced options visibility', 'french-typo' ); ?>">
			<span class="french-typo-toggle-text"><?php esc_html_e( 'Show advanced options', 'french-typo' ); ?></span>
		</summary>
		<div class="french-typo-advanced-content">
			<!-- Core WordPress Areas -->
			<div class="french-typo-checkbox-subgroup">
				<h3 class="french-typo-checkbox-subgroup-title"><?php esc_html_e( 'Core WordPress Areas', 'french-typo' ); ?></h3>
				<div class="french-typo-checkbox-group">
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_excerpts]" value="1" <?php checked( $options['apply_to_excerpts'], true ); ?> />
						<?php esc_html_e( 'Excerpts', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_widgets]" value="1" <?php checked( $options['apply_to_widgets'], true ); ?> />
						<?php esc_html_e( 'Widgets (text widgets and widget titles)', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_menus]" value="1" <?php checked( $options['apply_to_menus'], true ); ?> />
						<?php esc_html_e( 'Menu items', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_archives]" value="1" <?php checked( $options['apply_to_archives'], true ); ?> />
						<?php esc_html_e( 'Archives (titles and descriptions)', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_comments]" value="1" <?php checked( $options['apply_to_comments'], true ); ?> />
						<?php esc_html_e( 'Comment text and author names', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_user_profiles]" value="1" <?php checked( $options['apply_to_user_profiles'], true ); ?> />
						<?php esc_html_e( 'User profiles (descriptions)', 'french-typo' ); ?>
					</label>
				</div>
			</div>

			<!-- Custom Content -->
			<div class="french-typo-checkbox-subgroup">
				<h3 class="french-typo-checkbox-subgroup-title"><?php esc_html_e( 'Custom Content', 'french-typo' ); ?></h3>
				<div class="french-typo-checkbox-group">
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_custom_fields]" value="1" <?php checked( $options['apply_to_custom_fields'], true ); ?> />
						<?php esc_html_e( 'Custom fields (ACF, Meta Box, etc.)', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_taxonomies]" value="1" <?php checked( $options['apply_to_taxonomies'], true ); ?> />
						<?php esc_html_e( 'Taxonomies (categories, tags, custom taxonomies)', 'french-typo' ); ?>
					</label>
				</div>
			</div>

			<!-- Integrations -->
			<div class="french-typo-checkbox-subgroup">
				<h3 class="french-typo-checkbox-subgroup-title"><?php esc_html_e( 'Integrations', 'french-typo' ); ?></h3>
				<div class="french-typo-checkbox-group">
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_rss]" value="1" <?php checked( $options['apply_to_rss'], true ); ?> />
						<?php esc_html_e( 'RSS feeds', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_rest_api]" value="1" <?php checked( $options['apply_to_rest_api'], true ); ?> />
						<?php esc_html_e( 'REST API responses', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_breadcrumbs]" value="1" <?php checked( $options['apply_to_breadcrumbs'], true ); ?> />
						<?php esc_html_e( 'Breadcrumbs (Yoast, Rank Math, SEOPress)', 'french-typo' ); ?>
					</label>
				</div>
			</div>

			<p class="description">
				<?php esc_html_e( 'By default, all additional content areas are processed.', 'french-typo' ); ?>
			</p>
		</div>
	</details>
	<?php
}

/**
 * Render the content types settings field.
 *
 * @since 1.0.0
 */
function french_typo_content_types() {
	$options = french_typo_get_options();
	?>
	<div class="french-typo-checkbox-group">
		<label>
			<input type="checkbox" name="french_typo_options[apply_to_titles]" value="1" <?php checked( $options['apply_to_titles'], true ); ?> />
			<?php esc_html_e( 'Post and page titles', 'french-typo' ); ?>
		</label>
		<label>
			<input type="checkbox" name="french_typo_options[apply_to_content]" value="1" <?php checked( $options['apply_to_content'], true ); ?> />
			<?php esc_html_e( 'Post and page content', 'french-typo' ); ?>
		</label>
	</div>
	<p class="description">
		<?php esc_html_e( 'By default, both titles and content are processed. Use advanced options for excerpts and other areas.', 'french-typo' ); ?>
	</p>
	<?php
}

/**
 * Render the main settings page.
 *
 * @since 1.0.0
 */
function french_typo_admin_options() {
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<?php settings_errors( 'french_typo_settings' ); ?>
		
		<div class="card french-typo-intro-card">
			<h2 class="title"><?php esc_html_e( 'Thank you for using this plugin', 'french-typo' ); ?></h2>
			<p>
				<?php esc_html_e( 'This plugin automatically applies French typography rules to your WordPress content: non-breaking spaces before punctuation marks and special character replacements. Configure the options below according to your needs.', 'french-typo' ); ?>
			</p>
			<p class="french-typo-intro-footer">
				<?php
				echo wp_kses_post(
					sprintf(
					/* translators: %1$s is a link to GitHub, %2$s is a link to Ko-fi */
						__( 'Find the source code on %1$s. If this plugin is useful to you, you can support its author and help make further developments on %2$s.', 'french-typo' ),
						'<a href="' . esc_url( 'https://github.com/jaz-on/french-typo' ) . '" target="_blank" rel="noopener noreferrer">GitHub</a>',
						'<a href="' . esc_url( 'https://ko-fi.com/jasonrouet' ) . '" target="_blank" rel="noopener noreferrer">Ko-fi</a>'
					)
				);
				?>
			</p>
		</div>

		<form method="post" action="options.php" novalidate="novalidate">
			<?php settings_fields( 'french_typo_settings' ); ?>
			
			<div class="card french-typo-settings-card">
				<!-- Typography Rules -->
				<fieldset class="french-typo-fieldset-group">
					<legend class="french-typo-fieldset-title"><?php esc_html_e( 'Non-breaking spaces', 'french-typo' ); ?></legend>
					<?php french_typo_narrow_space_text(); ?>
					<?php french_typo_narrow_space(); ?>
				</fieldset>

				<fieldset class="french-typo-fieldset-group">
					<legend class="french-typo-fieldset-title"><?php esc_html_e( 'Special characters', 'french-typo' ); ?></legend>
					<?php french_typo_special_characters_text(); ?>
					<?php french_typo_special_characters(); ?>
				</fieldset>

				<!-- Application Zones -->
				<fieldset class="french-typo-fieldset-group">
					<legend class="french-typo-fieldset-title"><?php esc_html_e( 'Posts and pages', 'french-typo' ); ?></legend>
					<?php french_typo_content_types_text(); ?>
					<?php french_typo_content_types(); ?>
				</fieldset>

				<fieldset class="french-typo-fieldset-group">
					<legend class="french-typo-fieldset-title"><?php esc_html_e( 'Advanced options', 'french-typo' ); ?></legend>
					<?php french_typo_advanced_text(); ?>
					<?php french_typo_advanced(); ?>
				</fieldset>

				<div class="french-typo-save-wrapper">
					<?php submit_button(); ?>
				</div>
			</div>
		</form>
		
		<div class="french-typo-version-info">
			<?php
			printf(
				/* translators: %s is the version number */
				esc_html__( 'Installed version: %s', 'french-typo' ),
				esc_html( FRENCH_TYPO_VERSION )
			);
			?>
		</div>

	</div>
	<?php
}

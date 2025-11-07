<?php
/**
 * Plugin Name: French Typo
 * Plugin URI: https://github.com/jaz-on/french-typo
 * Description: Automatically applies French typography rules to your content: non-breaking spaces before punctuation marks (; : ! ? % « ») and special character replacements ((c) → ©, (r) → ®).
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.3
 * Tested up to: 6.9
 * Author: Jason Rouet
 * Author URI: https://profiles.wordpress.org/jaz_on/
 * Contributors: jaz_on, audrasjb
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: french-typo
 * Domain Path: /languages
 * Update URI: https://github.com/jaz-on/french-typo
 * GitHub Plugin URI: jaz-on/french-typo
 * GitHub Branch: main
 * Primary Branch: main
 * Release Asset: false
 *
 * @package French_Typo
 */

// Security check: prevent direct access to the file.
defined( 'ABSPATH' ) || die( 'Silence is golden.' );

/**
 * Initialize plugin hooks.
 *
 * Sets up filters for content processing and admin menu if in admin area.
 *
 * @since 1.0.0
 */
function french_typo_hooks() {
	// Apply typography rules to post titles and content.
	add_filter( 'the_title', 'french_typo_replace_title' );
	add_filter( 'the_content', 'french_typo_replace_content' );
	add_filter( 'the_excerpt', 'french_typo_replace_excerpt' );

	// Apply to widgets.
	add_filter( 'widget_text', 'french_typo_replace_widget' );
	add_filter( 'widget_title', 'french_typo_replace_widget_title' );

	// Apply to menu items.
	add_filter( 'wp_nav_menu_items', 'french_typo_replace_menu' );

	// Apply to taxonomies (categories, tags, custom taxonomies).
	add_filter( 'term_description', 'french_typo_replace_taxonomy' );
	add_filter( 'single_term_title', 'french_typo_replace_taxonomy_title' );
	add_filter( 'single_cat_title', 'french_typo_replace_taxonomy_title' );
	add_filter( 'single_tag_title', 'french_typo_replace_taxonomy_title' );
	add_filter( 'single_post_type_archive_title', 'french_typo_replace_taxonomy_title' );

	// Apply to archive titles and descriptions.
	add_filter( 'get_the_archive_title', 'french_typo_replace_archive_title' );
	add_filter( 'get_the_archive_description', 'french_typo_replace_archive' );

	// Apply to comments.
	add_filter( 'comment_text', 'french_typo_replace_comment' );
	add_filter( 'get_comment_author', 'french_typo_replace_comment_author' );

	// Support for Advanced Custom Fields (ACF).
	if ( function_exists( 'get_field' ) ) {
		add_filter( 'acf/format_value/type=text', 'french_typo_replace_custom_field', 10, 3 );
		add_filter( 'acf/format_value/type=textarea', 'french_typo_replace_custom_field', 10, 3 );
		add_filter( 'acf/format_value/type=wysiwyg', 'french_typo_replace_custom_field', 10, 3 );
	}

	// Support for Meta Box plugin.
	if ( function_exists( 'rwmb_get_value' ) ) {
		add_filter( 'rwmb_the_value', 'french_typo_replace_custom_field', 10, 3 );
	}

	// Apply to RSS feeds.
	add_filter( 'the_title_rss', 'french_typo_replace_rss_title' );
	add_filter( 'the_content_feed', 'french_typo_replace_rss_content' );
	add_filter( 'the_excerpt_rss', 'french_typo_replace_rss_excerpt' );
	add_filter( 'comment_text_rss', 'french_typo_replace_rss_comment' );

	// Apply to REST API responses.
	add_filter( 'rest_prepare_post', 'french_typo_rest_api_post', 10, 3 );
	add_filter( 'rest_prepare_page', 'french_typo_rest_api_post', 10, 3 );
	add_filter( 'rest_prepare_attachment', 'french_typo_rest_api_post', 10, 3 );

	// Apply to user profiles.
	add_filter( 'get_the_author_description', 'french_typo_replace_user_profile', 10, 1 );
	add_filter( 'get_user_meta', 'french_typo_user_meta', 10, 4 );

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
		add_filter( 'wpseo_metadesc', 'french_typo_replace', 10, 1 );
		add_filter( 'wpseo_title', 'french_typo_replace', 10, 1 );
		add_filter( 'wpseo_opengraph_title', 'french_typo_replace', 10, 1 );
		add_filter( 'wpseo_opengraph_desc', 'french_typo_replace', 10, 1 );
		add_filter( 'wpseo_twitter_title', 'french_typo_replace', 10, 1 );
		add_filter( 'wpseo_twitter_description', 'french_typo_replace', 10, 1 );
	}
	// Rank Math.
	if ( defined( 'RANK_MATH_VERSION' ) ) {
		add_filter( 'rank_math/frontend/title', 'french_typo_replace', 10, 1 );
		add_filter( 'rank_math/frontend/description', 'french_typo_replace', 10, 1 );
		add_filter( 'rank_math/opengraph/title', 'french_typo_replace', 10, 1 );
		add_filter( 'rank_math/opengraph/description', 'french_typo_replace', 10, 1 );
		add_filter( 'rank_math/twitter/title', 'french_typo_replace', 10, 1 );
		add_filter( 'rank_math/twitter/description', 'french_typo_replace', 10, 1 );
	}
	// SEOPress.
	if ( defined( 'SEOPRESS_VERSION' ) ) {
		add_filter( 'seopress_titles_title', 'french_typo_replace', 10, 1 );
		add_filter( 'seopress_titles_desc', 'french_typo_replace', 10, 1 );
		add_filter( 'seopress_social_og_title', 'french_typo_replace', 10, 1 );
		add_filter( 'seopress_social_og_desc', 'french_typo_replace', 10, 1 );
		add_filter( 'seopress_social_twitter_title', 'french_typo_replace', 10, 1 );
		add_filter( 'seopress_social_twitter_desc', 'french_typo_replace', 10, 1 );
	}

	// Generic filter for custom fields and other content.
	add_filter( 'french_typo_process_text', 'french_typo_replace', 10, 1 );

	// Only load admin functionality when in WordPress admin area.
	if ( is_admin() ) {
		add_action( 'admin_menu', 'french_typo_admin_menu' );
		add_action( 'admin_init', 'french_typo_admin_init' );
		add_action( 'admin_enqueue_scripts', 'french_typo_admin_enqueue_scripts' );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'french_typo_action_links' );
	}
}
add_action( 'init', 'french_typo_hooks' );

/**
 * Apply French typography rules to post titles.
 *
 * Wrapper function that checks if title processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The title text to process.
 * @return string The processed title text.
 */
function french_typo_replace_title( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_titles'] ) || 1 === $options['apply_to_titles'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to post content.
 *
 * Wrapper function that checks if content processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The content text to process.
 * @return string The processed content text.
 */
function french_typo_replace_content( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_content'] ) || 1 === $options['apply_to_content'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to post excerpts.
 *
 * Wrapper function that checks if excerpt processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The excerpt text to process.
 * @return string The processed excerpt text.
 */
function french_typo_replace_excerpt( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_excerpts'] ) || 1 === $options['apply_to_excerpts'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to widget content.
 *
 * Wrapper function that checks if widget processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The widget text to process.
 * @return string The processed widget text.
 */
function french_typo_replace_widget( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_widgets'] ) || 1 === $options['apply_to_widgets'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to widget titles.
 *
 * Wrapper function that checks if widget processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The widget title text to process.
 * @return string The processed widget title text.
 */
function french_typo_replace_widget_title( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_widgets'] ) || 1 === $options['apply_to_widgets'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to menu items.
 *
 * Wrapper function that checks if menu processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The menu items text to process.
 * @return string The processed menu items text.
 */
function french_typo_replace_menu( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_menus'] ) || 1 === $options['apply_to_menus'] ) {
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
 * @param int    $_post_id  The post ID (unused, required by filter hook).
 * @param object $_field    The field object (unused, required by filter hook).
 * @return mixed The processed custom field value.
 */
function french_typo_replace_custom_field( $value, $_post_id = null, $_field = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	// Parameters $_post_id and $_field are required by filter hooks but not used.
	// Only process string values.
	if ( ! is_string( $value ) ) {
		return $value;
	}

	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_custom_fields'] ) || 1 === $options['apply_to_custom_fields'] ) {
		return french_typo_replace( $value );
	}
	return $value;
}

/**
 * Apply French typography rules to taxonomy descriptions.
 *
 * Wrapper function that checks if taxonomy processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The taxonomy description text to process.
 * @return string The processed taxonomy description text.
 */
function french_typo_replace_taxonomy( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_taxonomies'] ) || 1 === $options['apply_to_taxonomies'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to taxonomy titles.
 *
 * Wrapper function that checks if taxonomy processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The taxonomy title text to process.
 * @return string The processed taxonomy title text.
 */
function french_typo_replace_taxonomy_title( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_taxonomies'] ) || 1 === $options['apply_to_taxonomies'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to archive descriptions.
 *
 * Wrapper function that checks if archive processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The archive description text to process.
 * @return string The processed archive description text.
 */
function french_typo_replace_archive( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_archives'] ) || 1 === $options['apply_to_archives'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to archive titles.
 *
 * Wrapper function that checks if archive processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The archive title text to process.
 * @return string The processed archive title text.
 */
function french_typo_replace_archive_title( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_archives'] ) || 1 === $options['apply_to_archives'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to comment text.
 *
 * Wrapper function that checks if comment processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The comment text to process.
 * @return string The processed comment text.
 */
function french_typo_replace_comment( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_comments'] ) || 1 === $options['apply_to_comments'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to comment author names.
 *
 * Wrapper function that checks if comment processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The comment author name to process.
 * @return string The processed comment author name.
 */
function french_typo_replace_comment_author( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_comments'] ) || 1 === $options['apply_to_comments'] ) {
		return french_typo_replace( $text );
	}
	return $text;
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
	$options = get_option( 'french_typo_options', array() );
	if ( ( ! isset( $options['apply_to_rss'] ) || 1 === $options['apply_to_rss'] ) && ( ! isset( $options['apply_to_titles'] ) || 1 === $options['apply_to_titles'] ) ) {
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
	$options = get_option( 'french_typo_options', array() );
	if ( ( ! isset( $options['apply_to_rss'] ) || 1 === $options['apply_to_rss'] ) && ( ! isset( $options['apply_to_content'] ) || 1 === $options['apply_to_content'] ) ) {
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
	$options = get_option( 'french_typo_options', array() );
	if ( ( ! isset( $options['apply_to_rss'] ) || 1 === $options['apply_to_rss'] ) && ( ! isset( $options['apply_to_excerpts'] ) || 1 === $options['apply_to_excerpts'] ) ) {
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
	$options = get_option( 'french_typo_options', array() );
	if ( ( ! isset( $options['apply_to_rss'] ) || 1 === $options['apply_to_rss'] ) && ( ! isset( $options['apply_to_comments'] ) || 1 === $options['apply_to_comments'] ) ) {
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
 * @param WP_Post          $_post    The post object (unused, required by filter hook).
 * @param WP_REST_Request  $_request The request object (unused, required by filter hook).
 * @return WP_REST_Response Modified response object.
 */
function french_typo_rest_api_post( $response, $_post, $_request ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	// Parameters $_post and $_request are required by filter hooks but not used.
	$options = get_option( 'french_typo_options', array() );

	if ( ! isset( $options['apply_to_rest_api'] ) || 1 === $options['apply_to_rest_api'] ) {
		$data = $response->get_data();

		// Process title if enabled.
		if ( isset( $data['title']['rendered'] ) && ( ! isset( $options['apply_to_titles'] ) || 1 === $options['apply_to_titles'] ) ) {
			$data['title']['rendered'] = french_typo_replace( $data['title']['rendered'] );
		}

		// Process content if enabled.
		if ( isset( $data['content']['rendered'] ) && ( ! isset( $options['apply_to_content'] ) || 1 === $options['apply_to_content'] ) ) {
			$data['content']['rendered'] = french_typo_replace( $data['content']['rendered'] );
		}

		// Process excerpt if enabled.
		if ( isset( $data['excerpt']['rendered'] ) && ( ! isset( $options['apply_to_excerpts'] ) || 1 === $options['apply_to_excerpts'] ) ) {
			$data['excerpt']['rendered'] = french_typo_replace( $data['excerpt']['rendered'] );
		}

		$response->set_data( $data );
	}

	return $response;
}

/**
 * Apply French typography rules to user profile descriptions.
 *
 * Wrapper function that checks if user profile processing is enabled before applying rules.
 *
 * @since 1.0.0
 *
 * @param string $text The user description text to process.
 * @return string The processed user description text.
 */
function french_typo_replace_user_profile( $text ) {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_user_profiles'] ) || 1 === $options['apply_to_user_profiles'] ) {
		return french_typo_replace( $text );
	}
	return $text;
}

/**
 * Apply French typography rules to user metadata.
 *
 * Processes user description and other text metadata.
 *
 * @since 1.0.0
 *
 * @param mixed  $value     The metadata value.
 * @param int    $_user_id  The user ID (unused, required by filter hook).
 * @param string $meta_key  The meta key.
 * @param bool   $_single   Whether to return a single value (unused, required by filter hook).
 * @return mixed The processed metadata value.
 */
function french_typo_user_meta( $value, $_user_id, $meta_key, $_single ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	// Parameters $_user_id and $_single are required by filter hooks but not used.
	// Only process description field.
	if ( 'description' !== $meta_key ) {
		return $value;
	}

	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_user_profiles'] ) || 1 === $options['apply_to_user_profiles'] ) {
		if ( is_string( $value ) ) {
			return french_typo_replace( $value );
		} elseif ( is_array( $value ) ) {
			return array_map( 'french_typo_replace', $value );
		}
	}

	return $value;
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
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_breadcrumbs'] ) || 1 === $options['apply_to_breadcrumbs'] ) {
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
 * Apply French typography rules to text content.
 *
 * Processes text to add non-breaking spaces before/after punctuation and replaces
 * special character sequences. Carefully avoids processing HTML tags and shortcodes
 * to prevent breaking the markup.
 *
 * @since 1.0.0
 *
 * @param string $text The text content to process.
 * @return string The processed text with French typography rules applied.
 */
function french_typo_replace( $text ) {
	// Get plugin options from database.
	$options = get_option( 'french_typo_options', array() );

	// Determine which type of non-breaking space to use based on settings.
	// 0 = disabled, 1 = regular (&#160;), 2 = thin (&#8239;).
	if ( isset( $options['narrow_space'] ) ) {
		switch ( $options['narrow_space'] ) {
			case '0':
				$narrow_space = null;
				break;
			default:
			case '1':
				$narrow_space = '&#160;';
				break;
			case '2':
				$narrow_space = '&#8239;';
				break;
		}
	} else {
		$narrow_space = null;
	}

	// Check if special character replacement is enabled.
	if ( isset( $options['special_characters'] ) ) {
		$special_characters = $options['special_characters'];
	} else {
		$special_characters = null;
	}

	// If both features are disabled, return text unchanged.
	if ( null === $narrow_space && ( ! isset( $special_characters ) || 0 === $special_characters ) ) {
		return $text;
	}

	// Static replacements: simple character sequences that don't need regex.
	$french_typo_static_characters   = array( '(c)', '(r)' );
	$french_typo_static_replacements = array( '&#169;', '&#174;' );

	// Dynamic replacements using regex patterns:
	// Pattern 1: Add non-breaking space before ; : ! ? % » (but not if followed by word char or //).
	// Pattern 2: Add non-breaking space after «.
	// Pattern 3: Fix cases where non-breaking space was incorrectly added before semicolon in HTML entities.
	$french_typo_dynamique_characters   = array(
		'#\s?([?!:;%»])(?!\w|//)#u',
		'#([«])\s?#u',
		'/(&#?[a-zA-Z0-9]+)' . $narrow_space . ';/',
	);
	$french_typo_dynamique_replacements = array(
		$narrow_space . '$1',
		'$1' . $narrow_space,
		'$1;',
	);

	// Split text into array, preserving HTML tags and shortcodes as separate elements.
	$textarr = preg_split( '#(<.*>|\[.*\])#Us', $text, -1, PREG_SPLIT_DELIM_CAPTURE );
	$stop    = count( $textarr );

	$text = '';

	// Process each segment of the text.
	for ( $i = 0; $i < $stop; $i++ ) {
		$curl = $textarr[ $i ];

		// Only process text segments (not HTML tags or shortcodes).
		if ( ! empty( $curl ) && '<' !== $curl[0] && '[' !== $curl[0] ) {
			// Replace special characters if enabled.
			if ( $special_characters > 0 ) {
				$curl = str_replace( $french_typo_static_characters, $french_typo_static_replacements, $curl );
			}

			// Apply non-breaking space rules if enabled.
			if ( null !== $narrow_space ) {
				$curl = preg_replace( $french_typo_dynamique_characters, $french_typo_dynamique_replacements, $curl );
			}
		}
		$text .= $curl;
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

	wp_enqueue_style(
		'french-typo-admin',
		plugin_dir_url( __FILE__ ) . 'admin.css',
		array(),
		'1.0.0'
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
		'content_types_section',
		__( 'Content types', 'french-typo' ),
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
			/* translators: %1$s and %2$s are links to Wikipedia articles */
				__( 'This plugin automatically handles <a href="%1$s" target="_blank" rel="noopener noreferrer">non-breaking spaces</a> or <a href="%2$s" target="_blank" rel="noopener noreferrer">thin non-breaking spaces</a> for the characters <code>;</code>, <code>:</code>, <code>!</code>, <code>?</code>, <code>%%</code>, <code>«</code> and <code>»</code>.', 'french-typo' ),
				esc_url( 'http://fr.wikipedia.org/wiki/Espace_ins%C3%A9cable' ),
				esc_url( 'https://fr.wikipedia.org/wiki/Espace_fine_ins%C3%A9cable' )
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
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['narrow_space'] ) ) {
		$options['narrow_space'] = 1;
	}
	?>
	<fieldset>
		<legend class="screen-reader-text"><span><?php esc_html_e( 'Automatic replacement', 'french-typo' ); ?></span></legend>
		<div class="french-typo-checkbox-group">
			<label>
				<input type="radio" name="french_typo_options[narrow_space]" value="0" <?php checked( $options['narrow_space'], 0 ); ?> />
				<?php esc_html_e( 'Disable', 'french-typo' ); ?>
			</label>
			<label>
				<input type="radio" name="french_typo_options[narrow_space]" value="1" <?php checked( $options['narrow_space'], 1 ); ?> />
				<?php
				printf(
					/* translators: %1$s and %2$s are HTML entity codes */
					esc_html__( 'Enable and use regular non-breaking spaces (HTML entity %1$s or %2$s)', 'french-typo' ),
					'<code>&amp;nbsp;</code>',
					'<code>&amp;#160;</code>'
				);
				?>
			</label>
			<label>
				<input type="radio" name="french_typo_options[narrow_space]" value="2" <?php checked( $options['narrow_space'], 2 ); ?> />
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
			<?php esc_html_e( 'Note: The thin non-breaking space may not display correctly. This depends on the font, browser version, and operating system used.', 'french-typo' ); ?>
		</p>
	</fieldset>
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
			/* translators: %1$s, %2$s, %3$s, and %4$s are character codes */
				__( 'French Typo replaces the characters <code>%1$s</code> and <code>%2$s</code> with <code>%3$s</code> and <code>%4$s</code>.', 'french-typo' ),
				esc_html( '(c)' ),
				esc_html( '(r)' ),
				esc_html( '©' ),
				esc_html( '®' )
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
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['special_characters'] ) ) {
		$options['special_characters'] = 1;
	}
	?>
	<fieldset>
		<legend class="screen-reader-text"><span><?php esc_html_e( 'Automatic replacement', 'french-typo' ); ?></span></legend>
		<div class="french-typo-checkbox-group">
			<label>
				<input type="radio" name="french_typo_options[special_characters]" value="0" <?php checked( $options['special_characters'], 0 ); ?> />
				<?php esc_html_e( 'Disable', 'french-typo' ); ?>
			</label>
			<label>
				<input type="radio" name="french_typo_options[special_characters]" value="1" <?php checked( $options['special_characters'], 1 ); ?> />
				<?php esc_html_e( 'Enable', 'french-typo' ); ?>
			</label>
		</div>
	</fieldset>
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
	$newinput = array();

	if ( isset( $input['narrow_space'] ) ) {
		$newinput['narrow_space'] = absint( $input['narrow_space'] );
	}

	if ( isset( $input['special_characters'] ) ) {
		$newinput['special_characters'] = absint( $input['special_characters'] );
	}

	if ( isset( $input['apply_to_titles'] ) ) {
		$newinput['apply_to_titles'] = absint( $input['apply_to_titles'] );
	} else {
		$newinput['apply_to_titles'] = 0;
	}

	if ( isset( $input['apply_to_content'] ) ) {
		$newinput['apply_to_content'] = absint( $input['apply_to_content'] );
	} else {
		$newinput['apply_to_content'] = 0;
	}

	if ( isset( $input['apply_to_excerpts'] ) ) {
		$newinput['apply_to_excerpts'] = absint( $input['apply_to_excerpts'] );
	} else {
		$newinput['apply_to_excerpts'] = 0;
	}

	if ( isset( $input['apply_to_widgets'] ) ) {
		$newinput['apply_to_widgets'] = absint( $input['apply_to_widgets'] );
	} else {
		$newinput['apply_to_widgets'] = 0;
	}

	if ( isset( $input['apply_to_menus'] ) ) {
		$newinput['apply_to_menus'] = absint( $input['apply_to_menus'] );
	} else {
		$newinput['apply_to_menus'] = 0;
	}

	if ( isset( $input['apply_to_custom_fields'] ) ) {
		$newinput['apply_to_custom_fields'] = absint( $input['apply_to_custom_fields'] );
	} else {
		$newinput['apply_to_custom_fields'] = 0;
	}

	if ( isset( $input['apply_to_taxonomies'] ) ) {
		$newinput['apply_to_taxonomies'] = absint( $input['apply_to_taxonomies'] );
	} else {
		$newinput['apply_to_taxonomies'] = 0;
	}

	if ( isset( $input['apply_to_archives'] ) ) {
		$newinput['apply_to_archives'] = absint( $input['apply_to_archives'] );
	} else {
		$newinput['apply_to_archives'] = 0;
	}

	if ( isset( $input['apply_to_comments'] ) ) {
		$newinput['apply_to_comments'] = absint( $input['apply_to_comments'] );
	} else {
		$newinput['apply_to_comments'] = 0;
	}

	if ( isset( $input['apply_to_rss'] ) ) {
		$newinput['apply_to_rss'] = absint( $input['apply_to_rss'] );
	} else {
		$newinput['apply_to_rss'] = 0;
	}

	if ( isset( $input['apply_to_rest_api'] ) ) {
		$newinput['apply_to_rest_api'] = absint( $input['apply_to_rest_api'] );
	} else {
		$newinput['apply_to_rest_api'] = 0;
	}

	if ( isset( $input['apply_to_user_profiles'] ) ) {
		$newinput['apply_to_user_profiles'] = absint( $input['apply_to_user_profiles'] );
	} else {
		$newinput['apply_to_user_profiles'] = 0;
	}

	if ( isset( $input['apply_to_breadcrumbs'] ) ) {
		$newinput['apply_to_breadcrumbs'] = absint( $input['apply_to_breadcrumbs'] );
	} else {
		$newinput['apply_to_breadcrumbs'] = 0;
	}

	return $newinput;
}

/**
 * Display description text for the content types section.
 *
 * @since 1.0.0
 */
function french_typo_content_types_text() {
	?>
	<p><?php esc_html_e( 'Choose which content types should have French typography rules applied.', 'french-typo' ); ?></p>
	<?php
}

/**
 * Display description text for the advanced options section.
 *
 * @since 1.0.0
 */
function french_typo_advanced_text() {
	?>
	<p>
		<?php
		echo wp_kses_post(
			sprintf(
			/* translators: %s is a code example */
				__( 'Apply typography rules to additional content areas like widgets, menus, excerpts, custom fields, taxonomies, archives, comments, RSS feeds, REST API, user profiles, and breadcrumbs. Meta descriptions and social tags (Open Graph, Twitter Cards) from SEO plugins (Yoast, Rank Math, SEOPress) are also processed automatically. You can also use the filter <code>%s</code> in your code to process custom content.', 'french-typo' ),
				'apply_filters( \'french_typo_process_text\', $text )'
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
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_widgets'] ) ) {
		$options['apply_to_widgets'] = 1;
	}
	if ( ! isset( $options['apply_to_menus'] ) ) {
		$options['apply_to_menus'] = 1;
	}
	if ( ! isset( $options['apply_to_excerpts'] ) ) {
		$options['apply_to_excerpts'] = 1;
	}
	if ( ! isset( $options['apply_to_custom_fields'] ) ) {
		$options['apply_to_custom_fields'] = 1;
	}
	if ( ! isset( $options['apply_to_taxonomies'] ) ) {
		$options['apply_to_taxonomies'] = 1;
	}
	if ( ! isset( $options['apply_to_archives'] ) ) {
		$options['apply_to_archives'] = 1;
	}
	if ( ! isset( $options['apply_to_comments'] ) ) {
		$options['apply_to_comments'] = 1;
	}
	if ( ! isset( $options['apply_to_rss'] ) ) {
		$options['apply_to_rss'] = 1;
	}
	if ( ! isset( $options['apply_to_rest_api'] ) ) {
		$options['apply_to_rest_api'] = 1;
	}
	if ( ! isset( $options['apply_to_user_profiles'] ) ) {
		$options['apply_to_user_profiles'] = 1;
	}
	if ( ! isset( $options['apply_to_breadcrumbs'] ) ) {
		$options['apply_to_breadcrumbs'] = 1;
	}
	?>
	<details class="french-typo-advanced-details">
		<summary class="french-typo-advanced-toggle" aria-label="<?php esc_attr_e( 'Toggle advanced options visibility', 'french-typo' ); ?>">
			<span class="french-typo-toggle-text"><?php esc_html_e( 'Show advanced options', 'french-typo' ); ?></span>
		</summary>
		<div class="french-typo-advanced-content">
			<fieldset>
			<legend class="screen-reader-text"><span><?php esc_html_e( 'Apply to', 'french-typo' ); ?></span></legend>
			
			<!-- Core WordPress Areas -->
			<div class="french-typo-checkbox-subgroup">
				<h3 class="french-typo-checkbox-subgroup-title"><?php esc_html_e( 'Core WordPress Areas', 'french-typo' ); ?></h3>
				<div class="french-typo-checkbox-group">
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_excerpts]" value="1" <?php checked( $options['apply_to_excerpts'], 1 ); ?> />
						<?php esc_html_e( 'Excerpts', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_widgets]" value="1" <?php checked( $options['apply_to_widgets'], 1 ); ?> />
						<?php esc_html_e( 'Widgets (text widgets and widget titles)', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_menus]" value="1" <?php checked( $options['apply_to_menus'], 1 ); ?> />
						<?php esc_html_e( 'Menu items', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_archives]" value="1" <?php checked( $options['apply_to_archives'], 1 ); ?> />
						<?php esc_html_e( 'Archives (titles and descriptions)', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_comments]" value="1" <?php checked( $options['apply_to_comments'], 1 ); ?> />
						<?php esc_html_e( 'Comments (text and author names)', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_user_profiles]" value="1" <?php checked( $options['apply_to_user_profiles'], 1 ); ?> />
						<?php esc_html_e( 'User profiles (descriptions)', 'french-typo' ); ?>
					</label>
				</div>
			</div>

			<!-- Custom Content -->
			<div class="french-typo-checkbox-subgroup">
				<h3 class="french-typo-checkbox-subgroup-title"><?php esc_html_e( 'Custom Content', 'french-typo' ); ?></h3>
				<div class="french-typo-checkbox-group">
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_custom_fields]" value="1" <?php checked( $options['apply_to_custom_fields'], 1 ); ?> />
						<?php esc_html_e( 'Custom fields (ACF, Meta Box, etc.)', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_taxonomies]" value="1" <?php checked( $options['apply_to_taxonomies'], 1 ); ?> />
						<?php esc_html_e( 'Taxonomies (categories, tags, custom taxonomies)', 'french-typo' ); ?>
					</label>
				</div>
			</div>

			<!-- Integrations -->
			<div class="french-typo-checkbox-subgroup">
				<h3 class="french-typo-checkbox-subgroup-title"><?php esc_html_e( 'Integrations', 'french-typo' ); ?></h3>
				<div class="french-typo-checkbox-group">
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_rss]" value="1" <?php checked( $options['apply_to_rss'], 1 ); ?> />
						<?php esc_html_e( 'RSS feeds', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_rest_api]" value="1" <?php checked( $options['apply_to_rest_api'], 1 ); ?> />
						<?php esc_html_e( 'REST API responses', 'french-typo' ); ?>
					</label>
					<label>
						<input type="checkbox" name="french_typo_options[apply_to_breadcrumbs]" value="1" <?php checked( $options['apply_to_breadcrumbs'], 1 ); ?> />
						<?php esc_html_e( 'Breadcrumbs (Yoast, Rank Math, SEOPress)', 'french-typo' ); ?>
					</label>
				</div>
			</div>

			<p class="description">
				<?php esc_html_e( 'By default, all additional content areas are processed. Uncheck to disable processing for specific areas.', 'french-typo' ); ?>
			</p>
		</fieldset>
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
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['apply_to_titles'] ) ) {
		$options['apply_to_titles'] = 1;
	}
	if ( ! isset( $options['apply_to_content'] ) ) {
		$options['apply_to_content'] = 1;
	}
	?>
	<fieldset>
		<legend class="screen-reader-text"><span><?php esc_html_e( 'Apply to', 'french-typo' ); ?></span></legend>
		<div class="french-typo-checkbox-group">
			<label>
				<input type="checkbox" name="french_typo_options[apply_to_titles]" value="1" <?php checked( $options['apply_to_titles'], 1 ); ?> />
				<?php esc_html_e( 'Post and page titles', 'french-typo' ); ?>
			</label>
			<label>
				<input type="checkbox" name="french_typo_options[apply_to_content]" value="1" <?php checked( $options['apply_to_content'], 1 ); ?> />
				<?php esc_html_e( 'Post and page content', 'french-typo' ); ?>
			</label>
		</div>
		<p class="description">
			<?php esc_html_e( 'By default, both titles and content are processed. Uncheck to disable processing for specific content types.', 'french-typo' ); ?>
		</p>
	</fieldset>
	<?php
}

/**
 * Render the main settings page.
 *
 * @since 1.0.0
 */
function french_typo_admin_options() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'french-typo' ) );
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<?php settings_errors(); ?>
		
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
				<div class="french-typo-fieldset-group">
					<h2 class="french-typo-fieldset-title"><?php esc_html_e( 'Non-breaking spaces', 'french-typo' ); ?></h2>
					<?php french_typo_narrow_space_text(); ?>
					<?php french_typo_narrow_space(); ?>
				</div>

				<div class="french-typo-fieldset-group">
					<h2 class="french-typo-fieldset-title"><?php esc_html_e( 'Special characters', 'french-typo' ); ?></h2>
					<?php french_typo_special_characters_text(); ?>
					<?php french_typo_special_characters(); ?>
				</div>

				<!-- Application Zones -->
				<div class="french-typo-fieldset-group">
					<h2 class="french-typo-fieldset-title"><?php esc_html_e( 'Content types', 'french-typo' ); ?></h2>
					<?php french_typo_content_types_text(); ?>
					<?php french_typo_content_types(); ?>
				</div>

				<div class="french-typo-fieldset-group">
					<h2 class="french-typo-fieldset-title"><?php esc_html_e( 'Advanced options', 'french-typo' ); ?></h2>
					<?php french_typo_advanced_text(); ?>
					<?php french_typo_advanced(); ?>
				</div>

				<div class="french-typo-save-wrapper">
					<?php submit_button(); ?>
				</div>
			</div>
		</form>
		
		<?php french_typo_display_version_info(); ?>
	</div>
	<?php
}

/**
 * Display version information in footer.
 *
 * Shows the installed version from plugin headers.
 *
 * @since 1.0.0
 */
function french_typo_display_version_info() {
	$plugin_data = get_file_data( __FILE__, array( 'Version' => 'Version' ) );
	if ( ! isset( $plugin_data['Version'] ) || empty( $plugin_data['Version'] ) ) {
		return;
	}
	?>
	<div class="french-typo-version-info">
		<?php
		printf(
			/* translators: %s is the version number */
			esc_html__( 'Installed version: %s', 'french-typo' ),
			esc_html( $plugin_data['Version'] )
		);
		?>
	</div>
	<?php
}


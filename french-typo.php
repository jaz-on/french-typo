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
	add_filter( 'the_title', 'french_typo_replace' );
	add_filter( 'the_content', 'french_typo_replace' );

	// Only load admin functionality when in WordPress admin area.
	if ( is_admin() ) {
		add_action( 'admin_menu', 'french_typo_admin_menu' );
		add_action( 'admin_init', 'french_typo_admin_init' );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'french_typo_action_links' );
	}
}
add_action( 'init', 'french_typo_hooks' );

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
}

/**
 * Display description text for the non-breaking spaces section.
 *
 * @since 1.0.0
 */
function french_typo_narrow_space_text() {
	echo '<p>' . wp_kses_post(
		sprintf(
		/* translators: %1$s and %2$s are links to Wikipedia articles */
			__( 'This plugin automatically handles <a href="%1$s" target="_blank" rel="noopener noreferrer">non-breaking spaces</a> or <a href="%2$s" target="_blank" rel="noopener noreferrer">thin non-breaking spaces</a> for the characters <code>;</code>, <code>:</code>, <code>!</code>, <code>?</code>, <code>%%</code>, <code>«</code> and <code>»</code>.', 'french-typo' ),
			esc_url( 'http://fr.wikipedia.org/wiki/Espace_ins%C3%A9cable' ),
			esc_url( 'https://fr.wikipedia.org/wiki/Espace_fine_ins%C3%A9cable' )
		)
	) . '</p>';
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
		<label>
			<input type="radio" name="french_typo_options[narrow_space]" value="0" <?php checked( $options['narrow_space'], 0 ); ?> />
			<?php esc_html_e( 'Disable', 'french-typo' ); ?>
		</label>
		<br>
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
		<br>
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
	echo '<p>' . wp_kses_post(
		sprintf(
		/* translators: %1$s, %2$s, %3$s, and %4$s are character codes */
			__( 'French Typo replaces the characters <code>%1$s</code> and <code>%2$s</code> with <code>%3$s</code> and <code>%4$s</code>.', 'french-typo' ),
			esc_html( '(c)' ),
			esc_html( '(r)' ),
			esc_html( '©' ),
			esc_html( '®' )
		)
	) . '</p>';
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
		<label>
			<input type="radio" name="french_typo_options[special_characters]" value="0" <?php checked( $options['special_characters'], 0 ); ?> />
			<?php esc_html_e( 'Disable', 'french-typo' ); ?>
		</label>
		<br>
		<label>
			<input type="radio" name="french_typo_options[special_characters]" value="1" <?php checked( $options['special_characters'], 1 ); ?> />
			<?php esc_html_e( 'Enable', 'french-typo' ); ?>
		</label>
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

	return $newinput;
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
		<h1><?php echo esc_html__( 'French Typo Settings', 'french-typo' ); ?></h1>
		<form method="post" action="options.php" novalidate="novalidate">
			<?php settings_fields( 'french_typo_settings' ); ?>
			<?php do_settings_sections( 'admin_options' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

<?php
/*
 * Plugin Name: Typographie française
 * Description: Applique les règles de la typographie française. Fork de l'extension French Typo (par Gilles Marchand), non maintenue.
 * Author: Jb Audras & Whodunit
 * Author URI: https://whodunit.fr
 * Version: 0.1
*/
defined( 'ABSPATH' ) or die( 'Le silence est d’or.' );

function french_typo_hooks() {
	add_filter( 'the_title', 'french_typo_replace' );
	add_filter( 'the_content', 'french_typo_replace' );
	if ( is_admin() ) {
		add_action( 'admin_menu', 'french_typo_admin_menu');
		add_action( 'admin_init', 'french_typo_admin_init');
	}
}
add_action( 'init', 'french_typo_hooks' );

function french_typo_replace( $text ) {
		$options = get_option( 'french_typo_options', array() );
		if ( isset( $options['narrow_space'] ) ) {
			switch( $options['narrow_space'] ) {
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

		if ( isset( $options['narrow_space'] ) ) {
			$special_characters = $options['special_characters'];
		} else {
			$special_characters = null;
		}

		$french_typo_static_characters = array( '(c)', '(r)' );
		$french_typo_static_replacements = array( '&#169;', '&#174;' );

		$french_typo_dynamique_characters = array( '#\s?([?!:;%»])(?!\w|//)#u', '#([«])\s?#u', '/(&#?[a-zA-Z0-9]+)' . $narrow_space . ';/' );
		$french_typo_dynamique_replacements = array( $narrow_space . '$1', '$1' . $narrow_space, '$1;' );

		$textarr = preg_split( '#(<.*>|\[.*\])#Us', $text, -1, PREG_SPLIT_DELIM_CAPTURE );
		$stop = count($textarr);

		$text = '';

		for( $i = 0; $i < $stop; $i++ ) {
			$curl = $textarr[$i];
			if ( !empty($curl) && '<' != $curl[0] && '[' != $curl[0] ) {
				if ( $special_characters > 0 ) {
					$curl = str_replace( $french_typo_static_characters, $french_typo_static_replacements, $curl );
				}

				if ( $narrow_space !== null ) {
					$curl = preg_replace( $french_typo_dynamique_characters, $french_typo_dynamique_replacements, $curl );
				}
			}
			$text .= $curl;
		}
		return $text;
	}

function french_typo_admin_menu() {
	add_options_page( 'Réglages typographiques', 'Réglages typographiques', 'manage_options', 'french-typo', 'french_typo_admin_options' );
}

function french_typo_admin_init() {
	register_setting( 'french_typo_settings', 'french_typo_options', 'french_typo_options_validate' );
	add_settings_section( 'narrow_space_section', 'Espaces insécables', 'french_typo_narrow_space_text', 'admin_options' );
	add_settings_field( 'narrow_space_field', 'Remplacement automatique', 'french_typo_narrow_space', 'admin_options', 'narrow_space_section' );
	add_settings_section( 'special_characters_section', 'Caractères spéciaux', 'french_typo_special_characters_text', 'admin_options' );
	add_settings_field( 'special_characters_field', 'Remplacement automatique', 'french_typo_special_characters', 'admin_options', 'special_characters_section' );
}

function french_typo_narrow_space_text() {
	echo '<p>Cette extension gère automatiquement les <a href="http://fr.wikipedia.org/wiki/Espace_ins%C3%A9cable" target="_blank">espaces insécables</a> ou les <a href="https://fr.wikipedia.org/wiki/Espace_fine_ins%C3%A9cable" target="_blank">espaces fines insécables</a> pour les caractères <code>;</code>, <code>:</code>, <code>!</code>, <code>?</code>, <code>%</code>, <code>«</code> et <code>»</code>.</p>';
}

function french_typo_narrow_space() {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['narrow_space'] ) ) {
		$options['narrow_space'] = 1;
	}
	?>
	<fieldset>
		<label>
			<input type="radio" name="french_typo_options[narrow_space]" value="0" <?php checked( $options['narrow_space'], 0 ); ?> />
			Désactiver
		</label>
		<br>
		<label>
			<input type="radio" name="french_typo_options[narrow_space]" value="1" <?php checked( $options['narrow_space'], 1 ); ?> />
			Activer et utiliser des espaces «&nbsp;normaux&nbsp;» insécables (entité HTML <code>&amp;nbsp;</code> ou <code>&amp;#160;</code>)
		</label>
		<br>
		<label>
			<input type="radio" name="french_typo_options[narrow_space]" value="2" <?php checked( $options['narrow_space'], 2 ); ?> />
			Activer et utiliser des espaces fines insécables (entité HTML <code>&amp;#8239;</code>)
		</label>
		<p class="description">À noter : l’espace fine insécable peut ne pas s’afficher correctement. Cela dépend de la fonte, de la version du navigateur et du système d’exploitation utilisé.</p>
	</fieldset>
	<?php
}

function french_typo_special_characters_text() {
		echo '<p>French Typo remplace les caractères <code>(c)</code> et <code>(r)</code> par <code>©</code> et <code>®</code>.</p>';
	}

function french_typo_special_characters() {
	$options = get_option( 'french_typo_options', array() );
	if ( ! isset( $options['special_characters'] ) ) {
		$options['special_characters'] = 1;
	}
	?>
	<fieldset>
		<label>
			<input type="radio" name="french_typo_options[special_characters]" value="0" <?php checked( $options['special_characters'], 0 ); ?> />
			Désactiver
		</label>
		<br>
		<label>
			<input type="radio" name="french_typo_options[special_characters]" value="1" <?php checked( $options['special_characters'], 1 ); ?> />
			Activer
		</label>
	</fieldset>
	<?php
}

function french_typo_options_validate( $input ) {
	$newinput = array();
	$newinput['narrow_space'] = absint( $input['narrow_space'] );
	$newinput['special_characters'] = absint( $input['special_characters'] );
	return $newinput;
}

function french_typo_admin_options() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'french-typo' ) );
	} else {
		?>
		<div class="wrap">
			<h1>Options de French Typo</h1>
			<form method="post" action="options.php" novalidate="novalidate">
				<?php settings_fields( 'french_typo_settings' ); ?>
				<?php do_settings_sections( 'admin_options' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}

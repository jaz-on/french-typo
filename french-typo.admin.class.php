<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package FrenchTypo
 */

class FrenchTypoAdmin
{
	private static $initiated = false;
	private static $options;

	public function init() {
		if(!self::$initiated) {
			self::$initiated = true;

			add_action('admin_menu', array('FrenchTypoAdmin', 'admin_menu'));
			add_action('admin_init', array('FrenchTypoAdmin', 'admin_init'));
		}
	}

	public function admin_menu() {
		add_options_page('Options de French Typo', 'French Typo', 'manage_options', 'french-typo', array('FrenchTypoAdmin', 'admin_options'));
	}

	public function admin_init() {
		register_setting('french_typo_settings', 'french_typo_options', array('FrenchTypoAdmin', 'options_validate'));
		add_settings_section('narrow_space_section', 'Espace insécable', array('FrenchTypoAdmin', 'narrow_space_text'), 'admin_options');
		add_settings_field('narrow_space_field', 'Remplacement automatique', array('FrenchTypoAdmin', 'narrow_space'), 'admin_options', 'narrow_space_section');
		add_settings_section('special_characters_section', 'Caractères spéciaux', array('FrenchTypoAdmin', 'special_characters_text'), 'admin_options');
		add_settings_field('special_characters_field', 'Remplacement automatique', array('FrenchTypoAdmin', 'special_characters'), 'admin_options', 'special_characters_section');
	}

	public function narrow_space_text() {
		echo '<p>French Typo gère automatiquement les <a href="http://fr.wikipedia.org/wiki/Espace_ins%C3%A9cable" target="_blank">espaces insécables</a> ou les <a href="https://fr.wikipedia.org/wiki/Espace_fine_ins%C3%A9cable" target="_blank">espaces fines insécables</a> pour les caractères <code>;</code>, <code>:</code>, <code>!</code>, <code>?</code>, <code>%</code>, <code>«</code> et <code>»</code>.</p>';
	}

	public function narrow_space() {
		self::$options = get_option('french_typo_options');
		if(!isset(self::$options['narrow_space'])) {
			self::$options['narrow_space'] = 1;
		}
		?>
		<fieldset>
			<label>
				<input type="radio" name="french_typo_options[narrow_space]" value="0" <?php checked(self::$options['narrow_space'], 0) ?>></input>
				Désactiver
			</label>
			<br>
			<label>
				<input type="radio" name="french_typo_options[narrow_space]" value="1" <?php checked(self::$options['narrow_space'], 1) ?>></input>
				Activer et utiliser des espaces «&nbsp;normaux&nbsp;» insécables (entité HTML <code>&amp;nbsp;</code> ou <code>&amp;#160;</code>)
			</label>
			<br>
			<label>
				<input type="radio" name="french_typo_options[narrow_space]" value="2" <?php checked(self::$options['narrow_space'], 2) ?>></input>
				Activer et utiliser des espaces fines insécables (entité HTML <code>&amp;#8239;</code>)
			</label>
			<p class="description">L’espace fine insécable peut ne pas s’afficher correctement. Cela dépend de la fonte, de la version du navigateur et du système d’exploitation utilisé.</p>
		</fieldset>
		<?php
	}

	public function special_characters_text() {
		echo '<p>French Typo remplace les caractères <code>(c)</code> et <code>(r)</code> par <code>©</code> et <code>®</code>.</p>';
	}

	public function special_characters() {
		self::$options = get_option('french_typo_options');
		if(!isset(self::$options['special_characters'])) {
			self::$options['special_characters'] = 1;
		}
		?>
		<fieldset>
			<label>
				<input type="radio" name="french_typo_options[special_characters]" value="0" <?php checked(self::$options['special_characters'], 0) ?>></input>
				Désactiver
			</label>
			<br>
			<label>
				<input type="radio" name="french_typo_options[special_characters]" value="1" <?php checked(self::$options['special_characters'], 1) ?>></input>
				Activer
			</label>
		</fieldset>
		<?php
	}
	public function options_validate($input) {
		$newinput = array();
		$newinput['narrow_space'] = absint($input['narrow_space']);
		$newinput['special_characters'] = absint($input['special_characters']);
		return $newinput;
	}

	public function admin_options() {
		if(!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		} else {
			?>
			<div class="wrap">
				<h1>Options de French Typo</h1>
				<form method="post" action="options.php" novalidate="novalidate">
					<?= settings_fields('french_typo_settings') ?>
					<?= do_settings_sections('admin_options') ?>
					<?= submit_button() ?>
				</form>
			</div>
			<?php
		}
	}
}

<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package FrenchTypo
 */
/*
	Plugin Name: French Typo
	Plugin URI: https://bitbucket.org/master_shiva/wp-french-typo
	Description: Applique les règles de la typographie française.
	Author: Gilles Marchand
	Author URI: https://swiss-connection.net
	Version: 1.2.2
*/

define('FRENCH_TYPO_DIR', plugin_dir_path(__FILE__));

require_once(FRENCH_TYPO_DIR.'french-typo.class.php');

add_action('init', array('FrenchTypo', 'init'));

if(is_admin()) {
	require_once(FRENCH_TYPO_DIR.'french-typo.admin.class.php');
	add_action('init', array('FrenchTypoAdmin', 'init'));
}

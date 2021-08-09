<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * @package FrenchTypo
 */

class FrenchTypo
{
	private static $initiated = false;
	private static $narrow_space;
	private static $special_characters;

	public function init() {
		if(!self::$initiated) {
			self::$initiated = true;

			add_filter('the_title', array('FrenchTypo', 'replace'));
			add_filter('the_content', array('FrenchTypo', 'replace'));
		}
	}

	public function replace($text) {
		$options = get_option('french_typo_options');
		switch($options['narrow_space']) {
			case '0':
				self::$narrow_space = null;
				break;

			default:
			case '1':
				self::$narrow_space = '&#160;';
				break;

			case '2':
				self::$narrow_space = '&#8239;';
				break;
		}
		self::$special_characters = $options['special_characters'];

		$french_typo_static_characters = array('(c)', '(r)');
		$french_typo_static_replacements = array('&#169;', '&#174;');

		$french_typo_dynamique_characters = array('#\s?([?!:;%»])(?!\w|//)#u', '#([«])\s?#u', '/(&#?[a-zA-Z0-9]+)'.self::$narrow_space.';/');
		$french_typo_dynamique_replacements = array(self::$narrow_space.'$1', '$1'.self::$narrow_space, '$1;');

		$textarr = preg_split('#(<.*>|\[.*\])#Us', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
		$stop = count($textarr);

		$text = '';

		for($i = 0; $i < $stop; $i++) {
			$curl = $textarr[$i];

			if(!empty($curl) && '<' != $curl[0] && '[' != $curl[0]) {
				if(self::$special_characters > 0) {
					$curl = str_replace($french_typo_static_characters, $french_typo_static_replacements, $curl);
				}

				if(self::$narrow_space !== null) {
					$curl = preg_replace($french_typo_dynamique_characters, $french_typo_dynamique_replacements, $curl);
				}
			}

			$text .= $curl;
		}

		return $text;
	}
}

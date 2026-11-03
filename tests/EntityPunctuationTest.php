<?php
/**
 * Punctuation glued to an HTML entity (wptexturize output) still gets its non-breaking space.
 *
 * @package French_Typo
 */

class EntityPunctuationTest extends FrenchTypoTestCase {

	private const NBSP_MODES = array(
		1 => '&#160;',
		2 => '&#8239;',
	);

	/**
	 * @dataProvider positive_provider
	 */
	public function test_punctuation_before_an_entity( int $narrow, string $input, string $expected ): void {
		$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => $narrow );
		$first = french_typo_replace( $input );
		$this->assertSame( $expected, $first );
		$this->assertSame( $expected, french_typo_replace( $first ), 'second pass must not change the output' );
	}

	public function positive_provider(): array {
		$cases = array();
		foreach ( self::NBSP_MODES as $mode => $n ) {
			$cases[ "question before closing quote mode $mode" ] = array( $mode, '&#8220;Pourquoi ?&#8221;', '&#8220;Pourquoi' . $n . '?&#8221;' );
			$cases[ "question before ellipsis mode $mode" ]      = array( $mode, 'Vraiment ?&#8230; non', 'Vraiment' . $n . '?&#8230; non' );
			$cases[ "percent before closing quote mode $mode" ]  = array( $mode, '100%&#8221;', '100' . $n . '%&#8221;' );
			$cases[ "exclamation before closing quote mode $mode" ] = array( $mode, '&#8220;Voilà !&#8221;', '&#8220;Voilà' . $n . '!&#8221;' );
			$cases[ "html exclamation mode $mode" ]              = array( $mode, '<p>&#8220;Voilà !&#8221;</p>', '<p>&#8220;Voilà' . $n . '!&#8221;</p>' );
			$cases[ "guillemets with narrow spaces mode $mode" ] = array( $mode, '«&#8239;Pourquoi ?&#8239;»', '«' . $n . 'Pourquoi' . $n . '?' . $n . '»' );
			$cases[ "guillemets with existing nbsp mode $mode" ] = array( $mode, '«&#160;Pourquoi ?&#160;»', '«' . $n . 'Pourquoi' . $n . '?' . $n . '»' );
			$cases[ "guillemets exclamation mode $mode" ]        = array( $mode, '«&#8239;Voilà !&#8239;»', '«' . $n . 'Voilà' . $n . '!' . $n . '»' );
		}
		return $cases;
	}

	/**
	 * @dataProvider unchanged_provider
	 */
	public function test_unchanged( string $input ): void {
		foreach ( array_keys( self::NBSP_MODES ) as $mode ) {
			$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => $mode );
			$this->assertSame( $input, french_typo_replace( $input ) );
		}
	}

	public function unchanged_provider(): array {
		return array(
			'url with query and entity' => array( 'https://ex.fr/a?x=1&amp;y=2' ),
			'query string'              => array( 'page?id=1' ),
			'time'                      => array( '12:30' ),
			'ratio'                     => array( '16:9' ),
			'lone ampersand entity'     => array( '&amp;' ),
		);
	}
}

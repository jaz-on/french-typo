<?php
/**
 * ACF / Meta Box values that look like URLs, tel:/mailto: links, CSS or JSON are not typographed.
 *
 * @package French_Typo
 */

class CustomFieldShapeTest extends FrenchTypoTestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['french_typo_test_options_override'] = array(
			'narrow_space'           => 1,
			'apply_to_custom_fields' => true,
		);
	}

	/**
	 * @dataProvider technical_provider
	 */
	public function test_technical_values_are_untouched( string $value ): void {
		$this->assertSame( $value, french_typo_replace_custom_field( $value ) );
	}

	public function technical_provider(): array {
		return array(
			'tel'              => array( 'tel:+33123456789' ),
			'tel with spaces'  => array( 'tel:+33 1 23 45 67 89' ),
			'mailto'           => array( 'mailto:jo@example.fr?subject=Salut' ),
			'https url'        => array( 'https://example.fr/page?x=1;y=2' ),
			'url with colon'   => array( 'https://example.fr:8080/a?b=c' ),
			'protocol-relative' => array( '//cdn.example.fr/a.js?v=1' ),
			'www'              => array( 'www.example.fr/page?x=1' ),
			'css'              => array( 'color: red; width: 100%' ),
			'css trailing ;'   => array( 'color: red; width: 100%;' ),
			'css rule'         => array( '.a { color: red; margin: 0 }' ),
			'json object'      => array( '{"a": "b"}' ),
			'json array'       => array( '["a: b", "c; d"]' ),
		);
	}

	public function test_prose_is_still_typographed(): void {
		$n = self::NBSP;
		$this->assertSame( 'Quoi' . $n . ': oui' . $n . '!', french_typo_replace_custom_field( 'Quoi : oui !' ) );
		$this->assertSame( 'Prix' . $n . ': 10 €', french_typo_replace_custom_field( 'Prix : 10 €' ) );
		$this->assertSame( 'Écrire à tel' . $n . ': 0123' . $n . '?', french_typo_replace_custom_field( 'Écrire à tel : 0123 ?' ) );
		$this->assertSame( '<p>Oui' . $n . '!</p>', french_typo_replace_custom_field( '<p>Oui !</p>' ) );
		$this->assertSame( "Voir https://example.fr\nQuoi" . $n . '?', french_typo_replace_custom_field( "Voir https://example.fr\nQuoi ?" ) );
		$this->assertSame( 'Note' . $n . ': à voir' . $n . '; merci', french_typo_replace_custom_field( 'Note : à voir ; merci' ) );
	}
}

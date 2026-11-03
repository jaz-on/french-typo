<?php
/**
 * french_typo_replace(): raw boundaries, Verse, ordinals, entities, shortcodes.
 *
 * @package French_Typo
 */

class ReplaceTest extends FrenchTypoTestCase {

	public function test_svg_style_is_left_alone_and_prose_after_it_is_typographed(): void {
		$out = french_typo_replace( '<svg><style>.x{a:b;}</style></svg><p>Ok !</p>' );
		$this->assertStringContainsString( '<style>.x{a:b;}</style>', $out );
		$this->assertStringContainsString( '<p>Ok' . self::NBSP . '!</p>', $out );
		$this->assertStringNotContainsString( 'a' . self::NBSP . ':b', $out );
	}

	public function test_nested_pre_code_is_left_alone(): void {
		$out = french_typo_replace( '<pre><code>z ! z; (c) (r) (tm) (TM)</code></pre>' );
		$this->assertStringContainsString( '<code>z ! z; (c) (r) (tm) (TM)</code>', $out );
	}

	public function test_gutenberg_verse_is_still_typographed(): void {
		$this->assertSame(
			'<pre class="wp-block-verse">Hi' . self::NBSP . '!</pre>',
			french_typo_replace( '<pre class="wp-block-verse">Hi !</pre>' )
		);
	}

	public function test_textarea_content_is_not_typographed(): void {
		$this->assertSame( '<textarea>q ! (c)</textarea>', french_typo_replace( '<textarea>q ! (c)</textarea>' ) );
	}

	public function test_ordinals_in_plain_text(): void {
		$this->assertSame(
			'1re 3e 22e nième xième 1ème 1st 2nd',
			french_typo_replace( '1ère 3ème 22ème n-ième x–ième 1ème 1st 2nd' )
		);
	}

	public function test_ordinals_in_html_text_only(): void {
		$this->assertSame( '<p>La 3e fois</p>', french_typo_replace( '<p>La 3ème fois</p>' ) );
	}

	public function test_ordinals_are_left_alone_inside_code(): void {
		$this->assertSame( '<code>3ème</code>', french_typo_replace( '<code>3ème</code>' ) );
	}

	public function test_ordinals_after_an_svg_block(): void {
		$this->assertStringContainsString( '<p>2e</p>', french_typo_replace( '<svg><style>.x{a:b;}</style></svg><p>2ème</p>' ) );
	}

	/**
	 * The trailing ; of an entity must not trigger the nbsp-before-; rule.
	 *
	 * @dataProvider entity_provider
	 */
	public function test_entities_are_not_split_by_the_semicolon_rule( string $input, string $expected ): void {
		$this->assertSame( $expected, french_typo_replace( $input ) );
	}

	public function entity_provider(): array {
		return array(
			'numeric entity in a title' => array( 'You Got Me (feat. Erykah Badu &#038; Eve)', 'You Got Me (feat. Erykah Badu &#038; Eve)' ),
			'entity next to real punctuation' => array( 'Vraiment ? Earth &amp; Fire', 'Vraiment' . self::NBSP . '? Earth &amp; Fire' ),
			'named, numeric and hex entities' => array( 'A &amp; B &#038; C &#x26; D', 'A &amp; B &#038; C &#x26; D' ),
		);
	}

	/**
	 * Shortcode attributes must not be typographed before do_shortcode() parses them (issue #11).
	 */
	public function test_shortcode_attributes_are_untouched_in_plain_text(): void {
		$this->assertSame(
			'Prix' . self::NBSP . ': 10€ [gallery caption="Merci !"] Fin.',
			french_typo_replace( 'Prix : 10€ [gallery caption="Merci !"] Fin.' )
		);
	}

	public function test_shortcode_attributes_are_untouched_in_html(): void {
		$this->assertSame(
			'<p>Prix' . self::NBSP . ': 10€ [gallery caption="Merci !"] Fin.</p>',
			french_typo_replace( '<p>Prix : 10€ [gallery caption="Merci !"] Fin.</p>' )
		);
	}
}

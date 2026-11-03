<?php
/**
 * Fast paths of french_typo_replace(): they must never change the output.
 *
 * @package French_Typo
 */

class PerformanceGuardsTest extends FrenchTypoTestCase {

	/**
	 * Run the markup stack update on one tag segment and return the resulting stack.
	 *
	 * @param string $segment Tag segment.
	 * @return array
	 */
	private function stack_after( string $segment ): array {
		$stack = array();
		french_typo_markup_stack_update( $stack, $segment );
		return $stack;
	}

	public function test_stack_ignores_ordinary_tags(): void {
		$this->assertSame( array(), $this->stack_after( '<a href="/x" class="y">' ) );
		$this->assertSame( array(), $this->stack_after( '</p>' ) );
		$this->assertSame( array(), $this->stack_after( '<!-- wp:paragraph -->' ) );
	}

	public function test_stack_opens_raw_tags_in_any_case_or_prefix(): void {
		$this->assertSame( array( 'pre' ), $this->stack_after( '<PRE>' ) );
		$this->assertSame( array( 'pre' ), $this->stack_after( '<pre class=x>' ) );
		$this->assertSame( array( 'code' ), $this->stack_after( '<svg:code>' ) );
		$this->assertSame( array( 'textarea' ), $this->stack_after( '<Textarea rows="3">' ) );
	}

	public function test_stack_closes_with_spaces_and_case(): void {
		$stack = array( 'pre' );
		french_typo_markup_stack_update( $stack, '</pre >' );
		$this->assertSame( array(), $stack );
		$stack = array( 'code' );
		french_typo_markup_stack_update( $stack, '</CODE>' );
		$this->assertSame( array(), $stack );
	}

	public function test_stack_ignores_comments_mentioning_a_raw_tag(): void {
		$this->assertSame( array(), $this->stack_after( '<!-- <code> -->' ) );
	}

	public function test_raw_zones_in_unusual_notation_stay_untouched(): void {
		$this->assertSame( '<PRE>a ? b</PRE>', french_typo_replace( '<PRE>a ? b</PRE>' ) );
		$this->assertSame( '<pre class=x>a ? b</pre >', french_typo_replace( '<pre class=x>a ? b</pre >' ) );
		$this->assertSame( '<svg:code>a ? b</svg:code>', french_typo_replace( '<svg:code>a ? b</svg:code>' ) );
	}

	public function test_ordinal_gate_returns_text_untouched_without_accent(): void {
		$this->assertSame( 'Le 1er et le 2nd mot', french_typo_apply_ordinal_abbreviations( 'Le 1er et le 2nd mot' ) );
		$this->assertSame( 'Un été très long', french_typo_apply_ordinal_abbreviations( 'Un été très long' ) );
	}

	public function test_ordinal_gate_still_converts_lower_and_upper_case(): void {
		$this->assertSame( '1re 3e 1RE 3E nième', french_typo_apply_ordinal_abbreviations( '1ère 3ème 1ÈRE 3ÈME n-ième' ) );
	}

	public function test_ordinal_still_returns_null_on_invalid_utf8_with_or_without_accent(): void {
		$this->assertNull( french_typo_apply_ordinal_abbreviations( "abc \xFF def" ) );
		$this->assertNull( french_typo_apply_ordinal_abbreviations( "3\xC3\xA8me \xFF" ) );
	}

	public function test_invalid_utf8_without_accent_keeps_the_original_text(): void {
		$GLOBALS['french_typo_test_options_override'] = array(
			'narrow_space'          => 0,
			'special_characters'    => 1,
			'ordinal_abbreviations' => true,
		);
		$text = "<p>(c) \xFF abc</p><p>(c) ok</p>";
		$this->assertSame( $text, french_typo_replace( $text ) );
	}

	public function test_medium_texts_are_cached(): void {
		$text = '<p>' . str_repeat( 'Bonjour : le monde ! ', 20 ) . ' cache-medium</p>';
		$this->assertLessThan( 65536, strlen( $text ) );
		$first = french_typo_replace( $text );
		$GLOBALS['french_typo_test_split_calls'] = 0;
		$this->assertSame( $first, french_typo_replace( $text ) );
		$this->assertSame( 0, $GLOBALS['french_typo_test_split_calls'] );
	}

	public function test_texts_over_64_kb_are_not_cached_but_stay_correct(): void {
		$text = '<p>' . str_repeat( 'Bonjour : le monde ! ', 3500 ) . ' cache-large</p>';
		$this->assertGreaterThan( 65536, strlen( $text ) );
		$first = french_typo_replace( $text );
		$GLOBALS['french_typo_test_split_calls'] = 0;
		$this->assertSame( $first, french_typo_replace( $text ) );
		$this->assertSame( 1, $GLOBALS['french_typo_test_split_calls'] );
		$this->assertStringContainsString( 'Bonjour' . self::NBSP . ': le monde' . self::NBSP . '!', $first );
	}

	public function test_short_accented_strings_below_three_characters_are_left_alone(): void {
		$this->assertSame( 'é!', french_typo_replace( 'é!' ) );
		$this->assertSame( 'é?', french_typo_replace( 'é?' ) );
	}
}

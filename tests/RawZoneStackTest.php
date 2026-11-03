<?php
/**
 * Raw-text stack (pre, code, script, style, textarea): comments are ignored and unclosed tags do not swallow the rest.
 *
 * @package French_Typo
 */

class RawZoneStackTest extends FrenchTypoTestCase {

	protected function setUp(): void {
		parent::setUp();
		$GLOBALS['french_typo_test_options_override'] = array( 'narrow_space' => 1 );
	}

	/**
	 * @dataProvider provider
	 */
	public function test_stack_rules( string $input, string $expected ): void {
		$first = french_typo_replace( $input );
		$this->assertSame( $expected, $first );
		$this->assertSame( $expected, french_typo_replace( $first ), 'second pass must not change the output' );
	}

	public function provider(): array {
		$n = self::NBSP;
		return array(
			'code opened inside an html comment'     => array(
				'<!-- <code> --><p>Oui !</p><p>Non ?</p>',
				'<!-- <code> --><p>Oui' . $n . '!</p><p>Non' . $n . '?</p>',
			),
			'pre opened and closed in a comment'     => array(
				'<!-- <pre> --><p>Oui !</p><!-- </pre> -->',
				'<!-- <pre> --><p>Oui' . $n . '!</p><!-- </pre> -->',
			),
			'closing tag in a comment closes nothing' => array(
				'<code>a ! <!-- </code> --> b !</code> c !',
				'<code>a ! <!-- </code> --> b !</code> c' . $n . '!',
			),
			'unclosed code is ignored'               => array(
				'<p>Voir <code>foo</p><p>Après : ok</p>',
				'<p>Voir <code>foo</p><p>Après' . $n . ': ok</p>',
			),
			'closing pre also closes an unclosed code' => array(
				'<pre><code>x !</pre><p>Oui !</p>',
				'<pre><code>x !</pre><p>Oui' . $n . '!</p>',
			),
			'closed code stays raw'                  => array(
				'<p>Voir <code>a ! b</code> et c !</p>',
				'<p>Voir <code>a ! b</code> et c' . $n . '!</p>',
			),
			'nested pre code stays raw'              => array(
				'<pre><code>z ! z</code></pre> Fin !',
				'<pre><code>z ! z</code></pre> Fin' . $n . '!',
			),
			'unclosed code before a closed one'      => array(
				'<code>A ! <code>B !</code> fin !',
				'<code>A' . $n . '! <code>B !</code> fin' . $n . '!',
			),
		);
	}
}

<?php
/**
 * Idempotence assertions for french_typo_replace() — issue #8.
 *
 * Ensures that NBSP variants already present in the input (named entity, numeric
 * decimal/hex entities, U+00A0 / U+202F literals) do not trigger duplicate NBSP
 * insertion before French punctuation, and that consecutive invocations of the
 * function are stable (relevant for Elementor's layered widget_text + the_content
 * pipeline and for content pre-treated by editors like Advanced Editor Tools).
 *
 * @package French_Typo
 */

require __DIR__ . '/bootstrap.php';

if ( ! function_exists( 'french_typo_test_fail' ) ) {
	/**
	 * Print message and exit 1.
	 *
	 * @param string $message Message.
	 */
	function french_typo_test_fail( $message ) {
		fwrite( STDERR, $message . PHP_EOL );
		exit( 1 );
	}
}

$nbs = '&#160;';
$u00a0 = "\xC2\xA0";
$u202f = "\xE2\x80\xAF";

/**
 * Run a single idempotence assertion and its second-pass check.
 *
 * @param string $label    Test label.
 * @param string $input    Raw input.
 * @param string $expected Expected output after first pass.
 */
function ft_assert_idempotent( $label, $input, $expected ) {
	$first  = french_typo_replace( $input );
	if ( $first !== $expected ) {
		french_typo_test_fail(
			$label . ': first pass mismatch.' . PHP_EOL
			. '  input:    ' . bin2hex( $input ) . PHP_EOL
			. '  expected: ' . bin2hex( $expected ) . PHP_EOL
			. '  got:      ' . bin2hex( $first )
		);
	}
	$second = french_typo_replace( $first );
	if ( $second !== $expected ) {
		french_typo_test_fail(
			$label . ': second pass not idempotent.' . PHP_EOL
			. '  expected: ' . bin2hex( $expected ) . PHP_EOL
			. '  got:      ' . bin2hex( $second )
		);
	}
}

// Reference output expected after canonicalisation: one &#160; in front of the punctuation.
$expected = '<p>Bonjour' . $nbs . ': monde</p>';

ft_assert_idempotent( 'named entity preserved',        '<p>Bonjour&nbsp;: monde</p>',     $expected );
ft_assert_idempotent( 'numeric entity preserved',      '<p>Bonjour&#160;: monde</p>',     $expected );
ft_assert_idempotent( 'numeric entity padded',         '<p>Bonjour&#0160;: monde</p>',    $expected );
ft_assert_idempotent( 'hex entity preserved',          '<p>Bonjour&#xA0;: monde</p>',     $expected );
ft_assert_idempotent( 'hex entity padded',             '<p>Bonjour&#x00A0;: monde</p>',   $expected );
ft_assert_idempotent( 'fine numeric entity',           '<p>Bonjour&#8239;: monde</p>',    $expected );
ft_assert_idempotent( 'fine hex entity',               '<p>Bonjour&#x202F;: monde</p>',   $expected );
ft_assert_idempotent( 'U+00A0 literal preserved',      '<p>Bonjour' . $u00a0 . ': monde</p>', $expected );
ft_assert_idempotent( 'U+202F literal preserved',      '<p>Bonjour' . $u202f . ': monde</p>', $expected );

// Multiple consecutive NBSP variants (e.g. layered Elementor widget_text + the_content) collapse.
ft_assert_idempotent( 'double named entity collapses',     '<p>Bonjour&nbsp;&nbsp;: monde</p>',     $expected );
ft_assert_idempotent( 'mixed named+numeric collapses',     '<p>Bonjour&nbsp;&#160;: monde</p>',     $expected );
ft_assert_idempotent( 'double U+00A0 collapses',           '<p>Bonjour' . $u00a0 . $u00a0 . ': monde</p>', $expected );
ft_assert_idempotent( 'U+00A0 then entity collapses',      '<p>Bonjour' . $u00a0 . '&#160;: monde</p>',    $expected );

// Opening « gets NBSP after, closing » gets NBSP before. Spaces outside the guillemets are kept.
$expected_open = '<p>Il dit «' . $nbs . 'salut' . $nbs . '» au revoir</p>';
ft_assert_idempotent( 'guillemets named entities',
	'<p>Il dit «&nbsp;salut&nbsp;» au revoir</p>',
	$expected_open
);
ft_assert_idempotent( 'guillemets U+00A0 literals',
	'<p>Il dit «' . $u00a0 . 'salut' . $u00a0 . '» au revoir</p>',
	$expected_open
);
ft_assert_idempotent( 'guillemets doubled NBSP collapses',
	'<p>Il dit «&nbsp;&nbsp;salut&nbsp;&nbsp;» au revoir</p>',
	$expected_open
);

// Plain text branch (no markup): same guarantees.
ft_assert_idempotent( 'plain U+00A0 literal',     'Bonjour' . $u00a0 . ': monde',     'Bonjour' . $nbs . ': monde' );
ft_assert_idempotent( 'plain double U+00A0',      'Bonjour' . $u00a0 . $u00a0 . ': monde', 'Bonjour' . $nbs . ': monde' );
ft_assert_idempotent( 'plain no NBSP at all',     'Bonjour: monde',                   'Bonjour' . $nbs . ': monde' );
ft_assert_idempotent( 'plain ASCII space',        'Bonjour : monde',                  'Bonjour' . $nbs . ': monde' );

// Negative: entity-like sequences not adjacent to French punctuation must stay untouched.
$mid = '<p>A&amp;B mots ' . $u00a0 . ' au milieu</p>';
$out_mid = french_typo_replace( $mid );
if ( false === strpos( $out_mid, '&amp;' ) ) {
	french_typo_test_fail( 'entity protection: &amp; must remain intact in the middle of text.' );
}

echo 'OK french-typo-idempotence-test' . PHP_EOL;

<?php
/**
 * The advanced-options toggle is named by its visible text only (WCAG 2.5.3).
 *
 * @package French_Typo
 */

// Output helpers used by the settings field renderers, stubbed only for this test file.
if ( ! function_exists( 'esc_html_e' ) ) {
	function esc_html_e( $text ) { // phpcs:ignore
		echo htmlspecialchars( $text, ENT_QUOTES );
	}
}
if ( ! function_exists( 'checked' ) ) {
	function checked( $checked, $current = true, $display = true ) { // phpcs:ignore
		$result = ( (string) $checked === (string) $current ) ? " checked='checked'" : '';
		if ( $display ) {
			echo $result; // phpcs:ignore
		}
		return $result;
	}
}

class AdvancedOptionsTest extends FrenchTypoTestCase {

	private function render(): string {
		ob_start();
		french_typo_advanced();
		return (string) ob_get_clean();
	}

	public function test_summary_has_no_aria_label_and_keeps_its_visible_text(): void {
		$html = $this->render();
		$this->assertSame( 1, preg_match( '#<summary\b([^>]*)>(.*?)</summary>#s', $html, $m ), 'summary is rendered' );
		$this->assertStringNotContainsString( 'aria-label', $m[1] );
		$this->assertStringContainsString( 'Show advanced options', $m[2] );
	}
}

<?php
/**
 * Unit tests for Helper — starting with the security-relevant sanitizers.
 * These are the highest-value, lowest-fixture targets: pure input→output
 * functions whose regressions silently corrupt escaped markup.
 *
 * @package Essential_Addons_Elementor
 */

use Essential_Addons_Elementor\Classes\Helper;

class HelperTest extends WP_UnitTestCase {

	/**
	 * str_to_css_id() must produce a safe CSS identifier: lowercase, only
	 * [a-z0-9_-], no stray special characters, whitespace collapsed to dashes.
	 */
	public function test_str_to_css_id_strips_unsafe_characters() {
		$this->assertSame( 'hello-world', Helper::str_to_css_id( 'Hello World' ) );
		$this->assertSame( 'abc123', Helper::str_to_css_id( 'abc123' ) );
	}

	public function test_str_to_css_id_removes_html_and_quotes() {
		// A would-be injection payload must not survive as active characters.
		$out = Helper::str_to_css_id( '"><script>alert(1)</script>' );
		$this->assertStringNotContainsString( '<', $out );
		$this->assertStringNotContainsString( '>', $out );
		$this->assertStringNotContainsString( '"', $out );
		$this->assertMatchesRegularExpression( '/^[a-z0-9_-]*$/', $out );
	}

	public function test_str_to_css_id_collapses_whitespace_and_lowercases() {
		// Runs of whitespace/underscores collapse to single dashes; leading and
		// trailing separators become edge dashes (current, characterized behavior).
		$this->assertSame( '-foo-bar-baz-', Helper::str_to_css_id( '  FOO   bar_baz ' ) );
	}

	public function test_str_to_css_id_is_idempotent() {
		$once  = Helper::str_to_css_id( 'Some Title!!' );
		$twice = Helper::str_to_css_id( $once );
		$this->assertSame( $once, $twice );
	}
}

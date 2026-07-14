<?php
/**
 * PHPUnit bootstrap — boots the WordPress test framework (via wp-phpunit) and
 * loads this plugin so its classes/hooks are testable.
 *
 * Run through wp-env (provides the tests DB):
 *   wp-env start
 *   composer --working-dir=tools install
 *   wp-env run tests-cli --env-cwd=wp-content/plugins/essential-addons-for-elementor-lite \
 *     tools/vendor/bin/phpunit
 *
 * @package Essential_Addons_Elementor
 */

// Composer autoload for the dev toolchain (PHPUnit polyfills, wp-phpunit).
require_once dirname( __DIR__, 2 ) . '/tools/vendor/autoload.php';

// Locate the WordPress test suite. wp-env's tests environment exposes it at
// WP_TESTS_DIR (with an adjacent wp-tests-config.php); some setups use
// WP_PHPUNIT__DIR instead; the Composer wp-phpunit package is the last resort.
$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = getenv( 'WP_PHPUNIT__DIR' );
}
if ( ! $_tests_dir ) {
	$_tests_dir = dirname( __DIR__, 2 ) . '/tools/vendor/wp-phpunit/wp-phpunit';
}
$_tests_dir = rtrim( $_tests_dir, '/' );

require_once $_tests_dir . '/includes/functions.php';

/**
 * Load Essential Addons (and Elementor) before WordPress finishes booting the
 * test environment.
 */
function _eael_manually_load_plugin() {
	$plugins_dir = dirname( __DIR__, 3 ); // wp-content/plugins

	$elementor = $plugins_dir . '/elementor/elementor.php';
	if ( file_exists( $elementor ) ) {
		require $elementor;
	}

	require dirname( __DIR__, 2 ) . '/essential_adons_elementor.php';
}
tests_add_filter( 'muplugins_loaded', '_eael_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';

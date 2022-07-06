<?php
/**
 * PHPUnit bootstrap file
 *
 * @package EA
 */

// Require composer dependencies.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

$_tests_dir = getenv( 'WP_TESTS_DIR' );

// Try the `wp-phpunit` composer package.
if ( ! $_tests_dir ) {
	$_tests_dir = getenv( 'WP_PHPUNIT__DIR' );
}

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php" . PHP_EOL; // WPCS: XSS ok.
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

// Load the PHPUnit Polyfills library.
$_phpunit_polyfills_lib = dirname( dirname( __FILE__ ) ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';
if ( ! file_exists( $_phpunit_polyfills_lib ) ) {
	echo "Could not find $_phpunit_polyfills_lib, have you run `docker-compose up` in order to install Composer packages?" . PHP_EOL; // WPCS: XSS ok.
	exit( 1 );
}
require_once $_phpunit_polyfills_lib;

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/essential_adons_elementor.php';
	require '/var/www/html/wp-content/plugins/elementor/elementor.php';
	require '/var/www/html/wp-content/plugins/woocommerce/woocommerce.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';

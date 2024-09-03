<?php
/**
 * Plugin Name: Essential Addons for Elementor
 * Description: The Essential plugin you install after Elementor! Packed with  100+ powerful Elementor widgets & extensions including Advanced Data Table, Event Calendar, Filterable Gallery, WooCommerce, and many more.
 * Plugin URI: https://essential-addons.com/
 * Author: WPDeveloper
 * Version: 6.0.2
 * Author URI: https://wpdeveloper.com/
 * Text Domain: essential-addons-for-elementor-lite
 * Domain Path: /languages
 *
 * WC tested up to: 9.2
 * Elementor tested up to: 3.23
 * Elementor Pro tested up to: 3.23
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Defining plugin constants.
 *
 * @since 3.0.0
 */
define('EAEL_PLUGIN_FILE', __FILE__);
define('EAEL_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('EAEL_PLUGIN_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('EAEL_PLUGIN_URL', trailingslashit(plugins_url('/', __FILE__)));
define('EAEL_PLUGIN_VERSION', '6.0.2');
define('EAEL_ASSET_PATH', wp_upload_dir()['basedir'] . '/essential-addons-elementor');
define('EAEL_ASSET_URL', wp_upload_dir()['baseurl'] . '/essential-addons-elementor');
/**
 * Including composer autoloader globally.
 *
 * @since 3.0.0
 */
require_once EAEL_PLUGIN_PATH . 'autoload.php';

/**
 * Including plugin config.
 *
 * @since 3.0.0
 */
$GLOBALS['eael_config'] = require_once EAEL_PLUGIN_PATH . 'config.php';

/**
 * Run plugin after all others plugins
 *
 * @since 3.0.0
 */
add_action( 'plugins_loaded', function () {
	if ( class_exists( '\Essential_Addons_Elementor\Classes\Bootstrap' ) ) {
		\Essential_Addons_Elementor\Classes\Bootstrap::instance();
	}
} );

/**
 * Plugin migrator
 *
 * @since v3.0.0
 */
add_action('wp_loaded', function () {
    $migration = new \Essential_Addons_Elementor\Classes\Migration;
    $migration->migrator();
});

/**
 * Activation hook
 *
 * @since v3.0.0
 */
register_activation_hook(__FILE__, function () {
    $migration = new \Essential_Addons_Elementor\Classes\Migration;
    $migration->plugin_activation_hook();
});

/**
 * Deactivation hook
 *
 * @since v3.0.0
 */
register_deactivation_hook(__FILE__, function () {
    $migration = new \Essential_Addons_Elementor\Classes\Migration;
    $migration->plugin_deactivation_hook();
});

/**
 * Upgrade hook
 *
 * @since v3.0.0
 */
add_action( 'upgrader_process_complete', function ( $upgrader_object, $options ) {
	if ( class_exists( '\Essential_Addons_Elementor\Classes\Migration' ) ) {
		$migration = new \Essential_Addons_Elementor\Classes\Migration;
		$migration->plugin_upgrade_hook( $upgrader_object, $options );
	}
}, 10, 2 );

add_action( 'wp_loaded', function () {
    $setup_wizard = get_option( 'eael_setup_wizard' );
    if ( $setup_wizard == 'redirect' ) {
        \Essential_Addons_Elementor\Classes\WPDeveloper_Setup_Wizard::redirect();
    }

    if ( $setup_wizard == 'init' ) {
        new \Essential_Addons_Elementor\Classes\WPDeveloper_Setup_Wizard();
    }
} );

/**
 * WooCommerce HPOS Support
 *
 * @since v5.8.2
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );
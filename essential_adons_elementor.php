<?php
/**
 * Plugin Name: Essential Addons for Elementor
 * Description: The ultimate elements library for Elementor page builder plugin for WordPress.
 * Plugin URI: https://essential-addons.com/elementor/
 * Author: WPDeveloper
 * Version: 2.10.3
 * Author URI: https://wpdeveloper.net/
 *
 * Text Domain: essential-addons-elementor
 * Domain Path: /languages
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
define('EAEL_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('EAEL_PLUGIN_URL', plugins_url('/', __FILE__));
define('EAEL_PLUGIN_VERSION', '2.10.3');
define('EAEL_ASSET_PATH', wp_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . 'essential-addons-elementor');
define('EAEL_ASSET_URL', wp_upload_dir()['baseurl'] . '/essential-addons-elementor');

/**
 * Including composer autoloader globally.
 *
 * @since 3.0.0
 */
$GLOBALS['Essential_Addons_Elementor_Loader'] = require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

/**
 * Run plugin after all others plugins
 *
 * @since 3.0.0
 */
add_action('plugins_loaded', function () {
    \Essential_Addons_Elementor\Classes\Bootstrap::instance();
});

/**
 * Activation hook
 *
 * @since v3.0.0
 */
register_activation_hook(__FILE__, function () {
    \Essential_Addons_Elementor\Classes\Migration::plugin_activation_hook();
});

/**
 * Deactivation hook
 *
 * @since v3.0.0
 */
register_deactivation_hook(__FILE__, function () {
    \Essential_Addons_Elementor\Classes\Migration::plugin_deactivation_hook();
});

/**
 * Upgrade hook
 *
 * @since v3.0.0
 */
add_action('upgrader_process_complete', function ($upgrader_object, $options) {
    \Essential_Addons_Elementor\Classes\Migration::plugin_upgrade_hook($upgrader_object, $options);
}, 10, 2);

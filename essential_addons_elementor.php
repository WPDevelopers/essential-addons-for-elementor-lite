<?php
/**
 * Plugin Name: Essential Addons for Elementor
 * Description: The ultimate elements library for Elementor page builder plugin for WordPress.
 * Plugin URI: https://essential-addons.com/elementor/
 * Author: WPDeveloper
 * Version: 3.0.0
 * Author URI: https://wpdeveloper.net/
 *
 * Text Domain: essential-addons-elementor
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Including composer autoload.
 *
 * @since 3.0.0
 */
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

class Essential_Addons_Elementor
{
    use Essential_Addons_Elementor\Traits\Core;
    use Essential_Addons_Elementor\Traits\Generator;
    use Essential_Addons_Elementor\Traits\Enqueue;
    use Essential_Addons_Elementor\Traits\Admin;
    use Essential_Addons_Elementor\Traits\Helper;
    use Essential_Addons_Elementor\Traits\Elements;
    
    public $registered_elements;

    public function __construct()
    {
        define('EAEL_PLUGIN_BASENAME', plugin_basename(__FILE__));
        define('EAEL_PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('EAEL_PLUGIN_URL', plugins_url('/', __FILE__));
        define('EAEL_PLUGIN_VERSION', '3.0.0');
        define('EAEL_ASSET_PATH', wp_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . 'essential-addons-elementor');
        define('EAEL_ASSET_URL', wp_upload_dir()['baseurl'] . DIRECTORY_SEPARATOR . 'essential-addons-elementor');

        $this->registered_elements = [
            'contact-form-7',
            'countdown',
            'creative-button',
            'fancy-text',
            'post-grid',
            'post-timeline',
            'product-grid',
            'team-member',
            'testimonial',
            'weform',
            'cta-box',
            'flip-box',
            'info-box',
            'dual-color-header',
            'pricing-table',
            'ninja-form',
            'gravity-form',
            'caldera-form',
            'wpforms',
            'twitter-feed',
            'data-table',
            'filterable-gallery',
            'image-accordion',
            'content-ticker',
            'tooltip',
            'adv-accordion',
            'adv-tabs',
            'progress-bar',
            'feature-list',
        ];

        // Start plugin tracking
        $this->start_plugin_tracking();

        // Generator
        add_action('eael_generate_editor_scripts', array($this, 'generate_scripts'));
        add_action('elementor/editor/after_save', array($this, 'generate_post_scripts'));

        // Enqueue
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Ajax
        add_action('wp_ajax_nopriv_load_more', array($this, 'eael_load_more_ajax'));
        add_action('wp_ajax_load_more', array($this, 'eael_load_more_ajax'));

        // Elements
        add_action('elementor/widgets/widgets_registered', array($this, 'add_eael_elements'));
        add_action('elementor/controls/controls_registered', array($this, 'controls_registered'));
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_widget_categories'));

        if (class_exists('Caldera_Forms')) {
            add_filter('caldera_forms_force_enqueue_styles_early', '__return_true');
        }

        // Admin
        if (is_admin()) {
            add_action('admin_init', array($this, 'admin_notice'));
            add_action('admin_menu', array($this, 'admin_menu'), 600);
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
            add_action('wp_ajax_save_settings_with_ajax', array($this, 'save_settings'));

            // Core Helper - Leave them for now
            add_filter("plugin_action_links_EAEL_PLUGIN_BASENAME", array($this, 'eael_add_settings_link'));
            add_action('admin_init', array($this, 'eael_redirect'));
            add_action('admin_footer-plugins.php', array($this, 'plugins_footer_for_pro'));
            add_filter('plugin_action_links_essential-addons-elementor/essential_adons_elementor.php', array($this, 'eae_pro_filter_action_links'));

            if (!did_action('elementor/loaded')) {
                add_action('admin_notices', array($this, 'eael_is_failed_to_load'));
            }
        }
    }

}
add_action('plugins_loaded', function () {
    new Essential_Addons_Elementor;
});

/**
 * Activation redirects
 *
 * @since v1.0.0
 */
function eael_activate()
{
    update_option('eael_do_activation_redirect', true);
}
register_activation_hook(__FILE__, 'eael_activate');

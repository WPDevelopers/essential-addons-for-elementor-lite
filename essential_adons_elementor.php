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
    use Essential_Addons_Elementor\Traits\Helper;
    use Essential_Addons_Elementor\Traits\Generator;
    use Essential_Addons_Elementor\Traits\Enqueue;
    use Essential_Addons_Elementor\Traits\Admin;
    use Essential_Addons_Elementor\Traits\Elements;

    public $registered_elements;

    public function __construct()
    {
        // define plugins constants
        define('EAEL_PLUGIN_FILE', __FILE__);
        define('EAEL_PLUGIN_BASENAME', plugin_basename(__FILE__));
        define('EAEL_PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('EAEL_PLUGIN_URL', plugins_url('/', __FILE__));
        define('EAEL_PLUGIN_VERSION', '3.0.0');
        define('EAEL_ASSET_PATH', wp_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . 'essential-addons-elementor');
        define('EAEL_ASSET_URL', wp_upload_dir()['baseurl'] . '/essential-addons-elementor');

        // define elements classmap
        $this->registered_elements = [
            'post-grid' => [
                'class' => 'Eael_Post_Grid',
            ],
            'post-timeline' => [
                'class' => 'Eael_Post_Timeline',
            ],
            'fancy-text' => [
                'class' => 'Eael_Fancy_Text',
            ],
            'creative-button' => [
                'class' => 'Eael_Creative_Button',
            ],
            'countdown' => [
                'class' => 'Eael_Countdown',
            ],
            'team-member' => [
                'class' => 'Eael_Team_Member',
            ],
            'testimonial' => [
                'class' => 'Eael_Testimonial',
            ],
            'info-box' => [
                'class' => 'Eael_Info_Box',
            ],
            'flip-box' => [
                'class' => 'Eael_Flip_Box',
            ],
            'cta-box' => [
                'class' => 'Eael_Cta_Box',
            ],
            'dual-color-header' => [
                'class' => 'Eael_Dual_Color_Header',
            ],
            'pricing-table' => [
                'class' => 'Eael_Pricing_Table',
            ],
            'twitter-feed' => [
                'class' => 'Eael_Twitter_Feed',
            ],
            'data-table' => [
                'class' => 'Eael_Data_Table',
            ],
            'filterable-gallery' => [
                'class' => 'Eael_Filterable_Gallery',
            ],
            'image-accordion' => [
                'class' => 'Eael_Image_Accordion',
            ],
            'content-ticker' => [
                'class' => 'Eael_Content_Ticker',
            ],
            'tooltip' => [
                'class' => 'Eael_Tooltip',
            ],
            'adv-accordion' => [
                'class' => 'Eael_Adv_Accordion',
            ],
            'adv-tabs' => [
                'class' => 'Eael_Adv_Tabs',
            ],
            'progress-bar' => [
                'class' => 'Eael_Progress_Bar',
            ],
            'feature-list' => [
                'class' => 'Eael_Feature_List',
            ],
            'product-grid' => [
                'class' => 'Eael_Product_Grid',
                'condition' => [
                    'function_exists',
                    'WC',
                ],
            ],
            'contact-form-7' => [
                'class' => 'Eael_Contact_Form_7',
                'condition' => [
                    'function_exists',
                    'wpcf7',
                ],
            ],
            'weform' => [
                'class' => 'Eael_WeForms',
                'condition' => [
                    'function_exists',
                    'WeForms',
                ],
            ],
            'ninja-form' => [
                'class' => 'Eael_NinjaForms',
                'condition' => [
                    'function_exists',
                    'Ninja_Forms',
                ],
            ],
            'gravity-form' => [
                'class' => 'Eael_GravityForms',
                'condition' => [
                    'class_exists',
                    'GFForms',
                ],
            ],
            'caldera-form' => [
                'class' => 'Eael_Caldera_Forms',
                'condition' => [
                    'class_exists',
                    'Caldera_Forms',
                ],
            ],
            'wpforms' => [
                'class' => 'Eael_WpForms',
                'condition' => [
                    'class_exists',
                    '\WPForms\WPForms',
                ],
            ],
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
        add_action('elementor/widgets/widgets_registered', array($this, 'eael_add_elements'));
        add_action('elementor/controls/controls_registered', array($this, 'controls_registered'));
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_widget_categories'));

        if (class_exists('Caldera_Forms')) {
            add_filter('caldera_forms_force_enqueue_styles_early', '__return_true');
        }

        // Admin
        if (is_admin()) {
            // Admin
            $this->admin_notice();
            add_action('admin_menu', array($this, 'admin_menu'), 600);
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
            add_action('wp_ajax_save_settings_with_ajax', array($this, 'save_settings'));

            // Core
            add_filter('plugin_action_links_' . EAEL_PLUGIN_BASENAME, array($this, 'eael_add_settings_link'));
            add_filter('plugin_action_links_' . EAEL_PLUGIN_BASENAME, array($this, 'eael_pro_filter_action_links'));
            add_action('admin_init', array($this, 'eael_redirect'));
            add_action('admin_footer-plugins.php', array($this, 'plugins_footer_for_pro'));

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
register_activation_hook(__FILE__, function () {
    update_option('eael_do_activation_redirect', true);
});

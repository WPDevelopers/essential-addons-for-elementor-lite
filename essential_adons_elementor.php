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
 * Including composer autoload.
 *
 * @since 3.0.0
 */
$GLOBALS['Essential_Addons_Elementor_Loader'] = require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

/**
 * Skeleton of Essential Addons
 *
 * @since 3.0.0
 */
class Essential_Addons_Elementor
{
    use Essential_Addons_Elementor\Traits\Core;
    use Essential_Addons_Elementor\Traits\Helper;
    use Essential_Addons_Elementor\Traits\Generator;
    use Essential_Addons_Elementor\Traits\Enqueue;
    use Essential_Addons_Elementor\Traits\Admin;
    use Essential_Addons_Elementor\Traits\Elements;

    public $registered_elements;
    public $transient_elements;
    private static $instance = null;

    
    public static function instance() {
        if(self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Constructor of plugin class
     *
     * @since 3.0.0
     */
    public function __construct()
    {
        // before load hook
        do_action('eael_before_loaded');
        error_log(print_r(did_action('eael_before_loaded'), 1));

        // define plugins constants
        define('EAEL_PLUGIN_FILE', __FILE__);
        define('EAEL_PLUGIN_BASENAME', plugin_basename(__FILE__));
        define('EAEL_PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('EAEL_PLUGIN_URL', plugins_url('/', __FILE__));
        define('EAEL_PLUGIN_VERSION', '2.10.3');
        define('EAEL_ASSET_PATH', wp_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . 'essential-addons-elementor');
        define('EAEL_ASSET_URL', wp_upload_dir()['baseurl'] . '/essential-addons-elementor');

        // define elements classmap
        $this->registered_elements = apply_filters('eael_registered_elements', [
            'post-grid' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Post_Grid',
                'dependency' => [
                    'css' => [
                        'assets/front-end/css/product-grid.css',
                    ],
                    'js' => [
                        'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                        'assets/front-end/js/vendor/load-more/load-more.js',
                    ],
                ],
            ],
            'post-timeline' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Post_Timeline',
                'dependency' => [
                    'js' => [
                        'assets/front-end/js/vendor/load-more/load-more.js',
                    ],
                ],
            ],
            'fancy-text' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Fancy_Text',
                'dependency' => [
                    'js' => [
                        'assets/front-end/js/vendor/fancy-text/fancy-text.js',
                    ],
                ],
            ],
            'creative-btn' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Creative_Button',
            ],
            'count-down' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Countdown',
                'dependency' => [
                    'js' => [
                        'assets/front-end/js/vendor/count-down/count-down.min.js',
                    ],
                ],
            ],
            'team-members' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Team_Member',
            ],
            'testimonials' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Testimonial',
            ],
            'info-box' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Info_Box',
            ],
            'flip-box' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Flip_Box',
            ],
            'call-to-action' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Cta_Box',
            ],
            'dual-header' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Dual_Color_Header',
            ],
            'price-table' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Pricing_Table',
                'dependency' => [
                    'js' => [
                        'assets/front-end/js/vendor/tooltipster/tooltipster.bundle.min.js',
                    ],
                ],
            ],
            'twitter-feed' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Twitter_Feed',
                'dependency' => [
                    'js' => [
                        'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                        'assets/front-end/js/vendor/social-feeds/codebird.js',
                        'assets/front-end/js/vendor/social-feeds/doT.min.js',
                        'assets/front-end/js/vendor/social-feeds/moment.js',
                        'assets/front-end/js/vendor/social-feeds/jquery.socialfeed.js',
                    ],
                ],
            ],
            'data-table' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Data_Table',
            ],
            'filter-gallery' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Filterable_Gallery',
                'dependency' => [
                    'css' => [
                        'assets/front-end/css/magnific-popup.css',
                    ],
                    'js' => [
                        'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                        'assets/front-end/js/vendor/magnific-popup/jquery.magnific-popup.min.js',
                    ],
                ],
            ],
            'image-accordion' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Image_Accordion',
            ],
            'content-ticker' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Content_Ticker',
            ],
            'tooltip' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Tooltip',
            ],
            'adv-accordion' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Adv_Accordion',
            ],
            'adv-tabs' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Adv_Tabs',
            ],
            'progress-bar' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Progress_Bar',
                'dependency' => [
                    'js' => [
                        'assets/front-end/js/vendor/progress-bar/progress-bar.min.js',
                        'assets/front-end/js/vendor/inview/inview.min.js',
                    ],
                ],
            ],
            'feature-list' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Feature_List',
            ],
            'product-grid' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Product_Grid',
                'condition' => [
                    'function_exists',
                    'WC',
                ],
            ],
            'contact-form-7' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Contact_Form_7',
                'condition' => [
                    'function_exists',
                    'wpcf7',
                ],
            ],
            'weforms' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_WeForms',
                'condition' => [
                    'function_exists',
                    'WeForms',
                ],
            ],
            'ninja-form' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_NinjaForms',
                'condition' => [
                    'function_exists',
                    'Ninja_Forms',
                ],
            ],
            'gravity-form' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_GravityForms',
                'condition' => [
                    'class_exists',
                    'GFForms',
                ],
            ],
            'caldera-form' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Caldera_Forms',
                'condition' => [
                    'class_exists',
                    'Caldera_Forms',
                ],
            ],
            'wpforms' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_WpForms',
                'condition' => [
                    'class_exists',
                    '\WPForms\WPForms',
                ],
            ],
        ]);

        // initialize transient elements
        $this->transient_elements = [];
    }

    public function run()
    {
        do_action('eael_before_loaded');

        // Start plugin tracking
        $this->start_plugin_tracking();

        // Generator
        add_action('elementor/frontend/before_render', array($this, 'collect_transient_elements'));
        add_action('loop_end', array($this, 'generate_frontend_scripts'));

        // Enqueue
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Ajax
        add_action('wp_ajax_load_more', array($this, 'eael_load_more_ajax'));
        add_action('wp_ajax_nopriv_load_more', array($this, 'eael_load_more_ajax'));

        // Elements
        add_action('elementor/widgets/widgets_registered', array($this, 'eael_add_elements'));
        add_action('elementor/controls/controls_registered', array($this, 'controls_registered'));
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_widget_categories'));

        // Admin
        if (is_admin()) {
            // Admin
            $this->admin_notice();
            add_action('admin_menu', array($this, 'admin_menu'), 600);
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
            add_action('wp_ajax_save_settings_with_ajax', array($this, 'save_settings'));
            add_action('wp_ajax_clear_cache_files_with_ajax', array($this, 'clear_cache_files'));

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


/**
 * Run plugin
 *
 * @since 3.0.0
 */
add_action('plugins_loaded', function () {

    /**
     * Global instance of Essential Addons
     *
     * @since 3.0.0
     */
    $GLOBALS['Essential_Addons_Elementor'] = Essential_Addons_Elementor::instance();

    global $Essential_Addons_Elementor;
    $Essential_Addons_Elementor->run();
});

/**
 * Activation hook
 *
 * @since v3.0.0
 */
register_activation_hook(__FILE__, function () {
    global $Essential_Addons_Elementor;
    $Essential_Addons_Elementor->plugin_activation_hook();
});

/**
 * Deactivation hook
 *
 * @since v3.0.0
 */
register_deactivation_hook(__FILE__, function () {
    global $Essential_Addons_Elementor;
    $Essential_Addons_Elementor->plugin_deactivation_hook();
});

/**
 * Upgrade hook
 *
 * @since v3.0.0
 */
add_action('upgrader_process_complete', function () {
    global $Essential_Addons_Elementor;
    $Essential_Addons_Elementor->plugin_upgrade_hook();
});

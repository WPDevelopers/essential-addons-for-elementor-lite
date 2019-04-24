<?php

namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class Bootstrap
{
    use \Essential_Addons_Elementor\Traits\Core;
    use \Essential_Addons_Elementor\Traits\Helper;
    use \Essential_Addons_Elementor\Traits\Generator;
    use \Essential_Addons_Elementor\Traits\Enqueue;
    use \Essential_Addons_Elementor\Traits\Admin;
    use \Essential_Addons_Elementor\Traits\Elements;

    // instance container
    private static $instance = null;

    // registered elements container
    public $registered_elements;

    // transient elements container
    public $transient_elements;

    /**
     * Singleton instance
     *
     * @since 3.0.0
     */
    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Constructor of plugin class
     *
     * @since 3.0.0
     */
    private function __construct()
    {
        // before init hook
        do_action('eael_before_init');

        // elements classmap
        $this->registered_elements = apply_filters('eael_registered_elements', [
            'post-grid' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Post_Grid',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/product-grid.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/load-more/load-more.js',
                    ],
                ],
            ],
            'post-timeline' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Post_Timeline',
                'dependency' => [
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/load-more/load-more.js',
                    ],
                ],
            ],
            'fancy-text' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Fancy_Text',
                'dependency' => [
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/fancy-text/fancy-text.js',
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
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/count-down/count-down.min.js',
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
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/tooltipster/tooltipster.bundle.min.js',
                    ],
                ],
            ],
            'twitter-feed' => [
                'class' => '\Essential_Addons_Elementor\Elements\Eael_Twitter_Feed',
                'dependency' => [
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/social-feeds/codebird.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/social-feeds/doT.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/social-feeds/moment.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/social-feeds/jquery.socialfeed.js',
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
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/magnific-popup.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/magnific-popup/jquery.magnific-popup.min.js',
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
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/progress-bar/progress-bar.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/inview/inview.min.js',
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

        // initialize transient container
        $this->transient_elements = [];

        // Start plugin tracking
        $this->start_plugin_tracking();

        // Register hooks
        $this->register_hooks();
    }

    public function register_hooks()
    {
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

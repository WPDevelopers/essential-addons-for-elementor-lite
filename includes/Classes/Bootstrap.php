<?php

namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class Bootstrap
{
    use \Essential_Addons_Elementor\Traits\Library;
    use \Essential_Addons_Elementor\Traits\Shared;
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

    // registered extensions container
    public $registered_extensions;

    // transient elements container
    public $transient_elements;

    // transient elements container
    public $transient_extensions;

    // identify whether pro is enabled
    public $pro_enabled;

    // localize objects
    public $localize_objects;

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
        do_action('eael/before_init');

        // search for pro version
        $this->pro_enabled = apply_filters('eael/pro_enabled', false);

        // elements classmap
        $this->registered_elements = apply_filters('eael/registered_elements', [
            'post-grid' => [
                'class' => '\Essential_Addons_Elementor\Elements\Post_Grid',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/components/load-more.min.css',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/post-grid/index.min.css',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/product-grid/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/load-more/load-more.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/post-grid/index.min.js',
                    ],
                ],
            ],
            'post-timeline' => [
                'class' => '\Essential_Addons_Elementor\Elements\Post_Timeline',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/components/load-more.min.css',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/post-timeline/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/load-more/load-more.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/post-timeline/index.min.js',
                    ],
                ],
            ],
            'fancy-text' => [
                'class' => '\Essential_Addons_Elementor\Elements\Fancy_Text',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/fancy-text/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/fancy-text/fancy-text.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/fancy-text/index.min.js',
                    ],
                ],
            ],
            'creative-btn' => [
                'class' => '\Essential_Addons_Elementor\Elements\Creative_Button',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/creative-btn/index.min.css',
                    ],
                ],
            ],
            'count-down' => [
                'class' => '\Essential_Addons_Elementor\Elements\Countdown',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/count-down/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/count-down/countdown.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/count-down/index.min.js',
                    ],
                ],
            ],
            'team-members' => [
                'class' => '\Essential_Addons_Elementor\Elements\Team_Member',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/team-members/index.min.css',
                    ],
                ],
            ],
            'testimonials' => [
                'class' => '\Essential_Addons_Elementor\Elements\Testimonial',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/testimonials/index.min.css',
                    ],
                ],
            ],
            'info-box' => [
                'class' => '\Essential_Addons_Elementor\Elements\Info_Box',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/info-box/index.min.css',
                    ],
                ],
            ],
            'flip-box' => [
                'class' => '\Essential_Addons_Elementor\Elements\Flip_Box',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/flip-box/index.min.css',
                    ],
                ],
            ],
            'call-to-action' => [
                'class' => '\Essential_Addons_Elementor\Elements\Cta_Box',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/call-to-action/index.min.css',
                    ],
                ],
            ],
            'dual-header' => [
                'class' => '\Essential_Addons_Elementor\Elements\Dual_Color_Header',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/dual-header/index.min.css',
                    ],
                ],
            ],
            'price-table' => [
                'class' => '\Essential_Addons_Elementor\Elements\Pricing_Table',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/vendor/tooltipster/tooltipster.bundle.min.css',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/price-table/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/tooltipster/tooltipster.bundle.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/price-table/index.min.js',
                    ],
                ],
            ],
            'twitter-feed' => [
                'class' => '\Essential_Addons_Elementor\Elements\Twitter_Feed',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/twitter-feed/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/social-feeds/codebird.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/social-feeds/doT.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/social-feeds/moment.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/social-feeds/jquery.socialfeed.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/twitter-feed/index.min.js',
                    ],
                ],
            ],
            'data-table' => [
                'class' => '\Essential_Addons_Elementor\Elements\Data_Table',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/data-table/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/data-table/index.min.js',
                    ],
                ],
            ],
            'filter-gallery' => [
                'class' => '\Essential_Addons_Elementor\Elements\Filterable_Gallery',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/components/load-more.min.css',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/magnific-popup/index.min.css',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/filter-gallery/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/imagesLoaded/imagesloaded.pkgd.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/magnific-popup/jquery.magnific-popup.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/filter-gallery/index.min.js',
                    ],
                ],
            ],
            'image-accordion' => [
                'class' => '\Essential_Addons_Elementor\Elements\Image_Accordion',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/image-accordion/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/image-accordion/index.min.js',
                    ],
                ],
            ],
            'content-ticker' => [
                'class' => '\Essential_Addons_Elementor\Elements\Content_Ticker',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/content-ticker/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/content-ticker/index.min.js',
                    ],
                ],
            ],
            'tooltip' => [
                'class' => '\Essential_Addons_Elementor\Elements\Tooltip',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/tooltip/index.min.css',
                    ],
                ],
            ],
            'adv-accordion' => [
                'class' => '\Essential_Addons_Elementor\Elements\Adv_Accordion',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/adv-accordion/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/adv-accordion/index.min.js',
                    ],
                ],
            ],
            'adv-tabs' => [
                'class' => '\Essential_Addons_Elementor\Elements\Adv_Tabs',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/adv-tabs/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/adv-tabs/index.min.js',
                    ],
                ],
            ],
            'progress-bar' => [
                'class' => '\Essential_Addons_Elementor\Elements\Progress_Bar',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/progress-bar/index.min.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/inview/inview.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/progress-bar/progress-bar.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/progress-bar/index.min.js',
                    ],
                ],
            ],
            'feature-list' => [
                'class' => '\Essential_Addons_Elementor\Elements\Feature_List',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/feature-list/index.min.css',
                    ],
                ],
            ],
            'product-grid' => [
                'class' => '\Essential_Addons_Elementor\Elements\Product_Grid',
                'condition' => [
                    'function_exists',
                    'WC',
                ],
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/product-grid/index.min.css',
                    ],
                ],
            ],
            'contact-form-7' => [
                'class' => '\Essential_Addons_Elementor\Elements\Contact_Form_7',
                'condition' => [
                    'function_exists',
                    'wpcf7',
                ],
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/contact-form-7/index.min.css',
                    ],
                ],
            ],
            'weforms' => [
                'class' => '\Essential_Addons_Elementor\Elements\WeForms',
                'condition' => [
                    'function_exists',
                    'WeForms',
                ],
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/weforms/index.min.css',
                    ],
                ],
            ],
            'ninja-form' => [
                'class' => '\Essential_Addons_Elementor\Elements\NinjaForms',
                'condition' => [
                    'function_exists',
                    'Ninja_Forms',
                ],
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/ninja-form/index.min.css',
                    ],
                ],
            ],
            'gravity-form' => [
                'class' => '\Essential_Addons_Elementor\Elements\GravityForms',
                'condition' => [
                    'class_exists',
                    'GFForms',
                ],
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/gravity-form/index.min.css',
                    ],
                ],
            ],
            'caldera-form' => [
                'class' => '\Essential_Addons_Elementor\Elements\Caldera_Forms',
                'condition' => [
                    'class_exists',
                    'Caldera_Forms',
                ],
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/caldera-form/index.min.css',
                    ],
                ],
            ],
            'wpforms' => [
                'class' => '\Essential_Addons_Elementor\Elements\WpForms',
                'condition' => [
                    'class_exists',
                    '\WPForms\WPForms',
                ],
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/wpforms/index.min.css',
                    ],
                ],
            ],
        ]);

        // extensions classmap
        $this->registered_extensions = apply_filters('eael/registered_extensions', []);

        // initialize transient container
        $this->transient_elements = [];
        $this->transient_extensions = [];

        // start plugin tracking
        if (!$this->pro_enabled) {
            $this->start_plugin_tracking();
        }

        // post args
        $this->post_args = apply_filters('eael/post_args', $this->post_args);

        // register extensions
        $this->register_extensions();

        // register hooks
        $this->register_hooks();
    }

    protected function register_hooks()
    {
        // Generator
        add_action('elementor/frontend/before_render', array($this, 'collect_transient_elements'));
        add_action('wp_footer', array($this, 'generate_frontend_scripts'));

        // Enqueue
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Ajax
        add_action('wp_ajax_load_more', array($this, 'eael_load_more_ajax'));
        add_action('wp_ajax_nopriv_load_more', array($this, 'eael_load_more_ajax'));

        // Elements
        add_action('elementor/elements/categories_registered', array($this, 'register_widget_categories'));
        add_action('elementor/controls/controls_registered', array($this, 'register_controls_group'));
        add_action('elementor/widgets/widgets_registered', array($this, 'register_elements'));

        // Admin
        if (is_admin()) {
            // Admin
            if (!$this->pro_enabled) {
                $this->admin_notice();
            }

            add_action('admin_menu', array($this, 'admin_menu'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
            add_action('wp_ajax_save_settings_with_ajax', array($this, 'save_settings'));
            add_action('wp_ajax_clear_cache_files_with_ajax', array($this, 'clear_cache_files'));

            // Core
            add_filter('plugin_action_links_' . EAEL_PLUGIN_BASENAME, array($this, 'insert_plugin_links'));
            add_filter('plugin_row_meta', array($this, 'insert_plugin_row_meta'), 10, 2);
            add_action('admin_init', array($this, 'redirect_on_activation'));

            if (!did_action('elementor/loaded')) {
                add_action('admin_notices', array($this, 'elementor_not_loaded'));
            }
        }
    }
}

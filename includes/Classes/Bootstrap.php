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

    // registered extensions container
    public $registered_extensions;

    // transient elements container
    public $transient_elements;

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
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/post-grid.css',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/product-grid.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/load-more/load-more.js',
                    ],
                ],
            ],
            'post-timeline' => [
                'class' => '\Essential_Addons_Elementor\Elements\Post_Timeline',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/post-timeline.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/load-more/load-more.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/post-timeline/index.js',
                    ],
                ],
            ],
            'fancy-text' => [
                'class' => '\Essential_Addons_Elementor\Elements\Fancy_Text',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/fancy-text.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/fancy-text/fancy-text.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/fancy-text/index.js',
                    ],
                ],
            ],
            'creative-btn' => [
                'class' => '\Essential_Addons_Elementor\Elements\Creative_Button',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/creative-btn.css',
                    ],
                ],
            ],
            'count-down' => [
                'class' => '\Essential_Addons_Elementor\Elements\Countdown',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/count-down.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/count-down/count-down.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/count-down/index.js',
                    ],
                ],
            ],
            'team-members' => [
                'class' => '\Essential_Addons_Elementor\Elements\Team_Member',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/team-members.css',
                    ],
                ],
            ],
            'testimonials' => [
                'class' => '\Essential_Addons_Elementor\Elements\Testimonial',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/testimonials.css',
                    ],
                ],
            ],
            'info-box' => [
                'class' => '\Essential_Addons_Elementor\Elements\Info_Box',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/info-box.css',
                    ],
                ],
            ],
            'flip-box' => [
                'class' => '\Essential_Addons_Elementor\Elements\Flip_Box',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/flip-box.css',
                    ],
                ],
            ],
            'call-to-action' => [
                'class' => '\Essential_Addons_Elementor\Elements\Cta_Box',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/call-to-action.css',
                    ],
                ],
            ],
            'dual-header' => [
                'class' => '\Essential_Addons_Elementor\Elements\Dual_Color_Header',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/dual-header.css',
                    ],
                ],
            ],
            'price-table' => [
                'class' => '\Essential_Addons_Elementor\Elements\Pricing_Table',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/price-table.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/tooltipster/tooltipster.bundle.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/price-table/index.js',
                    ],
                ],
            ],
            'twitter-feed' => [
                'class' => '\Essential_Addons_Elementor\Elements\Twitter_Feed',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/twitter-feed.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/social-feeds/codebird.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/social-feeds/doT.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/social-feeds/moment.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/social-feeds/jquery.socialfeed.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/twitter-feed/index.js',
                    ],
                ],
            ],
            'data-table' => [
                'class' => '\Essential_Addons_Elementor\Elements\Data_Table',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/data-table.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/data-table/index.js',
                    ],
                ],
            ],
            'filter-gallery' => [
                'class' => '\Essential_Addons_Elementor\Elements\Filterable_Gallery',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/magnific-popup.css',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/filter-gallery.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/magnific-popup/jquery.magnific-popup.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/filter-gallery/index.js',
                    ],
                ],
            ],
            'image-accordion' => [
                'class' => '\Essential_Addons_Elementor\Elements\Image_Accordion',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/image-accordion.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/image-accordion/index.js',
                    ],
                ],
            ],
            'content-ticker' => [
                'class' => '\Essential_Addons_Elementor\Elements\Content_Ticker',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/content-ticker.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/content-ticker/index.js',
                    ],
                ],
            ],
            'tooltip' => [
                'class' => '\Essential_Addons_Elementor\Elements\Tooltip',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/tooltip.css',
                    ],
                ],
            ],
            'adv-accordion' => [
                'class' => '\Essential_Addons_Elementor\Elements\Adv_Accordion',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/adv-accordion.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/adv-accordion/index.js',
                    ],
                ],
            ],
            'adv-tabs' => [
                'class' => '\Essential_Addons_Elementor\Elements\Adv_Tabs',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/adv-tabs.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/adv-tabs/index.js',
                    ],
                ],
            ],
            'progress-bar' => [
                'class' => '\Essential_Addons_Elementor\Elements\Progress_Bar',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/progress-bar.css',
                    ],
                    'js' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/progress-bar/progress-bar.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/inview/inview.min.js',
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/progress-bar/index.js',
                    ],
                ],
            ],
            'feature-list' => [
                'class' => '\Essential_Addons_Elementor\Elements\Feature_List',
                'dependency' => [
                    'css' => [
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/feature-list.css',
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
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/product-grid.css',
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
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/contact-form-7.css',
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
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/weforms.css',
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
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/ninja-form.css',
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
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/gravity-form.css',
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
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/caldera-form.css',
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
                        EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/wpforms.css',
                    ],
                ],
            ],
        ]);

        // extensions classmap
        $this->registered_extensions = apply_filters('eael/registered_extensions', []);

        // initialize transient container
        $this->transient_elements = [];

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
        add_action('loop_end', array($this, 'generate_frontend_scripts'));

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
            add_action('admin_init', array($this, 'redirect_on_activation'));

            if (!did_action('elementor/loaded')) {
                add_action('admin_notices', array($this, 'elementor_not_loaded'));
            }
        }
    }
}

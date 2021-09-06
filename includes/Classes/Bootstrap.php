<?php

namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use Essential_Addons_Elementor\Traits\Admin;
use Essential_Addons_Elementor\Traits\Core;
use Essential_Addons_Elementor\Traits\Elements;
use Essential_Addons_Elementor\Traits\Enqueue;
use Essential_Addons_Elementor\Traits\Generator;
use Essential_Addons_Elementor\Traits\Helper;
use Essential_Addons_Elementor\Traits\Library;
use Essential_Addons_Elementor\Traits\Login_Registration;
use Essential_Addons_Elementor\Traits\Woo_Product_Comparable;
use Essential_Addons_Elementor\Traits\Controls;
use Essential_Addons_Elementor\Traits\Facebook_Feed;


class Bootstrap
{
    use Library;
    use Core;
    use Helper;
    use Generator;
    use Enqueue;
    use Admin;
    use Elements;
    use Login_Registration;
    use Woo_Product_Comparable;
    use Controls;
    use Facebook_Feed;

    // instance container
    private static $instance = null;

    // request unique id container
    protected $uid = null;

    // registered elements container
    protected $registered_elements;

    // registered extensions container
    protected $registered_extensions;

    // identify whether pro is enabled
    protected $pro_enabled;

    // localize objects
    public $localize_objects = [];

    // request data container
    protected $request_requires_update;

    // loaded templates in a request
    protected $loaded_templates = [];

    // loaded elements in a request
    protected $loaded_elements = [];

    // used for internal css
    protected $css_strings;

    // used for internal js
    protected $js_strings;

    // used to store custom js
    protected $custom_js_strings;

    // modules
    protected $installer;

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
        // init modules
        $this->installer = new WPDeveloper_Plugin_Installer();

        // before init hook
        do_action('eael/before_init');

        // search for pro version
        $this->pro_enabled = apply_filters('eael/pro_enabled', false);

        // elements classmap
        $this->registered_elements = apply_filters('eael/registered_elements', $GLOBALS['eael_config']['elements']);

        // extensions classmap
        $this->registered_extensions = apply_filters('eael/registered_extensions', $GLOBALS['eael_config']['extensions']);

        // start plugin tracking
        if (!$this->pro_enabled) {
            $this->start_plugin_tracking();
        }

        // register extensions
        $this->register_extensions();

        // register hooks
        $this->register_hooks();

    }

    protected function register_hooks()
    {
        // Core
        add_action('init', [$this, 'i18n']);
        // TODO::RM
        add_filter('eael/active_plugins', [$this, 'is_plugin_active'], 10, 1);

        add_filter('eael/is_plugin_active', [$this, 'is_plugin_active'], 10, 1);
        add_action('elementor/editor/after_save', array($this, 'save_global_values'), 10, 2);

        // Enqueue
        add_action('eael/before_enqueue_styles', [$this, 'before_enqueue_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'editor_enqueue_scripts']);
        add_action('wp_head', [$this, 'enqueue_inline_styles']);
        add_action('wp_footer', [$this, 'enqueue_inline_scripts']);

        // Generator
        add_action('wp', [$this, 'init_request_data']);
        add_filter('elementor/frontend/builder_content_data', [$this, 'collect_loaded_templates'], 10, 2);
        add_action('wp_print_footer_scripts', [$this, 'update_request_data']);


        // Ajax
        add_action('wp_ajax_load_more', array($this, 'ajax_load_more'));
        add_action('wp_ajax_nopriv_load_more', array($this, 'ajax_load_more'));

        add_action('wp_ajax_woo_product_pagination_product', array($this, 'eael_woo_pagination_product_ajax'));
        add_action('wp_ajax_nopriv_woo_product_pagination_product', array($this, 'eael_woo_pagination_product_ajax'));

        add_action('wp_ajax_woo_product_pagination', array($this, 'eael_woo_pagination_ajax'));
        add_action('wp_ajax_nopriv_woo_product_pagination', array($this, 'eael_woo_pagination_ajax'));

        //ajax add to cart fro product grid quick view
        add_action('wp_ajax_eael_product_add_to_cart', array($this, 'eael_product_add_to_cart'));
        add_action('wp_ajax_nopriv_eael_product_add_to_cart', array($this, 'eael_product_add_to_cart'));

        add_action('wp_ajax_facebook_feed_load_more', [$this, 'facebook_feed_render_items']);
        add_action('wp_ajax_nopriv_facebook_feed_load_more', [$this, 'facebook_feed_render_items']);

        add_action('wp_ajax_woo_checkout_update_order_review', [$this, 'woo_checkout_update_order_review']);
        add_action('wp_ajax_nopriv_woo_checkout_update_order_review', [$this, 'woo_checkout_update_order_review']);
        // Compare table
	    add_action( 'wp_ajax_nopriv_eael_product_grid', [$this, 'get_compare_table']);
	    add_action( 'wp_ajax_eael_product_grid', [$this, 'get_compare_table']);
		//quick view popup
	    add_action( 'wp_ajax_nopriv_eael_product_quickview_popup', [$this, 'eael_product_quickview_popup']);
	    add_action( 'wp_ajax_eael_product_quickview_popup', [$this, 'eael_product_quickview_popup']);

	    //product gallery
	    add_action( 'wp_ajax_nopriv_eael_product_gallery', [$this, 'ajax_eael_product_gallery']);
	    add_action( 'wp_ajax_eael_product_gallery', [$this, 'ajax_eael_product_gallery']);

//        handle select2 ajax search
        add_action('wp_ajax_eael_select2_search_post', [$this, 'select2_ajax_posts_filter_autocomplete']);
        add_action('wp_ajax_nopriv_eael_select2_search_post', [$this, 'select2_ajax_posts_filter_autocomplete']);

        add_action('wp_ajax_eael_select2_get_title', [$this, 'select2_ajax_get_posts_value_titles']);
        add_action('wp_ajax_nopriv_eael_select2_get_title', [$this, 'select2_ajax_get_posts_value_titles']);

        // Elements
        add_action('elementor/controls/controls_registered', array($this, 'register_controls'));
        add_action('elementor/elements/categories_registered', array($this, 'register_widget_categories'));
        add_action('elementor/widgets/widgets_registered', array($this, 'register_elements'));
        add_filter('elementor/editor/localize_settings', [$this, 'promote_pro_elements']);
        add_action('wp_footer', [$this, 'render_global_html']);

        // Controls
        add_action('eael/controls/query', [$this, 'query'], 10, 1);
        add_action('eael/controls/betterdocs/query', [$this, 'betterdocs_query'], 10, 1);
        add_action('eael/controls/layout', [$this, 'layout'], 10, 1);
        add_action('eael/controls/terms_style', [$this, 'terms_style'], 10, 1);
        add_action('eael/controls/read_more_button_style', [$this, 'read_more_button_style'], 10, 1);
        add_action('eael/controls/load_more_button_style', [$this, 'load_more_button_style'], 10, 1);
        add_action('eael/controls/custom_positioning', [$this, 'custom_positioning'], 10, 5);
	    add_action('eael/controls/nothing_found_style', [$this, 'nothing_found_style'], 10, 1);

        add_filter('eael/controls/event-calendar/source', [$this, 'event_calendar_source']);
        add_action('eael/controls/advanced-data-table/source', [$this, 'advanced_data_table_source']);

        // Login | Register
        add_action('init', [$this, 'login_or_register_user']);
        add_filter('wp_new_user_notification_email', array($this, 'new_user_notification_email'), 10, 3);
        add_filter('wp_new_user_notification_email_admin', array($this, 'new_user_notification_email_admin'), 10, 3);

        //rank math support
        add_filter('rank_math/researches/toc_plugins', [$this, 'toc_rank_math_support']);

        if(defined('WPML_TM_VERSION')){
	        add_filter( 'elementor/documents/get/post_id',[$this, 'eael_wpml_template_translation']);
        }


        //templately plugin support
        if( !class_exists('Templately\Plugin') && !get_option('eael_templately_promo_hide') ) {
            add_action( 'elementor/editor/before_enqueue_scripts', [$this, 'templately_promo_enqueue_scripts'] );
            add_action( 'eael/before_enqueue_styles', [$this, 'templately_promo_enqueue_style'] );
            add_action( 'elementor/editor/footer', [ $this, 'print_template_views' ] );
            add_action( 'wp_ajax_templately_promo_status', array($this, 'templately_promo_status'));
        }

	    if( class_exists( 'woocommerce' ) ) {
		    // quick view
		    add_action( 'eael_woo_single_product_image', 'woocommerce_show_product_images', 20 );
		    add_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_title', 5 );
		    add_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_rating', 10 );
		    add_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_price', 15 );
		    add_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		    add_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_add_to_cart', 25 );
		    add_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_meta', 30 );

		    add_filter( 'woocommerce_product_get_rating_html', [ $this, 'eael_rating_markup' ], 10, 3 );
	    }


        // Admin
        if (is_admin()) {
            // Admin
            if (!$this->pro_enabled) {
                $this->admin_notice();
            } else {
                new WPDeveloper_Core_Installer( basename( EAEL_PLUGIN_BASENAME, '.php' ) );
            }

            add_action('admin_menu', array($this, 'admin_menu'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
            add_action('wp_ajax_save_settings_with_ajax', array($this, 'save_settings'));
            add_action('wp_ajax_clear_cache_files_with_ajax', array($this, 'clear_cache_files'));

            // Core
            add_filter('plugin_action_links_' . EAEL_PLUGIN_BASENAME, array($this, 'insert_plugin_links'));
            add_filter('plugin_row_meta', array($this, 'insert_plugin_row_meta'), 10, 2);

            // removed activation redirection temporarily
            // add_action('admin_init', array($this, 'redirect_on_activation'));

            if (!did_action('elementor/loaded')) {
                add_action('admin_notices', array($this, 'elementor_not_loaded'));
            }

	        //handle typeform auth token
	        add_action('admin_init', [$this, 'typeform_auth_handle']);


            // On Editor - Register WooCommerce frontend hooks before the Editor init.
            // Priority = 5, in order to allow plugins remove/add their wc hooks on init.
            if (!empty($_REQUEST['action']) && 'elementor' === $_REQUEST['action']) {
                add_action('init', [$this, 'register_wc_hooks'], 5);
            }
        }
    }
}

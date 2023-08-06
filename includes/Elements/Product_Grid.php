<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;
use Essential_Addons_Elementor\Template\Content\Product_Grid as Product_Grid_Trait;
use Essential_Addons_Elementor\Traits\Helper;
use Essential_Addons_Elementor\Traits\Woo_Product_Comparable;

class Product_Grid extends Widget_Base
{
    use Woo_Product_Comparable;
    use Helper;
    use Product_Grid_Trait;

    private $is_show_custom_add_to_cart = false;
    private $simple_add_to_cart_button_text;
    private $variable_add_to_cart_button_text;
    private $grouped_add_to_cart_button_text;
    private $external_add_to_cart_button_text;
    private $default_add_to_cart_button_text;
    /**
     * @var int
     */
    protected $page_id;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$is_type_instance = $this->is_type_instance();

		if ( ! $is_type_instance && null === $args ) {
			throw new \Exception( '`$args` argument is required when initializing a full widget instance.' );
		}

		if ( $is_type_instance && class_exists('woocommerce')) {
		    $this->load_quick_view_asset();
		}
	}

    public function get_name()
    {
        return 'eicon-woocommerce';
    }

    public function get_title()
    {
        return esc_html__('Product Grid', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-product-grid';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'woo',
            'woocommerce',
            'ea woocommerce',
            'ea woo product grid',
            'ea woocommerce product grid',
            'product gallery',
            'woocommerce grid',
            'gallery',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/woocommerce-product-grid/';
    }

    public function get_style_depends()
    {
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
        ];
    }

    public function get_script_depends()
    {
        return [
            'font-awesome-4-shim',
        ];
    }

    public function add_to_cart_button_custom_text($default)
    {
        if ($this->is_show_custom_add_to_cart) {
            global $product;
            switch ($product->get_type()) {
                case 'external':
                    return $this->external_add_to_cart_button_text;
                case 'grouped':
                    return $this->grouped_add_to_cart_button_text;
                case 'simple':
                    if ( ! $product->is_in_stock() ) {
                        return $this->default_add_to_cart_button_text;
                    }
                    return $this->simple_add_to_cart_button_text;
                case 'variable':
                    return $this->variable_add_to_cart_button_text;
                default:
                    return $this->default_add_to_cart_button_text;
            }
        }

	    if( 'Read more' === $default ) {
		    return esc_html__( 'View More', 'essential-addons-for-elementor-lite' );
	    }

        return $default;
    }

    protected function eael_get_product_orderby_options()
    {
        return apply_filters('eael/product-grid/orderby-options', [
            'ID' => __('Product ID', 'essential-addons-for-elementor-lite'),
            'title' => __('Product Title', 'essential-addons-for-elementor-lite'),
            '_price' => __('Price', 'essential-addons-for-elementor-lite'),
            '_sku' => __('SKU', 'essential-addons-for-elementor-lite'),
            'date' => __('Date', 'essential-addons-for-elementor-lite'),
            'modified' => __('Last Modified Date', 'essential-addons-for-elementor-lite'),
            'parent' => __('Parent Id', 'essential-addons-for-elementor-lite'),
            'rand' => __('Random', 'essential-addons-for-elementor-lite'),
            'menu_order' => __('Menu Order', 'essential-addons-for-elementor-lite'),
        ]);
    }

    protected function eael_get_product_filterby_options()
    {
        return apply_filters('eael/product-grid/filterby-options', [
            'recent-products' => esc_html__('Recent Products', 'essential-addons-for-elementor-lite'),
            'featured-products' => esc_html__('Featured Products', 'essential-addons-for-elementor-lite'),
            'best-selling-products' => esc_html__('Best Selling Products', 'essential-addons-for-elementor-lite'),
            'sale-products' => esc_html__('Sale Products', 'essential-addons-for-elementor-lite'),
            'top-products' => esc_html__('Top Rated Products', 'essential-addons-for-elementor-lite'),
            'manual' => esc_html__('Manual Selection', 'essential-addons-for-elementor-lite'),
        ]);
    }

    protected function register_controls()
    {
        $this->init_content_wc_notice_controls();
        if (!function_exists('WC')) {
            return;
        }
        // Content Controls
        $this->init_content_layout_controls();
        $this->init_content_product_settings_controls();
        $this->eael_product_badges();
        $this->init_content_addtocart_controls();
        $this->init_content_load_more_controls();
        $this->eael_product_pagination();
        // Product Compare
        $this->init_content_product_compare_controls();
        $this->init_content_table_settings_controls();

        // Style Controls---------------
        $this->init_style_product_controls();
        $this->init_style_color_typography_controls();
        $this->init_style_addtocart_controls();
        $this->sale_badge_style();
        $this->eael_product_action_buttons();
        $this->eael_product_action_buttons_style();
        /**
         * Load More Button Style Controls!
         */
        do_action('eael/controls/load_more_button_style', $this);
        /**
         * Pagination Style Controls!
         */
        $this->eael_product_pagination_style();

        /**
         * Pagination Style Controls!
         */
        $this->eael_product_view_popup_style();
        // Product Compare Table Style
        $container_class = '.eael-wcpc-modal';
        $table = ".eael-wcpc-modal .eael-wcpc-wrapper table";
        $table_title = ".eael-wcpc-modal .eael-wcpc-wrapper .wcpc-title";
        $table_title_wrap = ".eael-wcpc-modal .eael-wcpc-wrapper .first-th";
        $compare_btn_condition = [
            'eael_product_grid_style_preset!' => [
                'eael-product-preset-5',
                'eael-product-preset-6',
                'eael-product-preset-7',
                'eael-product-preset-8',
            ],
            'eael_product_grid_layout!' => 'list',
        ];
        $this->init_style_compare_button_controls($compare_btn_condition);
        $this->init_style_content_controls(compact('container_class'));
        $this->init_style_table_controls(compact('table', 'table_title', 'table_title_wrap'));
        $this->init_style_close_button_controls();
    }

    protected function init_content_layout_controls()
    {
        $this->start_controls_section(
            'eael_section_product_grid_layouts',
            [
                'label' => esc_html__('Layouts', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_product_grid_layout',
            [
                'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'masonry',
                'options' => [
                    'grid' => esc_html__('Grid', 'essential-addons-for-elementor-lite'),
                    'list' => esc_html__('List', 'essential-addons-for-elementor-lite'),
                    'masonry' => esc_html__('Masonry', 'essential-addons-for-elementor-lite'),
                ]
            ]
        );

        $this->add_control(
            'eael_product_grid_style_preset',
            [
                'label' => esc_html__('Style Preset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'eael-product-simple',
                'options' => [
                    'eael-product-default' => esc_html__('Default', 'essential-addons-for-elementor-lite'),
                    'eael-product-simple' => esc_html__('Simple Style', 'essential-addons-for-elementor-lite'),
                    'eael-product-reveal' => esc_html__('Reveal Style', 'essential-addons-for-elementor-lite'),
                    'eael-product-overlay' => esc_html__('Overlay Style', 'essential-addons-for-elementor-lite'),
                    'eael-product-preset-5' => esc_html__('Preset 5', 'essential-addons-for-elementor-lite'),
                    'eael-product-preset-6' => esc_html__('Preset 6', 'essential-addons-for-elementor-lite'),
                    'eael-product-preset-7' => esc_html__('Preset 7', 'essential-addons-for-elementor-lite'),
                    'eael-product-preset-8' => esc_html__('Preset 8', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_product_grid_layout' => ['grid', 'masonry'],
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_style_preset',
            [
                'label' => esc_html__('Style Preset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'eael-product-list-preset-1',
                'options' => [
                    'eael-product-list-preset-1' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
                    'eael-product-list-preset-2' => esc_html__('Preset 2', 'essential-addons-for-elementor-lite'),
                    'eael-product-list-preset-3' => esc_html__('Preset 3', 'essential-addons-for-elementor-lite'),
                    'eael-product-list-preset-4' => esc_html__('Preset 4', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_product_grid_layout' => ['list'],
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_grid_column',
            [
                'label' => esc_html__('Columns', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '1' => esc_html__('1', 'essential-addons-for-elementor-lite'),
                    '2' => esc_html__('2', 'essential-addons-for-elementor-lite'),
                    '3' => esc_html__('3', 'essential-addons-for-elementor-lite'),
                    '4' => esc_html__('4', 'essential-addons-for-elementor-lite'),
                    '5' => esc_html__('5', 'essential-addons-for-elementor-lite'),
                    '6' => esc_html__('6', 'essential-addons-for-elementor-lite'),
                ],
                'toggle' => true,
                'prefix_class' => 'eael-product-grid-column%s-',
                'condition' => [
                    'eael_product_grid_layout!' => 'list',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_list_column',
            [
                'label' => esc_html__('Columns', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => '2',
                'options' => [
                    '1' => esc_html__('1', 'essential-addons-for-elementor-lite'),
                    '2' => esc_html__('2', 'essential-addons-for-elementor-lite'),
                ],
                'toggle' => true,
                'prefix_class' => 'eael-product-list-column%s-',
                'condition' => [
                    'eael_product_grid_layout' => 'list',
                ],
            ]
        );

        $this->add_control(
            'eael_show_product_sale_badge',
            [
                'label' => esc_html__( 'Show Badge ?', 'essential-addons-for-elementor-lite' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
                'label_off' => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function init_content_product_settings_controls()
    {
        $this->start_controls_section('eael_section_product_grid_settings', [
            'label' => esc_html__('Product Settings', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'post_type',
            [
                'label'   => __( 'Source', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'product',
                'options' => [
                    'product'        => esc_html__( 'Products', 'essential-addons-for-elementor-lite' ),
                    'source_dynamic' => esc_html__( 'Dynamic', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'eael_global_dynamic_source_warning_text',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __( 'This option will only affect in <strong>Archive page of Elementor Theme Builder</strong> dynamically.', 'essential-addons-for-elementor-lite' ),
                'content_classes' => 'eael-warning',
                'condition'       => [
                    'post_type' => 'source_dynamic',
                ],
            ]
        );

        if ( !apply_filters( 'eael/is_plugin_active', 'woocommerce/woocommerce.php' ) ) {
            $this->add_control(
                'ea_product_grid_woo_required',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.', 'essential-addons-for-elementor-lite' ),
                    'content_classes' => 'eael-warning',
                ]
            );
        }

        $this->add_control('eael_product_grid_product_filter', [
            'label' => esc_html__('Filter By', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'recent-products',
            'options' => $this->eael_get_product_filterby_options(),
            'condition' => [
              'post_type!' => 'source_dynamic',
            ],
        ]);

        $this->add_control('orderby', [
            'label' => __('Order By', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SELECT,
            'options' => $this->eael_get_product_orderby_options(),
            'default' => 'date',

        ]);

        $this->add_control('order', [
            'label' => __('Order', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'asc' => 'Ascending',
                'desc' => 'Descending',
            ],
            'default' => 'desc',

        ]);

        $this->add_control('eael_product_grid_products_count', [
            'label' => __('Products Count', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => 4,
            'min' => 1,
            'max' => 1000,
            'step' => 1,
        ]);

        $this->add_control('product_offset', [
            'label' => __('Offset', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                'eael_product_grid_product_filter!' => 'manual'
            ]
        ]);

        $this->add_control(
            'eael_product_grid_products_status',
            [
                'label' => __( 'Product Status', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'default' => [ 'publish', 'pending', 'future' ],
                'options' => $this->eael_get_product_statuses(),
                'condition' => [
                    'eael_product_grid_product_filter!' => 'manual'
                ]
            ]
        );

        $this->add_control('eael_product_grid_categories', [
            'label' => esc_html__('Product Categories', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'multiple' => true,
            'options' => HelperClass::get_terms_list('product_cat', 'slug'),
            'condition'   => [
              'post_type!' => 'source_dynamic',
              'eael_product_grid_product_filter!' => 'manual'
            ],
        ]);

        $this->add_control('eael_product_grid_products_in', [
            'label' => esc_html__('Select Products', 'essential-addons-for-elementor-lite'),
            'type'        => 'eael-select2',
            'label_block' => true,
            'multiple' => true,
            'source_name' => 'post_type',
            'source_type' => 'product',
            'condition'   => [
                'post_type!' => 'source_dynamic',
                'eael_product_grid_product_filter' => 'manual'
            ],
        ]);

        $this->add_control(
            'eael_dynamic_template_Layout',
            [
                'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => $this->get_template_list_for_dropdown(),
            ]
        );

        $this->add_control(
            'eael_product_grid_title_html_tag',
            [
                'label'   => __( 'Title HTML Tag', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1'   => __( 'H1', 'essential-addons-for-elementor-lite' ),
                    'h2'   => __( 'H2', 'essential-addons-for-elementor-lite' ),
                    'h3'   => __( 'H3', 'essential-addons-for-elementor-lite' ),
                    'h4'   => __( 'H4', 'essential-addons-for-elementor-lite' ),
                    'h5'   => __( 'H5', 'essential-addons-for-elementor-lite' ),
                    'h6'   => __( 'H6', 'essential-addons-for-elementor-lite' ),
                    'div'  => __( 'div', 'essential-addons-for-elementor-lite' ),
                    'span' => __( 'span', 'essential-addons-for-elementor-lite' ),
                    'p'    => __( 'p', 'essential-addons-for-elementor-lite' ),
                ],
                'condition'=> [
                    'eael_product_grid_style_preset!' => 'eael-product-default'
                ]
            ]
        );

        $this->add_control('eael_product_grid_rating', [
            'label' => esc_html__('Show Product Rating?', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'condition' => [
                'eael_product_grid_style_preset!' => ['eael-product-preset-8'],
            ],

        ]);

        $this->add_control(
            'eael_product_grid_price',
            [
                'label' => esc_html__('Show Product Price?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'eael_product_grid_style_preset!' => 'eael-product-default',
                ],
            ]
        );
        $this->add_control(
            'eael_product_grid_excerpt',
            [
                'label' => esc_html__('Short Description?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'eael_product_grid_layout' => 'list',
                ],
            ]
        );
        $this->add_control(
            'eael_product_grid_excerpt_length',
            [
                'label' => __('Excerpt Words', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '10',
                'condition' => [
                    'eael_product_grid_excerpt' => 'yes',
                    'eael_product_grid_layout' => 'list',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_excerpt_expanison_indicator',
            [
                'label' => esc_html__('Expansion Indicator', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => '...',
                'condition' => [
                    'eael_product_grid_excerpt' => 'yes',
                    'eael_product_grid_layout' => 'list',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'eael_product_grid_image_size',
                'exclude' => ['custom'],
                'default' => 'medium',
                'label_block' => true,
            ]
        );

        $this->add_control('show_compare', [
            'label' => esc_html__('Show Product Compare?', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
        ]);

	    $this->add_control(
		    'eael_product_grid_image_clickable',
		    [
			    'label' => esc_html__('Image Clickable?', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SWITCHER,
			    'return_value' => 'yes',
			    'default' => 'no',
		    ]
	    );

	    $this->add_control(
		    'eael_product_grid_wishlist',
		    [
			    'label' => esc_html__('Show Wishlist?', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SWITCHER,
			    'return_value' => 'yes',
			    'default' => 'no',
		    ]
	    );

	    if ( ! function_exists( 'YITH_WCWL' ) ) {
		    $this->add_control( 'eael_wishlist_warning_text',
			    [
				    'type'            => Controls_Manager::RAW_HTML,
				    'raw'             => __( '<strong>YITH WOOCOMMERCE WISHLIST</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=yith-woocommerce-wishlist&tab=search&type=term" target="_blank">YITH WOOCOMMERCE WISHLIST</a> first.', 'essential-addons-for-elementor-lite' ),
				    'content_classes' => 'eael-warning',
				    'condition'       => [
					    'eael_product_grid_wishlist' => 'yes'
				    ],
			    ]
		    );
	    }

        $this->end_controls_section();
    }

    protected function init_content_addtocart_controls()
    {
        $this->start_controls_section(
            'eael_product_grid_add_to_cart_section',
            [
                'label' => esc_html__('Add To Cart', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'show_add_to_cart_custom_text',
            [
                'label' => __('Show Add to cart custom text', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $this->add_control(
            'add_to_cart_simple_product_button_text',
            [
                'label' => esc_html__('Simple Product', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default' => esc_html__('Buy Now', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'show_add_to_cart_custom_text' => 'true',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        $this->add_control(
            'add_to_cart_variable_product_button_text',
            [
                'label' => esc_html__('Variable Product', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default' => esc_html__('Select options', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'show_add_to_cart_custom_text' => 'true',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        $this->add_control(
            'add_to_cart_grouped_product_button_text',
            [
                'label' => esc_html__('Grouped Product', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default' => esc_html__('View products', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'show_add_to_cart_custom_text' => 'true',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        $this->add_control(
            'add_to_cart_external_product_button_text',
            [
                'label' => esc_html__('External Product', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default' => esc_html__('Buy Now', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'show_add_to_cart_custom_text' => 'true',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        $this->add_control(
            'add_to_cart_default_product_button_text',
            [
                'label' => esc_html__('Default Product', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default' => esc_html__('Read More', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'show_add_to_cart_custom_text' => 'true',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->end_controls_section(); # end of section 'add to cart'
    }

    protected function init_content_load_more_controls()
    {
        $this->start_controls_section('eael_product_grid_load_more_section', [
            'label' => esc_html__('Load More', 'essential-addons-for-elementor-lite'),
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'name' => 'eael_product_grid_layout',
                        'operator' => 'in',
                        'value' => [
                            'masonry',
                        ],
                    ],
                    [
                        'name' => 'show_pagination',
                        'operator' => '!=',
                        'value' => 'true'
                    ],
                ],
            ],
        ]);

        $this->add_control('show_load_more', [
            'label' => __('Show Load More', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
            'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
            'return_value' => 'true',
            'default' => '',
        ]);

        $this->add_control('show_load_more_text', [
            'label' => esc_html__('Label Text', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::TEXT,
            'label_block' => false,
            'default' => esc_html__('Load More', 'essential-addons-for-elementor-lite'),
            'condition' => [
                'show_load_more' => ['yes', '1', 'true'],
            ],
            'ai' => [
                'active' => false,
            ],
        ]);

        $this->end_controls_section(); # end of section 'Load More'
    }

    protected function init_style_product_controls()
    {
        $this->start_controls_section(
            'eael_product_grid_styles',
            [
                'label' => esc_html__('Products', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eael_product_grid_content_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid:not(.list) .woocommerce ul.products li.product' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid:not(.list) .woocommerce ul.products li.product .star-rating' => 'margin-{{VALUE}}: 0;',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => '!=',
                            'value' => [
                                'list',
                            ],
                        ],
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => 'in',
                            'value' => [
                                'eael-product-default',
                                'eael-product-simple',
                                'eael-product-reveal',
                                'eael-product-overlay',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_background_color',
            [
                'label' => esc_html__('Content Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product, {{WRAPPER}} .eael-product-grid .icons-wrap.block-box-style' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product.eael-product-list-preset-4 .product-details-wrap' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product.eael-product-list-preset-3, {{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product.eael-product-list-preset-4'
                    => 'background-color: transparent;',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => 'in',
                            'value' => [
                                'grid',
                                'list',
                                'masonry',
                            ],
                        ],
                        [
                            'name' => 'eael_product_list_style_preset',
                            'operator' => '!=',
                            'value' => [
                                'eael-product-list-preset-3',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ada8a8',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .price-wrap, {{WRAPPER}} .eael-product-grid .title-wrap' => 'border-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => '!in',
                            'value' => [
                                'grid',
                                'masonry',
                            ],
                        ],
                        [
                            'name' => 'eael_product_list_style_preset',
                            'operator' => '==',
                            'value' => 'eael-product-list-preset-3',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_peoduct_grid_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => '!=',
                            'value' => [
                                'list',
                            ],
                        ],
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => 'in',
                            'value' => [
                                'eael-product-default',
                                'eael-product-simple',
                                'eael-product-reveal',
                                'eael-product-overlay',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->start_controls_tabs('eael_product_grid_tabs', [
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'name' => 'eael_product_grid_layout',
                        'operator' => 'in',
                        'value' => [
                            'grid',
                            'mesonry',
                        ]
                    ],
                    [
                        'name' => 'eael_product_list_style_preset',
                        'operator' => '!in',
                        'value' => [
                            'eael-product-list-preset-3',
                            'eael-product-list-preset-4',
                        ]
                    ]
                ]
            ],
        ]);

        $this->start_controls_tab('eael_product_grid_tabs_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_peoduct_grid_border',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'default' => '#eee',
                    ],
                ],
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product',
                'condition' => [
                    'eael_product_grid_style_preset' => [
                        'eael-product-default',
                        'eael-product-simple',
                        'eael-product-overlay',
                        'eael-product-preset-5',
                        'eael-product-preset-6',
                        'eael-product-preset-7',
                        'eael-product-preset-8',
                    ]
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_peoduct_grid_shadow',
                'label' => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product',
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab('eael_product_grid_hover_styles', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_grid_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_peoduct_grid_border_border!' => '',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_product_grid_box_shadow_hover',
                'label' => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'eael_peoduct_grid_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product woocommerce-loop-product__link img' => 'border-radius: {{TOP}}px {{RIGHT}}px 0 0;',
                    '{{WRAPPER}} .eael-product-grid.list .woocommerce ul.products li.product .woocommerce-loop-product__link img' => 'border-radius: {{TOP}}px 0 0 {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_grid_image_width',
            [
                'label' => esc_html__('Image Width(%)', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid.list .eael-product-wrap .product-image-wrap' => 'width: {{SIZE}}%;',
                ],
                'condition' => [
                    'eael_product_grid_layout' => 'list',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_grid_details_heading',
            [
                'label' => __('Product Details', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => 'in',
                            'value' => [
                                'grid',
                                'list',
                                'masonry',
                            ],
                        ],
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => '!in',
                            'value' => [
                                'eael-product-default',
                                'eael-product-simple',
                                'eael-product-reveal',
                                'eael-product-overlay',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_grid_details_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .product-details-wrap' => 'text-align: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => '!=',
                            'value' => [
                                'list',
                            ],
                        ],
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => '!in',
                            'value' => [
                                'eael-product-default',
                                'eael-product-simple',
                                'eael-product-reveal',
                                'eael-product-overlay',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_grid_inner_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid.grid .eael-product-wrap .product-details-wrap, {{WRAPPER}} .eael-product-grid.masonry .eael-product-wrap .product-details-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => '!=',
                            'value' => [
                                'list',
                            ],
                        ],
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => '!in',
                            'value' => [
                                'eael-product-default',
                                'eael-product-simple',
                                'eael-product-reveal',
                                'eael-product-overlay',
                            ]
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_list_padding',
            [
                'label' => esc_html__('Padding (PX)', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid.list .eael-product-list-preset-1 .eael-product-wrap .product-details-wrap, {{WRAPPER}} .eael-product-grid.list .eael-product-list-preset-4 .eael-product-wrap .product-details-wrap' => 'padding: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-grid.list .eael-product-list-preset-2 .eael-product-wrap' => 'padding: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-grid.list .eael-product-list-preset-2 .eael-product-wrap .product-details-wrap' => 'padding: 0 0 0 {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-grid.list .eael-product-list-preset-3 .eael-product-wrap .product-details-wrap' => 'padding: 0 0 0 {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_product_grid_layout' => 'list',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_list_content_width',
            [
                'label' => esc_html__('Width (%)', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid.list .eael-product-wrap .product-details-wrap' => 'width: {{SIZE}}%;',
                ],
                'condition' => [
                    'eael_product_grid_layout' => 'list',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function init_style_color_typography_controls()
    {

        $this->start_controls_section(
            'eael_section_product_grid_typography',
            [
                'label' => esc_html__('Color &amp; Typography', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_product_grid_product_title_heading',
            [
                'label' => __('Product Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_grid_product_title_color',
            [
                'label' => esc_html__('Product Title Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#272727',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .woocommerce-loop-product__title, {{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .eael-product-title h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_grid_product_title_typography',
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .woocommerce-loop-product__title, {{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .eael-product-title h2',
            ]
        );

        $this->add_control(
            'eael_product_grid_product_price_heading',
            [
                'label' => __('Product Price', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_grid_product_price_color',
            [
                'label' => esc_html__('Price Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#272727',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .price, {{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .eael-product-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_product_sale_price_color',
            [
                'label' => esc_html__('Sale Price Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .price ins, {{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .eael-product-price ins' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_grid_product_price_typography',
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .price,{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .eael-product-price',
            ]
        );

        $this->add_control(
            'eael_product_grid_product_rating_heading',
            [
                'label' => __('Star Rating', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_grid_product_rating_color',
            [
                'label' => esc_html__('Rating Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2b01e',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce .star-rating::before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid .woocommerce .star-rating span::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_grid_product_rating_typography',
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .star-rating',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => '!in',
                            'value' => [
                                'eael-product-preset-5',
                                'eael-product-preset-6',
                                'eael-product-preset-7',
                                'eael-product-preset-8',
                            ],
                        ],
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => '!==',
                            'value' => 'list'
                        ]
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_grid_product_rating_size',
            [
                'label' => esc_html__('Icon Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .star-rating' => 'font-size: {{SIZE}}px!important;',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => 'in',
                            'value' => [
                                'eael-product-preset-5',
                                'eael-product-preset-6',
                                'eael-product-preset-7',
                            ],
                        ],
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => '==',
                            'value' => 'list'
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_product_desc_heading',
            [
                'label' => __('Product Description', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_product_grid_layout' => 'list',
                    'eael_product_grid_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_product_desc_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#272727',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .eael-product-excerpt' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_grid_layout' => 'list',
                    'eael_product_grid_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_grid_product_desc_typography',
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .eael-product-excerpt',
                'condition' => [
                    'eael_product_grid_layout' => 'list',
                    'eael_product_grid_excerpt' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function sale_badge_style(){
	    $this->start_controls_section(
		    'eael_section_product_grid_sale_badge_style',
		    [
			    'label' => esc_html__('Sale Badge Style', 'essential-addons-for-elementor-lite'),
			    'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_show_product_sale_badge' => 'yes'
                ]
		    ]
	    );

	    $this->add_control(
		    'eael_product_grid_sale_badge_color',
		    [
			    'label' => esc_html__('Sale Badge Color', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::COLOR,
			    'default' => '#fff',
			    'selectors' => [
				    '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_grid_sale_badge_background',
		    [
			    'label' => esc_html__('Sale Badge Background', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::COLOR,
			    'default' => '#ff2a13',
			    'selectors' => [
				    '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale' => 'background-color: {{VALUE}};',
				    '{{WRAPPER}} .woocommerce ul.products li.product .eael-onsale:not(.outofstock).sale-preset-4:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'eael_product_grid_sale_badge_typography',
			    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale:not(.outofstock)',
		    ]
	    );

	    // stock out badge
	    $this->add_control(
		    'eael_product_grid_stock_out_badge_heading',
		    [
			    'label' => __('Stock Out Badge', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::HEADING,
		    ]
	    );

	    $this->add_control(
		    'eael_product_grid_stock_out_badge_color',
		    [
			    'label' => esc_html__('Stock Out Badge Color', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::COLOR,
			    'default' => '#fff',
			    'selectors' => [
				    '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_grid_stock_out_badge_background',
		    [
			    'label' => esc_html__('Stock Out Badge Background', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::COLOR,
			    'default' => '#ff2a13',
			    'selectors' => [
				    '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock' => 'background-color: {{VALUE}};',
				    '{{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock.sale-preset-4:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'eael_product_grid_stock_out_badge_typography',
			    'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock',
		    ]
	    );

	    $this->end_controls_section();
    }

    protected function init_style_addtocart_controls()
    {
        // add to cart button
        $this->start_controls_section(
            'eael_section_product_grid_add_to_cart_styles',
            [
                'label' => esc_html__('Button Styles', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_product_grid_style_preset!' => [
                        'eael-product-preset-5',
                        'eael-product-preset-6',
                        'eael-product-preset-7',
                        'eael-product-preset-8',
                    ],
                    'eael_product_grid_layout!' => 'list',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_add_to_cart_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button,
                    {{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button,
                    {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link,
                    {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist,
                    {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_add_to_cart_radius',
            [
                'label' => __('Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
	                '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button,
                    {{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button,
                    {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link,
                    {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist,
                    {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'eael_product_grid_add_to_cart_is_gradient_bg',
            [
                'label' => __('Use Gradient Background', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_controls_tabs('eael_product_grid_add_to_cart_style_tabs');

        $this->start_controls_tab('normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_grid_add_to_cart_color',
            [
                'label' => esc_html__('Button Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button, 
                    {{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_product_grid_add_to_cart_gradient_background',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button,
                {{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button,
                {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link,
                {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist,
                {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart',
                'condition' => [
                    'eael_product_grid_add_to_cart_is_gradient_bg' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'eael_product_grid_add_to_cart_background',
            [
                'label' => esc_html__('Background', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button, 
                    {{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_grid_add_to_cart_is_gradient_bg' => ''
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_product_grid_add_to_cart_border',
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button, 
                {{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button, 
                {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link, 
                {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist, 
                {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_grid_add_to_cart_typography',
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button,
                {{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button',
                'condition' => [
                    'eael_product_grid_style_preset' => ['eael-product-default', 'eael-product-simple'],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_product_grid_add_to_cart_hover_styles', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_grid_add_to_cart_hover_color',
            [
                'label' => esc_html__('Button Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button:hover,
                    {{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_product_grid_add_to_cart_hover_gradient_background',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button:hover,
                {{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button:hover,
                {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover,
                {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist:hover,
                {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover',
                'condition' => [
                    'eael_product_grid_add_to_cart_is_gradient_bg' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'eael_product_grid_add_to_cart_hover_background',
            [
                'label' => esc_html__('Background', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button:hover,
                    {{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_grid_add_to_cart_is_gradient_bg' => '',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_add_to_cart_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button:hover,
                    {{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .add_to_wishlist:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function eael_product_badges()
    {
        $this->start_controls_section(
            'eael_section_product_badges',
            [
                'label' => esc_html__('Sale / Stock Out Badge', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_show_product_sale_badge' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'eael_product_sale_badge_preset',
            [
                'label' => esc_html__('Style Preset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'sale-preset-1',
                'options' => [
                    'sale-preset-1' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
                    'sale-preset-2' => esc_html__('Preset 2', 'essential-addons-for-elementor-lite'),
                    'sale-preset-3' => esc_html__('Preset 3', 'essential-addons-for-elementor-lite'),
                    'sale-preset-4' => esc_html__('Preset 4', 'essential-addons-for-elementor-lite'),
                    'sale-preset-5' => esc_html__('Preset 5', 'essential-addons-for-elementor-lite'),

                ]
            ]
        );

        $this->add_control(
            'eael_product_sale_badge_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'condition' => [
                    'eael_product_grid_layout!' => 'list',
                ],
            ]
        );

	    $this->add_control(
		    'eael_product_sale_text',
		    [
			    'label'       => esc_html__( 'Sale Text', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::TEXT,
			    'separator' => 'before',
                'ai' => [
					'active' => false,
				],
		    ]
	    );

	    $this->add_control(
		    'eael_product_stockout_text',
		    [
			    'label'       => esc_html__( 'Stock Out Text', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::TEXT,
                'ai' => [
					'active' => false,
				],
		    ]
	    );

        $this->end_controls_section();
    }

    protected function eael_product_action_buttons()
    {
        $this->start_controls_section(
            'eael_section_product_action_buttons',
            [
                'label' => esc_html__('Buttons', 'essential-addons-for-elementor-lite'),
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => 'in',
                            'value' => [
                                'eael-product-preset-5',
                                'eael-product-preset-6',
                                'eael-product-preset-7',
                                'eael-product-preset-8',
                            ],
                        ],
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => '==',
                            'value' => 'list'
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_quick_view',
            [
                'label' => esc_html__('Show Quick view?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
    
        $this->add_control(
            'eael_product_quick_view_title_tag',
            [
                'label' => __('Quick view Title Tag', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h1',
                'separator' => 'after',
                'options' => [
                    'h1' => __('H1', 'essential-addons-for-elementor-lite'),
                    'h2' => __('H2', 'essential-addons-for-elementor-lite'),
                    'h3' => __('H3', 'essential-addons-for-elementor-lite'),
                    'h4' => __('H4', 'essential-addons-for-elementor-lite'),
                    'h5' => __('H5', 'essential-addons-for-elementor-lite'),
                    'h6' => __('H6', 'essential-addons-for-elementor-lite'),
                    'span' => __('Span', 'essential-addons-for-elementor-lite'),
                    'p' => __('P', 'essential-addons-for-elementor-lite'),
                    'div' => __('Div', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_product_grid_quick_view' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_action_buttons_preset',
            [
                'label' => esc_html__('Style Preset', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'details-block-style',
                'options' => [
                    'details-block-style' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
                    'details-block-style-2' => esc_html__('Preset 2', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_product_grid_layout' => 'list',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function eael_product_action_buttons_style()
    {
        $this->start_controls_section(
            'eael_section_product_grid_buttons_styles',
            [
                'label' => esc_html__('Button', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => 'in',
                            'value' => [
                                'eael-product-preset-5',
                                'eael-product-preset-6',
                                'eael-product-preset-7',
                                'eael-product-preset-8',
                            ],
                        ],
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => '==',
                            'value' => 'list'
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_buttons_preset5_background',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8040FF',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .eael-product-wrap .icons-wrap.block-style' => 'background: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => 'in',
                            'value' => [
                                'grid',
                                'masonry',
                            ],
                        ],
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => '==',
                            'value' => 'eael-product-preset-5',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_buttons_icon_size',
            [
                'label' => esc_html__('Icons Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid.list .eael-product-wrap .icons-wrap li a i' => 'font-size: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_product_grid_layout' => 'list',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_grid_buttons_typography',
                'selector' => '{{WRAPPER}} .eael-product-grid .icons-wrap li.add-to-cart a',
            ]
        );

        $this->add_control(
            'eael_product_grid_buttons_preset5_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .icons-wrap.block-style li' => 'border-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => 'in',
                            'value' => [
                                'grid',
                                'masonry',
                            ],
                        ],
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => '==',
                            'value' => 'eael-product-preset-5',
                        ],
                    ],
                ],
            ]
        );

        $this->start_controls_tabs('eael_product_grid_buttons_style_tabs');

        $this->start_controls_tab('eael_product_grid_buttons_style_tabs_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_grid_buttons_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .eael-product-wrap .icons-wrap li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-compare-icon' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_buttons_background',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8040FF',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .eael-product-wrap .icons-wrap li a' => 'background-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => '!==',
                            'value' => 'eael-product-preset-5'
                        ],
                        [
                            'name' => 'eael_product_grid_layout',
                            'operator' => '==',
                            'value' => 'list'
                        ]
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_product_grid_buttons_border',
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button,
                {{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button, 
                {{WRAPPER}} .eael-product-grid .eael-product-wrap .icons-wrap li a',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => '!==',
                            'value' => 'eael-product-preset-5'
                        ],
                        [
                            'name' => 'eael_product_action_buttons_preset',
                            'operator' => '==',
                            'value' => 'details-block-style-2'
                        ]
                    ],
                ],
            ]
        );
        $this->add_control(
            'eael_product_grid_buttons_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .eael-product-wrap .icons-wrap:not(.details-block-style-2) li a' => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-grid .eael-product-wrap .icons-wrap.details-block-style-2 li:only-child a' => 'border-radius: {{SIZE}}px!important;',
                    '{{WRAPPER}} .eael-product-grid .eael-product-wrap .icons-wrap.details-block-style-2 li:first-child a' => 'border-radius: {{SIZE}}px 0 0 {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-grid .eael-product-wrap .icons-wrap.details-block-style-2 li:last-child a' => 'border-radius: 0 {{SIZE}}px {{SIZE}}px 0;',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_buttons_top_spacing',
            [
                'label' => esc_html__('Top Spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .eael-product-wrap .icons-wrap' => 'margin-top: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_product_grid_layout' => 'list',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_product_grid_buttons_hover_styles', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_grid_buttons_hover_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F5EAFF',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .eael-product-wrap .icons-wrap li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_buttons_hover_background',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .eael-product-wrap .icons-wrap li a:hover' => 'background-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'eael_product_grid_style_preset',
                            'operator' => '!==',
                            'value' => 'eael-product-preset-5'
                        ],
                        [
                            'name' => 'eael_product_action_buttons_preset',
                            'operator' => '!==',
                            'value' => 'details-block-style-2'
                        ]
                    ]
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_buttons_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .eael-product-wrap .icons-wrap li a:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_grid_buttons_border_border!' => '',
                    'eael_product_grid_style_preset!' => 'eael-product-preset-5',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function eael_product_pagination()
    {

        $this->start_controls_section(
            'eael_product_grid_pagination_section',
            [
                'label' => __('Pagination', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'eael_product_grid_layout' => ['grid', 'list'],
                    'show_load_more!' => 'true'
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => __('Show pagination', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $this->add_control(
            'pagination_prev_label',
            [
                'label' => __('Previous Label', 'essential-addons-for-elementor-lite'),
                'default' => __('←', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'show_pagination' => 'true',
                ]
            ]
        );

        $this->add_control(
            'pagination_next_label',
            [
                'label' => __('Next Label', 'essential-addons-for-elementor-lite'),
                'default' => __('→', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'show_pagination' => 'true',
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function eael_product_pagination_style()
    {
        $this->start_controls_section(
            'eael_section_product_pagination_style',
            [
                'label' => __('Pagination', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_pagination' => 'true',
                    'eael_product_grid_layout' => ['grid', 'list'],
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_grid_pagination_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-pagination' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_grid_pagination_top_spacing',
            [
                'label' => esc_html__('Top Spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-pagination' => 'margin-top: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_grid_pagination_typography',
                'selector' => '{{WRAPPER}} .eael-woo-pagination',
            ]
        );

        $this->start_controls_tabs('eael_product_grid_pagination_tabs');

        // Normal State Tab
        $this->start_controls_tab('eael_product_grid_pagination_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_grid_pagination_normal_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#2F436C',
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-pagination a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_pagination_normal_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-pagination a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_product_grid_pagination_normal_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-woo-pagination a, {{WRAPPER}} .eael-woo-pagination span',
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab('eael_product_grid_pagination_hover', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_grid_pagination_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-pagination a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_pagination_hover_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8040FF',
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-pagination a:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_pagination_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-pagination a:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_grid_pagination_normal_border_border!' => '',
                ]
            ]

        );
        $this->end_controls_tab();

        // Active State Tab
        $this->start_controls_tab('eael_product_grid_pagination_active', ['label' => esc_html__('Active', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_grid_pagination_hover_text_active',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-pagination .current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_pagination_active_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8040FF',
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-pagination .current' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_pagination_active_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-pagination .current' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_grid_pagination_normal_border_border!' => '',
                ]
            ]

        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'eael_product_grid_pagination_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-pagination li > *' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        // Pagination Loader
        $this->add_control(
            'eael_product_pagination_loader',
            [
                'label' => __('Loader', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_pagination_loader_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}}.eael-product-loader::after' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function eael_product_view_popup_style()
    {
        $this->start_controls_section(
            'eael_product_popup',
            [
                'label' => __('Popup', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_product_popup_title',
            [
                'label' => __('Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_popup_title_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '.eael-popup-details-render{{WRAPPER}} div.product .product_title',
            ]
        );

        $this->add_control(
            'eael_product_popup_title_color',
            [
                'label' => __('Title Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#252525',
                'selectors' => [
//                    '{{WRAPPER}} .eael-product-popup .eael-product-quick-view-title.product_title.entry-title' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} div.product .product_title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_price',
            [
                'label' => __('Price', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_popup_price_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '.eael-popup-details-render{{WRAPPER}} div.product .price',
            ]
        );

        $this->add_control(
            'eael_product_popup_price_color',
            [
                'label' => __('Price Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0242e4',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} div.product .price' => 'color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_sale_price_color',
            [
                'label' => __('Sale Price Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff2a13',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} div.product .price ins' => 'color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_content',
            [
                'label' => __('Content', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_popup_content_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '.eael-popup-details-render{{WRAPPER}} div.product .woocommerce-product-details__short-description',
            ]
        );

        $this->add_control(
            'eael_product_popup_content_color',
            [
                'label' => __('Content Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#707070',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_review_link_color',
            [
                'label' => __('Review Link Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ccc',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} .product_meta a.woocommerce-review-link, .eael-popup-details-render{{WRAPPER}} .product_meta a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_product_popup_review_link_hover',
            [
                'label' => __('Review Link Hover', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ccc',
                'selectors' => [
                    '.eael-popup-details-render{{WRAPPER}} a.woocommerce-review-link:hover, .eael-popup-details-render{{WRAPPER}} .product_meta a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_table_border_color',
            [
                'label' => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ccc',
                'selectors' => [
	                '.eael-popup-details-render{{WRAPPER}} div.product table tbody tr, {{WRAPPER}} .eael-product-popup.woocommerce div.product .product_meta' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        // Sale
        $this->add_control(
            'eael_product_popup_sale_style',
            [
                'label' => __('Sale', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name'     => 'eael_product_popup_sale_typo',
			    'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
			    'selector' => '.eael-popup-details-render{{WRAPPER}} .eael-onsale',
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_sale_color',
		    [
			    'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .eael-onsale' => 'color: {{VALUE}}!important;',
			    ],
		    ]
	    );
	    $this->add_control(
		    'eael_product_popup_sale_bg_color',
		    [
			    'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .eael-onsale' => 'background-color: {{VALUE}}!important;',
				    '.eael-popup-details-render{{WRAPPER}} .eael-onsale:not(.outofstock).sale-preset-4:after'        => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
			    ],
		    ]
	    );

	    // Quantity
	    $this->add_control(
		    'eael_product_popup_quantity',
		    [
			    'label'     => __( 'Quantity', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::HEADING,
			    'separator' => 'before',
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name'     => 'eael_product_popup_quantity_typo',
			    'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
			    'selector' => '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a',
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_quantity_color',
		    [
			    'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#000',
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_quantity_bg_color',
		    [
			    'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#fff',
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_quantity_border_color',
		    [
			    'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#000',
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'border-color: {{VALUE}};',
				    // OceanWP
				    '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty:focus'                                                                                                                                                                         => 'border-color: {{VALUE}};',
			    ],
		    ]
	    );

        // Cart Button
        $this->add_control(
            'eael_product_popup_cart_button',
            [
                'label' => __('Cart Button', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name'     => 'eael_product_popup_cart_button_typo',
			    'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
			    'selector' => '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt',
		    ]
	    );

        $this->start_controls_tabs('eael_product_popup_cart_button_style_tabs');

        $this->start_controls_tab('eael_product_popup_cart_button_style_tabs_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

	    $this->add_control(
		    'eael_product_popup_cart_button_color',
		    [
			    'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#fff',
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_cart_button_background',
		    [
			    'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#8040FF',
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name'     => 'eael_product_popup_cart_button_border',
			    'selector' => '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt',
		    ]
	    );
	    $this->add_control(
		    'eael_product_popup_cart_button_border_radius',
		    [
			    'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::SLIDER,
			    'range'     => [
				    'px' => [
					    'max' => 100,
				    ],
			    ],
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'border-radius: {{SIZE}}px;',
			    ],
		    ]
	    );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_product_popup_cart_button_hover_styles', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

	    $this->add_control(
		    'eael_product_popup_cart_button_hover_color',
		    [
			    'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#F5EAFF',
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_cart_button_hover_background',
		    [
			    'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '#F12DE0',
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_cart_button_hover_border_color',
		    [
			    'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'border-color: {{VALUE}};',
			    ],
			    'condition' => [
				    'eael_product_popup_cart_button_border_border!' => '',
			    ],
		    ]
	    );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // SKU
        $this->add_control(
            'eael_product_popup_sku_style',
            [
                'label' => __('SKU', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name'     => 'eael_product_popup_sku_typo',
			    'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
			    'selector' => '.eael-popup-details-render{{WRAPPER}} .product_meta',
		    ]
	    );


	    $this->add_control(
		    'eael_product_popup_sku_title_color',
		    [
			    'label'     => __( 'Title Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .product_meta' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_sku_content_color',
		    [
			    'label'     => __( 'Content Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .product_meta .sku, .eael-popup-details-render{{WRAPPER}} .product_meta a' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_sku_hover_color',
		    [
			    'label'     => __( 'Hover Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} .product_meta a:hover' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_close_button_style',
		    [
			    'label'     => __( ' Close Button', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::HEADING,
			    'separator' => 'before',
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_product_popup_close_button_icon_size',
		    [
			    'label'      => __( 'Icon Size', 'essential-addons-for-elementor-lite' ),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => ['px', 'em', '%'],
			    'range'      => [
				    'px' => [
					    'min' => 0,
					    'max' => 100,
				    ],
				    'em' => [
					    'min' => 0,
					    'max' => 100,
				    ],
				    '%'  => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'selectors'  => [
				    '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'font-size: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_product_popup_close_button_size',
		    [
			    'label'      => __( 'Button Size', 'essential-addons-for-elementor-lite' ),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => ['px', 'em', '%'],
			    'range'      => [
				    'px' => [
					    'min' => 0,
					    'max' => 100,
				    ],
				    'em' => [
					    'min' => 0,
					    'max' => 100,
				    ],
				    '%'  => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'selectors'  => [
				    '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'max-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_close_button_color',
		    [
			    'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'color: {{VALUE}}!important;',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_close_button_bg',
		    [
			    'label'     => __( 'Background', 'essential-addons-for-elementor-lite' ),
			    'type'      => Controls_Manager::COLOR,
			    'selectors' => [
				    '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'background-color: {{VALUE}}!important;',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_product_popup_close_button_border_radius',
		    [
			    'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			    'type'       => Controls_Manager::SLIDER,
			    'size_units' => ['px', '%'],
			    'range'      => [
				    'px' => [
					    'min'  => 0,
					    'max'  => 100,
					    'step' => 1,
				    ],
				    '%'  => [
					    'min' => 0,
					    'max' => 100,
				    ],
			    ],
			    'selectors'  => [
				    '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close' => 'border-radius: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Box_Shadow::get_type(),
		    [
			    'name'     => 'eael_product_popup_close_button_box_shadow',
			    'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
			    'selector' => '.eael-popup-details-render{{WRAPPER}} button.eael-product-popup-close',
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_product_popup_border_radius',
		    [
			    'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%'],
			    'selectors'  => [
				    '.eael-popup-details-render{{WRAPPER}}.eael-product-popup-details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
			    'separator'  => 'before',
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Background::get_type(),
		    [
			    'name'     => 'eael_product_popup_background',
			    'label'    => __( 'Background', 'essential-addons-for-elementor-lite' ),
			    'types'    => ['classic', 'gradient'],
			    'selector' => '.eael-popup-details-render{{WRAPPER}}.eael-product-popup-details',
			    'exclude'  => [
				    'image',
			    ],
		    ]
	    );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_product_popup_box_shadow',
                'label' => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-product-popup .eael-product-popup-details',
            ]
        );

        $this->end_controls_section();
    }

	function eael_customize_woo_prod_thumbnail_size( $size ) {
		$settings = $this->get_settings_for_display();

		return $settings['eael_product_grid_image_size_size'];
	}

    protected function render()
    {
        if (!function_exists('WC')) {
            return;
        }

        $settings = $this->get_settings_for_display();

        // normalize for load more fix
	    $settings['layout_mode']    = $settings["eael_product_grid_layout"];
	    $widget_id                  = $this->get_id();
	    $settings['eael_widget_id'] = $widget_id;

	    if ( $settings['post_type'] === 'source_dynamic' && is_archive() || ! empty( $_REQUEST['post_type'] ) ) {
		    $settings['posts_per_page'] = $settings['eael_product_grid_products_count'] ?: 4;
		    $settings['offset']         = $settings['product_offset'];
		    $args                       = HelperClass::get_query_args( $settings );
		    $args                       = HelperClass::get_dynamic_args( $settings, $args );
	    } else {
		    $args = $this->build_product_query( $settings );
	    }

	    $this->is_show_custom_add_to_cart       = boolval( $settings['show_add_to_cart_custom_text'] );
	    $this->simple_add_to_cart_button_text   = $settings['add_to_cart_simple_product_button_text'];
	    $this->variable_add_to_cart_button_text = $settings['add_to_cart_variable_product_button_text'];
	    $this->grouped_add_to_cart_button_text  = $settings['add_to_cart_grouped_product_button_text'];
	    $this->external_add_to_cart_button_text = $settings['add_to_cart_external_product_button_text'];
	    $this->default_add_to_cart_button_text  = $settings['add_to_cart_default_product_button_text'];

	    if ( Plugin::$instance->documents->get_current() ) {
		    $this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
	    }
        // render dom
	    $this->add_render_attribute( 'wrap', [
		    'class'          => [
			    "eael-product-grid",
			    $settings['eael_product_grid_style_preset'],
			    $settings['eael_product_grid_layout']
		    ],
		    'id'             => 'eael-product-grid',
		    'data-widget-id' => $widget_id,
		    'data-page-id'   => $this->page_id,
		    'data-nonce'     => wp_create_nonce( 'eael_product_grid' ),
	    ] );

	    add_filter( 'woocommerce_product_add_to_cart_text', [
		    $this,
		    'add_to_cart_button_custom_text',
	    ] );
        ?>

        <div <?php $this->print_render_attribute_string('wrap'); ?> >
            <div class="woocommerce">
                <?php
                do_action( 'eael_woo_before_product_loop' );

                $template                       = $this->get_template( $settings['eael_dynamic_template_Layout'] );
                $settings['loadable_file_name']  = $this->get_filename_only( $template );
                $dir_name                       = $this->get_temp_dir_name( $settings['loadable_file_name'] );
                $found_posts                    = 0;

                if ( file_exists( $template ) ) {
	                $settings['eael_page_id'] = $this->page_id ? $this->page_id : get_the_ID();
	                $query                    = new \WP_Query( $args );
	                if ( $query->have_posts() ) {
		                $found_posts        = $query->found_posts;
		                $max_page           = ceil( $found_posts / absint( $args['posts_per_page'] ) );
		                $args['max_page']   = $max_page;
		                $args['total_post'] = $found_posts;

		                printf( '<ul class="products" data-layout-mode="%s">', esc_attr( $settings["eael_product_grid_layout"] ) );

                            while ( $query->have_posts() ) {
                                $query->the_post();
                                include( realpath( $template ) );
                            }
                            wp_reset_postdata();

		                echo '</ul>';

	                } else {
		                _e( '<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite' );
	                }

                } else {
	                _e( '<p class="no-posts-found">No layout found!</p>', 'essential-addons-for-elementor-lite' );
                }

                if ( 'true' == $settings['show_pagination'] ) {
	                $settings['eael_widget_name'] = $this->get_name();
	                echo HelperClass::eael_pagination( $args, $settings );
                }

                if ( $found_posts > $args['posts_per_page'] ) {
	                $this->print_load_more_button( $settings, $args, $dir_name );
                }

                ?>
            </div>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                var $scope = jQuery(".elementor-element-<?php echo $this->get_id(); ?>");
                var $products = $('.products', $scope);
                var $layout_mode = $products.data('layout-mode');
                
                if ( $layout_mode === 'masonry' ) {
                    // init isotope
                    var $isotope_products = $products.isotope({
                        itemSelector: "li.product",
                        layoutMode: $layout_mode,
                        percentPosition: true
                    });

                    $isotope_products.imagesLoaded().progress( function() {
                        $isotope_products.isotope('layout');
                    })

                    $(window).on('resize', function() {
                        $isotope_products.isotope('layout');
                    });
                }
            });
        </script>
        <?php
        remove_filter('woocommerce_product_add_to_cart_text', [
            $this,
            'add_to_cart_button_custom_text',
        ]);
	    remove_filter( 'single_product_archive_thumbnail_size', [ $this, 'eael_customize_woo_prod_thumbnail_size' ] );
    }

    /**
     * build_product_query
     * @param $settings
     * @return array
     */
    public function build_product_query( $settings ){
        $args = [
            'post_type' => 'product',
            'post_status'    => ! empty( $settings['eael_product_grid_products_status'] ) ? $settings['eael_product_grid_products_status'] : [ 'publish', 'pending', 'future' ],
            'posts_per_page' => $settings['eael_product_grid_products_count'] ?: 4,
            'order' => (isset($settings['order']) ? $settings['order'] : 'desc'),
            'offset' => $settings['product_offset'],
            'tax_query' => [
                'relation' => 'AND',
                [
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => ['exclude-from-search', 'exclude-from-catalog'],
                    'operator' => 'NOT IN',
                ],
            ],
        ];
        // price & sku filter
        if ($settings['orderby'] == '_price') {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = '_price';
        } else if ($settings['orderby'] == '_sku') {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = '_sku';
        } else {
            $args['orderby'] = (isset($settings['orderby']) ? $settings['orderby'] : 'date');
        }

        if (!empty($settings['eael_product_grid_categories'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $settings['eael_product_grid_categories'],
                    'operator' => 'IN',
                ],
            ];
        }

        $args['meta_query'] = ['relation' => 'AND'];

        if (get_option('woocommerce_hide_out_of_stock_items') == 'yes') {
            $args['meta_query'][] = [
                'key' => '_stock_status',
                'value' => 'instock'
            ];
        }

        if ($settings['eael_product_grid_product_filter'] == 'featured-products') {
            $args['tax_query'] = [
                'relation' => 'AND',
                [
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                ],
                [
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => ['exclude-from-search', 'exclude-from-catalog'],
                    'operator' => 'NOT IN',
                ],
            ];

            if ($settings['eael_product_grid_categories']) {
                $args['tax_query'][] = [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $settings['eael_product_grid_categories'],
                ];
            }
        }
        else if ($settings['eael_product_grid_product_filter'] == 'best-selling-products') {
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }
        else if ($settings['eael_product_grid_product_filter'] == 'sale-products') {
            $args['post__in']  = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
        }
        else if ($settings['eael_product_grid_product_filter'] == 'top-products') {
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }
        else if( $settings['eael_product_grid_product_filter'] == 'manual' ){
            $args['post__in'] = $settings['eael_product_grid_products_in'] ? $settings['eael_product_grid_products_in'] : [ 0 ];
        }

        return $args;
    }

    protected function eael_get_product_statuses() {
        return apply_filters( 'eael/woo-product-grid/product-statuses', [
            'publish'       => esc_html__( 'Publish', 'essential-addons-for-elementor-lite' ),
            'draft'         => esc_html__( 'Draft', 'essential-addons-for-elementor-lite' ),
            'pending'       => esc_html__( 'Pending Review', 'essential-addons-for-elementor-lite' ),
            'future'        => esc_html__( 'Schedule', 'essential-addons-for-elementor-lite' ),
        ] );
    }

    public function load_quick_view_asset(){
	    add_action('wp_footer',function (){
		    if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
			    if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
				    wp_enqueue_script( 'zoom' );
			    }
			    if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
				    wp_enqueue_script( 'flexslider' );
			    }
			    if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
				    wp_enqueue_script( 'photoswipe-ui-default' );
				    wp_enqueue_style( 'photoswipe-default-skin' );
				    if ( has_action( 'wp_footer', 'woocommerce_photoswipe' ) === false ) {
					    add_action( 'wp_footer', 'woocommerce_photoswipe', 15 );
				    }
			    }
			    wp_enqueue_script( 'wc-add-to-cart-variation' );
			    wp_enqueue_script( 'wc-single-product' );
		    }
	    });
    }
}

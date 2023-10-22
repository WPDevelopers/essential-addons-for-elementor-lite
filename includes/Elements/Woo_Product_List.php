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
use Essential_Addons_Elementor\Classes\Helper as ClassesHelper;
use Essential_Addons_Elementor\Traits\Helper;

class Woo_Product_List extends Widget_Base
{
    use Helper;

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
        return 'eael-woo-product-list';
    }

    public function get_title()
    {
        return esc_html__('Woo Product List', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-woo-product-list';
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
            'ea woo product list',
            'ea woocommerce product list',
            'product gallery',
            'woocommerce list',
            'gallery',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/woo-product-list/';
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

    protected function eael_get_product_orderby_options()
    {
        return apply_filters('eael/woo-product-list/orderby-options', [
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
        return apply_filters('eael/woo-product-list/filterby-options', [
            'recent-products' => esc_html__('Recent Products', 'essential-addons-for-elementor-lite'),
            'featured-products' => esc_html__('Featured Products', 'essential-addons-for-elementor-lite'),
            'best-selling-products' => esc_html__('Best Selling Products', 'essential-addons-for-elementor-lite'),
            'sale-products' => esc_html__('Sale Products', 'essential-addons-for-elementor-lite'),
            'top-products' => esc_html__('Top Rated Products', 'essential-addons-for-elementor-lite'),
            'manual' => esc_html__('Manual Selection', 'essential-addons-for-elementor-lite'),
        ]);
    }

    protected function register_controls() {
        $this->init_content_wc_notice_controls();

        if ( !function_exists( 'WC' ) ) {
            return;
        }

        $this->eael_product_list_layout();
        $this->eael_product_list_query();
        $this->eael_product_list_image();
        $this->eael_product_list_content();
        // $this->eael_product_list_popup();
        // $this->eael_product_list_carousel();

        $this->eael_product_list_container_style();
        $this->eael_product_list_item_style();
        $this->eael_product_list_item_content_style();
        $this->eael_product_list_popup_style();
        $this->eael_product_list_color_typography_style();
    }

    protected function init_content_wc_notice_controls() {
		if ( ! function_exists( 'WC' ) ) {
			$this->start_controls_section( 'eael_global_warning', [
				'label' => __( 'Warning!', 'essential-addons-for-elementor-lite' ),
			] );
			$this->add_control( 'eael_global_warning_text', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.', 'essential-addons-for-elementor-lite' ),
				'content_classes' => 'eael-warning',
			] );
			$this->end_controls_section();

			return;
		}
	}

    protected function eael_product_list_layout() {
        $this->start_controls_section(
            'eael_section_woo_product_list_layouts',
            [
                'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
		    'eael_dynamic_template_layout',
		    [
			    'label'   => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
			    'type'    => Controls_Manager::SELECT,
			    'default' => 'preset-1',
			    'options' => $this->get_template_list_for_dropdown(true),
		    ]
	    );

        $this->add_control(
            'eael_product_list_layout_general_heading',
            [
                'label' => __('Image', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control('eael_woo_product_list_badge_show', [
            'label' => esc_html__('Badge', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_carousel_show', [
            'label' => esc_html__('Carousel', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => '',
            'condition'     => [
                'eael_dynamic_template_layout!' => 'preset-2',
            ]
        ]);

        $this->add_control('eael_woo_product_list_carousel_preset_2_show', [
            'label' => esc_html__('Carousel', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
            'condition'     => [
                'eael_dynamic_template_layout' => 'preset-2',
            ]
        ]);

        $this->add_control(
            'eael_product_list_layout_content_header_heading',
            [
                'label' => __('Content Header', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control('eael_woo_product_list_rating_show', [
            'label' => esc_html__('Rating', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_rating_count_show', [
            'label' => esc_html__('Rating Count', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_shipping_method_show', [
            'label' => esc_html__('Shipping Method', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
            'condition'     => [
                'eael_dynamic_template_layout!' => 'preset-2',
            ]
        ]);

        $this->add_control('eael_woo_product_list_category_show', [
            'label' => esc_html__('Category', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
            'condition'     => [
                'eael_dynamic_template_layout' => 'preset-2',
            ]
        ]);

        $this->add_control('eael_woo_product_list_countdown_show', [
            'label' => esc_html__('Countdown', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value'  => 'yes',
            'default'       => '',
            'condition'     => [
                'eael_dynamic_template_layout' => 'preset-3',
            ]
        ]);

        $this->add_control(
            'eael_product_list_layout_content_body_heading',
            [
                'label' => __('Content Body', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control('eael_woo_product_list_title_show', [
            'label' => esc_html__('Title', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_excerpt_show', [
            'label' => esc_html__('Excerpt', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_price_show', [
            'label' => esc_html__('Price', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_size_show', [
            'label' => esc_html__('Size', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => '',
            'condition'     => [
                'eael_dynamic_template_layout!' => 'preset-3',
            ]
        ]);

        $this->add_control('eael_woo_product_list_size_preset_3_show', [
            'label' => esc_html__('Size', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
            'condition'     => [
                'eael_dynamic_template_layout' => 'preset-3',
            ]
        ]);

        $this->add_control(
            'eael_product_list_layout_content_footer_heading',
            [
                'label' => __('Content Footer', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control('eael_woo_product_list_total_sold_show', [
            'label' => esc_html__('Total Sold', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => '',
            'condition'     => [
                'eael_dynamic_template_layout' => 'preset-1',
            ]
        ]);

        $this->add_control('eael_woo_product_list_total_sold_preset_2_3_show', [
            'label' => esc_html__('Total Sold', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
            'condition'     => [
                'eael_dynamic_template_layout!' => 'preset-1',
            ]
        ]);

        $this->add_control('eael_woo_product_list_add_to_cart_button_show', [
            'label' => esc_html__('Add to Cart', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_quick_view_button_show', [
            'label' => esc_html__('Quick View', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control('eael_woo_product_list_link_button_show', [
            'label' => esc_html__('Link', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->end_controls_section();
    }

    protected function eael_product_list_query() {
        $this->start_controls_section('eael_section_woo_product_list_query', [
            'label' => esc_html__('Query', 'essential-addons-for-elementor-lite'),
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

        if ( ! apply_filters( 'eael/is_plugin_active', 'woocommerce/woocommerce.php' ) ) {
            $this->add_control(
                'ea_woo_product_list_woo_required',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.', 'essential-addons-for-elementor-lite' ),
                    'content_classes' => 'eael-warning',
                ]
            );
        }

        $this->add_control('eael_product_list_product_filter', [
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
            'condition' => [
                'eael_product_list_product_filter!' => [ 'best-selling-products', 'top-products' ],
            ]
        ]);

        $this->add_control( 'order', [
            'label'   => __( 'Order', 'essential-addons-for-elementor-lite' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
                'asc'  => 'Ascending',
                'desc' => 'Descending',
            ],
            'default' => 'desc',
            'condition' => [
                'eael_product_list_product_filter!' => [ 'best-selling-products' ],
            ]
        ]);

        $this->add_control('eael_woo_product_list_products_count', [
            'label' => __('Count', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => 4,
            'min' => 1,
            'max' => 1000,
            'step' => 1,
            'separator' => 'before',
        ]);

        $this->add_control('product_offset', [
            'label' => __('Offset', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                'eael_product_list_product_filter!' => 'manual'
            ],
        ]);

        $this->add_control(
            'eael_product_list_products_status',
            [
                'label' => __( 'Status', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'default' => [ 'publish', 'pending', 'future' ],
                'options' => $this->eael_get_product_statuses(),
                'condition' => [
                    'eael_product_list_product_filter!' => 'manual'
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control('eael_product_list_categories', [
            'label' => esc_html__('Categories', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'multiple' => true,
            'options' => ClassesHelper::get_terms_list('product_cat', 'slug'),
            'condition'   => [
              'post_type!' => 'source_dynamic',
              'eael_product_list_product_filter!' => 'manual'
            ],
        ]);

        $this->add_control('eael_product_list_products_in', [
            'label' => esc_html__('Select Products', 'essential-addons-for-elementor-lite'),
            'type'        => 'eael-select2',
            'label_block' => true,
            'multiple' => true,
            'source_name' => 'post_type',
            'source_type' => 'product',
            'condition'   => [
                'post_type!' => 'source_dynamic',
                'eael_product_list_product_filter' => 'manual'
            ],
        ]);

        $this->end_controls_section();
    }

    protected function eael_product_list_image() {
        $this->start_controls_section('eael_section_woo_product_list_image', [
            'label' => esc_html__('Image', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'        => 'eael_product_list_image_size',
                'exclude'     => ['custom'],
                'default'     => 'medium',
                'label_block' => true,
            ]
        );
        
        $this->add_control(
            'eael_product_list_image_stretch',
            [
                'label'        => __( 'Stretch', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'eael_product_list_image_clickable',
            [
                'label' => esc_html__('Clickable', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    protected function eael_product_list_content() {
        $this->start_controls_section('eael_section_woo_product_list_content', [
            'label' => esc_html__('Content', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control(
            'eael_product_list_content_general_heading',
            [
                'label' => __('General', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_list_content_general_button_position',
            [
                'label'   => __( 'Buttons', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'both',
                'options' => [
                    'both'    => esc_html__( 'Both', 'essential-addons-for-elementor-lite' ),
                    'static'    => esc_html__( 'Static', 'essential-addons-for-elementor-lite' ),
                    'on-hover'  => esc_html__( 'On Hover', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_header_heading',
            [
                'label' => __('Content Header', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_content_header_position',
            [
                'label'   => __( 'Position', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'before-title',
                'options' => [
                    'before-title'        => esc_html__( 'Before Title', 'essential-addons-for-elementor-lite' ),
                    'after-title' => esc_html__( 'After Title', 'essential-addons-for-elementor-lite' ),
                ],
                'condition' => [
				    'eael_dynamic_template_layout!' => 'preset-3',
			    ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_header_position_preset_3',
            [
                'label'   => __( 'Position', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'after-title',
                'options' => [
                    'before-title'        => esc_html__( 'Before Title', 'essential-addons-for-elementor-lite' ),
                    'after-title' => esc_html__( 'After Title', 'essential-addons-for-elementor-lite' ),
                ],
                'condition' => [
				    'eael_dynamic_template_layout' => 'preset-3',
			    ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_header_direction',
            [
                'label'   => __( 'Direction', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'ltr',
                'options' => [
                    'ltr'        => esc_html__( 'Left to Right', 'essential-addons-for-elementor-lite' ),
                    'rtl' => esc_html__( 'Right to Left', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_body_heading',
            [
                'label' => __('Content Body', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_content_body_title_heading',
            [
                'label' => __('Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
		    'eael_product_list_content_body_title_tag',
		    [
			    'label' => __('Tag', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SELECT,
			    'default' => 'h2',
			    'options' => [
				    'h1' => __('H1', 'essential-addons-for-elementor-lite'),
				    'h2' => __('H2', 'essential-addons-for-elementor-lite'),
				    'h3' => __('H3', 'essential-addons-for-elementor-lite'),
				    'h4' => __('H4', 'essential-addons-for-elementor-lite'),
				    'h5' => __('H5', 'essential-addons-for-elementor-lite'),
				    'h6' => __('H6', 'essential-addons-for-elementor-lite'),
				    'p' => __('P', 'essential-addons-for-elementor-lite'),
				    'div' => __('Div', 'essential-addons-for-elementor-lite'),
			    ],
			    'condition' => [
				    'eael_woo_product_list_title_show' => 'yes',
			    ],
		    ]
	    );

        $this->add_control(
            'eael_product_list_content_body_title_clickable',
            [
                'label' => esc_html__('Clickable', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
				    'eael_woo_product_list_title_show' => 'yes',
			    ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_body_excerpt_heading',
            [
                'label' => __('Excerpt', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_list_content_body_excerpt_words_count',
            [
                'label'     => __( 'Words Count', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => '30',
                'condition' => [
                    'eael_woo_product_list_excerpt_show' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_list_content_body_excerpt_expanison_indicator',
            [
                'label'       => esc_html__( 'Expansion Indicator', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => '...',
                'condition'   => [
                    'eael_woo_product_list_excerpt_show' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_heading',
            [
                'label' => __('Content Footer', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    protected function eael_product_list_popup() {
        $this->start_controls_section('eael_section_woo_product_list_popup', [
            'label' => esc_html__('Popup', 'essential-addons-for-elementor-lite'),
        ]);

        $this->end_controls_section();
    }

    protected function eael_product_list_carousel() {
        $this->start_controls_section('eael_section_woo_product_list_carousel', [
            'label' => esc_html__('Carousel', 'essential-addons-for-elementor-lite'),
        ]);

        $this->end_controls_section();
    }

    protected function eael_product_list_container_style() {

	    $this->start_controls_section(
			'eael_section_product_list_container_style',
			[
				'label' => esc_html__( 'Container', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_product_list_container_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_container_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_product_list_container_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-container' => 'border-radius: {{SIZE}}px;',
				],
			]
		);
        
		$this->add_control(
			'eael_product_list_container_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
                'default'   => '#F4F5F7',
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-container' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_list_container_normal_border',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-container',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_list_container_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-container',
			]
		);

		$this->end_controls_section();
    }
    
    protected function eael_product_list_item_style() {

	    $this->start_controls_section(
			'eael_section_product_list_item_style',
			[
				'label' => esc_html__( 'Item', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_product_list_item_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
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
				'default'    => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 15,
					'left'     => 0,
					'unit'     => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item:not(:last-child)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
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
				'default'    => [
					'top'      => '64',
					'right'    => '64',
					'bottom'   => '64',
					'left'     => '64',
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_product_list_item_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_product_list_item_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_list_item_normal_border',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_list_item_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item',
			]
		);

		$this->end_controls_section();
    }

    protected function eael_product_list_item_content_style() {

	    $this->start_controls_section(
			'eael_section_product_list_item_content_style',
			[
				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
            'eael_product_list_content_header_heading_style',
            [
                'label' => __('Content Header', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_list_content_header_general_heading_style',
            [
                'label' => __('General', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

		$this->add_responsive_control(
			'eael_product_list_content_header_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_header_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'eael_product_list_content_header_countdown_heading_style',
            [
                'label' => __('Countdown', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

		$this->add_responsive_control(
			'eael_product_list_content_header_countdown_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-countdown .eael-product-list-countdown-items' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_header_countdown_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-countdown .eael-product-list-countdown-items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'eael_product_list_content_header_countdown_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-countdown .eael-product-list-countdown-items' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_list_content_header_countdown_normal_border',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-countdown .eael-product-list-countdown-items',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_list_content_header_countdown_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-countdown .eael-product-list-countdown-items',
			]
		);

        $this->add_control(
            'eael_product_list_content_body_heading_style',
            [
                'label' => __('Content Body', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_content_body_title_heading_style',
            [
                'label' => __('Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

		$this->add_responsive_control(
			'eael_product_list_content_body_title_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-body .eael-product-list-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_body_title_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-body .eael-product-list-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'eael_product_list_content_body_excerpt_heading_style',
            [
                'label' => __('Excerpt', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

		$this->add_responsive_control(
			'eael_product_list_content_body_excerpt_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-body .eael-product-list-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_body_excerpt_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-body .eael-product-list-excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'eael_product_list_content_body_price_heading_style',
            [
                'label' => __('Price', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

		$this->add_responsive_control(
			'eael_product_list_content_body_price_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-body .eael-product-list-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_body_price_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-body .eael-product-list-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'eael_product_list_content_footer_heading_style',
            [
                'label' => __('Content Footer', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_total_sold_heading_style',
            [
                'label' => __('Total Sold', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
			'eael_product_list_content_footer_total_sold_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
                'default'   => [
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-bar-outer' => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-bar-outer .eael-product-list-progress-bar-inner' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

        $this->add_control(
            'eael_product_list_content_footer_add_to_cart_heading_style',
            [
                'label' => __('Add to Cart', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

		$this->add_responsive_control(
			'eael_product_list_content_footer_add_to_cart_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-add-to-cart-button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_footer_add_to_cart_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-add-to-cart-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'eael_product_list_content_footer_add_to_cart_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-add-to-cart-button a' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_list_content_footer_add_to_cart_normal_border',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-add-to-cart-button a',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_list_content_footer_add_to_cart_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-add-to-cart-button a',
			]
		);

        $this->add_control(
            'eael_product_list_content_footer_quick_view_heading_style',
            [
                'label' => __('Quick View', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

		$this->add_responsive_control(
			'eael_product_list_content_footer_quick_view_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-quick-view-button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_footer_quick_view_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-quick-view-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'eael_product_list_content_footer_quick_view_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-quick-view-button a' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_list_content_footer_quick_view_normal_border',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-quick-view-button a',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_list_content_footer_quick_view_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-quick-view-button a',
			]
		);

        $this->add_control(
            'eael_product_list_content_footer_on_hover_buttons_heading_style',
            [
                'label' => __('On Hover Buttons', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );

		$this->add_responsive_control(
			'eael_product_list_content_footer_on_hover_buttons_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'conditions' => $this->on_hover_buttons_conditions(),
			]
		);

		$this->add_responsive_control(
			'eael_product_list_content_footer_on_hover_buttons_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'conditions' => $this->on_hover_buttons_conditions(),
			]
		);

        $this->add_control(
			'eael_product_list_content_footer_on_hover_buttons_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a' => 'border-radius: {{SIZE}}px;',
				],
                'conditions' => $this->on_hover_buttons_conditions(),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_list_content_footer_on_hover_buttons_normal_border',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a',
                'conditions' => $this->on_hover_buttons_conditions(),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_list_content_footer_on_hover_buttons_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a',
                'conditions' => $this->on_hover_buttons_conditions(),
			]
		);

		$this->end_controls_section();
    }

    protected function eael_product_list_popup_style() {

        $this->start_controls_section(
            'eael_product_popup',
            [
                'label' => __('Popup', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
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
				    '.eael-popup-details-render{{WRAPPER}} div.product form.cart div.quantity .qty:focus' => 'border-color: {{VALUE}};',
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

        $this->start_controls_tabs('eael_product_popup_cart_button_style_tabs');

        $this->start_controls_tab('eael_product_popup_cart_button_style_tabs_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

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

	protected function eael_product_list_color_typography_style() {

        $this->start_controls_section(
            'eael_section_product_list_color_typography_style',
            [
                'label' => esc_html__('Color &amp; Typography', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_content_header_heading',
            [
                'label' => __('Content Header', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_rating_heading',
            [
                'label' => __('Star Rating', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_list_rating_color',
            [
                'label' => esc_html__('Rating Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FF9900',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating::before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating span::before' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} div.product .star-rating' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_list_rating_size',
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
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating::before' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating span::before' => 'font-size: {{SIZE}}px;',
                    '.eael-popup-details-render{{WRAPPER}} div.product .star-rating' => 'font-size: {{SIZE}}px; width: 100%;',
                    '.eael-popup-details-render{{WRAPPER}} div.product .star-rating::before' => 'font-size: {{SIZE}}px;',
                    '.eael-popup-details-render{{WRAPPER}} div.product .star-rating span::before' => 'font-size: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_countdown_heading',
            [
                'label' => __('Countdown', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition'     => [
                    'eael_woo_product_list_countdown_show' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_countdown_text_color',
            [
                'label'     => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFEBCE',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-countdown .eael-product-list-countdown-items .eael-product-list-countdown-item .eael-product-list-countdown-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-countdown .eael-product-list-countdown-items .eael-product-list-countdown-item:not(:last-child)::after' => 'color: {{VALUE}};',
                ],
                'condition'     => [
                    'eael_woo_product_list_countdown_show' => 'yes',
                ]
            ]
        );
        
        $this->add_control(
            'eael_product_list_color_typography_countdown_number_color',
            [
                'label'     => esc_html__( 'Number', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-countdown .eael-product-list-countdown-items .eael-product-list-countdown-item .eael-product-list-countdown-number' => 'color: {{VALUE}};',
                ],
                'condition'     => [
                    'eael_woo_product_list_countdown_show' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_countdown_bg_color',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FB812E',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-countdown .eael-product-list-countdown-items' => 'background: {{VALUE}};',
                ],
                'condition'     => [
                    'eael_woo_product_list_countdown_show' => 'yes',
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_countdown_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-countdown .eael-product-list-countdown-items .eael-product-list-countdown-item *',
                'condition'     => [
                    'eael_woo_product_list_countdown_show' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_content_body_heading',
            [
                'label' => __('Content Body', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_title_heading',
            [
                'label' => __('Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_color_typography_title_color_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_color_typography_title_color_tabs_normal',
            [
                'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_title_color_normal',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343434',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-title a' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} div.product .product_title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_color_typography_title_color_tabs_hover',
            [
                'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                    'eael_product_list_content_body_title_clickable' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_title_color_hover',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#758F4D',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-title a:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                    'eael_product_list_content_body_title_clickable' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_title_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-title, {{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-title a, .eael-popup-details-render{{WRAPPER}} div.product .product_title',
                'condition' => [
                    'eael_woo_product_list_title_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_excerpt_heading',
            [
                'label' => __('Excerpt', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_woo_product_list_excerpt_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_excerpt_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5F6368',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-excerpt' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_excerpt_show' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_excerpt_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-excerpt, .eael-popup-details-render{{WRAPPER}} .woocommerce-product-details__short-description',
                'condition' => [
                    'eael_woo_product_list_excerpt_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_regular_price_heading',
            [
                'label' => __( 'Regular Price', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
                'condition' => [
                    'eael_woo_product_list_price_show' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_list_color_typography_regular_price_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#757C86',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-price del' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} div.product .price' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_price_show' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_regular_price_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-price del, .eael-popup-details-render{{WRAPPER}} div.product .price',
                'condition' => [
                    'eael_woo_product_list_price_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_sale_price_heading',
            [
                'label' => __( 'Sale Price', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
                'condition' => [
                    'eael_woo_product_list_price_show' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_list_color_typography_sale_price_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343434',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-price ins' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-price > .amount' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} div.product .price ins' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_woo_product_list_price_show' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_sale_price_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-price ins, {{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-body .eael-product-list-price > .amount, .eael-popup-details-render{{WRAPPER}} div.product .price ins',
                'condition' => [
                    'eael_woo_product_list_price_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_content_footer_heading',
            [
                'label' => __('Content Footer', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_total_sold_heading',
            [
                'label' => __('Total Sold', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_color_typography_total_sold_text_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_color_typography_total_sold_text',
            [
                'label' => esc_html__( 'Total Sold', 'essential-addons-for-elementor-lite' ),
                'conditions' => $this->total_sold_conditions(),
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_total_sold_color',
            [
                'label'     => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#515151',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-info .eael-product-list-progress-count' => 'color: {{VALUE}};',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_total_sold_count_color',
            [
                'label'     => esc_html__( 'Count', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343434',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-info .eael-product-list-progress-count span' => 'color: {{VALUE}};',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_color_typography_total_sold_remaining_text',
            [
                'label' => esc_html__( 'Remaining', 'essential-addons-for-elementor-lite' ),
                'conditions' => $this->total_sold_conditions(),
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_total_sold_remaining_color',
            [
                'label'     => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#515151',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-info .eael-product-list-progress-remaining' => 'color: {{VALUE}};',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_total_sold_remaining_count_color',
            [
                'label'     => esc_html__( 'Count', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#343434',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-info .eael-product-list-progress-remaining span' => 'color: {{VALUE}};',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_control(
            'eael_product_list_color_typography_total_sold_progress_color',
            [
                'label'     => esc_html__( 'Progress Outer', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#EFE4E4',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-bar-outer' => 'background: {{VALUE}};',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_total_sold_progress_inner_color',
            [
                'label'     => esc_html__( 'Progress Inner', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#C29F9D',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-bar-outer .eael-product-list-progress-bar-inner' => 'background: {{VALUE}};',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->add_responsive_control(
            'eael_product_list_color_typography_total_sold_progress_height',
            [
                'label' => esc_html__('Progress Height', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-bar-outer' => 'height: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-bar-outer .eael-product-list-progress-bar-inner' => 'height: {{SIZE}}px;',
                ],
                'conditions' => $this->total_sold_conditions(),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_total_sold_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-info .eael-product-list-progress-count, {{WRAPPER}} .eael-product-list-wrapper .eael-product-list-content-footer .eael-product-list-progress .eael-product-list-progress-info .eael-product-list-progress-remaining',
                'conditions' => $this->total_sold_conditions(),
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_add_to_cart_heading',
            [
                'label' => __('Add to Cart', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_color_typography_add_to_cart_color_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_color_typography_add_to_cart_color_tabs_normal',
            [
                'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_add_to_cart_color_normal',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-add-to-cart-button a' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_list_color_typography_add_to_cart_bg_color_normal',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-add-to-cart-button a' => 'background: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_color_typography_add_to_cart_color_tabs_hover',
            [
                'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_add_to_cart_color_hover',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-add-to-cart-button a:hover' => 'color: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_add_to_cart_bg_color_hover',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-add-to-cart-button a:hover' => 'background: {{VALUE}};',
                    '.eael-popup-details-render{{WRAPPER}} .button:hover, .eael-popup-details-render{{WRAPPER}} button.button.alt:hover' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_add_to_cart_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-add-to-cart-button a, .eael-popup-details-render{{WRAPPER}} .button, .eael-popup-details-render{{WRAPPER}} button.button.alt',
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_add_to_cart_button_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_buttons_quick_view_heading',
            [
                'label' => __('Quick View', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_color_typography_quick_view_color_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_color_typography_quick_view_color_tabs_normal',
            [
                'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_quick_view_color_normal',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-quick-view-button a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'eael_product_list_color_typography_quick_view_bg_color_normal',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-quick-view-button a' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_color_typography_quick_view_color_tabs_hover',
            [
                'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_quick_view_color_hover',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-quick-view-button a:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_quick_view_bg_color_hover',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-quick-view-button a:hover' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_quick_view_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-content-footer .eael-product-list-quick-view-button a',
                'condition' => [
                    'eael_product_list_content_general_button_position!' => 'on-hover',
                    'eael_woo_product_list_quick_view_button_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_on_hover_buttons_heading',
            [
                'label' => __('On Hover Buttons', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );

        $this->start_controls_tabs( 'eael_product_list_color_typography_on_hover_buttons_color_tabs' );
        
        $this->start_controls_tab( 
            'eael_product_list_color_typography_on_hover_buttons_color_tabs_normal',
            [
                'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
                'conditions' => $this->on_hover_buttons_conditions(),
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_on_hover_buttons_color_normal',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a' => 'color: {{VALUE}};',
                ],
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );
        
        $this->add_control(
            'eael_product_list_color_typography_on_hover_buttons_bg_color_normal',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a' => 'background: {{VALUE}};',
                ],
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 
            'eael_product_list_color_typography_on_hover_buttons_color_tabs_hover',
            [
                'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
                'conditions' => $this->on_hover_buttons_conditions(),
            ] 
        );

        $this->add_control(
            'eael_product_list_color_typography_on_hover_buttons_color_hover',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a:hover' => 'color: {{VALUE}};',
                ],
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );

        $this->add_control(
            'eael_product_list_color_typography_on_hover_buttons_bg_color_hover',
            [
                'label'     => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a:hover' => 'background: {{VALUE}};',
                ],
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_list_color_typography_on_hover_buttons_typography',
                'selector' => '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li a:not(.add_to_cart_button), {{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .eael-product-list-image-wrap .eael-product-list-buttons-on-hover li .add_to_cart_button::before',
                'conditions' => $this->on_hover_buttons_conditions(),
            ]
        );

        $this->end_controls_section();
    }

    protected function on_hover_buttons_conditions(){
        $conditions =   
        [
            'relation' => 'and',
            'terms' => [
                [
                    'name' => 'eael_product_list_content_general_button_position',
                    'operator' => '!==',
                    'value' => 'static',
                ],
                [
                'relation' => 'or',
                'terms' => [
                        [
                            'name' => 'eael_woo_product_list_add_to_cart_button_show',
                            'operator' => '===',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'eael_woo_product_list_quick_view_button_show',
                            'operator' => '===',
                            'value' => 'yes',
                        ],
                        [
                            'name' => 'eael_woo_product_list_link_button_show',
                            'operator' => '===',
                            'value' => 'yes',
                        ],
                    ],
                ],
            ],
        ];
        
        return $conditions;
    }
    
    protected function total_sold_conditions(){
        $conditions =   
        [
            'relation' => 'or',
            'terms' => [
                [
                    'name' => 'eael_woo_product_list_total_sold_show',
                    'operator' => '===',
                    'value' => 'yes',
                ],
                [
                    'name' => 'eael_woo_product_list_total_sold_preset_2_3_show',
                    'operator' => '===',
                    'value' => 'yes',
                ],
            ],
        ];
        
        return $conditions;
    }

    protected function eael_get_product_statuses() {
        return apply_filters( 'eael/woo-woo-product-list/product-statuses', [
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

    protected function eael_get_product_title_html( $woo_product_list, $product ){
        if ( $woo_product_list['title_show'] ) : ?>
            <<?php echo $woo_product_list['title_tag'];  ?> class="eael-product-list-title">
                <?php if ( $woo_product_list['title_clickable'] ) : ?>
                <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link" target="_blank">
                    <?php echo ClassesHelper::eael_wp_kses( $product->get_title() ); ?>
                </a>
                <?php else : ?>
                    <?php echo ClassesHelper::eael_wp_kses( $product->get_title() ); ?>
                <?php endif; ?>
            </<?php echo $woo_product_list['title_tag'];  ?>>
        <?php 
        endif;
    }

    protected function get_woo_product_list_settings() {
		$settings 							= $this->get_settings_for_display();
		
		$woo_product_list 					= [];
		$woo_product_list['layout'] 		= ! empty( $settings['eael_dynamic_template_layout'] ) ? $settings['eael_dynamic_template_layout'] : 'preset-1';
		
        $woo_product_list['rating_show']    = ! empty( $settings['eael_woo_product_list_rating_show'] ) && 'yes' === $settings['eael_woo_product_list_rating_show'] ? 1 : 0;
		$woo_product_list['countdown_show'] = ! empty( $settings['eael_woo_product_list_countdown_show'] ) && 'yes' === $settings['eael_woo_product_list_countdown_show'] ? 1 : 0;
		$woo_product_list['title_show']     = ! empty( $settings['eael_woo_product_list_title_show'] ) && 'yes' === $settings['eael_woo_product_list_title_show'] ? 1 : 0;
		$woo_product_list['excerpt_show']   = ! empty( $settings['eael_woo_product_list_excerpt_show'] ) && 'yes' === $settings['eael_woo_product_list_excerpt_show'] ? 1 : 0;
		$woo_product_list['price_show']     = ! empty( $settings['eael_woo_product_list_price_show'] ) && 'yes' === $settings['eael_woo_product_list_price_show'] ? 1 : 0;
		$woo_product_list['total_sold_show']                = ! empty( $settings['eael_woo_product_list_total_sold_show'] ) && 'yes' === $settings['eael_woo_product_list_total_sold_show'] ? 1 : 0;
		$woo_product_list['add_to_cart_button_show']        = ! empty( $settings['eael_woo_product_list_add_to_cart_button_show'] ) && 'yes' === $settings['eael_woo_product_list_add_to_cart_button_show'] ? 1 : 0;
		$woo_product_list['quick_view_button_show']         = ! empty( $settings['eael_woo_product_list_quick_view_button_show'] ) && 'yes' === $settings['eael_woo_product_list_quick_view_button_show'] ? 1 : 0;
		$woo_product_list['link_button_show']               = ! empty( $settings['eael_woo_product_list_link_button_show'] ) && 'yes' === $settings['eael_woo_product_list_link_button_show'] ? 1 : 0;
		
		$woo_product_list['image_size']                     = ! empty( $settings['eael_product_list_image_size_size'] ) ? esc_html( $settings['eael_product_list_image_size_size'] ) : esc_html( 'medium' );
		$woo_product_list['image_clickable']                = ! empty( $settings['eael_product_list_image_clickable'] ) && 'yes' === $settings['eael_product_list_image_clickable'] ? 1 : 0;
        $woo_product_list['button_position_static']         = ! empty( $settings['eael_product_list_content_general_button_position'] ) && 'on-hover' !== $settings['eael_product_list_content_general_button_position'] ? 1 : 0;
        $woo_product_list['content_header_position']        = ! empty( $settings['eael_product_list_content_header_position'] ) ? esc_html( $settings['eael_product_list_content_header_position'] ) : esc_html( 'before-title' );
		$woo_product_list['content_header_direction_rtl']   = ! empty( $settings['eael_product_list_content_header_direction'] ) && 'rtl' === $settings['eael_product_list_content_header_direction'] ? 1 : 0;
		$woo_product_list['title_tag']                      = ! empty( $settings['eael_product_list_content_body_title_tag'] ) ? ClassesHelper::eael_validate_html_tag( $settings['eael_product_list_content_body_title_tag'] ) : 'div';
		$woo_product_list['title_clickable']                = ! empty( $settings['eael_product_list_content_body_title_clickable'] ) && 'yes' === $settings['eael_product_list_content_body_title_clickable'] ? 1 : 0;
		$woo_product_list['excerpt_words_count']            = ! empty( $settings['eael_product_list_content_body_excerpt_words_count'] ) ? intval( $settings['eael_product_list_content_body_excerpt_words_count'] ) : 30;
		$woo_product_list['excerpt_expanison_indicator']    = ! empty( $settings['eael_product_list_content_body_excerpt_expanison_indicator'] ) ? esc_html( $settings['eael_product_list_content_body_excerpt_expanison_indicator'] ) : esc_html('...');
		
        if( 'preset-3' === $woo_product_list['layout'] ){
            $woo_product_list['content_header_position']    = ! empty( $settings['eael_product_list_content_header_position_preset_3'] ) ? esc_html( $settings['eael_product_list_content_header_position_preset_3'] ) : esc_html( 'after-title' );
        }
        
        if( 'preset-2' === $woo_product_list['layout'] || 'preset-3' === $woo_product_list['layout'] ){
            $woo_product_list['total_sold_show']    = ! empty( $settings['eael_woo_product_list_total_sold_preset_2_3_show'] ) && 'yes' === esc_html( $settings['eael_woo_product_list_total_sold_preset_2_3_show'] ) ? 1 : 0;
        }

        return $woo_product_list;
	}

    /**
     * Prepare product query
     * @param $settings
     * @return array
     */
    public function eael_prepare_product_query( $settings ) {
        $args = [
            'post_type'         => 'product',
            'order'             => ! empty( $settings['order'] )  ? sanitize_text_field( $settings['order'] ) : 'desc',
            'post_status'       => ! empty( $settings['eael_product_list_products_status'] ) ? $settings['eael_product_list_products_status'] : [ 'publish', 'pending', 'future' ],
            'posts_per_page'    => ! empty( $settings['eael_woo_product_list_products_count'] )  ? intval( $settings['eael_woo_product_list_products_count'] ) : 4,
            'offset'            => ! empty( $settings['product_offset'] )  ? intval( $settings['product_offset'] ) : 0,
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

        // order by
        if ( '_price' === $settings['orderby'] ) {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = '_price';
        } else if ( '_sku' === $settings['orderby'] ) {
            $args['orderby'] = 'meta_value meta_value_num';
            $args['meta_key'] = '_sku';
        } else {
            $args['orderby'] = ! empty( $settings['orderby'] ) ? sanitize_text_field( $settings['orderby'] ) : 'date';
        }

        // categories
        if ( ! empty( $settings['eael_product_list_categories'] ) && is_array( $settings['eael_product_list_categories'] ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $settings['eael_product_list_categories'],
                'operator' => 'IN',
            ];
        }

        $args['meta_query'] = [
            'relation' => 'AND',
        ];

        // stock settings
        if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
            $args['meta_query'][] = [
                'key' => '_stock_status',
                'value' => 'instock'
            ];
        }

        // filter by
        switch( $settings['eael_product_list_product_filter'] ){
            case 'featured-products':
                $args['tax_query'][] = [
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                ];
                break;

            case 'best-selling-products':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'desc';
                break;
                
            case 'sale-products':
                $args['post__in']  = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
                break;
                
            case 'top-products':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'desc';
                break;
            
            case 'manual':
                $args['post__in'] = ! empty( $settings['eael_product_list_products_in'] ) ? $settings['eael_product_list_products_in'] : [ 0 ];
                break;

            default:
                break;
        }

        return $args;
    }

    protected function render() {
		if ( ! function_exists( 'WC' ) ) {
            return;
        }

        $settings 	= $this->get_settings_for_display();
		$woo_product_list = $this->get_woo_product_list_settings();
        $args = $this->eael_prepare_product_query( $settings );
        
        $widget_id = $this->get_id();
        $settings[ 'eael_widget_id' ] = $widget_id;
        if ( Plugin::$instance->documents->get_current() ) {
            $this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
        }

        $this->add_render_attribute( 'eael-product-list-content-header', [
			'class' => [
				'eael-product-list-content-header',
				$woo_product_list['content_header_direction_rtl'] ? 'eael-direction-rtl' : '',
			],
		] );
        ?>

        <div>
            <?php
            do_action( 'eael/woo-product-list/before-product-loop' );

			$template                       = $this->get_template( $woo_product_list[ 'layout' ] );
            $settings['loadable_file_name'] = $this->get_filename_only( $template );

			if ( file_exists( $template ) ) {
                $query  = new \WP_Query( $args );

                if ( $query->have_posts() ) {
                    $settings['eael_page_id'] = $this->page_id ? $this->page_id : get_the_ID();

                    include( realpath( $template ) );
                } else {
                    _e( '<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite' );
                }
            } else {
				_e( '<p class="eael-no-posts-found">No layout found!</p>', 'essential-addons-for-elementor-lite' );
            }

            do_action( 'eael/woo-product-list/after-product-loop' );
			?>
        </div>

		<?php
    }
}

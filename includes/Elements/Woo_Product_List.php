<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Traits\Helper;

class Woo_Product_List extends Widget_Base
{
    use Helper;

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
        $this->eael_product_list_modal();
        $this->eael_product_list_carousel();

        $this->eael_product_list_container_style();
        $this->eael_product_list_item_style();
        $this->eael_product_list_item_image_style();
        $this->eael_product_list_item_content_style();
        $this->eael_product_list_modal_style();
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

		$this->add_control('eael_woo_product_list_image_show', [
            'label' => esc_html__('Image', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
			'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
			'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

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
            'default'       => 'yes',
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

        $this->add_control('eael_woo_product_list_description_show', [
            'label' => esc_html__('Description', 'essential-addons-for-elementor-lite'),
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

        $this->add_control('eael_woo_product_list_products_count', [
            'label' => __('Products Count', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::NUMBER,
            'default' => 4,
            'min' => 1,
            'max' => 1000,
            'step' => 1,
        ]);

        $this->end_controls_section();
    }

    protected function eael_product_list_image() {
        $this->start_controls_section('eael_section_woo_product_list_image', [
            'label' => esc_html__('Image', 'essential-addons-for-elementor-lite'),
        ]);

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
                'default' => 'static',
                'options' => [
                    'static'        => esc_html__( 'Static', 'essential-addons-for-elementor-lite' ),
                    'on-hover' => esc_html__( 'On Hover', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'eael_product_list_content_header_heading',
            [
                'label' => __('Content Header', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
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
            ]
        );

        $this->add_control(
            'eael_product_list_content_footer_heading',
            [
                'label' => __('Content Header', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->end_controls_section();
    }

    protected function eael_product_list_modal() {
        $this->start_controls_section('eael_section_woo_product_list_modal', [
            'label' => esc_html__('Modal', 'essential-addons-for-elementor-lite'),
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
				'default'	=> '#F4F5F7',
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
				'default'	=> '#ffffff',
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

    protected function eael_product_list_item_image_style() {

	    $this->start_controls_section(
			'eael_section_product_list_item_image_style',
			[
				'label' => esc_html__( 'Image', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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

		$this->end_controls_section();
    }

    protected function eael_product_list_modal_style() {

        $this->start_controls_section(
            'eael_section_product_list_modal_style',
            [
                'label' => esc_html__('Modal', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
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
            'eael_product_list_rating_heading',
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
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating' => 'font-size: {{SIZE}}px !important;',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating::before' => 'font-size: {{SIZE}}px !important;',
                    '{{WRAPPER}} .eael-product-list-wrapper .eael-product-list-item .star-rating span::before' => 'font-size: {{SIZE}}px !important;',
                ],
            ]
        );

        $this->end_controls_section();
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

    protected function get_woo_product_list_settings() {
		$settings 							= $this->get_settings_for_display();
		
		$woo_product_list 					= [];
		$woo_product_list['layout'] 		= ! empty( $settings['eael_dynamic_template_layout'] ) ? $settings['eael_dynamic_template_layout'] : 'preset-1';
		$woo_product_list['posts_per_page'] = ! empty( $settings['eael_woo_product_list_products_count'] ) ? intval( $settings['eael_woo_product_list_products_count'] ) : 4;
		$woo_product_list['rating_show'] = ! empty( $settings['eael_woo_product_list_rating'] ) && 'yes' === $settings['eael_woo_product_list_rating'] ? 1 : 0;
		$woo_product_list['content_header_direction_rtl'] = ! empty( $settings['eael_product_list_content_header_direction'] ) && 'rtl' === $settings['eael_product_list_content_header_direction'] ? 1 : 0;
		
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
            'post_status'       => ! empty( $settings['products_status'] ) ? $settings['products_status'] : [ 'publish', 'pending', 'future' ],
            'posts_per_page'    => ! empty( $settings['posts_per_page'] )  ? intval( $settings['posts_per_page'] ) : 4,
            'order'             => ! empty( $settings['order'] )  ? sanitize_text_field( $settings['order'] ) : 'DESC',
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

        return $args;
    }

    protected function render() {
		if ( ! function_exists( 'WC' ) ) {
            return;
        }

        $settings 	= $this->get_settings_for_display();
		$woo_product_list = $this->get_woo_product_list_settings();
        $args = $this->eael_prepare_product_query( $settings );

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

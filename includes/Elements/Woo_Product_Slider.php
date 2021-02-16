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
//use Essential_Addons_Elementor\Template\Content\Product_Grid as Product_slider_Trait;
use Essential_Addons_Elementor\Traits\Helper;
use Essential_Addons_Elementor\Traits\Woo_Product_Comparable;

class Woo_Product_Slider extends Widget_Base
{
    use Woo_Product_Comparable;
    use Helper;
//    use Product_slider_Trait;

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

    public function get_name()
    {
        return 'woo-product-slider';
    }

    public function get_title()
    {
        return esc_html__('Woo Product Slider', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-post-carousel';
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
            'ea woo product slider',
            'ea woocommerce product slider',
            'woo commerce',
            'ea woo commerce',
            'product gallery',
            'woocommerce slider',
            'gallery',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/woocommerce-product-slider/';
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
                    return $this->simple_add_to_cart_button_text;
                case 'variable':
                    return $this->variable_add_to_cart_button_text;
                default:
                    return $this->default_add_to_cart_button_text;
            }
        }

        return $default;
    }

    protected function eael_get_product_orderby_options()
    {
        return apply_filters('eael/woo-product-slider/orderby-options', [
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
        return apply_filters('eael/woo-product-slider/filterby-options', [
            'recent-products' => esc_html__('Recent Products', 'essential-addons-for-elementor-lite'),
            'featured-products' => esc_html__('Featured Products', 'essential-addons-for-elementor-lite'),
            'best-selling-products' => esc_html__('Best Selling Products', 'essential-addons-for-elementor-lite'),
            'sale-products' => esc_html__('Sale Products', 'essential-addons-for-elementor-lite'),
            'top-products' => esc_html__('Top Rated Products', 'essential-addons-for-elementor-lite'),
        ]);
    }

    protected function _register_controls()
    {
        $this->init_content_wc_notice_controls();
        if (!function_exists('WC')) {
            return;
        }
        // Content Controls
        $this->eael_woo_product_slider_layout();
	    $this->eael_woo_product_slider_settings();
        $this->eael_woo_product_slider_query();
	    $this->eael_product_action_buttons();
        $this->eael_product_badges();
        $this->eael_woo_product_slider_addtocart();

        // Product Compare
        $this->init_content_product_compare_controls();
        $this->init_content_table_settings_controls();

        // Style Controls---------------
        $this->init_style_product_controls();
        $this->init_style_color_typography_controls();
        $this->eael_woo_product_slider_addtocart_style();
        $this->eael_woo_product_slider_buttons_style();

        /**
         * Pagination Style Controls!
         */
        $this->eael_product_view_popup_style();
        $this->eael_woo_product_slider_dots();
        $this->eael_woo_product_slider_arrows();

        // Product Compare Table Style
        $container_class = '.eael-wcpc-modal';
        $table = ".eael-wcpc-modal .eael-wcpc-wrapper table";
        $table_title = ".eael-wcpc-modal .eael-wcpc-wrapper .wcpc-title";
        $table_title_wrap = ".eael-wcpc-modal .eael-wcpc-wrapper .first-th";
        $compare_btn_condition = [
            'eael_product_slider_layout' => 'slider',
        ];
        $this->init_style_compare_button_controls($compare_btn_condition);
        $this->init_style_content_controls(compact('container_class'));
        $this->init_style_table_controls(compact('table', 'table_title', 'table_title_wrap'));
        $this->init_style_close_button_controls();
    }

    protected function eael_woo_product_slider_layout()
    {
        $this->start_controls_section(
            'eael_section_product_slider_layouts',
            [
                'label' => esc_html__('Layouts', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_product_slider_layout',
            [
                'label' => esc_html__('Type', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'slider',
                'options' => [
                    'slider' => esc_html__('Slider', 'essential-addons-for-elementor-lite'),
                    'carousel' => esc_html__('Carousel', 'essential-addons-for-elementor-lite'),
                ]
            ]
        );

	    $this->add_control(
		    'eael_dynamic_template_Layout',
		    [
			    'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SELECT,
			    'default' => 'slider-default',
			    'options' => $this->get_template_list_for_dropdown(),
			    'condition' => [
				    'eael_product_slider_layout' => ['slider'],
			    ],
		    ]
	    );

        $this->end_controls_section();
    }

    protected function eael_woo_product_slider_query()
    {
        $this->start_controls_section('eael_section_product_slider_settings', [
            'label' => esc_html__('Products', 'essential-addons-for-elementor-lite'),
        ]);

        $this->add_control('eael_product_slider_product_filter', [
            'label' => esc_html__('Filter By', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SELECT,
            'default' => 'recent-products',
            'options' => $this->eael_get_product_filterby_options(),
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

        $this->add_control('eael_product_slider_products_count', [
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
        ]);

        $this->add_control('eael_product_slider_categories', [
            'label' => esc_html__('Product Categories', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SELECT2,
            'label_block' => true,
            'multiple' => true,
            'options' => HelperClass::get_terms_list('product_cat', 'slug'),
        ]);

        $this->add_control('eael_product_slider_rating', [
            'label' => esc_html__('Show Product Rating?', 'essential-addons-for-elementor-lite'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control(
            'eael_product_slider_price',
            [
                'label' => esc_html__('Show Product Price?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'eael_product_slider_excerpt',
            [
                'label' => esc_html__('Short Description?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'eael_product_slider_layout' => 'carousel',
                ],
            ]
        );
        $this->add_control(
            'eael_product_slider_excerpt_length',
            [
                'label' => __('Excerpt Words', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'default' => '10',
                'condition' => [
                    'eael_product_slider_excerpt' => 'yes',
                    'eael_product_slider_layout' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'eael_product_slider_excerpt_expanison_indicator',
            [
                'label' => esc_html__('Expansion Indicator', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => false,
                'default' => '...',
	            'condition' => [
		            'eael_product_slider_excerpt' => 'yes',
		            'eael_product_slider_layout' => 'carousel',
	            ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'eael_product_slider_image_size',
                'exclude' => ['custom'],
                'default' => 'medium',
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function eael_woo_product_slider_addtocart()
    {
        $this->start_controls_section(
            'eael_product_slider_add_to_cart_section',
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
            ]
        );

        $this->end_controls_section(); # end of section 'add to cart'
    }

    protected function init_style_product_controls()
    {
        $this->start_controls_section(
            'eael_product_slider_styles',
            [
                'label' => esc_html__('Products', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eael_product_slider_content_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider:not(.list) .woocommerce ul.products li.product' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_slider_background_color',
            [
                'label' => esc_html__('Content Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_peoduct_slider_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('eael_product_slider_tabs');

        $this->start_controls_tab('eael_product_slider_tabs_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_peoduct_slider_border',
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
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_peoduct_slider_shadow',
                'label' => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product',
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab('eael_product_slider_hover_styles', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_slider_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_peoduct_slider_border_border!' => '',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_product_slider_box_shadow_hover',
                'label' => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'eael_peoduct_slider_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product woocommerce-loop-product__link img' => 'border-radius: {{TOP}}px {{RIGHT}}px 0 0;',
                    '{{WRAPPER}} .eael-product-slider.list .woocommerce ul.products li.product .woocommerce-loop-product__link img' => 'border-radius: {{TOP}}px 0 0 {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_slider_image_width',
            [
                'label' => esc_html__('Image Width(%)', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider.list .eael-product-wrap .product-image-wrap' => 'width: {{SIZE}}%;',
                ],
                'condition' => [
                    'eael_product_slider_layout' => 'list',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_slider_details_heading',
            [
                'label' => __('Product Details', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'eael_product_slider_inner_padding',
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
                    '{{WRAPPER}} .eael-product-slider .product-details-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .eael-product-slider.list .eael-product-list-preset-1 .eael-product-wrap .product-details-wrap, {{WRAPPER}} .eael-product-slider.list .eael-product-list-preset-4 .eael-product-wrap .product-details-wrap' => 'padding: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-slider.list .eael-product-list-preset-2 .eael-product-wrap' => 'padding: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-slider.list .eael-product-list-preset-2 .eael-product-wrap .product-details-wrap' => 'padding: 0 0 0 {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-slider.list .eael-product-list-preset-3 .eael-product-wrap .product-details-wrap' => 'padding: 0 0 0 {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_product_slider_layout' => 'list',
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
                    '{{WRAPPER}} .eael-product-slider.list .eael-product-wrap .product-details-wrap' => 'width: {{SIZE}}%;',
                ],
                'condition' => [
                    'eael_product_slider_layout' => 'list',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function init_style_color_typography_controls()
    {

        $this->start_controls_section(
            'eael_section_product_slider_typography',
            [
                'label' => esc_html__('Color &amp; Typography', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_product_slider_product_title_heading',
            [
                'label' => __('Product Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_slider_product_title_color',
            [
                'label' => esc_html__('Product Title Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#272727',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .woocommerce-loop-product__title, {{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .eael-product-title h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_slider_product_title_typography',
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .woocommerce-loop-product__title, {{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .eael-product-title h2',
            ]
        );

        $this->add_control(
            'eael_product_slider_product_price_heading',
            [
                'label' => __('Product Price', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_slider_product_price_color',
            [
                'label' => esc_html__('Product Price Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#272727',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .price, {{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .eael-product-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_slider_product_price_typography',
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .price',
            ]
        );

        $this->add_control(
            'eael_product_slider_product_rating_heading',
            [
                'label' => __('Star Rating', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_slider_product_rating_color',
            [
                'label' => esc_html__('Rating Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2b01e',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce .star-rating::before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider .woocommerce .star-rating span::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_slider_product_rating_typography',
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .star-rating',

            ]
        );

        $this->add_responsive_control(
            'eael_product_slider_product_rating_size',
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
                    '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .star-rating' => 'font-size: {{SIZE}}px!important;',
                ],

            ]
        );

        $this->add_control(
            'eael_product_slider_product_desc_heading',
            [
                'label' => __('Product Description', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'eael_product_slider_layout' => 'list',
                    'eael_product_slider_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_slider_product_desc_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#272727',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .eael-product-excerpt' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_slider_layout' => 'list',
                    'eael_product_slider_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_slider_product_desc_typography',
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .eael-product-excerpt',
                'condition' => [
                    'eael_product_slider_layout' => 'list',
                    'eael_product_slider_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_product_slider_sale_badge_heading',
            [
                'label' => __('Sale Badge', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_slider_sale_badge_color',
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
            'eael_product_slider_sale_badge_background',
            [
                'label' => esc_html__('Sale Badge Background', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff2a13',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .price ins, {{WRAPPER}} .eael-product-slider .woocommerce ul.products li.product .eael-product-price ins' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .woocommerce ul.products li.product .eael-onsale:not(.outofstock).sale-preset-4:after' => 'border-left-color: {{VALUE}}; border-right-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_slider_sale_badge_typography',
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .onsale, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale:not(.outofstock)',
            ]
        );

        // stock out badge
        $this->add_control(
            'eael_product_slider_stock_out_badge_heading',
            [
                'label' => __('Stock Out Badge', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_slider_stock_out_badge_color',
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
            'eael_product_slider_stock_out_badge_background',
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
                'name' => 'eael_product_slider_stock_out_badge_typography',
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge, {{WRAPPER}} .woocommerce ul.products li.product .eael-onsale.outofstock',
            ]
        );

        $this->end_controls_section();
    }

    protected function eael_woo_product_slider_addtocart_style()
    {
// add to cart button
        $this->start_controls_section(
            'eael_section_product_slider_add_to_cart_styles',
            [
                'label' => esc_html__('Add to Cart Button Styles', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_control(
            'eael_product_slider_add_to_cart_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce li.product .button.add_to_cart_button,
                    {{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link,
                    {{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_slider_add_to_cart_radius',
            [
                'label' => __('Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce li.product .button.add_to_cart_button,
                    {{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link,
                    {{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'eael_product_slider_add_to_cart_is_gradient_bg',
            [
                'label' => __('Use Gradient Background', 'essential-addons-for-elementor-lite'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_controls_tabs('eael_product_slider_add_to_cart_style_tabs');

        $this->start_controls_tab('normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_slider_add_to_cart_color',
            [
                'label' => esc_html__('Button Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce li.product .button.add_to_cart_button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_product_slider_add_to_cart_gradient_background',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce li.product .button.add_to_cart_button,
                {{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link,
                {{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart',
                'condition' => [
                    'eael_product_slider_add_to_cart_is_gradient_bg' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'eael_product_slider_add_to_cart_background',
            [
                'label' => esc_html__('Background', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce li.product .button.add_to_cart_button' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_slider_add_to_cart_is_gradient_bg' => ''
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_product_slider_add_to_cart_border',
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce li.product .button.add_to_cart_button, {{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link, {{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_slider_add_to_cart_typography',
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce li.product .button.add_to_cart_button',

            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_product_slider_add_to_cart_hover_styles', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_slider_add_to_cart_hover_color',
            [
                'label' => esc_html__('Button Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce li.product .button.add_to_cart_button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'eael_product_slider_add_to_cart_hover_gradient_background',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce li.product .button.add_to_cart_button:hover,
                {{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover,
                {{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover',
                'condition' => [
                    'eael_product_slider_add_to_cart_is_gradient_bg' => 'yes'
                ]
            ]
        );
        $this->add_control(
            'eael_product_slider_add_to_cart_hover_background',
            [
                'label' => esc_html__('Background', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce li.product .button.add_to_cart_button:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_slider_add_to_cart_is_gradient_bg' => '',
                ],
            ]
        );

        $this->add_control(
            'eael_product_slider_add_to_cart_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .woocommerce li.product .button.add_to_cart_button:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-slider.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover' => 'border-color: {{VALUE}};',
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
                        'icon' => 'fa fa-align-left',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'condition' => [
                    'eael_product_slider_layout!' => 'list',
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
            ]
        );

        $this->add_control(
            'eael_product_slider_quick_view',
            [
                'label' => esc_html__('Show Quick view?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'after',
            ]
        );

	    $this->add_control('show_compare', [
		    'label' => esc_html__('Show Product Compare?', 'essential-addons-for-elementor-lite'),
		    'type' => Controls_Manager::SWITCHER,
	    ]);

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
                    'eael_product_slider_layout' => 'list',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function eael_woo_product_slider_buttons_style()
    {
        $this->start_controls_section(
            'eael_section_product_slider_buttons_styles',
            [
                'label' => esc_html__('Button', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_product_slider_buttons_preset5_background',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8040FF',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .eael-product-wrap .icons-wrap.block-style' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_slider_buttons_icon_size',
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
                    '{{WRAPPER}} .eael-product-slider.list .eael-product-wrap .icons-wrap li a i' => 'font-size: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_product_slider_layout' => 'list',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_slider_buttons_typography',
                'selector' => '{{WRAPPER}} .eael-product-slider .icons-wrap li.add-to-cart a',
            ]
        );

        $this->add_control(
            'eael_product_slider_buttons_preset5_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .icons-wrap.block-style li' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('eael_product_slider_buttons_style_tabs');

        $this->start_controls_tab('eael_product_slider_buttons_style_tabs_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_slider_buttons_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .eael-product-wrap .icons-wrap li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-compare-icon' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_slider_buttons_background',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8040FF',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .eael-product-wrap .icons-wrap li a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_product_slider_buttons_border',
                'selector' => '{{WRAPPER}} .eael-product-slider .woocommerce li.product .button.add_to_cart_button, {{WRAPPER}} .eael-product-slider .eael-product-wrap .icons-wrap li a',
            ]
        );
        $this->add_control(
            'eael_product_slider_buttons_border_radius',
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
                    '{{WRAPPER}} .eael-product-slider .eael-product-wrap .icons-wrap:not(.details-block-style-2) li a' => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-slider .eael-product-wrap .icons-wrap.details-block-style-2 li:only-child a' => 'border-radius: {{SIZE}}px!important;',
                    '{{WRAPPER}} .eael-product-slider .eael-product-wrap .icons-wrap.details-block-style-2 li:first-child a' => 'border-radius: {{SIZE}}px 0 0 {{SIZE}}px;',
                    '{{WRAPPER}} .eael-product-slider .eael-product-wrap .icons-wrap.details-block-style-2 li:last-child a' => 'border-radius: 0 {{SIZE}}px {{SIZE}}px 0;',
                ],
            ]
        );

        $this->add_control(
            'eael_product_slider_buttons_top_spacing',
            [
                'label' => esc_html__('Top Spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .eael-product-wrap .icons-wrap' => 'margin-top: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_product_slider_layout' => 'list',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_product_slider_buttons_hover_styles', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_slider_buttons_hover_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F5EAFF',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .eael-product-wrap .icons-wrap li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_slider_buttons_hover_background',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .eael-product-wrap .icons-wrap li a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_slider_buttons_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-slider .eael-product-wrap .icons-wrap li a:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_product_slider_buttons_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function eael_woo_product_slider_settings()
    {

	    /**
	     * Content Tab: Carousel Settings
	     */

	    $this->start_controls_section(
		    'section_additional_options',
		    [
			    'label' => __('Carousel Settings', 'essential-addons-elementor'),
		    ]
	    );

	    $this->add_control(
		    'carousel_effect',
		    [
			    'label'       => __('Effect', 'essential-addons-elementor'),
			    'description' => __('Sets transition effect', 'essential-addons-elementor'),
			    'type'        => Controls_Manager::SELECT,
			    'default'     => 'slide',
			    'options'     => [
				    'slide'     => __('Slide', 'essential-addons-elementor'),
				    'fade'      => __('Fade', 'essential-addons-elementor'),
				    'cube'      => __('Cube', 'essential-addons-elementor'),
				    'coverflow' => __('Coverflow', 'essential-addons-elementor'),
				    'flip'      => __('Flip', 'essential-addons-elementor'),
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'items',
		    [
			    'label'          => __('Visible Items', 'essential-addons-elementor'),
			    'type'           => Controls_Manager::SLIDER,
			    'default'        => ['size' => 3],
			    'tablet_default' => ['size' => 2],
			    'mobile_default' => ['size' => 1],
			    'range'          => [
				    'px' => [
					    'min'  => 1,
					    'max'  => 10,
					    'step' => 1,
				    ],
			    ],
			    'size_units'     => '',
			    'condition'      => [
				    'carousel_effect' => ['slide', 'coverflow'],
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'margin',
		    [
			    'label'      => __('Items Gap', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::SLIDER,
			    'default'    => ['size' => 10],
			    'range'      => [
				    'px' => [
					    'min'  => 0,
					    'max'  => 100,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => '',
			    'condition'  => [
				    'carousel_effect' => ['slide', 'coverflow'],
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'post_image_height',
		    [
			    'label'      => __('Image Height', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::SLIDER,
			    'default'    => ['size' => 350],
			    'range'      => [
				    'px' => [
					    'min'  => 0,
					    'max'  => 600,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => ['px'],
			    'selectors'  => [
				    '{{WRAPPER}} .eael-entry-thumbnail' => 'height: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'slider_speed',
		    [
			    'label'       => __('Slider Speed', 'essential-addons-elementor'),
			    'description' => __('Duration of transition between slides (in ms)', 'essential-addons-elementor'),
			    'type'        => Controls_Manager::SLIDER,
			    'default'     => ['size' => 400],
			    'range'       => [
				    'px' => [
					    'min'  => 100,
					    'max'  => 3000,
					    'step' => 1,
				    ],
			    ],
			    'size_units'  => '',
		    ]
	    );

	    $this->add_control(
		    'autoplay',
		    [
			    'label'        => __('Autoplay', 'essential-addons-elementor'),
			    'type'         => Controls_Manager::SWITCHER,
			    'default'      => 'yes',
			    'label_on'     => __('Yes', 'essential-addons-elementor'),
			    'label_off'    => __('No', 'essential-addons-elementor'),
			    'return_value' => 'yes',
		    ]
	    );

	    $this->add_control(
		    'autoplay_speed',
		    [
			    'label'      => __('Autoplay Speed', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::SLIDER,
			    'default'    => ['size' => 2000],
			    'range'      => [
				    'px' => [
					    'min'  => 500,
					    'max'  => 5000,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => '',
			    'condition'  => [
				    'autoplay' => 'yes',
			    ],
		    ]
	    );

	    $this->add_control(
		    'pause_on_hover',
		    [
			    'label'        => __('Pause On Hover', 'essential-addons-elementor'),
			    'type'         => Controls_Manager::SWITCHER,
			    'default'      => '',
			    'label_on'     => __('Yes', 'essential-addons-elementor'),
			    'label_off'    => __('No', 'essential-addons-elementor'),
			    'return_value' => 'yes',
			    'condition'    => [
				    'autoplay' => 'yes',
			    ],
		    ]
	    );

	    $this->add_control(
		    'infinite_loop',
		    [
			    'label'        => __('Infinite Loop', 'essential-addons-elementor'),
			    'type'         => Controls_Manager::SWITCHER,
			    'default'      => 'yes',
			    'label_on'     => __('Yes', 'essential-addons-elementor'),
			    'label_off'    => __('No', 'essential-addons-elementor'),
			    'return_value' => 'yes',
		    ]
	    );

	    $this->add_control(
		    'grab_cursor',
		    [
			    'label'        => __('Grab Cursor', 'essential-addons-elementor'),
			    'description'  => __('Shows grab cursor when you hover over the slider', 'essential-addons-elementor'),
			    'type'         => Controls_Manager::SWITCHER,
			    'default'      => '',
			    'label_on'     => __('Show', 'essential-addons-elementor'),
			    'label_off'    => __('Hide', 'essential-addons-elementor'),
			    'return_value' => 'yes',
		    ]
	    );

	    $this->add_control(
		    'navigation_heading',
		    [
			    'label'     => __('Navigation', 'essential-addons-elementor'),
			    'type'      => Controls_Manager::HEADING,
			    'separator' => 'before',
		    ]
	    );

	    $this->add_control(
		    'arrows',
		    [
			    'label'        => __('Arrows', 'essential-addons-elementor'),
			    'type'         => Controls_Manager::SWITCHER,
			    'default'      => 'yes',
			    'label_on'     => __('Yes', 'essential-addons-elementor'),
			    'label_off'    => __('No', 'essential-addons-elementor'),
			    'return_value' => 'yes',
		    ]
	    );

	    $this->add_control(
		    'dots',
		    [
			    'label'        => __('Dots', 'essential-addons-elementor'),
			    'type'         => Controls_Manager::SWITCHER,
			    'default'      => 'yes',
			    'label_on'     => __('Yes', 'essential-addons-elementor'),
			    'label_off'    => __('No', 'essential-addons-elementor'),
			    'return_value' => 'yes',
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
                'selector' => '{{WRAPPER}} .eael-product-popup.woocommerce div.product .product_title',
            ]
        );

        $this->add_control(
            'eael_product_popup_title_color',
            [
                'label' => __('Title Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#252525',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup h1.product_title.entry-title' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .eael-product-popup.woocommerce div.product .price',
            ]
        );

        $this->add_control(
            'eael_product_popup_price_color',
            [
                'label' => __('Price Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0242e4',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce div.product .price' => 'color: {{VALUE}}!important;',
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
                    '{{WRAPPER}} .eael-product-popup.woocommerce div.product .price ins' => 'color: {{VALUE}}!important;',
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
                'selector' => '{{WRAPPER}} .eael-product-popup.woocommerce div.product .woocommerce-product-details__short-description',
            ]
        );

        $this->add_control(
            'eael_product_popup_content_color',
            [
                'label' => __('Content Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#707070',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .eael-product-popup .product_meta a.woocommerce-review-link, {{WRAPPER}} .eael-product-popup .product_meta a' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .eael-product-popup .product_meta a.woocommerce-review-link:hover, {{WRAPPER}} .eael-product-popup .product_meta a:hover' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .eael-product-popup.woocommerce div.product table tbody tr, {{WRAPPER}} .eael-product-popup.woocommerce div.product .product_meta' => 'border-color: {{VALUE}};',
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
                'name' => 'eael_product_popup_sale_typo',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-product-popup .eael-onsale',
            ]
        );
        $this->add_control(
            'eael_product_popup_sale_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup .eael-onsale' => 'color: {{VALUE}}!important;',
                ],
            ]
        );
        $this->add_control(
            'eael_product_popup_sale_bg_color',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup .eael-onsale' => 'background-color: {{VALUE}}!important;',
                ],
            ]
        );

        // Quantity
        $this->add_control(
            'eael_product_popup_quantity',
            [
                'label' => __('Quantity', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_product_popup_quantity_typo',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a',
            ]
        );

        $this->add_control(
            'eael_product_popup_quantity_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_quantity_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_quantity_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity .qty, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > a, {{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity > .button' => 'border-color: {{VALUE}};',
                    // OceanWP
                    '{{WRAPPER}} .eael-product-popup.woocommerce div.product form.cart div.quantity .qty:focus' => 'border-color: {{VALUE}};',
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
                'name' => 'eael_product_popup_cart_button_typo',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-product-popup.woocommerce .button, .eael-product-popup.woocommerce button.button.alt',
            ]
        );

        $this->start_controls_tabs('eael_product_popup_cart_button_style_tabs');

        $this->start_controls_tab('eael_product_popup_cart_button_style_tabs_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_popup_cart_button_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce .button, .eael-product-popup.woocommerce button.button.alt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_cart_button_background',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#8040FF',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce .button, .eael-product-popup.woocommerce button.button.alt' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_product_popup_cart_button_border',
                'selector' => '{{WRAPPER}} .eael-product-popup.woocommerce .button, .eael-product-popup.woocommerce button.button.alt',
            ]
        );
        $this->add_control(
            'eael_product_popup_cart_button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce .button, .eael-product-popup.woocommerce button.button.alt' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('eael_product_popup_cart_button_hover_styles', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_product_popup_cart_button_hover_color',
            [
                'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F5EAFF',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce .button:hover, .eael-product-popup.woocommerce button.button.alt:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_cart_button_hover_background',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#F12DE0',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce .button:hover, .eael-product-popup.woocommerce button.button.alt:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_cart_button_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce .button:hover, .eael-product-popup.woocommerce button.button.alt:hover' => 'border-color: {{VALUE}};',
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
                'name' => 'eael_product_popup_sku_typo',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-product-popup.woocommerce .product_meta',
            ]
        );
        $this->add_control(
            'eael_product_popup_sku_title_color',
            [
                'label' => __('Title Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce .product_meta' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_product_popup_sku_content_color',
            [
                'label' => __('Content Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce .product_meta .sku, .eael-product-popup.woocommerce .product_meta a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_product_popup_sku_hover_color',
            [
                'label' => __('Hover Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup.woocommerce .product_meta a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_close_button_style',
            [
                'label' => __(' Close Button', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'eael_product_popup_close_button_icon_size',
            [
                'label' => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup button.eael-product-popup-close' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_popup_close_button_size',
            [
                'label' => __('Button Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup button.eael-product-popup-close' => 'max-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_close_button_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup button.eael-product-popup-close' => 'color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_close_button_bg',
            [
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup button.eael-product-popup-close' => 'background-color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'eael_product_popup_close_button_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
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
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup button.eael-product-popup-close' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_product_popup_close_button_box_shadow',
                'label' => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-product-popup button.eael-product-popup-close',
            ]
        );

        $this->add_responsive_control(
            'eael_product_popup_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-product-popup .eael-product-popup-details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'eael_product_popup_background',
                'label' => __('Background', 'essential-addons-for-elementor-lite'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-product-popup .eael-product-popup-details',
                'exclude' => [
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

    protected function eael_woo_product_slider_dots(){
	    /**
	     * Style Tab: Dots
	     */
	    $this->start_controls_section(
		    'section_dots_style',
		    [
			    'label'     => __('Dots', 'essential-addons-elementor'),
			    'tab'       => Controls_Manager::TAB_STYLE,
			    'condition' => [
				    'dots' => 'yes',
			    ],
		    ]
	    );

	    $this->add_control(
		    'dots_position',
		    [
			    'label'   => __('Position', 'essential-addons-elementor'),
			    'type'    => Controls_Manager::SELECT,
			    'options' => [
				    'inside'  => __('Inside', 'essential-addons-elementor'),
				    'outside' => __('Outside', 'essential-addons-elementor'),
			    ],
			    'default' => 'outside',
		    ]
	    );

	    $this->add_control(
		    'is_use_dots_custom_width_height',
		    [
			    'label'        => __('Use Custom Width/Height?', 'essential-addons-elementor'),
			    'type'         => \Elementor\Controls_Manager::SWITCHER,
			    'label_on'     => __('Yes', 'essential-addons-elementor'),
			    'label_off'    => __('No', 'essential-addons-elementor'),
			    'return_value' => 'yes',
		    ]
	    );

	    $this->add_responsive_control(
		    'dots_width',
		    [
			    'label'      => __('Width', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::SLIDER,
			    'range'      => [
				    'px' => [
					    'min'  => 2,
					    'max'  => 100,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => '',
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}',
			    ],
			    'condition'  => [
				    'is_use_dots_custom_width_height' => 'yes',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'dots_height',
		    [
			    'label'      => __('Height', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::SLIDER,
			    'range'      => [
				    'px' => [
					    'min'  => 2,
					    'max'  => 100,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => '',
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}',
			    ],
			    'condition'  => [
				    'is_use_dots_custom_width_height' => 'yes',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'dots_size',
		    [
			    'label'      => __('Size', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::SLIDER,
			    'range'      => [
				    'px' => [
					    'min'  => 2,
					    'max'  => 100,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => '',
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
			    ],
			    'condition'  => [
				    'is_use_dots_custom_width_height' => '',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'dots_spacing',
		    [
			    'label'      => __('Spacing', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::SLIDER,
			    'range'      => [
				    'px' => [
					    'min'  => 1,
					    'max'  => 30,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => '',
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );

	    $this->start_controls_tabs('tabs_dots_style');

	    $this->start_controls_tab(
		    'tab_dots_normal',
		    [
			    'label' => __('Normal', 'essential-addons-elementor'),
		    ]
	    );

	    $this->add_control(
		    'dots_color_normal',
		    [
			    'label'     => __('Color', 'essential-addons-elementor'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name'        => 'dots_border_normal',
			    'label'       => __('Border', 'essential-addons-elementor'),
			    'placeholder' => '1px',
			    'default'     => '1px',
			    'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
		    ]
	    );

	    $this->add_control(
		    'dots_border_radius_normal',
		    [
			    'label'      => __('Border Radius', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%'],
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'dots_padding',
		    [
			    'label'              => __('Padding', 'essential-addons-elementor'),
			    'type'               => Controls_Manager::DIMENSIONS,
			    'size_units'         => ['px', 'em', '%'],
			    'allowed_dimensions' => 'vertical',
			    'placeholder'        => [
				    'top'    => '',
				    'right'  => 'auto',
				    'bottom' => '',
				    'left'   => 'auto',
			    ],
			    'selectors'          => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'sub_section_dots_active_mode',
		    [
			    'label'     => __('Dots Active Style', 'essential-addons-elementor'),
			    'type'      => Controls_Manager::HEADING,
			    'separator' => 'before',
		    ]
	    );

	    $this->add_control(
		    'active_dot_color_normal',
		    [
			    'label'     => __('Color', 'essential-addons-elementor'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'active_dots_width',
		    [
			    'label'      => __('Width', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::SLIDER,
			    'range'      => [
				    'px' => [
					    'min'  => 2,
					    'max'  => 100,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => '',
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'active_dots_height',
		    [
			    'label'      => __('Height', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::SLIDER,
			    'range'      => [
				    'px' => [
					    'min'  => 2,
					    'max'  => 100,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => '',
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}}',
			    ],
		    ]
	    );

	    $this->add_control(
		    'active_dots_radius',
		    [
			    'label'      => __('Border Radius', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%'],
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    \Elementor\Group_Control_Box_Shadow::get_type(),
		    [
			    'name'     => 'active_dots_shadow',
			    'label'    => __('Shadow', 'essential-addons-elementor'),
			    'selector' => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active',
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'tab_dots_hover',
		    [
			    'label' => __('Hover', 'essential-addons-elementor'),
		    ]
	    );

	    $this->add_control(
		    'dots_color_hover',
		    [
			    'label'     => __('Color', 'essential-addons-elementor'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'dots_border_color_hover',
		    [
			    'label'     => __('Border Color', 'essential-addons-elementor'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->end_controls_tabs();

	    $this->end_controls_section();
    }

    protected function eael_woo_product_slider_arrows(){
	    /**
	     * Style Tab: Arrows
	     */
	    $this->start_controls_section(
		    'section_arrows_style',
		    [
			    'label'     => __('Arrows', 'essential-addons-elementor'),
			    'tab'       => Controls_Manager::TAB_STYLE,
			    'condition' => [
				    'arrows' => 'yes',
			    ],
		    ]
	    );

	    $this->add_control(
		    'arrow',
		    [
			    'label'       => __('Choose Arrow', 'essential-addons-elementor'),
			    'type'        => Controls_Manager::SELECT,
			    'label_block' => true,
			    'default'     => 'fa fa-angle-right',
			    'options'     => [
				    'fa fa-angle-right'          => __('Angle', 'essential-addons-elementor'),
				    'fa fa-angle-double-right'   => __('Double Angle', 'essential-addons-elementor'),
				    'fa fa-chevron-right'        => __('Chevron', 'essential-addons-elementor'),
				    'fa fa-chevron-circle-right' => __('Chevron Circle', 'essential-addons-elementor'),
				    'fa fa-arrow-right'          => __('Arrow', 'essential-addons-elementor'),
				    'fa fa-long-arrow-right'     => __('Long Arrow', 'essential-addons-elementor'),
				    'fa fa-caret-right'          => __('Caret', 'essential-addons-elementor'),
				    'fa fa-caret-square-o-right' => __('Caret Square', 'essential-addons-elementor'),
				    'fa fa-arrow-circle-right'   => __('Arrow Circle', 'essential-addons-elementor'),
				    'fa fa-arrow-circle-o-right' => __('Arrow Circle O', 'essential-addons-elementor'),
				    'fa fa-toggle-right'         => __('Toggle', 'essential-addons-elementor'),
				    'fa fa-hand-o-right'         => __('Hand', 'essential-addons-elementor'),
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'arrows_size',
		    [
			    'label'      => __('Arrows Size', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::SLIDER,
			    'default'    => ['size' => '22'],
			    'range'      => [
				    'px' => [
					    'min'  => 15,
					    'max'  => 100,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => ['px'],
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'left_arrow_position',
		    [
			    'label'      => __('Align Left Arrow', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::SLIDER,
			    'range'      => [
				    'px' => [
					    'min'  => -100,
					    'max'  => 40,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => ['px'],
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'right_arrow_position',
		    [
			    'label'      => __('Align Right Arrow', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::SLIDER,
			    'range'      => [
				    'px' => [
					    'min'  => -100,
					    'max'  => 40,
					    'step' => 1,
				    ],
			    ],
			    'size_units' => ['px'],
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->start_controls_tabs('tabs_arrows_style');

	    $this->start_controls_tab(
		    'tab_arrows_normal',
		    [
			    'label' => __('Normal', 'essential-addons-elementor'),
		    ]
	    );

	    $this->add_control(
		    'arrows_bg_color_normal',
		    [
			    'label'     => __('Background Color', 'essential-addons-elementor'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'arrows_color_normal',
		    [
			    'label'     => __('Color', 'essential-addons-elementor'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name'        => 'arrows_border_normal',
			    'label'       => __('Border', 'essential-addons-elementor'),
			    'placeholder' => '1px',
			    'default'     => '1px',
			    'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev',
		    ]
	    );

	    $this->add_control(
		    'arrows_border_radius_normal',
		    [
			    'label'      => __('Border Radius', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%'],
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'tab_arrows_hover',
		    [
			    'label' => __('Hover', 'essential-addons-elementor'),
		    ]
	    );

	    $this->add_control(
		    'arrows_bg_color_hover',
		    [
			    'label'     => __('Background Color', 'essential-addons-elementor'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'arrows_color_hover',
		    [
			    'label'     => __('Color', 'essential-addons-elementor'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'arrows_border_color_hover',
		    [
			    'label'     => __('Border Color', 'essential-addons-elementor'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->end_controls_tabs();

	    $this->add_responsive_control(
		    'arrows_padding',
		    [
			    'label'      => __('Padding', 'essential-addons-elementor'),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%'],
			    'selectors'  => [
				    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
			    'separator'  => 'before',
		    ]
	    );

	    $this->end_controls_section();
    }

    protected function render()
    {
        if (!function_exists('WC')) {
            return;
        }
        $settings = $this->get_settings_for_display();
        // normalize for load more fix
        $settings['layout_mode'] = $settings["eael_product_slider_layout"];
        $widget_id = $this->get_id();
        $settings['eael_widget_id'] = $widget_id;
        $args = [
            'post_type' => 'product',
            'posts_per_page' => $settings['eael_product_slider_products_count'] ?: 4,
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

        if (!empty($settings['eael_product_slider_categories'])) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $settings['eael_product_slider_categories'],
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

        if ($settings['eael_product_slider_product_filter'] == 'featured-products') {
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

            if ($settings['eael_product_slider_categories']) {
                $args['tax_query'][] = [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $settings['eael_product_slider_categories'],
                ];
            }
        } else if ($settings['eael_product_slider_product_filter'] == 'best-selling-products') {
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        } else if ($settings['eael_product_slider_product_filter'] == 'sale-products') {
            $args['meta_query'][] = [
                'relation' => 'OR',
                [
                    'key' => '_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'numeric',
                ], [
                    'key' => '_min_variation_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'numeric',
                ],
            ];
        } else if ($settings['eael_product_slider_product_filter'] == 'top-products') {
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }


        $this->is_show_custom_add_to_cart = boolval($settings['show_add_to_cart_custom_text']);
        $this->simple_add_to_cart_button_text = $settings['add_to_cart_simple_product_button_text'];
        $this->variable_add_to_cart_button_text = $settings['add_to_cart_variable_product_button_text'];
        $this->grouped_add_to_cart_button_text = $settings['add_to_cart_grouped_product_button_text'];
        $this->external_add_to_cart_button_text = $settings['add_to_cart_external_product_button_text'];
        $this->default_add_to_cart_button_text = $settings['add_to_cart_default_product_button_text'];

        if (Plugin::$instance->documents->get_current()) {
            $this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
        }
        // render dom
        $this->add_render_attribute('container', [
            'class' => [
	            'swiper-container-wrap',
                'eael-woo-product-slider-container',
	            $settings['eael_dynamic_template_Layout'],
	        ],
            'id' => 'eael-product-slider-'. esc_attr($this->get_id()),
            'data-widget-id' => $widget_id,
//            'data-page-id' => $this->page_id,
//            'data-nonce' => wp_create_nonce('eael_product_grid'),
        ]);

	    if ($settings['dots_position']) {
		    $this->add_render_attribute('eael-woo-product-slider-container', 'class', 'swiper-container-wrap-dots-' . $settings['dots_position']);
	    }

	    $this->add_render_attribute(
		    'eael-woo-product-slider-wrap',
		    [
			    'class'           => [
				    'woocommerce',
				    'swiper-container',
				    'eael-woo-product-slider',
				    'swiper-container-' . esc_attr($this->get_id()),
				    'eael-product-appender-' . esc_attr($this->get_id()),
			    ],
			    'data-pagination' => '.swiper-pagination-' . esc_attr($this->get_id()),
			    'data-arrow-next' => '.swiper-button-next-' . esc_attr($this->get_id()),
			    'data-arrow-prev' => '.swiper-button-prev-' . esc_attr($this->get_id()),
		    ]
	    );

	    if (!empty($settings['items']['size'])) {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-items', $settings['items']['size']);
	    }
	    if (!empty($settings['items_tablet']['size'])) {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-items-tablet', $settings['items_tablet']['size']);
	    }
	    if (!empty($settings['items_mobile']['size'])) {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-items-mobile', $settings['items_mobile']['size']);
	    }
	    if (!empty($settings['margin']['size'])) {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-margin', $settings['margin']['size']);
	    }
	    if (!empty($settings['margin_tablet']['size'])) {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-margin-tablet', $settings['margin_tablet']['size']);
	    }
	    if (!empty($settings['margin_mobile']['size'])) {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-margin-mobile', $settings['margin_mobile']['size']);
	    }
	    if ($settings['carousel_effect']) {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-effect', $settings['carousel_effect']);
	    }
	    if (!empty($settings['slider_speed']['size'])) {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-speed', $settings['slider_speed']['size']);
	    }

	    if ($settings['autoplay'] == 'yes' && !empty($settings['autoplay_speed']['size'])) {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-autoplay', $settings['autoplay_speed']['size']);
	    } else {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-autoplay', '0');
	    }

	    if ($settings['pause_on_hover'] == 'yes') {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-pause-on-hover', 'true');
	    }

	    if ($settings['infinite_loop'] == 'yes') {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-loop', '1');
	    }
	    if ($settings['grab_cursor'] == 'yes') {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-grab-cursor', '1');
	    }
	    if ($settings['arrows'] == 'yes') {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-arrows', '1');
	    }
	    if ($settings['dots'] == 'yes') {
		    $this->add_render_attribute('eael-woo-product-slider-wrap', 'data-dots', '1');
	    }

        add_filter('woocommerce_product_add_to_cart_text', [
            $this,
            'add_to_cart_button_custom_text',
        ]);
        ?>

        <div <?php $this->print_render_attribute_string('container'); ?> >
                <div <?php echo $this->get_render_attribute_string('eael-woo-product-slider-wrap'); ?>>
                    <?php
                    do_action( 'eael_woo_before_product_loop' );
                    $template = $this->get_template($settings['eael_dynamic_template_Layout']);
                    if (file_exists($template)) {
                        $query = new \WP_Query($args);
                        if ($query->have_posts()) {
                            echo '<ul class="swiper-wrapper products">';
                            while ($query->have_posts()) {
                                $query->the_post();
                                include($template);
                            }
                            wp_reset_postdata();
                            echo '</ul>';
                        } else {
                            _e('<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite');
                        }
                    } else {
                        _e('<p class="no-posts-found">No layout found!</p>', 'essential-addons-for-elementor-lite');
                    }

                    ?>
                </div>
            <div class="clearfix"></div>
	        <?php

	        /**
	         * Render Slider Dots!
	         */
	        $this->render_dots();

	        /**
	         * Render Slider Navigations!
	         */
	        $this->render_arrows();
	        ?>
        </div>
        <?php
        remove_filter('woocommerce_product_add_to_cart_text', [
            $this,
            'add_to_cart_button_custom_text',
        ]);
    }

	//changes
	protected function render_dots()
	{
		$settings = $this->get_settings_for_display();

		if ($settings['dots'] == 'yes') { ?>
            <!-- Add Pagination -->
            <div class="swiper-pagination swiper-pagination-<?php echo esc_attr($this->get_id()); ?>"></div>
		<?php }
	}

	/**
	 * Render logo carousel arrows output on the frontend.
	 */
	protected function render_arrows()
	{
		$settings = $this->get_settings_for_display();

		if ($settings['arrows'] == 'yes') { ?>
			<?php
			if ($settings['arrow']) {
				$pa_next_arrow = $settings['arrow'];
				$pa_prev_arrow = str_replace("right", "left", $settings['arrow']);
			} else {
				$pa_next_arrow = 'fa fa-angle-right';
				$pa_prev_arrow = 'fa fa-angle-left';
			}
			?>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button-next-<?php echo esc_attr($this->get_id()); ?>">
                <i class="<?php echo esc_attr($pa_next_arrow); ?>"></i>
            </div>
            <div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr($this->get_id()); ?>">
                <i class="<?php echo esc_attr($pa_prev_arrow); ?>"></i>
            </div>
			<?php
		}
	}
}
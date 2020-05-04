<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Widget_Base as Widget_Base;

class Product_Grid extends Widget_Base {
    use \Essential_Addons_Elementor\Traits\Helper;
    use \Essential_Addons_Elementor\Template\Content\Product_Grid;

    public function get_name() {
        return 'eicon-woocommerce';
    }

    public function get_title() {
        return esc_html__( 'Product Grid', 'essential-addons-for-elementor-lite' );
    }

    public function get_icon() {
        return 'eaicon-product-grid';
    }

    public function get_categories() {
        return ['essential-addons-elementor'];
    }

    public function get_keywords() {
        return [
            'woo',
            'woocommerce',
            'ea woocommerce',
            'ea woo product grid',
            'ea woocommerce product grid',
            'woo commerce',
            'ea woo commerce',
            'product gallery',
            'woocommerce grid',
            'gallery',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url() {
        return 'https://essential-addons.com/elementor/docs/woocommerce-product-grid/';
    }

    public function get_style_depends() {
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
        ];
    }

    public function get_script_depends() {
        return [
            'font-awesome-4-shim',
        ];
    }

    protected function _register_controls() {

        // Content Controls
        $this->start_controls_section(
            'eael_section_product_grid_settings',
            [
                'label' => esc_html__( 'Product Settings', 'essential-addons-for-elementor-lite' ),
            ]
        );

        if ( !apply_filters( 'eael/active_plugins', 'woocommerce/woocommerce.php' ) ) {
            $this->add_control(
                'ea_product_grid_woo_required',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.', 'essential-addons-for-elementor-lite' ),
                    'content_classes' => 'eael-warning',
                ]
            );
        }

        $this->add_control(
            'eael_product_grid_product_filter',
            [
                'label'   => esc_html__( 'Filter By', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'recent-products',
                'options' => [
                    'recent-products'       => esc_html__( 'Recent Products', 'essential-addons-for-elementor-lite' ),
                    'featured-products'     => esc_html__( 'Featured Products', 'essential-addons-for-elementor-lite' ),
                    'best-selling-products' => esc_html__( 'Best Selling Products', 'essential-addons-for-elementor-lite' ),
                    'sale-products'         => esc_html__( 'Sale Products', 'essential-addons-for-elementor-lite' ),
                    'top-products'          => esc_html__( 'Top Rated Products', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_grid_column',
            [
                'label'        => esc_html__( 'Columns', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => '4',
                'options'      => [
                    '1' => esc_html__( '1', 'essential-addons-for-elementor-lite' ),
                    '2' => esc_html__( '2', 'essential-addons-for-elementor-lite' ),
                    '3' => esc_html__( '3', 'essential-addons-for-elementor-lite' ),
                    '4' => esc_html__( '4', 'essential-addons-for-elementor-lite' ),
                    '5' => esc_html__( '5', 'essential-addons-for-elementor-lite' ),
                    '6' => esc_html__( '6', 'essential-addons-for-elementor-lite' ),
                ],
                'toggle'       => true,
                'prefix_class' => 'eael-product-grid-column%s-',
            ]
        );

        $this->add_control(
            'eael_product_grid_products_count',
            [
                'label'   => __( 'Products Count', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 4,
                'min'     => 1,
                'max'     => 1000,
                'step'    => 1,
            ]
        );

        $this->add_control(
            'product_offset',
            [
                'label'   => __( 'Offset', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 0,
            ]
        );

        $this->add_control(
            'eael_product_grid_categories',
            [
                'label'       => esc_html__( 'Product Categories', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple'    => true,
                'options'     => $this->eael_woocommerce_product_categories(),
            ]
        );

        $this->add_control(
            'eael_product_grid_style_preset',
            [
                'label'   => esc_html__( 'Style Preset', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'eael-product-simple',
                'options' => [
                    'eael-product-default' => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
                    'eael-product-simple'  => esc_html__( 'Simple Style', 'essential-addons-for-elementor-lite' ),
                    'eael-product-reveal'  => esc_html__( 'Reveal Style', 'essential-addons-for-elementor-lite' ),
                    'eael-product-overlay' => esc_html__( 'Overlay Style', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_rating',
            [
                'label'        => esc_html__( 'Show Product Rating?', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------
         *  Section => Load More
         * -------------------------------
         */
        $this->start_controls_section(
            'eael_product_grid_load_more_section',
            [
                'label' => esc_html__( 'Load More', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'show_load_more',
            [
                'label'        => __( 'Show Load More', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'true',
                'default'      => '',
            ]
        );

        $this->add_control(
            'show_load_more_text',
            [
                'label'       => esc_html__( 'Label Text', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => false,
                'default'     => esc_html__( 'Load More', 'essential-addons-for-elementor-lite' ),
                'condition'   => [
                    'show_load_more' => 'true',
                ],
            ]
        );

        $this->end_controls_section(); # end of section 'Load More'

        $this->start_controls_section(
            'eael_product_grid_styles',
            [
                'label' => esc_html__( 'Products Styles', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_product_grid_background_color',
            [
                'label'     => esc_html__( 'Content Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'           => 'eael_peoduct_grid_border',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width'  => [
                        'default' => [
                            'top'      => '1',
                            'right'    => '1',
                            'bottom'   => '1',
                            'left'     => '1',
                            'isLinked' => false,
                        ],
                    ],
                    'color'  => [
                        'default' => '#eee',
                    ],
                ],
                'selector'       => '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product',
                'condition'      => [
                    'eael_product_grid_style_preset' => ['eael-product-default', 'eael-product-simple', 'eael-product-overlay'],
                ],
            ]
        );

        $this->add_control(
            'eael_peoduct_grid_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_product_grid_typography',
            [
                'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_product_grid_product_title_heading',
            [
                'label' => __( 'Product Title', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_grid_product_title_color',
            [
                'label'     => esc_html__( 'Product Title Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#272727',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .woocommerce-loop-product__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_grid_product_title_typography',
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .woocommerce-loop-product__title',
            ]
        );

        $this->add_control(
            'eael_product_grid_product_price_heading',
            [
                'label' => __( 'Product Price', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_grid_product_price_color',
            [
                'label'     => esc_html__( 'Product Price Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#272727',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_grid_product_price_typography',
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .price',
            ]
        );

        $this->add_control(
            'eael_product_grid_product_rating_heading',
            [
                'label' => __( 'Star Rating', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_grid_product_rating_color',
            [
                'label'     => esc_html__( 'Rating Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f2b01e',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce .star-rating::before'      => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid .woocommerce .star-rating span::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_grid_product_rating_typography',
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .star-rating',
            ]
        );

        $this->add_control(
            'eael_product_grid_sale_badge_heading',
            [
                'label' => __( 'Sale Badge', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_grid_sale_badge_color',
            [
                'label'     => esc_html__( 'Sale Badge Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .onsale' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_sale_badge_background',
            [
                'label'     => esc_html__( 'Sale Badge Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ff2a13',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .onsale'                       => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid .woocommerce ul.products li.product .price ins' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_grid_sale_badge_typography',
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .onsale',
            ]
        );
        // stock out badge
        $this->add_control(
            'eael_product_grid_stock_out_badge_heading',
            [
                'label' => __( 'Stock Out Badge', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_product_grid_stock_out_badge_color',
            [
                'label'     => esc_html__( 'Stock Out Badge Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_stock_out_badge_background',
            [
                'label'     => esc_html__( 'Stock Out Badge Background', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ff2a13',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_grid_stock_out_badge_typography',
                'selector' => '{{WRAPPER}} .woocommerce ul.products li.product .outofstock-badge',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_product_grid_add_to_cart_styles',
            [
                'label' => esc_html__( 'Add to Cart Button Styles', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'eael_product_grid_add_to_cart_style_tabs' );

        $this->start_controls_tab( 'normal', ['label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' )] );

        $this->add_control(
            'eael_product_grid_add_to_cart_color',
            [
                'label'     => esc_html__( 'Button Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button'                               => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_add_to_cart_background',
            [
                'label'     => esc_html__( 'Button Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button'                               => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link'  => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_product_grid_add_to_cart_border',
                'selector' => '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button, {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link, {{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_product_grid_add_to_cart_typography',
                'selector'  => '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button',
                'condition' => [
                    'eael_product_grid_style_preset' => ['eael-product-default', 'eael-product-simple'],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'eael_product_grid_add_to_cart_hover_styles', ['label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' )] );

        $this->add_control(
            'eael_product_grid_add_to_cart_hover_color',
            [
                'label'     => esc_html__( 'Button Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button:hover'                               => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_add_to_cart_hover_background',
            [
                'label'     => esc_html__( 'Button Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button:hover'                               => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover'  => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_grid_add_to_cart_hover_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-product-grid .woocommerce li.product .button.add_to_cart_button:hover'                               => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .product-link:hover'  => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-product-grid.eael-product-overlay .woocommerce ul.products li.product .overlay .added_to_cart:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Load More Button Style Controls!
         */
        $this->eael_load_more_button_style();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( !apply_filters( 'eael/active_plugins', 'woocommerce/woocommerce.php' ) ) {
            return;
        }

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $settings['eael_product_grid_products_count'] ?: 4,
            'order'          => 'DESC',
            'offset'         => $settings['product_offset'],
        ];

        if ( !empty( $settings['eael_product_grid_categories'] ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $settings['eael_product_grid_categories'],
                    'operator' => 'IN',
                ],
            ];
        }

        if ( $settings['eael_product_grid_product_filter'] == 'featured-products' ) {
            $args['tax_query'] = [
                'relation' => 'AND',
                [
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                ],
            ];

            if ( $settings['eael_product_grid_categories'] ) {
                $args['tax_query'][] = [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $settings['eael_product_grid_categories'],
                ];
            }

        } else if ( $settings['eael_product_grid_product_filter'] == 'best-selling-products' ) {
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        } else if ( $settings['eael_product_grid_product_filter'] == 'sale-products' ) {
            $args['meta_query'] = [
                'relation' => 'OR',
                [
                    'key'     => '_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'numeric',
                ], [
                    'key'     => '_min_variation_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'numeric',
                ],
            ];
        } else if ( $settings['eael_product_grid_product_filter'] == 'top-products' ) {
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }

        $settings = [
            'eael_product_grid_style_preset' => $settings['eael_product_grid_style_preset'],
            'eael_product_grid_rating'       => $settings['eael_product_grid_rating'],
            'eael_product_grid_column'       => $settings['eael_product_grid_column'],
            'show_load_more'                 => $settings['show_load_more'],
            'show_load_more_text'            => $settings['show_load_more_text'],
        ];

        $html = '<div class="eael-product-grid ' . $settings['eael_product_grid_style_preset'] . '">';
        $html .= '<div class="woocommerce">';

        $html .= '<ul class="products">
                    ' . self::render_template_( $args, $settings ) . '
                </ul>';

        if ( 'true' == $settings['show_load_more'] ) {
            if ( $args['posts_per_page'] != '-1' ) {
                $html .= '<div class="eael-load-more-button-wrap">
                            <button class="eael-load-more-button" id="eael-load-more-btn-' . $this->get_id() . '" data-widget="' . $this->get_id() . '" data-class="' . get_class( $this ) . '" data-args="' . http_build_query( $args ) . '" data-settings="' . http_build_query( $settings ) . '" data-layout="masonry" data-page="1">
                                <div class="eael-btn-loader button__loader"></div>
                                <span>' . esc_html__( $settings['show_load_more_text'], 'essential-addons-for-elementor-lite' ) . '</span>
                            </button>
                        </div>';
            }
        }

        $html .= '</div>
        </div>';

        echo $html;
    }

}

<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;
use \Elementor\Icons_Manager;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;
use Essential_Addons_Elementor\Traits\Helper;

class Woo_Cross_Sells extends Widget_Base {
	use Helper;

	public function get_name() {
		return 'eael-woo-cross-sells';
	}

	public function get_title() {
		return esc_html__( 'Woo Cross Sells', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-woo-cross-sells';
	}

	public function get_style_depends() {
		return [
			'font-awesome-5-all',
			'font-awesome-4-shim',
		];
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @access public
	 *
	 */
	public function get_keywords() {
		return [
			'cart',
			'woo cart',
			'cross sells',
			'woo cart cross sells',
			'ea cross sells',
			'woocommerce',
			'woocommerce cross sells',
			'ea',
			'essential addons',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/woo-cross-sells/';
	}

	protected function register_controls() {
		if ( ! class_exists( 'woocommerce' ) ) {
			$this->start_controls_section(
				'eael_global_warning',
				[
					'label' => __( 'Warning!', 'essential-addons-for-elementor-lite' ),
				]
			);

			$this->add_control(
				'eael_global_warning_text',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.',
						'essential-addons-for-elementor-lite' ),
					'content_classes' => 'eael-warning',
				]
			);

			$this->end_controls_section();

			return;
		}

		/**
		 * General Settings
		 */
		$this->start_controls_section(
			'ea_section_woo_cross_sells_general_settings',
			[
				'label' => esc_html__( 'General Settings', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_dynamic_template_layout',
			[
				'label'   => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => $this->get_template_list_for_dropdown( true ),
			]
		);

		$this->add_responsive_control(
			'eael_cross_sales_column',
			[
				'label'           => esc_html__( 'Columns', 'essential-addons-for-elementor-lite' ),
				'type'            => Controls_Manager::SELECT,
				'default'         => '4',
				'desktop_default' => '4',
				'tablet_default'  => '3',
				'mobile_default'  => '2',
				'options'         => [
					'1' => esc_html__( '1', 'essential-addons-for-elementor-lite' ),
					'2' => esc_html__( '2', 'essential-addons-for-elementor-lite' ),
					'3' => esc_html__( '3', 'essential-addons-for-elementor-lite' ),
					'4' => esc_html__( '4', 'essential-addons-for-elementor-lite' ),
					'5' => esc_html__( '5', 'essential-addons-for-elementor-lite' ),
					'6' => esc_html__( '6', 'essential-addons-for-elementor-lite' ),
				],
				'prefix_class'    => 'eael-cross-sales-column%s-',
				'condition'       => [
					'eael_dynamic_template_layout' => [ 'style-1', 'style-2' ],
				],
				'selectors'       => [
					'{{WRAPPER}} .eael-cs-products-container' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'        => 'eael_cross_sales_image_size',
				'exclude'     => [ 'custom' ],
				'default'     => 'medium',
				'label_block' => true,
			]
		);

		$this->add_control( 'orderby', [
			'label'   => __( 'Order By', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'none'       => __( 'None', 'essential-addons-for-elementor-lite' ),
				'title'      => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'id'         => __( 'ID', 'essential-addons-for-elementor-lite' ),
				'date'       => __( 'Date', 'essential-addons-for-elementor-lite' ),
				'modified'   => __( 'Modified', 'essential-addons-for-elementor-lite' ),
				'menu_order' => __( 'Menu Order', 'essential-addons-for-elementor-lite' ),
				'price'      => __( 'Price', 'essential-addons-for-elementor-lite' ),
			],
			'default' => 'none',

		] );

		$this->add_control( 'order', [
			'label'   => __( 'Order', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'asc'  => __( 'Ascending', 'essential-addons-for-elementor-lite' ),
				'desc' => __( 'Descending', 'essential-addons-for-elementor-lite' ),
			],
			'default' => 'desc',
		] );

		$this->add_control( 'products_count', [
			'label'   => __( 'Products Count', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::NUMBER,
			'default' => 4,
			'min'     => 1,
			'max'     => 100,
			'step'    => 1,
		] );

		$this->add_control(
			'eael_cross_sales_custom_size_img',
			[
				'label'        => esc_html__( 'Custom Image Area?', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'eael_cross_sales_img_render_type',
			[
				'label'     => esc_html__( 'Image Render Type', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fill',
				'options'   => [
					'contain' => esc_html__( 'Contain', 'essential-addons-for-elementor-lite' ),
					'fill'    => esc_html__( 'Stretched', 'essential-addons-for-elementor-lite' ),
					'cover'   => esc_html__( 'Cropped', 'essential-addons-for-elementor-lite' ),
				],
				'selectors' => [
					'{{WRAPPER}} .eael-cs-product-image img' => 'height: 100%; width: 100%; object-fit: {{VALUE}};',
				],
				'condition' => [
					'eael_cross_sales_custom_size_img' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_cross_sales_custom_height',
			[
				'label'      => __( 'Height', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'size' => 100,
					'unit' => '%',
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-cs-products-container .eael-cs-product-image'         => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-cs-products-container.style-2 .eael-cs-product-image' => 'max-height: calc(100% - 78px);',
				],
				'condition'  => [
					'eael_cross_sales_custom_size_img' => 'yes',
				]
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style General Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_cross_sells_thumbnail_style',
			[
				'label' => esc_html__( 'Thumbnail Area', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'eael_woo_cross_sells_thumbnail_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-cs-product-image',
			]
		);

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "eael_woo_cross_sells_thumnbail_border",
			'selector' => '{{WRAPPER}} .eael-cs-product-image',
		] );

		$this->add_control( "eael_woo_cross_sells_thumnbail_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				'{{WRAPPER}} .eael-cs-product-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		$this->start_controls_section(
			'ea_section_woo_cross_sells_product_details_style',
			[
				'label' => esc_html__( 'Product Details', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'eael_woo_cross_sells_product_details_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-cs-product-info',
			]
		);

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "eael_woo_cross_sells_product_details_border",
			'selector' => '{{WRAPPER}} .eael-cs-product-info',
		] );

		$this->add_control( "eael_woo_cross_sells_product_details_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				'{{WRAPPER}} .eael-cs-product-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control(
			'eael_woo_cross_sells_product_details_divider_color',
			[
				'label'     => esc_html__( 'Divider Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-cs-products-container.style-1 .eael-cs-single-product .eael-cs-product-info .eael-cs-product-buttons'        => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .eael-cs-products-container.style-1 .eael-cs-single-product .eael-cs-product-info .eael-cs-product-buttons::after' => 'background: {{VALUE}};',
				],
				'condition' => [
					'eael_dynamic_template_layout' => 'style-1'
				]
			]
		);

		$this->add_control(
			'eael_woo_cross_sells_title_heading',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_woo_cross_sells_title_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-cs-product-info .eael-cs-product-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_woo_cross_sells_title_typography',
				'selector' => '{{WRAPPER}} .eael-cs-products-container .eael-cs-product-info .eael-cs-product-title',
			]
		);

		$this->add_control(
			'eael_woo_cross_sells_price_heading',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Price', 'essential-addons-for-elementor-lite' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_woo_cross_sells_price_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-cs-product-info .eael-cs-product-price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_woo_cross_sells_price_typography',
				'selector' => '{{WRAPPER}} .eael-cs-products-container .eael-cs-product-info .eael-cs-product-price',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ea_section_woo_cross_sells_button_style',
			[
				'label' => esc_html__( 'Buttons', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'eael_woo_cross_sells_style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_woo_cross_sells_button_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a,
				{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'eael_woo_cross_sells_button_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_woo_cross_sells_button_typography',
				'selector' => '{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a,
				{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a i',
			]
		);

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "eael_woo_cross_sells_button_border",
			'selector' => '{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a',
		] );

		$this->add_control( "eael_woo_cross_sells_button_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				'{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_woo_cross_sells_button_box_shadow',
				'selector' => '{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_woo_cross_sells_button_color_hover',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a:hover,
				{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a:hover i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'eael_woo_cross_sells_button_bg_hover',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a:hover',
			]
		);

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "eael_woo_cross_sells_button_border_hover",
			'selector' => '{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a:hover',
		] );

		$this->add_control( "eael_woo_cross_sells_button_border_radius_hover", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				'{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_woo_cross_sells_button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .eael-cs-products-container .eael-cs-single-product .eael-cs-product-buttons a:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! class_exists( 'woocommerce' ) ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$orderby  = $settings['orderby'];
		$order    = $settings['order'];
		$limit    = $settings['products_count'];

		// Handle product query.
		$cross_sells = array_filter( array_map( 'wc_get_product', WC()->cart->get_cross_sells() ), 'wc_products_array_filter_visible' );
		$cross_sells = wc_products_array_orderby( $cross_sells, $orderby, $order );
		$cross_sells = $limit > 0 ? array_slice( $cross_sells, 0, $limit ) : $cross_sells;

		$this->add_render_attribute( 'container', [
			'class' => [
				'eael-cs-products-container',
				$settings['eael_dynamic_template_layout']
			]
		] );

		$image_size = $settings['eael_cross_sales_image_size_size'];
		$template   = $this->get_template( $settings['eael_dynamic_template_layout'] ); ?>
        <div <?php $this->print_render_attribute_string( 'container' ); ?>>
			<?php
			if ( file_exists( $template ) ) {
				foreach ( $cross_sells as $cs_product ) {
					$is_purchasable = $cs_product->is_purchasable();
					include( $template );
				}
			} else {
				_e( '<p class="eael-no-layout-found">No layout found!</p>', 'essential-addons-for-elementor-lite' );
			} ?>
        </div>
		<?php
	}
}
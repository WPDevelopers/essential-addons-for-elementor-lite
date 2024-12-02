<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
   exit;
}

use Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

class Woo_Add_To_Cart extends Widget_Base {
   public function get_name() {
		return 'eael-woo-add-to-cart';
	}

	public function get_title() {
		return esc_html__( 'Woo Add To Cart', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-add-to-cart';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor', 'woocommerce-elements' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'cart', 'product', 'button', 'add to cart', 'ea cart', 'ea add to cart', 'ea product' ];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/eael-product-add-to-cart/';
	}

	protected function register_controls() {

		//
		$this->eael_product_add_to_cart_content();

        // Start Button
		$this->start_controls_section(
			'eael_add_to_cart_button',
			[
				'label' => esc_html__( 'Button', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_responsive_control(
			'eael_add_to_cart_align',
			[
				'label'   => esc_html__( 'Button Alignment', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'eael-add-to-cart--align-',
			]
		);

        $this->add_responsive_control(
			'width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'range'      => [
					'px' => [
						'max' => 1000,
					],
					'%' => [
						'max' => 100,
					],
					'rem' => [
						'max' => 100,
					],
					'em' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_add_to_cart_text_align',
			[
				'label'   => esc_html__( 'Text Alignment', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button' => 'justify-content: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_add_to_cart_button_typography',
				'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'eael_add_to_cart_button_border',
				'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button',
				'exclude'  => [ 'color' ],
			]
		);

        $this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->start_controls_tabs( 'eael_add_to_cart_button_style_tabs' );

		$this->start_controls_tab( 'eael_add_to_cart_button_style_normal',
			[
				'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_add_to_cart_button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_cart_button_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_cart_button_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_add_to_cart_button_style_hover',
			[
				'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_add_to_cart_button_text_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_cart_button_bg_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_cart_button_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_cart_button_transition',
			[
				'label'   => esc_html__( 'Transition Duration (s)', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.2,
				],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button' => 'transition: all {{SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'eael_cart_button_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'vw', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		// End Button

         // Start Quantity
		$this->start_controls_section(
			'eael_add_to_cart_quantity_section',
			[
				'label' => esc_html__( 'Quantity', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'eael_add_to_cart_qt_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'range'      => [
					'px' => [
						'max' => 100,
					],
					'%' => [
						'max' => 100,
					],
					'rem' => [
						'max' => 100,
					],
					'em' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_add_to_cart_qt_typo',
				'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .quantity .qty',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'                 => 'eael_add_to_cart_qt_border',
				'exclude'              => [ 'color' ],
                'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .quantity .qty',
			]
		);

        $this->add_control(
			'eael_add_to_cart_qt_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .quantity .qty' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'eael_add_to_cart_qt_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .quantity .qty' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->start_controls_tabs( 'eael_add_to_cart_qt_style_tabs' );

        $this->start_controls_tab( 'eael_add_to_cart_qt_style_normal',
			[
				'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_add_to_cart_qt_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .quantity .qty' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_cart_qt_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .quantity .qty' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_cart_qt_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .quantity .qty' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

        $this->start_controls_tab( 'eael_add_to_cart_qt_style_focus',
			[
				'label' => esc_html__( 'Focus', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_add_to_cart_qt_text_color_focus',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .quantity .qty:focus' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_cart_qt_bg_color_focus',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .quantity .qty:focus' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_cart_qt_border_color_focus',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .quantity .qty:focus' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_cart_qt_transition',
			[
				'label'   => esc_html__( 'Transition Duration (s)', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.2,
				],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .quantity .qty' => 'transition: all {{SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();

        $this->end_controls_tabs();

		$this->end_controls_section();

        //Start Variations
      $this->start_controls_section(
			'eael_add_to_cart_variations_style',
			[
				'label' => esc_html__( 'Variations', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'eael_add_to_variations_width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'vw', 'custom' ],
				'default'    => [
					'unit' => '%',
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart .variations' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

        $this->add_control(
			'eael_add_to_variations_spacing',
			[
				'label'      => esc_html__( 'Spacing Bottom', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart .variations' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_variations_space_between',
			[
				'label'      => esc_html__( 'Space Between', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'range'      => [
					'px' => [
						'max' => 100,
					],
                    '%' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations tr th, 
                    .woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations tr td' => 'padding-top: calc( {{SIZE}}{{UNIT}}/2 ); padding-bottom: calc( {{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

        $this->add_control(
			'eael_add_to_cart_variations_label_style',
			[
				'label'     => esc_html__( 'Label', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_add_to_cart_variations_label_color_focus',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations label' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_add_to_cart_variations_label_typography',
				'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations label',
			]
		);

        $this->add_control(
			'eael_add_to_cart_variations_select_style',
			[
				'label'     => esc_html__( 'Select field', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'eael_add_to_cart_variations_select_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations td.value select' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'eael_add_to_cart_variations_select_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations td.value select, 
                    .woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations td.value:before' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'eael_add_to_cart_variations_select_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations td.value select, 
                    .woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations td.value:before' => 'border: 1px solid {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'variations_select_typography',
				'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations td.value select, 
                .woocommerce div.product.elementor{{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations td.value:before',
			]
		);

		$this->add_control(
			'variations_select_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations td.value select, 
                    .woocommerce {{WRAPPER}} .eael-single-product-add-to-cart form.cart table.variations td.value:before' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
		// End Variations

		//Icon
		$this->eael_add_to_cart_icon_style();
	}

	protected function eael_add_to_cart_icon_style() {
		$this->start_controls_section(
			'eael_add_to_cart_icon_style',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

      $this->add_control(
			'eael_add_to_icon_width',
			[
				'label'      => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => '18',
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .single_add_to_cart_button svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_icon_gap',
			[
				'label'      => esc_html__( 'Gap', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => '10',
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .single_add_to_cart_button' => 'gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_icon_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .single_add_to_cart_button svg path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_add_to_icon_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .single_add_to_cart_button svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function eael_product_add_to_cart_content() {
		$this->start_controls_section(
			'section_product',
			[
				'label' => esc_html__( 'General', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'add_to_cart_layout',
			[
				'label'   => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'row',
				'options' => [
					'row'    => esc_html__( 'Inline', 'essential-addons-for-elementor-lite' ),
					'column' => esc_html__( 'Stacked', 'essential-addons-for-elementor-lite' ),
				],
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-add-to-cart .elementor-add-to-cart.elementor-product-simple .cart' => 'flex-direction: {{VALUE}};',
				],
				'prefix_class' => 'eael-add-to-cart--layout-',
			]
		);

		$this->add_control(
			'add_to_cart_show_quantity',
			[
				'label'        => esc_html__( 'Show Quantity', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'description'  => esc_html__( 'Please note that switching on this option will disable some of the design controls.', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'add_to_cart_text',
			[
				'label'   => esc_html__( 'Button Text', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Add to cart', 'essential-addons-for-elementor-lite' ),
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'add_to_cart_icon_show',
			[
				'label'        => esc_html__( 'Show Icon', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'add_to_cart_icon',
			[
				'label'   => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-cart-plus',
					'library' => 'fa-solid',
				],
				'condition' => [
					'add_to_cart_icon_show' => 'yes'
				]
			]
		);

		$this->end_controls_section();
	}

	public function eael_add_to_cart_text_single() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' == $settings['add_to_cart_icon_show'] ) {
			\Elementor\Icons_Manager::render_icon( $settings['add_to_cart_icon'], [ 'aria-hidden' => 'true' ] );
		}
		if ( ! empty( $settings['add_to_cart_text'] ) ) {
			return $settings['add_to_cart_text'];
		}
	}

	public function eael_add_to_cart_icon( $settings ) {
		echo '<span class="cart-icon">';
		if ( 'yes' == $settings['add_to_cart_icon_show'] ) {
			return \Elementor\Icons_Manager::render_icon( $settings['add_to_cart_icon'], [ 'aria-hidden' => 'true' ] );
		}
		echo '<span>';
	}

	public function eael_show_quantity_fields( $return, $product ) {
		return true;
	}	 

	protected function render() {
      global $product;

      $product = Helper::get_product();

		$settings = $this->get_settings_for_display();

		if ( ! $settings['add_to_cart_show_quantity'] ) {
			add_filter( 'woocommerce_is_sold_individually', [ $this, 'eael_show_quantity_fields' ], 10, 2 );
		}
      ?>
      <div class="eael-single-product-add-to-cart">
			<?php 
			if( \Elementor\Plugin::$instance->editor->is_edit_mode() || get_post_type( get_the_ID() ) === 'templately_library' ) {
				?>
				<div class="custom-add-to-cart-wrapper">
					<?php if( 'yes' === $settings['add_to_cart_show_quantity'] ) {
						?>
						<input type="number" class="quantity-input" value="1" min="1" />
						<?php
					} ?>
					<button class="custom-add-to-cart">
						<?php $this->eael_add_to_cart_icon( $settings ); ?>
						<span class="button-text"><?php echo Helper::eael_wp_kses( $settings['add_to_cart_text'] ); ?></span>
					</button>
				</div>
				<?php
			} else {
				if ( ! $product ) {
					return;
				}
				?>
				<div class="elementor-add-to-cart elementor-product-<?php echo esc_attr( $product->get_type() ); ?>">
               <?php 
					add_filter( 'woocommerce_product_single_add_to_cart_text', [ $this, 'eael_add_to_cart_text_single'] ); 
					woocommerce_template_single_add_to_cart();
					?>
            </div>
				<?php
			}
			?>
            
      </div>
      <?php
	}
}
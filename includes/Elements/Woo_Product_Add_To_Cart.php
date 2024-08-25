<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;

class Woo_Product_Add_To_Cart extends Widget_Base {
    public function get_name() {
		return 'eael-woo-product-add-to-cart';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Add To Cart', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eicon-elementor-circle';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'cart', 'product' ];
	}

	protected function register_controls() {

		// Style Tab Start
		$this->start_controls_section(
			'eael_add_to_cart_title_style',
			[
				'label' => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'eael_add_to_cart_layout',
			[
				'label'   => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					''        => esc_html__( 'Inline', 'essential-addons-for-elementor-lite' ),
					'stacked' => esc_html__( 'Stacked', 'essential-addons-for-elementor-lite' ),
					'auto'    => esc_html__( 'Auto', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->end_controls_section();
		// Style Tab End

        // Start Button
		$this->start_controls_section(
			'eael_add_to_cart_button',
			[
				'label' => esc_html__( 'Button', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'eael_add_to_cart_align',
			[
				'label'   => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
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

		$this->add_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'vw', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-add-to-cart .cart .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'eael_show_quantity',
			[
				'label'        => esc_html__( 'Show Quantity', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
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
		// End

	}

	protected function render() {
        global $product;

        if ( ! $product ) {
            return;
        }

        $settings = $this->get_settings_for_display();

        ?>
        <div class="eael-single-product-add-to-cart">
            <div class="elementor-add-to-cart elementor-product-<?php echo esc_attr( $product->get_type() ); ?>">
                <?php 
                    woocommerce_template_single_add_to_cart();
                ?>
            </div>
        </div>
        <?php
	}
}
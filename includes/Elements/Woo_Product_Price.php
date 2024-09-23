<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

class Woo_Product_Price extends Widget_Base {
    public function get_name() {
		return 'eael-woo-product-price';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Price', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eicon-elementor-circle templately-widget-icon';
	}

	public function get_categories() {
		return [ 'templately_library' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'price', 'sale', 'product' ];
	}

	protected function register_controls() {

		// Style Tab Start
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Price', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        //Start regular price
        $this->add_control(
			'regular_peice_heading',
			[
				'label' => esc_html__( 'Regular Price', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'price_align',
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
				'toggle'       => true,
				'selectors'    => [
					'{{WRAPPER}} .eael-single-product-price' =>  'text-align: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-price .price',
			]
		);

        $this->add_control(
			'price_text_decoration_color',
			[
				'label'     => esc_html__( 'Text Decoration Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price del' => 'text-decoration-color: {{VALUE}};',
				],
			]
		);
        //End regular price

        //Start Currency Symbol
        $this->add_control(
			'peice_currency_heading',
			[
				'label' => esc_html__( 'Price Currency Symbol', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'price_currency_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price del .woocommerce-Price-currencySymbol' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'price_currency_typography',
				'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-price .price del .woocommerce-Price-currencySymbol',
			]
		);

        $this->add_control(
			'price_currency_spacing',
			[
				'label' => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', 'em', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price del .woocommerce-Price-currencySymbol' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
        //End Currency Symbol

        //Start sale price
        $this->add_control(
			'sale_heading',
			[
				'label' => esc_html__( 'Sale Price', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'sale_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price ins' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'sale_typography',
				'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-price .price ins',
			]
		);

        $this->add_control(
			'sale_spacing',
			[
				'label' => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', 'em', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price del' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

        $this->add_control(
			'sale_text_decoration_color',
			[
				'label'     => esc_html__( 'Text Decoration Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price ins' => 'text-decoration-color: {{VALUE}};',
				],
			]
		);
        //End sale price

        //Start Currency Symbol
        $this->add_control(
			'sale_currency_heading',
			[
				'label' => esc_html__( 'Sale Currency Symbol', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'sale_currency_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price ins .woocommerce-Price-currencySymbol' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'sale_currency_typography',
				'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-price .price ins .woocommerce-Price-currencySymbol',
			]
		);

        $this->add_control(
			'sale_currency_spacing',
			[
				'label' => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', 'em', '%', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'%' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price ins .woocommerce-Price-currencySymbol' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);
        //End Currency Symbol

		$this->end_controls_section();
		// Style Tab End

	}

	protected function render() {
        global $product;

		$product = Helper::get_product();

        if ( ! $product ) {
            return;
        }
        ?>
        <div class="eael-single-product-price">
            <?php wc_get_template( '/single-product/price.php' ); ?>
        </div>
        <?php
	}
}
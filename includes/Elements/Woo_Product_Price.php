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
		return 'eaicon-product-price';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor', 'woocommerce-elements' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'price', 'sale', 'product' ];
	}

	protected function register_controls() {

		$this->eael_product_price_content();

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

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function eael_product_price_content() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'sale_price_position',
			[
				'label'   => esc_html__( 'Sale Price Position', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'after_price',
				'options' => [
					'after_price'  => esc_html__( 'After Regular Price', 'essential-addons-for-elementor-lite' ),
					'before_price' => esc_html__( 'Before Regular Price', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'stacked_price',
			[
				'label'        => esc_html__( 'Stacked', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'price_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		//Prefix Controls
		$this->add_control(
			'show_prefix',
			[
				'label'        => esc_html__( 'Show Prefix', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

		//Text
		$this->start_controls_tabs(
			'prefix_control'
		);

		$this->start_controls_tab(
			'prefix_text_tab',
			[
				'label'     => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'show_prefix' => 'yes',
				],
			]
		);

		$this->add_control(
			'prefix_text',
			[
				'label'       => esc_html__( 'Prefix Text', 'essential-addons-for-elementor-lite' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Default title', 'essential-addons-for-elementor-lite' ),
				'placeholder' => esc_html__( 'Type your title here', 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'show_prefix' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		//Icon
		$this->start_controls_tab(
			'prefix_icon_tab',
			[
				'label'     => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'show_prefix' => 'yes',
				],
			]
		);

		$this->add_control(
			'prefix_icon',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'  => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_prefix' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		//Suffix Controls
		$this->eael_prefix_suffix();

		$this->end_controls_section();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function eael_prefix_suffix() {
		$this->add_control(
			'show_suffix',
			[
				'label'        => esc_html__( 'Show Suffix', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

		//Text
		$this->start_controls_tabs(
			'suffix_control'
		);

		$this->start_controls_tab(
			'suffix_text_tab',
			[
				'label'     => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'show_suffix' => 'yes',
				],
			]
		);

		$this->add_control(
			'suffix_text',
			[
				'label'       => esc_html__( 'Suffix Text', 'essential-addons-for-elementor-lite' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Default title', 'essential-addons-for-elementor-lite' ),
				'placeholder' => esc_html__( 'Type your title here', 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'show_suffix' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		//Icon
		$this->start_controls_tab(
			'suffix_icon_tab',
			[
				'label'     => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'show_suffix' => 'yes',
				],
			]
		);

		$this->add_control(
			'suffix_icon',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'  => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_suffix' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}
	
	

	protected function render() {
		global $product;

		$product = Helper::get_product();

		if ( ! $product ) {
			return;
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
			<div class="eael-single-product-price">
				<p class="price">
					<del aria-hidden="true">
						<span class="woocommerce-Price-amount amount">
							<bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'essential-addons-for-elementor-lite' ); ?></span><?php esc_html_e( '80.00', 'essential-addons-for-elementor-lite' ); ?></bdi>
						</span>
					</del>
					<ins aria-hidden="true">
						<span class="woocommerce-Price-amount amount">
							<bdi><span class="woocommerce-Price-currencySymbol"><?php esc_html_e( '$', 'essential-addons-for-elementor-lite' ); ?></span><?php esc_html_e( '50.00', 'essential-addons-for-elementor-lite' ); ?></bdi>
						</span>
					</ins>
				</p>
			</div>
			<?php
		} else {
			?>
			<div class="eael-single-product-price">
				<?php wc_get_template( '/single-product/price.php' ); ?>
			</div>
			<?php
		}
	}
}
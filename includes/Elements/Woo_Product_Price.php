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
		$this->eael_product_regular_price();

		//Start Currency Symbol
		$this->eael_product_regular_price_currency();

		//Start sale price
		$this->eael_product_sale_price();

		//Start Currency Symbol
		$this->eael_product_sale_price_currency();

		$this->end_controls_section();
		// Style Tab End

		//Prefix
		$this->start_controls_section(
			'section_prefix',
			[
				'label' => esc_html__( 'Prefix', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->eael_product_price_prefix();

		$this->end_controls_section();
		// Style Tab End

		//Suffix
		$this->start_controls_section(
			'section_suffix',
			[
				'label' => esc_html__( 'Suffix', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->eael_product_price_suffix();

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
				'default' => 'row',
				'options' => [
					'row'  => esc_html__( 'After Regular Price', 'essential-addons-for-elementor-lite' ),
					'row-reverse' => esc_html__( 'Before Regular Price', 'essential-addons-for-elementor-lite' ),
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price' => 'flex-direction: {{VALUE}}',
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
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price' => 'flex-wrap: wrap;',
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price del' => 'display: block; flex: 1 1 100%;',
					'.woocommerce {{WRAPPER}} .eael-single-product-price .price ins' => 'display: block; flex: 1 1 100%;',
				],
			]
		);

		$this->add_control(
			'price_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		//Prefix-Suffix Controls
		$this->eael_prefix_suffix();

		$this->end_controls_section();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	protected function eael_prefix_suffix() {
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

		$this->add_control(
			'prefix_content',
			[
				'label'   => esc_html__( 'Prefix Content', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'text' => [
						'title' => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-area',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-nerd',
					],
				],
				'default'   => 'text',
				'toggle'    => false,
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
				'default'     => esc_html__( 'Limited Time Offer', 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'show_prefix'    => 'yes',
					'prefix_content' => 'text',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'prefix_icon',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'  => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-fire',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_prefix'    => 'yes',
					'prefix_content' => 'icon',
				],
			]
		);

		//Suffix
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

		$this->add_control(
			'suffix_content',
			[
				'label'   => esc_html__( 'Suffix Content', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'text' => [
						'title' => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-area',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-nerd',
					],
				],
				'default'   => 'text',
				'toggle'    => false,
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
				'default'     => esc_html__( 'Sales Ongoing', 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'show_suffix'    => 'yes',
					'suffix_content' => 'text',
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'suffix_icon',
			[
				'label'   => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-pepper-hot',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_suffix'    => 'yes',
					'suffix_content' => 'icon',
				],
			]
		);
	}

	protected function eael_product_regular_price() {
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
					'.woocommerce {{WRAPPER}} .woocommerce .eael-single-product-price .price' => 'color: {{VALUE}};',
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
					'.woocommerce {{WRAPPER}} .eael-single-product-price' =>  'text-align: {{VALUE}};',
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
	}
	protected function eael_product_regular_price_currency() {
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
	}
	protected function eael_product_sale_price() {
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
	}
	protected function eael_product_sale_price_currency() {
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
	}
	protected function eael_product_price_prefix() {
		$this->add_control(
			'price_prefix',
			[
				'label'     => esc_html__( 'Price Prefix', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'prefix_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .prefix-price-text span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'prefix_text_typography',
				'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-price .prefix-price-text span',
			]
		);

		$this->add_control(
			'prefix_text_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'default'    => [
					'top'    => 3,
					'right'  => 2,
					'bottom' => 0,
					'left'   => 0,
					'unit'   => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .prefix-price-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'prefix_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .prefix-price-icon svg' => 'fill: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'prefix_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .prefix-price-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'prefix_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'default'    => [
					'top'    => 4,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 0,
					'unit'   => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .prefix-price-icon svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}
	protected function eael_product_price_suffix() {
		$this->add_control(
			'price_suffix',
			[
				'label'     => esc_html__( 'Price Suffix', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'suffix_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .suffix-price-text span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'suffix_text_typography',
				'selector' => '.woocommerce {{WRAPPER}} .eael-single-product-price .suffix-price-text span',
			]
		);

		$this->add_control(
			'suffix_text_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'default'    => [
					'top'    => 3,
					'right'  => 2,
					'bottom' => 0,
					'left'   => 0,
					'unit'   => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .suffix-price-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'suffix_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .suffix-price-icon svg' => 'fill: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'suffix_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .suffix-price-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'suffix_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'default'    => [
					'top'    => 4,
					'right'  => 0,
					'bottom' => 0,
					'left'   => 0,
					'unit'   => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'.woocommerce {{WRAPPER}} .eael-single-product-price .suffix-price-icon svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	protected function render() {
		global $product;
		$settings = $this->get_settings_for_display();
		$prefix_content = $settings['prefix_content'];
		$suffix_content = $settings['suffix_content'];
		$product = Helper::get_product();

		if ( ! $product ) {
			return;
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			?>
			<div class="eael-single-product-price">
				<?php
					if ( 'yes' === $settings['show_prefix'] ) {
						switch ($prefix_content) {
							case 'text':
								?>
								<div class="prefix-price-text">
									<span><?php echo Helper::eael_wp_kses( $settings['prefix_text'] ); ?></span>
								</div>
								<?php
								break;
							case 'icon':
								?>
								<div class="prefix-price-icon">
									<?php \Elementor\Icons_Manager::render_icon( $settings['prefix_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</div>
								<?php
								break;
						}
					}
				?>
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
				<?php
					if ( 'yes' === $settings['show_suffix'] ) {
						switch ($suffix_content) {
							case 'text':
								?>
								<div class="suffix-price-text">
									<span><?php echo Helper::eael_wp_kses( $settings['suffix_text'] ); ?></span>
								</div>
								<?php
								break;
							case 'icon':
								?>
								<div class="suffix-price-icon">
									<?php \Elementor\Icons_Manager::render_icon( $settings['suffix_icon'], [ 'aria-hidden' => 'true' ] ); ?>
								</div>
								<?php
								break;
						}
					}
				?>
			</div>
			<?php
		} else {
			?>
			<div class="eael-single-product-price">
				<?php 
				if ( 'yes' === $settings['show_prefix'] ) {
					switch ($prefix_content) {
						case 'text':
							?>
							<div class="prefix-price-text">
								<span><?php echo Helper::eael_wp_kses( $settings['prefix_text'] ); ?></span>
							</div>
							<?php
							break;
						case 'icon':
							?>
							<div class="prefix-price-icon">
								<?php \Elementor\Icons_Manager::render_icon( $settings['prefix_icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</div>
							<?php
							break;
					}
				}
				?>
				<?php 
				wc_get_template( '/single-product/price.php' );

				if ( 'yes' === $settings['show_suffix'] ) {
					switch ($suffix_content) {
						case 'text':
							?>
							<div class="suffix-price-text">
								<span><?php echo Helper::eael_wp_kses( $settings['suffix_text'] ); ?></span>
							</div>
							<?php
							break;
						case 'icon':
							?>
							<div class="suffix-price-icon">
								<?php \Elementor\Icons_Manager::render_icon( $settings['suffix_icon'], [ 'aria-hidden' => 'true' ] ); ?>
							</div>
							<?php
							break;
					}
				}
				?>
			</div>
			<?php
		}
	}
}
<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Essential_Addons_Elementor\Classes\Helper;

class Woo_Product_Title extends Widget_Base {

	public function get_name() {
		return 'eael-woo-product-title';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Title', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-product-price';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor', 'woocommerce-elements' ];
	}

	public function get_keywords() {
		return [
			'woocommerce',
			'product',
			'title',
			'heading',
			'woo',
			'ea',
			'product title',
			'ea product title',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/ea-woo-product-title';
	}

	protected function register_controls() {

		$this->eael_wc_notice_controls();
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		/**
		 * Content Tab
		 */
		$this->start_controls_section(
			'eael_section_product_title_content',
			[
				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'header_size',
			[
				'label'   => esc_html__( 'HTML Tag', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
			]
		);

		$this->add_control(
			'eael_product_title_link',
			[
				'label'        => esc_html__( 'Link', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'eael_product_title_link_type',
			[
				'label'     => esc_html__( 'Link Type', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'product',
				'options'   => [
					'product' => esc_html__( 'Product Permalink', 'essential-addons-for-elementor-lite' ),
					'custom'  => esc_html__( 'Custom URL', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_product_title_link' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_product_title_custom_link',
			[
				'label'         => esc_html__( 'Custom URL', 'essential-addons-for-elementor-lite' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'essential-addons-for-elementor-lite' ),
				'show_external' => true,
				'dynamic'       => [ 'active' => true ],
				'condition'     => [
					'eael_product_title_link'      => 'yes',
					'eael_product_title_link_type' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Style Tab
		 */
		$this->start_controls_section(
			'eael_section_product_title_style',
			[
				'label' => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-woo-product-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-woo-product-title .product_title'    => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-woo-product-title .product_title a'  => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .eael-woo-product-title .product_title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .eael-woo-product-title .product_title',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label'     => esc_html__( 'Blend Mode', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''            => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
					'multiply'    => esc_html__( 'Multiply', 'essential-addons-for-elementor-lite' ),
					'screen'      => esc_html__( 'Screen', 'essential-addons-for-elementor-lite' ),
					'overlay'     => esc_html__( 'Overlay', 'essential-addons-for-elementor-lite' ),
					'darken'      => esc_html__( 'Darken', 'essential-addons-for-elementor-lite' ),
					'lighten'     => esc_html__( 'Lighten', 'essential-addons-for-elementor-lite' ),
					'color-dodge' => esc_html__( 'Color Dodge', 'essential-addons-for-elementor-lite' ),
					'saturation'  => esc_html__( 'Saturation', 'essential-addons-for-elementor-lite' ),
					'color'       => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
					'difference'  => esc_html__( 'Difference', 'essential-addons-for-elementor-lite' ),
					'exclusion'   => esc_html__( 'Exclusion', 'essential-addons-for-elementor-lite' ),
					'hue'         => esc_html__( 'Hue', 'essential-addons-for-elementor-lite' ),
					'luminosity'  => esc_html__( 'Luminosity', 'essential-addons-for-elementor-lite' ),
				],
				'selectors' => [
					'{{WRAPPER}} .eael-woo-product-title .product_title' => 'mix-blend-mode: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Show a warning section when WooCommerce is not installed/activated.
	 *
	 * @return void
	 */
	protected function eael_wc_notice_controls() {
		if ( ! function_exists( 'WC' ) ) {
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
					'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.', 'essential-addons-for-elementor-lite' ),
					'content_classes' => 'eael-warning',
				]
			);
			$this->end_controls_section();

			return;
		}
	}

	protected function render() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$settings  = $this->get_settings_for_display();
		$product   = Helper::get_product();
		$is_editor = Plugin::$instance->editor->is_edit_mode();

		// Resolve the title text.
		if ( ! $product ) {
			if ( ! $is_editor ) {
				return; // No product context on the frontend — render nothing.
			}
			$title = esc_html__( 'Product Title', 'essential-addons-for-elementor-lite' );
		} else {
			$title = $product->get_name();
		}

		// Validate the HTML tag against EA's allow-list (falls back to div).
		$tag = Helper::eael_validate_html_tag( $settings['header_size'] );

		$title_html = esc_html( $title );

		// Optional link wrap.
		if ( 'yes' === $settings['eael_product_title_link'] ) {
			if ( 'custom' === $settings['eael_product_title_link_type'] ) {
				if ( ! empty( $settings['eael_product_title_custom_link']['url'] ) ) {
					$this->add_link_attributes( 'product_title_link', $settings['eael_product_title_custom_link'] );
					$title_html = sprintf(
						'<a %1$s>%2$s</a>',
						$this->get_render_attribute_string( 'product_title_link' ),
						$title_html
					);
				}
			} elseif ( $product ) {
				$title_html = sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url( get_permalink( $product->get_id() ) ),
					$title_html
				);
			}
		}

		printf(
			'<div class="eael-woo-product-title"><%1$s class="product_title entry-title">%2$s</%1$s></div>',
			esc_attr( $tag ),
			$title_html // Title is escaped above; optional <a> built from sanitized attributes.
		);
	}

	public function render_plain_content() {}
}

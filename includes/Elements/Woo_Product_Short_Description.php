<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Essential_Addons_Elementor\Classes\Helper;

class Woo_Product_Short_Description extends Widget_Base {

	public function get_name() {
		return 'eael-woo-product-short-description';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Short Description', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eicon-product-description';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor', 'woocommerce-elements' ];
	}

	public function get_keywords() {
		return [
			'woocommerce',
			'product',
			'description',
			'short description',
			'excerpt',
			'woo',
			'ea',
			'essential addons',
			'ea product',
			'woo product',
			'Product Description',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/ea-woo-product-short-description';
	}

	protected function register_controls() {

		$this->eael_wc_notice_controls();
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$this->start_controls_section(
			'section_short_description_style',
			[
				'label' => esc_html__( 'Short Description', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
            'eael_product_sd_text_align',
			[
				'label'   => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'classes'              => 'elementor-control-start-end',
				'selectors_dictionary' => [
					'left'  => is_rtl() ? 'end' : 'start',
					'right' => is_rtl() ? 'start' : 'end',
				],
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-short-description' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
            'eael_product_sd_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-short-description .woocommerce-product-details__short-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_sd_text_typography',
				'selector' => '{{WRAPPER}} .eael-single-product-short-description .woocommerce-product-details__short-description',
			]
		);

		$this->add_control(
			'eael_product_sd_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-short-description' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_sd_border',
				'selector' => '{{WRAPPER}} .eael-single-product-short-description',
			]
		);

		$this->add_responsive_control(
			'eael_product_sd_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-single-product-short-description' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_sd_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-single-product-short-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_sd_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-single-product-short-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * WC Notice
	 */
	protected function eael_wc_notice_controls() {
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

	protected function render() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		global $product;
		$product = Helper::get_product(); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound

		$is_editor = Plugin::$instance->editor->is_edit_mode() || get_post_type( get_the_ID() ) === 'templately_library';

		if ( ! $product && ! $is_editor ) {
			return;
		}
		?>
		<div class="eael-single-product-short-description">
			<?php
			if ( $product ) {
				wc_get_template( 'single-product/short-description.php' );
			} else {
				?>
				<div class="woocommerce-product-details__short-description">
					<p><?php esc_html_e( 'This is a short product description placeholder. Add a short description to your product to see it here.', 'essential-addons-for-elementor-lite' ); ?></p>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}

}

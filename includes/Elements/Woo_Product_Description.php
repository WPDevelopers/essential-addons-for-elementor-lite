<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Essential_Addons_Elementor\Classes\Helper;

class Woo_Product_Description extends Widget_Base {

	public function get_name() {
		return 'eael-woo-product-description';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Description', 'essential-addons-for-elementor-lite' );
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
			'long description',
			'content',
			'product content',
			'woo',
			'ea',
			'essential addons',
			'ea product',
			'woo product',
			'Product Description',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/ea-woo-product-description';
	}

	protected function register_controls() {

		$this->eael_wc_notice_controls();
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$this->start_controls_section(
			'section_product_description_style',
			[
				'label' => esc_html__( 'Description', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_product_description_align',
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
					'{{WRAPPER}} .eael-single-product-description' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_product_description_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_description_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .eael-single-product-description',
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
		$post_object = $product ? get_post( $product->get_id() ) : false;
		$has_content = $post_object && '' !== trim( $post_object->post_content );
		?>
		<div class="eael-single-product-description">
			<?php
			if ( $has_content ) {
				setup_postdata( $post_object );
				echo apply_filters( 'the_content', $post_object->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				wp_reset_postdata();
			} elseif ( $is_editor ) {
				echo wp_kses_post( $this->get_editor_placeholder() );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Dummy long-description markup shown only inside the Elementor editor
	 */
	protected function get_editor_placeholder() {
		$para_1  = esc_html__( 'This is a placeholder for the product long description. Add content to the main editor of your product and it will appear here on the front end.', 'essential-addons-for-elementor-lite' );
		$para_2  = esc_html__( 'Use this widget on a Single Product template to display the full product description. Style the alignment, text color and typography from the Style tab.', 'essential-addons-for-elementor-lite' );

		return sprintf(
			'<p>%1$s</p><p>%2$s</p>',
			$para_1,
			$para_2
		);
	}

}

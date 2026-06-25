<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Essential_Addons_Elementor\Classes\Helper;

class Woo_Product_Tabs extends Widget_Base {

	public function get_name() {
		return 'eael-woo-product-tabs';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Tabs', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-product-tabs';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor', 'woocommerce-elements' ];
	}

	public function get_keywords() {
		return [
			'woocommerce',
			'product',
			'tabs',
			'data',
			'description',
			'reviews',
			'woo',
			'ea',
			'essential addons',
			'EA Product Tabs',
			'Product Data Tabs',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/ea-woo-product-tabs';
	}

	protected function register_controls() {

		$this->eael_wc_notice_controls();
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		/**
		 * Content Tab: Tab Items
		 */
		$this->start_controls_section(
			'eael_section_product_tabs_content',
			[
				'label' => esc_html__( 'Tab Items', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'eael_product_tabs_items_info',
			[
				'type'       => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'content'    => esc_html__( 'Each row controls one WooCommerce tab. Drag rows to reorder, switch a row off to hide it, or set a Title to rename it.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'eael_product_tabs_item_key',
			[
				'label'       => esc_html__( 'Tab Key', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'description',
				'description' => esc_html__( 'WooCommerce tab slug, e.g. description, additional_information, reviews, or a custom tab key added by another plugin.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$repeater->add_control(
			'eael_product_tabs_item_icon',
			[
				'label'       => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::ICONS,
			]
		);

		$repeater->add_control(
			'eael_product_tabs_item_title',
			[
				'label'       => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'description' => esc_html__( 'Leave empty to keep the original tab title.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$repeater->add_control(
			'eael_product_tabs_item_show',
			[
				'label'        => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_product_tabs_items',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ eael_product_tabs_item_title || eael_product_tabs_item_key }}}',
				'default'     => [
					[
						'eael_product_tabs_item_key'   => 'description',
						'eael_product_tabs_item_title' => '',
						'eael_product_tabs_item_show'  => 'yes',
					],
					[
						'eael_product_tabs_item_key'   => 'additional_information',
						'eael_product_tabs_item_title' => '',
						'eael_product_tabs_item_show'  => 'yes',
					],
					[
						'eael_product_tabs_item_key'   => 'reviews',
						'eael_product_tabs_item_title' => '',
						'eael_product_tabs_item_show'  => 'yes',
					],
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Tabs
		 */
		$this->start_controls_section(
			'eael_section_product_tabs_style',
			[
				'label' => esc_html__( 'Tabs', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'eael_product_tabs_style_tabs' );

		$this->start_controls_tab(
			'eael_product_tabs_normal_style',
			[
				'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_product_tabs_tab_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_product_tabs_tab_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_product_tabs_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-color: {{VALUE}}',
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li'            => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'eael_product_tabs_active_style',
			[
				'label' => esc_html__( 'Active', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_product_tabs_active_tab_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_product_tabs_active_tab_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel, .woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'background-color: {{VALUE}}',
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_product_tabs_active_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel'   => 'border-color: {{VALUE}}',
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li.active'       => 'border-color: {{VALUE}} {{VALUE}} {{eael_product_tabs_active_tab_bg_color.VALUE}} {{VALUE}}',
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li:not(.active)' => 'border-bottom-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'eael_product_tabs_typography',
				'selector'  => '.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li a',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_product_tabs_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs li' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Panel
		 */
		$this->start_controls_section(
			'eael_section_product_panel_style',
			[
				'label' => esc_html__( 'Panel', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'eael_product_tabs_panel_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .woocommerce-Tabs-panel' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_tabs_content_typography',
				'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel',
			]
		);

		$this->add_control(
			'eael_product_tabs_panel_heading',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Heading', 'essential-addons-for-elementor-lite' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_product_tabs_heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .woocommerce-Tabs-panel h2' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_tabs_content_heading_typography',
				'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel h2',
			]
		);

		$this->add_control(
			'eael_product_tabs_panel_border_width',
			[
				'label'      => esc_html__( 'Border Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; margin-top: -{{TOP}}{{UNIT}}',
				],
				'separator'  => 'before',
			]
		);

		$this->add_control(
			'eael_product_tabs_panel_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
					'.woocommerce {{WRAPPER}} .woocommerce-tabs ul.wc-tabs'             => 'margin-left: {{TOP}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_product_tabs_panel_box_shadow',
				'selector' => '.woocommerce {{WRAPPER}} .woocommerce-tabs .woocommerce-Tabs-panel',
			]
		);

		$this->end_controls_section();
	}

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

		if ( ! $product ) {
			if ( $is_editor ) {
				?>
				<div class="eael-woo-product-tabs-placeholder">
					<?php esc_html_e( 'Product Data Tabs will be displayed here on a single product page.', 'essential-addons-for-elementor-lite' ); ?>
				</div>
				<?php
			}

			return;
		}

		setup_postdata( $product->get_id() );

		// Hide / rename / reorder tabs based on the Tab Items repeater (see manage_product_tabs()).
		add_filter( 'woocommerce_product_tabs', [ $this, 'manage_product_tabs' ], 98 );

		echo '<div class="eael-woo-product-tabs">';
		wc_get_template( 'single-product/tabs/tabs.php' );
		echo '</div>';

		remove_filter( 'woocommerce_product_tabs', [ $this, 'manage_product_tabs' ], 98 );

		// On render widget from Editor - trigger the WooCommerce tab JS manually.
		if ( wp_doing_ajax() ) {
			?>
			<script>
				jQuery( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
			</script>
			<?php
		}

		wp_reset_postdata();
	}

	/**
	 * Apply the Tab Items repeater to the WooCommerce product tabs.
	 * Hooked to the `woocommerce_product_tabs` filter while the tabs
	 * template renders. `$tabs` is an array keyed by tab slug
	 * ( description, additional_information, reviews, or any custom key ).
	 */
	public function manage_product_tabs( $tabs ) {
		$settings = $this->get_settings_for_display();
		$items    = $settings['eael_product_tabs_items'];

		if ( empty( $items ) ) {
			return $tabs;
		}

		$order = 10;

		foreach ( $items as $item ) {
			$key = trim( $item['eael_product_tabs_item_key'] );

			// Row points to a tab that isn't there — ignore it.
			if ( '' === $key || ! isset( $tabs[ $key ] ) ) {
				continue;
			}

			// Switched off — remove the tab and move on.
			if ( 'yes' !== $item['eael_product_tabs_item_show'] ) {
				unset( $tabs[ $key ] );
				continue;
			}

			// Start from the original title, replace it only when a custom one is set.
			$title = $tabs[ $key ]['title'];
			if ( '' !== $item['eael_product_tabs_item_title'] ) {
				$title = $item['eael_product_tabs_item_title'];
			}

			// Prepend the chosen icon (WC runs wp_kses_post on the title, so font-icon HTML is allowed).
			$icon                  = isset( $item['eael_product_tabs_item_icon'] ) ? $item['eael_product_tabs_item_icon'] : [];
			$tabs[ $key ]['title'] = $this->get_tab_icon_html( $icon ) . $title;

			// Order the tab to match this row's position.
			$tabs[ $key ]['priority'] = $order;
			$order                   += 10;
		}

		return $tabs;
	}

	/**
	 * Build the HTML for a repeater row icon.
	 * Returns an empty string when no icon was picked. Otherwise the icon
	 */
	private function get_tab_icon_html( $icon ) {
		if ( empty( $icon['value'] ) ) {
			return '';
		}

		$icon_html = Icons_Manager::try_get_icon_html( $icon, [ 'aria-hidden' => 'true' ] );

		if ( empty( $icon_html ) ) {
			return '';
		}

		return '<span class="eael-product-tab-icon">' . $icon_html . '</span>';
	}
}

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

        $repeater->add_control(
            'eael_product_tabs_list_note',
            [
                'type'      => Controls_Manager::RAW_HTML,
                'raw'       => '<style>.elementor-control-eael_product_tabs_items .elementor-repeater-add { display: none !important; }</style>',
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_product_tabs_typography',
                'selector'  => '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs ul.wc-tabs li a',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'eael_product_tabs_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-woo-product-tabs .tabs.wc-tabs li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'width',
            [
                'label'      => esc_html__('Gap', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range'      => [
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
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-product-tabs .tabs.wc-tabs' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_tabs_margin',
            [
                'label'      => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-woo-product-tabs .tabs.wc-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_tabs_padding',
            [
                'label'      => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-woo-product-tabs .tabs.wc-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'eael_product_tabs_border',
                'selector' => '{{WRAPPER}} .eael-woo-product-tabs .tabs.wc-tabs li',
            ]
        );

        $this->add_control(
            'eael_product_tabs_icon_heading',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_tabs_icon_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-product-tabs .tabs.wc-tabs li a .eael-product-tab-icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-woo-product-tabs .tabs.wc-tabs li a .eael-product-tab-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_tabs_icon_active_color',
            [
                'label'     => esc_html__('Active Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-product-tabs .tabs.wc-tabs li.active a .eael-product-tab-icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-woo-product-tabs .tabs.wc-tabs li.active a .eael-product-tab-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_tabs_icon_size',
            [
                'label'      => esc_html__('Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-woo-product-tabs .tabs.wc-tabs li a .eael-product-tab-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-woo-product-tabs .tabs.wc-tabs li a .eael-product-tab-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_tabs_icon_gap',
            [
                'label'      => esc_html__('Gap', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 60],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-woo-product-tabs .tabs.wc-tabs li a .eael-product-tab-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
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
                    '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs ul.wc-tabs li a' => 'color: {{VALUE}}',
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
					'.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs ul.wc-tabs li' => 'background-color: {{VALUE}}',
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
                    '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs ul.wc-tabs li.active a' => 'color: {{VALUE}}',
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
					'.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs ul.wc-tabs li.active' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
            'eael_product_tabs_active_border_color',
            [
                'label'     => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    // '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel'   => 'border-color: {{VALUE}}',
                    // '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs ul.wc-tabs li.active'       => 'border-color: {{VALUE}} {{VALUE}} {{eael_product_tabs_active_tab_bg_color.VALUE}} {{VALUE}}',
                    // '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs ul.wc-tabs li:not(.active)' => 'border-bottom-color: {{VALUE}}',
                    '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs ul.wc-tabs li.active a' => 'border-bottom-color: {{VALUE}}',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->end_controls_tabs();

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
            'eael_product_tabs_panel_bg_color',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-Tabs-panel' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_tabs_panel_margin',
            [
                'label'      => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_tabs_panel_padding',
            [
                'label'      => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'eael_product_tabs_panel_box_border',
                'selector' => '{{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel',
            ]
        );

        $this->add_responsive_control(
            'eael_product_tabs_panel_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_product_tabs_panel_box_shadow',
                'selector' => '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel',
            ]
        );

        $this->add_control(
            'eael_product_tabs_panel_heading',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__('Heading', 'essential-addons-for-elementor-lite'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_product_tabs_heading_color',
            [
                'label'     => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-Tabs-panel h2' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_tabs_content_heading_typography',
                'selector' => '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel h2',
            ]
        );

        $this->add_control(
            'eael_product_tabs_panel_heading_content',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__('Content', 'essential-addons-for-elementor-lite'),
                'separator' => 'before',
            ]
        );

		$this->add_control(
			'eael_product_tabs_panel_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
                    '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-Tabs-panel' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_product_tabs_content_typography',
				'selector' => '.woocommerce {{WRAPPER}} .eael-woo-product-tabs .woocommerce-tabs .woocommerce-Tabs-panel',
			]
		);

        $this->add_control(
            'eael_product_tabs_panel_button',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__('Button', 'essential-addons-for-elementor-lite'),
                'separator' => 'before',
            ]
        );

        $button_selector = '{{WRAPPER}} .eael-woo-product-tabs .woocommerce-Tabs-panel #respond input#submit, {{WRAPPER}} .eael-woo-product-tabs .woocommerce-Tabs-panel .button';

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_product_tabs_button_typography',
                'selector' => $button_selector,
            ]
        );

        $this->start_controls_tabs( 'eael_product_tabs_button_tabs' );

        $this->start_controls_tab(
            'eael_product_tabs_button_normal',
            [
                'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'eael_product_tabs_button_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    $button_selector => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_tabs_button_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    $button_selector => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_product_tabs_button_hover',
            [
                'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'eael_product_tabs_button_hover_color',
            [
                'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-product-tabs .woocommerce-Tabs-panel #respond input#submit:hover, {{WRAPPER}} .eael-woo-product-tabs .woocommerce-Tabs-panel .button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_product_tabs_button_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-product-tabs .woocommerce-Tabs-panel #respond input#submit:hover, {{WRAPPER}} .eael-woo-product-tabs .woocommerce-Tabs-panel .button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'eael_product_tabs_button_border',
                'selector'  => $button_selector,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'eael_product_tabs_button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors'  => [
                    $button_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_tabs_button_padding',
            [
                'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors'  => [
                    $button_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_product_tabs_button_margin',
            [
                'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors'  => [
                    $button_selector => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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

		// Editor with no product in context: load the latest published product so the
		// widget previews REAL tabs (same approach Elementor Pro uses for its Single
		// Product preview). Set $GLOBALS['post'] too, so the Description tab's the_content
		// resolves. The static mock below is only reached when the store has no products.
		if ( ! $product && $is_editor ) {
			$product = $this->get_editor_preview_product();
			if ( $product ) {
				$GLOBALS['post'] = get_post( $product->get_id() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}
		}

		if ( ! $product ) {
			// No product in context (e.g. a normal page or a Single Product template
			// with no preview product set). Render a static mock built from the
			// configured tab items so the widget is visible and stylable in the editor.
			if ( $is_editor ) {
				$this->render_editor_mock();
			}

			return;
		}

		setup_postdata( $product->get_id() );

		// Hide / rename / reorder tabs based on the Tab Items repeater (see manage_product_tabs()).
		add_filter( 'woocommerce_product_tabs', [ $this, 'manage_product_tabs' ], 98 );

		// Buffer the WC tabs markup so we can inject icons into the tab anchors
		// directly (bypasses the title's wp_kses_post, which strips inline SVG).
		ob_start();
		wc_get_template( 'single-product/tabs/tabs.php' );
		$tabs_html = ob_get_clean();

		remove_filter( 'woocommerce_product_tabs', [ $this, 'manage_product_tabs' ], 98 );

		echo '<div class="eael-woo-product-tabs">' . $this->inject_tab_icons( $tabs_html ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// In the Elementor editor / preview the page
		if ( wp_doing_ajax() || Plugin::$instance->editor->is_edit_mode() || Plugin::$instance->preview->is_preview_mode() ) {
			?>
			<script>
				jQuery( function ( $ ) {
					$( '.wc-tabs-wrapper, .woocommerce-tabs, #rating' ).trigger( 'init' );
				} );
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

			// Rename the tab when a custom title is set (icons are injected later,
			// see inject_tab_icons() — the title is run through wp_kses_post which
			// would strip inline SVG, so icons must not go through it).
			if ( '' !== $item['eael_product_tabs_item_title'] ) {
				$tabs[ $key ]['title'] = $item['eael_product_tabs_item_title'];
			}

			// Order the tab to match this row's position.
			$tabs[ $key ]['priority'] = $order;
			$order                   += 10;
		}

		return $tabs;
	}

	/**
	 * Insert each row's icon into its tab anchor.
	 *
	 * WooCommerce prints every tab link as `<a href="#tab-{key}" …>`, so we
	 * match that opening tag per key and drop the icon HTML right after it —
	 * before the (kses-escaped) title text. Because the icon never passes
	 * through `wp_kses_post`, both font icons (`<i>`) and inline `<svg>`
	 * render intact. A callback replacement is used so `$`/`\` inside SVG
	 */
	private function inject_tab_icons( $html ) {
		if ( '' === trim( $html ) ) {
			return $html;
		}

		$settings = $this->get_settings_for_display();
		$items    = ! empty( $settings['eael_product_tabs_items'] ) ? $settings['eael_product_tabs_items'] : [];

		foreach ( $items as $item ) {
			$key = trim( $item['eael_product_tabs_item_key'] );

			if ( '' === $key || 'yes' !== $item['eael_product_tabs_item_show'] ) {
				continue;
			}

			$icon      = isset( $item['eael_product_tabs_item_icon'] ) ? $item['eael_product_tabs_item_icon'] : [];
			$icon_html = $this->get_tab_icon_html( $icon );

			if ( '' === $icon_html ) {
				continue;
			}

			// Match the opening <a> of this tab: href="#tab-{key}".
			// Delimiter is ~ because the pattern itself contains a literal #.
			$pattern = '~(<a[^>]*href="#tab-' . preg_quote( $key, '~' ) . '"[^>]*>)~';

			$html = preg_replace_callback(
				$pattern,
				function ( $matches ) use ( $icon_html ) {
					return $matches[1] . $icon_html;
				},
				$html,
				1
			);
		}

		return $html;
	}

	/**
	 * Render a static tabs mock for the Elementor editor.
	 *
	 * Used when there is no product in context (a normal page, or a Single
	 * Product template without a preview product). Mirrors the WooCommerce
	 * tabs markup and classes so the default styles and the Style-tab controls
	 * preview correctly. Built from the configured Tab Items (title, icon,
	 * order, visibility); falls back to the three WooCommerce defaults when
	 * nothing is configured.
	 *
	 * @return void
	 */
	private function render_editor_mock() {
		$settings = $this->get_settings_for_display();
		$items    = ! empty( $settings['eael_product_tabs_items'] ) ? $settings['eael_product_tabs_items'] : [];

		$tabs = [];

		foreach ( $items as $item ) {
			$key = trim( $item['eael_product_tabs_item_key'] );

			if ( '' === $key || 'yes' !== $item['eael_product_tabs_item_show'] ) {
				continue;
			}

			$label = '' !== $item['eael_product_tabs_item_title']
				? $item['eael_product_tabs_item_title']
				: ucwords( str_replace( '_', ' ', $key ) );

			$icon = isset( $item['eael_product_tabs_item_icon'] ) ? $item['eael_product_tabs_item_icon'] : [];

			$tabs[] = [
				'key'       => $key,
				'label'     => $label,
				'icon_html' => $this->get_tab_icon_html( $icon ),
			];
		}

		if ( empty( $tabs ) ) {
			$tabs = [
				[ 'key' => 'description', 'label' => esc_html__( 'Description', 'essential-addons-for-elementor-lite' ), 'icon_html' => '' ],
				[ 'key' => 'additional_information', 'label' => esc_html__( 'Additional Information', 'essential-addons-for-elementor-lite' ), 'icon_html' => '' ],
				[ 'key' => 'reviews', 'label' => esc_html__( 'Reviews', 'essential-addons-for-elementor-lite' ), 'icon_html' => '' ],
			];
		}

		$first = $tabs[0];
		?>
		<div class="eael-woo-product-tabs eael-woo-product-tabs--editor-preview">
			<div class="woocommerce-tabs wc-tabs-wrapper">
				<ul class="tabs wc-tabs">
					<?php foreach ( $tabs as $index => $tab ) : ?>
						<li class="<?php echo esc_attr( $tab['key'] ); ?>_tab<?php echo 0 === $index ? ' active' : ''; ?>">
							<a href="#"><?php echo $tab['icon_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- icon HTML from Elementor icon picker (admin) ?><?php echo esc_html( $tab['label'] ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $first['key'] ); ?> panel entry-content wc-tab">
					<h2><?php echo esc_html( $first['label'] ); ?></h2>
					<p><?php esc_html_e( 'This is an editor preview of the product tabs. The real tab content is shown on a single product page.', 'essential-addons-for-elementor-lite' ); ?></p>
					<p><a href="#" class="button"><?php esc_html_e( 'Sample Button', 'essential-addons-for-elementor-lite' ); ?></a></p>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Editor-only fallback product.
	 *
	 * Mirrors how Elementor Pro's Single Product document seeds its preview with
	 * the latest published product (elementor-pro product.php:176-188) so the
	 * widget can preview real tab content when no product is in context (e.g. a
	 * plain page or a non-Pro theme-builder template). Frontend never calls this.
	 *
	 * @return \WC_Product|false
	 */
	private function get_editor_preview_product() {
		static $cached = null; // avoid re-query when several tabs widgets exist.

		if ( null !== $cached ) {
			return $cached;
		}

		$products = wc_get_products( [
			'status'  => 'publish',
			'limit'   => 1,
			'orderby' => 'date',
			'order'   => 'DESC',
		] );

		$cached = ! empty( $products ) ? $products[0] : false;

		return $cached;
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

<?php

namespace Essential_Addons_Elementor\MegaMenu\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

/**
 * Style tab controls for the Mega Menu widget.
 *
 * Every value is written into a CSS custom property on `{{WRAPPER}}` so the
 * stylesheet stays static and the same declarations drive both the desktop bar
 * and the collapsed mobile accordion.
 *
 * @since 6.3.0
 */
class Style_Controls {

	/**
	 * Register every style tab section on the widget.
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	public static function register( Widget_Base $widget ) {
		self::register_menu_bar_section( $widget );
		self::register_menu_item_section( $widget );
		self::register_icon_section( $widget );
		self::register_indicator_section( $widget );
		self::register_panel_section( $widget );
		self::register_dropdown_section( $widget );
		self::register_toggle_section( $widget );
	}

	/**
	 * The horizontal bar that holds the menu items.
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	protected static function register_menu_bar_section( Widget_Base $widget ) {
		$widget->start_controls_section( 'eael_mega_menu_bar_style_section', [
			'label' => esc_html__( 'Menu Bar', 'essential-addons-for-elementor-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$widget->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'eael_mega_menu_bar_background',
			'types'    => [ 'classic', 'gradient' ],
			'exclude'  => [ 'image' ],
			'selector' => '{{WRAPPER}} .eael-mega-menu__list',
		] );

		$widget->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'eael_mega_menu_bar_border',
			'selector' => '{{WRAPPER}} .eael-mega-menu__list',
		] );

		$widget->add_responsive_control( 'eael_mega_menu_bar_radius', [
			'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'selectors'  => [
				'{{WRAPPER}} .eael-mega-menu__list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_bar_padding', [
			'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'selectors'  => [
				'{{WRAPPER}} .eael-mega-menu__list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_bar_gap', [
			'label'      => esc_html__( 'Gap Between Items', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 120 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 8 ],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-item-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$widget->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'eael_mega_menu_bar_shadow',
			'selector' => '{{WRAPPER}} .eael-mega-menu__list',
		] );

		$widget->end_controls_section();
	}

	/**
	 * Menu item link — normal / hover / active states.
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	protected static function register_menu_item_section( Widget_Base $widget ) {
		$widget->start_controls_section( 'eael_mega_menu_item_style_section', [
			'label' => esc_html__( 'Menu Items', 'essential-addons-for-elementor-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$widget->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'eael_mega_menu_item_typography',
			'selector' => '{{WRAPPER}} .eael-mega-menu__link',
		] );

		$widget->add_responsive_control( 'eael_mega_menu_item_padding', [
			'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'default'    => [
				'top'      => '12',
				'right'    => '16',
				'bottom'   => '12',
				'left'     => '16',
				'unit'     => 'px',
				'isLinked' => false,
			],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-item-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_item_radius', [
			'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-item-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$widget->start_controls_tabs( 'eael_mega_menu_item_style_tabs' );

		// Normal.
		$widget->start_controls_tab( 'eael_mega_menu_item_normal_tab', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_item_color', [
			'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-item-color: {{VALUE}};',
			],
		] );

		$widget->add_control( 'eael_mega_menu_item_bg', [
			'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-item-bg: {{VALUE}};',
			],
		] );

		$widget->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'eael_mega_menu_item_border',
			'selector' => '{{WRAPPER}} .eael-mega-menu__item',
		] );

		$widget->end_controls_tab();

		// Hover.
		$widget->start_controls_tab( 'eael_mega_menu_item_hover_tab', [
			'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_item_color_hover', [
			'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-item-color-hover: {{VALUE}};',
			],
		] );

		$widget->add_control( 'eael_mega_menu_item_bg_hover', [
			'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-item-bg-hover: {{VALUE}};',
			],
		] );

		$widget->add_control( 'eael_mega_menu_item_border_color_hover', [
			'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eael-mega-menu__item:hover' => 'border-color: {{VALUE}};',
			],
		] );

		$widget->end_controls_tab();

		// Active (submenu open).
		$widget->start_controls_tab( 'eael_mega_menu_item_active_tab', [
			'label' => esc_html__( 'Active', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_item_color_active', [
			'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-item-color-active: {{VALUE}};',
			],
		] );

		$widget->add_control( 'eael_mega_menu_item_bg_active', [
			'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-item-bg-active: {{VALUE}};',
			],
		] );

		$widget->add_control( 'eael_mega_menu_item_border_color_active', [
			'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .eael-mega-menu__item--active' => 'border-color: {{VALUE}};',
			],
		] );

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->add_control( 'eael_mega_menu_divider_heading', [
			'label'     => esc_html__( 'Divider', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$widget->add_control( 'eael_mega_menu_divider_style', [
			'label'     => esc_html__( 'Style', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			// Empty writes no custom property, which lets the stylesheet keep its
			// per-layout defaults: no divider on the bar, a hairline when collapsed.
			'default'   => '',
			'options'   => [
				''       => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
				'none'   => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
				'solid'  => esc_html__( 'Solid', 'essential-addons-for-elementor-lite' ),
				'dashed' => esc_html__( 'Dashed', 'essential-addons-for-elementor-lite' ),
				'dotted' => esc_html__( 'Dotted', 'essential-addons-for-elementor-lite' ),
			],
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-divider-style: {{VALUE}};',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_divider_width', [
			'label'      => esc_html__( 'Thickness', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-divider-width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [ 'eael_mega_menu_divider_style!' => 'none' ],
		] );

		$widget->add_control( 'eael_mega_menu_divider_color', [
			'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-divider-color: {{VALUE}};',
			],
			'condition' => [ 'eael_mega_menu_divider_style!' => 'none' ],
		] );

		$widget->end_controls_section();
	}

	/**
	 * The collapsed (mobile) dropdown surface.
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	protected static function register_dropdown_section( Widget_Base $widget ) {
		// Scoped to the collapsed layout so the desktop wrapper stays untouched.
		$dropdown = '{{WRAPPER}} .eael-mega-menu--mobile .eael-mega-menu__container';

		$widget->start_controls_section( 'eael_mega_menu_dropdown_style_section', [
			'label'     => esc_html__( 'Mobile Dropdown', 'essential-addons-for-elementor-lite' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'eael_mega_menu_breakpoint!' => 'none' ],
		] );

		$widget->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'eael_mega_menu_dropdown_background',
			'types'    => [ 'classic', 'gradient' ],
			'exclude'  => [ 'image' ],
			'selector' => $dropdown,
		] );

		$widget->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'eael_mega_menu_dropdown_border',
			'selector' => $dropdown,
		] );

		$widget->add_responsive_control( 'eael_mega_menu_dropdown_radius', [
			'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'selectors'  => [
				$dropdown => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_dropdown_padding', [
			'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'selectors'  => [
				$dropdown => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_dropdown_offset', [
			'label'       => esc_html__( 'Distance From Menu', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'px', 'em', 'rem' ],
			'range'       => [ 'px' => [ 'min' => 0, 'max' => 120 ] ],
			'default'     => [ 'unit' => 'px', 'size' => 0 ],
			'description' => esc_html__( 'Push the dropdown clear of the bar it opens under. The sheet is anchored to the menu itself, so a menu inset inside a padded header bar opens over that padding until this is set.', 'essential-addons-for-elementor-lite' ),
			'selectors'   => [
				'{{WRAPPER}}' => '--eael-mm-dropdown-offset: {{SIZE}}{{UNIT}};',
			],
		] );

		$widget->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'eael_mega_menu_dropdown_shadow',
			'selector' => $dropdown,
		] );

		$widget->add_responsive_control( 'eael_mega_menu_dropdown_max_height', [
			'label'       => esc_html__( 'Max Height', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'px', 'vh' ],
			'range'       => [ 'px' => [ 'min' => 0, 'max' => 1200 ], 'vh' => [ 'min' => 0, 'max' => 100 ] ],
			'description' => esc_html__( 'Scroll the dropdown once it exceeds this height. Leave empty for no limit.', 'essential-addons-for-elementor-lite' ),
			'selectors'   => [
				$dropdown => 'max-height: {{SIZE}}{{UNIT}}; overflow-y: auto;',
			],
		] );

		$widget->end_controls_section();
	}

	/**
	 * Menu item icon.
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	protected static function register_icon_section( Widget_Base $widget ) {
		$widget->start_controls_section( 'eael_mega_menu_icon_style_section', [
			'label' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		// These controls style the per-item icon, which is opt-in: a menu item only
		// renders an icon once one is picked in Content > Menu Items > Icon. Without
		// that there is no icon element on the page and every control in this section
		// is a no-op, which reads as a broken Position control. Say so up front.
		$widget->add_control( 'eael_mega_menu_icon_notice', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => esc_html__( 'These options style the menu item icons. Pick an icon for an item under Content > Menu Items to see them take effect.', 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-descriptor',
		] );

		$widget->add_responsive_control( 'eael_mega_menu_icon_position', [
			'label'                => esc_html__( 'Position', 'essential-addons-for-elementor-lite' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => [
				'before' => [
					'title' => esc_html__( 'Before', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-h-align-left',
				],
				'above'  => [
					'title' => esc_html__( 'Above', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-v-align-top',
				],
				'after'  => [
					'title' => esc_html__( 'After', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'default'              => 'before',
			'selectors_dictionary' => [
				'before' => '--eael-mm-item-direction: row; --eael-mm-icon-order: 0;',
				'after'  => '--eael-mm-item-direction: row; --eael-mm-icon-order: 5;',
				'above'  => '--eael-mm-item-direction: column; --eael-mm-icon-order: 0;',
			],
			'selectors'            => [
				'{{WRAPPER}}' => '{{VALUE}}',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_icon_size', [
			'label'      => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 6, 'max' => 100 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 16 ],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-icon-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_icon_gap', [
			'label'      => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 8 ],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-icon-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$widget->start_controls_tabs( 'eael_mega_menu_icon_style_tabs' );

		$widget->start_controls_tab( 'eael_mega_menu_icon_normal_tab', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_icon_color', [
			'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-icon-color: {{VALUE}};',
			],
		] );

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'eael_mega_menu_icon_hover_tab', [
			'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_icon_color_hover', [
			'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-icon-color-hover: {{VALUE}};',
			],
		] );

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'eael_mega_menu_icon_active_tab', [
			'label' => esc_html__( 'Active', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_icon_color_active', [
			'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-icon-color-active: {{VALUE}};',
			],
		] );

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->end_controls_section();
	}

	/**
	 * Submenu indicator arrow.
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	protected static function register_indicator_section( Widget_Base $widget ) {
		$widget->start_controls_section( 'eael_mega_menu_indicator_style_section', [
			'label'     => esc_html__( 'Submenu Indicator', 'essential-addons-for-elementor-lite' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'eael_mega_menu_indicator_icon[value]!' => '' ],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_indicator_size', [
			'label'      => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 6, 'max' => 60 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 12 ],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-indicator-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_indicator_gap', [
			'label'      => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 6 ],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-indicator-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		$widget->add_control( 'eael_mega_menu_indicator_rotate', [
			'label'        => esc_html__( 'Rotate When Open', 'essential-addons-for-elementor-lite' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => 'yes',
			'return_value' => 'yes',
			'selectors'    => [
				'{{WRAPPER}}' => '--eael-mm-indicator-rotate: 180deg;',
			],
		] );

		$widget->start_controls_tabs( 'eael_mega_menu_indicator_style_tabs' );

		$widget->start_controls_tab( 'eael_mega_menu_indicator_normal_tab', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_indicator_color', [
			'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-indicator-color: {{VALUE}};',
			],
		] );

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'eael_mega_menu_indicator_active_tab', [
			'label' => esc_html__( 'Active', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_indicator_color_active', [
			'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-indicator-color-active: {{VALUE}};',
			],
		] );

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->end_controls_section();
	}

	/**
	 * Submenu panel (the nested container wrapper).
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	protected static function register_panel_section( Widget_Base $widget ) {
		$panel_selector = '{{WRAPPER}} .eael-mega-menu__panel';

		$widget->start_controls_section( 'eael_mega_menu_panel_style_section', [
			'label' => esc_html__( 'Submenu Panel', 'essential-addons-for-elementor-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		// Scoped to the inline built panels — the ones whose surface the widget
		// owns. A saved template or a borrowed section arrives with its own
		// design, so painting a default behind it would only show at the edges.
		//
		// The default is set on the control rather than in the stylesheet so it
		// is visible in the panel: users see the colour they are getting and can
		// change or clear it, instead of hunting for a value they cannot find.
		$widget->add_group_control( Group_Control_Background::get_type(), [
			'name'           => 'eael_mega_menu_panel_background',
			'types'          => [ 'classic', 'gradient' ],
			'selector'       => '{{WRAPPER}} .eael-mega-menu__panel--inline',
			'fields_options' => [
				'background' => [
					'default'     => 'classic',
					'description' => esc_html__( 'Applies to menu items built inline. Saved Template and Section CSS ID items keep their own background.', 'essential-addons-for-elementor-lite' ),
				],
				'color'      => [
					'default' => '#F9F8F6',
				],
			],
		] );

		$widget->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'eael_mega_menu_panel_border',
			'selector' => $panel_selector,
		] );

		$widget->add_responsive_control( 'eael_mega_menu_panel_radius', [
			'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'selectors'  => [
				$panel_selector => '--border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$widget->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'eael_mega_menu_panel_shadow',
			'selector' => $panel_selector,
		] );

		$widget->add_responsive_control( 'eael_mega_menu_panel_padding', [
			'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'selectors'  => [
				$panel_selector => '--padding-top: {{TOP}}{{UNIT}}; --padding-right: {{RIGHT}}{{UNIT}}; --padding-bottom: {{BOTTOM}}{{UNIT}}; --padding-left: {{LEFT}}{{UNIT}};',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_panel_offset', [
			'label'      => esc_html__( 'Distance From Menu', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 120 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 0 ],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-panel-offset: {{SIZE}}{{UNIT}};',
			],
		] );

		$widget->add_control( 'eael_mega_menu_panel_z_index', [
			'label'     => esc_html__( 'Z-Index', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 0,
			'max'       => 99999,
			'default'   => 999,
			'description' => esc_html__( 'Raise this if the submenu is covered by other content in a sticky header.', 'essential-addons-for-elementor-lite' ),
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-panel-z-index: {{VALUE}};',
			],
		] );

		$widget->end_controls_section();
	}

	/**
	 * Mobile toggle button.
	 *
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	protected static function register_toggle_section( Widget_Base $widget ) {
		$widget->start_controls_section( 'eael_mega_menu_toggle_style_section', [
			'label'     => esc_html__( 'Mobile Toggle', 'essential-addons-for-elementor-lite' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'eael_mega_menu_breakpoint!' => 'none' ],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_toggle_align', [
			'label'     => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'flex-start' => [
					'title' => esc_html__( 'Start', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-start-h',
				],
				'center'     => [
					'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-center-h',
				],
				'flex-end'   => [
					'title' => esc_html__( 'End', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-align-end-h',
				],
			],
			'default'   => 'flex-end',
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-toggle-align: {{VALUE}};',
			],
		] );

		$widget->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'eael_mega_menu_toggle_typography',
			'selector' => '{{WRAPPER}} .eael-mega-menu__toggle',
		] );

		$widget->add_responsive_control( 'eael_mega_menu_toggle_icon_size', [
			'label'      => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem' ],
			'range'      => [ 'px' => [ 'min' => 8, 'max' => 80 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 20 ],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-toggle-icon-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_toggle_padding', [
			'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'default'    => [
				'top'      => '10',
				'right'    => '14',
				'bottom'   => '10',
				'left'     => '14',
				'unit'     => 'px',
				'isLinked' => false,
			],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-toggle-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$widget->add_responsive_control( 'eael_mega_menu_toggle_radius', [
			'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem' ],
			'selectors'  => [
				'{{WRAPPER}}' => '--eael-mm-toggle-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$widget->start_controls_tabs( 'eael_mega_menu_toggle_style_tabs' );

		$widget->start_controls_tab( 'eael_mega_menu_toggle_normal_tab', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_toggle_color', [
			'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-toggle-color: {{VALUE}};',
			],
		] );

		$widget->add_control( 'eael_mega_menu_toggle_bg', [
			'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-toggle-bg: {{VALUE}};',
			],
		] );

		$widget->end_controls_tab();

		$widget->start_controls_tab( 'eael_mega_menu_toggle_hover_tab', [
			'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );

		$widget->add_control( 'eael_mega_menu_toggle_color_hover', [
			'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-toggle-color-hover: {{VALUE}};',
			],
		] );

		$widget->add_control( 'eael_mega_menu_toggle_bg_hover', [
			'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--eael-mm-toggle-bg-hover: {{VALUE}};',
			],
		] );

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->add_group_control( Group_Control_Border::get_type(), [
			'name'      => 'eael_mega_menu_toggle_border',
			'selector'  => '{{WRAPPER}} .eael-mega-menu__toggle',
			'separator' => 'before',
		] );

		$widget->end_controls_section();
	}
}

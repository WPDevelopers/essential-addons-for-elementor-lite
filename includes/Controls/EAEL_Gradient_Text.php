<?php

namespace Essential_Addons_Elementor\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * EAEL Gradient Text group control.
 *
 * A self-contained group control that provides both a classic single-color
 * picker and a full gradient builder for text, selected via an internal
 * `color_type` CHOOSE field.
 *
 * Classic mode  → outputs `color: {{VALUE}}` only.
 * Gradient mode → outputs `background-image`, `-webkit-background-clip`,
 *                 `background-clip: text`, and `color: transparent`.
 *                 The first color (`color`) also emits `color: {{VALUE}}`
 *                 as the automatic browser fallback (no separate field needed).
 *
 * Default behaviour (nothing passed in `fields_options`):
 *   classic mode, single color picker only.
 *
 * To default to gradient mode pass via `fields_options`:
 *   'color_type' => [ 'default' => 'gradient' ]
 *
 * Usage:
 *
 *   // Classic default (single color)
 *   $this->add_group_control(
 *       \Essential_Addons_Elementor\Controls\EAEL_Gradient_Text::get_type(),
 *       [
 *           'name'           => 'my_text',
 *           'selector'       => '{{WRAPPER}} .my-el',
 *           'fields_options' => [
 *               'color' => [ 'default' => '#4d4d4d' ],
 *           ],
 *       ]
 *   );
 *
 *   // Gradient default
 *   $this->add_group_control(
 *       \Essential_Addons_Elementor\Controls\EAEL_Gradient_Text::get_type(),
 *       [
 *           'name'           => 'my_text',
 *           'selector'       => '{{WRAPPER}} .my-el',
 *           'fields_options' => [
 *               'color_type' => [ 'default' => 'gradient' ],
 *               'color'      => [ 'default' => '#4d4d4d' ],
 *               'color_b'    => [ 'default' => '#4d4d4d' ],
 *           ],
 *       ]
 *   );
 *
 * @since 6.6.0
 */
class EAEL_Gradient_Text extends Group_Control_Base {

	/**
	 * Holds the control fields.
	 *
	 * @since 6.6.0
	 * @access protected
	 * @static
	 *
	 * @var array
	 */
	protected static $fields;

	/**
	 * Return the unique type key for this group control.
	 *
	 * @since 6.6.0
	 * @access public
	 * @static
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'eael-gradient-text';
	}

	/**
	 * Initialize the control fields.
	 *
	 * @since 6.6.0
	 * @access public
	 *
	 * @return array
	 */
	public function init_fields() {
		$fields = [];

		// ── Color type picker ─────────────────────────────────────────────────
		// Drives classic/gradient visibility. Default is 'classic' so the control
		// behaves as a plain color picker unless opted into gradient mode.
		$fields['color_type'] = [
			'label'       => esc_html__( 'Color Type', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::CHOOSE,
			'options'     => [
				'classic'  => [
					'title' => esc_html__( 'Classic', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-paint-brush',
				],
				'gradient' => [
					'title' => esc_html__( 'Gradient', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'eicon-barcode',
				],
			],
			'default'     => 'classic',
			'render_type' => 'ui',
		];

		// ── First / only color ────────────────────────────────────────────────
		// Shown in both modes.
		// - Classic: its `color: {{VALUE}}` selector is the sole CSS output.
		// - Gradient: acts as the first gradient stop AND as the automatic
		//   browser fallback (overridden by `color: transparent` in modern
		//   browsers that support background-clip: text).
		$fields['color'] = [
			'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#6d3be4',
			'selectors' => [
				'{{SELECTOR}}' => 'color: {{VALUE}};',
			],
		];

		// ── Gradient-only fields ──────────────────────────────────────────────

		$fields['color_stop'] = [
			'label'       => esc_html__( 'Location', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ '%', 'custom' ],
			'default'     => [
				'unit' => '%',
				'size' => 0,
			],
			'render_type' => 'ui',
			'condition'   => [
				'color_type' => 'gradient',
			],
		];

		$fields['color_b'] = [
			'label'       => esc_html__( 'Second Color', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#f2295b',
			'render_type' => 'ui',
			'condition'   => [
				'color_type' => 'gradient',
			],
		];

		$fields['color_b_stop'] = [
			'label'       => esc_html__( 'Location', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ '%', 'custom' ],
			'default'     => [
				'unit' => '%',
				'size' => 100,
			],
			'render_type' => 'ui',
			'condition'   => [
				'color_type' => 'gradient',
			],
		];

		$fields['gradient_type'] = [
			'label'       => esc_html__( 'Type', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'linear' => esc_html__( 'Linear', 'essential-addons-for-elementor-lite' ),
				'radial' => esc_html__( 'Radial', 'essential-addons-for-elementor-lite' ),
			],
			'default'     => 'linear',
			'render_type' => 'ui',
			'condition'   => [
				'color_type' => 'gradient',
			],
		];

		// Linear gradient — outputs full text-clip CSS.
		$fields['gradient_angle'] = [
			'label'      => esc_html__( 'Angle', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'deg', 'grad', 'rad', 'turn', 'custom' ],
			'default'    => [
				'unit' => 'deg',
				'size' => 135,
			],
			'selectors'  => [
				'{{SELECTOR}}' => implode( '; ', [
					'background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
					'-webkit-background-clip: text',
					'background-clip: text',
					'color: transparent',
				] ),
			],
			'condition'  => [
				'color_type'    => 'gradient',
				'gradient_type' => 'linear',
			],
		];

		// Radial gradient — outputs full text-clip CSS.
		$fields['gradient_position'] = [
			'label'     => esc_html__( 'Position', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'center center' => esc_html__( 'Center Center', 'essential-addons-for-elementor-lite' ),
				'center left'   => esc_html__( 'Center Left', 'essential-addons-for-elementor-lite' ),
				'center right'  => esc_html__( 'Center Right', 'essential-addons-for-elementor-lite' ),
				'top center'    => esc_html__( 'Top Center', 'essential-addons-for-elementor-lite' ),
				'top left'      => esc_html__( 'Top Left', 'essential-addons-for-elementor-lite' ),
				'top right'     => esc_html__( 'Top Right', 'essential-addons-for-elementor-lite' ),
				'bottom center' => esc_html__( 'Bottom Center', 'essential-addons-for-elementor-lite' ),
				'bottom left'   => esc_html__( 'Bottom Left', 'essential-addons-for-elementor-lite' ),
				'bottom right'  => esc_html__( 'Bottom Right', 'essential-addons-for-elementor-lite' ),
			],
			'default'   => 'center center',
			'selectors' => [
				'{{SELECTOR}}' => implode( '; ', [
					'background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
					'-webkit-background-clip: text',
					'background-clip: text',
					'color: transparent',
				] ),
			],
			'condition' => [
				'color_type'    => 'gradient',
				'gradient_type' => 'radial',
			],
		];

		return $fields;
	}

	/**
	 * Default options for this group control.
	 *
	 * Renders fields inline in the panel (no popover toggle button).
	 *
	 * @since 6.6.0
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_default_options() {
		return [
			'popover' => false,
		];
	}
}

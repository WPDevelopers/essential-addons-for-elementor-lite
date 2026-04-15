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
 * Applies a CSS gradient to text via background-clip: text + color: transparent.
 * Outputs background-image (linear or radial gradient), -webkit-background-clip,
 * background-clip, and color: transparent to the target selector.
 *
 * Fallback: A solid "Fallback Color" field sets `color` on the same selector.
 * Since the gradient rules appear after it in the stylesheet, browsers that
 * fully support background-clip:text will display the gradient; all modern
 * browsers (Chrome 4+, Firefox 3.5+, Safari 3.1+, Edge 12+) do.
 *
 * Usage in a widget:
 *
 *   $this->add_group_control(
 *       \Essential_Addons_Elementor\Controls\EAEL_Gradient_Text::get_type(),
 *       [
 *           'name'     => 'heading_gradient',
 *           'selector' => '{{WRAPPER}} .my-heading',
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

		// ── First gradient color ──────────────────────────────────────────────
		// Also outputs `color: {{VALUE}}` so it doubles as the automatic fallback
		// for browsers that do not support background-clip: text — no separate
		// Fallback Color field is needed.
		$fields['color'] = [
			'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#6d3be4',
			'selectors' => [
				'{{SELECTOR}}' => 'color: {{VALUE}};',
			],
		];

		$fields['color_stop'] = [
			'label'       => esc_html__( 'Location', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ '%', 'custom' ],
			'default'     => [
				'unit' => '%',
				'size' => 0,
			],
			'render_type' => 'ui',
		];

		// ── Second gradient color ─────────────────────────────────────────────
		$fields['color_b'] = [
			'label'       => esc_html__( 'Second Color', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#f2295b',
			'render_type' => 'ui',
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
		];

		// ── Gradient type ─────────────────────────────────────────────────────
		$fields['gradient_type'] = [
			'label'       => esc_html__( 'Type', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'linear' => esc_html__( 'Linear', 'essential-addons-for-elementor-lite' ),
				'radial' => esc_html__( 'Radial', 'essential-addons-for-elementor-lite' ),
			],
			'default'     => 'linear',
			'render_type' => 'ui',
		];

		// ── Linear: angle ─────────────────────────────────────────────────────
		// Outputs all four text-clip CSS properties so the gradient is visible
		// on the text in every modern browser.
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
				'gradient_type' => 'linear',
			],
		];

		// ── Radial: origin position ───────────────────────────────────────────
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
				'gradient_type' => 'radial',
			],
		];

		return $fields;
	}

	/**
	 * Default options for this group control.
	 *
	 * Disables the popover wrapper so all fields render inline in the
	 * Elementor panel without an extra toggle button.
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

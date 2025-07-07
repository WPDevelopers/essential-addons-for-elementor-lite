<?php
namespace Essential_Addons_Elementor\Extensions;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Liquid_Glass_Effect {

	/**
	 * Initialize hooks
	 */
	public function __construct() {
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/frontend/before_render', [ $this, 'before_render' ], 100 );
	}

	public function register_controls( $element ) {
		$element->start_controls_section(
			'eael_liquid_glass_effect_section',
			[
				'label' => __( '<i class="eaicon-logo"></i> Liquid Glass Effect', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_ADVANCED
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_switch',
			[
				'label' => __( 'Enable Liquid Glass Effect', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect',
			[
				'label'   => esc_html__( 'Liquid Glass Effects', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'effect1',
				'options' => [
					'effect1' => esc_html__( 'Effect 1', 'essential-addons-for-elementor-lite' ),
					'effect2' => esc_html__( 'Effect 2', 'essential-addons-for-elementor-lite' ),
					'effect3' => esc_html__( 'Effect 3', 'essential-addons-for-elementor-lite' ),
					'effect4' => esc_html__( 'Effect 4', 'essential-addons-for-elementor-lite' ),
					'effect5' => esc_html__( 'Effect 5', 'essential-addons-for-elementor-lite' ),
					'effect6' => esc_html__( 'Effect 6', 'essential-addons-for-elementor-lite' ),
				],
				'prefix_class' => 'eael_liquid_glass-',
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
				]
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_settings',
			[
				'label'     => esc_html__( 'Liquid Glass Settings', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
				]
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_bg_color',
			[
				'label'     => esc_html__( 'Bankground Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect1, 
					{{WRAPPER}}.eael_liquid_glass-effect2, 
					{{WRAPPER}}.eael_liquid_glass-effect3, 
					{{WRAPPER}}.eael_liquid_glass-effect4::before, 
					{{WRAPPER}}.eael_liquid_glass-effect5::before, 
					{{WRAPPER}}.eael_liquid_glass-effect6::before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_opacity',
			[
				'label' => esc_html__( 'Opacity', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 1,
						'step' => .1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect1, 
					{{WRAPPER}}.eael_liquid_glass-effect2, 
					{{WRAPPER}}.eael_liquid_glass-effect3, 
					{{WRAPPER}}.eael_liquid_glass-effect4::before, 
					{{WRAPPER}}.eael_liquid_glass-effect5::before, 
					{{WRAPPER}}.eael_liquid_glass-effect6::before' => 'opacity: {{SIZE}}',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_backdrop_filter',
			[
				'label' => esc_html__( 'Backdrop Filter', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect1, 
					{{WRAPPER}}.eael_liquid_glass-effect2, 
					{{WRAPPER}}.eael_liquid_glass-effect3, 
					{{WRAPPER}}.eael_liquid_glass-effect4::before, 
					{{WRAPPER}}.eael_liquid_glass-effect5::before, 
					{{WRAPPER}}.eael_liquid_glass-effect6::before' => 'backdrop-filter: blur({{SIZE}}px)',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_shadow_effect',
			[
				'label'     => esc_html__( 'Shadow Effects', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'effect1',
				'separator' => 'before',
				'options'   => [
					'' 		 => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
					'effect1' => esc_html__( 'Effect 1', 'essential-addons-for-elementor-lite' ),
					'effect2' => esc_html__( 'Effect 2', 'essential-addons-for-elementor-lite' ),
					'effect3' => esc_html__( 'Effect 3', 'essential-addons-for-elementor-lite' ),
					'effect4' => esc_html__( 'Effect 4', 'essential-addons-for-elementor-lite' ),
				],
				'prefix_class' => 'eael_liquid_glass_shadow-',
				'condition'    => [
					'eael_liquid_glass_effect_switch' => 'yes',
				]
			]
		);

		$element->add_control(
			'eael_liquid_glass_shadow_inner',
			[
				'label'     => esc_html__( 'Shadow Settings', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_liquid_glass_effect_switch'  => 'yes',
					'eael_liquid_glass_shadow_effect!' => '',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'eael_liquid_glass_shadow_color',
				'selector'  => '{{WRAPPER}}.eael_liquid_glass_shadow-effect1, {{WRAPPER}}.eael_liquid_glass_shadow-effect2, {{WRAPPER}}.eael_liquid_glass_shadow-effect3, {{WRAPPER}}.eael_liquid_glass_shadow-effect4',
				'condition' => [
					'eael_liquid_glass_effect_switch'  => 'yes',
					'eael_liquid_glass_shadow_effect!' => '',
				],
			]
		);

		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$settings = $element->get_settings_for_display();
	}
}
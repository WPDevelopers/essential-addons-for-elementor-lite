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
				'type'    => Controls_Manager::SELECT2,
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
			'eael_liquid_glass_effect_backdrop_filter_effect1',
			[
				'label' => esc_html__( 'Backdrop Filter', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect1' => 'backdrop-filter: blur({{SIZE}}px)',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect' => 'effect1',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_backdrop_filter_effect2',
			[
				'label' => esc_html__( 'Backdrop Filter', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect2' => 'backdrop-filter: blur({{SIZE}}px) brightness(1.1) saturate(1.5)',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect'        => 'effect2',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_backdrop_filter_effect3',
			[
				'label' => esc_html__( 'Backdrop Filter', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect3' => 'backdrop-filter: blur({{SIZE}}px)',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect'        => 'effect3',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_backdrop_filter_effect4',
			[
				'label' => esc_html__( 'Backdrop Filter', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect4::before' => 'backdrop-filter: blur({{SIZE}}px)',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect'        => 'effect4',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_backdrop_filter_effect5',
			[
				'label' => esc_html__( 'Backdrop Filter', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect5::before' => 'backdrop-filter: blur({{SIZE}}px)',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect'        => 'effect5',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_backdrop_filter_effect6',
			[
				'label' => esc_html__( 'Backdrop Filter', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect6::before' => 'backdrop-filter: blur({{SIZE}}px)',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect'        => 'effect6',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_noise_settings',
			[
				'label'     => esc_html__( 'Noise Distortion Settings', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect' => ['effect4', 'effect5', 'effect6'],
				]
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_noise_freq',
			[
				'label' => esc_html__( 'Noise Freq', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 0.02,
						'step' => 0.001,
					],
				],
				'default' => [
					'size' => 0.008,
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect' => ['effect4', 'effect5', 'effect6'],
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_noise_strength',
			[
				'label' => esc_html__( 'Strength', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 77,
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect' => ['effect4', 'effect5', 'effect6'],
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
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'eael_liquid_glass_border_radius_effect1',
				'selector'  => '{{WRAPPER}}.eael_liquid_glass_shadow-effect1',
				'condition' => [
					'eael_liquid_glass_effect_switch'  => 'yes',
					'eael_liquid_glass_shadow_effect' => 'effect1',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_border_radius_effect1',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}}.eael_liquid_glass_shadow-effect1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_shadow_effect' => 'effect1',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'eael_liquid_glass_shadow_effect1',
				'fields_options'     => [
					'box_shadow_type' => [ 'default' => 'yes' ],
					'box_shadow'      => [
						'default'      => [
							'color'      => 'rgba(0,0,0,0.78)',
							'horizontal' => -1,
							'vertical'   => 9,
							'blur'       => 28,
							'spread'     => 2,
						],
					],
				],
				'selector'  => '{{WRAPPER}}.eael_liquid_glass_shadow-effect1',
				'condition' => [
					'eael_liquid_glass_effect_switch'  => 'yes',
					'eael_liquid_glass_shadow_effect' => 'effect1',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'eael_liquid_glass_border_radius_effect2',
				'selector'  => '{{WRAPPER}}.eael_liquid_glass_shadow-effect2',
				'condition' => [
					'eael_liquid_glass_effect_switch'  => 'yes',
					'eael_liquid_glass_shadow_effect' => 'effect2',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_border_radius_effect2',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}}.eael_liquid_glass_shadow-effect2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_shadow_effect' => 'effect2',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'eael_liquid_glass_shadow_effect2',
				'fields_options'     => [
					'box_shadow_type' => [ 'default' => 'yes' ],
					'box_shadow'      => [
						'default'      => [
							'color'      => 'rgb(165 165 165 / 78%)',
							'horizontal' => 0,
							'vertical'   => 0,
							'blur'       => 8,
							'spread'     => 1,
						],
					],
				],
				'selector'  => '{{WRAPPER}}.eael_liquid_glass_shadow-effect2',
				'condition' => [
					'eael_liquid_glass_effect_switch'  => 'yes',
					'eael_liquid_glass_shadow_effect' => 'effect2',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'eael_liquid_glass_border_radius_effect3',
				'selector'  => '{{WRAPPER}}.eael_liquid_glass_shadow-effect3',
				'condition' => [
					'eael_liquid_glass_effect_switch'  => 'yes',
					'eael_liquid_glass_shadow_effect' => 'effect3',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_border_radius_effect3',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}}.eael_liquid_glass_shadow-effect3' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_shadow_effect' => 'effect3',
				],
			]
		);
		
		$element->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'eael_liquid_glass_shadow_effect3',
				'fields_options'     => [
					'box_shadow_type' => [ 'default' => 'yes' ],
					'box_shadow_position' => [ 'default' => 'inset' ],
					'box_shadow'      => [
						'default'      => [
							'color'      => '#bebebe',
							'horizontal' =>1,
							'vertical'   =>1,
							'blur'       => 10,
							'spread'     => 5,
						],
					],
				],
				'selector'  => '{{WRAPPER}}.eael_liquid_glass_shadow-effect3',
				'condition' => [
					'eael_liquid_glass_effect_switch'  => 'yes',
					'eael_liquid_glass_shadow_effect' => 'effect3',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'eael_liquid_glass_border_effect4',
				'selector' => '{{WRAPPER}}.eael_liquid_glass_shadow-effect4',
				'fields_options' => [
					'border' => [
						'default' => 'solid',
					],
					'width' => [
						'default' => [
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'isLinked' =>  false,
						],
					],
					'color' => [
						'default' => '#808080',
					],
				],
				'condition' => [
					'eael_liquid_glass_effect_switch'  => 'yes',
					'eael_liquid_glass_shadow_effect' => 'effect4',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_border_radius_effect4',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}}.eael_liquid_glass_shadow-effect4' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_shadow_effect' => 'effect4',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'eael_liquid_glass_shadow_effect4',
				'selector'  => '{{WRAPPER}}.eael_liquid_glass_shadow-effect4',
				'condition' => [
					'eael_liquid_glass_effect_switch'  => 'yes',
					'eael_liquid_glass_shadow_effect' => 'effect4',
				],
			]
		);

		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$settings = $element->get_settings_for_display();

		if( !empty( $settings['eael_liquid_glass_effect_noise_strength']['size'] ) || !empty( $settings['eael_liquid_glass_effect_noise_freq']['size'] ) ) {
			$strength_settings = [
				'scale' => $settings['eael_liquid_glass_effect_noise_strength']['size'],
				'freq' => $settings['eael_liquid_glass_effect_noise_freq']['size'],
			];
			$element->add_render_attribute( '_wrapper', 'data-eael_glass_effects', wp_json_encode( $strength_settings ) );
		}
	}
}
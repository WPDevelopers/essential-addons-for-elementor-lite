<?php
namespace Essential_Addons_Elementor\Extensions;
use Elementor\Controls_Manager;
use Essential_Addons_Elementor\Traits\Helper as HelperTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Liquid_Glass_Effect {
	use HelperTrait;
	/**
	 * Initialize hooks
	 */
	public function __construct() {
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/frontend/before_render', [ $this, 'before_render' ], 100 );
	}

	public function eael_liquid_glass_effect_bg_color_effect( $element, $effect, $default_bg_color ) {
		$element->add_control(
			'eael_liquid_glass_effect_bg_color_' . $effect,
			[
				'label'     => esc_html__( 'Bankground Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $default_bg_color,
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-'.$effect => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect'        => $effect,
				],
			]
		);
	}

	public function eael_liquid_glass_effect_backdrop_filter( $element, $effect, $default_size ) {
		$element->add_control(
			'eael_liquid_glass_effect_backdrop_filter_'.$effect,
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
					'size' => $default_size,
				],
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-'.$effect => 'backdrop-filter: blur({{SIZE}}px)',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect' => $effect,
				],
			]
		);
	}

	public function eael_liquid_glass_effect_border( $element, $effect, $default_color ) {
		$element->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'eael_liquid_glass_border_'.$effect,
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
						'default' => $default_color,
					],
				],
				'selector'  => '{{WRAPPER}}.eael_liquid_glass_shadow-'.$effect,
				'condition' => [
					'eael_liquid_glass_effect_switch'  => 'yes',
					'eael_liquid_glass_shadow_effect' => $effect,
				],
			]
		);
	}

	public function eael_liquid_glass_effect_border_radius( $element, $effect, $default_radius ) {
		$element->add_control(
			'eael_liquid_glass_border_radius_'.$effect,
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'default' => $default_radius,
				'selectors'  => [
					'{{WRAPPER}}.eael_liquid_glass_shadow-'.$effect => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_shadow_effect' => $effect,
				],
			]
		);
	}

	public function eael_liquid_glass_effect_box_shadow( $element, $effect, $default_shadow ) {
		$element->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'eael_liquid_glass_shadow_'.$effect,
				'fields_options'     => [
					'box_shadow_type' => [ 'default' => 'yes' ],
					'box_shadow'      => [
						'default'      => $default_shadow,
					],
				],
				'selector'  => '{{WRAPPER}}.eael_liquid_glass_shadow-'.$effect,
				'condition' => [
					'eael_liquid_glass_effect_switch'  => 'yes',
					'eael_liquid_glass_shadow_effect' => $effect,
				],
			]
		);
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
			'eael_liquid_glass_effect_notice',
			[
				'type'        => Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'dismissible' => false,
				'heading'     => esc_html__( 'Important: ', 'essential-addons-for-elementor-lite' ),
				'content'     => esc_html__( 'The effect will be noticeable only if a semi-transparent background color is used.', 'essential-addons-for-elementor-lite' ) . ' <a href = "#">' . esc_html__( 'Learn More', 'essential-addons-for-elementor-lite' ) . '</a>',
				'condition'   => [
					'eael_liquid_glass_effect_switch' => 'yes',
				]
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

		// $element->add_control(
		// 	'eael_liquid_glass_effect_bg_color',
		// 	[
		// 		'label'     => esc_html__( 'Bankground Color', 'essential-addons-for-elementor-lite' ),
		// 		'type'      => Controls_Manager::COLOR,
		// 		'selectors' => [
		// 			'{{WRAPPER}}.eael_liquid_glass-effect1, 
		// 			{{WRAPPER}}.eael_liquid_glass-effect2, 
		// 			{{WRAPPER}}.eael_liquid_glass-effect3, 
		// 			{{WRAPPER}}.eael_liquid_glass-effect4::before, 
		// 			{{WRAPPER}}.eael_liquid_glass-effect5::before, 
		// 			{{WRAPPER}}.eael_liquid_glass-effect6::before' => 'background-color: {{VALUE}}',
		// 		],
		// 		'condition' => [
		// 			'eael_liquid_glass_effect_switch' => 'yes',
		// 		],
		// 	]
		// );

		// Background Color Controls
		$this->eael_liquid_glass_effect_bg_color_effect( $element, 'effect1', '#FFFFFF1F' );
		$this->eael_liquid_glass_effect_bg_color_effect( $element, 'effect2', '' );
		$this->eael_liquid_glass_effect_bg_color_effect( $element, 'effect3', '#FFFFFF1F' );

		$element->add_control(
			'eael_liquid_glass_effect_bg_color_effect4',
			[
				'label'     => esc_html__( 'Bankground Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect4::before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect'        => 'effect4',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_bg_color_effect5',
			[
				'label'     => esc_html__( 'Bankground Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect5::before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect'        => 'effect5',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_bg_color_effect6',
			[
				'label'     => esc_html__( 'Bankground Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect6::before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect'        => 'effect6',
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

		// Backdrop Filter Controls
		$this->eael_liquid_glass_effect_backdrop_filter( $element, 'effect1', 24 );
		$this->eael_liquid_glass_effect_backdrop_filter( $element, 'effect2', 20 );
		$this->eael_liquid_glass_effect_backdrop_filter( $element, 'effect3', 16 );

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
				'default' => [
					'size' => 5,
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
				'default' => [
					'size' => 7,
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
			'eael_liquid_glass_effect_noise_freq_effect4',
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
					'size' => 0.009,
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect' => 'effect4',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_noise_freq_effect5',
			[
				'label' => esc_html__( 'Noise Freq', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 5,
						'step' => 0.001,
					],
				],
				'default' => [
					'size' => 1.2,
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect' => 'effect5',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_noise_freq_effect6',
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
					'size' => 0.02,
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect' => 'effect6',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_noise_strength_effect4',
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
					'eael_liquid_glass_effect' => 'effect4',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_noise_strength_effect5',
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
					'size' => 70,
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect' => 'effect5',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_noise_strength_effect6',
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
					'size' => 30,
				],
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect' => 'effect6',
				],
			]
		);

		$element->add_control(
			'eael_liquid_glass_shadow_effect',
			[
				'label'     => esc_html__( 'Shadow Effects', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT2,
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

		// Add border effect for Liquid Glass Effect
		$this->eael_liquid_glass_effect_border( $element, 'effect1', '#FFFFFF1F' );
		$this->eael_liquid_glass_effect_border( $element, 'effect2', '#FFFFFF1F' );
		$this->eael_liquid_glass_effect_border( $element, 'effect3', '#FFFFFF1F' );
		$this->eael_liquid_glass_effect_border( $element, 'effect4', '#AAAAAA1A' );

		// Add border radius effect for Liquid Glass Effect
		$this->eael_liquid_glass_effect_border_radius( $element, 'effect1', [
			'top'      => 24,
			'right'    => 24,
			'bottom'   => 24,
			'left'     => 24,
			'unit'     => 'px',
			'isLinked' => true,
		] );

		$this->eael_liquid_glass_effect_border_radius( $element, 'effect2', [
			'top' 	  => 16,
			'right'    => 16,
			'bottom'   => 16,
			'left'     => 16,
			'unit'     => 'px',
			'isLinked' => true,
		] );

		$this->eael_liquid_glass_effect_border_radius( $element, 'effect3', [
			'top' 	  => 8,
			'right'    => 8,
			'bottom'   => 8,
			'left'     => 8,
			'unit'     => 'px',
			'isLinked' => true,
		] );

		$this->eael_liquid_glass_effect_border_radius( $element, 'effect4', [
			'top' 	  => 24,
			'right'    => 24,
			'bottom'   => 24,
			'left'     => 24,
			'unit'     => 'px',
			'isLinked' => true,
		] );

		// Add box shadow effect for Liquid Glass Effect
		$this->eael_liquid_glass_effect_box_shadow( $element, 'effect1', [
			'color'      => 'rgba(0,0,0,0.78)',
			'horizontal' => 0,
			'vertical'   => 19,
			'blur'       => 26,
			'spread'     => 1,
		] );

		$this->eael_liquid_glass_effect_box_shadow( $element, 'effect2', [
			'color'      => '#383C65',
			'horizontal' => 0,
			'vertical'   => 0,
			'blur'       => 33,
			'spread'     => -2,
		] );

		$this->eael_liquid_glass_effect_box_shadow( $element, 'effect3', [
			'color'      => 'rgba(255, 255, 255, 0.4)',
			'horizontal' => 1,
			'vertical'   => 1,
			'blur'       => 10,
			'spread'     => 5,
		] );

		$this->eael_liquid_glass_effect_box_shadow( $element, 'effect4', [
			'color'      => '#00000040',
			'horizontal' => 0,
			'vertical'   => 9,
			'blur'       => 21,
			'spread'     => 0,
		] );

		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$settings = $element->get_settings_for_display();

		// Get the current effect type
		$effect_type = $settings['eael_liquid_glass_effect'] ?? '';

		// Initialize strength and freq variables
		$strength = null;
		$freq = null;

		// Get effect-specific noise settings based on the selected effect
		switch( $effect_type ) {
			case 'effect4':
				$strength = $settings['eael_liquid_glass_effect_noise_strength_effect4']['size'] ?? null;
				$freq = $settings['eael_liquid_glass_effect_noise_freq_effect4']['size'] ?? null;
				break;
			case 'effect5':
				$strength = $settings['eael_liquid_glass_effect_noise_strength_effect5']['size'] ?? null;
				$freq = $settings['eael_liquid_glass_effect_noise_freq_effect5']['size'] ?? null;
				break;
			case 'effect6':
				$strength = $settings['eael_liquid_glass_effect_noise_strength_effect6']['size'] ?? null;
				$freq = $settings['eael_liquid_glass_effect_noise_freq_effect6']['size'] ?? null;
				break;
		}

		// Add render attribute if we have valid strength or freq values
		if( !empty( $strength ) || !empty( $freq ) ) {
			$strength_settings = [
				'scale' => $strength ?? 77, // Default value
				'freq' => $freq ?? 0.008,   // Default value
			];
			$element->add_render_attribute( '_wrapper', 'data-eael_glass_effects', wp_json_encode( $strength_settings ) );
		}
	}
}
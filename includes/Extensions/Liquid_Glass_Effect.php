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
		add_filter( 'elementor/widget/render_content', [ $this, 'eael_liquid_glass_effect_svg_render' ], 10, 2 );
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
				'label' => __( '<i class="eaicon-logo"></i> Liquid Glass Presets', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_ADVANCED
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_switch',
			[
				'label' => __( 'Enable Liquid Glass Presets', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

		$element->add_control(
			'eael_liquid_glass_effect_notice',
			[
					'type'        => Controls_Manager::ALERT,
					'alert_type'  => 'info',
					'content'     => esc_html__( 'Liquid glass effect is only visible when a semi-transparent background color is applied.', 'essential-addons-for-elementor-lite' ) . ' <a href = "#">' . esc_html__( 'Learn More', 'essential-addons-for-elementor-lite' ) . '</a>',
					'condition'   => [
						'eael_liquid_glass_effect_switch' => 'yes',
					]
			]
		);

		$eael_liquid_glass_effect = apply_filters(
			'eael_liquid_glass_effect_filter',
			[
					'styles' => [
						'effect1' => esc_html__( 'Heavy Frost', 'essential-addons-for-elementor-lite' ),
						'effect2' => esc_html__( 'Soft Mist', 'essential-addons-for-elementor-lite' ),
						'effect4' => esc_html__( 'Light Frost (Pro)', 'essential-addons-for-elementor-lite' ),
						'effect5' => esc_html__( 'Grain Frost (Pro)', 'essential-addons-for-elementor-lite' ),
						'effect6' => esc_html__( 'Fine Frost (Pro)', 'essential-addons-for-elementor-lite' ),
				],
				'conditions' => ['effect4', 'effect5', 'effect6'],
			]
      );

		$element->add_control(
			'eael_liquid_glass_effect',
			[
				'label'   => esc_html__( 'Liquid Glass Presets', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT2,
				'default' => 'effect1',
				'options' => $eael_liquid_glass_effect['styles'],
				'prefix_class' => 'eael_liquid_glass-',
				'condition' => [
					'eael_liquid_glass_effect_switch' => 'yes',
				]
			]
		);

		
		if ( !apply_filters('eael/pro_enabled', false ) ) {
			$element->add_control(
					'eael_liquid_glass_effect_pro_alert',
					[
						'label' 		=> sprintf( '<a class="eael_pro_alert" target="_blank" href="https://wpdeveloper.com/upgrade/ea-pro">%s</a>', esc_html__('Only Available in Pro Version!', 'essential-addons-for-elementor-lite')),
						'type'      => Controls_Manager::HEADING,
						'condition' => [
							'eael_liquid_glass_effect_switch' => 'yes',
							'eael_liquid_glass_effect'        => ['effect4', 'effect5', 'effect6'],
						]
				]
			);
		} else {
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
		}

		// Background Color Controls
		$this->eael_liquid_glass_effect_bg_color_effect( $element, 'effect1', '#FFFFFF1F' );
		$this->eael_liquid_glass_effect_bg_color_effect( $element, 'effect2', '#FFFFFF1F' );

		// Background Color Controls for Pro
		do_action( 'eael_liquid_glass_effect_bg_color_effect4', $element, 'effect4', '' );
		do_action( 'eael_liquid_glass_effect_bg_color_effect5', $element, 'effect5', '' );
		do_action( 'eael_liquid_glass_effect_bg_color_effect6', $element, 'effect6', '' );

		// Backdrop Filter Controls
		$this->eael_liquid_glass_effect_backdrop_filter( $element, 'effect1', 24 );
		$this->eael_liquid_glass_effect_backdrop_filter( $element, 'effect2', 20 );

		// Brightness Effect Controls
		$element->add_control(
			'eael_liquid_glass_effect_brightness_effect2',
			[
				'label' => esc_html__( 'Brightness', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 0,
						'max'  => 5,
						'step' => .1,
					],
				],
				'default' => [
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}.eael_liquid_glass-effect2' => 'backdrop-filter: blur({{eael_liquid_glass_effect_backdrop_filter_effect2.SIZE}}px) brightness({{SIZE}});',
				],
				'condition'    => [
					'eael_liquid_glass_effect_switch' => 'yes',
					'eael_liquid_glass_effect'        => 'effect2',
				]
			]
		);
		
		// Backdrop Filter Controls for Pro
		do_action( 'eael_liquid_glass_effect_backdrop_filter_effect4', $element, 'effect4', 5 );
		do_action( 'eael_liquid_glass_effect_backdrop_filter_effect5', $element, 'effect5', '' );
		do_action( 'eael_liquid_glass_effect_backdrop_filter_effect6', $element, 'effect6', 7 );

		// Noise Distortion Settings (Pro)
		do_action( 'eael_liquid_glass_effect_noise_action', $element );

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

		// Add border effect for Liquid Glass Presets
		$this->eael_liquid_glass_effect_border( $element, 'effect1', '#FFFFFF1F' );
		$this->eael_liquid_glass_effect_border( $element, 'effect2', '#FFFFFF1F' );
		$this->eael_liquid_glass_effect_border( $element, 'effect3', '#FFFFFF1F' );
		$this->eael_liquid_glass_effect_border( $element, 'effect4', '#AAAAAA1A' );

		// Add border radius effect for Liquid Glass Presets
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

		// Add box shadow effect for Liquid Glass Presets
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
	}

	public function eael_liquid_glass_effect_svg_render( $content, $element ) {
		$settings = $element->get_settings_for_display();
		do_action( 'eael_liquid_glass_effect_svg_pro', $element, $settings );

		return $content;
	}
}
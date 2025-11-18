<?php
namespace Essential_Addons_Elementor\Extensions;
use Elementor\Controls_Manager;
use Elementor\Element_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Vertical_Text_Orientation {
	/**
	 * Initialize hooks
	 */
	public function __construct() {
		// add_action( 'elementor/element/common/_section_style/before_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/frontend/before_render', [ $this, 'before_render' ], 100 );
        add_action( 'elementor/element/heading/section_title_style/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/text-editor/section_style/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/animated-headline/section_style_text/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/eael-dual-color-header/eael_section_dch_title_style_settings/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/eael-fancy-text/eael_fancy_text_suffix_styles/after_section_end', [ $this, 'register_controls' ] );
	}

	public function register_controls( $element ) {
		$element->start_controls_section(
			'eael_vertical_text_orientation_section',
			[
				'label' => __( '<i class="eaicon-logo"></i> Vertical Text Orientation', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$element->add_control(
			'eael_vertical_text_orientation_switch',
			[
				'label'        => __( 'Enable Vertical Text Orientation', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

        $element->add_control(
            'eael_vto_writing_mode',
            [
                'label'   => esc_html__( 'Writing Mode', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'vertical-lr',
                'options' => [
                    'vertical-lr' => esc_html__( 'Vertical LR', 'essential-addons-for-elementor-lite' ),
                    'vertical-rl' => esc_html__( 'Vertical RL', 'essential-addons-for-elementor-lite' ),
                ],
                'selectors' => [
                    '{{WRAPPER}}.eael_vto-vertical-lr, {{WRAPPER}}.eael_vto-vertical-rl' => 'writing-mode: {{VALUE}};',
                ],
                'prefix_class' => 'eael_vto-',
                'condition' => [
                    'eael_vertical_text_orientation_switch' => 'yes',
                ],
            ]
        );

        $element->add_control(
			'eael_vto_writing_mode_flip',
			[
				'label'        => esc_html__( 'Flip', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'selectors_dictionary' => [
					'yes' => 'rotate(180deg)',
				],
                'selectors' => [
                    '{{WRAPPER}}.eael_vto-vertical-lr .elementor-heading-title, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-heading-title, 
                    {{WRAPPER}}.eael_vto-vertical-lr .elementor-headline, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-headline, 
                    {{WRAPPER}}.eael_vto-vertical-lr .elementor-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-lr.elementor-widget-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-rl.elementor-widget-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-lr .eael-dual-header, 
                    {{WRAPPER}}.eael_vto-vertical-rl .eael-dual-header, 
                    {{WRAPPER}}.eael_vto-vertical-lr .eael-fancy-text-container, 
                    {{WRAPPER}}.eael_vto-vertical-rl .eael-fancy-text-container' => 'transform: {{VALUE}};',
                ],
				'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
				],
			]
		);

        $element->add_responsive_control(
			'eael_vto_writing_height',
			[
				'label'      => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.eael_vto-vertical-lr, {{WRAPPER}}.eael_vto-vertical-rl' => 'inline-size: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
				],
			]
		);

        $element->add_control(
			'eael_vto_writing_text_orientation',
			[
				'label'        => esc_html__( 'Upright Orientation', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'selectors_dictionary' => [
					'yes' => 'upright',
				],
                'selectors' => [
                    '{{WRAPPER}}.eael_vto-vertical-lr, {{WRAPPER}}.eael_vto-vertical-rl' => 'text-orientation: {{VALUE}};',
                ],
				'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
				],
			]
		);

        $element->add_responsive_control(
			'eael_vto_writing_letter_spacing',
			[
				'label'      => esc_html__( 'Letter Spacing', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.eael_vto-vertical-lr, {{WRAPPER}}.eael_vto-vertical-rl' => 'letter-spacing: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
				],
			]
		);

        $element->add_responsive_control(
			'eael_vto_writing_word_spacing',
			[
				'label'      => esc_html__( 'Word Spacing', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.eael_vto-vertical-lr, {{WRAPPER}}.eael_vto-vertical-rl' => 'word-spacing: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
				],
			]
		);

        $element->add_responsive_control(
			'eael_vto_writing_text_indent',
			[
				'label'      => esc_html__( 'Text Indent', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.eael_vto-vertical-lr, {{WRAPPER}}.eael_vto-vertical-rl' => 'text-indent: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
				],
			]
		);

        $element->add_responsive_control(
			'eael_vto_writing_line_height',
			[
				'label'      => esc_html__( 'Line Height', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.eael_vto-vertical-lr, {{WRAPPER}}.eael_vto-vertical-rl' => 'line-height: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
				],
			]
		);

        $element->add_control(
			'eael_vto_writing_styling_heading',
			[
				'label' => esc_html__( 'Styling Options', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
                'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
				],
			]
		);

        $element->add_control(
			'eael_vto_writing_styling_type',
			[
				'label'   => esc_html__( 'Select Style', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal Type', 'essential-addons-for-elementor-lite' ),
					'background' => esc_html__( 'Background Type', 'essential-addons-for-elementor-lite' ),
					'gradient'   => esc_html__( 'Gradient Type', 'essential-addons-for-elementor-lite' ),
				],
                'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
				],
			]
		);

        $element->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'eael_vto_writing_styling_background',
				'types'    => [ 'classic' ],
				'selector' => '{{WRAPPER}}.eael_vto-vertical-lr .elementor-heading-title, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-heading-title, 
                    {{WRAPPER}}.eael_vto-vertical-lr .elementor-headline, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-headline, 
                    {{WRAPPER}}.eael_vto-vertical-lr .elementor-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-lr.elementor-widget-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-rl.elementor-widget-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-lr .eael-dual-header, 
                    {{WRAPPER}}.eael_vto-vertical-rl .eael-dual-header, 
                    {{WRAPPER}}.eael_vto-vertical-lr .eael-fancy-text-container, 
                    {{WRAPPER}}.eael_vto-vertical-rl .eael-fancy-text-container',
                'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
					'eael_vto_writing_styling_type' => 'background',
				],
			]
		);

        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'eael_vto_writing_gradient_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
			]
		);

        $repeater->add_control(
			'eael_vto_writing_gradient_color_location',
			[
				'label'      => esc_html__( 'Location', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'range'      => [
                    '%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
			]
		);

        $element->add_control(
			'eael_vto_writing_gradient_color_repeater',
			[
				'label'   => esc_html__( 'Choose Gradient Colors', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [
					[
						'eael_vto_writing_gradient_color' => '#7C62FF',
                        'eael_vto_writing_gradient_color_location' => [
                            'unit' => '%',
                            'size' => 50,
                        ],
					],
					[
						'eael_vto_writing_gradient_color' => '#FF6464',
                        'eael_vto_writing_gradient_color_location' => [
                            'unit' => '%',
                            'size' => 90,
                        ],
					],
				],
				'title_field' => '{{{ eael_vto_writing_gradient_color }}}',
                'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
                    'eael_vto_writing_styling_type' => 'gradient',
				],
			]
		);

        $element->add_control(
			'eael_vto_writing_styling_text_clip',
			[
				'label'        => esc_html__( 'Text Clip', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
                'selectors_dictionary' => [
					'yes' => 'text',
				],
                'selectors' => [
                    '{{WRAPPER}}.eael_vto-vertical-lr .elementor-heading-title, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-heading-title, 
                    {{WRAPPER}}.eael_vto-vertical-lr .elementor-headline, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-headline, 
                    {{WRAPPER}}.eael_vto-vertical-lr .elementor-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-lr.elementor-widget-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-rl.elementor-widget-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-lr .eael-dual-header, 
                    {{WRAPPER}}.eael_vto-vertical-rl .eael-dual-header, 
                    {{WRAPPER}}.eael_vto-vertical-lr .eael-fancy-text-container, 
                    {{WRAPPER}}.eael_vto-vertical-rl .eael-fancy-text-container' => 'background-clip: {{VALUE}}; color: transparent;',
                ],
				'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
					'eael_vto_writing_styling_type' => 'background',
					'eael_vto_writing_styling_background_background' => 'classic',
				],
			]
		);

        $element->add_control(
			'eael_vto_writing_styling_text_animation_bg',
			[
				'label'        => esc_html__( 'Animation', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'selectors' => [
                    '{{WRAPPER}}.eael_vto-vertical-lr .elementor-heading-title, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-heading-title, 
                    {{WRAPPER}}.eael_vto-vertical-lr .elementor-headline, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-headline, 
                    {{WRAPPER}}.eael_vto-vertical-lr .elementor-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-lr.elementor-widget-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-rl.elementor-widget-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-lr .eael-dual-header, 
                    {{WRAPPER}}.eael_vto-vertical-rl .eael-dual-header, 
                    {{WRAPPER}}.eael_vto-vertical-lr .eael-fancy-text-container, 
                    {{WRAPPER}}.eael_vto-vertical-rl .eael-fancy-text-container' => 'animation: eaelAnimationVTO 30s linear infinite;',
                ],
				'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
                    'eael_vto_writing_styling_type' => ['background'],
					'eael_vto_writing_styling_background_background' => 'classic',
				],
			]
		);

        $element->add_control(
			'eael_vto_writing_text_animation_control',
			[
				'label'   => esc_html__( 'Gradient Color Direction', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'horizontal' => [
						'title' => esc_html__( 'Horizontal', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-arrow-right',
					],
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-arrow-down',
					],
				],
				'default' => 'vertical',
				'toggle'  => false,
                'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
                    'eael_vto_writing_styling_type' => 'gradient',
				],
			]
		);

        $element->add_responsive_control(
			'eael_vto_writing_gradient_color_angle',
			[
				'label'      => esc_html__( 'Angle', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'deg', 'grad', 'px', '%', 'custom' ],
				'range' => [
					'deg' => [
						'min'  => 0,
						'max'  => 360,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'deg',
					'size' => 95,
				],
                'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
                    'eael_vto_writing_styling_type' =>  'gradient',
                    'eael_vto_writing_text_animation_control' =>  'horizontal',
				],
			]
		);

        $element->add_control(
			'eael_vto_writing_styling_text_animation',
			[
				'label'        => esc_html__( 'Animation', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'selectors' => [
                    '{{WRAPPER}}.eael_vto-vertical-lr .elementor-heading-title, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-heading-title, 
                    {{WRAPPER}}.eael_vto-vertical-lr .elementor-headline, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-headline, 
                    {{WRAPPER}}.eael_vto-vertical-lr .elementor-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-lr.elementor-widget-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-rl.elementor-widget-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-lr .eael-dual-header, 
                    {{WRAPPER}}.eael_vto-vertical-rl .eael-dual-header, 
                    {{WRAPPER}}.eael_vto-vertical-lr .eael-fancy-text-container, 
                    {{WRAPPER}}.eael_vto-vertical-rl .eael-fancy-text-container' => 'animation: eaelAnimationVTO 5s linear infinite;',
                ],
				'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
                    'eael_vto_writing_styling_type' => 'gradient',
                    'eael_vto_writing_text_animation_control' =>  'horizontal',
				],
			]
		);

        $element->add_responsive_control(
			'eael_vto_writing_gradient_color_angle_vertical',
			[
				'label'      => esc_html__( 'Angle', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'deg', 'grad', 'px', '%', 'custom' ],
				'range' => [
					'deg' => [
						'min'  => 0,
						'max'  => 360,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'deg',
					'size' => 2,
				],
                'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
                    'eael_vto_writing_styling_type' =>  'gradient',
                    'eael_vto_writing_text_animation_control' =>  'vertical',
				],
			]
		);

        $element->add_control(
			'eael_vto_writing_styling_text_animation_vertical',
			[
				'label'        => esc_html__( 'Animation', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
                'selectors' => [
                    '{{WRAPPER}}.eael_vto-vertical-lr .elementor-heading-title, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-heading-title, 
                    {{WRAPPER}}.eael_vto-vertical-lr .elementor-headline, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-headline, 
                    {{WRAPPER}}.eael_vto-vertical-lr .elementor-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-rl .elementor-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-lr.elementor-widget-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-rl.elementor-widget-text-editor p, 
                    {{WRAPPER}}.eael_vto-vertical-lr .eael-dual-header, 
                    {{WRAPPER}}.eael_vto-vertical-rl .eael-dual-header, 
                    {{WRAPPER}}.eael_vto-vertical-lr .eael-fancy-text-container, 
                    {{WRAPPER}}.eael_vto-vertical-rl .eael-fancy-text-container' => 'animation: eaelAnimationVertical 5s linear infinite;',
                ],
				'condition' => [
					'eael_vertical_text_orientation_switch' => 'yes',
                    'eael_vto_writing_styling_type' => 'gradient',
                    'eael_vto_writing_text_animation_control' =>  'vertical',
				],
			]
		);
		
		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$settings = $element->get_settings_for_display();
        if ( !empty( $settings['eael_vto_writing_gradient_color_repeater'] ) && is_array( $settings['eael_vto_writing_gradient_color_repeater'] ) ) {
            $gradient_colors = [];
            foreach ( $settings['eael_vto_writing_gradient_color_repeater'] as $value ) {
                $gradient_colors[] = [
                    'color' => $value['eael_vto_writing_gradient_color'],
                    'location' => $value['eael_vto_writing_gradient_color_location']['size'] . $value['eael_vto_writing_gradient_color_location']['unit'],
                ];
            }
            $element->add_render_attribute( '_wrapper', 'data-gradient_colors', wp_json_encode( $gradient_colors ) );

            if ($settings['eael_vto_writing_text_animation_control'] === 'horizontal') {
                $eael_gradient_color_angle = $settings['eael_vto_writing_gradient_color_angle']['size'] ? $settings['eael_vto_writing_gradient_color_angle']['size'] . $settings['eael_vto_writing_gradient_color_angle']['unit'] : '0deg';
                $element->add_render_attribute( '_wrapper', 'data-gradient_color_angle', $eael_gradient_color_angle );
                $element->add_render_attribute( '_wrapper', 'data-animation_control', 'horizontal' );
            } else {
                $eael_gradient_color_angle = $settings['eael_vto_writing_gradient_color_angle_vertical']['size'] ? $settings['eael_vto_writing_gradient_color_angle_vertical']['size'] . $settings['eael_vto_writing_gradient_color_angle_vertical']['unit'] : '0deg';
                $element->add_render_attribute( '_wrapper', 'data-gradient_color_angle', $eael_gradient_color_angle );
                $element->add_render_attribute( '_wrapper', 'data-animation_control', 'vertical' );
            }
        }
	}
}
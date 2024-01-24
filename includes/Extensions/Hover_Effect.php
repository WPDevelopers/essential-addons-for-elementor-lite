<?php

namespace Essential_Addons_Elementor\Extensions;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hover_Effect {

	/**
	 * Initialize hooks
	 */
	public function __construct() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/frontend/before_render', [ $this, 'before_render' ], 1 );
	}

	public function register_controls( $element ) {
		$element->start_controls_section(
			'eael_hover_effect_section',
			[
				'label' => __( '<i class="eaicon-logo"></i> Hover Effect', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_ADVANCED
			]
		);

		$element->add_control(
			'eael_hover_effect_switch',
			[
				'label' => __( 'Enable Hover Effect', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->start_controls_tabs(
			'eael_hover_effect'
		);

        //Normal Tab
        $element->start_controls_tab(
			'eael_hover_effect_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

        //Background
        $element->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'eael_hover_effect_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .elementor-widget-container',
			]
		);

        //Opacity
        $element->add_control(
			'eael_hover_effect_opacity_popover',
			[
				'label'              => __( 'Opacity', 'essential-addons-for-elementor-lite' ), 
                'type'               => Controls_Manager::POPOVER_TOGGLE, 
                'return_value'       => 'yes',
			]
		);

        $element->start_popover();
        $element->add_control(
            'eael_hover_effect_opacity',
            [
                'label'              => __( 'Opacity', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'max'  => 1,
                        'step' => 0.01,
                    ],
                ],
            ]
        );
        $element->end_popover();

        //Filter
        $element->add_control(
			'eael_hover_effect_filter_popover',
			[
				'label'              => __( 'Filter', 'essential-addons-for-elementor-lite' ), 
                'type'               => Controls_Manager::POPOVER_TOGGLE, 
                'return_value'       => 'yes',
			]
		);

        $element->start_popover();
        $element->add_control(
			'eael_hover_effect_blur_is_on',
			[
				'label' => __( 'Blur', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_hover_effect_blur',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'max'  => 10,
                        'step' => 0.5,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: blur({{SIZE}}px);',
				],
                'condition' => [
					'eael_hover_effect_blur_is_on' => 'yes', 
				],
            ]
        );

        $element->add_control(
			'eael_hover_effect_contrast_is_on',
			[
				'label' => __( 'Contrast', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_hover_effect_contrast',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
					'size' => 100,
				],
                'range' => [
                    '%' => [
                        'max'  => 1000,
                        'step' => 10,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: blur({{eael_hover_effect_blur.SIZE}}px) contrast({{SIZE}}%);',
				],
                'condition' => [
					'eael_hover_effect_contrast_is_on' => 'yes', 
				],
            ]
        );

        $element->add_control(
			'eael_hover_effect_grayscale_is_on',
			[
				'label' => __( 'Grayscale', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_hover_effect_grayscal',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
					'size' => 0,
				],
                'range' => [
                    '%' => [
                        'max'  => 100,
                        'step' => 10,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: blur({{eael_hover_effect_blur.SIZE}}px) contrast({{eael_hover_effect_contrast.SIZE}}%) grayscale({{SIZE}}%);',
				],
                'condition' => [
					'eael_hover_effect_grayscale_is_on' => 'yes', 
				],
            ]
        );

        $element->add_control(
			'eael_hover_effect_invert_is_on',
			[
				'label' => __( 'Invert', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_hover_effect_invert',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
					'size' => 0,
				],
                'range' => [
                    '%' => [
                        'max'  => 100,
                        'step' => 10,
                    ],
                ],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: blur({{eael_hover_effect_blur.SIZE}}px) contrast({{eael_hover_effect_contrast.SIZE}}%) grayscale({{eael_hover_effect_grayscal.SIZE}}%) invert({{SIZE}}%);',
				],
                'condition' => [
					'eael_hover_effect_invert_is_on' => 'yes', 
				],
            ]
        );

        $element->add_control(
			'eael_hover_effect_saturate_is_on',
			[
				'label' => __( 'Saturate', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_hover_effect_saturate',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
					'size' => 100,
				],
                'range' => [
                    '%' => [
                        'max'  => 1000,
                        'step' => 10,
                    ],
                ],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: blur({{eael_hover_effect_blur.SIZE}}px) contrast({{eael_hover_effect_contrast.SIZE}}%) grayscale({{eael_hover_effect_grayscal.SIZE}}%) invert({{eael_hover_effect_invert.SIZE}}%) saturate({{SIZE}}%);',
				],
                'condition' => [
					'eael_hover_effect_saturate_is_on' => 'yes', 
				],
            ]
        );

        $element->add_control(
			'eael_hover_effect_sepia_is_on',
			[
				'label' => __( 'Sepia', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_hover_effect_sepia',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
					'size' => 0,
				],
                'range' => [
                    '%' => [
                        'max'  => 100,
                        'step' => 10,
                    ],
                ],
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: blur({{eael_hover_effect_blur.SIZE}}px) contrast({{eael_hover_effect_contrast.SIZE}}%) grayscale({{eael_hover_effect_grayscal.SIZE}}%) invert({{eael_hover_effect_invert.SIZE}}%) saturate({{eael_hover_effect_saturate.SIZE}}%) sepia({{SIZE}}%);',
				],
                'condition' => [
					'eael_hover_effect_sepia_is_on' => 'yes', 
				],
            ]
        );
        $element->end_popover();

        //Offset
        $element->add_control(
			'eael_hover_effect_offset_popover',
			[
				'label'              => __( 'Offset', 'essential-addons-for-elementor-lite' ), 
                'type'               => Controls_Manager::POPOVER_TOGGLE, 
                'return_value'       => 'yes', 
                'frontend_available' => true,
			]
		);
        $element->start_popover();
        $element->add_control(
            'eael_hover_effect_offset_top',
            [
                'label'              => __( 'Offset Top', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'size_units'         => ['px', '%'],
                'default' => [
					'unit' => 'px',
					'size' => 0,
				],
                'range' => [
                    'px' => [
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'top: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $element->add_control(
            'eael_hover_effect_offset_left',
            [
                'label'              => __( 'Offset Left', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'size_units'         => ['px', '%'],
                'default' => [
					'unit' => 'px',
					'size' => 0,
				],
                'range' => [
                    'px' => [
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'left: {{SIZE}}{{UNIT}};',
				],
            ]
        );
        $element->end_popover();

        //Transform
        $element->add_control(
			'eael_hover_effect_transform_popover',
			[
				'label'              => __( 'Transform', 'essential-addons-for-elementor-lite' ), 
                'type'               => Controls_Manager::POPOVER_TOGGLE, 
                'return_value'       => 'yes', 
                'frontend_available' => true,
			]
		);
        
        //Rotate
        $element->start_popover();
        $element->add_control(
			'eael_hover_effect_rotate_is_on',
			[
				'label' => __( 'Rotate', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		//RotateX
        $element->add_control(
			'eael_hover_effect_transform_rotatex',
			[
				'label' => esc_html__( 'RotateX', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => -180,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
                'condition' => [
					'eael_hover_effect_rotate_is_on' => 'yes', 
				],
			]
		);

		//RotateY
        $element->add_control(
			'eael_hover_effect_transform_rotatey',
			[
				'label' => esc_html__( 'RotateY', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => -180,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
                'condition' => [
					'eael_hover_effect_rotate_is_on' => 'yes', 
				],
			]
		);

		//RotateZ
        $element->add_control(
			'eael_hover_effect_transform_rotatez',
			[
				'label' => esc_html__( 'RotateZ', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => -180,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
                'condition' => [
					'eael_hover_effect_rotate_is_on' => 'yes', 
				],
			]
		);

        //Scale
        $element->add_control(
			'eael_hover_effect_scale_is_on',
			[
				'label' => __( 'Scale', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		//ScaleX
        $element->add_control(
			'eael_hover_effect_transform_scalex',
			[
				'label' => esc_html__( 'ScaleX', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
                        'max'  => 3,
                        'step' => 0.1,
                    ],
				],
                'default' => [
					'size' => 1,
				],
                'condition' => [
					'eael_hover_effect_scale_is_on' => 'yes', 
				],
			]
		);

		//ScaleY
        $element->add_control(
			'eael_hover_effect_transform_scaley',
			[
				'label' => esc_html__( 'ScaleY', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
                        'max'  => 3,
                        'step' => 0.1,
                    ],
				],
                'default' => [
					'size' => 1,
				],
                'condition' => [
					'eael_hover_effect_scale_is_on' => 'yes', 
				],
			]
		);

        //Skew
        $element->add_control(
			'eael_hover_effect_skew_is_on',
			[
				'label' => __( 'Skew', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

		//SkewX
        $element->add_control(
			'eael_hover_effect_transform_skewx',
			[
				'label' => esc_html__( 'SkewX', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => 0,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
                'condition' => [
					'eael_hover_effect_skew_is_on' => 'yes', 
				],
			]
		);

		//SkewY
        $element->add_control(
			'eael_hover_effect_transform_skewy',
			[
				'label' => esc_html__( 'SkewY', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => 0,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
                'condition' => [
					'eael_hover_effect_skew_is_on' => 'yes', 
				],
			]
		);

        $element->end_popover();

        $element->end_controls_tab();

        /**
         * Hovar Tab
         */
        $element->start_controls_tab(
			'eael_hover_effect_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
			]
		);

         //Background Hover
        $element->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'eael_hover_effect_background_hover',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .your-class',
			]
		);

        //Opacity Hover
        $element->add_control(
			'eael_hover_effect_opacity_popover_hover',
			[
				'label'              => __( 'Opacity', 'essential-addons-for-elementor-lite' ), 
                'type'               => Controls_Manager::POPOVER_TOGGLE, 
                'return_value'       => 'yes', 
                'frontend_available' => true,
			]
		);

        $element->start_popover();
        $element->add_control(
            'eael_hover_effect_opacity_hover',
            [
                'label'              => __( 'Opacity', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'max'  => 1,
                        'step' => 0.01,
                    ],
                ],
            ]
        );

        $element->add_control(
            'eael_hover_effect_opacity_delay',
            [
                'label'              => __( 'Opacity Delay', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'default' => [
                    'size' => 0,
                ],
                'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
                ],
            ]
        );

        $element->add_control(
            'eael_hover_effect_opacity_hover_duration',
            [
                'label'              => __( 'Opacity Duration', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'default' => [
                    'size' => 0,
                ],
                'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
                ],
            ]
        );

        $element->add_control(
			'eael_hover_effect_opacity_hover_easing',
			[
				'label'                 => __( 'Easing', 'essential-addons-for-elementor-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'ease',
				'frontend_available'    => true, 
                'separator'             => 'before', 
				'options'               => [
					'ease'        => __( 'Default', 'essential-addons-for-elementor-lite' ), 
					'ease-in'     => __( 'Ease-in', 'essential-addons-for-elementor-lite' ), 
                    'ease-out'    => __( 'Ease-out', 'essential-addons-for-elementor-lite' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'essential-addons-for-elementor-lite' ), 
                ],
			]
        );
        $element->end_popover();

        //Filter Hover Start
        $element->add_control(
			'eael_hover_effect_filter_hover_popover',
			[
				'label'              => __( 'Filter', 'essential-addons-for-elementor-lite' ), 
                'type'               => Controls_Manager::POPOVER_TOGGLE, 
                'return_value'       => 'yes',
			]
		);

        $element->start_popover();

        //Blur Start
        $element->add_control(
			'eael_hover_effect_blur_hover_is_on',
			[
				'label' => __( 'Blur', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_hover_effect_blur_hover',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'size_units'         => ['px'],
                'default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'max'  => 10,
                        'step' => 0.5,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: blur({{SIZE}}{{UNIT}});',
				],
                'condition' => [
					'eael_hover_effect_blur_hover_is_on' => 'yes', 
				],
            ]
        );

        //Duration
        $element->add_control(
			'eael_hover_effect_blur_hover_duration',
			[
                'label'     => __( 'Duration (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'default'   => [
                    'unit' => 'px',
					'size' => 1000,
                ],
				'condition' => [
					'eael_hover_effect_blur_hover_is_on' => 'yes', 
				],
            ]
		);

        //Delay
		$element->add_control(
			'eael_hover_effect_blur_hover_delay',
			[
                'label'     => __( 'Delay (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_blur_hover_is_on' => 'yes', 
				],
            ]
		);

        $element->add_control(
			'eael_hover_effect_blur_hover_easing',
			[
				'label'                 => __( 'Easing', 'essential-addons-for-elementor-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'ease',
				'frontend_available'    => true, 
                'separator'             => 'before', 
				'options'               => [
					'ease'        => __( 'Default', 'essential-addons-for-elementor-lite' ), 
					'ease-in'     => __( 'Ease-in', 'essential-addons-for-elementor-lite' ), 
                    'ease-out'    => __( 'Ease-out', 'essential-addons-for-elementor-lite' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'essential-addons-for-elementor-lite' ), 
                ],
                'condition' => [
					'eael_hover_effect_blur_hover_is_on' => 'yes', 
				],
			]
        );
        //Blur End

        //Contrast Start
        $element->add_control(
			'eael_hover_effect_contrast_hover_is_on',
			[
				'label' => __( 'Contrast', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_hover_effect_contrast_hover',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'size_units'         => ['%'],
                'default' => [
					'unit' => '%',
					'size' => 100,
				],
                'range' => [
                    '%' => [
                        'max'  => 1000,
                        'step' => 10,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: contrast({{SIZE}}{{UNIT}});',
				],
                'condition' => [
					'eael_hover_effect_contrast_hover_is_on' => 'yes', 
				],
            ]
        );

        //Duration
        $element->add_control(
			'eael_hover_effect_contrast_hover_duration',
			[
                'label'     => __( 'Duration (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'default'   => [
                    'unit' => 'px',
					'size' => 1000,
                ],
				'condition' => [
					'eael_hover_effect_contrast_hover_is_on' => 'yes', 
				],
            ]
		);

        //Delay
		$element->add_control(
			'eael_hover_effect_contrast_hover_delay',
			[
                'label'     => __( 'Delay (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_contrast_hover_is_on' => 'yes', 
				],
            ]
		);

        $element->add_control(
			'eael_hover_effect_contrast_hover_easing',
			[
				'label'                 => __( 'Easing', 'essential-addons-for-elementor-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'ease',
				'frontend_available'    => true, 
                'separator'             => 'before', 
				'options'               => [
					'ease'        => __( 'Default', 'essential-addons-for-elementor-lite' ), 
					'ease-in'     => __( 'Ease-in', 'essential-addons-for-elementor-lite' ), 
                    'ease-out'    => __( 'Ease-out', 'essential-addons-for-elementor-lite' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'essential-addons-for-elementor-lite' ), 
                ],
                'condition' => [
					'eael_hover_effect_contrast_hover_is_on' => 'yes', 
				],
			]
        );
        //Contrast End

        //Grayscale Start
        $element->add_control(
			'eael_hover_effect_grayscale_hover_is_on',
			[
				'label' => __( 'Grayscale', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_hover_effect_grayscal_hover',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'size_units'         => ['%'],
                'default' => [
					'unit' => '%',
					'size' => 0,
				],
                'range' => [
                    '%' => [
                        'max'  => 100,
                        'step' => 10,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: grayscale({{SIZE}}{{UNIT}});',
				],
                'condition' => [
					'eael_hover_effect_grayscale_hover_is_on' => 'yes', 
				],
            ]
        );
        
        //Duration
        $element->add_control(
			'eael_hover_effect_grayscale_hover_duration',
			[
                'label'     => __( 'Duration (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'default'   => [
                    'unit' => 'px',
					'size' => 1000,
                ],
				'condition' => [
					'eael_hover_effect_grayscale_hover_is_on' => 'yes', 
				],
            ]
		);

        //Delay
		$element->add_control(
			'eael_hover_effect_grayscale_hover_delay',
			[
                'label'     => __( 'Delay (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_grayscale_hover_is_on' => 'yes', 
				],
            ]
		);

        $element->add_control(
			'eael_hover_effect_grayscale_hover_easing',
			[
				'label'                 => __( 'Easing', 'essential-addons-for-elementor-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'ease',
				'frontend_available'    => true, 
                'separator'             => 'before', 
				'options'               => [
					'ease'        => __( 'Default', 'essential-addons-for-elementor-lite' ), 
					'ease-in'     => __( 'Ease-in', 'essential-addons-for-elementor-lite' ), 
                    'ease-out'    => __( 'Ease-out', 'essential-addons-for-elementor-lite' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'essential-addons-for-elementor-lite' ), 
                ],
                'condition' => [
					'eael_hover_effect_grayscale_hover_is_on' => 'yes', 
				],
			]
        );
        //Grayscale End

        //Invert Start
        $element->add_control(
			'eael_hover_effect_invert_hover_is_on',
			[
				'label' => __( 'Invert', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_hover_effect_invert_hover',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'size_units'         => ['%'],
                'default' => [
					'unit' => '%',
					'size' => 0,
				],
                'range' => [
                    '%' => [
                        'max'  => 100,
                        'step' => 10,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: invert({{SIZE}}{{UNIT}});',
				],
                'condition' => [
					'eael_hover_effect_invert_hover_is_on' => 'yes', 
				],
            ]
        );

        //Duration
        $element->add_control(
			'eael_hover_effect_invert_hover_duration',
			[
                'label'     => __( 'Duration (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'default'   => [
                    'unit' => 'px',
					'size' => 1000,
                ],
				'condition' => [
					'eael_hover_effect_invert_hover_is_on' => 'yes', 
				],
            ]
		);

        //Delay
		$element->add_control(
			'eael_hover_effect_invert_hover_delay',
			[
                'label'     => __( 'Delay (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_invert_hover_is_on' => 'yes', 
				],
            ]
		);

        $element->add_control(
			'eael_hover_effect_invert_hover_easing',
			[
				'label'                 => __( 'Easing', 'essential-addons-for-elementor-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'ease',
				'frontend_available'    => true, 
                'separator'             => 'before', 
				'options'               => [
					'ease'        => __( 'Default', 'essential-addons-for-elementor-lite' ), 
					'ease-in'     => __( 'Ease-in', 'essential-addons-for-elementor-lite' ), 
                    'ease-out'    => __( 'Ease-out', 'essential-addons-for-elementor-lite' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'essential-addons-for-elementor-lite' ), 
                ],
                'condition' => [
					'eael_hover_effect_invert_hover_is_on' => 'yes', 
				],
			]
        );
        //Invert End

        //Saturate Start
        $element->add_control(
			'eael_hover_effect_saturate_hover_is_on',
			[
				'label' => __( 'Saturate', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_hover_effect_saturate_hover',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'size_units'         => ['%'],
                'default' => [
					'unit' => '%',
					'size' => 100,
				],
                'range' => [
                    '%' => [
                        'max'  => 1000,
                        'step' => 10,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: saturate({{SIZE}}{{UNIT}});',
				],
                'condition' => [
					'eael_hover_effect_saturate_hover_is_on' => 'yes', 
				],
            ]
        );

        //Duration
        $element->add_control(
			'eael_hover_effect_saturate_hover_duration',
			[
                'label'     => __( 'Duration (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'default'   => [
                    'unit' => 'px',
					'size' => 1000,
                ],
				'condition' => [
					'eael_hover_effect_saturate_hover_is_on' => 'yes', 
				],
            ]
		);

        //Delay
		$element->add_control(
			'eael_hover_effect_saturate_hover_delay',
			[
                'label'     => __( 'Delay (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_saturate_hover_is_on' => 'yes', 
				],
            ]
		);

        $element->add_control(
			'eael_hover_effect_saturate_hover_easing',
			[
				'label'                 => __( 'Easing', 'essential-addons-for-elementor-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'ease',
				'frontend_available'    => true, 
                'separator'             => 'before', 
				'options'               => [
					'ease'        => __( 'Default', 'essential-addons-for-elementor-lite' ), 
					'ease-in'     => __( 'Ease-in', 'essential-addons-for-elementor-lite' ), 
                    'ease-out'    => __( 'Ease-out', 'essential-addons-for-elementor-lite' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'essential-addons-for-elementor-lite' ), 
                ],
                'condition' => [
					'eael_hover_effect_saturate_hover_is_on' => 'yes', 
				],
			]
        );
        //Saturate End

        //Sepia Start
        $element->add_control(
			'eael_hover_effect_sepia_hover_is_on',
			[
				'label' => __( 'Sepia', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_hover_effect_sepia_hover',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'size_units'         => ['%'],
                'default' => [
					'unit' => '%',
					'size' => 0,
				],
                'range' => [
                    '%' => [
                        'max'  => 100,
                        'step' => 10,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'filter: sepia({{SIZE}}{{UNIT}});',
				],
                'condition' => [
					'eael_hover_effect_sepia_hover_is_on' => 'yes', 
				],
            ]
        );

        //Duration
        $element->add_control(
			'eael_hover_effect_sepia_hover_duration',
			[
                'label'     => __( 'Duration (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'default'   => [
                    'unit' => 'px',
					'size' => 1000,
                ],
				'condition' => [
					'eael_hover_effect_sepia_hover_is_on' => 'yes', 
				],
            ]
		);

        //Delay
		$element->add_control(
			'eael_hover_effect_sepia_hover_delay',
			[
                'label'     => __( 'Delay (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_sepia_hover_is_on' => 'yes', 
				],
            ]
		);

        $element->add_control(
			'eael_hover_effect_sepia_hover_easing',
			[
				'label'                 => __( 'Easing', 'essential-addons-for-elementor-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'ease',
				'frontend_available'    => true, 
                'separator'             => 'before', 
				'options'               => [
					'ease'        => __( 'Default', 'essential-addons-for-elementor-lite' ), 
					'ease-in'     => __( 'Ease-in', 'essential-addons-for-elementor-lite' ), 
                    'ease-out'    => __( 'Ease-out', 'essential-addons-for-elementor-lite' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'essential-addons-for-elementor-lite' ), 
                ],
                'condition' => [
					'eael_hover_effect_sepia_hover_is_on' => 'yes', 
				],
			]
        );
        //Sepia End
        $element->end_popover();
        //Filter End

        //Offset Hover Start
        $element->add_control(
			'eael_hover_effect_offset_hover_popover',
			[
				'label'              => __( 'Offset', 'essential-addons-for-elementor-lite' ), 
                'type'               => Controls_Manager::POPOVER_TOGGLE, 
                'return_value'       => 'yes', 
                'frontend_available' => true,
			]
		);
        $element->start_popover();
        $element->add_control(
            'eael_hover_effect_offset_hover_top',
            [
                'label'              => __( 'Offset Top', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'size_units'         => ['px', '%'],
                'default' => [
					'unit' => 'px',
					'size' => 0,
				],
                'range' => [
                    'px' => [
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'top: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $element->add_control(
            'eael_hover_effect_offset_hover_left',
            [
                'label'              => __( 'Offset Left', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'size_units'         => ['px', '%'],
                'default' => [
					'unit' => 'px',
					'size' => 0,
				],
                'range' => [
                    'px' => [
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'left: {{SIZE}}{{UNIT}};',
				],
            ]
        );
        $element->end_popover();
        //Offset End

        //Transform Hover Start
        $element->add_control(
			'eael_hover_effect_transform_hover_popover',
			[
				'label'              => __( 'Transform', 'essential-addons-for-elementor-lite' ), 
                'type'               => Controls_Manager::POPOVER_TOGGLE, 
                'return_value'       => 'yes', 
                'frontend_available' => true,
			]
		);
        
        //Rotate Start
        $element->start_popover();
        $element->add_control(
			'eael_hover_effect_rotate_hover_is_on',
			[
				'label' => __( 'Rotate', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_hover_rotatex',
			[
                'label' => esc_html__( 'RotateX', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => -180,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
                'condition' => [
					'eael_hover_effect_rotate_hover_is_on' => 'yes', 
				],
            ]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_hover_rotatey',
			[
                'label' => esc_html__( 'RotateY', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => -180,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
                'condition' => [
					'eael_hover_effect_rotate_hover_is_on' => 'yes', 
				],
            ]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_hover_rotatez',
			[
                'label' => esc_html__( 'RotateZ', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => -180,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
                'condition' => [
					'eael_hover_effect_rotate_hover_is_on' => 'yes', 
				],
            ]
		);

        //Duration
        $element->add_control(
			'eael_hover_effect_rotate_hover_duration',
			[
                'label'     => __( 'Duration (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'default'   => [
                    'unit' => 'px',
					'size' => 1000,
                ],
				'condition' => [
					'eael_hover_effect_rotate_hover_is_on' => 'yes', 
				],
            ]
		);

        //Delay
		$element->add_control(
			'eael_hover_effect_rotate_hover_delay',
			[
                'label'     => __( 'Delay (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_rotate_hover_is_on' => 'yes', 
				],
            ]
		);

        $element->add_control(
			'eael_hover_effect_rotate_hover_easing',
			[
				'label'                 => __( 'Easing', 'essential-addons-for-elementor-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'ease',
				'frontend_available'    => true, 
                'separator'             => 'before', 
				'options'               => [
					'ease'        => __( 'Default', 'essential-addons-for-elementor-lite' ), 
					'ease-in'     => __( 'Ease-in', 'essential-addons-for-elementor-lite' ), 
                    'ease-out'    => __( 'Ease-out', 'essential-addons-for-elementor-lite' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'essential-addons-for-elementor-lite' ), 
                ],
                'condition' => [
					'eael_hover_effect_rotate_hover_is_on' => 'yes', 
				],
			]
        );
        //Rotate End

        //Scale Start
        $element->add_control(
			'eael_hover_effect_scale_hover_is_on',
			[
				'label' => __( 'Scale', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_hover_scalex',
			[
                'label' => esc_html__( 'ScaleX', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
                        'max'  => 3,
                        'step' => 0.1,
                    ],
				],
                'default' => [
					'size' => 1,
				],
                'condition' => [
					'eael_hover_effect_scale_hover_is_on' => 'yes', 
				],
            ]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_hover_scaley',
			[
                'label' => esc_html__( 'ScaleY', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
                        'max'  => 3,
                        'step' => 0.1,
                    ],
				],
                'default' => [
					'size' => 1,
				],
                'condition' => [
					'eael_hover_effect_scale_hover_is_on' => 'yes', 
				],
            ]
		);

        //Duration
        $element->add_control(
			'eael_hover_effect_scale_hover_duration',
			[
                'label'     => __( 'Duration (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'default'   => [
                    'unit' => 'px',
					'size' => 1000,
                ],
				'condition' => [
					'eael_hover_effect_scale_hover_is_on' => 'yes', 
				],
            ]
		);

        //Delay
		$element->add_control(
			'eael_hover_effect_scale_hover_delay',
			[
                'label'     => __( 'Delay (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_scale_hover_is_on' => 'yes', 
				],
            ]
        );

        $element->add_control(
			'eael_hover_effect_scale_hover_easing',
			[
				'label'                 => __( 'Easing', 'essential-addons-for-elementor-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'ease',
				'frontend_available'    => true, 
                'separator'             => 'before', 
				'options'               => [
					'ease'        => __( 'Default', 'essential-addons-for-elementor-lite' ), 
					'ease-in'     => __( 'Ease-in', 'essential-addons-for-elementor-lite' ), 
                    'ease-out'    => __( 'Ease-out', 'essential-addons-for-elementor-lite' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'essential-addons-for-elementor-lite' ), 
                ],
                'condition' => [
					'eael_hover_effect_scale_hover_is_on' => 'yes', 
				],
			]
        );
        //Scale End

        //Skew
        $element->add_control(
			'eael_hover_effect_skew_hover_is_on',
			[
				'label' => __( 'Skew', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_hover_skewx',
			[
                'label' => esc_html__( 'SkewX', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => 0,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
                'condition' => [
					'eael_hover_effect_skew_hover_is_on' => 'yes', 
				],
            ]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_hover_skewy',
			[
                'label' => esc_html__( 'SkewY', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => 0,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 0,
				],
                'condition' => [
					'eael_hover_effect_skew_hover_is_on' => 'yes', 
				],
            ]
		);

        //Duration
        $element->add_control(
			'eael_hover_effect_skew_hover_duration',
			[
                'label'     => __( 'Duration (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'default'   => [
                    'unit' => 'px',
					'size' => 1000,
                ],
				'condition' => [
					'eael_hover_effect_skew_hover_is_on' => 'yes', 
				],
            ]
		);

        //Delay
		$element->add_control(
			'eael_hover_effect_skew_hover_delay',
			[
                'label'     => __( 'Delay (ms)', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
                    'px' => [
                        'min'  => 0,
						'max'  => 10000,
						'step' => 100,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_skew_hover_is_on' => 'yes', 
				],
            ]
		);

        $element->add_control(
			'eael_hover_effect_skew_hover_easing',
			[
				'label'                 => __( 'Easing', 'essential-addons-for-elementor-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'ease',
				'frontend_available'    => true, 
                'separator'             => 'before', 
				'options'               => [
					'ease'        => __( 'Default', 'essential-addons-for-elementor-lite' ), 
					'ease-in'     => __( 'Ease-in', 'essential-addons-for-elementor-lite' ), 
                    'ease-out'    => __( 'Ease-out', 'essential-addons-for-elementor-lite' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'essential-addons-for-elementor-lite' ), 
                ],
                'condition' => [
					'eael_hover_effect_skew_hover_is_on' => 'yes', 
				],
			]
        );
        //Skew End
        $element->end_popover();

        $element->end_controls_tab();
        $element->end_controls_tabs();
		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$settings = $element->get_settings_for_display();

		//Opacity
		if( !empty( $settings['eael_hover_effect_opacity']['size'] ) ) {
			$opacity_settings = [
				'opacity' => $settings['eael_hover_effect_opacity']['size'],
			];
			$element->add_render_attribute( '_wrapper', 'data-eael_opacity', wp_json_encode( $opacity_settings ) );
		}
		
		//Rotate Effect
		if( 'yes' == $settings['eael_hover_effect_rotate_is_on'] ) {
			$rotate_settings = [
				'rotate_x' => $settings['eael_hover_effect_transform_rotatex']['size'],
				'rotate_y' => $settings['eael_hover_effect_transform_rotatey']['size'],
				'rotate_z' => $settings['eael_hover_effect_transform_rotatez']['size'],
			];
			$element->add_render_attribute( '_wrapper', 'data-eael_rotate_effect', wp_json_encode( $rotate_settings ) );
		}

		//Scale Effect
		if( 'yes' == $settings['eael_hover_effect_scale_is_on'] ) {
			$scale_settings = [
				'scale_x' => $settings['eael_hover_effect_transform_scalex']['size'],
				'scale_y' => $settings['eael_hover_effect_transform_scaley']['size'],
			];
			$element->add_render_attribute( '_wrapper', 'data-eael_scale_effect', wp_json_encode( $scale_settings ) );
		}

		//Skew Effect
		if( 'yes' == $settings['eael_hover_effect_skew_is_on'] ) {
			$skew_settings = [
				'skew_x' => $settings['eael_hover_effect_transform_skewx']['size'],
				'skew_y' => $settings['eael_hover_effect_transform_skewy']['size'],
			];
			$element->add_render_attribute( '_wrapper', 'data-eael_skew_effect', wp_json_encode( $skew_settings ) );
		}
		
        $element->add_render_attribute( '_wrapper', 'class', 'eael_hover_effect' );
	}

}

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
		add_action( 'elementor/frontend/before_render', [ $this, 'before_render' ], 100 );
	}

	public function register_controls( $element ) {
		$element->start_controls_section(
			'eael_hover_effect_section',
			[
				'label' => __( '<i class="eaicon-logo"></i> Hover Interactions', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_ADVANCED
			]
		);

		$element->add_control(
			'eael_hover_effect_switch',
			[
				'label' => __( 'Enable Hover Interactions', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

		$element->add_control(
			'eael_hover_effect_enable_note',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( "Enabling this option will disable Elementor's Transform feature within the Elementor Editor.", 'essential-addons-for-elementor-lite' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$element->add_control(
			'eael_hover_effect_enable_live_changes',
			[
				'label'     => __( 'Show Preview in Editor', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);

		$element->add_control(
			'eael_hover_effect_enable_live_changes_note',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Enabling this option will let you preview the Hover Interactions inside the Elementor Editor.', 'essential-addons-for-elementor-lite' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => [
					'eael_hover_effect_switch' => 'yes',
				],
			]
		);

		$element->start_controls_tabs(
			'eael_hover_effect',
			[
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);

        //Normal Tab
        $element->start_controls_tab(
			'eael_hover_effect_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);

        //Opacity
        $element->add_control(
			'eael_hover_effect_opacity_popover',
			[
				'label'              => __( 'Opacity', 'essential-addons-for-elementor-lite' ), 
                'type'               => Controls_Manager::POPOVER_TOGGLE, 
                'return_value'       => 'yes',
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);

        $element->start_popover();
        $element->add_control(
            'eael_hover_effect_opacity',
            [
                'label'              => __( 'Opacity', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 0.8,
                ],
                'range' => [
                    'px' => [
                        'max'  => 1,
                        'step' => 0.01,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_opacity_popover' => 'yes',
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
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);

        $element->start_popover();

		//Blur
        $element->add_control(
			'eael_hover_effect_blur_is_on',
			[
				'label' => __( 'Blur', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [
					'eael_hover_effect_filter_popover' => 'yes',
				],
			]
		);

        $element->add_control(
            'eael_hover_effect_blur',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'max'  => 10,
                        'step' => 0.5,
                    ],
                ],
                'condition' => [
					'eael_hover_effect_blur_is_on'     => 'yes',
					'eael_hover_effect_filter_popover' => 'yes',
				],
            ]
        );

		//Contrast
        $element->add_control(
			'eael_hover_effect_contrast_is_on',
			[
				'label' => __( 'Contrast', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [ 
					'eael_hover_effect_filter_popover' => 'yes', 
				],
			]
		);

        $element->add_control(
            'eael_hover_effect_contrast',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
					'size' => 80,
				],
                'range' => [
                    '%' => [
                        'max'  => 1000,
                        'step' => 10,
                    ],
                ],
                'condition' => [
					'eael_hover_effect_contrast_is_on' => 'yes',
					'eael_hover_effect_filter_popover' => 'yes',
				],
            ]
        );

		//Grayscal
        $element->add_control(
			'eael_hover_effect_grayscale_is_on',
			[
				'label' => __( 'Grayscale', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [ 
					'eael_hover_effect_filter_popover' => 'yes',
				],
			]
		);

        $element->add_control(
            'eael_hover_effect_grayscal',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
					'size' => 40,
				],
                'range' => [
                    '%' => [
                        'max'  => 100,
                        'step' => 10,
                    ],
                ],
                'condition' => [
					'eael_hover_effect_grayscale_is_on' => 'yes',
					'eael_hover_effect_filter_popover'  => 'yes',
				],
            ]
        );

		//Invert
        $element->add_control(
			'eael_hover_effect_invert_is_on',
			[
				'label' => __( 'Invert', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [ 
					'eael_hover_effect_filter_popover' => 'yes',
				],
			]
		);

        $element->add_control(
            'eael_hover_effect_invert',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
					'size' => 70,
				],
                'range' => [
                    '%' => [
                        'max'  => 100,
                        'step' => 10,
                    ],
                ],
                'condition' => [
					'eael_hover_effect_invert_is_on'   => 'yes',
					'eael_hover_effect_filter_popover' => 'yes',
				],
            ]
        );

		//Saturate
        $element->add_control(
			'eael_hover_effect_saturate_is_on',
			[
				'label' => __( 'Saturate', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [ 
					'eael_hover_effect_filter_popover' => 'yes',
				],
			]
		);

        $element->add_control(
            'eael_hover_effect_saturate',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
					'size' => 50,
				],
                'range' => [
                    '%' => [
                        'max'  => 1000,
                        'step' => 10,
                    ],
                ],
                'condition' => [
					'eael_hover_effect_saturate_is_on' => 'yes',
					'eael_hover_effect_filter_popover' => 'yes',
				],
            ]
        );

		//Sepia
        $element->add_control(
			'eael_hover_effect_sepia_is_on',
			[
				'label' => __( 'Sepia', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [ 
					'eael_hover_effect_filter_popover' => 'yes',
				],
			]
		);

        $element->add_control(
            'eael_hover_effect_sepia',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER,
                'default' => [
					'size' => 50,
				],
                'range' => [
                    '%' => [
                        'max'  => 100,
                        'step' => 10,
                    ],
                ],
                'condition' => [
					'eael_hover_effect_sepia_is_on'    => 'yes',
					'eael_hover_effect_filter_popover' => 'yes',
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
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);
        $element->start_popover();
		$element->add_responsive_control(
            'eael_hover_effect_offset_left',
            [
                'label'              => __( 'Offset Top', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'size_units'         => ['px', '%'],
                'default' => [
					'unit' => 'px',
					'size' => 5,
				],
                'range' => [
                    'px' => [
                        'max'  => 500,
						'min'  => -500,
                        'step' => 1,
                    ],
                    '%' => [
                        'max'  => 100,
						'min'  => -100,
                        'step' => 1,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_offset_popover' => 'yes',
				],
            ]
        );

        $element->add_responsive_control(
            'eael_hover_effect_offset_top',
            [
                'label'              => __( 'Offset Left', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true, 
                'size_units'         => ['px', '%'],
                'default' => [
					'unit' => 'px',
					'size' => 5,
				],
                'range' => [
                    'px' => [
                        'max'  => 500,
						'min'  => -500,
                        'step' => 1,
                    ],
                    '%' => [
                        'max'  => 100,
						'min'  => -100,
                        'step' => 1,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_offset_popover' => 'yes',
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
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);
        
        //Rotate
        $element->start_popover();
        $element->add_control(
			'eael_hover_effect_rotate_is_on',
			[
				'label' => __( 'Rotate', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [
					'eael_hover_effect_transform_popover' => 'yes',
				],
			]
		);

		//RotateX
        $element->add_responsive_control(
			'eael_hover_effect_transform_rotatex',
			[
				'label' => esc_html__( 'RotateX (deg)', 'essential-addons-for-elementor-lite' ),
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
					'eael_hover_effect_rotate_is_on'      => 'yes',
					'eael_hover_effect_transform_popover' => 'yes',
				],
			]
		);

		//RotateY
        $element->add_responsive_control(
			'eael_hover_effect_transform_rotatey',
			[
				'label' => esc_html__( 'RotateY (deg)', 'essential-addons-for-elementor-lite' ),
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
					'eael_hover_effect_rotate_is_on'      => 'yes',
					'eael_hover_effect_transform_popover' => 'yes',
				],
			]
		);

		//RotateZ
        $element->add_responsive_control(
			'eael_hover_effect_transform_rotatez',
			[
				'label' => esc_html__( 'RotateZ (deg)', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => -180,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 5,
				],
                'condition' => [
					'eael_hover_effect_rotate_is_on'      => 'yes',
					'eael_hover_effect_transform_popover' => 'yes',
				],
			]
		);

        //Scale
        $element->add_control(
			'eael_hover_effect_scale_is_on',
			[
				'label' => __( 'Scale', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [
					'eael_hover_effect_transform_popover' => 'yes', 
				],
			]
		);

		//ScaleX
        $element->add_responsive_control(
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
					'size' => 0.9,
				],
                'condition' => [
					'eael_hover_effect_scale_is_on'       => 'yes',
					'eael_hover_effect_transform_popover' => 'yes',
				],
			]
		);

		//ScaleY
        $element->add_responsive_control(
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
					'size' => 0.9,
				],
                'condition' => [
					'eael_hover_effect_scale_is_on'       => 'yes',
					'eael_hover_effect_transform_popover' => 'yes',
				],
			]
		);

        //Skew
        $element->add_control(
			'eael_hover_effect_skew_is_on',
			[
				'label' => __( 'Skew', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [
					'eael_hover_effect_transform_popover' => 'yes', 
				],
			]
		);

		//SkewX
        $element->add_responsive_control(
			'eael_hover_effect_transform_skewx',
			[
				'label' => esc_html__( 'SkewX (deg)', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => 0,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 5,
				],
                'condition' => [
					'eael_hover_effect_skew_is_on'        => 'yes',
					'eael_hover_effect_transform_popover' =>  'yes',
				],
			]
		);

		//SkewY
        $element->add_responsive_control(
			'eael_hover_effect_transform_skewy',
			[
				'label' => esc_html__( 'SkewY (deg)', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
						'min' => 0,
						'max' => 180,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 5,
				],
                'condition' => [
					'eael_hover_effect_skew_is_on'        => 'yes',
					'eael_hover_effect_transform_popover' =>  'yes',
				],
			]
		);

        $element->end_popover();

		// General Settings
		$element->add_control(
			'eael_hover_effect_general_settings_heading',
			[
				'label'     => __( 'General Settings', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);

		//Duration
        $element->add_control(
			'eael_hover_effect_general_settings_duration',
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
					'size' => 1000,
                ],
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
            ]
		);

        //Delay
		$element->add_control(
			'eael_hover_effect_general_settings_delay',
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
					'eael_hover_effect_switch' => 'yes',
				]
            ]
		);

        $element->add_control(
			'eael_hover_effect_general_settings_easing',
			[
				'label'                 => __( 'Easing', 'essential-addons-for-elementor-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'ease',
				'frontend_available'    => true,
				'options'               => [
					'ease'        => __( 'Default', 'essential-addons-for-elementor-lite' ), 
					'ease-in'     => __( 'Ease-in', 'essential-addons-for-elementor-lite' ), 
                    'ease-out'    => __( 'Ease-out', 'essential-addons-for-elementor-lite' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'essential-addons-for-elementor-lite' ), 
                ],
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
        );

        $element->end_controls_tab();

        /**
         * Hovar Tab
         */
        $element->start_controls_tab(
			'eael_hover_effect_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
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
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
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
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'max'  => 1,
                        'step' => 0.01,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_opacity_popover_hover' => 'yes',
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
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);

        $element->start_popover();

        //Blur Start
        $element->add_control(
			'eael_hover_effect_blur_hover_is_on',
			[
				'label' => __( 'Blur', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [
					'eael_hover_effect_filter_hover_popover' => 'yes',
				]
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
                'condition' => [
					'eael_hover_effect_blur_hover_is_on'     => 'yes',
					'eael_hover_effect_filter_hover_popover' => 'yes',
				],
            ]
        );
        //Blur End

        //Contrast Start
        $element->add_control(
			'eael_hover_effect_contrast_hover_is_on',
			[
				'label' => __( 'Contrast', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [
					'eael_hover_effect_filter_hover_popover' => 'yes', 
				],
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
                'condition' => [
					'eael_hover_effect_contrast_hover_is_on' => 'yes',
					'eael_hover_effect_filter_hover_popover' => 'yes',
				],
            ]
        );
        //Contrast End

        //Grayscale Start
        $element->add_control(
			'eael_hover_effect_grayscale_hover_is_on',
			[
				'label' => __( 'Grayscale', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [
					'eael_hover_effect_filter_hover_popover' => 'yes', 
				],
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
                'condition' => [
					'eael_hover_effect_grayscale_hover_is_on' => 'yes',
					'eael_hover_effect_filter_hover_popover'  => 'yes',
				],
            ]
        );
        //Grayscale End

        //Invert Start
        $element->add_control(
			'eael_hover_effect_invert_hover_is_on',
			[
				'label' => __( 'Invert', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [
					'eael_hover_effect_filter_hover_popover' => 'yes', 
				],
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
                'condition' => [
					'eael_hover_effect_invert_hover_is_on'   => 'yes',
					'eael_hover_effect_filter_hover_popover' => 'yes',
				],
            ]
        );
        //Invert End

        //Saturate Start
        $element->add_control(
			'eael_hover_effect_saturate_hover_is_on',
			[
				'label' => __( 'Saturate', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [
					'eael_hover_effect_filter_hover_popover' => 'yes', 
				],
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
                'condition' => [
					'eael_hover_effect_saturate_hover_is_on' => 'yes', 
					'eael_hover_effect_filter_hover_popover' => 'yes', 
				],
            ]
        );
        //Saturate End

        //Sepia Start
        $element->add_control(
			'eael_hover_effect_sepia_hover_is_on',
			[
				'label' => __( 'Sepia', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [
					'eael_hover_effect_filter_hover_popover' => 'yes',
				],
			]
		);

        $element->add_control(
            'eael_hover_effect_sepia_hover',
            [
                'label'              => __( 'Value', 'essential-addons-for-elementor-lite' ),
                'type'               => Controls_Manager::SLIDER, 
                'frontend_available' => true,
                'default' => [
					'size' => 1,
				],
                'range' => [
                    '%' => [
                        'max'  => 100,
                        'step' => 10,
                    ],
                ],
                'condition' => [
					'eael_hover_effect_sepia_hover_is_on'    => 'yes',
					'eael_hover_effect_filter_hover_popover' => 'yes',
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
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);
        $element->start_popover();
		$element->add_responsive_control(
            'eael_hover_effect_offset_hover_left',
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
                        'max'  => 500,
                        'min'  => -500,
                        'step' => 1,
                    ],
                    '%' => [
                        'max'  => 100,
                        'min'  => -100,
                        'step' => 1,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_offset_hover_popover' => 'yes',
				],
            ]
        );
		
        $element->add_responsive_control(
            'eael_hover_effect_offset_hover_top',
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
                        'max'  => 500,
                        'min'  => -500,
                        'step' => 1,
                    ],
                    '%' => [
                        'max'  => 100,
                        'min'  => -100,
                        'step' => 1,
                    ],
                ],
				'condition' => [
					'eael_hover_effect_offset_hover_popover' => 'yes',
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
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);
        
        //Rotate Start
        $element->start_popover();
        $element->add_control(
			'eael_hover_effect_rotate_hover_is_on',
			[
				'label' => __( 'Rotate', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [
					'eael_hover_effect_transform_hover_popover' => 'yes',
				]
			]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_hover_rotatex',
			[
                'label' => esc_html__( 'RotateX (deg)', 'essential-addons-for-elementor-lite' ),
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
					'eael_hover_effect_rotate_hover_is_on'      => 'yes',
					'eael_hover_effect_transform_hover_popover' => 'yes',
				],
            ]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_hover_rotatey',
			[
                'label' => esc_html__( 'RotateY (deg)', 'essential-addons-for-elementor-lite' ),
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
					'eael_hover_effect_rotate_hover_is_on'      => 'yes', 
					'eael_hover_effect_transform_hover_popover' => 'yes',
				],
            ]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_hover_rotatez',
			[
                'label' => esc_html__( 'RotateZ (deg)', 'essential-addons-for-elementor-lite' ),
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
					'eael_hover_effect_rotate_hover_is_on'      => 'yes',
					'eael_hover_effect_transform_hover_popover' => 'yes',
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
				'condition' => [
					'eael_hover_effect_transform_hover_popover' => 'yes', 
				],
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
					'eael_hover_effect_scale_hover_is_on'       => 'yes', 
					'eael_hover_effect_transform_hover_popover' => 'yes',
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
					'size' => 0,
				],
                'condition' => [
					'eael_hover_effect_scale_hover_is_on'       => 'yes',
					'eael_hover_effect_transform_hover_popover' => 'yes',
				],
            ]
		);
        //Scale End

        //Skew
        $element->add_control(
			'eael_hover_effect_skew_hover_is_on',
			[
				'label' => __( 'Skew', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => [
					'eael_hover_effect_transform_hover_popover' => 'yes',
				],
			]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_hover_skewx',
			[
                'label' => esc_html__( 'SkewX (deg)', 'essential-addons-for-elementor-lite' ),
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
					'eael_hover_effect_skew_hover_is_on'        => 'yes',
					'eael_hover_effect_transform_hover_popover' => 'yes',
				],
            ]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_hover_skewy',
			[
                'label' => esc_html__( 'SkewY (deg)', 'essential-addons-for-elementor-lite' ),
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
					'eael_hover_effect_skew_hover_is_on'        => 'yes',
					'eael_hover_effect_transform_hover_popover' =>  'yes',
				],
            ]
		);
        //Skew End
        $element->end_popover();

		// General Settings
		$element->add_control(
			'eael_hover_effect_general_settings_hover_heading',
			[
				'label'     => __( 'General Settings', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);

		//Duration
        $element->add_control(
			'eael_hover_effect_general_settings_hover_duration',
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
					'eael_hover_effect_switch' => 'yes',
				]
            ]
		);

        //Delay
		$element->add_control(
			'eael_hover_effect_general_settings_hover_delay',
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
					'eael_hover_effect_switch' => 'yes',
				]
            ]
		);

		//Easing
        $element->add_control(
			'eael_hover_effect_general_settings_hover_easing',
			[
				'label'                 => __( 'Easing', 'essential-addons-for-elementor-lite' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'ease',
				'frontend_available'    => true,
				'options'               => [
					'ease'        => __( 'Default', 'essential-addons-for-elementor-lite' ), 
					'ease-in'     => __( 'Ease-in', 'essential-addons-for-elementor-lite' ), 
                    'ease-out'    => __( 'Ease-out', 'essential-addons-for-elementor-lite' ), 
                    'ease-in-out' => __( 'Ease-in-out', 'essential-addons-for-elementor-lite' ), 
                ],
				'condition' => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
        );

		$element->add_control(
			'eael_hover_effect_hover_tilt',
			[
				'label'        => esc_html__( 'Tilt Effect', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Off', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'condition'    => [
					'eael_hover_effect_switch' => 'yes',
				]
			]
		);

        $element->end_controls_tab();
        $element->end_controls_tabs();

		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$settings = $element->get_settings_for_display();

		/**
		 * Normal Effect Options
		 */
		//Opacity
		if( !empty( $settings['eael_hover_effect_switch'] ) ) {
			if( !empty( $settings['eael_hover_effect_opacity']['size'] ) ) {
				$opacity_settings = [
					'opacity' => $settings['eael_hover_effect_opacity']['size'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_opacity', wp_json_encode( $opacity_settings ) );
			}
	
			//Blur Effect
			if( 'yes' == $settings['eael_hover_effect_blur_is_on'] ) {
				$blur_settings = [
					'blur' => $settings['eael_hover_effect_blur']['size']
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_blur_effect', wp_json_encode( $blur_settings ) );
			}
	
			//Contrast Effect
			if( 'yes' == $settings['eael_hover_effect_contrast_is_on'] ) {
				$contrast_settings = [
					'contrast' => $settings['eael_hover_effect_contrast']['size']
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_contrast_effect', wp_json_encode( $contrast_settings ) );
			}
	
			//Grayscale Effect
			if( 'yes' == $settings['eael_hover_effect_grayscale_is_on'] ) {
				$grayscale_settings = [
					'grayscale' => $settings['eael_hover_effect_grayscal']['size']
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_grayscale_effect', wp_json_encode( $grayscale_settings ) );
			}
	
			//Invert Effect
			if( 'yes' == $settings['eael_hover_effect_invert_is_on'] ) {
				$invert_settings = [
					'invert' => $settings['eael_hover_effect_invert']['size']
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_invert_effect', wp_json_encode( $invert_settings ) );
			}
	
			//Saturate Effect
			if( 'yes' == $settings['eael_hover_effect_saturate_is_on'] ) {
				$saturate_settings = [
					'saturate' => $settings['eael_hover_effect_saturate']['size']
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_saturate_effect', wp_json_encode( $saturate_settings ) );
			}
	
			//Sepia Effect
			if( 'yes' == $settings['eael_hover_effect_sepia_is_on'] ) {
				$sepia_settings = [
					'sepia' => $settings['eael_hover_effect_sepia']['size']
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_sepia_effect', wp_json_encode( $sepia_settings ) );
			}
	
			//Offset Top
			if( !empty( $settings['eael_hover_effect_offset_top']['size'] ) ) {
				$offset_top_settings = [
					'size' => $settings['eael_hover_effect_offset_top']['size'],
					'unit' => $settings['eael_hover_effect_offset_top']['unit'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_offset_top', wp_json_encode( $offset_top_settings ) );
			}
	
			//Offset Left
			if( !empty( $settings['eael_hover_effect_offset_left']['size'] ) ) {
				$offset_left_settings = [
					'size' => $settings['eael_hover_effect_offset_left']['size'],
					'unit' => $settings['eael_hover_effect_offset_left']['unit'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_offset_left', wp_json_encode( $offset_left_settings ) );
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
	
			//Transition Duration
			if( !empty( $settings['eael_hover_effect_general_settings_duration']['size'] ) ) {
				$transition_duration_settings = [
					'transitionDuration' => $settings['eael_hover_effect_general_settings_duration']['size'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_duration', wp_json_encode( $transition_duration_settings ) );
			}
	
			//Transition Delay
			if( !empty( $settings['eael_hover_effect_general_settings_delay']['size'] ) ) {
				$transition_delay_settings = [
					'transitionDelay' => $settings['eael_hover_effect_general_settings_delay']['size'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_delay', wp_json_encode( $transition_delay_settings ) );
			}
	
			//Transition Easing
			if( !empty( $settings['eael_hover_effect_general_settings_easing'] ) ) {
				$transition_easing_settings = [
					'transitionEasing' => $settings['eael_hover_effect_general_settings_easing'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_easing', wp_json_encode( $transition_easing_settings ) );
			}
	
			/**
			 * Hover Interactions Options
			 */
			//Opacity
			if( !empty( $settings['eael_hover_effect_opacity_hover']['size'] ) ) {
				$opacity_settings = [
					'opacity' => $settings['eael_hover_effect_opacity_hover']['size'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_opacity_hover', wp_json_encode( $opacity_settings ) );
			}
	
			//blur
			if( 'yes' == $settings['eael_hover_effect_blur_hover_is_on'] ) {
				$blur_settings = [
					'blur' => $settings['eael_hover_effect_blur_hover']['size']
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_blur_hover_effect', wp_json_encode( $blur_settings ) );
			}
	
			//Contrast
			if( 'yes' == $settings['eael_hover_effect_contrast_hover_is_on'] ) {
				$contrast_settings = [
					'contrast' => $settings['eael_hover_effect_contrast_hover']['size']
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_contrast_hover_effect', wp_json_encode( $contrast_settings ) );
			}
	
			//Grayscale
			if( 'yes' == $settings['eael_hover_effect_grayscale_hover_is_on'] ) {
				$grayscale_settings = [
					'grayscale' => $settings['eael_hover_effect_grayscal_hover']['size']
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_grayscal_hover_effect', wp_json_encode( $grayscale_settings ) );
			}
	
			//Invert
			if( 'yes' == $settings['eael_hover_effect_invert_hover_is_on'] ) {
				$invert_settings = [
					'invert' => $settings['eael_hover_effect_invert_hover']['size']
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_invert_hover_effect', wp_json_encode( $invert_settings ) );
			}
	
			//Saturate
			if( 'yes' == $settings['eael_hover_effect_saturate_hover_is_on'] ) {
				$saturate_settings = [
					'saturate' => $settings['eael_hover_effect_saturate_hover']['size']
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_saturate_hover_effect', wp_json_encode( $saturate_settings ) );
			}
	
			//Sepia
			if( 'yes' == $settings['eael_hover_effect_sepia_hover_is_on'] ) {
				$sepia_settings = [
					'sepia' => $settings['eael_hover_effect_sepia_hover']['size']
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_sepia_hover_effect', wp_json_encode( $sepia_settings ) );
			}
	
			//Offset Top
			if( !empty( $settings['eael_hover_effect_offset_hover_top']['size'] ) ) {
				$offset_top_settings = [
					'size' => $settings['eael_hover_effect_offset_hover_top']['size'],
					'unit' => $settings['eael_hover_effect_offset_hover_top']['unit'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_offset_hover_top', wp_json_encode( $offset_top_settings ) );
			}
	
			//Offset Left
			if( !empty( $settings['eael_hover_effect_offset_hover_left']['size'] ) ) {
				$offset_left_settings = [
					'size' => $settings['eael_hover_effect_offset_hover_left']['size'],
					'unit' => $settings['eael_hover_effect_offset_hover_left']['unit'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_offset_hover_left', wp_json_encode( $offset_left_settings ) );
			}
	
			//Rotate
			if( 'yes' == $settings['eael_hover_effect_rotate_hover_is_on'] ) {
				$rotate_settings = [
					'rotate_x' => $settings['eael_hover_effect_transform_hover_rotatex']['size'],
					'rotate_y' => $settings['eael_hover_effect_transform_hover_rotatey']['size'],
					'rotate_z' => $settings['eael_hover_effect_transform_hover_rotatez']['size'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_rotate_hover_effect', wp_json_encode( $rotate_settings ) );
			}
	
			//Scale Effect
			if( 'yes' == $settings['eael_hover_effect_scale_hover_is_on'] ) {
				$scale_settings = [
					'scale_x' => $settings['eael_hover_effect_transform_hover_scalex']['size'],
					'scale_y' => $settings['eael_hover_effect_transform_hover_scaley']['size'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_scale_hover_effect', wp_json_encode( $scale_settings ) );
			}
	
			//Skew Effect
			if( 'yes' == $settings['eael_hover_effect_skew_hover_is_on'] ) {
				$skew_settings = [
					'skew_x' => $settings['eael_hover_effect_transform_hover_skewx']['size'],
					'skew_y' => $settings['eael_hover_effect_transform_hover_skewy']['size'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_skew_hover_effect', wp_json_encode( $skew_settings ) );
			}
	
			//Transition Duration
			if( !empty( $settings['eael_hover_effect_general_settings_hover_duration']['size'] ) ) {
				$transition_duration_settings = [
					'transitionDuration' => $settings['eael_hover_effect_general_settings_hover_duration']['size'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_hover_duration', wp_json_encode( $transition_duration_settings ) );
			}
	
			//Transition Delay
			if( !empty( $settings['eael_hover_effect_general_settings_hover_delay']['size'] ) ) {
				$transition_delay_settings = [
					'transitionDelay' => $settings['eael_hover_effect_general_settings_hover_delay']['size'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_hover_delay', wp_json_encode( $transition_delay_settings ) );
			}
	
			//Transition Easing
			if( !empty( $settings['eael_hover_effect_general_settings_hover_easing'] ) ) {
				$transition_easing_settings = [
					'transitionEasing' => $settings['eael_hover_effect_general_settings_hover_easing'],
				];
				$element->add_render_attribute( '_wrapper', 'data-eael_hover_easing', wp_json_encode( $transition_easing_settings ) );
			}
			
			//Tilt
			if( !empty( $settings['eael_hover_effect_hover_tilt'] ) ) {
				$element->add_render_attribute( '_wrapper', 'data-eaeltilt', 'eael_tilt' );
			}
			
			$element->add_render_attribute( '_wrapper', 'class', 'eael_hover_effect' );

			?>
			<?php
		}
	}

}

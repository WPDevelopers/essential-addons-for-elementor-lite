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

        //Opacity
        $element->add_control(
			'eael_hover_effect_opacity_popover',
			[
				'label'              => __( 'Opacity', 'essential-addons-for-elementor-lite' ), 
                'type'               => Controls_Manager::POPOVER_TOGGLE, 
                'return_value'       => 'yes', 
                'frontend_available' => true,
			]
		);

        $element->start_popover();
        $element->add_control(
            'eael_hover_effect_opacity',
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
                'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'opacity: {{SIZE}};',
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
                'frontend_available' => true,
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
					'{{WRAPPER}} .elementor-widget-container' => 'filter: grayscale({{SIZE}}{{UNIT}});',
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

        $element->add_responsive_control(
			'eael_hover_effect_transform_rotatex',
			[
                'label'     => __( 'Rotate X', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
                    'sizes' => [
                        'from' => 0,
						'to'   => 45,
                    ],
					'unit'  => 'deg',
                ],
				'range'     => [
                    'deg' => [
                        'min' => -180,
						'max' => 180,
                    ],
                ],
				'labels'    => [
                    __( 'From', 'essential-addons-for-elementor-lite' ),
					__( 'To', 'essential-addons-for-elementor-lite' ),
                ],
				'scales'    => 1,
				'handles'   => 'range',
                'condition' => [
					'eael_hover_effect_rotate_is_on' => 'yes', 
				],
            ]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_rotatey',
			[
                'label'     => __( 'Rotate Y', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
                    'sizes' => [
                        'from' => 0,
						'to'   => 45,
                    ],
					'unit'  => 'deg',
                ],
				'range'     => [
                    'deg' => [
                        'min' => -180,
						'max' => 180,
                    ],
                ],
				'labels'    => [
                    __( 'From', 'essential-addons-for-elementor-lite' ),
					__( 'To', 'essential-addons-for-elementor-lite' ),
                ],
				'scales'    => 1,
				'handles'   => 'range',
                'condition' => [
					'eael_hover_effect_rotate_is_on' => 'yes', 
				],
            ]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_rotatez',
			[
                'label'     => __( 'Rotate Z', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
                    'sizes' => [
                        'from' => 0,
						'to'   => 45,
                    ],
					'unit'  => 'deg',
                ],
				'range'     => [
                    'deg' => [
                        'min' => -180,
						'max' => 180,
                    ],
                ],
				'labels'    => [
                    __( 'From', 'essential-addons-for-elementor-lite' ),
					__( 'To', 'essential-addons-for-elementor-lite' ),
                ],
				'scales'    => 1,
				'handles'   => 'range',
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

        $element->add_responsive_control(
			'eael_hover_effect_transform_scalex',
			[
                'label'     => __( 'Scale X', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
                'separator' => 'after',
				'default'   => [
                    'sizes' => [
                        'from' => 0,
						'to'   => 45,
                    ],
					'unit'  => 'deg',
                ],
				'range'     => [
                    'deg' => [
                        'min' => -180,
						'max' => 180,
                    ],
                ],
				'labels'    => [
                    __( 'From', 'essential-addons-for-elementor-lite' ),
					__( 'To', 'essential-addons-for-elementor-lite' ),
                ],
				'scales'    => 1,
				'handles'   => 'range',
                'condition' => [
					'eael_hover_effect_scale_is_on' => 'yes', 
				],
            ]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_scaley',
			[
                'label'     => __( 'Scale Y', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
                    'sizes' => [
                        'from' => 0,
						'to'   => 45,
                    ],
					'unit'  => 'deg',
                ],
				'range'     => [
                    'deg' => [
                        'min' => -180,
						'max' => 180,
                    ],
                ],
				'labels'    => [
                    __( 'From', 'essential-addons-for-elementor-lite' ),
					__( 'To', 'essential-addons-for-elementor-lite' ),
                ],
				'scales'    => 1,
				'handles'   => 'range',
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

        $element->add_responsive_control(
			'eael_hover_effect_transform_skewx',
			[
                'label'     => __( 'Skew X', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
                'separator' => 'before',
				'default'   => [
                    'sizes' => [
                        'from' => 0,
						'to'   => 45,
                    ],
					'unit'  => 'deg',
                ],
				'range'     => [
                    'deg' => [
                        'min' => -180,
						'max' => 180,
                    ],
                ],
				'labels'    => [
                    __( 'From', 'essential-addons-for-elementor-lite' ),
					__( 'To', 'essential-addons-for-elementor-lite' ),
                ],
				'scales'    => 1,
				'handles'   => 'range',
                'condition' => [
					'eael_hover_effect_skew_is_on' => 'yes', 
				],
            ]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_skewy',
			[
                'label'     => __( 'Skew Y', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
                    'sizes' => [
                        'from' => 0,
						'to'   => 45,
                    ],
					'unit'  => 'deg',
                ],
				'range'     => [
                    'deg' => [
                        'min' => -180,
						'max' => 180,
                    ],
                ],
				'labels'    => [
                    __( 'From', 'essential-addons-for-elementor-lite' ),
					__( 'To', 'essential-addons-for-elementor-lite' ),
                ],
				'scales'    => 1,
				'handles'   => 'range',
                'condition' => [
					'eael_hover_effect_skew_is_on' => 'yes', 
				],
            ]
		);

        //Translate
        $element->add_control(
			'eael_hover_effect_translate_is_on',
			[
				'label' => __( 'Translate', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_translatex',
			[
                'label'     => __( 'Translate X', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
                'separator' => 'before',
				'default'   => [
                    'sizes' => [
                        'from' => 0,
						'to'   => 45,
                    ],
					'unit'  => 'deg',
                ],
				'range'     => [
                    'deg' => [
                        'min' => -180,
						'max' => 180,
                    ],
                ],
				'labels'    => [
                    __( 'From', 'essential-addons-for-elementor-lite' ),
					__( 'To', 'essential-addons-for-elementor-lite' ),
                ],
				'scales'    => 1,
				'handles'   => 'range',
                'condition' => [
					'eael_hover_effect_translate_is_on' => 'yes', 
				],
            ]
		);

        $element->add_responsive_control(
			'eael_hover_effect_transform_translatey',
			[
                'label'     => __( 'Translate Y', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
                    'sizes' => [
                        'from' => 0,
						'to'   => 45,
                    ],
					'unit'  => 'deg',
                ],
				'range'     => [
                    'deg' => [
                        'min' => -180,
						'max' => 180,
                    ],
                ],
				'labels'    => [
                    __( 'From', 'essential-addons-for-elementor-lite' ),
					__( 'To', 'essential-addons-for-elementor-lite' ),
                ],
				'scales'    => 1,
				'handles'   => 'range',
                'condition' => [
					'eael_hover_effect_translate_is_on' => 'yes', 
				],
            ]
		);

        $element->end_popover();

        $element->end_controls_tab();

        //Hover Tab
        $element->start_controls_tab(
			'eael_hover_effect_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
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

        $element->end_controls_tab();
        $element->end_controls_tabs();
		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$wrapper_link_settings = $element->get_settings_for_display( 'eael_hover_effect' );

	}

}
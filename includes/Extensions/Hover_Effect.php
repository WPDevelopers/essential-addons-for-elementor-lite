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

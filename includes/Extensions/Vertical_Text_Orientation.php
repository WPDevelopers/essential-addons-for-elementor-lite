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
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/frontend/before_render', [ $this, 'before_render' ], 100 );
	}

	public function register_controls( $element ) {
		$element->start_controls_section(
			'eael_vertical_text_orientation_section',
			[
				'label' => __( '<i class="eaicon-logo"></i> Vertical Text Orientation', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_ADVANCED
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
                'prefix_class' => 'eael_vto-vertical eael_vto-',
                'condition' => [
                    'eael_vertical_text_orientation_switch' => 'yes',
                ],
            ]
        );

		
		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$settings = $element->get_settings_for_display();
	}
}
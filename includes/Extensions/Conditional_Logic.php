<?php

namespace Essential_Addons_Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Conditional_Logic {

	/**
	 * Initialize hooks
	 */
	public function __construct() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ] );
		add_filter( 'elementor/frontend/widget/should_render', [ $this, 'content_render' ], 10, 2 );
	}

	public function register_controls( $element ) {
		$element->start_controls_section(
			'eael_conditional_logic_section',
			[
				'label' => __( '<i class="eaicon-logo"></i> Conditional Logic', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_ADVANCED
			]
		);

		$element->add_control(
			'eael_cl_enable',
			[
				'label'        => __( 'Enable Logic', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

		$element->add_control(
			'eael_cl_visibility_action',
			[
				'label'     => __( 'Visibility Action', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'show' => [
						'title' => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-eye',
					],
					'hide' => [
						'title' => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-eye-slash',
					],
				],
				'default'   => 'hide',
				'toggle'    => false,
				'condition' => [
					'eael_cl_enable' => 'yes',
				]
			]
		);

		$element->end_controls_section();
	}

	public function parse_arg( $arg ) {
		$arg = wp_parse_args( $arg, [
			'eael_cl_enable'            => '',
			'eael_cl_visibility_action' => '',
		] );

		return $arg;
	}

	public function content_render( $should_render, Element_Base $element ) {
		$settings = $element->get_settings();
		$settings = $this->parse_arg( $settings );

		if ( $settings['eael_cl_enable'] === 'yes' ) {
			if ( $settings['eael_cl_visibility_action'] === 'show' ) {
				return true;
			} elseif ( $settings['eael_cl_visibility_action'] === 'hide' ) {
				return false;
			}
		}

		return $should_render;
	}

}

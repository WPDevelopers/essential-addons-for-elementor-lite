<?php

namespace Essential_Addons_Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Element_Base;
use Elementor\Repeater;

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
					'show'            => [
						'title' => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-eye',
					],
					'hide'            => [
						'title' => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-eye-slash',
					],
					'forcefully_hide' => [
						'title' => esc_html__( 'Hide without condition', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-ban',
					],
				],
				'default'   => 'hide',
				'toggle'    => false,
				'condition' => [
					'eael_cl_enable' => 'yes',
				]
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'logic_type',
			[
				'label'   => __( 'Type', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'login_status',
				'options' => [
					'login_status' => __( 'Login Status', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$repeater->add_control(
			'login_status_operand',
			[
				'label'     => __( 'Login Status', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'logged_in'     => [
						'title' => esc_html__( 'Logged In', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-user',
					],
					'not_logged_in' => [
						'title' => esc_html__( 'Not Logged In', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-user-slash',
					],
				],
				'default'   => 'not_logged_in',
				'toggle'    => false,
				'condition' => [
					'logic_type' => 'login_status',
				]
			]
		);

		$element->add_control(
			'eael_cl_logics',
			[
				'label'       => __( 'Logics', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'column_type'          => 'remove',
						'column_heading_title' => esc_html__( '', 'essential-addons-for-elementor-lite' ),
					],
				],
				'title_field' => '{{{ ea_conditional_logic_type_title(logic_type) }}}',
				'condition'   => [
					'eael_cl_enable'             => 'yes',
					'eael_cl_visibility_action!' => 'forcefully_hide',
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
			switch ( $settings['eael_cl_visibility_action'] ) {
				case 'show':
					return true;
					break;
				case 'hide':
					return false;
					break;
				case 'forcefully_hide':
					return false;
			}
		}

		return $should_render;
	}

}

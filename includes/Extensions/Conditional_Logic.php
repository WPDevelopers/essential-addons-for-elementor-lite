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

		$element->add_control(
			'eael_cl_action_apply_if',
			[
				'label'     => __( 'Action Applicable if', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'all' => [
						'title' => esc_html__( 'True All Logic', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-dice-six',
					],
					'any' => [
						'title' => esc_html__( 'True Any Logic', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-dice-one',
					],
				],
				'default'   => 'any',
				'toggle'    => false,
				'condition' => [
					'eael_cl_enable'             => 'yes',
					'eael_cl_visibility_action!' => 'forcefully_hide',
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
					'boolean'      => __( 'Boolean', 'essential-addons-for-elementor-lite' ),
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

		$repeater->add_control(
			'boolean_operand',
			[
				'label'     => __( 'Boolean Type', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'true'  => [
						'title' => esc_html__( 'True', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-user',
					],
					'false' => [
						'title' => esc_html__( 'False', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'fa fa-user-slash',
					],
				],
				'default'   => 'true',
				'toggle'    => false,
				'condition' => [
					'logic_type' => 'boolean',
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
						'logic_type'           => 'login_status',
						'login_status_operand' => 'not_logged_in',
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
			'eael_cl_logics'            => [],
			'eael_cl_action_apply_if'   => '',
		] );

		return $arg;
	}

	public function check_logics( $settings ) {
		$return                = false;
		$needed_any_logic_true = $settings['eael_cl_action_apply_if'] === 'any';
		$needed_all_logic_true = $settings['eael_cl_action_apply_if'] === 'all';
		foreach ( $settings['eael_cl_logics'] as $cl_logic ) {
			switch ( $cl_logic['logic_type'] ) {
				case 'login_status':
					$return = $cl_logic['login_status_operand'] === 'logged_in' ? is_user_logged_in() : ! is_user_logged_in();
					if ( $needed_any_logic_true && $return ) {
						break( 2 );
					}
					if ( $needed_all_logic_true && ! $return ) {
						break( 2 );
					}
					break;
				case 'boolean':
					$return = $cl_logic['boolean_operand'] === 'true' ? true : false;
					if ( $needed_any_logic_true && $return ) {
						break( 2 );
					}
					if ( $needed_all_logic_true && ! $return ) {
						break( 2 );
					}
					break;
			}
		}

		return $return;
	}

	public function content_render( $should_render, Element_Base $element ) {
		$settings = $element->get_settings();
		$settings = $this->parse_arg( $settings );

		if ( $settings['eael_cl_enable'] === 'yes' ) {
			switch ( $settings['eael_cl_visibility_action'] ) {
				case 'show':
					return $this->check_logics( $settings ) ? true : false;
					break;
				case 'hide':
					return $this->check_logics( $settings ) ? false : true;
					break;
				case 'forcefully_hide':
					return false;
			}
		}

		return $should_render;
	}

}

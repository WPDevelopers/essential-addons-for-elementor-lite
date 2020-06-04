<?php

namespace Essential_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Login_Register
 * @package Essential_Addons_Elementor\Elements
 */
class Login_Register extends Widget_Base {

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'eael-login-register';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return esc_html__( 'Login | Register Form', EAEL_TEXTDOMAIN );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-lock-user'; //@TODO; use better icon later
	}

	/**
	 * @inheritDoc
	 */
	public function get_keywords() {
		return [
			'login',
			'ea login',
			'register',
			'ea register',
			'registration',
			'ea registration',
			'sign in',
			'sign out',
			'logout',
			'auth',
			'authentication',
			'user-registration',
			'google',
			'facebook',
			'ea',
			'essential addons',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	/**
	 * @inheritDoc
	 */
	protected function _register_controls() {
		/*----Content Tab----*/
		$this->init_content_login_fields_controls();


		/*----Style Tab----*/


	}


	/**
	 * It adds controls related to Login Form Fields section to the Widget Content Tab
	 */
	protected function init_content_login_fields_controls() {
		$this->start_controls_section( 'section_content_login_fields', [
			'label' => __( 'Login Form Fields', EAEL_TEXTDOMAIN ),
		] );

		$this->add_control( 'login_label_types', [
			'label'   => __( 'Login Fields Label', EAEL_TEXTDOMAIN ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'default' => __( 'Default', EAEL_TEXTDOMAIN ),
				'custom'  => __( 'Custom', EAEL_TEXTDOMAIN ),
				'none'    => __( 'Hide', EAEL_TEXTDOMAIN ),
			],
			'default' => 'default',
		] );

		$this->add_control( 'login_labels_heading', [
			'label'     => esc_html__( 'Labels', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );


		$this->add_control( 'login_user_label', [
			'label'       => __( 'Username Label', EAEL_TEXTDOMAIN ),
			'default'     => __( 'Username or Email Address', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
		] );

		$this->add_control( 'login_password_label', [
			'label'       => __( 'Password Label', EAEL_TEXTDOMAIN ),
			'default'     => __( 'Password', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
		] );

		$this->add_control( 'login_placeholders_heading', [
			'label'     => esc_html__( 'Placeholders', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'login_user_placeholder', [
			'label'       => __( 'Username Placeholder', EAEL_TEXTDOMAIN ),
			'default'     => __( 'Username or Email Address', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
		] );

		$this->add_control( 'login_password_placeholder', [
			'label'       => __( 'Password Placeholder', EAEL_TEXTDOMAIN ),
			'default'     => __( 'Password', EAEL_TEXTDOMAIN ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [ 'active' => true, ],
			'label_block' => true,
			'condition'   => [ 'login_label_types' => 'custom', ],
		] );

		$this->add_control( 'login_field_width', [
			'label'      => esc_html__( 'Input Fields width', EAEL_TEXTDOMAIN ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'%',
			],
			'range'      => [
				'px' => [
					'min'  => 0,
					'max'  => 500,
					'step' => 5,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors'  => [
				'{{WRAPPER}} .eael-login-form input' => 'width: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		] );

		$this->add_control( 'login_show_remember_me', [
			'label'     => __( 'Remember Me Checkbox', EAEL_TEXTDOMAIN ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => 'yes',
			'label_off' => __( 'Hide', EAEL_TEXTDOMAIN ),
			'label_on'  => __( 'Show', EAEL_TEXTDOMAIN ),
		] );

		$this->add_control( 'login_enable_ajax', [
			'label'   => __( 'Submit Login Form via AJAX', EAEL_TEXTDOMAIN ),
			'type'    => Controls_Manager::SWITCHER,
			'default' => 'yes',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		echo 'working on it....';
	}


}
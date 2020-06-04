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
		$this->start_controls_section( 'eael_section_content_login_fields', [
				'label' => __( 'Login Form Fields', EAEL_TEXTDOMAIN ),
			]
		);

		$this->add_control(
			'eael_login_label_types',
			[
				'label'   => __( 'Login Fields Label', EAEL_TEXTDOMAIN ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => __( 'Default', EAEL_TEXTDOMAIN ),
					'custom'  => __( 'Custom', EAEL_TEXTDOMAIN ),
					'none'    => __( 'None', EAEL_TEXTDOMAIN ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'eael_login_field_width',
			[
				'label' => esc_html__( 'Input Fields width', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-login-form input' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		echo 'working on it....';
	}


}
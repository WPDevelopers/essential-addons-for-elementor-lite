<?php

namespace Essential_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) )
{
	exit;
} // Exit if accessed directly

/**
 * Class Login_Register
 * @package Essential_Addons_Elementor\Elements
 */
class Dummy_Widget extends Widget_Base
{

	/**
	 * @inheritDoc
	 */
	public function get_name()
	{
		return 'eael-dummy-widget';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title()
	{
		return esc_html__( 'Dummy Widget', 'essential-addons-for-elementor-lite' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon()
	{
		return 'eaicon-login';
	}

	/**
	 * @inheritDoc
	 */
	public function get_keywords()
	{
		return [
			'dummy widget',
			'ea',
			'essential addons',
		];
	}

	public function get_custom_help_url()
	{
		return 'https://essential-addons.com/elementor/docs/login-register-form/';
	}

	/**
	 * @inheritDoc
	 */
	public function get_categories()
	{
		return [ 'essential-addons-elementor' ];
	}

	/**
	 * @inheritDoc
	 */
	protected function _register_controls()
	{
		$this->start_controls_section( 'test_section_content_general', [
			'label' => __( 'General', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( 'test_default_form_type_notice', [
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => __( 'Testing control', 'essential-addons-for-elementor-lite' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		] );
		$this->end_controls_section();
	}


	protected function render()
	{
		echo '<p class="dummy-content">Rending a dummy widget content</p>';
	}

}

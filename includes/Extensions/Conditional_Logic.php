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
			'eael_should_render',
			[
				'label'        => __( 'Should Render', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

		$element->end_controls_section();
	}

	public function content_render( $should_render, Element_Base $element ) {
		$settings = $element->get_settings();

		if ( isset( $settings['eael_should_render'] ) && $settings['eael_should_render'] === 'yes' ) {
			return false;
		}

		return $should_render;
	}

}

<?php

namespace Essential_Addons_Elementor\Extensions;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wrapper_Link {

	/**
	 * Initialize hooks
	 */
	public function __construct() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ] );
	}

	public function register_controls( $element ) {
		$element->start_controls_section(
			'eael_wrapper_link_section',
			[
				'label' => __( '<i class="eaicon-logo"></i> Wrapper Link', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_ADVANCED
			]
		);

		$element->add_control(
			'wrapper_link',
			[
				'label'   => __( 'Link', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$element->end_controls_section();
	}

}

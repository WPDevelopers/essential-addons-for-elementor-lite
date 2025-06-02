<?php

namespace Essential_Addons_Elementor\Extensions;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Custom_Cursor {

	/**
	 * Initialize hooks
	 */
	public function __construct() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/frontend/before_render', [ $this, 'before_render' ], 100 );
	}

	public function register_controls( $element ) {
		$element->start_controls_section(
			'eael_custom_cursor_section',
			[
				'label' => __( '<i class="eaicon-logo"></i> Custom Cursor', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_ADVANCED
			]
		);

		$element->add_control(
			'eael_custom_cursor_switch',
			[
				'label'        => __( 'Enable Custom Cursor', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes'
			]
		);

		$element->add_control(
			'eael_custom_cursor_type',
			[
				'label'     => __( 'Cursor Type', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'icon'   => [
						'title' => __( 'Pointer', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-pointer'
					],
					'crosshair' => [
						'title' => __( 'Crosshair', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-crosshair'
					]
				],
				'default'   => 'icon',
                'condition' => [
					'eael_custom_cursor_switch' => 'yes'
				]
			]
		);

		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$wrapper_link_settings = $element->get_settings_for_display( 'eael_wrapper_link' );

		if ( "yes" === $element->get_settings_for_display( 'eael_wrapper_link_switch' ) && ! empty( $wrapper_link_settings['url'] ) ) {
			$disable_traditional = $element->get_settings_for_display( 'eael_wrapper_link_disable_traditional' );
			if( 'yes' === $disable_traditional ){
				$element->add_render_attribute( '_wrapper',
					'data-eael-wrapper-link',
					wp_json_encode( [
						'url'         => esc_url( $wrapper_link_settings['url'] ),
						'is_external' => esc_attr( $wrapper_link_settings['is_external'] ),
						'nofollow'    => esc_attr( $wrapper_link_settings['nofollow'] )
					] )
				);

				$element->add_render_attribute( '_wrapper', 'class', 'eael-non-traditional-link' );
			} else {
				$link_id = 'eael-wrapper-link-' . $element->get_id();
				$element->add_render_attribute( 'eael_wrapper_link', 'class', $link_id . ' --eael-wrapper-link-tag' );
				$element->add_link_attributes( 'eael_wrapper_link', $wrapper_link_settings );
				echo "<a "; $element->print_render_attribute_string( 'eael_wrapper_link' ); echo "></a>";

				$element->add_render_attribute( '_wrapper', 'data-eael-wrapper-link', $link_id );
			}
		}
	}
}

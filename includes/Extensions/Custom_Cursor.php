<?php

namespace Essential_Addons_Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Essential_Addons_Elementor\Classes\Helper;


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
				'label'        => __( 'Enable', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes'
			]
		);

		$element->add_control(
			'eael_custom_cursor_type',
			[
				'label'     => __( 'Type', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'icon'   => [
						'title' => __( 'Icon', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-favorite'
					],
					'image' => [
						'title' => __( 'Image', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-image'
					]
				],
				'default'   => 'icon',
                'condition' => [
					'eael_custom_cursor_switch' => 'yes'
				]
			]
		);

        $element->add_control(
			'eael_custom_cursor_icon',
			[
				'label'     => '',
				'type'      => Controls_Manager::ICONS,
				'condition' => [
					'eael_custom_cursor_switch' => 'yes',
					'eael_custom_cursor_type'   => 'icon'
				]
			]
		);

        $element->add_control(
			'eael_custom_cursor_image',
			[
				'label'     => '',
				'type'      => Controls_Manager::MEDIA,
                'ai' => [
					'active' => false,
				],
				'condition' => [
					'eael_custom_cursor_switch' => 'yes',
					'eael_custom_cursor_type'   => 'image'
				]
			]
		);

        $element->add_control(
			'eael_custom_cursor_image_notice',
			[
				'type'        => Controls_Manager::NOTICE,
                'notice_type' => 'warning',
                'content'     => __( 'Cursor image must be optimized and no larger than 128x128 pixels. For best compatibility across browsers, a 32x32 pixel size is recommended.', 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'eael_custom_cursor_switch' => 'yes',
					'eael_custom_cursor_type'   => 'image'
				]
			]
		);

		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$settings = $element->get_settings_for_display();

		if ( "yes" === $settings['eael_custom_cursor_switch'] ) {
			$element->add_render_attribute( '_wrapper', 'data-eael-custom-cursor', 'yes' );
            if( 'image' === $settings['eael_custom_cursor_type'] && ! empty( $settings['eael_custom_cursor_image']['url'] ) ) {
                $element->add_render_attribute( '_wrapper', 'style', 'cursor: url("' . $settings['eael_custom_cursor_image']['url'] . '") 0 0, auto;' );
            }else if( 'icon' === $settings['eael_custom_cursor_type'] && ! empty( $settings['eael_custom_cursor_icon']['value'] ) ) {
				$svg = '';
				if( 'icon' === $settings['eael_custom_cursor_icon']['library'] ) {
					$attributes = [
						'height' => '50',
						'width'  => '50',
						'fill'  => '#c36'
					];
					$svg  = Helper::get_svg_by_icon( $settings['eael_custom_cursor_icon'], $attributes );
				} else if( 'svg' === $settings['eael_custom_cursor_icon']['library'] ) {
					$svg = Icons_Manager::try_get_icon_html( $settings['eael_custom_cursor_icon'], [ 'aria-hidden' => 'true' ] );
				}
				if( ! empty( $svg ) ) {
					$svg = base64_encode( $svg );
					$element->add_render_attribute( '_wrapper', 'style', 'cursor: url("data:image/svg+xml;base64,' . $svg . '") 0 0, auto;' );
				}
            }
			
		}
	}
}

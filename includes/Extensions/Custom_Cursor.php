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
					],
					'svg_code'   => [
						'title' => __( 'SVG Code', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-code'
					],
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
			'eael_custom_cursor_icon_size',
			[
				'label'     => __( 'Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 128
					]
				],
				'default'   => [
					'size' => 50,
					'unit' => 'px'
				],
				'condition' => [
					'eael_custom_cursor_switch' => 'yes',
					'eael_custom_cursor_type'   => 'icon'
				],
				'description' => __( 'Size do not working with SVG icons.', 'essential-addons-for-elementor-lite' )
			]
		);

		$element->add_control(
			'eael_custom_cursor_icon_color',
			[
				'label'     => __( 'Icon Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#9121fc',
				'condition' => [
					'eael_custom_cursor_switch' => 'yes',
					'eael_custom_cursor_type'   => 'icon'
				],
				'description' => __( 'Color do not working with SVG icons.', 'essential-addons-for-elementor-lite' )
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
			'eael_custom_cursor_svg_code',
			[
				'label'     => '',
				'type'      => Controls_Manager::TEXTAREA,
				'ai'        => [ 'active' => false ],
				'placeholder' => __( 'Paste your SVG code here', 'essential-addons-for-elementor-lite' ),
				'default'   => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0" id="Layer_1" width="100" height="100" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"><g><path fill="#F76D57" d="M32,52.789l-12-18C18.5,32,16,28.031,16,24c0-8.836,7.164-16,16-16s16,7.164,16,16   c0,4.031-2.055,8-4,10.789L32,52.789z"/><g><path fill="#394240" d="M32,0C18.746,0,8,10.746,8,24c0,5.219,1.711,10.008,4.555,13.93c0.051,0.094,0.059,0.199,0.117,0.289    l16,24C29.414,63.332,30.664,64,32,64s2.586-0.668,3.328-1.781l16-24c0.059-0.09,0.066-0.195,0.117-0.289    C54.289,34.008,56,29.219,56,24C56,10.746,45.254,0,32,0z M44,34.789l-12,18l-12-18C18.5,32,16,28.031,16,24    c0-8.836,7.164-16,16-16s16,7.164,16,16C48,28.031,45.945,32,44,34.789z"/><circle fill="#394240" cx="32" cy="24" r="8"/></g></g></svg>',
				'condition' => [
					'eael_custom_cursor_switch' => 'yes',
					'eael_custom_cursor_type'   => 'svg_code'
				]
			]
		);

        $element->add_control(
			'eael_custom_cursor_image_notice',
			[
				'type'        => Controls_Manager::NOTICE,
                'notice_type' => 'warning',
                'content'     => __( 'Cursor image/svg must not be larger than 128x128 pixels. For best compatibility across browsers, a 32x32 pixel size is recommended.', 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'eael_custom_cursor_switch' => 'yes',
					'eael_custom_cursor_type'   => [ 'image', 'svg_code' ]
				]
			]
		);

		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$settings = $element->get_settings_for_display();

		if ( "yes" === $settings['eael_custom_cursor_switch'] ) {
			$element->add_render_attribute( '_wrapper', 'data-eael-custom-cursor', 'yes' );
			$cursor = '';
            if( 'image' === $settings['eael_custom_cursor_type'] && ! empty( $settings['eael_custom_cursor_image']['url'] ) ) {
				$cursor = 'url("' . $settings['eael_custom_cursor_image']['url'] . '") 0 0, auto';
            } else if( 'icon' === $settings['eael_custom_cursor_type'] && ! empty( $settings['eael_custom_cursor_icon']['value'] ) ) {
				$size = !empty( $settings['eael_custom_cursor_icon_size']['size'] ) ? $settings['eael_custom_cursor_icon_size']['size'] : 50;
				$attributes = [
					'height' => $size,
					'width'  => $size,
				];
				$attributes['fill'] = !empty( $settings['eael_custom_cursor_icon_color'] ) ? $settings['eael_custom_cursor_icon_color'] : '#000';
				$svg = '';
				if( 'svg' === $settings['eael_custom_cursor_icon']['library'] ) {
					$svg = Icons_Manager::try_get_icon_html( $settings['eael_custom_cursor_icon'], [ 'aria-hidden' => 'true' ] );
				} else {
					$svg = Helper::get_svg_by_icon( $settings['eael_custom_cursor_icon'], $attributes );
				}

				if( ! empty( $svg ) ) {
					$svg = base64_encode( $svg );
					$cursor = 'url("data:image/svg+xml;base64,' . $svg . '") 0 0, auto';
				}
            } else if( 'svg_code' === $settings['eael_custom_cursor_type'] && ! empty( $settings['eael_custom_cursor_svg_code'] ) ) {
				$svg = base64_encode( $settings['eael_custom_cursor_svg_code'] );
				$cursor = 'url("data:image/svg+xml;base64,' . $svg . '") 0 0, auto';
			}

			if( ! empty( $cursor ) ) {
				$element->add_render_attribute( '_wrapper', 'style', 'cursor: ' . $cursor . ';' );
			}
			
		}
	}
}

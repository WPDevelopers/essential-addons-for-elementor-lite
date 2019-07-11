<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Background as Group_Control_Background;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Scheme_Color as Scheme_Color;
use \Elementor\Scheme_Typography as Scheme_Typography;
use \Elementor\Utils as Utils;
use \Elementor\Widget_Base as Widget_Base;

class Feature_List extends Widget_Base
{
	public function get_name() {
		return 'eael-feature-list';
	}

	public function get_title() {
		return esc_html__( 'EA Feature List', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-bullet-list';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {
		/**
		 * Feature List Settings
		 */
		$this->start_controls_section(
			'eael_section_feature_list_content_settings',
			[
				'label' => esc_html__( 'Content Settings', 'essential-addons-elementor' )
			]
		);

		$this->add_control(
			'eael_feature_list',
			[
				'label'       => esc_html__( 'Feature Item', 'essential-addons-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'seperator'   => 'before',
				'default'     => [
					[
						'eael_feature_list_icon'    => 'fa fa-check',
						'eael_feature_list_title'   => esc_html__( 'Feature Item 1', 'essential-addons-elementor' ),
						'eael_feature_list_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna', 'essential-addons-elementor' )
					],
					[
						'eael_feature_list_icon'    => 'fa fa-times',
						'eael_feature_list_title'   => esc_html__( 'Feature Item 2', 'essential-addons-elementor' ),
						'eael_feature_list_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna', 'essential-addons-elementor' )
					],
					[
						'eael_feature_list_icon'    => 'fa fa-dot-circle-o',
						'eael_feature_list_title'   => esc_html__( 'Feature Item 3', 'essential-addons-elementor' ),
						'eael_feature_list_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna', 'essential-addons-elementor' )
					]
				],
				'fields'      => [
					[
						'name'        => 'eael_feature_list_icon_type',
						'label'       => esc_html__( 'Icon Type', 'essential-addons-elementor' ),
						'type'        => Controls_Manager::CHOOSE,
						'options'     => [
							'icon'  => [
								'title' => esc_html__( 'Icon', 'essential-addons-elementor' ),
								'icon'  => 'fa fa-star',
							],
							'image' => [
								'title' => esc_html__( 'Image', 'essential-addons-elementor' ),
								'icon'  => 'fa fa-picture-o',
							],
						],
						'default'     => 'icon',
						'label_block' => false,
					],
					[
						'name'    => 'eael_feature_list_icon',
						'label'   => esc_html__( 'Icon', 'essential-addons-elementor' ),
						'type'    => Controls_Manager::ICON,
						'default' => 'fa fa-plus',
						'condition' => [
							'eael_feature_list_icon_type' => 'icon'
						]
					],
					[
						'name'      => 'eael_feature_list_img',
						'label'     => esc_html__( 'Image', 'essential-addons-elementor' ),
						'type'      => Controls_Manager::MEDIA,
						'default'   => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'condition' => [
							'eael_feature_list_icon_type' => 'image'
						]
					],
					[
						'name'    => 'eael_feature_list_title',
						'label'   => esc_html__( 'Title', 'essential-addons-elementor' ),
						'type'    => Controls_Manager::TEXT,
						'default' => esc_html__( 'Title', 'essential-addons-elementor' ),
						'dynamic' => [ 'active' => true ]
					],
					[
						'name'    => 'eael_feature_list_content',
						'label'   => esc_html__( 'Content', 'essential-addons-elementor' ),
						'type'    => Controls_Manager::TEXTAREA,
						'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'essential-addons-elementor' ),
						'dynamic' => [ 'active' => true ]
					],
					[
						'name'        => 'eael_feature_list_link',
						'label'       => esc_html__( 'Link', 'essential-addons-elementor' ),
						'type'        => Controls_Manager::URL,
						'dynamic'     => [ 'active' => true ],
						'placeholder' => esc_html__( 'https://your-link.com', 'essential-addons-elementor' ),
						'separator'   => 'before',
					],
				],
				'title_field' => '<i class="{{ eael_feature_list_icon }}" aria-hidden="true"></i> {{{ eael_feature_list_title }}}',
			]
		);

		$this->add_control(
			'eael_feature_list_title_size',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default'   => 'h3',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_feature_list_icon_shape',
			[
				'label'       => esc_html__( 'Icon Shape', 'essential-addons-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'circle',
				'label_block' => false,
				'options'     => [
					'circle'  => esc_html__( 'Circle', 'essential-addons-elementor' ),
					'square'  => esc_html__( 'Square', 'essential-addons-elementor' ),
					'rhombus' => esc_html__( 'Rhombus', 'essential-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'eael_feature_list_icon_shape_view',
			[
				'label'       => esc_html__( 'Shape View', 'essential-addons-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'stacked',
				'label_block' => false,
				'options'     => [
					'framed'  => esc_html__( 'Framed', 'essential-addons-elementor' ),
					'stacked' => esc_html__( 'Stacked', 'essential-addons-elementor' )
				],
			]
		);

		$this->add_responsive_control(
			'eael_feature_list_icon_position',
			[
				'label'           => esc_html__( 'Icon Position', 'essential-addons-elementor' ),
				'type'            => Controls_Manager::CHOOSE,
				'options'         => [
					'left'  => [
						'title' => esc_html__( 'Left', 'essential-addons-elementor' ),
						'icon'  => 'fa fa-align-left',
					],
					'top'   => [
						'title' => esc_html__( 'Top', 'essential-addons-elementor' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-elementor' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'         => 'left',
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => 'left',
				'tablet_default'  => 'left',
				'mobile_default'  => 'left',
				'prefix_class'    => '%s-icon-position-',
				'toggle'          => false,
			]
		);

		$this->add_control(
			'eael_feature_list_connector',
			[
				'label'        => esc_html__( 'Show Connector', 'essential-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Show', 'essential-addons-elementor' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Feature List Style
		 * -------------------------------------------
		 */

		$this->start_controls_section(
			'eael_section_feature_list_style',
			[
				'label' => esc_html__( 'List', 'essential-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_feature_list_space_between',
			[
				'label'     => esc_html__( 'Space Between', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 15,
				],
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-feature-list-items .eael-feature-list-item:not(:last-child)'                              => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .eael-feature-list-items .eael-feature-list-item:not(:first-child)'                             => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
					'{{WRAPPER}} .eael-feature-list-items.connector-type-modern .eael-feature-list-item:not(:last-child):before' => 'height: calc(100% + {{SIZE}}{{UNIT}})',
					'body.rtl {{WRAPPER}} .eael-feature-list-items .eael-feature-list-item:after'                                => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
				],
			]
		);

		$this->add_control(
			'eael_feature_list_connector_type',
			[
				'label'       => esc_html__( 'Connector Type', 'essential-addons-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'connector-type-classic',
				'label_block' => false,
				'options'     => [
					'connector-type-classic' => esc_html__( 'Classic', 'essential-addons-elementor' ),
					'connector-type-modern'  => esc_html__( 'Modern', 'essential-addons-elementor' ),
				],
				'condition'   => [
					'eael_feature_list_connector'      => 'yes',
					'eael_feature_list_icon_position!' => 'top',
				],
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'eael_feature_list_connector_styles',
			[
				'label'       => esc_html__( 'Connector Styles', 'essential-addons-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'solid',
				'label_block' => false,
				'options'     => [
					'solid'  => esc_html__( 'Solid', 'essential-addons-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'essential-addons-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'essential-addons-elementor' ),
				],
				'condition'   => [
					'eael_feature_list_connector' => 'yes',
				],
				'selectors'   => [
					'{{WRAPPER}} .connector-type-classic .connector'                                                                                      => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .connector-type-modern .eael-feature-list-item:before, {{WRAPPER}} .connector-type-modern .eael-feature-list-item:after' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_feature_list_connector_color',
			[
				'label'     => esc_html__( 'Connector Color', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'default'   => '#37368e',
				'selectors' => [
					'{{WRAPPER}} .connector-type-classic .connector'                                                                                      => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .connector-type-modern .eael-feature-list-item:before, {{WRAPPER}} .connector-type-modern .eael-feature-list-item:after' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'eael_feature_list_connector' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_feature_list_connector_width',
			[
				'label'     => esc_html__( 'Connector Width', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 1,
				],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .connector-type-classic .connector'                                                                                                                                => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.-icon-position-left .connector-type-modern .eael-feature-list-item:before, {{WRAPPER}}.-icon-position-left .connector-type-modern .eael-feature-list-item:after'   => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.-icon-position-right .connector-type-modern .eael-feature-list-item:before, {{WRAPPER}}.-icon-position-right .connector-type-modern .eael-feature-list-item:after' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'eael_feature_list_connector' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Feature List Icon Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_feature_list_style_icon',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background:: get_type(),
			[
				'name'    => 'eael_feature_list_icon_background',
				'types'   => [ 'classic', 'gradient' ],
				'exclude' => [
                    'image',
                ],
				'color' => [
					'default' => '#3858f4',
				],
				'selector' => '{{WRAPPER}} .eael-feature-list-items .eael-feature-list-icon-box .eael-feature-list-icon-inner',
			]
		);

		$this->add_control(
			'eael_feature_list_secondary_color',
			[
				'label'     => esc_html__( 'Secondary Color', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .eael-feature-list-items.framed .eael-feature-list-icon'  => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'eael_feature_list_icon_shape_view' => 'framed',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_feature_list_icon_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .eael-feature-list-items .eael-feature-list-icon' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'eael_feature_list_icon_size',
			[
				'label'     => esc_html__( 'Size', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 30,
				],
				'range'     => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-feature-list-img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_feature_list_icon_padding',
			[
				'label'     => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 15,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_feature_list_icon_border_width',
			[
				'label'     => esc_html__( 'Border Width', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 1,
				],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon-inner' => 'padding: {{SIZE}}{{UNIT}};',

				],
				'condition' => [
					'eael_feature_list_icon_shape_view' => 'framed',
				],
			]
		);

		$this->add_control(
			'eael_feature_list_icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon-inner .eael-feature-list-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'eael_feature_list_icon_shape_view' => 'framed',
				],
			]
		);

		$this->add_responsive_control(
			'eael_feature_list_icon_space',
			[
				'label'           => esc_html__( 'Spacing', 'essential-addons-elementor' ),
				'type'            => Controls_Manager::SLIDER,
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 30,
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => 20,
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}}.-icon-position-left .eael-feature-list-content-box, {{WRAPPER}}.-icon-position-right .eael-feature-list-content-box, {{WRAPPER}}.-icon-position-top .eael-feature-list-content-box' => 'margin: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.-mobile-icon-position-left .eael-feature-list-content-box'                                                                                                                  => 'margin: 0 0 0 {{SIZE}}{{UNIT}} !important;',
					'(mobile){{WRAPPER}}.-mobile-icon-position-right .eael-feature-list-content-box'                                                                                                                 => 'margin: 0 {{SIZE}}{{UNIT}} 0 0 !important;',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Feature List Content Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_feature_list_style_content',
			[
				'label' => esc_html__( 'Content', 'essential-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_feature_list_text_align',
			[
				'label' => __( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'condition' => [
					'eael_feature_list_icon_position' => 'top',
				],
				'selectors' => [
					'{{WRAPPER}} .eael-feature-list-item' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_feature_list_heading_title',
			[
				'label' => esc_html__( 'Title', 'essential-addons-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'eael_feature_list_title_bottom_space',
			[
				'label'     => esc_html__( 'Spacing', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 10,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-feature-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_feature_list_title_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#414247',
				'selectors' => [
					'{{WRAPPER}} .eael-feature-list-content-box .eael-feature-list-title' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_feature_list_title_typography',
				'selector' => '{{WRAPPER}} .eael-feature-list-content-box .eael-feature-list-title',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'eael_feature_list_description',
			[
				'label'     => esc_html__( 'Description', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_feature_list_description_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .eael-feature-list-content-box .eael-feature-list-content' => 'color: {{VALUE}};',
				],
				'scheme'    => [
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_feature_list_description_typography',
				'selector' => '{{WRAPPER}} .eael-feature-list-content-box .eael-feature-list-content',
				'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'fields_options' => [
	                'font_size' => [ 'default' => [ 'unit' => 'px', 'size' => 14 ] ]
                ]
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'eael_feature_list', [
			'id'    => 'eael-feature-list-' . esc_attr( $this->get_id() ),
			'class' => [
				'eael-feature-list-items',
				$settings['eael_feature_list_icon_shape'],
				$settings['eael_feature_list_icon_shape_view'],
				$settings['eael_feature_list_connector_type'],
			]
		] );

		if ( ( $settings['eael_feature_list_icon_position'] == 'top' ) && ( $settings['eael_feature_list_connector'] == 'yes' ) ) {
			$this->add_render_attribute( 'eael_feature_list', 'class', 'connector-type-modern' );
		}

		$this->add_render_attribute( 'eael_feature_list_item', 'class', 'eael-feature-list-item' );

		$padding = $settings['eael_feature_list_icon_padding']['size'];
		$font    = $settings['eael_feature_list_icon_size']['size'];
		$border  = $settings['eael_feature_list_icon_border_width']['right'] + $settings['eael_feature_list_icon_border_width']['left'];


		if ( $settings['eael_feature_list_icon_shape'] == 'rhombus' ) {
		    $margin = 30;
			$connector_width = ( $padding * 2 ) + $font + $border + $margin;
		} else {
			$connector_width = ( $padding * 2 ) + $font + $border;
		}


		if ( $settings['eael_feature_list_icon_position'] == 'left' ) {

			$connector = 'right: calc(100% - ' . $connector_width . 'px) !important; left: 0;';

		} else {
			$connector = 'left: calc(100% - ' . $connector_width . 'px) !important; right: 0;';
		}


		?>

        <ul <?php echo $this->get_render_attribute_string( 'eael_feature_list' ); ?>>
			<?php $i = 0;
			foreach ( $settings['eael_feature_list'] as $index => $item ) :

				$list_icon_setting_key = $this->get_repeater_setting_key( 'eael_feature_list_icon', 'eael_feature_list', $index );
				$list_title_setting_key = $this->get_repeater_setting_key( 'eael_feature_list_title', 'eael_feature_list', $index );
				$list_content_setting_key = $this->get_repeater_setting_key( 'eael_feature_list_content', 'eael_feature_list', $index );
				$list_link_setting_key = $this->get_repeater_setting_key( 'eael_feature_list_link', 'eael_feature_list', $index );

				$this->add_render_attribute( $list_icon_setting_key, 'class', 'eael-feature-list-icon' );
				$this->add_render_attribute( $list_title_setting_key, 'class', 'eael-feature-list-title' );

				$feat_title_tag = $settings['eael_feature_list_title_size'];
				if( ! empty( $item['eael_feature_list_link']['url'] ) ) {
					$this->add_render_attribute( $list_title_setting_key, 'href', $item['eael_feature_list_link']['url'] );

					if ( $item['eael_feature_list_link']['is_external'] ) {
						$this->add_render_attribute( $list_title_setting_key, 'target', '_blank' );
					}

					if ( $item['eael_feature_list_link']['nofollow'] ) {
						$this->add_render_attribute( $list_title_setting_key, 'rel', 'nofollow' );
					}
					$feat_title_tag = 'a';
				}
				$this->add_render_attribute( $list_content_setting_key, 'class', 'eael-feature-list-content' );

				$feature_icon_attributes = $this->get_render_attribute_string( $list_icon_setting_key );

				$feature_icon_tag = 'span';
				$feature_has_icon = ! empty( $item['eael_feature_list_icon'] );

				if ( ! empty( $item['eael_feature_list_link']['url'] ) ) {
					$this->add_render_attribute( $list_link_setting_key, 'href', $item['eael_feature_list_link']['url'] );

					if ( $item['eael_feature_list_link']['is_external'] ) {
						$this->add_render_attribute( $list_link_setting_key, 'target', '_blank' );
					}

					if ( $item['eael_feature_list_link']['nofollow'] ) {
						$this->add_render_attribute( $list_link_setting_key, 'rel', 'nofollow' );
					}

					$feature_icon_tag = 'a';
				}

				$feature_link_attributes = $this->get_render_attribute_string( $list_link_setting_key );

				?>
                <li class="eael-feature-list-item">
					<?php if ( 'yes' == $settings['eael_feature_list_connector'] ) : ?>
                        <span class="connector" style="<?php echo $connector; ?>"></span>
					<?php endif; ?>

					<?php if ( $feature_has_icon ) : ?>

                        <div class="eael-feature-list-icon-box">
                            <div class="eael-feature-list-icon-inner">
                        <<?php echo implode( ' ', [
							$feature_icon_tag,
							$feature_icon_attributes,
							$feature_link_attributes
						] ); ?>>

                        <?php if ($item['eael_feature_list_icon_type'] == 'icon') { ?>
                            <i class="<?php echo esc_attr( $item['eael_feature_list_icon'] ); ?>" aria-hidden="true"></i>
                        <?php } ?>

                        <?php if ($item['eael_feature_list_icon_type'] == 'image') {
							$this->add_render_attribute('feature_list_image'.$i, [
								'src'	=> esc_url( $item['eael_feature_list_img']['url'] ),
								'class'	=> 'eael-feature-list-img',
								'alt'	=> esc_attr(get_post_meta($item['eael_feature_list_img']['id'], '_wp_attachment_image_alt', true))
							]);

                            ?>
                            <img <?php echo $this->get_render_attribute_string('feature_list_image'.$i); ?>>
                        <?php } ?>

                        </<?php echo $feature_icon_tag; ?>>
                            </div>
                        </div>

					<?php endif; ?>

                    <div class="eael-feature-list-content-box">
                        <<?php echo implode( ' ', [
							$feat_title_tag,
							$this->get_render_attribute_string( $list_title_setting_key )
						] ); ?>
                        ><?php echo $item['eael_feature_list_title']; ?></<?php echo $feat_title_tag; ?>
                    >
                    <p <?php echo $this->get_render_attribute_string( $list_content_setting_key ); ?>><?php echo $item['eael_feature_list_content']; ?></p>
                    </div>

                </li>
			<?php $i++; endforeach; ?>
        </ul>
		<?php
	}

	protected function _content_template() {
	}
}

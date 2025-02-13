<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

class SVG_Draw extends Widget_Base {
	public function get_name() {
		return 'eael-svg-draw';
	}

	public function get_title() {
		return esc_html__( 'SVG Draw', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-svg-draw';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords() {
		return [
			'svg',
			'draq',
			'ea svg',
			'ea svg draw',
			'animation',
			'icon',
			'icon animation',
			'svg animation',
			'ea',
			'essential addons',
		];
	}

	protected function is_dynamic_content():bool {
        return false;
    }

	public function has_widget_inner_wrapper(): bool {
        return ! Helper::eael_e_optimized_markup();
    }

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/ea-svg-draw/';
	}

	protected function default_custom_svg() {
		return '<?xml version="1.0"?>
            <svg xmlns="http://www.w3.org/2000/svg" id="Icons" viewBox="0 0 74 74" width="512" height="512">
                <path d="M65,72H9a3,3,0,0,1-3-3V30a1,1,0,0,1,2,0V69a1,1,0,0,0,1,1H65a1,1,0,0,0,1-1V30a1,1,0,0,1,2,0V69A3,3,0,0,1,65,72Z"/>
                <path d="M70,31H52.31a1,1,0,0,1,0-2H70V19H4V29H21.69a1,1,0,1,1,0,2H4a2,2,0,0,1-2-2V19a2,2,0,0,1,2-2H70a2,2,0,0,1,2,2V29A2,2,0,0,1,70,31Z"/>
                <path d="M37,19a1,1,0,0,1-.86-1.509c2.193-3.712,7.618-12.538,10.615-14.326a8.2,8.2,0,1,1,8.4,14.078c-1.222.73-3.7,1.319-7.369,1.75a1,1,0,1,1-.233-1.986c4.439-.522,6.025-1.151,6.576-1.48A6.179,6.179,0,0,0,56.971,11.7a6.194,6.194,0,0,0-9.191-6.818c-2.126,1.269-6.517,7.871-9.918,13.626A1,1,0,0,1,37,19Z"/>
                <path d="M37,19a1,1,0,0,1-.862-.491c-3.4-5.756-7.792-12.358-9.917-13.626a6.2,6.2,0,1,0-6.347,10.644c.55.329,2.136.958,6.576,1.48a1,1,0,1,1-.233,1.986c-3.667-.431-6.147-1.02-7.369-1.75a8.2,8.2,0,1,1,8.4-14.078c3,1.788,8.42,10.614,10.614,14.326A1,1,0,0,1,37,19Z"/>
                <path d="M42,72H32a1,1,0,0,1-1-1V29.12a1,1,0,0,1,2,0V70h8V29.12a1,1,0,0,1,2,0V71A1,1,0,0,1,42,72Z"/>
                <path d="M41.94,30H32.06a1,1,0,1,1,0-2h9.88a1,1,0,0,1,0,2Z"/>
                <path d="M46.692,40.563a1,1,0,0,1-.912-.59L36.088,18.41a1,1,0,0,1,1.824-.82l8.613,19.162,1.29-4.114a1,1,0,0,1,1.365-.613l3.77,1.7-7.163-15.3a1,1,0,1,1,1.812-.848L55.906,35.32a1,1,0,0,1-1.316,1.335l-5.2-2.344-1.74,5.55a1,1,0,0,1-.895.7Z"/>
                <path d="M27.308,40.563l-.06,0a1,1,0,0,1-.895-.7l-1.74-5.55-5.2,2.344a1,1,0,0,1-1.316-1.335L26.4,17.576a1,1,0,1,1,1.812.848l-7.163,15.3,3.77-1.7a1,1,0,0,1,1.365.613l1.29,4.114L36.088,17.59a1,1,0,0,1,1.824.82L28.22,39.973A1,1,0,0,1,27.308,40.563Z"/>
            </svg>
    ';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'eael_section_svg_content_settings',
			[
				'label' => esc_html__( 'General', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'eael_svg_src',
			[
				'label'   => esc_html__( 'Source', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon'   => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
					'custom' => esc_html__( 'Custom SVG', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'eael_svg_icon',
			[
				'label'     => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'default'   => [
					'value'   => [
						'url' => EAEL_PLUGIN_URL . 'assets/admin/images/svg-draw.svg',
					],
					'library' => 'svg',
				],
				'condition' => [
					'eael_svg_src' => 'icon'
				]
			]
		);

		$this->add_control(
			'svg_html',
			[
				'label'       => esc_html__( 'SVG Code', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXTAREA,
				'condition'   => [
					'eael_svg_src' => 'custom'
				],
				'default'     => $this->default_custom_svg(),
				'description' => esc_html__( 'SVG draw works best on path elements.', 'essential-addons-for-elementor-lite' ),
			]
		);


		$this->add_control(
			'eael_svg_exclude_style',
			[
				'label'       => esc_html__( 'Exclude Style', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'   => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'default'     => 'no',
				'description' => esc_html__( 'Exclude style from SVG Source (If any).', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->add_responsive_control(
			'eael_svg_width',
			[
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 500,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 200,
				],
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_svg_height',
			[
				'label'      => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 200,
				],
				'selectors'  => [
					'{{WRAPPER}} svg' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_svg_alignment',
			[
				'label'     => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .eael-svg-draw-container' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_svg_link',
			[
				'label'       => esc_html__( 'Link', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'essential-addons-for-elementor-lite' ),
				'options'     => [ 'url' ],
				'label_block' => true,
				'separator'   => 'before'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'eael_section_svg_appearance',
			[
				'label' => esc_html__( 'Appearance', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'eael_svg_fill',
			[
				'label'   => esc_html__( 'SVG Fill Type', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'   => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
					'after'  => esc_html__( 'Fill After Draw', 'essential-addons-for-elementor-lite' ),
					'before' => esc_html__( 'Fill Before Draw', 'essential-addons-for-elementor-lite' ),
				],
			]
		);


		$this->add_control(
			'eael_svg_fill_transition',
			[
				'label'       => esc_html__( 'Fill Transition', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'min'         => 0,
				'selectors'   => [
					'{{WRAPPER}} .fill-svg svg path' => 'animation-duration: {{SIZE}}s;',
				],
				'description' => esc_html__( 'Duration on SVG fills (in seconds)', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->add_control(
			'eael_svg_animation_on',
			[
				'label'     => esc_html__( 'Animation', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'page-load',
				'options'   => [
					'none'        => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
					'page-load'   => esc_html__( 'On Page Load', 'essential-addons-for-elementor-lite' ),
					'page-scroll' => esc_html__( 'On Page Scroll', 'essential-addons-for-elementor-lite' ),
					'hover'       => esc_html__( 'Mouse Hover', 'essential-addons-for-elementor-lite' ),
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_svg_draw_offset',
			[
				'label'       => esc_html__( 'Drawing Start Point', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 1000,
				'step'        => 1,
				'default'     => 50,
				'condition'   => [
					'eael_svg_animation_on' => [ 'page-scroll' ],
				],
				'description' => esc_html__( 'The point at which the drawing begins to animate as scrolls down (in pixels).', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->add_control(
			'eael_svg_pause_on_hover',
			[
				'label'       => esc_html__( 'Pause on Hover Off', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'   => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'default'     => 'yes',
				'condition'   => [
					'eael_svg_animation_on' => 'hover',
				],
				'description' => esc_html__( 'Pause SVG drawing on mouse leave', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->add_control(
			'eael_svg_loop',
			[
				'label'        => esc_html__( 'Repeat Drawing', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_svg_animation_on!' => [ 'page-scroll', 'none' ],
				]
			]
		);

		$this->add_control(
			'eael_svg_animation_direction',
			[
				'label'     => esc_html__( 'Direction', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'reverse',
				'options'   => [
					'reverse' => esc_html__( 'Reverse', 'essential-addons-for-elementor-lite' ),
					'restart' => esc_html__( 'Restart', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_svg_animation_on!' => [ 'page-scroll', 'none' ],
					'eael_svg_loop'          => 'yes'
				]
			]
		);

		$this->add_control(
			'eael_svg_draw_speed',
			[
				'label'       => esc_html__( 'Speed', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 300,
				'step'        => 1,
				'default'     => 20,
				'condition'   => [
					'eael_svg_animation_on!' => [ 'page-scroll' ],
				],
				'description' => esc_html__( 'Duration on SVG draws (in ms)', 'essential-addons-for-elementor-lite' )
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'eael_section_svg_style_settings',
			[
				'label' => esc_html__( 'Style', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'eael_svg_path_thickness',
			[
				'label'      => esc_html__( 'Path Thickness', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => .1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 1.2,
				],
				'selectors'  => [
					'{{WRAPPER}} svg path'    => 'stroke-width: {{SIZE}};',
					'{{WRAPPER}} svg circle'  => 'stroke-width: {{SIZE}};',
					'{{WRAPPER}} svg rect'    => 'stroke-width: {{SIZE}};',
					'{{WRAPPER}} svg polygon' => 'stroke-width: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'eael_svg_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'selectors' => [
					'{{WRAPPER}} svg path'    => 'stroke:{{VALUE}};',
					'{{WRAPPER}} svg circle'  => 'stroke:{{VALUE}};',
					'{{WRAPPER}} svg rect'    => 'stroke:{{VALUE}};',
					'{{WRAPPER}} svg polygon' => 'stroke:{{VALUE}};',
				],
				'default'   => '#974CF3'
			]
		);

		$this->add_control(
			'eael_svg_fill_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Fill Color', 'essential-addons-for-elementor-lite' ),
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-eael-svg-draw .fill-svg svg path'                            => 'fill:{{VALUE}};',
					'{{WRAPPER}} .elementor-widget-eael-svg-draw .eael-svg-draw-container.fill-svg svg path'    => 'fill:{{VALUE}};',
					'{{WRAPPER}} .elementor-widget-eael-svg-draw .eael-svg-draw-container.fill-svg svg circle'  => 'fill:{{VALUE}};',
					'{{WRAPPER}} .elementor-widget-eael-svg-draw .eael-svg-draw-container.fill-svg svg rect'    => 'fill:{{VALUE}};',
					'{{WRAPPER}} .elementor-widget-eael-svg-draw .eael-svg-draw-container.fill-svg svg polygon' => 'fill:{{VALUE}};'
				],
				'default'   => '#D8C2F3',
				'condition' => [
					'eael_svg_fill!' => 'none'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'eael_svg_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-svg-draw-container svg',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'eael_svg_border',
				'selector'  => '{{WRAPPER}} .eael-svg-draw-container svg',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_svg_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-svg-draw-container svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_svg_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-svg-draw-container svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_svg_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-svg-draw-container svg' => 'Margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .eael-svg-draw-container svg',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$svg_html = isset( $settings['svg_html'] ) ? preg_replace( ['#<script(.*?)>(.*?)</script>#is', '#<script(.*?)>(.*?)</script#is'], '', $settings['svg_html'] ) : '';
		$this->add_render_attribute( 'eael-svg-drow-wrapper', [
			'class' => [
				'eael-svg-draw-container',
				esc_attr( $settings['eael_svg_animation_on'] ),
				$settings['eael_svg_fill'] === 'before' ? 'fill-svg' : ''
			],
		] );

		$svg_options = [
			'fill'         => $settings['eael_svg_fill'] === 'after' ? 'fill-svg' : '',
			'speed'        => esc_attr( $settings['eael_svg_draw_speed'] ),
			'offset'       => esc_attr( $settings['eael_svg_draw_offset'] ),
			'loop'         => $settings['eael_svg_loop'] ? esc_attr( $settings['eael_svg_loop'] ) : 'no',
			'pause'        => $settings['eael_svg_pause_on_hover'] ? esc_attr( $settings['eael_svg_pause_on_hover'] ) : 'no',
			'direction'    => esc_attr( $settings['eael_svg_animation_direction'] ),
			'excludeStyle' => esc_attr( $settings['eael_svg_exclude_style'] )
		];

		$this->add_render_attribute( 'eael-svg-drow-wrapper', [
			'data-settings' => wp_json_encode( $svg_options )
		] );

		if ( ! empty( $settings['eael_svg_link']['url'] ) ) {
			$this->add_link_attributes( 'eael_svg_link', $settings['eael_svg_link'] );
			echo '<a '; $this->print_render_attribute_string( 'eael_svg_link' ); echo '>';
		}

		echo '<div '; $this->print_render_attribute_string( 'eael-svg-drow-wrapper' ); echo '>';

		if ( $settings['eael_svg_src'] === 'icon' ):

			if ( $settings['eael_svg_icon']['library'] === 'svg' ) {
				if ( empty( $settings['eael_svg_icon']['value']['id'] ) ) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $this->default_custom_svg();
				}

				Icons_Manager::render_icon( $settings['eael_svg_icon'], [ 'aria-hidden' => 'true', 'class' => [ 'eael-svg-drow-wrapper' ] ] );
			} else {
				echo wp_kses( Helper::get_svg_by_icon( $settings['eael_svg_icon'] ), Helper::eael_allowed_icon_tags() );
			}

		else:
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf( '%s', $svg_html );
		endif;

		echo ' </div>';

		if ( ! empty( $settings['eael_svg_link']['url'] ) ) {
			echo "</a>";
		}

	}
}
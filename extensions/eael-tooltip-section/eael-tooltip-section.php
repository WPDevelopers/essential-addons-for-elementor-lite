<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Elementor_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Element_Base;
use Elementor\Widget_Base;

class EAEL_Tooltip_Section extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ], 10 );

		add_action( 'elementor/widget/before_render_content', array( $this, 'before_render' ) );
		add_action( 'elementor/widget/before_render_content',array( $this,'after_render') );

	}

	public function get_name() {
		return 'eael-tooltip-section';
	}

	public function register_controls( $element ) {

		$element->start_controls_section(
			'eael_tooltip_section',
			[
				'label' => __( 'EA Tooltip', 'essential-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_ADVANCED
			]
		);

		$element->add_control(
			'eael_tooltip_section_enable',
			[
				'label' => __( 'Enable Tooltip', 'essential-addons-elementor' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$element->start_controls_tabs( 'eael_tooltip_tabs' );

		$element->start_controls_tab( 'eael_tooltip_settings', [
			'label'     => __( 'Settings', 'essential-addons-elementor' ),
			'condition' => [
				'eael_tooltip_section_enable!' => '',
			],
		] );

		$element->add_control(
			'eael_tooltip_section_content',
			[
				'label'              => __( 'Content', 'essential-addons-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => __( 'I am a tooltip', 'essential-addons-elementor' ),
				'dynamic'            => [ 'active' => true ],
				'frontend_available' => true,
				'condition'          => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_position',
			[
				'label'              => __( 'Position', 'essential-addons-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'top',
				'options'            => [
					'top'    => __( 'Top', 'essential-addons-elementor' ),
					'bottom' => __( 'Bottom', 'essential-addons-elementor' ),
					'left'   => __( 'Left', 'essential-addons-elementor' ),
					'right'  => __( 'Right', 'essential-addons-elementor' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_animation',
			[
				'label'              => __( 'Animation', 'essential-addons-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'scale',
				'options'            => [
					'shift-away'   => __( 'Shift Away', 'essential-addons-elementor' ),
					'shift-toward' => __( 'Shift Toward', 'essential-addons-elementor' ),
					'fade'         => __( 'Fade', 'essential-addons-elementor' ),
					'scale'        => __( 'Scale', 'essential-addons-elementor' ),
					'perspective'  => __( 'Perspective', 'essential-addons-elementor' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_trigger',
			[
				'label'              => __( 'Trigger', 'essential-addons-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'mouseenter',
				'options'            => [
					'mouseenter' => __( 'Mouseenter', 'essential-addons-elementor' ),
					'click'      => __( 'Click', 'essential-addons-elementor' ),
					'focus'      => __( 'Focus', 'essential-addons-elementor' ),
					'manual'     => __( 'Manual', 'essential-addons-elementor' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_size',
			[
				'label'              => __( 'Size', 'essential-addons-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'regular',
				'options'            => [
					'small'   => __( 'Small', 'essential-addons-elementor' ),
					'regular' => __( 'Regular', 'essential-addons-elementor' ),
					'large'   => __( 'Large', 'essential-addons-elementor' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_arrow',
			[
				'label'              => __( 'Arrow', 'essential-addons-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Show', 'essential-addons-elementor' ),
				'label_off'          => __( 'Hide', 'essential-addons-elementor' ),
				'return_value'       => true,
				'default'            => true,
				'frontend_available' => true,
				'condition'          => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_duration',
			[
				'label'              => __( 'Duration', 'essential-addons-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 100,
				'max'                => 1000,
				'step'               => 10,
				'default'            => 100,
				'frontend_available' => true,
				'condition'          => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_delay',
			[
				'label'              => __( 'Delay out (s)', 'essential-addons-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 100,
				'max'                => 1000,
				'step'               => 5,
				'default'            => 100,
				'frontend_available' => true,
				'condition'          => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_disable',
			[
				'label'              => __( 'Disable On', 'essential-addons-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '',
				'options'            => [
					''       => __( 'None', 'essential-addons-elementor' ),
					'tablet' => __( 'Tablet & Mobile', 'essential-addons-elementor' ),
					'mobile' => __( 'Mobile', 'essential-addons-elementor' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->end_controls_tab();

		$element->start_controls_tab( 'eael_tooltip_section_styles', [
			'label'     => __( 'Styles', 'essential-addons-elementor' ),
			'condition' => [
				'eael_tooltip_section_enable!' => '',
			],
		] );

		$element->add_control(
			'eael_tooltip_section_width',
			[
				'label'       => __( 'Max Width', 'essential-addons-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [
					'size' => '',
				],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'label_block' => false,
				'selectors'   => [
					'.ee-tooltip.ee-tooltip-{{ID}}' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_distance',
			[
				'label'       => __( 'Distance', 'essential-addons-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 05,
				'max'         => 50,
				'step'        => 2,
				'default'     => 10,
				'label_block' => false,
				'condition'   => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_theme',
			[
				'label'              => __( 'Theme', 'essential-addons-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'dark',
				'options'            => [
					'dark'         => __( 'Dark', 'essential-addons-elementor' ),
					'light'        => __( 'Light', 'essential-addons-elementor' ),
					'light-border' => __( 'Light Border', 'essential-addons-elementor' ),
					'google'       => __( 'Google', 'essential-addons-elementor' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_align',
			[
				'label'     => __( 'Text Align', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'.ee-tooltip.ee-tooltip-{{ID}}' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_padding',
			[
				'label'      => __( 'Padding', 'essential-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'.ee-tooltip.ee-tooltip-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_border_radius',
			[
				'label'      => __( 'Border Radius', 'essential-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'.ee-tooltip.ee-tooltip-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'eael_tooltip_section_typography',
				'selector'  => '.ee-tooltip.ee-tooltip-{{ID}}',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
				'separator' => 'after',
				'condition' => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_background_color',
			[
				'label'     => __( 'Background Color', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
//				'selectors' => [
//					'#eael-section-tooltip-{{ID}}.tippy-tooltip' => 'background-color: {{VALUE}};',
//				],
				'condition' => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_control(
			'eael_tooltip_section_color',
			[
				'label'     => __( 'Color', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.ee-tooltip.ee-tooltip-{{ID}}' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_tooltip_section_enable!' => '',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'eael_tooltip_section_box_shadow',
				'selector'  => '.ee-tooltip.ee-tooltip-{{ID}}',
				'separator' => '',
			]
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();

	}

	public function before_render( $element ) {

		$settings = $element->get_settings_for_display();

//		echo '<pre>', print_r($settings), '</pre>';

		if ( $element->get_settings( 'eael_tooltip_section_enable' ) == 'yes' ) {

			$element->add_render_attribute( '_wrapper', [
				'id'                   => 'eael-section-tooltip-' . $element->get_id(),
				'class'                => 'eael-section-tooltip',
				'data-tippy'           => $settings['eael_tooltip_section_content'],
				'data-tippy-animation' => $settings['eael_tooltip_section_animation'],
				'data-tippy-duration'  => $settings['eael_tooltip_section_duration'],
				'data-tippy-arrow'     => $settings['eael_tooltip_section_arrow'],
				'data-tippy-delay'     => $settings['eael_tooltip_section_delay'],
				'data-tippy-size'      => $settings['eael_tooltip_section_size'],
				'data-tippy-placement' => $settings['eael_tooltip_section_position'],
				'data-tippy-trigger'   => $settings['eael_tooltip_section_trigger'],

				'data-tippy-distance' => $settings['eael_tooltip_section_distance'],
				'data-tippy-theme'    => $settings['eael_tooltip_section_theme'],
			] );

			$bg_color = $settings['eael_tooltip_section_background_color'];

			if ( $settings['eael_tooltip_section_theme'] == 'light' ) { ?>
                <style>
                    .tippy-popper[x-placement^=top] .tippy-tooltip.light-theme .tippy-arrow {
                        border-top: 8px solid <?php echo $bg_color; ?>;
                        border-right: 8px solid transparent;
                        border-left: 8px solid transparent
                    }

                    .tippy-popper[x-placement^=bottom] .tippy-tooltip.light-theme .tippy-arrow {
                        border-bottom: 8px solid <?php echo $bg_color; ?>;
                        border-right: 8px solid transparent;
                        border-left: 8px solid transparent
                    }

                    .tippy-popper[x-placement^=left] .tippy-tooltip.light-theme .tippy-arrow {
                        border-left: 8px solid <?php echo $bg_color; ?>;
                        border-top: 8px solid transparent;
                        border-bottom: 8px solid transparent
                    }

                    .tippy-popper[x-placement^=right] .tippy-tooltip.light-theme .tippy-arrow {
                        border-right: 8px solid #fff;
                        border-top: 8px solid transparent;
                        border-bottom: 8px solid transparent;
                    }

                    .tippy-tooltip.light-theme {
                        color: #26323d;
                        box-shadow: 0 0 20px 4px rgba(154, 161, 177, .15), 0 4px 80px -8px rgba(36, 40, 47, .25), 0 4px 4px -2px rgba(91, 94, 105, .15);
                        background-color: <?php echo $bg_color; ?>;
                    }

                    .tippy-tooltip.light-theme .tippy-backdrop {
                        background-color: <?php echo $bg_color; ?>;
                    }

                    .tippy-tooltip.light-theme .tippy-roundarrow {
                        fill: <?php echo $bg_color; ?>;
                    }

                    .tippy-tooltip.light-theme[data-animatefill] {
                        background-color: transparent;
                    }
                </style>
			<?php }

			if ( $settings['eael_tooltip_section_theme'] == 'google' ) { ?>
                <style>
                    .tippy-popper[x-placement^=top] .tippy-tooltip.google-theme .tippy-arrow {
                        border-top: 8px solid #505355;
                        border-right: 8px solid transparent;
                        border-left: 8px solid transparent
                    }

                    .tippy-popper[x-placement^=bottom] .tippy-tooltip.google-theme .tippy-arrow {
                        border-bottom: 8px solid #505355;
                        border-right: 8px solid transparent;
                        border-left: 8px solid transparent
                    }

                    .tippy-popper[x-placement^=left] .tippy-tooltip.google-theme .tippy-arrow {
                        border-left: 8px solid #505355;
                        border-top: 8px solid transparent;
                        border-bottom: 8px solid transparent
                    }

                    .tippy-popper[x-placement^=right] .tippy-tooltip.google-theme .tippy-arrow {
                        border-right: 8px solid #505355;
                        border-top: 8px solid transparent;
                        border-bottom: 8px solid transparent
                    }

                    .tippy-tooltip.google-theme {
                        background-color: #505355;
                        padding: .25rem .4rem;
                        font-size: .85rem;
                        font-weight: 600
                    }

                    .tippy-tooltip.google-theme .tippy-backdrop {
                        background-color: #505355
                    }

                    .tippy-tooltip.google-theme .tippy-roundarrow {
                        fill: #505355
                    }

                    .tippy-tooltip.google-theme[data-animatefill] {
                        background-color: transparent
                    }

                    .tippy-tooltip.google-theme[data-size=small] {
                        font-size: .75rem;
                        padding: .2rem .4rem
                    }

                    .tippy-tooltip.google-theme[data-size=large] {
                        font-size: 1rem;
                        padding: .4rem .8rem
                    }
                </style>
			<?php }

			if ( $settings['eael_tooltip_section_theme'] == 'light-border' ) { ?>
                <style>
                    .tippy-tooltip.light-border-theme {
                        background-color: #fff;
                        background-clip: padding-box;
                        border: 1px solid rgba(0, 8, 16, .15);
                        color: #26323d;
                        box-shadow: 0 3px 14px -.5px rgba(0, 8, 16, .08)
                    }

                    .tippy-tooltip.light-border-theme .tippy-backdrop {
                        background-color: #fff
                    }

                    .tippy-tooltip.light-border-theme .tippy-arrow:after, .tippy-tooltip.light-border-theme .tippy-arrow:before, .tippy-tooltip.light-border-theme .tippy-roundarrow:after, .tippy-tooltip.light-border-theme .tippy-roundarrow:before {
                        content: "";
                        position: absolute;
                        z-index: -1
                    }

                    .tippy-tooltip.light-border-theme .tippy-roundarrow {
                        fill: #fff
                    }

                    .tippy-tooltip.light-border-theme .tippy-roundarrow:after {
                        background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj48c3ZnIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIHZpZXdCb3g9IjAgMCAyNCA4IiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zOnNlcmlmPSJodHRwOi8vd3d3LnNlcmlmLmNvbS8iIHN0eWxlPSJmaWxsLXJ1bGU6ZXZlbm9kZDtjbGlwLXJ1bGU6ZXZlbm9kZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MS40MTQyMTsiPjxwYXRoIGQ9Ik0zLDhjMCwwIDIuMDIxLC0wLjAxNSA1LjI1MywtNC4yMThjMS4zMzEsLTEuNzMxIDIuNTQ0LC0yLjc3NSAzLjc0NywtMi43ODJjMS4yMDMsLTAuMDA3IDIuNDE2LDEuMDM1IDMuNzYxLDIuNzgyYzMuMjUxLDQuMjIzIDUuMjM5LDQuMjE4IDUuMjM5LDQuMjE4bC0xOCwwWiIgc3R5bGU9ImZpbGw6IzAwMDgxNjtmaWxsLW9wYWNpdHk6MC4yMDM5MjI7ZmlsbC1ydWxlOm5vbnplcm87Ii8+PC9zdmc+);
                        background-size: 24px 8px;
                        width: 24px;
                        height: 8px;
                        left: 0;
                        top: 0;
                        fill: rgba(0, 8, 16, .15)
                    }

                    .tippy-popper[x-placement^=top] .tippy-tooltip.light-border-theme .tippy-roundarrow:after {
                        top: 1px;
                        -webkit-transform: rotate(180deg);
                        transform: rotate(180deg)
                    }

                    .tippy-popper[x-placement^=top] .tippy-tooltip.light-border-theme .tippy-arrow {
                        border-top-color: #fff
                    }

                    .tippy-popper[x-placement^=top] .tippy-tooltip.light-border-theme .tippy-arrow:after {
                        border-top: 7px solid #fff;
                        top: -7px
                    }

                    .tippy-popper[x-placement^=top] .tippy-tooltip.light-border-theme .tippy-arrow:before {
                        border-top: 7px solid rgba(0, 8, 16, .2);
                        bottom: -1px
                    }

                    .tippy-popper[x-placement^=bottom] .tippy-tooltip.light-border-theme .tippy-roundarrow:after {
                        top: -1px
                    }

                    .tippy-popper[x-placement^=bottom] .tippy-tooltip.light-border-theme .tippy-arrow {
                        border-bottom-color: #fff
                    }

                    .tippy-popper[x-placement^=bottom] .tippy-tooltip.light-border-theme .tippy-arrow:after {
                        border-bottom: 7px solid #fff;
                        bottom: -7px
                    }

                    .tippy-popper[x-placement^=bottom] .tippy-tooltip.light-border-theme .tippy-arrow:before {
                        border-bottom: 7px solid rgba(0, 8, 16, .2);
                        bottom: -6px
                    }

                    .tippy-popper[x-placement^=left] .tippy-tooltip.light-border-theme .tippy-roundarrow:after {
                        left: 1px;
                        top: 0;
                        -webkit-transform: rotate(90deg);
                        transform: rotate(90deg)
                    }

                    .tippy-popper[x-placement^=left] .tippy-tooltip.light-border-theme .tippy-arrow {
                        border-left-color: #fff
                    }

                    .tippy-popper[x-placement^=left] .tippy-tooltip.light-border-theme .tippy-arrow:after {
                        border-left: 7px solid #fff;
                        left: -7px
                    }

                    .tippy-popper[x-placement^=left] .tippy-tooltip.light-border-theme .tippy-arrow:before {
                        border-left: 7px solid rgba(0, 8, 16, .2);
                        left: -6px
                    }

                    .tippy-popper[x-placement^=right] .tippy-tooltip.light-border-theme .tippy-roundarrow:after {
                        left: -1px;
                        top: 0;
                        -webkit-transform: rotate(-90deg);
                        transform: rotate(-90deg)
                    }

                    .tippy-popper[x-placement^=right] .tippy-tooltip.light-border-theme .tippy-arrow {
                        border-right-color: #fff
                    }

                    .tippy-popper[x-placement^=right] .tippy-tooltip.light-border-theme .tippy-arrow:after {
                        border-right: 7px solid #fff;
                        right: -7px
                    }

                    .tippy-popper[x-placement^=right] .tippy-tooltip.light-border-theme .tippy-arrow:before {
                        border-right: 7px solid rgba(0, 8, 16, .2);
                        right: -6px
                    }

                    .tippy-popper[x-placement^=bottom] .tippy-tooltip.light-border-theme .tippy-arrow, .tippy-popper[x-placement^=bottom] .tippy-tooltip.light-border-theme .tippy-roundarrow, .tippy-popper[x-placement^=top] .tippy-tooltip.light-border-theme .tippy-arrow, .tippy-popper[x-placement^=top] .tippy-tooltip.light-border-theme .tippy-roundarrow {
                        -webkit-transform: translateX(-1px);
                        transform: translateX(-1px)
                    }

                    .tippy-popper[x-placement^=bottom] .tippy-tooltip.light-border-theme .tippy-arrow:after, .tippy-popper[x-placement^=bottom] .tippy-tooltip.light-border-theme .tippy-arrow:before, .tippy-popper[x-placement^=top] .tippy-tooltip.light-border-theme .tippy-arrow:after, .tippy-popper[x-placement^=top] .tippy-tooltip.light-border-theme .tippy-arrow:before {
                        left: -7px;
                        border-left: 7px solid transparent;
                        border-right: 7px solid transparent
                    }

                    .tippy-popper[x-placement^=left] .tippy-tooltip.light-border-theme .tippy-arrow, .tippy-popper[x-placement^=left] .tippy-tooltip.light-border-theme .tippy-roundarrow, .tippy-popper[x-placement^=right] .tippy-tooltip.light-border-theme .tippy-arrow, .tippy-popper[x-placement^=right] .tippy-tooltip.light-border-theme .tippy-roundarrow {
                        -webkit-transform: translateY(-1px);
                        transform: translateY(-1px)
                    }

                    .tippy-popper[x-placement^=left] .tippy-tooltip.light-border-theme .tippy-arrow:after, .tippy-popper[x-placement^=left] .tippy-tooltip.light-border-theme .tippy-arrow:before, .tippy-popper[x-placement^=right] .tippy-tooltip.light-border-theme .tippy-arrow:after, .tippy-popper[x-placement^=right] .tippy-tooltip.light-border-theme .tippy-arrow:before {
                        top: -7px;
                        border-top: 7px solid transparent;
                        border-bottom: 7px solid transparent
                    }
                </style>

			<?php }

		}

	}

	public function after_render( $element ) {

		$data     = $element->get_data();
		$settings = $element->get_settings_for_display();
//		$data = $settings['eael_tooltip_section_content'];

//		echo '<pre>', var_dump(), '</pre>';

		?>

<!--        <script>-->
<!---->
<!--                    // tippy('#eael-section-tooltip-14476e7', tooltipOptions);-->
<!---->
<!--                    tippy('.eael-section-tooltip', {-->
<!--                        content: 'hello',-->
<!--                        animation: 'scale',-->
<!--                        duration: 0,-->
<!--                        arrow: true,-->
<!--                        delay: [1000, 200],-->
<!--                    });-->
<!--        </script>-->

	<?php }
}

EAEL_Tooltip_Section::instance();
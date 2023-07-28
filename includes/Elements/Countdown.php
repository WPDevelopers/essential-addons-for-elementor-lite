<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Plugin;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Widget_Base;

use \Essential_Addons_Elementor\Classes\Helper;

class Countdown extends Widget_Base {
    

    public function get_name() {
        return 'eael-countdown';
    }

    public function get_title() {
        return esc_html__( 'Countdown', 'essential-addons-for-elementor-lite' );
    }

    public function get_icon() {
        return 'eaicon-countdown';
    }

    public function get_categories() {
        return ['essential-addons-elementor'];
    }

    public function get_keywords() {
        return [
            'countdown',
            'ea countdown',
            'count down',
            'ea count down',
            'timer',
            'ea timer',
            'chronometer',
            'stopwatch',
            'clock',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url() {
        return 'https://essential-addons.com/elementor/docs/creative-elements/ea-countdown/';
    }

    protected function register_controls() {

        $this->start_controls_section(
            'eael_section_countdown_settings_general',
            [
                'label' => esc_html__( 'Timer Settings', 'essential-addons-for-elementor-lite' ),
            ]
        );

	    $this->add_control(
		    'eael_countdown_type',
		    [
			    'label' => esc_html__( 'Type', 'essential-addons-for-elementor-lite' ),
			    'type' => Controls_Manager::SELECT,
			    'options' => [
				    'due_date' => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
				    'evergreen' => esc_html__( 'Evergreen Timer', 'essential-addons-for-elementor-lite' ),
			    ],
			    'default' => 'due_date',
		    ]
	    );

	    $this->add_control(
		    'eael_evergreen_counter_hours',
		    [
			    'label' => esc_html__( 'Hours', 'essential-addons-for-elementor-lite' ),
			    'type' => Controls_Manager::NUMBER,
			    'default' => 11,
			    'placeholder' => esc_html__( 'Hours', 'essential-addons-for-elementor-lite' ),
			    'condition' => [
				    'eael_countdown_type' => 'evergreen',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_evergreen_counter_minutes',
		    [
			    'label' => esc_html__( 'Minutes', 'essential-addons-for-elementor-lite' ),
			    'type' => Controls_Manager::NUMBER,
			    'default' => 59,
			    'placeholder' => esc_html__( 'Minutes', 'essential-addons-for-elementor-lite' ),
			    'condition' => [
				    'eael_countdown_type' => 'evergreen',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_evergreen_counter_recurring',
		    [
			    'label'        => esc_html__( 'Recurring Countdown', 'essential-addons-for-elementor-lite' ),
			    'type'         => Controls_Manager::SWITCHER,
			    'return_value' => 'yes',
			    'default'      => '',
			    'condition'    => [
				    'eael_countdown_type' => 'evergreen',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_evergreen_counter_recurring_restart_after',
		    [
			    'label'       => esc_html__( 'Restart After (In Hours)', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::NUMBER,
			    'default'     => 0,
			    'description' => esc_html__( 'Specify how much time it will take to restart the countdown. If you set 0, the countdown will restart immediately.', 'essential-addons-for-elementor-lite' ),
			    'placeholder' => esc_html__( 'Hours', 'essential-addons-for-elementor-lite' ),
			    'condition'   => [
				    'eael_countdown_type'              => 'evergreen',
				    'eael_evergreen_counter_recurring' => 'yes',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_evergreen_counter_recurring_stop_time',
		    [
			    'label'       => esc_html__( 'Recurring Countdown End Date', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::DATE_TIME,
			    'default'     => date( "Y-m-d", strtotime( "+ 7 day" ) ),
			    'description' => esc_html__( 'Set the countdown end time', 'essential-addons-for-elementor-lite' ),
			    'condition'   => [
				    'eael_countdown_type'              => 'evergreen',
				    'eael_evergreen_counter_recurring' => 'yes',
			    ],
		    ]
	    );

        $this->add_control(
            'eael_countdown_due_time',
            [
                'label'       => esc_html__( 'Countdown Due Date', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::DATE_TIME,
                'default'     => date( "Y-m-d", strtotime( "+ 1 day" ) ),
                'description' => esc_html__( 'Set the due date and time', 'essential-addons-for-elementor-lite' ),
                'condition' => [
	                'eael_countdown_type' => 'due_date',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_countdown_label_view',
            [
                'label'   => esc_html__( 'Label Position', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'eael-countdown-label-block',
                'options' => [
                    'eael-countdown-label-block'  => esc_html__( 'Block', 'essential-addons-for-elementor-lite' ),
                    'eael-countdown-label-inline' => esc_html__( 'Inline', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_countdown_label_padding_left',
            [
                'label'       => esc_html__( 'Left spacing for Labels', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::SLIDER,
                'description' => esc_html__( 'Use when you select inline labels', 'essential-addons-for-elementor-lite' ),
                'range'       => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'   => [
                    '{{WRAPPER}} .eael-countdown-label' => 'padding-left:{{SIZE}}px;',
                ],
                'condition'   => [
                    'eael_countdown_label_view' => 'eael-countdown-label-inline',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_countdown_alignment',
            [
                'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-item > div' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_countdown_settings_content',
            [
                'label' => esc_html__( 'Content Settings', 'essential-addons-for-elementor-lite' ),
            ]
        );
        $this->add_control(
            'eael_section_countdown_layout',
            [
                'label'     => __( 'Layout', 'essential-addons-for-elementor-lite' ),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                    'grid'       => [
                        'title' => __( 'List view', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'fa fa-th-list',
                    ],
                    'table-cell' => [
                        'title' => __( 'Grid View', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'fa fa-th-large',
                    ],
                ],
                'default'   => 'table-cell',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-items>li' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_days',
            [
                'label'        => esc_html__( 'Display Days', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'eael_countdown_days_label',
            [
                'label'       => esc_html__( 'Custom Label for Days', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default'     => esc_html__( 'Days', 'essential-addons-for-elementor-lite' ),
                'description' => esc_html__( 'Leave blank to hide', 'essential-addons-for-elementor-lite' ),
                'condition'   => [
                    'eael_countdown_days' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_countdown_hours',
            [
                'label'        => esc_html__( 'Display Hours', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'eael_countdown_hours_label',
            [
                'label'       => esc_html__( 'Custom Label for Hours', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default'     => esc_html__( 'Hours', 'essential-addons-for-elementor-lite' ),
                'description' => esc_html__( 'Leave blank to hide', 'essential-addons-for-elementor-lite' ),
                'condition'   => [
                    'eael_countdown_hours' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_countdown_minutes',
            [
                'label'        => esc_html__( 'Display Minutes', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'eael_countdown_minutes_label',
            [
                'label'       => esc_html__( 'Custom Label for Minutes', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default'     => esc_html__( 'Minutes', 'essential-addons-for-elementor-lite' ),
                'description' => esc_html__( 'Leave blank to hide', 'essential-addons-for-elementor-lite' ),
                'condition'   => [
                    'eael_countdown_minutes' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_countdown_seconds',
            [
                'label'        => esc_html__( 'Display Seconds', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'eael_countdown_seconds_label',
            [
                'label'       => esc_html__( 'Custom Label for Seconds', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default'     => esc_html__( 'Seconds', 'essential-addons-for-elementor-lite' ),
                'description' => esc_html__( 'Leave blank to hide', 'essential-addons-for-elementor-lite' ),
                'condition'   => [
                    'eael_countdown_seconds' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_countdown_separator_heading',
            [
                'label' => __( 'Countdown Separator', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_countdown_separator',
            [
                'label'        => esc_html__( 'Display Separator', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'eael-countdown-show-separator',
                'default'      => '',
            ]
        );

        $this->add_control(
            'eael_countdown_separator_style',
            [
                'label'     => __( 'Separator Style', 'essential-addons-for-elementor-lite' ),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 'dotted',
                'options'   => [
                    'solid'  => __( 'Solid', 'essential-addons-for-elementor-lite' ),
                    'dotted' => __( 'Dotted', 'essential-addons-for-elementor-lite' ),
                ],
                'condition' => [
                    'eael_countdown_separator' => 'eael-countdown-show-separator',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_separator_position_top',
            [
                'label'      => __( 'Position Top', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 5,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-countdown-digits::after' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_separator_position_left',
            [
                'label'      => __( 'Position Left', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 5,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 98,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-countdown-digits::after' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_separator_color',
            [
                'label'     => esc_html__( 'Separator Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'condition' => [
                    'eael_countdown_separator' => 'eael-countdown-show-separator',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-digits::after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_countdown_separator_typography',
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_SECONDARY
                ],
                'selector'  => '{{WRAPPER}} .eael-countdown-digits::after',
                'condition' => [
                    'eael_countdown_separator' => 'eael-countdown-show-separator',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'countdown_on_expire_settings',
            [
                'label' => esc_html__( 'Expire Action', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'countdown_expire_type',
            [
                'label'       => esc_html__( 'Expire Type', 'essential-addons-for-elementor-lite' ),
                'label_block' => false,
                'type'        => Controls_Manager::SELECT,
                'description' => esc_html__( 'Choose whether if you want to set a message or a redirect link', 'essential-addons-for-elementor-lite' ),
                'options'     => [
                    'none'     => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
                    'text'     => esc_html__( 'Message', 'essential-addons-for-elementor-lite' ),
                    'url'      => esc_html__( 'Redirection Link', 'essential-addons-for-elementor-lite' ),
                    'template' => esc_html__( 'Saved Templates', 'essential-addons-for-elementor-lite' ),
                ],
                'default'     => 'none',
            ]
        );

        $this->add_control(
            'countdown_expiry_text_title',
            [
                'label'     => esc_html__( 'On Expiry Title', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::TEXTAREA,
                'dynamic' => ['active' => true],
                'default'   => esc_html__( 'Countdown is finished!', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'countdown_expire_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            'countdown_expiry_text',
            [
                'label'     => esc_html__( 'On Expiry Content', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::WYSIWYG,
                'default'   => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'countdown_expire_type' => 'text',
                ],
            ]
        );

        $this->add_control(
            'countdown_expiry_redirection',
            [
                'label'     => esc_html__( 'Redirect To (URL)', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'condition' => [
                    'countdown_expire_type' => 'url',
                ],
                'default'   => '#',
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'countdown_expiry_templates',
            [
                'label'     => __( 'Choose Template', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => Helper::get_elementor_templates(),
                'condition' => [
                    'countdown_expire_type' => 'template',
                ],
            ]
        );

        $this->end_controls_section();

        if ( !apply_filters( 'eael/pro_enabled', false ) ) {
            $this->start_controls_section(
                'eael_section_pro',
                [
                    'label' => __( 'Go Premium for More Features', 'essential-addons-for-elementor-lite' ),
                ]
            );

            $this->add_control(
                'eael_control_get_pro',
                [
                    'label'       => __( 'Unlock more possibilities', 'essential-addons-for-elementor-lite' ),
                    'type'        => Controls_Manager::CHOOSE,
                    'options'     => [
                        '1' => [
                            'title' => '',
                            'icon'  => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default'     => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
                ]
            );

            $this->end_controls_section();
        }

        $this->start_controls_section(
            'eael_section_countdown_styles_general',
            [
                'label' => esc_html__( 'Countdown Styles', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_countdown_is_gradient',
            [
                'label'        => __( 'Use Gradient Background?', 'essential-addons-for-elementor-lite' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
                'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'      => 'eael_countdown_background',
                'label'     => __( 'Box Background Color', 'essential-addons-for-elementor-lite' ),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .eael-countdown-item > div',
                'condition' => [
                    'eael_countdown_is_gradient' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_background',
            [
                'label'     => esc_html__( 'Box Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-item > div' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_countdown_is_gradient' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_countdown_item_bottom_margin',
            [
                'label'     => esc_html__( 'Space Between Boxes', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 15,
                ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-items>li' => 'margin-bottom:{{SIZE}}px;',
                ],
                'condition' => [
                    'eael_section_countdown_layout' => 'grid',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_countdown_spacing',
            [
                'label'     => esc_html__( 'Space Between Boxes', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 15,
                ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-item > div' => 'margin-right:{{SIZE}}px; margin-left:{{SIZE}}px;',
                    '{{WRAPPER}} .eael-countdown-container'  => 'margin-right: -{{SIZE}}px; margin-left: -{{SIZE}}px;',
                ],
                'condition' => [
                    'eael_section_countdown_layout' => 'table-cell',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_countdown_container_margin_bottom',
            [
                'label'     => esc_html__( 'Space Below Container', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 0,
                ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-container' => 'margin-bottom:{{SIZE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_countdown_box_padding',
            [
                'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-countdown-item > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_countdown_box_border',
                'label'    => esc_html__( 'Border', 'essential-addons-for-elementor-lite' ),
                'selector' => '{{WRAPPER}} .eael-countdown-item > div',
            ]
        );

        $this->add_control(
            'eael_countdown_box_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-item > div' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_countdown_box_shadow',
                'selector' => '{{WRAPPER}} .eael-countdown-item > div',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_countdown_styles_content',
            [
                'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_countdown_digits_heading',
            [
                'label' => __( 'Countdown Digits', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_countdown_digits_color',
            [
                'label'     => esc_html__( 'Digits Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fec503',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-digits' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_countdown_digit_typography',
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_SECONDARY
                ],
                'selector' => '{{WRAPPER}} .eael-countdown-digits',
            ]
        );

        $this->add_control(
            'eael_countdown_label_heading',
            [
                'label' => __( 'Countdown Labels', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_countdown_label_color',
            [
                'label'     => esc_html__( 'Label Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_countdown_label_typography',
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_SECONDARY
                ],
                'selector' => '{{WRAPPER}} .eael-countdown-label',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_countdown_styles_individual',
            [
                'label' => esc_html__( 'Individual Box Styling', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_countdown_days_label_heading',
            [
                'label' => __( 'Days', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'      => 'eael_countdown_days_background_color',
                'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .eael-countdown-item > div.eael-countdown-days',
                'condition' => [
                    'eael_countdown_is_gradient' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_days_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-item > div.eael-countdown-days' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_countdown_is_gradient' => '',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_days_digit_color',
            [
                'label'     => esc_html__( 'Digit Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-days .eael-countdown-digits' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_days_label_color',
            [
                'label'     => esc_html__( 'Label Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-days .eael-countdown-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_days_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-item > div.eael-countdown-days' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_hours_label_heading',
            [
                'label' => __( 'Hours', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'      => 'eael_countdown_hours_background_color',
                'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .eael-countdown-item > div.eael-countdown-hours',
                'condition' => [
                    'eael_countdown_is_gradient' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_hours_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-item > div.eael-countdown-hours' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_countdown_is_gradient' => '',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_hours_digit_color',
            [
                'label'     => esc_html__( 'Digit Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-hours .eael-countdown-digits' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_hours_label_color',
            [
                'label'     => esc_html__( 'Label Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-hours .eael-countdown-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_hours_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-item > div.eael-countdown-hours' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_minutes_label_heading',
            [
                'label' => __( 'Minutes', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'      => 'eael_countdown_minutes_background_color',
                'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .eael-countdown-item > div.eael-countdown-minutes',
                'condition' => [
                    'eael_countdown_is_gradient' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_minutes_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-item > div.eael-countdown-minutes' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_countdown_is_gradient' => '',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_minutes_digit_color',
            [
                'label'     => esc_html__( 'Digit Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-minutes .eael-countdown-digits' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_minutes_label_color',
            [
                'label'     => esc_html__( 'Label Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-minutes .eael-countdown-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_minutes_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-item > div.eael-countdown-minutes' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_seconds_label_heading',
            [
                'label' => __( 'Seconds', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'      => 'eael_countdown_seconds_background_color',
                'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .eael-countdown-item > div.eael-countdown-seconds',
                'condition' => [
                    'eael_countdown_is_gradient' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_seconds_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-item > div.eael-countdown-seconds' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_countdown_is_gradient' => '',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_seconds_digit_color',
            [
                'label'     => esc_html__( 'Digit Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-seconds .eael-countdown-digits' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_seconds_label_color',
            [
                'label'     => esc_html__( 'Label Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-seconds .eael-countdown-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_countdown_seconds_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-item > div.eael-countdown-seconds' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_section_countdown_expire_style',
            [
                'label'     => esc_html__( 'Expire Message', 'essential-addons-for-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'countdown_expire_type' => 'text',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_countdown_expire_message_alignment',
            [
                'label'       => esc_html__( 'Text Alignment', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options'     => [
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
                'default'     => 'left',
                'selectors'   => [
                    '{{WRAPPER}} .eael-countdown-finish-message' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_eael_countdown_expire_title',
            [
                'label'     => __( 'Title Style', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_countdown_expire_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-finish-message .expiry-title' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'countdown_expire_type' => 'text',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_countdown_expire_title_typography',
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_SECONDARY
                ],
                'selector'  => '{{WRAPPER}} .eael-countdown-finish-message .expiry-title',
                'condition' => [
                    'countdown_expire_type' => 'text',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_expire_title_margin',
            [
                'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-countdown-finish-message .expiry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_eael_countdown_expire_message',
            [
                'label'     => __( 'Content Style', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_countdown_expire_message_color',
            [
                'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-countdown-finish-text' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'countdown_expire_type' => 'text',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_countdown_expire_message_typography',
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_SECONDARY
                ],
                'selector'  => '.eael-countdown-finish-text',
                'condition' => [
                    'countdown_expire_type' => 'text',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_countdown_expire_message_padding',
            [
                'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .eael-countdown-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'countdown_expire_type' => 'text',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $get_due_date = esc_attr( $settings['eael_countdown_due_time'] );
        $due_date = date( "M d Y G:i:s", strtotime( $get_due_date ) );
	    $gmt_offset = str_replace( array( '.25', '.5', '.75' ), array( ':15', ':30', ':45' ), ( get_option( 'gmt_offset' ) < 0 ? '' : '+' ) . get_option( 'gmt_offset' ) );

        $this->add_render_attribute( 'eael-countdown', 'class', 'eael-countdown-wrapper' );
        $this->add_render_attribute( 'eael-countdown', 'data-countdown-id', esc_attr( $this->get_id() ) );
        $this->add_render_attribute( 'eael-countdown', 'data-expire-type', $settings['countdown_expire_type'] );
        $this->add_render_attribute( 'eael-countdown', 'data-countdown-type', $settings['eael_countdown_type'] );

	    if ( $settings['eael_countdown_type'] === 'evergreen' ) {
		    $hour   = absint( $settings['eael_evergreen_counter_hours'] ) * HOUR_IN_SECONDS;
		    $minute = absint( $settings['eael_evergreen_counter_minutes'] ) * MINUTE_IN_SECONDS;
		    $this->add_render_attribute( 'eael-countdown', 'data-evergreen-time', absint( $hour + $minute ) );

		    if ( $settings['eael_evergreen_counter_recurring'] === 'yes' ) {
			    $this->add_render_attribute( 'eael-countdown', 'data-evergreen-recurring', $settings['eael_evergreen_counter_recurring_restart_after'] ? $settings['eael_evergreen_counter_recurring_restart_after'] : 0 );
			    $this->add_render_attribute( 'eael-countdown', 'data-evergreen-recurring-stop', date( "M d Y G:i:s", strtotime( $settings['eael_evergreen_counter_recurring_stop_time'] ) ) . " {$gmt_offset}" );
		    }
	    }

        if ( $settings['countdown_expire_type'] == 'text' ) {
            if ( !empty( $settings['countdown_expiry_text'] ) ) {
                $this->add_render_attribute( 'eael-countdown', 'data-expiry-text', wp_kses_post( $settings['countdown_expiry_text'] ) );
            }

            if ( !empty( $settings['countdown_expiry_text_title'] ) ) {
                $this->add_render_attribute( 'eael-countdown', 'data-expiry-title', wp_kses_post( $settings['countdown_expiry_text_title'] ) );
            }
        } elseif ( $settings['countdown_expire_type'] == 'url' ) {
            $this->add_render_attribute( 'eael-countdown', 'data-redirect-url', $settings['countdown_expiry_redirection'] );
        } elseif ( $settings['countdown_expire_type'] == 'template' ) {
            //$this->add_render_attribute( 'eael-countdown', 'data-template', esc_attr( $template ) );
        } else {
            //do nothing
        }
        // separator
        $separator = '';
        if ( $settings['eael_countdown_separator'] === 'eael-countdown-show-separator' ) {
            $separator = 'eael-countdown-show-separator eael-countdown-separator-' . $settings['eael_countdown_separator_style'];
        }

        // label view
	    $this->add_render_attribute( 'eael-countdown-container', [
		    'class' => [
			    'eael-countdown-container',
			    $settings['eael_countdown_label_view'],
			    empty( $settings['eael_countdown_label_view_tablet'] ) ? '' : $settings['eael_countdown_label_view_tablet'] . '-tablet',
			    empty( $settings['eael_countdown_label_view_mobile'] ) ? '' : $settings['eael_countdown_label_view_mobile'] . '-mobile',
			    $separator,
		    ],
	    ] );
        ?>

		<div <?php echo $this->get_render_attribute_string( 'eael-countdown' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'eael-countdown-container' ); ?>>
				<ul id="eael-countdown-<?php echo esc_attr( $this->get_id() ); ?>" class="eael-countdown-items" data-date="<?php echo esc_attr( "{$due_date} {$gmt_offset}" ); ?>">
					<?php if ( !empty( $settings['eael_countdown_days'] ) ): ?><li class="eael-countdown-item"><div class="eael-countdown-days"><span data-days class="eael-countdown-digits">00</span><?php if ( !empty( $settings['eael_countdown_days_label'] ) ): ?><span class="eael-countdown-label"><?php echo esc_attr( $settings['eael_countdown_days_label'] ); ?></span><?php endif;?></div></li><?php endif;?>
					<?php if ( !empty( $settings['eael_countdown_hours'] ) ): ?><li class="eael-countdown-item"><div class="eael-countdown-hours"><span data-hours class="eael-countdown-digits">00</span><?php if ( !empty( $settings['eael_countdown_hours_label'] ) ): ?><span class="eael-countdown-label"><?php echo esc_attr( $settings['eael_countdown_hours_label'] ); ?></span><?php endif;?></div></li><?php endif;?>
				<?php if ( !empty( $settings['eael_countdown_minutes'] ) ): ?><li class="eael-countdown-item"><div class="eael-countdown-minutes"><span data-minutes class="eael-countdown-digits">00</span><?php if ( !empty( $settings['eael_countdown_minutes_label'] ) ): ?><span class="eael-countdown-label"><?php echo esc_attr( $settings['eael_countdown_minutes_label'] ); ?></span><?php endif;?></div></li><?php endif;?>
				<?php if ( !empty( $settings['eael_countdown_seconds'] ) ): ?><li class="eael-countdown-item"><div class="eael-countdown-seconds"><span data-seconds class="eael-countdown-digits">00</span><?php if ( !empty( $settings['eael_countdown_seconds_label'] ) ): ?><span class="eael-countdown-label"><?php echo esc_attr( $settings['eael_countdown_seconds_label'] ); ?></span><?php endif;?></div></li><?php endif;?>
				</ul>
                <div class="eael-countdown-expiry-template" style="display: none;">
					<?php
					if ( 'template' == $settings['countdown_expire_type'] ) {
						if ( ! empty( $settings['countdown_expiry_templates'] ) ) {
							// WPML Compatibility
							if ( ! is_array( $settings['countdown_expiry_templates'] ) ) {
								$settings['countdown_expiry_templates'] = apply_filters( 'wpml_object_id', $settings['countdown_expiry_templates'], 'wp_template', true );
							}
							echo Plugin::$instance->frontend->get_builder_content( $settings['countdown_expiry_templates'], true );
						}
					}
					?>
                </div>
				<div class="clearfix"></div>
			</div>
		</div>

	<?php

    }
}

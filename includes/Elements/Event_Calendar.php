<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Repeater;
use \Elementor\Widget_Base;

class Event_Calendar extends Widget_Base {
    use \Essential_Addons_Elementor\Traits\Helper;

    public function get_name () {
        return 'eael-event-calendar';
    }

    public function get_style_depends () {
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
        ];
    }

    public function get_title () {
        return esc_html__('Event Calendar', 'essential-addons-for-elementor-lite');
    }

    public function get_icon () {
        return 'eaicon-event-calendar';
    }

    public function get_categories () {
        return ['essential-addons-elementor'];
    }
    
    public function get_keywords() {
        return [
            'event',
            'events',
            'calendar',
            'ea calendar',
            'ea event calendar',
            'eventon',
            'google calendar',
            'event marketing',
            'scheduled events',
            'event calendar',
            'modern events',
            'ea',
            'essential addons'
        ];
    }

    public function get_custom_help_url() {
        return 'https://essential-addons.com/elementor/docs/event-calendar/';
    }

    protected function _register_controls () {
        /**
         * -------------------------------------------
         * Events
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_event_section',
            [
                'label' => __('Events', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eael_event_calendar_type',
            [
                'label'   => __('Source', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'options' => apply_filters('eael/event-calendar/source', [
                    'manual' => __('Manual', 'essential-addons-for-elementor-lite'),
                    'google' => __('Google', 'essential-addons-for-elementor-lite'),
                    'the_events_calendar' => __('The Events Calendar', 'essential-addons-for-elementor-lite'),

                ]),
                'default' => 'manual',
            ]
        );

        if (!apply_filters('eael/active_plugins', 'the-events-calendar/the-events-calendar.php')) {
            $this->add_control(
                'eael_the_event_calendar_warning_text',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => __('<strong>The Events Calendar</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=the-events-calendar&tab=search&type=term" target="_blank">The Events Calendar</a> first.',
                        'essential-addons-for-elementor'),
                    'content_classes' => 'eael-warning',
                    'condition' => [
                        'eael_event_calendar_type' => 'the_events_calendar',
                    ],
                ]
            );
        }

        if (!apply_filters('eael/pro_enabled', false)) {
            $this->add_control(
                'eael_event_calendar_pro_enable_warning',
                [
                    'label' => esc_html__('Only Available in Pro Version!', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                    'condition' => [
                        'eael_event_calendar_type' => ['eventon'],
                    ],
                ]
            );
        }


        do_action('eael/event-calendar/activation-notice', $this);

        $repeater = new Repeater;
        $repeater->start_controls_tabs('eael_event_content_tabs');

        $repeater->start_controls_tab(
            'eaelec_event_info_tab',
            [
                'label' => __('General', 'essential-addons-for-elementor-lite'),
            ]
        );

        $repeater->add_control(
            'eael_event_title',
            [
                'label'       => __('Title', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'eael_event_link',
            [
                'label'         => __('Link', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => __('https://sample-domain.com', 'essential-addons-for-elementor-lite'),
                'show_external' => true,
            ]
        );

        $repeater->add_control(
            'eael_event_all_day',
            [
                'label'        => __('All Day', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_block'  => false,
                'return_value' => 'yes',
            ]
        );

        $repeater->add_control(
            'eael_event_start_date',
            [
                'label'     => __('Start Date', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::DATE_TIME,
                'default'   => date('Y-m-d H:i', current_time('timestamp', 0)),
                'condition' => [
                    'eael_event_all_day' => '',
                ],
            ]
        );

        $repeater->add_control(
            'eael_event_end_date',
            [
                'label'     => __('End Date', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::DATE_TIME,
                'default'   => date('Y-m-d H:i', strtotime("+59 minute", current_time('timestamp', 0))),
                'condition' => [
                    'eael_event_all_day' => '',
                ],
            ]
        );

        $repeater->add_control(
            'eael_event_start_date_allday',
            [
                'label'          => __('Start Date', 'essential-addons-for-elementor-lite'),
                'type'           => Controls_Manager::DATE_TIME,
                'picker_options' => ['enableTime' => false],
                'default'        => date('Y-m-d', current_time('timestamp', 0)),
                'condition'      => [
                    'eael_event_all_day' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'eael_event_end_date_allday',
            [
                'label'          => __('End Date', 'essential-addons-for-elementor-lite'),
                'type'           => Controls_Manager::DATE_TIME,
                'picker_options' => ['enableTime' => false],
                'default'        => date('Y-m-d', current_time('timestamp', 0)),
                'condition'      => [
                    'eael_event_all_day' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'eael_event_bg_color',
            [
                'label'   => __('Event Background Color', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#5725ff',
            ]
        );

        $repeater->add_control(
            'eael_event_text_color',
            [
                'label'   => __('Event Text Color', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $repeater->add_control(
            'eael_event_border_color',
            [
                'label'   => __('Popup Ribbon Color', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#E8E6ED',
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'eaelec_event_content_tab',
            [
                'label' => __('Content', 'essential-addons-for-elementor-lite'),
            ]
        );

        $repeater->add_control(
            'eael_event_description',
            [
                'label' => __('Description', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::WYSIWYG,
            ]
        );

        $repeater->end_controls_tab();

        $this->add_control(
            'eael_event_items',
            [
                'label'       => __('Event', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    ['eael_event_title' => 'Event Title'],
                ],
                'title_field' => '{{ eael_event_title }}',
                'condition'   => [
                    'eael_event_calendar_type' => 'manual',
                ],
            ]
        );

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_event_google_calendar',
            [
                'label'     => __('Google Calendar', 'essential-addons-for-elementor-lite'),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'eael_event_calendar_type' => 'google',
                ],
            ]
        );

        $this->add_control(
            'eael_event_google_api_key',
            [
                'label'       => __('APi Key', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'description' => sprintf(__('<a href="https://essential-addons.com/elementor/docs/google-api-key/" class="eael-btn" target="_blank">%s</a>',
                    'essential-addons-for-elementor-lite'), 'Get API Key'),
            ]
        );

        $this->add_control(
            'eael_event_calendar_id',
            [
                'label'       => __('Calendar ID', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'description' => sprintf(__('<a href="https://essential-addons.com/elementor/docs/google-calendar-id/" class="eael-btn" target="_blank">%s</a>',
                    'essential-addons-for-elementor-lite'), 'Get google calendar ID'),
            ]
        );

        $this->add_control(
            'eael_google_calendar_start_date',
            [
                'label'   => __('Start Date', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', current_time('timestamp', 0)),
            ]
        );

        $this->add_control(
            'eael_google_calendar_end_date',
            [
                'label'   => __('End Date', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime("+6 months", current_time('timestamp', 0))),
            ]
        );

        $this->add_control(
            'eael_google_calendar_max_result',
            [
                'label'   => __('Max Result', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'default' => 100,
            ]
        );

        $this->end_controls_section();

        //the events calendar
        if (apply_filters('eael/active_plugins', 'the-events-calendar/the-events-calendar.php')) {
            $this->start_controls_section(
                'eael_event_the_events_calendar',
                [
                    'label'     => __('The Event Calendar', 'essential-addons-for-elementor-lite'),
                    'tab'       => Controls_Manager::TAB_CONTENT,
                    'condition' => [
                        'eael_event_calendar_type' => 'the_events_calendar',
                    ],
                ]
            );

            $this->add_control(
                'eael_the_events_calendar_fetch',
                [
                    'label'       => __('Get Events', 'essential-addons-for-elementor-lite'),
                    'type'        => Controls_Manager::SELECT,
                    'label_block' => true,
                    'default'     => ['all'],
                    'options'     => [
                        'all'        => __('All', 'essential-addons-for-elementor-lite'),
                        'date_range' => __('Date Range', 'essential-addons-for-elementor-lite'),
                    ],
                    'render_type' => 'none',
                ]
            );


            $this->add_control(
                'eael_the_events_calendar_start_date',
                [
                    'label'     => __('Start Date', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::DATE_TIME,
                    'default'   => date('Y-m-d H:i', current_time('timestamp', 0)),
                    'condition' => [
                        'eael_the_events_calendar_fetch' => 'date_range',
                    ],
                ]
            );

            $this->add_control(
                'eael_the_events_calendar_end_date',
                [
                    'label'     => __('End Date', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::DATE_TIME,
                    'default'   => date('Y-m-d H:i', strtotime("+6 months", current_time('timestamp', 0))),
                    'condition' => [
                        'eael_the_events_calendar_fetch' => 'date_range',
                    ],
                ]
            );

            $this->add_control(
                'eael_the_events_calendar_category',
                [
                    'label'       => __('Event Category', 'essential-addons-for-elementor-lite'),
                    'type'        => Controls_Manager::SELECT2,
                    'multiple'    => true,
                    'label_block' => true,
                    'default'     => [],
                    'options'     => $this->eael_get_tags(['taxonomy' => 'tribe_events_cat', 'hide_empty' => false]),
                ]
            );

            $this->add_control(
                'eael_the_events_calendar_max_result',
                [
                    'label'   => __('Max Result', 'essential-addons-for-elementor-lite'),
                    'type'    => Controls_Manager::NUMBER,
                    'min'     => 1,
                    'default' => 100,
                ]
            );

            $this->end_controls_section();
        }


        do_action('eael/event-calendar/source/control', $this);

        $this->start_controls_section(
            'eael_event_calendar_section',
            [
                'label' => __('Calendar', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eael_event_calendar_language',
            [
                'label'   => __('Language', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'options' => $this->eael_language_code_list(),
                'default' => 'en'
            ]
        );

        $this->add_control(
            'eael_event_calendar_default_view',
            [
                'label'   => __('Calendar Default View', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'timeGridDay'  => __('Day', 'essential-addons-for-elementor-lite'),
                    'timeGridWeek' => __('Week', 'essential-addons-for-elementor-lite'),
                    'dayGridMonth' => __('Month', 'essential-addons-for-elementor-lite'),
                    'listMonth'    => __('List', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'dayGridMonth',
            ]
        );

        $this->add_control(
            'eael_event_calendar_first_day',
            [
                'label'   => __('First Day of Week', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '0' => __('Sunday', 'essential-addons-for-elementor-lite'),
                    '1' => __('Monday', 'essential-addons-for-elementor-lite'),
                    '2' => __('Tuesday', 'essential-addons-for-elementor-lite'),
                    '3' => __('Wednesday', 'essential-addons-for-elementor-lite'),
                    '4' => __('Thursday', 'essential-addons-for-elementor-lite'),
                    '5' => __('Friday', 'essential-addons-for-elementor-lite'),
                    '6' => __('Saturday', 'essential-addons-for-elementor-lite'),
                ],
                'default' => '0',
            ]
        );

        $this->add_control(
            'eael_event_details_link_hide',
            [
                'label'        => __('Hide Event Details Link', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_block'  => false,
                'return_value' => 'yes',
                'description'  => __('Hide Event Details link in event popup','essential-addons-for-elementor-lite')
            ]
        );

        if (apply_filters('eael/active_plugins', 'eventON/eventon.php') && apply_filters('eael/pro_enabled', false)) {
            $this->add_control(
                'eael_event_on_featured_color',
                [
                    'label'     => __('Featured Event Color', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '#ffcb55',
                    'condition' => [
                        'eael_event_calendar_type' => 'eventon',
                    ],
                ]
            );
        }


        $this->add_control(
            'eael_event_global_bg_color',
            [
                'label'     => __('Event Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5725ff',
                'condition' => [
                    'eael_event_calendar_type!' => 'manual',
                ],
            ]
        );

        $this->add_control(
            'eael_event_global_text_color',
            [
                'label'     => __('Event Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'condition' => [
                    'eael_event_calendar_type!' => 'manual',
                ],
            ]
        );
        $this->add_control(
            'eael_event_global_popup_ribbon_color',
            [
                'label'     => __('Popup Ribbon Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#10ecab',
                'condition' => [
                    'eael_event_calendar_type!' => 'manual',
                ],
            ]
        );


        $this->end_controls_section();

        /**
         * Style Tab Started
         */
        $this->start_controls_section(
            'eael_event_calendar_interface',
            [
                'label' => __('Calendar', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'calendar_background_color',
            [
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper'                       => 'background: {{VALUE}}',
                    '{{WRAPPER}} .eael-event-calendar-wrapper table tbody > tr > td' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'calendar_border_color',
            [
                'label'     => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#CFCFDA',
                'selectors' => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc td'                                              => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-event-calendar-wrapper hr.fc-divider'                                       => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc th'                                              => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-view  td.fc-today'                               => 'border-left-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-view  table thead:first-child tr:first-child td' => 'border-top-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-view.fc-listWeek-view'                           => 'border-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-view.fc-listMonth-view'                          => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_calendar_box_shadow',
                'label'    => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-event-calendar-wrapper .fc-view-container .fc-view > table',
            ]
        );

        $this->add_responsive_control(
            'calendar_inside',
            [
                'label'      => esc_html__('Inside Space', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'calendar_outside',
            [
                'label'      => esc_html__('Outside Space', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );

        $this->add_control(
            'calendar_title_heading',
            [
                'label' => __('Title', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'calendar_title_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .fc-toolbar h2',
            ]
        );

        $this->add_control(
            'calendar_title_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Buttons style
        $this->add_control(
            'buttons_style_heading',
            [
                'label'     => __('Button', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'calendar_button_typography_normal',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-event-calendar-wrapper .fc-toolbar.fc-header-toolbar .fc-button',
            ]
        );

        $this->start_controls_tabs('calendar_buttons_style');

        // Normal
        $this->start_controls_tab(
            'button_normal_state',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'button_color_normal',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:not(.fc-button-active)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_normal',
            [
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:not(.fc-button-active)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border_normal',
                'label'    => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:not(.fc-button-active)',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius_normal',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:not(.fc-button-active)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'buttons_margin',
            [
                'label'      => esc_html__('Space', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:not(.fc-button-active)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );
        // Buttons style

        $this->end_controls_tab();

        // Hover
        $this->start_controls_tab(
            'button_hover_state',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'button_color_hover',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover',
            [
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border_hover',
                'label'    => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:hover',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius_hover',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );

        $this->end_controls_tab();

        // Active
        $this->start_controls_tab(
            'button_active_state',
            [
                'label' => __('Active', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'button_color_active',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button.fc-button-active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_active',
            [
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button.fc-button-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border_active',
                'label'    => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button.fc-button-active',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius_active',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button.fc-button-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'buttons_margin_active',
            [
                'label'      => esc_html__('Space', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button.fc-button-active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs(); # end of $this->add_controls_tabs('calendar_buttons_style');

        $this->end_controls_section();

        /**
         * Tab: Style => Panel: Days
         * -----------------------------------------------
         */
        $this->start_controls_section(
            'calendar_week_days',
            [
                'label' => __('Day', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'days_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .fc-row table thead:first-child tr:first-child th > span,{{WRAPPER}} .fc-listWeek-view .fc-list-table .fc-widget-header span,{{WRAPPER}} .fc-listMonth-view .fc-list-table .fc-widget-header span',
            ]
        );

        $this->add_control(
            'days_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-row table thead:first-child tr:first-child th > span' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_responsive_control(
            'days_position_alignment',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'   => 'center',
                'toggle'    => true,
                'selectors' => [
                    '{{WRAPPER}} .fc-row table thead:first-child tr:first-child th' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'days_background',
                'label'    => __('Background', 'essential-addons-for-elementor-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .fc-row table thead:first-child tr:first-child th',
                'exclude'  => [
                    'image',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Tab: Style => Panel: Time
         * -----------------------------------------------
         */
        $this->start_controls_section(
            'calendar_week_time',
            [
                'label' => __('Time', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'time_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .fc-unthemed .fc-timeGridDay-view .fc-bg table tbody tr>td span, {{WRAPPER}} .fc-unthemed .fc-timeGridWeek-view .fc-bg table tbody tr>td span ,{{WRAPPER}} .fc-unthemed .fc-timeGridDay-view .fc-slats table tbody tr>td span ,{{WRAPPER}} .fc-unthemed .fc-timeGridWeek-view .fc-slats table tbody tr>td span',
            ]
        );

        $this->add_control(
            'time_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-unthemed .fc-timeGridDay-view .fc-bg table tbody tr>td span'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fc-unthemed .fc-timeGridWeek-view .fc-bg table tbody tr>td span'    => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fc-unthemed .fc-timeGridWeek-view .fc-slats table tbody tr>td span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .fc-unthemed .fc-timeGridDay-view .fc-slats table tbody tr>td span'  => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'date_styles',
            [
                'label' => __('Date', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'date_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .fc-day-number',
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fc-day-number' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'date_number_background',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => __('Number Background', 'essential-addons-for-elementor-lite'),
                'selectors' => [
                    '{{WRAPPER}} .fc-day-number' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'date_background',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'selectors' => [
                    '{{WRAPPER}} .fc-day'                  => 'background: {{VALUE}} !important',
                    '{{WRAPPER}} .fc-unthemed td.fc-today' => 'background: {{VALUE}} !important',
                    '{{WRAPPER}} table tbody > tr > td'    => 'background: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_position_alignment',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'   => 'center',
                'toggle'    => true,
                'selectors' => [
                    '{{WRAPPER}} .fc-day-number'                                     => 'float: unset',
                    '{{WRAPPER}} .fc-view table thead:first-child tr:first-child td' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_padding',
            [
                'label'      => esc_html__('Inside Space', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .fc-day-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .fc-day-number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'date_margin',
            [
                'label'      => esc_html__('Outside Space', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .fc-day-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'today_date_setting',
            [
                'label'     => __('Today Date', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'today_date_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'default'   => '#1111e1',
                'selectors' => [
                    '{{WRAPPER}} .fc-today .fc-day-number' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'today_date_background',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'selectors' => [
                    '{{WRAPPER}} .fc-unthemed td.fc-today' => 'background: {{VALUE}} !important',
                ],
            ]
        );

        $this->end_controls_section();


        /**
         * Tab: Style => Panel: List
         * -----------------------------------------------
         */
        $this->start_controls_section(
            'calendar_list_view',
            [
                'label' => __('List view', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_list_view_header_heading',
            [
                'label' => __('Header', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'list_row_header_color',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-listWeek-view .fc-list-table .fc-widget-header span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-listMonth-view .fc-list-table .fc-widget-header span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'list_header_background_color',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f1edf8',
                'selectors' => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-view.fc-listWeek-view .fc-list-table tr.fc-list-heading td.fc-widget-header' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-view.fc-listMonth-view .fc-list-table tr.fc-list-heading td.fc-widget-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_list_view_body_heading',
            [
                'label' => __('Body', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'list_element_text_color',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-listWeek-view .fc-list-table .fc-list-item' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-listMonth-view .fc-list-table .fc-list-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'list_element_even_color',
            [
                'label'     => __('Even row Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-view.fc-listWeek-view .fc-list-table tr.fc-list-item:nth-child(even) td' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-view.fc-listMonth-view .fc-list-table tr.fc-list-item:nth-child(even) td' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'list_element_odd_color',
            [
                'label'     => __('Odd row Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-view.fc-listWeek-view .fc-list-table tr.fc-list-item:nth-child(odd) td' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-event-calendar-wrapper .fc-view.fc-listMonth-view .fc-list-table tr.fc-list-item:nth-child(odd) td' => 'background-color: {{VALUE}};',

                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eaelec_event_section',
            [
                'label' => __('Events', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_event_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .fc-content .fc-title,{{WRAPPER}} .fc-content .fc-time,{{WRAPPER}} .eael-event-calendar-wrapper .fc-list-table .fc-list-item td',
            ]
        );

        $this->add_responsive_control(
            'day_event_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .fc-day-grid-event' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'day_event_padding',
            [
                'label'      => esc_html__('Inside Space', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'.'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .fc-day-grid-event' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'day_event_margin',
            [
                'label'      => esc_html__('Outside Space', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'.'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .fc-day-grid-event' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'event_popup',
            [
                'label' => __('Event Popup', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'event_popup_title_heading',
            [
                'label' => __('Title', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'event_popup_title_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eaelec-modal-header .eael-ec-modal-title',
            ]
        );

        $this->add_control(
            'event_popup_title_color',
            [
                'label'     => __('Title Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eaelec-modal-header .eael-ec-modal-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'event_popup_date_heading',
            [
                'label'     => __('Date', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'event_popup_date_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eaelec-modal-header > span.eaelec-event-popup-date',
            ]
        );

        $this->add_control(
            'event_popup_date_color',
            [
                'label'     => __('Date Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eaelec-modal-header span.eaelec-event-date-start' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eaelec-modal-header span.eaelec-event-date-end'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'event_popup_date_icon',
            [
                'label'     => __('Date Icon', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'event_popup_date_icon_size',
            [
                'label'      => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eaelec-modal-header span.eaelec-event-date-start i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'event_popup_date_icon_color',
            [
                'label'     => __('Icon Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eaelec-modal-header span.eaelec-event-date-start i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'event_popup_content_heading',
            [
                'label'     => __('Content', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'event_popup_content_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eaelec-modal-body',
            ]
        );

        $this->add_control(
            'event_popup_content_color',
            [
                'label'     => __('Content Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eaelec-modal-body' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'event_popup_close_button_style',
            [
                'label'     => __(' Close Button', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'close_button_icon_size',
            [
                'label'      => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eaelec-modal-close > span' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'close_button_size',
            [
                'label'      => __('Button Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eaelec-modal-close' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'close_button_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eaelec-modal-close > span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'close_button_background',
                'label'    => __('Background', 'essential-addons-for-elementor-lite'),
                'types'    => [
                    'classic',
                    'gradient',
                ],
                'selector' => '{{WRAPPER}} .eael-event-calendar-wrapper .eaelec-modal-close',
                'exclude'  => [
                    'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'close_button_border',
                'label'    => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-event-calendar-wrapper .eaelec-modal-close',
            ]
        );

        $this->add_responsive_control(
            'close_button_border_radius',
            [
                'label'      => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper .eaelec-modal-close' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'close_button_box_shadow',
                'label'    => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-event-calendar-wrapper .eaelec-modal-close',
            ]
        );

        $this->add_control(
            'event_popup_ext_link_heading',
            [
                'label'     => __('External Link', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'event_popup_ext_link_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eaelec-modal-footer .eaelec-event-details-link',
            ]
        );

        $this->add_control(
            'event_popup_ext_link_color',
            [
                'label'     => __('Date Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eaelec-modal-footer .eaelec-event-details-link' => 'color: {{VALUE}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'event_popup_border',
                'label'    => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eaelec-modal .eaelec-modal-content',
            ]
        );

        $this->add_responsive_control(
            'event_popup_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eaelec-modal .eaelec-modal-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'event_popup_background',
                'label'    => __('Background', 'essential-addons-for-elementor-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eaelec-modal .eaelec-modal-content',
                'exclude'  => [
                    'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'event_popup_box_shadow',
                'label'    => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eaelec-modal .eaelec-modal-content',
            ]
        );

        $this->end_controls_section();
    }

    protected function render () {
        $settings = $this->get_settings_for_display();

        if (in_array($settings['eael_event_calendar_type'], ['eventon'])) {
            $data = apply_filters('eael/event-calendar/integration', [], $settings);
        } elseif ($settings['eael_event_calendar_type'] == 'google') {
            $data = $this->get_google_calendar_events($settings);
        } elseif ($settings['eael_event_calendar_type'] == 'the_events_calendar') {
            $data = $this->get_the_events_calendar_events($settings);
        } else {
            $data = $this->get_manual_calendar_events($settings);
        }

        $local = $settings['eael_event_calendar_language'];
        $default_view = $settings['eael_event_calendar_default_view'];
        $translate_date = [
            'today' =>__('Today', 'essential-addons-for-elementor-lite'),
            'tomorrow' =>__('Tomorrow', 'essential-addons-for-elementor-lite'),
        ];

        echo '<div class="eael-event-calendar-wrapper">';

        echo '<div id="eael-event-calendar-'.$this->get_id().'" class="eael-event-calendar-cls"
            data-cal_id = "'.$this->get_id().'"
            data-locale = "'.$local.'"
            data-translate = "'.htmlspecialchars(json_encode($translate_date), ENT_QUOTES, 'UTF-8').'"
            data-defaultview = "'.$default_view.'"
            data-events="'.htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8').'"
            data-first_day="'.$settings['eael_event_calendar_first_day'].'"></div>
            '.$this->eaelec_load_event_details().'
        </div>';
    }

    protected function eaelec_load_event_details () {
        return '<div id="eaelecModal" class="eaelec-modal eael-zoom-in">
            <div class="eael-ec-modal-bg"></div>
            <div class="eaelec-modal-content">
                <div class="eaelec-modal-header">
                    <div class="eaelec-modal-close"><span><i class="fas fa-times"></i></span></div>
                    <h2 class="eael-ec-modal-title"></h2>
                    <span class="eaelec-event-date-start eaelec-event-popup-date"></span>
                    <span class="eaelec-event-date-end eaelec-event-popup-date"></span>
                </div>
                <div class="eaelec-modal-body">
                    <p></p>
                </div>
                <div class="eaelec-modal-footer">
                    <a class="eaelec-event-details-link">'.__("Event Details","essential-addons-for-elementor-lite").'</a>
                </div>
            </div>
        </div>';
    }

    public function get_manual_calendar_events ($settings) {
        $events = $settings['eael_event_items'];
        $data = [];
        if ($events) {
            $i = 0;

            foreach ($events as $event) {

                if ($event['eael_event_all_day'] == 'yes') {
                    $start = $event["eael_event_start_date_allday"];
                    $end = date('Y-m-d', strtotime("+1 days", strtotime($event["eael_event_end_date_allday"])));
                } else {
                    $start = $event["eael_event_start_date"];
                    $end = date('Y-m-d H:i', strtotime($event["eael_event_end_date"])).":01";
                }

                $data[] = [
                    'id'          => $i,
                    'title'       => !empty($event["eael_event_title"]) ? $event["eael_event_title"] : 'No Title',
                    'description' => $event["eael_event_description"],
                    'start'       => $start,
                    'end'         => $end,
                    'borderColor' => !empty($event['eael_event_border_color']) ? $event['eael_event_border_color'] : '#10ecab',
                    'textColor'   => $event['eael_event_text_color'],
                    'color'       => $event['eael_event_bg_color'],
                    'url'         => ($settings['eael_event_details_link_hide']!=='yes')?$event["eael_event_link"]["url"]:'',
                    'allDay'      => $event['eael_event_all_day'],
                    'external'    => $event['eael_event_link']['is_external'],
                    'nofollow'    => $event['eael_event_link']['nofollow'],
                ];

                $i++;
            }
        }
        return $data;
    }

    /**
     * get google calendar events
     *
     * @param $settings
     *
     * @return array
     */
    public function get_google_calendar_events ($settings) {

        if (empty($settings['eael_event_google_api_key']) && empty($settings['eael_event_calendar_id'])) {
            return [];
        }

        $calendar_id = urlencode($settings['eael_event_calendar_id']);
        $base_url = "https://www.googleapis.com/calendar/v3/calendars/{$calendar_id}/events";

        $start_date = strtotime($settings['eael_google_calendar_start_date']);
        $end_date = strtotime($settings['eael_google_calendar_end_date']);

        $arg = [
            'key'          => $settings['eael_event_google_api_key'],
            'maxResults'   => $settings['eael_google_calendar_max_result'],
            'timeMin'      => urlencode(date('c', $start_date)),
            'singleEvents' => 'true',
            'calendar_id'  => urlencode($settings['eael_event_calendar_id']),
        ];

        if (!empty($end_date) && $end_date > $start_date) {
            $arg['timeMax'] = urlencode(date('c', $end_date));
        }

        $transient_key = 'eael_google_calendar_'.md5(implode('', $arg));
        $data = get_transient($transient_key);

        if (isset($arg['calendar_id'])) {
            unset($arg['calendar_id']);
        }

        if(empty($data)){
            $data = wp_remote_retrieve_body(wp_remote_get(add_query_arg($arg, $base_url)));
            set_transient($transient_key, $data, 1 * HOUR_IN_SECONDS);
        }

        if (is_wp_error($data)) {
            return [];
        }

        $data = json_decode($data);
        if (isset($data->items)) {
            $calendar_data = [];

            foreach ($data->items as $key => $item) {
                if ($item->status !== 'confirmed') {
                    continue;
                }
                $all_day = '';
                if (isset($item->start->date)) {
                    $all_day = 'yes';
                    $ev_start_date = $item->start->date;
                    $ev_end_date = $item->end->date;
                } else {
                    $ev_start_date = $item->start->dateTime;
                    $ev_end_date = $item->end->dateTime;
                }

                $calendar_data[] = [
                    'id'          => ++$key,
                    'title'       => !empty($item->summary) ? $item->summary : 'No Title',
                    'description' => isset($item->description) ? $item->description : '',
                    'start'       => $ev_start_date,
                    'end'         => $ev_end_date,
                    'borderColor' => !empty($settings['eael_event_global_popup_ribbon_color']) ? $settings['eael_event_global_popup_ribbon_color'] : '#10ecab',
                    'textColor'   => $settings['eael_event_global_text_color'],
                    'color'       => $settings['eael_event_global_bg_color'],
                    'url'         => ($settings['eael_event_details_link_hide']!=='yes')?$item->htmlLink:'',
                    'allDay'      => $all_day,
                    'external'    => 'on',
                    'nofollow'    => 'on',
                ];
            }


        }

        return $calendar_data;
    }

    /**
     * @param $settings
     *
     * @return array
     * @since  3.8.2
     */
    public function get_the_events_calendar_events ($settings) {

        if (!function_exists('tribe_get_events')) {
            return [];
        }
        $arg = [
            'posts_per_page' => $settings['eael_the_events_calendar_max_result'],
        ];
        if ($settings['eael_the_events_calendar_fetch'] == 'date_range') {
            $arg['start_date'] = $settings['eael_the_events_calendar_start_date'];
            $arg['end_date'] = $settings['eael_the_events_calendar_end_date'];
        }
        if (!empty($settings['eael_the_events_calendar_category'])) {
            $arg['tax_query'] = [
                [
                    'taxonomy' => 'tribe_events_cat', 'field' => 'id',
                    'terms'    => $settings['eael_the_events_calendar_category']
                ]
            ];
        }
        $events = tribe_get_events($arg);
        if (empty($events)) {
            return [];
        }
        $calendar_data = [];
        foreach ($events as $key => $event) {
            $date_format = 'Y-m-d';
            $all_day = 'yes';
            if (!tribe_event_is_all_day($event->ID)) {
                $date_format .= ' H:i';
                $all_day = '';
            }
            $calendar_data[] = [
                'id'          => ++$key,
                'title'       => !empty($event->post_title) ? $event->post_title : __('No Title',
                    'essential-addons-for-elementor-lite'),
                'description' => $event->post_content,
                'start'       => tribe_get_start_date($event->ID, true, $date_format),
                'end'         => tribe_get_end_date($event->ID, true, $date_format),
                'borderColor' => !empty($settings['eael_event_global_popup_ribbon_color']) ? $settings['eael_event_global_popup_ribbon_color'] : '#10ecab',
                'textColor'   => $settings['eael_event_global_text_color'],
                'color'       => $settings['eael_event_global_bg_color'],
                'url'         => ($settings['eael_event_details_link_hide']!=='yes')?get_the_permalink($event->ID):'',
                'allDay'      => $all_day,
                'external'    => 'on',
                'nofollow'    => 'on',
            ];
        }
        return $calendar_data;
    }
}
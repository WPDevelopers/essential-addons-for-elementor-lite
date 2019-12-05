<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Scheme_Typography;
use \Elementor\Utils;
use \Elementor\Widget_Base;

class Event_Calendar extends Widget_Base {
	use \Essential_Addons_Elementor\Traits\Helper;

	public function get_name() {
		return 'eael-event-calendar';
	}

	public function get_title() {
		return esc_html__( 'EA Event Calendar', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-calendar';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {
        
        $this->start_controls_section(
            'eael_event_section',
            [
                'label' => __('Events', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'eael_event_calendar_type',
            [
                'label' => __('Source', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'manual' => __('Manual', 'essential-addons-elementor'),
                    'others' => __('Others', 'essential-addons-elementor'),
                ],
                'default' => 'manual',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->start_controls_tabs('eael_event_content_tabs');

            $repeater->start_controls_tab(
                'eaelec_event_info_tab',
                [
                    'label'	=> __( 'General', 'essential-addons-elementor' )
                ]
            );
            
            $repeater->add_control(
                'eael_event_title',
                [
                    'label'         => __( 'Title', 'essential-addons-elementor' ),
                    'type'          => Controls_Manager::TEXT,
                    'label_block'   => true,
                ]
            );

            $repeater->add_control(
                'eael_event_link',
                [
                    'label' => __( 'Link', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::URL,
                    'placeholder' => __( 'https://sample-domain.com', 'essential-addons-elementor' ),
                    'show_external' => true,
                    'default' => [
                        'url' => '#',
                        'is_external' => false,
                        'nofollow' => false,
                    ],
                ]
            );
            
            $repeater->add_control(
                'eael_event_start_date',
                [
                    'label' => __( 'Start Date', 'plugin-domain' ),
                    'type' => Controls_Manager::DATE_TIME,
                    'default'   => date( 'Y-m-d H:i', current_time( 'timestamp', 0 ) ),
                ]
            );
            
            $repeater->add_control(
                'eael_event_end_date',
                [
                    'label' => __( 'End Date', 'plugin-domain' ),
                    'type' => Controls_Manager::DATE_TIME,
                    'default'   => date( 'Y-m-d H:i', strtotime("+5 minute", current_time( 'timestamp', 0 )) ),
                ]
            );

            $repeater->add_control(
                'eael_event_bg_color',
                [
                    'label' => __('Event Background Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default'   => '#009900',
                ]
            );
    
            $repeater->add_control(
                'eael_event_text_color',
                [
                    'label' => __('Event Text Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default'   => '#FFFFFF',
                ]
            );
    
            $repeater->add_control(
                'eael_event_border_color',
                [
                    'label' => __('Event Border Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default'   => '#009900',
                ]
            );

            $repeater->end_controls_tab();

            $repeater->start_controls_tab(
				'eaelec_event_content_tab',
				[
					'label'	=> __( 'Content', 'essential-addons-elementor' )
				]
            );

            $repeater->add_control(
                'eael_event_description',
                [
                    'label' => __( 'Description', 'essential-addons-elementor' ),
                    'type'  => Controls_Manager::WYSIWYG,
                ]
            );
            
            $repeater->end_controls_tab();

            $this->add_control(
                'eael_event_items',
                [
                    'label'         => __( 'Events', 'essential-addons-elementor' ),
                    'type'          => Controls_Manager::REPEATER,
                    'fields'        => $repeater->get_controls(),
                    'default' => [
                        [ 'eael_event_title' => 'Event Title' ],
                    ],
                    'title_field'   => '{{ eael_event_title }}',
                    'condition' => [
                        'eael_event_calendar_type' => 'manual',
                    ],
    
                ]
            );
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

        $this->start_controls_section(
            'eael_event_calendar_section',
            [
                'label' => __('Calendar', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
			'eael_event_calendar_days_heading',
			[
				'label' => __( 'Days', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
        );
        
        $this->add_control(
            'eael_event_calendar_days_sat',
            [
                'label' => __('Saturday', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'Sat'
            ]
        );

        $this->add_control(
            'eael_event_calendar_days_sun',
            [
                'label' => __('Sunday', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'Sun'
            ]
        );

        $this->add_control(
            'eael_event_calendar_days_mon',
            [
                'label' => __('Monday', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'Mon'
            ]
        );

        $this->add_control(
            'eael_event_calendar_days_tue',
            [
                'label' => __('Tuesday', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'Tue'
            ]
        );

        $this->add_control(
            'eael_event_calendar_days_wed',
            [
                'label' => __('Wednesday', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'Wed'
            ]
        );

        $this->add_control(
            'eael_event_calendar_days_thu',
            [
                'label' => __('Thursday', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'Thu'
            ]
        );

        $this->add_control(
            'eael_event_calendar_days_fri',
            [
                'label' => __('Friday', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'Fri'
            ]
        );

        $this->add_control(
			'eael_event_calendar_months_name',
			[
				'label' => __( 'Months', 'essential-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
			]
        );
        
        $this->add_control(
            'eael_event_calendar_month_jan',
            [
                'label' => __('January', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'January'
            ]
        );

        $this->add_control(
            'eael_event_calendar_month_feb',
            [
                'label' => __('February', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'February'
            ]
        );

        $this->add_control(
            'eael_event_calendar_month_mar',
            [
                'label' => __('March', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'March'
            ]
        );

        $this->add_control(
            'eael_event_calendar_month_apr',
            [
                'label' => __('April', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'April'
            ]
        );

        $this->add_control(
            'eael_event_calendar_month_may',
            [
                'label' => __('May', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'May'
            ]
        );

        $this->add_control(
            'eael_event_calendar_month_jun',
            [
                'label' => __('June', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'June'
            ]
        );

        $this->add_control(
            'eael_event_calendar_month_jul',
            [
                'label' => __('July', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'July'
            ]
        );

        $this->add_control(
            'eael_event_calendar_month_aug',
            [
                'label' => __('August', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'August'
            ]
        );

        $this->add_control(
            'eael_event_calendar_month_sep',
            [
                'label' => __('September', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'September'
            ]
        );

        $this->add_control(
            'eael_event_calendar_month_oct',
            [
                'label' => __('October', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'October'
            ]
        );

        $this->add_control(
            'eael_event_calendar_month_nov',
            [
                'label' => __('November', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'November'
            ]
        );

        $this->add_control(
            'eael_event_calendar_month_dec',
            [
                'label' => __('December', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                'default'   => 'December'
            ]
        );

        $this->add_control(
            'eael_event_calendar_first_day',
            [
                'label' => __('First Day of Week', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '0' => __('Sunday', 'essential-addons-elementor'),
                    '1' => __('Monday', 'essential-addons-elementor'),
                    '2' => __('Tuesday', 'essential-addons-elementor'),
                    '3' => __('Wednesday', 'essential-addons-elementor'),
                    '4' => __('Thursday', 'essential-addons-elementor'),
                    '5' => __('Friday', 'essential-addons-elementor'),
                    '6' => __('Saturday', 'essential-addons-elementor'),
                ],
                'default' => '0',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        
        /**
         * Style Tab Started
         */
        $this->start_controls_section(
            'eael_event_calendar_interface',
            [
                'label' => __('Calendar', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_event_calendar_border_type',
            [
                'label' => __('Border Type', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('None', 'essential-addons-elementor'),
                    'solid' => __('Solid', 'essential-addons-elementor'),
                    'double' => __('Double', 'essential-addons-elementor'),
                    'dotted' => __('Dotted', 'essential-addons-elementor'),
                    'dashed' => __('Dashed', 'essential-addons-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_event_calendar_border_width',
            [
                'label' => esc_html__('Border Width', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_event_calendar_border_color',
            [
                'label' => __('Border Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#009900',
                'selectors' => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_event_calendar_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'eael_event_calendar_title_color',
            [
                'label' => __('Title Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#009900',
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar h2' => 'color: {{VALUE}}!important',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_event_calendar_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .eael-event-calendar-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_event_calendar_today_bg_color',
            [
                'label' => __('Today Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#009900',
                'selectors' => [
                    '{{WRAPPER}} .fc-unthemed td.fc-today' => 'color: {{VALUE}}!important',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
			'eael_event_calendar_button_style',
			[
				'label' => __( 'Button', 'essential-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
			]
        );

        $this->add_control(
            'eael_event_calendar_button_color',
            [
                'label' => __('Button Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#EAEAEA',
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar .fc-button' => 'background: {{VALUE}}!important',
                ],
            ]
        );

        $this->add_control(
            'eael_event_calendar_button_hover_color',
            [
                'label' => __('Button Hover Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#EAEAEA',
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar .fc-button:hover' => 'background: {{VALUE}}!important',
                ],
            ]
        );

        $this->add_control(
            'eael_event_calendar_button_active_color',
            [
                'label' => __('Button Active Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#EAEAEA',
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar .fc-state-active' => 'background: {{VALUE}}!important',
                ],
            ]
        );

        $this->add_control(
            'eael_event_calendar_today_button_color',
            [
                'label' => __('Today Button Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#EAEAEA',
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar .fc-today-button' => 'background: {{VALUE}}!important',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_event_week_interface',
            [
                'label' => __('Weekday', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_event_calendar_weekday_bg_color',
            [
                'label' => __('Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#009900',
                'selectors' => [
                    '{{WRAPPER}} .fc-row table thead:first-child tr:first-child th' => 'background: {{VALUE}}!important',
                ],
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_event_calendar_weekday_typography',
				'label' => __( 'Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .fc-row table thead:first-child tr:first-child th',
			]
		);

        $this->add_control(
            'eael_event_calendar_weekday_font_color',
            [
                'label' => __('Font Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#009900',
                'selectors' => [
                    '{{WRAPPER}} .fc-row table thead:first-child tr:first-child th' => 'color: {{VALUE}}!important',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_event_calendar_date_interface',
            [
                'label' => __('Date', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_event_calendar_date_font_color',
            [
                'label' => __('Font Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#242424',
                'selectors' => [
                    '{{WRAPPER}} .fc-day-number' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_event_calendar_date_bg_color',
            [
                'label' => __('Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#EAEAEA',
                'selectors' => [
                    '{{WRAPPER}} .fc-day-number' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_event_calendar_date_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .fc-day-number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_event_calendar_date_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .fc-day-number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_event_calendar_date_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .fc-day-number' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


	protected function render() {
        $settings = $this->get_settings_for_display();
        $events = $settings['eael_event_items'];
        
        $daysWeek = array(
                                $settings['eael_event_calendar_days_sun'],
                                $settings['eael_event_calendar_days_mon'],
                                $settings['eael_event_calendar_days_tue'],
                                $settings['eael_event_calendar_days_wed'],
                                $settings['eael_event_calendar_days_thu'],
                                $settings['eael_event_calendar_days_fri'],
                                $settings['eael_event_calendar_days_sat']
                            );

        $monthNames = array(
                                $settings['eael_event_calendar_month_jan'],
                                $settings['eael_event_calendar_month_feb'],
                                $settings['eael_event_calendar_month_mar'],
                                $settings['eael_event_calendar_month_apr'],
                                $settings['eael_event_calendar_month_may'],
                                $settings['eael_event_calendar_month_jun'],
                                $settings['eael_event_calendar_month_jul'],
                                $settings['eael_event_calendar_month_aug'],
                                $settings['eael_event_calendar_month_sep'],
                                $settings['eael_event_calendar_month_oct'],
                                $settings['eael_event_calendar_month_nov'],
                                $settings['eael_event_calendar_month_dec']
                            );
        echo '<div class="eael-event-calendar-wrapper">';
        if($events):
            $data = array();
            $i=0;
            foreach($events as $event):
                $data[] = array(
                                    'id'            => $i,
                                    'title'         => $event["eael_event_title"],
                                    'description'   => $event["eael_event_description"],
                                    'start'         => $event["eael_event_start_date"],
                                    'end'           => $event["eael_event_end_date"],
                                    'borderColor'   => $event['eael_event_border_color'],
                                    'textColor'     => $event['eael_event_text_color'],
                                    'color'         => $event['eael_event_bg_color'],
                                    'url'           => $event["eael_event_link"]["url"],
                                    'dayNames'      => ['Sunday', 'Monday', 'Tuesday', 'Wednesday',
                                    'Thursday', 'Friday', 'Saturday']
                                );
                $i++;
            endforeach;
        endif;
        //
        echo '<div id="eael-event-calendar" class="eael-event-calendar-cls"
                data-events="' . htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') . '"
                data-month_names="' . htmlspecialchars(json_encode($monthNames), ENT_QUOTES, 'UTF-8') . '"
                data-first_day="' . $settings['eael_event_calendar_first_day'] . '"
                data-days_week="' . htmlspecialchars(json_encode($daysWeek), ENT_QUOTES, 'UTF-8') . '"></div>';
        
        $this->eaelec_load_event_details();    
        echo '</div>';
    }

    protected function eaelec_load_event_details(){
        ?>
        <div id="eaelecModal" class="eaelec-modal">
            <div class="eaelec-modal-content">
                <div class="eaelec-modal-header">
                    <span class="eaelec-modal-close">&times;</span>
                    <h2></h2>
                </div>
                <div class="eaelec-modal-body">
                    <span>Event Start:</span>
                    <span class="eaelec-event-date-start"></span>
                    <br>
                    <span>Event End:</span>
                    <span class="eaelec-event-date-end"></span>
                    <p></p>
                </div>
                <div class="eaelec-modal-footer">
                    <a href="#">Event Details</a>
                </div>
            </div>
        </div>
        <?php
    }

}
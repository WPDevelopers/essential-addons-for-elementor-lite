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
                ]
            );
            
            $repeater->add_control(
                'eael_event_end_date',
                [
                    'label' => __( 'End Date', 'plugin-domain' ),
                    'type' => Controls_Manager::DATE_TIME,
                ]
            );

            $repeater->end_controls_tab();

            $repeater->start_controls_tab(
				'eaelec_event_content_tab',
				[
					'label'	=> __( 'Description', 'essential-addons-elementor' )
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
				//'separator' => 'before',
			]
        );
        
        $this->add_control(
            'eael_event_calendar_days_sat',
            [
                'label' => __('Saturday', 'essential-addons-elementor'),
                'type' => Controls_Manager::TEXT,
                //'label_block' => true,
                //'show_label' => false,
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

        $this->end_controls_section();

        
        /**
         * Style Tab Started
         */
        $this->start_controls_section(
            'eael_event_event_interface',
            [
                'label' => __('Event', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_event_bg_color',
            [
                'label' => __('Event Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#009900',
            ]
        );

        $this->add_control(
            'eael_event_text_color',
            [
                'label' => __('Event Text Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
            ]
        );

        $this->add_control(
            'eael_event_border_color',
            [
                'label' => __('Event Border Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#009900',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_event_calendar_head_interface',
            [
                'label' => __('Calendar Head', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_event_calendar_title_color',
            [
                'label' => __('Calendar Title Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default'   => '#009900',
                'selectors' => [
                    '{{WRAPPER}} .fc-toolbar h2' => 'color: {{VALUE}}!important',
                    '{{WRAPPER}} .fc-toolbar .fc-button' => 'background: {{VALUE}}!important',
                    '{{WRAPPER}} .fc-row table thead:first-child tr:first-child th' => 'background: {{VALUE}}!important',
                ],
            ]
        );

        $this->end_controls_section();

    }


	protected function render( ) {
        $settings = $this->get_settings_for_display();
        $events = $settings['eael_event_items'];
        /*
        $daysWeek = "[ 
                        '" . $settings['eael_event_calendar_days_sun'] . "',
                        '" . $settings['eael_event_calendar_days_mon'] . "',
                        '" . $settings['eael_event_calendar_days_tue'] . "',
                        '" . $settings['eael_event_calendar_days_wed'] . "',
                        '" . $settings['eael_event_calendar_days_thu'] . "',
                        '" . $settings['eael_event_calendar_days_fri'] . "',
                        '" . $settings['eael_event_calendar_days_sat'] .  "']";

                        '" .  . "'," .
                        '" . $settings['eael_event_calendar_days_mon'] . "'," .
                        '" . $settings['eael_event_calendar_days_tue'] . "'," .
                        '" . $settings['eael_event_calendar_days_wed'] . "'," .
                        '" . $settings['eael_event_calendar_days_thu'] . "'," .
                        '" . $settings['eael_event_calendar_days_fri'] . "',]""; */
        $daysWeek = array(
                                $settings['eael_event_calendar_days_sun'],
                                $settings['eael_event_calendar_days_mon'],
                                $settings['eael_event_calendar_days_tue'],
                                $settings['eael_event_calendar_days_wed'],
                                $settings['eael_event_calendar_days_thu'],
                                $settings['eael_event_calendar_days_fri'],
                                $settings['eael_event_calendar_days_sat']
                            );
        echo '<div class="eael-event-calendar-wrapper" style="padding:20px;">';
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
                                    'borderColor'   => $settings['eael_event_border_color'],
                                    'textColor'     => $settings['eael_event_text_color'],
                                    'color'         => $settings['eael_event_bg_color'],
                                    'url'           => $event["eael_event_link"]["url"],
                                    'dayNames'      => ['Sunday', 'Monday', 'Tuesday', 'Wednesday',
                                    'Thursday', 'Friday', 'Saturday']
                                );
                $i++;
            endforeach;
        endif;
        echo '<div id="eael-event-calendar" class="eael-event-calendar-cls"
                data-events="' . htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') . '"
                data-days_week="' . htmlspecialchars(json_encode($daysWeek), ENT_QUOTES, 'UTF-8') . '"></div>';
        
        echo '<div id="eaelecModal" class="eaelec-modal">
                <div class="eaelec-modal-content">
                  <div class="eaelec-modal-header">
                    <span class="eaelec-modal-close">&times;</span>
                    <h2></h2>
                  </div>
                  <div class="eaelec-modal-body">
                    <p></p>
                  </div>
                  <div class="eaelec-modal-footer">
                    <a href="#">Event Details</a>
                  </div>
                </div>
              </div>';    
        echo '</div>';
    }

}
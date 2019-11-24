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

		$repeater->add_control(
			'eael_event_title',
			[
				'label'         => __( 'Title', 'essential-addons-elementor' ),
				'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
            ]
        );

        $repeater->add_control(
			'eael_event_description',
			[
				'label' => __( 'Description', 'essential-addons-elementor' ),
                'type'  => Controls_Manager::TEXTAREA,
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
        
        $this->end_controls_section();

    }


	protected function render( ) {
        $settings = $this->get_settings_for_display();
        $events = $settings['eael_event_items'];
        echo '<div class="eael-event-calendar-wrapper" style="padding:20px;">';
        if($events):
            $data = array();
            $i=0;
            foreach($events as $event):
                //echo $eaelEventTitle =  . '<br>';
                //echo $dueDate =  . '<br>';
                $data[] = array(
                                    'id'    => $i,
                                    'title' => $event["eael_event_title"],
                                    'start' => $event["eael_event_start_date"],
                                    'end'   => $event["eael_event_start_date"]
                                );
                $i++;
            endforeach;
        endif;
        echo '<div id="calendar" class="calendar2"
                data-events="' . htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') . '"></div>';
        echo '</div>';
    }

}
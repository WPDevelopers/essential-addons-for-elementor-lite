<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Controls_Manager;
use \Elementor\Scheme_Typography;

trait Extender
{
    /**
     * @since  3.8.2
     * @param $source
     *
     * @return array
     */
    public function event_calendar_source( $source ){

        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        if (is_plugin_active('the-events-calendar/the-events-calendar.php')) {
            $source['the_events_calendar'] = __('The Events Calendar', 'essential-addons-for-elementor-lite');
        }

        return $source;
    }

    /**
     * @since  3.8.2
     * @param $obj Event Calendar Widget object
     */
    public function event_calendar_source_control( $obj ){

        $obj->start_controls_section(
            'eael_event_the_events_calendar',
            [
                'label' => __('The Event Calendar', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'eael_event_calendar_type' => 'the_events_calendar',
                ],
            ]
        );

        $obj->add_control(
            'eael_the_events_calendar_fetch',
            [
                'label'     => __('Get Events', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SELECT,
                'label_block' => true,
                'default'   => ['all'],
                'options'   => [
                    'all' => __('All', 'essential-addons-for-elementor-lite'),
                    'date_range' => __('Date Range', 'essential-addons-for-elementor-lite'),
                ],
                'render_type' => 'none',
            ]
        );

        $obj->add_control(
            'eael_the_events_calendar_start_date',
            [
                'label' => __('Start Date', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', current_time('timestamp', 0)),
                'condition' => [
                    'eael_the_events_calendar_fetch' => 'date_range',
                ],
            ]
        );

        $obj->add_control(
            'eael_the_events_calendar_end_date',
            [
                'label' => __('End Date', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DATE_TIME,
                'default' => date('Y-m-d H:i', strtotime("+6 months", current_time('timestamp', 0))),
                'condition' => [
                    'eael_the_events_calendar_fetch' => 'date_range',
                ]
            ]
        );

        $obj->add_control(
            'eael_the_events_calendar_category',
            [
                'label'     => __('Event Category', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SELECT2,
                'multiple'  => true,
                'label_block' => true,
                'default'   => [],
                'options'   => $this->eael_get_tags(['taxonomy'=>'tribe_events_cat','hide_empty' => false]),
            ]
        );

        $obj->add_control(
            'eael_the_events_calendar_max_result',
            [
                'label' => __('Max Result', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 200,
                'default' => 20,
            ]
        );

        $obj->end_controls_section();
    }
}
<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Controls_Manager;

trait Extender
{
    /**
     * @since  3.8.2
     * @param $source
     *
     * @return array
     */
    public function eael_event_calendar_source($source)
    {

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
    public function eael_event_calendar_source_control($obj)
    {
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
                'label' => __('Get Events', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'default' => ['all'],
                'options' => [
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
                ],
            ]
        );

        $obj->add_control(
            'eael_the_events_calendar_category',
            [
                'label' => __('Event Category', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'label_block' => true,
                'default' => [],
                'options' => $this->eael_get_tags(['taxonomy' => 'tribe_events_cat', 'hide_empty' => false]),
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

    public function eael_event_calendar_event_data($data, $settings)
    {
        if ($settings['eael_event_calendar_type'] == 'google') {
            $data = $this->get_google_calendar_events($settings);
        } else if ($settings['eael_event_calendar_type'] == 'the_events_calendar') {
            $data = $this->get_the_events_calendar_events($settings);
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
    public function get_google_calendar_events($settings)
    {

        if (empty($settings['eael_event_google_api_key']) && empty($settings['eael_event_calendar_id'])) {
            return [];
        }

        $calendar_id = urlencode($settings['eael_event_calendar_id']);
        $base_url = "https://www.googleapis.com/calendar/v3/calendars/{$calendar_id}/events";

        $start_date = strtotime($settings['eael_google_calendar_start_date']);
        $end_date = strtotime($settings['eael_google_calendar_end_date']);

        $arg = [
            'key' => $settings['eael_event_google_api_key'],
            'maxResults' => $settings['eael_google_calendar_max_result'],
            'timeMin' => urlencode(date('c', $start_date)),
            '$calendar_id' => urlencode($settings['eael_event_calendar_id']),
        ];

        if (!empty($end_date) && $end_date > $start_date) {
            $arg['timeMax'] = urlencode(date('c', $end_date));
        }

        $transient_key = 'eael_google_calendar_' . md5(implode('', $arg));
        $calendar_data = get_transient($transient_key);

        if (!empty($calendar_data)) {
            return $calendar_data;
        }

        $data = wp_remote_retrieve_body(wp_remote_get(add_query_arg($arg, $base_url)));

        if (is_wp_error($data)) {
            return [];
        }

        $data = json_decode($data);
        if (isset($data->items)) {
            $calendar_data = [];
            foreach ($data->items as $key => $item) {

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
                    'id' => ++$key,
                    'title' => $item->summary,
                    'description' => isset($item->description) ? $item->description : '',
                    'start' => $ev_start_date,
                    'end' => $ev_end_date,
                    'borderColor' => '#6231FF',
                    'textColor' => $settings['eael_event_global_text_color'],
                    'color' => $settings['eael_event_global_bg_color'],
                    'url' => $item->htmlLink,
                    'allDay' => $all_day,
                    'external' => 'on',
                    'nofollow' => 'on',
                ];
            }

            set_transient($transient_key, $calendar_data, 1 * HOUR_IN_SECONDS);
        }

        return $calendar_data;
    }

    /**
     * @since  3.8.0
     * @param $settings
     *
     * @return array
     */
    public function get_the_events_calendar_events($settings)
    {

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
            $arg['tax_query'] = [['taxonomy' => 'tribe_events_cat', 'field' => 'id', 'terms' => $settings['eael_the_events_calendar_category']]];
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
                'id' => ++$key,
                'title' => $event->post_title,
                'description' => $event->post_content,
                'start' => tribe_get_start_date($event->ID, true, $date_format),
                'end' => tribe_get_end_date($event->ID, true, $date_format),
                'borderColor' => '#6231FF',
                'textColor' => $settings['eael_event_global_text_color'],
                'color' => $settings['eael_event_global_bg_color'],
                'url' => get_the_permalink($event->ID),
                'allDay' => $all_day,
                'external' => 'on',
                'nofollow' => 'on',
            ];
        }
        return $calendar_data;
    }
}

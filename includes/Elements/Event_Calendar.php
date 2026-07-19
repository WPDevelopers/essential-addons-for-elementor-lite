<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper;

class Event_Calendar extends Widget_Base {



	public function get_name() {
		return 'eael-event-calendar';
	}

	public function get_style_depends() {
		return array(
			'font-awesome-5-all',
			'font-awesome-4-shim',
		);
	}

	public function get_title() {
		return esc_html__( 'Event Calendar', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-event-calendar';
	}

	public function get_categories() {
		return array( 'essential-addons-elementor' );
	}

	public function get_keywords() {
		return array(
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
			'essential addons',
		);
	}

	public function get_script_depends() {
		return array( 'moment' );
	}

	public function has_widget_inner_wrapper(): bool {
		return ! Helper::eael_e_optimized_markup();
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/event-calendar/';
	}

	protected function register_controls() {
		/**
		 * -------------------------------------------
		 * Events
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_event_section',
			array(
				'label' => __( 'Events', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'eael_event_calendar_type',
			array(
				'label'   => __( 'Source', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => apply_filters(
					'eael/controls/event-calendar/source',
					array(
						'manual'              => __( 'Manual', 'essential-addons-for-elementor-lite' ),
						'google'              => __( 'Google', 'essential-addons-for-elementor-lite' ),
						'the_events_calendar' => __( 'The Events Calendar', 'essential-addons-for-elementor-lite' ),

					)
				),
				'default' => 'manual',
			)
		);

		$this->add_control(
			'eael_event_display_layout',
			array(
				'label'   => __( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'calendar' => __( 'Calendar', 'essential-addons-for-elementor-lite' ),
					'table'    => __( 'Table', 'essential-addons-for-elementor-lite' ),

				),
				'default' => 'calendar',
			)
		);

		if ( ! apply_filters( 'eael/is_plugin_active', 'the-events-calendar/the-events-calendar.php' ) ) {
			$this->add_control(
				'eael_the_event_calendar_warning_text',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __(
						'<strong>The Events Calendar</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=the-events-calendar&tab=search&type=term" target="_blank">The Events Calendar</a> first.',
						'essential-addons-for-elementor-lite'
					),
					'content_classes' => 'eael-warning',
					'condition'       => array(
						'eael_event_calendar_type' => 'the_events_calendar',
					),
				)
			);
		}

		if ( ! apply_filters( 'eael/pro_enabled', false ) ) {
			$this->add_control(
				'eael_event_calendar_pro_enable_warning',
				array(
					'label'     => sprintf( '<a target="_blank" href="https://wpdeveloper.com/upgrade/ea-pro">%s</a>', esc_html__( 'Only Available in Pro Version!', 'essential-addons-for-elementor-lite' ) ),
					'type'      => Controls_Manager::RAW_HTML,
					'condition' => array(
						'eael_event_calendar_type' => array( 'eventon' ),
					),
				)
			);
		}

		do_action( 'eael/event-calendar/activation-notice', $this );

		$repeater = new Repeater();
		$repeater->start_controls_tabs( 'eael_event_content_tabs' );

		$repeater->start_controls_tab(
			'eaelec_event_info_tab',
			array(
				'label' => __( 'General', 'essential-addons-for-elementor-lite' ),
			)
		);

		$repeater->add_control(
			'eael_event_title',
			array(
				'label'       => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => true,
				'ai'          => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'eael_event_link',
			array(
				'label'         => __( 'Event Link', 'essential-addons-for-elementor-lite' ),
				'type'          => Controls_Manager::URL,
				'dynamic'       => array( 'active' => true ),
				'placeholder'   => __( 'https://example.com', 'essential-addons-for-elementor-lite' ),
				'show_external' => true,
			)
		);

		$repeater->add_control(
			'eael_event_redirection',
			array(
				'label'        => __( 'Redirect to Event Link', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'return_value' => 'yes',
				'description'  => __( 'The popup will not appear and you will be redirected to the Event Link page instead.', 'essential-addons-for-elementor-lite' ),
			)
		);

		$repeater->add_control(
			'eael_event_all_day',
			array(
				'label'        => __( 'All Day', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'eael_event_start_date',
			array(
				'label'     => __( 'Start Date', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::DATE_TIME,
				'condition' => array(
					'eael_event_all_day' => '',
				),
			)
		);

		$repeater->add_control(
			'eael_event_end_date',
			array(
				'label'     => __( 'End Date', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::DATE_TIME,
				'condition' => array(
					'eael_event_all_day' => '',
				),
			)
		);

		$repeater->add_control(
			'eael_event_start_date_allday',
			array(
				'label'          => __( 'Start Date', 'essential-addons-for-elementor-lite' ),
				'type'           => Controls_Manager::DATE_TIME,
				'picker_options' => array( 'enableTime' => false ),
				'condition'      => array(
					'eael_event_all_day' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'eael_event_end_date_allday',
			array(
				'label'          => __( 'End Date', 'essential-addons-for-elementor-lite' ),
				'type'           => Controls_Manager::DATE_TIME,
				'picker_options' => array( 'enableTime' => false ),
				'condition'      => array(
					'eael_event_all_day' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'eael_event_bg_color',
			array(
				'label'   => __( 'Event Background Color', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#5725ff',
			)
		);

		$repeater->add_control(
			'eael_event_text_color',
			array(
				'label'   => __( 'Event Text Color', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#ffffff',
			)
		);

		$repeater->add_control(
			'eael_event_border_color',
			array(
				'label'     => __( 'Popup Ribbon Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#E8E6ED',
				'condition' => array(
					'eael_event_redirection!' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'eael_event_location',
			array(
				'label'       => __( 'Location', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'eael_event_category',
			array(
				'label'       => __( 'Category', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'label_block' => true,
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'eaelec_event_content_tab',
			array(
				'label'     => __( 'Content', 'essential-addons-for-elementor-lite' ),
				'condition' => array(
					'eael_event_redirection!' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'eael_event_description',
			array(
				'label' => __( 'Description', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::WYSIWYG,
			)
		);

		$repeater->end_controls_tab();

		$this->add_control(
			'eael_event_items',
			array(
				'label'       => __( 'Event', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array( 'eael_event_title' => 'Event Title' ),
				),
				'title_field' => '{{ eael_event_title }}',
				'condition'   => array(
					'eael_event_calendar_type' => 'manual',
				),
			)
		);

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'eael_event_google_calendar',
			array(
				'label'     => __( 'Google Calendar', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'eael_event_calendar_type' => 'google',
				),
			)
		);

		$this->add_control(
			'eael_event_google_api_key',
			array(
				'label'       => __( 'API Key', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => '<a href="https://essential-addons.com/elementor/docs/google-api-key/" class="eael-btn" target="_blank">' . __( 'Get API Key', 'essential-addons-for-elementor-lite' ) . '</a>',
				'ai'          => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_id',
			array(
				'label'       => __( 'Calendar ID', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => '<a href="https://essential-addons.com/elementor/docs/google-calendar-id/" class="eael-btn" target="_blank">' . __( 'Get google calendar ID', 'essential-addons-for-elementor-lite' ) . '</a>',
				'ai'          => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'eael_google_calendar_start_date',
			array(
				'label'   => __( 'Start Date', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::DATE_TIME,
				'default' => wp_date( 'Y-m-d H:i', current_time( 'timestamp', 0 ) ),
			)
		);

		$this->add_control(
			'eael_google_calendar_end_date',
			array(
				'label'   => __( 'End Date', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::DATE_TIME,
				'default' => wp_date( 'Y-m-d H:i', strtotime( '+6 months', current_time( 'timestamp', 0 ) ) ),
			)
		);

		$this->add_control(
			'eael_google_calendar_max_result',
			array(
				'label'   => __( 'Max Result', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'default' => 100,
			)
		);

		$this->end_controls_section();

		//the events calendar
		if ( apply_filters( 'eael/is_plugin_active', 'the-events-calendar/the-events-calendar.php' ) ) {
			$this->start_controls_section(
				'eael_event_the_events_calendar',
				array(
					'label'     => __( 'The Event Calendar', 'essential-addons-for-elementor-lite' ),
					'tab'       => Controls_Manager::TAB_CONTENT,
					'condition' => array(
						'eael_event_calendar_type' => 'the_events_calendar',
					),
				)
			);

			$this->add_control(
				'eael_the_events_calendar_fetch',
				array(
					'label'       => __( 'Get Events', 'essential-addons-for-elementor-lite' ),
					'type'        => Controls_Manager::SELECT,
					'label_block' => true,
					'default'     => array( 'all' ),
					'options'     => array(
						'all'        => __( 'All', 'essential-addons-for-elementor-lite' ),
						'date_range' => __( 'Date Range', 'essential-addons-for-elementor-lite' ),
					),
					'render_type' => 'none',
				)
			);

			$this->add_control(
				'eael_the_events_calendar_start_date',
				array(
					'label'     => __( 'Start Date', 'essential-addons-for-elementor-lite' ),
					'type'      => Controls_Manager::DATE_TIME,
					'default'   => wp_date( 'Y-m-d H:i', current_time( 'timestamp', 0 ) ),
					'condition' => array(
						'eael_the_events_calendar_fetch' => 'date_range',
					),
				)
			);

			$this->add_control(
				'eael_the_events_calendar_end_date',
				array(
					'label'     => __( 'End Date', 'essential-addons-for-elementor-lite' ),
					'type'      => Controls_Manager::DATE_TIME,
					'default'   => wp_date( 'Y-m-d H:i', strtotime( '+6 months', current_time( 'timestamp', 0 ) ) ),
					'condition' => array(
						'eael_the_events_calendar_fetch' => 'date_range',
					),
				)
			);

			$this->add_control(
				'eael_the_events_calendar_category',
				array(
					'label'       => __( 'Event Category', 'essential-addons-for-elementor-lite' ),
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'label_block' => true,
					'default'     => array(),
					'options'     => Helper::get_tags_list(
						array(
							'taxonomy'   => 'tribe_events_cat',
							'hide_empty' => false,
						)
					),
				)
			);

			$this->add_control(
				'eael_the_events_calendar_max_result',
				array(
					'label'   => __( 'Max Result', 'essential-addons-for-elementor-lite' ),
					'type'    => Controls_Manager::NUMBER,
					'min'     => 1,
					'default' => 100,
				)
			);

			$this->end_controls_section();
		}

		do_action( 'eael/event-calendar/source/control', $this );

		$this->start_controls_section(
			'eael_event_calendar_section',
			array(
				'label'     => __( 'Calendar', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'eael_event_display_layout' => 'calendar',
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_language',
			array(
				'label'   => __( 'Language', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'af'    => 'Afrikaans',
					'sq'    => 'Albanian',
					'hy-am' => 'Armenian',
					'ar'    => 'Arabic',
					'az'    => 'Azerbaijani',
					'eu'    => 'Basque',
					'bn'    => 'Bengali',
					'bs'    => 'Bosnian',
					'bg'    => 'Bulgarian',
					'ca'    => 'Catalan',
					'zh-cn' => 'Chinese',
					'zh-tw' => 'Chinese-tw',
					'hr'    => 'Croatian',
					'cs'    => 'Czech',
					'da'    => 'Danish',
					'nl'    => 'Dutch',
					'en'    => 'English',
					'et'    => 'Estonian',
					'fi'    => 'Finnish',
					'fr'    => 'French',
					'gl'    => 'Galician',
					'ka'    => 'Georgian',
					'de'    => 'German',
					'el'    => 'Greek (Modern)',
					'he'    => 'Hebrew',
					'hi'    => 'Hindi',
					'hu'    => 'Hungarian',
					'is'    => 'Icelandic',
					'io'    => 'Ido',
					'id'    => 'Indonesian',
					'it'    => 'Italian',
					'ja'    => 'Japanese',
					'kk'    => 'Kazakh',
					'ko'    => 'Korean',
					'lv'    => 'Latvian',
					'lb'    => 'Letzeburgesch',
					'lt'    => 'Lithuanian',
					'lu'    => 'Luba-Katanga',
					'mk'    => 'Macedonian',
					'mg'    => 'Malagasy',
					'ms'    => 'Malay',
					'ro'    => 'Moldovan, Moldavian, Romanian',
					'nb'    => 'Norwegian Bokmål',
					'nn'    => 'Norwegian Nynorsk',
					'fa'    => 'Persian',
					'pl'    => 'Polish',
					'pt'    => 'Portuguese',
					'ru'    => 'Russian',
					'sr'    => 'Serbian',
					'sk'    => 'Slovak',
					'sl'    => 'Slovenian',
					'es'    => 'Spanish',
					'sv'    => 'Swedish',
					'tr'    => 'Turkish',
					'uk'    => 'Ukrainian',
					'vi'    => 'Vietnamese',
				),
				'default' => 'en',
			)
		);

		$this->add_control(
			'eael_event_time_format',
			array(
				'label'        => __( '24-Hour Time Format', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'eael_event_calendar_default_view',
			array(
				'label'   => __( 'Default View', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'timeGridDay'  => __( 'Day', 'essential-addons-for-elementor-lite' ),
					'timeGridWeek' => __( 'Week', 'essential-addons-for-elementor-lite' ),
					'dayGridMonth' => __( 'Month', 'essential-addons-for-elementor-lite' ),
					'listMonth'    => __( 'List', 'essential-addons-for-elementor-lite' ),
				),
				'default' => 'dayGridMonth',
			)
		);

		$this->add_control(
			'eael_event_default_date_type',
			array(
				'label'   => __( 'Start Date', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'current' => __( 'Current Date', 'essential-addons-for-elementor-lite' ),
					'custom'  => __( 'Custom Date', 'essential-addons-for-elementor-lite' ),
				),
				'default' => 'custom',
			)
		);

		$default_date = wp_date( 'Y-m-d' );
		$this->add_control(
			'eael_event_calendar_default_date',
			array(
				'label'          => '',
				'type'           => Controls_Manager::DATE_TIME,
				'label_block'    => true,
				'picker_options' => array(
					'enableTime' => false,
					'dateFormat' => 'Y-m-d',
				),
				'default'        => $default_date,
				'condition'      => array(
					'eael_event_default_date_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_first_day',
			array(
				'label'   => __( 'First Day of Week', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'0' => __( 'Sunday', 'essential-addons-for-elementor-lite' ),
					'1' => __( 'Monday', 'essential-addons-for-elementor-lite' ),
					'2' => __( 'Tuesday', 'essential-addons-for-elementor-lite' ),
					'3' => __( 'Wednesday', 'essential-addons-for-elementor-lite' ),
					'4' => __( 'Thursday', 'essential-addons-for-elementor-lite' ),
					'5' => __( 'Friday', 'essential-addons-for-elementor-lite' ),
					'6' => __( 'Saturday', 'essential-addons-for-elementor-lite' ),
				),
				'default' => '0',
			)
		);

		$this->add_control(
			'eael_event_details_link_hide',
			array(
				'label'        => __( 'Hide Event Details Link', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'return_value' => 'yes',
				'description'  => __( 'Hide Event Details link in event popup', 'essential-addons-for-elementor-lite' ),
			)
		);

		$this->add_control(
			'eael_old_events_hide',
			array(
				'label'       => __( 'Hide Old Events', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'default'     => '',
				'options'     => array(
					''      => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
					'yes'   => esc_html__( 'Till Current Date', 'essential-addons-for-elementor-lite' ),
					'start' => esc_html__( 'Till Start Date', 'essential-addons-for-elementor-lite' ),
				),
			)
		);

		$this->add_control(
			'eael_event_multi_days_event_day_count',
			array(
				'label'        => __( 'Multi-Days Event Day Count', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'return_value' => 'yes',
				'description'  => __( 'Extra text "Day Count/Event Total Days" will be added in the event title', 'essential-addons-for-elementor-lite' ),
				'condition'    => array(
					'eael_event_calendar_default_view' => 'listMonth',
					'eael_event_calendar_type'         => 'google',
				),
			)
		);

		$this->add_control(
			'eael_event_details_text',
			array(
				'label'     => __( 'Event Details Text', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Event Details', 'essential-addons-for-elementor-lite' ),
				'condition' => array(
					'eael_event_details_link_hide!' => 'yes',
				),
				'ai'        => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'eael_event_location_display',
			array(
				'label'        => __( 'Show Event Location', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'return_value' => 'yes',
				'default'      => 'no',
				'description'  => __( 'Display event location information in the popup modal', 'essential-addons-for-elementor-lite' ),
				'condition'    => array(
					'eael_event_calendar_type' => 'google',
				),
			)
		);

		$this->add_control(
			'eael_event_limit',
			array(
				'label'       => __( 'Event Limit', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '3',
				'min'         => '2',
				'description' => __( 'Limit the number of events displayed on a day. The rest will show up in a popover.', 'essential-addons-for-elementor-lite' ),
			)
		);

		$this->add_control(
			'eael_event_calendar_show_search',
			array(
				'label'        => esc_html__( 'Show Search', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'eael_event_calendar_search_placeholder',
			array(
				'label'       => esc_html__( 'Search Placeholder', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Search', 'essential-addons-for-elementor-lite' ),
				'default'     => esc_html__( 'Search', 'essential-addons-for-elementor-lite' ),
				'condition'   => array(
					'eael_event_calendar_show_search' => 'yes',
				),
			)
		);

		if ( apply_filters( 'eael/is_plugin_active', 'eventON/eventon.php' ) && apply_filters( 'eael/pro_enabled', false ) ) {
			$this->add_control(
				'eael_event_on_featured_color',
				array(
					'label'     => __( 'Featured Event Color', 'essential-addons-for-elementor-lite' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ffcb55',
					'condition' => array(
						'eael_event_calendar_type' => 'eventon',
					),
				)
			);
		}

		$this->add_control(
			'eael_event_random_bg_color',
			array(
				'label'        => __( 'Random Background Color', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
				'conditions'   => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'eael_event_calendar_type',
							'operator' => '=',
							'value'    => 'google',
						),
						array(
							'name'     => 'eael_event_calendar_type',
							'operator' => '=',
							'value'    => 'the_events_calendar',
						),
					),
				),
			)
		);

		$this->add_control(
			'eael_event_global_bg_color',
			array(
				'label'      => __( 'Event Background Color', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#5725ff',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'eael_event_calendar_type',
									'operator' => '=',
									'value'    => 'google',
								),
								array(
									'name'     => 'eael_event_random_bg_color',
									'operator' => '=',
									'value'    => '',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'eael_event_calendar_type',
									'operator' => '=',
									'value'    => 'the_events_calendar',
								),
								array(
									'name'     => 'eael_event_random_bg_color',
									'operator' => '=',
									'value'    => '',
								),
							),
						),
						array(
							'name'     => 'eael_event_calendar_type',
							'operator' => '=',
							'value'    => 'eventon',
						),
					),
				),
			)
		);

		$this->add_control(
			'eael_event_global_text_color',
			array(
				'label'      => __( 'Event Text Color', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#ffffff',
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'eael_event_calendar_type',
									'operator' => '=',
									'value'    => 'google',
								),
								array(
									'name'     => 'eael_event_random_bg_color',
									'operator' => '=',
									'value'    => '',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'eael_event_calendar_type',
									'operator' => '=',
									'value'    => 'the_events_calendar',
								),
								array(
									'name'     => 'eael_event_random_bg_color',
									'operator' => '=',
									'value'    => '',
								),
							),
						),
						array(
							'name'     => 'eael_event_calendar_type',
							'operator' => '=',
							'value'    => 'eventon',
						),
					),
				),
			)
		);

		$this->add_control(
			'eael_calendar_column_heading_formats',
			array(
				'label'     => __( 'Calendar Table Heading Date Format', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'eael_calendar_column_heading_month',
			array(
				'label'   => esc_html__( 'Month View', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''     => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
					'dddd' => esc_html__( 'Full Day Name', 'essential-addons-for-elementor-lite' ),
				),
			)
		);

		$this->add_control(
			'eael_calendar_column_heading_week',
			array(
				'label'   => esc_html__( 'Week View', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => apply_filters(
					'eael_calendar_column_heading_week_date_formats',
					array(
						''         => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
						'ddd Do'   => wp_date( 'M j' ) . 'th',
						'dddd Do'  => wp_date( 'l j' ) . 'th',
						'dddd D/Y' => wp_date( 'l j/Y' ),
						'ddd D/Y'  => wp_date( 'M j/Y' ),
					)
				),
			)
		);

		$this->add_control(
			'_heading_popup_settings',
			array(
				'label'     => esc_html__( 'Popup', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'eael_event_popup_date_format',
			array(
				'label'   => esc_html__( 'Date Format', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'MMM Do',
				'options' => array(
					'MMM Do'     => wp_date( 'M jS' ),     // Sep 29th
					'MMMM Do'    => wp_date( 'F jS' ),     // September 29th
					'Do MMM'     => wp_date( 'jS M' ),     // 29th Sep
					'Do MMMM'    => wp_date( 'jS F' ),     // 29th September
					'MM-DD-YYYY' => wp_date( 'm-d-Y' ),    // 09-29-2024
					'YYYY-DD-MM' => wp_date( 'Y-d-m' ),    // 2024-29-09
					'YYYY-MM-DD' => wp_date( 'Y-m-d' ),    // 2024-09-29
					'DD/MM/YYYY' => wp_date( 'd/m/Y' ),    // 29/09/2024
					'MM/DD/YYYY' => wp_date( 'm/d/Y' ),    // 09/29/2024
					'YYYY/MM/DD' => wp_date( 'Y/m/d' ),    // 2024/09/29
					'DD.MM.YYYY' => wp_date( 'd.m.Y' ),    // 29/09/2024
					'MM.DD.YYYY' => wp_date( 'm.d.Y' ),    // 09/29/2024
					'YYYY.MM.DD' => wp_date( 'Y.m.d' ),    // 2024/09/29
					'D-MMM-YYYY' => wp_date( 'j-M-Y' ),    // 29-Sep-2024
					'MMMM YYYY'  => wp_date( 'F Y' ),      // September 2024
					'MMM YYYY'   => wp_date( 'M Y' ),      // Sep 2024
				),
			)
		);

		$this->add_control(
			'eael_event_global_popup_ribbon_color',
			array(
				'label'   => __( 'Ribbon Color', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#10ecab',
			)
		);

		$this->add_control(
			'eael_event_show_thumbnail',
			array(
				'label'        => esc_html__( 'Thumbnail', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'eael_event_calendar_type' => 'the_events_calendar',
				),
			)
		);

		$this->add_control(
			'eael_event_thumbnail_position',
			array(
				'label'     => esc_html__( 'Position', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'header'     => array(
						'title' => esc_html__( 'Header', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-v-align-top',
					),
					'body-left'  => array(
						'title' => esc_html__( 'Body Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-left',
					),
					'body-right' => array(
						'title' => esc_html__( 'Body Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-right',
					),
					'body-bg'    => array(
						'title' => esc_html__( 'Body Background', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'background' => array(
						'title' => esc_html__( 'Full Background', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-v-align-stretch',
					),
				),
				'default'   => 'body-right',
				'toggle'    => false,
				'condition' => array(
					'eael_event_show_thumbnail' => 'yes',
					'eael_event_calendar_type'  => 'the_events_calendar',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Table Layout content
		 */
		$this->start_controls_section(
			'eael_event_calendar_table_layout_section',
			array(
				'label'     => __( 'Calendar', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'eael_event_display_layout' => 'table',
				),
			)
		);

		$this->add_control(
			'eael_table_ec_default_date_type',
			array(
				'label'   => esc_html__( 'Start Date', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'current',
				'options' => array(
					'current' => esc_html__( 'Current Day', 'essential-addons-for-elementor-lite' ),
					'custom'  => esc_html__( 'Custom Date', 'essential-addons-for-elementor-lite' ),
				),
			)
		);

		$default_date = wp_date( 'Y-m-d' );
		$this->add_control(
			'eael_table_event_calendar_default_date',
			array(
				'label'          => '',
				'type'           => Controls_Manager::DATE_TIME,
				'label_block'    => true,
				'picker_options' => array(
					'enableTime' => false,
					'dateFormat' => 'Y-m-d',
				),
				'default'        => $default_date,
				'condition'      => array(
					'eael_table_ec_default_date_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'eael_ec_show_search',
			array(
				'label'        => esc_html__( 'Search', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'eael_ec_search_placeholder',
			array(
				'label'       => esc_html__( 'Placeholder', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => array( 'active' => true ),
				'placeholder' => esc_html__( 'Search', 'essential-addons-for-elementor-lite' ),
				'default'     => esc_html__( 'Search', 'essential-addons-for-elementor-lite' ),
				'condition'   => array(
					'eael_ec_show_search' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_ec_search_label',
			array(
				'label'       => esc_html__( 'Label', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => array( 'active' => true ),
				'placeholder' => esc_html__( 'Search', 'essential-addons-for-elementor-lite' ),
				'condition'   => array(
					'eael_ec_show_search' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_ec_search_align',
			array(
				'label'     => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'right',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .ea-ec-search-wrap' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'eael_ec_show_search' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_ec_show_title',
			array(
				'label'        => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'eael_ec_title_label',
			array(
				'label'       => esc_html__( 'Label', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => array( 'active' => true ),
				'placeholder' => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'default'     => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'condition'   => array(
					'eael_ec_show_title' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_ec_event_details_link',
			array(
				'label'        => esc_html__( 'Event Details Link', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'eael_ec_show_title'        => 'yes',
					'eael_event_calendar_type!' => 'manual',
				),
			)
		);

		$this->add_control(
			'eael_ec_title_on_new_tab',
			array(
				'label'        => esc_html__( 'Open in new Window', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'eael_ec_show_title'         => 'yes',
					'eael_ec_event_details_link' => 'yes',
					'eael_event_calendar_type!'  => 'manual',
				),
			)
		);

		$this->add_control(
			'eael_ec_show_description',
			array(
				'label'        => esc_html__( 'Description', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'eael_ec_desc_label',
			array(
				'label'       => esc_html__( 'Label', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => array( 'active' => true ),
				'placeholder' => esc_html__( 'Description', 'essential-addons-for-elementor-lite' ),
				'default'     => esc_html__( 'Description', 'essential-addons-for-elementor-lite' ),
				'condition'   => array(
					'eael_ec_show_description' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_ec_description_limit',
			array(
				'label'       => esc_html__( 'Word Count', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'placeholder' => 20,
				'default'     => 20,
				'min'         => 0,
				'condition'   => array(
					'eael_ec_show_description' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_ec_desc_see_more',
			array(
				'label'       => esc_html__( 'Expansion Indicator', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => array( 'active' => true ),
				'placeholder' => esc_html__( '...', 'essential-addons-for-elementor-lite' ),
				'default'     => esc_html__( '... see more', 'essential-addons-for-elementor-lite' ),
				'conditions'  => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'eael_ec_show_description',
							'operator' => ' === ',
							'value'    => 'yes',
						),
						array(
							'name'     => 'eael_ec_description_limit',
							'operator' => '>',
							'value'    => 0,
						),
					),
				),
			)
		);

		$this->add_control(
			'eael_ec_desc_see_more_link',
			array(
				'label'        => esc_html__( 'Linkable', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'description'  => esc_html__( 'By clicking on the expansion indicator will redirect to the event details link.', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'conditions'   => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'eael_ec_show_description',
							'operator' => ' === ',
							'value'    => 'yes',
						),
						array(
							'name'     => 'eael_ec_description_limit',
							'operator' => '>',
							'value'    => 0,
						),
					),
				),
			)
		);

		$this->add_control(
			'eael_ec_show_date',
			array(
				'label'        => esc_html__( 'Date', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'eael_ec_date_label',
			array(
				'label'       => esc_html__( 'Label', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'ai'          => array( 'active' => true ),
				'placeholder' => esc_html__( 'Date', 'essential-addons-for-elementor-lite' ),
				'default'     => esc_html__( 'Date', 'essential-addons-for-elementor-lite' ),
				'condition'   => array(
					'eael_ec_show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_ec_date_time_format',
			array(
				'label'     => esc_html__( 'Visibility', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => array(
					'date-time' => esc_html__( 'Date Time', 'essential-addons-for-elementor-lite' ),
					'time-date' => esc_html__( 'Time Date', 'essential-addons-for-elementor-lite' ),
					'date'      => esc_html__( 'Only Date', 'essential-addons-for-elementor-lite' ),
					'time'      => esc_html__( 'Only Time', 'essential-addons-for-elementor-lite' ),
				),
				'condition' => array(
					'eael_ec_show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_ec_enable_custom_date_format',
			array(
				'label'        => esc_html__( 'Custom Date Format', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition'    => array(
					'eael_ec_show_date'         => 'yes',
					'eael_ec_date_time_format!' => 'time',
				),
			)
		);

		$this->add_control(
			'eael_ec_custom_date_format',
			array(
				'label'       => esc_html__( 'Date Format', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Y.m.d',
				'placeholder' => 'm/d/Y',
				'ai'          => array( 'active' => true ),
				'condition'   => array(
					'eael_ec_show_date'                 => 'yes',
					'eael_ec_date_time_format!'         => 'time',
					'eael_ec_enable_custom_date_format' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_ec_date_format',
			array(
				'label'     => esc_html__( 'Date Format', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'jS F Y',
				'options'   => array(
					'F j, Y'    => wp_date( 'F j, Y' ),                   // January 1, 2022
					'Y-m-d'     => wp_date( 'Y-m-d' ),
					'd-m-Y'     => wp_date( 'd-m-y' ),
					'm-d-Y'     => wp_date( 'm-d-y' ),
					'Y.m.d'     => wp_date( 'Y.m.d' ),
					'd.m.Y'     => wp_date( 'd.m.y' ),
					'm.d.Y'     => wp_date( 'm.d.y' ),
					'm/d/Y'     => wp_date( 'm/d/Y' ),                    // 01/01/2022
					'd/m/Y'     => wp_date( 'd/m/Y' ),                    // 01/01/2022
					'Y/m/d'     => wp_date( 'Y/m/d' ),                    // 2022/01/01
					'M j, Y'    => wp_date( 'M j, Y' ),                   // Jan 1, 2022
					'jS F Y'    => wp_date( 'jS F Y' ),                   // 1st January 2022
					'D, M j, Y' => wp_date( 'D, M j, Y' ),                // Sat, Jan 1, 2022
					'l, F j, Y' => wp_date( 'l, F j, Y' ),                // Saturday, January 1, 2022
					'j F, Y'    => wp_date( 'j F, Y' ),                   // 1 January, 2022
					'l, j F, Y' => wp_date( 'l, j F, Y' ),                // Saturday, 1 January, 2022
					'D, d M Y'  => wp_date( 'D, d M Y' ),                 // Sat, 01 Jan 2022
					'l, d-M-Y'  => wp_date( 'l, d-M-Y' ),                 // Saturday, 01-Jan-2022
				),
				'condition' => array(
					'eael_ec_show_date'                  => 'yes',
					'eael_ec_date_time_format!'          => 'time',
					'eael_ec_enable_custom_date_format!' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_ec_time_format',
			array(
				'label'     => esc_html__( 'Time Format', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'g:i A',
				'options'   => array(             // 00:00
					'g:i a'   => wp_date( 'g:i a' ),            // 12:00 am/pm
					'g:i:s a' => wp_date( 'g:i:s a' ),            // 12:00 am/pm
					'g:i A'   => wp_date( 'g:i A' ),            // 12:00 AM/PM
					'g:i:s A' => wp_date( 'g:i:s A' ),            // 12:00 AM/PM
					'g:i:s'   => wp_date( 'g:i:s' ),            // 12:00 AM/PM
					'H:i'     => wp_date( 'H:i' ) . esc_html__( ' (24 Hours)', 'essential-addons-for-elementor-lite' ),
					'H:i:s'   => wp_date( 'H:i:s' ) . esc_html__( ' (24 Hours)', 'essential-addons-for-elementor-lite' ),
				),
				'condition' => array(
					'eael_ec_show_date'         => 'yes',
					'eael_ec_date_time_format!' => 'date',
				),
			)
		);

		$this->add_control(
			'eael_ec_date_time_separator',
			array(
				'label'     => esc_html__( 'Date Time Separator', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'ai'        => array( 'active' => true ),
				'default'   => esc_html__( ', ', 'essential-addons-for-elementor-lite' ),
				'condition' => array(
					'eael_ec_show_date'         => 'yes',
					'eael_ec_date_time_format!' => array( 'date', 'time' ),
				),
			)
		);

		$this->add_control(
			'eael_ec_date_to_date_separator',
			array(
				'label'     => esc_html__( 'Event Time Separator', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::TEXT,
				'ai'        => array( 'active' => true ),
				'default'   => esc_html__( '-', 'essential-addons-for-elementor-lite' ),
				'condition' => array(
					'eael_ec_show_date' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_ec_show_pagination',
			array(
				'label'        => esc_html__( 'Pagination', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'eael_ec_item_per_page',
			array(
				'label'     => esc_html__( 'Item Per Page', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'default'   => 10,
				'condition' => array(
					'eael_ec_show_pagination' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_ec_pagination_align',
			array(
				'label'     => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-pagination' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'eael_ec_show_pagination' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Data cache setting
		 */
		$this->start_controls_section(
			'eael_event_calendar_data_cache',
			array(
				'label'     => __( 'Data Cache Setting', 'essential-addons-for-elementor-lite' ),
				'condition' => array(
					'eael_event_calendar_type!' => 'manual',
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_data_cache_limit',
			array(
				'label'       => __( 'Data Cache Time', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'default'     => 60,
				'description' => __( 'Cache expiration time (Minutes)', 'essential-addons-for-elementor-lite' ),
			)
		);

		$this->end_controls_section();

		/**
		 * Table Layout design Search
		 */
		$this->start_controls_section(
			'eael_event_calendar_search_styling',
			array(
				'label'     => __( 'Search Input', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'table',
					'eael_ec_show_search'       => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_search_width',
			array(
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 200,
				),
				'selectors'  => array(
					'{{WRAPPER}} .ea-ec-search-wrap input' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eael_event_calendar_search_typography',
				'selector' => '{{WRAPPER}} .ea-ec-search-wrap input',
			)
		);

		$this->add_control(
			'eael_event_calendar_search_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ea-ec-search-wrap input' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'eael_event_calendar_search_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .ea-ec-search-wrap input',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'eael_event_calendar_search_border',
				'selector' => '{{WRAPPER}} .ea-ec-search-wrap input',
			)
		);

		$this->add_control(
			'eael_event_calendar_search_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .ea-ec-search-wrap input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_search_padding',
			array(
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .ea-ec-search-wrap input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_search_margin',
			array(
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .ea-ec-search-wrap input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Table Layout design Search
		 */
		$this->start_controls_section(
			'eael_event_calendar_search_label_styling',
			array(
				'label'     => __( 'Search Label', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'table',
					'eael_ec_show_search'       => 'yes',
					'eael_ec_search_label!'     => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eael_event_calendar_search_label_typography',
				'selector' => '{{WRAPPER}} .ea-ec-search-wrap label.ea-ec-search-label:not(.sr-only)',
			)
		);

		$this->add_control(
			'eael_event_calendar_search_label_color',
			array(
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ea-ec-search-wrap label.ea-ec-search-label:not(.sr-only)' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_search_label_padding',
			array(
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .ea-ec-search-wrap label.ea-ec-search-label:not(.sr-only)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_search_label_margin',
			array(
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .ea-ec-search-wrap label.ea-ec-search-label:not(.sr-only)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Table Layout design
		 */
		$this->start_controls_section(
			'eael_event_calendar_table_layout_styling',
			array(
				'label'     => __( 'Table', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'table',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'eael_ec_table_background',
				'types'    => array( 'classic', 'gradient', 'video' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-table',
			)
		);

		$this->add_control(
			'eael_ec_table_margin',
			array(
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Table Layout design header
		 */
		$this->start_controls_section(
			'eael_event_calendar_table_header_styling',
			array(
				'label'     => __( 'Header', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'table',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eael_ec_table_header_typography',
				'selector' => '{{WRAPPER}} .eael-event-calendar-table thead tr th',
			)
		);

		$this->add_control(
			'eael_ec_table_header_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#181818',
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-table thead tr th' => 'color: {{VALUE}}',
					'{{WRAPPER}} .eael-event-calendar-table thead tr th a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'eael_ec_table_header_background',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-table thead tr th',
			)
		);

		$this->add_control(
			'eael_ec_table_header_padding',
			array(
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-table thead tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'eael_ec_table_header',
				'selector' => '{{WRAPPER}} .eael-event-calendar-table thead tr th',
			)
		);

		$this->end_controls_section();

		/**
		 * Table Layout design
		 */
		$this->start_controls_section(
			'eael_event_calendar_table_Body_styling',
			array(
				'label'     => __( 'Body', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'table',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eael_ec_table_body_typography',
				'selector' => '{{WRAPPER}} .eael-event-calendar-table tbody tr td',
			)
		);

		$this->add_control(
			'eael_ec_table_body_style_notice',
			array(
				'label'           => '',
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Note: Please reset colors from the Event section before applying styles here. ', 'essential-addons-for-elementor-lite' ),
				'separator'       => 'before',
				'content_classes' => 'eael-warning',
				'condition'       => array(
					'eael_event_calendar_type' => 'manual',
				),
			)
		);

		$this->start_controls_tabs(
			'eael_ec_table_body_style_tabs'
		);

		$this->start_controls_tab(
			'eael_ec_table_body_style_even_row',
			array(
				'label' => esc_html__( 'Row Even', 'essential-addons-for-elementor-lite' ),
			)
		);

		$this->add_control(
			'eael_ec_table_body_text_color_even',
			array(
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-table tbody tr:nth-child(even) td' => 'color: {{VALUE}}',
					'{{WRAPPER}} .eael-event-calendar-table tbody tr:nth-child(even) td a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'eael_ec_table_body_content_linked_color',
			array(
				'label'      => esc_html__( 'Linked Text', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-table tbody tr:nth-child(even) td.eael-ec-event-description a' => 'color: {{VALUE}} !important',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'eael_ec_show_description',
							'operator' => ' === ',
							'value'    => 'yes',
						),
						array(
							'name'     => 'eael_ec_description_limit',
							'operator' => '<',
							'value'    => 1,
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'eael_ec_table_body_background_even',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-table tbody tr:nth-child(even) td',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'eael_ec_table_body_style_odd_row',
			array(
				'label' => esc_html__( 'Row Odd', 'essential-addons-for-elementor-lite' ),
			)
		);

		$this->add_control(
			'eael_ec_table_body_text_color_odd',
			array(
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-table tbody tr:nth-child(odd) td' => 'color: {{VALUE}}',
					'{{WRAPPER}} .eael-event-calendar-table tbody tr:nth-child(odd) td a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'eael_ec_table_body_content_linked_color_odd',
			array(
				'label'      => esc_html__( 'Linked Text', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-table tbody tr:nth-child(odd) td.eael-ec-event-description a' => 'color: {{VALUE}} !important',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'eael_ec_show_description',
							'operator' => ' === ',
							'value'    => 'yes',
						),
						array(
							'name'     => 'eael_ec_description_limit',
							'operator' => '<',
							'value'    => 1,
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'eael_ec_table_body_background_odd',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-table tbody tr:nth-child(odd) td',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'eael_ec_table_body_padding',
			array(
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-table tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'eael_ec_table_body',
				'selector' => '{{WRAPPER}} .eael-event-calendar-table tbody tr td',
			)
		);

		$this->add_control(
			'eael_ec_table_body_see_more',
			array(
				'label'      => esc_html__( 'Expansion Indicator', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'eael_ec_show_description',
							'operator' => ' === ',
							'value'    => 'yes',
						),
						array(
							'name'     => 'eael_ec_description_limit',
							'operator' => '>',
							'value'    => 0,
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'       => 'eael_ec_table_body_see_more_typography',
				'selector'   => '{{WRAPPER}} .eael-event-calendar-table tbody tr td .eael-see-more',
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'eael_ec_show_description',
							'operator' => ' === ',
							'value'    => 'yes',
						),
						array(
							'name'     => 'eael_ec_description_limit',
							'operator' => '>',
							'value'    => 0,
						),
					),
				),
			)
		);

		$this->add_control(
			'eael_ec_table_body_see_more_color',
			array(
				'label'      => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-table tbody tr td .eael-see-more' => 'color: {{VALUE}}',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'eael_ec_show_description',
							'operator' => ' === ',
							'value'    => 'yes',
						),
						array(
							'name'     => 'eael_ec_description_limit',
							'operator' => '>',
							'value'    => 0,
						),
					),
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Table Layout pagination styling
		 */
		$this->start_controls_section(
			'eael_event_calendar_table_pagination_styling',
			array(
				'label'     => __( 'Pagination', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'table',
					'eael_ec_show_pagination'   => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eael_event_calendar_table_pagination_typography',
				'selector' => '{{WRAPPER}} .eael-event-calendar-pagination a,{{WRAPPER}} .eael-event-calendar-pagination span',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'eael_event_calendar_table_pagination_border',
				'selector' => '{{WRAPPER}} .eael-event-calendar-pagination a, {{WRAPPER}} .eael-event-calendar-pagination span',
				'exclude'  => array( 'color' ),
			)
		);

		$this->add_control(
			'eael_event_calendar_table_pagination_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-event-calendar-pagination span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'eael_event_calendar_table_pagination_border_border!' => array( 'none', 'default' ),
				),
			)
		);

		$this->start_controls_tabs(
			'eael_event_calendar_table_pagination_styles_tabs'
		);

		$this->start_controls_tab(
			'eael_event_calendar_table_pagination_style_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
			)
		);
		$this->add_control(
			'eael_event_calendar_table_pagination_color',
			array(
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-pagination a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .eael-event-calendar-pagination span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'eael_event_calendar_table_pagination_background',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-pagination a, {{WRAPPER}} .eael-event-calendar-pagination span',
			)
		);

		$this->add_control(
			'eael_event_calendar_table_pagination_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-pagination a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .eael-event-calendar-pagination span' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'eael_event_calendar_table_pagination_border_border!' => array( 'none', 'default' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'eael_event_calendar_table_pagination_style_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
			)
		);
		$this->add_control(
			'eael_event_calendar_table_pagination_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-pagination a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'eael_event_calendar_table_pagination_background_hover',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-pagination a:hover',
			)
		);

		$this->add_control(
			'eael_event_calendar_table_pagination_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-pagination a:hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'eael_event_calendar_table_pagination_border_border!' => array( 'none', 'default' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'eael_event_calendar_table_pagination_style_active_tab',
			array(
				'label' => esc_html__( 'Active', 'essential-addons-for-elementor-lite' ),
			)
		);

		$this->add_control(
			'eael_event_calendar_table_pagination_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-pagination a.active' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'eael_event_calendar_table_pagination_background_active',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-pagination a.active',
			)
		);

		$this->add_control(
			'eael_event_calendar_table_pagination_border_color_active',
			array(
				'label'     => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-pagination a.active' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'eael_event_calendar_table_pagination_border_border!' => array( 'none', 'default' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'eael_event_calendar_table_pagination_padding',
			array(
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-event-calendar-pagination span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->add_control(
			'eael_event_calendar_table_pagination_margin',
			array(
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-pagination a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-event-calendar-pagination span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .eael-event-calendar-pagination a, {{WRAPPER}} .eael-event-calendar-pagination span',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab Started
		 */
		$this->start_controls_section(
			'eael_event_calendar_interface',
			array(
				'label'     => __( 'Calendar', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'calendar',
				),
			)
		);

		$this->add_control(
			'calendar_background_color',
			array(
				'label'     => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-wrapper' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'calendar_border_color',
			array(
				'label'     => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#CFCFDA',
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc td' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .eael-event-calendar-wrapper hr.fc-divider' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc th' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc-view  td.fc-today' => 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc-view  table thead:first-child tr:first-child td' => 'border-top-color: {{VALUE}} !important;',
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc-view.fc-listWeek-view' => 'border-color: {{VALUE}} !important;',
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc-view.fc-listMonth-view' => 'border-color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'eael_calendar_box_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-wrapper .fc-view-harness',
			)
		);

		$this->add_responsive_control(
			'calendar_inside',
			array(
				'label'      => esc_html__( 'Inside Space', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'calendar_outside',
			array(
				'label'      => esc_html__( 'Outside Space', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'calendar_title_heading',
			array(
				'label' => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'calendar_title_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .fc-toolbar h2',
			)
		);

		$this->add_control(
			'calendar_title_color',
			array(
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fc-toolbar h2' => 'color: {{VALUE}};',
				),
			)
		);

		// Buttons style
		$this->add_control(
			'buttons_style_heading',
			array(
				'label'     => __( 'Button', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'calendar_button_typography_normal',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-wrapper .fc-toolbar.fc-header-toolbar .fc-button',
			)
		);

		$this->start_controls_tabs( 'calendar_buttons_style' );

		// Normal
		$this->start_controls_tab(
			'button_normal_state',
			array(
				'label' => __( 'Normal', 'essential-addons-for-elementor-lite' ),
			)
		);

		$this->add_control(
			'button_color_normal',
			array(
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:not(.fc-button-active)' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_normal',
			array(
				'label'     => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:not(.fc-button-active)' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border_normal',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:not(.fc-button-active)',
			)
		);

		$this->add_responsive_control(
			'button_border_radius_normal',
			array(
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:not(.fc-button-active)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'buttons_margin',
			array(
				'label'      => esc_html__( 'Space', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:not(.fc-button-active)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);
		// Buttons style

		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab(
			'button_hover_state',
			array(
				'label' => __( 'Hover', 'essential-addons-for-elementor-lite' ),
			)
		);

		$this->add_control(
			'button_color_hover',
			array(
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_hover',
			array(
				'label'     => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border_hover',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:hover',
			)
		);

		$this->add_responsive_control(
			'button_border_radius_hover',
			array(
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->end_controls_tab();

		// Active
		$this->start_controls_tab(
			'button_active_state',
			array(
				'label' => __( 'Active', 'essential-addons-for-elementor-lite' ),
			)
		);

		$this->add_control(
			'button_color_active',
			array(
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button.fc-button-active' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background_active',
			array(
				'label'     => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button.fc-button-active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'button_border_active',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button.fc-button-active',
			)
		);

		$this->add_responsive_control(
			'button_border_radius_active',
			array(
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button.fc-button-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'buttons_margin_active',
			array(
				'label'      => esc_html__( 'Space', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fc-toolbar.fc-header-toolbar .fc-button.fc-button-active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
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
			array(
				'label'     => __( 'Day', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'calendar',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'days_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .fc-col-header-cell a, {{WRAPPER}} .fc-list-sticky .fc-list-day th a',
			)
		);

		$this->add_control(
			'days_color',
			array(
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fc-col-header-cell a' => 'color: {{VALUE}};',

				),
			)
		);

		$this->add_responsive_control(
			'days_position_alignment',
			array(
				'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .fc-col-header-cell' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'days_background',
				'label'    => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .fc-col-header-cell, {{WRAPPER}} table thead .fc-timegrid-axis',
				'exclude'  => array(
					'image',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Tab: Style => Panel: Time
		 * -----------------------------------------------
		 */
		$this->start_controls_section(
			'calendar_week_time',
			array(
				'label'     => __( 'Time', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'calendar',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'time_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .fc-timegrid-slot,{{WRAPPER}} .fc-timegrid-axis',
			)
		);

		$this->add_control(
			'time_color',
			array(
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fc-timegrid-slot' => 'color: {{VALUE}};',
					'{{WRAPPER}} .fc-timegrid-axis' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'date_styles',
			array(
				'label'     => __( 'Date', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'calendar',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'date_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .fc-daygrid-day-number',
			)
		);

		$this->add_control(
			'date_color',
			array(
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fc-daygrid-day-number' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'date_number_background',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Number Background', 'essential-addons-for-elementor-lite' ),
				'selectors' => array(
					'{{WRAPPER}} .fc-daygrid-day-top' => 'background: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'date_background',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'selectors' => array(
					'{{WRAPPER}} table tbody .fc-day'      => 'background: {{VALUE}} !important',
					'{{WRAPPER}} table tbody .fc-timegrid-axis' => 'background: {{VALUE}} !important',
					'{{WRAPPER}} table tbody .fc-timegrid-slot' => 'background: {{VALUE}} !important',
					'{{WRAPPER}} .fc-unthemed td.fc-today' => 'background: {{VALUE}} !important',
				),
			)
		);

		$this->add_responsive_control(
			'date_position_alignment',
			array(
				'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .fc-daygrid-day-top' => 'display: block;text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'date_padding',
			array(
				'label'      => esc_html__( 'Inside Space', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fc-daygrid-day-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'date_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fc-daygrid-day-top' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'date_margin',
			array(
				'label'      => esc_html__( 'Outside Space', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fc-daygrid-day-top' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'today_date_setting',
			array(
				'label'     => __( 'Today Date', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'today_date_color',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'default'   => '#1111e1',
				'selectors' => array(
					'{{WRAPPER}} .fc-day-today .fc-daygrid-day-top a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'today_date_background',
			array(
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'selectors' => array(
					'{{WRAPPER}} table tbody tr .fc-day-today' => 'background: {{VALUE}} !important',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Tab: Style => Panel: List
		 * -----------------------------------------------
		 */
		$this->start_controls_section(
			'calendar_list_view',
			array(
				'label'     => __( 'List view', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'calendar',
				),
			)
		);

		$this->add_control(
			'eael_list_view_header_heading',
			array(
				'label' => __( 'Header', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'list_row_header_color',
			array(
				'label'     => __( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc-list .fc-list-table .fc-list-day .fc-list-day-cushion a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'list_header_background_color',
			array(
				'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f1edf8',
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc-list .fc-list-table .fc-list-day .fc-list-day-cushion' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'eael_list_view_body_heading',
			array(
				'label' => __( 'Body', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'list_element_text_color',
			array(
				'label'     => __( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc-list-event .fc-list-event-time' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc-list-event .fc-list-event-title a' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_control(
			'list_element_even_color',
			array(
				'label'     => __( 'Even row Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc-list-event:nth-child(even)' => 'background-color: {{VALUE}} !important;',
				),
			)
		);
		$this->add_control(
			'list_element_odd_color',
			array(
				'label'     => __( 'Odd row Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc-list-event:nth-child(odd) td' => 'background-color: {{VALUE}} !important;',

				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'eaelec_event_section',
			array(
				'label'     => __( 'Events', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'calendar',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eael_event_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .fc-event .fc-event-title,{{WRAPPER}} .fc-event .fc-event-time,{{WRAPPER}} .fc-list-event-time,{{WRAPPER}} .fc-list-event-title',
			)
		);

		$this->add_responsive_control(
			'day_event_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fc-event' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'day_event_padding',
			array(
				'label'      => esc_html__( 'Inside Space', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' . 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fc-event' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'day_event_margin',
			array(
				'label'      => esc_html__( 'Outside Space', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' . 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fc-event' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'eael_more_event',
			array(
				'label'     => esc_html__( 'More Event Text', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eael_more_event_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .fc-daygrid-day-bottom .fc-daygrid-more-link',
			)
		);

		$this->add_responsive_control(
			'eael_event_calendar_event_container_height',
			array(
				'label'       => esc_html__( 'Cell Height', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px', '%', 'em', 'rem' ),
				'separator'   => 'before',
				'range'       => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'description' => __( 'This is only effective for month view.', 'essential-addons-for-elementor-lite' ),
				'selectors'   => array(
					'{{WRAPPER}} .eael-event-calendar-wrapper .fc-daygrid.fc-dayGridMonth-view.fc-view td.fc-daygrid-day' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'event_popup',
			array(
				'label'     => __( 'Event Popup', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout' => 'calendar',
				),
			)
		);

		$this->add_control(
			'event_popup_title_heading',
			array(
				'label' => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'event_popup_title_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eaelec-modal-header .eael-ec-modal-title',
			)
		);

		$this->add_control(
			'event_popup_title_color',
			array(
				'label'     => __( 'Title Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eaelec-modal-header .eael-ec-modal-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'event_popup_date_heading',
			array(
				'label'     => __( 'Date', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'event_popup_date_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eaelec-modal-header > span.eaelec-event-popup-date',
			)
		);

		$this->add_control(
			'event_popup_date_color',
			array(
				'label'     => __( 'Date Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eaelec-modal-header span.eaelec-event-date-start' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eaelec-modal-header span.eaelec-event-date-end' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'event_popup_date_icon',
			array(
				'label'     => __( 'Date Icon', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'event_popup_date_icon_size',
			array(
				'label'      => __( 'Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .eaelec-modal-header span.eaelec-event-date-start i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'event_popup_date_icon_color',
			array(
				'label'     => __( 'Icon Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eaelec-modal-header span.eaelec-event-date-start i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'event_popup_location_heading',
			array(
				'label'     => __( 'Location', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'event_popup_location_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eaelec-modal-header .eaelec-event-popup-location',
			)
		);

		$this->add_control(
			'event_popup_location_color',
			array(
				'label'     => __( 'Location Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eaelec-modal-header .eaelec-event-popup-location' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'event_popup_location_icon',
			array(
				'label'     => __( 'Location Icon', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'event_popup_location_icon_size',
			array(
				'label'      => __( 'Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .eaelec-modal-header .eaelec-event-popup-location i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'event_popup_location_icon_color',
			array(
				'label'     => __( 'Icon Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eaelec-modal-header .eaelec-event-popup-location i' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'event_popup_content_heading',
			array(
				'label'     => __( 'Content', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'event_popup_content_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eaelec-modal-body, {{WRAPPER}} .eaelec-modal-body *',
			)
		);

		$this->add_control(
			'event_popup_content_color',
			array(
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eaelec-modal-body'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .eaelec-modal-body *' => 'color: {{VALUE}};',
				),
				'default'   => '#555',
			)
		);

		$this->add_control(
			'_heading_thumbnail_style',
			array(
				'label'     => esc_html__( 'Thumbnail', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'eael_event_show_thumbnail'     => 'yes',
					'eael_event_thumbnail_position' => array( 'body-left', 'body-right' ),
					'eael_event_calendar_type'      => 'the_events_calendar',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'event_popup_thumbnail_border',
				'selector'  => '{{WRAPPER}} .eaelec-modal-body-img',
				'condition' => array(
					'eael_event_show_thumbnail'     => 'yes',
					'eael_event_thumbnail_position' => array( 'body-left', 'body-right' ),
					'eael_event_calendar_type'      => 'the_events_calendar',
				),
			)
		);

		$this->add_responsive_control(
			'event_popup_thumbnail_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eaelec-modal-body-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'eael_event_show_thumbnail'     => 'yes',
					'eael_event_thumbnail_position' => array( 'body-left', 'body-right' ),
					'eael_event_calendar_type'      => 'the_events_calendar',
				),
			)
		);

		$this->add_control(
			'event_popup_close_button_style',
			array(
				'label'     => __( ' Close Button', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'close_button_icon_size',
			array(
				'label'      => __( 'Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .eaelec-modal-close > span' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'close_button_size',
			array(
				'label'      => __( 'Button Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .eaelec-modal-close' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'close_button_color',
			array(
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eaelec-modal-close > span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'close_button_background',
				'label'    => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'types'    => array(
					'classic',
					'gradient',
				),
				'selector' => '{{WRAPPER}} .eael-event-calendar-wrapper .eaelec-modal-close',
				'exclude'  => array(
					'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'close_button_border',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-wrapper .eaelec-modal-close',
			)
		);

		$this->add_responsive_control(
			'close_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-wrapper .eaelec-modal-close' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'close_button_box_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-wrapper .eaelec-modal-close',
			)
		);

		$this->add_control(
			'event_popup_ext_link_heading',
			array(
				'label'     => __( 'External Link', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'event_popup_ext_link_typography',
				'label'    => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eaelec-modal-footer .eaelec-event-details-link',
			)
		);

		$this->add_control(
			'event_popup_ext_link_color',
			array(
				'label'     => __( 'Date Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eaelec-modal-footer .eaelec-event-details-link' => 'color: {{VALUE}};',
				),
				'separator' => 'after',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'event_popup_border',
				'label'    => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eaelec-modal .eaelec-modal-content',
			)
		);

		$this->add_responsive_control(
			'event_popup_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .eaelec-modal .eaelec-modal-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eaelec-modal .eaelec-modal-content .eaelec-modal-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'after',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'event_popup_background',
				'label'    => __( 'Background', 'essential-addons-for-elementor-lite' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .eaelec-modal .eaelec-modal-content, {{WRAPPER}} .eaelec-modal .eaelec-modal-content .eaelec-modal-overlay',
				'exclude'  => array(
					'image',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'event_popup_box_shadow',
				'label'    => __( 'Box Shadow', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eaelec-modal .eaelec-modal-content',
			)
		);

		$this->end_controls_section();

		/**
		 * Calendar Layout design Search
		 */
		$this->start_controls_section(
			'eael_event_calendar_search_calendar_input_styling',
			array(
				'label'     => __( 'Search Input', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout'       => 'calendar',
					'eael_event_calendar_show_search' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'eael_event_calendar_search_calendar_input_width',
			array(
				'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-wrapper .eael-event-calendar-search .eael-event-calendar-search-input' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eael_event_calendar_search_calendar_input_typography',
				'selector' => '{{WRAPPER}} .eael-event-calendar-search-input',
			)
		);

		$this->add_control(
			'eael_event_calendar_search_calendar_input_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-search-input' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_search_calendar_input_placeholder_color',
			array(
				'label'     => esc_html__( 'Placeholder Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-search-input::placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'eael_event_calendar_search_calendar_input_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-search-input',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'eael_event_calendar_search_calendar_input_border',
				'selector' => '{{WRAPPER}} .eael-event-calendar-search-input',
			)
		);

		$this->add_control(
			'eael_event_calendar_search_calendar_input_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-search-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'eael_event_calendar_search_calendar_input_box_shadow',
				'selector' => '{{WRAPPER}} .eael-event-calendar-search-input',
			)
		);

		$this->add_responsive_control(
			'eael_event_calendar_search_calendar_input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-search-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'eael_event_calendar_search_calendar_input_margin',
			array(
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-search' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Search Suggestions design Search
		 */
		$this->start_controls_section(
			'eael_event_calendar_search_suggestions_styling',
			array(
				'label'     => __( 'Search Suggestions', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'eael_event_display_layout'       => 'calendar',
					'eael_event_calendar_show_search' => 'yes',
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_suggestions_container_heading',
			array(
				'label' => esc_html__( 'Container', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'eael_event_calendar_suggestions_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-search-suggestions',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'eael_event_calendar_suggestions_border',
				'selector' => '{{WRAPPER}} .eael-event-calendar-search-suggestions',
			)
		);

		$this->add_control(
			'eael_event_calendar_suggestions_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-search-suggestions' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'eael_event_calendar_suggestions_box_shadow',
				'selector' => '{{WRAPPER}} .eael-event-calendar-search-suggestions',
			)
		);

		$this->add_control(
			'eael_event_calendar_suggestion_item_heading',
			array(
				'label'     => esc_html__( 'Suggestion Item', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eael_event_calendar_suggestion_title_typography',
				'label'    => esc_html__( 'Title Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-search-suggestions .suggestion-item .suggestion-title',
			)
		);

		$this->add_control(
			'eael_event_calendar_suggestion_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-search-suggestions .suggestion-item .suggestion-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'eael_event_calendar_suggestion_meta_typography',
				'label'    => esc_html__( 'Meta Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .eael-event-calendar-search-suggestions .suggestion-item .suggestion-meta',
			)
		);

		$this->add_control(
			'eael_event_calendar_suggestion_meta_color',
			array(
				'label'     => esc_html__( 'Meta Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-search-suggestions .suggestion-item .suggestion-meta' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_suggestion_item_hover_bg',
			array(
				'label'     => esc_html__( 'Hover Background color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eael-event-calendar-search-suggestions .suggestion-item:hover' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'eael_event_calendar_suggestion_item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .eael-event-calendar-search-suggestions .suggestion-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$settings = apply_filters( 'eael/event-calendar/settings', $settings );

		if ( in_array( $settings['eael_event_calendar_type'], array( 'eventon' ) ) ) {
			$data = apply_filters( 'eael/event-calendar/integration', array(), $settings );
		} elseif ( $settings['eael_event_calendar_type'] == 'google' ) {
			$data = $this->get_google_calendar_events( $settings );
		} elseif ( $settings['eael_event_calendar_type'] == 'the_events_calendar' ) {
			$data = $this->get_the_events_calendar_events( $settings );
		} else {
			$data = $this->get_manual_calendar_events( $settings );
		}

		$data = apply_filters( 'eael/event-calendar/events', $data, $settings );

		$local                      = $settings['eael_event_calendar_language'];
		$default_view               = $settings['eael_event_calendar_default_view'];
		$default_date               = $settings['eael_event_default_date_type'] === 'custom' ? $settings['eael_event_calendar_default_date'] : wp_date( 'Y-m-d' );
		$time_format                = $settings['eael_event_time_format'];
		$event_limit                = ! empty( $settings['eael_event_limit'] ) ? intval( $settings['eael_event_limit'] ) : 2;
		$multi_days_event_day_count = ! empty( $settings['eael_event_multi_days_event_day_count'] ) && 'yes' === $settings['eael_event_multi_days_event_day_count'] ? 1 : 0;

		$translate_date = array(
			'today'    => __( 'Today', 'essential-addons-for-elementor-lite' ),
			'tomorrow' => __( 'Tomorrow', 'essential-addons-for-elementor-lite' ),
		);

		echo '<div class="eael-event-calendar-wrapper layout-' . esc_attr( $settings['eael_event_display_layout'] ) . '">';

		if ( $settings['eael_event_display_layout'] === 'calendar' && $settings['eael_event_calendar_show_search'] === 'yes' ) {
			?>
			<div class="eael-event-calendar-search-wrap eael-event-calendar-search">
				<input type="search" id="eael-event-calendar-search-input-<?php echo esc_attr( $this->get_id() ); ?>" placeholder="<?php echo esc_html( $settings['eael_event_calendar_search_placeholder'] ); ?>" class="eael-event-calendar-search-input">
				<div class="eael-event-calendar-search-suggestions"></div>
			</div>
			<?php
		}

		if ( $settings['eael_event_display_layout'] === 'calendar' ) {
			echo '<div id="eael-event-calendar-' . esc_attr( $this->get_id() ) . '" class="eael-event-calendar-cls"
            data-cal_id = "' . esc_attr( $this->get_id() ) . '"
            data-locale = "' . esc_attr( $local ) . '"
            data-translate = "' . esc_attr( htmlspecialchars( json_encode( $translate_date ), ENT_QUOTES, 'UTF-8' ) ) . '"
            data-defaultview = "' . esc_attr( $default_view ) . '"
            data-defaultdate = "' . esc_attr( $default_date ) . '"
            data-time_format = "' . esc_attr( $time_format ) . '"
            data-event_limit = "' . esc_attr( $event_limit ) . '"
            data-popup_date_format = "' . esc_attr( $settings['eael_event_popup_date_format'] ) . '"
            data-multidays_event_day_count= "' . esc_attr( $multi_days_event_day_count ) . '"
            data-monthColumnHeaderFormat = "' . esc_attr( $settings['eael_calendar_column_heading_month'] ) . '"
            data-weekColumnHeaderFormat = "' . esc_attr( $settings['eael_calendar_column_heading_week'] ) . '"
            data-hideDetailsLink= "' . esc_attr( $settings['eael_event_details_link_hide'] ) . '"
            data-detailsButtonText = "' . esc_attr( wp_kses( $settings['eael_event_details_text'], Helper::eael_allowed_tags() ) ) . '"
            data-location-display = "' . esc_attr( $settings['eael_event_location_display'] ) . '"
            data-events="' . esc_attr( htmlspecialchars( json_encode( eael_neutralize_shortcodes( $data ) ), ENT_QUOTES, 'UTF-8' ) ) . '"
            data-thumbnail_position = "' . ( ! empty( $settings['eael_event_show_thumbnail'] ) ? esc_attr( $settings['eael_event_thumbnail_position'] ) : '' ) . '"
            data-first_day="' . esc_attr( $settings['eael_event_calendar_first_day'] ) . '"></div>';

            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $this->eaelec_load_event_details();
		} else {
			$this->eaelec_display_table( $data, $settings );
		}
		echo '</div>';
	}

	public function eaelec_display_table( $data, $settings ) {
		if ( $settings['eael_ec_show_search'] === 'yes' ) {
			$labeltext = ! empty( $settings['eael_ec_search_label'] ) ? $settings['eael_ec_search_label'] : '';
			?>
			<div class="ea-ec-search-wrap ea-ec-search-right">
				<label for="ea-ec-search-input-<?php echo esc_attr( $this->get_id() ); ?>" class="ea-ec-search-label<?php echo empty( $labeltext ) ? ' sr-only' : ''; ?>"><?php echo ! empty( $labeltext ) ? esc_html( $labeltext ) : esc_html__( 'Search', 'essential-addons-for-elementor-lite' ); ?></label>
				<input type="search" id="ea-ec-search-input-<?php echo esc_attr( $this->get_id() ); ?>" placeholder="<?php echo esc_html( $settings['eael_ec_search_placeholder'] ); ?>" class="eael-event-calendar-table-search">
			</div>
			<?php
		}
		$is_paginated  = $settings['eael_ec_show_pagination'] === 'yes';
		$item_per_page = $is_paginated && ! empty( $settings['eael_ec_item_per_page'] ) ? intval( $settings['eael_ec_item_per_page'] ) : 1;

		?>
		<table class="eael-event-calendar-table <?php echo $is_paginated ? 'ea-ec-table-paginated' : ''; ?> ea-ec-table-sortable" data-items-per-page="<?php echo esc_attr( $item_per_page ); ?>">
			<thead>
			<tr style="display: table-row;">
				<?php
				$thead_html = '';
				if ( $settings['eael_ec_show_title'] === 'yes' ) {
					$thead_html .= '<th>' . $settings['eael_ec_title_label'] . '</th>';
				}
				if ( $settings['eael_ec_show_description'] === 'yes' ) {
					$thead_html .= '<th>' . $settings['eael_ec_desc_label'] . '</th>';
				}
				if ( $settings['eael_ec_show_date'] === 'yes' ) {
					$thead_html .= '<th>' . $settings['eael_ec_date_label'] . '</th>';
				}
				if ( $thead_html ) {
					echo wp_kses( $thead_html, Helper::eael_allowed_tags() );
				}
				?>
			</tr>
			</thead>
			<tbody>
			<?php

			$item_count = 1;

			$date_format    = 'yes' === $settings['eael_ec_enable_custom_date_format'] && $settings['eael_ec_custom_date_format'] ? esc_html( $settings['eael_ec_custom_date_format'] ) : $settings['eael_ec_date_format'];
			$time_format    = $settings['eael_ec_time_format'];
			$time_separator = '';

			if ( $settings['eael_ec_date_time_separator'] ) {
				$letters                = str_split( $settings['eael_ec_date_time_separator'] );
				$lettersWithBackslashes = array_map(
					function ( $letter ) {
						return '\\' . $letter;
					},
					$letters
				);
				$time_separator         = implode( '', $lettersWithBackslashes );
			}

			if ( $settings['eael_ec_date_time_format'] === 'date-time' ) {
				$date_format = $date_format . $time_separator . $time_format;
			} elseif ( $settings['eael_ec_date_time_format'] === 'time-date' ) {
				$date_format = $time_format . $time_separator . $date_format;
			} elseif ( $settings['eael_ec_date_time_format'] === 'time' ) {
				$date_format = $time_format;
			}

			foreach ( $data as $event ) {
				$start        = wp_date( 'Y-m-d', strtotime( $event['start'] ) );
				$is_old_event = false;
				if ( 'current' === $settings['eael_table_ec_default_date_type'] ) {
					$is_old_event = $this->is_old_event( $start );
				} elseif ( 'custom' === $settings['eael_table_ec_default_date_type'] ) {
					$custom_date          = strtotime( $settings['eael_table_event_calendar_default_date'] );
					$start_date_timestamp = strtotime( $start );
					$is_old_event         = $start_date_timestamp < $custom_date;
				}

				if ( $is_old_event ) {
					continue;
				}

				$style     = $item_count >= $item_per_page ? 'style="display: none;"' : '';
				$row_style = '';
				if ( ! empty( $event['color'] ) ) {
					$row_style .= "background:{$event['color']};";
				}
				if ( ! empty( $event['textColor'] ) ) {
					$row_style .= "color:{$event['textColor']};";
				}

				$row_style = $row_style !== '' ? 'style="' . esc_attr( $row_style ) . '" ' : '';

				++$item_count;

                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '<tr ' . $style . ' >';
				$tr_inner_html = '';
				if ( $settings['eael_ec_show_title'] === 'yes' ) {
					if ( $settings['eael_ec_event_details_link'] === 'yes' && $event['url'] ) {
						$new_tab        = $settings['eael_ec_title_on_new_tab'] === 'yes' ? 'target="_blank"' : '';
						$event['title'] = sprintf( "<a href='%s' %s>%s</a>", esc_url( $event['url'] ), $new_tab, $event['title'] );
					}

					if ( $settings['eael_event_calendar_type'] === 'manual' && $event['url'] && $event['is_redirect'] === 'yes' ) {
						$this->add_link_attributes( 'eael_event_link_' . $item_count, $event['event_link'] );
						$event['title'] = '<a ' . $this->get_render_attribute_string( 'eael_event_link_' . $item_count ) . ' >' . $event['title'] . '</a>';
					}
					$tr_inner_html .= '<td class="eael-ec-event-title" ' . $row_style . '>' . $event['title'] . '</td>';
				}
				if ( $settings['eael_ec_show_description'] === 'yes' ) {
					$link = '';
					if ( $settings['eael_ec_desc_see_more_link'] === 'yes' && $event['url'] ) {
						$link = sprintf( " href='%s'", esc_url( $event['url'] ) );
					}
					if ( $settings['eael_ec_description_limit'] > 0 ) {
						$see_more          = sprintf( " <a %s class='eael-see-more'>%s</a>", $link, Helper::eael_wp_kses( $settings['eael_ec_desc_see_more'] ) );
						$event_description = wp_trim_words( $event['description'], $settings['eael_ec_description_limit'], $see_more );
					} else {
						$event_description = $event['description'];
					}
					$tr_inner_html .= '<td class="eael-ec-event-description" ' . $row_style . '>' . $event_description . '</td>';
				}
				if ( $settings['eael_ec_show_date'] === 'yes' ) {
					$start_timezone = isset( $event['start_timezone'] ) && '' !== $event['start_timezone'] ? new \DateTimeZone( $event['start_timezone'] ) : '';
					$end_timezone   = isset( $event['end_timezone'] ) && '' !== $event['end_timezone'] ? new \DateTimeZone( $event['end_timezone'] ) : $start_timezone;

					$start_time = strtotime( $event['start'] );
					$end_time   = strtotime( $event['end'] );
					if ( '' !== $start_timezone && '' !== $end_timezone ) {
						$start    = wp_date( $date_format, $start_time, $start_timezone );
						$end      = wp_date( $date_format, $end_time, $end_timezone );
						$same_day = wp_date( 'Ymd', $start_time, $start_timezone ) === wp_date( 'Ymd', $end_time, $end_timezone );
					} else {
						$start    = date_i18n( $date_format, $start_time );
						$end      = date_i18n( $date_format, $end_time );
						$same_day = wp_date( 'Ymd', $start_time ) === wp_date( 'Ymd', $end_time );
					}

					if ( $time_format && $same_day ) {
						$end = '' !== $start_timezone && '' !== $end_timezone ? wp_date( $time_format, $end_time, $end_timezone ) : date_i18n( $time_format, $end_time );
					} elseif ( ! $time_format && $same_day ) {
						$end = '';
					}

					$separator      = $end ? $settings['eael_ec_date_to_date_separator'] : '';
					$date           = sprintf( '<span class="hide">%s</span> %s %s %s', strtotime( $event['start'] ), $start, $separator, $end );
					$tr_inner_html .= '<td class="eael-ec-event-date" ' . $row_style . '>' . $date . '</td>';
				}
				if ( $tr_inner_html ) {
					echo eael_neutralize_shortcodes( wp_kses( $tr_inner_html, Helper::eael_allowed_tags() ) );
				}
				echo '</tr>';
			}
			?>
			</tbody>
		</table>

		<?php
		if ( $settings['eael_ec_show_pagination'] ) {
			echo '<div class="eael-event-calendar-pagination ea-ec-pagination-button"></div>';
		}
	}

	protected function eaelec_load_event_details() {
		$event_details_text = $this->get_settings( 'eael_event_details_text' );
		$html               = '<div id="eaelecModal" class="eaelec-modal eael-zoom-in">
            <div class="eaelec-modal-content">
                <div class="eaelec-modal-header">
                    <div class="eaelec-modal-close"><span><i class="fas fa-times"></i></span></div>
                    <h2 class="eael-ec-modal-title"></h2>
                    <span class="eaelec-event-date-start eaelec-event-popup-date"></span>
                    <span class="eaelec-event-date-end eaelec-event-popup-date"></span>
                    <span class="eaelec-event-location eaelec-event-popup-location" style="display: none;"></span>
                </div>
                <div class="eaelec-modal-body">
                    <p></p>
                </div>
                <div class="eaelec-modal-footer">
                    <a class="eaelec-event-details-link">' . esc_html( $event_details_text ) . '</a>
                </div>
            </div>
        </div>';

		return $html;
	}

	public function xssAttributes() {
		return array(
			'onclick',
			'onmouseover',
			'onmouseout',
			'onmousedown',
			'onmouseup',
			'ondblclick',
			'onmousemove',
			'onmouseenter',
			'onmouseleave',
			'onwheel',
			'onkeydown',
			'onkeypress',
			'onkeyup',
			'onsubmit',
			'onreset',
			'onfocus',
			'onblur',
			'onchange',
			'oninput',
			'onselect',
			'onload',
			'onunload',
			'onresize',
			'onscroll',
			'onbeforeunload',
			'onerror',
			'onhashchange',
			'onpagehide',
			'onpageshow',
			'onpopstate',
			'onstorage',
			'oncopy',
			'oncut',
			'onpaste',
			'onplay',
			'onpause',
			'onended',
			'onvolumechange',
			'onwaiting',
			'oncanplay',
			'oncanplaythrough',
			'oncuechange',
			'ondurationchange',
			'onemptied',
			'onloadeddata',
			'onloadedmetadata',
			'onloadstart',
			'onplaying',
			'onprogress',
			'onratechange',
			'onseeked',
			'onseeking',
			'onstalled',
			'onsuspend',
			'ontimeupdate',
			'ondrag',
			'ondragstart',
			'ondragend',
			'ondragover',
			'ondragenter',
			'ondragleave',
			'ondrop',
			'ontouchstart',
			'ontouchmove',
			'ontouchend',
			'ontouchcancel',
			'onfocusin',
			'onfocusout',
			'oncontextmenu',
			'onreadystatechange',
			'onvisibilitychange',
			'onshow',
			'onmessage',
			'onabort',
			'onafterprint',
			'onbeforeprint',
			'oninvalid',
			'ontoggle',
			'onanimationstart',
			'onanimationend',
			'onanimationiteration',
			'onoffline',
			'ononline',
		);
	}

	public function get_manual_calendar_events( $settings ) {
		$events = $settings['eael_event_items'];
		$data   = array();
		if ( $events ) {
			$i = 0;
			foreach ( $events as $event ) {

				if ( $event['eael_event_all_day'] == 'yes' ) {
					$start = ! empty( $event['eael_event_start_date_allday'] ) ? $event['eael_event_start_date_allday'] : wp_date( 'Y-m-d', current_time( 'timestamp', 0 ) );
					$_end  = ! empty( $event['eael_event_end_date_allday'] ) ? $event['eael_event_end_date_allday'] : wp_date( 'Y-m-d', current_time( 'timestamp', 0 ) );
					$end   = wp_date( 'Y-m-d', strtotime( '+1 days', strtotime( $_end ) ) );
				} else {
					$start = ! empty( $event['eael_event_start_date'] ) ? $event['eael_event_start_date'] : wp_date( 'Y-m-d', current_time( 'timestamp', 0 ) );
					$_end  = ! empty( $event['eael_event_end_date'] ) ? $event['eael_event_end_date'] : wp_date( 'Y-m-d', strtotime( '+59 minute', current_time( 'timestamp', 0 ) ) );
					$end   = gmdate( 'Y-m-d H:i', strtotime( $_end ) ) . ':01';
				}

				if ( ! empty( $settings['eael_old_events_hide'] ) && 'yes' === $settings['eael_old_events_hide'] ) {
					$is_old_event = $this->is_old_event( $start );
					if ( $is_old_event ) {
						continue;
					}
				}

				if ( $settings['eael_old_events_hide'] === 'start' ) {
					$default_date = $settings['eael_event_default_date_type'] === 'custom' ? $settings['eael_event_calendar_default_date'] : wp_date( 'Y-m-d' );
					$should_show  = $this->is_old_event( $start, $default_date );

					if ( $should_show ) {
						continue;
					}
				}

				$settings_eael_event_global_bg_color           = $this->fetch_color_or_global_color( $event, 'eael_event_bg_color' );
				$settings_eael_event_global_text_color         = $this->fetch_color_or_global_color( $event, 'eael_event_text_color' );
				$settings_eael_event_global_popup_ribbon_color = $this->fetch_color_or_global_color( $event, 'eael_event_border_color' );

				$_custom_attributes = Utils::parse_custom_attributes( $event['eael_event_link']['custom_attributes'] );
				$custom_attributes  = array();

				if ( $_custom_attributes ) {
					foreach ( $_custom_attributes as $key => $value ) {
						if ( $key ) {
							$custom_attributes[] = array(
								'key'   => sanitize_text_field( $key ),
								'value' => esc_attr( $value ),
							);
						}
					}
				}

				$data[] = array(
					'id'                => $i,
					'title'             => ! empty( $event['eael_event_title'] ) ? $event['eael_event_title'] : 'No Title',
					'description'       => $event['eael_event_description'],
					'start'             => $start,
					'end'               => $end,
					'borderColor'       => ! empty( $settings_eael_event_global_popup_ribbon_color ) ? $settings_eael_event_global_popup_ribbon_color : '#10ecab',
					'textColor'         => $settings_eael_event_global_text_color,
					'color'             => $settings_eael_event_global_bg_color,
					'url'               => esc_url_raw( $event['eael_event_link']['url'] ),
					'allDay'            => $event['eael_event_all_day'],
					'external'          => $event['eael_event_link']['is_external'],
					'nofollow'          => $event['eael_event_link']['nofollow'],
					'is_redirect'       => $event['eael_event_redirection'],
					'custom_attributes' => $custom_attributes,
					'location'          => sanitize_text_field( $event['eael_event_location'] ),
					'category'          => sanitize_text_field( $event['eael_event_category'] ),
					'event_link'        => $event['eael_event_link'],
				);

				++$i;
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
	public function get_google_calendar_events( $settings ) {

		if ( empty( $settings['eael_event_google_api_key'] ) && empty( $settings['eael_event_calendar_id'] ) ) {
			return array();
		}

		$calendar_id = urlencode( $settings['eael_event_calendar_id'] );
		$base_url    = "https://www.googleapis.com/calendar/v3/calendars/{$calendar_id}/events";

		$start_date = strtotime( $settings['eael_google_calendar_start_date'] );
		$end_date   = strtotime( $settings['eael_google_calendar_end_date'] );

		$arg = array(
			'key'          => $settings['eael_event_google_api_key'],
			'maxResults'   => $settings['eael_google_calendar_max_result'],
			'timeMin'      => urlencode( wp_date( 'c', $start_date ) ),
			'singleEvents' => 'true',
			'calendar_id'  => urlencode( $settings['eael_event_calendar_id'] ),
		);

		$transient_args = array(
			'key'          => $settings['eael_event_google_api_key'],
			'maxResults'   => $settings['eael_google_calendar_max_result'],
			'timeMin'      => urlencode( wp_date( 'Y-m-d H', $start_date ) ),
			'singleEvents' => 'true',
			'calendar_id'  => urlencode( $settings['eael_event_calendar_id'] ),
			'cache_time'   => $settings['eael_event_calendar_data_cache_limit'],
		);

		if ( ! empty( $end_date ) && $end_date > $start_date ) {
			$arg['timeMax']            = urlencode( wp_date( 'c', $end_date ) );
			$transient_args['timeMax'] = urlencode( wp_date( 'Y-m-d H', $end_date ) );
		}

		$transient_key = 'eael_google_calendar_' . md5( implode( '', $transient_args ) );
		$data          = get_transient( $transient_key );

		if ( isset( $arg['calendar_id'] ) ) {
			unset( $arg['calendar_id'] );
		}

		if ( empty( $data ) ) {
			$data        = wp_remote_retrieve_body( wp_remote_get( esc_url_raw( add_query_arg( $arg, $base_url ) ) ) );
			$check_error = json_decode( $data );

			if ( ! empty( $check_error->error ) ) {
				return array();
			}
			set_transient( $transient_key, $data, $settings['eael_event_calendar_data_cache_limit'] * MINUTE_IN_SECONDS );
		}

		$calendar_data        = array();
		$data                 = json_decode( $data );
		$random_colors        = $this->get_random_colors();
		$random_color_enabled = isset( $settings['eael_event_random_bg_color'] ) && 'yes' == $settings['eael_event_random_bg_color'];
		$random_color_index   = 0;

		if ( isset( $data->items ) ) {
			foreach ( $data->items as $key => $item ) {
				if ( $item->status !== 'confirmed' ) {
					//                    continue;
				}
				$all_day        = '';
				$start_timezone = isset( $item->start->timeZone ) ? $item->start->timeZone : '';
				$end_timezone   = isset( $item->end->timeZone ) ? $item->end->timeZone : $start_timezone;

				if ( isset( $item->start->date ) ) {
					$all_day       = 'yes';
					$ev_start_date = $item->start->date;
					$ev_end_date   = $item->end->date;
				} else {
					$ev_start_date = $item->start->dateTime;
					$ev_end_date   = $item->end->dateTime;
				}

				if ( $random_color_enabled ) {
					$random_color_index = $random_color_index > count( $random_colors ) - 2 ? 0 : $random_color_index + 1;

					$settings_eael_event_global_bg_color   = $random_colors[ $random_color_index ];
					$settings_eael_event_global_text_color = '#ffffff';
				} else {
					$settings_eael_event_global_bg_color   = $this->fetch_color_or_global_color( $settings, 'eael_event_global_bg_color' );
					$settings_eael_event_global_text_color = $this->fetch_color_or_global_color( $settings, 'eael_event_global_text_color' );
				}

				$settings_eael_event_global_popup_ribbon_color = $this->fetch_color_or_global_color( $settings, 'eael_event_global_popup_ribbon_color' );

				if ( ! empty( $settings['eael_old_events_hide'] ) && 'yes' === $settings['eael_old_events_hide'] ) {
					$is_old_event = $this->is_old_event( $ev_start_date, '', 'yes' === $all_day );

					if ( $is_old_event ) {
						continue;
					}
				}

				if ( $settings['eael_old_events_hide'] === 'start' ) {
					$default_date = $settings['eael_event_default_date_type'] === 'custom' ? $settings['eael_event_calendar_default_date'] : wp_date( 'Y-m-d' );
					$should_show  = $this->is_old_event( $ev_start_date, $default_date, 'yes' === $all_day );

					if ( $should_show ) {
						continue;
					}
				}

				$calendar_data[] = array(
					'id'              => ++$key,
					'title'           => ! empty( $item->summary ) ? $item->summary : 'No Title',
					'description'     => isset( $item->description ) ? $item->description : '',
					'location'        => isset( $item->location ) ? $item->location : '',
					'start'           => $ev_start_date,
					'end'             => $ev_end_date,
					'start_timezone'  => $start_timezone,
					'end_timezone'    => $end_timezone,
					'borderColor'     => ! empty( $settings_eael_event_global_popup_ribbon_color ) ? $settings_eael_event_global_popup_ribbon_color : '#10ecab',
					'textColor'       => $settings_eael_event_global_text_color,
					'color'           => $settings_eael_event_global_bg_color,
					'url'             => ( $settings['eael_event_details_link_hide'] !== 'yes' ) ? esc_url( $item->htmlLink ) : '',
					'allDay'          => $all_day,
					'external'        => 'on',
					'nofollow'        => 'on',
					'google_event_id' => $item->id,
					'backgroundColor' => '',
				);
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
	public function get_the_events_calendar_events( $settings ) {

		if ( ! function_exists( 'tribe_get_events' ) ) {
			return array();
		}
		$arg = array(
			'posts_per_page' => $settings['eael_the_events_calendar_max_result'],
		);

		if ( $settings['eael_the_events_calendar_fetch'] == 'date_range' && 'table' === $settings['eael_event_display_layout'] ) {
			$arg['start_date'] = $settings['eael_the_events_calendar_start_date'];
			$arg['end_date']   = $settings['eael_the_events_calendar_end_date'];
		} elseif ( $settings['eael_event_default_date_type'] === 'custom' && 'table' === $settings['eael_event_display_layout'] ) {
			$arg['start_date'] = $settings['eael_event_calendar_default_date'];
		}

		if ( ! empty( $settings['eael_the_events_calendar_category'] ) ) {
			$arg['tax_query'] = array(
				array(
					'taxonomy' => 'tribe_events_cat',
					'field'    => 'id',
					'terms'    => $settings['eael_the_events_calendar_category'],
				),
			);
		}
		$events = tribe_get_events( $arg );
		if ( empty( $events ) ) {
			return array();
		}

		$random_colors        = $this->get_random_colors();
		$random_color_enabled = isset( $settings['eael_event_random_bg_color'] ) && 'yes' == $settings['eael_event_random_bg_color'];
		$random_color_index   = 0;

		$calendar_data = array();
		foreach ( $events as $key => $event ) {
			$date_format = 'Y-m-d';
			$all_day     = 'yes';
			if ( ! tribe_event_is_all_day( $event->ID ) ) {
				$date_format .= ' H:i';
				$all_day      = '';
			}

			if ( tribe_event_is_all_day( $event->ID ) ) {
				$end = wp_date( 'Y-m-d', strtotime( '+1 days', strtotime( tribe_get_end_date( $event->ID, true, $date_format ) ) ) );
			} else {
				$end = gmdate( 'Y-m-d H:i', strtotime( tribe_get_end_date( $event->ID, true, $date_format ) ) ) . ':01';
			}

			if ( $random_color_enabled ) {
				$random_color_index = $random_color_index > count( $random_colors ) - 2 ? 0 : $random_color_index + 1;

				$settings_eael_event_global_bg_color   = $random_colors[ $random_color_index ];
				$settings_eael_event_global_text_color = '#ffffff';
			} else {
				$settings_eael_event_global_bg_color   = $this->fetch_color_or_global_color( $settings, 'eael_event_global_bg_color' );
				$settings_eael_event_global_text_color = $this->fetch_color_or_global_color( $settings, 'eael_event_global_text_color' );
			}

			$start = tribe_get_start_date( $event->ID, true, $date_format );

			if ( ! empty( $settings['eael_old_events_hide'] ) && 'yes' === $settings['eael_old_events_hide'] ) {
				$is_old_event = $this->is_old_event( $start );
				if ( $is_old_event ) {
					continue;
				}
			}

			if ( $settings['eael_old_events_hide'] === 'start' ) {
				$default_date = $settings['eael_event_default_date_type'] === 'custom' ? $settings['eael_event_calendar_default_date'] : wp_date( 'Y-m-d' );
				$should_show  = $this->is_old_event( $start, $default_date );

				if ( $should_show ) {
					continue;
				}
			}

			$settings_eael_event_global_popup_ribbon_color = $this->fetch_color_or_global_color( $settings, 'eael_event_global_popup_ribbon_color' );

			$calendar_data[] = array(
				'id'          => ++$key,
				'title'       => ! empty( $event->post_title ) ? $event->post_title : __(
					'No Title',
					'essential-addons-for-elementor-lite'
				),
				'description' => do_shortcode( $event->post_content ),
				'start'       => $start,
				'end'         => $end,
				'borderColor' => ! empty( $settings_eael_event_global_popup_ribbon_color ) ? $settings_eael_event_global_popup_ribbon_color : '#10ecab',
				'textColor'   => $settings_eael_event_global_text_color,
				'color'       => $settings_eael_event_global_bg_color,
				'url'         => ( $settings['eael_event_details_link_hide'] !== 'yes' ) ? esc_url( get_the_permalink( $event->ID ) ) : '',
				'allDay'      => $all_day,
				'external'    => 'on',
				'nofollow'    => 'on',
				'imageUrl'    => get_the_post_thumbnail_url( $event->ID, 'full' ),
				'location'    => function_exists( 'tribe_get_venue' ) ? tribe_get_venue( $event->ID ) : '',
				'category'    => function_exists( 'tribe_get_event_categories' ) ? wp_strip_all_tags(
					tribe_get_event_categories(
						$event->ID,
						array(
							'label'        => '',
							'label_before' => '',
							'label_after'  => '',
						)
					)
				) : '',
			);
		}

		return $calendar_data;
	}

	public function is_old_event( $start_date, $date_to_comp = '', $is_all_day = false ) {

		// Use WP timezone correctly
		$tz = wp_timezone();

		// Default comparison date = today (site timezone)
		if ( $date_to_comp === '' ) {
			$date_to_comp = current_time( 'Y-m-d' );
		}

		// ✅ All-day event: compare dates only
		if ( $is_all_day ) {
			return strtotime( $start_date ) < strtotime( $date_to_comp );
		}

		// ✅ Timed event: compare full datetime with timezone
		$start = new \DateTime( $start_date, $tz );
		$now   = new \DateTime( 'now', $tz );

		return $start < $now;
	}


	public function fetch_color_or_global_color( $settings, $control_name = '' ) {
		if ( ! isset( $settings[ $control_name ] ) ) {
			return '';
		}

		$color = $settings[ $control_name ];

		if ( ! empty( $settings['__globals__'] ) && ! empty( $settings['__globals__'][ $control_name ] ) ) {
			$color     = $settings['__globals__'][ $control_name ];
			$color_arr = explode( '?id=', $color ); //E.x. 'globals/colors/?id=primary'

			$color_name = count( $color_arr ) > 1 ? $color_arr[1] : '';
			if ( ! empty( $color_name ) ) {
				$color = "var( --e-global-color-$color_name )";
			}
		}

		return $color;
	}

	public function get_random_colors() {
		$colors = array( '#F43E3E', '#F46C3E', '#F4993E', '#F4C63E', '#F4F43E', '#C6F43E', '#99F43E', '#3EF43E', '#3EF499', '#3EF4C6', '#3EF4F4', '#3EC6F4', '#3E99F4', '#3E3EF4', '#6C3EF4', '#993EF4', '#C63EF4', '#F43EF4', '#F43E99', '#F43E6C', '#F43E3E' );

		return $colors;
	}
}

<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use \Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper;

class Business_Reviews extends Widget_Base {

	/**
	 * Elementor Display Settings
	 * @return mixed An array of all settings, or a single value if `$setting` was specified.
	 */
	protected $settings_data;

	/**
	 * Business Reviews Settings
	 * @return array
	 */
	protected $business_reviews_data;

	public function get_name() {
		return 'eael-business-reviews';
	}

	public function get_title() {
		return esc_html__( 'Business Reviews', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-business-reviews';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords() {
		return [
			'reviews',
			'ea reviews',
			'business reviews',
			'ea business reviews',
			'google reviews',
			'ea google reviews',
			'ea',
			'essential addons',
			'testimonial',
			'google testimonial',
			'reviews carousel',
			'reviews slider',
			'carousel',
			'slider'
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/ea-business-reviews/';
	}

	public function get_style_depends() {
		return [
			'font-awesome-5-all',
			'font-awesome-4-shim'
		];
	}

	public function get_script_depends() {
		return [
			'font-awesome-4-shim'
		];
	}

	protected function register_controls() {

		/**
		 * Business Reviews Settings
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_general_settings',
			[
				'label' => esc_html__( 'General', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_business_reviews_sources',
			[
				'label'   => __( 'Source', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'google-reviews',
				'options' => [
					'google-reviews' => __( 'Google Reviews', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		if ( empty( get_option( 'eael_br_google_place_api_key' ) ) ) {
			$this->add_control( 'eael_br_google_place_api_key_missing', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf( __( 'Google Place API key is missing. Please add it from EA Dashboard » Elements » <a href="%s" target="_blank">Business Reviews Settings</a>', 'essential-addons-for-elementor-lite' ), esc_attr( site_url( '/wp-admin/admin.php?page=eael-settings' ) ) ),
				'content_classes' => 'eael-warning',
				'condition'       => [
					'eael_business_reviews_sources' => 'google-reviews',
				],
			] );
		}

		$this->add_control( 'eael_business_reviews_business_place_id', [
			'label'       => esc_html__( 'Place ID', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'description' => sprintf( __( 'Get Place ID from <a href="%s" target="_blank">here</a>', 'essential-addons-for-elementor-lite' ), esc_url( 'https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder' ) ),
			'placeholder' => esc_html__( 'Place ID', 'essential-addons-for-elementor-lite' ),
			'label_block' => false,
			'default'     => '',
			'dynamic' => [
				'active' => true,
			],
			'condition'   => [
				'eael_business_reviews_sources' => 'google-reviews',
			],
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control(
			'eael_business_reviews_sort_by',
			[
				'label'   => __( 'Sort By', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'most_relevant',
				'options' => [
					'most_relevant' => __( 'Most Relevant', 'essential-addons-for-elementor-lite' ),
					'newest'        => __( 'Newest', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_max_reviews',
			[
				'label'       => __( 'Reviews to Show', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 5,
				'default'     => 5,
				'description' => __( 'Max 5 reviews, please specify amount.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_business_reviews_data_cache_time',
			[
				'label'       => __( 'Data Cache Time', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'default'     => 0,
				'description' => __( 'Cache expiration time (in Minutes), 0 or empty sets 1 day.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_business_reviews_localbusiness_schema',
			[
				'label'        => __( 'Local Business Schema', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Enable', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Disable', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->end_controls_section();

		/**
		 * Business Reviews Layout Settings
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_layout_settings',
			[
				'label' => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_business_reviews_items_layout',
			[
				'label'   => esc_html__( 'Layout Type', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slider',
				'options' => [
					'slider' => esc_html__( 'Slider', 'essential-addons-for-elementor-lite' ),
					'grid'   => esc_html__( 'Grid', 'essential-addons-for-elementor-lite' ),
				]
			]
		);

		$image_path = EAEL_PLUGIN_URL . 'assets/admin/images/layout-previews/business-reviews-';
		$layout_options = [
			'preset-1' => [
				'title' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
				'image' => $image_path . 'preset-1.png'
			],
			'preset-2' => [
				'title' => esc_html__('Preset 2', 'essential-addons-for-elementor-lite'),
				'image' => $image_path . 'preset-2.png'
			],
			'preset-3' => [
				'title' => esc_html__('Preset 3', 'essential-addons-for-elementor-lite'),
				'image' => $image_path . 'preset-3.png'
			],
		];
		
		$this->add_control(
			'eael_business_reviews_style_preset_slider',
			[
				'label'       => esc_html__( 'Style Preset', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => $layout_options,
				'default'     => 'preset-1',
				'label_block' => true,
				'toggle'      => false,
				'image_choose'=> true,
				'condition'   => [
					'eael_business_reviews_items_layout' => 'slider'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_style_preset_grid',
			[
				'label'       => esc_html__( 'Style Preset', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => $layout_options,
				'default'     => 'preset-1',
				'label_block' => true,
				'toggle'      => false,
				'image_choose'=> true,
				'condition'   => [
					'eael_business_reviews_items_layout' => 'grid'
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_column',
			[
				'label'              => esc_html__( 'Columns', 'essential-addons-for-elementor-lite' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '3',
				'mobile_default'     => '3',
				'options'            => [
					'1' => esc_html__( '1', 'essential-addons-for-elementor-lite' ),
					'2' => esc_html__( '2', 'essential-addons-for-elementor-lite' ),
					'3' => esc_html__( '3', 'essential-addons-for-elementor-lite' ),
					'4' => esc_html__( '4', 'essential-addons-for-elementor-lite' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'eael_business_reviews_items_layout'         => 'slider',
					'eael_business_reviews_style_preset_slider!' => 'preset-2',
					'eael_business_reviews_transition_effect!'   => 'coverflow'
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_column_preset_2',
			[
				'label'              => esc_html__( 'Columns', 'essential-addons-for-elementor-lite' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '1',
				'tablet_default'     => '1',
				'mobile_default'     => '1',
				'options'            => [
					'1' => esc_html__( '1', 'essential-addons-for-elementor-lite' ),
					'2' => esc_html__( '2', 'essential-addons-for-elementor-lite' ),
					'3' => esc_html__( '3', 'essential-addons-for-elementor-lite' ),
					'4' => esc_html__( '4', 'essential-addons-for-elementor-lite' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'eael_business_reviews_items_layout'        => 'slider',
					'eael_business_reviews_style_preset_slider' => 'preset-2',
					'eael_business_reviews_transition_effect!'  => 'coverflow'
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_column_grid',
			[
				'label'              => esc_html__( 'Columns', 'essential-addons-for-elementor-lite' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => [
					'1' => esc_html__( '1', 'essential-addons-for-elementor-lite' ),
					'2' => esc_html__( '2', 'essential-addons-for-elementor-lite' ),
					'3' => esc_html__( '3', 'essential-addons-for-elementor-lite' ),
					'4' => esc_html__( '4', 'essential-addons-for-elementor-lite' ),
				],
				'frontend_available' => true,
                'condition'          => [
					'eael_business_reviews_items_layout' => 'grid',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_transition_effect',
			[
				'label'     => __( 'Effect', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'slide',
				'options'   => [
					'slide'     => __( 'Slide', 'essential-addons-for-elementor-lite' ),
					'coverflow' => __( 'Coverflow', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_business_reviews_items_layout' => 'slider'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_item_gap',
			[
				'label'      => __( 'Item Gap', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [ 'size' => 30 ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 300,
						'step' => 5,
					],
				],
				'size_units' => '',
				'condition' => [
					'eael_business_reviews_items_layout' => 'slider'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_grid_row_gap',
			[
				'label'      => __( 'Row Gap', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [ 'size' => 30 ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 300,
						'step' => 5,
					],
				],
				'size_units' => '',
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-grid-body' => 'row-gap: {{SIZE}}px;',
				],
				'condition' => [
					'eael_business_reviews_items_layout' => 'grid'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_grid_column_gap',
			[
				'label'      => __( 'Column Gap', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [ 'size' => 30 ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 300,
						'step' => 5,
					],
				],
				'size_units' => '',
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-grid-body' => 'column-gap: {{SIZE}}px;',
				],
				'condition' => [
					'eael_business_reviews_items_layout' => 'grid'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_slider_speed',
			[
				'label'       => __( 'Sliding Speed', 'essential-addons-for-elementor-lite' ),
				'description' => __( 'Duration of transition between slides (in ms)', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [ 'size' => 1000 ],
				'range'       => [
					'px' => [
						'min'  => 100,
						'max'  => 3000,
						'step' => 1,
					],
				],
				'size_units'  => '',
				'condition'   => [
					'eael_business_reviews_items_layout' => 'slider',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_autoplay',
			[
				'label'        => __( 'Autoplay', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_business_reviews_items_layout' => 'slider'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_autoplay_delay',
			[
				'label'      => __( 'Autoplay Delay', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [ 'size' => 3000 ],
				'range'      => [
					'px' => [
						'min'  => 1000,
						'max'  => 10000,
						'step' => 100,
					],
				],
				'size_units' => '',
				'condition'  => [
					'eael_business_reviews_items_layout' => 'slider',
					'eael_business_reviews_autoplay'     => 'yes'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_loop',
			[
				'label'        => __( 'Infinite Loop', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_business_reviews_items_layout' => 'slider'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_pause_on_hover',
			[
				'label'        => __( 'Pause on Hover', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_business_reviews_items_layout' => 'slider'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_grab_cursor',
			[
				'label'        => __( 'Grab Cursor', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'description'  => __( 'Shows grab cursor when you hover over the slider', 'essential-addons-for-elementor-lite' ),
				'condition'    => [
					'eael_business_reviews_items_layout' => 'slider'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_navigation',
			[
				'label'     => esc_html__( 'Navigation', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition'    => [
					'eael_business_reviews_items_layout' => 'slider'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_arrows',
			[
				'label'        => __( 'Arrows', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_business_reviews_items_layout' => 'slider'
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_dots',
			[
				'label'        => __( 'Dots', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'eael_business_reviews_items_layout' => 'slider'
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Business Reviews Content
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_content',
			[
				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_business_reviews_business_content',
			[
				'label' => esc_html__( 'Business', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_business_reviews_business_logo',
			[
				'label'        => __( 'Logo', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_business_reviews_business_logo_icon_new',
			[
				'label'            => __( 'Custom Logo', 'essential-addons-for-elementor-lite' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'eael_business_reviews_business_logo_icon',
				'condition'        => [
					'eael_business_reviews_business_logo' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_business_name',
			[
				'label'        => __( 'Name', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control( 'eael_business_reviews_business_name_label', [
			'label'       => esc_html__( 'Custom Text', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'placeholder' => esc_html__( 'Business Name', 'essential-addons-for-elementor-lite' ),
			'label_block' => false,
			'default'     => '',
			'condition'   => [
				'eael_business_reviews_business_name' => 'yes'
			],
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control(
			'eael_business_reviews_business_rating',
			[
				'label'        => __( 'Rating', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control( 'eael_business_reviews_google_reviews_label', [
			'label'       => esc_html__( 'Custom Text', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'default'     => esc_html__( 'Google Reviews', 'essential-addons-for-elementor-lite' ),
			'placeholder' => esc_html__( 'Google Reviews', 'essential-addons-for-elementor-lite' ),
			'condition'   => [
				'eael_business_reviews_sources'         => 'google-reviews',
				'eael_business_reviews_business_rating' => 'yes'
			],
			'ai' => [
				'active' => false,
			],
		] );

		$this->add_control(
			'eael_business_reviews_business_address',
			[
				'label'        => __( 'Address', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'eael_business_reviews_review_content',
			[
				'label'     => esc_html__( 'Review', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_business_reviews_review_time',
			[
				'label'        => __( 'Time', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_business_reviews_review_text',
			[
				'label'        => __( 'Text', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_business_reviews_review_rating',
			[
				'label'        => __( 'Rating', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_business_reviews_review_text_translation',
			[
				'label'        => __( 'Translation', 'essential-addons-for-elementor-lite' ),
				'description'  => __('Reviews will be translated into English.', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'	   => [
					'eael_business_reviews_review_text' => 'yes',
				]
			]
		);

		$this->add_control(
			'eael_business_reviews_review_1_star_hide',
			[
				'label'        => __( 'Hide 1 Star Reviews', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'eael_business_reviews_reviewer_content',
			[
				'label'     => esc_html__( 'Reviewer', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_business_reviews_reviewer_photo',
			[
				'label'        => __( 'Avatar', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_business_reviews_reviewer_name',
			[
				'label'        => __( 'Name', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

		/**
		 * Accessibilty Controller
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_accessibilty',
			[
				'label' => esc_html__( 'Accessibilty', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_business_reviews_enable_accessibilty',
			[
				'label'        => __( 'Enable Accessibilty', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'eael_business_reviews_link_in_same_tab',
			[
				'label'        => __( 'Open in same window', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'description'  => __( 'Recommended to open link in the same tab instead of a new tab', 'essential-addons-for-elementor-lite' ),
				'condition'    => [
					'eael_business_reviews_enable_accessibilty' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Container Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_container_style',
			[
				'label' => esc_html__( 'Container', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_wrap_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-business-reviews-items' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_wrap_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [ 'size' => 15 ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-business-reviews-items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_wrap_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-business-reviews-items' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->start_controls_tabs( 'eael_business_reviews_wrap_controls_tabs' );

		$this->start_controls_tab( 'eael_business_reviews_wrap_control_normal', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_business_reviews_wrap_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-business-reviews-items' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_business_reviews_wrap_normal_border',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-business-reviews-items',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_business_reviews_wrap_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-business-reviews-items',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_business_reviews_wrap_control_hover', [
			'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_business_reviews_wrap_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-business-reviews-items:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_business_reviews_wrap_hover_border',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-business-reviews-items:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_business_reviews_wrap_hover_box_shadow',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-business-reviews-items:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Header Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_header_style',
			[
				'label' => esc_html__( 'Header', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_header_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-grid-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_header_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-grid-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_header_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-header' => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-grid-header' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->start_controls_tabs( 'eael_business_reviews_header_controls_tabs' );

		$this->start_controls_tab( 'eael_business_reviews_header_control_normal', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_business_reviews_header_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-header' => 'background: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-grid-header' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_business_reviews_header_normal_border',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-header, {{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-grid-header',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_business_reviews_header_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-header, {{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-grid-header',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_business_reviews_header_control_hover', [
			'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_business_reviews_header_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-header:hover' => 'background: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-grid-header:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_business_reviews_header_hover_border',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-header:hover, {{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-grid-header:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_business_reviews_header_hover_box_shadow',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-header:hover, {{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-grid-header:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'eael_business_reviews_header_alignment',
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
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-header'   => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-grid-header'   => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-rating' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_header_business_logo_label',
			[
				'label'     => esc_html__( 'Business Logo', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_business_reviews_business_logo' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_header_business_logo_size',
			[
				'label'     => __( 'Logo Size', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 100,
				],
				'range'     => [
					'px' => [
						'min'  => 20,
						'max'  => 500,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-logo span' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-logo img'  => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-logo svg'  => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'eael_business_reviews_business_logo' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_header_business_logo_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					"{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-logo span"     => 'color: {{VALUE}};',
					"{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-logo svg"      => 'color: {{VALUE}} !important; fill: {{VALUE}} !important;',
					"{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-logo svg path" => 'color: {{VALUE}} !important; fill: {{VALUE}} !important;',
				],
				'condition' => [
					'eael_business_reviews_business_logo' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_header_business_logo_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_business_logo' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_header_business_logo_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_business_logo' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_header_business_name_label',
			[
				'label'     => esc_html__( 'Business Name', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_business_reviews_business_name' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'eael_business_reviews_header_business_name_typography',
				'selector'  => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-name a',
				'condition' => [
					'eael_business_reviews_business_name' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_header_business_name_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-name'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-name a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_business_reviews_business_name' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_header_business_name_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_business_name' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_header_business_name_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_business_name' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_header_business_rating_label',
			[
				'label'     => esc_html__( 'Business Rating', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_business_reviews_business_rating' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'eael_business_reviews_header_business_rating_typography',
				'selector'  => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-rating, {{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-rating a',
				'condition' => [
					'eael_business_reviews_business_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_header_business_rating_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#5E5E5E',
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-rating'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-rating a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_business_reviews_business_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_header_business_rating_star_color',
			[
				'label'     => esc_html__( 'Star Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-rating svg'      => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-rating svg path' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'eael_business_reviews_business_rating' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_header_business_rating_star_size',
			[
				'label'      => esc_html__( 'Star Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 15,
						'max'  => 200,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-rating svg'      => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-rating svg path' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_business_rating' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_header_business_rating_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_business_rating' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_header_business_rating_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_business_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_header_business_address_label',
			[
				'label'     => esc_html__( 'Business Address', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_business_reviews_business_address' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'eael_business_reviews_header_business_address_typography',
				'selector'  => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-address, {{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-address a',
				'condition' => [
					'eael_business_reviews_business_address' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_header_business_address_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-address'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-address a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_business_reviews_business_address' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_header_business_address_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-address' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_business_address' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_header_business_address_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-business-address' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_business_address' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Content Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_content_style',
			[
				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_content_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_content_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_content_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-content' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->start_controls_tabs( 'eael_business_reviews_content_controls_tabs' );

		$this->start_controls_tab( 'eael_business_reviews_content_control_normal', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_business_reviews_content_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-content' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_business_reviews_content_normal_border',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-content',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_business_reviews_content_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-content',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_business_reviews_content_control_hover', [
			'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_business_reviews_content_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-content:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_business_reviews_content_hover_border',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-content:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_business_reviews_content_hover_box_shadow',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-content:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Reviews Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_reviews_style',
			[
				'label' => esc_html__( 'Reviews', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_reviews_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_reviews_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_reviews_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-item' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->start_controls_tabs( 'eael_business_reviews_reviews_controls_tabs' );

		$this->start_controls_tab( 'eael_business_reviews_reviews_control_normal', [
			'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_business_reviews_reviews_normal_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-item'                               => 'background: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-item .preset-extra-shadow svg'      => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-item .preset-extra-shadow svg path' => 'fill: {{VALUE}}; display:none',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_business_reviews_reviews_normal_border',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_business_reviews_reviews_normal_box_shadow',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_business_reviews_reviews_control_hover', [
			'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
		] );

		$this->add_control(
			'eael_business_reviews_reviews_hover_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-item:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_business_reviews_reviews_hover_border',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-item:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_business_reviews_reviews_hover_box_shadow',
				'selector' => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-item:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'eael_business_reviews_reviewer_photo_label',
			[
				'label'     => esc_html__( 'Reviewer Avatar', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_business_reviews_reviewer_photo' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_reviewer_photo_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-reviewer-photo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_reviewer_photo' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_reviewer_photo_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-reviewer-photo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_reviewer_photo' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_reviewer_photo_size',
			[
				'label'      => esc_html__( 'Photo Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [ 'size' => '50' ],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-reviewer-photo img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_reviewer_photo' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_reviewer_name_label',
			[
				'label'     => esc_html__( 'Reviewer Name', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_business_reviews_reviewer_name' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'eael_business_reviews_reviewer_name_typography',
				'selector'  => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-reviewer-name a',
				'condition' => [
					'eael_business_reviews_reviewer_name' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_reviewer_name_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-reviewer-name'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-reviewer-name a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_business_reviews_reviewer_name' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_reviewer_name_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-reviewer-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_reviewer_name' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_reviewer_name_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-reviewer-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_reviewer_name' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_review_time_label',
			[
				'label'     => esc_html__( 'Review Time', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_business_reviews_review_time' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'eael_business_reviews_review_time_typography',
				'selector'  => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-time, {{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-time a',
				'condition' => [
					'eael_business_reviews_review_time' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_review_time_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default' 	=> '#4A4B50',
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-time'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-time a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_business_reviews_review_time' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_review_time_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-time' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_review_time' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_review_time_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-time' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_review_time' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_review_text_label',
			[
				'label'     => esc_html__( 'Review Text', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_business_reviews_review_text' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'eael_business_reviews_review_text_typography',
				'selector'  => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-text, {{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-text a',
				'condition' => [
					'eael_business_reviews_review_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_review_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-text'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-text a' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_business_reviews_review_text' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_review_text_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_review_text' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_review_text_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_review_text' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'eael_business_reviews_review_text_outer_border',
				'selector'       => '{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-reviews-slider-item .preset-content-body',
				'fields_options' => [
					'border' => [
						'default' => 'none',
					],
					'width'  => [
						'default' => [
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
							'isLinked' => false,
						],
					],
					'color'  => [
						'default' => '#f5f5f5',
					],
				],
				'condition'      => [
					'eael_business_reviews_style_preset_slider' => 'preset-3',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_review_text_height',
			[
				'label'      => __( 'Height', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 15,
						'max'  => 500,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-text' => 'height: {{SIZE}}{{UNIT}}; overflow-y: auto;',
				],
				'condition'  => [
					'eael_business_reviews_review_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_review_rating_label',
			[
				'label'     => esc_html__( 'Review Rating', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'eael_business_reviews_review_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_review_rating_star_color',
			[
				'label'     => esc_html__( 'Star Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-rating svg'      => 'fill: {{VALUE}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-rating svg path' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'eael_business_reviews_review_rating' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_review_rating_star_size',
			[
				'label'      => esc_html__( 'Star Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 15,
						'max'  => 200,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-rating svg'      => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-rating svg path' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_review_rating' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_review_rating_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_review_rating' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_review_rating_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'eael_business_reviews_review_rating' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_review_rating_star_alignment',
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
					'{{WRAPPER}} .eael-business-reviews-wrapper .eael-google-review-rating' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Arrows Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_arrows_style',
			[
				'label'     => esc_html__( 'Arrows', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_business_reviews_items_layout' => 'slider',
					'eael_business_reviews_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_arrows_type',
			[
				'label'       => __( 'Choose Arrow', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'fa fa-angle-right',
				'options'     => [
					'fa fa-angle-right'          => __( 'Angle', 'essential-addons-for-elementor-lite' ),
					'fa fa-angle-double-right'   => __( 'Double Angle', 'essential-addons-for-elementor-lite' ),
					'fa fa-chevron-right'        => __( 'Chevron', 'essential-addons-for-elementor-lite' ),
					'fa fa-chevron-circle-right' => __( 'Chevron Circle', 'essential-addons-for-elementor-lite' ),
					'fa fa-arrow-right'          => __( 'Arrow', 'essential-addons-for-elementor-lite' ),
					'fa fa-long-arrow-right'     => __( 'Long Arrow', 'essential-addons-for-elementor-lite' ),
					'fa fa-caret-right'          => __( 'Caret', 'essential-addons-for-elementor-lite' ),
					'fa fa-caret-square-o-right' => __( 'Caret Square', 'essential-addons-for-elementor-lite' ),
					'fa fa-arrow-circle-right'   => __( 'Arrow Circle', 'essential-addons-for-elementor-lite' ),
					'fa fa-arrow-circle-o-right' => __( 'Arrow Circle O', 'essential-addons-for-elementor-lite' ),
					'fa fa-toggle-right'         => __( 'Toggle', 'essential-addons-for-elementor-lite' ),
					'fa fa-hand-o-right'         => __( 'Hand', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_arrows_width',
			[
				'label'      => __( 'Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [ 'size' => '27' ],
				'range'      => [
					'px' => [
						'min'  => 15,
						'max'  => 200,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_arrows_height',
			[
				'label'      => __( 'Height', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [ 'size' => '44' ],
				'range'      => [
					'px' => [
						'min'  => 15,
						'max'  => 200,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_arrows_size',
			[
				'label'      => __( 'Font Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [ 'size' => '22' ],
				'range'      => [
					'px' => [
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_arrows_left_position',
			[
				'label'      => __( 'Align Left Arrow', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => - 100,
						'max'  => 40,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_arrows_right_position',
			[
				'label'      => __( 'Align Right Arrow', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => - 100,
						'max'  => 40,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'eael_business_reviews_arrows_tabs_style' );

		$this->start_controls_tab(
			'eael_business_reviews_arrows_tab_normal',
			[
				'label' => __( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_business_reviews_arrows_bg_color_normal',
			[
				'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_arrows_color_normal',
			[
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'eael_business_reviews_arrows_border_normal',
				'label'       => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev'
			]
		);

		$this->add_control(
			'eael_business_reviews_arrows_border_radius_normal',
			[
				'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'eael_business_reviews_arrows_tab_hover',
			[
				'label' => __( 'Hover', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_business_reviews_arrows_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_arrows_color_hover',
			[
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_arrows_border_color_hover',
			[
				'label'     => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Dots Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_business_reviews_dots_style',
			[
				'label'     => esc_html__( 'Dots', 'essential-addons-for-elementor-lite' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_business_reviews_items_layout' => 'slider',
					'eael_business_reviews_dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_dots_size',
			[
				'label'      => __( 'Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 2,
						'max'  => 40,
						'step' => 1,
					],
				],
				'size_units' => '',
				'selectors'  => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'eael_business_reviews_dots_spacing',
			[
				'label'      => __( 'Spacing', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 30,
						'step' => 1,
					],
				],
				'size_units' => '',
				'selectors'  => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'eael_business_reviews_dots_tabs_style' );

		$this->start_controls_tab(
			'eael_business_reviews_dots_tab_normal',
			[
				'label' => __( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_business_reviews_dots_color_normal',
			[
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_active_dot_color_normal',
			[
				'label'     => __( 'Active Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'eael_business_reviews_dots_border_normal',
				'label'       => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
			]
		);

		$this->add_control(
			'eael_business_reviews_dots_border_radius_normal',
			[
				'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'eael_business_reviews_dots_tab_hover',
			[
				'label' => __( 'Hover', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_business_reviews_dots_color_hover',
			[
				'label'     => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_business_reviews_dots_border_color_hover',
			[
				'label'     => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'eael_business_reviews_dots_padding',
			[
				'label'              => __( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', 'em', '%' ],
				'allowed_dimensions' => 'vertical',
				'placeholder'        => [
					'top'    => '',
					'right'  => 'auto',
					'bottom' => '',
					'left'   => 'auto',
				],
				'selectors'          => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function get_business_reviews_settings() {
		$settings                                     		= $this->get_settings_for_display();
		$settings['eael_business_reviews_source_key'] 		= get_option( 'eael_br_google_place_api_key' );

		$business_reviews                            		= [];
		$business_reviews['source']                  		= ! empty( $settings['eael_business_reviews_sources'] ) ? esc_html( $settings['eael_business_reviews_sources'] ) : 'google-reviews';
		$business_reviews['place_id']                		= ! empty( $settings['eael_business_reviews_business_place_id'] ) ? esc_html( $settings['eael_business_reviews_business_place_id'] ) : 'ChIJj61dQgK6j4AR4GeTYWZsKWw';
		$business_reviews['api_key']                 		= ! empty( $settings['eael_business_reviews_source_key'] ) ? esc_html( $settings['eael_business_reviews_source_key'] ) : '';
		$business_reviews['reviews_sort']            		= ! empty( $settings['eael_business_reviews_sort_by'] ) ? esc_html( $settings['eael_business_reviews_sort_by'] ) : 'most_relevant';
		$business_reviews['review_text_translation'] 		= ! empty( $settings['eael_business_reviews_review_text_translation'] ) && 'yes' === $settings['eael_business_reviews_review_text_translation'] ? 1 : 0;

		$business_reviews['expiration'] 					= ! empty( $settings['eael_business_reviews_data_cache_time'] ) ? absint( $settings['eael_business_reviews_data_cache_time'] ) * MINUTE_IN_SECONDS : DAY_IN_SECONDS;
		$business_reviews['md5']        					= md5( $business_reviews['api_key'] . $business_reviews['reviews_sort'] . $business_reviews['review_text_translation'] . $this->get_id() );
		$business_reviews['cache_key']  					= "eael_{$business_reviews['source']}_{$business_reviews['place_id']}_{$business_reviews['expiration']}_{$business_reviews['md5']}_brev_cache";

		$business_reviews['layout'] 						= ! empty( $settings['eael_business_reviews_items_layout'] ) ? $settings['eael_business_reviews_items_layout'] : 'slider';
		$business_reviews['preset'] 						= ! empty( $settings['eael_business_reviews_style_preset_slider'] ) && 'slider' === $business_reviews['layout'] ? $settings['eael_business_reviews_style_preset_slider'] : 'preset-1';
		$business_reviews['preset'] 						= ! empty( $settings['eael_business_reviews_style_preset_grid'] ) && 'grid' === $business_reviews['layout'] ? $settings['eael_business_reviews_style_preset_grid'] : $business_reviews['preset'];

		$business_reviews['columns']        				= ! empty( $settings['eael_business_reviews_column'] ) ? $settings['eael_business_reviews_column'] : 3;
		$business_reviews['columns_tablet'] 				= ! empty( $settings['eael_business_reviews_column_tablet'] ) ? $settings['eael_business_reviews_column_tablet'] : 3;
		$business_reviews['columns_mobile'] 				= ! empty( $settings['eael_business_reviews_column_mobile'] ) ? $settings['eael_business_reviews_column_mobile'] : 3;

		$business_reviews['loop']              				= ! empty( $settings['eael_business_reviews_loop'] ) && 'yes' === $settings['eael_business_reviews_loop'] ? 1 : 0;
		$business_reviews['arrows']            				= ! empty( $settings['eael_business_reviews_arrows'] ) && 'yes' === $settings['eael_business_reviews_arrows'] ? 1 : 0;
		$business_reviews['dots']              				= ! empty( $settings['eael_business_reviews_dots'] ) && 'yes' === $settings['eael_business_reviews_dots'] ? 1 : 0;
		$business_reviews['effect']            				= ! empty( $settings['eael_business_reviews_transition_effect'] ) ? $settings['eael_business_reviews_transition_effect'] : 'slide';
		$business_reviews['item_gap']          				= ! empty( $settings['eael_business_reviews_item_gap']['size'] ) ? $settings['eael_business_reviews_item_gap']['size'] : 30;
		$business_reviews['autoplay']          				= ! empty( $settings['eael_business_reviews_autoplay'] ) && 'yes' === $settings['eael_business_reviews_autoplay'] ? 1 : 0;
		$business_reviews['autoplay_delay']    				= ! empty( $settings['eael_business_reviews_autoplay_delay']['size'] ) ? $settings['eael_business_reviews_autoplay_delay']['size'] : 3000;
		$business_reviews['pause_on_hover']    				= ! empty( $settings['eael_business_reviews_pause_on_hover'] ) && 'yes' === $settings['eael_business_reviews_pause_on_hover'] ? 1 : 0;
		$business_reviews['grab_cursor']       				= ! empty( $settings['eael_business_reviews_grab_cursor'] ) && 'yes' === $settings['eael_business_reviews_grab_cursor'] ? 1 : 0;
		$business_reviews['speed']             				= ! empty( $settings['eael_business_reviews_slider_speed']['size'] ) ? $settings['eael_business_reviews_slider_speed']['size'] : 1000;
		$business_reviews['business_logo']     				= ! empty( $settings['eael_business_reviews_business_logo'] ) && 'yes' === $settings['eael_business_reviews_business_logo'] ? 1 : 0;
		$business_reviews['business_name']     				= ! empty( $settings['eael_business_reviews_business_name'] ) && 'yes' === $settings['eael_business_reviews_business_name'] ? 1 : 0;
		$business_reviews['business_rating']   				= ! empty( $settings['eael_business_reviews_business_rating'] ) && 'yes' === $settings['eael_business_reviews_business_rating'] ? 1 : 0;
		$business_reviews['business_address']  				= ! empty( $settings['eael_business_reviews_business_address'] ) && 'yes' === $settings['eael_business_reviews_business_address'] ? 1 : 0;
		$business_reviews['reviewer_photo']    				= ! empty( $settings['eael_business_reviews_reviewer_photo'] ) && 'yes' === $settings['eael_business_reviews_reviewer_photo'] ? 1 : 0;
		$business_reviews['reviewer_name']     				= ! empty( $settings['eael_business_reviews_reviewer_name'] ) && 'yes' === $settings['eael_business_reviews_reviewer_name'] ? 1 : 0;
		$business_reviews['review_time']       				= ! empty( $settings['eael_business_reviews_review_time'] ) && 'yes' === $settings['eael_business_reviews_review_time'] ? 1 : 0;
		$business_reviews['review_text']       				= ! empty( $settings['eael_business_reviews_review_text'] ) && 'yes' === $settings['eael_business_reviews_review_text'] ? 1 : 0;
		$business_reviews['review_rating']     				= ! empty( $settings['eael_business_reviews_review_rating'] ) && 'yes' === $settings['eael_business_reviews_review_rating'] ? 1 : 0;
		$business_reviews['review_1_star']     				= empty( $settings['eael_business_reviews_review_1_star_hide'] ) ? 1 : 0;
		$business_reviews['reviews_max_count'] 				= ! empty( $settings['eael_business_reviews_max_reviews'] ) ? intval( $settings['eael_business_reviews_max_reviews'] ) : 5;

		$business_reviews['business_logo_icon_migrated']	= isset( $settings['__fa4_migrated']['eael_business_reviews_business_logo_icon_new'] );
		$business_reviews['business_logo_icon_new']      	= empty( $settings['eael_business_reviews_business_logo_icon'] );
		$business_reviews['business_logo_icon_new_data'] 	= ! empty( $settings['eael_business_reviews_business_logo_icon_new'] ) ? $settings['eael_business_reviews_business_logo_icon_new'] : [];
		$business_reviews['business_logo_icon_data']     	= ! empty( $settings['eael_business_reviews_business_logo_icon'] ) ? $settings['eael_business_reviews_business_logo_icon'] : [];
		$business_reviews['business_name_label']         	= ! empty( $settings['eael_business_reviews_business_name_label'] ) ? $settings['eael_business_reviews_business_name_label'] : '';
		$business_reviews['google_reviews_label']        	= ! empty( $settings['eael_business_reviews_google_reviews_label'] ) ? $settings['eael_business_reviews_google_reviews_label'] : '';
		$business_reviews['arrows_type']                 	= ! empty( $settings['eael_business_reviews_arrows_type'] ) ? $settings['eael_business_reviews_arrows_type'] : 'fa fa-angle-right';
		$business_reviews['localbusiness_schema']       	= ! empty( $settings['eael_business_reviews_localbusiness_schema'] ) && 'yes' === $settings['eael_business_reviews_localbusiness_schema'] ? 1 : 0;

		if ( 'grid' === $business_reviews['layout'] ) {
			$business_reviews['columns']              		= ! empty( $settings['eael_business_reviews_column_grid'] ) ? $settings['eael_business_reviews_column_grid'] : 3;
			$business_reviews['columns_tablet']       		= ! empty( $settings['eael_business_reviews_column_grid_tablet'] ) ? $settings['eael_business_reviews_column_grid_tablet'] : 2;
			$business_reviews['columns_mobile']       		= ! empty( $settings['eael_business_reviews_column_grid_mobile'] ) ? $settings['eael_business_reviews_column_grid_mobile'] : 1;
			$business_reviews['columns_class']        		= ! empty( $settings['eael_business_reviews_column_grid'] ) ? 'eael-column-' . $business_reviews['columns'] : 'eael-column-3';
			$business_reviews['columns_tablet_class'] 		= ! empty( $settings['eael_business_reviews_column_grid_tablet'] ) ? 'eael-column-tablet-' . $business_reviews['columns_tablet'] : 'eael-column-tablet-2';
			$business_reviews['columns_mobile_class'] 		= ! empty( $settings['eael_business_reviews_column_grid_mobile'] ) ? 'eael-column-mobile-' . $business_reviews['columns_mobile'] : 'eael-column-mobile-1';
		}

		if ( 'slider' === $business_reviews['layout'] && 'preset-2' === $business_reviews['preset'] ) {
			$business_reviews['columns']        			= ! empty( $settings['eael_business_reviews_column_preset_2'] ) ? $settings['eael_business_reviews_column_preset_2'] : $business_reviews['columns'];
			$business_reviews['columns_tablet'] 			= ! empty( $settings['eael_business_reviews_column_preset_2_tablet'] ) ? $settings['eael_business_reviews_column_preset_2_tablet'] : $business_reviews['columns'];
			$business_reviews['columns_mobile'] 			= ! empty( $settings['eael_business_reviews_column_preset_2_mobile'] ) ? $settings['eael_business_reviews_column_preset_2_mobile'] : $business_reviews['columns'];
		}

		if ( 'coverflow' === $business_reviews['effect'] ) {
			$business_reviews['columns'] 					= 3;
		}

		$business_reviews['accessibility_link_in_same_tab'] = 0;
		$business_reviews['accessibility_enabled'] = ! empty( $settings['eael_business_reviews_enable_accessibilty'] ) && 'yes' === $settings['eael_business_reviews_enable_accessibilty'];

		if ( $business_reviews['accessibility_enabled'] ) {
			$business_reviews['accessibility_link_in_same_tab'] = ! empty( $settings['eael_business_reviews_link_in_same_tab'] ) && 'yes' === $settings['eael_business_reviews_link_in_same_tab'];
		}

		return $business_reviews;
	}

	/**
	 * API Call to Get Business Reviews
	 */
	public function fetch_business_reviews_from_api() {
		$settings      = $this->get_settings_for_display();
		$response      = [];
		$error_message = '';

		$business_reviews = $this->get_business_reviews_settings();
		$items            = get_transient( $business_reviews['cache_key'] );

		if ( false === $items ) {
			switch ( $business_reviews['source'] ) {
				case 'google-reviews':
					$data = $this->fetch_google_reviews_from_api( $business_reviews );
					break;
				default:
					$data = $this->fetch_google_reviews_from_api( $business_reviews );
					break;
			}

			return $data;
		}

		$response = $items ? $items : $response;

		$data = [
			'items'         => $response,
			'error_message' => $error_message,
		];

		return $data;
	}

	public function fetch_google_reviews_from_api( $business_reviews_settings ) {
		$business_reviews = $business_reviews_settings;

		$url           = "https://maps.googleapis.com/maps/api/place/details/json";
		$param         = array();
		$error_message = '';

		$api_fields = 'formatted_address,international_phone_number,name,rating,reviews,url,user_ratings_total,website,photos';
		$api_fields = $business_reviews['localbusiness_schema'] ? 'address_components,' . $api_fields : $api_fields;
		
		$args = array(
			'key'     => sanitize_text_field( $business_reviews['api_key'] ),
			'placeid' => sanitize_text_field( $business_reviews['place_id'] ),
			'reviews_no_translations' => intval( $business_reviews['review_text_translation'] ) ? false : true,
			'fields'  => sanitize_text_field( $api_fields ),
		);

		if ( ! empty( $business_reviews['reviews_sort'] ) ) {
			$args['reviews_sort'] = $business_reviews['reviews_sort'];
		}

		$param = array_merge( $param, $args );

		$headers = array(
			'headers' => array(
				'Content-Type' => 'application/json',
			)
		);
		$options = array(
			'timeout' => 240
		);

		$options = array_merge( $headers, $options );

		if ( empty( $error_message ) ) {
			$response = wp_remote_get(
				esc_url_raw( add_query_arg( $param, $url ) ),
				$options
			);

			$body     = json_decode( wp_remote_retrieve_body( $response ) );
			$response = 'OK' === $body->status ? $body->result : false;

			if ( ! empty( $response ) ) {
				set_transient( $business_reviews['cache_key'], $response, $business_reviews['expiration'] );
			} else {
				$error_message = $this->fetch_google_place_response_error_message( $body->status );
			}
		}

		$data = [
			'items'         => $response,
			'error_message' => $error_message,
		];

		return $data;
	}

	public function fetch_google_place_response_error_message( $status = 'OK' ) {
		$error_message = '';

		switch ( $status ) {
			case 'OK':
				break;

			case 'ZERO_RESULTS':
				$error_message = esc_html__( 'The referenced location, place_id, was valid but no longer refers to a valid result. This may occur if the establishment is no longer in business.', 'essential-addons-for-elementor-lite' );
				break;

			case 'NOT_FOUND':
				$error_message = esc_html__( 'The referenced location, place_id, was not found in the Places database.', 'essential-addons-for-elementor-lite' );
				break;

			case 'INVALID_REQUEST':
				$error_message = esc_html__( 'The API request was malformed.', 'essential-addons-for-elementor-lite' );
				break;

			case 'OVER_QUERY_LIMIT':
				$error_message = esc_html__( 'You have exceeded the QPS limits. Or, Billing has not been enabled on your account. Or, The monthly $200 credit, or a self-imposed usage cap, has been exceeded. Or, The provided method of payment is no longer valid (for example, a credit card has expired).', 'essential-addons-for-elementor-lite' );
				break;

			case 'REQUEST_DENIED':
				$error_message = esc_html__( 'The request is missing an API key. Or, The key parameter is invalid.', 'essential-addons-for-elementor-lite' );
				break;

			case 'UNKNOWN_ERROR':
				$error_message = esc_html__( 'An unknown error occurred.', 'essential-addons-for-elementor-lite' );
				break;

			default:
				break;
		}

		return $error_message;
	}

	public function print_business_reviews( $business_reviews_items ) {
		$settings 			= $this->settings_data         = $this->get_settings_for_display();
		$business_reviews 	= $this->business_reviews_data = $this->get_business_reviews_settings();

		ob_start();

		$this->add_render_attribute( 'eael-business-reviews-wrapper', [
			'class'       => [
				'eael-business-reviews-wrapper',
				'eael-business-reviews-' . $this->get_id(),
				'clearfix',
			],
			'data-source' => esc_attr( $business_reviews['source'] ),
			'data-layout' => esc_attr( $business_reviews['layout'] ),
		] );

		$this->add_render_attribute(
			'eael-business-reviews-items',
			[
				'id'    => 'eael-business-reviews-' . esc_attr( $this->get_id() ),
				'class' => [
					'eael-business-reviews-items',
					'eael-business-reviews-' . esc_attr( $business_reviews['layout'] ),
					esc_attr( $business_reviews['preset'] ),
				],
			]
		);
		?>

        <div <?php echo $this->get_render_attribute_string( 'eael-business-reviews-wrapper' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'eael-business-reviews-items' ); ?>>
				<?php
				switch ( $business_reviews['source'] ) {
					case 'google-reviews':
						$this->print_business_reviews_google( $business_reviews_items );
						break;
					default:
						$this->print_business_reviews_google( $business_reviews_items );
						break;
				}
				?>
            </div>
        </div>

		<?php
		echo ob_get_clean();
	}

	public function print_business_reviews_google( $business_reviews_items ) {
		$settings         = $this->get_settings_for_display();
		$business_reviews = $this->get_business_reviews_settings();

		$google_reviews_data = [];
		$business_review_obj = isset( $business_reviews_items['items'] ) ? $business_reviews_items['items'] : false;
		$error_message       = ! empty( $business_reviews_items['error_message'] ) ? $business_reviews_items['error_message'] : "";

		if ( is_object( $business_review_obj ) && ! is_null( $business_review_obj ) ) {
			$google_reviews_data['formatted_address']          = ! empty( $business_review_obj->formatted_address ) ? $business_review_obj->formatted_address : '';
			$google_reviews_data['international_phone_number'] = ! empty( $business_review_obj->international_phone_number ) ? $business_review_obj->international_phone_number : '';
			$google_reviews_data['name']                       = ! empty( $business_review_obj->name ) ? $business_review_obj->name : '';
			$google_reviews_data['photos']                     = ! empty( $business_review_obj->photos ) ? $business_review_obj->photos : [];
			$google_reviews_data['rating']                     = ! empty( $business_review_obj->rating ) ? $business_review_obj->rating : '';
			$google_reviews_data['reviews']                    = ! empty( $business_review_obj->reviews ) ? $business_review_obj->reviews : [];
			$google_reviews_data['url']                        = ! empty( $business_review_obj->url ) ? $business_review_obj->url : '#';
			$google_reviews_data['user_ratings_total']         = ! empty( $business_review_obj->user_ratings_total ) ? $business_review_obj->user_ratings_total : 0;
			$google_reviews_data['website']                    = ! empty( $business_review_obj->website ) ? $business_review_obj->website : '#';

			switch ( $business_reviews['layout'] ) {
				case 'slider':
					$this->print_google_reviews_slider( $google_reviews_data );
					break;
				case 'grid':
					$this->print_google_reviews_grid( $google_reviews_data );
					break;
				default:
					$this->print_google_reviews_slider( $google_reviews_data );
					break;
			}
		} else {
			printf( '<div class="eael-business-reviews-error-message">%s</div>', esc_html( $error_message ) );
		}
	}

	public function print_google_reviews_slider( $google_reviews_data ) {
		$business_reviews = $this->get_business_reviews_settings();

		$this->add_render_attribute( 'eael-google-reviews-wrapper', [
			'class' => [ 'eael-google-reviews-wrapper', 'swiper-container-wrap', 'swiper-container-wrap-dots-outside', esc_attr( $business_reviews['preset'] ) ],
			'id'    => 'eael-google-reviews-' . esc_attr( $this->get_id() ),
		] );

		$swiper_class = $swiper_version_class = '';
        if ( class_exists( 'Elementor\Plugin' ) ) {
            $swiper_class           = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
            $swiper_version_class   = 'swiper' === $swiper_class ? 'swiper-8' : 'swiper-8-lower';
        }

		$this->add_render_attribute( 'eael-google-reviews-content', [
			'class'               => [ 'eael-google-reviews-content', esc_attr( $swiper_class ), esc_attr( $swiper_version_class ), 'swiper-container-' . esc_attr( $this->get_id() ) ],
			'data-pagination'     => '.swiper-pagination-' . esc_attr( $this->get_id() ),
			'data-arrow-next'     => '.swiper-button-next-' . esc_attr( $this->get_id() ),
			'data-arrow-prev'     => '.swiper-button-prev-' . esc_attr( $this->get_id() ),
			'data-effect'         => esc_attr( $business_reviews['effect'] ),
			'data-items'          => esc_attr( $business_reviews['columns'] ),
			'data-items_tablet'   => esc_attr( $business_reviews['columns_tablet'] ),
			'data-items_mobile'   => esc_attr( $business_reviews['columns_mobile'] ),
			'data-item_gap'       => esc_attr( $business_reviews['item_gap'] ),
			'data-loop'           => esc_attr( $business_reviews['loop'] ),
			'data-autoplay'       => esc_attr( $business_reviews['autoplay'] ),
			'data-autoplay_delay' => esc_attr( $business_reviews['autoplay_delay'] ),
			'data-pause_on_hover' => esc_attr( $business_reviews['pause_on_hover'] ),
			'data-grab_cursor'    => esc_attr( $business_reviews['grab_cursor'] ),
			'data-speed'          => esc_attr( $business_reviews['speed'] ),
		] );

		if ( ! empty( $google_reviews_data['reviews'] ) && count( $google_reviews_data['reviews'] ) ) {
			$single_review_data = [];
			?>
            <div <?php echo $this->get_render_attribute_string( 'eael-google-reviews-wrapper' ); ?>>

                <div class="eael-google-reviews-items eael-google-reviews-slider">
                    <div class="eael-google-reviews-arrows eael-google-reviews-arrows-outside">
						<?php
						if ( ! empty( $business_reviews['arrows'] ) ) {
							$this->render_arrows();
						}
						?>
                    </div>

                    <div class="eael-google-reviews-dots eael-google-reviews-dots-outside">

                    </div>

                    <div <?php echo $this->get_render_attribute_string( 'eael-google-reviews-content' ); ?>>
                        <div class="eael-google-reviews-slider-header">
							<?php if ( $business_reviews['business_logo'] ): ?>
                                <div class="eael-google-reviews-business-logo">
									<?php
									if ( $business_reviews['business_logo_icon_migrated'] || $business_reviews['business_logo_icon_new'] ) {
										if ( isset( $business_reviews['business_logo_icon_new_data']['value']['url'] ) ) {
											Icons_Manager::render_icon( $business_reviews['business_logo_icon_new_data'], [ 'aria-hidden' => 'true' ] );
										} elseif ( isset( $business_reviews['business_logo_icon_new_data']['value'] ) ) {
											if ( empty( $business_reviews['business_logo_icon_new_data']['value'] ) ) {
												?>
                                                <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="480px" height="480px"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg> <?php
											} else {
												printf( '<span class="eael-google-reviews-business-logo-icon %s" aria-hidden="true"></span>', esc_attr( $business_reviews['business_logo_icon_new_data']['value'] ) );
											}
										}
									} else {
										printf( '<span class="eael-google-reviews-business-logo-icon %s" aria-hidden="true"></span>', esc_attr( $business_reviews['business_logo_icon_data'] ) );
									}
									?>
                                </div>
							<?php endif; ?>

							<?php if ( $business_reviews['business_name'] ): ?>
                                <div class="eael-google-reviews-business-name">
									<?php $business_reviews['business_name_label'] = $business_reviews['business_name_label'] ? $business_reviews['business_name_label'] : $google_reviews_data['name']; ?>
                                    <a href="<?php echo esc_url( $google_reviews_data['website'] ); ?>" <?php if ( ! $business_reviews['accessibility_link_in_same_tab'] ) : ?>  target="_blank" <?php endif; ?>  ><?php echo esc_html( $business_reviews['business_name_label'] ); ?></a>
                                </div>
							<?php endif; ?>

							<?php if ( $business_reviews['business_rating'] ): ?>
                                <div class="eael-google-reviews-business-rating">
                                    <p><?php echo esc_html( $google_reviews_data['rating'] ); ?></p>
                                    <p><?php $this->print_business_reviews_ratings( $google_reviews_data['rating'] ); ?></p>
                                    <p><a href="<?php echo esc_url( $google_reviews_data['url'] ); ?>" <?php if ( ! $business_reviews['accessibility_link_in_same_tab'] ) :  ?> target="_blank"  <?php endif; ?> ><?php echo esc_html( number_format( $google_reviews_data['user_ratings_total'] ) . ' ' . $business_reviews['google_reviews_label'] ); ?></a></p>
                                </div>
							<?php endif; ?>

							<?php if ( $business_reviews['business_address'] ): ?>
                                <div class="eael-google-reviews-business-address">
                                    <p><?php printf( '<span>%s</span> %s', esc_html( '' ), esc_html( $google_reviews_data['formatted_address'] ) ); ?></p>
                                    <p><?php printf( '<span>%s</span> <a href="tel:%s">%s</a>', esc_attr( '' ), esc_html( $google_reviews_data['international_phone_number'] ), esc_attr( $google_reviews_data['international_phone_number'] ) ); ?></p>
                                </div>
							<?php endif; ?>
                        </div>

                        <div class="eael-google-reviews-slider-body swiper-wrapper">
							<?php
							$i = 0;

							foreach ( $google_reviews_data['reviews'] as $single_review ) {
								if ( $i >= $business_reviews['reviews_max_count'] ){
									break;
								}

								$single_review_data['author_name']               = ! empty( $single_review->author_name ) ? $single_review->author_name : '';
								$single_review_data['author_url']                = ! empty( $single_review->author_url ) ? $single_review->author_url : '';
								$single_review_data['profile_photo_url']         = ! empty( $single_review->profile_photo_url ) ? $single_review->profile_photo_url : '';
								$single_review_data['rating']                    = ! empty( $single_review->rating ) ? $single_review->rating : '';
								$single_review_data['relative_time_description'] = ! empty( $single_review->relative_time_description ) ? $single_review->relative_time_description : '';
								$single_review_data['text']                      = ! empty( $single_review->text ) ? $single_review->text : '';

								if( ! $business_reviews['review_1_star'] ){
									if ( $single_review_data['rating'] === 1 ) {
										continue;
									}
								}

								$this->add_render_attribute( 'eael-google-reviews-slider-item-' . $i, [
									'class' => [ 'eael-google-reviews-slider-item', 'clearfix', 'swiper-slide' ]
								] );
								?>

                                <div <?php echo $this->get_render_attribute_string( 'eael-google-reviews-slider-item-' . $i ); ?>>
                                    <div class="eael-google-review-reviewer-with-text">
										<?php
										switch ( $business_reviews['preset'] ) {
											case 'preset-1':
												$this->print_google_reviews_slider_preset_1( $business_reviews, $single_review_data );
												break;
											case 'preset-2':
												$this->print_google_reviews_slider_preset_2( $business_reviews, $single_review_data );
												break;
											case 'preset-3':
												$this->print_google_reviews_slider_preset_3( $business_reviews, $single_review_data );
												break;
											default:
												$this->print_google_reviews_slider_preset_1( $business_reviews, $single_review_data );
												break;
										}
										?>
                                    </div>
                                </div>
								<?php
								$i ++;
							}
							?>
                        </div>
						<?php
						if ( ! empty( $business_reviews['dots'] ) ) {
							$this->render_dots();
						}
						?>
                    </div>
                </div>
            </div>
			<?php
		}
	}

	public function print_google_reviews_slider_preset_1( $business_reviews, $single_review_data ) {
		if ( $business_reviews['reviewer_photo'] ): ?>
            <div class="eael-google-review-reviewer-photo">
                <img src="<?php echo esc_url_raw( $single_review_data['profile_photo_url'] ); ?>" alt="<?php echo  $single_review_data['author_name'] ? esc_attr(  $single_review_data['author_name'] ) : esc_html__( 'Reviewer', 'essential-addons-for-elementor-lite' ); ?>">
            </div>
		<?php endif;

		if ( $business_reviews['reviewer_name'] ): ?>
            <div class="eael-google-review-reviewer-name">
                <a href="<?php echo ! empty ( $single_review_data['author_url'] ) ? esc_url_raw( $single_review_data['author_url'] ) : '#'; ?>" <?php if ( ! $business_reviews['accessibility_link_in_same_tab'] ) : ?> target="_blank" <?php endif; ?> ><?php echo esc_html( $single_review_data['author_name'] ); ?></a>
            </div>
		<?php endif;

		if ( $business_reviews['review_time'] ): ?>
            <div class="eael-google-review-time">
				<?php echo esc_html( $single_review_data['relative_time_description'] ); ?>
            </div>
		<?php endif;

		if ( $business_reviews['review_text'] ): ?>
            <div class="eael-google-review-text">
				<?php echo esc_html( $single_review_data['text'] ); ?>
            </div>
		<?php endif;

		if ( $business_reviews['review_rating'] ): ?>
            <div class="eael-google-review-rating">
				<?php $this->print_business_reviews_ratings( $single_review_data['rating'] ); ?>
            </div>
		<?php endif;
	}

	public function print_google_reviews_slider_preset_2( $business_reviews, $single_review_data ) {
		?>
        <div class="preset-content-wrap">
            <div class="preset-content-body">
				<?php if ( $business_reviews['review_text'] ): ?>
                    <div class="eael-google-review-text">
						<?php echo esc_html( $single_review_data['text'] ); ?>
                    </div>
				<?php endif; ?>
            </div>

            <div class="preset-content-footer">
                <div class="preset-content-footer-photo">
					<?php if ( $business_reviews['reviewer_photo'] ): ?>
                        <div class="eael-google-review-reviewer-photo">
                            <img src="<?php echo esc_url_raw( $single_review_data['profile_photo_url'] ); ?>" alt="<?php echo  $single_review_data['author_name'] ? esc_attr(  $single_review_data['author_name'] ) : esc_html__( 'Reviewer', 'essential-addons-for-elementor-lite' ); ?>">
                        </div>
					<?php endif; ?>
                </div>

                <div class="preset-content-footer-reviewer-name">
					<?php if ( $business_reviews['reviewer_name'] ): ?>
                        <div class="eael-google-review-reviewer-name">
                            <a href="<?php echo ! empty ( $single_review_data['author_url'] ) ? esc_url_raw( $single_review_data['author_url'] ) : '#'; ?>" <?php if ( ! $business_reviews['accessibility_link_in_same_tab'] ) : ?> target="_blank"  <?php endif; ?> ><?php echo esc_html( $single_review_data['author_name'] ); ?></a>
                        </div>
					<?php endif;

					if ( $business_reviews['review_time'] ): ?>
                        <div class="eael-google-review-time">
							<?php echo esc_html( $single_review_data['relative_time_description'] ); ?>
                        </div>
					<?php endif; ?>
                </div>

                <div class="preset-content-footer-rating">
					<?php if ( $business_reviews['review_rating'] ): ?>
                        <div class="eael-google-review-rating">
							<?php $this->print_business_reviews_ratings( $single_review_data['rating'] ); ?>
                        </div>
					<?php endif; ?>
                </div>
            </div>
        </div>
		<?php
	}

	public function print_google_reviews_slider_preset_3( $business_reviews, $single_review_data ) {
		?>
        <div class="preset-content-body">
			<?php if ( $business_reviews['review_rating'] ): ?>
                <div class="eael-google-review-rating">
					<?php $this->print_business_reviews_ratings( $single_review_data['rating'] ); ?>
                </div>
			<?php endif;

			if ( $business_reviews['review_text'] ): ?>
                <div class="eael-google-review-text">
					<?php echo esc_html( $single_review_data['text'] ); ?>
                </div>
			<?php endif; ?>

			<div class="preset-extra-shadow eael-d-none">
				<svg width="85" height="74" viewBox="0 0 85 74" fill="none" xmlns="http://www.w3.org/2000/svg">
				<g filter="url(#filter0_d_67_255)">
				<path d="M25 31.3423V12C25 10.8954 25.8954 10 27 10H57.174C59.1715 10 59.9349 12.6058 58.2533 13.6838L28.0793 33.0261C26.7482 33.8794 25 32.9235 25 31.3423Z" fill="white"/>
				</g>
				<defs>
				<filter id="filter0_d_67_255" x="0" y="0" width="84.1776" height="73.3457" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
				<feFlood flood-opacity="0" result="BackgroundImageFix"/>
				<feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
				<feOffset dy="15"/>
				<feGaussianBlur stdDeviation="12.5"/>
				<feComposite in2="hardAlpha" operator="out"/>
				<feColorMatrix type="matrix" values="0 0 0 0 0.0745098 0 0 0 0 0.101961 0 0 0 0 0.25098 0 0 0 0.1 0"/>
				<feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_67_255"/>
				<feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_67_255" result="shape"/>
				</filter>
				</defs>
				</svg>
			</div>
		</div>

        <div class="preset-content-footer">
            <div>
				<?php if ( $business_reviews['reviewer_photo'] ): ?>
                    <div class="eael-google-review-reviewer-photo">
                        <img src="<?php echo esc_url_raw( $single_review_data['profile_photo_url'] ); ?>" alt="<?php echo $single_review_data['author_name'] ? esc_attr(  $single_review_data['author_name'] ) : esc_html__( 'Reviewer', 'essential-addons-for-elementor-lite' ); ?>">
                    </div>
				<?php endif; ?>
            </div>

            <div>
				<?php if ( $business_reviews['reviewer_name'] ): ?>
                    <div class="eael-google-review-reviewer-name">
                        <a href="<?php echo ! empty ( $single_review_data['author_url'] ) ? esc_url_raw( $single_review_data['author_url'] ) : '#'; ?>" <?php if( ! $business_reviews['accessibility_link_in_same_tab'] ) : ?> target="_blank"  <?php endif; ?> ><?php echo esc_html( $single_review_data['author_name'] ); ?></a>
                    </div>
				<?php endif;

				if ( $business_reviews['review_time'] ): ?>
                    <div class="eael-google-review-time">
						<?php echo esc_html( $single_review_data['relative_time_description'] ); ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
		<?php
	}

	public function print_google_reviews_grid( $google_reviews_data ) {
		$business_reviews = $this->get_business_reviews_settings();

		$this->add_render_attribute( 'eael-google-reviews-wrapper', [
			'class' => [ 'eael-google-reviews-wrapper', esc_attr( $business_reviews['preset'] ) ],
			'id'    => 'eael-google-reviews-' . esc_attr( $this->get_id() ),
		] );

		$this->add_render_attribute( 'eael-google-reviews-content', [
			'class'	=> [ 'eael-google-reviews-content' ],
		] );

		$this->add_render_attribute( 'eael-google-reviews-grid-body', [
			'class'	=> [
						'eael-google-reviews-grid-body',
						esc_attr( $business_reviews['columns_class'] ),
						esc_attr( $business_reviews['columns_tablet_class'] ),
						esc_attr( $business_reviews['columns_mobile_class'] )
			],
		] );

		if ( ! empty( $google_reviews_data['reviews'] ) && count( $google_reviews_data['reviews'] ) ) {
			$single_review_data = [];
			?>
            <div <?php echo $this->get_render_attribute_string( 'eael-google-reviews-wrapper' ); ?>>

                <div class="eael-google-reviews-items eael-google-reviews-grid">

                    <div <?php echo $this->get_render_attribute_string( 'eael-google-reviews-content' ); ?>>
                        <div class="eael-google-reviews-grid-header">
							<?php if ( $business_reviews['business_logo'] ): ?>
                                <div class="eael-google-reviews-business-logo">
									<?php
									if ( $business_reviews['business_logo_icon_migrated'] || $business_reviews['business_logo_icon_new'] ) {
										if ( isset( $business_reviews['business_logo_icon_new_data']['value']['url'] ) ) {
											Icons_Manager::render_icon( $business_reviews['business_logo_icon_new_data'], [ 'aria-hidden' => 'true' ] );
										} elseif ( isset( $business_reviews['business_logo_icon_new_data']['value'] ) ) {
											if ( empty( $business_reviews['business_logo_icon_new_data']['value'] ) ) {
												?>
                                                <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 48 48" width="480px" height="480px"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/></svg> <?php
											} else {
												printf( '<span class="eael-google-reviews-business-logo-icon %s" aria-hidden="true"></span>', esc_attr( $business_reviews['business_logo_icon_new_data']['value'] ) );
											}
										}
									} else {
										printf( '<span class="eael-google-reviews-business-logo-icon %s" aria-hidden="true"></span>', esc_attr( $business_reviews['business_logo_icon_data'] ) );
									}
									?>
                                </div>
							<?php endif; ?>

							<?php if ( $business_reviews['business_name'] ): ?>
                                <div class="eael-google-reviews-business-name">
									<?php $business_reviews['business_name_label'] = $business_reviews['business_name_label'] ? $business_reviews['business_name_label'] : $google_reviews_data['name']; ?>
                                    <a href="<?php echo esc_url( $google_reviews_data['website'] ); ?>" <?php if( ! $business_reviews['accessibility_link_in_same_tab'] ) : ?> target="_blank"  <?php endif; ?> ><?php echo esc_html( $business_reviews['business_name_label'] ); ?></a>
                                </div>
							<?php endif; ?>

							<?php if ( $business_reviews['business_rating'] ): ?>
                                <div class="eael-google-reviews-business-rating">
                                    <p><?php echo esc_html( $google_reviews_data['rating'] ); ?></p>
                                    <p><?php $this->print_business_reviews_ratings( $google_reviews_data['rating'] ); ?></p>
                                    <p><a href="<?php echo esc_url( $google_reviews_data['url'] ); ?>" <?php if( ! $business_reviews['accessibility_link_in_same_tab'] ) : ?> target="_blank"  <?php endif; ?> ><?php echo esc_html( number_format( $google_reviews_data['user_ratings_total'] ) . ' ' . $business_reviews['google_reviews_label'] ); ?></a></p>
                                </div>
							<?php endif; ?>

							<?php if ( $business_reviews['business_address'] ): ?>
                                <div class="eael-google-reviews-business-address">
                                    <p><?php printf( '<span>%s</span> %s', esc_html( '' ), esc_html( $google_reviews_data['formatted_address'] ) ); ?></p>
                                    <p><?php printf( '<span>%s</span> <a href="tel:%s">%s</a>', esc_attr( '' ), esc_html( $google_reviews_data['international_phone_number'] ), esc_attr( $google_reviews_data['international_phone_number'] ) ); ?></p>
                                </div>
							<?php endif; ?>
                        </div>

                        <div <?php echo $this->get_render_attribute_string( 'eael-google-reviews-grid-body' ); ?> >
							<?php
							$i = 0;

							foreach ( $google_reviews_data['reviews'] as $single_review ) {
								if ( $i >= $business_reviews['reviews_max_count'] ){
									break;
								}

								$single_review_data['author_name']               = ! empty( $single_review->author_name ) ? $single_review->author_name : '';
								$single_review_data['author_url']                = ! empty( $single_review->author_url ) ? $single_review->author_url : '';
								$single_review_data['profile_photo_url']         = ! empty( $single_review->profile_photo_url ) ? $single_review->profile_photo_url : '';
								$single_review_data['rating']                    = ! empty( $single_review->rating ) ? $single_review->rating : '';
								$single_review_data['relative_time_description'] = ! empty( $single_review->relative_time_description ) ? $single_review->relative_time_description : '';
								$single_review_data['text']                      = ! empty( $single_review->text ) ? $single_review->text : '';

								if( ! $business_reviews['review_1_star'] ){
									if ( $single_review_data['rating'] === 1 ) {
										continue;
									}
								}

								$this->add_render_attribute( 'eael-google-reviews-grid-item-' . $i, [
									'class' => [ 'eael-google-reviews-grid-item' ]
								] );
								?>

                                <div <?php echo $this->get_render_attribute_string( 'eael-google-reviews-grid-item-' . $i ); ?>>
                                    <div class="eael-google-review-reviewer-with-text">
										<?php
										switch ( $business_reviews['preset'] ) {
											case 'preset-1':
												$this->print_google_reviews_slider_preset_1( $business_reviews, $single_review_data );
												break;
											case 'preset-2':
												$this->print_google_reviews_slider_preset_2( $business_reviews, $single_review_data );
												break;
											case 'preset-3':
												$this->print_google_reviews_slider_preset_3( $business_reviews, $single_review_data );
												break;
											default:
												$this->print_google_reviews_slider_preset_1( $business_reviews, $single_review_data );
												break;
										}
										?>
                                    </div>
                                </div>
								<?php
								$i ++;
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}
	}

	protected function render_dots() {
		?>
        <!-- Add Pagination -->
        <div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ); ?>"></div>
		<?php
	}

	protected function render_arrows() {
		$business_reviews = $this->get_business_reviews_settings();

		if ( ! empty( $business_reviews['arrows_type'] ) ) {
			$pa_next_arrow = $business_reviews['arrows_type'];
			$pa_prev_arrow = str_replace( "right", "left", $business_reviews['arrows_type'] );
		} else {
			$pa_next_arrow = 'fa fa-angle-right';
			$pa_prev_arrow = 'fa fa-angle-left';
		}
		?>
        <!-- Add Arrows -->
        <div class="swiper-button-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
            <i class="<?php echo esc_attr( $pa_next_arrow ); ?>"></i>
        </div>
        <div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
            <i class="<?php echo esc_attr( $pa_prev_arrow ); ?>"></i>
        </div>
		<?php
	}

	public function print_business_reviews_ratings( $rating ) {
		if ( empty( $rating ) || intval( $rating ) > 5 ) {
			return false;
		}

		$rating_svg = '<svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M7.37499 10.6517L3.26074 12.9547L4.17949 8.33008L0.717407 5.12875L5.39982 4.57341L7.37499 0.291748L9.35016 4.57341L14.0326 5.12875L10.5705 8.33008L11.4892 12.9547L7.37499 10.6517Z" fill="#F4BB4C"/>
		</svg>';

		$rating_svg_half = '<svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
		<g clip-path="url(#clip0_51_21)">
		<path d="M7.88891 9.31475L10.3663 10.7013L9.81274 7.91708L11.897 5.98916L9.07774 5.65491L7.88891 3.07716V9.31475ZM7.88891 10.6517L3.77466 12.9547L4.69341 8.33008L1.23132 5.12875L5.91374 4.57341L7.88891 0.291748L9.86407 4.57341L14.5465 5.12875L11.0844 8.33008L12.0032 12.9547L7.88891 10.6517Z" fill="#F4BB4C"/>
		</g>
		<defs>
		<clipPath id="clip0_51_21">
		<rect width="14" height="14" fill="white" transform="translate(0.888916)"/>
		</clipPath>
		</defs>
		</svg>
		';

		for ( $i = 1; $i <= floor( $rating ); $i ++ ) {
			printf( "%s", $rating_svg );
		}

		if ( ! is_int( $rating ) ) {
			printf( "%s", $rating_svg_half );
		}

		return true;
	}

	public function print_localbusiness_schema( $business_reviews_items ){
		$business_reviews_items_obj = isset( $business_reviews_items['items'] ) ? $business_reviews_items['items'] : false;
		
		if ( ! is_object( $business_reviews_items_obj ) ) {
			return;
		}

		$business_reviews_items_reviews = ! empty( $business_reviews_items_obj->reviews ) ? $business_reviews_items_obj->reviews : []; 
		
		if ( ! empty( $this->business_reviews_data['localbusiness_schema'] ) && count( $business_reviews_items_reviews ) ) {
			$reviews = [];
			$street_number = 
			$street_name = 
			$locality_city = 
			$region_state = 
			$postal_code =  
			$country = '';

			// Reviews
			foreach ( $business_reviews_items_reviews as $business_reviews_items_reivew ) {
				$reviews[] = [
					"@type" => "Review",
					"reviewRating" => [
						"@type" => "Rating",
						"ratingValue" => ! empty( $business_reviews_items_reivew->rating ) ? $business_reviews_items_reivew->rating : '',
					],
					"author" => [
						"@type" => "Person",
						"name" => ! empty( $business_reviews_items_reivew->author_name ) ? $business_reviews_items_reivew->author_name : '',
					],
				];
			}

			// Address
			$address_components = ! empty( $business_reviews_items_obj->address_components ) ? $business_reviews_items_obj->address_components : [];

			foreach ($address_components as $component) {
				if (in_array('street_number', $component->types)) {
					$street_number = $component->long_name;
				}
				
				if (in_array('route', $component->types)) {
					$street_name = $component->long_name;
				}

				if (in_array('locality', $component->types)) {
					$locality_city = $component->long_name;
				}

				if (in_array('administrative_area_level_1', $component->types)) {
					$region_state = $component->short_name;
				}
				
				if (in_array('postal_code', $component->types)) {
					$postal_code = $component->long_name;
				}

				if (in_array('country', $component->types)) {
					$country = $component->short_name;
				}
			}

			$address = [
				'@type' => 'PostalAddress',
				'streetAddress' => "{$street_number} {$street_name}",
				'addressLocality' => $locality_city,
				'addressRegion' => $region_state,
				'postalCode' => $postal_code,
				'addressCountry' => $country
			];
			
			$full_schema_array = [
				"@context" => "https://schema.org",
				"@type" => "LocalBusiness",
				"name" => ! empty( $business_reviews_items_obj->name ) ? $business_reviews_items_obj->name : '',
				"address" => $address,
				"review" => $reviews,
				"aggregateRating" => [
					"@type" => "AggregateRating",
					"ratingValue" => ! empty( $business_reviews_items_obj->rating ) ? $business_reviews_items_obj->rating : 0,
					"ratingCount" => ! empty( $business_reviews_items_obj->user_ratings_total ) ? $business_reviews_items_obj->user_ratings_total : 0,
				],
				"url" => ! empty( $business_reviews_items_obj->url ) ? $business_reviews_items_obj->url : '',
				"telephone" => ! empty( $business_reviews_items_obj->international_phone_number ) ? $business_reviews_items_obj->international_phone_number : '',
			];

			ob_start();
			?> 
			<!-- EA LocalBusiness Schema : Starts-->
			<script type="application/ld+json">
				<?php echo json_encode( $full_schema_array ); ?>
			</script>
			<!-- EA LocalBusiness Schema : Ends-->
			<?php
			echo ob_get_clean();
		}
	}

	protected function render() {
		$business_reviews = $this->get_business_reviews_settings();
		if( ! $business_reviews['api_key'] ) {
			return false;
		}
		$business_reviews_items = $this->fetch_business_reviews_from_api();
		$this->print_business_reviews( $business_reviews_items );
		$this->print_localbusiness_schema( $business_reviews_items );
	}
}
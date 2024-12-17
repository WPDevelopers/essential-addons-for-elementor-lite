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
use Elementor\Icons_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Repeater;
use \Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;

class Pricing_Table extends Widget_Base
{

    

    public function get_name()
    {
        return 'eael-pricing-table';
    }

    public function get_title()
    {
        return esc_html__('Pricing Table', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-pricing-table';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'price menu',
            'pricing',
            'price',
            'price table',
            'table',
            'ea table',
            'ea pricing table',
            'comparison table',
            'pricing plan',
            'dynamic price',
            'woocommerce pricing',
            'ea',
            'essential addons',
        ];
    }

    protected function is_dynamic_content():bool {
        return false;
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/pricing-table/';
    }

    protected function register_controls()
    {

        /**
         * Pricing Table Settings
         */
        $this->start_controls_section(
            'eael_section_pricing_table_settings',
            [
                'label' => esc_html__('Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $pricing_style = apply_filters(
            'eael_pricing_table_styles',
            [
                'styles'     => [
                    'style-1' => esc_html__('Default', 'essential-addons-for-elementor-lite'),
                    'style-2' => esc_html__('Pricing Style 2', 'essential-addons-for-elementor-lite'),
                    'style-3' => esc_html__('Pricing Style 3 (Pro)', 'essential-addons-for-elementor-lite'),
                    'style-4' => esc_html__('Pricing Style 4 (Pro)', 'essential-addons-for-elementor-lite'),
                    'style-5' => esc_html__('Pricing Style 5 (Pro)', 'essential-addons-for-elementor-lite'),
                ],
                'conditions' => ['style-3', 'style-4', 'style-5'],
            ]
        );

        $this->add_control(
            'eael_pricing_table_style',
            [
                'label'       => esc_html__('Pricing Style', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'style-1',
                'label_block' => false,
                'options'     => $pricing_style['styles'],
            ]
        );

        $this->add_control(
            'eael_pricing_table_style_pro_alert',
            [
                'label'     => esc_html__('Only available in pro version!', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_pricing_table_style' => $pricing_style['conditions'],
                ],
            ]
        );

        do_action('eael_pricing_table_after_pricing_style', $this);

        /**
         * Condition: 'eael_pricing_table_featured' => 'yes'
         */
        $this->add_control(
            'eael_pricing_table_icon_enabled',
            [
                'label'        => esc_html__('List Icon', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'show',
                'default'      => 'show',
            ]
        );

        $this->add_control(
            'eael_pricing_table_icon_placement',
            [
                'label'       => esc_html__('Icon Placement', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'left',
                'label_block' => false,
                'options'     => [
                    'left'  => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                    'right' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                ],
                'condition'   => [
                    'eael_pricing_table_icon_enabled' => 'show',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_title',
            [
                'label'       => esc_html__('Title', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => false,
                'default'     => esc_html__('Startup', 'essential-addons-for-elementor-lite'),
                'ai' => [
					'active' => false,
				],
            ]
        );

        /**
         * Condition: 'eael_pricing_table_style' => 'style-2'
         */
        $subtitles_fields = apply_filters('pricing_table_subtitle_field_for', ['style-2']);
        $this->add_control(
            'eael_pricing_table_sub_title',
            [
                'label'       => esc_html__('Sub Title', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => false,
                'default'     => esc_html__('A tagline here.', 'essential-addons-for-elementor-lite'),
                'condition'   => [
                    'eael_pricing_table_style' => $subtitles_fields,
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        /**
         * Condition: 'eael_pricing_table_style' => 'style-2'
         */
        $this->add_control(
            'eael_pricing_table_style_2_icon_new',
            [
                'label'            => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_pricing_table_style_2_icon',
                'default'          => [
                    'value'   => 'fas fa-home',
                    'library' => 'fa-solid',
                ],
                'condition'        => [
                    'eael_pricing_table_style' => apply_filters('eael_pricing_table_icon_supported_style', ['style-2']),
                ],
            ]
        );

        do_action('add_pricing_table_settings_control', $this);

        $this->end_controls_section();

        /**
         * Pricing Table Price
         */
        $this->start_controls_section(
            'eael_section_pricing_table_price',
            [
                'label' => esc_html__('Price', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_pricing_table_price',
            [
                'label'       => esc_html__('Price', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'               => [
                    'active'       => true,
                ],
                'label_block' => false,
                'default'     => esc_html__('99', 'essential-addons-for-elementor-lite'),
                'ai' => [
					'active' => false,
				],
            ]
        );
        $this->add_control(
            'eael_pricing_table_onsale',
            [
                'label'        => __('On Sale?', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'eael_pricing_table_onsale_price',
            [
                'label'       => esc_html__('Sale Price', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'               => [
                    'active'       => true,
                ],
                'label_block' => false,
                'default'     => esc_html__('89', 'essential-addons-for-elementor-lite'),
                'condition'   => [
                    'eael_pricing_table_onsale' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );
        $this->add_control(
            'eael_pricing_table_price_cur',
            [
                'label'       => esc_html__('Price Currency', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default'     => esc_html__('$', 'essential-addons-for-elementor-lite'),
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_pricing_table_price_cur_placement',
            [
                'label'       => esc_html__('Currency Placement', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'left',
                'label_block' => false,
                'options'     => [
                    'left'  => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                    'right' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        do_action('pricing_table_currency_position', $this);

        $this->add_control(
            'eael_pricing_table_price_period',
            [
                'label'       => esc_html__('Price Period (per)', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default'     => esc_html__('month', 'essential-addons-for-elementor-lite'),
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_pricing_table_period_separator',
            [
                'label'       => esc_html__('Period Separator', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => false,
                'default'     => esc_html__('/', 'essential-addons-for-elementor-lite'),
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->end_controls_section();

        /**
         * Pricing Table Feature
         */
        $this->start_controls_section(
            'eael_section_pricing_table_feature',
            [
                'label' => esc_html__('Feature', 'essential-addons-for-elementor-lite'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'eael_pricing_table_item',
            [
                'label'       => esc_html__( 'List Item', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'label_block' => true,
                'default'     => esc_html__( 'Pricing table list item', 'essential-addons-for-elementor-lite' ),
                'ai' => [
					'active' => false,
				],
            ]
        );

        $repeater->add_control(
            'eael_pricing_table_list_icon_new',
            [
                'label'            => esc_html__( 'List Icon', 'essential-addons-for-elementor-lite' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_pricing_table_list_icon',
                'default'          => [
                    'value'   => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'eael_pricing_table_icon_mood',
            [
                'label'        => esc_html__( 'Item Active?', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $repeater->add_control(
            'eael_pricing_table_list_icon_color',
            [
                'label'   => esc_html__( 'Icon Color', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::COLOR,
                'default' => '#00C853',
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}} .li-icon i" => 'color: {{VALUE}};',
                    "{{WRAPPER}} {{CURRENT_ITEM}} .li-icon svg" => 'color: {{VALUE}} !important; fill: {{VALUE}} !important;',
                ],
            ]
        );

        $repeater->add_control(
			'eael_pricing_item_tooltip_heading',
			[
				'label'     => esc_html__( 'Tooltip', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $repeater->add_control(
            'eael_pricing_item_tooltip',
            [
                'label'        => esc_html__( 'Enable', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => false,
            ]
        );

        $repeater->add_control(
            'eael_pricing_item_tooltip_content',
            [
                'label'     => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::TEXTAREA,
                'dynamic'   => ['active' => true],
                'default'   => __( "I'm a awesome tooltip!!", 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_pricing_item_tooltip' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'eael_pricing_item_tooltip_side',
            [
                'label'     => esc_html__( 'Appearance', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'top'    => [
                        'title' => __( 'Top', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'right'  => [
                        'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                    'bottom' => [
                        'title' => __( 'Bottom', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'default'   => 'top',
                'condition' => [
                    'eael_pricing_item_tooltip' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'eael_pricing_item_tooltip_trigger',
            [
                'label'     => esc_html__( 'Trigger', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'hover' => __( 'Hover', 'essential-addons-for-elementor-lite' ),
                    'click' => __( 'Click', 'essential-addons-for-elementor-lite' ),
                ],
                'default'   => 'hover',
                'condition' => [
                    'eael_pricing_item_tooltip' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'eael_pricing_item_tooltip_animation',
            [
                'label'     => esc_html__( 'Animation Type', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'fade'  => __( 'Fade', 'essential-addons-for-elementor-lite' ),
                    'grow'  => __( 'Grow', 'essential-addons-for-elementor-lite' ),
                    'swing' => __( 'Swing', 'essential-addons-for-elementor-lite' ),
                    'slide' => __( 'Slide', 'essential-addons-for-elementor-lite' ),
                    'fall'  => __( 'Fall', 'essential-addons-for-elementor-lite' ),
                ],
                'default'   => 'fade',
                'condition' => [
                    'eael_pricing_item_tooltip' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'pricing_item_tooltip_animation_duration',
            [
                'label'     => esc_html__( 'Animation Duration', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => 300,
                'condition' => [
                    'eael_pricing_item_tooltip' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $repeater->add_control(
            'eael_pricing_table_toolip_arrow',
            [
                'label'        => esc_html__( 'Arrow', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'eael_pricing_item_tooltip' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'eael_pricing_item_tooltip_theme',
            [
                'label'     => esc_html__( 'Theme', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'default'    => __( 'Default', 'essential-addons-for-elementor-lite' ),
                    'noir'       => __( 'Noir', 'essential-addons-for-elementor-lite' ),
                    'light'      => __( 'Light', 'essential-addons-for-elementor-lite' ),
                    'punk'       => __( 'Punk', 'essential-addons-for-elementor-lite' ),
                    'shadow'     => __( 'Shadow', 'essential-addons-for-elementor-lite' ),
                    'borderless' => __( 'Borderless', 'essential-addons-for-elementor-lite' ),
                ],
                'default'   => 'noir',
                'condition' => [
                    'eael_pricing_item_tooltip' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_items',
            [
                'type'        => Controls_Manager::REPEATER,
                'seperator'   => 'before',
                'default'     => [
                    ['eael_pricing_table_item' => 'Unlimited calls'],
                    ['eael_pricing_table_item' => 'Free hosting'],
                    ['eael_pricing_table_item' => '500 MB of storage space'],
                    ['eael_pricing_table_item' => '500 MB Bandwidth'],
                    ['eael_pricing_table_item' => '24/7 support'],
                ],
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{eael_pricing_table_item}}',
            ]
        );

        $this->end_controls_section();

        /**
         * Pricing Table Footer
         */
        $this->start_controls_section(
            'eael_section_pricing_table_footerr',
            [
                'label' => esc_html__('Button', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_pricing_table_button_show',
            [
                'label'        => __('Display Button', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'eael_pricing_table_button_icon_new',
            [
                'label'            => esc_html__('Button Icon', 'essential-addons-for-elementor-lite'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_pricing_table_button_icon',
                'condition'        => [
                    'eael_pricing_table_button_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_button_icon_alignment',
            [
                'label'     => esc_html__('Icon Position', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'left',
                'options'   => [
                    'left'  => esc_html__('Before', 'essential-addons-for-elementor-lite'),
                    'right' => esc_html__('After', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_pricing_table_button_icon_new!' => '',
                    'eael_pricing_table_button_show'      => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_button_icon_indent',
            [
                'label'     => esc_html__('Icon Spacing', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 60,
                    ],
                ],
                'condition' => [
                    'eael_pricing_table_button_icon_new!' => '',
                    'eael_pricing_table_button_show'      => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing-button i.fa-icon-left'  => 'margin-right: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing-button i.fa-icon-right' => 'margin-left: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_btn',
            [
                'label'       => esc_html__('Button Text', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => ['active' => true],
                'default'     => esc_html__('Choose Plan', 'essential-addons-for-elementor-lite'),
                'condition'   => [
                    'eael_pricing_table_button_show' => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_pricing_table_btn_link',
            [
                'label'         => esc_html__('Button Link', 'essential-addons-for-elementor-lite'),
                'type'          => Controls_Manager::URL,
                'dynamic'   => ['active' => true],
                'label_block'   => true,
                'default'       => [
                    'url'         => '#',
                    'is_external' => '',
                ],
                'show_external' => true,
                'condition'     => [
                    'eael_pricing_table_button_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Pricing Table Rebon
         */
        $this->start_controls_section(
            'eael_section_pricing_table_featured',
            [
                'label' => esc_html__('Ribbon', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_pricing_table_featured',
            [
                'label'        => esc_html__('Featured?', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'eael_pricing_table_featured_styles',
            [
                'label'     => esc_html__('Ribbon Style', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'ribbon-1',
                'options'   => [
                    'ribbon-1' => esc_html__('Style 1', 'essential-addons-for-elementor-lite'),
                    'ribbon-2' => esc_html__('Style 2', 'essential-addons-for-elementor-lite'),
                    'ribbon-3' => esc_html__('Style 3', 'essential-addons-for-elementor-lite'),
                    'ribbon-4' => esc_html__('Style 4', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_pricing_table_featured' => 'yes',
                ],
            ]
        );

        /**
         * Condition: 'eael_pricing_table_featured_styles' => [ 'ribbon-2', 'ribbon-3', 'ribbon-4' ], 'eael_pricing_table_featured' => 'yes'
         */
        $this->add_control(
            'eael_pricing_table_featured_tag_text',
            [
                'label'       => esc_html__('Featured Tag Text', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => ['active' => true],
                'label_block' => false,
                'default'     => esc_html__('Featured', 'essential-addons-for-elementor-lite'),
                'selectors'   => [
                    '{{WRAPPER}} .eael-pricing.style-1 .eael-pricing-item.featured:before' => 'content: "{{VALUE}}";',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item.featured:before' => 'content: "{{VALUE}}";',
                    '{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.featured:before' => 'content: "{{VALUE}}";',
                    '{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item.featured:before' => 'content: "{{VALUE}}";',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item.featured:before' => 'content: "{{VALUE}}";',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-image.featured:before' => 'content: "{{VALUE}}";',
                ],
                'condition'   => [
                    'eael_pricing_table_featured_styles' => ['ribbon-2', 'ribbon-3', 'ribbon-4'],
                    'eael_pricing_table_featured'        => 'yes',
                ],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $this->add_control(
            'eael_pricing_table_ribbon_alignment',
            [
                'label'     => __('Ribbon Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                    'left'  => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'right',
                'condition' => [
                    'eael_pricing_table_featured_styles' => ['ribbon-4'],
                    'eael_pricing_table_featured'        => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        if (!apply_filters('eael/pro_enabled', false)) {
            $this->start_controls_section(
                'eael_section_pro',
                [
                    'label' => __('Go Premium for More Features', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'eael_control_get_pro',
                [
                    'label'       => __('Unlock more possibilities', 'essential-addons-for-elementor-lite'),
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

        /**
         * -------------------------------------------
         * Tab Style (Pricing Table Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_pricing_table_style_settings',
            [
                'label' => esc_html__('Pricing Table Style', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_pricing_table_bg_color',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_pricing_table_container_padding',
            [
                'label'      => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_pricing_table_container_margin',
            [
                'label'      => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_pricing_table_border',
                'label'    => esc_html__('Border Type', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-pricing .eael-pricing-item',
            ]
        );

        $this->add_control(
            'eael_pricing_table_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 4,
                ],
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing' => 'border-radius: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing .eael-pricing-item' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'eael_pricing_table_shadow',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-item',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_pricing_table_content_alignment',
            [
                'label'        => esc_html__('Content Alignment', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => true,
                'options'      => [
                    'left'   => [
                        'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'      => 'center',
                'prefix_class' => 'eael-pricing-content-align%s-',
            ]
        );

        $this->add_responsive_control(
            'eael_pricing_table_content_button_alignment',
            [
                'label'        => esc_html__('Button Alignment', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => true,
                'options'      => [
                    'left'   => [
                        'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'      => 'center',
                'prefix_class' => 'eael-pricing-button-align%s-',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Style (Header)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_pricing_table_header_style_settings',
            [
                'label' => esc_html__('Header', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_pricing_table_title_heading',
            [
                'label' => esc_html__('Title Style', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_pricing_table_title_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing-item .header .title'                            => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item:hover .header:after' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_style_2_title_bg_color',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#C8E6C9',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item .header' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item .header' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_pricing_table_style' => ['style-2'],
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_style_1_title_line_color',
            [
                'label'     => esc_html__('Line Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#dbdbdb',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-1 .eael-pricing-item .header:after, {{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item .header:after' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_pricing_table_style' => ['style-1', 'style-3'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_pricing_table_title_typography',
                'selector' => '{{WRAPPER}} .eael-pricing-item .header .title',
            ]
        );

        $this->add_control(
            'eael_pricing_table_subtitle_heading',
            [
                'label'     => esc_html__('Subtitle Style', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'eael_pricing_table_style!' => 'style-1',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_subtitle_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing-item .header .subtitle' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_pricing_table_style!' => 'style-1',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_pricing_table_subtitle_typography',
                'selector'  => '{{WRAPPER}} .eael-pricing-item .header .subtitle',
                'condition' => [
                    'eael_pricing_table_style!' => 'style-1',
                ],
            ]

        );

        $this->add_control(
            'eael_pricing_table_header_bg_heading',
            [
                'label'     => esc_html__('Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'eael_pricing_table_style!' => apply_filters('eael_pricing_table_header_bg_supported_style', ['style-1', 'style-2']),
                ],
            ]
        );
        $this->add_control(
            'eael_pricing_table_header_radius',
            [
                'label'      => __('Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_pricing_table_style' => apply_filters('eael_pricing_table_header_radius_supported_style', []),
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'eael_pricing_table_header_bg',
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item .header, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .header',
                'condition' => [
                    'eael_pricing_table_style' => apply_filters('eael_pricing_table_header_bg_supported_style', ['style-4']),
                ],
            ]
        );

        $this->end_controls_section();

        do_action('eael_pricing_table_control_header_extra_layout', $this);

        /**
         * -------------------------------------------
         * Style (Pricing)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_pricing_table_title_style_settings',
            [
                'label' => esc_html__('Pricing', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        // original price
        $this->add_control(
            'eael_pricing_table_price_tag_onsale_heading',
            [
                'label'     => esc_html__('Original Price', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_pricing_table_pricing_onsale_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#999',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing-item .eael-pricing-tag .price-tag .original-price, {{WRAPPER}} .eael-pricing-item .eael-pricing-tag .price-tag .original-price .price-currency' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_pricing_table_price_tag_onsale_typography',
                'selector' => '{{WRAPPER}} .eael-pricing-item .eael-pricing-tag .price-tag .original-price',
            ]
        );

        $this->add_control(
            'eael_pricing_table_original_price_currency_heading',
            [
                'label'     => esc_html__('Original Price Currency', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_pricing_table_original_price_currency_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing-item .eael-pricing-tag .price-tag .original-price .price-currency' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_pricing_table_original_price_currency_typography',
                'selector' => '{{WRAPPER}} .eael-pricing-item .eael-pricing-tag .price-tag .original-price .price-currency',
            ]
        );

        $this->add_responsive_control(
            'eael_pricing_table_original_price_currency_margin',
            [
                'label'      => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-pricing-item .eael-pricing-tag .price-tag .original-price .price-currency' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // sale price
        $this->add_control(
            'eael_pricing_table_price_tag_heading',
            [
                'label'     => esc_html__('Sale Price', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_pricing_table_pricing_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#00C853',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing-item .eael-pricing-tag .price-tag .sale-price, {{WRAPPER}} .eael-pricing-item .eael-pricing-tag .price-tag .sale-price .price-currency' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_pricing_table_price_tag_typography',
                'selector' => '{{WRAPPER}} .eael-pricing-item .eael-pricing-tag .price-tag .sale-price',
            ]
        );

        $this->add_control(
            'eael_pricing_table_price_currency_heading',
            [
                'label'     => esc_html__('Sale Price Currency', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_pricing_table_pricing_curr_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing-item .eael-pricing-tag .price-tag .sale-price .price-currency' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_pricing_table_price_cur_typography',
                'selector' => '{{WRAPPER}} .eael-pricing-item .eael-pricing-tag .price-tag .sale-price .price-currency',
            ]
        );

        $this->add_responsive_control(
            'eael_pricing_table_price_cur_margin',
            [
                'label'      => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-pricing-item .eael-pricing-tag .price-tag .sale-price .price-currency' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_pricing_period_heading',
            [
                'label'     => esc_html__('Pricing Period', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_pricing_table_pricing_period_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing-item .price-period' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_pricing_table_price_preiod_typography',
                'selector' => '{{WRAPPER}} .eael-pricing-item .price-period',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Style (Feature List)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_pricing_table_style_featured_list_settings',
            [
                'label' => esc_html__('Feature List', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_pricing_table_list_item_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing-item .body ul li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_list_disable_item_color',
            [
                'label'     => esc_html__('Disable item color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-item ul li.disable-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_list_item_icon_size',
            [
                'label'     => esc_html__('Icon Size', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing-item .body ul li .li-icon img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-pricing-item .body ul li .li-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-pricing-item .body ul li .li-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_list_item_icon_size_svg',
            [
                'label'     => esc_html__('SVG Icon Size', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing-item .body ul li .li-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-pricing-item .body ul li .li-icon svg'   => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_pricing_table_list_item_typography',
                'selector' => '{{WRAPPER}} .eael-pricing-item .body ul li',
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Style (Ribbon)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_pricing_table_style_3_featured_tag_settings',
            [
                'label' => esc_html__('Ribbon', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_pricing_table_featured' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_style_1_featured_bar_color',
            [
                'label'     => esc_html__('Line Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#00C853',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-1 .eael-pricing-item.ribbon-1:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item.ribbon-1:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.ribbon-1:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item.ribbon-1:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item.ribbon-1:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-image.ribbon-1:before' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_pricing_table_featured'        => 'yes',
                    'eael_pricing_table_featured_styles' => 'ribbon-1',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_style_1_featured_bar_height',
            [
                'label'     => esc_html__('Line Height', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 3,
                ],
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-1 .eael-pricing-item.ribbon-1:before' => 'height: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item.ribbon-1:before' => 'height: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.ribbon-1:before' => 'height: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item.ribbon-1:before' => 'height: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item.ribbon-1:before' => 'height: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-image.ribbon-1:before' => 'height: {{SIZE}}px;',
                ],
                'condition' => [
                    'eael_pricing_table_featured'        => 'yes',
                    'eael_pricing_table_featured_styles' => 'ribbon-1',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_featured_tag_font_size',
            [
                'label'     => esc_html__('Font Size', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 10,
                ],
                'range'     => [
                    'px' => [
                        'max' => 18,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-1 .eael-pricing-item.ribbon-2:before' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item.ribbon-2:before' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.ribbon-2:before' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item.ribbon-2:before' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item.ribbon-2:before' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-image.ribbon-2:before' => 'font-size: {{SIZE}}px;',

                    '{{WRAPPER}} .eael-pricing.style-1 .eael-pricing-item.ribbon-3:before' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item.ribbon-3:before' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.ribbon-3:before' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item.ribbon-3:before' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item.ribbon-3:before' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-image.ribbon-3:before' => 'font-size: {{SIZE}}px;',

                    '{{WRAPPER}} .eael-pricing .eael-pricing-item.ribbon-4:before'         => 'font-size: {{SIZE}}px;',

                    '{{WRAPPER}} .eael-pricing .eael-pricing-image.ribbon-4:before'         => 'font-size: {{SIZE}}px;',

                ],
                'condition' => [
                    'eael_pricing_table_featured'        => 'yes',
                    'eael_pricing_table_featured_styles' => ['ribbon-2', 'ribbon-3', 'ribbon-4'],
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_featured_tag_text_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-1 .eael-pricing-item.ribbon-2:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item.ribbon-2:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.ribbon-2:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item.ribbon-2:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item.ribbon-2:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-image.ribbon-2:before' => 'color: {{VALUE}};',

                    '{{WRAPPER}} .eael-pricing.style-1 .eael-pricing-item.ribbon-3:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item.ribbon-3:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.ribbon-3:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item.ribbon-3:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item.ribbon-3:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-image.ribbon-3:before' => 'color: {{VALUE}};',


                    '{{WRAPPER}} .eael-pricing .eael-pricing-item.ribbon-4:before'         => 'color: {{VALUE}};',

                    '{{WRAPPER}} .eael-pricing .eael-pricing-image.ribbon-4:before'         => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_pricing_table_featured'        => 'yes',
                    'eael_pricing_table_featured_styles' => ['ribbon-2', 'ribbon-3', 'ribbon-4'],
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_featured_tag_bg_color',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-1 .eael-pricing-item.ribbon-2:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-1 .eael-pricing-item.ribbon-2:after'  => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-1 .eael-pricing-item.ribbon-3:before' => 'background: {{VALUE}};',

                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item.ribbon-2:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item.ribbon-2:after'  => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item.ribbon-3:before' => 'background: {{VALUE}};',

                    '{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.ribbon-2:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.ribbon-2:after'  => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.ribbon-3:before' => 'background: {{VALUE}};',

                    '{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item.ribbon-2:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item.ribbon-2:after'  => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item.ribbon-3:before' => 'background: {{VALUE}};',

                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item.ribbon-2:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item.ribbon-2:after'  => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item.ribbon-3:before' => 'background: {{VALUE}};',

                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-image.ribbon-2:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-image.ribbon-2:after'  => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-5 .eael-pricing-image.ribbon-3:before' => 'background: {{VALUE}};',

                    '{{WRAPPER}} .eael-pricing .eael-pricing-item.ribbon-4:before'         => 'background: {{VALUE}};',

                    '{{WRAPPER}} .eael-pricing .eael-pricing-image.ribbon-4:before'         => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_pricing_table_featured'        => 'yes',
                    'eael_pricing_table_featured_styles' => ['ribbon-2', 'ribbon-3', 'ribbon-4'],
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'eael_pricing_table_featured_tag_bg_shadow',
                'label'     => __('Shadow', 'essential-addons-for-elementor-lite'),
                'selector'  => '{{WRAPPER}} .eael-pricing .eael-pricing-item.ribbon-4:before',
                'condition' => [
                    'eael_pricing_table_featured'        => 'yes',
                    'eael_pricing_table_featured_styles' => ['ribbon-4'],
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Tooltip Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_pricing_table_tooltip_style',
            [
                'label' => esc_html__('Tooltip', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_pricing_table_tooltip_typography',
                'selector' => '.tooltipster-base.tooltipster-sidetip .tooltipster-content',
            ]
        );

        $this->add_control(
            'eael_pricing_table_tooltip_bg_color',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    'div.tooltipster-base.tooltipster-sidetip .tooltipster-box' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_tooltip_arrow_bg',
            [
                'label'     => esc_html__('Arrow Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#3d3d3d',
                'selectors' => [
                    'div.tooltipster-base.tooltipster-sidetip.tooltipster-top .tooltipster-arrow-border,
					div.tooltipster-base.tooltipster-sidetip.tooltipster-top .tooltipster-arrow-background'                                                                                  => 'border-top-color: {{VALUE}};',
                    'div.tooltipster-base.tooltipster-sidetip.tooltipster-right .tooltipster-arrow-border, .tooltipster-base.tooltipster-sidetip.tooltipster-right .tooltipster-arrow-background' => 'border-right-color: {{VALUE}};',
                    'div.tooltipster-base.tooltipster-sidetip.tooltipster-left .tooltipster-arrow-border,
					div.tooltipster-base.tooltipster-sidetip.tooltipster-left .tooltipster-arrow-background'                                                                                 => 'border-left-color: {{VALUE}};',
                    'div.tooltipster-base.tooltipster-sidetip.tooltipster-bottom .tooltipster-arrow-border,
					div.tooltipster-base.tooltipster-sidetip.tooltipster-bottom .tooltipster-arrow-background'                                                                               => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_tooltip_color',
            [
                'label'     => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    'div.tooltipster-base.tooltipster-sidetip .tooltipster-box .tooltipster-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_pricing_table_tooltip_padding',
            [
                'label'       => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::DIMENSIONS,
                'size_units'  => 'px',
                'description' => __('Refresh your browser after saving the padding value for see changes.', 'essential-addons-for-elementor-lite'),
                'selectors'   => [
                    'div.tooltipster-base.tooltipster-sidetip .tooltipster-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_pricing_table_tooltip_border',
                'label'    => esc_html__('Border Type', 'essential-addons-for-elementor-lite'),
                'selector' => '.tooltipster-base.tooltipster-sidetip .tooltipster-box',
            ]
        );

        $this->add_control(
            'eael_pricing_table_tooltip_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    '%'  => [
                        'max'  => 100,
                        'step' => 1,
                    ],
                    'px' => [
                        'max'  => 200,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '.tooltipster-base.tooltipster-sidetip .tooltipster-box' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_tooltip_arrow_heading',
            [
                'label'     => __('Tooltip Arrow', 'essential-addons-for-elementor-lite'),
                'separator' => 'before',
                'type'      => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_pricing_table_tooltip_arrow_size',
            [
                'label'     => esc_html__('Arrow Size', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max'  => 45,
                        'step' => 1,
                    ],
                ],
                'selectors' => [

                    // Right Position Arrow
                    'div.tooltipster-base.tooltipster-sidetip.tooltipster-right .tooltipster-arrow'                                                                             => 'width: calc( {{SIZE}}px * 2); height: calc( {{SIZE}}px * 2); margin-top: calc( (-{{SIZE}}px * 2) / 2 ); left: calc( (-{{SIZE}}px * 2) / 2 );',
                    'div.tooltipster-sidetip.tooltipster-right .tooltipster-box'                                                                                                => 'margin-left: calc({{SIZE}}px - 10px);',
                    'div.tooltipster-base.tooltipster-sidetip.tooltipster-right .tooltipster-arrow-background,.tooltipster-sidetip.tooltipster-right .tooltipster-arrow-border' => 'border: {{SIZE}}px solid transparent;',

                    // Left Position Arrow
                    '.tooltipster-sidetip.tooltipster-base.tooltipster-left .tooltipster-arrow'                                                                                 => 'width: calc( {{SIZE}}px * 2); height: calc( {{SIZE}}px * 2); margin-top: calc( (-{{SIZE}}px * 2) / 2 ); right: calc( (-{{SIZE}}px * 2) / 2 );',
                    'div.tooltipster-sidetip.tooltipster-left .tooltipster-box'                                                                                                 => 'margin-right: calc({{SIZE}}px - 1px);',
                    'div.tooltipster-base.tooltipster-sidetip.tooltipster-left .tooltipster-arrow-background, .tooltipster-sidetip.tooltipster-left .tooltipster-arrow-border'  => 'border: {{SIZE}}px solid transparent;',

                    // Top Position Arrow
                    '.tooltipster-sidetip.tooltipster-base.tooltipster-top .tooltipster-arrow'                                                                                  => 'width: calc( {{SIZE}}px * 2); height: calc( {{SIZE}}px * 2); margin-left: calc( (-{{SIZE}}px * 2) / 2 ); left: 40%;top: 100%;',
                    'div.tooltipster-sidetip.tooltipster-top .tooltipster-box'                                                                                                  => 'margin-bottom: -1px;',
                    'div.tooltipster-base.tooltipster-sidetip.tooltipster-top .tooltipster-arrow-background, .tooltipster-sidetip.tooltipster-top .tooltipster-arrow-border'    => 'border: {{SIZE}}px solid transparent;',

                    // Bottom Position Arrow
                    '.tooltipster-sidetip.tooltipster-base.tooltipster-bottom .tooltipster-arrow'                                                                               => 'width: calc( {{SIZE}}px * 2); height: calc( {{SIZE}}px * 2); margin-left: calc( (-{{SIZE}}px * 2) / 2 ); left: 40%; top: auto; bottom: 88%;',

                    'div.tooltipster-base.tooltipster-sidetip.tooltipster-bottom .tooltipster-arrow-background,
					.tooltipster-sidetip.tooltipster-bottom .tooltipster-arrow-border'                                                                                     => 'border: {{SIZE}}px solid transparent;',

                ],
            ]
        );
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Pricing Table Icon Style)
         * Condition: 'eael_pricing_table_style' => 'style-2, style-5'
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_pricing_table_icon_settings',
            [
                'label'     => esc_html__('Icon Settings', 'essential-addons-for-elementor-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_pricing_table_style' => apply_filters('eael_pricing_table_icon_supported_style', ['style-2']),
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_icon_bg_show',
            [
                'label'        => __('Show Background', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        /**
         * Condition: 'eael_pricing_table_icon_bg_show' => 'yes'
         */
        $this->add_control(
            'eael_pricing_table_icon_bg_color',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item .eael-pricing-icon .icon, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .eael-pricing-icon .icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_pricing_table_icon_bg_show' => 'yes',
                ],
            ]
        );

        /**
         * Condition: 'eael_pricing_table_icon_bg_show' => 'yes'
         */
        $this->add_control(
            'eael_pricing_table_icon_bg_hover_color',
            [
                'label'     => esc_html__('Background Hover Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item:hover .eael-pricing-icon .icon, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item:hover .eael-pricing-icon .icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_pricing_table_icon_bg_show' => 'yes',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_responsive_control(
            'eael_pricing_table_icon_settings',
            [
                'label'     => esc_html__('Icon Size', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 30,
                ],
                'range'     => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item .eael-pricing-icon .icon i, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .eael-pricing-icon .icon i'     => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item .eael-pricing-icon .icon img, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .eael-pricing-icon .icon img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item .eael-pricing-icon .icon svg, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .eael-pricing-icon .icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_pricing_table_icon_area_width',
            [
                'label'      => esc_html__('Icon Area Width', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'default'    => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'range'      => [
                    'px' => [
                        'max' => 500,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item .eael-pricing-icon .icon, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .eael-pricing-icon .icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_pricing_table_icon_area_height',
            [
                'label'     => esc_html__('Icon Area Height', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 80,
                ],
                'range'     => [
                    'px' => [
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item .eael-pricing-icon .icon, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .eael-pricing-icon .icon' => 'height: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item .eael-pricing-icon .icon i, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .eael-pricing-icon .icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item .eael-pricing-icon .icon svg, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .eael-pricing-icon .icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_icon_hover_color',
            [
                'label'     => esc_html__('Icon Hover Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item:hover .eael-pricing-icon .icon i, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item:hover .eael-pricing-icon .icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item:hover .eael-pricing-icon .icon svg, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item:hover .eael-pricing-icon .icon svg' => 'fill: {{VALUE}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_pricing_table_icon_border',
                'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item .eael-pricing-icon .icon, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .eael-pricing-icon .icon',
            ]
        );

        $this->add_control(
            'eael_pricing_table_icon_border_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item:hover .eael-pricing-icon .icon, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item:hover .eael-pricing-icon .icon' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_pricing_table_icon_border_border!' => '',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_icon_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 50,
                ],
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item .eael-pricing-icon .icon, {{WRAPPER}} .eael-pricing.style-5 .eael-pricing-item .eael-pricing-icon .icon' => 'border-radius: {{SIZE}}%;',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style (Button Style)
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_pricing_table_btn_style_settings',
            [
                'label' => esc_html__('Button', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eael_pricing_table_btn_padding',
            [
                'label'      => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_pricing_table_btn_margin',
            [
                'label'      => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-pricing .footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_btn_icon_size',
            [
                'label'     => esc_html__('Icon Size', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-button img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-pricing .eael-pricing-button svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-pricing .eael-pricing-button i'   => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_pricing_table_btn_typography',
                'selector' => '{{WRAPPER}} .eael-pricing .eael-pricing-button',
            ]
        );

        $this->add_control(
            'eael_is_button_gradient_background',
            [
                'label'        => __('Button Gradient Background', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->start_controls_tabs('eael_cta_button_tabs');

        // Normal State Tab
        $this->start_controls_tab('eael_pricing_table_btn_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_pricing_table_btn_normal_text_color',
            [
                'label'     => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing .eael-pricing-button svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_btn_normal_bg_color',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#00C853',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-button' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_is_button_gradient_background' => '',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'      => 'eael_pricing_table_btn_normal_bg_gradient',
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'types'     => ['gradient'],
                'selector'  => '{{WRAPPER}} .eael-pricing .eael-pricing-button',
                'condition' => [
                    'eael_is_button_gradient_background' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_pricing_table_btn_border',
                'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-pricing .eael-pricing-button',
            ]
        );

        $this->add_control(
            'eael_pricing_table_btn_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-button' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State Tab
        $this->start_controls_tab('eael_pricing_table_btn_hover', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'eael_pricing_table_btn_hover_text_color',
            [
                'label'     => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f9f9f9',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-pricing .eael-pricing-button:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_btn_hover_bg_color',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#03b048',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-button:hover' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'eael_is_button_gradient_background' => '',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name'      => 'eael_pricing_table_btn_hover_bg_gradient',
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'types'     => ['gradient'],
                'selector'  => '{{WRAPPER}} .eael-pricing .eael-pricing-button:hover',
                'condition' => [
                    'eael_is_button_gradient_background' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_pricing_table_btn_hover_border_color',
            [
                'label'     => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-pricing .eael-pricing-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]

        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'eael_cta_button_shadow',
                'selector'  => '{{WRAPPER}} .eael-pricing .eael-pricing-button',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    public function render_pricing_list_icon( $settings, $item ){
        if ('show' === $settings['eael_pricing_table_icon_enabled']) :
            $icon_color = HelperClass::eael_fetch_color_or_global_color($item, 'eael_pricing_table_list_icon_color');
            ?>

            <span class="li-icon" style="<?php echo 'color:'. esc_attr( $icon_color ) . ';fill:' . esc_attr( $icon_color ) . ';'; ?>" >
                <?php
                if ( isset( $item['__fa4_migrated']['eael_pricing_table_list_icon_new'] ) || empty( $item['eael_pricing_table_list_icon'] ) ) {
                    Icons_Manager::render_icon( $item['eael_pricing_table_list_icon_new'], [ 'aria-hidden' => 'true' ] );
                } else {
                    echo '<i class="'. esc_attr( $item['eael_pricing_table_list_icon'] ) .'"></i>';
                }
                ?>
            </span>

        <?php endif;
    }

    public function render_feature_list($settings, $obj)
    {
        if (empty($settings['eael_pricing_table_items'])) {
            return;
        }

        $counter = 0;
?>
        <ul>
            <?php
            foreach ($settings['eael_pricing_table_items'] as $item) :

                $obj->add_render_attribute(
                    'pricing_feature_item' . $counter, 
                    [ 
                        'class' => 'elementor-repeater-item-' . esc_attr( $item['_id'] ),
                    ]
                );

                if ('yes' !== $item['eael_pricing_table_icon_mood']) {
                    $obj->add_render_attribute('pricing_feature_item' . $counter, 'class', 'disable-item');
                }

                if ('yes' === $item['eael_pricing_item_tooltip']) {
                    $obj->add_render_attribute(
                        'pricing_feature_item_tooltip' . $counter,
                        [
                            'class' => 'eael-pricing-tooltip',
                            'title' => HelperClass::eael_wp_kses($item['eael_pricing_item_tooltip_content']),
                            'id'    => $obj->get_id() . $counter,
                        ]
                    );
                }

                if ('yes' == $item['eael_pricing_item_tooltip']) {

                    if ($item['eael_pricing_item_tooltip_side']) {
                        $obj->add_render_attribute('pricing_feature_item_tooltip' . $counter, 'data-side', $item['eael_pricing_item_tooltip_side']);
                    }

                    if ($item['eael_pricing_item_tooltip_trigger']) {
                        $obj->add_render_attribute('pricing_feature_item_tooltip' . $counter, 'data-trigger', $item['eael_pricing_item_tooltip_trigger']);
                    }

                    if ($item['eael_pricing_item_tooltip_animation']) {
                        $obj->add_render_attribute('pricing_feature_item_tooltip' . $counter, 'data-animation', $item['eael_pricing_item_tooltip_animation']);
                    }

                    if (!empty($item['pricing_item_tooltip_animation_duration'])) {
                        $obj->add_render_attribute('pricing_feature_item_tooltip' . $counter, 'data-animation_duration', $item['pricing_item_tooltip_animation_duration']);
                    }

                    if (!empty($item['eael_pricing_table_toolip_arrow'])) {
                        $obj->add_render_attribute('pricing_feature_item_tooltip' . $counter, 'data-arrow', $item['eael_pricing_table_toolip_arrow']);
                    }

                    if (!empty($item['eael_pricing_item_tooltip_theme'])) {
                        $obj->add_render_attribute('pricing_feature_item_tooltip' . $counter, 'data-theme', $item['eael_pricing_item_tooltip_theme']);
                    }
                }
            ?>
                <li <?php $obj->print_render_attribute_string('pricing_feature_item' . $counter); ?>>
                    
                    <?php 
                        $settings['eael_pricing_table_icon_placement'] = ! empty ( $settings['eael_pricing_table_icon_placement'] ) ? $settings['eael_pricing_table_icon_placement'] : 'left';
                        if( 'show' === $settings['eael_pricing_table_icon_enabled'] && 'left' === $settings['eael_pricing_table_icon_placement'] ){
                            $this->render_pricing_list_icon($settings, $item);
                        } 
                    ?>

                    <span <?php $obj->print_render_attribute_string('pricing_feature_item_tooltip' . $counter); ?>><?php echo wp_kses( $item['eael_pricing_table_item'], HelperClass::eael_allowed_tags() ); ?></span>
                    
                    <?php 
                        if( 'show' === $settings['eael_pricing_table_icon_enabled'] && 'right' === $settings['eael_pricing_table_icon_placement'] ){
                            $this->render_pricing_list_icon($settings, $item);
                        } 
                    ?> 
                </li>
            <?php
                $counter++;
            endforeach;
            ?>
        </ul>
    <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $featured_class = ('yes' === $settings['eael_pricing_table_featured'] ? 'featured ' . $settings['eael_pricing_table_featured_styles'] : '');
        $featured_class .= ($settings['eael_pricing_table_ribbon_alignment'] === 'left' ? ' ribbon-left' : '');
        $inline_style = $settings['eael_pricing_table_featured_styles'] === 'ribbon-4' && 'yes' === $settings['eael_pricing_table_featured'];
        $icon_position = $this->get_settings('eael_pricing_table_button_icon_alignment');
	    $settings['eael_pricing_table_price'] = ( $settings['eael_pricing_table_price'] === '0' ) ? '0' : HelperClass::eael_wp_kses( $settings[ 'eael_pricing_table_price' ] );
	    $settings['eael_pricing_table_onsale_price'] = HelperClass::eael_wp_kses($settings['eael_pricing_table_onsale_price']);
	    $settings['eael_pricing_table_price_cur'] = HelperClass::eael_wp_kses($settings['eael_pricing_table_price_cur']);
	    $settings['eael_pricing_table_btn'] = HelperClass::eael_wp_kses($settings['eael_pricing_table_btn']);
        $button_url = ! empty( $settings['eael_pricing_table_btn_link']['url'] ) ? $settings['eael_pricing_table_btn_link']['url'] : '';
        
        $this->add_render_attribute('eael_pricing_button', [ 'class' => [ 'eael-pricing-button' ] ]);

        if ( ! empty( $settings['eael_pricing_table_btn_link']['url'] ) ) {
            $this->add_link_attributes( 'eael_pricing_button', $settings['eael_pricing_table_btn_link'] );
        }

        if ('yes' === $settings['eael_pricing_table_onsale']) {
            if ($settings['eael_pricing_table_price_cur_placement'] == 'left') {
                $pricing = '<del class="original-price">
                    <span class="price-currency">'
                    . $settings['eael_pricing_table_price_cur'] .
                    '</span>' .
                    $settings['eael_pricing_table_price'] .
                    '</del>
                <span class="sale-price">
                    <span class="price-currency">' .
                    $settings['eael_pricing_table_price_cur'] .
                    '</span>' .
                    $settings['eael_pricing_table_onsale_price'] .
                    '</span>';
            } else if ($settings['eael_pricing_table_price_cur_placement'] == 'right') {
                $pricing = '<del class="original-price">' .
                    $settings['eael_pricing_table_price'] .
                    '<span class="price-currency">' .
                    $settings['eael_pricing_table_price_cur'] . '</span></del> ' .
                    '<span class="sale-price">' .
                    $settings['eael_pricing_table_onsale_price'] .
                    '<span class="price-currency">' .
                    $settings['eael_pricing_table_price_cur'] . '</span>
                </span>';
            }
        } else {
            if ($settings['eael_pricing_table_price_cur_placement'] == 'left') {
                $pricing = '<span class="original-price">' .
                    '<span class="price-currency">' .
                    $settings['eael_pricing_table_price_cur'] . '</span>' .
                    $settings['eael_pricing_table_price'] .
                    '</span>';
            } else if ($settings['eael_pricing_table_price_cur_placement'] == 'right') {
                $pricing = '<span class="original-price">' .
                    $settings['eael_pricing_table_price'] .
                    '<span class="price-currency">' . $settings['eael_pricing_table_price_cur'] . '</span>
                    </span>';
            }
        }
    ?>
        <?php if ('style-1' === $settings['eael_pricing_table_style']) : ?>
            <div class="eael-pricing style-1" <?php echo $inline_style ? 'style="overflow: hidden;"' : ''; ?>>
                <div class="eael-pricing-item <?php echo esc_attr($featured_class); ?>">
                    <div class="header">
                        <h2 class="title"><?php echo wp_kses( $settings['eael_pricing_table_title'], HelperClass::eael_allowed_tags() ); ?></h2>
                    </div>
                    <div class="eael-pricing-tag">
                        <?php 
                            $html = '<span class="price-tag">' . $pricing . '</span>';
                            $html .= '<span class="price-period">' . $settings['eael_pricing_table_period_separator'] . $settings['eael_pricing_table_price_period'] . '</span>';
                            echo wp_kses( $html, HelperClass::eael_allowed_tags() );
                        ?>
                    </div>
                    <div class="body">
                        <?php $this->render_feature_list($settings, $this); ?>
                    </div>
	                <?php if( isset( $settings['eael_pricing_table_button_show'] ) && 'yes' === $settings['eael_pricing_table_button_show'] ): ?>
                    <div class="footer">
                        <a <?php $this->print_render_attribute_string('eael_pricing_button'); ?> >
                            <?php if ('left' == $icon_position) : ?>
                                <?php if (empty($settings['eael_pricing_table_button_icon']) || isset($settings['__fa4_migrated']['eael_pricing_table_button_icon_new'])) {
                                    Icons_Manager::render_icon( $settings['eael_pricing_table_button_icon_new'], [ 'class' => 'fa-icon-left' ] );
                                   } else { ?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon']); ?> fa-icon-left"></i>
                                <?php } ?>
                                <?php echo wp_kses( $settings['eael_pricing_table_btn'], HelperClass::eael_allowed_tags() ); ?>
                            <?php elseif ('right' == $icon_position) : ?>
                                <?php echo wp_kses( $settings['eael_pricing_table_btn'], HelperClass::eael_allowed_tags() ); ?>
                                <?php if (empty($settings['eael_pricing_table_button_icon']) || isset($settings['__fa4_migrated']['eael_pricing_table_button_icon_new'])) {
                                    Icons_Manager::render_icon( $settings['eael_pricing_table_button_icon_new'], [ 'class' => 'fa-icon-right' ] );
                                    } else { ?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon']); ?> fa-icon-right"></i>
                                <?php } ?>
                            <?php endif; ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ('style-2' === $settings['eael_pricing_table_style']) : ?>
            <div class="eael-pricing style-2" <?php echo $inline_style ? 'style="overflow: hidden;"' : ''; ?>>
                <div class="eael-pricing-item <?php echo esc_attr($featured_class); ?>">
                    <div class="eael-pricing-icon">
                        <span class="icon" style="background:<?php if ('yes' != $settings['eael_pricing_table_icon_bg_show']) : echo 'none';
                                                                endif; ?>;">
                            <?php if (empty($settings['eael_pricing_table_style_2_icon']) || isset($settings['__fa4_migrated']['eael_pricing_table_style_2_icon_new'])) {
                                Icons_Manager::render_icon( $settings['eael_pricing_table_style_2_icon_new'] );
                                } else { ?>
                                <i class="<?php echo esc_attr($settings['eael_pricing_table_style_2_icon']); ?>"></i>
                            <?php } ?>
                        </span>
                    </div>
                    <div class="header">
                        <?php 
                        $header_html = '<h2 class="title">' . $settings['eael_pricing_table_title'] . '</h2>';
                        $header_html .= '<span class="subtitle">' . $settings['eael_pricing_table_sub_title'] . '</span>';
                        echo wp_kses( $header_html, HelperClass::eael_allowed_tags() );
                        ?>
                    </div>
                    <div class="eael-pricing-tag">
                        <?php 
                            $html = '<span class="price-tag">' . $pricing . '</span>';
                            $html .= '<span class="price-period">' . $settings['eael_pricing_table_period_separator'] . $settings['eael_pricing_table_price_period'] . '</span>';
                            echo wp_kses( $html, HelperClass::eael_allowed_tags() );
                        ?>
                    </div>
                    <div class="body">
                        <?php $this->render_feature_list($settings, $this); ?>
                    </div>
	                <?php if( isset( $settings['eael_pricing_table_button_show'] ) && 'yes' === $settings['eael_pricing_table_button_show'] ): ?>
                    <div class="footer">
                        <a <?php $this->print_render_attribute_string('eael_pricing_button'); ?> >
                            <?php if ('left' == $icon_position) : ?>
                                <?php if (empty($settings['eael_pricing_table_button_icon']) || isset($settings['__fa4_migrated']['eael_pricing_table_button_icon_new'])) {
                                    Icons_Manager::render_icon( $settings['eael_pricing_table_button_icon_new'], [ 'class' => 'fa-icon-left'] );
                                } else { ?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon']); ?> fa-icon-left"></i>
                                <?php } ?>
                                <?php echo wp_kses( $settings['eael_pricing_table_btn'], HelperClass::eael_allowed_tags() ); ?>
                            <?php elseif ('right' == $icon_position) : ?>
                                <?php echo wp_kses( $settings['eael_pricing_table_btn'], HelperClass::eael_allowed_tags() ); ?>
                                <?php if (empty($settings['eael_pricing_table_button_icon']) || isset($settings['__fa4_migrated']['eael_pricing_table_button_icon_new'])) {
                                    Icons_Manager::render_icon( $settings['eael_pricing_table_button_icon_new'], [ 'class' => 'fa-icon-right'] );
                                } else { ?>
                                    <i class="<?php echo esc_attr($settings['eael_pricing_table_button_icon']); ?> fa-icon-right"></i>
                                <?php } ?>
                            <?php endif; ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php
        $depricated_param = $featured_class;
        do_action('add_pricing_table_style_block', $settings, $this, $pricing, $button_url, $featured_class, $depricated_param );
    }
}

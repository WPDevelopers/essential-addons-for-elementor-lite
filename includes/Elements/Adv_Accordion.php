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
use Elementor\Repeater;
use \Elementor\Plugin;
use \Elementor\Widget_Base;

use \Essential_Addons_Elementor\Classes\Helper;

class Adv_Accordion extends Widget_Base
{
    public function get_name()
    {
        return 'eael-adv-accordion';
    }

    public function get_title()
    {
        return esc_html__('Advanced Accordion', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-advanced-accordion';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'accordion',
            'ea accordion',
            'ea advanced accordion',
            'toggle',
            'collapsible',
            'faq',
            'faq schema',
            'group',
            'expand',
            'collapse',
            'ea',
            'essential addons',
        ];
    }

    public function get_style_depends()
    {
        return [
            'font-awesome-5-all',
            'font-awesome-4-shim',
        ];
    }

    public function get_script_depends()
    {
        return [
            'font-awesome-4-shim'
        ];
    }

    protected function is_dynamic_content():bool {
        if( Plugin::$instance->editor->is_edit_mode() ) {
            return false;
        }
        $accordion_tabs     = $this->get_settings('eael_adv_accordion_tab');
        $is_dynamic_content = false;
        if( ! empty( $accordion_tabs ) ){
            foreach( $accordion_tabs as $accordion_tab ){
                if( isset( $accordion_tab['eael_adv_accordion_text_type'] ) && 'template' == $accordion_tab['eael_adv_accordion_text_type'] ) {
                    $is_dynamic_content = true;
                    break;
                }
            }
        }

        if( ! $is_dynamic_content ) {
            $is_dynamic_content = 'yes' === $this->get_settings( 'eael_adv_accordion_faq_schema_show' );
        }

        return $is_dynamic_content;
    }

    public function has_widget_inner_wrapper(): bool {
        return ! Helper::eael_e_optimized_markup();
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/advanced-accordion/';
    }

    protected function register_controls()
    {
        /**
         * Content Tab Controls
         */
        $this->init_content_general_controls();
        $this->init_content_content_controls();
        $this->init_content_promotion_controls();

        /**
         * Style Tab Controls
         */
        $this->init_style_general_controls();
        $this->init_style_tab_controls();
        $this->init_style_tab_content_controls();
        $this->init_style_caret_controls();
    }

    protected function init_content_general_controls()
    {
        $this->start_controls_section(
            'eael_section_adv-accordion_settings',
            [
                'label' => esc_html__('General Settings', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_adv_accordion_type',
            [
                'label'       => esc_html__('Accordion Type', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'accordion',
                'label_block' => false,
                'options'     => [
                    'accordion' => esc_html__('Accordion', 'essential-addons-for-elementor-lite'),
                    'toggle'    => esc_html__('Toggle', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );
        $this->add_control(
            'eael_adv_accordion_title_tag',
            [
                'label'   => __('Tab Title Tag', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'span',
                'options' => [
                    'h1'   => __('H1', 'essential-addons-for-elementor-lite'),
                    'h2'   => __('H2', 'essential-addons-for-elementor-lite'),
                    'h3'   => __('H3', 'essential-addons-for-elementor-lite'),
                    'h4'   => __('H4', 'essential-addons-for-elementor-lite'),
                    'h5'   => __('H5', 'essential-addons-for-elementor-lite'),
                    'h6'   => __('H6', 'essential-addons-for-elementor-lite'),
                    'span' => __('Span', 'essential-addons-for-elementor-lite'),
                    'p'    => __('P', 'essential-addons-for-elementor-lite'),
                    'div'  => __('Div', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );
        $this->add_control(
            'eael_adv_accordion_icon_show',
            [
                'label'        => esc_html__('Enable Toggle Icon', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'eael_adv_accordion_toggle_icon_postion',
            [
                'label'        => esc_html__('Toggle Icon Postion', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Right', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Left', 'essential-addons-for-elementor-lite'),
                'default'      => 'right',
                'return_value' => 'right',
                'condition'    => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_adv_accordion_icon_new',
            [
                'label'            => esc_html__('Toggle Icon', 'essential-addons-for-elementor-lite'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_adv_accordion_icon',
                'default'          => [
                    'value'   => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
                'condition'        => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_accordion_toggle_speed',
            [
                'label'       => esc_html__('Toggle Speed (ms)', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::NUMBER,
                'label_block' => false,
                'default'     => 300,
            ]
        );

        $this->add_control(
            'eael_adv_accordion_custom_id_offset',
            [
                'label'       => esc_html__('Custom ID offset', 'essential-addons-for-elementor-lite'),
                'description' => esc_html__('Use offset to set the custom ID target scrolling position.', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::NUMBER,
                'label_block' => false,
                'default'     => 0,
                'min'         => 0,
            ]
        );

	    $this->add_control(
		    'eael_adv_accordion_scroll_speed',
		    [
			    'label'       => esc_html__('Scroll Speed (ms)', 'essential-addons-for-elementor-lite'),
			    'type'        => Controls_Manager::NUMBER,
			    'label_block' => false,
			    'default'     => 300,
		    ]
	    );

	    $this->add_control(
		    'eael_adv_accordion_scroll_onclick',
		    [
			    'label'        => esc_html__('Scroll on Click', 'essential-addons-for-elementor-lite'),
			    'type'         => Controls_Manager::SWITCHER,
			    'default'      => 'no',
			    'return_value' => 'yes',
		    ]
	    );

        $this->add_control(
            'eael_adv_accordion_faq_schema_show',
            [
                'label'        => esc_html__('Enable FAQ Schema', 'essential-addons-for-elementor-lite'),
                'description'  => esc_html__('For saved template, FAQ Schema Text can be added manually on each tab.', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->end_controls_section();
    }

    protected function init_content_content_controls()
    {
        $this->start_controls_section(
            'eael_section_adv_accordion_content_settings',
            [
                'label' => esc_html__('Content Settings', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_adv_accordion_content_source',
            [
                'label'       => __('Content Source', 'essential-addons-for-elementor-lite'),
				'label_block' => false,
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'dynamic' => [
						'title' => esc_html__( 'Dynamic', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-archive',
					],
					'custom' => [
						'title' => esc_html__( 'Custom', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-custom',
					],
				],
                'default'   => 'custom',
				'toggle'    => false,
			]
		);

        $this->add_control(
			'eael_adv_accordion_show_full_content',
			[
				'label'        => esc_html__( 'Show Full Content', 'textdomain' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
                'description'  => esc_html__( 'By Enabling this, Post\'s full content will be shown. By default it shows the excerpt.', 'essential-addons-for-elementor-lite' ),
                'condition'    => [
                    'eael_adv_accordion_content_source' => 'dynamic',
                ],
			]
		);

        $this->start_controls_tabs( 'eael_adv_accordion_icon_tabs',
            [
                'condition' => [
                    'eael_adv_accordion_content_source' => 'dynamic',
                ],
            ]
        );

		$this->start_controls_tab( 'eael_adv_accordion_open_icon_tab', 
            [ 
                'label' => esc_html__( 'Opened Tab Icon', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'eael_adv_accordion_open_icon',
            [
                'label'   => '',
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-minus',
                    'library' => 'fa-solid',
                ],
            ]
        );

		$this->end_controls_tab();

        $this->start_controls_tab( 'eael_adv_accordion_closed_icon_tab', 
            [ 
                'label' => esc_html__( 'Closed Tab Icon', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'eael_adv_accordion_close_icon',
            [
                'label'   => '',
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->end_controls_tabs();

        $repeater = new Repeater();

        $repeater->add_control(
            'eael_adv_accordion_tab_default_active',
            [
                'label' => esc_html__('Active as Default', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
            ]
        );

        $repeater->add_control(
            'eael_adv_accordion_tab_icon_show',
            [
                'label' => esc_html__('Enable Tab Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

		$repeater->start_controls_tabs( 'tab_icons_repeater' );

		$repeater->start_controls_tab( 'opened_tab', 
            [ 
                'label' => esc_html__( 'Opened Tab', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_adv_accordion_tab_icon_show' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'eael_adv_accordion_tab_title_icon_new_opened',
            [
                'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_adv_accordion_tab_title_icon_opened',
                'default' => [
                    'value' => 'fas fa-minus',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'eael_adv_accordion_tab_icon_show' => 'yes',
                ],
            ]
        );

		$repeater->end_controls_tab();

        $repeater->start_controls_tab( 'closed_tab', 
            [ 
                'label' => esc_html__( 'Closed Tab', 'essential-addons-for-elementor-lite' ),
                'condition' => [
                    'eael_adv_accordion_tab_icon_show' => 'yes',
                ],
            ]
        );

        $repeater->add_control(
            'eael_adv_accordion_tab_title_icon_new',
            [
                'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_adv_accordion_tab_title_icon',
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'eael_adv_accordion_tab_icon_show' => 'yes',
                    // 'eael_adv_accordion_tab_title_icon_new_active!' => 'opened'
                ],
            ]
        );

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

        $repeater->add_control(
            'eael_adv_accordion_tab_title',
            [
                'label' => esc_html__('Tab Title', 'essential-addons-for-elementor-lite'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Tab Title', 'essential-addons-for-elementor-lite'),
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'ai' => [
					'active' => false,
				],
            ]
        );


        $repeater->add_control(
            'eael_adv_accordion_text_type',
            [
                'label' => __('Content Type', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'content' => __('Content', 'essential-addons-for-elementor-lite'),
                    'template' => __('Saved Templates', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'content',
            ]
        );

	    $repeater->add_control(
		    'eael_primary_templates',
		    [
			    'name' => 'eael_primary_templates',
			    'label' => __('Choose Template', 'essential-addons-for-elementor-lite'),
			    'type' => 'eael-select2',
			    'source_name' => 'post_type',
			    'source_type' => 'elementor_library',
			    'label_block' => true,
			    'condition' => [
				    'eael_adv_accordion_text_type' => 'template',
			    ],
		    ]
	    );

        $repeater->add_control(
            'eael_adv_accordion_tab_content',
            [
                'name' => 'eael_adv_accordion_tab_content',
                'label' => esc_html__('Tab Content', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'essential-addons-for-elementor-lite'),
                'dynamic' => ['active' => true],
                'condition' => [
                    'eael_adv_accordion_text_type' => 'content',
                ],
            ]
        );

	    $repeater->add_control(
		    'eael_adv_accordion_tab_id',
		    [
			    'label' => esc_html__('Custom ID', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::TEXT,
			    'description' => esc_html__( 'Custom ID will be added as an anchor tag. For example, if you add ‘test’ as your custom ID, the link will become like the following: https://www.example.com/#test and it will open the respective tab directly.', 'essential-addons-for-elementor-lite' ),
			    'default' => '',
                'ai' => [
					'active' => false,
				],
		    ]
	    );

        $repeater->add_control(
            'eael_adv_accordion_tab_faq_schema_text',
            [
                'label' => esc_html__('FAQ Schema Text', 'essential-addons-for-elementor-lite'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'separator' => 'before',
                'ai' => [
					'active' => false,
				],
                'condition' => [
                    'eael_adv_accordion_text_type' => 'template',
                ],
            ]
        );

        $this->add_control(
            'eael_adv_accordion_tab',
            [
                'type'        => Controls_Manager::REPEATER,
                'seperator'   => 'before',
                'default'     => [
                    ['eael_adv_accordion_tab_title' => esc_html__('Accordion Tab Title 1', 'essential-addons-for-elementor-lite')],
                    ['eael_adv_accordion_tab_title' => esc_html__('Accordion Tab Title 2', 'essential-addons-for-elementor-lite')],
                    ['eael_adv_accordion_tab_title' => esc_html__('Accordion Tab Title 3', 'essential-addons-for-elementor-lite')],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{eael_adv_accordion_tab_title}}',
                'condition' => [
                    'eael_adv_accordion_content_source' => 'custom'
                ]
            ]
        );

        $this->end_controls_section();

        do_action( 'eael/controls/query', $this );
    }

    protected function init_content_promotion_controls()
    {
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
                            'icon' => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default'     => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
                ]
            );

            $this->end_controls_section();
        }
    }

    protected function init_style_general_controls()
    {
        $this->start_controls_section(
            'eael_section_adv_accordion_style_settings',
            [
                'label' => esc_html__('General Style', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_padding',
            [
                'label'      => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_margin',
            [
                'label'      => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_adv_accordion_border',
                'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_adv_accordion_box_shadow',
                'selector' => '{{WRAPPER}} .eael-adv-accordion',
            ]
        );
        $this->end_controls_section();
    }

    protected function init_style_tab_controls()
    {
        $this->start_controls_section(
            'eael_section_adv_accordions_tab_style_settings',
            [
                'label' => esc_html__('Tab Style', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_adv_accordion_tab_title_typography',
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .eael-accordion-tab-title',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_icon_size',
            [
                'label'      => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-accordion-icon'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header svg.fa-accordion-icon'   => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_icon_gap',
            [
                'label'      => __('Icon Gap', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-accordion-icon'   => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_adv_accordion_toggle_icon_postion' => 'right',
                ],
            ]
        );
        // after change toggle icon postion, tab icon will be also change postion then this control will be work
        $this->add_responsive_control(
            'eael_adv_accordion_tab_icon_gap_left',
            [
                'label'      => __('Icon Gap', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-accordion-icon'   => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_adv_accordion_toggle_icon_postion' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_adv_accordion_tab_distance',
            [
                'label'      => esc_html__('Distance', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_adv_accordion_tab_padding',
            [
                'label'      => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_margin',
            [
                'label'      => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('eael_adv_accordion_header_tabs');
        # Normal State Tab
        $this->start_controls_tab('eael_adv_accordion_header_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'eael_adv_accordion_tab_bgtype',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header',
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab_text_color',
            [
                'label'     => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .eael-accordion-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-accordion-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-accordion-icon-svg svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header svg' => 'fill: {{VALUE}};',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_adv_accordion_tab_border',
                'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        # Hover State Tab
        $this->start_controls_tab(
            'eael_adv_accordion_header_hover',
            [
                'label' => esc_html__('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'eael_adv_accordion_tab_bgtype_hover',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header:hover',
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab_text_color_hover',
            [
                'label'     => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header:hover .eael-accordion-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab_icon_color_hover',
            [
                'label'     => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header:hover .fa-accordion-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header:hover .fa-accordion-icon svg' => 'color: {{VALUE}}; fill: {{VALUE}}',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header:hover svg.fa-accordion-icon' => 'fill: {{VALUE}}',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_adv_accordion_tab_border_hover',
                'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header:hover',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_border_radius_hover',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();

        #Active State Tab
        $this->start_controls_tab(
            'eael_adv_accordion_header_active',
            [
                'label' => esc_html__('Active', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'eael_adv_accordion_tab_bgtype_active',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active',
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab_text_color_active',
            [
                'label'     => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active'                           => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active .eael-accordion-tab-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab_icon_color_active',
            [
                'label'     => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active .fa-accordion-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active .fa-accordion-icon svg' => 'color: {{VALUE}};fill: {{VALUE}}',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active svg.fa-accordion-icon' => 'fill: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_adv_accordion_tab_border_active',
                'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_border_radius_active',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function init_style_tab_content_controls()
    {
        $this->start_controls_section(
            'eael_section_adv_accordion_tab_content_style_settings',
            [
                'label' => esc_html__('Content Style', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'adv_accordion_content_bgtype',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content',
            ]
        );

        $this->add_control(
            'adv_accordion_content_text_color',
            [
                'label'     => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_adv_accordion_content_typography',
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_content_padding',
            [
                'label'      => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_content_margin',
            [
                'label'      => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_adv_accordion_content_border',
                'label'    => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'eael_adv_accordion_content_shadow',
                'selector'  => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content',
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
    }

    protected function init_style_caret_controls()
    {
        $this->start_controls_section(
            'eael_section_adv_accordion_caret_settings',
            [
                'label' => esc_html__('Toggle Caret Style', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eael_adv_accordion_tab_toggle_icon_size',
            [
                'label'      => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-toggle, {{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header > .fa-toggle-svg' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header svg.fa-toggle' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_toggle_icon_padding',
            [
                'label'      => __('Icon Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_toggle_icon_radius',
            [
                'label'      => __('Icon Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );

        // caret tabs
        $this->start_controls_tabs(
            'eael_adv_accordion_tab_caret_tabs'
        );

        $this->start_controls_tab(
            'eael_adv_accordion_tab_caret_tabs_normal',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_toggle_color',
            [
                'label'     => esc_html__('Caret Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#444',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-toggle' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-toggle svg' => 'color: {{VALUE}}; fill:{{VALUE}}',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header svg.fa-toggle' => 'fill:{{VALUE}}',
                ],
                'condition' => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_toggle_background_color',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-toggle' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_tabs_tab_toggle_border',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-toggle',
            ]
        );



        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_adv_accordion_tab_caret_tabs_hover',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_toggle_hover_color',
            [
                'label'     => esc_html__('Caret Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list:hover .eael-accordion-header .fa-toggle'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list:hover .eael-accordion-header .fa-toggle svg'  => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list:hover .eael-accordion-header svg.fa-toggle'  => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_toggle_hover_background_color',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list:hover .eael-accordion-header .fa-toggle'  => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_tabs_tab_toggle_border_hover',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-toggle:hover',
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_adv_accordion_tab_caret_tabs_active',
            [
                'label' => __('Active', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_toggle_active_color',
            [
                'label'     => esc_html__('Caret Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active .fa-toggle' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active .fa-toggle svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active svg.fa-toggle' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_toggle_active_background_color',
            [
                'label'     => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active .fa-toggle' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_tabs_tab_toggle_border_active',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active .fa-toggle',
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        // end caret tabs

        $this->end_controls_section();
    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $id_int = substr($this->get_id_int(), 0, 3);

        $this->add_render_attribute('eael-adv-accordion', 'class', 'eael-adv-accordion');
        $this->add_render_attribute('eael-adv-accordion', 'id', 'eael-adv-accordion-' . esc_attr($this->get_id()));
        $this->add_render_attribute('eael-adv-accordion', 'data-scroll-on-click', esc_attr( $settings['eael_adv_accordion_scroll_onclick'] ));
        $this->add_render_attribute('eael-adv-accordion', 'data-scroll-speed', esc_attr( $settings['eael_adv_accordion_scroll_speed'] ));

        if( !empty($settings['eael_adv_accordion_custom_id_offset']) ){
            $this->add_render_attribute('eael-adv-accordion', 'data-custom-id-offset', esc_attr( $settings['eael_adv_accordion_custom_id_offset'] ) );
        }
?>
        <div <?php $this->print_render_attribute_string('eael-adv-accordion'); ?> <?php echo 'data-accordion-id="' . esc_attr($this->get_id()) . '"'; ?> <?php echo !empty($settings['eael_adv_accordion_type']) ? 'data-accordion-type="' . esc_attr($settings['eael_adv_accordion_type']) . '"' : 'accordion'; ?> <?php echo !empty($settings['eael_adv_accordion_toggle_speed']) ? 'data-toogle-speed="' . esc_attr($settings['eael_adv_accordion_toggle_speed']) . '"' : '300'; ?>>
    <?php 
        if( 'dynamic' === $settings['eael_adv_accordion_content_source'] ) {
            $this->render_dynamic_content();
        } else {
            foreach ($settings['eael_adv_accordion_tab'] as $index => $tab) {
                if( empty( $tab['eael_adv_accordion_tab_title'] ) || ( 'content' == $tab['eael_adv_accordion_text_type'] && empty( $tab['eael_adv_accordion_tab_content'] ) ) ){
                    continue;
                }

                $tab_count = $index + 1;
                $tab_title_setting_key = $this->get_repeater_setting_key('eael_adv_accordion_tab_title', 'eael_adv_accordion_tab', $index);
                $tab_content_setting_key = $this->get_repeater_setting_key('eael_adv_accordion_tab_content', 'eael_adv_accordion_tab', $index);

                $tab_title_class = ['elementor-tab-title', 'eael-accordion-header'];
                $tab_content_class = ['eael-accordion-content', 'clearfix'];

                $tab_icon_migrated = isset($tab['__fa4_migrated']['eael_adv_accordion_tab_title_icon_new']);
                $tab_icon_is_new = empty($tab['eael_adv_accordion_tab_title_icon']);

                if ($tab['eael_adv_accordion_tab_default_active'] == 'yes') {
                    $tab_title_class[] = 'active-default';
                    $tab_content_class[] = 'active-default';
                }

                $tab_id = $tab['eael_adv_accordion_tab_id'] ? $tab['eael_adv_accordion_tab_id'] : Helper::str_to_css_id( $tab['eael_adv_accordion_tab_title'] );
                $tab_id = $tab_id === 'safari' ? 'eael-safari' : $tab_id;

                $this->add_render_attribute($tab_title_setting_key, [
                    'id'            => $tab_id,
                    'class'         => $tab_title_class,
                    'tabindex'      => 0,
                    'data-tab'      => $tab_count,
                    'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
                ]);

                $this->add_render_attribute($tab_content_setting_key, [
                    'id'              => 'elementor-tab-content-' . $id_int . $tab_count,
                    'class'           => $tab_content_class,
                    'data-tab'        => $tab_count,
    //                'role'            => 'tabpanel',
                    'aria-labelledby' => $tab_id,
                ]);

            echo '<div class="eael-accordion-list">
                <div '; $this->print_render_attribute_string($tab_title_setting_key); echo '>';
            // toggle icon if user set position to left
            if ($settings['eael_adv_accordion_icon_show'] === 'yes' && $settings['eael_adv_accordion_toggle_icon_postion'] === '') {
                $this->print_toggle_icon($settings);
            }
            // tab title
            if ($settings['eael_adv_accordion_toggle_icon_postion'] === '') {
                $title_tag = Helper::eael_validate_html_tag( $settings['eael_adv_accordion_title_tag'] );
                $title = '<' . $title_tag  . ' class="eael-accordion-tab-title">' . $tab['eael_adv_accordion_tab_title'] . '</' . $title_tag . '>';
                echo wp_kses( $title, Helper::eael_allowed_tags() );
            }
            // tab icon
            if ($tab['eael_adv_accordion_tab_icon_show'] === 'yes') {
                if ($tab_icon_is_new || $tab_icon_migrated) {
                    if ( 'svg' === $tab['eael_adv_accordion_tab_title_icon_new']['library'] ) {
                        echo '<span class="fa-accordion-icon fa-accordion-icon-svg eaa-svg eael-advanced-accordion-icon-closed">';
                        Icons_Manager::render_icon( $tab['eael_adv_accordion_tab_title_icon_new'] );
                        echo '</span>';
                    }else{
                        echo '<span class="eael-advanced-accordion-icon-closed">';
                        Icons_Manager::render_icon( $tab['eael_adv_accordion_tab_title_icon_new'], [ 'aria-hidden' => 'true', 'class' => "fa-accordion-icon" ] );
                        echo '</span>';
                    }

                        if ( 'svg' === $tab['eael_adv_accordion_tab_title_icon_new_opened']['library'] ) {
                            echo '<span class="fa-accordion-icon fa-accordion-icon-svg eaa-svg eael-advanced-accordion-icon-opened">';
                            Icons_Manager::render_icon( $tab['eael_adv_accordion_tab_title_icon_new_opened'] );
                            echo '</span>';
                        }else{
                            echo '<span class="eael-advanced-accordion-icon-opened">';
                            Icons_Manager::render_icon( $tab['eael_adv_accordion_tab_title_icon_new_opened'], [ 'aria-hidden' => 'true', 'class' => "fa-accordion-icon" ] );
                            echo '</span>';
                        }

                } else {
                    echo '<span class="eael-advanced-accordion-icon-closed"><i class="' . ( ! empty( $tab['eael_adv_accordion_tab_title_icon'] ) ? esc_attr( $tab['eael_adv_accordion_tab_title_icon'] ) : '' ) . ' fa-accordion-icon"></i></span>';
                    echo '<span class="eael-advanced-accordion-icon-opened"><i class="' . ( ! empty( $tab['eael_adv_accordion_tab_title_icon_opened'] ) ? esc_attr( $tab['eael_adv_accordion_tab_title_icon_opened'] ) : ' fa fa-minus ' ) . ' fa-accordion-icon"></i></span>';
                }
            }
            // tab title
            if ($settings['eael_adv_accordion_toggle_icon_postion'] === 'right' || $settings['eael_adv_accordion_toggle_icon_postion'] === null) {
                $title_tag = Helper::eael_validate_html_tag( $settings['eael_adv_accordion_title_tag'] );
                $title = '<' . $title_tag . ' class="eael-accordion-tab-title">' . $tab['eael_adv_accordion_tab_title'] . '</' . $title_tag . '>';
                echo wp_kses( $title, Helper::eael_allowed_tags() );
            }
            // toggle icon
            if ($settings['eael_adv_accordion_icon_show'] === 'yes' && $settings['eael_adv_accordion_toggle_icon_postion'] === 'right') {
                $this->print_toggle_icon( $settings );
            }
            echo '</div>';

            echo '<div ';  $this->print_render_attribute_string($tab_content_setting_key); echo '>';
            if ('content' == $tab['eael_adv_accordion_text_type']) {
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo $this->parse_text_editor( $tab['eael_adv_accordion_tab_content'] );
            } elseif ('template' == $tab['eael_adv_accordion_text_type']) {
                if ( ! empty( $tab['eael_primary_templates'] ) && Helper::is_elementor_publish_template( $tab['eael_primary_templates'] ) ) {
                    // WPML Compatibility
                    if ( ! is_array( $tab['eael_primary_templates'] ) ) {
                        $tab['eael_primary_templates'] = apply_filters( 'wpml_object_id', $tab['eael_primary_templates'], 'wp_template', true );
                    }

	                Helper::eael_onpage_edit_template_markup( get_the_ID(), $tab['eael_primary_templates'] );
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo Plugin::$instance->frontend->get_builder_content( $tab['eael_primary_templates'], true ); 
                }
            }
            echo '</div>
                </div>';
        }
        echo '</div>';
        
        // FAQ Schema
        if ( !empty( $settings['eael_adv_accordion_faq_schema_show'] ) && 'yes' === $settings['eael_adv_accordion_faq_schema_show'] ) {
            foreach ( $settings['eael_adv_accordion_tab'] as $index => $tab ) {
                $faq_schema_text = ! empty( $tab['eael_adv_accordion_tab_faq_schema_text'] ) ? $tab['eael_adv_accordion_tab_faq_schema_text'] : '';
                
                $faq = [
                    '@type' => 'Question',
                    'name' => Helper::eael_wp_kses( $tab['eael_adv_accordion_tab_title'] ),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => ('content' === $tab['eael_adv_accordion_text_type']) ? do_shortcode( $tab['eael_adv_accordion_tab_content'] ) : Helper::eael_wp_kses( $faq_schema_text ),
                    ],
                ];

                    Helper::set_eael_advanced_accordion_faq($faq);
                }	
            }
        }
    }

    protected function render_dynamic_content() {
        $settings = $this->get_settings_for_display();
        $settings = Helper::fix_old_query( $settings );
		$args     = Helper::get_query_args( $settings );
        $query    = new \WP_Query($args);
        $current_id = get_the_ID();
        $has_block = false;

        if ( $query->have_posts() ) {
            $tab_count = 0;
            while ( $query->have_posts() ) {
                $query->the_post();
                $tab_id = get_the_ID();
                if( $current_id === $tab_id ){
                    continue;
                }

                $tab_count++;

                $tab_title_setting_key   = 'eael_adv_accordion_title_' . $tab_id;
                $tab_content_setting_key = 'eael_adv_accordion_content_' . $tab_id;
                $tab_title_class         = ['elementor-tab-title', 'eael-accordion-header'];
                $tab_content_class       = ['eael-accordion-content', 'clearfix'];

                $this->add_render_attribute($tab_title_setting_key, [
                    'id'            => $tab_id,
                    'class'         => $tab_title_class,
                    'tabindex'      => 0,
                    'data-tab'      => $tab_count,
                    'aria-controls' => 'elementor-tab-content-' . $tab_id . '-' . $tab_count,
                ]);

                $this->add_render_attribute($tab_content_setting_key, [
                    'id'              => 'elementor-tab-content-' . $tab_id . '-' . $tab_count,
                    'class'           => $tab_content_class,
                    'data-tab'        => $tab_count,
    //                'role'            => 'tabpanel',
                    'aria-labelledby' => $tab_id,
                ]);

                echo '<div class="eael-accordion-list">';
                    echo '<div '; $this->print_render_attribute_string($tab_title_setting_key); echo '>';

                        // toggle icon if user set position to left
                        if ( 'yes' === $settings['eael_adv_accordion_icon_show'] && '' === $settings['eael_adv_accordion_toggle_icon_postion'] ) {
                            $this->print_toggle_icon($settings);
                        }
                        // tab title
                        if ( '' === $settings['eael_adv_accordion_toggle_icon_postion'] ) {
                            $title_tag = Helper::eael_validate_html_tag( $settings['eael_adv_accordion_title_tag'] );
                            printf( '<%1$s class="eael-accordion-tab-title">%2$s</%1$s>', esc_html( $title_tag ), wp_kses( get_the_title(), Helper::eael_allowed_tags() ) );
                        }
                        // tab icon
                        if ( isset( $settings['eael_adv_accordion_open_icon'] ) && !empty( $settings['eael_adv_accordion_open_icon'] ) ) {
                            echo '<span class="eael-advanced-accordion-icon-opened">';
                            Icons_Manager::render_icon( $settings['eael_adv_accordion_open_icon'], [ 'aria-hidden' => 'true', 'class' => "fa-accordion-icon" ] );
                            echo '</span>';
                        }
                        if ( isset( $settings['eael_adv_accordion_open_icon'] ) && !empty( $settings['eael_adv_accordion_open_icon'] ) ) {
                            echo '<span class="eael-advanced-accordion-icon-closed">';
                            Icons_Manager::render_icon( $settings['eael_adv_accordion_close_icon'], [ 'aria-hidden' => 'true', 'class' => "fa-accordion-icon" ] );
                            echo '</span>';
                        }
                        // tab title
                        if ( 'right' === $settings['eael_adv_accordion_toggle_icon_postion'] || null === $settings['eael_adv_accordion_toggle_icon_postion'] ) {
                            $title_tag = Helper::eael_validate_html_tag( $settings['eael_adv_accordion_title_tag'] );
                            printf( '<%1$s class="eael-accordion-tab-title">%2$s</%1$s>', esc_html( $title_tag ), wp_kses( get_the_title(), Helper::eael_allowed_tags() ) );
                        }
                        // toggle icon
                        if ( 'yes' === $settings['eael_adv_accordion_icon_show'] && 'right' === $settings['eael_adv_accordion_toggle_icon_postion'] ) {
                            $this->print_toggle_icon($settings);
                        }
                    echo '</div>';

                    echo '<div ' . $this->get_render_attribute_string($tab_content_setting_key) . '>';
                        if( isset( $settings['eael_adv_accordion_show_full_content'] ) && 'yes' === $settings['eael_adv_accordion_show_full_content'] ) {
                            $document = Plugin::instance()->documents->get( $tab_id );
                            if( $document && $document->is_built_with_elementor() ) {
                                echo Plugin::$instance->frontend->get_builder_content( $tab_id, true );
                            }
                            else if ( has_blocks( get_the_content() ) ) {
                                $has_block = true;
                                echo '<div class="eael-accordion-gutenberg-content">';
                                the_content();
                                echo '</div>';
                            } else {
                                the_content();
                            }
                        } else {
                            echo wp_kses( get_the_excerpt(), Helper::eael_allowed_tags() );
                        }
                        
                    echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="no-posts-found">'. esc_html__('No posts found!', 'essential-addons-elementor') .'</p>';
        }
        wp_reset_postdata();
        
        if ( $has_block ) {
            wp_enqueue_style( 'wp-block-library' );
            wp_enqueue_style( 'wp-block-library-theme' );
            wp_enqueue_style( 'wc-block-style' );
            wp_enqueue_style( 'wc-blocks-style' );
        }
    }
    

    protected function print_toggle_icon($settings)
    {
        $accordion_icon_migrated = isset($settings['__fa4_migrated']['eael_adv_accordion_icon_new']);
        $accordion_icon_is_new = empty($settings['eael_adv_accordion_icon']);
        if ($accordion_icon_is_new || $accordion_icon_migrated) {
            if ( 'svg' === $settings['eael_adv_accordion_icon_new']['library'] ) {
                echo '<span class="fa-toggle fa-toggle-svg eaa-svg">';
                Icons_Manager::render_icon( $settings['eael_adv_accordion_icon_new'] );
                echo '</span>';
            }else{
                Icons_Manager::render_icon( $settings['eael_adv_accordion_icon_new'], [ 'aria-hidden' => 'true', 'class' => "fa-toggle" ] );
            }

        } else {
	        echo '<i class="' . esc_attr( $settings['eael_adv_accordion_icon'] ) . ' fa-toggle"></i>';
        }
    }
}



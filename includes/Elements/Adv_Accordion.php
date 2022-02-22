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
                'label'   => __('Select Accordion Tab Title Tag', 'essential-addons-for-elementor-lite'),
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
            'eael_adv_accordion_faq_schema_show',
            [
                'label'        => esc_html__('Enable FAQ Schema', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'return_value' => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'eael_adv_accordion_faq_schema_type',
            [
                'label'       => esc_html__('Schema Type', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'faq',
                'label_block' => false,
                'options'     => [
                    'article' => esc_html__('Article', 'essential-addons-for-elementor-lite'),
                    'book'    => esc_html__('Book', 'essential-addons-for-elementor-lite'),
                    'breadcrumb'    => esc_html__('Breadcrumb', 'essential-addons-for-elementor-lite'),
                    'carousel'    => esc_html__('Carousel', 'essential-addons-for-elementor-lite'),
                    'course'    => esc_html__('Course', 'essential-addons-for-elementor-lite'),
                    'covid-19'    => esc_html__('Covid 19', 'essential-addons-for-elementor-lite'),
                    'announcements'    => esc_html__('Announcements', 'essential-addons-for-elementor-lite'),
                    'dataset'    => esc_html__('Dataset', 'essential-addons-for-elementor-lite'),
                    'employer-aggregate-rating'    => esc_html__('Employer Aggregate Rating', 'essential-addons-for-elementor-lite'),
                    'estimated-salary'    => esc_html__('Estimated Salary', 'essential-addons-for-elementor-lite'),
                    'event'    => esc_html__('Event', 'essential-addons-for-elementor-lite'),
                    'fact-check'    => esc_html__('Fact Check', 'essential-addons-for-elementor-lite'),
                    'faq'    => esc_html__('FAQ', 'essential-addons-for-elementor-lite'),
                    'home-activities'    => esc_html__('Home Activities', 'essential-addons-for-elementor-lite'),
                    'how-to'    => esc_html__('How To', 'essential-addons-for-elementor-lite'),
                    'image-license'    => esc_html__('Image License', 'essential-addons-for-elementor-lite'),
                    'job-posting'    => esc_html__('Job Posting', 'essential-addons-for-elementor-lite'),
                    'job-training'    => esc_html__('Job Training', 'essential-addons-for-elementor-lite'),
                    'local-business'    => esc_html__('Local Business', 'essential-addons-for-elementor-lite'),
                    'logo'    => esc_html__('Logo', 'essential-addons-for-elementor-lite'),
                    'math-solvers'    => esc_html__('Math Solvers', 'essential-addons-for-elementor-lite'),
                    'movie'    => esc_html__('Movie', 'essential-addons-for-elementor-lite'),
                    'practice-problems'    => esc_html__('Practice Problems', 'essential-addons-for-elementor-lite'),
                    'product'    => esc_html__('Product', 'essential-addons-for-elementor-lite'),
                    'q-and-a'    => esc_html__('Q&A', 'essential-addons-for-elementor-lite'),
                    'recipe'    => esc_html__('Recipe', 'essential-addons-for-elementor-lite'),
                    'review-snippet'    => esc_html__('Review Snippet', 'essential-addons-for-elementor-lite'),
                    'sitelinks-search-box'    => esc_html__('Sitelinks Search Box', 'essential-addons-for-elementor-lite'),
                    'software-app'    => esc_html__('Software App', 'essential-addons-for-elementor-lite'),
                    'speakable'    => esc_html__('Speakable', 'essential-addons-for-elementor-lite'),
                    'subscription-and-paywalled-content'    => esc_html__('Subscription And Paywalled Content', 'essential-addons-for-elementor-lite'),
                    'video'    => esc_html__('Video', 'essential-addons-for-elementor-lite'),
                ],
                'condition'    => [
                    'eael_adv_accordion_faq_schema_show' => 'yes',
                ],
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
                ],
            ]
        );

        $repeater->add_control(
            'eael_adv_accordion_tab_title',
            [
                'label' => esc_html__('Tab Title', 'essential-addons-for-elementor-lite'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Tab Title', 'essential-addons-for-elementor-lite'),
                'dynamic' => ['active' => true],
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
            ]
        );

        $this->end_controls_section();
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
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list:hover .eael-accordion-header .fa-toggle-svg svg'  => 'color: {{VALUE}}; fill: {{VALUE}};',
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
?>
        <div <?php echo $this->get_render_attribute_string('eael-adv-accordion'); ?> <?php echo 'data-accordion-id="' . esc_attr($this->get_id()) . '"'; ?> <?php echo !empty($settings['eael_adv_accordion_type']) ? 'data-accordion-type="' . esc_attr($settings['eael_adv_accordion_type']) . '"' : 'accordion'; ?> <?php echo !empty($settings['eael_adv_accordion_toggle_speed']) ? 'data-toogle-speed="' . esc_attr($settings['eael_adv_accordion_toggle_speed']) . '"' : '300'; ?>>
    <?php foreach ($settings['eael_adv_accordion_tab'] as $index => $tab) {
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
                'tabindex'      => $id_int . $tab_count,
                'data-tab'      => $tab_count,
                'role'          => 'tab',
                'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
            ]);

            $this->add_render_attribute($tab_content_setting_key, [
                'id'              => 'elementor-tab-content-' . $id_int . $tab_count,
                'class'           => $tab_content_class,
                'data-tab'        => $tab_count,
                'role'            => 'tabpanel',
                'aria-labelledby' => $tab_id,
            ]);

            echo '<div class="eael-accordion-list">
                <div ' . $this->get_render_attribute_string($tab_title_setting_key) . '>';
            // toggle icon if user set position to left
            if ($settings['eael_adv_accordion_icon_show'] === 'yes' && $settings['eael_adv_accordion_toggle_icon_postion'] === '') {
                $this->print_toggle_icon($settings);
            }
            // tab title
            if ($settings['eael_adv_accordion_toggle_icon_postion'] === '') {
                echo '<' . Helper::eael_validate_html_tag($settings['eael_adv_accordion_title_tag']) . ' class="eael-accordion-tab-title">' . Helper::eael_wp_kses($tab['eael_adv_accordion_tab_title']) . '</' . Helper::eael_validate_html_tag($settings['eael_adv_accordion_title_tag']) . '>';
            }
            // tab icon
            if ($tab['eael_adv_accordion_tab_icon_show'] === 'yes') {
                if ($tab_icon_is_new || $tab_icon_migrated) {
                    if ( 'svg' === $tab['eael_adv_accordion_tab_title_icon_new']['library'] ) {
                        echo '<span class="fa-accordion-icon fa-accordion-icon-svg eaa-svg">';
                        Icons_Manager::render_icon( $tab['eael_adv_accordion_tab_title_icon_new'] );
                        echo '</span>';
                    }else{
                        Icons_Manager::render_icon( $tab['eael_adv_accordion_tab_title_icon_new'], [ 'aria-hidden' => 'true', 'class' => "fa-accordion-icon" ] );
                    }


                } else {
                    echo '<i class="' . $tab['eael_adv_accordion_tab_title_icon'] . ' fa-accordion-icon"></i>';
                }
            }
            // tab title
            if ($settings['eael_adv_accordion_toggle_icon_postion'] === 'right' || $settings['eael_adv_accordion_toggle_icon_postion'] === null) {
                echo '<' . Helper::eael_validate_html_tag($settings['eael_adv_accordion_title_tag']) . ' class="eael-accordion-tab-title">' . Helper::eael_wp_kses($tab['eael_adv_accordion_tab_title']) . '</' . Helper::eael_validate_html_tag($settings['eael_adv_accordion_title_tag']) . '>';
            }
            // toggle icon
            if ($settings['eael_adv_accordion_icon_show'] === 'yes' && $settings['eael_adv_accordion_toggle_icon_postion'] === 'right') {
                $this->print_toggle_icon($settings);
            }
            echo '</div>';

            echo '<div ' . $this->get_render_attribute_string($tab_content_setting_key) . '>';
            if ('content' == $tab['eael_adv_accordion_text_type']) {
                echo '<p>' . do_shortcode($tab['eael_adv_accordion_tab_content']) . '</p>';
            } elseif ('template' == $tab['eael_adv_accordion_text_type']) {
                if (!empty($tab['eael_primary_templates'])) {
                    echo Plugin::$instance->frontend->get_builder_content($tab['eael_primary_templates'], true);
                }
            }
            echo '</div>
                </div>';
        }
        echo '</div>';
        ?>

        <!-- FAQ Shema : Starts-->
        <?php if( !empty( $settings['eael_adv_accordion_faq_schema_show'] ) && 'yes' === $settings['eael_adv_accordion_faq_schema_show'] ): ?>
        <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [{
            "@type": "Question",
            "name": "What is the return policy?",
            "acceptedAnswer": {
            "@type": "Answer",
            "text": "<p>Most unopened items in new condition and returned within <b>90 days</b> will receive a refund or exchange. Some items have a modified return policy noted on the receipt or packing slip. Items that are opened or damaged or do not have a receipt may be denied a refund or exchange. Items purchased online or in-store may be returned to any store.</p><p>Online purchases may be returned via a major parcel carrier. <a href=http://example.com/returns> Click here </a> to initiate a return.</p>"
            }
        }, {
            "@type": "Question",
            "name": "How long does it take to process a refund?",
            "acceptedAnswer": {
            "@type": "Answer",
            "text": "We will reimburse you for returned items in the same way you paid for them. For example, any amounts deducted from a gift card will be credited back to a gift card. For returns by mail, once we receive your return, we will process it within 4–5 business days. It may take up to 7 days after we process the return to reflect in your account, depending on your financial institution's processing time."
            }
        }, {
            "@type": "Question",
            "name": "What is the policy for late/non-delivery of items ordered online?",
            "acceptedAnswer": {
            "@type": "Answer",
            "text": "<p>Our local teams work diligently to make sure that your order arrives on time, within our normaldelivery hours of 9AM to 8PM in the recipient's time zone. During  busy holiday periods like Christmas, Valentine's and Mother's Day, we may extend our delivery hours before 9AM and after 8PM to ensure that all gifts are delivered on time. If for any reason your gift does not arrive on time, our dedicated Customer Service agents will do everything they can to help successfully resolve your issue.</p><p><a href=https://example.com/orders/>Click here</a> to complete the form with your order-related question(s).</p>"
            }
        }, {
            "@type": "Question",
            "name": "When will my credit card be charged?",
            "acceptedAnswer": {
            "@type": "Answer",
            "text": "We'll attempt to securely charge your credit card at the point of purchase online. If there's a problem, you'll be notified on the spot and prompted to use another card. Once we receive verification of sufficient funds, your payment will be completed and transferred securely to us. Your account will be charged in 24 to 48 hours."
            }
        }, {
            "@type": "Question",
            "name": "Will I be charged sales tax for online orders?",
            "acceptedAnswer": {
            "@type": "Answer",
            "text":"Local and State sales tax will be collected if your recipient's mailing address is in: <ul><li>Arizona</li><li>California</li><li>Colorado</li></ul>"}
            }]
        }
        </script>
        <?php endif; ?>
        <!-- FAQ Shema : Ends-->

        <?php 
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
            echo '<i class="' . $settings['eael_adv_accordion_icon'] . ' fa-toggle"></i>';
        }
    }
}



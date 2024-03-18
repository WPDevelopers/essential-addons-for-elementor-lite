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
use \Elementor\Plugin;
use Elementor\Repeater;
use \Elementor\Utils;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

class Adv_Tabs extends Widget_Base
{
    public function get_name()
    {
        return 'eael-adv-tabs';
    }

    public function get_title()
    {
        return esc_html__('Advanced Tabs', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-advanced-tabs';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'tab',
            'tabs',
            'ea tabs',
            'ea advanced tabs',
            'panel',
            'navigation',
            'group',
            'tabs content',
            'product tabs',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/advanced-tabs/';
    }

    protected function register_controls()
    {
        /**
         * Advance Tabs Settings
         */
        $this->start_controls_section(
            'eael_section_adv_tabs_settings',
            [
                'label' => esc_html__('General Settings', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_adv_tab_layout',
            [
                'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'eael-tabs-horizontal',
                'label_block' => false,
                'options' => [
                    'eael-tabs-horizontal' => esc_html__('Horizontal', 'essential-addons-for-elementor-lite'),
                    'eael-tabs-vertical' => esc_html__('Vertical', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_icon_show',
            [
                'label' => esc_html__('Enable Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'eael_adv_tab_icon_position',
            [
                'label' => esc_html__('Icon Position', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'eael-tab-inline-icon',
                'label_block' => false,
                'options' => [
                    'eael-tab-top-icon' => esc_html__('Stacked', 'essential-addons-for-elementor-lite'),
                    'eael-tab-inline-icon' => esc_html__('Inline', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
                    'eael_adv_tabs_icon_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_adv_tabs_tab_icon_alignment',
            [
                'label' => esc_html__( 'Icon Alignment', 'essential-addons-for-elementor-lite' ),
                'description' => sprintf( __( 'Set icon position before/after the tab title.', 'essential-addons-for-elementor-lite' ) ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'before',
                'options' => [
                    'before' => [
                        'title' => esc_html__( 'Before', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'after' => [
                        'title' => esc_html__( 'After', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'condition' => [
                    'eael_adv_tab_icon_position' => 'eael-tab-inline-icon',
                ],
            ]
        );

        $this->add_control(
            'eael_adv_tabs_default_active_tab',
            [
                'label' => esc_html__('Auto Active?', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Activate the first tab if no tab is selected as the active tab.', 'essential-addons-for-elementor-lite'),
                'default' => 'yes',
                'return_value' => 'yes',
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_adv_tabs_toggle_tab',
            [
                'label' => esc_html__('Toggle Tab', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Enables tab to expand and collapse.', 'essential-addons-for-elementor-lite'),
                'default' => '',
                'return_value' => 'yes',
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_adv_tabs_custom_id_offset',
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
            'eael_adv_tabs_scroll_speed',
            [
                'label'       => esc_html__('Scroll Speed (ms)', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::NUMBER,
                'label_block' => false,
                'default'     => 300,
            ]
        );

	    $this->add_control(
		    'eael_adv_tabs_scroll_onclick',
		    [
			    'label'        => esc_html__('Scroll on Click', 'essential-addons-for-elementor-lite'),
			    'type'         => Controls_Manager::SWITCHER,
			    'default'      => 'no',
			    'return_value' => 'yes',
		    ]
	    );

        $this->end_controls_section();

        /**
         * Advance Tabs Content Settings
         */
        $this->start_controls_section(
            'eael_section_adv_tabs_content_settings',
            [
                'label' => esc_html__('Content', 'essential-addons-for-elementor-lite'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'eael_adv_tabs_tab_show_as_default',
            [
                'label' => __('Active as Default', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'inactive',
                'return_value' => 'active-default',
            ]
        );

        $repeater->add_control(
            'eael_adv_tabs_icon_type',
            [
                'label' => esc_html__('Icon Type', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'none' => [
                        'title' => esc_html__('None', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-ban',
                    ],
                    'icon' => [
                        'title' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-icon-box',
                    ],
                    'image' => [
                        'title' => esc_html__('Image', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-image-bold',
                    ],
                ],
                'default' => 'icon',
            ]
        );

        $repeater->add_control(
            'eael_adv_tabs_tab_title_icon_new',
            [
                'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_adv_tabs_tab_title_icon',
                'default' => [
                    'value' => 'fas fa-home',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'eael_adv_tabs_icon_type' => 'icon',
                ],
            ]
        );

        $repeater->add_control(
            'eael_adv_tabs_tab_title_image',
            [
                'label' => esc_html__('Image', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'eael_adv_tabs_icon_type' => 'image',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $repeater->add_control(
            'eael_adv_tabs_tab_title',
            [
                'name' => 'eael_adv_tabs_tab_title',
                'label' => esc_html__('Tab Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Tab Title', 'essential-addons-for-elementor-lite'),
                'dynamic' => ['active' => true],
                'ai' => [
					'active' => false,
				],
            ]
        );

        $repeater->add_control(
            'eael_adv_tabs_tab_title_html_tag',
            [
                'name' => 'eael_adv_tabs_tab_title',
                'label' => esc_html__('Title HTML Tag', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
                'default' => 'span',
                'dynamic' => ['active' => true],
            ]
        );

        $repeater->add_control(
            'eael_adv_tabs_text_type',
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
                'label' => __('Choose Template', 'essential-addons-for-elementor-lite'),
                'type' => 'eael-select2',
                'source_name' => 'post_type',
                'source_type' => 'elementor_library',
                'label_block' => true,
                'condition' => [
                    'eael_adv_tabs_text_type' => 'template',
                ],
            ]
        );

        $repeater->add_control(
            'eael_adv_tabs_tab_content',
            [
                'label' => esc_html__('Tab Content', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'essential-addons-for-elementor-lite'),
                'dynamic' => ['active' => true],
                'condition' => [
                    'eael_adv_tabs_text_type' => 'content',
                ],
            ]
        );
        
        $repeater->add_control(
            'eael_adv_tabs_tab_id',
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

        $this->add_control(
            'eael_adv_tabs_tab',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' => [
                    ['eael_adv_tabs_tab_title' => esc_html__('Tab Title 1', 'essential-addons-for-elementor-lite')],
                    ['eael_adv_tabs_tab_title' => esc_html__('Tab Title 2', 'essential-addons-for-elementor-lite')],
                    ['eael_adv_tabs_tab_title' => esc_html__('Tab Title 3', 'essential-addons-for-elementor-lite')],
                ],
                'fields' => $repeater->get_controls(),
                'title_field' => '{{eael_adv_tabs_tab_title}}',
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
                    'label' => __('Unlock more possibilities', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        '1' => [
                            'title' => '',
                            'icon' => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default' => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
                ]
            );

            $this->end_controls_section();
        }

        /**
         * -------------------------------------------
         * Tab Style Advance Tabs Generel Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_adv_tabs_style_settings',
            [
                'label' => esc_html__('General', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eael_adv_tabs_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_tabs_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-advance-tabs',
            ]
        );

        $this->add_responsive_control(
            'eael_adv_tabs_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_adv_tabs_box_shadow',
                'selector' => '{{WRAPPER}} .eael-advance-tabs',
            ]
        );
        $this->end_controls_section();
        /**
         * -------------------------------------------
         * Tab Style Advance Tabs Content Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_adv_tabs_tab_style_settings',
            [
                'label' => esc_html__('Tab Title', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_adv_tabs_tab_title_typography',
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_title_width',
            [
                'label' => __('Title Min Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs.eael-tabs-vertical > .eael-tabs-nav' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_adv_tab_layout' => 'eael-tabs-vertical',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_icon_size',
            [
                'label' => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li img' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_icon_gap',
            [
                'label' => __('Icon Gap', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-tab-inline-icon li .title-before-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-tab-inline-icon li .title-after-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-tab-top-icon li i, {{WRAPPER}} .eael-tab-top-icon li img, {{WRAPPER}} .eael-tab-top-icon li svg' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_adv_tabs_tab_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-widget-container > .eael-advance-tabs > .eael-tabs-nav ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} > .elementor-widget-container > .eael-advance-tabs > .eael-tabs-nav ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('eael_adv_tabs_header_tabs');
        // Normal State Tab
        $this->start_controls_tab('eael_adv_tabs_header_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);
        $this->add_control(
            'eael_adv_tabs_tab_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f1f1f1',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'eael_adv_tabs_tab_bgtype',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li',
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_icon_color',
            [
                'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_tabs_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_tabs_tab_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        // Hover State Tab
        $this->start_controls_tab('eael_adv_tabs_header_hover', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);
        $this->add_control(
            'eael_adv_tabs_tab_color_hover',
            [
                'label' => esc_html__('Tab Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'eael_adv_tabs_tab_bgtype_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover',
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_text_color_hover',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover > i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover > svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_tabs_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_tabs_tab_border_hover',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        // Active State Tab
        $this->start_controls_tab('eael_adv_tabs_header_active', ['label' => esc_html__('Active', 'essential-addons-for-elementor-lite')]);
        $this->add_control(
            'eael_adv_tabs_tab_color_active',
            [
                'label' => esc_html__('Tab Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'eael_adv_tabs_tab_bgtype_active',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active',
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_text_color_active',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active > i' => 'color: {{VALUE}};',
                    //'{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active-default > i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active > svg' => 'fill: {{VALUE}};',
                    //'{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active-default > svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_tabs_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_tabs_tab_border_active',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_border_radius_active',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style Advance Tabs Content Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_adv_tabs_tab_content_style_settings',
            [
                'label' => esc_html__('Content', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'adv_tabs_content_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'adv_tabs_content_bgtype',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div',
            ]
        );
        $this->add_control(
            'adv_tabs_content_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_adv_tabs_content_typography',
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_content_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_content_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_tabs_content_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_content_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-tabs-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_adv_tabs_content_shadow',
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div',
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style Advance Tabs Caret Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_adv_tabs_tab_caret_style_settings',
            [
                'label' => esc_html__('Caret', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_caret_show',
            [
                'label' => esc_html__('Show Caret on Active Tab', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_caret_size',
            [
                'label' => esc_html__('Caret Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:after' => 'border-width: {{SIZE}}px; bottom: -{{SIZE}}px',
                    '{{WRAPPER}} .eael-advance-tabs.eael-tabs-vertical > .eael-tabs-nav > ul li:after' => 'right: -{{SIZE}}px; top: calc(50% - {{SIZE}}px) !important;',
                    '.rtl {{WRAPPER}} .eael-advance-tabs.eael-tabs-vertical > .eael-tabs-nav > ul li:after' => 'right: auto; left: -{{SIZE}}px !important; top: calc(50% - {{SIZE}}px) !important;',
                ],
                'condition' => [
                    'eael_adv_tabs_tab_caret_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_caret_color',
            [
                'label' => esc_html__('Caret Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs:not(.eael-tabs-vertical) > .eael-tabs-nav > ul li:after' => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-advance-tabs.eael-tabs-vertical > .eael-tabs-nav > ul li:after' => 'border-left-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_tabs_tab_caret_show' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style: Advance Tabs Responsive Controls
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_ad_responsive_controls',
            [
                'label' => esc_html__('Responsive Controls', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'responsive_vertical_layout',
            [
                'label' => __('Vertical Layout', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $eael_find_default_tab = [];
        $eael_adv_tab_id = 1;
        $eael_adv_tab_content_id = 1;
        $tab_icon_migrated = isset($settings['__fa4_migrated']['eael_adv_tabs_tab_title_icon_new']);
        $tab_icon_is_new = empty($settings['eael_adv_tabs_tab_title_icon']);
        $tab_auto_active =  'yes' === $settings['eael_adv_tabs_default_active_tab'] ? esc_attr('eael-tab-auto-active') : '';
        $tab_tpggle = 'yes' === $settings['eael_adv_tabs_toggle_tab'] ? esc_attr( 'eael-tab-toggle' ) : '';

        $this->add_render_attribute('eael_tab_wrapper', 'data-scroll-on-click', esc_attr( $settings['eael_adv_tabs_scroll_onclick'] ));
        $this->add_render_attribute('eael_tab_wrapper', 'data-scroll-speed', esc_attr( $settings['eael_adv_tabs_scroll_speed'] ));

        $this->add_render_attribute(
            'eael_tab_wrapper',
            [
                'id' => "eael-advance-tabs-{$this->get_id()}",
                'class' => ['eael-advance-tabs', $settings['eael_adv_tab_layout'], $tab_auto_active, $tab_tpggle],
                'data-tabid' => $this->get_id(),
            ]
        );
        if ($settings['eael_adv_tabs_tab_caret_show'] != 'yes') {
            $this->add_render_attribute('eael_tab_wrapper', 'class', 'active-caret-on');
        }

        if ($settings['responsive_vertical_layout'] != 'yes') {
            $this->add_render_attribute('eael_tab_wrapper', 'class', 'responsive-vertical-layout');
        }

        if( !empty($settings['eael_adv_tabs_custom_id_offset']) ){
            $this->add_render_attribute('eael_tab_wrapper', 'data-custom-id-offset', esc_attr( $settings['eael_adv_tabs_custom_id_offset'] ) );
        }

        $this->add_render_attribute('eael_tab_icon_position', 'class', esc_attr($settings['eael_adv_tab_icon_position']));
        $this->add_render_attribute('eael_tab_icon_position', 'role', 'tablist'); 
        ?>
        <div <?php echo $this->get_render_attribute_string('eael_tab_wrapper'); ?>>
            <div class="eael-tabs-nav">
                <ul <?php echo $this->get_render_attribute_string('eael_tab_icon_position'); ?>>
                    <?php foreach ($settings['eael_adv_tabs_tab'] as $index => $tab) :
	                    $tab_id = $tab['eael_adv_tabs_tab_id'] ? $tab['eael_adv_tabs_tab_id'] : Helper::str_to_css_id( $tab['eael_adv_tabs_tab_title'] );
	                    $tab_id = $tab_id === 'safari' ? 'eael-safari' : $tab_id;

                        $tab_count = $index + 1;
					    $tab_title_setting_key = $this->get_repeater_setting_key( 'eael_adv_tabs_tab_title', 'eael_adv_tabs_tab', $index );
					    

                        $this->add_render_attribute( $tab_title_setting_key, [
                            'id' => $tab_id,
                            'class' => [ $tab['eael_adv_tabs_tab_show_as_default'], 'eael-tab-item-trigger' ],
                            'aria-selected' => 1 === $tab_count ? 'true' : 'false',
                            'data-tab' => $tab_count,
                            'role' => 'tab',
                            'tabindex' => 1 === $tab_count ? '0' : '-1',
                            'aria-controls' => $tab_id . '-tab',
                            'aria-expanded' => 'false',
                        ] );

	                    $repeater_html_tag = ! empty( $tab['eael_adv_tabs_tab_title_html_tag'] ) ? Helper::eael_validate_html_tag( $tab['eael_adv_tabs_tab_title_html_tag'] ) : 'span';
                        $repeater_tab_title = Helper::eael_wp_kses($tab['eael_adv_tabs_tab_title']);
                                
                        ?>
                        <li <?php $this->print_render_attribute_string( $tab_title_setting_key ); ?>>
                            <?php if( $settings['eael_adv_tab_icon_position'] === 'eael-tab-inline-icon' && $settings['eael_adv_tabs_tab_icon_alignment'] === 'after' ) : ?>
                                <?php 
                                $this->add_render_attribute( $tab_title_setting_key . '_repeater_tab_title_attr', [
                                    'class' => [ 'eael-tab-title', ' title-before-icon' ],
                                ] );

                                printf('<%1$s %2$s>%3$s</%1$s>', 
                                    $repeater_html_tag,
                                    $this->get_render_attribute_string( $tab_title_setting_key . '_repeater_tab_title_attr'), 
                                    $repeater_tab_title 
                                ); 
                                ?>
                            <?php endif; ?>

                            <?php if ($settings['eael_adv_tabs_icon_show'] === 'yes') :
                                if ($tab['eael_adv_tabs_icon_type'] === 'icon') : ?>
                                    <?php if ($tab_icon_is_new || $tab_icon_migrated) {
		                                Icons_Manager::render_icon( $tab['eael_adv_tabs_tab_title_icon_new'] );
                                    } else {
                                        echo '<i class="' . esc_attr( $tab['eael_adv_tabs_tab_title_icon'] ) . '"></i>';
                                    } ?>
                                <?php elseif ($tab['eael_adv_tabs_icon_type'] === 'image') : ?>
                                    <img src="<?php echo esc_url( $tab['eael_adv_tabs_tab_title_image']['url'] ); ?>" alt="<?php echo esc_attr(get_post_meta($tab['eael_adv_tabs_tab_title_image']['id'], '_wp_attachment_image_alt', true)); ?>">
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if( $settings['eael_adv_tab_icon_position'] === 'eael-tab-inline-icon' && $settings['eael_adv_tabs_tab_icon_alignment'] !== 'after' ) : ?>
                                <?php 
                                $this->add_render_attribute( $tab_title_setting_key . '_repeater_tab_title_attr', [
                                    'class' => [ 'eael-tab-title', ' title-after-icon' ],
                                ] );

                                printf('<%1$s %2$s>%3$s</%1$s>', 
                                    $repeater_html_tag,
                                    $this->get_render_attribute_string( $tab_title_setting_key . '_repeater_tab_title_attr'), 
                                    $repeater_tab_title 
                                ); 
                                ?>
                            <?php endif; ?>

                            <?php if( $settings['eael_adv_tab_icon_position'] !== 'eael-tab-inline-icon' ) : ?>
                                <?php 
                                $this->add_render_attribute( $tab_title_setting_key . '_repeater_tab_title_attr', [
                                    'class' => [ 'eael-tab-title', ' title-after-icon' ],
                                ] );

                                printf('<%1$s %2$s>%3$s</%1$s>', 
                                    $repeater_html_tag,
                                    $this->get_render_attribute_string( $tab_title_setting_key . '_repeater_tab_title_attr'), 
                                    $repeater_tab_title 
                                ); 
                                ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="eael-tabs-content">
		        <?php foreach ($settings['eael_adv_tabs_tab'] as $tab) :
			        $eael_find_default_tab[] = $tab['eael_adv_tabs_tab_show_as_default'];
			        $tab_id = $tab['eael_adv_tabs_tab_id'] ? $tab['eael_adv_tabs_tab_id'] : Helper::str_to_css_id( $tab['eael_adv_tabs_tab_title'] );
			        $tab_id = $tab_id === 'safari' ? 'eael-safari-tab' : $tab_id . '-tab'; ?>

                    <div id="<?php echo esc_attr( $tab_id ); ?>" class="clearfix eael-tab-content-item <?php echo esc_attr($tab['eael_adv_tabs_tab_show_as_default']); ?>" data-title-link="<?php echo esc_attr( $tab_id ); ?>">
				        <?php if ('content' == $tab['eael_adv_tabs_text_type']) : ?>
					        <?php echo do_shortcode($tab['eael_adv_tabs_tab_content']); ?>
				        <?php elseif ('template' == $tab['eael_adv_tabs_text_type']) : ?>
                            <?php if ( ! empty( $tab['eael_primary_templates'] ) ) {
                                // WPML Compatibility
                                if ( ! is_array( $tab['eael_primary_templates'] ) ) {
                                    $tab['eael_primary_templates'] = apply_filters( 'wpml_object_id', $tab['eael_primary_templates'], 'wp_template', true );
                                }
                                echo Plugin::$instance->frontend->get_builder_content( $tab['eael_primary_templates'] );
                            } ?>
				        <?php endif; ?>
                    </div>
		        <?php endforeach; ?>
            </div>
        </div>
<?php }
}

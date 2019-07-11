<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Utils as Utils;
use \Elementor\Widget_Base as Widget_Base;
use \Essential_Addons_Elementor\Classes\Bootstrap;

class Adv_Tabs extends Widget_Base
{
    use \Essential_Addons_Elementor\Traits\Helper;

    public function get_name()
    {
        return 'eael-adv-tabs';
    }

    public function get_title()
    {
        return esc_html__('EA Advanced Tabs', 'essential-addons-elementor');
    }

    public function get_icon()
    {
        return 'eicon-tabs';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    protected function _register_controls()
    {
        /**
         * Advance Tabs Settings
         */
        $this->start_controls_section(
            'eael_section_adv_tabs_settings',
            [
                'label' => esc_html__('General Settings', 'essential-addons-elementor'),
            ]
        );
        $this->add_control(
            'eael_adv_tab_layout',
            [
                'label' => esc_html__('Layout', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'eael-tabs-horizontal',
                'label_block' => false,
                'options' => [
                    'eael-tabs-horizontal' => esc_html__('Horizontal', 'essential-addons-elementor'),
                    'eael-tabs-vertical' => esc_html__('Vertical', 'essential-addons-elementor'),
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_icon_show',
            [
                'label' => esc_html__('Enable Icon', 'essential-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'eael_adv_tab_icon_position',
            [
                'label' => esc_html__('Icon Position', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'eael-tab-inline-icon',
                'label_block' => false,
                'options' => [
                    'eael-tab-top-icon' => esc_html__('Stacked', 'essential-addons-elementor'),
                    'eael-tab-inline-icon' => esc_html__('Inline', 'essential-addons-elementor'),
                ],
                'condition' => [
                    'eael_adv_tabs_icon_show' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

        /**
         * Advance Tabs Content Settings
         */
        $this->start_controls_section(
            'eael_section_adv_tabs_content_settings',
            [
                'label' => esc_html__('Content', 'essential-addons-elementor'),
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' => [
                    ['eael_adv_tabs_tab_title' => esc_html__('Tab Title 1', 'essential-addons-elementor')],
                    ['eael_adv_tabs_tab_title' => esc_html__('Tab Title 2', 'essential-addons-elementor')],
                    ['eael_adv_tabs_tab_title' => esc_html__('Tab Title 3', 'essential-addons-elementor')],
                ],
                'fields' => [
                    [
                        'name' => 'eael_adv_tabs_tab_show_as_default',
                        'label' => __('Set as Default', 'essential-addons-elementor'),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'inactive',
                        'return_value' => 'active-default',
                    ],
                    [
                        'name' => 'eael_adv_tabs_icon_type',
                        'label' => esc_html__('Icon Type', 'essential-addons-elementor'),
                        'type' => Controls_Manager::CHOOSE,
                        'label_block' => false,
                        'options' => [
                            'none' => [
                                'title' => esc_html__('None', 'essential-addons-elementor'),
                                'icon' => 'fa fa-ban',
                            ],
                            'icon' => [
                                'title' => esc_html__('Icon', 'essential-addons-elementor'),
                                'icon' => 'fa fa-gear',
                            ],
                            'image' => [
                                'title' => esc_html__('Image', 'essential-addons-elementor'),
                                'icon' => 'fa fa-picture-o',
                            ],
                        ],
                        'default' => 'icon',
                    ],
                    [
                        'name' => 'eael_adv_tabs_tab_title_icon',
                        'label' => esc_html__('Icon', 'essential-addons-elementor'),
                        'type' => Controls_Manager::ICON,
                        'default' => 'fa fa-home',
                        'condition' => [
                            'eael_adv_tabs_icon_type' => 'icon',
                        ],
                    ],
                    [
                        'name' => 'eael_adv_tabs_tab_title_image',
                        'label' => esc_html__('Image', 'essential-addons-elementor'),
                        'type' => Controls_Manager::MEDIA,
                        'default' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'condition' => [
                            'eael_adv_tabs_icon_type' => 'image',
                        ],
                    ],
                    [
                        'name' => 'eael_adv_tabs_tab_title',
                        'label' => esc_html__('Tab Title', 'essential-addons-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('Tab Title', 'essential-addons-elementor'),
                        'dynamic' => ['active' => true],
                    ],
                    [
                        'name' => 'eael_adv_tabs_text_type',
                        'label' => __('Content Type', 'essential-addons-elementor'),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            'content' => __('Content', 'essential-addons-elementor'),
                            'template' => __('Saved Templates', 'essential-addons-elementor'),
                        ],
                        'default' => 'content',
                    ],
                    [
                        'name' => 'eael_primary_templates',
                        'label' => __('Choose Template', 'essential-addons-elementor'),
                        'type' => Controls_Manager::SELECT,
                        'options' => $this->eael_get_page_templates(),
                        'condition' => [
                            'eael_adv_tabs_text_type' => 'template',
                        ],
                    ],
                    [
                        'name' => 'eael_adv_tabs_tab_content',
                        'label' => esc_html__('Tab Content', 'essential-addons-elementor'),
                        'type' => Controls_Manager::WYSIWYG,
                        'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'essential-addons-elementor'),
                        'dynamic' => ['active' => true],
                        'condition' => [
                            'eael_adv_tabs_text_type' => 'content',
                        ],
                    ],
                ],
                'title_field' => '{{eael_adv_tabs_tab_title}}',
            ]
        );
        $this->end_controls_section();


        if(!apply_filters('eael/pro_enabled', false)) {
            $this->start_controls_section(
                'eael_section_pro',
                [
                    'label' => __( 'Go Premium for More Features', 'essential-addons-elementor' )
                ]
            );

            $this->add_control(
                'eael_control_get_pro',
                [
                    'label' => __( 'Unlock more possibilities', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        '1' => [
                            'title' => __( '', 'essential-addons-elementor' ),
                            'icon' => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default' => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank">Pro version</a> for more stunning elements and customization options.</span>'
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
                'label' => esc_html__('General', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-elementor'),
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
                'label' => esc_html__('Margin', 'essential-addons-elementor'),
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
                'label' => esc_html__('Border', 'essential-addons-elementor'),
                'selector' => '{{WRAPPER}} .eael-advance-tabs',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-elementor'),
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
                'label' => esc_html__('Tab Title', 'essential-addons-elementor'),
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
                'label' => __('Title Min Width', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
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
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs.eael-tabs-vertical .eael-tabs-nav > ul' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_adv_tab_layout' => 'eael-tabs-vertical',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_icon_size',
            [
                'label' => __('Icon Size', 'essential-addons-elementor'),
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
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_icon_gap',
            [
                'label' => __('Icon Gap', 'essential-addons-elementor'),
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
                    '{{WRAPPER}} .eael-tab-inline-icon li i, {{WRAPPER}} .eael-tab-inline-icon li img' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-tab-top-icon li i, {{WRAPPER}} .eael-tab-top-icon li img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('eael_adv_tabs_header_tabs');
        // Normal State Tab
        $this->start_controls_tab('eael_adv_tabs_header_normal', ['label' => esc_html__('Normal', 'essential-addons-elementor')]);
        $this->add_control(
            'eael_adv_tabs_tab_color',
            [
                'label' => esc_html__('Tab Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f1f1f1',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-elementor'),
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
                'label' => esc_html__('Icon Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li i' => 'color: {{VALUE}};',
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
                'label' => esc_html__('Border', 'essential-addons-elementor'),
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        // Hover State Tab
        $this->start_controls_tab('eael_adv_tabs_header_hover', ['label' => esc_html__('Hover', 'essential-addons-elementor')]);
        $this->add_control(
            'eael_adv_tabs_tab_color_hover',
            [
                'label' => esc_html__('Tab Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f1f1f1',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_text_color_hover',
            [
                'label' => esc_html__('Text Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover .fa' => 'color: {{VALUE}};',
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
                'label' => esc_html__('Border', 'essential-addons-elementor'),
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        // Active State Tab
        $this->start_controls_tab('eael_adv_tabs_header_active', ['label' => esc_html__('Active', 'essential-addons-elementor')]);
        $this->add_control(
            'eael_adv_tabs_tab_color_active',
            [
                'label' => esc_html__('Tab Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active-default' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_text_color_active',
            [
                'label' => esc_html__('Text Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active-deafult' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active .fa' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active-default .fa' => 'color: {{VALUE}};',
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
                'label' => esc_html__('Border', 'essential-addons-elementor'),
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active, {{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active-default',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_tabs_tab_border_radius_active',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active-default' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'label' => esc_html__('Content', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'adv_tabs_content_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'adv_tabs_content_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-elementor'),
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
                'label' => esc_html__('Padding', 'essential-addons-elementor'),
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
                'label' => esc_html__('Margin', 'essential-addons-elementor'),
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
                'label' => esc_html__('Border', 'essential-addons-elementor'),
                'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div',
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
                'label' => esc_html__('Caret', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_caret_show',
            [
                'label' => esc_html__('Show Caret on Active Tab', 'essential-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_caret_size',
            [
                'label' => esc_html__('Caret Size', 'essential-addons-elementor'),
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
                    '{{WRAPPER}} .eael-advance-tabs.eael-tabs-vertical .eael-tabs-nav > ul li:after' => 'right: -{{SIZE}}px; top: calc(50% - {{SIZE}}px) !important;',
                ],
                'condition' => [
                    'eael_adv_tabs_tab_caret_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_caret_color',
            [
                'label' => esc_html__('Caret Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444',
                'selectors' => [
                    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:after' => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}} .eael-advance-tabs.eael-tabs-vertical .eael-tabs-nav > ul li:after' => 'border-top-color: transparent; border-left-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_tabs_tab_caret_show' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $eael_find_default_tab = array();
        $eael_adv_tab_id = 1;
        $eael_adv_tab_content_id = 1;

        $this->add_render_attribute(
            'eael_tab_wrapper',
            [
                'id' => "eael-advance-tabs-{$this->get_id()}",
                'class' => ['eael-advance-tabs', $settings['eael_adv_tab_layout']],
                'data-tabid' => $this->get_id(),
            ]
        );
        if ($settings['eael_adv_tabs_tab_caret_show'] != 'yes') {
            $this->add_render_attribute('eael_tab_wrapper', 'class', 'active-caret-on');
        }

        $this->add_render_attribute('eael_tab_icon_position', 'class', esc_attr($settings['eael_adv_tab_icon_position']));
        ?>
	<div <?php echo $this->get_render_attribute_string('eael_tab_wrapper'); ?>>
  		<div class="eael-tabs-nav">
		  <ul <?php echo $this->get_render_attribute_string('eael_tab_icon_position'); ?>>
	    	<?php foreach ($settings['eael_adv_tabs_tab'] as $tab): ?>
	      		<li class="<?php echo esc_attr($tab['eael_adv_tabs_tab_show_as_default']); ?>"><?php if ($settings['eael_adv_tabs_icon_show'] === 'yes'):
            if ($tab['eael_adv_tabs_icon_type'] === 'icon'): ?>
			      					<i class="<?php echo esc_attr($tab['eael_adv_tabs_tab_title_icon']); ?>"></i>
			      				<?php elseif ($tab['eael_adv_tabs_icon_type'] === 'image'): ?>
	      					<img src="<?php echo esc_attr($tab['eael_adv_tabs_tab_title_image']['url']); ?>" alt="<?php echo esc_attr(get_post_meta($tab['eael_adv_tabs_tab_title_image']['id'], '_wp_attachment_image_alt', true)); ?>">
	      				<?php endif;?>
	      		<?php endif;?> <span class="eael-tab-title"><?php echo $tab['eael_adv_tabs_tab_title']; ?></span></li>
	      	<?php endforeach;?>
    		</ul>
  		</div>
  		<div class="eael-tabs-content">
  			<?php foreach ($settings['eael_adv_tabs_tab'] as $tab): $eael_find_default_tab[] = $tab['eael_adv_tabs_tab_show_as_default'];?>
		    			<div class="clearfix <?php echo esc_attr($tab['eael_adv_tabs_tab_show_as_default']); ?>">
		      				<?php if ('content' == $tab['eael_adv_tabs_text_type']): ?>
								<?php echo do_shortcode($tab['eael_adv_tabs_tab_content']); ?>
							<?php elseif ('template' == $tab['eael_adv_tabs_text_type']): ?>
						<?php
if (!empty($tab['eael_primary_templates'])) {
            $eael_template_id = $tab['eael_primary_templates'];
            $eael_frontend = new Frontend;
            echo $eael_frontend->get_builder_content($eael_template_id, true);
        }
        ?>
					<?php endif;?>
    			</div>
			<?php endforeach;?>
  		</div>
	</div>
	<?php
}

    protected function content_template(){}
}
<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;

class Adv_Accordion extends Widget_Base
{
    use \Essential_Addons_Elementor\Traits\Helper;

    public function get_name()
    {
        return 'eael-adv-accordion';
    }

    public function get_title()
    {
        return esc_html__('EA Advanced Accordion', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eicon-accordion';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    protected function _register_controls()
    {
        /**
         * Advance Accordion Settings
         */
        $this->start_controls_section(
            'eael_section_adv-accordion_settings',
            [
                'label' => esc_html__('General Settings', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_adv_accordion_type',
            [
                'label' => esc_html__('Accordion Type', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'accordion',
                'label_block' => false,
                'options' => [
                    'accordion' => esc_html__('Accordion', 'essential-addons-for-elementor-lite'),
                    'toggle' => esc_html__('Toggle', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );
        $this->add_control(
            'eael_adv_accordion_icon_show',
            [
                'label' => esc_html__('Enable Toggle Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'eael_adv_accordion_icon_new',
            [
                'label' => esc_html__('Toggle Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_adv_accordion_icon',
                'default' => [
                    'value' => 'fas fa-angle-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_accordion_toggle_speed',
            [
                'label' => esc_html__('Toggle Speed (ms)', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'label_block' => false,
                'default' => 300,
            ]
        );
        $this->end_controls_section();

        /**
         * Advance Accordion Content Settings
         */
        $this->start_controls_section(
            'eael_section_adv_accordion_content_settings',
            [
                'label' => esc_html__('Content Settings', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab',
            [
                'type' => Controls_Manager::REPEATER,
                'seperator' => 'before',
                'default' => [
                    ['eael_adv_accordion_tab_title' => esc_html__('Accordion Tab Title 1', 'essential-addons-for-elementor-lite')],
                    ['eael_adv_accordion_tab_title' => esc_html__('Accordion Tab Title 2', 'essential-addons-for-elementor-lite')],
                    ['eael_adv_accordion_tab_title' => esc_html__('Accordion Tab Title 3', 'essential-addons-for-elementor-lite')],
                ],
                'fields' => [
                    [
                        'name' => 'eael_adv_accordion_tab_default_active',
                        'label' => esc_html__('Active as Default', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'no',
                        'return_value' => 'yes',
                    ],
                    [
                        'name' => 'eael_adv_accordion_tab_icon_show',
                        'label' => esc_html__('Enable Tab Icon', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'yes',
                        'return_value' => 'yes',
                    ],
                    [
                        'name' => 'eael_adv_accordion_tab_title_icon_new',
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
                    ],
                    [
                        'name' => 'eael_adv_accordion_tab_title',
                        'label' => esc_html__('Tab Title', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::TEXT,
                        'default' => esc_html__('Tab Title', 'essential-addons-for-elementor-lite'),
                        'dynamic' => ['active' => true],
                    ],
                    [
                        'name' => 'eael_adv_accordion_text_type',
                        'label' => __('Content Type', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SELECT,
                        'options' => [
                            'content' => __('Content', 'essential-addons-for-elementor-lite'),
                            'template' => __('Saved Templates', 'essential-addons-for-elementor-lite'),
                        ],
                        'default' => 'content',
                    ],
                    [
                        'name' => 'eael_primary_templates',
                        'label' => __('Choose Template', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::SELECT,
                        'options' => $this->eael_get_page_templates(),
                        'condition' => [
                            'eael_adv_accordion_text_type' => 'template',
                        ],
                    ],
                    [
                        'name' => 'eael_adv_accordion_tab_content',
                        'label' => esc_html__('Tab Content', 'essential-addons-for-elementor-lite'),
                        'type' => Controls_Manager::WYSIWYG,
                        'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'essential-addons-for-elementor-lite'),
                        'dynamic' => ['active' => true],
                        'condition' => [
                            'eael_adv_accordion_text_type' => 'content',
                        ],
                    ],
                ],
                'title_field' => '{{eael_adv_accordion_tab_title}}',
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
                            'title' => __('', 'essential-addons-for-elementor-lite'),
                            'icon' => 'fa fa-unlock-alt',
                        ],
                    ],
                    'default' => '1',
                    'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank">Pro version</a> for more stunning elements and customization options.</span>',
                ]
            );

            $this->end_controls_section();
        }

        /**
         * -------------------------------------------
         * Tab Style Advance Accordion Generel Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_adv_accordion_style_settings',
            [
                'label' => esc_html__('General Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_accordion_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_adv_accordion_box_shadow',
                'selector' => '{{WRAPPER}} .eael-adv-accordion',
            ]
        );
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style Advance Accordion Content Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_adv_accordions_tab_style_settings',
            [
                'label' => esc_html__('Tab Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_adv_accordion_tab_title_typography',
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_icon_size',
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
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_icon_gap',
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
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header i' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header img'   => 'margin-right: {{SIZE}}{{UNIT}};'
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('eael_adv_accordion_header_tabs');
        # Normal State Tab
        $this->start_controls_tab('eael_adv_accordion_header_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);
        $this->add_control(
            'eael_adv_accordion_tab_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f1f1f1',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'eael_adv_accordion_tab_bgtype',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header',
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab_icon_color',
            [
                'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_tabs_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_accordion_tab_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
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
        $this->add_control(
            'eael_adv_accordion_tab_color_hover',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#414141',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'eael_adv_accordion_tab_bgtype_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header:hover',
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab_text_color_hover',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header:hover .fa' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_accordion_toggle_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_accordion_tab_border_hover',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header:hover',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
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
        $this->add_control(
            'eael_adv_accordion_tab_color_active',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'eael_adv_accordion_tab_bgtype_active',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active',
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab_text_color_active',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_accordion_tab_icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active .fa' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_accordion_toggle_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_accordion_tab_border_active',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_tab_border_radius_active',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style Advance Accordion Content Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_adv_accordion_tab_content_style_settings',
            [
                'label' => esc_html__('Content Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'adv_accordion_content_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'adv_accordion_content_bgtype',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content',
            ]
        );

        $this->add_control(
            'adv_accordion_content_text_color',
            [
                'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_adv_accordion_content_typography',
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content',
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_content_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_adv_accordion_content_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_adv_accordion_content_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_adv_accordion_content_shadow',
                'selector' => '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-content',
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();

        /**
         * Advance Accordion Caret Settings
         */
        $this->start_controls_section(
            'eael_section_adv_accordion_caret_settings',
            [
                'label' => esc_html__('Toggle Caret Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eael_adv_accordion_tab_toggle_icon_size',
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
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-toggle' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header > img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_toggle_color',
            [
                'label' => esc_html__('Caret Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header .fa-toggle' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'eael_adv_tabs_tab_toggle_active_color',
            [
                'label' => esc_html__('Caret Color (Active)', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list .eael-accordion-header.active .fa-toggle' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-adv-accordion .eael-accordion-list:hover .eael-accordion-header .fa-toggle' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_adv_accordion_icon_show' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $id_int = substr($this->get_id_int(), 0, 3);

        $this->add_render_attribute('eael-adv-accordion', 'class', 'eael-adv-accordion');
        $this->add_render_attribute('eael-adv-accordion', 'id', 'eael-adv-accordion-' . esc_attr($this->get_id()));

        // icon handler
        $accordion_icon_migrated = isset($settings['__fa4_migrated']['eael_adv_accordion_icon_new']);
        $accordion_icon_is_new = empty($settings['eael_adv_accordion_icon']);
        ?>
        <div
            <?php echo $this->get_render_attribute_string('eael-adv-accordion'); ?>
            <?php echo 'data-accordion-id="' . esc_attr($this->get_id()) . '"'; ?>
            <?php echo !empty($settings['eael_adv_accordion_type']) ? 'data-accordion-type="' . esc_attr($settings['eael_adv_accordion_type']) . '"' : 'accordion'; ?>
            <?php echo !empty($settings['eael_adv_accordion_toggle_speed']) ? 'data-toogle-speed="' . esc_attr($settings['eael_adv_accordion_toggle_speed']) . '"' : '300'; ?>
        >
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

            $this->add_render_attribute($tab_title_setting_key, [
                'id' => 'elementor-tab-title-' . $id_int . $tab_count,
                'class' => $tab_title_class,
                'tabindex' => $id_int . $tab_count,
                'data-tab' => $tab_count,
                'role' => 'tab',
                'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
            ]);

            $this->add_render_attribute($tab_content_setting_key, [
                'id' => 'elementor-tab-content-' . $id_int . $tab_count,
                'class' => $tab_content_class,
                'data-tab' => $tab_count,
                'role' => 'tabpanel',
                'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
            ]);

            echo '<div class="eael-accordion-list">
                <div ' . $this->get_render_attribute_string($tab_title_setting_key) . '>
                    <span>';
            if ($tab['eael_adv_accordion_tab_icon_show'] === 'yes') {
                if ($tab_icon_is_new || $tab_icon_migrated) {
                    if( isset($tab['eael_adv_accordion_tab_title_icon_new']['value']['url']) ) {
                        echo '<img src="' . $tab['eael_adv_accordion_tab_title_icon_new']['value']['url'] . '" />';
                    }else {
                        echo '<i class="' . $tab['eael_adv_accordion_tab_title_icon_new']['value'] . ' fa-accordion-icon"></i>';
                    }
                } else {
                    echo '<i class="' . $tab['eael_adv_accordion_tab_title_icon'] . ' fa-accordion-icon"></i>';
                }
            }
            echo $tab['eael_adv_accordion_tab_title'] .
                '</span>';
            if ($settings['eael_adv_accordion_icon_show'] === 'yes') {
                if ($accordion_icon_is_new || $accordion_icon_migrated) {
                    if( isset($settings['eael_adv_accordion_icon_new']['value']['url']) ) {
                        echo '<img src="' . $settings['eael_adv_accordion_icon_new']['value']['url'] . '" />';
                    }else {
                        echo '<i class="' . $settings['eael_adv_accordion_icon_new']['value'] . ' fa-toggle"></i>';
                    }
                } else {
                    echo '<i class="' . $settings['eael_adv_accordion_icon'] . ' fa-toggle"></i>';
                }
            }
            echo '</div>';

            echo '<div ' . $this->get_render_attribute_string($tab_content_setting_key) . '>';
            if ('content' == $tab['eael_adv_accordion_text_type']) {
                echo '<p>' . do_shortcode($tab['eael_adv_accordion_tab_content']) . '</p>';
            } elseif ('template' == $tab['eael_adv_accordion_text_type']) {
                if (!empty($tab['eael_primary_templates'])) {
                    $eael_template_id = $tab['eael_primary_templates'];
                    $eael_frontend = new Frontend;

                    echo $eael_frontend->get_builder_content($eael_template_id, true);
                }
            }
            echo '</div>
                </div>';
        }
        echo '</div>';
    }
}
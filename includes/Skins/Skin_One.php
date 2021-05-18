<?php

namespace Essential_Addons_Elementor\Skins;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Skin_Base;
use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Core\Schemes\Typography;

class Skin_One extends Skin_Base
{
    public function get_id()
    {
        return 'skin-one';
    }

    public function get_title()
    {
        return __('Skin One', 'essential-addons-for-elementor-lite');
    }

    protected function _register_controls_actions()
    {
        add_action('elementor/element/eael-simple-menu/eael_simple_menu_section_general/before_section_end', [$this, 'section_general']);
        add_action('elementor/element/eael-simple-menu/eael_simple_menu_section_style_menu/before_section_end', [$this, 'section_style_menu']);
        add_action('elementor/element/eael-simple-menu/eael_simple_menu_section_style_dropdown/before_section_end', [$this, 'section_style_dropdown']);
        add_action('elementor/element/eael-simple-menu/eael_simple_menu_section_style_top_level_item/before_section_end', [$this, 'section_style_top_level_item']);
        add_action('elementor/element/eael-simple-menu/eael_simple_menu_section_style_dropdown_item/before_section_end', [$this, 'section_style_dropdown_item']);
    }

    public function section_general(Widget_Base $widget)
    {
        $this->parent = $widget;

        $this->add_control(
            'eael_simple_menu_layout',
            [
                'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => [
                    'horizontal' => __('Horizontal', 'essential-addons-for-elementor-lite'),
                    'vertical' => __('Vertical', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'horizontal',

            ]
        );
    }

    public function section_style_menu(Widget_Base $widget)
    {
        $this->parent = $widget;

        $this->add_responsive_control(
            'eael_simple_menu_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu-container' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu-container .eael-simple-menu.eael-simple-menu-horizontal' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_simple_menu_border',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-simple-menu-container, {{WRAPPER}} .eael-simple-menu-container .eael-simple-menu-toggle, {{WRAPPER}} .eael-simple-menu.eael-simple-menu-horizontal.eael-simple-menu-responsive',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_simple_menu_box_shadow',
                'label' => __('Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-simple-menu-container',
            ]
        );
    }

    public function section_style_dropdown(Widget_Base $widget)
    {
        $this->parent = $widget;

        $this->add_control(
            'eael_simple_menu_dropdown_animation',
            [
                'label' => __('Animation', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'eael-simple-menu-dropdown-animate-fade' => __('Fade', 'essential-addons-for-elementor-lite'),
                    'eael-simple-menu-dropdown-animate-to-top' => __('To Top', 'essential-addons-for-elementor-lite'),
                    'eael-simple-menu-dropdown-animate-zoom-in' => __('ZoomIn', 'essential-addons-for-elementor-lite'),
                    'eael-simple-menu-dropdown-animate-zoom-out' => __('ZoomOut', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'eael-simple-menu-dropdown-animate-fade',
                'condition' => [
                    'skin_one_eael_simple_menu_layout' => ['horizontal'],
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_simple_menu_dropdown_border',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-simple-menu li ul',
            ]
        );

        $this->add_responsive_control(
            'eael_simple_menu_dropdown_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'desktop_default' => [
                    'unit' => 'px',
                    'top' => '5',
                    'right' => '5',
                    'bottom' => '5',
                    'left' => '5',
                    'isLinked' => true,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'isLinked' => true,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'isLinked' => true,
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_simple_menu_dropdown_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'desktop_default' => [
                    'unit' => 'px',
                    'top' => '20',
                    'right' => '0',
                    'bottom' => '20',
                    'left' => '0',
                    'isLinked' => false,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'isLinked' => true,
                ],
                'mobile_default' => [
                    'unit' => 'px',
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'isLinked' => true,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_simple_menu_dropdown_box_shadow',
                'label' => __('Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-simple-menu li ul',
            ]
        );
    }

    public function section_style_top_level_item(Widget_Base $widget)
    {
        $this->parent = $widget;

        $this->start_controls_tabs('eael_simple_menu_top_level_item');

        $this->start_controls_tab(
            'eael_simple_menu_top_level_item_default',
            [
                'label' => __('Default', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'eael-simple-menu-align-left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'eael-simple-menu-align-center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'eael-simple-menu-align-right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'eael-simple-menu-align-center',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_simple_menu_item_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-simple-menu li > a, .eael-simple-menu-container .eael-simple-menu-toggle-text',
                'fields_options' => [
                    'font_family' => [
                        'default' => 'Playfair Display',
                    ],
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '17',
                        ],
                    ],
                    'font_weight' => [
                        'default' => '700',
                    ],
                    'line_height' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '50',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li > a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu-toggle-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0)',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_divider_color',
            [
                'label' => __('Divider Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-horizontal:not(.eael-simple-menu-responsive) > li > a' => 'border-right: 1px solid {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu-align-center .eael-simple-menu.eael-simple-menu-horizontal:not(.eael-simple-menu-responsive) > li:first-child > a' => 'border-left: 1px solid {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu-align-right .eael-simple-menu.eael-simple-menu-horizontal:not(.eael-simple-menu-responsive) > li:first-child > a' => 'border-left: 1px solid {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-horizontal.eael-simple-menu-responsive > li:not(:last-child) > a' => 'border-bottom: 1px solid {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-vertical > li:not(:last-child) > a' => 'border-bottom: 1px solid {{VALUE}}',
                ],

            ]
        );

        $this->add_control(
            'eael_simple_menu_item_padding',
            [
                'label' => __('Item Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-horizontal li ul li a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_heading',
            [
                'label' => __('Dropdown Indicator', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,

            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator',
            [
                'label' => __('Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'recommended' => [
                    'fa-solid' => [
                        'fas fa-angle-down',
                    ]
                ],
                'default' => [
                    'value' => 'fas fa-angle-down',
                    'library' => 'fa-solid',
                ],

            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_note',
            [
                'label' => __('Important Note', 'essential-addons-for-elementor-lite'),
                'show_label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<div style="font-size: 11px;font-style:italic;line-height:1.4;color:#a4afb7;">Following options are only available in the <span style="color:#d30c5c"><strong>Small</strong></span> screens for <span style="color:#d30c5c"><strong>Horizontal</strong></span> Layout, and all screens for <span style="color:#d30c5c"><strong>Vertical</strong></span> Layout</div>', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#1a1a1a',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0)',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_border',
            [
                'label' => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0)',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_simple_menu_top_level_item_hover',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_color_hover',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#66cc66',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li:hover > a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-item > a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-ancestor > a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_background_hover',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0)',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li:hover > a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-item > a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-ancestor > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_hover_heading',
            [
                'label' => __('Dropdown Indicator', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,

            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_hover_note',
            [
                'label' => __('Important Note', 'essential-addons-for-elementor-lite'),
                'show_label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<div style="font-size: 11px;font-style:italic;line-height:1.4;color:#a4afb7;">Following options are only available in the <span style="color:#d30c5c"><strong>Small</strong></span> screens for <span style="color:#d30c5c"><strong>Horizontal</strong></span> Layout, and all screens for <span style="color:#d30c5c"><strong>Vertical</strong></span> Layout</div>', 'essential-addons-for-elementor-lite'),

            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_color_hover',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#66cc66',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator:hover:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator.eael-simple-menu-indicator-open:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_background_hover',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator.eael-simple-menu-indicator-open' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_border_hover',
            [
                'label' => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator.eael-simple-menu-indicator-open' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
    }

    public function section_style_dropdown_item(Widget_Base $widget)
    {
        $this->parent = $widget;

        $this->start_controls_tabs('eael_simple_menu_dropdown_item');

        $this->start_controls_tab(
            'eael_simple_menu_dropdown_item_default',
            [
                'label' => __('Default', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'eael-simple-menu-dropdown-align-left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'eael-simple-menu-dropdown-align-center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'eael-simple-menu-dropdown-align-right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'eael-simple-menu-dropdown-align-left',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_simple_menu_dropdown_item_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-simple-menu li ul li > a',
                'fields_options' => [
                    'font_family' => [
                        'default' => 'Playfair Display',
                    ],
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '14',
                        ],
                    ],
                    'font_weight' => [
                        'default' => '400',
                    ],
                    'line_height' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => '40',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#d7d7d7',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li > a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0)',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_divider_color',
            [
                'label' => __('Divider Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-horizontal li ul li > a' => 'border-bottom: 1px solid {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-vertical li ul li > a' => 'border-bottom: 1px solid {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_heading',
            [
                'label' => __('Dropdown Indicator', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,

            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator',
            [
                'label' => __('Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'recommended' => [
                    'fa-solid' => [
                        'fas fa-angle-down',
                    ]
                ],
                'default' => [
                    'value' => 'fas fa-angle-down',
                    'library' => 'fa-solid',
                ],

            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_note',
            [
                'label' => __('Important Note', 'essential-addons-for-elementor-lite'),
                'show_label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<div style="font-size: 11px;font-style:italic;line-height:1.4;color:#a4afb7;">Following options are only available in the <span style="color:#d30c5c"><strong>Small</strong></span> screens for <span style="color:#d30c5c"><strong>Horizontal</strong></span> Layout, and all screens for <span style="color:#d30c5c"><strong>Vertical</strong></span> Layout</div>', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#d7d7d7',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0)',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_border',
            [
                'label' => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0)',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_simple_menu_dropdown_item_hover',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_color_hover',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#66cc66',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li:hover > a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li.current-menu-item > a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li.current-menu-ancestor > a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_background_hover',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0)',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li:hover > a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li.current-menu-item > a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li.current-menu-ancestor > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_hover_heading',
            [
                'label' => __('Dropdown Indicator', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,

            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_hover_note',
            [
                'label' => __('Important Note', 'essential-addons-for-elementor-lite'),
                'show_label' => false,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('<div style="font-size: 11px;font-style:italic;line-height:1.4;color:#a4afb7;">Following options are only available in the <span style="color:#d30c5c"><strong>Small</strong></span> screens for <span style="color:#d30c5c"><strong>Horizontal</strong></span> Layout, and all screens for <span style="color:#d30c5c"><strong>Vertical</strong></span> Layout</div>', 'essential-addons-for-elementor-lite'),

            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_color_hover',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#66cc66',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator:hover:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator.eael-simple-menu-indicator-open:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_background_hover',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator.eael-simple-menu-indicator-open' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_border_hover',
            [
                'label' => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator.eael-simple-menu-indicator-open' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
    }

    public function render()
    {
        $settings = $this->parent->get_settings();
        $menu_classes = ['eael-simple-menu', $settings['skin_one_eael_simple_menu_dropdown_animation'], 'eael-simple-menu-indicator'];
        $container_classes = ['eael-simple-menu-container', $settings['skin_one_eael_simple_menu_item_alignment'], $settings['skin_one_eael_simple_menu_dropdown_item_alignment']];

        if ($settings['skin_one_eael_simple_menu_layout'] == 'horizontal') {
            $menu_classes[] = 'eael-simple-menu-horizontal';
        } else {
            $menu_classes[] = 'eael-simple-menu-vertical';
        }

        if (isset($settings['skin_one_eael_simple_menu_item_dropdown_indicator']) && $settings['skin_one_eael_simple_menu_item_dropdown_indicator'] == 'yes') {
            $menu_classes[] = 'eael-simple-menu-indicator';
        }

        $this->parent->add_render_attribute('eael-simple-menu', [
            'class' => implode(' ', array_filter($container_classes)),
            'data-indicator-class' => $settings['skin_one_eael_simple_menu_item_indicator'],
            'data-dropdown-indicator-class' => $settings['skin_one_eael_simple_menu_dropdown_item_indicator'],
        ]);

        if ($settings['eael_simple_menu_menu']) {
            $args = [
                'menu' => $settings['eael_simple_menu_menu'],
                'menu_class' => implode(' ', array_filter($menu_classes)),
                'fallback_cb' => '__return_empty_string',
                'container' => false,
                'echo' => false,
            ];

            echo '<div ' . $this->parent->get_render_attribute_string('eael-simple-menu') . '>' . wp_nav_menu($args) . '</div>';
        }
    }
}

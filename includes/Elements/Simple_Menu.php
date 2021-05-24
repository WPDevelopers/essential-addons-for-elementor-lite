<?php

namespace Essential_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

use Essential_Addons_Elementor\Classes\Helper as HelperClass;
use Essential_Addons_Elementor\Traits\Helper;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class Simple_Menu extends Widget_Base
{

    use Helper;

    protected $_has_template_content = false;

    public function get_name()
    {
        return 'eael-simple-menu';
    }

    public function get_title()
    {
        return esc_html__('Simple Menu', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-simple-menu';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'simple menu',
            'ea simple menu',
            'nav menu',
            'ea nav menu',
            'navigation',
            'ea navigation',
            'navigation menu',
            'ea navigation menu',
            'header menu',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/simple-menu/';
    }

    /**
     * Get all registered menus.
     *
     * @return array of menus.
     */
    private function get_simple_menus()
    {
        $menus   = wp_get_nav_menus();
        $options = [];

        if (empty($menus)) {
            return $options;
        }

        foreach ($menus as $menu) {
            $options[$menu->term_id] = $menu->name;
        }

        return $options;
    }

    protected function register_controls()
    {
        /**
         * Content: General
         */
        $this->start_controls_section(
            'eael_simple_menu_section_general',
            [
                'label' => esc_html__('General', 'essential-addons-for-elementor-lite'),
            ]
        );

        $simple_menus = $this->get_simple_menus();

        if ($simple_menus) {
            $this->add_control(
                'eael_simple_menu_menu',
                [
                    'label'       => esc_html__('Select Menu', 'essential-addons-for-elementor-lite'),
                    'description' => sprintf(__('Go to the <a href="%s" target="_blank">Menu screen</a> to manage your menus.', 'essential-addons-for-elementor-lite'), admin_url('nav-menus.php')),
                    'type'        => Controls_Manager::SELECT,
                    'label_block' => false,
                    'options'     => $simple_menus,
                    'default'     => array_keys($simple_menus)[0],
                ]
            );
        } else {
            $this->add_control(
                'menu',
                [
                    'type'      => Controls_Manager::RAW_HTML,
                    'raw'       => sprintf(__('<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'essential-addons-for-elementor-lite'), admin_url('nav-menus.php?action=edit&menu=0')),
                    'separator' => 'after',
                ]
            );
        }

        $this->add_control(
            'eael_simple_menu_preset',
            [
                'label'   => esc_html__('Preset', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'preset-1',
                'options' => [
                    'preset-1' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
                    'preset-2' => esc_html__('Preset 2', 'essential-addons-for-elementor-lite'),
                    'preset-3' => esc_html__('Preset 3', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_layout',
            [
                'label'       => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SELECT,
                'label_block' => false,
                'options'     => [
                    'horizontal' => __('Horizontal', 'essential-addons-for-elementor-lite'),
                    'vertical'   => __('Vertical', 'essential-addons-for-elementor-lite'),
                ],
                'default'     => 'horizontal',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_simple_menu_section_hamburger',
            [
                'label' => esc_html__('Hamburger Options', 'essential-addons-for-elementor-lite'),
                'condition'    => [
                    'eael_simple_menu_layout' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_hamburger_disable_selected_menu',
            [
                'label'        => esc_html__('Disable Selected Menu', 'essential-addons-for-elementor-lite'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'hide',
                'default'      => 'no',
                'prefix_class' => 'eael_simple_menu_hamburger_disable_selected_menu_',
            ]
        );

        $this->add_control(
            'eael_simple_menu_hamburger_alignment',
            [
                'label'        => __('Hamburger Alignment', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'      => 'eael-simple-menu-hamburger-align-right',
                'prefix_class' => 'eael-simple-menu-hamburger-align-',
                'condition'    => [
                    'eael_simple_menu_hamburger_disable_selected_menu' => 'hide',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_full_width',
            [
                'label'        => __('Full Width', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'description'  => __('Stretch the dropdown of the menu to full width.', 'essential-addons-for-elementor-lite'),
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'stretch',
                'default'      => 'no',
                'prefix_class' => 'eael-simple-menu--',
            ]
        );

        $this->end_controls_section();

        /**
         * Style: Main Menu
         */

        $this->style_menu();

        /**
         * Style: Top Level Items
         */
        $this->style_top_level_item();

        /**
         * Style: Mobile Menu
         */
        $this->start_controls_section(
            'eael_simple_menu_section_style_mobile_menu',
            [
                'label' => __('Hamburger Menu', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
	                'eael_simple_menu_layout' => ['horizontal'],
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_hamburger_bg',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu-container .eael-simple-menu-toggle' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_hamburger_icon',
            [
                'label'     => __('Icon Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu-container .eael-simple-menu-toggle .eicon-menu-bar' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->end_controls_section();

        /**
         * Style: Dropdown Menu
         */
        $this->start_controls_section(
            'eael_simple_menu_section_style_dropdown',
            [
                'label' => __('Dropdown Menu', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_animation',
            [
                'label'     => __('Animation', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'eael-simple-menu-dropdown-animate-fade'     => __('Fade', 'essential-addons-for-elementor-lite'),
                    'eael-simple-menu-dropdown-animate-to-top'   => __('To Top', 'essential-addons-for-elementor-lite'),
                    'eael-simple-menu-dropdown-animate-zoom-in'  => __('ZoomIn', 'essential-addons-for-elementor-lite'),
                    'eael-simple-menu-dropdown-animate-zoom-out' => __('ZoomOut', 'essential-addons-for-elementor-lite'),
                ],
                'default'   => 'eael-simple-menu-dropdown-animate-to-top',
                'condition' => [
                    'eael_simple_menu_layout' => ['horizontal'],
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_background',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
//                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_simple_menu_dropdown_border',
                'label'    => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-simple-menu li ul',
            ]
        );

        $this->add_responsive_control(
            'eael_simple_menu_dropdown_border_radius',
            [
                'label'      => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-simple-menu li ul' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_simple_menu_dropdown_padding',
            [
                'label'      => __('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-simple-menu li ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_simple_menu_dropdown_box_shadow',
                'label'    => __('Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-simple-menu li ul',
            ]
        );

        $this->end_controls_section();

        /**
         * Style: Main Menu (Hover)
         */
        $this->style_dropdown_item();
    }

    protected function style_menu()
    {
        $this->start_controls_section(
            'eael_simple_menu_section_style_menu',
            [
                'label' => __('Main Menu', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eael_simple_menu_background',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
//                'default' => '#54595f',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu-container'                                               => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu-container .eael-simple-menu.eael-simple-menu-horizontal' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_simple_menu_border',
                'label'    => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-simple-menu-container, {{WRAPPER}} .eael-simple-menu-container .eael-simple-menu-toggle, {{WRAPPER}} .eael-simple-menu.eael-simple-menu-horizontal.eael-simple-menu-responsive',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_simple_menu_box_shadow',
                'label'    => __('Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-simple-menu-container',
            ]
        );

        $this->end_controls_section();
    }

    protected function style_top_level_item()
    {
        $this->start_controls_section(
            'eael_simple_menu_section_style_top_level_item',
            [
                'label' => __('Top Level Item', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_padding',
            [
                'label'      => __('Item Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-simple-menu li a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'eael_simple_menu_item_alignment',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'eael-simple-menu-align-left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'eael-simple-menu-align-center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'eael-simple-menu-align-right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'   => 'eael-simple-menu-align-left',
                'condition' => [
                    'eael_simple_menu_preset!' => ['preset-2', 'preset-3']
                ]
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_alignment_right',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'eael-simple-menu-align-left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'eael-simple-menu-align-center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'eael-simple-menu-align-right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'   => 'eael-simple-menu-align-right',
                'condition' => [
                    'eael_simple_menu_preset' => ['preset-3']
                ]
            ]
        );
        $this->add_control(
            'eael_simple_menu_item_alignment_center',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'eael-simple-menu-align-left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'eael-simple-menu-align-center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'eael-simple-menu-align-right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'   => 'eael-simple-menu-align-center',
                'condition' => [
                    'eael_simple_menu_preset' => ['preset-2']
                ]
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_divider_color',
            [
                'label'     => __('Divider Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-horizontal:not(.eael-simple-menu-responsive) > li > a'                                            => 'border-right: 1px solid {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu-align-center .eael-simple-menu.eael-simple-menu-horizontal:not(.eael-simple-menu-responsive) > li:first-child > a' => 'border-left: 1px solid {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu-align-right .eael-simple-menu.eael-simple-menu-horizontal:not(.eael-simple-menu-responsive) > li:first-child > a'  => 'border-left: 1px solid {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-horizontal.eael-simple-menu-responsive > li:not(:last-child) > a'                                 => 'border-bottom: 1px solid {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-vertical > li:not(:last-child) > a'                                                               => 'border-bottom: 1px solid {{VALUE}}',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_simple_menu_item_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme'   => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-simple-menu >li > a, .eael-simple-menu-container .eael-simple-menu-toggle-text',
            ]
        );

        $this->start_controls_tabs('eael_simple_menu_top_level_item');

        $this->start_controls_tab(
            'eael_simple_menu_top_level_item_default',
            [
                'label' => __('Default', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_color',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
//                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li > a'      => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li > a > span svg'      => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu-toggle-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_background',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_heading',
            [
                'label'     => __('Dropdown Indicator', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator',
            [
                'label'                  => __('Icon', 'essential-addons-for-elementor-lite'),
                'type'                   => Controls_Manager::ICONS,
                'recommended'            => [
                    'fa-solid' => [
                        'fas fa-angle-down',
                    ]
                ],
                'default'                => [
                    'value'   => 'fas fa-angle-down',
                    'library' => 'fa-solid',
                ],
            ]
        );

	    $this->add_control(
		    'eael_simple_menu_item_indicator_size',
		    [
			    'label' => esc_html__( 'Icon Size', 'essential-addons-elementor' ),
			    'type' => Controls_Manager::SLIDER,
			    'default' => [
				    'size' => '15'
			    ],
			    'range' => [
				    'px' => [
					    'max' => 30,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu li a span, {{WRAPPER}} .eael-simple-menu li span.eael-simple-menu-indicator' => 'font-size: {{SIZE}}{{UNIT}};',
				    '{{WRAPPER}} .eael-simple-menu li span.eael-simple-menu-indicator svg, {{WRAPPER}} .indicator-svg svg'	=> 'width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_control(
            'eael_simple_menu_item_indicator_note',
            [
                'label'      => __('Important Note', 'essential-addons-for-elementor-lite'),
                'show_label' => false,
                'type'       => Controls_Manager::RAW_HTML,
                'raw'        => __('<div style="font-size: 11px;font-style:italic;line-height:1.4;color:#a4afb7;">Following options are only available in the <span style="color:#d30c5c"><strong>Small</strong></span> screens for <span style="color:#d30c5c"><strong>Horizontal</strong></span> Layout, and all screens for <span style="color:#d30c5c"><strong>Vertical</strong></span> Layout</div>', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f44336',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_background',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_border',
            [
                'label'     => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f44336',
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
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
//                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li:hover > a'                 => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li:hover > a > span svg'                 => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-item > a'     => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-ancestor > a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_background_hover',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
//                'default' => '#ee355f',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li:hover > a'                 => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-item > a'     => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-ancestor > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_hover_indicator_heading',
            [
                'label'     => __('Dropdown Indicator', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_hover_indicator_note',
            [
                'label'      => __('Important Note', 'essential-addons-for-elementor-lite'),
                'show_label' => false,
                'type'       => Controls_Manager::RAW_HTML,
                'raw'        => __('<div style="font-size: 11px;font-style:italic;line-height:1.4;color:#a4afb7;">Following options are only available in the <span style="color:#d30c5c"><strong>Small</strong></span> screens for <span style="color:#d30c5c"><strong>Horizontal</strong></span> Layout, and all screens for <span style="color:#d30c5c"><strong>Vertical</strong></span> Layout</div>', 'essential-addons-for-elementor-lite'),

            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_color_hover',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f44336',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator:hover:before'                           => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator.eael-simple-menu-indicator-open:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_background_hover',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator:hover'                           => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator.eael-simple-menu-indicator-open' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_border_hover',
            [
                'label'     => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f44336',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator:hover'                           => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator.eael-simple-menu-indicator-open' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function style_dropdown_item()
    {
        $this->start_controls_section(
            'eael_simple_menu_section_style_dropdown_item',
            [
                'label' => __('Dropdown Item', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_alignment',
            [
                'label'   => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'eael-simple-menu-dropdown-align-left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'eael-simple-menu-dropdown-align-center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'eael-simple-menu-dropdown-align-right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default' => 'eael-simple-menu-dropdown-align-left',
            ]
        );

        $this->add_responsive_control(
            'eael_simple_menu_dropdown_item_padding',
            [
                'label'      => __('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-horizontal li ul li a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_divider_color',
            [
                'label'     => __('Divider Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f2f2f2',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-horizontal li ul li > a' => 'border-bottom: 1px solid {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-vertical li ul li > a'   => 'border-bottom: 1px solid {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_simple_menu_dropdown_item_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme'   => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-simple-menu li ul li > a',
            ]
        );

        $this->start_controls_tabs('eael_simple_menu_dropdown_item');

        $this->start_controls_tab(
            'eael_simple_menu_dropdown_item_default',
            [
                'label' => __('Default', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_color',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
//                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li > a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_background',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
//                'default' => 'rgba(255,255,255,0)',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_heading',
            [
                'label'     => __('Dropdown Indicator', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator',
            [
                'label'       => __('Icon', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::ICONS,
                'recommended' => [
                    'fa-solid' => [
                        'fas fa-angle-down',
                    ]
                ],
                'default'     => [
                    'value'   => 'fas fa-angle-down',
                    'library' => 'fa-solid',
                ],
            ]
        );

	    $this->add_control(
		    'eael_simple_menu_dropdown_item_indicator_size',
		    [
			    'label' => esc_html__( 'Icon Size', 'essential-addons-elementor' ),
			    'type' => Controls_Manager::SLIDER,
			    'default' => [
				    'size' => '12'
			    ],
			    'range' => [
				    'px' => [
					    'max' => 30,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu li ul li a span, {{WRAPPER}} .eael-simple-menu li ul li span.eael-simple-menu-indicator' => 'font-size: {{SIZE}}{{UNIT}};',
				    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator svg, {{WRAPPER}} .eael-simple-menu li ul li a .indicator-svg svg'	=> 'width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_note',
            [
                'label'      => __('Important Note', 'essential-addons-for-elementor-lite'),
                'show_label' => false,
                'type'       => Controls_Manager::RAW_HTML,
                'raw'        => __('<div style="font-size: 11px;font-style:italic;line-height:1.4;color:#a4afb7;">Following options are only available in the <span style="color:#d30c5c"><strong>Small</strong></span> screens for <span style="color:#d30c5c"><strong>Horizontal</strong></span> Layout, and all screens for <span style="color:#d30c5c"><strong>Vertical</strong></span> Layout</div>', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f44336',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_background',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_border',
            [
                'label'     => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f44336',
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
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
//                'default' => '#ee355f',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li:hover > a'                 => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li.current-menu-item > a'     => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li.current-menu-ancestor > a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_background_hover',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
//                'default' => 'rgba(255,255,255,0)',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li:hover > a'                 => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li.current-menu-item > a'     => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li.current-menu-ancestor > a' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_hover_indicator_heading',
            [
                'label'     => __('Dropdown Indicator', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_hover_indicator_note',
            [
                'label'      => __('Important Note', 'essential-addons-for-elementor-lite'),
                'show_label' => false,
                'type'       => Controls_Manager::RAW_HTML,
                'raw'        => __('<div style="font-size: 11px;font-style:italic;line-height:1.4;color:#a4afb7;">Following options are only available in the <span style="color:#d30c5c"><strong>Small</strong></span> screens for <span style="color:#d30c5c"><strong>Horizontal</strong></span> Layout, and all screens for <span style="color:#d30c5c"><strong>Vertical</strong></span> Layout</div>', 'essential-addons-for-elementor-lite'),

            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_color_hover',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f44336',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator:hover:before'                           => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator.eael-simple-menu-indicator-open:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_background_hover',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator:hover'                           => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator.eael-simple-menu-indicator-open' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_border_hover',
            [
                'label'     => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f44336',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator:hover'                           => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator.eael-simple-menu-indicator-open' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings();

        if ($settings['eael_simple_menu_preset'] == 'preset-2') {
            $align = $settings['eael_simple_menu_item_alignment_center'];
        } elseif ($settings['eael_simple_menu_preset'] == 'preset-3') {
            $align = $settings['eael_simple_menu_item_alignment_right'];
        } else {
            $align = $settings['eael_simple_menu_item_alignment'];
        }

        $menu_classes      = ['eael-simple-menu', $settings['eael_simple_menu_dropdown_animation'], 'eael-simple-menu-indicator'];
        $container_classes = ['eael-simple-menu-container', $align, $settings['eael_simple_menu_dropdown_item_alignment'], $settings['eael_simple_menu_preset']];

        if ($settings['eael_simple_menu_layout'] == 'horizontal') {
            $menu_classes[] = 'eael-simple-menu-horizontal';
        } else {
            $menu_classes[] = 'eael-simple-menu-vertical';
        }

        if (isset($settings['eael_simple_menu_item_dropdown_indicator']) && $settings['eael_simple_menu_item_dropdown_indicator'] == 'yes') {
            $menu_classes[] = 'eael-simple-menu-indicator';
        }

        if ($settings['eael_simple_menu_item_indicator']['library'] == 'svg'){
	        ob_start();
	        Icons_Manager::render_icon( $settings['eael_simple_menu_item_indicator'], [ 'aria-hidden' => 'true' ] );
	        $indicator_icon = ob_get_clean();
	        $this->add_render_attribute( 'eael-simple-menu', 'data-indicator-class', $indicator_icon );
	        $this->add_render_attribute( 'eael-simple-menu', 'data-indicator', 'svg' );
        } else {
	        $this->add_render_attribute( 'eael-simple-menu', 'data-indicator-class', $settings['eael_simple_menu_item_indicator']['value'] );
        }

	    if ($settings['eael_simple_menu_dropdown_item_indicator']['library']=='svg'){
		    ob_start();
		    Icons_Manager::render_icon( $settings['eael_simple_menu_dropdown_item_indicator'] );
		    $dropdown_indicator_icon = ob_get_clean();
		    $this->add_render_attribute( 'eael-simple-menu', 'data-dropdown-indicator-class', $dropdown_indicator_icon );
		    $this->add_render_attribute( 'eael-simple-menu', 'data-dropdown-indicator', 'svg' );
	    } else {
		    $this->add_render_attribute( 'eael-simple-menu', 'data-dropdown-indicator-class', $settings['eael_simple_menu_dropdown_item_indicator']['value'] );
	    }


        $this->add_render_attribute('eael-simple-menu', [
            'class'                         => implode(' ', array_filter($container_classes)),
//            'data-indicator-class'          => $settings['eael_simple_menu_item_indicator'],
//            'data-dropdown-indicator-class' => $settings['eael_simple_menu_dropdown_item_indicator'],
        ]);

        if ($settings['eael_simple_menu_menu']) {
            $args = [
                'menu'        => $settings['eael_simple_menu_menu'],
                'menu_class'  => implode(' ', array_filter($menu_classes)),
                'fallback_cb' => '__return_empty_string',
                'container'   => false,
                'echo'        => false,
            ];

            echo '<div ' . $this->get_render_attribute_string('eael-simple-menu') . '>' . wp_nav_menu($args) . '</div>';

        }
    }

}

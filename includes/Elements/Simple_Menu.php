<?php
namespace Essential_Addons_Elementor\Elements;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
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

    /**
     * Forcefully enqueue elementor icon library
     *
     * @return string[]
     */
    public function get_style_depends()
    {
        return ['elementor-icons'];
    }

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
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
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'      => 'right',
                'prefix_class' => 'eael-simple-menu-hamburger-align-',
//                'condition'    => [
//                    'eael_simple_menu_hamburger_disable_selected_menu' => 'hide',
//                ],
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
                'default'      => 'no',
            ]
        );

        $this->add_control(
	       'eael_simple_menu_hamburger_icon',
	       [
	           'label'       => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
	           'type'        => Controls_Manager::ICONS,
	           'default'     => [
	               'value'   => 'fas fa-bars',
	               'library' => 'fa-solid',
	           ],
	       ]
	   );

       $this->add_control(
            'eael_simple_menu_heading_mobile_dropdown',
            [
                'label' => esc_html__( 'Mobile Dropdown', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $default_value = 'tablet';

        $this->add_control(
            'eael_simple_menu_dropdown',
            [
                'label' => esc_html__( 'Breakpoint', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => esc_html( $default_value ),
                'options' => $this->get_dropdown_options(),
                'prefix_class' => 'eael-hamburger--',
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
            ]
        );
	    $this->add_responsive_control(
		    'eael_simple_menu_hamburger_min_height',
		    [
			    'label' => esc_html__( 'Min Height', 'essential-addons-for-elementor-lite' ),
			    'type' => Controls_Manager::SLIDER,
//			    'devices' => [ 'tablet', 'mobile' ],
//			    'devices' => [ 'desktop', 'mobile' ],
			    'range' => [
				    'px' => [
					    'max' => 500,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu-container.eael-simple-menu-hamburger' => 'min-height: {{SIZE}}{{UNIT}};',
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
		    'eael_simple_menu_hamburger_size',
		    [
			    'label' => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
			    'type' => Controls_Manager::SLIDER,
			    'range' => [
				    'px' => [
					    'max' => 30,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu-container .eael-simple-menu-toggle i' => 'font-size: {{SIZE}}{{UNIT}};',
				    '{{WRAPPER}} .eael-simple-menu-container .eael-simple-menu-toggle svg' => 'width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
            'eael_simple_menu_hamburger_icon_color',
            [
                'label'     => __('Icon Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu-container .eael-simple-menu-toggle i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu-container .eael-simple-menu-toggle svg' => 'fill: {{VALUE}}',
                ],

            ]
        );

	    $this->add_responsive_control(
		    'eael_simple_menu_hamburger_padding',
		    [
			    'label'      => __('Padding', 'essential-addons-for-elementor-lite'),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%', 'em'],
			    'selectors'  => [
				    '{{WRAPPER}} .eael-simple-menu-container .eael-simple-menu-toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_simple_menu_hamburger_item_heading',
		    [
			    'label'     => __('Items', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::HEADING,
			    'separator' => 'before',
		    ]
	    );

	    $this->add_control(
		    'eael_hamburger_menu_item_alignment',
		    [
			    'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::CHOOSE,
			    'options'   => [
				    'eael-hamburger-left'   => [
					    'title' => __('Left', 'essential-addons-for-elementor-lite'),
					    'icon'  => 'eicon-text-align-left',
				    ],
				    'eael-hamburger-center' => [
					    'title' => __('Center', 'essential-addons-for-elementor-lite'),
					    'icon'  => 'eicon-text-align-center',
				    ],
				    'eael-hamburger-right'  => [
					    'title' => __('Right', 'essential-addons-for-elementor-lite'),
					    'icon'  => 'eicon-text-align-right',
				    ],
			    ],
			    'condition' => [
				    'eael_simple_menu_preset!' => ['preset-2', 'preset-3']
			    ]
		    ]
	    );

	    $this->start_controls_tabs('eael_simple_menu_hamburger_top_level_item');

	    $this->start_controls_tab(
		    'eael_simple_menu_hamburger_top_level_item_default',
		    [
			    'label' => __('Default', 'essential-addons-for-elementor-lite'),
		    ]
	    );

	    $this->add_control(
		    'eael_simple_menu_hamburger_top_level_item_color',
		    [
			    'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
//                'default' => '#ffffff',
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li > a'      => 'color: {{VALUE}}',
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li > a > span svg'      => 'fill: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_simple_menu_hamburger_top_level_item_background',
		    [
			    'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li > a' => 'background-color: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'eael_simple_menu_hamburger_top_level_item_hover',
		    [
			    'label' => __('Hover', 'essential-addons-for-elementor-lite'),
		    ]
	    );

	    $this->add_control(
		    'eael_simple_menu_hamburger_top_level_item_color_hover',
		    [
			    'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
//                'default' => '#ffffff',
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li:hover > a'      => 'color: {{VALUE}}',
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li:hover > a > span svg'      => 'fill: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_simple_menu_hamburger_top_level_item_bg_hover',
		    [
			    'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li:hover > a' => 'background-color: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'eael_simple_menu_hamburger_top_level_item_active',
		    [
			    'label' => __('Active', 'essential-addons-for-elementor-lite'),
		    ]
	    );

	    $this->add_control(
		    'eael_simple_menu_hamburger_top_level_item_color_active',
		    [
			    'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
//                'default' => '#ffffff',
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li.current-menu-item > a.eael-item-active'      => 'color: {{VALUE}}',
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li.current-menu-item > a.eael-item-active > span svg'      => 'fill: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_simple_menu_hamburger_top_level_item_bg_active',
		    [
			    'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li.current-menu-item > a.eael-item-active' => 'background-color: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->end_controls_tab();
	    $this->end_controls_tabs();

	    $this->add_control(
		    'eael_simple_menu_hamburger_dropdown_heading',
		    [
			    'label'     => __('Dropdown Items', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::HEADING,
			    'separator' => 'before',
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_simple_menu_hamburger_dropdown_item_padding',
		    [
			    'label'      => __('Padding', 'essential-addons-for-elementor-lite'),
			    'type'       => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', '%', 'em'],
			    'selectors'  => [
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->start_controls_tabs('eael_simple_menu_hamburger_dropdown_item');

	    $this->start_controls_tab(
		    'eael_simple_menu_hamburger_dropdown_item_default',
		    [
			    'label' => __('Default', 'essential-addons-for-elementor-lite'),
		    ]
	    );

	    $this->add_control(
		    'eael_simple_menu_hamburger_dropdown_item_color',
		    [
			    'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
//                'default' => '#ffffff',
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li ul li a'      => 'color: {{VALUE}}',
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li ul li > span svg'      => 'fill: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_simple_menu_hamburger_dropdown_item_background',
		    [
			    'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li ul li a' => 'background-color: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->end_controls_tab();

	    $this->start_controls_tab(
		    'eael_simple_menu_hamburger_dropdown_item_hover',
		    [
			    'label' => __('Hover', 'essential-addons-for-elementor-lite'),
		    ]
	    );

	    $this->add_control(
		    'eael_simple_menu_hamburger_dropdown_item_color_hover',
		    [
			    'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
//                'default' => '#ffffff',
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li ul li:hover a'      => 'color: {{VALUE}}',
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li ul li:hover a > span svg'      => 'fill: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_simple_menu_hamburger_dropdown_item_bg_hover',
		    [
			    'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::COLOR,
			    'default'   => '',
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive li ul li:hover a' => 'background-color: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->end_controls_tab();
	    $this->end_controls_tabs();

	    $this->add_control(
		    'eael_simple_menu_hamburger_dropdown_a',
		    [
			    'label'     => __('Dropdown Indicator', 'essential-addons-for-elementor-lite'),
			    'type'      => Controls_Manager::HEADING,
			    'separator' => 'before',
		    ]
	    );

	    $this->add_control(
		    'eael_simple_menu_hamburger_indicator_possition',
		    [
			    'label' => esc_html__( 'Top Position', 'essential-addons-for-elementor-lite' ),
			    'type' => Controls_Manager::SLIDER,
//			    'range' => [
//				    'px' => [
//					    'max' => 30,
//				    ],
//			    ],
			    'size_units' => ['px', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .eael-simple-menu.eael-simple-menu-responsive .eael-simple-menu-indicator' => 'top: {{SIZE}}{{UNIT}};',
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

        $this->add_responsive_control(
            'eael_simple_menu_item_padding',
            [
                'label'      => __('Item Padding', 'essential-addons-for-elementor-lite'),
	            'type'       => Controls_Manager::DIMENSIONS,
	            'size_units' => ['px', '%', 'em'],
	            'selectors'  => [
		            '{{WRAPPER}} .eael-simple-menu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'eael-simple-menu-align-center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'eael-simple-menu-align-right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-right',
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
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'eael-simple-menu-align-center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'eael-simple-menu-align-right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-right',
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
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'eael-simple-menu-align-center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'eael-simple-menu-align-right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-right',
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
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_PRIMARY
                ],
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
			    'label' => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
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
                    '{{WRAPPER}} .eael-simple-menu li a span, {{WRAPPER}} .eael-simple-menu li span.eael-simple-menu-indicator'   => 'font-size: {{SIZE}}{{UNIT}};',
				    '{{WRAPPER}} .eael-simple-menu li a span, {{WRAPPER}} .eael-simple-menu li span.eael-simple-menu-indicator i' => 'font-size: {{SIZE}}{{UNIT}};',
				    '{{WRAPPER}} .eael-simple-menu li span.eael-simple-menu-indicator svg'                                        => 'width: {{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};line-height:{{SIZE}}{{UNIT}};',
				    '{{WRAPPER}} .eael-simple-menu li span svg'                                                                   => 'width: {{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};line-height:{{SIZE}}{{UNIT}};',
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
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li a span'                             => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-simple-menu li a span svg path'                         => 'fill: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator:before' => 'color: {{VALUE}} !important',
	                '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator i'      => 'color: {{VALUE}} !important',
	                '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator svg path'    => 'fill: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_background',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li a span'                      => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator' => 'background-color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_border',
            [
                'label'     => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li a span'                      => 'border-color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator' => 'border-color: {{VALUE}} !important',
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
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator:hover:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator:hover i'      => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator:hover svg'    => 'fill: {{VALUE}}',                ],
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
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_simple_menu_top_level_item_active',
            [
                'label' => __('Active', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_color_active',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
//                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li > a.eael-item-active'     => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-item > a > span svg'           => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-item > a.eael-item-active'     => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-ancestor > a.eael-item-active' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_background_active',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
//                'default' => '#ee355f',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li > a.eael-item-active'     => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-item > a.eael-item-active'     => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li.current-menu-ancestor > a.eael-item-active' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_hover_indicator_heading_active',
            [
                'label'     => __('Dropdown Indicator', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_hover_indicator_note_active',
            [
                'label'      => __('Important Note', 'essential-addons-for-elementor-lite'),
                'show_label' => false,
                'type'       => Controls_Manager::RAW_HTML,
                'raw'        => __('<div style="font-size: 11px;font-style:italic;line-height:1.4;color:#a4afb7;">Following options are only available in the <span style="color:#d30c5c"><strong>Small</strong></span> screens for <span style="color:#d30c5c"><strong>Horizontal</strong></span> Layout, and all screens for <span style="color:#d30c5c"><strong>Vertical</strong></span> Layout</div>', 'essential-addons-for-elementor-lite'),

            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_color_active',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f44336',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator.eael-simple-menu-indicator-open:before' => 'color: {{VALUE}} !important',
	                '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator.eael-simple-menu-indicator-open svg'    => 'fill: {{VALUE}} !important',
	                '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator.eael-simple-menu-indicator-open i'      => 'color: {{VALUE}} !important',                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_background_active',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator.eael-simple-menu-indicator-open' => 'background-color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_item_indicator_border_active',
            [
                'label'     => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f44336',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li .eael-simple-menu-indicator.eael-simple-menu-indicator-open' => 'border-color: {{VALUE}} !important',
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
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'eael-simple-menu-dropdown-align-center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'eael-simple-menu-dropdown-align-right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-text-align-right',
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
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_PRIMARY
                ],
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
			    'label' => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
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
                    '{{WRAPPER}} .eael-simple-menu li ul li a span'                            => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-simple-menu li ul li span.eael-simple-menu-indicator'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-simple-menu li ul li span.eael-simple-menu-indicator i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator svg'   => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}};',
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
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator:before' => 'color: {{VALUE}} !important',
	                '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator svg'    => 'fill: {{VALUE}} !important',
	                '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator i'      => 'color: {{VALUE}} !important',
	                '{{WRAPPER}} .eael-simple-menu li ul li a span.eael-simple-menu-dropdown-indicator'      => 'color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_background',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-simple-menu li ul li a span.eael-simple-menu-dropdown-indicator' => 'background-color: {{VALUE}} !important',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_dropdown_item_indicator_border',
            [
                'label'     => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator' => 'border-color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-simple-menu li ul li a span.eael-simple-menu-dropdown-indicator' => 'border-color: {{VALUE}} !important',
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
                    '{{WRAPPER}} .eael-simple-menu li ul li.current-menu-item > a.eael-item-active'     => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li.current-menu-ancestor > a.eael-item-active' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .eael-simple-menu li ul li.current-menu-item > a.eael-item-active'     => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-simple-menu li ul li.current-menu-ancestor > a.eael-item-active' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator:hover:before'                           => 'color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator.eael-simple-menu-indicator-open:before' => 'color: {{VALUE}} !important',
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
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator:hover'                           => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator.eael-simple-menu-indicator-open' => 'background-color: {{VALUE}} !important',
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
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator:hover'                           => 'border-color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-simple-menu li ul li .eael-simple-menu-indicator.eael-simple-menu-indicator-open' => 'border-color: {{VALUE}} !important',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    public function get_dropdown_options(){
        $breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

        $dropdown_options = [];
        $excluded_breakpoints = [
            'laptop',
            'widescreen',
        ];

        foreach ( $breakpoints as $breakpoint_key => $breakpoint_instance ) {
            // Do not include laptop and widscreen in the options since this feature is for mobile devices.
            if ( in_array( $breakpoint_key, $excluded_breakpoints, true ) ) {
                continue;
            }

            $dropdown_options[ $breakpoint_key ] = sprintf(
                /* translators: 1: Breakpoint label, 2: `>` character, 3: Breakpoint value */
                esc_html__( '%1$s (%2$s %3$dpx)', 'essential-addons-for-elementor-lite' ),
                $breakpoint_instance->get_label(),
                '>',
                $breakpoint_instance->get_value()
            );
        }

        $dropdown_options['desktop']    = esc_html__( 'Desktop (> 2400px)', 'essential-addons-for-elementor-lite' );
        $dropdown_options['none']       = esc_html__( 'None', 'essential-addons-for-elementor-lite' );
        
        return $dropdown_options;
    }

    protected function render()
    {
        $settings = $this->get_settings();
        $hamburger_device = !empty( $settings['eael_simple_menu_dropdown'] ) ? esc_html( $settings['eael_simple_menu_dropdown'] ) : esc_html( 'tablet' );
       
        if ( $settings['eael_simple_menu_preset'] == 'preset-2' ) {
		    $align = $settings['eael_simple_menu_item_alignment_center'];
	    } elseif ( $settings['eael_simple_menu_preset'] == 'preset-3' ) {
		    $align = $settings['eael_simple_menu_item_alignment_right'];
	    } else {
		    $align = $settings['eael_simple_menu_item_alignment'];
	    }

	    if ( $settings['eael_simple_menu_full_width'] == 'yes' ) {
		    $fullWidth = 'eael-simple-menu--stretch';
	    } else {
		    $fullWidth = '';
	    }

        $menu_classes      = ['eael-simple-menu', $settings['eael_simple_menu_dropdown_animation'], 'eael-simple-menu-indicator', $settings['eael_hamburger_menu_item_alignment']];
        $container_classes = ['eael-simple-menu-container', $align, $fullWidth, $settings['eael_simple_menu_dropdown_item_alignment'], $settings['eael_simple_menu_preset']];

        if ($settings['eael_simple_menu_layout'] == 'horizontal') {
            $menu_classes[] = 'eael-simple-menu-horizontal';
        } else {
            $menu_classes[] = 'eael-simple-menu-vertical';
        }

        if (isset($settings['eael_simple_menu_item_dropdown_indicator']) && $settings['eael_simple_menu_item_dropdown_indicator'] == 'yes') {
            $menu_classes[] = 'eael-simple-menu-indicator';
        }

        if (isset($settings['eael_simple_menu_hamburger_icon'])) {
            ob_start();
            Icons_Manager::render_icon( $settings['eael_simple_menu_hamburger_icon'], [ 'aria-hidden' => 'true' ] );
            $hamburger_icon = ob_get_clean();
            $this->add_render_attribute( 'eael-simple-menu', 'data-hamburger-icon', $hamburger_icon );
        }

        ob_start();
	    Icons_Manager::render_icon( $settings['eael_simple_menu_item_indicator'], [ 'aria-hidden' => 'true' ] );
	    $indicator_icon = ob_get_clean();
	    $this->add_render_attribute( 'eael-simple-menu', 'data-indicator-icon', $indicator_icon );

	    ob_start();
	    Icons_Manager::render_icon( $settings['eael_simple_menu_dropdown_item_indicator'] );
	    $dropdown_indicator_icon = ob_get_clean();
	    $this->add_render_attribute( 'eael-simple-menu', 'data-dropdown-indicator-icon', $dropdown_indicator_icon );

	    $this->add_render_attribute( 'eael-simple-menu', [
		    'class'                      => implode( ' ', array_filter( $container_classes ) ),
		    'data-hamburger-breakpoints' => wp_json_encode( $this->get_dropdown_options() ),
		    'data-hamburger-device'      => $hamburger_device,
	    ] );
        
        if ($settings['eael_simple_menu_menu']) {
            $args = [
                'menu'        => $settings['eael_simple_menu_menu'],
                'menu_class'  => implode(' ', array_filter($menu_classes)),
                'fallback_cb' => '__return_empty_string',
                'container'   => false,
                'echo'        => false,
            ];

	        //Check breakpoint form hamburger options
	        if ( ! empty( $hamburger_device ) && 'none' !== $hamburger_device ) {
		        if ( 'desktop' === $hamburger_device ) {
			        $breakpoints                     = method_exists( Plugin::$instance->breakpoints, 'get_breakpoints_config' ) ? Plugin::$instance->breakpoints->get_breakpoints_config() : [];
			        $eael_get_breakpoint_from_option = isset( $breakpoints['widescreen'] ) ? $breakpoints['widescreen']['value'] - 1 : 2400;
		        } else {
			        $eael_get_breakpoint_from_option = Plugin::$instance->breakpoints->get_breakpoints( $hamburger_device )->get_value();
		        }

		        echo "<style>
                        @media screen and (max-width: {$eael_get_breakpoint_from_option}px) {
                            .eael-hamburger--{$hamburger_device} {
                                .eael-simple-menu-horizontal,
                                .eael-simple-menu-vertical {
                                    display: none;
                                }
                            }
                            .eael-hamburger--{$hamburger_device} {
                                .eael-simple-menu-container .eael-simple-menu-toggle {
                                    display: block;
                                }
                            }
                        }
                    </style>";
	        }
            ?>
            <div <?php echo $this->get_render_attribute_string('eael-simple-menu'); ?>>
                <?php echo wp_nav_menu( $args ); ?>
                <button class="eael-simple-menu-toggle">
                    <span class="sr-only "><?php esc_html_e( 'Humberger Toggle Menu', 'essential-addons-for-elementor-lite' ); ?></span>
                    <?php Icons_Manager::render_icon( $settings['eael_simple_menu_hamburger_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </button>
            </div>
            <?php
        }
    }

}

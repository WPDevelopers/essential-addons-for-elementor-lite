<?php
namespace Essential_Addons_Elementor\Elements;

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Skins\Skin_Default;
use \Essential_Addons_Elementor\Skins\Skin_Five;
use \Essential_Addons_Elementor\Skins\Skin_Four;
use \Essential_Addons_Elementor\Skins\Skin_One;
use \Essential_Addons_Elementor\Skins\Skin_Seven;
use \Essential_Addons_Elementor\Skins\Skin_Six;
use \Essential_Addons_Elementor\Skins\Skin_Three;
use \Essential_Addons_Elementor\Skins\Skin_Two;

use \Essential_Addons_Elementor\Classes\Helper;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class Simple_Menu extends Widget_Base
{

    protected $_has_template_content = false;

    public function get_name()
    {
        return 'eael-simple-menu';
    }

    public function get_title()
    {
        return esc_html__('Simple Menu', 'essential-addons-elementor');
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
            'megamenu',
            'mega menu',
            'ea megamenu',
            'ea mega menu',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/simple-menu/';
    }

    protected function _register_skins()
    {
        $this->add_skin(new Skin_Default($this));
        $this->add_skin(new Skin_One($this));
        $this->add_skin(new Skin_Two($this));
        $this->add_skin(new Skin_Three($this));
        $this->add_skin(new Skin_Four($this));
        $this->add_skin(new Skin_Five($this));
        $this->add_skin(new Skin_Six($this));
        $this->add_skin(new Skin_Seven($this));
    }

    protected function _register_controls()
    {
        /**
         * Content: General
         */
        $this->start_controls_section(
            'eael_simple_menu_section_general',
            [
                'label' => esc_html__('General', 'essential-addons-elementor'),
            ]
        );

        $this->add_control(
            'eael_simple_menu_menu',
            [
                'label' => esc_html__('Select Menu', 'essential-addons-elementor'),
                'description' => sprintf(__('Go to the <a href="%s" target="_blank">Menu screen</a> to manage your menus.', 'essential-addons-elementor'), admin_url('nav-menus.php')),
                'type' => Controls_Manager::SELECT,
                'label_block' => false,
                'options' => Helper::get_simple_menus(),

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'eael_simple_menu_section_hamburger',
            [
                'label' => esc_html__('Hamburger Options', 'essential-addons-elementor'),
            ]
        );

        $this->add_control(
          'eael_simple_menu_hamburger_disable_selected_menu',
          [
            'label' => esc_html__('Disable Selected Menu', 'essential-addons-elementor'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __( 'Yes', 'essential-addons-elementor' ),
            'label_off' => __( 'No', 'essential-addons-elementor' ),
            'return_value' => 'hide',
            'default' => 'no',
            'prefix_class' => 'eael_simple_menu_hamburger_disable_selected_menu_',
          ]
        );

        $this->add_control(
          'eael_simple_menu_hamburger_alignment',
          [
              'label' => __('Hamburger Alignment', 'essential-addons-elementor'),
              'type' => Controls_Manager::CHOOSE,
              'options' => [
                  'left' => [
                      'title' => __('Left', 'essential-addons-elementor'),
                      'icon' => 'fa fa-align-left',
                  ],
                  'center' => [
                      'title' => __('Center', 'essential-addons-elementor'),
                      'icon' => 'fa fa-align-center',
                  ],
                  'right' => [
                      'title' => __('Right', 'essential-addons-elementor'),
                      'icon' => 'fa fa-align-right',
                  ],
              ],
              'default' => 'eael-simple-menu-hamburger-align-right',
              'prefix_class' => 'eael-simple-menu-hamburger-align-',
              'condition' => [
                'eael_simple_menu_hamburger_disable_selected_menu' => 'hide',
              ],
          ]
      );

      $this->add_control(
        'eael_simple_menu_full_width',
        [
          'label' => __( 'Full Width', 'essential-addons-elementor' ),
          'type' => Controls_Manager::SWITCHER,
          'description' => __( 'Stretch the dropdown of the menu to full width.', 'essential-addons-elementor' ),
          'label_on' => __( 'Yes', 'essential-addons-elementor' ),
          'label_off' => __( 'No', 'essential-addons-elementor' ),
          'return_value' => 'stretch',
          'default' => 'no',
          'prefix_class' => 'eael-simple-menu--',
        ]
      );

        $this->end_controls_section();

        /**
         * Style: Main Menu
         */
        $this->start_controls_section(
            'eael_simple_menu_section_style_menu',
            [
                'label' => __('Main Menu', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();

        /**
         * Style: Mobile Menu
         */
        $this->start_controls_section(
            'eael_simple_menu_section_style_mobile_menu',
            [
                'label' => __('Hamburger Menu', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_simple_menu_hamburger_bg',
            [
                'label' => __('Background Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .eael-simple-menu-container .eael-simple-menu-toggle' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_simple_menu_hamburger_icon',
            [
                'label' => __('Icon Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
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
                'label' => __('Dropdown Menu', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();

        /**
         * Style: Top Level Items
         */
        $this->start_controls_section(
            'eael_simple_menu_section_style_top_level_item',
            [
                'label' => __('Top Level Item', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();

        /**
         * Style: Main Menu (Hover)
         */
        $this->start_controls_section(
            'eael_simple_menu_section_style_dropdown_item',
            [
                'label' => __('Dropdown Item', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();
    }

}

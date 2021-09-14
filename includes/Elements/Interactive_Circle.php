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

class Interactive_Circle extends Widget_Base
{
    public function get_name()
    {
        return 'eael-interactive-circle';
    }

    public function get_title()
    {
        return esc_html__('Interactive Circle', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-advanced-tabs';
    }

    public function get_categories()
    {
        return ['essential-addons-for-elementor-lite'];
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
        return 'https://essential-addons.com/elementor/docs/interactive-circle/';
    }

    protected function eael_interactive_circle_general() {
	    /**
	     * Advance Tabs Settings
	     */
	    $this->start_controls_section(
		    'eael_section_interactive_circle_settings',
		    [
			    'label' => esc_html__('General Settings', 'essential-addons-for-elementor-lite'),
		    ]
	    );
	    $this->add_control(
		    'eael_interactive_circle_preset',
		    [
			    'label' => esc_html__('Preset', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SELECT,
			    'default' => 'eael-interactive-circle-preset-1',
			    'label_block' => false,
			    'options' => [
				    'eael-interactive-circle-preset-1' => esc_html__('Preset 1', 'essential-addons-for-elementor-lite'),
				    'eael-interactive-circle-preset-2' => esc_html__('Preset 2', 'essential-addons-for-elementor-lite'),
				    'eael-interactive-circle-preset-3' => esc_html__('Preset 3', 'essential-addons-for-elementor-lite'),
				    'eael-interactive-circle-preset-4' => esc_html__('Preset 4', 'essential-addons-for-elementor-lite'),
			    ],
		    ]
	    );

	    $this->add_control(
		    'eael_interactive_circle_btn_settings',
		    [
			    'label' => esc_html__('Button', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::HEADING,
			    'separator' => 'before',
		    ]
	    );

//	    $this->add_control(
//		    'eael_interactive_circle_item_default_active',
//		    [
//			    'label' => esc_html__('Active as Default', 'essential-addons-for-elementor-lite'),
//			    'type' => Controls_Manager::SWITCHER,
//			    'default' => '',
//			    'return_value' => 'active',
//		    ]
//	    );

	    $this->add_control(
		    'eael_interactive_circle_title_icon_show',
		    [
			    'label' => esc_html__('Enable Icon', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::SWITCHER,
			    'default' => 'yes',
			    'return_value' => 'yes',
		    ]
	    );

	    $this->add_control(
		    'eael_interactive_circle_content_settings',
		    [
			    'label' => esc_html__('Content', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::HEADING,
			    'separator' => 'before',
		    ]
	    );
	    
	    $this->end_controls_section();
    }

    protected function eael_interactive_circle_item(){
	    /**
	     * Advance Tabs Content Settings
	     */
	    $this->start_controls_section(
		    'eael_section_interactive_circle_content_settings',
		    [
			    'label' => esc_html__('Content', 'essential-addons-for-elementor-lite'),
		    ]
	    );

	    $repeater = new Repeater();

	    $repeater->start_controls_tabs( 'interactive_circle_tabs' );

	    $repeater->start_controls_tab( 'interactive_circle_btn_tab', [ 'label' => __( 'Button', 'essential-addons-for-elementor-lite' ) ] );

	    $repeater->add_control(
		    'eael_interactive_circle_item_title_icon',
		    [
			    'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::ICONS,
			    'default' => [
				    'value' => 'fas fa-home',
				    'library' => 'fa-solid',
			    ],
		    ]
	    );

	    $repeater->add_control(
		    'eael_interactive_circle_item_title',
		    [
			    'name' => 'eael_interactive_circle_item_title',
			    'label' => esc_html__('Tab Title', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::TEXT,
			    'default' => esc_html__('Tab Title', 'essential-addons-for-elementor-lite'),
			    'dynamic' => ['active' => true],
		    ]
	    );

	    $repeater->add_control(
		    'eael_interactive_circle_short_desc',
		    [
			    'label' => esc_html__('Tab Content', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::TEXTAREA,
			    'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'essential-addons-for-elementor-lite'),
			    'dynamic' => ['active' => true],
		    ]
	    );

	    $repeater->end_controls_tab();

	    $repeater->start_controls_tab( 'interactive_circle_content_tab', [ 'label' => __( 'Content', 'essential-addons-for-elementor-lite' ) ] );

	    $repeater->add_control(
		    'eael_interactive_circle_item_content',
		    [
			    'label' => esc_html__('Tab Content', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::WYSIWYG,
			    'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'essential-addons-for-elementor-lite'),
			    'dynamic' => ['active' => true],
		    ]
	    );

	    $repeater->end_controls_tab();

	    $repeater->start_controls_tab( 'interactive_circle_item_style_tab', [ 'label' => __( 'Style', 'essential-addons-for-elementor-lite' ) ] );

	    //	    $repeater->add_control(
//		    'eael_interactive_circle_tab_content',
//		    [
//			    'label' => esc_html__('Tab Content', 'essential-addons-for-elementor-lite'),
//			    'type' => Controls_Manager::WYSIWYG,
//			    'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'essential-addons-for-elementor-lite'),
//			    'dynamic' => ['active' => true],
//		    ]
//	    );

	    $repeater->add_control(
		    'eael_interactive_circle_item_title_text_color',
		    [
			    'label' => esc_html__('Text Color', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::COLOR,
			    'default' => '#333',
			    'selectors' => [
				    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li' => 'color: {{VALUE}};',
			    ],
		    ]
	    );

	    $repeater->end_controls_tab();
	    $repeater->end_controls_tabs();

	    $this->add_control(
		    'eael_interactive_circle_item',
		    [
			    'type' => Controls_Manager::REPEATER,
			    'seperator' => 'before',
			    'default' => [
				    [
                        'eael_interactive_circle_item_title' => esc_html__('Tab Title 1', 'essential-addons-for-elementor-lite'),
                        'eael_interactive_circle_item_default_active' => __('active', 'essential-addons-for-elementor-lite'),
                    ],
				    [
                        'eael_interactive_circle_item_title' => esc_html__('Tab Title 2', 'essential-addons-for-elementor-lite'),
                    ],
				    [
                        'eael_interactive_circle_item_title' => esc_html__('Tab Title 3', 'essential-addons-for-elementor-lite'),
                    ],
			    ],
			    'fields' => $repeater->get_controls(),
			    'title_field' => '{{eael_interactive_circle_item_title}}',
		    ]
	    );
	    $this->end_controls_section();
    }

    protected function eael_interactive_circle_general_style(){
	    /**
	     * -------------------------------------------
	     * Tab Style Advance Tabs Generel Style
	     * -------------------------------------------
	     */
	    $this->start_controls_section(
		    'eael_section_interactive_circle_style_settings',
		    [
			    'label' => esc_html__('General', 'essential-addons-for-elementor-lite'),
			    'tab' => Controls_Manager::TAB_STYLE,
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_interactive_circle_width',
		    [
			    'label' => __('Circle Width', 'essential-addons-for-elementor-lite'),
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
				    '{{WRAPPER}} .eael-circle-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_interactive_circle_padding',
		    [
			    'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .eael-circle-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_interactive_circle_margin',
		    [
			    'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .eael-circle-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'eael_interactive_circle_border',
			    'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
			    'selector' => '{{WRAPPER}} .eael-circle-inner',
		    ]
	    );

	    $this->add_responsive_control(
		    'eael_interactive_circle_border_radius',
		    [
			    'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .eael-circle-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->end_controls_section();
    }

    protected function eael_interactive_circle_button_style(){
	    $this->start_controls_section(
		    'eael_section_interactive_circle_tab_style_settings',
		    [
			    'label' => esc_html__('Button', 'essential-addons-for-elementor-lite'),
			    'tab' => Controls_Manager::TAB_STYLE,
		    ]
	    );
	    $this->add_group_control(
		    Group_Control_Typography::get_type(),
		    [
			    'name' => 'eael_interactive_circle_btn_typo',
			    'selector' => '{{WRAPPER}} .eael-circle-btn-txt',
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_interactive_circle_btn_width',
		    [
			    'label' => __('Width', 'essential-addons-for-elementor-lite'),
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
				    '{{WRAPPER}} .eael-circle-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			    ],

		    ]
	    );
	    $this->add_responsive_control(
		    'eael_interactive_circle_btn_icon_size',
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
				    '{{WRAPPER}} .eael-circle-btn-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				    '{{WRAPPER}} .eael-circle-btn-icon svg' => 'width: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_interactive_circle_btn_icon_gap',
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
				    '{{WRAPPER}} .eael-tab-inline-icon li i, {{WRAPPER}} .eael-tab-inline-icon li img, {{WRAPPER}} .eael-tab-inline-icon li svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				    '{{WRAPPER}} .eael-tab-top-icon li i, {{WRAPPER}} .eael-tab-top-icon li img, {{WRAPPER}} .eael-tab-top-icon li svg' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_interactive_circle_btn_padding',
		    [
			    'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_interactive_circle_tab_margin',
		    [
			    'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'size_units' => ['px', 'em', '%'],
			    'selectors' => [
				    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->start_controls_tabs('eael_interactive_circle_header_tabs');
	    // Normal State Tab
	    $this->start_controls_tab('eael_interactive_circle_header_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);
	    $this->add_control(
		    'eael_interactive_circle_tab_color',
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
			    'name' => 'eael_interactive_circle_tab_bgtype',
			    'types' => ['classic', 'gradient'],
			    'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li',
		    ]
	    );
	    $this->add_control(
		    'eael_interactive_circle_tab_text_color',
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
		    'eael_interactive_circle_tab_icon_color',
		    [
			    'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::COLOR,
			    'default' => '#333',
			    'selectors' => [
				    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li i' => 'color: {{VALUE}};',
				    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li svg' => 'fill: {{VALUE}};',
			    ],
			    'condition' => [
				    'eael_interactive_circle_icon_show' => 'yes',
			    ],
		    ]
	    );
	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'eael_interactive_circle_tab_border',
			    'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
			    'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li',
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_interactive_circle_tab_border_radius',
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
	    $this->start_controls_tab('eael_interactive_circle_header_hover', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);
	    $this->add_control(
		    'eael_interactive_circle_tab_color_hover',
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
			    'name' => 'eael_interactive_circle_tab_bgtype_hover',
			    'types' => ['classic', 'gradient'],
			    'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover',
		    ]
	    );
	    $this->add_control(
		    'eael_interactive_circle_tab_text_color_hover',
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
		    'eael_interactive_circle_tab_icon_color_hover',
		    [
			    'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::COLOR,
			    'default' => '#fff',
			    'selectors' => [
				    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover > i' => 'color: {{VALUE}};',
				    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover > svg' => 'fill: {{VALUE}};',
			    ],
			    'condition' => [
				    'eael_interactive_circle_icon_show' => 'yes',
			    ],
		    ]
	    );
	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'eael_interactive_circle_tab_border_hover',
			    'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
			    'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li:hover',
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_interactive_circle_tab_border_radius_hover',
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
	    $this->start_controls_tab('eael_interactive_circle_header_active', ['label' => esc_html__('Active', 'essential-addons-for-elementor-lite')]);
	    $this->add_control(
		    'eael_interactive_circle_tab_color_active',
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
			    'name' => 'eael_interactive_circle_tab_bgtype_active',
			    'types' => ['classic', 'gradient'],
			    'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active',
		    ]
	    );
	    $this->add_control(
		    'eael_interactive_circle_tab_text_color_active',
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
		    'eael_interactive_circle_tab_icon_color_active',
		    [
			    'label' => esc_html__('Icon Color', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::COLOR,
			    'default' => '#fff',
			    'selectors' => [
				    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active > i' => 'color: {{VALUE}};',
				    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active-default > i' => 'color: {{VALUE}};',
				    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active > svg' => 'fill: {{VALUE}};',
				    '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active-default > svg' => 'fill: {{VALUE}};',
			    ],
			    'condition' => [
				    'eael_interactive_circle_icon_show' => 'yes',
			    ],
		    ]
	    );
	    $this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
			    'name' => 'eael_interactive_circle_tab_border_active',
			    'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
			    'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-nav > ul li.active',
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_interactive_circle_tab_border_radius_active',
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
    }

    protected function eael_interactive_circle_content_style(){

	    $this->start_controls_section(
		    'eael_section_interactive_circle_tab_content_style_settings',
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
			    'name' => 'eael_interactive_circle_content_typography',
			    'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div',
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_interactive_circle_content_padding',
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
		    'eael_interactive_circle_content_margin',
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
			    'name' => 'eael_interactive_circle_content_border',
			    'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
			    'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div',
		    ]
	    );
	    $this->add_responsive_control(
		    'eael_interactive_circle_content_border_radius',
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
			    'name' => 'eael_interactive_circle_content_shadow',
			    'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tabs-content > div',
			    'separator' => 'before',
		    ]
	    );
	    $this->end_controls_section();
    }

    protected function _register_controls()
    {
	    $this->eael_interactive_circle_general();
	    $this->eael_interactive_circle_item();

	    $this->eael_interactive_circle_general_style();

	    $this->eael_interactive_circle_button_style();
	    $this->eael_interactive_circle_content_style();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $eael_find_default_tab = [];
        $eael_adv_tab_id = 1;
        $eael_adv_tab_content_id = 1;
        $tab_icon_migrated = isset($settings['__fa4_migrated']['eael_interactive_circle_item_title_icon_new']);
        $tab_icon_is_new = empty($settings['eael_interactive_circle_item_title_icon']);

        $this->add_render_attribute(
            'eael_tab_wrapper',
            [
                'id' => "eael-interactive-circle-{$this->get_id()}",
                'class' => ['eael-interactive-circle',],
                'data-tabid' => $this->get_id(),
            ]
        );

?>
        <div <?php echo $this->get_render_attribute_string('eael_tab_wrapper'); ?>>

<!--            --><?php //if(($settings['eael_interactive_circle_preset'] === 'eael-interactive-circle-preset-4') ||
//                     ($settings['eael_interactive_circle_preset'] === 'eael-interactive-circle-preset-3')) { ?>
                <div class="eael-circle-wrapper toggle-on-click <?php echo $settings['eael_interactive_circle_preset']
                ?>">
                    <nav class="ea-circle-info">
                        <div class="eael-circle-inner">
                        <?php foreach ($settings['eael_interactive_circle_item'] as $index => $item) :
                            $item_count = $index + 1;
                            ?>
                            <div class="eael-circle-item">
                                <div class="eael-circle-btn" id="ea-circle-item-<?php echo $item_count; ?>" >
                                    <div class="eael-circle-icon-shapes">
                                        <div class="ea-shape-1"></div>
                                        <div class="ea-shape-2"></div>
                                    </div>
                                    <div class="eael-circle-btn-icon">
                                        <div class="eael-circle-icon-inner">
	                                        <?php Icons_Manager::render_icon($item['eael_interactive_circle_item_title_icon']); ?>
	                                        <span class="eael-circle-btn-txt"><?php echo $item['eael_interactive_circle_item_title'];?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="eael-circle-btn-content ea-circle-item-<?php echo $item_count; ?>">
                                    <div class="eael-circle-content">
                                        <h2>Graphics Design</h2>
	                                    <?php echo $item['eael_interactive_circle_item_content'] ?>
                                    </div>
                                </div>
                            </div>

	                     <?php endforeach; ?>

                        </div>
                    </nav>


<!--            --><?php //} elseif ($settings['eael_interactive_circle_preset'] === 'eael-interactive-circle-preset-3') {?>

<!--            --><?php //} else {?>
<!---->
<!--            --><?php //}?>

        </div>
<?php }
}

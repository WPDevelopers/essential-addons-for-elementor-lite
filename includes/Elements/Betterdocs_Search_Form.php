<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Scheme_Typography as Scheme_Typography;
use \Elementor\Widget_Base as Widget_Base;

class Betterdocs_Search_Form extends Widget_Base
{

    use \Essential_Addons_Elementor\Traits\Helper;

    public function get_name()
    {
        return 'eael-betterdocs-search-form';
    }

    public function get_title()
    {
        return __('BetterDocs Search Form', 'essential-addons-for-elementor-lite');
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_icon()
    {
        return 'eaicon-betterdocs-search-form';
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 3.5.2
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return [
            'knowledgebase',
            'knowledge Base',
            'documentation',
            'doc',
            'kb',
            'betterdocs',
            'ea betterdocs',
            'search',
            'search form',
            'ea',
            'essential addons'
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/betterdocs-search-form/';
    }

    protected function _register_controls()
    {
        /*-----------------------------------------------------------------------------------*/
        /*    Content Tab
        /*-----------------------------------------------------------------------------------*/
        if (!defined('BETTERDOCS_URL')) {
            $this->start_controls_section(
                'eael_global_warning',
                [
                    'label' => __('Warning!', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'eael_global_warning_text',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => __('<strong>BetterDocs</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=BetterDocs&tab=search&type=term" target="_blank">BetterDocs</a> first.', 'essential-addons-for-elementor-lite'),
                    'content_classes' => 'eael-warning',
                ]
            );

            $this->end_controls_section();
        } else {

            /**
             * ----------------------------------------------------------
             * Section: Search Box
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_search_box_settings',
                [
                    'label' => __('Search Box', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'search_box_bg',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .betterdocs-live-search'
                ]
            );

            $this->add_responsive_control(
                'search_box_padding',
                [
                    'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'default'   => [
                        'top'       => 50,
                        'right'     => 50,
                        'bottom'    => 50,
                        'left'      => 50
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-live-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->end_controls_section(); # end of 'Search Box'

            /**
             * ----------------------------------------------------------
             * Section: Search Field
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_search_field_settings',
                [
                    'label' => __('Search Field', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'search_field_bg',
                [
                    'label' => esc_html__('Field Background Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-searchform' => 'background: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'search_field_text_color',
                [
                    'label' => esc_html__('Field Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-searchform .betterdocs-search-field' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'search_field_text_typography',
                    'selector' => '{{WRAPPER}} .betterdocs-searchform .betterdocs-search-field'
                ]
            );

            $this->add_responsive_control(
                'search_field_padding',
                [
                    'label' => __('Field Padding', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-searchform .betterdocs-search-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'search_field_padding_radius',
                [
                    'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-searchform' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ],
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'search_field_border',
                    'label' => __('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .betterdocs-searchform',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'search_field_shadow',
                    'label' => __('Shadow', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .betterdocs-searchform',
                ]
            );


            $this->add_control(
                'field_search_icon_heading',
                [
                    'label' => esc_html__('Search Icon', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'field_search_icon_color',
                [
                    'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-searchform svg.docs-search-icon' => 'fill: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'field_search_icon_size',
                [
                    'label' => esc_html__('Size', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'range' => [
                        'px' => [
                            'max' => 500,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-searchform svg.docs-search-icon' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'field_close_icon_heading',
                [
                    'label' => esc_html__('Close Icon', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'search_field_close_icon_color',
                [
                    'label' => esc_html__('Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .docs-search-close .close-line' => 'fill: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'search_field_close_icon_border_color',
                [
                    'label' => esc_html__('Border Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .docs-search-loader, {{WRAPPER}} .docs-search-close .close-border' => 'stroke: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_section(); # end of 'Search Field'


            /**
             * ----------------------------------------------------------
             * Section: Search Result Box
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_search_result_settings',
                [
                    'label' => __('Search Result Box', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_responsive_control(
                'result_box_width',
                [
                    'label' => __('Width', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 100,
                        'unit' => '%',
                    ],
                    'size_units' => ['%', 'px', 'em'],
                    'range' => [
                        '%' => [
                            'max' => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-live-search .docs-search-result' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'result_box_max_width',
                [
                    'label' => __('Max Width', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 1600,
                        'unit' => 'px',
                    ],
                    'size_units' => ['px', 'em'],
                    'range' => [
                        'px' => [
                            'max' => 1600,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-live-search .docs-search-result' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'result_box_bg',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .betterdocs-live-search .docs-search-result'
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'result_box_border',
                    'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .betterdocs-live-search .docs-search-result',
                ]
            );

            $this->end_controls_section(); # end of 'Search Result Box'

            /**
             * ----------------------------------------------------------
             * Section: Search Result Item
             * ----------------------------------------------------------
             */
            $this->start_controls_section(
                'section_search_result_item_settings',
                [
                    'label' => __('Search Result List', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->start_controls_tabs('item_settings_tab');

            // Normal State Tab
            $this->start_controls_tab(
                'item_normal',
                ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]
            );

            $this->add_control(
                'result_box_item',
                [
                    'label' => esc_html__('Item', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'result_box_item_typography',
                    'selector' => '{{WRAPPER}} .betterdocs-live-search .docs-search-result li a'
                ]
            );

            $this->add_control(
                'result_box_item_color',
                [
                    'label' => esc_html__('Item Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-live-search .docs-search-result li a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'result_item_border',
                    'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .betterdocs-live-search .docs-search-result li'
                ]
            );

            $this->add_responsive_control(
                'result_box_item_padding',
                [
                    'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-live-search .docs-search-result li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'search_result_box_item_count',
                [
                    'label' => esc_html__('Count', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'result_box_item_count_typography',
                    'selector' => '{{WRAPPER}} .betterdocs-live-search .docs-search-result li span'
                ]
            );

            $this->add_control(
                'result_box_item_count_color',
                [
                    'label' => esc_html__('Item Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-live-search .docs-search-result li span' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->end_controls_tab();

            // Hover State Tab
            $this->start_controls_tab(
                'item_hover',
                ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]
            );

            $this->add_responsive_control(
                'result_item_transition',
                [
                    'label' => __('Transition', 'essential-addons-for-elementor-lite'),
                    'type'  => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 300,
                        'unit' => '%',
                    ],
                    'size_units' => ['%'],
                    'range' => [
                        '%' => [
                            'max' => 2500,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-live-search .docs-search-result li, {{WRAPPER}} .betterdocs-live-search .docs-search-result li a, {{WRAPPER}} .betterdocs-live-search .docs-search-result li span, {{WRAPPER}} .betterdocs-live-search .docs-search-result' => 'transition: {{SIZE}}ms;',
                    ],
                ]
            );

            $this->add_control(
                'result_box_item_hover_heading',
                [
                    'label' => esc_html__('Item', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'result_box_item_hover_bg',
                    'types' => ['classic', 'gradient'],
                    'selector' => '{{WRAPPER}} .betterdocs-live-search .docs-search-result li:hover',
                    'exclude'   => [
                        'image'
                    ]
                ]
            );

            $this->add_control(
                'result_box_item_hover_color',
                [
                    'label' => esc_html__('Item Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-live-search .docs-search-result li:hover a' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'result_item_hover_border',
                    'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                    'selector' => '{{WRAPPER}} .betterdocs-live-search .docs-search-result li:hover'
                ]
            );

            $this->add_control(
                'result_box_item_hover_count_heading',
                [
                    'label' => esc_html__('Count', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before'
                ]
            );

            $this->add_control(
                'result_box_item_hover_count_color',
                [
                    'label' => esc_html__('Item Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .betterdocs-live-search .docs-search-result li:hover span' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->end_controls_tab();
            $this->end_controls_tabs();

            $this->end_controls_section(); # end of 'Search Result Item'

        }
    }

    protected function render()
    {
        if (!defined('BETTERDOCS_URL')) return;
        $settings = $this->get_settings_for_display();
        $shortcode  = sprintf('[betterdocs_search_form]', apply_filters('eael_betterdocs_search_form_params', []));
        echo do_shortcode(shortcode_unautop($shortcode));
    }

    public function render_plain_content()
    {
        // In plain mode, render without shortcode
        echo '[betterdocs_search_form]';
    }
}

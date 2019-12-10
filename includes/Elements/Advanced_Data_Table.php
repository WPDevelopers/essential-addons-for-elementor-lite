<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use \Elementor\Plugin;
use \Elementor\Scheme_Typography;
use \Elementor\Widget_Base;

class Advanced_Data_Table extends Widget_Base
{
    public function get_name()
    {
        return 'eael-advanced-data-table';
    }

    public function get_title()
    {
        return esc_html__('EA Advanced Data Table', 'essential-addons-elementor');
    }

    public function get_icon()
    {
        return 'eicon-table';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    protected function _register_controls()
    {
        // general
        $this->start_controls_section(
            'ea_section_adv_data_table_source',
            [
                'label' => esc_html__('Data Source', 'essential-addons-elementor'),
            ]
        );

        $this->add_control(
            'ea_adv_data_table_source',
            [
                'label' => esc_html__('Source', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'static' => esc_html__('Static Data', 'essential-addons-elementor'),
                ],
                'default' => 'static',
            ]
        );

        $this->add_control(
            'ea_adv_data_table_static_html',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => '<thead><tr><th></th><th></th><th></th><th></th></tr></thead><tbody><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr></tbody>',
            ]
        );

        $this->end_controls_section();

        // general
        $this->start_controls_section(
            'ea_section_adv_data_table_features',
            [
                'label' => esc_html__('Advance Features', 'essential-addons-elementor'),
            ]
        );

        $this->add_control(
            'ea_adv_data_table_search',
            [
                'label' => esc_html__('Search', 'essential-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ea_adv_data_table_pagination',
            [
                'label' => esc_html__('Pagination', 'essential-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // $this->add_control(
        //     'ea_adv_data_table_search_pagination',
        //     [
        //         'label' => esc_html__('Search Pagination', 'essential-addons-elementor'),
        //         'type' => Controls_Manager::SWITCHER,
        //         'return_value' => 'yes',
        //         'default' => 'yes',
        //         'condition' => [
        //             'ea_adv_data_table_search' => 'yes',
        //             'ea_adv_data_table_pagination' => 'yes',
        //         ],
        //     ]
        // );

        $this->add_control(
            'ea_adv_data_table_items_per_page',
            [
                'label' => esc_html__('Pagination', 'essential-addons-elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'condition' => [
                    'ea_adv_data_table_pagination' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // style
        $this->start_controls_section(
            'ea_section_adv_data_table_style_table',
            [
                'label' => __('Table', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Border', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_border',
                'label' => __('Border', 'essential-addons-elementor'),
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'unit' => 'px',
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#eeeeee',
                    ],
                ],
                'selector' => '{{WRAPPER}} .ea-advanced-data-table',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ea_section_adv_data_table_style_head',
            [
                'label' => __('Head', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ea_adv_data_table_head_typography',
                'label' => __('Typography', 'essential-addons-elementor'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} th textarea, {{WRAPPER}} th',
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_head_horizontal_alignment',
            [
                'label' => esc_html__('Text Alignment', 'essential-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} th textarea' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} th' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_head_color',
            [
                'label' => __('Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444444',
                'selectors' => [
                    '{{WRAPPER}} th textarea' => 'color: {{VALUE}};',
                    '{{WRAPPER}} th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Background', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_head_background',
            [
                'label' => __('Background', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbfbfb',
                'selectors' => [
                    '{{WRAPPER}} thead' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Border', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_head_cell_border',
                'label' => __('Cell Border', 'essential-addons-elementor'),
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'unit' => 'px',
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#eeeeee',
                    ],
                ],
                'selector' => '{{WRAPPER}} th',
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_head_cell_padding',
            [
                'label' => __('Padding', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'top' => '10',
                    'right' => '10',
                    'bottom' => '10',
                    'left' => '10',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table:not(.ea-advanced-data-table-editable) th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-advanced-data-table.ea-advanced-data-table-editable th textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ea_section_adv_data_table_style_body',
            [
                'label' => __('Body', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ea_adv_data_table_body_typography',
                'label' => __('Typography', 'essential-addons-elementor'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} td textarea, {{WRAPPER}} td',
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_horizontal_alignment',
            [
                'label' => esc_html__('Text Alignment', 'essential-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} td textarea' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} td' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_color',
            [
                'label' => __('Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} td textarea' => 'color: {{VALUE}};',
                    '{{WRAPPER}} td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Background', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_background',
            [
                'label' => __('Background', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} tbody' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Border', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_body_cell_border',
                'label' => __('Cell Border', 'essential-addons-elementor'),
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'unit' => 'px',
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#eeeeee',
                    ],
                ],
                'selector' => '{{WRAPPER}} td',
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Highlight Row/Colum', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_highlight',
            [
                'label' => esc_html__('Highlight', 'essential-addons-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'essential-addons-elementor'),
                    'f-col' => esc_html__('First Column', 'essential-addons-elementor'),
                    'l-col' => esc_html__('Last Column', 'essential-addons-elementor'),
                    'e-col' => esc_html__('Even Column', 'essential-addons-elementor'),
                    'o-col' => esc_html__('Odd Column', 'essential-addons-elementor'),
                    'e-row' => esc_html__('Even Row', 'essential-addons-elementor'),
                    'o-row' => esc_html__('Odd Row', 'essential-addons-elementor'),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_highlight_color',
            [
                'label' => __('Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0)',
                'selectors' => [
                    '{{WRAPPER}} tbody td:first-child' => 'color: {{VALUE}}',
                    '{{WRAPPER}} tbody td:first-child textarea' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'f-col',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_highlight_background',
            [
                'label' => __('Background', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0)',
                'selectors' => [
                    '{{WRAPPER}} tbody td:first-child' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody td:first-child textarea' => 'background-color: {{VALUE}} !important',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'f-col',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_cell_padding',
            [
                'label' => __('Padding', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'top' => '10',
                    'right' => '10',
                    'bottom' => '10',
                    'left' => '10',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table:not(.ea-advanced-data-table-editable) td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-advanced-data-table.ea-advanced-data-table-editable td textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ea_section_adv_data_table_style_search',
            [
                'label' => __('Search', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ea_adv_data_table_search' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_search_width',
            [
                'label' => __('Width', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-search' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_search_height',
            [
                'label' => __('Height', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-search' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_search_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_search_margin',
            [
                'label' => __('Margin Bottom', 'essential-addons-elementor'),
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
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-search' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_search_alignment',
            [
                'label' => esc_html__('Alignment', 'essential-addons-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-elementor'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'right',
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ea_adv_data_table_search_typography',
                'label' => __('Typography', 'essential-addons-elementor'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ea-advanced-data-table-search',
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_search_color',
            [
                'label' => __('Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-search' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Background', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_search_background',
            [
                'label' => __('Background', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-search' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Border', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_search_border',
                'label' => __('Border', 'essential-addons-elementor'),
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'unit' => 'px',
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#eeeeee',
                    ],
                ],
                'selector' => '{{WRAPPER}} .ea-advanced-data-table-search',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ea_section_adv_data_table_style_pagination',
            [
                'label' => __('Pagination', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ea_adv_data_table_pagination' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_pagination_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'top' => '5',
                    'right' => '15',
                    'bottom' => '5',
                    'left' => '15',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_pagination_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'default' => [
                    'unit' => 'px',
                    'top' => '5',
                    'right' => '5',
                    'bottom' => '0',
                    'left' => '0',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ea_adv_data_table_pagination_typography',
                'label' => __('Typography', 'essential-addons-elementor'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ea-advanced-data-table-pagination a',
            ]
        );

        $this->start_controls_tabs('ea_adv_data_table_pagination_tabs');

        $this->start_controls_tab('normal', ['label' => esc_html__('Normal', 'essential-addons-elementor')]);

        $this->add_control(
            'ea_adv_data_table_pagination_color',
            [
                'label' => __('Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Background', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_pagination_background',
            [
                'label' => __('Background', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Border', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_pagination_border',
                'label' => __('Border', 'essential-addons-elementor'),
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'unit' => 'px',
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#eeeeee',
                    ],
                ],
                'selector' => '{{WRAPPER}} .ea-advanced-data-table-pagination a',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('hover', ['label' => esc_html__('Hover', 'essential-addons-elementor')]);

        $this->add_control(
            'ea_adv_data_table_pagination_color_hover',
            [
                'label' => __('Color', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a:hover, {{WRAPPER}} .ea-advanced-data-table-pagination a.ea-advanced-data-table-pagination-current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Background', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_pagination_background_hover',
            [
                'label' => __('Background', 'essential-addons-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a:hover, {{WRAPPER}} .ea-advanced-data-table-pagination a.ea-advanced-data-table-pagination-current' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Border', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_pagination_border_hover',
                'label' => __('Border', 'essential-addons-elementor'),
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'unit' => 'px',
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#eeeeee',
                    ],
                ],
                'selector' => '{{WRAPPER}} .ea-advanced-data-table-pagination a:hover, {{WRAPPER}} .ea-advanced-data-table-pagination a.ea-advanced-data-table-pagination-current',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if ($settings['ea_adv_data_table_source'] == 'static') {
            $this->add_render_attribute('ea-adv-data-table-wrap', [
                'class' => "ea-advanced-data-table-wrap",
                'data-id' => $this->get_id(),
            ]);

            $this->add_render_attribute('ea-adv-data-table', [
                'class' => "ea-advanced-data-table ea-advanced-data-table-{$settings['ea_adv_data_table_source']} ea-advanced-data-table-{$this->get_id()}",
                'data-id' => $this->get_id(),
                'data-items-per-page' => $settings['ea_adv_data_table_items_per_page'],
            ]);
        }

        if ($settings['ea_adv_data_table_pagination'] == 'yes') {
            $this->add_render_attribute('ea-adv-data-table', [
                'class' => "ea-advanced-data-table-paginated",
            ]);
        }

        if ($settings['ea_adv_data_table_search'] == 'yes') {
            $this->add_render_attribute('ea-adv-data-table', [
                'class' => "ea-advanced-data-table-searchable",
            ]);

            $this->add_render_attribute('ea-adv-data-table-search-wrap', [
                'class' => "ea-advanced-data-table-search-wrap ea-advanced-data-table-search-{$settings['ea_adv_data_table_search_alignment']}",
            ]);
        }

        // if($settings['ea_adv_data_table_search_pagination']) {
        //     $this->add_render_attribute('ea-adv-data-table', [
        //         'class' => "ea-advanced-data-table-searchable-paginated",
        //     ]);
        // }

        echo '<div ' . $this->get_render_attribute_string('ea-adv-data-table-wrap') . '>';

        if ($settings['ea_adv_data_table_search'] == 'yes') {
            echo '<div ' . $this->get_render_attribute_string('ea-adv-data-table-search-wrap') . '><input type="search" class="ea-advanced-data-table-search"></div>';
        }

        echo '<table ' . $this->get_render_attribute_string('ea-adv-data-table') . '>' . $this->html_static_table($settings) . '</table>';

        if ($settings['ea_adv_data_table_pagination'] == 'yes') {
            if (Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="ea-advanced-data-table-pagination clearfix">
                    <a href="#">&laquo;</a>
                    <a href="#">1</a>
                    <a href="#">2</a>
                    <a href="#">&raquo;</a>
                </div>';
            } else {
                echo '<div class="ea-advanced-data-table-pagination clearfix"></div>';
            }
        }

        echo '</div>';
    }

    protected function html_static_table($settings)
    {
        return $settings['ea_adv_data_table_static_html'];
    }

}

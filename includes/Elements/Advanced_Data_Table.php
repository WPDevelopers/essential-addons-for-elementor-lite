<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
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
        return esc_html__('EA Advanced Data Table', 'essential-addons-for-elementor-lite');
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
                'label' => esc_html__('Data Source', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'ea_adv_data_table_source',
            [
                'label' => esc_html__('Source', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => apply_filters('eael/advanced-data-table/source', [
                    'static' => __('Static Data', 'essential-addons-for-elementor-lite'),
                ]),
                'default' => 'static',
            ]
        );

        do_action('eael/advanced-data-table/source/control', $this);

        $this->add_control(
            'ea_adv_data_table_static_html',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => '<thead><tr><th></th><th></th><th></th><th></th></tr></thead><tbody><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr></tbody>',
            ]
        );

        $this->end_controls_section();

        // features
        $this->start_controls_section(
            'ea_section_adv_data_table_features',
            [
                'label' => esc_html__('Advanced Features', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'ea_adv_data_table_sort',
            [
                'label' => esc_html__('Sort', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ea_adv_data_table_search',
            [
                'label' => esc_html__('Search', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ea_adv_data_table_search_placeholder',
            [
                'label' => __('Placeholder', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Search', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'ea_adv_data_table_search' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_pagination',
            [
                'label' => esc_html__('Pagination', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'ea_adv_data_table_items_per_page',
            [
                'label' => esc_html__('Rows Per Page', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 10,
                'condition' => [
                    'ea_adv_data_table_pagination' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_global_warning_text',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('Note: Pagination will be applied on Live Preview only.', 'essential-addons-for-elementor-lite'),
                'content_classes' => 'eael-warning',
                'condition' => [
                    'ea_adv_data_table_pagination' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // export/import
        $this->start_controls_section(
            'ea_section_adv_data_table_export_import',
            [
                'label' => esc_html__('Export/Import', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'ea_adv_data_table_export_csv_button',
            [
                'label' => __('Export table as CSV file', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::BUTTON,
                'text' => __('Export', 'essential-addons-for-elementor-lite'),
                'event' => 'ea:advTable:export',
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'label' => __('Import', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'ea_adv_data_table_source' => 'static',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_csv_string',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<textarea class="ea_adv_table_csv_string" rows="5" placeholder="Paste CSV string"></textarea><label for="ea_adv_table_csv_string_table"><input type="checkbox" id="ea_adv_table_csv_string_table" class="ea_adv_table_csv_string_table"> Import first row as Header</label>',
                'condition' => [
                    'ea_adv_data_table_source' => 'static',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_import_csv_button',
            [
                'label' => __('Import', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::BUTTON,
                'show_label' => false,
                'text' => __('Import', 'essential-addons-for-elementor-lite'),
                'event' => 'ea:advTable:import',
                'condition' => [
                    'ea_adv_data_table_source' => 'static',
                ],
            ]
        );

        $this->end_controls_section();

        // style
        $this->start_controls_section(
            'ea_section_adv_data_table_style_table',
            [
                'label' => __('Table', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'ea_adv_data_table_width',
            [
                'label' => __('Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 10000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'desktop_default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'tablet_default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'mobile_default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_border',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
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

        $this->add_control(
            'ea_adv_data_table_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-wrap .ea-advanced-data-table-wrap-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ea_adv_data_table_width_box_shadow',
                'label' => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .ea-advanced-data-table-wrap .ea-advanced-data-table-wrap-inner',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ea_section_adv_data_table_style_head',
            [
                'label' => __('Head', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ea_adv_data_table_head_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
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
                'label' => esc_html__('Text Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
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
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444444',
                'selectors' => [
                    '{{WRAPPER}} th textarea' => 'color: {{VALUE}};',
                    '{{WRAPPER}} th' => 'color: {{VALUE}};',
                    '{{WRAPPER}} th:before' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} th:after' => 'border-top-color: {{VALUE}};',
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
            'ea_adv_data_table_head_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
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

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_head_cell_border',
                'label' => __('Cell Border', 'essential-addons-for-elementor-lite'),
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

        $this->add_responsive_control(
            'ea_adv_data_table_head_cell_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'desktop_default' => [
                    'unit' => 'px',
                    'top' => '10',
                    'right' => '10',
                    'bottom' => '10',
                    'left' => '10',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table:not(.ea-advanced-data-table-editable) th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-advanced-data-table:not(.ea-advanced-data-table-static) th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-advanced-data-table:not(.ea-advanced-data-table-static) td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-advanced-data-table.ea-advanced-data-table-editable th textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ea_section_adv_data_table_style_body',
            [
                'label' => __('Body', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ea_adv_data_table_body_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
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
                'label' => esc_html__('Text Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
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
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
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
            'ea_adv_data_table_body_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
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

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_body_cell_border',
                'label' => __('Cell Border', 'essential-addons-for-elementor-lite'),
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
            'ea_adv_data_table_body_highlight',
            [
                'label' => esc_html__('Highlight', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'essential-addons-for-elementor-lite'),
                    'f-col' => esc_html__('First Column', 'essential-addons-for-elementor-lite'),
                    'l-col' => esc_html__('Last Column', 'essential-addons-for-elementor-lite'),
                    'e-col' => esc_html__('Even Column', 'essential-addons-for-elementor-lite'),
                    'o-col' => esc_html__('Odd Column', 'essential-addons-for-elementor-lite'),
                    'e-row' => esc_html__('Even Row', 'essential-addons-for-elementor-lite'),
                    'o-row' => esc_html__('Odd Row', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'none',
            ]
        );

        // first col
        $this->add_control(
            'ea_adv_data_table_body_f_col_highlight_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444444',
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
            'ea_adv_data_table_body_f_col_highlight_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbfbfb',
                'selectors' => [
                    '{{WRAPPER}} tbody td:first-child' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody td:first-child textarea' => 'background-color: {{VALUE}} !important',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'f-col',
                ],
            ]
        );

        // last col
        $this->add_control(
            'ea_adv_data_table_body_l_col_highlight_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444444',
                'selectors' => [
                    '{{WRAPPER}} tbody td:last-child' => 'color: {{VALUE}}',
                    '{{WRAPPER}} tbody td:last-child textarea' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'l-col',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_l_col_highlight_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbfbfb',
                'selectors' => [
                    '{{WRAPPER}} tbody td:last-child' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody td:last-child textarea' => 'background-color: {{VALUE}} !important',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'l-col',
                ],
            ]
        );

        // even col
        $this->add_control(
            'ea_adv_data_table_body_e_col_highlight_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444444',
                'selectors' => [
                    '{{WRAPPER}} tbody td:nth-child(even)' => 'color: {{VALUE}}',
                    '{{WRAPPER}} tbody td:nth-child(even) textarea' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'e-col',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_e_col_highlight_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbfbfb',
                'selectors' => [
                    '{{WRAPPER}} tbody td:nth-child(even)' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody td:nth-child(even) textarea' => 'background-color: {{VALUE}} !important',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'e-col',
                ],
            ]
        );

        // odd col
        $this->add_control(
            'ea_adv_data_table_body_o_col_highlight_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444444',
                'selectors' => [
                    '{{WRAPPER}} tbody td:nth-child(odd)' => 'color: {{VALUE}}',
                    '{{WRAPPER}} tbody td:nth-child(odd) textarea' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'o-col',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_o_col_highlight_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbfbfb',
                'selectors' => [
                    '{{WRAPPER}} tbody td:nth-child(odd)' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody td:nth-child(odd) textarea' => 'background-color: {{VALUE}} !important',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'o-col',
                ],
            ]
        );

        // even row
        $this->add_control(
            'ea_adv_data_table_body_e_row_highlight_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444444',
                'selectors' => [
                    '{{WRAPPER}} tbody tr:nth-child(even)' => 'color: {{VALUE}}',
                    '{{WRAPPER}} tbody tr:nth-child(even) textarea' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'e-row',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_e_row_highlight_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbfbfb',
                'selectors' => [
                    '{{WRAPPER}} tbody tr:nth-child(even)' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody tr:nth-child(even) textarea' => 'background-color: {{VALUE}} !important',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'e-row',
                ],
            ]
        );

        // odd row
        $this->add_control(
            'ea_adv_data_table_body_o_row_highlight_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444444',
                'selectors' => [
                    '{{WRAPPER}} tbody tr:nth-child(odd)' => 'color: {{VALUE}}',
                    '{{WRAPPER}} tbody tr:nth-child(odd) textarea' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'o-row',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_o_row_highlight_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbfbfb',
                'selectors' => [
                    '{{WRAPPER}} tbody tr:nth-child(odd)' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} tbody tr:nth-child(odd) textarea' => 'background-color: {{VALUE}} !important',
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'o-row',
                ],
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'ea_adv_data_table_body_cell_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'desktop_default' => [
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
                'label' => __('Search', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ea_adv_data_table_search' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_search_width',
            [
                'label' => __('Width', 'essential-addons-for-elementor-lite'),
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
                'label' => __('Height', 'essential-addons-for-elementor-lite'),
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

        $this->add_responsive_control(
            'ea_adv_data_table_search_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
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
                'label' => __('Margin Bottom', 'essential-addons-for-elementor-lite'),
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
                'label' => esc_html__('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
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
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
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
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
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
            'ea_adv_data_table_search_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
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

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_search_border',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
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

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_search_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ea_section_adv_data_table_style_pagination',
            [
                'label' => __('Pagination', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ea_adv_data_table_pagination' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_pagination_alignment',
            [
                'label' => esc_html__('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination' => 'text-align: {{VALUE}};',
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
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ea-advanced-data-table-pagination a',
            ]
        );

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'ea_adv_data_table_pagination_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'desktop_default' => [
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

        $this->add_responsive_control(
            'ea_adv_data_table_pagination_margin',
            [
                'label' => esc_html__('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'desktop_default' => [
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

        $this->start_controls_tabs('ea_adv_data_table_pagination_tabs');

        $this->start_controls_tab('normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'ea_adv_data_table_pagination_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
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
            'ea_adv_data_table_pagination_background',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
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

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_pagination_border',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
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

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_pagination_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('hover', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'ea_adv_data_table_pagination_color_hover',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
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
            'ea_adv_data_table_pagination_background_hover',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fafafa',
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

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_pagination_border_hover',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
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

        $this->add_control(
            base64_encode(random_bytes(10)),
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'ea_adv_data_table_pagination_border_radius_hover',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a:hover, {{WRAPPER}} .ea-advanced-data-table-pagination a.ea-advanced-data-table-pagination-current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('ea-adv-data-table-wrap', [
            'class' => "ea-advanced-data-table-wrap",
            'data-id' => $this->get_id(),
        ]);

        $this->add_render_attribute('ea-adv-data-table', [
            'class' => "ea-advanced-data-table ea-advanced-data-table-{$settings['ea_adv_data_table_source']} ea-advanced-data-table-{$this->get_id()}",
            'data-id' => $this->get_id()
        ]);

        if ($settings['ea_adv_data_table_sort'] == 'yes') {
            $this->add_render_attribute('ea-adv-data-table', [
                'class' => "ea-advanced-data-table-sortable",
            ]);
        }

        if ($settings['ea_adv_data_table_pagination'] == 'yes') {
            $this->add_render_attribute('ea-adv-data-table', [
                'class' => "ea-advanced-data-table-paginated",
                'data-items-per-page' => $settings['ea_adv_data_table_items_per_page'],
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

        echo '<div ' . $this->get_render_attribute_string('ea-adv-data-table-wrap') . '>';

        if ($settings['ea_adv_data_table_search'] == 'yes') {
            echo '<div ' . $this->get_render_attribute_string('ea-adv-data-table-search-wrap') . '><input type="search" placeholder="' . $settings['ea_adv_data_table_search_placeholder'] . '" class="ea-advanced-data-table-search"></div>';
        }

        echo '<div class="ea-advanced-data-table-wrap-inner">
            <table ' . $this->get_render_attribute_string('ea-adv-data-table') . '>' . $this->html_static_table() . '</table>
        </div>';

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

    protected function html_static_table()
    {
        $settings = $this->get_parsed_dynamic_settings();

        if ($settings['ea_adv_data_table_source'] == 'database' || $settings['ea_adv_data_table_source'] == 'remote' || $settings['ea_adv_data_table_source'] == 'google') {
            return apply_filters('eael/advanced-data-table/table_html/database', $settings, '');
        }

        return $settings['ea_adv_data_table_static_html'];
    }

}

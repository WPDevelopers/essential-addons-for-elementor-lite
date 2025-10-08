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
use \Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper;

class Advanced_Data_Table extends Widget_Base
{
    public function get_name()
    {
        return 'eael-advanced-data-table';
    }

    public function get_title()
    {
        return esc_html__('Advanced Data Table', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-advanced-data-table';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'table',
            'ea table',
            'ea advanced table',
            'ea advanced data table',
            'CSV',
            'google sheet',
            'spreadsheet',
            'excel',
            'tablepress',
            'ninja tables',
            'data dable',
            'comparison table',
            'grid',
            'import data',
            'import table',
            'ea',
            'essential addons',
        ];
    }

    public function has_widget_inner_wrapper(): bool {
        return ! Helper::eael_e_optimized_markup();
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/advanced-data-table/';
    }

    protected function register_controls()
    {
        // general
        $this->start_controls_section(
            'ea_section_adv_data_table_source',
            [
                'label' => esc_html__('Data Source', 'essential-addons-for-elementor-lite'),
            ]
        );

        $sources = [
            'static'     => __('Static Data', 'essential-addons-for-elementor-lite'),
            'csv'        => __('CSV Data', 'essential-addons-for-elementor-lite'),
            'ninja'      => __('Ninja Tables', 'essential-addons-for-elementor-lite'),
            'database'   => __('Database', 'essential-addons-for-elementor-lite'),
            'remote'     => __('Remote Database', 'essential-addons-for-elementor-lite'),
            'google'     => __('Google Sheets', 'essential-addons-for-elementor-lite'),
            'tablepress' => __('TablePress', 'essential-addons-for-elementor-lite'),
        ];

        if( ! current_user_can('install_plugins') ) {
            unset( $sources['database'] );
        }

        if ( ! apply_filters('eael/pro_enabled', false) ) {
            $sources['database']   = __('Database (Pro)', 'essential-addons-for-elementor-lite');
            $sources['remote']     = __('Remote Database (Pro)', 'essential-addons-for-elementor-lite');
            $sources['google']     = __('Google Sheets (Pro)', 'essential-addons-for-elementor-lite');
            $sources['tablepress'] = __('TablePress (Pro)', 'essential-addons-for-elementor-lite');
        }

        $this->add_control(
            'ea_adv_data_table_source',
            [
                'label' => esc_html__('Source', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => $sources,
                'default' => 'static',
            ]
        );

        $this->add_control(
            'heading-import',
            [
                'label' => __('Import', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'ea_adv_data_table_source' => 'csv',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_csv_string',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<textarea class="ea_adv_table_csv_string" rows="5" placeholder="Paste CSV string"></textarea><label for="ea_adv_table_csv_string_table"><input type="checkbox" id="ea_adv_table_csv_string_table" class="ea_adv_table_csv_string_table"> Import first row as Header</label>',
                'condition' => [
                    'ea_adv_data_table_source' => 'csv',
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
                    'ea_adv_data_table_source' => 'csv',
                ],
            ]
        );

	    if (!apply_filters('eael/pro_enabled', false)) {
		    $this->add_control(
			    'eael_adv_data_table_pro_enable_warning',
			    [
				    'label' => sprintf( '<a target="_blank" href="https://wpdeveloper.com/upgrade/ea-pro">%s</a>', esc_html__('Only Available in Pro Version!', 'essential-addons-for-elementor-lite')),
				    'type' => Controls_Manager::RAW_HTML,
				    'condition' => [
					    'ea_adv_data_table_source' => ['database','remote','google','tablepress'],
				    ],
			    ]
		    );
	    }

        // TODO: RM
        do_action('eael/advanced-data-table/source/control', $this);

        do_action('eael/controls/advanced-data-table/source', $this);

        $this->add_control(
            'ea_adv_data_table_static_html',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => '<thead><tr><th></th><th></th><th></th><th></th></tr></thead><tbody><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr><tr><td></td><td></td><td></td><td></td></tr></tbody>',
            ]
        );

        $this->add_control(
            'ea_adv_data_table_csv_html',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => '<table><thead><tr><th>Name</th><th>Age</th><th>Country</th><th>Occupation</th></tr></thead><tbody><tr><td>John Doe</td><td>28</td><td>USA</td><td>Software Engineer</td></tr><tr><td>Jane Smith</td><td>32</td><td>UK</td><td>Graphic Designer</td></tr><tr><td>John Albert</td><td>24</td><td>Canada</td><td>Data Scientist</td></tr><tr><td>Maria Garcia</td><td>29</td><td>Spain</td><td>Marketing Specialist</td></tr></tbody></table>',
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
                'label'     => __('Placeholder', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default'   => __('Search', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'ea_adv_data_table_search' => 'yes',
                ],
                'ai' => [
					'active' => false,
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
            'ea_adv_data_table_pagination_type',
            [
                'label' => esc_html__('Pagination Type', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'button' => esc_html__('Button', 'essential-addons-for-elementor-lite'),
                    'select' => esc_html__('Select', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'button',
                'condition' => [
                    'ea_adv_data_table_pagination' => 'yes',
                ],
            ]
        );

	    $this->add_control(
		    'ea_adv_data_table_items_per_page',
		    [
			    'label'       => esc_html__( 'Rows Per Page', 'essential-addons-for-elementor-lite' ),
			    'type'        => Controls_Manager::NUMBER,
			    'min'         => 1,
			    'default'     => 10,
			    'description' => esc_html__( 'If you left blank or 0 it will show 10 items by default.', 'essential-addons-for-elementor-lite' ),
			    'condition'   => [
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
                'label' => esc_html__('Export', 'essential-addons-for-elementor-lite'),
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

        $this->end_controls_section();

	    /**
	     * Data cache setting
	     */
	    $this->start_controls_section(
		    'ea_adv_data_table_data_cache',
		    [
			    'label' => __('Data Cache Setting', 'essential-addons-for-elementor-lite'),
			    'condition' => [
				    'ea_adv_data_table_source' => 'google',
			    ],
		    ]
	    );

	    $this->add_control(
		    'ea_adv_data_table_data_cache_limit',
		    [
			    'label' => __('Data Cache Time', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::NUMBER,
			    'min' => 1,
			    'default' => 60,
			    'description' => __('Cache expiration time (Minutes)', 'essential-addons-for-elementor-lite')
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
                'selector' => '{{WRAPPER}} th',
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
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} th' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} th .ql-editor' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_head_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444444',
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-wrap .ea-advanced-data-table th' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ea-advanced-data-table-wrap .ea-advanced-data-table th:before' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .ea-advanced-data-table-wrap .ea-advanced-data-table th:after' => 'border-top-color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .ea-advanced-data-table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} td',
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
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} td' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} td .ql-editor' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_link_color',
            [
                'label' => __('Link Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} td a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_body_link_hovercolor',
            [
                'label' => __('Link Hover Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} td a:hover' => 'color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} tbody tr:nth-child(even) td' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} tbody tr:nth-child(odd) td' => 'color: {{VALUE}}',
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
                ],
                'condition' => [
                    'ea_adv_data_table_body_highlight' => 'o-row',
                ],
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
                    '{{WRAPPER}} .ea-advanced-data-table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'ea_adv_data_table_search_alignment',
            [
                'label' => esc_html__('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'right',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ea_adv_data_table_search_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .ea-advanced-data-table-search',
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

        $this->add_responsive_control(
            'ea_adv_data_table_pagination_select_width',
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
                    'unit' => 'px',
                    'size' => 100,
                ],
                'tablet_default' => [
                    'unit' => 'px',
                    'size' => 100,
                ],
                'mobile_default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination select' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'ea_adv_data_table_pagination_type' => 'select',
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
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ea_adv_data_table_pagination_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .ea-advanced-data-table-pagination a, {{WRAPPER}} .ea-advanced-data-table-pagination select',
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
                    '{{WRAPPER}} .ea-advanced-data-table-pagination select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .ea-advanced-data-table-pagination select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-advanced-data-table-pagination select' => 'color: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .ea-advanced-data-table-pagination select' => 'background-color: {{VALUE}};',
                ],
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
                'selector' => '{{WRAPPER}} .ea-advanced-data-table-pagination a, {{WRAPPER}} .ea-advanced-data-table-pagination select',
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
                    '{{WRAPPER}} .ea-advanced-data-table-pagination select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a.ea-adtp-current' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ea-advanced-data-table-pagination select:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_pagination_background_hover',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fafafa',
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a.ea-adtp-current' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .ea-advanced-data-table-pagination select:hover' => 'background-color: {{VALUE}};',
                ],
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
                'selector' => '{{WRAPPER}} .ea-advanced-data-table-pagination a:hover, {{WRAPPER}} .ea-advanced-data-table-pagination a.ea-adtp-current, {{WRAPPER}} .ea-advanced-data-table-pagination select:hover',
            ]
        );

        $this->add_control(
            'ea_adv_data_table_pagination_border_radius_hover',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-advanced-data-table-pagination a.ea-adtp-current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ea-advanced-data-table-pagination select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'ea_section_adv_data_table_style_button',
            [
                'label' => __('Button', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'ea_adv_data_table_source' => 'ninja'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ea_adv_data_table_button_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} td button, {{WRAPPER}} td .button',
            ]
        );

        $this->start_controls_tabs('ea_adv_data_table_button_tabs');

        $this->start_controls_tab('ea_adv_data_table_button_tab_normal', ['label' => esc_html__('Normal', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'ea_adv_data_table_button_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} td button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} td .button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_button_background_color',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} td button' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} td .button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('ea_adv_data_table_button_tab_hover', ['label' => esc_html__('Hover', 'essential-addons-for-elementor-lite')]);

        $this->add_control(
            'ea_adv_data_table_button_color_hover',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} td button:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} td .button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_button_background_color_hover',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} td button:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} td .button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ea_adv_data_table_button_border',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'fields_options' => [
                    'border' => [
                        'default' => '',
                    ],
                    'width' => [
                        'default' => [
                            'unit' => 'px',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '',
                    ],
                ],
                'selector' => '{{WRAPPER}} td button, {{WRAPPER}} td .button',
            ]
        );

        $this->add_control(
            'ea_adv_data_table_button_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} td button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} td .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'ea_adv_data_table_button_box_shadow',
                'label' => __('Box Shadow', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} td button, {{WRAPPER}} td .button',
            ]
        );

        $this->add_control(
            'ea_adv_data_table_button_border_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} td button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} td .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if (in_array($settings['ea_adv_data_table_source'], ['database', 'remote', 'google'])) {
            if (!apply_filters('eael/pro_enabled', false)) {
                return;
            }
        } else if ($settings['ea_adv_data_table_source'] == "tablepress") {
            if (!apply_filters('eael/pro_enabled', false)) {
                return;
            }

            if (!apply_filters('eael/is_plugin_active', 'tablepress/tablepress.php')) {
                return;
            }
        } else if ($settings['ea_adv_data_table_source'] == "ninja") {
            if (!apply_filters('eael/is_plugin_active', 'ninja-tables/ninja-tables.php')) {
                return;
            }
        }

        $this->add_render_attribute('ea-adv-data-table-wrap', [
            'class' => "ea-advanced-data-table-wrap",
            'data-id' => $this->get_id(),
        ]);

        $this->add_render_attribute('ea-adv-data-table', [
            'class' => "ea-advanced-data-table ea-advanced-data-table-{$settings['ea_adv_data_table_source']} ea-advanced-data-table-{$this->get_id()}",
            'data-id' => $this->get_id(),
        ]);

        if ($settings['ea_adv_data_table_sort'] == 'yes') {
            $this->add_render_attribute('ea-adv-data-table', [
                'class' => "ea-advanced-data-table-sortable",
            ]);
        }

        if ($settings['ea_adv_data_table_pagination'] == 'yes') {
            $this->add_render_attribute('ea-adv-data-table', [
                'class' => "ea-advanced-data-table-paginated",
                'data-items-per-page' => ! empty( $settings['ea_adv_data_table_items_per_page'] ) ? $settings['ea_adv_data_table_items_per_page'] : 10,
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


        $content = $this->get_table_content();
        if ( Plugin::$instance->editor->is_edit_mode() ) {
            $this->add_render_attribute('ea-adv-data-table', [
                'class' => "ea-advanced-data-table-editable",
            ]);

            if ( $content && 'csv' === $settings['ea_adv_data_table_source'] ) {
                $dom = new \DOMDocument( '1.0', 'UTF-8' );
                $html = "<table>{$content}</table>";

                $dom->loadHTML( '<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

                $rows = $dom->getElementsByTagName( 'tr' );
                $content = '';
                $pagination = ! empty( $settings['ea_adv_data_table_items_per_page'] ) ? $settings['ea_adv_data_table_items_per_page'] : 10;

                $thead_elements = $dom->getElementsByTagName( 'thead' );
                foreach ( $thead_elements as $thead ) {
                    $content .= $dom->saveHTML($thead);
                }

                $tbody_rows = $dom->getElementsByTagName( 'tbody' );
                if ( $tbody_rows->length > 0 ) {
                    foreach ( $tbody_rows as $tbody ) {
                        $rows = $tbody->getElementsByTagName( 'tr' );
                        $tbody_content = '';
                        foreach ( $rows as $index => $row ) {
                            if ( $index >= $pagination ) {
                                break;
                            }
                            $tbody_content .= $dom->saveHTML($row);
                        }

                        if ( $tbody_content ) {
                            $content .= '<tbody>' . $tbody_content . '</tbody>';
                        }
                    }
                } else {
                    $all_rows = $dom->getElementsByTagName( 'tr' );
                    $data_rows = [];
                    foreach ( $all_rows as $row ) {
                        if ( $row->parentNode->nodeName !== 'thead' ) {
                            $data_rows[] = $row;
                        }
                    }

                    $tbody_content = '';
                    foreach ( $data_rows as $index => $row ) {
                        if ( $index >= $pagination ) {
                            break;
                        }
                        $tbody_content .= $dom->saveHTML($row);
                    }

                    if ( $tbody_content ) {
                        $content .= '<tbody>' . $tbody_content . '</tbody>';
                    }
                }
            }
        }

        echo '<div '; $this->print_render_attribute_string('ea-adv-data-table-wrap'); echo '>';

        if ( $content ) {
            if ($settings['ea_adv_data_table_search'] == 'yes') {
                echo '<div '; $this->print_render_attribute_string('ea-adv-data-table-search-wrap'); echo '><input type="search" placeholder="' . esc_attr( $settings['ea_adv_data_table_search_placeholder'] ). '" class="ea-advanced-data-table-search"></div>';
            }

            echo '<div class="ea-advanced-data-table-wrap-inner">
                <table '; $this->print_render_attribute_string('ea-adv-data-table'); echo '>' . wp_kses( $content, Helper::eael_allowed_tags(), Helper::eael_allowed_protocols() ) . '</table>
            </div>';

            if ($settings['ea_adv_data_table_pagination'] == 'yes') {
                if (Plugin::$instance->editor->is_edit_mode()) {
                    if ($settings['ea_adv_data_table_pagination_type'] == 'button') {
                        echo '<div class="ea-advanced-data-table-pagination clearfix">
                            <a href="#">&laquo;</a>
                            <a href="#">1</a>
                            <a href="#">2</a>
                            <a href="#">&raquo;</a>
                        </div>';
                    } else {
                        echo '<div class="ea-advanced-data-table-pagination clearfix">
                            <select>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>';
                    }
                } else {
                    echo '<div class="ea-advanced-data-table-pagination ea-advanced-data-table-pagination-' . esc_attr( $settings['ea_adv_data_table_pagination_type'] ) . ' clearfix"></div>';
                }
            }
        } else {
	        $no_content = apply_filters( 'eael/advanced-data-table/no-content-found-text', __( 'No content found', 'essential-addons-for-elementor-lite' ) );
	        echo esc_html( $no_content );
        }

        echo '</div>';
    }

    public function get_table_content()
    {
        $settings = $this->get_settings_for_display();

        if ( 'static' === $settings['ea_adv_data_table_source'] ) {
            return $settings['ea_adv_data_table_static_html'];
        } else if ( 'csv' === $settings['ea_adv_data_table_source'] ) {
            return $settings['ea_adv_data_table_csv_html'];
        } else if ($settings['ea_adv_data_table_source'] == 'ninja') {
            return $this->ninja_integration();
        }

        if ( $settings[ 'ea_adv_data_table_source' ] == 'remote' ) {
            $settings_legacy                                        = $this->get_settings();
            $settings[ 'ea_adv_data_table_source_remote_host' ]     = $settings_legacy[ 'ea_adv_data_table_source_remote_host' ];
            $settings[ 'ea_adv_data_table_source_remote_username' ] = $settings_legacy[ 'ea_adv_data_table_source_remote_username' ];
            $settings[ 'ea_adv_data_table_source_remote_password' ] = $settings_legacy[ 'ea_adv_data_table_source_remote_password' ];
            $settings[ 'ea_adv_data_table_source_remote_database' ] = $settings_legacy[ 'ea_adv_data_table_source_remote_database' ];
        }

        $content = apply_filters('eael/advanced-data-table/table_html/integration/' . $settings['ea_adv_data_table_source'], $settings);
        if( ! current_user_can('install_plugins') && Plugin::$instance->editor->is_edit_mode() ) {
            $content = '';
        }

        if (is_array($content)) {
            return '';
        }

        return $content;
    }

    public function ninja_integration()
    {
        $settings = $this->get_settings_for_display();

        if (empty($settings['ea_adv_data_table_source_ninja_table_id'])) {
            return;
        }

        $html = '';
        $table_settings = ninja_table_get_table_settings($settings['ea_adv_data_table_source_ninja_table_id']);
        $table_headers = ninja_table_get_table_columns($settings['ea_adv_data_table_source_ninja_table_id']);
        $table_rows = ninjaTablesGetTablesDataByID($settings['ea_adv_data_table_source_ninja_table_id']);

        if (!empty($table_rows)) {
            if (!isset($table_settings['hide_header_row']) || $table_settings['hide_header_row'] != true) {
                $html .= '<thead><tr>';
                foreach ($table_headers as $key => $th) {
                    $style = isset($settings['ea_adv_data_table_dynamic_th_width']) && isset($settings['ea_adv_data_table_dynamic_th_width'][$key]) ? ' style="width:' . $settings['ea_adv_data_table_dynamic_th_width'][$key] . '"' : '';
                    $html .= '<th' . $style . '>' . $th['name'] . '</th>';
                }
                $html .= '</tr></thead>';
            }

            $html .= '<tbody>';
            foreach ($table_rows as $key => $tr) {
                $html .= '<tr>';
                foreach ($table_headers as $th) {
                    if (!isset($th['data_type'])) {
                        $th['data_type'] = '';
                    }

                    if ($th['data_type'] == 'image') {
                        $html .= '<td>' . (isset($tr[$th['key']]['image_thumb']) ? '<a href="' . esc_url( $tr[$th['key']]['image_full'] ) . '"><img src="' . esc_url( $tr[$th['key']]['image_thumb'] ) . '"></a>' : '') . '</td>';
                    } elseif ($th['data_type'] == 'selection') {
                        $html .= '<td>' . (!empty($tr[$th['key']]) ? implode((array) $tr[$th['key']], ', ') : '') . '</td>';
                    } elseif ($th['data_type'] == 'button') {
                        $html .= '<td>' . (!empty($tr[$th['key']]) ? '<a href="' . esc_url( $tr[$th['key']] ) . '" class="button" target="' . esc_attr( $th['link_target'] ) . '">' . $th['button_text'] . '</a>' : '') . '</td>';
                    } else {
	                    $html .= '<td>' . ( isset( $tr[ $th['key'] ] ) ? $tr[ $th['key'] ] : '' ) . '</td>';
                    }
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        }

        return $html;
    }

}

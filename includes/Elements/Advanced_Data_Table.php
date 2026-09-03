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
    /**
     * Whether the rendered content came from a Ninja Tables Drag & Drop builder table,
     * whose markup carries builder styling that needs a wider sanitiser allowlist.
     *
     * @var bool
     */
    protected $is_ninja_builder = false;

    /**
     * The inline style the Drag & Drop builder put on its own <table> element — the
     * table border, fixed layout and font it was designed with.
     *
     * @var string
     */
    protected $ninja_builder_table_style = '';

    public function get_name()
    {
        return 'eael-advanced-data-table';
    }

    /**
     * A Ninja Tables Drag & Drop table needs Ninja's own stylesheet for the parts of its
     * design that are class-based rather than inline: star ratings, ribbons, progress
     * bars, buttons and the icon masks.
     *
     * Enqueuing it while rendering only reaches the front end. In the editor Elementor
     * collects style dependencies from widget prototypes, before any settings exist
     * (see Elementor's `Preview::enqueue_styles()`), so the editor has to be given the
     * dependency for the widget as a whole or the table renders there unstyled.
     *
     * @return array
     */
    public function get_style_depends()
    {
        if ( ! defined( 'NINJA_TABLES_DIR_URL' ) || ! defined( 'NINJA_TABLES_VERSION' ) ) {
            return [];
        }

        $collected_without_settings = Plugin::$instance->editor->is_edit_mode() || Plugin::$instance->preview->is_preview_mode();

        if ( ! $collected_without_settings ) {
            $settings = $this->get_settings_for_display();

            if ( empty( $settings['ea_adv_data_table_source'] ) || 'ninja' !== $settings['ea_adv_data_table_source'] ) {
                return [];
            }
        }

        // Registered under Ninja's own handle, so this is a no-op once Ninja has done it.
        wp_register_style( 'ninja_table_builder_style', NINJA_TABLES_DIR_URL . 'assets/css/ninja-table-builder-public.css', [], NINJA_TABLES_VERSION );

        return [ 'ninja_table_builder_style' ];
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
            'ea_adv_data_table_default_sort_column',
            [
                'label' => esc_html__('Default Sort Column', 'essential-addons-for-elementor-lite'),
                'description' => esc_html__('Enter the column number to sort by on initial load. Leave empty for no default sorting.', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => '',
                'condition' => [
                    'ea_adv_data_table_sort' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'ea_adv_data_table_default_sort_order',
            [
                'label' => esc_html__('Default Sort Order', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => esc_html__('Ascending', 'essential-addons-for-elementor-lite'),
                    'desc' => esc_html__('Descending', 'essential-addons-for-elementor-lite'),
                ],
                'default' => 'asc',
                'condition' => [
                    'ea_adv_data_table_sort' => 'yes',
                    'ea_adv_data_table_default_sort_column!' => '',
                ],
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
					'active' => true,
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
                // The builder's own table style is filtered against this control while
                // rendering, so the markup has to be rebuilt when it changes.
                'render_type' => 'template',
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
                    // The builder's own table style is filtered against this control
                    // while rendering, so the markup has to be rebuilt when it changes.
                    '__all' => [ 'render_type' => 'template' ],
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
                                'fields_options' => [
                    // See the note on the colour controls: the markup depends on this too.
                    '__all' => [ 'render_type' => 'template' ],
                ],
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
                                // A Ninja Tables Drag & Drop table drops the builder's competing inline
                // declaration when this control is set, and that happens while rendering.
                // Elementor only regenerates CSS for a style control, so ask for the markup
                // to be rebuilt too or the change would not show until the page is reloaded.
                'render_type' => 'template',
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
                                // A Ninja Tables Drag & Drop table drops the builder's competing inline
                // declaration when this control is set, and that happens while rendering.
                // Elementor only regenerates CSS for a style control, so ask for the markup
                // to be rebuilt too or the change would not show until the page is reloaded.
                'render_type' => 'template',
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
                                // A Ninja Tables Drag & Drop table drops the builder's competing inline
                // declaration when this control is set, and that happens while rendering.
                // Elementor only regenerates CSS for a style control, so ask for the markup
                // to be rebuilt too or the change would not show until the page is reloaded.
                'render_type' => 'template',
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
                    // See the note on the colour controls: the markup depends on this too.
                    '__all' => [ 'render_type' => 'template' ],

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
                                // A Ninja Tables Drag & Drop table drops the builder's competing inline
                // declaration when this control is set, and that happens while rendering.
                // Elementor only regenerates CSS for a style control, so ask for the markup
                // to be rebuilt too or the change would not show until the page is reloaded.
                'render_type' => 'template',
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
                                'fields_options' => [
                    // See the note on the colour controls: the markup depends on this too.
                    '__all' => [ 'render_type' => 'template' ],
                ],
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
                                // A Ninja Tables Drag & Drop table drops the builder's competing inline
                // declaration when this control is set, and that happens while rendering.
                // Elementor only regenerates CSS for a style control, so ask for the markup
                // to be rebuilt too or the change would not show until the page is reloaded.
                'render_type' => 'template',
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
                                // A Ninja Tables Drag & Drop table drops the builder's competing inline
                // declaration when this control is set, and that happens while rendering.
                // Elementor only regenerates CSS for a style control, so ask for the markup
                // to be rebuilt too or the change would not show until the page is reloaded.
                'render_type' => 'template',
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
                                // A Ninja Tables Drag & Drop table drops the builder's competing inline
                // declaration when this control is set, and that happens while rendering.
                // Elementor only regenerates CSS for a style control, so ask for the markup
                // to be rebuilt too or the change would not show until the page is reloaded.
                'render_type' => 'template',
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
                    // See the note on the colour controls: the markup depends on this too.
                    '__all' => [ 'render_type' => 'template' ],

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
                                // A Ninja Tables Drag & Drop table drops the builder's competing inline
                // declaration when this control is set, and that happens while rendering.
                // Elementor only regenerates CSS for a style control, so ask for the markup
                // to be rebuilt too or the change would not show until the page is reloaded.
                'render_type' => 'template',
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
            $sort_attrs = [
                'class' => "ea-advanced-data-table-sortable",
            ];

            if (!empty($settings['ea_adv_data_table_default_sort_column'])) {
                $sort_attrs['data-default-sort-column'] = intval($settings['ea_adv_data_table_default_sort_column']);
                $sort_attrs['data-default-sort-order'] = !empty($settings['ea_adv_data_table_default_sort_order']) ? sanitize_text_field($settings['ea_adv_data_table_default_sort_order']) : 'asc';
            }

            $this->add_render_attribute('ea-adv-data-table', $sort_attrs);
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

        if ( $this->ninja_builder_table_style ) {
            $this->add_render_attribute( 'ea-adv-data-table', 'style', $this->ninja_builder_table_style );
        }
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

            // Builder tables carry Ninja's own styling, so they need its stylesheet and
            // the wrapper class its rules are scoped to, plus a sanitiser that keeps the
            // inline CSS that styling is made of.
            if ( $this->is_ninja_builder ) {
                $inner_class = 'ea-advanced-data-table-wrap-inner ntb_table_wrapper';
                $safe_content = Helper::eael_ninja_builder_kses( $content );
                wp_enqueue_style( 'ninja_table_builder_style', NINJA_TABLES_DIR_URL . 'assets/css/ninja-table-builder-public.css', [], NINJA_TABLES_VERSION );
            } else {
                $inner_class = 'ea-advanced-data-table-wrap-inner';
                $safe_content = wp_kses( $content, Helper::eael_allowed_tags(), Helper::eael_allowed_protocols() );
            }

            echo '<div class="' . esc_attr( $inner_class ) . '">
                <table '; $this->print_render_attribute_string('ea-adv-data-table'); echo '>' . $safe_content . '</table>
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
	        $no_content = apply_filters( 'eael/advanced-data-table/no-content-found-text', $this->get_no_content_message( $settings ) );
	        echo esc_html( $no_content );
        }

        echo '</div>';
    }

    /**
     * Message shown when the table renders no rows.
     *
     * A Ninja Table whose data provider has no row handler — a Drag & Drop table most
     * commonly — always yields zero rows, so say why instead of leaving the generic
     * "No content found" to look like an empty table.
     *
     * @param array $settings
     *
     * @return string
     */
    public function get_no_content_message( $settings )
    {
        $table_id = ! empty( $settings['ea_adv_data_table_source_ninja_table_id'] ) ? $settings['ea_adv_data_table_source_ninja_table_id'] : '';

        if ( 'ninja' === $settings['ea_adv_data_table_source'] && $table_id && ! Helper::is_ninja_table_supported( $table_id ) ) {
            return sprintf(
                /* translators: %s: Ninja Tables data provider name, e.g. "CSV". */
                __( 'Advanced Data Table cannot read this Ninja Table, because its %s data source needs an add-on that is not active on this site. Please select a different Ninja Table.', 'essential-addons-for-elementor-lite' ),
                Helper::get_ninja_table_provider_label( Helper::get_ninja_table_provider( $table_id ) )
            );
        }

        return __( 'No content found', 'essential-addons-for-elementor-lite' );
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

        if ('drag_and_drop' === Helper::get_ninja_table_provider($settings['ea_adv_data_table_source_ninja_table_id'])) {
            return $this->ninja_builder_integration($settings['ea_adv_data_table_source_ninja_table_id'], $settings);
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
            $row_count = 0;
            $is_edit_mode = Plugin::$instance->editor->is_edit_mode();
            foreach ($table_rows as $key => $tr) {
                if( $is_edit_mode && 'yes' === $settings['ea_adv_data_table_pagination'] ){
                    $row_count++;
                    $pagination_count = $settings['ea_adv_data_table_items_per_page'] > 0 ? $settings['ea_adv_data_table_items_per_page'] : 10;
                    if( $row_count > $pagination_count ){
                        break;
                    }
                }
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

    /**
     * Build the table markup for a Ninja Tables Drag & Drop table.
     *
     * These tables register no `ninja_tables_fetching_table_rows_*` handler — the
     * builder persists its grid as post meta rather than as queryable rows — so read
     * that grid directly instead of going through ninjaTablesGetTablesDataByID().
     *
     * `headers` carries the authoritative left-to-right column order. That is NOT the
     * key order of each row's cells: reordering a column in the builder rewrites the
     * rows edited afterwards but leaves earlier ones in their original key order, so
     * iterating a row's own keys would silently shuffle columns between rows.
     *
     * @param int   $table_id
     * @param array $settings
     *
     * @return string
     */
    protected function ninja_builder_integration( $table_id, $settings )
    {
        // Rebuilding the cells from the saved grid drops the builder's inline styling,
        // which is what lets this widget's own Style controls reach the table. Keeping
        // the builder's markup is the opposite trade: an exact copy of the designed
        // table, whose inline styles necessarily outrank those controls.
        if ( ! isset( $settings['ea_adv_data_table_ninja_keep_builder_design'] ) || 'yes' === $settings['ea_adv_data_table_ninja_keep_builder_design'] ) {
            $this->is_ninja_builder = true;

            $html = $this->ninja_builder_saved_markup( $table_id, $settings );

            if ( '' !== $html ) {
                return $html;
            }

            // Nothing saved to copy, so fall through and build from the grid instead.
            $this->is_ninja_builder          = false;
            $this->ninja_builder_table_style = '';
        }

        return $this->ninja_builder_grid( $table_id, $settings );
    }

    /**
     * Reshape the builder's saved markup into this widget's table structure.
     *
     * Ninja renders the saved table as one <tbody> of <tr>s. Promote its first row to
     * <thead> so sorting and search have column headers, and keep every cell's inner
     * markup and inline styling untouched so the result looks like the built table.
     *
     * @param int   $table_id
     * @param array $settings
     *
     * @return string Empty when the builder has not saved markup for this table yet.
     */
    protected function ninja_builder_saved_markup( $table_id, $settings )
    {
        $markup = Helper::get_ninja_builder_table_html( $table_id );

        if ( '' === $markup ) {
            return '';
        }

        // Ninja runs the saved markup through the shortcode parser before printing it.
        $markup = do_shortcode( $markup );

        $dom = new \DOMDocument( '1.0', 'UTF-8' );
        $libxml_previous = libxml_use_internal_errors( true );
        $dom->loadHTML( '<?xml encoding="UTF-8">' . $markup, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
        libxml_clear_errors();
        libxml_use_internal_errors( $libxml_previous );

        $xpath = new \DOMXPath( $dom );
        $rows  = $xpath->query( '//tr' );

        if ( ! $rows->length ) {
            return '';
        }

        // The <table> itself is this widget's, so carry over the border, layout mode and
        // font the table was built with; without them only the cells look designed.
        $table = $xpath->query( '//table' )->item( 0 );

        if ( $table instanceof \DOMElement ) {
            // The Table section's own controls draw on the same element, so they get the
            // same treatment as the Head and Body ones.
            $this->ninja_builder_table_style = $this->ninja_builder_filter_declarations(
                $table->getAttribute( 'style' ),
                $this->ninja_builder_table_overridden_properties( $settings )
            );
        }

        // A designed table often merges a cell downwards out of its first row — a label
        // column spanning every row, say. A rowspan cannot cross the thead/tbody
        // boundary, so promoting that row would drop the cell and shift the whole grid.
        // Keep such a table in one body, exactly as Ninja prints it.
        $use_thead = ! $xpath->query( '(//tr)[1]/*[@rowspan][number(@rowspan) > 1]' )->length;

        // The builder writes its design as inline styles, which outrank the CSS this
        // widget's Style controls generate. Wherever a control has actually been set,
        // drop the declaration it competes with so the control wins; everything the user
        // left alone keeps the design it was built with.
        $head_overrides = $this->ninja_builder_overridden_properties( $settings, 'head' );
        $body_overrides = $this->ninja_builder_overridden_properties( $settings, 'body' );

        $html         = '';
        $row_count    = 0;
        $is_edit_mode = Plugin::$instance->editor->is_edit_mode();
        $body         = '';

        foreach ( $rows as $index => $row ) {
            $cells = $xpath->query( './td|./th', $row );

            if ( ! $cells->length ) {
                continue;
            }

            $is_header = ( 0 === $index && $use_thead );
            $cell_tag  = $is_header ? 'th' : 'td';
            $overrides = $is_header ? $head_overrides : $body_overrides;

            if ( $overrides ) {
                $this->ninja_builder_strip_styles( $row, $overrides );
            }

            $row_markup = '<tr' . $this->ninja_builder_attributes( $row, [ 'class', 'style' ] ) . '>';

            foreach ( $cells as $key => $cell ) {
                $attributes = $this->ninja_builder_attributes( $cell, [ 'class', 'style', 'colspan', 'rowspan' ] );

                if ( $is_header && isset( $settings['ea_adv_data_table_dynamic_th_width'][ $key ] ) ) {
                    $attributes .= ' style="width:' . $settings['ea_adv_data_table_dynamic_th_width'][ $key ] . '"';
                }

                $row_markup .= '<' . $cell_tag . $attributes . '>' . $this->ninja_builder_inner_html( $cell ) . '</' . $cell_tag . '>';
            }

            $row_markup .= '</tr>';

            if ( $is_header ) {
                $html .= '<thead>' . $row_markup . '</thead>';
                continue;
            }

            if ( $is_edit_mode && 'yes' === $settings['ea_adv_data_table_pagination'] ) {
                $row_count++;
                $pagination_count = $settings['ea_adv_data_table_items_per_page'] > 0 ? $settings['ea_adv_data_table_items_per_page'] : 10;
                if ( $row_count > $pagination_count ) {
                    break;
                }
            }

            $body .= $row_markup;
        }

        if ( '' === $body ) {
            return $html;
        }

        return $html . '<tbody>' . $body . '</tbody>';
    }

    /**
     * CSS properties a Style control has taken ownership of for one table region.
     *
     * Only controls the user actually set are listed: an untouched control generates no
     * CSS, so stripping for it would strip the builder's design and put nothing back.
     *
     * @param array  $settings
     * @param string $region   `head` or `body`.
     *
     * @return array Property names, matched as prefixes (`border` covers `border-top`).
     */
    protected function ninja_builder_overridden_properties( $settings, $region )
    {
        $controls = [
            [ 'setting' => "ea_adv_data_table_{$region}_background", 'properties' => [ 'background' ] ],
            [ 'setting' => "ea_adv_data_table_{$region}_color", 'properties' => [ 'color' ] ],
            [ 'setting' => "ea_adv_data_table_{$region}_horizontal_alignment", 'properties' => [ 'text-align' ] ],
            [ 'setting' => "ea_adv_data_table_{$region}_cell_padding", 'properties' => [ 'padding' ] ],
            [ 'group' => "ea_adv_data_table_{$region}_cell_border", 'properties' => [ 'border' ] ],
            [ 'group' => "ea_adv_data_table_{$region}_typography", 'properties' => [ 'font', 'line-height', 'letter-spacing', 'text-transform', 'text-decoration', 'word-spacing' ] ],
        ];

        $properties = [];

        foreach ( $controls as $control ) {
            $is_set = isset( $control['group'] )
                ? $this->ninja_builder_group_is_customised( $settings, $control['group'] )
                : $this->ninja_builder_setting_is_customised( $settings, $control['setting'] );

            if ( $is_set ) {
                $properties = array_merge( $properties, $control['properties'] );
            }
        }

        return $properties;
    }

    /**
     * CSS properties a Table-section control has taken ownership of.
     *
     * These controls draw on the table element itself and are named without the region
     * prefix the Head and Body ones use, so they are listed separately.
     *
     * @param array $settings
     *
     * @return array
     */
    protected function ninja_builder_table_overridden_properties( $settings )
    {
        $properties = [];

        // Named exactly rather than by prefix: `ea_adv_data_table_border_radius` is a
        // separate control that shares the border group's prefix but styles the wrapper.
        foreach ( [ 'ea_adv_data_table_border_border', 'ea_adv_data_table_border_width', 'ea_adv_data_table_border_color' ] as $key ) {
            if ( $this->ninja_builder_setting_is_customised( $settings, $key ) ) {
                $properties[] = 'border';
                break;
            }
        }

        if ( $this->ninja_builder_setting_is_customised( $settings, 'ea_adv_data_table_width' ) ) {
            $properties[] = 'width';
        }

        return $properties;
    }

    /**
     * Whether a style control has been moved off its default.
     *
     * Emptiness is not the test: these controls ship with real defaults (a head colour
     * of #444444, a 1px #eeeeee cell border), and the editor hands over the full control
     * stack while the front end drops style controls it has not been given values for.
     * Testing for a value would therefore strip the builder's design in the editor and
     * keep it on the front end — the same table rendered two different ways.
     *
     * @param array  $settings
     * @param string $key
     *
     * @return bool
     */
    protected function ninja_builder_setting_is_customised( $settings, $key )
    {
        if ( ! isset( $settings[ $key ] ) || $this->ninja_builder_value_is_empty( $settings[ $key ] ) ) {
            return false;
        }

        $control = $this->get_controls( $key );
        $default = isset( $control['default'] ) ? $control['default'] : '';

        // Loose comparison: dimension and slider controls arrive as arrays.
        return $settings[ $key ] != $default;
    }

    /**
     * Whether a control value amounts to "nothing set".
     *
     * @param mixed $value
     *
     * @return bool
     */
    protected function ninja_builder_value_is_empty( $value )
    {
        if ( ! is_array( $value ) ) {
            return '' === $value || null === $value;
        }

        // Slider controls carry a size, dimension controls carry per-side values.
        if ( array_key_exists( 'size', $value ) ) {
            return '' === $value['size'] || null === $value['size'];
        }

        foreach ( [ 'top', 'right', 'bottom', 'left' ] as $side ) {
            if ( isset( $value[ $side ] ) && '' !== $value[ $side ] ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether any control of a group (typography, border) has been moved off its default.
     *
     * @param array  $settings
     * @param string $prefix
     *
     * @return bool
     */
    protected function ninja_builder_group_is_customised( $settings, $prefix )
    {
        foreach ( array_keys( $settings ) as $key ) {
            if ( 0 !== strpos( $key, $prefix ) ) {
                continue;
            }

            // Skip a group's popover toggle: it opens the panel section rather than
            // producing a declaration, so on its own it overrides nothing.
            $control = $this->get_controls( $key );

            if ( isset( $control['render_type'] ) && 'ui' === $control['render_type'] ) {
                continue;
            }

            if ( $this->ninja_builder_setting_is_customised( $settings, $key ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Drop the listed CSS properties from an element and everything inside it.
     *
     * The builder puts colour and font on wrappers deep inside a cell, not on the cell
     * itself, so a shallow pass would leave the control still visibly ignored.
     *
     * @param \DOMElement $element
     * @param array       $properties
     *
     * @return void
     */
    protected function ninja_builder_strip_styles( $element, $properties )
    {
        $nodes = [ $element ];

        foreach ( $element->getElementsByTagName( '*' ) as $descendant ) {
            $nodes[] = $descendant;
        }

        foreach ( $nodes as $node ) {
            if ( ! $node instanceof \DOMElement || ! $node->hasAttribute( 'style' ) ) {
                continue;
            }

            $style = $this->ninja_builder_filter_declarations( $node->getAttribute( 'style' ), $properties );

            if ( '' !== $style ) {
                $node->setAttribute( 'style', $style );
            } else {
                $node->removeAttribute( 'style' );
            }
        }
    }

    /**
     * Remove the listed properties from one inline style string.
     *
     * Matching is by prefix, so `border` covers `border-top-color`. Three descendants of
     * `border` are spared: they set table layout rather than the visible edge a border
     * control draws, and dropping them would reflow the table.
     *
     * @param string $style
     * @param array  $properties
     *
     * @return string
     */
    protected function ninja_builder_filter_declarations( $style, $properties )
    {
        $layout_properties = [ 'border-collapse', 'border-spacing', 'border-radius' ];
        $kept              = [];

        foreach ( explode( ';', $style ) as $declaration ) {
            if ( '' === trim( $declaration ) ) {
                continue;
            }

            $parts    = explode( ':', $declaration, 2 );
            $property = strtolower( trim( $parts[0] ) );

            if ( ! in_array( $property, $layout_properties, true ) ) {
                foreach ( $properties as $overridden ) {
                    if ( $property === $overridden || 0 === strpos( $property, $overridden . '-' ) ) {
                        continue 2;
                    }
                }
            }

            $kept[] = trim( $declaration );
        }

        return implode( '; ', $kept );
    }

    /**
     * Copy through the listed attributes of a saved builder element.
     *
     * @param \DOMElement $element
     * @param array       $names
     *
     * @return string
     */
    protected function ninja_builder_attributes( $element, $names )
    {
        $attributes = '';

        foreach ( $names as $name ) {
            if ( ! $element->hasAttribute( $name ) ) {
                continue;
            }

            $value = $element->getAttribute( $name );

            if ( '' === $value ) {
                continue;
            }

            $attributes .= ' ' . $name . '="' . esc_attr( $value ) . '"';
        }

        return $attributes;
    }

    /**
     * The inner markup of a saved builder cell.
     *
     * @param \DOMElement $element
     *
     * @return string
     */
    protected function ninja_builder_inner_html( $element )
    {
        $inner = '';

        foreach ( $element->childNodes as $child ) {
            $inner .= $element->ownerDocument->saveHTML( $child );
        }

        return $inner;
    }

    /**
     * Build the table from the builder's saved grid.
     *
     * The fallback for a table whose markup the builder has not written yet — one
     * created from a ready-made template and never opened, whose `table_html` is null.
     *
     * @param int   $table_id
     * @param array $settings
     *
     * @return string
     */
    protected function ninja_builder_grid( $table_id, $settings )
    {
        $table_data = Helper::get_ninja_builder_table_data( $table_id );

        if ( empty( $table_data ) ) {
            return '';
        }

        $rows    = array_values( $table_data['data'] );
        $columns = ! empty( $table_data['headers'] ) && is_array( $table_data['headers'] )
            ? $table_data['headers']
            : array_keys( (array) $rows[0]['rows'] );

        $html = '';

        // The builder paints its first row with the header background, so promote that
        // row to <thead> — the widget's sorting and search key off the header cells.
        $header_row = array_shift( $rows );

        if ( ! empty( $header_row['rows'] ) ) {
            $html .= '<thead><tr>';

            foreach ( array_values( $columns ) as $key => $column ) {
                if ( ! isset( $header_row['rows'][ $column ] ) ) {
                    continue;
                }

                $style = isset( $settings['ea_adv_data_table_dynamic_th_width'][ $key ] ) ? ' style="width:' . $settings['ea_adv_data_table_dynamic_th_width'][ $key ] . '"' : '';
                $html .= '<th' . $style . $this->ninja_builder_cell_span( $header_row['rows'][ $column ] ) . '>' . $this->ninja_builder_cell_content( $header_row['rows'][ $column ] ) . '</th>';
            }

            $html .= '</tr></thead>';
        }

        $html .= '<tbody>';

        $row_count    = 0;
        $is_edit_mode = Plugin::$instance->editor->is_edit_mode();

        foreach ( $rows as $row ) {
            if ( $is_edit_mode && 'yes' === $settings['ea_adv_data_table_pagination'] ) {
                $row_count++;
                $pagination_count = $settings['ea_adv_data_table_items_per_page'] > 0 ? $settings['ea_adv_data_table_items_per_page'] : 10;
                if ( $row_count > $pagination_count ) {
                    break;
                }
            }

            $html .= '<tr>';

            foreach ( $columns as $column ) {
                // A missing cell was swallowed by a neighbouring row/col span.
                if ( ! isset( $row['rows'][ $column ] ) ) {
                    continue;
                }

                $html .= '<td' . $this->ninja_builder_cell_span( $row['rows'][ $column ] ) . '>' . $this->ninja_builder_cell_content( $row['rows'][ $column ] ) . '</td>';
            }

            $html .= '</tr>';
        }

        $html .= '</tbody>';

        return $html;
    }

    /**
     * Render the `colspan`/`rowspan` attributes of a merged builder cell.
     *
     * @param array $cell
     *
     * @return string
     */
    protected function ninja_builder_cell_span( $cell )
    {
        $attributes = '';

        foreach ( [ 'colspan', 'rowspan' ] as $span ) {
            $value = isset( $cell['style'][ $span ] ) ? absint( $cell['style'][ $span ] ) : 1;

            if ( $value > 1 ) {
                $attributes .= ' ' . $span . '="' . esc_attr( $value ) . '"';
            }
        }

        return $attributes;
    }

    /**
     * Render every content component stacked inside one builder cell.
     *
     * @param array $cell
     *
     * @return string
     */
    protected function ninja_builder_cell_content( $cell )
    {
        if ( empty( $cell['columns'] ) || ! is_array( $cell['columns'] ) ) {
            return '';
        }

        $content = '';

        foreach ( $cell['columns'] as $item ) {
            if ( empty( $item['data'] ) ) {
                continue;
            }

            $content .= $this->ninja_builder_component( $item['data'] );
        }

        return $content;
    }

    /**
     * Render a single Drag & Drop builder component as plain table-cell markup.
     *
     * The builder's own output is a nest of positioned wrappers carrying inline colours
     * tuned to its canvas, which would fight this widget's style controls. Emit the
     * content itself and let the widget's own styling apply.
     *
     * @param array $data
     *
     * @return string
     */
    protected function ninja_builder_component( $data )
    {
        $type  = isset( $data['type'] ) ? $data['type'] : 'text';
        $value = isset( $data['value'] ) ? $data['value'] : '';
        $style = isset( $data['style'] ) ? (array) $data['style'] : [];

        switch ( $type ) {
            case 'button':
                if ( empty( $style['url'] ) ) {
                    return wp_kses_post( $value );
                }

                return '<a href="' . esc_url( $style['url'] ) . '" class="button"' . ( ! empty( $style['newTab'] ) ? ' target="_blank" rel="noopener"' : '' ) . '>' . wp_kses_post( $value ) . '</a>';

            case 'image':
                if ( empty( $value ) ) {
                    return '';
                }

                $image = '<img src="' . esc_url( $value ) . '" alt="' . esc_attr( isset( $style['alt'] ) ? $style['alt'] : '' ) . '">';

                if ( ! empty( $style['link'] ) ) {
                    $image = '<a href="' . esc_url( $style['link'] ) . '"' . ( ! empty( $style['target'] ) ? ' target="_blank" rel="noopener"' : '' ) . '>' . $image . '</a>';
                }

                return $image;

            case 'list':
            case 'stylist_list':
                $items = array_filter( (array) $value, 'strlen' );

                if ( empty( $items ) ) {
                    return '';
                }

                $tag  = ( isset( $style['listType'] ) && 'ol' === $style['listType'] ) ? 'ol' : 'ul';
                $list = '';

                foreach ( $items as $item ) {
                    $list .= '<li>' . wp_kses_post( $item ) . '</li>';
                }

                return '<' . $tag . '>' . $list . '</' . $tag . '>';

            case 'star_rating':
                $max = isset( $style['maxStar'] ) ? absint( $style['maxStar'] ) : 5;

                return esc_html( sprintf( '%s/%s', (float) $value, $max ) );

            case 'progress':
                $percentage = isset( $style['percentage'] ) ? $style['percentage'] : $value;

                return esc_html( $percentage . '%' );

            case 'shortcode':
                return do_shortcode( $value );

            case 'custom_html':
                return wp_kses_post( $value );

            case 'icon':
                $icon = Helper::get_ninja_builder_icon_url( $value );

                return $icon ? '<img src="' . esc_url( $icon ) . '" alt="" width="' . esc_attr( isset( $style['fontSize'] ) ? absint( $style['fontSize'] ) : 20 ) . '">' : '';

            default:
                // text, ribbon, text_icon, and any component added by a later release.
                return wp_kses_post( is_array( $value ) ? implode( ', ', $value ) : (string) $value );
        }
    }

}

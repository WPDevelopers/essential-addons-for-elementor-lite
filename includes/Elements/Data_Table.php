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
use \Elementor\Utils;
use \Elementor\Widget_Base;
use \Elementor\Repeater;
use Elementor\Icons_Manager;

use \Essential_Addons_Elementor\Classes\Helper;

class Data_Table extends Widget_Base {
	

    public $unique_id = null;
    public function get_name()
    {
        return 'eael-data-table';
    }

    public function get_title()
    {
        return esc_html__('Data Table', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-data-table';
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
            'data table',
            'ea data table',
            'export eable',
            'CSV',
            'comparison table',
            'grid',
            'ea',
            'essential addons',
        ];
    }

	protected function is_dynamic_content():bool {
        return false;
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/data-table/';
    }

    protected function register_controls()
    {

        /**
         * Data Table Header
         */
        $this->start_controls_section(
            'eael_section_data_table_header',
            [
                'label' => esc_html__('Header', 'essential-addons-for-elementor-lite')
            ]
        );

        do_action('eael_section_data_table_enabled', $this);

        if (!apply_filters('eael/pro_enabled', false)) {

	        $this->add_control(
		        'eael_section_data_table_enabled', [
		        'label'        => sprintf( __( 'Enable Table Sorting %s', 'essential-addons-for-elementor-lite' ), __( '<i class="eael-pro-labe eicon-pro-icon"></i>', 'essential-addons-for-elementor-lite' ) ),
		        'type'         => Controls_Manager::SWITCHER,
		        'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
		        'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
		        'return_value' => 'true',
		        'classes'      => 'eael-pro-control',
	        ] );

            $this->add_control(
                'eael_pricing_table_style_pro_alert',
                [
                    'label'     => esc_html__('Sorting feature is available in pro version!', 'essential-addons-for-elementor-lite'),
                    'type'      => Controls_Manager::HEADING,
                    'condition' => [
                        'eael_section_data_table_enabled' => 'true',
                    ]
                ]
            );
        }

        $repeater = new Repeater();

        $repeater->add_control(
            'eael_data_table_header_col',
            [
                'label' => esc_html__('Column Name', 'essential-addons-for-elementor-lite'),
                'default' => esc_html__('Table Header', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'label_block' => false,
				'ai' => [
					'active' => false,
				],
            ]
        );

        $repeater->add_control(
            'eael_data_table_header_col_span',
            [
                'label' => esc_html__('Column Span', 'essential-addons-for-elementor-lite'),
                'default' => '',
                'type' => Controls_Manager::TEXT,
                'dynamic'   => ['active' => true],
                'label_block' => false,
				'ai' => [
					'active' => false,
				],
            ]
        );

        $repeater->add_control(
            'eael_data_table_header_col_icon_enabled',
            [
                'label' => esc_html__('Enable Header Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('no', 'essential-addons-for-elementor-lite'),
                'default' => 'false',
                'return_value' => 'true',
            ]
        );

        $repeater->add_control(
            'eael_data_table_header_icon_type',
            [
                'label'    => esc_html__('Header Icon Type', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options'               => [
                    'none'        => [
                        'title'   => esc_html__('None', 'essential-addons-for-elementor-lite'),
                        'icon'    => 'fa fa-ban',
                    ],
                    'icon'        => [
                        'title'   => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                        'icon'    => 'fa fa-star',
                    ],
                    'image'       => [
                        'title'   => esc_html__('Image', 'essential-addons-for-elementor-lite'),
                        'icon'    => 'eicon-image-bold',
                    ],
                ],
                'default'               => 'icon',
                'condition' => [
                    'eael_data_table_header_col_icon_enabled' => 'true'
                ]
            ]
        );

        // Comment on this control
        $repeater->add_control(
            'eael_data_table_header_col_icon_new',
            [
                'label' => esc_html__('Icon', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_data_table_header_col_icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
				'condition' => [
					'eael_data_table_header_col_icon_enabled' => 'true',
					'eael_data_table_header_icon_type'	=> 'icon'
				]
			]
		);

		$repeater->add_control(
			'eael_data_table_header_col_img',
			[
				'label' => esc_html__( 'Image', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'eael_data_table_header_icon_type'	=> 'image'
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$repeater->add_control(
			'eael_data_table_header_col_img_size',
			[
				'label' => esc_html__( 'Image Size(px)', 'essential-addons-for-elementor-lite'),
				'default' => '25',
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'condition' => [
					'eael_data_table_header_icon_type'	=> 'image'
				]
			]
		);

		$repeater->add_control(
			'eael_data_table_header_css_class',
			[
				'label'			=> esc_html__( 'CSS Class', 'essential-addons-for-elementor-lite'),
				'type'			=> Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
				'label_block' 	=> false,
				'ai' => [
					'active' => false,
				],
			]
		);

		$repeater->add_control(
			'eael_data_table_header_css_id',
			[
				'label'			=> esc_html__( 'CSS ID', 'essential-addons-for-elementor-lite'),
				'type'			=> Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
				'label_block'	=> false,
				'ai' => [
					'active' => false,
				],
			]
		);

  		$this->add_control(
			'eael_data_table_header_cols_data',
			[
				'type' => Controls_Manager::REPEATER,
				'seperator' => 'before',
				'default' => [
					[ 'eael_data_table_header_col' => 'Table Header' ],
					[ 'eael_data_table_header_col' => 'Table Header' ],
					[ 'eael_data_table_header_col' => 'Table Header' ],
					[ 'eael_data_table_header_col' => 'Table Header' ],
				],
				'fields'      =>  $repeater->get_controls() ,
				'title_field' => '{{eael_data_table_header_col}}',
			]
		);

  		$this->end_controls_section();

  		/**
  		 * Data Table Content
  		 */
  		$this->start_controls_section(
  			'eael_section_data_table_cotnent',
  			[
  				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite')
  			]
		  );

		$repeater = new Repeater();

		$repeater->add_control(
			'eael_data_table_content_row_type',
			[
				'label' => esc_html__( 'Row Type', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SELECT,
				'default' => 'row',
				'label_block' => false,
				'options' => [
					'row' => esc_html__( 'Row', 'essential-addons-for-elementor-lite'),
					'col' => esc_html__( 'Column', 'essential-addons-for-elementor-lite'),
				]
			]
		);

		$repeater->add_control(
			'eael_data_table_content_row_colspan',
			[
				'label'			=> esc_html__( 'Col Span', 'essential-addons-for-elementor-lite'),
				'type'			=> Controls_Manager::NUMBER,
				'description'	=> esc_html__( 'Default: 1 (optional).'),
				'default' 		=> 1,
				'min'     		=> 1,
				'label_block'	=> true,
				'condition' 	=> [
					'eael_data_table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_control(
			'eael_data_table_content_type',
			[
				'label'		=> esc_html__( 'Content Type', 'essential-addons-for-elementor-lite'),
				'type'	=> Controls_Manager::CHOOSE,
				'options'               => [
                    'icon' => [
						'title' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite'),
						'icon' => 'fa fa-info',
					],
					'textarea'        => [
						'title'   => esc_html__( 'Textarea', 'essential-addons-for-elementor-lite'),
						'icon'    => 'fa fa-text-width',
					],
					'editor'       => [
						'title'   => esc_html__( 'Editor', 'essential-addons-for-elementor-lite'),
						'icon'    => 'eicon-pencil',
					],
					'template'        => [
						'title'   => esc_html__( 'Templates', 'essential-addons-for-elementor-lite'),
						'icon'    => 'fa fa-file',
					]
				],
				'default'	=> 'textarea',
				'condition' => [
					'eael_data_table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_control(
			'eael_data_table_content_row_rowspan',
			[
				'label'			=> esc_html__( 'Row Span', 'essential-addons-for-elementor-lite'),
				'type'			=> Controls_Manager::NUMBER,
				'description'	=> esc_html__( 'Default: 1 (optional).'),
				'default' 		=> 1,
				'min'     		=> 1,
				'label_block'	=> true,
				'condition' 	=> [
					'eael_data_table_content_row_type' => 'col'
				]
			]
		);

		$repeater->add_control(
			'eael_primary_templates_for_tables',
			[
				'label'                 => __( 'Choose Template', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::SELECT,
				'options'               => Helper::get_elementor_templates(),
				'condition'             => [
					'eael_data_table_content_type'      => 'template',
				],
			]
		);

        $repeater->add_control(
			'eael_data_table_icon_content_new',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'eael_data_table_icon_content',
				'default' => [
					'value' => 'fas fa-home',
					'library' => 'fa-solid',
				],
				'condition' => [
					'eael_data_table_content_type' => [ 'icon' ]
				]
			]
		);

		$repeater->add_control(
			'eael_data_table_content_row_title',
			[
				'label' => esc_html__( 'Cell Text', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::TEXTAREA,
                'dynamic'   => ['active' => true],
				'label_block' => true,
				'default' => esc_html__( 'Content', 'essential-addons-for-elementor-lite'),
				'condition' => [
					'eael_data_table_content_row_type' => 'col',
					'eael_data_table_content_type' => 'textarea'
				]
			]
		);

		$repeater->add_control(
			'eael_data_table_content_row_content',
			[
				'label' => esc_html__( 'Cell Text', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default' => esc_html__( 'Content', 'essential-addons-for-elementor-lite'),
				'condition' => [
					'eael_data_table_content_row_type' => 'col',
					'eael_data_table_content_type' => 'editor'
				]
			]
		);

		$repeater->add_control(
			'eael_data_table_content_row_title_link',
			[
				'label' => esc_html__( 'Link', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::URL,
                'dynamic'   => ['active' => true],
				'label_block' => true,
				'default' => [
						'url' => '',
						'is_external' => '',
					 ],
					 'show_external' => true,
					 'separator' => 'before',
				 'condition' => [
					'eael_data_table_content_row_type' => 'col',
					'eael_data_table_content_type' => 'textarea'
				],
			]
		);

		$repeater->add_control(
			'eael_data_table_content_row_css_class',
			[
				'label'			=> esc_html__( 'CSS Class', 'essential-addons-for-elementor-lite'),
				'type'			=> Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
				'label_block'	=> false,
				'condition' 	=> [
					'eael_data_table_content_row_type' => 'col'
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$repeater->add_control(
			'eael_data_table_content_row_css_id',
			[
				'label'			=> esc_html__( 'CSS ID', 'essential-addons-for-elementor-lite'),
				'type'			=> Controls_Manager::TEXT,
                'dynamic'     => [ 'active' => true ],
				'label_block'	=> false,
				'condition' 	=> [
					'eael_data_table_content_row_type' => 'col'
				],
				'ai' => [
					'active' => false,
				],
			]
		);

  		$this->add_control(
			'eael_data_table_content_rows',
			[
				'type' => Controls_Manager::REPEATER,
				'seperator' => 'before',
				'default' => [
					[ 'eael_data_table_content_row_type' => 'row' ],
					[ 'eael_data_table_content_row_type' => 'col' ],
					[ 'eael_data_table_content_row_type' => 'col' ],
					[ 'eael_data_table_content_row_type' => 'col' ],
					[ 'eael_data_table_content_row_type' => 'col' ],
				],
				'fields' =>  $repeater->get_controls() ,
				'title_field' => '{{eael_data_table_content_row_type}}::{{eael_data_table_content_row_title || eael_data_table_content_row_content}}',
			]
		);

		$this->end_controls_section();

		// export
        $this->start_controls_section(
            'ea_section_adv_data_table_export',
            [
                'label' => esc_html__('Export', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'ea_adv_data_table_export_csv_button',
            [
                'label' => __('Export table as CSV file', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::BUTTON,
                'text'  => __('Export', 'essential-addons-for-elementor-lite'),
                'event' => 'ea:table:export',
            ]
        );

		$this->end_controls_section();

		if(!apply_filters('eael/pro_enabled', false)) {
			$this->start_controls_section(
				'eael_section_pro',
				[
					'label' => __( 'Go Premium for More Features', 'essential-addons-for-elementor-lite')
				]
			);

			$this->add_control(
				'eael_control_get_pro',
				[
					'label' => __( 'Unlock more possibilities', 'essential-addons-for-elementor-lite'),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'1' => [
							'title' => '',
							'icon' => 'fa fa-unlock-alt',
						],
					],
					'default' => '1',
					'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">Pro version</a> for more stunning elements and customization options.</span>'
				]
			);

			$this->end_controls_section();
		}

  		/**
		 * -------------------------------------------
		 * Tab Style (Data Table Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_data_table_style_settings',
			[
				'label' => esc_html__( 'General Style', 'essential-addons-for-elementor-lite'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
            'table_width',
            [
                'label'      => __('Width', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'size_units' => ['%', 'px'],
                'range'      => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-data-table' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'table_alignment',
            [
                'label'        => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'default'      => 'center',
                'options'      => [
                    'left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-h-align-right',
                    ],
				],
                'prefix_class'           => 'eael-table-align-',
            ]
        );

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Data Table Header Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_data_table_title_style_settings',
			[
				'label' => esc_html__( 'Header Style', 'essential-addons-for-elementor-lite'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);


		$this->add_control(
			'eael_section_data_table_header_radius',
			[
				'label' => esc_html__( 'Header Border Radius', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-data-table thead tr th:first-child' => 'border-radius: {{SIZE}}px 0px 0px 0px;',
					'{{WRAPPER}} .eael-data-table thead tr th:last-child' => 'border-radius: 0px {{SIZE}}px 0px 0px;',
                    '.rtl {{WRAPPER}} .eael-data-table thead tr th:first-child' => 'border-radius: 0px {{SIZE}}px 0px 0px;',
                    '.rtl {{WRAPPER}} .eael-data-table thead tr th:last-child' => 'border-radius: {{SIZE}}px 0px 0px 0px;',
				],
			]
		);

		$this->add_responsive_control(
			'eael_data_table_each_header_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-data-table .table-header th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-data-table tbody tr td .th-mobile-screen' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('eael_data_table_header_title_clrbg');

			$this->start_controls_tab( 'eael_data_table_header_title_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite') ] );

				$this->add_control(
					'eael_data_table_header_title_color',
					[
						'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::COLOR,
						'default' => '#fff',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table thead tr th' => 'color: {{VALUE}};',
							'{{WRAPPER}} table.dataTable thead .sorting:after' => 'color: {{VALUE}};',
							'{{WRAPPER}} table.dataTable thead .sorting_asc:after' => 'color: {{VALUE}};',
							'{{WRAPPER}} table.dataTable thead .sorting_desc:after' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_header_title_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::COLOR,
						'default' => '#4a4893',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table thead tr th' => 'background-color: {{VALUE}};'
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
						[
							'name' => 'eael_data_table_header_border',
							'label' => esc_html__( 'Border', 'essential-addons-for-elementor-lite'),
							'selector' => '{{WRAPPER}} .eael-data-table thead tr th'
						]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'eael_data_table_header_title_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite') ] );

				$this->add_control(
					'eael_data_table_header_title_hover_color',
					[
						'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::COLOR,
						'default' => '#fff',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table thead tr th:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}} table.dataTable thead .sorting:after:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}} table.dataTable thead .sorting_asc:after:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}} table.dataTable thead .sorting_desc:after:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_header_title_hover_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .eael-data-table thead tr th:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
						[
							'name' => 'eael_data_table_header_hover_border',
							'label' => esc_html__( 'Border', 'essential-addons-for-elementor-lite'),
							'selector' => '{{WRAPPER}} .eael-data-table thead tr th:hover',
						]
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             	'name' => 'eael_data_table_header_title_typography',
				'selector' => '{{WRAPPER}} .eael-data-table thead > tr th .data-table-header-text',
			]
		);

		$this->add_responsive_control(
            'header_icon_size',
            [
                'label'      => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 20,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-data-table thead tr th i'                           => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-data-table thead tr th .data-table-header-svg-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_icon_position_from_top',
            [
                'label'      => __('Icon Position', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-data-table thead tr th .data-header-icon' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_icon_space',
            [
                'label'      => __('Icon Space', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
				],
                'selectors'             => [
					'{{WRAPPER}} .eael-data-table thead tr th i, {{WRAPPER}} .eael-data-table thead tr th img' => 'margin-right: {{SIZE}}{{UNIT}};'
				]
            ]
		);

		$this->add_responsive_control(
			'eael_data_table_header_title_alignment',
			[
				'label' => esc_html__( 'Title Alignment', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'prefix_class' => 'eael-dt-th-align%s-',
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Data Table Content Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_data_table_content_style_settings',
			[
				'label' => esc_html__( 'Content Style', 'essential-addons-for-elementor-lite'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs('eael_data_table_content_row_cell_styles');

			$this->start_controls_tab('eael_data_table_odd_cell_style', ['label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite')]);

				$this->add_control(
					'eael_data_table_content_odd_style_heading',
					[
						'label' => esc_html__( 'ODD Cell', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::HEADING,
					]
				);

				$this->add_control(
					'eael_data_table_content_color_odd',
					[
						'label' => esc_html__( 'Color ( Odd Row )', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::COLOR,
						'default' => '#000000',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n) td' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_content_bg_odd',
					[
						'label' => esc_html__( 'Background ( Odd Row )', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::COLOR,
						'default' => '#f2f2f2',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n) td' => 'background: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_content_even_style_heading',
					[
						'label' => esc_html__( 'Even Cell', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::HEADING,
						'separator'	=> 'before'
					]
				);

				$this->add_control(
					'eael_data_table_content_even_color',
					[
						'label' => esc_html__( 'Color ( Even Row )', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::COLOR,
						'default' => '#000000',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n+1) td' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_content_bg_even_color',
					[
						'label' => esc_html__( 'Background Color (Even Row)', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n+1) td' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Border::get_type(),
						[
							'name' => 'eael_data_table_cell_border',
							'label' => esc_html__( 'Border', 'essential-addons-for-elementor-lite'),
							'selector' => '{{WRAPPER}} .eael-data-table tbody tr td',
							'separator'	=> 'before'
						]
				);

				$this->add_responsive_control(
					'eael_data_table_each_cell_padding',
					[
						'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em' ],
						'selectors' => [
								 '{{WRAPPER}} .eael-data-table tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						 ],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab('eael_data_table_odd_cell_hover_style', ['label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite')]);

				$this->add_control(
					'eael_data_table_content_hover_color_odd',
					[
						'label' => esc_html__( 'Color ( Odd Row )', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n) td:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_content_hover_bg_odd',
					[
						'label' => esc_html__( 'Background ( Odd Row )', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n) td:hover' => 'background: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_content_even_hover_style_heading',
					[
						'label' => esc_html__( 'Even Cell', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::HEADING,
					]
				);

				$this->add_control(
					'eael_data_table_content_hover_color_even',
					[
						'label' => esc_html__( 'Color ( Even Row )', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::COLOR,
						'default' => '#6d7882',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n+1) td:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_content_bg_even_hover_color',
					[
						'label' => esc_html__( 'Background Color (Even Row)', 'essential-addons-for-elementor-lite'),
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n+1) td:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             	'name' => 'eael_data_table_content_typography',
				'selector' => '{{WRAPPER}} .eael-data-table tbody tr td'
			]
		);

		$this->add_control(
			'eael_data_table_content_link_typo',
			[
				'label' => esc_html__( 'Link Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::HEADING,
				'separator'	=> 'before'
			]
		);

		/* Table Content Link */
		$this->start_controls_tabs( 'eael_data_table_link_tabs' );

			// Normal State Tab
			$this->start_controls_tab( 'eael_data_table_link_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite') ] );

			$this->add_control(
				'eael_data_table_link_normal_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite'),
					'type' => Controls_Manager::COLOR,
					'default' => '#c15959',
					'selectors' => [
						'{{WRAPPER}} .eael-data-table-wrap table td a' => 'color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			// Hover State Tab
			$this->start_controls_tab( 'eael_data_table_link_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite') ] );

			$this->add_control(
				'eael_data_table_link_hover_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite'),
					'type' => Controls_Manager::COLOR,
					'default' => '#6d7882',
					'selectors' => [
						'{{WRAPPER}} .eael-data-table-wrap table td a:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'eael_data_table_content_alignment',
			[
				'label' => esc_html__( 'Content Alignment', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-text-align-right',
					],
				],
//				'toggle' => true,
				'default' => 'left',
                'selectors' => [
                        '{{WRAPPER}} .eael-data-table tbody .td-content-wrapper' => 'text-align: {{VALUE}};'
                ],
//				'prefix_class' => 'eael-dt-td-align%s-',
			]
		);

		/* Table Content Icon  Style*/

        $this->add_control(
			'eael_data_table_content_icon_style',
			[
				'label' => esc_html__( 'Icon Style', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::HEADING,
				'separator'	=> 'before'
			]
		);

        $this->add_responsive_control(
            'eael_data_table_content_icon_size',
            [
                'label'      => __('Icon Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 70,
                    ],
                ],
                'default'    => [
                    'size' => 20,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-data-table tbody .td-content-wrapper .eael-datatable-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-data-table tbody .td-content-wrapper .eael-datatable-icon svg' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
				'separator'	=> 'before'
            ]
        );

		$this->start_controls_tabs( 'eael_data_table_icon_tabs' );

			// Normal State Tab
			$this->start_controls_tab( 'eael_data_table_icon_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite') ] );

			$this->add_control(
				'eael_data_table_icon_normal_color',
				[
					'label' => esc_html__( 'Icon Color', 'essential-addons-for-elementor-lite'),
					'type' => Controls_Manager::COLOR,
					'default' => '#c15959',
					'selectors' => [
						'{{WRAPPER}} .eael-data-table tbody .td-content-wrapper .eael-datatable-icon i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .eael-data-table tbody .td-content-wrapper .eael-datatable-icon svg' => 'fill: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			// Hover State Tab
			$this->start_controls_tab( 'eael_data_table_icon_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite') ] );

			$this->add_control(
				'eael_data_table_link_hover_color',
				[
					'label' => esc_html__( 'Icon Color', 'essential-addons-for-elementor-lite'),
					'type' => Controls_Manager::COLOR,
					'default' => '#6d7882',
					'selectors' => [
						'{{WRAPPER}} .eael-data-table tbody .td-content-wrapper:hover .eael-datatable-icon i' => 'color: {{VALUE}};',
						'{{WRAPPER}} .eael-data-table tbody .td-content-wrapper:hover .eael-datatable-icon svg' => 'fill: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Responsive Style (Data Table Content Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_data_table_responsive_style_settings',
			[
				'label'		=> esc_html__( 'Responsive Options', 'essential-addons-for-elementor-lite'),
				'devices'	=> [ 'tablet', 'mobile' ],
				'tab'		=> Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
		  'eael_enable_responsive_header_styles',
		  	[
				'label'			=> __( 'Enable Responsive Table', 'essential-addons-for-elementor-lite'),
				'description'	=> esc_html__( 'If enabled, table header will be automatically responsive for mobile.', 'essential-addons-for-elementor-lite'),
				'type'			=> Controls_Manager::SWITCHER,
				'label_on'		=> esc_html__( 'Yes', 'essential-addons-for-elementor-lite'),
				'label_off' 	=> esc_html__( 'No', 'essential-addons-for-elementor-lite'),
				'return_value' 	=> 'yes',
		  	]
		);

	    $this->add_control(
		    'eael_data_table_responsive_breakpoint',
		    [
			    'label' => esc_html__( 'Custom Breakpoint', 'essential-addons-for-elementor-lite'),
			    'type' => Controls_Manager::NUMBER,
			    'default' => 767,
                'min' => 100,
			    'description'	=> esc_html__( 'Responsive styles working till this screen size.', 'essential-addons-for-elementor-lite'),
			    'condition'	=> [
				    'eael_enable_responsive_header_styles'	=> 'yes'
			    ]
		    ]
	    );

		$this->add_responsive_control(
            'mobile_table_header_width',
            [
                'label'      => __('Width', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => 100,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-data-table .th-mobile-screen' => 'flex-basis: {{SIZE}}px;',
                ],
                'condition'  => [
                    'eael_enable_responsive_header_styles' => 'yes',
                ],
            ]
		);

		$this->add_responsive_control(
			'eael_data_table_responsive_header_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-data-table tbody .th-mobile-screen'	=> 'color: {{VALUE}};'
				],
				'condition'	=> [
                	'eael_enable_responsive_header_styles'	=> 'yes'
                ]
			]
		);

		$this->add_responsive_control(
			'eael_data_table_responsive_header_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-data-table tbody .th-mobile-screen'	=> 'background-color: {{VALUE}};'
				],
				'condition'	=> [
                	'eael_enable_responsive_header_styles'	=> 'yes'
                ]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'		=> 'eael_data_table_responsive_header_typography',
				'selector'	=> '{{WRAPPER}} .eael-data-table .th-mobile-screen',
				'condition'	=> [
                	'eael_enable_responsive_header_styles'	=> 'yes'
                ]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
				[
					'name' => 'eael_data_table_responsive_header_border',
					'label' => esc_html__( 'Border', 'essential-addons-for-elementor-lite'),
					'selector' => '{{WRAPPER}} tbody td .th-mobile-screen',
					'condition'	=> [
	                	'eael_enable_responsive_header_styles'	=> 'yes'
	                ]
				]
		);


		$this->end_controls_section();

	}

	public function get_style_depends() {
		return [
			'font-awesome-5-all',
			'font-awesome-4-shim',
		];
	}

	protected function render( ) {

        $settings = $this->get_settings_for_display();

        $table_tr = [];
		$table_td = [];

	  	// Storing Data table content values
	  	foreach( $settings['eael_data_table_content_rows'] as $content_row ) {
	  		$row_id = uniqid();
	  		if( $content_row['eael_data_table_content_row_type'] == 'row' ) {
	  			$table_tr[] = [
	  				'id' => $row_id,
	  				'type' => $content_row['eael_data_table_content_row_type'],
	  			];

	  		}
	  		if( $content_row['eael_data_table_content_row_type'] == 'col' ) {

                $icon_migrated = isset($settings['__fa4_migrated']['eael_data_table_icon_content_new']);
                $icon_is_new = empty($settings['eael_data_table_icon_content']);

	  			$target = !empty($content_row['eael_data_table_content_row_title_link']['is_external']) ? 'target="_blank"' : '';
	  			$nofollow = !empty($content_row['eael_data_table_content_row_title_link']['nofollow']) ? 'rel="nofollow"' : '';

	  			$table_tr_keys = array_keys( $table_tr );
	  			$last_key = end( $table_tr_keys );
				$tbody_content = ($content_row['eael_data_table_content_type'] == 'editor') ? $content_row['eael_data_table_content_row_content'] : Helper::eael_wp_kses($content_row['eael_data_table_content_row_title']);

	  			$table_td[] = [
	  				'row_id'		=> !empty( $table_tr[$last_key]['id'] ) ? $table_tr[$last_key]['id'] : $row_id,
	  				'type'			=> $content_row['eael_data_table_content_row_type'],
					'content_type'	=> $content_row['eael_data_table_content_type'],
					'template'		=> $content_row['eael_primary_templates_for_tables'],
	  				'title'			=> $tbody_content,
	  				'link_url'		=> !empty($content_row['eael_data_table_content_row_title_link']['url'])?$content_row['eael_data_table_content_row_title_link']['url']:'',
	  				'icon_content_new'	=> !empty($content_row['eael_data_table_icon_content_new']) ? $content_row['eael_data_table_icon_content_new']:'',
	  				'icon_content'	=> !empty($content_row['eael_data_table_icon_content']) ? $content_row['eael_data_table_icon_content']:'',
	  				'icon_migrated'	=> $icon_migrated,
	  				'icon_is_new'	=> $icon_is_new,
	  				'link_target'	=> $target,
	  				'nofollow'		=> $nofollow,
					'colspan'		=> $content_row['eael_data_table_content_row_colspan'],
					'rowspan'		=> $content_row['eael_data_table_content_row_rowspan'],
					'tr_class'		=> $content_row['eael_data_table_content_row_css_class'],
					'tr_id'			=> $content_row['eael_data_table_content_row_css_id']
	  			];
	  		}
		}
		$table_th_count = count($settings['eael_data_table_header_cols_data']);
		$this->add_render_attribute('eael_data_table_wrap', [
			'class'                  => 'eael-data-table-wrap',
			'data-table_id'          => esc_attr($this->get_id()),
            'id'                     => 'eael-data-table-wrapper-'.esc_attr($this->get_id()),
			'data-custom_responsive' => $settings['eael_enable_responsive_header_styles'] ? 'true' : 'false'
		]);
		if(isset($settings['eael_section_data_table_enabled']) && $settings['eael_section_data_table_enabled']){
			$this->add_render_attribute('eael_data_table_wrap', 'data-table_enabled', 'true');
		}
		$this->add_render_attribute('eael_data_table', [
			'class'	=> [ 'tablesorter eael-data-table', esc_attr($settings['table_alignment']) ],
			'id'	=> 'eael-data-table-'.esc_attr($this->get_id())
		]);

		$this->add_render_attribute( 'td_content', [
			'class'	=> 'td-content'
		]);

		if('yes' == $settings['eael_enable_responsive_header_styles']) {
			$this->add_render_attribute('eael_data_table_wrap', 'class', 'custom-responsive-option-enable');
			$break_point = $settings['eael_data_table_responsive_breakpoint'] ? $settings['eael_data_table_responsive_breakpoint'] : 767;
			$section_id  = $this->get_id();
			echo '<style>
			@media (max-width: ' . intval( $break_point ) . 'px) {
			   #eael-data-table-wrapper-' . esc_html( $section_id ) . '.custom-responsive-option-enable .eael-data-table thead {
                    display: none;
               }
               #eael-data-table-wrapper-' . esc_html( $section_id ) . '.custom-responsive-option-enable .eael-data-table tbody tr td {
                    float: none;
                    clear: left;
                    width: 100%;
                    text-align: left;
                    display: flex;
                    align-items: center;
                }
			}
			</style>';
		}
	  	?>
		<div <?php echo $this->get_render_attribute_string('eael_data_table_wrap'); ?>>
			<table <?php echo $this->get_render_attribute_string('eael_data_table'); ?>>
			    <thead>
			        <tr class="table-header">
						<?php $i = 0; foreach( $settings['eael_data_table_header_cols_data'] as $header_title ) :
							$this->add_render_attribute('th_class'.$i, [
								'class'		=> [ $header_title['eael_data_table_header_css_class'] ],
								'id'		=> $header_title['eael_data_table_header_css_id'],
								'colspan'	=> $header_title['eael_data_table_header_col_span']
							]);

							if(apply_filters('eael/pro_enabled', false)) {
								$this->add_render_attribute('th_class'.$i, 'class', 'sorting' );
							}
						?>
			            <th <?php echo $this->get_render_attribute_string('th_class'.$i); ?>>
							<?php if( $header_title['eael_data_table_header_col_icon_enabled'] == 'true' && $header_title['eael_data_table_header_icon_type'] == 'icon' ) : ?>
								<?php if (empty($header_title['eael_data_table_header_col_icon']) || isset($header_title['__fa4_migrated']['eael_data_table_header_col_icon_new'])) { ?>
									<?php if( isset($header_title['eael_data_table_header_col_icon_new']['value']['url']) ) : ?>
										<img class="data-header-icon data-table-header-svg-icon" src="<?php echo esc_url( $header_title['eael_data_table_header_col_icon_new']['value']['url'] ); ?>" alt="<?php echo esc_attr(get_post_meta($header_title['eael_data_table_header_col_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
									<?php else : ?>
										<i class="<?php echo esc_attr( $header_title['eael_data_table_header_col_icon_new']['value'] ); ?> data-header-icon"></i>
									<?php endif; ?>
								<?php } else { ?>
									<i class="<?php echo esc_attr( $header_title['eael_data_table_header_col_icon'] ); ?> data-header-icon"></i>
								<?php } ?>
			            	<?php endif; ?>
							<?php
								if( $header_title['eael_data_table_header_col_icon_enabled'] == 'true' && $header_title['eael_data_table_header_icon_type'] == 'image' ) :
									$this->add_render_attribute('data_table_th_img'.$i, [
										'src'	=> esc_url( $header_title['eael_data_table_header_col_img']['url'] ),
										'class'	=> 'eael-data-table-th-img',
										'style'	=> "width:{$header_title['eael_data_table_header_col_img_size']}px;",
										'alt'	=> esc_attr(get_post_meta($header_title['eael_data_table_header_col_img']['id'], '_wp_attachment_image_alt', true))
									]);
							?><img <?php echo $this->get_render_attribute_string('data_table_th_img'.$i); ?>><?php endif; ?><span class="data-table-header-text"><?php echo __( Helper::eael_wp_kses($header_title['eael_data_table_header_col']), 'essential-addons-for-elementor-lite'); ?></span></th>
			        	<?php $i++; endforeach; ?>
			        </tr>
			    </thead>
			  	<tbody>
					<?php for( $i = 0; $i < count( $table_tr ); $i++ ) : ?>
						<tr>
							<?php
								for( $j = 0; $j < count( $table_td ); $j++ ) {
									if( $table_tr[$i]['id'] == $table_td[$j]['row_id'] ) {

										$this->add_render_attribute('table_inside_td'.$i.$j,
											[
												'colspan' => $table_td[$j]['colspan'] > 1 ? $table_td[$j]['colspan'] : '',
												'rowspan' => $table_td[$j]['rowspan'] > 1 ? $table_td[$j]['rowspan'] : '',
												'class'		=> $table_td[$j]['tr_class'],
												'id'		=> $table_td[$j]['tr_id']
											]
										);
										?>
									   <?php if(  $table_td[$j]['content_type'] == 'icon' ) : ?>
											<td <?php echo $this->get_render_attribute_string('table_inside_td'.$i.$j); ?>>
												<div class="td-content-wrapper">
													<?php if ( $table_td[$j]['icon_is_new'] || $table_td[$j]['icon_migrated']) { ?>
                                                        <div class="eael-datatable-icon td-content">
                                                        <?php Icons_Manager::render_icon( $table_td[$j]['icon_content_new'] );?>
                                                        </div>
                                                   <?php } else { ?>
                                                        <div class="td-content">
                                                            <span class="<?php echo esc_attr( $table_td[ $j ]['icon_content'] ); ?>" aria-hidden="true"></span>
                                                        </div>
                                                    <?php } ?>
												</div>
											</td>
										<?php elseif(  $table_td[$j]['content_type'] == 'textarea' && !empty($table_td[$j]['link_url']) ) : ?>
											<td <?php echo $this->get_render_attribute_string('table_inside_td'.$i.$j); ?>>
												<div class="td-content-wrapper">
													<a href="<?php echo esc_url( $table_td[$j]['link_url'] ); ?>" <?php echo $table_td[$j]['link_target'] ?> <?php echo $table_td[$j]['nofollow'] ?>><?php echo wp_kses_post($table_td[$j]['title']); ?></a>
												</div>
											</td>

										<?php elseif( $table_td[$j]['content_type'] == 'template' && ! empty($table_td[$j]['template']) ) : ?>
										<td <?php echo $this->get_render_attribute_string('table_inside_td'.$i.$j); ?>>
											<div class="td-content-wrapper">
												<div <?php echo $this->get_render_attribute_string('td_content'); ?>>
													<?php
													// WPML Compatibility
													if ( ! is_array( $table_td[ $j ]['template'] ) ) {
														$table_td[ $j ]['template'] = apply_filters( 'wpml_object_id', $table_td[ $j ]['template'], 'wp_template', true );
													}
													echo Plugin::$instance->frontend->get_builder_content( intval( $table_td[ $j ]['template'] ), true );
													?>
												</div>
											</div>
										</td>
										<?php else: ?>
											<td <?php echo $this->get_render_attribute_string('table_inside_td'.$i.$j); ?>>
												<div class="td-content-wrapper"><div <?php echo $this->get_render_attribute_string('td_content'); ?>><?php echo $table_td[$j]['title']; ?></div></div>
											</td>
										<?php endif; ?>
										<?php
									}
								}
							?>
						</tr>
			        <?php endfor; ?>
			    </tbody>
			</table>
		</div>
	  	<?php
	}

	protected function content_template() {}
}

<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Widget_Eael_Data_Table extends Widget_Base {
	public $unique_id = null;
	public function get_name() {
		return 'eael-data-table';
	}

	public function get_title() {
		return esc_html__( 'EA Data Table', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-table';
	}

	public function get_script_depends() {
        return [
			'eael-scripts'
        ];
    }

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {

  		/**
  		 * Data Table Header
  		 */
  		$this->start_controls_section(
  			'eael_section_data_table_header',
  			[
  				'label' => esc_html__( 'Header', 'essential-addons-elementor' )
  			]
  		);

  		$this->add_control(
			'eael_section_data_table_enabled',
			[
				'label' => __( 'Enable Table Sorting', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'false',
				'label_on' => esc_html__( 'Yes', 'essential-addons-elementor' ),
				'label_off' => esc_html__( 'No', 'essential-addons-elementor' ),
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'eael_section_data_table_enabled_pro_alert',
			[
				'label' => esc_html__( 'Table Sorting available in pro version!', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'eael_section_data_table_enabled' => 'true',
				]
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
				'fields' => [
					[
						'name' => 'eael_data_table_header_col',
						'label' => esc_html__( 'Column Name', 'essential-addons-elementor' ),
						'default' => 'Table Header',
						'type' => Controls_Manager::TEXT,
						'label_block' => false,
					],
					[
						'name' => 'eael_data_table_header_col_span',
						'label' => esc_html__( 'Column Span', 'essential-addons-elementor' ),
						'default' => '',
						'type' => Controls_Manager::NUMBER,
						'label_block' => false,
					],
					[
						'name'	=> 'data_table_header_colspan_pro_alert',
						'label' => esc_html__( 'Column Span available in pro version!', 'essential-addons-elementor' ),
						'type' => Controls_Manager::HEADING,
						'condition' => [
							'eael_data_table_header_col_span!' => '',
						]
					],
					[
						'name' => 'eael_data_table_header_col_icon_enabled',
						'label' => esc_html__( 'Enable Header Icon', 'essential-addons-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => __( 'yes', 'essential-addons-elementor' ),
						'label_off' => __( 'no', 'essential-addons-elementor' ),
						'default' => 'false',
						'return_value' => 'true',
					],
					[
						'name'	=> 'eael_data_table_header_icon_type',
						'label'	=> esc_html__( 'Header Icon Type', 'essential-addons-elementor' ),
						'type'	=> Controls_Manager::CHOOSE,
						'options'               => [
							'none'        => [
								'title'   => esc_html__( 'None', 'essential-addons-elementor' ),
								'icon'    => 'fa fa-ban',
							],
							'icon'        => [
								'title'   => esc_html__( 'Icon', 'essential-addons-elementor' ),
								'icon'    => 'fa fa-star',
							],
							'image'       => [
								'title'   => esc_html__( 'Image', 'essential-addons-elementor' ),
								'icon'    => 'fa fa-picture-o',
							],
						],
						'default'               => 'icon',
						'condition' => [
							'eael_data_table_header_col_icon_enabled' => 'true'
						]
					],
					[
						'name'	=> 'data_table_header_img_pro_alert',
						'label' => esc_html__( 'Image icon available in pro version!', 'essential-addons-elementor' ),
						'type' => Controls_Manager::HEADING,
						'condition' => [
							'eael_data_table_header_icon_type' => 'image',
						]
					],
					[
						'name' => 'eael_data_table_header_col_icon',
						'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
						'type' => Controls_Manager::ICON,
						'default' => '',
						'condition' => [
							'eael_data_table_header_col_icon_enabled' => 'true',
							'eael_data_table_header_icon_type'	=> 'icon'
						]
					],
					[
						'name'			=> 'eael_data_table_header_css_class',
						'label'			=> esc_html__( 'CSS Class', 'essential-addons-elementor' ),
						'type'			=> Controls_Manager::TEXT,
						'label_block' 	=> false,
					],
					[
						'name'			=> 'eael_data_table_header_css_id',
						'label'			=> esc_html__( 'CSS ID', 'essential-addons-elementor' ),
						'type'			=> Controls_Manager::TEXT,
						'label_block'	=> false,
					],

				],
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
  				'label' => esc_html__( 'Content', 'essential-addons-elementor' )
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
				'fields' => [
					[
						'name' => 'eael_data_table_content_row_type',
						'label' => esc_html__( 'Row Type', 'essential-addons-elementor' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'row',
						'label_block' => false,
						'options' => [
							'row' => esc_html__( 'Row', 'essential-addons-elementor' ),
							'col' => esc_html__( 'Column', 'essential-addons-elementor' ),
						]
					],
					[
						'name'			=> 'eael_data_table_content_row_colspan',
						'label'			=> esc_html__( 'Col Span', 'essential-addons-elementor' ),
						'type'			=> Controls_Manager::NUMBER,
						'default' 		=> '1',
						'min'			=> '1',
						'label_block'	=> false,
						'condition' 	=> [
							'eael_data_table_content_row_type' => 'col'
						]
					],
					[
						'name'			=> 'eael_data_table_content_row_rowspan',
						'label'			=> esc_html__( 'Row Span', 'essential-addons-elementor' ),
						'type'			=> Controls_Manager::NUMBER,
						'label_block'	=> false,
						'condition' 	=> [
							'eael_data_table_content_row_type' => 'col'
						]
					],
					[
						'name'	=> 'data_table_content_rowspan_pro_alert',
						'label'	=> esc_html__( 'Row Span available in pro version!', 'essential-addons-elementor' ),
						'type'	=> Controls_Manager::HEADING,
						'condition' => [
							'eael_data_table_content_row_rowspan!' => '',
							'eael_data_table_content_row_type' => 'col'
						]
					],
					[
						'name'		=> 'eael_data_table_content_type',
						'label'		=> esc_html__( 'Content Type', 'essential-addons-elementor' ),
						'type'	=> Controls_Manager::CHOOSE,
						'options'               => [
							'textarea'        => [
								'title'   => esc_html__( 'Textarea', 'essential-addons-elementor' ),
								'icon'    => 'fa fa-text-width',
							],
							'editor'       => [
								'title'   => esc_html__( 'Editor', 'essential-addons-elementor' ),
								'icon'    => 'fa fa-pencil',
							],
							'template'        => [
								'title'   => esc_html__( 'Templates', 'essential-addons-elementor' ),
								'icon'    => 'fa fa-file',
							]
						],
						'default'	=> 'textarea',
						'condition' => [
							'eael_data_table_content_row_type' => 'col'
						]
					],
					[
						'name'	=> 'data_table_content_template_pro_alert',
						'label'	=> esc_html__( 'Templates option available in pro version!', 'essential-addons-elementor' ),
						'type'	=> Controls_Manager::HEADING,
						'condition' => [
							'eael_data_table_content_type' => 'template'
						]
					],
					[
						'name' => 'eael_data_table_content_row_title',
						'label' => esc_html__( 'Cell Text', 'essential-addons-elementor' ),
						'type' => Controls_Manager::TEXTAREA,
						'label_block' => true,
						'default' => esc_html__( 'Content', 'essential-addons-elementor' ),
						'condition' => [
							'eael_data_table_content_row_type' => 'col',
							'eael_data_table_content_type' => 'textarea'
						]
					],
					[
						'name' => 'eael_data_table_content_row_content',
						'label' => esc_html__( 'Cell Text', 'essential-addons-elementor' ),
						'type' => Controls_Manager::WYSIWYG,
						'label_block' => true,
						'default' => esc_html__( 'Content', 'essential-addons-elementor' ),
						'condition' => [
							'eael_data_table_content_row_type' => 'col',
							'eael_data_table_content_type' => 'editor'
						]
					],
					[
						'name' => 'eael_data_table_content_row_title_link',
						'label' => esc_html__( 'Link', 'essential-addons-elementor' ),
						'type' => Controls_Manager::URL,
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
					],
					[
						'name'			=> 'eael_data_table_content_row_css_class',
						'label'			=> esc_html__( 'CSS Class', 'essential-addons-elementor' ),
						'type'			=> Controls_Manager::TEXT,
						'label_block'	=> false,
						'condition' 	=> [
							'eael_data_table_content_row_type' => 'col'
						]
					],
					[
						'name'			=> 'eael_data_table_content_row_css_id',
						'label'			=> esc_html__( 'CSS ID', 'essential-addons-elementor' ),
						'type'			=> Controls_Manager::TEXT,
						'label_block'	=> false,
						'condition' 	=> [
							'eael_data_table_content_row_type' => 'col'
						]
					]
				],
				'title_field' => '{{eael_data_table_content_row_type}}::{{eael_data_table_content_row_title || eael_data_table_content_row_content}}',
			]
		);

  		$this->end_controls_section();

  		/**
		 * -------------------------------------------
		 * Tab Style (Data Table Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_data_table_style_settings',
			[
				'label' => esc_html__( 'General Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
            'table_width',
            [
                'label'                 => __( 'Width', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'size_units'            => [ '%', 'px' ],
                'range'                 => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1200,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-data-table' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'eael_data_table_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-data-table-wrap' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_data_table_container_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-data-table-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_data_table_container_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-data-table-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
				[
					'name' => 'eael_data_table_border',
					'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
					'selector' => '{{WRAPPER}} .eael-data-table-wrap',
				]
		);

		$this->add_control(
			'eael_data_table_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-data-table-wrap' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'eael_data_table_th_padding',
			[
				'label' => esc_html__( 'Table Header Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-data-table thead tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_data_table_td_padding',
			[
				'label' => esc_html__( 'Table Data Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-data-table tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_data_table_shadow',
				'selector' => '{{WRAPPER}} .eael-data-table-wrap',
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
				'label' => esc_html__( 'Header Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);


		$this->add_control(
			'eael_section_data_table_header_radius',
			[
				'label' => esc_html__( 'Header Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-data-table thead tr th:first-child' => 'border-radius: {{SIZE}}px 0px 0px 0px;',
					'{{WRAPPER}} .eael-data-table thead tr th:last-child' => 'border-radius: 0px {{SIZE}}px 0px 0px;',
				],
			]
		);

		$this->start_controls_tabs('eael_data_table_header_title_clrbg');

			$this->start_controls_tab( 'eael_data_table_header_title_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

				$this->add_control(
					'eael_data_table_header_title_color',
					[
						'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#fff',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table thead tr th' => 'color: {{VALUE}};'
						],
					]
				);

				$this->add_control(
					'eael_data_table_header_title_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#4a4893',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table thead tr th' => 'background-color: {{VALUE}};',
						],
					]
				);
				
				$this->add_group_control(
					Group_Control_Border::get_type(),
						[
							'name' => 'eael_data_table_header_border',
							'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
							'selector' => '{{WRAPPER}} .eael-data-table thead tr th',
						]
				);

			$this->end_controls_tab();
			
			$this->start_controls_tab( 'eael_data_table_header_title_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

				$this->add_control(
					'eael_data_table_header_title_hover_color',
					[
						'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#fff',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table thead tr th:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_header_title_hover_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
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
							'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
							'selector' => '{{WRAPPER}} .eael-data-table thead tr th:hover',
						]
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             	'name' => 'eael_data_table_header_title_typography',
				'selector' => '{{WRAPPER}} .eael-data-table thead tr th',
			]
		);

		$this->add_responsive_control(
			'eael_data_table_header_title_alignment',
			[
				'label' => esc_html__( 'Title Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
				'prefix_class' => 'eael-dt-th-align-',
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
				'label' => esc_html__( 'Content Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs('eael_data_table_content_row_cell_styles');

			$this->start_controls_tab('eael_data_table_odd_cell_style', ['label' => esc_html__( 'Normal', 'essential-addons-elementor')]);

				$this->add_control(
					'eael_data_table_content_odd_style_heading',
					[
						'label' => esc_html__( 'ODD Cell', 'essential-addons-elementor' ),
						'type' => Controls_Manager::HEADING,
					]
				);

				$this->add_control(
					'eael_data_table_content_color_odd',
					[
						'label' => esc_html__( 'Color ( Odd Row )', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#6d7882',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n) td' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_content_bg_odd',
					[
						'label' => esc_html__( 'Background ( Odd Row )', 'essential-addons-elementor' ),
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
						'label' => esc_html__( 'Even Cell', 'essential-addons-elementor' ),
						'type' => Controls_Manager::HEADING,
					]
				);

				$this->add_control(
					'eael_data_table_content_even_color',
					[
						'label' => esc_html__( 'Color ( Even Row )', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#6d7882',
						'selectors' => [
							'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n+1) td' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_content_bg_even_color',
					[
						'label' => esc_html__( 'Background Color (Even Row)', 'essential-addons-elementor' ),
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
							'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
							'selector' => '{{WRAPPER}} .eael-data-table tbody tr td',
						]
				);

				$this->add_responsive_control(
					'eael_data_table_each_cell_padding',
					[
						'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em' ],
						'selectors' => [
								 '{{WRAPPER}} .eael-data-table tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						 ],
					]
				);

			$this->end_controls_tab();
			
			$this->start_controls_tab('eael_data_table_odd_cell_hover_style', ['label' => esc_html__( 'Hover', 'essential-addons-elementor')]);

				$this->add_control(
					'eael_data_table_content_hover_color_odd',
					[
						'label' => esc_html__( 'Color ( Odd Row )', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n) td:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_content_hover_bg_odd',
					[
						'label' => esc_html__( 'Background ( Odd Row )', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n) td:hover' => 'background: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'eael_data_table_content_even_hover_style_heading',
					[
						'label' => esc_html__( 'Even Cell', 'essential-addons-elementor' ),
						'type' => Controls_Manager::HEADING,
					]
				);

				$this->add_control(
					'eael_data_table_content_hover_color_even',
					[
						'label' => esc_html__( 'Color ( Even Row )', 'essential-addons-elementor' ),
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
						'label' => esc_html__( 'Background Color (Even Row)', 'essential-addons-elementor' ),
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
				'selector' => '{{WRAPPER}} .eael-data-table tbody tr td',
			]
		);

		$this->add_control(
			'eael_data_table_content_link_typo',
			[
				'label' => esc_html__( 'Link Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		/* Table Content Link */
		$this->start_controls_tabs( 'eael_data_table_link_tabs' );

			// Normal State Tab
			$this->start_controls_tab( 'eael_data_table_link_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

			$this->add_control(
				'eael_data_table_link_normal_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#c15959',
					'selectors' => [
						'{{WRAPPER}} .eael-data-table-wrap table td a' => 'color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();

			// Hover State Tab
			$this->start_controls_tab( 'eael_data_table_link_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

			$this->add_control(
				'eael_data_table_link_hover_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
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
				'label' => esc_html__( 'Content Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
				'prefix_class' => 'eael-dt-td-align-',
			]
		);

		$this->end_controls_section();

	}


	protected function render( ) {

   		$settings = $this->get_settings();

	  	$table_tr = [];
		$table_td = [];

	  	// Storing Data table content values
	  	foreach( $settings['eael_data_table_content_rows'] as $content_row ) {

	  		$row_id = rand(10, 1000);
	  		if( $content_row['eael_data_table_content_row_type'] == 'row' ) {
	  			$table_tr[] = [
	  				'id' => $row_id,
	  				'type' => $content_row['eael_data_table_content_row_type'],
	  			];

	  		}
	  		if( $content_row['eael_data_table_content_row_type'] == 'col' ) {
	  			$target = $content_row['eael_data_table_content_row_title_link']['is_external'] ? 'target="_blank"' : '';
	  			$nofollow = $content_row['eael_data_table_content_row_title_link']['nofollow'] ? 'rel="nofollow"' : '';

	  			$table_tr_keys = array_keys( $table_tr );
				  $last_key = end( $table_tr_keys );
				  
				$tbody_content = ($content_row['eael_data_table_content_type'] == 'editor') ? $content_row['eael_data_table_content_row_content'] : $content_row['eael_data_table_content_row_title'];

	  			$table_td[] = [
	  				'row_id'		=> $table_tr[$last_key]['id'],
	  				'type'			=> $content_row['eael_data_table_content_row_type'],
	  				'content_type'	=> $content_row['eael_data_table_content_type'],
	  				'title'			=> $tbody_content,
	  				'link_url'		=> $content_row['eael_data_table_content_row_title_link']['url'],
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
			'class'	=> 'eael-data-table-wrap',
			'data-table_enabled'	=> $settings['eael_section_data_table_enabled'] ? 'true': 'false',
			'data-table_id'			=> esc_attr($this->get_id())
		]);
		$this->add_render_attribute('eael_data_table', [
			'class'	=> 'tablesorter eael-data-table',
			'id'	=> 'eael-data-table-'.esc_attr($this->get_id())
		]);
	  	?>
		<div <?php echo $this->get_render_attribute_string('eael_data_table_wrap'); ?>>
			<table <?php echo $this->get_render_attribute_string('eael_data_table'); ?>>
			    <thead>
			        <tr class="table-header">
						<?php $i = 0; foreach( $settings['eael_data_table_header_cols_data'] as $header_title ) :
							$this->add_render_attribute('th_class'.$i, [
								'class'	=> [ 'sorting', $header_title['eael_data_table_header_css_class'] ],
								'id'	=> $header_title['eael_data_table_header_css_id']
							]);
						?>
			            <th <?php echo $this->get_render_attribute_string('th_class'.$i); ?>>
							<?php
								if( $header_title['eael_data_table_header_col_icon_enabled'] == 'true' && $header_title['eael_data_table_header_icon_type'] == 'icon' ) :
									$this->add_render_attribute('table_header_col_icon'.$i, [
										'class'	=> [ 'data-header-icon', esc_attr( $header_title['eael_data_table_header_col_icon'] )]
									]);
							?>
			            		<i <?php echo $this->get_render_attribute_string('table_header_col_icon'.$i); ?>></i>
			            	<?php endif; ?>
							<?php echo __( $header_title['eael_data_table_header_col'], 'essential-addons-elementor' ); ?></th>
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
										<?php if( !empty( $table_td[$j]['link_url'] ) && $table_td[$j]['content_type'] == 'textarea' ) : ?>
											<td <?php echo $this->get_render_attribute_string('table_inside_td'.$i.$j); ?>>
												<a href="<?php echo esc_url( $table_td[$j]['link_url'] ); ?>" <?php echo $table_td[$j]['link_target'] ?> <?php echo $table_td[$j]['nofollow'] ?>><?php echo wp_kses_post($table_td[$j]['title']); ?></a>
											</td>
										<?php else: ?>
											<td <?php echo $this->get_render_attribute_string('table_inside_td'.$i.$j); ?>><?php echo $table_td[$j]['title']; ?></td>
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

	protected function content_template() { }
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Data_Table() );
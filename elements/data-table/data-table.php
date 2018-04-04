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
				'label' => esc_html__( 'Only available in pro version!', 'essential-addons-elementor' ),
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
						'name' => 'eael_data_table_header_col_icon_enabled',
						'label' => esc_html__( 'Enable Header Icon', 'essential-addons-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => __( 'yes', 'essential-addons-elementor' ),
						'label_off' => __( 'no', 'essential-addons-elementor' ),
						'default' => 'false',
						'return_value' => 'true',
					],
					[
						'name' => 'eael_data_table_header_col_icon',
						'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
						'type' => Controls_Manager::ICON,
						'default' => '',
						'condition' => [
							'eael_data_table_header_col_icon_enabled' => 'true'
						]
					],
					[
						'name' => 'eael_data_table_header_col_img_enabled',
						'label' => esc_html__( 'Enable Header Image', 'essential-addons-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => __( 'yes', 'essential-addons-elementor' ),
						'label_off' => __( 'no', 'essential-addons-elementor' ),
						'default' => 'false',
						'return_value' => 'true',
					],
					[
						'name' => 'eael_data_table_header_col_img',
						'label' => esc_html__( 'Image', 'essential-addons-elementor' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
						'condition' => [
							'eael_data_table_header_col_img_enabled' => 'true',
						]
					],
					[
						'name' => 'eael_data_table_header_col_img_size',
						'label' => esc_html__( 'Image Size(px)', 'essential-addons-elementor' ),
						'default' => '25',
						'type' => Controls_Manager::TEXT,
						'label_block' => false,
						'condition' => [
							'eael_data_table_header_col_img_enabled' => 'true',
						]
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
						'name' => 'eael_data_table_content_row_title',
						'label' => esc_html__( 'Cell Text', 'essential-addons-elementor' ),
						'type' => Controls_Manager::TEXTAREA,
						'label_block' => false,
						'default' => esc_html__( 'Content', 'essential-addons-elementor' ),
						'condition' => [
							'eael_data_table_content_row_type' => 'col'
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
							'eael_data_table_content_row_type' => 'col'
						]
					]
				],
				'title_field' => '{{eael_data_table_content_row_type}}::{{eael_data_table_content_row_title}}',
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
				'default' => [
					'size' => 7,
				],
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

		$this->add_control(
			'eael_data_table_header_title_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
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
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4a4893',
				'selectors' => [
					'{{WRAPPER}} .eael-data-table thead tr th' => 'background-color: {{VALUE}};',
				],
			]
		);

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
			'eael_data_table_content_bg_odd_color',
			[
				'label' => esc_html__( 'Background Color (Odd Row)', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-data-table tbody > tr:nth-child(2n) td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_data_table_content_color_even',
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

		$this->start_controls_section(
			'eael_section_pro',
			[
				'label' => __( 'Go Premium for More Features', 'essential-addons-elementor' )
			]
		);

        $this->add_control(
            'eael_control_get_pro',
            [
                'label' => __( 'Unlock more possibilities', 'essential-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'1' => [
						'title' => __( '', 'essential-addons-elementor' ),
						'icon' => 'fa fa-unlock-alt',
					],
				],
				'default' => '1',
                'description' => '<span class="pro-feature"> Get the  <a href="https://essential-addons.com/elementor/buy.php" target="_blank">Pro version</a> for more stunning elements and customization options.</span>'
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

	  			$table_td[] = [
	  				'row_id' => $table_tr[$last_key]['id'],
	  				'type' => $content_row['eael_data_table_content_row_type'],
	  				'title' => $content_row['eael_data_table_content_row_title'],
	  				'link_url' => $content_row['eael_data_table_content_row_title_link']['url'],
	  				'link_target' => $target,
	  				'nofollow' => $nofollow
	  			];
	  		}
	  	}

	  	$table_th_count = count($settings['eael_data_table_header_cols_data']);
	  	?>
		<div class="eael-data-table-wrap">
			<table id="eael-data-table-<?php echo $this->get_id(); ?>" class="tablesorter eael-data-table">
			    <thead>
			        <tr class="table-header">
			        	<?php foreach( $settings['eael_data_table_header_cols_data'] as $header_title ) : ?>
			            <th class="sorting">
			            	<?php if( $header_title['eael_data_table_header_col_icon_enabled'] == 'true' ) : ?>
			            		<i class="data-header-icon <?php echo esc_attr( $header_title['eael_data_table_header_col_icon'] ); ?>"></i>
			            	<?php endif; ?>
			            	<?php if( $header_title['eael_data_table_header_col_img_enabled'] == 'true' ) : ?>
			            		<img src="<?php echo esc_url( $header_title['eael_data_table_header_col_img']['url'] ) ?>" class="eael-data-table-th-img" style="width:<?php echo $header_title['eael_data_table_header_col_img_size'] ?>px" alt="<?php echo esc_attr( $header_title['eael_data_table_header_col'] ); ?>">
			            	<?php endif; ?>
			            	<?php echo esc_html__( $header_title['eael_data_table_header_col'], 'essential-addons-elementor' ); ?>
			            </th>
			        	<?php endforeach; ?>
			        </tr>
			    </thead>
			  	<tbody>
					<?php for( $i = 0; $i < count( $table_tr ); $i++ ) : ?>
						<tr>
							<?php
								for( $j = 0; $j < count( $table_td ); $j++ ) {
									if( $table_tr[$i]['id'] == $table_td[$j]['row_id'] ) {
										?>
										<?php if( !empty( $table_td[$j]['link_url'] ) ) : ?>
											<td>
												<a href="<?php echo esc_attr( $table_td[$j]['link_url'] ); ?>" <?php echo $table_td[$j]['link_target'] ?> <?php echo $table_td[$j]['nofollow'] ?>><?php echo $table_td[$j]['title']; ?></a>
											</td>
										<?php else: ?>
											<td><?php echo $table_td[$j]['title']; ?></td>
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

	protected function content_template() {

		?>


		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Data_Table() );
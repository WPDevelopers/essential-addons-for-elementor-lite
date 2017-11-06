<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_Eael_Post_Grid extends Widget_Base {

	public function get_name() {
		return 'eael-post-grid';
	}

	public function get_title() {
		return __( 'EA Post Grid', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'eael_section_post_grid_filters',
			[
				'label' => __( 'Post Settings', 'essential-addons-elementor' )
			]
		);


		$this->add_control(
            'eael_post_type',
            [
                'label' => __( 'Post Type', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => eael_get_post_types(),
                'default' => 'post',

            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __( 'Categories', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => eael_post_type_categories(),
                'condition' => [
                       'eael_post_type' => 'post'
                ]
            ]
        );


        $this->add_control(
            'eael_posts_count',
            [
                'label' => __( 'Number of Posts', 'essential-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '4'
            ]
        );


        $this->add_control(
            'eael_post_offset',
            [
                'label' => __( 'Post Offset', 'essential-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '0'
            ]
        );

        $this->add_control(
            'eael_post_orderby',
            [
                'label' => __( 'Order By', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => eael_get_post_orderby_options(),
                'default' => 'date',

            ]
        );

        $this->add_control(
            'eael_post_order',
            [
                'label' => __( 'Order', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending'
                ],
                'default' => 'desc',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'eael_section_post_grid_layout',
			[
				'label' => __( 'Layout Settings', 'essential-addons-elementor' )
			]
		);

		$this->add_control(
			'eael_post_grid_columns',
			[
				'label' => esc_html__( 'Number of Columns', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'eael-col-4',
				'options' => [
					'eael-col-1' => esc_html__( 'Single Column', 'essential-addons-elementor' ),
					'eael-col-2' => esc_html__( 'Two Columns',   'essential-addons-elementor' ),
					'eael-col-3' => esc_html__( 'Three Columns', 'essential-addons-elementor' ),
					'eael-col-4' => esc_html__( 'Four Columns',  'essential-addons-elementor' ),
					'eael-col-5' => esc_html__( 'Five Columns',  'essential-addons-elementor' ),
					'eael-col-6' => esc_html__( 'Six Columns',   'essential-addons-elementor' ),
				],
			]
		);

		$this->add_control(
            'eael_post_grid_show_load_more',
            [
                'label' => __( 'Show Load More', 'essential-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'1' => [
						'title' => __( 'Yes', 'essential-addons-elementor' ),
						'icon' => 'fa fa-check',
					],
					'0' => [
						'title' => __( 'No', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					]
				],
				'default' => '0'
            ]
        );

        $this->add_control(
			'eael_post_grid_show_load_more_text',
			[
				'label' => esc_html__( 'Label Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( 'Load More', 'essential-addons-elementor' ),
				'condition' => [
					'eael_post_grid_show_load_more' => '1',
				]
			]
		);

        $this->add_control(
            'eael_show_image',
            [
                'label' => __( 'Show Image', 'essential-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'1' => [
						'title' => __( 'Yes', 'essential-addons-elementor' ),
						'icon' => 'fa fa-check',
					],
					'0' => [
						'title' => __( 'No', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					]
				],
				'default' => '1'
            ]
        );
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image',
				'exclude' => [ 'custom' ],
				'default' => 'medium',
				'condition' => [
                    'eael_show_image' => '1',
                ]
			]
		);


		$this->add_control(
            'eael_show_title',
            [
                'label' => __( 'Show Title', 'essential-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'1' => [
						'title' => __( 'Yes', 'essential-addons-elementor' ),
						'icon' => 'fa fa-check',
					],
					'0' => [
						'title' => __( 'No', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					]
				],
				'default' => '1'
            ]
        );

		$this->add_control(
            'eael_show_excerpt',
            [
                'label' => __( 'Show excerpt', 'essential-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'1' => [
						'title' => __( 'Yes', 'essential-addons-elementor' ),
						'icon' => 'fa fa-check',
					],
					'0' => [
						'title' => __( 'No', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					]
				],
				'default' => '1'
            ]
        );


        $this->add_control(
            'eael_excerpt_length',
            [
                'label' => __( 'Excerpt Words', 'essential-addons-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'default' => '10',
                'condition' => [
                    'eael_show_excerpt' => '1',
                ],
                'description' => '<span class="pro-feature"> Pro Feature. Get <a href="https://essential-addons.com/elementor/buy.php" target="_blank">Pro version</a> </span>'

            ]
        );


		$this->add_control(
            'eael_show_meta',
            [
                'label' => __( 'Show Meta', 'essential-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'1' => [
						'title' => __( 'Yes', 'essential-addons-elementor' ),
						'icon' => 'fa fa-check',
					],
					'0' => [
						'title' => __( 'No', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					]
				],
				'default' => '1'
            ]
        );

		$this->add_control(
			'eael_post_grid_meta_position',
			[
				'label' => esc_html__( 'Meta Position', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'meta-entry-footer',
				'options' => [
					'meta-entry-header' => esc_html__( 'Entry Header', 'essential-addons-elementor' ),
					'meta-entry-footer' => esc_html__( 'Entry Footer',   'essential-addons-elementor' ),
				],
                'condition' => [
                    'eael_show_meta' => '1',
                ]
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

        $this->start_controls_section(
            'eael_section_post_grid_style',
            [
                'label' => __( 'Post Grid Style', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
			'eael_post_grid_bg_color',
			[
				'label' => __( 'Post Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-grid-post-holder' => 'background-color: {{VALUE}}',
				]

			]
		);

        $this->add_control(
			'eael_thumbnail_overlay_color',
			[
				'label' => __( 'Thumbnail Overlay Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0, .75)',
				'selectors' => [
					'{{WRAPPER}} .eael-entry-overlay' => 'background-color: {{VALUE}}',
				]

			]
		);

		$this->add_responsive_control(
			'eael_post_grid_spacing',
			[
				'label' => esc_html__( 'Spacing Between Items', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-grid-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_post_grid_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-grid-post-holder',
			]
		);

		$this->add_control(
			'eael_post_grid_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eael-grid-post-holder' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_post_grid_box_shadow',
				'selector' => '{{WRAPPER}} .eael-grid-post-holder',
			]
		);


		$this->end_controls_section();

        $this->start_controls_section(
            'eael_section_typography',
            [
                'label' => __( 'Color & Typography', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

		$this->add_control(
			'eael_post_grid_title_style',
			[
				'label' => __( 'Title Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'eael_post_grid_title_color',
			[
				'label' => __( 'Title Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#303133',
				'selectors' => [
					'{{WRAPPER}} .eael-entry-title, {{WRAPPER}} .eael-entry-title a' => 'color: {{VALUE}};',
				]

			]
		);

        $this->add_control(
			'eael_post_grid_title_hover_color',
			[
				'label' => __( 'Title Hover Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#23527c',
				'selectors' => [
					'{{WRAPPER}} .eael-entry-title:hover, {{WRAPPER}} .eael-entry-title a:hover' => 'color: {{VALUE}};',
				]

			]
		);

		$this->add_responsive_control(
			'eael_post_grid_title_alignment',
			[
				'label' => __( 'Title Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .eael-entry-title' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_grid_title_typography',
				'label' => __( 'Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-entry-title',
			]
		);

		$this->add_control(
			'eael_post_grid_excerpt_style',
			[
				'label' => __( 'Excerpt Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'eael_post_grid_excerpt_color',
			[
				'label' => __( 'Excerpt Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '',
				'selectors' => [
					'{{WRAPPER}} .eael-grid-post-excerpt p' => 'color: {{VALUE}};',
				]
			]
		);

        $this->add_responsive_control(
			'eael_post_grid_excerpt_alignment',
			[
				'label' => __( 'Excerpt Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-grid-post-excerpt p' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_grid_excerpt_typography',
				'label' => __( 'excerpt Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .eael-grid-post-excerpt p',
			]
		);


		$this->add_control(
			'eael_post_grid_meta_style',
			[
				'label' => __( 'Meta Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'eael_post_grid_meta_color',
			[
				'label' => __( 'Meta Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '',
				'selectors' => [
					'{{WRAPPER}} .eael-entry-meta, .eael-entry-meta a' => 'color: {{VALUE}};',
				]
			]
		);

        $this->add_responsive_control(
			'eael_post_grid_meta_alignment',
			[
				'label' => __( 'Meta Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'flex-end' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
					'stretch' => [
						'title' => __( 'Justified', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-entry-footer' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_post_grid_meta_typography',
				'label' => __( 'Excerpt Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .eael-entry-meta > div',
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
            'eael_section_load_more_btn',
            [
                'label' => __( 'Load More Button Style', 'essential-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                	'eael_post_grid_show_load_more' => '1'
                ]
            ]
        );

		$this->add_responsive_control(
			'eael_post_grid_load_more_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_post_grid_load_more_btn_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-load-more-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
	         'name' => 'eael_post_grid_load_more_btn_typography',
				'selector' => '{{WRAPPER}} .eael-load-more-button',
			]
		);

		$this->start_controls_tabs( 'eael_post_grid_load_more_btn_tabs' );

			// Normal State Tab
			$this->start_controls_tab( 'eael_post_grid_load_more_btn_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

			$this->add_control(
				'eael_post_grid_load_more_btn_normal_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#fff',
					'selectors' => [
						'{{WRAPPER}} .eael-load-more-button' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_cta_btn_normal_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#29d8d8',
					'selectors' => [
						'{{WRAPPER}} .eael-load-more-button' => 'background: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'eael_post_grid_load_more_btn_normal_border',
					'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
					'selector' => '{{WRAPPER}} .eael-load-more-button',
				]
			);

			$this->add_control(
				'eael_post_grid_load_more_btn_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .eael-load-more-button' => 'border-radius: {{SIZE}}px;',
					],
				]
			);

			$this->end_controls_tab();

			// Hover State Tab
			$this->start_controls_tab( 'eael_post_grid_load_more_btn_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

			$this->add_control(
				'eael_post_grid_load_more_btn_hover_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#fff',
					'selectors' => [
						'{{WRAPPER}} .eael-load-more-button:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_post_grid_load_more_btn_hover_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '27bdbd',
					'selectors' => [
						'{{WRAPPER}} .eael-load-more-button:hover' => 'background: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_post_grid_load_more_btn_hover_border_color',
				[
					'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .eael-load-more-button:hover' => 'border-color: {{VALUE}};',
					],
				]

			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_post_grid_load_more_btn_shadow',
				'selector' => '{{WRAPPER}} .eael-load-more-button',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_post_grid_load_more_loader_pos_title',
			[
				'label' => esc_html__( 'Loader Position', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_post_grid_loader_pos_left',
			[
				'label' => esc_html__( 'From Left', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-load-more-button.button--loading .button__loader' => 'left: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_post_grid_loader_pos_top',
			[
				'label' => esc_html__( 'From Top', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-load-more-button.button--loading .button__loader' => 'top: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();

	}


	protected function render( ) {
        $settings = $this->get_settings();

        $post_args = eael_get_post_settings($settings);

        $posts = eael_get_post_data($post_args);

        /* Get Post Categories */
        $post_categories = $this->get_settings( 'category' );
        if( !empty( $post_categories ) ) {
        	foreach ( $post_categories as $key=>$value ) {
	        	$categories[] = $value;
	        }
	        $categories_id_string = implode( ',' , $categories );

	        /* Get All Post Count */
	        $total_post = 0;
	        foreach( $categories as $cat ) {
	        	$category = get_category( $cat );
	        	$total_post = $total_post + $category->category_count;
	        }
        }else {
        	$categories_id_string = '';
        	$total_post = wp_count_posts( $settings['eael_post_type'] )->publish;
        }

        ?>

		<div id="eael-post-grid-<?php echo esc_attr($this->get_id()); ?>" class="eael-post-grid-container <?php echo esc_attr($settings['eael_post_grid_columns'] ); ?>">
		    <div class="eael-post-grid eael-post-appender-<?php echo esc_attr( $this->get_id() ); ?>">
		    <?php
		        if(count($posts)){
		            global $post;
		            ?>
		                <?php
		                    foreach($posts as $post){
		                        setup_postdata($post);
		                    ?>


		                    <article class="eael-grid-post eael-post-grid-column">
		                    	<div class="eael-grid-post-holder">
			                    	<div class="eael-grid-post-holder-inner">

			                    		<?php if ($thumbnail_exists = has_post_thumbnail()): ?>
			                    		<div class="eael-entry-media">
			                    			<div class="eael-entry-overlay">
			                    				<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
			                    				<a href="<?php echo get_permalink(); ?>"></a>
			                    			</div>
				                    		<div class="eael-entry-thumbnail">
				                    			<?php if($settings['eael_show_image'] == 1){ ?>
				                    			<img src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id(), $settings['image_size'])?>">
				                    			<?php } ?>
				                    		</div>
			                    		</div>
			                    		<?php endif; ?>

			                    		<div class="eael-entry-wrapper">
			                    			<header class="eael-entry-header">
			                    				<?php if($settings['eael_show_title']){ ?>
			                    				<h2 class="eael-entry-title"><a class="eael-grid-post-link" href="<?php echo get_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			                    				<?php } ?>

			                    				<?php if($settings['eael_show_meta'] && $settings['eael_post_grid_meta_position'] == 'meta-entry-header'){ ?>
				                    			<div class="eael-entry-meta">
				                    				<span class="eael-posted-by"><?php the_author_posts_link(); ?></span>
				                    				<span class="eael-posted-on"><time datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time></span>
				                    			</div>
				                    			<?php } ?>
			                    			</header>

			                    			<div class="eael-entry-content">
					                            <?php if($settings['eael_show_excerpt']){ ?>
					                            <div class="eael-grid-post-excerpt">
					                                <p><?php echo  eael_get_excerpt_by_id(get_the_ID(),$settings['eael_excerpt_length']);?></p>
					                            </div>
					                            <?php } ?>
			                    			</div>
			                    		</div>

			                    		<?php if($settings['eael_show_meta'] && $settings['eael_post_grid_meta_position'] == 'meta-entry-footer'){ ?>
			                    		<div class="eael-entry-footer">
			                    			<div class="eael-author-avatar">
			                    				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 96 ); ?> </a>
			                    			</div>
			                    			<div class="eael-entry-meta">
			                    				<div class="eael-posted-by"><?php the_author_posts_link(); ?></div>
			                    				<div class="eael-posted-on"><time datetime="<?php echo get_the_date(); ?>"><?php echo get_the_date(); ?></time></div>
			                    			</div>
			                    		</div>
			                    		<?php } ?>
			                    	</div>
		                    	</div>
		                    </article>
		                    <?php
		                    }
		                    wp_reset_postdata();
		                ?>
		            <?php
		        }
		    ?>
		    </div>
		    <div class="clearfix"></div>
		</div>
		<?php if( 1 == $settings['eael_post_grid_show_load_more'] ) : ?>
		<!-- Load More Button -->
		<div class="eael-load-more-button-wrap">
			<button class="eael-load-more-button" id="eael-load-more-btn-<?php echo $this->get_id(); ?>">
				<div class="eael-btn-loader button__loader"></div>
		  		<span><?php echo esc_html__( $settings['eael_post_grid_show_load_more_text'], 'essential-addons-elementor' ); ?></span>
			</button>
		</div>
		<?php endif; ?>
<!-- Loading Lode More Js -->
<script>
jQuery(document).ready(function($) {

	'use strict';
	var options = {
		siteUrl: '<?php echo home_url( '/' ); ?>',
		totalPosts: <?php echo $total_post; ?>,
		loadMoreBtn: $( '#eael-load-more-btn-<?php echo $this->get_id(); ?>' ),
		postContainer: $( '.eael-post-appender-<?php echo esc_attr( $this->get_id() ); ?>' ),
		postStyle: 'grid',
	}

	var settings = {
		postType: '<?php echo $settings['eael_post_type']; ?>',
		perPage: parseInt( <?php echo $settings['eael_posts_count'] ?>, 10 ),
		postOrder: '<?php echo $settings['eael_post_order'] ?>',
		showImage: <?php echo $settings['eael_show_image']; ?>,
		showTitle: <?php echo $settings['eael_show_title']; ?>,
		showExcerpt: <?php echo $settings['eael_show_excerpt']; ?>,
		showMeta: <?php echo $settings['eael_show_meta']; ?>,
		metaPosition: '<?php echo $settings['eael_post_grid_meta_position']; ?>',
		excerptLength: parseInt( <?php echo $settings['eael_excerpt_length']; ?>, 10 ),
		btnText: '<?php echo $settings['eael_post_grid_show_load_more_text']; ?>',
		categories: '<?php echo $categories_id_string; ?>',
	}

	loadMore( options, settings );

	// Load Masonry Js
  	$(window).load(function(){
    	$('.eael-post-grid').masonry({
      		itemSelector: '.eael-grid-post',
      		percentPosition: true,
      		columnWidth: '.eael-post-grid-column'
    	});
	});

});

</script>

        <?php
	}

	protected function content_template() {
		?>

		<?php
	}
}
Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Post_Grid() );
<?php 
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Widget_Eael_Pricing_Table extends Widget_Base {

	public function get_name() {
		return 'eael-pricing-table';
	}

	public function get_title() {
		return esc_html__( 'EA Pricing Table', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
	
	protected function _register_controls() {

  		/**
  		 * Pricing Table Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_pricing_table_settings',
  			[
  				'label' => esc_html__( 'Pricing Table Settings', 'essential-addons-elementor' )
  			]
  		);

  		$this->add_control(
		  'eael_pricing_table_style',
		  	[
		   	'label'       	=> esc_html__( 'Pricing Style', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'style-1',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'style-1'  	=> esc_html__( 'Default', 'essential-addons-elementor' ),
		     		'style-2' 	=> esc_html__( 'Pricing Style 2', 'essential-addons-elementor' ),
		     		'style-3' 	=> esc_html__( 'Pricing Style 3', 'essential-addons-elementor' ),
		     		'style-4' 	=> esc_html__( 'Pricing Style 4', 'essential-addons-elementor' ),
		     	],
		  	]
		);

		$this->add_control( 
			'eael_pricing_table_price_cur',
			[
				'label' => esc_html__( 'Price Currency', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( '$', 'essential-addons-elementor' ),
				'selectors' => [
            	'{{WRAPPER}} .eael-pricing .eael-pricing-item .price-tag:before ' => 'content: "{{VALUE}}";',
        		],
			]
		);

		$this->add_control( 
			'eael_pricing_table_price',
			[
				'label' => esc_html__( 'Price', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( '99', 'essential-addons-elementor' )
			]
		);

		/**
		 * Condition: 'eael_pricing_table_style' => 'style-3'
		 */
		$this->add_control(
		  'eael_pricing_table_style_3_price_position',
		  	[
		   	'label'       	=> esc_html__( 'Pricing Position', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'bottom',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'top'  		=> esc_html__( 'On Top', 'essential-addons-elementor' ),
		     		'bottom' 	=> esc_html__( 'At Bottom', 'essential-addons-elementor' ),
		     	],
		     	'condition' => [
		     		'eael_pricing_table_style' => 'style-3'
		     	]
		  	]
		);

		$this->add_control( 
			'eael_pricing_table_price_period',
			[
				'label' => esc_html__( 'Price Period (per)', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( 'month', 'essential-addons-elementor' )
			]
		);

		$this->add_control( 
			'eael_pricing_table_title',
			[
				'label' => esc_html__( 'Title', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( 'Startup', 'essential-addons-elementor' )
			]
		);

		/**
		 * Condition: 'eael_pricing_table_style' => 'style-2'
		 */
		$this->add_control( 
			'eael_pricing_table_sub_title',
			[
				'label' => esc_html__( 'Sub Title', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( 'A tagline here.', 'essential-addons-elementor' ),
				'condition' => [
					'eael_pricing_table_style' => [ 'style-2', 'style-3', 'style-4' ]
				]
			]
		);

		/**
		 * Condition: 'eael_pricing_table_style' => 'style-2'
		 */
		$this->add_control(
			'eael_pricing_table_style_2_icon',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-home',
				'condition' => [
					'eael_pricing_table_style' => 'style-2'
				]
			]
		);

		/**
		 * Condition: 'eael_pricing_table_style' => 'style-4'
		 */
		$this->add_control(
			'eael_pricing_table_style_4_image',
			[
				'label' => esc_html__( 'Header Image', 'essential-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'selectors' => [
					'{{WRAPPER}} .eael-pricing-image' => 'background-image: url({{URL}});',
				],
				'condition' => [
					'eael_pricing_table_style' => 'style-4'
				]
			]
		);

		/**
		 * Condition: 'eael_pricing_table_style' => [ 'style-3', 'style-4' ], 'eael_pricing_table_featured' => 'yes'
		 */
		$this->add_control(
			'eael_pricing_table_icon_enabled',
			[
				'label' => esc_html__( 'List Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'show',
				'default' => 'show',
			]
		);

		$this->add_control(
			'eael_pricing_table_featured',
			[
				'label' => esc_html__( 'Featured?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		/**
		 * Condition: 'eael_pricing_table_style' => [ 'style-3', 'style-4' ], 'eael_pricing_table_featured' => 'yes'
		 */
		$this->add_control( 
			'eael_pricing_table_featured_tag_text',
			[
				'label' => esc_html__( 'Featured Tag Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( 'Featured', 'essential-addons-elementor' ),
				'selectors' => [
					'{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.featured:before' => 'content: "{{VALUE}}";',
					'{{WRAPPER}} .eael-pricing.style-4 .eael-pricing-item.featured:before' => 'content: "{{VALUE}}";',
				],
				'condition' => [
					'eael_pricing_table_style' => [ 'style-3', 'style-4' ],
					'eael_pricing_table_featured' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'eael_pricing_table_content_alignment',
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
				],
				'default' => 'center',
				'prefix_class' => 'eael-pricing-content-align-',
			]
		);

		$this->add_control(
			'eael_pricing_table_items',
			[
				'type' => Controls_Manager::REPEATER,
				'seperator' => 'before',
				'default' => [
					[ 'eael_pricing_table_item' => 'Unlimited calls' ],
					[ 'eael_pricing_table_item' => 'Free hosting' ],
					[ 'eael_pricing_table_item' => '500 MB of storage space' ],
					[ 'eael_pricing_table_item' => '500 MB Bandwidth' ],
					[ 'eael_pricing_table_item' => '24/7 support' ]
				],
				'fields' => [
					[
						'name' => 'eael_pricing_table_item',
						'label' => esc_html__( 'List Item', 'essential-addons-elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => esc_html__( 'Pricing table list item', 'essential-addons-elementor' )
					],
					[
						'name' => 'eael_pricing_table_list_icon',
						'label' => esc_html__( 'List Icon', 'essential-addons-elementor' ),
						'type' => Controls_Manager::ICON,
						'label_block' => false,
						'default' => 'fa fa-check',
						'include' => [
							'fa fa-check',
							'fa fa-check-circle',
							'fa fa-check-circle-o',
							'fa fa-check-square',
							'fa fa-check-square-o',
							'fa fa-minus',
							'fa fa-minus-circle',
							'fa fa-minus-square',
							'fa fa-minus-square-o',
							'fa fa-window-close',
							'fa fa-window-close-o',
							'fa fa-times',
							'fa fa-times-circle',
							'fa fa-times-circle-o',
							'fa fa-user-times',
						],
					],
					[
						'name' => 'eael_pricing_table_icon_mood',
						'label' => esc_html__( 'Item Active?', 'essential-addons-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'return_value' => 'yes',
						'default' => 'yes',
					],
				],
				'title_field' => '{{eael_pricing_table_item}}',
			]
		);

		$this->add_control( 
			'eael_pricing_table_btn',
			[
				'label' => esc_html__( 'Button Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'separator' => 'before',
				'default' => esc_html__( 'Choose Plan', 'essential-addons-elementor' )
			]
		);

		$this->add_control( 
			'eael_pricing_table_btn_link',
			[
				'label' => esc_html__( 'Button Link', 'essential-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'default' => [
        			'url' => 'http://',
        			'is_external' => '',
     			],
     			'show_external' => true,
			]
		);
		
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Pricing Table Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_pricing_table_style_settings',
			[
				'label' => esc_html__( 'Pricing Table Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_pricing_table_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-pricing .eael-pricing-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_pricing_table_container_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-pricing .eael-pricing-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_pricing_table_container_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-pricing .eael-pricing-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_pricing_table_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-pricing .eael-pricing-item',
			]
		);

		$this->add_control(
			'eael_pricing_table_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 4,
				],
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-pricing .eael-pricing-item' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_pricing_table_shadow',
				'selector' => '{{WRAPPER}} .eael-pricing .eael-pricing-item',
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Pricing Table Title Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_pricing_table_title_style_settings',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_pricing_table_title_heading',
			[
				'label' => esc_html__( 'Title Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_pricing_table_title_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-pricing-item .header .title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item:hover .header:after' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_pricing_table_title_typography',
				'selector' => '{{WRAPPER}} .eael-pricing-item .header .title',
			]
		);

		$this->add_control(
			'eael_pricing_table_price_tag_heading',
			[
				'label' => esc_html__( 'Price Tag Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' =>  'before'
			]
		);

		$this->add_control(
			'eael_pricing_table_pricing_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-pricing-item .price-tag' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            'name' => 'eael_pricing_table_price_tag_typography',
				'selector' => '{{WRAPPER}} .eael-pricing-item .price-tag',
			]
		);

		$this->add_control(
			'eael_pricing_table_pricing_period_heading',
			[
				'label' => esc_html__( 'Pricing Period Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_pricing_table_pricing_period_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-pricing-item .price-period' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            'name' => 'eael_pricing_table_price_preiod_typography',
				'selector' => '{{WRAPPER}} .eael-pricing-item .price-period',
			]
		);

		$this->add_control(
			'eael_pricing_table_price_list_heading',
			[
				'label' => esc_html__( 'Price List Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' =>  'before'
			]
		);

		$this->add_control(
			'eael_pricing_table_list_item_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-pricing-item .body ul li' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            'name' => 'eael_pricing_table_list_item_typography',
				'selector' => '{{WRAPPER}} .eael-pricing-item .body ul li',
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Pricing Table Featured Tag Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_pricing_table_style_3_featured_tag_settings',
			[
				'label' => esc_html__( 'Featured Tag Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_pricing_table_style' => [ 'style-3' ]
				]
			]
		);

		$this->add_control(
			'eael_pricing_table_featured_tag_font_size',
			[
				'label' => esc_html__( 'Font Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10
				],
				'range' => [
					'px' => [
						'max' => 18,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.featured:before' => 'font-size: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_pricing_table_featured_tag_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.featured:before' => 'background: {{VALUE}};',
					'{{WRAPPER}} .eael-pricing.style-3 .eael-pricing-item.featured:after' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Pricing Table Icon Style)
		 * Condition: 'eael_pricing_table_style' => 'style-2'
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_pricing_table_icon_settings',
			[
				'label' => esc_html__( 'Icon Settings', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_pricing_table_style' => 'style-2'
				]
			]
		);

		$this->add_control(
				'eael_pricing_table_icon_settings',
				[
					'label' => esc_html__( 'Icon Size', 'essential-addons-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' => 30
					],
					'range' => [
						'px' => [
							'max' => 60,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .eael-pricing.style-2 .eael-pricing-item .eael-pricing-icon .icon i' => 'font-size: {{SIZE}}px;',
					],
				]
			);

		$this->add_control(
			'eael_pricing_table_style_2_icon_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-pricing-item .eael-pricing-icon .icon i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_pricing_table_style_2_icon_reveal_color',
			[
				'label' => esc_html__( 'Reveal Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-pricing-item .eael-pricing-icon .icon i.fa-reveal' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Buttont Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_pricing_table_btn_style_settings',
			[
				'label' => esc_html__( 'Button Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'eael_pricing_table_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-pricing .eael-pricing-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_pricing_table_btn_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-pricing .eael-pricing-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
	         'name' => 'eael_pricing_table_btn_typography',
				'selector' => '{{WRAPPER}} .eael-pricing .eael-pricing-button',
			]
		);

		$this->start_controls_tabs( 'eael_cta_button_tabs' );

			// Normal State Tab
			$this->start_controls_tab( 'eael_pricing_table_btn_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

			$this->add_control(
				'eael_pricing_table_btn_normal_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#fff',
					'selectors' => [
						'{{WRAPPER}} .eael-pricing .eael-pricing-button' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_pricing_table_btn_normal_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#00C853',
					'selectors' => [
						'{{WRAPPER}} .eael-pricing .eael-pricing-button' => 'background: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
			Group_Control_Border::get_type(),
				[
					'name' => 'eael_pricing_table_btn_border',
					'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
					'selector' => '{{WRAPPER}} .eael-pricing .eael-pricing-button',
				]
			);

			$this->add_control(
				'eael_pricing_table_btn_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .eael-pricing .eael-pricing-button' => 'border-radius: {{SIZE}}px;',
					],
				]
			);

			$this->end_controls_tab();

			// Hover State Tab
			$this->start_controls_tab( 'eael_pricing_table_btn_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

			$this->add_control(
				'eael_pricing_table_btn_hover_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#f9f9f9',
					'selectors' => [
						'{{WRAPPER}} .eael-pricing .eael-pricing-button:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_pricing_table_btn_hover_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#03b048',
					'selectors' => [
						'{{WRAPPER}} .eael-pricing .eael-pricing-button:hover' => 'background: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_pricing_table_btn_hover_border_color',
				[
					'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .eael-pricing .eael-pricing-button:hover' => 'border-color: {{VALUE}};',
					],
				]

			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_cta_button_shadow',
				'selector' => '{{WRAPPER}} .eael-pricing .eael-pricing-button',
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

	}


	protected function render( ) {
		
   	$settings = $this->get_settings();
      $pricing_table_image = $this->get_settings( 'eael_pricing_table_image' );
	  	$pricing_table_image_url = Group_Control_Image_Size::get_attachment_image_src( $pricing_table_image['id'], 'thumbnail', $settings );	
		$target = $settings['eael_pricing_table_btn_link']['url'] ? 'target="_blank"' : '';
		if( 'yes' === $settings['eael_pricing_table_featured'] ) : $featured_class = 'featured'; else : $featured_class = ''; endif;
	?>
	<?php if( 'style-1' === $settings['eael_pricing_table_style'] ) : ?>
	<div class="eael-pricing style-1">
	    <div class="eael-pricing-item <?php echo esc_attr( $featured_class ); ?>">
	        <div class="header">
	            <h2 class="title"><?php echo $settings['eael_pricing_table_title']; ?></h2>
	        </div>
	        <div class="eael-pricing-tag">
	            <span class="price-tag"><?php echo $settings['eael_pricing_table_price'] ?></span> <span class="price-period">/ <?php echo $settings['eael_pricing_table_price_period']; ?></span>
	        </div>
	        <div class="body">
	            <ul>
	            	<?php 
	            		foreach( $settings['eael_pricing_table_items'] as $item ) : 
	            		if( 'yes' === $item['eael_pricing_table_icon_mood'] ) : $icon_mood = ''; else : $icon_mood = 'disable-item'; endif;
	            	?>
	                	<li class="<?php echo esc_attr( $icon_mood ); ?>">
	                		<?php if( 'show' === $settings['eael_pricing_table_icon_enabled'] ) : ?>
	                		<span class="li-icon"><i class="<?php echo esc_attr( $item['eael_pricing_table_list_icon'] ); ?>"></i></span> 
	                		<?php endif; ?>
	                		<?php echo $item['eael_pricing_table_item']; ?>
	                	</li>
	               <?php endforeach; ?> 
	            </ul>
	        </div>
	        <div class="footer">
	            <a href="<?php echo esc_url( $settings['eael_pricing_table_btn_link']['url'] ); ?>" <?php echo $target; ?> class="eael-pricing-button"><?php echo $settings['eael_pricing_table_btn']; ?></a>
	        </div>
	    </div>
	</div>
	<?php elseif( 'style-2' === $settings['eael_pricing_table_style'] ) : ?>
	<div class="eael-pricing style-2">
	    <div class="eael-pricing-item <?php echo esc_attr( $featured_class ); ?>">
	        <div class="eael-pricing-icon">
	            <span class="icon"><i class="<?php echo esc_attr( $settings['eael_pricing_table_style_2_icon'] ); ?>"></i> <i class="<?php echo esc_attr( $settings['eael_pricing_table_style_2_icon'] ); ?> fa-reveal"></i></span>
	        </div>
	        <div class="header">
	            <h2 class="title"><?php echo $settings['eael_pricing_table_title']; ?></h2>
	            <span class="subtitle"><?php echo $settings['eael_pricing_table_sub_title']; ?></span>
	        </div>
	        <div class="eael-pricing-tag">
	            <span class="price-tag"><?php echo $settings['eael_pricing_table_price'] ?></span> <span class="price-period">/ <?php echo $settings['eael_pricing_table_price_period']; ?></span>
	        </div>
	        <div class="body">
	            <ul>
	            	<?php 
	            		foreach( $settings['eael_pricing_table_items'] as $item ) : 
	            		if( 'yes' === $item['eael_pricing_table_icon_mood'] ) : $icon_mood = ''; else : $icon_mood = 'disable-item'; endif;
	            	?>
	                	<li class="<?php echo esc_attr( $icon_mood ); ?>">
	                		<?php if( 'show' === $settings['eael_pricing_table_icon_enabled'] ) : ?>
	                		<span class="li-icon"><i class="<?php echo esc_attr( $item['eael_pricing_table_list_icon'] ); ?>"></i></span> 
	                		<?php endif; ?>
	                		<?php echo $item['eael_pricing_table_item']; ?>
	                	</li>
	               <?php endforeach; ?> 
	            </ul>
	        </div>
	        <div class="footer">
	            <a href="<?php echo esc_url( $settings['eael_pricing_table_btn_link']['url'] ); ?>" <?php echo $target; ?> class="eael-pricing-button"><?php echo $settings['eael_pricing_table_btn']; ?></a>
	        </div>
	    </div>
	</div>
	<?php elseif( 'style-3' === $settings['eael_pricing_table_style'] ) : ?>
	<div class="eael-pricing style-3">
		<div class="eael-pricing-item <?php echo esc_attr( $featured_class ); ?>">
			<?php if( 'top' === $settings['eael_pricing_table_style_3_price_position'] ) : ?>
		    <div class="eael-pricing-tag on-top">
		        <span class="price-tag"><?php echo $settings['eael_pricing_table_price'] ?></span> <span class="price-period">/ <?php echo $settings['eael_pricing_table_price_period']; ?></span>
		    </div>
		 	<?php endif; ?>
		    <div class="header">
		        <h2 class="title"><?php echo $settings['eael_pricing_table_title']; ?></h2>
		        <span class="subtitle"><?php echo $settings['eael_pricing_table_sub_title']; ?></span>
		    </div>
		    <div class="body">
		        <ul>
	            	<?php 
	            		foreach( $settings['eael_pricing_table_items'] as $item ) : 
	            		if( 'yes' === $item['eael_pricing_table_icon_mood'] ) : $icon_mood = ''; else : $icon_mood = 'disable-item'; endif;
	            	?>
	                	<li class="<?php echo esc_attr( $icon_mood ); ?>">
	                		<?php if( 'show' === $settings['eael_pricing_table_icon_enabled'] ) : ?>
	                		<span class="li-icon"><i class="<?php echo esc_attr( $item['eael_pricing_table_list_icon'] ); ?>"></i></span> 
	                		<?php endif; ?>
	                		<?php echo $item['eael_pricing_table_item']; ?>
	                	</li>
	               <?php endforeach; ?> 
	            </ul>
		    </div>
		    <?php if( 'bottom' === $settings['eael_pricing_table_style_3_price_position'] ) : ?>
		    <div class="eael-pricing-tag">
		        <span class="price-tag"><?php echo $settings['eael_pricing_table_price'] ?></span> <span class="price-period">/ <?php echo $settings['eael_pricing_table_price_period']; ?></span>
		    </div>
		 	<?php endif; ?>
		    <div class="footer">
		        <a href="<?php echo esc_url( $settings['eael_pricing_table_btn_link']['url'] ); ?>" <?php echo $target; ?> class="eael-pricing-button"><?php echo $settings['eael_pricing_table_btn']; ?></a>
		    </div>
		</div>
	</div>
	<?php elseif( 'style-4' === $settings['eael_pricing_table_style'] ) : ?>
	<div class="eael-pricing style-4">
		<div class="eael-pricing-item <?php echo esc_attr( $featured_class ); ?>">
		    <div class="eael-pricing-image">
		        <div class="eael-pricing-tag">
		            <span class="price-tag"><?php echo $settings['eael_pricing_table_price'] ?></span> <span class="price-period">/ <?php echo $settings['eael_pricing_table_price_period']; ?></span>
		        </div>
		    </div>
		    <div class="header">
		        <h2 class="title"><?php echo $settings['eael_pricing_table_title']; ?></h2>
		        <span class="subtitle"><?php echo $settings['eael_pricing_table_sub_title']; ?></span>
		    </div>
		    <div class="body">
		        <ul>
	            	<?php 
	            		foreach( $settings['eael_pricing_table_items'] as $item ) : 
	            		if( 'yes' === $item['eael_pricing_table_icon_mood'] ) : $icon_mood = ''; else : $icon_mood = 'disable-item'; endif;
	            	?>
	                	<li class="<?php echo esc_attr( $icon_mood ); ?>">
	                		<?php if( 'show' === $settings['eael_pricing_table_icon_enabled'] ) : ?>
	                		<span class="li-icon"><i class="<?php echo esc_attr( $item['eael_pricing_table_list_icon'] ); ?>"></i></span> 
	                		<?php endif; ?>
	                		<?php echo $item['eael_pricing_table_item']; ?>
	                	</li>
	               <?php endforeach; ?> 
	            </ul>
		    </div>
		    <div class="footer">
		        <a href="<?php echo esc_url( $settings['eael_pricing_table_btn_link']['url'] ); ?>" <?php echo $target; ?> class="eael-pricing-button"><?php echo $settings['eael_pricing_table_btn']; ?></a>
		    </div>
		</div>
	</div>
	<?php endif; ?>
	<?php
	}

	protected function content_template() {
		
		?>
		
	
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Pricing_Table() );
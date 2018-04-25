<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Widget_Eael_Adv_Tabs extends Widget_Base {

	public function get_name() {
		return 'eael-adv-tabs';
	}

	public function get_title() {
		return esc_html__( 'EA Advanced Tabs', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {
		/**
  		 * Advance Tabs Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_adv_tabs_settings',
  			[
  				'label' => esc_html__( 'General Settings', 'essential-addons-elementor' )
  			]
  		);
		$this->add_control(
			'eael_adv_tabs_icon_show',
			[
				'label' => esc_html__( 'Enable Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);
		$this->add_control(
		  'eael_adv_tab_icon_position',
		  	[
		   	'label'       	=> esc_html__( 'Icon Position', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'eael-tab-inline-icon',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'eael-tab-top-icon' => esc_html__( 'Stacked', 'essential-addons-elementor' ),
		     		'eael-tab-inline-icon' => esc_html__( 'Inline', 'essential-addons-elementor' ),
		     	],
		     	'condition' => [
		     		'eael_adv_tabs_icon_show' => 'yes'
		     	]
		  	]
		);
  		$this->end_controls_section();

  		/**
  		 * Advance Tabs Content Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_adv_tabs_content_settings',
  			[
  				'label' => esc_html__( 'Content Settings', 'essential-addons-elementor' )
  			]
  		);
  		$this->add_control(
			'eael_adv_tabs_tab',
			[
				'type' => Controls_Manager::REPEATER,
				'seperator' => 'before',
				'default' => [
					[ 'eael_adv_tabs_tab_title' => esc_html__( 'Tab Title 1', 'essential-addons-elementor' ) ],
					[ 'eael_adv_tabs_tab_title' => esc_html__( 'Tab Title 2', 'essential-addons-elementor' ) ],
					[ 'eael_adv_tabs_tab_title' => esc_html__( 'Tab Title 3', 'essential-addons-elementor' ) ],
				],
				'fields' => [
					[
						'name' => 'eael_adv_tabs_tab_show_as_default',
						'label' => __( 'Set as Default', 'essential-addons-elementor' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => 'inactive',
						'return_value' => 'active',
				  	],
					[
						'name' => 'eael_adv_tabs_tab_title_icon',
						'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
						'type' => Controls_Manager::ICON,
						'default' => 'fa fa-home',
					],
					[
						'name' => 'eael_adv_tabs_tab_title',
						'label' => esc_html__( 'Tab Title', 'essential-addons-elementor' ),
						'type' => Controls_Manager::TEXT,
						'default' => esc_html__( 'Tab Title', 'essential-addons-elementor' )
					],
				  	[
						'name' => 'eael_adv_tabs_tab_content',
						'label' => esc_html__( 'Tab Content', 'essential-addons-elementor' ),
						'type' => Controls_Manager::WYSIWYG,
						'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'essential-addons-elementor' )
					],
				],
				'title_field' => '{{eael_adv_tabs_tab_title}}',
			]
		);
  		$this->end_controls_section();
  		/**
  		 * Go Premium For More Features
  		 */
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

  		/**
		 * -------------------------------------------
		 * Tab Style Advance Tabs Generel Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_adv_tabs_style_settings',
			[
				'label' => esc_html__( 'General Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'eael_adv_tabs_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-advance-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_responsive_control(
			'eael_adv_tabs_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-advance-tabs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_adv_tabs_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-advance-tabs',
			]
		);
		$this->add_responsive_control(
			'eael_adv_tabs_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-advance-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_adv_tabs_box_shadow',
				'selector' => '{{WRAPPER}} .eael-advance-tabs',
			]
		);
  		$this->end_controls_section();
  		/**
		 * -------------------------------------------
		 * Tab Style Advance Tabs Content Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_adv_tabs_tab_style_settings',
			[
				'label' => esc_html__( 'Tab Title Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            	'name' => 'eael_adv_tabs_tab_title_typography',
				'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a',
			]
		);
		$this->add_responsive_control(
			'eael_adv_tabs_tab_icon_size',
			[
				'label' => __( 'Icon Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 16,
					'unit' => 'px',
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a .fa' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'eael_adv_tabs_tab_icon_gap',
			[
				'label' => __( 'Icon Gap', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
					'unit' => 'px',
				],
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .eael-tab-inline-icon li a .fa' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-tab-top-icon li a .fa' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'eael_adv_tabs_tab_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_responsive_control(
			'eael_adv_tabs_tab_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->start_controls_tabs( 'eael_adv_tabs_header_tabs' );
			// Normal State Tab
			$this->start_controls_tab( 'eael_adv_tabs_header_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );
				$this->add_control(
					'eael_adv_tabs_tab_color',
					[
						'label' => esc_html__( 'Tab Background Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#f1f1f1',
						'selectors' => [
							'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'eael_adv_tabs_tab_text_color',
					[
						'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#333',
						'selectors' => [
							'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'eael_adv_tabs_tab_icon_color',
					[
						'label' => esc_html__( 'Icon Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#333',
						'selectors' => [
							'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a .fa' => 'color: {{VALUE}};',
						],
						'condition' => [
							'eael_adv_tabs_icon_show' => 'yes'
						]
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'eael_adv_tabs_tab_border',
						'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
						'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a',
					]
				);
				$this->add_responsive_control(
					'eael_adv_tabs_tab_border_radius',
					[
						'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors' => [
			 					'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			 			],
					]
				);
			$this->end_controls_tab();
			// Hover State Tab
			$this->start_controls_tab( 'eael_adv_tabs_header_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );
				$this->add_control(
					'eael_adv_tabs_tab_color_hover',
					[
						'label' => esc_html__( 'Tab Background Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#f1f1f1',
						'selectors' => [
							'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'eael_adv_tabs_tab_text_color_hover',
					[
						'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#333',
						'selectors' => [
							'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'eael_adv_tabs_tab_icon_color_hover',
					[
						'label' => esc_html__( 'Icon Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#333',
						'selectors' => [
							'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a:hover .fa' => 'color: {{VALUE}};',
						],
						'condition' => [
							'eael_adv_tabs_icon_show' => 'yes'
						]
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'eael_adv_tabs_tab_border_hover',
						'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
						'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a:hover',
					]
				);
				$this->add_responsive_control(
					'eael_adv_tabs_tab_border_radius_hover',
					[
						'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors' => [
			 					'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			 			],
					]
				);
			$this->end_controls_tab();
			// Active State Tab
			$this->start_controls_tab( 'eael_adv_tabs_header_active', [ 'label' => esc_html__( 'Active', 'essential-addons-elementor' ) ] );
				$this->add_control(
					'eael_adv_tabs_tab_color_active',
					[
						'label' => esc_html__( 'Tab Background Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#444',
						'selectors' => [
							'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a.active' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'eael_adv_tabs_tab_text_color_active',
					[
						'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#fff',
						'selectors' => [
							'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a.active' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'eael_adv_tabs_tab_icon_color_active',
					[
						'label' => esc_html__( 'Icon Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#fff',
						'selectors' => [
							'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a.active .fa' => 'color: {{VALUE}};',
						],
						'condition' => [
							'eael_adv_tabs_icon_show' => 'yes'
						]
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'eael_adv_tabs_tab_border_active',
						'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
						'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a.active',
					]
				);
				$this->add_responsive_control(
					'eael_adv_tabs_tab_border_radius_active',
					[
						'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
						'type' => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors' => [
			 					'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			 			],
					]
				);
			$this->end_controls_tab();
		$this->end_controls_tabs();
  		$this->end_controls_section();

  		/**
		 * -------------------------------------------
		 * Tab Style Advance Tabs Content Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_adv_tabs_tab_content_style_settings',
			[
				'label' => esc_html__( 'Content Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'adv_tabs_content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-advance-tabs .eael-tab-contents .eael-tab-content' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'adv_tabs_content_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333',
				'selectors' => [
					'{{WRAPPER}} .eael-advance-tabs .eael-tab-contents .eael-tab-content' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            	'name' => 'eael_adv_tabs_content_typography',
				'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tab-contents .eael-tab-content',
			]
		);
		$this->add_responsive_control(
			'eael_adv_tabs_content_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-advance-tabs .eael-tab-contents .eael-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_responsive_control(
			'eael_adv_tabs_content_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-advance-tabs .eael-tab-contents .eael-tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_adv_tabs_content_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tab-contents .eael-tab-content',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_adv_tabs_content_shadow',
				'selector' => '{{WRAPPER}} .eael-advance-tabs .eael-tab-contents .eael-tab-content',
				'separator' => 'before'
			]
		);
  		$this->end_controls_section();

  		/**
		 * -------------------------------------------
		 * Tab Style Advance Tabs Caret Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_adv_tabs_tab_caret_style_settings',
			[
				'label' => esc_html__( 'Caret Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'eael_adv_tabs_tab_caret_show',
			[
				'label' => esc_html__( 'Show Caret on Active Tab', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'eael_adv_tabs_tab_caret_size',
			[
				'label' => esc_html__( 'Caret Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10
				],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a.active:after' => 'border-width: {{SIZE}}px; bottom: -{{SIZE}}px',
				],
				'condition' => [
					'eael_adv_tabs_tab_caret_show' => 'yes'
				]
			]
		);
		$this->add_control(
			'eael_adv_tabs_tab_caret_color',
			[
				'label' => esc_html__( 'Caret Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#444',
				'selectors' => [
					'{{WRAPPER}} .eael-advance-tabs .eael-tab-navs li a.active:after' => 'border-top-color: {{VALUE}};',
				],
				'condition' => [
					'eael_adv_tabs_tab_caret_show' => 'yes'
				]
			]
		);
  		$this->end_controls_section();
	}

	protected function render() {

   		$settings = $this->get_settings();
   		$eael_find_default_tab = array();
   		$eael_adv_tab_id = 1;
   		$eael_adv_tab_content_id = 1;
	?>
	<div class="eael-advance-tabs" id="eael-advance-tabs-<?php echo esc_attr( $this->get_id() ); ?>">
		<ul class="eael-tab-navs <?php echo esc_attr( $settings['eael_adv_tab_icon_position'] ); ?>">
			<?php foreach( $settings['eael_adv_tabs_tab'] as $tab ) : ?>
			<li><a hreg="javascript:;" data-eael-tab-id="eael-adv-tab-<?php echo esc_attr($eael_adv_tab_id); ?>" class="<?php echo esc_attr( $tab['eael_adv_tabs_tab_show_as_default'] ); ?>"><?php if( $settings['eael_adv_tabs_icon_show'] === 'yes' ) : ?><i class="<?php echo esc_attr( $tab['eael_adv_tabs_tab_title_icon'] ); ?>"></i><?php endif; ?> <span class="eael-tab-title"><?php echo $tab['eael_adv_tabs_tab_title']; ?></span></a></li>
			<?php $eael_adv_tab_id++; endforeach; ?>
		</ul>
		<div class="eael-tab-contents">
			<?php foreach( $settings['eael_adv_tabs_tab'] as $tab ) : $eael_find_default_tab[] = $tab['eael_adv_tabs_tab_show_as_default'];?>
			<div class="eael-tab-content clearfix <?php echo esc_attr( $tab['eael_adv_tabs_tab_show_as_default'] ); ?>" id="eael-adv-tab-<?php echo esc_attr($eael_adv_tab_content_id); ?>">
				<?php echo do_shortcode( $tab['eael_adv_tabs_tab_content'] ); ?>
			</div>
			<?php $eael_adv_tab_content_id++; endforeach;?>
		</div>
	</div>
	<script>
		jQuery(document).ready(function($) {
			var $eaelTab = $('#eael-advance-tabs-<?php echo esc_attr( $this->get_id() ); ?>');
			var $eaelTabNavLi = $eaelTab.find('.eael-tab-navs li');
			var $eaelTabNavs = $eaelTab.find('.eael-tab-navs li a');

			var $eaelTabContents = $eaelTab.find('.eael-tab-contents');
			<?php
			if( in_array('active', $eael_find_default_tab) ) {
				// Do nothing
			}else {
			?>
				$eaelTabNavLi.each( function(i) {
					if( i < 1 ) {
						$(this).find('a').removeClass('inactive').addClass('active');
					}
				} );
				$eaelTabContents.find('.eael-tab-content').each( function(i) {
					if( i < 1 ) {
						$(this).removeClass('inactive').addClass('active');
					}
				} );
			<?php
			}
			?>
			$eaelTabNavs.on('click', function(e) {
				e.preventDefault();
				$eaelTabNavs.removeClass('active');
				var $eaelTabCotnentId = $(this).data('eael-tab-id');
				$(this).addClass('active');
				$eaelTabContents.find('.eael-tab-content').removeClass('inactive active');
				$eaelTabContents.find('#'+$eaelTabCotnentId).addClass('active');
			});
		});
	</script>
	<?php if( $settings['eael_adv_tabs_tab_caret_show'] !== 'yes' ) : ?>
	<style>
		#eael-advance-tabs-<?php echo esc_attr( $this->get_id() ); ?> .eael-tab-navs li a.active:after {
			display: none;
		}
	</style>
	<?php endif; ?>
	<?php
	}

	protected function content_template() {}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Adv_Tabs() );
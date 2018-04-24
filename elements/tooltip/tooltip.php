<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Widget_Eael_Tooltip extends Widget_Base {

	public function get_name() {
		return 'eael-tooltip';
	}

	public function get_title() {
		return esc_html__( 'EA Tooltip', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-tooltip';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {
		/**
  		 * Tooltip Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_tooltip_settings',
  			[
  				'label' => esc_html__( 'Content Settings', 'essential-addons-elementor' )
  			]
  		);
		$this->add_responsive_control(
			'eael_tooltip_type',
			[
				'label' => esc_html__( 'Tooltip Type', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'icon' => [
						'title' => esc_html__( 'Icon', 'essential-addons-elementor' ),
						'icon' => 'fa fa-info',
					],
					'text' => [
						'title' => esc_html__( 'Text', 'essential-addons-elementor' ),
						'icon' => 'fa fa-text-width',
					],
					'image' => [
						'title' => esc_html__( 'Image', 'essential-addons-elementor' ),
						'icon' => 'fa fa-image',
					],
					'shortcode' => [
						'title' => esc_html__( 'Shortcode', 'essential-addons-elementor' ),
						'icon' => 'fa fa-code',
					],
				],
				'default' => 'icon',
			]
		);
  		$this->add_control(
			'eael_tooltip_content',
			[
				'label' => esc_html__( 'Content', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Hover Me!', 'essential-addons-elementor' ),
				'condition' => [
					'eael_tooltip_type' => [ 'text' ]
				]
			]
		);
		$this->add_control(
		  'eael_tooltip_content_tag',
		  	[
		   		'label'       	=> esc_html__( 'Content Tag', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'span',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'h1'  	=> esc_html__( 'H1', 'essential-addons-elementor' ),
		     		'h2'  	=> esc_html__( 'H2', 'essential-addons-elementor' ),
		     		'h3'  	=> esc_html__( 'H3', 'essential-addons-elementor' ),
		     		'h4'  	=> esc_html__( 'H4', 'essential-addons-elementor' ),
		     		'h5'  	=> esc_html__( 'H5', 'essential-addons-elementor' ),
		     		'h6'  	=> esc_html__( 'H6', 'essential-addons-elementor' ),
		     		'div'  	=> esc_html__( 'DIV', 'essential-addons-elementor' ),
		     		'span'  => esc_html__( 'SPAN', 'essential-addons-elementor' ),
		     		'p'  	=> esc_html__( 'P', 'essential-addons-elementor' ),
		     	],
		     	'condition' => [
		     		'eael_tooltip_type' => 'text'
		     	]
		  	]
		);
		$this->add_control(
			'eael_tooltip_shortcode_content',
			[
				'label' => esc_html__( 'Shortcode', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( '[shortcode-here]', 'essential-addons-elementor' ),
				'condition' => [
					'eael_tooltip_type' => [ 'shortcode' ]
				]
			]
		);
		$this->add_control(
			'eael_tooltip_icon_content',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-home',
				'condition' => [
					'eael_tooltip_type' => [ 'icon' ]
				]
			]
		);
		$this->add_control(
			'eael_tooltip_img_content',
			[
				'label' => esc_html__( 'Image', 'essential-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'eael_tooltip_type' => [ 'image' ]
				]
			]
		);
		$this->add_responsive_control(
			'eael_tooltip_alignment',
			[
				'label' => esc_html__( 'Tooltip Alignment', 'essential-addons-elementor' ),
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
					'justify' => [
						'title' => __( 'Justified', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => 'left',
				'prefix_class' => 'eael-tooltip-align-',
			]
		);
		$this->add_control(
			'eael_tooltip_enable_link',
			[
				'label' => esc_html__( 'Enable Link', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'false',
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'eael_tooltip_link',
			[
				'label' => esc_html__( 'Button Link', 'essential-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'default' => [
        			'url' => '#',
        			'is_external' => '',
     			],
     			'show_external' => true,
     			'condition' => [
     				'eael_tooltip_enable_link' => 'yes'
     			]
			]
		);
  		$this->end_controls_section();

  		/**
  		 * Tooltip Hover Content Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_tooltip_hover_content_settings',
  			[
  				'label' => esc_html__( 'Tooltip Settings', 'essential-addons-elementor' )
  			]
  		);
  		$this->add_control(
			'eael_tooltip_hover_content',
			[
				'label' => esc_html__( 'Content', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Tooltip hover content', 'essential-addons-elementor' ),
			]
		);
		$this->add_control(
		  'eael_tooltip_hover_dir',
		  	[
		   		'label'       	=> esc_html__( 'Hover Direction', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'right',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'left'  	=> esc_html__( 'Left', 'essential-addons-elementor' ),
		     		'right'  	=> esc_html__( 'Right', 'essential-addons-elementor' ),
		     		'up'  		=> esc_html__( 'Up', 'essential-addons-elementor' ),
		     		'down'  	=> esc_html__( 'Down', 'essential-addons-elementor' ),
		     	],
		  	]
		);
  		$this->end_controls_section();

  		/**
		 * -------------------------------------------
		 * Tab Style Tooltip Content
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_tooltip_style_settings',
			[
				'label' => esc_html__( 'Content Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'eael_tooltip_content_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_responsive_control(
			'eael_tooltip_content_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-tooltip' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->start_controls_tabs( 'eael_tooltip_content_style_tabs' );
			// Normal State Tab
			$this->start_controls_tab( 'eael_tooltip_content_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );
				$this->add_control(
					'eael_tooltip_content_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .eael-tooltip' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'eael_tooltip_content_color',
					[
						'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .eael-tooltip' => 'color: {{VALUE}};',
							'{{WRAPPER}} .eael-tooltip a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'eael_tooltip_shadow',
						'selector' => '{{WRAPPER}} .eael-tooltip',
						'separator' => 'before'
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'eael_tooltip_border',
						'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
						'selector' => '{{WRAPPER}} .eael-tooltip',
					]
				);
			$this->end_controls_tab();

			// Hover State Tab
			$this->start_controls_tab( 'eael_tooltip_content_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );
				$this->add_control(
					'eael_tooltip_content_hover_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '',
						'selectors' => [
							'{{WRAPPER}} .eael-tooltip:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'eael_tooltip_content_hover_color',
					[
						'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
						'type' => Controls_Manager::COLOR,
						'default' => '#212121',
						'selectors' => [
							'{{WRAPPER}} .eael-tooltip:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}} .eael-tooltip:hover a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'eael_tooltip_hover_shadow',
						'selector' => '{{WRAPPER}} .eael-tooltip:hover',
						'separator' => 'before'
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'eael_tooltip_hover_border',
						'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
						'selector' => '{{WRAPPER}} .eael-tooltip:hover',
					]
				);
			$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            	'name' => 'eael_tooltip_content_typography',
				'selector' => '{{WRAPPER}} .eael-tooltip',
			]
		);
		$this->add_responsive_control(
			'eael_tooltip_content_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style Tooltip Hover Content
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_tooltip_hover_style_settings',
			[
				'label' => esc_html__( 'Tooltip Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'eael_tooltip_hover_content_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-tooltip[tooltip]::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_responsive_control(
			'eael_tooltip_hover_content_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-tooltip[tooltip]::after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_control(
			'eael_tooltip_hover_content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555',
				'selectors' => [
					'{{WRAPPER}} .eael-tooltip[tooltip]::after' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'eael_tooltip_hover_content_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-tooltip[tooltip]::after' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            	'name' => 'eael_tooltip_hover_content_typography',
				'selector' => '{{WRAPPER}} .eael-tooltip[tooltip]::after',
			]
		);
		$this->add_responsive_control(
			'eael_tooltip_arrow_size',
			[
				'label' => __( 'Arrow Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
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
					'{{WRAPPER}} .eael-tooltip[tooltip]::before' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-tooltip[tooltip]:not([flow])::after, .eael-tooltip[tooltip][flow^="up"]::after' => 'bottom: calc(100% + 2*{{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .eael-tooltip[tooltip][flow^="down"]::after' => 'top: calc(100% + 2*{{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .eael-tooltip[tooltip][flow^="left"]::after' => 'right: calc(100% + 2*{{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .eael-tooltip[tooltip][flow^="right"]::after' => 'left: calc(100% + 2*{{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .eael-tooltip[tooltip][flow^="left"]::before' => 'left: calc(0em - 2*{{SIZE}}{{UNIT}});',
					'{{WRAPPER}} .eael-tooltip[tooltip][flow^="right"]::before' => 'right: calc(0em - 2*{{SIZE}}{{UNIT}});',
				],
			]
		);
		$this->add_control(
			'eael_tooltip_arrow_color',
			[
				'label' => esc_html__( 'Arrow Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555',
				'selectors' => [
					'{{WRAPPER}} .eael-tooltip[tooltip][flow^="down"]::before' => 'border-bottom-color: {{VALUE}};',
					'{{WRAPPER}} .eael-tooltip[tooltip][flow^="up"]::before' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .eael-tooltip[tooltip][flow^="left"]::before' => 'border-left-color: {{VALUE}};',
					'{{WRAPPER}} .eael-tooltip[tooltip][flow^="right"]::before' => 'border-right-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
	}


	protected function render( ) {

   		$settings = $this->get_settings();
   		$target = $settings['eael_tooltip_link']['is_external'] ? 'target="_blank"' : '';
	  	$nofollow = $settings['eael_tooltip_link']['nofollow'] ? 'rel="nofollow"' : '';
	?>
	<?php if( $settings['eael_tooltip_type'] === 'icon' ) : ?>
		<span class="eael-tooltip eael-tooltip-icon" tooltip="<?php echo esc_attr( $settings['eael_tooltip_hover_content'] ); ?>" flow="<?php echo esc_attr( $settings['eael_tooltip_hover_dir'] ) ?>"><?php if( $settings['eael_tooltip_enable_link'] === 'yes' ) : ?><a href="<?php echo esc_url( $settings['eael_tooltip_link']['url'] ); ?>" <?php echo $target; ?> <?php echo $nofollow; ?> ><?php endif; ?><i class="<?php echo esc_attr( $settings['eael_tooltip_icon_content'] ); ?>"><?php if( $settings['eael_tooltip_enable_link'] === 'yes' ) : ?></a><?php endif; ?></i></span>
	<?php elseif( $settings['eael_tooltip_type'] === 'text' ) : ?>
		<<?php echo esc_attr( $settings['eael_tooltip_content_tag'] ); ?> class="eael-tooltip eael-tooltip-text" tooltip="<?php echo esc_attr( $settings['eael_tooltip_hover_content'] ); ?>" flow="<?php echo esc_attr( $settings['eael_tooltip_hover_dir'] ) ?>"><?php if( $settings['eael_tooltip_enable_link'] === 'yes' ) : ?><a href="<?php echo esc_url( $settings['eael_tooltip_link']['url'] ); ?>" <?php echo $target; ?> <?php echo $nofollow; ?> ><?php endif; ?><?php echo esc_html__( $settings['eael_tooltip_content'], 'essential-addons-elementor' ); ?><?php if( $settings['eael_tooltip_enable_link'] === 'yes' ) : ?></a><?php endif; ?></<?php echo esc_attr( $settings['eael_tooltip_content_tag'] ); ?>>
	<?php elseif( $settings['eael_tooltip_type'] === 'image' ) : ?>
		<div class="eael-tooltip eael-tooltip-img" tooltip="<?php echo esc_attr( $settings['eael_tooltip_hover_content'] ); ?>" flow="<?php echo esc_attr( $settings['eael_tooltip_hover_dir'] ) ?>"><?php if( $settings['eael_tooltip_enable_link'] === 'yes' ) : ?><a href="<?php echo esc_url( $settings['eael_tooltip_link']['url'] ); ?>" <?php echo $target; ?> <?php echo $nofollow; ?> ><?php endif; ?><img src="<?php echo esc_url( $settings['eael_tooltip_img_content']['url'] ); ?>" alt="<?php echo esc_attr( $settings['eael_tooltip_hover_content'] ); ?>"><?php if( $settings['eael_tooltip_enable_link'] === 'yes' ) : ?></a><?php endif; ?></div>
	<?php elseif( $settings['eael_tooltip_type'] === 'shortcode' ) : ?>
		<div class="eael-tooltip eael-tooltip-shortcode" tooltip="<?php echo esc_attr( $settings['eael_tooltip_hover_content'] ); ?>" flow="<?php echo esc_attr( $settings['eael_tooltip_hover_dir'] ) ?>"><?php echo do_shortcode( $settings['eael_tooltip_shortcode_content'] ); ?></div>
	<?php endif; ?>

	<?php
	}

	protected function content_template() {}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Tooltip() );
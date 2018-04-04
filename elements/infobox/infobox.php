<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Widget_Eael_Info_Box extends Widget_Base {

	public function get_name() {
		return 'eael-info-box';
	}

	public function get_title() {
		return esc_html__( 'EA Info Box', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-info-box';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {

  		/**
  		 * Infobox Image Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_infobox_content_settings',
  			[
  				'label' => esc_html__( 'Infobox Image', 'essential-addons-elementor' )
  			]
  		);

  		$this->add_control(
		  'eael_infobox_img_type',
		  	[
		   	'label'       	=> esc_html__( 'Infobox Type', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'img-on-top',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'img-on-top'  	=> esc_html__( 'Image/Icon On Top', 'essential-addons-elementor' ),
		     		'img-on-left' 	=> esc_html__( 'Image/Icon On Left', 'essential-addons-elementor' ),
		     		'img-on-right' 	=> esc_html__( 'Image/Icon On Right', 'essential-addons-elementor' ),
		     	],
		  	]
		);

		$this->add_responsive_control(
			'eael_infobox_img_or_icon',
			[
				'label' => esc_html__( 'Image or Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'img' => [
						'title' => esc_html__( 'Image', 'essential-addons-elementor' ),
						'icon' => 'fa fa-picture-o',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'essential-addons-elementor' ),
						'icon' => 'fa fa-info-circle',
					],
				],
				'default' => 'icon',
			]
		);
		/**
		 * Condition: 'eael_infobox_img_or_icon' => 'img'
		 */
		$this->add_control(
			'eael_infobox_image',
			[
				'label' => esc_html__( 'Infobox Image', 'essential-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'eael_infobox_img_or_icon' => 'img'
				]
			]
		);


		/**
		 * Condition: 'eael_infobox_img_or_icon' => 'icon'
		 */
		$this->add_control(
			'eael_infobox_icon',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-building-o',
				'condition' => [
					'eael_infobox_img_or_icon' => 'icon'
				]
			]
		);

		$this->add_control(
			'eael_show_infobox_clickable',
			[
				'label' => __( 'Infobox Clickable', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Yes', 'essential-addons-elementor' ),
				'label_off' => __( 'No', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'eael_show_infobox_clickable_link',
			[
				'label' => esc_html__( 'Infobox Link', 'essential-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'default' => [
        			'url' => 'http://',
        			'is_external' => '',
     			],
     			'show_external' => true,
     			'condition' => [
     				'eael_show_infobox_clickable' => 'yes'
     			]
			]
		);

		$this->end_controls_section();

		/**
		 * Infobox Content
		 */
		$this->start_controls_section(
			'eael_infobox_content',
			[
				'label' => esc_html__( 'Infobox Content', 'essential-addons-elementor' ),
			]
		);
		$this->add_control(
			'eael_infobox_title',
			[
				'label' => esc_html__( 'Infobox Title', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'This is an icon box', 'essential-addons-elementor' )
			]
		);
		$this->add_control(
			'eael_infobox_text',
			[
				'label' => esc_html__( 'Infobox Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Write a short description, that will describe the title or something informational and useful.', 'essential-addons-elementor' )
			]
		);
		$this->add_control(
			'eael_show_infobox_content',
			[
				'label' => __( 'Show Content', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'essential-addons-elementor' ),
				'label_off' => __( 'Hide', 'essential-addons-elementor' ),
				'return_value' => 'yes',
			]
		);
		$this->add_responsive_control(
			'eael_infobox_content_alignment',
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
				'default' => 'center',
				'prefix_class' => 'eael-infobox-content-align-',
				'condition' => [
					'eael_infobox_img_type' => 'img-on-top'
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

		/**
		 * -------------------------------------------
		 * Tab Style (Info Box Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_infobox_style_settings',
			[
				'label' => esc_html__( 'Info Box Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_infobox_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-infobox' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_infobox_container_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-infobox' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_infobox_container_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-infobox' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
				[
					'name' => 'eael_infobox_border',
					'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
					'selector' => '{{WRAPPER}} .eael-infobox',
				]
		);

		$this->add_control(
			'eael_infobox_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-infobox' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_infobox_shadow',
				'selector' => '{{WRAPPER}} .eael-infobox',
			]
		);

		$this->end_controls_section();
		/**
		 * -------------------------------------------
		 * Tab Style (Info Box Image)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_infobox_imgae_style_settings',
			[
				'label' => esc_html__( 'Image Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
		     		'eael_infobox_img_or_icon' => 'img'
		     	]
			]
		);

		$this->add_control(
		  'eael_infobox_img_shape',
		  	[
		   	'label'     	=> esc_html__( 'Image Shape', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'square',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'square'  	=> esc_html__( 'Square', 'essential-addons-elementor' ),
		     		'circle' 	=> esc_html__( 'Circle', 'essential-addons-elementor' ),
		     		'radius' 	=> esc_html__( 'Radius', 'essential-addons-elementor' ),
		     	],
		     	'prefix_class' => 'eael-infobox-shape-',
		     	'condition' => [
		     		'eael_infobox_img_or_icon' => 'img'
		     	]
		  	]
		);

		$this->add_control(
			'eael_infobox_image_resizer',
			[
				'label' => esc_html__( 'Image Resizer', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100
				],
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-infobox .infobox-icon img' => 'width: {{SIZE}}px;',
				],
				'condition' => [
		     		'eael_infobox_img_or_icon' => 'img',
		     		'eael_infobox_img_type' => 'img-on-top'
		     	]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'full',
				'condition' => [
					'eael_infobox_image[url]!' => '',
				],
				'condition' => [
					'eael_infobox_img_or_icon' => 'img',
				]
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Info Box Icon Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_infobox_icon_style_settings',
			[
				'label' => esc_html__( 'Icon Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
		     		'eael_infobox_img_or_icon' => 'icon'
		     	]
			]
		);

		$this->add_control(
    		'eael_infobox_icon_size',
    		[
        		'label' => __( 'Icon Size', 'essential-addons-elementor' ),
       		'type' => Controls_Manager::SLIDER,
        		'default' => [
            	'size' => 40,
        		],
        		'range' => [
            	'px' => [
                	'min' => 20,
                	'max' => 100,
                	'step' => 1,
            	]
        		],
        		'selectors' => [
            	'{{WRAPPER}} .eael-infobox .infobox-icon i' => 'font-size: {{SIZE}}px;',
        		],
    		]
		);

		$this->add_control(
    		'eael_infobox_icon_margin_bottom',
    		[
        		'label' => __( 'Icon Margin Bottom', 'essential-addons-elementor' ),
       			'type' => Controls_Manager::SLIDER,
        		'default' => [
            		'size' => 30,
        		],
        		'range' => [
            		'px' => [
                		'min' => 0,
                		'max' => 200,
                		'step' => 1,
            		]
        		],
        		'selectors' => [
            		'{{WRAPPER}} .eael-infobox .infobox-icon' => 'margin-bottom: {{SIZE}}px;',
        		],
        		'condition' => [
					'eael_infobox_img_type' => 'img-on-top',
				]
    		]
		);

		$this->add_control(
    		'eael_infobox_icon_bg_size',
    		[
        		'label' => __( 'Icon Background Size', 'essential-addons-elementor' ),
       			'type' => Controls_Manager::SLIDER,
        		'default' => [
            		'size' => 90,
        		],
        		'range' => [
            		'px' => [
                		'min' => 0,
                		'max' => 300,
                		'step' => 1,
            		]
        		],
        		'selectors' => [
            		'{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-wrap' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
        		],
        		'condition' => [
					'eael_infobox_icon_bg_shape!' => 'none',
					'eael_infobox_img_type!' => ['img-on-left', 'img-on-right'],
				]
    		]
		);

		$this->add_control(
    		'eael_infobox_icon_margin_right',
    		[
        		'label' => __( 'Icon Margin Right', 'essential-addons-elementor' ),
       			'type' => Controls_Manager::SLIDER,
        		'default' => [
            		'size' => 15,
        		],
        		'range' => [
            		'px' => [
                		'min' => 0,
                		'max' => 200,
                		'step' => 1,
            		]
        		],
        		'selectors' => [
            		'{{WRAPPER}} .eael-infobox.icon-on-left .infobox-content' => 'padding-left: {{SIZE}}px;',
        		],
        		'condition' => [
					'eael_infobox_img_type' => 'img-on-left',
				]
    		]
		);

		$this->add_control(
    		'eael_infobox_icon_margin_left',
    		[
        		'label' => __( 'Icon Margin Left', 'essential-addons-elementor' ),
       			'type' => Controls_Manager::SLIDER,
        		'default' => [
            		'size' => 15,
        		],
        		'range' => [
            		'px' => [
                		'min' => 0,
                		'max' => 200,
                		'step' => 1,
            		]
        		],
        		'selectors' => [
            		'{{WRAPPER}} .eael-infobox.icon-on-right .infobox-content' => 'padding-right: {{SIZE}}px;',
        		],
        		'condition' => [
					'eael_infobox_img_type' => 'img-on-right',
				]
    		]
		);

		$this->add_control(
			'eael_infobox_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4d4d4d',
				'selectors' => [
					'{{WRAPPER}} .eael-infobox .infobox-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-infobox.icon-beside-title .infobox-content .title figure i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
		  'eael_infobox_icon_bg_shape',
		  	[
		   	'label'     	=> esc_html__( 'Background Shape', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'none',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'none'  	=> esc_html__( 'None', 'essential-addons-elementor' ),
		     		'circle' 	=> esc_html__( 'Circle', 'essential-addons-elementor' ),
		     		'radius' 	=> esc_html__( 'Radius', 'essential-addons-elementor' ),
		     		'square' 	=> esc_html__( 'Square', 'essential-addons-elementor' ),
		     	],
		     	'prefix_class' => 'eael-infobox-icon-bg-shape-',
		     	'condition' => [
					'eael_infobox_img_type!' => ['img-on-left', 'img-on-right'],
				]
		  	]
		);

		$this->add_control(
			'eael_infobox_icon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f4f4f4',
				'selectors' => [
					'{{WRAPPER}} .eael-infobox .infobox-icon .infobox-icon-wrap' => 'background: {{VALUE}};',
				],
				'condition' => [
					'eael_infobox_img_type!' => ['img-on-left', 'img-on-right'],
					'eael_infobox_icon_bg_shape!' => 'none',
				]
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Info Box Title Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_infobox_title_style_settings',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_infobox_title_heading',
			[
				'label' => esc_html__( 'Title Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_infobox_title_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4d4d4d',
				'selectors' => [
					'{{WRAPPER}} .eael-infobox .infobox-content .title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_infobox_title_typography',
				'selector' => '{{WRAPPER}} .eael-infobox .infobox-content .title',
			]
		);

		$this->add_control(
    		'eael_infobox_title_margin_bottom',
    		[
        		'label' => __( 'Title Margin Bottom', 'essential-addons-elementor' ),
       			'type' => Controls_Manager::SLIDER,
        		'default' => [
            		'size' => 30,
        		],
        		'range' => [
            		'px' => [
                		'min' => 0,
                		'max' => 300,
                		'step' => 1,
            		]
        		],
        		'selectors' => [
            		'{{WRAPPER}} .eael-infobox .infobox-content .title' => 'margin-bottom: {{SIZE}}px;',
        		],
    		]
		);

		$this->add_control(
			'eael_infobox_content_heading',
			[
				'label' => esc_html__( 'Content Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_infobox_content_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4d4d4d',
				'selectors' => [
					'{{WRAPPER}} .eael-infobox .infobox-content p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_infobox_content_typography',
				'selector' => '{{WRAPPER}} .eael-infobox .infobox-content p',
			]
		);

		$this->end_controls_section();

	}


	protected function render( ) {

   		$settings = $this->get_settings();
      	$infobox_image = $this->get_settings( 'eael_infobox_image' );
	  	$infobox_image_url = Group_Control_Image_Size::get_attachment_image_src( $infobox_image['id'], 'thumbnail', $settings );
	  	if( empty( $infobox_image_url ) ) : $infobox_image_url = $infobox_image['url']; else: $infobox_image_url = $infobox_image_url; endif;

	  	$target = $settings['eael_show_infobox_clickable_link']['is_external'] ? 'target="_blank"' : '';
	  	$nofollow = $settings['eael_show_infobox_clickable_link']['nofollow'] ? 'rel="nofollow"' : '';

	?>
		<?php if( 'img-on-top' == $settings['eael_infobox_img_type'] ) : ?>
		<div class="eael-infobox">
			<?php if( 'yes' == $settings['eael_show_infobox_clickable'] ) : ?><a href="<?php echo esc_url( $settings['eael_show_infobox_clickable_link']['url'] ) ?>" <?php echo $target; ?> <?php echo $nofollow; ?>> <?php endif;?>
			<div class="infobox-icon">
				<?php if( 'img' == $settings['eael_infobox_img_or_icon'] ) : ?>
					<img src="<?php echo esc_url( $infobox_image_url ); ?>" alt="Icon Image">
				<?php endif; ?>
				<?php if( 'icon' == $settings['eael_infobox_img_or_icon'] ) : ?>
				<div class="infobox-icon-wrap">
					<i class="<?php echo esc_attr( $settings['eael_infobox_icon'] ); ?>"></i>
				</div>
				<?php endif; ?>
			</div>
			<div class="infobox-content">
				<h4 class="title"><?php echo $settings['eael_infobox_title']; ?></h4>
				<?php if( 'yes' == $settings['eael_show_infobox_content'] ) : ?>
					<p><?php echo $settings['eael_infobox_text']; ?></p>
				<?php endif; ?>
			</div>
			<?php if( 'yes' == $settings['eael_show_infobox_clickable'] ) : ?></a><?php endif; ?>
		</div>
		<?php endif; ?>
		<?php if( 'img-on-left' == $settings['eael_infobox_img_type'] ) : ?>
		<?php if( 'yes' == $settings['eael_show_infobox_clickable'] ) : ?><a href="<?php echo esc_url( $settings['eael_show_infobox_clickable_link']['url'] ) ?>" <?php echo $target; ?> <?php echo $nofollow; ?>> <?php endif;?>
		<div class="eael-infobox icon-on-left">
			<div class="infobox-icon <?php if( 'icon' == $settings['eael_infobox_img_or_icon'] ) : echo esc_attr( 'eael-icon-only', 'essential-addons-elementor' ); endif; ?>">
				<?php if( 'img' == $settings['eael_infobox_img_or_icon'] ) : ?>
				<figure>
					<img src="<?php echo esc_url( $infobox_image_url ); ?>" alt="Icon Image">
				</figure>
				<?php endif; ?>
				<?php if( 'icon' == $settings['eael_infobox_img_or_icon'] ) : ?>
				<div class="infobox-icon-wrap">
					<i class="<?php echo esc_attr( $settings['eael_infobox_icon'] ); ?>"></i>
				</div>
				<?php endif; ?>
			</div>
			<div class="infobox-content <?php if( 'icon' == $settings['eael_infobox_img_or_icon'] ) : echo esc_attr( 'eael-icon-only', 'essential-addons-elementor' ); endif; ?>">
				<h4 class="title"><?php echo $settings['eael_infobox_title']; ?></h4>
				<?php if( 'yes' == $settings['eael_show_infobox_content'] ) : ?>
					<p><?php echo $settings['eael_infobox_text']; ?></p>
				<?php endif; ?>
			</div>
		</div>
		<?php if( 'yes' == $settings['eael_show_infobox_clickable'] ) : ?></a><?php endif; ?>
		<?php endif; ?>
		<?php if( 'img-on-right' == $settings['eael_infobox_img_type'] ) : ?>
		<?php if( 'yes' == $settings['eael_show_infobox_clickable'] ) : ?><a href="<?php echo esc_url( $settings['eael_show_infobox_clickable_link']['url'] ) ?>" <?php echo $target; ?> <?php echo $nofollow; ?>> <?php endif;?>
		<div class="eael-infobox icon-on-right">
			<div class="infobox-icon <?php if( 'icon' == $settings['eael_infobox_img_or_icon'] ) : echo esc_attr( 'eael-icon-only', 'essential-addons-elementor' ); endif; ?>">
				<?php if( 'img' == $settings['eael_infobox_img_or_icon'] ) : ?>
				<figure>
					<img src="<?php echo esc_url( $infobox_image_url ); ?>" alt="Icon Image">
				</figure>
				<?php endif; ?>
				<?php if( 'icon' == $settings['eael_infobox_img_or_icon'] ) : ?>
				<div class="infobox-icon-wrap">
					<i class="<?php echo esc_attr( $settings['eael_infobox_icon'] ); ?>"></i>
				</div>
				<?php endif; ?>
			</div>
			<div class="infobox-content <?php if( 'icon' == $settings['eael_infobox_img_or_icon'] ) : echo esc_attr( 'eael-icon-only', 'essential-addons-elementor' ); endif; ?>">
				<h4 class="title"><?php echo $settings['eael_infobox_title']; ?></h4>
				<?php if( 'yes' == $settings['eael_show_infobox_content'] ) : ?>
					<p><?php echo $settings['eael_infobox_text']; ?></p>
				<?php endif; ?>
			</div>
		</div>
		<?php if( 'yes' == $settings['eael_show_infobox_clickable'] ) : ?></a><?php endif; ?>
		<?php endif; ?>
	<?php
	}

	protected function content_template() {

		?>


		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Info_Box() );
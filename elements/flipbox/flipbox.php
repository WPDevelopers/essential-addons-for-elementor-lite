<?php 
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Widget_Eael_Flip_Box extends Widget_Base {

	public function get_name() {
		return 'eael-flip-box';
	}

	public function get_title() {
		return esc_html__( 'EA Flip Box', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-flip-box';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
	
	protected function _register_controls() {

  		/**
  		 * Flipbox Image Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_flipbox_content_settings',
  			[
  				'label' => esc_html__( 'Flipbox Settings', 'essential-addons-elementor' )
  			]
  		);

  		$this->add_control(
		  'eael_flipbox_type',
		  	[
		   	'label'       	=> esc_html__( 'Flipbox Type', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'animate-left',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'animate-left'  		=> esc_html__( 'Flip Left', 'essential-addons-elementor' ),
		     		'animate-right' 		=> esc_html__( 'Flip Right', 'essential-addons-elementor' ),
		     		'animate-up' 			=> esc_html__( 'Flip Top', 'essential-addons-elementor' ),
		     		'animate-down' 		=> esc_html__( 'Flip Bottom', 'essential-addons-elementor' ),
		     		'animate-zoom-in' 	=> esc_html__( 'Zoom In', 'essential-addons-elementor' ),
		     		'animate-zoom-out' 	=> esc_html__( 'Zoom Out', 'essential-addons-elementor' ),
		     	],
		  	]
		);

		$this->add_responsive_control(
			'eael_flipbox_img_or_icon',
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
		 * Condition: 'eael_flipbox_img_or_icon' => 'img'
		 */
		$this->add_control(
			'eael_flipbox_image',
			[
				'label' => esc_html__( 'Flipbox Image', 'essential-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'eael_flipbox_img_or_icon' => 'img'
				]
			]
		);

		$this->add_control(
			'eael_flipbox_image_resizer',
			[
				'label' => esc_html__( 'Image Resizer', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => '100'
				],
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-elements-flip-box-icon-image img' => 'width: {{SIZE}}px;',
				],
				'condition' => [
					'eael_flipbox_img_or_icon' => 'img'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'full',
				'condition' => [
					'eael_flipbox_image[url]!' => '',
				],
				'condition' => [
					'eael_flipbox_img_or_icon' => 'img'
				]
			]
		);
		/**
		 * Condition: 'eael_flipbox_img_or_icon' => 'icon'
		 */
		$this->add_control(
			'eael_flipbox_icon',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-snowflake-o',
				'condition' => [
					'eael_flipbox_img_or_icon' => 'icon'
				]
			]
		);

		$this->end_controls_section();

		/**
		 * Flipbox Content
		 */
		$this->start_controls_section( 
			'eael_flipbox_content',
			[
				'label' => esc_html__( 'Flipbox Content', 'essential-addons-elementor' ),
			]
		);
		$this->add_responsive_control(
			'eael_flipbox_front_or_back_content',
			[
				'label' => esc_html__( 'Front or Back Content', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'front' => [
						'title' => esc_html__( 'Front Content', 'essential-addons-elementor' ),
						'icon' => 'fa fa-reply',
					],
					'back' => [
						'title' => esc_html__( 'Back Content', 'essential-addons-elementor' ),
						'icon' => 'fa fa-share',
					],
				],
				'default' => 'front',
			]
		);
		/**
		 * Condition: 'eael_flipbox_front_or_back_content' => 'front'
		 */
		$this->add_control( 
			'eael_flipbox_front_title',
			[
				'label' => esc_html__( 'Front Title', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Elementor Flipbox', 'essential-addons-elementor' ),
				'condition' => [
					'eael_flipbox_front_or_back_content' => 'front'
				]
			]
		);
		$this->add_control( 
			'eael_flipbox_front_text',
			[
				'label' => esc_html__( 'Front Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'This is front-end content.', 'essential-addons-elementor' ),
				'condition' => [
					'eael_flipbox_front_or_back_content' => 'front'
				]
			]
		);
		/**
		 * Condition: 'eael_flipbox_front_or_back_content' => 'back'
		 */
		$this->add_control( 
			'eael_flipbox_back_title',
			[
				'label' => esc_html__( 'Back Title', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Elementor Flipbox', 'essential-addons-elementor' ),
				'condition' => [
					'eael_flipbox_front_or_back_content' => 'back'
				]
			]
		);
		$this->add_control( 
			'eael_flipbox_back_text',
			[
				'label' => esc_html__( 'Back Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'This is back-end content.', 'essential-addons-elementor' ),
				'condition' => [
					'eael_flipbox_front_or_back_content' => 'back'
				]
			]
		);
		$this->add_responsive_control(
			'eael_flipbox_content_alignment',
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
				'prefix_class' => 'eael-flipbox-content-align-',
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
		 * Tab Style (Flipbox Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_flipbox_style_settings',
			[
				'label' => esc_html__( 'Filp Box Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_flipbox_front_bg_color',
			[
				'label' => esc_html__( 'Front Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#14bcc8',
				'selectors' => [
					'{{WRAPPER}} .eael-elements-flip-box-front-container' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_flipbox_back_bg_color',
			[
				'label' => esc_html__( 'Back Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff7e70',
				'selectors' => [
					'{{WRAPPER}} .eael-elements-flip-box-rear-container' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_flipbox_container_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-elements-progression-flip-box-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_flipbox_front_back_padding',
			[
				'label' => esc_html__( 'Fornt / Back Content Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-elements-flip-box-front-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 					'{{WRAPPER}} .eael-elements-flip-box-rear-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_flipbox_container_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-elements-progression-flip-box-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
				[
					'name' => 'eael_filbpox_border',
					'label' => esc_html__( 'Border Style', 'essential-addons-elementor' ),
					'selectors' => ['{{WRAPPER}} .eael-elements-flip-box-front-container', '{{WRAPPER}} .eael-elements-flip-box-rear-container'],
				]
		);

		$this->add_control(
			'eael_flipbox_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-elements-progression-flip-box-container' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_flipbox_shadow',
				'selector' => '{{WRAPPER}} .eael-elements-progression-flip-box-container',
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Flip Box Image)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_flipbox_image_style_settings',
			[
				'label' => esc_html__( 'Image Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
		     		'eael_flipbox_img_or_icon' => 'img'
		     	]
			]
		);

		$this->add_control(
		  'eael_flipbox_img_type',
		  	[
		   	'label'       	=> esc_html__( 'Image Type', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'default',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'circle'  	=> esc_html__( 'Circle', 'essential-addons-elementor' ),
		     		'radius' 	=> esc_html__( 'Radius', 'essential-addons-elementor' ),
		     		'default' 	=> esc_html__( 'Default', 'essential-addons-elementor' ),
		     	],
		     	'prefix_class' => 'eael-flipbox-img-',
		     	'condition' => [
		     		'eael_flipbox_img_or_icon' => 'img'
		     	]
		  	]
		);

		/**
		 * Condition: 'eael_flipbox_img_type' => 'radius'
		 */
		$this->add_control(
			'eael_filpbox_img_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-elements-flip-box-icon-image img' => 'border-radius: {{SIZE}}px;',
				],
				'condition' => [
					'eael_flipbox_img_or_icon' => 'img',
					'eael_flipbox_img_type' => 'radius'
				]
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Flip Box Icon Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_flipbox_icon_style_settings',
			[
				'label' => esc_html__( 'Icon Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
		     		'eael_flipbox_img_or_icon' => 'icon'
		     	]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
				[
					'name' => 'eael_flipbox_border',
					'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
					'selector' => '{{WRAPPER}} .eael-elements-flip-box-icon-image',
					'condition' => [
						'eael_flipbox_img_or_icon' => 'icon'
					]
				]
		);

		$this->add_control(
			'eael_flipbox_icon_border_padding',
			[
				'label' => esc_html__( 'Border Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-elements-flip-box-icon-image' => 'padding: {{SIZE}}px;',
				],
				'condition' => [
					'eael_flipbox_img_or_icon' => 'icon'
				]
			]
		);

		$this->add_control(
			'eael_flipbox_icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-elements-flip-box-icon-image' => 'border-radius: {{SIZE}}px;',
				],
				'condition' => [
					'eael_flipbox_img_or_icon' => 'icon'
				]
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Flip Box Title Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_flipbox_title_style_settings',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'eael_flipbox_front_back_content_toggler',
			[
				'label' => esc_html__( 'Front or Rear Content', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'front' => [
						'title' => esc_html__( 'Front Content', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-left',
					],
					'back' => [
						'title' => esc_html__( 'Rear Content', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-right',
					],
				],
				'default' => 'front',
			]
		);
		
		$this->add_control(
			'eael_flipbox_front_title_heading',
			[
				'label' => esc_html__( 'Title Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		/**
		 * Condition: 'eael_flipbox_front_back_content_toggler' => 'front'
		 */
		$this->add_control(
			'eael_flipbox_front_title_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-heading' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_flipbox_front_back_content_toggler' => 'front'
				]
			]
		);

		/**
		 * Condition: 'eael_flipbox_front_back_content_toggler' => 'back'
		 */
		$this->add_control(
			'eael_flipbox_back_title_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-heading' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_flipbox_front_back_content_toggler' => 'back'
				]
			]
		);

		/**
		 * Condition: 'eael_flipbox_front_back_content_toggler' => 'front'
		 */
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            	'name' => 'eael_flipbox_front_title_typography',
				'selector' => '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-heading',
				'condition' => [
					'eael_flipbox_front_back_content_toggler' => 'front'
				],
			]
		);

		/**
		 * Condition: 'eael_flipbox_front_back_content_toggler' => 'back'
		 */
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            	'name' => 'eael_flipbox_back_title_typography',
				'selector' => '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-heading',
				'condition' => [
					'eael_flipbox_front_back_content_toggler' => 'back'
				],
			]
		);

		/**
		 * Content
		 */
		$this->add_control(
			'eael_flipbox_content_heading',
			[
				'label' => esc_html__( 'Content Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		/**
		 * Condition: 'eael_flipbox_front_back_content_toggler' => 'front'
		 */
		$this->add_control(
			'eael_flipbox_front_content_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-content' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_flipbox_front_back_content_toggler' => 'front'
				]
			]
		);

		/**
		 * Condition: 'eael_flipbox_front_back_content_toggler' => 'back'
		 */
		$this->add_control(
			'eael_flipbox_back_content_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-content' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_flipbox_front_back_content_toggler' => 'back'
				]
			]
		);

		/**
		 * Condition: 'eael_flipbox_front_back_content_toggler' => 'front'
		 */
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            	'name' => 'eael_flipbox_front_content_typography',
				'selector' => '{{WRAPPER}} .eael-elements-flip-box-front-container .eael-elements-flip-box-content',
				'condition' => [
					'eael_flipbox_front_back_content_toggler' => 'front'
				]
			]
		);

		/**
		 * Condition: 'eael_flipbox_front_back_content_toggler' => 'back'
		 */
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            	'name' => 'eael_flipbox_back_content_typography',
				'selector' => '{{WRAPPER}} .eael-elements-flip-box-rear-container .eael-elements-flip-box-content',
				'condition' => [
					'eael_flipbox_front_back_content_toggler' => 'back'
				]
			]
		);
		
		$this->end_controls_section();

	}


	protected function render( ) {
		
   		$settings = $this->get_settings();
      	$flipbox_image = $this->get_settings( 'eael_flipbox_image' );
	  	$flipbox_image_url = Group_Control_Image_Size::get_attachment_image_src( $flipbox_image['id'], 'thumbnail', $settings );
	  	if( empty( $flipbox_image_url ) ) : $flipbox_image_url = $flipbox_image['url']; else: $flipbox_image_url = $flipbox_image_url; endif;

	?>
	
	<div class="eael-elements-progression-flip-box-container eael-animate-flip eael-<?php echo esc_attr( $settings['eael_flipbox_type'] ); ?>">
	    <div class="eael-elements-flip-box-flip-card">
	        <div class="eael-elements-flip-box-front-container">
	            <div class="eael-elements-slider-display-table">
	                <div class="eael-elements-flip-box-vertical-align">
	                    <div class="eael-elements-flip-box-padding">
	                        <div class="eael-elements-flip-box-icon-image">
	                        	<?php if( 'icon' === $settings['eael_flipbox_img_or_icon'] ) : ?>
	                           	<i class="<?php echo esc_attr( $settings['eael_flipbox_icon'] ); ?>"></i>
	                           <?php elseif( 'img' === $settings['eael_flipbox_img_or_icon'] ): ?>
	                           	<img src="<?php echo esc_url( $flipbox_image_url ); ?>" alt="">
	                           <?php endif; ?>
	                        </div>
	                        <h2 class="eael-elements-flip-box-heading"><?php echo esc_html__( $settings['eael_flipbox_front_title'], 'essential-addons-elementor' ); ?></h2>
	                        <div class="eael-elements-flip-box-content">
	                           <p><?php echo __( $settings['eael_flipbox_front_text'], 'essential-addons-elementor' ); ?></p>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="eael-elements-flip-box-rear-container">
	            <div class="eael-elements-slider-display-table">
	                <div class="eael-elements-flip-box-vertical-align">
	                    <div class="eael-elements-flip-box-padding">
	                        <h2 class="eael-elements-flip-box-heading"><?php echo esc_html__( $settings['eael_flipbox_back_title'], 'essential-addons-elementor' ); ?></h2>
	                        <div class="eael-elements-flip-box-content">
	                           <p><?php echo __( $settings['eael_flipbox_back_text'], 'essential-addons-elementor' ); ?></p>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<?php
	}

	protected function content_template() {
		
		?>
		
	
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Flip_Box() );
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
		return 'fa fa-newspaper-o';
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
		     	'default' 		=> 'vf-right-to-left',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'vf-left-to-right'  	=> esc_html__( 'Vertical Flip ( Left to Right )', 'essential-addons-elementor' ),
		     		'vf-right-to-left' 	=> esc_html__( 'Vertical Flip ( Right to Left )', 'essential-addons-elementor' ),
		     		'hf-top-to-bottom' 	=> esc_html__( 'Horizontal Flip ( Top to Bottom )', 'essential-addons-elementor' ),
		     		'hf-bottom-to-top' 	=> esc_html__( 'Horizontal Flip ( Bottom to Top )', 'essential-addons-elementor' ),
		     	],
		  	]
		);

		$this->add_responsive_control(
			'eael_flipbox_img_or_icon',
			[
				'label' => esc_html__( 'Image Or Icon', 'essential-addons-elementor' ),
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
				'default' => 'img',
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
				'default' => 'left',
				'prefix_class' => 'eael-flipbox-content-align-',
			]
		);
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Info Box Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_flipbox_style_settings',
			[
				'label' => esc_html__( 'Filp Box Styles', 'essential-addons-elementor' ),
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
					'{{WRAPPER}} .eael-flipbox .eael-vertical-flip .front' => 'background: {{VALUE}};',
					'{{WRAPPER}} .eael-flipbox .eael-horizontal-flip .front' => 'background: {{VALUE}};',
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
					'{{WRAPPER}} .eael-flipbox .eael-vertical-flip .back' => 'background: {{VALUE}};',
					'{{WRAPPER}} .eael-flipbox .eael-horizontal-flip .back' => 'background: {{VALUE}};',
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
	 					'{{WRAPPER}} .eael-flipbox' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_flipbox_front_back_padding',
			[
				'label' => esc_html__( 'Fornt / Back Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-flipbox .front' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 					'{{WRAPPER}} .eael-flipbox .back' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	 					'{{WRAPPER}} .eael-flipbox .front' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 					'{{WRAPPER}} .eael-flipbox .back' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_control(
			'eael_flipbox_border_type',
			[
				'label' => esc_html__( 'Border Type', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' 	=> esc_html__( 'None', 'essential-addons-elementor' ),
					'solid' 	=> esc_html__( 'Solid', 'essential-addons-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'essential-addons-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'essential-addons-elementor' ),
					'double' => esc_html__( 'Double', 'essential-addons-elementor' ),
				],
				'selectors' => [
	 					'{{WRAPPER}} .eael-flipbox .front' => 'border-style: {{VALUE}};',
	 					'{{WRAPPER}} .eael-flipbox .back' => 'border-style: {{VALUE}};',
	 			],
			]
		);

		$this->add_control(
			'eael_flipbox_border_thickness',
			[
				'label' => esc_html__( 'Border Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-flipbox .front' => 'border-width: {{SIZE}}px;',
					'{{WRAPPER}} .eael-flipbox .back' => 'border-width: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_flipbox_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-flipbox .front' => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .eael-flipbox .back' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_flipbox_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .eael-flipbox .front' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .eael-flipbox .back' => 'border-color: {{VALUE}};',
				],
			]

		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_flipbox_shadow',
				'selector' => '{{WRAPPER}} .eael-flipbox',
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Flip Box Image)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_flipbox_imgae_style_settings',
			[
				'label' => esc_html__( 'Image Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
		     		'eael_flipbox_img_or_icon' => 'img'
		     	]
			]
		);

		$this->add_control(
		  'eael_flipbox_img_shape',
		  	[
		   	'label'     	=> esc_html__( 'Image Shape', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'circle',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'square'  	=> esc_html__( 'Square', 'essential-addons-elementor' ),
		     		'circle' 	=> esc_html__( 'Circle', 'essential-addons-elementor' ),
		     		'radius' 	=> esc_html__( 'Radius', 'essential-addons-elementor' ),
		     	],
		     	'prefix_class' => 'eael-flipbox-shape-',
		     	'condition' => [
		     		'eael_flipbox_img_or_icon' => 'img'
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

		$this->add_control(
    		'eael_flipbox_icon_size',
    		[
        		'label' => __( 'Icon Size', 'essential-addons-elementor' ),
       		'type' => Controls_Manager::SLIDER,
        		'default' => [
            	'size' => 80,
        		],
        		'range' => [
            	'px' => [
                	'min' => 20,
                	'max' => 120,
                	'step' => 1,
            	]
        		],
        		'selectors' => [
            	'{{WRAPPER}} .eael-vertical-flip .front .content i' => 'font-size: {{SIZE}}px;',
            	'{{WRAPPER}} .eael-horizontal-flip .front .content i' => 'font-size: {{SIZE}}px;',
        		],
    		]
		);

		$this->add_control(
			'eael_flipbox_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-vertical-flip .front .content i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-horizontal-flip .front .content i' => 'color: {{VALUE}};',
				],
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
				'label' => esc_html__( 'Title Typography &amp; Color', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'eael_flipbox_front_or_back_title_typo',
			[
				'label' => esc_html__( 'Front or Back Title', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'front' => [
						'title' => esc_html__( 'Front Title', 'essential-addons-elementor' ),
						'icon' => 'fa fa-reply',
					],
					'back' => [
						'title' => esc_html__( 'Back Title', 'essential-addons-elementor' ),
						'icon' => 'fa fa-share',
					],
				],
				'default' => 'front',
			]
		);
		/**
		 * Condition: 'eael_flipbox_front_or_back_title_typo' => 'front'
		 */
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            'name' => 'eael_flipbox_front_title_typography',
				'selector' => '{{WRAPPER}} .eael-flipbox .front .content .title',
				'condition' => [
					'eael_flipbox_front_or_back_title_typo' => 'front'
				]
			]
		);

		$this->add_control(
			'eael_flipbox_front_title_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-flipbox .front .content .title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_flipbox_front_or_back_title_typo' => 'front'
				]
			]
		);
		/**
		 * Condition: 'eael_flipbox_front_or_back_title_typo' => 'back'
		 */
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            'name' => 'eael_flipbox_back_title_typography',
				'selector' => '{{WRAPPER}} .eael-flipbox .back .content .title',
				'condition' => [
					'eael_flipbox_front_or_back_title_typo' => 'back'
				]
			]
		);

		$this->add_control(
			'eael_flipbox_back_title_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-flipbox .back .content .title' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_flipbox_front_or_back_title_typo' => 'back'
				]
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Flip Box Content Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_flipbox_content_style_settings',
			[
				'label' => esc_html__( 'Content Typography &amp; Color', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_responsive_control(
			'eael_flipbox_front_or_back_typography',
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
		 * Condition: 'eael_flipbox_front_or_back_typography' => 'front'
		 */
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            'name' => 'eael_flipbox_front_content_typography',
				'selector' => '{{WRAPPER}} .eael-flipbox .front .content',
				'condition' => [
					'eael_flipbox_front_or_back_typography' => 'front'
				]
			]
		);

		$this->add_control(
			'eael_flipbox_front_content_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-flipbox .front .content' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_flipbox_front_or_back_typography' => 'front'
				]
			]
		);

		/**
		 * Condition: 'eael_flipbox_front_or_back_typography' => 'back'
		 */
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            'name' => 'eael_flipbox_back_content_typography',
				'selector' => '{{WRAPPER}} .eael-flipbox .back .content',
				'condition' => [
					'eael_flipbox_front_or_back_typography' => 'back'
				]
			]
		);

		$this->add_control(
			'eael_flipbox_back_content_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-flipbox .back .content' => 'color: {{VALUE}};',
				],
				'condition' => [
					'eael_flipbox_front_or_back_typography' => 'back'
				]
			]
		);
		
		$this->end_controls_section();

	}


	protected function render( ) {
		
   	$settings = $this->get_settings();
      $flipbox_image = $this->get_settings( 'eael_flipbox_image' );
	  	$flipbox_image_url = Group_Control_Image_Size::get_attachment_image_src( $flipbox_image['id'], 'thumbnail', $settings );	
	
		if( 'vf-right-to-left' == $settings['eael_flipbox_type'] ) {
			$flip_class = 'eael-vertical-flip flip-left';
		}else if( 'vf-left-to-right' == $settings['eael_flipbox_type'] ) {
			$flip_class = 'eael-vertical-flip flip-right';
		}else if( 'hf-top-to-bottom' == $settings['eael_flipbox_type'] ) {
			$flip_class = 'eael-horizontal-flip flip-bottom';
		}else if( 'hf-bottom-to-top' == $settings['eael_flipbox_type'] ) {
			$flip_class = 'eael-horizontal-flip flip-top';
		}
	?>
	<div class="eael-flipbox">
		<div class="<?php echo esc_attr( $flip_class ); ?>">
		    <div class="front">
		        <div class="content">
		        		<?php if( 'img' == $settings['eael_flipbox_img_or_icon'] ) : ?>
		        		<img src="<?php echo esc_url( $flipbox_image_url ); ?>" alt="Icon Image">
		        		<?php endif; ?>
		        		<?php if( 'icon' == $settings['eael_flipbox_img_or_icon'] ) : ?>
		            <i class="<?php echo esc_attr( $settings['eael_flipbox_icon'] ); ?>"></i>
		         	<?php endif; ?>
		            <h4 class="title"><?php echo $settings['eael_flipbox_front_title']; ?></h4>
		            <?php if( $settings['eael_flipbox_front_text'] != '' ) : ?>
		            <p><?php echo $settings['eael_flipbox_front_text']; ?></p>
		         	<?php endif; ?>
		        </div>
		    </div>
		    <div class="back">
		        <div class="content">
		            <h4 class="title"><?php echo $settings['eael_flipbox_back_title']; ?></h4>
		            <?php if( $settings['eael_flipbox_back_text'] != '' ) : ?>
		            <p><?php echo $settings['eael_flipbox_back_text']; ?></p>
		         	<?php endif; ?>
		        </div>
		    </div>
		</div>
	</div>	
	<?php if( 'vf-right-to-left' == $settings['eael_flipbox_type'] || 'vf-left-to-right' == $settings['eael_flipbox_type'] ) : ?>
	<script>
		jQuery(document).ready( function($) {
			$(".eael-vertical-flip").hover( function() {
  				$(this).addClass( 'flip' );
  			}, function() {
  				$(this).removeClass( 'flip' );
  			} );
		} );
	</script>
	<?php endif; ?>	
	<?php if( 'hf-top-to-bottom' == $settings['eael_flipbox_type'] || 'hf-bottom-to-top' == $settings['eael_flipbox_type'] ) : ?>
	<script>
		jQuery(document).ready( function($) {
			$(".eael-horizontal-flip").hover( function() {
  				$(this).addClass( 'flip' );
  			}, function() {
  				$(this).removeClass( 'flip' );
  			} );
		} );
	</script>
	<?php endif; ?>
	<?php
	}

	protected function content_template() {
		
		?>
		
	
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Flip_Box() );
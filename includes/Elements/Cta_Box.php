<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Utils;
use \Elementor\Widget_Base;

class Cta_Box extends Widget_Base {
	use \Essential_Addons_Elementor\Traits\Helper;

	public function get_name() {
		return 'eael-cta-box';
	}

	public function get_title() {
		return esc_html__( 'EA Call to Action', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {

  		/**
  		 * Call to Action Content Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_cta_content_settings',
  			[
  				'label' => esc_html__( 'Content Settings', 'essential-addons-elementor' )
  			]
  		);

  		$this->add_control(
		  'eael_cta_type',
		  	[
		   	'label'       	=> esc_html__( 'Content Style', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'cta-basic',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'cta-basic'  		=> esc_html__( 'Basic', 'essential-addons-elementor' ),
		     		'cta-flex' 			=> esc_html__( 'Flex Grid', 'essential-addons-elementor' ),
		     		'cta-icon-flex' 	=> esc_html__( 'Flex Grid with Icon', 'essential-addons-elementor' ),
		     	],
		  	]
		);

  		/**
  		 * Condition: 'eael_cta_type' => 'cta-basic'
  		 */
		$this->add_control(
		  'eael_cta_content_type',
		  	[
		   	'label'       	=> esc_html__( 'Content Type', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'cta-default',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'cta-default'  	=> esc_html__( 'Left', 'essential-addons-elementor' ),
		     		'cta-center' 		=> esc_html__( 'Center', 'essential-addons-elementor' ),
		     		'cta-right' 		=> esc_html__( 'Right', 'essential-addons-elementor' ),
		     	],
		     	'condition'    => [
		     		'eael_cta_type' => 'cta-basic'
		     	]
		  	]
		);

		$this->add_control(
		  'eael_cta_color_type',
		  	[
		   	'label'       	=> esc_html__( 'Color Style', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'cta-bg-color',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'cta-bg-color'  		=> esc_html__( 'Background Color', 'essential-addons-elementor' ),
		     		'cta-bg-img' 			=> esc_html__( 'Background Image', 'essential-addons-elementor' ),
		     		'cta-bg-img-fixed' 	=> esc_html__( 'Background Fixed Image', 'essential-addons-elementor' ),
		     	],
		  	]
		);

		/**
		 * Condition: 'eael_cta_type' => 'cta-icon-flex'
		 */
		$this->add_control(
			'eael_cta_flex_grid_icon_new',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'eael_cta_flex_grid_icon',
				'default' => [
					'value' => 'fas fa-bullhorn',
					'library' => 'fa-solid',
				],
				'condition' => [
					'eael_cta_type' => 'cta-icon-flex'
				]
			]
		);

		$this->add_control(
			'eael_cta_title',
			[
				'label' => esc_html__( 'Title', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'The Ultimate Addons For Elementor', 'essential-addons-elementor' ),
				'dynamic' => [ 'active' => true ]
			]
		);
		$this->add_control(
            'eael_cta_title_content_type',
            [
                'label'                 => __( 'Content Type', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                    'content'       => __( 'Content', 'essential-addons-elementor' ),
                    'template'      => __( 'Saved Templates', 'essential-addons-elementor' ),
                ],
                'default'               => 'content',
            ]
        );

        $this->add_control(
            'eael_primary_templates',
            [
                'label'                 => __( 'Choose Template', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => $this->eael_get_page_templates(),
				'condition'             => [
					'eael_cta_title_content_type'      => 'template',
				],
            ]
        );
		$this->add_control(
			'eael_cta_content',
			[
				'label' => esc_html__( 'Content', 'essential-addons-elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default' => esc_html__( 'Add a strong one liner supporting the heading above and giving users a reason to click on the button below.', 'essential-addons-elementor' ),
				'separator' => 'after',
				'condition' => [
					'eael_cta_title_content_type' => 'content'
				]
			]
		);

		$this->add_control(
			'eael_cta_btn_text',
			[
				'label' => esc_html__( 'Button Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Button Text', 'essential-addons-elementor' )
			]
		);

		$this->add_control(
			'eael_cta_btn_link',
			[
				'label' => esc_html__( 'Button Link', 'essential-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'default' => [
        			'url' => 'http://',
        			'is_external' => '',
     			],
     			'show_external' => true,
     			'separator' => 'after'
			]
		);

		/**
		 * Condition: 'eael_cta_color_type' => 'cta-bg-img' && 'eael_cta_color_type' => 'cta-bg-img-fixed',
		 */
		$this->add_control(
			'eael_cta_bg_image',
			[
				'label' => esc_html__( 'Background Image', 'essential-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'selectors' => [
            	'{{WRAPPER}} .eael-call-to-action.bg-img' => 'background-image: url({{URL}});',
            	'{{WRAPPER}} .eael-call-to-action.bg-img-fixed' => 'background-image: url({{URL}});',
        		],
				'condition' => [
					'eael_cta_color_type' => [ 'cta-bg-img', 'cta-bg-img-fixed' ],
				]
			]
		);

		$this->end_controls_section();

		if(!apply_filters('eael/pro_enabled', false)) {
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
					'description' => '<span class="pro-feature"> Get the  <a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank">Pro version</a> for more stunning elements and customization options.</span>'
				]
			);
			
			$this->end_controls_section();
		}

		/**
		 * -------------------------------------------
		 * Tab Style (Cta Title Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_cta_style_settings',
			[
				'label' => esc_html__( 'Call to Action Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_cta_container_width',
			[
				'label' => esc_html__( 'Set max width for the container?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'eael_cta_container_width_value',
			[
				'label' => __( 'Container Max Width (% or px)', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1170,
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%' ],
				'range' => [
		            'px' => [
		                'min' => 0,
		                'max' => 1500,
		                'step' => 5,
		            ],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-call-to-action' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'eael_cta_container_width' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_cta_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f4f4f4',
				'selectors' => [
					'{{WRAPPER}} .eael-call-to-action' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_cta_container_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-call-to-action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_cta_container_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-call-to-action' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_cta_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-call-to-action',
			]
		);

		$this->add_control(
			'eael_cta_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-call-to-action' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_cta_shadow',
				'selector' => '{{WRAPPER}} .eael-call-to-action',
			]
		);


		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Cta Title Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_cta_title_style_settings',
			[
				'label' => esc_html__( 'Color &amp; Typography ', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_cta_title_heading',
			[
				'label' => esc_html__( 'Title Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_cta_title_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-call-to-action .title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_cta_title_typography',
				'selector' => '{{WRAPPER}} .eael-call-to-action .title',
			]
		);

		$this->add_control(
			'eael_cta_content_heading',
			[
				'label' => esc_html__( 'Content Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_cta_content_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-call-to-action p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_cta_content_typography',
				'selector' => '{{WRAPPER}} .eael-call-to-action p',
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Button Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_cta_btn_style_settings',
			[
				'label' => esc_html__( 'Button Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
		  'eael_cta_btn_effect_type',
		  	[
		   	'label'       	=> esc_html__( 'Effect', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'default',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'default'  			=> esc_html__( 'Default', 'essential-addons-elementor' ),
		     		'top-to-bottom'  	=> esc_html__( 'Top to Bottom', 'essential-addons-elementor' ),
		     		'left-to-right'  	=> esc_html__( 'Left to Right', 'essential-addons-elementor' ),
		     	],
		  	]
		);

		$this->add_responsive_control(
			'eael_cta_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-call-to-action .cta-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_cta_btn_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-call-to-action .cta-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
	         'name' => 'eael_cta_btn_typography',
				'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button',
			]
		);

		$this->start_controls_tabs( 'eael_cta_button_tabs' );

			// Normal State Tab
			$this->start_controls_tab( 'eael_cta_btn_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

			$this->add_control(
				'eael_cta_btn_normal_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#4d4d4d',
					'selectors' => [
						'{{WRAPPER}} .eael-call-to-action .cta-button' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_cta_btn_normal_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#f9f9f9',
					'selectors' => [
						'{{WRAPPER}} .eael-call-to-action .cta-button' => 'background: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'eael_cat_btn_normal_border',
					'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
					'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button',
				]
			);

			$this->add_control(
				'eael_cta_btn_border_radius',
				[
					'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .eael-call-to-action .cta-button' => 'border-radius: {{SIZE}}px;',
					],
				]
			);

			$this->end_controls_tab();

			// Hover State Tab
			$this->start_controls_tab( 'eael_cta_btn_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

			$this->add_control(
				'eael_cta_btn_hover_text_color',
				[
					'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#f9f9f9',
					'selectors' => [
						'{{WRAPPER}} .eael-call-to-action .cta-button:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_cta_btn_hover_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#3F51B5',
					'selectors' => [
						'{{WRAPPER}} .eael-call-to-action .cta-button:after' => 'background: {{VALUE}};',
						'{{WRAPPER}} .eael-call-to-action .cta-button:hover' => 'background: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'eael_cta_btn_hover_border_color',
				[
					'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .eael-call-to-action .cta-button:hover' => 'border-color: {{VALUE}};',
					],
				]

			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_cta_button_shadow',
				'selector' => '{{WRAPPER}} .eael-call-to-action .cta-button',
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Button Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_cta_icon_style_settings',
			[
				'label' => esc_html__( 'Icon Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'eael_cta_type' => 'cta-icon-flex'
				]
			]
		);

		$this->add_control(
			'eael_section_cta_icon_size',
			[
				'label' => esc_html__( 'Font Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 80
				],
				'range' => [
					'px' => [
						'max' => 160,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-call-to-action.cta-icon-flex .icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .eael-call-to-action.cta-icon-flex .icon img' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_section_cta_icon_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#444',
				'selectors' => [
					'{{WRAPPER}} .eael-call-to-action.cta-icon-flex .icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}


	protected function render( ) {

   		$settings = $this->get_settings_for_display();
	  	$target = $settings['eael_cta_btn_link']['is_external'] ? 'target="_blank"' : '';
		$nofollow = $settings['eael_cta_btn_link']['nofollow'] ? 'rel="nofollow"' : '';
		$icon_migrated = isset($settings['__fa4_migrated']['eael_cta_flex_grid_icon_new']);
		$icon_is_new = empty($settings['eael_cta_flex_grid_icon']);  

	  	if( 'cta-bg-color' == $settings['eael_cta_color_type'] ) {
	  		$cta_class = 'bg-lite';
	  	}else if( 'cta-bg-img' == $settings['eael_cta_color_type'] ) {
	  		$cta_class = 'bg-img';
	  	}else if( 'cta-bg-img-fixed' == $settings['eael_cta_color_type'] ) {
	  		$cta_class = 'bg-img bg-fixed';
	  	}else {
	  		$cta_class = '';
	  	}
	  	// Is Basic Cta Content Center or Not
	  	if( 'cta-center' === $settings['eael_cta_content_type'] ) {
	  		$cta_alignment = 'cta-center';
	  	}elseif( 'cta-right' === $settings['eael_cta_content_type'] ) {
	  		$cta_alignment = 'cta-right';
	  	}else {
	  		$cta_alignment = 'cta-left';
	  	}
	  	// Button Effect
	  	if( 'left-to-right' == $settings['eael_cta_btn_effect_type'] ) {
	  		$cta_btn_effect = 'effect-2';
	  	}elseif( 'top-to-bottom' == $settings['eael_cta_btn_effect_type'] ) {
	  		$cta_btn_effect = 'effect-1';
	  	}else {
	  		$cta_btn_effect = '';
	  	}

	?>
	<?php if( 'cta-basic' == $settings['eael_cta_type'] ) : ?>
	<div class="eael-call-to-action <?php echo esc_attr( $cta_class ); ?> <?php echo esc_attr( $cta_alignment ); ?>">
	    <h2 class="title"><?php echo $settings['eael_cta_title']; ?></h2>
	    <?php if( 'content' == $settings['eael_cta_title_content_type'] ) : ?>
	    <p><?php echo $settings['eael_cta_content']; ?></p>
		<?php elseif( 'template' == $settings['eael_cta_title_content_type'] ) : ?>
			<?php
				if ( !empty( $settings['eael_primary_templates'] ) ) {
                    $eael_template_id = $settings['eael_primary_templates'];
                    $eael_frontend = new Frontend;
					echo $eael_frontend->get_builder_content( $eael_template_id, true );
                }
			?>
		<?php endif; ?>
	    <a href="<?php echo esc_url( $settings['eael_cta_btn_link']['url'] ); ?>" <?php echo $target; ?> <?php echo $nofollow; ?> class="cta-button <?php echo esc_attr( $cta_btn_effect ); ?>"><?php esc_html_e( $settings['eael_cta_btn_text'], 'essential-addons-elementor' ); ?></a>
	</div>
	<?php endif; ?>
	<?php if( 'cta-flex' == $settings['eael_cta_type'] ) : ?>
	<div class="eael-call-to-action cta-flex <?php echo esc_attr( $cta_class ); ?>">
	    <div class="content">
	        <h2 class="title"><?php echo $settings['eael_cta_title']; ?></h2>
	        <?php if( 'content' == $settings['eael_cta_title_content_type'] ) : ?>
		    <p><?php echo $settings['eael_cta_content']; ?></p>
			<?php elseif( 'template' == $settings['eael_cta_title_content_type'] ) : ?>
				<?php
					if ( !empty( $settings['eael_primary_templates'] ) ) {
	                    $eael_template_id = $settings['eael_primary_templates'];
	                    $eael_frontend = new Frontend;
						echo $eael_frontend->get_builder_content( $eael_template_id, true );
	                }
				?>
			<?php endif; ?>
	    </div>
	    <div class="action">
	        <a href="<?php echo esc_url( $settings['eael_cta_btn_link']['url'] ); ?>" <?php echo $target; ?> <?php echo $nofollow; ?> class="cta-button <?php echo esc_attr( $cta_btn_effect ); ?>"><?php esc_html_e( $settings['eael_cta_btn_text'], 'essential-addons-elementor' ); ?></a>
	    </div>
	</div>
	<?php endif; ?>
	<?php if( 'cta-icon-flex' == $settings['eael_cta_type'] ) : ?>
	<div class="eael-call-to-action cta-icon-flex <?php echo esc_attr( $cta_class ); ?>">
	    <div class="icon">
			<?php if($icon_is_new || $icon_migrated) { ?>
				<?php if( isset($settings['eael_cta_flex_grid_icon_new']['value']['url']) ) : ?>
					<img src="<?php echo esc_attr( $settings['eael_cta_flex_grid_icon_new']['value']['url'] ); ?>" alt="<?php echo esc_attr(get_post_meta($settings['eael_cta_flex_grid_icon_new']['value']['id'], '_wp_attachment_image_alt', true)); ?>" />
				<?php else : ?>
					<i class="<?php echo esc_attr( $settings['eael_cta_flex_grid_icon_new']['value'] ); ?>"></i>
				<?php endif; ?>
			<?php } else { ?>
				<i class="<?php echo esc_attr( $settings['eael_cta_flex_grid_icon'] ); ?>"></i>
			<?php } ?>
	    </div>
	    <div class="content">
	        <h2 class="title"><?php echo $settings['eael_cta_title']; ?></h2>
	        <?php if( 'content' == $settings['eael_cta_title_content_type'] ) : ?>
		    <p><?php echo $settings['eael_cta_content']; ?></p>
			<?php elseif( 'template' == $settings['eael_cta_title_content_type'] ) : ?>
				<?php
					if ( !empty( $settings['eael_primary_templates'] ) ) {
	                    $eael_template_id = $settings['eael_primary_templates'];
	                    $eael_frontend = new Frontend;
						echo $eael_frontend->get_builder_content( $eael_template_id, true );
	                }
				?>
			<?php endif; ?>
	    </div>
	    <div class="action">
	       <a href="<?php echo esc_url( $settings['eael_cta_btn_link']['url'] ); ?>" <?php echo $target; ?> class="cta-button <?php echo esc_attr( $cta_btn_effect ); ?>"><?php esc_html_e( $settings['eael_cta_btn_text'], 'essential-addons-elementor' ); ?></a>
	    </div>
	</div>
	<?php endif; ?>
	<?php
	}
}
<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Widget_Eael_Dual_Color_Header extends Widget_Base {

	public function get_name() {
		return 'eael-dual-color-header';
	}

	public function get_title() {
		return esc_html__( 'EA Dual Color Header', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'fa fa-header';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
	
	protected function _register_controls() {

  		/**
  		 * Dual Color Heading Content Settings
  		 */
  		$this->start_controls_section(
  			'eael_section_dch_content_settings',
  			[
  				'label' => esc_html__( 'Content Settings', 'essential-addons-elementor' )
  			]
  		);

  		$this->add_control(
		  'eael_dch_type',
		  	[
		   	'label'       	=> esc_html__( 'Content Style', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'dch-default',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'dch-default'  					=> esc_html__( 'Default', 'essential-addons-elementor' ),
		     		'dch-icon-on-top'  				=> esc_html__( 'Icon on top', 'essential-addons-elementor' ),
		     		'dch-icon-subtext-on-top'  	=> esc_html__( 'Icon &amp; sub-text on top', 'essential-addons-elementor' ),
		     		'dch-subtext-on-top'  			=> esc_html__( 'Sub-text on top', 'essential-addons-elementor' ),
		     	],
		  	]
		);

		$this->add_control(
		  'eael_dch_color_type',
		  	[
		   	'label'       	=> esc_html__( 'Color Style', 'essential-addons-elementor' ),
		     	'type' 			=> Controls_Manager::SELECT,
		     	'default' 		=> 'dch-colored',
		     	'label_block' 	=> false,
		     	'options' 		=> [
		     		'dch-basic'  					=> esc_html__( 'Basic', 'essential-addons-elementor' ),
		     		'dch-colored'  				=> esc_html__( 'Colored', 'essential-addons-elementor' ),
		     		'dch-colored-reverse'  		=> esc_html__( 'Reverse Color', 'essential-addons-elementor' ),
		     	],
		  	]
		);

		$this->add_control(
			'eael_show_dch_icon_content',
			[
				'label' => __( 'Show Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', 'essential-addons-elementor' ),
				'label_off' => __( 'Hide', 'essential-addons-elementor' ),
				'return_value' => 'yes',
				'separator' => 'after',
			]
		);
		/**
		 * Condition: 'eael_show_dch_icon_content' => 'yes'
		 */
		$this->add_control(
			'eael_dch_icon',
			[
				'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::ICON,
				'default' => 'fa fa-snowflake-o',
				'condition' => [
					'eael_show_dch_icon_content' => 'yes'
				]
			]
		);

		$this->add_control( 
			'eael_dch_first_title',
			[
				'label' => esc_html__( 'Title ( First Part )', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Dual Heading', 'essential-addons-elementor' )
			]
		);

		$this->add_control( 
			'eael_dch_last_title',
			[
				'label' => esc_html__( 'Title ( Last Part )', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Example', 'essential-addons-elementor' )
			]
		);

		$this->add_control( 
			'eael_dch_subtext',
			[
				'label' => esc_html__( 'Sub Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default' => esc_html__( 'Insert a meaningful line to evaluate the headline.', 'essential-addons-elementor' )
			]
		);

		$this->add_responsive_control(
			'eael_dch_content_alignment',
			[
				'label' => esc_html__( 'Alignment', 'essential-addons-elementor' ),
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
				'prefix_class' => 'eael-dual-header-content-align-'
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style ( Dual Heading Style )
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_dch_style_settings',
			[
				'label' => esc_html__( 'Dual Heading Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_dch_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-dual-header' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_dch_container_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-dual-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_dch_container_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-dual-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_control(
			'eael_dch_border_type',
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
	 					'{{WRAPPER}} .eael-dual-header' => 'border-style: {{VALUE}};',
	 			],
			]
		);

		$this->add_control(
			'eael_dch_border_thickness',
			[
				'label' => esc_html__( 'Border Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-dual-header' => 'border-width: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_dch_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-dual-header' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_dch_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .eael-dual-header' => 'border-color: {{VALUE}};',
				],
			]

		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_dch_shadow',
				'selector' => '{{WRAPPER}} .eael-dual-header',
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Icon Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_dch_icon_style_settings',
			[
				'label' => esc_html__( 'Icon Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
		     		'eael_show_dch_icon_content' => 'yes'
		     	]
			]
		);

		$this->add_control(
    		'eael_dch_icon_size',
    		[
        		'label' => __( 'Icon Size', 'essential-addons-elementor' ),
       		'type' => Controls_Manager::SLIDER,
        		'default' => [
            	'size' => 36,
        		],
        		'range' => [
            	'px' => [
                	'min' => 20,
                	'max' => 100,
                	'step' => 1,
            	]
        		],
        		'selectors' => [
            	'{{WRAPPER}} .eael-dual-header i' => 'font-size: {{SIZE}}px;',
        		],
    		]
		);

		$this->add_control(
			'eael_dch_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4d4d4d',
				'selectors' => [
					'{{WRAPPER}} .eael-dual-header i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Title Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_dch_title_style_settings',
			[
				'label' => esc_html__( 'Title Style &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            'name' => 'eael_dch_first_title_typography',
				'selector' => '{{WRAPPER}} .eael-dual-header .title, .eael-dual-header .title span.lead',
			]
		);

		$this->add_control(
			'eael_dch_base_title_color',
			[
				'label' => esc_html__( 'Base Title Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4d4d4d',
				'selectors' => [
					'{{WRAPPER}} .eael-dual-header .title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_dch_dual_title_color',
			[
				'label' => esc_html__( 'Dual Title Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#1abc9c',
				'selectors' => [
					'{{WRAPPER}} .eael-dual-header.dh-colored .title span.lead, {{WRAPPER}} .eael-dual-header.dh-colored-reverse .title span.lead' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style (Subtext Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_dch_subtext_style_settings',
			[
				'label' => esc_html__( 'Subtext Style &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            'name' => 'eael_dch_subtext_typography',
				'selector' => '{{WRAPPER}} .eael-dual-header .subtext',
			]
		);

		$this->add_control(
			'eael_dch_subtext_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4d4d4d',
				'selectors' => [
					'{{WRAPPER}} .eael-dual-header .subtext' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

	}


	protected function render( ) {
		
   	$settings = $this->get_settings();

   	if( 'dch-basic' == $settings['eael_dch_color_type'] ) {
   		$dch_color_class = 'dh-basic';
   	}else if( 'dch-colored' == $settings['eael_dch_color_type'] ) {
   		$dch_color_class = 'dh-colored';
   	}else if( 'dch-colored-reverse' == $settings['eael_dch_color_type'] ) {
   		$dch_color_class = 'dh-colored-reverse';
   	}
	?>
	<?php if( 'dch-default' == $settings['eael_dch_type'] ) : ?>
	<div class="eael-dual-header <?php echo esc_attr( $dch_color_class ); ?>">
		<?php if( 'dch-colored' == $settings['eael_dch_color_type'] ) : ?>
	   	<h2 class="title"><span class="lead"><?php esc_html_e( $settings['eael_dch_first_title'], 'essential-addons-elementor' ); ?></span> <?php esc_html_e( $settings['eael_dch_last_title'], 'essential-addons-elementor' ); ?></h2>
		<?php elseif( 'dch-colored-reverse' == $settings['eael_dch_color_type'] ) : ?>
			<h2 class="title"><?php esc_html_e( $settings['eael_dch_first_title'], 'essential-addons-elementor' ); ?><span class="lead"> <?php esc_html_e( $settings['eael_dch_last_title'], 'essential-addons-elementor' ); ?></span></h2>	
		<?php else: ?>
			<h2 class="title"><span class="lead"><?php esc_html_e( $settings['eael_dch_first_title'], 'essential-addons-elementor' ); ?></span> <?php esc_html_e( $settings['eael_dch_last_title'], 'essential-addons-elementor' ); ?></h2>
		<?php endif; ?>
	   <span class="subtext"><?php esc_html_e( $settings['eael_dch_subtext'], 'essential-addons-elementor' ); ?></span>
	   <?php if( 'yes' == $settings['eael_show_dch_icon_content'] ) : ?>
	   	<i class="<?php echo esc_attr( $settings['eael_dch_icon'] ); ?>"></i>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<?php if( 'dch-icon-on-top' == $settings['eael_dch_type'] ) : ?>
	<div class="eael-dual-header <?php echo esc_attr( $dch_color_class ); ?>">
		<?php if( 'yes' == $settings['eael_show_dch_icon_content'] ) : ?>
	   	<i class="<?php echo esc_attr( $settings['eael_dch_icon'] ); ?>"></i>
		<?php endif; ?>
		<?php if( 'dch-colored' == $settings['eael_dch_color_type'] ) : ?>
	   <h2 class="title"><span class="lead"><?php esc_html_e( $settings['eael_dch_first_title'], 'essential-addons-elementor' ); ?></span> <?php esc_html_e( $settings['eael_dch_last_title'], 'essential-addons-elementor' ); ?></h2>
	   <?php elseif( 'dch-colored-reverse' == $settings['eael_dch_color_type'] ) : ?>
			<h2 class="title"><?php esc_html_e( $settings['eael_dch_first_title'], 'essential-addons-elementor' ); ?><span class="lead"> <?php esc_html_e( $settings['eael_dch_last_title'], 'essential-addons-elementor' ); ?></span></h2>
		<?php else: ?>
			<h2 class="title"><span class="lead"><?php esc_html_e( $settings['eael_dch_first_title'], 'essential-addons-elementor' ); ?></span> <?php esc_html_e( $settings['eael_dch_last_title'], 'essential-addons-elementor' ); ?></h2>
		<?php endif; ?>
	   <span class="subtext"><?php esc_html_e( $settings['eael_dch_subtext'], 'essential-addons-elementor' ); ?></span>
	</div>
	<?php endif; ?>

	<?php if( 'dch-icon-subtext-on-top' == $settings['eael_dch_type'] ) : ?>
	<div class="eael-dual-header <?php echo esc_attr( $dch_color_class ); ?>">
		<?php if( 'yes' == $settings['eael_show_dch_icon_content'] ) : ?>
	   	<i class="<?php echo esc_attr( $settings['eael_dch_icon'] ); ?>"></i>
		<?php endif; ?>
	   <span class="subtext"><?php esc_html_e( $settings['eael_dch_subtext'], 'essential-addons-elementor' ); ?></span>
	   <?php if( 'dch-colored' == $settings['eael_dch_color_type'] ) : ?>
	   <h2 class="title"><span class="lead"><?php esc_html_e( $settings['eael_dch_first_title'], 'essential-addons-elementor' ); ?></span> <?php esc_html_e( $settings['eael_dch_last_title'], 'essential-addons-elementor' ); ?></h2>
	   <?php elseif( 'dch-colored-reverse' == $settings['eael_dch_color_type'] ) : ?>
			<h2 class="title"><?php esc_html_e( $settings['eael_dch_first_title'], 'essential-addons-elementor' ); ?><span class="lead"> <?php esc_html_e( $settings['eael_dch_last_title'], 'essential-addons-elementor' ); ?></span></h2>
		<?php else: ?>
			<h2 class="title"><span class="lead"><?php esc_html_e( $settings['eael_dch_first_title'], 'essential-addons-elementor' ); ?></span> <?php esc_html_e( $settings['eael_dch_last_title'], 'essential-addons-elementor' ); ?></h2>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<?php if( 'dch-subtext-on-top' == $settings['eael_dch_type'] ) : ?>
	<div class="eael-dual-header <?php echo esc_attr( $dch_color_class ); ?>">
	   <span class="subtext"><?php esc_html_e( $settings['eael_dch_subtext'], 'essential-addons-elementor' ); ?></span>
	   <?php if( 'dch-colored' == $settings['eael_dch_color_type'] ) : ?>
	   <h2 class="title"><span class="lead"><?php esc_html_e( $settings['eael_dch_first_title'], 'essential-addons-elementor' ); ?></span> <?php esc_html_e( $settings['eael_dch_last_title'], 'essential-addons-elementor' ); ?></h2>
	   <?php elseif( 'dch-colored-reverse' == $settings['eael_dch_color_type'] ) : ?>
			<h2 class="title"><?php esc_html_e( $settings['eael_dch_first_title'], 'essential-addons-elementor' ); ?><span class="lead"> <?php esc_html_e( $settings['eael_dch_last_title'], 'essential-addons-elementor' ); ?></span></h2>
		<?php else: ?>
			<h2 class="title"><span class="lead"><?php esc_html_e( $settings['eael_dch_first_title'], 'essential-addons-elementor' ); ?></span> <?php esc_html_e( $settings['eael_dch_last_title'], 'essential-addons-elementor' ); ?></h2>
		<?php endif; ?>
		<?php if( 'yes' == $settings['eael_show_dch_icon_content'] ) : ?>
	   	<i class="<?php echo esc_attr( $settings['eael_dch_icon'] ); ?>"></i>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<?php
	}

	protected function content_template() {
		?>
		
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Dual_Color_Header() );
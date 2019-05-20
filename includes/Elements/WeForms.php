<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Scheme_Typography as Scheme_Typography;
use \Elementor\Widget_Base as Widget_Base;
use \Essential_Addons_Elementor\Classes\Bootstrap;

class WeForms extends Widget_Base {

	use \Essential_Addons_Elementor\Traits\Helper;
	
	public function get_name() {
		return 'eael-weform';
	}

	public function get_title() {
		return esc_html__( 'EA weForm', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'fa fa-envelope-o';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
	
	protected function _register_controls() {

		
  		$this->start_controls_section(
  			'eael_section_weform',
  			[
  				'label' => esc_html__( 'Select Form', 'essential-addons-elementor' )
  			]
  		);
		
	
		
		$this->add_control(
			'wpuf_contact_form',
			[
				'label'       => esc_html__( 'Select weForm', 'essential-addons-elementor' ),
				'description' => esc_html__( 'Please save and refresh the page after selecting the form', 'essential-addons-elementor' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => $this->eael_select_weform(),
				'default'     => '0',
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
		
		$this->start_controls_section(
			'eael_section_weform_styles',
			[
				'label' => esc_html__( 'Form Container Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->add_control(
			'eael_weform_background',
			[
				'label' => esc_html__( 'Form Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'eael_weform_alignment',
			[
				'label' => esc_html__( 'Form Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'default' => [
						'title' => __( 'Default', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					],
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
				'default' => 'default',
				'prefix_class' => 'eael-contact-form-align-',
			]
		);

  		$this->add_responsive_control(
  			'eael_weform_width',
  			[
  				'label' => esc_html__( 'Form Width', 'essential-addons-elementor' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1500,
					],
					'em' => [
						'min' => 1,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

  		$this->add_responsive_control(
  			'eael_weform_max_width',
  			[
  				'label' => esc_html__( 'Form Max Width', 'essential-addons-elementor' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1500,
					],
					'em' => [
						'min' => 1,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
		
		
		$this->add_responsive_control(
			'eael_weform_margin',
			[
				'label' => esc_html__( 'Form Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		
		$this->add_responsive_control(
			'eael_weform_padding',
			[
				'label' => esc_html__( 'Form Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_control(
			'eael_weform_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_weform_border',
				'selector' => '{{WRAPPER}} .eael-weform-container',
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_weform_box_shadow',
				'selector' => '{{WRAPPER}} .eael-weform-container',
			]
		);
		
		$this->end_controls_section();
		
		

		$this->start_controls_section(
			'eael_section_weform_field_styles',
			[
				'label' => esc_html__( 'Form Fields Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->add_control(
			'eael_weform_input_background',
			[
				'label' => esc_html__( 'Input Field Background', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="text"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="password"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="email"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="number"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields textarea' => 'background-color: {{VALUE}};',
				],
			]
		);
		

  		$this->add_responsive_control(
  			'eael_weform_input_width',
  			[
  				'label' => esc_html__( 'Input Width', 'essential-addons-elementor' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1500,
					],
					'em' => [
						'min' => 1,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="text"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="password"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="email"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="number"]' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
		
  		$this->add_responsive_control(
  			'eael_weform_textarea_width',
  			[
  				'label' => esc_html__( 'Textarea Width', 'essential-addons-elementor' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1500,
					],
					'em' => [
						'min' => 1,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields textarea' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);	
		
		$this->add_responsive_control(
			'eael_weform_input_padding',
			[
				'label' => esc_html__( 'Fields Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="text"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="password"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="email"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="number"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		
		$this->add_control(
			'eael_weform_input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="text"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="password"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="email"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="number"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_weform_input_border',
				'selector' => '{{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="text"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="password"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="email"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="number"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields textarea',
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_weform_input_box_shadow',
				'selector' => '{{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="text"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="password"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="email"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="number"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields textarea',
			]
		);

		$this->add_control(
			'eael_weform_focus_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Focus State Style', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_weform_input_focus_box_shadow',
				'selector' => '{{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="text"]:focus, 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="password"]:focus, 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="email"]:focus, 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"]:focus, 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"]:focus, 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="number"]:focus, 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields textarea:focus',
			]
		);

		$this->add_control(
			'eael_weform_input_focus_border',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="text"]:focus, 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="password"]:focus, 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="email"]:focus, 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"]:focus, 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"]:focus, 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="number"]:focus, 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields textarea:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		


		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'eael_section_weform_typography',
			[
				'label' => esc_html__( 'Color & Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		
		$this->add_control(
			'eael_weform_label_color',
			[
				'label' => esc_html__( 'Label Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container, {{WRAPPER}} .eael-weform-container .wpuf-label label' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'eael_weform_field_color',
			[
				'label' => esc_html__( 'Field Font Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="text"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="password"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="email"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="number"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields textarea' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'eael_weform_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Font Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-weform-container ::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-weform-container ::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
			]
		);
		
		
		$this->add_control(
			'eael_weform_label_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Label Typography', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_weform_label_typography',
				'selector' => '{{WRAPPER}} .eael-weform-container, {{WRAPPER}} .eael-weform-container .wpuf-label label',
			]
		);
		
		
		$this->add_control(
			'eael_weform_heading_input_field',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Input Fields Typography', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_weform_input_field_typography',
				'selector' => '{{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="text"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="password"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="email"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="url"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields input[type="number"], 
					 {{WRAPPER}} .eael-weform-container ul.wpuf-form li .wpuf-fields textarea',
			]
		);
		
		$this->end_controls_section();
		
		
		
		$this->start_controls_section(
			'eael_section_weform_submit_button_styles',
			[
				'label' => esc_html__( 'Submit Button Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

  		$this->add_responsive_control(
  			'eael_weform_submit_btn_width',
  			[
  				'label' => esc_html__( 'Button Width', 'essential-addons-elementor' ),
  				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1500,
					],
					'em' => [
						'min' => 1,
						'max' => 80,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
  		
		$this->add_responsive_control(
			'eael_weform_submit_btn_alignment',
			[
				'label' => esc_html__( 'Button Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'default' => [
						'title' => __( 'Default', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					],
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
				'default' => 'default',
				'prefix_class' => 'eael-contact-form-btn-align-',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_weform_submit_btn_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]',
			]
		);
		
		$this->add_responsive_control(
			'eael_weform_submit_btn_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_responsive_control(
			'eael_weform_submit_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		
		$this->start_controls_tabs( 'eael_weform_submit_button_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_weform_submit_btn_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]' => 'color: {{VALUE}};',
				],
			]
		);
		

		
		$this->add_control(
			'eael_weform_submit_btn_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_weform_submit_btn_border',
				'selector' => '{{WRAPPER}} .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]',
			]
		);
		
		$this->add_control(
			'eael_weform_submit_btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]' => 'border-radius: {{SIZE}}px;',
				],
			]
		);
		

		
		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_weform_submit_btn_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_weform_submit_btn_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_weform_submit_btn_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_weform_submit_btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_weform_submit_btn_box_shadow',
				'selector' => '{{WRAPPER}} .eael-weform-container ul.wpuf-form .wpuf-submit input[type="submit"]',
			]
		);
		
		
		$this->end_controls_section();
		
		
	}


	protected function render( ) {
		
      $settings = $this->get_settings();
		

	?>
	
	
	<?php if ( ! empty( $settings['wpuf_contact_form'] ) ) : ?>
		<div class="eael-weform-container">		
			<?php echo do_shortcode( '[weforms id="' . $settings['wpuf_contact_form'] . '" ]' ); ?>
		</div>
	<?php endif; ?>
	
	<?php
	
	}

	protected function content_template() {}
}

<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Caldera_Form extends Widget_Base {

	public function get_name() {
		return 'eael-caldera-form';
	}

	public function get_title() {
		return esc_html__( 'EA Caldera Form', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'fa fa-envelope-o';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {


  		$this->start_controls_section(
  			'eael_section_caldera_form',
  			[
  				'label' => esc_html__( 'Caldera Form', 'essential-addons-elementor' )
  			]
  		);



		$this->add_control(
			'eael_caldera_form',
			[
				'label' => esc_html__( 'Select your caldera form', 'essential-addons-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'options' => eael_select_caldera_form(),
			]
		);


		$this->end_controls_section();


		$this->start_controls_section(
			'eael_section_caldera_form_styles',
			[
				'label' => esc_html__( 'Form Container Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_caldera_form_background',
			[
				'label' => esc_html__( 'Form Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_caldera_form_alignment',
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
				'prefix_class' => 'eael-caldera-form-align-',
			]
		);

  		$this->add_responsive_control(
  			'eael_caldera_form_width',
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
					'{{WRAPPER}} .eael-caldera-form-container' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

  		$this->add_responsive_control(
  			'eael_caldera_form_max_width',
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
					'{{WRAPPER}} .eael-caldera-form-container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);


		$this->add_responsive_control(
			'eael_caldera_form_margin',
			[
				'label' => esc_html__( 'Form Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_caldera_form_padding',
			[
				'label' => esc_html__( 'Form Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'eael_caldera_form_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_caldera_form_border',
				'selector' => '{{WRAPPER}} .eael-caldera-form-container',
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_caldera_form_box_shadow',
				'selector' => '{{WRAPPER}} .eael-caldera-form-container',
			]
		);

		$this->end_controls_section();



		$this->start_controls_section(
			'eael_section_caldera_form_field_styles',
			[
				'label' => esc_html__( 'Form Fields Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_caldera_form_input_background',
			[
				'label' => esc_html__( 'Input Field Background', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f2f2f2',
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container input.form-control, {{WRAPPER}} .eael-caldera-form-container form input[type="text"], {{WRAPPER}} .eael-caldera-form-container form input[type="password"], {{WRAPPER}} .eael-caldera-form-container form input[type="email"], {{WRAPPER}} .eael-caldera-form-container form input[type="url"], {{WRAPPER}} .eael-caldera-form-container form input[type="date"], {{WRAPPER}} .eael-caldera-form-container form input[type="month"], {{WRAPPER}} .eael-caldera-form-container form input[type="time"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime-local"], {{WRAPPER}} .eael-caldera-form-container form input[type="week"], {{WRAPPER}} .eael-caldera-form-container form input[type="number"], {{WRAPPER}} .eael-caldera-form-container form input[type="search"], {{WRAPPER}} .eael-caldera-form-container form input[type="tel"], {{WRAPPER}} .eael-caldera-form-container form input[type="color"], {{WRAPPER}} .eael-caldera-form-container form select, {{WRAPPER}} .eael-caldera-form-container form textarea' => 'background: {{VALUE}};',
				],
			]
		);


  		$this->add_responsive_control(
  			'eael_caldera_form_input_width',
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
					'{{WRAPPER}} .eael-caldera-form-container input.form-control, {{WRAPPER}} .eael-caldera-form-container form input[type="text"], {{WRAPPER}} .eael-caldera-form-container form input[type="password"], {{WRAPPER}} .eael-caldera-form-container form input[type="email"], {{WRAPPER}} .eael-caldera-form-container form input[type="url"], {{WRAPPER}} .eael-caldera-form-container form input[type="date"], {{WRAPPER}} .eael-caldera-form-container form input[type="month"], {{WRAPPER}} .eael-caldera-form-container form input[type="time"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime-local"], {{WRAPPER}} .eael-caldera-form-container form input[type="week"], {{WRAPPER}} .eael-caldera-form-container form input[type="number"], {{WRAPPER}} .eael-caldera-form-container form input[type="search"], {{WRAPPER}} .eael-caldera-form-container form input[type="tel"], {{WRAPPER}} .eael-caldera-form-container form input[type="color"], {{WRAPPER}} .eael-caldera-form-container form select, {{WRAPPER}} .eael-caldera-form-container form textarea' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

  		$this->add_responsive_control(
  			'eael_caldera_form_textarea_width',
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
					'{{WRAPPER}} .eael-caldera-form-container form textarea' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

		$this->add_responsive_control(
			'eael_caldera_form_input_padding',
			[
				'label' => esc_html__( 'Fields Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container input.form-control, {{WRAPPER}} .eael-caldera-form-container form input[type="text"], {{WRAPPER}} .eael-caldera-form-container form input[type="password"], {{WRAPPER}} .eael-caldera-form-container form input[type="email"], {{WRAPPER}} .eael-caldera-form-container form input[type="url"], {{WRAPPER}} .eael-caldera-form-container form input[type="date"], {{WRAPPER}} .eael-caldera-form-container form input[type="month"], {{WRAPPER}} .eael-caldera-form-container form input[type="time"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime-local"], {{WRAPPER}} .eael-caldera-form-container form input[type="week"], {{WRAPPER}} .eael-caldera-form-container form input[type="number"], {{WRAPPER}} .eael-caldera-form-container form input[type="search"], {{WRAPPER}} .eael-caldera-form-container form input[type="tel"], {{WRAPPER}} .eael-caldera-form-container form input[type="color"], {{WRAPPER}} .eael-caldera-form-container form select, {{WRAPPER}} .eael-caldera-form-container form textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);



		$this->add_control(
			'eael_caldera_form_input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container input.form-control, {{WRAPPER}} .eael-caldera-form-container form input[type="text"], {{WRAPPER}} .eael-caldera-form-container form input[type="password"], {{WRAPPER}} .eael-caldera-form-container form input[type="email"], {{WRAPPER}} .eael-caldera-form-container form input[type="url"], {{WRAPPER}} .eael-caldera-form-container form input[type="date"], {{WRAPPER}} .eael-caldera-form-container form input[type="month"], {{WRAPPER}} .eael-caldera-form-container form input[type="time"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime-local"], {{WRAPPER}} .eael-caldera-form-container form input[type="week"], {{WRAPPER}} .eael-caldera-form-container form input[type="number"], {{WRAPPER}} .eael-caldera-form-container form input[type="search"], {{WRAPPER}} .eael-caldera-form-container form input[type="tel"], {{WRAPPER}} .eael-caldera-form-container form input[type="color"], {{WRAPPER}} .eael-caldera-form-container form select, {{WRAPPER}} .eael-caldera-form-container form textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_caldera_form_input_border',
				'selector' => '{{WRAPPER}} .eael-caldera-form-container input.form-control, {{WRAPPER}} .eael-caldera-form-container form input[type="text"], {{WRAPPER}} .eael-caldera-form-container form input[type="password"], {{WRAPPER}} .eael-caldera-form-container form input[type="email"], {{WRAPPER}} .eael-caldera-form-container form input[type="url"], {{WRAPPER}} .eael-caldera-form-container form input[type="date"], {{WRAPPER}} .eael-caldera-form-container form input[type="month"], {{WRAPPER}} .eael-caldera-form-container form input[type="time"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime-local"], {{WRAPPER}} .eael-caldera-form-container form input[type="week"], {{WRAPPER}} .eael-caldera-form-container form input[type="number"], {{WRAPPER}} .eael-caldera-form-container form input[type="search"], {{WRAPPER}} .eael-caldera-form-container form input[type="tel"], {{WRAPPER}} .eael-caldera-form-container form input[type="color"], {{WRAPPER}} .eael-caldera-form-container form select, {{WRAPPER}} .eael-caldera-form-container form textarea',
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_caldera_form_input_box_shadow',
				'selector' => '{{WRAPPER}} .eael-caldera-form-container input.form-control, {{WRAPPER}} .eael-caldera-form-container form input[type="text"], {{WRAPPER}} .eael-caldera-form-container form input[type="password"], {{WRAPPER}} .eael-caldera-form-container form input[type="email"], {{WRAPPER}} .eael-caldera-form-container form input[type="url"], {{WRAPPER}} .eael-caldera-form-container form input[type="date"], {{WRAPPER}} .eael-caldera-form-container form input[type="month"], {{WRAPPER}} .eael-caldera-form-container form input[type="time"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime-local"], {{WRAPPER}} .eael-caldera-form-container form input[type="week"], {{WRAPPER}} .eael-caldera-form-container form input[type="number"], {{WRAPPER}} .eael-caldera-form-container form input[type="search"], {{WRAPPER}} .eael-caldera-form-container form input[type="tel"], {{WRAPPER}} .eael-caldera-form-container form input[type="color"], {{WRAPPER}} .eael-caldera-form-container form select, {{WRAPPER}} .eael-caldera-form-container form textarea',
			]
		);

		$this->add_control(
			'eael_caldera_form_focus_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Focus State Style', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_caldera_form_input_focus_box_shadow',
				'selector' => '{{WRAPPER}} .eael-caldera-form-container input.form-control, {{WRAPPER}} .eael-caldera-form-container form input[type="text"], {{WRAPPER}} .eael-caldera-form-container form input[type="password"], {{WRAPPER}} .eael-caldera-form-container form input[type="email"], {{WRAPPER}} .eael-caldera-form-container form input[type="url"], {{WRAPPER}} .eael-caldera-form-container form input[type="date"], {{WRAPPER}} .eael-caldera-form-container form input[type="month"], {{WRAPPER}} .eael-caldera-form-container form input[type="time"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime"], {{WRAPPER}} .eael-caldera-form-container form input[type="datetime-local"], {{WRAPPER}} .eael-caldera-form-container form input[type="week"], {{WRAPPER}} .eael-caldera-form-container form input[type="number"], {{WRAPPER}} .eael-caldera-form-container form input[type="search"], {{WRAPPER}} .eael-caldera-form-container form input[type="tel"], {{WRAPPER}} .eael-caldera-form-container form input[type="color"], {{WRAPPER}} .eael-caldera-form-container form select, {{WRAPPER}} .eael-caldera-form-container form textarea',
			]
		);

		$this->add_control(
			'eael_caldera_form_input_focus_border',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'body {{WRAPPER}} .eael-caldera-form-container input.form-control, {{WRAPPER}} .eael-caldera-form-container form input[type="text"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="password"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="email"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="url"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="date"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="month"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="time"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="datetime"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="datetime-local"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="week"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="number"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="search"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="tel"]:focus, {{WRAPPER}} .eael-caldera-form-container form input[type="color"]:focus, {{WRAPPER}} .eael-caldera-form-container form select:focus, {{WRAPPER}} .eael-caldera-form-container form textarea:focus' => 'border-color: {{VALUE}};',
				],
			]
		);



		$this->end_controls_section();


		$this->start_controls_section(
			'eael_section_caldera_form_typography',
			[
				'label' => esc_html__( 'Color & Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);


		$this->add_control(
			'eael_caldera_form_label_color',
			[
				'label' => esc_html__( 'Label Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container, {{WRAPPER}} .eael-caldera-form-container .caldera-form label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_caldera_form_field_color',
			[
				'label' => esc_html__( 'Field Font Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container input.form-control, {{WRAPPER}} .eael-caldera-form-container textarea.form-control' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_caldera_form_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Font Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container ::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-caldera-form-container ::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-caldera-form-container ::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'eael_caldera_form_label_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Label Typography', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_caldera_form_label_typography',
				'selector' => '{{WRAPPER}} .eael-caldera-form-container, {{WRAPPER}} .eael-caldera-form-container .caldera-form label',
			]
		);


		$this->add_control(
			'eael_caldera_form_heading_input_field',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Input Fields Typography', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_caldera_form_input_field_typography',
				'selector' => '{{WRAPPER}} .eael-caldera-form-container input.form-control, {{WRAPPER}} .eael-caldera-form-container textarea.form-control',
			]
		);

		$this->end_controls_section();



		$this->start_controls_section(
			'eael_section_caldera_form_submit_button_styles',
			[
				'label' => esc_html__( 'Submit Button Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

  		$this->add_responsive_control(
  			'eael_caldera_form_submit_btn_width',
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
					'{{WRAPPER}} .eael-caldera-form-container input[type="submit"]' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

		$this->add_responsive_control(
			'eael_caldera_form_submit_btn_alignment',
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
				'prefix_class' => 'eael-caldera-form-btn-align-',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_caldera_form_submit_btn_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-caldera-form-container input[type="submit"]',
			]
		);

		$this->add_responsive_control(
			'eael_caldera_form_submit_btn_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'eael_caldera_form_submit_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);



		$this->start_controls_tabs( 'eael_caldera_form_submit_button_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_caldera_form_submit_btn_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container input[type="submit"]' => 'color: {{VALUE}};',
				],
			]
		);



		$this->add_control(
			'eael_caldera_form_submit_btn_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#a3bf5e',
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container input[type="submit"]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_caldera_form_submit_btn_border',
				'selector' => '{{WRAPPER}} .eael-caldera-form-container input[type="submit"]',
			]
		);

		$this->add_control(
			'eael_caldera_form_submit_btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container input[type="submit"]' => 'border-radius: {{SIZE}}px;',
				],
			]
		);



		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_caldera_form_submit_btn_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_caldera_form_submit_btn_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container input[type="submit"]:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_caldera_form_submit_btn_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container input[type="submit"]:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_caldera_form_submit_btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-caldera-form-container input[type="submit"]:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_caldera_form_submit_btn_box_shadow',
				'selector' => '{{WRAPPER}} .eael-caldera-form-container input[type="submit"]',
			]
		);


		$this->end_controls_section();


	}


	protected function render( ) {

      $settings = $this->get_settings();

	?>
	<?php if ( ! empty( $settings['eael_caldera_form'] ) ) : ?>
		<div class="eael-caldera-form-container">
			<?php echo do_shortcode( '[caldera_form id="' . $settings['eael_caldera_form'] . '" ]' ); ?>
		</div>
	<?php endif; ?>

	<?php

	}

	protected function content_template() {''

		?>


		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Caldera_Form() );
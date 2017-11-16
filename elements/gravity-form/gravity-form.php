<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_GravityForm extends Widget_Base {

	public function get_name() {
		return 'eael-gravity';
	}

	public function get_title() {
		return esc_html__( 'EA Gravity Form', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'fa fa-envelope-o';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
  			'eael_section_gravity_form',
  			[
  				'label' => esc_html__( 'Gravity Form', 'essential-addons-elementor' )
  			]
  		);

		$this->add_control(
			'eael_gravity_form',
			[
				'label' => esc_html__( 'Select gravity form', 'essential-addons-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'options' => eael_select_gravity_form(),
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'eael_section_gravity_styles',
			[
				'label' => esc_html__( 'Form Container Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_gravity_background',
			[
				'label' => esc_html__( 'Form Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_gravity_alignment',
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
				'prefix_class' => 'eael-gravity-form-align-',
			]
		);

		$this->add_responsive_control(
  			'eael_gravity_width',
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
					'{{WRAPPER}} .eael-gravity-container' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

  		$this->add_responsive_control(
  			'eael_gravity_max_width',
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
					'{{WRAPPER}} .eael-gravity-container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

		$this->add_responsive_control(
			'eael_gravity_margin',
			[
				'label' => esc_html__( 'Form Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_gravity_padding',
			[
				'label' => esc_html__( 'Form Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_gravity_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_gravity_border',
				'selector' => '{{WRAPPER}} .eael-gravity-container',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_gravity_box_shadow',
				'selector' => '{{WRAPPER}} .eael-gravity-container',
			]
		);

		$this->end_controls_section();

		/**
		 * Form Fields Styles
		 */
		$this->start_controls_section(
			'eael_section_gravity_field_styles',
			[
				'label' => esc_html__( 'Form Fields Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_gravity_input_background',
			[
				'label' => esc_html__( 'Input Field Background', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gfield input[type="text"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="password"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="email"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="url"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="number"],
					 {{WRAPPER}} .eael-gravity-container .gfield select,
					 {{WRAPPER}} .eael-gravity-container .gfield textarea' => 'background-color: {{VALUE}};',
				],
			]
		);


  		$this->add_responsive_control(
  			'eael_gravity_input_width',
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
					'{{WRAPPER}} .eael-gravity-container .gfield input[type="text"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="password"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="email"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="url"],
					 {{WRAPPER}} .eael-gravity-container .gfield select,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="number"]' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

  		$this->add_responsive_control(
  			'eael_gravity_textarea_width',
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
					'{{WRAPPER}} .eael-gravity-container .gfield textarea' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

		$this->add_responsive_control(
			'eael_gravity_input_padding',
			[
				'label' => esc_html__( 'Fields Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gfield input[type="text"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="password"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="email"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="url"],
					 {{WRAPPER}} .eael-gravity-container .gfield select,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="number"],
					 {{WRAPPER}} .eael-gravity-container .gfield textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);



		$this->add_control(
			'eael_gravity_input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gfield input[type="text"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="password"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="email"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="url"],
					 {{WRAPPER}} .eael-gravity-container .gfield select,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="number"],
					 {{WRAPPER}} .eael-gravity-container .gfield textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_gravity_input_border',
				'selector' => '
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="text"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="password"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="email"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="url"],
					 {{WRAPPER}} .eael-gravity-container .gfield select,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="number"],
					 {{WRAPPER}} .eael-gravity-container .gfield textarea',
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_gravity_input_box_shadow',
				'selector' => '
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="text"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="password"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="email"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="url"],
					 {{WRAPPER}} .eael-gravity-container .gfield select,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="number"],
					 {{WRAPPER}} .eael-gravity-container .gfield textarea',
			]
		);

		$this->add_control(
			'eael_gravity_focus_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Focus State Style', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_gravity_input_focus_box_shadow',
				'selector' => '
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="text"]:focus,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="password"]:focus,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="email"]:focus,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="url"]:focus,
					 {{WRAPPER}} .eael-gravity-container .gfield select:focus,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="number"]:focus,
					 {{WRAPPER}} .eael-gravity-container .gfield textarea:focus',
			]
		);

		$this->add_control(
			'eael_gravity_input_focus_border',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gfield input[type="text"]:focus,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="password"]:focus,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="email"]:focus,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="url"]:focus,
					 {{WRAPPER}} .eael-gravity-container .gfield select:focus,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="number"]:focus,
					 {{WRAPPER}} .eael-gravity-container .gfield textarea:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Typography
		 */
		$this->start_controls_section(
			'eael_section_gravity_typography',
			[
				'label' => esc_html__( 'Color & Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);


		$this->add_control(
			'eael_gravity_label_color',
			[
				'label' => esc_html__( 'Label Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container, {{WRAPPER}} .eael-gravity-container .nf-field-label label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_gravity_field_color',
			[
				'label' => esc_html__( 'Field Font Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gfield input[type="text"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="password"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="email"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="url"],
					 {{WRAPPER}} .eael-gravity-container .gfield select,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="number"],
					 {{WRAPPER}} .eael-gravity-container .gfield textarea' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_gravity_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Font Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container ::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-gravity-container ::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-gravity-container ::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
			]
		);


		$this->add_control(
			'eael_gravity_label_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Label Typography', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_gravity_label_typography',
				'selector' => '{{WRAPPER}} .eael-gravity-container, {{WRAPPER}} .eael-gravity-container .wpuf-label label',
			]
		);


		$this->add_control(
			'eael_gravity_heading_input_field',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Input Fields Typography', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_gravity_input_field_typography',
				'selector' => '{{WRAPPER}} .eael-gravity-container .gfield input[type="text"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="password"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="email"],
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="url"],
					 {{WRAPPER}} .eael-gravity-container .gfield select,
					 {{WRAPPER}} .eael-gravity-container .gfield input[type="number"],
					 {{WRAPPER}} .eael-gravity-container .gfield textarea',
			]
		);

		$this->end_controls_section();

		/**
		 * Button Style
		 */
		$this->start_controls_section(
			'eael_section_gravity_submit_button_styles',
			[
				'label' => esc_html__( 'Submit Button Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

  		$this->add_responsive_control(
  			'eael_gravity_submit_btn_width',
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
					'{{WRAPPER}} .eael-gravity-container .gform_button' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

		$this->add_responsive_control(
			'eael_gravity_submit_btn_alignment',
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
				'prefix_class' => 'eael-gravity-form-btn-align-',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_gravity_submit_btn_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-gravity-container .gform_button',
			]
		);

		$this->add_responsive_control(
			'eael_gravity_submit_btn_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gform_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_gravity_submit_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gform_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'eael_gravity_submit_button_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_gravity_submit_btn_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gform_button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_gravity_submit_btn_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gform_button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_gravity_submit_btn_border',
				'selector' => '{{WRAPPER}} .eael-gravity-container .gform_button',
			]
		);

		$this->add_control(
			'eael_gravity_submit_btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gform_button' => 'border-radius: {{SIZE}}px;',
				],
			]
		);



		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_gravity_submit_btn_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_gravity_submit_btn_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gform_button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_gravity_submit_btn_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gform_button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_gravity_submit_btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-container .gform_button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_gravity_submit_btn_box_shadow',
				'selector' => '{{WRAPPER}} .eael-gravity-container .gform_button',
			]
		);


		$this->end_controls_section();

	}


	protected function render( ) {

      $settings = $this->get_settings();


	?>


	<?php if ( ! empty( $settings['eael_gravity_form'] ) ) : ?>
		<div class="eael-gravity-container">
			<?php echo do_shortcode( '[gravityform id="'.$settings['eael_gravity_form'].'" title="true" description="true"]' ); ?>
		</div>
	<?php endif; ?>

	<?php

	}

	protected function content_template() {''

		?>


		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_GravityForm() );
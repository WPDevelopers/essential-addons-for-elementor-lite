<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Contact_Form extends Widget_Base {

	public function get_name() {
		return 'eael-contact-form';
	}

	public function get_title() {
		return esc_html__( 'EA Contact Form 7', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'fa fa-envelope-o';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
	
	protected function _register_controls() {

		
  		$this->start_controls_section(
  			'eael_section_wpcf7_form',
  			[
  				'label' => esc_html__( 'Contact Form', 'essential-addons-elementor' )
  			]
  		);
		
	
		
		$this->add_control(
			'eael_wpcf7_form',
			[
				'label' => esc_html__( 'Select your contact form 7', 'essential-addons-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::SELECT,
				'options' => eael_select_contact_form(),
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


		$this->start_controls_section(
			'eael_section_contact_form_styles',
			[
				'label' => esc_html__( 'Form Container Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->add_control(
			'eael_contact_form_background',
			[
				'label' => esc_html__( 'Form Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'eael_contact_form_alignment',
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
  			'eael_contact_form_width',
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
					'{{WRAPPER}} .eael-contact-form-container' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

  		$this->add_responsive_control(
  			'eael_contact_form_max_width',
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
					'{{WRAPPER}} .eael-contact-form-container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
		
		
		$this->add_responsive_control(
			'eael_contact_form_margin',
			[
				'label' => esc_html__( 'Form Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		
		$this->add_responsive_control(
			'eael_contact_form_padding',
			[
				'label' => esc_html__( 'Form Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_control(
			'eael_contact_form_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_contact_form_border',
				'selector' => '{{WRAPPER}} .eael-contact-form-container',
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_contact_form_box_shadow',
				'selector' => '{{WRAPPER}} .eael-contact-form-container',
			]
		);
		
		$this->end_controls_section();
		
		

		$this->start_controls_section(
			'eael_section_contact_form_field_styles',
			[
				'label' => esc_html__( 'Form Fields Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		$this->add_control(
			'eael_contact_form_input_background',
			[
				'label' => esc_html__( 'Input Field Background', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-text, {{WRAPPER}} .eael-contact-form-container textarea.wpcf7-textarea' => 'background: {{VALUE}};',
				],
			]
		);
		

  		$this->add_responsive_control(
  			'eael_contact_form_input_width',
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
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-text' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
		
  		$this->add_responsive_control(
  			'eael_contact_form_textarea_width',
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
					'{{WRAPPER}} .eael-contact-form-container textarea.wpcf7-textarea' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);	
		
		$this->add_responsive_control(
			'eael_contact_form_input_padding',
			[
				'label' => esc_html__( 'Fields Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-text, {{WRAPPER}} .eael-contact-form-container textarea.wpcf7-textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		
		$this->add_control(
			'eael_contact_form_input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-text, {{WRAPPER}} .eael-contact-form-container textarea.wpcf7-textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_contact_form_input_border',
				'selector' => '{{WRAPPER}} .eael-contact-form-container input.wpcf7-text, {{WRAPPER}} .eael-contact-form-container textarea.wpcf7-textarea',
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_contact_form_input_box_shadow',
				'selector' => '{{WRAPPER}} .eael-contact-form-container input.wpcf7-text, {{WRAPPER}} .eael-contact-form-container textarea.wpcf7-textarea',
			]
		);

		$this->add_control(
			'eael_contact_form_focus_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Focus State Style', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_contact_form_input_focus_box_shadow',
				'selector' => '{{WRAPPER}} .eael-contact-form-container input.wpcf7-text:focus, {{WRAPPER}} .eael-contact-form-container textarea.wpcf7-textarea:focus',
			]
		);

		$this->add_control(
			'eael_contact_form_input_focus_border',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'body {{WRAPPER}} .eael-contact-form-container input.wpcf7-text:focus, body {{WRAPPER}} .eael-contact-form-container textarea.wpcf7-textarea:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		


		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'eael_section_contact_form_typography',
			[
				'label' => esc_html__( 'Color & Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		
		
		$this->add_control(
			'eael_contact_form_label_color',
			[
				'label' => esc_html__( 'Label Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container, {{WRAPPER}} .eael-contact-form-container .wpcf7-form label' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'eael_contact_form_field_color',
			[
				'label' => esc_html__( 'Field Font Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-text, {{WRAPPER}} .eael-contact-form-container textarea.wpcf7-textarea' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'eael_contact_form_placeholder_color',
			[
				'label' => esc_html__( 'Placeholder Font Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container ::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-contact-form-container ::-moz-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-contact-form-container ::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
			]
		);
		
		
		$this->add_control(
			'eael_contact_form_label_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Label Typography', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_contact_form_label_typography',
				'selector' => '{{WRAPPER}} .eael-contact-form-container, {{WRAPPER}} .eael-contact-form-container .wpcf7-form label',
			]
		);
		
		
		$this->add_control(
			'eael_contact_form_heading_input_field',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Input Fields Typography', 'essential-addons-elementor' ),
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_contact_form_input_field_typography',
				'selector' => '{{WRAPPER}} .eael-contact-form-container input.wpcf7-text, {{WRAPPER}} .eael-contact-form-container textarea.wpcf7-textarea',
			]
		);
		
		$this->end_controls_section();
		
		
		
		$this->start_controls_section(
			'eael_section_contact_form_submit_button_styles',
			[
				'label' => esc_html__( 'Submit Button Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

  		$this->add_responsive_control(
  			'eael_contact_form_submit_btn_width',
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
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-submit' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
  		
		$this->add_responsive_control(
			'eael_contact_form_submit_btn_alignment',
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
             'name' => 'eael_contact_form_submit_btn_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .eael-contact-form-container input.wpcf7-submit',
			]
		);
		
		$this->add_responsive_control(
			'eael_contact_form_submit_btn_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_responsive_control(
			'eael_contact_form_submit_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		
		$this->start_controls_tabs( 'eael_contact_form_submit_button_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_contact_form_submit_btn_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-submit' => 'color: {{VALUE}};',
				],
			]
		);
		

		
		$this->add_control(
			'eael_contact_form_submit_btn_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-submit' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_contact_form_submit_btn_border',
				'selector' => '{{WRAPPER}} .eael-contact-form-container input.wpcf7-submit',
			]
		);
		
		$this->add_control(
			'eael_contact_form_submit_btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-submit' => 'border-radius: {{SIZE}}px;',
				],
			]
		);
		

		
		$this->end_controls_tab();

		$this->start_controls_tab( 'eael_contact_form_submit_btn_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_contact_form_submit_btn_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-submit:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_contact_form_submit_btn_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-submit:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_contact_form_submit_btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form-container input.wpcf7-submit:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_contact_form_submit_btn_box_shadow',
				'selector' => '{{WRAPPER}} .eael-contact-form-container input.wpcf7-submit',
			]
		);
		
		
		$this->end_controls_section();
		
		
	}


	protected function render( ) {
		
      $settings = $this->get_settings();
		

	?>
	
	
	<?php if ( ! empty( $settings['eael_wpcf7_form'] ) ) : ?>
		<div class="eael-contact-form-container">		
			<?php echo do_shortcode( '[contact-form-7 id="' . $settings['eael_wpcf7_form'] . '" ]' ); ?>
		</div>
	<?php endif; ?>
	
	<?php
	
	}

	protected function content_template() {''
		
		?>
		
	
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Contact_Form() );
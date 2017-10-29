<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Testimonial extends Widget_Base {

	public function get_name() {
		return 'eael-testimonial';
	}

	public function get_title() {
		return esc_html__( 'EA Testimonial', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
	
	
	protected function _register_controls() {

		
  		$this->start_controls_section(
  			'eael_section_testimonial_image',
  			[
  				'label' => esc_html__( 'Testimonial Image', 'essential-addons-elementor' )
  			]
  		);

		$this->add_control(
			'eael_testimonial_enable_avatar',
			[
				'label' => esc_html__( 'Display Avatar?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);		

		$this->add_control(
			'eael_testimonial_image',
			[
				'label' => __( 'Testimonial Avatar', 'essential-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'eael_testimonial_enable_avatar' => 'yes',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'thumbnail',
				'condition' => [
					'eael_testimonial_image[url]!' => '',
					'eael_testimonial_enable_avatar' => 'yes',
				],
			]
		);


		$this->end_controls_section();

  		$this->start_controls_section(
  			'eael_section_testimonial_content',
  			[
  				'label' => esc_html__( 'Testimonial Content', 'essential-addons-elementor' )
  			]
  		);


		$this->add_control(
			'eael_testimonial_name',
			[
				'label' => esc_html__( 'User Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'John Doe', 'essential-addons-elementor' ),
			]
		);
		
		$this->add_control(
			'eael_testimonial_company_title',
			[
				'label' => esc_html__( 'Company Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Codetic', 'essential-addons-elementor' ),
			]
		);
		
		$this->add_control(
			'eael_testimonial_description',
			[
				'label' => esc_html__( 'Testimonial Description', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Add testimonial description here. Edit and place your own text.', 'essential-addons-elementor' ),
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
			'eael_section_testimonial_styles_general',
			[
				'label' => esc_html__( 'Testimonial Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_testimonial_background',
			[
				'label' => esc_html__( 'Testimonial Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-item' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_testimonial_alignment',
			[
				'label' => esc_html__( 'Set Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'eael-testimonial-align-default' => [
						'title' => __( 'Default', 'essential-addons-elementor' ),
						'icon' => 'fa fa-ban',
					],
					'eael-testimonial-align-left' => [
						'title' => esc_html__( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'eael-testimonial-align-centered' => [
						'title' => esc_html__( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'eael-testimonial-align-right' => [
						'title' => esc_html__( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'eael-testimonial-align-default',
			]
		);

		$this->add_control(
			'eael_testimonial_user_display_block',
			[
				'label' => esc_html__( 'Display User & Company Block?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_responsive_control(
			'eael_testimonial_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_testimonial_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-testimonial-item',
			]
		);

		$this->add_control(
			'eael_testimonial_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-item' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
			]
		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'eael_section_testimonial_image_styles',
			[
				'label' => esc_html__( 'Testimonial Image Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);		

		$this->add_responsive_control(
			'eael_testimonial_image_width',
			[
				'label' => esc_html__( 'Image Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 150,
					'unit' => 'px',
				],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'size_units' => [ '%', 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-image img' => 'width:{{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'eael_testimonial_image_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_testimonial_image_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_testimonial_image_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-testimonial-image img',
			]
		);

		$this->add_control(
			'eael_testimonial_image_rounded',
			[
				'label' => esc_html__( 'Rounded Avatar?', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'testimonial-avatar-rounded',
				'default' => '',
			]
		);


		$this->add_control(
			'eael_testimonial_image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-image img' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
				],
				'condition' => [
					'eael_testimonial_image_rounded!' => 'testimonial-avatar-rounded',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'eael_section_testimonial_typography',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_testimonial_name_heading',
			[
				'label' => __( 'User Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_testimonial_name_color',
			[
				'label' => esc_html__( 'User Name Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#272727',
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-content .eael-testimonial-user' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_testimonial_name_typography',
				'selector' => '{{WRAPPER}} .eael-testimonial-content .eael-testimonial-user',
			]
		);

		$this->add_control(
			'eael_testimonial_company_heading',
			[
				'label' => __( 'Company Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);


		$this->add_control(
			'eael_testimonial_company_color',
			[
				'label' => esc_html__( 'Company Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#272727',
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-content .eael-testimonial-user-company' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_testimonial_position_typography',
				'selector' => '{{WRAPPER}} .eael-testimonial-content .eael-testimonial-user-company',
			]
		);

		$this->add_control(
			'eael_testimonial_description_heading',
			[
				'label' => __( 'Testimonial Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_testimonial_description_color',
			[
				'label' => esc_html__( 'Testimonial Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7a7a7a',
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-content .eael-testimonial-text' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_testimonial_description_typography',
				'selector' => '{{WRAPPER}} .eael-testimonial-content .eael-testimonial-text',
			]
		);

		$this->add_control(
			'eael_testimonial_quotation_heading',
			[
				'label' => __( 'Quotation Mark', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_testimonial_quotation_color',
			[
				'label' => esc_html__( 'Quotation Mark Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.15)',
				'selectors' => [
					'{{WRAPPER}} .eael-testimonial-quote' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_testimonial_quotation_typography',
				'selector' => '{{WRAPPER}} .eael-testimonial-quote',
			]
		);


		$this->end_controls_section();


	}


	protected function render( ) {
		
      $settings = $this->get_settings();
      $testimonial_image = $this->get_settings( 'eael_testimonial_image' );
	  $testimonial_image_url = Group_Control_Image_Size::get_attachment_image_src( $testimonial_image['id'], 'thumbnail', $settings );	
	  $testimonial_classes = $this->get_settings('eael_testimonial_image_rounded') . " " . $this->get_settings('eael_testimonial_alignment');


	?>


<div id="eael-testimonial-<?php echo esc_attr($this->get_id()); ?>" class="eael-testimonial-item clearfix <?php echo $testimonial_classes; ?>">

	<div class="eael-testimonial-image">
		<span class="eael-testimonial-quote"></span>
		<figure>
			<img src="<?php echo esc_url($testimonial_image_url);?>" alt="<?php echo $settings['eael_testimonial_name'];?>">
		</figure>
	</div>

	<div class="eael-testimonial-content">
		<span class="eael-testimonial-quote"></span>
		<p class="eael-testimonial-text"><?php echo $settings['eael_testimonial_description']; ?></p>
		<p class="eael-testimonial-user" <?php if ( ! empty( $settings['eael_testimonial_user_display_block'] ) ) : ?> style="display: block; float: none;"<?php endif;?>><?php echo $settings['eael_testimonial_name']; ?></p>
		<p class="eael-testimonial-user-company"><?php echo $settings['eael_testimonial_company_title']; ?></p>
	</div>
</div>

	
	<?php
	
	}

	protected function content_template() {
		
		?>
		
	
		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Testimonial() );
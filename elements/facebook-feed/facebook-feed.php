<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Facebook_Feed extends Widget_Base {

	public function get_name() {
		return 'eael-facebook-feed';
	}

	public function get_title() {
		return esc_html__( 'EA Facebook Feed', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'fa fa-facebook-official';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
  			'eael_section_facebook_feed_acc_settings',
  			[
  				'label' => esc_html__( 'Account Settings', 'essential-addons-elementor' )
  			]
  		);

		$this->add_control(
			'eael_facebook_feed_ac_name',
			[
				'label' => esc_html__( 'Account Name', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => '@Codetic',
				'description' => esc_html__( 'Use @ sign with your account name.', 'essential-addons-elementor' ),

			]
		);

		$this->add_control(
			'eael_facebook_feed_app_id',
			[
				'label' => esc_html__( 'App ID', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => '',
				'description' => '<a href="https://developers.facebook.com/apps/" target="_blank">Get App ID.</a> Create or select an app and grab the App ID',
			]
		);

		$this->add_control(
			'eael_facebook_feed_app_secret',
			[
				'label' => esc_html__( 'App Secret', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => '',
				'description' => '<a href="https://developers.facebook.com/apps/" target="_blank">Get App Secret.</a> Create or select an app and grab the App ID',
			]
		);

  		$this->end_controls_section();

		$this->start_controls_section(
  			'eael_section_facebook_feed_settings',
  			[
  				'label' => esc_html__( 'Layout Settings', 'essential-addons-elementor' )
  			]
  		);

		$this->add_control(
			'eael_facebook_feed_type',
			[
				'label' => esc_html__( 'Content Layout', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'masonry',
				'options' => [
					'list' => esc_html__( 'List', 'essential-addons-elementor' ),
					'masonry' => esc_html__( 'Masonry', 'essential-addons-elementor' ),
				],
			]
		);

		$this->add_control(
            'eael_facebook_feed_type_col_type',
            [
                'label' => __( 'Column Grid', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'col-2' => '2 Columns',
                    'col-3' => '3 Columns',
                    'col-4' => '4 Columns',
                ],
                'default' => 'col-3',
                'prefix_class' => 'eael-social-feed-masonry-',
                'condition' => [
                	'eael_facebook_feed_type' => 'masonry'
                ],
            ]
        );

		$this->add_control(
			'eael_facebook_feed_content_length',
			[
				'label' => esc_html__( 'Content Length', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => '400'
			]
		);

		$this->add_responsive_control(
			'eael_facebook_feed_column_spacing',
			[
				'label' => esc_html__( 'Column spacing', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element' => 'padding: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_facebook_feed_post_limit',
			[
				'label' => esc_html__( 'Post Limit', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'default' => 10
			]
		);

		$this->add_control(
			'eael_facebook_feed_media',
			[
				'label' => esc_html__( 'Show Media Elements', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
			]
		);

  		$this->end_controls_section();

  		$this->start_controls_section(
  			'eael_section_facebook_feed_card_settings',
  			[
  				'label' => esc_html__( 'Card Settings', 'essential-addons-elementor' ),
  			]
  		);

  		$this->add_control(
			'eael_facebook_feed_show_avatar',
			[
				'label' => esc_html__( 'Show Avatar', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
            'eael_facebook_feed_avatar_style',
            [
                'label' => __( 'Avatar Style', 'essential-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'circle' => 'Circle',
                    'square' => 'Square'
                ],
                'default' => 'circle',
                'prefix_class' => 'eael-social-feed-avatar-',
                'condition' => [
                	'eael_facebook_feed_show_avatar' => 'true'
                ],
            ]
        );

		$this->add_control(
			'eael_facebook_feed_show_date',
			[
				'label' => esc_html__( 'Show Date', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'eael_facebook_feed_show_read_more',
			[
				'label' => esc_html__( 'Show Read More', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
			]
		);
		$this->add_control(
			'eael_facebook_feed_show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'essential-addons-elementor' ),
				'label_off' => __( 'no', 'essential-addons-elementor' ),
				'default' => 'true',
				'return_value' => 'true',
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
		 * Tab Style (Facebook Feed Title Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_facebook_feed_style_settings',
			[
				'label' => esc_html__( 'General Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_facebook_feed_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-facebook-feed-wrapper' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_facebook_feed_container_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-facebook-feed-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_facebook_feed_container_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-facebook-feed-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_facebook_feed_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-facebook-feed-wrapper',
			]
		);

		$this->add_control(
			'eael_facebook_feed_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-facebook-feed-wrapper' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_facebook_feed_shadow',
				'selector' => '{{WRAPPER}} .eael-facebook-feed-wrapper',
			]
		);

  		$this->end_controls_section();

  		/**
		 * -------------------------------------------
		 * Tab Style (Facebook Feed Card Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_facebook_feed_card_style_settings',
			[
				'label' => esc_html__( 'Card Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_facebook_feed_card_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element .eael-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_facebook_feed_card_container_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-social-feed-element .eael-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_responsive_control(
			'eael_facebook_feed_card_container_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
	 					'{{WRAPPER}} .eael-social-feed-element .eael-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_facebook_feed_card_border',
				'label' => esc_html__( 'Border', 'essential-addons-elementor' ),
				'selector' => '{{WRAPPER}} .eael-social-feed-element .eael-content',
			]
		);

		$this->add_control(
			'eael_facebook_feed_card_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element .eael-content' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_facebook_feed_card_shadow',
				'selector' => '{{WRAPPER}} .eael-social-feed-element .eael-content',
			]
		);

  		$this->end_controls_section();

  		/**
		 * -------------------------------------------
		 * Tab Style (Twitter Feed Typography Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_facebook_feed_card_typo_settings',
			[
				'label' => esc_html__( 'Color &amp; Typography', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_facebook_feed_title_heading',
			[
				'label' => esc_html__( 'Title Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_facebook_feed_title_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element .author-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_facebook_feed_title_typography',
				'selector' => '{{WRAPPER}} .eael-social-feed-element .author-title',
			]
		);
		// Content Style
		$this->add_control(
			'eael_facebook_feed_content_heading',
			[
				'label' => esc_html__( 'Content Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_facebook_feed_content_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element .social-feed-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_facebook_feed_content_typography',
				'selector' => '{{WRAPPER}} .eael-social-feed-element .social-feed-text',
			]
		);

		// Content Link Style
		$this->add_control(
			'eael_facebook_feed_content_link_heading',
			[
				'label' => esc_html__( 'Link Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'eael_facebook_feed_content_link_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element .text-wrapper a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_facebook_feed_content_link_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-social-feed-element .text-wrapper a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
             'name' => 'eael_facebook_feed_content_link_typography',
				'selector' => '{{WRAPPER}} .eael-social-feed-element .text-wrapper a',
			]
		);

  		$this->end_controls_section();

  		/**
		 * -------------------------------------------
		 * Tab Style (Facebook Feed Preloader Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_facebook_feed_card_preloader_settings',
			[
				'label' => esc_html__( 'Preloader Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_facebook_feed_preloader_size',
			[
				'label' => esc_html__( 'Size', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-loading-feed .loader' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
				],
			]
		);

		$this->add_control(
			'eael_section_facebook_feed_preloader_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#3498db',
				'selectors' => [
					'{{WRAPPER}} .eael-loading-feed .loader' => 'border-top-color: {{VALUE}};',
				],
			]
		);


  		$this->end_controls_section();

	}


	protected function render( ) {

		$settings = $this->get_settings();

		if( 'list' == $settings['eael_facebook_feed_type'] ) {
			$feed_class = 'list-view';
		}elseif( 'masonry' == $settings['eael_facebook_feed_type'] ) {
			$feed_class = 'masonry-view';
		}
		  
		$this->add_render_attribute( 'eael-facebook-feed', 'data-facebook-feed-ac-name', $settings['eael_facebook_feed_ac_name'] );
		$this->add_render_attribute( 'eael-facebook-feed', 'data-facebook-feed-post-limit', $settings['eael_facebook_feed_post_limit'] );
		$this->add_render_attribute( 'eael-facebook-feed', 'data-facebook-feed-app-id', $settings['eael_facebook_feed_app_id'] );
		$this->add_render_attribute( 'eael-facebook-feed', 'data-facebook-feed-app-secret', $settings['eael_facebook_feed_app_secret'] );
		$this->add_render_attribute( 'eael-facebook-feed', 'data-facebook-feed-content-length', $settings['eael_facebook_feed_content_length'] );
		$this->add_render_attribute( 'eael-facebook-feed', 'data-facebook-feed-media', $settings['eael_facebook_feed_media'] );
		$this->add_render_attribute( 'eael-facebook-feed', 'data-facebook-feed-type', $settings['eael_facebook_feed_type'] );
		$this->add_render_attribute( 'eael-facebook-feed', 'data-facebook-feed-id', esc_attr($this->get_id()) ); 
	?>
	<div class="eael-facebook-feed-wrapper eael-facebook-feed-layout-wrapper" <?php echo $this->get_render_attribute_string( 'eael-facebook-feed' ); ?> >
		<div id="eael-facebook-feed-<?php echo esc_attr($this->get_id()); ?>" class="eael-facebook-feed-container eael-facebook-feed-layout-container <?php echo esc_attr( $feed_class ); ?>"></div>
		<div class="eael-loading-feed"><div class="loader"></div></div>
	</div>

	

	<?php
		echo '<style>';
		// Show Avatar
		if( $settings['eael_facebook_feed_show_avatar'] == 'true' ) {
			echo '.eael-social-feed-element .auth-img { display: block; }';
		}else {
			echo '.eael-social-feed-element .auth-img { display: none; }';
		}
		// Show Date
		if( $settings['eael_facebook_feed_show_date'] == 'true' ) {
			echo '.eael-social-feed-element .social-feed-date { display: block;  }';
		}else {
			echo '.eael-social-feed-element .social-feed-date { display: none;  }';
		}
		//  Show Read More
		 if( $settings['eael_facebook_feed_show_read_more'] == 'true' ) {
		 	echo '.eael-social-feed-element .read-more-link { display: block }';
		 }else {
		 	echo '.eael-social-feed-element .read-more-link { display: none !important; }';
		 }

		 //  Show Icon
		 if( $settings['eael_facebook_feed_show_icon'] == 'true' ) {
		 	echo '.eael-social-feed-element .social-feed-icon { display: inline-block }';
		 }else {
		 	echo '.eael-social-feed-element .social-feed-icon { display: none !important; }';
		 }

		// Masonry Grid
		if( $settings['eael_facebook_feed_type_col_type'] == 'col-2' ) {
			$width = '50%';
		}else if( $settings['eael_facebook_feed_type_col_type'] == 'col-3' ) {
			$width = '33.33%';
		}else if( $settings['eael_facebook_feed_type_col_type'] == 'col-4' ) {
			$width = '25%';
			echo '.eael-social-feed-element .social-feed-date { text-align: left; width: 100%; margin-bottom: 8px;}';
		}
		echo '.eael-facebook-feed-container.masonry-view .eael-social-feed-element { width: '.$width.' }
		.eael-social-feed-element .media-object { width: 30px; }';

		echo '</style>';
	}

	protected function content_template() {''

		?>


		<?php
	}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Facebook_Feed() );
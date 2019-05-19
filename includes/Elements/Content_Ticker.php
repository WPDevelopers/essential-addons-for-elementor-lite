<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Widget_Base as Widget_Base;
use \Essential_Addons_Elementor\Classes\Bootstrap;

class Content_Ticker extends Widget_Base {
	use \Essential_Addons_Elementor\Traits\Helper;

	public function get_name() {
		return 'eael-content-ticker';
	}

	public function get_title() {
		return esc_html__( 'EA Content Ticker', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
	}

	 public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	protected function _register_controls() {
		/**
		 * Content Ticker Content Settings
		 */
		$this->start_controls_section(
			'eael_section_content_ticker_settings',
			[
				'label' => esc_html__( 'Ticker Settings', 'essential-addons-elementor' )
			]
		);

		$ticker_options = apply_filters(
			'eael_ticker_options',
			[
				'options'	=> [
					'dynamic'     => esc_html__( 'Dynamic', 'essential-addons-elementor' ),
					'custom'      => esc_html__( 'Custom', 'essential-addons-elementor' ),
				],
				'conditions'	=> [
					'custom'
				]
			]
		);

		$this->add_control(
			'eael_ticker_type',
			[
				'label'       => esc_html__( 'Ticker Type', 'essential-addons-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'dynamic',
				'label_block' => false,
				'options'     => $ticker_options['options']
			]
		);

		$this->add_control(
			'eael_ticker_type_pro_alert',
			[
				'label'     => esc_html__( 'Custom Content available in pro version only!', 'essential-addons-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'eael_ticker_type' => $ticker_options['conditions'],
				]
			]
		);

		$this->add_control(
			'eael_ticker_tag_text',
			[
				'label' => esc_html__( 'Tag Text', 'essential-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => esc_html__( 'Trending Today', 'essential-addons-elementor' ),
			]
		);

		
		$this->end_controls_section();

		/**
		 * Query Controls
		 * @source includes/elementor-helper.php
		 */
		$this->eael_query_controls();

		do_action('eael_ticker_custom_content_controls', $this);

		/**
         * Content Tab: Carousel Settings
         */
        $this->start_controls_section(
            'section_additional_options',
            [
                'label'                 => __( 'Animation Settings', 'essential-addons-elementor' ),
            ]
        );
        
        $this->add_control(
            'carousel_effect',
            [
                'label'                 => __( 'Effect', 'essential-addons-elementor' ),
                'description'           => __( 'Sets transition effect', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'slide',
                'options'               => [
                    'slide'     => __( 'Slide', 'essential-addons-elementor' ),
                    'fade'      => __( 'Fade', 'essential-addons-elementor' ),
                ],
            ]
        );
        
        $this->add_responsive_control(
            'items',
            [
                'label'                 => __( 'Visible Items', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 1 ],
                'tablet_default'        => [ 'size' => 1 ],
                'mobile_default'        => [ 'size' => 1 ],
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 10,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'condition'             => [
                    'carousel_effect'   => 'slide',
                ],
                'separator'             => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'margin',
            [
                'label'                 => __( 'Items Gap', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 10 ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'condition'             => [
                    'carousel_effect'   => 'slide',
                ],
            ]
        );
        
        $this->add_control(
            'slider_speed',
            [
                'label'                 => __( 'Slider Speed', 'essential-addons-elementor' ),
                'description'           => __( 'Duration of transition between slides (in ms)', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 400 ],
                'range'                 => [
                    'px' => [
                        'min'   => 100,
                        'max'   => 3000,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'separator'             => 'before',
            ]
        );
        
		$this->add_control(
            'autoplay',
            [
                'label'                 => __( 'Autoplay', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'             => __( 'No', 'essential-addons-elementor' ),
                'return_value'          => 'yes',
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'autoplay_speed',
            [
                'label'                 => __( 'Autoplay Speed', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 2000 ],
                'range'                 => [
                    'px' => [
                        'min'   => 500,
                        'max'   => 5000,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'condition'         => [
                    'autoplay'      => 'yes',
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label'                 => __( 'Pause On Hover', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'             => __( 'No', 'essential-addons-elementor' ),
                'return_value'          => 'yes',
                'condition'         => [
                    'autoplay'      => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'infinite_loop',
            [
                'label'                 => __( 'Infinite Loop', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'             => __( 'No', 'essential-addons-elementor' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'grab_cursor',
            [
                'label'                 => __( 'Grab Cursor', 'essential-addons-elementor' ),
                'description'           => __( 'Shows grab cursor when you hover over the slider', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'          => __( 'Show', 'essential-addons-elementor' ),
                'label_off'         => __( 'Hide', 'essential-addons-elementor' ),
                'return_value'      => 'yes',
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'navigation_heading',
            [
                'label'                 => __( 'Navigation', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'arrows',
            [
                'label'                 => __( 'Arrows', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'essential-addons-elementor' ),
                'label_off'             => __( 'No', 'essential-addons-elementor' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'direction',
            [
                'label'                 => __( 'Direction', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'left',
                'options'               => [
                    'left'       => __( 'Left', 'essential-addons-elementor' ),
                    'right'      => __( 'Right', 'essential-addons-elementor' ),
                ],
				'separator'             => 'before',
				'condition'             => [
                    'carousel_effect'   => 'slide',
                ],
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
		 * Tab Style (Ticker Content Style)
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'eael_section_ticker_typography_settings',
			[
				'label' => esc_html__( 'Ticker Content', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'eael_ticker_content_bg',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-ticker-wrap .eael-ticker' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_ticker_content_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .eael-ticker-wrap .eael-ticker .ticker-content a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'eael_ticker_hover_content_color',
			[
				'label' => esc_html__( 'Text Hover Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f44336',
				'selectors' => [
					'{{WRAPPER}} .eael-ticker-wrap .eael-ticker .ticker-content a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_ticker_content_typography',
				'selector' =>'{{WRAPPER}} .eael-ticker-wrap .eael-ticker .ticker-content a',

			]
		);

		$this->add_responsive_control(
			'eael_ticker_content_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-ticker-wrap .eael-ticker .ticker-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'eael_section_ticker_tag_style_settings',
			[
				'label' => esc_html__( 'Tag Style', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		$this->add_control(
			'eael_ticker_tag_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .eael-ticker-wrap .ticker-badge' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'eael_ticker_tag_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-ticker-wrap .ticker-badge span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'eael_ticker_tag_typography',
				'selector' => '{{WRAPPER}} .eael-ticker-wrap .ticker-badge span',
			]
		);
		$this->add_responsive_control(
			'eael_ticker_tag_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-ticker-wrap .ticker-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_ticker_tag_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-ticker-wrap .ticker-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'eael_ticker_tag_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-ticker-wrap .ticker-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		/**
         * Style Tab: Arrows
         */
        $this->start_controls_section(
            'section_arrows_style',
            [
                'label'                 => __( 'Arrows', 'essential-addons-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'arrows'        => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'arrow',
            [
                'label'                 => __( 'Choose Arrow', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::ICON,
                'label_block'           => true,
                'default'               => 'fa fa-angle-right',
                'include'               => [
                    'fa fa-angle-right',
                    'fa fa-angle-double-right',
                    'fa fa-chevron-right',
                    'fa fa-chevron-circle-right',
                    'fa fa-arrow-right',
                    'fa fa-long-arrow-right',
                    'fa fa-caret-right',
                    'fa fa-caret-square-o-right',
                    'fa fa-arrow-circle-right',
                    'fa fa-arrow-circle-o-right',
                    'fa fa-toggle-right',
                    'fa fa-hand-o-right',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'arrows_size',
            [
                'label'                 => __( 'Arrows Size', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => '22' ],
                'range'                 => [
                    'px' => [
                        'min'   => 5,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
				],
            ]
        );
        
        $this->add_responsive_control(
            'left_arrow_position',
            [
                'label'                 => __( 'Align Left Arrow', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -100,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'         => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'right: {{SIZE}}{{UNIT}};',
				],
            ]
        );
        
        $this->add_responsive_control(
            'right_arrow_position',
            [
                'label'                 => __( 'Align Right Arrow', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -100,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'         => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_arrows_normal',
            [
                'label'                 => __( 'Normal', 'essential-addons-elementor' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_normal',
            [
                'label'                 => __( 'Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'arrows_border_normal',
				'label'                 => __( 'Border', 'essential-addons-elementor' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev'
			]
		);

		$this->add_control(
			'arrows_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'essential-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_arrows_hover',
            [
                'label'                 => __( 'Hover', 'essential-addons-elementor' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_hover',
            [
                'label'                 => __( 'Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			[
				'label'                 => __( 'Padding', 'essential-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'             => 'before',
			]
		);
        
        $this->end_controls_section();
	}


	protected function render( ) {
		$settings = $this->get_settings();

		/**
		 * Setup the post arguments.
		 */
		$settings['post_style'] = 'ticker';
		$post_args = $this->eael_get_post_settings( $settings );
		$query_args = $this->eael_get_query_args( 'eaeposts', $settings);
		$query_args = array_merge( $query_args, $post_args );

		/**
		 * Get posts from database.
		 */
		$posts = $this->eael_load_more_ajax( $query_args );
		/**
		 * Render the content
		 */
		$this->add_render_attribute( 'content-ticker-wrap', 'class', 'swiper-container-wrap eael-ticker' );
        
		$this->add_render_attribute( 'content-ticker', 'class', 'swiper-container eael-content-ticker' );
		$this->add_render_attribute( 'content-ticker', 'class', 'swiper-container-'.esc_attr( $this->get_id() ) );
		$this->add_render_attribute( 'content-ticker', 'data-pagination', '.swiper-pagination-'.esc_attr( $this->get_id() ) );
		$this->add_render_attribute( 'content-ticker', 'data-arrow-next', '.swiper-button-next-'.esc_attr( $this->get_id() ) );
		$this->add_render_attribute( 'content-ticker', 'data-arrow-prev', '.swiper-button-prev-'.esc_attr( $this->get_id() ) );
  
		if ( $settings['direction'] == 'right' ) {
			$this->add_render_attribute( 'content-ticker', 'dir', 'rtl' );
		}
  
		if ( ! empty( $settings['items']['size'] ) ) {
			$this->add_render_attribute( 'content-ticker', 'data-items', $settings['items']['size'] );
		}
		if ( ! empty( $settings['items_tablet']['size'] ) ) {
			$this->add_render_attribute( 'content-ticker', 'data-items-tablet', $settings['items_tablet']['size'] );
		}
		if ( ! empty( $settings['items_mobile']['size'] ) ) {
			$this->add_render_attribute( 'content-ticker', 'data-items-mobile', $settings['items_mobile']['size'] );
		}
		if ( ! empty( $settings['margin']['size'] ) ) {
			$this->add_render_attribute( 'content-ticker', 'data-margin', $settings['margin']['size'] );
		}
		if ( ! empty( $settings['margin_tablet']['size'] ) ) {
			$this->add_render_attribute( 'content-ticker', 'data-margin-tablet', $settings['margin_tablet']['size'] );
		}
		if ( ! empty( $settings['margin_mobile']['size'] ) ) {
			$this->add_render_attribute( 'content-ticker', 'data-margin-mobile', $settings['margin_mobile']['size'] );
		}
		if ( $settings['carousel_effect'] ) {
			$this->add_render_attribute( 'content-ticker', 'data-effect', $settings['carousel_effect'] );
		}
		if ( ! empty( $settings['slider_speed']['size'] ) ) {
			$this->add_render_attribute( 'content-ticker', 'data-speed', $settings['slider_speed']['size'] );
		}
		if ( $settings['autoplay'] == 'yes' && ! empty( $settings['autoplay_speed']['size'] ) ) {
			$this->add_render_attribute( 'content-ticker', 'data-autoplay', $settings['autoplay_speed']['size'] );
		} else {
			$this->add_render_attribute( 'content-ticker', 'data-autoplay', '999999' );
		}
		if( $settings['pause_on_hover'] == 'yes' ) {
            $this->add_render_attribute( 'content-ticker', 'data-pause-on-hover', 'true' );
        }
		if ( $settings['infinite_loop'] == 'yes' ) {
			$this->add_render_attribute( 'content-ticker', 'data-loop', true );
		}
		if ( $settings['grab_cursor'] == 'yes' ) {
			$this->add_render_attribute( 'content-ticker', 'data-grab-cursor', true );
		}
		if ( $settings['arrows'] == 'yes' ) {
			$this->add_render_attribute( 'content-ticker', 'data-arrows', '1' );
		}
  
		?>
		<div class="eael-ticker-wrap" id="eael-ticker-wrap-<?php echo $this->get_id(); ?>">
			<?php if( !empty($settings['eael_ticker_tag_text']) ) : ?>
			<div class="ticker-badge">
				<span><?php echo $settings['eael_ticker_tag_text']; ?></span>
			</div>
			<?php endif; ?>          
			<div <?php echo $this->get_render_attribute_string( 'content-ticker-wrap' ); ?> >
				<div <?php echo $this->get_render_attribute_string( 'content-ticker' ); ?> >
					<div class="swiper-wrapper">
						<?php 
							if( 'dynamic' === $settings['eael_ticker_type'] ) {
								if( ! empty( $posts['content'] ) ) {
									echo $posts['content'];
								} else {
									echo ' <div class="swiper-slide"><a href="#" class="ticker-content">'. __( 'Something went wrong!', 'essential-addons-elementor' ) .'</a></div>';
								}
							}
							do_action( 'render_content_ticker_custom_content', $settings );
						?>
					</div>
				</div>
				<?php $this->render_arrows(); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Content Ticker arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_arrows() {
        $settings = $this->get_settings_for_display();

        if ( $settings['arrows'] == 'yes' ) { ?>
            <?php
                if ( $settings['arrow'] ) {
                    $ticker_next_arrow = $settings['arrow'];
                    $ticker_prev_arrow = str_replace("right","left",$settings['arrow']);
                }
                else {
                    $ticker_next_arrow = 'fa fa-angle-right';
                    $ticker_prev_arrow = 'fa fa-angle-left';
                }
            ?>
            <!-- Add Arrows -->
            <div class="content-ticker-pagination">
	            <div class="swiper-button-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
	                <i class="<?php echo esc_attr( $ticker_next_arrow ); ?>"></i>
	            </div>
	            <div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
	                <i class="<?php echo esc_attr( $ticker_prev_arrow ); ?>"></i>
	            </div>
            </div>
        <?php }
    }

	protected function content_template() {}
}

<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Progress_Bar extends Widget_Base {

    public function get_name() {
		return 'eael-progress-bar';
	}

	public function get_title() {
		return esc_html__( 'EA Progress Bar', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'fa fa-tasks';
	}

    public function get_categories() {
		return [ 'essential-addons-elementor' ];
    }

    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	CONTENT TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Progress
         */
        $this->start_controls_section(
            'eael_section_progress_bar',
            [
                'label'                 => __( 'Progress', 'essential-addons-elementor' ),
            ]
        );

        $this->add_control(
			'progress_bar_show_title',
			[
				'label' => esc_html__( 'Display Title', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );

        $this->add_control(
            'progress_bar_title',
            [
                'label'                 => __( 'Title', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Progress Bar Title', 'essential-addons-elementor' ),
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'title_html_tag',
            [
                'label'                => __( 'Title HTML Tag', 'essential-addons-elementor' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'div',
                'options'              => [
                    'h1'     => __( 'H1', 'essential-addons-elementor' ),
                    'h2'     => __( 'H2', 'essential-addons-elementor' ),
                    'h3'     => __( 'H3', 'essential-addons-elementor' ),
                    'h4'     => __( 'H4', 'essential-addons-elementor' ),
                    'h5'     => __( 'H5', 'essential-addons-elementor' ),
                    'h6'     => __( 'H6', 'essential-addons-elementor' ),
                    'div'    => __( 'div', 'essential-addons-elementor' ),
                    'span'   => __( 'span', 'essential-addons-elementor' ),
                    'p'      => __( 'p', 'essential-addons-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'progress_bar_layout',
            [
                'label'                => __( 'Layout', 'essential-addons-elementor' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'line',
                'options'              => [
                    'line'         => __( 'Line', 'essential-addons-elementor' ),
                    'fan'          => __( 'Fan', 'essential-addons-elementor' ),
                    'circle'       => __( 'Circle', 'essential-addons-elementor' ),
                    'bubble'       => __( 'Bubble', 'essential-addons-elementor' ),
                    'rainbow'      => __( 'Rainbow', 'essential-addons-elementor' )
                ],
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
			'progress_bar_show_number',
			[
				'label' => esc_html__( 'Display Number', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
        );

        $this->add_control(
            'progress_number',
            [
                'label'                 => __( 'Number', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::NUMBER,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( '60', 'essential-addons-elementor' ),
                'separator'             => 'before',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Settings for line progress bar
         */

        $this->start_controls_section(
            'section_progress_bar_line_settings',
            [
                'label'                 => __( 'Line Progress Bar Settings ', 'essential-addons-elementor' ),
                'condition'             => [
                    'progress_bar_layout'   => 'line',
                ],
            ]
        );

        $this->add_control(
			'progress_bar_line_direction',
			[
				'label' => __( 'Direction', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'normal' => [
						'title' => __( 'Left To Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'reverse' => [
						'title' => __( 'Right To Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'normal',
			]
		);
        
        $this->add_control(
            'progress_bar_line_stroke_color',
            [
                'label'                 => __( 'Stroke Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#25b',
            ]
        );

        $this->add_control(
			'progress_bar_line_stroke_width',
			[
				'label' => __( 'Stroke Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
			]
		);

        $this->add_control(
            'progress_bar_line_stroke_trail_color',
            [
                'label'                 => __( 'Stroke Trail Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#ddd',
            ]
        );

        $this->add_control(
			'progress_bar_line_stroke_trail_width',
			[
				'label' => __( 'Stroke Trail Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => .5,
				'default' => .5,
			]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Settings for fan progress bar
         */

        $this->start_controls_section(
            'section_progress_bar_fan_settings',
            [
                'label'                 => __( 'Fan Progress Bar Settings ', 'essential-addons-elementor' ),
                'condition'             => [
                    'progress_bar_layout'   => 'fan',
                ],
            ]
        );

        $this->add_control(
			'progress_bar_fan_direction',
			[
				'label' => __( 'Direction', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'ltr' => [
						'title' => __( 'Left To Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'rtl' => [
						'title' => __( 'Right To Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
                    ],
                    'btt' => [
						'title' => __( 'Bottom To Top', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-up',
                    ],
                    'ttb' => [
						'title' => __( 'Top To Bottom', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-down',
                    ],
                ],
                'default' => 'btt',
			]
		);
        
        $this->add_control(
            'progress_bar_fan_stroke_color',
            [
                'label'                 => __( 'Stroke Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#25b',
            ]
        );

        $this->add_control(
			'progress_bar_fan_stroke_width',
			[
				'label' => __( 'Stroke Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
			]
		);

        $this->add_control(
            'progress_bar_fan_stroke_trail_color',
            [
                'label'                 => __( 'Stroke Trail Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#ddd',
            ]
        );

        $this->add_control(
			'progress_bar_fan_stroke_trail_width',
			[
				'label' => __( 'Stroke Trail Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 1,
			]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Settings for circle progress bar
         */

        $this->start_controls_section(
            'section_progress_bar_circle_settings',
            [
                'label'                 => __( 'Circle Progress Bar Settings ', 'essential-addons-elementor' ),
                'condition'             => [
                    'progress_bar_layout'   => 'circle',
                ],
            ]
        );

        $this->add_control(
			'progress_bar_circle_direction',
			[
				'label' => __( 'Direction', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'ltr' => [
						'title' => __( 'Left To Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'rtl' => [
						'title' => __( 'Right To Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
                    ],
                    'btt' => [
						'title' => __( 'Bottom To Top', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-up',
                    ],
                    'ttb' => [
						'title' => __( 'Top To Bottom', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-down',
                    ],
                ],
                'default' => 'btt',
			]
		);
        
        $this->add_control(
            'progress_bar_circle_stroke_color',
            [
                'label'                 => __( 'Stroke Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#25b',
            ]
        );

        $this->add_control(
			'progress_bar_circle_stroke_width',
			[
				'label' => __( 'Stroke Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
			]
		);

        $this->add_control(
            'progress_bar_circle_stroke_trail_color',
            [
                'label'                 => __( 'Stroke Trail Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#ddd',
            ]
        );

        $this->add_control(
			'progress_bar_circle_stroke_trail_width',
			[
				'label' => __( 'Stroke Trail Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 1,
			]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Settings for bubble progress bar
         */

        $this->start_controls_section(
            'section_progress_bar_bubble_settings',
            [
                'label'                 => __( 'Bubble Progress Bar Settings ', 'essential-addons-elementor' ),
                'condition'             => [
                    'progress_bar_layout'   => 'bubble',
                ],
            ]
        );

        $this->add_control(
			'progress_bar_bubble_direction',
			[
				'label' => __( 'Direction', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'ltr' => [
						'title' => __( 'Left To Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'rtl' => [
						'title' => __( 'Right To Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
                    ],
                    'btt' => [
						'title' => __( 'Bottom To Top', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-up',
                    ],
                    'ttb' => [
						'title' => __( 'Top To Bottom', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-down',
                    ],
                ],
                'default' => 'btt',
			]
		);
        
        $this->add_control(
            'progress_bar_bubble_circle_color',
            [
                'label'                 => __( 'Bubble Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#cef',
            ]
        );

        $this->add_control(
            'progress_bar_bubble_bg_color',
            [
                'label'                 => __( 'Background Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#39d',
            ]
        );

        $this->add_control(
			'progress_bar_bubble_circle_width',
			[
				'label' => __( 'Bubble Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 5,
				'max' => 150,
				'step' => 1,
				'default' => 50,
			]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Settings for rainbow progress bar
         */

        $this->start_controls_section(
            'section_progress_bar_rainbow_settings',
            [
                'label'                 => __( 'Rainbow Progress Bar Settings ', 'essential-addons-elementor' ),
                'condition'             => [
                    'progress_bar_layout'   => 'rainbow',
                ],
            ]
        );

        $this->add_control(
			'progress_bar_rainbow_direction',
			[
				'label' => __( 'Direction', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'normal' => [
						'title' => __( 'Left To Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'reverse' => [
						'title' => __( 'Right To Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'normal',
			]
		);

        $this->add_control(
			'progress_bar_rainbow_stroke_width',
			[
				'label' => __( 'Stroke Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
			]
		);

        $this->add_control(
			'progress_bar_rainbow_stroke_trail_width',
			[
				'label' => __( 'Stroke Trail Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => .5,
				'default' => .5,
			]
        );

        $this->add_control(
            'progress_bar_rainbow_color_one',
            [
                'label'                 => __( 'Gradient Color One', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#a551df',
            ]
        );

        $this->add_control(
            'progress_bar_rainbow_color_two',
            [
                'label'                 => __( 'Gradient Color Two', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#fd51ad',
            ]
        );

        $this->add_control(
            'progress_bar_rainbow_color_three',
            [
                'label'                 => __( 'Gradient Color Three', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#ff7f82',
            ]
        );

        $this->add_control(
            'progress_bar_rainbow_color_four',
            [
                'label'                 => __( 'Gradient Color Four', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#ffb874',
            ]
        );

        $this->add_control(
            'progress_bar_rainbow_color_five',
            [
                'label'                 => __( 'Gradient Color Five', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#ffeb90',
            ]
        );



        $this->end_controls_section();

        /**
         * Content Tab: Settings for energy progress bar
         */

        $this->start_controls_section(
            'section_progress_bar_energy_settings',
            [
                'label'                 => __( 'Energy Progress Bar Settings ', 'essential-addons-elementor' ),
                'condition'             => [
                    'progress_bar_layout'   => 'energy',
                ],
            ]
        );

        $this->add_control(
			'progress_bar_energy_direction',
			[
				'label' => __( 'Direction', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'ltr' => [
						'title' => __( 'Left To Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'rtl' => [
						'title' => __( 'Right To Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
                    ],
                    'btt' => [
						'title' => __( 'Bottom To Top', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-up',
                    ],
                    'ttb' => [
						'title' => __( 'Top To Bottom', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-down',
                    ],
                ],
                'default' => 'ltr',
			]
		);
        
        $this->add_control(
            'progress_bar_energy_start_color',
            [
                'label'                 => __( 'Start Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#f00',
            ]
        );

        $this->add_control(
            'progress_bar_energy_end_color',
            [
                'label'                 => __( 'End Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#ff0',
            ]
        );

        $this->add_control(
            'progress_bar_energy_bg_color',
            [
                'label'                 => __( 'Background Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#444',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Settings for stripe progress bar
         */

        $this->start_controls_section(
            'section_progress_bar_stripe_settings',
            [
                'label'                 => __( 'Stripe Bar Settings ', 'essential-addons-elementor' ),
                'condition'             => [
                    'progress_bar_layout'   => 'stripe',
                ],
            ]
        );

        $this->add_control(
			'progress_bar_stripe_direction',
			[
				'label' => __( 'Direction', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'ltr' => [
						'title' => __( 'Left To Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'rtl' => [
						'title' => __( 'Right To Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
                    ],
                    'btt' => [
						'title' => __( 'Bottom To Top', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-up',
                    ],
                    'ttb' => [
						'title' => __( 'Top To Bottom', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-down',
                    ],
                ],
                'default' => 'rtl',
			]
		);
        
        $this->add_control(
            'progress_bar_stripe_start_color',
            [
                'label'                 => __( 'Start Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#f00',
            ]
        );

        $this->add_control(
            'progress_bar_stripe_end_color',
            [
                'label'                 => __( 'End Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#ff0',
            ]
        );

        $this->add_control(
            'progress_bar_stripe_bg_color',
            [
                'label'                 => __( 'Background Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#ddd',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Settings for text progress bar
         */

        $this->start_controls_section(
            'section_progress_bar_text_settings',
            [
                'label'                 => __( 'Text Progress Bar Settings ', 'essential-addons-elementor' ),
                'condition'             => [
                    'progress_bar_layout'   => 'text',
                ],
            ]
        );

        $this->add_control(
            'progress_bar_text_title',
            [
                'label'                 => __( 'Title for Text Progress Bar', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => __( 'LOADING', 'essential-addons-elementor' ),
                'dynamic'               => [
					'active'   => true,
				],
            ]
        );

        $this->add_control(
			'progress_bar_text_direction',
			[
				'label' => __( 'Direction', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'ltr' => [
						'title' => __( 'Left To Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'rtl' => [
						'title' => __( 'Right To Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
                    ],
                    'btt' => [
						'title' => __( 'Bottom To Top', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-up',
                    ],
                    'ttb' => [
						'title' => __( 'Top To Bottom', 'essential-addons-elementor' ),
						'icon' => 'fa fa-arrow-down',
                    ],
                ],
                'default' => 'ltr',
			]
		);

        $this->add_control(
            'progress_bar_text_bg_color',
            [
                'label'                 => __( 'Background Color', 'essential-addons-elementor' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#ddd',
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

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Style Tab: Progress Bar
         */
        $this->start_controls_section(
            'progress_bar_section_style',
            [
                'label'                 => __( 'Style', 'essential-addons-elementor' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'progress_bar_width',
			[
				'label' => __( 'Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .ldBar' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
        );
        
        $this->add_control(
			'progress_bar_height',
			[
				'label' => __( 'Height', 'plugin-domain' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ldBar' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
        );
        
        $this->add_control(
			'progress_bar_title_style',
			[
				'label' => __( 'Title Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'progress_bar_title_color',
			[
				'label' => __( 'Title Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#303133',
				'selectors' => [
					'{{WRAPPER}} .progress-title' => 'color: {{VALUE}};',
				]

			]
		);

        $this->add_control(
			'progress_bar_title_hover_color',
			[
				'label' => __( 'Title Hover Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#23527c',
				'selectors' => [
					'{{WRAPPER}} .progress-title:hover' => 'color: {{VALUE}};',
				]

			]
		);

		$this->add_responsive_control(
			'progress_bar_title_alignment',
			[
				'label' => __( 'Title Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .progress-title' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'progress_bar_title_typography',
				'label' => __( 'Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .progress-title',
			]
        );
        
        $this->add_control(
			'progress_bar_number_style',
			[
				'label' => __( 'Number Style', 'essential-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
			'progress_bar_number_color',
			[
				'label' => __( 'Number Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#303133',
				'selectors' => [
					'{{WRAPPER}} .ldBar-label' => 'color: {{VALUE}};',
				]

			]
		);

        $this->add_control(
			'progress_bar_number_hover_color',
			[
				'label' => __( 'Number Hover Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default'=> '#23527c',
				'selectors' => [
					'{{WRAPPER}} .ldBar-label:hover' => 'color: {{VALUE}};',
				]

			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'progress_bar_number_typography',
				'label' => __( 'Typography', 'essential-addons-elementor' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .ldBar-label',
			]
        );
        
        $this->end_controls_section();

}

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute('eael-progress-bar-container', [
            'class' => [ 'eael-progress-bar-container' ],
            'data-layout'   => $settings['progress_bar_layout'],
            'data-id'       => esc_attr($this->get_id()),
            'data-number'   => $settings['progress_number']
        ]);

        if('line' == $settings['progress_bar_layout']){
            $this->add_render_attribute('eael-progress-bar-container', [
                'data-line-stroke-color'        => $settings['progress_bar_line_stroke_color'],
                'data-line-stroke-width'        => $settings['progress_bar_line_stroke_width'],
                'data-line-stroke-trail-color'  => $settings['progress_bar_line_stroke_trail_color'],
                'data-line-stroke-trail-width'  => $settings['progress_bar_line_stroke_trail_width'],
                'data-line-direction'           => $settings['progress_bar_line_direction']
            ]);
        }
        
        if('fan' == $settings['progress_bar_layout']){
            $this->add_render_attribute('eael-progress-bar-container', [
                'data-fan-stroke-color'         => $settings['progress_bar_fan_stroke_color'],
                'data-fan-stroke-width'         => $settings['progress_bar_fan_stroke_width'],
                'data-fan-stroke-trail-color'   => $settings['progress_bar_fan_stroke_trail_color'],
                'data-fan-stroke-trail-width'   => $settings['progress_bar_fan_stroke_trail_width'],
                'data-fan-direction'            => $settings['progress_bar_fan_direction']
            ]);
        }
       
        if('circle' == $settings['progress_bar_layout']){
            $this->add_render_attribute('eael-progress-bar-container', [
                'data-circle-stroke-color'          => $settings['progress_bar_circle_stroke_color'],
                'data-circle-stroke-width'          => $settings['progress_bar_circle_stroke_width'],
                'data-circle-stroke-trail-color'    => $settings['progress_bar_circle_stroke_trail_color'],
                'data-circle-stroke-trail-width'    => $settings['progress_bar_circle_stroke_trail_width'],
                'data-circle-direction'             => $settings['progress_bar_circle_direction']
            ]);
        }
        
        if('bubble' == $settings['progress_bar_layout']){
            $this->add_render_attribute('eael-progress-bar-container', [
                'data-bubble-circle-color'  => $settings['progress_bar_bubble_circle_color'],
                'data-bubble-bg-color'      => $settings['progress_bar_bubble_bg_color'],
                'data-bubble-circle-width'  => $settings['progress_bar_bubble_circle_width'],
                'data-bubble-direction'     => $settings['progress_bar_bubble_direction']
            ]);
        }
       
        if('rainbow' == $settings['progress_bar_layout']){
            $this->add_render_attribute('eael-progress-bar-container', [
                'data-rainbow-stroke-width'         => $settings['progress_bar_rainbow_stroke_width'],
                'data-rainbow-stroke-trail-width'   => $settings['progress_bar_rainbow_stroke_trail_width'],
                'data-rainbow-color-one'            => $settings['progress_bar_rainbow_color_one'],
                'data-rainbow-color-two'            => $settings['progress_bar_rainbow_color_two'],
                'data-rainbow-color-three'          => $settings['progress_bar_rainbow_color_three'],
                'data-rainbow-color-four'           => $settings['progress_bar_rainbow_color_four'],
                'data-rainbow-color-five'           => $settings['progress_bar_rainbow_color_five'],
                'data-rainbow-direction'            => $settings['progress_bar_rainbow_direction']
            ]);
        }
        
        if('energy' == $settings['progress_bar_layout']){
            $this->add_render_attribute('eael-progress-bar-container', [
                'data-energy-start-color'   => $settings['progress_bar_energy_start_color'],
                'data-energy-end-color'     => $settings['progress_bar_energy_end_color'],
                'data-energy-bg-color'      => $settings['progress_bar_energy_bg_color'],
                'data-energy-direction'     => $settings['progress_bar_energy_direction']
            ]);
        }
        
        if('stripe' == $settings['progress_bar_layout']){
            $this->add_render_attribute('eael-progress-bar-container', [
                'data-stripe-start-color'   => $settings['progress_bar_stripe_start_color'],
                'data-stripe-end-color'     => $settings['progress_bar_stripe_end_color'],
                'data-stripe-bg-color'      => $settings['progress_bar_stripe_bg_color'],
                'data-stripe-direction'     => $settings['progress_bar_stripe_direction']
            ]);
        }
        
        if('text' == $settings['progress_bar_layout']){
            $this->add_render_attribute('eael-progress-bar-container', [
                'data-text-title'       => $settings['progress_bar_text_title'],
                'data-text-direction'   => $settings['progress_bar_text_direction'],
                'data-text-bg-color'    => $settings['progress_bar_text_bg_color'],
            ]);
        }
        

        if(
            'fan' == $settings['progress_bar_layout'] ||
            'circle' == $settings['progress_bar_layout'] ||
            'bubble' == $settings['progress_bar_layout']
        ){
            $class = 'label-center';
            $height = '150px';
        }else {
            $class = '';
            $height = '30px';
        }


        $this->add_render_attribute('inside-progressbar',[
            'style' => 'height:'.$height,
            'class' => [ 'inside-progressbar', 'ldBar', 'auto', $class ],
            'id'    => 'myItem'.esc_attr($this->get_id()),
            'data-preset'   => $settings['progress_bar_layout'],
        ]);


        $this->add_render_attribute('progressbar-title', 'class', 'progress-title');
        if( 'line' == $settings['progress_bar_layout'] ) {
            $this->add_render_attribute('progressbar-title', 'class', 'line');
        }


        ?>

            <div <?php echo $this->get_render_attribute_string( 'eael-progress-bar-container' ); ?>>
                <?php if (  'yes' == $settings['progress_bar_show_title'] ) : ?>
                    <div <?php echo $this->get_render_attribute_string('progressbar-title'); ?>>
                        <?php printf( '<%1$s>', $settings['title_html_tag'] ); echo $settings['progress_bar_title']; printf( '</%1$s>', $settings['title_html_tag'] ); ?>
                    </div>
                <?php endif; ?>
                <div <?php echo $this->get_render_attribute_string('inside-progressbar'); ?>></div>
                <?php if ( 'yes' != $settings['progress_bar_show_number'] ) : ?><style>.ldBar-label{display:none;}</style><?php endif; ?>
            </div>    
           
        <?php
    }

}

Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Progress_Bar() );    
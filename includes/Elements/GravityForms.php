<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Scheme_Typography;
use \Elementor\Widget_Base;

use \Essential_Addons_Elementor\Classes\Helper;


/**
 * Gravity Forms Widget
 */
class GravityForms extends Widget_Base {
    
    /**
	 * Retrieve gravity forms widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'eael-gravity-form';
    }

    /**
	 * Retrieve gravity forms widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Gravity Forms', 'essential-addons-for-elementor-lite');
    }

    /**
	 * Retrieve the list of categories the gravity forms widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
    public function get_categories() {
        return [ 'essential-addons-elementor' ];
    }
    
    public function get_keywords()
    {
        return [
            'contact form',
            'ea contact form',
            'ea gravity form',
            'ea form styler',
            'form styler',
            'elementor form',
            'feedback',
            'gravityforms',
            'ea',
            'essential addons'
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/gravity-forms/';
    }

    /**
	 * Retrieve gravity forms widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'eaicon-gravity-form';
    }

    /**
	 * Register gravity forms widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	CONTENT TAB
        /*-----------------------------------------------------------------------------------*/
        if(!class_exists('\GFForms')) {
            $this->start_controls_section(
                'eael_global_warning',
                [
                    'label'             => __( 'Warning!', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'eael_global_warning_text',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => __( '<strong>Gravity Forms</strong> is not installed/activated on your site. Please install and activate <strong>Gravity Forms</strong> first.', 'essential-addons-for-elementor-lite'),
                    'content_classes' => 'eael-warning',
                ]
            );

            $this->end_controls_section();
        } else {
            /**
             * Content Tab: Contact Form
             * -------------------------------------------------
             */
            $this->start_controls_section(
                'section_info_box',
                [
                    'label'                 => __( 'Gravity Forms', 'essential-addons-for-elementor-lite'),
                ]
            );
            
            $this->add_control(
                'contact_form_list',
                [
                    'label'                 => esc_html__( 'Select Form', 'essential-addons-for-elementor-lite'),
                    'type'                  => Controls_Manager::SELECT,
                    'label_block'           => true,
                    'options'               => Helper::get_gravity_form_list(),
                    'default'               => '0',
                ]
            );
            
            $this->add_control(
                'custom_title_description',
                [
                    'label'                 => __( 'Custom Title & Description', 'essential-addons-for-elementor-lite'),
                    'type'                  => Controls_Manager::SWITCHER,
                    'label_on'              => __( 'Yes', 'essential-addons-for-elementor-lite'),
                    'label_off'             => __( 'No', 'essential-addons-for-elementor-lite'),
                    'return_value'          => 'yes',
                ]
            );
            
            $this->add_control(
                'form_title',
                [
                    'label'                 => __( 'Title', 'essential-addons-for-elementor-lite'),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'yes',
                    'label_on'              => __( 'Show', 'essential-addons-for-elementor-lite'),
                    'label_off'             => __( 'Hide', 'essential-addons-for-elementor-lite'),
                    'return_value'          => 'yes',
                    'condition'             => [
                        'custom_title_description!'   => 'yes',
                    ],
                ]
            );
            
            $this->add_control(
                'form_description',
                [
                    'label'                 => __( 'Description', 'essential-addons-for-elementor-lite'),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'yes',
                    'label_on'              => __( 'Show', 'essential-addons-for-elementor-lite'),
                    'label_off'             => __( 'Hide', 'essential-addons-for-elementor-lite'),
                    'return_value'          => 'yes',
                    'condition'             => [
                        'custom_title_description!'   => 'yes',
                    ],
                ]
            );
            
            $this->add_control(
                'form_title_custom',
                [
                    'label'                 => esc_html__( 'Title', 'essential-addons-for-elementor-lite'),
                    'type'                  => Controls_Manager::TEXT,
                    'label_block'           => true,
                    'default'               => '',
                    'condition'             => [
                        'custom_title_description'   => 'yes',
                    ],
                ]
            );
            
            $this->add_control(
                'form_description_custom',
                [
                    'label'                 => esc_html__( 'Description', 'essential-addons-for-elementor-lite'),
                    'type'                  => Controls_Manager::TEXTAREA,
                    'default'               => '',
                    'condition'             => [
                        'custom_title_description'   => 'yes',
                    ],
                ]
            );
            
            $this->add_control(
                'labels_switch',
                [
                    'label'                 => __( 'Labels', 'essential-addons-for-elementor-lite'),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'yes',
                    'label_on'              => __( 'Show', 'essential-addons-for-elementor-lite'),
                    'label_off'             => __( 'Hide', 'essential-addons-for-elementor-lite'),
                    'return_value'          => 'yes',
                ]
            );
            
            $this->add_control(
                'placeholder_switch',
                [
                    'label'                 => __( 'Placeholder', 'essential-addons-for-elementor-lite'),
                    'type'                  => Controls_Manager::SWITCHER,
                    'default'               => 'yes',
                    'label_on'              => __( 'Show', 'essential-addons-for-elementor-lite'),
                    'label_off'             => __( 'Hide', 'essential-addons-for-elementor-lite'),
                    'return_value'          => 'yes',
                ]
            );
            
            $this->add_control(
                'form_ajax',
                [
                    'label'                 => __( 'Use Ajax', 'essential-addons-for-elementor-lite'),
                    'type'                  => Controls_Manager::SWITCHER,
                    'description'           => __( 'Use ajax to submit the form', 'essential-addons-for-elementor-lite'),
                    'label_on'              => __( 'Yes', 'essential-addons-for-elementor-lite'),
                    'label_off'             => __( 'No', 'essential-addons-for-elementor-lite'),
                    'return_value'          => 'yes',
                ]
            );
            
            $this->end_controls_section();

            /**
             * Content Tab: Errors
             * -------------------------------------------------
             */
            $this->start_controls_section(
                'section_errors',
                [
                    'label'                 => __( 'Errors', 'essential-addons-for-elementor-lite'),
                ]
            );
            
            $this->add_control(
                'error_messages',
                [
                    'label'                 => __( 'Error Messages', 'essential-addons-for-elementor-lite'),
                    'type'                  => Controls_Manager::SELECT,
                    'default'               => 'show',
                    'options'               => [
                        'show'          => __( 'Show', 'essential-addons-for-elementor-lite'),
                        'hide'          => __( 'Hide', 'essential-addons-for-elementor-lite'),
                    ],
                    'selectors_dictionary'  => [
                        'show'          => 'block',
                        'hide'          => 'none',
                    ],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-gravity-form .validation_message' => 'display: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_control(
                'validation_errors',
                [
                    'label'                 => __( 'Validation Errors', 'essential-addons-for-elementor-lite'),
                    'type'                  => Controls_Manager::SELECT,
                    'default'               => 'show',
                    'options'               => [
                        'show'          => __( 'Show', 'essential-addons-for-elementor-lite'),
                        'hide'          => __( 'Hide', 'essential-addons-for-elementor-lite'),
                    ],
                    'selectors_dictionary'  => [
                        'show'          => 'block',
                        'hide'          => 'none',
                    ],
                    'selectors'             => [
                        '{{WRAPPER}} .eael-gravity-form .validation_error' => 'display: {{VALUE}} !important;',
                    ],
                ]
            );
            
            $this->end_controls_section();
        }

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Form Container
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_container_style',
            [
                'label'                 => __( 'Form Container', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'eael_gravity_form_background',
			[
				'label' => esc_html__( 'Form Background Color', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'eael_gravity_form_alignment',
			[
				'label' => esc_html__( 'Form Alignment', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'default' => [
						'title' => __( 'Default', 'essential-addons-for-elementor-lite'),
						'icon' => 'fa fa-ban',
					],
					'left' => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'default',
			]
		);

  		$this->add_responsive_control(
  			'eael_gravity_form_width',
  			[
  				'label' => esc_html__( 'Form Width', 'essential-addons-for-elementor-lite'),
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
					'{{WRAPPER}} .eael-contact-form' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

  		$this->add_responsive_control(
  			'eael_gravity_form_max_width',
  			[
  				'label' => esc_html__( 'Form Max Width', 'essential-addons-for-elementor-lite'),
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
					'{{WRAPPER}} .eael-contact-form' => 'max-width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
		
		
		$this->add_responsive_control(
			'eael_gravity_form_margin',
			[
				'label' => esc_html__( 'Form Margin', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		
		$this->add_responsive_control(
			'eael_gravity_form_padding',
			[
				'label' => esc_html__( 'Form Padding', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_control(
			'eael_gravity_form_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'separator' => 'before',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .eael-contact-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'eael_gravity_form_border',
				'selector' => '{{WRAPPER}} .eael-contact-form',
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'eael_gravity_form_box_shadow',
				'selector' => '{{WRAPPER}} .eael-contact-form',
			]
		);

        $this->end_controls_section();
        /**
         * Style Tab: Title and Description
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_general_style',
            [
                'label'                 => __( 'Title & Description', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'heading_alignment',
            [
                'label'                 => __( 'Alignment', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite'),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'essential-addons-for-elementor-lite'),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite'),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gform_wrapper .gform_heading' => 'text-align: {{VALUE}};',
				],
			]
		);
        
        $this->add_control(
            'title_heading',
            [
                'label'                 => __( 'Title', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper .gform_title, {{WRAPPER}} .eael-gravity-form .eael-gravity-form-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'essential-addons-for-elementor-lite'),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .eael-gravity-form .gform_wrapper .gform_title, {{WRAPPER}} .eael-gravity-form .eael-gravity-form-title',
            ]
        );
        
        $this->add_control(
            'description_heading',
            [
                'label'                 => __( 'Description', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
            ]
        );

        $this->add_control(
            'description_text_color',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper .gform_description, {{WRAPPER}} .eael-gravity-form .eael-gravity-form-description' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'description_typography',
                'label'                 => __( 'Typography', 'essential-addons-for-elementor-lite'),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .eael-gravity-form .gform_wrapper .gform_description, {{WRAPPER}} .eael-gravity-form .eael-gravity-form-description',
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Labels
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_label_style',
            [
                'label'                 => __( 'Labels', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'labels_switch'   => 'yes',
                ],
            ]
        );

        $this->add_control(
            'text_color_label',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield label' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'labels_switch'   => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'typography_label',
                'label'                 => __( 'Typography', 'essential-addons-for-elementor-lite'),
                'selector'              => '{{WRAPPER}} .eael-gravity-form .gfield label',
                'condition'             => [
                    'labels_switch'   => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Input & Textarea
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_fields_style',
            [
                'label'                 => __( 'Input & Textarea', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'input_alignment',
            [
                'label'                 => __( 'Alignment', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'essential-addons-for-elementor-lite'),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'essential-addons-for-elementor-lite'),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'essential-addons-for-elementor-lite'),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gfield input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield textarea' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_fields_style' );

        $this->start_controls_tab(
            'tab_fields_normal',
            [
                'label'                 => __( 'Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'field_bg_color',
            [
                'label'                 => __( 'Background Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield textarea, {{WRAPPER}} .eael-gravity-form .gfield select' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield textarea, {{WRAPPER}} .eael-gravity-form .gfield select' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'field_spacing',
            [
                'label'                 => __( 'Spacing', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_spacing_right',
            [
                'label'                 => __( 'Spacing Right', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield.gf_left_half' => 'padding-right: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

		$this->add_responsive_control(
			'field_padding',
			[
				'label'                 => __( 'Padding', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gform_wrapper input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .eael-gravity-form .gfield textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'text_indent',
            [
                'label'                 => __( 'Text Indent', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 60,
                        'step'  => 1,
                    ],
                    '%'         => [
                        'min'   => 0,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield textarea, {{WRAPPER}} .eael-gravity-form .gfield select' => 'text-indent: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'input_width',
            [
                'label'                 => __( 'Input Width', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield select' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'input_height',
            [
                'label'                 => __( 'Input Height', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield input[type="email"], {{WRAPPER}} .eael-gravity-form .gfield input[type="url"], {{WRAPPER}} .eael-gravity-form .gfield select' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'textarea_width',
            [
                'label'                 => __( 'Textarea Width', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield textarea' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'textarea_height',
            [
                'label'                 => __( 'Textarea Height', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 400,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield textarea' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'field_border',
				'label'                 => __( 'Border', 'essential-addons-for-elementor-lite'),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .eael-gravity-form .gfield input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield textarea, {{WRAPPER}} .eael-gravity-form .gfield select',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'field_radius',
			[
				'label'                 => __( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gfield input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'field_typography',
                'label'                 => __( 'Typography', 'essential-addons-for-elementor-lite'),
                'selector'              => '{{WRAPPER}} .eael-gravity-form .gfield .ginput_complex.ginput_container input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield .ginput_container_date input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield .ginput_container_phone input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield .ginput_container_email input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield .ginput_container_text input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield textarea, {{WRAPPER}} .eael-gravity-form .gfield select',
				'separator'             => 'before',
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'field_box_shadow',
				'selector'              => '{{WRAPPER}} .eael-gravity-form .gfield input[type="text"], {{WRAPPER}} .eael-gravity-form .gfield textarea, {{WRAPPER}} .eael-gravity-form .gfield select',
				'separator'             => 'before',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_fields_focus',
            [
                'label'                 => __( 'Focus', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'field_bg_color_focus',
            [
                'label'                 => __( 'Background Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield input:focus, {{WRAPPER}} .eael-gravity-form .gfield textarea:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'focus_input_border',
				'label'                 => __( 'Border', 'essential-addons-for-elementor-lite'),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .eael-gravity-form .gfield input:focus, {{WRAPPER}} .eael-gravity-form .gfield textarea:focus',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'focus_box_shadow',
				'selector'              => '{{WRAPPER}} .eael-gravity-form .gfield input:focus, {{WRAPPER}} .eael-gravity-form .gfield textarea:focus',
				'separator'             => 'before',
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Field Description
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_field_description_style',
            [
                'label'                 => __( 'Field Description', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'field_description_text_color',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield .gfield_description' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'field_description_typography',
                'label'                 => __( 'Typography', 'essential-addons-for-elementor-lite'),
                'selector'              => '{{WRAPPER}} .eael-gravity-form .gfield .gfield_description',
            ]
        );
        
        $this->add_responsive_control(
            'field_description_spacing',
            [
                'label'                 => __( 'Spacing', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield .gfield_description' => 'padding-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Section Field
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_field_style',
            [
                'label'                 => __( 'Section Field', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'section_field_text_color',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield.gsection .gsection_title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'section_field_typography',
                'label'                 => __( 'Typography', 'essential-addons-for-elementor-lite'),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .eael-gravity-form .gfield.gsection .gsection_title',
				'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'section_field_border_type',
            [
                'label'                 => __( 'Border Type', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'solid',
                'options'               => [
                    'none'      => __( 'None', 'essential-addons-for-elementor-lite'),
                    'solid'     => __( 'Solid', 'essential-addons-for-elementor-lite'),
                    'double'    => __( 'Double', 'essential-addons-for-elementor-lite'),
                    'dotted'    => __( 'Dotted', 'essential-addons-for-elementor-lite'),
                    'dashed'    => __( 'Dashed', 'essential-addons-for-elementor-lite'),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield.gsection' => 'border-bottom-style: {{VALUE}}',
                ],
				'separator'             => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'section_field_border_height',
            [
                'label'                 => __( 'Border Height', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => 1,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 20,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield.gsection' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'section_field_border_type!'   => 'none',
                ],
            ]
        );

        $this->add_control(
            'section_field_border_color',
            [
                'label'                 => __( 'Border Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield.gsection' => 'border-bottom-color: {{VALUE}}',
                ],
                'condition'             => [
                    'section_field_border_type!'   => 'none',
                ],
            ]
        );

		$this->add_responsive_control(
			'section_field_margin',
			[
				'label'                 => __( 'Margin', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gfield.gsection' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'             => 'before',
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Section Field
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_price_style',
            [
                'label'                 => __( 'Price', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_label_color',
            [
                'label'                 => __( 'Price Label Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper .ginput_product_price_label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'price_text_color',
            [
                'label'                 => __( 'Price Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper .ginput_product_price' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Section Field
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_total_price_style',
            [
                'label'                 => __( 'Total Price', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'total_price_typography',
                'label'                 => __( 'Typography', 'essential-addons-for-elementor-lite'),
                'selector'              => '{{WRAPPER}} .eael-gravity-form .gform_wrapper .ginput_container_total .ginput_total',
				'separator'             => 'before',
            ]
        );

        $this->add_control(
            'total_price_text_color',
            [
                'label'                 => __( 'Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper .ginput_container_total .ginput_total' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Placeholder
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_placeholder_style',
            [
                'label'                 => __( 'Placeholder', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'placeholder_switch'   => 'yes',
                ],
            ]
        );

        $this->add_control(
            'text_color_placeholder',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield input::-webkit-input-placeholder, {{WRAPPER}} .eael-gravity-form .gfield textarea::-webkit-input-placeholder' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'placeholder_switch'   => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Style Tab: Checkbox
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_checkbox_style',
            [
                'label'                 => __( 'Checkbox', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'custom_radio_checkbox',
            [
                'label'                 => __( 'Custom Styles', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SWITCHER,
                'label_on'              => __( 'Yes', 'essential-addons-for-elementor-lite'),
                'label_off'             => __( 'No', 'essential-addons-for-elementor-lite'),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_responsive_control(
            'checkbox_size',
            [
                'label'                 => __( 'Size', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'      => '15',
                    'unit'      => 'px'
                ],
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"]' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_checkbox_style' );

        $this->start_controls_tab(
            'checkbox_normal',
            [
                'label'                 => __( 'Normal', 'essential-addons-for-elementor-lite'),
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'checkbox_color',
            [
                'label'                 => __( 'Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"]' => 'background: {{VALUE}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'checkbox_border_width',
            [
                'label'                 => __( 'Border Width', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 15,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"]' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'checkbox_border_color',
            [
                'label'                 => __( 'Border Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"]' => 'border-color: {{VALUE}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'checkbox_heading',
            [
                'label'                 => __( 'Checkbox', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::HEADING,
				'condition'             => [
					'custom_radio_checkbox' => 'yes',
				],
            ]
        );

		$this->add_control(
			'checkbox_border_radius',
			[
				'label'                 => __( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"]:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
			]
        );
        
        $this->add_responsive_control(
			'checkbox_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'checkbox_padding',
			[
				'label'                 => __( 'Padding', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'checkbox_checked',
            [
                'label'                 => __( 'Checked', 'essential-addons-for-elementor-lite'),
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'checkbox_color_checked',
            [
                'label'                 => __( 'Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"]:checked:before' => 'background: {{VALUE}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Radio
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_radio_style',
            [
                'label'                 => __( 'Radio', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'custom_radio_style',
            [
                'label'                 => __( 'Custom Styles', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SWITCHER,
                'label_on'              => __( 'Yes', 'essential-addons-for-elementor-lite'),
                'label_off'             => __( 'No', 'essential-addons-for-elementor-lite'),
                'return_value'          => 'yes',
            ]
        );

        $this->add_responsive_control(
            'radio_size',
            [
                'label'                 => __( 'Size', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'      => '15',
                    'unit'      => 'px'
                ],
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"]' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'custom_radio_style' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_radio_style' );

        $this->start_controls_tab(
            'radio_normal',
            [
                'label'                 => __( 'Normal', 'essential-addons-for-elementor-lite'),
                'condition'             => [
                    'custom_radio_style' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_color',
            [
                'label'                 => __( 'Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"]' => 'background: {{VALUE}}',
                ],
                'condition'             => [
                    'custom_radio_style' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'radio_border_width',
            [
                'label'                 => __( 'Border Width', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 15,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"]' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'custom_radio_style' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_border_color',
            [
                'label'                 => __( 'Border Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"]' => 'border-color: {{VALUE}}',
                ],
                'condition'             => [
                    'custom_radio_style' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'radio_heading',
            [
                'label'                 => __( 'Radio Buttons', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::HEADING,
				'condition'             => [
					'custom_radio_style' => 'yes',
				],
            ]
        );

		$this->add_control(
			'radio_border_radius',
			[
				'label'                 => __( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"], {{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"]:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'custom_radio_style' => 'yes',
                ],
			]
        );
        
        $this->add_responsive_control(
			'radio_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'radio_padding',
			[
				'label'                 => __( 'Padding', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'radio_checked',
            [
                'label'                 => __( 'Checked', 'essential-addons-for-elementor-lite'),
                'condition'             => [
                    'custom_radio_style' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_color_checked',
            [
                'label'                 => __( 'Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"]:checked:before' => 'background: {{VALUE}}',
                ],
                'condition'             => [
                    'custom_radio_style' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        /**
         * Style Tab: Scrolling Text
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'scrolling_text_style',
            [
                'label' => __( 'Scrolling Text', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'scrolling_text_typography',
                'label'                 => __( 'Typography', 'essential-addons-for-elementor-lite'),
                'selector'              => '{{WRAPPER}} .eael-gravity-form .gform_wrapper .gf_scroll_text .gsection_description',
				'separator'             => 'before',
            ]
        );

        $this->add_control(
            'scrolling_text_color',
            [
                'label'                 => __( 'Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper .gf_scroll_text .gsection_description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'scrolling_text_bg_color',
            [
                'label'                 => __( 'Background Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper .gf_scroll_text .gsection_description' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'scrolling_text_width',
            [
                'label'                 => __( 'Width', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'      => '100',
                    'unit'      => '%'
                ],
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper .gf_scroll_text' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'scrolling_text_border',
				'label'                 => __( 'Border', 'essential-addons-for-elementor-lite'),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .eael-gravity-form .gform_wrapper .gf_scroll_text',
			]
        );
        
        $this->add_control(
			'scrolling_text_border_radius',
			[
				'label'                 => __( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gform_wrapper .gf_scroll_text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
			]
        );
        
        $this->add_responsive_control(
			'scrolling_text_padding',
			[
				'label'                 => __( 'Padding', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gform_wrapper .gf_scroll_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper .gf_scroll_text .gsection_description' => 'margin: 0;',
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper .gf_scroll_text::-webkit-scrollbar' => 'border:2px solid #009900;',
                ],
			]
        );
        
        $this->add_responsive_control(
			'scrolling_text_margin',
			[
				'label' => esc_html__( 'Margin', 'essential-addons-for-elementor-lite'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-gravity-form .gform_wrapper .gf_scroll_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Submit Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_submit_button_style',
            [
                'label'                 => __( 'Submit Button', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'button_align',
			[
				'label'                 => __( 'Alignment', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'        => [
						'title'   => __( 'Left', 'essential-addons-for-elementor-lite'),
						'icon'    => 'eicon-h-align-left',
					],
					'center'      => [
						'title'   => __( 'Center', 'essential-addons-for-elementor-lite'),
						'icon'    => 'eicon-h-align-center',
					],
					'right'       => [
						'title'   => __( 'Right', 'essential-addons-for-elementor-lite'),
						'icon'    => 'eicon-h-align-right',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gform_footer'   => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"]' => 'display:inline-block;'
				],
                'condition'             => [
                    'button_width_type' => 'custom',
                ],
			]
		);
        
        $this->add_control(
            'button_width_type',
            [
                'label'                 => __( 'Width', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'custom',
                'options'               => [
                    'full-width'    => __( 'Full Width', 'essential-addons-for-elementor-lite'),
                    'custom'        => __( 'Custom', 'essential-addons-for-elementor-lite'),
                ],
                'prefix_class'          => 'eael-gravity-form-button-',
            ]
        );
        
        $this->add_responsive_control(
            'button_width',
            [
                'label'                 => __( 'Width', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'      => '100',
                    'unit'      => 'px'
                ],
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"]' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="submit"]' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'button_width_type' => 'custom',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label'                 => __( 'Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'button_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"]' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="submit"]' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_normal',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"]' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="submit"]' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'button_border_normal',
				'label'                 => __( 'Border', 'essential-addons-for-elementor-lite'),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"], {{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="submit"]',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => __( 'Padding', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
			]
		);
        
        $this->add_responsive_control(
            'button_margin',
            [
                'label'                 => __( 'Margin Top', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"]' => 'margin-top: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="submit"]' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label'                 => __( 'Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"]:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="submit"]' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"]:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="submit"]:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"]:hover' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="submit"]:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'button_typography',
                'label'                 => __( 'Typography', 'essential-addons-for-elementor-lite'),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"], {{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="submit"]',
				'separator'             => 'before',
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .eael-gravity-form .gform_footer input[type="submit"], {{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="submit"]',
				'separator'             => 'before',
			]
		);
        
        $this->end_controls_section();


        /**
         * Style Tab: Next Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_gravity_forms_section_next_button_style',
            [
                'label'                 => __( 'Next/Previous Button', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'eael_gravity_forms_next_button_align',
			[
				'label'                 => __( 'Alignment', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'        => [
						'title'   => __( 'Left', 'essential-addons-for-elementor-lite'),
						'icon'    => 'eicon-h-align-left',
					],
					'center'      => [
						'title'   => __( 'Center', 'essential-addons-for-elementor-lite'),
						'icon'    => 'eicon-h-align-center',
					],
					'right'       => [
						'title'   => __( 'Right', 'essential-addons-for-elementor-lite'),
						'icon'    => 'eicon-h-align-right',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer'   => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]' => 'display:inline-block;'
				],
			]
		);

        $this->add_responsive_control(
            'eael_gravity_forms_next_button_width',
            [
                'label'                 => __( 'Width', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'      => '100',
                    'unit'      => 'px'
                ],
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'eael_gravity_forms_tabs_next_button_style' );

        $this->start_controls_tab(
            'eael_gravity_forms_tab_next_button_normal',
            [
                'label'                 => __( 'Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_gravity_forms_next_button_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_gravity_forms_next_button_text_color_normal',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'eael_gravity_forms_next_button_border_normal',
				'label'                 => __( 'Border', 'essential-addons-for-elementor-lite'),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]',
			]
		);

		$this->add_control(
			'eael_gravity_forms_next_button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_gravity_forms_next_button_padding',
			[
				'label'                 => __( 'Padding', 'essential-addons-for-elementor-lite'),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'eael_gravity_forms_next_button_margin',
            [
                'label'                 => __( 'Margin Top', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_gravity_forms_tab_next_button_hover',
            [
                'label'                 => __( 'Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_gravity_forms_next_button_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_gravity_forms_next_button_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_gravity_forms_next_button_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'eael_gravity_forms_next_button_typography',
                'label'                 => __( 'Typography', 'essential-addons-for-elementor-lite'),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]',
				'separator'             => 'before',
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'eael_gravity_forms_next_button_box_shadow',
				'selector'              => '{{WRAPPER}} .eael-gravity-form .gform_body .gform_page_footer input[type="button"]',
				'separator'             => 'before',
			]
		);

        $this->end_controls_section();

        
        /**
         * Style Tab: Errors
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_error_style',
            [
                'label'                 => __( 'Errors', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'error_messages_heading',
            [
                'label'                 => __( 'Error Messages', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::HEADING,
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

        $this->add_control(
            'error_message_text_color',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield .validation_message' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );
        
        $this->add_control(
            'validation_errors_heading',
            [
                'label'                 => __( 'Validation Errors', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );

        $this->add_control(
            'validation_error_description_color',
            [
                'label'                 => __( 'Error Description Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper .validation_error' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );

        $this->add_control(
            'validation_error_border_color',
            [
                'label'                 => __( 'Error Border Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper .validation_error' => 'border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}}',
                    '{{WRAPPER}} .eael-gravity-form .gfield_error' => 'border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}}',
                ],
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );

        $this->add_control(
            'validation_errors_bg_color',
            [
                'label'                 => __( 'Error Field Background Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield_error' => 'background: {{VALUE}}',
                ],
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );

        $this->add_control(
            'validation_error_field_label_color',
            [
                'label'                 => __( 'Error Field Label Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gfield_error .gfield_label' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );

        $this->add_control(
            'validation_error_field_input_border_color',
            [
                'label'                 => __( 'Error Field Input Border Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper li.gfield_error input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .gform_wrapper li.gfield_error textarea' => 'border-color: {{VALUE}}',
                ],
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );

        $this->add_control(
            'validation_error_field_input_border_width',
            [
                'label'                 => __( 'Error Field Input Border Width', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => 1,
                'min'                   => 1,
                'max'                   => 10,
                'step'                  => 1,
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_wrapper li.gfield_error input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .gform_wrapper li.gfield_error textarea' => 'border-width: {{VALUE}}px',
                ],
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Thank You Message
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_ty_style',
            [
                'label'                 => __( 'Thank You Message', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ty_message_text_color',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .eael-gravity-form .gform_confirmation_wrapper .gform_confirmation_message' => 'color: {{VALUE}}!important',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'eaelgf_thankyou_message_typography',
                'label'                 => __( 'Typography', 'essential-addons-for-elementor-lite'),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .eael-gravity-form .gform_confirmation_wrapper .gform_confirmation_message',
				'separator'             => 'before',
            ]
        );
        
        $this->end_controls_section();
    }

    /**
	 * Render gravity forms widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        if(!class_exists('\GFForms')) {
            return;
        }

        $settings = $this->get_settings();
        
        $this->add_render_attribute( 'contact-form', 'class', [
				'eael-contact-form',
				'eael-gravity-form',
			]
		);
        
        if ( $settings['labels_switch'] != 'yes' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'labels-hide' );
        }
        
        if ( $settings['placeholder_switch'] != 'yes' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'placeholder-hide' );
        }
        
        if ( $settings['custom_title_description'] == 'yes' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'title-description-hide' );
        }
        
        if ( $settings['custom_radio_checkbox'] == 'yes' || $settings['custom_radio_style'] == 'yes' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'eael-custom-radio-checkbox' );
        }

        if ( $settings['eael_gravity_form_alignment'] == 'left' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'eael-contact-form-align-left' );
        }
        elseif ( $settings['eael_gravity_form_alignment'] == 'center' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'eael-contact-form-align-center' );
        }
        elseif ( $settings['eael_gravity_form_alignment'] == 'right' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'eael-contact-form-align-right' );
        }
        else {
            $this->add_render_attribute( 'contact-form', 'class', 'eael-contact-form-align-default' );
        }

        if ( ! empty( $settings['contact_form_list'] ) ) { ?>
            <div <?php echo $this->get_render_attribute_string( 'contact-form' ); ?>>
                <?php if ( $settings['custom_title_description'] == 'yes' ) { ?>
                    <div class="eael-gravity-form-heading">
                        <?php if ( $settings['form_title_custom'] != '' ) { ?>
                            <h3 class="eael-contact-form-title eael-gravity-form-title">
                                <?php echo esc_attr( $settings['form_title_custom'] ); ?>
                            </h3>
                        <?php } ?>
                        <?php if ( $settings['form_description_custom'] != '' ) { ?>
                            <div class="eael-contact-form-description eael-gravity-form-description">
                                <?php echo $this->parse_text_editor( $settings['form_description_custom'] ); ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php
                    $eael_form_id = $settings['contact_form_list'];
                    $eael_form_title = $settings['form_title'];
                    $eael_form_description = $settings['form_description'];
                    $eael_form_ajax = $settings['form_ajax'];

                    gravity_form( $eael_form_id, $eael_form_title, $eael_form_description, $display_inactive = false, $field_values = null, $eael_form_ajax, '', $echo = true );
                ?>
            </div>
            <?php
        }
    }

    protected function _content_template() {}

}
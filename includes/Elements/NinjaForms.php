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
use \Elementor\Core\Schemes\Typography;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;
use \Ninja_Forms;

class NinjaForms extends Widget_Base
{
    public function get_name()
    {
        return 'eael-ninja';
    }

    public function get_title()
    {
        return __('Ninja Forms', 'essential-addons-for-elementor-lite');
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_icon()
    {
        return 'eaicon-ninja-forms';
    }

    public function get_keywords()
    {
        return [
            'contact form',
            'ea contact form',
            'form styler',
            'ea form styler',
            'ea ninjaform styler',
            'ea ninja form styler',
            'elementor form',
            'feedback',
            'ninjaforms',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/ninja-forms/';
    }

    protected function register_controls()
    {

        /*-----------------------------------------------------------------------------------*/
        /*    CONTENT TAB
        /*-----------------------------------------------------------------------------------*/
        if (!function_exists('Ninja_Forms')) {
            $this->start_controls_section(
                'eael_global_warning',
                [
                    'label' => __('Warning!', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'eael_global_warning_text',
                [
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => __('<strong>Ninja Forms</strong> is not installed/activated on your site. Please install and activate <strong>Ninja Forms</strong> first.', 'essential-addons-for-elementor-lite'),
                    'content_classes' => 'eael-warning',
                ]
            );

            $this->end_controls_section();
        } else {
            /**
             * Content Tab: Ninja Forms
             * -------------------------------------------------
             */
            $this->start_controls_section(
                'section_info_box',
                [
                    'label' => __('Ninja Forms', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'contact_form_list',
                [
                    'label' => esc_html__('Select Form', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'label_block' => true,
                    'options' => Helper::get_ninja_form_list(),
                    'default' => '0',
                ]
            );

            $this->add_control(
                'custom_title_description',
                [
                    'label' => __('Custom Title & Description', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'form_title',
                [
                    'label' => __('Title', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                    'prefix_class' => 'eael-ninja-form-title-',
                    'condition' => [
                        'custom_title_description!' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'form_title_custom',
                [
                    'label'                 => esc_html__( 'Title', 'essential-addons-for-elementor-lite'),
                    'type'                  => Controls_Manager::TEXT,
                    'dynamic' => [
                        'active' => true,
                    ],
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
                    'dynamic' => [
                        'active' => true,
                    ],
                    'default'               => '',
                    'condition'             => [
                        'custom_title_description'   => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'labels_switch',
                [
                    'label' => __('Labels', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
                    'prefix_class' => 'eael-ninja-form-labels-',
                ]
            );

            $this->add_control(
                'placeholder_switch',
                [
                    'label' => __('Placeholder', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                    'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                    'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                    'return_value' => 'yes',
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
                    'label' => __('Errors', 'essential-addons-for-elementor-lite'),
                ]
            );

            $this->add_control(
                'error_messages',
                [
                    'label' => __('Error Messages', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'show',
                    'options' => [
                        'show' => __('Show', 'essential-addons-for-elementor-lite'),
                        'hide' => __('Hide', 'essential-addons-for-elementor-lite'),
                    ],
                    'selectors_dictionary' => [
                        'show' => 'block',
                        'hide' => 'none',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-ninja-form .nf-error-wrap .nf-error-required-error' => 'display: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->add_control(
                'validation_errors',
                [
                    'label' => __('Validation Errors', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'show',
                    'options' => [
                        'show' => __('Show', 'essential-addons-for-elementor-lite'),
                        'hide' => __('Hide', 'essential-addons-for-elementor-lite'),
                    ],
                    'selectors_dictionary' => [
                        'show' => 'block',
                        'hide' => 'none',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-ninja-form .nf-form-errors .nf-error-field-errors' => 'display: {{VALUE}} !important;',
                    ],
                ]
            );

            $this->end_controls_section();

            /**
             * Style Tab: Form Container
             * -------------------------------------------------
             */
            $this->start_controls_section(
                'section_container_style',
                [
                    'label' => __('Form Container', 'essential-addons-for-elementor-lite'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
            );

            $this->add_control(
                'eael_contact_form_background',
                [
                    'label' => esc_html__('Form Background Color', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eael-contact-form' => 'background: {{VALUE}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'eael_contact_form_alignment',
                [
                    'label' => esc_html__('Form Alignment', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::CHOOSE,
                    'label_block' => true,
                    'options' => [
                        'default' => [
                            'title' => __('Default', 'essential-addons-for-elementor-lite'),
                            'icon' => 'fa fa-ban',
                        ],
                        'left' => [
                            'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                            'icon' => 'eicon-h-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                            'icon' => 'eicon-h-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                            'icon' => 'eicon-h-align-right',
                        ],
                    ],
                    'default' => 'default',
                ]
            );

            $this->add_responsive_control(
                'eael_contact_form_width',
                [
                    'label' => esc_html__('Form Width', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', 'em', '%'],
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
                'eael_contact_form_max_width',
                [
                    'label' => esc_html__('Form Max Width', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => ['px', 'em', '%'],
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
                'eael_contact_form_margin',
                [
                    'label' => esc_html__('Form Margin', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-contact-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'eael_contact_form_padding',
                [
                    'label' => esc_html__('Form Padding', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-contact-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'eael_contact_form_border_radius',
                [
                    'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'separator' => 'before',
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}} .eael-contact-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'eael_contact_form_border',
                    'selector' => '{{WRAPPER}} .eael-contact-form',
                ]
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'eael_contact_form_box_shadow',
                    'selector' => '{{WRAPPER}} .eael-contact-form',
                ]
            );

            $this->end_controls_section();
        }

        /*-----------------------------------------------------------------------------------*/
        /*    STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Form Title & Description
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_form_title_style',
            [
                'label' => __('Title & Description', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'heading_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-form-title h3, {{WRAPPER}} .eael-ninja-form-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_heading',
            [
                'label' => __('Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'form_title_text_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-form-title h3, {{WRAPPER}} .eael-contact-form-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'form_title_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-ninja-form .nf-form-title h3, {{WRAPPER}} .eael-contact-form-title',
            ]
        );

        $this->add_responsive_control(
            'form_title_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'allowed_dimensions' => 'vertical',
                'placeholder' => [
                    'top' => '',
                    'right' => 'auto',
                    'bottom' => '',
                    'left' => 'auto',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-form-title h3, {{WRAPPER}} .eael-contact-form-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'description_heading',
            [
                'label' => __('Description', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'form_description_text_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .eael-contact-form-description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'form_description_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme' => Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .eael-ninja-form .eael-contact-form-description',
            ]
        );

        $this->add_responsive_control(
            'form_description_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'allowed_dimensions' => 'vertical',
                'placeholder' => [
                    'top' => '',
                    'right' => 'auto',
                    'bottom' => '',
                    'left' => 'auto',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-contact-form-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
                'label' => __('Labels', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'labels_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'text_color_label',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field-label label' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'labels_switch' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography_label',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-ninja-form .nf-field-label label',
                'condition' => [
                    'labels_switch' => 'yes',
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
                'label' => __('Input & Textarea', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'input_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field input[type="text"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="email"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="tel"], {{WRAPPER}} .eael-ninja-form .nf-field textarea, {{WRAPPER}} .eael-ninja-form .nf-field select' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_fields_style');

        $this->start_controls_tab(
            'tab_fields_normal',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'field_bg_color',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field input[type="text"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="email"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="tel"], {{WRAPPER}} .eael-ninja-form .nf-field textarea, {{WRAPPER}} .eael-ninja-form .nf-field select' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field input[type="text"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="email"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="tel"], {{WRAPPER}} .eael-ninja-form .nf-field textarea, {{WRAPPER}} .eael-ninja-form .nf-field select' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'field_border',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .eael-ninja-form .nf-field input[type="text"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="email"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="tel"], {{WRAPPER}} .eael-ninja-form .nf-field textarea, {{WRAPPER}} .eael-ninja-form .nf-field select',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'field_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field input[type="text"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="email"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="tel"], {{WRAPPER}} .eael-ninja-form .nf-field textarea, {{WRAPPER}} .eael-ninja-form .nf-field select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'text_indent',
            [
                'label' => __('Text Indent', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 30,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field input[type="text"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="email"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="tel"], {{WRAPPER}} .eael-ninja-form .nf-field textarea, {{WRAPPER}} .eael-ninja-form .nf-field select' => 'text-indent: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'input_width',
            [
                'label' => __('Input Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field input[type="text"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="email"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="tel"], {{WRAPPER}} .eael-ninja-form .nf-field select' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'input_height',
            [
                'label' => __('Input Height', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field input[type="text"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="email"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="tel"], {{WRAPPER}} .eael-ninja-form .nf-field select' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'textarea_width',
            [
                'label' => __('Textarea Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field textarea' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'textarea_height',
            [
                'label' => __('Textarea Height', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 400,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field textarea' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field input[type="text"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="email"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="tel"], {{WRAPPER}} .eael-ninja-form .nf-field textarea, {{WRAPPER}} .eael-ninja-form .nf-field select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'field_spacing',
            [
                'label' => __('Spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field-container' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'field_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-ninja-form .nf-field input[type="text"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="email"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="tel"], {{WRAPPER}} .eael-ninja-form .nf-field textarea, {{WRAPPER}} .eael-ninja-form .nf-field select',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'field_box_shadow',
                'selector' => '{{WRAPPER}} .eael-ninja-form .nf-field input[type="text"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="email"], {{WRAPPER}} .eael-ninja-form .nf-field input[type="tel"], {{WRAPPER}} .eael-ninja-form .nf-field textarea, {{WRAPPER}} .eael-ninja-form .nf-field select',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_fields_focus',
            [
                'label' => __('Focus', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'field_bg_color_focus',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field input:focus, {{WRAPPER}} .eael-ninja-form .nf-field textarea:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'focus_input_border',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .eael-ninja-form .nf-field input:focus, {{WRAPPER}} .eael-ninja-form .nf-field textarea:focus',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'focus_box_shadow',
                'selector' => '{{WRAPPER}} .eael-ninja-form .nf-field input:focus, {{WRAPPER}} .eael-ninja-form .nf-field textarea:focus',
                'separator' => 'before',
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
                'label' => __('Field Description', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'field_description_text_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field .nf-field-description' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'field_description_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-ninja-form .nf-field .nf-field-description',
            ]
        );

        $this->add_responsive_control(
            'field_description_spacing',
            [
                'label' => __('Spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field .nf-field-description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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
                'label' => __('Placeholder', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'placeholder_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'text_color_placeholder',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-field input::-webkit-input-placeholder, {{WRAPPER}} .eael-ninja-form .nf-field textarea::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-ninja-form .nf-field input::-moz-input-placeholder, {{WRAPPER}} .eael-ninja-form .nf-field textarea::-moz-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-ninja-form .nf-field input:-ms-input-placeholder, {{WRAPPER}} .eael-ninja-form .nf-field textarea:-ms-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .eael-ninja-form .nf-field input:-moz-placeholder, {{WRAPPER}} .eael-ninja-form .nf-field textarea:-moz-placeholder' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'placeholder_switch' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Radio & Checkbox
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_radio_checkbox_style',
            [
                'label' => __('Radio & Checkbox', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'custom_radio_checkbox',
            [
                'label' => __('Custom Styles', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'radio_checkbox_size',
            [
                'label' => __('Size', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '15',
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"], {{WRAPPER}} .list-radio-wrap .nf-field-element li label:after, {{WRAPPER}} .listcheckbox-wrap .nf-field-element label:after, {{WRAPPER}} .checkbox-wrap .nf-field-label label:after' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_radio_checkbox_style');

        $this->start_controls_tab(
            'radio_checkbox_normal',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_checkbox_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"], {{WRAPPER}} .listradio-wrap .nf-field-element label:after, {{WRAPPER}} .listcheckbox-wrap .nf-field-element label:after, {{WRAPPER}} .checkbox-wrap .nf-field-label label:after' => 'background: {{VALUE}}',
                ],
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'radio_checkbox_border_width',
            [
                'label' => __('Border Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 15,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"], {{WRAPPER}} .listradio-wrap .nf-field-element label:after, {{WRAPPER}} .listcheckbox-wrap .nf-field-element label:after, {{WRAPPER}} .checkbox-wrap .nf-field-label label:after' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_checkbox_border_color',
            [
                'label' => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"], {{WRAPPER}} .listradio-wrap .nf-field-element label:after, {{WRAPPER}} .listcheckbox-wrap .nf-field-element label:after, {{WRAPPER}} .checkbox-wrap .nf-field-label label:after' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'checkbox_heading',
            [
                'label' => __('Checkbox', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'checkbox_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"]:before, {{WRAPPER}} .listcheckbox-wrap .nf-field-element label:after, {{WRAPPER}} .checkbox-wrap .nf-field-label label:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_heading',
            [
                'label' => __('Radio Buttons', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'radio_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"], {{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"]:before, {{WRAPPER}} .list-radio-wrap .nf-field-element li label:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'radio_checkbox_checked',
            [
                'label' => __('Checked', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_checkbox_color_checked',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-custom-radio-checkbox input[type="checkbox"]:checked:before, {{WRAPPER}} .eael-custom-radio-checkbox input[type="radio"]:checked:before, {{WRAPPER}} .listradio-wrap .nf-field-element label.nf-checked-label:before' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .checkbox-wrap .nf-field-label label.nf-checked-label:before, {{WRAPPER}} .listcheckbox-wrap .nf-field-element label.nf-checked-label:before' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'checked_checkbox_heading',
            [
                'label' => __('Checkbox', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'checked_checkbox_position_x_axis',
            [
                'label' => __('Position: X Axis', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .checkbox-container.label-right label:before' => 'left: -{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'checked_checkbox_position_y_axis',
            [
                'label' => __('Position: Y Axis', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .checkbox-container.label-right label:before' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'checked_radio_buttons_heading',
            [
                'label' => __('Radio Buttons', 'essential-addons-elementor'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'checked_radio_position_x_axis',
            [
                'label' => __('Position: X Axis', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .listradio-wrap .nf-field-element label.nf-checked-label:before' => 'left: -{{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'checked_radio_position_y_axis',
            [
                'label' => __('Position: Y Axis', 'essential-addons-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .listradio-wrap .nf-field-element label.nf-checked-label:before' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Submit Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_submit_button_style',
            [
                'label' => __('Submit Button', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_align',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .submit-container' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'button_width_type' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'button_width_type',
            [
                'label' => __('Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'full-width' => __('Full Width', 'essential-addons-for-elementor-lite'),
                    'custom' => __('Custom', 'essential-addons-for-elementor-lite'),
                ],
                'prefix_class' => 'eael-ninja-form-button-',
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label' => __('Width', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => '130',
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .submit-container input[type="button"]' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_width_type' => 'custom',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'button_bg_color_normal',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .submit-container input[type="button"]' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_normal',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .submit-container input[type="button"]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border_normal',
                'label' => __('Border', 'essential-addons-for-elementor-lite'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .eael-ninja-form .submit-container input[type="button"]',
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .submit-container input[type="button"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .submit-container input[type="button"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => __('Margin Top', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .submit-container input[type="button"]' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .submit-container input[type="button"]:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .submit-container input[type="button"]:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label' => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .submit-container input[type="button"]:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme' => Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .eael-ninja-form .submit-container input[type="button"]',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .eael-ninja-form .submit-container input[type="button"]',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Success Message
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_success_message_style',
            [
                'label' => __('Success Message', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'success_message_text_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-response-msg' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'success_message_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-ninja-form .nf-response-msg',
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Required Fields Notice
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_required_notice_style',
            [
                'label' => __('Required Fields Notice', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'required_notice_text_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-form-fields-required' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'required_notice_spacing',
            [
                'label' => __('Spacing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-form-fields-required' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'required_notice_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme' => Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .eael-ninja-form .nf-form-fields-required',
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
                'label' => __('Errors', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'error_messages_heading',
            [
                'label' => __('Error Messages', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'error_messages' => 'show',
                ],
            ]
        );

        $this->add_control(
            'error_message_text_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-error-wrap .nf-error-required-error' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'error_messages' => 'show',
                ],
            ]
        );

        $this->add_control(
            'validation_errors_heading',
            [
                'label' => __('Validation Errors', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'validation_errors' => 'show',
                ],
            ]
        );

        $this->add_control(
            'validation_error_description_color',
            [
                'label' => __('Error Description Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-form-errors .nf-error-field-errors' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'validation_errors' => 'show',
                ],
            ]
        );

        $this->add_control(
            'validation_error_field_input_border_color',
            [
                'label' => __('Error Field Input Border Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-ninja-form .nf-error .ninja-forms-field' => 'border-color: {{VALUE}}',
                ],
                'condition' => [
                    'validation_errors' => 'show',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        if (!class_exists('Ninja_Forms')) {
            return;
        }

        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('contact-form', 'class', [
            'eael-contact-form',
            'eael-ninja-form',
        ]);

        $this->add_render_attribute('contact-form', 'id', [
            'eael-ninja-form-' . get_the_ID(),
        ]);

        if ($settings['placeholder_switch'] != 'yes') {
            $this->add_render_attribute('contact-form', 'class', 'placeholder-hide');
        }

        if ($settings['custom_title_description'] == 'yes') {
            $this->add_render_attribute('contact-form', 'class', 'title-description-hide');
        }

        if ($settings['custom_radio_checkbox'] == 'yes') {
            $this->add_render_attribute('contact-form', 'class', 'eael-custom-radio-checkbox');
        }
        if ($settings['eael_contact_form_alignment'] == 'left') {
            $this->add_render_attribute('contact-form', 'class', 'eael-contact-form-align-left');
        } elseif ($settings['eael_contact_form_alignment'] == 'center') {
            $this->add_render_attribute('contact-form', 'class', 'eael-contact-form-align-center');
        } elseif ($settings['eael_contact_form_alignment'] == 'right') {
            $this->add_render_attribute('contact-form', 'class', 'eael-contact-form-align-right');
        } else {
            $this->add_render_attribute('contact-form', 'class', 'eael-contact-form-align-default');
        }

        if (!empty($settings['contact_form_list'])) {?>
            <div <?php echo $this->get_render_attribute_string('contact-form'); ?>>
                <?php if ($settings['custom_title_description'] == 'yes') {?>
                    <div class="eael-ninja-form-heading">
                        <?php if ($settings['form_title_custom'] != '') {?>
                            <h3 class="eael-contact-form-title eael-ninja-form-title">
                                <?php echo esc_attr($settings['form_title_custom']); ?>
                            </h3>
                        <?php }?>
                        <?php if ($settings['form_description_custom'] != '') {?>
                            <div class="eael-contact-form-description eael-ninja-form-description">
                                <?php echo $this->parse_text_editor($settings['form_description_custom']); ?>
                            </div>
                        <?php }?>
                    </div>
                <?php }?>
                <?php echo Ninja_Forms()->display($settings['contact_form_list']); ?>
            </div>
            <?php
        }
    }
}

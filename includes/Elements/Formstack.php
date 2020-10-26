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
use \Elementor\Group_Control_Background;
use \Elementor\Scheme_Color;

class Formstack extends Widget_Base {

    // 

    public function get_name () {
        return 'eael-formstack';
    }

    public function get_title () {
        return __('Formstack', 'essential-addons-for-elementor-lite');
    }

    public function get_categories () {
        return ['essential-addons-elementor'];
    }

    public function get_icon () {
        return 'eaicon-formstack';
    }

    public function get_keywords () {
        return [
            'forms',
            'ea',
            'ea formstack',
            'ea forms',
            'formstack',
            'contact form',
            'form styler',
            'elementor form',
            'feedback',
            'ea',
            'essential addons'
        ];
    }

    public function get_custom_help_url () {
        return 'https://essential-addons.com/elementor/docs/formstack/';
    }

    private function access_token () {
        return get_option('formstack_oauth2_code', '');
    }

    private function formstackAuth ($key) {
        return get_option('formstack_settings')[$key];
    }

    private function get_form_count () {
        return get_option('formstack_form_count', '');
    }

    private function no_app_setup () {
        $this->start_controls_section(
            'eael_global_warning',
            [
                'label' => __('Warning!', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_global_warning_text',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __('Please set your app client credentials on the <strong>Formstack</strong> settings page.',
                    'essential-addons-for-elementor-lite'),
                'content_classes' => 'eael-warning',
            ]
        );

        $this->end_controls_section();
    }

    private function no_forms_created () {
        $this->start_controls_section(
            'eael_global_warning',
            [
                'label' => __('Warning!', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_global_warning_text',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __('Please create form on the <strong>Formstack</strong> settings page.',
                    'essential-addons-for-elementor-lite'),
                'content_classes' => 'eael-warning',
            ]
        );

        $this->end_controls_section();
    }

    private function formstack_not_activated () {
        $this->start_controls_section(
            'eael_global_warning',
            [
                'label' => __('Warning!', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_global_warning_text',
            [
                'type'            => Controls_Manager::RAW_HTML,
                'raw'             => __('<strong>Formstack Online Forms</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=formstack&tab=search&type=term" target="_blank">Formstack Online Forms</a> first.',
                    'essential-addons-for-elementor-lite'),
                'content_classes' => 'eael-warning',
            ]
        );

        $this->end_controls_section();
    }


    protected function get_forms () {
        $forms = get_option('formstack_forms', '');

        $keys = ['' => __('-- Select One --', 'essential-addons-for-elementor-lite')];

        if (!empty($forms['forms'])) {
            foreach ($forms['forms'] as $form) {
                $keys[$form->url] = $form->name;
            }
        }

        return $keys;
    }

    protected function _register_controls () {

        if (!apply_filters('eael/is_plugin_active', 'formstack/plugin.php')) {
            $this->formstack_not_activated();
            return;
        }

        if (empty($this->formstackAuth('client_id')) || empty($this->formstackAuth('client_secret')) || empty($this->access_token())) {
            $this->no_app_setup();
            return;
        }

        if (empty($this->get_forms())) {
            $this->no_forms_created();
            return;
        }

        $this->start_controls_section(
            'section_form_info_box',
            [
                'label' => __('Formstack', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_form_key',
            [
                'label'       => __('Forms', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SELECT,
                'label_block' => true,
                'options'     => $this->get_forms(),
                'default'     => '',
                'description' => __('To sync latest created forms make sure you have <a href="'.add_query_arg(['clear_formstack_cache' => 'true'],
                        admin_url('admin.php?page=Formstack')).'">Refresh Formstack form cache</a>',
                    'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_formstack_custom_title_description',
            [
                'label'        => __('Custom Title & Description', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'eael_formstack_form_title_custom',
            [
                'label'       => esc_html__('Title', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => '',
                'condition'   => [
                    'eael_formstack_custom_title_description' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_form_description_custom',
            [
                'label'     => esc_html__('Description', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => '',
                'condition' => [
                    'eael_formstack_custom_title_description' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_labels_switch',
            [
                'label'        => __('Labels', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes'
            ]
        );

        $this->add_control(
            'eael_formstack_placeholder_switch',
            [
                'label'        => __('Placeholder', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Errors
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_formstack_section_errors',
            [
                'label' => __('Errors', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_formstack_error_messages',
            [
                'label'   => __('Error Messages', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'show',
                'options' => [
                    'show' => __('Show', 'essential-addons-for-elementor-lite'),
                    'hide' => __('Hide', 'essential-addons-for-elementor-lite'),
                ]
            ]
        );

        $this->add_control(
            'eael_formstack_validation_messages',
            [
                'label'   => __('Validation Errors', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'show',
                'options' => [
                    'show' => __('Show', 'essential-addons-for-elementor-lite'),
                    'hide' => __('Hide', 'essential-addons-for-elementor-lite'),
                ]
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*    Style Tab
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Form Container
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_formstack_section_container_style',
            [
                'label' => __('Form Container', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_formstack_form_background',
            [
                'label'     => esc_html__('Form Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack'         => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-formstack .fsForm' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_form_alignment',
            [
                'label'       => esc_html__('Form Alignment', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::CHOOSE,
                'label_block' => true,
                'options'     => [
                    'default' => [
                        'title' => __('Default', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-ban',
                    ],
                    'left'    => [
                        'title' => esc_html__('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'  => [
                        'title' => esc_html__('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'   => [
                        'title' => esc_html__('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'     => 'default',
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_form_max_width',
            [
                'label'      => esc_html__('Form Width', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 1500,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 80,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_form_margin',
            [
                'label'      => esc_html__('Form Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_form_padding',
            [
                'label'      => esc_html__('Form Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_form_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'separator'  => 'before',
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_formstack_form_border',
                'selector' => '{{WRAPPER}} .eael-formstack',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_formstack_form_box_shadow',
                'selector' => '{{WRAPPER}} .eael-formstack',
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Form Title & Description
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_formstack_section_form_title_style',
            [
                'label'     => __('Title & Description', 'essential-addons-for-elementor-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_formstack_custom_title_description' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_heading_alignment',
            [
                'label'     => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack-title'       => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .eael-formstack-description' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'eael_formstack_title_heading',
            [
                'label'     => __('Title', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'eael_formstack_form_title_text_color',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack-title' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_formstack_form_title_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-formstack-title',
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_form_title_margin',
            [
                'label'              => __('Margin', 'essential-addons-for-elementor-lite'),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => ['px', 'em', '%'],
                'allowed_dimensions' => 'vertical',
                'placeholder'        => [
                    'top'    => '',
                    'right'  => 'auto',
                    'bottom' => '',
                    'left'   => 'auto',
                ],
                'selectors'          => [
                    '{{WRAPPER}} .eael-formstack-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'eael_formstack_description_heading',
            [
                'label'     => __('Description', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'eael_formstack_form_description_text_color',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack-description' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_formstack_form_description_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .eael-formstack-description'
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_form_description_margin',
            [
                'label'              => __('Margin', 'essential-addons-for-elementor-lite'),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => ['px', 'em', '%'],
                'allowed_dimensions' => 'vertical',
                'placeholder'        => [
                    'top'    => '',
                    'right'  => 'auto',
                    'bottom' => '',
                    'left'   => 'auto',
                ],
                'selectors'          => [
                    '{{WRAPPER}} .eael-formstack-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Field Description
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_formstack_section_field_description_style',
            [
                'label'                 => __( 'Field Description', 'essential-addons-for-elementor-lite'),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_formstack_field_description_text_color',
            [
                'label'                 => __( 'Text Color', 'essential-addons-for-elementor-lite'),
                'type'                  => Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .eael-formstack .fsSupporting' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'eael_formstack_field_description_typography',
                'label'                 => __( 'Typography', 'essential-addons-for-elementor-lite'),
                'selector'              => '{{WRAPPER}} .eael-formstack .fsSupporting',
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_field_description_spacing',
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
                    '{{WRAPPER}} .eael-formstack .fsSupporting' => 'padding-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Section Break Style
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_formstack_section_break_style',
            [
                'label' => __('Section Heading Style', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_formstack_section_break_background_color',
            [
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_section_break_label',
            [
                'label' => __('Label', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING
            ]
        );

        $this->add_control(
            'eael_formstack_section_break_label_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionHeading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_formstack_section_break_label_typography',
                'label'     => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector'  => '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionHeading',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_section_break_label_padding',
            [
                'label'      => __('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionHeading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_section_break_label_margin',
            [
                'label'      => __('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionHeading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_section_break_description',
            [
                'label'     => __('Description', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'eael_formstack_section_break_description_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionText' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_formstack_section_break_description_typography',
                'label'     => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector'  => '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionText',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_section_break_description_padding',
            [
                'label'      => __('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionText' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_section_break_description_margin',
            [
                'label'      => __('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionText' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_section_break_alignment',
            [
                'label'        => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'      => 'center',
                'prefix_class' => 'eael-formstack-section-break-content-'
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Labels
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_formstack_section_label_style',
            [
                'label' => __('Labels', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_formstack_text_color_label',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsLabel' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_formstack_typography_label',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-formstack .fsLabel',
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Input & Textarea
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_formstack_section_fields_style',
            [
                'label' => __('Input & Textarea', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('eael_formstack_tabs_fields_style');

        $this->start_controls_tab(
            'eael_formstack_tab_fields_normal',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_formstack_field_bg_color',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .eael-formstack .fsRowBody textarea, {{WRAPPER}} .eael-formstack .fsRowBody select' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_field_text_color',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .eael-formstack .fsRowBody textarea, {{WRAPPER}} .eael-formstack .fsRowBody select' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'eael_formstack_field_border',
                'label'       => __('Border', 'essential-addons-for-elementor-lite'),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .eael-formstack .fsRowBody textarea, {{WRAPPER}} .eael-formstack .fsRowBody select',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'eael_formstack_field_radius',
            [
                'label'      => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .eael-formstack .fsRowBody textarea, {{WRAPPER}} .eael-formstack .fsRowBody select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_field_text_indent',
            [
                'label'      => __('Text Indent', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 60,
                        'step' => 1,
                    ],
                    '%'  => [
                        'min'  => 0,
                        'max'  => 30,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .eael-formstack .fsRowBody textarea, {{WRAPPER}} .eael-formstack .fsRowBody select' => 'text-indent: {{SIZE}}{{UNIT}}',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_input_width',
            [
                'label'      => __('Input Width', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .eael-formstack .fsRowBody select' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_input_height',
            [
                'label'      => __('Input Height', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 80,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .eael-formstack .fsRowBody select' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_textarea_width',
            [
                'label'      => __('Textarea Width', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsRowBody textarea' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_textarea_height',
            [
                'label'      => __('Textarea Height', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 400,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsRowBody textarea' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_field_padding',
            [
                'label'      => __('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .eael-formstack .fsRowBody textarea, {{WRAPPER}} .eael-formstack .fsRowBody select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_field_spacing',
            [
                'label'      => __('Spacing', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsFieldRow' => 'margin-bottom: {{SIZE}}{{UNIT}} !important',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_formstack_field_typography',
                'label'     => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector'  => '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .eael-formstack .fsRowBody textarea, {{WRAPPER}} .eael-formstack .fsRowBody select',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'eael_formstack_field_box_shadow',
                'selector'  => '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{WRAPPER}} .eael-formstack .fsRowBody textarea, {{WRAPPER}} .eael-formstack .fsRowBody select',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_formstack_tab_fields_focus',
            [
                'label' => __('Focus', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_formstack_field_bg_color_focus',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .eael-formstack .fsRowBody textarea:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'eael_formstack_focus_input_border',
                'label'       => __('Border', 'essential-addons-for-elementor-lite'),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .eael-formstack .fsRowBody textarea:focus',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'eael_formstack_focus_box_shadow',
                'selector'  => '{{WRAPPER}} .eael-formstack input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]):focus, {{WRAPPER}} .eael-formstack .fsRowBody textarea:focus',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Placeholder
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_formstack_section_placeholder_style',
            [
                'label'     => __('Placeholder', 'essential-addons-for-elementor-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_formstack_placeholder_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_text_color_placeholder',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsRowBody input::-webkit-input-placeholder, {{WRAPPER}} .eael-formstack .fsRowBody textarea::-webkit-input-placeholder' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Radio & Checkbox
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_formstack_section_radio_checkbox_style',
            [
                'label' => __('Radio & Checkbox', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_formstack_custom_radio_checkbox',
            [
                'label'        => __('Custom Styles', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_radio_checkbox_size',
            [
                'label'      => __('Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'default'    => [
                    'size' => '15',
                    'unit' => 'px',
                ],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 80,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack-custom-radio-checkbox .fsRowBody input[type="checkbox"]:before,{{WRAPPER}} .eael-formstack-custom-radio-checkbox .fsRowBody input[type="checkbox"], {{WRAPPER}} .eael-formstack-custom-radio-checkbox .fsRowBody input[type="radio"]:before,{{WRAPPER}} .eael-formstack-custom-radio-checkbox .fsRowBody input[type="radio"]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'eael_formstack_custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs('eael_formstack_tabs_radio_checkbox_style');

        $this->start_controls_tab(
            'eael_formstack_radio_checkbox_normal',
            [
                'label'     => __('Normal', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_formstack_custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_radio_checkbox_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack-custom-radio-checkbox .fsRowBody input[type=checkbox]:before, {{WRAPPER}} .eael-formstack-custom-radio-checkbox .fsRowBody input[type=radio]:before' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'eael_formstack_custom_radio_checkbox' => 'yes',
                ],
            ]
        );


        $this->add_control(
            'eael_formstack_checkbox_heading',
            [
                'label'     => __('Checkbox', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_formstack_custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_checkbox_border_radius',
            [
                'label'      => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .eael-formstack-custom-radio-checkbox input[type="checkbox"]:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_formstack_custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_radio_heading',
            [
                'label'     => __('Radio Buttons', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_formstack_custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_radio_border_radius',
            [
                'label'      => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack-custom-radio-checkbox input[type="radio"], {{WRAPPER}} .eael-formstack-custom-radio-checkbox input[type="radio"]:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_formstack_custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_formstack_radio_checkbox_checked',
            [
                'label'     => __('Checked', 'essential-addons-for-elementor-lite'),
                'condition' => [
                    'eael_formstack_custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_radio_checkbox_color_checked',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack-custom-radio-checkbox .fsRowBody input[type=checkbox]:checked:before' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .eael-formstack-custom-radio-checkbox .fsRowBody input[type=radio]:checked:before'    => 'border-color: {{VALUE}} !important',
                ],
                'condition' => [
                    'eael_formstack_custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Rating
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_formstack_section_rating_style',
            [
                'label' => __('Rating', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'eael_formstack_rating_size',
            [
                'label'      => __('Size', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsRatingFieldContainer .fsRatingPipButton .fsRatingShape .phx-Icon' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
                ]
            ]
        );

        $this->add_control(
            'eael_formstack_rating_bg_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1c2f3a',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsRatingShape .phx-Icon' => 'fill: {{VALUE}} !important;',
                ],
            ]
        );


        $this->add_responsive_control(
            'eael_formstack_rating_padding',
            [
                'label'      => __('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsRatingFieldContainer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_rating_margin',
            [
                'label'      => __('Margin Top', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsRatingFieldContainer' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();


        /**
         * Style Tab: Submit Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_formstack_section_submit_button_style',
            [
                'label' => __('Submit Button', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_submit_button_align',
            [
                'label'        => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'left'   => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'      => '',
                'prefix_class' => 'eael-formstack-form-button-',
                'condition'    => [
                    'eael_formstack_button_width_type' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_button_width_type',
            [
                'label'        => __('Width', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'custom',
                'options'      => [
                    'full-width' => __('Full Width', 'essential-addons-for-elementor-lite'),
                    'custom'     => __('Custom', 'essential-addons-for-elementor-lite'),
                ],
                'prefix_class' => 'eael-formstack-form-button-',
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_button_width',
            [
                'label'      => __('Width', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1200,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsSubmit .fsSubmitButton' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'  => [
                    'eael_formstack_button_width_type' => 'custom',
                ],
            ]
        );

        $this->start_controls_tabs('eael_formstack_tabs_button_style');

        $this->start_controls_tab(
            'eael_formstack_tab_button_normal',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_formstack_button_bg_color_normal',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#1c2f3a',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSubmit .fsSubmitButton' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_button_text_color_normal',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSubmit .fsSubmitButton' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'eael_formstack_button_border_normal',
                'label'       => __('Border', 'essential-addons-for-elementor-lite'),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .eael-formstack .fsSubmit .fsSubmitButton',
            ]
        );

        $this->add_control(
            'eael_formstack_button_border_radius',
            [
                'label'      => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsSubmit .fsSubmitButton' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_button_padding',
            [
                'label'      => __('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsSubmit .fsSubmitButton' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_button_margin',
            [
                'label'      => __('Margin Top', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsSubmit .fsSubmitButton' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_formstack_button_typography',
                'label'     => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector'  => '{{WRAPPER}} .eael-formstack .fsSubmit .fsSubmitButton',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'eael_formstack_button_box_shadow',
                'selector'  => '{{WRAPPER}} .eael-formstack .fsSubmit .fsSubmitButton',
                'separator' => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_formstack_tab_button_hover',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_formstack_button_bg_color_hover',
            [
                'label'     => __('Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSubmit .fsSubmitButton:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_button_text_color_hover',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSubmit .fsSubmitButton:hover' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_button_border_color_hover',
            [
                'label'     => __('Border Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSubmit .fsSubmitButton:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Pagination and progressbar
         * -------------------------------------------------
         */

        $this->start_controls_section(
            'eael_formstack_section_pagination_style',
            [
                'label' => __('Pagination', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('eael_formstack_form_progressbar_style_tabs');

        $this->start_controls_tab(
            'eael_formstack_form_progressbar_normal',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );



        $this->add_control(
            'eael_formstack_pagination_progressbar',
            [
                'label' => __('Progressbar', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'eael_formstack_show_progressbar',
            [
                'label'        => __('Show Progressbar', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'prefix_class' => 'eael-formstack-progressbar-'
            ]
        );

        $this->add_control(
            'eael_formstack_progressbar_color',
            [
                'label'     => __('Title Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'scheme'    => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsProgressBarContainer .fsProgressText' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_formstack_show_progressbar' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_formstack_progressbar_title_typography',
                'label'     => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector'  => '{{WRAPPER}} .eael-formstack .fsProgressBarContainer .fsProgressText',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_formstack_progressbar_height',
            [
                'label'      => __('Height', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ]
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsProgressBarContainer' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_formstack_show_progressbar' => 'yes'
                ]
            ]
        );



        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'eael_formstack_progressbar_border',
                'label'     => __('Border', 'essential-addons-for-elementor-lite'),
                'selector'  => '{{WRAPPER}} .eael-formstack .fsProgressBarContainer',
                'condition' => [
                    'eael_formstack_show_progressbar' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'eael_formstack_progressbar_border_radius',
            [
                'label'      => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsProgressBarContainer , {{WRAPPER}} .eael-formstack .fsProgressBarContainer .fsProgressBar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_formstack_show_progressbar' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'eael_formstack_progressbar_bg',
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .eael-formstack .fsProgressBarContainer',
                'condition' => [
                    'eael_formstack_show_progressbar' => 'yes'
                ],
                'exclude'   => [
                    'image'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_formstack_form_progressbar_filled',
            [
                'label' => __('Filled', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'eael_formstack_progressbar_bg_filled',
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .eael-formstack .fsProgressBarContainer .fsProgressBar',
                'condition' => [
                    'eael_formstack_show_progressbar' => 'yes'
                ],
                'exclude'   => [
                    'image'
                ]
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->start_controls_tabs(
            'eael_formstack_form_pagination_button_style_tabs',
            [
                'separator' => 'before'
            ]
        );

        $this->start_controls_tab(
            'eael_formstack_form_pagination_button',
            [
                'label' => __('Normal', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_formstack_pagination_button_style',
            [
                'label' => __('Button', 'essential-addons-for-elementor-lite'),
                'type'  => Controls_Manager::HEADING
            ]
        );

        $this->add_control(
            'eael_formstack_pagination_button_color',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsPagination button .fsFull' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_formstack_pagination_button_typography',
                'label'    => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .eael-formstack .fsPagination button .fsFull',
            ]
        );

        $this->add_control(
            'eael_formstack_pagination_button_bg',
            [
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsPagination button' => 'background-color: {{VALUE}} !important;',
                ]
            ]
        );


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_formstack_pagination_button_border',
                'label'    => __('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-formstack .fsPagination button',
            ]
        );

        $this->add_control(
            'eael_formstack_pagination_button_border_radius',
            [
                'label'      => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsPagination button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_pagination_button_padding',
            [
                'label'      => __('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsPagination button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'eael_formstack_form_pagination_button_hover',
            [
                'label' => __('Hover', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_formstack_pagination_button_hover_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsPagination button:hover .fsFull' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'eael_formstack_pagination_button_hover_bg',
            [
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsPagination button:hover' => 'background-color: {{VALUE}} !important;',
                ]
            ]
        );


        $this->add_control(
            'eael_formstack_pagination_button_border_hover_radius',
            [
                'label'      => __('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsPagination button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();


        /**
         * Style Tab: Errors
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'eael_formstack_section_error_style',
            [
                'label' => __('Errors', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_formstack_error_messages_heading',
            [
                'label'     => __('Error Messages', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_formstack_error_messages' => 'show',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_error_message_background_color',
            [
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsError' => 'background-color: {{VALUE}}',
                ],
                'condition' => [
                    'eael_formstack_error_messages' => 'show',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_error_message_text_color',
            [
                'label'     => __('Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsError' => 'color: {{VALUE}}; border: 1px solid {{VALUE}};',
                ],
                'condition' => [
                    'eael_formstack_error_messages' => 'show',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'eael_formstack_error_message_typography',
                'label'     => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector'  => '{{WRAPPER}} .eael-formstack .fsError',
                'condition' => [
                    'eael_formstack_error_messages' => 'show',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_error_message_padding',
            [
                'label'      => __('Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsError' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_formstack_error_messages' => 'show',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_error_message_margin',
            [
                'label'      => __('Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-formstack .fsError' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_formstack_error_messages' => 'show',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_validation_error_messages_heading',
            [
                'label'     => __('Validation Messages', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'eael_formstack_validation_messages' => 'show',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_validation_message_background_color',
            [
                'label'     => __('Background', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fae9e9',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsValidationError' => 'background-color: {{VALUE}}; border: 1px solid {{VALUE}};',
                ],
                'condition' => [
                    'eael_formstack_validation_messages' => 'show',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_validation_message_text_color',
            [
                'label'     => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ce5f6d',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsValidationError .fsLabel, {{WRAPPER}} .eael-formstack .fsValidationError .fsRequiredLabel, {{WRAPPER}} .eael-formstack .fsValidationError .fsRequiredMarker ' => 'color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'eael_formstack_validation_messages' => 'show',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'eael_formstack_validation_error_box_shadow',
                'selector'  => '{{WRAPPER}} .eael-formstack .fsValidationError',
                'condition' => [
                    'eael_formstack_validation_messages' => 'show',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render () {

        if (!apply_filters('eael/is_plugin_active', 'formstack/plugin.php') || empty($this->get_forms())) {
            return;
        }

        if (empty($this->formstackAuth('client_id')) || empty($this->formstackAuth('client_secret')) || empty($this->access_token())) {
            return;
        }


        $settings = $this->get_settings_for_display();
        $key = 'eael_formstack_'.md5($settings['eael_form_key']);
        $form_data = get_transient($key);
        if (empty($form_data) && $settings['eael_form_key']!='') {
            $wp = wp_remote_get(
                $settings['eael_form_key'],
                array(
                    'timeout' => 120,
                )
            );
            $form_data = wp_remote_retrieve_body($wp);
            set_transient($key, $form_data, 1 * HOUR_IN_SECONDS);
        }

        $this->add_render_attribute(
            'eael_formstack_wrapper',
            [
                'class' => [
                    'eael-formstack',
                    'clearfix',
                    'fs_wp_sidebar',
                    'fsBody',
                    'eael-contact-form'
                ]
            ]
        );

        if ($settings['eael_formstack_placeholder_switch'] != 'yes') {
            $this->add_render_attribute('eael_formstack_wrapper', 'class', 'placeholder-hide');
        }

        if ($settings['eael_formstack_labels_switch'] != 'yes') {
            $this->add_render_attribute('eael_formstack_wrapper', 'class', 'eael-formstack-form-labels-hide');
        }

        if ($settings['eael_formstack_error_messages'] == 'hide') {
            $this->add_render_attribute('eael_formstack_wrapper', 'class', 'eael-formstack-error-message-hide');
        }

        if ($settings['eael_formstack_validation_messages'] == 'hide') {
            $this->add_render_attribute('eael_formstack_wrapper', 'class', 'eael-formstack-validation-message-hide');
        }

        if ($settings['eael_formstack_custom_radio_checkbox'] == 'yes') {
            $this->add_render_attribute('eael_formstack_wrapper', 'class', 'eael-formstack-custom-radio-checkbox');
        }

        $alignment = $settings['eael_formstack_form_alignment'];
        $this->add_render_attribute('eael_formstack_wrapper', 'class', 'eael-formstack-form-align-'.$alignment);


        ?>
        <div <?php echo $this->get_render_attribute_string('eael_formstack_wrapper'); ?>>
            <?php if ($settings['eael_formstack_custom_title_description'] == 'yes') { ?>
                <div class="eael-formstack-heading">
                    <?php if ($settings['eael_formstack_form_title_custom'] != '') { ?>
                        <h3 class="eael-contact-form-title eael-formstack-title">
                            <?php echo esc_attr($settings['eael_formstack_form_title_custom']); ?>
                        </h3>
                    <?php } ?>
                    <?php if ($settings['eael_formstack_form_description_custom'] != '') { ?>
                        <div class="eael-contact-form-description eael-formstack-description">
                            <?php echo $this->parse_text_editor($settings['eael_formstack_form_description_custom']); ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="fsForm">
                <?php echo $form_data; ?>
            </div>
        </div>
        <?php

    }

}

<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Group_Control_Border as Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography as Group_Control_Typography;
use \Elementor\Scheme_Typography as Scheme_Typography;
use \Elementor\Widget_Base as Widget_Base;
use \Elementor\Group_Control_Background as Group_Control_Background;
use \Elementor\Scheme_Color;

class Formstack extends Widget_Base {

    // use \Essential_Addons_Elementor\Traits\Helper;

    public function get_name () {
        return 'eael-formstack';
    }

    public function get_title () {
        return __('EA Formstack', 'essential-addons-for-elementor-lite');
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

    private function formstackAuth (string $key) {
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

        if (!apply_filters('eael/active_plugins', 'formstack/plugin.php')) {
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
                'label' => __('Custom Title & Description', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off' => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'eael_formstack_form_title_custom',
            [
                'label' => esc_html__('Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => '',
                'condition' => [
                    'eael_formstack_custom_title_description' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_form_description_custom',
            [
                'label' => esc_html__('Description', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'condition' => [
                    'eael_formstack_custom_title_description' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_labels_switch',
            [
                'label' => __('Labels', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Show', 'essential-addons-for-elementor-lite'),
                'label_off' => __('Hide', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes'
            ]
        );

        $this->add_control(
            'eael_formstack_placeholder_switch',
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
            'eael_formstack_section_errors',
            [
                'label' => __('Errors', 'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'eael_formstack_error_messages',
            [
                'label' => __('Error Messages', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
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
                'label' => __('Validation Errors', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
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
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_formstack_form_background',
            [
                'label' => esc_html__('Form Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .eael-formstack .fsForm' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_form_alignment',
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
            'eael_formstack_form_max_width',
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
                    '{{WRAPPER}} .eael-formstack' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_form_margin',
            [
                'label' => esc_html__('Form Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_form_padding',
            [
                'label' => esc_html__('Form Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_form_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_formstack_form_border',
                'selector' => '{{WRAPPER}} .eael-formstack',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'eael_formstack_form_box_shadow',
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
                'label' => __('Title & Description', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_formstack_custom_title_description' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_heading_alignment',
            [
                'label' => __('Alignment', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'essential-addons-for-elementor-lite'),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack-title' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .eael-formstack-description' => 'text-align: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'eael_formstack_title_heading',
            [
                'label' => __('Title', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'eael_formstack_form_title_text_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack-title' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_formstack_form_title_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-formstack-title',
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_form_title_margin',
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
                    '{{WRAPPER}} .eael-formstack-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control(
            'eael_formstack_description_heading',
            [
                'label' => __('Description', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'eael_formstack_form_description_text_color',
            [
                'label' => __('Text Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack-description' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_formstack_form_description_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'scheme' => Scheme_Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} .eael-formstack-description'
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_form_description_margin',
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
                    '{{WRAPPER}} .eael-formstack-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
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
                'label' => __('Section Break Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_formstack_section_break_label',
            [
                'label' => __('Label', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING
            ]
        );

        $this->add_control(
            'eael_formstack_section_break_label_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionHeading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_formstack_section_break_label_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionHeading',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_section_break_label_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionHeading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_section_break_label_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionHeading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_formstack_section_break_description',
            [
                'label' => __('Description', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'eael_formstack_section_break_description_color',
            [
                'label' => __('Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionText p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'eael_formstack_section_break_description_typography',
                'label' => __('Typography', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionText p',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_section_break_description_padding',
            [
                'label' => __('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionText p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_section_break_description_margin',
            [
                'label' => __('Margin', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-formstack .fsSectionHeader .fsSectionText p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_formstack_section_break_alignment',
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
                'prefix_class' => 'eael-formstack-section-break-content-'
            ]
        );

        $this->end_controls_section();
    }

    protected function render () {

        if (!apply_filters('eael/active_plugins', 'formstack/plugin.php') || empty($this->get_forms())) {
            return;
        }

        if (empty($this->formstackAuth('client_id')) || empty($this->formstackAuth('client_secret')) || empty($this->access_token())) {
            return;
        }


        $settings = $this->get_settings_for_display();
        $key = 'eael_formstack_'.md5($settings['eael_form_key']);
        $form_data = get_transient($key);
        if(empty($form_data)){
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

        if ( $settings['eael_formstack_placeholder_switch'] != 'yes' ) {
            $this->add_render_attribute( 'eael_formstack_wrapper', 'class', 'placeholder-hide' );
        }

        if( $settings['eael_formstack_labels_switch'] != 'yes' ) {
            $this->add_render_attribute( 'eael_formstack_wrapper', 'class', 'eael-formstack-form-labels-hide' );
        }

        if( $settings['eael_formstack_error_messages'] == 'hide' ) {
            $this->add_render_attribute( 'eael_formstack_wrapper', 'class', 'eael-formstack-error-message-hide' );
        }

        if( $settings['eael_formstack_validation_messages'] == 'hide' ) {
            $this->add_render_attribute( 'eael_formstack_wrapper', 'class', 'eael-formstack-validation-message-hide' );
        }

        $alignment = $settings['eael_formstack_form_alignment'];
        $this->add_render_attribute( 'eael_formstack_wrapper', 'class', 'eael-formstack-form-align-'.$alignment );


        ?>
        <div <?php echo $this->get_render_attribute_string('eael_formstack_wrapper'); ?>>
            <?php if ( $settings['eael_formstack_custom_title_description'] == 'yes' ) { ?>
                <div class="eael-formstack-heading">
                    <?php if ( $settings['eael_formstack_form_title_custom'] != '' ) { ?>
                        <h3 class="eael-contact-form-title eael-formstack-title">
                            <?php echo esc_attr( $settings['eael_formstack_form_title_custom'] ); ?>
                        </h3>
                    <?php } ?>
                    <?php if ( $settings['eael_formstack_form_description_custom'] != '' ) { ?>
                        <div class="eael-contact-form-description eael-formstack-description">
                            <?php echo $this->parse_text_editor( $settings['eael_formstack_form_description_custom'] ); ?>
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

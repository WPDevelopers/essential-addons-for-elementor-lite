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
            'formstack'
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

        ?>
        <div <?php echo $this->get_render_attribute_string('eael_formstack_wrapper'); ?>>
            <div class="fsForm">
                <?php echo $form_data; ?>
            </div>
        </div>
        <?php

    }

}

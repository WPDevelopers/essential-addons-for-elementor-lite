<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager as Controls_Manager;
use \Elementor\Widget_Base as Widget_Base;

// use \Elementor\Group_Control_Border as Group_Control_Border;
// use \Elementor\Group_Control_Box_Shadow as Group_Control_Box_Shadow;
// use \Elementor\Group_Control_Typography as Group_Control_Typography;
// use \Elementor\Scheme_Typography as Scheme_Typography;
// use \Elementor\Group_Control_Background as Group_Control_Background;
// use \Elementor\Scheme_Color;

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

    protected function get_extra_params () {
        $settings = $this->get_settings_for_display();
        $url = [];

        if (isset($settings['nojquery']) && 'true' === $settings['nojquery']) {
            $url['nojquery'] = '1';
        }

        if (isset($settings['nojqueryui']) && 'true' === $settings['nojqueryui']) {
            $url['nojqueryui'] = '1';
        }

        if (isset($settings['nomodernizr']) && 'true' === $settings['nomodernizr']) {
            $url['nomodernizr'] = '1';
        }

        if (isset($settings['no_style']) && 'true' === $settings['no_style']) {
            $url['no_style'] = '1';
        }

        if (isset($settings['no_style_strict']) && 'true' === $settings['no_style_strict']) {
            $url['no_style_strict'] = '1';
        }

        return $url;
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
            'form_key',
            [
                'label'       => __('Forms', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_forms(),
                'default'     => '',
                'description' => __('To sync latest created forms make sure you have <a href="'.add_query_arg(['clear_formstack_cache' => 'true'],
                        admin_url('admin.php?page=Formstack')).'">Refresh Formstack form cache</a>',
                    'essential-addons-for-elementor-lite'),
            ]
        );

        $this->add_control(
            'aditional_options',
            [
                'label'     => __('Additional Options', 'plugin-name'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'nojquery',
            [
                'label'        => __('I do not need jQuery.', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('True', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('False', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'nojqueryui',
            [
                'label'        => __('I do not need jQuery UI.', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('True', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('False', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'nomodernizr',
            [
                'label'        => __('I do not need Modernizr.', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('True', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('False', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'no_style',
            [
                'label'        => __('Use Bare Bones CSS', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('True', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('False', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
            ]
        );

        $this->add_control(
            'no_style_strict',
            [
                'label'        => __('Use No CSS', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('True', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('False', 'essential-addons-for-elementor-lite'),
                'return_value' => 'true',
            ]
        );

        $this->end_controls_section();
    }

    protected function render () {
        $settings = $this->get_settings_for_display();
        list($form) = explode('-', $settings['form_key']);

        $extras = $this->get_extra_params();

        $wp = wp_remote_get($settings['form_key']);

        ?>
        <div class="eael-formstack fs_wp_sidebar fsBody">
            <div class="fsForm">
                <?php echo(wp_remote_retrieve_body($wp)); ?>
            </div>
        </div>
        <?php

    }

}

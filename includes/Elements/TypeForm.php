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

class TypeForm extends Widget_Base {

    private $form_list = [];

    public function __construct ($data = [], $args = null) {
        parent::__construct($data, $args);
    }

    public function get_name () {
        return 'eael-typeform';
    }

    public function get_title () {
        return __('TypeForm', 'essential-addons-for-elementor-lite');
    }

    public function get_categories () {
        return ['essential-addons-elementor'];
    }

    public function get_icon () {
        return 'eaicon-fluent-forms';
    }

    public function get_keywords () {
        return [
            'ea contact form',
            'ea typeform',
            'ea type form',
            'ea type forms',
            'contact form',
            'form styler',
            'elementor form',
            'feedback',
            'typeform',
            'ea',
            'essential addons'
        ];
    }

    public function get_custom_help_url () {
        return 'https://essential-addons.com/elementor/docs/type-form/';
    }

    private function get_personal_token () {
        return get_option('eael_save_typeform_personal_token');
    }

    public function get_form_list () {

        $token = $this->get_personal_token();
        $key = 'eael_type_form_data';
        $form_arr = get_transient($key);
        if (empty($form_arr)) {
            $response = wp_remote_get(
                'https://api.typeform.com/forms',
                [
                    'headers' => [
                        'Authorization' => "Bearer $token",
                    ]
                ]
            );

            if (isset($response['response']['code']) && $response['response']['code'] == 200) {
                $data = json_decode(wp_remote_retrieve_body($response));
                if (isset($data->items)) {
                    $form_arr = $data->items;
                    set_transient($key, $form_arr, 1 * HOUR_IN_SECONDS);
                }
            }
        }
        $this->form_list[''] = __('Select Form', 'essential-addons-for-elementor-lite');
        foreach ($form_arr as $item) {
            $this->form_list[$item->_links->display] = $item->title;
        }
        return $this->form_list;
    }

    private function no_token_set () {
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
                'raw'             => __('Whoops! It\' seems like you didn\'t set TypeForm personal token. You can set from 
                                    Essential Addons &gt; Elements &gt; TypeForm (Settings)',
                    'essential-addons-for-elementor-lite'),
                'content_classes' => 'eael-warning',
            ]
        );

        $this->end_controls_section();
    }

    protected function _register_controls () {

        if ($this->get_personal_token() == '') {
            $this->no_token_set();
            return;
        }

        $this->start_controls_section(
            'section_info_box',
            [
                'label' => __('TypeForm', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_typeform_list',
            [
                'label'   => __('Form', 'essential-addons-for-elementor-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_form_list()
            ]
        );
        $this->end_controls_section();
    }

    protected function render () {

        $settings = $this->get_settings_for_display();
        if ($settings['eael_typeform_list'] == '') {
            return;
        }
        $id = 'eael-type-form-'.$this->get_id();
        $this->add_render_attribute(
            'eael_typeform_wrapper',
            [
                'id'    => $id,
                'class' => [
                    'eael-typeform',
                    'clearfix',
                    'fs_wp_sidebar',
                    'fsBody',
                    'eael-contact-form'
                ]
            ]
        );
        $data = [
                'url' => esc_url($settings['eael_typeform_list'])
        ];
        echo '<div data-typeform="'.htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8').'" '.$this->get_render_attribute_string('eael_typeform_wrapper').'></div>';
    }

}

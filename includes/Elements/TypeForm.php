<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Widget_Base;
use \Elementor\Core\Schemes\Color;

class TypeForm extends Widget_Base {

    private $form_list = [];

    public function get_name () {
        return 'eael-typeform';
    }

    public function get_title () {
        return __('Typeform', 'essential-addons-for-elementor-lite');
    }

    public function get_categories () {
        return ['essential-addons-elementor'];
    }

    public function get_icon () {
        return 'eaicon-typeform';
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
        return 'https://essential-addons.com/elementor/docs/typeform/';
    }

    private function get_personal_token () {
        return get_option('eael_save_typeform_personal_token');
    }

    public function get_form_list () {

        $token = $this->get_personal_token();
        $key = 'eael_typeform_'.md5(implode('', ['eael_type_form_data', $token]));
        $form_arr = get_transient($key);
        if (empty($form_arr)) {
            $response = wp_remote_get(
                'https://api.typeform.com/forms?page_size=200',
                [
                    'headers' => [
                        'Authorization' => "Bearer $token",
                    ]
                ]
            );
            if (is_wp_error($response)) {
                return $this->form_list;
            }

            if (isset($response['response']['code']) && $response['response']['code'] == 200) {
                $data = json_decode(wp_remote_retrieve_body($response));
                if (isset($data->items)) {
                    $form_arr = $data->items;
                    set_transient($key, $form_arr, 1 * HOUR_IN_SECONDS);
                }
            }
        }
        $this->form_list[''] = __('Select Form', 'essential-addons-for-elementor-lite');
        if (!empty($form_arr)) {
            foreach ($form_arr as $item) {
                $this->form_list[$item->_links->display] = $item->title;
            }
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
                'raw'             => __('Whoops! It seems like you haven\'t connected your Typeform account. To do this, navigate to <b>WordPress Dashboard -> Essential Addons -> Elements -> Typeform</b> (<a target="_blank" href="'.esc_url(admin_url( 'admin.php?page=eael-settings')).'">Get Access</a>).',
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
                'label' => __('Typeform', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_typeform_list',
            [
                'label'       => __('Typeform', 'essential-addons-for-elementor-lite'),
                'type'        => Controls_Manager::SELECT,
                'default'     => '',
                'label_block' => true,
                'options'     => $this->get_form_list()
            ]
        );
        $this->add_control(
            'eael_typeform_hideheaders',
            [
                'label'        => __('Hide Header', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'return_value' => 'yes',
            ]
        );
        $this->add_control(
            'eael_typeform_hidefooter',
            [
                'label'        => __('Hide Footer', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'return_value' => 'yes',
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
            'section_container_style',
            [
                'label' => __('Form Container', 'essential-addons-for-elementor-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_typeform_background',
            [
                'label'     => esc_html__('Form Background Color', 'essential-addons-for-elementor-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-typeform' => 'background: {{VALUE}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'eael_typeform_alignment',
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
            'eael_typeform_max_width',
            [
                'label'      => esc_html__('Form Max Width', 'essential-addons-for-elementor-lite'),
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
                    '{{WRAPPER}} .eael-typeform' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_typeform_max_height',
            [
                'label'      => esc_html__('Form Height', 'essential-addons-for-elementor-lite'),
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
                'default'    => [
                    'size' => '700',
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-typeform' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'eael_typeform_opacity',
            [
                'label'      => __('Opacity', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 50,
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_typeform_margin',
            [
                'label'      => esc_html__('Form Margin', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-typeform' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_typeform_padding',
            [
                'label'      => esc_html__('Form Padding', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-typeform' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_type_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'separator'  => 'before',
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-typeform' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'eael_type_border',
                'selector' => '{{WRAPPER}} .eael-typeform',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'eael_typeform_box_shadow',
                'selector' => '{{WRAPPER}} .eael-typeform',
            ]
        );

        $this->end_controls_section();


    }

    protected function render () {

        $settings = $this->get_settings_for_display();
        if ($this->get_settings('eael_typeform_list') == '') {
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
        $alignment = $settings['eael_typeform_alignment'];
        $this->add_render_attribute('eael_typeform_wrapper', 'class', 'eael-typeform-align-'.$alignment);
        $data = [
            'url'         => esc_url($settings['eael_typeform_list']),
            'hideFooter'  => ($this->get_settings('eael_typeform_hidefooter') == 'yes'),
            'hideHeaders' => ($this->get_settings('eael_typeform_hideheaders') == 'yes'),
            'opacity'     => $this->get_settings('eael_typeform_opacity')['size']
        ];
        echo '<div data-typeform="'.htmlspecialchars(json_encode($data), ENT_QUOTES,
                'UTF-8').'" '.$this->get_render_attribute_string('eael_typeform_wrapper').'></div>';
    }

}

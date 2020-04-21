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

    use \Essential_Addons_Elementor\Traits\Helper;

    public function __construct ($data = [], $args = null) {
        parent::__construct($data, $args);
        $is_type_instance = $this->is_type_instance();

        if (!$is_type_instance && null === $args) {
            throw new \Exception('`$args` argument is required when initializing a full widget instance.');
        }

        if ($is_type_instance) {
            echo '<script src="https://embed.typeform.com/embed.js" type="text/javascript"></script>';
        }

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

    private function no_token_set(){
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
            return ;
        }

        $this->start_controls_section(
            'section_info_box',
            [
                'label' => __('TypeForm', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'type_form_color',
            [
                'label'        => __('Custom Title & Description', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
            ]
        );
        $this->end_controls_section();
    }

    protected function render () {

        $settings = $this->get_settings_for_display();
        ?>
        <div id="my-embedded-typeform"
             style="width: 100%; height: 300px;"></div>

        <script type="text/javascript">
			jQuery(document).ready(function () {
				var el = document.getElementById("my-embedded-typeform");
				typeformEmbed.makeWidget(el, "https://admin.typeform.com/to/cVa5IG", {
					hideFooter: true,
					hideHeaders: true,
					opacity: 0
				});
			});
        </script>
        <?php
    }

}

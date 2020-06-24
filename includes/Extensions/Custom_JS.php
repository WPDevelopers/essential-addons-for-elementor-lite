<?php
namespace Essential_Addons_Elementor\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;

class Custom_JS
{
    public function __construct()
    {
        add_action('elementor/documents/register_controls', [$this, 'section_custom_js'], 20);
    }

    public function section_custom_js($controls)
    {
        $controls->start_controls_section(
            'eael_ext_section_custom_js',
            [
                'label' => sprintf('<i class="eaicon-logo"></i> %s', __('Custom JS', 'essential-addons-for-elementor-lite')),
                'tab'   => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $controls->add_control(
            'eael_custom_js',
            [
                'type'     => Controls_Manager::CODE,
                'label'    => __('Add your own custom JS here', 'essential-addons-for-elementor-lite'),
                'language' => 'javascript',
            ]
        );

        $controls->end_controls_section();
    }
}

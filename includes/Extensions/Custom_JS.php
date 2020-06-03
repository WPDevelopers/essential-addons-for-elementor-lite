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

        $controls->add_control(
            'eael_custom_js_print_method',
            [
                'type'        => Controls_Manager::SELECT,
                'label'       => __('Print Method', 'essential-addons-for-elementor-lite'),
                'description' => 'Use external JS files for all generated scripts. Choose this setting for better performance (recommended).<br>Use internal JS that is embedded in the footer of the page for troubleshooting server configuration conflicts and managing development environments.',
                'default'     => 'internal',
                'options'     => [
                    'internal' => __('Internal Embedding', 'essential-addons-for-elementor-lite'),
                    'external' => __('External File', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $controls->end_controls_section();
    }
}

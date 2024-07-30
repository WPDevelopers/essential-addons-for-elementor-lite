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
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        $controls->add_control(
            'eael_custom_js_label',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('Add your own custom JS here', 'essential-addons-for-elementor-lite'),
            ]
        );

        $controls->add_control(
            'eael_custom_js',
            [
                'type' => Controls_Manager::CODE,
                'show_label' => false,
                'language' => 'javascript',
            ]
        );

	    if ( ! current_user_can( 'administrator' ) ) {
		    $controls->add_control(
			    'eael_custom_js_global_warning_text',
			    [
				    'type'            => Controls_Manager::RAW_HTML,
				    'raw'             => __( '<strong>Note:</strong> Only the Administrator can add/edit JavaScript code from here', 'essential-addons-for-elementor-lite' ),
				    'content_classes' => 'eael-warning',
			    ]
		    );
	    }

        $controls->add_control(
            'eael_custom_js_usage',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('You may use both jQuery selector e.g. $(‘.selector’) or Vanilla JS selector e.g. document.queryselector(‘.selector’)', 'essential-addons-for-elementor-lite'),
                'content_classes' => 'elementor-descriptor',
            ]
        );
        
        $controls->add_control(
            'eael_custom_js_docs',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('For more information, <a href="https://essential-addons.com/elementor/docs/custom-js/" target="_blank">click here</a>', 'essential-addons-for-elementor-lite'),
                'content_classes' => 'elementor-descriptor',
            ]
        );

        $controls->end_controls_section();
    }
}

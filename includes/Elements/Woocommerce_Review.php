<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;
class Woocommerce_Review extends Widget_Base {

	public function get_name() {
		return 'eael-woocommerce-review';
	}

	public function get_title() {
		return esc_html__( 'ReviewX Woo Review', 'essential-addons-for-elementor-lite');
	}

	public function get_icon() {
		return 'eaicon-reviewx';
	}

   	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
    
    public function get_keywords() {
        return [
            'reviewx',
            'woo review',
            'woo',
            'woocommerce',
            'comment',
            'review',
            'addons',
            'ea',
            'essential addons',
            'woocommerce review'
		];
    }

    public function get_custom_help_url() {
        return 'https://reviewx.io/docs';
    }

	protected function register_controls() {
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
                'raw'             => __('<strong>ReviewX</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=reviewx&tab=search&type=term" target="_blank">ReviewX</a> first.',
                    'essential-addons-for-elementor-lite'),
                'content_classes' => 'eael-warning',
            ]
        );

        $this->end_controls_section();
	}


	protected function render() {
	    return;
	}
}

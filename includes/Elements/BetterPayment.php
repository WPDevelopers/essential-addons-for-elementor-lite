<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;

class BetterPayment extends Widget_Base {

	public function get_name() {
		return 'eael-better-payment';
	}

	public function get_title() {
		return esc_html__( 'Better Payment', 'essential-addons-for-elementor-lite');
	}

	public function get_icon() {
		return 'eaicon-better-payment';
	}

   	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
    
    public function get_keywords() {
        return [
            'payment', 'better-payment' ,'paypal', 'stripe', 'sell', 'donate', 'transaction', 'online-transaction', 'better payment', 'online transaction', 'ea better payment', 'ea', 'essential addons'
		];
    }

    public function get_custom_help_url() {
        return '#';
    }

	protected function _register_controls() {
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
                'raw'             => __('<strong>Better Payment</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=better-payment&tab=search&type=tag" target="_blank">Better Payment</a> first.',
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

<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;

class Crowdfundly_All_Campaign extends Widget_Base {

	public function get_name() {
		return 'crowdfundly-all-campaign';
	}

	public function get_title() {
		return __( 'Crowdfundly All Campaign', 'crowdfundly' );
	}

	public function get_icon() {
		return 'eaicon-crowdfundly-campaign';
	}

	public function get_keywords() {
		return [ 'crowdfundly', 'fund', 'donation', 'campaign', 'ea', 'ea-crowdfundly' ];
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
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
                'raw'             => __('<strong>Crowdfundly</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=crowdfundly&tab=search&type=term" target="_blank">Crowdfundly</a> first.',
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

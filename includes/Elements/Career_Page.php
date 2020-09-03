<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;
class Career_Page extends Widget_Base {

	public function get_name() {
		return 'eael-career-page';
	}

	public function get_title() {
		return esc_html__( 'EasyJobs Career Page', 'essential-addons-for-elementor-lite');
	}

	public function get_icon() {
		return 'eaicon-easyjobs';
	}

   	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
    
    public function get_keywords() {
        return [
            'easyjobs',
            'addons',
            'ea',
            'career',
            'job',
            'career page',
            'essential addons',
		];
    }

    public function get_custom_help_url() {
        return 'https://easy.jobs/docs/';
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
                'raw'             => __('<strong>EasyJobs</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=easyjobs&tab=search&type=term" target="_blank">EasyJobs</a> first.',
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
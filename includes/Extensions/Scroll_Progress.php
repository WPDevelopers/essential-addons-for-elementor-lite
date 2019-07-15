<?php
namespace Essential_Addons_Elementor\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Scheme_Typography;

class Scroll_Progress
{
    use \Essential_Addons_Elementor\Traits\Helper;
    use \Essential_Addons_Elementor\Pro\Traits\Helper;

    public function __construct()
    {
        add_action('elementor/element/post/document_settings/after_section_end', [$this, 'register_controls'], 10);
    }

    public function register_controls($element)
    {
        $element->start_controls_section(
            'eael_ext_scroll_progress_section',
            [
                'label' => esc_html__('EA Scroll Progress', 'essential-addons-elementor'),
                'tab' => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $element->add_control(
            'eael_ext_scroll_progress',
            [
                'label' => __('Enable Scroll Progress', 'essential-addons-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'essential-addons-elementor'),
                'label_off' => __('No', 'essential-addons-elementor'),
                'return_value' => 'yes',
            ]
        );
    
        $element->end_controls_section();
    }

    

}

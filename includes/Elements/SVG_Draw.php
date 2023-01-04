<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use \Elementor\Plugin;
use Elementor\Repeater;
use \Elementor\Utils;
use \Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

class SVG_Draw Extends Widget_Base
{
    public function get_name()
    {
        return 'eael-svg-draw';
    }

    public function get_title()
    {
        return esc_html__('SVG Draw', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eaicon-advanced-tabs';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_keywords()
    {
        return [
            'svg',
            'draq',
            'ea svg',
            'ea svg draw',
            'animation',
            'icon',
            'icon animation',
            'ea',
            'essential addons',
        ];
    }

    public function get_custom_help_url()
    {
        return 'https://essential-addons.com/elementor/docs/advanced-tabs/';
    }

    protected function register_controls()
    {

    }

    protected function render()
    {
        echo "Hello SVG";
    }
}
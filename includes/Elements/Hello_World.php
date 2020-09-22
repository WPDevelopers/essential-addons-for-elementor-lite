<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Widget_Base;

/**
 * Gravity Forms Widget
 */
class Hello_World extends Widget_Base
{
    public function get_name()
    {
        return 'eael-hello-world';
    }

    public function get_title()
    {
        return __('Hello World', 'essential-addons-for-elementor-lite');
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    public function get_icon()
    {
        return 'eaicon-gravity-form';
    }

    public function render()
    {
        echo 'Hello World!';
    }
}

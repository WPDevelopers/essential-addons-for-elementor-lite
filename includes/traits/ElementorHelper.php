<?php
namespace EssentialAddonsElementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use EssentialAddonsElementor\Classes\Group_Control_EA_Posts as Group_Control_EA_Posts;

trait ElementorHelper
{
    public function add_elementor_widget_categories($elements_manager)
    {
        $elements_manager->add_category(
            'essential-addons-elementor',
            [
                'title' => __('Essential Addons', 'essential-addons-elementor'),
                'icon' => 'font',
            ],
            1
        );
    }

    public function controls_registered($controls_manager)
    {
        $controls_manager->add_group_control('eaeposts', new Group_Control_EA_Posts);
    }
}

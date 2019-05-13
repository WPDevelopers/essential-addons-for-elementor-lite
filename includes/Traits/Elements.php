<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

use Essential_Addons_Elementor\Classes\Group_Control_EA_Posts as Group_Control_EA_Posts;

trait Elements
{
    /**
     * Add Category
     *
     * @since v1.0.0
     */
    protected function register_widget_categories($elements_manager)
    {
        $elements_manager->add_category(
            'essential-addons-elementor',
            [
                'title' => __('Essential Addons', 'essential-addons-elementor'),
                'icon' => 'font',
            ], 1);
    }

    /**
     * Add new group control
     *
     * @since v1.0.0
     */
    protected function register_controls_group($controls_manager)
    {
        $controls_manager->add_group_control('eaeposts', new Group_Control_EA_Posts);
    }

    /**
     * Acivate or Deactivate Modules
     *
     * @since v1.0.0
     */
    protected function register_elements($widgets_manager)
    {
        $active_elements = $this->get_settings();

        if (empty($active_elements)) {
            return;
        }

        foreach ($active_elements as $active_element) {
            if (!isset($this->registered_elements[$active_element])) {
                continue;
            }

            if (isset($this->registered_elements[$active_element]['condition'])) {
                if ($this->registered_elements[$active_element]['condition'][0]($this->registered_elements[$active_element]['condition'][1]) == false) {
                    continue;
                }
            }

            $widgets_manager->register_widget_type(new $this->registered_elements[$active_element]['class']);
        }
    }

    protected function register_extensions()
    {
        $active_elements = $this->get_settings();

        if (empty($active_elements)) {
            return;
        }

        foreach ($this->registered_extensions as $key => $extension) {
            if (!in_array($key, $active_elements)) {
                continue;
            }

            new $extension['class'];
        }
    }

}

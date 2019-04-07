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
    public function add_elementor_widget_categories($elements_manager)
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
    public function controls_registered($controls_manager)
    {
        $controls_manager->add_group_control('eaeposts', new Group_Control_EA_Posts);
    }

    /**
     * Acivate or Deactivate Modules
     *
     * @since v1.0.0
     */
    public function eael_add_elements($widgets_manager)
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

            $element_class = '\Essential_Addons_Elementor\Elements\\' . $this->registered_elements[$active_element]['class'];
            $widgets_manager->register_widget_type(new $element_class);
        }
    }

}

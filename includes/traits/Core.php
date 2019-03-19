<?php

namespace EssentialAddonsElementor\Traits;

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly

trait Core
{
    /**
     *  Return array of registered elements.
     * 
     * @todo filter output
     */
    public function get_registered_elements()
    {
        return $this->registered_elements;
    }

    /** 
     * Return saved settings
     * 
     * @since 3.0.0
     */
    public function get_settings($element = null)
    {
        $elements = get_option('eael_save_settings', array_fill_keys($this->registered_elements, true));

        return (isset($element) ? $elements[$element] : $elements);
    }

    
}

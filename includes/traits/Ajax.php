<?php

namespace EssentialAddonsElementor\Traits;

if (!defined('ABSPATH')) {
    exit();
}
// Exit if accessed directly

trait Ajax
{
    /**
     * Saving data with ajax request
     * @param
     * @return  array
     * @since 1.1.2
     */
    public function save_settings()
    {
        check_ajax_referer('essential-addons-elementor', 'security');

        if (!isset($_POST['fields'])) {
            return;
        }

        parse_str($_POST['fields'], $settings);

        // update new settings
        $updated = update_option('eael_save_settings', array_merge(array_fill_keys($this->get_registered_elements(), 0), array_map(function ($value) {return 1;}, $settings)));

        // Build assets files
        do_action('eael_generate_editor_scripts', array_keys($settings));

        wp_send_json($updated);
    }
}

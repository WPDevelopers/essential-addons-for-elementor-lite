<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Library
{
    /**
     *  Return array of registered elements.
     *
     * @todo filter output
     */
    public function get_registered_elements()
    {
        return array_keys($this->registered_elements);
    }

    /**
     * Return saved settings
     *
     * @since 3.0.0
     */
    public function get_settings($element = null)
    {
        $elements = get_option('eael_save_settings', array_fill_keys(array_keys($this->registered_elements), true));

        return (isset($element) ? (isset($elements[$element]) ? $elements[$element] : 0) : array_keys(array_filter($elements)));
    }

    /**
     * Remove files
     *
     * @since 3.0.0
     */
    public function remove_files($post_type = null, $post_id = null)
    {
        $css_path = $this->safe_path(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($post_type ? 'eael-' . $post_type : 'eael') . ($post_id ? '-' . $post_id : '') . '.min.css');
        $js_path = $this->safe_path(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($post_type ? 'eael-' . $post_type : 'eael') . ($post_id ? '-' . $post_id : '') . '.min.js');

        if (file_exists($css_path)) {
            unlink($css_path);
        }

        if (file_exists($js_path)) {
            unlink($js_path);
        }
    }

    /**
     * Remove files in dir
     *
     * @since 3.0.0
     */
    public function empty_dir($path)
    {
        if (!is_dir($path) || !file_exists($path)) {
            return;
        }

        foreach (scandir($path) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            unlink($this->safe_path($path . DIRECTORY_SEPARATOR . $item));
        }
    }

    /**
     * Clear cache files
     *
     * @since 3.0.0
     */
    public function clear_cache_files()
    {
        check_ajax_referer('essential-addons-elementor', 'security');

        // clear cache files
        $this->empty_dir(EAEL_ASSET_PATH);

        wp_send_json(true);
    }

    /**
     * Check if elementor preview mode or not
     *
     * @since 3.0.0
     */
    public function is_preview_mode()
    {
        if (isset($_REQUEST['doing_wp_cron'])) {
            return true;
        }
        if (wp_doing_ajax()) {
            return true;
        }
        if (isset($_GET['elementor-preview'])) {
            return true;
        }
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'elementor') {
            return true;
        }

        return false;
    }

    /**
     * Check if a plugin is installed
     *
     * @since v3.0.0
     */
    public function is_plugin_installed($basename)
    {
        if (!function_exists('get_plugins')) {
            include_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        $installed_plugins = get_plugins();

        return isset($installed_plugins[$basename]);
    }

    /**
     * Generate safe path
     *
     * @since v3.0.0
     */
    public function safe_path($path)
    {
        $path = str_replace(['//', '\\\\'], ['/', '\\'], $path);

        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }
}

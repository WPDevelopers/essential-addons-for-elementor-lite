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
        $defaults = array_fill_keys(array_keys(array_merge($this->registered_elements, $this->registered_extensions)), true);
        $elements = get_option('eael_save_settings', $defaults);
        $elements = array_merge($defaults, $elements);

        return (isset($element) ? (isset($elements[$element]) ? $elements[$element] : 0) : array_keys(array_filter($elements)));
    }

    /**
     * Remove files
     *
     * @since 3.0.0
     */
    public function remove_files($uid = null, $ext = ['css', 'js'])
    {
        foreach ($ext as $e) {
            $path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($uid ? $uid : 'eael') . '.min.' . $e;

            if (file_exists($path)) {
                unlink($path);
            }
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

        if (isset($_REQUEST['posts'])) {
            if (!empty($_POST['posts'])) {
                foreach (json_decode($_POST['posts']) as $post) {
                    $this->remove_files('post-' . $post);
                }
            }
        } else {
            // clear cache files
            $this->empty_dir(EAEL_ASSET_PATH);
        }

        wp_send_json(true);
    }

    /**
     * Check if wp running in background
     *
     * @since 3.0.0
     */
    public function is_running_background()
    {
        if (isset($_REQUEST['doing_wp_cron'])) {
            return true;
        }

        if (wp_doing_ajax()) {
            return true;
        }

        return false;
    }

    /**
     * Check if elementor edit mode or not
     *
     * @since 3.0.0
     */
    public function is_edit_mode()
    {
        if (isset($_REQUEST['elementor-preview']) && isset($_REQUEST['ver'])) {
            return true;
        }

        return false;
    }

    /**
     * Check if elementor edit mode or not
     *
     * @since 3.0.0
     */
    public function is_preview_mode()
    {
        if (isset($_REQUEST['elementor-preview'])) {
            return false;
        }

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'elementor') {
            return false;
        }

        if (isset($_REQUEST['ver'])) {
            return false;
        }

        return true;
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

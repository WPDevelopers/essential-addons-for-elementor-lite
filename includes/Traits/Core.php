<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\User;
use \Essential_Addons_Elementor\Classes\Plugin_Usage_Tracker;

trait Core
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
     *  Check if pro version enabled.
     *
     * @since 3.0.0
     */
    public static function pro_enabled()
    {
        return self::$instance->pro_enabled;
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
     * Check if elementor preview mode or not
     *
     * @since 3.0.0
     */
    public function is_preview_mode()
    {
        if (!User::is_current_user_can_edit()) {
            return false;
        }

        if (!isset($_GET['elementor-preview'])) {
            return false;
        }

        return true;
    }

    /**
     * Generate safe url
     *
     * @since v3.0.0
     */
    public function safe_protocol($url)
    {
        return preg_replace(['/^http:/', '/^https:/', '/(?!^)\/\//'], ['', '', '/'], $url);
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

    /**
     * Check if a plugin is installed
     *
     * @since v3.0.0
     */
    public function plugin_installed($basename)
    {
        if (!function_exists('get_plugins')) {
            include_once ABSPATH . '/wp-admin/includes/plugin.php';
        }

        $installed_plugins = get_plugins();

        return isset($installed_plugins[$basename]);
    }

    /**
     * Creates an action menu
     *
     * @since 3.0.0
     */
    public function insert_plugin_links($links)
    {
        if ($this->pro_enabled) {
            return array();
        }

        // insert settings link
        $links[] = sprintf('<a href="admin.php?page=eael-settings">' . __('Settings') . '</a>');

        // insert go pro link
        if (!$this->plugin_installed('essential-addons-elementor/essential_adons_elementor.php')) {
            $links[] = sprintf('<a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank" style="color: #39b54a; font-weight: bold;">' . __('Go Pro') . '</a>');
        }

        return $links;
    }

    /**
     * Redirect to options page
     *
     * @since v1.0.0
     */
    public function redirect_on_activation()
    {
        if (get_option('eael_do_activation_redirect', false)) {
            delete_option('eael_do_activation_redirect');

            if (!isset($_GET['activate-multi'])) {
                wp_redirect("admin.php?page=eael-settings");
            }
        }
    }

    /**
     * Check if elementor plugin is activated
     *
     * @since v1.0.0
     */
    public function elementor_not_loaded()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        $elementor = 'elementor/elementor.php';

        if ($this->plugin_installed($elementor)) {
            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor);
            $message = __('<strong>Essential Addons for Elementor</strong> requires <strong>Elementor</strong> plugin to be active. Please activate Elementor to continue.', 'essential-addons-elementor');
            $button_text = __('Activate Elementor', 'essential-addons-elementor');
        } else {
            $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
            $message = sprintf(__('<strong>Essential Addons for Elementor</strong> requires <strong>Elementor</strong> plugin to be installed and activated. Please install Elementor to continue.', 'essential-addons-elementor'), '<strong>', '</strong>');
            $button_text = __('Install Elementor', 'essential-addons-elementor');
        }

        $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';

        printf('<div class="error"><p>%1$s</p>%2$s</div>', __($message), $button);
    }

    /**
     * Optional usage tracker
     *
     * @since v1.0.0
     */
    public function start_plugin_tracking()
    {
        new Plugin_Usage_Tracker(
            EAEL_PLUGIN_FILE,
            'http://app.wpdeveloper.net',
            array(),
            true,
            true,
            1
        );
    }
}

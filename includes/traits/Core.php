<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Essential_Addons_Elementor\Classes\Plugin_Usage_Tracker as Plugin_Usage_Tracker;

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
     * Return saved settings
     *
     * @since 3.0.0
     */
    public function get_settings($element = null)
    {
        $elements = get_option('eael_save_settings', array_fill_keys(array_keys($this->registered_elements), true));

        return (isset($element) ? $elements[$element] : array_keys(array_filter($elements)));
    }

    /**
     * Remove files
     *
     * @since 3.0.0
     */
    public function remove_files($post_id = null)
    {
        $css_path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($post_id ? 'eael-' . $post_id : 'eael') . '.min.css';
        $js_path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($post_id ? 'eael-' . $post_id : 'eael') . '.min.js';

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

            unlink($path . DIRECTORY_SEPARATOR . $item);
        }
    }

    /**
     * Plugin activation hook
     */
    public function plugin_activation_hook()
    {
        // remove old cache files
        $this->empty_dir(EAEL_ASSET_PATH);

        // Redirect to options page
        update_option('eael_do_activation_redirect', true);
    }

    /**
     * Plugin deactivation hook
     */
    public function plugin_deactivation_hook()
    {
        $this->empty_dir(EAEL_ASSET_PATH);
    }

    /**
     * Plugin activation hook
     */
    public function plugin_upgrade_hook()
    {
        $this->empty_dir(EAEL_ASSET_PATH);
    }

    /**
     * Creates an Action Menu
     */
    public function eael_add_settings_link($links)
    {
        $settings_link = sprintf('<a href="admin.php?page=eael-settings">' . __('Settings') . '</a>');
        $go_pro_link = sprintf('<a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank" style="color: #39b54a; font-weight: bold;">' . __('Go Pro') . '</a>');
        array_push($links, $settings_link, $go_pro_link);
        return $links;
    }

    /**
     * Redirect to options page
     *
     * @since v1.0.0
     */
    public function eael_redirect()
    {
        if (get_option('eael_do_activation_redirect', false)) {
            delete_option('eael_do_activation_redirect');
            if (!isset($_GET['activate-multi'])) {
                wp_redirect("admin.php?page=eael-settings");
            }
        }
    }

    public function plugins_footer_for_pro()
    {
        ?>
        <script>
        jQuery(document).ready(function( $ ){
            $('#eae-pro-activation').on('click', function( e ){
                e.preventDefault();
                swal({
                    title: '<h2>Please <span style="color: red">Deactivate</span> <br><br> Free Version</h2>',
                    type: 'error',
                    html:
                    'You don\'t need the <span style="color: #1abc9c;font-weight: 700;">Free Version</span> to use the <span style="color: #00629a;font-weight: 700;">Premium</span> one.',
                    showCloseButton: true,
                    showCancelButton: false,
                    focusConfirm: false,
                }).catch(swal.noop);
            });
        });
        </script>
        <?php
}

    public function eael_pro_filter_action_links($links)
    {
        if (!function_exists('get_plugins')) {
            include ABSPATH . '/wp-admin/includes/plugin.php';
        }
        $activate_plugins = get_option('active_plugins');
        if (in_array(plugin_basename(__FILE__), $activate_plugins)) {
            $pro_plugin_base_name = 'essential-addons-elementor/essential_adons_elementor.php';
            if (isset($links['activate'])) {
                $activate_link = $links['activate'];
                // Insert an onClick action to allow form before deactivating
                $activation_link = str_replace('<a ', '<a id="eae-pro-activation" onclick="javascript:event.preventDefault();"', $activate_link);
                $links['activate'] = $activation_link;
            }
        }
        return $links;
    }

    public function eael_is_elementor_active()
    {
        $file_path = 'elementor/elementor.php';
        if (!function_exists('get_plugins')) {
            include ABSPATH . '/wp-admin/includes/plugin.php';
        }
        $installed_plugins = get_plugins();
        return isset($installed_plugins[$file_path]);
    }

    /**
     * This notice will appear if Elementor is not installed or activated or both
     */
    public function eael_is_failed_to_load()
    {
        $elementor = 'elementor/elementor.php';
        if ($this->eael_is_elementor_active()) {
            if (!current_user_can('activate_plugins')) {
                return;
            }
            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor);
            $message = __('<strong>Essential Addons for Elementor</strong> requires <strong>Elementor</strong> plugin to be active. Please activate Elementor to continue.', 'essential-addons-elementor');
            $button_text = __('Activate Elementor', 'essential-addons-elementor');
        } else {
            if (!current_user_can('activate_plugins')) {
                return;
            }
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
        $wpins = new Plugin_Usage_Tracker(
            EAEL_PLUGIN_FILE,
            'http://app.wpdeveloper.net',
            array(),
            true,
            true,
            1
        );
    }
}

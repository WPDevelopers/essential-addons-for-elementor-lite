<?php
namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly.

class WPDeveloper_Plugin_Installer
{
    public function __construct()
    {
        add_action('wp_ajax_wpdeveloper_install_plugin', [$this, 'ajax_install_plugin']);
        add_action('wp_ajax_wpdeveloper_upgrade_plugin', [$this, 'ajax_upgrade_plugin']);
        add_action('wp_ajax_wpdeveloper_activate_plugin', [$this, 'ajax_activate_plugin']);
    }

    public function get_local_plugin_data($basename = '')
    {
        if (empty($basename)) {
            return false;
        }

        if (!function_exists('get_plugins')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $plugins = get_plugins();

        if (!isset($plugins[$basename])) {
            return false;
        }

        return $plugins[$basename];
    }

    public function get_remote_plugin_data($slug = '')
    {
        if (empty($slug)) {
            return;
        }

        $response = wp_remote_post(
            'http://api.wordpress.org/plugins/info/1.0/',
            [
                'body' => [
                    'action' => 'plugin_information',
                    'request' => serialize((object) [
                        'slug' => $slug,
                        'fields' => [
                            'version' => false,
                        ],
                    ]),
                ],
            ]
        );

        if (is_wp_error($response)) {
            return $response->get_error_message();
        }

        return unserialize(wp_remote_retrieve_body($response));
    }

    public function install_plugin($slug = '', $active = true)
    {
        if (empty($slug)) {
            return;
        }

        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';

        $plugin_data = $this->get_remote_plugin_data($slug);

        if (!isset($plugin_data->download_link)) {
            return;
        }

        $upgrader = new \Plugin_Upgrader(new \Automatic_Upgrader_Skin());

        // install plugin
        $install = $upgrader->install($plugin_data->download_link);

        // activate plugin
        if ($install === true && $active) {
            $active = activate_plugin($upgrader->plugin_info(), '', false, true);

            if (is_wp_error($active)) {
                return $active;
            }

            return $active === null;
        }

        return $install;
    }

    public function upgrade_plugin($basename = '')
    {
        if (empty($slug)) {
            return;
        }

        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';

        $upgrader = new \Plugin_Upgrader(new \Automatic_Upgrader_Skin());

        // upgrade plugin
        return $upgrader->upgrade($basename);
    }

    public function ajax_install_plugin()
    {
        check_ajax_referer('essential-addons-elementor', 'security');

        if (!isset($_POST['slug'])) {
            wp_send_json_error(__('Plugin name not defined.', 'essential-addons-for-elementor-lite'));
        }

        $result = $this->install_plugin($_POST['slug']);

        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }

        wp_send_json_success(__('Plugin installed successfully!', 'essential-addons-for-elementor-lite'));
    }

    public function ajax_upgrade_plugin()
    {
        check_ajax_referer('essential-addons-elementor', 'security');

        if (!isset($_POST['basename'])) {
            wp_send_json_error(__('Plugin name not defined.', 'essential-addons-for-elementor-lite'));
        }

        $result = $this->upgrade_plugin($_POST['basename']);

        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }

        wp_send_json_success(__('Plugin upgraded successfully!', 'essential-addons-for-elementor-lite'));
    }

    public function ajax_activate_plugin()
    {
        check_ajax_referer('essential-addons-elementor', 'security');

        if (!isset($_POST['basename'])) {
            wp_send_json_error(__('Plugin name not defined.', 'essential-addons-for-elementor-lite'));
        }

        $result = \activate_plugin($_POST['basename'], '', false, true);

        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }

        wp_send_json_success(__('Plugin activated successfully!', 'essential-addons-for-elementor-lite'));
    }
}

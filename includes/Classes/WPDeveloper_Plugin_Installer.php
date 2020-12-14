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
    }

    public function get_local_plugin_data($basename = '')
    {
        if (empty($basename)) {
            return;
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
            return false;
        }

        $response = unserialize(wp_remote_retrieve_body($response));

        if ($response) {
            return $response;
        }

        return false;
    }

    public function install_plugin($slug, $active = true)
    {
        if (empty($slug)) {
            return;
        }

        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';

        $plugin_data = $this->get_remote_plugin_data($slug);
        $skin = new \Automatic_Upgrader_Skin();
        $upgrader = new \Plugin_Upgrader($skin);

        // install plugin
        $upgrader->install($plugin_data->download_link);

        // activate plugin
        if ($active) {
            activate_plugin($upgrader->plugin_info(), '', false, true);
        }

        return $skin->result;
    }

    public function upgrade_plugin($basename = '')
    {
        if (empty($slug)) {
            return;
        }

        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';

        $skin = new \Automatic_Upgrader_Skin();
        $upgrader = new \Plugin_Upgrader($skin);

        // upgrade plugin
        $upgrader->upgrade($basename);

        return $skin->result;
    }

    public function ajax_install_plugin()
    {
        check_ajax_referer('essential-addons-elementor', 'security');

        if (!isset($_POST['slug'])) {
            return;
        }

        echo $this->install_plugin($_POST['slug']);
        die();
    }

    public function ajax_upgrade_plugin()
    {
        check_ajax_referer('essential-addons-elementor', 'security');

        if (!isset($_POST['basename'])) {
            return;
        }

        echo $this->upgrade_plugin($_POST['basename']);
        die();
    }
}

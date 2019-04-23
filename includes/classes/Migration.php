<?php

namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class Migration
{
    use \Essential_Addons_Elementor\Traits\Core;

    /**
     * Plugin activation hook
     *
     * @since 3.0.0
     */
    public static function plugin_activation_hook()
    {
        // remove old cache files
        self::empty_dir(EAEL_ASSET_PATH);

        // Redirect to options page
        update_option('eael_do_activation_redirect', true);
    }

    /**
     * Plugin deactivation hook
     *
     * @since 3.0.0
     */
    public static function plugin_deactivation_hook()
    {
        self::empty_dir(EAEL_ASSET_PATH);
    }

    /**
     * Plugin upgrade hook
     *
     * @since 3.0.0
     */
    public static function plugin_upgrade_hook()
    {
        self::empty_dir(EAEL_ASSET_PATH);
    }
}

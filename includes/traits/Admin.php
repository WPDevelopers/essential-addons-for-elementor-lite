<?php
namespace EssentialAddonsElementor\Traits;

if (!defined('ABSPATH')) {
    exit();
}
// Exit if accessed directly

trait Admin
{
    protected $is_pro = false;

    /**
     * Create an admin menu.
     * @param
     * @return void
     * @since 1.1.2
     */
    public function admin_menu()
    {
        add_submenu_page(
            'elementor',
            'Essential Addons',
            'Essential Addons',
            'manage_options',
            'eael-settings',
            array($this, 'eael_admin_settings_page')
        );
    }

    /**
     * Loading all essential scripts
     * @param
     * @return void
     * @since 1.1.2
     */
    public function admin_enqueue_scripts($hook)
    {
        wp_enqueue_style('essential_addons_elementor-notice-css', $this->plugin_url . '/assets/admin/css/eael-notice.css', false, $this->plugin_version);

        if (isset($hook) && $hook == 'plugins.php') {
            wp_enqueue_style('sweetalert2-css', $this->plugin_url . '/assets/admin/vendor/sweetalert2/css/sweetalert2.min.css', false, $this->plugin_version);
            wp_enqueue_script('sweetalert2-js', $this->plugin_url . '/assets/admin/vendor/sweetalert2/js/sweetalert2.min.js', array('jquery', 'essential_addons_core-js'), $this->plugin_version, true);
            wp_enqueue_script('sweetalert2-core-js', $this->plugin_url . '/assets/admin/vendor/sweetalert2/js/core.js', array('jquery'), $this->plugin_version, true);
        } // check this

        if (isset($hook) && $hook == 'elementor_page_eael-settings') {
            wp_enqueue_style('essential_addons_elementor-admin-css', $this->plugin_url . '/assets/admin/css/admin.css', false, $this->plugin_version);
            wp_enqueue_style('sweetalert2-css', $this->plugin_url . '/assets/admin/vendor/sweetalert2/css/sweetalert2.min.css', false, $this->plugin_version);
            wp_enqueue_script('sweetalert2-js', $this->plugin_url . '/assets/admin/vendor/sweetalert2/js/sweetalert2.min.js', array('jquery', 'essential_addons_core-js'), $this->plugin_version, true);
            wp_enqueue_script('sweetalert2-core-js', $this->plugin_url . '/assets/admin/vendor/sweetalert2/js/core.js', array('jquery'), $this->plugin_version, true);
            wp_enqueue_script('essential_addons_elementor-admin-js', $this->plugin_url . '/assets/admin/js/admin.js', array('jquery'), $this->plugin_version, true);

            wp_localize_script('essential_addons_elementor-admin-js', 'localize', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('essential-addons-elementor'),
            ));
        }
    }

    /**
     * Create settings page.
     * @param
     * @return void
     * @since 1.1.2
     */
    public function eael_admin_settings_page()
    {
        echo '<div class="eael-settings-wrap">
		  	<form action="" method="POST" id="eael-settings" name="eael-settings">
		  		<div class="eael-header-bar">
					<div class="eael-header-left">
						<div class="eael-admin-logo-inline">
							<img src="' . $this->plugin_url . '/assets/admin/images/ea-logo.svg' . '">
						</div>
						<h2 class="title">' . __('Essential Addons Settings', 'essential-addons-elementor') . '</h2>
					</div>
					<div class="eael-header-right">
					<button type="submit" class="button eael-btn js-eael-settings-save">' . __('Save settings', 'essential-addons-elementor') . '</button>
					</div>
				</div>
			  	<div class="eael-settings-tabs">
			    	<ul class="eael-tabs">
				      	<li><a href="#general" class="active"><img src="' . $this->plugin_url . '/assets/admin/images/icon-settings.svg' . '"><span>General</span></a></li>
				      	<li><a href="#elements"><img src="' . $this->plugin_url . '/assets/admin/images/icon-modules.svg' . '"><span>Elements</span></a></li>
						<li><a href="#extensions"><img src="' . $this->plugin_url . '/assets/admin/images/icon-extensions.svg' . '"><span>Extensions</span></a></li>
						<li><a href="#go-pro"><img src="' . $this->plugin_url . '/assets/admin/images/icon-upgrade.svg' . '"><span>Go Premium</span></a></li>
                    </ul>';
                    include_once $this->plugin_path . 'includes/templates/admin/general.php';
                    include_once $this->plugin_path . 'includes/templates/admin/elements.php';
                    include_once $this->plugin_path . 'includes/templates/admin/extensions.php';
                    include_once $this->plugin_path . 'includes/templates/admin/go-pro.php';
                echo '</div>
            </form>
        </div>';
    }
}

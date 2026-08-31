<?php
namespace Essential_Addons_Elementor\Classes;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly.

use \WP_Error;

class WPDeveloper_Plugin_Installer
{
	/**
	 * Have this class's AJAX endpoints already been registered.
	 *
	 * The constructor has a global side effect, so a second instance built to
	 * reuse install_plugin() — which is a plain method, not a static one — would
	 * bind every endpoint a second time. add_action() keys callbacks by object
	 * hash, so those are genuine duplicates: the handler would run twice and send
	 * two JSON bodies. Registration belongs to whichever instance is built first.
	 *
	 * @var bool
	 */
	private static $endpoints_registered = false;

	public function __construct() {
		if ( self::$endpoints_registered ) {
			return;
		}

		self::$endpoints_registered = true;

		add_action( 'wp_ajax_wpdeveloper_auto_active_even_not_installed', [ $this, 'ajax_auto_active_even_not_installed' ] );
		add_action( 'wp_ajax_wpdeveloper_install_plugin', [ $this, 'ajax_install_plugin' ] );
		add_action( 'wp_ajax_wpdeveloper_upgrade_plugin', [ $this, 'ajax_upgrade_plugin' ] );
		add_action( 'wp_ajax_wpdeveloper_activate_plugin', [ $this, 'ajax_activate_plugin' ] );
		add_action( 'wp_ajax_wpdeveloper_deactivate_plugin', [ $this, 'ajax_deactivate_plugin' ] );
	}

	/**
	 * Consume a freshly activated plugin's own "go to my setup wizard" flag.
	 *
	 * Plugins installed from an EA surface are activated in the background over
	 * AJAX, and EA then sends the user where its own CTA promised — ThinkRank's
	 * dashboard, for instance. ThinkRank's activator sets a 60-second transient
	 * that redirects the NEXT admin page load to its Setup Wizard, so without
	 * this the promised destination is hijacked on arrival.
	 *
	 * Called after every successful EA-driven activation, so the guarantee holds
	 * for the admin banner, Quick Setup and the plain activate endpoint alike.
	 * Harmless no-op for plugins with no such flag.
	 *
	 * @param string $slug wp.org slug of the plugin just activated.
	 * @return void
	 */
	public static function suppress_activation_redirect( $slug ) {
		$flags = [
			'thinkrank' => 'thinkrank_setup_wizard_redirect',
		];

		if ( isset( $flags[ $slug ] ) ) {
			delete_transient( $flags[ $slug ] );
		}
	}

    /**
     * get_local_plugin_data
     *
     * @param  mixed $basename
     * @return array|false
     */
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

    /**
     * get_remote_plugin_data
     *
     * @param  mixed $slug
     * @return mixed array|WP_Error
     */
    public function get_remote_plugin_data($slug = '')
    {
        if (empty($slug)) {
            return new WP_Error('empty_arg', __('Argument should not be empty.', 'essential-addons-for-elementor-lite'));
        }

        // Core's plugins_api() talks to the HTTPS JSON endpoint with certificate
        // verification and returns a structured object. It never feeds a remote
        // response body to unserialize(), so neither the object-injection sink
        // nor an attacker-chosen download_link survives a MITM.
        if ( ! function_exists( 'plugins_api' ) ) {
            include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }

        $plugin_data = plugins_api(
            'plugin_information',
            [
                'slug'   => $slug,
                'fields' => [
                    'version' => false,
                ],
            ]
        );

        if ( is_wp_error( $plugin_data ) ) {
            return $plugin_data;
        }

        if ( ! is_object( $plugin_data ) ) {
            return new WP_Error( 'invalid_response', __( 'Could not retrieve plugin information.', 'essential-addons-for-elementor-lite' ) );
        }

        return $plugin_data;
    }

    /**
     * install_plugin
     *
     * @param  mixed $slug
     * @param  bool $active
     * @return mixed bool|WP_Error
     */
    public function install_plugin($slug = '', $active = true)
    {
        if (empty($slug)) {
            return new WP_Error('empty_arg', __('Argument should not be empty.', 'essential-addons-for-elementor-lite'));
        }

        include_once ABSPATH . 'wp-admin/includes/file.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        include_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		// Already installed? Plugin_Upgrader::install() would return null
		// (not true, not WP_Error), so the caller would report success while
		// the plugin was never activated. Activate the existing copy instead.
		foreach ( array_keys( get_plugins() ) as $installed_basename ) {
			if ( 0 !== strpos( $installed_basename, $slug . '/' ) ) {
				continue;
			}

			if ( ! $active || is_plugin_active( $installed_basename ) ) {
				return true;
			}

			// xSpeed must be configured BEFORE activation — see XSpeed_Setup.
			$prepared  = XSpeed_Setup::before_activation( $slug );
			$activated = activate_plugin( $installed_basename, '', false, false );

			if ( is_wp_error( $activated ) ) {
				XSpeed_Setup::activation_failed( $slug, $prepared );

				return $activated;
			}

			XSpeed_Setup::after_activation( $slug );
			self::suppress_activation_redirect( $slug );

			return true;
		}

        $plugin_data = $this->get_remote_plugin_data($slug);

        if (is_wp_error($plugin_data)) {
            return $plugin_data;
        }

        if ( empty( $plugin_data->download_link ) ) {
            return new WP_Error( 'no_download_link', __( 'Could not retrieve plugin information.', 'essential-addons-for-elementor-lite' ) );
        }

        $upgrader = new \Plugin_Upgrader(new \Automatic_Upgrader_Skin());

        // install plugin
        $install = $upgrader->install($plugin_data->download_link);

        if (is_wp_error($install)) {
            return $install;
        }

        // activate plugin
        if ($install === true && $active) {
            // xSpeed reads its stored settings during activation instead of
            // stamping over them, so the state it should come up in has to be
            // written first — see XSpeed_Setup. No-op for every other plugin.
            $prepared = XSpeed_Setup::before_activation( $slug );

            // Not silent: silent activation skips the "activate_{$plugin}" hook,
            // which is what register_activation_hook() binds to. Suppressing it
            // leaves the freshly installed plugin without its tables, default
            // options and cron events.
            $active = activate_plugin($upgrader->plugin_info(), '', false, false);

            if (is_wp_error($active)) {
                XSpeed_Setup::activation_failed( $slug, $prepared );

                return $active;
            }

            XSpeed_Setup::after_activation( $slug );
            self::suppress_activation_redirect( $slug );

            return $active === null;
        }

        return $install;
    }

    /**
     * upgrade_plugin
     *
     * @param  mixed $basename
     * @return mixed bool|WP_Error
     */
    public function upgrade_plugin($basename = '')
    {
        if (empty($slug)) {
            return new WP_Error('empty_arg', __('Argument should not be empty.', 'essential-addons-for-elementor-lite'));
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

        if(!current_user_can( 'install_plugins' )) {
            wp_send_json_error(__('you are not allowed to do this action', 'essential-addons-for-elementor-lite'));
        }

	    $slug   = isset( $_POST['slug'] ) ? sanitize_text_field( wp_unslash( $_POST['slug'] ) ) : '';
	    $result = $this->install_plugin( $slug );

        if ( isset( $_POST['promotype'], $_POST['slug'] ) ) {
            $promotype = sanitize_text_field( wp_unslash( $_POST['promotype'] ) );
            $slug      = sanitize_text_field( wp_unslash( $_POST['slug'] ) );
        
            $remote_urls = [
                'quick-setup' => [
                    'essential-blocks' => 'https://essential-addons.com/essential-blocks-install-quick-setup',
                    'templately'       => 'https://essential-addons.com/templately-install-quick-setup',
                ]
            ];
        
            if ( isset( $remote_urls[ $promotype ][ $slug ] ) ) {
                wp_remote_get( $remote_urls[ $promotype ][ $slug ] );
            }
        }

	    if ( is_wp_error( $result ) ) {
		    wp_send_json_error( $result->get_error_message() );
	    }

	    // install_plugin() also returns false / an array when Plugin_Upgrader
	    // bails without a WP_Error. Only a strict true means installed+activated.
	    if ( true !== $result ) {
		    wp_send_json_error( __( 'Plugin could not be installed.', 'essential-addons-for-elementor-lite' ) );
	    }

        wp_send_json_success(__('Plugin is installed successfully!', 'essential-addons-for-elementor-lite'));
    }

    public function ajax_upgrade_plugin()
    {
        check_ajax_referer('essential-addons-elementor', 'security');
        //check user capabilities
        if(!current_user_can( 'update_plugins' )) {
            wp_send_json_error(__('you are not allowed to do this action', 'essential-addons-for-elementor-lite'));
        }

	    $basename = isset( $_POST['basename'] ) ? sanitize_text_field( wp_unslash( $_POST['basename'] ) ) : '';
	    $result   = $this->upgrade_plugin( $basename );

        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }

        wp_send_json_success(__('Plugin is updated successfully!', 'essential-addons-for-elementor-lite'));
    }

    public function ajax_activate_plugin()
    {
        check_ajax_referer('essential-addons-elementor', 'security');

        //check user capabilities
        if(!current_user_can( 'activate_plugins' )) {
            wp_send_json_error(__('you are not allowed to do this action', 'essential-addons-for-elementor-lite'));
        }

	    $basename = isset( $_POST['basename'] ) ? sanitize_text_field( wp_unslash( $_POST['basename'] ) ) : '';

	    // The Integrations toggle reaches an already-installed plugin here
	    // rather than through install_plugin(), so xSpeed's settings-before-
	    // activation ordering has to be honoured on this path too.
	    $slug     = XSpeed_Setup::slug_for_basename( $basename );
	    $prepared = XSpeed_Setup::before_activation( $slug );

	    // Not silent — see install_plugin(): a silent activation never fires the
	    // plugin's own activation hook.
	    $result   = activate_plugin( $basename, '', false, false );

	    if ( is_wp_error( $result ) ) {
		    XSpeed_Setup::activation_failed( $slug, $prepared );

		    wp_send_json_error( $result->get_error_message() );
	    }

        if ($result === false) {
            XSpeed_Setup::activation_failed( $slug, $prepared );

            wp_send_json_error(__('Plugin couldn\'t be activated.', 'essential-addons-for-elementor-lite'));
        }

        XSpeed_Setup::after_activation( $slug );
        self::suppress_activation_redirect( $slug );

        wp_send_json_success(__('Plugin is activated successfully!', 'essential-addons-for-elementor-lite'));
    }

	public function ajax_deactivate_plugin() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		//check user capabilities
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		$basename = isset( $_POST['basename'] ) ? sanitize_text_field( wp_unslash( $_POST['basename'] ) ) : '';
		deactivate_plugins( $basename, true );

		wp_send_json_success( __( 'Plugin is deactivated successfully!', 'essential-addons-for-elementor-lite' ) );
	}

	public function ajax_auto_active_even_not_installed() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( !empty( $_POST['basename'] ) && $this->get_local_plugin_data( sanitize_text_field( wp_unslash( $_POST['basename'] ) ) ) === false ) {
			$this->ajax_install_plugin();
		} else {
			$this->ajax_activate_plugin();
		}
	}
}

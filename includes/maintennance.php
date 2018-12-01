<?php
if( !defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

class EAEL_Version_Maintennance {

    /**
     * Instance of this class
     * 
     * @return null|EAEL_Version_Maintennance
     */
    private static $_instance = null;

    public function get_instance() {
        if( is_null(self::$_instance) ) {
            self::$_instance = new self();
        }
        return $_instance;
    }

    public function __construct() {
        add_action( 'admin_enqueue_scripts', [$this, 'eael_version_control_script'] );
        add_action( 'admin_post_eael_version_rollback', [$this, 'post_eael_version_rollback'] );
    }

    /**
     * Version control page script
     */
    function eael_version_control_script() {
        wp_enqueue_script(
            'eael-rollback-admin-js',
            ESSENTIAL_ADDONS_EL_URL . 'admin/assets/js/rollback-admin.js',
            [],
            '1.0',
            true
        );

        wp_localize_script(
            'eael-rollback-admin-js',
            'EAELRollBackConfirm',
            [
                'home_url' => home_url(),
                'i18n'     => [
					'rollback_confirm'             => __( 'Are you sure you want to reinstall version ' . ESSENTIAL_ADDONS_STABLE_VERSION . ' ?', 'essential-addons-elementor' ),
					'rollback_to_previous_version' => __( 'Rollback to Previous Version', 'essential-addons-elementor' ),
					'yes'                          => __( 'Yes', 'essential-addons-elementor' ),
					'cancel'                       => __( 'Cancel', 'essential-addons-elementor' ),
				],
            ]
        );
    }

    /**
     * Trigger the EAEL rollback function
     * 
     * @since 2.8.5
     */
    function post_eael_version_rollback() {
        check_admin_referer( 'eael_version_rollback' );
        $plugin_slug = 'essential-addons-for-elementor-lite';

        $eael_rollback = new EAEL_Version_Rollback([
            'plugin_version' => ESSENTIAL_ADDONS_STABLE_VERSION,
            'plugin_name'    => ESSENTIAL_ADDONS_BASENAME,
            'plugin_slug'    => $plugin_slug,
            'package_url'    => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, ESSENTIAL_ADDONS_STABLE_VERSION )
        ]);
        $eael_rollback->run();

      wp_die(
         '', __( 'Rollback to Previous Version', 'essential-addons-elementor' ),
         [ 'response' => 200 ]
      );
    }

}

EAEL_Version_Maintennance::get_instance();
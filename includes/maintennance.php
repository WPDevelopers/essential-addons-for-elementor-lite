<?php
if( !defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

class EAEL_Version_Maintennance {

    /**
     * Instance of this class
     * 
     * @return null|EAEL_Version_Maintennance
     */
    private static $_instance = null;

    /**
     * Upgrade to version
     * 
     * @return string
     */
    protected $upgrade_to_version;

    public static function get_instance() {
        if( is_null(self::$_instance) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'admin_enqueue_scripts', [$this, 'eael_version_control_script'] );
        add_action( 'admin_post_eael_version_rollback', [$this, 'post_eael_version_rollback'] );
    }

        /**
     * Trigger the EAEL rollback function
     * 
     * @since 2.8.5
     */
    function post_eael_version_rollback() {
        check_admin_referer( 'eael_version_rollback' );

        $upgrade_to = ( ! empty($_GET['upgrade_version']) ) ? $_GET['upgrade_version'] : ESSENTIAL_ADDONS_STABLE_VERSION;

        $this->upgrade_to_version = $upgrade_to;

        $plugin_slug = 'essential-addons-for-elementor-lite';
        $eael_rollback = new EAEL_Version_Rollback([
            'plugin_version' => $upgrade_to,
            'plugin_name'    => ESSENTIAL_ADDONS_BASENAME,
            'plugin_slug'    => $plugin_slug,
            'package_url'    => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, $upgrade_to)
        ]);
        
        $eael_rollback->run();

      wp_die(
         '', __( 'Rollback to Previous Version', 'essential-addons-elementor' ),
         [ 'response' => 200 ]
      );
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
					'rollback_confirm'             => __( 'Are you sure you want to perform this rollback?', 'essential-addons-elementor' ),
					'rollback_to_previous_version' => __( 'Rollback to Previous Version', 'essential-addons-elementor' ),
					'yes'                          => __( 'Yes', 'essential-addons-elementor' ),
					'cancel'                       => __( 'Cancel', 'essential-addons-elementor' ),
				],
            ]
        );
    }

}

EAEL_Version_Maintennance::get_instance();
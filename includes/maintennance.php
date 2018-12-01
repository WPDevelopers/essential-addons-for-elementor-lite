<?php
if( !defined( 'ABSPATH' ) ) exit; //Exit if accessed directly

add_action( 'admin_enqueue_scripts', 'eael_localize_rollback_script' );
if( ! function_exists('eael_localize_rollback_script') ) {
    function eael_localize_rollback_script() {
        wp_enqueue_script(
           'rollback-admin-js',
           ESSENTIAL_ADDONS_EL_URL . 'admin/assets/js/rollback-admin.js',
           [],
           '1.0',
           true
        );
     
        wp_localize_script(
           'rollback-admin-js',
           'EAELRollBackConfirm',
           [
              'home_url' => home_url(),
              'i18n'     => [
                 'rollback_confirm'             => __( 'Are you sure you want to reinstall version ' . ESSENTIAL_ADDONS_STABLE_VERSION . ' ?', 'essential-addons-elementor' ),
                 'rollback_to_previous_version' => __( 'Rollback to Previous Version', 'essential-addons-elementor' ),
                 'yes'                          => __( 'Yes', 'essential-addons-elementor' ),
                 'cancel'                       => __( 'Cancel', 'essential-addons-elementor' ),
              ]
           ]
        );
     }
}


if( ! function_exists( 'post_eael_version_rollback' ) ) {

    /**
     * Trigger the EAEL rollback function
     * 
     * @since 2.8.5
     */
    function post_eael_version_rollback() {
        check_admin_referer( 'eael_version_rollback' );
        $plugin_slug = basename( ESSENTIAL_ADDONS_EL_ROOT, '.php' );
        

        $eael_rollback = new EAEL_Rollback([
            'version'     => ESSENTIAL_ADDONS_STABLE_VERSION,
            'plugin_name' => ESSENTIAL_ADDONS_BASENAME,
            'plugin_slug' => $plugin_slug,
            'package_url' => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, ESSENTIAL_ADDONS_STABLE_VERSION ),
        ]);
        $eael_rollback->run();

        wp_die(
            '', __( 'Rollback to Previous Version', 'essential-addons-elementor' ),
            [ 'response' => 200 ]
        );


    }
    
}
add_action( 'admin_post_post_eael_version_rollback', 'post_eael_version_rollback' );
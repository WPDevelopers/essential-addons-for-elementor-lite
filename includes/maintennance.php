<?php
if( !defined( 'ABSPATH' ) ) exit; //Exit if accessed directly


if( ! function_exists( 'post_eael_version_rollback' ) ) {

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
add_action( 'admin_post_eael_version_rollback', 'post_eael_version_rollback' );
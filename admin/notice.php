<?php
/**
 * Notice
 *
 * Notice related functionality goes in this file.
 *
 * @since   1.0.0
 * @package WP
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'eael_review_notice' ) ) {
    // Add an admin notice.
   add_action('admin_notices', 'eael_review_notice');

    /**
     *  Admin Notice to Encourage a Review or Donation.
     *
     *  @author Matt Cromwell
     *  @version 1.0.0
     */
    function eael_review_notice() {

        // Define your Plugin name, review url, and donation url.
        $plugin_name = 'Essential Addons for Elementor';
        $review_url = 'https://wordpress.org/support/plugin/essential-addons-for-elementor-lite/reviews/#new-post';
        $demo_url = 'https://essential-addons.com/elementor/';

        // Get current user.
        global $current_user, $pagenow ;
        $user_id = $current_user->ID;

        // Get today's timestamp.
        $today = mktime( 0, 0, 0, date('m')  , date('d'), date('Y') );

        // Get the trigger date
        $triggerdate = get_option( 'eael_activation_date', false );

        $installed = ( ! empty( $triggerdate ) ? $triggerdate : '7' );

        // First check whether today's date is greater than the install date plus the delay
        // Then check whether the user is a Super Admin or Admin on a non-Multisite Network
        // For testing live, remove `$installed <= $today &&` from this conditional
        if ( $installed <= $today && danp_is_super_admin_admin( $current_user = $current_user ) == true ) {
            
            // If the user hasn't already dismissed our alert,
            // Output the activation banner.
            $nag_admin_dismiss_url = 'plugins.php?eael_review_dismiss=0';
            $user_meta             = get_user_meta( $user_id, 'eael_review_dismiss' );

            if ( empty($user_meta) ) {

                ?>
                <div class="notice notice-success">

                    <style>
                        p.eael-review {
                            position: relative;
                            margin-left: 35px;
                        }
                        p.eael-review span.dashicons-heart {
                            color: white;
                            background: #66BB6A;
                            position: absolute;
                            left: -50px;
                            padding: 9px;
                            top: -8px;
                        }

                        p.eael-review strong {
                            color: #66BB6A;
                        }
                        p.eael-review a.dismiss {
                            float: right;
                            text-decoration: none;
                            color: #66BB6A;
                        }
                    </style>
                    <?php
                    // For testing purposes
                    // echo '<p>Today = ' . $today . '</p>';
                    // echo '<p>Installed = ' . $installed . '</p>';
                    ?>

                    <p class="eael-review"><span class="dashicons dashicons-heart"></span><?php echo wp_kses( sprintf( __( 'Are you enjoying <strong>' . $plugin_name . '</strong>? You might not realize it, but user reviews are such a great help to us. We would be so grateful if you could take a minute to leave a review on WordPress.org. Many thanks in advance! :) <a href="%2$s" target="_blank">Leave a Review', 'essential-addons-elementor' ), esc_url( $demo_url ), esc_url( $review_url ) ), array( 'strong' => array(), 'a' => array( 'href' => array(), 'target' => array() ) ) ); ?><a href="<?php echo admin_url( $nag_admin_dismiss_url ); ?>" class="dismiss"><span class="dashicons dashicons-dismiss"></span></a>

                </div>

            <?php }
            
        }
    }
}


if ( ! function_exists( 'eael_ignore_review_notice' ) ) {
    // Function to force the Review Admin Notice to stay dismissed correctly.
    add_action('admin_init', 'eael_ignore_review_notice');

    /**
     * Ignore review notice.
     *
     * @since  1.0.0
     */
    function eael_ignore_review_notice() {
        if ( isset( $_GET[ 'eael_review_dismiss' ] ) && '0' == $_GET[ 'eael_review_dismiss' ] ) {

            // Get the global user.
            global $current_user;
            $user_id = $current_user->ID;

            add_user_meta( $user_id, 'eael_review_dismiss', 'true', true );
        }
    }
}

if ( ! function_exists( 'danp_is_super_admin_admin' ) ) {

    // Helper function to determine whether the current
    // user is a Super Admin or Admin on a non-Network environment
    function danp_is_super_admin_admin($current_user)
    {
        global $current_user;

        $shownotice = false;

        if (is_multisite() && current_user_can('create_sites')) {
            $shownotice = true;
        } elseif (is_multisite() == false && current_user_can('install_plugins')) {
            $shownotice = true;
        } else {
            $shownotice = false;
        }

        return $shownotice;
    }
}

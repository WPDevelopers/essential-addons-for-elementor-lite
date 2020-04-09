<?php
namespace Essential_Addons_Elementor\Template\Woocommerce\Checkout;

use Elementor\Icons_Manager;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Checkout_Login {

    public static function checkout_login_template() {
	    $settings = self::get_settings();

        if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
            return;
        }

        ?>
        <div class="woo-checkout-login">
            <div class="ea-login-icon">
	            <?php Icons_Manager::render_icon( $settings['ea_woo_checkout_login_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </div>
            <?php wc_get_template('checkout/form-login.php', array('checkout' => WC()->checkout(),)); ?>
        </div>
    <?php }

}





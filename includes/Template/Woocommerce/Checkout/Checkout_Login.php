<?php
namespace Essential_Addons_Elementor\Template\Woocommerce\Checkout;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Checkout_Login {

    public static function checkout_login_template() {

        if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
            return;
        }

        ?>
        <div class="woo-checkout-login">
            <?php wc_get_template('checkout/form-login.php', array('checkout' => WC()->checkout(),)); ?>
        </div>
    <?php }
}





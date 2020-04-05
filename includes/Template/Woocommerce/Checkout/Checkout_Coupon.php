<?php
namespace Essential_Addons_Elementor\Template\Woocommerce\Checkout;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Checkout_Coupon {

    public static function checkout_coupon_template() {
        ?>
        <div class="woo-checkout-coupon">
            <?php wc_get_template('checkout/form-coupon.php', array('checkout' => WC()->checkout(),)); ?>
        </div>
    <?php }
}





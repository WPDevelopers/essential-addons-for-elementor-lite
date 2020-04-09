<?php
namespace Essential_Addons_Elementor\Template\Woocommerce\Checkout;

use Elementor\Icons_Manager;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Checkout_Coupon {

    public static function checkout_coupon_template() {
        $settings = self::get_settings();
        
	    ?>
        <div class="woo-checkout-coupon">
            <div class="ea-coupon-icon">
	            <?php Icons_Manager::render_icon( $settings['ea_woo_checkout_coupon_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </div>
            <?php wc_get_template('checkout/form-coupon.php', array('checkout' => WC()->checkout(),)); ?>
        </div>
    <?php }
}





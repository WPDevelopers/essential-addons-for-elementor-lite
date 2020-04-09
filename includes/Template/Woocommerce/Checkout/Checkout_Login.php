<?php
namespace Essential_Addons_Elementor\Template\Woocommerce\Checkout;

use Elementor\Icons_Manager;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Checkout_Login {

//	add_filter('ea_checkout_login_template', 'checkout_login_template');

    public static function checkout_login_template() {
//	    parse_str($_REQUEST['settings'], $settings);
	    $settings = $this->get_settings();
	    var_dump($settings);

        ?>
        <div class="woo-checkout-login">
            <div class="ea-login-icon">
<!--	            --><?php //Icons_Manager::render_icon( $settings['ea_woo_checkout_login_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </div>
            <?php wc_get_template('checkout/form-login.php', array('checkout' => WC()->checkout(),)); ?>
        </div>
    <?php }



}





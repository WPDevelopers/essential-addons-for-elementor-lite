<?php
namespace Essential_Addons_Elementor\Template\Woocommerce\Checkout;

use Elementor\Icons_Manager;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Checkout_Coupon {

    public static function checkout_coupon_template($settings) {
	    ?>
        <div class="woo-checkout-coupon">
            <div class="ea-coupon-icon">
	        <?php
	        $migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
	        $is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

            if ( $is_new || $migrated ) :
		        Icons_Manager::render_icon( $settings['ea_woo_checkout_coupon_icon'], [ 'aria-hidden' => 'true' ] );
	        else : ?>
                <i class="<?php echo esc_attr($settings['ea_woo_checkout_coupon_icon'] ); ?> eael-creative-button-icon-left" aria-hidden="true"></i>
	        <?php endif; ?>
            </div>
            <?php wc_get_template('checkout/form-coupon.php', array('checkout' => WC()->checkout(),)); ?>
        </div>
    <?php }
}





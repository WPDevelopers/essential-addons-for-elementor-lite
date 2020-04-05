<?php
namespace Essential_Addons_Elementor\Template\Woocommerce\Checkout\Layouts;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Woo_Checkout_Default {

    use \Essential_Addons_Elementor\Template\Woocommerce\Checkout\Order_Review;
    use \Essential_Addons_Elementor\Template\Woocommerce\Checkout\Checkout_Login;
    use \Essential_Addons_Elementor\Template\Woocommerce\Checkout\Checkout_Coupon;

    public static function render_default_template_($checkout, $settings) { ?>
        <h3 id="order_review_heading woo-checkout-section-title"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>
        <div class="eael-order-review-wapper">
            <?php self::order_review_template(); ?>
        </div>

        <?php //    Order Details Login
        // If checkout registration is disabled and not logged in, the user cannot checkout.
        if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
            echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
            return;
        }

        self::checkout_login_template();

        // Order Details Coupon
        self::checkout_coupon_template();

        ?>

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

            <?php if ( $checkout->get_checkout_fields() ) : ?>

                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                <div class="col2-set" id="customer_details">
                    <div class="col-1">
                        <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    </div>

                    <div class="col-2">
                        <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                    </div>
                </div>

                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            <?php endif; ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php woocommerce_checkout_payment(); ?>
            </div>

        </form>

        <?php

    }
}


<?php
namespace Essential_Addons_Elementor\Template\Woocommerce\Checkout\Layouts;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Woo_Checkout_Default {

    use \Essential_Addons_Elementor\Template\Woocommerce\Checkout\Order_Review;
    use \Essential_Addons_Elementor\Template\Woocommerce\Checkout\Checkout_Login;
    use \Essential_Addons_Elementor\Template\Woocommerce\Checkout\Checkout_Coupon;

    public static function render_default_template_($checkout, $settings) {

        do_action('woocommerce_checkout_before_order_review_heading');
        ?>
        <h3 id="order_review_heading" class="woo-checkout-section-title"><?php esc_html_e( 'Your order', 'essential-addons-for-elementor-lite' ); ?></h3>

        <div class="ea-woo-checkout-order-review">
            <?php self::order_review_template($settings); ?>
        </div>

        <?php
        // If checkout registration is disabled and not logged in, the user cannot checkout.
        if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
            echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
            return;
        }

        // Login
//        self::checkout_login_template($settings);

        // Coupon
//        self::checkout_coupon_template($settings);

        ?>
        <?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>
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

            <div class="woo-checkout-payment">
                <h3 id="payment-title" class="woo-checkout-section-title"><?php esc_html_e( 'Payment Methods', 'essential-addons-for-elementor-lite' ); ?></h3>

                <?php woocommerce_checkout_payment(); ?>
            </div>

        </form>

        <?php
        do_action('woocommerce_after_checkout_form', $checkout);

    }
}


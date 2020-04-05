<?php
namespace Essential_Addons_Elementor\Template\Woocommerce\Checkout;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Order_Review {

    public static function order_review_template() {
        ?>
        <div class="ea-checkout-review-order-table">
            <ul class="responsive-table">
                <li class="table-header">
                    <div class="table-col-1"><?php esc_html_e( 'Product', 'woocommerce' ); ?></div>
                    <div class="table-col-2"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></div>
                    <div class="table-col-3"><?php esc_html_e( 'Price', 'woocommerce' ); ?></div>
                </li>

                <?php
                do_action( 'woocommerce_review_order_before_cart_contents' );

                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        ?>
                        <li class="table-row <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                            <div class="table-col-1 product-thum-name">
                                <div class="product-thumbnail">
                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                    if ( ! $product_permalink ) {
                                        echo $thumbnail; // PHPCS: XSS ok.
                                    } else {
                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                                    }
                                    ?>
                                </div>
                                <div class="product-name">
                                    <?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

                                    <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </div>
                            </div>
                            <div class="table-col-2 product-quantity">
                                <?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . $cart_item['quantity'] . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </div>
                            <div class="table-col-3 product-total">
                                <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </div>
                        </li>
                        <?php
                    }
                }

                do_action( 'woocommerce_review_order_after_cart_contents' );
                ?>
            </ul>

            <div class="order-review-table-footer">
                <a class="back-to-shop" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
                    <?php esc_html_e( 'Continue Shopping', 'woocommerce' ); ?>
                </a>

                <div class="footer-content">
                    <div class="cart-subtotal">
                        <div><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></div>
                        <div><?php wc_cart_totals_subtotal_html(); ?></div>
                    </div>

                    <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                        <div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                            <div><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
                            <div><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
                        </div>
                    <?php endforeach; ?>

                    <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

                    <div class="shipping-area">

                        <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

                        <?php WC()->cart->calculate_totals();
                            wc_cart_totals_shipping_html();

                            do_action( 'woocommerce_review_order_after_shipping' ); ?>
                    </div>

                    <?php endif; ?>

                    <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                        <div class="fee">
                            <div><?php echo esc_html( $fee->name ); ?></div>
                            <div><?php wc_cart_totals_fee_html( $fee ); ?></div>
                        </div>
                    <?php endforeach; ?>

                    <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
                        <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                            <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited ?>
                                <div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                                    <div><?php echo esc_html( $tax->label ); ?></div>
                                    <div><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="tax-total">
                                <div><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></div>
                                <div><?php wc_cart_totals_taxes_total_html(); ?></div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

                    <div class="order-total">
                        <div><?php esc_html_e( 'Total', 'woocommerce' ); ?></div>
                        <div><?php wc_cart_totals_order_total_html(); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private static function order_received( $order_id = 0 ) {
        $order = false;

        // Get the order.
        $order_id  = apply_filters( 'woocommerce_thankyou_order_id', absint( $order_id ) );
        $order_key = apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( wp_unslash( $_GET['key'] ) ) ); // WPCS: input var ok, CSRF ok.

        if ( $order_id > 0 ) {
            $order = wc_get_order( $order_id );
            if ( ! $order || ! hash_equals( $order->get_order_key(), $order_key ) ) {
                $order = false;
            }
        }

        // Empty awaiting payment session.
        unset( WC()->session->order_awaiting_payment );

        // In case order is created from admin, but paid by the actual customer, store the ip address of the payer
        // when they visit the payment confirmation page.
        if ( $order && $order->is_created_via( 'admin' ) ) {
            $order->set_customer_ip_address( WC_Geolocation::get_ip_address() );
            $order->save();
        }

        // Empty current cart.
        wc_empty_cart();

        wc_get_template( 'checkout/thankyou.php', array( 'order' => $order ) );
    }
}





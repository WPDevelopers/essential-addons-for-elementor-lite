<?php

namespace Essential_Addons_Elementor\Template\Woocommerce\Cart;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( class_exists( '\WC_Shortcode_Cart' ) ) {
	class Woo_Cart_Shortcode extends \WC_Shortcode_Cart {

		use Woo_Cart_Helper;

		/**
		 * Output the cart shortcode.
		 *
		 * @param array $atts Shortcode attributes.
		 */
		public static function output( $atts, $settings = [] ) {
			if ( ! apply_filters( 'woocommerce_output_cart_shortcode_content', true ) ) {
				return;
			}

			// Constants.
			wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

			$atts        = shortcode_atts( [], $atts, 'woocommerce_cart' );
			$nonce_value = wc_get_var( $_REQUEST['woocommerce-shipping-calculator-nonce'],
				wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

			// Update Shipping. Nonce check uses new value and old value (woocommerce-cart). @todo remove in 4.0.
			if ( ! empty( $_POST['calc_shipping'] )
			     && ( wp_verify_nonce( $nonce_value, 'woocommerce-shipping-calculator' )
			          || wp_verify_nonce( $nonce_value, 'woocommerce-cart' ) )
			) { // WPCS: input var ok.
				self::calculate_shipping();

				// Also calc totals before we check items so subtotals etc are up to date.
				WC()->cart->calculate_totals();
			}

			// Check cart items are valid.
			do_action( 'woocommerce_check_cart_items' );

			// Calc totals.
			WC()->cart->calculate_totals();
			$auto_update = $settings['eael_woo_cart_auto_cart_update'] === 'yes' ? 'eael-auto-update' : '';
			if ( WC()->cart->is_empty() ) { ?>
				<div class="eael-woo-cart-wrapper eael-woo-cart-empty <?php echo esc_attr( printf( '%s %s', "eael-woo-{$settings['ea_woo_cart_layout']}", $auto_update ) ); ?>">
				<?php wc_get_template( 'cart/cart-empty.php' ); ?>
                </div>
				<?php
			} else {
				$style_two_wrapper_class = '';
				if ( $settings['ea_woo_cart_layout'] === 'style-2' ) {
					if ( $settings['eael_woo_cart_table_components_thumbnail'] === 'yes' ) {
						$style_two_wrapper_class .= ' has-table-left-content';
					}

					if ( in_array( 'yes', [
						$settings['eael_woo_cart_table_components_price'],
						$settings['eael_woo_cart_table_components_qty'],
						$settings['eael_woo_cart_table_components_subtotal'],
						$settings['eael_woo_cart_table_components_remove']
					] ) ) {
						$style_two_wrapper_class .= ' has-table-right-content';
					}
				}
				?>
                <div class="eael-woo-cart-wrapper <?php echo esc_attr( printf( '%s %s %s', "eael-woo-{$settings['ea_woo_cart_layout']}", $auto_update, $style_two_wrapper_class ) ); ?>">
					<?php
					do_action( 'woocommerce_before_cart' );

					switch ( $settings['ea_woo_cart_layout'] ) {
						case 'default':
							self::woo_cart_style_one( $settings );
							break;
						case 'style-2':
							self::woo_cart_style_two( $settings );
							break;
					}

					do_action( 'woocommerce_after_cart' );
					?>
                </div>
				<?php
			}
		}

	}
}
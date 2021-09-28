<?php

namespace Essential_Addons_Elementor\Template\Woocommerce\Cart;

use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

trait Woo_Cart_Helper {

	public static $setting_data = [];

	public static function ea_get_woo_cart_settings() {
		return self::$setting_data;
	}

	public static function ea_set_woo_cart_settings( $setting ) {
		self::$setting_data = $setting;
	}

	/**
	 * Added all actions
	 */
	public function ea_woo_cart_add_actions( $settings ) {
		// set ea cart controller settings
		self::ea_set_woo_cart_settings( $settings );
	}

	public function ea_cart_render() {
		$settings = self::ea_get_woo_cart_settings();
		Woo_Cart_Shortcode::output( [], $settings );
	}

	public static function woo_cart_style_one( $settings ) { ?>
        <form class="woocommerce-cart-form eael-woo-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

            <table class="shop_table cart woocommerce-cart-form__contents eael-woo-cart-table">
                <thead>
                <tr>
					<?php
					if ( ! empty( $settings['table_items'] ) && is_array( $settings['table_items'] ) ) {
						foreach ( $settings['table_items'] as $column_data ) {
							$item_class = "elementor-repeater-item-{$column_data['_id']}";
							?>
                            <th class="<?php echo esc_attr( "product-{$column_data['column_type']} {$item_class}" ); ?>">
								<?php echo esc_html( $column_data['column_heading_title'] ); ?>
                            </th>
							<?php
						}
					}
					?>
                </tr>
                </thead>
                <tbody>
				<?php
				do_action( 'woocommerce_before_cart_contents' );

				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0
					     && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key )
					) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink',
							$_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item,
							$cart_item_key );
						?>
                        <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class',
							'cart_item', $cart_item, $cart_item_key ) ); ?>">
							<?php
							if ( ! empty( $settings['table_items'] ) && is_array( $settings['table_items'] ) ) {
								foreach ( $settings['table_items'] as $column_data ) {
									$item_class = "elementor-repeater-item-{$column_data['_id']}";

									switch ( $column_data['column_type'] ) {
										case 'remove': ?>
                                            <td class="product-remove <?php echo esc_attr( $item_class ); ?>">
												<?php
												echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													'woocommerce_cart_item_remove_link',
													sprintf(
														'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
														esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
														esc_html__( 'Remove this item', 'essential-addons-for-elementor-lite' ),
														esc_attr( $product_id ),
														esc_attr( $_product->get_sku() ),
														Icons_Manager::render_font_icon( $column_data['item_remove_icon'], [ 'aria-hidden' => 'true' ] )
													),
													$cart_item_key
												);
												?>
                                            </td>
											<?php
											break;
										case 'thumbnail': ?>
                                            <td class="product-thumbnail <?php echo esc_attr( $item_class ); ?>">
												<?php
												$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

												if ( ! $product_permalink ) {
													echo $thumbnail; // PHPCS: XSS ok.
												} else {
													printf( '<a href="%s">%s</a>', esc_url( $product_permalink ),
														$thumbnail ); // PHPCS: XSS ok.
												}
												?>
                                            </td>
											<?php
											break;
										case 'name': ?>
                                            <td class="product-name <?php echo esc_attr( $item_class ); ?>">
												<?php
												if ( ! $product_permalink ) {
													echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name',
															$_product->get_name(), $cart_item, $cart_item_key )
													                   . '&nbsp;' );
												} else {
													echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name',
														sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ),
															$_product->get_name() ), $cart_item, $cart_item_key ) );
												}

												do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

												// Meta data.
												echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

												// Backorder notification.
												if ( $_product->backorders_require_notification()
												     && $_product->is_on_backorder( $cart_item['quantity'] )
												) {
													echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification',
														'<p class="backorder_notification">'
														. esc_html__( 'Available on backorder', 'essential-addons-for-elementor-lite' )
														. '</p>', $product_id ) );
												}
												?>
                                            </td>
											<?php
											break;
										case 'price': ?>
                                            <td class="product-price <?php echo esc_attr( $item_class ); ?>">
												<?php
												echo apply_filters( 'woocommerce_cart_item_price',
													WC()->cart->get_product_price( $_product ), $cart_item,
													$cart_item_key ); // PHPCS: XSS ok.
												?>
                                            </td>
											<?php
											break;
										case 'quantity': ?>
                                            <td class="product-quantity <?php echo esc_attr( $item_class ); ?>">
												<?php
												if ( $_product->is_sold_individually() ) {
													$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
												} else {
													$product_quantity = woocommerce_quantity_input(
														[
															'input_name'   => "cart[{$cart_item_key}][qty]",
															'input_value'  => $cart_item['quantity'],
															'max_value'    => $_product->get_max_purchase_quantity(),
															'min_value'    => '0',
															'product_name' => $_product->get_name(),
														],
														$_product,
														false
													);
												}

												echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity,
													$cart_item_key, $cart_item ); // PHPCS: XSS ok.
												?>
                                            </td>
											<?php
											break;
										case 'subtotal': ?>
                                            <td class="product-subtotal <?php echo esc_attr( $item_class ); ?>">
												<?php
												echo apply_filters( 'woocommerce_cart_item_subtotal',
													WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ),
													$cart_item,
													$cart_item_key ); // PHPCS: XSS ok.
												?>
                                            </td>
											<?php
											break;
									}
								}
							}
							?>
                        </tr>
						<?php
					}
				}

				do_action( 'woocommerce_cart_contents' );
				do_action( 'woocommerce_after_cart_contents' ); ?>
                </tbody>
            </table>

			<?php do_action( 'woocommerce_after_cart_table' ); ?>

            <div class="eael-cart-coupon-and-collaterals">
                <div class="eael-cart-coupon-wrapper">
					<?php if ( wc_coupons_enabled() && $settings['eael_woo_cart_components_cart_coupon'] === 'yes' ) {
						$button_text = apply_filters( 'eael_woo_cart_coupon_button_text', $settings['eael_woo_cart_components_cart_coupon_button_text'] );
						$placeholder = apply_filters( 'eael_woo_cart_coupon_placeholder', $settings['eael_woo_cart_components_cart_coupon_placeholder'] );
						?>
                        <div class="coupon">
                            <label for="coupon_code" class="sr-only"><?php esc_html_e( 'Coupon:', 'essential-addons-for-elementor-lite' ); ?></label>
                            <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php echo esc_attr( $placeholder ); ?>"/>
                            <button type="submit" class="button" name="apply_coupon"
                                    value="<?php echo esc_attr( $button_text ); ?>"><?php echo esc_html( $button_text ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
					<?php } ?>
                </div>

				<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

                <div class="cart-collaterals">
                    <div class="eael-cart-update-btn">
						<?php if ( $settings['eael_woo_cart_components_cart_update_button'] === 'yes' ) {
							$update_text = apply_filters( 'eael_woo_cart_update_button_text', $settings['eael_woo_cart_components_cart_update_button_text'] );
							?>
                            <button type="submit" class="button" name="update_cart"
                                    value="<?php echo esc_attr( $update_text ); ?>"><?php echo esc_html( $update_text ); ?></button>
							<?php
						}

						do_action( 'woocommerce_cart_actions' );
						wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' );
						?>
                    </div>

					<?php
					/**
					 * Cart collaterals hook.
					 *
					 * @hooked woocommerce_cross_sell_display
					 * @hooked woocommerce_cart_totals - 10
					 */
					if ( $settings['eael_woo_cart_components_cart_totals'] === 'yes' ) {
						do_action( 'woocommerce_cart_collaterals', $settings );
					}
					?>
                </div>
            </div>
        </form>
		<?php
	}

	public static function eael_woo_cart_totals( $settings ) { ?>
        <div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

			<?php
			do_action( 'woocommerce_before_cart_totals' );

			if ( $settings['eael_woo_cart_components_cart_totals_subtotal'] === 'yes' || $settings['eael_woo_cart_components_cart_totals_coupon'] === 'yes' ||
			     $settings['eael_woo_cart_components_cart_totals_shipping'] === 'yes' ||
			     ( ! empty( WC()->cart->get_fees() ) && $settings['eael_woo_cart_components_cart_totals_fees'] === 'yes' ) ||
			     $settings['eael_woo_cart_components_cart_totals_tax'] === 'yes' || $settings['eael_woo_cart_components_cart_totals_total'] === 'yes' ) {
				?>
                <table class="shop_table shop_table_responsive">

					<?php if ( $settings['eael_woo_cart_components_cart_totals_subtotal'] === 'yes' ) { ?>
                        <tr class="cart-subtotal">
                            <th><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                            <td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
                        </tr>
					<?php } ?>

					<?php
					if ( $settings['eael_woo_cart_components_cart_totals_coupon'] === 'yes' ) {
						foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                            <tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                                <th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
                                <td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
                            </tr>
						<?php
						endforeach;
					}
					?>

					<?php
					if ( $settings['eael_woo_cart_components_cart_totals_shipping'] === 'yes' ) {
						if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

							<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

							<?php wc_cart_totals_shipping_html(); ?>

							<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

						<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

                            <tr class="shipping">
                                <th><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
                                <td data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
                            </tr>

						<?php
						endif;
					} ?>

					<?php
					if ( $settings['eael_woo_cart_components_cart_totals_fees'] === 'yes' ) {
						foreach ( WC()->cart->get_fees() as $fee ) : ?>
                            <tr class="fee">
                                <th><?php echo esc_html( $fee->name ); ?></th>
                                <td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
                            </tr>
						<?php
						endforeach;
					} ?>

					<?php
					if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() && $settings['eael_woo_cart_components_cart_totals_tax'] === 'yes' ) {
						$taxable_address = WC()->customer->get_taxable_address();
						$estimated_text  = '';

						if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
							/* translators: %s location. */
							$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
						}

						if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
							foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
								?>
                                <tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                                    <th><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
                                    <td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
                                </tr>
								<?php
							}
						} else {
							?>
                            <tr class="tax-total">
                                <th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
                                <td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
                            </tr>
							<?php
						}
					}
					?>

					<?php
					do_action( 'woocommerce_cart_totals_before_order_total' );

					if ( $settings['eael_woo_cart_components_cart_totals_total'] === 'yes' ) {
						?>
                        <tr class="order-total">
                            <th><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
                            <td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
                        </tr>
						<?php
					}

					do_action( 'woocommerce_cart_totals_after_order_total' );
					?>

                </table>
				<?php
			}

			if ( $settings['eael_woo_cart_components_cart_totals_checkout_button'] === 'yes' ) { ?>
                <div class="wc-proceed-to-checkout">
					<?php do_action( 'woocommerce_proceed_to_checkout', $settings ); ?>
                </div>
				<?php
			}

			do_action( 'woocommerce_after_cart_totals' ); ?>

        </div>
		<?php
	}

	public static function eael_cart_button_proceed_to_checkout( $settings ) {
		$button_text = apply_filters( 'eael_woo_cart_checkout_button_text', $settings['eael_woo_cart_components_cart_checkout_button_text'] );
		?>
        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward">
			<?php echo esc_html( $button_text ); ?>
        </a>
		<?php
	}

	public static function woo_cart_style_two( $settings ) {
		echo 'this is from woo cart helper style two';
	}

}





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

	public static function woo_cart_style_one( $settings ) {
		do_action( 'woocommerce_before_cart' ); ?>

        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

            <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                <thead>
                <tr>
					<?php
					if ( ! empty( $settings['table_items'] ) && is_array( $settings['table_items'] ) ) {
						foreach ( $settings['table_items'] as $column_data ) {
							?>
                            <th class="product-<?php echo esc_attr( $column_data['column_type'] ); ?>"><?php echo esc_html( $column_data['column_heading_title'] ); ?></th>
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
									switch ( $column_data['column_type'] ) {
										case 'remove': ?>
                                            <td class="product-remove">
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
                                            <td class="product-thumbnail">
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
                                            <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'essential-addons-for-elementor-lite' ); ?>">
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
                                            <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'essential-addons-for-elementor-lite' ); ?>">
												<?php
												echo apply_filters( 'woocommerce_cart_item_price',
													WC()->cart->get_product_price( $_product ), $cart_item,
													$cart_item_key ); // PHPCS: XSS ok.
												?>
                                            </td>
											<?php
											break;
										case 'quantity': ?>
                                            <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'essential-addons-for-elementor-lite' ); ?>">
												<?php
												if ( $_product->is_sold_individually() ) {
													$product_quantity
														= sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />',
														$cart_item_key );
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
                                            <td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'essential-addons-for-elementor-lite' ); ?>">
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

            <div>
                <div>
					<?php if ( wc_coupons_enabled() ) { ?>
                        <div class="coupon">
                            <label for="coupon_code"><?php esc_html_e( 'Coupon:', 'essential-addons-for-elementor-lite' ); ?></label> <input
                                    type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
                                    placeholder="<?php esc_attr_e( 'Coupon code', 'essential-addons-for-elementor-lite' ); ?>"/>
                            <button type="submit" class="button" name="apply_coupon"
                                    value="<?php esc_attr_e( 'Apply coupon',
								        'essential-addons-for-elementor-lite' ); ?>"><?php esc_attr_e( 'Apply coupon',
									'essential-addons-for-elementor-lite' ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
					<?php } ?>

                    <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart',
						'essential-addons-for-elementor-lite' ); ?>"><?php esc_html_e( 'Update cart', 'essential-addons-for-elementor-lite' ); ?></button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                </div>

				<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

                <div class="cart-collaterals">
					<?php
					/**
					 * Cart collaterals hook.
					 *
					 * @hooked woocommerce_cross_sell_display
					 * @hooked woocommerce_cart_totals - 10
					 */
					do_action( 'woocommerce_cart_collaterals' );
					?>
                </div>
            </div>
        </form>

		<?php do_action( 'woocommerce_after_cart' );
	}

	public static function woo_cart_style_two() {
		echo 'this is from woo cart helper style two';
	}

}





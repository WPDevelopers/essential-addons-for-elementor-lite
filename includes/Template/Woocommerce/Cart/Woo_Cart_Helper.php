<?php

namespace Essential_Addons_Elementor\Template\Woocommerce\Cart;
use \Essential_Addons_Elementor\Classes\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

trait Woo_Cart_Helper {

	public static $setting_data = [];

	public static function ea_get_woo_cart_settings() {
		return self::$setting_data;
	}

	public static function ea_set_woo_cart_settings( $settings ) {
		self::$setting_data = $settings;
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
        <form class="woocommerce-cart-form eael-woo-cart-form woocommerce" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>
			
			<div class="eael-cart-clear-btn mt10">
				<?php if ( ! empty( $settings['eael_woo_cart_components_cart_clear_button'] ) && $settings['eael_woo_cart_components_cart_clear_button'] === 'yes' ) {
					$clear_text = apply_filters( 'eael_woo_cart_clear_button_text', $settings['eael_woo_cart_components_cart_clear_button_text'] );
					echo '<a href="' . esc_url( add_query_arg( 'empty_cart', 'yes' ) ) . '" class="button" title="' . esc_attr( $clear_text ) . '">' . esc_html( $clear_text ) . '</a>';
				}
				?>
            </div>

            <div class="eael-woo-cart-table-warp">
                <table class="shop_table cart woocommerce-cart-form__contents eael-woo-cart-table">
                    <thead>
                    <tr>
				        <?php
				        if ( ! empty( $settings['table_items'] ) && is_array( $settings['table_items'] ) ) {
					        foreach ( $settings['table_items'] as $column_data ) {
						        $item_class = "elementor-repeater-item-{$column_data['_id']}";
						        ?>
                                <th class="<?php echo esc_attr( "product-{$column_data['column_type']} {$item_class}" ); ?>">
							        <?php
							        $title = apply_filters( "eael_woo_cart_table_{$column_data['column_type']}_title", $column_data['column_heading_title'] );
							        echo esc_html( $title );
							        ?>
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
													        Helper::get_render_icon( $column_data['item_remove_icon'], [ 'aria-hidden' => 'true' ] )
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
														// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												        echo $thumbnail; // PHPCS: XSS ok.
											        } else {
														// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
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
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity,
												        $cart_item_key, $cart_item ); // PHPCS: XSS ok.
											        ?>
                                                </td>
										        <?php
										        break;
									        case 'subtotal': ?>
                                                <td class="product-subtotal <?php echo esc_attr( $item_class ); ?>">
											        <?php
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
            </div>
			<?php
			do_action( 'woocommerce_after_cart_table' );
			self::woo_cart_collaterals( $settings );
			?>
        </form>
		<?php
	}

	public static function eael_woo_cart_totals( $settings ) {
		if ( ! empty( $settings['ea_woo_cart_layout'] ) ) {
			?>
            <div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">
				<?php
				do_action( 'woocommerce_before_cart_totals' );

				if ( $settings['eael_woo_cart_components_cart_totals_subtotal'] === 'yes' || $settings['eael_woo_cart_components_cart_totals_coupon'] === 'yes' ||
				     $settings['eael_woo_cart_components_cart_totals_shipping'] === 'yes' ||
				     ( ! empty( WC()->cart->get_fees() ) && $settings['eael_woo_cart_components_cart_totals_fees'] === 'yes' ) ||
				     $settings['eael_woo_cart_components_cart_totals_tax'] === 'yes' || $settings['eael_woo_cart_components_cart_totals_total'] === 'yes' ) {
					?>
                    <table class="shop_table shop_table_responsive">

						<?php if ( $settings['eael_woo_cart_components_cart_totals_subtotal'] === 'yes' ) {
							$subtotal_label = apply_filters( 'eael_woo_cart_totals_subtotal_label', esc_html__( 'Subtotal', 'essential-addons-for-elementor-lite' ) );
							?>
                            <tr class="cart-subtotal">
                                <th><?php echo esc_html( $subtotal_label ); ?></th>
                                <td data-title="<?php echo esc_attr( $subtotal_label ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
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

							<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) :
								$shipping_label = apply_filters( 'eael_woo_cart_totals_shipping_label', esc_html__( 'Shipping', 'essential-addons-for-elementor-lite' ) );
								?>

                                <tr class="shipping">
                                    <th><?php echo esc_html( $shipping_label ); ?></th>
                                    <td data-title="<?php echo esc_attr( $shipping_label ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
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
								$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'essential-addons-for-elementor-lite' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
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
							$total_label = apply_filters( 'eael_woo_cart_totals_total_label', esc_html__( 'Total', 'essential-addons-for-elementor-lite' ) );
							?>
                            <tr class="order-total">
                                <th><?php echo esc_html( $total_label ); ?></th>
                                <td data-title="<?php echo esc_attr( $total_label ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
                            </tr>
							<?php
						}

						do_action( 'woocommerce_cart_totals_after_order_total' );
						?>

                    </table>
					<?php
				}

                ?>
                <div class="wc-proceed-to-checkout">
                    <?php do_action( 'woocommerce_proceed_to_checkout', $settings ); ?>
                </div>
                <?php

				do_action( 'woocommerce_after_cart_totals' ); ?>
            </div>
			<?php
		} else {
			woocommerce_cart_totals();
		}
	}

	public function remove_woocommerce_cross_sell_display( $settings ) {
		// Remove default 'woocommerce_cross_sell_display' callback from 'woocommerce_cart_collaterals'
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	}

	public static function eael_cart_button_proceed_to_checkout( $settings ) {
		if ( 'yes' === $settings['eael_woo_cart_hide_checkout_btn'] ) {
			return;
		}
		if ( ! empty( $settings['ea_woo_cart_layout'] ) ) {
			$button_text = apply_filters( 'eael_woo_cart_checkout_button_text', $settings['eael_woo_cart_components_cart_checkout_button_text'] );
			?>
            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward">
				<?php echo esc_html( $button_text ); ?>
            </a>
			<?php
		} else {
			woocommerce_button_proceed_to_checkout();
		}
	}

	public static function woo_cart_style_two( $settings ) { ?>
        <form class="woocommerce-cart-form eael-woo-cart-form woocommerce" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<div class="eael-cart-clear-btn mt10">
				<?php if ( ! empty( $settings['eael_woo_cart_components_cart_clear_button'] ) && $settings['eael_woo_cart_components_cart_clear_button'] === 'yes' ) {
					$clear_text = apply_filters( 'eael_woo_cart_clear_button_text', $settings['eael_woo_cart_components_cart_clear_button_text'] );
					echo '<a href="' . esc_url( add_query_arg( 'empty_cart', 'yes' ) ) . '" class="button" title="' . esc_attr( $clear_text ) . '">' . esc_html( $clear_text ) . '</a>';
				}
				?>
            </div>
				
            <div class="shop_table cart woocommerce-cart-form__contents eael-woo-cart-table">
				<?php
				$has_table_left_components  = $settings['eael_woo_cart_table_components_thumbnail'] === 'yes' ? true : false;
				$has_table_right_components = in_array( 'yes', [
					$settings['eael_woo_cart_table_components_price'],
					$settings['eael_woo_cart_table_components_qty'],
					$settings['eael_woo_cart_table_components_subtotal'],
					$settings['eael_woo_cart_table_components_remove']
				] ) ? true : false;

				if ( $has_table_left_components || $has_table_right_components ) {
					?>
                    <div class="eael-woo-cart-thead">
                        <div class="eael-woo-cart-tr">
							<?php if ( $has_table_left_components ) { ?>
                                <div class="eael-woo-cart-tr-left">
                                    <div class="eael-woo-cart-td product-thumbnail">
										<?php
										$title = apply_filters( "eael_woo_cart_table_thumbnail_title", $settings['eael_woo_cart_table_components_thumbnail_title'] );
										echo esc_html( $title );
										?>
                                    </div>
                                </div>
								<?php
							}

							if ( $has_table_right_components ) { ?>
                                <div class="eael-woo-cart-tr-right">
									<?php if ( $settings['eael_woo_cart_table_components_price'] === 'yes' ) { ?>
                                        <div class="eael-woo-cart-td product-price">
											<?php
											$title = apply_filters( "eael_woo_cart_table_price_title", $settings['eael_woo_cart_table_components_price_title'] );
											echo esc_html( $title );
											?>
                                        </div>
										<?php
									}

									if ( $settings['eael_woo_cart_table_components_qty'] === 'yes' ) { ?>
                                        <div class="eael-woo-cart-td product-quantity">
											<?php
											$title = apply_filters( "eael_woo_cart_table_quantity_title", $settings['eael_woo_cart_table_components_qty_title'] );
											echo esc_html( $title );
											?>
                                        </div>
										<?php
									}

									if ( $settings['eael_woo_cart_table_components_subtotal'] === 'yes' ) { ?>
                                        <div class="eael-woo-cart-td product-subtotal">
											<?php
											$title = apply_filters( "eael_woo_cart_table_subtotal_title", $settings['eael_woo_cart_table_components_subtotal_title'] );
											echo esc_html( $title );
											?>
                                        </div>
										<?php
									}

									if ( $settings['eael_woo_cart_table_components_remove'] === 'yes' ) { ?>
                                        <div class="eael-woo-cart-td product-remove"></div>
									<?php } ?>
                                </div>
							<?php } ?>
                        </div>
                    </div>
                    <div class="eael-woo-cart-tbody">
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
                                <div class="eael-woo-cart-tr woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class',
									'cart_item', $cart_item, $cart_item_key ) ); ?>">
									<?php if ( $has_table_left_components ) { ?>
                                        <div class="eael-woo-cart-tr-left">
                                            <div class="eael-woo-cart-td product-thumbnail">
												<?php
												$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

												if ( ! $product_permalink ) {
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													echo $thumbnail; // PHPCS: XSS ok.
												} else {
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
												}

												if ( $settings['eael_woo_cart_table_components_remove'] === 'yes' ) { ?>
                                                    <div class="eael-woo-cart-product-remove">
														<?php
														echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
															'woocommerce_cart_item_remove_link',
															sprintf(
																'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
																esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
																esc_html__( 'Remove this item', 'essential-addons-for-elementor-lite' ),
																esc_attr( $product_id ),
																esc_attr( $_product->get_sku() ),
																Helper::get_render_icon( $settings['eael_woo_cart_table_components_remove_icon'], [ 'aria-hidden' => 'true' ] )
															),
															$cart_item_key
														);
														?>
                                                    </div>
												<?php }
												?>
                                            </div>
											<?php if ( $settings['eael_woo_cart_table_components_name'] === 'yes' ) { ?>
                                                <div class="eael-woo-cart-td product-name">
													<?php
													if ( ! $product_permalink ) {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
													} else {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ),
															$_product->get_name() ), $cart_item, $cart_item_key ) );
													}

													do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

													// Product SKU
													if ( $settings['eael_woo_cart_table_components_sku'] === 'yes' && ! empty( $_product->get_sku() ) ) {
														printf( '<p class="eael-woo-cart-sku">#%s</p>', esc_html( $_product->get_sku() ) );
													}

													// Meta data. 
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

													// Backorder notification.
													if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
														echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification',
															'<p class="backorder_notification">'
															. esc_html__( 'Available on backorder', 'essential-addons-for-elementor-lite' )
															. '</p>', $product_id ) );
													}
													?>
                                                </div>
											<?php } ?>
                                        </div>
										<?php
									}

									if ( $has_table_right_components ) { ?>
                                        <div class="eael-woo-cart-tr-right">
											<?php if ( $settings['eael_woo_cart_table_components_price'] === 'yes' ) { ?>
                                                <div class="eael-woo-cart-td product-price">
													<?php
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													echo apply_filters( 'woocommerce_cart_item_price',
														WC()->cart->get_product_price( $_product ), $cart_item,
														$cart_item_key ); // PHPCS: XSS ok.
													?>
                                                </div>
												<?php
											}

											if ( $settings['eael_woo_cart_table_components_qty'] === 'yes' ) { ?>
                                                <div class="eael-woo-cart-td product-quantity">
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
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity,
														$cart_item_key, $cart_item ); // PHPCS: XSS ok.
													?>
                                                </div>
												<?php
											}

											if ( $settings['eael_woo_cart_table_components_subtotal'] === 'yes' ) { ?>
                                                <div class="eael-woo-cart-td product-subtotal" data-title="<?php echo esc_html( $title ); ?>">
													<?php
													// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													echo apply_filters( 'woocommerce_cart_item_subtotal',
														WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ),
														$cart_item,
														$cart_item_key ); // PHPCS: XSS ok.
													?>
                                                </div>
												<?php
											}

											if ( $settings['eael_woo_cart_table_components_remove'] === 'yes' ) { ?>
                                                <div class="eael-woo-cart-td product-remove">
													<?php
													echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														'woocommerce_cart_item_remove_link',
														sprintf(
															'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
															esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
															esc_html__( 'Remove this item', 'essential-addons-for-elementor-lite' ),
															esc_attr( $product_id ),
															esc_attr( $_product->get_sku() ),
															Helper::get_render_icon( $settings['eael_woo_cart_table_components_remove_icon'], [ 'aria-hidden' => 'true' ] )
														),
														$cart_item_key
													);
													?>
                                                </div>
											<?php } ?>
                                        </div>
									<?php } ?>
                                </div>
								<?php
							}
						}

						do_action( 'woocommerce_cart_contents' );
						do_action( 'woocommerce_after_cart_contents' ); ?>
                    </div>
				<?php } ?>
            </div>

			<?php
			do_action( 'woocommerce_after_cart_table' );
			self::woo_cart_collaterals( $settings );
			?>
        </form>
		<?php
	}

	public static function woo_cart_collaterals( $settings ) { ?>
        <div class="eael-cart-coupon-and-collaterals">
            <div class="eael-cart-coupon-wrapper">
	            <?php if ( wc_coupons_enabled() && $settings['eael_woo_cart_components_cart_coupon'] === 'yes' ) {
		            $button_text  = apply_filters( 'eael_woo_cart_coupon_button_text', $settings['eael_woo_cart_components_cart_coupon_button_text'] );
		            $placeholder  = apply_filters( 'eael_woo_cart_coupon_placeholder', $settings['eael_woo_cart_components_cart_coupon_placeholder'] );
		            $coupon_label = apply_filters( 'eael_woo_cart_coupon_label_text', esc_html__( 'Coupon:', 'essential-addons-for-elementor-lite' ) );
		            ?>
                    <div class="coupon">
                        <label for="coupon_code" class="sr-only"><?php echo esc_html( $coupon_label ); ?></label>
                        <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php echo esc_attr( $placeholder ); ?>"/>
                        <button type="submit" class="button" name="apply_coupon"
                                value="<?php echo esc_attr( $button_text ); ?>"><?php echo esc_html( $button_text ); ?></button>
			            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                    </div>
		            <?php
	            } 

				if ( $settings['eael_woo_cart_components_continue_shopping'] === 'yes' ) {
					$continue_shopping_text = apply_filters( 'eael_woo_cart_continue_shopping_text', $settings['eael_woo_cart_components_continue_shopping_text'] );
					$icon = Helper::get_render_icon( $settings['eael_woo_cart_components_continue_shopping_icon'], [ 'aria-hidden' => 'true' ] );
					printf( '<a class="eael-woo-cart-back-to-shop" href="%s">%s %s</a>',
						esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
						wp_kses( $icon, Helper::eael_allowed_icon_tags() ),
						esc_html( $continue_shopping_text )
					);
				}
				?>
            </div>

			<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

            <div class="cart-collaterals">
                <div class="eael-cart-update-btn">
					<?php if ( $settings['eael_woo_cart_components_cart_update_button'] === 'yes' ) {
						$update_text = apply_filters( 'eael_woo_cart_update_button_text', $settings['eael_woo_cart_components_cart_update_button_text'] );
						?>
                        <button type="submit" class="button" name="update_cart" value="<?php echo esc_attr( $update_text ); ?>"><?php echo esc_html( $update_text ); ?></button>
						<?php
					}
					else if ( ! $settings['eael_woo_cart_components_cart_update_button'] && $settings['eael_woo_cart_auto_cart_update'] === 'yes' ){
						echo '<button type="submit" class="button" name="update_cart" style="display:none;"></button>';
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

				do_action( 'eael_woocommerce_before_cart_collaterals', $settings );

				if ( $settings['eael_woo_cart_components_cart_totals'] === 'yes' ) {
					do_action( 'woocommerce_cart_collaterals', $settings );
				} else {
					?>
                    <div class="cart_totals">
                        <div class="wc-proceed-to-checkout">
							<?php do_action( 'woocommerce_proceed_to_checkout', $settings ); ?>
                        </div>
                    </div>
					<?php
				}
				?>
            </div>
        </div>
		<?php
	}

	public function wc_empty_cart_message( $message ) {
		$settings   = self::ea_get_woo_cart_settings();
		$empty_text = $settings['eael_woo_cart_components_empty_cart_text'];

		return empty( $empty_text ) ? $message : esc_html( $empty_text );
	}
}





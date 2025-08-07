<?php

namespace Essential_Addons_Elementor\Template\Content;
use Essential_Addons_Elementor\Classes\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

trait Woo_Product_List {
	public static function render_template_( $args, $settings ) {
		ob_start();

		// Check if args are already WC_Product_Query compatible or need conversion
		if ( isset( $args['post_type'] ) && $args['post_type'] === 'product' ) {
			// Convert WP_Query args to WC_Product_Query args for backward compatibility
			$wc_args = self::convert_wp_query_args_to_wc_product_query( $args, $settings );
		} else {
			// Assume args are already WC_Product_Query compatible
			$wc_args = $args;
		}

		// Use WC_Product_Query for better performance
		$wc_query = new \WC_Product_Query( $wc_args );
		$products = $wc_query->get_products();

		// Handle WC_Product_Query results
		if ( is_object( $products ) && isset( $products->products ) ) {
			$product_objects = $products->products;
		} else {
			$product_objects = $products;
		}

		if ( ! empty( $product_objects ) ) {
			foreach ( $product_objects as $product_obj ) {
				global $post;
				$post = get_post( $product_obj->get_id() );
				setup_postdata( $post );
				$product = $product_obj;
				if ( $settings['eael_woo_product_list_style_preset'] == 'eael-product-simple' || $settings['eael_woo_product_list_style_preset'] == 'eael-product-reveal' ) { ?>
                    <li class="product">
                        <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
							<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' )); ?>
                            <h2 class="woocommerce-loop-product__title"> <?php echo esc_html( $product->get_title()); ?> </h2>
							<?php

							if ( 'yes' === $settings['eael_woo_product_list_rating'] ) {
								$avg_rating = $product->get_average_rating();
								if( $avg_rating > 0 ){
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
								} else {
									// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									echo Helper::eael_rating_markup( $product->get_average_rating(), $product->get_rating_count() );
								}
							}

							if ( ! $product->managing_stock() && ! $product->is_in_stock() ) {
								printf( '<span class="outofstock-badge">%s <br/> %s</span>', esc_html__( 'Stock', 'essential-addons-for-elementor-lite' ), esc_html__( 'Out', 'essential-addons-for-elementor-lite' ) );
							} elseif ( $product->is_on_sale() ) {
								printf( '<span class="onsale">%s</span>', esc_html__( 'Sale!', 'essential-addons-for-elementor-lite' ) );
							}
							?>
                            <span class="price"><?php echo wp_kses_post( $product->get_price_html()); ?></span>
                        </a>
						<?php
						woocommerce_template_loop_add_to_cart();
						if ( isset( $settings['show_compare']) && 'yes' === $settings['show_compare'] ) {
							self::print_compare_button( $product->get_id() );
						}
						?>
                    </li>
					<?php
				} else if ( $settings['eael_woo_product_list_style_preset'] == 'eael-product-overlay' ) {
				    ?>
					<li class="product">
                        <div class="overlay">
                            <?php 
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo $product->get_image( 'woocommerce_thumbnail' ); ?>
                            <div class="button-wrap clearfix">
                                <a href="<?php echo esc_url( $product->get_permalink()); ?>" class="product-link"><span class="fas fa-link"></span></a>
                                <?php
                                woocommerce_template_loop_add_to_cart();
                                if ( isset( $settings['show_compare']) && 'yes' === $settings['show_compare'] ) {
	                                self::print_compare_button( $product->get_id(), 'icon' );
                                }
                                ?>
					        </div>
                        </div>
                        <h2 class="woocommerce-loop-product__title"><?php echo esc_html( $product->get_title()); ?></h2>
                        <?php
                        if ( 'yes' === $settings['eael_woo_product_list_rating'] ) {
							$avg_rating = $product->get_average_rating();
							if( $avg_rating > 0 ){
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
							} else {
								// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								echo Helper::eael_rating_markup( $product->get_average_rating(), $product->get_rating_count() );
							}
						}
                        if ($product->is_on_sale()){
                            printf( '<span class="onsale">%s</span>', esc_html__( 'Sale!', 'essential-addons-for-elementor-lite' ));
                        }
                        ?>
                        <span class="price"> <?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						echo $product->get_price_html(); ?> </span>
                    </li>
                    <?php
				} else {
					if ( isset( $settings['show_compare']) && 'yes' === $settings['show_compare'] ) {
						add_action( 'woocommerce_after_shop_loop_item', function (){
							global $product;
							if (!$product) return;
							self::print_compare_button( $product->get_id() );
						});
					}

					wc_get_template_part( 'content', 'product' );
				}
			}
			wp_reset_postdata();
		} else {
			printf( '<p class="no-posts-found">%s</p>', esc_html__( 'No products found!', 'essential-addons-for-elementor-lite' ) );
		}

		return ob_get_clean();
	}

	/**
	 * Convert WP_Query arguments to WC_Product_Query arguments
	 * @param array $wp_args Original WP_Query arguments
	 * @param array $settings Widget settings
	 * @return array WC_Product_Query compatible arguments
	 */
	private static function convert_wp_query_args_to_wc_product_query($wp_args, $settings = []) {
		$wc_args = [
			'paginate' => true,
			'return' => 'objects',
			'visibility' => 'visible'
		];

		// Parameter mapping
		$arg_mapping = [
			'posts_per_page' => 'limit',
			'post_status' => 'status',
			'post__in' => 'include',
			'post__not_in' => 'exclude',
			'author__in' => 'author',
			'paged' => 'page',
		];

		foreach ($arg_mapping as $wp_key => $wc_key) {
			if (isset($wp_args[$wp_key])) {
				$wc_args[$wc_key] = $wp_args[$wp_key];
			}
		}

		// Handle other parameters
		$direct_copy = ['orderby', 'order', 'offset'];
		foreach ($direct_copy as $key) {
			if (isset($wp_args[$key])) {
				$wc_args[$key] = $wp_args[$key];
			}
		}

		// Handle search
		if (isset($wp_args['s'])) {
			$wc_args['search'] = $wp_args['s'];
		}

		// Preserve complex queries
		if (isset($wp_args['meta_query'])) {
			$wc_args['meta_query'] = $wp_args['meta_query'];
		}
		if (isset($wp_args['tax_query'])) {
			$wc_args['tax_query'] = $wp_args['tax_query'];
		}

		// Handle meta_key/meta_value
		if (isset($wp_args['meta_key'])) {
			if (!isset($wc_args['meta_query'])) {
				$wc_args['meta_query'] = ['relation' => 'AND'];
			}
			$meta_query = ['key' => $wp_args['meta_key']];
			if (isset($wp_args['meta_value'])) {
				$meta_query['value'] = $wp_args['meta_value'];
			}
			if (isset($wp_args['meta_compare'])) {
				$meta_query['compare'] = $wp_args['meta_compare'];
			}
			$wc_args['meta_query'][] = $meta_query;
		}

		// Apply settings-based parameters
		if (!empty($settings['eael_product_list_products_status'])) {
			$wc_args['status'] = array_intersect(
				(array) $settings['eael_product_list_products_status'],
				['publish', 'draft', 'pending', 'future']
			);
		}

		return $wc_args;
	}
}

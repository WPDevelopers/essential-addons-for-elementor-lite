<?php

namespace Essential_Addons_Elementor\Template\Content;
use Essential_Addons_Elementor\Classes\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

trait Product_Grid {
	public static function render_template_( $args, $settings ) {
		$query = new \WP_Query( $args );
		ob_start();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$product = wc_get_product( get_the_ID() );

				// Get secondary image data for hover effect
				$show_secondary_image = isset( $settings['eael_product_grid_show_secondary_image'] ) && 'yes' === $settings['eael_product_grid_show_secondary_image'];
				$image_sources = [
				    'src' => '',
				    'src_hover' => ''
				];
				if( $show_secondary_image ){
				    $image_sources = Helper::eael_get_woo_product_gallery_image_srcs( $product, 'woocommerce_thumbnail' );
				}

				if ( $settings['eael_product_grid_style_preset'] == 'eael-product-simple' || $settings['eael_product_grid_style_preset'] == 'eael-product-reveal' ) { ?>
                    <li class="product">
                        <div class="eael-product-wrap" data-src="<?php echo esc_url( $image_sources['src'] ); ?>" data-src-hover="<?php echo esc_url( $image_sources['src_hover'] ); ?>">
                        <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
							<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' )); ?>
                            <h2 class="woocommerce-loop-product__title"> <?php echo esc_html( $product->get_title()); ?> </h2>
							<?php
							if ( 'yes' === $settings['eael_product_grid_rating'] ) {
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
                        </div>
                    </li>
					<?php
				} else if ( $settings['eael_product_grid_style_preset'] == 'eael-product-overlay' ) {
				    ?>
					<li class="product">
                        <div class="eael-product-wrap" data-src="<?php echo esc_url( $image_sources['src'] ); ?>" data-src-hover="<?php echo esc_url( $image_sources['src_hover'] ); ?>">
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
                        if ( 'yes' === $settings['eael_product_grid_rating'] ) {
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
                        </div>
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
		} else {
			printf( '<p class="no-posts-found">%</p>', esc_html__( 'No products found!', 'essential-addons-for-elementor-lite' ) );

		}

		wp_reset_postdata();
		?>
		<?php
		return ob_get_clean();
	}
}

<?php

namespace Essential_Addons_Elementor\Template\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

trait Woo_Product_List {
	public static function render_template_( $args, $settings ) {
		$query = new \WP_Query( $args );
		ob_start();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$product = wc_get_product( get_the_ID() );
				if ( $settings['eael_woo_product_list_style_preset'] == 'eael-product-simple' || $settings['eael_woo_product_list_style_preset'] == 'eael-product-reveal' ) { ?>
                    <li class="product">
                        <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
							<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' )); ?>
                            <h2 class="woocommerce-loop-product__title"> <?php echo esc_html( $product->get_title()); ?> </h2>
							<?php
							if ( $settings['eael_woo_product_list_rating'] == 'yes' ) {
								echo wp_kses_post( wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ));
							}
							if ( ! $product->managing_stock() && ! $product->is_in_stock() ) {
								printf( '<span class="outofstock-badge">%s</span>', __( 'Stock <br/> Out', 'essential-addons-for-elementor-lite' ) );
							} elseif ( $product->is_on_sale() ) {
								printf( '<span class="onsale">%s</span>', __( 'Sale!', 'essential-addons-for-elementor-lite' ) );
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
                            <?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
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
                        if ($settings['eael_woo_product_list_rating'] === 'yes') {
                            echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() );
                        }
                        if ($product->is_on_sale()){
                            printf( '<span class="onsale">%s</span>', __( 'Sale!', 'essential-addons-for-elementor-lite' ));
                        }
                        ?>
                        <span class="price"> <?php echo $product->get_price_html(); ?> </span>
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
			printf( '<p class="no-posts-found">%</p>', __( 'No products found!', 'essential-addons-for-elementor-lite' ) );

		}

		wp_reset_postdata();
		?>
		<?php
		return ob_get_clean();
	}
}

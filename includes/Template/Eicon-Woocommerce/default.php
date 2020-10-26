<?php
/**
 * Template Name: Default
 */

use Essential_Addons_Elementor\Elements\Product_Grid;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$product = wc_get_product( get_the_ID() );
if ( ! $product ) {
	error_log( '$product not found in ' . __FILE__ );
	return;
}
$should_print_compare_btn = isset( $settings['show_compare'] ) && 'yes' === $settings['show_compare'];

if ( $settings['eael_product_grid_style_preset'] == 'eael-product-simple' || $settings['eael_product_grid_style_preset'] == 'eael-product-reveal' ) { ?>
    <li class="product">
        <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
			<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
            <h2 class="woocommerce-loop-product__title"> <?php echo esc_html( $product->get_title() ); ?> </h2>
			<?php
			if ( $settings['eael_product_grid_rating'] == 'yes' ) {
				echo wp_kses_post( wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) );
			}
			if ( ! $product->managing_stock() && ! $product->is_in_stock() ) {
				printf( '<span class="outofstock-badge">%s</span>', __( 'Stock <br/> Out', 'essential-addons-for-elementor-lite' ) );
			} elseif ( $product->is_on_sale() ) {
				printf( '<span class="onsale">%s</span>', __( 'Sale!', 'essential-addons-for-elementor-lite' ) );
			}
			?>
            <span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
        </a>
		<?php
		woocommerce_template_loop_add_to_cart();
		if ( $should_print_compare_btn ) {
			Product_Grid::print_compare_button( $product->get_id() );
		}
		?>
    </li>
	<?php
} else if ( $settings['eael_product_grid_style_preset'] == 'eael-product-overlay' ) {
	?>
    <li class="product">
        <div class="overlay">
			<?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
            <div class="button-wrap clearfix">
                <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="product-link"><span class="fas fa-link"></span></a>';
				<?php
				woocommerce_template_loop_add_to_cart();
				if ( $should_print_compare_btn ) {
					Product_Grid::print_compare_button( $product->get_id(), 'icon' );
				}
				?>
            </div>
        </div>
        <h2 class="woocommerce-loop-product__title"><?php echo esc_html( $product->get_title() ); ?></h2>
		<?php
		if ( $settings['eael_product_grid_rating'] === 'yes' ) {
			echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() );
		}
		if ( $product->is_on_sale() ) {
			printf( '<span class="onsale">%s</span>', __( 'Sale!', 'essential-addons-for-elementor-lite' ) );
		}
		?>
        <span class="price"> <?php echo $product->get_price_html(); ?> </span>
    </li>
	<?php
} else {
	if ( $should_print_compare_btn ) {
		add_action( 'woocommerce_after_shop_loop_item', [
			'\Essential_Addons_Elementor\Elements\Product_Grid',
			'print_compare_button',
		] );
	}
	wc_get_template_part( 'content', 'product' );

	if ( $should_print_compare_btn ) {
		remove_action( 'woocommerce_after_shop_loop_item', [
			'\Essential_Addons_Elementor\Elements\Product_Grid',
			'print_compare_button',
		] );
	}
}

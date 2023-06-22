<?php
/**
 * Template Name: Style 1
 *
 * @var $cs_product
 * @var $image_size
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="eael-cs-single-product">
    <div class="eael-cs-product-image">
		<?php echo $cs_product->get_image( $image_size ); ?>
    </div>
    <div class="eael-cs-product-info">
        <div class="eael-cs-product-title-price">
            <div class="eael-cs-product-title">
				<?php echo esc_html( $cs_product->get_title() ); ?>
            </div>
            <div class="eael-cs-product-price">
				<?php echo $cs_product->get_price_html(); ?>
            </div>
        </div>
        <div class="eael-cs-product-buttons">
            <a href="<?php echo esc_url( $cs_product->get_permalink() ); ?>"><i
                        class="fas fa-eye"></i> <?php esc_html_e( 'View Product', 'essential-addons-for-elementor-lite' ); ?></a>
			<?php if ( $cs_product->is_purchasable() ) { ?>
                <a href="<?php echo esc_url( $cs_product->add_to_cart_url() ); ?>" class="add_to_cart_button ajax_add_to_cart"
                   data-product_id="<?php echo esc_html( $cs_product->get_ID() ); ?>" data-quantity="1"><i
                            class="fas fa-shopping-cart"></i><?php esc_html_e( 'Add to Cart', 'essential-addons-for-elementor-lite' ); ?></a>
			<?php } ?>
        </div>
    </div>
</div>

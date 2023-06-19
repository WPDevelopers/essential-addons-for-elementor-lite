<?php
/**
 * Template Name: Style 1
 *
 * @var $cs_product
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="eael-cs-single-product">
    <div class="eael-cs-product-image">
		<?php echo $cs_product->get_image( 'medium' ); ?>
    </div>
    <div class="eael-cs-product-info">
        <div class="eael-cs-product-title-price">
            <div class="eael-cs-product-title">
				<?php echo esc_html( $cs_product->get_title() ); ?>
            </div>
            <div class="eael-cs-product-price">
				<?php echo esc_html( $cs_product->get_price() ); ?>
            </div>
        </div>
        <div class="eael-cs-product-buttons">
            <button><i class="far fa-eye"></i> <?php esc_html_e( 'View Product', 'essential-addons-for-elementor-lite' ); ?></button>
            <button><i class="fas fa-shopping-cart"></i><?php esc_html_e( 'Add to Cart', 'essential-addons-for-elementor-lite' ); ?></button>
        </div>
    </div>
</div>

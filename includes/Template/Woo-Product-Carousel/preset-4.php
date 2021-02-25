<?php

use \Essential_Addons_Elementor\Classes\Helper;

/**
 * Template Name: Preset 4
 */

use Essential_Addons_Elementor\Elements\Woo_Product_carousel;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

$product = wc_get_product( get_the_ID() );
if ( ! $product ) {
    error_log( '$product not found in ' . __FILE__ );
    return;
}
// Improvement
$sale_badge_align = isset( $settings['eael_product_sale_badge_alignment'] ) ? $settings['eael_product_sale_badge_alignment'] : '';
$sale_badge_preset = isset($settings['eael_product_sale_badge_preset']) ? $settings['eael_product_sale_badge_preset'] : '';
// should print vars
$should_print_rating = isset( $settings['eael_product_carousel_rating'] ) && 'yes' === $settings['eael_product_carousel_rating'];
$should_print_quick_view = isset( $settings['eael_product_carousel_quick_view'] ) && 'yes' === $settings['eael_product_carousel_quick_view'];
$should_print_price = isset( $settings['eael_product_carousel_price'] ) && 'yes' === $settings['eael_product_carousel_price'];
$should_print_excerpt = isset( $settings['eael_product_carousel_excerpt'] ) && ('yes' === $settings['eael_product_carousel_excerpt'] && has_excerpt());
$widget_id = isset($settings['eael_widget_id']) ? $settings['eael_widget_id'] : null;


if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {
    ?>
    <li <?php post_class( ['product', 'swiper-slide'] ); ?>>
        <div class="eael-product-carousel">
            <div class="product-image-wrap">
                <div class="image-wrap">
                    <?php
                    echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'.__('Stock ', 'essential-addons-for-elementor-lite'). '<br />' . __('Out', 'essential-addons-for-elementor-lite').'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') );
                    echo $product->get_image($settings['eael_product_carousel_image_size_size'], ['loading' => 'eager']);
                    ?>
                </div>
                <div class="image-hover-wrap">
                    <ul class="icons-wrap box-style">
                            <li class="add-to-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
                            <?php if( $should_print_quick_view ){?>
                                <li class="eael-product-quick-view">
                                    <a href="#eaproduct<?php echo $widget_id.$product->get_id(); ?>"
                                       class="open-popup-link">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="view-details" title="Details"><?php echo '<a href="' . $product->get_permalink() . '"><i class="fas fa-link"></i></a>'; ?></li>
                        </ul>
                    <?php
                    if( $should_print_quick_view ){
                        Helper::eael_product_quick_view( $product, $settings, $widget_id );
                    }
                    ?>
                </div>
            </div>
            <div class="product-details-wrap">
                <div class="product-details">
                    <div class="eael-product-title"><h2><?php echo $product->get_title(); ?></h2></div>
                    <?php if ($should_print_rating) {
	                    echo wc_get_rating_html
	                    ($product->get_average_rating(), $product->get_rating_count());
                    } ?>
	                <?php if($should_print_price ){
		                echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
	                }?>
                    <div class="test">asdkhajhsjdsh</div>
                </div>
            </div>
        </div>
    </li>
    <?php
}
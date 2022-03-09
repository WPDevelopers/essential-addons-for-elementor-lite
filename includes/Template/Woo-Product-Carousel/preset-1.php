<?php

use \Essential_Addons_Elementor\Classes\Helper;

/**
 * Template Name: Preset 1
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
$sale_text = !empty($settings['eael_product_carousel_sale_text']) ? $settings['eael_product_carousel_sale_text'] : 'Sale!';
$stockout_text = !empty($settings['eael_product_carousel_stockout_text']) ? $settings['eael_product_carousel_stockout_text'] : 'Stock Out';

// should print vars
$should_print_rating = isset( $settings['eael_product_carousel_rating'] ) && 'yes' === $settings['eael_product_carousel_rating'];
$should_print_quick_view = isset( $settings['eael_product_carousel_quick_view'] ) && 'yes' === $settings['eael_product_carousel_quick_view'];
$should_print_image_clickable = isset( $settings['eael_product_carousel_image_clickable'] ) && 'yes' === $settings['eael_product_carousel_image_clickable'];
$should_print_price = isset( $settings['eael_product_carousel_price'] ) && 'yes' === $settings['eael_product_carousel_price'];
$should_print_excerpt = isset( $settings['eael_product_carousel_excerpt'] ) && ('yes' === $settings['eael_product_carousel_excerpt'] && has_excerpt());
$widget_id = isset($settings['eael_widget_id']) ? $settings['eael_widget_id'] : null;
$quick_view_setting = [
        'widget_id' => $widget_id,
        'product_id' => $product->get_id(),
        'page_id' => $settings['eael_page_id'],
];

if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {
    ?>
    <li <?php post_class( ['product', 'swiper-slide'] ); ?>>
        <div class="eael-product-carousel">
            <div class="product-image-wrap">
                <div class="image-wrap">
                    <?php
                    echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'. $stockout_text .'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . $sale_text . '</span>' : '') );
                    if( $should_print_image_clickable ) {
	                    echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
                    }
                    echo $product->get_image($settings['eael_product_carousel_image_size_size'], ['loading' => 'eager']);

                    if( $should_print_image_clickable ) {
	                    echo '</a>';
                    }
                    ?>
                </div>
                <div class="image-hover-wrap">
                    <ul class="icons-wrap box-style">
                            <li class="add-to-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
                            <?php if( $should_print_quick_view ){?>
                                <li class="eael-product-quick-view">
                                    <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
                                       class="open-popup-link">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="view-details" title="Details"><?php echo '<a href="' . $product->get_permalink() . '"><i class="fas fa-link"></i></a>'; ?></li>
                        </ul>
                    <?php
                    ?>
                </div>
            </div>
            <div class="product-details-wrap">
                <div class="product-details">
                    <?php
                    if ( $settings['eael_product_carousel_show_title'] ) {
                        echo '<div class="eael-product-title">';
	                    echo '<' . Helper::eael_validate_html_tag( $settings['eael_product_carousel_title_tag'] ) . '>';
	                    if ( empty( $settings['eael_product_carousel_title_length'] ) ) {
		                    echo Helper::eael_wp_kses( $product->get_title() );
	                    } else {
		                    echo implode( " ", array_slice( explode( " ", $product->get_title() ), 0, $settings['eael_product_carousel_title_length'] ) );
	                    }
	                    echo '</' . Helper::eael_validate_html_tag( $settings['eael_product_carousel_title_tag'] ) . '>';
	                    echo '</div>';
                    }
                    ?>

                    <?php if ($should_print_rating) {
	                    echo wc_get_rating_html
	                    ($product->get_average_rating(), $product->get_rating_count());
                    }
                    if ( $should_print_excerpt ) {
	                    echo '<div class="eael-product-excerpt">';
	                    echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt()), $settings['eael_product_carousel_excerpt_length'],
                                $settings['eael_product_carousel_excerpt_expanison_indicator']) . '</p>';
	                    echo '</div>';
                    }
                    ?>

                </div>
                <?php if($should_print_price ){
                    echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                }?>
            </div>
        </div>
    </li>
    <?php
}

<?php
/**
 * Template Name: Default
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

$product = wc_get_product(get_the_ID());

if ($settings['eael_product_grid_style_preset'] == 'eael-product-simple' || $settings['eael_product_grid_style_preset'] == 'eael-product-reveal') {
    echo '<li class="product">
        <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
            ' . $product->get_image('woocommerce_thumbnail') . '
            <h2 class="woocommerce-loop-product__title">' . $product->get_title() . '</h2>
            ' . ($settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html($product->get_average_rating(), $product->get_rating_count())) . '
            '.( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="outofstock-badge">'.__('Stock ', 'essential-addons-for-elementor-lite'). '<br />' . __('Out', 'essential-addons-for-elementor-lite').'</span>' : ($product->is_on_sale() ? '<span class="onsale">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') ).'
            <span class="price">' . $product->get_price_html() . '</span>
        </a>';
        woocommerce_template_loop_add_to_cart();
    echo '</li>';
} else if ($settings['eael_product_grid_style_preset'] == 'eael-product-overlay') {
    echo '<li class="product">
        <div class="overlay">
            ' . $product->get_image('woocommerce_thumbnail') . '
            <div class="button-wrap clearfix">
                <a href="' . $product->get_permalink() . '" class="product-link"><span class="fas fa-link"></span></a>';
                woocommerce_template_loop_add_to_cart();
            echo '</div>
        </div>
        <h2 class="woocommerce-loop-product__title">' . $product->get_title() . '</h2>
        ' . ($settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html($product->get_average_rating(), $product->get_rating_count())) . '
        ' . ($product->is_on_sale() ? '<span class="onsale">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') . '
        <span class="price">' . $product->get_price_html() . '</span>
    </li>';
} else {
    wc_get_template_part('content', 'product');
}

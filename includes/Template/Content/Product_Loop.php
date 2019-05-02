<?php

namespace Essential_Addons_Elementor\Template\Content;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Product_Loop
{
    public function template_product_loop($args, $post_object)
    {
        $GLOBALS['product'] = $product = wc_get_product($post_object->ID);

        if ($args['eael_product_grid_style_preset'] == 'eael-product-simple' || $args['eael_product_grid_style_preset'] == 'eael-product-reveal') {
            echo '<li class="product">
                <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                    ' . $product->get_image('woocommerce_thumbnail') . '
                    <h2 class="woocommerce-loop-product__title">' . $product->get_title() . '</h2>
                    ' . ($args['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html($product->get_average_rating(), $product->get_rating_count())) . '
                    ' . ($product->is_on_sale() ? '<span class="onsale">' . __('Sale!', 'essential-addons-elementor') . '</span>' : '') . '
                    <span class="price">' . $product->get_price_html() . '</span>
                </a>';

                woocommerce_template_loop_add_to_cart();
            echo '</li>';
        } else if ($args['eael_product_grid_style_preset'] == 'eael-product-overlay') {
            echo '<li class="product">
                <div class="overlay">
                    ' . $product->get_image('woocommerce_thumbnail') . '
                    <div class="button-wrap clearfix">
                        <a href="' . $product->get_permalink() . '" class="product-link"><span class="fa fa-link"></span></a>';
                        woocommerce_template_loop_add_to_cart();
                    echo '</div>
                </div>
                <h2 class="woocommerce-loop-product__title">' . $product->get_title() . '</h2>
                ' . ($args['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html($product->get_average_rating(), $product->get_rating_count())) . '
                ' . ($product->is_on_sale() ? '<span class="onsale">' . __('Sale!', 'essential-addons-elementor') . '</span>' : '') . '
                <span class="price">' . $product->get_price_html() . '</span>
            </li>';
        } else {
            setup_postdata($GLOBALS['post'] = $post_object);
            wc_get_template_part('content', 'product');
            wp_reset_postdata();
        }

        unset($GLOBALS['product']);
    }
}

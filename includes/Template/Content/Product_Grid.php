<?php

namespace Essential_Addons_Elementor\Template\Content;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

trait Product_Grid
{
    public static function render_template_($args, $settings)
    {
        $query = new \WP_Query($args);

        ob_start();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $product = wc_get_product(get_the_ID());

                if ($settings['eael_product_grid_style_preset'] == 'eael-product-simple' || $settings['eael_product_grid_style_preset'] == 'eael-product-reveal') {
                    echo '<li class="product">
                        <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                            ' . $product->get_image('woocommerce_thumbnail') . '
                            <h2 class="woocommerce-loop-product__title">' . $product->get_title() . '</h2>
                            ' . ($settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html($product->get_average_rating(), $product->get_rating_count())) . '
                            ' . ($product->is_on_sale() ? '<span class="onsale">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') . '
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
            }
        } else {
            _e('<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite');
        }

        wp_reset_postdata();

        return ob_get_clean();
    }
}

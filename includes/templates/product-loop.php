<?php

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}

?>
<li <?php wc_product_class('product');?>>
	<?php if ($grid_layout == 'eael-product-default') {
		do_action('woocommerce_before_shop_loop_item');
		do_action('woocommerce_before_shop_loop_item_title');
		do_action('woocommerce_shop_loop_item_title');
		do_action('woocommerce_after_shop_loop_item_title');
		do_action('woocommerce_after_shop_loop_item');
	} elseif ($grid_layout == 'eael-product-simple' || $grid_layout == 'eael-product-reveal') {
		echo '<a href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
			' . $product->get_image('woocommerce_thumbnail') . '
			<h2 class="woocommerce-loop-product__title">' . $product->get_title() . '</h2>
			' . ($show_rating != 'yes' ? '' : wc_get_rating_html($product->get_average_rating(), $product->get_rating_count())) . '
			' . ($product->is_on_sale() ? '<span class="onsale">' . __('Sale!', 'essential-addons-elementor') . '</span>' : '') . '
			<span class="price">' . $product->get_price_html() . '</span>
		</a>';

		woocommerce_template_loop_add_to_cart();
	} else if ($grid_layout == 'eael-product-overlay') {
		echo '<div class="overlay">
			' . $product->get_image('woocommerce_thumbnail') . '
			<div class="button-wrap clearfix">
				<a href="' . get_the_permalink() . '" class="product-link"><span class="fa fa-link"></span></a>';
				woocommerce_template_loop_add_to_cart();
			echo '</div>
		</div>
		<h2 class="woocommerce-loop-product__title">' . $product->get_title() . '</h2>
		' . ($show_rating != 'yes' ? '' : wc_get_rating_html($product->get_average_rating(), $product->get_rating_count())) . '
		' . ($product->is_on_sale() ? '<span class="onsale">' . __('Sale!', 'essential-addons-elementor') . '</span>' : '') . '
		<span class="price">' . $product->get_price_html() . '</span>';
	}?>
</li>
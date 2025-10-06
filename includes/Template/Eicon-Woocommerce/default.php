<?php


/**
 * Template Name: Default
 */

use \Essential_Addons_Elementor\Classes\Helper;
use Essential_Addons_Elementor\Elements\Product_Grid;
use \Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$product = wc_get_product( get_the_ID() );
if ( ! $product ) {
	error_log( '$product not found in ' . __FILE__ );
	return;
}


if ( has_post_thumbnail() ) {
	$settings[ 'eael_image_size_customize' ] = [
		'id' => get_post_thumbnail_id(),
	];
	$settings['eael_image_size_customize_size'] = $settings['eael_product_grid_image_size_size'];
	$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings,'eael_image_size_customize' );
}

$title_tag = isset( $settings['eael_product_grid_title_html_tag'] ) ? Helper::eael_validate_html_tag($settings['eael_product_grid_title_html_tag'])  : 'h2';
$should_print_compare_btn = isset( $settings['show_compare'] ) && 'yes' === $settings['show_compare'];

if ( function_exists( 'YITH_WCWL' ) ) {
	$should_print_wishlist_btn = isset( $settings['eael_product_grid_wishlist'] ) && 'yes' === $settings['eael_product_grid_wishlist'];
}
// Improvement
$grid_style_preset = isset($settings['eael_product_grid_style_preset']) ? $settings['eael_product_grid_style_preset'] : '';
$list_style_preset = isset($settings['eael_product_list_style_preset']) ? $settings['eael_product_list_style_preset'] : '';
$sale_badge_align  = isset( $settings['eael_product_sale_badge_alignment'] ) ? esc_attr( $settings['eael_product_sale_badge_alignment'] ) : '';
$sale_badge_preset = isset( $settings['eael_product_sale_badge_preset'] ) ? esc_attr( $settings['eael_product_sale_badge_preset'] ) : '';
// should print vars
$should_print_rating = isset( $settings['eael_product_grid_rating'] ) && 'yes' === $settings['eael_product_grid_rating'];
$should_print_quick_view = isset( $settings['eael_product_grid_quick_view'] ) && 'yes' === $settings['eael_product_grid_quick_view'];
$should_print_image_clickable = isset( $settings['eael_product_grid_image_clickable'] ) && 'yes' === $settings['eael_product_grid_image_clickable'];
$should_print_price = isset( $settings['eael_product_grid_price'] ) && 'yes' === $settings['eael_product_grid_price'];
$should_print_excerpt = isset( $settings['eael_product_grid_excerpt'] ) && ('yes' === $settings['eael_product_grid_excerpt'] && has_excerpt());
$widget_id = isset($settings['eael_widget_id']) ? $settings['eael_widget_id'] : '';

$sale_badge_text = !empty($settings['eael_product_sale_text']) ? $settings['eael_product_sale_text'] :  __( 'Sale!', 'essential-addons-for-elementor-lite' );
$stock_out_badge_text = !empty($settings['eael_product_stockout_text']) ?$settings['eael_product_stockout_text'] : __( 'Stock <br/> Out', 'essential-addons-for-elementor-lite' );
$is_show_badge = $settings['eael_show_product_sale_badge'];

$quick_view_setting = [
	'widget_id' => $widget_id,
	'product_id' => $product->get_id(),
	'page_id' => $settings['eael_page_id'],
];
$product_wrapper_classes = implode( " ", apply_filters( 'eael_product_wrapper_class', [], $product->get_id(), 'eicon-woocommerce' ) );

$product_data = [
	'id'     => get_the_ID(),
	'title'  => '<div class="eael-product-title">
                                <a href="' . esc_url( $product->get_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">' .
	            sprintf( '<%1$s class="woocommerce-loop-product__title">%2$s</%1$s>', $title_tag, $product->get_title() )
	            . '</a></div>',
	'ratings' => $should_print_rating ? wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) : '',
	'price'   => $should_print_price ? '<div class="eael-product-price">' . $product->get_price_html() . '</div>' : ''
];

if ( $should_print_rating ) {
	$avg_rating = $product->get_average_rating();
	if( $avg_rating > 0 ){
		$product_data['ratings'] = wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
	} else {
		$product_data['ratings'] = Helper::eael_rating_markup( $product->get_average_rating(), $product->get_rating_count() );
	}
}



if ( 'yes' !== $settings['eael_product_grid_rating'] ) {
	remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating',5);
}

add_action('woocommerce_before_shop_loop_item_title', function() use ( $stock_out_badge_text ) {
	global $product;
	if ( ! $product->is_in_stock() ) {
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<span class="outofstock-badge">'. $stock_out_badge_text .'</span>';
	}
}, 9 );

add_filter('woocommerce_sale_flash', function($text, $post, $product) use( $sale_badge_text ) {
	return '<span class="onsale" data-notification="default">'. $sale_badge_text .'</span>';
}, 10, 3);

if ( $should_print_compare_btn ) {
	add_action( 'woocommerce_after_shop_loop_item', [
		'\Essential_Addons_Elementor\Elements\Product_Grid',
		'print_compare_button',
	] );
}

$thumb_size = isset($settings['eael_product_grid_image_size_size']) ? $settings['eael_product_grid_image_size_size'] : '';
global $eael_thumb_default;
add_filter( 'single_product_archive_thumbnail_size', function( $size ) use ( $thumb_size ) {
	global $eael_thumb_default;
	$eael_thumb_default = $size;
	return  ! empty( $thumb_size ) ? $thumb_size : $size ;
} );

wc_get_template_part( 'content', 'product' );

add_filter( 'single_product_archive_thumbnail_size', function( $size ) {
	global $eael_thumb_default;
	return ! empty( $eael_thumb_default ) ? $eael_thumb_default : $size;
} );

if ( $should_print_compare_btn ) {
	remove_action( 'woocommerce_after_shop_loop_item', [
		'\Essential_Addons_Elementor\Elements\Product_Grid',
		'print_compare_button',
	] );
}

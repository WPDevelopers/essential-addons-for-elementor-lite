<?php
/**
 * Template Name: Preset 4
 */

use \Essential_Addons_Elementor\Classes\Helper;
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
	$settings['eael_image_size_customize_size'] = $settings['eael_product_gallery_image_size_size'];
	$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings,'eael_image_size_customize' );
}

$title_tag = isset( $settings['eael_product_gallery_title_html_tag'] ) ? Helper::eael_validate_html_tag($settings['eael_product_gallery_title_html_tag'])  : 'h2';

// Improvement
$sale_badge_align = isset( $settings['eael_product_sale_badge_alignment'] ) ? $settings['eael_product_sale_badge_alignment'] : '';
$sale_badge_preset = isset($settings['eael_product_sale_badge_preset']) ? $settings['eael_product_sale_badge_preset'] : '';
// should print vars
$sale_text = !empty($settings['eael_product_gallery_sale_text']) ? $settings['eael_product_gallery_sale_text'] : 'Sale!';
$stockout_text = !empty($settings['eael_product_gallery_stockout_text']) ? $settings['eael_product_gallery_stockout_text'] : 'Stock <br/> Out';
$should_print_rating = isset( $settings['eael_product_gallery_rating'] ) && 'yes' === $settings['eael_product_gallery_rating'];
$should_print_quick_view = isset( $settings['eael_product_gallery_quick_view'] ) && 'yes' === $settings['eael_product_gallery_quick_view'];
$should_print_addtocart = isset( $settings['eael_product_gallery_addtocart_show'] ) && 'yes' === $settings['eael_product_gallery_addtocart_show'];
$should_print_link = isset( $settings['eael_product_gallery_link_show'] ) && 'yes' === $settings['eael_product_gallery_link_show'];
$should_print_image_clickable = isset( $settings['eael_product_gallery_image_clickable'] ) && 'yes' === $settings['eael_product_gallery_image_clickable'];
$should_print_price = isset( $settings['eael_product_gallery_price'] ) && 'yes' === $settings['eael_product_gallery_price'];
$widget_id = isset($settings['eael_widget_id']) ? $settings['eael_widget_id'] : null;
$quick_view_setting = [
	'widget_id' => $widget_id,
	'product_id' => $product->get_id(),
	'page_id' => $settings['eael_page_id'],
];

$show_secondary_image = isset( $settings['eael_product_gallery_show_secondary_image'] ) && 'yes' === $settings['eael_product_gallery_show_secondary_image'];
$image_sources = [
    'src' => '',
    'src_hover' => ''
];
//if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {
$product_wrapper_classes = implode( " ", apply_filters( 'eael_product_wrapper_class', [], $product->get_id(), 'eael-woo-product-gallery' ) );
?>
<li class="product <?php echo esc_attr( $product_wrapper_classes ) ?>">
    <?php 
    if($show_secondary_image){
        $image_sources = Helper::eael_get_woo_product_gallery_image_srcs( $product, $settings['eael_product_gallery_image_size_size'] );
    }

    ?>
    <div class="eael-product-wrap" data-src="<?php echo esc_url( $image_sources['src'] ); ?>" data-src-hover="<?php echo esc_url( $image_sources['src_hover'] ) ?>" >
    <?php
    do_action( 'eael_woocommerce_before_shop_loop_item' );
    if ( $settings['eael_wc_loop_hooks'] === 'yes' ){
        do_action( 'woocommerce_before_shop_loop_item' );
    }

    echo ( ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '. esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) .'">'. Helper::eael_wp_kses($stockout_text) .'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '. esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) .'">' . Helper::eael_wp_kses($sale_text) . '</span>' : '') );

    if( $should_print_image_clickable ) {
        echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
    }?>
        <?php
        echo $product->get_image( $settings['eael_product_gallery_image_size_size'], ['loading' => 'eager', 'alt' => esc_attr( $product->get_title() ) ] );
        if ( $should_print_image_clickable ) {
            echo '</a>';
        }
        printf('<%1$s class="woocommerce-loop-product__title">%2$s</%1$s>', $title_tag, Helper::eael_wp_kses($product->get_title()));
        if ( $should_print_rating ) {
            $avg_rating = $product->get_average_rating();
            if( $avg_rating > 0 ){
                echo wc_get_rating_html( $avg_rating, $product->get_rating_count());
            } else {
                echo Helper::eael_rating_markup( $avg_rating, $product->get_rating_count() );
            }
        }

        if ( $should_print_price ) {
            echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
        }
        ?>
    <?php
    if ( $should_print_addtocart ) {
        woocommerce_template_loop_add_to_cart();
    }
    if ( $settings['eael_wc_loop_hooks'] === 'yes' ){
        do_action( 'woocommerce_after_shop_loop_item' );
    }
    do_action( 'eael_woocommerce_after_shop_loop_item' );
    ?>
    </div>
</li>
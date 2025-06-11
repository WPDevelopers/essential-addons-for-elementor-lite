<?php
/**
 * Template Name: Preset 2
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

$sale_badge_text = ! empty($settings['eael_product_sale_text']) ? $settings['eael_product_sale_text'] :  __( 'Sale!', 'essential-addons-for-elementor-lite' );
$stock_out_badge_text = ! empty($settings['eael_product_stockout_text']) ? $settings['eael_product_stockout_text'] : __( 'Stock <br/> Out', 'essential-addons-for-elementor-lite' );
$is_show_badge = $settings['eael_show_product_sale_badge'];

$quick_view_setting = [
	'widget_id' => $widget_id,
	'product_id' => $product->get_id(),
	'page_id' => $settings['eael_page_id'],
];
$product_wrapper_classes = implode( " ", apply_filters( 'eael_product_wrapper_class', [], $product->get_id(), 'eicon-woocommerce' ) );

if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {
    ?>
    <li class="product <?php echo esc_attr( "{$product_wrapper_classes} {$list_style_preset}" ) ?>">
        <div class="eael-product-wrap">
            <div class="product-image-wrap">
                <div class="image-wrap">
                    <?php
                    if ( $should_print_image_clickable ) {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
                    }
                    if ( $is_show_badge ) {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo ( ! $product->is_in_stock() ? '<span class="eael-onsale outofstock ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">' . $stock_out_badge_text . '</span>' : ( $product->is_on_sale() ? '<span class="eael-onsale ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">' . $sale_badge_text . '</span>' : '' ) );
                    }
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo $product->get_image( $settings['eael_product_grid_image_size_size'], [ 'loading' => 'eager' ] );

                    if ( $should_print_image_clickable ) {
                        echo '</a>';
                    }
                    ?>
                </div>
            </div>
            <div class="product-details-wrap">
                <?php
                do_action( 'eael_woocommerce_before_shop_loop_item' );
                if ( $settings['eael_wc_loop_hooks'] === 'yes' ) {
                    do_action( 'woocommerce_before_shop_loop_item' );
                }

                echo '<div class="eael-product-title">
                        <a href="' . esc_url( $product->get_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
                printf('<%1$s>%2$s</%1$s>', esc_attr( $title_tag ), wp_kses( $product->get_title(), Helper::eael_allowed_tags() ));
                echo '</a>
                    </div>';
                if ( $should_print_excerpt ) {
                    echo '<div class="eael-product-excerpt">';
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt()), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator']) . '</p>';
                    echo '</div>';
                }
                if ( $should_print_price ) {
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    echo '<div class="eael-product-price">' . $product->get_price_html() . '</div>';
                }

                if ( $should_print_rating ) {
                    $avg_rating = $product->get_average_rating();
                    if( $avg_rating > 0 ){
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
                    } else {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo Helper::eael_rating_markup( $product->get_average_rating(), $product->get_rating_count() );
                    }
                }
                ?>

                <ul class="icons-wrap <?php echo esc_attr( $settings['eael_product_action_buttons_preset'] ); ?>">
                    <?php
                    if ( $should_print_compare_btn ) {
                        echo '<li class="add-to-compare">';
                        Product_Grid::print_compare_button( $product->get_id(), 'icon' );
                        echo '</li>';
                    }
                    ?>
                    <li class="add-to-cart"><?php
                        woocommerce_template_loop_add_to_cart(); ?></li>

                    <?php
                    if ( ! empty( $should_print_wishlist_btn ) ) {
                        echo '<li class="add-to-whishlist">';
                        echo do_shortcode('[yith_wcwl_add_to_wishlist]');
                        echo '</li>';
                    }
                    ?>
                    <?php
                    if( $should_print_quick_view ){?>
                        <li class="eael-product-quick-view">
                            <a id="eael_quick_view_<?php echo esc_attr( uniqid() ); ?>" data-quickview-setting="<?php echo esc_attr( htmlspecialchars(wp_json_encode($quick_view_setting),ENT_QUOTES) ); ?>"
                               class="eael-product-grid-open-popup open-popup-link">
                                <i class="fas fa-eye"></i>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <?php
                if ( $settings['eael_wc_loop_hooks'] === 'yes' ) {
                    do_action( 'woocommerce_after_shop_loop_item' );
                }
                do_action( 'eael_woocommerce_after_shop_loop_item' );
                ?>
            </div>
        </div>
    </li>
    <?php
}
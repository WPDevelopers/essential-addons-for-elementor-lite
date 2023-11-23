<?php
/**
 * Template Name: Preset 1
 */

use Essential_Addons_Elementor\Elements\Woo_Product_List;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( empty( $woo_product_list ) ) {
    $woo_product_list = Woo_Product_List::get_woo_product_list_settings( $settings );
}

$product = wc_get_product( get_the_ID() );
if ( ! $product ) {
    error_log( '$product not found in ' . __FILE__ );
    return;
}

$woo_product_list_loop = Woo_Product_List::get_woo_product_list_loop_settings( $product, $settings, $woo_product_list ); // static method as the template is used by read more feature too
?>
<div <?php post_class( 'product' ); ?>>
    <div class="eael-product-list-item <?php echo esc_attr( $woo_product_list['image_alignment'] ); ?>">
        <?php if( 'badge-preset-2' === $woo_product_list['badge_preset'] ) : ?>
            <?php Woo_Product_List::eael_print_produt_badge_html( $woo_product_list, $product ); ?>
        <?php endif; ?>
        
        <div class="eael-product-list-image-wrap">
            <?php if( 'badge-preset-2' !== $woo_product_list['badge_preset'] ) : ?>
                <?php Woo_Product_List::eael_print_produt_badge_html( $woo_product_list, $product ); ?>
            <?php endif; ?>
            
            <?php if ( $woo_product_list['image_clickable'] ) : ?>                                
            <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link" >
            <?php endif; ?>

                <?php echo wp_kses_post( $product->get_image( $woo_product_list['image_size'], ['loading' => 'eager'] ) ); ?>
            
            <?php if ( $woo_product_list['image_clickable'] ) : ?>                   
            </a>
            <?php endif; ?>

            <?php if ( $woo_product_list['button_position_on_hover'] ) : ?>
            <ul class="eael-product-list-buttons-on-hover">
                <?php if ( $woo_product_list['add_to_cart_button_show'] ) : ?>
                <li class="eael-product-list-add-to-cart-button eael-m-0">
                    <?php woocommerce_template_loop_add_to_cart(); ?>
                </li>
                <?php endif; ?>

                <?php if ( $woo_product_list['quick_view_button_show'] ) : ?>
                <li class="eael-product-list-quick-view-button eael-m-0">
                    <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars( json_encode( $woo_product_list_loop['quick_view_setting'] ), ENT_QUOTES ); ?>" class="open-popup-link"><i class="fas fa-eye"></i></a>
                </li>
                <?php endif; ?>

                <?php if ( $woo_product_list['link_button_show'] ) : ?>
                <li class="eael-product-list-link-button eael-m-0">
                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>"><i class="fas fa-link"></i></a>
                </li>
                <?php endif; ?>
            </ul>
            <?php endif; ?>
        </div>

        <div class="eael-product-list-content-wrap">
            <?php 
            if ( 'after-title' === $woo_product_list['content_header_position'] ) :
                Woo_Product_List::eael_print_product_title_html( $woo_product_list, $product );
            endif; 
            ?>

            <div class="eael-product-list-content-header <?php echo esc_attr( $woo_product_list_loop['direction_rtl_class'] ) ?>" >
                <?php if ( $woo_product_list['rating_show'] ) : ?>
                <div class="eael-product-list-rating">
                    <?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) ); ?>
                    
                    <?php if ( $woo_product_list['review_count_show'] && $woo_product_list_loop['review_count'] > 0 ) : ?>
                        <a href="<?php echo esc_url( get_permalink() ) ?>#reviews" class="woocommerce-review-link eael-product-list-review-count" rel="nofollow">(<?php printf( '%s', __( $woo_product_list_loop['review_count'], 'essential-addons-for-elementor-lite' ) ); ?>)</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div class="eael-product-list-notice eael-product-list-notice-shiping-free">
                    <?php if ( $woo_product_list['category_show'] && $woo_product_list_loop['has_terms'] ) : ?> 
                    <p>
                        <i class="fas fa-box"></i>
                        <?php echo esc_html( Woo_Product_List::eael_get_product_category_name( $woo_product_list_loop['terms'] ) ); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="eael-product-list-content-body">
                <?php
                if ( 'before-title' === $woo_product_list['content_header_position'] ) :
                    Woo_Product_List::eael_print_product_title_html( $woo_product_list, $product );
                endif; 
                ?>

                <?php if ( $woo_product_list['excerpt_show'] ) : ?>
                <div class="eael-product-list-excerpt">
                    <?php echo wp_trim_words( strip_shortcodes( get_the_excerpt() ), $woo_product_list['excerpt_words_count'], $woo_product_list['excerpt_expanison_indicator'] ); ?>
                </div>
                <?php endif; ?>

                <?php if ( $woo_product_list['price_show'] ) : ?>
                <h4 class="eael-product-list-price">
                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                </h4>
                <?php endif; ?>
            </div>
            <div class="eael-product-list-content-footer">
                <?php if ( $woo_product_list['total_sold_show'] ) : ?>
                <div class="eael-product-list-progress">
                    <div class="eael-product-list-progress-info">
                        <h4 class="eael-product-list-progress-count"><?php esc_html_e( $woo_product_list['total_sold_text'], 'essential-addons-for-elementor-lite' ); ?> <span><?php echo esc_html( $woo_product_list_loop['total_sales_count'] ); ?></span></h4>
                        <?php if( $product->managing_stock() && $woo_product_list['total_sold_remaining_show'] ) : ?>
                        <h4 class="eael-product-list-progress-remaining"><?php esc_html_e( $woo_product_list['total_sold_remaining_text'], 'essential-addons-for-elementor-lite' ); ?> <span><?php echo esc_html( $woo_product_list_loop['stock_quantity_count'] ); ?></span></h4>
                        <?php endif; ?>
                    </div>
                    <div class="eael-product-list-progress-bar-outer">
                        <div style="width: <?php echo esc_attr( $woo_product_list_loop['total_sold_progress_percentage'] ); ?>%;" class="eael-product-list-progress-bar-inner"></div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ( $woo_product_list['button_position_static'] ) : ?>
                <div class="eael-product-list-buttons">
                    <?php if ( $woo_product_list['add_to_cart_button_show'] ) : ?>
                    <p class="eael-product-list-add-to-cart-button eael-m-0"><?php woocommerce_template_loop_add_to_cart(); ?></p>
                    <?php endif; ?>

                    <?php if ( $woo_product_list['quick_view_button_show'] ) : ?>
                    <p class="eael-product-list-quick-view-button eael-m-0">
                        <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars( json_encode( $woo_product_list_loop['quick_view_setting'] ), ENT_QUOTES ); ?>" class="open-popup-link">
                            <?php esc_html_e( $woo_product_list['quick_view_text'], 'essential-addons-for-elementor-lite' ); ?>
                        </a>
                    </p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
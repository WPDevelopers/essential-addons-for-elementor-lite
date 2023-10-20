<?php
/**
 * Template Name: Preset 2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly
?>

<div class="eael-product-list-wrapper preset-2">
    <div class="eael-product-list-body woocommerce">
        <div class="eael-product-list-container">
            <?php
            while ( $query->have_posts() ) {
                $query->the_post();

                $product = wc_get_product( get_the_ID() );
                if ( ! $product ) {
                    error_log( '$product not found in ' . __FILE__ );
                    return;
                }

                $quick_view_setting = [
                    'widget_id'     => $widget_id,
                    'product_id'    => $product->get_id(),
                    'page_id'       => $settings['eael_page_id'],
                ];
                ?>
                <div class="eael-product-list-item">
                    <div class="eael-product-list-image-wrap">
                        <?php if ( $woo_product_list['image_clickable'] ) : ?>                                
                        <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link" >
                        <?php endif; ?>

                            <?php echo wp_kses_post( $product->get_image( $woo_product_list['image_size'], ['loading' => 'eager'] ) ); ?>
                        
                        <?php if ( $woo_product_list['image_clickable'] ) : ?>                   
                        </a>
                        <?php endif; ?>

                        <ul class="eael-product-list-buttons-on-hover">
                            <?php if ( $woo_product_list['add_to_cart_button_show'] ) : ?>
                            <li class="eael-product-list-add-to-cart-button eael-m-0">
                                <?php woocommerce_template_loop_add_to_cart(); ?>
                            </li>
                            <?php endif; ?>

                            <?php if ( $woo_product_list['quick_view_button_show'] ) : ?>
                            <li class="eael-product-list-quick-view-button eael-m-0">
                                <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars( json_encode( $quick_view_setting ), ENT_QUOTES ); ?>" class="open-popup-link"><i class="fas fa-eye"></i></a>
                            </li>
                            <?php endif; ?>

                            <?php if ( $woo_product_list['link_button_show'] ) : ?>
                            <li class="eael-product-list-link-button eael-m-0">
                                <a href="<?php echo esc_url( $product->get_permalink() ); ?>"><i class="fas fa-link"></i></a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="eael-product-list-content-wrap">
                        <div <?php $this->print_render_attribute_string('eael-product-list-content-header'); ?> >
                            <?php
                            if ( 'after-title' === $woo_product_list['content_header_position'] ) :
                                $this->eael_get_product_title_html( $woo_product_list, $product );
                            endif; 
                            ?>
                            <?php if ( $woo_product_list['rating_show'] ) : ?>
                            <div class="eael-product-list-rating">
                                <?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) ); ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="eael-product-list-notice eael-product-list-notice-category">
                                <p><i class="fa-solid fa-box"></i> Home Decor</p>
                            </div>
                        </div>
                        <div class="eael-product-list-content-body">
                            <?php
                            if ( 'before-title' === $woo_product_list['content_header_position'] ) :
                                $this->eael_get_product_title_html( $woo_product_list, $product );
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
                                    <h4 class="eael-product-list-progress-count">Total Sold: <span>50</span></h4>
                                    <h4 class="eael-product-list-progress-remaining">Remaining: <span>08</span></h4>
                                </div>
                                <div class="eael-product-list-progress-bar-outer">
                                    <div style="width: 80%;" class="eael-product-list-progress-bar-inner"></div>
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
                                    <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars( json_encode( $quick_view_setting ), ENT_QUOTES ); ?>" class="open-popup-link">
                                       <?php _e('View Product', 'essential-addons-for-elementor-lite'); ?>
                                    </a>
                                </p>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php 
            }

            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>
<?php
/**
 * Template Name: Preset 1
 */

use Essential_Addons_Elementor\Classes\Helper;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly
?>

<div class="eael-product-list-wrapper preset-1">
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
                        <div class="eael-product-list-sale-badge">
                            <div class="eael-product-list-sale-badge-bg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100" fill="none">
                                    <path d="M50 0L59.861 13.1982L75 6.69873L76.9408 23.0592L93.3013 25L86.8018 40.139L100 50L86.8018 59.861L93.3013 75L76.9408 76.9408L75 93.3013L59.861 86.8018L50 100L40.139 86.8018L25 93.3013L23.0592 76.9408L6.69873 75L13.1982 59.861L0 50L13.1982 40.139L6.69873 25L23.0592 23.0592L25 6.69873L40.139 13.1982L50 0Z" fill="#DBEC73"/>
                                    </svg>
                            </div>
                            <p><span>30%</span> Off</p>
                        </div>
                        <a href="#">
                            <img src="//essential-addons-dev.test/wp-content/uploads/2023/10/product-image-1.png" alt="Saguaro with Wooden stand">
                        </a>

                        <div class="eael-product-list-image-hover-wrap">
                            <ul class="eael-product-list-buttons-on-hover">
                                <?php if ( $woo_product_list['add_to_cart_button_show'] ) : ?>
                                <p class="eael-product-list-add-to-cart-button eael-m-0">
                                    <?php woocommerce_template_loop_add_to_cart(); ?>
                                </p>
                                <?php endif; ?>

                                <?php if ( $woo_product_list['quick_view_button_show'] ) : ?>
                                <p class="eael-product-list-quick-view-button eael-m-0">
                                    <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars( json_encode( $quick_view_setting ), ENT_QUOTES ); ?>" class="open-popup-link"><i class="fas fa-eye"></i></a>
                                </p>
                                <?php endif; ?>

                                <?php if ( $woo_product_list['link_button_show'] ) : ?>
                                <p class="eael-product-list-link-button eael-m-0">
                                    <a href="<?php echo esc_url( $product->get_permalink() ); ?>"><i class="fas fa-link"></i></a>
                                </p>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="eael-product-list-content-wrap">
                        <div <?php $this->print_render_attribute_string('eael-product-list-content-header'); ?> >
                            <?php if ( $woo_product_list['rating_show'] ) : ?>
                            <div class="eael-product-list-rating">
                                <?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) ); ?>
                            </div>
                            <?php endif; ?>
                            
                            <div class="eael-product-list-notice eael-product-list-notice-shiping-free">
                                <p><i class="fa-solid fa-box"></i> Free Shipping</p>
                            </div>
                        </div>
                        <div class="eael-product-list-content-body">
                            <?php if ( $woo_product_list['title_show'] ) : ?>
                            <<?php echo $woo_product_list['title_tag'];  ?> class="eael-product-list-title">
                                <?php if ( $woo_product_list['title_clickable'] ) : ?>
                                <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link" target="_blank">
                                    <?php echo Helper::eael_wp_kses( $product->get_title() ); ?>
                                </a>
                                <?php else : ?>
                                    <?php echo Helper::eael_wp_kses( $product->get_title() ); ?>
                                <?php endif; ?>
                            </<?php echo $woo_product_list['title_tag'];  ?>>
                            <?php endif; ?>

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
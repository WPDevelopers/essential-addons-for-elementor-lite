<?php
/**
 * Template Name: Preset 1
 */

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
                        <ul class="eael-product-list-links">
                            <li><a href="#"><i class="fa-regular fa-heart"></i></a></li>
                            <li><a href="#"><i class="fa-solid fa-code-compare"></i></a></li>
                            <li><a href="#"><i class="fa-solid fa-eye"></i></a></li>
                            <li><a href="#"><i class="fa-solid fa-cart-shopping"></i></a></li>
                        </ul>
                    </div>
                    <div class="eael-product-list-content-wrap">
                        <div class="eael-product-list-content-header">
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
                            <h2 class="eael-product-list-title">
                                <a href="#">Saguaro with Wooden stand</a>
                            </h2>
                            <div class="eael-product-list-rating-count">
                                <i class="fa-solid fa-star"></i>
                                <span class="rating">4.7</span>
                                <span class="count">(100+ Review)</span>
                            </div>
                            <ul class="eael-product-list-features-list">
                                <li>Through mechanical movements, without the use of batteries or electronics.</li>
                                <li>Through mechanical movements, without the use of batteries or electronics.</li>
                                <li>Perfect craftsmanship, precision, and timeless appeal. Timepiece that operates
                                    purely</li>
                            </ul>
                            <p class="eael-product-list-features">
                                Perfect craftsmanship, precision, and timeless appeal. Timepiece that operates purely
                            </p>
                            <div class="eael-product-list-progress">
                                <h4 class="eael-product-list-progress-count">Total Sold: 300 Item</h4>
                                <div class="eael-product-list-progress-bar-outer">
                                    <div style="width: 80%;" class="eael-product-list-progress-bar-inner"></div>
                                </div>
                            </div>
                            <h4 class="eael-product-list-price">
                                <span class="eael-product-list-sale-price">$200.00</span>
                                <span class="eael-product-list-main-price">$287.00</span>
                            </h4>
                        </div>
                        <div class="eael-product-list-content-footer">
                            <div class="eael-product-list-buttons">
                                <a href="#" class="eael-product-list-action-cart"><i class="fa-solid fa-cart-shopping"></i>Add To Cart</a>
                                <a href="#" class="eael-product-list-action-view">View Product</a>
                            </div>
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
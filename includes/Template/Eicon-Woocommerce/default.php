<?php

use \Essential_Addons_Elementor\Classes\Helper;

/**
 * Template Name: Default
 */

use Essential_Addons_Elementor\Elements\Product_Grid;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

$product = wc_get_product( get_the_ID() );
if ( ! $product ) {
    error_log( '$product not found in ' . __FILE__ );
    return;
}
$should_print_compare_btn = isset( $settings['show_compare'] ) && 'yes' === $settings['show_compare'];
// Improvement
$grid_style_preset = isset($settings['eael_product_grid_style_preset']) ? $settings['eael_product_grid_style_preset'] : '';
$list_style_preset = isset($settings['eael_product_list_style_preset']) ? $settings['eael_product_list_style_preset'] : '';
$sale_badge_align = isset( $settings['eael_product_sale_badge_alignment'] ) ? $settings['eael_product_sale_badge_alignment'] : '';
$sale_badge_preset = isset($settings['eael_product_sale_badge_preset']) ? $settings['eael_product_sale_badge_preset'] : '';
// should print vars
$should_print_rating = isset( $settings['eael_product_grid_rating'] ) && 'yes' === $settings['eael_product_grid_rating'];
$should_print_quick_view = isset( $settings['eael_product_grid_quick_view'] ) && 'yes' === $settings['eael_product_grid_quick_view'];
$should_print_price = isset( $settings['eael_product_grid_price'] ) && 'yes' === $settings['eael_product_grid_price'];
$should_print_excerpt = isset( $settings['eael_product_grid_excerpt'] ) && ('yes' === $settings['eael_product_grid_excerpt'] && has_excerpt());
$widget_id = isset($settings['eael_widget_id']) ? $settings['eael_widget_id'] : null;

if ( $grid_style_preset == 'eael-product-simple' || $grid_style_preset == 'eael-product-reveal' ) { ?>
    <li class="product">
        <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
            <?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail', ['loading' => 'eager'] ) ); ?>
            <h2 class="woocommerce-loop-product__title"> <?php echo esc_html( $product->get_title() ); ?> </h2>
            <?php
            if ( $should_print_rating ) {
                echo wp_kses_post( wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) );
            }
            if ( ! $product->managing_stock() && ! $product->is_in_stock() ) {
                printf( '<span class="outofstock-badge">%s</span>', __( 'Stock <br/> Out', 'essential-addons-for-elementor-lite' ) );
            } elseif ( $product->is_on_sale() ) {
                printf( '<span class="onsale">%s</span>', __( 'Sale!', 'essential-addons-for-elementor-lite' ) );
            }
            ?>
            <span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
        </a>
        <?php
        woocommerce_template_loop_add_to_cart();
        if ( $should_print_compare_btn ) {
            Product_Grid::print_compare_button( $product->get_id() );
        }
        ?>
    </li>
    <?php
} else if ( $grid_style_preset == 'eael-product-overlay' ) {
    ?>
    <li class="product">
        <div class="overlay">
            <?php echo $product->get_image( 'woocommerce_thumbnail', ['loading' => 'eager'] ); ?>
            <div class="button-wrap clearfix">
                <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="product-link"><span class="fas fa-link"></span></a>';
                <?php
                woocommerce_template_loop_add_to_cart();
                if ( $should_print_compare_btn ) {
                    Product_Grid::print_compare_button( $product->get_id(), 'icon' );
                }
                ?>
            </div>
        </div>
        <h2 class="woocommerce-loop-product__title"><?php echo esc_html( $product->get_title() ); ?></h2>
        <?php
        if ( $should_print_rating ) {
            echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() );
        }
        if ( $product->is_on_sale() ) {
            printf( '<span class="onsale">%s</span>', __( 'Sale!', 'essential-addons-for-elementor-lite' ) );
        }
        ?>
        <span class="price"> <?php echo $product->get_price_html(); ?> </span>
    </li>
    <?php
} else if (($grid_style_preset == 'eael-product-preset-5') || ($grid_style_preset == 'eael-product-preset-6') || ($grid_style_preset == 'eael-product-preset-7')) {
    if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {
        ?>
        <li <?php post_class( 'product' ); ?>>
            <div class="eael-product-wrap">
                <div class="product-image-wrap">
                    <div class="image-wrap">
                        <?php
                        echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'.__('Stock ', 'essential-addons-for-elementor-lite'). '<br />' . __('Out', 'essential-addons-for-elementor-lite').'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') );
                        echo $product->get_image($settings['eael_product_grid_image_size_size'], ['loading' => 'eager']);
                        ?>
                    </div>
                    <div class="image-hover-wrap">
                        <?php if($grid_style_preset == 'eael-product-preset-5'){ ?>
                            <ul class="icons-wrap block-style">
                                <?php if( $should_print_quick_view ){?>
                                    <li class="eael-product-quick-view">
                                        <a href="#eaproduct<?php echo $widget_id.$product->get_id(); ?>"
                                           class="open-popup-link">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php
                                if ( $should_print_compare_btn ) {
                                    echo '<li class="add-to-compare">';
                                    Product_Grid::print_compare_button( $product->get_id(), 'icon' );
                                    echo '</li>';
                                }
                                ?>
                                <li class="add-to-cart"><?php woocommerce_template_loop_add_to_cart();
                                    ?></li>
                                <li class="view-details"><?php echo '<a href="' . $product->get_permalink() . '"><i class="fas fa-link"></i></a>'; ?></li>

                            </ul>
                        <?php } elseif ($grid_style_preset == 'eael-product-preset-7') { ?>
                            <ul class="icons-wrap block-box-style">
                                <li class="add-to-cart"><?php
                                    woocommerce_template_loop_add_to_cart(); ?></li>
                                <?php
                                if ( $should_print_compare_btn ) {
                                    echo '<li class="add-to-compare">';
                                    Product_Grid::print_compare_button( $product->get_id(), 'icon' );
                                    echo '</li>';
                                }
                                ?>
                                <?php if( $should_print_quick_view ){?>
                                    <li class="eael-product-quick-view">
                                        <a href="#eaproduct<?php echo $widget_id.$product->get_id(); ?>"
                                           class="open-popup-link">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </li>
                                <?php } ?>
                                <li class="view-details"><?php echo '<a href="' . $product->get_permalink
                                        () . '"><i class="fas fa-link"></i></a>'; ?></li>
                            </ul>
                        <?php } else { ?>
                            <ul class="icons-wrap box-style">
                                <li class="add-to-cart"><?php
                                    woocommerce_template_loop_add_to_cart(); ?></li>
                                <?php
                                if ( $should_print_compare_btn ) {
                                    echo '<li class="add-to-compare">';
                                    Product_Grid::print_compare_button( $product->get_id(), 'icon' );
                                    echo '</li>';
                                }
                                ?>
                                <?php if( $should_print_quick_view ){?>
                                    <li class="eael-product-quick-view">
                                        <a href="#eaproduct<?php echo $widget_id.$product->get_id(); ?>"
                                           class="open-popup-link">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </li>
                                <?php } ?>
                                <li class="view-details" title="Details"><?php echo '<a href="' . $product->get_permalink() . '"><i class="fas fa-link"></i></a>'; ?></li>
                            </ul>
                        <?php }
                        if( $should_print_quick_view ){
                            Helper::eael_product_quick_view( $product, $settings, $widget_id );
                        }
                        ?>
                    </div>
                </div>
                <div class="product-details-wrap">
                    <?php
                    if(($grid_style_preset == 'eael-product-preset-7') && $should_print_price ){
                        echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                    }

                    if ($should_print_rating) {
                        echo wc_get_rating_html
                        ($product->get_average_rating(), $product->get_rating_count());
                    }
                    ?>
                    <div class="eael-product-title"><h2><?php echo $product->get_title(); ?></h2></div>
                    <?php if(($grid_style_preset != 'eael-product-preset-7') && $should_print_price ){
                        echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                    }?>
                </div>
            </div>
        </li>
        <?php
    }
} else if ($grid_style_preset == 'eael-product-preset-8') {
    if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {
        ?>
        <li <?php post_class( 'product' ); ?>>
            <div class="eael-product-wrap">
                <div class="product-image-wrap">
                    <div class="image-wrap">
                        <?php
                        echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
                        echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'.__('Stock ', 'essential-addons-for-elementor-lite'). '<br />' . __('Out', 'essential-addons-for-elementor-lite').'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') );
                        echo $product->get_image($settings['eael_product_grid_image_size_size'], ['loading' => 'eager']);
                        echo '</a>';
                        ?>
                    </div>
                    <div class="image-hover-wrap">
                        <ul class="icons-wrap over-box-style">
                            <li class="add-to-cart"><?php
                                woocommerce_template_loop_add_to_cart(); ?></li>
                            <?php
                            if ( $should_print_compare_btn ) {
                                echo '<li class="add-to-compare">';
                                Product_Grid::print_compare_button( $product->get_id(), 'icon' );
                                echo '</li>';
                            }
                            ?>
                            <?php if( $should_print_quick_view ){?>
                                <li class="eael-product-quick-view">
                                    <a href="#eaproduct<?php echo $widget_id.$product->get_id(); ?>"
                                       class="open-popup-link">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php
                        if( $should_print_quick_view ){
                            Helper::eael_product_quick_view( $product, $settings, $widget_id );
                        }
                        ?>
                    </div>
                </div>
                <div class="product-details-wrap">
                    <?php
                    if ( $should_print_price ) {
                        echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                    }
                    ?>
                    <div class="eael-product-title">
                        <?php
                        echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                <h2>'. $product->get_title() .'</h2></a>';
                        ?>
                    </div>
                </div>
            </div>
        </li>
        <?php
    }
} else if(($list_style_preset == 'eael-product-list-preset-1') ||
    ($list_style_preset == 'eael-product-list-preset-2') ||
    ($list_style_preset == 'eael-product-list-preset-3') ||
    ($list_style_preset == 'eael-product-list-preset-4')) {
    if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {
        ?>
        <li class="product <?php echo $list_style_preset;?>">
            <div class="eael-product-wrap">
                <div class="product-image-wrap">
                    <div class="image-wrap">
                        <?php
                        echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
                        echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'.__('Stock ', 'essential-addons-for-elementor-lite'). '<br />' . __('Out', 'essential-addons-for-elementor-lite').'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') );
                        echo $product->get_image($settings['eael_product_grid_image_size_size'], ['loading' => 'eager']);
                        echo '</a>';
                        ?>
                    </div>
                </div>
                <div class="product-details-wrap">
                    <?php
                    if ($list_style_preset == 'eael-product-list-preset-2') {
                        echo '<div class="eael-product-title">
                                                <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                    <h2>'. $product->get_title() .'</h2>
                                                </a>
                                              </div>';
                        if ( $should_print_excerpt ) {
                            echo '<div class="eael-product-excerpt">';
                            echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt()), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator']) . '</p>';
                            echo '</div>';
                        }
                        if ( $should_print_price ) {
                            echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                        }

                        if ($should_print_rating) {
                            echo wc_get_rating_html
                            ($product->get_average_rating(), $product->get_rating_count());
                        }

                    } elseif ($list_style_preset == 'eael-product-list-preset-3') {
                        echo '<div class="price-wrap">';
                        if ($should_print_price) {
                            echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                        }
                        if ($should_print_rating) {
                            echo wc_get_rating_html
                            ($product->get_average_rating(), $product->get_rating_count());
                        }
                        echo '</div>
                                              <div class="title-wrap">
                                                  <div class="eael-product-title">
                                                    <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                        <h2>'. $product->get_title() .'</h2>
                                                    </a>
                                                  </div>';
                        if ( $should_print_excerpt ) {
                            echo '<div class="eael-product-excerpt">';
                            echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() :
                                    get_the_content()), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator']) . '</p>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } elseif ($list_style_preset == 'eael-product-list-preset-4') {

                        if ($should_print_rating) {
                            echo wc_get_rating_html
                            ($product->get_average_rating(), $product->get_rating_count());
                        }

                        echo '<div class="eael-product-title">
                                                <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                    <h2>'. $product->get_title() .'</h2>
                                                </a>
                                              </div>';
                        if ( $should_print_excerpt ) {
                            echo '<div class="eael-product-excerpt">';
                            echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() :
                                    get_the_content()), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator']) . '</p>';
                            echo '</div>';
                        }
                        if ( $should_print_price ) {
                            echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                        }

                    } else {
                        echo '<div class="eael-product-title">
                                                <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                    <h2>'. $product->get_title() .'</h2>
                                                </a>
                                              </div>
                                              <div class="eael-product-price">'.$product->get_price_html().'</div>';
                        if ($should_print_rating) {
                            echo wc_get_rating_html
                            ($product->get_average_rating(), $product->get_rating_count());
                        }

                        if ( $should_print_excerpt ) {
                            echo '<div class="eael-product-excerpt">';
                            echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() :
                                    get_the_content()), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator']) . '</p>';
                            echo '</div>';
                        };
                    }
                    ?>

                    <ul class="icons-wrap <?php echo $settings['eael_product_action_buttons_preset'] ;?>">
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
                        if( $should_print_quick_view ){?>
                            <li class="eael-product-quick-view">
                                <a href="#eaproduct<?php echo $widget_id.$product->get_id(); ?>"
                                   class="open-popup-link">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <?php
                    if( $should_print_quick_view ){
                        Helper::eael_product_quick_view( $product, $settings, $widget_id );
                    }
                    ?>
                </div>
            </div>
        </li>
        <?php
    }
}else {
    if($settings['eael_product_grid_rating']!='yes'){
        remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating',5);
    }
    if ( $should_print_compare_btn ) {
        add_action( 'woocommerce_after_shop_loop_item', [
            '\Essential_Addons_Elementor\Elements\Product_Grid',
            'print_compare_button',
        ] );
    }

    wc_get_template_part( 'content', 'product' );

    if ( $should_print_compare_btn ) {
        remove_action( 'woocommerce_after_shop_loop_item', [
            '\Essential_Addons_Elementor\Elements\Product_Grid',
            'print_compare_button',
        ] );
    }
}
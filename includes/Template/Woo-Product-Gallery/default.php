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
	$settings['eael_image_size_customize_size'] = $settings['eael_product_gallery_image_size_size'];
	$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings,'eael_image_size_customize' );
}

$title_tag = isset( $settings['eael_product_gallery_title_html_tag'] ) ? Helper::eael_validate_html_tag($settings['eael_product_gallery_title_html_tag'])  : 'h2';

// Improvement
$gallery_style_preset = isset($settings['eael_product_gallery_style_preset']) ? $settings['eael_product_gallery_style_preset'] : '';
$sale_badge_align = isset( $settings['eael_product_sale_badge_alignment'] ) ? $settings['eael_product_sale_badge_alignment'] : '';
$sale_badge_preset = isset($settings['eael_product_sale_badge_preset']) ? $settings['eael_product_sale_badge_preset'] : '';
// should print vars
$should_print_rating = isset( $settings['eael_product_gallery_rating'] ) && 'yes' === $settings['eael_product_gallery_rating'];
$should_print_quick_view = isset( $settings['eael_product_gallery_quick_view'] ) && 'yes' === $settings['eael_product_gallery_quick_view'];
$should_print_price = isset( $settings['eael_product_gallery_price'] ) && 'yes' === $settings['eael_product_gallery_price'];
$widget_id = isset($settings['eael_widget_id']) ? $settings['eael_widget_id'] : null;
$quick_view_setting = [
	'widget_id' => $widget_id,
	'product_id' => $product->get_id(),
	'page_id' => $settings['eael_page_id'],
];
if ( $gallery_style_preset == 'eael-product-simple' ) { ?>
    <li class="product">
        <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
            <?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail', ['loading' => 'eager'] ) );
            printf('<%1$s class="woocommerce-loop-product__title">%2$s</%1$s>', $title_tag, $product->get_title());
            if ( $should_print_rating ) {
                echo wp_kses_post( wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) );
            }
            if ( ! $product->managing_stock() && ! $product->is_in_stock() ) {
                printf( '<span class="outofstock-badge">%s</span>', __( 'Stock <br/> Out', 'essential-addons-for-elementor-lite' ) );
            } elseif ( $product->is_on_sale() ) {
                printf( '<span class="onsale">%s</span>', __( 'Sale!', 'essential-addons-for-elementor-lite' ) );
            }

            if ( $should_print_price ) {
              echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
            }
            ?>
        </a>
        <?php
        woocommerce_template_loop_add_to_cart();
        ?>
    </li>
    <?php
} else if (($gallery_style_preset == 'eael-product-preset-6') || ($gallery_style_preset == 'eael-product-preset-7')) {
    if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {
        ?>
        <li <?php post_class( 'product' ); ?>>
            <div class="eael-product-wrap">
                <div class="product-image-wrap">
                    <div class="image-wrap">
                        <?php
                        echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'.__('Stock ', 'essential-addons-for-elementor-lite'). '<br />' . __('Out', 'essential-addons-for-elementor-lite').'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') );
                        echo $product->get_image($settings['eael_product_gallery_image_size_size'], ['loading' => 'eager']);
                        ?>
                    </div>
                    <div class="image-hover-wrap">
                        <?php if($gallery_style_preset == 'eael-product-preset-5'){ ?>
                            <ul class="icons-wrap block-style">
                                <?php if( $should_print_quick_view ){?>
                                    <li class="eael-product-quick-view">
                                        <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
                                           class="eael-product-gallery-open-popup">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </li>
                                <?php } ?>
                                <li class="add-to-cart"><?php woocommerce_template_loop_add_to_cart();
                                    ?></li>
                                <li class="view-details"><?php echo '<a href="' . $product->get_permalink() . '"><i class="fas fa-link"></i></a>'; ?></li>

                            </ul>
                        <?php } elseif ($gallery_style_preset == 'eael-product-preset-7') { ?>
                            <ul class="icons-wrap block-box-style">
                                <li class="add-to-cart"><?php
                                    woocommerce_template_loop_add_to_cart(); ?></li>
                                <?php if( $should_print_quick_view ){?>
                                    <li class="eael-product-quick-view">
                                        <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
                                           class="eael-product-gallery-open-popup">
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
                                <?php if( $should_print_quick_view ){?>
                                    <li class="eael-product-quick-view">
                                        <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
                                           class="eael-product-gallery-open-popup">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </li>
                                <?php } ?>
                                <li class="view-details" title="Details"><?php echo '<a href="' . $product->get_permalink() . '"><i class="fas fa-link"></i></a>'; ?></li>
                            </ul>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="product-details-wrap">
                    <?php
                    if(($gallery_style_preset == 'eael-product-preset-7') && $should_print_price ){
                        echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                    }

                    if ($should_print_rating) {
                        echo wc_get_rating_html
                        ($product->get_average_rating(), $product->get_rating_count());
                    }
                    ?>
                    <div class="eael-product-title">
                       <?php printf('<%1$s>%2$s</%1$s>', $title_tag, $product->get_title()); ?>
                    </div>
                    <?php if(($gallery_style_preset != 'eael-product-preset-7') && $should_print_price ){
                        echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                    }?>
                </div>
            </div>
        </li>
        <?php
    }
} else if ($gallery_style_preset == 'eael-product-preset-8') {
    if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {
        ?>
        <li <?php post_class( 'product' ); ?>>
            <div class="eael-product-wrap">
                <div class="product-image-wrap">
                    <div class="image-wrap">
                        <?php
                        echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
                        echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'.__('Stock ', 'essential-addons-for-elementor-lite'). '<br />' . __('Out', 'essential-addons-for-elementor-lite').'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') );
                        echo $product->get_image($settings['eael_product_gallery_image_size_size'], ['loading' => 'eager']);
                        echo '</a>';
                        ?>
                    </div>
                    <div class="image-hover-wrap">
                        <ul class="icons-wrap over-box-style">
                            <li class="add-to-cart"><?php
                                woocommerce_template_loop_add_to_cart(); ?>
                            </li>
                            <?php if( $should_print_quick_view ){?>
                                <li class="eael-product-quick-view">
                                    <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
                                       class="eael-product-gallery-open-popup">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
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
                            echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
                            printf('<%1$s>%2$s</%1$s>', $title_tag, $product->get_title());
                            echo '</a>';
                        ?>
                    </div>
                </div>
            </div>
        </li>
        <?php
    }
}

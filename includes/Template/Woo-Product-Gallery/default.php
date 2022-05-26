<?php

/**
 * Template Name: Default
 */

use \Essential_Addons_Elementor\Classes\Helper;
use Essential_Addons_Elementor\Elements\Woo_Product_Gallery;
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

//if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {

    if ( $gallery_style_preset == 'eael-product-preset-4' ) { ?>
        <li class="product">
            <div class="eael-product-wrap">
	        <?php
	        echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'. $stockout_text .'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . $sale_text . '</span>' : '') );

	        if( $should_print_image_clickable ) {
		        echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
	        }?>
                <?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail', ['loading' => 'eager'] ) );
                if ( $should_print_image_clickable ) {
	                echo '</a>';
                }
                printf('<%1$s class="woocommerce-loop-product__title">%2$s</%1$s>', $title_tag, Helper::eael_wp_kses($product->get_title()));
                if ( $should_print_rating ) {
                    echo wp_kses_post( wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) );
                }

                if ( $should_print_price ) {
                    echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                }
                ?>
            <?php
	        if ( $should_print_addtocart ) {
		        woocommerce_template_loop_add_to_cart();
	        } ?>
            </div>
        </li>
        <?php
    } else if (($gallery_style_preset == 'eael-product-preset-3') || ($gallery_style_preset == 'eael-product-preset-2')) {
        ?>
        <li <?php post_class( 'product' ); ?>>
            <div class="eael-product-wrap">
                <div class="product-image-wrap">
                    <div class="image-wrap">
	                    <?php if( $should_print_image_clickable ) {
		                    echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
	                    }?>
                        <?php
                        echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'. $stockout_text .'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . $sale_text . '</span>' : '') );
                        echo $product->get_image($settings['eael_product_gallery_image_size_size'], ['loading' => 'eager']);
                        ?>
	                    <?php if( $should_print_image_clickable ) {
		                    echo '</a>';
	                    }?>
                    </div>
                    <div class="image-hover-wrap">
                        <?php if ($gallery_style_preset == 'eael-product-preset-2') { ?>
                            <ul class="icons-wrap block-box-style">
		                        <?php if( $should_print_addtocart ){?>
                                    <li class="add-to-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
		                        <?php } ?>
                                <?php if( $should_print_quick_view ){?>
                                    <li class="eael-product-quick-view">
                                        <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
                                           class="eael-product-gallery-open-popup open-popup-link">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </li>
                                <?php } ?>
			                    <?php if( $should_print_link ){?>
                                    <li class="view-details"><?php echo '<a href="' . $product->get_permalink
                                        () . '"><i class="fas fa-link"></i></a>'; ?></li>
			                    <?php } ?>
                            </ul>
                        <?php } else { ?>
                            <ul class="icons-wrap box-style">
		                        <?php if( $should_print_addtocart ){?>
                                    <li class="add-to-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
		                        <?php } ?>

                                <?php if( $should_print_quick_view ){?>
                                    <li class="eael-product-quick-view">
                                        <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
                                           class="eael-product-gallery-open-popup open-popup-link">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </li>
                                <?php } ?>

			                    <?php if( $should_print_link ){?>
                                    <li class="view-details" title="Details"><?php echo '<a href="' . $product->get_permalink() . '"><i class="fas fa-link"></i></a>'; ?></li>
			                    <?php } ?>
                            </ul>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="product-details-wrap">
                    <?php
                    if(($gallery_style_preset == 'eael-product-preset-2') && $should_print_price ){
                        echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                    }

                    if ($should_print_rating) {
                        echo wc_get_rating_html
                        ($product->get_average_rating(), $product->get_rating_count());
                    }
                    ?>
                    <div class="eael-product-title">
                        <?php printf('<%1$s>%2$s</%1$s>', $title_tag, Helper::eael_wp_kses($product->get_title())); ?>
                    </div>
                    <?php if(($gallery_style_preset != 'eael-product-preset-2') && $should_print_price ){
                        echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                    }?>
                </div>
            </div>
        </li>
        <?php

    } else if ($gallery_style_preset == 'eael-product-preset-1') {
        ?>
        <li <?php post_class( 'product' ); ?>>
            <div class="eael-product-wrap">
                <div class="product-image-wrap">
                    <div class="image-wrap">
                        <?php if( $should_print_image_clickable ) {
                            echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
                        }?>

                        <?php
                            echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'. $stockout_text .'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . $sale_text . '</span>' : '') );
                            echo $product->get_image($settings['eael_product_gallery_image_size_size'], ['loading' => 'eager']);
                        ?>
	                    <?php if( $should_print_image_clickable ) {
	                        echo '</a>';
                        }?>
                    </div>
                    <div class="image-hover-wrap">
                        <ul class="icons-wrap over-box-style">
	                        <?php if( $should_print_addtocart ){?>
                                <li class="add-to-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
	                        <?php } ?>
                            <?php if( $should_print_quick_view ){?>
                                <li class="eael-product-quick-view">
                                    <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
                                       class="eael-product-gallery-open-popup open-popup-link">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </li>
                            <?php } ?>
	                        <?php if( $should_print_link ){?>
                                <li class="view-details"><?php echo '<a href="' . $product->get_permalink
				                        () . '"><i class="fas fa-link"></i></a>'; ?></li>
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
                        printf('<%1$s>%2$s</%1$s>', $title_tag, Helper::eael_wp_kses($product->get_title()));
                        echo '</a>';
                        ?>
                    </div>
                </div>
            </div>
        </li>
        <?php

    }
//}

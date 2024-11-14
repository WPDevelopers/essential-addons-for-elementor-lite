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
$widget_id = isset($settings['eael_widget_id']) ? $settings['eael_widget_id'] : null;

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

if ( $grid_style_preset == 'eael-product-simple' || $grid_style_preset == 'eael-product-reveal' ) { ?>
    <li class="product <?php echo esc_attr( $product_wrapper_classes ); ?>">
		<?php
		do_action( 'eael_woocommerce_before_shop_loop_item' );
		if ( $settings['eael_wc_loop_hooks'] === 'yes' ) {
			do_action( 'woocommerce_before_shop_loop_item' );
		}
        ?>
        <div class="eael-product-wrap">
			<?php

            if ( $should_print_image_clickable ) {
	            echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
            }

            echo wp_kses_post( $product->get_image( $settings['eael_product_grid_image_size_size'], [ 'loading' => 'eager', 'class'=> 'attachment-woocommerce_thumbnail size-woocommerce_thumbnail wvs-archive-product-image' ] ) );
            if ( $should_print_image_clickable ) {
	            echo '</a>';
            }

			$product_data = apply_filters( 'eael/product-grid/content/reordering', $product_data, $settings, $product );
			unset( $product_data['id'] );
            if ( ! empty( $product_data ) ) {
	            foreach ( $product_data as $content ) {
		            if ( ! empty( $content ) ) {
			            echo wp_kses( $content, Helper::eael_allowed_tags(), Helper::eael_allowed_protocols() );
		            }
	            }
            }

			if ( $is_show_badge ){
				if ( ! $product->is_in_stock() ) {
					printf( '<span class="outofstock-badge ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">%s</span>', $stock_out_badge_text );
				} elseif ( $product->is_on_sale() ) {
					printf( '<span class="onsale ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">%s</span>', $sale_badge_text );
				}
			}

            ?>
            <?php
            woocommerce_template_loop_add_to_cart();
            if ( $should_print_compare_btn ) {
                Product_Grid::print_compare_button( $product->get_id() );
            }
            ?>
            <?php
	    if ( ! empty( $should_print_wishlist_btn ) ) {
		    echo '<div class="add-to-whishlist">';
		    echo do_shortcode('[yith_wcwl_add_to_wishlist]');
		    echo '</div>';
	    }
	    if ( $settings['eael_wc_loop_hooks'] === 'yes' ) {
				do_action( 'woocommerce_after_shop_loop_item' );
			}
			do_action( 'eael_woocommerce_after_shop_loop_item' );
			?>
        </div>

    </li>
	<?php
} else if ( $grid_style_preset == 'eael-product-overlay' ) {
	?>
    <li <?php post_class( "product {$product_wrapper_classes}" ); ?>>
		<?php
		do_action( 'eael_woocommerce_before_shop_loop_item' );
		if ( $settings['eael_wc_loop_hooks'] === 'yes' ) {
			do_action( 'woocommerce_before_shop_loop_item' );
		}
        ?>
        <div class="overlay">
			<?php
			if( $should_print_image_clickable ) {
				echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
			}
			echo $product->get_image( $settings['eael_product_grid_image_size_size'], [ 'loading' => 'eager' ] );
			if ( $should_print_image_clickable ) {
				echo '</a>';
			}
			?>
            <div class="button-wrap clearfix">
                <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="product-link"><span class="fas fa-link"></span></a>
				<?php
				woocommerce_template_loop_add_to_cart();
				if ( $should_print_compare_btn ) {
					Product_Grid::print_compare_button( $product->get_id(), 'icon' );
				}
				?>
				<?php
				if ( ! empty( $should_print_wishlist_btn ) ) {
					echo '<div class="add-to-whishlist">';
					echo do_shortcode('[yith_wcwl_add_to_wishlist]');
					echo '</div>';
				}
				?>
            </div>
        </div>
        <?php

        $product_data = apply_filters( 'eael/product-grid/content/reordering', $product_data, $settings, $product );
		unset( $product_data['id'] );
        if ( ! empty( $product_data ) ) {
	        foreach ( $product_data as $content ) {
		        if ( ! empty( $content ) ) {
					echo wp_kses( $content, Helper::eael_allowed_tags(), Helper::eael_allowed_protocols() );
		        }
	        }
        }

        if ( $is_show_badge ) {
	        if ( ! $product->is_in_stock() ) {
		        printf( '<span class="outofstock-badge ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">%s</span>', $stock_out_badge_text );
	        } elseif ( $product->is_on_sale() ) {
		        printf( '<span class="onsale ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">%s</span>', $sale_badge_text );
	        }
        }

        if ( $settings['eael_wc_loop_hooks'] === 'yes' ) {
	        do_action( 'woocommerce_after_shop_loop_item' );
        }
        do_action( 'eael_woocommerce_after_shop_loop_item' );
       ?>
    </li>
	<?php
} else if (($grid_style_preset == 'eael-product-preset-5') || ($grid_style_preset == 'eael-product-preset-6') || ($grid_style_preset == 'eael-product-preset-7')) {
	if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {
		?>
        <li <?php post_class( "product {$product_wrapper_classes}" ); ?>>
	        <?php
	        do_action( 'eael_woocommerce_before_shop_loop_item' );
	        if ( $settings['eael_wc_loop_hooks'] === 'yes' ) {
		        do_action( 'woocommerce_before_shop_loop_item' );
	        }
	        ?>
            <div class="eael-product-wrap">
                <div class="product-image-wrap">
                    <div class="image-wrap">
						<?php
						if ( $is_show_badge ) {
							echo( ! $product->is_in_stock() ? '<span class="eael-onsale outofstock ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">' . $stock_out_badge_text . '</span>' : ( $product->is_on_sale() ? '<span class="eael-onsale ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">' . $sale_badge_text . '</span>' : '' ) );
						}

						if( $should_print_image_clickable ) {
							echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
						}

						echo wp_kses_post( $product->get_image( $settings['eael_product_grid_image_size_size'], [ 'loading' => 'eager' ] ) );

						if( $should_print_image_clickable ) {
							echo '</a>';
						}
						?>
                    </div>
                    <div class="image-hover-wrap">
						<?php if($grid_style_preset == 'eael-product-preset-5'){ ?>
                            <ul class="icons-wrap block-style">
								<?php if( $should_print_quick_view ){?>
                                    <li class="eael-product-quick-view">
                                        <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
                                           class="eael-product-grid-open-popup open-popup-link">
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
								<?php
								if ( ! empty( $should_print_wishlist_btn ) ) {
									echo '<li class="add-to-whishlist">';
									echo do_shortcode('[yith_wcwl_add_to_wishlist]');
									echo '</li>';
								}
								?>
                                <li class="view-details"><?php echo '<a href="' . $product->get_permalink() . '"><i class="fas fa-link"></i></a>'; ?></li>
                                <li class="add-to-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
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
								<?php
								if ( ! empty( $should_print_wishlist_btn ) ) {
									echo '<li class="add-to-whishlist">';
									echo do_shortcode('[yith_wcwl_add_to_wishlist]');
									echo '</li>';
								}
								?>
								<?php if( $should_print_quick_view ){?>
                                    <li class="eael-product-quick-view">
                                        <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
                                           class="eael-product-grid-open-popup open-popup-link">
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
									woocommerce_template_loop_add_to_cart();
									?></li>
								<?php
								if ( $should_print_compare_btn ) {
									echo '<li class="add-to-compare">';
									Product_Grid::print_compare_button( $product->get_id(), 'icon' );
									echo '</li>';
								}
								?>
								<?php
								if ( ! empty( $should_print_wishlist_btn ) ) {
									echo '<li class="add-to-whishlist">';
									echo do_shortcode('[yith_wcwl_add_to_wishlist]');
									echo '</li>';
								}
								?>
								<?php if( $should_print_quick_view ){?>
                                    <li class="eael-product-quick-view">
                                        <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
                                           class="eael-product-grid-open-popup open-popup-link">
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
					$new_product_data['id'] = $product_data['id'];
                    if(($grid_style_preset == 'eael-product-preset-7') && $should_print_price ){
	                    $new_product_data['price'] = '<div class="eael-product-price">'.$product->get_price_html().'</div>';
					}

                    if ( $should_print_rating ) {
						$avg_rating = $product->get_average_rating();
						if( $avg_rating > 0 ){
							$new_product_data['ratings'] = wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
						} else {
							$new_product_data['ratings'] = Helper::eael_rating_markup( $product->get_average_rating(), $product->get_rating_count() );
						}
                    }
                    $new_product_data['title'] = $product_data['title'];

                    if(($grid_style_preset != 'eael-product-preset-7') && $should_print_price ){
	                    $new_product_data['price'] = '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                    }

                    $product_data = apply_filters( 'eael/product-grid/content/reordering', $new_product_data, $settings, $product );
					unset( $product_data['id'] );
                    if ( ! empty( $product_data ) ) {
	                    foreach ( $product_data as $content ) {
		                    if ( ! empty( $content ) ) {
								echo wp_kses( $content, Helper::eael_allowed_tags(), Helper::eael_allowed_protocols() );
		                    }
	                    }
                    }

                    ?>
                </div>
            </div>
			<?php
			if ( $settings['eael_wc_loop_hooks'] === 'yes' ) {
				do_action( 'woocommerce_after_shop_loop_item' );
			}
            do_action( 'eael_woocommerce_after_shop_loop_item' );
            ?>
        </li>
		<?php
	}
} else if ($grid_style_preset == 'eael-product-preset-8') {
	if ( true === wc_get_loop_product_visibility( $product->get_id() ) || $product->is_visible() ) {
		?>
        <li <?php post_class( "product {$product_wrapper_classes}" ); ?>>
            <div class="eael-product-wrap">
                <div class="product-image-wrap">
                    <div class="image-wrap">
						<?php
						if( $should_print_image_clickable ) {
							echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
						}

						if ( $is_show_badge ) {
							echo( ! $product->is_in_stock() ? '<span class="eael-onsale outofstock ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">' . $stock_out_badge_text . '</span>' : ( $product->is_on_sale() ? '<span class="eael-onsale ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">' . $sale_badge_text . '</span>' : '' ) );
						}

						echo $product->get_image($settings['eael_product_grid_image_size_size'], ['loading' => 'eager']);

						if( $should_print_image_clickable ) {
							echo '</a>';
						}
						?>
                    </div>
                    <div class="image-hover-wrap">
                        <ul class="icons-wrap over-box-style">
                            <li class="add-to-cart"><?php
								woocommerce_template_loop_add_to_cart(); ?>
                            </li>
							<?php
							if ( $should_print_compare_btn ) {
								echo '<li class="add-to-compare">';
								Product_Grid::print_compare_button( $product->get_id(), 'icon' );
								echo '</li>';
							}
							?>
							<?php
							if ( ! empty( $should_print_wishlist_btn ) ) {
								echo '<li class="add-to-whishlist">';
								echo do_shortcode('[yith_wcwl_add_to_wishlist]');
								echo '</li>';
							}
							?>
							<?php if( $should_print_quick_view ){?>
                                <li class="eael-product-quick-view">
                                    <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
                                       class="eael-product-grid-open-popup open-popup-link">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </li>
							<?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="product-details-wrap">
					<?php
					$_product_data['id'] = $product_data['id'];
                    if ( $should_print_price ) {
	                    $_product_data['price'] = '<div class="eael-product-price">'.$product->get_price_html().'</div>';
                    }

                    $_product_data['title'] = $product_data['title'];
                    $product_data = apply_filters( 'eael/product-grid/content/reordering', $_product_data, $settings, $product );
					unset( $product_data['id'] );
                    if ( ! empty( $product_data ) ) {
	                    foreach ( $product_data as $content ) {
		                    if ( ! empty( $content ) ) {
								echo wp_kses( $content, Helper::eael_allowed_tags(), Helper::eael_allowed_protocols() );
		                    }
	                    }
                    }
                    ?>
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
        <li class="product <?php echo esc_attr( "{$product_wrapper_classes} {$list_style_preset}" ) ?>">
            <div class="eael-product-wrap">
                <div class="product-image-wrap">
                    <div class="image-wrap">
						<?php
						if( $should_print_image_clickable ) {
							echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
						}
						if ( $is_show_badge ) {
							echo( ! $product->is_in_stock() ? '<span class="eael-onsale outofstock ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">' . $stock_out_badge_text . '</span>' : ( $product->is_on_sale() ? '<span class="eael-onsale ' . esc_attr( $sale_badge_preset . ' ' . $sale_badge_align ) . '">' . $sale_badge_text . '</span>' : '' ) );
						}
						echo wp_kses_post( $product->get_image( $settings['eael_product_grid_image_size_size'], [ 'loading' => 'eager' ] ) );

						if( $should_print_image_clickable ) {
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

                    $_product_data['id'] = $product_data['id'];
                    if ( $list_style_preset == 'eael-product-list-preset-2' ) {
	                    $_product_data['title']       = $product_data['title'];
	                    $_product_data['description'] = $should_print_excerpt ? '<div class="eael-product-excerpt">
                            <p>' . wp_trim_words( strip_shortcodes( get_the_excerpt() ), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator'] ) . '</p></div>' : '';

	                    $_product_data['price'] = $product_data['price'];

	                    $_product_data['ratings'] = $product_data['ratings'];

                    }
                    elseif ( $list_style_preset == 'eael-product-list-preset-3' ) {
                        if ( isset( $settings['enable_eael_layout_custom_ordering'] ) && $settings['enable_eael_layout_custom_ordering'] === 'yes' ){
	                        $_product_data['price']       = $product_data['price'];
	                        $_product_data['ratings']     = $product_data['ratings'];
	                        $_product_data['title']       = $product_data['title'];
	                        $_product_data['description'] = $should_print_excerpt ? '<div class="eael-product-excerpt">
                            <p>' . wp_trim_words( strip_shortcodes( get_the_excerpt() ), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator'] ) . '</p></div>' : '';
                        }
                        else{
	                        echo '<div class="price-wrap">';
	                        if ( $should_print_price ) {
		                        echo '<div class="eael-product-price">' . $product->get_price_html() . '</div>';
	                        }
	                        if ( $should_print_rating ) {
		                        echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() );
	                        }
	                        echo '</div>
                            <div class="title-wrap">
                                <div class="eael-product-title">
                                  <a href="' . esc_url( $product->get_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
	                        printf( '<%1$s>%2$s</%1$s>', $title_tag, Helper::eael_wp_kses( $product->get_title() ) );
	                        echo '</a>
                                </div>';
	                        if ( $should_print_excerpt ) {
		                        echo '<div class="eael-product-excerpt">';
		                        echo '<p>' . wp_trim_words( strip_shortcodes( get_the_excerpt() ? get_the_excerpt() :
				                        get_the_content() ), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator'] ) . '</p>';
		                        echo '</div>';
	                        }
	                        echo '</div>';
                        }
                    }
                    elseif ( $list_style_preset == 'eael-product-list-preset-4' ) {
	                    $_product_data['ratings']     = $product_data['ratings'];
	                    $_product_data['title']       = $product_data['title'];
	                    $_product_data['description'] = $should_print_excerpt ? '<div class="eael-product-excerpt">
                            <p>' . wp_trim_words( strip_shortcodes( get_the_excerpt() ), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator'] ) . '</p></div>' : '';
	                    $_product_data['price']       = $product_data['price'];

                    }
                    else {
	                    $_product_data['title']       = $product_data['title'];
	                    $_product_data['price']       = $product_data['price'];
	                    $_product_data['ratings']     = $product_data['ratings'];
	                    $_product_data['description'] = $should_print_excerpt ? '<div class="eael-product-excerpt">
							<p>' . wp_trim_words( strip_shortcodes( get_the_excerpt() ), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator'] ) . '</p></div>' : '';
                    }
                    $product_data = apply_filters( 'eael/product-grid/content/reordering', $_product_data, $settings, $product );
					unset( $product_data['id'] );
                    if ( ! empty( $product_data ) ) {
	                    foreach ( $product_data as $content ) {
		                    if ( ! empty( $content ) ) {
								echo wp_kses( $content, Helper::eael_allowed_tags(), Helper::eael_allowed_protocols() );
		                    }
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
                                <a id="eael_quick_view_<?php echo uniqid(); ?>" data-quickview-setting="<?php echo htmlspecialchars(json_encode($quick_view_setting),ENT_QUOTES); ?>"
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
}else {

	if($settings['eael_product_grid_rating']!='yes'){
		remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating',5);
	}

	add_action('woocommerce_before_shop_loop_item_title',function() use ( $stock_out_badge_text ){
		global $product;
		if ( ! $product->is_in_stock() ) {
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
			echo '<span class="outofstock-badge">'. $stock_out_badge_text .'</span>';
		}
	},9);

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
	add_filter( 'single_product_archive_thumbnail_size', function($size)use($thumb_size){
		global $eael_thumb_default;
		$eael_thumb_default = $size;
		return $thumb_size != '' ? $thumb_size : $size ;
	});

	wc_get_template_part( 'content', 'product' );

	add_filter( 'single_product_archive_thumbnail_size', function($size){
		global $eael_thumb_default;
		return !empty($eael_thumb_default) ? $eael_thumb_default : $size;
	});

	if ( $should_print_compare_btn ) {
		remove_action( 'woocommerce_after_shop_loop_item', [
			'\Essential_Addons_Elementor\Elements\Product_Grid',
			'print_compare_button',
		] );
	}
}

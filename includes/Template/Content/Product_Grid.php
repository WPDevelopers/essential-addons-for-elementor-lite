<?php

namespace Essential_Addons_Elementor\Template\Content;

if (!defined('ABSPATH')) {
	exit;
} // Exit if accessed directly

trait Product_Grid
{
	public static function render_template_($args, $settings)
	{
		$query = new \WP_Query($args);
		global $woocommerce_loop;

		$woocommerce_loop['paged']        = $args['paged'];
		$woocommerce_loop['total']        = $query->found_posts;
		$woocommerce_loop['post_count']   = $query->post_count;
		$woocommerce_loop['per_page']     = $settings['eael_product_grid_products_count'];
		$woocommerce_loop['total_pages']  = ceil( $query->found_posts / $settings['eael_product_grid_products_count'] );
		$woocommerce_loop['current_page'] = $args['paged'];

		ob_start();

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();

				$product = wc_get_product(get_the_ID());

				if ($settings['eael_product_grid_style_preset'] == 'eael-product-simple' || $settings['eael_product_grid_style_preset'] == 'eael-product-reveal') {
					echo '<li class="product">
                        <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                            ' . $product->get_image('woocommerce_thumbnail') . '
                            <h2 class="woocommerce-loop-product__title">' . $product->get_title() . '</h2>
                            ' . ($settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html($product->get_average_rating(), $product->get_rating_count())) . '
                            '.( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="outofstock-badge">'.__('Stock ', 'essential-addons-for-elementor-lite'). '<br />' . __('Out', 'essential-addons-for-elementor-lite').'</span>' : ($product->is_on_sale() ? '<span class="onsale">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') ).'
                            <span class="price">' . $product->get_price_html() . '</span>
                        </a>';
					woocommerce_template_loop_add_to_cart();
					echo '</li>';
				} else if ($settings['eael_product_grid_style_preset'] == 'eael-product-overlay') {
					echo '<li class="product">
                        <div class="overlay">
                            ' . $product->get_image('woocommerce_thumbnail') . '
                            <div class="button-wrap clearfix">
                                <a href="' . $product->get_permalink() . '" class="product-link"><span class="fas fa-link"></span></a>';
					woocommerce_template_loop_add_to_cart();
					echo '</div>
                        </div>
                        <h2 class="woocommerce-loop-product__title">' . $product->get_title() . '</h2>
                        ' . ($settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html($product->get_average_rating(), $product->get_rating_count())) . '
                        ' . ($product->is_on_sale() ? '<span class="onsale">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') . '
                        <span class="price">' . $product->get_price_html() . '</span>
                    </li>';
				} else if (($settings['eael_product_grid_style_preset'] == 'eael-product-preset-5') || ($settings['eael_product_grid_style_preset'] == 'eael-product-preset-6') || ($settings['eael_product_grid_style_preset'] == 'eael-product-preset-7')) { ?>
					<li class="product">
						<div class="eael-product-wrap">
							<div class="product-image-wrap">
								<div class="image-wrap">
									<?php echo ($product->is_on_sale() ? '<span class="eael-onsale">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '');
									echo $product->get_image('woocommerce_thumbnail');
									?>
								</div>
								<div class="image-hover-wrap">
									<?php if($settings['eael_product_grid_style_preset'] == 'eael-product-preset-5'){?>
										<ul class="icons-wrap block-style">
											<li class="quick-view"><a href="#test<?php echo $product->get_id(); ?>"
                                                    class="open-popup-link"><i class="fas fa-eye"></i></a></li>
											<li class="add-to-cart"><?php woocommerce_template_loop_add_to_cart();
												?></li>
											<li class="view-details"><?php echo '<a href="' . $product->get_permalink() . '"><i class="fas fa-link"></i></a>'; ?></li>
										</ul>
									<?php } elseif ($settings['eael_product_grid_style_preset'] == 'eael-product-preset-7') { ?>
										<ul class="icons-wrap block-box-style">
											<li class="add-to-cart"><?php
												woocommerce_template_loop_add_to_cart(); ?></li>
											<li class="quick-view"><a href="#test<?php echo
												$product->get_id(); ?>" class="open-popup-link"><i class="fas fa-eye"></i></a></li>
											<li class="view-details"><?php echo '<a href="' . $product->get_permalink
													() . '"><i class="fas fa-link"></i></a>'; ?></li>
										</ul>
									<?php } else { ?>
										<ul class="icons-wrap box-style">
											<li class="add-to-cart"><?php
												woocommerce_template_loop_add_to_cart(); ?></li>
											<li class="quick-view"><a href="#test<?php echo
												$product->get_id(); ?>" class="open-popup-link"><i class="fas fa-eye"></i></a></li>
											<li class="view-details" title="Details"><?php echo '<a href="' . $product->get_permalink
													() . '"><i class="fas fa-link"></i></a>'; ?></li>
										</ul>
									<?php }
									self::eael_product_quick_view( $product, $settings );
									?>
								</div>
							</div>
							<div class="product-details-wrap">
								<?php echo ($settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html
								($product->get_average_rating(), $product->get_rating_count())); ?>
								<div class="eael-product-title"><h2><?php echo $product->get_title(); ?></h2></div>
								<div class="eael-product-price"><?php echo $product->get_price_html(); ?></div>
							</div>
						</div>
					</li>
                    <?php } else if ($settings['eael_product_grid_style_preset'] == 'eael-product-preset-8') { ?>
                    <li class="product">
                        <div class="eael-product-wrap">
                            <div class="product-image-wrap">
                                <div class="image-wrap">
									<?php
                                    echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
                                        echo ($product->is_on_sale() ? '<span class="eael-onsale style-5">' . __('Sale!',
                                                'essential-addons-for-elementor-lite') . '</span>' : '');
                                        echo $product->get_image('woocommerce_thumbnail');
                                    echo '</a>';
									?>
                                </div>
                                <div class="image-hover-wrap">
                                    <ul class="icons-wrap over-box-style">
                                        <li class="add-to-cart"><?php
                                            woocommerce_template_loop_add_to_cart(); ?></li>
                                        <li class="quick-view"><a href="#test<?php echo
                                            $product->get_id(); ?>" class="open-popup-link"><i class="fas fa-eye"></i></a></li>
                                    </ul>
									<?php self::eael_product_quick_view( $product, $settings ); ?>
                                </div>
                            </div>
                            <div class="product-details-wrap">
                                <div class="eael-product-price"><?php echo $product->get_price_html(); ?></div>xaq
                                <div class="eael-product-title">
                                    <?php
                                    echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                            <h2>'. $product->get_title() .'</h2></a>';
                                    ?>
                                </div>
                            </div>
                        </div>
                    </li>
				<?php } else if(($settings['eael_product_list_style_preset'] == 'eael-product-list-preset-1') ||
                                ($settings['eael_product_list_style_preset'] == 'eael-product-list-preset-2') ||
				                ($settings['eael_product_list_style_preset'] == 'eael-product-list-preset-3') ||
				                ($settings['eael_product_list_style_preset'] == 'eael-product-list-preset-4')){ ?>
					<li class="product <?php echo $settings['eael_product_list_style_preset'];?>">
						<div class="eael-product-wrap">
							<div class="product-image-wrap">
								<div class="image-wrap">
									<?php
									echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
									echo ($product->is_on_sale() ? '<span class="eael-onsale '.$settings['eael_product_sale_badge_preset'].'">' . __('Sale!',
											'essential-addons-for-elementor-lite') . '</span>' : '');
									echo $product->get_image($settings['eael_product_grid_image_size']);
									echo '</a>';
									?>
								</div>
							</div>
							<div class="product-details-wrap">
								<?php
                                if ($settings['eael_product_list_style_preset'] == 'eael-product-list-preset-2') {
	                                echo '<div class="eael-product-title">
                                            <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                <h2>'. $product->get_title() .'</h2>
                                            </a>
                                          </div>';
                                         if ( ($settings['eael_product_grid_excerpt'] == true) && has_excerpt() ) {
	                                         echo '<div class="eael-product-excerpt">';
	                                         echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() :
			                                         get_the_content()), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator']) . '</p>';
	                                         echo '</div>';
                                         }
                                    echo '<div class="eael-product-price">'.$product->get_price_html().'</div>'.
	                                     ($settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html
	                                     ($product->get_average_rating(), $product->get_rating_count()));
                                } elseif ($settings['eael_product_list_style_preset'] == 'eael-product-list-preset-3') {
								    echo '<div class="price-wrap">
									        <div class="eael-product-price">'.$product->get_price_html().'</div>'.
									        ($settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html
									        ($product->get_average_rating(), $product->get_rating_count())).
                                          '</div>
	                                      <div class="title-wrap">
                                              <div class="eael-product-title">
                                                <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                    <h2>'. $product->get_title() .'</h2>
                                                </a>
                                              </div>';
                                        if ( ($settings['eael_product_grid_excerpt'] == true) && has_excerpt() ) {
                                            echo '<div class="eael-product-excerpt">';
                                            echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() :
                                                    get_the_content()), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator']) . '</p>';
                                            echo '</div>';
                                        };
                                        echo '</div>';
								} elseif ($settings['eael_product_list_style_preset'] == 'eael-product-list-preset-4') {
									echo ($settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html
									($product->get_average_rating(), $product->get_rating_count())).
                                        '<div class="eael-product-title">
                                            <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                <h2>'. $product->get_title() .'</h2>
                                            </a>
                                          </div>';
                                         if ( ($settings['eael_product_grid_excerpt'] == true) && has_excerpt() ) {
	                                         echo '<div class="eael-product-excerpt">';
	                                         echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() :
			                                         get_the_content()), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator']) . '</p>';
	                                         echo '</div>';
                                         }
                                    echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
								} else {
								    echo '<div class="eael-product-title">
                                            <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                                                <h2>'. $product->get_title() .'</h2>
                                            </a>
                                          </div>
                                          <div class="eael-product-price">'.$product->get_price_html().'</div>'.
                                    ($settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html
                                    ($product->get_average_rating(), $product->get_rating_count()));
									if ( ($settings['eael_product_grid_excerpt'] == true) && has_excerpt() ) {
										echo '<div class="eael-product-excerpt">';
										echo '<p>' . wp_trim_words(strip_shortcodes(get_the_excerpt() ? get_the_excerpt() :
												get_the_content()), $settings['eael_product_grid_excerpt_length'], $settings['eael_product_grid_excerpt_expanison_indicator']) . '</p>';
										echo '</div>';
									};
								}
								?>

                                <ul class="icons-wrap <?php echo $settings['eael_product_action_buttons_preset'] ;?>">
                                    <li class="add-to-cart"><?php
										woocommerce_template_loop_add_to_cart(); ?></li>
                                    <?php if( $settings['eael_product_grid_quick_view'] == true ){?>
	                                    <li class="quick-view">
                                            <a href="#test<?php echo $product->get_id(); ?>" class="open-popup-link">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
								<?php
                                if( $settings['eael_product_grid_quick_view'] == true ){
                                    self::eael_product_quick_view( $product, $settings );
                                }
                                ?>
							</div>
						</div>
					</li>
				<?php }else {
					wc_get_template_part('content', 'product');
				}
			}

		} else {
			_e('<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite');
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

	public static function eael_pagination () {
		$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
		$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
		$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
		$format  = isset( $format ) ? $format : '';

		if ( $total <= 1 ) {
			return;
		}

		$html = '<nav class="eael-woo-pagination">';
		$html .= paginate_links(
			apply_filters(
				'eael_woo_pagination_args', array( // WPCS: XSS ok.
					'base'      => $base,
					'format'    => $format,
					'add_args'  => false,
					'current'   => max( 1, $current ),
					'total'     => $total,
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'type'      => 'list',
					'end_size'  => 3,
					'mid_size'  => 3,
				)
			)
		);
		$html .= '</nav>';

		return $html;
	}

	protected static function eael_product_quick_view ($product, $settings) { ?>
		<div id="test<?php echo $product->get_id(); ?>" class="eael-product-popup mfp-hide woocommerce">
            <div id="product-<?php the_ID(); ?>" <?php post_class( 'product' ); ?>>
                <div class="eael-product-image-wrap">
                    <?php
                    echo ($product->is_on_sale() ? '<span class="eael-onsale '.$settings['eael_product_sale_badge_preset'].'">' . __('Sale!',
		                    'essential-addons-for-elementor-lite') . '</span>' : '');
                    do_action( 'ea_woo_single_product_image' );
                    ?>
                </div>
                <div class="eael-product-details-wrap">
                    <?php do_action( 'ea_woo_single_product_summary' ); ?>
                </div>
            </div>
        </div>
	<?php }

	/**
	 * Added all actions
	 */
	public function ea_woo_checkout_add_actions() {

		// Image.
//		add_action( 'ea_woo_single_product_image', 'woocommerce_show_product_sale_flash', 10 );
		add_action( 'ea_woo_single_product_image', 'woocommerce_show_product_images', 20 );

		// Summary.
		add_action( 'ea_woo_single_product_summary', 'woocommerce_template_single_title', 5 );
		add_action( 'ea_woo_single_product_summary', 'woocommerce_template_single_rating', 10 );
		add_action( 'ea_woo_single_product_summary', 'woocommerce_template_single_price', 15 );
		add_action( 'ea_woo_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		add_action( 'ea_woo_single_product_summary', 'woocommerce_template_single_add_to_cart', 25 );
		add_action( 'ea_woo_single_product_summary', 'woocommerce_template_single_meta', 30 );

		add_action( 'ea_woo_before_product_loop', 'woocommerce_output_all_notices', 30 );

	}
}

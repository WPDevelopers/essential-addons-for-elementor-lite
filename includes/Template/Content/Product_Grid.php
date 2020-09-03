<?php

namespace Essential_Addons_Elementor\Template\Content;

if (!defined('ABSPATH')) {
	exit;
} // Exit if accessed directly

trait Product_Grid
{
	public static function render_template_($args, $settings)
	{
		$widget_id = $settings['eael_widget_id'];

		$query = new \WP_Query($args);
//		global $woocommerce_loop;
//
//		$woocommerce_loop['paged']        = $args['paged'];
//		$woocommerce_loop['total']        = $query->found_posts;
//		$woocommerce_loop['post_count']   = $query->post_count;
//		$woocommerce_loop['per_page']     = $settings['eael_product_grid_products_count'];
//		$woocommerce_loop['total_pages']  = ceil( $query->found_posts / $settings['eael_product_grid_products_count'] );
//		$woocommerce_loop['current_page'] = $args['paged'];

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
                            '.( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="outofstock-badge">'.__('Stock ', 'essential-addons-for-elementor-lite'). '<br />' . __('Out', 'essential-addons-for-elementor-lite').'</span>' : ($product->is_on_sale() ? '<span class="onsale">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') ).
					     ($settings['eael_product_grid_price'] != 'yes' ? '' : '<span class="price">' . $product->get_price_html() . '</span>') .'
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
                        ' . ($product->is_on_sale() ? '<span class="onsale">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') .
					     ($settings['eael_product_grid_price'] != 'yes' ? '' : '<span class="price">' . $product->get_price_html() . '</span>') .'
                    </li>';
				} else if (($settings['eael_product_grid_style_preset'] == 'eael-product-preset-5') || ($settings['eael_product_grid_style_preset'] == 'eael-product-preset-6') || ($settings['eael_product_grid_style_preset'] == 'eael-product-preset-7')) { ?>
                    <li <?php post_class( 'product' ); ?>>
                        <div class="eael-product-wrap">
                            <div class="product-image-wrap">
                                <div class="image-wrap">
									<?php
									echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$settings['eael_product_sale_badge_preset'].' '.$settings['eael_product_sale_badge_alignment'].'">'.__('Stock ', 'essential-addons-for-elementor-lite'). '<br />' . __('Out', 'essential-addons-for-elementor-lite').'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$settings['eael_product_sale_badge_preset'].' '.$settings['eael_product_sale_badge_alignment'].'">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') );
									echo $product->get_image($settings['eael_product_grid_image_size']);
									?>
                                </div>
                                <div class="image-hover-wrap">
									<?php if($settings['eael_product_grid_style_preset'] == 'eael-product-preset-5'){?>
                                        <ul class="icons-wrap block-style">
											<?php if( $settings['eael_product_grid_quick_view'] == true ){?>
                                                <li class="quick-view">
                                                    <a href="#eaproduct<?php echo $widget_id.$product->get_id(); ?>"
                                                       class="open-popup-link">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </li>
											<?php } ?>
                                            <li class="add-to-cart"><?php woocommerce_template_loop_add_to_cart();
												?></li>
                                            <li class="view-details"><?php echo '<a href="' . $product->get_permalink() . '"><i class="fas fa-link"></i></a>'; ?></li>
                                        </ul>
									<?php } elseif ($settings['eael_product_grid_style_preset'] == 'eael-product-preset-7') { ?>
                                        <ul class="icons-wrap block-box-style">
                                            <li class="add-to-cart"><?php
												woocommerce_template_loop_add_to_cart(); ?></li>
											<?php if( $settings['eael_product_grid_quick_view'] == true ){?>
                                                <li class="quick-view">
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
											<?php if( $settings['eael_product_grid_quick_view'] == true ){?>
                                                <li class="quick-view">
                                                    <a href="#eaproduct<?php echo $widget_id.$product->get_id(); ?>"
                                                       class="open-popup-link">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </li>
											<?php } ?>
                                            <li class="view-details" title="Details"><?php echo '<a href="' . $product->get_permalink
													() . '"><i class="fas fa-link"></i></a>'; ?></li>
                                        </ul>
									<?php }
									if( $settings['eael_product_grid_quick_view'] == true ){
										self::eael_product_quick_view( $product, $settings, $widget_id );
									}
									?>
                                </div>
                            </div>
                            <div class="product-details-wrap">
								<?php
								if(($settings['eael_product_grid_style_preset'] == 'eael-product-preset-7') && ( $settings['eael_product_grid_price'] == true)){
									echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
								}
								echo ($settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html
								($product->get_average_rating(), $product->get_rating_count())); ?>
                                <div class="eael-product-title"><h2><?php echo $product->get_title(); ?></h2></div>
								<?php if(($settings['eael_product_grid_style_preset'] != 'eael-product-preset-7') && ( $settings['eael_product_grid_price'] == true)){
									echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
								}?>
                            </div>
                        </div>
                    </li>
				<?php } else if ($settings['eael_product_grid_style_preset'] == 'eael-product-preset-8') { ?>
                    <li <?php post_class( 'product' ); ?>>
                        <div class="eael-product-wrap">
                            <div class="product-image-wrap">
                                <div class="image-wrap">
									<?php
									echo '<a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
									echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$settings['eael_product_sale_badge_preset'].' '.$settings['eael_product_sale_badge_alignment'].'">'.__('Stock ', 'essential-addons-for-elementor-lite'). '<br />' . __('Out', 'essential-addons-for-elementor-lite').'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$settings['eael_product_sale_badge_preset'].' '.$settings['eael_product_sale_badge_alignment'].'">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') );
									echo $product->get_image($settings['eael_product_grid_image_size']);
									echo '</a>';
									?>
                                </div>
                                <div class="image-hover-wrap">
                                    <ul class="icons-wrap over-box-style">
                                        <li class="add-to-cart"><?php
											woocommerce_template_loop_add_to_cart(); ?></li>
										<?php if( $settings['eael_product_grid_quick_view'] == true ){?>
                                            <li class="quick-view">
                                                <a href="#eaproduct<?php echo $widget_id.$product->get_id(); ?>"
                                                   class="open-popup-link">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </li>
										<?php } ?>
                                    </ul>
									<?php
									if( $settings['eael_product_grid_quick_view'] == true ){
										self::eael_product_quick_view( $product, $settings, $widget_id );
									}
									?>
                                </div>
                            </div>
                            <div class="product-details-wrap">
								<?php
								if ( $settings['eael_product_grid_price'] == true) {
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
									echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="eael-onsale outofstock '.$settings['eael_product_sale_badge_preset'].' '.$settings['eael_product_sale_badge_alignment'].'">'.__('Stock ', 'essential-addons-for-elementor-lite'). '<br />' . __('Out', 'essential-addons-for-elementor-lite').'</span>' : ($product->is_on_sale() ? '<span class="eael-onsale '.$settings['eael_product_sale_badge_preset'].' '.$settings['eael_product_sale_badge_alignment'].'">' . __('Sale!', 'essential-addons-for-elementor-lite') . '</span>' : '') );
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
									if ( $settings['eael_product_grid_price'] == true) {
										echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
									}
									echo ($settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html
									($product->get_average_rating(), $product->get_rating_count()));
								} elseif ($settings['eael_product_list_style_preset'] == 'eael-product-list-preset-3') {
									echo '<div class="price-wrap">'.
									     ( $settings['eael_product_grid_price'] != 'yes' ? '' : '<div class="eael-product-price">'.$product->get_price_html().'</div>').

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
									if ( $settings['eael_product_grid_price'] == true) {
										echo '<div class="eael-product-price">'.$product->get_price_html().'</div>';
									}

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
                                            <a href="#eaproduct<?php echo $widget_id.$product->get_id(); ?>"
                                               class="open-popup-link">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </li>
									<?php } ?>
                                </ul>
								<?php
								if( $settings['eael_product_grid_quick_view'] == true ){
									self::eael_product_quick_view( $product, $settings, $widget_id );
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

	public static function eael_pagination ($args, $settings) {
		$args['posts_per_page'] = -1;

		$pagination_Query = new \WP_Query($args);
		$pagination_Count = count($pagination_Query->posts);
		$paginationLimit = $settings['eael_product_grid_products_count'] ?: 4;
		$pagination_Paginationlist = ceil($pagination_Count/$paginationLimit);
		$last = ceil( $pagination_Paginationlist );
//		$paginationtaxname = 'product_cat';
//		$paginationCatName = $settings['eael_product_grid_categories'] ?: '';

		$widget_id = $settings['eael_widget_id'];
		$adjacents = "2";
		$setPagination = "";
		if( $pagination_Paginationlist > 0 ){

			$setPagination .="<nav class='eael-woo-pagination'>";
			$setPagination .="<ul class='page-numbers'>";
			$setPagination .="<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-pnumber='1' data-plimit='$paginationLimit'>Prev</a></li>";

			if ( $pagination_Paginationlist < 7 + ($adjacents * 2) ){

				for( $pagination=1; $pagination<=$pagination_Paginationlist; $pagination++){

					if( $pagination ==  0 || $pagination ==  1 ){ $active="current"; }else{ $active=""; }
					$setPagination .="<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$pagination' data-plimit='$paginationLimit'>$pagination</a></li>";

				}

			} else if ( $pagination_Paginationlist > 5 + ($adjacents * 2) ){

				for( $pagination=1; $pagination <= 4 + ($adjacents * 2); $pagination++){
					if( $pagination ==  0 || $pagination ==  1 ){ $active="current"; }else{ $active=""; }

					$setPagination .="<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$pagination' data-plimit='$paginationLimit'>$pagination</a></li>";
				}

				$setPagination .="<li class='pagitext dots'>...</li>";
				$setPagination .="<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$pagination' data-plimit='$paginationLimit'>$pagination</a></li>";

			} else {

				for( $pagination=1; $pagination<=$pagination_Paginationlist; $pagination++){
					if( $pagination ==  0 || $pagination ==  1 ){ $active="current"; }else{ $active=""; }
					$setPagination .="<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$pagination' data-plimit='$paginationLimit'>$pagination</a></li>";
				}

			}
			$setPagination .="<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-pnumber='2' data-plimit='$paginationLimit'>Next</a></li>";
			$setPagination .="</ul>";
			$setPagination .="</nav>";

			return $setPagination;
		}
	}

	protected static function eael_product_quick_view ($product, $settings, $widget_id) { ?>
        <div id="eaproduct<?php echo $widget_id.$product->get_id(); ?>" class="eael-product-popup
		eael-product-zoom-in woocommerce">
            <div class="eael-product-modal-bg"></div>
            <div class="eael-product-popup-details">
                <div id="product-<?php the_ID(); ?>" <?php post_class( 'product' ); ?>>
                    <div class="eael-product-image-wrap">
						<?php
						echo ($product->is_on_sale() ? '<span class="eael-onsale '.$settings['eael_product_sale_badge_preset'].'">' . __('Sale!',
								'essential-addons-for-elementor-lite') . '</span>' : '');
						do_action( 'eael_woo_single_product_image' );
						?>
                    </div>
                    <div class="eael-product-details-wrap">
						<?php do_action( 'eael_woo_single_product_summary' ); ?>
                    </div>
                </div>
                <button class="eael-product-popup-close"><i class="fas fa-times"></i></button>
            </div>

        </div>
	<?php }

	public function eael_fix_query_offset( &$query ) {
		if ( ! empty( $query->query_vars['offset_to_fix'] ) ) {
			if ( $query->is_paged ) {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'] + ( ( $query->query_vars['paged'] - 1 ) * $query->query_vars['posts_per_page'] );
			} else {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'];
			}
		}
	}
	/**
	 * Query Found Posts Fix.
	 */
	public function eael_fix_query_found_posts( $found_posts, $query ) {
		$offset_to_fix = $query->get( 'offset_to_fix' );
		if ( $offset_to_fix ) {
			$found_posts -= $offset_to_fix;
		}
		return $found_posts;
	}

	public function eael_avoid_redirect_to_single_page( $value ) {
		return '';
	}

	/**
	 * Rating Markup
	 */
	public function eael_rating_markup( $html, $rating, $count ) {

		if ( 0 == $rating ) {
			$html  = '<div class="star-rating">';
			$html .= wc_get_star_rating_html( $rating, $count );
			$html .= '</div>';
		}
		return $html;
	}

	public function eael_product_grid_script(){
		if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
			\WC_Frontend_Scripts::load_scripts();
			if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
				wp_enqueue_script( 'zoom' );
			}
			if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
				wp_enqueue_script( 'flexslider' );
			}
			if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
				wp_enqueue_script( 'photoswipe-ui-default' );
				wp_enqueue_style( 'photoswipe-default-skin' );
				if ( has_action( 'wp_footer', 'woocommerce_photoswipe' ) === false ) {
					add_action( 'wp_footer', 'woocommerce_photoswipe', 15 );
				}
			}

			wp_enqueue_script( 'wc-single-product' );
		}
	}

	/**
	 * Added all actions
	 */
	public function eael_woo_checkout_add_actions($settings) {

//		if ( '' !== $settings['pagination_type'] ) {
//			add_action( 'pre_get_posts', [ $this, 'eael_fix_query_offset' ], 1 );
//			add_filter( 'found_posts', [ $this, 'eael_fix_query_found_posts' ], 1, 2 );
//		}

		add_filter( 'woocommerce_add_to_cart_form_action', array( $this, 'eael_avoid_redirect_to_single_page' ), 10,
			1 );

		// Image.
//		add_action( 'eael_woo_single_product_image', 'woocommerce_show_product_sale_flash', 10 );
		add_action( 'eael_woo_single_product_image', 'woocommerce_show_product_images', 20 );

		// Summary.
		add_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_title', 5 );
		add_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_rating', 10 );
		add_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_price', 15 );
		add_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		add_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_add_to_cart', 25 );
		add_action( 'eael_woo_single_product_summary', 'woocommerce_template_single_meta', 30 );

		add_action( 'eael_woo_before_product_loop', 'woocommerce_output_all_notices', 30 );

		add_action( 'wp_footer', [ $this, 'eael_product_grid_script' ] );

		add_filter( 'woocommerce_product_get_rating_html', [ $this, 'eael_rating_markup' ], 10, 3 );
	}
}


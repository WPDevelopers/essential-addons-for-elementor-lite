<?php

namespace Essential_Addons_Elementor\Template\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

trait Product_Grid {
	public static function render_template_( $args, $settings ) {
		$query = new \WP_Query( $args );

		ob_start();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$product = wc_get_product( get_the_ID() );

				if ( $settings['eael_product_grid_style_preset'] == 'eael-product-simple' || $settings['eael_product_grid_style_preset'] == 'eael-product-reveal' ) {
					echo '<li class="product">
                        <a href="' . $product->get_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
                            ' . $product->get_image( 'woocommerce_thumbnail' ) . '
                            <h2 class="woocommerce-loop-product__title">' . $product->get_title() . '</h2>
                            ' . ( $settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) ) . '
                            ' . ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="outofstock-badge">' . __( 'Stock ', 'essential-addons-for-elementor-lite' ) . '<br />' . __( 'Out', 'essential-addons-for-elementor-lite' ) . '</span>' : ( $product->is_on_sale() ? '<span class="onsale">' . __( 'Sale!', 'essential-addons-for-elementor-lite' ) . '</span>' : '' ) ) . '
                            <span class="price">' . $product->get_price_html() . '</span>
                        </a>';
					woocommerce_template_loop_add_to_cart();
					self::print_compare_button( $product->get_id() );
					echo '</li>';
				} else if ( $settings['eael_product_grid_style_preset'] == 'eael-product-overlay' ) {
					echo '<li class="product">
                        <div class="overlay">
                            ' . $product->get_image( 'woocommerce_thumbnail' ) . '
                            <div class="button-wrap clearfix">
                                <a href="' . $product->get_permalink() . '" class="product-link"><span class="fas fa-link"></span></a>';
					woocommerce_template_loop_add_to_cart();
					self::print_compare_button( $product->get_id() );

					echo '</div>
                        </div>
                        <h2 class="woocommerce-loop-product__title">' . $product->get_title() . '</h2>
                        ' . ( $settings['eael_product_grid_rating'] != 'yes' ? '' : wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) ) . '
                        ' . ( $product->is_on_sale() ? '<span class="onsale">' . __( 'Sale!', 'essential-addons-for-elementor-lite' ) . '</span>' : '' ) . '
                        <span class="price">' . $product->get_price_html() . '</span>
                    </li>';
				} else {
					wc_get_template_part( 'content', 'product' );
				}
			}
		} else {
			printf( '<p class="no-posts-found">%</p>', __( 'No products found!', 'essential-addons-for-elementor-lite' ) );

		}

		wp_reset_postdata();

		?>
        <style>
            .eael-wcpc-modal {
                position: absolute;
                z-index: 9999999;
                width: 100%;
                height: 100vh;
                background: #fff;
                display: flex;
                justify-content: center;
                justify-items: center;
            }
            .modal__content{
                max-width: 100%;
            }
            .wcpc-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                z-index: 10;
                background: rgba(0, 0, 0, 0.5);
                pointer-events: none;
            }
            .wcpc-overlay, .eael-wcpc-modal{
                visibility: hidden;
                opacity: 0;
                transition: all .5s ease;
            }

            .close-modal {
                position: absolute;
                top: -10px;
                right: -10px;
                cursor: pointer;
                display: block;
                border-radius: 50%;
                color: #fff;
                background: #000000;
                height: 30px;
                width: 30px;
                font-weight: bold;
                text-align: center;
                line-height: 26px;
            }
        </style>
        <div class="eael-wcpc-modal">
            <span class="close-modal" title="Close">x</span>
            <div class="modal__content">
				<?php self::test_table(); ?>
            </div>
        </div>

        <script>
            (function () {
                const overlay = document.createElement("div");
                overlay.classList.add('wcpc-overlay');
                overlay.setAttribute('id', 'wcpc-overlay');
                const body = document.getElementsByTagName('body')[0];
                body.appendChild(overlay);
                // Vanilla JS version
                const overlayNode = document.getElementById('wcpc-overlay');
                //const openBtn = document.getElementsByClassName('open-modal')[0];
                const openBtns = document.getElementsByClassName('eael-wc-compare');
                //const closeBtn = document.getElementsByClassName('close-modal')[0];
                const closeBtns = document.getElementsByClassName('close-modal');
                const modal = document.getElementsByClassName('eael-wcpc-modal')[0];
                if (openBtns.length){
                    for (let i = 0; i < openBtns.length ; i++) {
                        openBtns[i].addEventListener('click', function (event) {
                            modal.style.visibility = 'visible';
                            modal.style.opacity = '1';
                            overlayNode.style.visibility = 'visible';
                            overlayNode.style.opacity = '1';
                        })
                    }
                }

                if (closeBtns.length){
                    for (let i = 0; i < closeBtns.length ; i++) {
                        closeBtns[i].addEventListener('click', function (event) {
                            modal.style.visibility = 'hidden';
                            modal.style.opacity = '0';
                            overlayNode.style.visibility = 'hidden';
                            overlayNode.style.opacity = '0';
                        });
                    }
                }


            })();
        </script>
		<?php
		return ob_get_clean();
	}

	public static function test_table() {
		?>
        <div class="eael-wcpc-wrapper woocommerce  custom theme-4">
            <table class="eael-wcpc-table table-responsive">
                <tbody>
                <tr class="image">
                    <th class="thead first-th">
                        <div>
                            <h1 class="wcpc-title">Compare Products</h1>
                        </div>
                    </th>

                    <td class="odd col_0 product_306 ">
                                    <span>
                                    <span class="img-inner"><img src="https://wpdevsocial.com/wp-content/uploads/2020/09/vneck-tee-2-300x300.jpg" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" srcset="https://wpdevsocial.com/wp-content/uploads/2020/09/vneck-tee-2-300x300.jpg 300w, https://wpdevsocial.com/wp-content/uploads/2020/09/vneck-tee-2-100x100.jpg 100w, https://wpdevsocial.com/wp-content/uploads/2020/09/vneck-tee-2-600x599.jpg 600w, https://wpdevsocial.com/wp-content/uploads/2020/09/vneck-tee-2-150x150.jpg 150w, https://wpdevsocial.com/wp-content/uploads/2020/09/vneck-tee-2-768x767.jpg 768w, https://wpdevsocial.com/wp-content/uploads/2020/09/vneck-tee-2.jpg 801w" sizes="(max-width: 300px) 100vw, 300px" width="300" height="300"><p class="product-title">V-Neck T-Shirt</p><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>15</span> – <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>20</span></span>                                    </span>
                    </td>

                    <td class="even col_1 product_317 featured">
                                    <span>
                                    <span class="img-inner"><span class="ribbon">Best</span><img src="https://wpdevsocial.com/wp-content/uploads/2020/09/watch3.png" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" width="103" height="160"><p class="product-title">Polo</p><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>20</span></span>                                    </span>
                    </td>

                    <td class="odd col_2 product_318 ">
                                    <span>
                                    <span class="img-inner"><img src="https://wpdevsocial.com/wp-content/uploads/2020/09/watch2.png" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" loading="lazy" width="102" height="160"><p class="product-title">Album</p><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>15</span></span>                                    </span>
                    </td>


                </tr>

                <tr class="title">
                    <th class="thead ">
                        <div>
                            <svg class="icon right-arrow" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 492.004 492.004" xml:space="preserve">
            <path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
                    c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
                    v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
                    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
                    l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"></path>
        </svg>
                            <span class="field-name">Title</span>
                        </div>
                    </th>

                    <td class="odd col_0 product_306 ">
                                    <span>
                                    V-Neck T-Shirt                                    </span>
                    </td>

                    <td class="even col_1 product_317 featured">
                                    <span>
                                    Polo                                    </span>
                    </td>

                    <td class="odd col_2 product_318 ">
                                    <span>
                                    Album                                    </span>
                    </td>


                </tr>

                <tr class="price">
                    <th class="thead ">
                        <div>
                            <svg class="icon right-arrow" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 492.004 492.004" xml:space="preserve">
            <path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
                    c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
                    v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
                    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
                    l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"></path>
        </svg>
                            <span class="field-name">Price</span>
                        </div>
                    </th>

                    <td class="odd col_0 product_306 ">
                                    <span>
                                    <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>15</bdi></span> – <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>20</bdi></span>                                    </span>
                    </td>

                    <td class="even col_1 product_317 featured">
                                    <span>
                                    <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>20</bdi></span>                                    </span>
                    </td>

                    <td class="odd col_2 product_318 ">
                                    <span>
                                    <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>15</bdi></span>                                    </span>
                    </td>


                </tr>

                <tr class="description">
                    <th class="thead ">
                        <div>
                            <svg class="icon right-arrow" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 492.004 492.004" xml:space="preserve">
            <path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
                    c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
                    v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
                    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
                    l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"></path>
        </svg>
                            <span class="field-name">Description</span>
                        </div>
                    </th>

                    <td class="odd col_0 product_306 ">
                                    <span>
                                    <p>This is a variable product.</p>
                                    </span>
                    </td>

                    <td class="even col_1 product_317 featured">
                                    <span>
                                    <p>This is a simple product.</p>
                                    </span>
                    </td>

                    <td class="odd col_2 product_318 ">
                                    <span>
                                    <p>This is a simple, virtual product.</p>
                                    </span>
                    </td>


                </tr>

                <tr class="add-to-cart">
                    <th class="thead ">
                        <div>
                            <svg class="icon right-arrow" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 492.004 492.004" xml:space="preserve">
            <path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
                    c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
                    v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
                    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
                    l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"></path>
        </svg>
                            <span class="field-name">Add to cart</span>
                        </div>
                    </th>

                    <td class="odd col_0 product_306 ">
                                    <span>
                                    <a href="https://wpdevsocial.com/product/v-neck-t-shirt/" data-quantity="1" class="button product_type_variable add_to_cart_button" data-product_id="306" data-product_sku="woo-vneck-tee" aria-label="Select options for “V-Neck T-Shirt”" rel="nofollow">Select options</a>                                    </span>
                    </td>

                    <td class="even col_1 product_317 featured">
                                    <span>
                                    <a href="?add-to-cart=317" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="317" data-product_sku="woo-polo" aria-label="Add “Polo” to your cart" rel="nofollow">Add to cart</a>                                    </span>
                    </td>

                    <td class="odd col_2 product_318 ">
                                    <span>
                                    <a href="?add-to-cart=318" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="318" data-product_sku="woo-album" aria-label="Add “Album” to your cart" rel="nofollow">Add to cart</a>                                    </span>
                    </td>


                </tr>

                <tr class="sku">
                    <th class="thead ">
                        <div>
                            <svg class="icon right-arrow" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 492.004 492.004" xml:space="preserve">
            <path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
                    c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
                    v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
                    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
                    l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"></path>
        </svg>
                            <span class="field-name">SKU</span>
                        </div>
                    </th>

                    <td class="odd col_0 product_306 ">
                                    <span>
                                    woo-vneck-tee                                    </span>
                    </td>

                    <td class="even col_1 product_317 featured">
                                    <span>
                                    woo-polo                                    </span>
                    </td>

                    <td class="odd col_2 product_318 ">
                                    <span>
                                    woo-album                                    </span>
                    </td>


                </tr>

                <tr class="stock">
                    <th class="thead ">
                        <div>
                            <svg class="icon right-arrow" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 492.004 492.004" xml:space="preserve">
            <path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
                    c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
                    v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
                    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
                    l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"></path>
        </svg>
                            <span class="field-name">Availability</span>
                        </div>
                    </th>

                    <td class="odd col_0 product_306 ">
                                    <span>
                                    <span>In stock</span>                                    </span>
                    </td>

                    <td class="even col_1 product_317 featured">
                                    <span>
                                    <span>In stock</span>                                    </span>
                    </td>

                    <td class="odd col_2 product_318 ">
                                    <span>
                                    <span>In stock</span>                                    </span>
                    </td>


                </tr>

                <tr class="weight">
                    <th class="thead ">
                        <div>
                            <svg class="icon right-arrow" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 492.004 492.004" xml:space="preserve">
            <path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
                    c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
                    v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
                    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
                    l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"></path>
        </svg>
                            <span class="field-name">Weight</span>
                        </div>
                    </th>

                    <td class="odd col_0 product_306 ">
                                    <span>
                                    <span>-</span>                                    </span>
                    </td>

                    <td class="even col_1 product_317 featured">
                                    <span>
                                    <span>-</span>                                    </span>
                    </td>

                    <td class="odd col_2 product_318 ">
                                    <span>
                                    <span>-</span>                                    </span>
                    </td>


                </tr>

                <tr class="dimension">
                    <th class="thead ">
                        <div>
                            <svg class="icon right-arrow" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 492.004 492.004" xml:space="preserve">
            <path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
                    c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
                    v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
                    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
                    l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"></path>
        </svg>
                            <span class="field-name">Dimension</span>
                        </div>
                    </th>

                    <td class="odd col_0 product_306 ">
                                    <span>
                                    &nbsp;                                    </span>
                    </td>

                    <td class="even col_1 product_317 featured">
                                    <span>
                                    &nbsp;                                    </span>
                    </td>

                    <td class="odd col_2 product_318 ">
                                    <span>
                                    &nbsp;                                    </span>
                    </td>


                </tr>

                <tr class="pa_color">
                    <th class="thead ">
                        <div>
                            <svg class="icon right-arrow" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 492.004 492.004" xml:space="preserve">
            <path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
                    c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
                    v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
                    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
                    l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"></path>
        </svg>
                            <span class="field-name">Color</span>
                        </div>
                    </th>

                    <td class="odd col_0 product_306 ">
                                    <span>
                                    Blue, Green, Red                                    </span>
                    </td>

                    <td class="even col_1 product_317 featured">
                                    <span>
                                    Blue                                    </span>
                    </td>

                    <td class="odd col_2 product_318 ">
                                    <span>
                                    Blue, Gray                                    </span>
                    </td>


                </tr>

                <tr class="pa_size">
                    <th class="thead ">
                        <div>
                            <svg class="icon right-arrow" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 492.004 492.004" xml:space="preserve">
            <path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
                    c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
                    v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
                    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
                    l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"></path>
        </svg>
                            <span class="field-name">Size</span>
                        </div>
                    </th>

                    <td class="odd col_0 product_306 ">
                                    <span>
                                    Large, Medium, Small                                    </span>
                    </td>

                    <td class="even col_1 product_317 featured">
                                    <span>
                                    &nbsp;                                    </span>
                    </td>

                    <td class="odd col_2 product_318 ">
                                    <span>
                                    Large, Medium                                    </span>
                    </td>


                </tr>


                <tr class="price repeated">
                    <th>
                        <div>
                            <svg class="icon right-arrow" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 492.004 492.004" xml:space="preserve">
            <path d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
                    c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
                    v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
                    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
                    l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"></path>
        </svg>
                            Price
                        </div>
                    </th>

                    <td class="odd col_0 product_306 ">
                        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>15</span> –
                        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>20</span>
                    </td>
                    <td class="even col_1 product_317 featured">
                        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>20</span>
                    </td>
                    <td class="odd col_2 product_318 ">
                        <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">৳&nbsp;</span>15</span>
                    </td>

                </tr>


                </tbody>
            </table>
        </div>
		<?php
	}


}

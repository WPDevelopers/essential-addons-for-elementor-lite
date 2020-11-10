<?php

namespace Essential_Addons_Elementor\Traits;

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

use \Essential_Addons_Elementor\Classes\Helper as HelperClass;
use \Essential_Addons_Elementor\Elements\Woo_Checkout;

trait Helper
{
    use \Essential_Addons_Elementor\Traits\Template_Query;
    /**
     * This function is responsible for get the post data.
     * It will return HTML markup with AJAX call and with normal call.
     *
     * @return string of an html markup with AJAX call.
     * @return array of content and found posts count without AJAX call.
     */
	public function ajax_load_more() {
		parse_str( $_REQUEST[ 'args' ], $args );
		parse_str( $_REQUEST[ 'settings' ], $settings );

		$html = '';
		$class = '\\' . str_replace( '\\\\', '\\', $_REQUEST[ 'class' ] );
		$args[ 'offset' ] = (int)$args[ 'offset' ] + ( ( (int)$_REQUEST[ 'page' ] - 1 ) * (int)$args[ 'posts_per_page' ] );

		if ( isset( $_REQUEST[ 'taxonomy' ] ) && $_REQUEST[ 'taxonomy' ][ 'taxonomy' ] != 'all' ) {
			$args[ 'tax_query' ] = [
				$_REQUEST[ 'taxonomy' ],
			];
		}

		if ( $class == '\Essential_Addons_Elementor\Elements\Post_Grid' && $settings[ 'orderby' ] === 'rand' ) {
			$args[ 'post__not_in' ] = array_unique( $_REQUEST[ 'post__not_in' ] );
		}

		$template_info = $_REQUEST[ 'template_info' ];

		if ( $template_info ) {
			if ( $template_info[ 'dir' ] === 'free' ) {
				$file_path = EAEL_PLUGIN_PATH;
			}

			if ( $template_info[ 'dir' ] === 'pro' ) {
				$file_path = EAEL_PRO_PLUGIN_PATH;
			}

			$file_path = sprintf(
				'%sincludes/Template/%s/%s.php',
				$file_path,
				$template_info[ 'name' ],
				$template_info[ 'file_name' ]
			);

			if ( $file_path ) {
				$query = new \WP_Query( $args );

				$iterator = 0;

				if ( $query->have_posts() ) {
					if ( $class === '\Essential_Addons_Elementor\Elements\Product_Grid' && boolval( $settings[ 'show_add_to_cart_custom_text' ] ) ) {

						$add_to_cart_text = [
							'add_to_cart_simple_product_button_text' => $settings[ 'add_to_cart_simple_product_button_text' ],
							'add_to_cart_variable_product_button_text' => $settings[ 'add_to_cart_variable_product_button_text' ],
							'add_to_cart_grouped_product_button_text' => $settings[ 'add_to_cart_grouped_product_button_text' ],
							'add_to_cart_external_product_button_text' => $settings[ 'add_to_cart_external_product_button_text' ],
							'add_to_cart_default_product_button_text' => $settings[ 'add_to_cart_default_product_button_text' ],
						];
						$this->change_add_to_cart_text($add_to_cart_text);
					}

					if ( $class === '\Essential_Addons_Elementor\Pro\Elements\Post_List' ) {
//						$html .= '<div class="eael-post-list-posts-wrap">';
					}

					while ( $query->have_posts() ) {
						$query->the_post();

						$html .= HelperClass::include_with_variable( $file_path, [ 'settings' => $settings, 'iterator' => $iterator ] );
						$iterator++;
					}
					if ( $class === '\Essential_Addons_Elementor\Pro\Elements\Post_List' ) {
//						$html .= '</div>';
					}
				}
			}
		}

		echo $html;
		wp_die();
	}

    /**
     * Woo Checkout
     */
    public function woo_checkout_update_order_review() {
        $setting = $_POST[ 'orderReviewData' ];
        ob_start();
        Woo_Checkout::checkout_order_review_default( $setting );
        $woo_checkout_update_order_review = ob_get_clean();

        wp_send_json(
            array(
                'order_review' => $woo_checkout_update_order_review,
            )
        );
    }

    /** Filter to add plugins to the TOC list.
     *
     * @param array TOC plugins.
     *
     * @return mixed
     * @since  3.9.3
     */
    public function toc_rank_math_support( $toc_plugins ) {
        $toc_plugins[ 'essential-addons-for-elementor-lite/essential_adons_elementor.php' ] = __( 'Essential Addons for Elementor', 'essential-addons-for-elementor-lite' );
        return $toc_plugins;
    }

    /**
     * Save typeform access token
     *
     * @since  4.0.2
     */
    public function typeform_auth_handle() {
        $post = $_POST;
        if ( isset( $post[ 'typeform_tk' ] ) && isset( $post[ 'pr_code' ] ) ) {
            if ( wp_hash( 'eael_typeform' ) === $post[ 'pr_code' ] ) {
                update_option( 'eael_save_typeform_personal_token', sanitize_text_field( $post[ 'typeform_tk' ] ) );
            }
        }
        wp_send_json_success( [ 'status' => 'success' ] );
    }

    /*****************************
     *
     * Compatibility for Pro
     *
     * @since  4.2.4
     */
    public function eael_get_page_templates( $type = null ) {
        return HelperClass::get_elementor_templates( $type );
    }

    public function eael_query_controls() {
        return do_action( 'eael/controls/query', $this );
    }

    public function eael_layout_controls() {
        return do_action( 'eael/controls/layout', $this );
    }

    public function eael_load_more_button_style() {
        return do_action( 'eael/controls/load_more_button_style', $this );
    }

    public function eael_read_more_button_style() {
        return do_action( 'eael/controls/read_more_button_style', $this );
    }

    public function eael_controls_custom_positioning( $_1, $_2, $_3, $_4 ) {
        return do_action( 'eael/controls/custom_positioning', $this, $_1, $_2, $_3, $_4 );
    }

    public function eael_get_all_types_post() {
        return HelperClass::get_post_types();
    }

    public function eael_get_pages() {
        return HelperClass::get_post_list( 'page' );
    }

    public function eael_woocommerce_product_categories_by_id() {
        return HelperClass::get_terms_list( 'product_cat' );
    }

    public function fix_old_query( $settings ) {
        return HelperClass::fix_old_query( $settings );
    }

    public function eael_get_query_args( $settings ) {
        return HelperClass::get_query_args( $settings );
    }

    public function eael_get_tags( $args ) {
        return HelperClass::get_tags_list( $args );
    }

    public function eael_get_taxonomies_by_post( $args ) {
        return HelperClass::get_taxonomies_by_post( $args );
    }

    public function twitter_feed_render_items( $id, $settings, $class = '' ) {
        $token = get_option( $id . '_' . $settings[ 'eael_twitter_feed_ac_name' ] . '_tf_token' );
        $items = get_transient( $id . '_' . $settings[ 'eael_twitter_feed_ac_name' ] . '_tf_cache' );
        $html = '';

        if ( empty( $settings[ 'eael_twitter_feed_consumer_key' ] ) || empty( $settings[ 'eael_twitter_feed_consumer_secret' ] ) ) {
            return;
        }

        if ( $items === false ) {
            if ( empty( $token ) ) {
                $credentials = base64_encode( $settings[ 'eael_twitter_feed_consumer_key' ] . ':' . $settings[ 'eael_twitter_feed_consumer_secret' ] );

                add_filter( 'https_ssl_verify', '__return_false' );

                $response = wp_remote_post( 'https://api.twitter.com/oauth2/token', [
                    'method' => 'POST',
                    'httpversion' => '1.1',
                    'blocking' => true,
                    'headers' => [
                        'Authorization' => 'Basic ' . $credentials,
                        'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                    ],
                    'body' => [ 'grant_type' => 'client_credentials' ],
                ] );

                $body = json_decode( wp_remote_retrieve_body( $response ) );

                if ( $body ) {
                    update_option( $id . '_' . $settings[ 'eael_twitter_feed_ac_name' ] . '_tf_token', $body->access_token );
                    $token = $body->access_token;
                }
            }

            $args = array(
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => array(
                    'Authorization' => "Bearer $token",
                ),
            );

            add_filter( 'https_ssl_verify', '__return_false' );

            $response = wp_remote_get( 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=' . $settings[ 'eael_twitter_feed_ac_name' ] . '&count=999&tweet_mode=extended', [
                'httpversion' => '1.1',
                'blocking' => true,
                'headers' => [
                    'Authorization' => "Bearer $token",
                ],
            ] );

            if ( !is_wp_error( $response ) ) {
                $items = json_decode( wp_remote_retrieve_body( $response ), true );
                set_transient( $id . '_' . $settings[ 'eael_twitter_feed_ac_name' ] . '_tf_cache', $items, 1800 );
            }
        }

        if ( empty( $items ) ) {
            return;
        }

        if ( $settings[ 'eael_twitter_feed_hashtag_name' ] ) {
            foreach ( $items as $key => $item ) {
                $match = false;

                if ( $item[ 'entities' ][ 'hashtags' ] ) {
                    foreach ( $item[ 'entities' ][ 'hashtags' ] as $tag ) {
                        if ( strcasecmp( $tag[ 'text' ], $settings[ 'eael_twitter_feed_hashtag_name' ] ) == 0 ) {
                            $match = true;
                        }
                    }
                }

                if ( $match == false ) {
                    unset( $items[ $key ] );
                }
            }
        }

        $items = array_splice( $items, 0, $settings[ 'eael_twitter_feed_post_limit' ] );

        foreach ( $items as $item ) {
            $delimeter = strlen( $item[ 'full_text' ] ) > $settings[ 'eael_twitter_feed_content_length' ] ? '...' : '';

            $html .= '<div class="eael-twitter-feed-item ' . $class . '">
				<div class="eael-twitter-feed-item-inner">
				    <div class="eael-twitter-feed-item-header clearfix">';
            if ( $settings[ 'eael_twitter_feed_show_avatar' ] == 'true' ) {
                $html .= '<a class="eael-twitter-feed-item-avatar avatar-' . $settings[ 'eael_twitter_feed_avatar_style' ] . '" href="//twitter.com/' . $settings[ 'eael_twitter_feed_ac_name' ] . '" target="_blank">
                                <img src="' . $item[ 'user' ][ 'profile_image_url_https' ] . '">
                            </a>';
            }

            $html .= '<a class="eael-twitter-feed-item-meta" href="//twitter.com/' . $settings[ 'eael_twitter_feed_ac_name' ] . '" target="_blank">';
            if ( $settings[ 'eael_twitter_feed_show_icon' ] == 'true' ) {
                $html .= '<i class="fab fa-twitter eael-twitter-feed-item-icon"></i>';
            }
            $html .= '<span class="eael-twitter-feed-item-author">' . $item[ 'user' ][ 'name' ] . '</span>
                        </a>';

            if ( $settings[ 'eael_twitter_feed_show_date' ] == 'true' ) {
                $html .= '<span class="eael-twitter-feed-item-date">' . sprintf( __( '%s ago', 'essential-addons-for-elementor-lite' ), human_time_diff( strtotime( $item[ 'created_at' ] ) ) ) . '</span>';
            }
            $html .= '</div>

                    <div class="eael-twitter-feed-item-content">';
            if ( isset( $item[ 'entities' ][ 'urls' ][ 0 ][ 'url' ] ) ) {
                $html .= '<p>' . substr( str_replace( $item[ 'entities' ][ 'urls' ][ 0 ][ 'url' ], '', $item[ 'full_text' ] ), 0, $settings[ 'eael_twitter_feed_content_length' ] ) . $delimeter . '</p>';
            }

            if ( $settings[ 'eael_twitter_feed_show_read_more' ] == 'true' ) {
                $html .= '<a href="//twitter.com/' . $item[ 'user' ][ 'screen_name' ] . '/status/' . $item[ 'id_str' ] . '" target="_blank" class="read-more-link">Read More <i class="fas fa-angle-double-right"></i></a>';
            }
            $html .= '</div>
                    ' . ( isset( $item[ 'extended_entities' ][ 'media' ][ 0 ] ) && $settings[ 'eael_twitter_feed_media' ] == 'true' ? ( $item[ 'extended_entities' ][ 'media' ][ 0 ][ 'type' ] == 'photo' ? '<img src="' . $item[ 'extended_entities' ][ 'media' ][ 0 ][ 'media_url_https' ] . '">' : '' ) : '' ) . '
                </div>
			</div>';
        }

        return $html;
    }

    public function select2_ajax_posts_filter_autocomplete() {
        $post_type = 'post';
        if ( !empty( $_GET[ 'post_type' ] ) ) {
            $post_type = sanitize_text_field( $_GET[ 'post_type' ] );
        }
        $search = !empty( $_GET[ 'term' ] ) ? sanitize_text_field( $_GET[ 'term' ] ) : '';
        $results = [];
        $post_list = HelperClass::get_query_post_list( $post_type, 10, $search );
        if ( !empty( $post_list ) ) {
            foreach ( $post_list as $key => $item ) {
                $results[] = [ 'text' => $item, 'id' => $key ];
            }
        }
        wp_send_json( [ 'results' => $results ] );
    }

    public function select2_ajax_get_posts_value_titles() {
        if ( empty( $_POST[ 'id' ] ) ) {
            wp_send_json_error( [] );
        }
        $id = sanitize_text_field( $_POST[ 'id' ] );
        $post_info = get_post( $id );
        if ( $post_info ) {
            wp_send_json_success( [ 'id' => $id, 'text' => $post_info->post_title ] );
        } else {
            wp_send_json_error( [] );
        }
    }

	public function eael_product_grid_script(){
		if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
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
            wp_enqueue_script( 'wc-add-to-cart-variation' );
			wp_enqueue_script( 'wc-single-product' );
		}
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

	public function eael_woo_pagination_product_ajax() {
		parse_str($_REQUEST['args'], $args);
		parse_str($_REQUEST['settings'], $settings);

		$paginationNumber = absint($_POST['number']);
		$paginationLimit  = absint($_POST['limit']);

		$args['posts_per_page'] = $paginationLimit;

		if( $paginationNumber == "1" ){
			$paginationOffsetValue = "0";
		}else{
			$paginationOffsetValue = ($paginationNumber-1)*$paginationLimit;
			$args['offset'] = $paginationOffsetValue;
		}

		$template_info = $_REQUEST['templateInfo'];
        $this->set_widget_name( $template_info['name'] );
        $template = $this->get_template( $template_info['file_name'] );
        ob_start();
        $query = new \WP_Query( $args );
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                include( $template );
            }
        }
        echo ob_get_clean();
        wp_die();
	}

	public function eael_woo_pagination_ajax() {
		parse_str($_REQUEST['args'], $args);
		parse_str($_REQUEST['settings'], $settings);
		$class = '\Essential_Addons_Elementor\Elements\Product_Grid';

		global $wpdb;
		$paginationNumber = absint($_POST['number']);
		$paginationLimit  = absint($_POST['limit']);

		$pagination_args = $args;
		$pagination_args['posts_per_page'] = -1;

		$pagination_Query = new \WP_Query( $pagination_args );
		$pagination_Count = count($pagination_Query->posts);
		$pagination_Paginationlist = ceil($pagination_Count/$paginationLimit);
		$last = ceil( $pagination_Paginationlist );
		$paginationprev = $paginationNumber-1;
		$paginationnext = $paginationNumber+1;
		if( $paginationNumber>1 ){ $paginationprev;	}
		if( $paginationNumber < $last ){ $paginationnext; }

		$adjacents = "2";
		$widget_id = $settings['eael_widget_id'];
		$next_label = $settings['pagination_next_label'];
		$prev_label = $settings['pagination_prev_label'];

		$setPagination = "";
		if( $pagination_Paginationlist > 0 ){

			$setPagination .="<ul class='page-numbers'>";

			if( 1< $paginationNumber ){
				$setPagination .="<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-template='".json_encode([ 'dir'   => 'free', 'file_name' => $settings['eael_dynamic_template_Layout'], 'name' => $settings['eael_widget_name'] ], 1)."' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$paginationprev' data-plimit='$paginationLimit'>$prev_label</a></li>";
			}

			if ( $pagination_Paginationlist < 7 + ($adjacents * 2) ){

				for( $pagination=1; $pagination<=$pagination_Paginationlist; $pagination++){

					if( $paginationNumber ==  $pagination ){ $active="current"; }else{ $active=""; }
					$setPagination .="<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='".json_encode([ 'dir'   => 'free', 'file_name' => $settings['eael_dynamic_template_Layout'], 'name' => $settings['eael_widget_name'] ], 1)."' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$pagination' data-plimit='$paginationLimit'>$pagination</a></li>";

				}

			} else if ( $pagination_Paginationlist > 5 + ($adjacents * 2) ){

				if( $paginationNumber < 1 + ($adjacents * 2) ){

					for( $pagination=1; $pagination <=4 + ($adjacents * 2); $pagination++){

						if( $paginationNumber ==  $pagination ){ $active="current"; }else{ $active=""; }
						$setPagination .="<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='".json_encode([ 'dir'   => 'free', 'file_name' => $settings['eael_dynamic_template_Layout'], 'name' => $settings['eael_widget_name'] ], 1)."' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$pagination' data-plimit='$paginationLimit'>$pagination</a></li>";
					}
					$setPagination .="<li class='pagitext dots'>...</li>";
					$setPagination .="<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-template='".json_encode([ 'dir'   => 'free', 'file_name' => $settings['eael_dynamic_template_Layout'], 'name' => $settings['eael_widget_name'] ], 1)."' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$last' data-plimit='$paginationLimit'>".$last."</a></li>";

				} elseif( $pagination_Paginationlist - ($adjacents * 2) > $paginationNumber && $paginationNumber > ($adjacents * 2)) {
					$active = '';
					$setPagination .="<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='".json_encode([ 'dir'   => 'free', 'file_name' => $settings['eael_dynamic_template_Layout'], 'name' => $settings['eael_widget_name'] ], 1)."' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='1' data-plimit='$paginationLimit'>1</a></li>";
					$setPagination .="<li class='pagitext dots'>...</li>";

					for( $pagination=$paginationNumber - $adjacents; $pagination<=$paginationNumber + $adjacents; $pagination++){

						if( $paginationNumber ==  $pagination ){ $active="current"; }else{ $active=""; }
						$setPagination .="<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='".json_encode([ 'dir'   => 'free', 'file_name' => $settings['eael_dynamic_template_Layout'], 'name' => $settings['eael_widget_name'] ], 1)."' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$pagination' data-plimit='$paginationLimit'>$pagination</a></li>";

					}

					$setPagination .="<li class='pagitext dots'>...</li>";
					$setPagination .="<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-template='".json_encode([ 'dir'   => 'free', 'file_name' => $settings['eael_dynamic_template_Layout'], 'name' => $settings['eael_widget_name'] ], 1)."' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$last' data-plimit='$paginationLimit'>".$last."</a></li>";

				} else {
					$active = '';
					$setPagination .="<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='".json_encode([ 'dir'   => 'free', 'file_name' => $settings['eael_dynamic_template_Layout'], 'name' => $settings['eael_widget_name'] ], 1)."' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='1' data-plimit='$paginationLimit'>1</a></li>";
					$setPagination .="<li class='pagitext dots'>...</li>";

					for ($pagination = $last - (2 + ($adjacents * 2)); $pagination <= $last; $pagination++){

						if( $paginationNumber ==  $pagination ){ $active="current"; }else{ $active=""; }
						$setPagination .="<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='".json_encode([ 'dir'   => 'free', 'file_name' => $settings['eael_dynamic_template_Layout'], 'name' => $settings['eael_widget_name'] ], 1)."' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$pagination' data-plimit='$paginationLimit'>$pagination</a></li>";

					}

				}

			} else {

				for( $pagination=1; $pagination<=$pagination_Paginationlist; $pagination++){
					if( $paginationNumber ==  $pagination ){ $active="current"; }else{ $active=""; }
					$setPagination .="<li><a href='javascript:void(0);' id='post' class='page-numbers $active' data-template='".json_encode([ 'dir'   => 'free', 'file_name' => $settings['eael_dynamic_template_Layout'], 'name' => $settings['eael_widget_name'] ], 1)."' data-widgetid='$widget_id' data-args='".http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$pagination' data-plimit='$paginationLimit'>$pagination</a></li>";
				}

			}

			if ($paginationNumber < $pagination_Paginationlist){
				$setPagination .="<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-template='".json_encode([ 'dir'   => 'free', 'file_name' => $settings['eael_dynamic_template_Layout'], 'name' => $settings['eael_widget_name'] ], 1)."' data-widgetid='$widget_id' data-args='"
				                 .http_build_query($args)."' data-settings='".http_build_query($settings)."' data-pnumber='$paginationnext' data-plimit='$paginationLimit'>$next_label</a></li>";
			}

			$setPagination .="</ul>";
		}
		echo $setPagination;
		wp_die();
	}

	public function eael_product_add_to_cart () {

		$ajax   = wp_doing_ajax();

		if(isset($_POST['product_data'])){
			foreach ($_POST['product_data'] as $item){
				$product_id   = isset( $item['product_id'] ) ? sanitize_text_field( $item['product_id'] ) : 0;
				$variation_id = isset( $item['variation_id'] ) ? sanitize_text_field( $item['variation_id'] ) : 0;
				$quantity     = isset( $item['quantity'] ) ? sanitize_text_field( $item['quantity'] ) : 0;
				if ( $variation_id ) {
					WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );
				} else {
					WC()->cart->add_to_cart( $product_id, $quantity );
				}
			}
		}
		wp_send_json_success();
	}

	public function change_add_to_cart_text( $add_to_cart_text ) {
		add_filter( 'woocommerce_product_add_to_cart_text', function ( $default ) use ( $add_to_cart_text ) {
			global $product;
			switch ( $product->get_type() ) {
				case 'external':
					return $add_to_cart_text[ 'add_to_cart_external_product_button_text' ];
					break;
				case 'grouped':
					return $add_to_cart_text[ 'add_to_cart_grouped_product_button_text' ];
					break;
				case 'simple':
					return $add_to_cart_text[ 'add_to_cart_simple_product_button_text' ];
					break;
				case 'variable':
					return $add_to_cart_text[ 'add_to_cart_variable_product_button_text' ];
					break;
				default:
					return $default;
			}
		} );
	}
	
}

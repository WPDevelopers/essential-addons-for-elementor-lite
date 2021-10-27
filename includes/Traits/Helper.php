<?php

namespace Essential_Addons_Elementor\Traits;

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

use Elementor\Plugin;
use \Essential_Addons_Elementor\Classes\Helper as HelperClass;
use \Essential_Addons_Elementor\Elements\Woo_Checkout;

trait Helper
{
    use Template_Query;
    /**
     * This function is responsible for get the post data.
     * It will return HTML markup with AJAX call and with normal call.
     *
     * @return string of an html markup with AJAX call.
     * @return array of content and found posts count without AJAX call.
     */
    public function ajax_load_more()
    {
        $ajax   = wp_doing_ajax();

        parse_str($_POST['args'], $args);
        if ( empty( $_POST['nonce'] ) ) {
            $err_msg = __( 'Insecure form submitted without security token', 'essential-addons-for-elementor-lite' );
            if ( $ajax ) {
                wp_send_json_error( $err_msg );
            }
            return false;
        }

        if ( ! wp_verify_nonce( $_POST['nonce'], 'load_more' ) ) {
            $err_msg = __( 'Security token did not match', 'essential-addons-for-elementor-lite' );
            if ( $ajax ) {
                wp_send_json_error( $err_msg );
            }
            return false;
        }

        if ( ! empty( $_POST['page_id'] ) ) {
            $page_id = intval( $_POST['page_id'], 10 );
        } else {
            $err_msg = __( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
            if ( $ajax ) {
                wp_send_json_error( $err_msg );
            }
            return false;
        }

        if ( ! empty( $_POST['widget_id'] ) ) {
            $widget_id = sanitize_text_field( $_POST['widget_id'] );
        } else {
            $err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
            if ( $ajax ) {
                wp_send_json_error( $err_msg );
            }
            return false;
        }

        $settings = HelperClass::eael_get_widget_settings($page_id, $widget_id);

        if (empty($settings)) {
            wp_send_json_error(['message' => __('Widget settings are not found. Did you save the widget before using load more??', 'essential-addons-for-elementor-lite')]);
        }

        $settings['eael_widget_id'] = $widget_id;
        $settings['eael_page_id'] = $page_id;
        $html = '';
        $class = '\\' . str_replace( '\\\\', '\\', $_REQUEST[ 'class' ] );
        $args[ 'offset' ] = (int)$args[ 'offset' ] + ( ( (int)$_REQUEST[ 'page' ] - 1 ) * (int)$args[ 'posts_per_page' ] );

        if ( isset( $_REQUEST[ 'taxonomy' ] ) && isset($_REQUEST[ 'taxonomy' ][ 'taxonomy' ]) && $_REQUEST[ 'taxonomy' ][ 'taxonomy' ] != 'all' ) {
            $args[ 'tax_query' ] = [
                $_REQUEST[ 'taxonomy' ],
            ];
        }

        if ( $class == '\Essential_Addons_Elementor\Elements\Post_Grid' && $settings[ 'orderby' ] === 'rand' ) {
            $args[ 'post__not_in' ] = array_unique( $_REQUEST[ 'post__not_in' ] );
	        unset( $args['offset'] );
        }

        // ensure control name compatibility to old code if it is post block
        if ($class === '\Essential_Addons_Elementor\Pro\Elements\Post_Block' ) {
            $settings ['post_block_hover_animation'] = $settings['eael_post_block_hover_animation'];
            $settings ['show_read_more_button'] = $settings['eael_show_read_more_button'];
            $settings ['eael_post_block_bg_hover_icon'] = (isset($settings['__fa4_migrated']['eael_post_block_bg_hover_icon_new']) || empty($settings['eael_post_block_bg_hover_icon'])) ? $settings['eael_post_block_bg_hover_icon_new']['value'] : $settings['eael_post_block_bg_hover_icon'];
            $settings ['expanison_indicator'] = $settings['excerpt_expanison_indicator'];
        }
        if ( $class === '\Essential_Addons_Elementor\Elements\Post_Timeline' ) {
            $settings ['expanison_indicator'] = $settings['excerpt_expanison_indicator'];
        }
        if ($class === '\Essential_Addons_Elementor\Pro\Elements\Dynamic_Filterable_Gallery' ) {
            $settings['eael_section_fg_zoom_icon'] = (isset($settings['__fa4_migrated']['eael_section_fg_zoom_icon_new']) || empty($settings['eael_section_fg_zoom_icon']) ? $settings['eael_section_fg_zoom_icon_new']['value'] : $settings['eael_section_fg_zoom_icon']);
            $settings['eael_section_fg_link_icon'] = (isset($settings['__fa4_migrated']['eael_section_fg_link_icon_new']) || empty($settings['eael_section_fg_link_icon']) ? $settings['eael_section_fg_link_icon_new']['value'] : $settings['eael_section_fg_link_icon']);
            $settings['show_load_more_text'] = $settings['eael_fg_loadmore_btn_text'];
            $settings['layout_mode'] = isset($settings['layout_mode']) ? $settings['layout_mode'] : 'masonry';

        }

        $link_settings = [
            'image_link_nofollow' => $settings['image_link_nofollow'] ? 'rel="nofollow"' : '',
            'image_link_target_blank' => $settings['image_link_target_blank'] ? 'target="_blank"' : '',
            'title_link_nofollow' => $settings['title_link_nofollow'] ? 'rel="nofollow"' : '',
            'title_link_target_blank' => $settings['title_link_target_blank'] ? 'target="_blank"' : '',
            'read_more_link_nofollow' => $settings['read_more_link_nofollow'] ? 'rel="nofollow"' : '',
            'read_more_link_target_blank' => $settings['read_more_link_target_blank'] ? 'target="_blank"' : '',
        ];

        $template_info = $_REQUEST['template_info'];

        if ( $template_info ) {

	        if ( $template_info[ 'dir' ] === 'theme' ) {
		        $file_path = $this->retrive_theme_path();
	        } else if($template_info[ 'dir' ] === 'pro') {
		        $file_path = sprintf("%sincludes",EAEL_PRO_PLUGIN_PATH);
            } else {
		        $file_path = sprintf("%sincludes",EAEL_PLUGIN_PATH);
            }

            $file_path = sprintf(
                '%s/Template/%s/%s',
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


                    while ( $query->have_posts() ) {
                        $query->the_post();

                        $html .= HelperClass::include_with_variable( $file_path, [ 'settings' => $settings, 'link_settings' => $link_settings, 'iterator' => $iterator ] );
                        $iterator++;
                    }
                }
            }
        }


        while ( ob_get_status() ) {
            ob_end_clean();
        }
        if (function_exists( 'gzencode' ) ) {
            $response = gzencode( wp_json_encode( $html ) );

            header( 'Content-Type: application/json; charset=utf-8' );
            header( 'Content-Encoding: gzip' );
            header( 'Content-Length: ' . strlen( $response ) );

            echo $response;
        } else {
            echo $html;
        }
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
	    if ( isset($_GET[ 'page' ]) && 'eael-settings' == $_GET[ 'page' ] ) {
		    if ( isset( $_GET[ 'typeform_tk' ] ) && isset( $_GET[ 'pr_code' ] ) ) {
			    if ( wp_hash( 'eael_typeform' ) === $_GET[ 'pr_code' ] ) {
				    update_option( 'eael_save_typeform_personal_token', sanitize_text_field( $_GET[ 'typeform_tk' ] ), false );
			    }
		    }
	    }
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
                $html .= '<a href="//twitter.com/' . $item[ 'user' ][ 'screen_name' ] . '/status/' . $item[ 'id_str' ] . '" target="_blank" class="read-more-link">'.$settings['eael_twitter_feed_show_read_more_text'].' <i class="fas fa-angle-double-right"></i></a>';
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
        $source_name = 'post_type';

        if ( !empty( $_GET[ 'post_type' ] ) ) {
            $post_type = sanitize_text_field( $_GET[ 'post_type' ] );
        }

        if ( !empty( $_GET[ 'source_name' ] ) ) {
            $source_name = sanitize_text_field( $_GET[ 'source_name' ] );
        }

        $search = !empty( $_GET[ 'term' ] ) ? sanitize_text_field( $_GET[ 'term' ] ) : '';
        $results = $post_list = [];
        switch($source_name){
            case 'taxonomy':
                $post_list = wp_list_pluck( get_terms( $post_type,
                    [
                        'hide_empty' => false,
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'search'     => $search,
                        'number'     => '5',
                    ]
                ), 'name', 'term_id' );
                break;
            default:
                $post_list = HelperClass::get_query_post_list( $post_type, 10, $search );
        }

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

        if ( empty( array_filter($_POST[ 'id' ]) ) ) {
            wp_send_json_error( [] );
        }
        $ids          = array_map('intval',$_POST[ 'id' ]);
        $source_name = !empty( $_POST[ 'source_name' ] ) ? sanitize_text_field( $_POST[ 'source_name' ] ) : '';

        switch ( $source_name ) {
            case 'taxonomy':
                $response = wp_list_pluck( get_terms( sanitize_text_field( $_POST[ 'post_type' ] ),
                    [
                        'hide_empty' => false,
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                        'include'    => implode( ',', $ids ),
                    ]
                ), 'name', 'term_id' );
                break;
            default:
                $post_info = get_posts( [ 'post_type' => sanitize_text_field( $_POST[ 'post_type' ] ), 'include' => implode( ',', $ids ) ] );
                $response  = wp_list_pluck( $post_info, 'post_title', 'ID' );
        }

        if ( !empty( $response ) ) {
            wp_send_json_success( [ 'results' => $response ] );
        } else {
            wp_send_json_error( [] );
        }
    }

	/**
	 * It returns the widget settings provided the page id and widget id
	 * @param int $page_id Page ID where the widget is used
	 * @param string $widget_id the id of the widget whose settings we want to fetch
	 *
	 * @return array
	 */
	public function eael_get_widget_settings( $page_id, $widget_id ) {
		$document = Plugin::$instance->documents->get( $page_id );
		$settings = [];
		if ( $document ) {
			$elements    = Plugin::instance()->documents->get( $page_id )->get_elements_data();
			$widget_data = $this->find_element_recursive( $elements, $widget_id );
            if(!empty($widget_data)) {
                $widget      = Plugin::instance()->elements_manager->create_element_instance( $widget_data );
                if ( $widget ) {
                    $settings    = $widget->get_settings_for_display();
                }
            }
		}
		return $settings;
	}
	/**
	 * It store data temporarily for 5 mins by default
	 *
	 * @param     $name
	 * @param     $data
	 * @param int $time time in seconds. Default is 300s = 5 minutes
	 *
	 * @return bool it returns true if the data saved, otherwise, false returned.
	 */
	public function eael_set_transient( $name, $data, $time = 300 ) {
		$time = !empty( $time ) ? (int) $time : ( 5 * MINUTE_IN_SECONDS );
		return set_transient( $name, $data, $time );
	}
    public function print_load_more_button($settings, $args, $plugin_type = 'free')
    {
        //@TODO; not all widget's settings contain posts_per_page name exactly, so adjust the settings before passing here or run a migration and make all settings key generalize for load more feature.
        if (!isset($this->page_id)) {
            if ( Plugin::$instance->documents->get_current() ) {
                $this->page_id = Plugin::$instance->documents->get_current()->get_main_id();
            }else{
                $this->page_id = null;
            }
        }

	    $max_page = empty( $args['max_page'] ) ? false : $args['max_page'];
	    unset( $args['max_page'] );

        $this->add_render_attribute('load-more', [
            'class'          => "eael-load-more-button",
            'id'             => "eael-load-more-btn-" . $this->get_id(),
            'data-widget-id' => $this->get_id(),
            'data-widget' => $this->get_id(),
            'data-page-id'   => $this->page_id,
            'data-nonce'     => wp_create_nonce( 'load_more' ),
            'data-template'  => json_encode([
                'dir'   => $plugin_type,
                'file_name' => $settings['loadable_file_name'],
                'name' => $this->process_directory_name() ],
                1),
            'data-class'    => get_class( $this ),
            'data-layout'   => isset($settings['layout_mode']) ? $settings['layout_mode'] : "",
            'data-page'     => 1,
            'data-args'     => http_build_query( $args ),
        ]);

	    if ( $max_page ) {
		    $this->add_render_attribute( 'load-more', [ 'data-max-page' => $max_page ] );
	    }

        if ( ('true' == $settings['show_load_more'] || 1 == $settings['show_load_more'] || 'yes' == $settings['show_load_more']) && $args['posts_per_page'] != '-1' ) { ?>
            <div class="eael-load-more-button-wrap<?php echo "eael-dynamic-filterable-gallery" == $this->get_name() ? " dynamic-filter-gallery-loadmore" : ""; ?>">
                <button <?php $this->print_render_attribute_string( 'load-more' ); ?>>
                    <div class="eael-btn-loader button__loader"></div>
                    <span><?php echo esc_html($settings['show_load_more_text']) ?></span>
                </button>
            </div>
        <?php }
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
		$cart_items = isset($_POST['cart_item_data'])?$_POST['cart_item_data']:[];
		$variation = [];
		if(!empty($cart_items)){
			foreach ($cart_items as $key => $value) {
				if (preg_match("/^attribute*/", $value['name'])) {
					$variation[$value['name']] = $value['value'];
				}
			}
		}

		if(isset($_POST['product_data'])){
			foreach ($_POST['product_data'] as $item){
				$product_id   = isset( $item['product_id'] ) ? sanitize_text_field( $item['product_id'] ) : 0;
				$variation_id = isset( $item['variation_id'] ) ? sanitize_text_field( $item['variation_id'] ) : 0;
				$quantity     = isset( $item['quantity'] ) ? sanitize_text_field( $item['quantity'] ) : 0;

				if ( $variation_id ) {
					WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
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

	public function print_template_views(){
        $button_test = ( HelperClass::get_local_plugin_data( 'templately/templately.php' ) === false )?'Install Templately':'Activate Templately ';
        ?>
        <div id="eael-promo-temp-wrap" class="eael-promo-temp-wrap" style="display: none">
            <div class="eael-promo-temp-wrapper">
                <div class="eael-promo-temp">
                    <a href="#" class="eael-promo-temp__times">
                        <i class="eicon-close" aria-hidden="true" title="Close"></i>
                    </a>
                    <div class="eael-promo-temp--left">
                        <div class="eael-promo-temp__logo">
                            <img src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/templately/logo.svg'; ?>" alt="">
                        </div>
                        <ul class="eael-promo-temp__feature__list">
                            <li><?php _e('1,000+ Stunning Templates','essential-addons-for-elementor-lite'); ?></li>
                            <li><?php _e('Supports Elementor & Gutenberg','essential-addons-for-elementor-lite'); ?></li>
                            <li><?php _e('Powering up 17,000+ Websites','essential-addons-for-elementor-lite'); ?></li>
                            <li><?php _e('Cloud Collaboration with Team','essential-addons-for-elementor-lite'); ?></li>
                        </ul>
                        <form class="eael-promo-temp__form">
                            <label>
                                <input type="radio" value="install" class="eael-temp-promo-confirmation" name='eael-promo-temp__radio' checked>
                                <span><?php echo $button_test; ?></span>
                            </label>
                            <label>
                                <input type="radio" value="dnd" class="eael-temp-promo-confirmation" name='eael-promo-temp__radio'>
                                <span><?php _e('Don’t Show This Again','essential-addons-for-elementor-lite'); ?></span>
                            </label>
                        </form>

                        <?php if ( HelperClass::get_local_plugin_data( 'templately/templately.php' ) === false ) { ?>
                            <button class="wpdeveloper-plugin-installer" data-action="install"
                               data-slug="<?php echo 'templately'; ?>"><?php _e( 'Install Templately', 'essential-addons-for-elementor-lite' ); ?></button>
                        <?php } else { ?>
                            <?php if ( is_plugin_active( 'templately/templately.php' ) ) { ?>
                                <button class="wpdeveloper-plugin-installer"><?php _e( 'Activated Templately', 'essential-addons-for-elementor-lite' ); ?></button>
                            <?php } else { ?>
                                <button class="wpdeveloper-plugin-installer" data-action="activate"
                                   data-basename="<?php echo 'templately/templately.php'; ?>"><?php _e( 'Activate Templately', 'essential-addons-for-elementor-lite' ); ?></button>
                            <?php } ?>
                        <?php } ?>
                        <button class="eael-prmo-status-submit" style="display: none"><?php _e('Submit','essential-addons-for-elementor-lite') ?></button>
                    </div>
                    <div class="eael-promo-temp--right">
                        <img src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/templately/templates-edit.jpg'; ?>" alt="">
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function templately_promo_status() {
        check_ajax_referer( 'essential-addons-elementor', 'security' );

        if(!current_user_can('manage_options')){
            wp_send_json_error(__('you are not allowed to do this action', 'essential-addons-for-elementor-lite'));
        }

        $status = update_option( 'eael_templately_promo_hide', true );
        if ( $status ) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

	/**
	 * Retrieve product quick view data
     *
     * @return string
	 */
    public function eael_product_quickview_popup(){
	    //check nonce
	    check_ajax_referer( 'essential-addons-elementor', 'security' );
	    $widget_id  = sanitize_key( $_POST[ 'widget_id' ] );
	    $product_id = absint( $_POST[ 'product_id' ] );
	    $page_id    = absint( $_POST[ 'page_id' ] );

	    if ( $widget_id == '' && $product_id == '' && $page_id == '' ) {
		    wp_send_json_error();
	    }

	    global $post, $product;
	    $product = wc_get_product( $product_id );
	    $post    = get_post( $product_id );
	    setup_postdata( $post );

	    $settings = $this->eael_get_widget_settings( $page_id, $widget_id );
	    ob_start();
	    HelperClass::eael_product_quick_view( $product, $settings, $widget_id );
	    $data = ob_get_clean();
	    wp_reset_postdata();

	    wp_send_json_success( $data );
    }

    /**
	 * return file path which are store in theme Template directory
	 * @param $file
	 */
	public function retrive_theme_path() {
		$current_theme = wp_get_theme();
		return sprintf(
			'%s/%s',
			$current_theme->theme_root,
			$current_theme->stylesheet
		);
	}

	/**
	 * @param string $tag
	 * @param string $function_to_remove
	 * @param int|string $priority
	 */
	public function eael_forcefully_remove_action( $tag, $function_to_remove, $priority ) {
		global $wp_filter;
		if (  isset( $wp_filter[ $tag ][ $priority ] ) &&  is_array( $wp_filter[ $tag ][ $priority ] ) ) {
			foreach ( $wp_filter[ $tag ][ $priority ] as $callback_function => $registration ) {
				if ( strlen( $callback_function ) > 32 && strpos( $callback_function, $function_to_remove, 32 ) !== false || $callback_function === $function_to_remove ) {
					remove_action( $tag, $callback_function, $priority );
					break;
				}
			}
		}
	}


	/**
	 * Retrieve product quick view data
	 *
	 * @return string
	 */
	public function ajax_eael_product_gallery(){

		$ajax   = wp_doing_ajax();

		parse_str($_POST['args'], $args);

		if ( empty( $_POST['nonce'] ) ) {
			$err_msg = __( 'Insecure form submitted without security token', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			return false;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'eael_product_gallery' ) ) {
			$err_msg = __( 'Security token did not match', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			return false;
		}

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			return false;
		}

		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}
			return false;
		}

		$settings = HelperClass::eael_get_widget_settings($page_id, $widget_id);
		if (empty($settings)) {
			wp_send_json_error(['message' => __('Widget settings are not found. Did you save the widget before using load more??', 'essential-addons-for-elementor-lite')]);
		}

		if ( $widget_id == '' && $page_id == '' ) {
			wp_send_json_error();
		}

		$settings['eael_widget_id'] = $widget_id;
		$settings['eael_page_id'] = $page_id;
		$args[ 'offset' ] = (int)$args[ 'offset' ] + ( ( (int)$_REQUEST[ 'page' ] - 1 ) * (int)$args[ 'posts_per_page' ] );

		if ( isset( $_REQUEST[ 'taxonomy' ] ) && isset($_REQUEST[ 'taxonomy' ][ 'taxonomy' ]) && $_REQUEST[ 'taxonomy' ][ 'taxonomy' ] != 'all' ) {
			$args[ 'tax_query' ] = [
				$_REQUEST[ 'taxonomy' ],
			];
		}

		$template_info = $_REQUEST['template_info'];

		if ( $template_info ) {

			if ( $template_info[ 'dir' ] === 'theme' ) {
				$file_path = $this->retrive_theme_path();
			} else if($template_info[ 'dir' ] === 'pro') {
				$file_path = sprintf("%sincludes",EAEL_PRO_PLUGIN_PATH);
			} else {
				$file_path = sprintf("%sincludes",EAEL_PLUGIN_PATH);
			}

			$file_path = sprintf(
				'%s/Template/%s/%s',
				$file_path,
				$template_info[ 'name' ],
				$template_info[ 'file_name' ]
			);

            $html  = '';
			if ( $file_path ) {
				$query = new \WP_Query( $args );

				if ( $query->have_posts() ) {

					while ( $query->have_posts() ) {
						$query->the_post();
						$html .= HelperClass::include_with_variable( $file_path, [ 'settings' => $settings ] );
					}
					print $html;
					wp_reset_postdata();
				}
			}
		}
		wp_die();
	}

	/**
	 * eael_wpml_template_translation
	 * @param $id
	 * @return mixed|void
	 */
    public function eael_wpml_template_translation($id){
	    $postType = get_post_type( $id );
	    if ( 'elementor_library' === $postType ) {
		    return apply_filters( 'wpml_object_id', $id, $postType, true );
	    }
	    return $id;
    }
	
}


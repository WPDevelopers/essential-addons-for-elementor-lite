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
     * Woo Checkout
     */


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

        if ( isset( $args['found_posts'] ) && $args['found_posts'] <= $args['posts_per_page'] ){
	        $this->add_render_attribute( 'load-more', [ 'class' => 'hide-load-more' ] );
	        unset( $args['found_posts'] );
        }

	    $this->add_render_attribute( 'load-more', [
		    'class'          => "eael-load-more-button",
		    'id'             => "eael-load-more-btn-" . $this->get_id(),
		    'data-widget-id' => $this->get_id(),
		    'data-widget'    => $this->get_id(),
		    'data-page-id'   => $this->page_id,
		    'data-template'  => json_encode( [
			    'dir'       => $plugin_type,
			    'file_name' => $settings['loadable_file_name'],
			    'name'      => $this->process_directory_name()
		    ],
			    1 ),
		    'data-class'     => get_class( $this ),
		    'data-layout'    => isset( $settings['layout_mode'] ) ? $settings['layout_mode'] : "",
		    'data-page'      => 1,
		    'data-args'      => http_build_query( $args ),
	    ] );

	    if ( $max_page ) {
		    $this->add_render_attribute( 'load-more', [ 'data-max-page' => $max_page ] );
	    }

        if ( $args['posts_per_page'] != '-1' ) {
            $show_or_hide = ('true' == $settings['show_load_more'] || 1 == $settings['show_load_more'] || 'yes' == $settings['show_load_more']) ? '' : ' eael-force-hide';
            do_action( 'eael/global/before-load-more-button', $settings, $args, $plugin_type );
            ?>
            <div class="eael-load-more-button-wrap<?php echo "eael-dynamic-filterable-gallery" == $this->get_name() ? " dynamic-filter-gallery-loadmore" : ""; echo esc_attr( $show_or_hide ); ?>">
                <button <?php $this->print_render_attribute_string( 'load-more' ); ?>>
                    <span class="eael-btn-loader button__loader"></span>
                    <span class="eael_load_more_text"><?php echo esc_html($settings['show_load_more_text']) ?></span>
                </button>
            </div>
            <?php 
            do_action( 'eael/global/after-load-more-button', $settings, $args, $plugin_type );
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
			$html  = '<div class="eael-star-rating star-rating">';
			$html .= wc_get_star_rating_html( $rating, $count );
			$html .= '</div>';
		}
		return $html;
	}

	public function eael_product_wrapper_class( $classes, $product_id, $widget_name ) {

		if ( ! is_plugin_active( 'woo-variation-swatches-pro/woo-variation-swatches-pro.php' ) ) {
			return $classes;
		}

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return $classes;
		}

		if ( $product->is_type( 'variable' ) ) {
			$classes[] = 'wvs-archive-product-wrapper';
		}

		return $classes;
	}

	public function eael_woo_cart_empty_action() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		if ( isset( $_GET['empty_cart'] ) && 'yes' === esc_html( $_GET['empty_cart'] ) ) {
			WC()->cart->empty_cart();
		}
	}

    /**
	 * Update Checkout Cart Quantity via ajax call.
	 */
	public function eael_checkout_cart_qty_update() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'essential-addons-elementor' ) ) {
            die( __('Permission Denied!') );
        }

        $cart_item_key = $_POST['cart_item_key'];
		$cart_item = WC()->cart->get_cart_item( $cart_item_key );
		$cart_item_quantity = apply_filters( 'woocommerce_stock_amount_cart_item', apply_filters( 'woocommerce_stock_amount', preg_replace( "/[^0-9\.]/", '', filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT)) ), $cart_item_key );

		$passed_validation  = apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $cart_item, $cart_item_quantity );
		if ( $passed_validation ) {
			WC()->cart->set_quantity( $cart_item_key, $cart_item_quantity, true );
			wp_send_json_success(
                array(
                    'message' => __( 'Quantity updated successfully.', 'essential-addons-for-elementor-lite' ),
                    // 'cart_item_key' => $cart_item_key,
                    'cart_item_quantity' => $cart_item_quantity,
                    'cart_item_subtotal' => WC()->cart->get_product_subtotal( $cart_item['data'], $cart_item_quantity ),
                    'cart_subtotal' => WC()->cart->get_cart_subtotal(),
                    'cart_total' => WC()->cart->get_cart_total()
                )
            );
		} else {
    		wp_send_json_error(
                array(
                    'message' => __( 'Quantity update failed.', 'essential-addons-for-elementor-lite' ),
                )
            );
        }

		die();
	}

	public function change_add_woo_checkout_update_order_reviewto_cart_text( $add_to_cart_text ) {
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
                            <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/templately/logo.svg' ); ?>" alt="">
                        </div>
                        <ul class="eael-promo-temp__feature__list">
                            <li><?php _e('5,000+ Stunning Templates','essential-addons-for-elementor-lite'); ?></li>
                            <li><?php _e('Supports Elementor & Gutenberg','essential-addons-for-elementor-lite'); ?></li>
                            <li><?php _e('Powering up 200,000+ Websites','essential-addons-for-elementor-lite'); ?></li>
                            <li><?php _e('Cloud Collaboration with Team','essential-addons-for-elementor-lite'); ?></li>
                        </ul>
                        <form class="eael-promo-temp__form">
                            <label>
                                <input type="radio" value="install" class="eael-temp-promo-confirmation" name='eael-promo-temp__radio' checked>
                                <span><?php echo esc_html( $button_test ); ?></span>
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
                        <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/templately/templates-edit.jpg' ); ?>" alt="">
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

	/**
	 * eael_sanitize_template_param
     * Removes special characters that are illegal in filenames
     *
	 * @param array $template_info
	 *
     * @access public
	 * @return array
     * @since 5.0.4
	 */
    public function eael_sanitize_template_param( $template_info ){
	    $template_info = array_map( 'sanitize_text_field', $template_info );
	    return array_map( 'sanitize_file_name', $template_info );
    }

	/**
	 * sanitize_taxonomy_data
     * Sanitize all value for tax query
     *
	 * @param array $tax_list taxonomy param list
	 *
     * @access protected
	 * @return array|array[]|string[]
	 * @since 5.0.4
	 */
    protected function sanitize_taxonomy_data( $tax_list ){
	    return array_map( function ( $param ) {
		    return is_array( $param ) ? array_map( 'sanitize_text_field', $param ) : sanitize_text_field( $param );
	    }, $tax_list );
    }

	/**
	 * eael_clear_widget_cache_data
     * Remove cache from transient which contains widget data
     *
     * @access public
     * @return array
     * @since 5.0.7
	 */
	public function eael_clear_widget_cache_data() {
		global $wpdb;

		check_ajax_referer( 'essential-addons-elementor', 'security' );

		$ac_name     = sanitize_text_field( $_POST['ac_name'] );
		$hastag      = sanitize_text_field( $_POST['hastag'] );
		$c_key       = sanitize_text_field( $_POST['c_key'] );
		$c_secret    = sanitize_text_field( $_POST['c_secret'] );
		$widget_id   = sanitize_text_field( $_POST['widget_id'] );
		$permalink   = sanitize_text_field( $_POST['page_permalink'] );
        $page_id     = url_to_postid($permalink);
        
        $settings = $this->eael_get_widget_settings($page_id, $widget_id);
        $twitter_v2 = ! empty( $settings['eael_twitter_api_v2'] ) && 'yes' === $settings['eael_twitter_api_v2'] ? true : false;

		$key_pattern = '_transient_' . $ac_name . '%' . md5( $hastag . $c_key . $c_secret ) . '_tf_cache';
        
        if( $twitter_v2 ){
            $bearer_token = $settings['eael_twitter_feed_bearer_token'];
            $key_pattern = '_transient_' . $ac_name . '%' . md5( $hastag . $c_key . $c_secret . $bearer_token ) . '_tf_cache';
        }

		$sql     = "SELECT `option_name` AS `name`
            FROM  $wpdb->options
            WHERE `option_name` LIKE '$key_pattern'
            ORDER BY `option_name`";
		$results = $wpdb->get_results( $sql );

		foreach ( $results as $transient ) {
			$cache_key = substr( $transient->name, 11 );
			delete_transient( $cache_key );
		}

		wp_send_json_success();
	}

	public function promotion_message_on_admin_screen() {
		?>
        <div id="eael-admin-promotion-message" class="eael-admin-promotion-message">
            <i class="e-notice__dismiss eael-admin-promotion-close" role="button" aria-label="Dismiss" tabindex="0"></i>
			<?php printf( __( "<p> <i>📣</i> NEW: Essential Addons Pro 5.8 is here, with new '<a target='_blank' href='%s'>Fancy Chart</a>' widget & more! Check out the <a target='_blank' href='%s'>Changelog</a> for more details 🎉</p>", "essential-addons-for-elementor-lite" ), esc_url( 'https://essential-addons.com/elementor/fancy-chart/' ), esc_url( 'https://essential-addons.com/elementor/changelog/' ) ); ?>
        </div>
		<?php
	}

	/**
	 * remove_admin_notice
	 *
	 *
	 * @return void
	 */
	public function remove_admin_notice() {
		$current_screen = get_current_screen();
		if ( $current_screen->id == 'toplevel_page_eael-settings' ) {

			remove_all_actions( 'user_admin_notices' );
			remove_all_actions( 'admin_notices' );

            // To showing notice in EA settings page we have to use 'eael_admin_notices' action hook
			add_action( 'admin_notices', function () {
				do_action( 'eael_admin_notices' );
			} );

			/*Added admin notice which is basically uses for display new promotion message*/
			if ( get_option( 'eael_admin_promotion' ) < self::EAEL_PROMOTION_FLAG ) {
				add_action( 'eael_admin_notices', array( $this, 'promotion_message_on_admin_screen' ), 1 );
			}
		}
	}

	/**
	 * eael_show_admin_menu_notice
     *
     * Update flag if user visit Essential Addons setting page only first time
     * @return void
     * @since 5.1.0
	 */
	public function eael_show_admin_menu_notice() {
		if ( get_option( 'eael_admin_menu_notice' ) < self::EAEL_ADMIN_MENU_FLAG ) {
            update_option( 'eael_admin_menu_notice',self::EAEL_ADMIN_MENU_FLAG,'no' );
		}
	}

	/**
	 * Checking that is actually elementor activated and works
     *
	 * @return bool
	 */
	public function is_activate_elementor() {
		return defined( 'ELEMENTOR_VERSION' ) && class_exists( 'Elementor\Plugin' );
	}

	public function essential_blocks_promo_admin_js_template() {
		$eb_logo          = EAEL_PLUGIN_URL . 'assets/admin/images/eb-new.svg';
		$eb_promo_cross   = EAEL_PLUGIN_URL . 'assets/admin/images/essential-blocks/cross.svg';
		$eb_promo_img1    = EAEL_PLUGIN_URL . 'assets/admin/images/essential-blocks/eb-promo-img1.gif';
		$eb_promo_img2    = EAEL_PLUGIN_URL . 'assets/admin/images/essential-blocks/eb-promo-img2.gif';
		$eb_promo_img3    = EAEL_PLUGIN_URL . 'assets/admin/images/essential-blocks/eb-promo-img3.gif';
		$eb_promo_img4    = EAEL_PLUGIN_URL . 'assets/admin/images/essential-blocks/eb-promo-img4.jpg';
		$eb_promo_img5    = EAEL_PLUGIN_URL . 'assets/admin/images/essential-blocks/eb-promo-img5.png';
		$eb_not_installed = HelperClass::get_local_plugin_data( 'essential-blocks/essential-blocks.php' ) === false;
		$action           = $eb_not_installed ? 'install' : 'activate';
		$button_title     = $eb_not_installed ? esc_html__( 'Try Essential Blocks', 'essential-addons-for-elementor-lite' ) : esc_html__( 'Activate', 'essential-addons-for-elementor-lite' );
		$nonce            = wp_create_nonce( 'essential-addons-elementor' );
		?>
        <script id="eael-gb-eb-button-template" type="text/html">
            <button id="eael-eb-popup-button" type="button" class="components-button is-primary">
                <img width="20" src="<?php echo esc_url( $eb_logo ); ?>" alt=""><?php esc_html_e( 'Essential Blocks', 'essential-addons-for-elementor-lite' ); ?>
            </button>
        </script>

        <script id="eael-gb-eb-popup-template" type="text/html">
            <div class="eael-gb-eb-popup">
                <div class="eael-gb-eb-header">
                    <img src="<?php echo esc_url( $eb_promo_cross ); ?>" class="eael-gb-eb-dismiss" alt="">
                    <div class="eael-gb-eb-tooltip"><?php esc_html_e( 'Close dialog', 'essential-addons-for-elementor-lite' ); ?></div>
                </div>
                <div class="eael-gb-eb-popup-content --page-1">
                    <div class="eael-gb-eb-content">
                        <div class="eael-gb-eb-content-image">
                            <img src="<?php echo esc_url( $eb_promo_img1 ); ?>" alt="">
                        </div>
                        <div class="eael-gb-eb-content-pagination">
                            <span class="active" data-page="1"></span>
                            <span data-page="2"></span>
                            <span data-page="3"></span>
                            <span data-page="4"></span>
                            <span data-page="5"></span>
                        </div>
                        <div class="eael-gb-eb-content-info">
                            <h3><?php esc_html_e( 'Supercharge Your Gutenberg Experience With Essential Blocks', 'essential-addons-for-elementor-lite' ); ?></h3>
                            <p><?php esc_html_e( 'If you like Essential Addons for Elementor, check out Essential Blocks, the ultimate block library for Gutenberg that is trusted by more than 60,000+ web creators.', 'essential-addons-for-elementor-lite' ); ?></p>
                            <button class="eael-gb-eb-install components-button is-primary" data-action="<?php echo esc_attr( $action ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php echo esc_html( $button_title ); ?></button>
                        </div>
                    </div>
                    <div class="eael-gb-eb-footer">
	                    <button class="eael-gb-eb-never-show" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php esc_html_e( 'Never Show Again', 'essential-addons-for-elementor-lite' ); ?></button>
                        <button class="eael-gb-eb-prev"><?php esc_html_e( 'Previous', 'essential-addons-for-elementor-lite' ); ?></button>
                        <button class="eael-gb-eb-next"><?php esc_html_e( 'Next', 'essential-addons-for-elementor-lite' ); ?></button>
                    </div>
                </div>
            </div>
        </script>

        <script id="eael-gb-eb-button-template-page-1" type="text/html">
            <div>
                <div class="eael-gb-eb-content-image">
                    <img src="<?php echo esc_url( $eb_promo_img1 ); ?>" alt="">
                </div>
                <div class="eael-gb-eb-content-info">
                    <h3><?php esc_html_e( 'Supercharge Your Gutenberg Experience With Essential Blocks', 'essential-addons-for-elementor-lite' ); ?></h3>
                    <p><?php esc_html_e( 'If you like Essential Addons for Elementor, check out Essential Blocks, the ultimate block library for Gutenberg that is trusted by more than 60,000+ web creators.', 'essential-addons-for-elementor-lite' ) ?></p>
                    <button class="eael-gb-eb-install components-button is-primary" data-action="<?php echo esc_attr( $action ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php echo esc_html( $button_title ); ?></button>
                </div>
            </div>
        </script>

        <script id="eael-gb-eb-button-template-page-2" type="text/html">
            <div>
                <div class="eael-gb-eb-content-image">
                    <img src="<?php echo esc_url( $eb_promo_img2 ); ?>" alt="">
                </div>
                <div class="eael-gb-eb-content-info">
                    <h3><?php esc_html_e( '40+ Amazing Gutenberg Blocks', 'essential-addons-for-elementor-lite' ); ?></h3>
                    <p><?php esc_html_e( 'Create & design your WordPress websites just the way you want with more than 40 amazing, ready blocks from Essential Blocks for Gutenberg.', 'essential-addons-for-elementor-lite' ) ?></p>
                    <button class="eael-gb-eb-install components-button is-primary" data-action="<?php echo esc_attr( $action ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php echo esc_html( $button_title ); ?></button>
                </div>
            </div>
        </script>

        <script id="eael-gb-eb-button-template-page-3" type="text/html">
            <div>
                <div class="eael-gb-eb-content-image">
                    <img src="<?php echo esc_url( $eb_promo_img3 ); ?>" alt="">
                </div>
                <div class="eael-gb-eb-content-info">
                    <h3><?php esc_html_e( 'Useful Block Control Option', 'essential-addons-for-elementor-lite' ); ?></h3>
                    <p><?php esc_html_e( 'Get the fastest loading time and smoothest experience on your web page by enabling and disabling individual blocks as per your requirements.', 'essential-addons-for-elementor-lite' ) ?></p>
                    <button class="eael-gb-eb-install components-button is-primary" data-action="<?php echo esc_attr( $action ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php echo esc_html( $button_title ); ?></button>
                </div>
            </div>
        </script>

        <script id="eael-gb-eb-button-template-page-4" type="text/html">
            <div>
                <div class="eael-gb-eb-content-image">
                    <img src="<?php echo esc_url( $eb_promo_img4 ); ?>" alt="">
                </div>
                <div class="eael-gb-eb-content-info">
                    <h3><?php esc_html_e( 'Access To Thousands Of Ready Gutenberg Templates', 'essential-addons-for-elementor-lite' ); ?></h3>
                    <p><?php esc_html_e( 'Design unique websites using ready Gutenberg templates from Templately with absolute ease and instantly grab attention.', 'essential-addons-for-elementor-lite' ) ?></p>
                    <button class="eael-gb-eb-install components-button is-primary" data-action="<?php echo esc_attr( $action ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php echo esc_html( $button_title ); ?></button>
                </div>
            </div>
        </script>

        <script id="eael-gb-eb-button-template-page-5" type="text/html">
            <div>
                <div class="eael-gb-eb-content-image">
                    <img src="<?php echo esc_url( $eb_promo_img5 ); ?>" alt="">
                </div>
                <div class="eael-gb-eb-content-info">
                    <h3><?php esc_html_e( 'Try Essential Blocks Today!', 'essential-addons-for-elementor-lite' ); ?></h3>
                    <p><?php printf( __( 'Want to get started with Essential Blocks now? Check out %scomplete guides for each blocks%s to learn more about this ultimate block library for Gutenberg.', 'essential-addons-for-elementor-lite' ), '<a href="https://essential-blocks.com/demo" target="_blank">', '</a>' ) ?></p>
                    <button class="eael-gb-eb-install components-button is-primary" data-action="<?php echo esc_attr( $action ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php echo esc_html( $button_title ); ?></button>
                    <button class="eael-gb-eb-never-show" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php esc_html_e( 'Never Show Again', 'essential-addons-for-elementor-lite' ); ?></button>
                </div>
            </div>
        </script>
		<?php
	}

	public function eael_post_view_count() {
		if ( is_single() ) {
			$post_id    = get_the_ID();
			$view_count = absint( get_post_meta( $post_id, '_eael_post_view_count', true ) );
			update_post_meta( $post_id, '_eael_post_view_count', ++ $view_count );
		}
	}
}


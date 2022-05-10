<?php
/**
 * Class trait Ajax_Handler file
 *
 * @package Essential-addons-for-elementor-lite\Traits
 */

namespace Essential_Addons_Elementor\Traits;

use Essential_Addons_Elementor\Classes\Helper as HelperClass;
use Essential_Addons_Elementor\Template\Woocommerce\Checkout\Woo_Checkout_Helper;
use Essential_Addons_Elementor\Traits\Template_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Essential Addons ajax request handler
 *
 * Manage all ajax request for EA
 *
 * @class       Ajax_Handler
 * @since       5.0.9
 * @package     Essential-addons-for-elementor-lite\Traits
 */
trait Ajax_Handler {
	//use Template_Query;
	/**
	 * init_ajax_hooks
	 */
	public function init_ajax_hooks() {

		add_action( 'wp_ajax_load_more', array( $this, 'ajax_load_more' ) );
		add_action( 'wp_ajax_nopriv_load_more', array( $this, 'ajax_load_more' ) );

		add_action( 'wp_ajax_woo_product_pagination_product', array( $this, 'eael_woo_pagination_product_ajax' ) );
		add_action( 'wp_ajax_nopriv_woo_product_pagination_product', array(
			$this,
			'eael_woo_pagination_product_ajax'
		) );

		add_action( 'wp_ajax_woo_product_pagination', array( $this, 'eael_woo_pagination_ajax' ) );
		add_action( 'wp_ajax_nopriv_woo_product_pagination', array( $this, 'eael_woo_pagination_ajax' ) );

		add_action( 'wp_ajax_eael_product_add_to_cart', array( $this, 'eael_product_add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_eael_product_add_to_cart', array( $this, 'eael_product_add_to_cart' ) );

		add_action( 'wp_ajax_woo_checkout_update_order_review', [ $this, 'woo_checkout_update_order_review' ] );
		add_action( 'wp_ajax_nopriv_woo_checkout_update_order_review', [ $this, 'woo_checkout_update_order_review' ] );

		add_action( 'wp_ajax_nopriv_eael_product_quickview_popup', [ $this, 'eael_product_quickview_popup' ] );
		add_action( 'wp_ajax_eael_product_quickview_popup', [ $this, 'eael_product_quickview_popup' ] );

		add_action( 'wp_ajax_nopriv_eael_product_gallery', [ $this, 'ajax_eael_product_gallery' ] );
		add_action( 'wp_ajax_eael_product_gallery', [ $this, 'ajax_eael_product_gallery' ] );

		add_action( 'wp_ajax_eael_select2_search_post', [ $this, 'select2_ajax_posts_filter_autocomplete' ] );
		add_action( 'wp_ajax_nopriv_eael_select2_search_post', [ $this, 'select2_ajax_posts_filter_autocomplete' ] );

		add_action( 'wp_ajax_eael_select2_get_title', [ $this, 'select2_ajax_get_posts_value_titles' ] );
		add_action( 'wp_ajax_nopriv_eael_select2_get_title', [ $this, 'select2_ajax_get_posts_value_titles' ] );

		if ( is_admin() ) {
			add_action( 'wp_ajax_save_settings_with_ajax', array( $this, 'save_settings' ) );
			add_action( 'wp_ajax_clear_cache_files_with_ajax', array( $this, 'clear_cache_files' ) );
			add_action( 'wp_ajax_eael_admin_promotion', array( $this, 'eael_admin_promotion' ) );
		}
	}

	/**
	 * Ajax Load More
	 * This function is responsible for get the post data.
	 * It will return HTML markup with AJAX call and with normal call.
	 *
	 * @access public
	 * @return false|void of a html markup with AJAX call.
	 * @return array of content and found posts count without AJAX call.
	 * @since 3.1.0
	 */
	public function ajax_load_more() {
		$ajax = wp_doing_ajax();

		wp_parse_str( $_POST['args'], $args );
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

		$settings = HelperClass::eael_get_widget_settings( $page_id, $widget_id );

		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings are not found. Did you save the widget before using load more??', 'essential-addons-for-elementor-lite' ) ] );
		}

		$settings['eael_widget_id'] = $widget_id;
		$settings['eael_page_id']   = $page_id;
		$html                       = '';
		$class                      = '\\' . str_replace( '\\\\', '\\', $_REQUEST['class'] );
		$args['offset']             = (int) $args['offset'] + ( ( (int) $_REQUEST['page'] - 1 ) * (int) $args['posts_per_page'] );

		if ( isset( $_REQUEST['taxonomy'] ) && isset( $_REQUEST['taxonomy']['taxonomy'] ) && $_REQUEST['taxonomy']['taxonomy'] != 'all' ) {
			$args['tax_query'] = [
				$this->sanitize_taxonomy_data( $_REQUEST['taxonomy'] ),
			];
		}

		if ( $class == '\Essential_Addons_Elementor\Elements\Post_Grid' && $settings['orderby'] === 'rand' ) {
			$args['post__not_in'] = array_map( 'intval', array_unique( $_REQUEST['post__not_in'] ) );
			unset( $args['offset'] );
		}

		// ensure control name compatibility to old code if it is post block
		if ( $class === '\Essential_Addons_Elementor\Pro\Elements\Post_Block' ) {
			$settings ['post_block_hover_animation']    = $settings['eael_post_block_hover_animation'];
			$settings ['show_read_more_button']         = $settings['eael_show_read_more_button'];
			$settings ['eael_post_block_bg_hover_icon'] = ( isset( $settings['__fa4_migrated']['eael_post_block_bg_hover_icon_new'] ) || empty( $settings['eael_post_block_bg_hover_icon'] ) ) ? $settings['eael_post_block_bg_hover_icon_new']['value'] : $settings['eael_post_block_bg_hover_icon'];
			$settings ['expanison_indicator']           = $settings['excerpt_expanison_indicator'];
		}
		if ( $class === '\Essential_Addons_Elementor\Elements\Post_Timeline' ) {
			$settings ['expanison_indicator'] = $settings['excerpt_expanison_indicator'];
		}
		if ( $class === '\Essential_Addons_Elementor\Pro\Elements\Dynamic_Filterable_Gallery' ) {
			$settings['eael_section_fg_zoom_icon'] = ( isset( $settings['__fa4_migrated']['eael_section_fg_zoom_icon_new'] ) || empty( $settings['eael_section_fg_zoom_icon'] ) ? $settings['eael_section_fg_zoom_icon_new']['value'] : $settings['eael_section_fg_zoom_icon'] );
			$settings['eael_section_fg_link_icon'] = ( isset( $settings['__fa4_migrated']['eael_section_fg_link_icon_new'] ) || empty( $settings['eael_section_fg_link_icon'] ) ? $settings['eael_section_fg_link_icon_new']['value'] : $settings['eael_section_fg_link_icon'] );
			$settings['show_load_more_text']       = $settings['eael_fg_loadmore_btn_text'];
			$settings['layout_mode']               = isset( $settings['layout_mode'] ) ? $settings['layout_mode'] : 'masonry';

			$exclude_ids = json_decode( html_entity_decode( stripslashes ( $_POST['exclude_ids'] ) ) );
			$args['post__not_in'] = ( !empty( $_POST['exclude_ids'] ) ) ? array_map( 'intval', array_unique($exclude_ids) ) : array();
			$active_term_id = ( !empty( $_POST['active_term_id'] ) ) ? intval( $_POST['active_term_id'] ) : 0;
			$active_taxonomy = ( !empty( $_POST['active_taxonomy'] ) ) ? sanitize_text_field( $_POST['active_taxonomy'] ) : '';
			
			if( 0 < $active_term_id && 
				!empty( $active_taxonomy ) && 
				!empty($args['tax_query']) 
			) {
				foreach ($args['tax_query'] as $key => $taxonomy) {
					if (isset($taxonomy['taxonomy']) && $taxonomy['taxonomy'] === $active_taxonomy) {
						$args['tax_query'][$key]['terms'] = [$active_term_id];
					}
				}
			}
		}

		$link_settings = [
			'image_link_nofollow'         => ! empty( $settings['image_link_nofollow'] ) ? 'rel="nofollow"' : '',
			'image_link_target_blank'     => ! empty( $settings['image_link_target_blank'] ) ? 'target="_blank"' : '',
			'title_link_nofollow'         => ! empty( $settings['title_link_nofollow'] ) ? 'rel="nofollow"' : '',
			'title_link_target_blank'     => ! empty( $settings['title_link_target_blank'] ) ? 'target="_blank"' : '',
			'read_more_link_nofollow'     => ! empty( $settings['read_more_link_nofollow'] ) ? 'rel="nofollow"' : '',
			'read_more_link_target_blank' => ! empty( $settings['read_more_link_target_blank'] ) ? 'target="_blank"' : '',
		];

		$template_info = $this->eael_sanitize_template_param( $_REQUEST['template_info'] );

		if ( $template_info ) {

			if ( $template_info['dir'] === 'theme' ) {
				$dir_path = $this->retrive_theme_path();
			} else if ( $template_info['dir'] === 'pro' ) {
				$dir_path = sprintf( "%sincludes", EAEL_PRO_PLUGIN_PATH );
			} else {
				$dir_path = sprintf( "%sincludes", EAEL_PLUGIN_PATH );
			}

			$file_path = realpath( sprintf(
				'%s/Template/%s/%s',
				$dir_path,
				$template_info['name'],
				$template_info['file_name']
			) );

			if ( ! $file_path || 0 !== strpos( $file_path, realpath( $dir_path ) ) ) {
				wp_send_json_error( 'Invalid template', 'invalid_template', 400 );
			}

			if ( $file_path ) {
				$query = new \WP_Query( $args );
				$found_posts = $query->found_posts;
				$iterator = 0;

				if ( $query->have_posts() ) {
					if ( $class === '\Essential_Addons_Elementor\Elements\Product_Grid' && boolval( $settings['show_add_to_cart_custom_text'] ) ) {

						$add_to_cart_text = [
							'add_to_cart_simple_product_button_text'   => $settings['add_to_cart_simple_product_button_text'],
							'add_to_cart_variable_product_button_text' => $settings['add_to_cart_variable_product_button_text'],
							'add_to_cart_grouped_product_button_text'  => $settings['add_to_cart_grouped_product_button_text'],
							'add_to_cart_external_product_button_text' => $settings['add_to_cart_external_product_button_text'],
							'add_to_cart_default_product_button_text'  => $settings['add_to_cart_default_product_button_text'],
						];
						$this->change_add_woo_checkout_update_order_reviewto_cart_text( $add_to_cart_text );
					}

					if ( $class === '\Essential_Addons_Elementor\Pro\Elements\Dynamic_Filterable_Gallery' ) {
						$html .= "<div class='found_posts' style='display: none;'>{$found_posts}</div>";
					}

					while ( $query->have_posts() ) {
						$query->the_post();

						$html .= HelperClass::include_with_variable( $file_path, [
							'settings'      => $settings,
							'link_settings' => $link_settings,
							'iterator'      => $iterator
						] );
						$iterator ++;
					}
				} else {
					$html .= __( '<p class="no-posts-found">No posts found!</p>', 'essential-addons-for-elementor-lite' );
				}
			}
		}


		while ( ob_get_status() ) {
			ob_end_clean();
		}
		if ( function_exists( 'gzencode' ) ) {
			$response = gzencode( wp_json_encode( $html ) );

			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Encoding: gzip' );
			header( 'Content-Length: ' . strlen( $response ) );

			printf( '%1$s', $response );
		} else {
			echo wp_kses_post( $html );
		}
		wp_die();
	}

	/**
	 * Woo Pagination Product Ajax
	 * get product list when pagination number/dot click by ajax
	 *
	 * @access public
	 * @return void of a html markup with AJAX call.
	 * @since 3.1.0
	 */
	public function eael_woo_pagination_product_ajax() {

		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
			wp_send_json_error( $err_msg );
		}

		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
			wp_send_json_error( $err_msg );
		}

		$settings = HelperClass::eael_get_widget_settings( $page_id, $widget_id );
		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings are not found. Did you save the widget before using load more??', 'essential-addons-for-elementor-lite' ) ] );
		}
		$settings['eael_page_id'] = $page_id;
		wp_parse_str( $_REQUEST['args'], $args );

		$paginationNumber = absint( $_POST['number'] );
		$paginationLimit  = absint( $_POST['limit'] );

		$args['posts_per_page'] = $paginationLimit;

		if ( $paginationNumber == "1" ) {
			$paginationOffsetValue = "0";
		} else {
			$paginationOffsetValue = ( $paginationNumber - 1 ) * $paginationLimit;
			$args['offset']        = $paginationOffsetValue;
		}


		$template_info = $this->eael_sanitize_template_param( $_REQUEST['templateInfo'] );

		$this->set_widget_name( $template_info['name'] );
		$template = realpath( $this->get_template( $template_info['file_name'] ) );

		ob_start();
		$query = new \WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				include( $template );
			}
			wp_reset_postdata();
		}
		echo ob_get_clean();
		wp_die();
	}

	/**
	 * Woo Pagination Ajax
	 * Return pagination list for product post type while used Product_Grid widget
	 *
	 * @access public
	 * @return void of a html markup with AJAX call.
	 * @since unknown
	 */
	public function eael_woo_pagination_ajax() {

		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( ! empty( $_POST['page_id'] ) ) {
			$page_id = intval( $_POST['page_id'], 10 );
		} else {
			$err_msg = __( 'Page ID is missing', 'essential-addons-for-elementor-lite' );
			wp_send_json_error( $err_msg );
		}

		if ( ! empty( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field( $_POST['widget_id'] );
		} else {
			$err_msg = __( 'Widget ID is missing', 'essential-addons-for-elementor-lite' );
			wp_send_json_error( $err_msg );
		}

		$settings = HelperClass::eael_get_widget_settings( $page_id, $widget_id );

		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings are not found. Did you save the widget before using load more??', 'essential-addons-for-elementor-lite' ) ] );
		}

		$settings['eael_page_id'] = $page_id;
		wp_parse_str( $_REQUEST['args'], $args );

		$paginationNumber          = absint( $_POST['number'] );
		$paginationLimit           = absint( $_POST['limit'] );
		$pagination_Count          = intval( $args['total_post'] );
		$pagination_Paginationlist = ceil( $pagination_Count / $paginationLimit );
		$last                      = ceil( $pagination_Paginationlist );
		$paginationprev            = $paginationNumber - 1;
		$paginationnext            = $paginationNumber + 1;

		if ( $paginationNumber > 1 ) {
			$paginationprev;
		}
		if ( $paginationNumber < $last ) {
			$paginationnext;
		}

		$adjacents                    = "2";
		$next_label                   = sanitize_text_field( $settings['pagination_next_label'] );
		$prev_label                   = sanitize_text_field( $settings['pagination_prev_label'] );
		$settings['eael_widget_name'] = realpath( sanitize_file_name( $_REQUEST['template_name'] ) );
		$setPagination                = "";

		if ( $pagination_Paginationlist > 0 ) {

			$setPagination .= "<ul class='page-numbers'>";

			if ( 1 < $paginationNumber ) {
				$setPagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers'   data-pnumber='$paginationprev' >$prev_label</a></li>";
			}

			if ( $pagination_Paginationlist < 7 + ( $adjacents * 2 ) ) {

				for ( $pagination = 1; $pagination <= $pagination_Paginationlist; $pagination ++ ) {
					$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", $active, $pagination );
				}

			} else if ( $pagination_Paginationlist > 5 + ( $adjacents * 2 ) ) {

				if ( $paginationNumber < 1 + ( $adjacents * 2 ) ) {
					for ( $pagination = 1; $pagination <= 4 + ( $adjacents * 2 ); $pagination ++ ) {

						$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
						$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", $active, $pagination );
					}
					$setPagination .= "<li class='pagitext dots'>...</li>";
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", $active, $pagination );

				} elseif ( $pagination_Paginationlist - ( $adjacents * 2 ) > $paginationNumber && $paginationNumber > ( $adjacents * 2 ) ) {
					$active        = '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", $active, 1 );
					$setPagination .= "<li class='pagitext dots'>...</li>";
					for ( $pagination = $paginationNumber - $adjacents; $pagination <= $paginationNumber + $adjacents; $pagination ++ ) {
						$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
						$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", $active, $pagination );
					}

					$setPagination .= "<li class='pagitext dots'>...</li>";
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", $active, $last );

				} else {
					$active        = '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", $active, 1 );
					$setPagination .= "<li class='pagitext dots'>...</li>";
					for ( $pagination = $last - ( 2 + ( $adjacents * 2 ) ); $pagination <= $last; $pagination ++ ) {
						$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
						$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", $active, $pagination );
					}
				}

			} else {
				for ( $pagination = 1; $pagination <= $pagination_Paginationlist; $pagination ++ ) {
					$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", $active, $pagination );
				}

			}

			if ( $paginationNumber < $pagination_Paginationlist ) {
				$setPagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-pnumber='$paginationnext' >$next_label</a></li>";
			}

			$setPagination .= "</ul>";
		}

		printf( '%1$s', $setPagination );
		wp_die();
	}

	/**
	 * Product Add to Cart
	 * added product in cart through ajax
	 *
	 * @access public
	 * @return void of a html markup with AJAX call.
	 * @since unknown
	 */
	public function eael_product_add_to_cart() {

		$ajax       = wp_doing_ajax();
		$cart_items = isset( $_POST['cart_item_data'] ) ? $_POST['cart_item_data'] : [];
		$variation  = [];
		if ( ! empty( $cart_items ) ) {
			foreach ( $cart_items as $key => $value ) {
				if ( preg_match( "/^attribute*/", $value['name'] ) ) {
					$variation[ $value['name'] ] = sanitize_text_field( $value['value'] );
				}
			}
		}

		if ( isset( $_POST['product_data'] ) ) {
			foreach ( $_POST['product_data'] as $item ) {
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

	/**
	 * Woo Checkout Update Order Review
	 * return order review data
	 *
	 * @access public
	 * @return void
	 * @since 4.0.0
	 */
	public function woo_checkout_update_order_review() {
		$setting = $_POST['orderReviewData'];
		ob_start();
		Woo_Checkout_Helper::checkout_order_review_default( $setting );
		$woo_checkout_update_order_review = ob_get_clean();

		wp_send_json(
			array(
				'order_review' => $woo_checkout_update_order_review,
			)
		);
	}

	/**
	 * Eael Product Quick View Popup
	 * Retrieve product quick view data
	 *
	 * @access public
	 * @return void
	 * @since 4.0.0
	 */
	public function eael_product_quickview_popup() {
		//check nonce
		check_ajax_referer( 'essential-addons-elementor', 'security' );
		$widget_id  = sanitize_key( $_POST['widget_id'] );
		$product_id = absint( $_POST['product_id'] );
		$page_id    = absint( $_POST['page_id'] );

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
	 * Ajax Eael Product Gallery
	 * Retrieve product quick view data
	 *
	 * @access public
	 * @return false|void
	 * @since 4.0.0
	 */
	public function ajax_eael_product_gallery() {

		$ajax = wp_doing_ajax();

		wp_parse_str( $_POST['args'], $args );

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

		$settings = HelperClass::eael_get_widget_settings( $page_id, $widget_id );
		if ( empty( $settings ) ) {
			wp_send_json_error( [ 'message' => __( 'Widget settings are not found. Did you save the widget before using load more??', 'essential-addons-for-elementor-lite' ) ] );
		}

		if ( $widget_id == '' && $page_id == '' ) {
			wp_send_json_error();
		}

		$settings['eael_widget_id'] = $widget_id;
		$settings['eael_page_id']   = $page_id;
		$args['offset']             = (int) $args['offset'] + ( ( (int) $_REQUEST['page'] - 1 ) * (int) $args['posts_per_page'] );

		if ( isset( $_REQUEST['taxonomy'] ) && isset( $_REQUEST['taxonomy']['taxonomy'] ) && $_REQUEST['taxonomy']['taxonomy'] != 'all' ) {
			$args['tax_query'] = [
				$this->sanitize_taxonomy_data( $_REQUEST['taxonomy'] ),
			];
		}

		$template_info = $this->eael_sanitize_template_param( $_REQUEST['template_info'] );

		if ( $template_info ) {

			if ( $template_info['dir'] === 'theme' ) {
				$dir_path = $this->retrive_theme_path();
			} else if ( $template_info['dir'] === 'pro' ) {
				$dir_path = sprintf( "%sincludes", EAEL_PRO_PLUGIN_PATH );
			} else {
				$dir_path = sprintf( "%sincludes", EAEL_PLUGIN_PATH );
			}

			$file_path = realpath( sprintf(
				'%s/Template/%s/%s',
				$dir_path,
				$template_info['name'],
				$template_info['file_name']
			) );

			if ( ! $file_path || 0 !== strpos( $file_path, realpath( $dir_path ) ) ) {
				wp_send_json_error( 'Invalid template', 'invalid_template', 400 );
			}

			$html = '';
			if ( $file_path ) {
				$query = new \WP_Query( $args );

				if ( $query->have_posts() ) {

					while ( $query->have_posts() ) {
						$query->the_post();
						$html .= HelperClass::include_with_variable( $file_path, [ 'settings' => $settings ] );
					}
					printf( '%1$s', $html );
					wp_reset_postdata();
				}
			}
		}
		wp_die();
	}

	/**
	 * Select2 Ajax Posts Filter Autocomplete
	 * Fetch post/taxonomy data and render in Elementor control select2 ajax search box
	 *
	 * @access public
	 * @return void
	 * @since 4.0.0
	 */
	public function select2_ajax_posts_filter_autocomplete() {
		$post_type   = 'post';
		$source_name = 'post_type';

		if ( ! empty( $_GET['post_type'] ) ) {
			$post_type = sanitize_text_field( $_GET['post_type'] );
		}

		if ( ! empty( $_GET['source_name'] ) ) {
			$source_name = sanitize_text_field( $_GET['source_name'] );
		}

		$search  = ! empty( $_GET['term'] ) ? sanitize_text_field( $_GET['term'] ) : '';
		$results = $post_list = [];
		switch ( $source_name ) {
			case 'taxonomy':
				$args = [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'search'     => $search,
					'number'     => '5',
				];

				if ( $post_type !== 'all' ) {
					$args['taxonomy'] = $post_type;
				}

				$post_list = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				$users = [];

				foreach ( get_users( [ 'search' => "*{$search}*" ] ) as $user ) {
					$user_id           = $user->ID;
					$user_name         = $user->display_name;
					$users[ $user_id ] = $user_name;
				}

				$post_list = $users;
				break;
			default:
				$post_list = HelperClass::get_query_post_list( $post_type, 10, $search );
		}

		if ( ! empty( $post_list ) ) {
			foreach ( $post_list as $key => $item ) {
				$results[] = [ 'text' => $item, 'id' => $key ];
			}
		}
		wp_send_json( [ 'results' => $results ] );
	}

	/**
	 * Select2 Ajax Get Posts Value Titles
	 * get selected value to show elementor editor panel in select2 ajax search box
	 *
	 * @access public
	 * @return void
	 * @since 4.0.0
	 */
	public function select2_ajax_get_posts_value_titles() {

		if ( empty( $_POST['id'] ) ) {
			wp_send_json_error( [] );
		}

		if ( empty( array_filter( $_POST['id'] ) ) ) {
			wp_send_json_error( [] );
		}
		$ids         = array_map( 'intval', $_POST['id'] );
		$source_name = ! empty( $_POST['source_name'] ) ? sanitize_text_field( $_POST['source_name'] ) : '';

		switch ( $source_name ) {
			case 'taxonomy':
				$args = [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'include'    => implode( ',', $ids ),
				];

				if ( $_POST['post_type'] !== 'all' ) {
					$args['taxonomy'] = sanitize_text_field( $_POST['post_type'] );
				}

				$response = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				$users = [];

				foreach ( get_users( [ 'include' => $ids ] ) as $user ) {
					$user_id           = $user->ID;
					$user_name         = $user->display_name;
					$users[ $user_id ] = $user_name;
				}

				$response = $users;
				break;
			default:
				$post_info = get_posts( [
					'post_type' => sanitize_text_field( $_POST['post_type'] ),
					'include'   => implode( ',', $ids )
				] );
				$response  = wp_list_pluck( $post_info, 'post_title', 'ID' );
		}

		if ( ! empty( $response ) ) {
			wp_send_json_success( [ 'results' => $response ] );
		} else {
			wp_send_json_error( [] );
		}
	}

	/**
	 * Save Settings
	 * Save EA settings data through ajax request
	 *
	 * @access public
	 * @return  void
	 * @since 1.1.2
	 */
	public function save_settings() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		if ( ! isset( $_POST['fields'] ) ) {
			return;
		}

		wp_parse_str( $_POST['fields'], $settings );

		if ( ! empty( $_POST['is_login_register'] ) ) {
			// Saving Login | Register Related Data
			if ( isset( $settings['recaptchaSiteKey'] ) ) {
				update_option( 'eael_recaptcha_sitekey', sanitize_text_field( $settings['recaptchaSiteKey'] ) );
			}
			if ( isset( $settings['recaptchaSiteSecret'] ) ) {
				update_option( 'eael_recaptcha_secret', sanitize_text_field( $settings['recaptchaSiteSecret'] ) );
			}
			if ( isset( $settings['recaptchaLanguage'] ) ) {
				update_option( 'eael_recaptcha_language', sanitize_text_field( $settings['recaptchaLanguage'] ) );
			}

			//pro settings
			if ( isset( $settings['gClientId'] ) ) {
				update_option( 'eael_g_client_id', sanitize_text_field( $settings['gClientId'] ) );
			}
			if ( isset( $settings['fbAppId'] ) ) {
				update_option( 'eael_fb_app_id', sanitize_text_field( $settings['fbAppId'] ) );
			}
			if ( isset( $settings['fbAppSecret'] ) ) {
				update_option( 'eael_fb_app_secret', sanitize_text_field( $settings['fbAppSecret'] ) );
			}

			wp_send_json_success( [ 'message' => __( 'Login | Register Settings updated', 'essential-addons-for-elementor-lite' ) ] );
		}

		//Login-register data
		if ( isset( $settings['lr_recaptcha_sitekey'] ) ) {
			update_option( 'eael_recaptcha_sitekey', sanitize_text_field( $settings['lr_recaptcha_sitekey'] ) );
		}
		if ( isset( $settings['lr_recaptcha_secret'] ) ) {
			update_option( 'eael_recaptcha_secret', sanitize_text_field( $settings['lr_recaptcha_secret'] ) );
		}
		if ( isset( $settings['lr_recaptcha_language'] ) ) {
			update_option( 'eael_recaptcha_language', sanitize_text_field( $settings['lr_recaptcha_language'] ) );
		}

		//pro settings
		if ( isset( $settings['lr_g_client_id'] ) ) {
			update_option( 'eael_g_client_id', sanitize_text_field( $settings['lr_g_client_id'] ) );
		}
		if ( isset( $settings['lr_fb_app_id'] ) ) {
			update_option( 'eael_fb_app_id', sanitize_text_field( $settings['lr_fb_app_id'] ) );
		}
		if ( isset( $settings['lr_fb_app_secret'] ) ) {
			update_option( 'eael_fb_app_secret', sanitize_text_field( $settings['lr_fb_app_secret'] ) );
		}

		// Saving Google Map Api Key
		if ( isset( $settings['google-map-api'] ) ) {
			update_option( 'eael_save_google_map_api', sanitize_text_field( $settings['google-map-api'] ) );
		}

		// Saving Mailchimp Api Key
		if ( isset( $settings['mailchimp-api'] ) ) {
			update_option( 'eael_save_mailchimp_api', sanitize_text_field( $settings['mailchimp-api'] ) );
		}

		// Saving TYpeForm token
		if ( isset( $settings['typeform-personal-token'] ) ) {
			update_option( 'eael_save_typeform_personal_token', sanitize_text_field( $settings['typeform-personal-token'] ) );
		}

		// Saving Duplicator Settings
		if ( isset( $settings['post-duplicator-post-type'] ) ) {
			update_option( 'eael_save_post_duplicator_post_type', sanitize_text_field( $settings['post-duplicator-post-type'] ) );
		}

		// save js print method
		if ( isset( $settings['eael-js-print-method'] ) ) {
			update_option( 'eael_js_print_method', sanitize_text_field( $settings['eael-js-print-method'] ) );
		}

		$settings = array_map( 'sanitize_text_field', $settings );
		$defaults = array_fill_keys( array_keys( array_merge( $this->registered_elements, $this->registered_extensions ) ), false );
		$elements = array_merge( $defaults, array_fill_keys( array_keys( array_intersect_key( $settings, $defaults ) ), true ) );

		// update new settings
		$updated = update_option( 'eael_save_settings', $elements );

		// clear assets files
		$this->empty_dir( EAEL_ASSET_PATH );

		wp_send_json( $updated );
	}

	/**
	 * Clear Cache Files
	 * Clear cache files from uploads/essential-addons-elementor
	 *
	 * @access public
	 * @return void
	 * @since 3.0.0
	 */
	public function clear_cache_files() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		if ( isset( $_REQUEST['posts'] ) ) {
			if ( ! empty( $_POST['posts'] ) ) {
				foreach ( json_decode( $_POST['posts'] ) as $post ) {
					$this->remove_files( 'post-' . $post );
				}
			}
		} else {
			// clear cache files
			$this->empty_dir( EAEL_ASSET_PATH );
		}

		wp_send_json( true );
	}

	public function eael_admin_promotion(){
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		update_option( 'eael_admin_promotion', self::EAEL_PROMOTION_FLAG );
	}
}

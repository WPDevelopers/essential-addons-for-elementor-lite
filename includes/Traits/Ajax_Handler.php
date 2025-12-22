<?php
/**
 * Class trait Ajax_Handler file
 *
 * @package Essential-addons-for-elementor-lite\Traits
 */

namespace Essential_Addons_Elementor\Traits;

use Essential_Addons_Elementor\Classes\AllTraits;
use Essential_Addons_Elementor\Classes\Elements_Manager;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;

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
		add_action( 'wp_ajax_nopriv_woo_product_pagination_product', array( $this, 'eael_woo_pagination_product_ajax' ) );

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
		add_action( 'wp_ajax_eael_select2_get_title', [ $this, 'select2_ajax_get_posts_value_titles' ] );

		if ( is_admin() ) {
			add_action( 'wp_ajax_save_settings_with_ajax', array( $this, 'save_settings' ) );
			add_action( 'wp_ajax_clear_cache_files_with_ajax', array( $this, 'clear_cache_files' ) );
			add_action( 'wp_ajax_eael_admin_promotion', array( $this, 'eael_admin_promotion' ) );
		}

		add_action( 'wp_ajax_eael_get_token', [ $this, 'eael_get_token' ] );
		add_action( 'wp_ajax_nopriv_eael_get_token', [ $this, 'eael_get_token' ] );

		add_action( 'eael_before_woo_pagination_product_ajax_start', [ $this, 'eael_yith_wcwl_ajax_disable' ] );
		add_action( 'eael_before_ajax_load_more', [ $this, 'eael_yith_wcwl_ajax_disable' ] );
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

		do_action( 'eael_before_ajax_load_more', $_REQUEST );

		wp_parse_str( $_POST['args'], $args );
		$args['post_status'] = 'publish';

		if ( isset( $args['date_query']['relation'] ) ) {
			$args['date_query']['relation'] = HelperClass::eael_sanitize_relation( $args['date_query']['relation'] );
		}

		if ( empty( $_POST['nonce'] ) ) {
			$err_msg = __( 'Insecure form submitted without security token', 'essential-addons-for-elementor-lite' );
			if ( $ajax ) {
				wp_send_json_error( $err_msg );
			}

			return false;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'load_more' ) && ! wp_verify_nonce( $_POST['nonce'], 'essential-addons-elementor' ) ) {
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

			$relation = isset( $settings['relation_cats_tags'] ) ? $settings['relation_cats_tags'] : 'OR';
			$args['tax_query'] = $this->eael_terms_query_multiple( $args['tax_query'], $relation );
		}

		if ( $class == '\Essential_Addons_Elementor\Elements\Post_Grid' ) {
			$settings['read_more_button_text']       = get_transient( 'eael_post_grid_read_more_button_text_' . $widget_id );
			$settings['excerpt_expanison_indicator'] = get_transient( 'eael_post_grid_excerpt_expanison_indicator_' . $widget_id );

			if ( $settings['orderby'] === 'rand' && ! empty( $_REQUEST['post__not_in'] ) ) {
				$post__not_in = $_REQUEST['post__not_in'];
				if ( ! empty( $args['post__not_in'] ) ) {
					$post__not_in = array_merge( $post__not_in, $args['post__not_in'] );
				}
				$args['post__not_in'] = array_map( 'intval', array_unique( $post__not_in ) );
				unset( $args['offset'] );
			}
		}
		if ( $class === '\Essential_Addons_Elementor\Elements\Product_Grid' ) {
			do_action( 'eael_woo_before_product_loop', $settings['eael_product_grid_style_preset'] );
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

			// Check if this is a hybrid/combined query with ACF gallery
			// Also check settings for hybrid query flag as backup (in case args encoding failed)
			$is_hybrid_query = ( ! empty( $args['eael_dfg_enable_combined_query'] ) && 'yes' === $args['eael_dfg_enable_combined_query'] )
				|| ( ! empty( $settings['eael_dfg_enable_combined_query'] ) && 'yes' === $settings['eael_dfg_enable_combined_query'] && 'yes' === $settings['fetch_acf_image_gallery'] );

			if ( $is_hybrid_query && class_exists( 'ACF' ) && ! empty( $settings['eael_acf_gallery_keys'] ) ) {
				// Build taxonomy map for ACF gallery attachments
				$taxonomy_map = $this->build_dfg_acf_taxonomy_map( $args, $settings, $active_term_id, $active_taxonomy );

				// Store globally for templates
				global $eael_dfg_attachment_taxonomy_map;
				$eael_dfg_attachment_taxonomy_map = $taxonomy_map['taxonomy_map'];

				// Update args with the filtered post IDs
				if ( ! empty( $taxonomy_map['post_ids'] ) ) {
					$args['post__in'] = $taxonomy_map['post_ids'];
					$args['post_type'] = 'any';
					$args['post_status'] = 'any';
					$args['tax_query'] = [];
					$args['orderby'] = 'post__in';
				}

				// Apply exclusions
				if ( ! empty( $args['post__not_in'] ) && ! empty( $args['post__in'] ) ) {
					$args['post__in'] = array_values( array_diff( $args['post__in'], array_unique( $args['post__not_in'] ) ) );
				}
			} else {
				// Standard ACF gallery handling (non-hybrid)
				if ( ! empty( $args['fetch_acf_image'] ) && 'yes' === $args['fetch_acf_image'] && ! empty( $args['post__in'] ) ) {
					$args['post_status'] = 'any';
					$args['post_type'] = 'any';
				}

				if ( ! empty( $args['post__not_in'] ) && ! empty( $args['post__in'] ) ) {
					$args['post__in'] = array_diff( $args['post__in'], array_unique( $args['post__not_in'] ) );
				}

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
				// wp_send_json( $args );
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

		if ( $class === '\Essential_Addons_Elementor\Elements\Product_Grid' ) {
			do_action( 'eael_woo_after_product_loop', $settings['eael_product_grid_style_preset'] );
		}
		while ( ob_get_status() ) {
			ob_end_clean();
		}
		if ( function_exists( 'gzencode' ) ) {
			$response = gzencode( wp_json_encode( $html ) );

			header( 'Content-Type: application/json; charset=utf-8' );
			header( 'Content-Encoding: gzip' );
			header( 'Content-Length: ' . strlen( $response ) );

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $response;
		} else {
			echo wp_kses_post( $html );
		}
		wp_die();
	}

	/**
	 * Build taxonomy map for Dynamic Filterable Gallery ACF attachments
	 * Maps attachment IDs to their parent post's taxonomy classes for filtering
	 *
	 * @param array $args Query args
	 * @param array $settings Widget settings
	 * @param int $active_term_id Active filter term ID
	 * @param string $active_taxonomy Active filter taxonomy
	 * @return array Array with 'post_ids' and 'taxonomy_map'
	 */
	protected function build_dfg_acf_taxonomy_map( $args, $settings, $active_term_id = 0, $active_taxonomy = '' ) {
		$_args = $args;
		$_args['posts_per_page'] = -1;
		$_args['fields'] = 'ids';

		// Restore original post_type from settings (args may have 'any' from hybrid query encoding)
		if ( ! empty( $settings['post_type'] ) && $settings['post_type'] !== 'by_id' ) {
			$_args['post_type'] = $settings['post_type'];
			$_args['post_status'] = 'publish';
		}

		// Remove post__in constraint to get all matching parent posts
		unset( $_args['post__in'] );

		// If filtering by a specific term, apply the filter to parent posts
		if ( $active_term_id > 0 && ! empty( $active_taxonomy ) ) {
			$_args['tax_query'] = [
				[
					'taxonomy' => $active_taxonomy,
					'field' => 'term_id',
					'terms' => [ $active_term_id ],
				]
			];
		}

		$query = new \WP_Query( $_args );

		$post_ids = [];
		$taxonomy_map = [];

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$parent_post_id = get_the_ID();

				// Get parent post's taxonomy classes
				$parent_taxonomy_classes = $this->get_dfg_post_taxonomy_classes( $parent_post_id, $settings );

				// Include parent posts unless hidden
				if ( ! isset( $settings['eael_gf_hide_parent_items'] ) || 'yes' !== $settings['eael_gf_hide_parent_items'] ) {
					$post_ids[] = $parent_post_id;
				}

				// Get ACF gallery items
				$acf_gallery = [];
				if ( ! empty( $settings['eael_acf_gallery_keys'] ) ) {
					foreach ( $settings['eael_acf_gallery_keys'] as $key ) {
						$_acf_gallery = get_field( $key, $parent_post_id );
						if ( ! empty( $_acf_gallery ) ) {
							$acf_gallery = array_merge( $_acf_gallery, $acf_gallery );
						}
					}
				}

				if ( ! empty( $acf_gallery ) ) {
					foreach ( $acf_gallery as $item ) {
						$attachment_id = false;

						if ( empty( $item['ID'] ) ) {
							if ( 'integer' === gettype( $item ) ) {
								$attachment_id = $item;
							} else if ( 'string' === gettype( $item ) ) {
								$attachment_id = HelperClass::eael_get_attachment_id_from_url( $item );
							}

							if ( ! $attachment_id ) {
								continue;
							}

							$attachment = get_post( $attachment_id );
							if ( ! is_object( $attachment ) || ! isset( $attachment->ID ) ) {
								continue;
							}
						} else {
							$attachment_id = $item['ID'];
						}

						$post_ids[] = $attachment_id;

						// Map this attachment to its parent's taxonomy classes
						if ( ! isset( $taxonomy_map[ $attachment_id ] ) ) {
							$taxonomy_map[ $attachment_id ] = $parent_taxonomy_classes;
						} else {
							$taxonomy_map[ $attachment_id ] = array_unique(
								array_merge( $taxonomy_map[ $attachment_id ], $parent_taxonomy_classes )
							);
						}
					}
				}
			}
		}
		wp_reset_postdata();

		return [
			'post_ids' => array_unique( $post_ids ),
			'taxonomy_map' => $taxonomy_map
		];
	}

	/**
	 * Get taxonomy classes for a post (for DFG ACF gallery parent posts)
	 *
	 * @param int $post_id Post ID
	 * @param array $settings Widget settings
	 * @return array Array of taxonomy slug classes
	 */
	protected function get_dfg_post_taxonomy_classes( $post_id, $settings ) {
		$classes = [];
		$post_type = get_post_type( $post_id );

		// Get all taxonomies for this post type
		$get_object_taxonomies = get_object_taxonomies( $post_type );
		$taxonomies = wp_get_object_terms( $post_id, $get_object_taxonomies, array( "fields" => "slugs" ) );

		if ( $taxonomies && ! is_wp_error( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				$classes[] = $taxonomy;
			}
		}

		// Handle category child items
		$show_category_child_items = ! empty( $settings['category_show_child_items'] ) && 'yes' === $settings['category_show_child_items'] ? 1 : 0;
		$show_product_cat_child_items = ! empty( $settings['product_cat_show_child_items'] ) && 'yes' === $settings['product_cat_show_child_items'] ? 1 : 0;

		$category_or_product_cat = '';
		if ( 1 === $show_category_child_items && ! empty( $get_object_taxonomies ) && in_array( 'category', $get_object_taxonomies ) ) {
			$category_or_product_cat = 'category';
		}

		if ( 1 === $show_product_cat_child_items && ! empty( $get_object_taxonomies ) && in_array( 'product_cat', $get_object_taxonomies ) ) {
			$category_or_product_cat = 'product_cat';
		}

		if ( $category_or_product_cat ) {
			$terms = get_the_terms( $post_id, $category_or_product_cat );
			if ( $terms && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$parent_list = get_term_parents_list( $term->term_id, $category_or_product_cat, array( "format" => "slug", 'separator' => '/', "link" => 0, "inclusive" => 0 ) );
					$parent_list = explode( '/', $parent_list );
					$classes = array_merge( $classes, array_filter( $parent_list ) );
				}
			}
		}

		// Get categories
		$categories = get_the_category( $post_id );
		if ( $categories ) {
			foreach ( $categories as $category ) {
				$classes[] = $category->slug;
			}
		}

		// Get tags
		$tags = wp_get_post_tags( $post_id );
		if ( $tags ) {
			foreach ( $tags as $tag ) {
				$classes[] = $tag->slug;
			}
		}

		// Get product categories
		$product_cats = get_the_terms( $post_id, 'product_cat' );
		if ( $product_cats && ! is_wp_error( $product_cats ) ) {
			foreach ( $product_cats as $cat ) {
				if ( is_object( $cat ) ) {
					$classes[] = $cat->slug;
				}
			}
		}

		// Add post name/slug
		$classes[] = get_post_field( 'post_name', $post_id );

		return array_unique( array_filter( $classes ) );
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

		do_action( 'eael_before_woo_pagination_product_ajax_start', $_REQUEST );

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
		$settings['eael_page_id']   = $page_id;
		$settings['eael_widget_id'] = $widget_id;
		wp_parse_str( $_REQUEST['args'], $args );
		$args['post_status'] = array_intersect( (array) $settings['eael_product_grid_products_status'], [ 'publish', 'draft', 'pending', 'future' ] );

		if ( isset( $args['date_query']['relation'] ) ) {
			$args['date_query']['relation'] = HelperClass::eael_sanitize_relation( $args['date_query']['relation'] );
		}

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
			if ( isset( $template_info['name'] ) && $template_info['name'] === 'eicon-woocommerce' && boolval( $settings['show_add_to_cart_custom_text'] ) ){
				$add_to_cart_text = [
					'add_to_cart_simple_product_button_text'   => $settings['add_to_cart_simple_product_button_text'],
					'add_to_cart_variable_product_button_text' => $settings['add_to_cart_variable_product_button_text'],
					'add_to_cart_grouped_product_button_text'  => $settings['add_to_cart_grouped_product_button_text'],
					'add_to_cart_external_product_button_text' => $settings['add_to_cart_external_product_button_text'],
					'add_to_cart_default_product_button_text'  => $settings['add_to_cart_default_product_button_text'],
				];
				$this->change_add_woo_checkout_update_order_reviewto_cart_text( $add_to_cart_text );
			}

			while ( $query->have_posts() ) {
				$query->the_post();
				include( $template );
			}
			wp_reset_postdata();
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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

		if ( isset( $args['date_query']['relation'] ) ) {
			$args['date_query']['relation'] = HelperClass::eael_sanitize_relation( $args['date_query']['relation'] );
		}

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
				$setPagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers'   data-pnumber='" . esc_attr( $paginationprev ) . "' >" . esc_html( $prev_label ) . "</a></li>";
			}

			if ( $pagination_Paginationlist < 7 + ( $adjacents * 2 ) ) {

				for ( $pagination = 1; $pagination <= $pagination_Paginationlist; $pagination ++ ) {
					$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
				}

			} else if ( $pagination_Paginationlist > 5 + ( $adjacents * 2 ) ) {

				if ( $paginationNumber < 1 + ( $adjacents * 2 ) ) {
					for ( $pagination = 1; $pagination <= 4 + ( $adjacents * 2 ); $pagination ++ ) {

						$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
						$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
					}
					$setPagination .= "<li class='pagitext dots'>...</li>";
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );

				} elseif ( $pagination_Paginationlist - ( $adjacents * 2 ) > $paginationNumber && $paginationNumber > ( $adjacents * 2 ) ) {
					$active        = '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), 1 );
					$setPagination .= "<li class='pagitext dots'>...</li>";
					for ( $pagination = $paginationNumber - $adjacents; $pagination <= $paginationNumber + $adjacents; $pagination ++ ) {
						$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
						$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
					}

					$setPagination .= "<li class='pagitext dots'>...</li>";
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $last ) );

				} else {
					$active        = '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), 1 );
					$setPagination .= "<li class='pagitext dots'>...</li>";
					for ( $pagination = $last - ( 2 + ( $adjacents * 2 ) ); $pagination <= $last; $pagination ++ ) {
						$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
						$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
					}
				}

			} else {
				for ( $pagination = 1; $pagination <= $pagination_Paginationlist; $pagination ++ ) {
					$active        = ( $paginationNumber == $pagination ) ? 'current' : '';
					$setPagination .= sprintf( "<li><a href='javascript:void(0);' id='post' class='page-numbers %s' data-pnumber='%2\$d'>%2\$d</a></li>", esc_attr( $active ), esc_html( $pagination ) );
				}

			}

			if ( $paginationNumber < $pagination_Paginationlist ) {
				$setPagination .= "<li class='pagitext'><a href='javascript:void(0);' class='page-numbers' data-pnumber='" . esc_attr( $paginationnext ) . "' > " . esc_html( $next_label ) . " </a></li>";
			}

			$setPagination .= "</ul>";
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $setPagination;
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
		$setting       = $_POST['orderReviewData'];
        $shipping_data = empty ( $_POST['shippingData'] ) ? WC()->session->get('chosen_shipping_methods') : [wc_clean( $_POST['shippingData'] )];
		//Mondial Relay plugin integration
		do_action( 'eael_mondialrelay_order_after_shipping' );
        
        WC()->session->set( 'chosen_shipping_methods', $shipping_data );

		ob_start();
		AllTraits::checkout_order_review_default( $setting );
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
		$args['post_status'] = 'publish';
		$args['offset']      = $args['offset'] ?? 0;

		if ( isset( $args['date_query']['relation'] ) ) {
			$args['date_query']['relation'] = HelperClass::eael_sanitize_relation( $args['date_query']['relation'] );
		}

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

			$relation = isset( $settings['relation_cats_tags'] ) ? $settings['relation_cats_tags'] : 'OR';
			if ( 'and' === strtolower( $relation ) ) {
				if ( 'product_cat' === $_REQUEST['taxonomy']['taxonomy'] && ! empty( $settings['eael_product_gallery_tags'] ) ) {
					$args['tax_query'][] = [
						'taxonomy' => 'product_tag',
						'field'    => 'term_id',
						'terms'    => $settings['eael_product_gallery_tags'],
						'operator' => 'IN',
					];
				}
				if ( 'product_tag' === $_REQUEST['taxonomy']['taxonomy'] && ! empty( $settings['eael_product_gallery_categories'] ) ) {
					$args['tax_query'][] = [
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $settings['eael_product_gallery_categories'],
						'operator' => 'IN',
					];
				}
			}

			$args['tax_query'] = $this->eael_terms_query_multiple( $args['tax_query'], $relation );

			if ( $settings[ 'eael_product_gallery_product_filter' ] == 'featured-products' ) {
				$args[ 'tax_query' ][] = [
					'relation' => 'AND',
					[
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
					],
					[
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => [ 'exclude-from-search', 'exclude-from-catalog' ],
						'operator' => 'NOT IN',
					],
				];
			}


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

					do_action( 'eael_woo_before_product_loop' );

					while ( $query->have_posts() ) {
						$query->the_post();
						$html .= HelperClass::include_with_variable( $file_path, [ 'settings' => $settings ] );
					}
					
					do_action( 'eael_woo_after_product_loop' );

					$html .= '<div class="eael-max-page" style="display:none;">'. ceil($query->found_posts / absint( $args['posts_per_page'] ) ) . '</div>';

					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $html;
					wp_reset_postdata();
				}
			}
		}
		wp_die();
	}

	public function eael_terms_query_multiple( $args_tax_query = [], $relation = 'OR' ){
		if ( strpos($args_tax_query[0]['taxonomy'], '|') !== false ) {
			$args_tax_query_item = $args_tax_query[0];

			//Query for category and tag
			$args_multiple['tax_query'] = [];

			if( isset( $args_tax_query_item['terms'] ) ){
				$args_multiple['tax_query'][] = [
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $args_tax_query_item['terms'],
				];
			}

			if( isset( $args_tax_query_item['terms_tag'] ) ){
				$args_multiple['tax_query'][] = [
					'taxonomy' => 'product_tag',
					'field' => 'term_id',
					'terms' => $args_tax_query_item['terms_tag'],
				];
			}


			if ( count( $args_multiple['tax_query'] ) ) {
				$args_multiple['tax_query']['relation'] = $relation;
			}

			$args_tax_query = $args_multiple['tax_query'];
		}

		if( isset( $args_tax_query[0]['terms_tag'] ) ){
			if( 'product_tag' === $args_tax_query[0]['taxonomy'] ){
				$args_tax_query[0]['terms'] = $args_tax_query[0]['terms_tag'];
			}
			unset($args_tax_query[0]['terms_tag']);
		}

		return $args_tax_query;
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

		if ( ! empty( $_POST['post_type'] ) ) {
			$post_type = sanitize_text_field( $_POST['post_type'] );
		}

		if ( ! empty( $_POST['source_name'] ) ) {
			$source_name = sanitize_text_field( $_POST['source_name'] );
		}

		$search  = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
		$results = $post_list = [];
		switch ( $source_name ) {
			case 'taxonomy':
				$args = [
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'search'     => $search
				];

				if ( $post_type !== 'all' ) {
					$args['taxonomy'] = $post_type;
				}

				$post_list = wp_list_pluck( get_terms( $args ), 'name', 'term_id' );
				break;
			case 'user':
				if ( ! current_user_can( 'list_users' ) ) {
					$post_list = [];
					break;
				}

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

		$settings = array_map( 'sanitize_text_field', $_POST );
		unset( $settings['action'], $settings['security'] );
		$settings['embedpress']     = true;
		$settings['career-page']    = true;
		$settings['better-payment'] = true;

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

			//reCAPTCHA V3
			if ( isset( $settings['recaptchaSiteKeyV3'] ) ) {
				update_option( 'eael_recaptcha_sitekey_v3', sanitize_text_field( $settings['recaptchaSiteKeyV3'] ) );
			}
			if ( isset( $settings['recaptchaSiteSecretV3'] ) ) {
				update_option( 'eael_recaptcha_secret_v3', sanitize_text_field( $settings['recaptchaSiteSecretV3'] ) );
			}
			if ( isset( $settings['recaptchaLanguageV3'] ) ) {
				update_option( 'eael_recaptcha_language_v3', sanitize_text_field( $settings['recaptchaLanguageV3'] ) );
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

		//Cloudflare Turnstile
		if ( isset( $settings['lr_cloudflare_turnstile_sitekey'] ) ) {
			update_option( 'eael_cloudflare_turnstile_sitekey', sanitize_text_field( $settings['lr_cloudflare_turnstile_sitekey'] ) );
		}
		if ( isset( $settings['lr_cloudflare_turnstile_secretkey'] ) ) {
			update_option( 'eael_cloudflare_turnstile_secretkey', sanitize_text_field( $settings['lr_cloudflare_turnstile_secretkey'] ) );
		}

		//reCAPTCHA v3
		if ( isset( $settings['lr_recaptcha_sitekey_v3'] ) ) {
			update_option( 'eael_recaptcha_sitekey_v3', sanitize_text_field( $settings['lr_recaptcha_sitekey_v3'] ) );
		}
		if ( isset( $settings['lr_recaptcha_secret_v3'] ) ) {
			update_option( 'eael_recaptcha_secret_v3', sanitize_text_field( $settings['lr_recaptcha_secret_v3'] ) );
		}
		if ( isset( $settings['lr_recaptcha_language_v3'] ) ) {
			update_option( 'eael_recaptcha_language_v3', sanitize_text_field( $settings['lr_recaptcha_language_v3'] ) );
		}

		if ( isset( $settings['lr_recaptcha_badge_hide'] ) ) {
			update_option( 'eael_recaptcha_badge_hide', sanitize_text_field( $settings['lr_recaptcha_badge_hide'] ) );
		}

		if ( isset( $settings['lr_custom_profile_fields'] ) ) {
			update_option( 'eael_custom_profile_fields', sanitize_text_field( $settings['lr_custom_profile_fields'] ) );
		}

		if ( isset( $settings['lr_custom_profile_fields_text'] ) ) {
			update_option( 'eael_custom_profile_fields_text', sanitize_text_field( $settings['lr_custom_profile_fields_text'] ) );
		}

		if ( isset( $settings['lr_custom_profile_fields_img'] ) ) {
			update_option( 'eael_custom_profile_fields_img', sanitize_text_field( $settings['lr_custom_profile_fields_img'] ) );
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

		// Business Reviews : Saving Google Place Api Key
		if ( isset( $settings['br_google_place_api_key'] ) ) {
			update_option( 'eael_br_google_place_api_key', sanitize_text_field( $settings['br_google_place_api_key'] ) );
		}







		// Saving Google Map Api Key
		if ( isset( $settings['google-map-api'] ) ) {
			update_option( 'eael_save_google_map_api', sanitize_text_field( $settings['google-map-api'] ) );
		}

		// Saving Mailchimp Api Key
		if ( isset( $settings['mailchimp-api'] ) ) {
			update_option( 'eael_save_mailchimp_api', sanitize_text_field( $settings['mailchimp-api'] ) );
		}

		// Saving Mailchimp Api Key for EA Login | Register Form
		if ( isset( $settings['lr_mailchimp_api_key'] ) ) {
			update_option( 'eael_lr_mailchimp_api_key', sanitize_text_field( $settings['lr_mailchimp_api_key'] ) );
		}

		// Saving TYpeForm token
		if ( isset( $settings['typeform-personal-token'] ) ) {
			update_option( 'eael_save_typeform_personal_token', sanitize_text_field( $settings['typeform-personal-token'] ) );
		}

		// Saving Duplicator Settings
		if ( isset( $settings['post-duplicator-post-type'] ) ) {
			update_option( 'eael_save_post_duplicator_post_type', sanitize_text_field( $settings['post-duplicator-post-type'] ) );
		}

		// Saving Woo Acount Dashboard Settings
		if ( isset( $settings['woo-account-dashboard-custom-tabs'] ) ) {
			update_option( 'eael_woo_ac_dashboard_custom_tabs', sanitize_text_field( $settings['woo-account-dashboard-custom-tabs'] ) );
		}

		// save js print method
		if ( isset( $settings['eael-js-print-method'] ) ) {
			update_option( 'eael_js_print_method', sanitize_text_field( $settings['eael-js-print-method'] ) );
		}

		if ( ! empty( $settings['elements'] ) ) {
			$defaults    = array_fill_keys( array_keys( array_merge( $this->registered_elements, $this->registered_extensions ) ), false );
			$elements    = array_merge( $defaults, array_fill_keys( array_keys( array_intersect_key( $settings, $defaults ) ), true ) );
			$el_disable  = get_option( 'elementor_disabled_elements', [] );
			$new_disable = [];

			foreach ( $el_disable as $element ) {
				$el_new_name = Elements_Manager::replace_widget_name();
				$el_new_name = $el_new_name[ $element ] ?? $element;
				$el_new_name = substr( $el_new_name, 5 );
				if ( in_array( $el_new_name, $elements ) && $elements[ $el_new_name ] === true ) {
					continue;
				}

				$new_disable[] = $element;
			}

			// update new settings
			$updated = update_option( 'eael_save_settings', $elements );
			update_option( 'elementor_disabled_elements', $new_disable );

			// clear assets files
			$this->empty_dir( EAEL_ASSET_PATH );
		}

		wp_send_json_success( true );
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
			if ( $this->is_activate_elementor() ) {
				\Elementor\Plugin::$instance->files_manager->clear_cache();
			}
		}

		// Purge All LS Cache
		do_action( 'litespeed_purge_all', '3rd Essential Addons for Elementor' );

		// After clear the cache hook
		do_action( 'eael_after_clear_cache_files' );

		wp_send_json( true );
	}

	public function eael_admin_promotion(){
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		update_option( 'eael_admin_promotion', self::EAEL_PROMOTION_FLAG );
	}

	/**
	 * Get nonce token through ajax request
	 *
	 * @since 5.1.13
	 * @return void
	 */
	public function eael_get_token() {
		$nonce = wp_create_nonce( 'essential-addons-elementor' );
		if ( $nonce ) {
			wp_send_json_success( [ 'nonce' => $nonce ] );
		}
		wp_send_json_error( __( 'you are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
	}

	public function eael_yith_wcwl_ajax_disable( $request ) {
		add_filter( 'option_yith_wcwl_ajax_enable', function ( $data ) {
			return 'no';
		} );
	}

}

<?php

namespace Essential_Addons_Elementor\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly

use \Elementor\Plugin;
use Essential_Addons_Elementor\Classes\Helper;

trait Elements {
	public $extensions_data = [];

	/**
	 * Register custom controls
	 *
	 * @since v4.4.2
	 */
	public function register_controls( $controls_manager ) {
		if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
			$controls_manager->register( new \Essential_Addons_Elementor\Controls\Select2() );
			$controls_manager->add_group_control( 'eael-background', new \Essential_Addons_Elementor\Controls\EAEL_Background() );
			$controls_manager->register( new \Essential_Addons_Elementor\Controls\EAEL_Choose() );
		} else {
			$controls_manager->register_control( 'eael-select2', new \Essential_Addons_Elementor\Controls\Select2() );
		}
	}

	/**
	 * Add elementor category
	 *
	 * @since v1.0.0
	 */
	public function register_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'essential-addons-elementor',
			[
				'title' => __( 'Essential Addons', 'essential-addons-for-elementor-lite' ),
				'icon'  => 'font',
			], 1 );
	}

	/**
	 * Register widgets
	 *
	 * @since v3.0.0
	 */
	public function register_elements( $widgets_manager ) {
		$active_elements = (array) $this->get_settings();

		if ( empty( $active_elements ) ) {
			return;
		}

		asort( $active_elements );

		foreach ( $active_elements as $active_element ) {
			if ( ! isset( $this->registered_elements[ $active_element ] ) ) {
				continue;
			}

			if ( isset( $this->registered_elements[ $active_element ]['condition'] ) ) {
				$check = false;

				if ( isset( $this->registered_elements[ $active_element ]['condition'][2] ) ) {
					$check = $this->registered_elements[ $active_element ]['condition'][2];
				}

				if ( $this->registered_elements[ $active_element ]['condition'][0]( $this->registered_elements[ $active_element ]['condition'][1] ) == $check ) {
					continue;
				}
			}

			if ( $this->pro_enabled && \version_compare( EAEL_PRO_PLUGIN_VERSION, '3.3.0', '<' ) ) {
				if ( in_array( $active_element, [
					'content-timeline',
					'dynamic-filter-gallery',
					'post-block',
					'post-carousel',
					'post-list'
				] ) ) {
					continue;
				}
			}

	        if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
		        $widgets_manager->register( new $this->registered_elements[ $active_element ]['class'] );
	        } else {
		        $widgets_manager->register_widget_type( new $this->registered_elements[ $active_element ]['class'] );
	        }

        }
    }

	/**
	 * Register extensions
	 *
	 * @since v3.0.0
	 */
	public function register_extensions() {
		$active_elements = (array) $this->get_settings();

		// set promotion extension enabled
		array_push( $active_elements, 'promotion' );

		foreach ( $this->registered_extensions as $key => $extension ) {
			if ( ! in_array( $key, $active_elements ) ) {
				continue;
			}

			if ( class_exists( $extension['class'] ) ) {
				new $extension['class']; // Safely instantiate
			}
		}
	}

	/**
	 * List pro widgets
	 *
	 * @since v3.1.4
	 */
	public function promote_pro_elements( $config ) {

		if ( $this->pro_enabled ) {
			return $config;
		}

		$promotion_widgets = [];

		if ( isset( $config['promotionWidgets'] ) ) {
			$promotion_widgets = $config['promotionWidgets'];
		}

		$combine_array = array_merge( $promotion_widgets, [
			[
				'name'       => 'eael-advanced-menu',
				'title'      => __( 'Advanced Menu', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-advanced-menu',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-content-timeline',
				'title'      => __( 'Content Timeline', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-content-timeline',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-counter',
				'title'      => __( 'Counter', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-counter',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-divider',
				'title'      => __( 'Divider', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-divider',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-dynamic-filterable-gallery',
				'title'      => __( 'Dynamic Gallery', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-dynamic-gallery',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-flip-carousel',
				'title'      => __( 'Flip Carousel', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-flip-carousel',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-figma-to-elementor',
				'title'      => __( 'Figma to Elementor Converter', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-flip-carousel',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-google-map',
				'title'      => __( 'Google Map', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-advanced-google-maps',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-image-comparison',
				'title'      => __( 'Image Comparison', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-image-comparison',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-image-hotspots',
				'title'      => __( 'Image Hotspots', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-image-hotspots',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-image-scroller',
				'title'      => __( 'Image Scroller', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-image-scroller',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-instafeed',
				'title'      => __( 'Instagram Feed', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-instagram-feed',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-interactive-card',
				'title'      => __( 'Interactive Card', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-interactive-cards',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-interactive-promo',
				'title'      => __( 'Interactive Promo', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-interactive-promo',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-learn-dash-course-list',
				'title'      => __( 'LearnDash Course List', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-learndash',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-lightbox',
				'title'      => __( 'Lightbox & Modal', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-lightbox-modal',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-logo-carousel',
				'title'      => __( 'Logo Carousel', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-logo-carousel',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-mailchimp',
				'title'      => __( 'Mailchimp', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-mailchimp',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-offcanvas',
				'title'      => __( 'Offcanvas', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-offcanvas',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-one-page-nav',
				'title'      => __( 'One Page Navigation', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-one-page-navigaton',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-post-block',
				'title'      => __( 'Post Block', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-post-block',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-post-carousel',
				'title'      => __( 'Post Carousel', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-post-carousel',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-post-list',
				'title'      => __( 'Smart Post List', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-smart-post-list',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-price-menu',
				'title'      => __( 'Price Menu', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-price-menu',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-multicolumn-pricing-table',
				'title'      => __( 'Multicolumn Pricing Table', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-multicolumn-pricing',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-protected-content',
				'title'      => __( 'Protected Content', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-protected-content',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-static-product',
				'title'      => __( 'Static Product', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-static-product',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-team-member-carousel',
				'title'      => __( 'Team Member Carousel', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-team-member-carousel',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-testimonial-slider',
				'title'      => __( 'Testimonial Slider', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-testimonial-slider',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-toggle',
				'title'      => __( 'Toggle', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-content-toggle',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-twitter-feed-carousel',
				'title'      => __( 'X (Twitter) Feed Carousel', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-twitter-feed-carousel',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-woo-collections',
				'title'      => __( 'Woo Product Collections', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-woo-product-collections',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-woo-product-slider',
				'title'      => __( 'Woo Product Slider', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-woo-product-collections',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eaicon-advanced-search',
				'title'      => __( 'Advanced Search', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-advanced-search',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-woo-thank-you',
				'title'      => __( 'Woo Thank You', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-thank-you',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-woo-cross-sells',
				'title'      => __( 'Woo Cross Sells', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-woo-cross-sells',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'eael-woo-account-dashboard',
				'title'      => __( 'Woo Account Dashboard', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-woo-account-dashboard',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'fancy-chart',
				'title'      => __( 'Fancy Chart', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-fancy-chart',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'stacked-cards',
				'title'      => __( 'Stacked Cards', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-stacked-cards',
				'categories' => '["essential-addons-elementor"]',
			],
			[
				'name'       => 'sphere-photo-viewer',
				'title'      => __( '360 Degree Photo Viewer', 'essential-addons-for-elementor-lite' ),
				'icon'       => 'eaicon-photo-sphere',
				'categories' => '["essential-addons-elementor"]',
			],
		] );

		$config['promotionWidgets'] = $combine_array;

		return $config;
	}

	public function eael_is_theme_builder_archive_template( $type = 'archive' ){
		$is_archive_template = false;

		if ( class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
			$conditions_manager = \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'theme-builder' )->get_conditions_manager();
		
			if( ! empty( $conditions_manager->get_documents_for_location( 'archive') ) || ! empty( $conditions_manager->get_documents_for_location( 'single') ) ) {
				$is_archive_template = true;
			}
		}

		return $is_archive_template;
	}

	public function eael_get_theme_builder_archive_template_id(){
		$template_id = 0;

		if ( class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) {
			if ( $this->eael_is_theme_builder_archive_template() ) {
				$page_body_classes = get_body_class();

				if( is_array( $page_body_classes ) && count( $page_body_classes ) ){
					foreach( $page_body_classes as $page_body_class){
						if ( is_string($page_body_class) && strpos( $page_body_class, 'elementor-page-' ) !== FALSE ) {
							$template_id = intval( str_replace('elementor-page-', '', $page_body_class) );
						} 
					}
				}
			}
		}

		return $template_id;
	}

	/**
	 * Inject global extension html.
	 *
	 * @since v3.1.4
	 */
	public function render_global_html() {
		if ( ! apply_filters( 'eael/is_plugin_active', 'elementor/elementor.php' ) ) {
			return;
		}

		if ( ! ( is_singular() || is_archive() || is_home() || is_front_page() || is_search() ) ) {
			return;
		}

		$post_id         = get_the_ID();
		$html            = '';
		$global_settings = $settings_data = $document = [];

		if ( is_front_page() ) {
			$post_id = get_option('page_on_front');
		} else if ( is_home() ) {
			$post_id = get_option('page_for_posts');
		}
		
		if ( $this->get_settings( 'reading-progress' ) || $this->get_settings( 'table-of-content' ) || $this->get_settings( 'scroll-to-top' ) || $this->get_settings( 'custom-cursor' ) ) {
			$html            = '';
			$global_settings = get_option( 'eael_global_settings' );

			$is_archive_template = $this->eael_is_theme_builder_archive_template();
			if( ! empty ( $is_archive_template ) ){
				$template_id = $this->eael_get_theme_builder_archive_template_id();

				if ( ! empty( $template_id ) ) {
					$post_id = $template_id;
				}
			}

			$document = Plugin::$instance->documents->get( $post_id, false );

			if ( is_object( $document ) ) {
				$settings_data = $document->get_settings();
			}
		}

		// Reading Progress Bar
		if ( $this->get_settings( 'reading-progress' ) == true ) {
			$reading_progress_status = $global_reading_progress = false;

			if ( isset( $settings_data['eael_ext_reading_progress'] ) && $settings_data['eael_ext_reading_progress'] == 'yes' ) {
				$reading_progress_status = true;
			} elseif ( isset( $global_settings['reading_progress']['enabled'] ) && $global_settings['reading_progress']['enabled'] ) {
				$reading_progress_status = true;
				$global_reading_progress = true;
				$settings_data           = $global_settings['reading_progress'];
			}

			if ( $reading_progress_status ) {
				if ( ! empty( $document ) && is_object( $document ) ) {
					$this->progress_bar_local_css( $document->get_settings() );
				}

				$this->extensions_data = $settings_data;
				$progress_height       = ! empty( $settings_data['eael_ext_reading_progress_height']['size'] ) ? $settings_data['eael_ext_reading_progress_height']['size'] : '';
				$animation_speed       = ! empty( $settings_data['eael_ext_reading_progress_animation_speed']['size'] ) ? $settings_data['eael_ext_reading_progress_animation_speed']['size'] : '';

				$reading_progress_html = '<div id="eael-reading-progress-'. get_the_ID() .'" class="eael-reading-progress-wrap eael-reading-progress-wrap-' . ( $this->get_extensions_value( 'eael_ext_reading_progress' ) == 'yes' ? 'local' : 'global' ) . '">';

				if ( $global_reading_progress ) {
					$reading_progress_html .= '<div class="eael-reading-progress eael-reading-progress-global eael-reading-progress-' . $this->get_extensions_value( 'eael_ext_reading_progress_position' ) . '" style="height: ' . esc_attr( $progress_height ) . 'px;background-color: ' . $this->get_extensions_value( 'eael_ext_reading_progress_bg_color' ) . ';">
                        <div class="eael-reading-progress-fill" style="height: ' . esc_attr( $progress_height ) . 'px;background-color: ' . $this->get_extensions_value( 'eael_ext_reading_progress_fill_color' ) . ';transition: width ' . esc_attr( $animation_speed ) . 'ms ease;"></div>
                    </div>';
				} else {
					$reading_progress_html .= '<div class="eael-reading-progress eael-reading-progress-local eael-reading-progress-' . $this->get_extensions_value( 'eael_ext_reading_progress_position' ) . '">
                        <div class="eael-reading-progress-fill"></div>
                    </div>';
				}

				$reading_progress_html .= '</div>';

				if ( $this->get_extensions_value( 'eael_ext_reading_progress' ) != 'yes' ) {
					$display_condition = $this->get_extensions_value( 'eael_ext_reading_progress_global_display_condition' );
					if ( get_post_status( $this->get_extensions_value( 'post_id' ) ) != 'publish' ) {
						$reading_progress_html = '';
					} else if ( $display_condition == 'pages' && ! is_page() ) {
						$reading_progress_html = '';
					} else if ( $display_condition == 'posts' && ! is_single() ) {
						$reading_progress_html = '';
					}
				}

				if ( ! empty( $reading_progress_html ) ) {
					wp_enqueue_script( 'eael-reading-progress' );
					wp_enqueue_style( 'eael-reading-progress' );

					$html .= $reading_progress_html;
				}
			}
		}

		// Table of Contents
		if ( $this->get_settings( 'table-of-content' ) ) {
			$toc_status 		= false;
			$toc_status_global 	= false;

			if ( is_object( $document ) ) {
				$settings_data = $document->get_settings();
			}

			if ( isset( $settings_data['eael_ext_table_of_content'] ) && $settings_data['eael_ext_table_of_content'] == 'yes' ) {
				$toc_status = true;
			} elseif ( isset( $global_settings['eael_ext_table_of_content']['enabled'] ) && $global_settings['eael_ext_table_of_content']['enabled'] ) {
				$toc_status    	= true;
				$settings_data 	= $global_settings['eael_ext_table_of_content'];
			}

			$toc_status_global = isset( $global_settings['eael_ext_table_of_content']['enabled'] ) && $global_settings['eael_ext_table_of_content']['enabled'];

			if ( $toc_status ) {
				$this->extensions_data = $settings_data;
				$el_class              = 'eael-toc eael-toc-disable';

				if ( $this->get_extensions_value( 'eael_ext_table_of_content' ) != 'yes' && ! empty( $settings_data['enabled'] ) ) {
					$el_class .= ' eael-toc-global';
					$this->toc_global_css( $global_settings );
				}

				$icon                            = 'fas fa-list';
				$support_tag                     = (array) $settings_data['eael_ext_toc_supported_heading_tag'];
				$support_tag                     = implode( ',', array_filter( $support_tag ) );
				$position                        = $settings_data['eael_ext_toc_position'];
				$is_mobile_on                    = isset( $settings_data['eael_ext_toc_position_mobile'] ) ? $settings_data['eael_ext_toc_position_mobile'] : 'no';
				$mobile_position                 = isset( $settings_data['eael_ext_toc_position_mobile_top_bottom'] ) ? $settings_data['eael_ext_toc_position_mobile_top_bottom'] : $position;
				$page_offset                     = ! empty( $settings_data['eael_ext_toc_main_page_offset'] ) ? $settings_data['eael_ext_toc_main_page_offset']['size'] : 0;
				$close_bt_text_style             = $settings_data['eael_ext_toc_close_button_text_style'];
				$auto_collapse                   = $settings_data['eael_ext_toc_auto_collapse'];
				$auto_highlight                  = ! empty( $settings_data['eael_ext_toc_auto_highlight'] ) ? $settings_data['eael_ext_toc_auto_highlight'] : '';
				$auto_highlight_single_item_only = ! empty( $settings_data['eael_ext_toc_auto_highlight_single_item_only'] ) ? $settings_data['eael_ext_toc_auto_highlight_single_item_only'] : '';
				$title_to_url                    = $settings_data['eael_ext_toc_use_title_in_url'];
				$toc_style                       = $settings_data['eael_ext_table_of_content_list_style'];
				$toc_word_wrap                   = $settings_data['eael_ext_toc_word_wrap'];
				$toc_collapse                    = $settings_data['eael_ext_toc_collapse_sub_heading'];
				$list_icon                       = $settings_data['eael_ext_toc_list_icon'];
				$toc_title                       = $settings_data['eael_ext_toc_title'];
				$toc_title_tag                   = isset( $settings_data['eael_ext_toc_title_tag'] ) ? $settings_data['eael_ext_toc_title_tag'] : 'h2';
				$icon_check                      = $settings_data['eael_ext_table_of_content_header_icon'];
				$sticky_scroll                   = $settings_data['eael_ext_toc_sticky_scroll'];
				$hide_mobile                     = $settings_data['eael_ext_toc_hide_in_mobile'];
				$content_selector                = $settings_data['eael_ext_toc_content_selector'];
				$exclude_selector                = $settings_data['eael_toc_exclude_selector'];

				$el_class .= ( $position == 'right' ) ? ' eael-toc-right' : ' eael-toc-left';
				$el_class .= ( $close_bt_text_style == 'bottom_to_top' ) ? ' eael-bottom-to-top' : ' ';
				$el_class .= ( $auto_collapse == 'yes' ) ? ' eael-toc-auto-collapse collapsed' : ' ';
				$el_class .= ( $hide_mobile == 'yes' ) ? ' eael-toc-mobile-hide' : ' ';

				if( 'yes' === $is_mobile_on ) {
					$el_class .= ( 'top' === $mobile_position ) ? ' eael-toc-top' : ' eael-toc-bottom';
				}

				$toc_style_class = ' eael-toc-list-' . $toc_style;
				$toc_style_class .= ( $toc_collapse == 'yes' ) ? ' eael-toc-collapse' : ' ';
				$toc_style_class .= ( $list_icon == 'number' ) ? ' eael-toc-number' : ' eael-toc-bullet';
				$toc_style_class .= ( $toc_word_wrap == 'yes' ) ? ' eael-toc-word-wrap' : ' ';
				$toc_style_class .= ( $auto_highlight == 'yes' ) ? ' eael-toc-auto-highlight' : ' ';
				$toc_style_class .= ( $auto_highlight == 'yes' && $auto_highlight_single_item_only == 'yes' ) ? ' eael-toc-highlight-single-item' : ' ';
				$title_url       = ( $title_to_url == 'yes' ) ? 'true' : 'false';
				$icon_html       = ! empty( $icon_check['value'] ) ? "<i class='" . esc_attr( $icon_check['value'] ) . "'></i>" : '';

				$table_of_content_html = "<div data-eaelTocTag='" . esc_attr( $support_tag ) . "' data-contentSelector='" . esc_attr( $content_selector ) . "' data-excludeSelector='" . esc_attr( $exclude_selector ) . "' data-stickyScroll='" . esc_attr( $sticky_scroll['size'] ) . "' data-titleUrl='" . esc_attr( $title_url ) . "' data-page_offset='" . esc_attr( $page_offset ) . "' id='eael-toc' class='" . esc_attr( $el_class ) . " '>
                    <div class='eael-toc-header'>
                            <span class='eael-toc-close'>×</span>
                            <" . Helper::eael_validate_html_tag( $toc_title_tag ) . " class='eael-toc-title'>" . esc_html( $toc_title ) . "</" . Helper::eael_validate_html_tag( $toc_title_tag ) . ">
                    </div>
                    <div class='eael-toc-body'>
                        <ul id='eael-toc-list' class='eael-toc-list " . esc_attr( $toc_style_class ) . "'></ul>
                    </div>
                    <button class='eael-toc-button'>" . wp_kses( $icon_html, [ 'i' => [ 'class' => [] ] ] ) . "<span>" . esc_html( $toc_title ) . "</span></button>
                </div>";

				$is_toc_enabled    = $this->get_extensions_value( 'eael_ext_table_of_content' );
				$should_render_toc = 'yes' === $is_toc_enabled;

				if ( 'yes' !== $is_toc_enabled ) {
					$toc_global_display_condition = $this->get_extensions_value( 'eael_ext_toc_global_display_condition' );
					if ( 'page' === $toc_global_display_condition ) {
						$should_render_toc = is_page();
					} else if ( 'post' === $toc_global_display_condition ) {
						$should_render_toc = is_single();
					} else if ( 'all' === $toc_global_display_condition ){
						$should_render_toc = true;
					} else if ( get_post_type() === $toc_global_display_condition ){
						$should_render_toc = true;
					}

					if ( get_post_status( $this->get_extensions_value( 'post_id' ) ) !== 'publish' ) {
						$should_render_toc = false;
					}
				}

				// Exclude TOC configured page / post based on display condition
				if ( $toc_status && $toc_status_global ) {
					$toc_global_display_condition = $this->get_extensions_value( 'eael_ext_toc_global_display_condition' );
					if ( 'page' === $toc_global_display_condition ) {
						$should_render_toc = is_page();
					} else if ( 'post' === $toc_global_display_condition ) {
						$should_render_toc = is_single();
					} else if ( 'all' === $toc_global_display_condition ){
						$should_render_toc = true;
					} else if ( get_post_type() === $toc_global_display_condition ){
						$should_render_toc = true;
					}
				}

				if( ! $should_render_toc ){
					$table_of_content_html = '';
				}

				if ( ! empty( $table_of_content_html ) ) {
					wp_enqueue_style( 'eael-table-of-content' );
					wp_enqueue_script( 'eael-table-of-content' );

					$html .= $table_of_content_html;
				}
			}
		}

		//Scroll to Top
		if ( $this->get_settings( 'scroll-to-top' ) == true ) {
			if ( isset( $document ) && is_object( $document ) ) {
				$document_settings_data = $document->get_settings();
			}

			$scroll_to_top_status = $scroll_to_top_status_global = false;

			if ( isset( $document_settings_data['eael_ext_scroll_to_top'] ) && $document_settings_data['eael_ext_scroll_to_top'] == 'yes' ) {
				$scroll_to_top_status        = true;
				$settings_data_scroll_to_top = $document_settings_data;
			} elseif ( isset( $global_settings['eael_ext_scroll_to_top']['enabled'] ) && $global_settings['eael_ext_scroll_to_top']['enabled'] ) {
				$scroll_to_top_status        = true;
				$scroll_to_top_status_global = true;
				$settings_data_scroll_to_top = $global_settings['eael_ext_scroll_to_top'];
			}

			if ( $scroll_to_top_status ) {
				if ( $scroll_to_top_status_global ) {
					//global status is true only when locally scroll to top is disabled.
					$this->scroll_to_top_global_css( $global_settings );
				}
				$scroll_to_top_icon_image = ! empty( $settings_data_scroll_to_top['eael_ext_scroll_to_top_button_icon_image'] )
					? $settings_data_scroll_to_top['eael_ext_scroll_to_top_button_icon_image']['value'] : '';

				$scroll_to_top_icon_html = \Essential_Addons_Elementor\Classes\Helper::get_render_icon( $settings_data_scroll_to_top['eael_ext_scroll_to_top_button_icon_image'] ?? '' );

				$scroll_to_top_html = "<div class='eael-ext-scroll-to-top-wrap scroll-to-top-hide'><span class='eael-ext-scroll-to-top-button'>$scroll_to_top_icon_html</span></div>";

				$scroll_to_top_global_display_condition = isset( $settings_data_scroll_to_top['eael_ext_scroll_to_top_global_display_condition'] ) ? $settings_data_scroll_to_top['eael_ext_scroll_to_top_global_display_condition'] : 'all';

				if ( isset( $settings_data_scroll_to_top['post_id'] ) && $settings_data_scroll_to_top['post_id'] != get_the_ID() ) {
					if ( get_post_status( $settings_data_scroll_to_top['post_id'] ) != 'publish' ) {
						$scroll_to_top_html = '';
					} else if ( $scroll_to_top_global_display_condition == 'pages' && ! is_page() ) {
						$scroll_to_top_html = '';
					} else if ( $scroll_to_top_global_display_condition == 'posts' && ! is_single() ) {
						$scroll_to_top_html = '';
					}
				}

				if ( ! empty( $scroll_to_top_html ) ) {
					wp_enqueue_script( 'eael-scroll-to-top' );
					wp_enqueue_style( 'eael-scroll-to-top' );

					$html .= $scroll_to_top_html;
				}
			}
		}

		//Custom Cursor
		if ( $this->get_settings( 'custom-cursor' ) == true ) {
			do_action( 'eael/custom_cursor/page_render', $document, $global_settings );
		}
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( '%1$s', $html );
	}

	/**
	 * @param $post_css
	 * @param $elements
	 *
	 * @return string|void
	 */
	public function toc_global_css( $global_settings ) {
		$eael_toc                 = $global_settings['eael_ext_table_of_content'];
		$eael_toc_width           = isset( $eael_toc['eael_ext_toc_width']['size'] ) ? $eael_toc['eael_ext_toc_width']['size'] : 300;
		$toc_list_color_active    = $eael_toc['eael_ext_table_of_content_list_text_color_active'];
		$toc_list_separator_style = $eael_toc['eael_ext_table_of_content_list_separator_style'];
		$header_padding           = $eael_toc['eael_ext_toc_header_padding'];
		$body_padding             = $eael_toc['eael_ext_toc_body_padding'];
		$header_typography        = $this->get_typography_data( 'eael_ext_table_of_content_header_typography', $eael_toc );
		$list_typography          = $this->get_typography_data( 'eael_ext_table_of_content_list_typography_normal', $eael_toc );
		$box_shadow               = $eael_toc['eael_ext_toc_table_box_shadow_box_shadow'];
		$border_radius            = $eael_toc['eael_ext_toc_box_border_radius']['size'];
		$bullet_size              = $eael_toc['eael_ext_toc_box_list_bullet_size']['size'];
		$top_position             = $eael_toc['eael_ext_toc_box_list_top_position']['size'];
		$indicator_size           = $eael_toc['eael_ext_toc_indicator_size']['size'];
		$indicator_position       = $eael_toc['eael_ext_toc_indicator_position']['size'];
		$close_bt_box_shadow      = $eael_toc['eael_ext_table_of_content_close_button_box_shadow'];
		$toc_global_css           = "
            .eael-toc-global .eael-toc-header,
            .eael-toc-global.collapsed .eael-toc-button
            {
                background-color:{$eael_toc['eael_ext_table_of_content_header_bg']};
            }

            .eael-toc-global {
                width:{$eael_toc_width}px;
                z-index:{$eael_toc['eael_ext_toc_sticky_z_index']['size']};
            }

            .eael-toc-global.eael-sticky {
                top:{$eael_toc['eael_ext_toc_sticky_offset']['size']};
            }
            .eael-toc-global .eael-toc-header .eael-toc-title,
            .eael-toc-global.collapsed .eael-toc-button
            {
                color:{$eael_toc['eael_ext_table_of_content_header_text_color']};
                $header_typography
            }
            .eael-toc-global .eael-toc-header {
                padding:{$header_padding['top']}px {$header_padding['right']}px {$header_padding['bottom']}px {$header_padding['left']}px;
            }

            .eael-toc-global .eael-toc-body {
                padding:{$body_padding['top']}px {$body_padding['right']}px {$body_padding['bottom']}px {$body_padding['left']}px;
            }

            .eael-toc-global .eael-toc-close
            {
                font-size: {$eael_toc['eael_ext_table_of_content_close_button_icon_size']['size']}px !important;
                height: {$eael_toc['eael_ext_table_of_content_close_button_size']['size']}px !important;
                width: {$eael_toc['eael_ext_table_of_content_close_button_size']['size']}px !important;
                line-height: {$eael_toc['eael_ext_table_of_content_close_button_line_height']['size']}px !important;
                color:{$eael_toc['eael_ext_table_of_content_close_button_text_color']} !important;
                background-color:{$eael_toc['eael_ext_table_of_content_close_button_bg']} !important;
                border-radius: {$eael_toc['eael_ext_table_of_content_close_button_border_radius']['size']}px !important;
                box-shadow:{$close_bt_box_shadow['horizontal']}px {$close_bt_box_shadow['vertical']}px {$close_bt_box_shadow['blur']}px {$close_bt_box_shadow['spread']}px {$close_bt_box_shadow['color']} !important;
            }

            .eael-toc-global.eael-toc:not(.collapsed)
            {
                box-shadow:{$box_shadow['horizontal']}px {$box_shadow['vertical']}px {$box_shadow['blur']}px {$box_shadow['spread']}px {$box_shadow['color']};
            }

            .eael-toc-global .eael-toc-body
            {
                background-color:{$eael_toc['eael_ext_table_of_content_body_bg']};
            }

            .eael-toc-global .eael-toc-body ul.eael-toc-list.eael-toc-bullet li:before
            {
                width:{$bullet_size}px;
                height:{$bullet_size}px;
                top:{$top_position}px;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list li,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-number li:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li a
            {
                color:{$eael_toc['eael_ext_table_of_content_list_text_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-bullet li:before {
                background-color:{$eael_toc['eael_ext_table_of_content_list_text_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list li:hover,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-number li:hover:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li:hover > a
            {
                color:{$eael_toc['eael_ext_table_of_list_hover_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list li:hover > a:before {
                border-bottom-color:{$eael_toc['eael_ext_table_of_list_hover_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-bullet li:hover:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li:hover > a:after {
                background-color:{$eael_toc['eael_ext_table_of_list_hover_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-active,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-number li.eael-highlight-active:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-active > a,
            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-parent,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-number li.eael-highlight-parent:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-parent > a
            {
                color: $toc_list_color_active !important;
            }


            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-active > a:before
            {
                border-bottom-color: $toc_list_color_active !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-bullet li.eael-highlight-active:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-active > a:after,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-bullet li.eael-highlight-parent:before
            {
                background-color: $toc_list_color_active !important;
            }

            .eael-toc-global ul.eael-toc-list > li
            {
                color:{$eael_toc['eael_ext_table_of_content_list_separator_color']} !important;
                $list_typography
            }
            .eael-toc.eael-toc-global .eael-toc-body ul.eael-toc-list li:before {
                $list_typography
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-list-bar li.eael-highlight-active > a:after {
                height:{$indicator_size}px;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-list-arrow li.eael-highlight-active > a:before,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-list-bar li.eael-highlight-active > a:after {
                margin-top:{$indicator_position}px;
            }


            .eael-toc:not(.eael-toc-right)
            {
                border-top-right-radius:{$border_radius}px;
                border-bottom-right-radius:{$border_radius}px;
            }

            .eael-toc:not(.eael-toc-right) .eael-toc-header
            {
                border-top-right-radius:{$border_radius}px;
            }

            .eael-toc:not(.eael-toc-right) .eael-toc-body {
                border-bottom-right-radius:{$border_radius}px;
            }

            .eael-toc.eael-toc-right {
                border-top-left-radius:{$border_radius}px;
                border-bottom-left-radius:{$border_radius}px;
            }

            .eael-toc.eael-toc-right .eael-toc-header {
                border-top-left-radius:{$border_radius}px;
            }

            .eael-toc.eael-toc-right .eael-toc-body {
                border-bottom-left-radius:{$border_radius}px;
            }


            #eael-toc.eael-toc-global ul.eael-toc-list > li
            {
                padding-top:{$eael_toc['eael_ext_toc_top_level_space']['size']}px;
                padding-bottom:{$eael_toc['eael_ext_toc_top_level_space']['size']}px;
            }

            #eael-toc.eael-toc-global ul.eael-toc-list>li ul li
            {
                padding-top:{$eael_toc['eael_ext_toc_subitem_level_space']['size']}px;
                padding-bottom:{$eael_toc['eael_ext_toc_subitem_level_space']['size']}px;
            }
        ";
		if ( $toc_list_separator_style != 'none' ) {
			$toc_global_css .= "
            .eael-toc-global ul.eael-toc-list > li
            {border-top: 0.5px $toc_list_separator_style !important;}
            .eael-toc ul.eael-toc-list>li:first-child
            {border: none !important;}";
		}

		if ( isset( $eael_toc['eael_ext_toc_border_border'] ) ) {
			$border_width   = $eael_toc['eael_ext_toc_border_width'];
			$toc_global_css .= "
            .eael-toc.eael-toc-global,.eael-toc-global button.eael-toc-button
            {
                border-style: {$eael_toc['eael_ext_toc_border_border']};
                border-width: {$border_width['top']}px {$border_width['right']}px {$border_width['bottom']}px {$border_width['left']}px;
                border-color: {$eael_toc['eael_ext_toc_border_color']};
            }";
		}

		wp_add_inline_style( 'eael-table-of-content', $toc_global_css );
	}
	
	/**
	 * @param $document_settings
	 *
	 * @return string|void
	 */
	public function progress_bar_local_css( $document_settings ) {
		$eael_reading_progress_fill_color = Helper::eael_fetch_color_or_global_color($document_settings, 'eael_ext_reading_progress_fill_color');

		$reading_progress_local_css = '';
		$eael_reading_progress_id_selector = '#eael-reading-progress-' . get_the_ID();
		if( ! empty( $eael_reading_progress_fill_color ) ){
			$reading_progress_local_css .= "
				{$eael_reading_progress_id_selector} .eael-reading-progress .eael-reading-progress-fill {
					background-color: {$eael_reading_progress_fill_color};	
				}
			";
		}
		wp_add_inline_style( 'eael-reading-progress', $reading_progress_local_css );
	}

	/**
	 * @return string|void
	 */
	public function scroll_to_top_global_css( $global_settings ) {
		if ( ! is_array( $global_settings ) ) {
			return false;
		}

		if ( empty( $global_settings['eael_ext_scroll_to_top'] ) ) {
			return false;
		}

		$eael_scroll_to_top            = $global_settings['eael_ext_scroll_to_top'];
		$eael_stt_position             = $eael_scroll_to_top['eael_ext_scroll_to_top_position_text'];
		$eael_stt_position_bottom_size = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_position_bottom']['size'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_position_bottom']['size'] : 5;
		$eael_stt_position_bottom_unit = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_position_bottom']['unit'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_position_bottom']['unit'] : 'px';
		$eael_stt_position_left_size   = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_position_left']['size'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_position_left']['size'] : 15;
		$eael_stt_position_left_unit   = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_position_left']['unit'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_position_left']['unit'] : 'px';
		$eael_stt_position_right_size  = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_position_right']['size'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_position_right']['size'] : 15;
		$eael_stt_position_right_unit  = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_position_right']['unit'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_position_right']['unit'] : 'px';

		$eael_stt_button_width_size         = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_button_width']['size'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_button_width']['size'] : 50;
		$eael_stt_button_width_unit         = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_button_width']['unit'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_button_width']['unit'] : 'px';
		$eael_stt_button_height_size        = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_button_height']['size'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_button_height']['size'] : 50;
		$eael_stt_button_height_unit        = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_button_height']['unit'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_button_height']['unit'] : 'px';
		$eael_stt_z_index_size              = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_z_index']['size'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_z_index']['size'] : 9999;
		$eael_stt_z_index_unit              = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_z_index']['unit'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_z_index']['unit'] : 'px';
		$eael_stt_button_opacity_size       = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_button_opacity']['size'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_button_opacity']['size'] : 0.7;
		$eael_stt_button_opacity_unit       = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_button_opacity']['unit'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_button_opacity']['unit'] : 'px';
		$eael_stt_button_icon_size_size     = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_button_icon_size']['size'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_button_icon_size']['size'] : 16;
		$eael_stt_button_icon_size_unit     = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_button_icon_size']['unit'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_button_icon_size']['unit'] : 'px';
		$eael_stt_button_icon_svg_size_size = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_button_icon_svg_size']['size'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_button_icon_svg_size']['size'] : 32;
		$eael_stt_button_icon_svg_size_unit = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_button_icon_svg_size']['unit'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_button_icon_svg_size']['unit'] : 'px';
		$eael_stt_button_icon_color         = $eael_scroll_to_top['eael_ext_scroll_to_top_button_icon_color'];
		$eael_stt_button_bg_color           = $eael_scroll_to_top['eael_ext_scroll_to_top_button_bg_color'];
		$eael_stt_button_border_radius_size = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_button_border_radius']['size'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_button_border_radius']['size'] : 5;
		$eael_stt_button_border_radius_unit = isset( $eael_scroll_to_top['eael_ext_scroll_to_top_button_border_radius']['unit'] ) ? $eael_scroll_to_top['eael_ext_scroll_to_top_button_border_radius']['unit'] : 'px';

		$eael_stt_position_left_right_key   = $eael_stt_position == 'bottom-left' ? 'left' : 'right';
		$eael_stt_position_left_right_value = $eael_stt_position == 'bottom-left' ? $eael_stt_position_left_size . $eael_stt_position_left_unit : $eael_stt_position_right_size . $eael_stt_position_right_unit;

		$scroll_to_top_global_css = "
            .eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button {
                bottom: {$eael_stt_position_bottom_size}{$eael_stt_position_bottom_unit};
                {$eael_stt_position_left_right_key}: {$eael_stt_position_left_right_value};
                width: {$eael_stt_button_width_size}{$eael_stt_button_width_unit};
                height: {$eael_stt_button_height_size}{$eael_stt_button_height_unit};
                z-index: {$eael_stt_z_index_size};
                opacity: {$eael_stt_button_opacity_size};
                background-color: {$eael_stt_button_bg_color};
                border-radius: {$eael_stt_button_border_radius_size}{$eael_stt_button_border_radius_unit};
            }

            .eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button i {
                color: {$eael_stt_button_icon_color};
                font-size: {$eael_stt_button_icon_size_size}{$eael_stt_button_icon_size_unit};
            }

            .eael-ext-scroll-to-top-wrap .eael-ext-scroll-to-top-button svg {
                fill: {$eael_stt_button_icon_color};
                width: {$eael_stt_button_icon_size_size}{$eael_stt_button_icon_size_unit};
                height: {$eael_stt_button_icon_size_size}{$eael_stt_button_icon_size_unit};
            } 
        ";

		wp_add_inline_style( 'eael-scroll-to-top', $scroll_to_top_global_css );
	}

	/**
	 * Register WC Hooks
	 */
	public function register_wc_hooks() {
		if ( class_exists( 'WooCommerce' ) ) {
			wc()->frontend_includes();
		}
	}

	public function get_extensions_value( $key = '' ) {
		return isset( $this->extensions_data[ $key ] ) ? $this->extensions_data[ $key ] : '';
	}

    /**
     * Single instance for all advanced accordion faqs
     *
     * @return void
     */
    public function render_advanced_accordion_global_faq(){
        if( count( Helper::get_eael_advanced_accordion_faq() )) : ?>
            <!-- EA FAQ Schema : Starts-->
            <script type="application/ld+json">
                <?php echo json_encode( Helper::get_eael_advanced_accordion_faq() ); ?>
            </script>
            <!-- EA FAQ Schema : Ends-->
        <?php endif;
    }
}

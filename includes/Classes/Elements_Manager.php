<?php

namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Plugin;

class Elements_Manager {
	public $post_id;

	const ELEMENT_KEY = '_eael_widget_elements';

	const JS_KEY = '_eael_custom_js';

	public $registered_elements;

	public $registered_extensions;

	public function __construct( $registered_elements, $registered_extensions ) {
		$this->registered_elements   = $registered_elements;
		$this->registered_extensions = $registered_extensions;
		add_action( 'elementor/editor/after_save', array( $this, 'eael_elements_cache' ), 10, 2 );
	}

	public function eael_elements_cache( $post_id, $data ) {
		$widget_list  = $this->get_widget_list( $data );
		$page_setting = get_post_meta( $post_id, '_elementor_page_settings', true );
		$custom_js    = isset( $page_setting['eael_custom_js'] ) ? trim( $page_setting['eael_custom_js'] ) : '';

		update_post_meta( $post_id, '_eael_custom_js', $custom_js );
		$this->save_elements_data( $post_id, $widget_list );
	}

	public function get_widget_list( $data ) {
		$widget_list = [];
		Plugin::$instance->db->iterate_data( $data, function ( $element ) use ( &$widget_list ) {

			if ( empty( $element['widgetType'] ) ) {
				$type = $element['elType'];
			} else {
				$type = $element['widgetType'];
			}
			$replace = $this->replace_widget_name();
			if ( strpos( $type, 'eael-' ) !== false ) {

				if ( isset( $replace[ $type ] ) ) {
					$type = $replace[ $type ];
				}

				$type = str_replace( 'eael-', '', $type );
				if ( ! isset( $widget_list[ $type ] ) ) {
					$widget_list[ $type ] = $type;
				}
			}

			$widget_list += $this->get_ext_name( $element );
		} );

		return $widget_list;
	}

	public function get_element_data(  ) {

		if ( Plugin::instance()->editor->is_edit_mode() ) {
			return false;
		}

		if ( $this->has_exist( $this->post_id ) ) {
			return false;
		}

		$document = Plugin::$instance->documents->get( $this->post_id );
		$data     = $document ? $document->get_elements_data() : [];
		$data     = $this->get_widget_list( $data );
		update_post_meta( $this->post_id, '_eael_custom_js', $document->get_settings( 'eael_custom_js' ) );
		$this->save_widgets_meta( $this->post_id, $data );
	}

	public function get_ext_name( $element ) {
		$list = [];
		if ( isset( $element['elType'] ) && $element['elType'] == 'section' ) {
			if ( ! empty( $element['settings']['eael_particle_switch'] ) ) {
				$list['section-particles'] = 'section-particles';
			}
			if ( ! empty( $element['settings']['eael_parallax_switcher'] ) ) {
				$list['section-parallax'] = 'section-parallax';
			}
		} else {
			if ( ! empty( $element['settings']['eael_tooltip_section_enable'] ) ) {
				$list['tooltip-section'] = 'tooltip-section';
			}
			if ( ! empty( $element['settings']['eael_ext_content_protection'] ) ) {
				$list['content-protection'] = 'content-protection';
			}
		}

		return $list;
	}

	public function replace_widget_name() {
		return $replace = [
			'eicon-woocommerce'               => 'eael-product-grid',
			'eael-countdown'                  => 'eael-count-down',
			'eael-creative-button'            => 'eael-creative-btn',
			'eael-team-member'                => 'eael-team-members',
			'eael-testimonial'                => 'eael-testimonials',
			'eael-weform'                     => 'eael-weforms',
			'eael-cta-box'                    => 'eael-call-to-action',
			'eael-dual-color-header'          => 'eael-dual-header',
			'eael-pricing-table'              => 'eael-price-table',
			'eael-filterable-gallery'         => 'eael-filter-gallery',
			'eael-one-page-nav'               => 'eael-one-page-navigation',
			'eael-interactive-card'           => 'eael-interactive-cards',
			'eael-image-comparison'           => 'eael-img-comparison',
			'eael-dynamic-filterable-gallery' => 'eael-dynamic-filter-gallery',
			'eael-google-map'                 => 'eael-adv-google-map',
			'eael-instafeed'                  => 'eael-instagram-gallery',
		];
	}

	public function save_widgets_meta( $post_id, $list ) {
		if ( get_post_status( $post_id ) !== 'publish' || ! Plugin::$instance->documents->get( $post_id )->is_built_with_elementor() ) {
			return false;
		}
		update_post_meta( $post_id, self::ELEMENT_KEY, $list );
		$this->remove_files_new( $post_id );

		if ( ! empty( $list ) ) {
			if ( get_option( 'eael_js_print_method' ) == 'internal' ) {
				$this->custom_js .= $this->generate_strings_new( $list, 'view', 'js' );
			} else {
				$this->generate_script_new( $post_id, $list, 'view', 'js' );
			}

			$this->generate_script_new( $post_id, $list, 'view', 'css' );

			if ( get_option( 'elementor_css_print_method' ) == 'internal' ) {
				$this->custom_css .= $this->generate_strings_new( $list, 'view', 'css' );
			} else {
				$this->generate_script_new( $post_id, $list, 'view', 'css' );
			}

		}
	}
}

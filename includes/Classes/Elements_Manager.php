<?php

namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Plugin;
use Essential_Addons_Elementor\Traits\Library;

class Elements_Manager {
	use Library;

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

		$this->save_widgets_list( $post_id, $widget_list, $custom_js );
	}

	public function get_widget_list( $data ) {
		$widget_list = [];
		$replace = $this->replace_widget_name();
		Plugin::$instance->db->iterate_data( $data, function ( $element ) use ( &$widget_list, $replace ) {

			if ( empty( $element['widgetType'] ) ) {
				$type = $element['elType'];
			} else {
				$type = $element['widgetType'];
			}

			if ( ! empty( $element['widgetType'] ) && $element['widgetType'] === 'global' ) {
				$document = Plugin::$instance->documents->get( $element['templateID'] );
				$type = current( $this->get_widget_list( $document->get_elements_data() ) );

				if ( ! empty( $type ) ) {
					$type = 'eael-' . $type;
				}
			}

			if ( ! empty( $type ) && !is_array($type)  ) {
				if ( strpos( $type, 'eael-' ) !== false ) {

					if ( isset( $replace[ $type ] ) ) {
						$type = $replace[ $type ];
					}

					$type = str_replace( 'eael-', '', $type );
					if ( ! isset( $widget_list[ $type ] ) ) {
						$widget_list[ $type ] = $type;
					}
				}

				$widget_list += $this->get_extension_list( $element );
			}

		} );

		return $widget_list;
	}

	public function get_element_list( $post_id ) {

		if ( Plugin::instance()->editor->is_edit_mode() ) {
			return false;
		}

		if ( $this->has_exist( $post_id ) ) {
			return false;
		}

		$document = Plugin::$instance->documents->get( $post_id );
		$data     = $document ? $document->get_elements_data() : [];
		$data     = $this->get_widget_list( $data );
		$this->save_widgets_list( $post_id, $data, $document->get_settings( 'eael_custom_js' ) );
		return true;
	}

	public function get_extension_list( $element ) {
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
		return [
			'eicon-woocommerce'               => 'eael-product-grid',
			'eael-countdown'                  => 'eael-count-down',
			'eael-creative-button'            => 'eael-creative-btn',
			'eael-team-member'                => 'eael-team-members',
			'eael-testimonial'                => 'eael-testimonials',
			'eael-weform'                     => 'eael-weforms',
			'eael-cta-box'                    => 'eael-call-to-action',
			'eael-dual-color-header'          => 'eael-dual-header',
			'eael-pricing-table'              => 'eael-price-table',
			'eael-filterable-gallery'          => 'eael-filter-gallery',
			'eael-one-page-nav'               => 'eael-one-page-navigation',
			'eael-interactive-card'           => 'eael-interactive-cards',
			'eael-image-comparison'           => 'eael-img-comparison',
			'eael-dynamic-filterable-gallery'  => 'eael-dynamic-filter-gallery',
			'eael-google-map'                 => 'eael-adv-google-map',
			'eael-instafeed'                  => 'eael-instagram-gallery',
		];
	}

	public function save_widgets_list( $post_id, $list, $custom_js = '' ) {

		if ( \defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( get_post_status( $post_id ) !== 'publish' || ! Plugin::$instance->documents->get( $post_id )->is_built_with_elementor() ) {
			return false;
		}

		if ( in_array( get_post_meta( $post_id, '_elementor_template_type', true ), $this->excluded_template_type() ) ) {
			return false;
		}

		update_post_meta( $post_id, '_eael_custom_js', $custom_js );

		if ( md5( implode('', (array) $list) ) == md5( implode('', (array) get_post_meta( $post_id, self::ELEMENT_KEY, true )) ) ) {
			return false;
		}

		try {
			update_post_meta( $post_id, self::ELEMENT_KEY, $list );
			$this->remove_files( $post_id );

			return true;
		} catch ( \Exception $e ) {
			return false;
		}
	}

	public function has_exist( $post_id ) {
		$status = get_post_meta( $post_id, self::ELEMENT_KEY, true );

		return ! empty( $status );
	}

	public function excluded_template_type() {
		return [
			'kit',
			'search-results'
		];
	}
}

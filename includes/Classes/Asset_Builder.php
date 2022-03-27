<?php

namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
use Elementor\Core\Files\CSS\Post as Post_CSS;
use Elementor\Plugin;

class Asset_Builder {

	public $post_id;
	const ELEMENT_KEY = '_eael_widget_elements';
	const JS_KEY = '_eael_custom_js';

	public function __construct() {
		add_action( 'elementor/editor/after_save', array( $this, 'eael_elements_cache' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_asset_load' ] );
		add_action( 'elementor/css-file/post/enqueue', [ $this, 'post_asset_load' ] );
	}

	public function eael_elements_cache( $post_id, $data ) {
		$widget_list = $this->get_widget_list( $data );
		$this->save_elements_data( $post_id, $widget_list );
	}

	public function frontend_asset_load() {
		$this->post_id = get_the_ID();
		$this->get_element_data();
	}

	public function post_asset_load( Post_CSS $css ) {
		$this->post_id = $css->get_post_id();
		$this->get_element_data();
	}

	public function get_ext_name( $element ) {
		$list = [];
		if ( isset( $element['elType'] ) && $element['elType'] == 'section' ) {
			if ( ! empty( $element['settings']['eael_particle_switch'] ) ) {
				$list['eael-section-particles'] = 'eael-section-particles';
			}
			if ( ! empty( $element['settings']['eael_parallax_switcher'] ) ) {
				$list['eael-section-parallax'] = 'eael-section-parallax';
			}
		} else {
			if ( ! empty( $element['settings']['eael_tooltip_section_enable'] ) ) {
				$list['eael-tooltip-section'] = 'eael-tooltip-section';
			}
			if ( ! empty( $element['settings']['eael_ext_content_protection'] ) ) {
				$list['eael-content-protection'] = 'eael-content-protection';
			}
		}

		return $list;
	}

	public function get_element_data() {

		if ( Plugin::instance()->editor->is_edit_mode() ) {
			return false;
		}

		if ( $this->has_exist( $this->post_id ) ) {
			return false;
		}

		$document = Plugin::$instance->documents->get( $this->post_id );
		$data = $document ? $document->get_elements_data() : [];
		$data = $this->get_widget_list( $data );
		$this->save_elements_data( $this->post_id, $data );
	}

	public function get_widget_list( $data ) {
		$widget_list = [];
		Plugin::$instance->db->iterate_data( $data, function ( $element ) use ( &$widget_list ) {

			if ( empty( $element['widgetType'] ) ) {
				$type = $element['elType'];
			} else {
				$type = $element['widgetType'];
			}

			if ( strpos( $type, 'eael-' ) !== false ) {
				if ( ! isset( $widget_list[ $type ] ) ) {
					$widget_list[ $type ] = $type;
				}
			}

			$widget_list += $this->get_ext_name( $element );
		} );

		return $widget_list;
	}

	public function save_elements_data( $post_id, $list ) {
		if ( get_post_status( $post_id ) !== 'publish' || ! Plugin::$instance->documents->get( $post_id )->is_built_with_elementor() ) {
			return false;
		}
		update_post_meta( $post_id, self::ELEMENT_KEY, $list );
	}

	public function has_exist( $post_id ) {
		$status = get_post_meta( $post_id, self::ELEMENT_KEY, true );
		if ( ! empty( $status ) ) {
			return true;
		}

		return false;
	}
}

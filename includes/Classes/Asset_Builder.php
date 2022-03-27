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
	public $registered_elements;
	public $registered_extensions;

	public function __construct( $registered_elements, $registered_extensions) {
		$this->registered_elements = $registered_elements;
		$this->registered_extensions = $registered_extensions;
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

	public function get_element_data() {

		if ( Plugin::instance()->editor->is_edit_mode() ) {
			return false;
		}

		if ( $this->has_exist( $this->post_id ) ) {
			return false;
		}

		$document = Plugin::$instance->documents->get( $this->post_id );
		$data     = $document ? $document->get_elements_data() : [];
		$data     = $this->get_widget_list( $data );
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
				$type = str_replace( 'eael-', '', $type );
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
		$this->remove_files_new( $post_id );

		if ( ! empty( $list ) ) {
			$this->generate_script_new( $post_id, $list, 'view', 'css' );
			$this->generate_script_new( $post_id, $list, 'view', 'js' );
		}
	}

	public function has_exist( $post_id ) {
		$status = get_post_meta( $post_id, self::ELEMENT_KEY, true );
		if ( ! empty( $status ) ) {
			return true;
		}

		return false;
	}

	public function cache_asset() {

	}

	public function enqueue_asset() {

	}

	public function has_asset() {

	}

	public function generate_script_new( $post_id, $elements, $context, $ext ) {
		// if folder not exists, create new folder
		if ( ! file_exists( EAEL_ASSET_PATH ) ) {
			wp_mkdir_p( EAEL_ASSET_PATH );
		}

		// naming asset file
		$file_name = 'eael-' . $post_id .'.'. $ext;

		// output asset string
		$output = $this->generate_strings_new( $elements, $context, $ext );

		// write to file
		$file_path = $this->safe_path_new( EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . $file_name );
		file_put_contents( $file_path, $output );
	}

	public function generate_strings_new( $elements, $context, $ext ) {
		$output = '';

		$paths  = $this->generate_dependency_new( $elements, $context, $ext );

		if ( ! empty( $paths ) ) {
			foreach ( $paths as $path ) {
				$output .= file_get_contents( $this->safe_path_new( $path ) );
			}
		}

		return $output;
	}

	public function generate_dependency_new( array $elements, $context, $type ) {
		$lib  = [ 'view' => [], 'edit' => [] ];
		$self = [ 'general' => [], 'view' => [], 'edit' => [] ];

		if ( $type == 'js' ) {
			$self['general'][] = EAEL_PLUGIN_PATH . 'assets/front-end/js/view/general.min.js';
			$self['edit'][]    = EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/promotion.min.js';
		} else if ( $type == 'css' ) {
			$self['view'][] = EAEL_PLUGIN_PATH . "assets/front-end/css/view/general.min.css";
		}
		foreach ( $elements as $element ) {

			if ( isset( $this->registered_elements[ $element ] ) ) {
				if ( ! empty( $this->registered_elements[ $element ]['dependency'][ $type ] ) ) {
					foreach ( $this->registered_elements[ $element ]['dependency'][ $type ] as $file ) {
						${$file['type']}[ $file['context'] ][] = $file['file'];
					}
				}
			} elseif ( isset( $this->registered_extensions[ $element ] ) ) {
				if ( ! empty( $this->registered_extensions[ $element ]['dependency'][ $type ] ) ) {
					foreach ( $this->registered_extensions[ $element ]['dependency'][ $type ] as $file ) {
						${$file['type']}[ $file['context'] ][] = $file['file'];
					}
				}
			}
		}

		if ( $context == 'view' ) {
			return array_unique( array_merge( $lib['view'], $self['general'], $self['view'] ) );
		}

		return array_unique( array_merge( $lib['view'], $lib['edit'], $self['general'], $self['edit'], $self['view'] ) );
	}

	public function safe_path_new( $path ) {
		$path = str_replace( [ '//', '\\\\' ], [ '/', '\\' ], $path );

		return str_replace( [ '/', '\\' ], DIRECTORY_SEPARATOR, $path );
	}

	public function remove_files_new( $post_id = null, $ext = [ 'css', 'js' ] ) {
		foreach ( $ext as $e ) {
			$path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . 'eael-' . $post_id .'.'. $e;
			if ( file_exists( $path ) ) {
				unlink( $path );
			}
		}
	}
}

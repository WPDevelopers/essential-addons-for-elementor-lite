<?php

namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
use Elementor\Core\Files\CSS\Post as Post_CSS;
use Elementor\Plugin;
use Essential_Addons_Elementor\Classes\Elements_Manager;

class Asset_Builder {

	public $post_id;
	public $custom_js = '';
	public $custom_css = '';
	public $elements_manager;

	public function __construct( $registered_elements, $registered_extensions ) {

		$this->elements_manager = new Elements_Manager( $registered_elements, $registered_extensions );
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_asset_load' ] );
		add_action( 'elementor/css-file/post/enqueue', [ $this, 'post_asset_load' ], 100 );
		add_action( 'wp_footer', [ $this, 'add_inline_js' ], 100 );
	}

	public function add_inline_js(){
		wp_add_inline_script( 'eael-load-js', $this->custom_js);

		printf( '<script id="eael-inline-js">%s</script>', $this->custom_js );
		printf( '<style id="eael-inline-css">%s</style>', $this->custom_css );
	}



	public function frontend_asset_load() {
		$this->post_id = get_the_ID();
		$this->elements_manager->get_element_list( $this->post_id );

		if ( !$this->is_edit() ) {
			wp_enqueue_script( 'eael-gent', EAEL_PLUGIN_URL . 'assets/front-end/js/view/general.min.js', [ 'jquery' ], 10, true );
		}

	}

	public function post_asset_load( Post_CSS $css ) {

		if ( $this->is_edit() ) {
			return false;
		}

		$this->post_id = $css->get_post_id();
		$this->elements_manager->get_element_list( $this->post_id );
		//$this->enqueue_asset( $this->post_id );
	}



	public function cache_asset() {

	}

	public function enqueue_asset( $post_id ) {

		if ( file_exists( $this->safe_path_new( EAEL_ASSET_PATH . '/' . 'eael-' . $post_id . '.css' ) ) ) {

			wp_enqueue_style(
				'eael-' . $post_id,
				$this->safe_url_new( EAEL_ASSET_URL . '/' . 'eael-' . $post_id . '.css' ),
				[ 'elementor-frontend' ],
				time()
			);

			wp_enqueue_script(
				'eael-' . $post_id,
				$this->safe_url_new( EAEL_ASSET_URL . '/' . 'eael-' . $post_id . '.js' ),
				[ 'eael-gent' ],
				time(),
				true
			);
		}

		$this->load_custom_js( $post_id );

	}

	public function has_asset( $post_id, $elements ) {
		//if ( ! file_exists( $this->safe_path_new( EAEL_ASSET_PATH . '/' . 'eael-' . $post_id . '.css' ) ) ) {
			if ( ! empty( $elements ) ) {
				if(get_option('eael_js_print_method') == 'internal'){
					$this->custom_js .= $this->generate_strings_new( $elements, 'view', 'js' );
				}else{
					$this->generate_script_new( $post_id, $elements, 'view', 'js' );
				}

				if (get_option('elementor_css_print_method') == 'internal') {
					$this->custom_css .= $this->generate_strings_new( $elements, 'view', 'css' );
				}else {
					$this->generate_script_new( $post_id, $elements, 'view', 'css' );
				}

			}
		//}
	}

	public function generate_script_new( $post_id, $elements, $context, $ext ) {
		// if folder not exists, create new folder
		if ( ! file_exists( EAEL_ASSET_PATH ) ) {
			wp_mkdir_p( EAEL_ASSET_PATH );
		}

		// naming asset file
		$file_name = 'eael-' . $post_id . '.' . $ext;

		// output asset string
		$output = $this->generate_strings_new( $elements, $context, $ext );

		// write to file
		$file_path = $this->safe_path_new( EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . $file_name );
		file_put_contents( $file_path, $output );
	}

	public function generate_strings_new( $elements, $context, $ext ) {
		$output = '';

		$paths = $this->generate_dependency_new( $elements, $context, $ext );

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
			return array_unique( array_merge( $lib['view'], $self['view'] ) );
		}

		return array_unique( array_merge( $lib['view'], $lib['edit'], $self['edit'], $self['view'] ) );
	}

	public function load_custom_js( $post_id ){
		$custom_js = get_post_meta( $post_id,'_eael_custom_js',true );
		if ( $custom_js ) {
			$this->custom_js .= $custom_js;
		}
	}

	public function is_edit(){
		return (
			Plugin::instance()->editor->is_edit_mode() ||
			Plugin::instance()->preview->is_preview_mode() ||
			is_preview()
		);
	}

	public function safe_path_new( $path ) {
		$path = str_replace( [ '//', '\\\\' ], [ '/', '\\' ], $path );

		return str_replace( [ '/', '\\' ], DIRECTORY_SEPARATOR, $path );
	}


	public function safe_url_new( $url ) {
		if ( is_ssl() ) {
			$url = wp_parse_url( $url );

			if ( ! empty( $url['host'] ) ) {
				$url['scheme'] = 'https';
			}

			return $this->unparse_url_new( $url );
		}

		return $url;
	}

	public function unparse_url_new( $parsed_url ) {
		$scheme   = isset( $parsed_url['scheme'] ) ? $parsed_url['scheme'] . '://' : '';
		$host     = isset( $parsed_url['host'] ) ? $parsed_url['host'] : '';
		$port     = isset( $parsed_url['port'] ) ? ':' . $parsed_url['port'] : '';
		$user     = isset( $parsed_url['user'] ) ? $parsed_url['user'] : '';
		$pass     = isset( $parsed_url['pass'] ) ? ':' . $parsed_url['pass'] : '';
		$pass     = ( $user || $pass ) ? "$pass@" : '';
		$path     = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '';
		$query    = isset( $parsed_url['query'] ) ? '?' . $parsed_url['query'] : '';
		$fragment = isset( $parsed_url['fragment'] ) ? '#' . $parsed_url['fragment'] : '';

		return "$scheme$user$pass$host$port$path$query$fragment";
	}




}

<?php

namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
use Elementor\Core\Files\CSS\Post as Post_CSS;
use Elementor\Plugin;
use Essential_Addons_Elementor\Classes\Elements_Manager;
use Essential_Addons_Elementor\Traits\Library;

class Asset_Builder {

	use Library;

	public $post_id;

	public $custom_js = '';

	public $css_strings = '';

	public $elements_manager;

	public $css_print_method = '';

	public $js_print_method = '';

	public $registered_elements;

	public $registered_extensions;

	public function __construct( $registered_elements, $registered_extensions ) {

		$this->registered_elements   = $registered_elements;
		$this->registered_extensions = $registered_extensions;
		$this->elements_manager = new Elements_Manager( $this->registered_elements, $this->registered_extensions );
		$this->css_print_method = get_option( 'elementor_css_print_method' );
		$this->js_print_method  = get_option( 'eael_js_print_method' );
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_asset_load' ] );
		add_action( 'elementor/css-file/post/enqueue', [ $this, 'post_asset_load' ], 100 );
		add_action( 'wp_footer', [ $this, 'add_inline_js' ], 100 );

	}

	public function add_inline_js(){
		wp_add_inline_script( 'eael-load-js', $this->custom_js);

		printf( '<script id="eael-inline-js">%s</script>', $this->custom_js );
		printf( '<style id="eael-inline-css">%s</style>', $this->css_strings );
	}

	public function register_script(){
		wp_register_script( 'eael-general', EAEL_PLUGIN_URL . 'assets/front-end/js/view/general.min.js', [ 'jquery' ], 10, true );
	}

	public function load_commnon_asset(){
		wp_register_style(
			'font-awesome-5-all',
			ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
			false,
			EAEL_PLUGIN_VERSION
		);

		wp_register_style(
			'font-awesome-4-shim',
			ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/v4-shims.min.css',
			false,
			EAEL_PLUGIN_VERSION
		);

		wp_register_script(
			'font-awesome-4-shim',
			ELEMENTOR_ASSETS_URL . 'lib/font-awesome/js/v4-shims.min.js',
			false,
			EAEL_PLUGIN_VERSION
		);

		// register reading progress assets
		wp_register_style(
			'eael-reading-progress',
			EAEL_PLUGIN_URL . 'assets/front-end/css/view/reading-progress.min.css',
			false,
			EAEL_PLUGIN_VERSION
		);

		wp_register_script(
			'eael-reading-progress',
			EAEL_PLUGIN_URL . 'assets/front-end/js/view/reading-progress.min.js',
			['jquery'],
			EAEL_PLUGIN_VERSION
		);

		// register Table of contents assets
		wp_register_style(
			'eael-table-of-content',
			EAEL_PLUGIN_URL . 'assets/front-end/css/view/table-of-content.min.css',
			false,
			EAEL_PLUGIN_VERSION
		);

		wp_register_script(
			'eael-table-of-content',
			EAEL_PLUGIN_URL . 'assets/front-end/js/view/table-of-content.min.js',
			['jquery'],
			EAEL_PLUGIN_VERSION
		);

		// register scroll to top assets
		wp_register_style(
			'eael-scroll-to-top',
			EAEL_PLUGIN_URL . 'assets/front-end/css/view/scroll-to-top.min.css',
			false,
			EAEL_PLUGIN_VERSION
		);

		wp_register_script(
			'eael-scroll-to-top',
			EAEL_PLUGIN_URL . 'assets/front-end/js/view/scroll-to-top.min.js',
			['jquery'],
			EAEL_PLUGIN_VERSION
		);

		// localize object
		$this->localize_objects = apply_filters( 'eael/localize_objects', [
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'nonce'          => wp_create_nonce( 'essential-addons-elementor' ),
			'i18n'           => [
				'added'   => __( 'Added ', 'essential-addons-for-elementor-lite' ),
				'compare' => __( 'Compare', 'essential-addons-for-elementor-lite' ),
				'loading' => esc_html__( 'Loading...', 'essential-addons-for-elementor-lite' )
			],
			'page_permalink' => get_the_permalink(),
		] );
	}

	public function frontend_asset_load() {
		$this->post_id = get_the_ID();
		$this->elements_manager->get_element_list( $this->post_id );
		$this->register_script();

		if ( ! $this->is_edit() ) {
			wp_enqueue_script( 'eael-general' );
		} else {
			$elements = $this->get_settings();
			if( empty( $elements ) ){
				return false;
			}
			$this->enqueue_asset( null, $elements );
		}
	}

	public function post_asset_load( Post_CSS $css ) {

		if ( $this->is_edit() ) {
			return false;
		}

		$this->post_id = $css->get_post_id();
		$this->elements_manager->get_element_list( $this->post_id );
		$elements = get_post_meta( $this->post_id, '_eael_widget_elements', true);

		if(!empty( $elements )){
			$this->enqueue_asset( $this->post_id, $elements );
		}
	}



	public function cache_asset() {

	}

	public function enqueue_asset( $post_id = null, $elements ) {
		$dynamic_asset_id = ( $post_id ? '-' . $post_id : '' );

		if ( $this->css_print_method == 'internal' ) {
			$this->css_strings .= $this->generate_strings_new( $elements, 'view', 'css' );
		} else {
			if ( ! $this->has_asset( $post_id, 'css' ) ) {
				$this->generate_script_new( $post_id, $elements, 'view', 'css' );
			}

			wp_enqueue_style(
				'eael' . $dynamic_asset_id,
				$this->safe_url( EAEL_ASSET_URL . '/' . 'eael' . $dynamic_asset_id . '.css' ),
				[ 'elementor-frontend' ],
				get_post_modified_time()
			);
		}

		if ( $this->js_print_method == 'internal' ) {
			$this->custom_js .= $this->generate_strings_new( $elements, 'view', 'js' );
		} else {
			if ( ! $this->has_asset( $post_id, 'js' ) ) {
				$this->generate_script_new( $post_id, $elements, 'view', 'js' );
			}

			wp_enqueue_script(
				'eael' .$dynamic_asset_id,
				$this->safe_url( EAEL_ASSET_URL . '/' . 'eael' . $dynamic_asset_id . '.js' ),
				[ 'eael-general' ],
				get_post_modified_time(),
				true
			);
		}

		$this->load_custom_js( $post_id );

	}

	public function has_asset( $post_id, $file = 'css' ) {
		if ( file_exists( $this->safe_path( EAEL_ASSET_PATH . '/' . 'eael' . ( $post_id ? '-' . $post_id : '' ) . '.' . $file ) ) ) {
			return true;
		}

		return false;
	}

	public function generate_script_new( $post_id, $elements, $context, $ext ) {
		// if folder not exists, create new folder
		if ( ! file_exists( EAEL_ASSET_PATH ) ) {
			wp_mkdir_p( EAEL_ASSET_PATH );
		}

		// naming asset file
		$file_name = 'eael' . ( $post_id ? '-' . $post_id : '' ) . '.' . $ext;

		// output asset string
		$output = $this->generate_strings_new( $elements, $context, $ext );

		// write to file
		$file_path = $this->safe_path( EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . $file_name );
		file_put_contents( $file_path, $output );
	}

	public function generate_strings_new( $elements, $context, $ext ) {
		$output = '';

		$paths = $this->generate_dependency_new( $elements, $context, $ext );

		if ( ! empty( $paths ) ) {
			foreach ( $paths as $path ) {
				$output .= file_get_contents( $this->safe_path( $path ) );
			}
		}

		return $output;
	}

	public function generate_dependency_new( $elements, $context, $type ) {
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

}

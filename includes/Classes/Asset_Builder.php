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

	public $localize_objects;

	public function __construct( $registered_elements, $registered_extensions ) {

		$this->registered_elements                = $registered_elements;
		$this->registered_extensions              = $registered_extensions;
		$this->elements_manager                   = new Elements_Manager( $this->registered_elements, $this->registered_extensions );
		$this->elements_manager->css_print_method = $this->css_print_method = get_option( 'elementor_css_print_method' );
		$this->elements_manager->js_print_method  = $this->js_print_method = get_option( 'eael_js_print_method' );

		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_asset_load' ] );
		add_action( 'elementor/css-file/post/enqueue', [ $this, 'post_asset_load' ] );
		add_action( 'wp_footer', [ $this, 'add_inline_js' ], 100 );
		add_action( 'wp_head', [ $this, 'add_inline_css' ], 100 );
		add_action( 'after_delete_post', [ $this, 'delete_cache_data' ] );
	}

	public function add_inline_js() {

		if ( $this->is_edit_mode() || $this->is_preview_mode() ) {
			if ( $this->custom_js ) {
				printf( '<script>%1$s</script>', 'var localize =' . wp_json_encode( $this->localize_objects ) );
				printf( '<script id="eael-inline-js">%s</script>', $this->custom_js );
			}
		}
	}

	public function add_inline_css() {
		if ( $this->is_edit_mode() || $this->is_preview_mode() ) {
			if ( $this->css_strings ) {
				printf( '<style id="eael-inline-css">%s</style>', $this->css_strings );
			}
		}
	}

	public function register_script() {
		wp_register_script( 'eael-general', EAEL_PLUGIN_URL . 'assets/front-end/js/view/general.min.js', [ 'jquery' ], 10, true );
		wp_register_style( 'eael-general', EAEL_PLUGIN_PATH . "assets/front-end/css/view/general.min.css", [ 'elementor-frontend' ], 10, true );
	}

	public function load_commnon_asset() {
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
			[ 'jquery' ],
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
			[ 'jquery' ],
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
			[ 'jquery' ],
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
		$handle        = 'eael';
		$context       = 'edit';
		$this->post_id = get_the_ID();
		$this->elements_manager->get_element_list( $this->post_id );
		$this->load_commnon_asset();
		$this->register_script();

		if ( ! $this->is_edit() ) {
			wp_enqueue_script( 'eael-general' );
			wp_enqueue_style( 'eael-general' );
			$handle = 'eael-general';
		} else {
			$elements = $this->get_settings();

			if ( empty( $elements ) ) {
				return false;
			}

			if ( $this->js_print_method == 'internal' ) {
				wp_enqueue_script( 'eael-general' );
			}

			if ( $this->css_print_method == 'internal' ) {
				wp_enqueue_style( 'eael-general' );
			}

			do_action( 'eael/before_enqueue_styles', $elements );
			do_action( 'eael/before_enqueue_scripts', $elements );

			$this->enqueue_asset( null, $elements, 'edit' );
		}
		wp_localize_script( $handle, 'localize', $this->localize_objects );
	}

	public function post_asset_load( Post_CSS $css ) {

		if ( $this->is_edit() ) {
			return false;
		}

		$this->post_id = $css->get_post_id();
		$this->elements_manager->get_element_list( $this->post_id );
		$elements = get_post_meta( $this->post_id, '_eael_widget_elements', true );

		if ( ! empty( $elements ) ) {
			do_action( 'eael/before_enqueue_styles', $elements );
			do_action( 'eael/before_enqueue_scripts', $elements );
			$this->enqueue_asset( $this->post_id, $elements );
		}
	}

	public function enqueue_asset( $post_id = null, $elements, $context = 'view' ) {
		$dynamic_asset_id = ( $post_id ? '-' . $post_id : '' );

		if ( $this->css_print_method == 'internal' ) {
			$this->css_strings .= $this->elements_manager->generate_strings( $elements, $context, 'css' );
		} else {
			if ( ! $this->has_asset( $post_id, 'css' ) ) {
				$this->elements_manager->generate_script( $post_id, $elements, $context, 'css' );
			}

			wp_enqueue_style(
				'eael' . $dynamic_asset_id,
				$this->safe_url( EAEL_ASSET_URL . '/' . 'eael' . $dynamic_asset_id . '.css' ),
				[ 'eael-general' ],
				get_post_modified_time()
			);
		}

		if ( $this->js_print_method == 'internal' ) {
			$this->custom_js .= $this->elements_manager->generate_strings( $elements, $context, 'js' );
		} else {
			if ( ! $this->has_asset( $post_id, 'js' ) ) {
				$this->elements_manager->generate_script( $post_id, $elements, $context, 'js' );
			}

			wp_enqueue_script(
				'eael' . $dynamic_asset_id,
				$this->safe_url( EAEL_ASSET_URL . '/' . 'eael' . $dynamic_asset_id . '.js' ),
				[ 'eael-general' ],
				get_post_modified_time(),
				true
			);
		}
		$this->load_custom_js( $post_id );

	}

	public function delete_cache_data( $post_id, $post ) {
		$this->elements_manager->remove_files( $post_id );

		delete_post_meta( $post_id, '_eael_custom_js' );
		delete_post_meta( $post_id, '_eael_widget_elements' );

	}

	public function has_asset( $post_id, $file = 'css' ) {
		if ( file_exists( $this->safe_path( EAEL_ASSET_PATH . '/' . 'eael' . ( $post_id ? '-' . $post_id : '' ) . '.' . $file ) ) ) {
			return true;
		}

		return false;
	}

	public function load_custom_js( $post_id ) {
		$custom_js = get_post_meta( $post_id, '_eael_custom_js', true );
		if ( $custom_js ) {
			$this->custom_js .= $custom_js;
		}
	}

	public function is_edit() {
		return (
			Plugin::instance()->editor->is_edit_mode() ||
			Plugin::instance()->preview->is_preview_mode() ||
			is_preview()
		);
	}

}

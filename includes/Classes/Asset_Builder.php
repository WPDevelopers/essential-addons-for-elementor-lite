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

	/**
	 * @theTraitAnnotation Library
	 */
	use Library;

	/**
	 * Post ID
	 * @var int
	 */
	protected $post_id;

	/**
	 * @var string
	 */
	protected $custom_js = '';

	/**
	 * @var string
	 */
	protected $css_strings = '';

	/**
	 * @var \Essential_Addons_Elementor\Classes\Elements_Manager
	 */
	protected $elements_manager;

	/**
	 * @var false|mixed|string|void
	 */
	protected $css_print_method = '';

	/**
	 * @var false|mixed|string|void
	 */
	protected $js_print_method = '';

	/**
	 * @var array
	 */
	protected $registered_elements;

	/**
	 * @var array
	 */
	protected $registered_extensions;

	/**
	 * @var object
	 */
	protected $localize_objects;

	/**
	 * @var int|int[]|mixed|string[]
	 */
	protected $custom_js_enable;

	/**
	 * @var bool
	 */
	protected $main_page;

	/**
	 * construct
	 *
	 * @param array $registered_elements
	 * @param array $registered_extensions
	 */
	public function __construct( $registered_elements, $registered_extensions ) {

		$this->registered_elements                = $registered_elements;
		$this->registered_extensions              = $registered_extensions;
		$this->elements_manager                   = new Elements_Manager( $this->registered_elements, $this->registered_extensions );
		$this->elements_manager->css_print_method = $this->css_print_method = get_option( 'elementor_css_print_method' );
		$this->elements_manager->js_print_method  = $this->js_print_method = get_option( 'eael_js_print_method' );

		$this->init_hook();

		$this->custom_js_enable = $this->get_settings( 'custom-js' );

	}

	/**
	 * init_hook
	 * Load Hook
	 */
	protected function init_hook() {
		add_action( 'wp_footer', [ $this, 'add_inline_js' ], 100 );
		add_action( 'wp_footer', [ $this, 'add_inline_css' ], 15 );
		add_action( 'after_delete_post', [ $this, 'delete_cache_data' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_asset_load' ], 100 );
		add_action( 'elementor/frontend/before_enqueue_styles', [ $this, 'ea_before_enqueue_styles' ] );
		add_action( 'elementor/theme/register_locations', [ $this, 'load_asset_per_location' ], 20 );
		add_filter( 'elementor/files/file_name', [ $this, 'load_asset_per_file' ] );
	}

	/**
	 * frontend_asset_load
	 * Load asset as per condition
	 * @return false|void
	 */
	public function frontend_asset_load() {
		$handle        = 'eael-general';
		$this->post_id = get_the_ID();

		$this->elements_manager->get_element_list( $this->post_id );
		$this->load_commnon_asset();
		$this->register_script();

		if ( ! $this->is_edit() ) {
			wp_enqueue_script( 'eael-general' );
			wp_enqueue_style( 'eael-general' );
			$this->load_custom_js( $this->post_id );
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
			$this->load_custom_js( $this->post_id );
		}

		wp_localize_script( $handle, 'localize', $this->localize_objects );
	}

	/**
	 * ea_before_enqueue_styles
	 * @return false|void
	 */
	public function ea_before_enqueue_styles() {

		if ( $this->is_edit() ) {
			return false;
		}

		$this->post_id = get_the_ID();
		$this->set_main_page( $this->post_id );
		$this->elements_manager->get_element_list( $this->post_id );
		$elements = get_post_meta( $this->post_id, '_eael_widget_elements', true );

		if ( ! empty( $elements ) ) {
			$this->enqueue_asset( $this->post_id, $elements );
		}

		if ( ! $this->main_page ) {
			$this->load_custom_js( $this->post_id );
		}
	}

	/**
	 * load_asset_per_location
	 *
	 * @param $instance
	 *
	 * @return false|void
	 */
	public function load_asset_per_location( $instance ) {

		if ( is_admin() || ! ( class_exists( 'ElementorPro\Modules\ThemeBuilder\Module' ) ) ) {
			return false;
		}

		$locations = $instance->get_locations();

		foreach ( $locations as $location => $settings ) {

			$documents = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( $location );
			foreach ( $documents as $document ) {
				$post_id = $document->get_post()->ID;

				$this->post_id = $post_id;
				$this->set_main_page( $this->post_id );
				$this->elements_manager->get_element_list( $this->post_id );
				$elements = get_post_meta( $this->post_id, '_eael_widget_elements', true );

				if ( ! empty( $elements ) ) {
					do_action( 'eael/before_enqueue_styles', $elements );
					do_action( 'eael/before_enqueue_scripts', $elements );
					$this->enqueue_asset( $this->post_id, $elements );
				}

				if ( ! $this->main_page ) {
					$this->load_custom_js( $this->post_id );
				}
			}
		}
	}

	/**
	 * load_asset_per_file
	 * @param $file_name
	 *
	 * @return mixed
	 */
	public function load_asset_per_file( $file_name ) {

		if( empty( $file_name ) ){
			return $file_name;
		}

		$post_id  = preg_replace( '/[^0-9]/', '', $file_name );

		if ( $post_id < 1 ) {
			return $file_name;
		}

		$this->post_id = $post_id;
		$type = get_post_meta( $this->post_id, '_elementor_template_type', true );
		$template_list = ['popup'];

		$this->set_main_page( $this->post_id );
		$this->elements_manager->get_element_list( $this->post_id );
		$elements = get_post_meta( $this->post_id, '_eael_widget_elements', true );

		if ( ! empty( $elements ) ) {
			do_action( 'eael/before_enqueue_styles', $elements );
			do_action( 'eael/before_enqueue_scripts', $elements );
			$this->enqueue_asset( $this->post_id, $elements );
		}

		if ( ! $this->main_page ) {
			$this->load_custom_js( $this->post_id );
		}

		return $file_name;
	}

	/**
	 * add_inline_js
	 * Load inline js data
	 */
	public function add_inline_js() {

		if ( $this->is_edit_mode() || $this->is_preview_mode() ) {
			if ( $this->custom_js ) {
				printf( '<script>%1$s</script>', 'var localize =' . wp_json_encode( $this->localize_objects ) );
				printf( '<script id="eael-inline-js">%s</script>', $this->custom_js );
			}
		}
	}

	/**
	 * add_inline_css
	 * Load inline css file
	 */
	public function add_inline_css() {
		if ( $this->is_edit_mode() || $this->is_preview_mode() ) {
			if ( $this->css_strings ) {
				printf( '<style id="eael-inline-css">%s</style>', $this->css_strings );
			}
		}
	}

	public function register_script() {
		$css_deps   = [ 'elementor-frontend' ];
		$js_deps    = [ 'jquery' ];
		$theme      = wp_get_theme(); // gets the current theme
		$theme_data = $theme->parent() ? $theme->parent() : $theme;
		if ( 'Hello Elementor' === $theme_data->name && version_compare( $theme_data->Version, '2.1.0', '>=' ) && wp_style_is( 'hello-elementor-theme-style', 'registered' ) ) {
			array_unshift( $css_deps, 'hello-elementor-theme-style' );
		} elseif ( in_array( 'Astra', [ $theme->name, $theme->parent_theme ] ) && wp_style_is( 'astra-theme-css', 'registered' ) ) {
			array_unshift( $css_deps, 'astra-theme-css' );
		} elseif ( in_array( 'XStore', [ $theme->name, $theme->parent_theme ] ) ) {
			$js_deps[] = 'etheme';
		}

		if ( class_exists( 'Cartflows_Loader' ) && wcf()->utils->is_step_post_type() ) {
			$css_deps = [ 'elementor-frontend' ];
		}

		wp_register_script( 'eael-general', EAEL_PLUGIN_URL . 'assets/front-end/js/view/general.min.js', $js_deps, EAEL_PLUGIN_VERSION, true );
		wp_register_style( 'eael-general', EAEL_PLUGIN_URL . "assets/front-end/css/view/general.min.css", $css_deps, EAEL_PLUGIN_VERSION );
	}

	/**
	 * load_common_asset
	 * Load common asset file
	 */
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
			'ajaxurl'            => admin_url( 'admin-ajax.php' ),
			'nonce'              => wp_create_nonce( 'essential-addons-elementor' ),
			'i18n'               => [
				'added'   => __( 'Added ', 'essential-addons-for-elementor-lite' ),
				'compare' => __( 'Compare', 'essential-addons-for-elementor-lite' ),
				'loading' => esc_html__( 'Loading...', 'essential-addons-for-elementor-lite' )
			],
			'eael_translate_text' => [
				'required_text' => esc_html__( 'is a required field', 'essential-addons-for-elementor-lite' ),
				'invalid_text'  => esc_html__( 'Invalid', 'essential-addons-for-elementor-lite' ),
				'billing_text'  => esc_html__( 'Billing', 'essential-addons-for-elementor-lite' ),
				'shipping_text' => esc_html__( 'Shipping', 'essential-addons-for-elementor-lite' ),
                'fg_mfp_counter_text' => apply_filters( 'eael/filterble-gallery/mfp-counter-text', __( 'of', 'essential-addons-for-elementor-lite' ) ),
			],
			'page_permalink'     => get_the_permalink(),
			'cart_redirectition' => get_option( 'woocommerce_cart_redirect_after_add' ),
			'cart_page_url'      => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '',
			'el_breakpoints'     => method_exists( Plugin::$instance->breakpoints, 'get_breakpoints_config' ) ? Plugin::$instance->breakpoints->get_breakpoints_config() : '',
		] );
	}





	/**
	 * enqueue_asset
	 *
	 * @param int $post_id
	 * @param array $elements
	 * @param string $context
	 */
	public function enqueue_asset( $post_id = null, $elements = [], $context = 'view' ) {
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
	}

	/**
	 * delete_cache_data
	 *
	 * @param int $post_id
	 */
	public function delete_cache_data( $post_id ) {
		$this->elements_manager->remove_files( $post_id );

		delete_post_meta( $post_id, '_eael_custom_js' );
		delete_post_meta( $post_id, '_eael_widget_elements' );
	}

	/**
	 * has_asset
	 *
	 * @param int $post_id
	 * @param string $file
	 *
	 * @return bool
	 */
	public function has_asset( $post_id, $file = 'css' ) {
		if ( file_exists( $this->safe_path( EAEL_ASSET_PATH . '/' . 'eael' . ( $post_id ? '-' . $post_id : '' ) . '.' . $file ) ) ) {
			return true;
		}

		return false;
	}

	public function load_custom_js( $post_id ) {
		static $post_ids_array = [];

		if ( in_array( $post_id, $post_ids_array ) ) {
			return false;
		}

		$post_ids_array[] = $post_id;

		if ( ! $this->custom_js_enable ) {
			return false;
		}

		$custom_js = get_post_meta( $post_id, '_eael_custom_js', true );
		if ( $custom_js ) {
			// add semicolon if someone misses adding this in custom js code .
			$this->custom_js .= $custom_js.';';
		}
	}

	/**
	 * is_edit
	 * check is edit page
	 * @return bool
	 */
	public function is_edit() {
		return (
			Plugin::instance()->editor->is_edit_mode() ||
			Plugin::instance()->preview->is_preview_mode() ||
			is_preview()
		);
	}

	/**
	 * set_main_page
	 *
	 * @param $post_id
	 */
	protected function set_main_page( $post_id ) {
		$this->main_page = get_post_meta( $post_id, '_elementor_template_type', true ) == 'wp-page';
	}

}

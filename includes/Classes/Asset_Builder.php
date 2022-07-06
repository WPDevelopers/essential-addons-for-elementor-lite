<?php
/**
 * Asset_Builder
 *
 * @package Essential_Addons_Elementor
 * @since   1.0.0
 */

namespace Essential_Addons_Elementor\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
use Elementor\Core\Files\CSS\Post as Post_CSS;
use Elementor\Plugin;
use Essential_Addons_Elementor\Classes\Elements_Manager;
use Essential_Addons_Elementor\Traits\Library;

/**
 * Asset Builder Class.
 *
 * @class Asset_Builder
 */
class Asset_Builder {

	/**
	 * Trait Library.
	 *
	 * @theTraitAnnotation Library
	 */
	use Library;

	/**
	 * Post ID.
	 *
	 * @var int
	 */
	protected $post_id;

	/**
	 * Keep inline js.
	 *
	 * @var string
	 */
	protected $custom_js = '';

	/**
	 * Keep inline js.
	 *
	 * @var string
	 */
	protected $css_strings = '';

	/**
	 * Manage Elements.
	 *
	 * @var \Essential_Addons_Elementor\Classes\Elements_Manager
	 */
	protected $elements_manager;

	/**
	 * CSS Print Method.
	 *
	 * @var false|mixed|string|void
	 */
	protected $css_print_method = '';

	/**
	 * JS Print Method.
	 *
	 * @var false|mixed|string|void
	 */
	protected $js_print_method = '';

	/**
	 * Registered Elements.
	 *
	 * @var array
	 */
	protected $registered_elements;

	/**
	 * Registered Extensions.
	 *
	 * @var array
	 */
	protected $registered_extensions;

	/**
	 * Localize Objects.
	 *
	 * @var object
	 */
	protected $localize_objects;

	/**
	 * Custom JS Enable.
	 *
	 * @var int|int[]|mixed|string[]
	 */
	protected $custom_js_enable;

	/**
	 * Main Page.
	 *
	 * @var bool
	 */
	protected $main_page;

	/**
	 * Construct.
	 *
	 * @param array $registered_elements EA widget list.
	 * @param array $registered_extensions EA extension list.
	 */
	public function __construct( $registered_elements, $registered_extensions ) {

		$this->registered_elements                = $registered_elements;
		$this->registered_extensions              = $registered_extensions;
		$this->elements_manager                   = new Elements_Manager( $this->registered_elements, $this->registered_extensions );
		$this->css_print_method                   = get_option( 'elementor_css_print_method' );
		$this->js_print_method                    = get_option( 'eael_js_print_method' );
		$this->elements_manager->css_print_method = $this->css_print_method;
		$this->elements_manager->js_print_method  = $this->js_print_method;
		$this->init_hook();

		$this->custom_js_enable = $this->get_settings( 'custom-js' );

	}

	/**
	 * Init_hook.
	 * Load Hook.
	 */
	protected function init_hook() {
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_asset_load' ) );
		add_action( 'elementor/frontend/before_enqueue_styles', array( $this, 'ea_before_enqueue_styles' ) );
		add_action( 'elementor/theme/register_locations', array( $this, 'post_asset_load' ), 100 );
		add_action( 'wp_footer', array( $this, 'add_inline_js' ), 100 );
		add_action( 'wp_footer', array( $this, 'add_inline_css' ), 15 );
		add_action( 'after_delete_post', array( $this, 'delete_cache_data' ), 10, 2 );
	}

	/**
	 * Load inline js data.
	 * Add_inline_js
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
	 * Load inline css file.
	 * add_inline_css
	 */
	public function add_inline_css() {
		if ( $this->is_edit_mode() || $this->is_preview_mode() ) {
			if ( $this->css_strings ) {
				printf( '<style id="eael-inline-css">%s</style>', $this->css_strings );
			}
		}
	}

	/**
	 * Register general script.
	 * register_script
	 */
	public function register_script() {
		wp_register_script( 'eael-general', EAEL_PLUGIN_URL . 'assets/front-end/js/view/general.min.js', array( 'jquery' ), 10, true );
		wp_register_style( 'eael-general', EAEL_PLUGIN_PATH . 'assets/front-end/css/view/general.min.css', array( 'elementor-frontend' ), 10, true );
	}

	/**
	 * Load common asset file.
	 * load_common_asset
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

		// register reading progress assets.
		wp_register_style(
			'eael-reading-progress',
			EAEL_PLUGIN_URL . 'assets/front-end/css/view/reading-progress.min.css',
			false,
			EAEL_PLUGIN_VERSION
		);

		wp_register_script(
			'eael-reading-progress',
			EAEL_PLUGIN_URL . 'assets/front-end/js/view/reading-progress.min.js',
			array( 'jquery' ),
			EAEL_PLUGIN_VERSION
		);

		// register Table of contents assets.
		wp_register_style(
			'eael-table-of-content',
			EAEL_PLUGIN_URL . 'assets/front-end/css/view/table-of-content.min.css',
			false,
			EAEL_PLUGIN_VERSION
		);

		wp_register_script(
			'eael-table-of-content',
			EAEL_PLUGIN_URL . 'assets/front-end/js/view/table-of-content.min.js',
			array( 'jquery' ),
			EAEL_PLUGIN_VERSION
		);

		// register scroll to top assets.
		wp_register_style(
			'eael-scroll-to-top',
			EAEL_PLUGIN_URL . 'assets/front-end/css/view/scroll-to-top.min.css',
			false,
			EAEL_PLUGIN_VERSION
		);

		wp_register_script(
			'eael-scroll-to-top',
			EAEL_PLUGIN_URL . 'assets/front-end/js/view/scroll-to-top.min.js',
			array( 'jquery' ),
			EAEL_PLUGIN_VERSION
		);

		// localize object.
		$this->localize_objects = apply_filters(
			'eael/localize_objects',
			array(
				'ajaxurl'        => admin_url( 'admin-ajax.php' ),
				'nonce'          => wp_create_nonce( 'essential-addons-elementor' ),
				'i18n'           => array(
					'added'   => __( 'Added ', 'essential-addons-for-elementor-lite' ),
					'compare' => __( 'Compare', 'essential-addons-for-elementor-lite' ),
					'loading' => esc_html__( 'Loading...', 'essential-addons-for-elementor-lite' ),
				),
				'page_permalink' => get_the_permalink(),
			)
		);
	}

	/**
	 * Load asset as per condition.
	 * frontend_asset_load
	 *
	 * @return false|void
	 */
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
			$this->load_custom_js( $this->post_id );
		} else {
			$elements = $this->get_settings();

			if ( empty( $elements ) ) {
				return false;
			}

			if ( 'internal' === $this->js_print_method ) {
				wp_enqueue_script( 'eael-general' );
			}

			if ( 'internal' === $this->css_print_method ) {
				wp_enqueue_style( 'eael-general' );
			}

			do_action( 'eael/before_enqueue_styles', $elements );
			do_action( 'eael/before_enqueue_scripts', $elements );

			$this->enqueue_asset( null, $elements, 'edit' );
		}

		wp_localize_script( $handle, 'localize', $this->localize_objects );
	}

	/**
	 * Load EA asset before elementor asset loading.
	 * ea_before_enqueue_styles
	 *
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
			do_action( 'eael/before_enqueue_styles', $elements );
			do_action( 'eael/before_enqueue_scripts', $elements );
			$this->enqueue_asset( $this->post_id, $elements );
		}

		if ( ! $this->main_page ) {
			$this->load_custom_js( $this->post_id );
		}
	}

	/**
	 * Load asset for each post ID.
	 * post_asset_load
	 *
	 * @param object $instance Elementor PRO location class.
	 */
	public function post_asset_load( $instance ) {
		$locations = $instance->get_locations();
		foreach ( $locations as $location => $settings ) {
			$documents = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( $location );
			foreach ( $documents as $document ) {
				$post_id       = $document->get_post()->ID;
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
	 * Enqueue Asset.
	 * enqueue_asset
	 *
	 * @param int    $post_id post ID.
	 * @param array  $elements Widget list.
	 * @param string $context Request fields.
	 */
	public function enqueue_asset( $post_id = null, $elements, $context = 'view' ) {
		$dynamic_asset_id = ( $post_id ? '-' . $post_id : '' );

		if ( 'internal' === $this->css_print_method ) {
			$this->css_strings .= $this->elements_manager->generate_strings( $elements, $context, 'css' );
		} else {
			if ( ! $this->has_asset( $post_id, 'css' ) ) {
				$this->elements_manager->generate_script( $post_id, $elements, $context, 'css' );
			}

			wp_enqueue_style(
				'eael' . $dynamic_asset_id,
				$this->safe_url( EAEL_ASSET_URL . '/' . 'eael' . $dynamic_asset_id . '.css' ),
				array( 'eael-general' ),
				get_post_modified_time()
			);
		}

		if ( 'internal' === $this->js_print_method ) {
			$this->custom_js .= $this->elements_manager->generate_strings( $elements, $context, 'js' );
		} else {
			if ( ! $this->has_asset( $post_id, 'js' ) ) {
				$this->elements_manager->generate_script( $post_id, $elements, $context, 'js' );
			}

			wp_enqueue_script(
				'eael' . $dynamic_asset_id,
				$this->safe_url( EAEL_ASSET_URL . '/' . 'eael' . $dynamic_asset_id . '.js' ),
				array( 'eael-general' ),
				get_post_modified_time(),
				true
			);
		}
	}

	/**
	 * Delete Asset.
	 * delete_cache_data
	 *
	 * @param int   $post_id post ID.
	 * @param array $post post object.
	 */
	public function delete_cache_data( $post_id, $post ) {
		$this->elements_manager->remove_files( $post_id );

		delete_post_meta( $post_id, '_eael_custom_js' );
		delete_post_meta( $post_id, '_eael_widget_elements' );

	}

	/**
	 * Check asset availability.
	 * has_asset
	 *
	 * @param int    $post_id post ID.
	 * @param string $file file name .
	 *
	 * @return bool
	 */
	public function has_asset( $post_id, $file = 'css' ) {
		if ( file_exists( $this->safe_path( EAEL_ASSET_PATH . '/' . 'eael' . ( $post_id ? '-' . $post_id : '' ) . '.' . $file ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Load JS.
	 * load_custom_js
	 *
	 * @param int $post_id post ID.
	 *
	 * @return false|void
	 */
	public function load_custom_js( $post_id ) {

		if ( ! $this->custom_js_enable ) {
			return false;
		}

		$custom_js = get_post_meta( $post_id, '_eael_custom_js', true );
		if ( $custom_js ) {
			$this->custom_js .= $custom_js;
		}
	}

	/**
	 * Check is edit page.
	 * is_edit
	 *
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
	 * Set Main Page.
	 * set_main_page
	 *
	 * @param int $post_id post ID.
	 */
	protected function set_main_page( $post_id ) {
		$this->main_page = 'wp-page' === get_post_meta( $post_id, '_elementor_template_type', true );
	}

}

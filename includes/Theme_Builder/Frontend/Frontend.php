<?php
/**
 * Front-end render engine.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Frontend;

use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;
use Essential_Addons_Elementor\Theme_Builder\Core\Template_Types;
use Essential_Addons_Elementor\Theme_Builder\Renderers\Template_Renderer;
use Essential_Addons_Elementor\Theme_Builder\Theme_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Decides where Theme Builder templates are injected into the page.
 *
 * Three render modes are supported:
 *
 * - `replace` (classic themes, default) — the theme's `header.php` / `footer.php`
 *   are swapped out for the matched templates.
 * - `hooks` (block themes) — templates are injected at `wp_body_open` and
 *   `wp_footer` without touching the theme's own markup.
 * - `theme` — the theme declares `add_theme_support( 'ea-theme-builder' )` and
 *   calls `do_action( 'eael/theme_builder/render', 'header' )` wherever it wants
 *   the template to appear.
 *
 * @since 6.7.3
 */
class Frontend {

	/**
	 * Template IDs resolved for this request, keyed by type slug.
	 *
	 * @var array
	 */
	private $active = [];

	/**
	 * Guard against a theme (or plugin) firing `get_header` twice.
	 *
	 * @var bool
	 */
	private $header_overridden = false;

	/**
	 * Guard against a theme (or plugin) firing `get_footer` twice.
	 *
	 * @var bool
	 */
	private $footer_overridden = false;

	/**
	 * Register the front-end hooks.
	 *
	 * @since 6.7.3
	 */
	public function __construct() {
		// Conditional tags are only reliable from `template_redirect` onwards, and
		// it still runs before the theme template — and therefore before wp_head.
		add_action( 'template_redirect', [ $this, 'setup' ], 5 );

		// Manual render entry point for themes and shortcodes:
		// do_action( 'eael/theme_builder/render', 'header' );
		add_action( 'eael/theme_builder/render', [ $this, 'render_type' ] );
	}

	/**
	 * Resolve the templates for this request and hook the renderer accordingly.
	 *
	 * @since 6.7.3
	 */
	public function setup() {
		if ( ! $this->should_render() ) {
			return;
		}

		$this->collect_active_templates();

		if ( empty( $this->active ) ) {
			return;
		}

		// Announce the templates before Elementor enqueues its styles at priority
		// 20, so anything that builds stylesheets from the rendered-post list sees
		// them. See register_template_assets().
		add_action( 'wp_enqueue_scripts', [ $this, 'register_template_assets' ], 9 );

		// Enqueue late so Elementor and Essential Addons have registered their
		// handles, but still inside wp_head so nothing lands in the footer.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ], 101 );

		$mode = $this->get_render_mode();

		if ( 'theme' === $mode ) {
			return;
		}

		$has_header = $this->has_location( 'header' );
		$has_footer = $this->has_location( 'footer' );

		if ( 'replace' === $mode ) {
			if ( $has_header ) {
				// Our replacement document head must be able to print a <title>.
				if ( ! current_theme_supports( 'title-tag' ) ) {
					add_theme_support( 'title-tag' );
				}

				add_action( 'get_header', [ $this, 'override_header' ], 100, 2 );
			}

			if ( $has_footer ) {
				add_action( 'get_footer', [ $this, 'override_footer' ], 100, 2 );
			}

			return;
		}

		// `hooks` mode — inject alongside the theme's own header and footer.
		if ( $has_header ) {
			add_action( 'wp_body_open', [ $this, 'render_header_location' ], 0 );
		}

		if ( $has_footer ) {
			add_action( 'wp_footer', [ $this, 'render_footer_location' ], 5 );
		}
	}

	/**
	 * Whether Theme Builder templates may render on this request.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public function should_render() {
		$should = true;

		if ( is_feed() || is_embed() || is_robots() || is_trackback() ) {
			$should = false;
		}

		// A template must never render inside its own editor preview.
		if ( $should && is_singular( Post_Type::CPT ) ) {
			$should = false;
		}

		if ( $should && class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			$should = false;
		}

		if ( $should && function_exists( 'wp_is_json_request' ) && wp_is_json_request() ) {
			$should = false;
		}

		/**
		 * Filters whether Theme Builder templates render on the current request.
		 *
		 * @since 6.7.3
		 *
		 * @param bool $should Whether to render.
		 */
		return (bool) apply_filters( 'eael/theme_builder/should_render', $should );
	}

	/**
	 * Resolve one template per registered type.
	 *
	 * @since 6.7.3
	 */
	private function collect_active_templates() {
		foreach ( Template_Types::instance()->get_types() as $slug => $type ) {
			$template_id = Template_Renderer::get_template_id( $slug );

			if ( $template_id ) {
				$this->active[ $slug ] = $template_id;
			}
		}
	}

	/**
	 * Whether any resolved template renders at the given location.
	 *
	 * @since 6.7.3
	 *
	 * @param string $location `header`, `footer` or `content`.
	 *
	 * @return bool
	 */
	private function has_location( $location ) {
		foreach ( Template_Types::instance()->get_types_by_location( $location ) as $slug => $type ) {
			if ( ! empty( $this->active[ $slug ] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * The render mode for this site.
	 *
	 * @since 6.7.3
	 *
	 * @return string `replace`, `hooks` or `theme`.
	 */
	public function get_render_mode() {
		if ( current_theme_supports( 'ea-theme-builder' ) ) {
			$mode = 'theme';
		} elseif ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
			// Block themes never call get_header()/get_footer(), so there is
			// nothing to replace — inject instead.
			$mode = 'hooks';
		} else {
			$mode = 'replace';
		}

		/**
		 * Filters the Theme Builder render mode.
		 *
		 * @since 6.7.3
		 *
		 * @param string $mode `replace`, `hooks` or `theme`.
		 */
		$mode = apply_filters( 'eael/theme_builder/render_mode', $mode );

		return in_array( $mode, [ 'replace', 'hooks', 'theme' ], true ) ? $mode : 'replace';
	}

	/**
	 * Register the resolved templates with Elementor's rendering pipeline.
	 *
	 * Elementor announces the post it is about to render with `elementor/post/render`
	 * and turns on that post's conditionally-loaded assets — but only for the
	 * *current* post, and only when `is_singular()`
	 * (see `Frontend::enqueue_styles()` in Elementor).
	 *
	 * A Theme Builder template is neither: it renders on pages that know nothing
	 * about it, and often on non-singular views. Without this, anything Elementor
	 * builds from the rendered-post list is skipped for the template. The visible
	 * symptom is a header that lays out correctly on a single page but collapses
	 * on the blog index or an archive, because `Atomic_Styles_Manager` bails when
	 * no post was announced and never emits the atomic widgets' shared
	 * `base-desktop.css` / `base-mobile.css`.
	 *
	 * @since 6.7.3
	 */
	public function register_template_assets() {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		foreach ( $this->active as $template_id ) {
			/** This action is documented in elementor/includes/frontend.php */
			do_action( 'elementor/post/render', $template_id );

			$this->enable_conditional_assets( $template_id );
		}
	}

	/**
	 * Turn on the conditionally-loaded assets a template's widgets declared.
	 *
	 * Mirrors Elementor's own private `Frontend::handle_page_assets()`.
	 *
	 * @since 6.7.3
	 *
	 * @param int $template_id Template ID.
	 */
	private function enable_conditional_assets( $template_id ) {
		$plugin = \Elementor\Plugin::$instance;

		// Elementor\Core\Base\Elements_Iteration_Actions\Assets::ASSETS_META_KEY.
		$page_assets = get_post_meta( $template_id, '_elementor_page_assets', true );

		if ( ! empty( $page_assets ) && isset( $plugin->assets_loader ) && method_exists( $plugin->assets_loader, 'enable_assets' ) ) {
			$plugin->assets_loader->enable_assets( $page_assets );

			return;
		}

		// No manifest cached yet — let the document build one.
		$document = $plugin->documents->get( $template_id );

		if ( $document && method_exists( $document, 'update_runtime_elements' ) ) {
			$document->update_runtime_elements();
		}
	}

	/**
	 * Enqueue everything the resolved templates need.
	 *
	 * Creating the Elementor CSS file object also fires `elementor/files/file_name`,
	 * which is how Essential Addons' Asset_Builder discovers the widgets used
	 * inside the template and loads their CSS/JS.
	 *
	 * @since 6.7.3
	 */
	public function enqueue_assets() {
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}

		$frontend = \Elementor\Plugin::$instance->frontend;

		// Elementor only auto-enqueues its frontend styles on singular views built
		// with Elementor; a template rendering on an archive would otherwise get
		// no base styles at all. The method guards itself against running twice.
		if ( $frontend && method_exists( $frontend, 'enqueue_styles' ) ) {
			$frontend->enqueue_styles();
		}

		foreach ( $this->active as $template_id ) {
			$css_file = $this->create_css_file( $template_id );

			if ( $css_file ) {
				$css_file->enqueue();
			}
		}

		/**
		 * Fires after the Theme Builder templates' assets are enqueued.
		 *
		 * @since 6.7.3
		 *
		 * @param array $active Resolved template IDs keyed by type slug.
		 */
		do_action( 'eael/theme_builder/enqueue_assets', $this->active );
	}

	/**
	 * Build the Elementor CSS file object for a template.
	 *
	 * @since 6.7.3
	 *
	 * @param int $template_id Template ID.
	 *
	 * @return \Elementor\Core\Files\CSS\Post|null
	 */
	private function create_css_file( $template_id ) {
		$class = '\Elementor\Core\Files\CSS\Post';

		if ( ! class_exists( $class ) ) {
			return null;
		}

		if ( method_exists( $class, 'create' ) ) {
			return call_user_func( [ $class, 'create' ], $template_id );
		}

		return new \Elementor\Core\Files\CSS\Post( $template_id );
	}

	/**
	 * Replace the theme's header with the matched template.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name Optional header name passed to get_header().
	 * @param array  $args Optional arguments passed to get_header().
	 */
	public function override_header( $name = '', $args = [] ) {
		if ( $this->header_overridden ) {
			return;
		}

		$this->header_overridden = true;

		// Another header override — a second builder plugin, or a theme that
		// prints its head before `get_header` — has already opened the document.
		// Emitting a second doctype/<head>/<body> would corrupt the page, so
		// print the template markup on its own.
		if ( did_action( 'wp_head' ) ) {
			$this->render_location( 'header' );

			return;
		}

		// Emits the document head, opens <body> and prints the header template.
		include Theme_Builder::path() . 'Templates/header.php';

		// The head has already been printed; make sure loading the theme's own
		// header below cannot fire those callbacks a second time.
		remove_all_actions( 'wp_head' );
		remove_all_actions( 'wp_body_open' );

		$this->discard_theme_template( 'header', $name );
	}

	/**
	 * Replace the theme's footer with the matched template.
	 *
	 * @since 6.7.3
	 *
	 * @param string $name Optional footer name passed to get_footer().
	 * @param array  $args Optional arguments passed to get_footer().
	 */
	public function override_footer( $name = '', $args = [] ) {
		if ( $this->footer_overridden ) {
			return;
		}

		$this->footer_overridden = true;

		// Another footer override already closed the document — see override_header().
		if ( did_action( 'wp_footer' ) ) {
			$this->render_location( 'footer' );

			return;
		}

		// Prints the footer template, calls wp_footer() and closes the document.
		include Theme_Builder::path() . 'Templates/footer.php';

		remove_all_actions( 'wp_footer' );

		$this->discard_theme_template( 'footer', $name );
	}

	/**
	 * Swallow the theme's own header/footer template.
	 *
	 * `locate_template()` loads with `require_once`, so pre-loading the file into
	 * a discarded buffer here makes the call WordPress issues immediately after
	 * this hook a silent no-op.
	 *
	 * @since 6.7.3
	 *
	 * @param string $slug `header` or `footer`.
	 * @param string $name Optional template name.
	 */
	private function discard_theme_template( $slug, $name = '' ) {
		$templates = [];
		$name      = (string) $name;

		if ( '' !== $name ) {
			$templates[] = "{$slug}-{$name}.php";
		}

		$templates[] = "{$slug}.php";

		ob_start();
		locate_template( $templates, true );
		ob_get_clean();
	}

	/**
	 * Print every template registered at the header location.
	 *
	 * @since 6.7.3
	 */
	public function render_header_location() {
		$this->render_location( 'header' );
	}

	/**
	 * Print every template registered at the footer location.
	 *
	 * @since 6.7.3
	 */
	public function render_footer_location() {
		$this->render_location( 'footer' );
	}

	/**
	 * Print every resolved template of a location.
	 *
	 * @since 6.7.3
	 *
	 * @param string $location Location slug.
	 */
	public function render_location( $location ) {
		foreach ( Template_Types::instance()->get_types_by_location( $location ) as $slug => $type ) {
			if ( empty( $this->active[ $slug ] ) ) {
				continue;
			}

			$this->render_type( $slug );
		}
	}

	/**
	 * Print a single template type.
	 *
	 * @since 6.7.3
	 *
	 * @param string $type Template type slug.
	 */
	public function render_type( $type ) {
		/**
		 * Fires before a Theme Builder template is printed.
		 *
		 * @since 6.7.3
		 *
		 * @param string $type Template type slug.
		 */
		do_action( 'eael/theme_builder/before_render', $type );

		Template_Renderer::render( $type );

		/**
		 * Fires after a Theme Builder template is printed.
		 *
		 * @since 6.7.3
		 *
		 * @param string $type Template type slug.
		 */
		do_action( 'eael/theme_builder/after_render', $type );
	}
}

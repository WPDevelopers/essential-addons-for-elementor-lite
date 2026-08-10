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
 * - `hooks` (block themes) — the theme's `core/template-part` blocks in the
 *   header and footer areas are swapped out for the matched templates, falling
 *   back to injection at `wp_body_open` / `wp_footer` when the resolved block
 *   template has no such part.
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
	 * Resolved `wp_template_part` areas, keyed by `theme//slug`.
	 *
	 * @var array
	 */
	private $part_areas = [];

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

		// `hooks` mode. A block theme renders its header and footer as
		// `core/template-part` blocks, so the template takes the part's place in
		// the document — otherwise both would render, one under the other.
		if ( ( $has_header || $has_footer ) && $this->replaces_block_template_parts() ) {
			add_filter( 'pre_render_block', [ $this, 'maybe_replace_template_part' ], 10, 2 );

			// The block template is resolved by the time `template_include` runs,
			// and that is still before `wp_body_open` — the last moment a header
			// fallback can be hooked for a template that has no header part.
			add_filter( 'template_include', [ $this, 'prepare_block_template_fallback' ], PHP_INT_MAX );

			// Rendering inside `.wp-site-blocks` means inheriting the spacing the
			// block theme reserves there. See enqueue_block_theme_spacing_reset().
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_block_theme_spacing_reset' ], 102 );
		} elseif ( $has_header ) {
			add_action( 'wp_body_open', [ $this, 'render_header_location' ], 0 );
		}

		// Always safe to hook: the renderer prints a `single` type only once, so
		// this is a no-op when the footer template part was already replaced.
		if ( $has_footer ) {
			add_action( 'wp_footer', [ $this, 'render_footer_location' ], 5 );
		}
	}

	/**
	 * Whether the theme's header/footer block template parts get replaced.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	private function replaces_block_template_parts() {
		$replace = function_exists( 'wp_is_block_theme' ) && wp_is_block_theme();

		/**
		 * Filters whether Theme Builder replaces the theme's header/footer block
		 * template parts.
		 *
		 * Turning this off makes templates inject at `wp_body_open` / `wp_footer`
		 * and leaves the theme's own parts rendering alongside them.
		 *
		 * @since 6.7.3
		 *
		 * @param bool $replace Whether to replace the theme's template parts.
		 */
		return (bool) apply_filters( 'eael/theme_builder/replace_block_template_parts', $replace );
	}

	/**
	 * Undo the spacing a block theme reserves around `.wp-site-blocks`.
	 *
	 * A block theme with `useRootPaddingAwareAlignments` makes WordPress emit
	 * (`wp-includes/class-wp-theme-json.php`):
	 *
	 *     .wp-site-blocks { padding-top: var(--wp--style--root--padding-top);
	 *                       padding-bottom: var(--wp--style--root--padding-bottom); }
	 *     :where(.wp-site-blocks) > * { margin-block-start: <block gap>; }
	 *
	 * The theme's own header and footer template parts sit inside that padding by
	 * design. A Theme Builder header or footer takes their slot, so it inherits
	 * the same reserved space — which shows up as a strip of page background above
	 * a full-bleed header and below a full-bleed footer, with nothing in Elementor
	 * that can remove it. Twenty Twenty-Three is the obvious example: its root
	 * padding is `var(--wp--preset--spacing--40)` top and bottom. Themes that set
	 * only left/right padding (Twenty Twenty-Four, Twenty Twenty-Five) leave the
	 * custom property undefined, so the `0px` fallback makes these rules a no-op.
	 *
	 * Horizontal padding needs no handling: it lands on the first `.has-global-padding`
	 * container, and `.wp-site-blocks` is not one.
	 *
	 * @since 6.7.3
	 */
	public function enqueue_block_theme_spacing_reset() {
		/**
		 * Filters whether the theme's root spacing is neutralized around Theme
		 * Builder templates that replaced a block template part.
		 *
		 * Return false to keep the gap — useful for a theme whose root padding is
		 * part of a deliberate framed layout.
		 *
		 * @since 6.7.3
		 *
		 * @param bool $reset Whether to emit the reset stylesheet.
		 */
		if ( ! apply_filters( 'eael/theme_builder/block_theme_spacing_reset', true ) ) {
			return;
		}

		$header = $this->location_selectors( 'header' );
		$footer = $this->location_selectors( 'footer' );

		if ( empty( $header ) && empty( $footer ) ) {
			return;
		}

		$rules = [];

		// The block gap between the root's children — the header is already exempt
		// as the first child, the footer is not.
		$all = array_merge( $header, $footer );

		$rules[] = implode( ',', $all ) . '{margin-block-start:0;margin-block-end:0}';

		// Only the first/last child can be the one sitting inside the root padding.
		if ( $header ) {
			$rules[] = implode( ':first-child,', $header ) . ':first-child{margin-top:calc(var(--wp--style--root--padding-top, 0px) * -1)}';
		}

		if ( $footer ) {
			$rules[] = implode( ':last-child,', $footer ) . ':last-child{margin-bottom:calc(var(--wp--style--root--padding-bottom, 0px) * -1)}';
		}

		// An inline-only handle: there is no file, the rules are computed per theme.
		wp_register_style( 'eael-theme-builder-fse', false, [], EAEL_PLUGIN_VERSION );
		wp_enqueue_style( 'eael-theme-builder-fse' );
		wp_add_inline_style( 'eael-theme-builder-fse', implode( '', $rules ) );
	}

	/**
	 * Selectors for the resolved templates of a location, as root children.
	 *
	 * Built from the type registry rather than hard-coded, so a third-party type
	 * registered at the header or footer location is covered too.
	 *
	 * @since 6.7.3
	 *
	 * @param string $location Location slug.
	 *
	 * @return array
	 */
	private function location_selectors( $location ) {
		$selectors = [];

		foreach ( Template_Types::instance()->get_types_by_location( $location ) as $slug => $type ) {
			if ( empty( $this->active[ $slug ] ) ) {
				continue;
			}

			$selectors[] = '.wp-site-blocks > .eael-theme-builder--' . sanitize_html_class( $slug );
		}

		return $selectors;
	}

	/**
	 * Hook the header fallback when the block template has no header part.
	 *
	 * A block theme is free to build a template without a header template part —
	 * a landing page template, for instance. Nothing would then be replaced, and
	 * without this the matched header would silently never render.
	 *
	 * @since 6.7.3
	 *
	 * @param string $template Template file about to be loaded.
	 *
	 * @return string
	 */
	public function prepare_block_template_fallback( $template ) {
		if ( ! $this->has_location( 'header' ) ) {
			return $template;
		}

		if ( ! $this->block_template_has_header( $template ) ) {
			add_action( 'wp_body_open', [ $this, 'render_header_location' ], 0 );
		}

		return $template;
	}

	/**
	 * Whether the template about to load will render a header template part.
	 *
	 * @since 6.7.3
	 *
	 * @param string $template Template file about to be loaded.
	 *
	 * @return bool
	 */
	private function block_template_has_header( $template ) {
		// Something replaced the block template with a file of its own — Elementor
		// Canvas is the common case — so the block template, and with it every
		// template part in it, never renders.
		if ( 'template-canvas.php' !== basename( (string) $template ) ) {
			return false;
		}

		global $_wp_current_template_content;

		return $this->content_has_area( (string) $_wp_current_template_content, 'header' );
	}

	/**
	 * Whether block markup contains a template part of the given area.
	 *
	 * @since 6.7.3
	 *
	 * @param string $content Block markup.
	 * @param string $area    `header` or `footer`.
	 *
	 * @return bool
	 */
	private function content_has_area( $content, $area ) {
		if ( '' === trim( $content ) || ! function_exists( 'parse_blocks' ) ) {
			return false;
		}

		$blocks = parse_blocks( $content );

		while ( $blocks ) {
			$block = array_shift( $blocks );

			if ( ! is_array( $block ) ) {
				continue;
			}

			if ( isset( $block['blockName'] ) && 'core/template-part' === $block['blockName']
				&& $area === $this->get_template_part_area( $block ) ) {
				return true;
			}

			if ( ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
				$blocks = array_merge( $blocks, $block['innerBlocks'] );
			}
		}

		return false;
	}

	/**
	 * Render the matched template in place of a header/footer template part.
	 *
	 * Runs on `pre_render_block`: returning a string short-circuits the block, so
	 * the theme's own part is never rendered. Every further part of the same area
	 * collapses to an empty string, which is what stops a theme that repeats its
	 * header part from stacking two of ours.
	 *
	 * @since 6.7.3
	 *
	 * @param string|null $pre_render   Pre-rendered content, or null to keep rendering.
	 * @param array       $parsed_block The block about to be rendered.
	 *
	 * @return string|null
	 */
	public function maybe_replace_template_part( $pre_render, $parsed_block ) {
		if ( null !== $pre_render ) {
			return $pre_render;
		}

		if ( ! is_array( $parsed_block ) || empty( $parsed_block['blockName'] ) || 'core/template-part' !== $parsed_block['blockName'] ) {
			return $pre_render;
		}

		$area = $this->get_template_part_area( $parsed_block );

		if ( ! in_array( $area, [ 'header', 'footer' ], true ) || ! $this->has_location( $area ) ) {
			return $pre_render;
		}

		// A second part of the same area: the template is already on the page, so
		// the theme's copy must not render underneath it.
		if ( $this->is_location_rendered( $area ) ) {
			return '';
		}

		$html = $this->capture_location( $area );

		// An empty template — no widgets yet, or filtered away — must not take the
		// theme's header off the page and leave nothing behind.
		if ( '' === trim( $html ) ) {
			return $pre_render;
		}

		return $html;
	}

	/**
	 * The `wp_template_part` area a `core/template-part` block renders.
	 *
	 * @since 6.7.3
	 *
	 * @param array $parsed_block Parsed block.
	 *
	 * @return string Area slug, or an empty string when it cannot be determined.
	 */
	private function get_template_part_area( $parsed_block ) {
		$attrs = ( isset( $parsed_block['attrs'] ) && is_array( $parsed_block['attrs'] ) ) ? $parsed_block['attrs'] : [];
		$slug  = isset( $attrs['slug'] ) ? (string) $attrs['slug'] : '';
		$area  = isset( $attrs['area'] ) ? (string) $attrs['area'] : '';

		// The block only carries `area` when the editor wrote it; otherwise the
		// authoritative value is the one on the template part itself.
		if ( ( '' === $area || 'uncategorized' === $area ) && '' !== $slug && function_exists( 'get_block_template' ) ) {
			$theme = ! empty( $attrs['theme'] ) ? (string) $attrs['theme'] : get_stylesheet();
			$id    = $theme . '//' . $slug;

			if ( ! array_key_exists( $id, $this->part_areas ) ) {
				$part = get_block_template( $id, 'wp_template_part' );

				$this->part_areas[ $id ] = ( $part && ! empty( $part->area ) ) ? (string) $part->area : '';
			}

			if ( '' !== $this->part_areas[ $id ] ) {
				$area = $this->part_areas[ $id ];
			}
		}

		if ( '' !== $area && 'uncategorized' !== $area ) {
			return $area;
		}

		// Last resort: an unregistered part still tells us what it is through the
		// tag it renders with, or through its slug.
		$tag = isset( $attrs['tagName'] ) ? (string) $attrs['tagName'] : '';

		foreach ( [ 'header', 'footer' ] as $candidate ) {
			if ( $candidate === $tag || $candidate === $slug || 0 === strpos( $slug, $candidate . '-' ) ) {
				return $candidate;
			}
		}

		return '';
	}

	/**
	 * Whether any template of a location has already been printed.
	 *
	 * @since 6.7.3
	 *
	 * @param string $location Location slug.
	 *
	 * @return bool
	 */
	private function is_location_rendered( $location ) {
		foreach ( Template_Types::instance()->get_types_by_location( $location ) as $slug => $type ) {
			if ( ! empty( $this->active[ $slug ] ) && Template_Renderer::is_rendered( $slug ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Markup of a location, captured instead of printed.
	 *
	 * Buffered rather than concatenated so the `before_render` / `after_render`
	 * hooks behave exactly as they do when the location is echoed.
	 *
	 * @since 6.7.3
	 *
	 * @param string $location Location slug.
	 *
	 * @return string
	 */
	private function capture_location( $location ) {
		ob_start();

		$this->render_location( $location );

		return (string) ob_get_clean();
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

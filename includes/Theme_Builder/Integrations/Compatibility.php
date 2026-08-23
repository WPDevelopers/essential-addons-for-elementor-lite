<?php
/**
 * Third party compatibility for the Theme Builder.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Integrations;

use Essential_Addons_Elementor\Theme_Builder\Core\Post_Type;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Keeps the Theme Builder well behaved next to multilingual and SEO plugins, and
 * next to the other plugins that add to Elementor's add-element row.
 *
 * @since 6.7.3
 */
class Compatibility {

	/**
	 * Register the compatibility hooks.
	 *
	 * @since 6.7.3
	 */
	public function __construct() {
		// Polylang has no Elementor integration, so custom post types have to opt
		// in explicitly before templates can be translated.
		add_filter( 'pll_get_post_types', [ $this, 'polylang_post_types' ], 10, 2 );

		// Template fragments are not documents — keep them out of every sitemap.
		add_filter( 'wpseo_sitemap_exclude_post_type', [ $this, 'yoast_exclude_post_type' ], 10, 2 );
		add_filter( 'rank_math/sitemap/exclude_post_type', [ $this, 'rank_math_exclude_post_type' ], 10, 2 );

		// …and out of the SEO plugins' meta boxes / indexables.
		add_filter( 'wpseo_accessible_post_types', [ $this, 'remove_post_type_from_list' ] );

		// The add-element row lives in the preview iframe, which is a front-end
		// request — so this cannot be hooked from the editor component, which only
		// exists in the admin.
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'even_add_element_row' ] );

		// Last, so every integration that enqueues on this hook is registered by
		// the time the order is adjusted.
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'boot_editor_last' ], PHP_INT_MAX );
	}

	/**
	 * Let editor integrations register before Elementor boots.
	 *
	 * `elementor.start()` lives in a script tag of its own — the editor loader —
	 * and Elementor enqueues it *before* firing `elementor/editor/after_enqueue_scripts`,
	 * with the comment "Must be last". So every integration that enqueues on that
	 * hook prints after the boot, not before it.
	 *
	 * That matters because starting the editor dispatches `elementor:init`, once,
	 * from a promise continuation. An integration that binds its listener when its
	 * own script evaluates — the shape Elementor's own `EditorModule` encourages —
	 * is only in time if that continuation has not settled yet. Whether it has
	 * depends on whether the document config came from cache or from a request,
	 * which is why the symptom is a button that appears on some loads and not
	 * others rather than one that is simply missing.
	 *
	 * Naming those scripts as dependencies of the loader moves them in front of
	 * the boot, where binding to a one-shot event is safe. Their own dependencies
	 * are unaffected: `elementor-editor-modules` is queued earlier and still
	 * prints first, so what they extend is there when they run.
	 *
	 * @since 6.7.4
	 */
	public function boot_editor_last() {
		$scripts = wp_scripts();

		/**
		 * Filters the editor scripts that must print before Elementor boots.
		 *
		 * For integrations that bind to `elementor:init` as their script runs, and
		 * so cannot be registered after the event has already fired.
		 *
		 * @since 6.7.4
		 *
		 * @param array $handles Script handles.
		 */
		$handles = (array) apply_filters(
			'eael/theme_builder/before_editor_boot',
			[ 'templately-elementor' ]
		);

		$loaders = [ 'elementor-editor-loader-v2', 'elementor-editor-loader-v1' ];

		foreach ( $loaders as $loader ) {
			if ( ! isset( $scripts->registered[ $loader ] ) ) {
				continue;
			}

			foreach ( $handles as $handle ) {
				if ( ! isset( $scripts->registered[ $handle ] ) ) {
					continue;
				}

				if ( in_array( $handle, $scripts->registered[ $loader ]->deps, true ) ) {
					continue;
				}

				// A script that already depends on the loader cannot also come
				// before it; adding this would be a cycle, and WordPress would
				// silently drop one of the two.
				if ( in_array( $loader, $scripts->registered[ $handle ]->deps, true ) ) {
					continue;
				}

				$scripts->registered[ $loader ]->deps[] = $handle;
			}
		}
	}

	/**
	 * Keep Elementor's add-element row evenly spaced.
	 *
	 * The row is a flex container with `gap: 5px`; spacing is the container's job,
	 * and none of Elementor's own buttons carry a margin. A plugin button that
	 * brings its own margin adds to the gap rather than replacing it, and the row
	 * ends up with one seam wider than the rest — Templately's button does this,
	 * with a `margin-left` left over from before the row used `gap` at all.
	 *
	 * The rule is written about the row rather than about any one button, so a
	 * second plugin doing the same would not need a second rule. `!important`
	 * because the margins it undoes are set by more specific selectors than this
	 * one, and it must not depend on which stylesheet happens to print last.
	 *
	 * @since 6.7.4
	 */
	public function even_add_element_row() {
		$handle = 'eael-theme-builder-preview';

		wp_register_style( $handle, false, [], EAEL_PLUGIN_VERSION );
		wp_enqueue_style( $handle );
		wp_add_inline_style(
			$handle,
			'.elementor-add-new-section .elementor-add-section-area-button { margin: 0 !important; }'
		);
	}

	/**
	 * Register the template post type with Polylang.
	 *
	 * @since 6.7.3
	 *
	 * @param array $post_types Translatable post types.
	 * @param bool  $is_settings Whether the list is rendered in the settings screen.
	 *
	 * @return array
	 */
	public function polylang_post_types( $post_types, $is_settings = false ) {
		$post_types[ Post_Type::CPT ] = Post_Type::CPT;

		return $post_types;
	}

	/**
	 * Exclude the post type from the Yoast sitemap.
	 *
	 * @since 6.7.3
	 *
	 * @param bool   $excluded  Whether the post type is excluded.
	 * @param string $post_type Post type slug.
	 *
	 * @return bool
	 */
	public function yoast_exclude_post_type( $excluded, $post_type ) {
		return Post_Type::CPT === $post_type ? true : $excluded;
	}

	/**
	 * Exclude the post type from the Rank Math sitemap.
	 *
	 * @since 6.7.3
	 *
	 * @param bool   $excluded  Whether the post type is excluded.
	 * @param string $post_type Post type slug.
	 *
	 * @return bool
	 */
	public function rank_math_exclude_post_type( $excluded, $post_type ) {
		return Post_Type::CPT === $post_type ? true : $excluded;
	}

	/**
	 * Drop the post type from a list of post type slugs.
	 *
	 * @since 6.7.3
	 *
	 * @param array $post_types Post type slugs, possibly keyed.
	 *
	 * @return array
	 */
	public function remove_post_type_from_list( $post_types ) {
		if ( ! is_array( $post_types ) ) {
			return $post_types;
		}

		unset( $post_types[ Post_Type::CPT ] );

		$key = array_search( Post_Type::CPT, $post_types, true );

		if ( false !== $key ) {
			unset( $post_types[ $key ] );
		}

		return $post_types;
	}

	/**
	 * Current language code, or an empty string on monolingual sites.
	 *
	 * Used to namespace the template caches so a Spanish visitor never gets the
	 * template set resolved for the English site.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	public static function get_current_language() {
		if ( function_exists( 'pll_current_language' ) ) {
			$language = pll_current_language();

			if ( $language ) {
				return (string) $language;
			}
		}

		if ( defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE ) {
			return (string) ICL_LANGUAGE_CODE;
		}

		return '';
	}

	/**
	 * Map a template ID to its translation for the current language.
	 *
	 * @since 6.7.3
	 *
	 * @param int $template_id Template ID.
	 *
	 * @return int
	 */
	public static function translate_template_id( $template_id ) {
		$template_id = absint( $template_id );

		if ( ! $template_id ) {
			return 0;
		}

		if ( function_exists( 'pll_get_post' ) ) {
			$translated = pll_get_post( $template_id );

			if ( $translated ) {
				return (int) $translated;
			}
		}

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$translated = apply_filters( 'wpml_object_id', $template_id, Post_Type::CPT, true );

			if ( $translated ) {
				return (int) $translated;
			}
		}

		return $template_id;
	}
}

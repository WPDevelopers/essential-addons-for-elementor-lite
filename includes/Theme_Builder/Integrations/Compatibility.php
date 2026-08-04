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
 * Keeps the Theme Builder well behaved next to multilingual and SEO plugins.
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

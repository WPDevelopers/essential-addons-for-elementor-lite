<?php
/**
 * Ready-made header and footer presets.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Presets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * The starting points offered by the EA button in the Elementor editor.
 *
 * A preset is two things: a card for the picker, and the Elementor elements it
 * inserts. The elements are **built at insert time**, not stored as a frozen
 * JSON blob, because a header only feels ready-made when it comes up with the
 * site's own name and the site's own menu already in it — and because element
 * IDs have to be unique per insert.
 *
 * Everything a preset uses ships with Lite: containers, heading, button, icon
 * and image are Elementor core, the navigation is EA's own Simple Menu or Mega
 * Menu. Nothing here needs Elementor Pro.
 *
 * @since 6.7.3
 */
class Preset_Library {

	/**
	 * Every preset, keyed by slug.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_presets() {
		// Richest first within each type — that is the order the picker shows.
		$presets = [
			'classic-header' => [
				'slug'        => 'classic-header',
				'type'        => 'header',
				'title'       => __( 'Classic Header', 'essential-addons-for-elementor-lite' ),
				'badge'       => __( 'Clean & Simple', 'essential-addons-for-elementor-lite' ),
				'thumbnail'   => self::thumbnail_url( 'classic-header.png' ),
				'widgets'     => [ 'eael-simple-menu', 'heading', 'image', 'button' ],
				'builder'     => [ Classic_Header::class, 'build' ],
			],
			'mega-header'    => [
				'slug'        => 'mega-header',
				'type'        => 'header',
				'title'       => __( 'Mega Menu Header', 'essential-addons-for-elementor-lite' ),
				'badge'       => __( 'Mega Menu', 'essential-addons-for-elementor-lite' ),
				'thumbnail'   => self::thumbnail_url( 'mega-header.png' ),
				'widgets'     => [ 'eael-mega-menu', 'heading', 'image', 'icon', 'icon-box', 'icon-list', 'button', 'text-editor' ],
				'builder'     => [ Mega_Header::class, 'build' ],
			],
			'shop-header'    => [
				'slug'        => 'shop-header',
				'type'        => 'header',
				'title'       => __( 'Shop Header', 'essential-addons-for-elementor-lite' ),
				'badge'       => __( 'Search & Cart', 'essential-addons-for-elementor-lite' ),
				'thumbnail'   => self::thumbnail_url( 'shop-header.png' ),
				'widgets'     => [ 'eael-simple-menu', 'eael-creative-button', 'heading', 'image', 'icon' ],
				'builder'     => [ Shop_Header::class, 'build' ],
			],
				'simple-footer'  => [
				'slug'        => 'simple-footer',
				'type'        => 'footer',
				'title'       => __( 'Simple Footer', 'essential-addons-for-elementor-lite' ),
				'badge'       => __( 'Minimal', 'essential-addons-for-elementor-lite' ),
				'thumbnail'   => self::thumbnail_url( 'simple-footer.png' ),
				'widgets'     => [ 'divider', 'heading', 'image', 'icon-list', 'social-icons' ],
				'builder'     => [ Simple_Footer::class, 'build' ],
			],
			'modern-footer'  => [
				'slug'        => 'modern-footer',
				'type'        => 'footer',
				'title'       => __( 'Modern Footer', 'essential-addons-for-elementor-lite' ),
				'badge'       => __( 'Multi-Column', 'essential-addons-for-elementor-lite' ),
				'thumbnail'   => self::thumbnail_url( 'modern-footer.png' ),
				'widgets'     => [ 'eael-creative-button', 'heading', 'image', 'icon-list' ],
				'builder'     => [ Modern_Footer::class, 'build' ],
			],
			'brand-footer'   => [
				'slug'        => 'brand-footer',
				'type'        => 'footer',
				'title'       => __( 'Brand Footer', 'essential-addons-for-elementor-lite' ),
				'badge'       => __( 'Rounded Card', 'essential-addons-for-elementor-lite' ),
				'thumbnail'   => self::thumbnail_url( 'brand-footer.png' ),
				'widgets'     => [ 'eael-dual-color-header', 'heading', 'icon-list', 'social-icons' ],
				'builder'     => [ Brand_Footer::class, 'build' ],
			],
		];

		/**
		 * Filters the header and footer presets offered in the editor.
		 *
		 * A preset needs a `type` (`header` or `footer`), a `title`, a
		 * `thumbnail` URL and a `builder` callable returning an array of
		 * Elementor elements. The optional `widgets` list names every widget type
		 * the builder can emit; a preset that names them is hidden whenever one is
		 * missing, rather than half inserting and leaving the wreckage behind.
		 *
		 * @since 6.7.3
		 *
		 * @param array $presets Preset definitions keyed by slug.
		 */
		$presets = (array) apply_filters( 'eael/theme_builder/presets', $presets );

		$valid = [];

		foreach ( $presets as $slug => $preset ) {
			$slug = sanitize_key( $slug );

			// `empty()` before `is_callable()`: a filtered preset that forgot its
			// builder would otherwise raise an undefined-key warning here, and this
			// runs inside an AJAX handler where a warning lands in front of the
			// JSON and breaks every insert, not just the malformed preset.
			if ( '' === $slug || ! is_array( $preset ) || empty( $preset['title'] ) || empty( $preset['builder'] ) || ! is_callable( $preset['builder'] ) ) {
				continue;
			}

			// A widget can be missing because Elementor is too old, because an
			// experiment is off, because the element is switched off in EA's own
			// settings, or because it was deactivated in Elementor's element
			// manager. Any of those turns an insert into a partial one: the create
			// command throws part way through the tree, and what it already built
			// stays on the canvas. Not offering the card is the only clean answer.
			if ( ! empty( $preset['widgets'] ) && ! Elements::has_widgets( $preset['widgets'] ) ) {
				continue;
			}

			$preset['slug'] = $slug;
			$preset['type'] = ! empty( $preset['type'] ) ? sanitize_key( $preset['type'] ) : 'header';

			$valid[ $slug ] = $preset;
		}

		return $valid;
	}

	/**
	 * The presets as the picker needs them — cards only, no element data.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function get_presets_for_ui() {
		$prepared = [];

		foreach ( self::get_presets() as $slug => $preset ) {
			$prepared[] = [
				'slug'        => $slug,
				'type'        => $preset['type'],
				'title'       => $preset['title'],
				'badge'       => isset( $preset['badge'] ) ? $preset['badge'] : '',
				'description' => isset( $preset['description'] ) ? $preset['description'] : '',
				'thumbnail'   => isset( $preset['thumbnail'] ) ? $preset['thumbnail'] : '',
			];
		}

		return $prepared;
	}

	/**
	 * The Elementor elements one preset inserts.
	 *
	 * @since 6.7.3
	 *
	 * @param string $slug Preset slug.
	 *
	 * @return array|null Elements array, or null when the slug is unknown.
	 */
	public static function get_content( $slug ) {
		$presets = self::get_presets();
		$slug    = sanitize_key( $slug );

		if ( ! isset( $presets[ $slug ] ) ) {
			return null;
		}

		$content = call_user_func( $presets[ $slug ]['builder'] );

		/**
		 * Filters the elements a preset inserts.
		 *
		 * @since 6.7.3
		 *
		 * @param array  $content Elementor elements.
		 * @param string $slug    Preset slug.
		 */
		return (array) apply_filters( 'eael/theme_builder/preset_content', $content, $slug );
	}

	/* ---------------------------------------------------------------------
	 * Presets.
	 * ------------------------------------------------------------------ */


	/* ---------------------------------------------------------------------
	 * Helpers.
	 * ------------------------------------------------------------------ */

	/**
	 * URL of a preset thumbnail.
	 *
	 * @since 6.7.3
	 *
	 * @param string $file File name inside the theme builder image folder.
	 *
	 * @return string
	 */
	protected static function thumbnail_url( $file ) {
		return EAEL_PLUGIN_URL . 'assets/admin/images/theme-builder/' . $file;
	}
}

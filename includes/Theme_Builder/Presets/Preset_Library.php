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
			'mega-header'    => [
				'slug'        => 'mega-header',
				'type'        => 'header',
				'title'       => __( 'Mega Menu Header', 'essential-addons-for-elementor-lite' ),
				'badge'       => __( 'Mega Menu', 'essential-addons-for-elementor-lite' ),
				'description' => __( 'Logo, a centred mega menu with two ready-built panels, search, cart and a call to action. Collapses to a toggle on mobile.', 'essential-addons-for-elementor-lite' ),
				'thumbnail'   => self::thumbnail_url( 'mega-header.svg' ),
				'widgets'     => [ 'eael-mega-menu', 'heading', 'image', 'icon', 'icon-box', 'icon-list', 'button', 'text-editor' ],
				'builder'     => [ Mega_Header::class, 'build' ],
			],
			'modern-footer'  => [
				'slug'        => 'modern-footer',
				'type'        => 'footer',
				'title'       => __( 'Modern Footer', 'essential-addons-for-elementor-lite' ),
				'badge'       => __( 'Multi-Column', 'essential-addons-for-elementor-lite' ),
				'description' => __( 'Brand, a newsletter sign-up and three link columns on a dark ground, over a centred copyright line. Stacks on smaller screens.', 'essential-addons-for-elementor-lite' ),
				'thumbnail'   => self::thumbnail_url( 'modern-footer.svg' ),
				'widgets'     => [ 'eael-creative-button', 'heading', 'image', 'icon-list' ],
				'builder'     => [ Modern_Footer::class, 'build' ],
			],
			'simple-footer'  => [
				'slug'        => 'simple-footer',
				'type'        => 'footer',
				'title'       => __( 'Simple Footer', 'essential-addons-for-elementor-lite' ),
				'badge'       => __( 'Minimal', 'essential-addons-for-elementor-lite' ),
				'description' => __( 'One centred column: brand, a line of copy, links, social icons and a copyright. Reads the same at every screen size.', 'essential-addons-for-elementor-lite' ),
				'thumbnail'   => self::thumbnail_url( 'simple-footer.svg' ),
				'widgets'     => [ 'heading', 'image', 'icon-list', 'social-icons', 'text-editor' ],
				'builder'     => [ Simple_Footer::class, 'build' ],
			],
			'classic-header' => [
				'slug'        => 'classic-header',
				'type'        => 'header',
				'title'       => __( 'Classic Header', 'essential-addons-for-elementor-lite' ),
				'badge'       => __( 'Clean & Simple', 'essential-addons-for-elementor-lite' ),
				'description' => __( 'Site name on the left, navigation and a call to action on the right. Collapses to a hamburger on mobile.', 'essential-addons-for-elementor-lite' ),
				'thumbnail'   => self::thumbnail_url( 'classic-header.svg' ),
				'widgets'     => [ 'eael-simple-menu', 'heading', 'button' ],
				'builder'     => [ __CLASS__, 'build_classic_header' ],
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

	/**
	 * Classic header: brand, navigation, call to action.
	 *
	 * Laid out as one row of three containers rather than one container with
	 * three widgets, so each part can be moved, restyled or replaced on its own
	 * once the user starts editing — which is the point of a starting point.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	public static function build_classic_header() {
		$brand = Elements::container(
			[
				'width'       => Elements::size( 25, '%' ),
				'width_tablet' => Elements::size( 40, '%' ),
				'width_mobile' => Elements::size( 60, '%' ),
				'content_width' => 'full',
				'flex_justify_content' => 'flex-start',
			],
			[
				Elements::widget(
					'heading',
					[
						'title'       => get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : __( 'Brand', 'essential-addons-for-elementor-lite' ),
						'header_size' => 'h2',
						'link'        => [ 'url' => home_url( '/' ) ],
						// A linked heading inherits the theme's link colour, which
						// is rarely the right ink for a site name.
						'title_color' => '#1D1F2B',
						'typography_typography' => 'custom',
						'typography_font_size'  => Elements::size( 24 ),
						'typography_font_size_mobile' => Elements::size( 20 ),
						'typography_font_weight' => '700',
					]
				),
			]
		);

		$navigation = Elements::container(
			[
				'width'        => Elements::size( 50, '%' ),
				'width_tablet' => Elements::size( 20, '%' ),
				'width_mobile' => Elements::size( 40, '%' ),
				'content_width' => 'full',
				'flex_justify_content' => 'center',
				'flex_justify_content_tablet' => 'flex-end',
			],
			[
				Elements::widget( 'eael-simple-menu', self::menu_settings() ),
			]
		);

		$action = Elements::container(
			[
				'width'        => Elements::size( 25, '%' ),
				'width_tablet' => Elements::size( 40, '%' ),
				'content_width' => 'full',
				'flex_justify_content' => 'flex-end',
				// The button is the first thing worth dropping on a phone: the
				// menu and the brand still have to fit beside each other there.
				'hide_mobile'  => 'hidden-mobile',
			],
			[
				Elements::widget(
					'button',
					[
						'text'            => __( 'Get Started', 'essential-addons-for-elementor-lite' ),
						'link'            => [ 'url' => '#' ],
						'size'            => 'sm',
						// Spelled out rather than inherited: a theme's button
						// colour is rarely the one a header wants next to a logo.
						'background_color' => '#5B3DF5',
						'button_text_color' => '#FFFFFF',
						'border_radius'   => Elements::spacing( 6, 6, 6, 6 ),
						'text_padding'    => Elements::spacing( 10, 20, 10, 20 ),
						'typography_typography' => 'custom',
						'typography_font_size'  => Elements::size( 14 ),
						'typography_font_weight' => '600',
					]
				),
			]
		);

		return [
			Elements::container(
				[
					'content_width'        => 'boxed',
					'flex_direction'       => 'row',
					'flex_align_items'     => 'center',
					'flex_justify_content' => 'space-between',
					'flex_wrap'            => 'nowrap',
					'flex_gap'             => Elements::gap( 24 ),
					'padding'              => Elements::spacing( 16, 24, 16, 24 ),
					'padding_mobile'       => Elements::spacing( 12, 16, 12, 16 ),
					'background_background' => 'classic',
					'background_color'     => '#FFFFFF',
					'box_shadow_box_shadow_type' => 'yes',
					'box_shadow_box_shadow' => Elements::shadow( 2, 12, 'rgba(16, 18, 32, 0.08)' ),
				],
				[ $brand, $navigation, $action ]
			),
		];
	}

	/**
	 * Settings for the Simple Menu widget, pointed at the site's own menu.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function menu_settings() {
		$settings = [
			'eael_simple_menu_layout'   => 'horizontal',
			// Hamburger from tablet down: a row of links and a brand rarely fit
			// side by side below ~1024px.
			'eael_simple_menu_dropdown' => 'tablet',
			'eael_simple_menu_preset'   => 'preset-1',
			// Without this the mobile dropdown is only as wide as the container
			// holding the hamburger — a ~150px column of links on a phone.
			'eael_simple_menu_full_width' => 'yes',

			// Every colour is spelled out, transparents included. Left empty, the
			// setting emits no rule at all and the *theme's* menu styling wins —
			// which on a typical install is a solid bar in the theme's link
			// colour sitting inside the header.
			'eael_simple_menu_background'            => 'rgba(0,0,0,0)',
			'eael_simple_menu_item_color'            => '#1D1F2B',
			'eael_simple_menu_item_background'       => 'rgba(0,0,0,0)',
			'eael_simple_menu_item_color_hover'      => '#5B3DF5',
			'eael_simple_menu_item_background_hover' => 'rgba(0,0,0,0)',
			// The current page's link is filled by default. In a header that sits
			// on every page — and whose links can all point at the same place —
			// that reads as a stray purple block rather than as "you are here".
			'eael_simple_menu_item_color_active'     => '#5B3DF5',
			'eael_simple_menu_item_background_active' => 'rgba(0,0,0,0)',
			// Left, not right. The control is not responsive — one value covers
			// every device — and it is the mobile dropdown that pays for the
			// choice: a full width panel of right-aligned labels reads as a ragged
			// column pushed against the screen edge.
			'eael_simple_menu_item_alignment'       => 'eael-simple-menu-align-left',
			'eael_simple_menu_dropdown_item_alignment' => 'eael-simple-menu-dropdown-align-left',
			'eael_simple_menu_item_padding'         => Elements::spacing( 8, 14, 8, 14 ),
			'eael_simple_menu_item_typography_typography' => 'custom',
			'eael_simple_menu_item_typography_font_size'  => Elements::size( 15 ),
			'eael_simple_menu_item_typography_font_weight' => '500',

			// Hamburger: same ink as the links, no filled bar behind it, and no
			// current-item label printed next to it — a header that is this
			// compact wants the icon alone.
			'eael_simple_menu_hamburger_disable_selected_menu' => 'hide',
			'eael_simple_menu_hamburger_bg'         => 'rgba(0,0,0,0)',
			'eael_simple_menu_hamburger_icon_color' => '#1D1F2B',
			'eael_simple_menu_hamburger_alignment'  => 'right',

			// Inside the open dropdown the ink flips: that panel is the widget's
			// own filled surface, so the links there are white, not the dark ones
			// used on the header bar itself.
			'eael_simple_menu_hamburger_top_level_item_color'        => '#FFFFFF',
			'eael_simple_menu_hamburger_top_level_item_color_hover'  => '#FFFFFF',
			'eael_simple_menu_hamburger_top_level_item_bg_hover'     => 'rgba(255,255,255,0.14)',
			'eael_simple_menu_hamburger_top_level_item_color_active' => '#FFFFFF',
			'eael_simple_menu_hamburger_top_level_item_bg_active'    => 'rgba(255,255,255,0.14)',
		];

		$menus = wp_get_nav_menus();

		// Left unset when the site has no menus, so the widget shows its own
		// "create one" notice rather than pointing at a menu that is not there.
		if ( ! empty( $menus ) ) {
			$settings['eael_simple_menu_menu'] = (string) $menus[0]->term_id;
		}

		return $settings;
	}

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

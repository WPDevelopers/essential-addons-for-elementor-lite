<?php
/**
 * The shop header preset.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.4
 */

namespace Essential_Addons_Elementor\Theme_Builder\Presets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * A light shop bar: brand, centred navigation, search, cart and a sign-in.
 *
 * The header a store wants: the navigation stays in the middle where it is read,
 * and the three things a shopper reaches for — search, basket, account — are
 * grouped at the end, in reading order, with the only filled control of the three
 * last. The two icons are discs rather than bare glyphs so they read as controls
 * beside the button instead of as decoration beside the links.
 *
 * Three columns rather than a flex row of widgets, because the middle one has to
 * stay centred on the *bar* and not between its neighbours: a long site name
 * would otherwise push the links off centre. Each column is a `row` container —
 * on a column container `justify` would place its children up and down, and every
 * alignment here would do nothing at all.
 *
 * EA does the two pieces that carry behaviour: Simple Menu, which collapses to a
 * hamburger from tablet down, and Creative Button for the sign-in. The rest is
 * core, because an image and two icons are what core already draws well.
 *
 * The scheme is the constants below and nothing under them hard codes a shade of
 * one, so a shop in other colours is two edits.
 *
 * @since 6.7.4
 */
class Shop_Header {

	/**
	 * The bar.
	 */
	const SURFACE = '#FFFFFF';

	/**
	 * Brand, icons, and the current page's link.
	 */
	const BRAND = '#146B4C';

	/**
	 * The links, which sit back from the brand.
	 */
	const BODY = '#6B7280';

	/**
	 * Behind the icons.
	 */
	const DISC = '#EFF4F1';

	/**
	 * The sign-in button.
	 */
	const BUTTON = '#F5DC5B';

	/**
	 * Its edge, and the hairline under the bar.
	 */
	const LINE = '#E4E9E6';

	/**
	 * The elements this preset inserts.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	public static function build() {
		return [
			Elements::container(
				[
					'content_width'         => 'boxed',
					'flex_direction'        => 'row',
					'flex_align_items'      => 'center',
					'flex_justify_content'  => 'space-between',
					'flex_wrap'             => 'nowrap',
					'flex_gap'              => Elements::gap( 20 ),
					'padding'               => Elements::spacing( 14, 24, 14, 24 ),
					'padding_tablet'        => Elements::spacing( 12, 20, 12, 20 ),
					'padding_mobile'        => Elements::spacing( 10, 16, 10, 16 ),
					'background_background' => 'classic',
					'background_color'      => self::SURFACE,
					// A hairline rather than a shadow: this bar is white, and on a
					// page that opens white the edge is the only thing telling the
					// two apart.
					'border_border'         => 'solid',
					'border_width'          => Elements::spacing( 0, 0, 1, 0 ),
					'border_color'          => self::LINE,
					'_title'                => __( 'Header', 'essential-addons-for-elementor-lite' ),
				],
				[ self::brand(), self::navigation(), self::actions() ]
			),
		];
	}

	/**
	 * The site's logo, or its name.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function brand() {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		$logo    = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';

		if ( $logo ) {
			$mark = Elements::widget(
				'image',
				[
					'image'        => [ 'id' => $logo_id, 'url' => $logo ],
					'image_size'   => 'full',
					'align'        => 'start',
					'width'        => Elements::size( 170 ),
					'width_mobile' => Elements::size( 110 ),
					'link_to'      => 'custom',
					'link'         => Elements::link( home_url( '/' ) ),
				]
			);
		} else {
			$name = get_bloginfo( 'name' );
			$name = '' !== trim( (string) $name ) ? $name : __( 'Brand', 'essential-addons-for-elementor-lite' );

			$mark = Elements::widget(
				'heading',
				[
					'title'                       => $name,
					'header_size'                 => 'h2',
					'link'                        => Elements::link( home_url( '/' ) ),
					// A linked heading inherits the theme's link colour, which is
					// rarely the right ink for a site name.
					'title_color'                 => self::BRAND,
					'typography_typography'       => 'custom',
					'typography_font_size'        => Elements::size( 22 ),
					'typography_font_size_tablet' => Elements::size( 20 ),
					'typography_font_size_mobile' => Elements::size( 18 ),
					'typography_font_weight'      => '700',
					'typography_letter_spacing'   => Elements::size( -0.3 ),
				]
			);
		}

		return Elements::container(
			[
				'content_width'        => 'full',
				'width'                => Elements::size( 25, '%' ),
				'width_tablet'         => Elements::size( 55, '%' ),
				'width_mobile'         => Elements::size( 60, '%' ),
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'flex-start',
				'padding'              => Elements::spacing( 0, 0, 0, 0 ),
				'_title'               => __( 'Brand', 'essential-addons-for-elementor-lite' ),
			],
			[ $mark ]
		);
	}

	/**
	 * The centred navigation.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function navigation() {
		return Elements::container(
			[
				'content_width'               => 'full',
				'width'                       => Elements::size( 45, '%' ),
				// The tablet widths add up to a full row on purpose: any slack would
				// be shared out between the columns by `space-between`, which is
				// exactly where the toggle and the icons do not want it.
				'width_tablet'                => Elements::size( 18, '%' ),
				'width_mobile'                => Elements::size( 15, '%' ),
				'flex_direction'              => 'row',
				'flex_align_items'            => 'center',
				'flex_justify_content'        => 'center',
				'flex_justify_content_tablet' => 'flex-end',
				'padding'                     => Elements::spacing( 0, 0, 0, 0 ),
				'_title'                      => __( 'Navigation', 'essential-addons-for-elementor-lite' ),
			],
			[
				Elements::widget(
					'eael-simple-menu',
					array_merge(
						self::menu_settings(),
						[
							// Full width from tablet down, where this widget is a
							// hamburger whose dropdown panel measures itself against the
							// width of the widget.
							'_element_width_tablet' => 'inherit',
						]
					)
				),
			]
		);
	}

	/**
	 * Search, cart and the sign-in button.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function actions() {
		return Elements::container(
			[
				'content_width'        => 'full',
				'width'                => Elements::size( 25, '%' ),
				// Sized to what is actually in it from tablet down, so the toggle and
				// the controls end up next to each other rather than at opposite ends
				// of the bar: two discs, a button and their gaps come to about 250px,
				// and the button alone is floored at 150 by its own stylesheet.
				'width_tablet'         => Elements::size( 27, '%' ),
				'width_mobile'         => Elements::size( 25, '%' ),
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'flex-end',
				'flex_wrap'            => 'nowrap',
				'flex_gap'             => Elements::gap( 14 ),
				'flex_gap_mobile'      => Elements::gap( 8 ),
				'padding'              => Elements::spacing( 0, 0, 0, 0 ),
				'_title'               => __( 'Actions', 'essential-addons-for-elementor-lite' ),
			],
			[
				self::action_icon( 'fas fa-search', __( 'Search', 'essential-addons-for-elementor-lite' ) ),
				self::action_icon( 'fas fa-shopping-bag', __( 'Cart', 'essential-addons-for-elementor-lite' ) ),
				self::sign_in(),
			]
		);
	}

	/**
	 * One of the discs beside the button.
	 *
	 * @since 6.7.4
	 *
	 * @param string $icon  Font Awesome class.
	 * @param string $label Navigator title.
	 *
	 * @return array
	 */
	protected static function action_icon( $icon, $label ) {
		return Elements::widget( 'icon', [
			'selected_icon'         => Elements::icon( $icon ),
			'link'                  => Elements::link(),
			// Stacked, so the disc is the widget's own background rather than a
			// container wrapped around a glyph.
			'view'                  => 'stacked',
			'shape'                 => 'circle',
			'size'                  => Elements::size( 15 ),
			'icon_padding'          => Elements::size( 10 ),
			'primary_color'         => self::DISC,
			'secondary_color'       => self::BRAND,
			'hover_primary_color'   => self::BRAND,
			'hover_secondary_color' => self::SURFACE,
			'_title'                => $label,
		] );
	}

	/**
	 * The sign-in button.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function sign_in() {
		return Elements::widget(
			'eael-creative-button',
			[
				'creative_button_text'                        => __( 'Login', 'essential-addons-for-elementor-lite' ),
				'creative_button_link_url'                    => Elements::link( wp_login_url() ),
				'creative_button_effect'                      => 'eael-creative-button--default',
				'eael_creative_button_icon_new'               => Elements::icon( '' ),
				// The control writes `justify-content`, so it wants a flex value.
				'eael_creative_button_alignment'              => 'flex-end',
				'eael_creative_button_padding'                => Elements::spacing( 10, 22, 10, 22 ),
				'eael_creative_button_padding_mobile'         => Elements::spacing( 9, 16, 9, 16 ),
				// A slider, not a dimensions control: the widget's own stylesheet
				// rounds the button by 2px, and only this value sets the corner.
				'eael_creative_button_border_radius'          => Elements::size( 4 ),
				'eael_creative_button_text_color'             => self::BRAND,
				'eael_creative_button_background_color'       => self::BUTTON,
				'eael_creative_button_hover_text_color'       => self::SURFACE,
				'eael_creative_button_hover_background_color' => self::BRAND,
				'eael_creative_button_typography_typography'  => 'custom',
				'eael_creative_button_typography_font_size'   => Elements::size( 15 ),
				'eael_creative_button_typography_font_size_mobile' => Elements::size( 14 ),
				'eael_creative_button_typography_font_weight' => '600',
				// Out on a phone. Its own 150px floor is a third of a small screen,
				// and the basket and the toggle are what a shopper reaches for there;
				// signing in lives one tap away inside the menu.
				'hide_mobile'                                 => 'hidden-mobile',
				// Held at its own size: a widget in a row container grows into the
				// space left over, and a sign-in button stretched across a quarter of
				// the bar is not a button any more. Its own stylesheet then floors it
				// at 150px with no control behind that, so a short label like this one
				// sits in a wider button than its padding asks for — swapping in
				// core's Button is the way out for anyone who wants it tighter.
				'_flex_size'                                  => 'none',
			]
		);
	}

	/**
	 * Simple Menu, dressed for a light bar.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function menu_settings() {
		$settings = [
			'eael_simple_menu_layout'     => 'horizontal',
			// Hamburger from tablet down: a row of links, a brand and three controls
			// do not fit side by side below ~1024px.
			'eael_simple_menu_dropdown'   => 'tablet',
			'eael_simple_menu_preset'     => 'preset-1',
			// Without this the mobile dropdown is only as wide as the container
			// holding the hamburger — a narrow column of links on a phone.
			'eael_simple_menu_full_width' => 'yes',

			// Every colour is spelled out, transparents included. Left empty, the
			// setting emits no rule at all and the *theme's* menu styling wins —
			// which on a typical install is a solid bar in the theme's link colour
			// sitting inside the header.
			'eael_simple_menu_background'            => 'rgba(0,0,0,0)',
			'eael_simple_menu_item_color'            => self::BODY,
			'eael_simple_menu_item_background'       => 'rgba(0,0,0,0)',
			'eael_simple_menu_item_color_hover'      => self::BRAND,
			'eael_simple_menu_item_background_hover' => 'rgba(0,0,0,0)',
			// The current page's link is the one thing on the bar in the brand's
			// own colour, which is what makes it read as "you are here" without a
			// filled block behind it.
			'eael_simple_menu_item_color_active'      => self::BRAND,
			'eael_simple_menu_item_background_active' => 'rgba(0,0,0,0)',
			// Left, not right. The control is not responsive — one value covers
			// every device — and it is the mobile dropdown that pays for the
			// choice: a full width panel of right-aligned labels reads as a ragged
			// column pushed against the screen edge.
			'eael_simple_menu_item_alignment'          => 'eael-simple-menu-align-left',
			'eael_simple_menu_dropdown_item_alignment' => 'eael-simple-menu-dropdown-align-left',
			'eael_simple_menu_item_padding'            => Elements::spacing( 8, 14, 8, 14 ),
			'eael_simple_menu_item_typography_typography'  => 'custom',
			'eael_simple_menu_item_typography_font_size'   => Elements::size( 15 ),
			'eael_simple_menu_item_typography_font_weight' => '500',

			// The submenu panel on desktop: white, edged, and dark inked, rather
			// than the preset's own purple.
			'eael_simple_menu_dropdown_background'            => self::SURFACE,
			'eael_simple_menu_dropdown_item_color'            => self::BODY,
			'eael_simple_menu_dropdown_item_background'       => 'rgba(0,0,0,0)',
			'eael_simple_menu_dropdown_item_color_hover'      => self::BRAND,
			'eael_simple_menu_dropdown_item_background_hover' => self::DISC,
			'eael_simple_menu_dropdown_item_divider_color'    => self::LINE,

			// Hamburger: the brand's ink, no filled bar behind it, and no
			// current-item label printed next to it — a bar with three controls
			// already on it wants the icon alone.
			'eael_simple_menu_hamburger_disable_selected_menu' => 'hide',
			'eael_simple_menu_hamburger_bg'         => 'rgba(0,0,0,0)',
			'eael_simple_menu_hamburger_icon_color' => self::BRAND,
			'eael_simple_menu_hamburger_alignment'  => 'right',

			// The panel behind the open hamburger menu is painted by the widget's
			// preset — `preset-1` hard codes a purple there and exposes no control
			// for it — so the rows are given the bar's own colour instead. They tile
			// the full width of the panel, which is what leaves the purple with
			// nowhere to show.
			'eael_simple_menu_hamburger_top_level_item_background'    => self::SURFACE,
			'eael_simple_menu_hamburger_top_level_item_color'         => self::BODY,
			'eael_simple_menu_hamburger_top_level_item_color_hover'   => self::BRAND,
			'eael_simple_menu_hamburger_top_level_item_bg_hover'      => self::DISC,
			'eael_simple_menu_hamburger_top_level_item_color_active'  => self::BRAND,
			'eael_simple_menu_hamburger_top_level_item_bg_active'     => self::DISC,
			'eael_simple_menu_hamburger_dropdown_item_background'     => self::SURFACE,
			'eael_simple_menu_hamburger_dropdown_item_color'          => self::BODY,
			'eael_simple_menu_hamburger_dropdown_item_color_hover'    => self::BRAND,
			'eael_simple_menu_hamburger_dropdown_item_bg_hover'       => self::DISC,
		];

		$menus = wp_get_nav_menus();

		// Left unset when the site has no menus, so the widget shows its own
		// "create one" notice rather than pointing at a menu that is not there.
		if ( ! empty( $menus ) ) {
			$settings['eael_simple_menu_menu'] = (string) $menus[0]->term_id;
		}

		return $settings;
	}
}

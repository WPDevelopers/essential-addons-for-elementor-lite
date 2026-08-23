<?php
/**
 * The classic header preset.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.4
 */

namespace Essential_Addons_Elementor\Theme_Builder\Presets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * A single dark bar: brand, centred navigation, one call to action.
 *
 * Three columns rather than a flex row of three widgets, because the middle one
 * has to stay centred on the *bar* and not between its neighbours: a long site
 * name would otherwise push the links off centre. The columns are 23/54/23, so
 * the menu's half is centred whatever is in the other two, and the middle one
 * takes the larger share because a real menu carries five or six items, not the
 * three a mock-up shows.
 *
 * The bar is a colour, not white. A header sits on top of whatever the page
 * opens with — a hero image, a photo, a coloured section — and a coloured bar
 * holds its own against all three, where white asks the page to end in white.
 * The whole scheme is the six constants below: change `SURFACE` and `ACCENT`
 * and the header is a different colour, because nothing here hard codes a shade
 * of them.
 *
 * The navigation is EA's Simple Menu, which is what makes this responsive
 * without Elementor Pro: it collapses to a hamburger from tablet down, and the
 * call to action steps out on mobile so the brand and the toggle keep the bar.
 *
 * @since 6.7.4
 */
class Classic_Header {

	/**
	 * The bar.
	 */
	const SURFACE = '#173B32';

	/**
	 * The panel the hamburger opens, a shade under the bar.
	 */
	const PANEL = '#122E27';

	/**
	 * Brand, links, and the button's label.
	 */
	const INK = '#FFFFFF';

	/**
	 * Hovered links, and the button's fill on hover.
	 */
	const ACCENT = '#7FD1A8';

	/**
	 * The button, which is the bar showing through rather than a second colour.
	 */
	const BUTTON_FILL = 'rgba(255, 255, 255, 0.1)';

	/**
	 * Its edge, and the hairline under the bar.
	 */
	const LINE = 'rgba(255, 255, 255, 0.28)';

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
					'flex_gap'              => Elements::gap( 24 ),
					'padding'               => Elements::spacing( 26, 24, 26, 24 ),
					'padding_tablet'        => Elements::spacing( 20, 20, 20, 20 ),
					'padding_mobile'        => Elements::spacing( 14, 16, 14, 16 ),
					'background_background' => 'classic',
					'background_color'      => self::SURFACE,
					'_title'                => __( 'Header', 'essential-addons-for-elementor-lite' ),
				],
				[ self::brand(), self::navigation(), self::action() ]
			),
		];
	}

	/**
	 * The preset's wordmark.
	 *
	 * The shipped logo, not the site's own — see `Elements::brand_logo()`.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function brand() {
		$mark = Elements::widget(
			'image',
			[
				// An empty id is how Elementor's media control spells "no
				// attachment"; the file is shipped with the plugin, not in the
				// library. Leaving the key out entirely makes the editor look the
				// missing attachment up and warn about it on every load.
				'image'        => [ 'id' => '', 'url' => Elements::brand_logo( 'fintech-header-logo.png' ) ],
				'image_size'   => 'full',
				'align'        => 'start',
				'width'        => Elements::size( 165 ),
				'width_mobile' => Elements::size( 130 ),
				'link_to'      => 'custom',
				'link'         => Elements::link( home_url( '/' ) ),
			]
		);

		return Elements::container(
			[
				'content_width'        => 'full',
				'width'                => Elements::size( 23, '%' ),
				'width_tablet'         => Elements::size( 58, '%' ),
				'width_mobile'         => Elements::size( 60, '%' ),
				// Row, not the container default of column: `justify` places children
				// along the main axis, and in a column container that axis is the
				// vertical one — so a column here would leave every one of these
				// three alignments doing nothing at all.
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
				'width'                       => Elements::size( 54, '%' ),
				// The three tablet widths add up to a full row on purpose: any
				// slack would be shared out between the columns by `space-between`,
				// which is exactly where the toggle and the button do not want it.
				'width_tablet'                => Elements::size( 22, '%' ),
				'width_mobile'                => Elements::size( 40, '%' ),
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
							// hamburger whose dropdown panel measures itself against
							// the width of the widget.
							'_element_width_tablet' => 'inherit',
						]
					)
				),
			]
		);
	}

	/**
	 * The call to action.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function action() {
		return Elements::container(
			[
				'content_width'        => 'full',
				'width'                => Elements::size( 23, '%' ),
				'width_tablet'         => Elements::size( 20, '%' ),
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'flex-end',
				'padding'              => Elements::spacing( 0, 0, 0, 0 ),
				// The button is the first thing worth dropping on a phone: the menu
				// and the brand still have to fit beside each other there.
				'hide_mobile'          => 'hidden-mobile',
				'_title'               => __( 'Call to Action', 'essential-addons-for-elementor-lite' ),
			],
			[
				Elements::widget(
					'button',
					[
						'text'                            => __( 'Contact Now', 'essential-addons-for-elementor-lite' ),
						'link'                            => Elements::link(),
						'size'                            => 'sm',
						// Spelled out rather than inherited: a theme's button colour
						// is rarely the one a header wants next to a logo. The fill is
						// the bar showing through at a tenth of white, so the button
						// stays part of the bar until it is hovered.
						'background_background'           => 'classic',
						'background_color'                => self::BUTTON_FILL,
						'button_text_color'               => self::INK,
						'border_border'                   => 'solid',
						'border_width'                    => Elements::spacing( 1, 1, 1, 1 ),
						'border_color'                    => self::LINE,
						'border_radius'                   => Elements::spacing( 4, 4, 4, 4 ),
						'text_padding'                    => Elements::spacing( 15, 30, 15, 30 ),
						'text_padding_mobile'             => Elements::spacing( 12, 22, 12, 22 ),
						'typography_typography'           => 'custom',
						'typography_font_size'            => Elements::size( 15 ),
						'typography_font_weight'          => '600',
						// Hover fills it with the accent and flips the label to the
						// bar's own colour, which is the only pairing on this palette
						// that stays readable.
						'hover_color'                     => self::SURFACE,
						'button_background_hover_background' => 'classic',
						'button_background_hover_color'   => self::ACCENT,
						'button_hover_border_color'       => self::ACCENT,
						'button_hover_transition_duration' => Elements::size( 0.2, 's' ),
					]
				),
			]
		);
	}

	/**
	 * Simple Menu, dressed for a dark bar.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function menu_settings() {
		$settings = [
			'eael_simple_menu_layout'     => 'horizontal',
			// Hamburger from tablet down: a row of links and a brand rarely fit
			// side by side below ~1024px.
			'eael_simple_menu_dropdown'   => 'tablet',
			'eael_simple_menu_preset'     => 'preset-1',
			// Without this the mobile dropdown is only as wide as the container
			// holding the hamburger — a ~150px column of links on a phone.
			'eael_simple_menu_full_width' => 'yes',

			// Every colour is spelled out, transparents included. Left empty, the
			// setting emits no rule at all and the *theme's* menu styling wins —
			// which on a typical install is a solid bar in the theme's link colour
			// sitting inside the header.
			'eael_simple_menu_background'            => 'rgba(0,0,0,0)',
			'eael_simple_menu_item_color'            => self::INK,
			'eael_simple_menu_item_background'       => 'rgba(0,0,0,0)',
			'eael_simple_menu_item_color_hover'      => self::ACCENT,
			'eael_simple_menu_item_background_hover' => 'rgba(0,0,0,0)',
			// The current page's link is filled by default. In a header that sits
			// on every page — and whose links can all point at the same place —
			// that reads as a stray block rather than as "you are here".
			'eael_simple_menu_item_color_active'      => self::ACCENT,
			'eael_simple_menu_item_background_active' => 'rgba(0,0,0,0)',
			// Left, not right. The control is not responsive — one value covers
			// every device — and it is the mobile dropdown that pays for the
			// choice: a full width panel of right-aligned labels reads as a ragged
			// column pushed against the screen edge.
			'eael_simple_menu_item_alignment'          => 'eael-simple-menu-align-left',
			'eael_simple_menu_dropdown_item_alignment' => 'eael-simple-menu-dropdown-align-left',
			'eael_simple_menu_item_padding'            => Elements::spacing( 8, 16, 8, 16 ),
			'eael_simple_menu_item_typography_typography'   => 'custom',
			'eael_simple_menu_item_typography_font_size'    => Elements::size( 16 ),
			'eael_simple_menu_item_typography_font_weight'  => '500',

			// The submenu panel on desktop: a shade under the bar, so a dropdown
			// reads as part of the header rather than as a white card falling out
			// of it.
			'eael_simple_menu_dropdown_background'           => self::PANEL,
			'eael_simple_menu_dropdown_item_color'           => self::INK,
			'eael_simple_menu_dropdown_item_background'      => 'rgba(0,0,0,0)',
			'eael_simple_menu_dropdown_item_color_hover'     => self::ACCENT,
			'eael_simple_menu_dropdown_item_background_hover' => 'rgba(255,255,255,0.06)',
			'eael_simple_menu_dropdown_item_divider_color'   => 'rgba(255,255,255,0.1)',

			// Hamburger: same ink as the links, no filled bar behind it, and no
			// current-item label printed next to it — a header that is this compact
			// wants the icon alone.
			'eael_simple_menu_hamburger_disable_selected_menu' => 'hide',
			'eael_simple_menu_hamburger_bg'         => 'rgba(0,0,0,0)',
			'eael_simple_menu_hamburger_icon_color' => self::INK,
			'eael_simple_menu_hamburger_alignment'  => 'right',

			// The panel behind the open hamburger menu is painted by the widget's
			// preset — `preset-1` hard codes a purple there and exposes no control
			// for it — so the rows are given the header's own colour instead. They
			// tile the full width of the panel, which is what leaves the purple
			// with nowhere to show.
			'eael_simple_menu_hamburger_top_level_item_background'    => self::PANEL,
			'eael_simple_menu_hamburger_dropdown_item_background'     => self::SURFACE,
			'eael_simple_menu_hamburger_dropdown_item_color'          => self::INK,
			'eael_simple_menu_hamburger_dropdown_item_color_hover'    => self::ACCENT,
			'eael_simple_menu_hamburger_dropdown_item_bg_hover'       => self::PANEL,

			// Inside the open dropdown the ink stays white on the widget's own
			// filled surface, and the accent is kept for the row under the pointer.
			'eael_simple_menu_hamburger_top_level_item_color'        => self::INK,
			'eael_simple_menu_hamburger_top_level_item_color_hover'  => self::ACCENT,
			'eael_simple_menu_hamburger_top_level_item_bg_hover'     => 'rgba(255,255,255,0.08)',
			'eael_simple_menu_hamburger_top_level_item_color_active' => self::INK,
			'eael_simple_menu_hamburger_top_level_item_bg_active'    => 'rgba(255,255,255,0.08)',
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

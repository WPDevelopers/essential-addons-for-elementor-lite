<?php
/**
 * The Agency Services preset.
 *
 * @package Essential_Addons_Elementor
 * @since   6.8.4
 */

namespace Essential_Addons_Elementor\MegaMenu\Presets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Essential_Addons_Elementor\MegaMenu\Manager;
use Essential_Addons_Elementor\Theme_Builder\Presets\Elements;

/**
 * A services menu: eleven things an agency does, on a ruled grid.
 *
 * Three columns, and unlike the other presets they are the same *kind* of thing
 * three times — an icon, a name and a line of explanation — which is what a
 * services menu is. What changes between them is the job:
 *
 * - **For Enterprise Users** and **Solutions For Brands** — eight offerings,
 *   four to a column, each a row that lights up whole under the pointer.
 * - **Get In Touch** — three ways to reach someone, and the social accounts
 *   underneath. No wash on these rows: the column is the quiet one, and the
 *   tiles at the foot are what the eye is meant to land on.
 *
 * ## One widget per row
 *
 * Every row is Elementor's own **Icon Box**, which holds the glyph, the name,
 * the sentence, the link and the spacing between them as controls on a single
 * widget. Hand-building each row from an Icon plus two Headings would be three
 * widgets where one will do — thirty-three elements in this panel instead of
 * eleven — and would leave the gap between the icon and the text as the one
 * thing with no control behind it.
 *
 * The five social accounts are one **Social Icons** widget for the same reason,
 * and it is also the widget that already knows what a social account is: the
 * repeater's icon picker is filtered to brands, and the tile, the glyph and both
 * hover colours are its own controls.
 *
 * ## Nothing here is somebody else's element
 *
 * Icon Box, Social Icons, Heading, Image and Button, all Elementor core. No EA
 * element has to be switched on for this preset to arrive whole.
 *
 * ## Two line weights, on purpose
 *
 * The design rules the panel twice over and not with the same pen: the grid
 * under the headings is a hairline you have to look for ({@see SOFT_LINE}),
 * while the lines dividing the service rows are a step darker ({@see LINE}).
 * Measured off the mock rather than guessed — see {@see cell()}.
 *
 * @since 6.8.4
 */
class Agency_Menu {

	/**
	 * Row titles, menu items, the wordmark.
	 */
	const INK = '#686868';

	/**
	 * The sentence under a row title.
	 */
	const MUTED = '#858585';

	/**
	 * A column heading — lighter than everything it introduces, and carried by
	 * its letter-spacing rather than by its weight.
	 */
	const HEADING = '#ABABAB';

	/**
	 * Row icons, social glyphs, and the rules between service rows. The design
	 * draws all three in the same grey, which is what keeps the grid reading as
	 * part of the furniture rather than as a frame around it.
	 */
	const LINE = '#C1C1C1';

	/**
	 * The second, lighter pen: the rule under the column headings, the dividers
	 * inside that band, and the line above the social tiles.
	 */
	const SOFT_LINE = '#F1F1F1';

	/**
	 * The heading band, the third column, and a service row under the pointer.
	 */
	const SURFACE = '#FFFFFF';

	/**
	 * A service row at rest — a half step off white, which is the whole reason
	 * the hover reads as one.
	 */
	const WASH = '#F7F7F7';

	/**
	 * The header bar.
	 */
	const BAR = '#F2F2F2';

	/**
	 * A social tile's plate.
	 */
	const TILE = '#F1F1F1';

	/**
	 * The call to action's surface.
	 */
	const BUTTON = '#A9A9A9';

	/**
	 * The call to action's label, and a social glyph once the pointer is on it.
	 */
	const ON_DARK = '#FFFFFF';

	/**
	 * The element this preset applies.
	 *
	 * @since 6.8.4
	 *
	 * @param string $mode `header` or `widget`.
	 *
	 * @return array One Elementor element.
	 */
	public static function build( $mode = 'header' ) {
		$menu = Elements::widget( Manager::WIDGET_NAME, self::settings(), self::panels() );

		return 'widget' === $mode ? $menu : self::header( $menu );
	}

	/* ---------------------------------------------------------------------
	 * Header bar.
	 * ------------------------------------------------------------------ */

	/**
	 * The header: wordmark, navigation, and the one button in the bar.
	 *
	 * @since 6.8.4
	 *
	 * @param array $menu The Mega Menu widget element.
	 *
	 * @return array
	 */
	protected static function header( $menu ) {
		return Elements::container(
			[
				// Full, not boxed, and this is the one place this preset parts
				// company with the other three. The design's bar runs almost the
				// width of the page — its wordmark sits 32 from the edge, not at
				// a 1140 container's margin — and the panel under it is the same
				// width, which is what a services menu of eleven rows needs to
				// avoid three cramped columns.
				//
				// The cost is that the bar no longer lines up with a boxed page
				// below it. Switching this one control back to Boxed is the fix
				// if that matters more than matching the design.
				'content_width'         => 'full',
				'flex_direction'        => 'row',
				'flex_align_items'      => 'center',
				'flex_justify_content'  => 'space-between',
				'flex_wrap'             => 'nowrap',
				'flex_gap'              => Elements::gap( 24 ),
				'flex_gap_tablet'       => Elements::gap( 16 ),
				'flex_gap_mobile'       => Elements::gap( 8 ),
				'padding'               => Elements::spacing( 16, 32, 16, 32 ),
				'padding_tablet'        => Elements::spacing( 14, 20, 14, 20 ),
				'padding_mobile'        => Elements::spacing( 12, 16, 12, 16 ),
				'background_background' => 'classic',
				'background_color'      => self::BAR,
				'_title'                => __( 'Header Bar', 'essential-addons-for-elementor-lite' ),
			],
			[
				self::brand(),
				self::navigation( $menu ),
				self::actions(),
			]
		);
	}

	/**
	 * The wordmark column.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function brand() {
		return Elements::container(
			[
				'content_width'        => 'full',
				'width'                => Elements::size( 24, '%' ),
				'width_tablet'         => Elements::size( 26, '%' ),
				'width_mobile'         => Elements::size( 62, '%' ),
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'flex-start',
				'flex_gap'             => Elements::gap( 10 ),
				'padding'              => Elements::spacing( 0, 0, 0, 0 ),
				'_title'               => __( 'Brand', 'essential-addons-for-elementor-lite' ),
			],
			[ self::logo() ]
		);
	}

	/**
	 * The site logo, or its name when no logo is set.
	 *
	 * The site's own mark rather than the wordmark the mock draws. A header only
	 * feels ready-made when it comes up already wearing the site it landed on,
	 * and a preset that shipped somebody else's brand name would be a preset
	 * whose first job is to be deleted.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function logo() {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		$logo    = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';

		if ( $logo ) {
			return Elements::widget(
				'image',
				[
					'image'        => [ 'id' => $logo_id, 'url' => $logo ],
					'image_size'   => 'full',
					'align'        => 'start',
					'width'        => Elements::size( 150 ),
					'width_mobile' => Elements::size( 118 ),
					'link_to'      => 'custom',
					'link'         => Elements::link( home_url( '/' ) ),
				]
			);
		}

		return Elements::widget(
			'heading',
			[
				'title'                       => get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : __( 'BrainScript', 'essential-addons-for-elementor-lite' ),
				'header_size'                 => 'h2',
				'link'                        => Elements::link( home_url( '/' ) ),
				// A linked heading inherits the theme's link colour, which is
				// rarely the right ink for a site name.
				'title_color'                 => self::INK,
				'typography_typography'       => 'custom',
				'typography_font_size'        => Elements::size( 24 ),
				'typography_font_size_tablet' => Elements::size( 21 ),
				'typography_font_size_mobile' => Elements::size( 18 ),
				'typography_font_weight'      => '700',
				'typography_text_decoration'  => 'none',
			]
		);
	}

	/**
	 * The navigation column.
	 *
	 * Ordered last on mobile so the collapsed toggle lands beside the call to
	 * action rather than in the middle of the bar.
	 *
	 * @since 6.8.4
	 *
	 * @param array $menu The Mega Menu widget element.
	 *
	 * @return array
	 */
	protected static function navigation( $menu ) {
		return Elements::container(
			[
				'content_width'               => 'full',
				'width'                       => Elements::size( 56, '%' ),
				'width_tablet'                => Elements::size( 46, '%' ),
				'width_mobile'                => Elements::size( 38, '%' ),
				'flex_direction'              => 'row',
				'flex_align_items'            => 'center',
				'flex_justify_content'        => 'center',
				'flex_justify_content_mobile' => 'flex-end',
				'padding'                     => Elements::spacing( 0, 0, 0, 0 ),
				'_flex_order_mobile'          => 'end',
				'_title'                      => __( 'Navigation', 'essential-addons-for-elementor-lite' ),
			],
			[ $menu ]
		);
	}

	/**
	 * The call-to-action column.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function actions() {
		return Elements::container(
			[
				'content_width'        => 'full',
				'width'                => Elements::size( 24, '%' ),
				'width_tablet'         => Elements::size( 28, '%' ),
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'flex-end',
				'flex_wrap'            => 'nowrap',
				'padding'              => Elements::spacing( 0, 0, 0, 0 ),
				// Out on a phone, column and all: what has to fit there is the
				// wordmark and the menu toggle, and the call is one tap away
				// inside the menu. Hiding the button alone would leave the
				// column holding its width and push the toggle into the middle
				// of the bar.
				'hide_mobile'          => 'hidden-mobile',
				'_title'               => __( 'Actions', 'essential-addons-for-elementor-lite' ),
			],
			[ self::cta_button() ]
		);
	}

	/**
	 * "Book A Call" — the one thing in the bar meant to be pressed.
	 *
	 * Core's Button, not EA's Creative Button: this one is a plain pill with a
	 * label in it and nothing else, which is the shape core's own widget makes
	 * without help. The 129x40 the design draws is the label plus the padding
	 * below it.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function cta_button() {
		return Elements::widget(
			'button',
			[
				'text'                          => __( 'Book A Call', 'essential-addons-for-elementor-lite' ),
				'link'                          => Elements::link(),
				'align'                         => 'right',
				'background_color'              => self::BUTTON,
				'button_text_color'             => self::ON_DARK,
				'button_background_hover_color' => self::INK,
				'hover_color'                   => self::ON_DARK,
				// Past half the button's height, so the ends are semicircles at
				// whatever height the label makes it.
				'border_radius'                 => Elements::spacing( 40, 40, 40, 40 ),
				'text_padding'                  => Elements::spacing( 11, 26, 11, 26 ),
				'text_padding_tablet'           => Elements::spacing( 10, 20, 10, 20 ),
				'typography_typography'         => 'custom',
				'typography_font_size'          => Elements::size( 15 ),
				'typography_font_weight'        => '500',
				// The design's pill is 40 tall, which is 11 of padding either
				// side of an 18px line. Left unset the label takes the theme's
				// line-height — 32.4px on the one this was measured against —
				// and the button comes out 54. Height is the label's leading
				// plus the padding and nothing else, so this is where it is set.
				'typography_line_height'        => Elements::size( 1.2, 'em' ),
				// A button is a button, but to a theme it is still an `<a>`, and
				// plenty of them underline every link from a selector no widget
				// default outranks.
				'typography_text_decoration'    => 'none',
				// Held at its own size: a widget in a row container grows into
				// whatever space is left, and a stretched pill stops reading as
				// a button.
				'_flex_size'                    => 'none',
			]
		);
	}

	/* ---------------------------------------------------------------------
	 * Menu items.
	 * ------------------------------------------------------------------ */

	/**
	 * The menu items, in bar order.
	 *
	 * @since 6.8.4
	 *
	 * @return array List of item definitions.
	 */
	protected static function item_map() {
		return [
			[ 'label' => __( 'About Us', 'essential-addons-for-elementor-lite' ), 'type' => 'link' ],
			[
				'label' => __( 'Services', 'essential-addons-for-elementor-lite' ),
				'type'  => 'mega',
				'panel' => 'services',
				// Viewport, not a pixel width: the design's panel runs the full
				// width of the page, and a fixed width is an overflow waiting
				// for the first laptop narrower than it.
				'width' => 'viewport',
				'align' => 'center',
			],
			[
				'label' => __( 'Resources', 'essential-addons-for-elementor-lite' ),
				'type'  => 'mega',
				'panel' => 'resources',
				// A plain list of six destinations, so it is sized to them
				// rather than to the page. Anchored to the item's own start
				// edge, which is where a dropdown of this width belongs — a
				// centred one drifts away from the label that opened it.
				'width' => 260,
				'align' => 'start',
			],
			[ 'label' => __( 'Contact', 'essential-addons-for-elementor-lite' ), 'type' => 'link' ],
			[ 'label' => __( 'Our Work', 'essential-addons-for-elementor-lite' ), 'type' => 'link' ],
		];
	}

	/**
	 * Repeater rows for the menu items.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function menu_items() {
		$rows = [];

		foreach ( self::item_map() as $item ) {
			$row = [
				'eael_mega_menu_item_label' => $item['label'],
				'eael_mega_menu_item_type'  => $item['type'],
			];

			if ( 'link' === $item['type'] ) {
				// Without an href the renderer falls back to a <span>, which is
				// neither clickable nor focusable. The panel item is left
				// unlinked on purpose so the item itself opens its panel instead
				// of growing a separate disclosure button beside the label.
				$row['eael_mega_menu_item_link'] = Elements::link();
			} else {
				// Per item, not per preset: the two panels are different shapes
				// and want different widths — the catalogue is the page wide,
				// the resource list is 260 and would look abandoned in the same
				// room. See {@see item_map()}.
				$row['eael_mega_menu_item_panel_align'] = $item['align'];

				if ( 'viewport' === $item['width'] ) {
					$row['eael_mega_menu_item_submenu_width'] = 'viewport';
				} else {
					$row['eael_mega_menu_item_submenu_width']        = 'custom';
					$row['eael_mega_menu_item_submenu_custom_width'] = Elements::size( $item['width'] );
				}
			}

			$rows[] = Elements::row( $row );
		}

		return $rows;
	}

	/* ---------------------------------------------------------------------
	 * Widget settings.
	 * ------------------------------------------------------------------ */

	/**
	 * Settings for the widget itself.
	 *
	 * Every colour is spelled out, the transparent ones included: left empty a
	 * colour setting emits no rule at all and the theme's own link styling wins,
	 * which on a typical install paints the menu in the theme accent.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function settings() {
		return [
			'eael_mega_menu_items' => self::menu_items(),

			// Behaviour.
			'eael_mega_menu_trigger'            => 'hover',
			'eael_mega_menu_hover_delay'        => Elements::size( 180 ),
			'eael_mega_menu_animation'          => 'slide-down',
			'eael_mega_menu_animation_duration' => Elements::size( 220 ),
			'eael_mega_menu_stretch'            => 'yes',
			'eael_mega_menu_align'              => 'center',
			'eael_mega_menu_indicator_icon'     => Elements::icon( 'fas fa-chevron-down' ),

			// Responsive. Tablet, not mobile: the panel is three columns of
			// two-line copy and none of them survives a tablet in portrait.
			'eael_mega_menu_breakpoint'         => 'tablet',
			'eael_mega_menu_toggle_text'        => '',
			'eael_mega_menu_toggle_full_width'  => 'yes',

			// Menu bar — transparent, because it sits on the header's surface.
			'eael_mega_menu_bar_background_background' => 'classic',
			'eael_mega_menu_bar_background_color'      => 'rgba(0,0,0,0)',
			'eael_mega_menu_bar_gap'                   => Elements::size( 12 ),
			'eael_mega_menu_bar_padding'               => Elements::spacing( 0, 0, 0, 0 ),

			// Items.
			'eael_mega_menu_item_typography_typography'  => 'custom',
			'eael_mega_menu_item_typography_font_size'   => Elements::size( 16 ),
			'eael_mega_menu_item_typography_font_weight' => '500',
			// The bar is 72 tall in the design, and that is 16 either side of
			// the 40px call to action — so nothing else in the bar may exceed
			// 40. Without this the item labels take the theme's line-height
			// (32.4px on the one measured here), the menu becomes the tallest
			// thing in the row at 52, and the bar grows to 84 for no reason the
			// user can see. 10 of padding either side of a 19.2 line is 39.2.
			'eael_mega_menu_item_typography_line_height' => Elements::size( 1.2, 'em' ),
			// The widget's stylesheet already resets this; the control writes it
			// again at Elementor's own specificity, which is what a theme
			// reaching past three classes runs into.
			'eael_mega_menu_item_typography_text_decoration' => 'none',
			'eael_mega_menu_item_padding'                => Elements::spacing( 10, 12, 10, 12 ),
			'eael_mega_menu_item_radius'                 => Elements::spacing( 8, 8, 8, 8 ),
			'eael_mega_menu_item_color'                  => self::INK,
			'eael_mega_menu_item_bg'                     => 'rgba(0,0,0,0)',
			// The bar does not recolour. Hovering an item opens a panel below it
			// and a row inside that panel is already lighting up under the
			// pointer; a second highlight on the label above competes with the
			// one the visitor is reading.
			'eael_mega_menu_item_color_hover'            => self::INK,
			'eael_mega_menu_item_bg_hover'               => 'rgba(0,0,0,0)',
			'eael_mega_menu_item_color_active'           => self::INK,
			'eael_mega_menu_item_bg_active'              => 'rgba(0,0,0,0)',

			// Indicator.
			'eael_mega_menu_indicator_size'         => Elements::size( 11 ),
			'eael_mega_menu_indicator_gap'          => Elements::size( 6 ),
			'eael_mega_menu_indicator_rotate'       => 'yes',
			'eael_mega_menu_indicator_color'        => self::INK,
			'eael_mega_menu_indicator_color_active' => self::INK,

			// Panel. Deliberately invisible, and spelled out as transparent
			// rather than left empty — left empty these fall back to the
			// stylesheet's defaults and paint a second sheet behind the card.
			//
			// The visible thing is the boxed card inside, not the panel: the
			// panel runs the full width of the screen so it never overflows,
			// while the design's card stops at the same edges the header bar
			// does and shows the page either side of it. A panel painted white
			// would fill that margin in and the card would lose its edges.
			'eael_mega_menu_panel_background_background' => 'classic',
			'eael_mega_menu_panel_background_color'      => 'rgba(0,0,0,0)',
			'eael_mega_menu_panel_radius'                => Elements::spacing( 0, 0, 0, 0 ),
			// Zero, and the page gutter the services card needs is a container
			// inside that panel instead — see {@see services_panel()}. This
			// control is per widget, not per item, so a value here insets every
			// panel: the 23 the full-bleed catalogue wants would leave the
			// 260px Resources dropdown a 214px card floating in the middle of
			// its own box.
			'eael_mega_menu_panel_padding'               => Elements::spacing( 0, 0, 0, 0 ),
			// Flush with the bar: the design draws no gap, and the bar's own
			// wash is what separates the two.
			'eael_mega_menu_panel_offset'                => Elements::size( 0 ),
			'eael_mega_menu_panel_z_index'               => 999,

			// Mobile toggle.
			'eael_mega_menu_toggle_align'       => 'flex-end',
			'eael_mega_menu_toggle_icon_size'   => Elements::size( 18 ),
			'eael_mega_menu_toggle_padding'     => Elements::spacing( 9, 11, 9, 11 ),
			'eael_mega_menu_toggle_radius'      => Elements::spacing( 8, 8, 8, 8 ),
			'eael_mega_menu_toggle_color'       => self::INK,
			'eael_mega_menu_toggle_bg'          => self::TILE,
			'eael_mega_menu_toggle_color_hover' => self::ON_DARK,
			'eael_mega_menu_toggle_bg_hover'    => self::INK,

			// Collapsed dropdown. The accordion opens inside it and the panel is
			// eleven rows tall, so scrolling it beats clipping it.
			'eael_mega_menu_dropdown_background_background' => 'classic',
			'eael_mega_menu_dropdown_background_color'      => self::SURFACE,
			'eael_mega_menu_dropdown_radius'                => Elements::spacing( 12, 12, 12, 12 ),
			'eael_mega_menu_dropdown_padding'               => Elements::spacing( 10, 10, 10, 10 ),
			'eael_mega_menu_dropdown_shadow_box_shadow_type' => 'yes',
			'eael_mega_menu_dropdown_shadow_box_shadow'     => Elements::shadow( 16, 40, 'rgba(16, 24, 40, 0.16)' ),
			// Clear of the header bar. The sheet hangs off the menu widget,
			// which sits inside a bar with its own padding.
			'eael_mega_menu_dropdown_offset'                => Elements::size( 20 ),
			'eael_mega_menu_dropdown_max_height'            => Elements::size( 75, 'vh' ),
		];
	}

	/* ---------------------------------------------------------------------
	 * Panel.
	 * ------------------------------------------------------------------ */

	/**
	 * The nested containers, one per menu item.
	 *
	 * Positional: the widget prints child *n* for row *n*, so a link item gets
	 * an empty container rather than no container at all — skipping one would
	 * shift every panel after it onto the wrong item.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function panels() {
		$panels = [];

		foreach ( self::item_map() as $item ) {
			// Keyed off the item rather than its position in the list, so
			// reordering the bar — or dropping an item from it — cannot hand a
			// panel to the wrong label.
			switch ( isset( $item['panel'] ) ? $item['panel'] : '' ) {
				case 'services':
					$panels[] = self::services_panel( $item['label'] );
					break;

				case 'resources':
					$panels[] = self::resources_panel( $item['label'] );
					break;

				default:
					$panels[] = Elements::nested_child( [
						'content_width' => 'full',
						'_title'        => $item['label'],
					] );
					break;
			}
		}

		return $panels;
	}

	/**
	 * The Services panel.
	 *
	 * The panel itself is the full-bleed sheet; this is the boxed card of
	 * content on it, so the columns line up with the header bar above rather
	 * than with the edges of the screen.
	 *
	 * @since 6.8.4
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function services_panel( $title ) {
		return Elements::nested_child(
			[
				'content_width'  => 'full',
				'flex_direction' => 'column',
				'_title'         => $title,
			],
			[
				Elements::container(
					[
						// The page gutter, and it has to be its own container.
						// Putting it on the panel above would need the widget's
						// Submenu Panel > Padding control — that control writes
						// the very `--padding-*` variables a container reads, so
						// nothing set on the panel itself survives it — and that
						// control insets *every* panel, including the narrow
						// Resources dropdown, which wants none.
						'content_width'  => 'full',
						'flex_direction' => 'column',
						'padding'        => Elements::spacing( 0, 23, 0, 23 ),
						// Collapsed, the dropdown supplies its own inset and a
						// second one would double it.
						'padding_tablet' => Elements::spacing( 0, 0, 0, 0 ),
						'_title'         => __( 'Gutter', 'essential-addons-for-elementor-lite' ),
					],
					[ Elements::container(
					[
						'content_width'    => 'full',
						'flex_direction'   => 'row',
						'flex_wrap'        => 'nowrap',
						// Below the menu's own breakpoint the panel is inside
						// the collapsed dropdown, where three columns side by
						// side are three columns of about a hundred pixels each.
						'flex_wrap_tablet' => 'wrap',
						// What carries the dividers: the columns are as tall as
						// the tallest of them, so the hairline on the first two
						// reaches the bottom of the panel however they fill up.
						'flex_align_items' => 'stretch',
						'flex_gap'         => Elements::gap( 0 ),
						'padding'          => Elements::spacing( 0, 0, 0, 0 ),
						// The card's own edge. Squared off, because the panel is
						// a ruled grid and a rounded frame around a grid reads
						// as a card that lost its corners.
						'border_border'    => 'solid',
						'border_width'     => Elements::spacing( 1, 1, 1, 1 ),
						'border_color'     => self::LINE,
						'border_radius'    => Elements::spacing( 0, 0, 0, 0 ),
						// The shadow belongs here rather than on the panel, for
						// the same reason the border does: the panel is a
						// full-width sheet with nothing drawn on it, and a
						// shadow around that is a shadow down the edges of the
						// screen.
						'box_shadow_box_shadow_type' => 'yes',
						'box_shadow_box_shadow' => Elements::shadow( 18, 40, 'rgba(16, 24, 40, 0.10)' ),
						'_title'           => __( 'Panel', 'essential-addons-for-elementor-lite' ),
					],
					[
						self::cell(
							'34.1%',
							__( 'For Enterprise Users', 'essential-addons-for-elementor-lite' ),
							self::rows( self::enterprise_rows(), true ),
							self::WASH,
							true
						),
						self::cell(
							'34.1%',
							__( 'Solutions For Brands', 'essential-addons-for-elementor-lite' ),
							self::rows( self::brand_rows(), true ),
							self::WASH,
							true
						),
						self::cell(
							'31.8%',
							__( 'Get In Touch', 'essential-addons-for-elementor-lite' ),
							array_merge( self::rows( self::touch_rows(), false ), [ self::social_row() ] ),
							self::SURFACE,
							false
						),
					]
				) ]
				),
			]
		);
	}

	/**
	 * One column of the panel: a heading, the rule under it, and the rows.
	 *
	 * The heading is inside the column rather than in a band of its own above
	 * all three, and that is what makes the panel survive being stacked. A band
	 * draws the design's continuous rule in one border and needs no talking
	 * into meeting — but the moment the columns go one above the other, all
	 * three headings stay at the top, orphaned from what they name.
	 *
	 * Here the rule is three borders instead of one, and they still read as a
	 * single line: each is the bottom edge of a strip that spans its whole
	 * column, the strips are flush against each other, and their height is the
	 * same because their padding and their type are. The inset that keeps the
	 * words off the divider is inside the strip, not around it.
	 *
	 * The two pens are the design's, not a slip: inside the heading band every
	 * line is {@see SOFT_LINE}, and everything below it is {@see LINE}. That is
	 * what stops the band reading as a fourth row.
	 *
	 * The vertical divider belongs to the *rows*, not to the column, so it
	 * starts at the rule instead of running the whole height in one weight —
	 * and the row stack is set to grow, so it reaches the bottom of a column
	 * stretched to match its tallest neighbour.
	 *
	 * @since 6.8.4
	 *
	 * @param string $width   Width, as a percentage string.
	 * @param string $heading Column heading.
	 * @param array  $rows    The column's rows.
	 * @param string $surface The colour the rows sit on.
	 * @param bool   $divider Whether the column closes with a hairline.
	 *
	 * @return array
	 */
	protected static function cell( $width, $heading, $rows, $surface, $divider ) {
		$band = [
			'content_width'         => 'full',
			'flex_direction'        => 'column',
			'flex_gap'              => Elements::gap( 0 ),
			// 48 tall in the design, and that is 16 either side of a 12px
			// heading's own line box plus the rule under it.
			'padding'               => Elements::spacing( 16, 20, 16, 17 ),
			'padding_tablet'        => Elements::spacing( 18, 11, 16, 11 ),
			'background_background' => 'classic',
			'background_color'      => self::SURFACE,
			'border_border'         => 'solid',
			'border_width'          => Elements::spacing( 0, $divider ? 1 : 0, 1, 0 ),
			'border_width_tablet'   => Elements::spacing( 0, 0, 1, 0 ),
			'border_color'          => self::SOFT_LINE,
			'_title'                => __( 'Heading', 'essential-addons-for-elementor-lite' ),
		];

		$body = [
			'content_width'         => 'full',
			'flex_direction'        => 'column',
			'flex_gap'              => Elements::gap( 0 ),
			'padding'               => Elements::spacing( 0, 0, 0, 0 ),
			'background_background' => 'classic',
			'background_color'      => $surface,
			// Fills the height the column was stretched to, which is the only
			// reason the divider below reaches the foot of the panel.
			'_flex_size'            => 'grow',
			'_title'                => __( 'Rows', 'essential-addons-for-elementor-lite' ),
		];

		if ( $divider ) {
			$body['border_border'] = 'solid';
			$body['border_width']  = Elements::spacing( 0, 1, 0, 0 );
			// Stacked, there is no left and right for a line to divide, so it
			// becomes the rule between one column and the next.
			$body['border_width_tablet'] = Elements::spacing( 0, 0, 1, 0 );
			$body['border_color']  = self::LINE;
		}

		return Elements::container(
			[
				'content_width'  => 'full',
				'width'          => Elements::size( (float) $width, '%' ),
				'width_tablet'   => Elements::size( 100, '%' ),
				'flex_direction' => 'column',
				'flex_gap'       => Elements::gap( 0 ),
				'padding'        => Elements::spacing( 0, 0, 0, 0 ),
				'_title'         => $heading,
			],
			[
				Elements::container( $band, [ self::column_heading( $heading ) ] ),
				Elements::container( $body, $rows ),
			]
		);
	}

	/**
	 * A column heading — small, pale, and carried by its tracking.
	 *
	 * @since 6.8.4
	 *
	 * @param string $title Heading text.
	 *
	 * @return array
	 */
	protected static function column_heading( $title ) {
		return Elements::widget(
			'heading',
			[
				'title'                     => $title,
				'header_size'               => 'h6',
				'title_color'               => self::HEADING,
				'typography_typography'     => 'custom',
				'typography_font_size'      => Elements::size( 12 ),
				'typography_font_weight'    => '500',
				'typography_text_transform' => 'uppercase',
				'typography_letter_spacing' => Elements::size( 2.5 ),
				'typography_line_height'    => Elements::size( 1.2, 'em' ),
			]
		);
	}

	/* ---------------------------------------------------------------------
	 * Resources dropdown.
	 * ------------------------------------------------------------------ */

	/**
	 * The Resources panel: six destinations, one under another.
	 *
	 * Not every menu item needs a panel the width of the page. This one is a
	 * plain list — the same card, the same hairlines and the same hover wash as
	 * the services grid, at a sixth of the width — which is what keeps two very
	 * differently shaped panels reading as one menu.
	 *
	 * It is built from linked containers rather than one Icon List, and the
	 * reason is the chevron: Icon List draws a row's glyph at the *start*, and
	 * the one row here that has a mark has it at the end, where a disclosure
	 * belongs. Its Divider control would have drawn the hairlines, but a list
	 * that can only mark its rows on the wrong side is a list this panel cannot
	 * use.
	 *
	 * @since 6.8.4
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function resources_panel( $title ) {
		$rows  = self::resource_rows();
		$last  = count( $rows ) - 1;
		$items = [];

		foreach ( $rows as $index => $row ) {
			$items[] = self::dropdown_row( $row, $index !== $last );
		}

		return Elements::nested_child(
			[
				'content_width'  => 'full',
				'flex_direction' => 'column',
				'_title'         => $title,
			],
			[
				Elements::container(
					[
						'content_width'         => 'full',
						'flex_direction'        => 'column',
						'flex_gap'              => Elements::gap( 0 ),
						'padding'               => Elements::spacing( 0, 0, 0, 0 ),
						'background_background' => 'classic',
						'background_color'      => self::SURFACE,
						// The services card's edge, at this scale: same colour,
						// same 1px, same squared-off corners.
						'border_border'         => 'solid',
						'border_width'          => Elements::spacing( 1, 1, 1, 1 ),
						'border_color'          => self::LINE,
						'border_radius'         => Elements::spacing( 0, 0, 0, 0 ),
						'box_shadow_box_shadow_type' => 'yes',
						'box_shadow_box_shadow' => Elements::shadow( 18, 40, 'rgba(16, 24, 40, 0.10)' ),
						'_title'                => __( 'Card', 'essential-addons-for-elementor-lite' ),
					],
					$items
				),
			]
		);
	}

	/**
	 * What the Resources dropdown lists.
	 *
	 * `submenu` marks the row that leads somewhere further and earns a chevron.
	 * It is a mark, not a mechanism: the row is one link like the others, and
	 * the second level is the user's to build if they want it.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function resource_rows() {
		return [
			[ 'label' => __( 'Support', 'essential-addons-for-elementor-lite' ) ],
			[ 'label' => __( 'Client handbook', 'essential-addons-for-elementor-lite' ) ],
			[ 'label' => __( 'Video Tutorial', 'essential-addons-for-elementor-lite' ) ],
			[ 'label' => __( 'Customer', 'essential-addons-for-elementor-lite' ) ],
			[ 'label' => __( 'Blog', 'essential-addons-for-elementor-lite' ) ],
			[ 'label' => __( 'Portfolio', 'essential-addons-for-elementor-lite' ) ],
		];
	}

	/**
	 * One row of the Resources dropdown.
	 *
	 * The link and the hover wash live on the container, so the whole strip is
	 * the target rather than just the words in it — the same arrangement the
	 * service rows use, and for the same reason.
	 *
	 * @since 6.8.4
	 *
	 * @param array $row     Label, and whether it leads further.
	 * @param bool  $divider Whether the row is ruled off from the one below.
	 *
	 * @return array
	 */
	protected static function dropdown_row( $row, $divider ) {
		$settings = [
			'content_width'         => 'full',
			'flex_direction'        => 'row',
			'flex_align_items'      => 'center',
			'flex_justify_content'  => 'space-between',
			'flex_wrap'             => 'nowrap',
			'flex_gap'              => Elements::gap( 12 ),
			'padding'               => Elements::spacing( 15, 20, 15, 20 ),
			'padding_tablet'        => Elements::spacing( 14, 11, 14, 11 ),
			'link'                  => Elements::link(),
			'background_hover_background' => 'classic',
			'background_hover_color'      => self::WASH,
			'background_hover_transition' => Elements::size( 0.2 ),
			'_title'                => $row['label'],
		];

		if ( $divider ) {
			$settings['border_border'] = 'solid';
			$settings['border_width']  = Elements::spacing( 0, 0, 1, 0 );
			$settings['border_color']  = self::LINE;
		}

		$children = [
			Elements::widget(
				'heading',
				[
					'title'                  => $row['label'],
					// Not a heading tag: this is a label on a link in a menu,
					// and a theme's `h*` top margin would push it off centre
					// against the chevron beside it.
					'header_size'            => 'div',
					'title_color'            => self::INK,
					'typography_typography'  => 'custom',
					'typography_font_size'   => Elements::size( 16 ),
					'typography_font_weight' => '500',
					'typography_line_height' => Elements::size( 1.2, 'em' ),
					'_flex_size'             => 'none',
				]
			),
		];

		if ( ! empty( $row['submenu'] ) ) {
			$children[] = Elements::widget(
				'icon',
				[
					'selected_icon' => Elements::icon( 'fas fa-chevron-down' ),
					'view'          => 'default',
					'primary_color' => self::LINE,
					'size'          => Elements::size( 12 ),
					'align'         => 'center',
					'_flex_size'    => 'none',
					// Icon has no control for the line box its wrapper draws
					// the glyph inside, and that box is a theme's line-height
					// taller than the glyph — see the widget's own stylesheet.
					// Without this the chevron sits above the label it belongs
					// to instead of level with it.
					'_css_classes'  => 'eael-mm-cta-icon',
					'_title'        => __( 'Chevron', 'essential-addons-for-elementor-lite' ),
				]
			);
		}

		return Elements::container( $settings, $children );
	}

	/* ---------------------------------------------------------------------
	 * Service rows.
	 * ------------------------------------------------------------------ */

	/**
	 * The first column's offerings.
	 *
	 * Font Awesome 5 Free throughout, and nothing outside it: a preset that
	 * shipped eleven drawings of its own would be eleven files in the plugin for
	 * one layout, and the icon control the user opens to change one only offers
	 * the libraries that are loaded anyway.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function enterprise_rows() {
		return [
			[
				'icon'  => 'fas fa-chart-pie',
				'title' => __( 'SEO Audit', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Identify & fix SEO issues to improve search visibility and drive traffic.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'  => 'fas fa-percentage',
				'title' => __( 'UX Audit', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Evaluate your site\'s user experience to uncover usability issues.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'  => 'fas fa-rocket',
				'title' => __( 'Webpage Optimization', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Enhance page speed, performance, and responsiveness.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'  => 'fas fa-vial',
				'title' => __( 'Unit Testing', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Ensure code reliability for bug-free, scalable development.', 'essential-addons-for-elementor-lite' ),
			],
		];
	}

	/**
	 * The second column's offerings.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function brand_rows() {
		return [
			[
				'icon'  => 'fas fa-bolt',
				'title' => __( 'AI Solutions', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Leverage AI-driven tools to unlock smarter business insights.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'  => 'fas fa-th-large',
				'title' => __( 'Enterprise Solutions', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Scalable, secure, and custom-tailored software built.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'  => 'fas fa-cube',
				'title' => __( 'CRM Integration', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Streamline your customer relationships that centralize data.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'  => 'fas fa-envelope',
				'title' => __( 'Email Automation', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Automate targeted email campaigns to boost engagement.', 'essential-addons-for-elementor-lite' ),
			],
		];
	}

	/**
	 * The third column's destinations.
	 *
	 * The two brand glyphs are Font Awesome's own, from the library Elementor
	 * already loads — a Slack row with a generic chat bubble on it is a row that
	 * does not say Slack.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function touch_rows() {
		return [
			[
				'icon'  => 'fas fa-comment-dots',
				'title' => __( 'Contact', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Share your concerns, updates & reviews with us', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'    => 'fab fa-slack',
				'library' => 'fa-brands',
				'title'   => __( 'Slack Community', 'essential-addons-for-elementor-lite' ),
				'text'    => __( 'Join our dedicated Slack channel and get live updates', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'    => 'fab fa-discord',
				'library' => 'fa-brands',
				'title'   => __( 'Discord', 'essential-addons-for-elementor-lite' ),
				'text'    => __( 'Connect with us in our new room and follow up all details', 'essential-addons-for-elementor-lite' ),
			],
		];
	}

	/**
	 * A column's rows, ruled between but not below.
	 *
	 * The last row never carries a divider: the panel's own edge is already
	 * there, and a border on top of it draws the line twice.
	 *
	 * @since 6.8.4
	 *
	 * @param array $rows  Row definitions.
	 * @param bool  $ruled Whether the rows are divided and light up under the
	 *                     pointer. The third column is a quiet list and does
	 *                     neither.
	 *
	 * @return array
	 */
	protected static function rows( $rows, $ruled ) {
		$out  = [];
		$last = count( $rows ) - 1;

		foreach ( $rows as $index => $row ) {
			$out[] = self::feature_row( $row, $ruled && $index !== $last, $ruled );
		}

		return $out;
	}

	/**
	 * One service row.
	 *
	 * The link and the hover wash live on the container rather than on the Icon
	 * Box: a row a visitor can only click on its title is a row that misses most
	 * of the pointer's travel, and Icon Box has no hover background of its own
	 * to light the whole strip with.
	 *
	 * @since 6.8.4
	 *
	 * @param array $row     Icon, title and text.
	 * @param bool  $divider Whether the row is ruled off from the one below.
	 * @param bool  $lit     Whether the row lights up under the pointer.
	 *
	 * @return array
	 */
	protected static function feature_row( $row, $divider, $lit ) {
		$settings = [
			'content_width'  => 'full',
			'flex_direction' => 'column',
			// 24, and the row lands on the design's 120: 21.6 of title, 10 of
			// gap, two 20.25 lines of copy, and this either side. Every one of
			// those is a control, which is the point — the height is the sum of
			// the type, not a number typed into a Min Height.
			'padding'        => Elements::spacing( 24, 24, 24, 18 ),
			// Collapsed, the inset that keeps a row off a *divider* is gone and
			// the one that keeps it off the screen is what is needed. 11 is not
			// a round number picked by eye: the dropdown's own 10 plus the
			// card's 1px edge plus this is 22, which is where the menu items
			// above sit, so the panel's text lines up with the labels it opened
			// from.
			'padding_tablet' => Elements::spacing( 20, 11, 20, 11 ),
			'link'           => Elements::link(),
			'_title'         => $row['title'],
		];

		if ( $lit ) {
			$settings['background_hover_background'] = 'classic';
			$settings['background_hover_color']      = self::SURFACE;
			$settings['background_hover_transition'] = Elements::size( 0.2 );
		} else {
			// The quiet column still answers the pointer, just with the wash the
			// other two use at rest rather than the white they light up to —
			// this column is already white, so lighting it would do nothing.
			$settings['background_hover_background'] = 'classic';
			$settings['background_hover_color']      = self::WASH;
			$settings['background_hover_transition'] = Elements::size( 0.2 );
		}

		if ( $divider ) {
			$settings['border_border'] = 'solid';
			$settings['border_width']  = Elements::spacing( 0, 0, 1, 0 );
			$settings['border_color']  = self::LINE;
		}

		return Elements::container( $settings, [ self::icon_box( $row ) ] );
	}

	/**
	 * The glyph, name and sentence of one row.
	 *
	 * @since 6.8.4
	 *
	 * @param array $row Icon, optional library, title and text.
	 *
	 * @return array
	 */
	protected static function icon_box( $row ) {
		$library = isset( $row['library'] ) ? $row['library'] : 'fa-solid';

		return Elements::widget(
			'icon-box',
			[
				'selected_icon'             => Elements::icon( $row['icon'], $library ),
				'title_text'                => $row['title'],
				'description_text'          => $row['text'],
				// `div`, not a heading tag, and for two reasons that agree.
				// Themes give `h1`-`h6` a top margin — 8px on the one measured
				// here — and Icon Box's Content Spacing control only writes the
				// *bottom* one, so a heading tag puts a gap above every title
				// that nothing in the panel can reach. And these are labels on
				// links in a navigation menu, not document structure: eleven of
				// them in the page outline is eleven headings a screen reader
				// announces between the real ones.
				'title_size'                => 'div',
				// `inline-start`, not `left`: the control writes a logical
				// value, so the icon stays on the reading side in RTL.
				//
				// Repeated for mobile because Icon Box ships a `mobile_default`
				// of `block-start` — the icon jumps above the text on a phone
				// unless the mobile value is set too, and eleven rows that each
				// grow a line taller is an accordion nobody scrolls to the end
				// of. Inline is also simply better here: a 26px glyph beside two
				// lines of copy fits a 390px screen without help.
				'position'                  => 'inline-start',
				'position_mobile'           => 'inline-start',
				'content_vertical_alignment' => 'top',
				'text_align'                => 'start',
				// The row's own container is the link; a link on the widget too
				// would nest one anchor inside another.
				'view'                      => 'default',

				// Icon.
				'primary_color'             => self::LINE,
				'hover_primary_color'       => self::INK,
				'icon_size'                 => Elements::size( 26 ),
				'icon_size_tablet'          => Elements::size( 22 ),
				'icon_space'                => Elements::size( 30 ),
				'icon_space_tablet'         => Elements::size( 18 ),

				// Title.
				'title_color'               => self::INK,
				'hover_title_color'         => self::INK,
				'title_typography_typography' => 'custom',
				'title_typography_font_size'  => Elements::size( 18 ),
				'title_typography_font_size_tablet' => Elements::size( 17 ),
				'title_typography_font_weight' => '600',
				'title_typography_line_height' => Elements::size( 1.2, 'em' ),
				'title_bottom_space'        => Elements::size( 10 ),

				// Description.
				'description_color'         => self::MUTED,
				'description_typography_typography'  => 'custom',
				'description_typography_font_size'   => Elements::size( 15 ),
				'description_typography_font_size_tablet' => Elements::size( 14 ),
				'description_typography_font_weight' => '400',
				// 1.35, not the 1.5 that reads well in body copy: two lines of
				// it plus the title is what makes a row 120 tall, and a row
				// taller than that stops the third column — three rows and the
				// social tiles — from ending level with the four beside it.
				'description_typography_line_height' => Elements::size( 1.35, 'em' ),
			]
		);
	}

	/**
	 * The social accounts under the third column.
	 *
	 * One Social Icons widget rather than five linked tiles: the repeater's
	 * picker is already filtered to brand marks, and the plate, the glyph and
	 * both hover colours are its own controls.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function social_row() {
		$networks = [ 'fab fa-pinterest', 'fab fa-linkedin', 'fab fa-youtube', 'fab fa-instagram', 'fab fa-facebook-f' ];

		$rows = [];

		foreach ( $networks as $network ) {
			$rows[] = Elements::row( [
				'social_icon' => Elements::icon( $network, 'fa-brands' ),
				'link'        => Elements::link(),
			] );
		}

		return Elements::container(
			[
				'content_width'  => 'full',
				'flex_direction' => 'column',
				'padding'        => Elements::spacing( 27, 20, 29, 28 ),
				'padding_tablet' => Elements::spacing( 20, 11, 20, 11 ),
				// The lighter pen again: this closes the column's list rather
				// than dividing two rows of it.
				'border_border'  => 'solid',
				'border_width'   => Elements::spacing( 1, 0, 0, 0 ),
				'border_color'   => self::SOFT_LINE,
				'_flex_size'     => 'none',
				'_title'         => __( 'Social', 'essential-addons-for-elementor-lite' ),
			],
			[
				Elements::widget(
					'social-icons',
					[
						'social_icon_list'       => $rows,
						// Square, and squared off: the design's tiles have no
						// corner radius at all, which is the same decision the
						// panel's own edge makes.
						'shape'                  => 'square',
						'border_radius'          => Elements::spacing( 0, 0, 0, 0 ),
						'columns'                => '5',
						'align'                  => 'left',
						// Custom, or every tile arrives in its network's own
						// brand colour and the row stops being part of the
						// design.
						'icon_color'             => 'custom',
						'icon_primary_color'     => self::TILE,
						'icon_secondary_color'   => self::LINE,
						'hover_primary_color'    => self::INK,
						'hover_secondary_color'  => self::ON_DARK,
						// A 64px tile: Elementor sizes the glyph box at exactly
						// `1em`, so the plate is the font size plus twice the
						// padding and nothing else decides it.
						'icon_size'              => Elements::size( 24 ),
						'icon_size_tablet'       => Elements::size( 20 ),
						'icon_padding'           => Elements::size( 20 ),
						'icon_padding_tablet'    => Elements::size( 16 ),
						'icon_spacing'           => Elements::size( 8 ),
						'row_gap'                => Elements::size( 8 ),
					]
				),
			]
		);
	}
}

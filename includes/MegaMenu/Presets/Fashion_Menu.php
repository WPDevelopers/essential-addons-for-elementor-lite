<?php
/**
 * The Fashion Store preset.
 *
 * @package Essential_Addons_Elementor
 * @since   6.8.4
 */

namespace Essential_Addons_Elementor\MegaMenu\Presets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Essential_Addons_Elementor\Elements\Mega_Menu_Products;
use Essential_Addons_Elementor\MegaMenu\Manager;
use Essential_Addons_Elementor\Theme_Builder\Presets\Elements;

/**
 * A shop menu: three bands of catalogue under one rule, with the storefront's
 * best sellers on the end of it.
 *
 * One panel, not the two the other presets carry, because a clothing shop's
 * menu is not a set of destinations — it is the catalogue itself, and it is
 * read across rather than down:
 *
 * - **Newly Added** and **Style** — two columns of plain links, the arrivals
 *   beside the aesthetics. Core's Icon List with the icons switched off, so
 *   every label and every href stays a control.
 * - **Popular Category** — seven departments, each an icon and a two-line name,
 *   in two columns of the same widget.
 * - **Most Selling Products** — the Mega Menu's own Menu Products widget, with
 *   its Filter By / category / tag / count controls. See {@see products()}.
 *
 * ## The rule is three borders that happen to line up
 *
 * The design's hairline runs unbroken under all three headings, which a single
 * band above the columns would draw in one border and nothing would have to be
 * talked into meeting. It is three instead, one per column, and that is a
 * deliberate trade: a band keeps its headings at the top of the panel when the
 * columns stack, orphaned from what they name. See {@see cell()} for what makes
 * the three read as one line, and for why the vertical dividers start at the
 * rule rather than running up through the headings.
 *
 * ## Nothing here is somebody else's element
 *
 * Every widget this preset emits is either Elementor core — Icon List, Heading,
 * Image, Icon — or the Mega Menu's own **Menu Products**, which is registered by
 * the menu itself. Nothing is contingent on a second element being ticked in
 * EA's settings, so no column of this layout can arrive empty because of a
 * checkbox somewhere else.
 *
 * ## WooCommerce is optional, the design is not
 *
 * Three of the four columns read the shop when there is one and fall back to
 * the design's own labels when there is not — see the *Shop data* section
 * below. The fourth is Menu Products, which does the same thing for itself. A
 * preset that hid a quarter of its own layout on a site without a shop would be
 * a preset that arrives broken; one that hard-codes fake products on a site
 * *with* a shop is worse.
 *
 * @since 6.8.4
 */
class Fashion_Menu {

	/**
	 * The label under the pointer, and a price.
	 */
	const INK = '#444444';

	/**
	 * Resting link labels, category names, product titles.
	 */
	const MUTED = '#848484';

	/**
	 * A column heading — lighter than everything it introduces, and carried by
	 * its letter-spacing rather than by its weight.
	 */
	const HEADING = '#A1A1A1';

	/**
	 * The header bar's own ink: the wordmark, the menu items, the call to
	 * action's label and the surface of the chip beside "Sale".
	 */
	const BAR_INK = '#686868';

	/**
	 * Panel surface.
	 */
	const SURFACE = '#FFFFFF';

	/**
	 * The header bar, a shade off the panel below it — which is the only thing
	 * separating the two, since the design draws no line between them.
	 */
	const BAR = '#F1F1F1';

	/**
	 * Hairlines — the rule under the headings, the dividers between columns.
	 */
	const LINE = '#EDEDED';

	/**
	 * The call to action's surface.
	 */
	const BUTTON = '#DADADA';

	/**
	 * The call to action's surface with the pointer on it. The design draws no
	 * hover state; this is a half step, because a pill that does not answer the
	 * pointer at all reads as a label.
	 */
	const BUTTON_HOVER = '#CFCFCF';

	/**
	 * The "NEW" chip's label, on {@see BAR_INK}.
	 */
	const BADGE_INK = '#FFFFFF';

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
				'content_width'         => 'boxed',
				'flex_direction'        => 'row',
				'flex_align_items'      => 'center',
				'flex_justify_content'  => 'space-between',
				'flex_wrap'             => 'nowrap',
				'flex_gap'              => Elements::gap( 24 ),
				'flex_gap_tablet'       => Elements::gap( 16 ),
				'flex_gap_mobile'       => Elements::gap( 8 ),
				'padding'               => Elements::spacing( 24, 24, 24, 24 ),
				'padding_tablet'        => Elements::spacing( 18, 20, 18, 20 ),
				'padding_mobile'        => Elements::spacing( 14, 16, 14, 16 ),
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
	 * The site's own mark rather than artwork of the preset's: a header only
	 * feels ready-made when it comes up already wearing the site it landed on.
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
				'title'                       => get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : __( 'FashionFit', 'essential-addons-for-elementor-lite' ),
				'header_size'                 => 'h2',
				'link'                        => Elements::link( home_url( '/' ) ),
				// A linked heading inherits the theme's link colour, which is
				// rarely the right ink for a site name.
				'title_color'                 => self::BAR_INK,
				'typography_typography'       => 'custom',
				'typography_font_size'        => Elements::size( 23 ),
				'typography_font_size_tablet' => Elements::size( 20 ),
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
				'width'                       => Elements::size( 46, '%' ),
				'width_tablet'                => Elements::size( 42, '%' ),
				'width_mobile'                => Elements::size( 38, '%' ),
				'flex_direction'              => 'row',
				'flex_align_items'            => 'center',
				'flex_justify_content'        => 'flex-end',
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
				'width'                => Elements::size( 28, '%' ),
				'width_tablet'         => Elements::size( 30, '%' ),
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'flex-end',
				'flex_wrap'            => 'nowrap',
				'padding'              => Elements::spacing( 0, 0, 0, 0 ),
				// Out on a phone, column and all. "Shop the Collection" plus its
				// disc is most of a small screen, and what has to fit there is
				// the wordmark and the menu toggle; the collection is one tap
				// away inside the menu. Hiding the pill alone would leave the
				// column holding its width and push the toggle into the middle
				// of the bar. Left in on tablet, where there is room for it.
				'hide_mobile'          => 'hidden-mobile',
				'_title'               => __( 'Actions', 'essential-addons-for-elementor-lite' ),
			],
			[ self::cta_pill() ]
		);
	}

	/**
	 * "Shop the Collection" — a label and a disc, inside one pill.
	 *
	 * A linked container rather than a Button widget, because the design closes
	 * the pill with a filled circle around the arrow and no button control
	 * draws one: Button's icon is a glyph beside the text, and the Creative
	 * Button's is the same. Two widgets inside a container that is itself the
	 * link gives the disc its own colour, its own size and its own padding, all
	 * from controls the user already knows.
	 *
	 * Neither child is linked, deliberately — an `<a>` inside an `<a>` is
	 * invalid, and the container is already the target.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function cta_pill() {
		return Elements::container(
			[
				'content_width'               => 'full',
				// Sized to what is in it. A full-width container is `--width:
				// 100%` of whatever holds it, so without this the pill stretches
				// to the whole Actions column and the label and the disc drift
				// apart inside it — which is not padding going wrong, it is the
				// box being the wrong size. `custom` writes the value through
				// verbatim; `{{UNIT}}` resolves to nothing for that unit.
				'width'                       => [ 'unit' => 'custom', 'size' => 'fit-content' ],
				'flex_direction'              => 'row',
				'flex_align_items'            => 'center',
				'flex_justify_content'        => 'center',
				'flex_wrap'                   => 'nowrap',
				'flex_gap'                    => Elements::gap( 13 ),
				// 44 in the design, and set as a floor rather than reached with
				// vertical padding on purpose. Elementor's Icon widget wraps its
				// 26px disc in a block that inherits the theme's line-height —
				// 32.4px on the theme this was measured against — so a padding
				// that gives 44 on one site gives 50 on the next. A floor plus
				// centring puts the disc 9 from each edge whatever that wrapper
				// turns out to be, which is what the design draws.
				'min_height'                  => Elements::size( 44 ),
				// Measured off the design: a 198x44 pill reconciles exactly as 21
				// of left inset, 129 of label, 13 of gap, a 26 disc and 9 to the
				// right edge. Only the label's side is opened up — it is text
				// against a rounded end, where the disc is a circle already
				// carrying its own inset. Nothing vertical: that is the min
				// height above, and centring.
				'padding'                     => Elements::spacing( 0, 9, 0, 21 ),
				'padding_mobile'              => Elements::spacing( 0, 8, 0, 16 ),
				// Past half the pill's height, so the ends are semicircles at
				// whatever height the label makes it.
				'border_radius'               => Elements::spacing( 68, 68, 68, 68 ),
				'background_background'       => 'classic',
				'background_color'            => self::BUTTON,
				'background_hover_background' => 'classic',
				'background_hover_color'      => self::BUTTON_HOVER,
				'background_hover_transition' => Elements::size( 0.2 ),
				'link'                        => Elements::link(),
				// A flex child in a row container grows into whatever is left; a
				// stretched pill stops reading as a button.
				'_flex_size'                  => 'none',
				'_title'                      => __( 'Shop the Collection', 'essential-addons-for-elementor-lite' ),
			],
			[
				Elements::widget(
					'heading',
					[
						'title'                       => __( 'Shop the Collection', 'essential-addons-for-elementor-lite' ),
						'header_size'                 => 'span',
						'title_color'                 => self::BAR_INK,
						'typography_typography'       => 'custom',
						'typography_font_size'        => Elements::size( 16 ),
						'typography_font_size_mobile' => Elements::size( 14 ),
						'typography_font_weight'      => '500',
						'typography_line_height'      => Elements::size( 1.2, 'em' ),
						'_flex_size'                  => 'none',
					]
				),
				Elements::widget(
					'icon',
					[
						'selected_icon' => Elements::icon( 'fas fa-arrow-right' ),
						// The filled disc. In this view `primary_color` is the
						// plate and `secondary_color` the glyph on it — the other
						// way round from the default view, where there is no
						// plate and `primary_color` is the glyph.
						'view'          => 'stacked',
						'shape'         => 'circle',
						'primary_color' => self::BAR_INK,
						'secondary_color' => self::SURFACE,
						'size'          => Elements::size( 12 ),
						// A 26px disc, which is what the design draws. Elementor
						// sizes the glyph box at exactly `1em` square, so the
						// diameter is the font size plus twice this and nothing
						// else decides it: 12 + 7 + 7.
						'icon_padding'  => Elements::size( 7 ),
						'align'         => 'center',
						'_flex_size'    => 'none',
						// The one class this preset sets. Icon has no control
						// for the line box its wrapper draws the plate inside,
						// and that box is a theme's line-height taller than the
						// plate — see the widget's own stylesheet. Strip it and
						// the button still works, the disc just rides high.
						'_css_classes'  => 'eael-mm-cta-icon',
						'_title'        => __( 'Arrow', 'essential-addons-for-elementor-lite' ),
					]
				),
			]
		);
	}

	/* ---------------------------------------------------------------------
	 * Menu items.
	 * ------------------------------------------------------------------ */

	/**
	 * The menu items, in bar order.
	 *
	 * One of the four opens a panel. The other three are one control away from
	 * doing the same — the nested container each already owns is waiting for it.
	 *
	 * @since 6.8.4
	 *
	 * @return array List of item definitions.
	 */
	protected static function item_map() {
		return [
			[
				'label' => __( 'Home', 'essential-addons-for-elementor-lite' ),
				'type'  => 'link',
			],
			[
				'label' => __( 'Shop', 'essential-addons-for-elementor-lite' ),
				'type'  => 'mega',
			],
			[
				// The chip rides in the label because the widget prints it
				// through `wp_kses_post()` and has no badge control of its own —
				// see {@see new_badge()}. `title` is that label without it: the
				// Navigator prints a panel's title as text, and markup in it
				// would read as "SaleNEW".
				'label' => __( 'Sale', 'essential-addons-for-elementor-lite' ) . self::new_badge(),
				'title' => __( 'Sale', 'essential-addons-for-elementor-lite' ),
				'type'  => 'link',
			],
			[
				'label' => __( 'Offer', 'essential-addons-for-elementor-lite' ),
				'type'  => 'link',
			],
		];
	}

	/**
	 * The "NEW" chip beside the Sale item.
	 *
	 * Inline styles, not a class: the item label is printed through
	 * `wp_kses_post()`, so the chip can live in the field the user already
	 * edits, and keeping it self-contained means it needs nothing added to the
	 * widget's stylesheet. Delete it from the label and the item is a plain
	 * link again.
	 *
	 * @since 6.8.4
	 *
	 * @return string
	 */
	protected static function new_badge() {
		return sprintf(
			'<span style="display:inline-block;margin-inline-start:8px;padding:3px 6px;border-radius:4px;background:%1$s;color:%2$s;font-size:11px;font-weight:700;letter-spacing:0.5px;line-height:1;vertical-align:middle;">%3$s</span>',
			self::BAR_INK,
			self::BADGE_INK,
			esc_html__( 'NEW', 'essential-addons-for-elementor-lite' )
		);
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
				// Viewport, not a pixel width: the design's panel runs the full
				// width of the page, and a fixed width is an overflow waiting
				// for the first laptop narrower than it — the widget writes a
				// custom width through verbatim with nothing clamping it. The
				// card inside is boxed, so the columns still line up with the
				// header bar at whatever width the site is read at.
				$row['eael_mega_menu_item_submenu_width'] = 'viewport';
				$row['eael_mega_menu_item_panel_align']   = 'center';
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
			'eael_mega_menu_align'              => 'flex-end',
			'eael_mega_menu_indicator_icon'     => Elements::icon( 'fas fa-chevron-down' ),

			// Responsive. Tablet, not mobile: the panel is three columns side by
			// side and none of them survives a tablet in portrait.
			'eael_mega_menu_breakpoint'         => 'tablet',
			'eael_mega_menu_toggle_text'        => '',
			'eael_mega_menu_toggle_full_width'  => 'yes',

			// Menu bar — transparent, because it sits on the header's surface.
			'eael_mega_menu_bar_background_background' => 'classic',
			'eael_mega_menu_bar_background_color'      => 'rgba(0,0,0,0)',
			'eael_mega_menu_bar_gap'                   => Elements::size( 8 ),
			'eael_mega_menu_bar_padding'               => Elements::spacing( 0, 0, 0, 0 ),

			// Items.
			'eael_mega_menu_item_typography_typography'  => 'custom',
			'eael_mega_menu_item_typography_font_size'   => Elements::size( 16 ),
			'eael_mega_menu_item_typography_font_weight' => '500',
			// The widget's stylesheet already resets this; the control writes it
			// again at Elementor's own specificity, which is what a theme
			// reaching past three classes runs into.
			'eael_mega_menu_item_typography_text_decoration' => 'none',
			'eael_mega_menu_item_padding'                => Elements::spacing( 10, 12, 10, 12 ),
			'eael_mega_menu_item_radius'                 => Elements::spacing( 8, 8, 8, 8 ),
			'eael_mega_menu_item_color'                  => self::BAR_INK,
			'eael_mega_menu_item_bg'                     => 'rgba(0,0,0,0)',
			// The bar does not recolour. Hovering "Shop" opens a panel below it
			// and a row inside that panel is already lighting up under the
			// pointer; a second highlight on the label above competes with the
			// one the visitor is reading. The indicator rotating is the
			// acknowledgement, and it is enough.
			'eael_mega_menu_item_color_hover'            => self::BAR_INK,
			'eael_mega_menu_item_bg_hover'               => 'rgba(0,0,0,0)',
			'eael_mega_menu_item_color_active'           => self::BAR_INK,
			'eael_mega_menu_item_bg_active'              => 'rgba(0,0,0,0)',

			// Indicator.
			'eael_mega_menu_indicator_size'         => Elements::size( 11 ),
			'eael_mega_menu_indicator_gap'          => Elements::size( 6 ),
			'eael_mega_menu_indicator_rotate'       => 'yes',
			'eael_mega_menu_indicator_color'        => self::BAR_INK,
			'eael_mega_menu_indicator_color_active' => self::BAR_INK,

			// Panel. One panel, so unlike the other presets the surface is
			// painted here rather than on a card inside: the design runs the
			// white full-bleed across the page while the columns on it stay
			// boxed, and only the panel is that wide.
			'eael_mega_menu_panel_background_background' => 'classic',
			'eael_mega_menu_panel_background_color'      => self::SURFACE,
			'eael_mega_menu_panel_radius'                => Elements::spacing( 0, 0, 0, 0 ),
			'eael_mega_menu_panel_padding'               => Elements::spacing( 0, 0, 0, 0 ),
			'eael_mega_menu_panel_shadow_box_shadow_type' => 'yes',
			'eael_mega_menu_panel_shadow_box_shadow'     => Elements::shadow( 18, 40, 'rgba(16, 24, 40, 0.10)' ),
			// Flush with the bar: the design draws no gap, and the bar's own
			// wash is what separates the two.
			'eael_mega_menu_panel_offset'                => Elements::size( 0 ),
			'eael_mega_menu_panel_z_index'               => 999,

			// Mobile toggle.
			'eael_mega_menu_toggle_align'       => 'flex-end',
			'eael_mega_menu_toggle_icon_size'   => Elements::size( 18 ),
			'eael_mega_menu_toggle_padding'     => Elements::spacing( 9, 11, 9, 11 ),
			'eael_mega_menu_toggle_radius'      => Elements::spacing( 8, 8, 8, 8 ),
			'eael_mega_menu_toggle_color'       => self::BAR_INK,
			'eael_mega_menu_toggle_bg'          => self::BUTTON,
			'eael_mega_menu_toggle_color_hover' => self::SURFACE,
			'eael_mega_menu_toggle_bg_hover'    => self::BAR_INK,

			// Collapsed dropdown. The accordion opens inside it and the panel is
			// a catalogue, so scrolling it beats clipping it.
			'eael_mega_menu_dropdown_background_background' => 'classic',
			'eael_mega_menu_dropdown_background_color'      => self::SURFACE,
			'eael_mega_menu_dropdown_radius'                => Elements::spacing( 12, 12, 12, 12 ),
			'eael_mega_menu_dropdown_padding'               => Elements::spacing( 10, 10, 10, 10 ),
			'eael_mega_menu_dropdown_shadow_box_shadow_type' => 'yes',
			'eael_mega_menu_dropdown_shadow_box_shadow'     => Elements::shadow( 16, 40, 'rgba(16, 24, 40, 0.16)' ),
			// Clear of the header bar. The sheet hangs off the menu widget,
			// which sits inside a bar with its own padding — without this it
			// opens over the last of that padding.
			'eael_mega_menu_dropdown_offset'                => Elements::size( 24 ),
			'eael_mega_menu_dropdown_max_height'            => Elements::size( 75, 'vh' ),
		];
	}

	/* ---------------------------------------------------------------------
	 * Panels.
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

		foreach ( self::item_map() as $index => $item ) {
			if ( 1 === $index ) {
				$panels[] = self::shop_panel( __( 'Shop', 'essential-addons-for-elementor-lite' ) );
				continue;
			}

			$panels[] = Elements::nested_child( [
				'content_width' => 'full',
				'_title'        => isset( $item['title'] ) ? $item['title'] : $item['label'],
			] );
		}

		return $panels;
	}

	/**
	 * The Shop panel.
	 *
	 * The panel itself is the full-bleed white surface; this is the boxed card
	 * of content on it, so the columns line up with the header bar above rather
	 * than with the edges of the screen.
	 *
	 * @since 6.8.4
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function shop_panel( $title ) {
		return Elements::nested_child(
			[
				'content_width'  => 'full',
				'flex_direction' => 'column',
				'_title'         => $title,
			],
			[
				Elements::container(
					[
						'content_width'    => 'boxed',
						'flex_direction'   => 'row',
						'flex_wrap'        => 'nowrap',
						// Below the menu's own breakpoint the panel is inside the
						// collapsed dropdown, where three columns side by side
						// are three columns of about a hundred pixels each.
						'flex_wrap_tablet' => 'wrap',
						// What carries the dividers: the columns are as tall as
						// the tallest of them, so the hairline on the first two
						// reaches the bottom of the panel however they fill up.
						'flex_align_items' => 'stretch',
						'flex_gap'         => Elements::gap( 0 ),
						'padding'          => Elements::spacing( 0, 0, 0, 0 ),
						'_title'           => __( 'Panel', 'essential-addons-for-elementor-lite' ),
					],
					[
						self::cell(
							'31.4%',
							[ self::heading_pair() ],
							[ self::links_columns() ],
							0,
							24,
							true,
							__( 'Newly Added / Style', 'essential-addons-for-elementor-lite' )
						),
						self::cell(
							'33.2%',
							[ self::column_heading( __( 'Popular Category', 'essential-addons-for-elementor-lite' ) ) ],
							[ self::category_columns() ],
							44,
							24,
							true,
							__( 'Popular Category', 'essential-addons-for-elementor-lite' )
						),
						self::cell(
							'35.4%',
							[ self::column_heading( __( 'Most Selling Products', 'essential-addons-for-elementor-lite' ) ) ],
							[ self::products() ],
							44,
							0,
							false,
							__( 'Most Selling Products', 'essential-addons-for-elementor-lite' )
						),
					]
				),
			]
		);
	}

	/**
	 * One column of the panel: a heading, the rule under it, and the content.
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
	 * The divider is the *content's* right edge rather than the column's, so it
	 * starts at the rule instead of running up through the heading — and the
	 * content is set to grow, so it reaches the bottom of a column stretched to
	 * match its tallest neighbour.
	 *
	 * @since 6.8.4
	 *
	 * @param string $width   Width, as a percentage string.
	 * @param array  $heading The heading strip's children.
	 * @param array  $content The column's children.
	 * @param int    $left    Inset from the divider on the left, in pixels.
	 * @param int    $right   Inset from the divider on the right, in pixels.
	 * @param bool   $divider Whether the column closes with a hairline.
	 * @param string $title   Navigator title.
	 *
	 * @return array
	 */
	protected static function cell( $width, $heading, $content, $left, $right, $divider, $title ) {
		$body = [
			'content_width'  => 'full',
			'flex_direction' => 'column',
			'flex_gap'       => Elements::gap( 0 ),
			'padding'        => Elements::spacing( 34, $right, 40, $left ),
			// Collapsed, the panel is inside the dropdown and the columns are
			// stacked, so the inset that keeps text off a *divider* is gone and
			// the one that keeps it off the *screen* is what is needed. 12 is
			// not a round number picked by eye: the menu items above sit at 12
			// inside the dropdown's own 10, so this is what lines the panel's
			// text up with the labels it opened from.
			'padding_tablet' => Elements::spacing( 20, 12, 24, 12 ),
			// Fills the height the column was stretched to, which is the only
			// reason the divider below reaches the foot of the panel.
			'_flex_size'     => 'grow',
			'_title'         => __( 'Content', 'essential-addons-for-elementor-lite' ),
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
				'_title'         => $title,
			],
			[
				Elements::container(
					[
						'content_width'  => 'full',
						'flex_direction' => 'row',
						'flex_wrap'      => 'nowrap',
						'flex_gap'       => Elements::gap( 0 ),
						'padding'        => Elements::spacing( 30, $right, 26, $left ),
						'padding_tablet' => Elements::spacing( 22, 12, 14, 12 ),
						'border_border'  => 'solid',
						'border_width'   => Elements::spacing( 0, 0, 1, 0 ),
						'border_color'   => self::LINE,
						'_title'         => __( 'Heading', 'essential-addons-for-elementor-lite' ),
					],
					$heading
				),
				Elements::container( $body, $content ),
			]
		);
	}

	/**
	 * The first column's two headings, side by side over its two link lists.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function heading_pair() {
		return Elements::container(
			[
				'content_width'  => 'full',
				'flex_direction' => 'row',
				'flex_wrap'      => 'nowrap',
				'flex_gap'       => Elements::gap( 0 ),
				'padding'        => Elements::spacing( 0, 0, 0, 0 ),
			],
			[
				self::column( '46%', [ self::column_heading( __( 'Newly Added', 'essential-addons-for-elementor-lite' ) ) ] ),
				self::column( '54%', [ self::column_heading( __( 'Style', 'essential-addons-for-elementor-lite' ) ) ] ),
			]
		);
	}

	/**
	 * The arrivals beside the aesthetics.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function links_columns() {
		return Elements::container(
			[
				'content_width'  => 'full',
				'flex_direction' => 'row',
				'flex_wrap'      => 'nowrap',
				'flex_gap'       => Elements::gap( 0 ),
				'padding'        => Elements::spacing( 0, 0, 0, 0 ),
			],
			[
				self::column( '46%', [ self::link_list( self::newly_added() ) ] ),
				self::column( '54%', [ self::link_list( self::styles() ) ] ),
			]
		);
	}

	/**
	 * A plain sub-column.
	 *
	 * @since 6.8.4
	 *
	 * @param string $width    Width, as a percentage string.
	 * @param array  $children Child elements.
	 *
	 * @return array
	 */
	protected static function column( $width, $children ) {
		return Elements::container(
			[
				'content_width'  => 'full',
				'width'          => Elements::size( (float) $width, '%' ),
				'flex_direction' => 'column',
				'padding'        => Elements::spacing( 0, 0, 0, 0 ),
			],
			$children
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
				'typography_letter_spacing' => Elements::size( 2.4 ),
				'typography_line_height'    => Elements::size( 1.2, 'em' ),
			]
		);
	}

	/* ---------------------------------------------------------------------
	 * Catalogue columns.
	 * ------------------------------------------------------------------ */

	/**
	 * The Newly Added rows.
	 *
	 * The shop's newest products when there is a shop, and the design's own
	 * labels when there is not — the column is called Newly Added, and on a
	 * store that is a question the catalogue can answer for itself.
	 *
	 * @since 6.8.4
	 *
	 * @return array Rows of `label` and `link`.
	 */
	protected static function newly_added() {
		$rows = self::recent_products( 6 );

		return $rows ? $rows : self::demo_rows( [
			__( 'Tops', 'essential-addons-for-elementor-lite' ),
			__( 'Coats', 'essential-addons-for-elementor-lite' ),
			__( 'Jackets', 'essential-addons-for-elementor-lite' ),
			__( 'Shoes', 'essential-addons-for-elementor-lite' ),
			__( 'Home Decor', 'essential-addons-for-elementor-lite' ),
			__( 'Jewelry', 'essential-addons-for-elementor-lite' ),
		] );
	}

	/**
	 * The Style rows.
	 *
	 * Product *tags* first: "Modern", "Gothic", "Pop" are the cross-cutting
	 * labels a garment carries alongside the department it sits in, and that is
	 * what a tag is for.
	 *
	 * A shop that does not tag falls to its **sub**-categories, which is the
	 * other place a second axis of the catalogue tends to live, and which the
	 * column beside this one is not already showing — Popular Category takes
	 * only top-level terms. The design's labels are the last resort.
	 *
	 * @since 6.8.4
	 *
	 * @return array Rows of `label` and `link`.
	 */
	protected static function styles() {
		$rows = self::terms( 'product_tag', 6 );

		if ( ! $rows ) {
			// Every category that is somebody's child. `get_terms()` has no
			// "has a parent" argument — `parent` takes one term id — so this
			// asks for a wide slice and drops the ones sitting at the root.
			//
			// Wide on purpose: taking six and *then* filtering would come back
			// empty on any shop whose six busiest categories happen to be
			// top-level ones, which is most of them.
			$children = array_filter(
				self::terms( 'product_cat', 60 ),
				function ( $row ) {
					return ! empty( $row['parent'] );
				}
			);

			$rows = array_slice( array_values( $children ), 0, 6 );
			$rows = count( $rows ) >= self::MIN_ROWS ? $rows : [];
		}

		return $rows ? $rows : self::demo_rows( [
			__( 'Modern', 'essential-addons-for-elementor-lite' ),
			__( 'Gothic', 'essential-addons-for-elementor-lite' ),
			__( 'Pop', 'essential-addons-for-elementor-lite' ),
			__( 'Ethnic', 'essential-addons-for-elementor-lite' ),
			__( 'Traditional', 'essential-addons-for-elementor-lite' ),
			__( 'Western', 'essential-addons-for-elementor-lite' ),
		] );
	}

	/**
	 * Unlinked rows, for a column with no shop behind it.
	 *
	 * `#`, not no link at all: a row without an href is a row the Icon List
	 * prints as a `<span>`, which is neither clickable nor focusable, and the
	 * point of the demo labels is to show what the column will look like once
	 * it is pointed somewhere.
	 *
	 * @since 6.8.4
	 *
	 * @param array $labels Link labels.
	 *
	 * @return array
	 */
	protected static function demo_rows( $labels ) {
		$rows = [];

		foreach ( $labels as $label ) {
			$rows[] = [ 'label' => $label, 'link' => '' ];
		}

		return $rows;
	}

	/**
	 * A column of plain links.
	 *
	 * Core's Icon List with no icon on any row: one widget instead of six, and
	 * the label, the href, the spacing and both colours are already controls on
	 * it. No hover pill either — the design answers the pointer by darkening
	 * the label, which is what `text_color_hover` does on its own.
	 *
	 * @since 6.8.4
	 *
	 * @param array $links Rows of `label` and `link`.
	 *
	 * @return array
	 */
	protected static function link_list( $links ) {
		$rows = [];

		foreach ( $links as $link ) {
			$rows[] = Elements::row( [
				'text'          => $link['label'],
				'selected_icon' => Elements::icon( '' ),
				'link'          => Elements::link( '' !== $link['link'] ? $link['link'] : '#' ),
			] );
		}

		return Elements::widget(
			'icon-list',
			[
				'view'                        => 'traditional',
				'icon_list'                   => $rows,
				// 51 of pitch in the design, less the 19 a 16px row stands at.
				'space_between'               => Elements::size( 32 ),
				'space_between_mobile'        => Elements::size( 20 ),
				'text_indent'                 => Elements::size( 0 ),
				'text_color'                  => self::MUTED,
				'text_color_hover'            => self::INK,
				'text_color_hover_transition' => Elements::size( 0.2 ),
				'icon_typography_typography'  => 'custom',
				'icon_typography_font_size'   => Elements::size( 16 ),
				'icon_typography_font_weight' => '400',
				'icon_typography_line_height' => Elements::size( 1.2, 'em' ),
				'icon_typography_text_decoration' => 'none',
			]
		);
	}

	/**
	 * The seven departments, four beside three.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function category_columns() {
		$categories = self::categories();
		// Four beside three in the design, which is seven split down the middle
		// with the remainder on the left. Computed rather than hard-coded at 4,
		// because a shop with five categories gets five rows, not four and one.
		$split      = (int) ceil( count( $categories ) / 2 );

		return Elements::container(
			[
				'content_width'  => 'full',
				'flex_direction' => 'row',
				'flex_wrap'      => 'nowrap',
				'flex_gap'       => Elements::gap( 16 ),
				'padding'        => Elements::spacing( 0, 0, 0, 0 ),
			],
			[
				self::column( '50%', [ self::category_list( array_slice( $categories, 0, $split ) ) ] ),
				self::column( '50%', [ self::category_list( array_slice( $categories, $split ) ) ] ),
			]
		);
	}

	/**
	 * The departments and the glyph beside each.
	 *
	 * The shop's own product categories, busiest first, when there are any —
	 * "Popular Category" is a claim about the catalogue, and inventing seven
	 * departments a store does not stock makes it a false one. The design's
	 * labels stand in only when there is nothing to read.
	 *
	 * Top-level terms only. A department and its own sub-department side by
	 * side in the same list reads as two departments, and the sub-categories
	 * have somewhere better to be — see {@see styles()}.
	 *
	 * The icons are the design's either way. A category has no glyph of its own
	 * and nothing can guess one from its name, so the seven are dealt out in
	 * order and the user swaps any that land wrong from the row's own icon
	 * control. Font Awesome 5 Free throughout, and nothing outside it: a preset
	 * that shipped seven drawings would be seven files in the plugin for one
	 * layout, and the icon picker only offers the libraries that are loaded
	 * anyway.
	 *
	 * @since 6.8.4
	 *
	 * @return array Rows of `icon`, `library`, `label` and `link`.
	 */
	protected static function categories() {
		$icons = [
			[ 'icon' => 'fas fa-female' ],
			[ 'icon' => 'fas fa-glasses' ],
			// Regular, which is the weight this one is drawn in — the library
			// has to match the prefix or the icon control shows an empty box.
			[ 'icon' => 'far fa-clock', 'library' => 'fa-regular' ],
			[ 'icon' => 'fas fa-tshirt' ],
			[ 'icon' => 'fas fa-shoe-prints' ],
			[ 'icon' => 'fas fa-cut' ],
			[ 'icon' => 'fas fa-paint-brush' ],
		];

		$terms = self::terms( 'product_cat', count( $icons ), [ 'parent' => 0 ] );

		if ( ! $terms ) {
			$terms = self::demo_rows( [
				__( 'Fashion & Glamour', 'essential-addons-for-elementor-lite' ),
				__( 'Apparel & Trends', 'essential-addons-for-elementor-lite' ),
				__( 'Jewelry & Watches', 'essential-addons-for-elementor-lite' ),
				__( 'Clothing & Style', 'essential-addons-for-elementor-lite' ),
				__( 'Footwear & Accessories', 'essential-addons-for-elementor-lite' ),
				__( 'Hairstyle & Beauty', 'essential-addons-for-elementor-lite' ),
				__( 'Makeup & Skincare', 'essential-addons-for-elementor-lite' ),
			] );
		}

		$rows = [];

		foreach ( array_values( $terms ) as $index => $term ) {
			$rows[] = array_merge( $icons[ $index % count( $icons ) ], $term );
		}

		return $rows;
	}

	/**
	 * One column of departments.
	 *
	 * @since 6.8.4
	 *
	 * @param array $categories Rows of `icon`, `library`, `label` and `link`.
	 *
	 * @return array
	 */
	protected static function category_list( $categories ) {
		$rows = [];

		foreach ( $categories as $category ) {
			$library = isset( $category['library'] ) ? $category['library'] : 'fa-solid';

			$rows[] = Elements::row( [
				'text'          => $category['label'],
				'selected_icon' => Elements::icon( $category['icon'], $library ),
				'link'          => Elements::link( '' !== $category['link'] ? $category['link'] : '#' ),
			] );
		}

		return Elements::widget(
			'icon-list',
			[
				'view'                        => 'traditional',
				'icon_list'                   => $rows,
				// 72 of pitch, less the two lines a wrapped label stands at.
				'space_between'               => Elements::size( 26 ),
				'space_between_mobile'        => Elements::size( 18 ),
				'text_indent'                 => Elements::size( 16 ),
				'icon_size'                   => Elements::size( 28 ),
				'icon_color'                  => self::MUTED,
				'icon_color_hover'            => self::INK,
				'text_color'                  => self::MUTED,
				'text_color_hover'            => self::INK,
				'text_color_hover_transition' => Elements::size( 0.2 ),
				'icon_typography_typography'  => 'custom',
				'icon_typography_font_size'   => Elements::size( 15 ),
				'icon_typography_font_weight' => '400',
				// The labels are two words on two lines in the design, and this
				// is the gap between them.
				'icon_typography_line_height' => Elements::size( 1.55, 'em' ),
				'icon_typography_text_decoration' => 'none',
			]
		);
	}

	/* ---------------------------------------------------------------------
	 * Shop data.
	 *
	 * Read once, when the preset is applied, and written into the elements as
	 * plain values. That is a snapshot, not a query, and it is the right shape
	 * for three of the four columns: an Icon List row holds a label and an href,
	 * with nowhere for a taxonomy to live. A menu is curated anyway — the point
	 * is that it arrives curated from the shop that exists rather than from a
	 * fashion catalogue that does not, and every row is then a control.
	 *
	 * The queries are safe here because `Preset_Library::get_content()` runs on
	 * one nonce-checked, `edit_posts`-gated AJAX call. Nothing on this page runs
	 * on a front-end request.
	 * ------------------------------------------------------------------ */

	/**
	 * Is there a shop to read.
	 *
	 * @since 6.8.4
	 *
	 * @return bool
	 */
	protected static function has_woocommerce() {
		return function_exists( 'WC' );
	}

	/**
	 * How few rows make a column not worth filling from the shop.
	 *
	 * A column drawn for six entries showing one reads as a bug, not as a small
	 * shop, so a source that thin is passed over for the next one down.
	 */
	const MIN_ROWS = 2;

	/**
	 * Terms of one product taxonomy, busiest first.
	 *
	 * Sorted in PHP, not by `get_terms()`. WooCommerce replaces `$term->count`
	 * on `product_cat` with a figure that includes the term's children, while
	 * the ORDER BY runs against the raw column the taxonomy table stores — so
	 * asking the query for the busiest first hands back a list that is visibly
	 * out of order against the numbers WooCommerce itself reports. Fetching a
	 * bounded page and ordering it here is the only way the two agree.
	 *
	 * Only terms with products in them: a menu that offers an empty category is
	 * a menu with a dead end in it.
	 *
	 * @since 6.8.4
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $limit    How many to take.
	 * @param array  $args     Extra `get_terms()` arguments, e.g. `parent`.
	 *
	 * @return array Rows of `label` and `link`, empty when there is too little to read.
	 */
	protected static function terms( $taxonomy, $limit, $args = [] ) {
		if ( ! self::has_woocommerce() || ! taxonomy_exists( $taxonomy ) ) {
			return [];
		}

		$terms = get_terms( array_merge( [
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			// Bounded, because this sorts in PHP and a catalogue can have
			// thousands of terms. Far more than any column will show, so the
			// busiest are certain to be in here.
			'number'     => 100,
			'orderby'    => 'count',
			'order'      => 'DESC',
		], $args ) );

		if ( is_wp_error( $terms ) || ! $terms ) {
			return [];
		}

		$default = (int) get_option( 'default_product_cat' );

		$terms = array_filter( $terms, function ( $term ) use ( $taxonomy, $default ) {
			// WooCommerce ships an "Uncategorized" default that is a holding pen,
			// not a department, and putting it in a shop menu advertises the one
			// category nobody meant to publish.
			return ! ( 'product_cat' === $taxonomy && $default === (int) $term->term_id );
		} );

		usort( $terms, function ( $a, $b ) {
			return (int) $b->count <=> (int) $a->count;
		} );

		$rows = [];

		foreach ( array_slice( $terms, 0, $limit ) as $term ) {
			$link = get_term_link( $term );

			$rows[] = [
				'label'  => $term->name,
				'link'   => is_wp_error( $link ) ? '' : $link,
				'parent' => (int) $term->parent,
			];
		}

		return count( $rows ) >= self::MIN_ROWS ? $rows : [];
	}

	/**
	 * The newest products in the catalogue.
	 *
	 * @since 6.8.4
	 *
	 * @param int $limit How many to take.
	 *
	 * @return array Rows of `label` and `link`, empty when there is nothing to read.
	 */
	protected static function recent_products( $limit ) {
		$rows = [];

		foreach ( self::query_products( $limit ) as $product ) {
			$rows[] = [
				'label' => $product->get_name(),
				'link'  => $product->get_permalink(),
			];
		}

		return count( $rows ) >= self::MIN_ROWS ? $rows : [];
	}

	/**
	 * Products from the catalogue.
	 *
	 * @since 6.8.4
	 *
	 * @param int $limit How many to take.
	 *
	 * @return array List of WC_Product.
	 */
	protected static function query_products( $limit ) {
		if ( ! self::has_woocommerce() || ! function_exists( 'wc_get_product' ) ) {
			return [];
		}

		$args = [
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => (int) $limit,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'orderby'             => 'date',
			'order'               => 'DESC',
		];

		// Products the shop owner has hidden from the catalogue have no business
		// being advertised in the menu.
		if ( taxonomy_exists( 'product_visibility' ) ) {
			$args['tax_query'] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				[
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => [ 'exclude-from-catalog' ],
					'operator' => 'NOT IN',
				],
			];
		}

		$products = [];

		foreach ( get_posts( $args ) as $post ) {
			$product = wc_get_product( $post->ID );

			if ( $product && $product->is_visible() ) {
				$products[] = $product;
			}
		}

		return $products;
	}

	/* ---------------------------------------------------------------------
	 * Products.
	 * ------------------------------------------------------------------ */

	/**
	 * The Most Selling Products column.
	 *
	 * The Mega Menu's own **Menu Products** widget, which ships with the menu
	 * and is registered by it — so this column does not depend on any other
	 * element being switched on, and cannot arrive empty because of a checkbox
	 * somewhere else. It queries on every render, falls back to placeholder
	 * cards of its own when WooCommerce is not installed, and carries the
	 * controls that decide what appears: **Filter By** (Best Selling, Featured,
	 * Recent, Sale, Top Rated, Manual Selection), Product Categories, Product
	 * Tags, Select / Exclude Products, Count, Offset and Order.
	 *
	 * So the preset sets a starting point and nothing more. Everything about
	 * which products these are is answered in the panel the user is already
	 * looking at.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected static function products() {
		return Elements::widget(
			Mega_Menu_Products::WIDGET_NAME,
			[
				'eael_mm_products_source'      => 'best-selling',
				'eael_mm_products_count'       => 2,
				'eael_mm_products_columns'     => 2,
				'eael_mm_products_gap'         => Elements::size( 16 ),
				'eael_mm_products_show_title'  => 'yes',
				'eael_mm_products_show_price'  => 'yes',
				'eael_mm_products_title_tag'   => 'h6',

				// The design's plate: portrait, and the same height whatever the
				// shop uploaded.
				'eael_mm_products_image_height' => Elements::size( 211 ),
				'eael_mm_products_image_radius' => Elements::spacing( 0, 0, 0, 0 ),

				// Spelled out rather than left to the widget's defaults, which
				// happen to match today: a preset that reads as the design only
				// by coincidence is a preset that breaks the next time somebody
				// retunes the widget.
				'eael_mm_products_title_color'       => self::MUTED,
				'eael_mm_products_title_color_hover' => self::INK,
				'eael_mm_products_title_typography_typography'  => 'custom',
				'eael_mm_products_title_typography_font_size'   => Elements::size( 15 ),
				'eael_mm_products_title_typography_font_weight' => '400',
				'eael_mm_products_title_typography_line_height' => Elements::size( 1.4, 'em' ),
				'eael_mm_products_title_typography_text_decoration' => 'none',
				'eael_mm_products_title_margin'      => Elements::spacing( 14, 0, 6, 0 ),

				'eael_mm_products_price_color'       => self::BAR_INK,
				'eael_mm_products_price_typography_typography'  => 'custom',
				'eael_mm_products_price_typography_font_size'   => Elements::size( 15 ),
				'eael_mm_products_price_typography_font_weight' => '600',
				'eael_mm_products_price_typography_line_height' => Elements::size( 1.2, 'em' ),
			]
		);
	}
}

<?php
/**
 * The Product Suite preset.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.5
 */

namespace Essential_Addons_Elementor\MegaMenu\Presets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Essential_Addons_Elementor\MegaMenu\Manager;
use Essential_Addons_Elementor\Theme_Builder\Presets\Elements;

/**
 * A floating pill header over a product catalogue, the shape AI and SaaS
 * products have converged on.
 *
 * Two panels doing two different jobs, as in {@see Saas_Menu}, but arranged the
 * other way round:
 *
 * - **Quick Help** — the catalogue. A column of three destination cards on the
 *   left, built from EA's Info Box inside linked containers so the whole card is
 *   the target, and three columns of icon links beside them.
 * - **Solutions** — a plain list of five links, narrow, no icons and no
 *   descriptions. Not every menu item needs a panel the width of the page.
 *
 * ## The cards are tabs
 *
 * Each card switches the three columns beside it, and the first is open on load.
 * That is Elementor's Nested Tabs — see {@see category_tabs()} for why it, and
 * not EA's Advanced Tabs, carries this one.
 *
 * ## Why the panel is transparent
 *
 * The visible card is the *container inside* each panel, not the panel itself.
 * That is what makes the wide one responsive without a single breakpoint of its
 * own: the panel is set to viewport width, so it always fits the screen exactly,
 * and the boxed container inside it lines its card up with the header bar above
 * at whatever width the site is being read at. A fixed pixel width would have
 * been an overflow waiting for the first laptop narrower than it — the widget
 * writes a custom width through verbatim, with nothing clamping it.
 *
 * @since 6.7.5
 */
class Suite_Menu {

	/**
	 * Card titles and the label of the link under the pointer.
	 */
	const INK = '#323232';

	/**
	 * Menu items, resting link labels, the icons beside them.
	 */
	const MUTED = '#686868';

	/**
	 * The line under a card's title — a step lighter again, because it explains
	 * the title rather than competing with it.
	 */
	const FAINT = '#A3A3A3';

	/**
	 * A column heading, lighter still and carried by its letter-spacing.
	 */
	const HEADING = '#999999';

	/**
	 * Brand accent — the hovered link, the card icons.
	 */
	const ACCENT = '#6D28D9';

	/**
	 * Card and header surface.
	 */
	const SURFACE = '#FFFFFF';

	/**
	 * The wash behind a hovered row.
	 */
	const HOVER = '#EDEDED';

	/**
	 * Hairlines — the rule under each column heading.
	 */
	const LINE = '#DCDCDC';

	/**
	 * The panel's own edge, warmer than the rules inside it.
	 */
	const PANEL_LINE = '#E7E0F0';

	/**
	 * The outline of a category icon's tile.
	 */
	const TILE_LINE = '#F2EDF8';

	/**
	 * The "NEW" chip's surface.
	 */
	const BADGE = '#B1AFB8';

	/**
	 * The "NEW" chip's label, on that surface.
	 */
	const BADGE_INK = '#FFFFFF';

	/**
	 * The call to action's surface.
	 */
	const BUTTON = '#DADADA';

	/**
	 * The call to action's label, on that surface.
	 */
	const BUTTON_INK = '#686868';

	/**
	 * The call to action's edge, which is what separates it from the bar behind
	 * it now that both are pale.
	 */
	const BUTTON_LINE = '#C7C7C7';

	/**
	 * The promo tile's surface.
	 */
	const PROMO = '#F0F0F0';


	/**
	 * The element this preset applies.
	 *
	 * @since 6.7.5
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
	 * The header: brand, navigation, one call to action.
	 *
	 * A rounded card rather than a full-bleed bar, so it reads as floating over
	 * whatever the page opens with.
	 *
	 * @since 6.7.5
	 *
	 * @param array $menu The Mega Menu widget element.
	 *
	 * @return array
	 */
	protected static function header( $menu ) {
		return Elements::container(
			[
				'content_width'  => 'full',
				'flex_direction' => 'column',
				// The gutter, and the only place it can live. A boxed container
				// caps its *content* but paints its background across the whole
				// viewport, which squares off the two corners that make the bar a
				// floating pill; and a full-width container plus side margins is
				// `100% + margins`, which overflows the page. Padding on a wrapper
				// is inside its own box, so the bar simply fills what is left.
				'padding'        => Elements::spacing( 18, 16, 0, 16 ),
				'padding_mobile' => Elements::spacing( 12, 12, 0, 12 ),
				'_title'         => __( 'Header', 'essential-addons-for-elementor-lite' ),
			],
			[ self::header_bar( $menu ) ]
		);
	}

	/**
	 * The bar itself — the white pill.
	 *
	 * @since 6.7.5
	 *
	 * @param array $menu The Mega Menu widget element.
	 *
	 * @return array
	 */
	protected static function header_bar( $menu ) {
		return Elements::container(
			[
				'content_width'         => 'full',
				'flex_direction'        => 'row',
				'flex_align_items'      => 'center',
				'flex_justify_content'  => 'space-between',
				'flex_wrap'             => 'nowrap',
				'flex_gap'              => Elements::gap( 24 ),
				'flex_gap_tablet'       => Elements::gap( 16 ),
				'flex_gap_mobile'       => Elements::gap( 8 ),
				'padding'               => Elements::spacing( 16, 24, 16, 24 ),
				'padding_tablet'        => Elements::spacing( 14, 20, 14, 20 ),
				'padding_mobile'        => Elements::spacing( 10, 16, 10, 16 ),
				'background_background' => 'classic',
				'background_color'      => self::SURFACE,
				'border_radius'         => Elements::spacing( 16, 16, 16, 16 ),
				'border_radius_mobile'  => Elements::spacing( 14, 14, 14, 14 ),
				'box_shadow_box_shadow_type' => 'yes',
				// Wide and faint rather than tight and dark: the bar sits on a pale
				// page, and a shadow with any weight to it reads as a second border.
				'box_shadow_box_shadow' => Elements::shadow( 4, 47, 'rgba(0, 0, 0, 0.08)' ),
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
	 * The logo column.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function brand() {
		return Elements::container(
			[
				'content_width'        => 'full',
				'width'                => Elements::size( 20, '%' ),
				'width_tablet'         => Elements::size( 24, '%' ),
				'width_mobile'         => Elements::size( 52, '%' ),
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
	 * @since 6.7.5
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
					'width'        => Elements::size( 140 ),
					'width_mobile' => Elements::size( 112 ),
					'link_to'      => 'custom',
					'link'         => Elements::link( home_url( '/' ) ),
				]
			);
		}

		return Elements::widget(
			'heading',
			[
				'title'                       => get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : __( 'Brand', 'essential-addons-for-elementor-lite' ),
				'header_size'                 => 'h2',
				'link'                        => Elements::link( home_url( '/' ) ),
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
	 * @since 6.7.5
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
				'width_tablet'                => Elements::size( 50, '%' ),
				'width_mobile'                => Elements::size( 16, '%' ),
				'flex_direction'              => 'row',
				'flex_align_items'            => 'center',
				'flex_justify_content'        => 'center',
				'flex_justify_content_mobile' => 'flex-end',
				'padding'                     => Elements::spacing( 0, 0, 0, 0 ),
				// Below the menu's own breakpoint this column holds nothing but the
				// toggle, which belongs beside the sign-in button rather than in the
				// middle of the bar.
				'_flex_order_mobile'          => 'end',
				'_title'                      => __( 'Navigation', 'essential-addons-for-elementor-lite' ),
			],
			[ $menu ]
		);
	}

	/**
	 * The sign-in column.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function actions() {
		return Elements::container(
			[
				'content_width'        => 'full',
				'width'                => Elements::size( 22, '%' ),
				'width_tablet'         => Elements::size( 24, '%' ),
				'width_mobile'         => Elements::size( 28, '%' ),
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'flex-end',
				'flex_wrap'            => 'nowrap',
				'padding'              => Elements::spacing( 0, 0, 0, 0 ),
				'_title'               => __( 'Actions', 'essential-addons-for-elementor-lite' ),
			],
			[ self::login_button() ]
		);
	}

	/**
	 * Sign in — the one thing in the bar meant to be pressed.
	 *
	 * Core's Button rather than EA's Creative Button, which is what the other
	 * preset uses and what this one used first. The design calls for a compact
	 * 71x42 pill, and Creative Button's own stylesheet floors every instance at
	 * 150px wide with no control behind it — nothing in the panel can undo that,
	 * so the widget cannot render this button at the size it is meant to be.
	 *
	 * The size is set outright rather than left to fall out of the padding: a
	 * button sized by padding is a button whose height changes with the site's
	 * font, and this one is specified. `Stretch` makes the button fill the width
	 * set on the widget, and a line height in pixels plus symmetrical padding
	 * makes the height exact — 20 + 11 + 11.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function login_button() {
		return Elements::widget(
			'button',
			[
				'text'                          => __( 'Login', 'essential-addons-for-elementor-lite' ),
				'link'                          => Elements::link( wp_login_url() ),
				// Sized by its own padding now, not held to a fixed width: the design
				// gives it 16 either side of a 16px label and lets the shape follow.
				'align'                         => 'right',

				'background_color'              => self::BUTTON,
				'button_text_color'             => self::BUTTON_INK,
				'hover_color'                   => self::SURFACE,
				'button_background_hover_color' => self::ACCENT,
				// An edge, because the button and the bar behind it are both pale
				// now — without it the shape has nothing to end on.
				'border_border'                 => 'solid',
				'border_width'                  => Elements::spacing( 1, 1, 1, 1 ),
				'border_color'                  => self::BUTTON_LINE,
				'border_radius'                 => Elements::spacing( 12, 12, 12, 12 ),
				'text_padding'                  => Elements::spacing( 12, 16, 12, 16 ),

				'typography_typography'         => 'custom',
				'typography_font_size'          => Elements::size( 16 ),
				'typography_font_weight'        => '500',
				'typography_line_height'        => Elements::size( 1.1, 'em' ),
				'typography_text_decoration'    => 'none',

				// Held at its own size: a widget in a row container grows into
				// whatever space is left, and a stretched button stops reading as
				// one.
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
	 * @since 6.7.5
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
				'label' => __( 'Quick Help', 'essential-addons-for-elementor-lite' ),
				'type'  => 'mega',
				// Viewport, not a pixel width: see the class comment. The card the
				// user sees is the boxed container inside this panel.
				'width' => 'viewport',
				'align' => 'start',
			],
			[
				'label'  => __( 'Solutions', 'essential-addons-for-elementor-lite' ),
				'type'   => 'mega',
				'width'  => 'custom',
				// The card inside is 155 wide; the panel adds the 20px inset that
				// every panel carries on each side, and the nudge puts the card's
				// left edge back on the menu item's.
				'size'   => 200,
				'align'  => 'start',
				'offset' => -20,
			],
			[
				'label' => __( 'Contact', 'essential-addons-for-elementor-lite' ),
				'type'  => 'link',
			],
		];
	}

	/**
	 * Repeater rows for the menu items.
	 *
	 * @since 6.7.5
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
				// neither clickable nor focusable. Panel items are left unlinked so
				// the item itself opens its panel.
				$row['eael_mega_menu_item_link'] = Elements::link();
			} else {
				$row['eael_mega_menu_item_submenu_width'] = $item['width'];
				$row['eael_mega_menu_item_panel_align']   = $item['align'];

				if ( isset( $item['size'] ) ) {
					$row['eael_mega_menu_item_submenu_custom_width'] = Elements::size( $item['size'] );
				}

				if ( isset( $item['offset'] ) ) {
					$row['eael_mega_menu_item_panel_offset_x'] = Elements::size( $item['offset'] );
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
	 * @since 6.7.5
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
			'eael_mega_menu_animation_duration' => Elements::size( 200 ),
			'eael_mega_menu_stretch'            => 'yes',
			'eael_mega_menu_align'              => 'center',
			'eael_mega_menu_indicator_icon'     => Elements::icon( 'fas fa-chevron-down' ),

			'eael_mega_menu_breakpoint'         => 'tablet',
			'eael_mega_menu_toggle_text'        => '',
			'eael_mega_menu_toggle_full_width'  => 'yes',

			// Menu bar — transparent, because the header card is the surface.
			'eael_mega_menu_bar_background_background' => 'classic',
			'eael_mega_menu_bar_background_color'      => 'rgba(0,0,0,0)',
			// 40 between two labels in the design, less the 14 of item padding on
			// each side of the gap.
			'eael_mega_menu_bar_gap'                   => Elements::size( 12 ),
			'eael_mega_menu_bar_padding'               => Elements::spacing( 0, 0, 0, 0 ),

			// Items.
			'eael_mega_menu_item_typography_typography'  => 'custom',
			'eael_mega_menu_item_typography_font_size'   => Elements::size( 16 ),
			'eael_mega_menu_item_typography_font_weight' => '500',
			'eael_mega_menu_item_typography_text_decoration' => 'none',
			'eael_mega_menu_item_padding'                => Elements::spacing( 10, 14, 10, 14 ),
			'eael_mega_menu_item_radius'                 => Elements::spacing( 10, 10, 10, 10 ),
			'eael_mega_menu_item_color'                  => self::MUTED,
			'eael_mega_menu_item_bg'                     => 'rgba(0,0,0,0)',
			'eael_mega_menu_item_color_hover'            => self::ACCENT,
			'eael_mega_menu_item_bg_hover'               => self::HOVER,
			'eael_mega_menu_item_color_active'           => self::ACCENT,
			'eael_mega_menu_item_bg_active'              => self::HOVER,

			// Indicator.
			'eael_mega_menu_indicator_size'         => Elements::size( 12 ),
			'eael_mega_menu_indicator_gap'          => Elements::size( 4 ),
			'eael_mega_menu_indicator_rotate'       => 'yes',
			'eael_mega_menu_indicator_color'        => self::MUTED,
			'eael_mega_menu_indicator_color_active' => self::ACCENT,

			// Panel — deliberately invisible. The card is the container inside it,
			// which is what lets the wide panel span the viewport and still line
			// its content up with the header bar. Every surface control here is
			// spelled out as transparent or zero: left empty they would fall back
			// to the stylesheet's own defaults and paint a second card behind the
			// real one.
			'eael_mega_menu_panel_background_background' => 'classic',
			'eael_mega_menu_panel_background_color'      => 'rgba(0,0,0,0)',
			'eael_mega_menu_panel_radius'                => Elements::spacing( 0, 0, 0, 0 ),
			// This is what holds every card off the screen edges, by the same 20px
			// the header bar is inset — so the wide panel's card lines up with the
			// bar at any width, without either knowing the other's size. It has to
			// be here rather than on the panel containers: this control writes the
			// very `--padding-*` variables a container reads, so its value wins
			// over anything they set. Padding rather than a margin on the card,
			// because a full-width container plus margins overflows its parent.
			'eael_mega_menu_panel_padding'               => Elements::spacing( 0, 20, 0, 20 ),
			'eael_mega_menu_panel_padding_mobile'        => Elements::spacing( 0, 0, 0, 0 ),
			// Measured from the bottom of the menu *item*, which sits 16px above the
			// header bar's own bottom edge once the item's own 10 is taken off — so
			// this is that 6 plus the 4 the design leaves between bar and panel.
			'eael_mega_menu_panel_offset'                => Elements::size( 10 ),
			'eael_mega_menu_panel_z_index'               => 999,

			// Mobile toggle.
			'eael_mega_menu_toggle_align'       => 'flex-end',
			'eael_mega_menu_toggle_icon_size'   => Elements::size( 18 ),
			'eael_mega_menu_toggle_padding'     => Elements::spacing( 9, 11, 9, 11 ),
			'eael_mega_menu_toggle_radius'      => Elements::spacing( 10, 10, 10, 10 ),
			'eael_mega_menu_toggle_color'       => self::INK,
			'eael_mega_menu_toggle_bg'          => self::HOVER,
			'eael_mega_menu_toggle_color_hover' => self::SURFACE,
			'eael_mega_menu_toggle_bg_hover'    => self::ACCENT,

			// Collapsed dropdown.
			'eael_mega_menu_dropdown_background_background' => 'classic',
			'eael_mega_menu_dropdown_background_color'      => self::SURFACE,
			'eael_mega_menu_dropdown_radius'                => Elements::spacing( 18, 18, 18, 18 ),
			'eael_mega_menu_dropdown_padding'               => Elements::spacing( 10, 10, 10, 10 ),
			'eael_mega_menu_dropdown_shadow_box_shadow_type' => 'yes',
			'eael_mega_menu_dropdown_shadow_box_shadow'     => Elements::shadow( 16, 40, 'rgba(15, 23, 42, 0.16)' ),
			// Clear of the header bar. The sheet hangs off the menu widget, which
			// sits centred inside a bar with its own padding — without this it
			// opens over the last of that padding and the bar's rounded corners.
			'eael_mega_menu_dropdown_offset'                => Elements::size( 20 ),
			'eael_mega_menu_dropdown_max_height'            => Elements::size( 78, 'vh' ),
		];
	}

	/* ---------------------------------------------------------------------
	 * Panels.
	 * ------------------------------------------------------------------ */

	/**
	 * The nested containers, one per menu item.
	 *
	 * Positional: the widget prints child *n* for row *n*, so a link item gets an
	 * empty container rather than no container at all.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function panels() {
		$panels = [];

		foreach ( self::item_map() as $index => $item ) {
			switch ( $index ) {
				case 1:
					$panels[] = self::catalogue_panel( $item['label'] );
					break;

				case 2:
					$panels[] = self::shortlist_panel( $item['label'] );
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
	 * The short dropdown: five links and nothing else.
	 *
	 * @since 6.7.5
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function shortlist_panel( $title ) {
		$links = [
			__( 'Blogs', 'essential-addons-for-elementor-lite' ),
			__( 'Documentation', 'essential-addons-for-elementor-lite' ),
			__( 'Video Tutorial', 'essential-addons-for-elementor-lite' ),
			__( '24/7 Support', 'essential-addons-for-elementor-lite' ),
			__( 'Join Community', 'essential-addons-for-elementor-lite' ),
		];

		$rows = [];

		foreach ( $links as $link ) {
			$rows[] = Elements::row( [
				'text'          => $link,
				'link'          => Elements::link(),
				// Explicitly empty: the icon list defaults every new row to a tick,
				// and a help menu is not a checklist.
				'selected_icon' => Elements::icon( '' ),
			] );
		}

		return Elements::nested_child(
			self::panel_settings( $title ),
			[ self::card( [ self::link_list( $rows, 0, 0, true ) ], 8, 'column', 8 ) ]
		);
	}

	/**
	 * The catalogue panel: three destination cards and three columns of links.
	 *
	 * @since 6.7.5
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function catalogue_panel( $title ) {
		return Elements::nested_child(
			self::panel_settings( $title ),
			[ self::card( [ self::category_tabs() ], 23, 'column', 16 ) ]
		);
	}

	/**
	 * The catalogue itself: three categories, each with its own link columns.
	 *
	 * Elementor's own Nested Tabs, not EA's Advanced Tabs, and the reason is the
	 * links. Advanced Tabs holds a tab's content in a WYSIWYG field, which would
	 * turn all forty-odd links across the three categories from rows with a real
	 * icon picker and a real link field into one block of hand-written markup per
	 * tab. Nested Tabs gives each tab a *container*, so every column stays an Icon
	 * List and every link stays a control — which is the whole point of shipping a
	 * preset rather than a screenshot.
	 *
	 * It also takes the cards whole. The tab title is printed through
	 * `wp_kses_post()`, so the description line under each name is a `<span>` in
	 * the field the user already edits, and the icon beside it is the tab's own
	 * Icon control rather than markup. The first tab is active on load — that is
	 * Nested Tabs' own behaviour, nothing here has to ask for it.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function category_tabs() {
		$categories = self::categories();

		$tabs   = [];
		$panels = [];

		foreach ( $categories as $category ) {
			$tabs[] = Elements::row( [
				'tab_title' => self::tab_title( $category['title'], $category['text'] ),
				'tab_icon'  => Elements::icon( $category['icon'] ),
			] );

			$panels[] = self::tab_content(
				$category['columns'],
				$category['explore'],
				isset( $category['promo'] ) ? $category['promo'] : []
			);
		}

		return Elements::widget(
			'nested-tabs',
			[
				'tabs'            => $tabs,
				// "Before" — the tab list sits inline-start of its content, which is
				// the left-hand column of cards in the design.
				'tabs_direction'  => 'inline-start',
				// Top of the column, not the middle of it. Nested Tabs centres a
				// vertical tab list against its content by default, which leaves the
				// first card sitting a third of the way down the panel while the
				// column headings beside it start at the top. This is the control
				// that says otherwise — and it is a different one from
				// `title_alignment` below, which aligns the text inside a card.
				'tabs_justify_vertical' => 'start',
				// Stacked above the content once there is no room beside it.
				'tabs_direction_tablet' => 'block-start',
				// 388 of the card's 1200 of inner width.
				'tabs_width'      => Elements::size( 32, '%' ),
				// The gap between the card column and the links beside it.
				'tabs_title_spacing' => Elements::size( 40 ),
				'title_alignment' => 'start',
				'icon_position'   => 'inline-start',

				// The cards.
				'padding'                    => Elements::spacing( 8, 8, 8, 8 ),
				'tabs_title_space_between'   => Elements::size( 16 ),
				'tabs_title_border_radius'   => Elements::spacing( 8, 8, 8, 8 ),
				// Group controls, not flat colours. Each writes an
				// `--n-tabs-title-background-color*` variable, and the rule that
				// reads it is one Elementor sets at very high specificity — so a
				// value that never lands leaves the widget's own default in place,
				// which for the active tab is near-black.
				'tabs_title_background_color_background' => 'classic',
				'tabs_title_background_color_color'      => 'rgba(0,0,0,0)',
				'tabs_title_background_color_hover_background' => 'classic',
				'tabs_title_background_color_hover_color'      => self::HOVER,
				'tabs_title_background_color_active_background' => 'classic',
				'tabs_title_background_color_active_color'      => self::HOVER,
				'title_text_color'           => self::INK,
				'title_text_color_hover'     => self::INK,
				'title_text_color_active'    => self::INK,
				'title_typography_typography' => 'custom',
				'title_typography_font_size' => Elements::size( 16 ),
				'title_typography_font_weight' => '500',
				'title_typography_line_height' => Elements::size( 1.2, 'em' ),

				// The icon tile. Size and colour are controls; the rounded plate
				// behind it is the one thing Nested Tabs has no control for, and
				// comes from the class below.
				'icon_size'         => Elements::size( 28 ),
				'icon_spacing'      => Elements::size( 12 ),
				'icon_color'        => self::ACCENT,
				'icon_color_hover'  => self::ACCENT,
				'icon_color_active' => self::ACCENT,

				// The content box is the card the panel already draws.
				'box_background_color_background' => 'classic',
				'box_background_color_color'      => 'rgba(0,0,0,0)',
				'_css_classes'         => 'eael-mm-cardtabs',
			],
			$panels
		);
	}

	/**
	 * The three categories, their cards and their columns.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function categories() {
		return [
			[
				'icon'    => 'fas fa-shapes',
				'title'   => __( 'Features', 'essential-addons-for-elementor-lite' ),
				'text'    => __( 'Powerful tools and widgets to design', 'essential-addons-for-elementor-lite' ),
				'explore' => __( 'Explore All Features', 'essential-addons-for-elementor-lite' ),
				'columns' => [
					[
						'title' => __( 'Core Builders', 'essential-addons-for-elementor-lite' ),
						'links' => [
							[ 'text' => __( 'Visual Editor', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-pen-nib' ],
							[ 'text' => __( 'Content Engine', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-file-alt', 'new' => true ],
							[ 'text' => __( 'Header Builder', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-window-maximize' ],
							[ 'text' => __( 'Popup Creator', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-clone' ],
							[ 'text' => __( 'Form Styler', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-sliders-h' ],
						],
					],
					[
						'title' => __( 'Advanced Widgets', 'essential-addons-for-elementor-lite' ),
						'links' => [
							[ 'text' => __( 'Interactive Charts', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-chart-line' ],
							[ 'text' => __( 'Filterable Galleries', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-th-large' ],
							[ 'text' => __( 'Data Table Widget', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-table' ],
							[ 'text' => __( 'Countdown Timer', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-clock' ],
							[ 'text' => __( 'Progress Circle', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-spinner' ],
						],
					],
					[
						'title' => __( 'Site Enhancements', 'essential-addons-for-elementor-lite' ),
						'links' => [
							[ 'text' => __( 'AJAX Search Module', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-search' ],
							[ 'text' => __( 'Scroll Reveal Effects', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-mobile-alt' ],
							[ 'text' => __( 'User Login Widget', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-sign-in-alt' ],
							[ 'text' => __( 'Lightbox for Media', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-image' ],
						],
					],
				],
			],
			[
				'icon'    => 'fas fa-layer-group',
				'title'   => __( 'Ready Solutions', 'essential-addons-for-elementor-lite' ),
				'text'    => __( 'Pre-built functionality for popular use cases', 'essential-addons-for-elementor-lite' ),
				'explore' => __( 'Explore All Solutions', 'essential-addons-for-elementor-lite' ),
				'columns' => [
					[
						'title' => __( 'Ecommerce Kits', 'essential-addons-for-elementor-lite' ),
						'links' => [
							[ 'text' => __( 'Product Showcase', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-box' ],
							[ 'text' => __( 'Checkout &amp; Cart', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-shopping-cart', 'new' => true ],
							[ 'text' => __( 'Pricing Tables', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-tag' ],
							[ 'text' => __( 'Booking Calendar', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-calendar-alt' ],
							[ 'text' => __( 'Membership Area', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-user-circle' ],
						],
					],
					[
						'title' => __( 'Marketing Blocks', 'essential-addons-for-elementor-lite' ),
						'links' => [
							[ 'text' => __( 'Landing Pages', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-window-maximize' ],
							[ 'text' => __( 'Lead Capture Forms', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-clipboard' ],
							[ 'text' => __( 'Newsletter Optin', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-envelope' ],
							[ 'text' => __( 'Countdown Offers', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-percentage' ],
							[ 'text' => __( 'Social Proof Bar', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-star' ],
						],
					],
					[
						'title' => __( 'Support &amp; Engagement', 'essential-addons-for-elementor-lite' ),
						'links' => [
							[ 'text' => __( 'FAQ Accordion', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-question-circle' ],
							[ 'text' => __( 'Testimonial Slider', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-quote-right' ],
							[ 'text' => __( 'Live Chat Button', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-comment' ],
							[ 'text' => __( 'Help Center', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-life-ring' ],
						],
					],
				],
			],
			[
				'icon'    => 'fas fa-th-large',
				'title'   => __( 'Templates', 'essential-addons-for-elementor-lite' ),
				'text'    => __( 'Professionally designed templates and wireframes', 'essential-addons-for-elementor-lite' ),
				'explore' => __( 'Browse All Templates', 'essential-addons-for-elementor-lite' ),
				'columns' => [
					[
						'title' => __( 'Full Site Kits', 'essential-addons-for-elementor-lite' ),
						'links' => [
							[ 'text' => __( 'Business Pro', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-briefcase' ],
							[ 'text' => __( 'Agency Studio', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-compass', 'new' => true ],
							[ 'text' => __( 'Online Store', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-store' ],
							[ 'text' => __( 'Portfolio Plus', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-th-large' ],
							[ 'text' => __( 'Startup Launch', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-rocket' ],
						],
					],
					[
						'title' => __( 'Page Templates', 'essential-addons-for-elementor-lite' ),
						'links' => [
							[ 'text' => __( 'Landing Pages', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-window-maximize' ],
							[ 'text' => __( 'Pricing Pages', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-file-alt' ],
							[ 'text' => __( 'About &amp; Team', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-user-friends' ],
							[ 'text' => __( 'Contact Forms', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-envelope' ],
						],
					],
				],
				// Two columns and this, rather than three columns: the third slot in
				// this tab is the shop window, not another list.
				'promo'   => [
					'label' => __( 'What\'s New', 'essential-addons-for-elementor-lite' ),
					'icon'  => 'fas fa-magic',
					'title' => __( 'Multipurpose eCommerce Store Template', 'essential-addons-for-elementor-lite' ),
				],
			],
		];
	}

	/**
	 * One card, as a tab title.
	 *
	 * The second line is a class rather than inline styles, because the two things
	 * it needs most cannot be expressed inline usefully: Elementor lays the title
	 * span out as a flex *row*, so the description has to be told to stack, and its
	 * colour has to hold in all three tab states while Title Color repaints the
	 * name around it. Both live with the rest of the card in the widget's own
	 * stylesheet; the title is printed through `wp_kses_post()`, so the span
	 * survives, and stripping it leaves the name behind.
	 *
	 * @since 6.7.5
	 *
	 * @param string $title Card name.
	 * @param string $text  The line under it.
	 *
	 * @return string
	 */
	protected static function tab_title( $title, $text ) {
		return sprintf(
			'%1$s<span class="eael-mm-cardtab__text">%2$s</span>',
			esc_html( $title ),
			esc_html( $text )
		);
	}

	/**
	 * One category's columns, as a tab's content.
	 *
	 * Three slots wide, whatever fills them. A category that ships a promo takes
	 * the third for it and hands the width back to the two lists that remain, so
	 * the row still ends where the card does.
	 *
	 * @since 6.7.5
	 *
	 * @param array  $columns Column definitions.
	 * @param string $explore Label for the link that closes the first column.
	 * @param array  $promo   Promo card definition, empty for none.
	 *
	 * @return array
	 */
	protected static function tab_content( $columns, $explore, $promo = [] ) {
		$children = [];
		$width    = empty( $promo ) ? 30 : 30;

		foreach ( $columns as $index => $column ) {
			$children[] = self::link_column(
				$column['title'],
				$column['links'],
				0 === $index ? $explore : '',
				$width
			);
		}

		if ( ! empty( $promo ) ) {
			$children[] = self::promo_card( $promo );
		}

		return Elements::nested_child(
			[
				'content_width'    => 'full',
				'flex_direction'   => 'row',
				'flex_align_items' => 'flex-start',
				// Wrap, not nowrap: the column widths stop just short of the full
				// row, and a user who widens one or adds a fourth should get a
				// second row rather than a squeezed first one.
				'flex_wrap'        => 'wrap',
				'flex_gap'         => Elements::gap( 36 ),
				'flex_gap_tablet'  => Elements::gap( 24 ),
				'padding'          => Elements::spacing( 0, 0, 0, 0 ),
			],
			$children
		);
	}

	/* ---------------------------------------------------------------------
	 * The promo card.
	 * ------------------------------------------------------------------ */

	/**
	 * The tile that closes the Templates tab: a label, a headline, a screenshot.
	 *
	 * Three ordinary widgets on a gradient container rather than one image with
	 * the text baked into it — every line here is a control the user can edit, and
	 * the artwork underneath is the one part meant to be swapped.
	 *
	 * @since 6.7.5
	 *
	 * @param array $promo `label`, `icon`, `title` and `image`.
	 *
	 * @return array
	 */
	protected static function promo_card( $promo ) {
		return Elements::container(
			[
				'content_width'                  => 'full',
				// The third column's share plus the two points the lists beside it
				// gave up, so the row still ends where the card does.
				// 244 against the link columns' 236 — the one column that is a tile
				// rather than a list gets the extra eight.
				'width'                          => Elements::size( 31, '%' ),
				'width_tablet'                   => Elements::size( 100, '%' ),
				'width_mobile'                   => Elements::size( 100, '%' ),
				'flex_direction'                 => 'column',
				'flex_gap'                       => Elements::gap( 16 ),
				// None at all. The plate at the foot spans the tile edge to edge, and
				// an inset here would be an inset it has to be dragged back out of —
				// a widget cannot be widened by a negative margin anyway, since
				// Elementor caps it at the width of the box it sits in. So the tile
				// holds nothing back and the two lines of type carry their own.
				'padding'                        => Elements::spacing( 0, 0, 0, 0 ),
				// The plate's corners are square and the tile's are not; this is what
				// rounds them. Safe here because nothing inside overflows any more.
				'overflow'                       => 'hidden',
				// One flat colour, so it is a control again. This was a class in the
				// stylesheet while the tile carried a three-stop gradient, which is
				// one stop more than Elementor's Background group can hold; a single
				// colour has no such problem, and putting it back on the panel is
				// what lets the user recolour the tile without leaving the editor.
				'background_background'          => 'classic',
				'background_color'               => self::PROMO,
				'border_radius'                  => Elements::spacing( 8, 8, 8, 8 ),
				'_title'                         => __( 'Promo', 'essential-addons-for-elementor-lite' ),
			],
			[
				self::promo_label( $promo['label'], $promo['icon'] ),
				self::promo_heading( $promo['title'] ),
				self::promo_image(),
			]
		);
	}

	/**
	 * The eyebrow over the promo: a mark and a word.
	 *
	 * A one-row Icon List rather than a heading with the glyph written into it.
	 * Core's Heading escapes markup, so an inline `<i>` would print as text; the
	 * list keeps both halves as controls, and it is the same widget the columns
	 * beside it already use.
	 *
	 * Deliberately without the `eael-mm-linklist` class the columns carry: this
	 * row is a label, not a link, and it should not light up under the pointer.
	 *
	 * @since 6.7.5
	 *
	 * @param string $label Eyebrow text.
	 * @param string $icon  Font Awesome class for the mark.
	 *
	 * @return array
	 */
	protected static function promo_label( $label, $icon ) {
		return Elements::widget(
			'icon-list',
			[
				'view'                        => 'traditional',
				// No `link` on the row, so the renderer prints a span — nothing here
				// is meant to be clicked.
				'icon_list'                   => [
					Elements::row( [
						'text'          => $label,
						'selected_icon' => Elements::icon( $icon ),
					] ),
				],
				// The tile's own inset, carried here instead — see `promo_card()`.
				'_padding'                    => Elements::spacing( 34, 25, 0, 25 ),
				'space_between'               => Elements::size( 0 ),
				'text_indent'                 => Elements::size( 8 ),
				'icon_size'                   => Elements::size( 15 ),
				// The same grey as the columns beside it, now that the tile is a pale
				// plate rather than a saturated one.
				'icon_color'                  => self::MUTED,
				'text_color'                  => self::MUTED,
				'icon_typography_typography'  => 'custom',
				'icon_typography_font_size'   => Elements::size( 13 ),
				'icon_typography_font_weight' => '600',
			]
		);
	}

	/**
	 * The promo's headline.
	 *
	 * @since 6.7.5
	 *
	 * @param string $title Headline text.
	 *
	 * @return array
	 */
	protected static function promo_heading( $title ) {
		return Elements::widget(
			'heading',
			[
				'title'                        => $title,
				'header_size'                  => 'h6',
				'_padding'                     => Elements::spacing( 0, 25, 0, 25 ),
				'title_color'                  => self::MUTED,
				'typography_typography'        => 'custom',
				'typography_font_size'         => Elements::size( 16 ),
				'typography_font_weight'       => '600',
				'typography_line_height'       => Elements::size( 1.55, 'em' ),
			]
		);
	}

	/**
	 * The band under the headline.
	 *
	 * Elementor's own placeholder, not shipped artwork. The design draws this as
	 * an empty plate with a picture mark on it, and that is the honest thing for
	 * a preset to arrive with: a real screenshot of somebody else's template
	 * reads as content the user is meant to keep, where the placeholder is the
	 * one image on the page that says "swap me" in a language they already know.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function promo_image() {
		return Elements::widget(
			'image',
			[
				'image'               => [ 'id' => '', 'url' => Elements::placeholder_image() ],
				'image_size'          => 'full',
				'align'               => 'start',
				// Edge to edge, which it simply is: the tile keeps no padding for it
				// to be pulled out of.
				'width'               => Elements::size( 100, '%' ),
				// A band, not a square. The placeholder is square and the design's
				// plate is not, so it is cropped to the height rather than allowed
				// to set it — the mark sits in the middle and survives the crop.
				'height'              => Elements::size( 167 ),
				'object-fit'          => 'cover',
				// Square: every corner of the plate is on one of the tile's own
				// edges, and the tile is what rounds them.
				'image_border_radius' => Elements::spacing( 0, 0, 0, 0 ),
			]
		);
	}

	/**
	 * Settings shared by both panels.
	 *
	 * @since 6.7.5
	 *
	 * No padding here, and none is possible: the widget's Submenu Panel > Padding
	 * control writes the very `--padding-*` variables a container reads, so its
	 * value wins over anything set on this one — including a zero. What holds the
	 * wide card off the screen edges is a *margin* on the card itself.
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function panel_settings( $title ) {
		return [
			'content_width'  => 'full',
			'flex_direction' => 'column',
			'_title'         => $title,
		];
	}

	/**
	 * The white card a panel's content sits on.
	 *
	 * Always full width — it fills whatever the panel around it left it, which is
	 * how the wide one ends up the same width as the header bar and the narrow one
	 * ends up the width of its own dropdown.
	 *
	 * @since 6.7.5
	 *
	 * @param array  $children  Card contents.
	 * @param int    $padding   Inset, in pixels.
	 * @param string $direction `row` for the wide panel, `column` for the narrow one.
	 * @param int    $radius    Corner radius, in pixels. The narrow card takes a
	 *                          smaller one: a corner cut for a card the width of the
	 *                          page reads as a bubble on one 155 wide.
	 *
	 * @return array
	 */
	protected static function card( $children, $padding = 28, $direction = 'column', $radius = 22 ) {
		// The corner only ever shrinks on a small screen: the wide card softens to
		// 16 there, and one already tighter than that is left as it is.
		$mobile   = min( $radius, 16 );
		$settings = [
			'content_width'         => 'full',
			'flex_direction'        => $direction,
			'flex_align_items'      => 'flex-start',
			// Wrap, not nowrap: the column widths below stop just short of the
			// full row, and a user who widens one or adds a fifth should get a
			// second row rather than a squeezed first one.
			'flex_wrap'             => 'wrap',
			'flex_gap'              => Elements::gap( 'row' === $direction ? 28 : 2 ),
			'flex_gap_tablet'       => Elements::gap( 'row' === $direction ? 20 : 2 ),
			'padding'               => Elements::spacing( $padding, $padding, $padding, $padding ),
			'padding_mobile'        => Elements::spacing( 14, 14, 14, 14 ),
			'background_background' => 'classic',
			'background_color'      => self::SURFACE,
			'border_border'         => 'solid',
			'border_width'          => Elements::spacing( 1, 1, 1, 1 ),
			'border_color'          => self::PANEL_LINE,
			'border_radius'         => Elements::spacing( $radius, $radius, $radius, $radius ),
			'border_radius_mobile'  => Elements::spacing( $mobile, $mobile, $mobile, $mobile ),
			'_title'                => __( 'Card', 'essential-addons-for-elementor-lite' ),
		];

		return Elements::container( $settings, $children );
	}

	/* ---------------------------------------------------------------------
	 * Solutions panel columns.
	 * ------------------------------------------------------------------ */

	/**
	 * One column of links, under a ruled heading.
	 *
	 * @since 6.7.5
	 *
	 * @param string $title  Column heading.
	 * @param array  $links  Rows of `text`, `icon` and optionally `new`.
	 * @param string $footer Label for the link closing the column, empty for none.
	 * @param int    $width  Share of the row, as a percentage.
	 *
	 * @return array
	 */
	protected static function link_column( $title, $links, $footer = '', $width = 31 ) {
		$rows = [];

		foreach ( $links as $link ) {
			// Outline glyphs where Font Awesome 5 Free ships one, solid otherwise;
			// the library has to match the prefix or the icon control shows an
			// empty box.
			$library = 0 === strpos( $link['icon'], 'far ' ) ? 'fa-regular' : 'fa-solid';

			$rows[] = Elements::row( [
				'text'          => empty( $link['new'] ) ? $link['text'] : $link['text'] . self::new_badge(),
				'link'          => Elements::link(),
				'selected_icon' => Elements::icon( $link['icon'], $library ),
			] );
		}

		$children = [
			self::column_heading( $title ),
			// 8, not the design's 4: it sits its 20px glyph in a 24px box and adds
			// the gap to that, and Icon List has no box — only the glyph and the
			// gap after it. 20 + 8 puts the label where 24 + 4 does.
			self::link_list( $rows, 8 ),
		];

		if ( '' !== $footer ) {
			$children[] = self::explore_link( $footer );
		}

		return Elements::container(
			[
				'content_width'  => 'full',
				// 236 of the 772 the columns share, with the two 36px gaps between
				// them taken off first.
				'width'          => Elements::size( $width, '%' ),
				'width_tablet'   => Elements::size( 100, '%' ),
				'width_mobile'   => Elements::size( 100, '%' ),
				'flex_direction' => 'column',
				'flex_gap'       => Elements::gap( 24 ),
				'padding'        => Elements::spacing( 0, 0, 0, 0 ),
				'_title'         => $title,
			],
			$children
		);
	}

	/**
	 * A column heading, with the hairline under it.
	 *
	 * The rule is the heading widget's own Advanced-tab border rather than a
	 * separate Divider widget — one element instead of two, and it stays attached
	 * to the heading if the column is reordered.
	 *
	 * @since 6.7.5
	 *
	 * @param string $title Heading text.
	 *
	 * @return array
	 */
	protected static function column_heading( $title ) {
		return Elements::widget(
			'heading',
			[
				'title'                       => $title,
				'header_size'                 => 'h6',
				'title_color'                 => self::HEADING,
				'typography_typography'       => 'custom',
				'typography_font_size'        => Elements::size( 12 ),
				'typography_font_weight'      => '500',
				'typography_text_transform'   => 'uppercase',
				'typography_letter_spacing'   => Elements::size( 2.4 ),
				'typography_line_height'      => Elements::size( 1.2, 'em' ),
				'_border_border'              => 'solid',
				'_border_width'               => Elements::spacing( 0, 0, 1, 0 ),
				'_border_color'               => self::LINE,
				'_padding'                    => Elements::spacing( 0, 0, 8, 0 ),
			]
		);
	}

	/**
	 * A list of links.
	 *
	 * Core's Icon List rather than a container of buttons: one widget instead of
	 * five, and every part of a row — icon, label, spacing, both colours — is
	 * already a control on it. The class is the hook the widget's own stylesheet
	 * answers for the hover pill, which Icon List has no control for; strip it and
	 * the links still work.
	 *
	 * @since 6.7.5
	 *
	 * @param array $rows   Repeater rows.
	 * @param int   $indent Gap between an icon and its label, in pixels.
	 * @param int   $gap    Space between rows, in pixels.
	 * @param bool  $roomy  Whether rows take the taller inset the narrow dropdown
	 *                      uses. A modifier on the same class, because a row's
	 *                      padding is the one part of it Icon List has no control
	 *                      for — see the widget's own stylesheet.
	 *
	 * @return array
	 */
	protected static function link_list( $rows, $indent, $gap = 20, $roomy = false ) {
		return Elements::widget(
			'icon-list',
			[
				'view'                      => 'traditional',
				'icon_list'                 => $rows,
				// 52 of pitch in the design, less the 32 a row stands at.
				'space_between'             => Elements::size( $gap ),
				'text_indent'               => Elements::size( $indent ),
				'icon_size'                 => Elements::size( 20 ),
				'icon_color'                => self::MUTED,
				'icon_color_hover'          => self::ACCENT,
				'text_color'                => self::MUTED,
				'text_color_hover'          => self::ACCENT,
				'icon_typography_typography' => 'custom',
				'icon_typography_font_size'  => Elements::size( 16 ),
				'icon_typography_font_weight' => '400',
				'icon_typography_line_height' => Elements::size( 1.2, 'em' ),
				'_css_classes'              => $roomy ? 'eael-mm-linklist eael-mm-linklist--roomy' : 'eael-mm-linklist',
			]
		);
	}

	/**
	 * The "NEW" chip beside a link label.
	 *
	 * Inline styles, not a class: Icon List prints a row's text unescaped, so the
	 * chip can live in the label the user already edits, and keeping it
	 * self-contained means the one preset that uses it needs nothing added to the
	 * widget's stylesheet.
	 *
	 * @since 6.7.5
	 *
	 * @return string
	 */
	protected static function new_badge() {
		return sprintf(
			'<span style="display:inline-block;margin-inline-start:6px;padding:2px 4px;border-radius:4px;background:%1$s;color:%2$s;font-size:12px;font-weight:500;line-height:1.2;vertical-align:middle;">%3$s</span>',
			self::BADGE,
			self::BADGE_INK,
			esc_html__( 'NEW', 'essential-addons-for-elementor-lite' )
		);
	}

	/**
	 * The link that closes the first column.
	 *
	 * @since 6.7.5
	 *
	 * @param string $label Link text.
	 *
	 * @return array
	 */
	protected static function explore_link( $label ) {
		return Elements::widget(
			'button',
			[
				'text'                          => $label,
				'link'                          => Elements::link(),
				'size'                          => 'sm',
				'align'                         => 'left',
				'selected_icon'                 => Elements::icon( 'fas fa-external-link-alt' ),
				// `row-reverse`, not `right`: this control sets `flex-direction`.
				'icon_align'                    => 'row-reverse',
				'icon_indent'                   => Elements::size( 3 ),
				'background_color'              => 'rgba(0,0,0,0)',
				'button_text_color'             => self::MUTED,
				'hover_color'                   => self::INK,
				'button_background_hover_color' => 'rgba(0,0,0,0)',
				'text_padding'                  => Elements::spacing( 0, 0, 0, 4 ),
				'typography_typography'         => 'custom',
				'typography_font_size'          => Elements::size( 14 ),
				'typography_font_weight'        => '500',
				// The one underline in the design, and the reason the control is
				// worth knowing about: it goes both ways.
				'typography_text_decoration'    => 'underline',
				'_flex_size'                    => 'none',
			]
		);
	}
}

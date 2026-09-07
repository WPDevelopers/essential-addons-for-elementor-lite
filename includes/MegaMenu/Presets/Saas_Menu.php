<?php
/**
 * The SaaS Menu preset.
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
 * A product-navigation menu of the shape SaaS marketing sites converge on.
 *
 * Two panels, deliberately different from each other, because the two jobs a
 * SaaS menu does are different:
 *
 * - **Product** — a browsable catalogue. Eight categories on the left, the links
 *   for the selected one on the right. Built from EA's own Advanced Tabs in its
 *   vertical layout, so the category list is a real control the visitor drives,
 *   not a static column that happens to look like one.
 * - **Resources** — a short list of destinations. Four rows of icon, name and a
 *   line of explanation, built from EA's Info Box, each row a linked container
 *   so the whole row is the target and lights up under the pointer.
 *
 * The menu ships inside a finished header — the site's own logo on one side, a
 * sign-in link and a Create Account button on the other — because that is the
 * thing a visitor sees. A mega menu floating alone above an empty bar is a
 * component, not a design, and leaving the other two thirds of the header for
 * the user to assemble is leaving the preset half done.
 *
 * Everything is a control value. There is no fixed artwork, no block of custom
 * CSS and no markup this preset alone knows how to render: after it is applied
 * every colour, label and link is editable from the panel the user is already
 * looking at, and the two panels are ordinary containers they can rebuild from
 * scratch without leaving anything behind.
 *
 * The one exception is the link list inside a tab, which is a WYSIWYG field
 * rather than a widget — see {@see link_list()}.
 *
 * @since 6.7.5
 */
class Saas_Menu {

	/**
	 * Menu items, headings and link text.
	 */
	const INK = '#444444';

	/**
	 * Descriptions and other secondary copy.
	 */
	const MUTED = '#848484';

	/**
	 * Brand accent — the hovered link, the panel icons.
	 */
	const ACCENT = '#6DBB00';

	/**
	 * Panel surface.
	 */
	const SURFACE = '#FFFFFF';

	/**
	 * The wash behind a hovered or selected row.
	 */
	const HOVER = '#F7F7F7';

	/**
	 * Hairlines — the divider between the two tab columns, the rule under a
	 * column heading, the outline of a Resources icon tile.
	 */
	const LINE = '#E9E9E9';

	/**
	 * The Product card's own edge — a shade lighter than the rules inside it, so
	 * the card reads as a surface and the dividers as structure.
	 */
	const CARD_LINE = '#F4F4F4';

	/**
	 * The chevron closing a Resources row, lighter than the copy beside it.
	 */
	const CHEVRON = '#9D9D9D';

	/**
	 * The call to action's surface.
	 */
	const BUTTON = '#DADADA';

	/**
	 * The call to action's label, on that surface — the same grey the rest of the
	 * bar is set in.
	 */
	const BUTTON_INK = '#686868';

	/**
	 * The element this preset applies.
	 *
	 * Two shapes, because a Mega Menu can be applied to in two situations. In
	 * `header` mode the preset is the finished header bar — brand, menu, buttons —
	 * and it replaces the container the menu was sitting in. In `widget` mode it
	 * is the menu alone, which is what a menu nested somewhere the preset has no
	 * business rebuilding gets.
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
	 * The header: brand, navigation and the two account actions.
	 *
	 * Three columns rather than a single row of widgets, because the middle one
	 * has to be able to centre itself against the bar while the outer two hold
	 * their own edges — which is what percentage widths on three flex children
	 * buy, and what a bare row of four widgets cannot do.
	 *
	 * Responsive behaviour is split between the two layers that own it: the
	 * column widths carry `_tablet` / `_mobile` values, and the menu widget
	 * collapses itself into a toggle at its own breakpoint. The navigation column
	 * is ordered last on mobile so that toggle lands beside the account actions
	 * instead of in the middle of the bar.
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
				'content_width'         => 'boxed',
				'flex_direction'        => 'row',
				'flex_align_items'      => 'center',
				'flex_justify_content'  => 'space-between',
				'flex_wrap'             => 'nowrap',
				'flex_gap'              => Elements::gap( 24 ),
				'flex_gap_tablet'       => Elements::gap( 16 ),
				'flex_gap_mobile'       => Elements::gap( 8 ),
				'padding'               => Elements::spacing( 16, 24, 16, 24 ),
				'padding_tablet'        => Elements::spacing( 14, 20, 14, 20 ),
				'padding_mobile'        => Elements::spacing( 12, 16, 12, 16 ),
				'background_background' => 'classic',
				'background_color'      => self::SURFACE,
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
				'width'                => Elements::size( 22, '%' ),
				'width_tablet'         => Elements::size( 24, '%' ),
				'width_mobile'         => Elements::size( 46, '%' ),
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
				'title'                       => get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : __( 'Brand', 'essential-addons-for-elementor-lite' ),
				'header_size'                 => 'h2',
				'link'                        => Elements::link( home_url( '/' ) ),
				// A linked heading inherits the theme's link colour, which is rarely
				// the right ink for a site name.
				'title_color'                 => self::INK,
				'typography_typography'       => 'custom',
				'typography_font_size'        => Elements::size( 23 ),
				'typography_font_size_tablet' => Elements::size( 20 ),
				'typography_font_size_mobile' => Elements::size( 18 ),
				'typography_font_weight'      => '700',
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
				'width'                       => Elements::size( 50, '%' ),
				'width_tablet'                => Elements::size( 46, '%' ),
				'width_mobile'                => Elements::size( 16, '%' ),
				'flex_direction'              => 'row',
				'flex_align_items'            => 'center',
				'flex_justify_content'        => 'center',
				'flex_justify_content_mobile' => 'flex-end',
				'padding'                     => Elements::spacing( 0, 0, 0, 0 ),
				// Below the menu's own breakpoint this column holds nothing but the
				// toggle button, and a toggle sitting between the logo and the
				// account buttons reads as a third, unrelated control. Ordering the
				// column last puts it where a hamburger is expected.
				'_flex_order_mobile'          => 'end',
				'_title'                      => __( 'Navigation', 'essential-addons-for-elementor-lite' ),
			],
			[ $menu ]
		);
	}

	/**
	 * The account column: sign in, and the call to action beside it.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function actions() {
		return Elements::container(
			[
				'content_width'        => 'full',
				'width'                => Elements::size( 26, '%' ),
				'width_tablet'         => Elements::size( 28, '%' ),
				'width_mobile'         => Elements::size( 36, '%' ),
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'flex-end',
				'flex_wrap'            => 'nowrap',
				'flex_gap'             => Elements::gap( 14 ),
				'flex_gap_tablet'      => Elements::gap( 10 ),
				'flex_gap_mobile'      => Elements::gap( 6 ),
				'padding'              => Elements::spacing( 0, 0, 0, 0 ),
				'_title'               => __( 'Actions', 'essential-addons-for-elementor-lite' ),
			],
			[
				self::login_button(),
				self::cta_button(),
			]
		);
	}

	/**
	 * Sign in — a label, not a button.
	 *
	 * Core's Button rather than EA's Creative Button, which the call to action
	 * beside it uses: the Creative Button's own stylesheet floors every instance
	 * at 150px with no control behind it, and a 150px wide "Login" next to a real
	 * button is two calls to action where the design has one.
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
				'size'                          => 'sm',
				'align'                         => 'right',
				// Spelled out, transparents included: an empty colour setting emits
				// no rule and the theme's own button styling paints this instead.
				'background_color'              => 'rgba(0,0,0,0)',
				'button_text_color'             => self::INK,
				'hover_color'                   => self::ACCENT,
				'button_background_hover_color' => 'rgba(0,0,0,0)',
				'border_radius'                 => Elements::spacing( 8, 8, 8, 8 ),
				'text_padding'                  => Elements::spacing( 10, 12, 10, 12 ),
				'typography_typography'         => 'custom',
				'typography_font_size'          => Elements::size( 15 ),
				'typography_font_size_mobile'   => Elements::size( 14 ),
				'typography_font_weight'        => '500',
				// Spelled out for the same reason the colours are. A button is a
				// button, but to a theme it is still an `<a>`, and plenty of them
				// underline every link on the page from a selector no widget
				// default outranks. Setting it here writes Elementor's own
				// per-widget rule and puts the choice in the panel, next to the
				// rest of the typography, where it can be changed back.
				'typography_text_decoration'    => 'none',
				// Held at its own size: a widget in a row container grows into
				// whatever space is left, and a stretched text link stops reading
				// as one.
				'_flex_size'                    => 'none',
			]
		);
	}

	/**
	 * Create Account — the one thing in the bar that is meant to be pressed.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function cta_button() {
		return Elements::widget(
			'eael-creative-button',
			[
				'creative_button_text'                        => __( 'Create Account', 'essential-addons-for-elementor-lite' ),
				'creative_button_link_url'                    => Elements::link( wp_registration_url() ),
				'creative_button_effect'                      => 'eael-creative-button--default',
				'eael_creative_button_icon_new'               => Elements::icon( '' ),
				// The control writes `justify-content`, so it wants a flex value.
				'eael_creative_button_alignment'              => 'flex-end',
				'eael_creative_button_padding'                => Elements::spacing( 8, 16, 8, 16 ),
				'eael_creative_button_padding_mobile'         => Elements::spacing( 8, 14, 8, 14 ),
				// A slider, not a dimensions control: the widget's own stylesheet
				// rounds the button by 2px, and only this value sets the corner.
				// 68 is past half the button's height, so the ends are semicircles
				// however tall the label makes it.
				'eael_creative_button_border_radius'          => Elements::size( 68 ),
				'eael_creative_button_text_color'             => self::BUTTON_INK,
				'eael_creative_button_background_color'       => self::BUTTON,
				// The design draws no hover state; the accent is the preset's own,
				// and white rather than the resting grey because that grey is
				// unreadable on it.
				'eael_creative_button_hover_text_color'       => self::SURFACE,
				'eael_creative_button_hover_background_color' => self::ACCENT,
				'eael_creative_button_typography_typography'  => 'custom',
				'eael_creative_button_typography_font_size'   => Elements::size( 16 ),
				'eael_creative_button_typography_font_size_mobile' => Elements::size( 15 ),
				'eael_creative_button_typography_font_weight' => '500',
				// Out on a phone. Its own 150px floor is nearly half a small screen,
				// and the logo and the menu toggle are what has to fit there;
				// signing up lives one tap away inside the menu.
				'hide_mobile'                                 => 'hidden-mobile',
				// Same reason as the sign-in link above.
				'_flex_size'                                  => 'none',
			]
		);
	}

	/* ---------------------------------------------------------------------
	 * Menu items.
	 * ------------------------------------------------------------------ */

	/**
	 * The menu items, in bar order.
	 *
	 * Two of the five open a panel. A menu where every item drops one is a menu
	 * nobody ships, and the three plain links are one control away from becoming
	 * panels — the nested container each already owns is waiting for it.
	 *
	 * The panel geometry lives here rather than in {@see settings()} because it
	 * is per item: the catalogue needs room for two columns, the resource list
	 * would look abandoned in the same width.
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
				'label' => __( 'Product', 'essential-addons-for-elementor-lite' ),
				'type'  => 'mega',
				'width' => 620,
				'align' => 'start',
			],
			[
				'label' => __( 'Pricing', 'essential-addons-for-elementor-lite' ),
				'type'  => 'link',
			],
			[
				'label' => __( 'Contact', 'essential-addons-for-elementor-lite' ),
				'type'  => 'link',
			],
			[
				'label' => __( 'Resources', 'essential-addons-for-elementor-lite' ),
				'type'  => 'mega',
				// 270 of description plus everything beside it: 16 of card inset
				// either side, an 8/12 row inset, a 48 tile and the 16 after it, and
				// the 16 chevron with its 8.
				'width' => 410,
				'align' => 'center',
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
				// neither clickable nor focusable. Panel items are left unlinked
				// on purpose so the item itself opens its panel instead of
				// growing a separate disclosure button beside the label.
				$row['eael_mega_menu_item_link'] = Elements::link();
			} else {
				$row['eael_mega_menu_item_submenu_width']        = 'custom';
				$row['eael_mega_menu_item_submenu_custom_width'] = Elements::size( $item['width'] );
				$row['eael_mega_menu_item_panel_align']          = $item['align'];
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
			'eael_mega_menu_animation_duration' => Elements::size( 220 ),
			'eael_mega_menu_stretch'            => 'yes',
			'eael_mega_menu_align'              => 'center',
			'eael_mega_menu_indicator_icon'     => Elements::icon( 'fas fa-chevron-down' ),

			// Responsive. Tablet, not mobile: the Product panel is two columns
			// side by side and 760px of it does not survive a tablet in portrait.
			'eael_mega_menu_breakpoint'         => 'tablet',
			'eael_mega_menu_toggle_text'        => '',
			'eael_mega_menu_toggle_full_width'  => 'yes',

			// Menu bar — transparent, because it sits on the header's own surface.
			'eael_mega_menu_bar_background_background' => 'classic',
			'eael_mega_menu_bar_background_color'      => 'rgba(0,0,0,0)',
			'eael_mega_menu_bar_gap'                   => Elements::size( 6 ),
			'eael_mega_menu_bar_padding'               => Elements::spacing( 0, 0, 0, 0 ),

			// Items.
			'eael_mega_menu_item_typography_typography'  => 'custom',
			'eael_mega_menu_item_typography_font_size'   => Elements::size( 16 ),
			'eael_mega_menu_item_typography_font_weight' => '500',
			// The widget's stylesheet already resets this; the control writes it
			// again at Elementor's own specificity, which is what a theme reaching
			// past three classes runs into.
			'eael_mega_menu_item_typography_text_decoration' => 'none',
			'eael_mega_menu_item_padding'                => Elements::spacing( 10, 14, 10, 14 ),
			'eael_mega_menu_item_radius'                 => Elements::spacing( 8, 8, 8, 8 ),
			'eael_mega_menu_item_color'                  => self::INK,
			'eael_mega_menu_item_bg'                     => 'rgba(0,0,0,0)',
			// The bar does not recolour. Hovering an item opens a panel below it,
			// and inside that panel a row is already lighting up under the
			// pointer — a second highlight on the label above it competes with the
			// one the user is actually reading. The indicator rotating is the
			// acknowledgement, and it is enough.
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

			// Panel. Padding is left at zero and owned by the container inside it,
			// so the user styles the inset on the layout, where they can see it.
			// Deliberately invisible, because the two panels are not the same card:
			// the catalogue is a squared-off 8px box with a hairline edge, and the
			// shortlist is a 16px one with no edge at all and a tighter shadow. One
			// panel style cannot be both, so each panel's own container paints its
			// card and this is spelled out as transparent — left empty these fall
			// back to the stylesheet's defaults and paint a second card behind the
			// real one.
			'eael_mega_menu_panel_background_background' => 'classic',
			'eael_mega_menu_panel_background_color'      => 'rgba(0,0,0,0)',
			'eael_mega_menu_panel_radius'                => Elements::spacing( 0, 0, 0, 0 ),
			'eael_mega_menu_panel_padding'               => Elements::spacing( 0, 0, 0, 0 ),
			// A detached panel, so the corners round against the page rather than
			// against the bar. Small enough that the pointer crosses the gap
			// without the hover lapsing on the way down.
			'eael_mega_menu_panel_offset'                => Elements::size( 10 ),
			'eael_mega_menu_panel_z_index'               => 999,

			// Mobile toggle.
			'eael_mega_menu_toggle_align'       => 'flex-end',
			'eael_mega_menu_toggle_icon_size'   => Elements::size( 18 ),
			'eael_mega_menu_toggle_padding'     => Elements::spacing( 9, 11, 9, 11 ),
			'eael_mega_menu_toggle_radius'      => Elements::spacing( 8, 8, 8, 8 ),
			'eael_mega_menu_toggle_color'       => self::INK,
			'eael_mega_menu_toggle_bg'          => self::HOVER,
			'eael_mega_menu_toggle_color_hover' => self::SURFACE,
			'eael_mega_menu_toggle_bg_hover'    => self::ACCENT,

			// Collapsed dropdown. The accordion opens inside it, and the Product
			// panel is eight tabs tall, so scrolling it beats clipping it.
			'eael_mega_menu_dropdown_background_background' => 'classic',
			'eael_mega_menu_dropdown_background_color'      => self::SURFACE,
			'eael_mega_menu_dropdown_radius'                => Elements::spacing( 14, 14, 14, 14 ),
			'eael_mega_menu_dropdown_padding'               => Elements::spacing( 10, 10, 10, 10 ),
			'eael_mega_menu_dropdown_shadow_box_shadow_type' => 'yes',
			'eael_mega_menu_dropdown_shadow_box_shadow'     => Elements::shadow( 16, 40, 'rgba(16, 24, 40, 0.16)' ),
			// Clear of the header bar. The sheet hangs off the menu widget, which
			// sits centred inside a bar with its own padding — without this it
			// opens over the last of that padding and the bar's rounded corners.
			'eael_mega_menu_dropdown_offset'                => Elements::size( 20 ),
			'eael_mega_menu_dropdown_max_height'            => Elements::size( 75, 'vh' ),
		];
	}

	/* ---------------------------------------------------------------------
	 * Panels.
	 * ------------------------------------------------------------------ */

	/**
	 * The nested containers, one per menu item.
	 *
	 * Positional: the widget prints child *n* for row *n*, so a link item gets an
	 * empty container rather than no container at all — skipping one would shift
	 * every panel after it onto the wrong item.
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
					$panels[] = self::product_panel( $item['label'] );
					break;

				case 4:
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
	 * The Product panel: a category list beside the links it holds.
	 *
	 * @since 6.7.5
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function product_panel( $title ) {
		return Elements::nested_child(
			self::panel_settings( $title ),
			[
				self::card(
					[ self::category_tabs() ],
					8,
					self::CARD_LINE,
					Elements::shadow( 12, 36, 'rgba(0, 1, 35, 0.12)' )
				),
			]
		);
	}

	/**
	 * Settings shared by both panels.
	 *
	 * Nothing but layout. Everything a panel looks like belongs to the card
	 * inside it — see {@see card()}.
	 *
	 * @since 6.7.5
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
	 * The card a panel's content sits on.
	 *
	 * A child of the panel rather than the panel itself, and that is not a
	 * stylistic preference. The widget's own Submenu Panel controls are written
	 * against `.eael-mega-menu__panel--inline` — one class deeper than the rule
	 * Elementor writes for a container's own background — and its Radius control
	 * writes the very `--border-radius` variable a container reads. Set the
	 * panel's surface to transparent so the two panels can differ, and anything
	 * this preset then puts on that same container loses to it: a white card
	 * that never paints, which is a panel you can see the page through.
	 *
	 * A card one level in is out of that reach, and it is the same arrangement
	 * the other preset uses for the same reason.
	 *
	 * @since 6.7.5
	 *
	 * @param array  $children Card contents.
	 * @param int    $radius   Corner radius, in pixels.
	 * @param string $border   Edge colour, empty for no edge.
	 * @param array  $shadow   Box shadow value.
	 * @param int    $padding  Inset, in pixels.
	 * @param int    $gap      Gap between children, in pixels.
	 *
	 * @return array
	 */
	protected static function card( $children, $radius, $border, $shadow, $padding = 0, $gap = 0 ) {
		$settings = [
			'content_width'         => 'full',
			'flex_direction'        => 'column',
			'flex_gap'              => Elements::gap( $gap ),
			'padding'               => Elements::spacing( $padding, $padding, $padding, $padding ),
			'background_background' => 'classic',
			'background_color'      => self::SURFACE,
			'border_radius'         => Elements::spacing( $radius, $radius, $radius, $radius ),
			'box_shadow_box_shadow_type' => 'yes',
			'box_shadow_box_shadow' => $shadow,
			'_title'                => __( 'Card', 'essential-addons-for-elementor-lite' ),
		];

		if ( '' !== $border ) {
			$settings['border_border'] = 'solid';
			$settings['border_width']  = Elements::spacing( 1, 1, 1, 1 );
			$settings['border_color']  = $border;
		}

		return Elements::container( $settings, $children );
	}

	/**
	 * The category browser.
	 *
	 * Advanced Tabs rather than two hand-built columns: the left column is not
	 * decoration, it selects what the right column shows, and a widget that
	 * already does that keeps the behaviour out of this preset entirely.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function category_tabs() {
		return Elements::widget(
			'eael-adv-tabs',
			[
				'eael_adv_tab_new_style'           => 'default',
				'eael_adv_tab_layout'              => 'eael-tabs-vertical',
				// A category list is not an icon menu; eight glyphs beside eight
				// short labels is noise the design does not carry.
				'eael_adv_tabs_icon_show'          => '',
				'eael_adv_tabs_default_active_tab' => 'yes',
				// Off: with the toggle on, clicking the open category closes it and
				// the panel loses its right hand column while the pointer is still
				// inside it.
				'eael_adv_tabs_toggle_tab'         => '',
				'eael_adv_tabs_tab'                => self::category_rows(),

				// Wrapper. The inset the category column sits at; the content beside
				// it takes it back off again below.
				'eael_adv_tabs_padding' => Elements::spacing( 15, 15, 15, 15 ),
				'eael_adv_tabs_margin'  => Elements::spacing( 0, 0, 0, 0 ),

				// Tab titles. A category list is read down, not across — the rows
				// are close enough to scan as one column, and the weight is the
				// plain one so the selected row is told apart by its wash rather
				// than by getting heavier.
				'eael_adv_tabs_tab_title_typography_typography'  => 'custom',
				'eael_adv_tabs_tab_title_typography_font_size'   => Elements::size( 14 ),
				'eael_adv_tabs_tab_title_typography_font_weight' => '400',
				'eael_adv_tabs_tab_title_typography_line_height' => Elements::size( 1.12, 'em' ),
				// This is what places the divider: the content follows the list, and
				// its left border is the line between the two columns. 15 of wrapper
				// inset plus 211 puts it 226 in from the card's edge.
				'eael_adv_tabs_title_width'   => Elements::size( 211 ),
				'eael_adv_tabs_tab_padding'   => Elements::spacing( 8, 8, 8, 8 ),
				'eael_adv_tabs_tab_margin'    => Elements::spacing( 0, 0, 4, 0 ),

				// Normal. Both the deprecated colour control and the background
				// group are set: the group is what the panel now shows, the flat
				// control is what still paints when a site's saved data predates
				// it, and disagreeing values would show one colour and offer
				// another.
				'eael_adv_tabs_tab_color'          => 'rgba(0,0,0,0)',
				'eael_adv_tabs_tab_bgtype_background' => 'classic',
				'eael_adv_tabs_tab_bgtype_color'   => 'rgba(0,0,0,0)',
				'eael_adv_tabs_tab_text_color'     => self::MUTED,
				'eael_adv_tabs_tab_border_radius'  => Elements::spacing( 8, 8, 8, 8 ),

				// Hover.
				'eael_adv_tabs_tab_color_hover'          => self::HOVER,
				'eael_adv_tabs_tab_bgtype_hover_background' => 'classic',
				'eael_adv_tabs_tab_bgtype_hover_color'   => self::HOVER,
				'eael_adv_tabs_tab_text_color_hover'     => self::INK,
				'eael_adv_tabs_tab_border_radius_hover'  => Elements::spacing( 8, 8, 8, 8 ),

				// Active.
				'eael_adv_tabs_tab_color_active'          => self::HOVER,
				'eael_adv_tabs_tab_bgtype_active_background' => 'classic',
				'eael_adv_tabs_tab_bgtype_active_color'   => self::HOVER,
				'eael_adv_tabs_tab_text_color_active'     => self::INK,
				'eael_adv_tabs_tab_border_radius_active'  => Elements::spacing( 8, 8, 8, 8 ),

				// The caret is an arrow pointing from the selected tab into the
				// content. This panel separates the two with a hairline instead.
				'eael_adv_tabs_tab_caret_show' => '',

				// Content.
				// Muted, because the links inherit it — the one the visitor is over
				// darkens to INK, and that contrast is the whole signal.
				'adv_tabs_content_text_color'                    => self::MUTED,
				'eael_adv_tabs_content_typography_typography'    => 'custom',
				'eael_adv_tabs_content_typography_font_size'     => Elements::size( 14 ),
				'eael_adv_tabs_content_typography_line_height'   => Elements::size( 1.12, 'em' ),
				// No inset on the left, so the heading's rule starts on the divider
				// rather than 15px inside it; the words are inset instead, from the
				// class in the widget's stylesheet.
				//
				// In the design the divider and that rule are drawn past the card's
				// edges and clipped by it. Reproducing that took a negative margin
				// here and `overflow: hidden` on the card — and below the tab
				// widget's own 767px breakpoint that pair cut the panel in half:
				// the layout wraps to a column there, the content lands on a second
				// line already 15px taller than the room its margins leave it, and
				// the card clipped the rest. The 15px it buys is not worth a panel
				// that loses its links on a phone.
				'eael_adv_tabs_content_padding'                  => Elements::spacing( 15, 15, 0, 0 ),
				'eael_adv_tabs_content_margin'                   => Elements::spacing( 0, 0, 0, 0 ),
				'eael_adv_tabs_content_border_border'            => 'solid',
				'eael_adv_tabs_content_border_width'             => Elements::spacing( 0, 0, 0, 1 ),
				// Gone once the two columns stack: a line dividing left from right
				// is a line down the left edge of nothing when the right column has
				// moved underneath.
				'eael_adv_tabs_content_border_width_tablet'      => Elements::spacing( 0, 0, 0, 0 ),
				'eael_adv_tabs_content_padding_tablet'           => Elements::spacing( 12, 0, 0, 0 ),
				'eael_adv_tabs_content_border_color'             => self::LINE,
			]
		);
	}

	/**
	 * The categories and the links each one holds.
	 *
	 * @since 6.7.5
	 *
	 * @return array Repeater rows.
	 */
	protected static function category_rows() {
		$categories = [
			[
				'title' => __( 'Engineering & Development', 'essential-addons-for-elementor-lite' ),
				'links' => [
					__( 'Code assistants', 'essential-addons-for-elementor-lite' ),
					__( 'Developer tooling', 'essential-addons-for-elementor-lite' ),
					__( 'API platforms', 'essential-addons-for-elementor-lite' ),
					__( 'Testing & QA', 'essential-addons-for-elementor-lite' ),
					__( 'Deployment', 'essential-addons-for-elementor-lite' ),
				],
			],
			[
				'title' => __( 'LLMs', 'essential-addons-for-elementor-lite' ),
				'links' => [
					__( 'Chat interfaces', 'essential-addons-for-elementor-lite' ),
					__( 'Model providers', 'essential-addons-for-elementor-lite' ),
					__( 'Prompt libraries', 'essential-addons-for-elementor-lite' ),
					__( 'Evaluation', 'essential-addons-for-elementor-lite' ),
					__( 'Fine-tuning', 'essential-addons-for-elementor-lite' ),
				],
			],
			[
				'title' => __( 'Productivity', 'essential-addons-for-elementor-lite' ),
				'links' => [
					__( 'AI notetakers', 'essential-addons-for-elementor-lite' ),
					__( 'Note and writing apps', 'essential-addons-for-elementor-lite' ),
					__( 'Team collaboration software', 'essential-addons-for-elementor-lite' ),
					__( 'Search', 'essential-addons-for-elementor-lite' ),
					__( 'AI Workflow Automation', 'essential-addons-for-elementor-lite' ),
				],
			],
			[
				'title' => __( 'Marketing & Sales', 'essential-addons-for-elementor-lite' ),
				'links' => [
					__( 'Campaign automation', 'essential-addons-for-elementor-lite' ),
					__( 'CRM', 'essential-addons-for-elementor-lite' ),
					__( 'Content generation', 'essential-addons-for-elementor-lite' ),
					__( 'SEO tooling', 'essential-addons-for-elementor-lite' ),
					__( 'Analytics', 'essential-addons-for-elementor-lite' ),
				],
			],
			[
				'title' => __( 'Design & Creative', 'essential-addons-for-elementor-lite' ),
				'links' => [
					__( 'Image generation', 'essential-addons-for-elementor-lite' ),
					__( 'Video editing', 'essential-addons-for-elementor-lite' ),
					__( 'Design systems', 'essential-addons-for-elementor-lite' ),
					__( 'Prototyping', 'essential-addons-for-elementor-lite' ),
					__( 'Brand assets', 'essential-addons-for-elementor-lite' ),
				],
			],
			[
				'title' => __( 'Social & Community', 'essential-addons-for-elementor-lite' ),
				'links' => [
					__( 'Scheduling', 'essential-addons-for-elementor-lite' ),
					__( 'Community platforms', 'essential-addons-for-elementor-lite' ),
					__( 'Social listening', 'essential-addons-for-elementor-lite' ),
					__( 'Creator tools', 'essential-addons-for-elementor-lite' ),
					__( 'Moderation', 'essential-addons-for-elementor-lite' ),
				],
			],
			[
				'title' => __( 'Finance', 'essential-addons-for-elementor-lite' ),
				'links' => [
					__( 'Invoicing', 'essential-addons-for-elementor-lite' ),
					__( 'Expense management', 'essential-addons-for-elementor-lite' ),
					__( 'Payroll', 'essential-addons-for-elementor-lite' ),
					__( 'Forecasting', 'essential-addons-for-elementor-lite' ),
					__( 'Billing', 'essential-addons-for-elementor-lite' ),
				],
			],
			[
				'title' => __( 'AI Agents', 'essential-addons-for-elementor-lite' ),
				'links' => [
					__( 'Agent frameworks', 'essential-addons-for-elementor-lite' ),
					__( 'Browser agents', 'essential-addons-for-elementor-lite' ),
					__( 'Support agents', 'essential-addons-for-elementor-lite' ),
					__( 'Research agents', 'essential-addons-for-elementor-lite' ),
					__( 'Orchestration', 'essential-addons-for-elementor-lite' ),
				],
			],
		];

		$rows = [];

		foreach ( $categories as $index => $category ) {
			$rows[] = Elements::row( [
				'eael_adv_tabs_tab_title'          => $category['title'],
				'eael_adv_tabs_tab_title_html_tag' => 'span',
				'eael_adv_tabs_icon_type'          => 'none',
				'eael_adv_tabs_text_type'          => 'content',
				'eael_adv_tabs_tab_content'        => self::link_list( $category['title'], $category['links'] ),
				// Advanced Tabs falls back to the first tab when nothing is marked
				// active, but only after a frame of no content at all — marking it
				// here means the panel is filled the moment it opens.
				'eael_adv_tabs_tab_show_as_default' => 0 === $index ? 'active-default' : 'inactive',
			] );
		}

		return $rows;
	}

	/**
	 * One category's links, as tab content.
	 *
	 * The single piece of markup this preset writes. A tab holds a WYSIWYG field,
	 * not widgets, so the alternative to a small block of HTML is a row of bare
	 * theme-styled list items — and this way the user edits the links in the same
	 * field they would edit any other tab, with the Visual tab already showing
	 * them as a list.
	 *
	 * The classes are hooks the widget's own stylesheet answers, so the list
	 * arrives laid out rather than as browser default bullets. They carry no
	 * behaviour: strip them and the links still work.
	 *
	 * @since 6.7.5
	 *
	 * @param string $title Column heading.
	 * @param array  $links Link labels.
	 *
	 * @return string
	 */
	protected static function link_list( $title, $links ) {
		$items = '';

		foreach ( $links as $link ) {
			$items .= sprintf( '<li><a href="#">%s</a></li>', esc_html( $link ) );
		}

		return sprintf(
			'<div class="eael-mm-links"><h6 class="eael-mm-links__title">%1$s</h6><ul class="eael-mm-links__list">%2$s</ul></div>',
			esc_html( $title ),
			$items
		);
	}

	/**
	 * The Resources panel: four destinations, each an icon, a name and a line.
	 *
	 * @since 6.7.5
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function resources_panel( $title ) {
		// Outline glyphs for the three that have one, solid for the book, which
		// Font Awesome 5 Free does not ship in the regular weight. The library has
		// to match the prefix or the icon control shows an empty box.
		$resources = [
			[
				'icon'    => 'far fa-newspaper',
				'library' => 'fa-regular',
				'title'   => __( 'Blog', 'essential-addons-for-elementor-lite' ),
				'text'    => __( 'Product news and release notes.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'    => 'far fa-user',
				'library' => 'fa-regular',
				'title'   => __( 'About Us', 'essential-addons-for-elementor-lite' ),
				'text'    => __( 'Who we are and what we are building.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'    => 'far fa-envelope',
				'library' => 'fa-regular',
				'title'   => __( 'Contact', 'essential-addons-for-elementor-lite' ),
				'text'    => __( 'Talk to sales, support or the team.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'    => 'fas fa-book-open',
				'library' => 'fa-solid',
				'title'   => __( 'Documentations', 'essential-addons-for-elementor-lite' ),
				'text'    => __( 'Guides, references and how-tos.', 'essential-addons-for-elementor-lite' ),
			],
		];

		$rows = [];

		foreach ( $resources as $resource ) {
			$rows[] = self::resource_row( $resource );
		}

		return Elements::nested_child(
			self::panel_settings( $title ),
			[
				// Rounder than the catalogue's card and with no edge at all, which
				// is why each panel carries its own rather than sharing the
				// widget's — see {@see card()}. Four destinations, not a list of
				// four: the wash under a hovered row has to read as that row being
				// picked out, and at a two pixel gap it reads as the row above and
				// below being pushed apart.
				self::card(
					$rows,
					16,
					'',
					Elements::shadow( 12, 18, 'rgba(0, 1, 35, 0.12)' ),
					16,
					10
				),
			]
		);
	}

	/**
	 * One resource row.
	 *
	 * The link and the hover wash live on the container rather than on the Info
	 * Box: a row a visitor can only click on its title is a row that misses most
	 * of the pointer's travel, and the Info Box has no hover background of its
	 * own to light the whole strip with.
	 *
	 * @since 6.7.5
	 *
	 * @param array $resource Icon, title and description.
	 *
	 * @return array
	 */
	protected static function resource_row( $resource ) {
		return Elements::container(
			[
				'content_width'        => 'full',
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'space-between',
				'flex_wrap'            => 'nowrap',
				'flex_gap'             => Elements::gap( 8 ),
				// The tile is 48 and sets the row's height; this is the margin
				// around it, not the row's own spacing — that is the gap between
				// rows above. Tighter on the side the tile sits against than on the
				// side the chevron does, so the two end up optically level.
				'padding'              => Elements::spacing( 8, 12, 8, 8 ),
				'border_radius'        => Elements::spacing( 16, 16, 16, 16 ),
				'link'                 => Elements::link(),
				'background_hover_background' => 'classic',
				'background_hover_color'      => self::HOVER,
				'background_hover_transition' => Elements::size( 0.2 ),
				'_title'               => $resource['title'],
			],
			[
				self::resource_info_box( $resource ),
				self::resource_chevron(),
			]
		);
	}

	/**
	 * The icon, name and description of one resource.
	 *
	 * @since 6.7.5
	 *
	 * @param array $resource Icon, library, title and description.
	 *
	 * @return array
	 */
	protected static function resource_info_box( $resource ) {
		return Elements::widget(
			'eael-info-box',
			[
				'eael_infobox_img_type'     => 'img-on-left',
				'eael_infobox_img_or_icon'  => 'icon',
				'icon_vertical_position'    => 'middle',
				'eael_infobox_icon_new'     => Elements::icon( $resource['icon'], $resource['library'] ),
				'eael_infobox_title'        => $resource['title'],
				'eael_infobox_title_tag'    => 'h6',
				'eael_infobox_text_type'    => 'content',
				'eael_infobox_text'         => '<p>' . esc_html( $resource['text'] ) . '</p>',
				// The row's own container is the link; a button inside it would be
				// a second target sitting on top of the first.
				'eael_show_infobox_button'  => '',
				'eael_infobox_content_alignment_left_right' => 'left',

				// Icon. An outlined tile rather than a filled one, and the accent
				// saved for the row under the pointer: four green glyphs sitting in
				// four green tiles is four rows all claiming to be the live one.
				'eael_infobox_icon_size'     => Elements::size( 28 ),
				'eael_infobox_icon_bg_shape' => 'radius',
				'eael_infobox_icon_bg_size'  => Elements::size( 48 ),
				'eael_infobox_icon_bg_color' => self::SURFACE,
				'eael_infobox_icon_border_border' => 'solid',
				'eael_infobox_icon_border_width'  => Elements::spacing( 1, 1, 1, 1 ),
				'eael_infobox_icon_border_color'  => self::LINE,
				'eael_infobox_icon_color'    => self::MUTED,
				// Set outright rather than left alone: this control ships a #4d4d4d
				// default, so an unset value is not "no change" — it is the glyph
				// going grey the moment the pointer arrives, which is the opposite
				// of what the row is meant to do.
				'eael_infobox_icon_hover_color' => self::ACCENT,
				'eael_infobox_icon_margin'   => Elements::spacing( 0, 16, 0, 0 ),

				// Title.
				'eael_infobox_title_color_type' => 'classic',
				'eael_infobox_title_color'      => self::INK,
				'eael_infobox_title_typography_typography'  => 'custom',
				'eael_infobox_title_typography_font_size'   => Elements::size( 16 ),
				'eael_infobox_title_typography_font_weight' => '500',
				'eael_infobox_title_typography_line_height' => Elements::size( 1.2, 'em' ),
				'eael_infobox_title_margin'     => Elements::spacing( 0, 0, 4, 0 ),

				// Description.
				'eael_infobox_content_color'  => self::MUTED,
				'eael_infobox_content_margin' => Elements::spacing( 0, 0, 0, 0 ),
				'eael_infobox_content_typography_hover_typography' => 'custom',
				'eael_infobox_content_typography_hover_font_size'  => Elements::size( 13 ),
				'eael_infobox_content_typography_hover_line_height' => Elements::size( 1.5, 'em' ),

				// Container — the row around it already supplies the inset.
				'eael_section_infobox_container_padding' => Elements::spacing( 0, 0, 0, 0 ),
				'eael_section_infobox_container_margin'  => Elements::spacing( 0, 0, 0, 0 ),
			]
		);
	}

	/**
	 * The chevron at the end of a resource row.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function resource_chevron() {
		return Elements::widget(
			'icon',
			[
				'selected_icon' => Elements::icon( 'fas fa-chevron-right' ),
				'view'          => 'default',
				'primary_color' => self::CHEVRON,
				'size'          => Elements::size( 16 ),
				'_title'        => __( 'Chevron', 'essential-addons-for-elementor-lite' ),
			]
		);
	}
}

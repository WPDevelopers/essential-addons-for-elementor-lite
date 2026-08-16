<?php
/**
 * The Mega Menu header preset.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Presets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Essential_Addons_Elementor\MegaMenu\Manager;

/**
 * A header built around the EA Mega Menu widget.
 *
 * Three columns — brand, navigation, actions — with two of the menu items opening
 * a full width mega panel that is itself made of ordinary containers and widgets.
 * Nothing here is a fixed image or a block of custom CSS: every colour, size and
 * label is a control value, so the whole header stays editable in the panel after
 * it is inserted. That is also why the styling is spelled out rather than left to
 * defaults — a value the user can see in the panel is a value they can change,
 * and an empty control that inherits the theme is one they cannot find.
 *
 * Responsive behaviour is split between the two layers that own it: the container
 * widths carry `_tablet` / `_mobile` values, and the widget collapses itself into
 * a toggle at the mobile breakpoint. The navigation column is ordered last on
 * mobile so that toggle lands beside the other icons instead of in the middle of
 * the bar.
 *
 * @since 6.7.3
 */
class Mega_Header {

	/**
	 * Brand colour, used for links, the call to action and every accent.
	 */
	const ACCENT = '#5B3DF5';

	/**
	 * Heading / menu item ink.
	 */
	const INK = '#1D1F2B';

	/**
	 * Body copy.
	 */
	const BODY = '#4B5563';

	/**
	 * Secondary copy.
	 */
	const MUTED = '#6B7280';

	/**
	 * Header surface.
	 */
	const SURFACE = '#FFFFFF';

	/**
	 * Is the Mega Menu widget usable on this install.
	 *
	 * The preset is nothing without it, and the widget can be absent for two
	 * unrelated reasons — an Elementor without the nested elements API, or the
	 * element switched off in the EA settings. Asking the widgets manager covers
	 * both: it answers with what is actually registered for this editor session.
	 *
	 * @since 6.7.3
	 *
	 * @return bool
	 */
	public static function is_available() {
		return Elements::has_widget( Manager::WIDGET_NAME );
	}

	/**
	 * The elements this preset inserts.
	 *
	 * @since 6.7.3
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
					'flex_gap_tablet'       => Elements::gap( 16 ),
					'flex_gap_mobile'       => Elements::gap( 8 ),
					'padding'               => Elements::spacing( 14, 24, 14, 24 ),
					'padding_tablet'        => Elements::spacing( 12, 20, 12, 20 ),
					'padding_mobile'        => Elements::spacing( 10, 16, 10, 16 ),
					'background_background' => 'classic',
					'background_color'      => self::SURFACE,
					'box_shadow_box_shadow_type' => 'yes',
					'box_shadow_box_shadow' => Elements::shadow( 2, 12, 'rgba(16, 18, 32, 0.08)' ),
					'_title'                => __( 'Header Bar', 'essential-addons-for-elementor-lite' ),
				],
				[
					self::brand(),
					self::navigation(),
					self::actions(),
				]
			),
		];
	}

	/* ---------------------------------------------------------------------
	 * Header columns.
	 * ------------------------------------------------------------------ */

	/**
	 * The logo column.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function brand() {
		return Elements::container(
			[
				'content_width'        => 'full',
				'width'                => Elements::size( 20, '%' ),
				'width_tablet'         => Elements::size( 20, '%' ),
				'width_mobile'         => Elements::size( 42, '%' ),
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'flex-start',
				'flex_gap'             => Elements::gap( 10 ),
				'_title'               => __( 'Brand', 'essential-addons-for-elementor-lite' ),
			],
			[ self::logo() ]
		);
	}

	/**
	 * The navigation column.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function navigation() {
		return Elements::container(
			[
				'content_width'               => 'full',
				'width'                       => Elements::size( 54, '%' ),
				'width_tablet'                => Elements::size( 56, '%' ),
				'width_mobile'                => Elements::size( 16, '%' ),
				'flex_direction'              => 'row',
				'flex_align_items'            => 'center',
				'flex_justify_content'        => 'center',
				'flex_justify_content_mobile' => 'flex-end',
				// Below the menu's own breakpoint this column holds nothing but the
				// toggle button, and a toggle sitting between the logo and the
				// search icon reads as a third, unrelated control. Ordering the
				// column last puts it where a hamburger is expected.
				'_flex_order_mobile'          => 'end',
				'_title'                      => __( 'Navigation', 'essential-addons-for-elementor-lite' ),
			],
			[
				Elements::widget( Manager::WIDGET_NAME, self::menu_settings(), self::panels() ),
			]
		);
	}

	/**
	 * The search / cart / call to action column.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function actions() {
		return Elements::container(
			[
				'content_width'        => 'full',
				'width'                => Elements::size( 22, '%' ),
				'width_tablet'         => Elements::size( 22, '%' ),
				'width_mobile'         => Elements::size( 34, '%' ),
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'flex-end',
				'flex_wrap'            => 'nowrap',
				'flex_gap'             => Elements::gap( 16 ),
				'flex_gap_tablet'      => Elements::gap( 12 ),
				'flex_gap_mobile'      => Elements::gap( 8 ),
				'_title'               => __( 'Actions', 'essential-addons-for-elementor-lite' ),
			],
			[
				self::action_icon( 'fas fa-search', __( 'Search', 'essential-addons-for-elementor-lite' ) ),
				self::action_icon( 'fas fa-shopping-bag', __( 'Cart', 'essential-addons-for-elementor-lite' ) ),
				self::cta_button(),
			]
		);
	}

	/* ---------------------------------------------------------------------
	 * Header widgets.
	 * ------------------------------------------------------------------ */

	/**
	 * The site logo, or its name when no logo is set.
	 *
	 * @since 6.7.3
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
					'image'               => [ 'id' => $logo_id, 'url' => $logo ],
					'image_size'          => 'full',
					'align'               => 'left',
					'width'               => Elements::size( 160 ),
					'width_mobile'        => Elements::size( 120 ),
					'link_to'             => 'custom',
					'link'                => Elements::link( home_url( '/' ) ),
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
				'typography_font_size'        => Elements::size( 24 ),
				'typography_font_size_tablet' => Elements::size( 21 ),
				'typography_font_size_mobile' => Elements::size( 19 ),
				'typography_font_weight'      => '700',
			]
		);
	}

	/**
	 * One of the icon buttons beside the call to action.
	 *
	 * @since 6.7.3
	 *
	 * @param string $icon  Font Awesome class.
	 * @param string $label Navigator title.
	 *
	 * @return array
	 */
	protected static function action_icon( $icon, $label ) {
		return Elements::widget(
			'icon',
			[
				'selected_icon'       => Elements::icon( $icon ),
				'view'                => 'default',
				'link'                => Elements::link(),
				'size'                => Elements::size( 18 ),
				'primary_color'       => self::INK,
				'hover_primary_color' => self::ACCENT,
				'_title'              => $label,
			]
		);
	}

	/**
	 * The call to action.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function cta_button() {
		return Elements::widget(
			'button',
			[
				'text'                   => __( 'Get Started', 'essential-addons-for-elementor-lite' ),
				'link'                   => Elements::link(),
				'size'                   => 'sm',
				// Spelled out rather than inherited: a theme's button colour is
				// rarely the one a header wants next to a logo.
				'background_color'       => self::ACCENT,
				'button_text_color'      => self::SURFACE,
				'hover_color'            => self::SURFACE,
				'button_background_hover_color' => '#4A2FE0',
				'border_radius'          => Elements::spacing( 8, 8, 8, 8 ),
				'text_padding'           => Elements::spacing( 11, 22, 11, 22 ),
				'text_padding_tablet'    => Elements::spacing( 10, 14, 10, 14 ),
				'typography_typography'  => 'custom',
				'typography_font_size'   => Elements::size( 14 ),
				'typography_font_size_tablet' => Elements::size( 13 ),
				'typography_font_weight' => '600',
				// The first thing worth dropping on a phone: the logo and the
				// navigation toggle still have to fit beside each other there.
				'hide_mobile'            => 'hidden-mobile',
			]
		);
	}

	/* ---------------------------------------------------------------------
	 * Mega Menu.
	 * ------------------------------------------------------------------ */

	/**
	 * The menu items, in bar order.
	 *
	 * Two of them open a mega panel and the rest are plain links — a menu where
	 * every item drops a panel is a menu nobody ships. Switching any of the others
	 * over is one control away, and the nested container each already owns is
	 * waiting for it.
	 *
	 * @since 6.7.3
	 *
	 * @return array List of [ label, type ] pairs.
	 */
	protected static function item_map() {
		return [
			[ 'label' => __( 'Home', 'essential-addons-for-elementor-lite' ), 'type' => 'link' ],
			[ 'label' => __( 'About', 'essential-addons-for-elementor-lite' ), 'type' => 'link' ],
			[ 'label' => __( 'Features', 'essential-addons-for-elementor-lite' ), 'type' => 'mega' ],
			[ 'label' => __( 'Shop', 'essential-addons-for-elementor-lite' ), 'type' => 'mega' ],
			[ 'label' => __( 'Blog', 'essential-addons-for-elementor-lite' ), 'type' => 'link' ],
			[ 'label' => __( 'Contact', 'essential-addons-for-elementor-lite' ), 'type' => 'link' ],
		];
	}

	/**
	 * Repeater rows for the menu items.
	 *
	 * @since 6.7.3
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
				// neither clickable nor focusable. Mega items are left unlinked so
				// the item itself opens its panel.
				$row['eael_mega_menu_item_link'] = Elements::link();
			} else {
				// Viewport, not "full width (menu)". "Full" means the width of the
				// menu bar, and the bar here is the middle third of the header —
				// five columns inside it wrap into an unreadable stack. A viewport
				// panel gets the room the layout needs; the boxed panel container
				// below is what keeps its content lined up with the header.
				$row['eael_mega_menu_item_submenu_width'] = 'viewport';
			}

			$rows[] = Elements::row( $row );
		}

		return $rows;
	}

	/**
	 * Widget settings for the menu.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function menu_settings() {
		return [
			'eael_mega_menu_items'             => self::menu_items(),

			'eael_mega_menu_trigger'           => 'hover',
			'eael_mega_menu_animation'         => 'slide-down',
			'eael_mega_menu_animation_duration' => Elements::size( 250 ),
			'eael_mega_menu_stretch'           => 'yes',
			'eael_mega_menu_align'             => 'center',
			'eael_mega_menu_indicator_icon'    => Elements::icon( 'fas fa-chevron-down' ),

			// Mobile only, not tablet: seven short labels still fit on a tablet at
			// the reduced type size set below, and a tablet that shows the menu is
			// a tablet where the mega panels are usable.
			'eael_mega_menu_breakpoint'        => 'mobile',
			// Empty on purpose — beside a logo and two icons the word "Menu" is the
			// one element with nowhere to go on a 360px screen.
			'eael_mega_menu_toggle_text'       => '',
			'eael_mega_menu_toggle_full_width' => 'yes',

			// Menu bar: transparent, because it sits on the header's own surface.
			'eael_mega_menu_bar_background_background' => 'classic',
			'eael_mega_menu_bar_background_color' => 'rgba(0,0,0,0)',
			'eael_mega_menu_bar_gap'           => Elements::size( 4 ),
			'eael_mega_menu_bar_gap_tablet'    => Elements::size( 0 ),
			'eael_mega_menu_bar_padding'       => Elements::spacing( 0, 0, 0, 0 ),

			// Items. Every colour is spelled out, transparents included: left empty
			// the setting emits no rule at all and the theme's own link styling
			// wins, which on a typical install paints the menu in the theme accent.
			'eael_mega_menu_item_typography_typography'       => 'custom',
			'eael_mega_menu_item_typography_font_size'        => Elements::size( 15 ),
			'eael_mega_menu_item_typography_font_size_tablet' => Elements::size( 12 ),
			'eael_mega_menu_item_typography_font_weight'      => '500',
			'eael_mega_menu_item_padding'      => Elements::spacing( 10, 14, 10, 14 ),
			'eael_mega_menu_item_padding_tablet' => Elements::spacing( 8, 7, 8, 7 ),
			'eael_mega_menu_item_radius'       => Elements::spacing( 8, 8, 8, 8 ),
			'eael_mega_menu_item_color'        => self::INK,
			'eael_mega_menu_item_bg'           => 'rgba(0,0,0,0)',
			'eael_mega_menu_item_color_hover'  => self::ACCENT,
			'eael_mega_menu_item_bg_hover'     => 'rgba(91, 61, 245, 0.06)',
			'eael_mega_menu_item_color_active' => self::ACCENT,
			'eael_mega_menu_item_bg_active'    => 'rgba(91, 61, 245, 0.08)',

			// Indicator.
			'eael_mega_menu_indicator_size'    => Elements::size( 11 ),
			'eael_mega_menu_indicator_gap'     => Elements::size( 6 ),
			'eael_mega_menu_indicator_rotate'  => 'yes',
			'eael_mega_menu_indicator_color'   => self::MUTED,
			'eael_mega_menu_indicator_color_active' => self::ACCENT,

			// Panel. Padding is left to the container inside it so the user styles
			// the panel's inset where they can see it, on the layout itself.
			'eael_mega_menu_panel_background_background' => 'classic',
			'eael_mega_menu_panel_background_color' => self::SURFACE,
			// Bottom corners only, and flush against the bar: the panel spans the
			// viewport, so rounding its top corners would round them against the
			// screen edges, and a gap below a hover menu is a strip the pointer has
			// to cross to reach the panel.
			'eael_mega_menu_panel_radius'      => Elements::spacing( 0, 0, 16, 16 ),
			// Side padding matches the header bar's own, so the panel columns line
			// up with the logo above them; the boxed panel container supplies the
			// rest of the inset on wide screens.
			'eael_mega_menu_panel_padding'     => Elements::spacing( 32, 24, 32, 24 ),
			'eael_mega_menu_panel_padding_tablet' => Elements::spacing( 28, 20, 28, 20 ),
			'eael_mega_menu_panel_padding_mobile' => Elements::spacing( 16, 14, 16, 14 ),
			'eael_mega_menu_panel_shadow_box_shadow_type' => 'yes',
			'eael_mega_menu_panel_shadow_box_shadow' => Elements::shadow( 18, 44, 'rgba(16, 18, 32, 0.14)' ),
			'eael_mega_menu_panel_offset'      => Elements::size( 0 ),
			'eael_mega_menu_panel_z_index'     => 999,

			// Mobile toggle.
			'eael_mega_menu_toggle_align'      => 'flex-end',
			'eael_mega_menu_toggle_icon_size'  => Elements::size( 18 ),
			'eael_mega_menu_toggle_padding'    => Elements::spacing( 9, 11, 9, 11 ),
			'eael_mega_menu_toggle_radius'     => Elements::spacing( 8, 8, 8, 8 ),
			'eael_mega_menu_toggle_color'      => self::INK,
			'eael_mega_menu_toggle_bg'         => 'rgba(91, 61, 245, 0.08)',
			'eael_mega_menu_toggle_color_hover' => self::SURFACE,
			'eael_mega_menu_toggle_bg_hover'   => self::ACCENT,

			// Collapsed dropdown.
			'eael_mega_menu_dropdown_background_background' => 'classic',
			'eael_mega_menu_dropdown_background_color' => self::SURFACE,
			'eael_mega_menu_dropdown_radius'   => Elements::spacing( 14, 14, 14, 14 ),
			'eael_mega_menu_dropdown_padding'  => Elements::spacing( 10, 10, 10, 10 ),
			'eael_mega_menu_dropdown_shadow_box_shadow_type' => 'yes',
			'eael_mega_menu_dropdown_shadow_box_shadow' => Elements::shadow( 16, 40, 'rgba(16, 18, 32, 0.16)' ),
			// The accordion opens inside the dropdown, so a menu with two mega
			// panels can outgrow a phone screen; scrolling it beats clipping it.
			'eael_mega_menu_dropdown_max_height' => Elements::size( 75, 'vh' ),
		];
	}

	/**
	 * The nested containers, one per menu item.
	 *
	 * Positional: the widget prints child *n* for row *n*, so a link item gets an
	 * empty container rather than no container at all — skipping one would shift
	 * every panel after it onto the wrong item.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function panels() {
		$panels = [];

		foreach ( self::item_map() as $index => $item ) {
			switch ( $index ) {
				case 2:
					$panels[] = self::features_panel( $item['label'] );
					break;

				case 3:
					$panels[] = self::shop_panel( $item['label'] );
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

	/* ---------------------------------------------------------------------
	 * Mega panels.
	 * ------------------------------------------------------------------ */

	/**
	 * The Features panel: a featured column, three link columns and a promo.
	 *
	 * @since 6.7.3
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function features_panel( $title ) {
		return Elements::nested_child(
			self::panel_settings( $title ),
			[
				self::feature_column(),
				self::link_column(
					__( 'Content', 'essential-addons-for-elementor-lite' ),
					[
						__( 'Advanced Heading', 'essential-addons-for-elementor-lite' ),
						__( 'Dual Button', 'essential-addons-for-elementor-lite' ),
						__( 'Info Box', 'essential-addons-for-elementor-lite' ),
						__( 'Call To Action', 'essential-addons-for-elementor-lite' ),
						__( 'Testimonial Carousel', 'essential-addons-for-elementor-lite' ),
					],
					17,
					30
				),
				self::link_column(
					__( 'Marketing', 'essential-addons-for-elementor-lite' ),
					[
						__( 'Price Table', 'essential-addons-for-elementor-lite' ),
						__( 'Countdown Timer', 'essential-addons-for-elementor-lite' ),
						__( 'Flip Box', 'essential-addons-for-elementor-lite' ),
						__( 'Team Member', 'essential-addons-for-elementor-lite' ),
						__( 'Logo Carousel', 'essential-addons-for-elementor-lite' ),
					],
					17,
					30
				),
				self::link_column(
					__( 'Media', 'essential-addons-for-elementor-lite' ),
					[
						__( 'Image Gallery', 'essential-addons-for-elementor-lite' ),
						__( 'Image Accordion', 'essential-addons-for-elementor-lite' ),
						__( 'Video Gallery', 'essential-addons-for-elementor-lite' ),
						__( 'Advanced Slider', 'essential-addons-for-elementor-lite' ),
						__( 'Image Comparison', 'essential-addons-for-elementor-lite' ),
					],
					17,
					30
				),
				self::promo_column(
					__( 'Build Better with EA', 'essential-addons-for-elementor-lite' ),
					__( 'Explore 100+ advanced widgets and extensions.', 'essential-addons-for-elementor-lite' ),
					'',
					18,
					30
				),
			]
		);
	}

	/**
	 * The Shop panel: two link columns and a promo with a call to action.
	 *
	 * @since 6.7.3
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function shop_panel( $title ) {
		return Elements::nested_child(
			self::panel_settings( $title ),
			[
				self::link_column(
					__( 'Shop by Category', 'essential-addons-for-elementor-lite' ),
					[
						__( 'New Arrivals', 'essential-addons-for-elementor-lite' ),
						__( 'Best Sellers', 'essential-addons-for-elementor-lite' ),
						__( 'Accessories', 'essential-addons-for-elementor-lite' ),
						__( 'Footwear', 'essential-addons-for-elementor-lite' ),
						__( 'Gift Cards', 'essential-addons-for-elementor-lite' ),
					],
					28,
					30
				),
				self::link_column(
					__( 'Collections', 'essential-addons-for-elementor-lite' ),
					[
						__( 'Summer Edit', 'essential-addons-for-elementor-lite' ),
						__( 'Limited Drops', 'essential-addons-for-elementor-lite' ),
						__( 'Everyday Basics', 'essential-addons-for-elementor-lite' ),
						__( 'Work & Travel', 'essential-addons-for-elementor-lite' ),
						__( 'Clearance', 'essential-addons-for-elementor-lite' ),
					],
					28,
					30
				),
				self::promo_column(
					__( 'Season Sale', 'essential-addons-for-elementor-lite' ),
					__( 'Up to 40% off across the new collection.', 'essential-addons-for-elementor-lite' ),
					__( 'Shop Now', 'essential-addons-for-elementor-lite' ),
					30,
					34
				),
			]
		);
	}

	/**
	 * Settings shared by both mega panels.
	 *
	 * @since 6.7.3
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function panel_settings( $title ) {
		return [
			// Boxed, so the columns line up with the header's own content column
			// while the panel's surface still runs the full width of the viewport.
			'content_width'    => 'boxed',
			'flex_direction'   => 'row',
			'flex_align_items' => 'flex-start',
			// Wrap, not nowrap: the column widths below add up to just under the
			// full row on each breakpoint, but a user who widens one column or adds
			// a sixth should get a second row rather than a squeezed first one.
			'flex_wrap'        => 'wrap',
			'flex_gap'         => Elements::gap( 28 ),
			'flex_gap_tablet'  => Elements::gap( 20 ),
			'flex_gap_mobile'  => Elements::gap( 24 ),
			// No padding here on purpose. The widget's Submenu Panel > Padding
			// control writes the very `--padding-*` variables a container reads, so
			// whichever value it holds wins over anything set on this container —
			// including a zero. It is set on the widget instead, once.
			'_title'           => $title,
		];
	}

	/**
	 * A panel column.
	 *
	 * @since 6.7.3
	 *
	 * @param int    $width    Desktop width, in percent.
	 * @param int    $tablet   Tablet width, in percent.
	 * @param array  $children Child elements.
	 * @param string $title    Navigator title.
	 *
	 * @return array
	 */
	protected static function column( $width, $tablet, $children, $title = '' ) {
		$settings = [
			'content_width'  => 'full',
			'width'          => Elements::size( $width, '%' ),
			'width_tablet'   => Elements::size( $tablet, '%' ),
			// Stacked on a phone: the panel is inside the collapsed dropdown there,
			// and a column of link lists side by side is unreadable at that width.
			'width_mobile'   => Elements::size( 100, '%' ),
			'flex_direction' => 'column',
			'flex_gap'       => Elements::gap( 12 ),
			'padding'        => Elements::spacing( 0, 0, 0, 0 ),
		];

		if ( '' !== $title ) {
			$settings['_title'] = $title;
		}

		return Elements::container( $settings, $children );
	}

	/**
	 * The featured column: icon, title, description and a link.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function feature_column() {
		return self::column(
			21,
			30,
			[
				Elements::widget(
					'icon-box',
					[
						'selected_icon'      => Elements::icon( 'fas fa-cubes' ),
						'view'               => 'stacked',
						'shape'              => 'square',
						'position'           => 'top',
						'text_align'         => 'left',
						'title_text'         => __( 'Elementor Widgets', 'essential-addons-for-elementor-lite' ),
						'description_text'   => __( 'Powerful widgets to build anything with Elementor.', 'essential-addons-for-elementor-lite' ),
						'title_size'         => 'h4',
						'link'               => Elements::link(),
						// Stacked view: primary paints the tile, secondary the glyph.
						'primary_color'      => 'rgba(91, 61, 245, 0.10)',
						'secondary_color'    => self::ACCENT,
						'icon_size'          => Elements::size( 22 ),
						'icon_padding'       => Elements::size( 14 ),
						'icon_space'         => Elements::size( 16 ),
						'title_bottom_space' => Elements::size( 8 ),
						'title_color'        => self::ACCENT,
						'title_typography_typography'  => 'custom',
						'title_typography_font_size'   => Elements::size( 16 ),
						'title_typography_font_weight' => '600',
						'description_color'  => self::MUTED,
						'description_typography_typography'  => 'custom',
						'description_typography_font_size'   => Elements::size( 14 ),
						'description_typography_line_height' => Elements::size( 1.6, 'em' ),
					]
				),
				Elements::widget(
					'button',
					[
						'text'                  => __( 'Explore', 'essential-addons-for-elementor-lite' ),
						'link'                  => Elements::link(),
						'align'                 => 'left',
						'size'                  => 'sm',
						'selected_icon'         => Elements::icon( 'fas fa-arrow-right' ),
						'icon_align'            => 'right',
						'icon_indent'           => Elements::size( 8 ),
						'background_color'      => 'rgba(0,0,0,0)',
						'button_text_color'     => self::ACCENT,
						'hover_color'           => self::INK,
						'button_background_hover_color' => 'rgba(0,0,0,0)',
						'text_padding'          => Elements::spacing( 0, 0, 0, 0 ),
						'typography_typography' => 'custom',
						'typography_font_size'  => Elements::size( 14 ),
						'typography_font_weight' => '600',
					]
				),
			],
			__( 'Featured', 'essential-addons-for-elementor-lite' )
		);
	}

	/**
	 * A column of links under a heading.
	 *
	 * @since 6.7.3
	 *
	 * @param string $title  Column heading.
	 * @param array  $links  Link labels.
	 * @param int    $width  Desktop width, in percent.
	 * @param int    $tablet Tablet width, in percent.
	 *
	 * @return array
	 */
	protected static function link_column( $title, $links, $width, $tablet ) {
		$rows = [];

		foreach ( $links as $link ) {
			$rows[] = Elements::row( [
				'text'          => $link,
				'link'          => Elements::link(),
				// Explicitly empty: the icon list defaults every new row to a tick,
				// and a navigation column is not a checklist.
				'selected_icon' => Elements::icon( '' ),
			] );
		}

		return self::column(
			$width,
			$tablet,
			[
				self::column_heading( $title ),
				Elements::widget(
					'icon-list',
					[
						'view'                       => 'traditional',
						'icon_list'                  => $rows,
						'space_between'              => Elements::size( 14 ),
						'text_indent'                => Elements::size( 0 ),
						'text_color'                 => self::BODY,
						'text_color_hover'           => self::ACCENT,
						'icon_typography_typography' => 'custom',
						'icon_typography_font_size'  => Elements::size( 14 ),
					]
				),
			],
			$title
		);
	}

	/**
	 * A promo column: image, heading, copy and an optional button.
	 *
	 * @since 6.7.3
	 *
	 * @param string $title  Heading.
	 * @param string $copy   Description.
	 * @param string $button Button label, empty for no button.
	 * @param int    $width  Desktop width, in percent.
	 * @param int    $tablet Tablet width, in percent.
	 *
	 * @return array
	 */
	protected static function promo_column( $title, $copy, $button, $width, $tablet ) {
		$children = [
			Elements::widget(
				'image',
				[
					'image'               => [ 'url' => Elements::placeholder_image() ],
					'image_size'          => 'full',
					'align'               => 'left',
					'width'               => Elements::size( 100, '%' ),
					'image_border_radius' => Elements::spacing( 12, 12, 12, 12 ),
				]
			),
			Elements::widget(
				'heading',
				[
					'title'                  => $title,
					'header_size'            => 'h4',
					'title_color'            => self::ACCENT,
					'typography_typography'  => 'custom',
					'typography_font_size'   => Elements::size( 16 ),
					'typography_font_weight' => '600',
				]
			),
			Elements::widget(
				'text-editor',
				[
					'editor'                    => '<p>' . $copy . '</p>',
					'text_color'                => self::MUTED,
					'typography_typography'     => 'custom',
					'typography_font_size'      => Elements::size( 14 ),
					'typography_line_height'    => Elements::size( 1.6, 'em' ),
				]
			),
		];

		if ( '' !== $button ) {
			$children[] = Elements::widget(
				'button',
				[
					'text'                   => $button,
					'link'                   => Elements::link(),
					'align'                  => 'left',
					'size'                   => 'sm',
					'background_color'       => self::ACCENT,
					'button_text_color'      => self::SURFACE,
					'hover_color'            => self::SURFACE,
					'button_background_hover_color' => '#4A2FE0',
					'border_radius'          => Elements::spacing( 8, 8, 8, 8 ),
					'text_padding'           => Elements::spacing( 10, 20, 10, 20 ),
					'typography_typography'  => 'custom',
					'typography_font_size'   => Elements::size( 14 ),
					'typography_font_weight' => '600',
				]
			);
		}

		return self::column( $width, $tablet, $children, $title );
	}

	/**
	 * A panel column heading.
	 *
	 * @since 6.7.3
	 *
	 * @param string $title Heading text.
	 *
	 * @return array
	 */
	protected static function column_heading( $title ) {
		return Elements::widget(
			'heading',
			[
				'title'                  => $title,
				'header_size'            => 'h5',
				'title_color'            => self::ACCENT,
				'typography_typography'  => 'custom',
				'typography_font_size'   => Elements::size( 15 ),
				'typography_font_weight' => '700',
			]
		);
	}
}

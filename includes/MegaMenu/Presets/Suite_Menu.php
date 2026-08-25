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
 * - **Solutions** — the catalogue. A column of three destination cards on the
 *   left, built from EA's Info Box inside linked containers so the whole card is
 *   the target, and three columns of icon links beside them. Everything is
 *   visible at once; the cards lead somewhere rather than switching the columns,
 *   which is what the chevron on each of them means.
 * - **Quick Help** — a plain list of five links, narrow, no icons and no
 *   descriptions. Not every menu item needs a panel the width of the page.
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
	 * Headings, menu items and link text.
	 */
	const INK = '#0F172A';

	/**
	 * Descriptions, column headings and the resting icon colour.
	 */
	const MUTED = '#64748B';

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
	const HOVER = '#F5F3FF';

	/**
	 * The tile behind a category icon.
	 */
	const ICON_BG = '#EDE9FE';

	/**
	 * Hairlines — the rule under each column heading.
	 */
	const LINE = '#E9E7F3';

	/**
	 * The call to action's surface.
	 */
	const BUTTON = '#C7BFFF';

	/**
	 * The call to action's label, on that surface.
	 */
	const BUTTON_INK = '#1E1B4B';

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
				'padding'        => Elements::spacing( 20, 20, 0, 20 ),
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
				'padding'               => Elements::spacing( 14, 28, 14, 28 ),
				'padding_tablet'        => Elements::spacing( 12, 20, 12, 20 ),
				'padding_mobile'        => Elements::spacing( 10, 16, 10, 16 ),
				'background_background' => 'classic',
				'background_color'      => self::SURFACE,
				'border_radius'         => Elements::spacing( 26, 26, 26, 26 ),
				'border_radius_mobile'  => Elements::spacing( 18, 18, 18, 18 ),
				'box_shadow_box_shadow_type' => 'yes',
				'box_shadow_box_shadow' => Elements::shadow( 10, 34, 'rgba(15, 23, 42, 0.10)' ),
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
				// "Stretch" — fills the custom width set below.
				'align'                         => 'justify',
				'_element_width'                => 'initial',
				'_element_custom_width'         => Elements::size( 71 ),
				'_element_custom_width_mobile'  => Elements::size( 71 ),

				'background_color'              => self::BUTTON,
				'button_text_color'             => self::BUTTON_INK,
				'hover_color'                   => self::SURFACE,
				'button_background_hover_color' => self::ACCENT,
				'border_radius'                 => Elements::spacing( 12, 12, 12, 12 ),
				'text_padding'                  => Elements::spacing( 11, 0, 11, 0 ),

				'typography_typography'         => 'custom',
				'typography_font_size'          => Elements::size( 14 ),
				'typography_font_weight'        => '600',
				'typography_line_height'        => Elements::size( 20 ),
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
				'width'  => 'custom',
				// The card inside is 236 wide; the panel adds the 20px inset that
				// every panel carries, and the nudge puts the card's left edge back
				// on the menu item's.
				'size'   => 276,
				'align'  => 'start',
				'offset' => -20,
			],
			[
				'label' => __( 'Solutions', 'essential-addons-for-elementor-lite' ),
				'type'  => 'mega',
				// Viewport, not a pixel width: see the class comment. The card the
				// user sees is the boxed container inside this panel.
				'width' => 'viewport',
				'align' => 'start',
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
			'eael_mega_menu_bar_gap'                   => Elements::size( 8 ),
			'eael_mega_menu_bar_padding'               => Elements::spacing( 0, 0, 0, 0 ),

			// Items.
			'eael_mega_menu_item_typography_typography'  => 'custom',
			'eael_mega_menu_item_typography_font_size'   => Elements::size( 16 ),
			'eael_mega_menu_item_typography_font_weight' => '500',
			'eael_mega_menu_item_typography_text_decoration' => 'none',
			'eael_mega_menu_item_padding'                => Elements::spacing( 10, 14, 10, 14 ),
			'eael_mega_menu_item_radius'                 => Elements::spacing( 10, 10, 10, 10 ),
			'eael_mega_menu_item_color'                  => self::INK,
			'eael_mega_menu_item_bg'                     => 'rgba(0,0,0,0)',
			'eael_mega_menu_item_color_hover'            => self::ACCENT,
			'eael_mega_menu_item_bg_hover'               => self::HOVER,
			'eael_mega_menu_item_color_active'           => self::ACCENT,
			'eael_mega_menu_item_bg_active'              => self::HOVER,

			// Indicator.
			'eael_mega_menu_indicator_size'         => Elements::size( 11 ),
			'eael_mega_menu_indicator_gap'          => Elements::size( 6 ),
			'eael_mega_menu_indicator_rotate'       => 'yes',
			'eael_mega_menu_indicator_color'        => self::INK,
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
			// Measured from the bottom of the menu *item*, which sits 14px above the
			// header bar's own bottom edge — so this is that padding plus the gap
			// the design leaves between the bar and the card below it.
			'eael_mega_menu_panel_offset'                => Elements::size( 26 ),
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
					$panels[] = self::help_panel( $item['label'] );
					break;

				case 2:
					$panels[] = self::solutions_panel( $item['label'] );
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
	 * The Quick Help panel: five links and nothing else.
	 *
	 * @since 6.7.5
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function help_panel( $title ) {
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
			[ self::card( [ self::link_list( $rows, 0 ) ], 8 ) ]
		);
	}

	/**
	 * The Solutions panel: three destination cards and three columns of links.
	 *
	 * @since 6.7.5
	 *
	 * @param string $title Navigator title.
	 *
	 * @return array
	 */
	protected static function solutions_panel( $title ) {
		return Elements::nested_child(
			self::panel_settings( $title ),
			[
				self::card(
					[
						self::category_column(),
						self::link_column(
							__( 'Core Builders', 'essential-addons-for-elementor-lite' ),
							[
								[ 'text' => __( 'Visual Editor', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-pen-nib' ],
								[ 'text' => __( 'Content Engine', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-file-alt', 'new' => true ],
								[ 'text' => __( 'Header Builder', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-window-maximize' ],
								[ 'text' => __( 'Popup Creator', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-clone' ],
								[ 'text' => __( 'Form Styler', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-sliders-h' ],
							],
							true
						),
						self::link_column(
							__( 'Advanced Widgets', 'essential-addons-for-elementor-lite' ),
							[
								[ 'text' => __( 'Interactive Charts', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-chart-line' ],
								[ 'text' => __( 'Filterable Galleries', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-th-large' ],
								[ 'text' => __( 'Data Table Widget', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-table' ],
								[ 'text' => __( 'Countdown Timer', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-clock' ],
								[ 'text' => __( 'Progress Circle', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-spinner' ],
							]
						),
						self::link_column(
							__( 'Site Enhancements', 'essential-addons-for-elementor-lite' ),
							[
								[ 'text' => __( 'AJAX Search Module', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-search' ],
								[ 'text' => __( 'Scroll Reveal Effects', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-mobile-alt' ],
								[ 'text' => __( 'User Login Widget', 'essential-addons-for-elementor-lite' ), 'icon' => 'fas fa-sign-in-alt' ],
								[ 'text' => __( 'Lightbox for Media', 'essential-addons-for-elementor-lite' ), 'icon' => 'far fa-image' ],
							]
						),
					],
					28,
					'row'
				),
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
	 *
	 * @return array
	 */
	protected static function card( $children, $padding = 28, $direction = 'column' ) {
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
			'border_color'          => self::LINE,
			'border_radius'         => Elements::spacing( 22, 22, 22, 22 ),
			'border_radius_mobile'  => Elements::spacing( 16, 16, 16, 16 ),
			'box_shadow_box_shadow_type' => 'yes',
			'box_shadow_box_shadow' => Elements::shadow( 14, 40, 'rgba(15, 23, 42, 0.12)' ),
			'_title'                => __( 'Card', 'essential-addons-for-elementor-lite' ),
		];

		return Elements::container( $settings, $children );
	}

	/* ---------------------------------------------------------------------
	 * Solutions panel columns.
	 * ------------------------------------------------------------------ */

	/**
	 * The column of destination cards.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function category_column() {
		$categories = [
			[
				'icon'  => 'fas fa-shapes',
				'title' => __( 'Features', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Powerful tools and widgets to design', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'  => 'fas fa-layer-group',
				'title' => __( 'Ready Solutions', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Pre-built functionality for popular use cases', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'  => 'fas fa-th-large',
				'title' => __( 'Templates', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Professionally designed templates and wireframes', 'essential-addons-for-elementor-lite' ),
			],
		];

		$rows = [];

		foreach ( $categories as $category ) {
			$rows[] = self::category_row( $category );
		}

		return Elements::container(
			[
				'content_width'  => 'full',
				'width'          => Elements::size( 26, '%' ),
				'width_tablet'   => Elements::size( 100, '%' ),
				'width_mobile'   => Elements::size( 100, '%' ),
				'flex_direction' => 'column',
				'flex_gap'       => Elements::gap( 4 ),
				'padding'        => Elements::spacing( 0, 0, 0, 0 ),
				'_title'         => __( 'Categories', 'essential-addons-for-elementor-lite' ),
			],
			$rows
		);
	}

	/**
	 * One destination card.
	 *
	 * The link and the hover wash live on the container rather than on the Info
	 * Box: a card a visitor can only click on its title is a card that misses
	 * most of the pointer's travel, and the Info Box has no hover background of
	 * its own to light the whole strip with.
	 *
	 * @since 6.7.5
	 *
	 * @param array $category Icon, title and description.
	 *
	 * @return array
	 */
	protected static function category_row( $category ) {
		return Elements::container(
			[
				'content_width'        => 'full',
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'space-between',
				'flex_wrap'            => 'nowrap',
				'flex_gap'             => Elements::gap( 10 ),
				'padding'              => Elements::spacing( 12, 14, 12, 14 ),
				'border_radius'        => Elements::spacing( 14, 14, 14, 14 ),
				'link'                 => Elements::link(),
				'background_hover_background' => 'classic',
				'background_hover_color'      => self::HOVER,
				'background_hover_transition' => Elements::size( 0.2 ),
				'_title'               => $category['title'],
			],
			[
				self::category_info_box( $category ),
				self::chevron(),
			]
		);
	}

	/**
	 * The icon, name and description of one destination.
	 *
	 * @since 6.7.5
	 *
	 * @param array $category Icon, title and description.
	 *
	 * @return array
	 */
	protected static function category_info_box( $category ) {
		return Elements::widget(
			'eael-info-box',
			[
				'eael_infobox_img_type'    => 'img-on-left',
				'eael_infobox_img_or_icon' => 'icon',
				'icon_vertical_position'   => 'middle',
				'eael_infobox_icon_new'    => Elements::icon( $category['icon'] ),
				'eael_infobox_title'       => $category['title'],
				'eael_infobox_title_tag'   => 'h6',
				'eael_infobox_text_type'   => 'content',
				'eael_infobox_text'        => '<p>' . esc_html( $category['text'] ) . '</p>',
				// The row's own container is the link; a button inside it would be
				// a second target sitting on top of the first.
				'eael_show_infobox_button' => '',
				'eael_infobox_content_alignment_left_right' => 'left',

				// Icon.
				'eael_infobox_icon_size'     => Elements::size( 17 ),
				'eael_infobox_icon_bg_shape' => 'radius',
				'eael_infobox_icon_bg_size'  => Elements::size( 42 ),
				'eael_infobox_icon_bg_color' => self::ICON_BG,
				'eael_infobox_icon_color'    => self::ACCENT,
				'eael_infobox_icon_margin'   => Elements::spacing( 0, 12, 0, 0 ),

				// Title.
				'eael_infobox_title_color_type' => 'classic',
				'eael_infobox_title_color'      => self::INK,
				'eael_infobox_title_typography_typography'  => 'custom',
				'eael_infobox_title_typography_font_size'   => Elements::size( 15 ),
				'eael_infobox_title_typography_font_weight' => '600',
				'eael_infobox_title_margin'     => Elements::spacing( 0, 0, 3, 0 ),

				// Description.
				'eael_infobox_content_color'  => self::MUTED,
				'eael_infobox_content_margin' => Elements::spacing( 0, 0, 0, 0 ),
				'eael_infobox_content_typography_hover_typography' => 'custom',
				'eael_infobox_content_typography_hover_font_size'  => Elements::size( 12 ),
				'eael_infobox_content_typography_hover_line_height' => Elements::size( 1.5, 'em' ),

				// Container — the row around it already supplies the inset.
				'eael_section_infobox_container_padding' => Elements::spacing( 0, 0, 0, 0 ),
				'eael_section_infobox_container_margin'  => Elements::spacing( 0, 0, 0, 0 ),
			]
		);
	}

	/**
	 * The chevron at the end of a destination card.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function chevron() {
		return Elements::widget(
			'icon',
			[
				'selected_icon' => Elements::icon( 'fas fa-chevron-right' ),
				'view'          => 'default',
				'primary_color' => self::MUTED,
				'size'          => Elements::size( 11 ),
				'_title'        => __( 'Chevron', 'essential-addons-for-elementor-lite' ),
			]
		);
	}

	/**
	 * One column of links, under a ruled heading.
	 *
	 * @since 6.7.5
	 *
	 * @param string $title  Column heading.
	 * @param array  $links  Rows of `text`, `icon` and optionally `new`.
	 * @param bool   $footer Whether to close the column with the "explore" link.
	 *
	 * @return array
	 */
	protected static function link_column( $title, $links, $footer = false ) {
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
			self::link_list( $rows, 10 ),
		];

		if ( $footer ) {
			$children[] = self::explore_link();
		}

		return Elements::container(
			[
				'content_width'  => 'full',
				'width'          => Elements::size( 22, '%' ),
				'width_tablet'   => Elements::size( 100, '%' ),
				'width_mobile'   => Elements::size( 100, '%' ),
				'flex_direction' => 'column',
				'flex_gap'       => Elements::gap( 10 ),
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
				'title_color'                 => self::MUTED,
				'typography_typography'       => 'custom',
				'typography_font_size'        => Elements::size( 12 ),
				'typography_font_weight'      => '600',
				'typography_text_transform'   => 'uppercase',
				'typography_letter_spacing'   => Elements::size( 1 ),
				'_border_border'              => 'solid',
				'_border_width'               => Elements::spacing( 0, 0, 1, 0 ),
				'_border_color'               => self::LINE,
				'_padding'                    => Elements::spacing( 0, 0, 12, 0 ),
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
	 *
	 * @return array
	 */
	protected static function link_list( $rows, $indent ) {
		return Elements::widget(
			'icon-list',
			[
				'view'                      => 'traditional',
				'icon_list'                 => $rows,
				'space_between'             => Elements::size( 2 ),
				'text_indent'               => Elements::size( $indent ),
				'icon_size'                 => Elements::size( 15 ),
				'icon_color'                => self::MUTED,
				'icon_color_hover'          => self::ACCENT,
				'text_color'                => self::INK,
				'text_color_hover'          => self::ACCENT,
				'icon_typography_typography' => 'custom',
				'icon_typography_font_size'  => Elements::size( 14 ),
				'icon_typography_font_weight' => '500',
				'_css_classes'              => 'eael-mm-linklist',
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
			'<span style="display:inline-block;margin-inline-start:8px;padding:2px 6px;border-radius:5px;background:%1$s;color:%2$s;font-size:10px;font-weight:700;letter-spacing:.04em;line-height:1.4;vertical-align:middle;">%3$s</span>',
			self::ICON_BG,
			self::ACCENT,
			esc_html__( 'NEW', 'essential-addons-for-elementor-lite' )
		);
	}

	/**
	 * The link that closes the first column.
	 *
	 * @since 6.7.5
	 *
	 * @return array
	 */
	protected static function explore_link() {
		return Elements::widget(
			'button',
			[
				'text'                          => __( 'Explore All Features', 'essential-addons-for-elementor-lite' ),
				'link'                          => Elements::link(),
				'size'                          => 'sm',
				'align'                         => 'left',
				'selected_icon'                 => Elements::icon( 'fas fa-external-link-alt' ),
				// `row-reverse`, not `right`: this control sets `flex-direction`.
				'icon_align'                    => 'row-reverse',
				'icon_indent'                   => Elements::size( 8 ),
				'background_color'              => 'rgba(0,0,0,0)',
				'button_text_color'             => self::ACCENT,
				'hover_color'                   => self::INK,
				'button_background_hover_color' => 'rgba(0,0,0,0)',
				'text_padding'                  => Elements::spacing( 0, 0, 0, 10 ),
				'typography_typography'         => 'custom',
				'typography_font_size'          => Elements::size( 14 ),
				'typography_font_weight'        => '600',
				// The one underline in the design, and the reason the control is
				// worth knowing about: it goes both ways.
				'typography_text_decoration'    => 'underline',
				'_flex_size'                    => 'none',
			]
		);
	}
}

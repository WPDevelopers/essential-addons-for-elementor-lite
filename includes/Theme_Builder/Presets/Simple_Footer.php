<?php
/**
 * The centred single column footer preset.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Presets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * A minimal footer: brand, a rule, one line, links, social, copyright.
 *
 * One centred column, and **no EA widgets at all** — which is the point of it
 * rather than an oversight. `Asset_Builder` enqueues an EA widget's CSS on every
 * page the widget appears on, and a footer appears on every page of the site; a
 * row of links and a copyright line do not need extra stylesheets to draw. Sites
 * that want the richer footer have `Modern_Footer` and `Brand_Footer`, which
 * spend that weight on things worth it.
 *
 * Minimal is not the same as unfinished, so the parts that cost nothing are the
 * ones doing the work here: a tinted band so the footer is a place rather than
 * the bottom of the page, a short accent rule under the wordmark to anchor the
 * stack, a measure on the tagline so it breaks like a sentence instead of
 * running the width of the screen, and white discs behind the social icons so
 * they read as buttons against the tint.
 *
 * Because it is one column it has no breakpoint of its own: the same stack reads
 * the same at every width, and only type sizes and padding step down. That is the
 * other reason to keep a simple option in the library — nothing about it can
 * break on a screen size nobody tested.
 *
 * @since 6.7.3
 */
class Simple_Footer {

	/**
	 * Brand colour.
	 */
	const ACCENT = '#5B3DF5';

	/**
	 * Body copy.
	 */
	const BODY = '#4B5563';

	/**
	 * Secondary copy.
	 */
	const MUTED = '#6B7280';

	/**
	 * The footer band.
	 */
	const SURFACE = '#F6F5FD';

	/**
	 * The discs behind the social icons.
	 */
	const DISC = '#FFFFFF';

	/**
	 * Hairlines.
	 */
	const LINE = '#E4E1F5';

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
					'flex_direction'        => 'column',
					'flex_gap'              => Elements::gap( 20 ),
					'flex_gap_mobile'       => Elements::gap( 16 ),
					'padding'               => Elements::spacing( 76, 24, 44, 24 ),
					'padding_tablet'        => Elements::spacing( 56, 20, 36, 20 ),
					'padding_mobile'        => Elements::spacing( 44, 16, 30, 16 ),
					'background_background' => 'classic',
					// A tinted band, not white: the footer has to be visible as its
					// own area under whatever the page ends with, and a tint does
					// that without deciding the page's colour scheme for it. The
					// hairline on top holds the edge where the page's own background
					// happens to be the same tint.
					'background_color'      => self::SURFACE,
					'border_border'         => 'solid',
					'border_width'          => Elements::spacing( 1, 0, 0, 0 ),
					'border_color'          => self::LINE,
					'_title'                => __( 'Simple Footer', 'essential-addons-for-elementor-lite' ),
				],
				[
					self::brand(),
					self::accent_rule(),
					self::tagline(),
					self::links(),
					self::social_icons(),
					self::bottom(),
				]
			),
		];
	}

	/**
	 * The preset's wordmark.
	 *
	 * The shipped logo, not the site's own — see `Elements::brand_logo()`.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function brand() {
		return Elements::widget(
			'image',
			[
				// An empty id is how Elementor's media control spells "no
				// attachment"; the file is shipped with the plugin, not in the
				// library. Leaving the key out entirely makes the editor look the
				// missing attachment up and warn about it on every load.
				'image'        => [ 'id' => '', 'url' => Elements::brand_logo() ],
				'image_size'   => 'full',
				'align'        => 'center',
				'width'        => Elements::size( 170 ),
				'width_mobile' => Elements::size( 140 ),
				'link_to'      => 'custom',
				'link'         => Elements::link( home_url( '/' ) ),
			]
		);
	}

	/**
	 * The short accent rule under the wordmark.
	 *
	 * Core's Divider rather than a container with a border: a divider is one
	 * element with a width, a weight and a colour in the panel, which is exactly
	 * the three things anyone changing this would want to change.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function accent_rule() {
		return Elements::widget(
			'divider',
			[
				'style'  => 'solid',
				'align'  => 'center',
				'color'  => self::ACCENT,
				'width'  => Elements::size( 48 ),
				'weight' => Elements::size( 3 ),
				// The column's own gap does the spacing; the widget's default gap
				// would add 15px of its own on both sides of it.
				'gap'    => Elements::size( 0 ),
			]
		);
	}

	/**
	 * One line under the brand — the site's own tagline when it has one.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function tagline() {
		$tagline = trim( (string) get_bloginfo( 'description' ) );

		if ( '' === $tagline ) {
			$tagline = __( 'One short line about what you do and who you do it for.', 'essential-addons-for-elementor-lite' );
		}

		$line = self::text( $tagline, self::MUTED, 16, 15 );

		// A measure, so a long tagline breaks into two readable lines instead of
		// one line the full width of the screen.
		$line['settings']['_element_width']         = 'initial';
		$line['settings']['_element_custom_width']  = Elements::size( 52, '%' );
		$line['settings']['_element_custom_width_tablet'] = Elements::size( 76, '%' );
		$line['settings']['_element_custom_width_mobile'] = Elements::size( 100, '%' );
		$line['settings']['_flex_align_self']       = 'center';

		return $line;
	}

	/**
	 * The link row.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function links() {
		$labels = [
			__( 'Home', 'essential-addons-for-elementor-lite' ),
			__( 'About', 'essential-addons-for-elementor-lite' ),
			__( 'Features', 'essential-addons-for-elementor-lite' ),
			__( 'Blog', 'essential-addons-for-elementor-lite' ),
			__( 'Contact', 'essential-addons-for-elementor-lite' ),
			__( 'Privacy Policy', 'essential-addons-for-elementor-lite' ),
		];

		$rows = [];

		foreach ( $labels as $label ) {
			$rows[] = Elements::row( [
				'text'          => $label,
				'link'          => Elements::link(),
				// Explicitly empty: the icon list defaults every new row to a tick,
				// and a link row is not a checklist.
				'selected_icon' => Elements::icon( '' ),
			] );
		}

		return Elements::widget(
			'icon-list',
			[
				// Inline, which also wraps — six labels on a phone become two or
				// three centred rows rather than one row running off the screen.
				'view'                             => 'inline',
				'icon_list'                        => $rows,
				'icon_align'                       => 'center',
				'space_between'                    => Elements::size( 32 ),
				'space_between_mobile'             => Elements::size( 22 ),
				'text_indent'                      => Elements::size( 0 ),
				'text_color'                       => self::BODY,
				'text_color_hover'                 => self::ACCENT,
				'icon_typography_typography'       => 'custom',
				'icon_typography_font_size'        => Elements::size( 15 ),
				'icon_typography_font_weight'      => '500',
				'icon_typography_line_height'      => Elements::size( 1.6, 'em' ),
			]
		);
	}

	/**
	 * The social row.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function social_icons() {
		$networks = [
			[ 'fab fa-facebook-f', '#1877F2' ],
			[ 'fab fa-x-twitter', '#1D1F2B' ],
			[ 'fab fa-instagram', '#E1306C' ],
			[ 'fab fa-linkedin-in', '#0A66C2' ],
		];

		$rows = [];

		foreach ( $networks as $network ) {
			$rows[] = Elements::row( [
				'social_icon'               => Elements::icon( $network[0], 'fa-brands' ),
				'link'                      => Elements::link(),
				// Per row rather than one colour for the set: a footer's social row
				// is the one place the networks' own colours are expected.
				'item_icon_color'           => 'custom',
				'item_icon_primary_color'   => self::DISC,
				'item_icon_secondary_color' => $network[1],
			] );
		}

		return Elements::widget(
			'social-icons',
			[
				'social_icon_list'   => $rows,
				'shape'              => 'circle',
				'align'              => 'center',
				'icon_size'          => Elements::size( 15 ),
				'icon_padding'       => Elements::size( 0.9, 'em' ),
				'icon_spacing'       => Elements::size( 12 ),
				'row_gap'            => Elements::size( 12 ),
				// The discs are white on a tinted band, so they need an edge to keep
				// their shape. The control is named `image_border` in core.
				'image_border_border' => 'solid',
				'image_border_width' => Elements::spacing( 1, 1, 1, 1 ),
				'image_border_color' => self::LINE,
			]
		);
	}

	/**
	 * The copyright line, under a rule.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function bottom() {
		$name = trim( (string) get_bloginfo( 'name' ) );
		$name = '' !== $name ? $name : __( 'Your Brand', 'essential-addons-for-elementor-lite' );

		$line = sprintf(
			/* translators: 1: Current year, 2: Site name. */
			__( '&copy; %1$s %2$s. All rights reserved.', 'essential-addons-for-elementor-lite' ),
			esc_html( date_i18n( 'Y' ) ),
			esc_html( $name )
		);

		return Elements::container(
			[
				'content_width'  => 'full',
				'width'          => Elements::size( 100, '%' ),
				'flex_direction' => 'column',
				'padding'        => Elements::spacing( 24, 0, 0, 0 ),
				'margin'         => Elements::spacing( 12, 0, 0, 0 ),
				'border_border'  => 'solid',
				'border_width'   => Elements::spacing( 1, 0, 0, 0 ),
				'border_color'   => self::LINE,
				'_title'         => __( 'Copyright', 'essential-addons-for-elementor-lite' ),
			],
			[ self::text( $line, self::MUTED, 14, 13 ) ]
		);
	}

	/**
	 * A centred line of copy.
	 *
	 * A heading widget rather than a text editor: the text editor prints a real
	 * paragraph, which arrives carrying whatever margins the active theme puts on
	 * `p` — and a preset that spaces its own stack cannot have a theme opening
	 * gaps inside it. Elementor resets the heading's margins to zero, so what the
	 * container's gap says is what the user sees.
	 *
	 * @since 6.7.3
	 *
	 * @param string $text   Copy.
	 * @param string $color  Text colour.
	 * @param int    $size   Desktop font size.
	 * @param int    $mobile Mobile font size.
	 *
	 * @return array
	 */
	protected static function text( $text, $color, $size, $mobile ) {
		return Elements::widget(
			'heading',
			[
				'title'                       => $text,
				'header_size'                 => 'p',
				'align'                       => 'center',
				'title_color'                 => $color,
				'typography_typography'       => 'custom',
				'typography_font_size'        => Elements::size( $size ),
				'typography_font_size_mobile' => Elements::size( $mobile ),
				'typography_font_weight'      => '400',
				'typography_line_height'      => Elements::size( 1.7, 'em' ),
			]
		);
	}
}

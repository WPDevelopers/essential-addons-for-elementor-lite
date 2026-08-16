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
 * A minimal footer: brand, one line, links, social, copyright.
 *
 * One centred column, six elements, and **no EA widgets at all** — which is the
 * point of it rather than an oversight. `Asset_Builder` enqueues an EA widget's
 * CSS on every page the widget appears on, and a footer appears on every page of
 * the site; a row of links and a copyright line do not need three extra
 * stylesheets to draw. Sites that want the richer footer have `Modern_Footer`,
 * which spends that weight on things worth it.
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
	 * Heading ink.
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
	 * Footer surface.
	 */
	const SURFACE = '#FFFFFF';

	/**
	 * Tinted surface, behind the social icons.
	 */
	const TINT = '#F3F1FE';

	/**
	 * Hairlines.
	 */
	const LINE = '#E9E7F5';

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
					'flex_gap'              => Elements::gap( 22 ),
					'flex_gap_mobile'       => Elements::gap( 18 ),
					'padding'               => Elements::spacing( 56, 24, 40, 24 ),
					'padding_tablet'        => Elements::spacing( 44, 20, 32, 20 ),
					'padding_mobile'        => Elements::spacing( 36, 16, 28, 16 ),
					'background_background' => 'classic',
					'background_color'      => self::SURFACE,
					// A hairline instead of a filled band: the footer has to sit
					// under whatever the page ends with, and a rule separates it
					// without deciding the page's colour scheme for it.
					'border_border'         => 'solid',
					'border_width'          => Elements::spacing( 1, 0, 0, 0 ),
					'border_color'          => self::LINE,
					'_title'                => __( 'Simple Footer', 'essential-addons-for-elementor-lite' ),
				],
				[
					self::brand(),
					self::tagline(),
					self::links(),
					self::social_icons(),
					self::bottom(),
				]
			),
		];
	}

	/**
	 * The site's logo, or its name.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function brand() {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		$logo    = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';

		if ( $logo ) {
			return Elements::widget(
				'image',
				[
					'image'        => [ 'id' => $logo_id, 'url' => $logo ],
					'image_size'   => 'full',
					'align'        => 'center',
					'width'        => Elements::size( 170 ),
					'width_mobile' => Elements::size( 140 ),
					'link_to'      => 'custom',
					'link'         => Elements::link( home_url( '/' ) ),
				]
			);
		}

		$name = get_bloginfo( 'name' );
		$name = '' !== trim( (string) $name ) ? $name : __( 'Your Brand', 'essential-addons-for-elementor-lite' );

		return Elements::widget(
			'heading',
			[
				'title'                       => $name,
				'header_size'                 => 'h2',
				'align'                       => 'center',
				'link'                        => Elements::link( home_url( '/' ) ),
				'title_color'                 => self::INK,
				'typography_typography'       => 'custom',
				'typography_font_size'        => Elements::size( 26 ),
				'typography_font_size_mobile' => Elements::size( 23 ),
				'typography_font_weight'      => '700',
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
			$tagline = __( 'One short line about what you do. A footer rarely needs more.', 'essential-addons-for-elementor-lite' );
		}

		return Elements::widget(
			'text-editor',
			[
				'editor'                 => '<p>' . $tagline . '</p>',
				'align'                  => 'center',
				'text_color'             => self::MUTED,
				'typography_typography'  => 'custom',
				'typography_font_size'   => Elements::size( 15 ),
				'typography_line_height' => Elements::size( 1.7, 'em' ),
			]
		);
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
				'view'                       => 'inline',
				'icon_list'                  => $rows,
				'icon_align'                 => 'center',
				'space_between'              => Elements::size( 30 ),
				'space_between_mobile'       => Elements::size( 22 ),
				'text_indent'                => Elements::size( 0 ),
				'text_color'                 => self::BODY,
				'text_color_hover'           => self::ACCENT,
				'icon_typography_typography' => 'custom',
				'icon_typography_font_size'  => Elements::size( 15 ),
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
				'item_icon_color'           => 'custom',
				'item_icon_primary_color'   => self::TINT,
				'item_icon_secondary_color' => $network[1],
			] );
		}

		return Elements::widget(
			'social-icons',
			[
				'social_icon_list' => $rows,
				'shape'            => 'circle',
				'align'            => 'center',
				'icon_size'        => Elements::size( 15 ),
				'icon_padding'     => Elements::size( 0.9, 'em' ),
				'icon_spacing'     => Elements::size( 10 ),
				'row_gap'          => Elements::size( 10 ),
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
		return Elements::container(
			[
				'content_width'  => 'full',
				'width'          => Elements::size( 100, '%' ),
				'flex_direction' => 'column',
				'padding'        => Elements::spacing( 22, 0, 0, 0 ),
				'margin'         => Elements::spacing( 8, 0, 0, 0 ),
				'border_border'  => 'solid',
				'border_width'   => Elements::spacing( 1, 0, 0, 0 ),
				'border_color'   => self::LINE,
				'_title'         => __( 'Copyright', 'essential-addons-for-elementor-lite' ),
			],
			[
				Elements::widget(
					'text-editor',
					[
						'editor'                => '<p>' . sprintf(
							/* translators: 1: Current year, 2: Site name. */
							__( '&copy; %1$s %2$s. All rights reserved.', 'essential-addons-for-elementor-lite' ),
							esc_html( date_i18n( 'Y' ) ),
							esc_html( get_bloginfo( 'name' ) )
						) . '</p>',
						'align'                 => 'center',
						'text_color'            => self::MUTED,
						'typography_typography' => 'custom',
						'typography_font_size'  => Elements::size( 14 ),
					]
				),
			]
		);
	}
}

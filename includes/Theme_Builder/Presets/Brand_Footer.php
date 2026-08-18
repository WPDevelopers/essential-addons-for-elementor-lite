<?php
/**
 * The brand card footer preset.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.4
 */

namespace Essential_Addons_Elementor\Theme_Builder\Presets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * A coloured footer carrying a brand block and three link columns.
 *
 * It meets the page's edges on three sides and rounds only the two corners that
 * face the content above it, so the footer reads as the page's own foot rather
 * than as a block dropped onto it. The rounding is the whole look; nothing else
 * here depends on it.
 *
 * Two bands inside: the columns, and a copyright line under a hairline. The
 * hairline belongs to the copyright band so it runs the full width of the card
 * while the line under it stays in the site's content column.
 *
 * The wordmark is EA's Dual Color Header, which is the widget the design is
 * asking for by name — a two tone wordmark, the first word in the accent, the
 * rest in white. The link lists are core Icon List: a list widget for a list is
 * what the panel edits best, and a footer appears on every page of a site, so a
 * heavier widget here is a cost every page pays.
 *
 * Nothing is baked in: no custom CSS, no image assets, no fixed markup. Every
 * label, link, colour, size and space is a control value, so the whole footer
 * stays editable in the panel after insertion.
 *
 * @since 6.7.4
 */
class Brand_Footer {

	/**
	 * The card.
	 */
	const SURFACE = '#6C5CAB';

	/**
	 * The first word of the wordmark.
	 */
	const ACCENT = '#7BE0A0';

	/**
	 * Headings, the wordmark, and hovered links.
	 */
	const INK = '#FFFFFF';

	/**
	 * Links and body copy.
	 */
	const BODY = '#DAD5F0';

	/**
	 * The social glyphs, which sit on white discs.
	 */
	const GLYPH = '#3E3466';

	/**
	 * The hairline above the copyright.
	 */
	const RULE = 'rgba(255, 255, 255, 0.18)';

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
					'content_width'         => 'full',
					'flex_direction'        => 'column',
					'flex_gap'              => Elements::gap( 0 ),
					'padding'               => Elements::spacing( 0, 0, 0, 0 ),
					'background_background' => 'classic',
					'background_color'      => self::SURFACE,
					// Top two corners only: the footer runs into the sides and the
					// bottom of the screen, and rounding an edge that is never seen
					// only shows up as a stray sliver of page behind the corner.
					'border_radius'         => Elements::spacing( 20, 20, 0, 0 ),
					'border_radius_mobile'  => Elements::spacing( 16, 16, 0, 0 ),
					// Keeps anything the user later gives a band of its own — a
					// background, an image — inside those two rounded corners.
					'overflow'              => 'hidden',
					'_title'                => __( 'Footer', 'essential-addons-for-elementor-lite' ),
				],
				[
					self::columns(),
					self::copyright_bar(),
				]
			),
		];
	}

	/* ---------------------------------------------------------------------
	 * Band 1 — the columns.
	 * ------------------------------------------------------------------ */

	/**
	 * The brand block beside three link columns.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function columns() {
		return Elements::container(
			[
				'content_width'        => 'boxed',
				'flex_direction'       => 'row',
				'flex_align_items'     => 'flex-start',
				// Space between rather than a fixed gap: the four widths below add up
				// to less than a row, and letting the leftover space fall between them
				// is what spreads the lists across the right hand side.
				'flex_justify_content' => 'space-between',
				// Wrap is what makes the stacking work: the widths are read per
				// breakpoint, and once they add up past a row the columns drop to the
				// next one on their own.
				'flex_wrap'            => 'wrap',
				'flex_gap'             => Elements::gap( 30 ),
				'flex_gap_tablet'      => Elements::gap( 40 ),
				'flex_gap_mobile'      => Elements::gap( 36 ),
				'padding'              => Elements::spacing( 96, 24, 64, 24 ),
				'padding_tablet'       => Elements::spacing( 70, 24, 48, 24 ),
				'padding_mobile'       => Elements::spacing( 54, 20, 40, 20 ),
				'_title'               => __( 'Footer Columns', 'essential-addons-for-elementor-lite' ),
			],
			[
				self::brand_column(),
				self::links_column(
					__( 'Explore', 'essential-addons-for-elementor-lite' ),
					[
						__( 'Home', 'essential-addons-for-elementor-lite' ),
						__( 'Features', 'essential-addons-for-elementor-lite' ),
						__( 'Pricing', 'essential-addons-for-elementor-lite' ),
						__( 'Integrations', 'essential-addons-for-elementor-lite' ),
						__( 'Changelog', 'essential-addons-for-elementor-lite' ),
						__( 'Roadmap', 'essential-addons-for-elementor-lite' ),
					]
				),
				self::links_column(
					__( 'Support', 'essential-addons-for-elementor-lite' ),
					[
						__( 'Help Center', 'essential-addons-for-elementor-lite' ),
						__( 'Getting Started', 'essential-addons-for-elementor-lite' ),
						__( 'Order Tracking', 'essential-addons-for-elementor-lite' ),
						__( 'Shipping', 'essential-addons-for-elementor-lite' ),
						__( 'Returns', 'essential-addons-for-elementor-lite' ),
						__( 'Report an Issue', 'essential-addons-for-elementor-lite' ),
						__( 'Community', 'essential-addons-for-elementor-lite' ),
						__( 'Contact Us', 'essential-addons-for-elementor-lite' ),
					]
				),
				self::links_column(
					__( 'Policies', 'essential-addons-for-elementor-lite' ),
					[
						__( 'Terms of Use', 'essential-addons-for-elementor-lite' ),
						__( 'Privacy Policy', 'essential-addons-for-elementor-lite' ),
						__( 'Cookie Policy', 'essential-addons-for-elementor-lite' ),
						__( 'Refund Policy', 'essential-addons-for-elementor-lite' ),
					]
				),
			]
		);
	}

	/**
	 * Wordmark, a line about the site, and the social row.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function brand_column() {
		return self::column(
			34,
			100,
			100,
			[
				self::wordmark(),
				self::description_line(),
				self::social_icons(),
			],
			__( 'Brand', 'essential-addons-for-elementor-lite' ),
			26
		);
	}

	/**
	 * The site's name as a two tone wordmark.
	 *
	 * Dual Color Header colours the first part and the rest separately, which is
	 * the whole shape of this design's brand — so the site name is split at its
	 * first space and handed over as the widget's two parts. A one word name has
	 * nothing to split, so it comes up white rather than entirely in the accent:
	 * a wordmark that is all accent reads as an error, and typing a space into the
	 * panel is what turns the two tone back on.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function wordmark() {
		$name = trim( (string) get_bloginfo( 'name' ) );
		$name = '' !== $name ? $name : __( 'Your Brand', 'essential-addons-for-elementor-lite' );

		$parts = explode( ' ', $name, 2 );
		$first = $parts[0];
		$last  = isset( $parts[1] ) ? $parts[1] : '';

		return Elements::widget(
			'eael-dual-color-header',
			[
				'eael_dch_type'                                => 'dch-default',
				'eael_dch_first_title'                         => $first,
				'eael_dch_last_title'                          => $last,
				'title_tag'                                    => 'h2',
				'eael_dch_content_alignment'                   => 'left',
				// The widget ships with a line of filler under the title.
				'eael_dch_subtext'                             => '',
				// Both off: the widget's defaults are a snowflake icon and a rule, and
				// neither belongs on a wordmark.
				'eael_show_dch_icon_content'                   => '',
				'eael_show_dch_separator'                      => '',
				'eael_dch_base_title_color'                    => self::INK,
				'eael_dch_dual_color_selector'                 => 'solid-color',
				'eael_dch_dual_title_color'                    => '' !== $last ? self::ACCENT : self::INK,
				'eael_dch_first_title_typography_typography'   => 'custom',
				'eael_dch_first_title_typography_font_size'    => Elements::size( 32 ),
				'eael_dch_first_title_typography_font_size_mobile' => Elements::size( 27 ),
				'eael_dch_first_title_typography_font_weight'  => '700',
				'eael_dch_first_title_typography_line_height'  => Elements::size( 1.2, 'em' ),
				// The widget's stylesheet uppercases the title and sets a 48px line
				// height on it; the design's wordmark is neither.
				'eael_dch_first_title_typography_text_transform' => 'none',
				// And it hangs a 50px margin under the widget. This is the control
				// behind it, so the space under the wordmark is the column's gap.
				'eael_dch_container_margin'                    => Elements::spacing( -10, 0, -10, 0 ),
				'eael_dch_container_padding'                   => Elements::spacing( 0, 0, 0, 0 ),
			]
		);
	}

	/**
	 * The line under the wordmark.
	 *
	 * Capped on a tablet, where the brand block is the full width of the card:
	 * a line of copy running the whole screen is a paragraph, not a strapline.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function description_line() {
		$line = self::text( self::description(), self::BODY, 17, 16 );

		$line['settings']['_element_width_tablet']        = 'initial';
		$line['settings']['_element_custom_width_tablet'] = Elements::size( 70, '%' );

		return $line;
	}

	/**
	 * The copy for that line — the site's own tagline when it has one.
	 *
	 * @since 6.7.4
	 *
	 * @return string
	 */
	protected static function description() {
		$tagline = trim( (string) get_bloginfo( 'description' ) );

		if ( '' !== $tagline ) {
			return $tagline;
		}

		return __( 'Tell visitors what your team does and who it is for. A footer is a good place for the short version of the story.', 'essential-addons-for-elementor-lite' );
	}

	/**
	 * The social row: glyphs on white discs.
	 *
	 * Elementor core, not an EA widget — Lite ships no social icons widget, and
	 * pulling one in from elsewhere for four links would cost the page more than
	 * it saves the user.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function social_icons() {
		$networks = [ 'fab fa-facebook-f', 'fab fa-x-twitter', 'fab fa-instagram', 'fab fa-linkedin-in' ];

		$rows = [];

		foreach ( $networks as $network ) {
			$rows[] = Elements::row( [
				'social_icon' => Elements::icon( $network, 'fa-brands' ),
				'link'        => Elements::link(),
			] );
		}

		return Elements::widget(
			'social-icons',
			[
				'social_icon_list'  => $rows,
				'shape'             => 'circle',
				'align'             => 'left',
				// One colour for the set rather than each network's own: on a coloured
				// card, five brand colours would be five more colours competing with
				// the card.
				'icon_color'        => 'custom',
				'icon_primary_color' => self::INK,
				'icon_secondary_color' => self::GLYPH,
				'icon_size'         => Elements::size( 15 ),
				'icon_padding'      => Elements::size( 0.85, 'em' ),
				'icon_spacing'      => Elements::size( 20 ),
				'row_gap'           => Elements::size( 12 ),
			]
		);
	}

	/**
	 * A column of links under a heading.
	 *
	 * A plain icon list rather than a menu widget: a menu widget renders whichever
	 * menu the site already has, and a footer wants three different lists that the
	 * user edits in place. Swapping one for Simple Menu afterwards is a drag away
	 * for anyone who does want the real thing.
	 *
	 * @since 6.7.4
	 *
	 * @param string $title Column heading.
	 * @param array  $links Link labels.
	 *
	 * @return array
	 */
	protected static function links_column( $title, $links ) {
		$rows = [];

		foreach ( $links as $link ) {
			$rows[] = Elements::row( [
				'text'          => $link,
				'link'          => Elements::link(),
				// Empty on purpose: the design lists the links plain, and an icon list
				// with no icons is still the widget that edits like a list.
				'selected_icon' => Elements::icon( '' ),
			] );
		}

		return self::column(
			15,
			30,
			45,
			[
				self::column_heading( $title ),
				Elements::widget(
					'icon-list',
					[
						'view'                             => 'traditional',
						'icon_list'                        => $rows,
						'space_between'                    => Elements::size( 7 ),
						'text_indent'                      => Elements::size( 0 ),
						'text_color'                       => self::BODY,
						'text_color_hover'                 => self::INK,
						'icon_typography_typography'       => 'custom',
						'icon_typography_font_size'        => Elements::size( 17 ),
						'icon_typography_font_size_mobile' => Elements::size( 16 ),
						'icon_typography_line_height'      => Elements::size( 1.4, 'em' ),
					]
				),
			],
			$title,
			24
		);
	}

	/* ---------------------------------------------------------------------
	 * Band 2 — the copyright.
	 * ------------------------------------------------------------------ */

	/**
	 * One centred line under a hairline.
	 *
	 * @since 6.7.4
	 *
	 * @return array
	 */
	protected static function copyright_bar() {
		$name = trim( (string) get_bloginfo( 'name' ) );
		$name = '' !== $name ? $name : __( 'Your Brand', 'essential-addons-for-elementor-lite' );

		$line = sprintf(
			/* translators: 1: Current year, 2: Site name. */
			__( '&copy; %1$s %2$s. All rights reserved.', 'essential-addons-for-elementor-lite' ),
			esc_html( date_i18n( 'Y' ) ),
			esc_html( $name )
		);

		$copyright = self::text( $line, self::BODY, 16, 15 );

		$copyright['settings']['align'] = 'center';

		return Elements::container(
			[
				'content_width'        => 'boxed',
				'flex_direction'       => 'row',
				'flex_align_items'     => 'center',
				'flex_justify_content' => 'center',
				'padding'              => Elements::spacing( 26, 24, 28, 24 ),
				'padding_mobile'       => Elements::spacing( 22, 20, 24, 20 ),
				'border_border'        => 'solid',
				'border_width'         => Elements::spacing( 1, 0, 0, 0 ),
				'border_color'         => self::RULE,
				'_title'               => __( 'Copyright', 'essential-addons-for-elementor-lite' ),
			],
			[ $copyright ]
		);
	}

	/* ---------------------------------------------------------------------
	 * Shared pieces.
	 * ------------------------------------------------------------------ */

	/**
	 * A footer column.
	 *
	 * @since 6.7.4
	 *
	 * @param int    $width    Desktop width, in percent.
	 * @param int    $tablet   Tablet width, in percent.
	 * @param int    $mobile   Mobile width, in percent.
	 * @param array  $children Child elements.
	 * @param string $title    Navigator title.
	 * @param int    $gap      Space between the column's children.
	 *
	 * @return array
	 */
	protected static function column( $width, $tablet, $mobile, $children, $title = '', $gap = 24 ) {
		$settings = [
			'content_width'  => 'full',
			'width'          => Elements::size( $width, '%' ),
			'width_tablet'   => Elements::size( $tablet, '%' ),
			'width_mobile'   => Elements::size( $mobile, '%' ),
			'flex_direction' => 'column',
			'flex_gap'       => Elements::gap( $gap ),
			'padding'        => Elements::spacing( 0, 0, 0, 0 ),
		];

		if ( '' !== $title ) {
			$settings['_title'] = $title;
		}

		return Elements::container( $settings, $children );
	}

	/**
	 * A column heading.
	 *
	 * @since 6.7.4
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
				'header_size'                 => 'h3',
				'title_color'                 => self::INK,
				'typography_typography'       => 'custom',
				'typography_font_size'        => Elements::size( 22 ),
				'typography_font_size_mobile' => Elements::size( 20 ),
				'typography_font_weight'      => '700',
			]
		);
	}

	/**
	 * A line of copy.
	 *
	 * A heading widget rather than a text editor: the text editor prints a real
	 * paragraph, which arrives carrying whatever margins the active theme puts on
	 * `p` — and a preset that spaces its own columns cannot have a theme opening
	 * gaps inside them. Elementor resets the heading's margins to zero, so what the
	 * container's gap says is what the user sees.
	 *
	 * @since 6.7.4
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
				'title_color'                 => $color,
				'typography_typography'       => 'custom',
				'typography_font_size'        => Elements::size( $size ),
				'typography_font_size_mobile' => Elements::size( $mobile ),
				'typography_font_weight'      => '400',
				'typography_line_height'      => Elements::size( 1.75, 'em' ),
			]
		);
	}
}

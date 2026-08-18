<?php
/**
 * The multi-column footer preset.
 *
 * @package Essential_Addons_Elementor
 * @since   6.7.3
 */

namespace Essential_Addons_Elementor\Theme_Builder\Presets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * A dark four column footer with a newsletter sign-up.
 *
 * Two bands in one wrapper: the columns — brand, sign-up, and three lists of
 * links — and a centred copyright line under a hairline. Both bands sit on the
 * same dark ground; the hairline is the only thing separating them, which is what
 * keeps the bottom line reading as part of the footer rather than as a second bar
 * bolted under it.
 *
 * The sign-up field is composed, not a form widget: every form widget EA ships is
 * an integration with a form plugin, so a preset that used one would insert a
 * broken widget on the many sites that have none. What goes in is the field's
 * look — a bordered box holding a placeholder line and an EA Creative Button —
 * so the user drops their own form widget into that box, or points the button at
 * a sign-up page, and the footer is already dressed for it either way.
 *
 * Nothing is baked in: no custom CSS, no image assets, no fixed markup. Every
 * label, link, colour, size and space is a control value, so the whole footer
 * stays editable in the panel after insertion.
 *
 * @since 6.7.3
 */
class Modern_Footer {

	/**
	 * The footer ground.
	 */
	const SURFACE = '#0B1236';

	/**
	 * Brand colour, on the sign-up button.
	 */
	const ACCENT = '#7B5CFF';

	/**
	 * The button, hovered.
	 */
	const ACCENT_HOVER = '#6A49F5';

	/**
	 * Headings and hovered links.
	 */
	const INK = '#FFFFFF';

	/**
	 * Links and body copy.
	 */
	const BODY = '#A7ADCE';

	/**
	 * The placeholder line in the sign-up field.
	 */
	const MUTED = '#868CB4';

	/**
	 * The sign-up field's border.
	 */
	const FIELD_LINE = 'rgba(255, 255, 255, 0.22)';

	/**
	 * The hairline above the copyright.
	 */
	const RULE = 'rgba(255, 255, 255, 0.08)';

	/**
	 * The button's label, which is the ground colour rather than white.
	 */
	const BUTTON_INK = '#0B1236';

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
					'content_width'         => 'full',
					'flex_direction'        => 'column',
					'flex_gap'              => Elements::gap( 0 ),
					'padding'               => Elements::spacing( 0, 0, 0, 0 ),
					'background_background' => 'classic',
					'background_color'      => self::SURFACE,
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
	 * Brand and sign-up on the left, three link columns on the right.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function columns() {
		return Elements::container(
			[
				'content_width'        => 'boxed',
				'flex_direction'       => 'row',
				'flex_align_items'     => 'flex-start',
				// Space between rather than a fixed gap: the four widths below add
				// up to less than a row, and letting the leftover space fall
				// between them is what spreads the link columns across the right
				// hand side the way the design does.
				'flex_justify_content' => 'space-between',
				// Wrap is what makes the stacking work: the widths are read per
				// breakpoint, and once they add up past a row the columns drop to
				// the next one on their own.
				'flex_wrap'            => 'wrap',
				'flex_gap'             => Elements::gap( 30 ),
				'flex_gap_tablet'      => Elements::gap( 36 ),
				'flex_gap_mobile'      => Elements::gap( 34 ),
				'padding'              => Elements::spacing( 100, 24, 74, 24 ),
				'padding_tablet'       => Elements::spacing( 70, 20, 52, 20 ),
				'padding_mobile'       => Elements::spacing( 54, 16, 40, 16 ),
				'_title'               => __( 'Footer Columns', 'essential-addons-for-elementor-lite' ),
			],
			[
				self::brand_column(),
				self::links_column(
					__( 'Company', 'essential-addons-for-elementor-lite' ),
					[
						__( 'About', 'essential-addons-for-elementor-lite' ),
						__( 'Privacy Policies', 'essential-addons-for-elementor-lite' ),
						__( 'Contact Us', 'essential-addons-for-elementor-lite' ),
						__( 'Technologies', 'essential-addons-for-elementor-lite' ),
					]
				),
				self::links_column(
					__( 'Products', 'essential-addons-for-elementor-lite' ),
					[
						__( 'Automated', 'essential-addons-for-elementor-lite' ),
						__( 'Chatbot', 'essential-addons-for-elementor-lite' ),
						__( 'Data Security', 'essential-addons-for-elementor-lite' ),
						__( 'Virtual Reality', 'essential-addons-for-elementor-lite' ),
					]
				),
				self::links_column(
					__( 'Resources', 'essential-addons-for-elementor-lite' ),
					[
						__( 'Blog', 'essential-addons-for-elementor-lite' ),
						__( 'Documentation', 'essential-addons-for-elementor-lite' ),
						__( 'Support', 'essential-addons-for-elementor-lite' ),
						__( 'Affiliate', 'essential-addons-for-elementor-lite' ),
					]
				),
			]
		);
	}

	/**
	 * Logo, the sign-up line, and the sign-up field.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function brand_column() {
		return self::column(
			40,
			100,
			100,
			[
				self::wordmark(),
				self::text(
					__( 'Subscribe to our newsletter and get the latest updates', 'essential-addons-for-elementor-lite' ),
					self::BODY,
					17,
					16
				),
				self::subscribe_field(),
			],
			__( 'Brand', 'essential-addons-for-elementor-lite' ),
			26
		);
	}

	/**
	 * The site's logo, or its name as a wordmark.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function wordmark() {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		$logo    = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : '';

		if ( $logo ) {
			return Elements::widget(
				'image',
				[
					'image'        => [ 'id' => $logo_id, 'url' => $logo ],
					'image_size'   => 'full',
					'align'        => 'start',
					'width'        => Elements::size( 190 ),
					'width_mobile' => Elements::size( 155 ),
					'link_to'      => 'custom',
					'link'         => Elements::link( home_url( '/' ) ),
				]
			);
		}

		// No logo: the site name as a plain heading.
		//
		// Dual Color Header would give the two tone wordmark the design draws,
		// but its stylesheet hangs a 50px bottom margin off the widget with no
		// control behind it, which opens a gap under the wordmark that the user
		// cannot close from the panel. A preset has to be editable everywhere it is
		// visible, so the plain heading wins.
		$name = get_bloginfo( 'name' );
		$name = '' !== trim( (string) $name ) ? $name : __( 'Your Brand', 'essential-addons-for-elementor-lite' );

		return Elements::widget(
			'heading',
			[
				'title'                       => $name,
				'header_size'                 => 'h2',
				'link'                        => Elements::link( home_url( '/' ) ),
				'title_color'                 => self::INK,
				'typography_typography'       => 'custom',
				'typography_font_size'        => Elements::size( 32 ),
				'typography_font_size_mobile' => Elements::size( 27 ),
				'typography_font_weight'      => '700',
				'typography_letter_spacing'   => Elements::size( -0.5 ),
			]
		);
	}

	/**
	 * The sign-up field: a placeholder line with the button inside its border.
	 *
	 * The button is a Creative Button rather than core's, because this is the one
	 * control the design gives the visitor and EA's button is the one with the
	 * hover treatments a user is likely to want to reach for afterwards.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function subscribe_field() {
		return Elements::container(
			[
				'content_width'    => 'full',
				'flex_direction'   => 'row',
				'flex_align_items' => 'center',
				// No wrap: the placeholder gives up width to the button rather than
				// dropping it to a second line on a narrow screen.
				'flex_wrap'        => 'nowrap',
				'flex_gap'         => Elements::gap( 12 ),
				// Full width of the brand column, which is the whole column on a
				// tablet — and a field running the width of the screen reads as a
				// search bar, not as a sign-up.
				'width_tablet'     => Elements::size( 62, '%' ),
				'width_mobile'     => Elements::size( 100, '%' ),
				'padding'          => Elements::spacing( 8, 8, 8, 26 ),
				'padding_mobile'   => Elements::spacing( 6, 6, 6, 18 ),
				'margin'           => Elements::spacing( 14, 0, 0, 0 ),
				'border_border'    => 'solid',
				'border_width'     => Elements::spacing( 1, 1, 1, 1 ),
				'border_color'     => self::FIELD_LINE,
				'_title'           => __( 'Subscribe Field', 'essential-addons-for-elementor-lite' ),
			],
			[
				Elements::widget(
					'heading',
					[
						'title'                       => __( 'Your Email Address', 'essential-addons-for-elementor-lite' ),
						'header_size'                 => 'p',
						'title_color'                 => self::MUTED,
						'typography_typography'       => 'custom',
						'typography_font_size'        => Elements::size( 16 ),
						'typography_font_size_mobile' => Elements::size( 15 ),
						'typography_font_weight'      => '400',
						// Takes whatever the button leaves, so the border box is one
						// field rather than two elements sitting next to each other.
						'_flex_size'                  => 'grow',
					]
				),
				Elements::widget(
					'eael-creative-button',
					[
						'creative_button_text'                        => __( 'Subscribe', 'essential-addons-for-elementor-lite' ),
						'creative_button_link_url'                    => Elements::link(),
						'creative_button_effect'                      => 'eael-creative-button--default',
						'eael_creative_button_icon_new'               => Elements::icon( '' ),
						// The control writes `justify-content`, so it wants a flex value.
						'eael_creative_button_alignment'              => 'flex-start',
						'eael_creative_button_padding'                => Elements::spacing( 16, 32, 16, 32 ),
						'eael_creative_button_padding_mobile'         => Elements::spacing( 13, 18, 13, 18 ),
						// A slider, not a dimensions control: the widget's own stylesheet
						// rounds the button by 2px, and only this value squares it off.
						'eael_creative_button_border_radius'          => Elements::size( 0 ),
						'eael_creative_button_text_color'             => self::BUTTON_INK,
						'eael_creative_button_background_color'       => self::ACCENT,
						'eael_creative_button_hover_text_color'       => self::INK,
						'eael_creative_button_hover_background_color' => self::ACCENT_HOVER,
						'eael_creative_button_typography_typography'  => 'custom',
						'eael_creative_button_typography_font_size'   => Elements::size( 16 ),
						'eael_creative_button_typography_font_size_mobile' => Elements::size( 15 ),
						'eael_creative_button_typography_font_weight' => '500',
						// Held at its own size while the placeholder takes the rest of
						// the row, so the button never shrinks under its label.
						'_flex_size'                                  => 'none',
					]
				),
			]
		);
	}

	/**
	 * A column of links under a heading.
	 *
	 * A plain icon list rather than a menu widget: a menu widget renders whichever
	 * menu the site already has, and a footer wants three different short lists
	 * that the user edits in place. Swapping one for Simple Menu afterwards is a
	 * drag away for anyone who does want the real thing.
	 *
	 * @since 6.7.3
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
				// Empty on purpose: the design lists the links plain, and an icon
				// list with no icons is still the widget that edits like a list.
				'selected_icon' => Elements::icon( '' ),
			] );
		}

		return self::column(
			13,
			30,
			45,
			[
				self::column_heading( $title ),
				Elements::widget(
					'icon-list',
					[
						'view'                             => 'traditional',
						'icon_list'                        => $rows,
						'space_between'                    => Elements::size( 20 ),
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
			28
		);
	}

	/* ---------------------------------------------------------------------
	 * Band 2 — the copyright.
	 * ------------------------------------------------------------------ */

	/**
	 * One centred line under a hairline.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function copyright_bar() {
		$name = trim( (string) get_bloginfo( 'name' ) );
		$name = '' !== $name ? $name : __( 'Your Brand', 'essential-addons-for-elementor-lite' );

		$line = sprintf(
			/* translators: %s: Site name. */
			__( '&copy; Copyright by %s', 'essential-addons-for-elementor-lite' ),
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
				'padding'              => Elements::spacing( 28, 24, 30, 24 ),
				'padding_mobile'       => Elements::spacing( 22, 16, 24, 16 ),
				// The hairline belongs to this band rather than to the one above it,
				// so it runs the full width of the footer while the copy inside stays
				// in the site's content column.
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
	 * @since 6.7.3
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
				'title'                       => $title,
				'header_size'                 => 'h3',
				'title_color'                 => self::INK,
				'typography_typography'       => 'custom',
				'typography_font_size'        => Elements::size( 20 ),
				'typography_font_size_mobile' => Elements::size( 18 ),
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

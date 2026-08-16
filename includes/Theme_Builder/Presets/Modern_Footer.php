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
 * A five column footer built out of Essential Addons widgets.
 *
 * Three bands stacked in one wrapper: the columns (brand, two link lists, contact
 * details, newsletter), a strip of selling points, and a dark legal bar. Each band
 * is its own boxed container, so a band's background runs edge to edge while its
 * content stays in the site's content column — which is what lets the legal bar be
 * dark without the rest of the footer being dark with it.
 *
 * The EA widgets are here because they fit, not to pad a list: Feature List is an
 * icon-and-text list, which is exactly what contact details are; Info Box is an
 * icon-title-description card, which is what a selling point is; Creative Button
 * and Dual Color Header cover the call to action and the wordmark. Everything else
 * — headings, link lists, copy, social icons — is Elementor core, because core
 * already does those well and a preset that reaches for a heavier widget to draw a
 * heading is a preset that costs the page more than it gives it.
 *
 * Nothing is baked in: no custom CSS, no image assets, no fixed markup. Every
 * label, link, colour, size and space is a control value, so the whole footer
 * stays editable in the panel after insertion.
 *
 * @since 6.7.3
 */
class Modern_Footer {

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
	 * Tinted surface, behind icons and the newsletter card.
	 */
	const TINT = '#F3F1FE';

	/**
	 * Hairlines.
	 */
	const LINE = '#E9E7F5';

	/**
	 * The legal bar.
	 */
	const DARK = '#0B1033';

	/**
	 * Copy on the legal bar.
	 */
	const DARK_INK = '#C9C7E8';

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
					self::highlights(),
					self::legal_bar(),
				]
			),
		];
	}

	/* ---------------------------------------------------------------------
	 * Band 1 — the columns.
	 * ------------------------------------------------------------------ */

	/**
	 * Brand, links, resources, contact and newsletter.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function columns() {
		return Elements::container(
			[
				'content_width'    => 'boxed',
				'flex_direction'   => 'row',
				'flex_align_items' => 'flex-start',
				// Wrap is what makes the stacking work: the widths below are read
				// per breakpoint, and once they add up past a row the columns drop
				// to the next one on their own.
				'flex_wrap'        => 'wrap',
				'flex_gap'         => Elements::gap( 28 ),
				'flex_gap_tablet'  => Elements::gap( 24 ),
				'flex_gap_mobile'  => Elements::gap( 22 ),
				'padding'          => Elements::spacing( 56, 24, 40, 24 ),
				'padding_tablet'   => Elements::spacing( 40, 20, 32, 20 ),
				'padding_mobile'   => Elements::spacing( 32, 16, 24, 16 ),
				'_title'           => __( 'Footer Columns', 'essential-addons-for-elementor-lite' ),
			],
			[
				self::brand_column(),
				self::links_column(
					__( 'Quick Links', 'essential-addons-for-elementor-lite' ),
					[
						__( 'About Us', 'essential-addons-for-elementor-lite' ),
						__( 'Features', 'essential-addons-for-elementor-lite' ),
						__( 'Pricing', 'essential-addons-for-elementor-lite' ),
						__( 'Documentation', 'essential-addons-for-elementor-lite' ),
						__( 'Changelog', 'essential-addons-for-elementor-lite' ),
						__( 'Support Center', 'essential-addons-for-elementor-lite' ),
					],
					14,
					15,
					46
				),
				self::links_column(
					__( 'Resources', 'essential-addons-for-elementor-lite' ),
					[
						__( 'Blog', 'essential-addons-for-elementor-lite' ),
						__( 'Help & Support', 'essential-addons-for-elementor-lite' ),
						__( 'Community', 'essential-addons-for-elementor-lite' ),
						__( 'Templates', 'essential-addons-for-elementor-lite' ),
						__( 'Integration', 'essential-addons-for-elementor-lite' ),
						__( 'Affiliates', 'essential-addons-for-elementor-lite' ),
					],
					14,
					16,
					46
				),
				self::contact_column(),
				self::newsletter_column(),
			]
		);
	}

	/**
	 * Logo, description and social links.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function brand_column() {
		return self::column(
			24,
			32,
			100,
			[
				self::wordmark(),
				Elements::widget(
					'text-editor',
					[
						'editor'                 => '<p>' . self::description() . '</p>',
						'text_color'             => self::BODY,
						'typography_typography'  => 'custom',
						'typography_font_size'   => Elements::size( 15 ),
						'typography_line_height' => Elements::size( 1.7, 'em' ),
					]
				),
				self::social_icons(),
			],
			__( 'Brand', 'essential-addons-for-elementor-lite' )
		);
	}

	/**
	 * The site's logo, or its name as a two tone wordmark.
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
					'width'        => Elements::size( 180 ),
					'width_mobile' => Elements::size( 150 ),
					'link_to'      => 'custom',
					'link'         => Elements::link( home_url( '/' ) ),
				]
			);
		}

		// No logo: the site name as a plain heading.
		//
		// Dual Color Header would give the two tone wordmark the design asks for,
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
				'typography_font_size'        => Elements::size( 26 ),
				'typography_font_size_mobile' => Elements::size( 23 ),
				'typography_font_weight'      => '700',
			]
		);
	}

	/**
	 * The brand description — the site's own tagline when it has one.
	 *
	 * @since 6.7.3
	 *
	 * @return string
	 */
	protected static function description() {
		$tagline = trim( (string) get_bloginfo( 'description' ) );

		if ( '' !== $tagline ) {
			return $tagline;
		}

		return __( 'Write a short line about what you do and who you do it for. Two sentences is usually enough for a footer.', 'essential-addons-for-elementor-lite' );
	}

	/**
	 * The social row.
	 *
	 * Elementor core, not an EA widget — Lite ships no social icons widget, and
	 * pulling one in from elsewhere for five links would cost the page more than
	 * it saves the user.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function social_icons() {
		$networks = [
			[ 'fab fa-facebook-f', '#1877F2' ],
			[ 'fab fa-x-twitter', '#1D1F2B' ],
			[ 'fab fa-youtube', '#FF0000' ],
			[ 'fab fa-instagram', '#E1306C' ],
			[ 'fab fa-linkedin-in', '#0A66C2' ],
		];

		$rows = [];

		foreach ( $networks as $network ) {
			$rows[] = Elements::row( [
				'social_icon'              => Elements::icon( $network[0], 'fa-brands' ),
				'link'                     => Elements::link(),
				// Per row rather than one colour for the set: a footer's social row
				// is the one place the networks' own colours are expected.
				'item_icon_color'          => 'custom',
				'item_icon_primary_color'  => self::TINT,
				'item_icon_secondary_color' => $network[1],
			] );
		}

		return Elements::widget(
			'social-icons',
			[
				'social_icon_list' => $rows,
				'shape'            => 'circle',
				'align'            => 'left',
				'icon_size'        => Elements::size( 15 ),
				'icon_padding'     => Elements::size( 0.9, 'em' ),
				'icon_spacing'     => Elements::size( 10 ),
				'row_gap'          => Elements::size( 10 ),
			]
		);
	}

	/**
	 * A column of links under a heading.
	 *
	 * A plain icon list rather than a menu widget: a menu widget renders whichever
	 * menu the site already has, and a footer wants two different short lists that
	 * the user edits in place. Swapping one for Simple Menu afterwards is a drag
	 * away for anyone who does want the real thing.
	 *
	 * @since 6.7.3
	 *
	 * @param string $title  Column heading.
	 * @param array  $links  Link labels.
	 * @param int    $width  Desktop width, in percent.
	 * @param int    $tablet Tablet width, in percent.
	 * @param int    $mobile Mobile width, in percent.
	 *
	 * @return array
	 */
	protected static function links_column( $title, $links, $width, $tablet, $mobile ) {
		$rows = [];

		foreach ( $links as $link ) {
			$rows[] = Elements::row( [
				'text'          => $link,
				'link'          => Elements::link(),
				'selected_icon' => Elements::icon( 'fas fa-angle-right' ),
			] );
		}

		return self::column(
			$width,
			$tablet,
			$mobile,
			[
				self::column_heading( $title ),
				Elements::widget(
					'icon-list',
					[
						'view'                       => 'traditional',
						'icon_list'                  => $rows,
						'space_between'              => Elements::size( 14 ),
						'text_indent'                => Elements::size( 8 ),
						'icon_size'                  => Elements::size( 12 ),
						'icon_color'                 => self::ACCENT,
						'text_color'                 => self::BODY,
						'text_color_hover'           => self::ACCENT,
						'icon_typography_typography' => 'custom',
						'icon_typography_font_size'  => Elements::size( 15 ),
						'icon_typography_font_size_tablet' => Elements::size( 14 ),
					]
				),
			],
			$title
		);
	}

	/**
	 * Address, email and phone as an EA Feature List.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function contact_column() {
		$details = [
			[
				'icon'    => 'fas fa-map-marker-alt',
				'title'   => __( '123 Design Street,', 'essential-addons-for-elementor-lite' ),
				'content' => __( 'Dhaka, Bangladesh', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'    => 'fas fa-envelope',
				'title'   => __( 'hello@example.com', 'essential-addons-for-elementor-lite' ),
				// Every row carries a second line on purpose: the widget prints the
				// content paragraph whether or not it has anything in it, so a row
				// left blank still takes that paragraph's margin and the list ends up
				// unevenly spaced.
				'content' => __( 'Sales and support', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'    => 'fas fa-phone-alt',
				'title'   => __( '+880 1234 567 890', 'essential-addons-for-elementor-lite' ),
				'content' => __( 'Weekdays, 9am to 6pm', 'essential-addons-for-elementor-lite' ),
			],
		];

		$rows = [];

		foreach ( $details as $detail ) {
			$rows[] = Elements::row( [
				'eael_feature_list_icon_type' => 'icon',
				'eael_feature_list_icon_new'  => Elements::icon( $detail['icon'] ),
				'eael_feature_list_title'     => $detail['title'],
				'eael_feature_list_content'   => $detail['content'],
			] );
		}

		return self::column(
			18,
			25,
			100,
			[
				self::column_heading( __( 'Contact Us', 'essential-addons-for-elementor-lite' ) ),
				Elements::widget(
					'eael-feature-list',
					[
						'eael_feature_list'                 => $rows,
						'eael_feature_list_layout'          => 'vertical',
						'eael_feature_list_icon_position'   => 'left',
						'eael_feature_list_icon_shape'      => 'circle',
						'eael_feature_list_icon_shape_view' => 'stacked',
						// Empty, not 'no': the control is a switcher whose only "on"
						// value is 'yes', so anything else is an out of domain value the
						// panel cannot render.
						'eael_feature_list_connector'       => '',
						'eael_feature_list_title_size'      => 'h6',
						'eael_feature_list_space_between'   => Elements::size( 18 ),
						'eael_feature_list_icon_circle_size' => Elements::size( 40 ),
						'eael_feature_list_icon_size'       => Elements::size( 15 ),
						'eael_feature_list_icon_space'      => Elements::size( 14 ),
						'eael_feature_list_icon_color'      => self::ACCENT,
						'eael_feature_list_icon_background_background' => 'classic',
						'eael_feature_list_icon_background_color' => self::TINT,
						'eael_feature_list_title_color'     => self::INK,
						'eael_feature_list_title_typography_typography' => 'custom',
						'eael_feature_list_title_typography_font_size'  => Elements::size( 15 ),
						'eael_feature_list_title_typography_font_weight' => '500',
						'eael_feature_list_title_bottom_space' => Elements::size( 2 ),
						'eael_feature_list_description_color' => self::MUTED,
						'eael_feature_list_description_typography_typography' => 'custom',
						'eael_feature_list_description_typography_font_size'  => Elements::size( 15 ),
					]
				),
			],
			__( 'Contact', 'essential-addons-for-elementor-lite' )
		);
	}

	/**
	 * The newsletter card.
	 *
	 * An Info Box and a Creative Button rather than a form: every form widget EA
	 * ships is an integration with a form plugin, so a preset that used one would
	 * insert a broken widget on the many sites that have none. The button links
	 * out; dropping a real form widget in its place once one is installed is a
	 * drag away.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function newsletter_column() {
		$card = Elements::container(
			[
				'content_width'         => 'full',
				'flex_direction'        => 'column',
				'flex_gap'              => Elements::gap( 16 ),
				'padding'               => Elements::spacing( 24, 24, 24, 24 ),
				'background_background' => 'classic',
				'background_color'      => self::TINT,
				'border_radius'         => Elements::spacing( 14, 14, 14, 14 ),
				'_title'                => __( 'Newsletter Card', 'essential-addons-for-elementor-lite' ),
			],
			[
				Elements::widget(
					'eael-info-box',
					[
						'eael_infobox_img_type'       => 'img-on-top',
						'eael_infobox_img_or_icon'    => 'icon',
						'eael_infobox_icon_new'       => Elements::icon( 'fas fa-envelope-open-text' ),
						'eael_infobox_content_alignment' => 'left',
						// Labelled "Left" in the panel for this layout: the control
						// writes `align-self`, which is horizontal once the box is a
						// column, and its default centres the icon over left aligned
						// copy.
						'icon_vertical_position_top_bottom' => 'top',
						'eael_infobox_title'          => __( 'Subscribe to Newsletter', 'essential-addons-for-elementor-lite' ),
						'eael_infobox_title_tag'      => 'h4',
						'eael_show_infobox_content'   => 'yes',
						'eael_infobox_text'           => '<p>' . __( 'Get the latest updates, tips, and exclusive offers straight to your inbox.', 'essential-addons-for-elementor-lite' ) . '</p>',
						'eael_show_infobox_button'    => '',
						'eael_infobox_icon_bg_shape'  => 'radius',
						'eael_infobox_icon_color'     => self::SURFACE,
						'eael_infobox_icon_bg_color'  => self::ACCENT,
						'eael_infobox_icon_size'      => Elements::size( 20 ),
						'eael_infobox_icon_bg_size'   => Elements::size( 46 ),
						'eael_infobox_icon_margin'    => Elements::spacing( 0, 0, 14, 0 ),
						'eael_infobox_title_color'    => self::INK,
						'eael_infobox_title_typography_typography'  => 'custom',
						'eael_infobox_title_typography_font_size'   => Elements::size( 18 ),
						'eael_infobox_title_typography_font_weight' => '600',
						'eael_infobox_title_margin'   => Elements::spacing( 0, 0, 8, 0 ),
						'eael_infobox_content_color'  => self::BODY,
						'eael_infobox_content_typography_hover_typography'  => 'custom',
						'eael_infobox_content_typography_hover_font_size'   => Elements::size( 15 ),
						'eael_infobox_content_typography_hover_line_height' => Elements::size( 1.6, 'em' ),
					]
				),
				Elements::widget(
					'eael-creative-button',
					[
						'creative_button_text'            => __( 'Subscribe', 'essential-addons-for-elementor-lite' ),
						'creative_button_link_url'        => Elements::link(),
						'creative_button_effect'          => 'eael-creative-button--default',
						'eael_creative_button_icon_new'   => Elements::icon( 'fas fa-paper-plane' ),
						'eael_creative_button_icon_alignment' => 'right',
						'eael_creative_button_icon_size'  => Elements::size( 14 ),
						'eael_creative_button_icon_indent' => Elements::size( 8 ),
						// The control writes `justify-content`, so it wants a flex value.
						'eael_creative_button_alignment'  => 'flex-start',
						'eael_creative_button_padding'    => Elements::spacing( 12, 22, 12, 22 ),
						'eael_creative_button_border_radius' => Elements::spacing( 8, 8, 8, 8 ),
						'eael_creative_button_text_color' => self::SURFACE,
						'eael_creative_button_icon_color' => self::SURFACE,
						'eael_creative_button_background_color' => self::ACCENT,
						'eael_creative_button_hover_text_color' => self::SURFACE,
						'eael_creative_button_hover_icon_color' => self::SURFACE,
						'eael_creative_button_hover_background_color' => '#4A2FE0',
						'eael_creative_button_typography_typography'  => 'custom',
						'eael_creative_button_typography_font_size'   => Elements::size( 15 ),
						'eael_creative_button_typography_font_weight' => '600',
					]
				),
			]
		);

		return self::column(
			20,
			100,
			100,
			[ $card ],
			__( 'Newsletter', 'essential-addons-for-elementor-lite' )
		);
	}

	/* ---------------------------------------------------------------------
	 * Band 2 — the selling points.
	 * ------------------------------------------------------------------ */

	/**
	 * Four Info Boxes above a hairline.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function highlights() {
		$points = [
			[
				'icon'  => 'fas fa-rocket',
				'title' => __( '100+ Creative Elements', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Build anything with powerful widgets.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'  => 'fas fa-layer-group',
				'title' => __( 'Lightweight & Fast', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Optimized for speed and better performance.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'  => 'fas fa-mobile-alt',
				'title' => __( 'Fully Responsive', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Looks perfect on all devices and screens.', 'essential-addons-for-elementor-lite' ),
			],
			[
				'icon'  => 'fas fa-headset',
				'title' => __( 'Top Notch Support', 'essential-addons-for-elementor-lite' ),
				'text'  => __( 'Dedicated support team always here for you.', 'essential-addons-for-elementor-lite' ),
			],
		];

		$boxes = [];

		foreach ( $points as $point ) {
			$boxes[] = self::column(
				23,
				48,
				100,
				[
					Elements::widget(
						'eael-info-box',
						[
							'eael_infobox_img_type'    => 'img-on-left',
							'eael_infobox_img_or_icon' => 'icon',
							'icon_vertical_position'   => 'top',
							'eael_infobox_content_alignment_left_right' => 'left',
							'eael_infobox_icon_new'    => Elements::icon( $point['icon'] ),
							'eael_infobox_title'       => $point['title'],
							'eael_infobox_title_tag'   => 'h4',
							'eael_show_infobox_content' => 'yes',
							'eael_infobox_text'        => '<p>' . $point['text'] . '</p>',
							'eael_show_infobox_button' => '',
							'eael_infobox_icon_bg_shape' => 'radius',
							'eael_infobox_icon_color'  => self::ACCENT,
							'eael_infobox_icon_bg_color' => self::TINT,
							'eael_infobox_icon_size'   => Elements::size( 20 ),
							'eael_infobox_icon_bg_size' => Elements::size( 46 ),
							'eael_infobox_icon_margin' => Elements::spacing( 0, 14, 0, 0 ),
							'eael_infobox_title_color' => self::INK,
							'eael_infobox_title_typography_typography'  => 'custom',
							'eael_infobox_title_typography_font_size'   => Elements::size( 16 ),
							'eael_infobox_title_typography_font_weight' => '600',
							'eael_infobox_title_margin' => Elements::spacing( 0, 0, 4, 0 ),
							'eael_infobox_content_color' => self::MUTED,
							'eael_infobox_content_typography_hover_typography' => 'custom',
							'eael_infobox_content_typography_hover_font_size'  => Elements::size( 14 ),
							'eael_infobox_content_typography_hover_line_height' => Elements::size( 1.6, 'em' ),
						]
					),
				],
				$point['title']
			);
		}

		return Elements::container(
			[
				'content_width'    => 'boxed',
				'flex_direction'   => 'row',
				'flex_align_items' => 'flex-start',
				'flex_wrap'        => 'wrap',
				'flex_gap'         => Elements::gap( 24 ),
				'flex_gap_mobile'  => Elements::gap( 20 ),
				'padding'          => Elements::spacing( 28, 24, 36, 24 ),
				'padding_tablet'   => Elements::spacing( 24, 20, 28, 20 ),
				'padding_mobile'   => Elements::spacing( 22, 16, 24, 16 ),
				'border_border'    => 'solid',
				'border_width'     => Elements::spacing( 1, 0, 0, 0 ),
				'border_color'     => self::LINE,
				'_title'           => __( 'Highlights', 'essential-addons-for-elementor-lite' ),
			],
			$boxes
		);
	}

	/* ---------------------------------------------------------------------
	 * Band 3 — the legal bar.
	 * ------------------------------------------------------------------ */

	/**
	 * Copyright, legal links and credit on a dark bar.
	 *
	 * @since 6.7.3
	 *
	 * @return array
	 */
	protected static function legal_bar() {
		$legal = [
			__( 'Terms of Use', 'essential-addons-for-elementor-lite' ),
			__( 'Privacy Policy', 'essential-addons-for-elementor-lite' ),
			__( 'Refund Policy', 'essential-addons-for-elementor-lite' ),
		];

		$rows = [];

		foreach ( $legal as $item ) {
			$rows[] = Elements::row( [
				'text'          => $item,
				'link'          => Elements::link(),
				'selected_icon' => Elements::icon( '' ),
			] );
		}

		$copyright = self::column(
			34,
			100,
			100,
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
						'align'                 => 'start',
						'align_tablet'          => 'center',
						'text_color'            => self::DARK_INK,
						'typography_typography' => 'custom',
						'typography_font_size'  => Elements::size( 14 ),
					]
				),
			],
			__( 'Copyright', 'essential-addons-for-elementor-lite' )
		);

		$links = self::column(
			32,
			100,
			100,
			[
				Elements::widget(
					'icon-list',
					[
						// Inline, so three short links read as a row rather than a
						// stack sitting under the copyright line.
						'view'                       => 'inline',
						'icon_list'                  => $rows,
						'space_between'              => Elements::size( 28 ),
						'text_indent'                => Elements::size( 0 ),
						'icon_align'                 => 'center',
						'icon_align_tablet'          => 'center',
						'text_color'                 => self::DARK_INK,
						'text_color_hover'           => self::SURFACE,
						'icon_typography_typography' => 'custom',
						'icon_typography_font_size'  => Elements::size( 14 ),
					]
				),
			],
			__( 'Legal Links', 'essential-addons-for-elementor-lite' )
		);

		$credit = self::column(
			30,
			100,
			100,
			[
				Elements::widget(
					'text-editor',
					[
						'editor'                => '<p>' . __( 'Made with love by your team', 'essential-addons-for-elementor-lite' ) . '</p>',
						'align'                 => 'end',
						'align_tablet'          => 'center',
						'text_color'            => self::DARK_INK,
						'typography_typography' => 'custom',
						'typography_font_size'  => Elements::size( 14 ),
					]
				),
			],
			__( 'Credit', 'essential-addons-for-elementor-lite' )
		);

		return Elements::container(
			[
				'content_width'         => 'boxed',
				'flex_direction'        => 'row',
				'flex_align_items'      => 'center',
				'flex_justify_content'  => 'space-between',
				'flex_wrap'             => 'wrap',
				'flex_gap'              => Elements::gap( 16 ),
				'flex_gap_tablet'       => Elements::gap( 10 ),
				'padding'               => Elements::spacing( 20, 24, 20, 24 ),
				'padding_mobile'        => Elements::spacing( 20, 16, 20, 16 ),
				'background_background' => 'classic',
				'background_color'      => self::DARK,
				'_title'                => __( 'Legal Bar', 'essential-addons-for-elementor-lite' ),
			],
			[ $copyright, $links, $credit ]
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
	 *
	 * @return array
	 */
	protected static function column( $width, $tablet, $mobile, $children, $title = '' ) {
		$settings = [
			'content_width'  => 'full',
			'width'          => Elements::size( $width, '%' ),
			'width_tablet'   => Elements::size( $tablet, '%' ),
			'width_mobile'   => Elements::size( $mobile, '%' ),
			'flex_direction' => 'column',
			'flex_gap'       => Elements::gap( 16 ),
			'padding'        => Elements::spacing( 0, 0, 0, 0 ),
		];

		if ( '' !== $title ) {
			$settings['_title'] = $title;
		}

		return Elements::container( $settings, $children );
	}

	/**
	 * A column heading, with the short rule the design puts under it.
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
				'header_size'            => 'h4',
				'title_color'            => self::INK,
				'typography_typography'  => 'custom',
				'typography_font_size'   => Elements::size( 18 ),
				'typography_font_weight' => '600',
			]
		);
	}
}

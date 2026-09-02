<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper;

/**
 * Menu Products.
 *
 * A short, quiet row of products for a Mega Menu panel: a picture, a name and a
 * price, and a Query section to decide which products those are.
 *
 * ## Why this exists rather than the Woo Product Grid
 *
 * EA already has a product grid, and a mega menu preset used to borrow it. That
 * made the preset's fourth column contingent on a *different* element being
 * switched on — in EA's own settings, or in Elementor's element manager — and a
 * preset whose layout collapses because of a checkbox somewhere else is a preset
 * that arrives broken through no fault of the user's. This widget is registered
 * by the Mega Menu itself: if there is a menu to put it in, it is there.
 *
 * It is also a different job. The product grid is a shop page — presets, badges,
 * ratings, quick view, compare, wishlist, load more, pagination, an add-to-cart
 * button, and a style tab to match. A menu teaser is three lines that have to
 * fit in a panel and get out of the way, and every one of those features is
 * something to turn off. Starting from nothing is shorter than starting from all
 * of it.
 *
 * ## Without WooCommerce
 *
 * It renders demo cards on Elementor's own placeholder image — same markup, same
 * grid, same spacing — so a layout built around it does not develop a hole on a
 * site that has not installed a shop yet. Nothing about the widget disappears;
 * the Query section simply has nothing to answer with.
 *
 * ## No stylesheet
 *
 * Every rule this widget needs is written by one of its own controls, from that
 * control's default. There is no CSS file to load, nothing for `Asset_Builder`
 * to attribute, and no `npm run build` in the way of changing how it looks —
 * and the user can reach all of it from the panel, including the parts a
 * stylesheet would normally hide.
 *
 * @since 6.8.4
 */
class Mega_Menu_Products extends Widget_Base {

	/**
	 * Elementor widget name.
	 */
	const WIDGET_NAME = 'eael-mega-menu-products';

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return self::WIDGET_NAME;
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return esc_html__( 'Menu Products', 'essential-addons-for-elementor-lite' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eaicon-product-grid';
	}

	/**
	 * @inheritDoc
	 */
	public function get_categories() {
		return [ 'essential-addons-elementor', 'woocommerce-elements' ];
	}

	/**
	 * @inheritDoc
	 */
	public function get_keywords() {
		return [
			'mega menu',
			'menu',
			'products',
			'woocommerce',
			'woo',
			'best selling',
			'featured',
			'ea',
			'essential addons',
		];
	}

	/**
	 * Keep it out of the widget panel.
	 *
	 * It is a part of the Mega Menu, not a widget in its own right: its styling
	 * assumes the narrow column of a submenu panel, and it is registered by the
	 * menu rather than by an entry in `config.php`, so it has no element toggle
	 * of its own for a user to find it under. Listing it in the panel would
	 * offer a widget with none of the surrounding documentation the others have.
	 *
	 * Every control still works on an instance that exists — the preset builds
	 * one, and from there it is an ordinary widget.
	 *
	 * @return bool
	 */
	public function show_in_panel() {
		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/mega-menu/';
	}

	/**
	 * Is there a shop to read.
	 *
	 * @since 6.8.4
	 *
	 * @return bool
	 */
	protected function has_woocommerce() {
		return function_exists( 'WC' ) && function_exists( 'wc_get_product' );
	}

	/**
	 * The ways a menu can pick its products.
	 *
	 * The same vocabulary EA's other WooCommerce widgets use, so a user who has
	 * met Filter By once has met it here.
	 *
	 * @since 6.8.4
	 *
	 * @return array
	 */
	protected function get_source_options() {
		return apply_filters( 'eael/mega-menu-products/source-options', [
			'best-selling' => esc_html__( 'Best Selling Products', 'essential-addons-for-elementor-lite' ),
			'featured'     => esc_html__( 'Featured Products', 'essential-addons-for-elementor-lite' ),
			'recent'       => esc_html__( 'Recent Products', 'essential-addons-for-elementor-lite' ),
			'sale'         => esc_html__( 'Sale Products', 'essential-addons-for-elementor-lite' ),
			'top-rated'    => esc_html__( 'Top Rated Products', 'essential-addons-for-elementor-lite' ),
			'manual'       => esc_html__( 'Manual Selection', 'essential-addons-for-elementor-lite' ),
		] );
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		$this->register_query_controls();
		$this->register_layout_controls();
		$this->register_card_style_controls();
		$this->register_text_style_controls();
	}

	/* ---------------------------------------------------------------------
	 * Content.
	 * ------------------------------------------------------------------ */

	/**
	 * Which products appear.
	 *
	 * @since 6.8.4
	 */
	protected function register_query_controls() {
		$this->start_controls_section(
			'eael_mm_products_query_section',
			[ 'label' => esc_html__( 'Query', 'essential-addons-for-elementor-lite' ) ]
		);

		if ( ! $this->has_woocommerce() ) {
			$this->add_control(
				'eael_mm_products_wc_notice',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
						/* translators: %s: link to the WooCommerce plugin installer. */
						__( '<strong>WooCommerce</strong> is not active, so this shows placeholder cards. Install and activate %s to list real products here.', 'essential-addons-for-elementor-lite' ),
						'<a href="' . esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ) . '" target="_blank">WooCommerce</a>'
					),
					'content_classes' => 'eael-warning',
				]
			);
		}

		$this->add_control(
			'eael_mm_products_source',
			[
				'label'   => esc_html__( 'Filter By', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'best-selling',
				'options' => $this->get_source_options(),
			]
		);

		$this->add_control(
			'eael_mm_products_include',
			[
				'label'       => esc_html__( 'Select Products', 'essential-addons-for-elementor-lite' ),
				'type'        => 'eael-select2',
				'label_block' => true,
				'multiple'    => true,
				'source_name' => 'post_type',
				'source_type' => 'product',
				'condition'   => [ 'eael_mm_products_source' => 'manual' ],
			]
		);

		$this->add_control(
			'eael_mm_products_categories',
			[
				'label'       => esc_html__( 'Product Categories', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->has_woocommerce() ? Helper::get_terms_list( 'product_cat', 'slug' ) : [],
				'condition'   => [ 'eael_mm_products_source!' => 'manual' ],
			]
		);

		$this->add_control(
			'eael_mm_products_tags',
			[
				'label'       => esc_html__( 'Product Tags', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->has_woocommerce() ? Helper::get_terms_list( 'product_tag', 'slug' ) : [],
				'condition'   => [ 'eael_mm_products_source!' => 'manual' ],
			]
		);

		$this->add_control(
			'eael_mm_products_exclude',
			[
				'label'       => esc_html__( 'Exclude Products', 'essential-addons-for-elementor-lite' ),
				'type'        => 'eael-select2',
				'label_block' => true,
				'multiple'    => true,
				'source_name' => 'post_type',
				'source_type' => 'product',
				'condition'   => [ 'eael_mm_products_source!' => 'manual' ],
			]
		);

		$this->add_control(
			'eael_mm_products_count',
			[
				'label'     => esc_html__( 'Products Count', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 2,
				'min'       => 1,
				'max'       => 24,
				'condition' => [ 'eael_mm_products_source!' => 'manual' ],
			]
		);

		$this->add_control(
			'eael_mm_products_offset',
			[
				'label'     => esc_html__( 'Offset', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0,
				'min'       => 0,
				'condition' => [ 'eael_mm_products_source!' => 'manual' ],
			]
		);

		$this->add_control(
			'eael_mm_products_order',
			[
				'label'     => esc_html__( 'Order', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'desc',
				'options'   => [
					'desc' => esc_html__( 'Descending', 'essential-addons-for-elementor-lite' ),
					'asc'  => esc_html__( 'Ascending', 'essential-addons-for-elementor-lite' ),
				],
				// Manual selection is already in the order the user dragged the
				// rows into, and Featured / Best Selling / Top Rated each mean a
				// ranking — reversing one gives the *worst* sellers, which is
				// never what the control was reached for.
				'condition' => [ 'eael_mm_products_source' => [ 'recent', 'sale' ] ],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * What a card carries, and how the cards are arranged.
	 *
	 * @since 6.8.4
	 */
	protected function register_layout_controls() {
		$this->start_controls_section(
			'eael_mm_products_layout_section',
			[ 'label' => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ) ]
		);

		$this->add_responsive_control(
			'eael_mm_products_columns',
			[
				'label'          => esc_html__( 'Columns', 'essential-addons-for-elementor-lite' ),
				'type'           => Controls_Manager::NUMBER,
				'default'        => 2,
				'tablet_default' => 2,
				'mobile_default' => 2,
				'min'            => 1,
				'max'            => 6,
				'selectors'      => [
					'{{WRAPPER}} .eael-mm-products' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mm_products_gap',
			[
				'label'      => esc_html__( 'Gap', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
				'default'    => [ 'unit' => 'px', 'size' => 16 ],
				'selectors'  => [
					// The base layout, and the only place it is declared. A
					// widget with no stylesheet has to write `display: grid`
					// from somewhere, and a control that always has a value is
					// the one place it cannot be switched off by accident.
					'{{WRAPPER}} .eael-mm-products' => 'display: grid; gap: {{SIZE}}{{UNIT}};',
					// The card is an `<a>`, and themes underline links. **A
					// decoration set on an ancestor draws through its inline
					// descendants and cannot be switched off by them**, so no
					// value in the Title typography group below could ever have
					// removed it — the reset has to be here, on the anchor
					// itself, and it is drawn in the anchor's own colour, which
					// is why that is neutralised too. Setting an underline *on*
					// the title still works: a descendant can add a decoration,
					// it just cannot take an ancestor's away.
					'{{WRAPPER}} .eael-mm-products__card' => 'display: block; text-decoration: none; color: inherit;',
				],
			]
		);

		$this->add_control(
			'eael_mm_products_show_title',
			[
				'label'        => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'eael_mm_products_title_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h6',
				'options'   => [
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'condition' => [ 'eael_mm_products_show_title' => 'yes' ],
			]
		);

		$this->add_control(
			'eael_mm_products_show_price',
			[
				'label'        => esc_html__( 'Price', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'eael_mm_products_image',
				'default' => 'woocommerce_thumbnail',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * The picture.
	 *
	 * @since 6.8.4
	 */
	protected function register_card_style_controls() {
		$this->start_controls_section(
			'eael_mm_products_image_style_section',
			[
				'label' => esc_html__( 'Image', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'eael_mm_products_image_height',
			[
				'label'      => esc_html__( 'Height', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [ 'px' => [ 'min' => 60, 'max' => 480 ] ],
				'default'    => [ 'unit' => 'px', 'size' => 211 ],
				'selectors'  => [
					// `cover` with a fixed height, so two cards are the same
					// height whatever was uploaded — a shop with one square
					// photograph and one portrait one is the normal case, not
					// the exception.
					'{{WRAPPER}} .eael-mm-products__image img' => 'display: block; width: 100%; height: {{SIZE}}{{UNIT}}; object-fit: cover;',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mm_products_image_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-mm-products__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_mm_products_image_opacity_hover',
			[
				'label'     => esc_html__( 'Hover Opacity', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [ 'px' => [ 'min' => 0.1, 'max' => 1, 'step' => 0.05 ] ],
				'default'   => [ 'size' => 0.85 ],
				'selectors' => [
					'{{WRAPPER}} .eael-mm-products__card:hover .eael-mm-products__image img' => 'opacity: {{SIZE}};',
					'{{WRAPPER}} .eael-mm-products__image img' => 'transition: opacity 0.2s ease;',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * The name and the price.
	 *
	 * @since 6.8.4
	 */
	protected function register_text_style_controls() {
		$this->start_controls_section(
			'eael_mm_products_text_style_section',
			[
				'label' => esc_html__( 'Title & Price', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'eael_mm_products_title_heading',
			[
				'label' => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'eael_mm_products_title_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#848484',
				'selectors' => [
					'{{WRAPPER}} .eael-mm-products__title, {{WRAPPER}} .eael-mm-products__title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_mm_products_title_color_hover',
			[
				'label'     => esc_html__( 'Hover Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#444444',
				'selectors' => [
					'{{WRAPPER}} .eael-mm-products__card:hover .eael-mm-products__title, {{WRAPPER}} .eael-mm-products__card:hover .eael-mm-products__title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'eael_mm_products_title_typography',
				'selector'       => '{{WRAPPER}} .eael-mm-products__title, {{WRAPPER}} .eael-mm-products__title a',
				'fields_options' => [
					'typography'      => [ 'default' => 'custom' ],
					'font_size'       => [ 'default' => [ 'unit' => 'px', 'size' => 15 ] ],
					'font_weight'     => [ 'default' => '400' ],
					'line_height'     => [ 'default' => [ 'unit' => 'em', 'size' => 1.4 ] ],
					// A theme that underlines every link underlines this one, and
					// a decoration set on an ancestor cannot be switched off by a
					// descendant — so it is set here, where the user can see it.
					'text_decoration' => [ 'default' => 'none' ],
				],
			]
		);

		$this->add_responsive_control(
			'eael_mm_products_title_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				// Themes give headings a margin, and this one is a heading tag
				// by default. Spelled out so the gap under the picture is the
				// value below and not whatever the theme happened to set.
				'default'    => [ 'unit' => 'px', 'top' => '14', 'right' => '0', 'bottom' => '6', 'left' => '0', 'isLinked' => false ],
				'selectors'  => [
					'{{WRAPPER}} .eael-mm-products__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_mm_products_price_heading',
			[
				'label'     => esc_html__( 'Price', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'eael_mm_products_price_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#686868',
				'selectors' => [
					'{{WRAPPER}} .eael-mm-products__price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'eael_mm_products_price_typography',
				'selector'       => '{{WRAPPER}} .eael-mm-products__price',
				'fields_options' => [
					'typography'  => [ 'default' => 'custom' ],
					'font_size'   => [ 'default' => [ 'unit' => 'px', 'size' => 15 ] ],
					'font_weight' => [ 'default' => '600' ],
					'line_height' => [ 'default' => [ 'unit' => 'em', 'size' => 1.2 ] ],
				],
			]
		);

		$this->add_responsive_control(
			'eael_mm_products_price_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'default'    => [ 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ],
				'selectors'  => [
					'{{WRAPPER}} .eael-mm-products__price' => 'display: block; margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/* ---------------------------------------------------------------------
	 * Render.
	 * ------------------------------------------------------------------ */

	/**
	 * @inheritDoc
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$cards    = $this->has_woocommerce() ? $this->get_products( $settings ) : $this->get_demo_cards( $settings );

		if ( ! $cards ) {
			// Only ever an empty *shop* result — the demo cards above are never
			// empty. Said out loud rather than rendered as a blank column,
			// because in the editor a blank column is indistinguishable from a
			// widget that failed to load.
			printf(
				'<p class="eael-mm-products__empty">%s</p>',
				esc_html__( 'No products found.', 'essential-addons-for-elementor-lite' )
			);

			return;
		}

		$this->add_render_attribute( 'wrapper', 'class', 'eael-mm-products' );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php foreach ( $cards as $card ) : ?>
				<a class="eael-mm-products__card" href="<?php echo esc_url( $card['link'] ); ?>">
					<span class="eael-mm-products__image">
						<?php
						// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built by wp_get_attachment_image() / esc'd below.
						echo $card['image'];
						?>
					</span>
					<?php if ( '' !== $card['title'] ) : ?>
						<?php printf(
							'<%1$s class="eael-mm-products__title">%2$s</%1$s>',
							esc_attr( $card['tag'] ),
							esc_html( $card['title'] )
						); ?>
					<?php endif; ?>
					<?php if ( '' !== $card['price'] ) : ?>
						<span class="eael-mm-products__price"><?php echo esc_html( $card['price'] ); ?></span>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * The products this widget's settings ask for.
	 *
	 * @since 6.8.4
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return array Cards.
	 */
	protected function get_products( $settings ) {
		$tag         = Helper::eael_validate_html_tag( ! empty( $settings['eael_mm_products_title_tag'] ) ? $settings['eael_mm_products_title_tag'] : 'h6' );
		$show_title  = 'yes' === ( isset( $settings['eael_mm_products_show_title'] ) ? $settings['eael_mm_products_show_title'] : 'yes' );
		$show_price  = 'yes' === ( isset( $settings['eael_mm_products_show_price'] ) ? $settings['eael_mm_products_show_price'] : 'yes' );
		$image_size  = ! empty( $settings['eael_mm_products_image_size'] ) ? $settings['eael_mm_products_image_size'] : 'woocommerce_thumbnail';

		$cards = [];

		foreach ( $this->query_products( $settings ) as $product ) {
			$image_id = (int) $product->get_image_id();

			$cards[] = [
				'link'  => $product->get_permalink(),
				'image' => $image_id
					? wp_get_attachment_image( $image_id, $image_size, false, [ 'alt' => $product->get_name() ] )
					: $this->placeholder_image( $product->get_name() ),
				'title' => $show_title ? $product->get_name() : '',
				'price' => $show_price ? $this->price_text( $product ) : '',
				'tag'   => $tag,
			];
		}

		return $cards;
	}

	/**
	 * The WP_Query behind one Filter By value.
	 *
	 * `WP_Query` rather than `wc_get_products()`: three of the six sources are
	 * meta or taxonomy sorts that the product query helper takes no documented
	 * argument for, and doing five of them one way and one the other is worse
	 * than doing all six the same way. These are the same queries WooCommerce's
	 * own `[featured_products]`, `[best_selling_products]`, `[sale_products]`
	 * and `[top_rated_products]` shortcodes run.
	 *
	 * @since 6.8.4
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return array List of WC_Product.
	 */
	protected function query_products( $settings ) {
		$source = ! empty( $settings['eael_mm_products_source'] ) ? $settings['eael_mm_products_source'] : 'best-selling';
		$count  = isset( $settings['eael_mm_products_count'] ) ? absint( $settings['eael_mm_products_count'] ) : 2;
		$order  = ! empty( $settings['eael_mm_products_order'] ) && 'asc' === $settings['eael_mm_products_order'] ? 'ASC' : 'DESC';

		$args = [
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => $count > 0 ? $count : 2,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'order'               => $order,
			'tax_query'           => [], // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		];

		if ( 'manual' === $source ) {
			$ids = array_filter( array_map( 'absint', (array) ( $settings['eael_mm_products_include'] ?? [] ) ) );

			if ( ! $ids ) {
				return [];
			}

			$args['post__in']       = $ids;
			$args['orderby']        = 'post__in';
			$args['posts_per_page'] = count( $ids );
			unset( $args['order'] );
		} else {
			$args['offset']       = isset( $settings['eael_mm_products_offset'] ) ? absint( $settings['eael_mm_products_offset'] ) : 0;
			$args['post__not_in'] = array_filter( array_map( 'absint', (array) ( $settings['eael_mm_products_exclude'] ?? [] ) ) );

			foreach ( [ 'product_cat' => 'eael_mm_products_categories', 'product_tag' => 'eael_mm_products_tags' ] as $taxonomy => $key ) {
				$slugs = array_filter( (array) ( $settings[ $key ] ?? [] ) );

				if ( $slugs ) {
					$args['tax_query'][] = [
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => array_map( 'sanitize_title', $slugs ),
					];
				}
			}

			switch ( $source ) {
				case 'featured':
					$args['tax_query'][] = [
						'taxonomy' => 'product_visibility',
						'field'    => 'name',
						'terms'    => 'featured',
						'operator' => 'IN',
					];
					break;

				case 'best-selling':
					$args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
					$args['orderby']  = 'meta_value_num';
					$args['order']    = 'DESC';
					break;

				case 'top-rated':
					$args['meta_key'] = '_wc_average_rating'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
					$args['orderby']  = 'meta_value_num';
					$args['order']    = 'DESC';
					break;

				case 'sale':
					$args['post__in'] = array_merge( [ 0 ], wc_get_product_ids_on_sale() );
					$args['orderby']  = 'date';
					break;

				case 'recent':
				default:
					$args['orderby'] = 'date';
					break;
			}
		}

		// Products the shop owner has hidden from the catalogue have no business
		// being advertised in the menu.
		$args['tax_query'][] = [
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => [ 'exclude-from-catalog' ],
			'operator' => 'NOT IN',
		];

		$products = [];

		foreach ( get_posts( $args ) as $post ) {
			$product = wc_get_product( $post->ID );

			if ( $product && $product->is_visible() ) {
				$products[] = $product;
			}
		}

		return $products;
	}

	/**
	 * One product's price, as text.
	 *
	 * `get_price_html()` is markup and this is printed as text, so three things
	 * have to happen in order and skipping any one of them shows in the menu:
	 *
	 * - **The screen-reader span goes first.** A price range ships a visually
	 *   hidden "Price range: 15.00 through 20.00" *inside* the markup, so
	 *   stripping tags without removing that element first prints it twice.
	 * - **Then the tags.**
	 * - **Then the entities**, or the currency arrives as `&#2547;` — this is
	 *   escaped on output, so an entity left here is shown, not decoded.
	 *
	 * The non-breaking spaces the amounts are padded with collapse last, which
	 * turns `15.00৳  – 20.00৳ ` into `15.00৳ – 20.00৳`.
	 *
	 * @since 6.8.4
	 *
	 * @param \WC_Product $product Product.
	 *
	 * @return string
	 */
	protected function price_text( $product ) {
		$html = (string) $product->get_price_html();
		$html = preg_replace( '#<span[^>]*class="[^"]*screen-reader-text[^"]*"[^>]*>.*?</span>#is', '', $html );
		$text = html_entity_decode( wp_strip_all_tags( (string) $html ), ENT_QUOTES, 'UTF-8' );

		// \xC2\xA0 is a non-breaking space in UTF-8; \s does not match it.
		return trim( preg_replace( '/[\s\xC2\xA0]+/u', ' ', $text ) );
	}

	/**
	 * The cards a site with no WooCommerce sees.
	 *
	 * As many as the Products Count asks for, so the column is the shape the
	 * layout was built for rather than a fixed pair — and on Elementor's own
	 * placeholder, which is already installed and which users read as "swap me".
	 *
	 * @since 6.8.4
	 *
	 * @param array $settings Widget settings.
	 *
	 * @return array Cards.
	 */
	protected function get_demo_cards( $settings ) {
		$demo = [
			[ 'title' => __( 'Leather JUSTINE Tote', 'essential-addons-for-elementor-lite' ), 'price' => __( '$70', 'essential-addons-for-elementor-lite' ) ],
			[ 'title' => __( 'Tombot Stripe Shirt', 'essential-addons-for-elementor-lite' ), 'price' => __( '$99', 'essential-addons-for-elementor-lite' ) ],
			[ 'title' => __( 'Ridley Canvas Jacket', 'essential-addons-for-elementor-lite' ), 'price' => __( '$120', 'essential-addons-for-elementor-lite' ) ],
			[ 'title' => __( 'Marlow Knit Sweater', 'essential-addons-for-elementor-lite' ), 'price' => __( '$85', 'essential-addons-for-elementor-lite' ) ],
		];

		$tag        = Helper::eael_validate_html_tag( ! empty( $settings['eael_mm_products_title_tag'] ) ? $settings['eael_mm_products_title_tag'] : 'h6' );
		$show_title = 'yes' === ( isset( $settings['eael_mm_products_show_title'] ) ? $settings['eael_mm_products_show_title'] : 'yes' );
		$show_price = 'yes' === ( isset( $settings['eael_mm_products_show_price'] ) ? $settings['eael_mm_products_show_price'] : 'yes' );
		$count      = isset( $settings['eael_mm_products_count'] ) ? absint( $settings['eael_mm_products_count'] ) : 2;
		$count      = $count > 0 ? $count : 2;

		$cards = [];

		for ( $i = 0; $i < $count; $i++ ) {
			$item = $demo[ $i % count( $demo ) ];

			$cards[] = [
				'link'  => '#',
				'image' => $this->placeholder_image( $item['title'] ),
				'title' => $show_title ? $item['title'] : '',
				'price' => $show_price ? $item['price'] : '',
				'tag'   => $tag,
			];
		}

		return $cards;
	}

	/**
	 * Elementor's placeholder, as an `<img>`.
	 *
	 * @since 6.8.4
	 *
	 * @param string $alt Alternative text.
	 *
	 * @return string
	 */
	protected function placeholder_image( $alt ) {
		return sprintf(
			'<img src="%1$s" alt="%2$s" loading="lazy" />',
			esc_url( class_exists( '\Elementor\Utils' ) ? Utils::get_placeholder_image_src() : '' ),
			esc_attr( $alt )
		);
	}
}

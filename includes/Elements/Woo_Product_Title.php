<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Essential_Addons_Elementor\Classes\Helper;

class Woo_Product_Title extends Widget_Base {

	public function get_name() {
		return 'eael-woo-product-title';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Title', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-product-price';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor', 'woocommerce-elements' ];
	}

	public function get_keywords() {
		return [
			'woocommerce',
			'product',
			'title',
			'heading',
			'woo',
			'ea',
			'product title',
			'ea product title',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/ea-woo-product-title';
	}

	protected function register_controls() {

		$this->eael_wc_notice_controls();
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		/**
		 * Content Tab
		 */
		$this->start_controls_section(
			'eael_section_product_title_content',
			[
				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'header_size',
			[
				'label'   => esc_html__( 'HTML Tag', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
			]
		);

		$this->add_control(
			'eael_product_title_link',
			[
				'label'        => esc_html__( 'Link', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_control(
			'eael_product_title_link_type',
			[
				'label'     => esc_html__( 'Link Type', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'product',
				'options'   => [
					'product' => esc_html__( 'Product Permalink', 'essential-addons-for-elementor-lite' ),
					'custom'  => esc_html__( 'Custom URL', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_product_title_link' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_product_title_custom_link',
			[
				'label'         => esc_html__( 'Custom URL', 'essential-addons-for-elementor-lite' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'essential-addons-for-elementor-lite' ),
				'show_external' => true,
				'dynamic'       => [ 'active' => true ],
				'condition'     => [
					'eael_product_title_link'      => 'yes',
					'eael_product_title_link_type' => 'custom',
				],
			]
		);

		// Prefix.
		$this->add_control(
            'eael_product_title_show_prefix',
			[
				'label'        => esc_html__( 'Show Prefix', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			]
		);

		$this->add_control(
            'eael_product_title_prefix_content',
			[
				'label'     => esc_html__( 'Prefix Content', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'text',
				'toggle'    => false,
				'options'   => [
					'text' => [
						'title' => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-t-letter',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-star',
					],
				],
				'condition' => [
                    'eael_product_title_show_prefix' => 'yes',
				],
			]
		);

		$this->add_control(
            'eael_product_title_prefix_text',
			[
				'label'       => esc_html__( 'Prefix Text', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => esc_html__( 'New', 'essential-addons-for-elementor-lite' ),
				'label_block' => false,
				'condition'   => [
                    'eael_product_title_show_prefix'    => 'yes',
                    'eael_product_title_prefix_content' => 'text',
				],
			]
		);

		$this->add_control(
            'eael_product_title_prefix_icon',
			[
				'label'     => esc_html__( 'Prefix Icon', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
                    'eael_product_title_show_prefix'    => 'yes',
                    'eael_product_title_prefix_content' => 'icon',
				],
			]
		);

		// Suffix.
		$this->add_control(
            'eael_product_title_show_suffix',
			[
				'label'        => esc_html__( 'Show Suffix', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
			]
		);

		$this->add_control(
            'eael_product_title_suffix_content',
			[
				'label'     => esc_html__( 'Suffix Content', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'text',
				'toggle'    => false,
				'options'   => [
					'text' => [
						'title' => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-t-letter',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-star',
					],
				],
				'condition' => [
                    'eael_product_title_show_suffix' => 'yes',
				],
			]
		);

		$this->add_control(
            'eael_product_title_suffix_text',
			[
				'label'       => esc_html__( 'Suffix Text', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'default'     => esc_html__( 'Sale', 'essential-addons-for-elementor-lite' ),
				'label_block' => false,
				'condition'   => [
                    'eael_product_title_show_suffix'    => 'yes',
                    'eael_product_title_suffix_content' => 'text',
				],
			]
		);

		$this->add_control(
            'eael_product_title_suffix_icon',
			[
				'label'     => esc_html__( 'Suffix Icon', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-tag',
					'library' => 'fa-solid',
				],
				'condition' => [
                    'eael_product_title_show_suffix'    => 'yes',
                    'eael_product_title_suffix_content' => 'icon',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Style Tab — Title
		 */
		$this->start_controls_section(
			'eael_section_product_title_style',
			[
				'label' => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-woo-product-title'                => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .eael-woo-product-title .product_title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-woo-product-title .product_title'    => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-woo-product-title .product_title a'  => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .eael-woo-product-title .product_title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .eael-woo-product-title .product_title',
			]
		);

		$this->add_responsive_control(
			'eael_product_title_container_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-woo-product-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_product_title_container_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-woo-product-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_product_title_container_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-woo-product-title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_product_title_container_border',
				'selector' => '{{WRAPPER}} .eael-woo-product-title',
			]
		);

		$this->add_responsive_control(
			'eael_product_title_container_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-woo-product-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->register_affix_style_controls( 'prefix' );
		$this->register_affix_style_controls( 'suffix' );
	}

	/**
	 * Register Style-tab controls for a prefix/suffix affix.
	 *
	 * Text variant: color, typography, margin, padding.
	 * Icon variant: size, color, margin, padding.
	 */
	protected function register_affix_style_controls( $type ) {
		$base  = 'eael_product_title_';
		$show  = $base . 'show_' . $type;
		$ctype = $base . $type . '_content';
		$sel   = '{{WRAPPER}} .eael-product-title-' . $type;
		$label = 'prefix' === $type
			? esc_html__( 'Prefix', 'essential-addons-for-elementor-lite' )
			: esc_html__( 'Suffix', 'essential-addons-for-elementor-lite' );

		$this->start_controls_section(
			$base . $type . '_style',
			[
				'label'     => $label,
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ $show => 'yes' ],
			]
		);

		// Text variant.
		$this->add_control(
			$base . $type . '_text_heading',
			[
				'label'     => esc_html__( 'Text', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [ $ctype => 'text' ],
			]
		);

		$this->add_control(
			$base . $type . '_text_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$sel . '-text' => 'color: {{VALUE}};',
				],
				'condition' => [ $ctype => 'text' ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => $base . $type . '_text_typography',
				'selector'  => $sel . '-text',
				'condition' => [ $ctype => 'text' ],
			]
		);

		$this->add_responsive_control(
			$base . $type . '_text_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					$sel . '-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [ $ctype => 'text' ],
			]
		);

		$this->add_responsive_control(
			$base . $type . '_text_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					$sel . '-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [ $ctype => 'text' ],
			]
		);

		// Icon variant.
		$this->add_control(
			$base . $type . '_icon_heading',
			[
				'label'     => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [ $ctype => 'icon' ],
			]
		);

		$this->add_responsive_control(
			$base . $type . '_icon_size',
			[
				'label'      => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [ 'min' => 6, 'max' => 100 ],
					'em' => [ 'min' => 0.5, 'max' => 10, 'step' => 0.1 ],
				],
				'selectors'  => [
					$sel . '-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
					$sel . '-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ $ctype => 'icon' ],
			]
		);

		$this->add_control(
			$base . $type . '_icon_color',
			[
				'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$sel . '-icon i'   => 'color: {{VALUE}};',
					$sel . '-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [ $ctype => 'icon' ],
			]
		);

		$this->add_responsive_control(
			$base . $type . '_icon_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					$sel . '-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [ $ctype => 'icon' ],
			]
		);

		$this->add_responsive_control(
			$base . $type . '_icon_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					$sel . '-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [ $ctype => 'icon' ],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Show a warning section when WooCommerce is not installed/activated.
	 *
	 * @return void
	 */
	protected function eael_wc_notice_controls() {
		if ( ! function_exists( 'WC' ) ) {
			$this->start_controls_section(
				'eael_global_warning',
				[
					'label' => __( 'Warning!', 'essential-addons-for-elementor-lite' ),
				]
			);
			$this->add_control(
				'eael_global_warning_text',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.', 'essential-addons-for-elementor-lite' ),
					'content_classes' => 'eael-warning',
				]
			);
			$this->end_controls_section();

			return;
		}
	}

	protected function render() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$settings  = $this->get_settings_for_display();
		$product   = Helper::get_product();
		$is_editor = Plugin::$instance->editor->is_edit_mode();

		// Resolve the title text.
		if ( ! $product ) {
			if ( ! $is_editor ) {
				return;
			}
			$title = esc_html__( 'Product Title', 'essential-addons-for-elementor-lite' );
		} else {
			$title = $product->get_name();
		}

		// Validate the HTML tag against EA's allow-list (falls back to div).
		$tag = Helper::eael_validate_html_tag( $settings['header_size'] );

		$title_html = esc_html( $title );

		// Optional link wrap.
		if ( 'yes' === $settings['eael_product_title_link'] ) {
			if ( 'custom' === $settings['eael_product_title_link_type'] ) {
				if ( ! empty( $settings['eael_product_title_custom_link']['url'] ) ) {
					$this->add_link_attributes( 'product_title_link', $settings['eael_product_title_custom_link'] );
					$title_html = sprintf(
						'<a %1$s>%2$s</a>',
						$this->get_render_attribute_string( 'product_title_link' ),
						$title_html
					);
				}
			} elseif ( $product ) {
				$title_html = sprintf(
					'<a href="%1$s">%2$s</a>',
					esc_url( get_permalink( $product->get_id() ) ),
					$title_html
				);
			}
		}

		$title_element = sprintf(
			'<%1$s class="product_title entry-title">%2$s</%1$s>',
			esc_attr( $tag ),
			$title_html
		);

		// Optional prefix / suffix (text or icon) flanking the title.
		$prefix_html = $this->render_affix( $settings, 'prefix' );
		$suffix_html = $this->render_affix( $settings, 'suffix' );

		printf(
			'<div class="eael-woo-product-title">%1$s%2$s%3$s</div>',
			$prefix_html,
			$title_element,
			$suffix_html
		);
	}

	/**
	 * Build a prefix/suffix affix block (text or icon) that flanks the title.
	 *
	 * @param array  $settings Widget settings.
	 * @param string $type     'prefix' or 'suffix'.
	 */
	protected function render_affix( $settings, $type ) {
		$base = 'eael_product_title_';

		if ( 'yes' !== ( $settings[ $base . 'show_' . $type ] ?? '' ) ) {
			return '';
		}

		$content = $settings[ $base . $type . '_content' ] ?? 'text';

		if ( 'icon' === $content ) {
			$icon = $settings[ $base . $type . '_icon' ] ?? [];
			if ( empty( $icon['value'] ) ) {
				return '';
			}
			ob_start();
			Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
			$icon_html = ob_get_clean();

			return sprintf(
				'<span class="eael-product-title-%1$s eael-product-title-%1$s-icon">%2$s</span>',
				esc_attr( $type ),
				$icon_html
			);
		}

		$text = $settings[ $base . $type . '_text' ] ?? '';
		if ( '' === $text ) {
			return '';
		}

		return sprintf(
			'<span class="eael-product-title-%1$s eael-product-title-%1$s-text">%2$s</span>',
			esc_attr( $type ),
			wp_kses( $text, Helper::eael_allowed_tags() )
		);
	}

	public function render_plain_content() {}
}

<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
   exit;
}

use Elementor\Widget_Base;
use \Essential_Addons_Elementor\Classes\Helper;

class Woo_Product_Images extends Widget_Base {
   public function get_name() {
		return 'eael-woo-product-images';
	}

	public function get_title() {
		return esc_html__( 'Woo Product Images', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-product-image';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor', 'woocommerce-elements' ];
	}

	public function get_keywords() {
		return [ 
			'woocommerce', 
			'images', 
			'product', 
			'ea',
         'essential addons',
			'ea product',
			'ea product image',
			'woo',
			'woo product',
			'woo image',
			'woo product image',
			'Product Image',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/docs/ea-woo-product-images/';
	}

	public function get_style_depends() {
		return [
			'e-swiper'
		];
	}

	protected function register_controls() {
		$this->eael_wc_notice_controls();
		if ( !function_exists( 'WC' ) ) {
			return;
		}

		//General Control
		$this->eael_product_general_control();

		// Style Tab Start
		$this->start_controls_section(
			'eael_images_section_title_style',
			[
				'label' => esc_html__( 'Images', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

        //Start image
      $this->add_control(
			'eael_image_sale_flash',
			[
				'label'        => esc_html__( 'Sale Flash', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'render_type'  => 'template',
				'return_value' => 'yes',
				'default'      => 'yes',
				'prefix_class' => '',
			]
		);

      $this->add_control(
			'eael_image_sale_flash_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-images span.onsale, {{WRAPPER}} .eael-single-product-images span.ast-onsale-card ' => 'color: {{VALUE}};',
				],
					'condition' => [
						'eael_image_sale_flash' => 'yes',
					],      
			]
		);

		$this->add_control(
			'eael_image_sale_flash_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-images span.onsale, {{WRAPPER}} .eael-single-product-images span.ast-onsale-card ' => 'background-color: {{VALUE}};',
				],
					'condition' => [
						'eael_image_sale_flash' => 'yes',
					],
			]
		);

		$this->add_control(
			'eael_iamge_heading',
			[
				'label'     => esc_html__( 'Image', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

      $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'eael_image_border',
				'selector'  => '{{WRAPPER}} .eael-single-product-images .product_image_slider__container .swiper-slide-active img',
			]
		);

      $this->add_control(
			'eael_image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
						'{{WRAPPER}} .eael-single-product-images .product_image_slider__container .swiper-slide-active img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

      $this->add_responsive_control(
			'eael_image_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__container .swiper-slide' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'eael_image_zoom_box',
			[
				'label'     => esc_html__( 'Image Popup', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);

		$this->add_control(
			'eael_image_zoom_show',
			[
				'label'        => esc_html__( 'Show Image Popup', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_responsive_control(
			'eael_image_zoom_box_size',
			[
				'label'      => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__container .product_image_slider__trigger' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'eael_image_zoom_show' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_image_zoom_box_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__container .product_image_slider__trigger' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'eael_image_zoom_show' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_image_zoom_box_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#858585',
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__container .product_image_slider__trigger svg path' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'eael_image_zoom_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_image_zoom_box_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'default'    => [
					'top'    => 5,
					'right'  => 8,
					'bottom' => 5,
					'left'   => 8,
					'unit'   => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__container .product_image_slider__trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'eael_image_zoom_show' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_image_zoom_box_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'custom' ],
				'default'    => [
					'top'    => 15,
					'right'  => 15,
					'bottom' => 15,
					'left'   => 15,
					'unit'   => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__container .product_image_slider__trigger' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'eael_image_zoom_show' => 'yes',
				],
			]
		);

        //End image

        //Start thumbnail
      $this->add_control(
			'eael_thumb_heading',
			[
				'label'     => esc_html__( 'Thumbnails', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
				'condition' => [
					'eael_pi_thumbnail' => 'yes',
				],
			]
		);

      $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'eael_thumb_border',
				'selector'  => '{{WRAPPER}} .eael-single-product-images .product_image_slider__thumbs .swiper-slide',
				'condition' => [
					'eael_pi_thumbnail' => 'yes',
				],
			]
		);

      $this->add_control(
			'eael_thumb_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__thumbs .swiper-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'condition' => [
					'eael_pi_thumbnail' => 'yes',
				],
			]
		);

      $this->add_responsive_control(
			'eael_thumb_spacing',
			[
				'label'      => esc_html__( 'Gap', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'default'    => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__thumbs .swiper-wrapper' => 'gap: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'eael_pi_thumbnail' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_thumb_navigation',
			[
				'label'     => esc_html__( 'Navigation', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
				'condition' => [
					'eael_pi_thumb_navigation' => 'yes',
					'eael_pi_thumbnail'        => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_thumb_navigator_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-images .swiper-button-next' => 'color: {{VALUE}}',
					'{{WRAPPER}} .eael-single-product-images .swiper-button-prev' => 'color: {{VALUE}}',
				],
				'condition' => [
					'eael_pi_thumb_navigation' => 'yes',
					'eael_pi_thumbnail'        => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_thumb_navigator_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'default'    => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__thumbs .swiper-button-next:after, {{WRAPPER}} .eael-single-product-images .product_image_slider__thumbs .swiper-button-prev:after' => 'font-size: {{SIZE}}{{UNIT}}; font-weight: 1000;',
				],
				'condition' => [
					'eael_pi_thumb_navigation' => 'yes',
					'eael_pi_thumbnail'        => 'yes',
				],
			]
		);
        //End thumbnail

		$this->end_controls_section();
		// Style Tab End

	}

	/**
	 * WC Notice
	 *
	 * @return void
	 */
	protected function eael_wc_notice_controls() {
		if ( ! function_exists( 'WC' ) ) {
			$this->start_controls_section( 'eael_global_warning', [
				'label' => __( 'Warning!', 'essential-addons-for-elementor-lite' ),
			] );
			$this->add_control( 'eael_global_warning_text', [
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.', 'essential-addons-for-elementor-lite' ),
				'content_classes' => 'eael-warning',
			] );
			$this->end_controls_section();

			return;
		}
	}

	protected function eael_product_general_control() {
		$this->start_controls_section(
			'eael_content_section',
			[
				'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'eael_pi_image_resolution',
			[
				'label'   => esc_html__( 'Image Resolution', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'full',
				'options' => [
					'full'         => esc_html__( 'Full', 'essential-addons-for-elementor-lite' ),
					'large'        => esc_html__( 'Large', 'essential-addons-for-elementor-lite' ),
					'medium_large' => esc_html__( 'Medium Large', 'essential-addons-for-elementor-lite' ),
					'medium'       => esc_html__( 'Medium', 'essential-addons-for-elementor-lite' ),
					'thumbnail'    => esc_html__( 'Thumbnail', 'essential-addons-for-elementor-lite' ),
					'1536x1536'    => esc_html__( '1536 x 1536', 'essential-addons-for-elementor-lite' ),
					'2048x2048'    => esc_html__( '2048 x 2048', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'eael_pi_effects',
			[
				'label'   => esc_html__( 'Effects', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide'     => esc_html__( 'Slide', 'essential-addons-for-elementor-lite' ),
					'cards'     => esc_html__( 'Cards', 'essential-addons-for-elementor-lite' ),
					'fade'      => esc_html__( 'Fade', 'essential-addons-for-elementor-lite' ),
					'cube'      => esc_html__( 'Cube', 'essential-addons-for-elementor-lite' ),
					'flip'      => esc_html__( 'Flip', 'essential-addons-for-elementor-lite' ),
					'coverflow' => esc_html__( 'Coverflow', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'eael_pi_pagination',
			[
				'label'        => esc_html__( 'Show Pagination', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'eael_pi_navigation',
			[
				'label'        => esc_html__( 'Show Navigation', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'eael_pi_thumbnail',
			[
				'label'        => esc_html__( 'Show Thumbnail', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_pi_select_thumb_items',
			[
				'label'        => esc_html__( 'Thumb Items', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
				'label_on'     => esc_html__( 'Custom', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'condition' => [
					'eael_pi_thumbnail' => 'yes',
				],
			]
		);

		$this->start_popover();
		
		$this->add_control(
			'eael_pi_thumb_desktop_items',
			[
				'label' => esc_html__( 'For Desktop', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 4,
				],
				'condition' => [
					'eael_pi_thumbnail' => 'yes',
					'eael_pi_select_thumb_items' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_pi_thumb_tablet_items',
			[
				'label' => esc_html__( 'For Tablet', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 3,
				],
				'condition' => [
					'eael_pi_thumbnail' => 'yes',
					'eael_pi_select_thumb_items' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_pi_thumb_mobile_items',
			[
				'label' => esc_html__( 'For Mobile', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 2,
				],
				'condition' => [
					'eael_pi_thumbnail' => 'yes',
					'eael_pi_select_thumb_items' => 'yes',
				],
			]
		);

		$this->end_popover();

		$this->add_control(
			'eael_pi_thumb_height_for_mobile',
			[
				'label' => esc_html__( 'For Mobile Device Height', 'essential-addons-for-elementor-lite' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'description' => esc_html__( 'You can control the height of the image from here', 'essential-addons-for-elementor-lite' ),
				'range' => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 40,
				],
				'condition' => [
					'eael_pi_thumbnail' => 'yes',
					'eael_pi_select_thumb_items' => 'yes',
					// 'eael_pi_thumb_position' => ['left', 'right'],
				],
			]
		);

		$this->add_control(
			'eael_pi_thumb_position',
			[
				'label'   => esc_html__( 'Thumb Position', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => [
					'bottom' => esc_html__( 'Bottom', 'essential-addons-for-elementor-lite' ),
					'left'   => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
					'right'  => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'eael_pi_thumbnail' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_pi_thumb_navigation',
			[
				'label'        => esc_html__( 'Thumbnail Navigation', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition' => [
					'eael_pi_thumbnail' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_product_image_loop',
			[
				'label'        => esc_html__( 'Loop', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'true',
			]
		);

		$this->add_control(
			'eael_product_image_autoplay',
			[
				'label'        => esc_html__( 'Autoplay', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'eael_product_image_autoplay_delay',
			[
				'label' => esc_html__( 'Delay', 'essential-addons-for-elementor-lite' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min'  => 300,
						'max'  => 10000,
						'step' => 100,
					],
				],
				'default' => [
					'size' => 2000,
				],
				'condition' => [
					'eael_product_image_autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_pi_mouse_wheel',
			[
				'label'        => esc_html__( 'Mouse Wheel', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);

		$this->add_control(
			'eael_pi_grab_cursor',
			[
				'label'        => esc_html__( 'Grab Cursor', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);

		$this->add_control(
			'eael_pi_keyboard_press',
			[
				'label'        => esc_html__( 'Key Press', 'essential-addons-for-elementor-lite' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		
		$this->end_controls_section();
		
	}

	protected function eael_pi_data_settings( $settings ) {
		$pi_data_settings = [];
		$pi_data_settings['thumbnail'] = ! empty( $settings['eael_pi_thumbnail'] ) ? $settings['eael_pi_thumbnail'] : '';
		$pi_data_settings['desktop'] = ! empty( $settings['eael_pi_thumb_desktop_items'] ) ? $settings['eael_pi_thumb_desktop_items']['size'] : 4;
		$pi_data_settings['tablet'] = ! empty( $settings['eael_pi_thumb_tablet_items'] ) ? $settings['eael_pi_thumb_tablet_items']['size'] : 3;
		$pi_data_settings['mobile'] = ! empty( $settings['eael_pi_thumb_mobile_items'] ) ? $settings['eael_pi_thumb_mobile_items']['size'] : 2;
		$pi_data_settings['height_for_mobile'] = ! empty( $settings['eael_pi_thumb_height_for_mobile'] ) ? $settings['eael_pi_thumb_height_for_mobile']['size'] : 40;
		$pi_data_settings['image_loop'] = ! empty( $settings['eael_product_image_loop'] ) ? $settings['eael_product_image_loop'] : false;
		$pi_data_settings['image_autoplay'] = ! empty( $settings['eael_product_image_autoplay'] ) ? $settings['eael_product_image_autoplay'] : false;
		$pi_data_settings['autoplay_delay'] = ! empty( $settings['eael_product_image_autoplay_delay'] ) ? $settings['eael_product_image_autoplay_delay']['size'] : '';
		$pi_data_settings['image_effect'] = ! empty( $settings['eael_pi_effects'] ) ? $settings['eael_pi_effects'] : '';
		$pi_data_settings['thumb_position'] = ! empty( $settings['eael_pi_thumb_position'] ) ? $settings['eael_pi_thumb_position'] : '';
		$pi_data_settings['mouse_wheel'] = ! empty( $settings['eael_pi_mouse_wheel'] ) ? $settings['eael_pi_mouse_wheel'] : false;
		$pi_data_settings['grab_cursor'] = ! empty( $settings['eael_pi_grab_cursor'] ) ? $settings['eael_pi_grab_cursor'] : false;
		$pi_data_settings['keyboard_press'] = ! empty( $settings['eael_pi_keyboard_press'] ) ? $settings['eael_pi_keyboard_press'] : false;
		$pi_data_settings['thumb_navigation'] = ! empty( $settings['eael_pi_thumb_navigation'] ) ? $settings['eael_pi_thumb_navigation'] : false;
		$pi_data_settings['image_resolution'] = ! empty( $settings['eael_pi_image_resolution'] ) ? $settings['eael_pi_image_resolution'] : 'full';
		$pi_data_settings['pagination'] = ! empty( $settings['eael_pi_pagination'] ) ? $settings['eael_pi_pagination'] : 'false';
		$pi_data_settings['navigation'] = ! empty( $settings['eael_pi_navigation'] ) ? $settings['eael_pi_navigation'] : '';
		$pi_data_settings['sale_flash'] = ! empty( $settings['eael_image_sale_flash'] ) ? $settings['eael_image_sale_flash'] : '';
		$pi_data_settings['zoom_show'] = ! empty( $settings['eael_image_zoom_show'] ) ? $settings['eael_image_zoom_show'] : '';
		return $pi_data_settings;
	}

	protected function eael_product_gallery_html( $settings, $img_links, $product_featured_url ) {
		?>
		<div class="product_image_slider">
				<?php 
				if ( !empty( $img_links ) && is_array( $img_links ) ) {
					$this->render_image_slider( $settings, $img_links );
					$this->render_thumbnail_slider( $settings, $img_links );
				} elseif ( !empty( $product_featured_url ) ) {
					$this->render_image_slider( $settings, $product_featured_url );
				}
				?>
		</div>
		<?php
	}

	protected function render_image_slider( $settings, $img_links ) {
		$image_settings = $this->eael_pi_data_settings( $settings );
		$sliderImages = [
			'effect' => $image_settings['image_effect'],
			'slidesPerView' => 1,
			'loop' => $image_settings['image_loop'],
			'grabCursor' => $image_settings['grab_cursor'],
			'mousewheel' => $image_settings['mouse_wheel'],
			'keyboard'=> [
				'enabled' => $image_settings['keyboard_press'],
			],
		];

		$sliderImages['navigation'] = [
			'nextEl' => ".swiper-button-next",
			'prevEl' => ".swiper-button-prev",
		];

		if ( 'yes' == $image_settings['pagination'] ) {
			$sliderImages['pagination'] = [
				'el'             => ".swiper-pagination",
				'dynamicBullets' => false,
				'clickable'      => true
			];
		}

		if ( 'yes' == $image_settings['image_autoplay'] ) {
			$sliderImages['autoplay'] = [
				'delay' => $image_settings['autoplay_delay'],
				'disableOnInteraction' => false,
			];
		}

		$sliderImagesObj = json_encode( $sliderImages );
		$thumb_position = ['left', 'right'];
		$slider_image_container_width = in_array( $image_settings['thumb_position'], $thumb_position ) ? 'container_width' : 'container_width_full';
		$this->add_render_attribute( 'eael-pi-image', [
			'data-pi_image' => $sliderImagesObj,
			'class'        => 'product_image_slider__container ' . $slider_image_container_width,
		] );
		?>

		<div <?php $this->print_render_attribute_string('eael-pi-image'); ?>>
				<?php
				//Print flash sale on front-end
					if ( 'yes' === $image_settings['sale_flash'] && !( \Elementor\Plugin::$instance->editor->is_edit_mode() || get_post_type( get_the_ID() ) === 'templately_library' ) ) {
						wc_get_template( 'loop/sale-flash.php' );
					}
				?>
				<div class="swiper swiper-slider swiper-container">
					<?php if ( 'yes' === $image_settings['zoom_show'] ) { ?>
					<div class="product_image_slider__trigger">
						<a href="#">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
						</a>
					</div>
					<?php } ?>
					<div class="swiper-wrapper">
						<?php 
							foreach ( $img_links as $img_link ) {
								$this->render_slide( $img_link, 'image_slider__image', $image_settings['image_resolution'] );
							}
						?>
					</div>
					<?php if ( 'yes' == $image_settings['pagination'] ) {
						?>
						<span class="swiper-pagination"></span>
						<?php
					}
					if ( 'yes' == $image_settings['navigation'] ) {
						?>
						<span class="swiper-button-prev"></span>
						<span class="swiper-button-next"></span>
						<?php
					} 
					?>
				</div>
		</div>
		<?php
	}

	protected function render_thumbnail_slider( $settings, $img_links ) {
		$thumb_settings = $this->eael_pi_data_settings( $settings );
		$sliderThumbs = [
			'thumbnail' => $thumb_settings['thumbnail'],
			'mousewheel' => $thumb_settings['mouse_wheel'],
			'keyboard'=> [
				'enabled' => $thumb_settings['keyboard_press'],
			],
			'loop' => $thumb_settings['image_loop'],
		];

		if ( ! empty ( $img_links ) && is_array( $img_links ) && count( $img_links ) > $thumb_settings['desktop'] && 'yes' == $thumb_settings['thumbnail'] ) {
			$slidesDesktopPerView = count( $img_links ) > $thumb_settings['desktop'] ? $thumb_settings['desktop'] : count( $img_links );
			$slidesTabletPerView = count( $img_links ) > $thumb_settings['tablet'] ? $thumb_settings['tablet'] : count( $img_links );
			$slidesMobilePerView = count( $img_links ) > $thumb_settings['mobile'] ? $thumb_settings['mobile'] : count( $img_links );

			$sliderThumbs['slidesPerView'] = $thumb_settings['desktop'];
			$sliderThumbs ['navigation'] = [
				'nextEl' => ".swiper-button-next",
				'prevEl' => ".swiper-button-prev",
			];
			$sliderThumbs['breakpoints'] = [
				320 => [
					'slidesPerView'=>  $slidesMobilePerView,
				],
				768 => [
					'slidesPerView'=>  $slidesTabletPerView,
				],
				1024 => [
					'slidesPerView'=> $slidesDesktopPerView,
				],
				1440 => [
					'slidesPerView'=> $slidesDesktopPerView,
				],
				1920 => [
					'slidesPerView'=> $slidesDesktopPerView,
				],
			];
		} else {
			$sliderThumbs['slidesPerView'] = count( $img_links );
		}

		$thumb_position = ['left', 'right'];
		if ( in_array( $thumb_settings['thumb_position'], $thumb_position ) && 'yes' == $thumb_settings['thumbnail'] ) {
			$slidesDesktopPerView = count( $img_links ) > $thumb_settings['desktop'] ? $thumb_settings['desktop'] : count( $img_links );
			$slidesTabletPerView = count( $img_links ) > $thumb_settings['tablet'] ? $thumb_settings['tablet'] : count( $img_links );
			$slidesMobilePerView = count( $img_links ) > $thumb_settings['mobile'] ? $thumb_settings['mobile'] : count( $img_links );
			$sliderThumbs['breakpoints'] = [
				320 => [
					'direction'=> "vertical",
					'slidesPerView'=>  $slidesMobilePerView,
				],
				768 => [
					'direction'=> "vertical",
					'slidesPerView'=>  $slidesTabletPerView,
				],
				1024 => [
					'direction'=> "vertical",
					'slidesPerView'=> $slidesDesktopPerView,
				],
				1440 => [
					'direction'=> "vertical",
					'slidesPerView'=> $slidesDesktopPerView,
				],
				1920 => [
					'direction'=> "vertical",
					'slidesPerView'=> $slidesDesktopPerView,
				],
			];
		}
		
		if ( 'yes' == $thumb_settings['image_autoplay'] ) {
			$sliderThumbs['autoplay'] = [
				'delay' => $thumb_settings['autoplay_delay'],
				'disableOnInteraction' => false,
			];
		}

		$sliderThumbsObj = json_encode( $sliderThumbs );
		$this->add_render_attribute( 'eael-pi-thumb', [
			'data-pi_thumb' => $sliderThumbsObj,
			'class'         => 'product_image_slider__thumbs',
			'data-for_mobile' => $thumb_settings['height_for_mobile'],
		] );
		?>
		<div <?php $this->print_render_attribute_string( 'eael-pi-thumb' ); ?>>
			<div class="swiper-container">
				<?php if( 'yes' == $thumb_settings['thumbnail'] ) { ?>
					<?php $single_thumb_img = ( count($img_links) > 1 ) ? '' : 'single-thumb-img'; ?>
					<div class="swiper-wrapper <?php esc_attr_e( $single_thumb_img ); ?>">
						<?php 
							foreach ( $img_links as $img_link ) {
								$this->render_slide( $img_link, 'product_image_slider__thumbs__image', 'thumbnail' );
							}
						?>
					</div>
					<?php $print_left_right = in_array( $thumb_settings['thumb_position'], $thumb_position ) ? 'left-right-prev' : ''; ?>
						<?php if ( 'yes' == $thumb_settings['thumb_navigation'] && count( $img_links ) > $thumb_settings['desktop'] ) { ?>
							<span class="swiper-button-prev <?php esc_attr_e( $print_left_right ); ?>"></span>
							<span class="swiper-button-next <?php esc_attr_e( $print_left_right ); ?>"></span>
						<?php } ?>
					<?php } ?>
				</div>
		</div>
		<?php
	}

	protected function render_slide( $img_link, $class, $size ) {
		?>
		<div class="swiper-slide">
			<div class="<?php echo esc_attr( $class ); ?>">
			<?php 
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				?>
				<img src="<?php echo esc_url( $img_link ); ?>" alt="" />
				<?php
			} else {
				echo wp_get_attachment_image( $img_link, $size ); 
			}
			?>
			</div>
		</div>
		<?php
	}

	protected function render() {
		if ( !function_exists( 'WC' ) ) {
			return;
		}
		
		global $product;
		$eael_product_id = apply_filters( 'eael_product_image_product_id', false, $this );
		$product  = Helper::get_product( $eael_product_id );
		$settings = $this->get_settings_for_display();
		$widget_id = $this->get_id();

		$this->add_render_attribute( 'eael_thumb_position', [
			'class' => ['eael-single-product-images', 'eael-pi-thumb-'.$settings['eael_pi_thumb_position'] ],
			'id' => 'slider-container-' . $widget_id, // Unique ID
			'data-id' => $widget_id
			] );
      ?>
      <div <?php $this->print_render_attribute_string( 'eael_thumb_position' ); ?>>
            <?php 
				if( \Elementor\Plugin::$instance->editor->is_edit_mode() || get_post_type( get_the_ID() ) === 'templately_library' ) { 
					$default_image = EAEL_PLUGIN_URL . 'assets/front-end/img/eael-default-placeholder.png';
					$img_links = array_fill( 0, 6, $default_image );

					$this->eael_product_gallery_html( $settings, $img_links, $product_featured_url = [] );

				} else {
					if ( ! $product ) {
						return;
					}
					$product_id           = $product->get_id();
					$product_featured_url = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
					$product_featured_id  = get_post_thumbnail_id( $product_id );
					$product_group        = wc_get_product( $product_id );
					$product_gallery_ids  = array_merge( [ $product_featured_id ], $product_group->get_gallery_image_ids() );
					$this->eael_product_gallery_html( $settings, $product_gallery_ids, $product_featured_url );
				}
			?>
      </div>
      <?php
	}
}
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
		return 'eaicon-product-image templately-widget-icon';
	}

	public function get_categories() {
		return [ 'templately-single' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'images', 'product', 'gallery', 'lightbox', 'ea image', 'ea product', 'ea product image' ];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/woo-product-image/';
	}

	protected function register_controls() {

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
					'.woocommerce {{WRAPPER}} .eael-single-product-images span.onsale' => 'color: {{VALUE}};',
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
					'.woocommerce {{WRAPPER}} .eael-single-product-images span.onsale' => 'background-color: {{VALUE}};',
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

		$this->add_responsive_control(
			'eael_pi_image_height',
			[
				'label'      => esc_html__( 'Adjust Image Height', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'vh', 'px', '%', 'rem', 'em', 'custom' ],
				'range' => [
					'vh' => [
						'min' => 1,
						'max' => 500,
						'step' => 1,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
						'step' => 1,
					],
				],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-images .product_image_slider__container .swiper-slide' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

      $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'eael_image_border',
				'selector'  => '.woocommerce {{WRAPPER}} .eael-single-product-images .product_image_slider__container .swiper-slide-active img',
			]
		);

      $this->add_control(
			'eael_image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
						'.woocommerce {{WRAPPER}} .eael-single-product-images .product_image_slider__container .swiper-slide-active img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
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
					'.woocommerce {{WRAPPER}} .eael-single-product-images .product_image_slider__container .swiper-slide' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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
			]
		);

		$this->add_responsive_control(
			'eael_thumb_height',
			[
				'label'      => esc_html__( 'Adjust Thumb Height', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'vh', 'px', '%', 'rem', 'em', 'custom' ],
				'range' => [
					'px' => [
						'min'  => 1,
						'max'  => 1000,
						'step' => 5,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 35,
				],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-pi-thumb-left .product_image_slider .product_image_slider__thumbs' => 'height: {{SIZE}}{{UNIT}}',
					'.woocommerce {{WRAPPER}} .eael-pi-thumb-right .product_image_slider .product_image_slider__thumbs' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

      $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'eael_thumb_border',
				'selector'  => '.woocommerce {{WRAPPER}} .eael-single-product-images .product_image_slider__thumbs .swiper-slide',
			]
		);

      $this->add_control(
			'eael_thumb_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-images .product_image_slider__thumbs .swiper-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
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
					'.woocommerce {{WRAPPER}} .eael-single-product-images .product_image_slider__thumbs .swiper-wrapper' => 'gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'eael_thumb_navigation',
			[
				'label'     => esc_html__( 'Navigation', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
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
			]
		);

		$this->add_control(
			'eael_thumb_navigator_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__prev' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__next' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'eael_pi_thumb_navigation' => 'yes',
				],
			]
		);

		$this->add_control(
			'eael_thumb_navigator_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'essential-addons-for-elementor-lite' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__prev svg path' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__next svg path' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'eael_pi_thumb_navigation' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'eael_thumb_navigator_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'    => [
					'top'    => 3,
					'right'  => 3,
					'bottom' => 3,
					'left'   => 3,
					'unit'   => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-single-product-images .product_image_slider__next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'eael_pi_thumb_navigation' => 'yes',
				],
			]
		);
        //End thumbnail

		$this->end_controls_section();
		// Style Tab End

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
			'eael_pi_thumb_items',
			[
				'label' => esc_html__( 'Thumb Items', 'essential-addons-for-elementor-lite' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
						'step' => 1,
					],
				],
				'default' => [
					'size' => 4,
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
			]
		);

		$this->add_control(
			'eael_pi_thumb_position',
			[
				'label'   => esc_html__( 'Thumb Position', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => [
					'bottom' => esc_html__( 'Bottm', 'essential-addons-for-elementor-lite' ),
					'left'   => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
					'right'  => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
				],
			]
		);
		
		$this->end_controls_section();
		
	}

	protected function eael_pi_data_settings( $settings ) {
		$pi_data_settings = [];
		$pi_data_settings['thumb_items'] = ! empty( $settings['eael_pi_thumb_items'] ) ? $settings['eael_pi_thumb_items']['size'] : '';
		$pi_data_settings['image_loop'] = ! empty( $settings['eael_product_image_loop'] ) ? $settings['eael_product_image_loop'] : false;
		$pi_data_settings['image_autoplay'] = ! empty( $settings['eael_product_image_autoplay'] ) ? $settings['eael_product_image_autoplay'] : false;
		$pi_data_settings['autoplay_delay'] = ! empty( $settings['eael_product_image_autoplay_delay'] ) ? $settings['eael_product_image_autoplay_delay']['size'] : '';
		$pi_data_settings['image_effect'] = ! empty( $settings['eael_pi_effects'] ) ? $settings['eael_pi_effects'] : '';
		$pi_data_settings['thumb_position'] = ! empty( $settings['eael_pi_thumb_position'] ) ? $settings['eael_pi_thumb_position'] : '';
		$pi_data_settings['mouse_wheel'] = ! empty( $settings['eael_pi_mouse_wheel'] ) ? $settings['eael_pi_mouse_wheel'] : false;
		$pi_data_settings['grab_cursor'] = ! empty( $settings['eael_pi_grab_cursor'] ) ? $settings['eael_pi_grab_cursor'] : false;
		$pi_data_settings['keyboard_press'] = ! empty( $settings['eael_pi_keyboard_press'] ) ? $settings['eael_pi_keyboard_press'] : false;
		$pi_data_settings['thumb_navigation'] = ! empty( $settings['eael_pi_thumb_navigation'] ) ? $settings['eael_pi_thumb_navigation'] : false;
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
				} else {
					echo '<p>No images available for the gallery.</p>';
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
			'navigation' => [
				'nextEl' => ".product_image_slider__next",
				'prevEl' => ".product_image_slider__prev",
			],
			'keyboard'=> [
				'enabled' => $image_settings['keyboard_press'],
			],
		];

		if ( 'yes' == $image_settings['image_autoplay'] ) {
			$sliderImages['autoplay'] = [
				'delay' => $image_settings['autoplay_delay'],
				'disableOnInteraction' => false,
			];
		}

		$sliderImagesObj = json_encode( $sliderImages );
		$this->add_render_attribute( 'eael-pi-image', [
			'data-pi_image' => $sliderImagesObj,
			'class'        => 'product_image_slider__container',
		] );
		?>

		<div <?php $this->print_render_attribute_string('eael-pi-image'); ?>>
				<div class="swiper-container">
					<div class="product_image_slider__trigger">
						<a href="#">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/></svg>
						</a>
					</div>
					<div class="swiper-wrapper">
						<?php 
							foreach ( $img_links as $img_link ) {
								$this->render_slide( $img_link, 'image_slider__image', 'full' );
							}
						?>
					</div>
				</div>
		</div>
		<?php
	}

	protected function render_thumbnail_slider( $settings, $img_links ) {
		$thumb_settings = $this->eael_pi_data_settings( $settings );
		$sliderThumbs = [
			// 'spaceBetween' => 2,
			'mousewheel' => $thumb_settings['mouse_wheel'],
			'keyboard'=> [
				'enabled' => $thumb_settings['keyboard_press'],
			],
			'loop' => $thumb_settings['image_loop'],
		];

		if ( ! empty ( $img_links ) && is_array( $img_links ) && count( $img_links ) > 3 ) {
			$sliderThumbs['slidesPerView'] = $thumb_settings['thumb_items'];
			$sliderThumbs ['navigation'] = [
				'nextEl' => ".product_image_slider__next",
				'prevEl' => ".product_image_slider__prev",
			];
		} else {
			$sliderThumbs['slidesPerView'] = count( $img_links );
		}

		$thumb_position = ['left', 'right'];
		if ( in_array( $thumb_settings['thumb_position'], $thumb_position ) ) {
			$sliderThumbs['breakpoints'] = [
				480 => [
					'direction'=> "vertical",
					'slidesPerView'=> 3,
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
		] );
		?>
		<div <?php $this->print_render_attribute_string( 'eael-pi-thumb' ); ?>>

			<?php if ( ! empty ( $img_links ) && is_array( $img_links ) && count( $img_links ) > 3 && 'yes' == $thumb_settings['thumb_navigation'] ) { ?>
				<div class="product_image_slider__prev">
					<?php 
						if ( in_array( $thumb_settings['thumb_position'], $thumb_position ) ) {
							?>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM377 271c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-87-87-87 87c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9L239 167c9.4-9.4 24.6-9.4 33.9 0L377 271z"/></svg>
							<?php
						} else {
							?>
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M512 256A256 256 0 1 0 0 256a256 256 0 1 0 512 0zM271 135c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-87 87 87 87c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0L167 273c-9.4-9.4-9.4-24.6 0-33.9L271 135z"/></svg>
							<?php
						}
					?>
				</div>
			<?php } ?>
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php 
							foreach ( $img_links as $img_link ) {
								$this->render_slide( $img_link, 'product_image_slider__thumbs__image', 'thumbnail' );
							}
						?>
					</div>
				</div>
				<?php if ( ! empty ( $img_links ) && is_array( $img_links ) && count( $img_links ) > 3 && 'yes' == $thumb_settings['thumb_navigation'] ) { ?>
				<div class="product_image_slider__next">
					<?php if ( in_array( $thumb_settings['thumb_position'], $thumb_position ) ) {
						?>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM135 241c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l87 87 87-87c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9L273 345c-9.4 9.4-24.6 9.4-33.9 0L135 241z"/></svg>
						<?php
					} else {
						?>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM241 377c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l87-87-87-87c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L345 239c9.4 9.4 9.4 24.6 0 33.9L241 377z"/></svg>
						<?php
					} ?>
				</div>
				<?php } ?>
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
		global $product;
		$product = Helper::get_product();
		if ( ! $product ) {
         return;
		}

		$settings             = $this->get_settings_for_display();
		$product_id           = $product->get_id();
		$product_featured_url = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
		$product_featured_id  = get_post_thumbnail_id( $product_id );
		$product_group        = wc_get_product( $product_id );
		$product_gallery_ids  = array_merge( [ $product_featured_id ], $product_group->get_gallery_image_ids() );

		$this->add_render_attribute( 'eael_thumb_position', [
			'class' => ['eael-single-product-images', 'eael-pi-thumb-'.$settings['eael_pi_thumb_position'] ]
			] );
      ?>
      <div <?php $this->print_render_attribute_string( 'eael_thumb_position' ); ?>>
            <?php 
				if( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { 
					$img_links = [
						EAEL_PLUGIN_URL . 'assets/front-end/img/flexia-preview.jpg',
						EAEL_PLUGIN_URL . 'assets/front-end/img/flexia-preview.jpg',
						EAEL_PLUGIN_URL . 'assets/front-end/img/flexia-preview.jpg',
						EAEL_PLUGIN_URL . 'assets/front-end/img/flexia-preview.jpg',
						EAEL_PLUGIN_URL . 'assets/front-end/img/flexia-preview.jpg',
						EAEL_PLUGIN_URL . 'assets/front-end/img/flexia-preview.jpg',
					];

					if ( 'yes' === $settings['eael_image_sale_flash'] ) {
						wc_get_template( 'loop/sale-flash.php' );
					}
					$this->eael_product_gallery_html( $settings, $img_links, $product_featured_url );

				} else {
					if ( 'yes' === $settings['eael_image_sale_flash'] ) {
						wc_get_template( 'loop/sale-flash.php' );
					}
					// wc_get_template( 'single-product/product-image.php' );
					$this->eael_product_gallery_html( $settings, $product_gallery_ids, $product_featured_url );
				}
			?>
      </div>
      <?php
	}

}
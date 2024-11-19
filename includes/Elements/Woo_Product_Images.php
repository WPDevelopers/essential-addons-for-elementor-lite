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
		return [ 'woocommerce', 'shop', 'store', 'images', 'product' ];
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

      $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'eael_image_border',
				'selector'  => '.woocommerce {{WRAPPER}} .eael-single-product-images .flex-viewport',
				'separator' => 'before',
			]
		);

      $this->add_responsive_control(
			'eael_image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
						'.woocommerce {{WRAPPER}} .eael-single-product-images .flex-viewport' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

      $this->add_control(
			'eael_image_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-images .flex-viewport:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
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

      $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'      => 'eael_thumb_border',
				'selector'  => '.woocommerce {{WRAPPER}} .eael-single-product-images .flex-control-thumbs img',
			]
		);

      $this->add_responsive_control(
			'eael_thumb_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-images .flex-control-thumbs img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

      $this->add_control(
			'eael_thumb_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'rem', 'em', 'custom' ],
				'selectors'  => [
					'.woocommerce {{WRAPPER}} .eael-single-product-images .flex-control-thumbs li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
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
					'size' => 5,
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
			'eael_product_image_mouse_wheel',
			[
				'label'        => esc_html__( 'Mouse Wheel', 'essential-addons-for-elementor-lite' ),
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
			'spaceBetween' => 32,
			'loop' => $image_settings['image_loop'],
			'grabCursor' => true,
			'mousewheel' => true,
			'navigation' => [
				'nextEl' => ".product_image_slider__next",
				'prevEl' => ".product_image_slider__prev",
			],
			'keyboard'=> [
				'enabled' => true,
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
								$this->render_slide( $img_link, 'image_slider__image' );
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
			'slidesPerView' => $thumb_settings['thumb_items'],
			'spaceBetween' => 5,
			'navigation' => [
				'nextEl' => ".product_image_slider__next",
				'prevEl' => ".product_image_slider__prev",
			],
			'keyboard'=> [
				'enabled' => true,
			],
			'loop' => $thumb_settings['image_loop'],
		];

		$thumb_position = ['left', 'right'];
		if ( in_array( $thumb_settings['thumb_position'], $thumb_position ) ) {
			$sliderThumbs['breakpoints'] = [
				480 => [
					'direction'=> "vertical",
					'slidesPerView'=> 3,
				],
			];
			$this->add_render_attribute( '_wrapper', ['class' => 'eael-pi-thumb-'.$thumb_settings['thumb_position']] );
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
			<div class="product_image_slider__prev">
				<?php if ( in_array( $thumb_settings['thumb_position'], $thumb_position ) ) {
					?>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM377 271c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-87-87-87 87c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9L239 167c9.4-9.4 24.6-9.4 33.9 0L377 271z"/></svg>
					<?php
				} else {
					?>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M512 256A256 256 0 1 0 0 256a256 256 0 1 0 512 0zM271 135c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-87 87 87 87c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0L167 273c-9.4-9.4-9.4-24.6 0-33.9L271 135z"/></svg>
					<?php
				} ?>
			</div>
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php 
							foreach ( $img_links as $img_link ) {
								$this->render_slide( $img_link, 'product_image_slider__thumbs__image' );
							}
						?>
					</div>
				</div>
				<div class="product_image_slider__next">
					<?php if ( in_array( $thumb_settings['thumb_position'], $thumb_position ) ) {
						?>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM135 241c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l87 87 87-87c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9L273 345c-9.4 9.4-24.6 9.4-33.9 0L135 241z"/></svg>
						<?php
					} else {
						?>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM241 377c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l87-87-87-87c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0L345 239c9.4 9.4 9.4 24.6 0 33.9L241 377z"/></svg>
						<?php
					} ?>
				</div>
		</div>
		<?php
	}

	protected function render_slide( $img_link, $class ) {
		if ( filter_var( $img_link, FILTER_VALIDATE_URL ) ) {
			$image_url = $img_link;
		} else {
			$image_url = wp_get_attachment_url( $img_link ); // Fetch URL if it's an attachment ID
		}
		?>
		<div class="swiper-slide">
				<div class="<?php echo esc_attr( $class ); ?>">
					<img src="<?php echo esc_url( $image_url ); ?>" alt="" />
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
		$product_group        = wc_get_product( $product_id );
		$product_gallery_ids  = $product_group->get_gallery_image_ids();


      ?>
      <div class="eael-single-product-images">
            <?php 
				if( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { 
					$img_links = [
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

					$this->add_render_attribute("eael_pi_loop", 'data-loop', 'yes');

					$this->eael_product_gallery_html( $settings, $product_gallery_ids, $product_featured_url );
				}
			?>
      </div>
      <?php
	}

}
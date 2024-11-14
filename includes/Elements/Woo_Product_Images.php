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
			'eael_slide_effects',
			[
				'label'   => esc_html__( 'Effects', 'essential-addons-for-elementor-lite' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide'     => esc_html__( 'Slide', 'essential-addons-for-elementor-lite' ),
					'fade'      => esc_html__( 'Fade', 'essential-addons-for-elementor-lite' ),
					'cube'      => esc_html__( 'Cube', 'essential-addons-for-elementor-lite' ),
					'flip'      => esc_html__( 'Flip', 'essential-addons-for-elementor-lite' ),
					'coverflow' => esc_html__( 'Coverflow', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'eael_thumb_items',
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
		
		$this->end_controls_section();
		
	}

	protected function eael_product_gallery_html( $img_links ) {
		?>
		<div class="product_image_slider">
			<?php $this->render_image_slider( $img_links ); ?>
			<?php $this->render_thumbnail_slider( $img_links ); ?>
		</div>
		<?php
	}

	protected function render_image_slider( $img_links ) {
		?>
		<div class="product_image_slider__container">
				<div class="swiper-container">
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

	protected function render_thumbnail_slider( $img_links ) {
		?>
		<div class="product_image_slider__thumbs">
			<div class="product_image_slider__prev">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M512 256A256 256 0 1 0 0 256a256 256 0 1 0 512 0zM116.7 244.7l112-112c4.6-4.6 11.5-5.9 17.4-3.5s9.9 8.3 9.9 14.8l0 64 96 0c17.7 0 32 14.3 32 32l0 32c0 17.7-14.3 32-32 32l-96 0 0 64c0 6.5-3.9 12.3-9.9 14.8s-12.9 1.1-17.4-3.5l-112-112c-6.2-6.2-6.2-16.4 0-22.6z"/></svg>
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
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zm395.3 11.3l-112 112c-4.6 4.6-11.5 5.9-17.4 3.5s-9.9-8.3-9.9-14.8l0-64-96 0c-17.7 0-32-14.3-32-32l0-32c0-17.7 14.3-32 32-32l96 0 0-64c0-6.5 3.9-12.3 9.9-14.8s12.9-1.1 17.4 3.5l112 112c6.2 6.2 6.2 16.4 0 22.6z"/></svg>
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
					$this->eael_product_gallery_html( $img_links );
				} else {
					if ( 'yes' === $settings['eael_image_sale_flash'] ) {
						wc_get_template( 'loop/sale-flash.php' );
					}
					// wc_get_template( 'single-product/product-image.php' );

					$this->eael_product_gallery_html( $product_gallery_ids );
				}
			?>
      </div>
      <?php
	}

}
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

	protected function render() {
        global $product;

        $product = Helper::get_product();

        if ( ! $product ) {
            return;
        }

        $settings = $this->get_settings_for_display();
        ?>
        <div class="eael-single-product-images">
            <?php
               //  if ( 'yes' === $settings['eael_image_sale_flash'] ) {
               //      wc_get_template( 'loop/sale-flash.php' );
               //  } 
               //  wc_get_template( 'single-product/product-image.php' ); 
            ?>

            <?php 
				// if( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { 
					?>
					<section class="slider">
						<div class="slider__flex">
							<div class="slider__col">
								<div class="slider__prev">Prev</div>
								<div class="slider__thumbs">
									<div class="swiper-container">
										<div class="swiper-wrapper">
											<div class="swiper-slide">
											<div class="slider__image"><img src="https://picsum.photos/1920/1080" alt=""/></div>
											</div>
											<div class="swiper-slide">
											<div class="slider__image"><img src="https://picsum.photos/1920/1081" alt=""/></div>
											</div>
											<div class="swiper-slide">
											<div class="slider__image"><img src="https://picsum.photos/1920/1082" alt=""/></div>
											</div>
											<div class="swiper-slide">
											<div class="slider__image"><img src="https://picsum.photos/1920/1083" alt=""/></div>
											</div>
											<div class="swiper-slide">
											<div class="slider__image"><img src="https://picsum.photos/1920/1084" alt=""/></div>
											</div>
										</div>
									</div>
								</div>
								<div class="slider__next">Next</div>

							</div>

							<div class="slider__images">
								<div class="swiper-container">
								<div class="swiper-wrapper">
									<div class="swiper-slide">
										<div class="slider__image"><img src="https://picsum.photos/1920/1080" alt=""/></div>
									</div>
									<div class="swiper-slide">
										<div class="slider__image"><img src="https://picsum.photos/1920/1081" alt=""/></div>
									</div>
									<div class="swiper-slide">
										<div class="slider__image"><img src="https://picsum.photos/1920/1082" alt=""/></div>
									</div>
									<div class="swiper-slide">
										<div class="slider__image"><img src="https://picsum.photos/1920/1083" alt=""/></div>
									</div>
									<div class="swiper-slide">
										<div class="slider__image"><img src="https://picsum.photos/1920/1084" alt=""/></div>
									</div>
								</div>
								</div>
							</div>

						</div>
						</section>
					<?php
				?>
            <script>
				// 	document.addEventListener('DOMContentLoaded', function () {
					
				// });

				jQuery( '.woocommerce-product-gallery' ).each( function() {
					// jQuery( this ).wc_product_gallery();
					

				} );
			</script>
            <?php 
			// }
			?>
        </div>
        <?php
	}
}
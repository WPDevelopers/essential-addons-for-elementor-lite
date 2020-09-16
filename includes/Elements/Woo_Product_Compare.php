<?php

namespace Essential_Addons_Elementor\Elements;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Widget_Base;
use WC_Product;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Class Woo_Product_Compare
 * @package namespace Essential_Addons_Elementor\Pro\Elements;
 */
class Woo_Product_Compare extends Widget_Base {
	protected $products_list;
	protected $remove_action = 'eael-wcpc-remove-product';

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return 'eael-woo-product-compare';
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return esc_html__( 'Woo Product Compare', 'essential-addons-for-elementor-lite' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eicon-woocommerce';
	}

	/**
	 * @inheritDoc
	 */
	public function get_keywords() {
		return [
			'woocommerce product compare',
			'woocommerce product comparison',
			'product compare',
			'product comparison',
			'products compare',
			'products comparison',
			'wc',
			'woocommerce',
			'products',
			'compare',
			'comparison',
			'ea',
			'essential addons',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/woo-product-comparison/';
	}

	/**
	 * @inheritDoc
	 */
	public function get_categories() {
		return [ 'essential-addons-for-elementor-lite' ];
	}

	/**
	 * Get an array of field types.
	 * @return array
	 */
	protected function get_field_types() {
		return apply_filters( 'eael/wcpc/default-fields', [
			'image'       => __( 'Image', 'essential-addons-for-elementor-lite' ),
			'title'       => __( 'Title', 'essential-addons-for-elementor-lite' ),
			'price'       => __( 'Price', 'essential-addons-for-elementor-lite' ),
			'add-to-cart' => __( 'Add to cart', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Description', 'essential-addons-for-elementor-lite' ),
			'sku'         => __( 'SKU', 'essential-addons-for-elementor-lite' ),
			'stock'       => __( 'Availability', 'essential-addons-for-elementor-lite' ),
			'weight'      => __( 'weight', 'essential-addons-for-elementor-lite' ),
			'dimension'   => __( 'Dimension', 'essential-addons-for-elementor-lite' ),
			'pa_color'    => __( 'Color', 'essential-addons-for-elementor-lite' ),
			'pa_size'     => __( 'Size', 'essential-addons-for-elementor-lite' ),
		] );
	}

	protected function get_layouts() {
		return apply_filters( 'eael/wcpc/default-layouts', [
			'layout1'       => __( 'Layout 1', 'essential-addons-for-elementor-lite' ),
			'layout2'       => __( 'Layout 2', 'essential-addons-for-elementor-lite' ),
			'layout3'       => __( 'Layout 3', 'essential-addons-for-elementor-lite' ),
			'layout4'       => __( 'Layout 4', 'essential-addons-for-elementor-lite' ),
			'layout5'       => __( 'Layout 5', 'essential-addons-for-elementor-lite' ),
			'layout6'       => __( 'Layout 6', 'essential-addons-for-elementor-lite' ),
		] );
	}

	/**
	 * Get default fields value for the repeater's default value
	 */
	protected function get_default_rf_fields() {
		return apply_filters( 'eael/wcpc/default-rf-fields', [
			[
				'field_type'  => 'image',
				'field_label' => __( 'Image', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'title',
				'field_label' => __( 'Title', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'price',
				'field_label' => __( 'Price', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'description',
				'field_label' => __( 'Description', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'add-to-cart',
				'field_label' => __( 'Add to cart', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'sku',
				'field_label' => __( 'SKU', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'stock',
				'field_label' => __( 'Availability', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'weight',
				'field_label' => __( 'Weight', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'dimension',
				'field_label' => __( 'Dimension', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'pa_color',
				'field_label' => __( 'Color', 'essential-addons-for-elementor-lite' ),
			],
			[
				'field_type'  => 'pa_size',
				'field_label' => __( 'Size', 'essential-addons-for-elementor-lite' ),
			],
		] );
	}

	/**
	 * @inheritDoc
	 */
	protected function _register_controls() {
		if ( ! function_exists( 'WC' ) ) {
			$this->start_controls_section( 'eael_global_warning', [
				'label' => __( 'Warning!', 'essential-addons-for-elementor-lite' ),
			] );
			$this->add_control( 'eael_global_warning_text', [
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( '<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.', 'essential-addons-for-elementor-lite' ),
				'content_classes' => 'eael-warning',
			] );
			$this->end_controls_section();

			return;
		}

		/*----Content Tab----*/
		do_action( 'eael/wcpc/before-content-controls', $this );
		$this->init_content_content_controls();
		$this->init_content_table_settings_controls();
		do_action( 'eael/wcpc/after-content-controls', $this );

		/*----Style Tab----*/
		do_action( 'eael/wcpc/before-style-controls', $this );
		$this->init_style_content_controls();
		$this->init_style_table_controls();
		do_action( 'eael/wcpc/after-style-controls', $this );

	}

	public function init_content_content_controls() {
		$this->start_controls_section( 'section_content_content', [
			'label' => __( 'Content', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( "product_ids", [
			'label'       => __( 'Product IDs', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Enter Product IDs separated by a comma', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => true,
			'placeholder' => __( 'Eg. 123, 456 etc.', 'essential-addons-for-elementor-lite' ),
		] );
		$this->end_controls_section();
	}

	public function init_content_table_settings_controls() {

		$this->start_controls_section( 'section_content_table', [
			'label' => __( 'Table Settings', 'essential-addons-for-elementor-lite' ),
		] );
		$this->add_control( "table_title", [
			'label'       => __( 'Table Title', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => __( 'Compare Products', 'essential-addons-for-elementor-lite' ),
			'placeholder' => __( 'Compare Products', 'essential-addons-for-elementor-lite' ),
		] );
		$repeater = new Repeater();
		$repeater->add_control( 'field_type', [
			'label'   => __( 'Type', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $this->get_field_types(),
			'default' => 'title',
		] );

		$repeater->add_control( 'field_label', [
			'label'   => __( 'Label', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
		] );

		$this->add_control( 'fields', [
			'label'       => __( 'Fields to show', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Select the fields to show in the comparison table', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => apply_filters( 'eael/wcpc/rf-fields', $repeater->get_controls() ),
			'default'     => $this->get_default_rf_fields(),
			'title_field' => '{{{ field_label }}}',
		] );

		$this->add_control( 'repeat_price', [
			'label'       => __( 'Repeat "Price" field', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Repeat the "Price" field at the end of the table', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SWITCHER,
			'default'     => 'yes',
		] );
		$this->add_control( 'repeat_add_to_cart', [
			'label'       => __( 'Repeat "Add to cart" field', 'essential-addons-for-elementor-lite' ),
			'description' => __( 'Repeat the "Add to cart" field at the end of the table', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SWITCHER,
		] );
		$this->end_controls_section();
	}

	public function init_style_content_controls() {
		$this->start_controls_section( 'section_style_general', [
			'label' => __( 'General', 'essential-addons-for-elementor-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$container_class = '{{WRAPPER}} .eael-wcpc-wrapper';
		$this->add_responsive_control( "eael_container_width", [
			'label'      => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [
				'px',
				'rem',
				'%',
			],
			'range'      => [
				'px'  => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 5,
				],
				'rem' => [
					'min'  => 0,
					'max'  => 20,
					'step' => .5,
				],
				'%'   => [
					'min' => 0,
					'max' => 100,
				],
			],
			'desktop'    => [
				'unit' => '%',
				'size' => 100,
			],
			'selectors'  => [
				$container_class => 'width: {{SIZE}}{{UNIT}};',
			],

		] );

		$this->add_responsive_control( "eael_container_margin", [
			'label'      => __( 'Margin', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$container_class => $this->apply_dim( 'margin' ),
			],
		] );
		$this->add_responsive_control( "eael_container_padding", [
			'label'      => __( 'Padding', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'em',
				'%',
			],
			'selectors'  => [
				$container_class => $this->apply_dim( 'padding' ),
			],
		] );
		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => "eael_container_border",
			'selector' => $container_class,
		] );
		$this->add_control( "eael_container_border_radius", [
			'label'      => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [
				'px',
				'%',
			],
			'selectors'  => [
				$container_class => $this->apply_dim( 'border-radius' ),
			],
		] );
		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => "eael_container_bg_color",
			'label'    => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
			'types'    => [
				'classic',
				'gradient',
			],
			'selector' => $container_class,
		] );
		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'label'    => __( 'Container Box Shadow', 'essential-addons-for-elementor-lite' ),
			'name'     => 'eael_container_shadow',
			'selector' => $container_class,
			'exclude'  => [
				'box_shadow_position',
			],
		] );
		$this->end_controls_section();
	}

	public function init_style_table_controls() {
		$this->start_controls_section( 'section_style_table', [
			'label' => __( 'Table Style', 'essential-addons-for-elementor-lite' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->add_control( 'layout', [
			'label'   => __( 'Layout', 'essential-addons-for-elementor-lite' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $this->get_layouts(),
			'default' => 'layout1',
		] );
		$this->end_controls_section();
	}

	protected function render() {
		if ( ! function_exists( 'WC' ) ) {
			return;
		}
		$product_ids = $this->get_settings_for_display( 'product_ids' );
		$product_ids = ! empty( $product_ids ) ? array_filter( array_map( 'trim', explode( ',', $product_ids ) ), function ( $id ) {
			return ( ! empty( $id ) && is_numeric( $id ) );
		} ) : [];
		$products    = $this->get_products_list( $product_ids );
		$fields      = $this->fields();
		$title       = $this->get_settings_for_display( 'table_title' );
		?>
		<?php do_action( 'eael/wcpc/before_content_wrapper' ); ?>
        <div class="eael-wcpc-wrapper woocommerce">
			<?php do_action( 'eael/wcpc/before_main_table' ); ?>
            <table class="eael-wcpc-table table-responsive">
                <tbody>
				<?php if ( empty( $products ) ) { ?>

                    <tr class="no-products">
                        <td><?php esc_html_e( 'No products added to compare.', 'essential-addons-for-elementor-lite' ) ?></td>
                    </tr>

				<?php } else { ?>
					<?php foreach ( $fields as $field => $name ) : ?>

                        <tr class="<?php echo esc_attr( $field ); ?>">

                            <th class="thead">
								<?php if ( ! empty( $title ) &&  $field === 'image' ) {
									printf( "<h1 class='wcpc-title'>%s</h1>", esc_html( $title ) );
								}else{
									echo esc_html( $name );
								}?>
                            </th>

							<?php
							$index = 0;
							foreach ( $products as $product_id => $product ) :
								$product_class = ( $index % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product_id; ?>
                                <td class="<?php echo esc_attr( $product_class ); ?>"><?php
									echo ! empty( $product->fields[ $field ] ) ? $product->fields[ $field ] : '&nbsp;';
									?>
                                </td>
								<?php
								++ $index;
							endforeach; ?>

                        </tr>

					<?php endforeach; ?>

					<?php if ( $this->get_settings_for_display( 'repeat_price' ) == 'yes' && isset( $fields['price'] ) ) : ?>
                        <tr class="price repeated">
                            <th><?php echo wp_kses_post( $fields['price'] ) ?></th>

							<?php
							$index = 0;
							foreach ( $products as $product_id => $product ) :
								$product_class = ( $index % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product_id ?>
                                <td class="<?php echo esc_attr( $product_class ) ?>"><?php echo wp_kses_post( $product->fields['price'] ); ?></td>
								<?php
								++ $index;
							endforeach; ?>

                        </tr>
					<?php endif; ?>

					<?php if ( $this->get_settings_for_display( 'repeat_add_to_cart' ) == 'yes' && isset( $fields['add-to-cart'] ) ) : ?>
                        <tr class="add-to-cart repeated">
                            <th><?php echo wp_kses_post( $fields['add-to-cart'] ); ?></th>

							<?php
							$index = 0;
							foreach ( $products as $product_id => $product ) :
								$product_class = ( $index % 2 == 0 ? 'odd' : 'even' ) . ' product_' . $product_id ?>
                                <td class="<?php echo esc_attr( $product_class ); ?>">
									<?php woocommerce_template_loop_add_to_cart(); ?>
                                </td>
								<?php
								++ $index;
							endforeach; ?>

                        </tr>
					<?php endif; ?>

				<?php } ?>
                </tbody>
            </table>
			<?php do_action( 'eael/wcpc/after_main_table' ); ?>
        </div>
		<?php do_action( 'eael/wcpc/after_content_wrapper' ); ?>
		<?php
	}

	/**
	 * It will apply value like Elementor's dimension control to a property and return it.
	 *
	 * @param string $css_property CSS property name
	 *
	 * @return string
	 */
	public function apply_dim( $css_property ) {
		return "{$css_property}: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};";
	}

	/**
	 * Return the array with all products and all attributes values
	 *
	 * @param array $products ids of wc product
	 *
	 * @return array The complete list of products with all attributes value
	 */
	public function get_products_list( $products = [] ) {
		$products_list = [];
		empty( $products ) && $products = $this->products_list;
		$products = apply_filters( 'eael/wcpc/products_ids', $products );
		$fields   = $this->fields( $products );
		global $product;
		foreach ( $products as $product_id ) {
			/** @type WC_Product $product WooCommerce Product */
			$product = wc_get_product( $product_id );
			if ( ! $product ) {
				continue;
			}

			$product->fields = [];

			// custom attributes
			foreach ( $fields as $field => $name ) {
				switch ( $field ) {
					case 'title':
						$product->fields[ $field ] = $product->get_title();
						break;
					case 'price':
						$product->fields[ $field ] = $product->get_price_html();
						break;
					case 'add-to-cart':
						ob_start();
						woocommerce_template_loop_add_to_cart();
						$product->fields[ $field ] = ob_get_clean();
						break;
					case 'image':
						$product->fields[ $field ] = $product->get_image();
						break;
					case 'description':
						$description               = apply_filters( 'woocommerce_short_description', $product->get_short_description() ? $product->get_short_description() : wc_trim_string( $product->get_description(), 400 ) );
						$product->fields[ $field ] = apply_filters( 'eael/wcpc/woocommerce_short_description', $description );
						break;
					case 'stock':
						$availability = $product->get_availability();
						if ( empty( $availability['availability'] ) ) {
							$availability['availability'] = __( 'In stock', 'essential-addons-for-elementor-lite' );
						}
						$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $availability['availability'] ) );
						break;
					case 'sku':
						$sku = $product->get_sku();
						! $sku && $sku = '-';
						$product->fields[ $field ] = $sku;
						break;
					case 'weight':
						if ( $weight = $product->get_weight() ) {
							$weight = wc_format_localized_decimal( $weight ) . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) );
						} else {
							$weight = '-';
						}
						$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $weight ) );
						break;
					case 'dimensions':
						$dimensions = function_exists( 'wc_format_dimensions' ) ? wc_format_dimensions( $product->get_dimensions( false ) ) : $product->get_dimensions();
						! $dimensions && $dimensions = '-';
						$product->fields[ $field ] = sprintf( '<span>%s</span>', esc_html( $dimensions ) );
						break;
					default:
						if ( taxonomy_exists( $field ) ) {
							$product->fields[ $field ] = [];
							$terms                     = get_the_terms( $product_id, $field );
							if ( ! empty( $terms ) && is_array( $terms ) ) {
								foreach ( $terms as $term ) {
									$term                        = sanitize_term( $term, $field );
									$product->fields[ $field ][] = $term->name;
								}
							}
							$product->fields[ $field ] = implode( ', ', $product->fields[ $field ] );
						} else {
							do_action( 'eael/wcpc/compare_field_' . $field, [
								$product,
								&$product->fields,
							] );
						}
						break;
				}
			}

			$products_list[ $product_id ] = $product;
		}

		return apply_filters( 'eael/wcpc/products_list', $products_list );
	}

	/**
	 * Get the fields to show in the comparison table
	 *
	 * @param array $products Optional array of products ids
	 *
	 * @return array $fields it returns an array of fields to show on the comparison table
	 */
	public function fields( $products = [] ) {
		$fields = $this->get_settings_for_display( 'fields' );
		if ( empty( $fields ) || ! is_array( $fields ) ) {
			return apply_filters( 'eael/wcpc/products_fields_none', [] );
		}

		$df             = $this->get_field_types();
		$fields_to_show = [];
		foreach ( $fields as $field ) {
			if ( isset( $df[ $field['field_type'] ] ) ) {
				$fields_to_show[ $field['field_type'] ] = $field['field_label'];
			} else {
				if ( taxonomy_exists( $field['field_type'] ) ) {
					$fields_to_show[ $field['field_type'] ] = wc_attribute_label( $field['field_type'] );
				}
			}
		}

		return apply_filters( 'eael/wcpc/products_fields_to_show', $fields_to_show, $products );
	}

	public function get_product_remove_url( $product_id ) {
		$url_args = [
			'action' => $this->remove_action,
			'id'     => $product_id,
		];

		return apply_filters( 'eael/wcpc/get_product_remove_url', esc_url_raw( add_query_arg( $url_args, site_url() ) ), $this->remove_action );
	}


}

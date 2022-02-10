<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;
use \Elementor\Icons_Manager;
use Essential_Addons_Elementor\Traits\Helper;

class Woo_Checkout extends Widget_Base {
	
	use \Essential_Addons_Elementor\Template\Woocommerce\Checkout\Woo_Checkout_Helper;
	use Helper;

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		$is_type_instance = $this->is_type_instance();

		if ( ! $is_type_instance && null === $args ) {
			throw new \Exception( '`$args` argument is required when initializing a full widget instance.' );
		}

		if ( $is_type_instance && class_exists('woocommerce')) {

			if ( is_null( WC()->cart ) ) {
				include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
				include_once WC_ABSPATH . 'includes/class-wc-cart.php';
				wc_load_cart();
			}

			add_filter('body_class' , [$this, 'add_checkout_body_class']);
      $this->eael_woocheckout_recurring();
		}
	}

	public function get_name() {
		return 'eael-woo-checkout';
	}

	public function get_title() {
		return esc_html__( 'Woo Checkout', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-woo-checkout';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 3.5.2
	 * @access public
	 *
	 */
	public function get_keywords() {
		return [ 'ea woo checkout', 'woocommerce', 'checkout', 'woocommerce checkout', 'woocommerce split checkout', 'split checkout', 'multi steps checkout', 'ea', 'essential addons' ];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/woo-checkout/';
	}

	protected function register_controls() {
	    if( !class_exists( 'woocommerce' ) ) {
		    $this->start_controls_section(
			    'eael_global_warning',
			    [
				    'label' => __('Warning!', 'essential-addons-for-elementor-lite'),
			    ]
		    );

		    $this->add_control(
			    'eael_global_warning_text',
			    [
				    'type'            => Controls_Manager::RAW_HTML,
				    'raw'             => __('<strong>WooCommerce</strong> is not installed/activated on your site. Please install and activate <a href="plugin-install.php?s=woocommerce&tab=search&type=term" target="_blank">WooCommerce</a> first.',
					    'essential-addons-for-elementor-lite'),
				    'content_classes' => 'eael-warning',
			    ]
		    );

		    $this->end_controls_section();
		    return;
        }


		/**
		 * General Settings
		 */
		$this->start_controls_section(
			'ea_section_woo_checkout_general_settings',
			[
				'label' => esc_html__( 'General Settings', 'essential-addons-for-elementor-lite' ),
			]
		);
		$this->add_control(
			'ea_woo_checkout_layout',
			[
				'label' => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'label_block' => false,
				'options' => apply_filters('eael/woo-checkout/layout', [
					'default' => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
					'multi-steps' => esc_html__( 'Multi Steps (Pro)', 'essential-addons-for-elementor-lite' ),
					'split' => esc_html__( 'Split (Pro)', 'essential-addons-for-elementor-lite' ),
				]),
			]
		);

		if (!apply_filters('eael/pro_enabled', false)) {
			$this->add_control(
				'eael_woo_checkout_pro_enable_warning',
				[
					'label' => sprintf( '<a target="_blank" href="https://wpdeveloper.com/upgrade/ea-pro">%s</a>', esc_html__('Only Available in Pro Version!', 'essential-addons-for-elementor-lite')),
					'type' => Controls_Manager::RAW_HTML,
					'condition' => [
						'ea_woo_checkout_layout' => ['multi-steps', 'split'],
					],
				]
			);
		}

		do_action('eael_woo_checkout_pro_enabled_general_settings', $this);

		$this->end_controls_section();

		/**
		 * Order Details Settings
		 */
		$this->start_controls_section(
			'ea_section_woo_checkout_order_review_settings',
			[
				'label' => esc_html__( 'Order Details', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_woo_checkout_order_details_title',
			[
				'label' => __( 'Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Your Order', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		// Table Header
		$this->add_control(
			'ea_woo_checkout_table_header_text',
			[
				'label' => esc_html__( 'Change Labels', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'ea_woo_checkout_table_product_text',
			[
				'label' => __( 'Product Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => __( 'Product', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'ea_woo_checkout_table_header_text' => 'yes',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_table_quantity_text',
			[
				'label' => __( 'Quantity Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => __( 'Quantity', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'ea_woo_checkout_table_header_text' => 'yes',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_table_price_text',
			[
				'label' => __( 'Price Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => __( 'Price', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'ea_woo_checkout_table_header_text' => 'yes',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_table_subtotal_text',
			[
				'label' => __( 'Subtotal Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Subtotal', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
                'condition' => [
                    'ea_woo_checkout_table_header_text' => 'yes',
                ],
			]
		);
		$this->add_control(
			'ea_woo_checkout_table_shipping_text',
			[
				'label' => __( 'Shipping Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Shipping', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
                'condition' => [
                    'ea_woo_checkout_table_header_text' => 'yes',
                ],
			]
		);
		$this->add_control(
			'ea_woo_checkout_table_total_text',
			[
				'label' => __( 'Total Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Total', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
                'condition' => [
                    'ea_woo_checkout_table_header_text' => 'yes',
                ],
			]
		);

		// Shop Link
		$this->add_control(
			'ea_woo_checkout_shop_link',
			[
				'label' => esc_html__( 'Shop Link', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'ea_woo_checkout_shop_link_text',
			[
				'label' => __( 'Link Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
				'default' => __( 'Continue Shopping', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'ea_woo_checkout_shop_link' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Coupon Settings
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_checkout_settings',
			[
				'label' => esc_html__( 'Coupon', 'essential-addons-for-elementor-lite' ),
			]
		);
		$this->add_control(
			'ea_woo_checkout_coupon_hide',
			[
				'label'        => esc_html__( 'Hide', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);
		$this->add_control(
			'ea_woo_checkout_coupon_icon',
			[
				'label' => __( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-percent',
					'library' => 'fa-solid',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_coupon_title',
			[
				'label' => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Have a coupon?', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_coupon_link_text',
			[
				'label' => __( 'Link Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Click here to enter your code', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_coupon_form_content',
			[
				'label' => __( 'Form Content', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'If you have a coupon code, please apply it below.', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_coupon_placeholder_text',
			[
				'label' => __( 'Placeholder Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Coupon code', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_coupon_button_text',
			[
				'label' => __( 'Button Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Apply Coupon', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Login Settings
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_login_settings',
			[
				'label' => esc_html__( 'Login', 'essential-addons-for-elementor-lite' ),
			]
		);
		$this->add_control(
			'ea_section_woo_login_show',
			[
				'label' => __( 'Show Preview of Login', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => __( 'Show', 'essential-addons-elementor' ),
				'label_off' => __( 'Hide', 'essential-addons-elementor' ),
				'return_value' => 'yes',
				'description' => 'You can force show login in order to style them properly.',
			]
		);
        if('yes' != get_option( 'woocommerce_enable_checkout_login_reminder' )){
            $this->add_control(
                'ea_section_woo_login_show_warning_text',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => __('Allow customers to log into an existing account during checkout is disabled on your site. Please enable it to use the login form. You can enable it from WooCommerce >> Settings >> Accounts & Privacy >> <a target="_blank" href="'.esc_url(admin_url( 'admin.php?page=wc-settings&tab=account')).'">Guest checkout.</a>',
                        'essential-addons-for-elementor-lite'),
                    'content_classes' => 'eael-warning',
                    'condition' => [
                        'ea_section_woo_login_show' => 'yes',
                    ],
                ]
            );
        }


		$this->add_control(
			'ea_woo_checkout_login_icon',
			[
				'label' => __( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-user',
					'library' => 'fa-solid',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_login_title',
			[
				'label' => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Returning customer?', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
    );
    
    $this->add_control(
			'ea_woo_checkout_login_message',
			[
				'label' => __( 'Message', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
    );
    
		$this->add_control(
			'ea_woo_checkout_login_link_text',
			[
				'label' => __( 'Link Text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Click here to login', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Customer Details Settings
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_customer_details_settings',
			[
				'label' => esc_html__( 'Customer Details', 'essential-addons-for-elementor-lite' ),
			]
		);
		$this->add_control(
			'ea_woo_checkout_billing_title',
			[
				'label' => __( 'Billing Title', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Billing Details', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_shipping_title',
			[
				'label' => __( 'Shipping Title', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ship to a different address?', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_additional_info_title',
			[
				'label' => __( 'Additional Info Title', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Additional Information', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Payment Settings
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_payment_settings',
			[
				'label' => esc_html__( 'Payment', 'essential-addons-for-elementor-lite' ),
			]
		);
		$this->add_control(
			'ea_woo_checkout_payment_title',
			[
				'label' => __( 'Title', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Payment Methods', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_place_order_text',
			[
				'label' => __( 'Button text', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Place Order', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);
		$this->end_controls_section();

		do_action('eael_woo_checkout_pro_enabled_tabs_styles', $this);

		/**
		 * -------------------------------------------
		 * Tab Style Section title
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_checkout_section_title',
			[
				'label' => esc_html__( 'Section Title', 'essential-addons-for-elementor-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_section_title_typography',
				'selector' => '{{WRAPPER}} h3, {{WRAPPER}} #ship-to-different-address span, {{WRAPPER}} .ea-woo-checkout #customer_details h3',
			]
		);
		$this->add_control(
			'ea_woo_checkout_section_title_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#404040',
				'selectors' => [
					'{{WRAPPER}} h3, {{WRAPPER}} .woo-checkout-section-title, {{WRAPPER}} #ship-to-different-address span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_section_bottom_gap',
			[
				'label' => esc_html__( 'Bottom Gap', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} h3, {{WRAPPER}} .woo-checkout-section-title, {{WRAPPER}} .ea-woo-checkout #customer_details h3' => 'margin-bottom: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style Order Details Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_checkout_order_review_style',
			[
				'label' => esc_html__( 'Order Details', 'essential-addons-for-elementor-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'ea_woo_checkout_order_review_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#443e6d',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_order_review_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '20',
					'right' => '20',
					'bottom' => '20',
					'left' => '20',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_order_review_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_order_review_head',
			[
				'label' => __( 'Table Head', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
                'condition' => [
                    '!ea_woo_checkout_layout' => 'default',
                ],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_order_review_header_typo',
				'selector' => '{{WRAPPER}} .ea-woo-checkout-order-review .table-header',
                'condition' => [
                    '!ea_woo_checkout_layout' => 'default',
                ],
			]
		);
		$this->add_control(
			'ea_woo_checkout_order_review_header_color',
			[
				'label' => esc_html__( 'Header Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .table-header' => 'color: {{VALUE}};',
				],
                'condition' => [
                    '!ea_woo_checkout_layout' => 'default',
                ],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_order_review_header_top_spacing',
			[
				'label' => __( 'Top Spacing', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 12,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .ea-woo-checkout-order-review .ea-order-review-table li.table-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'ea_woo_checkout_layout' => 'default',
                ],
			]
		);

		$this->add_control(
			'ea_woo_checkout_order_review_body',
			[
				'label' => __( 'Table Body', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'ea_woo_checkout_order_review_row_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .table-row' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_layout' => 'default',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_order_review_row_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#404040',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .table-row' => 'color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_layout' => 'default',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_order_review_row_color_pro',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .table-row' => 'color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_layout!' => 'default',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_order_review_row_border_color_pro',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ab93f5',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout.layout-split .layout-split-container .table-area .ea-woo-checkout-order-review .ea-order-review-table .table-row, {{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .table-area .ea-woo-checkout-order-review .ea-order-review-table .table-row, {{WRAPPER}} .ea-woo-checkout.layout-split .layout-split-container .table-area .ea-woo-checkout-order-review .ea-order-review-table-footer .footer-content .order-total, {{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .table-area .ea-woo-checkout-order-review .ea-order-review-table-footer .footer-content .order-total' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .ea-woo-checkout .table-area .ea-woo-checkout-order-review .ea-order-review-table-footer .footer-content .recurring-wrapper td, {{WRAPPER}} .ea-woo-checkout .table-area .ea-woo-checkout-order-review .ea-order-review-table-footer .footer-content .recurring-wrapper th' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_layout!' => 'default',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_order_review_row_typography',
				'selector' => '{{WRAPPER}} .ea-woo-checkout-order-review .table-row',
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_order_review_row_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .table-row, {{WRAPPER}} .ea-woo-checkout-order-review .product-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'ea_woo_checkout_layout' => 'default',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_order_review_row_gap',
			[
				'label' => __( 'Row Gap', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .ea-woo-checkout-order-review .ea-order-review-table li.table-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ea-woo-checkout.layout-split .layout-split-container .table-area .ea-woo-checkout-order-review .ea-order-review-table .table-row' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_order_review_footer',
			[
				'label' => __( 'Table Footer', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_order_review_footer_typography',
				'selector' => '{{WRAPPER}} .ea-woo-checkout-order-review .footer-content, {{WRAPPER}} .ea-woo-checkout-order-review .footer-content table th, {{WRAPPER}} .ea-woo-checkout-order-review .footer-content table td .amount',
			]
		);
		$this->add_control(
			'ea_woo_checkout_order_review_footer_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .footer-content' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_layout' => 'default',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_order_review_footer_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#404040',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .footer-content' => 'color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_layout' => 'default',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_order_review_footer_color_pro',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .footer-content' => 'color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_layout!' => 'default',
				],
			]
		);
		$this->start_controls_tabs( 'ea_woo_checkout_order_review_footer_link_color_tabs',
            [
	            'condition' => [
		            'ea_woo_checkout_layout' => 'default',
	            ],
            ]);

		$this->start_controls_tab( 'ea_woo_checkout_order_review_footer_link_color_tab_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'ea_woo_checkout_order_review_footer_link_color',
			[
				'label' => esc_html__( 'Link Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#443e6d',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .footer-content a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'ea_woo_checkout_order_review_footer_link_color_tab_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'ea_woo_checkout_order_review_footer_link_color_hover',
			[
				'label' => esc_html__( 'Link Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .footer-content a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Not default
		$this->start_controls_tabs( 'ea_woo_checkout_order_review_footer_link_color_tabs_pro',
			[
				'condition' => [
					'ea_woo_checkout_layout!' => 'default',
				],
			]);

		$this->start_controls_tab( 'ea_woo_checkout_order_review_footer_link_color_tab_normal_pro', [ 'label' =>
            esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'ea_woo_checkout_order_review_footer_link_color_pro',
			[
				'label' => esc_html__( 'Link Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f1ecff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .footer-content a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'ea_woo_checkout_order_review_footer_link_color_tab_hover_pro', [ 'label' =>
            esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'ea_woo_checkout_order_review_footer_link_color_hover_pro',
			[
				'label' => esc_html__( 'Link Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .footer-content a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'ea_woo_checkout_order_review_footer_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#404040',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .footer-content > div' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_order_review_footer_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .footer-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'ea_woo_checkout_layout' => 'default',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_order_review_footer_top_spacing',
			[
				'label' => __( 'Top Spacing', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .ea-woo-checkout-order-review .ea-order-review-table-footer' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'ea_woo_checkout_order_review_total',
            [
                'label' => __( 'Total', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'ea_woo_checkout_layout!' => 'default',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ea_woo_checkout_order_review_total_typo',
                'selector' => '{{WRAPPER}} .ea-woo-checkout.layout-split .layout-split-container .table-area .ea-woo-checkout-order-review .ea-order-review-table-footer .footer-content .order-total, {{WRAPPER}} .ea-woo-checkout.layout-multi-steps .layout-multi-steps-container .table-area .ea-woo-checkout-order-review .ea-order-review-table-footer .footer-content .order-total, {{WRAPPER}} .ea-woo-checkout .table-area .ea-woo-checkout-order-review .ea-order-review-table-footer .footer-content table th, {{WRAPPER}} .ea-woo-checkout .table-area .ea-woo-checkout-order-review .ea-order-review-table-footer .footer-content table td .amount',
                'condition' => [
                    'ea_woo_checkout_layout!' => 'default',
                ],
            ]
        );
        $this->add_control(
            'ea_woo_checkout_order_review_total_color',
            [
                'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .ea-woo-checkout-order-review .footer-content .order-total, {{WRAPPER}} .ea-woo-checkout-order-review .footer-content th, {{WRAPPER}} .ea-woo-checkout-order-review .footer-content td' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'ea_woo_checkout_layout!' => 'default',
                ],
            ]
        );

		$this->add_control(
			'ea_woo_checkout_order_review_shop_link',
			[
				'label' => __( 'Shop Link', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'ea_woo_checkout_shop_link' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'ea_woo_checkout_shop_link_color_tabs', [
			'condition' => [
				'ea_woo_checkout_shop_link' => 'yes',
			],

		]);

		$this->start_controls_tab( 'ea_woo_checkout_shop_link_color_tab_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'ea_woo_checkout_shop_link_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .back-to-shopping' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'eea_woo_checkout_shop_link_color_tab_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'ea_woo_checkout_shop_link_color_hover',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout-order-review .back-to-shopping:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

        $this->add_responsive_control(
            'ea_woo_checkout_shop_link_top_spacing',
            [
                'label' => __( 'Top Spacing', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ea-woo-checkout-order-review .back-to-shopping' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
	            'condition' => [
		            'ea_woo_checkout_layout!' => 'default',
	            ],
            ]
        );

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style Login
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_checkout_login_style',
			[
				'label' => esc_html__( 'Login', 'essential-addons-for-elementor-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_login_typo',
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woo-checkout-login',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ea_woo_checkout_login_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woo-checkout-login',
			]
		);
		$this->add_control(
			'ea_woo_checkout_login_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#404040',
				'selectors' => [
					'{{WRAPPER}} .woo-checkout-login, {{WRAPPER}} .woo-checkout-login .woocommerce-form-login-toggle .woocommerce-info' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_login_icon_color',
			[
				'label' => __( 'Icon Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#404040',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .ea-login-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ea-woo-checkout .ea-login-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_login_links_color',
			[
				'label' => __( 'Links Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-login a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_login_links_color_hover',
			[
				'label' => __( 'Links Hover Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-login a:hover' => 'color: {{VALUE}}!important;',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_login_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '20',
					'right' => '20',
					'bottom' => '20',
					'left' => '20',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .woo-checkout-login' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ea-woo-checkout .ea-login-icon' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_login_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .woo-checkout-login' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_login_form_label',
			[
				'label' => __( 'Form Label', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_login_form_label_typo',
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woo-checkout-login label',
			]
		);
		$this->add_control(
			'ea_woo_checkout_login_form_label_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#404040',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woo-checkout-login label' => 'color: {{VALUE}};',
				],
			]
		);

		// Login Btn
		$this->add_control(
			'ea_woo_checkout_login_btn',
			[
				'label' => __( 'Button', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_login_btn_typo',
				'label' => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-login .button',
			]
		);

		$this->start_controls_tabs( 'ea_woo_checkout_login_btn_tabs' );
		$this->start_controls_tab(
			'ea_woo_checkout_login_btn_tab_normal',
			[
				'label' => __( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_woo_checkout_login_btn_bg_color',
			[
				'label' => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-login .button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_login_btn_color',
			[
				'label' => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-login .button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'ea_woo_checkout_login_btn_border',
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-login .button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ea_woo_checkout_login_btn_tab_hover',
			[
				'label' => __( 'Hover', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_woo_checkout_login_btn_bg_color_hover',
			[
				'label' => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-login .button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_login_btn_color_hover',
			[
				'label' => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-login .button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_login_btn_border_color_hover',
			[
				'label' => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-login .button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_login_btn_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'ea_woo_checkout_login_btn_border_radius',
			[
				'label' => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-login .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ea_woo_checkout_login_btn_padding',
			[
				'label' => __( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-login .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ea_woo_checkout_login_btn_box_shadow',
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-login .button',
			]
		);
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style Coupon
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_checkout_coupon_style',
			[
				'label' => esc_html__( 'Coupon', 'essential-addons-for-elementor-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_coupon_typo',
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woo-checkout-coupon .woocommerce-form-coupon-toggle .woocommerce-info,{{WRAPPER}} .ea-woo-checkout .woo-checkout-coupon .woocommerce-form-coupon-toggle .woocommerce-info a.showcoupon',
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'ea_woo_checkout_coupon_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woo-checkout-coupon',
			]
		);
		$this->add_control(
			'ea_woo_checkout_coupon_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#404040',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woo-checkout-coupon, {{WRAPPER}} .ea-woo-checkout .woo-checkout-coupon .woocommerce-form-coupon-toggle .woocommerce-info' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_coupon_icon_color',
			[
				'label' => __( 'Icon Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#404040',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .ea-coupon-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ea-woo-checkout .ea-coupon-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_coupon_links_color',
			[
				'label' => __( 'Links Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-form-coupon-toggle .woocommerce-info a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_coupon_links_color_hover',
			[
				'label' => __( 'Links Hover Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-form-coupon-toggle .woocommerce-info a:hover' => 'color: {{VALUE}}!important;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ea_woo_checkout_coupon_border',
				'label' => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woo-checkout-coupon',
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_coupon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .woo-checkout-coupon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ea_woo_checkout_coupon_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woo-checkout-coupon',
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_coupon_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '20',
					'right' => '20',
					'bottom' => '20',
					'left' => '20',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .woo-checkout-coupon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ea-woo-checkout .ea-coupon-icon' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_coupon_form',
			[
				'label' => __( 'Form', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'ea_woo_checkout_coupon_form_border_color',
			[
				'label' => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#404040',
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woocommerce form.checkout_coupon' => 'border: 1px solid {{VALUE}};',
				],
			]
		);

		// Coupon Btn
		$this->add_control(
			'ea_woo_checkout_coupon_btn',
			[
				'label' => __( 'Button', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_coupon_btn_typo',
				'label' => __( 'Typography', 'essential-addons-for-elementor-lite' ),
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-coupon .button',
			]
		);

		$this->start_controls_tabs( 'ea_woo_checkout_coupon_btn_tabs' );
		$this->start_controls_tab(
			'ea_woo_checkout_coupon_btn_tab_normal',
			[
				'label' => __( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_woo_checkout_coupon_btn_bg_color',
			[
				'label' => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-coupon .button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_coupon_btn_color',
			[
				'label' => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-coupon .button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'ea_woo_checkout_coupon_btn_border',
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-coupon .button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ea_woo_checkout_coupon_btn_tab_hover',
			[
				'label' => __( 'Hover', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_woo_checkout_coupon_btn_bg_color_hover',
			[
				'label' => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-coupon .button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_coupon_btn_color_hover',
			[
				'label' => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-coupon .button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_coupon_btn_border_color_hover',
			[
				'label' => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-coupon .button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_coupon_btn_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'ea_woo_checkout_coupon_btn_border_radius',
			[
				'label' => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-coupon .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ea_woo_checkout_coupon_btn_padding',
			[
				'label' => __( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-coupon .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ea_woo_checkout_coupon_btn_box_shadow',
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woocommerce .woo-checkout-coupon .button',
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style Notices
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_checkout_notices_style',
			[
				'label' => esc_html__( 'Notices', 'essential-addons-for-elementor-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_section_woo_checkout_notices_typo',
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woocommerce-error, {{WRAPPER}} .ea-woo-checkout .woocommerce-info, {{WRAPPER}} .ea-woo-checkout .woocommerce-message',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ea_woo_checkout_notices_border',
				'label' => __( 'Border', 'essential-addons-for-elementor-lite' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woocommerce-error',
			]
		);

		$this->start_controls_tabs( 'ea_woo_checkout_notices_style_tabs' );

		$this->start_controls_tab( 'ea_woo_checkout_notices_style_tab_info', [
			'label' => esc_html__( 'Info',
				'essential-addons-for-elementor-lite' )
		] );
		$this->add_control(
			'ea_woo_checkout_notices_info_bg_color',
			[
				'label' => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d1ecf1',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-info' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_notices_info_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0c5460',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-info, {{WRAPPER}} .woo-checkout-coupon .woocommerce-info, {{WRAPPER}} .woo-checkout-login .woocommerce-info' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_notices_info_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#0c5460',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-info, {{WRAPPER}} .woo-checkout-coupon .woocommerce-info, {{WRAPPER}} .woo-checkout-login .woocommerce-info' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_notices_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'ea_woo_checkout_notices_style_tab_error', [
			'label' => esc_html__( 'Error',
				'essential-addons-for-elementor-lite' )
		] );

		$this->add_control(
			'ea_woo_checkout_notices_error_bg_color',
			[
				'label' => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF3F5',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-error' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_notices_error_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF7E93',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-error, {{WRAPPER}} .woo-checkout-coupon .woocommerce-error, {{WRAPPER}} .woo-checkout-login .woocommerce-error, {{WRAPPER}} .woocommerce-NoticeGroup .woocommerce-error' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_notices_error_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF7E93',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-error, {{WRAPPER}} .woo-checkout-coupon .woocommerce-error, {{WRAPPER}} .woo-checkout-login .woocommerce-error' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_notices_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'ea_woo_checkout_notices_style_tab_message', [
			'label' => esc_html__( 'Message',
				'essential-addons-for-elementor-lite' )
		] );

		$this->add_control(
			'ea_woo_checkout_notices_message_bg_color',
			[
				'label' => esc_html__( 'Background', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#d4edda',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-message' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_notices_message_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#155724',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-message, {{WRAPPER}} .woo-checkout-coupon .woocommerce-message, {{WRAPPER}} .woo-checkout-login .woocommerce-message' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_notices_message_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#155724',
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-message, {{WRAPPER}} .woo-checkout-coupon .woocommerce-message, {{WRAPPER}} .woo-checkout-login .woocommerce-message' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_notices_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_responsive_control(
			'ea_woo_checkout_notices_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-error, {{WRAPPER}} .ea-woo-checkout .woocommerce-info, {{WRAPPER}} .ea-woo-checkout .woocommerce-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ea_woo_checkout_notices_box_shadow',
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .ea-woo-checkout .woocommerce-error, {{WRAPPER}} .ea-woo-checkout .woocommerce-info, {{WRAPPER}} .ea-woo-checkout .woocommerce-message',
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_notices_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '20',
					'right' => '20',
					'bottom' => '20',
					'left' => '20',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-notices-wrapper .woocommerce-error, {{WRAPPER}} .ea-woo-checkout .woocommerce-info, {{WRAPPER}} .ea-woo-checkout .woocommerce-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} calc({{LEFT}}{{UNIT}} + 30px);',
					'{{WRAPPER}} .ea-woo-checkout .woocommerce-error::before, , {{WRAPPER}} .ea-woo-checkout .woocommerce-info::before, {{WRAPPER}} .ea-woo-checkout .woocommerce-message::before' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style Customer Details
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_checkout_customer_details',
			[
				'label' => esc_html__( 'Customer Details', 'essential-addons-for-elementor-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'ea_woo_checkout_customer_details_label',
			[
				'label' => __( 'Label', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_customer_details_label_typography',
				'selector' => '{{WRAPPER}} #customer_details label',
			]
		);
		$this->add_control(
			'ea_woo_checkout_customer_details_label_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#443e6d',
				'selectors' => [
					'{{WRAPPER}} #customer_details label' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_customer_details_label_spacing',
			[
				'label' => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '0',
					'right' => '0',
					'bottom' => '5',
					'left' => '0',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #customer_details label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_customer_details_field_required',
			[
				'label' => __( 'Required (*)', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'ea_woo_checkout_customer_details_required_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ff0000',
				'selectors' => [
					'{{WRAPPER}} #customer_details label .required' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_customer_details_fields',
			[
				'label' => __( 'Fields', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'inputs_height',
			[
				'label' => __( 'Input Height', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 50,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ea-woo-checkout .woocommerce .woocommerce-checkout .form-row input.input-text, {{WRAPPER}} .ea-woo-checkout .woocommerce .woocommerce-checkout .form-row select, .eael-woo-checkout {{WRAPPER}} .ea-woo-checkout .select2-container .select2-selection--single'
					=> 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_customer_details_field_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#443e6d',
				'selectors' => [
					'{{WRAPPER}} #customer_details input, {{WRAPPER}} #customer_details select, {{WRAPPER}} #customer_details textarea' => 'color: {{VALUE}};',
				],
			]
		);
		$this->start_controls_tabs( 'ea_woo_checkout_customer_details_field_tabs' );

		$this->start_controls_tab( 'ea_woo_checkout_customer_details_field_tab_normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'ea_woo_checkout_customer_details_field_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#cccccc',
				'selectors' => [
					'{{WRAPPER}} #customer_details input, {{WRAPPER}} #customer_details .select, {{WRAPPER}} #customer_details .select2-container--default .select2-selection--single, {{WRAPPER}} #customer_details textarea' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'ea_woo_checkout_customer_details_field_tab_normal_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ) ] );

		$this->add_control(
			'ea_woo_checkout_customer_details_field_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} #customer_details input:hover, {{WRAPPER}} #customer_details input:focus, {{WRAPPER}} #customer_details input:active' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} #customer_details textarea:hover, {{WRAPPER}} #customer_details textarea:focus, {{WRAPPER}} #customer_details textarea:active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'ea_woo_checkout_customer_details_field_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #customer_details input, {{WRAPPER}} #customer_details select, {{WRAPPER}} #customer_details .select2-container--default .select2-selection--single, {{WRAPPER}} #customer_details textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_customer_details_field_spacing',
			[
				'label' => __( 'Bottom Spacing (PX)', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} #customer_details .form-row' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		if( true ) {
			$this->start_controls_section(
				'ea_section_woo_checkout_pickup_point_style',
				[
					'label' => esc_html__( 'Pickup Point', 'essential-addons-for-elementor-lite' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'ea_woo_checkout_pickup_point_title_color',
				[
					'label' => esc_html__( 'Title Color', 'essential-addons-for-elementor-lite' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#ffffff',
					'selectors' => [
						'.eael-woo-checkout {{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search #carrier-agent-heading' => 'color: {{VALUE}};',
					],
				]
			);

			$this->start_controls_tabs('ea_woo_checkout_pickup_point_tabs');
			$this->start_controls_tab('ea_woo_checkout_pickup_point_tab_normal', ['label' => __('Normal', 'essential-addons-for-elementor')]);

			$this->add_control('ea_woo_checkout_pickup_point_btn_bg_color', [
				'label' => __('Background Color', 'essential-addons-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search .woo-carrier-agents-postcode-input-wrapper #woo-carrier-agents-search-button' => 'background-color: {{VALUE}};',
				],
			]);

			$this->add_control('ea_woo_checkout_pickup_point_btn_color', [
				'label' => __('Color', 'essential-addons-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search .woo-carrier-agents-postcode-input-wrapper #woo-carrier-agents-search-button' => 'color: {{VALUE}};',
				],
			]);

			$this->add_group_control(Group_Control_Border::get_type(), [
				'name' => 'ea_woo_checkout_pickup_point_btn_border',
				'selector' => '{{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search .woo-carrier-agents-postcode-input-wrapper #woo-carrier-agents-search-button',
			]);

			$this->end_controls_tab();

			$this->start_controls_tab('ea_woo_checkout_pickup_point_tab_hover', ['label' => __('Hover', 'essential-addons-for-elementor')]);

			$this->add_control('ea_woo_checkout_pickup_point_btn_bg_color_hover', [
				'label' => __('Background Color', 'essential-addons-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search .woo-carrier-agents-postcode-input-wrapper #woo-carrier-agents-search-button:hover' => 'background-color: {{VALUE}};',
				],
			]);

			$this->add_control('ea_woo_checkout_pickup_point_btn_color_hover', [
				'label' => __('Color', 'essential-addons-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search .woo-carrier-agents-postcode-input-wrapper #woo-carrier-agents-search-button:hover' => 'color: {{VALUE}};',
				],
			]);

			$this->add_control('ea_woo_checkout_pickup_point_btn_border_color_hover', [
				'label' => __('Border Color', 'essential-addons-for-elementor'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woo-checkout-payment .carrier-agents-postcode-search .woo-carrier-agents-postcode-input-wrapper #woo-carrier-agents-search-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_pickup_point_btn_border_border!' => '',
				],
			]);

			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->end_controls_section();
		}

		/**
		 * -------------------------------------------
		 * Tab Style Payment
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_woo_checkout_payment_style',
			[
				'label' => esc_html__( 'Payment', 'essential-addons-for-elementor-lite' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'ea_woo_checkout_payment_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#443e6d',
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woo-checkout-payment, {{WRAPPER}} #payment' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_payment_title_color',
			[
				'label' => esc_html__( 'Title Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woo-checkout-payment .woo-checkout-section-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_payment_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '20',
					'right' => '20',
					'bottom' => '20',
					'left' => '20',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woo-checkout-payment' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_payment_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woo-checkout-payment' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Label
		$this->add_control(
			'ea_woo_checkout_payment_label',
			[
				'label' => __( 'Label', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_payment_label_typography',
				'selector' => '.eael-woo-checkout {{WRAPPER}} .woocommerce .woo-checkout-payment #payment .payment_methods .wc_payment_method > label',
			]
		);

		$this->start_controls_tabs( 'ea_woo_checkout_payment_label_tabs' );
		$this->start_controls_tab(
			'ea_woo_checkout_payment_label_tab_normal',
			[
				'label' => __( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_woo_checkout_payment_label_color',
			[
				'label' => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b8b6ca',
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woocommerce .woo-checkout-payment #payment .payment_methods .wc_payment_method input[type="radio"] + label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ea_woo_checkout_payment_label_tab_hover',
			[
				'label' => __( 'Selected', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_woo_checkout_payment_label_color_select',
			[
				'label' => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woocommerce .woo-checkout-payment #payment .payment_methods .wc_payment_method input[type="radio"]:checked + label' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		// Info
		$this->add_control(
			'ea_woo_checkout_payment_info',
			[
				'label' => __( 'Methods Info', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'ea_woo_checkout_payment_methods_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#2d284b',
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woo-checkout-payment .payment_box' => 'background-color: {{VALUE}}!important;',
					'.eael-woo-checkout {{WRAPPER}} .woo-checkout-payment .payment_box::before' => 'border-bottom-color: {{VALUE}}!important;',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_payment_methods_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woo-checkout-payment .payment_box' => 'color: {{VALUE}}!important;',
				],
			]
		);

		// Privacy Policy
		$this->add_control(
			'ea_woo_checkout_privacy_policy',
			[
				'label' => __( 'Privacy Policy', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'ea_woo_checkout_privacy_policy_color',
			[
				'label' => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b8b6ca',
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woo-checkout-payment .woocommerce-privacy-policy-text' => 'color: {{VALUE}}!important;',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_privacy_policy_typo',
				'selector' => '.eael-woo-checkout {{WRAPPER}} .woo-checkout-payment .woocommerce-privacy-policy-text',
			]
		);
		$this->add_control(
			'ea_woo_checkout_privacy_policy_link_color',
			[
				'label' => esc_html__( 'Link Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woo-checkout-payment a.woocommerce-privacy-policy-link' => 'color: {{VALUE}}!important;',
				],
			]
		);
		$this->add_control(
			'ea_woo_checkout_privacy_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b8b6ca',
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woo-checkout-payment .place-order' => 'border-color: {{VALUE}}!important;',
				],
			]
		);
		// Privacy Policy Btn
		$this->add_control(
			'ea_woo_checkout_payment_btn',
			[
				'label' => __( 'Button', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'ea_woo_checkout_layout' => 'default',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ea_woo_checkout_payment_btn_typo',
				'selector' => '.eael-woo-checkout {{WRAPPER}} .woocommerce #place_order',
				'condition' => [
					'ea_woo_checkout_layout' => 'default',
				],
			]
		);

		$this->start_controls_tabs( 'ea_woo_checkout_payment_btn_tabs',
            [
	            'condition' => [
		            'ea_woo_checkout_layout' => 'default',
	            ],
            ]
        );
		$this->start_controls_tab(
			'ea_woo_checkout_payment_btn_tab_normal',
			[
				'label' => __( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_woo_checkout_payment_btn_bg_color',
			[
				'label' => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} #place_order' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_payment_btn_color',
			[
				'label' => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} #place_order' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'ea_woo_checkout_payment_btn_border',
				'selector' => '{{WRAPPER}} #place_order',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'ea_woo_checkout_payment_btn_tab_hover',
			[
				'label' => __( 'Hover', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_woo_checkout_payment_btn_bg_color_hover',
			[
				'label' => __( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7866ff',
				'selectors' => [
					'{{WRAPPER}} #place_order:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_payment_btn_color_hover',
			[
				'label' => __( 'Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} #place_order:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ea_woo_checkout_payment_btn_border_color_hover',
			[
				'label' => __( 'Border Color', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #place_order:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'ea_woo_checkout_payment_btn_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'ea_woo_checkout_payment_btn_border_radius',
			[
				'label' => __( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} #place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'ea_woo_checkout_layout' => 'default',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ea_woo_checkout_payment_btn_box_shadow',
				'selector' => '{{WRAPPER}} #place_order',
				'condition' => [
					'ea_woo_checkout_layout' => 'default',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_payment_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => '15',
					'right' => '20',
					'bottom' => '15',
					'left' => '20',
					'unit' => 'px',
					'isLinked' => true,
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woocommerce #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'ea_woo_checkout_layout' => 'default',
				],
			]
		);
		$this->add_responsive_control(
			'ea_woo_checkout_payment_btn_top_spacing',
			[
				'label' => esc_html__( 'Top Spacing', 'essential-addons-for-elementor-lite' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'.eael-woo-checkout {{WRAPPER}} .woocommerce #place_order' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ea_woo_checkout_layout' => 'default',
				],
			]
		);

		$this->end_controls_section();

		do_action('eael_woo_checkout_pro_enabled_steps_btn_styles', $this);
	}

	public function add_checkout_body_class( $classes ){
		if ( is_checkout() ) {
			$classes[] = 'eael-woo-checkout';
		}
		return $classes;
	}


  public function eael_woocheckout_recurring(){
    if( class_exists('WC_Subscriptions_Cart') ) {
      remove_action('woocommerce_review_order_after_order_total', array( 'WC_Subscriptions_Cart', 'display_recurring_totals' ), 10);
      add_action('eael_display_recurring_total_total', array( 'WC_Subscriptions_Cart', 'display_recurring_totals'
      ), 10);
    }
  }

	protected function render() {
	    if( !class_exists('woocommerce') ) {
	        return;
        }

		/**
		 * Remove WC Coupon Action From  Neve Theme
		 */
		$this->eael_forcefully_remove_action( 'woocommerce_before_checkout_form', 'move_coupon', 10 );
		$this->eael_forcefully_remove_action( 'woocommerce_before_checkout_billing_form', 'clear_coupon', 10 );

		if ( class_exists( '\Woo_Carrier_Agents' ) ) {
			$this->eael_forcefully_remove_action( 'woocommerce_checkout_order_review', 'add_carrier_agent_field_before_payment', 15 );
			$wca = new \Woo_Carrier_Agents();
			add_action( 'eael_wc_multistep_checkout_after_shipping', [ $wca, 'add_carrier_agent_field_before_payment' ], 10, 0 );
		}

        $settings = $this->get_settings_for_display();

		if ( in_array( $settings[ 'ea_woo_checkout_layout' ], [ 'multi-steps', 'split' ] ) ) {
			if ( !apply_filters( 'eael/pro_enabled', false ) ) {
				return;
			}
		}

        $this->add_render_attribute( 'container', 'class', [
			'ea-woo-checkout',
			'layout-'. $settings['ea_woo_checkout_layout']
		] );

		global $wp;
		$order_review_change_data = [
			'ea_woo_checkout_layout'              => $settings['ea_woo_checkout_layout'],
			'ea_woo_checkout_table_header_text'   => $settings['ea_woo_checkout_table_header_text'],
			'ea_woo_checkout_table_product_text'  => $settings['ea_woo_checkout_table_product_text'],
			'ea_woo_checkout_table_quantity_text' => $settings['ea_woo_checkout_table_quantity_text'],
			'ea_woo_checkout_table_price_text'    => $settings['ea_woo_checkout_table_price_text'],
			'ea_woo_checkout_shop_link'           => $settings['ea_woo_checkout_shop_link'],
			'ea_woo_checkout_shop_link_text'      => $settings['ea_woo_checkout_shop_link_text'],
			'ea_woo_checkout_table_subtotal_text' => $settings['ea_woo_checkout_table_subtotal_text'],
			'ea_woo_checkout_table_shipping_text' => $settings['ea_woo_checkout_table_shipping_text'],
			'ea_woo_checkout_table_total_text'    => $settings['ea_woo_checkout_table_total_text'],
		];
        $this->ea_woo_checkout_add_actions($settings);

		?>
        <div data-checkout="<?php echo htmlspecialchars(json_encode($order_review_change_data), ENT_QUOTES, 'UTF-8'); ?>" <?php echo $this->get_render_attribute_string( 'container' ); ?>>
            <div class="woocommerce">
                <style>
                    .woocommerce .blockUI.blockOverlay:before {
                        background-image: url('<?php echo WC_ABSPATH . 'assets/images/icons/loader.svg' ?>') center center !important;
                    }
                </style>
				<?php

				// Backwards compatibility with old pay and thanks link arguments.
				if ( isset( $_GET['order'] ) && isset( $_GET['key'] ) ) { // WPCS: input var ok, CSRF ok.
					wc_deprecated_argument( __CLASS__ . '->' . __FUNCTION__, '2.1', '"order" is no longer used to pass an order ID. Use the order-pay or order-received endpoint instead.' );

					// Get the order to work out what we are showing.
					$order_id = absint( $_GET['order'] ); // WPCS: input var ok.
					$order    = wc_get_order( $order_id );

					if ( $order && $order->has_status( 'pending' ) ) {
						$wp->query_vars['order-pay'] = absint( $_GET['order'] ); // WPCS: input var ok.
					} else {
						$wp->query_vars['order-received'] = absint( $_GET['order'] ); // WPCS: input var ok.
					}
				}

				// Handle checkout actions.
				if ( ! empty( $wp->query_vars['order-pay'] ) ) {

					self::ea_order_pay( $wp->query_vars['order-pay'] );

				} elseif ( isset( $wp->query_vars['order-received'] ) ) {

					self::ea_order_received( $wp->query_vars['order-received'] );

				} else {
					self::ea_checkout( $settings );
				}

				?>
            </div>
        </div>
		<?php
	}
}

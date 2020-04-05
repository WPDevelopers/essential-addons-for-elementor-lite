<?php
namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;

class Woo_Checkout extends Widget_Base
{
    use \Essential_Addons_Elementor\Traits\Helper;
    use \Essential_Addons_Elementor\Template\Woocommerce\Checkout\Layouts\Woo_Checkout_Default;

    public function get_name()
    {
        return 'eael-woo-checkout';
    }

    public function get_title()
    {
        return esc_html__('EA Woo Checkout', 'essential-addons-for-elementor-lite');
    }

    public function get_icon()
    {
        return 'eicon-cart-medium';
    }

    public function get_categories()
    {
        return ['essential-addons-elementor'];
    }

    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 3.5.2
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return [ 'woocommerce', 'checkout', 'ea', 'woocommerce checkout' ];
    }

    protected function _register_controls()
    {
        /**
         * Woo Checkout Settings
         */
        $this->start_controls_section(
            'eael_section_woo_checkout_settings',
            [
                'label' => esc_html__('General Settings', 'essential-addons-for-elementor-lite'),
            ]
        );
        $this->add_control(
            'eael_woo_checkout_layout',
            [
                'label' => esc_html__('Layout', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'label_block' => false,
                'options' => [
                    'default' => esc_html__('Default', 'essential-addons-for-elementor-lite'),
                    'split' => esc_html__('Split', 'essential-addons-for-elementor-lite'),
                    'steps' => esc_html__('Steps', 'essential-addons-for-elementor-lite'),
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style Advance Accordion Generel Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_woo_checkout_style_settings',
            [
                'label' => esc_html__('General Style', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'eael_woo_checkout_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .eael-woo-checkout' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_woo_checkout_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-woo-checkout',
            ]
        );
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style Order Review Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_woo_checkout_order_review_style',
            [
                'label' => esc_html__('Order Review Table', 'essential-addons-for-elementor-lite'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'eael_woo_checkout_order_review_bg_color',
            [
                'label' => esc_html__('Background Color', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::COLOR,
                'default' => '#443e6d',
                'selectors' => [
                    '{{WRAPPER}} .eael-order-review-wapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_woo_checkout_order_review_padding',
            [
                'label' => esc_html__('Padding', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [ 'top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit'=> 'px', 'isLinked' => true, ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-order-review-wapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_woo_checkout_order_review_border_radius',
            [
                'label' => esc_html__('Border Radius', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::DIMENSIONS,
                'default' => [ 'top' => '5', 'right' => '5', 'bottom' => '5', 'left' => '5', 'unit'=> 'px', 'isLinked' => true, ],
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .eael-order-review-wapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings();

        $this->add_render_attribute( 'container', 'class', [
            'eael-woo-checkout'
        ] );

        ?>
        <div <?php echo $this->get_render_attribute_string( 'container' ); ?>>
            <div class="eael-woo-checkout-inner">
                <div class="woocommerce">
                    <style>
                        .woocommerce .blockUI.blockOverlay:before {
                            background-image: url('<?php echo WC_ABSPATH . 'assets/images/icons/loader.svg' ?>') center center !important;
                        }
                    </style>
                <?php
                global $wp;
                $checkout = WC()->checkout();

                if ($settings['eael_woo_checkout_layout'] == 'default') {
                    if ( isset( $wp->query_vars['order-received'] ) ) {
                        self::order_received( $wp->query_vars['order-received'] );
                    } else {
                        echo self::render_default_template_($checkout, $settings);
                    }
                }

                ?>
                </div>
            </div>
        </div>
        <?php
    }
}
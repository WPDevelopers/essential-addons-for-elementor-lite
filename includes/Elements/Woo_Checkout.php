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
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'eael_woo_checkout_border',
                'label' => esc_html__('Border', 'essential-addons-for-elementor-lite'),
                'selector' => '{{WRAPPER}} .eael-woo-checkout',
            ]
        );
        $this->end_controls_section();

        $this->end_controls_tab();
        $this->end_controls_tabs();
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
				<?php echo do_shortcode('[woocommerce_checkout]'); ?>
			</div>
        </div>
    <?php
    }
}
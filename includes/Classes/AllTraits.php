<?php 
/**
 * Class trait files
 *
 * @package Essential-addons-for-elementor-lite\classes
 */

namespace Essential_Addons_Elementor\Classes;
use Essential_Addons_Elementor\Template\Woocommerce\Checkout\Woo_Checkout_Helper;
use Essential_Addons_Elementor\Traits\Helper;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class AllTraits{
    use Helper, Woo_Checkout_Helper;
}
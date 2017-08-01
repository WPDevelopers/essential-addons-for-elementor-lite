<?php
/**
 * Plugin Name: Essential Addons for Elementor Lite
 * Description: Elements bundle for Elementor page builder plugin for WordPress. <a href="https://essential-addons.com/elementor/buy.php">Get Premium version</a>
 * Plugin URI: https://essential-addons.com/elementor/
 * Author: Codetic
 * Version: 1.0.0
 * Author URI: http://www.codetic.net
 *
 * Text Domain: essential-addons-elementor
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ESSENTIAL_ADDONS_EL_URL', plugins_url( '/', __FILE__ ) );
define( 'ESSENTIAL_ADDONS_EL_PATH', plugin_dir_path( __FILE__ ) );


require_once ESSENTIAL_ADDONS_EL_PATH.'includes/elementor-helper.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'includes/queries.php';

function add_eael_elements(){

   // load elements
   require_once ESSENTIAL_ADDONS_EL_PATH.'elements/post-timeline/post-timeline.php';
   require_once ESSENTIAL_ADDONS_EL_PATH.'elements/fancy-text/fancy-text.php';
   require_once ESSENTIAL_ADDONS_EL_PATH.'elements/creative-button/creative-button.php';
   require_once ESSENTIAL_ADDONS_EL_PATH.'elements/countdown/countdown.php';
   require_once ESSENTIAL_ADDONS_EL_PATH.'elements/team-members/team-members.php';
   require_once ESSENTIAL_ADDONS_EL_PATH.'elements/testimonials/testimonials.php';

   if ( function_exists( 'WC' ) ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/product-grid/product-grid.php';
   }

   if ( function_exists( 'wpcf7' ) ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/contact-form-7/contact-form-7.php';
   }

   if ( function_exists( 'WeForms' ) ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/weforms/weforms.php';
   }
}
add_action('elementor/widgets/widgets_registered','add_eael_elements');


function essential_addons_el_enqueue(){
   wp_enqueue_style('essential_addons_elementor-css',ESSENTIAL_ADDONS_EL_URL.'assets/css/essential-addons-elementor.css');
   wp_enqueue_script('essential_addons_elementor-fancy-text-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/fancy-text.js', array('jquery'),'1.0', true);
   wp_enqueue_script('essential_addons_elementor-countdown-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/countdown.min.js', array('jquery'),'1.0', true);
}
add_action( 'wp_enqueue_scripts', 'essential_addons_el_enqueue' );


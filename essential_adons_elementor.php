<?php
/**
 * Plugin Name: Essential Addons Elementor
 * Description: Elements bundle for Elementor page builder plugin for WordPress. <a href="https://essential-addons.com/elementor/buy.php">Get Premium version</a>
 * Plugin URI: https://essential-addons.com/elementor/
 * Author: Codetic
 * Version: 1.1.0
 * Author URI: http://www.codetic.net
 *
 * Text Domain: essential-addons-elementor
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ESSENTIAL_ADDONS_EL_URL', plugins_url( '/', __FILE__ ) );
define( 'ESSENTIAL_ADDONS_EL_PATH', plugin_dir_path( __FILE__ ) );


require_once ESSENTIAL_ADDONS_EL_PATH.'includes/elementor-helper.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'includes/queries.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'admin/settings.php';

function add_eael_elements(){

   $eael_default_settings = array(
      'contact-form-7'     => true,
      'count-down'         => true,
      'creative-btn'       => true,
      'fancy-text'         => true,
      'img-comparison'     => true,
      'instagram-gallery'  => true,
      'interactive-promo'  => true,
      'lightbox'           => true,
      'post-block'         => true,
      'post-grid'          => true,
      'post-timeline'      => true,
      'product-grid'       => true,
      'team-members'       => true,
      'testimonial-slider' => true,
      'testimonials'       => true,
      'weforms'            => true,
      'static-product'     => true,
   );
   $is_component_active = get_option( 'eael_save_settings', $eael_default_settings );
   // load elements
   if( $is_component_active['post-grid'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/post-grid/post-grid.php';
   }
   if( $is_component_active['post-timeline'] ) { 
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/post-timeline/post-timeline.php';
   }
   if( $is_component_active['fancy-text'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/fancy-text/fancy-text.php';
   }
   if( $is_component_active['creative-btn'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/creative-button/creative-button.php';
   }
   if( $is_component_active['count-down'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/countdown/countdown.php';
   }
   if( $is_component_active['team-members'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/team-members/team-members.php';
   }
   if( $is_component_active['testimonials'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/testimonials/testimonials.php';
   }

   if ( function_exists( 'WC' ) && $is_component_active['product-grid'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/product-grid/product-grid.php';
   }

   if ( function_exists( 'wpcf7' ) && $is_component_active['contact-form-7'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/contact-form-7/contact-form-7.php';
   }

   if ( function_exists( 'WeForms' ) && $is_component_active['weforms'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/weforms/weforms.php';
   }
}
add_action('elementor/widgets/widgets_registered','add_eael_elements');


function essential_addons_el_enqueue(){
   $is_component_active = get_option( 'eael_save_settings' );
   wp_enqueue_style('essential_addons_elementor-css',ESSENTIAL_ADDONS_EL_URL.'assets/css/essential-addons-elementor.css');

   if( $is_component_active['fancy-text'] ) {
      wp_enqueue_script('essential_addons_elementor-fancy-text-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/fancy-text.js', array('jquery'),'1.0', true);
   }
   
   if( $is_component_active['count-down'] ) {
      wp_enqueue_script('essential_addons_elementor-countdown-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/countdown.min.js', array('jquery'),'1.0', true);
   }
   
}
add_action( 'wp_enqueue_scripts', 'essential_addons_el_enqueue' );


// Editor CSS

add_action( 'elementor/editor/before_enqueue_scripts', function() {
   
   wp_register_style( 'essential_addons_elementor_editor-css', ESSENTIAL_ADDONS_EL_URL.'assets/css/essential-addons-editor.css');
   wp_enqueue_style( 'essential_addons_elementor_editor-css' );
   
} );

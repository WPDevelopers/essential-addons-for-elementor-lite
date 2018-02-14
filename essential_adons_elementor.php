<?php
/**
 * Plugin Name: Essential Addons for Elementor
 * Description: The ultimate elements library for Elementor page builder plugin for WordPress.
 * Plugin URI: https://essential-addons.com/elementor/
 * Author: Codetic
 * Version: 2.4.1
 * Author URI: https://www.codetic.net
 *
 * Text Domain: essential-addons-elementor
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ESSENTIAL_ADDONS_EL_URL', plugins_url( '/', __FILE__ ) );
define( 'ESSENTIAL_ADDONS_EL_PATH', plugin_dir_path( __FILE__ ) );


require_once ESSENTIAL_ADDONS_EL_PATH.'includes/elementor-helper.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'includes/queries.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'includes/class-plugin-usage-tracker.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'admin/settings.php';

/**
 * This function will return true for all activated modules
 *
 * @since   v2.4.1
 */
function eael_activated_modules() {

   $eael_default_keys = [ 'contact-form-7', 'count-down', 'creative-btn', 'fancy-text', 'img-comparison', 'instagram-gallery', 'interactive-promo',  'lightbox', 'post-block', 'post-grid', 'post-timeline', 'product-grid', 'team-members', 'testimonial-slider', 'testimonials', 'testimonials', 'weforms', 'static-product', 'call-to-action', 'flip-box', 'info-box', 'dual-header', 'price-table', 'flip-carousel', 'interactive-cards', 'ninja-form', 'gravity-form', 'caldera-form', 'wisdom_registered_setting', 'twitter-feed', 'facebook-feed', 'data-table', 'filter-gallery' ];

   $eael_default_settings  = array_fill_keys( $eael_default_keys, true );
   $eael_get_settings      = get_option( 'eael_save_settings', $eael_default_settings );
   $eael_new_settings      = array_diff_key( $eael_default_settings, $eael_get_settings );

   if( ! empty( $eael_new_settings ) ) {
      $eael_updated_settings = array_merge( $eael_get_settings, $eael_new_settings );
      update_option( 'eael_save_settings', $eael_updated_settings );
   }

   return $eael_get_settings = get_option( 'eael_save_settings', $eael_default_settings );

}

/**
 * Acivate or Deactivate Modules
 *
 * @since v1.0.0
 */
function add_eael_elements(){

   $eael_default_keys = [ 'contact-form-7', 'count-down', 'creative-btn', 'fancy-text', 'img-comparison', 'instagram-gallery', 'interactive-promo',  'lightbox', 'post-block', 'post-grid', 'post-timeline', 'product-grid', 'team-members', 'testimonial-slider', 'testimonials', 'testimonials', 'weforms', 'static-product', 'call-to-action', 'flip-box', 'info-box', 'dual-header', 'price-table', 'flip-carousel', 'interactive-cards', 'ninja-form', 'gravity-form', 'caldera-form', 'wisdom_registered_setting', 'twitter-feed', 'facebook-feed', 'data-table', 'filter-gallery' ];
   $eael_default_settings = array_fill_keys( $eael_default_keys, true );

   $is_component_active = eael_activated_modules();

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

   if( $is_component_active['info-box'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/infobox/infobox.php';
   }

   if( $is_component_active['flip-box'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/flipbox/flipbox.php';
   }

   if( $is_component_active['call-to-action'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/call-to-action/call-to-action.php';
   }

   if( $is_component_active['dual-header'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/dual-color-header/dual-color-header.php';
   }
   if( $is_component_active['price-table'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/pricing-table/pricing-table.php';
   }
   if( function_exists( 'Ninja_Forms' ) && $is_component_active['ninja-form'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/ninja-form/ninja-form.php';
   }
   if( class_exists( 'GFForms' ) && $is_component_active['gravity-form'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/gravity-form/gravity-form.php';
   }
   if( class_exists( 'Caldera_Forms' ) && $is_component_active['caldera-form'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/caldera-forms/caldera-forms.php';
   }
   if( $is_component_active['twitter-feed'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/twitter-feed/twitter-feed.php';
   }
   if( $is_component_active['facebook-feed'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/facebook-feed/facebook-feed.php';
   }
   if( $is_component_active['data-table'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/data-table/data-table.php';
   }
   if( $is_component_active['filter-gallery'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/filterable-gallery/filterable-gallery.php';
   }
}
add_action('elementor/widgets/widgets_registered','add_eael_elements');

/**
 * Load module's scripts and styles if any module is active.
 *
 * @since v1.0.0
 */
function essential_addons_el_enqueue(){
   $is_component_active = eael_activated_modules();
   wp_enqueue_style('essential_addons_elementor-css',ESSENTIAL_ADDONS_EL_URL.'assets/css/essential-addons-elementor.css');

   if( $is_component_active['fancy-text'] ) {
      wp_enqueue_script('essential_addons_elementor-fancy-text-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/fancy-text.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['count-down'] ) {
      wp_enqueue_script('essential_addons_elementor-countdown-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/countdown.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['post-grid'] || $is_component_active['twitter-feed'] || $is_component_active['facebook-feed'] ) {
      wp_enqueue_script('essential_addons_elementor-masonry-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/masonry.min.js', array('jquery'),'1.0', true);
   }
   if(  $is_component_active['post-grid'] || $is_component_active['post-timeline'] ) {
      wp_enqueue_script('essential_addons_elementor-load-more-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/load-more.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['twitter-feed']) {
      wp_enqueue_script('essential_addons_elementor-codebird-js',ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/codebird.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['twitter-feed'] || $is_component_active['facebook-feed'] ) {
      wp_enqueue_script('essential_addons_elementor-doT-js',ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/doT.min.js', array('jquery'),'1.0', true);
      wp_enqueue_script('essential_addons_elementor-moment-js',ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/moment.js', array('jquery'),'1.0', true);
      wp_enqueue_script('essential_addons_elementor-socialfeed-js',ESSENTIAL_ADDONS_EL_URL.'assets/social-feeds/jquery.socialfeed.js', array('jquery'),'1.0', true);
   }

   if( $is_component_active['filter-gallery'] ) {
      wp_enqueue_script('essential_addons_mixitup-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/mixitup.min.js', array('jquery'),'1.0', true);
      wp_enqueue_script('essential_addons_magnific-popup-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/jquery.magnific-popup.min.js', array('jquery'),'1.0', true);
   }

}
add_action( 'wp_enqueue_scripts', 'essential_addons_el_enqueue' );


/**
 * Editor Css
 */
add_action( 'elementor/editor/before_enqueue_scripts', function() {

   wp_register_style( 'essential_addons_elementor_editor-css', ESSENTIAL_ADDONS_EL_URL.'assets/css/essential-addons-editor.css');
   wp_enqueue_style( 'essential_addons_elementor_editor-css' );

} );

/**
 * Creates an Action Menu
 */
function eael_add_settings_link( $links ) {
    $settings_link = sprintf( '<a href="admin.php?page=eael-settings">' . __( 'Settings' ) . '</a>' );
    $go_pro_link = sprintf( '<a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank" style="color: #39b54a; font-weight: bold;">' . __( 'Go Pro' ) . '</a>' );
    array_push( $links, $settings_link, $go_pro_link );
   return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'eael_add_settings_link' );

/**
 * Activation redirects
 *
 * @since v1.0.0
 */
function eael_activate() {
    add_option('eael_do_activation_redirect', true);
}
register_activation_hook(__FILE__, 'eael_activate');

/**
 * Redirect to options page
 *
 * @since v1.0.0
 */
function eael_redirect() {
    if (get_option('eael_do_activation_redirect', false)) {
        delete_option('eael_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("admin.php?page=eael-settings");
        }
    }
}
add_action('admin_init', 'eael_redirect');

/**
 * Optional usage tracker
 *
 * @since v1.0.0
 */
if( ! class_exists( 'Eael_Plugin_Usage_Tracker') ) {
    require_once dirname( __FILE__ ) . '/includes/class-plugin-usage-tracker.php';
}
if( ! function_exists( 'essential_addons_elementor_lite_start_plugin_tracking' ) ) {
    function essential_addons_elementor_lite_start_plugin_tracking() {
        $wisdom = new Eael_Plugin_Usage_Tracker(
            __FILE__,
            'https://wpdeveloper.net',
            array(),
            true,
            true,
            1
        );
    }
    essential_addons_elementor_lite_start_plugin_tracking();
}

/**
 * Admin Notice
 *
 * @since v1.0.0
 */
function eael_admin_notice() {
  if ( current_user_can( 'install_plugins' ) ) {
    global $current_user ;
    $user_id = $current_user->ID;
    /* Check that the user hasn't already clicked to ignore the message */
    if ( ! get_user_meta($user_id, 'eael_ignore_notice241') ) {
      echo '<div class="eael-admin-notice updated"><p>';
      echo '<img style="width: 15px; padding-right: 6px; float: left" src="' . plugins_url( '/', __FILE__ ).'admin/assets/images/gift-icon.png'. '" > ';
      printf(__('Last chance to grab the <strong>New Year Deal!!</strong> Enjoy <strong>25&#37;</strong> discount on <strong>Essential Addons for Elementor Pro.</strong> Use the coupon <strong>"NewYear"</strong><a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank" style="text-decoration: none;"><span class="dashicons dashicons-tickets-alt" style="margin-top: -1px;margin-left: 10px;"></span> Grab the Deal</a>
        <a href="%1$s" style="text-decoration: none; margin-left: 10px;"><span class="dashicons dashicons-dismiss"></span> No Thanks</a>'),  admin_url( 'admin.php?page=eael-settings&eael_nag_ignore=0' ));
      echo "</p></div>";
    }
  }
}
add_action('admin_notices', 'eael_admin_notice');


/**
 * Nag Ignore
 */
function eael_nag_ignore() {
  global $current_user;
        $user_id = $current_user->ID;
        /* If user clicks to ignore the notice, add that to their user meta */
        if ( isset($_GET['eael_nag_ignore']) && '0' == $_GET['eael_nag_ignore'] ) {
             add_user_meta($user_id, 'eael_ignore_notice241', 'true', true);
  }
}
add_action('admin_init', 'eael_nag_ignore');
<?php
/**
 * Plugin Name: Essential Addons for Elementor
 * Description: The ultimate elements library for Elementor page builder plugin for WordPress.
 * Plugin URI: https://essential-addons.com/elementor/
 * Author: WPDeveloper
 * Version: 2.9.3
 * Author URI: https://wpdeveloper.net/
 *
 * Text Domain: essential-addons-elementor
 * Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ESSENTIAL_ADDONS_EL_URL', plugins_url( '/', __FILE__ ) );
define( 'ESSENTIAL_ADDONS_EL_PATH', plugin_dir_path( __FILE__ ) );
define( 'ESSENTIAL_ADDONS_EL_ROOT', __FILE__ );
define( 'ESSENTIAL_ADDONS_VERSION', '2.9.3' );
define( 'ESSENTIAL_ADDONS_STABLE_VERSION', '2.9.3' );
define( 'ESSENTIAL_ADDONS_BASENAME', plugin_basename( __FILE__ ) );


require_once ESSENTIAL_ADDONS_EL_PATH.'includes/elementor-helper.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'includes/queries.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'includes/class-plugin-usage-tracker.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'includes/version-rollback.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'includes/maintennance.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'includes/eael-rollback.php';
require_once ESSENTIAL_ADDONS_EL_PATH.'admin/settings.php';
require_once ESSENTIAL_ADDONS_EL_PATH .'includes/extensions.php';

/**
 * This function will return true for all activated modules
 *
 * @since   v2.4.1
 */
function eael_activated_modules() {

   $eael_default_keys = [ 'contact-form-7', 'count-down', 'creative-btn', 'fancy-text', 'img-comparison', 'instagram-gallery', 'interactive-promo',  'lightbox', 'post-block', 'post-grid', 'post-timeline', 'product-grid', 'team-members', 'testimonial-slider', 'testimonials', 'testimonials', 'weforms', 'static-product', 'call-to-action', 'flip-box', 'info-box', 'dual-header', 'price-table', 'flip-carousel', 'interactive-cards', 'ninja-form', 'gravity-form', 'caldera-form', 'wisdom_registered_setting', 'twitter-feed', 'facebook-feed', 'data-table', 'filter-gallery', 'image-accordion','content-ticker', 'tooltip', 'adv-accordion', 'adv-tabs', 'progress-bar', 'section-particles' ];
   
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
 * Load acivate or deactivate Modules
 *
 * @since v1.0.0
 */
function add_eael_extensions() {
	$is_component_active = eael_activated_modules();

	if( $is_component_active['section-particles'] ) {
		require_once ESSENTIAL_ADDONS_EL_PATH .'extensions/eael-particle-section/eael-particle-section.php';
	}
}
add_eael_extensions();

/**
 * Acivate or Deactivate Modules
 *
 * @since v1.0.0
 */
function add_eael_elements() {

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
   if( class_exists( '\WPForms\WPForms' ) && $is_component_active['wpforms'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/wpforms/wpforms.php';
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
   if( $is_component_active['image-accordion'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/image-accordion/image-accordion.php';
   }
   if( $is_component_active['content-ticker'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/content-ticker/content-ticker.php';
   }
   if( $is_component_active['tooltip'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/tooltip/tooltip.php';
   }
   if( $is_component_active['adv-accordion'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/advance-accordion/advance-accordion.php';
   }
   if( $is_component_active['adv-tabs'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/advance-tabs/advance-tabs.php';
   }
   if( $is_component_active['progress-bar'] ) {
      require_once ESSENTIAL_ADDONS_EL_PATH.'elements/progress-bar/progress-bar.php';
   }
}
add_action('elementor/widgets/widgets_registered','add_eael_elements');

/**
 * Registering a Group Control for All Posts Element
 */
function eae_posts_register_control( $controls_manager ){
	include_once ESSENTIAL_ADDONS_EL_PATH . 'includes/eae-posts-group-control.php';
    $controls_manager->add_group_control( 'eaeposts', new Elementor\EAE_Posts_Group_Control() );
}

add_action( 'elementor/controls/controls_registered', 'eae_posts_register_control' );

/**
 * Load module's scripts and styles if any module is active.
 *
 * @since v1.0.0
 */
function essential_addons_el_enqueue(){
    $is_component_active = eael_activated_modules();
    wp_enqueue_style('essential_addons_elementor-css',ESSENTIAL_ADDONS_EL_URL.'assets/css/essential-addons-elementor.css');
    wp_enqueue_script('eael-scripts',ESSENTIAL_ADDONS_EL_URL.'assets/js/eael-scripts.js', array('jquery'),'1.0', true);
    if ( class_exists( 'GFCommon' ) ) {
        foreach( eael_select_gravity_form() as $form_id => $form_name ){
            if ( $form_id != '0' ) {
                gravity_form_enqueue_scripts( $form_id );
            }
        };
    }
    if ( function_exists( 'wpforms' ) ) {
     wpforms()->frontend->assets_css();
    }
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
        $eael_js_settings = array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
        );
        wp_localize_script( 'essential_addons_elementor-load-more-js', 'eaelPostGrid', $eael_js_settings );
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

      wp_register_script('essential_addons_isotope-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/isotope.pkgd.min.js', array('jquery'),'1.0', true);

	    wp_register_script('jquery-resize', ESSENTIAL_ADDONS_EL_URL.'assets/js/jquery.resize.min.js', array('jquery'), '1.0', true);
    }

    if( $is_component_active['price-table'] ) {
		wp_enqueue_style('essential_addons_elementor-tooltipster',ESSENTIAL_ADDONS_EL_URL.'assets/css/tooltipster.bundle.min.css');
		wp_enqueue_script('essential_addons_elementor-tooltipster-js',ESSENTIAL_ADDONS_EL_URL.'assets/js/tooltipster.bundle.min.js', array('jquery'),'1.0', true);
    }
    
    if( $is_component_active['progress-bar'] ) {
		wp_enqueue_script('essential_addons_elementor-progress-bar',ESSENTIAL_ADDONS_EL_URL.'assets/js/loading-bar.min.js', array('jquery'),'1.0', true);
   }
   if( $is_component_active['section-particles'] ) {
		wp_enqueue_script(
         'particles-js', ESSENTIAL_ADDONS_EL_URL.'assets/js/particles.js',
         ['jquery'], '1.0', true
      );

		$preset_themes = require ESSENTIAL_ADDONS_EL_PATH.'extensions/eael-particle-section/particle-themes.php';
		wp_localize_script( 'particles-js', 'ParticleThemesData', $preset_themes );
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


function eael_init() {
    if ( class_exists( 'Caldera_Forms' ) ) {
        add_filter( 'caldera_forms_force_enqueue_styles_early', '__return_true' );
    }
   /**
    * Check if Elementor is Installed or not
    */
   if( ! function_exists( 'eael_is_elementor_active' ) ) :
      function eael_is_elementor_active() {
         if ( did_action( 'elementor/loaded' ) ) {
            return true;
         }

         return false;
      }
   endif;

   /**
    * This notice will appear if Elementor is not installed or activated or both
    */
   function eael_is_failed_to_load() {
      $elementor = 'elementor/elementor.php';
      if( eael_is_elementor_active() ) {
         if( ! current_user_can( 'activate_plugins' ) ) {
            return;
         }
         $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor );
         $message = __( '<strong>Essential Addons for Elementor</strong> requires <strong>Elementor</strong> plugin to be active. Please activate Elementor to continue.', 'essential-addons-elementor' );
         $button_text = __( 'Activate Elementor', 'essential-addons-elementor' );
      } else {
         if( ! current_user_can( 'activate_plugins' ) ) {
            return;
         }
         $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
         $message = sprintf( __( '<strong>Essential Addons for Elementor</strong> requires <strong>Elementor</strong> plugin to be installed and activated. Please install Elementor to continue.', 'essential-addons-elementor' ), '<strong>', '</strong>' );
         $button_text = __( 'Install Elementor', 'essential-addons-elementor' );
      }
      $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
      printf( '<div class="error"><p>%1$s</p>%2$s</div>', __( $message ), $button );
   }

   if( ! did_action( 'elementor/loaded' ) ) {
      add_action( 'admin_notices', 'eael_is_failed_to_load' );
   }
}
add_action( 'plugins_loaded', 'eael_init' );
require_once dirname( __FILE__ ) . '/includes/class-wpdev-notices.php';
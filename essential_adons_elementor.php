<?php
/**
 * Plugin Name: Essential Addons for Elementor
 * Description: The ultimate elements library for Elementor page builder plugin for WordPress.
 * Plugin URI: https://essential-addons.com/elementor/
 * Author: WPDeveloper
 * Version: 2.9.9
 * Author URI: https://wpdeveloper.net/
 *
 * Text Domain: essential-addons-elementor
 * Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists( 'Essential_Addons_EL') ) {
   class Essential_Addons_EL {

      /**
       * Instance of this class
       * 
       * @access protected
       */
      protected static $_instance = null;

      /**
       * Get instance of this class
       * 
       * @return Essential_Addons_EL
       */
      public static function get_instance() {
         if ( is_null( self::$_instance ) ) {
               self::$_instance = new self();
         }
         
         return self::$_instance;
      }

      /**
      * Constract of this class
      */
      public function __construct() {

         $this->define_constants();
         $this->inclulde_files();

         add_action( 'elementor/controls/controls_registered', array($this, 'eae_posts_register_control') );

         $plugin = plugin_basename( __FILE__ );
         add_filter( "plugin_action_links_$plugin", array($this, 'eael_add_settings_link') );
         add_action('admin_init', array($this, 'eael_redirect'));
         add_action( 'admin_footer-plugins.php', array($this, 'plugins_footer_for_pro') );

         if( ! class_exists( 'Eael_Plugin_Usage_Tracker') ) {
            require_once dirname( __FILE__ ) . '/includes/class-plugin-usage-tracker.php';
         }
         $this->essential_addons_elementor_lite_start_plugin_tracking();

         add_filter('plugin_action_links_essential-addons-elementor/essential_adons_elementor.php', array($this, 'eae_pro_filter_action_links'));
         if( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', array($this, 'eael_is_failed_to_load') );
         }

         if ( class_exists( 'Caldera_Forms' ) ) {
            add_filter( 'caldera_forms_force_enqueue_styles_early', '__return_true' );
         }

         add_action( 'elementor/editor/before_enqueue_scripts', array($this, 'eae_before_enqueue_scripts'));

      }

      /**
      * Defining constances
      * 
      * @access public
      */
      public function define_constants() {
         define( 'ESSENTIAL_ADDONS_EL_URL', plugins_url( '/', __FILE__ ) );
         define( 'ESSENTIAL_ADDONS_EL_PATH', plugin_dir_path( __FILE__ ) );
         define( 'ESSENTIAL_ADDONS_EL_ROOT', __FILE__ );
         define( 'ESSENTIAL_ADDONS_VERSION', '2.9.9' );
         define( 'ESSENTIAL_ADDONS_STABLE_VERSION', '2.9.9' );
         define( 'ESSENTIAL_ADDONS_BASENAME', plugin_basename( __FILE__ ) );
      }

      public function include_path( $file ) {
         $file = ltrim( $file, '/' );
         
         return ESSENTIAL_ADDONS_EL_PATH.'includes/' . $file;
      }

      public function inclulde_files() {

         require_once $this->include_path('elementor-helper.php');
         require_once $this->include_path('class-eae-scripts.php');
         require_once $this->include_path('queries.php');
         require_once $this->include_path('class-eae-elements.php');
         require_once $this->include_path('class-plugin-usage-tracker.php');
         require_once ESSENTIAL_ADDONS_EL_PATH.'admin/settings.php';
         require_once dirname( __FILE__ ) . '/includes/class-wpdev-notices.php';
      }

      /**
       * This function will return true for all activated modules
      *
      * @since   v2.4.1
      */
      public static function eael_activated_modules() {

         $eael_default_keys = [ 'contact-form-7', 'count-down', 'creative-btn', 'fancy-text', 'img-comparison', 'instagram-gallery', 'interactive-promo',  'lightbox', 'post-block', 'post-grid', 'post-timeline', 'product-grid', 'team-members', 'testimonial-slider', 'testimonials', 'testimonials', 'weforms', 'static-product', 'call-to-action', 'flip-box', 'info-box', 'dual-header', 'price-table', 'flip-carousel', 'interactive-cards', 'ninja-form', 'gravity-form', 'caldera-form', 'twitter-feed', 'data-table', 'filter-gallery', 'image-accordion','content-ticker', 'tooltip', 'adv-accordion', 'adv-tabs', 'progress-bar', 'feature-list' ];
         
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
       * Registering a Group Control for All Posts Element
      */
      public function eae_posts_register_control( $controls_manager ){
         include_once ESSENTIAL_ADDONS_EL_PATH . 'includes/eae-posts-group-control.php';
         $controls_manager->add_group_control( 'eaeposts', new Elementor\EAE_Posts_Group_Control() );
      }

      /**
       * Creates an Action Menu
       */
      public function eael_add_settings_link( $links ) {
         $settings_link = sprintf( '<a href="admin.php?page=eael-settings">' . __( 'Settings' ) . '</a>' );
         $go_pro_link = sprintf( '<a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" target="_blank" style="color: #39b54a; font-weight: bold;">' . __( 'Go Pro' ) . '</a>' );
         array_push( $links, $settings_link, $go_pro_link );
         return $links;
      }

      /**
       * Redirect to options page
      *
      * @since v1.0.0
      */
      public function eael_redirect() {
         if (get_option('eael_do_activation_redirect', false)) {
            delete_option('eael_do_activation_redirect');
            if(!isset($_GET['activate-multi']))
            {
                  wp_redirect("admin.php?page=eael-settings");
            }
         }
      }

      public function plugins_footer_for_pro(){
         ?>
         <script>
            jQuery(document).ready(function( $ ){
               $('#eae-pro-activation').on('click', function( e ){
                  e.preventDefault();
                  swal({
                     title: '<h2>Please <span style="color: red">Deactivate</span> <br><br> Free Version</h2>',
                     type: 'error',
                     html:
                        'You don\'t need the <span style="color: #1abc9c;font-weight: 700;">Free Version</span> to use the <span style="color: #00629a;font-weight: 700;">Premium</span> one.',
                     showCloseButton: true,
                     showCancelButton: false,
                     focusConfirm: false,
                  }).catch(swal.noop);
               });
            });
         </script>
         <?php
      }

      /**
       * Optional usage tracker
      *
      * @since v1.0.0
      */
      public function essential_addons_elementor_lite_start_plugin_tracking() {
         $wpins = new Eael_Plugin_Usage_Tracker(
            __FILE__,
            'http://app.wpdeveloper.net',
            array(),
            true,
            true,
            1
         );
      }

      public function eae_pro_filter_action_links( $links ) {
         if( ! function_exists( 'get_plugins' ) ) {
            include ABSPATH . '/wp-admin/includes/plugin.php';
         }
         $activate_plugins = get_option( 'active_plugins' );
         if( in_array( plugin_basename( __FILE__ ), $activate_plugins ) ) {
            $pro_plugin_base_name = 'essential-addons-elementor/essential_adons_elementor.php';
            if( isset( $links['activate'] ) ) {
               $activate_link = $links['activate'];
               // Insert an onClick action to allow form before deactivating
               $activation_link = str_replace( '<a ', '<a id="eae-pro-activation" onclick="javascript:event.preventDefault();"', $activate_link );
               $links['activate'] = $activation_link;
            }
            return $links;
         }
      }


      public function eael_is_elementor_active() {
         $file_path = 'elementor/elementor.php';
         if ( ! function_exists( 'get_plugins' ) ) {
            include ABSPATH . '/wp-admin/includes/plugin.php';
			}
         $installed_plugins = get_plugins();
         return isset( $installed_plugins[$file_path] );
      }


       /**
       * This notice will appear if Elementor is not installed or activated or both
      */
      public function eael_is_failed_to_load() {
         $elementor = 'elementor/elementor.php';
         if( $this->eael_is_elementor_active() ) {
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

      public function eae_before_enqueue_scripts() {
         wp_register_style( 'essential_addons_elementor_editor-css', ESSENTIAL_ADDONS_EL_URL.'assets/css/essential-addons-editor.css');
         wp_enqueue_style( 'essential_addons_elementor_editor-css' );
      }


   }

   function run_essential_addons() {
      return Essential_Addons_EL::get_instance();
   }
   add_action( 'plugins_loaded', 'run_essential_addons', 25 );

}


/**
 * Activation redirects
 *
 * @since v1.0.0
 */
function eael_activate() {
    add_option('eael_do_activation_redirect', true);
}
register_activation_hook(__FILE__, 'eael_activate');
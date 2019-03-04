<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if( ! class_exists('Essential_Addons_Elements') ) {
    class Essential_Addons_Elements {

        /**
         * Instance of this class
         * 
         * @access protected
         */
        protected static $_instance = null;

        /**
         * Get instance of this class
         * 
         * @return Essential_Addons_Elements
         */
        public static function get_instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            
            return self::$_instance;
        }

        public function __construct() {
            add_action('elementor/widgets/widgets_registered', array($this, 'add_eael_elements'));
        }

        /**
         * Acivate or Deactivate Modules
         *
        * @since v1.0.0
        */
        function add_eael_elements() {

            $is_component_active = Essential_Addons_EL::eael_activated_modules();

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
            if( $is_component_active['feature-list'] ) {
            require_once ESSENTIAL_ADDONS_EL_PATH.'elements/feature-list/feature-list.php';
            }
        }


    }
}

Essential_Addons_Elements::get_instance();
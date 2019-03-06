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

        public function elements_path( $file ) {
            $file = ltrim( $file, '/' );
            $file = ESSENTIAL_ADDONS_EL_PATH.'elements/'.$file.'/'.$file.'.php';
            if(file_exists($file)) {
                return $file;
            }
            return false;
        }

        /**
         * Acivate or Deactivate Modules
         *
        * @since v1.0.0
        */
        function add_eael_elements() {

            $elements = [
                [ 'name'  => 'post-grid' ],
                [ 'name'  => 'post-timeline' ],
                [ 'name'  => 'fancy-text' ],
                [ 'name'  => 'creative-btn' ],
                [ 'name'  => 'count-down' ],
                [ 'name'  => 'team-members' ],
                [ 'name'  => 'testimonials' ],
                [ 'name'  => 'info-box' ],
                [ 'name'  => 'flip-box' ],
                [ 'name'  => 'call-to-action' ],
                [ 'name'  => 'dual-header' ],
                [ 'name'  => 'price-table' ],
                [ 'name'  => 'twitter-feed' ],
                [ 'name'  => 'data-table' ],
                [ 'name'  => 'filter-gallery' ],
                [ 'name'  => 'image-accordion' ],
                [ 'name'  => 'content-ticker' ],
                [ 'name'  => 'tooltip' ],
                [ 'name'  => 'adv-accordion' ],
                [ 'name'  => 'adv-tabs' ],
                [ 'name'  => 'progress-bar' ],
                [ 'name'  => 'feature-list' ],
                [
                    'name'      => 'product-grid',
                    'condition' => [
                        'function_exists',
                        'WC'
                    ]
                ],
                [
                    'name'      => 'contact-form-7',
                    'condition' => [
                        'function_exists',
                        'wpcf7'
                    ]
                ],
                [
                    'name'      => 'weforms',
                    'condition' => [
                        'function_exists',
                        'WeForms'
                    ]
                ],
                [
                    'name'      => 'ninja-form',
                    'condition' => [
                        'function_exists',
                        'Ninja_Forms'
                    ]
                ],
                [
                    'name'      => 'gravity-form',
                    'condition' => [
                        'class_exists',
                        'GFForms'
                    ]
                ],
                [
                    'name'      => 'caldera-form',
                    'condition' => [
                        'class_exists',
                        'Caldera_Forms'
                    ]
                ],
                [
                    'name'      => 'wpforms',
                    'condition' => [
                        'class_exists',
                        '\WPForms\WPForms'
                    ]
                ]
            ];

            $is_component_active = Essential_Addons_EL::eael_activated_modules();
            $ea_elements = apply_filters( 'add_eae_element', $elements );
            
            foreach($ea_elements as $element) {
                if( isset($element['condition']) ) {
                    if( ($element['condition'][0]($element['condition'][1])) && $is_component_active[$element['name']] ) {
                        require_once $this->elements_path($element['name']);
                    }
                }else {
                    if($is_component_active[$element['name']]) {
                        require_once $this->elements_path($element['name']);
                    }
                }
            }
        }


    }
}

Essential_Addons_Elements::get_instance();
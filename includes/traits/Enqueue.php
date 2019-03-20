<?php

namespace EssentialAddonsElementor\Traits;

use Essential_Addons_EL;
use MatthiasMullie\Minify;

trait Enqueue
{
    public function generate_editor_scripts($elements)
    {
        // $active_components = $this->get_settings();
        $paths = array();

        foreach ($elements as $key => $component) {
            switch ($key) {
                case 'adv-accordion':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'adv-tabs':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'fancy-text':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/fancy-text/fancy-text.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'count-down':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/countdown/countdown.min.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;
                
                case 'data-table':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'filterable-gallery':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/isotope/isotope.pkgd.min.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/magnific-popup/jquery.magnific-popup.min.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'image-accordion':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'post-timeline':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/vendor/load-more/load-more.js';
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'price-table':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'progress-bar':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;

                case 'twitter-feed':
                    $paths[] = ESSENTIAL_ADDONS_EL_PATH . 'assets/js/' . $key . '/index.js';
                    break;
            }
        }

        $minifier = new Minify\JS($paths);
        $minifier->minify(WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'eael.js');

    }

    public function scripts_to_be_enq() {
        wp_enqueue_style(
            'essential_addons_elementor-css',
            ESSENTIAL_ADDONS_EL_URL.'assets/css/essential-addons-elementor.css'
        );

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

        // localize script
    }
}

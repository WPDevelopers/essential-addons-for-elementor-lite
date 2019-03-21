<?php

namespace EssentialAddonsElementor\Traits;

use Essential_Addons_EL;
use MatthiasMullie\Minify;

trait Enqueue
{
    public function generate_editor_style($elements)
    {
        $paths = array();

        foreach( $elements as $element ) {
            $file = $this->plugin_path . "assets/css/$element.css";
            if( file_exists($file) ) {
                $paths[] = $file;
            }
        }
        
        $minifier = new Minify\CSS($paths);
        $minifier->minify(WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'essential-addons/eael.css');
    }

    public function generate_editor_scripts($elements)
    {
        $paths = array();

        foreach ($elements as $element) {
            if($element == 'fancy-text') {
                $paths[] = $this->plugin_path . 'assets/js/vendor/fancy-text/fancy-text.js';
            }elseif($element == 'count-down') {
                $paths[] = $this->plugin_path . 'assets/js/vendor/countdown/countdown.min.js';
            }elseif($element == 'filterable-gallery') {
                $paths[] = $this->plugin_path . 'assets/js/vendor/isotope/isotope.pkgd.min.js';
                $paths[] = $this->plugin_path . 'assets/js/vendor/magnific-popup/jquery.magnific-popup.min.js';
            }elseif($element == 'post-timeline') {
                $paths[] = $this->plugin_path . 'assets/js/vendor/load-more/load-more.js';
            }elseif($element == 'price-table') {
                $paths[] = $this->plugin_path . 'assets/js/vendor/tooltipster/tooltipster.bundle.min.js';
            }elseif($element == 'progress-bar') {
                $paths[] = $this->plugin_path . 'assets/js/vendor/progress-bar/progress-bar.js';
            }elseif($element == 'twitter-feed') {
                $paths[] = $this->plugin_path . 'assets/js/vendor/isotope/isotope.pkgd.min.js';
                $paths[] = $this->plugin_path . 'assets/social-feeds/codebird.js';
                $paths[] = $this->plugin_path . 'assets/social-feeds/doT.min.js';
                $paths[] = $this->plugin_path . 'assets/social-feeds/moment.js';
                $paths[] = $this->plugin_path . 'assets/social-feeds/jquery.socialfeed.js';
            }elseif($element == 'post-grid') {
                $paths[] = $this->plugin_path . 'assets/js/vendor/isotope/isotope.pkgd.min.js';
                $paths[] = $this->plugin_path . 'assets/js/vendor/load-more/load-more.js';
            }

            $file = $this->plugin_path . 'assets/js/' . $element . '/index.js';
            if( file_exists($file) ) {
                $paths[] = $file;
            }

        }

        $minifier = new Minify\JS($paths);
        $minifier->minify(WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'essential-addons/eael.js');

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

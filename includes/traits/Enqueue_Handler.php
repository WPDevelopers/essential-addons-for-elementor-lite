<?php

namespace Essential_Addons_Elementor\Traits;

use Essential_Addons_EL;
use MatthiasMullie\Minify;

trait Enqueue_Handler
{
    public function generate_editor_scripts()
    {
        $active_components = Essential_Addons_EL::eael_activated_modules();
        $paths = array();

        foreach ($active_components as $key => $component) {
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
}

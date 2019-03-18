<?php

namespace Essential_Addons_Elementor\Traits;

use Essential_Addons_EL;
use MatthiasMullie\Minify;

trait Enqueue_Handler
{
    public function eael_generate_editor_scripts()
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
            }
        }
        // error_log(print_r(implode(',', $paths), true));
        $minifier = new Minify\JS($paths);
        $minifier->minify(WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'eael.js');

    }
}

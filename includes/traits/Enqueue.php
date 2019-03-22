<?php

namespace EssentialAddonsElementor\Traits;

use Essential_Addons_EL;
use MatthiasMullie\Minify;

trait Enqueue
{
    public function generate_editor_scripts($elements)
    {
        $js_paths = array();
        $css_paths = array();
        $file = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'uploads/essential-addons/';

        if(!file_exists($file)) {
            wp_mkdir_p($file);
        }

        foreach ($elements as $element) {
            if($element == 'fancy-text') {
                $js_paths[] = $this->plugin_path . 'assets/js/vendor/fancy-text/fancy-text.js';
            }elseif($element == 'count-down') {
                $js_paths[] = $this->plugin_path . 'assets/js/vendor/countdown/countdown.min.js';
            }elseif($element == 'filterable-gallery') {
                $js_paths[] = $this->plugin_path . 'assets/js/vendor/isotope/isotope.pkgd.min.js';
                $js_paths[] = $this->plugin_path . 'assets/js/vendor/magnific-popup/jquery.magnific-popup.min.js';
            }elseif($element == 'post-timeline') {
                $js_paths[] = $this->plugin_path . 'assets/js/vendor/load-more/load-more.js';
            }elseif($element == 'price-table') {
                $js_paths[] = $this->plugin_path . 'assets/js/vendor/tooltipster/tooltipster.bundle.min.js';
            }elseif($element == 'progress-bar') {
                $js_paths[] = $this->plugin_path . 'assets/js/vendor/inview/inview.min.js';
                $js_paths[] = $this->plugin_path . 'assets/js/vendor/progress-bar/progress-bar.js';
            }elseif($element == 'twitter-feed') {
                $js_paths[] = $this->plugin_path . 'assets/js/vendor/isotope/isotope.pkgd.min.js';
                $js_paths[] = $this->plugin_path . 'assets/social-feeds/codebird.js';
                $js_paths[] = $this->plugin_path . 'assets/social-feeds/doT.min.js';
                $js_paths[] = $this->plugin_path . 'assets/social-feeds/moment.js';
                $js_paths[] = $this->plugin_path . 'assets/social-feeds/jquery.socialfeed.js';
            }elseif($element == 'post-grid') {
                $js_paths[] = $this->plugin_path . 'assets/js/vendor/isotope/isotope.pkgd.min.js';
                $js_paths[] = $this->plugin_path . 'assets/js/vendor/load-more/load-more.js';
            }

            $js_file = $this->plugin_path . 'assets/js/' . $element . '/index.js';
            $js_paths[] = $this->plugin_path . 'assets/js/scripts.js';
            if( file_exists($js_file) ) {
                $js_paths[] = $js_file;
            }

            $css_file = $this->plugin_path . "assets/css/$element.css";
            if( file_exists($css_file) ) {
                $css_paths[] = $css_file;
            }

        }

        $minifier = new Minify\JS($js_paths);
        file_put_contents($file.'eael.min.js', $minifier->minify());
        
        $minifier = new Minify\CSS($css_paths);
        file_put_contents($file.'eael.min.css', $minifier->minify());
    }
}

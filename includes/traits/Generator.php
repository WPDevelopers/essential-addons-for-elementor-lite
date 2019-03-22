<?php

namespace EssentialAddonsElementor\Traits;

use MatthiasMullie\Minify;

trait Generator
{
    public function generate_editor_scripts($elements, $post_id = null)
    {
        $js_paths = array();
        $css_paths = array();

        if (!file_exists($this->asset_path)) {
            wp_mkdir_p($this->asset_path);
        }

        foreach ($elements as $element) {
            if ($element == 'fancy-text') {
                $js_paths[] = $this->plugin_path . 'assets/front-end/js/vendor/fancy-text/fancy-text.js';
            } elseif ($element == 'count-down') {
                $js_paths[] = $this->plugin_path . 'assets/front-end/js/vendor/countdown/countdown.min.js';
            } elseif ($element == 'filterable-gallery') {
                $js_paths[] = $this->plugin_path . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js';
                $js_paths[] = $this->plugin_path . 'assets/front-end/js/vendor/magnific-popup/jquery.magnific-popup.min.js';
            } elseif ($element == 'post-timeline') {
                $js_paths[] = $this->plugin_path . 'assets/front-end/js/vendor/load-more/load-more.js';
            } elseif ($element == 'price-table') {
                $js_paths[] = $this->plugin_path . 'assets/front-end/js/vendor/tooltipster/tooltipster.bundle.min.js';
            } elseif ($element == 'progress-bar') {
                $js_paths[] = $this->plugin_path . 'assets/front-end/js/vendor/progress-bar/progress-bar.js';
            } elseif ($element == 'twitter-feed') {
                $js_paths[] = $this->plugin_path . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js';
                $js_paths[] = $this->plugin_path . 'assets/front-end/social-feeds/codebird.js';
                $js_paths[] = $this->plugin_path . 'assets/front-end/social-feeds/doT.min.js';
                $js_paths[] = $this->plugin_path . 'assets/front-end/social-feeds/moment.js';
                $js_paths[] = $this->plugin_path . 'assets/front-end/social-feeds/jquery.socialfeed.js';
            } elseif ($element == 'post-grid') {
                $js_paths[] = $this->plugin_path . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js';
                $js_paths[] = $this->plugin_path . 'assets/front-end/js/vendor/load-more/load-more.js';
            }

            $js_file = $this->plugin_path . 'assets/front-end/js/' . $element . '/index.js';
            $js_paths[] = $this->plugin_path . 'assets/front-end/js/base.js';
            if (file_exists($js_file)) {
                $js_paths[] = $js_file;
            }

            $css_file = $this->plugin_path . "assets/front-end/css/$element.css";
            if (file_exists($css_file)) {
                $css_paths[] = $css_file;
            }

        }

        $minifier = new Minify\JS($js_paths);
        file_put_contents($this->asset_path . DIRECTORY_SEPARATOR . ($post_id ? $post_id : 'eael') . '.min.js', $minifier->minify());

        $minifier = new Minify\CSS($css_paths);
        file_put_contents($this->asset_path . DIRECTORY_SEPARATOR . ($post_id ? $post_id : 'eael') . '.min.css', $minifier->minify());
    }

    public function generate_post_scripts($editor_data)
    {
        error_log($editor_data);
    }
}

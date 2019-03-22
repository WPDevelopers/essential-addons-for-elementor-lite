<?php

namespace EssentialAddonsElementor\Traits;

use MatthiasMullie\Minify;

trait Generator
{
    public function generate_scripts($elements, $output = null)
    {
        $js_paths = array();
        $css_paths = array();

        if (!file_exists($this->asset_path)) {
            wp_mkdir_p($this->asset_path);
        }

        foreach ((array) $elements as $element) {
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
                $js_paths[] = $this->plugin_path . 'assets/front-end/js/vendor/inview/inview.min.js';
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
        file_put_contents($this->asset_path . DIRECTORY_SEPARATOR . ($output ? $output : 'eael') . '.min.js', $minifier->minify());

        $minifier = new Minify\CSS($css_paths);
        file_put_contents($this->asset_path . DIRECTORY_SEPARATOR . ($output ? $output : 'eael') . '.min.css', $minifier->minify());
    }

    public function generate_post_scripts($post_id)
    {
        $post_data = get_metadata('post', $post_id, '_elementor_data');
        $elements = array();

        if (!empty($post_data)) {
            $sections = json_decode($post_data[0]);

            foreach ((array) $sections as $section) {
                foreach ((array) $section->elements as $element) {
                    foreach ((array) $element->elements as $widget) {
                        if (@$widget->widgetType) {
                            $elements[] = $widget->widgetType;
                        } else {
                            foreach ((array) $widget as $inner_section) {
                                foreach ((array) $inner_section as $inner_elements) {
                                    foreach ((array) $inner_elements->elements as $inner_widget) {
                                        if ($inner_widget->widgetType) {
                                            $elements[] = $inner_widget->widgetType;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $elements = array_intersect($this->registered_elements, array_map(function ($val) {
                return preg_replace('/^eael-/', '', $val);
            }, $elements));

            
            $this->generate_scripts($elements, $post_id);
        }
    }
}

<?php
namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use MatthiasMullie\Minify;

trait Generator
{
    /**
     * Define js dependencies
     *
     * @since 3.0.0
     */
    public $dependencies = array(
        'fancy-text' => array(
            'assets/front-end/js/vendor/fancy-text/fancy-text.js',
        ),
        'countdown' => array(
            'assets/front-end/js/vendor/countdown/countdown.min.js',
        ),
        'filterable-gallery' => array(
            'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
            'assets/front-end/js/vendor/magnific-popup/jquery.magnific-popup.min.js',
        ),
        'post-timeline' => array(
            'assets/front-end/js/vendor/load-more/load-more.js',
        ),
        'pricing-table' => array(
            'assets/front-end/js/vendor/tooltipster/tooltipster.bundle.min.js',
        ),
        'progress-bar' => array(
            'assets/front-end/js/vendor/progress-bar/progress-bar.js',
            'assets/front-end/js/vendor/inview/inview.min.js',
        ),
        'twitter-feed' => array(
            'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
            'assets/front-end/social-feeds/codebird.js',
            'assets/front-end/social-feeds/doT.min.js',
            'assets/front-end/social-feeds/moment.js',
            'assets/front-end/social-feeds/jquery.socialfeed.js',
        ),
        'post-grid' => array(
            'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
            'assets/front-end/js/vendor/load-more/load-more.js',
        ),
    );

    /**
     * Collect dependencies for modules
     *
     * @since 3.0.0
     */
    public function add_dependency(array $elements, array $paths)
    {
        foreach ($elements as $element) {
            if (isset($this->dependencies[$element])) {
                foreach ($this->dependencies[$element] as $path) {
                    $paths[] = EAEL_PLUGIN_PATH . $path;
                }
            }
        }
        return array_unique($paths);
    }

    /**
     * Generate scripts and minify.
     *
     * @since 3.0.0
     */
    public function generate_scripts($elements, $output = null)
    {
        if (empty($elements)) {
            return;
        }

        // if folder not exists, create new folder
        if (!file_exists(EAEL_ASSET_PATH)) {
            wp_mkdir_p(EAEL_ASSET_PATH);
        }

        // collect eael js
        $js_paths = array(
            EAEL_PLUGIN_PATH . 'assets/front-end/js/general.js',
        );
        $css_paths = array(
            EAEL_PLUGIN_PATH . "assets/front-end/css/general.css",
        );

        // collect library scripts
        if ($this->add_dependency($elements, $js_paths)) {
            $js_paths[] = $this->add_dependency($elements, $js_paths);
        }

        foreach ((array) $elements as $element) {
            $js_file = EAEL_PLUGIN_PATH . 'assets/front-end/js/' . $element . '/index.js';
            if (file_exists($js_file)) {
                $js_paths[] = $js_file;
            }

            $css_file = EAEL_PLUGIN_PATH . "assets/front-end/css/$element.css";
            if (file_exists($css_file)) {
                $css_paths[] = $css_file;
            }
        }

        $minifier = new Minify\JS($js_paths);
        file_put_contents(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($output ? $output : 'eael') . '.min.js', $minifier->minify());

        $minifier = new Minify\CSS($css_paths);
        file_put_contents(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($output ? $output : 'eael') . '.min.css', $minifier->minify());
    }

    /**
     * Generate single post scripts
     *
     * @since 3.0.0
     */
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

            $elements = array_intersect(array_keys($this->registered_elements), array_map(function ($val) {
                return preg_replace('/^eael-/', '', $val);
            }, $elements));

            $this->generate_scripts($elements, 'eael-' . $post_id);
        }
    }
}

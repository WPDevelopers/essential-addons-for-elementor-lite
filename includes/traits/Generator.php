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
    public $js_dependencies = array(
        'fancy-text' => array(
            'assets/front-end/js/vendor/fancy-text/fancy-text.js',
        ),
        'count-down' => array(
            'assets/front-end/js/vendor/count-down/count-down.min.js',
        ),
        'filter-gallery' => array(
            'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
            'assets/front-end/js/vendor/magnific-popup/jquery.magnific-popup.min.js',
        ),
        'post-timeline' => array(
            'assets/front-end/js/vendor/load-more/load-more.js',
        ),
        'price-table' => array(
            'assets/front-end/js/vendor/tooltipster/tooltipster.bundle.min.js',
        ),
        'progress-bar' => array(
            'assets/front-end/js/vendor/progress-bar/progress-bar.min.js',
            'assets/front-end/js/vendor/inview/inview.min.js',
        ),
        'twitter-feed' => array(
            'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
            'assets/front-end/js/vendor/social-feeds/codebird.js',
            'assets/front-end/js/vendor/social-feeds/doT.min.js',
            'assets/front-end/js/vendor/social-feeds/moment.js',
            'assets/front-end/js/vendor/social-feeds/jquery.socialfeed.js',
        ),
        'post-grid' => array(
            'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
            'assets/front-end/js/vendor/load-more/load-more.js',
        ),
    );

    /**
     * Define css dependencies
     *
     * @since 3.0.0
     */
    public $css_dependencies = [
        'post-grid' => [
            'assets/front-end/css/product-grid.css',
        ],
        'filter-gallery' => [
            'assets/front-end/css/magnific-popup.css',
        ],
    ];

    /**
     * Collect dependencies for modules
     *
     * @since 3.0.0
     */
    public function generate_dependency(array $elements, array $deps)
    {
        $paths = [];
        foreach ($elements as $element) {
            if (isset($deps[$element])) {
                foreach ($deps[$element] as $path) {
                    $paths[] = EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . $path;
                }
            }
        }

        return array_unique($paths);
    }

    /**
     * Search elements in a post.
     *
     * @since 3.0.0
     */
    public function widgets_in_post($post_id)
    {
        $elements = array();
        $sections = json_decode(get_post_meta($post_id, '_elementor_data', true));

        if (empty($sections)) {
            return $elements;
        }

        foreach ((array) $sections as $section) {
            foreach ((array) $section->elements as $element) {
                if (empty($element->elements)) {
                    continue;
                }
                foreach ((array) $element->elements as $widget) {
                    if (@$widget->widgetType) {
                        $elements[] = $widget->widgetType;
                    } else {
                        foreach ((array) $widget as $inner_section) {
                            foreach ((array) $inner_section as $inner_elements) {
                                if (empty($inner_elements->elements)) {
                                    continue;
                                }
                                foreach ((array) $inner_elements->elements as $inner_widget) {
                                    if (@$inner_widget->widgetType) {
                                        $elements[] = $inner_widget->widgetType;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $elements = array_map(function ($val) {
            $val = str_replace(['eael-'], [''], $val);

            return str_replace([
                'eicon-woocommerce',
                'countdown',
                'creative-button',
                'team-member',
                'testimonial',
                'weform',
                'cta-box',
                'dual-color-header',
                'pricing-table',
                'filterable-gallery',
            ], [
                'product-grid',
                'count-down',
                'creative-btn',
                'team-members',
                'testimonials',
                'weforms',
                'call-to-action',
                'dual-header',
                'price-table',
                'filter-gallery',
            ], $val);
        }, $elements);

        $elements = array_intersect(array_keys($this->registered_elements), $elements);

        return $elements;
    }

    /**
     * Generate scripts and minify.
     *
     * @since 3.0.0
     */
    public function generate_scripts($elements, $file_name = null)
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
            EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/general.js',
        );
        $css_paths = array(
            EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . "assets/front-end/css/general.css",
        );

        // collect library scripts
        $js_paths = array_merge($js_paths, $this->generate_dependency($elements, $this->js_dependencies));

        // collect library styles
        $css_paths = array_merge($css_paths, $this->generate_dependency($elements, $this->css_dependencies));

        foreach ((array) $elements as $element) {
            $js_file = EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/' . $element . '/index.js';
            if (file_exists($js_file)) {
                $js_paths[] = $js_file;
            }

            $css_file = EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . "assets/front-end/css/$element.css";
            if (file_exists($css_file)) {
                $css_paths[] = $css_file;
            }
        }

        $minifier = new Minify\JS($js_paths);
        file_put_contents(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($file_name ? $file_name : 'eael') . '.min.js', $minifier->minify());

        $minifier = new Minify\CSS($css_paths);
        file_put_contents(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . ($file_name ? $file_name : 'eael') . '.min.css', $minifier->minify());
    }

    /**
     * Generate single post scripts
     *
     * @since 3.0.0
     */
    public function generate_post_scripts($post_id, $elements = null)
    {
        if (!is_array($elements)) {
            $elements = $this->widgets_in_post($post_id);
        }

        if (empty($elements)) {
            $css_path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . 'eael-' . $post_id . '.min.css';
            $js_path = EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . 'eael-' . $post_id . '.min.js';

            if (file_exists($css_path)) {
                unlink($css_path);
            }

            if (file_exists($js_path)) {
                unlink($js_path);
            }
        } else {
            $this->generate_scripts($elements, 'eael-' . $post_id);
        }
    }
}

<?php
namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Plugin;

trait Generator
{

    public function uid($uid = null)
    {
        if ($uid == null) {
            if (is_front_page()) {
                $uid = 'front-page';
            } else if (is_home()) {
                $uid = 'home';
            } else if (is_post_type_archive()) {
                $post_type = get_query_var('post_type');

                if (is_array($post_type)) {
                    $post_type = reset($post_type);
                }

                $uid = 'post-type-archive-' . $post_type;
            } else if (is_category()) {
                $uid = 'category-' . get_queried_object_id();
            } else if (is_tag()) {
                $uid = 'tag-' . get_queried_object_id();
            } else if (is_tax()) {
                $uid = 'tax-' . get_queried_object_id();
            } else if (is_author()) {
                $uid = 'author-' . get_queried_object_id();
            } else if (is_date()) {
                $uid = 'date';
            } else if (is_archive()) {
                $uid = 'archive';
            } else if (is_search()) {
                $uid = 'search';
            } else if (is_404()) {
                $uid = 'error-404';
            } else if (is_single() || is_page() || is_singular()) {
                $uid = 'singular-' . get_queried_object_id();
            }
        }

        if ($uid) {
            return substr(md5($uid), 0, 9);
        }

        return $uid;
    }

    /**
     * Parse widgets from page data
     *
     * @since 3.0.0
     */
    public function parse_widgets($post_id)
    {

        if (!Plugin::$instance->db->is_built_with_elementor($post_id)) {
            return;
        }

        $replace = [
            'eicon-woocommerce' => 'eael-product-grid',
            'eael-countdown' => 'eael-count-down',
            'eael-creative-button' => 'eael-creative-btn',
            'eael-team-member' => 'eael-team-members',
            'eael-testimonial' => 'eael-testimonials',
            'eael-weform' => 'eael-weforms',
            'eael-cta-box' => 'eael-call-to-action',
            'eael-dual-color-header' => 'eael-dual-header',
            'eael-pricing-table' => 'eael-price-table',
            'eael-filterable-gallery' => 'eael-filter-gallery',
            'eael-one-page-nav' => 'eael-one-page-navigation',
            'eael-interactive-card' => 'eael-interactive-cards',
            'eael-image-comparison' => 'eael-img-comparison',
            'eael-dynamic-filterable-gallery' => 'eael-dynamic-filter-gallery',
            'eael-google-map' => 'eael-adv-google-map',
            'eael-instafeed' => 'eael-instagram-gallery',
        ];
        $global_settings = get_option('eael_global_settings');
        $document = Plugin::$instance->documents->get($post_id);
        $widgets = $this->collect_recursive_elements($document->get_elements_data());
        $widgets = array_map(function ($val) use ($replace) {
            if (array_key_exists($val, $replace)) {
                $val = $replace[$val];
            }
            return (strpos($val, 'eael-') !== false ? preg_replace('/^eael-/', '', $val) : null);
        }, $widgets);

        // collect page extensions
        if (!Shared::is_prevent_load_extension($post_id)) {
            if ($document->get_settings('eael_custom_js')) {
                $widgets[] = 'eael-custom-js';
            }

            if ($document->get_settings('eael_ext_reading_progress') == 'yes' || isset($global_settings['reading_progress']['enabled'])) {
                $widgets[] = 'eael-reading-progress';
            }

            if ($document->get_settings('eael_ext_table_of_content') == 'yes' || isset($global_settings['eael_ext_table_of_content']['enabled'])) {
                $widgets[] = 'eael-table-of-content';
            }
        }

        return array_filter(array_unique($widgets));
    }

    /**
     * Traverse in element data recursively
     *
     * @since 3.0.0
     */
    public function collect_recursive_elements($elements)
    {
        $collections = [];

        foreach ($elements as $element) {
            // collect extensions for section
            if (isset($element['elType']) && $element['elType'] == 'section') {
                if (isset($element['settings']['eael_particle_switch']) && $element['settings']['eael_particle_switch'] == 'yes') {
                    $collections[] = 'eael-section-particles';
                }
                if (isset($element['settings']['eael_parallax_switcher']) && $element['settings']['eael_parallax_switcher'] == 'yes') {
                    $collections[] = 'eael-section-parallax';
                }
            }

            // collect widget
            if (isset($element['elType']) && $element['elType'] == 'widget') {
                // collect extensions for widget
                if (isset($element['settings']['eael_tooltip_section_enable']) && $element['settings']['eael_tooltip_section_enable'] == 'yes') {
                    $collections[] = 'eael-eael-tooltip-section';
                }
                if (isset($element['settings']['eael_ext_content_protection']) && $element['settings']['eael_ext_content_protection'] == 'yes') {
                    $collections[] = 'eael-eael-content-protection';
                }

                if ($element['widgetType'] === 'global') {
                    $document = Plugin::$instance->documents->get($element['templateID']);

                    if (is_object($document)) {
                        $collections = array_merge($collections, $this->collect_recursive_elements($document->get_elements_data()));
                    }
                } else {
                    $collections[] = $element['widgetType'];
                }
            }

            if (!empty($element['elements'])) {
                $collections = array_merge($collections, $this->collect_recursive_elements($element['elements']));
            }
        }

        return $collections;
    }

    /**
     * Generate scripts file.
     *
     * @since 3.0.0
     */
    public function generate_script($widgets, $context, $ext)
    {
        // if folder not exists, create new folder
        if (!file_exists(EAEL_ASSET_PATH)) {
            wp_mkdir_p(EAEL_ASSET_PATH);
        }

        // collect library scripts & styles
        $paths = $this->generate_dependency($widgets, $context, $ext);

        // combine files
        $this->combine_files($paths, $context, $ext);
    }

    /**
     * Generate scripts strings.
     *
     * @since 3.0.0
     */
    public function generate_strings($widgets, $context, $ext)
    {
        $output = '';
        $paths = $this->generate_dependency($widgets, $context, $ext);

        if (!empty($paths)) {
            foreach ($paths as $path) {
                $output .= file_get_contents($this->safe_path($path));
            }
        }

        if ($this->loaded_templates && $context == 'view' && $ext == 'js') {
            foreach ($this->loaded_templates as $post_id) {
                if (get_post_status($post_id) === false) {
                    continue;
                }
                
                $document = Plugin::$instance->documents->get($post_id);

                if ($custom_js = $document->get_settings('eael_custom_js')) {
                    $output .= $custom_js;
                }
            }
        }

        return $output;
    }

    /**
     * Collect dependencies for modules
     *
     * @since 3.0.0
     */
    public function generate_dependency(array $elements, $context, $type)
    {
        $lib = ['view' => [], 'edit' => []];
        $self = ['general' => [], 'view' => [], 'edit' => []];

        if ($type == 'js') {
            $self['general'][] = EAEL_PLUGIN_PATH . 'assets/front-end/js/view/general.min.js';
            $self['edit'][] = EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/promotion.min.js';
        } else if ($type == 'css') {
            $self['view'][] = EAEL_PLUGIN_PATH . "assets/front-end/css/view/general.min.css";
        }

        foreach ($elements as $element) {
            if (isset($this->registered_elements[$element])) {
                if (!empty($this->registered_elements[$element]['dependency'][$type])) {
                    foreach ($this->registered_elements[$element]['dependency'][$type] as $file) {
                        ${$file['type']}[$file['context']][] = $file['file'];
                    }
                }
            } elseif (isset($this->registered_extensions[$element])) {
                if (!empty($this->registered_extensions[$element]['dependency'][$type])) {
                    foreach ($this->registered_extensions[$element]['dependency'][$type] as $file) {
                        ${$file['type']}[$file['context']][] = $file['file'];
                    }
                }
            }
        }

        if ($context == 'view') {
            return array_unique(array_merge($lib['view'], $self['general'], $self['view']));
        }

        return array_unique(array_merge($lib['view'], $lib['edit'], $self['general'], $self['edit'], $self['view']));
    }

    /**
     * Combine files into one
     *
     * @since 3.0.1
     */
    public function combine_files($paths = [], $context, $ext)
    {
        $output = '';
        $file_name = ($context == 'view' ? $this->uid() : $this->uid('eael')) . '.min.' . $ext;

        if (!empty($paths)) {
            foreach ($paths as $path) {
                $output .= file_get_contents($this->safe_path($path));
            }
        }

        if ($this->loaded_templates && $context == 'view' && $ext == 'js') {
            foreach ($this->loaded_templates as $post_id) {
                if (get_post_status($post_id) === false) {
                    continue;
                }

                $document = Plugin::$instance->documents->get($post_id);

                if ($custom_js = $document->get_settings('eael_custom_js')) {
                    $output .= $custom_js;
                }
            }
        }

        file_put_contents($this->safe_path(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . $file_name), $output);
    }
}

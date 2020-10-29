<?php
namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Plugin;

trait Generator
{
    public function init_request_data()
    {
        if (!apply_filters('eael/is_plugin_active', 'elementor/elementor.php')) {
            return;
        }

        if (is_admin()) {
            return;
        }

        if ($this->is_running_background()) {
            return;
        }

        $uid = null;

        if ($this->is_preview_mode()) {
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
            } elseif (is_year()) {
                $uid = 'year-' . get_the_date('y');
            } elseif (is_month()) {
                $uid = 'month-' . get_the_date('m-y');
            } elseif (is_day()) {
                $uid = 'day-' . get_the_date('j-m-y');
            } else if (is_archive()) {
                $uid = 'archive-' . get_queried_object_id();
            } else if (is_search()) {
                $uid = 'search';
            } else if (is_single() || is_page() || is_singular()) {
                $uid = 'singular-' . get_queried_object_id();
            } else if (is_404()) {
                $uid = 'error-404';
            }
        } elseif ($this->is_edit_mode()) {
            $uid = 'eael';
        }

        // set request uid
        if ($uid && $this->uid == null) {
            $this->uid = substr(md5($uid), 0, 9);
            $this->request_requires_update = $this->request_requires_update();
        }
    }

    public function request_requires_update()
    {
        $elements = get_transient($this->uid . '_elements');
        $editor_updated_at = get_transient('eael_editor_updated_at');
        $post_updated_at = get_transient($this->uid . '_updated_at');

        if ($elements === false) {
            return true;
        }
        if ($editor_updated_at === false) {
            return true;
        }
        if ($post_updated_at === false) {
            return true;
        }
        if ($editor_updated_at != $post_updated_at) {
            return true;
        }

        return false;
    }

    public function collect_loaded_templates($content, $post_id)
    {
        if ($this->is_running_background()) {
            return $content;
        }

        if ($this->request_requires_update && $this->is_preview_mode()) {
            // loaded template stack
            $this->loaded_templates[] = $post_id;

            // loaded elements stack
            $this->loaded_elements = array_merge($this->loaded_elements, $this->collect_elements_in_content($content));

            // loaded custom js string
            $this->collect_elements_in_document($post_id);
        }

        return $content;
    }

    public function collect_elements_in_content($elements)
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
                    $collections[] = 'eael-tooltip-section';
                }
                if (isset($element['settings']['eael_ext_content_protection']) && $element['settings']['eael_ext_content_protection'] == 'yes') {
                    $collections[] = 'eael-content-protection';
                }

                if ($element['widgetType'] === 'global') {
                    $document = Plugin::$instance->documents->get($element['templateID']);

                    if (is_object($document)) {
                        $collections = array_merge($collections, $this->collect_elements_in_content($document->get_elements_data()));
                    }
                } else {
                    $collections[] = $element['widgetType'];
                }
            }

            if (!empty($element['elements'])) {
                $collections = array_merge($collections, $this->collect_elements_in_content($element['elements']));
            }
        }

        return $collections;
    }

    public function collect_elements_in_document($post_id)
    {
        if (!Plugin::$instance->db->is_built_with_elementor($post_id)) {
            return;
        }

        $document = Plugin::$instance->documents->get($post_id);

        if ($document->get_settings('eael_custom_js')) {
            $this->custom_js_strings .= $document->get_settings('eael_custom_js');
        }
    }

    public function update_request_data()
    {
        if (!apply_filters('eael/is_plugin_active', 'elementor/elementor.php')) {
            return;
        }

        if ($this->is_running_background()) {
            return;
        }

        if ($this->uid === null) {
            return;
        }

        if (!$this->is_preview_mode()) {
            return;
        }

        if (!$this->request_requires_update) {
            return;
        }

        // check if already updated
        if (get_transient('eael_editor_updated_at') == get_transient($this->uid . '_updated_at')) {
            return;
        }

        // parse loaded elements
        $this->loaded_elements = $this->parse_elements($this->loaded_elements);

        // update page data
        set_transient($this->uid . '_elements', $this->loaded_elements);
        set_transient($this->uid . '_custom_js', $this->custom_js_strings);
        set_transient($this->uid . '_updated_at', get_transient('eael_editor_updated_at'));

        // remove old cache files
        $this->remove_files($this->uid);

        // output custom js as fallback
        if ($this->custom_js_strings) {
            echo '<script>' . $this->custom_js_strings . '</script>';
        }
    }

    /**
     * Parse widgets from page data
     *
     * @since 3.0.0
     */
    public function parse_elements($widgets)
    {
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

        $widgets = array_map(function ($val) use ($replace) {
            if (array_key_exists($val, $replace)) {
                $val = $replace[$val];
            }
            return (strpos($val, 'eael-') !== false ? preg_replace('/^eael-/', '', $val) : null);
        }, $widgets);

        return array_filter(array_unique($widgets));
    }

    /**
     * Generate scripts file.
     *
     * @since 3.0.0
     */
    public function generate_script($elements, $context, $ext)
    {
        // if folder not exists, create new folder
        if (!file_exists(EAEL_ASSET_PATH)) {
            wp_mkdir_p(EAEL_ASSET_PATH);
        }

        // naming asset file
        $file_name = ($context == 'view' ? $this->uid : $this->uid) . '.min.' . $ext;

        // output asset string
        $output = $this->generate_strings($elements, $context, $ext);

        // write to file
        file_put_contents($this->safe_path(EAEL_ASSET_PATH . DIRECTORY_SEPARATOR . $file_name), $output);
    }

    /**
     * Generate scripts strings.
     *
     * @since 3.0.0
     */
    public function generate_strings($elements, $context, $ext)
    {
        $output = '';
        $paths = $this->generate_dependency($elements, $context, $ext);

        if (!empty($paths)) {
            foreach ($paths as $path) {
                $output .= file_get_contents($this->safe_path($path));
            }
        }

        if ($this->request_requires_update == false && $context == 'view' && $ext == 'js') {
            $output .= get_transient($this->uid . '_custom_js');
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
}

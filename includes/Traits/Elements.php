<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

use Essential_Addons_Elementor\Classes\Group_Control_EA_Posts;

trait Elements
{
    /**
     * Add elementor category
     *
     * @since v1.0.0
     */
    public function register_widget_categories($elements_manager)
    {
        $elements_manager->add_category(
            'essential-addons-elementor',
            [
                'title' => __('Essential Addons', 'essential-addons-elementor'),
                'icon' => 'font',
            ], 1);
    }

    /**
     * Add new elementor group control
     *
     * @since v1.0.0
     */
    public function register_controls_group($controls_manager)
    {
        $controls_manager->add_group_control('eaeposts', new Group_Control_EA_Posts);
    }

    /**
     * Register widgets
     *
     * @since v3.0.0
     */
    public function register_elements($widgets_manager)
    {
        $active_elements = (array) $this->get_settings();

        if (empty($active_elements)) {
            return;
        }

        asort($active_elements);

        foreach ($active_elements as $active_element) {
            if (!isset($this->registered_elements[$active_element])) {
                continue;
            }

            if (isset($this->registered_elements[$active_element]['condition'])) {
                if ($this->registered_elements[$active_element]['condition'][0]($this->registered_elements[$active_element]['condition'][1]) == false) {
                    continue;
                }
            }

            $widgets_manager->register_widget_type(new $this->registered_elements[$active_element]['class']);
        }
    }

    /**
     * Register extensions
     *
     * @since v3.0.0
     */
    public function register_extensions()
    {
        $active_elements = $this->get_settings();

        if (empty($active_elements)) {
            return;
        }

        foreach ($this->registered_extensions as $key => $extension) {
            if (!in_array($key, $active_elements)) {
                continue;
            }

            new $extension['class'];
        }
    }

    /**
     * Register extensions
     *
     * @since v3.1.4
     */
    public function render_global_html()
    {
        if (is_singular() && !in_array('eael-scroll-progress', (array) $this->transient_extensions)) {
            $settings = get_option('eael_global_settings');

            if ($settings['scroll_progress']['enabled']) {
                if ($settings['scroll_progress']['display_condition'] == 'pages' && !is_page()) {
                    return;
                }
                if ($settings['scroll_progress']['display_condition'] == 'posts' && !is_post()) {
                    return;
                }
                if ($settings['scroll_progress']['display_condition'] == 'all' && !is_singular()) {
                    return;
                }

                add_filter('eael/section/after_render', function ($extensions) {
                    $extensions[] = 'eael-scroll-progress';
                    return $extensions;
                });
    
                echo '<div class="eael-scroll-progress eael-scroll-progress-' . $settings['scroll_progress']['position'] . '">
                    <div class="eael-scroll-progress-fill"></div>
                    <style scoped>
                        .eael-scroll-progress, .eael-scroll-progress .eael-scroll-progress-fill {
                            height: ' . $settings['scroll_progress']['height']['size'] . 'px;
                        }
                        .eael-scroll-progress {
                            background-color: ' . $settings['scroll_progress']['bg_color'] . ';
                        }
                        .eael-scroll-progress .eael-scroll-progress-fill {
                            background-color: ' . $settings['scroll_progress']['fill_color'] . ';
                            transition: width ' . $settings['scroll_progress']['animation_speed']['size'] . 'ms ease;
                        }
                    </style>
                </div>';
            }
        }
    }

}

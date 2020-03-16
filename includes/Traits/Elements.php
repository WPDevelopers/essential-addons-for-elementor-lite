<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

use \Elementor\Core\Settings\Manager as Settings_Manager;
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
                'title' => __('Essential Addons', 'essential-addons-for-elementor-lite'),
                'icon' => 'font',
            ], 1);
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

            if ($this->pro_enabled && \version_compare(EAEL_PRO_PLUGIN_VERSION, '3.3.0', '<')) {
                if (in_array($active_element, ['content-timeline', 'dynamic-filter-gallery', 'post-block', 'post-carousel', 'post-list'])) {
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
        $active_elements = (array) $this->get_settings();

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
     * List pro widgets
     *
     * @since v3.1.4
     */
    public function pro_elements($config)
    {

        // if ($this->pro_enabled) {
        //     return $config;
        // }

        $combine_array = array_merge($config['promotionWidgets'], [
            [
                'name' => 'eael-advanced-menu',
                'title' => __('EA Advanced Menu', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-menu-bar',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-content-timeline',
                'title' => __('EA Content Timeline', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-post-list',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-counter',
                'title' => __('EA Counter', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-counter',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-divider',
                'title' => __('EA Divider', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-divider',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-dynamic-filterable-gallery',
                'title' => __('EA Dynamic Gallery', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-gallery-grid',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-flip-carousel',
                'title' => __('EA Flip Carousel', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-carousel',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-google-map',
                'title' => __('EA Google Map', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-google-maps',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-image-comparison',
                'title' => __('EA Image Comparison', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-image-before-after',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-image-hotspots',
                'title' => __('EA Image Hotspots', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-image-hotspot',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-image-scroller',
                'title' => __('EA Image Scroller', 'essential-addons-for-elementor-lite'),
                'icon' => 'fa fa-image',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-instafeed',
                'title' => __('EA Instagram Feed', 'essential-addons-for-elementor-lite'),
                'icon' => 'fa fa-instagram',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-interactive-card',
                'title' => __('EA Interactive Card', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-menu-card',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-interactive-promo',
                'title' => __('EA Interactive Promo', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-image-box',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-learn-dash-course-list',
                'title' => __('EA LearnDash Course List', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-post-list',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-lightbox',
                'title' => __('EA Lightbox &amp; Modal', 'essential-addons-for-elementor-lite'),
                'icon' => 'fa fa-eye',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-logo-carousel',
                'title' => __('EA Logo Carousel', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-carousel',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-mailchimp',
                'title' => __('EA Mailchimp', 'essential-addons-for-elementor-lite'),
                'icon' => 'fa fa-envelope-open',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-offcanvas',
                'title' => __('EA Offcanvas', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-sidebar',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-one-page-nav',
                'title' => __('EA One Page Navigation', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-navigation-vertical',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-post-block',
                'title' => __('EA Post Block', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-posts-grid',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-post-carousel',
                'title' => __('EA Post Carousel', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-slider-push',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-post-list',
                'title' => __('EA Smart Post List', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-post-list',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-price-menu',
                'title' => __('EA Price Menu', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-price-list',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-protected-content',
                'title' => __('EA Protected Content', 'essential-addons-for-elementor-lite'),
                'icon' => 'fa fa-lock',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-static-product',
                'title' => __('EA Static Product', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-image-box',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-team-member-carousel',
                'title' => __('EA Team Member Carousel', 'essential-addons-for-elementor-lite'),
                'icon' => 'fa fa-user-o',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-testimonial-slider',
                'title' => __('EA Testimonial Slider', 'essential-addons-for-elementor-lite'),
                'icon' => 'fa fa-comments-o',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-toggle',
                'title' => __('EA Toggle', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-dual-button',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-twitter-feed-carousel',
                'title' => __('EA Twitter Feed Carousel', 'essential-addons-for-elementor-lite'),
                'icon' => 'fa fa-twitter',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-woo-collections',
                'title' => __('EA Woo Product Collections', 'essential-addons-for-elementor-lite'),
                'icon' => 'eicon-woocommerce',
                'categories' => '["essential-addons-elementor"]',
            ],
        ]);

        $config['promotionWidgets'] = $combine_array;

        return $config;
    }

    /**
     * Inject global extension html.
     *
     * @since v3.1.4
     */
    public function render_global_html()
    {
        if (is_singular() && did_action('elementor/loaded')) {
            $page_settings_manager = Settings_Manager::get_settings_managers('page');
            $page_settings_model = $page_settings_manager->get_model(get_the_ID());
            $global_settings = get_option('eael_global_settings');
            $html = '';
            $reading_progress_html = '';
            $table_of_content_html = '';

            // Reading Progress Bar
            if ($this->get_settings('eael-reading-progress') == true) {
                if ($page_settings_model->get_settings('eael_ext_reading_progress') == 'yes' || isset($global_settings['reading_progress']['enabled'])) {
                    add_filter('eael/section/after_render', function ($extensions) {
                        $extensions[] = 'eael-reading-progress';
                        return $extensions;
                    });

                    $reading_progress_html = '<div class="eael-reading-progress-wrap eael-reading-progress-wrap-' . ($page_settings_model->get_settings('eael_ext_reading_progress') == 'yes' ? 'local' : 'global') . '">
                        <div class="eael-reading-progress eael-reading-progress-local eael-reading-progress-' . $page_settings_model->get_settings('eael_ext_reading_progress_position') . '">
                            <div class="eael-reading-progress-fill"></div>
                        </div>
                        <div class="eael-reading-progress eael-reading-progress-global eael-reading-progress-' . @$global_settings['reading_progress']['position'] . '" style="height: ' . @$global_settings['reading_progress']['height']['size'] . 'px;background-color: ' . @$global_settings['reading_progress']['bg_color'] . ';">
                            <div class="eael-reading-progress-fill" style="height: ' . @$global_settings['reading_progress']['height']['size'] . 'px;background-color: ' . @$global_settings['reading_progress']['fill_color'] . ';transition: width ' . @$global_settings['reading_progress']['animation_speed']['size'] . 'ms ease;"></div>
                        </div>
                    </div>';

                    if ($page_settings_model->get_settings('eael_ext_reading_progress') != 'yes') {
                        if (get_post_status($global_settings['reading_progress']['post_id']) != 'publish') {
                            $reading_progress_html = '';
                        } else if ($global_settings['reading_progress']['display_condition'] == 'pages' && !is_page()) {
                            $reading_progress_html = '';
                        } else if ($global_settings['reading_progress']['display_condition'] == 'posts' && !is_single()) {
                            $reading_progress_html = '';
                        } else if ($global_settings['reading_progress']['display_condition'] == 'all' && !is_singular()) {
                            $reading_progress_html = '';
                        }
                    }
                }

                $html .= $reading_progress_html;
            }

            // Table of Content
            if ($this->get_settings('eael-table-of-content') == true) {
                if ($page_settings_model->get_settings('eael_ext_table_of_content') == 'yes' || isset($global_settings['eael_ext_table_of_content']['enabled'])) {
                    add_filter('eael/section/after_render', function ($extensions) {
                        $extensions[] = 'eael-table-of-content';
                        return $extensions;
                    });

                    $el_class = 'eael-toc eael-toc-disable';

                    if ($page_settings_model->get_settings('eael_ext_table_of_content') != 'yes' && isset($global_settings['eael_ext_table_of_content']['enabled'])) {
                        $toc_settings = $page_settings_model;
                        $el_class .= ' eael-toc-global';
                        $this->eael_toc_global_css($page_settings_model, $global_settings);
                    }
                    $icon = 'fas fa-list';
                    $support_tag = (array) $this->eael_get_extension_settings($page_settings_model, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_supported_heading_tag');

                    $support_tag = implode(',', array_filter($support_tag));
                    $position = $this->eael_get_extension_settings($page_settings_model, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_position');
                    $close_bt_text_style = $this->eael_get_extension_settings($page_settings_model, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_close_button_text_style');
                    $box_shadow = $this->eael_get_extension_settings($page_settings_model, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_box_shadow');
                    $auto_collapse = $this->eael_get_extension_settings($page_settings_model, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_auto_collapse');
                    $toc_style = $this->eael_get_extension_settings($page_settings_model, $global_settings, 'eael_ext_table_of_content', 'eael_ext_table_of_content_list_style');
                    $toc_word_wrap = $this->eael_get_extension_settings($page_settings_model, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_word_wrap');
                    $toc_collapse = $this->eael_get_extension_settings($page_settings_model, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_collapse_sub_heading');
                    $list_icon = $this->eael_get_extension_settings($page_settings_model, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_list_icon');
                    $toc_title = $this->eael_get_extension_settings($page_settings_model, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_title');
                    $icon_check = $this->eael_get_extension_settings($page_settings_model, $global_settings, 'eael_ext_table_of_content', 'eael_ext_table_of_content_header_icon');
                    $sticky_scroll = $this->eael_get_extension_settings($page_settings_model, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_sticky_scroll');

                    $el_class .= ($position == 'right') ? ' eael-toc-right' : ' ';
                    $el_class .= ($close_bt_text_style == 'bottom_to_top') ? ' eael-bottom-to-top' : ' ';
                    $el_class .= ($auto_collapse == 'yes') ? ' eael-toc-auto-collapse' : ' ';
                    $el_class .= ($box_shadow == 'yes') ? ' eael-box-shadow' : ' ';
                    $toc_style_class = ' eael-toc-list-' . $toc_style;
                    $toc_style_class .= ($toc_collapse == 'yes') ? ' eael-toc-collapse' : ' ';
                    $toc_style_class .= ($list_icon == 'number') ? ' eael-toc-number' : ' eael-toc-bullet';
                    $toc_style_class .= ($toc_word_wrap == 'yes') ? ' eael-toc-word-wrap' : ' ';

                    if (!empty($icon_check['value'])) {
                        $icon = $icon_check['value'];
                    }

                    $table_of_content_html .= "<div data-eaelTocTag='{$support_tag}' data-stickyScroll='{$sticky_scroll['size']}' id='eael-toc' class='{$el_class} '>
                        <div class='eael-toc-header'>
                                <span class='eael-toc-close'>×</span>
                                <h2 class='eael-toc-title'>{$toc_title}</h2>
                        </div>
                        <div class='eael-toc-body'>
                            <ul id='eael-toc-list' class='eael-toc-list {$toc_style_class}'></ul>
                        </div>
                        <button class='eael-toc-button'><i class='{$icon}'></i><span>{$toc_title}</span></button>
                    </div>";
                }

                if ($page_settings_model->get_settings('eael_ext_table_of_content') != 'yes' && isset($global_settings['eael_ext_table_of_content']['post_id'])) {
                    if (get_post_status($global_settings['eael_ext_table_of_content']['post_id']) != 'publish') {
                        $table_of_content_html = '';
                    } else if ($global_settings['eael_ext_table_of_content']['display_condition'] == 'pages' && !is_page()) {
                        $table_of_content_html = '';
                    } else if ($global_settings['eael_ext_table_of_content']['display_condition'] == 'posts' && !is_single()) {
                        $table_of_content_html = '';
                    } else if ($global_settings['eael_ext_table_of_content']['display_condition'] == 'all' && !is_singular()) {
                        $table_of_content_html = '';
                    }
                }

                $html .= $table_of_content_html;
            }

            echo $html;
        }
    }
}

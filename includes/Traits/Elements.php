<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

use \Elementor\Plugin;

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
                $check = false;

                if (isset($this->registered_elements[$active_element]['condition'][2])) {
                    $check = $this->registered_elements[$active_element]['condition'][2];
                }

                if ($this->registered_elements[$active_element]['condition'][0]($this->registered_elements[$active_element]['condition'][1]) == $check) {
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

        // set promotion extension enabled
        array_push($active_elements, 'eael-promotion');

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
    public function promote_pro_elements($config)
    {

        if ($this->pro_enabled) {
            return $config;
        }

        $promotion_widgets = [];

        if (isset($config['promotionWidgets'])) {
            $promotion_widgets = $config['promotionWidgets'];
        }

        $combine_array = array_merge($promotion_widgets, [
            [
                'name' => 'eael-advanced-menu',
                'title' => __('Advanced Menu', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-advanced-menu',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-content-timeline',
                'title' => __('Content Timeline', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-content-timeline',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-counter',
                'title' => __('Counter', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-counter',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-divider',
                'title' => __('Divider', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-divider',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-dynamic-filterable-gallery',
                'title' => __('Dynamic Gallery', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-dynamic-gallery',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-flip-carousel',
                'title' => __('Flip Carousel', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-flip-carousel',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-google-map',
                'title' => __('Google Map', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-advanced-google-maps',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-image-comparison',
                'title' => __('Image Comparison', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-image-comparison',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-image-hotspots',
                'title' => __('Image Hotspots', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-image-hotspots',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-image-scroller',
                'title' => __('Image Scroller', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-image-scroller',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-instafeed',
                'title' => __('Instagram Feed', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-instagram-feed',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-interactive-card',
                'title' => __('Interactive Card', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-interactive-cards',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-interactive-promo',
                'title' => __('Interactive Promo', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-interactive-promo',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-learn-dash-course-list',
                'title' => __('LearnDash Course List', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-learndash',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-lightbox',
                'title' => __('Lightbox & Modal', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-lightbox-modal',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-logo-carousel',
                'title' => __('Logo Carousel', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-logo-carousel',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-mailchimp',
                'title' => __('Mailchimp', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-mailchimp',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-offcanvas',
                'title' => __('Offcanvas', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-offcanvas',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-one-page-nav',
                'title' => __('One Page Navigation', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-one-page-navigaton',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-post-block',
                'title' => __('Post Block', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-post-block',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-post-carousel',
                'title' => __('Post Carousel', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-post-carousel',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-post-list',
                'title' => __('Smart Post List', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-smart-post-list',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-price-menu',
                'title' => __('Price Menu', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-price-menu',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-protected-content',
                'title' => __('Protected Content', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-protected-content',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-static-product',
                'title' => __('Static Product', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-static-product',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-team-member-carousel',
                'title' => __('Team Member Carousel', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-team-member-carousel',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-testimonial-slider',
                'title' => __('Testimonial Slider', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-testimonial-slider',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-toggle',
                'title' => __('Toggle', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-content-toggle',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-twitter-feed-carousel',
                'title' => __('Twitter Feed Carousel', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-twitter-feed-carousel',
                'categories' => '["essential-addons-elementor"]',
            ],
            [
                'name' => 'eael-woo-collections',
                'title' => __('Woo Product Collections', 'essential-addons-for-elementor-lite'),
                'icon' => 'eaicon-woo-product-collections',
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

        if (!did_action('elementor/loaded')) {
            return;
        }

        if ($this->is_edit_mode() && is_singular()) {
            $this->loaded_templates[] = get_the_ID();
        }

        if (empty($this->loaded_templates)) {
            return;
        }

        $html = '';
        $global_settings = get_option('eael_global_settings');

        foreach ($this->loaded_templates as $post_id) {
            if (Shared::is_prevent_load_extension($post_id)) {
                continue;
            }

            if (!Plugin::$instance->db->is_built_with_elementor($post_id)) {
                continue;
            }

            $document = Plugin::$instance->documents->get($post_id);

            // Reading Progress Bar
            if ($this->get_settings('eael-reading-progress') == true) {
                if ($document->get_settings('eael_ext_reading_progress') == 'yes' || isset($global_settings['reading_progress']['enabled'])) {
                    $reading_progress_html = '<div class="eael-reading-progress-wrap eael-reading-progress-wrap-' . ($document->get_settings('eael_ext_reading_progress') == 'yes' ? 'local' : 'global') . '">
                            <div class="eael-reading-progress eael-reading-progress-local eael-reading-progress-' . $document->get_settings('eael_ext_reading_progress_position') . '">
                                <div class="eael-reading-progress-fill"></div>
                            </div>
                            <div class="eael-reading-progress eael-reading-progress-global eael-reading-progress-' . @$global_settings['reading_progress']['position'] . '" style="height: ' . @$global_settings['reading_progress']['height']['size'] . 'px;background-color: ' . @$global_settings['reading_progress']['bg_color'] . ';">
                                <div class="eael-reading-progress-fill" style="height: ' . @$global_settings['reading_progress']['height']['size'] . 'px;background-color: ' . @$global_settings['reading_progress']['fill_color'] . ';transition: width ' . @$global_settings['reading_progress']['animation_speed']['size'] . 'ms ease;"></div>
                            </div>
                        </div>';

                    if ($document->get_settings('eael_ext_reading_progress') != 'yes') {
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

                    $html .= $reading_progress_html;
                }
            }

            // Table of Contents
            if ($this->get_settings('eael-table-of-content')) {
                if ($document->get_settings('eael_ext_table_of_content') == 'yes' || isset($global_settings['eael_ext_table_of_content']['enabled'])) {
                    $el_class = 'eael-toc eael-toc-disable';

                    if ($document->get_settings('eael_ext_table_of_content') != 'yes' && isset($global_settings['eael_ext_table_of_content']['enabled'])) {
                        $toc_settings = $document;
                        $el_class .= ' eael-toc-global';
                        $this->eael_toc_global_css($document, $global_settings);
                    }
                    $icon = 'fas fa-list';
                    $support_tag = (array) $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_supported_heading_tag');

                    $support_tag = implode(',', array_filter($support_tag));
                    $position = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_position');
                    $close_bt_text_style = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_close_button_text_style');
                    $box_shadow = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_box_shadow');
                    $auto_collapse = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_auto_collapse');
                    $title_to_url = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_use_title_in_url');
                    $toc_style = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_table_of_content_list_style');
                    $toc_word_wrap = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_word_wrap');
                    $toc_collapse = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_collapse_sub_heading');
                    $list_icon = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_list_icon');
                    $toc_title = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_title');
                    $icon_check = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_table_of_content_header_icon');
                    $sticky_scroll = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_sticky_scroll');
                    $hide_mobile = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_hide_in_mobile');
                    $content_selector = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_ext_toc_content_selector');
                    $exclude_selector = $this->eael_get_extension_settings($document, $global_settings, 'eael_ext_table_of_content', 'eael_toc_exclude_selector');

                    $el_class .= ($position == 'right') ? ' eael-toc-right' : ' ';
                    $el_class .= ($close_bt_text_style == 'bottom_to_top') ? ' eael-bottom-to-top' : ' ';
                    $el_class .= ($auto_collapse == 'yes') ? ' eael-toc-auto-collapse' : ' ';
                    $el_class .= ($box_shadow == 'yes') ? ' eael-box-shadow' : ' ';
                    $el_class .= ($hide_mobile == 'yes') ? ' eael-toc-mobile-hide' : ' ';

                    $toc_style_class = ' eael-toc-list-' . $toc_style;
                    $toc_style_class .= ($toc_collapse == 'yes') ? ' eael-toc-collapse' : ' ';
                    $toc_style_class .= ($list_icon == 'number') ? ' eael-toc-number' : ' eael-toc-bullet';
                    $toc_style_class .= ($toc_word_wrap == 'yes') ? ' eael-toc-word-wrap' : ' ';
                    $title_url = ($title_to_url == 'yes') ? 'true' : 'false';

                    if (!empty($icon_check['value'])) {
                        $icon = $icon_check['value'];
                    }

                    $table_of_content_html = "<div data-eaelTocTag='{$support_tag}' data-contentSelector='{$content_selector}' data-excludeSelector='{$exclude_selector}' data-stickyScroll='{$sticky_scroll['size']}' data-titleUrl='{$title_url}' id='eael-toc' class='{$el_class} '>
                            <div class='eael-toc-header'>
                                    <span class='eael-toc-close'>×</span>
                                    <h2 class='eael-toc-title'>{$toc_title}</h2>
                            </div>
                            <div class='eael-toc-body'>
                                <ul id='eael-toc-list' class='eael-toc-list {$toc_style_class}'></ul>
                            </div>
                            <button class='eael-toc-button'><i class='{$icon}'></i><span>{$toc_title}</span></button>
                        </div>";

                    if ($document->get_settings('eael_ext_table_of_content') != 'yes') {
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
            }
        }

        echo $html;
    }

    /**
     * Register WC Hooks
     */

    public function register_wc_hooks()
    {

        if (class_exists('WooCommerce')) {
            wc()->frontend_includes();
        }

    }

}

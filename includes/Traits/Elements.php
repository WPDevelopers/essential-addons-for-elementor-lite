<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

use \Elementor\Plugin;

trait Elements
{
    public $extensions_data = [];

    /**
     * Register custom controls
     *
     * @since v4.4.2
     */
    public function register_controls($controls_manager)
    {
        $controls_manager->register_control('eael-select2', new \Essential_Addons_Elementor\Controls\Select2());
    }

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
        array_push($active_elements, 'promotion');

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
	        [
		        'name' => 'eael-woo-product-slider',
		        'title' => __('Woo Product Slider', 'essential-addons-for-elementor-lite'),
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
        if (!apply_filters('eael/is_plugin_active', 'elementor/elementor.php')) {
            return;
        }

        if (!is_singular()) {
            return;
        }

        $post_id = get_the_ID();
        $html = '';
        $global_settings = $setting_data = $document = [];

        if ($this->get_settings('reading-progress') || $this->get_settings('table-of-content')) {
            $html = '';
            $global_settings = get_option('eael_global_settings');
            $document = Plugin::$instance->documents->get($post_id, false);

            if (is_object($document)) {
                $settings_data = $document->get_settings();
            }
        }

        // Reading Progress Bar
        if ($this->get_settings('reading-progress') == true) {
            $reading_progress_status = $global_reading_progress = false;

            if (isset($settings_data['eael_ext_reading_progress']) && $settings_data['eael_ext_reading_progress'] == 'yes') {
                $reading_progress_status = true;
            } elseif (isset($global_settings['reading_progress']['enabled']) && $global_settings['reading_progress']['enabled']) {
                $reading_progress_status = true;
                $global_reading_progress = true;
                $settings_data = $global_settings['reading_progress'];
            }

            if ($reading_progress_status) {
                $this->extensions_data = $settings_data;
                $progress_height = !empty($settings_data['eael_ext_reading_progress_height']['size']) ? $settings_data['eael_ext_reading_progress_height']['size'] : '';
                $animation_speed = !empty($settings_data['eael_ext_reading_progress_animation_speed']['size']) ? $settings_data['eael_ext_reading_progress_animation_speed']['size'] : '';

                $reading_progress_html = '<div class="eael-reading-progress-wrap eael-reading-progress-wrap-' . ($this->get_extensions_value('eael_ext_reading_progress') == 'yes' ? 'local' : 'global') . '">';

                if ($global_reading_progress) {
                    $reading_progress_html .= '<div class="eael-reading-progress eael-reading-progress-global eael-reading-progress-' . $this->get_extensions_value('eael_ext_reading_progress_position') . '" style="height: ' . $progress_height . 'px;background-color: ' . $this->get_extensions_value('eael_ext_reading_progress_bg_color') . ';">
                        <div class="eael-reading-progress-fill" style="height: ' . $progress_height . 'px;background-color: ' . $this->get_extensions_value('eael_ext_reading_progress_fill_color') . ';transition: width ' . $animation_speed . 'ms ease;"></div>
                    </div>';
                } else {
                    $reading_progress_html .= '<div class="eael-reading-progress eael-reading-progress-local eael-reading-progress-' . $this->get_extensions_value('eael_ext_reading_progress_position') . '">
                        <div class="eael-reading-progress-fill"></div>
                    </div>';
                }

                $reading_progress_html .= '</div>';

                if ($this->get_extensions_value('eael_ext_reading_progress') != 'yes') {
                    $display_condition = $this->get_extensions_value('eael_ext_reading_progress_global_display_condition');
                    if (get_post_status($this->get_extensions_value('post_id')) != 'publish') {
                        $reading_progress_html = '';
                    } else if ($display_condition == 'pages' && !is_page()) {
                        $reading_progress_html = '';
                    } else if ($display_condition == 'posts' && !is_single()) {
                        $reading_progress_html = '';
                    }
                }

                if (!empty($reading_progress_html)) {
                    wp_enqueue_script('eael-reading-progress');
                    wp_enqueue_style('eael-reading-progress');

                    $html .= $reading_progress_html;
                }
            }
        }

        // Table of Contents
        if ($this->get_settings('table-of-content')) {
            $toc_status = false;

            if (is_object($document)) {
                $settings_data = $document->get_settings();
            }

            if (isset($settings_data['eael_ext_table_of_content']) && $settings_data['eael_ext_table_of_content'] == 'yes') {
                $toc_status = true;
            } elseif (isset($global_settings['eael_ext_table_of_content']['enabled']) && $global_settings['eael_ext_table_of_content']['enabled']) {
                $toc_status = true;
                $settings_data = $global_settings['eael_ext_table_of_content'];
            }

            if ($toc_status) {
                $this->extensions_data = $settings_data;
                $el_class = 'eael-toc eael-toc-disable';

                if ($this->get_extensions_value('eael_ext_table_of_content') != 'yes' && !empty($settings_data['enabled'])) {
                    $el_class .= ' eael-toc-global';
                    $this->toc_global_css($global_settings);
                }

                $icon = 'fas fa-list';
                $support_tag = (array) $settings_data['eael_ext_toc_supported_heading_tag'];
                $support_tag = implode(',', array_filter($support_tag));
                $position = $settings_data['eael_ext_toc_position'];
                $page_offset = !empty($settings_data['eael_ext_toc_main_page_offset']) ? $settings_data['eael_ext_toc_main_page_offset']['size'] : 0 ;
                $close_bt_text_style = $settings_data['eael_ext_toc_close_button_text_style'];
                $auto_collapse = $settings_data['eael_ext_toc_auto_collapse'];
                $title_to_url = $settings_data['eael_ext_toc_use_title_in_url'];
                $toc_style = $settings_data['eael_ext_table_of_content_list_style'];
                $toc_word_wrap = $settings_data['eael_ext_toc_word_wrap'];
                $toc_collapse = $settings_data['eael_ext_toc_collapse_sub_heading'];
                $list_icon = $settings_data['eael_ext_toc_list_icon'];
                $toc_title = $settings_data['eael_ext_toc_title'];
                $icon_check = $settings_data['eael_ext_table_of_content_header_icon'];
                $sticky_scroll = $settings_data['eael_ext_toc_sticky_scroll'];
                $hide_mobile = $settings_data['eael_ext_toc_hide_in_mobile'];
                $content_selector = $settings_data['eael_ext_toc_content_selector'];
                $exclude_selector = $settings_data['eael_toc_exclude_selector'];

                $el_class .= ($position == 'right') ? ' eael-toc-right' : ' eael-toc-left';
                $el_class .= ($close_bt_text_style == 'bottom_to_top') ? ' eael-bottom-to-top' : ' ';
                $el_class .= ($auto_collapse == 'yes') ? ' eael-toc-auto-collapse collapsed' : ' ';
                $el_class .= ($hide_mobile == 'yes') ? ' eael-toc-mobile-hide' : ' ';

                $toc_style_class = ' eael-toc-list-' . $toc_style;
                $toc_style_class .= ($toc_collapse == 'yes') ? ' eael-toc-collapse' : ' ';
                $toc_style_class .= ($list_icon == 'number') ? ' eael-toc-number' : ' eael-toc-bullet';
                $toc_style_class .= ($toc_word_wrap == 'yes') ? ' eael-toc-word-wrap' : ' ';
                $title_url = ($title_to_url == 'yes') ? 'true' : 'false';

                if (!empty($icon_check['value'])) {
                    $icon = $icon_check['value'];
                }

                $table_of_content_html = "<div data-eaelTocTag='{$support_tag}' data-contentSelector='{$content_selector}' data-excludeSelector='{$exclude_selector}' data-stickyScroll='{$sticky_scroll['size']}' data-titleUrl='{$title_url}' data-page_offset='{$page_offset}' id='eael-toc' class='{$el_class} '>
                    <div class='eael-toc-header'>
                            <span class='eael-toc-close'>×</span>
                            <h2 class='eael-toc-title'>{$toc_title}</h2>
                    </div>
                    <div class='eael-toc-body'>
                        <ul id='eael-toc-list' class='eael-toc-list {$toc_style_class}'></ul>
                    </div>
                    <button class='eael-toc-button'><i class='{$icon}'></i><span>{$toc_title}</span></button>
                </div>";

                if ($this->get_extensions_value('eael_ext_table_of_content') != 'yes') {
                    $toc_global_display_condition = $this->get_extensions_value('eael_ext_toc_global_display_condition');
                    if (get_post_status($this->get_extensions_value('post_id')) != 'publish') {
                        $table_of_content_html = '';
                    } else if ($toc_global_display_condition == 'pages' && !is_page()) {
                        $table_of_content_html = '';
                    } else if ($toc_global_display_condition == 'posts' && !is_single()) {
                        $table_of_content_html = '';
                    }
                }

                if (!empty($table_of_content_html)) {
                    wp_enqueue_style('eael-table-of-content');
                    wp_enqueue_script('eael-table-of-content');

                    $html .= $table_of_content_html;
                }
            }
        }

        echo $html;
    }

    /**
     * @param $post_css
     * @param $elements
     * @return string|void
     */
    public function toc_global_css($global_settings)
    {
        $eael_toc = $global_settings['eael_ext_table_of_content'];
        $eael_toc_width = isset($eael_toc['eael_ext_toc_width']['size']) ? $eael_toc['eael_ext_toc_width']['size'] : 300;
        $toc_list_color_active = $eael_toc['eael_ext_table_of_content_list_text_color_active'];
        $toc_list_separator_style = $eael_toc['eael_ext_table_of_content_list_separator_style'];
        $header_padding = $eael_toc['eael_ext_toc_header_padding'];
        $body_padding = $eael_toc['eael_ext_toc_body_padding'];
        $header_typography = $this->get_typography_data('eael_ext_table_of_content_header_typography', $eael_toc);
        $list_typography = $this->get_typography_data('eael_ext_table_of_content_list_typography_normal', $eael_toc);
        $box_shadow = $eael_toc['eael_ext_toc_table_box_shadow_box_shadow'];
        $border_radius = $eael_toc['eael_ext_toc_box_border_radius']['size'];
        $bullet_size = $eael_toc['eael_ext_toc_box_list_bullet_size']['size'];
        $top_position = $eael_toc['eael_ext_toc_box_list_top_position']['size'];
        $indicator_size = $eael_toc['eael_ext_toc_indicator_size']['size'];
        $indicator_position = $eael_toc['eael_ext_toc_indicator_position']['size'];
        $close_bt_box_shadow = $eael_toc['eael_ext_table_of_content_close_button_box_shadow'];
        $toc_global_css = "
            .eael-toc-global .eael-toc-header,
            .eael-toc-global.collapsed .eael-toc-button
            {
                background-color:{$eael_toc['eael_ext_table_of_content_header_bg']};
            }

            .eael-toc-global {
                width:{$eael_toc_width}px;
                z-index:{$eael_toc['eael_ext_toc_sticky_z_index']['size']};
            }

            .eael-toc-global.eael-sticky {
                top:{$eael_toc['eael_ext_toc_sticky_offset']['size']};
            }
            .eael-toc-global .eael-toc-header .eael-toc-title,
            .eael-toc-global.collapsed .eael-toc-button
            {
                color:{$eael_toc['eael_ext_table_of_content_header_text_color']};
                $header_typography
            }
            .eael-toc-global .eael-toc-header {
                padding:{$header_padding['top']}px {$header_padding['right']}px {$header_padding['bottom']}px {$header_padding['left']}px;
            }

            .eael-toc-global .eael-toc-body {
                padding:{$body_padding['top']}px {$body_padding['right']}px {$body_padding['bottom']}px {$body_padding['left']}px;
            }

            .eael-toc-global .eael-toc-close
            {
                font-size: {$eael_toc['eael_ext_table_of_content_close_button_icon_size']['size']}px !important;
                height: {$eael_toc['eael_ext_table_of_content_close_button_size']['size']}px !important;
                width: {$eael_toc['eael_ext_table_of_content_close_button_size']['size']}px !important;
                line-height: {$eael_toc['eael_ext_table_of_content_close_button_line_height']['size']}px !important;
                color:{$eael_toc['eael_ext_table_of_content_close_button_text_color']} !important;
                background-color:{$eael_toc['eael_ext_table_of_content_close_button_bg']} !important;
                border-radius: {$eael_toc['eael_ext_table_of_content_close_button_border_radius']['size']}px !important;
                box-shadow:{$close_bt_box_shadow['horizontal']}px {$close_bt_box_shadow['vertical']}px {$close_bt_box_shadow['blur']}px {$close_bt_box_shadow['spread']}px {$close_bt_box_shadow['color']} !important;
            }

            .eael-toc-global.eael-toc:not(.collapsed)
            {
                box-shadow:{$box_shadow['horizontal']}px {$box_shadow['vertical']}px {$box_shadow['blur']}px {$box_shadow['spread']}px {$box_shadow['color']};
            }

            .eael-toc-global .eael-toc-body
            {
                background-color:{$eael_toc['eael_ext_table_of_content_body_bg']};
            }

            .eael-toc-global .eael-toc-body ul.eael-toc-list.eael-toc-bullet li:before
            {
                width:{$bullet_size}px;
                height:{$bullet_size}px;
                top:{$top_position}px;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list li,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-number li:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li a
            {
                color:{$eael_toc['eael_ext_table_of_content_list_text_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-bullet li:before {
                background-color:{$eael_toc['eael_ext_table_of_content_list_text_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list li:hover,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-number li:hover:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li:hover > a
            {
                color:{$eael_toc['eael_ext_table_of_list_hover_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list li:hover > a:before {
                border-bottom-color:{$eael_toc['eael_ext_table_of_list_hover_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-bullet li:hover:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li:hover > a:after {
                background-color:{$eael_toc['eael_ext_table_of_list_hover_color']} !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-active,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-number li.eael-highlight-active:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-active > a,
            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-parent,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-number li.eael-highlight-parent:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-parent > a
            {
                color:$toc_list_color_active !important;
            }


            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-active > a:before
            {
                border-bottom-color:$toc_list_color_active !important;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-bullet li.eael-highlight-active:before,
            .eael-toc-global .eael-toc-body .eael-toc-list li.eael-highlight-active > a:after,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-bullet li.eael-highlight-parent:before
            {
                background-color:$toc_list_color_active !important;
            }

            .eael-toc-global ul.eael-toc-list > li
            {
                color:{$eael_toc['eael_ext_table_of_content_list_separator_color']} !important;
                $list_typography
            }
            .eael-toc.eael-toc-global .eael-toc-body ul.eael-toc-list li:before {
                $list_typography
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-list-bar li.eael-highlight-active > a:after {
                height:{$indicator_size}px;
            }

            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-list-arrow li.eael-highlight-active > a:before,
            .eael-toc-global .eael-toc-body .eael-toc-list.eael-toc-list-bar li.eael-highlight-active > a:after {
                margin-top:{$indicator_position}px;
            }


            .eael-toc:not(.eael-toc-right)
            {
                border-top-right-radius:{$border_radius}px;
                border-bottom-right-radius:{$border_radius}px;
            }

            .eael-toc:not(.eael-toc-right) .eael-toc-header
            {
                border-top-right-radius:{$border_radius}px;
            }

            .eael-toc:not(.eael-toc-right) .eael-toc-body {
                border-bottom-right-radius:{$border_radius}px;
            }

            .eael-toc.eael-toc-right {
                border-top-left-radius:{$border_radius}px;
                border-bottom-left-radius:{$border_radius}px;
            }

            .eael-toc.eael-toc-right .eael-toc-header {
                border-top-left-radius:{$border_radius}px;
            }

            .eael-toc.eael-toc-right .eael-toc-body {
                border-bottom-left-radius:{$border_radius}px;
            }


            #eael-toc.eael-toc-global ul.eael-toc-list > li
            {
                padding-top:{$eael_toc['eael_ext_toc_top_level_space']['size']}px;
                padding-bottom:{$eael_toc['eael_ext_toc_top_level_space']['size']}px;
            }

            #eael-toc.eael-toc-global ul.eael-toc-list>li ul li
            {
                padding-top:{$eael_toc['eael_ext_toc_subitem_level_space']['size']}px;
                padding-bottom:{$eael_toc['eael_ext_toc_subitem_level_space']['size']}px;
            }
        ";
        if ($toc_list_separator_style != 'none') {
            $toc_global_css .= "
            .eael-toc-global ul.eael-toc-list > li
            {border-top: 0.5px $toc_list_separator_style !important;}
            .eael-toc ul.eael-toc-list>li:first-child
            {border: none !important;}";
        }

        if (isset($eael_toc['eael_ext_toc_border_border'])) {
            $border_width = $eael_toc['eael_ext_toc_border_width'];
            $toc_global_css .= "
            .eael-toc.eael-toc-global,.eael-toc-global button.eael-toc-button
            {
                border-style: {$eael_toc['eael_ext_toc_border_border']};
                border-width: {$border_width['top']}px {$border_width['right']}px {$border_width['bottom']}px {$border_width['left']}px;
                border-color: {$eael_toc['eael_ext_toc_border_color']};
            }";
        }

        wp_add_inline_style('eael-table-of-content', $toc_global_css);
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

    public function get_extensions_value($key = '')
    {
        return isset($this->extensions_data[$key]) ? $this->extensions_data[$key] : '';
    }
}

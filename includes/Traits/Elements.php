<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

use \Elementor\Core\Settings\Manager as Settings_Manager;
use Elementor\Plugin;
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

            if($this->pro_enabled && \version_compare(EAEL_PRO_PLUGIN_VERSION, '3.3.0', '<')) {
                if(in_array($active_element, ['content-timeline', 'dynamic-filter-gallery', 'post-block', 'post-carousel', 'post-list'])) {
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
     * Register extensions
     *
     * @since v3.1.4
     */
    public function render_global_html()
    {
        if (is_singular()) {
            $page_settings_manager = Settings_Manager::get_settings_managers('page');
            $page_settings_model = $page_settings_manager->get_model(get_the_ID());
            $global_settings = get_option('eael_global_settings');
            $html = '';

            if($this->get_settings('eael-reading-progress') == false) {
                return;
            }

            if ($page_settings_model->get_settings('eael_ext_reading_progress') == 'yes' || isset($global_settings['reading_progress']['enabled'])) {
                add_filter('eael/section/after_render', function ($extensions) {
                    $extensions[] = 'eael-reading-progress';
                    return $extensions;
                });

                $html .= '<div class="eael-reading-progress-wrap eael-reading-progress-wrap-' . ($page_settings_model->get_settings('eael_ext_reading_progress') == 'yes' ? 'local' : 'global') . '">
                    <div class="eael-reading-progress eael-reading-progress-local eael-reading-progress-' . $page_settings_model->get_settings('eael_ext_reading_progress_position') . '">
                        <div class="eael-reading-progress-fill"></div>
                    </div>
                    <div class="eael-reading-progress eael-reading-progress-global eael-reading-progress-' . @$global_settings['reading_progress']['position'] . '" style="height: ' . @$global_settings['reading_progress']['height']['size'] . 'px;background-color: ' . @$global_settings['reading_progress']['bg_color'] . ';">
                        <div class="eael-reading-progress-fill" style="height: ' . @$global_settings['reading_progress']['height']['size'] . 'px;background-color: ' . @$global_settings['reading_progress']['fill_color'] . ';transition: width ' . @$global_settings['reading_progress']['animation_speed']['size'] . 'ms ease;"></div>
                    </div>
                </div>';

                if ($page_settings_model->get_settings('eael_ext_reading_progress') != 'yes') {
                    if(get_post_status($global_settings['reading_progress']['post_id']) != 'publish') {
                        return;
                    } else if ($global_settings['reading_progress']['display_condition'] == 'pages' && !is_page()) {
                        return;
                    } else if ($global_settings['reading_progress']['display_condition'] == 'posts' && !is_single()) {
                        return;
                    } else if ($global_settings['reading_progress']['display_condition'] == 'all' && !is_singular()) {
                        return;
                    }
                }

                echo $html;
            }
        }
    }

    /**
     * @return string
     */
    public function eael_table_of_content_editor (){

        if(!is_singular()){
            return '';
        }

        $page_settings_manager = Settings_Manager::get_settings_managers('page');
        $page_settings_model = $page_settings_manager->get_model(get_the_ID());
        $global_settings = get_option('eael_global_settings');
        $disable_toc = $html = '';
        $el_class = 'eael-toc';
        $enable_toc = true;

        if($page_settings_model->get_settings('eael_ext_table_of_content') != 'yes' && !isset($global_settings['table_of_content']['enabled'])){
            $el_class .= ' eael-toc-disable';
            $enable_toc = false;
        }else{
            add_filter('eael/section/after_render', function ($extensions) {
                $extensions[] = 'eael-table-of-content';
                return $extensions;
            });
        }

        if (!\Elementor\Plugin::$instance->preview->is_preview_mode() && !$enable_toc) {
            $disable_toc = 'style="display:none;"';
        }
        if($page_settings_model->get_settings('eael_ext_table_of_content') != 'yes' && isset($global_settings['table_of_content']['enabled'])){
            $el_class .=' eael-toc-global';
        }

        $content = get_the_content();
        $support_tag =  (array) $this->eael_get_toc_setting_value( $page_settings_model ,'eael_ext_toc_supported_heading_tag', $global_settings );
        $support_tag = implode( ',', array_filter( $support_tag ) );
        $position = $this->eael_get_toc_setting_value( $page_settings_model ,'eael_ext_toc_position', $global_settings );
        $toc_style = $this->eael_get_toc_setting_value( $page_settings_model ,'eael_ext_table_of_content_list_style', $global_settings );
        $toc_title = esc_html($this->eael_get_toc_setting_value( $page_settings_model ,'eael_ext_toc_title', $global_settings ));
        $el_class .= ($position =='right')?' eael-toc-right':'';
        $toc_style_class = ' eael-toc-list-'.$toc_style;

        $html = '';
        $html .= "<div data-eaelTocTag='{$support_tag}' id='eael-toc' class='{$el_class}' {$disable_toc}>";
            $html .= "<span class='eael-toc-close'>×</span>";
            $html .= "<div class='eael-toc-header'>";
                 $html .= "<h2 class='eael-toc-title'>{$toc_title}</h2>";
            $html .= "</div>";
                $html .= "<div class='eael-toc-body'>";
                $html .= $this->eael_list_hierarchy( $content, $support_tag, array( 'class' => $toc_style_class ) );
            $html .= "</div>";
            $html .= sprintf( "<button class='eael-toc-button'><i class='fas fa-list'></i><span>%s</span></button>", $toc_title );
            //$html .= sprintf( "<div class='eael-toc-button'><i class='fas fa-list'></i><span>%s</span></div>", $toc_title );
        $html .= "</div>";
        echo $html;
    }

    /**
     * @param $post_css
     * @param $elements
     * @return string|void
     */
    public function eael_toc_global_css( $post_css, $elements ){
        error_log('manzur');
        if(!is_singular()){
            return '';
        }
        $page_settings_manager = Settings_Manager::get_settings_managers('page');
        $page_settings_model = $page_settings_manager->get_model(get_the_ID());
        $global_settings = get_option('eael_global_settings');

        if ($page_settings_model->get_settings('eael_ext_table_of_content') != 'yes' && isset($global_settings['table_of_content']['enabled'])) {
            if(get_post_status($global_settings['table_of_content']['post_id']) != 'publish') {
                return;
            } else if ($global_settings['table_of_content']['display_condition'] == 'pages' && !is_page()) {
                return;
            } else if ($global_settings['table_of_content']['display_condition'] == 'posts' && !is_single()) {
                return;
            } else if ($global_settings['table_of_content']['display_condition'] == 'all' && !is_singular()) {
                return;
            }
        }else{
            return;
        }
        $header_bg = $global_settings['table_of_content']['eael_ext_table_of_content_header_bg'];
        $header_text_color = $global_settings['table_of_content']['eael_ext_table_of_content_header_text_color'];
        $toc_body_bg = $global_settings['table_of_content']['eael_ext_table_of_content_body_bg'];
        $toc_list_color = $global_settings['table_of_content']['eael_ext_table_of_content_list_text_color'];
        $toc_list_color_active = $global_settings['table_of_content']['eael_ext_table_of_content_list_text_color_active'];
        $toc_list_separator_style = $global_settings['table_of_content']['eael_ext_table_of_content_list_separator_style'];
        $toc_list_separator_color = $global_settings['table_of_content']['eael_ext_table_of_content_list_separator_color'];

        $toc_global_css = "
            .eael-toc-global .eael-toc-header,
            .eael-toc-global.expanded .eael-toc-button
            {background-color:$header_bg;}
            .eael-toc-global .eael-toc-close
            {color:$header_bg;}
            
            .eael-toc-global .eael-toc-header .eael-toc-title,
            .eael-toc-global.expanded .eael-toc-button
            {color:$header_text_color;}
            .eael-toc-global .eael-toc-close
            {background-color:$header_text_color;}
            
            .eael-toc-global .eael-toc-body
            {background-color:$toc_body_bg;}
            
            .eael-toc-global ul.eael-toc-list li a,
            .eael-toc-global ul.eael-toc-list li
            {color:$toc_list_color;}
            
            .eael-toc-global ul.eael-toc-list li.active > a,
            .eael-toc-global ul.eael-toc-list li.active
            {color:$toc_list_color_active;}
            .eael-toc-global .ul.eael-toc-list.eael-toc-list-style_2 li.active > a:before,
            {border-bottom:10px solid $toc_list_color_active;}
            .eael-toc-global ul.eael-toc-list.eael-toc-list-style_3 li.active>a:after > a:before
            {background-color:$toc_list_color_active;}
            
            .eael-toc-global ul.eael-toc-list>li
            {color:$toc_list_separator_color !important;}
        ";
        if($toc_list_separator_style!='none'){
            $toc_global_css .= "
            .eael-toc-global ul.eael-toc-list > li
            {border-top: 0.5px $toc_list_separator_style !important;}
            .eael-toc ul.eael-toc-list>li:first-child
            {border: none !important;}";
        }
        $post_css->get_stylesheet()->add_raw_css( $toc_global_css );
    }
}

<?php

namespace Essential_Addons_Elementor\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Essential_Addons_Elementor\Classes\Helper;

class AI_Recommended_Widgets
{

    public function __construct()
    {
        add_action('elementor/documents/register_controls', [$this, 'register_controls'], 10);
    }

    public function register_controls($element)
    {
        $ai_recommended_widgets = $this->get_ai_recommended_widgets();

        foreach( $ai_recommended_widgets as $ai_recommended_widget ){
            add_filter('eael/elements/categories/' . $ai_recommended_widget, function( $categories ){
                $categories[] = 'essential-addons-elementor-recommended';
                return $categories;
            });
        }
    }

    public function get_data_args() {
        $page_title = get_the_title();
        $current_post_type = get_post_type();
        $site_title = get_bloginfo();
        $site_tageline = get_bloginfo('description');
        $plugin_list = get_option('active_plugins');
        $theme_name_obj = wp_get_theme();
        $theme_name     = is_object( $theme_name_obj ) ? $theme_name_obj->get( 'TextDomain' ) : '';
        $all_post_types = get_post_types();
        
        $page_content = get_the_content();
        $page_content_headings = Helper::get_headings_from_content( $page_content );
        
        $data = [
            'page_title' => $page_title,
            'current_post_type' => $current_post_type,
            'page_content_headings' => $page_content_headings,
            'site_title' => $site_title,
            'site_tagline' => $site_tageline,
            'plugin_list' => $plugin_list,
            'theme_name' => $theme_name,
            'all_post_types' => $all_post_types,
        ];

        return $data;
    }

    public function get_ai_recommended_widgets_from_api( $args ){
        
    }

    public function get_ai_recommended_widgets() {
        $get_data_args = $this->get_data_args();
        $ai_recommended_widgets = $this->get_ai_recommended_widgets_from_api( $get_data_args );

        if ( is_array( $ai_recommended_widgets ) && count( $ai_recommended_widgets ) > 0 ) {
            return $ai_recommended_widgets;
        }

        return [];
    }
}
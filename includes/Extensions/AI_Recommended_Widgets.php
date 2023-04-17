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

        if( is_array( $ai_recommended_widgets ) && count( $ai_recommended_widgets ) ){
            foreach( $ai_recommended_widgets as $ai_recommended_widget ){
                add_filter('eael/elements/categories/' . $ai_recommended_widget, function( $categories ){
                    $categories[] = 'essential-addons-elementor-recommended';
                    return $categories;
                });
            }
        }
    }

    public function get_data_args() {
        $page_title         = get_the_title();
        $current_post_type  = get_post_type();
        $site_title         = get_bloginfo();
        $site_tageline      = get_bloginfo('description');
        $plugin_list        = get_option('active_plugins');
        $theme_name_obj     = wp_get_theme();
        $theme_name         = is_object( $theme_name_obj ) ? $theme_name_obj->get( 'Name' ) : '';
        
        $all_post_types = get_post_types([], 'objects');
        $all_post_types_labels = [];
        $plugin_list_names = [];

        if( is_array( $all_post_types ) && count( $all_post_types ) ){
            foreach( $all_post_types as $all_post_type ){
                $all_post_types_labels[] = isset( $all_post_type->labels ) ? $all_post_type->labels->singular_name : $all_post_type->label;
            }
        }

        if( is_array( $all_post_types ) && count( $all_post_types ) ){
            foreach ($plugin_list as $plugin_file) {
                $plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_file );
                $plugin_list_names[] = ! empty( $plugin_data['Name'] ) ? $plugin_data['Name'] : '';
            }
        }

        $page_content = get_the_content();
        $page_content_headings = Helper::get_headings_from_content( $page_content );

        $current_post_type_label        = '';
        $page_content_headings_string   = '';
        $all_post_types_labels_string   = '';
        $plugin_list_names_string       = '';
        
        if( $current_post_type ){
            $current_post_type_obj = get_post_type_object( $current_post_type );
            
            if( is_object( $current_post_type_obj ) ){
                $current_post_type_label = isset( $current_post_type_obj->labels ) ? $current_post_type_obj->labels->singular_name : $current_post_type_obj->label;
            }
        }

        if( is_array( $page_content_headings ) && count( $page_content_headings ) ){
            $page_content_headings_string = implode(', ', $page_content_headings);
        }
        
        if( is_array( $all_post_types_labels ) && count( $all_post_types_labels ) ){
            $all_post_types_labels_string = implode(', ', $all_post_types_labels);
        }
        
        if( is_array( $plugin_list_names ) && count( $plugin_list_names ) ){
            $plugin_list_names_string = implode(', ', $plugin_list_names);
        }
        
        // $data = [
        //     'page_title' => $page_title,
        //     'current_post_type' => $current_post_type_label,
        //     'page_content_headings' => $page_content_headings_string,
        //     'site_title' => $site_title,
        //     'site_tagline' => $site_tageline,
        //     'plugin_list' => $plugin_list_names_string,
        //     'theme_name' => $theme_name,
        //     'all_post_types' => $all_post_types_labels_string,
        // ];
        
        $data_global = [
            'site_title' => $site_title,
            'site_tag_line' => $site_tageline,
            'plugin_list' => $plugin_list_names_string,
            'theme_name' => $theme_name,
            'list_of_post_type' => $all_post_types_labels_string,
        ];

        $data = [
            'page_title' => $page_title,
            'current_post_type' => $current_post_type_label,
            'page_content_heading_tags' => $page_content_headings_string,
        ];

        $data = array_merge($data_global, $data);

        // data to cache


        echo "<pre>";
        print_r($data);
        wp_die('ok');
        return $data;
    }

    public function get_ai_recommended_widgets_from_api( $args ){
		$url            = "http://192.168.68.59:8000/recommend_widgets";
		$error_message  = '';
        
        $cache_key      = 'eael_ai_recommended_widgets_' . get_the_ID();
        $items          = get_transient( $cache_key );

		if ( false === $items ) {

			$response = wp_remote_post(
                $url,
                [
                    'method' => 'POST',
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode($args),
                ]
            );

			$response     = json_decode( wp_remote_retrieve_body( $response ) );

            if ( ! empty( $response ) ) {
                set_transient( $cache_key,  $response, 86400);
            } else {
                $error_message = __( 'Sorry, we could not get any recommendations.', 'essential-addons-elementor' );
            }
		}

		$response = $items ? $items : $response;

		$data = [
			'items'         => $response,
			'error_message' => $error_message,
		];

		return $data;
    }

    public function get_eael_elements() {

    }

    public function get_element_slug() {

    }

    public function get_ai_recommended_widgets() {
        $get_data_args = $this->get_data_args();
        $ai_recommended_widgets = $this->get_ai_recommended_widgets_from_api( $get_data_args );
        $ai_recommended_widgets = isset( $ai_recommended_widgets['items'] ) ? $ai_recommended_widgets['items'] : [];

        print_r($ai_recommended_widgets);
        wp_die('ok');
        if ( is_array( $ai_recommended_widgets ) && count( $ai_recommended_widgets ) > 0 ) {
            return $ai_recommended_widgets;
        }

        return [];
    }
}
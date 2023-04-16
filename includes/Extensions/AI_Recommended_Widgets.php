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

    public function get_ai_recommended_widgets() {
        $site_title = get_bloginfo();

        return [ 
            'eael-adv-tabs',
            'eael-adv-accordion',
        ];
    }
}
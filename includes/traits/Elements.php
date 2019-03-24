<?php

namespace Essential_Addons_Elementor\Traits;

if (!defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

use Essential_Addons_Elementor\Classes\Group_Control_EA_Posts as Group_Control_EA_Posts;

trait Elements
{
    /**
     * Add Category
     *
     * @since v1.0.0
     */
    public function add_elementor_widget_categories($elements_manager)
    {
        $elements_manager->add_category(
            'essential-addons-elementor',
            [
                'title' => __('Essential Addons', 'essential-addons-elementor'),
                'icon' => 'font',
            ], 1);
    }

    /**
     * Add new group control
     *
     * @since v1.0.0
     */
    public function controls_registered($controls_manager)
    {
        $controls_manager->add_group_control('eaeposts', new Group_Control_EA_Posts);
    }

    /**
     * Acivate or Deactivate Modules
     *
     * @since v1.0.0
     */
    public function add_eael_elements($widgets_manager)
    {

        $elements = [
            [
                'name' => 'post-grid',
                'class' => 'Eael_Adv_Accordion',
            ],
            [
                'name' => 'post-timeline',
                'class' => 'Eael_Post_Timeline',
            ],
            [
                'name' => 'fancy-text',
                'class' => 'Eael_Fancy_Text',
            ],
            [
                'name' => 'creative-btn',
                'class' => 'Eael_Creative_Button',
            ],
            [
                'name' => 'count-down',
                'class' => 'Eael_Countdown',
            ],
            [
                'name' => 'team-members',
                'class' => 'Eael_Team_Member',
            ],
            [
                'name' => 'testimonials',
                'class' => 'Eael_Testimonial',
            ],
            [
                'name' => 'info-box',
                'class' => 'Eael_Info_Box',
            ],
            [
                'name' => 'flip-box',
                'class' => 'Eael_Flip_Box',
            ],
            [
                'name' => 'call-to-action',
                'class' => 'Eael_Cta_Box',
            ],
            [
                'name' => 'dual-header',
                'class' => 'Eael_Dual_Color_Header',
            ],
            [
                'name' => 'price-table',
                'class' => 'Eael_Pricing_Table',
            ],
            [
                'name' => 'twitter-feed',
                'class' => 'Eael_Twitter_Feed',
            ],
            [
                'name' => 'data-table',
                'class' => 'Eael_Data_Table',
            ],
            [
                'name' => 'filter-gallery',
                'class' => 'Eael_Filterable_Gallery',
            ],
            [
                'name' => 'image-accordion',
                'class' => 'Eael_Image_Accordion',
            ],
            [
                'name' => 'content-ticker',
                'class' => 'Eael_Content_Ticker',
            ],
            [
                'name' => 'tooltip',
                'class' => 'Eael_Tooltip',
            ],
            [
                'name' => 'adv-accordion',
                'class' => 'Eael_Adv_Accordion',
            ],
            [
                'name' => 'adv-tabs',
                'class' => 'Eael_Adv_Tabs',
            ],
            [
                'name' => 'progress-bar',
                'class' => 'Eael_Progress_Bar',
            ],
            [
                'name' => 'feature-list',
                'class' => 'Eael_Feature_List',
            ],
            [
                'name' => 'product-grid',
                'class' => 'Eael_Product_Grid',
                'condition' => [
                    'function_exists',
                    'WC',
                ],
            ],
            [
                'name' => 'contact-form-7',
                'class' => 'Eael_Contact_Form_7',
                'condition' => [
                    'function_exists',
                    'wpcf7',
                ],
            ],
            [
                'name' => 'weforms',
                'class' => 'Eael_WeForms',
                'condition' => [
                    'function_exists',
                    'WeForms',
                ],
            ],
            [
                'name' => 'ninja-form',
                'class' => 'Eael_NinjaForms',
                'condition' => [
                    'function_exists',
                    'Ninja_Forms',
                ],
            ],
            [
                'name' => 'gravity-form',
                'class' => 'Eael_GravityForms',
                'condition' => [
                    'class_exists',
                    'GFForms',
                ],
            ],
            [
                'name' => 'caldera-form',
                'class' => 'Eael_Caldera_Forms',
                'condition' => [
                    'class_exists',
                    'Caldera_Forms',
                ],
            ],
            [
                'name' => 'wpforms',
                'class' => 'Eael_WpForms',
                'condition' => [
                    'class_exists',
                    '\WPForms\WPForms',
                ],
            ],
        ];

        $is_component_active = $this->get_settings();
        $ea_elements = apply_filters('add_eae_element', $elements);

        foreach ($ea_elements as $element) {
            if (isset($element['condition'])) {
                if (($element['condition'][0]($element['condition'][1])) && $is_component_active[$element['name']]) {
                    $element_class = '\Essential_Addons_Elementor\Elements\\' . $element['class'];
                    $widgets_manager->register_widget_type(new $element_class(array(), array('Hello')));
                }
            } else {
                if ($is_component_active[$element['name']]) {
                    $element_class = '\Essential_Addons_Elementor\Elements\\' . $element['class'];
                    $widgets_manager->register_widget_type(new $element_class(array(), array('Hello')));
                }
            }
        }
    }

}

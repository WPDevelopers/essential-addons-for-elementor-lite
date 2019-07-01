<?php

namespace Essential_Addons_Elementor\Classes\WPML;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


trait Eael_WPML {

    public function eael_translatable_widgets($widgets)
    {
        $widgets['eael-adv-accordion'] = [
            'conditions' => ['widgetType' => 'eael-adv-accordion'],
            'integration-class' => '\Essential_Addons_Elementor\Classes\WPML\Widgets\Accordion',
        ];

        $widgets['eael-adv-tabs'] = [
            'conditions' => ['widgetType' => 'eael-adv-tabs'],
            'integration-class' => '\Essential_Addons_Elementor\Classes\WPML\Widgets\Advance_Tab',
        ];

        $widgets['eael-creative-button'] = [
            'conditions' => ['widgetType' => 'eael-creative-button'],
            'fields' => [
                [
                    'field'       => 'creative_button_text',
                    'type'        => __('Creative Button: Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'creative_button_secondary_text',
                    'type'        => __('Creative Button: Secondary Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        $widgets['eael-cta-box'] = [
            'conditions' => ['widgetType' => 'eael-cta-box'],
            'fields' => [
                [
                    'field'       => 'eael_cta_title',
                    'type'        => __('Call to Action: Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_cta_content',
                    'type'        => __('Call to Action: Content', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ],
                [
                    'field' => 'eael_cta_btn_text',
                    'type'        => __('Call to Action: Button Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        // Doesn't work properly
        // $widgets['eael-data-table'] = [
        //     'conditions' => ['widgetType' => 'eael-data-table'],
        //     'integration-class' => '\Essential_Addons_Elementor\Classes\WPML\Widgets\Data_Table'
        // ];

        $widgets['eicon-animated-headline'] = [
            'conditions' => ['widgetType' => 'eael-dual-color-header'],
            'fields' => [
                [
                    'field'       => 'eael_dch_first_title',
                    'type'        => __('Title ( First Part )', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_dch_last_title',
                    'type'        => __('Title ( Last Part )', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_dch_subtext',
                    'type'        => __('Sub Text', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ]
            ],
        ];

        $widgets['eael-fancy-text'] = [
            'conditions' => ['widgetType' => 'eael-fancy-text'],
            'integration-class' => '\Essential_Addons_Elementor\Classes\WPML\Widgets\Fancy_Text',
            'fields' => [
                [
                    'field'       => 'eael_fancy_text_prefix',
                    'type'        => __('Prefix Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_fancy_text_suffix',
                    'type'        => __('Suffix Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        // $widgets['eael-filterable-gallery'] = [
        //     'conditions' => ['widgetType' => 'eael-filterable-gallery'],
        //     'fields' => [
        //         [
        //             'field'       => 'eael_fg_all_label_text',
        //             'type'        => __('Gallery All Label', 'essential-addons-elementor'),
        //             'editor_type' => 'LINE',
        //         ],
        //         [
        //             'field'       => 'eael_fg_control',
        //             'type'        => __('List Item', 'essential-addons-elementor'),
        //             'editor_type' => 'LINE',
        //         ],
        //         [
        //             'field'       => 'eael_fg_gallery_item_name',
        //             'type'        => __('Item Name', 'essential-addons-elementor'),
        //             'editor_type' => 'LINE',
        //         ],
        //         [
        //             'field'       => 'eael_fg_gallery_item_content',
        //             'type'        => __('Item Content', 'essential-addons-elementor'),
        //             'editor_type' => 'AREA',
        //         ]
        //     ],
        // ];

        $widgets['eael-image-accordion'] = [
            'conditions' => ['widgetType' => 'eael-image-accordion'],
            'integration-class' => '\Essential_Addons_Elementor\Classes\WPML\Widgets\Image_Accordion'
        ];

        // have to work on this later.
        // $widgets['eael-flip-box'] = [
        //     'conditions' => ['widgetType' => 'eael-flip-box'],
        //     'fields' => [
        //         [
        //             'field'       => 'eael_flipbox_front_title',
        //             'type'        => __('Flip Box: Front Title', 'essential-addons-elementor'),
        //             'editor_type' => 'LINE',
        //         ],
        //         [
        //             'field'       => 'eael_flipbox_front_text',
        //             'type'        => __('Flip Box: Front Text', 'essential-addons-elementor'),
        //             'editor_type' => 'AREA',
        //         ],
        //         [
        //             'field'       => 'eael_flipbox_back_title',
        //             'type'        => __('Flip Box: Back Title', 'essential-addons-elementor'),
        //             'editor_type' => 'LINE',
        //         ],
        //         [
        //             'field'       => 'eael_flipbox_back_text',
        //             'type'        => __('Flip Box: Back Text', 'essential-addons-elementor'),
        //             'editor_type' => 'AREA',
        //         ]
        //     ],
        // ];

        $widgets['eael-info-box'] = [
            'conditions' => ['widgetType' => 'eael-info-box'],
            'fields' => [
                [
                    'field'       => 'eael_infobox_title',
                    'type'        => __('Infobox: Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_infobox_text',
                    'type'        => __('Infobox Content', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ]
            ],
        ];

        $widgets['eael-pricing-table'] = [
            'conditions' => ['widgetType' => 'eael-pricing-table'],
            'integration-class' => '\Essential_Addons_Elementor\Classes\WPML\Widgets\Pricing_Table',
            'fields' => [
                [
                    'field'       => 'eael_pricing_table_title',
                    'type'        => __('Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_sub_title',
                    'type'        => __('Sub Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_price',
                    'type'        => __('Price', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_price_cur',
                    'type'        => __('Currency Placement', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_price_period',
                    'type'        => __('Price Period (per)', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_btn',
                    'type'        => __('Button Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_btn',
                    'type'        => __('Button Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_featured_tag_text',
                    'type'        => __('Featured Tag Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        $widgets['eael-progress-bar'] = [
            'conditions' => ['widgetType' => 'eael-progress-bar'],
            'fields' => [
                [
                    'field'       => 'progress_bar_title',
                    'type'        => __('Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        $widgets['eael-team-member'] = [
            'conditions' => ['widgetType' => 'eael-team-member'],
            'fields' => [
                [
                    'field'       => 'eael_team_member_name',
                    'type'        => __('Name', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_team_member_job_title',
                    'type'        => __('Job Position', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_team_member_description',
                    'type'        => __('Description', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ]
            ],
        ];

        $widgets['eael-testimonial'] = [
            'conditions' => ['widgetType' => 'eael-testimonial'],
            'fields' => [
                [
                    'field'       => 'eael_testimonial_name',
                    'type'        => __('User Name', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_testimonial_company_title',
                    'type'        => __('Company Name', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_testimonial_description',
                    'type'        => __('Testimonial Description', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ]
            ],
        ];


        $widgets['eael-tooltip'] = [
            'conditions' => ['widgetType' => 'eael-tooltip'],
            'fields' => [
                [
                    'field'       => 'eael_tooltip_content',
                    'type'        => __('Content', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_tooltip_hover_content',
                    'type'        => __('Content', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ]
            ],
        ];


        $widgets['eael-feature-list'] = [
            'conditions' => ['widgetType' => 'eael-feature-list'],
            'integration-class' => '\Essential_Addons_Elementor\Classes\WPML\Widgets\Feature_List',
            'fields' => [
                [
                    'field'       => 'eael_feature_list_title',
                    'type'        => __('Feature List: Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_feature_list_content',
                    'type'        => __('Feature List: Content', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ]
            ],
        ];

        return $widgets;
    }

}
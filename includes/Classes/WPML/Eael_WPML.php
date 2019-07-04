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
                    'editor_type' => 'VISUAL',
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
                    'type'        => __('Dual Color Heading: Title ( First Part )', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_dch_last_title',
                    'type'        => __('Dual Color Heading: Title ( Last Part )', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_dch_subtext',
                    'type'        => __('Dual Color Heading: Sub Text', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
                ]
            ],
        ];

        $widgets['eael-fancy-text'] = [
            'conditions' => ['widgetType' => 'eael-fancy-text'],
            'integration-class' => '\Essential_Addons_Elementor\Classes\WPML\Widgets\Fancy_Text',
            'fields' => [
                [
                    'field'       => 'eael_fancy_text_prefix',
                    'type'        => __('Fancy Text: Prefix Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_fancy_text_suffix',
                    'type'        => __('Fancy Text: Suffix Text', 'essential-addons-elementor'),
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
                    'editor_type' => 'VISUAL',
                ]
            ],
        ];

        $widgets['eael-pricing-table'] = [
            'conditions' => ['widgetType' => 'eael-pricing-table'],
            'integration-class' => '\Essential_Addons_Elementor\Classes\WPML\Widgets\Pricing_Table',
            'fields' => [
                [
                    'field'       => 'eael_pricing_table_title',
                    'type'        => __('Pricing Table: Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_sub_title',
                    'type'        => __('Pricing Table: Sub Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_price',
                    'type'        => __('Pricing Table: Price', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_price_cur',
                    'type'        => __('Pricing Table: Currency Placement', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_price_period',
                    'type'        => __('Pricing Table: Price Period (per)', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_btn',
                    'type'        => __('Pricing Table: Button Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_btn',
                    'type'        => __('Pricing Table: Button Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_pricing_table_featured_tag_text',
                    'type'        => __('Pricing Table: Featured Tag Text', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        $widgets['eael-progress-bar'] = [
            'conditions' => ['widgetType' => 'eael-progress-bar'],
            'fields' => [
                [
                    'field'       => 'progress_bar_title',
                    'type'        => __('Progressbar: Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ]
            ],
        ];

        $widgets['eael-team-member'] = [
            'conditions' => ['widgetType' => 'eael-team-member'],
            'fields' => [
                [
                    'field'       => 'eael_team_member_name',
                    'type'        => __('Team Member: Name', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_team_member_job_title',
                    'type'        => __('Team Member: Job Position', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_team_member_description',
                    'type'        => __('Team Member: Description', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ]
            ],
        ];

        $widgets['eael-testimonial'] = [
            'conditions' => ['widgetType' => 'eael-testimonial'],
            'fields' => [
                [
                    'field'       => 'eael_testimonial_name',
                    'type'        => __('Testimonial: User Name', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_testimonial_company_title',
                    'type'        => __('Testimonial: Company Name', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_testimonial_description',
                    'type'        => __('Testimonial: Testimonial Description', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
                ]
            ],
        ];


        $widgets['eael-tooltip'] = [
            'conditions' => ['widgetType' => 'eael-tooltip'],
            'fields' => [
                [
                    'field'       => 'eael_tooltip_content',
                    'type'        => __('Tooltip: Content', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'eael_tooltip_hover_content',
                    'type'        => __('Tooltip: Content', 'essential-addons-elementor'),
                    'editor_type' => 'VISUAL',
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

        $widgets['eael-caldera-form'] = [
            'conditions' => ['widgetType' => 'eael-caldera-form'],
            'fields' => [
                [
                    'field'       => 'form_title_custom',
                    'type'        => __('Caldera Form: Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'form_description_custom',
                    'type'        => __('Caldera Form: Description', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ]
            ],
        ];

        $widgets['eael-contact-form-7'] = [
            'conditions' => ['widgetType' => 'eael-contact-form-7'],
            'fields' => [
                [
                    'field'       => 'form_title_text',
                    'type'        => __('Contact Form-7: Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'form_description_text',
                    'type'        => __('Contact Form-7: Description', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ]
            ],
        ];

        $widgets['eael-gravity-form'] = [
            'conditions' => ['widgetType' => 'eael-gravity-form'],
            'fields' => [
                [
                    'field'       => 'form_title_custom',
                    'type'        => __('Gravity Form: Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'form_description_custom',
                    'type'        => __('Gravity Form: Description', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ]
            ],
        ];

        $widgets['eael-wpforms'] = [
            'conditions' => ['widgetType' => 'eael-wpforms'],
            'fields' => [
                [
                    'field'       => 'form_title_custom',
                    'type'        => __('WPForms: Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'form_description_custom',
                    'type'        => __('WPForms: Description', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ]
            ],
        ];

        $widgets['eael-ninja'] = [
            'conditions' => ['widgetType' => 'eael-ninja'],
            'fields' => [
                [
                    'field'       => 'form_title_custom',
                    'type'        => __('NinjaForm: Title', 'essential-addons-elementor'),
                    'editor_type' => 'LINE',
                ],
                [
                    'field'       => 'form_description_custom',
                    'type'        => __('NinjaForm: Description', 'essential-addons-elementor'),
                    'editor_type' => 'AREA',
                ]
            ],
        ];

        return $widgets;
    }

}
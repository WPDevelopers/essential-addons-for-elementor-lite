<?php

$config = [
    'elements' => [
        'post-grid' => [
            'class' => '\Essential_Addons_Elementor\Elements\Post_Grid',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/load-more.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/post-grid.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/product-grid.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/imagesloaded.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/isotope.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/load-more.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/post-grid.min.js',
                ],
            ],
        ],
        'post-timeline' => [
            'class' => '\Essential_Addons_Elementor\Elements\Post_Timeline',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/load-more.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/post-timeline.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/load-more.min.js',
                ],
            ],
        ],
        'fancy-text' => [
            'class' => '\Essential_Addons_Elementor\Elements\Fancy_Text',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/fancy-text.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/fancy-text.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/fancy-text.min.js',
                ],
            ],
        ],
        'creative-btn' => [
            'class' => '\Essential_Addons_Elementor\Elements\Creative_Button',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/creative-btn.min.css',
                ],
            ],
        ],
        'count-down' => [
            'class' => '\Essential_Addons_Elementor\Elements\Countdown',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/count-down.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/countdown.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/count-down.min.js',
                ],
            ],
        ],
        'team-members' => [
            'class' => '\Essential_Addons_Elementor\Elements\Team_Member',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/team-members.min.css',
                ],
            ],
        ],
        'testimonials' => [
            'class' => '\Essential_Addons_Elementor\Elements\Testimonial',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/testimonials.min.css',
                ],
            ],
        ],
        'info-box' => [
            'class' => '\Essential_Addons_Elementor\Elements\Info_Box',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/info-box.min.css',
                ],
            ],
        ],
        'flip-box' => [
            'class' => '\Essential_Addons_Elementor\Elements\Flip_Box',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/flip-box.min.css',
                ],
            ],
        ],
        'call-to-action' => [
            'class' => '\Essential_Addons_Elementor\Elements\Cta_Box',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/call-to-action.min.css',
                ],
            ],
        ],
        'dual-header' => [
            'class' => '\Essential_Addons_Elementor\Elements\Dual_Color_Header',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/dual-header.min.css',
                ],
            ],
        ],
        'price-table' => [
            'class' => '\Essential_Addons_Elementor\Elements\Pricing_Table',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/tooltipster.bundle.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/price-table.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/tooltipster.bundle.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/price-table.min.js',
                ],
            ],
        ],
        'twitter-feed' => [
            'class' => '\Essential_Addons_Elementor\Elements\Twitter_Feed',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/twitter-feed.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/imagesloaded.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/isotope.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/twitter-feed.min.js',
                ],
            ],
        ],
        'facebook-feed' => [
            'class' => '\Essential_Addons_Elementor\Elements\Facebook_Feed',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/load-more.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/facebook-feed.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/imagesloaded.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/isotope.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/facebook-feed.min.js',
                ],
            ],
        ],
        'advanced-data-table' => [
            'class' => '\Essential_Addons_Elementor\Elements\Advanced_Data_Table',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/advanced-data-table.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/advanced-data-table.min.js',
                ],
            ],
        ],
        'data-table' => [
            'class' => '\Essential_Addons_Elementor\Elements\Data_Table',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/data-table.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/data-table.min.js',
                ],
            ],
        ],
        'filter-gallery' => [
            'class' => '\Essential_Addons_Elementor\Elements\Filterable_Gallery',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/load-more.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/magnific-popup.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/filterable-gallery.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/imagesloaded.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/isotope.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/jquery.magnific-popup.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/filterable-gallery.min.js',
                ],
            ],
        ],
        'image-accordion' => [
            'class' => '\Essential_Addons_Elementor\Elements\Image_Accordion',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/image-accordion.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/image-accordion.min.js',
                ],
            ],
        ],
        'content-ticker' => [
            'class' => '\Essential_Addons_Elementor\Elements\Content_Ticker',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/content-ticker.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/content-ticker.min.js',
                ],
            ],
        ],
        'tooltip' => [
            'class' => '\Essential_Addons_Elementor\Elements\Tooltip',
            'dependency' => [
                'css' => [

                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/tooltip.min.css',
                ],
            ],
        ],
        'adv-accordion' => [
            'class' => '\Essential_Addons_Elementor\Elements\Adv_Accordion',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/advanced-accordion.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/advanced-accordion.min.js',
                ],
            ],
        ],
        'adv-tabs' => [
            'class' => '\Essential_Addons_Elementor\Elements\Adv_Tabs',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/advanced-tabs.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/advanced-tabs.min.js',
                ],
            ],
        ],
        'progress-bar' => [
            'class' => '\Essential_Addons_Elementor\Elements\Progress_Bar',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/progress-bar.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/inview.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/progress-bar.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/progress-bar.min.js',
                ],
            ],
        ],
        'feature-list' => [
            'class' => '\Essential_Addons_Elementor\Elements\Feature_List',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/feature-list.min.css',
                ],
            ],
        ],
        'product-grid' => [
            'class' => '\Essential_Addons_Elementor\Elements\Product_Grid',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/load-more.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/product-grid.min.css'
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/load-more.min.js',
                ],
            ],
        ],
        'contact-form-7' => [
            'class' => '\Essential_Addons_Elementor\Elements\Contact_Form_7',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/contact-form-7.min.css',
                ],
            ],
        ],
        'weforms' => [
            'class' => '\Essential_Addons_Elementor\Elements\WeForms',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/weforms.min.css',
                ],
            ],
        ],
        'ninja-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\NinjaForms',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/ninja-form.min.css',
                ],
            ],
        ],
        'formstack' => [
            'class' => '\Essential_Addons_Elementor\Elements\Formstack',
             'dependency' => [
                 'css' => [
                     EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/formstack.min.css',
                 ],
             ],
        ],
        'gravity-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\GravityForms',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/gravity-form.min.css',
                ],
            ],
        ],
        'caldera-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\Caldera_Forms',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/caldera-form.min.css',
                ],
            ],
        ],
        'wpforms' => [
            'class' => '\Essential_Addons_Elementor\Elements\WpForms',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/wpforms.min.css',
                ],
            ],
        ],
        'fluentform' => [
            'class' => '\Essential_Addons_Elementor\Elements\FluentForm',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/fluentform.min.css',
                ],
            ],
        ],
        'betterdocs-category-grid' => [
            'class' => '\Essential_Addons_Elementor\Elements\Betterdocs_Category_Grid',
        ],
        'betterdocs-category-box' => [
            'class' => '\Essential_Addons_Elementor\Elements\Betterdocs_Category_Box',
        ],
        'betterdocs-search-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\Betterdocs_Search_Form',
        ],
        'sticky-video' => [
            'class' => '\Essential_Addons_Elementor\Elements\Sticky_Video',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/sticky-video-plyr.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/sticky-video.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/sticky-video-plyr.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/sticky-video.min.js',
                ],
            ],
        ],
        'event-calendar' => [
            'class' => '\Essential_Addons_Elementor\Elements\Event_Calendar',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/calendar-main.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/daygrid.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/timegrid.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/listgrid.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/event-calendar.min.css',
                ],
                'js' => [
                    ///add moment.js code in calendar-main.js
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/locales-all.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/calendar-main.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/daygrid.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/timegrid.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/listgrid.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/event-calendar.min.js',
                ],
            ],
        ],
        'embedpress' => [
            'class' => '\Essential_Addons_Elementor\Elements\EmbedPress',
            'condition' => [
                'class_exists',
                '\EmbedPress\Elementor\Embedpress_Elementor_Integration',
                 true
            ]
        ],
    ],
    'extensions' => [
        'eael-promotion' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Promotion',
        ],
        'eael-reading-progress' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Reading_Progress',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/reading-progress.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/reading-progress.min.js',
                ],
            ],
        ],
        'eael-table-of-content' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Table_of_Content',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/table-of-content.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/table-of-content.min.js',
                ],
            ],
        ],
        'eael-post-duplicator' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Post_Duplicator',
        ],
    ],
];

return $config;

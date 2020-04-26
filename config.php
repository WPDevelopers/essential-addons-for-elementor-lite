<?php

$config = [
    'elements' => [
        'post-grid' => [
            'class' => '\Essential_Addons_Elementor\Elements\Post_Grid',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/load-more.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/post-grid.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/product-grid.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/imagesloaded.pkgd.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/isotope.pkgd.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/load-more.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/post-grid.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'post-timeline' => [
            'class' => '\Essential_Addons_Elementor\Elements\Post_Timeline',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/load-more.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/post-timeline.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/load-more.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'fancy-text' => [
            'class' => '\Essential_Addons_Elementor\Elements\Fancy_Text',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/fancy-text.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/morphext.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/typed.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/fancy-text.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'creative-btn' => [
            'class' => '\Essential_Addons_Elementor\Elements\Creative_Button',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/creative-btn.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'count-down' => [
            'class' => '\Essential_Addons_Elementor\Elements\Countdown',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/count-down.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/countdown.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/count-down.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'team-members' => [
            'class' => '\Essential_Addons_Elementor\Elements\Team_Member',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/team-members.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'testimonials' => [
            'class' => '\Essential_Addons_Elementor\Elements\Testimonial',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/testimonials.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'info-box' => [
            'class' => '\Essential_Addons_Elementor\Elements\Info_Box',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/info-box.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'flip-box' => [
            'class' => '\Essential_Addons_Elementor\Elements\Flip_Box',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/flip-box.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'call-to-action' => [
            'class' => '\Essential_Addons_Elementor\Elements\Cta_Box',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/call-to-action.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'dual-header' => [
            'class' => '\Essential_Addons_Elementor\Elements\Dual_Color_Header',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/dual-header.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'price-table' => [
            'class' => '\Essential_Addons_Elementor\Elements\Pricing_Table',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/tooltipster.bundle.min.css',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/price-table.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/tooltipster.bundle.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/price-table.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'twitter-feed' => [
            'class' => '\Essential_Addons_Elementor\Elements\Twitter_Feed',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/twitter-feed.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/imagesloaded.pkgd.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/isotope.pkgd.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/twitter-feed.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'facebook-feed' => [
            'class' => '\Essential_Addons_Elementor\Elements\Facebook_Feed',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/load-more.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/facebook-feed.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/imagesloaded.pkgd.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/isotope.pkgd.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/facebook-feed.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'advanced-data-table' => [
            'class' => '\Essential_Addons_Elementor\Elements\Advanced_Data_Table',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/advanced-data-table.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/advanced-data-table.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'data-table' => [
            'class' => '\Essential_Addons_Elementor\Elements\Data_Table',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/data-table.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/data-table.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'filter-gallery' => [
            'class' => '\Essential_Addons_Elementor\Elements\Filterable_Gallery',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/load-more.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/magnific-popup.min.css',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/filterable-gallery.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/imagesloaded.pkgd.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/isotope.pkgd.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/jquery.magnific-popup.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/filterable-gallery.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'image-accordion' => [
            'class' => '\Essential_Addons_Elementor\Elements\Image_Accordion',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/image-accordion.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/image-accordion.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'content-ticker' => [
            'class' => '\Essential_Addons_Elementor\Elements\Content_Ticker',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/content-ticker.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/content-ticker.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'tooltip' => [
            'class' => '\Essential_Addons_Elementor\Elements\Tooltip',
            'dependency' => [
                'css' => [

                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/tooltip.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'adv-accordion' => [
            'class' => '\Essential_Addons_Elementor\Elements\Adv_Accordion',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/advanced-accordion.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/advanced-accordion.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'adv-tabs' => [
            'class' => '\Essential_Addons_Elementor\Elements\Adv_Tabs',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/advanced-tabs.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/advanced-tabs.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'progress-bar' => [
            'class' => '\Essential_Addons_Elementor\Elements\Progress_Bar',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/progress-bar.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/inview.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/progress-bar.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ]
                ],
            ],
        ],
        'feature-list' => [
            'class' => '\Essential_Addons_Elementor\Elements\Feature_List',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/feature-list.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'product-grid' => [
            'class' => '\Essential_Addons_Elementor\Elements\Product_Grid',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/load-more.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/product-grid.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ]
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/load-more.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'contact-form-7' => [
            'class' => '\Essential_Addons_Elementor\Elements\Contact_Form_7',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/contact-form-7.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'weforms' => [
            'class' => '\Essential_Addons_Elementor\Elements\WeForms',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/weforms.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'ninja-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\NinjaForms',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/ninja-form.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'formstack' => [
            'class' => '\Essential_Addons_Elementor\Elements\Formstack',
             'dependency' => [
                 'css' => [
                     [
                         'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/formstack.min.css',
                         'type' => 'self',
                         'context'  => 'view'
                     ],
                 ],
             ],
        ],
        'gravity-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\GravityForms',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/gravity-form.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'caldera-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\Caldera_Forms',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/caldera-form.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'wpforms' => [
            'class' => '\Essential_Addons_Elementor\Elements\WpForms',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/wpforms.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'fluentform' => [
            'class' => '\Essential_Addons_Elementor\Elements\FluentForm',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/fluentform.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
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
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/sticky-video-plyr.min.css',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/sticky-video.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/sticky-video-plyr.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/sticky-video.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'event-calendar' => [
            'class' => '\Essential_Addons_Elementor\Elements\Event_Calendar',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/calendar-main.min.css',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/daygrid.min.css',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/timegrid.min.css',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/listgrid.min.css',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/event-calendar.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/locales-all.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/moment.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/calendar-main.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/daygrid.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/timegrid.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/listgrid.min.js',
                        'type'  => 'lib',
                        'context'   => 'view'
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/event-calendar.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
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
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/reading-progress.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/reading-progress.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'eael-table-of-content' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Table_of_Content',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/table-of-content.min.css',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/table-of-content.min.js',
                        'type'  => 'self',
                        'context'   => 'view'
                    ],
                ],
            ],
        ],
        'eael-post-duplicator' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Post_Duplicator',
        ],
    ],
];

return $config;

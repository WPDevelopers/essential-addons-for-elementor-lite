<?php

$config = [
    'elements' => [
        'post-grid' => [
            'class' => '\Essential_Addons_Elementor\Elements\Post_Grid',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/components/load-more.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/post-grid/index.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/product-grid/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/imagesLoaded/imagesloaded.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/load-more/load-more.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/post-grid/index.min.js',
                ],
            ],
        ],
        'post-timeline' => [
            'class' => '\Essential_Addons_Elementor\Elements\Post_Timeline',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/components/load-more.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/post-timeline/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/load-more/load-more.min.js',
                ],
            ],
        ],
        'fancy-text' => [
            'class' => '\Essential_Addons_Elementor\Elements\Fancy_Text',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/fancy-text/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/fancy-text/fancy-text.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/fancy-text/index.min.js',
                ],
            ],
        ],
        'creative-btn' => [
            'class' => '\Essential_Addons_Elementor\Elements\Creative_Button',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/creative-btn/index.min.css',
                ],
            ],
        ],
        'count-down' => [
            'class' => '\Essential_Addons_Elementor\Elements\Countdown',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/count-down/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/count-down/countdown.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/count-down/index.min.js',
                ],
            ],
        ],
        'team-members' => [
            'class' => '\Essential_Addons_Elementor\Elements\Team_Member',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/team-members/index.min.css',
                ],
            ],
        ],
        'testimonials' => [
            'class' => '\Essential_Addons_Elementor\Elements\Testimonial',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/testimonials/index.min.css',
                ],
            ],
        ],
        'info-box' => [
            'class' => '\Essential_Addons_Elementor\Elements\Info_Box',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/info-box/index.min.css',
                ],
            ],
        ],
        'flip-box' => [
            'class' => '\Essential_Addons_Elementor\Elements\Flip_Box',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/flip-box/index.min.css',
                ],
            ],
        ],
        'call-to-action' => [
            'class' => '\Essential_Addons_Elementor\Elements\Cta_Box',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/call-to-action/index.min.css',
                ],
            ],
        ],
        'dual-header' => [
            'class' => '\Essential_Addons_Elementor\Elements\Dual_Color_Header',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/dual-header/index.min.css',
                ],
            ],
        ],
        'price-table' => [
            'class' => '\Essential_Addons_Elementor\Elements\Pricing_Table',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/vendor/tooltipster/tooltipster.bundle.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/price-table/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/tooltipster/tooltipster.bundle.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/price-table/index.min.js',
                ],
            ],
        ],
        'twitter-feed' => [
            'class' => '\Essential_Addons_Elementor\Elements\Twitter_Feed',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/twitter-feed/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/imagesLoaded/imagesloaded.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/twitter-feed/index.min.js',
                ],
            ],
        ],
        'data-table' => [
            'class' => '\Essential_Addons_Elementor\Elements\Data_Table',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/data-table/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/data-table/index.min.js',
                ],
            ],
        ],
        'filter-gallery' => [
            'class' => '\Essential_Addons_Elementor\Elements\Filterable_Gallery',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/components/load-more.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/magnific-popup/index.min.css',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/filter-gallery/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/imagesLoaded/imagesloaded.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/isotope/isotope.pkgd.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/magnific-popup/jquery.magnific-popup.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/filter-gallery/index.min.js',
                ],
            ],
        ],
        'image-accordion' => [
            'class' => '\Essential_Addons_Elementor\Elements\Image_Accordion',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/image-accordion/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/image-accordion/index.min.js',
                ],
            ],
        ],
        'content-ticker' => [
            'class' => '\Essential_Addons_Elementor\Elements\Content_Ticker',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/content-ticker/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/content-ticker/index.min.js',
                ],
            ],
        ],
        'tooltip' => [
            'class' => '\Essential_Addons_Elementor\Elements\Tooltip',
            'dependency' => [
                'css' => [

                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/tooltip/index.min.css',
                ],
            ],
        ],
        'adv-accordion' => [
            'class' => '\Essential_Addons_Elementor\Elements\Adv_Accordion',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/adv-accordion/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/adv-accordion/index.min.js',
                ],
            ],
        ],
        'adv-tabs' => [
            'class' => '\Essential_Addons_Elementor\Elements\Adv_Tabs',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/adv-tabs/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/adv-tabs/index.min.js',
                ],
            ],
        ],
        'progress-bar' => [
            'class' => '\Essential_Addons_Elementor\Elements\Progress_Bar',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/progress-bar/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/inview/inview.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/vendor/progress-bar/progress-bar.min.js',
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/progress-bar/index.min.js',
                ],
            ],
        ],
        'feature-list' => [
            'class' => '\Essential_Addons_Elementor\Elements\Feature_List',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/feature-list/index.min.css',
                ],
            ],
        ],
        'product-grid' => [
            'class' => '\Essential_Addons_Elementor\Elements\Product_Grid',
            'condition' => [
                'function_exists',
                'WC',
            ],
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/product-grid/index.min.css',
                ],
            ],
        ],
        'contact-form-7' => [
            'class' => '\Essential_Addons_Elementor\Elements\Contact_Form_7',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/contact-form-7/index.min.css',
                ],
            ],
        ],
        'weforms' => [
            'class' => '\Essential_Addons_Elementor\Elements\WeForms',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/weforms/index.min.css',
                ],
            ],
        ],
        'ninja-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\NinjaForms',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/ninja-form/index.min.css',
                ],
            ],
        ],
        'gravity-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\GravityForms',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/gravity-form/index.min.css',
                ],
            ],
        ],
        'caldera-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\Caldera_Forms',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/caldera-form/index.min.css',
                ],
            ],
        ],
        'wpforms' => [
            'class' => '\Essential_Addons_Elementor\Elements\WpForms',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/wpforms/index.min.css',
                ],
            ],
        ],
        'fluentform' => [
            'class' => '\Essential_Addons_Elementor\Elements\FluentForm',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/fluentform/index.min.css',
                ],
            ],
        ],
    ],
    'extensions' => [
        'eael-reading-progress' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Reading_Progress',
            'dependency' => [
                'css' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/css/reading-progress/index.min.css',
                ],
                'js' => [
                    EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'assets/front-end/js/reading-progress/index.min.js',
                ],
            ],
        ],
        'eael-post-duplicator' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Post_Duplicator',
        ]
    ],
];

return $config;

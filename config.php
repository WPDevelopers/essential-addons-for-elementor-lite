<?php

$config = [
    'elements' => [
        'post-grid' => [
            'class' => '\Essential_Addons_Elementor\Elements\Post_Grid',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/load-more.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/post-grid.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/product-grid.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/load-more.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/post-grid.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'post-timeline' => [
            'class' => '\Essential_Addons_Elementor\Elements\Post_Timeline',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/load-more.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/post-timeline.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/load-more.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'fancy-text' => [
            'class' => '\Essential_Addons_Elementor\Elements\Fancy_Text',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/fancy-text.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/morphext/morphext.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/typed/typed.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/fancy-text.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'creative-btn' => [
            'class' => '\Essential_Addons_Elementor\Elements\Creative_Button',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/creative-btn.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'count-down' => [
            'class' => '\Essential_Addons_Elementor\Elements\Countdown',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/count-down.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/countdown/countdown.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/count-down.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'team-members' => [
            'class' => '\Essential_Addons_Elementor\Elements\Team_Member',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/team-members.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'testimonials' => [
            'class' => '\Essential_Addons_Elementor\Elements\Testimonial',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/testimonials.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'info-box' => [
            'class' => '\Essential_Addons_Elementor\Elements\Info_Box',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/info-box.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'flip-box' => [
            'class' => '\Essential_Addons_Elementor\Elements\Flip_Box',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/flip-box.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'call-to-action' => [
            'class' => '\Essential_Addons_Elementor\Elements\Cta_Box',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/call-to-action.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'dual-header' => [
            'class' => '\Essential_Addons_Elementor\Elements\Dual_Color_Header',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/dual-header.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'price-table' => [
            'class' => '\Essential_Addons_Elementor\Elements\Pricing_Table',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/tooltipster/tooltipster.bundle.min.css',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/price-table.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/tooltipster/tooltipster.bundle.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/price-table.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'twitter-feed' => [
            'class' => '\Essential_Addons_Elementor\Elements\Twitter_Feed',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/twitter-feed.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/twitter-feed.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'facebook-feed' => [
            'class' => '\Essential_Addons_Elementor\Elements\Facebook_Feed',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/load-more.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/facebook-feed.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/facebook-feed.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'advanced-data-table' => [
            'class' => '\Essential_Addons_Elementor\Elements\Advanced_Data_Table',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-edit/quill/quill.bubble.min.css',
                        'type' => 'lib',
                        'context' => 'edit',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/advanced-data-table.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-edit/quill/quill.min.js',
                        'type' => 'lib',
                        'context' => 'edit',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/advanced-data-table.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/advanced-data-table.min.js',
                        'type' => 'self',
                        'context' => 'edit',
                    ],
                ],
            ],
        ],
        'data-table' => [
            'class' => '\Essential_Addons_Elementor\Elements\Data_Table',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/data-table.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/data-table.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'filter-gallery' => [
            'class' => '\Essential_Addons_Elementor\Elements\Filterable_Gallery',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/load-more.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/magnific-popup/magnific-popup.min.css',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/filterable-gallery.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/magnific-popup/jquery.magnific-popup.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/filterable-gallery.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'image-accordion' => [
            'class' => '\Essential_Addons_Elementor\Elements\Image_Accordion',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/image-accordion.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/image-accordion.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'content-ticker' => [
            'class' => '\Essential_Addons_Elementor\Elements\Content_Ticker',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/content-ticker.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/content-ticker.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'tooltip' => [
            'class' => '\Essential_Addons_Elementor\Elements\Tooltip',
            'dependency' => [
                'css' => [

                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/tooltip.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'adv-accordion' => [
            'class' => '\Essential_Addons_Elementor\Elements\Adv_Accordion',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/advanced-accordion.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/advanced-accordion.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'adv-tabs' => [
            'class' => '\Essential_Addons_Elementor\Elements\Adv_Tabs',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/advanced-tabs.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/advanced-tabs.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'progress-bar' => [
            'class' => '\Essential_Addons_Elementor\Elements\Progress_Bar',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/progress-bar.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/inview/inview.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/progress-bar.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'feature-list' => [
            'class' => '\Essential_Addons_Elementor\Elements\Feature_List',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/feature-list.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'product-grid' => [
            'class' => '\Essential_Addons_Elementor\Elements\Product_Grid',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/load-more.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
	                [
		                'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/quick-view.min.css',
		                'type' => 'self',
		                'context' => 'view',
	                ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/product-grid.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js'  => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
	                [
		                'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
		                'type'    => 'lib',
		                'context' => 'view',
	                ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/load-more.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                    [
	                    'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/quick-view.min.js',
	                    'type' => 'self',
	                    'context' => 'view',
                    ],
	                [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/product-grid.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'contact-form-7' => [
            'class' => '\Essential_Addons_Elementor\Elements\Contact_Form_7',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/contact-form-7.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'weforms' => [
            'class' => '\Essential_Addons_Elementor\Elements\WeForms',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/weforms.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'ninja-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\NinjaForms',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/ninja-form.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'formstack' => [
            'class' => '\Essential_Addons_Elementor\Elements\Formstack',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/formstack.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'gravity-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\GravityForms',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/gravity-form.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
	                [
		                'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/gravity-form.min.js',
		                'type' => 'self',
		                'context' => 'edit',
	                ],
                ],
            ],
        ],
        'caldera-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\Caldera_Forms',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/caldera-form.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'wpforms' => [
            'class' => '\Essential_Addons_Elementor\Elements\WpForms',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/wpforms.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'fluentform' => [
            'class' => '\Essential_Addons_Elementor\Elements\FluentForm',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/fluentform.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'typeform' => [
            'class' => '\Essential_Addons_Elementor\Elements\TypeForm',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/typeform.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/embed/embed.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/typeform.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'betterdocs-category-grid' => [
            'class' => '\Essential_Addons_Elementor\Elements\Betterdocs_Category_Grid',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/betterdocs-category-grid.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
	                [
		                'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
		                'type' => 'lib',
		                'context' => 'view',
	                ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/betterdocs-category-grid.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'betterdocs-category-box' => [
            'class' => '\Essential_Addons_Elementor\Elements\Betterdocs_Category_Box',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/betterdocs-category-box.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'betterdocs-search-form' => [
            'class' => '\Essential_Addons_Elementor\Elements\Betterdocs_Search_Form',
        ],
        'sticky-video' => [
            'class' => '\Essential_Addons_Elementor\Elements\Sticky_Video',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/plyr/plyr.min.css',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/sticky-video.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/plyr/plyr.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/sticky-video.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'event-calendar' => [
            'class' => '\Essential_Addons_Elementor\Elements\Event_Calendar',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/full-calendar/calendar-main.min.css',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/full-calendar/daygrid.min.css',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/full-calendar/timegrid.min.css',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/full-calendar/listgrid.min.css',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/event-calendar.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/full-calendar/locales-all.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/moment/moment.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/full-calendar/calendar-main.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/full-calendar/daygrid.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/full-calendar/timegrid.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/full-calendar/listgrid.min.js',
                        'type' => 'lib',
                        'context' => 'view',
                    ],
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/event-calendar.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'embedpress' => [
            'class' => '\Essential_Addons_Elementor\Elements\EmbedPress',
            'condition' => [
                'class_exists',
                '\EmbedPress\Elementor\Embedpress_Elementor_Integration',
                true,
            ],
        ],

        'crowdfundly-organization' => [
	        'class' => '\Essential_Addons_Elementor\Elements\Crowdfundly_Organization',
	        'condition' => [
		        'defined',
		        'CROWDFUNDLY_VERSION',
		        true,
	        ],
        ],

        'crowdfundly-all-campaign' => [
	        'class' => '\Essential_Addons_Elementor\Elements\Crowdfundly_All_Campaign',
	        'condition' => [
		        'defined',
		        'CROWDFUNDLY_VERSION',
		        true,
	        ],
        ],

        'crowdfundly-single-campaign' => [
	        'class' => '\Essential_Addons_Elementor\Elements\Crowdfundly_Single_Campaign',
	        'condition' => [
		        'defined',
		        'CROWDFUNDLY_VERSION',
		        true,
	        ],
        ],

        'woo-checkout' => [
            'class' => '\Essential_Addons_Elementor\Elements\Woo_Checkout',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/woo-checkout.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/woo-checkout.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'woo-cart' => [
            'class' => '\Essential_Addons_Elementor\Elements\Woo_Cart',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/woo-cart.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/woo-cart.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'login-register' => [
            'class' => '\Essential_Addons_Elementor\Elements\Login_Register',
            'dependency' => [
                'css' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/login-register.min.css',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/login-register.min.js',
                        'type' => 'self',
                        'context' => 'view',
                    ],
                ],
            ],
        ],
        'woocommerce-review' => [
            'class' => '\Essential_Addons_Elementor\Elements\Woocommerce_Review',
            'condition' => [
                'function_exists',
                'run_reviewx',
                true,
            ],
        ],
        'career-page' => [
            'class' => '\Essential_Addons_Elementor\Elements\Career_Page',
            'condition' => [
                'function_exists',
                'run_easyjobs',
                true,
            ],
        ],
        'woo-product-compare'  => [
	        'class'      => '\Essential_Addons_Elementor\Elements\Woo_Product_Compare',
	        'dependency' => [
		        'css' => [
			        [
				        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/woo-product-compare.min.css',
				        'type' => 'self',
				        'context' => 'view',
			        ],
		        ],
	        ],
        ],
	    'woo-product-carousel' => [
		    'class' => '\Essential_Addons_Elementor\Elements\Woo_Product_Carousel',
		    'dependency' => [
			    'css' => [
				    [
					    'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/quick-view.min.css',
					    'type' => 'self',
					    'context' => 'view',
				    ],
				    [
					    'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/woo-product-carousel.min.css',
					    'type' => 'self',
					    'context' => 'view',
				    ],
			    ],
			    'js'  => [
				    [
					    'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/quick-view.min.js',
					    'type' => 'self',
					    'context' => 'view',
				    ],
				    [
					    'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/woo-product-carousel.min.js',
					    'type' => 'self',
					    'context' => 'view',
				    ],
			    ],
		    ],
	    ],
        'simple-menu' => [
	        'class' => '\Essential_Addons_Elementor\Elements\Simple_Menu',
	        'dependency' => [
		        'css' => [
			        [
				        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/simple-menu.min.css',
				        'type' => 'self',
				        'context' => 'view',
			        ],
		        ],
		        'js'  => [
			        [
				        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/simple-menu.min.js',
				        'type' => 'self',
				        'context' => 'view',
			        ],
		        ],
	        ],
        ],
        'woo-product-gallery' => [
	        'class' => '\Essential_Addons_Elementor\Elements\Woo_Product_Gallery',
	        'dependency' => [
		        'css' => [
			        [
				        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/load-more.min.css',
				        'type' => 'self',
				        'context' => 'view',
			        ],
			        [
				        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/quick-view.min.css',
				        'type' => 'self',
				        'context' => 'view',
			        ],
			        [
				        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/woo-product-gallery.min.css',
				        'type' => 'self',
				        'context' => 'view',
			        ],
		        ],
		        'js'  => [
			        [
				        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
				        'type' => 'lib',
				        'context' => 'view',
			        ],
			        [
				        'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
				        'type'    => 'lib',
				        'context' => 'view',
			        ],
			        [
				        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/load-more.min.js',
				        'type' => 'self',
				        'context' => 'view',
			        ],
			        [
				        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/quick-view.min.js',
				        'type' => 'self',
				        'context' => 'view',
			        ],
			        [
				        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/woo-product-gallery.min.js',
				        'type' => 'self',
				        'context' => 'view',
			        ],
		        ],
	        ],
        ],
        'interactive-circle' => [
	        'class' => '\Essential_Addons_Elementor\Elements\Interactive_Circle',
	        'dependency' => [
		        'css' => [
			        [
				        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/interactive-circle.min.css',
				        'type' => 'self',
				        'context' => 'view',
			        ],
		        ],
		        'js'  => [
                    [
                        'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/waypoint/waypoints.min.js',
                        'type'    => 'lib',
                        'context' => 'view',
                    ],
			        [
				        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/interactive-circle.min.js',
				        'type' => 'self',
				        'context' => 'view',
			        ],
		        ],
	        ],
        ],
        'better-payment' => [
            'class' => '\Essential_Addons_Elementor\Elements\Better_Payment',
            'condition' => [
                'class_exists',
                'Better_Payment',
                true,
            ],
        ],
    ],
    'extensions' => [
        'promotion' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Promotion',
        ],
        'custom-js' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Custom_JS',
        ],
        'reading-progress' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Reading_Progress',
            'dependency' => [
                //     'css' => [
                //         [
                //             'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/reading-progress.min.css',
                //             'type' => 'self',
                //             'context' => 'view',
                //         ],
                //     ],
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/reading-progress.min.js',
                        'type' => 'self',
                        'context' => 'edit',
                    ],
                    //         [
                    //             'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/reading-progress.min.js',
                    //             'type' => 'self',
                    //             'context' => 'view',
                    //         ],
                ],
            ],
        ],
        'table-of-content' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Table_of_Content',
             'dependency' => [
//                 'css' => [
//                     [
//                         'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/table-of-content.min.css',
//                         'type' => 'self',
//                         'context' => 'edit',
//                     ],
//                 ],
                 'js' => [
                     [
                         'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/table-of-content.min.js',
                         'type' => 'self',
                         'context' => 'edit',
                     ],
                 ],
             ],
        ],
        'post-duplicator' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Post_Duplicator',
        ],
        'scroll-to-top' => [
            'class' => '\Essential_Addons_Elementor\Extensions\Scroll_to_Top',
            'dependency' => [
                'js' => [
                    [
                        'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/scroll-to-top.min.js',
                        'type' => 'self',
                        'context' => 'edit',
                    ],
                ],
            ],
        ],
    ],
];

return $config;

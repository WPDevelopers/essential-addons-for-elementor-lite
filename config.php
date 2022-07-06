<?php

$config = array(
	'elements'   => array(
		'post-grid'                   => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Post_Grid',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/load-more.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/post-grid.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/product-grid.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/load-more.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/post-grid.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'post-timeline'               => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Post_Timeline',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/load-more.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/post-timeline.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/load-more.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'fancy-text'                  => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Fancy_Text',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/fancy-text.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/morphext/morphext.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/typed/typed.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/fancy-text.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'creative-btn'                => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Creative_Button',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/creative-btn.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'count-down'                  => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Countdown',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/count-down.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/countdown/countdown.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/count-down.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'team-members'                => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Team_Member',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/team-members.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'testimonials'                => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Testimonial',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/testimonials.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'info-box'                    => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Info_Box',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/info-box.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'flip-box'                    => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Flip_Box',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/flip-box.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'call-to-action'              => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Cta_Box',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/call-to-action.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'dual-header'                 => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Dual_Color_Header',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/dual-header.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'price-table'                 => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Pricing_Table',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/tooltipster/tooltipster.bundle.min.css',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/price-table.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/tooltipster/tooltipster.bundle.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/price-table.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'twitter-feed'                => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Twitter_Feed',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/twitter-feed.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/twitter-feed.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'facebook-feed'               => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Facebook_Feed',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/load-more.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/facebook-feed.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/facebook-feed.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'advanced-data-table'         => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Advanced_Data_Table',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-edit/quill/quill.bubble.min.css',
						'type'    => 'lib',
						'context' => 'edit',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/advanced-data-table.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-edit/quill/quill.min.js',
						'type'    => 'lib',
						'context' => 'edit',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/advanced-data-table.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/advanced-data-table.min.js',
						'type'    => 'self',
						'context' => 'edit',
					),
				),
			),
		),
		'data-table'                  => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Data_Table',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/data-table.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/data-table.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'filter-gallery'              => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Filterable_Gallery',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/load-more.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/magnific-popup/magnific-popup.min.css',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/filterable-gallery.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/magnific-popup/jquery.magnific-popup.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/filterable-gallery.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'image-accordion'             => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Image_Accordion',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/image-accordion.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/image-accordion.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'content-ticker'              => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Content_Ticker',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/content-ticker.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/content-ticker.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'tooltip'                     => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Tooltip',
			'dependency' => array(
				'css' => array(

					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/tooltip.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'adv-accordion'               => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Adv_Accordion',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/advanced-accordion.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/advanced-accordion.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'adv-tabs'                    => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Adv_Tabs',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/advanced-tabs.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/advanced-tabs.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'progress-bar'                => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Progress_Bar',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/progress-bar.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/inview/inview.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/progress-bar.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'feature-list'                => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Feature_List',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/feature-list.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'product-grid'                => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Product_Grid',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/load-more.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/quick-view.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/product-grid.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/load-more.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/quick-view.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/product-grid.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'contact-form-7'              => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Contact_Form_7',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/contact-form-7.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'weforms'                     => array(
			'class'      => '\Essential_Addons_Elementor\Elements\WeForms',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/weforms.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'ninja-form'                  => array(
			'class'      => '\Essential_Addons_Elementor\Elements\NinjaForms',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/ninja-form.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'formstack'                   => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Formstack',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/formstack.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'gravity-form'                => array(
			'class'      => '\Essential_Addons_Elementor\Elements\GravityForms',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/gravity-form.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/gravity-form.min.js',
						'type'    => 'self',
						'context' => 'edit',
					),
				),
			),
		),
		'caldera-form'                => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Caldera_Forms',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/caldera-form.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'wpforms'                     => array(
			'class'      => '\Essential_Addons_Elementor\Elements\WpForms',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/wpforms.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'fluentform'                  => array(
			'class'      => '\Essential_Addons_Elementor\Elements\FluentForm',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/fluentform.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'typeform'                    => array(
			'class'      => '\Essential_Addons_Elementor\Elements\TypeForm',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/typeform.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/embed/embed.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/typeform.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'betterdocs-category-grid'    => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Betterdocs_Category_Grid',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/betterdocs-category-grid.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/betterdocs-category-grid.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'betterdocs-category-box'     => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Betterdocs_Category_Box',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/betterdocs-category-box.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'betterdocs-search-form'      => array(
			'class' => '\Essential_Addons_Elementor\Elements\Betterdocs_Search_Form',
		),
		'sticky-video'                => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Sticky_Video',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/plyr/plyr.min.css',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/sticky-video.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/plyr/plyr.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/sticky-video.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'event-calendar'              => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Event_Calendar',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/full-calendar/calendar-main.min.css',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/full-calendar/daygrid.min.css',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/full-calendar/timegrid.min.css',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/lib-view/full-calendar/listgrid.min.css',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/event-calendar.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/full-calendar/locales-all.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/moment/moment.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/full-calendar/calendar-main.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/full-calendar/daygrid.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/full-calendar/timegrid.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/full-calendar/listgrid.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/event-calendar.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'embedpress'                  => array(
			'class'     => '\Essential_Addons_Elementor\Elements\EmbedPress',
			'condition' => array(
				'class_exists',
				'\EmbedPress\Elementor\Embedpress_Elementor_Integration',
				true,
			),
		),

		'crowdfundly-organization'    => array(
			'class'     => '\Essential_Addons_Elementor\Elements\Crowdfundly_Organization',
			'condition' => array(
				'class_exists',
				'Crowdfundly',
				true,
			),
		),

		'crowdfundly-all-campaign'    => array(
			'class'     => '\Essential_Addons_Elementor\Elements\Crowdfundly_All_Campaign',
			'condition' => array(
				'class_exists',
				'Crowdfundly',
				true,
			),
		),

		'crowdfundly-single-campaign' => array(
			'class'     => '\Essential_Addons_Elementor\Elements\Crowdfundly_Single_Campaign',
			'condition' => array(
				'class_exists',
				'Crowdfundly',
				true,
			),
		),

		'woo-checkout'                => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Woo_Checkout',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/woo-checkout.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/woo-checkout.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'woo-cart'                    => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Woo_Cart',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/woo-cart.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/woo-cart.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'login-register'              => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Login_Register',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/login-register.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/login-register.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'woocommerce-review'          => array(
			'class'     => '\Essential_Addons_Elementor\Elements\Woocommerce_Review',
			'condition' => array(
				'function_exists',
				'run_reviewx',
				true,
			),
		),
		'career-page'                 => array(
			'class'     => '\Essential_Addons_Elementor\Elements\Career_Page',
			'condition' => array(
				'function_exists',
				'run_easyjobs',
				true,
			),
		),
		'woo-product-compare'         => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Woo_Product_Compare',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/woo-product-compare.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'woo-product-carousel'        => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Woo_Product_Carousel',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/quick-view.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/woo-product-carousel.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/quick-view.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/woo-product-carousel.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'simple-menu'                 => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Simple_Menu',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/simple-menu.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/simple-menu.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'woo-product-gallery'         => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Woo_Product_Gallery',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/load-more.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/quick-view.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/woo-product-gallery.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/imagesloaded/imagesloaded.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/isotope/isotope.pkgd.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/load-more.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/quick-view.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/woo-product-gallery.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'interactive-circle'          => array(
			'class'      => '\Essential_Addons_Elementor\Elements\Interactive_Circle',
			'dependency' => array(
				'css' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/interactive-circle.min.css',
						'type'    => 'self',
						'context' => 'view',
					),
				),
				'js'  => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/lib-view/waypoint/waypoints.min.js',
						'type'    => 'lib',
						'context' => 'view',
					),
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/interactive-circle.min.js',
						'type'    => 'self',
						'context' => 'view',
					),
				),
			),
		),
		'better-payment'              => array(
			'class'     => '\Essential_Addons_Elementor\Elements\Better_Payment',
			'condition' => array(
				'class_exists',
				'Better_Payment',
				true,
			),
		),
	),
	'extensions' => array(
		'promotion'        => array(
			'class' => '\Essential_Addons_Elementor\Extensions\Promotion',
		),
		'custom-js'        => array(
			'class' => '\Essential_Addons_Elementor\Extensions\Custom_JS',
		),
		'reading-progress' => array(
			'class'      => '\Essential_Addons_Elementor\Extensions\Reading_Progress',
			'dependency' => array(
				// 'css' => [
				// [
				// 'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/reading-progress.min.css',
				// 'type' => 'self',
				// 'context' => 'view',
				// ],
				// ],
				'js' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/reading-progress.min.js',
						'type'    => 'self',
						'context' => 'edit',
					),
					// [
					// 'file' => EAEL_PLUGIN_PATH . 'assets/front-end/js/view/reading-progress.min.js',
					// 'type' => 'self',
					// 'context' => 'view',
					// ],
				),
			),
		),
		'table-of-content' => array(
			'class'      => '\Essential_Addons_Elementor\Extensions\Table_of_Content',
			'dependency' => array(
				// 'css' => [
				// [
				// 'file' => EAEL_PLUGIN_PATH . 'assets/front-end/css/view/table-of-content.min.css',
				// 'type' => 'self',
				// 'context' => 'edit',
				// ],
				// ],
						 'js' => array(
							 array(
								 'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/table-of-content.min.js',
								 'type'    => 'self',
								 'context' => 'edit',
							 ),
						 ),
			),
		),
		'post-duplicator'  => array(
			'class' => '\Essential_Addons_Elementor\Extensions\Post_Duplicator',
		),
		'scroll-to-top'    => array(
			'class'      => '\Essential_Addons_Elementor\Extensions\Scroll_to_Top',
			'dependency' => array(
				'js' => array(
					array(
						'file'    => EAEL_PLUGIN_PATH . 'assets/front-end/js/edit/scroll-to-top.min.js',
						'type'    => 'self',
						'context' => 'edit',
					),
				),
			),
		),
	),
);

return $config;

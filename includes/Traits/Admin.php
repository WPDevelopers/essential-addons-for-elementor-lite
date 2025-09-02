<?php

namespace Essential_Addons_Elementor\Traits;

if ( !defined( 'ABSPATH' ) ) {
    exit();
}

// Exit if accessed directly

use Essential_Addons_Elementor\Classes\Elements_Manager;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;
use PriyoMukul\WPNotice\Notices;
use PriyoMukul\WPNotice\Utils\CacheBank;
use PriyoMukul\WPNotice\Utils\NoticeRemover;

trait Admin {

    private static $cache_bank = null;

    /**
     * Create an admin menu.
     *
     * @since 1.1.2
     */
	public function admin_menu() {
		$menu_notice = ( $this->menu_notice_should_show() ) ? '<span class="eael-menu-notice">1</span>' : '';
		add_menu_page(
			__( 'Essential Addons', 'essential-addons-for-elementor-lite' ),
			sprintf( __( 'Essential Addons %s', 'essential-addons-for-elementor-lite' ), $menu_notice ),
			'manage_options',
			'eael-settings',
			[ $this, 'admin_settings_page' ],
			$this->safe_url( EAEL_PLUGIN_URL . 'assets/admin/images/ea-icon-new-white.svg' ),
			'58.6'
		);
	}

    /**
     * Loading all essential scripts
     *
     * @since 1.1.2
     */
    public function admin_enqueue_scripts( $hook ) {
        wp_enqueue_style( 'essential_addons_elementor-notice-css', EAEL_PLUGIN_URL . 'assets/admin/css/notice.css', false, EAEL_PLUGIN_VERSION );

        if ( $hook == 'essential-addons_page_template-cloud' ) {
            wp_enqueue_style( 'essential_addons_elementor-template-cloud-css', EAEL_PLUGIN_URL . 'assets/admin/css/cloud.css', false, EAEL_PLUGIN_VERSION );
        }

	    if ( isset( $hook ) && $hook == 'toplevel_page_eael-settings' ) {
		    wp_enqueue_style( 'eael-admin-icon-css', EAEL_PLUGIN_URL . 'includes/templates/admin/icons/style.css', array(), EAEL_PLUGIN_VERSION );
		    wp_enqueue_style( 'eael-admin-css', EAEL_PLUGIN_URL . 'includes/templates/admin/eael-dashboard/dist/assets/ea-dashboard.css', array(), EAEL_PLUGIN_VERSION );
		    wp_enqueue_script( 'eael-admin-dashboard', EAEL_PLUGIN_URL . 'includes/templates/admin/eael-dashboard/dist/assets/ea-dashboard.js', array(), EAEL_PLUGIN_VERSION, true );
		    add_filter( 'wp_script_attributes', [ $this, 'add_type_attribute' ] );

		    $ea_dashboard = [
			    'reactPath'               => EAEL_PLUGIN_URL . 'includes/templates/admin/eael-dashboard/dist/',
			    'is_eapro_activate'       => $this->pro_enabled,
			    'is_templately_installed' => $this->installer->get_local_plugin_data( 'templately/templately.php' ),
			    'menu'                    => [
				    'general'      => [
					    'label' => __( 'General', 'essential-addons-for-elementor-lite' ),
					    'icon'  => 'ea-home'
				    ],
				    'elements'     => [
					    'label' => __( 'Elements', 'essential-addons-for-elementor-lite' ),
					    'icon'  => 'ea-elements'
				    ],
				    'extensions'   => [
					    'label' => __( 'Extensions', 'essential-addons-for-elementor-lite' ),
					    'icon'  => 'ea-extensions'
				    ],
				    'tools'        => [
					    'label' => __( 'Tools', 'essential-addons-for-elementor-lite' ),
					    'icon'  => 'ea-tool'
				    ],
				    'integrations' => [
					    'label' => __( 'Integrations', 'essential-addons-for-elementor-lite' ),
					    'icon'  => 'ea-plug'
				    ],
				    'go-premium'   => [
					    'label' => __( 'Go Premium', 'essential-addons-for-elementor-lite' ),
					    'icon'  => 'ea-lock'
				    ],
			    ],
			    'i18n'                    => [
				    'enable_all'           => __( 'Enable All', 'essential-addons-for-elementor-lite' ),
				    'disable_all'          => __( 'Disable All', 'essential-addons-for-elementor-lite' ),
				    'enable_all_elements'  => __( 'Enable All Elements', 'essential-addons-for-elementor-lite' ),
				    'disable_all_elements' => __( 'Disable All Elements', 'essential-addons-for-elementor-lite' ),
				    'save_settings'        => __( 'Save Settings', 'essential-addons-for-elementor-lite' ),
				    'search_result_for'    => __( 'Search Results for :', 'essential-addons-for-elementor-lite' ),
				    'all_widgets'          => __( 'All Widgets', 'essential-addons-for-elementor-lite' ),
				    'toaster_success_msg'  => __( 'Your changes have been saved successfully.', 'essential-addons-for-elementor-lite' ),
				    'toaster_error_msg'    => __( 'Oops! Something went wrong. Please try again.', 'essential-addons-for-elementor-lite' ),
				    'search_not_found'     => __( 'Sorry, no results found', 'essential-addons-for-elementor-lite' ),
				    'enabling'             => __( 'Enabling...', 'essential-addons-for-elementor-lite' ),
				    'total_elements'       => __( 'Total Elements', 'essential-addons-for-elementor-lite' ),
				    'active'               => __( 'Active', 'essential-addons-for-elementor-lite' ),
				    'inactive'             => __( 'Inactive', 'essential-addons-for-elementor-lite' ),
			    ],
			    'whats_new'               => [
				    'heading' => __( "What's New In Essential Addons 6.0?", 'essential-addons-for-elementor-lite' ),
				    'list'    => [
					    [
						    'label'   => 'New Extension:',
						    'content' => __( 'Dynamic Tags, Hover Interactions, Interactive Animation', 'essential-addons-for-elementor-lite' )
					    ],
					    [
						    'label'   => 'Improvements:',
						    'content' => __( 'Dynamic Widgets, Conditional Display', 'essential-addons-for-elementor-lite' )
					    ],
					    [
						    'label'   => '',
						    'content' => __( 'Elevate your Workflow with the New Essential Addons Dashboard!', 'essential-addons-for-elementor-lite' )
					    ]
				    ],
				    'button'  => [
					    'label' => __( 'View Changelog', 'essential-addons-for-elementor-lite' ),
					    'url'   => 'https://essential-addons.com/view-ea-changelog'
				    ]
			    ],
			    'templately_promo'        => [
				    'heading' => __( 'Unlock 5000+ Ready Templates', 'essential-addons-for-elementor-lite' ),
				    'list'    => [
					    __( 'Stunning Templates For All', 'essential-addons-for-elementor-lite' ),
					    __( 'One-Click Full Site Import', 'essential-addons-for-elementor-lite' ),
					    __( 'Collaborate in Team WorkSpace', 'essential-addons-for-elementor-lite' ),
					    __( 'Unlimited Cloud Storage', 'essential-addons-for-elementor-lite' ),
				    ],
				    'button'  => [
					    'label' => __( 'Enable Templates', 'essential-addons-for-elementor-lite' )
				    ]
			    ],
			    'video_promo'             => [
				    'heading' => __( 'Design Your Website With Most Popular Elementor Addons', 'essential-addons-for-elementor-lite' ),
				    'content' => __( 'Learn to build stunning websites with 100+ elements & extensions of Essential Addons through our easy tutorials and enhance your Elementor site-building experience.', 'essential-addons-for-elementor-lite' ),
				    'image'   => 'images/video-promo.png',
				    'button'  => [
					    'label'    => __( 'Watch Tutorials', 'essential-addons-for-elementor-lite' ),
					    'playlist' => 'https://www.youtube.com/watch?v=2a3GRk_06bg&list=PLWHp1xKHCfxC7JeWSg31vtVbLHGzfxDvh',
					    'url'      => 'https://youtu.be/XPKZzYJcjZU'
				    ]
			    ],
			    'community_box'           => [
				    [
					    'heading'    => __( 'Need Any Help?', 'essential-addons-for-elementor-lite' ),
					    'content'    => __( "If you encounter issues or need assistance, we're here to help or report specific problems on <a href='https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues' target='_blank'>GitHub issues page.</a>", 'essential-addons-for-elementor-lite' ),
					    'button'     => [
						    'label' => __( 'Create a Ticket', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://wpdeveloper.com/support/'
					    ],
					    'icon'       => 'ea-support',
					    'icon_color' => 'eaicon-1'
				    ],
				    [
					    'heading'    => __( 'Join Our Community', 'essential-addons-for-elementor-lite' ),
					    'content'    => __( 'Join the Facebook community to discuss with fellow developers, connect with others, and stay updated.', 'essential-addons-for-elementor-lite' ),
					    'button'     => [
						    'label' => __( 'Join with us', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://www.facebook.com/groups/essentialaddons/'
					    ],
					    'icon'       => 'ea-community',
					    'icon_color' => 'eaicon-2'
				    ],
				    [
					    'heading'    => __( 'View Knowledge Base', 'essential-addons-for-elementor-lite' ),
					    'content'    => __( 'Read comprehensive documentation & learn to build a website easily with Essential Addons.', 'essential-addons-for-elementor-lite' ),
					    'button'     => [
						    'label' => __( 'Read Documentation', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/docs/'
					    ],
					    'icon'       => 'ea-docs',
					    'icon_color' => 'eaicon-3'
				    ],
				    [
					    'heading'    => __( 'Show Your Love', 'essential-addons-for-elementor-lite' ),
					    'content'    => __( 'We love having you in our Essential Addons family every day. Please take 2 minutes to review us and show some love.', 'essential-addons-for-elementor-lite' ),
					    'button'     => [
						    'label' => __( 'Leave a Review', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/ea-show-your-love'
					    ],
					    'icon'       => 'ea-star-lite',
					    'icon_color' => 'eaicon-4'
				    ],
				    [
					    'heading'    => __( 'Continuous Features & Security Updates', 'essential-addons-for-elementor-lite' ),
					    'content'    => __( 'Keep your website secure and up-to-date with regular security updates. Enjoy the convenience of continuous updates with exciting new features.', 'essential-addons-for-elementor-lite' ),
					    'icon'       => 'ea-security-update',
					    'icon_color' => 'eaicon-1'
				    ],
				    [
					    'heading'    => __( 'Priority Support', 'essential-addons-for-elementor-lite' ),
					    'content'    => __( 'Are you encountering issues? Do not worry! Our expert support team is available 24/7 through live chat or support tickets. Our team will reach out to you within 12-24 hours.', 'essential-addons-for-elementor-lite' ),
					    'icon'       => 'ea-priority-support',
					    'icon_color' => 'eaicon-1'
				    ]
			    ],
			    'sidebar_box'             => [
				    'heading' => __( 'Want Advanced Features?', 'essential-addons-for-elementor-lite' ),
				    'content' => __( 'Get more powerful widgets & extensions to elevate your Elementor website', 'essential-addons-for-elementor-lite' ),
				    'review'  => [
					    'count' => __( '3700+', 'essential-addons-for-elementor-lite' ),
					    'label' => __( 'Five Star Reviews', 'essential-addons-for-elementor-lite' )
				    ],
				    'button'  => [
					    'label' => __( 'Upgrade to PRO', 'essential-addons-for-elementor-lite' ),
					    'url'   => 'https://essential-addons.com/upgrade-ea-pro',
					    'icon'  => 'ea-crown-1'
				    ]
			    ],
			    'integration_box'         => [
				    'enable'  => __( 'Activate', 'essential-addons-for-elementor-lite' ),
				    'disable' => __( 'Deactivate', 'essential-addons-for-elementor-lite' ),
				    'list'    => [
					    'bd' => [
						    'slug'     => 'betterdocs',
						    'basename' => 'betterdocs/betterdocs.php',
						    'logo'     => 'images/BD.svg',
						    'title'    => __( 'BetterDocs', 'essential-addons-for-elementor-lite' ),
						    'desc'     => __( 'Create and organize your knowledge base, FAQ & documentation page efficiently, making it easy for visitors to find any helpful article quickly and effortlessly.', 'essential-addons-for-elementor-lite' ),
						    'status'   => is_plugin_active( 'betterdocs/betterdocs.php' )
					    ],
					    'bl' => [
						    'slug'     => 'betterlinks',
						    'basename' => 'betterlinks/betterlinks.php',
						    'logo'     => 'images/BL.svg',
						    'title'    => __( 'BetterLinks', 'essential-addons-for-elementor-lite' ),
						    'desc'     => __( 'Link Shortening tool to create, shorten & manage any URL. It helps to cross promote brands & products and gather analytics reports while running marketing campaigns.', 'essential-addons-for-elementor-lite' ),
						    'status'   => is_plugin_active( 'betterlinks/betterlinks.php' )
					    ],
					    'bp' => [
						    'slug'     => 'better-payment',
						    'basename' => 'better-payment/better-payment.php',
						    'logo'     => 'images/BP.svg',
						    'title'    => __( 'Better Payment', 'essential-addons-for-elementor-lite' ),
						    'desc'     => __( 'Streamline transactions in Elementor by integrating PayPal & Stripe. Experience advanced analytics, validation, and Elementor forms for secure & efficient payments.', 'essential-addons-for-elementor-lite' ),
						    'status'   => is_plugin_active( 'better-payment/better-payment.php' )
					    ],
					    'nx' => [
						    'slug'     => 'notificationx',
						    'basename' => 'notificationx/notificationx.php',
						    'logo'     => 'images/NX.svg',
						    'title'    => __( 'NotificationX', 'essential-addons-for-elementor-lite' ),
						    'desc'     => __( 'Best FOMO & social proof plugin to boost sales conversion by creating stunning sales popups, growth & discount alerts, flashing tabs, notification bars & more.', 'essential-addons-for-elementor-lite' ),
						    'status'   => is_plugin_active( 'notificationx/notificationx.php' )
					    ],
					    'sp' => [
						    'slug'     => 'wp-scheduled-posts',
						    'basename' => 'wp-scheduled-posts/wp-scheduled-posts.php',
						    'logo'     => 'images/SP.svg',
						    'title'    => __( 'SchedulePress', 'essential-addons-for-elementor-lite' ),
						    'desc'     => __( 'Advanced content marketing tool for WordPress to schedule posts & pages with Schedule Calendar, Auto & Manual Scheduler, etc. It also allows auto-social sharing.', 'essential-addons-for-elementor-lite' ),
						    'status'   => is_plugin_active( 'wp-scheduled-posts/wp-scheduled-posts.php' )
					    ],
					    'ej' => [
						    'slug'     => 'easyjobs',
						    'basename' => 'easyjobs/easyjobs.php',
						    'logo'     => 'images/EJ.svg',
						    'title'    => __( 'easy.jobs', 'essential-addons-for-elementor-lite' ),
						    'desc'     => __( 'Job recruitment tool to attract, manage, and hire the right talent faster. This talent recruitment solution lets you manage jobs and career pages in Elementor.', 'essential-addons-for-elementor-lite' ),
						    'status'   => is_plugin_active( 'easyjobs/easyjobs.php' )
					    ],
					    'ep' => [
						    'slug'     => 'embedpress',
						    'basename' => 'embedpress/embedpress.php',
						    'logo'     => 'images/EP.svg',
						    'title'    => __( 'EmbedPress', 'essential-addons-for-elementor-lite' ),
						    'desc'     => __( 'Embed videos, images, gifs, charts, docs, maps, audio, live streams, pdf & more from 150+ sources into your WordPress site and get seamless customization options.', 'essential-addons-for-elementor-lite' ),
						    'status'   => is_plugin_active( 'embedpress/embedpress.php' )
					    ],
					    'eb' => [
						    'slug'     => 'essential-blocks',
						    'basename' => 'essential-blocks/essential-blocks.php',
						    'logo'     => 'images/EB.svg',
						    'title'    => __( 'Essential Blocks', 'essential-addons-for-elementor-lite' ),
						    'desc'     => __( 'Enhance Gutenberg experience with 50+ unique blocks (more coming soon). Boost your block editor with easy-to-use blocks for a simpler WordPress page or post design.', 'essential-addons-for-elementor-lite' ),
						    'status'   => is_plugin_active( 'essential-blocks/essential-blocks.php' )
					    ]
				    ]
			    ],
			    'premium_items'           => [
				    'list' => [
					    [
						    'heading' => __( 'Protected Content', 'essential-addons-for-elementor-lite' ),
						    'content' => __( 'Restrict important data by setting up user permission or giving passwords to a particular area.', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => 'https://essential-addons.com/protected-content/'
						    ],
						    'image'   => 'images/Protected-Content.jpg'
					    ],
					    [
						    'heading' => __( 'Smart Post List', 'essential-addons-for-elementor-lite' ),
						    'content' => __( 'Display your blog posts in an amazing grid layout with advanced search & filter options.', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => 'https://essential-addons.com/post-list/'
						    ],
						    'image'   => 'images/Smart-Post-List.jpg'
					    ],
					    [
						    'heading' => __( 'Woo Product Slider', 'essential-addons-for-elementor-lite' ),
						    'content' => __( 'Showcase your WooCommerce products beautifully with amazing ready slider layouts.', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => 'https://essential-addons.com/woo-product-slider/'
						    ],
						    'image'   => 'images/Woo-Product-Slider.jpg'
					    ],
					    [
						    'heading' => __( 'Post Carousel', 'essential-addons-for-elementor-lite' ),
						    'content' => __( 'Amaze site visitors by displaying your posts creatively. Add transition effects, overlays, & more to showcase your posts beautifully on your site.', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => 'https://essential-addons.com/post-carousel/'
						    ],
						    'image'   => 'images/Post-Carousel.jpg'
					    ],
					    [
						    'heading' => __( 'Instagram Feed', 'essential-addons-for-elementor-lite' ),
						    'content' => __( 'Allows you to display a beautiful & responsive feed of your latest Instagram posts with customizable options and stunning layouts.', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => 'https://essential-addons.com/instagram-feed/'
						    ],
						    'image'   => 'images/Instagram-Feed.jpg'
					    ],
					    [
						    'heading' => __( 'Custom JS', 'essential-addons-for-elementor-lite' ),
						    'content' => __( 'Add custom JavaScript to your WordPress site effortlessly with advanced customization and functionality without modifying core files.', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => 'https://essential-addons.com/custom-js/'
						    ],
						    'image'   => 'images/Custom-JS.jpg'
					    ],
					    [
						    'heading' => __( 'MailChimp', 'essential-addons-for-elementor-lite' ),
						    'content' => __( 'Helps you easily connect your site with MailChimp. You can create and manage subscription forms directly on your website.', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => 'https://essential-addons.com/mailchimp/'
						    ],
						    'image'   => 'images/MailChimp.jpg'
					    ],
					    [
						    'heading' => __( 'Advanced Google Map', 'essential-addons-for-elementor-lite' ),
						    'content' => __( 'Integrates Google Maps to display locations, routes, and markers easily on your site as your preferences.', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => 'https://essential-addons.com/advanced-google-map/'
						    ],
						    'image'   => 'images/Advanced-Google-Map.jpg'
					    ],
					    [
						    'heading' => __( 'Dynamic Gallery', 'essential-addons-for-elementor-lite' ),
						    'content' => __( 'Showcase posts, Woo Products and more images in a captivating and interactive gallery format to highlight visual content dynamically.', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => 'https://essential-addons.com/dynamic-gallery/'
						    ],
						    'image'   => 'images/Dynamic-Gallery.jpg'
					    ]
				    ]
			    ],
			    'enhance_experience'      => [
				    'top_heading' => __( '2+ Million Active Users', 'essential-addons-for-elementor-lite' ),
				    'heading'     => __( "Enhance Your Elementor Experience By <br/> <b>Unlocking</b> <span class='Advance-color'>40+ Advanced PRO</span> <b>Elements</b>", 'essential-addons-for-elementor-lite' ),
				    'review'      => [
					    'count' => __( '3700+', 'essential-addons-for-elementor-lite' ),
					    'label' => __( 'Five Star Reviews', 'essential-addons-for-elementor-lite' )
				    ],
				    'button'      => [
					    'label' => __( 'Upgrade To PRO', 'essential-addons-for-elementor-lite' ),
					    'url'   => 'https://essential-addons.com/upgrade-ea-pro',
					    'icon'  => 'ea-crown-1'
				    ]
			    ],
			    'explore_pro_features'    => [
				    'heading' => __( "Get Access to Advanced Features!", 'essential-addons-for-elementor-lite' ),
				    'content' => __( "Discover the premium features of the most popular elements library for Elementor. Experience the web building experience with:", 'essential-addons-for-elementor-lite' ),
				    'list'    => [
					    __( 'Customization Flexibility in Design with Premium Creative Elements.', 'essential-addons-for-elementor-lite' ),
					    __( 'Advanced WooCommerce Widgets like Checkout, Cross-Sells & more.', 'essential-addons-for-elementor-lite' ),
					    __( 'Cutting-edge Extensions Like Custom JS, Content Protection & more.', 'essential-addons-for-elementor-lite' ),
				    ],
				    'image'   => 'images/img-3.png',
				    'button'  => [
					    'label' => __( 'More Premium Features', 'essential-addons-for-elementor-lite' ),
					    'url'   => 'https://essential-addons.com/premium-features/',
					    'icon'  => 'ea-link'
				    ],
				    'icons'   => [
					    [
						    'label' => __( 'Smart Post List', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/post-list/',
						    'icon'  => 'images/Smart-Post-List.svg'
					    ],
					    [
						    'label' => __( 'Dynamic Gallery', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/dynamic-gallery/',
						    'icon'  => 'images/Dynamic-Gallery.svg'
					    ],
					    [
						    'label' => __( 'Custom JS', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/custom-js/',
						    'icon'  => 'images/Custom-JS.svg'
					    ],
					    [
						    'label' => __( 'Protected Content', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/protected-content/',
						    'icon'  => 'images/Protected-Content.svg'
					    ],
					    [
						    'label' => __( 'Advanced Google Map', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/advanced-google-map/',
						    'icon'  => 'images/Advanced-Google-Map.svg'
					    ],
					    [
						    'label' => __( 'MailChimp', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/mailchimp/',
						    'icon'  => 'images/Mailchimp.svg'
					    ],
					    [
						    'label' => __( 'Instagram Feed', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/instagram-feed/',
						    'icon'  => 'images/Instagram-Feed.svg'
					    ],
					    [
						    'label' => __( 'Woo Product Slider', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/woo-product-slider/',
						    'icon'  => 'images/Woo-Product-Slider.svg'
					    ],
					    [
						    'label' => __( 'Parallax', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/parallax-scrolling/',
						    'icon'  => 'images/Parallax-Effect.svg'
					    ],
					    [
						    'label' => __( 'Post Carousel', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/post-carousel/',
						    'icon'  => 'images/Post-Carousel.svg'
					    ],
					    [
						    'label' => __( 'LearnDash Course List', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/learndash-course-list/',
						    'icon'  => 'images/Learn-Dash-Course-List.svg'
					    ],
					    [
						    'label' => __( 'Particle Effect', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/particle-effect/',
						    'icon'  => 'images/Particles.svg'
					    ],
					    [
						    'label' => __( 'Logo Carousel', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/logo-carousel/',
						    'icon'  => 'images/Logo-Carousel.svg'
					    ],
					    [
						    'label' => __( 'Image Hotspots', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/image-hotspots/',
						    'icon'  => 'images/Image-Hotspots.svg'
					    ],
					    [
						    'label' => __( 'Conditional Display', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/conditional-display/',
						    'icon'  => 'images/Conditional-Display.svg'
					    ],
					    [
						    'label' => __( 'Advanced Search', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/advanced-search',
						    'icon'  => 'images/Advanced-Search.svg'
					    ],
					    [
						    'label' => __( 'Team Member Carousel', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/team-members-carousel/',
						    'icon'  => 'images/Team-Member-Carousel.svg'
					    ],
					    [
						    'label' => __( 'Woo Cross Sells', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/woo-cross-sells/',
						    'icon'  => 'images/Woo-Cross-Sells.svg'
					    ],
					    [
						    'label' => __( 'Woo Account Dashboard', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/woo-account-dashboard/',
						    'icon'  => 'images/Woo-Account-Dashboard.svg'
					    ],
					    [
						    'label' => __( 'Lightbox And Modal', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/lightbox-modal/',
						    'icon'  => 'images/Lightbox-And-Modal.svg'
					    ]
				    ]
			    ],
			    'tools'                   => [
				    'box_1' => [
					    'heading' => __( "Regenerate Assets", 'essential-addons-for-elementor-lite' ),
					    'content' => __( "Essential Addons styles & scripts are saved in Uploads folder. This option will clear all those generated files.", 'essential-addons-for-elementor-lite' ),
					    'icon'    => 'ea-regenerate',
					    'button'  => [
						    'label' => __( 'Regenerate Assets', 'essential-addons-for-elementor-lite' ),
					    ]
				    ],
				    'box_2' => [
					    'heading' => __( "Assets Embed Method", 'essential-addons-for-elementor-lite' ),
					    'content' => __( "Configure the Essential Addons assets embed method. Keep it as default (recommended).", 'essential-addons-for-elementor-lite' ),
					    'icon'    => 'ea-settings',
					    'button'  => [
						    'label' => __( 'CSS Print Method', 'essential-addons-for-elementor-lite' ),
						    'url'   => defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.21.0', '>=' ) ? admin_url( 'admin.php?page=elementor-settings#tab-performance' ) : admin_url( 'admin.php?page=elementor#tab-advanced' )
					    ]
				    ],
				    'box_3' => [
					    'heading' => __( "JS Print Method", 'essential-addons-for-elementor-lite' ),
					    'content' => __( "CSS Print Method is handled by Elementor Settings itself. Use External CSS Files for better performance (recommended).", 'essential-addons-for-elementor-lite' ),
					    'methods' => [
						    'external' => __( 'External file', 'essential-addons-for-elementor-lite' ),
						    'internal' => __( 'Internal Embedding', 'essential-addons-for-elementor-lite' ),
					    ],
					    'name'    => 'eael-js-print-method',
					    'value'   => get_option( 'eael_js_print_method', 'external' )
				    ]
			    ],
			    'extensions'              => [
				    'heading' => __( 'Extensions', 'essential-addons-for-elementor-lite' ),
				    'list'    => [
					    'section-parallax'      => [
						    'key'         => 'section-parallax',
						    'title'       => __( 'Parallax', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/parallax-scrolling/',
						    'doc_link'    => 'https://essential-addons.com/docs/ea-parallax/',
						    'is_pro'      => true,
						    'promotion'   => 'popular',
						    'is_activate' => boolval( $this->get_settings( 'section-parallax' ) )
					    ],
					    'section-particles'     => [
						    'key'         => 'section-particles',
						    'title'       => __( 'Particles', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/particle-effect/',
						    'doc_link'    => 'https://essential-addons.com/docs/ea-particles/',
						    'is_pro'      => true,
						    'is_activate' => boolval( $this->get_settings( 'section-particles' ) )
					    ],
					    'tooltip-section'       => [
						    'key'         => 'tooltip-section',
						    'title'       => __( 'Advanced Tooltip', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/advanced-tooltip/',
						    'doc_link'    => 'https://essential-addons.com/docs/ea-advanced-tooltip/',
						    'is_pro'      => true,
						    'is_activate' => boolval( $this->get_settings( 'tooltip-section' ) )
					    ],
					    'content-protection'    => [
						    'key'         => 'content-protection',
						    'title'       => __( 'Content Protection', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/content-protection/',
						    'doc_link'    => 'https://essential-addons.com/docs/ea-content-protection/',
						    'is_pro'      => true,
						    'promotion'   => 'popular',
						    'is_activate' => boolval( $this->get_settings( 'content-protection' ) )
					    ],
					    'reading-progress'      => [
						    'key'         => 'reading-progress',
						    'title'       => __( 'Reading Progress Bar', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/reading-progress/',
						    'doc_link'    => 'https://essential-addons.com/docs/ea-reading-progress-bar/',
						    'is_pro'      => false,
						    'is_activate' => boolval( $this->get_settings( 'reading-progress' ) )
					    ],
					    'table-of-content'      => [
						    'key'         => 'table-of-content',
						    'title'       => __( 'Table of Contents', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/table-of-content/',
						    'doc_link'    => 'https://essential-addons.com/docs/table-of-content',
						    'is_pro'      => false,
						    'promotion'   => 'popular',
						    'is_activate' => boolval( $this->get_settings( 'table-of-content' ) )
					    ],
					    'post-duplicator'       => [
						    'key'         => 'post-duplicator',
						    'title'       => __( 'Duplicator', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/duplicator/',
						    'doc_link'    => 'https://essential-addons.com/docs/duplicator/',
						    'is_pro'      => false,
						    'setting'     => [ 'id' => 'postDuplicatorSetting' ],
						    'is_activate' => boolval( $this->get_settings( 'post-duplicator' ) )
					    ],
					    'custom-js'             => [
						    'key'         => 'custom-js',
						    'title'       => __( 'Custom JS', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/custom-js/',
						    'doc_link'    => 'https://essential-addons.com/docs/custom-js/',
						    'is_pro'      => false,
						    'promotion'   => 'popular',
						    'is_activate' => boolval( $this->get_settings( 'custom-js' ) )
					    ],
					    'scroll-to-top'         => [
						    'key'         => 'scroll-to-top',
						    'title'       => __( 'Scroll to Top', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/scroll-to-top/',
						    'doc_link'    => 'https://essential-addons.com/docs/scroll-to-top/',
						    'is_pro'      => false,
						    'is_activate' => boolval( $this->get_settings( 'scroll-to-top' ) )
					    ],
					    'conditional-display'   => [
						    'key'         => 'conditional-display',
						    'title'       => __( 'Conditional Display', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/conditional-display/',
						    'doc_link'    => 'https://essential-addons.com/docs/conditional-display/',
						    'is_pro'      => true,
						    'promotion'   => 'updated',
						    'is_activate' => boolval( $this->get_settings( 'conditional-display' ) )
					    ],
					    'wrapper-link'          => [
						    'key'         => 'wrapper-link',
						    'title'       => __( 'Wrapper Link', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/wrapper-link/',
						    'doc_link'    => 'https://essential-addons.com/docs/ea-wrapper-link/',
						    'is_pro'      => false,
						    'is_activate' => boolval( $this->get_settings( 'wrapper-link' ) )
					    ],
					    'custom-cursor'          => [
						    'key'         => 'custom-cursor',
						    'title'       => __( 'Custom Cursor', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/custom-cursor/',
						    'doc_link'    => 'https://essential-addons.com/docs/ea-custom-cursor/',
						    'is_pro'      => true,
							'promotion'   => 'new',
						    'is_activate' => boolval( $this->get_settings( 'custom-cursor' ) )
					    ],
					    'advanced-dynamic-tags' => [
						    'key'         => 'advanced-dynamic-tags',
						    'title'       => __( 'Dynamic Tags', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/dynamic-tags/',
						    'doc_link'    => 'https://essential-addons.com/docs/ea-dynamic-tags/',
						    'is_pro'      => true,
						    'promotion'   => 'new',
						    'is_activate' => boolval( $this->get_settings( 'advanced-dynamic-tags' ) )
					    ],
					    'smooth-animation'      => [
						    'key'         => 'smooth-animation',
						    'title'       => __( 'Interactive Animations', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/interactive-animations/',
						    'doc_link'    => 'https://essential-addons.com/docs/ea-interactive-animations/',
						    'is_pro'      => true,
						    'promotion'   => 'new',
						    'is_activate' => boolval( $this->get_settings( 'smooth-animation' ) )
					    ],
					    'special-hover-effect'  => [
						    'key'         => 'special-hover-effect',
						    'title'       => __( 'Hover Interactions', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/hover-interaction/',
						    'doc_link'    => 'https://essential-addons.com/docs/ea-hover-interaction/',
						    'promotion'   => 'new',
						    'is_activate' => boolval( $this->get_settings( 'special-hover-effect' ) )
						 ],
					    'liquid-glass-effect'  => [
						    'key'         => 'liquid-glass-effect',
						    'title'       => __( 'Liquid Glass Effects', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/liquid-glass-effects/',
						    'doc_link'    => 'https://essential-addons.com/docs/ea-liquid-glass-effects/',
						    'promotion'   => 'new',
						    'is_activate' => boolval( $this->get_settings( 'liquid-glass-effect' ) )
						 ],
				    ]
			    ],
			    'widgets'                 => [
				    'content-elements'         => [
					    'title'    => __( 'Content Elements', 'essential-addons-for-elementor-lite' ),
					    'icon'     => 'ea-content',
					    'elements' => [
						    'creative-btn'         => [
							    'key'         => 'creative-btn',
							    'title'       => __( 'Creative Button', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/creative-buttons/',
							    'doc_link'    => 'https://essential-addons.com/docs/creative-buttons/',
							    'is_activate' => boolval( $this->get_settings( 'creative-btn' ) )
						    ],
						    'team-members'         => [
							    'key'         => 'team-members',
							    'title'       => __( 'Team Member', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/team-members/',
							    'doc_link'    => 'https://essential-addons.com/docs/team-members/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'team-members' ) )
						    ],
						    'testimonials'         => [
							    'key'         => 'testimonials',
							    'title'       => __( 'Testimonial', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/testimonials/',
							    'doc_link'    => 'https://essential-addons.com/docs/testimonials/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'testimonials' ) )
						    ],
						    'flip-box'             => [
							    'key'         => 'flip-box',
							    'title'       => __( 'Flip Box', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/flip-box/',
							    'doc_link'    => 'https://essential-addons.com/docs/flip-box/',
							    'is_activate' => boolval( $this->get_settings( 'flip-box' ) )
						    ],
						    'info-box'             => [
							    'key'         => 'info-box',
							    'title'       => __( 'Info Box', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/info-box/',
							    'doc_link'    => 'https://essential-addons.com/docs/info-box/',
							    'is_activate' => boolval( $this->get_settings( 'info-box' ) )
						    ],
						    'dual-header'          => [
							    'key'         => 'dual-header',
							    'title'       => __( 'Dual Color Heading', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/dual-color-headline/',
							    'doc_link'    => 'https://essential-addons.com/docs/dual-color-headline/',
							    'is_activate' => boolval( $this->get_settings( 'dual-header' ) )
						    ],
						    'tooltip'              => [
							    'key'         => 'tooltip',
							    'title'       => __( 'Tooltip', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/tooltip/',
							    'doc_link'    => 'https://essential-addons.com/docs/tooltip/',
							    'is_activate' => boolval( $this->get_settings( 'tooltip' ) )
						    ],
						    'adv-accordion'        => [
							    'key'         => 'adv-accordion',
							    'title'       => __( 'Advanced Accordion', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/advanced-accordion/',
							    'doc_link'    => 'https://essential-addons.com/docs/advanced-accordion/',
							    'is_activate' => boolval( $this->get_settings( 'adv-accordion' ) )
						    ],
						    'adv-tabs'             => [
							    'key'         => 'adv-tabs',
							    'title'       => __( 'Advanced Tabs', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/advanced-tabs/',
							    'doc_link'    => 'https://essential-addons.com/docs/advanced-tabs/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'adv-tabs' ) )
						    ],
						    'feature-list'         => [
							    'key'         => 'feature-list',
							    'title'       => __( 'Feature List', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/feature-list/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-feature-list/',
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'feature-list' ) )
						    ],
						    'offcanvas'            => [
							    'key'         => 'offcanvas',
							    'title'       => __( 'Offcanvas', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/offcanvas-content/',
							    'doc_link'    => 'https://essential-addons.com/docs/essential-addons-elementor-offcanvas/',
							    'is_pro'      => true,
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'offcanvas' ) )
						    ],
						    'advanced-menu'        => [
							    'key'         => 'advanced-menu',
							    'title'       => __( 'Advanced Menu', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/advanced-menu/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-advanced-menu/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'advanced-menu' ) )
						    ],
						    'toggle'               => [
							    'key'         => 'toggle',
							    'title'       => __( 'Toggle', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/content-toggle/',
							    'doc_link'    => 'https://essential-addons.com/docs/content-toggle/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'toggle' ) )
						    ],
						    'testimonial-slider'   => [
							    'key'         => 'testimonial-slider',
							    'title'       => __( 'Testimonial Slider', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/testimonial-slider/',
							    'doc_link'    => 'https://essential-addons.com/docs/testimonial-slider/',
							    'promotion'   => 'updated',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'testimonial-slider' ) )
						    ],
						    'static-product'       => [
							    'key'         => 'static-product',
							    'title'       => __( 'Static Product', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/static-product/',
							    'doc_link'    => 'https://essential-addons.com/docs/static-product/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'static-product' ) )
						    ],
						    'team-member-carousel' => [
							    'key'         => 'team-member-carousel',
							    'title'       => __( 'Team Member Carousel', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/team-members-carousel/',
							    'doc_link'    => 'https://essential-addons.com/docs/team-member-carousel/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'team-member-carousel' ) )
						    ],
						    'sticky-video'         => [
							    'key'         => 'sticky-video',
							    'title'       => __( 'Sticky Video', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/sticky-video/',
							    'doc_link'    => 'https://essential-addons.com/docs/sticky-video/',
							    'is_activate' => boolval( $this->get_settings( 'sticky-video' ) )
						    ],
						    'event-calendar'       => [
							    'key'         => 'event-calendar',
							    'title'       => __( 'Event Calendar', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/event-calendar/',
							    'doc_link'    => 'https://essential-addons.com/docs/event-calendar/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'event-calendar' ) )
						    ],
						    'simple-menu'          => [
							    'key'         => 'simple-menu',
							    'title'       => __( 'Simple Menu', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/simple-menu/',
							    'doc_link'    => 'https://essential-addons.com/docs/simple-menu/',
							    'is_activate' => boolval( $this->get_settings( 'simple-menu' ) )
						    ],
						    'advanced-search'      => [
							    'key'         => 'advanced-search',
							    'title'       => __( 'Advanced Search', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/advanced-search/',
							    'doc_link'    => 'https://essential-addons.com/docs/advanced-search/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'advanced-search' ) )
						    ],
						    'breadcrumbs'          => [
							    'key'         => 'breadcrumbs',
							    'title'       => __( 'Breadcrumbs', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/breadcrumbs/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-breadcrumbs/',
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'breadcrumbs' ) )
						    ],
							 'code-snippet'		=> [
							    'key'         => 'code-snippet',
							    'title'       => __( 'Code Snippet', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/code-snippet/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-code-snippet/',
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'code-snippet' ) )
						    ],
					    ]
				    ],
				    'dynamic-content-elements' => [
					    'title'    => __( 'Dynamic Content Elements', 'essential-addons-for-elementor-lite' ),
					    'icon'     => 'ea-notes-2',
					    'elements' => [
						    'post-grid'              => [
							    'key'         => 'post-grid',
							    'title'       => __( 'Post Grid', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/post-grid/',
							    'doc_link'    => 'https://essential-addons.com/docs/post-grid/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'post-grid' ) )
						    ],
						    'post-timeline'          => [
							    'key'         => 'post-timeline',
							    'title'       => __( 'Post Timeline', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/post-timeline/',
							    'doc_link'    => 'https://essential-addons.com/docs/post-timeline/',
							    'is_activate' => boolval( $this->get_settings( 'post-timeline' ) )
						    ],
						    'data-table'             => [
							    'key'         => 'data-table',
							    'title'       => __( 'Data Table', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/table/',
							    'doc_link'    => 'https://essential-addons.com/docs/data-table/',
							    'is_activate' => boolval( $this->get_settings( 'data-table' ) )
						    ],
						    'advanced-data-table'    => [
							    'key'         => 'advanced-data-table',
							    'title'       => __( 'Advanced Data Table', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/advanced-data-table/',
							    'doc_link'    => 'https://essential-addons.com/docs/advanced-data-table/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'advanced-data-table' ) )
						    ],
						    'content-ticker'         => [
							    'key'         => 'content-ticker',
							    'title'       => __( 'Content Ticker', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/content-ticker/',
							    'doc_link'    => 'https://essential-addons.com/docs/content-ticker/',
							    'is_activate' => boolval( $this->get_settings( 'content-ticker' ) )
						    ],
						    'adv-google-map'         => [
							    'key'         => 'adv-google-map',
							    'title'       => __( 'Advanced Google Map', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/advanced-google-map/',
							    'doc_link'    => 'https://essential-addons.com/docs/advanced-google-map/',
							    'is_pro'      => true,
							    'setting'     => $this->pro_enabled ? [ 'id' => 'googleMapSetting' ] : [],
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'adv-google-map' ) )
						    ],
						    'post-block'             => [
							    'key'         => 'post-block',
							    'title'       => __( 'Post Block', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/post-block/',
							    'doc_link'    => 'https://essential-addons.com/docs/post-block/',
							    'is_pro'      => true,
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'post-block' ) )
						    ],
						    'post-carousel'          => [
							    'key'         => 'post-carousel',
							    'title'       => __( 'Post Carousel', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/post-carousel/',
							    'doc_link'    => 'https://essential-addons.com/docs/post-carousel/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'post-carousel' ) )
						    ],
						    'post-list'              => [
							    'key'         => 'post-list',
							    'title'       => __( 'Smart Post List', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/post-list/',
							    'doc_link'    => 'https://essential-addons.com/docs/smart-post-list/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'post-list' ) )
						    ],
						    'content-timeline'       => [
							    'key'         => 'content-timeline',
							    'title'       => __( 'Content Timeline', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/content-timeline/',
							    'doc_link'    => 'https://essential-addons.com/docs/content-timeline/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'content-timeline' ) )
						    ],
						    'dynamic-filter-gallery' => [
							    'key'         => 'dynamic-filter-gallery',
							    'title'       => __( 'Dynamic Gallery', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/dynamic-gallery/',
							    'doc_link'    => 'https://essential-addons.com/docs/dynamic-filterable-gallery/',
							    'promotion'   => 'popular',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'dynamic-filter-gallery' ) )
						    ],
						    'nft-gallery'            => [
							    'key'         => 'nft-gallery',
							    'title'       => __( 'NFT Gallery', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/nft-gallery/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-nft-gallery/',
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'nft-gallery' ) )
						    ],
						    'business-reviews'       => [
							    'key'         => 'business-reviews',
							    'title'       => __( 'Business Reviews', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/business-reviews/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-business-reviews/',
							    'setting'     => [ 'id' => 'businessReviewsSetting' ],
							    'is_activate' => boolval( $this->get_settings( 'business-reviews' ) )
						    ],
					    ]
				    ],
				    'creative-elements'        => [
					    'title'    => __( 'Creative Elements', 'essential-addons-for-elementor-lite' ),
					    'icon'     => 'ea-light',
					    'elements' => [
						    'count-down'          => [
							    'key'         => 'count-down',
							    'title'       => __( 'Countdown', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/countdown/',
							    'doc_link'    => 'https://essential-addons.com/docs/creative-elements/ea-countdown/',
							    'is_activate' => boolval( $this->get_settings( 'count-down' ) )
						    ],
						    'fancy-text'          => [
							    'key'         => 'fancy-text',
							    'title'       => __( 'Fancy Text', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/fancy-text/',
							    'doc_link'    => 'https://essential-addons.com/docs/fancy-text/',
							    'is_activate' => boolval( $this->get_settings( 'fancy-text' ) )
						    ],
						    'filter-gallery'      => [
							    'key'         => 'filter-gallery',
							    'title'       => __( 'Filterable Gallery', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/filterable-gallery/',
							    'doc_link'    => 'https://essential-addons.com/docs/filterable-gallery/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'filter-gallery' ) )
						    ],
						    'image-accordion'     => [
							    'key'         => 'image-accordion',
							    'title'       => __( 'Image Accordion', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/image-accordion/',
							    'doc_link'    => 'https://essential-addons.com/docs/image-accordion/',
							    'is_activate' => boolval( $this->get_settings( 'image-accordion' ) )
						    ],
						    'progress-bar'        => [
							    'key'         => 'progress-bar',
							    'title'       => __( 'Progress Bar', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/progress-bar/',
							    'doc_link'    => 'https://essential-addons.com/docs/progress-bar/',
							    'is_activate' => boolval( $this->get_settings( 'progress-bar' ) )
						    ],
						    'interactive-promo'   => [
							    'key'         => 'interactive-promo',
							    'title'       => __( 'Interactive Promo', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/interactive-promo/',
							    'doc_link'    => 'https://essential-addons.com/docs/interactive-promo/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'interactive-promo' ) )
						    ],
						    'counter'             => [
							    'key'         => 'counter',
							    'title'       => __( 'Counter', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/counter/',
							    'doc_link'    => 'https://essential-addons.com/docs/counter/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'counter' ) )
						    ],
						    'lightbox'            => [
							    'key'         => 'lightbox',
							    'title'       => __( 'Lightbox & Modal', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/lightbox-modal/',
							    'doc_link'    => 'https://essential-addons.com/docs/lightbox-modal/',
							    'is_pro'      => true,
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'lightbox' ) )
						    ],
						    'protected-content'   => [
							    'key'         => 'protected-content',
							    'title'       => __( 'Protected Content', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/protected-content/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-protected-content/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'protected-content' ) )
						    ],
						    'img-comparison'      => [
							    'key'         => 'img-comparison',
							    'title'       => __( 'Image Comparison', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/image-comparison/',
							    'doc_link'    => 'https://essential-addons.com/docs/image-comparison/',
							    'is_pro'      => true,
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'img-comparison' ) )
						    ],
						    'flip-carousel'       => [
							    'key'         => 'flip-carousel',
							    'title'       => __( 'Flip Carousel', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/flip-carousel/',
							    'doc_link'    => 'https://essential-addons.com/docs/flip-carousel/',
							    'is_pro'      => true,
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'flip-carousel' ) )
						    ],
						    'logo-carousel'       => [
							    'key'         => 'logo-carousel',
							    'title'       => __( 'Logo Carousel', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/logo-carousel/',
							    'doc_link'    => 'https://essential-addons.com/docs/logo-carousel/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'logo-carousel' ) )
						    ],
						    'interactive-cards'   => [
							    'key'         => 'interactive-cards',
							    'title'       => __( 'Interactive Cards', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/interactive-cards/',
							    'doc_link'    => 'https://essential-addons.com/docs/interactive-cards/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'interactive-cards' ) )
						    ],
						    'one-page-navigation' => [
							    'key'         => 'one-page-navigation',
							    'title'       => __( 'One Page Navigation', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/one-page-nav/',
							    'doc_link'    => 'https://essential-addons.com/docs/one-page-navigation/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'one-page-navigation' ) )
						    ],
						    'image-hotspots'      => [
							    'key'         => 'image-hotspots',
							    'title'       => __( 'Image Hotspots', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/image-hotspots/',
							    'doc_link'    => 'https://essential-addons.com/docs/image-hotspots/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'image-hotspots' ) )
						    ],
						    'divider'             => [
							    'key'         => 'divider',
							    'title'       => __( 'Divider', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/divider/',
							    'doc_link'    => 'https://essential-addons.com/docs/divider/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'divider' ) )
						    ],
						    'image-scroller'      => [
							    'key'         => 'image-scroller',
							    'title'       => __( 'Image Scroller', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/image-scroller/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-image-scroller/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'image-scroller' ) )
						    ],
						    'interactive-circle'  => [
							    'key'         => 'interactive-circle',
							    'title'       => __( 'Interactive Circle', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/interactive-circle/',
							    'doc_link'    => 'https://essential-addons.com/docs/interactive-circle/',
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'interactive-circle' ) )
						    ],
						    'svg-draw'            => [
							    'key'         => 'svg-draw',
							    'title'       => __( 'SVG Draw', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/svg-draw/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-svg-draw/',
							    'is_activate' => boolval( $this->get_settings( 'svg-draw' ) )
						    ],
						    'fancy-chart'         => [
							    'key'         => 'fancy-chart',
							    'title'       => __( 'Fancy Chart', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/fancy-chart/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-fancy-chart/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'fancy-chart' ) )
							 ],
						    'stacked-cards'         => [
							    'key'         => 'stacked-cards',
							    'title'       => __( 'Stacked Cards', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/stacked-cards/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-stacked-cards/',
							    'is_pro'      => true,
								 'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'stacked-cards' ) )
						    ],
						    'sphere-photo-viewer' => [
							    'key'         => 'sphere-photo-viewer',
							    'title'       => __( '360 Degree Photo Viewer', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/360-degree-photo-viewer',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-360-degree-photo-viewer/',
							    'is_pro'      => true,
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'sphere-photo-viewer' ) )
						    ]
					    ]
				    ],
				    'marketing-elements'       => [
					    'title'    => __( 'Marketing Elements', 'essential-addons-for-elementor-lite' ),
					    'icon'     => 'ea-marketing',
					    'elements' => [
						    'call-to-action' => [
							    'key'         => 'call-to-action',
							    'title'       => __( 'Call To Action', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/call-to-action/',
							    'doc_link'    => 'https://essential-addons.com/docs/call-to-action/',
							    'is_activate' => boolval( $this->get_settings( 'call-to-action' ) )
						    ],
						    'price-table'    => [
							    'key'         => 'price-table',
							    'title'       => __( 'Pricing Table', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/pricing-table/',
							    'doc_link'    => 'https://essential-addons.com/docs/pricing-table/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'price-table' ) )
						    ],
						    'multicolumn-pricing-table'    => [
							    'key'         => 'multicolumn-pricing-table',
							    'title'       => __( 'Multicolumn Pricing Table', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/multicolumn-pricing-table/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-multicolumn-pricing-table/',
							    'promotion'   => 'new',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'multicolumn-pricing-table' ) )
						    ],
						    'price-menu'     => [
							    'key'         => 'price-menu',
							    'title'       => __( 'Price Menu', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/price-menu/',
							    'doc_link'    => 'https://essential-addons.com/docs/price-menu/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'price-menu' ) )
						    ],
					    ]
				    ],
				    'form-styler-elements'     => [
					    'title'    => __( 'Form Styler Elements', 'essential-addons-for-elementor-lite' ),
					    'icon'     => 'ea-notes',
					    'elements' => [
						    'contact-form-7' => [
							    'key'         => 'contact-form-7',
							    'title'       => __( 'Contact Form 7', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/contact-form-7/',
							    'doc_link'    => 'https://essential-addons.com/docs/contact-form-7/',
							    'is_activate' => boolval( $this->get_settings( 'contact-form-7' ) )
						    ],
						    'weforms'        => [
							    'key'         => 'weforms',
							    'title'       => __( 'weForms', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/weforms/',
							    'doc_link'    => 'https://essential-addons.com/docs/weforms/',
							    'is_activate' => boolval( $this->get_settings( 'weforms' ) )
						    ],
						    'ninja-form'     => [
							    'key'         => 'ninja-form',
							    'title'       => __( 'Ninja Form', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/ninja-forms/',
							    'doc_link'    => 'https://essential-addons.com/docs/ninja-forms/',
							    'is_activate' => boolval( $this->get_settings( 'ninja-form' ) )
						    ],
						    'gravity-form'   => [
							    'key'         => 'gravity-form',
							    'title'       => __( 'Gravity Form', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/gravity-forms/',
							    'doc_link'    => 'https://essential-addons.com/docs/gravity-forms/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'gravity-form' ) )
						    ],
						    'caldera-form'   => [
							    'key'         => 'caldera-form',
							    'title'       => __( 'Caldera Form', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/caldera-forms/',
							    'doc_link'    => 'https://essential-addons.com/docs/caldera-forms/',
							    'is_activate' => boolval( $this->get_settings( 'caldera-form' ) )
						    ],
						    'wpforms'        => [
							    'key'         => 'wpforms',
							    'title'       => __( 'WPForms', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/wpforms/',
							    'doc_link'    => 'https://essential-addons.com/docs/wpforms/',
							    'is_activate' => boolval( $this->get_settings( 'wpforms' ) )
						    ],
						    'fluentform'     => [
							    'key'         => 'fluentform',
							    'title'       => __( 'Fluent Forms', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/fluent-forms/',
							    'doc_link'    => 'https://essential-addons.com/docs/fluent-form/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'fluentform' ) )
						    ],
						    'formstack'      => [
							    'key'         => 'formstack',
							    'title'       => __( 'Formstack', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/formstack/',
							    'doc_link'    => 'https://essential-addons.com/docs/formstack/',
							    'is_activate' => boolval( $this->get_settings( 'formstack' ) )
						    ],
						    'typeform'       => [
							    'key'         => 'typeform',
							    'title'       => __( 'Typeform', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/typeform/',
							    'doc_link'    => 'https://essential-addons.com/docs/typeform/',
							    'setting'     => [
								    'link' => esc_url( add_query_arg( [
									    'pr_code'      => wp_hash( 'eael_typeform' ),
									    'redirect_uri' => esc_url( admin_url( 'admin.php?page=eael-settings' ) ),
								    ], esc_url( 'https://app.essential-addons.com/typeform/index.php' ) ) ),
							    ],
							    'is_activate' => boolval( $this->get_settings( 'typeform' ) )
						    ],
						    'mailchimp'      => [
							    'key'         => 'mailchimp',
							    'title'       => __( 'Mailchimp', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/mailchimp/',
							    'doc_link'    => 'https://essential-addons.com/docs/mailchimp/',
							    'is_pro'      => true,
							    'setting'     => $this->pro_enabled ? [ 'id' => 'mailchimpSetting' ] : [],
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'mailchimp' ) )
						    ],
						    'login-register' => [
							    'key'         => 'login-register',
							    'title'       => __( 'Login | Register Form', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/login-register-form',
							    'doc_link'    => 'https://essential-addons.com/docs/login-register-form/',
							    'setting'     => [ 'id' => 'loginRegisterSetting' ],
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'login-register' ) )
						    ],
					    ]
				    ],
				    'social-feed-elements'     => [
					    'title'    => __( 'Social Feed Elements', 'essential-addons-for-elementor-lite' ),
					    'icon'     => 'ea-share-fill',
					    'elements' => [
						    'twitter-feed'          => [
							    'key'         => 'twitter-feed',
							    'title'       => __( 'Twitter Feed', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/twitter-feed/',
							    'doc_link'    => 'https://essential-addons.com/docs/twitter-feed/',
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'twitter-feed' ) )
						    ],
						    'twitter-feed-carousel' => [
							    'key'         => 'twitter-feed-carousel',
							    'title'       => __( 'Twitter Feed Carousel', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/twitter-feed-carousel/',
							    'doc_link'    => 'https://essential-addons.com/docs/twitter-feed-carousel/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'twitter-feed-carousel' ) )
						    ],
						    'instagram-gallery'     => [
							    'key'         => 'instagram-gallery',
							    'title'       => __( 'Instagram Feed', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/instagram-feed/',
							    'doc_link'    => 'https://essential-addons.com/docs/instagram-feed/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'instagram-gallery' ) )
						    ],
						    'facebook-feed'         => [
							    'key'         => 'facebook-feed',
							    'title'       => __( 'Facebook Feed', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/facebook-feed/',
							    'doc_link'    => 'https://essential-addons.com/docs/facebook-feed/',
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'facebook-feed' ) )
						    ],
					    ]
				    ],
				    'learn-dash-elements'      => [
					    'title'    => __( 'LearnDash Elements', 'essential-addons-for-elementor-lite' ),
					    'icon'     => 'ea-leardash',
					    'elements' => [
						    'learn-dash-course-list' => [
							    'key'         => 'learn-dash-course-list',
							    'title'       => __( 'LearnDash Course List', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/learndash-course-list/',
							    'doc_link'    => 'https://essential-addons.com/docs/learndash-course-list/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'learn-dash-course-list' ) )
						    ]
					    ]
				    ],
				    'documentation-elements'   => [
					    'title'    => __( 'Documentation Elements', 'essential-addons-for-elementor-lite' ),
					    'icon'     => 'ea-docs-fill',
					    'elements' => [
						    'betterdocs-category-grid' => [
							    'key'         => 'betterdocs-category-grid',
							    'title'       => __( 'BetterDocs Category Grid', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/betterdocs-category-grid/',
							    'doc_link'    => 'https://essential-addons.com/docs/betterdocs-category-grid/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'betterdocs-category-grid' ) )
						    ],
						    'betterdocs-category-box'  => [
							    'key'         => 'betterdocs-category-box',
							    'title'       => __( 'BetterDocs Category Box', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/betterdocs-category-box/',
							    'doc_link'    => 'https://essential-addons.com/docs/betterdocs-category-box/',
							    'is_activate' => boolval( $this->get_settings( 'betterdocs-category-box' ) )
						    ],
						    'betterdocs-search-form'   => [
							    'key'         => 'betterdocs-search-form',
							    'title'       => __( 'BetterDocs Search Form', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/betterdocs-search-form/',
							    'doc_link'    => 'https://essential-addons.com/docs/betterdocs-search-form/',
							    'is_activate' => boolval( $this->get_settings( 'betterdocs-search-form' ) )
						    ],
					    ]
					],
					'figma-design' => [
					    'title'    => __( 'Figma Design Elements', 'essential-addons-for-elementor-lite' ),
					    'icon'     => 'ea-figma-to-elementor',
					    'elements' => [
							'figma-to-elementor'   => [
							    'key'         => 'figma-to-elementor',
							    'title'       => __( 'Figma to Elementor Converter', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/figma-to-elementor-converter/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-figma-to-elementor-converter/',
							    'is_pro'      => true,
								'promotion'   => 'beta',
							    'is_activate' => boolval( $this->get_settings( 'figma-to-elementor' ) )
						    ],
					    ]
				    ],
				    'woocommerce-elements'     => [
					    'title'    => __( 'WooCommerce Elements', 'essential-addons-for-elementor-lite' ),
					    'icon'     => 'ea-cart',
					    'elements' => [
						    'product-grid'          => [
							    'key'         => 'product-grid',
							    'title'       => __( 'Woo Product Grid', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-product-grid/',
							    'doc_link'    => 'https://essential-addons.com/docs/woocommerce-product-grid/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'product-grid' ) )
						    ],
						    'woo-product-list'      => [
							    'key'         => 'woo-product-list',
							    'title'       => __( 'Woo Product List', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-product-list/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-list/',
							    'is_activate' => boolval( $this->get_settings( 'woo-product-list' ) )
						    ],
						    'woo-product-price'     => [
							    'key'         => 'woo-product-price',
							    'title'       => __( 'Woo Product Price', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-product-price/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-price',
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'woo-product-price' ) )
						    ],
						    'woo-product-rating'    => [
							    'key'         => 'woo-product-rating',
							    'title'       => __( 'Woo Product Rating', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-product-rating/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-rating',
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'woo-product-rating' ) )
						    ],
						    'woo-product-images'    => [
							    'key'         => 'woo-product-images',
							    'title'       => __( 'Woo Product Images', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-product-images/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-images/',
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'woo-product-images' ) )
						    ],
						    'woo-add-to-cart'       => [
							    'key'         => 'woo-add-to-cart',
							    'title'       => __( 'Woo Add To Cart', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-add-to-cart/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-woo-add-to-cart/',
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'woo-add-to-cart' ) )
						    ],
						    'woo-collections'       => [
							    'key'         => 'woo-collections',
							    'title'       => __( 'Woo Product Collections', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woocommerce-product-collections/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-collections/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'woo-collections' ) )
						    ],
						    'woo-product-slider'    => [
							    'key'         => 'woo-product-slider',
							    'title'       => __( 'Woo Product Slider', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-product-slider/',
							    'doc_link'    => 'https://essential-addons.com/docs/woo-product-slider/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'woo-product-slider' ) )
						    ],
						    'woo-product-carousel'  => [
							    'key'         => 'woo-product-carousel',
							    'title'       => __( 'Woo Product Carousel', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-product-carousel/',
							    'doc_link'    => 'https://essential-addons.com/docs/woo-product-carousel/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'woo-product-carousel' ) )
						    ],
						    'woo-checkout'          => [
							    'key'         => 'woo-checkout',
							    'title'       => __( 'Woo Checkout', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-checkout/',
							    'doc_link'    => 'https://essential-addons.com/docs/woo-checkout/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'woo-checkout' ) )
						    ],
						    'woo-cart'              => [
							    'key'         => 'woo-cart',
							    'title'       => __( 'Woo Cart', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-cart/',
							    'doc_link'    => 'https://essential-addons.com/docs/woocommerce-cart/',
							    'is_activate' => boolval( $this->get_settings( 'woo-cart' ) )
						    ],
						    'woo-thank-you'         => [
							    'key'         => 'woo-thank-you',
							    'title'       => __( 'Woo Thank You', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-thank-you',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-woo-thank-you',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'woo-thank-you' ) )
						    ],
						    'woo-cross-sells'       => [
							    'key'         => 'woo-cross-sells',
							    'title'       => __( 'Woo Cross Sells', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-cross-sells/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-woo-cross-sells/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'woo-cross-sells' ) )
						    ],
						    'woo-product-compare'   => [
							    'key'         => 'woo-product-compare',
							    'title'       => __( 'Woo Product Compare', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-product-compare/',
							    'doc_link'    => 'https://essential-addons.com/docs/woo-product-compare/',
							    'is_activate' => boolval( $this->get_settings( 'woo-product-compare' ) )
						    ],
						    'woo-product-gallery'   => [
							    'key'         => 'woo-product-gallery',
							    'title'       => __( 'Woo Product Gallery', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-product-gallery/',
							    'doc_link'    => 'https://essential-addons.com/docs/woo-product-gallery/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'woo-product-gallery' ) )
						    ],
						    'woo-account-dashboard' => [
							    'key'         => 'woo-account-dashboard',
							    'title'       => __( 'Woo Account Dashboard', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/woo-account-dashboard/',
							    'doc_link'    => 'https://essential-addons.com/docs/ea-woo-account-dashboard/',
							    'is_pro'      => true,
								'setting'     => $this->pro_enabled ? [ 'id' => 'wooAccountDashboard' ] : [],
							    'is_activate' => boolval( $this->get_settings( 'woo-account-dashboard' ) )
						    ]
					    ]
					]
			    ],
			    'modal'                   => [
				    'postDuplicatorSetting'  => [
					    'title'   => __( "Select Post Types", 'essential-addons-for-elementor-lite' ),
					    'name'    => 'post-duplicator-post-type',
					    'value'   => get_option( 'eael_save_post_duplicator_post_type', 'all' ),
					    'options' => get_post_types( [ 'public' => true, 'show_in_nav_menus' => true ] )
				    ],
				    'googleMapSetting'       => [
					    'title'      => __( "Google Map API Key", 'essential-addons-for-elementor-lite' ),
					    'title_icon' => 'images/map.svg',
					    'label'      => __( "Set API Key", 'essential-addons-for-elementor-lite' ),
					    'name'       => 'google-map-api',
					    'placeholder'=> __( "API Key", 'essential-addons-for-elementor-lite' ),
					    'value'      => get_option( 'eael_save_google_map_api', '' ),
					    'image'      => 'images/map.png',
				    ],
					'wooAccountDashboard'       => [
					    'title'      => __( "Woo Account Dashboard", 'essential-addons-for-elementor-lite' ),
					    'label'      => __( "Set Custom Tabs", 'essential-addons-for-elementor-lite' ),
					    'name'       => 'woo-account-dashboard-custom-tabs',
					    'placeholder'=> __( "Custom Tab 1, Custom Tab 2, Custom Tab 3", 'essential-addons-for-elementor-lite' ),
					    'value'      => get_option( 'eael_woo_ac_dashboard_custom_tabs', '' ),
				    ],
				    'businessReviewsSetting' => [
					    'title'      => __( "Google Place API Key", 'essential-addons-for-elementor-lite' ),
					    'title_icon' => 'images/map.svg',
					    'label'      => __( "Set API Key", 'essential-addons-for-elementor-lite' ),
					    'name'       => 'br_google_place_api_key',
					    'value'      => get_option( 'eael_br_google_place_api_key', '' ),
					    'image'      => 'images/map2.png',
					    'link'       => [
						    'text' => __( 'To configure the API Keys, check out this doc', 'essential-addons-for-elementor-lite' ),
						    'url'  => 'https://developers.google.com/maps/documentation/places/web-service/get-api-key'
					    ]
				    ],
				    'mailchimpSetting'       => [
					    'title'      => __( "MailChimp API Key", 'essential-addons-for-elementor-lite' ),
					    'title_icon' => 'images/mc.svg',
					    'label'      => __( "Set API Key", 'essential-addons-for-elementor-lite' ),
					    'name'       => 'mailchimp-api',
					    'value'      => get_option( 'eael_save_mailchimp_api', '' ),
					    'image'      => 'images/mc.png',
					    'link'       => [
						    'text' => __( 'To configure the API Keys, check out this doc', 'essential-addons-for-elementor-lite' ),
						    'url'  => 'https://essential-addons.com/docs/mailchimp/#3-toc-title'
					    ]
				    ],
				    'loginRegisterSetting'   => [
					    'accordion' => [
						    'reCaptchaV2'    => [
							    'title'  => __( 'reCAPTCHA v2', 'essential-addons-for-elementor-lite' ),
							    'icon'   => 'images/recap.svg',
							    'fields' => [
								    [
									    'name'        => 'lr_recaptcha_sitekey',
									    'value'       => get_option( 'eael_recaptcha_sitekey', '' ),
									    'label'       => __( 'Site Key:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'Site Key', 'essential-addons-for-elementor-lite' ),
								    ],
								    [
									    'name'        => 'lr_recaptcha_secret',
									    'value'       => get_option( 'eael_recaptcha_secret', '' ),
									    'label'       => __( 'Site Secret:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'Site Secret', 'essential-addons-for-elementor-lite' ),
								    ],
								    [
									    'name'        => 'lr_recaptcha_language',
									    'value'       => get_option( 'eael_recaptcha_language', '' ),
									    'label'       => __( 'Language:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'reCAPTCHA Language Code', 'essential-addons-for-elementor-lite' ),
								    ]
							    ]
						    ],
						    'reCaptchaV3'    => [
							    'title'  => __( 'reCAPTCHA v3', 'essential-addons-for-elementor-lite' ),
							    'icon'   => 'images/recap.svg',
							    'fields' => [
								    [
									    'name'        => 'lr_recaptcha_sitekey_v3',
									    'value'       => get_option( 'eael_recaptcha_sitekey_v3', '' ),
									    'label'       => __( 'Site Key:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'Site Key', 'essential-addons-for-elementor-lite' ),
								    ],
								    [
									    'name'        => 'lr_recaptcha_secret_v3',
									    'value'       => get_option( 'eael_recaptcha_secret_v3', '' ),
									    'label'       => __( 'Site Secret:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'Site Secret', 'essential-addons-for-elementor-lite' ),
								    ],
								    [
									    'name'        => 'lr_recaptcha_language_v3',
									    'value'       => get_option( 'eael_recaptcha_language_v3', '' ),
									    'label'       => __( 'Language:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'reCAPTCHA Language Code', 'essential-addons-for-elementor-lite' ),
								    ],
								    [
									    'name'  => 'lr_recaptcha_badge_hide',
									    'value' => get_option( 'eael_recaptcha_badge_hide', '' ),
									    'label' => __( 'Hide Badge', 'essential-addons-for-elementor-lite' ),
									    'type'  => 'checkbox',
									    'info'  => __( 'We are allowed to hide the badge as long as we include the reCAPTCHA branding visibly in the user flow.', 'essential-addons-for-elementor-lite' ),
								    ]
							    ]
						    ],
						    'cloudflareTurnstile'    => [
							    'title'  => __( 'Cloudflare Turnstile', 'essential-addons-for-elementor-lite' ),
							    'icon'   => 'images/cloudflare.svg',
							    'fields' => [
								    [
									    'name'        => 'lr_cloudflare_turnstile_sitekey',
									    'value'       => get_option( 'eael_cloudflare_turnstile_sitekey', '' ),
									    'label'       => __( 'Site Key:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'Site Key', 'essential-addons-for-elementor-lite' ),
								    ],
								    [
									    'name'        => 'lr_cloudflare_turnstile_secretkey',
									    'value'       => get_option( 'eael_cloudflare_turnstile_secretkey', '' ),
									    'label'       => __( 'Secret Key:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'Secret Key', 'essential-addons-for-elementor-lite' ),
								    ]
							    ]
						    ],
						    'googleLogin'    => [
							    'title'  => __( 'Google Login', 'essential-addons-for-elementor-lite' ),
							    'icon'   => 'images/map.svg',
							    'fields' => [
								    [
									    'name'        => 'lr_g_client_id',
									    'value'       => get_option( 'eael_g_client_id', '' ),
									    'label'       => __( 'Google Client ID:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'Google Client ID', 'essential-addons-for-elementor-lite' ),
								    ]
							    ],
							    'isPro'  => true
						    ],
						    'facebookLogin'  => [
							    'title'  => __( 'Facebook Login', 'essential-addons-for-elementor-lite' ),
							    'icon'   => 'images/fb.svg',
							    'fields' => [
								    [
									    'name'        => 'lr_fb_app_id',
									    'value'       => get_option( 'eael_fb_app_id', '' ),
									    'label'       => __( 'Facebook App ID:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'Facebook App ID', 'essential-addons-for-elementor-lite' ),
								    ],
								    [
									    'name'        => 'lr_fb_app_secret',
									    'value'       => get_option( 'eael_fb_app_secret', '' ),
									    'label'       => __( 'Facebook App Secret:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'Facebook App Secret', 'essential-addons-for-elementor-lite' ),
								    ]
							    ],
							    'isPro'  => true
						    ],
						    'mailchimpLogin' => [
							    'title'  => __( 'Mailchimp Integration', 'essential-addons-for-elementor-lite' ),
							    'icon'   => 'images/mcwhite.svg',
							    'fields' => [
								    [
									    'name'        => 'lr_mailchimp_api_key',
									    'value'       => get_option( 'eael_lr_mailchimp_api_key', '' ),
									    'label'       => __( 'Mailchimp API Key:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'Mailchimp API', 'essential-addons-for-elementor-lite' ),
								    ]
							    ],
							    'isPro'  => true
						    ],
						    'customFields'   => [
							    'title'  => __( 'Enable Custom Fields', 'essential-addons-for-elementor-lite' ),
							    'icon'   => 'images/customfield.svg',
							    'info'   => __( 'Fields will be available on both the edit profile page and the EA Login | Register Form.', 'essential-addons-for-elementor-lite' ),
							    'fields' => [
								    [
									    'name'        => 'lr_custom_profile_fields_text',
									    'value'       => get_option( 'eael_custom_profile_fields_text', '' ),
									    'label'       => __( 'Text Type Fields:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'Field 1, Field 2 ...', 'essential-addons-for-elementor-lite' ),
								    ],
								    [
									    'name'        => 'lr_custom_profile_fields_img',
									    'value'       => get_option( 'eael_custom_profile_fields_img', '' ),
									    'label'       => __( 'File Type Fields:', 'essential-addons-for-elementor-lite' ),
									    'placeholder' => __( 'Field 1, Field 2 ...', 'essential-addons-for-elementor-lite' ),
								    ]
							    ],
							    'status' => [
								    'name'  => 'lr_custom_profile_fields',
								    'value' => get_option( 'eael_custom_profile_fields', '' ),
							    ]
						    ],
					    ],
					    'link'      => [
						    'text' => __( 'To configure the API Keys, check out this doc', 'essential-addons-for-elementor-lite' ),
						    'url'  => 'https://essential-addons.com/docs/social-login-recaptcha/'
					    ]
				    ]
			    ],
			    'admin_screen_promo'      => [
				    'display' => get_option( 'eael_admin_promotion' ) < self::EAEL_PROMOTION_FLAG,
				    'content' => sprintf( __( "<p> <i>📣</i> <b>NEW:</b> Introducing EA Pro 6.6 with new \"<b><a target='_blank' href='%s'>Custom Cursor</a></b>\" extension. For more info, check out the <a target='_blank' href='%s'>Changelog</a> 🎉</p>", "essential-addons-for-elementor-lite" ),  esc_url( 'https://essential-addons.com/custom-cursor' ), esc_url( 'https://essential-addons.com/view-ea-changelog' ) )
			    ],
			    'pro_modal'               => [
				    'heading' => __( 'Unlock the PRO Features', 'essential-addons-for-elementor-lite' ),
				    'content' => __( 'Upgrade to Essential Addons PRO and gain access to advanced elements and functionalities to build websites more efficiently', 'essential-addons-for-elementor-lite' ),
				    'list'    => [
					    __( 'Customization Flexibility in Design with Premium Creative Elements.', 'essential-addons-for-elementor-lite' ),
					    __( 'Advanced WooCommerce Widgets like Checkout, Cross-Sells & more.', 'essential-addons-for-elementor-lite' ),
					    __( 'Cutting-edge Extensions Like Custom JS, Content Protection & more.', 'essential-addons-for-elementor-lite' )
				    ],
				    'button'  => [
					    'label' => __( 'Upgrade to PRO', 'essential-addons-for-elementor-lite' ),
					    'url'   => 'https://essential-addons.com/upgrade-ea-pro'
				    ]
			    ],
			    'el_disabled_elements'    => get_option( 'elementor_disabled_elements', [] ),
			    'replace_widget_old2new'  => Elements_Manager::replace_widget_name()
		    ];

		    wp_localize_script( 'eael-admin-dashboard', 'localize', array(
			    'ajaxurl'            => admin_url( 'admin-ajax.php' ),
			    'nonce'              => wp_create_nonce( 'essential-addons-elementor' ),
			    'eael_dashboard'     => $ea_dashboard
		    ) );
	    }

        $this->eael_admin_inline_css();
    }

	public function admin_dequeue_scripts( $hook ) {
		if ( isset( $hook ) && in_array( $hook, [ 'toplevel_page_eael-settings', 'admin_page_eael-setup-wizard' ] ) ) {
			wp_dequeue_style( 'betterdocs-global' );
			wp_dequeue_style( 'betterdocs-select2' );
			wp_dequeue_style( 'betterdocs-daterangepicker' );
			wp_dequeue_style( 'betterdocs-old' );
			wp_dequeue_style( 'betterdocs' );
			wp_dequeue_style( 'betterdocs-icons' );
			wp_dequeue_style( 'betterdocs-instant-answer' );
			wp_dequeue_style( 'betterdocs-pro-settings' );
			wp_dequeue_style( 'otgs-notices' );
			wp_dequeue_style( 'sitepress-style' );

			wp_dequeue_script( 'betterdocs-categorygrid' );
			wp_dequeue_script( 'betterdocs-blocks-actions' );
			wp_dequeue_script( 'betterdocs-kbselect' );
			wp_dequeue_script( 'betterdocs-instant-answer' );
			wp_dequeue_script( 'betterdocs-pro-settings' );
		}
	}

	public function add_type_attribute( $attributes ) {
		if ( isset( $attributes['id'] ) && $attributes['id'] === 'eael-admin-dashboard-js' ) {
			$attributes['type'] = 'module';
		}

		return $attributes;
	}

    /**
     * Create settings page.
     *
     * @since 1.1.2
     */
    public function admin_settings_page() {
        ?>
		<div id="eael-dashboard"></div>
        <?php
	    do_action( 'eael_admin_page_setting' );
    }

    /**
     * Saving data with ajax request
     * @param
     * @since 1.1.2
     */


    public function admin_notice() {
        require_once EAEL_PLUGIN_PATH . 'vendor/autoload.php';

	    if ( ! method_exists( CacheBank::class, 'get_instance' ) ) {
		    return;
	    }

        self::$cache_bank = CacheBank::get_instance();

        NoticeRemover::get_instance('1.0.0');
        NoticeRemover::get_instance('1.0.0', '\WPDeveloper\BetterDocs\Dependencies\PriyoMukul\WPNotice\Notices');

        $notices = new Notices( [
			'id'             => 'essential-addons-for-elementor-lite',
			'storage_key'    => 'notices',
			'lifetime'       => 3,
			'stylesheet_url' => esc_url_raw( EAEL_PLUGIN_URL . 'assets/admin/css/notice.css' ),
			'styles' => esc_url_raw( EAEL_PLUGIN_URL . 'assets/admin/css/notice.css' ),
			'priority'       => 1
		] );

        $review_notice = __( 'We hope you\'re enjoying Essential Addons for Elementor! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'essential-addons-for-elementor-lite' );
		$_review_notice = [
			'thumbnail' => plugins_url( 'assets/admin/images/icon-ea-new-logo.svg', EAEL_PLUGIN_BASENAME ),
			'html'      => '<p>' . $review_notice . '</p>',
			'links'     => [
				'later'            => array(
					'link'       => 'https://wpdeveloper.com/review-essential-addons-elementor',
					'target'     => '_blank',
					'label'      => __( 'Ok, you deserve it!', 'essential-addons-for-elementor-lite' ),
					'icon_class' => 'dashicons dashicons-external',
				),
				'allready'         => array(
					'label'      => __( 'I already did', 'essential-addons-for-elementor-lite' ),
					'icon_class' => 'dashicons dashicons-smiley',
					'attributes' => [
						'data-dismiss' => true
					],
				),
				'maybe_later'      => array(
					'label'      => __( 'Maybe Later', 'essential-addons-for-elementor-lite' ),
					'icon_class' => 'dashicons dashicons-calendar-alt',
					'attributes' => [
						'data-later' => true
					],
				),
				'support'          => array(
					'link'       => 'https://wpdeveloper.com/support',
					'label'      => __( 'I need help', 'essential-addons-for-elementor-lite' ),
					'icon_class' => 'dashicons dashicons-sos',
				),
				'never_show_again' => array(
					'label'      => __( 'Never show again', 'essential-addons-for-elementor-lite' ),
					'icon_class' => 'dashicons dashicons-dismiss',
					'attributes' => [
						'data-dismiss' => true
					],
				)
			]
		];

	    $notices->add(
		    'review',
		    $_review_notice,
		    [
			    'start'       => $notices->strtotime( '+7 day' ),
			    'recurrence'  => 30,
			    'refresh'     => EAEL_PLUGIN_VERSION,
			    'dismissible' => true,
		    ]
	    );

	    ob_start(); ?>
		<div class="eael-black-friday-optin-logo">
			<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/eael-bfcm-logo.png' ); ?>" width="25" alt="">
		</div>
		<div class="eael-black-friday-optin">
			<p><?php 
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo __( 'Join us in celebrating the 8th birthday of Essential Addons and grab up to an exclusive 40% OFF on all the premium plans.', 'essential-addons-for-elementor-lite' );
			?></p>
			<a href="https://essential-addons.com/8thBD-admin-notice" target="_blank" class="button-primary">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M16.7432 11.938L16.904 10.3596C16.9898 9.51748 17.0465 8.96138 17.002 8.61101L17.0176 8.6111C17.7443 8.6111 18.3334 7.98927 18.3334 7.22221C18.3334 6.45515 17.7443 5.83332 17.0176 5.83332C16.2909 5.83332 15.7018 6.45515 15.7018 7.22221C15.7018 7.56912 15.8223 7.88632 16.0215 8.12974C15.7355 8.31607 15.3616 8.70925 14.7987 9.30104L14.7987 9.30105L14.7987 9.30106C14.3651 9.75698 14.1483 9.98494 13.9064 10.0202C13.7724 10.0398 13.6359 10.0197 13.5121 9.96217C13.2888 9.85836 13.1399 9.57655 12.8421 9.01292L11.2723 6.04203C11.0886 5.69432 10.9349 5.4033 10.7962 5.16911C11.365 4.86283 11.7544 4.23868 11.7544 3.51851C11.7544 2.49576 10.9689 1.66666 10 1.66666C9.0311 1.66666 8.24563 2.49576 8.24563 3.51851C8.24563 4.23868 8.63509 4.86283 9.20382 5.16911C9.06517 5.40331 8.91143 5.69429 8.72769 6.04203L7.15797 9.01292C6.86016 9.57654 6.71126 9.85836 6.48792 9.96217C6.36418 10.0197 6.22763 10.0398 6.09362 10.0202C5.85175 9.98494 5.63494 9.75698 5.20133 9.30105C4.6385 8.70926 4.26455 8.31607 3.97856 8.12974C4.17777 7.88632 4.29827 7.56912 4.29827 7.22221C4.29827 6.45515 3.70917 5.83332 2.98248 5.83332C2.25579 5.83332 1.66669 6.45515 1.66669 7.22221C1.66669 7.98927 2.25579 8.6111 2.98248 8.6111L2.99801 8.61101C2.95354 8.96138 3.01021 9.51748 3.09603 10.3596L3.25686 11.938C3.34614 12.8142 3.42038 13.6478 3.51131 14.3981H16.4887C16.5797 13.6478 16.6539 12.8142 16.7432 11.938Z"
						  fill="white"/>
					<path d="M9.04569 18.3333H10.9544C13.442 18.3333 14.6858 18.3333 15.5157 17.5492C15.878 17.207 16.1073 16.59 16.2729 15.787H3.72718C3.8927 16.59 4.12207 17.207 4.4843 17.5492C5.3142 18.3333 6.55803 18.3333 9.04569 18.3333Z"
						  fill="white"/>
				</svg>
			    <?php esc_html_e( 'Upgrade To PRO Now', 'essential-addons-for-elementor-lite' ); ?>
			</a>
		</div>
		<script>
            jQuery(document).ready(function ($) {
                setTimeout(function () {
                    var dismissBtn = document.querySelector('#wpnotice-essential-addons-for-elementor-lite-ea8th_birthday_notice .notice-dismiss');

                    function wpNoticeDismissFunc(event) {
                        event.preventDefault();

                        var httpRequest = new XMLHttpRequest(),
                            postData = '',
                            dismiss = event.target.dataset?.hasOwnProperty('dismiss') && event.target.dataset.dismiss || false,
                            later = event.target.dataset?.hasOwnProperty('later') && event.target.dataset.later || false;

                        if (dismiss || later) {
                            jQuery(event.target.offsetParent).slideUp(200);
                        }

                        // Data has to be formatted as a string here.
                        postData += 'id=ea8th_birthday_notice';
                        postData += '&action=essential-addons-for-elementor-lite_wpnotice_dismiss_notice';
                        if (dismiss) {
                            postData += '&dismiss=' + dismiss;
                        }
                        if (later) {
                            postData += '&later=' + later;
                        }

                        postData += '&nonce=<?php echo wp_create_nonce( 'wpnotice_dismiss_notice_ea8th_birthday_notice' );?>';

                        httpRequest.open('POST', '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>');
                        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        httpRequest.send(postData);
                    }

                    // Add an event listener to the dismiss button.
                    dismissBtn && dismissBtn.addEventListener('click', wpNoticeDismissFunc);
                }, 1);
            });
		</script>
	    <?php
	    $b_message            = ob_get_clean();
	    $_black_friday_notice = [
		    'html' => $b_message,
	    ];

	    $notices->add(
			'ea8th_birthday_notice',
			$_black_friday_notice,
			[
				'start'       => $notices->time(),
				'recurrence'  => false,
				'dismissible' => true,
				'refresh'     => EAEL_PLUGIN_VERSION,
				"expire"      => strtotime( '10:00:00pm 3rd August, 2025' ),
				'display_if'  => ! $this->pro_enabled && $GLOBALS["pagenow"] === 'index.php' && time() < strtotime( '08:00:00am 3rd August, 2025' ),
			]
		);

	    self::$cache_bank->create_account( $notices );
	    self::$cache_bank->calculate_deposits( $notices );
    }

	/**
	 * eael_admin_inline_css
     *
     * Admin Menu highlighted
     * @return false
	 * @since 5.1.0
	 */
	public function eael_admin_inline_css() {

	    $screen = get_current_screen();
		if ( ! empty( $screen->id ) && $screen->id == 'toplevel_page_eael-settings' ) {
			return false;
		}

		if ( $this->menu_notice_should_show() ) {
			$custom_css = "
                #toplevel_page_eael-settings a ,
                #toplevel_page_eael-settings a:hover {
                    color:#f0f0f1 !important;
                    background: #7D55FF !important;
                }
				#toplevel_page_eael-settings .eael-menu-notice {
                    display:block !important;
                }"
            ;
			wp_add_inline_style( 'admin-bar', $custom_css );
		}
	}

	/**
	 * menu_notice_should_show
     *
     * Check two flags status (eael_admin_menu_notice and eael_admin_promotion),
     * if both true this display menu notice. it's prevent to display menu notice multiple time
     *
	 * @return bool
     * @since 5.1.0
	 */
	public function menu_notice_should_show() {
		return ( get_option( 'eael_admin_menu_notice' ) < self::EAEL_PROMOTION_FLAG && get_option( 'eael_admin_promotion' ) < self::EAEL_ADMIN_MENU_FLAG );
	}
}

<?php

namespace Essential_Addons_Elementor\Traits;

if ( ! defined( 'ABSPATH' ) ) {
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
			// translators: %s is the number of the menu notice
			sprintf( __( 'Essential Addons %s', 'essential-addons-for-elementor-lite' ), $menu_notice ),
			'manage_options',
			'eael-settings',
			array( $this, 'admin_settings_page' ),
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

		if ( isset( $hook ) && $hook == 'elementor_page_elementor-element-manager' ) {
			wp_enqueue_style( 'ea-icon-admin', EAEL_PLUGIN_URL . 'assets/admin/css/eaicon.css', array(), EAEL_PLUGIN_VERSION );
		}

		if ( isset( $hook ) && $hook == 'toplevel_page_eael-settings' ) {
			wp_enqueue_style( 'eael-admin-icon-css', EAEL_PLUGIN_URL . 'includes/templates/admin/icons/style.css', array(), EAEL_PLUGIN_VERSION );
			wp_enqueue_style( 'eael-admin-css', EAEL_PLUGIN_URL . 'includes/templates/admin/eael-dashboard/dist/assets/ea-dashboard.css', array(), EAEL_PLUGIN_VERSION );
			wp_enqueue_script( 'eael-admin-dashboard', EAEL_PLUGIN_URL . 'includes/templates/admin/eael-dashboard/dist/assets/ea-dashboard.js', array(), EAEL_PLUGIN_VERSION, true );
			add_filter( 'wp_script_attributes', array( $this, 'add_type_attribute' ) );

			$ea_dashboard = array(
				'reactPath'               => EAEL_PLUGIN_URL . 'includes/templates/admin/eael-dashboard/dist/',
				'is_eapro_activate'       => $this->pro_enabled,
				'is_templately_installed' => $this->installer->get_local_plugin_data( 'templately/templately.php' ),
				'menu'                    => array(
					'general'      => array(
						'label' => __( 'General', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'ea-home',
					),
					'elements'     => array(
						'label' => __( 'Elements', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'ea-elements',
					),
					'extensions'   => array(
						'label' => __( 'Extensions', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'ea-extensions',
					),
					'tools'        => array(
						'label' => __( 'Tools', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'ea-tool',
					),
					'integrations' => array(
						'label' => __( 'Integrations', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'ea-plug',
					),
					'go-premium'   => array(
						'label' => __( 'Go Premium', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'ea-lock',
					),
				),
				'i18n'                    => array(
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
				),
				'whats_new'               => array(
					'heading' => __( "What's New In Essential Addons 6.0?", 'essential-addons-for-elementor-lite' ),
					'list'    => array(
						array(
							'label'   => 'New Extension:',
							'content' => __( 'Dynamic Tags, Hover Interactions, Interactive Animation', 'essential-addons-for-elementor-lite' ),
						),
						array(
							'label'   => 'Improvements:',
							'content' => __( 'Dynamic Widgets, Conditional Display', 'essential-addons-for-elementor-lite' ),
						),
						array(
							'label'   => '',
							'content' => __( 'Elevate your Workflow with the New Essential Addons Dashboard!', 'essential-addons-for-elementor-lite' ),
						),
					),
					'button'  => array(
						'label' => __( 'View Changelog', 'essential-addons-for-elementor-lite' ),
						'url'   => 'https://essential-addons.com/view-ea-changelog',
					),
				),
				'templately_promo'        => array(
					'heading' => __( 'Unlock 6500+ Ready Templates', 'essential-addons-for-elementor-lite' ),
					'list'    => array(
						__( 'Stunning Templates For All', 'essential-addons-for-elementor-lite' ),
						__( 'One-Click Full Site Import', 'essential-addons-for-elementor-lite' ),
						__( 'Collaborate in Team WorkSpace', 'essential-addons-for-elementor-lite' ),
						__( 'Unlimited Cloud Storage', 'essential-addons-for-elementor-lite' ),
					),
					'button'  => array(
						'label' => __( 'Enable Templates', 'essential-addons-for-elementor-lite' ),
					),
				),
				'video_promo'             => array(
					'heading' => __( 'Design Your Website With Most Popular Elementor Addons', 'essential-addons-for-elementor-lite' ),
					'content' => __( 'Learn to build stunning websites with 100+ elements & extensions of Essential Addons through our easy tutorials and enhance your Elementor site-building experience.', 'essential-addons-for-elementor-lite' ),
					'image'   => 'images/video-promo.png',
					'button'  => array(
						'label'    => __( 'Watch Tutorials', 'essential-addons-for-elementor-lite' ),
						'playlist' => 'https://www.youtube.com/watch?v=2a3GRk_06bg&list=PLWHp1xKHCfxC7JeWSg31vtVbLHGzfxDvh',
						'url'      => 'https://youtu.be/XPKZzYJcjZU',
					),
				),
				'community_box'           => array(
					array(
						'heading'    => __( 'Need Any Help?', 'essential-addons-for-elementor-lite' ),
						'content'    => __( "If you encounter issues or need assistance, we're here to help or report specific problems on <a href='https://github.com/WPDevelopers/essential-addons-for-elementor-lite/issues' target='_blank'>GitHub issues page.</a>", 'essential-addons-for-elementor-lite' ),
						'button'     => array(
							'label' => __( 'Create a Ticket', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://wpdeveloper.com/support/',
						),
						'icon'       => 'ea-support',
						'icon_color' => 'eaicon-1',
					),
					array(
						'heading'    => __( 'Join Our Community', 'essential-addons-for-elementor-lite' ),
						'content'    => __( 'Join the Facebook community to discuss with fellow developers, connect with others, and stay updated.', 'essential-addons-for-elementor-lite' ),
						'button'     => array(
							'label' => __( 'Join with us', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://www.facebook.com/groups/essentialaddons/',
						),
						'icon'       => 'ea-community',
						'icon_color' => 'eaicon-2',
					),
					array(
						'heading'    => __( 'View Knowledge Base', 'essential-addons-for-elementor-lite' ),
						'content'    => __( 'Read comprehensive documentation & learn to build a website easily with Essential Addons.', 'essential-addons-for-elementor-lite' ),
						'button'     => array(
							'label' => __( 'Read Documentation', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/docs/',
						),
						'icon'       => 'ea-docs',
						'icon_color' => 'eaicon-3',
					),
					array(
						'heading'    => __( 'Show Your Love', 'essential-addons-for-elementor-lite' ),
						'content'    => __( 'We love having you in our Essential Addons family every day. Please take 2 minutes to review us and show some love.', 'essential-addons-for-elementor-lite' ),
						'button'     => array(
							'label' => __( 'Leave a Review', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/ea-show-your-love',
						),
						'icon'       => 'ea-star-lite',
						'icon_color' => 'eaicon-4',
					),
					array(
						'heading'    => __( 'Continuous Features & Security Updates', 'essential-addons-for-elementor-lite' ),
						'content'    => __( 'Keep your website secure and up-to-date with regular security updates. Enjoy the convenience of continuous updates with exciting new features.', 'essential-addons-for-elementor-lite' ),
						'icon'       => 'ea-security-update',
						'icon_color' => 'eaicon-1',
					),
					array(
						'heading'    => __( 'Priority Support', 'essential-addons-for-elementor-lite' ),
						'content'    => __( 'Are you encountering issues? Do not worry! Our expert support team is available 24/7 through live chat or support tickets. Our team will reach out to you within 12-24 hours.', 'essential-addons-for-elementor-lite' ),
						'icon'       => 'ea-priority-support',
						'icon_color' => 'eaicon-1',
					),
				),
				'sidebar_box'             => array(
					'heading' => __( 'Want Advanced Features?', 'essential-addons-for-elementor-lite' ),
					'content' => __( 'Get more powerful widgets & extensions to elevate your Elementor website', 'essential-addons-for-elementor-lite' ),
					'review'  => array(
						'count' => __( '3800+', 'essential-addons-for-elementor-lite' ),
						'label' => __( 'Five Star Reviews', 'essential-addons-for-elementor-lite' ),
					),
					'button'  => array(
						'label' => __( 'Upgrade to PRO', 'essential-addons-for-elementor-lite' ),
						'url'   => 'https://essential-addons.com/upgrade-ea-pro',
						'icon'  => 'ea-crown-1',
					),
				),
				'integration_box'         => array(
					'enable'  => __( 'Activate', 'essential-addons-for-elementor-lite' ),
					'disable' => __( 'Deactivate', 'essential-addons-for-elementor-lite' ),
					'list'    => array(
						'bd' => array(
							'slug'     => 'betterdocs',
							'basename' => 'betterdocs/betterdocs.php',
							'logo'     => 'images/BD.svg',
							'title'    => __( 'BetterDocs', 'essential-addons-for-elementor-lite' ),
							'desc'     => __( 'Create and organize your knowledge base, FAQ & documentation page efficiently, making it easy for visitors to find any helpful article quickly and effortlessly.', 'essential-addons-for-elementor-lite' ),
							'status'   => is_plugin_active( 'betterdocs/betterdocs.php' ),
						),
						'bl' => array(
							'slug'     => 'betterlinks',
							'basename' => 'betterlinks/betterlinks.php',
							'logo'     => 'images/BL.svg',
							'title'    => __( 'BetterLinks', 'essential-addons-for-elementor-lite' ),
							'desc'     => __( 'Link Shortening tool to create, shorten & manage any URL. It helps to cross promote brands & products and gather analytics reports while running marketing campaigns.', 'essential-addons-for-elementor-lite' ),
							'status'   => is_plugin_active( 'betterlinks/betterlinks.php' ),
						),
						'bp' => array(
							'slug'     => 'better-payment',
							'basename' => 'better-payment/better-payment.php',
							'logo'     => 'images/BP.svg',
							'title'    => __( 'Better Payment', 'essential-addons-for-elementor-lite' ),
							'desc'     => __( 'Streamline transactions in Elementor by integrating PayPal & Stripe. Experience advanced analytics, validation, and Elementor forms for secure & efficient payments.', 'essential-addons-for-elementor-lite' ),
							'status'   => is_plugin_active( 'better-payment/better-payment.php' ),
						),
						'nx' => array(
							'slug'     => 'notificationx',
							'basename' => 'notificationx/notificationx.php',
							'logo'     => 'images/NX.svg',
							'title'    => __( 'NotificationX', 'essential-addons-for-elementor-lite' ),
							'desc'     => __( 'Best FOMO & social proof plugin to boost sales conversion by creating stunning sales popups, growth & discount alerts, flashing tabs, notification bars & more.', 'essential-addons-for-elementor-lite' ),
							'status'   => is_plugin_active( 'notificationx/notificationx.php' ),
						),
						'sp' => array(
							'slug'     => 'wp-scheduled-posts',
							'basename' => 'wp-scheduled-posts/wp-scheduled-posts.php',
							'logo'     => 'images/SP.svg',
							'title'    => __( 'SchedulePress', 'essential-addons-for-elementor-lite' ),
							'desc'     => __( 'Advanced content marketing tool for WordPress to schedule posts & pages with Schedule Calendar, Auto & Manual Scheduler, etc. It also allows auto-social sharing.', 'essential-addons-for-elementor-lite' ),
							'status'   => is_plugin_active( 'wp-scheduled-posts/wp-scheduled-posts.php' ),
						),
						'ej' => array(
							'slug'     => 'easyjobs',
							'basename' => 'easyjobs/easyjobs.php',
							'logo'     => 'images/EJ.svg',
							'title'    => __( 'easy.jobs', 'essential-addons-for-elementor-lite' ),
							'desc'     => __( 'Job recruitment tool to attract, manage, and hire the right talent faster. This talent recruitment solution lets you manage jobs and career pages in Elementor.', 'essential-addons-for-elementor-lite' ),
							'status'   => is_plugin_active( 'easyjobs/easyjobs.php' ),
						),
						'ep' => array(
							'slug'     => 'embedpress',
							'basename' => 'embedpress/embedpress.php',
							'logo'     => 'images/EP.svg',
							'title'    => __( 'EmbedPress', 'essential-addons-for-elementor-lite' ),
							'desc'     => __( 'Embed videos, images, gifs, charts, docs, maps, audio, live streams, pdf & more from 150+ sources into your WordPress site and get seamless customization options.', 'essential-addons-for-elementor-lite' ),
							'status'   => is_plugin_active( 'embedpress/embedpress.php' ),
						),
						'eb' => array(
							'slug'     => 'essential-blocks',
							'basename' => 'essential-blocks/essential-blocks.php',
							'logo'     => 'images/EB.svg',
							'title'    => __( 'Essential Blocks', 'essential-addons-for-elementor-lite' ),
							'desc'     => __( 'Enhance Gutenberg experience with 50+ unique blocks (more coming soon). Boost your block editor with easy-to-use blocks for a simpler WordPress page or post design.', 'essential-addons-for-elementor-lite' ),
							'status'   => is_plugin_active( 'essential-blocks/essential-blocks.php' ),
						),
					),
				),
				'premium_items'           => array(
					'list' => array(
						array(
							'heading' => __( 'Protected Content', 'essential-addons-for-elementor-lite' ),
							'content' => __( 'Restrict important data by setting up user permission or giving passwords to a particular area.', 'essential-addons-for-elementor-lite' ),
							'button'  => array(
								'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
								'url'   => 'https://essential-addons.com/protected-content/',
							),
							'image'   => 'images/Protected-Content.jpg',
						),
						array(
							'heading' => __( 'Smart Post List', 'essential-addons-for-elementor-lite' ),
							'content' => __( 'Display your blog posts in an amazing grid layout with advanced search & filter options.', 'essential-addons-for-elementor-lite' ),
							'button'  => array(
								'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
								'url'   => 'https://essential-addons.com/post-list/',
							),
							'image'   => 'images/Smart-Post-List.jpg',
						),
						array(
							'heading' => __( 'Woo Product Slider', 'essential-addons-for-elementor-lite' ),
							'content' => __( 'Showcase your WooCommerce products beautifully with amazing ready slider layouts.', 'essential-addons-for-elementor-lite' ),
							'button'  => array(
								'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
								'url'   => 'https://essential-addons.com/woo-product-slider/',
							),
							'image'   => 'images/Woo-Product-Slider.jpg',
						),
						array(
							'heading' => __( 'Post Carousel', 'essential-addons-for-elementor-lite' ),
							'content' => __( 'Amaze site visitors by displaying your posts creatively. Add transition effects, overlays, & more to showcase your posts beautifully on your site.', 'essential-addons-for-elementor-lite' ),
							'button'  => array(
								'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
								'url'   => 'https://essential-addons.com/post-carousel/',
							),
							'image'   => 'images/Post-Carousel.jpg',
						),
						array(
							'heading' => __( 'Instagram Feed', 'essential-addons-for-elementor-lite' ),
							'content' => __( 'Allows you to display a beautiful & responsive feed of your latest Instagram posts with customizable options and stunning layouts.', 'essential-addons-for-elementor-lite' ),
							'button'  => array(
								'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
								'url'   => 'https://essential-addons.com/instagram-feed/',
							),
							'image'   => 'images/Instagram-Feed.jpg',
						),
						array(
							'heading' => __( 'Custom JS', 'essential-addons-for-elementor-lite' ),
							'content' => __( 'Add custom JavaScript to your WordPress site effortlessly with advanced customization and functionality without modifying core files.', 'essential-addons-for-elementor-lite' ),
							'button'  => array(
								'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
								'url'   => 'https://essential-addons.com/custom-js/',
							),
							'image'   => 'images/Custom-JS.jpg',
						),
						array(
							'heading' => __( 'MailChimp', 'essential-addons-for-elementor-lite' ),
							'content' => __( 'Helps you easily connect your site with MailChimp. You can create and manage subscription forms directly on your website.', 'essential-addons-for-elementor-lite' ),
							'button'  => array(
								'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
								'url'   => 'https://essential-addons.com/mailchimp/',
							),
							'image'   => 'images/MailChimp.jpg',
						),
						array(
							'heading' => __( 'Advanced Google Map', 'essential-addons-for-elementor-lite' ),
							'content' => __( 'Integrates Google Maps to display locations, routes, and markers easily on your site as your preferences.', 'essential-addons-for-elementor-lite' ),
							'button'  => array(
								'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
								'url'   => 'https://essential-addons.com/advanced-google-map/',
							),
							'image'   => 'images/Advanced-Google-Map.jpg',
						),
						array(
							'heading' => __( 'Dynamic Gallery', 'essential-addons-for-elementor-lite' ),
							'content' => __( 'Showcase posts, Woo Products and more images in a captivating and interactive gallery format to highlight visual content dynamically.', 'essential-addons-for-elementor-lite' ),
							'button'  => array(
								'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
								'url'   => 'https://essential-addons.com/dynamic-gallery/',
							),
							'image'   => 'images/Dynamic-Gallery.jpg',
						),
					),
				),
				'enhance_experience'      => array(
					'top_heading' => __( '2+ Million Active Users', 'essential-addons-for-elementor-lite' ),
					'heading'     => __( "Enhance Your Elementor Experience By <br/> <b>Unlocking</b> <span class='Advance-color'>40+ Advanced PRO</span> <b>Elements</b>", 'essential-addons-for-elementor-lite' ),
					'review'      => array(
						'count' => __( '3800+', 'essential-addons-for-elementor-lite' ),
						'label' => __( 'Five Star Reviews', 'essential-addons-for-elementor-lite' ),
					),
					'button'      => array(
						'label' => __( 'Upgrade To PRO', 'essential-addons-for-elementor-lite' ),
						'url'   => 'https://essential-addons.com/upgrade-ea-pro',
						'icon'  => 'ea-crown-1',
					),
				),
				'explore_pro_features'    => array(
					'heading' => __( 'Get Access to Advanced Features!', 'essential-addons-for-elementor-lite' ),
					'content' => __( 'Discover the premium features of the most popular elements library for Elementor. Experience the web building experience with:', 'essential-addons-for-elementor-lite' ),
					'list'    => array(
						__( 'Customization Flexibility in Design with Premium Creative Elements.', 'essential-addons-for-elementor-lite' ),
						__( 'Advanced WooCommerce Widgets like Checkout, Cross-Sells & more.', 'essential-addons-for-elementor-lite' ),
						__( 'Cutting-edge Extensions Like Custom JS, Content Protection & more.', 'essential-addons-for-elementor-lite' ),
					),
					'image'   => 'images/img-3.png',
					'button'  => array(
						'label' => __( 'More Premium Features', 'essential-addons-for-elementor-lite' ),
						'url'   => 'https://essential-addons.com/premium-features/',
						'icon'  => 'ea-link',
					),
					'icons'   => array(
						array(
							'label' => __( 'Smart Post List', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/post-list/',
							'icon'  => 'images/Smart-Post-List.svg',
						),
						array(
							'label' => __( 'Dynamic Gallery', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/dynamic-gallery/',
							'icon'  => 'images/Dynamic-Gallery.svg',
						),
						array(
							'label' => __( 'Custom JS', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/custom-js/',
							'icon'  => 'images/Custom-JS.svg',
						),
						array(
							'label' => __( 'Protected Content', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/protected-content/',
							'icon'  => 'images/Protected-Content.svg',
						),
						array(
							'label' => __( 'Advanced Google Map', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/advanced-google-map/',
							'icon'  => 'images/Advanced-Google-Map.svg',
						),
						array(
							'label' => __( 'MailChimp', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/mailchimp/',
							'icon'  => 'images/Mailchimp.svg',
						),
						array(
							'label' => __( 'Instagram Feed', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/instagram-feed/',
							'icon'  => 'images/Instagram-Feed.svg',
						),
						array(
							'label' => __( 'Woo Product Slider', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/woo-product-slider/',
							'icon'  => 'images/Woo-Product-Slider.svg',
						),
						array(
							'label' => __( 'Parallax', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/parallax-scrolling/',
							'icon'  => 'images/Parallax-Effect.svg',
						),
						array(
							'label' => __( 'Post Carousel', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/post-carousel/',
							'icon'  => 'images/Post-Carousel.svg',
						),
						array(
							'label' => __( 'LearnDash Course List', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/learndash-course-list/',
							'icon'  => 'images/Learn-Dash-Course-List.svg',
						),
						array(
							'label' => __( 'Particle Effect', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/particle-effect/',
							'icon'  => 'images/Particles.svg',
						),
						array(
							'label' => __( 'Logo Carousel', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/logo-carousel/',
							'icon'  => 'images/Logo-Carousel.svg',
						),
						array(
							'label' => __( 'Image Hotspots', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/image-hotspots/',
							'icon'  => 'images/Image-Hotspots.svg',
						),
						array(
							'label' => __( 'Conditional Display', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/conditional-display/',
							'icon'  => 'images/Conditional-Display.svg',
						),
						array(
							'label' => __( 'Advanced Search', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/advanced-search',
							'icon'  => 'images/Advanced-Search.svg',
						),
						array(
							'label' => __( 'Team Member Carousel', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/team-members-carousel/',
							'icon'  => 'images/Team-Member-Carousel.svg',
						),
						array(
							'label' => __( 'Woo Cross Sells', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/woo-cross-sells/',
							'icon'  => 'images/Woo-Cross-Sells.svg',
						),
						array(
							'label' => __( 'Woo Account Dashboard', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/woo-account-dashboard/',
							'icon'  => 'images/Woo-Account-Dashboard.svg',
						),
						array(
							'label' => __( 'Lightbox And Modal', 'essential-addons-for-elementor-lite' ),
							'url'   => 'https://essential-addons.com/lightbox-modal/',
							'icon'  => 'images/Lightbox-And-Modal.svg',
						),
					),
				),
				'tools'                   => array(
					'box_1' => array(
						'heading' => __( 'Regenerate Assets', 'essential-addons-for-elementor-lite' ),
						'content' => __( 'Essential Addons styles & scripts are saved in Uploads folder. This option will clear all those generated files.', 'essential-addons-for-elementor-lite' ),
						'icon'    => 'ea-regenerate',
						'button'  => array(
							'label' => __( 'Regenerate Assets', 'essential-addons-for-elementor-lite' ),
						),
					),
					'box_2' => array(
						'heading' => __( 'Assets Embed Method', 'essential-addons-for-elementor-lite' ),
						'content' => __( 'Configure the Essential Addons assets embed method. Keep it as default (recommended).', 'essential-addons-for-elementor-lite' ),
						'icon'    => 'ea-settings',
						'button'  => array(
							'label' => __( 'CSS Print Method', 'essential-addons-for-elementor-lite' ),
							'url'   => defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.21.0', '>=' ) ? admin_url( 'admin.php?page=elementor-settings#tab-performance' ) : admin_url( 'admin.php?page=elementor#tab-advanced' ),
						),
					),
					'box_3' => array(
						'heading' => __( 'JS Print Method', 'essential-addons-for-elementor-lite' ),
						'content' => __( 'CSS Print Method is handled by Elementor Settings itself. Use External CSS Files for better performance (recommended).', 'essential-addons-for-elementor-lite' ),
						'methods' => array(
							'external' => __( 'External file', 'essential-addons-for-elementor-lite' ),
							'internal' => __( 'Internal Embedding', 'essential-addons-for-elementor-lite' ),
						),
						'name'    => 'eael-js-print-method',
						'value'   => get_option( 'eael_js_print_method', 'external' ),
					),
				),
				'extensions'              => array(
					'heading' => __( 'Extensions', 'essential-addons-for-elementor-lite' ),
					'list'    => array(
						'section-parallax'          => array(
							'key'         => 'section-parallax',
							'title'       => __( 'Parallax', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/parallax-scrolling/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-parallax/',
							'is_pro'      => true,
							'promotion'   => 'popular',
							'is_activate' => boolval( $this->get_settings( 'section-parallax' ) ),
						),
						'section-particles'         => array(
							'key'         => 'section-particles',
							'title'       => __( 'Particles', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/particle-effect/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-particles/',
							'is_pro'      => true,
							'is_activate' => boolval( $this->get_settings( 'section-particles' ) ),
						),
						'tooltip-section'           => array(
							'key'         => 'tooltip-section',
							'title'       => __( 'Advanced Tooltip', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/advanced-tooltip/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-advanced-tooltip/',
							'is_pro'      => true,
							'is_activate' => boolval( $this->get_settings( 'tooltip-section' ) ),
						),
						'content-protection'        => array(
							'key'         => 'content-protection',
							'title'       => __( 'Content Protection', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/content-protection/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-content-protection/',
							'is_pro'      => true,
							'promotion'   => 'popular',
							'is_activate' => boolval( $this->get_settings( 'content-protection' ) ),
						),
						'reading-progress'          => array(
							'key'         => 'reading-progress',
							'title'       => __( 'Reading Progress Bar', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/reading-progress/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-reading-progress-bar/',
							'is_pro'      => false,
							'is_activate' => boolval( $this->get_settings( 'reading-progress' ) ),
						),
						'table-of-content'          => array(
							'key'         => 'table-of-content',
							'title'       => __( 'Table of Contents', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/table-of-content/',
							'doc_link'    => 'https://essential-addons.com/docs/table-of-content',
							'is_pro'      => false,
							'promotion'   => 'popular',
							'is_activate' => boolval( $this->get_settings( 'table-of-content' ) ),
						),
						'post-duplicator'           => array(
							'key'         => 'post-duplicator',
							'title'       => __( 'Duplicator', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/duplicator/',
							'doc_link'    => 'https://essential-addons.com/docs/duplicator/',
							'is_pro'      => false,
							'setting'     => array( 'id' => 'postDuplicatorSetting' ),
							'is_activate' => boolval( $this->get_settings( 'post-duplicator' ) ),
						),
						'custom-js'                 => array(
							'key'         => 'custom-js',
							'title'       => __( 'Custom JS', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/custom-js/',
							'doc_link'    => 'https://essential-addons.com/docs/custom-js/',
							'is_pro'      => false,
							'promotion'   => 'popular',
							'is_activate' => boolval( $this->get_settings( 'custom-js' ) ),
						),
						'scroll-to-top'             => array(
							'key'         => 'scroll-to-top',
							'title'       => __( 'Scroll to Top', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/scroll-to-top/',
							'doc_link'    => 'https://essential-addons.com/docs/scroll-to-top/',
							'is_pro'      => false,
							'is_activate' => boolval( $this->get_settings( 'scroll-to-top' ) ),
						),
						'conditional-display'       => array(
							'key'         => 'conditional-display',
							'title'       => __( 'Conditional Display', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/conditional-display/',
							'doc_link'    => 'https://essential-addons.com/docs/conditional-display/',
							'is_pro'      => true,
							'promotion'   => 'updated',
							'is_activate' => boolval( $this->get_settings( 'conditional-display' ) ),
						),
						'wrapper-link'              => array(
							'key'         => 'wrapper-link',
							'title'       => __( 'Wrapper Link', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/wrapper-link/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-wrapper-link/',
							'is_pro'      => false,
							'is_activate' => boolval( $this->get_settings( 'wrapper-link' ) ),
						),
						'custom-cursor'             => array(
							'key'         => 'custom-cursor',
							'title'       => __( 'Custom Cursor', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/custom-cursor/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-custom-cursor/',
							'is_pro'      => true,
							'promotion'   => 'new',
							'is_activate' => boolval( $this->get_settings( 'custom-cursor' ) ),
						),
						'image-masking'             => array(
							'key'         => 'image-masking',
							'title'       => __( 'Image Masking', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/image-masking-with-morphing/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-image-masking-with-morphing/',
							'is_pro'      => false,
							'promotion'   => 'new',
							'is_activate' => boolval( $this->get_settings( 'image-masking' ) ),
						),
						'advanced-slider'           => array(
							'key'         => 'advanced-slider',
							'title'       => __( 'Advanced Slider', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/advanced-slider/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-advanced-slider/',
							'is_pro'      => true,
							'promotion'   => 'new',
							'is_activate' => boolval( $this->get_settings( 'advanced-slider' ) ),
						),
						'advanced-dynamic-tags'     => array(
							'key'         => 'advanced-dynamic-tags',
							'title'       => __( 'Dynamic Tags', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/dynamic-tags/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-dynamic-tags/',
							'is_pro'      => true,
							'promotion'   => 'new',
							'is_activate' => boolval( $this->get_settings( 'advanced-dynamic-tags' ) ),
						),
						'smooth-animation'          => array(
							'key'         => 'smooth-animation',
							'title'       => __( 'Interactive Animations', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/interactive-animations/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-interactive-animations/',
							'is_pro'      => true,
							'promotion'   => 'new',
							'is_activate' => boolval( $this->get_settings( 'smooth-animation' ) ),
						),
						'special-hover-effect'      => array(
							'key'         => 'special-hover-effect',
							'title'       => __( 'Hover Interactions', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/hover-interaction/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-hover-interaction/',
							'promotion'   => 'new',
							'is_activate' => boolval( $this->get_settings( 'special-hover-effect' ) ),
						),
						'liquid-glass-effect'       => array(
							'key'         => 'liquid-glass-effect',
							'title'       => __( 'Liquid Glass Effects', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/liquid-glass-effects/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-liquid-glass-effects/',
							'promotion'   => 'new',
							'is_activate' => boolval( $this->get_settings( 'liquid-glass-effect' ) ),
						),
						'vertical-text-orientation' => array(
							'key'         => 'vertical-text-orientation',
							'title'       => __( 'Vertical Text Orientation', 'essential-addons-for-elementor-lite' ),
							'demo_link'   => 'https://essential-addons.com/vertical-text-orientation/',
							'doc_link'    => 'https://essential-addons.com/docs/ea-vertical-text-orientation/',
							'promotion'   => 'new',
							'is_activate' => boolval( $this->get_settings( 'vertical-text-orientation' ) ),
						),
					),
				),
				'widgets'                 => array(
					'content-elements'         => array(
						'title'    => __( 'Content Elements', 'essential-addons-for-elementor-lite' ),
						'icon'     => 'ea-content',
						'elements' => array(
							'creative-btn'         => array(
								'key'         => 'creative-btn',
								'title'       => __( 'Creative Button', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/creative-buttons/',
								'doc_link'    => 'https://essential-addons.com/docs/creative-buttons/',
								'is_activate' => boolval( $this->get_settings( 'creative-btn' ) ),
							),
							'team-members'         => array(
								'key'         => 'team-members',
								'title'       => __( 'Team Member', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/team-members/',
								'doc_link'    => 'https://essential-addons.com/docs/team-members/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'team-members' ) ),
							),
							'testimonials'         => array(
								'key'         => 'testimonials',
								'title'       => __( 'Testimonial', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/testimonials/',
								'doc_link'    => 'https://essential-addons.com/docs/testimonials/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'testimonials' ) ),
							),
							'flip-box'             => array(
								'key'         => 'flip-box',
								'title'       => __( 'Flip Box', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/flip-box/',
								'doc_link'    => 'https://essential-addons.com/docs/flip-box/',
								'is_activate' => boolval( $this->get_settings( 'flip-box' ) ),
							),
							'info-box'             => array(
								'key'         => 'info-box',
								'title'       => __( 'Info Box', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/info-box/',
								'doc_link'    => 'https://essential-addons.com/docs/info-box/',
								'is_activate' => boolval( $this->get_settings( 'info-box' ) ),
							),
							'dual-header'          => array(
								'key'         => 'dual-header',
								'title'       => __( 'Dual Color Heading', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/dual-color-headline/',
								'doc_link'    => 'https://essential-addons.com/docs/dual-color-headline/',
								'is_activate' => boolval( $this->get_settings( 'dual-header' ) ),
							),
							'tooltip'              => array(
								'key'         => 'tooltip',
								'title'       => __( 'Tooltip', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/tooltip/',
								'doc_link'    => 'https://essential-addons.com/docs/tooltip/',
								'is_activate' => boolval( $this->get_settings( 'tooltip' ) ),
							),
							'adv-accordion'        => array(
								'key'         => 'adv-accordion',
								'title'       => __( 'Advanced Accordion', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/advanced-accordion/',
								'doc_link'    => 'https://essential-addons.com/docs/advanced-accordion/',
								'is_activate' => boolval( $this->get_settings( 'adv-accordion' ) ),
							),
							'adv-tabs'             => array(
								'key'         => 'adv-tabs',
								'title'       => __( 'Advanced Tabs', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/advanced-tabs/',
								'doc_link'    => 'https://essential-addons.com/docs/advanced-tabs/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'adv-tabs' ) ),
							),
							'feature-list'         => array(
								'key'         => 'feature-list',
								'title'       => __( 'Feature List', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/feature-list/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-feature-list/',
								'promotion'   => 'updated',
								'is_activate' => boolval( $this->get_settings( 'feature-list' ) ),
							),
							'offcanvas'            => array(
								'key'         => 'offcanvas',
								'title'       => __( 'Offcanvas', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/offcanvas-content/',
								'doc_link'    => 'https://essential-addons.com/docs/essential-addons-elementor-offcanvas/',
								'is_pro'      => true,
								'promotion'   => 'updated',
								'is_activate' => boolval( $this->get_settings( 'offcanvas' ) ),
							),
							'advanced-menu'        => array(
								'key'         => 'advanced-menu',
								'title'       => __( 'Advanced Menu', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/advanced-menu/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-advanced-menu/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'advanced-menu' ) ),
							),
							'toggle'               => array(
								'key'         => 'toggle',
								'title'       => __( 'Toggle', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/content-toggle/',
								'doc_link'    => 'https://essential-addons.com/docs/content-toggle/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'toggle' ) ),
							),
							'testimonial-slider'   => array(
								'key'         => 'testimonial-slider',
								'title'       => __( 'Testimonial Slider', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/testimonial-slider/',
								'doc_link'    => 'https://essential-addons.com/docs/testimonial-slider/',
								'promotion'   => 'updated',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'testimonial-slider' ) ),
							),
							'static-product'       => array(
								'key'         => 'static-product',
								'title'       => __( 'Static Product', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/static-product/',
								'doc_link'    => 'https://essential-addons.com/docs/static-product/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'static-product' ) ),
							),
							'team-member-carousel' => array(
								'key'         => 'team-member-carousel',
								'title'       => __( 'Team Member Carousel', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/team-members-carousel/',
								'doc_link'    => 'https://essential-addons.com/docs/team-member-carousel/',
								'is_pro'      => true,
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'team-member-carousel' ) ),
							),
							'sticky-video'         => array(
								'key'         => 'sticky-video',
								'title'       => __( 'Sticky Video', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/sticky-video/',
								'doc_link'    => 'https://essential-addons.com/docs/sticky-video/',
								'is_activate' => boolval( $this->get_settings( 'sticky-video' ) ),
							),
							'event-calendar'       => array(
								'key'         => 'event-calendar',
								'title'       => __( 'Event Calendar', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/event-calendar/',
								'doc_link'    => 'https://essential-addons.com/docs/event-calendar/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'event-calendar' ) ),
							),
							'simple-menu'          => array(
								'key'         => 'simple-menu',
								'title'       => __( 'Simple Menu', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/simple-menu/',
								'doc_link'    => 'https://essential-addons.com/docs/simple-menu/',
								'is_activate' => boolval( $this->get_settings( 'simple-menu' ) ),
							),
							'advanced-search'      => array(
								'key'         => 'advanced-search',
								'title'       => __( 'Advanced Search', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/advanced-search/',
								'doc_link'    => 'https://essential-addons.com/docs/advanced-search/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'advanced-search' ) ),
							),
							'breadcrumbs'          => array(
								'key'         => 'breadcrumbs',
								'title'       => __( 'Breadcrumbs', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/breadcrumbs/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-breadcrumbs/',
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'breadcrumbs' ) ),
							),
							'code-snippet'         => array(
								'key'         => 'code-snippet',
								'title'       => __( 'Code Snippet', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/code-snippet/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-code-snippet/',
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'code-snippet' ) ),
							),
						),
					),
					'dynamic-content-elements' => array(
						'title'    => __( 'Dynamic Content Elements', 'essential-addons-for-elementor-lite' ),
						'icon'     => 'ea-notes-2',
						'elements' => array(
							'post-grid'              => array(
								'key'         => 'post-grid',
								'title'       => __( 'Post Grid', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/post-grid/',
								'doc_link'    => 'https://essential-addons.com/docs/post-grid/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'post-grid' ) ),
							),
							'post-timeline'          => array(
								'key'         => 'post-timeline',
								'title'       => __( 'Post Timeline', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/post-timeline/',
								'doc_link'    => 'https://essential-addons.com/docs/post-timeline/',
								'is_activate' => boolval( $this->get_settings( 'post-timeline' ) ),
							),
							'data-table'             => array(
								'key'         => 'data-table',
								'title'       => __( 'Data Table', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/table/',
								'doc_link'    => 'https://essential-addons.com/docs/data-table/',
								'is_activate' => boolval( $this->get_settings( 'data-table' ) ),
							),
							'advanced-data-table'    => array(
								'key'         => 'advanced-data-table',
								'title'       => __( 'Advanced Data Table', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/advanced-data-table/',
								'doc_link'    => 'https://essential-addons.com/docs/advanced-data-table/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'advanced-data-table' ) ),
							),
							'content-ticker'         => array(
								'key'         => 'content-ticker',
								'title'       => __( 'Content Ticker', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/content-ticker/',
								'doc_link'    => 'https://essential-addons.com/docs/content-ticker/',
								'is_activate' => boolval( $this->get_settings( 'content-ticker' ) ),
							),
							'adv-google-map'         => array(
								'key'         => 'adv-google-map',
								'title'       => __( 'Advanced Google Map', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/advanced-google-map/',
								'doc_link'    => 'https://essential-addons.com/docs/advanced-google-map/',
								'is_pro'      => true,
								'setting'     => $this->pro_enabled ? array( 'id' => 'googleMapSetting' ) : array(),
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'adv-google-map' ) ),
							),
							'post-block'             => array(
								'key'         => 'post-block',
								'title'       => __( 'Post Block', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/post-block/',
								'doc_link'    => 'https://essential-addons.com/docs/post-block/',
								'is_pro'      => true,
								'promotion'   => 'updated',
								'is_activate' => boolval( $this->get_settings( 'post-block' ) ),
							),
							'post-carousel'          => array(
								'key'         => 'post-carousel',
								'title'       => __( 'Post Carousel', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/post-carousel/',
								'doc_link'    => 'https://essential-addons.com/docs/post-carousel/',
								'is_pro'      => true,
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'post-carousel' ) ),
							),
							'post-list'              => array(
								'key'         => 'post-list',
								'title'       => __( 'Smart Post List', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/post-list/',
								'doc_link'    => 'https://essential-addons.com/docs/smart-post-list/',
								'is_pro'      => true,
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'post-list' ) ),
							),
							'content-timeline'       => array(
								'key'         => 'content-timeline',
								'title'       => __( 'Content Timeline', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/content-timeline/',
								'doc_link'    => 'https://essential-addons.com/docs/content-timeline/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'content-timeline' ) ),
							),
							'dynamic-filter-gallery' => array(
								'key'         => 'dynamic-filter-gallery',
								'title'       => __( 'Dynamic Gallery', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/dynamic-gallery/',
								'doc_link'    => 'https://essential-addons.com/docs/dynamic-filterable-gallery/',
								'promotion'   => 'popular',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'dynamic-filter-gallery' ) ),
							),
							'nft-gallery'            => array(
								'key'         => 'nft-gallery',
								'title'       => __( 'NFT Gallery', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/nft-gallery/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-nft-gallery/',
								'promotion'   => 'updated',
								'is_activate' => boolval( $this->get_settings( 'nft-gallery' ) ),
							),
							'business-reviews'       => array(
								'key'         => 'business-reviews',
								'title'       => __( 'Business Reviews', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/business-reviews/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-business-reviews/',
								'setting'     => array( 'id' => 'businessReviewsSetting' ),
								'is_activate' => boolval( $this->get_settings( 'business-reviews' ) ),
							),
						),
					),
					'creative-elements'        => array(
						'title'    => __( 'Creative Elements', 'essential-addons-for-elementor-lite' ),
						'icon'     => 'ea-light',
						'elements' => array(
							'count-down'          => array(
								'key'         => 'count-down',
								'title'       => __( 'Countdown', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/countdown/',
								'doc_link'    => 'https://essential-addons.com/docs/creative-elements/ea-countdown/',
								'is_activate' => boolval( $this->get_settings( 'count-down' ) ),
							),
							'fancy-text'          => array(
								'key'         => 'fancy-text',
								'title'       => __( 'Fancy Text', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/fancy-text/',
								'doc_link'    => 'https://essential-addons.com/docs/fancy-text/',
								'is_activate' => boolval( $this->get_settings( 'fancy-text' ) ),
							),
							'filter-gallery'      => array(
								'key'         => 'filter-gallery',
								'title'       => __( 'Filterable Gallery', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/filterable-gallery/',
								'doc_link'    => 'https://essential-addons.com/docs/filterable-gallery/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'filter-gallery' ) ),
							),
							'image-accordion'     => array(
								'key'         => 'image-accordion',
								'title'       => __( 'Image Accordion', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/image-accordion/',
								'doc_link'    => 'https://essential-addons.com/docs/image-accordion/',
								'is_activate' => boolval( $this->get_settings( 'image-accordion' ) ),
							),
							'progress-bar'        => array(
								'key'         => 'progress-bar',
								'title'       => __( 'Progress Bar', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/progress-bar/',
								'doc_link'    => 'https://essential-addons.com/docs/progress-bar/',
								'is_activate' => boolval( $this->get_settings( 'progress-bar' ) ),
							),
							'interactive-promo'   => array(
								'key'         => 'interactive-promo',
								'title'       => __( 'Interactive Promo', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/interactive-promo/',
								'doc_link'    => 'https://essential-addons.com/docs/interactive-promo/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'interactive-promo' ) ),
							),
							'counter'             => array(
								'key'         => 'counter',
								'title'       => __( 'Counter', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/counter/',
								'doc_link'    => 'https://essential-addons.com/docs/counter/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'counter' ) ),
							),
							'lightbox'            => array(
								'key'         => 'lightbox',
								'title'       => __( 'Lightbox & Modal', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/lightbox-modal/',
								'doc_link'    => 'https://essential-addons.com/docs/lightbox-modal/',
								'is_pro'      => true,
								'promotion'   => 'updated',
								'is_activate' => boolval( $this->get_settings( 'lightbox' ) ),
							),
							'protected-content'   => array(
								'key'         => 'protected-content',
								'title'       => __( 'Protected Content', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/protected-content/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-protected-content/',
								'is_pro'      => true,
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'protected-content' ) ),
							),
							'img-comparison'      => array(
								'key'         => 'img-comparison',
								'title'       => __( 'Image Comparison', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/image-comparison/',
								'doc_link'    => 'https://essential-addons.com/docs/image-comparison/',
								'is_pro'      => true,
								'promotion'   => 'updated',
								'is_activate' => boolval( $this->get_settings( 'img-comparison' ) ),
							),
							'flip-carousel'       => array(
								'key'         => 'flip-carousel',
								'title'       => __( 'Flip Carousel', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/flip-carousel/',
								'doc_link'    => 'https://essential-addons.com/docs/flip-carousel/',
								'is_pro'      => true,
								'promotion'   => 'updated',
								'is_activate' => boolval( $this->get_settings( 'flip-carousel' ) ),
							),
							'logo-carousel'       => array(
								'key'         => 'logo-carousel',
								'title'       => __( 'Logo Carousel', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/logo-carousel/',
								'doc_link'    => 'https://essential-addons.com/docs/logo-carousel/',
								'is_pro'      => true,
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'logo-carousel' ) ),
							),
							'interactive-cards'   => array(
								'key'         => 'interactive-cards',
								'title'       => __( 'Interactive Cards', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/interactive-cards/',
								'doc_link'    => 'https://essential-addons.com/docs/interactive-cards/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'interactive-cards' ) ),
							),
							'one-page-navigation' => array(
								'key'         => 'one-page-navigation',
								'title'       => __( 'One Page Navigation', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/one-page-nav/',
								'doc_link'    => 'https://essential-addons.com/docs/one-page-navigation/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'one-page-navigation' ) ),
							),
							'image-hotspots'      => array(
								'key'         => 'image-hotspots',
								'title'       => __( 'Image Hotspots', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/image-hotspots/',
								'doc_link'    => 'https://essential-addons.com/docs/image-hotspots/',
								'is_pro'      => true,
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'image-hotspots' ) ),
							),
							'divider'             => array(
								'key'         => 'divider',
								'title'       => __( 'Divider', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/divider/',
								'doc_link'    => 'https://essential-addons.com/docs/divider/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'divider' ) ),
							),
							'image-scroller'      => array(
								'key'         => 'image-scroller',
								'title'       => __( 'Image Scroller', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/image-scroller/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-image-scroller/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'image-scroller' ) ),
							),
							'interactive-circle'  => array(
								'key'         => 'interactive-circle',
								'title'       => __( 'Interactive Circle', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/interactive-circle/',
								'doc_link'    => 'https://essential-addons.com/docs/interactive-circle/',
								'promotion'   => 'updated',
								'is_activate' => boolval( $this->get_settings( 'interactive-circle' ) ),
							),
							'svg-draw'            => array(
								'key'         => 'svg-draw',
								'title'       => __( 'SVG Draw', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/svg-draw/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-svg-draw/',
								'is_activate' => boolval( $this->get_settings( 'svg-draw' ) ),
							),
							'fancy-chart'         => array(
								'key'         => 'fancy-chart',
								'title'       => __( 'Fancy Chart', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/fancy-chart/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-fancy-chart/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'fancy-chart' ) ),
							),
							'stacked-cards'       => array(
								'key'         => 'stacked-cards',
								'title'       => __( 'Stacked Cards', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/stacked-cards/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-stacked-cards/',
								'is_pro'      => true,
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'stacked-cards' ) ),
							),
							'sphere-photo-viewer' => array(
								'key'         => 'sphere-photo-viewer',
								'title'       => __( '360 Degree Photo Viewer', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/360-degree-photo-viewer',
								'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-360-degree-photo-viewer/',
								'is_pro'      => true,
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'sphere-photo-viewer' ) ),
							),
						),
					),
					'marketing-elements'       => array(
						'title'    => __( 'Marketing Elements', 'essential-addons-for-elementor-lite' ),
						'icon'     => 'ea-marketing',
						'elements' => array(
							'call-to-action'            => array(
								'key'         => 'call-to-action',
								'title'       => __( 'Call To Action', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/call-to-action/',
								'doc_link'    => 'https://essential-addons.com/docs/call-to-action/',
								'is_activate' => boolval( $this->get_settings( 'call-to-action' ) ),
							),
							'price-table'               => array(
								'key'         => 'price-table',
								'title'       => __( 'Pricing Table', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/pricing-table/',
								'doc_link'    => 'https://essential-addons.com/docs/pricing-table/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'price-table' ) ),
							),
							'multicolumn-pricing-table' => array(
								'key'         => 'multicolumn-pricing-table',
								'title'       => __( 'Multicolumn Pricing Table', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/multicolumn-pricing-table/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-multicolumn-pricing-table/',
								'promotion'   => 'new',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'multicolumn-pricing-table' ) ),
							),
							'price-menu'                => array(
								'key'         => 'price-menu',
								'title'       => __( 'Price Menu', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/price-menu/',
								'doc_link'    => 'https://essential-addons.com/docs/price-menu/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'price-menu' ) ),
							),
							'pricing-slider'            => array(
								'key'         => 'pricing-slider',
								'title'       => __( 'Pricing Slider', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/pricing-slider/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-pricing-slider/',
								'is_pro'      => true,
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'pricing-slider' ) ),
							),
						),
					),
					'form-styler-elements'     => array(
						'title'    => __( 'Form Styler Elements', 'essential-addons-for-elementor-lite' ),
						'icon'     => 'ea-notes',
						'elements' => array(
							'contact-form-7' => array(
								'key'         => 'contact-form-7',
								'title'       => __( 'Contact Form 7', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/contact-form-7/',
								'doc_link'    => 'https://essential-addons.com/docs/contact-form-7/',
								'is_activate' => boolval( $this->get_settings( 'contact-form-7' ) ),
							),
							'weforms'        => array(
								'key'         => 'weforms',
								'title'       => __( 'weForms', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/weforms/',
								'doc_link'    => 'https://essential-addons.com/docs/weforms/',
								'is_activate' => boolval( $this->get_settings( 'weforms' ) ),
							),
							'ninja-form'     => array(
								'key'         => 'ninja-form',
								'title'       => __( 'Ninja Form', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/ninja-forms/',
								'doc_link'    => 'https://essential-addons.com/docs/ninja-forms/',
								'is_activate' => boolval( $this->get_settings( 'ninja-form' ) ),
							),
							'gravity-form'   => array(
								'key'         => 'gravity-form',
								'title'       => __( 'Gravity Form', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/gravity-forms/',
								'doc_link'    => 'https://essential-addons.com/docs/gravity-forms/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'gravity-form' ) ),
							),
							'caldera-form'   => array(
								'key'         => 'caldera-form',
								'title'       => __( 'Caldera Form', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/caldera-forms/',
								'doc_link'    => 'https://essential-addons.com/docs/caldera-forms/',
								'is_activate' => boolval( $this->get_settings( 'caldera-form' ) ),
							),
							'wpforms'        => array(
								'key'         => 'wpforms',
								'title'       => __( 'WPForms', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/wpforms/',
								'doc_link'    => 'https://essential-addons.com/docs/wpforms/',
								'is_activate' => boolval( $this->get_settings( 'wpforms' ) ),
							),
							'fluentform'     => array(
								'key'         => 'fluentform',
								'title'       => __( 'Fluent Forms', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/fluent-forms/',
								'doc_link'    => 'https://essential-addons.com/docs/fluent-form/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'fluentform' ) ),
							),
							'formstack'      => array(
								'key'         => 'formstack',
								'title'       => __( 'Formstack', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/formstack/',
								'doc_link'    => 'https://essential-addons.com/docs/formstack/',
								'is_activate' => boolval( $this->get_settings( 'formstack' ) ),
							),
							'typeform'       => array(
								'key'         => 'typeform',
								'title'       => __( 'Typeform', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/typeform/',
								'doc_link'    => 'https://essential-addons.com/docs/typeform/',
								'setting'     => array(
									'link' => esc_url(
										add_query_arg(
											array(
												'pr_code' => wp_hash( 'eael_typeform' ),
												'redirect_uri' => esc_url( admin_url( 'admin.php?page=eael-settings' ) ),
											),
											esc_url( 'https://app.essential-addons.com/typeform/index.php' )
										)
									),
								),
								'is_activate' => boolval( $this->get_settings( 'typeform' ) ),
							),
							'mailchimp'      => array(
								'key'         => 'mailchimp',
								'title'       => __( 'Mailchimp', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/mailchimp/',
								'doc_link'    => 'https://essential-addons.com/docs/mailchimp/',
								'is_pro'      => true,
								'setting'     => $this->pro_enabled ? array( 'id' => 'mailchimpSetting' ) : array(),
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'mailchimp' ) ),
							),
							'login-register' => array(
								'key'         => 'login-register',
								'title'       => __( 'Login | Register Form', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/login-register-form',
								'doc_link'    => 'https://essential-addons.com/docs/login-register-form/',
								'setting'     => array( 'id' => 'loginRegisterSetting' ),
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'login-register' ) ),
							),
						),
					),
					'social-feed-elements'     => array(
						'title'    => __( 'Social Feed Elements', 'essential-addons-for-elementor-lite' ),
						'icon'     => 'ea-share-fill',
						'elements' => array(
							'twitter-feed'          => array(
								'key'         => 'twitter-feed',
								'title'       => __( 'Twitter Feed', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/twitter-feed/',
								'doc_link'    => 'https://essential-addons.com/docs/twitter-feed/',
								'promotion'   => 'updated',
								'is_activate' => boolval( $this->get_settings( 'twitter-feed' ) ),
							),
							'twitter-feed-carousel' => array(
								'key'         => 'twitter-feed-carousel',
								'title'       => __( 'Twitter Feed Carousel', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/twitter-feed-carousel/',
								'doc_link'    => 'https://essential-addons.com/docs/twitter-feed-carousel/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'twitter-feed-carousel' ) ),
							),
							'instagram-gallery'     => array(
								'key'         => 'instagram-gallery',
								'title'       => __( 'Instagram Feed', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/instagram-feed/',
								'doc_link'    => 'https://essential-addons.com/docs/instagram-feed/',
								'is_pro'      => true,
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'instagram-gallery' ) ),
							),
							'facebook-feed'         => array(
								'key'         => 'facebook-feed',
								'title'       => __( 'Facebook Feed', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/facebook-feed/',
								'doc_link'    => 'https://essential-addons.com/docs/facebook-feed/',
								'promotion'   => 'updated',
								'is_activate' => boolval( $this->get_settings( 'facebook-feed' ) ),
							),
							'pinterest-feed'        => array(
								'key'         => 'pinterest-feed',
								'title'       => __( 'Pinterest Feed', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/pinterest-feed/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-pinterest-feed',
								'is_pro'      => true,
								'promotion'   => 'new',
								'setting'     => $this->pro_enabled ? array( 'id' => 'pinterestFeedSetting' ) : array(),
								'is_activate' => boolval( $this->get_settings( 'pinterest-feed' ) ),
							),
						),
					),
					'learn-dash-elements'      => array(
						'title'    => __( 'LearnDash Elements', 'essential-addons-for-elementor-lite' ),
						'icon'     => 'ea-leardash',
						'elements' => array(
							'learn-dash-course-list' => array(
								'key'         => 'learn-dash-course-list',
								'title'       => __( 'LearnDash Course List', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/learndash-course-list/',
								'doc_link'    => 'https://essential-addons.com/docs/learndash-course-list/',
								'is_pro'      => true,
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'learn-dash-course-list' ) ),
							),
						),
					),
					'documentation-elements'   => array(
						'title'    => __( 'Documentation Elements', 'essential-addons-for-elementor-lite' ),
						'icon'     => 'ea-docs-fill',
						'elements' => array(
							'betterdocs-category-grid' => array(
								'key'         => 'betterdocs-category-grid',
								'title'       => __( 'BetterDocs Category Grid', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/betterdocs-category-grid/',
								'doc_link'    => 'https://essential-addons.com/docs/betterdocs-category-grid/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'betterdocs-category-grid' ) ),
							),
							'betterdocs-category-box'  => array(
								'key'         => 'betterdocs-category-box',
								'title'       => __( 'BetterDocs Category Box', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/betterdocs-category-box/',
								'doc_link'    => 'https://essential-addons.com/docs/betterdocs-category-box/',
								'is_activate' => boolval( $this->get_settings( 'betterdocs-category-box' ) ),
							),
							'betterdocs-search-form'   => array(
								'key'         => 'betterdocs-search-form',
								'title'       => __( 'BetterDocs Search Form', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/betterdocs-search-form/',
								'doc_link'    => 'https://essential-addons.com/docs/betterdocs-search-form/',
								'is_activate' => boolval( $this->get_settings( 'betterdocs-search-form' ) ),
							),
						),
					),
					'figma-design'             => array(
						'title'    => __( 'Figma Design Elements', 'essential-addons-for-elementor-lite' ),
						'icon'     => 'ea-figma-to-elementor',
						'elements' => array(
							'figma-to-elementor' => array(
								'key'         => 'figma-to-elementor',
								'title'       => __( 'Figma to Elementor Converter', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/figma-to-elementor-converter/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-figma-to-elementor-converter/',
								'is_pro'      => true,
								'promotion'   => 'beta',
								'is_activate' => boolval( $this->get_settings( 'figma-to-elementor' ) ),
							),
						),
					),
					'woocommerce-elements'     => array(
						'title'    => __( 'WooCommerce Elements', 'essential-addons-for-elementor-lite' ),
						'icon'     => 'ea-cart',
						'elements' => array(
							'product-grid'            => array(
								'key'         => 'product-grid',
								'title'       => __( 'Woo Product Grid', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-grid/',
								'doc_link'    => 'https://essential-addons.com/docs/woocommerce-product-grid/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'product-grid' ) ),
							),
							'woo-product-list'        => array(
								'key'         => 'woo-product-list',
								'title'       => __( 'Woo Product List', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-list/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-list/',
								'is_activate' => boolval( $this->get_settings( 'woo-product-list' ) ),
							),
							'woo-product-title'       => array(
								'key'         => 'woo-product-title',
								'title'       => __( 'Woo Product Title', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-title/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-title',
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'woo-product-title' ) ),
							),
							'woo-product-price'       => array(
								'key'         => 'woo-product-price',
								'title'       => __( 'Woo Product Price', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-price/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-price',
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'woo-product-price' ) ),
							),
							'woo-product-tabs'        => array(
								'key'         => 'woo-product-tabs',
								'title'       => __( 'Woo Product Tabs', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-tabs/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-tabs',
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'woo-product-tabs' ) ),
							),
							'woo-product-short-description' => array(
								'key'         => 'woo-product-short-description',
								'title'       => __( 'Woo Product Short Description', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-short-description/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-short-description',
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'woo-product-short-description' ) ),
							),
							'woo-product-description' => array(
								'key'         => 'woo-product-description',
								'title'       => __( 'Woo Product Description', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-description/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-description',
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'woo-product-description' ) ),
							),
							'woo-product-rating'      => array(
								'key'         => 'woo-product-rating',
								'title'       => __( 'Woo Product Rating', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-rating/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-rating',
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'woo-product-rating' ) ),
							),
							'woo-product-images'      => array(
								'key'         => 'woo-product-images',
								'title'       => __( 'Woo Product Images', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-images/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-images/',
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'woo-product-images' ) ),
							),
							'woo-add-to-cart'         => array(
								'key'         => 'woo-add-to-cart',
								'title'       => __( 'Woo Add To Cart', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-add-to-cart/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-add-to-cart/',
								'promotion'   => 'new',
								'is_activate' => boolval( $this->get_settings( 'woo-add-to-cart' ) ),
							),
							'woo-collections'         => array(
								'key'         => 'woo-collections',
								'title'       => __( 'Woo Product Collections', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woocommerce-product-collections/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-product-collections/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'woo-collections' ) ),
							),
							'woo-product-slider'      => array(
								'key'         => 'woo-product-slider',
								'title'       => __( 'Woo Product Slider', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-slider/',
								'doc_link'    => 'https://essential-addons.com/docs/woo-product-slider/',
								'is_pro'      => true,
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'woo-product-slider' ) ),
							),
							'woo-product-carousel'    => array(
								'key'         => 'woo-product-carousel',
								'title'       => __( 'Woo Product Carousel', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-carousel/',
								'doc_link'    => 'https://essential-addons.com/docs/woo-product-carousel/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'woo-product-carousel' ) ),
							),
							'woo-checkout'            => array(
								'key'         => 'woo-checkout',
								'title'       => __( 'Woo Checkout', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-checkout/',
								'doc_link'    => 'https://essential-addons.com/docs/woo-checkout/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'woo-checkout' ) ),
							),
							'woo-cart'                => array(
								'key'         => 'woo-cart',
								'title'       => __( 'Woo Cart', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-cart/',
								'doc_link'    => 'https://essential-addons.com/docs/woocommerce-cart/',
								'is_activate' => boolval( $this->get_settings( 'woo-cart' ) ),
							),
							'woo-thank-you'           => array(
								'key'         => 'woo-thank-you',
								'title'       => __( 'Woo Thank You', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-thank-you',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-thank-you',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'woo-thank-you' ) ),
							),
							'woo-cross-sells'         => array(
								'key'         => 'woo-cross-sells',
								'title'       => __( 'Woo Cross Sells', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-cross-sells/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-cross-sells/',
								'is_pro'      => true,
								'is_activate' => boolval( $this->get_settings( 'woo-cross-sells' ) ),
							),
							'woo-product-compare'     => array(
								'key'         => 'woo-product-compare',
								'title'       => __( 'Woo Product Compare', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-compare/',
								'doc_link'    => 'https://essential-addons.com/docs/woo-product-compare/',
								'is_activate' => boolval( $this->get_settings( 'woo-product-compare' ) ),
							),
							'woo-product-gallery'     => array(
								'key'         => 'woo-product-gallery',
								'title'       => __( 'Woo Product Gallery', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-product-gallery/',
								'doc_link'    => 'https://essential-addons.com/docs/woo-product-gallery/',
								'promotion'   => 'popular',
								'is_activate' => boolval( $this->get_settings( 'woo-product-gallery' ) ),
							),
							'woo-account-dashboard'   => array(
								'key'         => 'woo-account-dashboard',
								'title'       => __( 'Woo Account Dashboard', 'essential-addons-for-elementor-lite' ),
								'demo_link'   => 'https://essential-addons.com/woo-account-dashboard/',
								'doc_link'    => 'https://essential-addons.com/docs/ea-woo-account-dashboard/',
								'is_pro'      => true,
								'setting'     => $this->pro_enabled ? array( 'id' => 'wooAccountDashboard' ) : array(),
								'is_activate' => boolval( $this->get_settings( 'woo-account-dashboard' ) ),
							),
						),
					),
				),
				'modal'                   => array(
					'postDuplicatorSetting'  => array(
						'title'   => __( 'Select Post Types', 'essential-addons-for-elementor-lite' ),
						'name'    => 'post-duplicator-post-type',
						'value'   => get_option( 'eael_save_post_duplicator_post_type', 'all' ),
						'options' => get_post_types(
							array(
								'public'            => true,
								'show_in_nav_menus' => true,
							)
						),
					),
					'googleMapSetting'       => array(
						'title'       => __( 'Google Map API Key', 'essential-addons-for-elementor-lite' ),
						'title_icon'  => 'images/map.svg',
						'label'       => __( 'Set API Key', 'essential-addons-for-elementor-lite' ),
						'name'        => 'google-map-api',
						'placeholder' => __( 'API Key', 'essential-addons-for-elementor-lite' ),
						'value'       => get_option( 'eael_save_google_map_api', '' ),
						'image'       => 'images/map.png',
					),
					'wooAccountDashboard'    => array(
						'title'       => __( 'Woo Account Dashboard', 'essential-addons-for-elementor-lite' ),
						'label'       => __( 'Set Custom Tabs', 'essential-addons-for-elementor-lite' ),
						'name'        => 'woo-account-dashboard-custom-tabs',
						'placeholder' => __( 'Custom Tab 1, Custom Tab 2, Custom Tab 3', 'essential-addons-for-elementor-lite' ),
						'value'       => get_option( 'eael_woo_ac_dashboard_custom_tabs', '' ),
					),
					'pinterestFeedSetting'   => apply_filters(
						'eael/admin/modal/pinterestFeedSetting',
						array(
							'accordion' => array(),
							'link'      => array(
								'text' => __( 'How to connect your Pinterest account', 'essential-addons-for-elementor-lite' ),
								'url'  => 'https://essential-addons.com/docs/pinterest-feed/',
							),
						)
					),

					'businessReviewsSetting' => apply_filters(
						'eael/admin/modal/businessReviewsSetting',
						array(
							'accordion' => array(
								'googlePlaces' => array(
									'title'  => __( 'Google Places API', 'essential-addons-for-elementor-lite' ),
									'icon'   => 'images/map.svg',
									'fields' => array(
										array(
											'name'        => 'br_google_place_api_key',
											'value'       => get_option( 'eael_br_google_place_api_key', '' ),
											'label'       => __( 'API Key:', 'essential-addons-for-elementor-lite' ),
											'placeholder' => __( 'Enter Google Places API Key', 'essential-addons-for-elementor-lite' ),
										),
									),
									'info'   => __( 'Get your Google Places API key from Google Cloud Console. This is used for fetching Google Reviews.', 'essential-addons-for-elementor-lite' ),
								),

							),
							'link'      => array(
								'text' => __( 'To configure the API Keys, check out this doc', 'essential-addons-for-elementor-lite' ),
								'url'  => 'https://essential-addons.com/docs/ea-business-reviews/',
							),
						)
					),

					'mailchimpSetting'       => array(
						'title'      => __( 'MailChimp API Key', 'essential-addons-for-elementor-lite' ),
						'title_icon' => 'images/mc.svg',
						'label'      => __( 'Set API Key', 'essential-addons-for-elementor-lite' ),
						'name'       => 'mailchimp-api',
						'value'      => get_option( 'eael_save_mailchimp_api', '' ),
						'image'      => 'images/mc.png',
						'link'       => array(
							'text' => __( 'To configure the API Keys, check out this doc', 'essential-addons-for-elementor-lite' ),
							'url'  => 'https://essential-addons.com/docs/mailchimp/#3-toc-title',
						),
					),
					'loginRegisterSetting'   => array(
						'accordion' => array(
							'reCaptchaV2'         => array(
								'title'  => __( 'reCAPTCHA v2', 'essential-addons-for-elementor-lite' ),
								'icon'   => 'images/recap.svg',
								'fields' => array(
									array(
										'name'        => 'lr_recaptcha_sitekey',
										'value'       => get_option( 'eael_recaptcha_sitekey', '' ),
										'label'       => __( 'Site Key:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'Site Key', 'essential-addons-for-elementor-lite' ),
									),
									array(
										'name'        => 'lr_recaptcha_secret',
										'value'       => get_option( 'eael_recaptcha_secret', '' ),
										'label'       => __( 'Site Secret:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'Site Secret', 'essential-addons-for-elementor-lite' ),
									),
									array(
										'name'        => 'lr_recaptcha_language',
										'value'       => get_option( 'eael_recaptcha_language', '' ),
										'label'       => __( 'Language:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'reCAPTCHA Language Code', 'essential-addons-for-elementor-lite' ),
									),
								),
							),
							'reCaptchaV3'         => array(
								'title'  => __( 'reCAPTCHA v3', 'essential-addons-for-elementor-lite' ),
								'icon'   => 'images/recap.svg',
								'fields' => array(
									array(
										'name'        => 'lr_recaptcha_sitekey_v3',
										'value'       => get_option( 'eael_recaptcha_sitekey_v3', '' ),
										'label'       => __( 'Site Key:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'Site Key', 'essential-addons-for-elementor-lite' ),
									),
									array(
										'name'        => 'lr_recaptcha_secret_v3',
										'value'       => get_option( 'eael_recaptcha_secret_v3', '' ),
										'label'       => __( 'Site Secret:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'Site Secret', 'essential-addons-for-elementor-lite' ),
									),
									array(
										'name'        => 'lr_recaptcha_language_v3',
										'value'       => get_option( 'eael_recaptcha_language_v3', '' ),
										'label'       => __( 'Language:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'reCAPTCHA Language Code', 'essential-addons-for-elementor-lite' ),
									),
									array(
										'name'  => 'lr_recaptcha_badge_hide',
										'value' => get_option( 'eael_recaptcha_badge_hide', '' ),
										'label' => __( 'Hide Badge', 'essential-addons-for-elementor-lite' ),
										'type'  => 'checkbox',
										'info'  => __( 'We are allowed to hide the badge as long as we include the reCAPTCHA branding visibly in the user flow.', 'essential-addons-for-elementor-lite' ),
									),
								),
							),
							'cloudflareTurnstile' => array(
								'title'  => __( 'Cloudflare Turnstile', 'essential-addons-for-elementor-lite' ),
								'icon'   => 'images/cloudflare.svg',
								'fields' => array(
									array(
										'name'        => 'lr_cloudflare_turnstile_sitekey',
										'value'       => get_option( 'eael_cloudflare_turnstile_sitekey', '' ),
										'label'       => __( 'Site Key:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'Site Key', 'essential-addons-for-elementor-lite' ),
									),
									array(
										'name'        => 'lr_cloudflare_turnstile_secretkey',
										'value'       => get_option( 'eael_cloudflare_turnstile_secretkey', '' ),
										'label'       => __( 'Secret Key:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'Secret Key', 'essential-addons-for-elementor-lite' ),
									),
								),
							),
							'googleLogin'         => array(
								'title'  => __( 'Google Login', 'essential-addons-for-elementor-lite' ),
								'icon'   => 'images/map.svg',
								'fields' => array(
									array(
										'name'        => 'lr_g_client_id',
										'value'       => get_option( 'eael_g_client_id', '' ),
										'label'       => __( 'Google Client ID:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'Google Client ID', 'essential-addons-for-elementor-lite' ),
									),
								),
								'isPro'  => true,
							),
							'facebookLogin'       => array(
								'title'  => __( 'Facebook Login', 'essential-addons-for-elementor-lite' ),
								'icon'   => 'images/fb.svg',
								'fields' => array(
									array(
										'name'        => 'lr_fb_app_id',
										'value'       => get_option( 'eael_fb_app_id', '' ),
										'label'       => __( 'Facebook App ID:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'Facebook App ID', 'essential-addons-for-elementor-lite' ),
									),
									array(
										'name'        => 'lr_fb_app_secret',
										'value'       => get_option( 'eael_fb_app_secret', '' ),
										'label'       => __( 'Facebook App Secret:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'Facebook App Secret', 'essential-addons-for-elementor-lite' ),
									),
								),
								'isPro'  => true,
							),
							'mailchimpLogin'      => array(
								'title'  => __( 'Mailchimp Integration', 'essential-addons-for-elementor-lite' ),
								'icon'   => 'images/mcwhite.svg',
								'fields' => array(
									array(
										'name'        => 'lr_mailchimp_api_key',
										'value'       => get_option( 'eael_lr_mailchimp_api_key', '' ),
										'label'       => __( 'Mailchimp API Key:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'Mailchimp API', 'essential-addons-for-elementor-lite' ),
									),
								),
								'isPro'  => true,
							),
							'customFields'        => array(
								'title'  => __( 'Enable Custom Fields', 'essential-addons-for-elementor-lite' ),
								'icon'   => 'images/customfield.svg',
								'info'   => __( 'Fields will be available on both the edit profile page and the EA Login | Register Form.', 'essential-addons-for-elementor-lite' ),
								'fields' => array(
									array(
										'name'        => 'lr_custom_profile_fields_text',
										'value'       => get_option( 'eael_custom_profile_fields_text', '' ),
										'label'       => __( 'Text Type Fields:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'Field 1, Field 2 ...', 'essential-addons-for-elementor-lite' ),
									),
									array(
										'name'        => 'lr_custom_profile_fields_img',
										'value'       => get_option( 'eael_custom_profile_fields_img', '' ),
										'label'       => __( 'File Type Fields:', 'essential-addons-for-elementor-lite' ),
										'placeholder' => __( 'Field 1, Field 2 ...', 'essential-addons-for-elementor-lite' ),
									),
								),
								'status' => array(
									'name'  => 'lr_custom_profile_fields',
									'value' => get_option( 'eael_custom_profile_fields', '' ),
								),
							),
						),
						'link'      => array(
							'text' => __( 'To configure the API Keys, check out this doc', 'essential-addons-for-elementor-lite' ),
							'url'  => 'https://essential-addons.com/docs/social-login-recaptcha/',
						),
					),
				),
				'admin_screen_promo'      => array(
					'display' => get_option( 'eael_admin_promotion' ) < self::EAEL_PROMOTION_FLAG,
					'content' => sprintf( __( "<p> <i>📣</i> <b>NEW:</b> Introducing EA Pro 7.0 with new \"<b><a target='_blank' href='%1\$s'>Advanced Slider</a></b>\" widget. For more info, check out the <a target='_blank' href='%2\$s'>Changelog</a> 🎉</p>", 'essential-addons-for-elementor-lite' ), esc_url( 'https://essential-addons.com/advanced-slider/' ), esc_url( 'https://essential-addons.com/view-ea-changelog' ) ),
				),
				'pro_modal'               => array(
					'heading' => __( 'Unlock the PRO Features', 'essential-addons-for-elementor-lite' ),
					'content' => __( 'Upgrade to Essential Addons PRO and gain access to advanced elements and functionalities to build websites more efficiently', 'essential-addons-for-elementor-lite' ),
					'list'    => array(
						__( 'Customization Flexibility in Design with Premium Creative Elements.', 'essential-addons-for-elementor-lite' ),
						__( 'Advanced WooCommerce Widgets like Checkout, Cross-Sells & more.', 'essential-addons-for-elementor-lite' ),
						__( 'Cutting-edge Extensions Like Custom JS, Content Protection & more.', 'essential-addons-for-elementor-lite' ),
					),
					'button'  => array(
						'label' => __( 'Upgrade to PRO', 'essential-addons-for-elementor-lite' ),
						'url'   => 'https://essential-addons.com/upgrade-ea-pro',
					),
				),
				'el_disabled_elements'    => get_option( 'elementor_disabled_elements', array() ),
				'replace_widget_old2new'  => Elements_Manager::replace_widget_name(),
			);

			wp_localize_script(
				'eael-admin-dashboard',
				'localize',
				array(
					'ajaxurl'        => admin_url( 'admin-ajax.php' ),
					'nonce'          => wp_create_nonce( 'essential-addons-elementor' ),
					'eael_dashboard' => $ea_dashboard,
				)
			);
		}

		$this->eael_admin_inline_css();
	}

	public function admin_dequeue_scripts( $hook ) {
		if ( isset( $hook ) && in_array( $hook, array( 'toplevel_page_eael-settings', 'admin_page_eael-setup-wizard' ) ) ) {
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

		NoticeRemover::get_instance( '1.0.0' );
		NoticeRemover::get_instance( '1.0.0', '\WPDeveloper\BetterDocs\Dependencies\PriyoMukul\WPNotice\Notices' );

		$notices = new Notices(
			array(
				'id'             => 'essential-addons-for-elementor-lite',
				'storage_key'    => 'notices',
				'lifetime'       => 3,
				'stylesheet_url' => esc_url_raw( EAEL_PLUGIN_URL . 'assets/admin/css/notice.css' ),
				'styles'         => esc_url_raw( EAEL_PLUGIN_URL . 'assets/admin/css/notice.css' ),
				'priority'       => 1,
			)
		);

		$review_notice  = __( 'We hope you\'re enjoying Essential Addons for Elementor! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'essential-addons-for-elementor-lite' );
		$_review_notice = array(
			'thumbnail' => plugins_url( 'assets/admin/images/icon-ea-new-logo.svg', EAEL_PLUGIN_BASENAME ),
			'html'      => '<p>' . $review_notice . '</p>',
			'links'     => array(
				'later'            => array(
					'link'       => 'https://wpdeveloper.com/review-essential-addons-elementor',
					'target'     => '_blank',
					'label'      => __( 'Ok, you deserve it!', 'essential-addons-for-elementor-lite' ),
					'icon_class' => 'dashicons dashicons-external',
				),
				'allready'         => array(
					'label'      => __( 'I already did', 'essential-addons-for-elementor-lite' ),
					'icon_class' => 'dashicons dashicons-smiley',
					'attributes' => array(
						'data-dismiss' => true,
					),
				),
				'maybe_later'      => array(
					'label'      => __( 'Maybe Later', 'essential-addons-for-elementor-lite' ),
					'icon_class' => 'dashicons dashicons-calendar-alt',
					'attributes' => array(
						'data-later' => true,
					),
				),
				'support'          => array(
					'link'       => 'https://wpdeveloper.com/support',
					'label'      => __( 'I need help', 'essential-addons-for-elementor-lite' ),
					'icon_class' => 'dashicons dashicons-sos',
				),
				'never_show_again' => array(
					'label'      => __( 'Never show again', 'essential-addons-for-elementor-lite' ),
					'icon_class' => 'dashicons dashicons-dismiss',
					'attributes' => array(
						'data-dismiss' => true,
					),
				),
			),
		);

		$notices->add(
			'review',
			$_review_notice,
			array(
				'start'       => $notices->strtotime( '+7 day' ),
				'recurrence'  => 30,
				'refresh'     => EAEL_PLUGIN_VERSION,
				'dismissible' => true,
			)
		);

		ob_start();
		?>
		<div class="eael-summer-campaign-logo">
			<img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/eael-bfcm-logo.png' ); ?>" alt="">
		</div>
		<div class="eael-summer-campaign-content">
			<p>
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo __( '<strong>🏖️</strong> Your Elementor site deserves a Summer glow-up. Get 110+ advanced elements and build faster, smarter <strong>- up to $120 OFF</strong>', 'essential-addons-for-elementor-lite' );
			?>
			</p>
			<div class="eael-notice-action-button" style='display: inline-flex; column-gap: 12px; align-items: center;'>
				<a href="https://essential-addons.com/summer2026-admin-notice" target="_blank" class="button-primary">
					<?php esc_html_e( 'Upgrade To PRO Now', 'essential-addons-for-elementor-lite' ); ?>
				</a>
				<span class="eael-action-dismiss-btn">
					<?php esc_html_e( "I Don't Want Any Discount", 'essential-addons-for-elementor-lite' ); ?>
				</span>
			</div>
		</div>

		<script>
			jQuery(document).ready(function ($) {
				setTimeout(function () {
					var dismissBtn = document.querySelector('#wpnotice-essential-addons-for-elementor-lite-summer_campaign_2026_notice .notice-dismiss');

					function wpNoticeDismissFunc(event) {
						event.preventDefault();

						var httpRequest = new XMLHttpRequest(),
							postData = '',
							dismiss = event.target.dataset?.hasOwnProperty('dismiss') && event.target.dataset.dismiss || false,
							later = event.target.dataset?.hasOwnProperty('later') && event.target.dataset.later || false;

						if (dismiss || later) {
							jQuery(event.target.offsetParent).slideUp(200);
						}

						postData += 'id=summer_campaign_2026_notice';
						postData += '&action=essential-addons-for-elementor-lite_wpnotice_dismiss_notice';
						if (dismiss) {
							postData += '&dismiss=' + dismiss;
						}
						if (later) {
							postData += '&later=' + later;
						}

						postData += '&nonce=<?php echo esc_attr( wp_create_nonce( 'wpnotice_dismiss_notice_summer_campaign_2026_notice' ) ); ?>';

						httpRequest.open('POST', '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>');
						httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
						httpRequest.send(postData);
					}

					dismissBtn && dismissBtn.addEventListener('click', wpNoticeDismissFunc);

					var customDismissBtn = document.querySelector('#wpnotice-essential-addons-for-elementor-lite-summer_campaign_2026_notice .eael-action-dismiss-btn');
					if (customDismissBtn && dismissBtn) {
						customDismissBtn.addEventListener('click', function(event) {
							event.preventDefault();
							dismissBtn.click();
						});
					}
				}, 1);
			});
		</script>
		<?php
		$b_message                    = ob_get_clean();
		$_summer_campaign_2026_notice = array(
			'html' => $b_message,
		);

		$notices->add(
			'summer_campaign_2026_notice',
			$_summer_campaign_2026_notice,
			array(
				'start'       => strtotime( '12:00:00pm 24th June, 2026' ),
				'recurrence'  => false,
				'dismissible' => true,
				'refresh'     => EAEL_PLUGIN_VERSION,
				'expire'      => strtotime( '11:59:59pm 19th July, 2026' ),
				'display_if'  => ! $this->pro_enabled && $GLOBALS['pagenow'] === 'index.php' && time() < strtotime( '11:59:59pm 25th June, 2026' ),
			)
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
			$custom_css = '
                #toplevel_page_eael-settings a ,
                #toplevel_page_eael-settings a:hover {
                    color:#f0f0f1 !important;
                    background: #7D55FF !important;
                }
				#toplevel_page_eael-settings .eael-menu-notice {
                    display:block !important;
                }';
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

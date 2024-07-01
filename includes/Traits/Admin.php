<?php

namespace Essential_Addons_Elementor\Traits;

if ( !defined( 'ABSPATH' ) ) {
    exit();
}

// Exit if accessed directly

use Essential_Addons_Elementor\Classes\Helper as HelperClass;
use Essential_Addons_Elementor\Classes\WPDeveloper_Notice;
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
			$this->safe_url( EAEL_PLUGIN_URL . 'assets/admin/images/ea-icon-white.svg' ),
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
		    wp_enqueue_style( 'essential_addons_elementor-admin-icon-css', EAEL_PLUGIN_URL . 'includes/templates/admin/icons/style.css', false, EAEL_PLUGIN_VERSION );
		    wp_enqueue_style( 'essential_addons_elementor-admin-css', EAEL_PLUGIN_URL . 'includes/templates/admin/eael-dashboard/dist/assets/ea-dashboard.css', false, time() );
		    wp_enqueue_script( 'essential_addons_elementor-admin-js', EAEL_PLUGIN_URL . 'assets/admin/js/admin.js', array( 'jquery' ), EAEL_PLUGIN_VERSION, true );
		    wp_enqueue_script( 'essential_addons_elementor-admin-dashboard', EAEL_PLUGIN_URL . 'includes/templates/admin/eael-dashboard/dist/assets/ea-dashboard.js', array(), time(), true );
		    add_filter( 'wp_script_attributes', [ $this, 'add_type_attribute' ] );

		    //Internationalizing JS string translation
		    $i18n = [
			    'login_register' => [
				    //m=modal, rm=response modal, r=reCAPTCHA, g= google, f=facebook, e=error
				    'm_title'       => __( 'Login | Register Form Settings', 'essential-addons-for-elementor-lite' ),
				    'm_footer'      => $this->pro_enabled ? __( 'To configure the API Keys, check out this doc', 'essential-addons-for-elementor-lite' ) : __( 'To retrieve your API Keys, click here', 'essential-addons-for-elementor-lite' ),
				    'save'          => __( 'Save', 'essential-addons-for-elementor-lite' ),
				    'cancel'        => __( 'Cancel', 'essential-addons-for-elementor-lite' ),
				    'rm_title'      => __( 'Login | Register Form Settings Saved', 'essential-addons-for-elementor-lite' ),
				    'rm_footer'     => __( 'Reload the page to see updated data', 'essential-addons-for-elementor-lite' ),
				    'e_title'       => __( 'Oops...', 'essential-addons-for-elementor-lite' ),
				    'e_text'        => __( 'Something went wrong!', 'essential-addons-for-elementor-lite' ),
				    'r_title'       => __( 'reCAPTCHA v2', 'essential-addons-for-elementor-lite' ),
				    'r_sitekey'     => __( 'Site Key', 'essential-addons-for-elementor-lite' ),
				    'r_sitesecret'  => __( 'Site Secret', 'essential-addons-for-elementor-lite' ),
				    'r_language'    => __( 'Language', 'essential-addons-for-elementor-lite' ),
				    'r_language_ph' => __( 'reCAPTCHA Language Code', 'essential-addons-for-elementor-lite' ),
				    'g_title'       => __( 'Google Login', 'essential-addons-for-elementor-lite' ),
				    'g_cid'         => __( 'Google Client ID', 'essential-addons-for-elementor-lite' ),
				    'f_title'       => __( 'Facebook Login', 'essential-addons-for-elementor-lite' ),
				    'f_app_id'      => __( 'Facebook APP ID', 'essential-addons-for-elementor-lite' ),
				    'f_app_secret'  => __( 'Facebook APP Secret', 'essential-addons-for-elementor-lite' ),
			    ]
		    ];

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
			    ],
			    'whats_new'               => [
				    'heading' => __( "What's New In Essential Addons 6.0?", 'essential-addons-for-elementor-lite' ),
				    'list'    => [
					    __( 'EA Dynamic Tags allow you to display content dynamically from posts, users and custom fields.', 'essential-addons-for-elementor-lite' ),
					    __( 'EA Conditional Display lets you show contents based on specific conditions.', 'essential-addons-for-elementor-lite' ),
					    __( 'EA Hover Interactions add engaging effects when users hover over elements.', 'essential-addons-for-elementor-lite' ),
					    __( 'Interactive Animation brings dynamic, user-responsive animations to your website.', 'essential-addons-for-elementor-lite' ),
				    ],
				    'button'  => [
					    'label' => __( 'View Changelog', 'essential-addons-for-elementor-lite' ),
					    'url'   => 'https://essential-addons.com/changelog/'
				    ]
			    ],
			    'templately_promo'        => [
				    'heading' => __( 'Unlock 5000+ Ready Templates', 'essential-addons-for-elementor-lite' ),
				    'list'    => [
					    __( 'Stunning Website Templates For All Niche', 'essential-addons-for-elementor-lite' ),
					    __( 'One-Click Full Site Import Feature', 'essential-addons-for-elementor-lite' ),
					    __( 'Collaborate With Team Members in WorkSpace', 'essential-addons-for-elementor-lite' ),
					    __( 'Unlimited Cloud Storage', 'essential-addons-for-elementor-lite' ),
				    ],
				    'button'  => [
					    'label' => __( 'Install templately', 'essential-addons-for-elementor-lite' )
				    ]
			    ],
			    'community_box'           => [
				    [
					    'heading'    => __( 'Need Any Help?', 'essential-addons-for-elementor-lite' ),
					    'content'    => __( 'If you encounter any issues or need assistance, we are here to help. You can report specific issues or bugs directly on our GitHub issues page.', 'essential-addons-for-elementor-lite' ),
					    'button'     => [
						    'label' => __( 'Create a Ticket', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://wpdeveloper.com/support/'
					    ],
					    'icon'       => 'ea-github',
					    'icon_color' => 'eaicon-1'
				    ],
				    [
					    'heading'    => __( 'Join Our Community', 'essential-addons-for-elementor-lite' ),
					    'content'    => __( 'Join the Facebook community & discuss with fellow developers & users to get attached to the community people & stay updated.', 'essential-addons-for-elementor-lite' ),
					    'button'     => [
						    'label' => __( 'Join with us', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://www.facebook.com/groups/essentialaddons/'
					    ],
					    'icon'       => 'ea-community',
					    'icon_color' => 'eaicon-2'
				    ],
				    [
					    'heading'    => __( 'View Knowledge Base', 'essential-addons-for-elementor-lite' ),
					    'content'    => __( 'Read our comprehensive documentation and learn to build a stunning website easily with Essential Addons for Elementor.', 'essential-addons-for-elementor-lite' ),
					    'button'     => [
						    'label' => __( 'Read Documentation', 'essential-addons-for-elementor-lite' ),
						    'url'   => 'https://essential-addons.com/docs/'
					    ],
					    'icon'       => 'ea-docs',
					    'icon_color' => 'eaicon-3'
				    ],
				    [
					    'heading'    => __( 'Automatic Updates & Priority Support', 'essential-addons-for-elementor-lite' ),
					    'content'    => __( 'LoremGet access to automatic updates & keep your website up-to-date with constantly developing features. Having any trouble?', 'essential-addons-for-elementor-lite' ),
					    'button'     => [
						    'label' => __( 'Learn More', 'essential-addons-for-elementor-lite' ),
						    'url'   => '#'
					    ],
					    'icon'       => 'ea-support',
					    'icon_color' => 'eaicon-1'
				    ],
				    [
					    'heading'    => __( 'Automatic Updates & Priority Support', 'essential-addons-for-elementor-lite' ),
					    'content'    => __( 'LoremGet access to automatic updates & keep your website up-to-date with constantly developing features. Having any trouble?', 'essential-addons-for-elementor-lite' ),
					    'button'     => [
						    'label' => __( 'Learn More', 'essential-addons-for-elementor-lite' ),
						    'url'   => '#'
					    ],
					    'icon'       => 'ea-docs',
					    'icon_color' => 'eaicon-1'
				    ]
			    ],
			    'sidebar_box'             => [
				    'heading' => __( 'Want Advanced Features?', 'essential-addons-for-elementor-lite' ),
				    'content' => __( 'Get more powerful Widgets & Extensions to elevate your experience of creating a beautiful Elementor website.', 'essential-addons-for-elementor-lite' ),
				    'review'  => [
					    'label' => __( 'Review from Real Users', 'essential-addons-for-elementor-lite' ),
					    'score' => __( '5/5', 'essential-addons-for-elementor-lite' ),
				    ],
				    'button'  => [
					    'label' => __( 'Upgrade to PRO', 'essential-addons-for-elementor-lite' ),
					    'url'   => 'https://essential-addons.com/#pricing',
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
						    'content' => __( 'Restrict access to important data of your website by setting up user permissions', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => '#'
						    ],
						    'image'   => 'images/img-5.png'
					    ],
					    [
						    'heading' => __( 'Smart Post List', 'essential-addons-for-elementor-lite' ),
						    'content' => __( 'Restrict access to important data of your website by setting up user permissions', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => '#'
						    ],
						    'image'   => 'images/img-6.png'
					    ],
					    [
						    'heading' => __( 'Woo Product Slider', 'essential-addons-for-elementor-lite' ),
						    'content' => __( 'Restrict access to important data of your website by setting up user permissions', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => '#'
						    ],
						    'image'   => 'images/img-5.png'
					    ],
					    [
						    'heading' => __( 'Smart Post List', 'essential-addons-for-elementor-lite' ),
						    'content' => __( 'Restrict access to important data of your website by setting up user permissions', 'essential-addons-for-elementor-lite' ),
						    'button'  => [
							    'label' => __( 'View Demo', 'essential-addons-for-elementor-lite' ),
							    'url'   => '#'
						    ],
						    'image'   => 'images/img-6.png'
					    ],
				    ]
			    ],
			    'enhance_experience'      => [
				    'heading' => __( "Enhance Your Elementor Experience By <br/> <b>Unlocking</b> <span class='Advance-color'>35+ Advanced PRO</span> <b>Elements</b>", 'essential-addons-for-elementor-lite' ),
				    'review'  => [
					    'label' => __( 'Review from Real Users', 'essential-addons-for-elementor-lite' ),
					    'score' => __( '5/5', 'essential-addons-for-elementor-lite' ),
					    'url'   => '#',
				    ],
				    'button'  => [
					    'label' => __( 'Upgrade to PRO', 'essential-addons-for-elementor-lite' ),
					    'url'   => '#',
					    'icon'  => 'ea-crown-1'
				    ]
			    ],
			    'explore_pro_features'    => [
				    'heading' => __( "Explore Premiere Pro features", 'essential-addons-for-elementor-lite' ),
				    'content' => __( "Learn all about the tools and techniques you can use to edit videos, animate titles, add effects, mix sound, and more.", 'essential-addons-for-elementor-lite' ),
				    'image'   => 'images/img-3.png',
				    'button'  => [
					    'label' => __( 'View Changelog', 'essential-addons-for-elementor-lite' ),
					    'url'   => '#',
					    'icon'  => 'ea-link'
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
						    'url'   => admin_url( 'admin.php?page=elementor#tab-advanced' )
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
				    'heading' => __( 'Advanced Extensions', 'essential-addons-for-elementor-lite' ),
				    'list'    => [
					    'section-parallax'    => [
						    'key'         => 'section-parallax',
						    'title'       => __( 'Parallax', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/elementor/parallax-scrolling/',
						    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-parallax/',
						    'is_pro'      => true,
						    'is_activate' => boolval( $this->get_settings( 'section-parallax' ) )
					    ],
					    'section-particles'   => [
						    'key'         => 'section-particles',
						    'title'       => __( 'Particles', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/elementor/particle-effect/',
						    'doc_link'    => 'https://essential-addons.com/elementor/docs/particles/',
						    'is_pro'      => true,
						    'is_activate' => boolval( $this->get_settings( 'section-particles' ) )
					    ],
					    'tooltip-section'     => [
						    'key'         => 'tooltip-section',
						    'title'       => __( 'Advanced Tooltip', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/elementor/advanced-tooltip/',
						    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-advanced-tooltip/',
						    'is_pro'      => true,
						    'is_activate' => boolval( $this->get_settings( 'tooltip-section' ) )
					    ],
					    'content-protection'  => [
						    'key'         => 'content-protection',
						    'title'       => __( 'Content Protection', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/elementor/content-protection/',
						    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-content-protection/',
						    'is_pro'      => true,
						    'promotion'   => 'popular',
						    'is_activate' => boolval( $this->get_settings( 'content-protection' ) )
					    ],
					    'reading-progress'    => [
						    'key'         => 'reading-progress',
						    'title'       => __( 'Reading Progress Bar', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/elementor/reading-progress/',
						    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-reading-progress-bar/',
						    'is_pro'      => false,
						    'is_activate' => boolval( $this->get_settings( 'reading-progress' ) )
					    ],
					    'table-of-content'    => [
						    'key'         => 'table-of-content',
						    'title'       => __( 'Table of Contents', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/elementor/table-of-content/',
						    'doc_link'    => 'https://essential-addons.com/elementor/docs/table-of-content',
						    'is_pro'      => false,
						    'promotion'   => 'popular',
						    'is_activate' => boolval( $this->get_settings( 'table-of-content' ) )
					    ],
					    'post-duplicator'     => [
						    'key'         => 'post-duplicator',
						    'title'       => __( 'Duplicator', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/elementor/duplicator/',
						    'doc_link'    => 'https://essential-addons.com/elementor/docs/duplicator/',
						    'is_pro'      => false,
						    'setting'     => [ 'id' => 'postDuplicatorSetting' ],
						    'is_activate' => boolval( $this->get_settings( 'post-duplicator' ) )
					    ],
					    'custom-js'           => [
						    'key'         => 'custom-js',
						    'title'       => __( 'Custom JS', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/elementor/custom-js/',
						    'doc_link'    => 'https://essential-addons.com/elementor/docs/custom-js/',
						    'is_pro'      => false,
						    'promotion'   => 'popular',
						    'is_activate' => boolval( $this->get_settings( 'custom-js' ) )
					    ],
					    'xd-copy'             => [
						    'key'         => 'xd-copy',
						    'title'       => __( 'Cross-Domain Copy Paste', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/elementor/cross-domain-copy-paste/',
						    'doc_link'    => 'https://essential-addons.com/elementor/docs/cross-domain-copy-paste/',
						    'is_pro'      => true,
						    'promotion'   => 'new',
						    'is_activate' => boolval( $this->get_settings( 'xd-copy' ) )
					    ],
					    'scroll-to-top'       => [
						    'key'         => 'scroll-to-top',
						    'title'       => __( 'Scroll to Top', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/elementor/scroll-to-top/',
						    'doc_link'    => 'https://essential-addons.com/elementor/docs/scroll-to-top/',
						    'is_pro'      => false,
						    'promotion'   => 'new',
						    'is_activate' => boolval( $this->get_settings( 'scroll-to-top' ) )
					    ],
					    'conditional-display' => [
						    'key'         => 'conditional-display',
						    'title'       => __( 'Conditional Display', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/elementor/conditional-display/',
						    'doc_link'    => 'https://essential-addons.com/elementor/docs/conditional-display/',
						    'is_pro'      => true,
						    'promotion'   => 'new',
						    'is_activate' => boolval( $this->get_settings( 'conditional-display' ) )
					    ],
					    'wrapper-link'        => [
						    'key'         => 'wrapper-link',
						    'title'       => __( 'Wrapper Link', 'essential-addons-for-elementor-lite' ),
						    'demo_link'   => 'https://essential-addons.com/elementor/wrapper-link/',
						    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-wrapper-link/',
						    'is_pro'      => false,
						    'promotion'   => 'new',
						    'is_activate' => boolval( $this->get_settings( 'wrapper-link' ) )
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
							    'demo_link'   => 'https://essential-addons.com/elementor/creative-buttons/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/content-elements/creative-buttons/',
							    'is_activate' => boolval( $this->get_settings( 'creative-btn' ) )
						    ],
						    'team-members'         => [
							    'key'         => 'team-members',
							    'title'       => __( 'Team Member', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/team-members/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/team-members/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'team-members' ) )
						    ],
						    'testimonials'         => [
							    'key'         => 'testimonials',
							    'title'       => __( 'Testimonial', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/testimonials/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/testimonials/',
							    'is_activate' => boolval( $this->get_settings( 'testimonials' ) )
						    ],
						    'flip-box'             => [
							    'key'         => 'flip-box',
							    'title'       => __( 'Flip Box', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/flip-box/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/flip-box/',
							    'is_activate' => boolval( $this->get_settings( 'flip-box' ) )
						    ],
						    'info-box'             => [
							    'key'         => 'info-box',
							    'title'       => __( 'Info Box', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/info-box/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/info-box/',
							    'is_activate' => boolval( $this->get_settings( 'info-box' ) )
						    ],
						    'dual-header'          => [
							    'key'         => 'dual-header',
							    'title'       => __( 'Dual Color Heading', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/dual-color-headline/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/dual-color-headline/',
							    'is_activate' => boolval( $this->get_settings( 'dual-header' ) )
						    ],
						    'tooltip'              => [
							    'key'         => 'tooltip',
							    'title'       => __( 'Tooltip', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/tooltip/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/tooltip/',
							    'is_activate' => boolval( $this->get_settings( 'tooltip' ) )
						    ],
						    'adv-accordion'        => [
							    'key'         => 'adv-accordion',
							    'title'       => __( 'Advanced Accordion', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/advanced-accordion/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/advanced-accordion/',
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'adv-accordion' ) )
						    ],
						    'adv-tabs'             => [
							    'key'         => 'adv-tabs',
							    'title'       => __( 'Advanced Tabs', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/advanced-tabs/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/advanced-tabs/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'adv-tabs' ) )
						    ],
						    'feature-list'         => [
							    'key'         => 'feature-list',
							    'title'       => __( 'Feature List', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/feature-list/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-feature-list/',
							    'is_activate' => boolval( $this->get_settings( 'feature-list' ) )
						    ],
						    'offcanvas'            => [
							    'key'         => 'offcanvas',
							    'title'       => __( 'Offcanvas', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/offcanvas-content/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/essential-addons-elementor-offcanvas/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'offcanvas' ) )
						    ],
						    'advanced-menu'        => [
							    'key'         => 'advanced-menu',
							    'title'       => __( 'Advanced Menu', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/advanced-menu/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-advanced-menu/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'advanced-menu' ) )
						    ],
						    'toggle'               => [
							    'key'         => 'toggle',
							    'title'       => __( 'Toggle', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/content-toggle/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/content-toggle/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'toggle' ) )
						    ],
						    'testimonial-slider'   => [
							    'key'         => 'testimonial-slider',
							    'title'       => __( 'Testimonial Slider', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/testimonial-slider/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/testimonial-slider/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'testimonial-slider' ) )
						    ],
						    'static-product'       => [
							    'key'         => 'static-product',
							    'title'       => __( 'Static Product', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/static-product/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/static-product/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'static-product' ) )
						    ],
						    'team-member-carousel' => [
							    'key'         => 'team-member-carousel',
							    'title'       => __( 'Team Member Carousel', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/team-members-carousel/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/team-member-carousel/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'team-member-carousel' ) )
						    ],
						    'sticky-video'         => [
							    'key'         => 'sticky-video',
							    'title'       => __( 'Sticky Video', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/sticky-video/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/sticky-video/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'sticky-video' ) )
						    ],
						    'event-calendar'       => [
							    'key'         => 'event-calendar',
							    'title'       => __( 'Event Calendar', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/event-calendar/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/event-calendar/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'event-calendar' ) )
						    ],
						    'simple-menu'          => [
							    'key'         => 'simple-menu',
							    'title'       => __( 'Simple Menu', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/simple-menu/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/simple-menu/',
							    'is_activate' => boolval( $this->get_settings( 'simple-menu' ) )
						    ],
						    'advanced-search'      => [
							    'key'         => 'advanced-search',
							    'title'       => __( 'Advanced Search', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/advanced-search/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/advanced-search/',
							    'is_pro'      => true,
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'advanced-search' ) )
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
							    'demo_link'   => 'https://essential-addons.com/elementor/post-grid/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/post-grid/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'post-grid' ) )
						    ],
						    'post-timeline'          => [
							    'key'         => 'post-timeline',
							    'title'       => __( 'Post Timeline', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/post-timeline/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/post-timeline/',
							    'is_activate' => boolval( $this->get_settings( 'post-timeline' ) )
						    ],
						    'data-table'             => [
							    'key'         => 'data-table',
							    'title'       => __( 'Data Table', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/table/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/data-table/',
							    'is_activate' => boolval( $this->get_settings( 'data-table' ) )
						    ],
						    'advanced-data-table'    => [
							    'key'         => 'advanced-data-table',
							    'title'       => __( 'Advanced Data Table', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/advanced-data-table/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/advanced-data-table/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'advanced-data-table' ) )
						    ],
						    'content-ticker'         => [
							    'key'         => 'content-ticker',
							    'title'       => __( 'Content Ticker', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/content-ticker/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/content-ticker/',
							    'is_activate' => boolval( $this->get_settings( 'content-ticker' ) )
						    ],
						    'adv-google-map'         => [
							    'key'         => 'adv-google-map',
							    'title'       => __( 'Advanced Google Map', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/advanced-google-map/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/advanced-google-map/',
							    'is_pro'      => true,
							    'setting'     => $this->pro_enabled ? [ 'id' => 'googleMapSetting' ] : [],
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'adv-google-map' ) )
						    ],
						    'post-block'             => [
							    'key'         => 'post-block',
							    'title'       => __( 'Post Block', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/post-block/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/post-block/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'post-block' ) )
						    ],
						    'post-carousel'          => [
							    'key'         => 'post-carousel',
							    'title'       => __( 'Post Carousel', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/post-carousel/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/post-carousel/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'post-carousel' ) )
						    ],
						    'post-list'              => [
							    'key'         => 'post-list',
							    'title'       => __( 'Smart Post List', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/post-list/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/smart-post-list/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'post-list' ) )
						    ],
						    'content-timeline'       => [
							    'key'         => 'content-timeline',
							    'title'       => __( 'Content Timeline', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/content-timeline/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/content-timeline/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'content-timeline' ) )
						    ],
						    'dynamic-filter-gallery' => [
							    'key'         => 'dynamic-filter-gallery',
							    'title'       => __( 'Dynamic Gallery', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/dynamic-gallery/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/dynamic-filterable-gallery/',
							    'promotion'   => 'popular',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'dynamic-filter-gallery' ) )
						    ],
						    'nft-gallery'            => [
							    'key'         => 'nft-gallery',
							    'title'       => __( 'NFT Gallery', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/nft-gallery/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-nft-gallery/',
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'nft-gallery' ) )
						    ],
						    'business-reviews'       => [
							    'key'         => 'business-reviews',
							    'title'       => __( 'Business Reviews', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/business-reviews/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-business-reviews/',
							    'setting'     => [ 'id' => 'businessReviewsSetting' ],
							    'promotion'   => 'new',
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
							    'demo_link'   => 'https://essential-addons.com/elementor/countdown/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/creative-elements/ea-countdown/',
							    'is_activate' => boolval( $this->get_settings( 'count-down' ) )
						    ],
						    'fancy-text'          => [
							    'key'         => 'fancy-text',
							    'title'       => __( 'Fancy Text', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/fancy-text/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/fancy-text/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'fancy-text' ) )
						    ],
						    'filter-gallery'      => [
							    'key'         => 'filter-gallery',
							    'title'       => __( 'Filterable Gallery', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/filterable-gallery/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/filterable-gallery/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'filter-gallery' ) )
						    ],
						    'image-accordion'     => [
							    'key'         => 'image-accordion',
							    'title'       => __( 'Image Accordion', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/image-accordion/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/image-accordion/',
							    'is_activate' => boolval( $this->get_settings( 'image-accordion' ) )
						    ],
						    'progress-bar'        => [
							    'key'         => 'progress-bar',
							    'title'       => __( 'Progress Bar', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/progress-bar/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/progress-bar/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'progress-bar' ) )
						    ],
						    'interactive-promo'   => [
							    'key'         => 'interactive-promo',
							    'title'       => __( 'Interactive Promo', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/interactive-promo/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/interactive-promo/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'interactive-promo' ) )
						    ],
						    'counter'             => [
							    'key'         => 'counter',
							    'title'       => __( 'Counter', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/counter/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/counter/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'counter' ) )
						    ],
						    'lightbox'            => [
							    'key'         => 'lightbox',
							    'title'       => __( 'Lightbox & Modal', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/lightbox-modal/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/lightbox-modal/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'lightbox' ) )
						    ],
						    'protected-content'   => [
							    'key'         => 'protected-content',
							    'title'       => __( 'Protected Content', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/protected-content/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-protected-content/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'protected-content' ) )
						    ],
						    'img-comparison'      => [
							    'key'         => 'img-comparison',
							    'title'       => __( 'Image Comparison', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/image-comparison/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/image-comparison/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'img-comparison' ) )
						    ],
						    'flip-carousel'       => [
							    'key'         => 'flip-carousel',
							    'title'       => __( 'Flip Carousel', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/flip-carousel/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/flip-carousel/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'flip-carousel' ) )
						    ],
						    'logo-carousel'       => [
							    'key'         => 'logo-carousel',
							    'title'       => __( 'Logo Carousel', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/logo-carousel/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/logo-carousel/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'logo-carousel' ) )
						    ],
						    'interactive-cards'   => [
							    'key'         => 'interactive-cards',
							    'title'       => __( 'Interactive Cards', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/interactive-cards/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/interactive-cards/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'interactive-cards' ) )
						    ],
						    'one-page-navigation' => [
							    'key'         => 'one-page-navigation',
							    'title'       => __( 'One Page Navigation', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/one-page-nav/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/one-page-navigation/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'one-page-navigation' ) )
						    ],
						    'image-hotspots'      => [
							    'key'         => 'image-hotspots',
							    'title'       => __( 'Image Hotspots', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/image-hotspots/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/image-hotspots/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'image-hotspots' ) )
						    ],
						    'divider'             => [
							    'key'         => 'divider',
							    'title'       => __( 'Divider', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/divider/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/divider/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'divider' ) )
						    ],
						    'image-scroller'      => [
							    'key'         => 'image-scroller',
							    'title'       => __( 'Image Scroller', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/image-scroller/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-image-scroller/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'image-scroller' ) )
						    ],
						    'interactive-circle'  => [
							    'key'         => 'interactive-circle',
							    'title'       => __( 'Interactive Circle', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/interactive-circle/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/interactive-circle/',
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'interactive-circle' ) )
						    ],
						    'svg-draw'            => [
							    'key'         => 'svg-draw',
							    'title'       => __( 'SVG Draw', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/svg-draw/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-svg-draw/',
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'svg-draw' ) )
						    ],
						    'fancy-chart'         => [
							    'key'         => 'fancy-chart',
							    'title'       => __( 'Fancy Chart', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/fancy-chart/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-fancy-chart/',
							    'promotion'   => 'new',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'fancy-chart' ) )
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
							    'demo_link'   => 'https://essential-addons.com/elementor/call-to-action/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/call-to-action/',
							    'is_activate' => boolval( $this->get_settings( 'call-to-action' ) )
						    ],
						    'price-table'    => [
							    'key'         => 'price-table',
							    'title'       => __( 'Pricing Table', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/pricing-table/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/pricing-table/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'price-table' ) )
						    ],
						    'price-menu'     => [
							    'key'         => 'price-menu',
							    'title'       => __( 'Price Menu', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/price-menu/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/price-menu/',
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
							    'demo_link'   => 'https://essential-addons.com/elementor/contact-form-7/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/contact-form-7/',
							    'is_activate' => boolval( $this->get_settings( 'contact-form-7' ) )
						    ],
						    'weforms'        => [
							    'key'         => 'weforms',
							    'title'       => __( 'weForms', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/weforms/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/weforms/',
							    'is_activate' => boolval( $this->get_settings( 'weforms' ) )
						    ],
						    'ninja-form'     => [
							    'key'         => 'ninja-form',
							    'title'       => __( 'Ninja Form', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/ninja-forms/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ninja-forms/',
							    'is_activate' => boolval( $this->get_settings( 'ninja-form' ) )
						    ],
						    'gravity-form'   => [
							    'key'         => 'gravity-form',
							    'title'       => __( 'Gravity Form', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/gravity-forms/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/gravity-forms/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'gravity-form' ) )
						    ],
						    'caldera-form'   => [
							    'key'         => 'caldera-form',
							    'title'       => __( 'Caldera Form', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/caldera-forms/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/caldera-forms/',
							    'is_activate' => boolval( $this->get_settings( 'caldera-form' ) )
						    ],
						    'wpforms'        => [
							    'key'         => 'wpforms',
							    'title'       => __( 'WPForms', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/wpforms/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/wpforms/',
							    'is_activate' => boolval( $this->get_settings( 'wpforms' ) )
						    ],
						    'fluentform'     => [
							    'key'         => 'fluentform',
							    'title'       => __( 'Fluent Forms', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/fluent-forms/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/fluent-form/',
							    'is_activate' => boolval( $this->get_settings( 'fluentform' ) )
						    ],
						    'formstack'      => [
							    'key'         => 'formstack',
							    'title'       => __( 'Formstack', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/formstack/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/formstack/',
							    'is_activate' => boolval( $this->get_settings( 'formstack' ) )
						    ],
						    'typeform'       => [
							    'key'         => 'typeform',
							    'title'       => __( 'Typeform', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/typeform/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/typeform/',
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
							    'demo_link'   => 'https://essential-addons.com/elementor/mailchimp/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/mailchimp/',
							    'is_pro'      => true,
							    'setting'     => $this->pro_enabled ? [ 'id' => 'mailchimpSetting' ] : [],
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'mailchimp' ) )
						    ],
						    'login-register' => [
							    'key'         => 'login-register',
							    'title'       => __( 'Login | Register Form', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/login-register-form',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/login-register-form/',
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
							    'demo_link'   => 'https://essential-addons.com/elementor/twitter-feed/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/twitter-feed/',
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'twitter-feed' ) )
						    ],
						    'twitter-feed-carousel' => [
							    'key'         => 'twitter-feed-carousel',
							    'title'       => __( 'Twitter Feed Carousel', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/twitter-feed/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/twitter-feed-carousel/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'twitter-feed-carousel' ) )
						    ],
						    'instagram-gallery'     => [
							    'key'         => 'instagram-gallery',
							    'title'       => __( 'Instagram Feed', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/instagram-feed/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/instagram-feed/',
							    'is_pro'      => true,
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'instagram-gallery' ) )
						    ],
						    'facebook-feed'         => [
							    'key'         => 'facebook-feed',
							    'title'       => __( 'Facebook Feed', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/facebook-feed/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/facebook-feed/',
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
							    'demo_link'   => 'https://essential-addons.com/elementor/learndash-course-list/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/learndash-course-list/',
							    'is_pro'      => true,
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
							    'demo_link'   => 'https://essential-addons.com/elementor/betterdocs-category-grid/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/betterdocs-category-grid/',
							    'is_activate' => boolval( $this->get_settings( 'betterdocs-category-grid' ) )
						    ],
						    'betterdocs-category-box'  => [
							    'key'         => 'betterdocs-category-box',
							    'title'       => __( 'BetterDocs Category Box', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/betterdocs-category-box/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/betterdocs-category-box/',
							    'is_activate' => boolval( $this->get_settings( 'betterdocs-category-box' ) )
						    ],
						    'betterdocs-search-form'   => [
							    'key'         => 'betterdocs-search-form',
							    'title'       => __( 'BetterDocs Search Form', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/betterdocs-search-form/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/betterdocs-search-form/',
							    'is_activate' => boolval( $this->get_settings( 'betterdocs-search-form' ) )
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
							    'demo_link'   => 'https://essential-addons.com/elementor/woo-product-grid/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/woocommerce-product-grid/',
							    'promotion'   => 'popular',
							    'is_activate' => boolval( $this->get_settings( 'product-grid' ) )
						    ],
						    'woo-product-list'      => [
							    'key'         => 'woo-product-list',
							    'title'       => __( 'Woo Product List', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/woo-product-list/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-woo-product-list/',
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'woo-product-list' ) )
						    ],
						    'woo-collections'       => [
							    'key'         => 'woo-collections',
							    'title'       => __( 'Woo Product Collections', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/woocommerce-product-collections/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-woo-product-collections/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'woo-collections' ) )
						    ],
						    'woo-product-slider'    => [
							    'key'         => 'woo-product-slider',
							    'title'       => __( 'Woo Product Slider', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/woo-product-slider/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/woo-product-slider/',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'woo-product-slider' ) )
						    ],
						    'woo-product-carousel'  => [
							    'key'         => 'woo-product-carousel',
							    'title'       => __( 'Woo Product Carousel', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/woo-product-carousel/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/woo-product-carousel/',
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'woo-product-carousel' ) )
						    ],
						    'woo-checkout'          => [
							    'key'         => 'woo-checkout',
							    'title'       => __( 'Woo Checkout', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/woo-checkout/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/woo-checkout/',
							    'promotion'   => 'updated',
							    'is_activate' => boolval( $this->get_settings( 'woo-checkout' ) )
						    ],
						    'woo-cart'              => [
							    'key'         => 'woo-cart',
							    'title'       => __( 'Woo Cart', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/woo-cart/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/woocommerce-cart/',
							    'promotion'   => 'new',
							    'is_activate' => boolval( $this->get_settings( 'woo-cart' ) )
						    ],
						    'woo-thank-you'         => [
							    'key'         => 'woo-thank-you',
							    'title'       => __( 'Woo Thank You', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/woo-thank-you',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-woo-thank-you',
							    'promotion'   => 'new',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'woo-thank-you' ) )
						    ],
						    'woo-cross-sells'       => [
							    'key'         => 'woo-cross-sells',
							    'title'       => __( 'Woo Cross Sells', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/woo-cross-sells/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-woo-cross-sells/',
							    'promotion'   => 'new',
							    'is_pro'      => true,
							    'is_activate' => boolval( $this->get_settings( 'woo-cross-sells' ) )
						    ],
						    'woo-product-compare'   => [
							    'key'         => 'woo-product-compare',
							    'title'       => __( 'Woo Product Compare', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/woo-product-compare/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/woo-product-compare/',
							    'is_activate' => boolval( $this->get_settings( 'woo-product-compare' ) )
						    ],
						    'woo-product-gallery'   => [
							    'key'         => 'woo-product-gallery',
							    'title'       => __( 'Woo Product Gallery', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/woo-product-gallery/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/woo-product-gallery/',
							    'is_activate' => boolval( $this->get_settings( 'woo-product-gallery' ) )
						    ],
						    'woo-account-dashboard' => [
							    'key'         => 'woo-account-dashboard',
							    'title'       => __( 'Woo Account Dashboard', 'essential-addons-for-elementor-lite' ),
							    'demo_link'   => 'https://essential-addons.com/elementor/woo-account-dashboard/',
							    'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-woo-account-dashboard/',
							    'promotion'   => 'new',
							    'is_pro'      => true,
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
					    'value'      => get_option( 'eael_save_google_map_api', '' ),
					    'image'      => 'images/map.png',
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
						    'url'  => '#'
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
						    'url'  => '#'
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
						    'url'  => '#'
					    ]
				    ]
			    ],
			    'admin_screen_promo'      => [
				    'display' => get_option( 'eael_admin_promotion' ) < self::EAEL_PROMOTION_FLAG,
				    'content' => sprintf( __( "<p> <i>📣</i> NEW: Essential Addons 6.0 is here, with new '<a target='_blank' href='%s'>Woo Product List</a>' widget & more! Check out the <a target='_blank' href='%s'>Changelog</a> for more details 🎉</p>", "essential-addons-for-elementor-lite" ), esc_url( 'https://essential-addons.com/elementor/woo-product-list/' ), esc_url( 'https://essential-addons.com/elementor/changelog/' ) )
			    ]
		    ];

		    wp_localize_script( 'essential_addons_elementor-admin-dashboard', 'localize', array(
			    'ajaxurl'            => admin_url( 'admin-ajax.php' ),
			    'nonce'              => wp_create_nonce( 'essential-addons-elementor' ),
			    'i18n'               => $i18n,
			    'settings_save'      => EAEL_PLUGIN_URL . 'assets/admin/images/settings-save.gif',
			    'assets_regenerated' => EAEL_PLUGIN_URL . 'assets/admin/images/assets-regenerated.gif',
			    'eael_dashboard'     => $ea_dashboard
		    ) );
	    }

        $this->eael_admin_inline_css();
    }

	public function add_type_attribute( $attributes ) {
		if ( isset( $attributes['id'] ) && $attributes['id'] === 'essential_addons_elementor-admin-dashboard-js' ) {
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

        self::$cache_bank = CacheBank::get_instance();

        NoticeRemover::get_instance('1.0.0');
        NoticeRemover::get_instance('1.0.0', '\WPDeveloper\BetterDocs\Dependencies\PriyoMukul\WPNotice\Notices');

        $notices = new Notices( [
			'id'             => 'essential-addons-for-elementor',
			'storage_key'    => 'notices',
			'lifetime'       => 3,
			'stylesheet_url' => esc_url_raw( EAEL_PLUGIN_URL . 'assets/admin/css/notice.css' ),
			'styles' => esc_url_raw( EAEL_PLUGIN_URL . 'assets/admin/css/notice.css' ),
			'priority'       => 1
		] );

        $review_notice = __( 'We hope you\'re enjoying Essential Addons for Elementor! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'essential-addons-for-elementor-lite' );
		$_review_notice = [
			'thumbnail' => plugins_url( 'assets/admin/images/icon-ea-logo.svg', EAEL_PLUGIN_BASENAME ),
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

		$b_message            = '<p>Black Friday Sale: Unlock access to <strong>90+ advanced Elementor widgets</strong> with up to 40% discounts <span class="gift-icon">🎁</span></p><p><a class="button button-primary" href="https://wpdeveloper.com/upgrade/ea-bfcm" target="_blank">Upgrade to pro</a> <button data-dismiss="true" class="dismiss-btn button button-link">I don’t want to save money</button></p>';
		$_black_friday_notice = [
			'thumbnail' => plugins_url( 'assets/admin/images/full-logo.svg', EAEL_PLUGIN_BASENAME ),
			'html'      => $b_message,
		];

	    $notices->add(
			'black_friday_notice',
			$_black_friday_notice,
			[
				'start'       => $notices->time(),
				'recurrence'  => false,
				'dismissible' => true,
				'refresh'     => EAEL_PLUGIN_VERSION,
				"expire"      => strtotime( '11:59:59pm 2nd December, 2023' ),
				'display_if'  => ! $this->pro_enabled,
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

	public function essential_block_optin() {
		if ( is_plugin_active( 'essential-blocks/essential-blocks.php' ) || get_option( 'eael_eb_optin_hide' ) ) {
			return;
		}

		$screen           = get_current_screen();
		$is_exclude       = ! empty( $_GET['post_type'] ) && in_array( $_GET['post_type'], [ 'elementor_library', 'product' ] );
		$ajax_url         = admin_url( 'admin-ajax.php' );
		$nonce            = wp_create_nonce( 'essential-addons-elementor' );
		$eb_not_installed = HelperClass::get_local_plugin_data( 'essential-blocks/essential-blocks.php' ) === false;
		$action           = $eb_not_installed ? 'install' : 'activate';
		$button_title     = $eb_not_installed ? esc_html__( 'Install Essential Blocks', 'essential-addons-for-elementor-lite' ) : esc_html__( 'Activate', 'essential-addons-for-elementor-lite' );

		if ( $screen->parent_base !== 'edit' || $is_exclude ) {
			return;
		}
		?>
        <div class="wpnotice-wrapper notice  notice-info is-dismissible eael-eb-optin-notice">
            <div class="wpnotice-content-wrapper">
                <div class="eael-eb-optin">
                    <h3><?php esc_html_e( 'Using Gutenberg? Check out Essential Blocks!', 'essential-addons-for-elementor-lite' ); ?></h3>
                    <p><?php _e( 'Are you using the Gutenberg Editor for your website? Then try out Essential Blocks for Gutenberg, and explore 40+ unique blocks to make your web design experience in WordPress even more powerful. 🚀', 'essential-addons-for-elementor-lite' ); ?></p>
                    <p><?php _e( 'For more information, <a href="https://essential-blocks.com/demo/" target="_blank">check out the demo here</a>.', 'essential-addons-for-elementor-lite' ); ?></p>
                    <p>
                        <a href="#" class="button-primary wpdeveloper-eb-plugin-installer" data-action="<?php echo esc_attr( $action ); ?>"><?php echo esc_html( $button_title ); ?></a>
                    </p>
                </div>
            </div>
        </div>

        <script>
            // install/activate plugin
            (function ($) {
                $(document).on("click", ".wpdeveloper-eb-plugin-installer", function (ev) {
                    ev.preventDefault();

                    var button = $(this),
                        action = button.data("action");

                    if ($.active && typeof action != "undefined") {
                        button.text("Waiting...").attr("disabled", true);

                        setInterval(function () {
                            if (!$.active) {
                                button.attr("disabled", false).trigger("click");
                            }
                        }, 1000);
                    }

                    if (action === "install" && !$.active) {
                        button.text("Installing...").attr("disabled", true);

                        $.ajax({
                            url: "<?php echo esc_html( $ajax_url ); ?>",
                            type: "POST",
                            data: {
                                action: "wpdeveloper_install_plugin",
                                security: "<?php echo esc_html( $nonce ); ?>",
                                slug: "essential-blocks",
                            },
                            success: function (response) {
                                if (response.success) {
                                    button.text("Activated");
                                    button.data("action", null);

                                    setTimeout(function () {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    button.text("Install");
                                }

                                button.attr("disabled", false);
                            },
                            error: function (err) {
                                console.log(err.responseJSON);
                            },
                        });
                    } else if (action === "activate" && !$.active) {
                        button.text("Activating...").attr("disabled", true);

                        $.ajax({
                            url: "<?php echo esc_html( $ajax_url ); ?>",
                            type: "POST",
                            data: {
                                action: "wpdeveloper_activate_plugin",
                                security: "<?php echo esc_html( $nonce ); ?>",
                                basename: "essential-blocks/essential-blocks.php",
                            },
                            success: function (response) {
                                if (response.success) {
                                    button.text("Activated");
                                    button.data("action", null);

                                    setTimeout(function () {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    button.text("Activate");
                                }

                                button.attr("disabled", false);
                            },
                            error: function (err) {
                                console.log(err.responseJSON);
                            },
                        });
                    }
                }).on('click', '.eael-eb-optin-notice button.notice-dismiss', function (e) {
                    e.preventDefault();

                    var $notice_wrapper = $(this).closest('.eael-eb-optin-notice');

                    $.ajax({
                        url: "<?php echo esc_html( $ajax_url ); ?>",
                        type: "POST",
                        data: {
                            action: "eael_eb_optin_notice_dismiss",
                            security: "<?php echo esc_html( $nonce ); ?>",
                        },
                        success: function (response) {
                            if (response.success) {
                                $notice_wrapper.remove();
                            } else {
                                console.log(response.data);
                            }
                        },
                        error: function (err) {
                            console.log(err.responseText);
                        },
                    });
                });
            })(jQuery);
        </script>
		<?php
	}

	public function essential_block_special_optin() {
		if ( is_plugin_active( 'essential-blocks/essential-blocks.php' ) || get_option( 'eael_eb_optin_hide' ) ) {
			return;
		}

		$ajax_url         = admin_url( 'admin-ajax.php' );
		$nonce            = wp_create_nonce( 'essential-addons-elementor' );
		$eb_not_installed = HelperClass::get_local_plugin_data( 'essential-blocks/essential-blocks.php' ) === false;
		$action           = $eb_not_installed ? 'install' : 'activate';
		$button_title     = $eb_not_installed ? esc_html__( 'Install Essential Blocks', 'essential-addons-for-elementor-lite' ) : esc_html__( 'Activate', 'essential-addons-for-elementor-lite' );
		?>
        <style>
            /* Essential Blocks Special Optin*/
            .eael-eb-special-optin-notice {
                border-left-color: #6200ee;
                padding-top: 0;
                padding-bottom: 0;
                padding-left: 0;
            }

            .eael-eb-special-optin-notice h3,
            .eael-eb-special-optin-notice p,
            .eael-eb-special-optin-notice a {
                font-family: -apple-system,BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            }

            .eael-eb-special-optin-notice a {
                color: #2271b1;
            }

            .eael-eb-special-optin-notice .wpnotice-content-wrapper {
                display: flex;
            }

            .eael-eb-special-optin-notice .wpnotice-content-wrapper > div {
                padding-top: 15px;
            }

            .eael-eb-special-optin-notice .eael-eb-optin-logo {
                width: 50px;
                text-align: center;
                background: rgba(98, 0, 238, .1);
            }

            .eael-eb-special-optin-notice .eael-eb-optin-logo img {
                width: 25px;
            }

            .eael-eb-special-optin-notice .eael-eb-optin {
                padding-left: 10px;
            }

            .eael-eb-special-optin-notice .eael-eb-optin a.wpdeveloper-eb-plugin-installer {
                background: #5E2EFF;
            }
        </style>
        <div class="wpnotice-wrapper notice  notice-info is-dismissible eael-eb-special-optin-notice">
            <div class="wpnotice-content-wrapper">
                <div class="eael-eb-optin-logo">
                    <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/eb-new.svg' ); ?>" alt="">
                </div>
                <div class="eael-eb-optin">
                    <h3><?php esc_html_e( 'Using Gutenberg? Check out Essential Blocks!', 'essential-addons-for-elementor-lite' ); ?></h3>
                    <p><?php _e( 'Are you using the Gutenberg Editor for your website? Then try out Essential Blocks for Gutenberg, and explore 40+ unique blocks to make your web design experience in WordPress even more powerful. 🚀', 'essential-addons-for-elementor-lite' ); ?></p>
                    <p><?php _e( 'For more information, <a href="https://essential-blocks.com/demo/" target="_blank">check out the demo here</a>.', 'essential-addons-for-elementor-lite' ); ?></p>
                    <p>
                        <a href="#" class="button-primary wpdeveloper-eb-plugin-installer" data-action="<?php echo esc_attr( $action ); ?>"><?php echo esc_html( $button_title ); ?></a>
                    </p>
                </div>
            </div>
        </div>

        <script>
            // install/activate plugin
            (function ($) {
                $(document).on("click", ".wpdeveloper-eb-plugin-installer", function (ev) {
                    ev.preventDefault();

                    var button = $(this),
                        action = button.data("action");

                    if ($.active && typeof action != "undefined") {
                        button.text("Waiting...").attr("disabled", true);

                        setInterval(function () {
                            if (!$.active) {
                                button.attr("disabled", false).trigger("click");
                            }
                        }, 1000);
                    }

                    if (action === "install" && !$.active) {
                        button.text("Installing...").attr("disabled", true);

                        $.ajax({
                            url: "<?php echo esc_html( $ajax_url ); ?>",
                            type: "POST",
                            data: {
                                action: "wpdeveloper_install_plugin",
                                security: "<?php echo esc_html( $nonce ); ?>",
                                slug: "essential-blocks",
                            },
                            success: function (response) {
                                if (response.success) {
                                    button.text("Activated");
                                    button.data("action", null);

                                    setTimeout(function () {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    button.text("Install");
                                }

                                button.attr("disabled", false);
                            },
                            error: function (err) {
                                console.log(err.responseJSON);
                            },
                        });
                    } else if (action === "activate" && !$.active) {
                        button.text("Activating...").attr("disabled", true);

                        $.ajax({
                            url: "<?php echo esc_html( $ajax_url ); ?>",
                            type: "POST",
                            data: {
                                action: "wpdeveloper_activate_plugin",
                                security: "<?php echo esc_html( $nonce ); ?>",
                                basename: "essential-blocks/essential-blocks.php",
                            },
                            success: function (response) {
                                if (response.success) {
                                    button.text("Activated");
                                    button.data("action", null);

                                    setTimeout(function () {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    button.text("Activate");
                                }

                                button.attr("disabled", false);
                            },
                            error: function (err) {
                                console.log(err.responseJSON);
                            },
                        });
                    }
                }).on('click', '.eael-eb-special-optin-notice button.notice-dismiss', function (e) {
                    e.preventDefault();

                    var $notice_wrapper = $(this).closest('.eael-eb-optin-notice');

                    $.ajax({
                        url: "<?php echo esc_html( $ajax_url ); ?>",
                        type: "POST",
                        data: {
                            action: "eael_eb_optin_notice_dismiss",
                            security: "<?php echo esc_html( $nonce ); ?>",
                        },
                        success: function (response) {
                            if (response.success) {
                                $notice_wrapper.remove();
                            } else {
                                console.log(response.data);
                            }
                        },
                        error: function (err) {
                            console.log(err.responseText);
                        },
                    });
                });
            })(jQuery);
        </script>
		<?php
	}

	public function eael_eb_optin_notice_dismiss() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		update_option( 'eael_eb_optin_hide', true );
		wp_send_json_success();
	}

	public function eael_gb_eb_popup_dismiss() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You are not allowed to do this action', 'essential-addons-for-elementor-lite' ) );
		}

		update_option( 'eael_gb_eb_popup_hide', true );
		wp_send_json_success();
	}

	public function eael_black_friday_optin_dismiss() {
		check_ajax_referer( 'essential-addons-elementor', 'security' );

//		update_option( 'eael_black_friday_optin_hide', true );
		set_transient( 'eael_2M_optin_hide', true, 20 * DAY_IN_SECONDS );
		wp_send_json_success();
	}

	public function eael_black_friday_optin() {
		$time     = time();
		$ajax_url = admin_url( 'admin-ajax.php' );
		$nonce    = wp_create_nonce( 'essential-addons-elementor' );
		if ( $time > 1715126399 || get_transient( 'eael_2M_optin_hide' ) || defined( 'EAEL_PRO_PLUGIN_VERSION' ) ) {
			return;
		}
		?>
        <style>
            .eael-black-friday-notice,
            .eael-black-friday-notice * {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            }
            .eael-black-friday-notice {
                padding: 0;
                border-left-color: #6200ee;
            }
            .eael-black-friday-notice .wpnotice-content-wrapper {
                display: flex;
            }
            .eael-black-friday-notice .wpnotice-content-wrapper .eael-black-friday-optin-logo {
                width: 50px;
                padding: 26px 0 0;
                text-align: center;
                background: rgba(98, 0, 238, .1);
            }
            .eael-black-friday-notice .wpnotice-content-wrapper .eael-black-friday-optin {
                padding-left: 10px;
            }
            a.eael-2m-notice-hide {
                color: #2271b1;
                text-decoration: underline;
            }
            a.eael-2m-notice-hide:hover {
                color: #135e96;
                text-decoration: underline;
            }
        </style>
        <div class="wpnotice-wrapper notice notice-info is-dismissible eael-black-friday-notice">
            <div class="wpnotice-content-wrapper">
                <div class="eael-black-friday-optin-logo">
                    <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/icon-ea-logo.svg' ); ?>" width="25" alt="">
                </div>
                <div class="eael-black-friday-optin">
					<p><?php _e( 'Join us in celebrating <strong>2 MILLION+</strong> happy users and grab up to an exclusive 30% OFF on the most used Elementor addons!', 'essential-addons-for-elementor-lite' ); ?></p>
					<p><a href="https://essential-addons.com/upgrade-to-ea-pro" target="_blank"
						  class="button-primary"><?php _e( 'Upgrade To PRO Now', 'essential-addons-for-elementor-lite' ); ?></a>
						<a href="https://essential-addons.com/ea-lifetime-access" target="_blank"
						   class="button-secondary"><?php _e( 'Give Me LIFETIME access', 'essential-addons-for-elementor-lite' ); ?></a>
						<a href="#" target="_blank"
						   class="eael-2m-notice-hide"><?php _e( 'I don’t want to save money', 'essential-addons-for-elementor-lite' ); ?></a>
					</p>
                </div>
            </div>
        </div>

        <script>
            (function ($) {
                $(document).on('click', '.eael-black-friday-notice button.notice-dismiss, .eael-2m-notice-hide', function (e) {
                    e.preventDefault();

                    var $notice_wrapper = $(this).closest('.eael-black-friday-notice');

                    $.ajax({
                        url: "<?php echo esc_html( $ajax_url ); ?>",
                        type: "POST",
                        data: {
                            action: "eael_black_friday_optin_dismiss",
                            security: "<?php echo esc_html( $nonce ); ?>",
                        },
                        success: function (response) {
                            if (response.success) {
                                $notice_wrapper.remove();
                            } else {
                                console.log(response.data);
                            }
                        },
                        error: function (err) {
                            console.log(err.responseText);
                        },
                    });
                });
            })(jQuery);
        </script>
		<?php
	}
}

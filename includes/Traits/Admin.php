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
            wp_enqueue_style( 'essential_addons_elementor-admin-css', EAEL_PLUGIN_URL . 'assets/admin/css/admin.css', false, EAEL_PLUGIN_VERSION );
            if ( $this->pro_enabled ) {
                wp_enqueue_style( 'eael_pro-admin-css', EAEL_PRO_PLUGIN_URL . 'assets/admin/css/admin.css', false, EAEL_PRO_PLUGIN_VERSION );
            }
            wp_enqueue_style( 'sweetalert2-css', EAEL_PLUGIN_URL . 'assets/admin/vendor/sweetalert2/css/sweetalert2.min.css', false, EAEL_PLUGIN_VERSION );
            wp_enqueue_script( 'sweetalert2-js', EAEL_PLUGIN_URL . 'assets/admin/vendor/sweetalert2/js/sweetalert2.min.js', array( 'jquery', 'sweetalert2-core-js' ), EAEL_PLUGIN_VERSION, true );
            wp_enqueue_script( 'sweetalert2-core-js', EAEL_PLUGIN_URL . 'assets/admin/vendor/sweetalert2/js/core.js', array( 'jquery' ), EAEL_PLUGIN_VERSION, true );

            wp_enqueue_script( 'essential_addons_elementor-admin-js', EAEL_PLUGIN_URL . 'assets/admin/js/admin.js', array( 'jquery' ), EAEL_PLUGIN_VERSION, true );
	        wp_enqueue_script( 'essential_addons_elementor-admin-dashboard', EAEL_PLUGIN_URL . 'includes/templates/admin/eael-dashboard/dist/assets/ea-dashboard.js', array( 'essential_addons_elementor-admin-js' ), time(), true );
	        add_filter( 'wp_script_attributes', [ $this, 'add_type_attribute' ] );

            //Internationalizing JS string translation
            $i18n = [
                'login_register' => [
                    //m=modal, rm=response modal, r=reCAPTCHA, g= google, f=facebook, e=error
                    'm_title'      => __( 'Login | Register Form Settings', 'essential-addons-for-elementor-lite' ),
                    'm_footer'     => $this->pro_enabled ? __( 'To configure the API Keys, check out this doc', 'essential-addons-for-elementor-lite' ) : __( 'To retrieve your API Keys, click here', 'essential-addons-for-elementor-lite' ),
                    'save'         => __( 'Save', 'essential-addons-for-elementor-lite' ),
                    'cancel'       => __( 'Cancel', 'essential-addons-for-elementor-lite' ),
                    'rm_title'     => __( 'Login | Register Form Settings Saved', 'essential-addons-for-elementor-lite' ),
                    'rm_footer'    => __( 'Reload the page to see updated data', 'essential-addons-for-elementor-lite' ),
                    'e_title'      => __( 'Oops...', 'essential-addons-for-elementor-lite' ),
                    'e_text'       => __( 'Something went wrong!', 'essential-addons-for-elementor-lite' ),
                    'r_title'      => __( 'reCAPTCHA v2', 'essential-addons-for-elementor-lite' ),
                    'r_sitekey'    => __( 'Site Key', 'essential-addons-for-elementor-lite' ),
                    'r_sitesecret' => __( 'Site Secret', 'essential-addons-for-elementor-lite' ),
                    'r_language'   => __( 'Language', 'essential-addons-for-elementor-lite' ),
                    'r_language_ph'=> __( 'reCAPTCHA Language Code', 'essential-addons-for-elementor-lite' ),
                    'g_title'      => __( 'Google Login', 'essential-addons-for-elementor-lite' ),
                    'g_cid'        => __( 'Google Client ID', 'essential-addons-for-elementor-lite' ),
                    'f_title'      => __( 'Facebook Login', 'essential-addons-for-elementor-lite' ),
                    'f_app_id'     => __( 'Facebook APP ID', 'essential-addons-for-elementor-lite' ),
                    'f_app_secret' => __( 'Facebook APP Secret', 'essential-addons-for-elementor-lite' ),
                ]
            ];

	        $ea_dashboard = [
		        'reactPath'            => EAEL_PLUGIN_URL . 'includes/templates/admin/eael-dashboard/dist/',
		        'is_eapro_activate'    => $this->pro_enabled,
		        'menu'                 => [
			        __( 'General', 'essential-addons-for-elementor-lite' )     => 'ea-home',
			        __( 'Elements', 'essential-addons-for-elementor-lite' )    => 'ea-elements',
			        __( 'Extensions', 'essential-addons-for-elementor-lite' )  => 'ea-extensions',
			        __( 'Tools', 'essential-addons-for-elementor-lite' )       => 'ea-tool',
			        __( 'Integration', 'essential-addons-for-elementor-lite' ) => 'ea-plug',
			        __( 'Go Premium', 'essential-addons-for-elementor-lite' )  => 'ea-lock',
		        ],
		        'whats_new'            => [
			        'heading' => __( 'What is New on Version?', 'essential-addons-for-elementor-lite' ),
			        'list'    => [
				        __( 'EA Fancy Chart Symbol display option in the fancy chart', 'essential-addons-for-elementor-lite' ),
				        __( 'EA Instagram Feed Feed is getting broken on the front-end view', 'essential-addons-for-elementor-lite' ),
				        __( 'EA Woo Product Carouse Option to hide and show add to cart', 'essential-addons-for-elementor-lite' ),
			        ],
			        'button'  => [
				        'label' => __( 'View Changelog', 'essential-addons-for-elementor-lite' ),
				        'url'   => '#'
			        ]
		        ],
		        'templately_promo'     => [
			        'heading' => __( 'Unlock 5000+ Ready Templates', 'essential-addons-for-elementor-lite' ),
			        'list'    => [
				        __( 'Stunning, Ready Website Templates', 'essential-addons-for-elementor-lite' ),
				        __( 'Add Team Members & Collaborate', 'essential-addons-for-elementor-lite' ),
				        __( 'Cloud With Templately WorkSpace', 'essential-addons-for-elementor-lite' ),
			        ],
			        'button'  => [
				        'label' => __( 'Install templately', 'essential-addons-for-elementor-lite' ),
				        'url'   => '#'
			        ]
		        ],
		        'community_box'        => [
			        [
				        'heading'    => __( 'GitHub & Support', 'essential-addons-for-elementor-lite' ),
				        'content'    => __( 'Encountering a problem? Seek assistance through live chat or by submitting.', 'essential-addons-for-elementor-lite' ),
				        'button'     => [
					        'label' => __( 'Create Ticket', 'essential-addons-for-elementor-lite' ),
					        'url'   => '#'
				        ],
				        'icon'       => 'ea-github',
				        'icon_color' => 'eaicon-1'
			        ],
			        [
				        'heading'    => __( 'Join Community', 'essential-addons-for-elementor-lite' ),
				        'content'    => __( 'Encountering a problem? Seek assistance through live chat or by submitting.', 'essential-addons-for-elementor-lite' ),
				        'button'     => [
					        'label' => __( 'Join with us', 'essential-addons-for-elementor-lite' ),
					        'url'   => '#'
				        ],
				        'icon'       => 'ea-community',
				        'icon_color' => 'eaicon-2'
			        ],
			        [
				        'heading'    => __( 'View knowledgebase', 'essential-addons-for-elementor-lite' ),
				        'content'    => __( 'Get started by spending some time with the documentation', 'essential-addons-for-elementor-lite' ),
				        'button'     => [
					        'label' => __( 'View Docs', 'essential-addons-for-elementor-lite' ),
					        'url'   => '#'
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
		        'sidebar_box'          => [
			        'heading' => __( 'Unlimited Features', 'essential-addons-for-elementor-lite' ),
			        'content' => __( 'Supercharge your content schedule and', 'essential-addons-for-elementor-lite' ),
			        'review'  => [
				        'label' => __( 'Review from Real Users', 'essential-addons-for-elementor-lite' ),
				        'score' => __( '5/5', 'essential-addons-for-elementor-lite' ),
			        ],
			        'button'  => [
				        'label' => __( 'Upgrade to PRO', 'essential-addons-for-elementor-lite' ),
				        'url'   => '#',
				        'icon'  => 'ea-crown-1'
			        ]
		        ],
		        'integration_box'      => [
			        'enable'  => __( 'Enable Integration', 'essential-addons-for-elementor-lite' ),
			        'disable' => __( 'Disable Integration', 'essential-addons-for-elementor-lite' ),
			        'list'    => [
				        'bd' => [
					        'heading' => __( 'BetterDocs', 'essential-addons-for-elementor-lite' ),
					        'content' => __( 'BetterDocs will help you to create & organize your documentation page in a beautiful way that will make your visitors find any help article easily.', 'essential-addons-for-elementor-lite' ),
					        'icon'    => 'images/BD.svg',
					        'status'  => true
				        ],
				        'eb' => [
					        'heading' => __( 'Essential Blocks', 'essential-addons-for-elementor-lite' ),
					        'content' => __( 'BetterDocs will help you to create & organize your documentation page in a beautiful way that will make your visitors find any help article easily.', 'essential-addons-for-elementor-lite' ),
					        'icon'    => 'images/EB.svg',
					        'status'  => false
				        ],
				        'ep' => [
					        'heading' => __( 'EmbedPress', 'essential-addons-for-elementor-lite' ),
					        'content' => __( 'BetterDocs will help you to create & organize your documentation page in a beautiful way that will make your visitors find any help article easily.', 'essential-addons-for-elementor-lite' ),
					        'icon'    => 'images/EP.svg',
					        'status'  => true
				        ],
				        'nx' => [
					        'heading' => __( 'NotificationX', 'essential-addons-for-elementor-lite' ),
					        'content' => __( 'BetterDocs will help you to create & organize your documentation page in a beautiful way that will make your visitors find any help article easily.', 'essential-addons-for-elementor-lite' ),
					        'icon'    => 'images/NX.svg',
					        'status'  => true
				        ]
			        ]
		        ],
		        'premium_items'        => [
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
				        ]
			        ]
		        ],
		        'enhance_experience'   => [
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
		        'explore_pro_features' => [
			        'heading' => __( "Explore Premiere Pro features", 'essential-addons-for-elementor-lite' ),
			        'content' => __( "Learn all about the tools and techniques you can use to edit videos, animate titles, add effects, mix sound, and more.", 'essential-addons-for-elementor-lite' ),
			        'image'   => 'images/img-3.png',
			        'button'  => [
				        'label' => __( 'View Changelog', 'essential-addons-for-elementor-lite' ),
				        'url'   => '#',
				        'icon'  => 'ea-link'
			        ]
		        ],
		        'tools'                => [
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
					        'url'   => '#'
				        ]
			        ],
			        'box_3' => [
				        'heading' => __( "JS Print Method", 'essential-addons-for-elementor-lite' ),
				        'content' => __( "CSS Print Method is handled by Elementor Settings itself. Use External CSS Files for better performance (recommended).", 'essential-addons-for-elementor-lite' ),
				        'methods' => [
					        'external' => __( 'External file', 'essential-addons-for-elementor-lite' ),
					        'internal' => __( 'Internal Embedding', 'essential-addons-for-elementor-lite' ),
				        ]
			        ]
		        ],
		        'extensions'           => [
			        'heading'    => __( 'Premium Extensions', 'essential-addons-for-elementor-lite' ),
			        'enable_all' => [
				        'label'  => 'Enable all',
				        'status' => true
			        ],
			        'list'       => [
				        [
					        'key'         => 'section-parallax',
					        'title'       => __( 'Parallax', 'essential-addons-for-elementor-lite' ),
					        'demo_link'   => 'https://essential-addons.com/elementor/parallax-scrolling/',
					        'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-parallax/',
					        'is_pro'      => true,
					        'is_activate' => absint( $this->get_settings( 'section-parallax' ) )
				        ],
				        [
					        'key'         => 'section-particles',
					        'title'       => __( 'Particles', 'essential-addons-for-elementor-lite' ),
					        'demo_link'   => 'https://essential-addons.com/elementor/particle-effect/',
					        'doc_link'    => 'https://essential-addons.com/elementor/docs/particles/',
					        'is_pro'      => true,
					        'is_activate' => absint( $this->get_settings( 'section-parallax' ) )
				        ],
				        [
					        'key'         => 'tooltip-section',
					        'title'       => __( 'Advanced Tooltip', 'essential-addons-for-elementor-lite' ),
					        'demo_link'   => 'https://essential-addons.com/elementor/advanced-tooltip/',
					        'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-advanced-tooltip/',
					        'is_pro'      => true,
					        'is_activate' => absint( $this->get_settings( 'tooltip-section' ) )
				        ],
				        [
					        'key'         => 'content-protection',
					        'title'       => __( 'Content Protection', 'essential-addons-for-elementor-lite' ),
					        'demo_link'   => 'https://essential-addons.com/elementor/content-protection/',
					        'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-content-protection/',
					        'is_pro'      => true,
					        'promotion'   => 'popular',
					        'is_activate' => absint( $this->get_settings( 'content-protection' ) )
				        ],
				        [
					        'key'         => 'reading-progress',
					        'title'       => __( 'Reading Progress Bar', 'essential-addons-for-elementor-lite' ),
					        'demo_link'   => 'https://essential-addons.com/elementor/reading-progress/',
					        'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-reading-progress-bar/',
					        'is_pro'      => false,
					        'is_activate' => absint( $this->get_settings( 'reading-progress' ) )
				        ],
				        [
					        'key'         => 'table-of-content',
					        'title'       => __( 'Table of Contents', 'essential-addons-for-elementor-lite' ),
					        'demo_link'   => 'https://essential-addons.com/elementor/table-of-content/',
					        'doc_link'    => 'https://essential-addons.com/elementor/docs/table-of-content',
					        'is_pro'      => false,
					        'promotion'   => 'popular',
					        'is_activate' => absint( $this->get_settings( 'table-of-content' ) )
				        ],
				        [
					        'key'         => 'post-duplicator',
					        'title'       => __( 'Duplicator', 'essential-addons-for-elementor-lite' ),
					        'demo_link'   => 'https://essential-addons.com/elementor/duplicator/',
					        'doc_link'    => 'https://essential-addons.com/elementor/docs/duplicator/',
					        'is_pro'      => false,
					        'setting'     => [ 'id' => 'eael-post-duplicator-setting' ],
					        'is_activate' => absint( $this->get_settings( 'post-duplicator' ) )
				        ],
				        [
					        'key'         => 'custom-js',
					        'title'       => __( 'Custom JS', 'essential-addons-for-elementor-lite' ),
					        'demo_link'   => 'https://essential-addons.com/elementor/custom-js/',
					        'doc_link'    => 'https://essential-addons.com/elementor/docs/custom-js/',
					        'is_pro'      => false,
					        'promotion'   => 'popular',
					        'is_activate' => absint( $this->get_settings( 'custom-js' ) )
				        ],
				        [
					        'key'         => 'xd-copy',
					        'title'       => __( 'Cross-Domain Copy Paste', 'essential-addons-for-elementor-lite' ),
					        'demo_link'   => 'https://essential-addons.com/elementor/cross-domain-copy-paste/',
					        'doc_link'    => 'https://essential-addons.com/elementor/docs/cross-domain-copy-paste/',
					        'is_pro'      => true,
					        'promotion'   => 'new',
					        'is_activate' => absint( $this->get_settings( 'xd-copy' ) )
				        ],
				        [
					        'key'         => 'scroll-to-top',
					        'title'       => __( 'Scroll to Top', 'essential-addons-for-elementor-lite' ),
					        'demo_link'   => 'https://essential-addons.com/elementor/scroll-to-top/',
					        'doc_link'    => 'https://essential-addons.com/elementor/docs/scroll-to-top/',
					        'is_pro'      => false,
					        'promotion'   => 'new',
					        'is_activate' => absint( $this->get_settings( 'scroll-to-top' ) )
				        ],
				        [
					        'key'         => 'conditional-display',
					        'title'       => __( 'Conditional Display', 'essential-addons-for-elementor-lite' ),
					        'demo_link'   => 'https://essential-addons.com/elementor/conditional-display/',
					        'doc_link'    => 'https://essential-addons.com/elementor/docs/conditional-display/',
					        'is_pro'      => true,
					        'promotion'   => 'new',
					        'is_activate' => absint( $this->get_settings( 'conditional-display' ) )
				        ],
				        [
					        'key'         => 'wrapper-link',
					        'title'       => __( 'Wrapper Link', 'essential-addons-for-elementor-lite' ),
					        'demo_link'   => 'https://essential-addons.com/elementor/wrapper-link/',
					        'doc_link'    => 'https://essential-addons.com/elementor/docs/ea-wrapper-link/',
					        'is_pro'      => false,
					        'promotion'   => 'new',
					        'is_activate' => absint( $this->get_settings( 'wrapper-link' ) )
				        ],
			        ]
		        ]
	        ];

	        wp_localize_script( 'essential_addons_elementor-admin-js', 'localize', array(
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
		<link rel="stylesheet" href="<?php echo EAEL_PLUGIN_URL; ?>includes/templates/admin/new-ui/assets/icons/style.css">
		<link rel="stylesheet" href="<?php echo EAEL_PLUGIN_URL; ?>includes/templates/admin/eael-dashboard/dist/assets/ea-dashboard.css">
		<div id="eael-dashboard"></div>
		<form action="" method="POST" id="eael-settings" name="eael-settings" style="display: none;">
            <div class="template__wrapper background__greyBg px30 py50">
                <div class="eael-container">
                    <div class="eael-main__tab mb45">
                        <ul class="ls-none tab__menu">
                            <li class="tab__list active"><a class="tab__item" href="#general"><i class="ea-admin-icon eael-icon-gear-alt"></i><?php echo __( 'General', 'essential-addons-for-elementor-lite' ); ?></a></li>
                            <li class="tab__list"><a class="tab__item" href="#elements"><i class="ea-admin-icon eael-icon-element"></i><?php echo __( 'Elements', 'essential-addons-for-elementor-lite' ); ?></a></li>
                            <li class="tab__list"><a class="tab__item" href="#extensions"><i class="ea-admin-icon eael-icon-extension"></i><?php echo __( 'Extensions', 'essential-addons-for-elementor-lite' ); ?></a></li>
                            <li class="tab__list"><a class="tab__item" href="#tools"><i class="ea-admin-icon eael-icon-tools"></i><?php echo __( 'Tools', 'essential-addons-for-elementor-lite' ); ?></a></li>
                            <li class="tab__list"><a class="tab__item" href="#integrations"><i class="ea-admin-icon eael-icon-plug"></i><?php echo __( 'Integrations', 'essential-addons-for-elementor-lite' ); ?></a></li>
                            <?php  if ( !$this->pro_enabled ) { ?>
                                <li class="tab__list"><a class="tab__item" href="#go-pro"><i class="ea-admin-icon eael-icon-lock-alt"></i><?php echo __( 'Go Premium', 'essential-addons-for-elementor-lite' ); ?></a></li>
                             <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="eael-admin-setting-tabs">
	                <?php
	                include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/general.php';
	                include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/elements.php';
	                include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/extensions.php';
	                include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/tools.php';
	                if ( !$this->pro_enabled ) {
		                include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/go-pro.php';
	                }
	                include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/integrations.php';
	                include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/popup.php';
	                ?>
                </div>
            </div>
        </form>
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

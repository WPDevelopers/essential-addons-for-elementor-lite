<?php

namespace Essential_Addons_Elementor\Traits;

if ( !defined( 'ABSPATH' ) ) {
    exit();
}

// Exit if accessed directly

use Essential_Addons_Elementor\Classes\WPDeveloper_Notice;

trait Admin {
    /**
     * Create an admin menu.
     *
     * @since 1.1.2
     */
    public function admin_menu() {
        add_menu_page(
            __( 'Essential Addons', 'essential-addons-for-elementor-lite' ),
            __( 'Essential Addons', 'essential-addons-for-elementor-lite' ),
            'manage_options',
            'eael-settings',
            [$this, 'admin_settings_page'],
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

            wp_localize_script( 'essential_addons_elementor-admin-js', 'localize', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'essential-addons-elementor' ),
                'i18n'    => $i18n,
                'settings_save' => EAEL_PLUGIN_URL . 'assets/admin/images/settings-save.gif',
                'assets_regenerated' => EAEL_PLUGIN_URL . 'assets/admin/images/assets-regenerated.gif',
            ) );
        }
    }

    /**
     * Create settings page.
     *
     * @since 1.1.2
     */
    public function admin_settings_page() {
        $a = 'manzur';
        ?>
        <form action="" method="POST" id="eael-settings" name="eael-settings">
            <div class="template__wrapper background__greyBg px30 py50">
                <div class="eael-container">
                    <div class="eael-main__tab mb45">
                        <ul class="ls-none tab__menu">
                            <li class="tab__list active"><a class="tab__item" href="#general"><i class="ea-admin-icon icon-gear-alt"></i><?php echo __( 'General', 'essential-addons-for-elementor-lite' ); ?></a></li>
                            <li class="tab__list"><a class="tab__item" href="#elements"><i class="ea-admin-icon icon-element"></i><?php echo __( 'Elements', 'essential-addons-for-elementor-lite' ); ?></a></li>
                            <li class="tab__list"><a class="tab__item" href="#extensions"><i class="ea-admin-icon icon-extension"></i><?php echo __( 'Extensions', 'essential-addons-for-elementor-lite' ); ?></a></li>
                            <li class="tab__list"><a class="tab__item" href="#tools"><i class="ea-admin-icon icon-tools"></i><?php echo __( 'Tools', 'essential-addons-for-elementor-lite' ); ?></a></li>
                            <li class="tab__list"><a class="tab__item" href="#integrations"><i class="ea-admin-icon icon-plug"></i><?php echo __( 'Integrations', 'essential-addons-for-elementor-lite' ); ?></a></li>
                            <?php  if ( !$this->pro_enabled ) { ?>
                                <li class="tab__list"><a class="tab__item" href="#go-pro"><i class="ea-admin-icon icon-lock-alt"></i><?php echo __( 'Go Premium', 'essential-addons-for-elementor-lite' ); ?></a></li>
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
    }

    /**
     * Saving data with ajax request
     * @param
     * @return  array
     * @since 1.1.2
     */


    public function admin_notice() {
        $notice = new WPDeveloper_Notice( EAEL_PLUGIN_BASENAME, EAEL_PLUGIN_VERSION );
        /**
         * Current Notice End Time.
         * Notice will dismiss in 3 days if user does nothing.
         */
        $notice->cne_time = '3 Day';
        /**
         * Current Notice Maybe Later Time.
         * Notice will show again in 7 days
         */
        $notice->maybe_later_time = '21 Day';

        $scheme        = ( parse_url( $_SERVER[ 'REQUEST_URI' ], PHP_URL_QUERY ) ) ? '&' : '?';
        $url           = $_SERVER[ 'REQUEST_URI' ] . $scheme;
        $notice->links = [
            'review' => array(
                'later'            => array(
                    'link'       => 'https://wpdeveloper.com/review-essential-addons-elementor',
                    'target'     => '_blank',
                    'label'      => __( 'Ok, you deserve it!', 'essential-addons-for-elementor-lite' ),
                    'icon_class' => 'dashicons dashicons-external',
                ),
                'allready'         => array(
                    'link'       => esc_url( $url ),
                    'label'      => __( 'I already did', 'essential-addons-for-elementor-lite' ),
                    'icon_class' => 'dashicons dashicons-smiley',
                    'data_args'  => [
                        'dismiss' => true,
                    ],
                ),
                'maybe_later'      => array(
                    'link'       => esc_url( $url ),
                    'label'      => __( 'Maybe Later', 'essential-addons-for-elementor-lite' ),
                    'icon_class' => 'dashicons dashicons-calendar-alt',
                    'data_args'  => [
                        'later' => true,
                    ],
                ),
                'support'          => array(
                    'link'       => 'https://wpdeveloper.com/support',
                    'label'      => __( 'I need help', 'essential-addons-for-elementor-lite' ),
                    'icon_class' => 'dashicons dashicons-sos',
                ),
                'never_show_again' => array(
                    'link'       => esc_url( $url ),
                    'label'      => __( 'Never show again', 'essential-addons-for-elementor-lite' ),
                    'icon_class' => 'dashicons dashicons-dismiss',
                    'data_args'  => [
                        'dismiss' => true,
                    ],
                ),
            ),
        ];

        /**
         * This is review message and thumbnail.
         */
        $notice->message( 'review', '<p>' . __( 'We hope you\'re enjoying Essential Addons for Elementor! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'essential-addons-for-elementor-lite' ) . '</p>' );
        $notice->thumbnail( 'review', plugins_url( 'assets/admin/images/icon-ea-logo.svg', EAEL_PLUGIN_BASENAME ) );
        /**
         * This is upsale notice settings
         * classes for wrapper,
         * Message message for showing.
         */

        // Update Notice For PRO Version
        if ( $this->pro_enabled && \version_compare( EAEL_PRO_PLUGIN_VERSION, '4.0.0', '<' ) ) {
            $notice->classes( 'update', 'notice is-dismissible ' );
            $notice->message( 'update', '<p>' . __( 'You are using an incompatible version of Essential Addons PRO. Please update to v4.0.0+. If you do not see automatic update, <a href="https://essential-addons.com/elementor/docs/manually-update-essential-addons-pro/" target="_blank">Follow manual update guide.</a>', 'essential-addons-for-elementor-lite' ) . '</p>' );
            $notice->thumbnail( 'update', plugins_url( 'assets/admin/images/icon-ea-logo.svg', EAEL_PLUGIN_BASENAME ) );
        }


        $notice->options_args = array(
            'notice_will_show' => [
                'opt_in' => $notice->timestamp,
                'review' => $notice->makeTime( $notice->timestamp, '7 Day' ), // after 3 days
            ],
        );
        if ( $this->pro_enabled && \version_compare( EAEL_PRO_PLUGIN_VERSION, '4.0.0', '<' ) ) {
            $notice->options_args[ 'notice_will_show' ][ 'update' ] = $notice->timestamp;
        }

        $notice->init();
    }
}

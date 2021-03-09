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
            ) );
        }
    }

    /**
     * Create settings page.
     *
     * @since 1.1.2
     */
    public function admin_settings_page() {
        ?>
        <div class="eael-settings-wrap">
            <form action="" method="POST" id="eael-settings" name="eael-settings">
                <div class="eael-header-bar">
                    <div class="eael-header-left">
                        <div class="eael-admin-logo-inline">
                            <img src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-ea-logo.svg'; ?>"
                                 alt="essential-addons-for-elementor">
                        </div>
                        <h2 class="title"><?php echo __( 'Essential Addons Settings', 'essential-addons-for-elementor-lite' ); ?></h2>
                    </div>
                    <div class="eael-header-right">
                        <button type="submit"
                                class="button eael-btn js-eael-settings-save"><?php echo __( 'Save settings', 'essential-addons-for-elementor-lite' ); ?></button>
                    </div>
                </div>
                <div class="eael-settings-tabs">
                    <ul class="eael-tabs">
                        <li><a href="#general" class="active"><img
                                        src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-general.svg'; ?>"
                                        alt="essential-addons-general-settings"><span><?php echo __( 'General', 'essential-addons-for-elementor-lite' ); ?></span></a>
                        </li>
                        <li><a class="eael-elements-tab" href="#elements"><img
                                        src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-elements.svg'; ?>"
                                        alt="essential-addons-elements"><span><?php echo __( 'Elements', 'essential-addons-for-elementor-lite' ); ?></span></a>
                        </li>
                        <li><a href="#extensions"><img
                                        src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-extensions.svg'; ?>"
                                        alt="essential-addons-extensions"><span><?php echo __( 'Extensions', 'essential-addons-for-elementor-lite' ); ?></span></a>
                        </li>
                        <li><a href="#tools"><img
                                        src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-tools.svg'; ?>"
                                        alt="essential-addons-tools"><span><?php echo __( 'Tools', 'essential-addons-for-elementor-lite' ); ?></span></a>
                        </li>
                        <li><a href="#integrations"><img
                                        src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-integrations.svg'; ?>"
                                        alt="essential-addons-integrations"><span><?php echo __( 'Integrations', 'essential-addons-for-elementor-lite' ); ?></span></a>
                        </li>
                        <?php if ( !$this->pro_enabled ) { ?>
                            <li><a href="#go-pro"><img
                                            src="<?php echo EAEL_PLUGIN_URL . 'assets/admin/images/icon-upgrade.svg'; ?>"
                                            alt="essential-addons-go-pro"><span><?php echo __( 'Go Premium', 'essential-addons-for-elementor-lite' ); ?></span></a>
                            </li>
                        <?php } ?>

                    </ul>
                    <?php
                    include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/general.php';
                    include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/elements.php';
                    include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/extensions.php';
                    include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/tools.php';
                    if ( !$this->pro_enabled ) {
                        include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/go-pro.php';
                    }
                    include_once EAEL_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'includes/templates/admin/integrations.php';
                    ?>
                </div>
            </form>
        </div>
        <?php
    }

    /**
     * Saving data with ajax request
     * @param
     * @return  array
     * @since 1.1.2
     */
    public function save_settings() {
        check_ajax_referer( 'essential-addons-elementor', 'security' );

        if ( !isset( $_POST[ 'fields' ] ) ) {
            return;
        }

        parse_str( $_POST[ 'fields' ], $settings );

        if ( !empty( $_POST[ 'is_login_register' ] ) ) {
            // Saving Login | Register Related Data
            if ( isset( $settings[ 'recaptchaSiteKey' ] ) ) {
                update_option( 'eael_recaptcha_sitekey', sanitize_text_field( $settings[ 'recaptchaSiteKey' ] ) );
            }
            if ( isset( $settings[ 'recaptchaSiteSecret' ] ) ) {
                update_option( 'eael_recaptcha_secret', sanitize_text_field( $settings[ 'recaptchaSiteSecret' ] ) );
            }

            //pro settings
            if ( isset( $settings[ 'gClientId' ] ) ) {
                update_option( 'eael_g_client_id', sanitize_text_field( $settings[ 'gClientId' ] ) );
            }
            if ( isset( $settings[ 'fbAppId' ] ) ) {
                update_option( 'eael_fb_app_id', sanitize_text_field( $settings[ 'fbAppId' ] ) );
            }
            if ( isset( $settings[ 'fbAppSecret' ] ) ) {
                update_option( 'eael_fb_app_secret', sanitize_text_field( $settings[ 'fbAppSecret' ] ) );
            }

            wp_send_json_success( [ 'message' => __( 'Login | Register Settings updated', 'essential-addons-for-elementor-lite' ) ] );
        }


        // Saving Google Map Api Key
        if ( isset( $settings[ 'google-map-api' ] ) ) {
            update_option( 'eael_save_google_map_api', sanitize_text_field( $settings[ 'google-map-api' ] ) );
        }

        // Saving Mailchimp Api Key
        if ( isset( $settings[ 'mailchimp-api' ] ) ) {
            update_option( 'eael_save_mailchimp_api', sanitize_text_field( $settings[ 'mailchimp-api' ] ) );
        }

        // Saving TYpeForm token
        if ( isset( $settings[ 'typeform-personal-token' ] ) ) {
            update_option( 'eael_save_typeform_personal_token', sanitize_text_field( $settings[ 'typeform-personal-token' ] ) );
        }

        // Saving Duplicator Settings
        if ( isset( $settings[ 'post-duplicator-post-type' ] ) ) {
            update_option( 'eael_save_post_duplicator_post_type', sanitize_text_field( $settings[ 'post-duplicator-post-type' ] ) );
        }

        // save js print method
        if ( isset( $settings[ 'eael-js-print-method' ] ) ) {
            update_option( 'eael_js_print_method', sanitize_text_field( $settings[ 'eael-js-print-method' ] ) );
        }

        $defaults = array_fill_keys( array_keys( array_merge( $this->registered_elements, $this->registered_extensions ) ), false );
        $elements = array_merge( $defaults, array_fill_keys( array_keys( array_intersect_key( $settings, $defaults ) ), true ) );

        // update new settings
        $updated = update_option( 'eael_save_settings', $elements );

        // clear assets files
        $this->empty_dir( EAEL_ASSET_PATH );

        wp_send_json( $updated );
    }

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
                    'link'       => 'https://wpdeveloper.net/review-essential-addons-elementor',
                    'target'     => '_blank',
                    'label'      => __( 'Ok, you deserve it!', 'essential-addons-for-elementor-lite' ),
                    'icon_class' => 'dashicons dashicons-external',
                ),
                'allready'         => array(
                    'link'       => $url,
                    'label'      => __( 'I already did', 'essential-addons-for-elementor-lite' ),
                    'icon_class' => 'dashicons dashicons-smiley',
                    'data_args'  => [
                        'dismiss' => true,
                    ],
                ),
                'maybe_later'      => array(
                    'link'       => $url,
                    'label'      => __( 'Maybe Later', 'essential-addons-for-elementor-lite' ),
                    'icon_class' => 'dashicons dashicons-calendar-alt',
                    'data_args'  => [
                        'later' => true,
                    ],
                ),
                'support'          => array(
                    'link'       => 'https://wpdeveloper.net/support',
                    'label'      => __( 'I need help', 'essential-addons-for-elementor-lite' ),
                    'icon_class' => 'dashicons dashicons-sos',
                ),
                'never_show_again' => array(
                    'link'       => $url,
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
        // $notice->classes('upsale', 'notice is-dismissible ');
        // $notice->message('upsale', '<p>' . __('5,000+ People using <a href="https://betterdocs.co/wordpress-plugin" target="_blank">BetterDocs</a> to create better Documentation & Knowledge Base!', 'essential-addons-for-elementor-lite') . '</p>');
        // $notice->thumbnail('upsale', plugins_url('assets/admin/images/icon-documentation.svg', EAEL_PLUGIN_BASENAME));

        // Update Notice For PRO Version
        if ( $this->pro_enabled && \version_compare( EAEL_PRO_PLUGIN_VERSION, '4.0.0', '<' ) ) {
            $notice->classes( 'update', 'notice is-dismissible ' );
            $notice->message( 'update', '<p>' . __( 'You are using an incompatible version of Essential Addons PRO. Please update to v4.0.0+. If you do not see automatic update, <a href="https://essential-addons.com/elementor/docs/manually-update-essential-addons-pro/" target="_blank">Follow manual update guide.</a>', 'essential-addons-for-elementor-lite' ) . '</p>' );
            $notice->thumbnail( 'update', plugins_url( 'assets/admin/images/icon-ea-logo.svg', EAEL_PLUGIN_BASENAME ) );
        }

        // $notice->upsale_args = array(
        //     'slug' => 'betterdocs',
        //     'page_slug' => 'betterdocs-setup',
        //     'file' => 'betterdocs.php',
        //     'btn_text' => __('Install Free', 'essential-addons-for-elementor-lite'),
        //     'condition' => [
        //         'by' => 'class',
        //         'class' => 'BetterDocs',
        //     ],
        // );
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

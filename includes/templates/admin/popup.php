<div id="eael-admn-setting-popup" class="eael-modal__wrap eael-modal-show">
    <div class="eael-modal__dialogue">
        <div class="eael-modal">
            <div class="modal__body">
                <a href="#" id="eael-admin-popup-close" class="modal__close eael-admin-popup-close"><i
                            class="ea-admin-icon icon-times"></i></a>
                <div id="eael-pro-popup" class="modal__content modal__content__popup">
                    <div class="modal__card">
                        <div class="icon">
                            <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/go-pro.svg' ); ?>"
                                 alt="">
                        </div>
                        <h3><?php _e( 'Go PRO', 'essential-addons-for-elementor-lite' ); ?></h3>
                        <p><?php _e( 'Unlock 30+ amazing widgets to build awesome websites.', 'essential-addons-for-elementor-lite' ); ?></p>
                        <a target="_blank" href="https://wpdeveloper.com/in/upgrade-essential-addons-elementor"
                           class="eael-button"><?php _e( 'Upgrade
                            Now', 'essential-addons-for-elementor-lite' ); ?></a>
                    </div>
                </div>

                <div id="eael-google-map-popup" class="modal__content modal__content__popup">
                    <div class="modal__card">
                        <div class="icon">
                            <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/map-marker.svg' ); ?>"
                                 alt="">
                        </div>
                        <h3><?php _e( 'Google Map API Key', 'essential-addons-for-elementor-lite' ); ?></h3>
                        <div class="modal__api__form">
                            <input name="google-map-api" id="google-map-api"
                                   value="<?php echo esc_attr( get_option( 'eael_save_google_map_api' ) ); ?>" type="text"
                                   class="eael-form__control" placeholder="Set API key">
                            <button type="button" class="eael-button button__themeColor eael-save-trigger eael-admin-popup-close"><i
                                        class="ea-admin-icon icon-long-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <div id="eael-business-reviews-popup" class="modal__content__popup">
                    <div class="modal__head">
                        <p><?php _e( 'Business Reviews Settings', 'essential-addons-for-elementor-lite' ); ?></p>
                    </div>

                    <div class="modal__content">
                        <div class="eael-business_reviews__setup">
                            <span class="business_reviews__setup__header">
                                <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/google.svg' ); ?>"
                                        alt="">
                                <?php _e( 'Google Reviews', 'essential-addons-for-elementor-lite' ); ?>
                            </span>
                            <div class="eael-input__inline">
                                <label><?php _e( 'Google Place API Key:', 'essential-addons-for-elementor-lite' ); ?></label>
                                <input name="br_google_place_api_key" id="br_google_place_api_key"
                                        value="<?php echo esc_attr( get_option( 'eael_br_google_place_api_key' ) ); ?>"
                                        class="eael-form__control" type="text" placeholder="Google Place API Key">
                            </div>
                        </div>
                    </div>

                    <div class="modal__content">
                        <div class="eael__flex align__center justify__center">
                            <button class="eael-button button__themeColor mr15 eael-save-trigger eael-admin-popup-close">
                                <?php _e( 'Save', 'essential-addons-for-elementor-lite' ); ?>
                            </button>
                            <button class="eael-button button__white eael-admin-popup-close"><?php _e( 'Cancel', 'essential-addons-for-elementor-lite' ); ?></button>
                        </div>
                        <div class="config__api mt15">
                            <a target="_blank" href="https://developers.google.com/maps/documentation/places/web-service/get-api-key"
                               class="config__api"> <?php _e( 'To configure the API Keys, check out this doc', 'essential-addons-for-elementor-lite' ); ?></a>
                        </div>
                    </div>
                </div>

                <div id="eael-mailchimp-popup" class="modal__content modal__content__popup">
                    <div class="modal__card">
                        <div class="icon">
                            <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/mailchimp.svg' ); ?>"
                                 alt="">
                        </div>
                        <h3><?php _e( 'Mailchimp API Key', 'essential-addons-for-elementor-lite' ); ?></h3>
                        <div class="modal__api__form">
                            <input name="mailchimp-api" id="mailchimp-api" type="text" class="eael-form__control"
                                   value="<?php echo esc_attr( get_option( 'eael_save_mailchimp_api' ) ); ?>"
                                   placeholder="Set API key">
                            <button class="eael-button button__themeColor eael-save-trigger eael-admin-popup-close"><i
                                        class="ea-admin-icon icon-long-arrow-right"></i></button>
                        </div>

                    </div>
                </div>
				<?php
				$eael_recaptcha_sitekey  = get_option( 'eael_recaptcha_sitekey' );
				$eael_recaptcha_sitekey_v3  = get_option( 'eael_recaptcha_sitekey_v3' );
				$eael_recaptcha_secret   = get_option( 'eael_recaptcha_secret' );
				$eael_recaptcha_secret_v3   = get_option( 'eael_recaptcha_secret_v3' );
				$eael_recaptcha_language = get_option( 'eael_recaptcha_language' );
				$eael_recaptcha_language_v3 = get_option( 'eael_recaptcha_language_v3' );
				$eael_g_client_id        = get_option( 'eael_g_client_id' );
				$eael_fb_app_id          = get_option( 'eael_fb_app_id' );
				$eael_fb_app_secret      = get_option( 'eael_fb_app_secret' );
				$eael_lr_mailchimp_api_key      = get_option( 'eael_lr_mailchimp_api_key' );
				?>
                <div id="eael-login-register-popup" class="modal__content__popup">
                    <div class="modal__head">
                        <p><?php _e( 'Login | Register Form Settings', 'essential-addons-for-elementor-lite' ); ?></p>
                    </div>
                    <div class="modal__content">
                        <div class="eael-login__setup">
                            <span class="login__setup__header">
                                <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/recaptcha.svg' ); ?>"
                                     alt="">
                                <?php _e( 'reCAPTCHA v2', 'essential-addons-for-elementor-lite' ); ?>
                            </span>
                            <div class="eael-input__inline mb15">
                                <label><?php _e( 'Site Key:', 'essential-addons-for-elementor-lite' ); ?></label>
                                <input name="lr_recaptcha_sitekey" id="lr_recaptcha_sitekey"
                                       value="<?php echo esc_attr( get_option( 'eael_recaptcha_sitekey' ) ); ?>"
                                       class="eael-form__control" type="text" placeholder="Site key">
                            </div>
                            <div class="eael-input__inline mb15">
                                <label><?php _e( 'Site Secret:', 'essential-addons-for-elementor-lite' ); ?></label>
                                <input name="lr_recaptcha_secret" id="lr_recaptcha_secret"
                                       value="<?php echo esc_attr( get_option( 'eael_recaptcha_secret' ) ); ?>"
                                       class="eael-form__control" type="text" placeholder="Site Secret">
                            </div>
                            <div class="eael-input__inline">
                                <label><?php _e( 'Language:', 'essential-addons-for-elementor-lite' ); ?></label>
                                <input name="lr_recaptcha_language" id="lr_recaptcha_language"
                                       value="<?php echo esc_attr( get_option( 'eael_recaptcha_language' ) ); ?>"
                                       class="eael-form__control" type="text" placeholder="reCAPTCHA Language Code">
                            </div>
                        </div>
                    </div>

                    <div class="border__line"><span></span></div>

                    <div class="modal__content">
                        <div class="eael-login__setup">
                            <span class="login__setup__header">
                                <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/recaptcha.svg' ); ?>"
                                     alt="">
                                <?php _e( 'reCAPTCHA v3', 'essential-addons-for-elementor-lite' ); ?>
                            </span>
                            <div class="eael-input__inline mb15">
                                <label><?php _e( 'Site Key:', 'essential-addons-for-elementor-lite' ); ?></label>
                                <input name="lr_recaptcha_sitekey_v3" id="lr_recaptcha_sitekey_v3"
                                       value="<?php echo esc_attr( get_option( 'eael_recaptcha_sitekey_v3' ) ); ?>"
                                       class="eael-form__control" type="text" placeholder="Site key">
                            </div>
                            <div class="eael-input__inline mb15">
                                <label><?php _e( 'Site Secret:', 'essential-addons-for-elementor-lite' ); ?></label>
                                <input name="lr_recaptcha_secret_v3" id="lr_recaptcha_secret_v3"
                                       value="<?php echo esc_attr( get_option( 'eael_recaptcha_secret_v3' ) ); ?>"
                                       class="eael-form__control" type="text" placeholder="Site Secret">
                            </div>
                            <div class="eael-input__inline">
                                <label><?php _e( 'Language:', 'essential-addons-for-elementor-lite' ); ?></label>
                                <input name="lr_recaptcha_language_v3" id="lr_recaptcha_language_v3"
                                       value="<?php echo esc_attr( get_option( 'eael_recaptcha_language_v3' ) ); ?>"
                                       class="eael-form__control" type="text" placeholder="reCAPTCHA Language Code">
                            </div>
                        </div>
                    </div>
					<?php if ( $this->pro_enabled ): ?>
                        <div class="border__line"><span></span></div>
                        <div class="modal__content">
                            <div class="eael-login__setup">
                                <span class="login__setup__header">
                                    <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/google.svg' ); ?>"
                                         alt="">
                                    <?php _e( 'Google Login', 'essential-addons-for-elementor-lite' ); ?>
                                </span>
                                <div class="eael-input__inline">
                                    <label><?php _e( 'Google Client ID:', 'essential-addons-for-elementor-lite' ); ?></label>
                                    <input name="lr_g_client_id" id="lr_g_client_id"
                                           value="<?php echo esc_attr( get_option( 'eael_g_client_id' ) ); ?>"
                                           class="eael-form__control" type="text" placeholder="Google Client ID">
                                </div>
                            </div>
                        </div>
                        <div class="border__line"><span></span></div>

                        <div class="modal__content">
                            <div class="eael-login__setup">
                                <span class="login__setup__header">
                                    <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/facebook.svg' ); ?>"
                                         alt="">
                                    <?php _e( 'Facebook Login', 'essential-addons-for-elementor-lite' ); ?>
                                </span>
                                <div class="eael-input__inline mb15">
                                    <label><?php _e( 'Facebook App ID:', 'essential-addons-for-elementor-lite' ); ?></label>
                                    <input name="lr_fb_app_id" id="lr_fb_app_id"
                                           value="<?php echo esc_attr( get_option( 'eael_fb_app_id' ) ); ?>"
                                           class="eael-form__control" type="text" placeholder="Facebook App ID:">
                                </div>
                                <div class="eael-input__inline">
                                    <label><?php _e( 'Facebook App Secret:', 'essential-addons-for-elementor-lite' ); ?></label>
                                    <input name="lr_fb_app_secret" id="lr_fb_app_secret"
                                           value="<?php echo esc_attr( get_option( 'eael_fb_app_secret' ) ); ?>"
                                           class="eael-form__control" type="text" placeholder="Facebook App Secret:">
                                </div>
                            </div>
                        </div>

                        <div class="border__line"><span></span></div>

                        <div class="modal__content">
                            <div class="eael-login__setup">
                                <span class="login__setup__header">
                                    <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/mailchimp.svg' ); ?>"
                                         alt=""  width="20">
                                    <?php _e( 'Mailchimp Integration', 'essential-addons-for-elementor-lite' ); ?>
                                </span>
                                <div class="eael-input__inline mb15">
                                    <label><?php _e( 'Mailchimp API Key:', 'essential-addons-for-elementor-lite' ); ?></label>
                                    <input name="lr_mailchimp_api_key" id="lr_mailchimp_api_key"
                                           value="<?php echo esc_attr( get_option( 'eael_lr_mailchimp_api_key' ) ); ?>"
                                           class="eael-form__control" type="text" placeholder="Mailchimp API">
                                </div>
                            </div>
                        </div>
					<?php endif; ?>

                    <div class="border__line"><span></span></div>

                    <div class="eael-element__wrap eael-d-block eael-element__wrap-popup">
                        <div class="eael-element__item">
                            <div class="element__content">
                                <h4><?php esc_html_e( 'Enable Custom Fields', 'essential-addons-for-elementor-lite' ); ?></h4>
                                <div class="element__options">
                                    <p class="element__icon">
                                        <i class="eicon-info login-register-info-icon"></i>
                                        <span class="tooltip-text"><?php esc_attr_e('Fields will be available on both the edit profile page and the EA Login | Register Form.', 'essential-addons-for-elementor-lite') ?></span>
                                    </p>
                                    <label class="eael-switch">
                                        <input name="lr_custom_profile_fields" id="lr_custom_profile_fields" <?php if( 'on' === get_option( 'eael_custom_profile_fields' ) ) : ?> checked <?php endif; ?> class="eael-form__control eael-elements-list" type="checkbox">  <span class="switch__box "></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- <div class="border__line"><span></span></div> -->
                    <div class="modal__content">
                        <div class="eael__flex align__center justify__center">
                            <button class="eael-button button__themeColor mr15 eael-save-trigger eael-admin-popup-close">
                                <?php _e( 'Save', 'essential-addons-for-elementor-lite' ); ?>
                            </button>
                            <button class="eael-button button__white eael-admin-popup-close"><?php _e( 'Cancel', 'essential-addons-for-elementor-lite' ); ?></button>
                        </div>
                        <div class="config__api mt15">
                            <a target="_blank" href="https://essential-addons.com/elementor/docs/social-login-recaptcha"
                               class="config__api"> <?php _e( 'To configure the API Keys, check out this doc', 'essential-addons-for-elementor-lite' ); ?></a>
                        </div>
                    </div>
                </div>


                <div id="eael-post-duplicator-popup" class="modal__content modal__content__popup">
                    <div class="modal__card">
                        <h3><?php _e( 'Select Post Types', 'essential-addons-for-elementor-lite' ); ?></h3>
                        <div class="eael-select-box mb30 mt30">
                            <select class="eael-post-duplicator-box" name="post-duplicator-post-type"
                                    id="post-duplicator-post-type">
                                <option value="all">All</option>
								<?php
								$post_lists = get_post_types( [ 'public' => true, 'show_in_nav_menus' => true ] );
								$post_name  = get_option( 'eael_save_post_duplicator_post_type' );
								foreach ( $post_lists as $key => $post_list ) {
									$selected = $post_name == $key ? 'selected' : '';
									printf( '<option value="%s" %s>%s</option>', $key, $selected, $post_list );
								}
								?>
                            </select>
                        </div>
                        <button class="eael-button button__themeColor eael-save-trigger eael-admin-popup-close"><?php _e('Submit','essential-addons-for-elementor-lite') ?></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

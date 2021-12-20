<div id="eael-admn-setting-popup" class="eael-modal__wrap show">
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
                        <h3>Go Premium</h3>
                        <p>Purchase our premium version to unlock these pro components.</p>
                        <a href="https://wpdeveloper.com/in/upgrade-essential-addons-elementor" class="eael-button">Upgrade
                            Now</a>
                    </div>
                </div>

                <div id="eael-google-map-popup" class="modal__content modal__content__popup">
                    <div class="modal__card">
                        <div class="icon">
                            <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/map-marker.svg' ); ?>"
                                 alt="">
                        </div>
                        <h3>Google Map API Key</h3>
                        <div class="modal__api__form">
                            <input name="google-map-api" id="google-map-api"
                                   value="<?php echo get_option( 'eael_save_google_map_api' ); ?>" type="text"
                                   class="eael-form__control" placeholder="Set API key">
                            <button class="eael-button button__themeColor eael-save-trigger eael-admin-popup-close"><i
                                        class="ea-admin-icon icon-long-arrow-right"></i></button>
                        </div>
                    </div>
                </div>

                <div id="eael-mailchimp-popup" class="modal__content modal__content__popup">
                    <div class="modal__card">
                        <div class="icon">
                            <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/mailchimp.svg' ); ?>"
                                 alt="">
                        </div>
                        <h3>Mailchimp API Key</h3>
                        <div class="modal__api__form">
                            <input name="mailchimp-api" id="mailchimp-api" type="password" class="eael-form__control"
                                   value="<?php echo get_option( 'eael_save_mailchimp_api' ); ?>"
                                   placeholder="Set API key">
                            <button class="eael-button button__themeColor eael-save-trigger eael-admin-popup-close"><i
                                        class="ea-admin-icon icon-long-arrow-right"></i></button>
                        </div>

                    </div>
                </div>
				<?php
				$eael_recaptcha_sitekey  = get_option( 'eael_recaptcha_sitekey' );
				$eael_recaptcha_secret   = get_option( 'eael_recaptcha_secret' );
				$eael_recaptcha_language = get_option( 'eael_recaptcha_language' );
				$eael_g_client_id        = get_option( 'eael_g_client_id' );
				$eael_fb_app_id          = get_option( 'eael_fb_app_id' );
				$eael_fb_app_secret      = get_option( 'eael_fb_app_secret' );
				?>
                <div id="eael-login-register-popup" class="modal__content__popup">
                    <div class="modal__head">
                        <p>Login | Register Form Settings</p>
                    </div>
                    <div class="modal__content">
                        <div class="eael-login__setup">
                            <span class="login__setup__header">
                                <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/recaptcha.svg' ); ?>"
                                     alt="">
                                reCAPTCHA v2
                            </span>
                            <div class="eael-input__inline mb15">
                                <label>Site Key:</label>
                                <input name="lr_recaptcha_sitekey" id="lr_recaptcha_sitekey"
                                       value="<?php echo esc_attr( get_option( 'eael_recaptcha_sitekey' ) ); ?>"
                                       class="eael-form__control" type="text" placeholder="Site key">
                            </div>
                            <div class="eael-input__inline mb15">
                                <label>Site Secret:</label>
                                <input name="lr_recaptcha_secret" id="lr_recaptcha_secret"
                                       value="<?php echo esc_attr( get_option( 'eael_recaptcha_secret' ) ); ?>"
                                       class="eael-form__control" type="text" placeholder="Site Secret">
                            </div>
                            <div class="eael-input__inline">
                                <label>Language:</label>
                                <input name="lr_recaptcha_language" id="lr_recaptcha_language"
                                       value="<?php echo esc_attr( get_option( 'eael_recaptcha_language' ) ); ?>"
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
                                    Google Login
                                </span>
                                <div class="eael-input__inline">
                                    <label>Google Client ID:</label>
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
                                    facebook Login
                                </span>
                                <div class="eael-input__inline mb15">
                                    <label>Facebook App ID:</label>
                                    <input name="lr_fb_app_id" id="lr_fb_app_id"
                                           value="<?php echo esc_attr( get_option( 'eael_fb_app_id' ) ); ?>"
                                           class="eael-form__control" type="text" placeholder="Facebook App ID:">
                                </div>
                                <div class="eael-input__inline">
                                    <label>Facebook App Secret:</label>
                                    <input name="lr_fb_app_secret" id="lr_fb_app_secret"
                                           value="<?php echo esc_attr( get_option( 'eael_fb_app_secret' ) ); ?>"
                                           class="eael-form__control" type="text" placeholder="Facebook App Secret:">
                                </div>
                            </div>
                        </div>
					<?php endif; ?>

                    <div class="border__line"><span></span></div>
                    <div class="modal__content">
                        <div class="eael__flex align__center justify__center">
                            <button class="eael-button button__themeColor mr15 eael-save-trigger eael-admin-popup-close">Save</button>
                            <button class="eael-button button__white eael-admin-popup-close">Cancel</button>
                        </div>
                        <div class="config__api mt15">
                            <a target="_blank" href="https://essential-addons.com/elementor/docs/social-login-recaptcha"
                               class="config__api">To configure the API Keys, check out this doc</a>
                        </div>
                    </div>
                </div>


                <div id="eael-post-duplicator-popup" class="modal__content modal__content__popup">
                    <div class="modal__card">
                        <h3>Select Post Types</h3>
                        <div class="eael-select-box mb30 mt30">
                            <select class="eael-post-duplicator-box" name="post-duplicator-post-type" id="post-duplicator-post-type">
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
                        <button class="eael-button button__themeColor eael-save-trigger">Submit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

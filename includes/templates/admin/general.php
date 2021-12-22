<div id="general" class="eael-admin-setting-tab active">
    <div class="eael-grid">
        <div class="eael-col-xl-8">
            <div class="eael-block px45 py25">
                <div class="eael-basic__card  align__center justify__between eael__flex justify__between">
                    <p><i class="ea-admin-icon icon-edit"></i>Check out the changes & features we have added with our
                        new updates
                    </p>
                    <a target="_blank" href="https://essential-addons.com/elementor/changelog/" class="eael-button">View Changelog</a>
                </div>
            </div>
			<?php do_action( 'add_admin_license_markup' ); ?>
			<?php if ( $this->installer->get_local_plugin_data( 'templately/templately.php' ) === false ) { ?>
                <div class="template__block eael-block p45">
                    <div class="template__logo">
                        <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-logo.svg' ); ?>"
                             alt="">
                    </div>
                    <h2>Unlock <span class="color__secondary">1600+</span> Ready Templates Built With Elementor &
                        Essential
                        Addons From Templately.</h2>
                    <p>Get Access to amazing features and boost your Elementor page building experience with Templately
                        👇</p>
                    <ul class="ls-none mb30">
                        <li>🌟 Access Thousands Of Stunning, Ready Website Templates</li>
                        <li>🔥 Save Your Design Anywhere With MyCloud Storage Space</li>
                        <li>🚀 Add Team Members & Collaborate On Cloud With Templately WorkSpace</li>
                    </ul>
                    <a href="#" class="eael-button button__secondary hover__shadow wpdeveloper-plugin-installer"
                       data-action="install" data-slug="templately">Install Templately</a>
                </div>
			<?php } else { ?>
                <div class="eael-block p45">
                    <div class="eael-grid">
                        <div class="eael-col-md-6">
                            <div class="template__block">
                                <h2>🚀 Get Started with Essential Addons for Elementor</h2>
                                <p>Thank you for choosing Essential Addons for Elementor. Get ready to enhance your
                                    Elementor site building experience by using 50+ Essential Addons elements for
                                    FREE.</p>
                                <a target="_blank" href="https://www.youtube.com/playlist?list=PLWHp1xKHCfxC7JeWSg31vtVbLHGzfxDvh"
                                   class="eael-button button__secondary hover__shadow">YouTube Tutorials</a>
                            </div>
                        </div>
                        <div class="eael-col-md-6">
                            <div class="eael-video__block">
                                <div class="thumb">
                                    <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/youtube-promo.png' ) ?>"
                                         alt="">
                                </div>
                                <a target="_blank" href="https://youtu.be/uuyXfUDqRZM" class="play__btn">
                                    <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/play-btn.png' ); ?>"
                                         alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

			<?php } ?>
            <div class="eael-block px45 py25">
                <div class="eael-basic__card align__center justify__between eael__flex justify__between">
                    <div class="eael__flex align__center">
                        <div class="mr20 fs-0 thumb">
                            <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/ea.svg' ) ?>"
                                 alt="">
                        </div>
                        <p>Manage your License for your sites from your WPDeveloper account</p>
                    </div>
					<?php
					if ( !defined( 'EAEL_PRO_PLUGIN_BASENAME' ) ) {
						printf( __( '<a target="_blank" class="eael-button button__themeColor" href="https://wpdeveloper.com/in/upgrade-essential-addons-elementor" >%s</a>', 'essential-addons-for-elementor-lite' ), 'Upgrade to Pro' );
					} else {
						do_action( 'eael_manage_license_action_link' );
					}
					?>
                </div>
            </div>
        </div>
        <div class="eael-col-xl-4">
            <div class="eael-statistic eael-block py25 px15">
                <div class="statistic__item">
                    <h2 id="eael-total-elements">88</h2>
                    <p>Total Elements</p>
                </div>
                <div class="statistic__item">
                    <h2 id="eael-used-elements">00</h2>
                    <p>Used Elements</p>
                </div>
                <div class="statistic__item">
                    <h2 id="eael-unused-elements">00</h2>
                    <p>Unused Elements</p>
                </div>
            </div>
            <a target="_blank" href="https://essential-addons.com/elementor/docs/"
               class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon icon-edit"></i> View Knowledgebase</h6>
                <p>Get started by spending some time with the documentation to get familiar with Essential Addons.</p>
            </a>
            <a target="_blank" href="https://wpdeveloper.com/review-essential-addons-elementor"
               class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon icon-star"></i> Show Your Love</h6>
                <p>Take your 2 minutes to review the plugin and spread the love to encourage us to keep it going.</p>
            </a>
            <a target="_blank" href="https://wpdeveloper.com/support" class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon icon-support"></i> Need Help?</h6>
                <p>Stuck with something? Get help from live chat or submit a support ticket.</p>
            </a>
            <a target="_blank" href="https://www.facebook.com/groups/essentialaddons"
               class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon icon-community"></i> Join the Community</h6>
                <p>Join the Facebook community and discuss with fellow developers & users.</p>
            </a>
        </div>
    </div>
    <div class="border__line mt30"><span></span></div>
</div>


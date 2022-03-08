<div id="general" class="eael-admin-setting-tab active">
    <div class="eael-grid">
        <div class="eael-col-xl-8">
            <div class="eael-block px45 py25">
                <div class="eael-basic__card  align__center justify__between eael__flex justify__between">
                    <p><i class="ea-admin-icon icon-edit"></i>
                        <?php _e('Check out the changes & features we have added with our
                        new updates','essential-addons-for-elementor-lite'); ?>
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
                    <h2>Unlock <span class="color__secondary">2000+</span> <?php _e('Ready Templates Built With Elementor &
                        Essential
                        Addons From Templately.','essential-addons-for-elementor-lite'); ?></h2>
                    <p><?php _e('Get Access to amazing features and boost your Elementor page building experience with Templately','essential-addons-for-elementor-lite'); ?>
                        👇</p>
                    <ul class="ls-none mb30">
                        <li>🌟 <?php _e('Access Thousands Of Stunning, Ready Website Templates','essential-addons-for-elementor-lite'); ?></li>
                        <li>🔥 <?php _e('Save Your Design Anywhere With MyCloud Storage Space','essential-addons-for-elementor-lite'); ?></li>
                        <li>🚀 <?php _e('Add Team Members & Collaborate On Cloud With Templately WorkSpace','essential-addons-for-elementor-lite'); ?></li>
                    </ul>
                    <a href="#" class="eael-button button__secondary hover__shadow wpdeveloper-plugin-installer"
                       data-action="install" data-slug="templately"><?php _e('Install Templately','essential-addons-for-elementor-lite'); ?></a>
                </div>
			<?php } else { ?>
                <div class="eael-block p45">
                    <div class="eael-grid">
                        <div class="eael-col-md-6">
                            <div class="template__block">
                                <h2>🚀 <?php _e('Get Started with Essential Addons for Elementor','essential-addons-for-elementor-lite'); ?></h2>
                                <p> <?php _e('Thank you for choosing Essential Addons for Elementor. Get ready to enhance your Elementor site building experience by using 80+ Essential Addons elements & extensions.','essential-addons-for-elementor-lite'); ?></p>
                                <a target="_blank" href="https://www.youtube.com/playlist?list=PLWHp1xKHCfxC7JeWSg31vtVbLHGzfxDvh"
                                   class="eael-button button__secondary hover__shadow"><?php _e('YouTube Tutorials','essential-addons-for-elementor-lite'); ?></a>
                            </div>
                        </div>
                        <div class="eael-col-md-6">
                            <div class="eael-video__block">
                                <div class="thumb">
                                    <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/youtube-promo.jpg' ) ?>"
                                         alt="">
                                </div>
                                <a target="_blank" href="https://www.youtube.com/watch?v=KIrB_-0gZXI" class="play__btn">
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
                        <p><?php _e('Manage your License for your sites from your WPDeveloper account','essential-addons-for-elementor-lite'); ?></p>
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
                    <p><?php _e('Total Elements','essential-addons-for-elementor-lite'); ?></p>
                </div>
                <div class="statistic__item">
                    <h2 id="eael-used-elements">00</h2>
                    <p><?php _e('Active Elements','essential-addons-for-elementor-lite'); ?></p>
                </div>
                <div class="statistic__item">
                    <h2 id="eael-unused-elements">00</h2>
                    <p><?php _e('Inactive Elements','essential-addons-for-elementor-lite'); ?></p>
                </div>
            </div>
            <a target="_blank" href="https://essential-addons.com/elementor/docs/"
               class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon icon-edit"></i> <?php _e('View Knowledgebase','essential-addons-for-elementor-lite'); ?></h6>
                <p><?php _e('Get started by spending some time with the documentation to get familiar with Essential Addons.','essential-addons-for-elementor-lite'); ?></p>
            </a>
            <a target="_blank" href="https://wpdeveloper.com/review-essential-addons-elementor"
               class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon icon-star"></i> <?php _e('Show Your Love','essential-addons-for-elementor-lite'); ?></h6>
                <p><?php _e('Take your 2 minutes to review the plugin and spread the love to encourage us to keep it going.','essential-addons-for-elementor-lite'); ?></p>
            </a>
            <a target="_blank" href="https://wpdeveloper.com/support" class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon icon-support"></i><?php _e(' Need Help?','essential-addons-for-elementor-lite'); ?></h6>
                <p><?php _e('Stuck with something? Get help from live chat or submit a support ticket.','essential-addons-for-elementor-lite'); ?></p>
            </a>
            <a target="_blank" href="https://www.facebook.com/groups/essentialaddons"
               class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon icon-community"></i><?php  _e('Join the Community','essential-addons-for-elementor-lite'); ?></h6>
                <p><?php _e('Join the Facebook community and discuss with fellow developers & users.','essential-addons-for-elementor-lite'); ?></p>
            </a>
        </div>
    </div>
    <div class="border__line mt30"><span></span></div>
</div>


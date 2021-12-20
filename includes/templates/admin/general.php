<div id="general" class="eael-admin-setting-tab active">
    <div class="eael-grid">
        <div class="eael-col-xl-8">
            <div class="eael-block px45 py25">
                <div class="eael-basic__card eael__flex eael__flex--wrap align__center justify__between">
                    <p><i class="ea-admin-icon icon-edit"></i>Check out the changes & features we have added with our
                        new updates
                    </p>
                    <a href="#" class="eael-button">View Changelog</a>
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
                                    Elementor site building experience by using 40+ Essential Addons elements for
                                    FREE.</p>
                                <a href="#" class="eael-button button__secondary hover__shadow">YouTube Tutorials</a>
                            </div>
                        </div>
                        <div class="eael-col-md-6">
                            <iframe src="https://www.youtube.com/embed/rc-5L7fek3M" title="YouTube video player"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
			<?php } ?>
            <div class="eael-block px45 py25">
                <div class="eael-basic__card eael__flex align__center justify__between">
                    <div class="eael__flex align__center">
                        <div class="mr20 fs-0">
                            <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/ea.svg' ) ?>"
                                 alt="">
                        </div>
                        <h4>Manage your License for your sites from your WPDeveloper account</h4>
                    </div>
					<?php
					if ( !defined( 'EAEL_PRO_PLUGIN_BASENAME' ) ) {
						printf( __( '<a class="eael-button button__themeColor" href="https://wpdeveloper.com/in/upgrade-essential-addons-elementor" target="_blank">%s</a>', 'essential-addons-for-elementor-lite' ), 'Upgrade to Pro' );
					} else {
						do_action( 'eael_manage_license_action_link' );
					}
					?>
                </div>
            </div>
        </div>
        <div class="eael-col-xl-4">
            <div class="eael-statistic eael-block py25 px30">
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
            <a href="" class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon icon-edit"></i> View Knowledgebase</h6>
                <p>Get started by spending some time with the documentation to get familiar with Essential Addons. Build
                    awesome websites</p>
            </a>
            <a href="" class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon icon-play-alt"></i> YouTube Tutorials</h6>
                <p>Get started by spending some time with the documentation to get familiar with Essential Addons. Build
                    awesome websites</p>
            </a>
            <a href="" class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon icon-star"></i> 5 Star Review</h6>
                <p>Get started by spending some time with the documentation to get familiar with Essential Addons. Build
                    awesome websites</p>
            </a>
            <a href="" class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon icon-support"></i> GitHub & Support section</h6>
                <p>Get started by spending some time with the documentation to get familiar with Essential Addons. Build
                    awesome websites</p>
            </a>
        </div>
    </div>
    <div class="border__line mt30"><span></span></div>
</div>


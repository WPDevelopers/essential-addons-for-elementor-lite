<div id="general" class="eael-admin-setting-tab active">
    <div class="eael-grid">
        <div class="eael-col-xl-8">
            <div class="eael-block px45 py25">
                <div class="eael-basic__card  align__center justify__between eael__flex justify__between">
                    <p><i class="ea-admin-icon eael-icon-edit"></i>
                        <?php _e('Check out the changes & features we have added with our
                        new updates','essential-addons-for-elementor-lite'); ?>
                    </p>
                    <a target="_blank" href="https://essential-addons.com/elementor/changelog/" class="eael-button">View Changelog</a>
                </div>
            </div>
			<?php do_action( 'add_admin_license_markup' ); ?>
			<?php if ( $this->installer->get_local_plugin_data( 'templately/templately.php' ) === false ) { ?>
				<div id="templately" class="template__block eael-block">
					<div>
						<div class="eael-quick-setup-title">
							<?php printf( __( '<span class="eael-quick-setup-highlighted-red">%s</span> %s', 'essential-addons-for-elementor-lite' ), '5000+', 'Ready Templates' ); ?>
						</div>
						<div class="eael-quick-setup-text">
							<?php _e( 'Unlock an extensive collection of ready WordPress templates, along with full site import & cloud collaboration features', 'essential-addons-for-elementor-lite' ); ?>
						</div>
						<ul class="eael-quick-setup-list">
							<li class="eael-quick-setup-list-item">
                    <span class="eael-quick-setup-icon">
						<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0.700012 1.60117C0.700012 1.36248 0.794833 1.13356 0.963616 0.964776C1.1324 0.795993 1.36132 0.701172 1.60001 0.701172H12.4C12.6387 0.701172 12.8676 0.795993 13.0364 0.964776C13.2052 1.13356 13.3 1.36248 13.3 1.60117V3.40117C13.3 3.63987 13.2052 3.86878 13.0364 4.03757C12.8676 4.20635 12.6387 4.30117 12.4 4.30117H1.60001C1.36132 4.30117 1.1324 4.20635 0.963616 4.03757C0.794833 3.86878 0.700012 3.63987 0.700012 3.40117V1.60117ZM0.700012 7.00117C0.700012 6.76248 0.794833 6.53356 0.963616 6.36478C1.1324 6.19599 1.36132 6.10117 1.60001 6.10117H7.00001C7.23871 6.10117 7.46763 6.19599 7.63641 6.36478C7.80519 6.53356 7.90001 6.76248 7.90001 7.00117V12.4012C7.90001 12.6399 7.80519 12.8688 7.63641 13.0376C7.46763 13.2064 7.23871 13.3012 7.00001 13.3012H1.60001C1.36132 13.3012 1.1324 13.2064 0.963616 13.0376C0.794833 12.8688 0.700012 12.6399 0.700012 12.4012V7.00117ZM10.6 6.10117C10.3613 6.10117 10.1324 6.19599 9.96362 6.36478C9.79483 6.53356 9.70001 6.76248 9.70001 7.00117V12.4012C9.70001 12.6399 9.79483 12.8688 9.96362 13.0376C10.1324 13.2064 10.3613 13.3012 10.6 13.3012H12.4C12.6387 13.3012 12.8676 13.2064 13.0364 13.0376C13.2052 12.8688 13.3 12.6399 13.3 12.4012V7.00117C13.3 6.76248 13.2052 6.53356 13.0364 6.36478C12.8676 6.19599 12.6387 6.10117 12.4 6.10117H10.6Z"
	  fill="url(#paint0_linear_810_832)"/>
<defs>
<linearGradient id="paint0_linear_810_832" x1="7.00001" y1="0.701172" x2="7.00001" y2="13.3012" gradientUnits="userSpaceOnUse">
<stop stop-color="#9373FF"/>
<stop offset="1" stop-color="#7650F6"/>
</linearGradient>
</defs>
</svg>
					</span>
								<?php _e( 'Stunning, Ready Website Templates', 'essential-addons-for-elementor-lite' ); ?>
							</li>
							<li class="eael-quick-setup-list-item">
                    <span class="eael-quick-setup-icon">
						<svg width="18" height="17" viewBox="0 0 18 17" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd"
	  d="M3 0.75C1.75736 0.75 0.75 1.75736 0.75 3V14.25C0.75 15.4927 1.75736 16.5 3 16.5H15C16.2427 16.5 17.25 15.4927 17.25 14.25V5.25C17.25 4.00736 16.2427 3 15 3H9L7.40901 1.40901C6.98705 0.987053 6.41476 0.75 5.81802 0.75H3ZM9 5.25C9.41422 5.25 9.75 5.58578 9.75 6V10.9394L10.7197 9.96968C11.0126 9.6768 11.4874 9.6768 11.7803 9.96968C12.0732 10.2626 12.0732 10.7374 11.7803 11.0303L9.53032 13.2803C9.23745 13.5732 8.76255 13.5732 8.46968 13.2803L6.21967 11.0303C5.92678 10.7374 5.92678 10.2626 6.21967 9.96968C6.51256 9.6768 6.98744 9.6768 7.28033 9.96968L8.25 10.9394V6C8.25 5.58578 8.58577 5.25 9 5.25Z"
	  fill="url(#paint0_linear_922_1148)"/>
<defs>
<linearGradient id="paint0_linear_922_1148" x1="9" y1="0.75" x2="9" y2="16.5" gradientUnits="userSpaceOnUse">
<stop stop-color="#FFCD91"/>
<stop offset="1" stop-color="#FAAD50"/>
</linearGradient>
</defs>
</svg>
					</span>
								<?php _e( 'One-Click Full Site Import', 'essential-addons-for-elementor-lite' ); ?>
							</li>
							<li class="eael-quick-setup-list-item">
                    <span class="eael-quick-setup-icon">
						<svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M0.5 16.5C0.5 13.1863 3.18629 10.5 6.5 10.5C9.81373 10.5 12.5 13.1863 12.5 16.5H0.5ZM6.5 9.75C4.01375 9.75 2 7.73625 2 5.25C2 2.76375 4.01375 0.75 6.5 0.75C8.98625 0.75 11 2.76375 11 5.25C11 7.73625 8.98625 9.75 6.5 9.75ZM12.0221 11.4249C14.3362 12.0163 16.0759 14.0426 16.2377 16.5H14C14 14.5427 13.2502 12.7604 12.0221 11.4249ZM10.5051 9.71767C11.7296 8.61915 12.5 7.02455 12.5 5.25C12.5 4.187 12.2235 3.18856 11.7387 2.32265C13.4565 2.66548 14.75 4.18099 14.75 6C14.75 8.07188 13.0719 9.75 11 9.75C10.8322 9.75 10.667 9.73897 10.5051 9.71767Z"
	  fill="url(#paint0_linear_810_846)"/>
<defs>
<linearGradient id="paint0_linear_810_846" x1="8.36885" y1="0.75" x2="8.36885" y2="16.5" gradientUnits="userSpaceOnUse">
<stop stop-color="#FFBAC4"/>
<stop offset="1" stop-color="#FF7B8E"/>
</linearGradient>
</defs>
</svg>
					</span>
								<?php _e( 'Add Team Members & Collaborate', 'essential-addons-for-elementor-lite' ); ?>
							</li>
							<li class="eael-quick-setup-list-item">
                    <span class="eael-quick-setup-icon">
						<svg width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M12.75 14.75H5.25C2.76472 14.75 0.75 12.7353 0.75 10.25C0.75 8.28845 2.0051 6.62 3.75603 6.00393C3.75202 5.91979 3.75 5.83513 3.75 5.75C3.75 2.85051 6.10051 0.5 9 0.5C11.8995 0.5 14.25 2.85051 14.25 5.75C14.25 5.83513 14.248 5.91979 14.244 6.00393C15.9949 6.62 17.25 8.28845 17.25 10.25C17.25 12.7353 15.2353 14.75 12.75 14.75Z"
	  fill="url(#paint0_linear_810_854)"/>
<defs>
<linearGradient id="paint0_linear_810_854" x1="9" y1="0.5" x2="9" y2="14.75" gradientUnits="userSpaceOnUse">
<stop stop-color="#6CC7FF"/>
<stop offset="1" stop-color="#2FA7F1"/>
</linearGradient>
</defs>
</svg>
					</span>
								<?php _e( 'Templates Cloud with Workspace', 'essential-addons-for-elementor-lite' ); ?>
							</li>
						</ul>
						<button class="button wpdeveloper-plugin-installer eael-dashboard-templately-install-btn" data-action="install" data-slug="templately"><?php _e('Enable Templates','essential-addons-for-elementor-lite'); ?></button>
					</div><img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/quick-setup/templately-qs-img.png' ) ?>" alt="">
				</div>
			<?php } else { ?>
                <div class="eael-block p45">
                    <div class="eael-grid">
                        <div class="eael-col-md-6">
                            <div class="template__block">
                                <h2>🚀 <?php _e('Get Started with Essential Addons for Elementor','essential-addons-for-elementor-lite'); ?></h2>
                                <p> <?php _e('Thank you for choosing Essential Addons for Elementor. Get ready to enhance your Elementor site building experience by using 90+ Essential Addons elements & extensions.','essential-addons-for-elementor-lite'); ?></p>
                                <a target="_blank" href="https://www.youtube.com/playlist?list=PLWHp1xKHCfxC7JeWSg31vtVbLHGzfxDvh"
                                   class="eael-button button__secondary hover__shadow"><?php _e('YouTube Tutorials','essential-addons-for-elementor-lite'); ?></a>
                            </div>
                        </div>
                        <div class="eael-col-md-6">
                            <div class="eael-video__block">
                                <div class="thumb">
                                    <img src="<?php echo esc_url( EAEL_PLUGIN_URL . 'assets/admin/images/youtube-promo.png' ) ?>"
                                         alt="">
                                </div>
                                <a target="_blank" href="https://www.youtube.com/watch?v=ZISSbnHo0rE" class="play__btn">
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
                <h6><i class="ea-admin-icon eael-icon-edit"></i> <?php _e('View Knowledgebase','essential-addons-for-elementor-lite'); ?></h6>
                <p><?php _e('Get started by spending some time with the documentation to get familiar with Essential Addons.','essential-addons-for-elementor-lite'); ?></p>
            </a>
            <a target="_blank" href="https://wpdeveloper.com/review-essential-addons-elementor"
               class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon eael-icon-star"></i> <?php _e('Show Your Love','essential-addons-for-elementor-lite'); ?></h6>
                <p><?php _e('Take your 2 minutes to review the plugin and spread the love to encourage us to keep it going.','essential-addons-for-elementor-lite'); ?></p>
            </a>
            <a target="_blank" href="https://wpdeveloper.com/support" class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon eael-icon-support"></i><?php _e(' Need Help?','essential-addons-for-elementor-lite'); ?></h6>
                <p><?php _e('Stuck with something? Get help from live chat or submit a support ticket.','essential-addons-for-elementor-lite'); ?></p>
            </a>
            <a target="_blank" href="https://www.facebook.com/groups/essentialaddons"
               class="eael-info__box eael-block px30 py25">
                <h6><i class="ea-admin-icon eael-icon-community"></i><?php  _e('Join the Community','essential-addons-for-elementor-lite'); ?></h6>
                <p><?php _e('Join the Facebook community and discuss with fellow developers & users.','essential-addons-for-elementor-lite'); ?></p>
            </a>
        </div>
    </div>
    <div class="border__line mt30"><span></span></div>
</div>


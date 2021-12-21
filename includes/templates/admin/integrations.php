<?php
$integrations = [
	[
		'slug'     => 'betterdocs',
		'basename' => 'betterdocs/betterdocs.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/bd.svg',
		'title'    => __( 'BetterDocs', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'BetterDocs will help you to create & organize your documentation page in a beautiful way that will make your visitors find any help article easily', 'essential-addons-for-elementor-lite' ),
	],
	[
		'slug'     => 'embedpress',
		'basename' => 'embedpress/embedpress.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/ep.svg',
		'title'    => __( 'EmbedPress', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'EmbedPress lets you embed videos, images, posts, audio, maps and upload PDF, DOC, PPT & all other types of content into your WordPress site. ', 'essential-addons-for-elementor-lite' ),
	],
	[
		'slug'     => 'reviewx',
		'basename' => 'reviewx/reviewx.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/rx.svg',
		'title'    => __( 'ReviewX', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'ReviewX lets you get instant customer ratings and multi criteria reviews to add credibility to your WooCommerce Store and increase conversion rates.', 'essential-addons-for-elementor-lite' ),
	],
	[
		'slug'     => 'notificationx',
		'basename' => 'notificationx/notificationx.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/nx.svg',
		'title'    => __( 'NotificationX', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'Best FOMO Social Proof Plugin to boost your sales conversion. Create stunning Sales Popup & Notification Bar With Elementor Support', 'essential-addons-for-elementor-lite' ),
	],
	[
		'slug'     => 'easyjobs',
		'basename' => 'easyjobs/easyjobs.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/ej.svg',
		'title'    => __( 'EasyJobs', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'Easy solution for the job recruitment to attract, manage & hire right talent faster. The Best Talent Recruitment Suite which lets you manage jobs & career page in Elementor.', 'essential-addons-for-elementor-lite' ),
	],
	[
		'slug'     => 'crowdfundly',
		'basename' => 'crowdfundly/crowdfundly.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/cf.svg',
		'title'    => __( 'Crowdfundly', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'Crowdfundly is a Software as a Service (SaaS) digital crowdfunding solution. Best fundraising solution in WordPress with Elementor & WooCommerce support.', 'essential-addons-for-elementor-lite' ),
	],
];
?>

<div id="integrations" class="eael-admin-setting-tab">
    <div class="eael-grid">
		<?php foreach ( $integrations as $plugin ): ?>
            <div class="eael-col-xxl-3 eael-col-xl-4">
                <div class="pt60 h-100">
                    <div class="eael-integration__card eael-integration__card--classic">
                        <div class="icon">
                            <img src="<?php echo esc_url( $plugin[ 'logo' ] ); ?>" alt="">
                        </div>
                        <h3><?php echo esc_html( $plugin[ 'title' ] ); ?></h3>
                        <p><?php echo esc_html( $plugin[ 'desc' ] ); ?></p>
						<?php if ( $this->installer->get_local_plugin_data( $plugin[ 'basename' ] ) === false ) { ?>
                            <a class="eael-button button__themeColor hover__shadow wpdeveloper-plugin-installer"
                               data-action="install"
                               data-slug="<?php echo $plugin[ 'slug' ]; ?>"><?php _e( 'Install', 'essential-addons-for-elementor-lite' ); ?></a>
						<?php } else { ?>
							<?php if ( is_plugin_active( $plugin[ 'basename' ] ) ) { ?>
                                <a class="eael-button button__white button__white-not-hover wpdeveloper-plugin-installer"><?php _e( 'Activated', 'essential-addons-for-elementor-lite' ); ?></a>
							<?php } else { ?>
                                <a class="eael-button button__themeColor hover__shadow wpdeveloper-plugin-installer"
                                   data-action="activate"
                                   data-basename="<?php echo $plugin[ 'basename' ]; ?>"><?php _e( 'Activate', 'essential-addons-for-elementor-lite' ); ?></a>
							<?php } ?>
						<?php } ?>
                    </div>
                </div>
            </div>
		<?php endforeach; ?>
    </div>
    <div class="border__line mt30"><span></span></div>
</div>


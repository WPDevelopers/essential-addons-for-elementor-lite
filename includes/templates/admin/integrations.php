<?php
$integrations = [
	[
		'slug'     => 'betterdocs',
		'basename' => 'betterdocs/betterdocs.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/bd-new.svg',
		'title'    => __( 'BetterDocs', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'BetterDocs will help you to create & organize your documentation page in a beautiful way that will make your visitors find any help article easily.', 'essential-addons-for-elementor-lite' ),
	],
	[
		'slug'     => 'embedpress',
		'basename' => 'embedpress/embedpress.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/ep.svg',
		'title'    => __( 'EmbedPress', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'EmbedPress lets you embed videos, images, posts, audio, maps and upload PDF, DOC, PPT & all other types of content into your WordPress site. ', 'essential-addons-for-elementor-lite' ),
	],
	[
		'slug'     => 'notificationx',
		'basename' => 'notificationx/notificationx.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/nx.svg',
		'title'    => __( 'NotificationX', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'Best FOMO Social Proof Plugin to boost your sales conversion. Create stunning Sales Popup & Notification Bar With Elementor Support.', 'essential-addons-for-elementor-lite' ),
	],
	[
		'slug'     => 'easyjobs',
		'basename' => 'easyjobs/easyjobs.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/ej.svg',
		'title'    => __( 'easy.jobs', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'Easy solution for the job recruitment to attract, manage & hire right talent faster. The Best Talent Recruitment Suite which lets you manage jobs & career page in Elementor.', 'essential-addons-for-elementor-lite' ),
	],
	[
		'slug'     => 'wp-scheduled-posts',
		'basename' => 'wp-scheduled-posts/wp-scheduled-posts.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/wscp.svg',
		'title'    => __( 'SchedulePress', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'Best Content Marketing Tool For WordPress – Schedule, Organize, & Auto Share Blog Posts. Take a quick glance at your content planning with Schedule Calendar, Auto & Manual Scheduler and  more.', 'essential-addons-for-elementor-lite' ),
	],
	[
		'slug'     => 'betterlinks',
		'basename' => 'betterlinks/betterlinks.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/btl.svg',
		'title'    => __( 'BetterLinks', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'Best Link Shortening tool to create, shorten and manage any URL to help you cross-promote your brands & products. Gather analytics reports, run successfully marketing campaigns easily & many more.', 'essential-addons-for-elementor-lite' ),
	],
	[
		'slug'     => 'essential-blocks',
		'basename' => 'essential-blocks/essential-blocks.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/eb-new.svg',
		'title'    => __( 'Essential Blocks', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'Enhance your Gutenberg experience with 40+ unique blocks (more coming soon). Add power to the block editor using our easy-to-use blocks which are designed to make your next WordPress page or posts design easier and prettier than ever before.', 'essential-addons-for-elementor-lite' ),
	],
	[
		'slug'     => 'better-payment',
		'basename' => 'better-payment/better-payment.php',
		'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/bp.svg',
		'title'    => __( 'Better Payment', 'essential-addons-for-elementor-lite' ),
		'desc'     => __( 'Better Payment streamlines transactions in Elementor, integrating PayPal, Stripe, advanced analytics, validation, and Elementor forms for the most secure & efficient payments.', 'essential-addons-for-elementor-lite' ),
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
                               data-slug="<?php echo esc_attr( $plugin[ 'slug' ] ); ?>"><?php _e( 'Install', 'essential-addons-for-elementor-lite' ); ?></a>
						<?php } else { ?>
							<?php if ( is_plugin_active( $plugin[ 'basename' ] ) ) { ?>
                                <a class="eael-button button__white button__white-not-hover wpdeveloper-plugin-installer"><?php _e( 'Activated', 'essential-addons-for-elementor-lite' ); ?></a>
							<?php } else { ?>
                                <a class="eael-button button__themeColor hover__shadow wpdeveloper-plugin-installer"
                                   data-action="activate"
                                   data-basename="<?php echo esc_attr( $plugin[ 'basename' ] ); ?>"><?php _e( 'Activate', 'essential-addons-for-elementor-lite' ); ?></a>
							<?php } ?>
						<?php } ?>
                    </div>
                </div>
            </div>
		<?php endforeach; ?>
    </div>
    <div class="border__line mt30"><span></span></div>
</div>


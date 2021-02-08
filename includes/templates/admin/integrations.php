<?php
$integrations = [
    [
        'slug'     => 'betterdocs',
        'basename' => 'betterdocs/betterdocs.php',
        'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/bd-logo.png',
        'title'    => __('BetterDocs','essential-addons-for-elementor-lite'),
        'desc'     => __('BetterDocs will help you to create & organize your documentation page in a beautiful way that will make your visitors find any help article easily','essential-addons-for-elementor-lite'),
    ],
    [
        'slug'     => 'embedpress',
        'basename' => 'embedpress/embedpress.php',
        'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/ep-logo.png',
        'title'    => __('EmbedPress','essential-addons-for-elementor-lite'),
        'desc'     => __('EmbedPress lets you embed videos, images, posts, audio, maps and upload PDF, DOC, PPT & all other types of content into your WordPress site. ','essential-addons-for-elementor-lite'),
    ],
    [
        'slug'     => 'reviewx',
        'basename' => 'reviewx/reviewx.php',
        'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/review-logo.gif',
        'title'    => __('ReviewX','essential-addons-for-elementor-lite'),
        'desc'     => __('ReviewX let’s you get instant customer rating and multicriteria reviews to add credibility to your WooCommerce Store and increase conversion rates.','essential-addons-for-elementor-lite'),
    ],
    [
        'slug'     => 'notificationx',
        'basename' => 'notificationx/notificationx.php',
        'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
        'title'    => __('NotificationX','essential-addons-for-elementor-lite'),
        'desc'     => __('Best FOMO Social Proof Plugin to boost your sales conversion. Create stunning Sales Popup & Notification Bar With Elementor Support','essential-addons-for-elementor-lite'),
    ],
    [
        'slug'     => 'easyjobs',
        'basename' => 'easyjobs/easyjobs.php',
        'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/easy-jobs-logo.png',
        'title'    => __( 'EasyJobs', 'essential-addons-for-elementor-lite' ),
        'desc'     => __( 'Easy solution for the job recruitment to attract, manage & hire right talent faster. The Best Talent Recruitment Suite which lets you manage jobs & career page in Elementor.', 'essential-addons-for-elementor-lite' ),
    ]
];
?>

<div id="integrations" class="eael-settings-tab">
    <div class="row">
        <?php foreach ($integrations as $plugin) {?>
            <div class="col-one-fourth">
                <div class="eael-admin-block-wrapper">
                    <div class="eael-admin-block eael-admin-block-integrations">
                        <div class="eael-admin-block-content">
                            <div class="eael-admin-block-integrations-logo">
                                <img src="<?php echo $plugin['logo']; ?>" alt="logo" />
                            </div>
                            <h2 class="eael-admin-block-integrations-title"><?php echo $plugin['title']; ?></h2>
                            <p class="eael-admin-block-integrations-text"><?php echo $plugin['desc']; ?></p>
                            <div class="eael-admin-block-integrations-btn-wrap">
                                <?php if ($this->installer->get_local_plugin_data($plugin['basename']) === false) {?>
                                    <a class="ea-button wpdeveloper-plugin-installer" data-action="install" data-slug="<?php echo $plugin['slug']; ?>"><?php _e('Install', 'essential-addons-for-elementor-lite');?></a>
                                <?php } else {?>
                                    <?php if (is_plugin_active($plugin['basename'])) {?>
                                        <a class="ea-button wpdeveloper-plugin-installer"><?php _e('Activated', 'essential-addons-for-elementor-lite');?></a>
                                    <?php } else {?>
                                        <a class="ea-button wpdeveloper-plugin-installer" data-action="activate" data-basename="<?php echo $plugin['basename']; ?>"><?php _e('Activate', 'essential-addons-for-elementor-lite');?></a>
                                    <?php }?>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }?>
    </div>
</div>

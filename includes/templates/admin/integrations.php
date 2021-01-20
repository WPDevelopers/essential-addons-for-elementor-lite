<?php
$integrations = [
    [
        'slug'     => 'notificationx',
        'basename' => 'notificationx/notificationx.php',
        'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
        'title'    => 'NotificationX',
        'desc'     => 'Let the visitors know about your special offers, deals, announcement.',
    ],
    [
        'slug'     => 'betterdocs',
        'basename' => 'betterdocs/betterdocs.php',
        'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/bd-logo.png',
        'title'    => 'BetterDocs',
        'desc'     => 'Let the visitors know about your special offers, deals, announcement.',
    ],
    [
        'slug'     => 'embedpress',
        'basename' => 'embedpress/embedpress.php',
        'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/ep-logo.png',
        'title'    => 'EmbedPress',
        'desc'     => 'Let the visitors know about your special offers, deals, announcement.',
    ],
    [
        'slug'     => 'reviewx',
        'basename' => 'reviewx/reviewx.php',
        'logo'     => EAEL_PLUGIN_URL . 'assets/admin/images/review-logo.gif',
        'title'    => 'ReviewX',
        'desc'     => 'Let the visitors know about your special offers, deals, announcement.',
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

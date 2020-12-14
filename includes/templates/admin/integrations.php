<?php
$integrations = [
    [
        'slug' => 'notificationx',
        'basename' => 'notificationx/notificationx.php',
        'logo' => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
        'title' => 'NotificationX',
        'desc' => 'Let the visitors know about your special offers, deals, announcement.',
    ],
    [
        'slug' => 'notificationx',
        'basename' => 'notificationx/notificationx.php',
        'logo' => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
        'title' => 'NotificationX',
        'desc' => 'Let the visitors know about your special offers, deals, announcement.',
    ],
    [
        'slug' => 'notificationx',
        'basename' => 'notificationx/notificationx.php',
        'logo' => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
        'title' => 'NotificationX',
        'desc' => 'Let the visitors know about your special offers, deals, announcement.',
    ],
    [
        'slug' => 'notificationx',
        'basename' => 'notificationx/notificationx.php',
        'logo' => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
        'title' => 'NotificationX',
        'desc' => 'Let the visitors know about your special offers, deals, announcement.',
    ],
    [
        'slug' => 'notificationx',
        'basename' => 'notificationx/notificationx.php',
        'logo' => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
        'title' => 'NotificationX',
        'desc' => 'Let the visitors know about your special offers, deals, announcement.',
    ],
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
                                    <a href="#" class="ea-button ea-button-install-plugin" data-slug="<?php echo $plugin['slug']; ?>"><?php _e('Install Plugin', 'essential-addons-for-elementor-lite');?></a>
                                <?php } else {?>
                                    <a href="#" class="ea-button"><?php _e('Plugin Installed', 'essential-addons-for-elementor-lite');?></a>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }?>
    </div>
</div>

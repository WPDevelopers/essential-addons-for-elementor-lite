<?php
$integrations = [
    [
        'slug' => 'notificationx',
        'logo' => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
        'title' => 'NotificationX',
        'desc' => 'Let the visitors know about your special offers, deals, announcement.',
    ],
    [
        'slug' => 'notificationx',
        'logo' => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
        'title' => 'NotificationX',
        'desc' => 'Let the visitors know about your special offers, deals, announcement.',
    ],
    [
        'slug' => 'notificationx',
        'logo' => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
        'title' => 'NotificationX',
        'desc' => 'Let the visitors know about your special offers, deals, announcement.',
    ],
    [
        'slug' => 'notificationx',
        'logo' => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
        'title' => 'NotificationX',
        'desc' => 'Let the visitors know about your special offers, deals, announcement.',
    ],
    [
        'slug' => 'notificationx',
        'logo' => EAEL_PLUGIN_URL . 'assets/admin/images/nx-logo.png',
        'title' => 'NotificationX',
        'desc' => 'Let the visitors know about your special offers, deals, announcement.',
    ],
];
?>

<div id="integrations" class="eael-settings-tab">
    <div class="row">
        <?php foreach ($integrations as $integration) {?>
            <div class="col-one-fourth">
                <div class="eael-admin-block-wrapper">
                    <div class="eael-admin-block eael-admin-block-integrations">
                        <div class="eael-admin-block-content">
                            <div class="eael-admin-block-integrations-logo">
                                <img src="<?php echo $integration['logo']; ?>" alt="logo" />
                            </div>
                            <h2 class="eael-admin-block-integrations-title"><?php echo $integration['title']; ?></h2>
                            <p class="eael-admin-block-integrations-text"><?php echo $integration['desc']; ?></p>
                            <div class="eael-admin-block-integrations-btn-wrap">
                                <a href="#" class="ea-button eael-admin-block-integrations-btn"><?php _e('Install Plugin', 'essential-addons-for-elementor-lite');?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }?>
    </div>
</div>

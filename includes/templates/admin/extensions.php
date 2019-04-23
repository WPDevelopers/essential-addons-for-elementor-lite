<?php
    $extensions = [
        'eael-pro-extensions'   => [
            'title'      => __( 'Premium Extensions', 'essential-addons-elementor' ),
            'extensions' => [
                [
                    'key'    => 'section-parallax',
                    'title'  => __( 'Parallax Scrolling', 'essential-addons-elementor' ),
                    'is_pro' => true
                ],
                [
                    'key'    => 'section-particles',
                    'title'  => __( 'Particles', 'essential-addons-elementor' ),
                    'is_pro' => true
                ],
                [
                    'key'    => 'eael-tooltip-section',
                    'title'  => __( 'Advance Tooltip', 'essential-addons-elementor' ),
                    'is_pro' => true
                ]
            ]
        ]
    ];

?>

<div id="extensions" class="eael-settings-tab eael-extensions-list">
    <div class="row">
        <div class="col-full">
            
            <?php
                foreach($extensions as $extension) :
            ?>
                <h3><?php echo $extension['title']; ?></h3>
                <div class="eael-checkbox-container">
                    <?php
                        foreach($extension['extensions'] as $item) :
                            $status = isset($item['is_pro']) && ! defined('EAEL_PRO_PLUGIN_BASENAME') ? 'disabled' : checked( 1, $this->get_settings($item['key']), false );
                            $label_class = isset($item['is_pro']) && ! defined('EAEL_PRO_PLUGIN_BASENAME') ? 'eael-get-pro' : '';
                    ?>
                    <div class="eael-checkbox">
                        <input type="checkbox" id="<?php echo esc_attr($item['key']); ?>" name="<?php echo esc_attr($item['key']); ?>" <?php echo $status; ?>>
                        <label for="<?php echo esc_attr($item['key']); ?>" class="<?php echo $label_class; ?>"></label>
                        <p class="eael-el-title">
                            <?php _e( $item['title'], 'essential-addons-elementor' ) ?>
                            <?php echo isset( $item['is_pro'] ) && ! defined('EAEL_PRO_PLUGIN_BASENAME') ? '<sup class="pro-label">Pro</sup>' : ''; ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <div class="eael-save-btn-wrap">
                <button type="submit" class="button eael-btn js-eael-settings-save"><?php _e('Save settings', 'essential-addons-elementor'); ?></button>
            </div>
        </div>
    </div>
</div>
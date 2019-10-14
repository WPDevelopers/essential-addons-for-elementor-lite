<?php

$extensions = [
    'eael-pro-extensions'   => [
        'title'      => __( 'Premium Extensions', 'essential-addons-elementor' ),
        'extensions' => [
            [
                'key'    => 'section-parallax',
                'title'  => __( 'Parallax', 'essential-addons-elementor' ),
                'demo_link' => 'https://essential-addons.com/elementor/parallax-scrolling/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/ea-parallax/',
                'is_pro' => true
            ],
            [
                'key'    => 'section-particles',
                'title'  => __( 'Particles', 'essential-addons-elementor' ),
                'demo_link' => 'https://essential-addons.com/elementor/particle-effect/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/particles/',
                'is_pro' => true
            ],
            [
                'key'    => 'eael-tooltip-section',
                'title'  => __( 'Advanced Tooltip', 'essential-addons-elementor' ),
                'demo_link' => 'https://essential-addons.com/elementor/advanced-tooltip/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/ea-advanced-tooltip/',
                'is_pro' => true
            ],
            [
                'key'    => 'eael-content-protection',
                'title'  => __( 'Content Protection', 'essential-addons-elementor' ),
                'demo_link' => 'https://essential-addons.com/elementor/content-protection/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/ea-content-protection/',
                'is_pro' => true
            ],
            [
                'key'    => 'eael-reading-progress',
                'title'  => __( 'Reading Progress Bar', 'essential-addons-elementor' ),
                'demo_link' => 'https://essential-addons.com/elementor/reading-progress/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/ea-reading-progress-bar/',
            ],
            [
                'key'    => 'eael-post-duplicator',
                'title'  => __( 'Duplicator', 'essential-addons-elementor' ),
                'demo_link' => 'https://essential-addons.com/elementor/duplicator/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/duplicator/',
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
                            $status = isset($item['is_pro']) && !$this->pro_enabled ? 'disabled' : checked( 1, $this->get_settings($item['key']), false );
                            $label_class = isset($item['is_pro']) && !$this->pro_enabled ? 'eael-get-pro' : '';
                    ?>
                    <div class="eael-checkbox">
                        <div class="eael-elements-info">
                            <p class="eael-el-title">
                                <?php _e( $item['title'], 'essential-addons-elementor' ) ?>
                                <?php echo isset( $item['is_pro'] ) && !$this->pro_enabled ? '<sup class="pro-label">'.__('Pro', 'essential-addons-elementor').'</sup>' : ''; ?>
                                <?php if ($item['key'] === 'eael-post-duplicator') {
                                    echo '<span style="font-size: 12px; font-style:italic;"><a href="#" class="eael-admin-settings-popup" data-title="Select Post Types" data-option="select" data-options=' . json_encode(get_post_types(['public' => true, 'show_in_nav_menus' => true])) . ' data-target="#post-duplicator-post-type">'.__('Settings', 'essential-addons-elementor').'</a></span>
                                    <input type="hidden" name="post-duplicator-post-type" id="post-duplicator-post-type" class="post-duplicator-post-type" value="'.get_option('eael_save_post_duplicator_post_type').'">';
                                } ?>
                            </p>
                            <a  class="eael-element-info-link" href="<?php echo ($item['demo_link']);?>" target="_blank">
                                <span class="dashicons dashicons-welcome-view-site"></span>
                                <span class="eael-info-tooltip"><?php _e('Demo', 'essential-addons-elementor'); ?></span>
                            </a>
                            <a class="eael-element-info-link" href="<?php echo ($item['doc_link']);?>" target="_blank">
                                <span class="dashicons dashicons-editor-help"></span>
                                <span class="eael-info-tooltip"><?php _e('Documentation', 'essential-addons-elementor'); ?></span>
                            </a>
                        </div>
                        <input type="checkbox" id="<?php echo esc_attr($item['key']); ?>" name="<?php echo esc_attr($item['key']); ?>" <?php echo $status; ?>>
                        <label for="<?php echo esc_attr($item['key']); ?>" class="<?php echo $label_class; ?>"></label>
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
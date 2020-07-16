<?php

$extensions = [
    'eael-pro-extensions'   => [
        'title'      => __( 'Premium Extensions', 'essential-addons-for-elementor-lite'),
        'extensions' => [
            [
                'key'    => 'section-parallax',
                'title'  => __( 'Parallax', 'essential-addons-for-elementor-lite'),
                'demo_link' => 'https://essential-addons.com/elementor/parallax-scrolling/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/ea-parallax/',
                'is_pro' => true
            ],
            [
                'key'    => 'section-particles',
                'title'  => __( 'Particles', 'essential-addons-for-elementor-lite'),
                'demo_link' => 'https://essential-addons.com/elementor/particle-effect/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/particles/',
                'is_pro' => true
            ],
            [
                'key'    => 'eael-tooltip-section',
                'title'  => __( 'Advanced Tooltip', 'essential-addons-for-elementor-lite'),
                'demo_link' => 'https://essential-addons.com/elementor/advanced-tooltip/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/ea-advanced-tooltip/',
                'is_pro' => true
            ],
            [
                'key'    => 'eael-content-protection',
                'title'  => __( 'Content Protection', 'essential-addons-for-elementor-lite'),
                'demo_link' => 'https://essential-addons.com/elementor/content-protection/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/ea-content-protection/',
                'is_pro' => true
            ],
            [
                'key'    => 'eael-reading-progress',
                'title'  => __( 'Reading Progress Bar', 'essential-addons-for-elementor-lite'),
                'demo_link' => 'https://essential-addons.com/elementor/reading-progress/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/ea-reading-progress-bar/',
            ],
            [
                'key'    => 'eael-table-of-content',
                'title'  => __( 'Table of Contents', 'essential-addons-for-elementor-lite' ),
                'demo_link' => 'https://essential-addons.com/elementor/table-of-content/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/table-of-content',
            ],
            [
                'key'    => 'eael-post-duplicator',
                'title'  => __( 'Duplicator', 'essential-addons-for-elementor-lite'),
                'demo_link' => 'https://essential-addons.com/elementor/duplicator/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/duplicator/',
            ],
            [
                'key'    => 'eael-custom-js',
                'title'  => __( 'Custom JS', 'essential-addons-for-elementor-lite'),
                'demo_link' => 'https://essential-addons.com/elementor/custom-js/',
                'doc_link' => 'https://essential-addons.com/elementor/docs/custom-js/',
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
                                <?php _e( $item['title'], 'essential-addons-for-elementor-lite') ?>
                                <?php echo isset( $item['is_pro'] ) && !$this->pro_enabled ? '<sup class="pro-label">'.__('Pro', 'essential-addons-for-elementor-lite').'</sup>' : ''; ?>
                                <?php if ($item['key'] === 'eael-post-duplicator') {
                                    echo '<span style="font-size: 12px; font-style:italic;"><a href="#" class="eael-admin-settings-popup" data-title="Select Post Types" data-option="select" data-options=' . json_encode(get_post_types(['public' => true, 'show_in_nav_menus' => true])) . ' data-target="#post-duplicator-post-type">'.__('Settings', 'essential-addons-for-elementor-lite').'</a></span>
                                    <input type="hidden" name="post-duplicator-post-type" id="post-duplicator-post-type" class="post-duplicator-post-type" value="'.get_option('eael_save_post_duplicator_post_type').'">';
                                } ?>
                            </p>
                            <a  class="eael-element-info-link" href="<?php echo ($item['demo_link']);?>" target="_blank">
                                <span class="ea-view-demo"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30"><path d="M 5 4 C 3.895 4 3 4.895 3 6 L 3 20 C 3 21.105 3.895 22 5 22 L 12.75 22 L 12.25 24 L 11 24 A 1.0001 1.0001 0 1 0 11 26 L 19 26 A 1.0001 1.0001 0 1 0 19 24 L 17.75 24 L 17.25 22 L 25 22 C 26.105 22 27 21.105 27 20 L 27 6 C 27 4.895 26.105 4 25 4 L 5 4 z M 5 6 L 25 6 L 25 18 L 5 18 L 5 6 z M 15 19 C 15.552 19 16 19.448 16 20 C 16 20.552 15.552 21 15 21 C 14.448 21 14 20.552 14 20 C 14 19.448 14.448 19 15 19 z"></path></svg></span>
                                <span class="eael-info-tooltip"><?php _e('Demo', 'essential-addons-elementor'); ?></span>
                            </a>
                            <a class="eael-element-info-link" href="<?php echo ($item['doc_link']);?>" target="_blank">
                                <span class="ea-get-help"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M 12 2 C 6.4889971 2 2 6.4889971 2 12 C 2 17.511003 6.4889971 22 12 22 C 17.511003 22 22 17.511003 22 12 C 22 6.4889971 17.511003 2 12 2 z M 12 4 C 16.430123 4 20 7.5698774 20 12 C 20 16.430123 16.430123 20 12 20 C 7.5698774 20 4 16.430123 4 12 C 4 7.5698774 7.5698774 4 12 4 z M 12 6 C 9.79 6 8 7.79 8 10 L 10 10 C 10 8.9 10.9 8 12 8 C 13.1 8 14 8.9 14 10 C 14 12 11 12.367 11 15 L 13 15 C 13 13.349 16 12.5 16 10 C 16 7.79 14.21 6 12 6 z M 11 16 L 11 18 L 13 18 L 13 16 L 11 16 z"></path></svg></span>
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
                <button type="submit" class="button eael-btn js-eael-settings-save"><?php _e('Save settings', 'essential-addons-for-elementor-lite'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php

    $elements = [
        'content-elements'  => [
            'title' => __( 'CONTENT ELEMENTS', 'essential-addons-elementor' ),
            'elements'  => [
                [
                    'key'   => 'creative-btn',
                    'title' => __( 'Creative Button', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'team-members',
                    'title' => __( 'Team Member', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'testimonials',
                    'title' => __( 'Testimonial', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'flip-box',
                    'title' => __( 'Flip Box', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'info-box',
                    'title' => __( 'Info Box', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'dual-header',
                    'title' => __( 'Dual Color Header', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'tooltip',
                    'title' => __( 'Tooltip', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'essential-addons-elementor',
                    'title' => __( 'Advanced Accordion', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'adv-accordion',
                    'title' => __( 'Advanced Tabs', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'feature-list',
                    'title' => __( 'Feature List', 'essential-addons-elementor' )
                ]
            ]
        ],
        'dynamic-content-elements'  => [
            'title' => __( 'DYNAMIC CONTENT ELEMENTS', 'essential-addons-elementor' ),
            'elements'  => [
                [
                    'key'   => 'post-grid',
                    'title' => __( '    Post Grid', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'post-timeline',
                    'title' => __( 'Post Timeline', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'data-table',
                    'title' => __( 'Data Table', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'content-ticker',
                    'title' => __( 'Content Ticker', 'essential-addons-elementor' )
                ]
            ]
        ],
        'creative-elements' => [
            'title' => __( 'CREATIVE ELEMENTS', 'essential-addons-elementor' ),
            'elements'  => [
                [
                    'key'   => 'count-down',
                    'title' => __( 'Count Down', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'fancy-text',
                    'title' => __( 'Fancy Text', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'filter-gallery',
                    'title' => __( 'Filterable Gallery', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'image-accordion',
                    'title' => __( 'Image Accordion', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'progress-bar',
                    'title' => __( 'Progress Bar', 'essential-addons-elementor' )
                ]
            ]
        ],
        'marketing-elements'    => [
            'title' => __( 'MARKETING ELEMENTS', 'essential-addons-elementor' ),
            'elements'  => [
                [
                    'key'   => 'call-to-action',
                    'title' => __( 'Call To Action', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'price-table',
                    'title' => __( 'Pricing Table', 'essential-addons-elementor' )
                ]
            ]
        ],
        'form-styler-elements'  => [
            'title' => __( 'FORM STYLER ELEMENTS', 'essential-addons-elementor' ),
            'elements'  => [
                [
                    'key'   => 'contact-form-7',
                    'title' => __( 'Contact Form 7', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'weforms',
                    'title' => __( 'weForms', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'ninja-form',
                    'title' => __( 'Ninja Form', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'gravity-form',
                    'title' => __( 'Gravity Form', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'caldera-form',
                    'title' => __( 'Caldera Form', 'essential-addons-elementor' )
                ],
                [
                    'key'   => 'wpforms',
                    'title' => __( 'WPForms', 'essential-addons-elementor' )
                ]
            ]
        ],
        'social-feed-elements'  => [
            'title' => __( 'SOCIAL FEED ELEMENTS', 'essential-addons-elementor' ),
            'elements'  => [
                [
                    'key'   => 'twitter-feed',
                    'title' => __( 'Twitter Feed', 'essential-addons-elementor' )
                ]
            ]
        ]
    ];

    $elements = apply_filters( 'add_eael_elementor_addons', $elements );

?>
<div id="elements" class="eael-settings-tab eael-elements-list">
    <div class="row">
        <div class="col-full">
        <?php
            foreach($elements as $element) :
                ob_start();
        ?>
        <h4><?php echo $element['title']; ?></h4>
            <div class="eael-checkbox-container">
                <?php foreach($element['elements'] as $item) { ?>
                    <div class="eael-checkbox">
                        <input type="checkbox" id="<?php echo esc_attr($item['key']); ?>" name="<?php echo esc_attr($item['key']); ?>" <?php checked( 1, $this->get_settings($item['key']), true ); ?> >
                        <label for="<?php echo esc_attr($item['key']); ?>"></label>
                        <p class="eael-el-title"><?php _e( $item['title'], 'essential-addons-elementor' ) ?></p>
                    </div>
                <?php } ?>
            </div>
            <?php
                echo ob_get_clean();
                endforeach;
            ?>
        </div>
    </div>
</div>
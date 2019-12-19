<?php
    namespace Essential_Addons_Elementor\Extensions;

    if (!defined('ABSPATH')) {
        exit;
    }

    use \Elementor\Controls_Manager;

    class Table_of_Content {

        public function __construct(){
            add_action('elementor/documents/register_controls', [$this, 'register_controls'], 10);

        }

        public function register_controls( $element ){

            $global_settings = get_option('eael_global_settings');

            $element->start_controls_section(
                'eael_ext_table_of_content_section',
                [
                    'label' => esc_html__('EA Table of Content', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_SETTINGS,
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content',
                [
                    'label'         => __('Enable Table of Content', 'essential-addons-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'no',
                    'label_on'      => __('Yes', 'essential-addons-elementor'),
                    'label_off'     => __('No', 'essential-addons-elementor'),
                    'return_value'  => 'Yes',
                    'selectors' => [
                        '.eael-toc'  => 'display: none',
                    ],
                ]
            );

            $element->add_control(
                'eael_ext_toc_has_global',
                [
                    'label' => __('Enabled Globally?', 'essential-addons-elementor'),
                    'type' => Controls_Manager::HIDDEN,
                    'default' => isset($global_settings['table_of_content']['enabled']) ? true : false,
                ]
            );

            if (isset($global_settings['table_of_content']['enabled']) && ($global_settings['table_of_content']['enabled'] == true) && get_the_ID() != $global_settings['table_of_content']['post_id'] && get_post_status($global_settings['table_of_content']['post_id']) == 'publish') {
                $element->add_control(
                    'eael_ext_toc_global_warning_text',
                    [
                        'type' => Controls_Manager::RAW_HTML,
                        'raw' => __('You can modify the Global Table of content by <strong><a href="' . get_bloginfo('url') . '/wp-admin/post.php?post=' . $global_settings['table_of_content']['post_id'] . '&action=elementor">Clicking Here</a></strong>', 'essential-addons-elementor'),
                        'content_classes' => 'eael-warning',
                        'separator' => 'before',
                        'condition' => [
                            'eael_ext_table_of_content' => 'yes',
                        ],
                    ]
                );
            } else {
                $element->add_control(
                    'eael_ext_toc_global',
                    [
                        'label' => __('Enable Table of Content Globally', 'essential-addons-elementor'),
                        'description' => __('Enabling this option will effect on entire site.', 'essential-addons-elementor'),
                        'type' => Controls_Manager::SWITCHER,
                        'default' => 'no',
                        'label_on' => __('Yes', 'essential-addons-elementor'),
                        'label_off' => __('No', 'essential-addons-elementor'),
                        'return_value' => 'yes',
                        'separator' => 'before',
                        'condition' => [
                            'eael_ext_table_of_content' => 'yes',
                        ],
                    ]
                );

                $element->add_control(
                    'eael_ext_toc_global_display_condition',
                    [
                        'label' => __('Display On', 'essential-addons-elementor'),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => 'all',
                        'options' => [
                            'posts' => __('All Posts', 'essential-addons-elementor'),
                            'pages' => __('All Pages', 'essential-addons-elementor'),
                            'all' => __('All Posts & Pages', 'essential-addons-elementor'),
                        ],
                        'condition' => [
                            'eael_ext_table_of_content' => 'yes',
                            'eael_ext_toc_global' => 'yes',
                        ],
                        'separator' => 'before',
                    ]
                );
            }

            $element->add_control(
                'eael_ext_toc_position',
                [
                    'label' => esc_html__('Position', 'essential-addons-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'left',
                    'label_block' => false,
                    'options' => [
                        'left' => esc_html__('Left', 'essential-addons-elementor'),
                        'right' => esc_html__('Right', 'essential-addons-elementor'),
                    ],
                    'separator' => 'before',
                    'condition' => [
                        'eael_ext_table_of_content' => 'yes',
                    ],
                ]
            );

            $element->add_control(
                'eael_ext_toc_bg_color',
                [
                    'label' => __('Background Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '.eael-table-of-content' => 'background-color: {{VALUE}}',
                    ],
                    'separator' => 'before',
                    'condition' => [
                        'eael_ext_table_of_content' => 'yes',
                    ],
                ]
            );

            $element->add_control(
                'eael_ext_toc_supported_heading_tag',
                [
                    'label' => __( 'Supported Heading Tag', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::SELECT2,
                    'multiple' => true,
                    'label_block' => true,
                    'separator' => 'before',
                    'default' => [
                        'h1',
                        'h2',
                        'h3',
                        'h4',
                        'h5',
                        'h6',
                    ],
                    'options' => [
                        'h1' => __( 'H1', 'essential-addons-elementor' ),
                        'h2' => __( 'H2', 'essential-addons-elementor' ),
                        'h3' => __( 'H3', 'essential-addons-elementor' ),
                        'h4' => __( 'H4', 'essential-addons-elementor' ),
                        'h5' => __( 'H5', 'essential-addons-elementor' ),
                        'h6' => __( 'H6', 'essential-addons-elementor' ),
                    ],
                    'render_type' => 'none',
                    'condition' => [
                        'eael_ext_table_of_content' => 'yes',
                    ],
                ]
	        );

            $element->end_controls_section();
        }
    }

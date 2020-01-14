<?php
    namespace Essential_Addons_Elementor\Extensions;

    if (!defined('ABSPATH')) {
        exit;
    }

    use \Elementor\Controls_Manager;
    use \Elementor\Core\Schemes\Typography;
    use Elementor\Group_Control_Typography;

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
                    'return_value'  => 'yes',
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
                'eael_ext_toc_title',
                [
                    'label' => __('Title', 'essential-addons-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => __('Table of Contents', 'essential-addons-elementor'),
                    'label_block' => false,
                    'separator' => 'before',
                    'condition' => [
                        'eael_ext_table_of_content' => 'yes',
                    ],
                ]
            );

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
                'eael_ext_toc_list_icon',
                [
                    'label' => esc_html__('List Icon', 'essential-addons-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'bullet',
                    'label_block' => false,
                    'options' => [
                        'bullet' => esc_html__('Bullet', 'essential-addons-elementor'),
                        'number' => esc_html__('Number', 'essential-addons-elementor'),
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

            $element->add_control(
                'eael_ext_toc_collapse_sub_heading',
                [
                    'label'         => __('Collapse Sub Heading', 'essential-addons-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes',
                    'label_on'      => __('Yes', 'essential-addons-elementor'),
                    'label_off'     => __('No', 'essential-addons-elementor'),
                    'return_value'  => 'yes',
                    'separator'     => 'before',
                    'condition'     => [
                        'eael_ext_table_of_content' => 'yes',
                    ],
                ]
            );

            $element->end_controls_section();

            $element->start_controls_section(
                'eael_ext_table_of_content_header_style',
                [
                    'label' => esc_html__('EA TOC Header', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'eael_ext_table_of_content' => 'yes',
                    ],
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content_heading_separator',
                [
                    'label' => __( 'Heading', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content_header_bg',
                [
                    'label' => __('Background Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ff7d50',
                    'selectors' => [
                        '{{WRAPPER}} .eael-toc .eael-toc-header' => 'background-color: {{VALUE}}',
                        '{{WRAPPER}} .eael-toc.expanded .eael-toc-button' => 'background-color: {{VALUE}}'
                    ],
                    'separator' => 'before'
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content_header_text_color',
                [
                    'label' => __('Text Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .eael-toc .eael-toc-header .eael-toc-title' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .eael-toc.expanded .eael-toc-button' => 'color: {{VALUE}}'
                    ]
                ]
            );

            $element->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'eael_ext_table_of_content_header_typography',
                    'selector' => '{{WRAPPER}} .eael-toc-header .eael-toc-title,{{WRAPPER}} .eael-toc.expanded .eael-toc-button',
                    'scheme' => Typography::TYPOGRAPHY_1,
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content_header_icon',
                [
                    'label' => __( 'Icon', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::ICONS,
                    'label_block' => true,
                    'default' => [
                        'value'     => 'fas fa-list',
                        'library'   => 'fa-solid',
                    ],
                    'fa4compatibility' => 'icon',
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content_close_button',
                [
                    'label' => __( 'Close Button', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content_close_button_bg',
                [
                    'label' => __('Background Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .eael-toc .eael-toc-close' => 'background-color: {{VALUE}}'
                    ],
                    'separator' => 'before'
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content_close_button_text_color',
                [
                    'label' => __('Text Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ff7d50',
                    'selectors' => [
                        '{{WRAPPER}} .eael-toc .eael-toc-close' => 'color: {{VALUE}}',
                    ]
                ]
            );

            $element->end_controls_section();

            $element->start_controls_section(
                'eael_ext_table_of_content_list_style_section',
                [
                    'label' => esc_html__('EA TOC List', 'essential-addons-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'condition' => [
                        'eael_ext_table_of_content' => 'yes',
                    ],
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content_list_style_separator',
                [
                    'label' => __( 'List Style', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content_body_bg',
                [
                    'label' => __('Background Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#fff6f3',
                    'selectors' => [
                        '{{WRAPPER}} .eael-toc .eael-toc-body' => 'background-color: {{VALUE}}'
                    ],
                    'separator' => 'before'
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content_list_style',
                [
                    'label' => __( 'List Style', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'style_1',
                    'options' => [
                        'style_1' => __( 'Style 1', 'plugin-domain' ),
                        'style_2' => __( 'Style 2', 'plugin-domain' ),
                        'style_3' => __( 'Style 3', 'plugin-domain' )
                    ],
                ]
            );

            $element->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'eael_ext_table_of_content_list_typography',
                    'selector' => '{{WRAPPER}} .eael-toc .eael-toc-body ul.eael-toc-list li',
                    'scheme' => Typography::TYPOGRAPHY_1
                ]
            );

            $element->start_controls_tabs( 'ea_toc_list_style' );

            $element->start_controls_tab( 'normal',
                   [
                       'label' => __( 'Normal', 'essential-addons-elementor' ),
                   ]
            );

            $element->add_control(
                'eael_ext_table_of_content_list_text_color',
                [
                    'label' => __('Text Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#707070',
                    'selectors' => [
                        '{{WRAPPER}} .eael-toc ul.eael-toc-list li a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .eael-toc ul.eael-toc-list li ' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $element->end_controls_tab();

            $element->start_controls_tab( 'active',
                  [
                      'label' => __( 'Active', 'essential-addons-elementor' ),
                  ]
            );

            $element->add_control(
                'eael_ext_table_of_content_list_text_color_active',
                [
                    'label' => __('Text Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ff7d50',
                    'selectors' => [
                        '{{WRAPPER}} .eael-toc ul.eael-toc-list li.active > a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .eael-toc ul.eael-toc-list li.eael-highlight > a' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .eael-toc ul.eael-toc-list li.active' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .eael-toc ul.eael-toc-list li.eael-highlight' => 'color: {{VALUE}}',
                        '{{WRAPPER}} ul.eael-toc-list.eael-toc-list-style_2 li.active > a:before' => 'border-bottom: 10px solid {{VALUE}} !important',
                        '{{WRAPPER}} ul.eael-toc-list.eael-toc-list-style_3 li.active > a:after' => 'background: {{VALUE}} !important',
                    ],
                ]
            );

            $element->end_controls_tab(); // active

            $element->end_controls_tabs();

            $element->add_control(
                'eael_ext_table_of_content_list_separator',
                [
                    'label' => __( 'Separator', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content_list_separator_style',
                [
                    'label' => __( 'Style', 'essential-addons-elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'dashed',
                    'options' => [
                        'solid'  => __( 'Solid', 'plugin-domain' ),
                        'dashed' => __( 'Dashed', 'plugin-domain' ),
                        'none'   => __( 'None', 'plugin-domain' )
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eael-toc ul.eael-toc-list > li' => 'border-top: 0.5px {{VALUE}}',
                        '{{WRAPPER}} .eael-toc ul.eael-toc-list>li:first-child ' => 'border: none'
                    ]
                ]
            );

            $element->add_control(
                'eael_ext_table_of_content_list_separator_color',
                [
                    'label' => __('Color', 'essential-addons-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#c6c4cf',
                    'selectors' => [
                        '{{WRAPPER}} .eael-toc ul.eael-toc-list>li' => 'border-color: {{VALUE}}'
                    ],
                    'condition' => [
                        'eael_ext_table_of_content_list_separator_style!' => 'none',
                    ],
                ]
            );

            $element->end_controls_section();
        }
    }

<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Typography;
use \Elementor\Icons_Manager;
use \Elementor\Repeater;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use \Elementor\Utils;
use \Elementor\Widget_Base;

use \Essential_Addons_Elementor\Classes\Helper;

class Feature_List extends Widget_Base {
    public function get_name() {
        return 'eael-feature-list';
    }

    public function get_title() {
        return esc_html__( 'Feature List', 'essential-addons-for-elementor-lite' );
    }

    public function get_icon() {
        return 'eaicon-feature-list';
    }

    public function get_categories() {
        return ['essential-addons-elementor'];
    }

    public function get_keywords() {
        return [
            'list',
            'ea list',
            'ea feature list',
            'feature',
            'icon',
            'connector',
            'featured content',
            'highlights',
            'ea',
            'essential addons',
        ];
    }

    protected function is_dynamic_content():bool {
        return false;
    }

    public function has_widget_inner_wrapper(): bool {
        return ! Helper::eael_e_optimized_markup();
    }

    public function get_custom_help_url() {
        return 'https://essential-addons.com/elementor/docs/ea-feature-list/';
    }

    /**
     * Registers the ACF Repeater data-source controls: source switch, repeater picker,
     * ACF-inactive notice, and per-repeater sub-field mapping.
     */
    protected function eael_register_acf_controls() {
        $this->start_controls_section(
            'eael_section_feature_list_data_source',
            [
                'label' => esc_html__( 'Data Source', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $this->add_control(
            'eael_fl_data_source',
            [
                'label'   => esc_html__( 'Source', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'custom'       => esc_html__( 'Custom (Manual)', 'essential-addons-for-elementor-lite' ),
                    'acf_repeater' => esc_html__( 'ACF Repeater Field', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'eael_fl_acf_repeater_field',
            [
                'label'     => esc_html__( 'Repeater Field', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '',
                'options'   => Helper::eael_get_acf_repeater_options(),
                'condition' => [ 'eael_fl_data_source' => 'acf_repeater' ],
            ]
        );

        // ACF inactive notice (no-op when ACF is active).
        Helper::eael_acf_notice_controls( $this, [ 'eael_fl_data_source' => 'acf_repeater' ] );

        // Per-repeater sub-field mapping.
        $acf_sub_fields_by_repeater = Helper::eael_get_acf_repeater_sub_fields();

        foreach ( $acf_sub_fields_by_repeater as $repeater_name => $sub_field_options ) {
            $repeater_condition = [
                'eael_fl_data_source'        => 'acf_repeater',
                'eael_fl_acf_repeater_field' => $repeater_name,
            ];

            $this->add_control(
                'eael_fl_acf_fields_heading_' . $repeater_name,
                [
                    'label'     => esc_html__( 'Field Mapping', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'before',
                    'condition' => $repeater_condition,
                ]
            );

            $this->add_control(
                'eael_fl_acf_repeater_title_' . $repeater_name,
                [
                    'label'     => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => $sub_field_options,
                    'condition' => $repeater_condition,
                ]
            );

            $this->add_control(
                'eael_fl_acf_repeater_content_' . $repeater_name,
                [
                    'label'     => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => '',
                    'options'   => $sub_field_options,
                    'condition' => $repeater_condition,
                ]
            );

            $this->add_control(
                'eael_fl_acf_repeater_link_' . $repeater_name,
                [
                    'label'       => esc_html__( 'Link', 'essential-addons-for-elementor-lite' ),
                    'description' => esc_html__( 'Map an ACF Link, URL, or text field.', 'essential-addons-for-elementor-lite' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '',
                    'options'     => $sub_field_options,
                    'condition'   => $repeater_condition,
                ]
            );

            $this->add_control(
                'eael_fl_acf_repeater_image_' . $repeater_name,
                [
                    'label'       => esc_html__( 'Image', 'essential-addons-for-elementor-lite' ),
                    'description' => esc_html__( 'Map an ACF Image field. When set, each item shows an image instead of an icon.', 'essential-addons-for-elementor-lite' ),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => '',
                    'options'     => $sub_field_options,
                    'condition'   => $repeater_condition,
                ]
            );

            $extra_field_options = $sub_field_options;
            unset( $extra_field_options[''] );
            $this->add_control(
                'eael_fl_acf_repeater_extras_' . $repeater_name,
                [
                    'label'       => esc_html__( 'Additional Fields', 'essential-addons-for-elementor-lite' ),
                    'description' => esc_html__( 'Select extra sub-fields to display below each feature content.', 'essential-addons-for-elementor-lite' ),
                    'type'        => Controls_Manager::SELECT2,
                    'multiple'    => true,
                    'default'     => [],
                    'options'     => $extra_field_options,
                    'label_block' => true,
                    'condition'   => $repeater_condition,
                ]
            );
        }

        $this->end_controls_section();
    }

    /**
     * Returns the feature list items to render.
     *
     * Custom mode returns the manual repeater unchanged. ACF mode maps the selected
     * ACF Repeater field's rows into the exact item shape the render loop consumes,
     * so the render body stays identical across both sources.
     *
     * @param array $settings Widget settings from get_settings_for_display().
     * @return array
     */
    protected function get_feature_list_items( $settings ) {
        if ( 'acf_repeater' !== ( $settings['eael_fl_data_source'] ?? 'custom' ) ) {
            return is_array( $settings['eael_feature_list'] ?? null ) ? $settings['eael_feature_list'] : [];
        }

        $field_name = sanitize_text_field( $settings['eael_fl_acf_repeater_field'] ?? '' );
        if ( empty( $field_name ) || ! function_exists( 'get_field' ) ) {
            return [];
        }

        $title_key   = sanitize_text_field( $settings[ 'eael_fl_acf_repeater_title_' . $field_name ] ?? '' );
        $content_key = sanitize_text_field( $settings[ 'eael_fl_acf_repeater_content_' . $field_name ] ?? '' );
        $link_key    = sanitize_text_field( $settings[ 'eael_fl_acf_repeater_link_' . $field_name ] ?? '' );
        $image_key   = sanitize_text_field( $settings[ 'eael_fl_acf_repeater_image_' . $field_name ] ?? '' );
        $extra_keys  = ( isset( $settings[ 'eael_fl_acf_repeater_extras_' . $field_name ] ) && is_array( $settings[ 'eael_fl_acf_repeater_extras_' . $field_name ] ) )
            ? $settings[ 'eael_fl_acf_repeater_extras_' . $field_name ]
            : [];

        // Resolve sub-field labels once for the selected additional fields.
        $sub_field_labels = [];
        if ( ! empty( $extra_keys ) && function_exists( 'acf_get_field' ) ) {
            $repeater_field = acf_get_field( $field_name );
            if ( ! empty( $repeater_field['sub_fields'] ) ) {
                foreach ( $repeater_field['sub_fields'] as $sf ) {
                    $sub_field_labels[ $sf['name'] ] = $sf['label'];
                }
            }
        }

        $object_id = get_the_ID() ?: get_queried_object_id();
        $rows      = get_field( $field_name, $object_id );

        if ( empty( $rows ) || ! is_array( $rows ) ) {
            return [];
        }

        $items = [];
        foreach ( $rows as $row ) {
            if ( ! is_array( $row ) ) {
                continue;
            }

            // Title / content kept raw; the render loop escapes via wp_kses( ..., Helper::eael_allowed_tags() ).
            $title   = ( $title_key && is_scalar( $row[ $title_key ] ?? null ) ) ? (string) $row[ $title_key ] : '';
            $content = ( $content_key && is_scalar( $row[ $content_key ] ?? null ) ) ? (string) $row[ $content_key ] : '';

            // Link: ACF Link (array), URL string, or empty.
            $link = [ 'url' => '', 'is_external' => '', 'nofollow' => '' ];
            if ( $link_key ) {
                $raw_link = $row[ $link_key ] ?? '';
                if ( is_array( $raw_link ) ) {
                    $link['url']         = esc_url_raw( $raw_link['url'] ?? '' );
                    $link['is_external'] = ( '_blank' === ( $raw_link['target'] ?? '' ) ) ? 'on' : '';
                } elseif ( is_string( $raw_link ) && '' !== $raw_link ) {
                    $link['url'] = esc_url_raw( $raw_link );
                }
            }

            // Image: ACF image array, attachment ID, or URL string.
            $image = [ 'url' => '', 'id' => 0 ];
            if ( $image_key ) {
                $raw_image = $row[ $image_key ] ?? '';
                if ( is_array( $raw_image ) ) {
                    $image['url'] = $raw_image['url'] ?? '';
                    $image['id']  = (int) ( $raw_image['ID'] ?? ( $raw_image['id'] ?? 0 ) );
                } elseif ( is_numeric( $raw_image ) ) {
                    $image['id']  = (int) $raw_image;
                    $image['url'] = (string) wp_get_attachment_url( $image['id'] );
                } elseif ( is_string( $raw_image ) && '' !== $raw_image ) {
                    $image['url'] = $raw_image;
                }
            }

            $has_image = '' !== $image['url'];

            // Additional fields: [ [ 'label' => ..., 'value' => ... ], ... ].
            $extras = [];
            foreach ( $extra_keys as $ekey ) {
                $val = $row[ $ekey ] ?? '';
                if ( '' === $val || null === $val || [] === $val ) {
                    continue;
                }
                $extras[] = [
                    'label' => $sub_field_labels[ $ekey ] ?? $ekey,
                    'value' => is_scalar( $val ) ? (string) $val : '',
                ];
            }

            $items[] = [
                'eael_feature_list_icon_type' => $has_image ? 'image' : 'icon',
                'eael_feature_list_icon'      => '',
                'eael_feature_list_icon_new'  => '',
                'eael_feature_list_img'       => $image,
                'eael_feature_list_title'     => $title,
                'eael_feature_list_content'   => $content,
                'eael_feature_list_link'      => $link,
                'eael_feature_list_extras'    => $extras,
                '_id'                         => uniqid( 'fl_', false ),
            ];
        }

        return $items;
    }

    /**
     * Style controls for the ACF "Additional Fields" output (Style tab).
     * Only shown when the data source is an ACF Repeater.
     */
    protected function eael_acf_extra_data_controls_style() {
        $this->start_controls_section(
            'eael_fl_section_extra_data_style',
            [
                'label'     => esc_html__( 'Additional Fields', 'essential-addons-for-elementor-lite' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'eael_fl_data_source' => 'acf_repeater',
                ],
            ]
        );

        $this->add_control(
            'eael_fl_section_extra_data_text_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eael-feature-list-extras .eael-feature-list-extra-value' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_fl_section_extra_data_text_typography',
                'selector' => '{{WRAPPER}} .eael-feature-list-extras .eael-feature-list-extra-value',
            ]
        );

        $this->add_control(
            'eael_fl_section_extra_data_text_margin',
            [
                'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-feature-list-extras .eael-feature-list-extra' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_fl_section_extra_data_text_padding',
            [
                'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-feature-list-extras .eael-feature-list-extra' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_controls() {

        $this->eael_register_acf_controls();

        /**
         * Feature List Settings
         */
        $this->start_controls_section(
            'eael_section_feature_list_content_settings',
            [
                'label' => esc_html__( 'Content Settings', 'essential-addons-for-elementor-lite' ),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'eael_feature_list_icon_type',
            [
                'label'       => esc_html__( 'Icon Type', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
                    'icon'  => [
                        'title' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'fa fa-star',
                    ],
                    'image' => [
                        'title' => esc_html__( 'Image', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-image-bold',
                    ],
                ],
                'default'     => 'icon',
                'label_block' => false,
            ]
        );

        $repeater->add_control(
            'eael_feature_list_icon_new',
            [
                'label'            => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'eael_feature_list_icon',
                'condition'        => [
                    'eael_feature_list_icon_type' => 'icon',
                ],
            ]
        );

        // start icon color option
        $repeater->add_control(
            'eael_feature_list_icon_is_individual_style',
            [
                'label'            => esc_html__( 'Icon Style', 'essential-addons-for-elementor-lite' ),
                'type'             => Controls_Manager::SWITCHER,
                'label_on'         => __( 'ON', 'essential-addons-for-elementor-lite' ),
                'label_off'        => __( 'OFF', 'essential-addons-for-elementor-lite' ),
                'return_value'     => 'on',
                'default'          => '',
                'fa4compatibility' => 'eael_feature_list_icon',
                'condition'        => [
                    'eael_feature_list_icon_type' => 'icon',
                ],
            ]
        );
        $repeater->add_control(
            'eael_feature_list_icon_individual_color',
            [
                'label'            => esc_html__( 'Icon Color', 'essential-addons-for-elementor-lite' ),
                'type'             => Controls_Manager::COLOR,
                'default'          => '#fff',
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}} .eael-feature-list-icon i" => 'color: {{VALUE}};',
                    "{{WRAPPER}} {{CURRENT_ITEM}} .eael-feature-list-icon svg" => 'color: {{VALUE}} !important; fill: {{VALUE}} !important;',
                ],
                'fa4compatibility' => 'eael_feature_list_icon',
                'condition'        => [
                    'eael_feature_list_icon_is_individual_style' => 'on',
                ],
            ]
        );
        $repeater->add_control(
            'eael_feature_list_icon_individual_bg_color',
            [
                'label'            => esc_html__( 'Icon Background', 'essential-addons-for-elementor-lite' ),
                'type'             => Controls_Manager::COLOR,
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}} .eael-feature-list-icon" => 'background-color: {{VALUE}}',
                ],
                'fa4compatibility' => 'eael_feature_list_icon',
                'condition'        => [
                    'eael_feature_list_icon_is_individual_style' => 'on',
                ],
            ]
        );
        $repeater->add_control(
            'eael_feature_list_icon_individual_box_bg_color',
            [
                'label'            => esc_html__( 'Icon Box Background', 'essential-addons-for-elementor-lite' ),
                'type'             => Controls_Manager::COLOR,
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}} .eael-feature-list-icon-inner" => 'background-color: {{VALUE}}',
                ],
                'fa4compatibility' => 'eael_feature_list_icon',
                'condition'        => [
                    'eael_feature_list_icon_is_individual_style' => 'on',
                ],
            ]
        );
        // end icon color option

        $repeater->add_control(
            'eael_feature_list_img',
            [
                'label'     => esc_html__( 'Image', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'eael_feature_list_icon_type' => 'image',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $repeater->add_control(
            'eael_feature_list_title',
            [
                'label'   => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
                'dynamic' => ['active' => true],
                'ai' => [
					'active' => true,
				],
            ]
        );

        $repeater->add_control(
            'eael_feature_list_content',
            [
                'label'   => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'essential-addons-for-elementor-lite' ),
                'dynamic' => ['active' => true],
            ]
        );

        $repeater->add_control(
            'eael_feature_list_link',
            [
                'label'       => esc_html__( 'Link', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::URL,
                'dynamic'     => ['active' => true],
                'placeholder' => esc_html__( 'https://your-link.com', 'essential-addons-for-elementor-lite' ),
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'eael_feature_list',
            [
                'label'       => esc_html__( 'Feature Item', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::REPEATER,
                'seperator'   => 'before',
                'default'     => [
                    [
                        'eael_feature_list_icon_new' => [
                            'value'   => 'fas fa-check',
                            'library' => 'fa-solid',
                        ],
                        'eael_feature_list_title'    => esc_html__( 'Feature Item 1', 'essential-addons-for-elementor-lite' ),
                        'eael_feature_list_content'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'eael_feature_list_icon_new' => [
                            'value'   => 'fas fa-times',
                            'library' => 'fa-solid',
                        ],
                        'eael_feature_list_title'    => esc_html__( 'Feature Item 2', 'essential-addons-for-elementor-lite' ),
                        'eael_feature_list_content'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna', 'essential-addons-for-elementor-lite' ),
                    ],
                    [
                        'eael_feature_list_icon_new' => [
                            'value'   => 'fas fa-anchor',
                            'library' => 'fa-solid',
                        ],
                        'eael_feature_list_title'    => esc_html__( 'Feature Item 3', 'essential-addons-for-elementor-lite' ),
                        'eael_feature_list_content'  => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna', 'essential-addons-for-elementor-lite' ),
                    ],
                ],
                'condition'   => [ 'eael_fl_data_source' => 'custom' ],
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ elementor.helpers.renderIcon( this, eael_feature_list_icon_new, {}, "i", "panel" ) || \'<i class="{{ eael_feature_list_icon_new.value }}" aria-hidden="true"></i>\' }}} {{ eael_feature_list_title }}',
            ]
        );

        $this->add_responsive_control(
            'eael_feature_list_layout',
            [
                'label'           => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
                'type'            => Controls_Manager::SELECT,
                'default'         => 'vertical',
                'desktop_default' => 'vertical',
                'tablet_default'  => 'vertical',
                'mobile_default'  => 'vertical',
                'label_block'     => false,
                'separator'       => 'before',
                'options'         => [
                    'vertical'   => esc_html__( 'Vertical', 'essential-addons-for-elementor-lite' ),
                    'horizontal' => esc_html__( 'Horizontal', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'eael_feature_list_title_size',
            [
                'label'     => esc_html__( 'Title HTML Tag', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
                'default'   => 'h2',
            ]
        );

        $this->add_control(
            'eael_feature_list_icon_shape',
            [
                'label'       => esc_html__( 'Icon Shape', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'circle',
                'label_block' => false,
                'options'     => [
                    'circle'  => esc_html__( 'Circle', 'essential-addons-for-elementor-lite' ),
                    'square'  => esc_html__( 'Square', 'essential-addons-for-elementor-lite' ),
                    'rhombus' => esc_html__( 'Rhombus', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_control(
            'eael_feature_list_icon_shape_view',
            [
                'label'       => esc_html__( 'Shape View', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'stacked',
                'label_block' => false,
                'options'     => [
                    'framed'  => esc_html__( 'Framed', 'essential-addons-for-elementor-lite' ),
                    'stacked' => esc_html__( 'Stacked', 'essential-addons-for-elementor-lite' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_feature_list_icon_position',
            [
                'label'           => esc_html__( 'Icon Position', 'essential-addons-for-elementor-lite' ),
                'type'            => Controls_Manager::CHOOSE,
                'options'         => [
                    'left'  => [
                        'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-order-start',
                    ],
                    'top'   => [
                        'title' => esc_html__( 'Top', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-order-end',
                    ],
                ],
                'default'         => 'left',
                'devices'         => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => 'left',
                'tablet_default'  => 'left',
                'mobile_default'  => 'left',
                'toggle'          => false,
            ]
        );

        $this->add_control(
            'eael_feature_list_icon_vertical_position',
            [
                'label'           => esc_html__( 'Icon Vertical Position', 'essential-addons-for-elementor-lite' ),
                'type'            => Controls_Manager::CHOOSE,
                'options'         => [
                    'start'  => [
                        'title' => esc_html__( 'Top', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'center'   => [
                        'title' => esc_html__( 'Middle', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'end' => [
                        'title' => esc_html__( 'Bottom', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'default'         => 'start',
                'toggle'          => false,
                'condition'  => [
                    'eael_feature_list_layout'         => 'horizontal',
                    'eael_feature_list_icon_position!' => 'top',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-feature-list-items.eael-feature-list-horizontal .eael-feature-list-item' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_feature_list_icon_right_indicator_position',
            [
                'label'      => __( 'Arrow Indicator Position', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 500,
                        'step' => 5,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
					'unit' => 'px',
					'size' => 35,
				],
                'selectors'  => [
                    '{{WRAPPER}} .eael-feature-list-items.connector-type-modern .eael-feature-list-item:after' => 'top: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition'  => [
                    'eael_feature_list_layout'        => 'vertical',
                    'eael_feature_list_icon_position' => 'top',
                ],
            ]
        );

        $this->add_control(
            'eael_feature_list_connector',
            [
                'label'        => esc_html__( 'Show Connector', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => esc_html__( 'Show', 'essential-addons-for-elementor-lite' ),
                'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
                'condition' => [
                    'eael_feature_list_layout' => 'vertical'
                ]
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Feature List Style
         * -------------------------------------------
         */

        $this->start_controls_section(
            'eael_section_feature_list_style',
            [
                'label' => esc_html__( 'List', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'eael_feature_list_auto_width',
            [
                'label'        => esc_html__( 'Width', 'essential-addons-for-elementor-lite' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Auto', 'essential-addons-for-elementor-lite' ),
                'label_off'    => esc_html__( 'Fixed', 'essential-addons-for-elementor-lite' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'eael_feature_list_item_width',
            [
                'label'     => esc_html__( 'Item Width', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
                'default'   => [
					'unit' => '%',
                    'size' => 40,
                ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-feature-list-items.eael-feature-list-horizontal .eael-feature-list-item' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .eael-feature-list-items[data-layout-tablet="horizontal"] .eael-feature-list-item' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .eael-feature-list-items[data-layout-mobile="horizontal"] .eael-feature-list-item' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'   => [
                    'eael_feature_list_layout' => 'horizontal',
                    'eael_feature_list_auto_width!' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_feature_list_space_between',
            [
                'label'     => esc_html__( 'Space Between', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 15,
                ],
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-feature-list-items.eael-feature-list-horizontal' => 'gap: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .eael-feature-list-items.eael-feature-list-horizontal[data-layout-tablet="vertical"] .eael-feature-list-item' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .eael-feature-list-items.eael-feature-list-vertical .eael-feature-list-item'  => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
                    'body.rtl {{WRAPPER}} .eael-feature-list-items.eael-feature-list-vertical .eael-feature-list-item:after'    => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .eael-feature-list-items.eael-feature-list-vertical.connector-type-modern .eael-feature-list-item:not(:last-child):before' => 'height: calc(100% + {{SIZE}}{{UNIT}})',
                ],
            ]
        );

        $this->add_control(
            'eael_feature_list_connector_type',
            [
                'label'       => esc_html__( 'Connector Type', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'connector-type-classic',
                'label_block' => false,
                'options'     => [
                    'connector-type-classic' => esc_html__( 'Classic', 'essential-addons-for-elementor-lite' ),
                    'connector-type-modern'  => esc_html__( 'Modern', 'essential-addons-for-elementor-lite' ),
                ],
                'condition'   => [
                    'eael_feature_list_connector'      => 'yes',
                    'eael_feature_list_icon_position!' => 'top',
                ],
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'eael_feature_list_connector_styles',
            [
                'label'       => esc_html__( 'Connector Styles', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'solid',
                'label_block' => false,
                'options'     => [
                    'solid'  => esc_html__( 'Solid', 'essential-addons-for-elementor-lite' ),
                    'dashed' => esc_html__( 'Dashed', 'essential-addons-for-elementor-lite' ),
                    'dotted' => esc_html__( 'Dotted', 'essential-addons-for-elementor-lite' ),
                ],
                'condition'   => [
                    'eael_feature_list_connector' => 'yes',
                ],
                'selectors'   => [
                    '{{WRAPPER}} .connector-type-classic .connector'                                                                                      => 'border-style: {{VALUE}};',
                    '{{WRAPPER}} .connector-type-modern .eael-feature-list-item:before, {{WRAPPER}} .connector-type-modern .eael-feature-list-item:after' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_feature_list_connector_color',
            [
                'label'     => esc_html__( 'Connector Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'global' => [
					'default' => Global_Colors::COLOR_PRIMARY
				],
                'default'   => '#37368e',
                'selectors' => [
                    '{{WRAPPER}} .connector-type-classic .connector'                                                                                      => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .connector-type-modern .eael-feature-list-item:before, {{WRAPPER}} .connector-type-modern .eael-feature-list-item:after' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_feature_list_connector' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'eael_feature_list_connector_width',
            [
                'label'     => esc_html__( 'Connector Width', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'unit' => 'px',
					'size' => 1,
                ],
                'range'     => [
                    'px' => [
                        'min' => 1,
                        'max' => 5,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .connector-type-classic .connector'                                                                                                                                      => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-feature-list-items.connector-type-modern .eael-feature-list-item:before, {{WRAPPER}} .eael-feature-list-items.connector-type-modern .eael-feature-list-item:after' => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .-icon-position-left .connector-type-modern .eael-feature-list-item:before, {{WRAPPER}} .-icon-position-left .connector-type-modern .eael-feature-list-item:after'       => 'border-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .-icon-position-right .connector-type-modern .eael-feature-list-item:before, {{WRAPPER}} .-icon-position-right .connector-type-modern .eael-feature-list-item:after'     => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_feature_list_connector' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Feature List Icon Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_feature_list_style_icon',
            [
                'label' => esc_html__( 'Icon', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'eael_feature_list_icon_background',
                'types'    => ['classic', 'gradient'],
                'exclude'  => [ 'image' ],
                'fields_options'    => [
                    'background' => [
                        'default' => 'classic', // Default to classic background
                    ],
                    'color' => [
                        'default' => '#37368e', // Default background color (red)
                    ],
                ],
                'selector' => '{{WRAPPER}} .eael-feature-list-items .eael-feature-list-icon-box .eael-feature-list-icon-inner',
            ]
        );

        $this->add_control(
            'eael_feature_list_secondary_color',
            [
                'label'     => esc_html__( 'Secondary Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#07b4eb',
                'selectors' => [
                    '{{WRAPPER}} .eael-feature-list-items.framed .eael-feature-list-icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'eael_feature_list_icon_shape_view' => 'framed',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_feature_list_icon_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .eael-feature-list-items .eael-feature-list-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eael-feature-list-items .eael-feature-list-icon svg' => 'fill: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'eael_feature_list_icon_circle_size',
            [
                'label'     => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 70,
                ],
                'range'     => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon'        => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-feature-list-items.connector-type-classic .connector' => 'right: calc(100% - {{SIZE}}{{UNIT}});',
                ],
            ]
        );
        $this->add_responsive_control(
            'eael_feature_list_icon_size',
            [
                'label'     => esc_html__( 'Icon Size', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 21,
                ],
                'range'     => [
                    'px' => [
                        'min' => 6,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon i'   => 'font-size: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;line-height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eael-feature-list-img'                                  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_feature_list_icon_padding',
            [
                'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top'      => 15,
                    'right'    => 15,
                    'bottom'   => 15,
                    'left'     => 15,
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'eael_feature_list_icon_border_width',
            [
                'label'     => esc_html__( 'Border Width', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 1,
                ],
                'range'     => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon-inner' => 'padding: {{SIZE}}{{UNIT}};',

                ],
                'condition' => [
                    'eael_feature_list_icon_shape_view' => 'framed',
                ],
            ]
        );

        $this->add_control(
            'eael_feature_list_icon_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon-inner'                         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .eael-feature-list-icon-box .eael-feature-list-icon-inner .eael-feature-list-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'eael_feature_list_icon_shape_view' => 'framed',
                ],
            ]
        );

        $this->add_responsive_control(
            'eael_feature_list_icon_space',
            [
                'label'           => esc_html__( 'Spacing', 'essential-addons-for-elementor-lite' ),
                'type'            => Controls_Manager::SLIDER,
                'range'           => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'devices'         => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'tablet_default'  => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'mobile_default'  => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors'       => [
                    '{{WRAPPER}} .-icon-position-left .eael-feature-list-content-box, {{WRAPPER}} .-icon-position-right .eael-feature-list-content-box, {{WRAPPER}} .-icon-position-top .eael-feature-list-content-box' => 'margin: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}} .-mobile-icon-position-left .eael-feature-list-content-box'                                                                                                                    => 'margin: 0 0 0 {{SIZE}}{{UNIT}} !important;',
                    '(mobile){{WRAPPER}} .-mobile-icon-position-right .eael-feature-list-content-box'                                                                                                                   => 'margin: 0 {{SIZE}}{{UNIT}} 0 0 !important;',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Feature List Content Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'eael_section_feature_list_style_content',
            [
                'label' => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'eael_feature_list_text_align',
            [
                'label'     => __( 'Alignment', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'    => [
                        'title' => __( 'Left', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'  => [
                        'title' => __( 'Center', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'   => [
                        'title' => __( 'Right', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justified', 'essential-addons-for-elementor-lite' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'condition' => [
                    'eael_feature_list_icon_position' => 'top',
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-feature-list-item' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'eael_feature_list_heading_title',
            [
                'label' => esc_html__( 'Title', 'essential-addons-for-elementor-lite' ),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'eael_feature_list_title_bottom_space',
            [
                'label'     => esc_html__( 'Title Bottom Space', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 10,
                ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .eael-feature-list-item .eael-feature-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'eael_feature_list_title_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#414247',
                'selectors' => [
                    '{{WRAPPER}} .eael-feature-list-content-box .eael-feature-list-title, {{WRAPPER}} .eael-feature-list-content-box .eael-feature-list-title > a, {{WRAPPER}} .eael-feature-list-content-box .eael-feature-list-title:visited' => 'color: {{VALUE}};',
                ],
                'global' => [
					'default' => Global_Colors::COLOR_PRIMARY
				],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'eael_feature_list_title_typography',
                'selector' => '{{WRAPPER}} .eael-feature-list-content-box .eael-feature-list-title, {{WRAPPER}} .eael-feature-list-content-box .eael-feature-list-title a',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY
                ],
            ]
        );

        $this->add_control(
            'eael_feature_list_description',
            [
                'label'     => esc_html__( 'Description', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'eael_feature_list_description_color',
            [
                'label'     => esc_html__( 'Color', 'essential-addons-for-elementor-lite' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .eael-feature-list-content-box .eael-feature-list-content' => 'color: {{VALUE}};',
                ],
                'global' => [
					'default' => Global_Colors::COLOR_TEXT
				],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'eael_feature_list_description_typography',
                'selector'       => '{{WRAPPER}} .eael-feature-list-content-box .eael-feature-list-content',
                'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_TEXT
                ],
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->end_controls_section();

        $this->eael_acf_extra_data_controls_style();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $css_id = 'eael-feature-list-' . esc_attr( $this->get_id() );

        // Get responsive layout settings
        $layout_desktop = ( !empty($settings['eael_feature_list_layout']) && $settings['eael_feature_list_layout'] ) ? $settings['eael_feature_list_layout'] : 'vertical';
        $layout_tablet = ( !empty($settings['eael_feature_list_layout_tablet']) && $settings['eael_feature_list_layout_tablet'] ) ? $settings['eael_feature_list_layout_tablet'] : $layout_desktop;
        $layout_mobile = ( !empty($settings['eael_feature_list_layout_mobile']) && $settings['eael_feature_list_layout_mobile'] ) ? $settings['eael_feature_list_layout_mobile'] : $layout_tablet;

        $this->add_render_attribute( 'eael_feature_list', [
            'id'    => $css_id,
            'class' => [
                'eael-feature-list-items',
                $settings['eael_feature_list_icon_shape'],
                $settings['eael_feature_list_icon_shape_view'],
                $settings['eael_feature_list_connector_type'],
                'eael-feature-list-' . $layout_desktop, // Keep existing class for desktop
            ],
            'data-layout-tablet' => $layout_tablet,
            'data-layout-mobile' => $layout_mobile,
        ] );
        // connector class change by connector type
        if ( $settings['eael_feature_list_icon_position'] == 'top' && $settings['eael_feature_list_connector'] == 'yes' ) {
            $this->add_render_attribute( 'eael_feature_list', 'class', 'connector-type-modern' );
        }

        $this->add_render_attribute( 'eael_feature_list_item', 'class', 'eael-feature-list-item' );

        // $padding = $settings['eael_feature_list_icon_padding']['size'];
        $circle_size = isset( $settings['eael_feature_list_icon_circle_size']['size'] ) ? intval( $settings['eael_feature_list_icon_circle_size']['size'] ) : 70;
        $font = isset( $settings['eael_feature_list_icon_size']['size'] ) ? $settings['eael_feature_list_icon_size']['size'] : 21;

        if ( isset( $settings['eael_feature_list_icon_border_width']['right'] ) && isset( $settings['eael_feature_list_icon_border_width']['left'] ) ) {
            $border = $settings['eael_feature_list_icon_border_width']['right'] + $settings['eael_feature_list_icon_border_width']['left'];
        }

        if ( !empty($settings['eael_feature_list_icon_shape']) && $settings['eael_feature_list_icon_shape'] == 'rhombus' ) {
            $margin = 30;
            $connector_width = intval( $circle_size + $margin + ( !empty( $settings['eael_feature_list_connector_width']['size'] ) ? $settings['eael_feature_list_connector_width']['size'] : 0 ) );
        } else {
            $connector_width = intval( $circle_size + ( !empty( $settings['eael_feature_list_connector_width']['size'] ) ? $settings['eael_feature_list_connector_width']['size'] : 0 ) );
        }

        // connector
        if ( !empty($settings['eael_feature_list_icon_position']) && $settings['eael_feature_list_icon_position'] == 'right' ) {
            $connector = 'left: calc(100% - ' . $connector_width . 'px); right: 0;';
        } else {
            $connector = 'right: calc(100% - ' . $connector_width . 'px); left: 0;';
        }
        // mobile
        if ( !empty($settings['eael_feature_list_icon_position_tablet']) && $settings['eael_feature_list_icon_position_tablet'] == 'right' ) {
            $connector_tablet = 'left: calc(100% - ' . $connector_width . 'px); right: 0;';
        } else {
            $connector_tablet = 'right: calc(100% - ' . $connector_width . 'px); left: 0;';
        }
        // mobile
        if ( !empty($settings['eael_feature_list_icon_position_mobile']) && $settings['eael_feature_list_icon_position_mobile'] == 'right' ) {
            $connector_mobile = 'left: calc(100% - ' . $connector_width . 'px); right: 0;';
        } else {
            $connector_mobile = 'right: calc(100% - ' . $connector_width . 'px); left: 0;';
        }

        // icon position for all mode
        $eael_feature_list_icon_position_setting = ( !empty($settings['eael_feature_list_icon_position']) && $settings['eael_feature_list_icon_position'] ) ? $settings['eael_feature_list_icon_position'] : 'left';
        $eael_feature_list_icon_position_tablet_setting = ( !empty($settings['eael_feature_list_icon_position_tablet']) && $settings['eael_feature_list_icon_position_tablet'] ) ? $settings['eael_feature_list_icon_position_tablet'] : 'left';
        $eael_feature_list_icon_position_mobile_setting = ( !empty($settings['eael_feature_list_icon_position_mobile']) && $settings['eael_feature_list_icon_position_mobile'] ) ? $settings['eael_feature_list_icon_position_mobile'] : 'left';
        $this->add_render_attribute(
            'eael_feature_list_wrapper',
            [
                'class' => [
                    '-icon-position-' . $eael_feature_list_icon_position_setting,
                    '-tablet-icon-position-' . $eael_feature_list_icon_position_tablet_setting,
                    '-mobile-icon-position-' . $eael_feature_list_icon_position_mobile_setting,
                ],
            ]
        );

        ?>
		<div <?php $this->print_render_attribute_string( 'eael_feature_list_wrapper' ); ?>>
			<ul <?php $this->print_render_attribute_string( 'eael_feature_list' ); ?>>
			<?php
        foreach ( $this->get_feature_list_items( $settings ) as $index => $item ):

            $this->add_render_attribute( 'eael_feature_list_icon' . $index, 'class', 'eael-feature-list-icon fl-icon-'.$index );
            $this->add_render_attribute( 'eael_feature_list_title' . $index, 'class', 'eael-feature-list-title' );
            $this->add_render_attribute( 'eael_feature_list_content' . $index, 'class', 'eael-feature-list-content' );

            $feat_title_tag = Helper::eael_validate_html_tag($settings['eael_feature_list_title_size']);

            if ( $item['eael_feature_list_link']['url'] ) {
                $this->add_link_attributes( 'eael_feature_list_title_anchor_' . $index, $item['eael_feature_list_link'] );
            }

            $feature_icon_tag = 'span';

            $feature_has_icon = ( !empty( $item['eael_feature_list_icon'] ) || !empty( $item['eael_feature_list_icon_new'] ) );

            if ( $item['eael_feature_list_link']['url'] ) {
                $this->add_link_attributes( 'eael_feature_list_link' . $index, $item['eael_feature_list_link'] );

                $feature_icon_tag = 'a';
            }
            ?>
                <li class="eael-feature-list-item <?php echo 'elementor-repeater-item-' . esc_attr( $item['_id'] ); ?>">
                    <?php if ( 'yes' == $settings['eael_feature_list_connector'] ): ?>
                        <span class="connector" style="<?php echo esc_attr( $connector ); ?>"></span>
                        <span class="connector connector-tablet" style="<?php echo esc_attr( $connector_tablet ); ?>"></span>
                        <span class="connector connector-mobile" style="<?php echo esc_attr( $connector_mobile ); ?>"></span>
                    <?php endif;?>

						<div class="eael-feature-list-icon-box">
							<div class="eael-feature-list-icon-inner">

								<<?php echo esc_html( $feature_icon_tag ) . ' '; $this->print_render_attribute_string( 'eael_feature_list_icon' . $index); $this->print_render_attribute_string( 'eael_feature_list_link' . $index); ?>>

		<?php
            if ( $item['eael_feature_list_icon_type'] == 'icon' && $feature_has_icon ) {

            if ( empty( $item['eael_feature_list_icon'] ) || isset( $item['__fa4_migrated']['eael_feature_list_icon_new'] ) ) {
                Icons_Manager::render_icon( $item['eael_feature_list_icon_new'], [ 'aria-hidden' => 'true' ] );
            } else {
                echo '<i class="' . esc_attr( $item['eael_feature_list_icon'] ) . '" aria-hidden="true"></i>';
            }
        }


            if ( $item['eael_feature_list_icon_type'] == 'image' ) {
            $item['eael_feature_list_img'] = Helper::eael_wpml_translate_media( $item['eael_feature_list_img'] ); // WPML Media Translation compatibility
            $this->add_render_attribute( 'feature_list_image' . $index, [
                'src'   => esc_url( $item['eael_feature_list_img']['url'] ),
                'class' => 'eael-feature-list-img',
                'alt'   => esc_attr( get_post_meta( $item['eael_feature_list_img']['id'], '_wp_attachment_image_alt', true ) ),
            ] );

            echo '<img '; $this->print_render_attribute_string( 'feature_list_image' . $index); echo '>';

        }?>
								</<?php echo esc_html( $feature_icon_tag ); ?>>
							</div>
						</div>
						<div class="eael-feature-list-content-box">
                            <?php 
                            echo '<' . esc_html( $feat_title_tag ) . ' '; $this->print_render_attribute_string( 'eael_feature_list_title' . $index); echo '>';
                            $is_linked = ! empty( $item['eael_feature_list_link']['url'] );
                            if( $is_linked ){
                                echo '<a '; $this->print_render_attribute_string( 'eael_feature_list_title_anchor_' . $index); echo '>';
                            }
                            echo wp_kses( $item['eael_feature_list_title'], Helper::eael_allowed_tags() );
                            echo $is_linked ? '</a>' : '';
                            echo '</' . esc_html( $feat_title_tag ) . '>';
                            ?>
						<p <?php $this->print_render_attribute_string( 'eael_feature_list_content' . $index); ?>><?php echo wp_kses( $item['eael_feature_list_content'], Helper::eael_allowed_tags() ); ?></p>
						<?php if ( ! empty( $item['eael_feature_list_extras'] ) ) : ?>
							<ul class="eael-feature-list-extras">
								<?php foreach ( $item['eael_feature_list_extras'] as $extra ) : ?>
									<li class="eael-feature-list-extra"><span class="eael-feature-list-extra-value"><?php echo wp_kses_post( $extra['value'] ); ?></span></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						</div>

					</li>
				<?php
				endforeach;?>
			</ul>
		</div>
		<?php
}

    protected function content_template() {}
}

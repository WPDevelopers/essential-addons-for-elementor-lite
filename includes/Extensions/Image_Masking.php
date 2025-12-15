<?php

namespace Essential_Addons_Elementor\Extensions;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Image_Masking {

    private static $svg_dir_url = EAEL_PLUGIN_URL . 'assets/front-end/img/image-masking/svg-shapes/';
	/**
	 * Initialize hooks
	 */
	public function __construct() {
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/frontend/before_render', [ $this, 'before_render' ], 100 );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

    public function enqueue_scripts() {
        $data = [ 'svg_dir_url' => self::$svg_dir_url ];
        wp_localize_script( 'elementor-frontend', 'EAELImageMaskingConfig', $data );
    }

    private function clip_paths( $shape ){
        $shapes = [
            'bavel' => 'polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%)',
            'rabbet' => 'polygon(0% 15%, 15% 15%, 15% 0%, 85% 0%, 85% 15%, 100% 15%, 100% 85%, 85% 85%, 85% 100%, 15% 100%, 15% 85%, 0% 85%)',
            'chevron-left' => 'polygon(100% 0%, 75% 50%, 100% 100%, 25% 100%, 0% 50%, 25% 0%)',
            'chevron-right' => 'polygon(75% 0%, 100% 50%, 75% 100%, 0% 100%, 25% 50%, 0% 0%)',
            'star' => 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)',
        ];
        return $shapes[$shape] ?? '';
    }
    private function masking_controllers( $element, $tab = '' ) {

        $condition = [];
        if( '_hover' === $tab ) {
            $element->add_control(
                'eael_image_masking_hover_effect',
                [
                    'label' => esc_html__( 'Enable', 'essential-addons-for-elementor-lite' ),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => 'yes',
                ]
            );
            $condition = [
				'eael_image_masking_hover_effect' => 'yes',
			];
        }

        $condition['eael_image_masking_type'] = 'clip';
        $image_dir_url = EAEL_PLUGIN_URL . 'assets/front-end/img/image-masking/clip-paths/';
        $element->add_control(
            'eael_image_masking_clip_path' . $tab,
            [
                'label' => esc_html__( 'Select Clip Path', 'essential-addons-for-elementor-lite' ),
                'label_block' => true,
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'bavel' => [
                        'title' => esc_html__( 'Bavel', 'essential-addons-for-elementor-lite' ),
                        'image' => $image_dir_url . 'bavel.svg',
                    ],
                    'rabbet' => [
                        'title' => esc_html__( 'Rabbet', 'essential-addons-for-elementor-lite' ),
                        'image' => $image_dir_url . 'rabbet.svg',
                    ],
                    'chevron-left' => [
                        'title' => esc_html__( 'Chevron Left', 'essential-addons-for-elementor-lite' ),
                        'image' => $image_dir_url . 'chevron-left.svg',
                    ],
                    'chevron-right' => [
                        'title' => esc_html__( 'Chevron Right', 'essential-addons-for-elementor-lite' ),
                        'image' => $image_dir_url . 'chevron-right.svg',
                    ],
                    'star' => [
                        'title' => esc_html__( 'Start', 'essential-addons-for-elementor-lite' ),
                        'image' => $image_dir_url . 'star.svg',
                    ],
                ],
                'default' => 'bavel',
                'toggle'       => false,
                'image_choose' => true,
                'css_class'    => 'eael-image-masking-choose',
                'condition'    => array_merge( $condition, [ 'eael_image_masking_enable_custom_clip_path' . $tab . '!' => 'yes' ] )
            ]
        );

        $element->add_control(
            'eael_image_masking_enable_custom_clip_path' . $tab,
            [
                'label'       => esc_html__( 'Use Custom Clip Path', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'condition'   => $condition,
            ]
        );

        $element->add_control(
            'eael_image_masking_custom_clip_path' . $tab,
            [
                'label'       => '',
                'type'        => Controls_Manager::TEXTAREA,
                'rows'        => 10,
                'label_block' => true,
                'dynamic'     => [
					'active' => false,
				],
                'ai' => [
					'active' => false,
				],
                'default'     => 'clip-path: polygon(50% 0%, 80% 10%, 100% 35%, 100% 70%, 80% 90%, 50% 100%, 20% 90%, 0% 70%, 0% 35%, 20% 10%);',
                'placeholder' => 'clip-path: polygon(50% 0%, 0% 100%, 100% 100%);',
                'description' => __( 'Enter your custom clip path value. You can use <a href = "https://bennettfeely.com/clippy/" target = "_blank">Clippy</a> to generate your custom clip path.', 'essential-addons-for-elementor-lite' ),
                'condition'   => array_merge( $condition, [ 'eael_image_masking_enable_custom_clip_path' . $tab => 'yes' ] ),
            ]
        );

        $condition = [ 'eael_image_masking_type' => 'image' ];
        if( '_hover' === $tab ){
            $condition['eael_image_masking_hover_effect'] = 'yes';
        }

        $svg_url = EAEL_PLUGIN_URL . 'assets/front-end/img/image-masking/svg-shapes/';
        $element->add_control(
            'eael_image_masking_svg' . $tab,
            [
                'label' => esc_html__( 'Choose Shape', 'essential-addons-for-elementor-lite' ),
                'label_block' => true,
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'polygon' => [
                        'title' => esc_html__( 'Polygon', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'polygon.svg',
                    ],
                    'rounded' => [
                        'title' => esc_html__( 'Rounded', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'rounded.svg',
                    ],
                    'arch-down' => [
                        'title' => esc_html__( 'Arch Down', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'arch-down.svg',
                    ],
                    'arch-group' => [
                        'title' => esc_html__( 'Arch Group', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'arch-group.svg',
                    ],
                    'arch-up' => [
                        'title' => esc_html__( 'Arch Up', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'arch-up.svg',
                    ],
                    'asterisk' => [
                        'title' => esc_html__( 'Asterisk', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'asterisk.svg',
                    ],
                    'blob' => [
                        'title' => esc_html__( 'Blob', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'blob.svg',
                    ],
                    'blocks' => [
                        'title' => esc_html__( 'Blocks', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'blocks.svg',
                    ],
                    'brash-1' => [
                        'title' => esc_html__( 'Brash 1', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'brash-1.svg',
                    ],
                    'brash-2' => [
                        'title' => esc_html__( 'Brash 2', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'brash-2.svg',
                    ],
                    'brash-3' => [
                        'title' => esc_html__( 'Brash 3', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'brash-3.svg',
                    ],
                    'burst' => [
                        'title' => esc_html__( 'Burst', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'burst.svg',
                    ],
                    'chat' => [
                        'title' => esc_html__( 'Chat', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'chat.svg',
                    ],
                    'hex-tile' => [
                        'title' => esc_html__( 'Hex Tile', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'hex-tile.svg',
                    ],
                    'leaf' => [
                        'title' => esc_html__( 'Leaf', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'leaf.svg',
                    ],
                    'oval' => [
                        'title' => esc_html__( 'Oval', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'oval.svg',
                    ],
                    'pixel-cross' => [
                        'title' => esc_html__( 'Pixel Cross', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'pixel-cross.svg',
                    ],
                    'quote' => [
                        'title' => esc_html__( 'Quote', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'quote.svg',
                    ],
                    'star-dimond' => [
                        'title' => esc_html__( 'Star Diamond', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'star-dimond.svg',
                    ],
                    'upload' => [
                        'title' => esc_html__( 'Upload', 'essential-addons-for-elementor-lite' ),
                        'image' => $svg_url . 'upload.svg',
                        'fullwidth' => true,
                    ],
                ],
                'toggle'       => false,
                'image_choose' => true,
                'css_class'    => 'eael-image-masking-choose col-5',
                'default'      => 'polygon',
				'condition'    => $condition
            ]
        );

        if ( !apply_filters('eael/pro_enabled', false ) ) {
            $element->add_control(
                'eael_image_masking_upload_pro_message' . $tab,
                [
                    'label' => '',
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<div class="ea-nerd-box">
                            <div class="ea-nerd-box-message">' . __('This feature is available in the pro version.', 'essential-addons-for-elementor-lite') . '</div>
                            <a class="ea-nerd-box-link elementor-button elementor-button-default" href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">
                            ' . __('Upgrade to EA PRO', 'essential-addons-for-elementor-lite') . '
                            </a>
                        </div>',
                    'content_classes' => 'eael-pro-notice',
                    'condition' => array_merge( $condition, [ 'eael_image_masking_svg' . $tab => 'upload' ] )   
                ]
            );
        } else {
            do_action( 'eael/image-masking/image_control', $element, array_merge( $condition, [ 'eael_image_masking_svg' . $tab => 'upload' ] ), $tab );
        }

        if( '_hover' === $tab ){
            $element->add_control(
                'eael_image_masking_hover_selector',
                [
                    'label'       => esc_html__( 'Hover Selector', 'essential-addons-for-elementor-lite' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => [
                        'active' => false,
                    ],
                    'ai' => [
                        'active' => false,
                    ],
                    'placeholder' => '.not-masked-element, #not-masked-element',
                    'description' => __( 'Enter the selector for the element you want to apply the hover effect on. If you leave this field empty, the hover effect will be applied on hover for full section.', 'essential-addons-for-elementor-lite' ),
                    'condition'   => [
                        'eael_image_masking_hover_effect' => 'yes',
                    ]
                ]
            );
        }
    }

	public function register_controls( $element ) {
		$element->start_controls_section(
			'eael_image_masking_section',
			[
				'label' => __( '<i class="eaicon-logo"></i> Image Masking', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_ADVANCED
			]
		);

		$element->add_control(
			'eael_enable_image_masking',
			[
				'label' => __( 'Enable Image Masking', 'essential-addons-for-elementor-lite' ),
				'type'  => Controls_Manager::SWITCHER
			]
		);

        $element->add_control(
            'eael_image_masking_type',
            [
                'label' => esc_html__( 'Type', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'image' => [
                        'title' => esc_html__( 'Image/SVG Masking', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-image',
                    ],
                    'clip' => [
                        'title' => esc_html__( 'Clip Path', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-integration',
                    ],
                    'morphing' => [
                        'title' => esc_html__( 'Morphing', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-animation',
                    ]
                ],
                'condition' => [
					'eael_enable_image_masking' => 'yes'
				],
                'default' => 'clip',
                'toggle' => false,
            ]
        );

        $element->start_controls_tabs( 'eael_image_masking_tabs', [
            'condition' => [
                'eael_enable_image_masking' => 'yes',
                'eael_image_masking_type!' => 'morphing'
            ]
        ] );
        
        $element->start_controls_tab(
            'eael_image_masking_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
            ]
        );
        
        $this->masking_controllers( $element );
        
        $element->end_controls_tab();
        
        $element->start_controls_tab(
            'eael_image_masking_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
            ]
        );
        
        $this->masking_controllers( $element, '_hover' );
        
        $element->end_controls_tab();
        
        $element->end_controls_tabs();

        $element->add_control(
            'eael_image_masking_image_size',
            [
                'label' => esc_html__( 'Size', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'contain',
                'options' => [
                    ''        => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
                    'auto'    => esc_html__( 'Auto', 'essential-addons-for-elementor-lite' ),
                    'cover'   => esc_html__( 'Cover', 'essential-addons-for-elementor-lite' ),
                    'contain' => esc_html__( 'Contain', 'essential-addons-for-elementor-lite' ),
                    'custom'  => esc_html__( 'Custom', 'essential-addons-for-elementor-lite' ),
                ],
                'condition' => [
                    'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'image'
				],
                'selectors' => [
                    '{{WRAPPER}} img' => 'mask-size: {{VALUE}}; -webkit-mask-size: {{VALUE}};',
                ],
            ]
        );

        $element->add_control(
            'eael_image_masking_image_custom_size_custom',
            [
                'label' => '',
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} img' => 'mask-size: {{SIZE}}{{UNIT}}; -webkit-mask-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'image',
                    'eael_image_masking_image_size' => 'custom'
				],
            ]
        );

        $element->add_control(
            'eael_image_masking_image_position',
            [
                'label' => esc_html__( 'Position', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    ''              => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
                    'left top'      => esc_html__( 'Left Top', 'essential-addons-for-elementor-lite' ),
                    'left center'   => esc_html__( 'Left Center', 'essential-addons-for-elementor-lite' ),
                    'left bottom'   => esc_html__( 'Left Bottom', 'essential-addons-for-elementor-lite' ),
                    'center top'    => esc_html__( 'Center Top', 'essential-addons-for-elementor-lite' ),
                    'center center' => esc_html__( 'Center Center', 'essential-addons-for-elementor-lite' ),
                    'center bottom' => esc_html__( 'Center Bottom', 'essential-addons-for-elementor-lite' ),
                    'right top'     => esc_html__( 'Right Top', 'essential-addons-for-elementor-lite' ),
                    'right center'  => esc_html__( 'Right Center', 'essential-addons-for-elementor-lite' ),
                    'right bottom'  => esc_html__( 'Right Bottom', 'essential-addons-for-elementor-lite' )
                ],
                'condition' => [
                    'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'image'
				],
                'selectors' => [
                    '{{WRAPPER}} img' => 'mask-position: {{VALUE}}; -webkit-mask-position: {{VALUE}};',
                ],
            ]
        );

        $element->add_control(
            'eael_image_masking_image_repeat',
            [
                'label' => esc_html__( 'Repeat', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'no-repeat',
                'options' => [
                    'no-repeat' => esc_html__( 'No-repeat', 'essential-addons-for-elementor-lite' ),
                    'repeat'    => esc_html__( 'Repeat', 'essential-addons-for-elementor-lite' ),
                    'repeat-x'  => esc_html__( 'Repeat-x', 'essential-addons-for-elementor-lite' ),
                    'repeat-y'  => esc_html__( 'Repeat-y', 'essential-addons-for-elementor-lite' ),
                ],
                'condition' => [
                    'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'image'
				],
                'selectors' => [
                    '{{WRAPPER}} img' => 'mask-repeat: {{VALUE}}; -webkit-mask-repeat: {{VALUE}};',
                ],
            ]
        );

        if ( !apply_filters('eael/pro_enabled', false ) ) {
            $element->add_control(
                'eael_image_masking_pro_message',
                [
                    'label' => '',
                    'type' => Controls_Manager::RAW_HTML,
                    'raw' => '<div class="ea-nerd-box">
                            <div class="ea-nerd-box-message">' . __('This feature is available in the pro version.', 'essential-addons-for-elementor-lite') . '</div>
                            <a class="ea-nerd-box-link elementor-button elementor-button-default" href="https://wpdeveloper.com/upgrade/ea-pro" target="_blank">
                            ' . __('Upgrade to EA PRO', 'essential-addons-for-elementor-lite') . '
                            </a>
                        </div>',
                    'content_classes' => 'eael-pro-notice',
                    'condition' => [
                        'eael_enable_image_masking' => 'yes',
                        'eael_image_masking_type' => 'morphing'
                    ]
                ]
            );
        } else {
            do_action( 'eael/image_masking/morphing_controls', $element );
        }

		$element->end_controls_section();
	}

    private function extract_first_path_d($svg) {
        // Try DOM first
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        if (@$dom->loadXML($svg)) {
            $path = $dom->getElementsByTagName('path')->item(0);
            if ($path) {
                return $path->getAttribute('d');
            }
        }

        // Regex fallback
        if (preg_match('/<path[^>]+d="([^"]+)"/i', $svg, $match)) {
            return $match[1];
        }

        return null; // not found
    }
	public function before_render( $element ) {
		$is_enabled = $element->get_settings_for_display( 'eael_enable_image_masking' );
        $settings = $element->get_settings_for_display();

		if ( 'yes' === $is_enabled ) {
			$type = $settings['eael_image_masking_type'];
            $element_id = $element->get_id();
            $style = '';
            $element->add_render_attribute( '_wrapper', 'class', 'eael-image-masking-' . esc_attr( $element_id ) );
			if( 'clip' === $type ){
                $clip_path_value = '';
                if( 'yes' === $settings['eael_image_masking_enable_custom_clip_path'] ){
                    $clip_path_value = $settings['eael_image_masking_custom_clip_path'];
                    $clip_path_value = str_replace( 'clip-path: ', '', $clip_path_value );
                } else {
                    $clip_path = $settings['eael_image_masking_clip_path'];
                    $clip_path_value = $this->clip_paths( $clip_path );
                }
                if( $clip_path_value ) {
                    $style .= '.eael-image-masking-'. esc_html( $element_id ) .' img {clip-path: '.$clip_path_value.'}';
                }
    
                if( 'yes' === $settings['eael_image_masking_hover_effect'] ){
                    $hover_clip_path_value = '';
                    if( 'yes' === $settings['eael_image_masking_enable_custom_clip_path_hover'] ){
                        $hover_clip_path_value = $settings['eael_image_masking_custom_clip_path_hover'];
                        $hover_clip_path_value = str_replace( 'clip-path: ', '', $hover_clip_path_value );
                    } else {
                        $hover_clip_path = $settings['eael_image_masking_clip_path_hover'];
                        $hover_clip_path_value = $this->clip_paths( $hover_clip_path );
                    }
                    if( $hover_clip_path_value ) {
                        $hover_selector = $settings['eael_image_masking_hover_selector'];
                        if( $hover_selector ){
                            $hover_selector = ' ' . trim( $hover_selector );
                        }
                        $style .= '.eael-image-masking-'. esc_html( $element_id ) . $hover_selector . ':hover img {clip-path: '.$hover_clip_path_value.'}';
                    }
                    
                    $hover_selector = $settings['eael_image_masking_hover_selector'];
                    if( $hover_selector ){
                        $hover_selector = ' ' . trim( $hover_selector );
                    }
                    $style .= '.eael-image-masking-'. esc_html( $element_id ) . $hover_selector . ':hover img {clip-path: '.$hover_clip_path_value.'}';
                }
			} else if( 'image' === $type ) {
                $svg = $element->get_settings_for_display( 'eael_image_masking_svg' );
                $mask_url = '';
                if( 'upload' !== $svg ){
                    $svg_url = self::$svg_dir_url;
                    $mask_url = $svg_url . $svg . '.svg';
                } else if( 'upload' === $svg ){
                    $image = $element->get_settings_for_display( 'eael_image_masking_image' );
                    $mask_url = isset( $image['url'] ) ? $image['url'] : '';
                }

                if( $mask_url ) {
                    $style .= '.eael-image-masking-'. esc_html( $element_id ) .' img {mask-image: url('.$mask_url.'); -webkit-mask-image: url('.$mask_url.');}';
                }

                if( 'yes' === $settings['eael_image_masking_hover_effect'] ){
                    $hover_image = $element->get_settings_for_display( 'eael_image_masking_svg_hover' );
                    $hover_mask_url = '';
                    if( 'upload' !== $hover_image ){
                        $svg = $element->get_settings_for_display( 'eael_image_masking_svg' );
                        $svg_url = self::$svg_dir_url;
                        $hover_mask_url = $svg_url . $hover_image . '.svg';
                    } else if( 'upload' === $hover_image ){
                        $hover_image = $element->get_settings_for_display( 'eael_image_masking_image_hover' );
                        $hover_mask_url = $hover_image['url'] ?? '';
                    }
                    if( $hover_mask_url ) {
                        $hover_selector = $element->get_settings_for_display( 'eael_image_masking_hover_selector' );
                        if( $hover_selector ){
                            $hover_selector = ' ' . trim( $hover_selector );
                        }
                        $style .= '.eael-image-masking-'. esc_html( $element_id ) . $hover_selector .':hover img {mask-image: url('.$hover_mask_url.'); -webkit-mask-image: url('.$hover_mask_url.');}';
                    }
                }

			} else if( 'morphing' === $type ) {
                $morphing_options = apply_filters( 'eael/image_masking/morphing_options', [], $element, $element_id );
                if( !empty( $morphing_options ) ){
                    if( !empty( $morphing_options['svg_html'] ) ){
                        //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo $morphing_options['svg_html'];
                    }
                    unset( $morphing_options['svg_html'] );
                    $element->add_render_attribute( '_wrapper', 'data-morphing-options', wp_json_encode( $morphing_options ) );
                    $element->add_render_attribute( '_wrapper', 'class', 'eael-morphing-enabled' );
                }
			}
            
		
            if( $style ){
                echo '<style id="eael-image-masking-'. esc_attr( $element_id ) .'">'. esc_html( $style ) .'</style>';
            }
        }
	}
}
<?php

namespace Essential_Addons_Elementor\Extensions;

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Image_Masking {

	/**
	 * Initialize hooks
	 */
	public function __construct() {
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/frontend/before_render', [ $this, 'before_render' ], 100 );
	}

    private function clip_paths( $shape ){
        $shapes = [
            'circle'      => 'circle(50% at 50% 50%)',
            'ellipse'     => 'ellipse(50% 35% at 50% 50%)',
            'inset'       => 'inset(10% 10% 10% 10%)',
            'triangle'    => 'polygon(50% 0%, 0% 100%, 100% 100%)',
            'trapezoid'   => 'polygon(20% 0%, 80% 0%, 100% 100%, 0% 100%)',
            'parallelogram' => 'polygon(25% 0%, 100% 0%, 75% 100%, 0% 100%)',
            'rhombus'     => 'polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%)',
            'pentagon'    => 'polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%)',
            'hexagon'     => 'polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%)',
            'heptagon'    => 'polygon(50% 0%, 90% 20%, 100% 60%, 75% 100%, 25% 100%, 0% 60%, 10% 20%)',
            'octagon'     => 'polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%)',
            'nonagon'     => 'polygon(50% 0%, 85% 15%, 100% 50%, 85% 85%, 50% 100%, 15% 85%, 0% 50%, 15% 15%)',
            'decagon'     => 'polygon(50% 0%, 80% 10%, 100% 40%, 95% 80%, 65% 100%, 35% 100%, 5% 80%, 0% 40%, 20% 10%)',
            'star'        => 'polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%)',
            'cross'       => 'polygon(30% 0%, 70% 0%, 70% 30%, 100% 30%, 100% 70%, 70% 70%, 70% 100%, 30% 100%, 30% 70%, 0% 70%, 0% 30%, 30% 30%)',
            'arrow'       => 'polygon(0% 40%, 60% 40%, 60% 20%, 100% 50%, 60% 80%, 60% 60%, 0% 60%)',
            'left_arrow'  => 'polygon(100% 40%, 40% 40%, 40% 20%, 0% 50%, 40% 80%, 40% 60%, 100% 60%)',
            'chevron'     => 'polygon(25% 0%, 100% 50%, 25% 100%, 0% 75%, 50% 50%, 0% 25%)',
            'message'     => 'polygon(0% 0%, 100% 0%, 100% 75%, 75% 75%, 50% 100%, 50% 75%, 0% 75%)',
            'close'       => 'polygon(20% 0%, 50% 30%, 80% 0%, 100% 20%, 70% 50%, 100% 80%, 80% 100%, 50% 70%, 20% 100%, 0% 80%, 30% 50%, 0% 20%)',
            'frame'       => 'polygon(0% 0%, 0% 100%, 25% 100%, 25% 25%, 75% 25%, 75% 75%, 25% 75%, 25% 100%, 100% 100%, 100% 0%)',
            'rabbet'      => 'polygon(20% 0%, 80% 0%, 80% 20%, 100% 20%, 100% 80%, 80% 80%, 80% 100%, 20% 100%, 20% 80%, 0% 80%, 0% 20%, 20% 20%)',
            'starburst'   => 'polygon(50% 0%, 60% 20%, 80% 10%, 70% 30%, 90% 50%, 70% 70%, 80% 90%, 60% 80%, 50% 100%, 40% 80%, 20% 90%, 30% 70%, 10% 50%, 30% 30%, 20% 10%, 40% 20%)',
            'blob'        => 'polygon(50% 0%, 80% 10%, 100% 40%, 90% 70%, 60% 100%, 30% 90%, 10% 60%, 0% 30%, 20% 10%)',
        ];
        return $shapes[$shape] ?? '';
    }

    private function get_clip_path_options() {
        return [
            'circle'        => '⚪ ' . esc_html__( 'Circle', 'essential-addons-for-elementor-lite' ),
            'ellipse'       => '🟣 ' . esc_html__( 'Ellipse', 'essential-addons-for-elementor-lite' ),
            'inset'         => '⬛ ' . esc_html__( 'Inset', 'essential-addons-for-elementor-lite' ),
            'triangle'      => '🔺 ' . esc_html__( 'Triangle', 'essential-addons-for-elementor-lite' ),
            'trapezoid'     => '🟪 ' . esc_html__( 'Trapezoid', 'essential-addons-for-elementor-lite' ),
            'parallelogram' => '📐 ' . esc_html__( 'Parallelogram', 'essential-addons-for-elementor-lite' ),
            'rhombus'       => '🔷 ' . esc_html__( 'Rhombus', 'essential-addons-for-elementor-lite' ),
            'pentagon'      => '🔺 ' . esc_html__( 'Pentagon', 'essential-addons-for-elementor-lite' ),
            'hexagon'       => '⬡ ' . esc_html__( 'Hexagon', 'essential-addons-for-elementor-lite' ),
            'heptagon'      => '⬣ ' . esc_html__( 'Heptagon', 'essential-addons-for-elementor-lite' ),
            'octagon'       => '🛑 ' . esc_html__( 'Octagon', 'essential-addons-for-elementor-lite' ),
            'nonagon'       => '🔷 ' . esc_html__( 'Nonagon', 'essential-addons-for-elementor-lite' ),
            'decagon'       => '🔟 ' . esc_html__( 'Decagon', 'essential-addons-for-elementor-lite' ),
            'star'          => '⭐ ' . esc_html__( 'Star', 'essential-addons-for-elementor-lite' ),
            'cross'         => '✝️ ' . esc_html__( 'Cross', 'essential-addons-for-elementor-lite' ),
            'arrow'         => '➡️ ' . esc_html__( 'Arrow', 'essential-addons-for-elementor-lite' ),
            'left_arrow'    => '⬅️ ' . esc_html__( 'Left Arrow', 'essential-addons-for-elementor-lite' ),
            'chevron'       => '💠 ' . esc_html__( 'Chevron', 'essential-addons-for-elementor-lite' ),
            'message'       => '💬 ' . esc_html__( 'Message', 'essential-addons-for-elementor-lite' ),
            'close'         => '❌ ' . esc_html__( 'Close', 'essential-addons-for-elementor-lite' ),
            'frame'         => '🖼️ ' . esc_html__( 'Frame', 'essential-addons-for-elementor-lite' ),
            'rabbet'        => '🧩 ' . esc_html__( 'Rabbet', 'essential-addons-for-elementor-lite' ),
            'starburst'     => '🌟 ' . esc_html__( 'Starburst', 'essential-addons-for-elementor-lite' ),
            'blob'          => '💧 ' . esc_html__( 'Blob', 'essential-addons-for-elementor-lite' ),
            'custom'        => '✏️ ' . esc_html__( 'Custom', 'essential-addons-for-elementor-lite' ),
        ];

    }

    private function masking_controllers( $element, $tab = '' ) {

        $element->add_control(
            'eael_image_masking_clip_path' . $tab,
            [
                'label'       => esc_html__( 'Select Clip Path', 'essential-addons-for-elementor-lite' ),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'options'     => $this->get_clip_path_options(),
                'default'     => 'circle',
                'condition'   => [
					'eael_image_masking_type' => 'clip'
				]
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
                'condition'   => [
					'eael_image_masking_type'      => 'clip',
					'eael_image_masking_clip_path' . $tab => 'custom'
				],
            ]
        );

		$element->add_control(
			'eael_image_masking_image' . $tab,
			[
				'label'     => '',
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => [
					'active' => false,
				],
                'ai' => [
					'active' => false,
				],
				'condition' => [
					'eael_image_masking_type' => 'image'
				]
			]
		);

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
                    'placeholder' => '.eael-image-masking-hover-selector',
                    'description' => __( 'Enter the selector for the element you want to apply the hover effect on. If you leave this field empty, the hover effect will be applied on hover for full section.', 'essential-addons-for-elementor-lite' ),
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
                ],
                'condition' => [
					'eael_enable_image_masking' => 'yes'
				],
                'default' => 'clip',
                'toggle' => true,
            ]
        );

        $element->start_controls_tabs( 'eael_image_masking_tabs', [
            'condition' => [
                'eael_enable_image_masking' => 'yes'
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
                'default' => '',
                'options' => [
                    ''        => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
                    'auto'    => esc_html__( 'Auto', 'essential-addons-for-elementor-lite' ),
                    'cover'   => esc_html__( 'Cover', 'essential-addons-for-elementor-lite' ),
                    'contain' => esc_html__( 'Contain', 'essential-addons-for-elementor-lite' ),
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
                'label' => esc_html__( 'Reapeat', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'no-repeat',
                'options' => [
                    'no-repeat' => esc_html__( 'No-repeat', 'essential-addons-for-elementor-lite' ),
                    'repeat'    => esc_html__( 'Repeat', 'essential-addons-for-elementor-lite' ),
                    'repeat-x'  => esc_html__( 'Repeat-x', 'essential-addons-for-elementor-lite' ),
                    'repeat-y'  => esc_html__( 'Repeat-y', 'essential-addons-for-elementor-lite' ),
                ],
                'condition' => [
					'eael_image_masking_type' => 'image'
				],
                'selectors' => [
                    '{{WRAPPER}} img' => 'mask-repeat: {{VALUE}}; -webkit-mask-repeat: {{VALUE}};',
                ],
            ]
        );

		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$is_enabled = $element->get_settings_for_display( 'eael_enable_image_masking' );

		if ( 'yes' === $is_enabled ) {
			$type = $element->get_settings_for_display( 'eael_image_masking_type' );
            $element_id = $element->get_id();
            $style = '';
            $element->add_render_attribute( '_wrapper', 'class', 'eael-image-masking-' . $element_id );
			if( 'clip' === $type ){
                $clip_path = $element->get_settings_for_display( 'eael_image_masking_clip_path' );
                $clip_path_value = $this->clip_paths( $clip_path );
                if( 'custom' === $clip_path ){
                    $clip_path_value = $element->get_settings_for_display( 'eael_image_masking_custom_clip_path' );
                    $clip_path_value = str_replace( 'clip-path: ', '', $clip_path_value );
                }
                if( $clip_path_value ) {
                    $style .= '.eael-image-masking-'.$element_id.' img {clip-path: '.$clip_path_value.'}';
                }

                $hover_clip_path = $element->get_settings_for_display( 'eael_image_masking_clip_path_hover' );
                $hover_clip_path_value = $this->clip_paths( $hover_clip_path );
                if( 'custom' === $hover_clip_path ){
                    $hover_clip_path_value = $element->get_settings_for_display( 'eael_image_masking_custom_clip_path_hover' );
                    $hover_clip_path_value = str_replace( 'clip-path: ', '', $hover_clip_path_value );
                }
                if( $hover_clip_path_value ) {
                    $hover_selector = $element->get_settings_for_display( 'eael_image_masking_hover_selector' );
                    if( $hover_selector ){
                        $hover_selector = ' ' . trim( $hover_selector );
                    }
                    $style .= '.eael-image-masking-'.$element_id.$hover_selector.':hover img {clip-path: '.$hover_clip_path_value.'}';
                }
			} else if( 'image' === $type ) {
                $image = $element->get_settings_for_display( 'eael_image_masking_image' );
                if( $image['url'] ) {
                    $style .= '.eael-image-masking-'.$element_id.' img {mask-image: url('.$image['url'].'); -webkit-mask-image: url('.$image['url'].');}';
                }
                $hover_image = $element->get_settings_for_display( 'eael_image_masking_image_hover' );
                if( $hover_image['url'] ) {
                    $hover_selector = $element->get_settings_for_display( 'eael_image_masking_hover_selector' );
                    if( $hover_selector ){
                        $hover_selector = ' ' . trim( $hover_selector );
                    }
                    $style .= '.eael-image-masking-'.$element_id. $hover_selector .':hover img {mask-image: url('.$hover_image['url'].'); -webkit-mask-image: url('.$hover_image['url'].');}';
                }
			}
		
            if( $style ){
                echo '<style id="eael-image-masking-'.$element_id.'">'.$style.'</style>';
            }
        }
	}
}
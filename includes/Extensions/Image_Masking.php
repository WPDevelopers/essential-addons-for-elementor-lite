<?php

namespace Essential_Addons_Elementor\Extensions;

use Elementor\Controls_Manager;
use Elementor\Repeater;


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

        $element->add_control(
            'eael_morphing_type',
            [
                'label' => esc_html__( 'Morphing Type', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'clip-path' => [
                        'title' => esc_html__( 'Clip Path', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-integration',
                    ],
                    'svg' => [
                        'title' => esc_html__( 'SVG', 'essential-addons-for-elementor-lite' ),
                        'icon' => 'eicon-image',
                    ],
                ],
                'default' => 'clip-path',
                'toggle' => false,
                'condition' => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing'
				],
            ]
        );

        $clip_paths = new Repeater();
        $clip_paths->add_control(
            'eael_clip_path_title',
            [
                'label' => esc_html__( 'Shape Title', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Shape 1',
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $clip_paths->add_control(
            'eael_clip_path',
            [
                'label' => esc_html__( 'Clip Path', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::TEXTAREA,
                'ai' => [
                    'active' => false,
                ],
                'default' => 'clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);',
            ]
        );
        
        $element->add_control(
            'eael_clip_paths',
            [
                'label' => esc_html__( 'Shapes', 'text-domain' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $clip_paths->get_controls(),
                'default' => [
                    [
                        'eael_clip_path_title' => 'Shape 1',
                        'eael_clip_path' => 'clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);',
                    ],
                    [
                        'eael_clip_path_title' => 'Shape 2',
                        'eael_clip_path' => 'clip-path: polygon(11% 0, 89% 0, 100% 50%, 90% 100%, 12% 99%, 0% 50%);
',
                    ],
                    [
                        'eael_clip_path_title' => 'Shape 3',
                        'eael_clip_path' => 'clip-path: polygon(11% 0, 89% 0, 73% 50%, 90% 100%, 12% 99%, 24% 50%);',
                    ],
                    [
                        'eael_clip_path_title' => 'Shape 4',
                        'eael_clip_path' => 'clip-path: polygon(11% 0, 89% 0, 100% 37%, 90% 100%, 12% 99%, 0 70%);',
                    ]
                ],
                'title_field' => '{{{ eael_clip_path_title }}}',
                'condition' => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing',
					'eael_morphing_type' => 'clip-path'
				],
            ]
        );

        $svg_paths = new Repeater();
        $svg_paths->add_control(
            'eael_svg_path_title',
            [
                'label' => esc_html__( 'SVG Title', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'SVG 1',
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $svg_paths->add_control(
            'eael_svg_path',
            [
                'label' => esc_html__( 'SVG Path', 'essential-addons-for-elementor-lite' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( '', 'essential-addons-for-elementor-lite' ),
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $element->add_control(
            'eael_svg_paths',
            [
                'label' => esc_html__( 'Paths', 'text-domain' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $svg_paths->get_controls(),
                'default' => [
                    [
                        'eael_svg_path_title' => 'Path 1',
                        'eael_svg_path' => '<path d="M10 10 H 90 V 90 H 10 Z" />',
                    ]
                ],
                'title_field' => '{{{ eael_svg_path_title }}}',
                'condition' => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing',
					'eael_morphing_type' => 'svg'
				],
            ]
        );

        $element->add_control(
            'eael_blob_animation_heading',
            [
                'label' => __('🎬 Morphing Animation Settings', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'blob'
				],
            ]
        );

        $element->add_control(
            'eael_blob_animation_enable',
            [
                'label'        => __('Enable Blob Animation', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'no',
                'description'  => __('Enable morphing blob animation effects for dynamic visual appeal.', 'essential-addons-for-elementor-lite'),
                'condition'    => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'blob'
				],
            ]
        );

        $element->add_control(
            'eael_blob_animation_duration',
            [
                'label' => __('Animation Duration (seconds)', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                        'step' => 0.5,
                    ],
                ],
                'default' => [
                    'size' => 4,
                ],
                'condition' => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing',
					'eael_blob_animation_enable' => 'yes'
				],
            ]
        );

        $element->add_control(
            'eael_blob_animation_easing',
            [
                'label' => __('Animation Easing', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'power2.inOut',
                'options' => [
                    'none'          => __('None', 'essential-addons-for-elementor-lite'),
                    'power1.out'    => __('Power1 Out', 'essential-addons-for-elementor-lite'),
                    'power1.in'     => __('Power1 In', 'essential-addons-for-elementor-lite'),
                    'power1.inOut'  => __('Power1 InOut', 'essential-addons-for-elementor-lite'),
                    'power2.out'    => __('Power2 Out', 'essential-addons-for-elementor-lite'),
                    'power2.in'     => __('Power2 In', 'essential-addons-for-elementor-lite'),
                    'power2.inOut'  => __('Power2 InOut', 'essential-addons-for-elementor-lite'),
                    'power3.out'    => __('Power3 Out', 'essential-addons-for-elementor-lite'),
                    'power3.in'     => __('Power3 In', 'essential-addons-for-elementor-lite'),
                    'power3.inOut'  => __('Power3 InOut', 'essential-addons-for-elementor-lite'),
                    'back.out'      => __('Back Out', 'essential-addons-for-elementor-lite'),
                    'back.in'       => __('Back In', 'essential-addons-for-elementor-lite'),
                    'back.inOut'    => __('Back InOut', 'essential-addons-for-elementor-lite'),
                    'elastic.out'   => __('Elastic Out', 'essential-addons-for-elementor-lite'),
                    'elastic.in'    => __('Elastic In', 'essential-addons-for-elementor-lite'),
                    'elastic.inOut' => __('Elastic InOut', 'essential-addons-for-elementor-lite'),
                    'bounce.out'    => __('Bounce Out', 'essential-addons-for-elementor-lite'),
                    'bounce.in'     => __('Bounce In', 'essential-addons-for-elementor-lite'),
                    'bounce.inOut'  => __('Bounce InOut', 'essential-addons-for-elementor-lite'),
                ],
                'condition' => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing',
					'eael_blob_animation_enable' => 'yes'
				],
            ]
        );

        $element->add_control(
            'eael_blob_animation_loop',
            [
                'label'        => __('Loop Animation', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing',
					'eael_blob_animation_enable' => 'yes'
				],
            ]
        );

        $element->add_control(
            'eael_blob_animation_scale_min',
            [
                'label' => __('Scale Min', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 100,
                ],
                'condition' => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing',
					'eael_blob_animation_enable' => 'yes'
				],
            ]
        );

        $element->add_control(
            'eael_blob_animation_scale_max',
            [
                'label' => __('Scale Max', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 100,
                ],
                'condition' => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing',
					'eael_blob_animation_enable' => 'yes'
				],
            ]
        );

        $element->add_control(
            'eael_blob_animation_rotation',
            [
                'label'        => __('Enable Rotation', 'essential-addons-for-elementor-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'essential-addons-for-elementor-lite'),
                'label_off'    => __('No', 'essential-addons-for-elementor-lite'),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing',
					'eael_blob_animation_enable' => 'yes'
				],
            ]
        );

        $element->add_control(
            'eael_blob_animation_rotation_speed',
            [
                'label' => __('Rotation Speed (degrees)', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 90,
                        'max' => 720,
                        'step' => 90,
                    ],
                ],
                'default' => [
                    'size' => 360,
                ],
                'condition' => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing',
					'eael_blob_animation_enable' => 'yes',
					'eael_blob_animation_rotation' => 'yes'
				],
            ]
        );

        $element->add_control(
            'eael_blob_animation_shape_type',
            [
                'label' => __('Shape Type', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::SELECT,
                'default' => 'polygon',
                'options' => [
                    'polygon' => __('Polygon (CSS Clip-path)', 'essential-addons-for-elementor-lite'),
                    'path' => __('Path (SVG)', 'essential-addons-for-elementor-lite'),
                ],
                'description' => __('Polygon uses CSS clip-path for better performance, Path uses SVG for more complex shapes.', 'essential-addons-for-elementor-lite'),
                'condition' => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing',
					'eael_blob_animation_enable' => 'yes'
				],
            ]
        );

        $element->add_control(
            'eael_blob_animation_advanced_heading',
            [
                'label' => __('⚙️ Advanced Settings', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing',
					'eael_blob_animation_enable' => 'yes'
				],
            ]
        );

        $element->add_control(
            'eael_blob_animation_custom_shapes',
            [
                'label' => __('Custom Blob Shapes', 'essential-addons-for-elementor-lite'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 6,
                'placeholder' => __('Enter custom polygon shapes, one per line. Example:
polygon(30% 40%, 70% 30%, 80% 70%, 20% 80%)
polygon(40% 20%, 80% 40%, 60% 80%, 10% 60%)', 'essential-addons-for-elementor-lite'),
                'description' => __('Add custom polygon shapes for morphing animation. Leave empty to use default shapes.', 'essential-addons-for-elementor-lite'),
                'condition' => [
					'eael_enable_image_masking' => 'yes',
					'eael_image_masking_type' => 'morphing',
					'eael_blob_animation_enable' => 'yes',
					'eael_blob_animation_shape_type' => 'polygon'
				],
            ]
        );

		$element->end_controls_section();
	}

	public function before_render( $element ) {
		$is_enabled = $element->get_settings_for_display( 'eael_enable_image_masking' );
        $settings = $element->get_settings_for_display();

		if ( 'yes' === $is_enabled ) {
			$type = $settings['eael_image_masking_type'];
            $element_id = $element->get_id();
            $style = '';
            $element->add_render_attribute( '_wrapper', 'class', 'eael-image-masking-' . $element_id );
			if( 'clip' === $type ){
                $clip_path = $settings['eael_image_masking_clip_path'];
                $clip_path_value = $this->clip_paths( $clip_path );
                if( 'custom' === $clip_path ){
                    $clip_path_value = $settings['eael_image_masking_custom_clip_path'];
                    $clip_path_value = str_replace( 'clip-path: ', '', $clip_path_value );
                }
                if( $clip_path_value ) {
                    $style .= '.eael-image-masking-'.$element_id.' img {clip-path: '.$clip_path_value.'}';
                }

                $hover_clip_path = $settings['eael_image_masking_clip_path_hover'];
                $hover_clip_path_value = $this->clip_paths( $hover_clip_path );
                if( 'custom' === $hover_clip_path ){
                    $hover_clip_path_value = $settings['eael_image_masking_custom_clip_path_hover'];
                    $hover_clip_path_value = str_replace( 'clip-path: ', '', $hover_clip_path_value );
                }
                if( $hover_clip_path_value ) {
                    $hover_selector = $settings['eael_image_masking_hover_selector'];
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
			} else if( 'morphing' === $type ) {
                $morphing_type = $settings['eael_morphing_type'];
                $paths = [];
                if( 'clip-path' === $morphing_type ){
                    $clip_paths = $settings['eael_clip_paths'];
                    foreach( $clip_paths as $clip_path ){
                        $paths[] = str_replace( [ 'clip-path: ', ';' ], '', $clip_path['eael_clip_path'] );
                    }
                }
                $morphing_options = [
                    'shapes'        => base64_encode( wp_json_encode( $paths ) ),
                    'duration'      => $settings['eael_blob_animation_duration'],
                    'loop'          => 'yes' === $settings['eael_blob_animation_loop'],
                    'scaleMin'      => $settings['eael_blob_animation_scale_min'] / 100,
                    'scaleMax'      => $settings['eael_blob_animation_scale_max'] / 100,
                    'rotation'      => 'yes' === $settings['eael_blob_animation_rotation'],
                    'rotationSpeed' => $settings['eael_blob_animation_rotation_speed'],
                    'shapeType'     => $settings['eael_blob_animation_shape_type'],
                ];

                $element->add_render_attribute( '_wrapper', 'data-morphing-options', wp_json_encode( $morphing_options ) );
                $element->add_render_attribute( '_wrapper', 'class', 'eael-morphing-enabled' );
			}
            
		
            if( $style ){
                echo '<style id="eael-image-masking-'.$element_id.'">'.$style.'</style>';
            }
        }
	}
}
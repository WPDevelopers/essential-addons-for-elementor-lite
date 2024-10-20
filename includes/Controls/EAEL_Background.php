<?php

namespace Essential_Addons_Elementor\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * EAEL background control.
 *
 * A base control for creating background control. Displays input fields to define
 * the background color, background image, background gradient or background video.
 *
 * @since 1.2.2
 */
class EAEL_Background extends Group_Control_Background {

	/**
	 * Fields.
	 *
	 * Holds all the background control fields.
	 *
	 * @since 1.2.2
	 * @access protected
	 * @static
	 *
	 * @var array Background control fields.
	 */
	protected static $fields;

	/**
	 * Get background control type.
	 *
	 * Retrieve the control type, in this case `background`.
	 *
	 * @return string Control type.
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 */
	public static function get_type() {
		return 'eael-background';
	}

	/**
	 * Init fields.
	 *
	 * Initialize background control fields.
	 *
	 * @return array Control fields.
	 * @since 1.2.2
	 * @access public
	 *
	 */
	public function init_fields() {
		$fields = [];

		$fields['background'] = [
			'label'       => esc_html_x( 'Background Type', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::CHOOSE,
			'render_type' => 'ui',
		];

		$fields['color'] = [
			'label'     => esc_html_x( 'Color', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'title'     => esc_html_x( 'Background Color', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'selectors' => [
				'{{SELECTOR}}' => 'background: {{VALUE}};',
			],
			'condition' => [
				'background' => [ 'classic', 'gradient', 'video' ],
			],
		];

		$fields['color_stop'] = [
			'label'       => esc_html_x( 'Location', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ '%', 'custom' ],
			'default'     => [
				'unit' => '%',
				'size' => 0,
			],
			'render_type' => 'ui',
			'condition'   => [
				'background' => [ 'gradient' ],
			],
			'of_type'     => 'gradient',
		];

		$fields['color_b'] = [
			'label'       => esc_html_x( 'Second Color', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::COLOR,
			'default'     => '#f2295b',
			'render_type' => 'ui',
			'condition'   => [
				'background' => [ 'gradient' ],
			],
			'of_type'     => 'gradient',
		];

		$fields['color_b_stop'] = [
			'label'       => esc_html_x( 'Location', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ '%', 'custom' ],
			'default'     => [
				'unit' => '%',
				'size' => 100,
			],
			'render_type' => 'ui',
			'condition'   => [
				'background' => [ 'gradient' ],
			],
			'of_type'     => 'gradient',
		];

		$fields['gradient_type'] = [
			'label'       => esc_html_x( 'Type', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => [
				'linear' => esc_html_x( 'Linear', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'radial' => esc_html_x( 'Radial', 'Background Control', 'essential-addons-for-elementor-lite' ),
			],
			'default'     => 'linear',
			'render_type' => 'ui',
			'condition'   => [
				'background' => [ 'gradient' ],
			],
			'of_type'     => 'gradient',
		];

		$fields['gradient_angle'] = [
			'label'      => esc_html_x( 'Angle', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'deg', 'grad', 'rad', 'turn', 'custom' ],
			'default'    => [
				'unit' => 'deg',
				'size' => 180,
			],
			'selectors'  => [
				'{{SELECTOR}}' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			],
			'condition'  => [
				'background'    => [ 'gradient' ],
				'gradient_type' => 'linear',
			],
			'of_type'    => 'gradient',
		];

		$fields['gradient_position'] = [
			'label'     => esc_html_x( 'Position', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'center center' => esc_html_x( 'Center Center', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'center left'   => esc_html_x( 'Center Left', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'center right'  => esc_html_x( 'Center Right', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'top center'    => esc_html_x( 'Top Center', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'top left'      => esc_html_x( 'Top Left', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'top right'     => esc_html_x( 'Top Right', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'bottom center' => esc_html_x( 'Bottom Center', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'bottom left'   => esc_html_x( 'Bottom Left', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'bottom right'  => esc_html_x( 'Bottom Right', 'Background Control', 'essential-addons-for-elementor-lite' ),
			],
			'default'   => 'center center',
			'selectors' => [
				'{{SELECTOR}}' => 'background-color: transparent; background-image: radial-gradient(at {{VALUE}}, {{color.VALUE}} {{color_stop.SIZE}}{{color_stop.UNIT}}, {{color_b.VALUE}} {{color_b_stop.SIZE}}{{color_b_stop.UNIT}})',
			],
			'condition' => [
				'background'    => [ 'gradient' ],
				'gradient_type' => 'radial',
			],
			'of_type'   => 'gradient',
		];

		$fields['image'] = [
			'label'       => esc_html_x( 'Image', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::MEDIA,
			'ai'          => [
				'category' => 'background',
			],
			'dynamic'     => [
				'active' => true,
			],
			'responsive'  => true,
			'title'       => esc_html_x( 'Background Image', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'selectors'   => [
				'{{SELECTOR}}' => 'background-image: url("{{URL}}");',
			],
			'has_sizes'   => true,
			'render_type' => 'template',
			'condition'   => [
				'background' => [ 'classic' ],
			],
		];

		$fields['position'] = [
			'label'      => esc_html_x( 'Position', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SELECT,
			'default'    => '',
			'separator'  => 'before',
			'responsive' => true,
			'options'    => [
				''              => esc_html_x( 'Default', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'center center' => esc_html_x( 'Center Center', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'center left'   => esc_html_x( 'Center Left', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'center right'  => esc_html_x( 'Center Right', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'top center'    => esc_html_x( 'Top Center', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'top left'      => esc_html_x( 'Top Left', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'top right'     => esc_html_x( 'Top Right', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'bottom center' => esc_html_x( 'Bottom Center', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'bottom left'   => esc_html_x( 'Bottom Left', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'bottom right'  => esc_html_x( 'Bottom Right', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'initial'       => esc_html_x( 'Custom', 'Background Control', 'essential-addons-for-elementor-lite' ),

			],
			'selectors'  => [
				'{{SELECTOR}}' => 'background-position: {{VALUE}};',
			],
			'condition'  => [
				'background'  => [ 'classic' ],
				'image[url]!' => '',
			],
		];

		$fields['xpos'] = [
			'label'          => esc_html_x( 'X Position', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'           => Controls_Manager::SLIDER,
			'responsive'     => true,
			'size_units'     => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
			'default'        => [
				'size' => 0,
			],
			'tablet_default' => [
				'size' => 0,
			],
			'mobile_default' => [
				'size' => 0,
			],
			'range'          => [
				'px' => [
					'min' => - 800,
					'max' => 800,
				],
				'em' => [
					'min' => - 100,
					'max' => 100,
				],
				'%'  => [
					'min' => - 100,
					'max' => 100,
				],
				'vw' => [
					'min' => - 100,
					'max' => 100,
				],
			],
			'selectors'      => [
				'{{SELECTOR}}' => 'background-position: {{SIZE}}{{UNIT}} {{ypos.SIZE}}{{ypos.UNIT}}',
			],
			'condition'      => [
				'background'  => [ 'classic' ],
				'position'    => [ 'initial' ],
				'image[url]!' => '',
			],
			'required'       => true,
		];

		$fields['ypos'] = [
			'label'          => esc_html_x( 'Y Position', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'           => Controls_Manager::SLIDER,
			'responsive'     => true,
			'size_units'     => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
			'default'        => [
				'size' => 0,
			],
			'tablet_default' => [
				'size' => 0,
			],
			'mobile_default' => [
				'size' => 0,
			],
			'range'          => [
				'px' => [
					'min' => - 800,
					'max' => 800,
				],
				'em' => [
					'min' => - 100,
					'max' => 100,
				],
				'%'  => [
					'min' => - 100,
					'max' => 100,
				],
				'vh' => [
					'min' => - 100,
					'max' => 100,
				],
			],
			'selectors'      => [
				'{{SELECTOR}}' => 'background-position: {{xpos.SIZE}}{{xpos.UNIT}} {{SIZE}}{{UNIT}}',
			],
			'condition'      => [
				'background'  => [ 'classic' ],
				'position'    => [ 'initial' ],
				'image[url]!' => '',
			],
			'required'       => true,
		];

		$fields['attachment'] = [
			'label'     => esc_html_x( 'Attachment', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => [
				''       => esc_html_x( 'Default', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'scroll' => esc_html_x( 'Scroll', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'fixed'  => esc_html_x( 'Fixed', 'Background Control', 'essential-addons-for-elementor-lite' ),
			],
			'selectors' => [
				'(desktop+){{SELECTOR}}' => 'background-attachment: {{VALUE}};',
			],
			'condition' => [
				'background'  => [ 'classic' ],
				'image[url]!' => '',
			],
		];

		$fields['attachment_alert'] = [
			'type'            => Controls_Manager::RAW_HTML,
			'content_classes' => 'elementor-control-field-description',
			'raw'             => esc_html__( 'Note: Attachment Fixed works only on desktop.', 'essential-addons-for-elementor-lite' ),
			'separator'       => 'none',
			'condition'       => [
				'background'  => [ 'classic' ],
				'image[url]!' => '',
				'attachment'  => 'fixed',
			],
		];

		$fields['repeat'] = [
			'label'      => esc_html_x( 'Repeat', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SELECT,
			'default'    => '',
			'responsive' => true,
			'options'    => [
				''          => esc_html_x( 'Default', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'no-repeat' => esc_html_x( 'No-repeat', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'repeat'    => esc_html_x( 'Repeat', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'repeat-x'  => esc_html_x( 'Repeat-x', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'repeat-y'  => esc_html_x( 'Repeat-y', 'Background Control', 'essential-addons-for-elementor-lite' ),
			],
			'selectors'  => [
				'{{SELECTOR}}' => 'background-repeat: {{VALUE}};',
			],
			'condition'  => [
				'background'  => [ 'classic' ],
				'image[url]!' => '',
			],
		];

		$fields['size'] = [
			'label'      => esc_html_x( 'Display Size', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SELECT,
			'responsive' => true,
			'default'    => '',
			'options'    => [
				''        => esc_html_x( 'Default', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'auto'    => esc_html_x( 'Auto', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'cover'   => esc_html_x( 'Cover', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'contain' => esc_html_x( 'Contain', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'initial' => esc_html_x( 'Custom', 'Background Control', 'essential-addons-for-elementor-lite' ),
			],
			'selectors'  => [
				'{{SELECTOR}}' => 'background-size: {{VALUE}};',
			],
			'condition'  => [
				'background'  => [ 'classic' ],
				'image[url]!' => '',
			],
		];

		$fields['bg_width'] = [
			'label'      => esc_html_x( 'Width', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SLIDER,
			'responsive' => true,
			'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 1000,
				],
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'vw' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default'    => [
				'size' => 100,
				'unit' => '%',
			],
			'required'   => true,
			'selectors'  => [
				'{{SELECTOR}}' => 'background-size: {{SIZE}}{{UNIT}} auto',

			],
			'condition'  => [
				'background'  => [ 'classic' ],
				'size'        => [ 'initial' ],
				'image[url]!' => '',
			],
		];

		$fields['video_link'] = [
			'label'              => esc_html_x( 'Video Link', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::TEXT,
			'placeholder'        => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
			'description'        => esc_html__( 'YouTube/Vimeo link, or link to video file (mp4 is recommended).', 'essential-addons-for-elementor-lite' ),
			'label_block'        => true,
			'default'            => '',
			'dynamic'            => [
				'active'     => true,
				'categories' => [
					TagsModule::POST_META_CATEGORY,
					TagsModule::URL_CATEGORY,
				],
			],
			'condition'          => [
				'background' => [ 'video' ],
			],
			'of_type'            => 'video',
			'frontend_available' => true,
		];

		$fields['video_start'] = [
			'label'              => esc_html__( 'Start Time', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::NUMBER,
			'description'        => esc_html__( 'Specify a start time (in seconds)', 'essential-addons-for-elementor-lite' ),
			'placeholder'        => 10,
			'condition'          => [
				'background' => [ 'video' ],
			],
			'of_type'            => 'video',
			'frontend_available' => true,
		];

		$fields['video_end'] = [
			'label'              => esc_html__( 'End Time', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::NUMBER,
			'description'        => esc_html__( 'Specify an end time (in seconds)', 'essential-addons-for-elementor-lite' ),
			'placeholder'        => 70,
			'condition'          => [
				'background' => [ 'video' ],
			],
			'of_type'            => 'video',
			'frontend_available' => true,
		];

		$fields['play_once'] = [
			'label'              => esc_html__( 'Play Once', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SWITCHER,
			'condition'          => [
				'background' => [ 'video' ],
			],
			'of_type'            => 'video',
			'frontend_available' => true,
		];

		$fields['play_on_mobile'] = [
			'label'              => esc_html__( 'Play On Mobile', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SWITCHER,
			'condition'          => [
				'background' => [ 'video' ],
			],
			'of_type'            => 'video',
			'frontend_available' => true,
		];

		// This control was added to handle a bug with the Youtube Embed API. The bug: If there is a video with Privacy
		// Mode on, and at the same time the page contains another video WITHOUT privacy mode on, one of the videos
		// will not run properly. This added control allows users to align all their videos to one host (either
		// youtube.com or youtube-nocookie.com, depending on whether the user wants privacy mode on or not).
		$fields['privacy_mode'] = [
			'label'              => esc_html__( 'Privacy Mode', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SWITCHER,
			'condition'          => [
				'background' => [ 'video' ],
			],
			'of_type'            => 'video',
			'frontend_available' => true,
		];

		$fields['video_fallback'] = [
			'label'       => esc_html_x( 'Background Fallback', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'description' => esc_html__( 'This cover image will replace the background video in case that the video could not be loaded.', 'essential-addons-for-elementor-lite' ),
			'type'        => Controls_Manager::MEDIA,
			'dynamic'     => [
				'active' => true,
			],
			'condition'   => [
				'background' => [ 'video' ],
			],
			'selectors'   => [
				'{{SELECTOR}}' => 'background: url("{{URL}}") 50% 50%; background-size: cover;',
			],
			'of_type'     => 'video',
		];

		$fields['slideshow_gallery'] = [
			'label'              => esc_html_x( 'Images', 'Background Control', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::GALLERY,
			'condition'          => [
				'background' => [ 'slideshow' ],
			],
			'show_label'         => false,
			'of_type'            => 'slideshow',
			'frontend_available' => true,
		];

		$fields['slideshow_loop'] = [
			'label'              => esc_html__( 'Infinite Loop', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SWITCHER,
			'default'            => 'yes',
			'condition'          => [
				'background' => [ 'slideshow' ],
			],
			'of_type'            => 'slideshow',
			'frontend_available' => true,
		];

		$fields['slideshow_slide_duration'] = [
			'label'              => esc_html__( 'Duration', 'essential-addons-for-elementor-lite' ) . ' (ms)',
			'type'               => Controls_Manager::NUMBER,
			'default'            => 5000,
			'condition'          => [
				'background' => [ 'slideshow' ],
			],
			'frontend_available' => true,
		];

		$fields['slideshow_slide_transition'] = [
			'label'              => esc_html__( 'Transition', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SELECT,
			'default'            => 'fade',
			'options'            => [
				'fade'        => 'Fade',
				'slide_right' => 'Slide Right',
				'slide_left'  => 'Slide Left',
				'slide_up'    => 'Slide Up',
				'slide_down'  => 'Slide Down',
			],
			'condition'          => [
				'background' => [ 'slideshow' ],
			],
			'of_type'            => 'slideshow',
			'frontend_available' => true,
		];

		$fields['slideshow_transition_duration'] = [
			'label'              => esc_html__( 'Transition Duration', 'essential-addons-for-elementor-lite' ) . ' (ms)',
			'type'               => Controls_Manager::NUMBER,
			'default'            => 500,
			'condition'          => [
				'background' => [ 'slideshow' ],
			],
			'frontend_available' => true,
		];

		$fields['slideshow_background_size'] = [
			'label'      => esc_html__( 'Background Size', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SELECT,
			'responsive' => true,
			'default'    => '',
			'options'    => [
				''        => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
				'auto'    => esc_html__( 'Auto', 'essential-addons-for-elementor-lite' ),
				'cover'   => esc_html__( 'Cover', 'essential-addons-for-elementor-lite' ),
				'contain' => esc_html__( 'Contain', 'essential-addons-for-elementor-lite' ),
			],
			'selectors'  => [
				'{{WRAPPER}} .elementor-background-slideshow__slide__image' => 'background-size: {{VALUE}};',
			],
			'condition'  => [
				'background' => [ 'slideshow' ],
			],
		];

		$fields['slideshow_background_position'] = [
			'label'      => esc_html__( 'Background Position', 'essential-addons-for-elementor-lite' ),
			'type'       => Controls_Manager::SELECT,
			'default'    => '',
			'responsive' => true,
			'options'    => [
				''              => esc_html__( 'Default', 'essential-addons-for-elementor-lite' ),
				'center center' => esc_html_x( 'Center Center', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'center left'   => esc_html_x( 'Center Left', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'center right'  => esc_html_x( 'Center Right', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'top center'    => esc_html_x( 'Top Center', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'top left'      => esc_html_x( 'Top Left', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'top right'     => esc_html_x( 'Top Right', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'bottom center' => esc_html_x( 'Bottom Center', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'bottom left'   => esc_html_x( 'Bottom Left', 'Background Control', 'essential-addons-for-elementor-lite' ),
				'bottom right'  => esc_html_x( 'Bottom Right', 'Background Control', 'essential-addons-for-elementor-lite' ),
			],
			'selectors'  => [
				'{{WRAPPER}} .elementor-background-slideshow__slide__image' => 'background-position: {{VALUE}};',
			],
			'condition'  => [
				'background' => [ 'slideshow' ],
			],
		];

		$fields['slideshow_lazyload'] = [
			'label'              => esc_html__( 'Lazyload', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SWITCHER,
			'separator'          => 'before',
			'condition'          => [
				'background' => [ 'slideshow' ],
			],
			'of_type'            => 'slideshow',
			'frontend_available' => true,
		];

		$fields['slideshow_ken_burns'] = [
			'label'              => esc_html__( 'Ken Burns Effect', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SWITCHER,
			'separator'          => 'before',
			'condition'          => [
				'background' => [ 'slideshow' ],
			],
			'of_type'            => 'slideshow',
			'frontend_available' => true,
		];

		$fields['slideshow_ken_burns_zoom_direction'] = [
			'label'              => esc_html__( 'Direction', 'essential-addons-for-elementor-lite' ),
			'type'               => Controls_Manager::SELECT,
			'default'            => 'in',
			'options'            => [
				'in'  => esc_html__( 'In', 'essential-addons-for-elementor-lite' ),
				'out' => esc_html__( 'Out', 'essential-addons-for-elementor-lite' ),
			],
			'condition'          => [
				'background'           => [ 'slideshow' ],
				'slideshow_ken_burns!' => '',
			],
			'of_type'            => 'slideshow',
			'frontend_available' => true,
		];

		return $fields;
	}

}
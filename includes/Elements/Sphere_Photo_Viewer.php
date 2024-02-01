<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Frontend;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use \Elementor\Widget_Base;
use \Elementor\Icons_Manager;
use Essential_Addons_Elementor\Traits\Helper;

class Sphere_Photo_Viewer extends Widget_Base {

	public function get_name() {
		return 'eael-sphere-photo-viewer';
	}

	public function get_title() {
		return esc_html__( '360 Sphere Photo Viewer', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-photo-sphere';
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since  3.5.2
	 * @access public
	 *
	 */
	public function get_keywords() {
		return [
			'sphere photo viewer',
			'360',
			'360 photo',
			'360 photo viewer',
			'sphere photo',
			'photo viewer',
			'photo',
			'ea',
			'essential addons',
		];
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/sphere-photo-viewer/';
	}

	protected function register_controls() {
		/**
		 * General Settings
		 */
		$this->start_controls_section(
			'ea_section_spv_general_settings',
			[
				'label' => esc_html__( 'General Settings', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_spv_caption',
			[
				'label'       => __( 'Caption', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Parc national du Mercantour <b>&copy; Damien Sorel</b>', 'essential-addons-for-elementor-lite' ),
				'placeholder' => __( 'Type your title here', 'essential-addons-for-elementor-lite' ),
				'ai'          => [
					'active' => false
				]
			]
		);

		$this->add_control(
			'ea_spv_image',
			[
				'label'   => __( 'Image', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://photo-sphere-viewer-data.netlify.app/assets/sphere.jpg',

				],
				'ai'      => [
					'active' => false
				]
			]
		);

		$this->add_control(
			'ea_spv_description_switch',
			[
				'label'        => esc_html__( 'Description', 'textdomain' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'textdomain' ),
				'label_off'    => esc_html__( 'Hide', 'textdomain' ),
				'return_value' => 'yes'
			]
		);

		$this->add_control(
			'ea_spv_description',
			[
				'label'       => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default'     => esc_html__( 'Hover Me!', 'essential-addons-for-elementor-lite' ),
				'ai'          => [ 'active' => false ],
				'dynamic'     => [ 'active' => true ],
				'condition'   => [
					'ea_spv_description_switch' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ea_section_spv_markers_settings',
			[
				'label'     => esc_html__( 'Markers', 'essential-addons-for-elementor-lite' ),
				'condition' => [
					'ea_spv_markers_switch' => 'yes'
				]
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'left_position',
			[
				'label'   => __( 'Left Position', 'essential-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min'  => - 180,
						'max'  => 180,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
			]
		);

		$repeater->add_control(
			'top_position',
			[
				'label'   => __( 'Top Position', 'essential-addons-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min'  => - 90,
						'max'  => 90,
						'step' => 0.1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
			]
		);

		$repeater->add_control(
			'ea_spv_markers_img',
			[
				'label'   => esc_html__( 'Image', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'ai'      => [
					'active' => false,
				],
			]
		);

		$repeater->add_control(
			'custom_dimension',
			[
				'label'       => esc_html__( 'Image Dimension', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::IMAGE_DIMENSIONS,
				'description' => esc_html__( 'Crop the original image size to any custom size. Set custom width or height to keep the original size ratio.', 'essential-addons-for-elementor-lite' ),
				'default'     => [
					'width'  => '32',
					'height' => '32',
				],
			]
		);

		$repeater->add_control(
			'ea_spv_markers_tooltip',
			[
				'label'   => esc_html__( 'Tooltip', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'This is title', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [ 'active' => true ],
				'ai'      => [
					'active' => false,
				],
			]
		);

		$repeater->add_control(
			'ea_spv_markers_content',
			[
				'label'   => esc_html__( 'Content', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'essential-addons-for-elementor-lite' ),
				'dynamic' => [ 'active' => true ],
			]
		);

		$this->add_control(
			'ea_spv_markers_list',
			[
				'label'       => esc_html__( 'Feature Item', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::REPEATER,
				'seperator'   => 'before',
				'default'     => [
					[
						'ea_spv_markers_tooltip' => esc_html__( 'Feature Item 1', 'essential-addons-for-elementor-lite' ),
						'ea_spv_markers_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna', 'essential-addons-for-elementor-lite' ),
					],
					[
						'ea_spv_markers_tooltip' => esc_html__( 'Feature Item 2', 'essential-addons-for-elementor-lite' ),
						'ea_spv_markers_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisi cing elit, sed do eiusmod tempor incididunt ut abore et dolore magna', 'essential-addons-for-elementor-lite' ),
					],
				],
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{ ea_spv_markers_tooltip }}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ea_section_spv_options_settings',
			[
				'label' => esc_html__( 'Options', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'ea_spv_zoom_lvl',
			[
				'label'   => esc_html__( 'Zoom Level', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20
				],
				'range'   => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1
					],
				],
			]
		);

		$this->add_control(
			'ea_spv_fisheye',
			[
				'label'        => __( 'Fisheye', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => __( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'ea_spv_markers_switch',
			[
				'label'        => esc_html__( 'Markers', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Off', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before'
			]
		);

		$this->add_control(
			'ea_spv_autorotate_switch',
			[
				'label'        => esc_html__( 'Autorotate', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Off', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before'
			]
		);

		$this->add_control(
			'ea_spv_autorotate_delay',
			[
				'label'     => esc_html__( 'Auto Rotate Delay', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 100
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 5000,
						'step' => 100
					],
				],
				'condition' => [
					'ea_spv_autorotate_switch' => 'yes'
				]
			]
		);

		$this->add_control(
			'ea_spv_autorotate_speed',
			[
				'label'     => esc_html__( 'Auto Rotate Speed', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => .2
				],
				'range'     => [
					'px' => [
						'min'  => .01,
						'max'  => 2,
						'step' => .01
					],
				],
				'condition' => [
					'ea_spv_autorotate_switch' => 'yes'
				]
			]
		);

		$this->add_control(
			'ea_spv_autorotate_pitch',
			[
				'label'     => esc_html__( 'Auto Rotate Pitch', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0
				],
				'range'     => [
					'px' => [
						'min'  => - 90,
						'max'  => 90,
						'step' => 1
					],
				],
				'condition' => [
					'ea_spv_autorotate_switch' => 'yes'
				]
			]
		);

		$this->add_control(
			'ea_spv_autorotate_pan',
			[
				'label'     => esc_html__( 'Pan Correction', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0
				],
				'range'     => [
					'px' => [
						'min'  => - 1,
						'max'  => 1,
						'step' => 0.01
					],
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'ea_spv_autorotate_tilt',
			[
				'label'   => esc_html__( 'Tilt Correction', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0
				],
				'range'   => [
					'px' => [
						'min'  => - 1,
						'max'  => 1,
						'step' => 0.01
					],
				],
			]
		);

		$this->add_control(
			'ea_spv_autorotate_roll',
			[
				'label'   => esc_html__( 'Roll Correction', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0
				],
				'range'   => [
					'px' => [
						'min'  => - 1,
						'max'  => 1,
						'step' => 0.01
					],
				],
			]
		);

		$this->end_controls_section();

		/**
		 * -------------------------------------------
		 * Tab Style General Style
		 * -------------------------------------------
		 */
		$this->start_controls_section(
			'ea_section_spv_general_style',
			[
				'label' => esc_html__( 'General', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ea_spv_color',
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ea-woo-cart' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings        = $this->get_settings_for_display();
		$container_id    = "eael-psv-{$this->get_id()}";
		$sphere_settings = [
			'caption'          => $settings['ea_spv_caption'],
			'panorama'         => $settings['ea_spv_image']['url'],
			'container'        => $container_id,
			'description'      => $settings['ea_spv_description'],
			'defaultZoomLvl'   => empty( $settings['ea_spv_zoom_lvl']['size'] ) ? 20 : $settings['ea_spv_zoom_lvl']['size'],
			'fisheye'          => $settings['ea_spv_fisheye'] === 'yes',
			'sphereCorrection' => [
				'pan'  => floatval( $settings['ea_spv_autorotate_pan']['size'] ) * pi(),
				'tilt' => floatval( $settings['ea_spv_autorotate_tilt']['size'] ) * pi() / 2,
				'roll' => floatval( $settings['ea_spv_autorotate_roll']['size'] ) * pi()
			]
		];

		if ( $settings['ea_spv_autorotate_switch'] === 'yes' ) {
			$sphere_settings['plugins'][][0] = [
				'autostartDelay'  => empty( $settings['ea_spv_autorotate_delay']['size'] ) ? 100 : $settings['ea_spv_autorotate_delay']['size'],
				'autorotatePitch' => empty( $settings['ea_spv_autorotate_pitch']['size'] ) ? '5deg' : $settings['ea_spv_autorotate_pitch']['size'] . 'deg',
				'autorotateSpeed' => empty( $settings['ea_spv_autorotate_speed']['size'] ) ? .2 : $settings['ea_spv_autorotate_speed']['size']
			];
		}

		if ( $settings['ea_spv_markers_switch'] === 'yes' ) {
			$markers = [];
			$uid     = wp_rand();
			foreach ( $settings['ea_spv_markers_list'] as $key => $marker ) {
				$markers[] = [
					'id'       => "{$uid}_{$key}",
					'position' => [ 'yaw' => $marker['left_position']['size'] . 'deg', 'pitch' => $marker['top_position']['size'] . 'deg' ],
					'size'     => [ 'width' => $marker['custom_dimension']['width'], 'height' => $marker['custom_dimension']['height'] ],
					'anchor'   => 'bottom center',
					'image'    => empty( $marker['ea_spv_markers_img']['url'] ) ? '' : $marker['ea_spv_markers_img']['url'],
					'tooltip'  => $marker['ea_spv_markers_tooltip'],
					'content'  => $marker['ea_spv_markers_content']
				];
			}

			$sphere_settings['plugins'][][0] = [
				'markers' => $markers
			];
		}

		$sphere_settings = json_encode( $sphere_settings );

		$this->add_render_attribute( [
			'sphere-wrapper' => [
				'data-settings' => $sphere_settings
			]
		] )
		?>
		<div class="eael-sphere-photo-wrapper" <?php $this->print_render_attribute_string( 'sphere-wrapper' ) ?>>
			<div id="<?php echo esc_attr( $container_id ); ?>" style="height: 500px;"></div>
		</div>
		<?php
	}
}

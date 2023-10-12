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
		return 'eaicon-woo-cross-sells';
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

	protected function _register_controls() {
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
				'label'       => __( 'Caption', 'photo-sphere-viewer' ),
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
		$sphere_settings = json_encode( [
			'caption'         => $settings['ea_spv_caption'],
			'panorama'        => $settings['ea_spv_image']['url'],
			'container'       => $container_id,
			'description'     => $settings['ea_spv_description'],
			'autorotateDelay' => 100
		] );
		$this->add_render_attribute( [
			'sphere-wrapper' => [
				'data-settings' => $sphere_settings
			]
		] )
		?>
		<div class="eael-sphere-photo-wrapper" <?php $this->print_render_attribute_string( 'sphere-wrapper' ) ?>>
			<div id="<?php echo esc_attr( $container_id ); ?>" style="height: 500px;"></div>
		</div>
		<script type="importmap">
			{
				"imports": {
					"three": "https://cdn.jsdelivr.net/npm/three/build/three.module.js",
					"@photo-sphere-viewer/core": "https://cdn.jsdelivr.net/npm/@photo-sphere-viewer/core@5/index.module.js",
					"@photo-sphere-viewer/autorotate-plugin": "https://cdn.jsdelivr.net/npm/@photo-sphere-viewer/autorotate-plugin@5/index.module.js"
				}
			}
		</script>
		<script type="module">
            import {Viewer} from '@photo-sphere-viewer/core';
            import {AutorotatePlugin} from '@photo-sphere-viewer/autorotate-plugin';

            const viewer = new Viewer({
                "caption": "Parc national du Mercantour <b>&copy; Damien Sorel<\/b>",
                "panorama": "https:\/\/photo-sphere-viewer-data.netlify.app\/assets\/sphere.jpg",
                "container": "eael-psv-454a061",
                "description": null,
                "autorotateDelay": 100,
                "plugins": [
                    [AutorotatePlugin, {
                        autostartDelay: 1000,
                        autorotatePitch: '5deg',
                    }],
                ]
            });
		</script>
		<?php
	}
}

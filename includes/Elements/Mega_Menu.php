<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;

class Mega_Menu extends Widget_Base {

	public function get_name() {
		return 'eael-mega-menu';
	}

	public function get_title() {
		return esc_html__( 'Mega Menu', 'essential-addons-for-elementor-lite' );
	}

	public function get_icon() {
		return 'eaicon-simple-menu';
	}

	/**
	 * Forcefully enqueue elementor icon library
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return [ 'elementor-icons' ];
	}

	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	public function get_keywords() {
		return [
			'mega menu',
			'ea mega menu',
			'megamenu',
			'nav menu',
			'ea nav menu',
			'navigation',
			'ea navigation',
			'header menu',
			'dropdown menu',
			'ea',
			'essential addons',
		];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! HelperClass::eael_e_optimized_markup();
	}

	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/mega-menu/';
	}

	protected function register_controls() {

		/**
		 * Content: Layout
		 */
		$this->start_controls_section(
			'eael_mega_menu_section_layout',
			[
				'label' => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'eael_mega_menu_layout',
			[
				'label'   => esc_html__( 'Layout', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'essential-addons-for-elementor-lite' ),
					'vertical'   => esc_html__( 'Vertical', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_preset',
			[
				'label'   => esc_html__( 'Preset', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'preset-1',
				'options' => [
					'preset-1' => esc_html__( 'Preset 1', 'essential-addons-for-elementor-lite' ),
					'preset-2' => esc_html__( 'Preset 2', 'essential-addons-for-elementor-lite' ),
					'preset-3' => esc_html__( 'Preset 3', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_align',
			[
				'label'                => esc_html__( 'Alignment', 'essential-addons-for-elementor-lite' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'left',
				'options'              => [
					'left'    => [
						'title' => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-right',
					],
					'stretch' => [
						'title' => esc_html__( 'Stretch', 'essential-addons-for-elementor-lite' ),
						'icon'  => 'eicon-h-align-stretch',
					],
				],
				'selectors_dictionary' => [
					'left'    => '--eael-mm-justify: flex-start; --eael-mm-item-grow: 0;',
					'center'  => '--eael-mm-justify: center; --eael-mm-item-grow: 0;',
					'right'   => '--eael-mm-justify: flex-end; --eael-mm-item-grow: 0;',
					'stretch' => '--eael-mm-justify: flex-start; --eael-mm-item-grow: 1;',
				],
				'selectors'            => [
					'{{WRAPPER}} .eael-mega-menu-container' => '{{VALUE}}',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_trigger',
			[
				'label'       => esc_html__( 'Open Panel On', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'hover',
				'options'     => [
					'hover' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
					'click' => esc_html__( 'Click', 'essential-addons-for-elementor-lite' ),
				],
				'description' => esc_html__( 'Applies to desktop only. On mobile the menu always opens on tap.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_mega_menu_indicator_icon',
			[
				'label'       => esc_html__( 'Dropdown Indicator', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				],
				'description' => esc_html__( 'Shown only on menu items that have a dropdown panel.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_control(
			'eael_mega_menu_animation',
			[
				'label'   => esc_html__( 'Panel Animation', 'essential-addons-for-elementor-lite' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none'       => esc_html__( 'None', 'essential-addons-for-elementor-lite' ),
					'fade'       => esc_html__( 'Fade', 'essential-addons-for-elementor-lite' ),
					'slide-down' => esc_html__( 'Slide Down', 'essential-addons-for-elementor-lite' ),
					'slide-up'   => esc_html__( 'Slide Up', 'essential-addons-for-elementor-lite' ),
					'zoom'       => esc_html__( 'Zoom', 'essential-addons-for-elementor-lite' ),
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_animation_duration',
			[
				'label'      => esc_html__( 'Animation Duration (ms)', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 300,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 50,
					],
				],
				'condition'  => [
					'eael_mega_menu_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_enable_overlay',
			[
				'label'        => esc_html__( 'Page Overlay', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'Off', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'description'  => esc_html__( 'Dim the page behind the menu while a dropdown panel is open.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->end_controls_section();

		/**
		 * Content: Menu Items
		 */
		$this->start_controls_section(
			'eael_mega_menu_section_items',
			[
				'label' => esc_html__( 'Menu Items', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_label',
			[
				'label'       => esc_html__( 'Label', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Menu Item', 'essential-addons-for-elementor-lite' ),
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'item_link',
			[
				'label'       => esc_html__( 'Link', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::URL,
				'default'     => [
					'url' => '#',
				],
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_items',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ item_label }}}',
				'default'     => [
					[
						'item_label' => esc_html__( 'Home', 'essential-addons-for-elementor-lite' ),
						'item_link'  => [ 'url' => '#' ],
					],
					[
						'item_label' => esc_html__( 'About', 'essential-addons-for-elementor-lite' ),
						'item_link'  => [ 'url' => '#' ],
					],
					[
						'item_label' => esc_html__( 'Contact', 'essential-addons-for-elementor-lite' ),
						'item_link'  => [ 'url' => '#' ],
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		$items    = ! empty( $settings['eael_mega_menu_items'] ) && is_array( $settings['eael_mega_menu_items'] )
			? $settings['eael_mega_menu_items']
			: [];

		if ( empty( $items ) ) {
			if ( Plugin::$instance->editor->is_edit_mode() ) {
				printf(
					'<div class="eael-mega-menu-notice">%s</div>',
					esc_html__( 'Add at least one menu item from the Menu Items section.', 'essential-addons-for-elementor-lite' )
				);
			}

			return;
		}

		$layout    = ! empty( $settings['eael_mega_menu_layout'] ) ? $settings['eael_mega_menu_layout'] : 'horizontal';
		$preset    = ! empty( $settings['eael_mega_menu_preset'] ) ? $settings['eael_mega_menu_preset'] : 'preset-1';
		$trigger   = ! empty( $settings['eael_mega_menu_trigger'] ) ? $settings['eael_mega_menu_trigger'] : 'hover';
		$animation = ! empty( $settings['eael_mega_menu_animation'] ) ? $settings['eael_mega_menu_animation'] : 'fade';
		$duration  = isset( $settings['eael_mega_menu_animation_duration']['size'] )
			? absint( $settings['eael_mega_menu_animation_duration']['size'] )
			: 300;
		$nav_id    = 'eael-mega-menu-' . esc_attr( $this->get_id() );

		$this->add_render_attribute(
			'eael-mega-menu-container',
			[
				'class'          => [
					'eael-mega-menu-container',
					'eael-mega-menu--' . sanitize_html_class( $layout ),
					'eael-mega-menu--' . sanitize_html_class( $preset ),
				],
				'data-trigger'   => sanitize_key( $trigger ),
				'data-animation' => sanitize_key( $animation ),
				'data-duration'  => $duration,
			]
		);

		$this->add_render_attribute(
			'eael-mega-menu-nav',
			[
				'class'      => 'eael-mega-menu__nav',
				'id'         => $nav_id,
				'aria-label' => esc_attr__( 'Main menu', 'essential-addons-for-elementor-lite' ),
			]
		);
		?>
		<div <?php $this->print_render_attribute_string( 'eael-mega-menu-container' ); ?>>
			<nav <?php $this->print_render_attribute_string( 'eael-mega-menu-nav' ); ?>>
				<ul class="eael-mega-menu">
					<?php foreach ( $items as $index => $item ) : ?>
						<?php
						$item_key = 'menu-item-' . $index;
						$link_key = 'menu-item-link-' . $index;

						$this->add_render_attribute(
							$item_key,
							[
								'class'     => 'eael-mega-menu__item',
								'data-item' => $index,
							]
						);

						$this->add_render_attribute( $link_key, 'class', 'eael-mega-menu__item-link' );

						if ( ! empty( $item['item_link']['url'] ) ) {
							$this->add_link_attributes( $link_key, $item['item_link'] );
						}
						?>
						<li <?php $this->print_render_attribute_string( $item_key ); ?>>
							<a <?php $this->print_render_attribute_string( $link_key ); ?>>
								<span class="eael-mega-menu__item-text"><?php echo esc_html( $item['item_label'] ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</div>
		<?php
	}
}

<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper as HelperClass;

class Mega_Menu extends Widget_Base {

	/**
	 * Saved-template ids currently mid-render, keyed by template id.
	 *
	 * Guards against a template that itself contains a Mega Menu pointing back at
	 * the same template — without this the render would recurse until it fataled.
	 *
	 * @var array<int, bool>
	 */
	protected static $rendering_templates = [];

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

		$repeater->add_control(
			'content_source',
			[
				'label'       => esc_html__( 'Dropdown Content', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'template',
				'options'     => [
					'template' => esc_html__( 'Saved Template', 'essential-addons-for-elementor-lite' ),
					'section'  => esc_html__( 'Section ID', 'essential-addons-for-elementor-lite' ),
					'none'     => esc_html__( 'Link only', 'essential-addons-for-elementor-lite' ),
				],
				'description' => esc_html__( 'Saved Template — build the dropdown once in Templates → Saved Templates. Works on every page. Recommended. Section ID — design a Container in THIS page or template and enter its CSS ID here. Link only — no dropdown, plain menu link.', 'essential-addons-for-elementor-lite' ),
			]
		);

		$repeater->add_control(
			'content_template',
			[
				'label'       => esc_html__( 'Choose Template', 'essential-addons-for-elementor-lite' ),
				'type'        => 'eael-select2',
				'source_name' => 'post_type',
				'source_type' => 'elementor_library',
				'label_block' => true,
				'condition'   => [
					'content_source' => 'template',
				],
			]
		);

		$repeater->add_control(
			'section_css_id',
			[
				'label'       => esc_html__( 'Section CSS ID', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
				'description' => esc_html__( 'Enter the container\'s Advanced → CSS ID, without the #. It must exist in the same page or template as this menu. Editing a header template? Put the container inside that header template.', 'essential-addons-for-elementor-lite' ),
				'condition'   => [
					'content_source' => 'section',
				],
			]
		);

		$repeater->add_control(
			'editor_preview_open',
			[
				'label'        => esc_html__( 'Keep Panel Open (Editor Only)', 'essential-addons-for-elementor-lite' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'essential-addons-for-elementor-lite' ),
				'label_off'    => esc_html__( 'No', 'essential-addons-for-elementor-lite' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Holds this dropdown open inside the Elementor editor so you can style it. Has no effect on the live site.', 'essential-addons-for-elementor-lite' ),
				'condition'    => [
					'content_source!' => 'none',
				],
			]
		);

		$repeater->add_control(
			'panel_width',
			[
				'label'     => esc_html__( 'Panel Width', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'container',
				'options'   => [
					'full'      => esc_html__( 'Full Width (viewport)', 'essential-addons-for-elementor-lite' ),
					'container' => esc_html__( 'Container Width', 'essential-addons-for-elementor-lite' ),
					'custom'    => esc_html__( 'Custom', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'content_source!' => 'none',
				],
			]
		);

		$repeater->add_responsive_control(
			'panel_custom_width',
			[
				'label'      => esc_html__( 'Custom Width', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 1600,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 600,
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .eael-mega-menu__panel' => 'width: {{SIZE}}{{UNIT}}; max-width: none;',
				],
				'condition'  => [
					'content_source!' => 'none',
					'panel_width'     => 'custom',
				],
			]
		);

		$repeater->add_control(
			'panel_position',
			[
				'label'     => esc_html__( 'Panel Position', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'   => esc_html__( 'Left', 'essential-addons-for-elementor-lite' ),
					'center' => esc_html__( 'Center', 'essential-addons-for-elementor-lite' ),
					'right'  => esc_html__( 'Right', 'essential-addons-for-elementor-lite' ),
				],
				'condition' => [
					'content_source!' => 'none',
					'panel_width!'    => 'full',
				],
			]
		);

		$repeater->add_control(
			'panel_offset_y',
			[
				'label'      => esc_html__( 'Vertical Offset', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => -50,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .eael-mega-menu__panel' => '--eael-mm-offset-y: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'content_source!' => 'none',
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

		$this->register_container_style_controls();
		$this->register_item_style_controls();
		$this->register_panel_style_controls();
	}

	/**
	 * Style: Dropdown Panel
	 */
	protected function register_panel_style_controls() {

		$this->start_controls_section(
			'eael_mega_menu_style_panel',
			[
				'label' => esc_html__( 'Dropdown Panel', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'eael_mega_menu_panel_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-mega-menu__panel',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_panel_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => 20,
					'right'    => 20,
					'bottom'   => 20,
					'left'     => 20,
					'unit'     => 'px',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_mega_menu_panel_border',
				'selector' => '{{WRAPPER}} .eael-mega-menu__panel',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_panel_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_mega_menu_panel_shadow',
				'selector' => '{{WRAPPER}} .eael-mega-menu__panel',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_panel_max_height',
			[
				'label'       => esc_html__( 'Max Height', 'essential-addons-for-elementor-lite' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', 'vh' ],
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 1200,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'description' => esc_html__( 'Taller content scrolls inside the panel.', 'essential-addons-for-elementor-lite' ),
				'selectors'   => [
					'{{WRAPPER}} .eael-mega-menu__panel' => 'max-height: {{SIZE}}{{UNIT}}; overflow-y: auto;',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_panel_z_index',
			[
				'label'     => esc_html__( 'Z-Index', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'default'   => 999,
				'selectors' => [
					'{{WRAPPER}} .eael-mega-menu__panel' => 'z-index: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Resolve a repeater row's saved template to a renderable template id.
	 *
	 * Mirrors the validation chain used by Adv_Tabs — self-recursion, nested
	 * recursion, unpublished templates and the WPML translated id are all
	 * rejected before anything is echoed.
	 *
	 * @param array $item Repeater row.
	 *
	 * @return int Template id safe to render, or 0 when there is nothing to render.
	 */
	protected function get_renderable_template_id( $item ) {

		if ( empty( $item['content_template'] ) || is_array( $item['content_template'] ) ) {
			return 0;
		}

		$template_id = absint( $item['content_template'] );

		if ( ! $template_id ) {
			return 0;
		}

		// Self-recursion — the template is the page being rendered, or one of its revisions.
		$current_page_id = get_the_ID();

		if ( $template_id === absint( $current_page_id ) ) {
			return 0;
		}

		$revision_ids = wp_list_pluck( wp_get_post_revisions( $current_page_id ), 'ID' );

		if ( in_array( $template_id, array_map( 'absint', $revision_ids ), true ) ) {
			return 0;
		}

		// Nested recursion — this template is already being rendered further up the stack.
		if ( isset( self::$rendering_templates[ $template_id ] ) ) {
			return 0;
		}

		if ( ! HelperClass::is_elementor_publish_template( $template_id ) ) {
			return 0;
		}

		// WPML compatibility — swap in the translated template for the active language.
		$template_id = absint( apply_filters( 'wpml_object_id', $template_id, 'elementor_library', true ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

		// Re-validate: the translated post may not be a published elementor_library entry.
		if ( ! HelperClass::is_elementor_publish_template( $template_id ) ) {
			return 0;
		}

		return $template_id;
	}

	/**
	 * Echo a saved template's builder output inside a dropdown panel.
	 *
	 * @param int $template_id Already validated by get_renderable_template_id().
	 */
	protected function render_template_panel_content( $template_id ) {

		self::$rendering_templates[ $template_id ] = true;

		HelperClass::eael_onpage_edit_template_markup( get_the_ID(), $template_id );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo Plugin::$instance->frontend->get_builder_content( $template_id, true );

		unset( self::$rendering_templates[ $template_id ] );
	}

	/**
	 * Read a repeater row's linked-section CSS ID, sanitized for safe use.
	 *
	 * The value reaches both a CSS selector and getElementById(), and it can
	 * come from a dynamic tag, so it is reduced to [A-Za-z0-9_-] before use.
	 *
	 * @param array $item Repeater row.
	 *
	 * @return string Safe CSS ID, or an empty string when there is nothing usable.
	 */
	protected function get_linked_section_id( $item ) {

		if ( empty( $item['section_css_id'] ) || ! is_string( $item['section_css_id'] ) ) {
			return '';
		}

		// Users routinely paste the selector rather than the bare ID.
		$section_id = ltrim( trim( $item['section_css_id'] ), '#' );

		return sanitize_html_class( $section_id );
	}

	/**
	 * Collect every linked-section CSS ID used by this menu.
	 *
	 * @param array $items Repeater rows.
	 *
	 * @return array Unique list of sanitized CSS IDs.
	 */
	protected function collect_linked_section_ids( $items ) {

		$section_ids = [];

		foreach ( $items as $item ) {
			if ( empty( $item['content_source'] ) || 'section' !== $item['content_source'] ) {
				continue;
			}

			$section_id = $this->get_linked_section_id( $item );

			if ( '' !== $section_id ) {
				$section_ids[] = $section_id;
			}
		}

		return array_unique( $section_ids );
	}

	/**
	 * Print the stylesheet that hides linked sections until the JS has moved
	 * them into their panels. Without it the sections would flash in their
	 * original position on first paint.
	 *
	 * mega-menu.js removes this tag as soon as the move is done — otherwise the
	 * section would stay hidden inside the panel too.
	 *
	 * @param array $section_ids Sanitized CSS IDs.
	 */
	protected function print_linked_section_hide_style( $section_ids ) {

		if ( empty( $section_ids ) ) {
			return;
		}

		$selectors = [];

		foreach ( $section_ids as $section_id ) {
			// An attribute selector rather than `#id`, because a CSS ID that
			// starts with a digit is valid HTML but an invalid CSS selector —
			// and one bad selector would void the whole rule.
			$selectors[] = '[id="' . $section_id . '"]';
		}

		printf(
			'<style id="eael-mm-hide-%s">%s{display:none!important}</style>',
			esc_attr( $this->get_id() ),
			// Safe: every ID passed through sanitize_html_class(), so only
			// [A-Za-z0-9_-] survives. esc_html() is wrong here — <style> is a
			// raw-text element, so entities would be taken literally by the
			// CSS parser and break the rule.
			implode( ',', $selectors ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	/**
	 * Style: Container
	 */
	protected function register_container_style_controls() {

		$this->start_controls_section(
			'eael_mega_menu_style_container',
			[
				'label' => esc_html__( 'Container', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'eael_mega_menu_container_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .eael-mega-menu-container',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_container_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_container_margin',
			[
				'label'      => esc_html__( 'Margin', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_mega_menu_container_border',
				'selector' => '{{WRAPPER}} .eael-mega-menu-container',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_container_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_mega_menu_container_shadow',
				'selector' => '{{WRAPPER}} .eael-mega-menu-container',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_container_min_height',
			[
				'label'      => esc_html__( 'Min Height', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu-container' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_item_gap',
			[
				'label'      => esc_html__( 'Item Gap', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_container_z_index',
			[
				'label'     => esc_html__( 'Z-Index', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'selectors' => [
					'{{WRAPPER}} .eael-mega-menu-container' => 'z-index: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style: Menu Items
	 */
	protected function register_item_style_controls() {

		$this->start_controls_section(
			'eael_mega_menu_style_items',
			[
				'label' => esc_html__( 'Menu Items', 'essential-addons-for-elementor-lite' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'eael_mega_menu_item_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .eael-mega-menu__item-link',
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_item_padding',
			[
				'label'      => esc_html__( 'Padding', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top'      => 10,
					'right'    => 15,
					'bottom'   => 10,
					'left'     => 15,
					'unit'     => 'px',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .eael-mega-menu__item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'eael_mega_menu_item_tabs' );

		/**
		 * Normal
		 */
		$this->start_controls_tab(
			'eael_mega_menu_item_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_item_state_controls( 'normal', '{{WRAPPER}} .eael-mega-menu__item-link' );

		$this->end_controls_tab();

		/**
		 * Hover
		 */
		$this->start_controls_tab(
			'eael_mega_menu_item_tab_hover',
			[
				'label' => esc_html__( 'Hover', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_item_state_controls(
			'hover',
			'{{WRAPPER}} .eael-mega-menu__item-link:hover, {{WRAPPER}} .eael-mega-menu__item-link:focus'
		);

		$this->end_controls_tab();

		/**
		 * Active — current page item, and (from Step 5) the item whose panel is open.
		 */
		$this->start_controls_tab(
			'eael_mega_menu_item_tab_active',
			[
				'label' => esc_html__( 'Active', 'essential-addons-for-elementor-lite' ),
			]
		);

		$this->add_item_state_controls(
			'active',
			'{{WRAPPER}} .eael-mega-menu__item--active > .eael-mega-menu__item-link, {{WRAPPER}} .eael-mega-menu__item.is-active > .eael-mega-menu__item-link'
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Colour / background / border / radius / shadow for one menu item state.
	 *
	 * @param string $state    Control id suffix — normal, hover or active.
	 * @param string $selector CSS selector the state applies to.
	 */
	protected function add_item_state_controls( $state, $selector ) {

		$this->add_control(
			'eael_mega_menu_item_color_' . $state,
			[
				'label'     => esc_html__( 'Text Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => 'normal' === $state ? Global_Colors::COLOR_TEXT : '',
				],
				'selectors' => [
					$selector => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_mega_menu_item_bg_' . $state,
			[
				'label'     => esc_html__( 'Background Color', 'essential-addons-for-elementor-lite' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$selector => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'eael_mega_menu_item_border_' . $state,
				'selector' => $selector,
			]
		);

		$this->add_responsive_control(
			'eael_mega_menu_item_radius_' . $state,
			[
				'label'      => esc_html__( 'Border Radius', 'essential-addons-for-elementor-lite' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'eael_mega_menu_item_shadow_' . $state,
				'selector' => $selector,
			]
		);
	}

	/**
	 * Whether a menu item link points at the document currently being viewed.
	 *
	 * @param string $url Raw link url from the repeater row.
	 *
	 * @return bool
	 */
	protected function is_current_menu_item( $url ) {

		if ( empty( $url ) || '#' === $url ) {
			return false;
		}

		// Compare paths only — ignore query strings and fragments.
		$item_url = untrailingslashit( strtok( $url, '?#' ) );

		if ( '' === $item_url ) {
			return false;
		}

		global $wp;

		$current_url = untrailingslashit( home_url( isset( $wp->request ) ? $wp->request : '' ) );

		return $item_url === $current_url;
	}

	protected function render() {

		$settings      = $this->get_settings_for_display();
		$is_edit_mode  = Plugin::$instance->editor->is_edit_mode();
		$items         = ! empty( $settings['eael_mega_menu_items'] ) && is_array( $settings['eael_mega_menu_items'] )
			? $settings['eael_mega_menu_items']
			: [];

		if ( empty( $items ) ) {
			if ( $is_edit_mode ) {
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
		// Printed before the menu markup so the browser applies it as early as
		// possible. Editor keeps the sections visible and editable in place.
		if ( ! $is_edit_mode ) {
			$this->print_linked_section_hide_style( $this->collect_linked_section_ids( $items ) );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'eael-mega-menu-container' ); ?>>
			<nav <?php $this->print_render_attribute_string( 'eael-mega-menu-nav' ); ?>>
				<ul class="eael-mega-menu">
					<?php foreach ( $items as $index => $item ) : ?>
						<?php
						$item_key   = 'menu-item-' . $index;
						$link_key   = 'menu-item-link-' . $index;
						$panel_key  = 'menu-item-panel-' . $index;
						$item_url   = isset( $item['item_link']['url'] ) ? $item['item_link']['url'] : '';
						$is_current = $this->is_current_menu_item( $item_url );

						// A panel is only rendered when the chosen source actually resolves
						// to something. An unresolved template or a blank section ID
						// leaves the item as a plain link.
						$content_source = ! empty( $item['content_source'] ) ? $item['content_source'] : 'template';
						$template_id    = 'template' === $content_source ? $this->get_renderable_template_id( $item ) : 0;
						$section_id     = 'section' === $content_source ? $this->get_linked_section_id( $item ) : '';
						$has_panel      = $template_id || '' !== $section_id;
						$panel_id       = 'eael-mega-panel-' . $this->get_id() . '-' . $index;

						$item_classes = [
							'eael-mega-menu__item',
							// Elementor resolves {{CURRENT_ITEM}} in repeater selectors to this class.
							'elementor-repeater-item-' . ( isset( $item['_id'] ) ? $item['_id'] : $index ),
						];

						if ( $is_current ) {
							$item_classes[] = 'eael-mega-menu__item--active';
						}

						if ( $has_panel ) {
							$item_classes[] = 'eael-mega-menu__item--has-panel';
						}

						$this->add_render_attribute(
							$item_key,
							[
								'class'     => $item_classes,
								'data-item' => $index,
							]
						);

						$this->add_render_attribute( $link_key, 'class', 'eael-mega-menu__item-link' );

						if ( $is_current ) {
							$this->add_render_attribute( $link_key, 'aria-current', 'page' );
						}

						if ( $has_panel ) {
							$this->add_render_attribute(
								$link_key,
								[
									'aria-haspopup' => 'true',
									'aria-expanded' => 'false',
									'aria-controls' => $panel_id,
								]
							);

							$panel_width    = ! empty( $item['panel_width'] ) ? $item['panel_width'] : 'container';
							$panel_position = ! empty( $item['panel_position'] ) ? $item['panel_position'] : 'left';

							$panel_classes = [
								'eael-mega-menu__panel',
								'eael-mega-menu__panel--' . sanitize_html_class( $panel_width ),
							];

							if ( 'full' !== $panel_width ) {
								$panel_classes[] = 'eael-mega-menu__panel--pos-' . sanitize_html_class( $panel_position );
							}

							// Editor-only: hold the panel open so it can be styled. This class
							// is never emitted on the front end.
							if ( $is_edit_mode && ! empty( $item['editor_preview_open'] ) && 'yes' === $item['editor_preview_open'] ) {
								$panel_classes[] = 'eael-mega-menu__panel--editor-open';
							}

							$this->add_render_attribute(
								$panel_key,
								[
									'class'      => $panel_classes,
									'id'         => $panel_id,
									'role'       => 'region',
									/* translators: %s: menu item label. */
									'aria-label' => sprintf( esc_attr__( '%s submenu', 'essential-addons-for-elementor-lite' ), $item['item_label'] ),
								]
							);

							// mega-menu.js reads this and moves the matching element in.
							if ( '' !== $section_id ) {
								$this->add_render_attribute( $panel_key, 'data-mega-section', $section_id );
							}
						}

						if ( ! empty( $item_url ) ) {
							$this->add_link_attributes( $link_key, $item['item_link'] );
						}
						?>
						<li <?php $this->print_render_attribute_string( $item_key ); ?>>
							<a <?php $this->print_render_attribute_string( $link_key ); ?>>
								<span class="eael-mega-menu__item-text"><?php echo esc_html( $item['item_label'] ); ?></span>
								<?php if ( $has_panel && ! empty( $settings['eael_mega_menu_indicator_icon']['value'] ) ) : ?>
									<span class="eael-mega-menu__indicator">
										<?php Icons_Manager::render_icon( $settings['eael_mega_menu_indicator_icon'], [ 'aria-hidden' => 'true' ] ); ?>
									</span>
								<?php endif; ?>
							</a>
							<?php if ( $has_panel ) : ?>
								<div <?php $this->print_render_attribute_string( $panel_key ); ?>>
									<?php
									// Section panels start empty — the JS moves the linked
									// element in once the page has parsed.
									if ( $template_id ) {
										$this->render_template_panel_content( $template_id );
									}
									?>
								</div>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</div>
		<?php
	}
}

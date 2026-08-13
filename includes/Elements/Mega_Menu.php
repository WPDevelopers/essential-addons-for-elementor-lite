<?php

namespace Essential_Addons_Elementor\Elements;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Modules\NestedElements\Base\Widget_Nested_Base;
use Essential_Addons_Elementor\Classes\Helper;
use Essential_Addons_Elementor\MegaMenu\Controls\Content_Controls;
use Essential_Addons_Elementor\MegaMenu\Controls\Style_Controls;
use Essential_Addons_Elementor\MegaMenu\Manager;
use Essential_Addons_Elementor\MegaMenu\Renderers\Editor_Renderer;
use Essential_Addons_Elementor\MegaMenu\Renderers\Frontend_Renderer;
use Essential_Addons_Elementor\MegaMenu\Traits\Menu_Items;

/**
 * Mega Menu.
 *
 * A nested Elementor widget: every menu item behaves like a tab and owns its own
 * container, so any widget can be dropped inside a submenu panel.
 *
 * The class is only ever instantiated when
 * \Essential_Addons_Elementor\MegaMenu\Conditions::is_supported() passes — see the
 * `condition` entry for `mega-menu` in config.php — because `Widget_Nested_Base`
 * only ships with Elementor 3.8+.
 *
 * @since 6.3.0
 */
class Mega_Menu extends Widget_Nested_Base {

	use Menu_Items;

	/**
	 * @var Frontend_Renderer|null
	 */
	protected $renderer = null;

	/**
	 * @inheritDoc
	 */
	public function get_name() {
		return Manager::WIDGET_NAME;
	}

	/**
	 * @inheritDoc
	 */
	public function get_title() {
		return esc_html__( 'Mega Menu', 'essential-addons-for-elementor-lite' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_icon() {
		return 'eaicon-simple-menu';
	}

	/**
	 * @inheritDoc
	 */
	public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}

	/**
	 * @inheritDoc
	 */
	public function get_keywords() {
		return [
			'mega menu',
			'menu',
			'nav',
			'navigation',
			'header',
			'dropdown',
			'nested',
			'ea mega menu',
			'ea',
			'essential addons',
		];
	}

	/**
	 * Documentation link shown as "Need Help" at the bottom of the widget panel.
	 *
	 * @inheritDoc
	 */
	public function get_custom_help_url() {
		return 'https://essential-addons.com/elementor/docs/ea-mega-menu/';
	}

	/**
	 * Hide the widget while Elementor's nested elements API is switched off.
	 *
	 * @return bool
	 */
	public function show_in_panel(): bool {
		return Manager::instance()->is_available_in_panel();
	}

	/**
	 * @inheritDoc
	 */
	public function has_widget_inner_wrapper(): bool {
		return ! Helper::eael_e_optimized_markup();
	}

	/**
	 * One container per default menu item.
	 *
	 * @inheritDoc
	 */
	protected function get_default_children_elements() {
		return Manager::instance()->get_default_children_elements();
	}

	/**
	 * @inheritDoc
	 */
	protected function get_default_repeater_title_setting_key() {
		return 'eael_mega_menu_item_label';
	}

	/**
	 * @inheritDoc
	 */
	protected function get_default_children_title() {
		/* translators: %d: Menu item index. */
		return esc_html__( 'Menu Item #%d', 'essential-addons-for-elementor-lite' );
	}

	/**
	 * Where the editor mounts the nested container views.
	 *
	 * @inheritDoc
	 */
	protected function get_default_children_placeholder_selector() {
		return '.eael-mega-menu__panels';
	}

	/**
	 * @inheritDoc
	 */
	protected function register_controls() {
		Content_Controls::register( $this );
		Style_Controls::register( $this );
	}

	/**
	 * Per row settings the frontend handler actually reads.
	 *
	 * Mirrors getItemType() / getItemSetting() in src/js/view/mega-menu.js. The
	 * legacy switcher is kept because getItemType() still falls back to it for
	 * rows saved before the type control existed.
	 */
	const FRONTEND_ITEM_KEYS = [
		'eael_mega_menu_item_type',
		'eael_mega_menu_item_has_submenu',
		'eael_mega_menu_item_submenu_width',
		'eael_mega_menu_item_submenu_custom_width',
		'eael_mega_menu_item_panel_align',
		'eael_mega_menu_item_panel_offset_x',
		'eael_mega_menu_item_panel_offset_y',
	];

	/**
	 * Strip the menu item rows down to what the handler needs.
	 *
	 * The repeater is `frontend_available` because the handler resolves each
	 * panel's width and placement from it — but the default serialisation ships
	 * *every* field, labels, links, icons, template ids, CSS classes and all,
	 * into a `data-settings` attribute. On a widget built for site headers that
	 * is dead weight on every page of the site: a six item menu measured 7.2 KB,
	 * roughly half of it never read.
	 *
	 * Only the front end is affected. Inside the editor the handler reads the
	 * live settings model rather than this attribute, so the template preview and
	 * every other editor-only lookup keep seeing the full row.
	 *
	 * @inheritDoc
	 */
	public function get_frontend_settings() {
		$settings = parent::get_frontend_settings();

		if ( empty( $settings['eael_mega_menu_items'] ) || ! is_array( $settings['eael_mega_menu_items'] ) ) {
			return $settings;
		}

		$keep = array_flip( self::FRONTEND_ITEM_KEYS );

		foreach ( $settings['eael_mega_menu_items'] as $index => $item ) {
			$settings['eael_mega_menu_items'][ $index ] = array_intersect_key( (array) $item, $keep );
		}

		return $settings;
	}

	/**
	 * Frontend renderer, lazily built.
	 *
	 * @return Frontend_Renderer
	 */
	protected function get_renderer() {
		if ( null === $this->renderer ) {
			$this->renderer = new Frontend_Renderer( $this );
		}

		return $this->renderer;
	}

	/**
	 * @inheritDoc
	 */
	protected function render() {
		$this->get_renderer()->render();
	}

	/**
	 * @inheritDoc
	 */
	protected function content_template() {
		Editor_Renderer::content_template();
	}

	/**
	 * Print a nested container as a submenu panel.
	 *
	 * Mirrors Elementor's own nested tabs approach — the container's wrapper
	 * attributes are injected through a short lived filter so the panel keeps its
	 * id, ARIA wiring and ordering without altering the container element itself.
	 *
	 * @param int   $index    Child index.
	 * @param array $prepared Prepared repeater row.
	 */
	public function print_child( $index, $prepared = [] ) {
		$children = $this->get_children();

		if ( empty( $children[ $index ] ) ) {
			return;
		}

		if ( empty( $prepared ) ) {
			$children[ $index ]->print_element();

			return;
		}

		$child_id = $children[ $index ]->get_id();
		$renderer = $this->get_renderer();

		$decorate_container = function ( $should_render, $container ) use ( $child_id, $prepared, $renderer ) {
			if ( is_object( $container ) && $container->get_id() === $child_id ) {
				$renderer->add_panel_attributes( $container, $prepared );
			}

			return $should_render;
		};

		add_filter( 'elementor/frontend/container/should_render', $decorate_container, 10, 2 );
		$children[ $index ]->print_element();
		remove_filter( 'elementor/frontend/container/should_render', $decorate_container, 10 );
	}
}

<?php

namespace Essential_Addons_Elementor\MegaMenu\Renderers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

use Elementor\Plugin;
use Elementor\Widget_Base;
use Essential_Addons_Elementor\Classes\Helper;
use Essential_Addons_Elementor\MegaMenu\Manager;
use Essential_Addons_Elementor\MegaMenu\Traits\Menu_Items;

/**
 * Server side renderer for the Mega Menu widget.
 *
 * Only runs on the frontend — inside the editor Elementor renders the widget
 * from `content_template()` and mounts the real container views itself.
 *
 * @since 6.3.0
 */
class Frontend_Renderer {

	use Menu_Items;

	/**
	 * @var Widget_Base
	 */
	protected $widget;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * Unique numeric id of the widget instance.
	 *
	 * @var int
	 */
	protected $widget_number;

	/**
	 * Prepared repeater rows keyed by row index.
	 *
	 * @var array
	 */
	protected $prepared_items = [];

	/**
	 * @param Widget_Base $widget Mega Menu widget instance.
	 */
	public function __construct( Widget_Base $widget ) {
		$this->widget        = $widget;
		$this->settings      = $widget->get_settings_for_display();
		$this->widget_number = $widget->get_id_int();
	}

	/**
	 * Print the whole menu.
	 */
	public function render() {
		$items = isset( $this->settings['eael_mega_menu_items'] ) ? (array) $this->settings['eael_mega_menu_items'] : [];

		/**
		 * Filter the Mega Menu repeater rows right before rendering.
		 *
		 * @since 6.3.0
		 *
		 * @param array       $items  Repeater rows.
		 * @param Widget_Base $widget Widget instance.
		 */
		$items = apply_filters( 'eael/mega-menu/menu_items', $items, $this->widget );

		if ( empty( $items ) ) {
			return;
		}

		foreach ( $items as $index => $item ) {
			$this->prepared_items[ $index ] = $this->eael_mega_menu_prepare_item( $item, $index, $this->widget_number );
		}

		$container_id = 'eael-mega-menu-container-' . $this->widget_number;
		$has_toggle   = $this->has_mobile_toggle();

		$this->print_section_source_styles();

		$this->widget->add_render_attribute( 'eael_mega_menu_wrapper', [
			'class'                 => $this->get_wrapper_classes(),
			'data-widget-number'    => $this->widget_number,
			'data-trigger'          => $this->get_trigger(),
			'data-breakpoint'       => $this->get_breakpoint(),
			'data-touch-mode'       => 'false',
			'aria-label'            => esc_attr__( 'Mega Menu', 'essential-addons-for-elementor-lite' ),
		] );
		?>
		<nav <?php $this->widget->print_render_attribute_string( 'eael_mega_menu_wrapper' ); ?>>
			<?php
			if ( $has_toggle ) {
				$this->template( 'mobile-toggle', [
					'container_id' => $container_id,
					'settings'     => $this->settings,
				] );
			}
			?>
			<div class="eael-mega-menu__container" id="<?php echo esc_attr( $container_id ); ?>">
				<ul class="eael-mega-menu__list">
					<?php
					foreach ( $this->prepared_items as $prepared ) {
						$this->template( 'menu-item', [
							'prepared' => $prepared,
							'settings' => $this->settings,
							'renderer' => $this,
						] );
					}
					?>
				</ul>
				<div class="eael-mega-menu__panels">
					<?php $this->render_panels(); ?>
				</div>
			</div>
		</nav>
		<?php
	}

	/**
	 * Keep every referenced section hidden until the handler adopts it.
	 *
	 * A Section CSS ID panel is filled on the front end by *moving* an element
	 * that Elementor already rendered in its own place in the page — the panel
	 * itself ships empty. Until that runs the section is ordinary page content:
	 * it paints where it was authored and then disappears once the handler
	 * relocates it, which reads as a flash of unrelated content on every load.
	 *
	 * Printing the rule here, ahead of the menu's own markup, puts it before the
	 * section in the document for the usual "menu in the header" layout, so the
	 * section is never painted in the first place. The handler adds
	 * `--section-source` as it mounts the element, which is what releases it
	 * again — matching on its absence means a section that is never adopted
	 * (script blocked, id typo) is the only case left showing in place.
	 *
	 * Attribute form rather than `#id` so an id that starts with a digit stays a
	 * valid selector; ids are already reduced to `[A-Za-z0-9_-]` upstream by
	 * eael_mega_menu_sanitize_element_id().
	 */
	protected function print_section_source_styles() {
		$selectors = [];

		foreach ( $this->prepared_items as $prepared ) {
			if ( 'section' !== $prepared['type'] ) {
				continue;
			}

			// Re-reduced to `[A-Za-z0-9_-]` at the point of output. The value lands
			// inside a <style> element, whose content is raw text — an escaping
			// helper would turn the quotes into entities that CSS never decodes and
			// quietly break the selector, so narrowing the character set is what
			// makes this safe, not escaping.
			$section_id = $this->eael_mega_menu_sanitize_element_id( $prepared['section_id'] );

			if ( '' === $section_id ) {
				continue;
			}

			$selectors[] = '[id="' . $section_id . '"]:not(.eael-mega-menu__section-source)';
		}

		if ( empty( $selectors ) ) {
			return;
		}

		printf(
			'<style id="eael-mega-menu-sections-%1$d">%2$s{display:none !important;}</style>',
			(int) $this->widget_number,
			implode( ',', array_unique( $selectors ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Selector list built only from ids reduced to [A-Za-z0-9_-] above.
		);
	}

	/**
	 * Print every nested container that belongs to an item with a submenu.
	 */
	protected function render_panels() {
		foreach ( $this->prepared_items as $prepared ) {
			if ( ! $prepared['has_submenu'] ) {
				continue;
			}

			if ( 'template' === $prepared['type'] ) {
				$this->render_template_panel( $prepared );

				continue;
			}

			if ( 'section' === $prepared['type'] ) {
				$this->render_section_panel( $prepared );

				continue;
			}

			$this->widget->print_child( $prepared['index'], $prepared );
		}
	}

	/**
	 * Print an empty panel that the handler fills with an existing element.
	 *
	 * The referenced section is a normal Elementor element rendered elsewhere on
	 * the page, so it cannot be pulled in server side. The panel is printed with
	 * the id to look for and the frontend handler moves that node into it — a
	 * move, not a copy, so the id stays unique.
	 *
	 * @param array $prepared Prepared repeater row.
	 */
	protected function render_section_panel( $prepared ) {
		if ( '' === $prepared['section_id'] ) {
			return;
		}

		$key = 'eael_mega_menu_section_panel_' . $prepared['index'];

		$this->widget->add_render_attribute( $key, [
			'id'               => $prepared['panel_id'],
			'class'            => [ 'eael-mega-menu__panel', 'eael-mega-menu__panel--section' ],
			'aria-labelledby'  => $prepared['item_id'],
			'data-item-index'  => $prepared['position'],
			'data-width-mode'  => $prepared['width_mode'],
			'data-section-id'  => $prepared['section_id'],
			'style'            => '--eael-mm-order: ' . ( (int) $prepared['position'] * 2 + 1 ) . ';',
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( $key ); ?>></div>
		<?php
	}

	/**
	 * Print a panel whose content comes from a saved Elementor template.
	 *
	 * Mirrors the guards the Advanced Tabs widget uses for the same feature:
	 * refuse to render the current page (infinite recursion), require a published
	 * `elementor_library` post, and honour WPML translations.
	 *
	 * @param array $prepared Prepared repeater row.
	 */
	protected function render_template_panel( $prepared ) {
		$template_id = absint( $prepared['template_id'] );

		if ( ! $template_id ) {
			return;
		}

		$page_id      = get_the_ID();
		$revisions    = wp_get_post_revisions( $page_id );
		$revision_ids = wp_list_pluck( $revisions, 'ID' );

		$key = 'eael_mega_menu_template_panel_' . $prepared['index'];

		$this->widget->add_render_attribute( $key, [
			'id'              => $prepared['panel_id'],
			'class'           => [ 'eael-mega-menu__panel', 'eael-mega-menu__panel--template' ],
			'aria-labelledby' => $prepared['item_id'],
			'data-item-index' => $prepared['position'],
			'data-width-mode' => $prepared['width_mode'],
			'style'           => '--eael-mm-order: ' . ( (int) $prepared['position'] * 2 + 1 ) . ';',
		] );
		?>
		<div <?php $this->widget->print_render_attribute_string( $key ); ?>>
			<?php
			if ( $template_id === absint( $page_id ) || in_array( $template_id, array_map( 'absint', (array) $revision_ids ), true ) ) {
				echo '<p>' . esc_html__( 'The provided Template matches the current page or one of its revisions!', 'essential-addons-for-elementor-lite' ) . '</p>';
			} elseif ( Helper::is_elementor_publish_template( $template_id ) ) {
				// WPML Compatibility.
				$template_id = apply_filters( 'wpml_object_id', $template_id, 'elementor_library', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

				// Re-validate the translated template is still a published library post.
				if ( Helper::is_elementor_publish_template( $template_id ) ) {
					Helper::eael_onpage_edit_template_markup( $page_id, $template_id );

					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo Plugin::$instance->frontend->get_builder_content( $template_id, true );
				}
			}
			?>
		</div>
		<?php
	}

	/**
	 * Decorate a nested container so it behaves as a submenu panel.
	 *
	 * @param \Elementor\Element_Base $container Child container element.
	 * @param array                   $prepared  Prepared repeater row.
	 */
	public function add_panel_attributes( $container, $prepared ) {
		$container->add_render_attribute( '_wrapper', [
			'id'              => $prepared['panel_id'],
			'class'           => 'eael-mega-menu__panel',
			'aria-labelledby' => $prepared['item_id'],
			'data-item-index' => $prepared['position'],
			'data-width-mode' => $prepared['width_mode'],
			// Items sit on even orders, their panel on the next odd one, so the
			// collapsed mobile layout interleaves them correctly.
			'style'           => '--eael-mm-order: ' . ( (int) $prepared['position'] * 2 + 1 ) . ';',
		] );
	}

	/**
	 * Render an icon control value.
	 *
	 * @param array $icon Icon control value.
	 */
	public function render_icon( $icon ) {
		if ( empty( $icon['value'] ) ) {
			return;
		}

		\Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
	}

	/**
	 * Build the anchor / button attributes for a menu item.
	 *
	 * @param array $prepared Prepared repeater row.
	 *
	 * @return string Rendered attribute string.
	 */
	public function get_link_attributes( $prepared ) {
		$key = 'eael_mega_menu_link_' . $prepared['index'];

		$this->widget->add_render_attribute( $key, [
			'class' => 'eael-mega-menu__link',
			'id'    => $prepared['item_id'],
		] );

		if ( $prepared['has_url'] ) {
			$this->widget->add_link_attributes( $key, $prepared['link'] );
		}

		// When there is no link the item itself is the disclosure control.
		if ( ! $prepared['has_url'] && $prepared['has_submenu'] ) {
			$this->widget->add_render_attribute( $key, [
				'type'          => 'button',
				'aria-expanded' => 'false',
				'aria-controls' => $prepared['panel_id'],
			] );
		}

		return $this->widget->get_render_attribute_string( $key );
	}

	/**
	 * Attributes for the standalone disclosure button rendered next to a linked
	 * item that also owns a submenu.
	 *
	 * @param array $prepared Prepared repeater row.
	 *
	 * @return string Rendered attribute string.
	 */
	public function get_disclosure_attributes( $prepared ) {
		$key = 'eael_mega_menu_disclosure_' . $prepared['index'];

		$this->widget->add_render_attribute( $key, [
			'class'         => 'eael-mega-menu__disclosure',
			'type'          => 'button',
			'aria-expanded' => 'false',
			'aria-controls' => $prepared['panel_id'],
			'aria-label'    => sprintf(
			/* translators: %s: Menu item label. */
				esc_attr__( 'Show submenu for %s', 'essential-addons-for-elementor-lite' ),
				wp_strip_all_tags( (string) $prepared['label'] )
			),
		] );

		return $this->widget->get_render_attribute_string( $key );
	}

	/**
	 * Classes for the <li> wrapper of a menu item.
	 *
	 * @param array $prepared Prepared repeater row.
	 *
	 * @return string
	 */
	public function get_item_classes( $prepared ) {
		$classes = [ 'eael-mega-menu__item' ];

		if ( $prepared['has_submenu'] ) {
			$classes[] = 'eael-mega-menu__item--has-submenu';
		}

		$classes = array_merge( $classes, $this->eael_mega_menu_sanitize_classes( $prepared['custom_class'] ) );

		return implode( ' ', array_unique( $classes ) );
	}

	/**
	 * Root element classes.
	 *
	 * @return string
	 */
	protected function get_wrapper_classes() {
		$classes = [
			Manager::CSS_ROOT,
			Manager::CSS_ROOT . '--trigger-' . $this->get_trigger(),
			Manager::CSS_ROOT . '--anim-' . $this->get_animation(),
		];

		if ( ! $this->has_mobile_toggle() ) {
			$classes[] = Manager::CSS_ROOT . '--no-toggle';
		}

		// Read the raw value — a switcher that is turned off stores an empty
		// string, which get_setting() would mistake for "not set".
		if ( isset( $this->settings['eael_mega_menu_toggle_full_width'] )
			&& 'yes' === $this->settings['eael_mega_menu_toggle_full_width'] ) {
			$classes[] = Manager::CSS_ROOT . '--stretch-dropdown';
		}

		return implode( ' ', array_map( 'sanitize_html_class', $classes ) );
	}

	/**
	 * Normalised open trigger.
	 *
	 * @return string
	 */
	protected function get_trigger() {
		return 'click' === $this->get_setting( 'eael_mega_menu_trigger', 'hover' ) ? 'click' : 'hover';
	}

	/**
	 * Normalised animation key.
	 *
	 * @return string
	 */
	protected function get_animation() {
		$animation  = (string) $this->get_setting( 'eael_mega_menu_animation', 'fade' );
		$animations = Manager::instance()->get_animation_options();

		return isset( $animations[ $animation ] ) ? $animation : 'none';
	}

	/**
	 * Normalised collapse breakpoint.
	 *
	 * @return string
	 */
	protected function get_breakpoint() {
		$breakpoint  = (string) $this->get_setting( 'eael_mega_menu_breakpoint', 'tablet' );
		$breakpoints = Manager::instance()->get_breakpoint_options();

		return isset( $breakpoints[ $breakpoint ] ) ? $breakpoint : 'none';
	}

	/**
	 * Does this instance collapse into a toggle at all.
	 *
	 * @return bool
	 */
	protected function has_mobile_toggle() {
		return 'none' !== $this->get_breakpoint();
	}

	/**
	 * Settings accessor with a default.
	 *
	 * @param string $key     Setting key.
	 * @param mixed  $default Fallback when unset or empty string.
	 *
	 * @return mixed
	 */
	protected function get_setting( $key, $default = '' ) {
		return isset( $this->settings[ $key ] ) && '' !== $this->settings[ $key ] ? $this->settings[ $key ] : $default;
	}

	/**
	 * Include a markup partial from the Templates layer.
	 *
	 * @param string $name Template file name without extension.
	 * @param array  $args Variables exposed to the template as `$args`.
	 */
	protected function template( $name, array $args = [] ) {
		$file = EAEL_PLUGIN_PATH . 'includes/MegaMenu/Templates/' . sanitize_file_name( $name ) . '.php';

		if ( ! file_exists( $file ) ) {
			return;
		}

		include $file;
	}
}

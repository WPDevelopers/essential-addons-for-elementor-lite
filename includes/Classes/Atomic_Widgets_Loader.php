<?php

namespace Essential_Addons_Elementor\Classes;

use Essential_Addons_Elementor\Elements\Atomic\Creative_Button\Creative_Button;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers EA's atomic (V4 editor) widgets.
 *
 * Self-contained and gated on Elementor's Atomic Widgets experiment so that
 * nothing here loads — or fatals — when the experiment is off or the atomic
 * base classes are absent.
 */
class Atomic_Widgets_Loader {

	/**
	 * Panel category slug for EA's atomic widgets. Registered on
	 * `elementor/elements/categories_registered` and referenced by each atomic
	 * widget's `get_categories()`.
	 */
	const CATEGORY = 'ea-atomic-elements';

	public function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'register_categories' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
	}

	/**
	 * Paint the atomic (atom) chip on EA's atomic widget cards in the panel.
	 *
	 * Elementor only renders its native `<i class="eicon-atomic">` chip for the
	 * hardcoded `v4-elements` / `atomic-form` categories (see panel-elements.php,
	 * no filter available). Because EA's atomic widgets live in their own
	 * "Atomic Essential Addons" category, that chip is skipped — so we recreate
	 * it with a CSS `::before` (top-left, clear of the top-right "EA" badge).
	 *
	 * Scoped to the `eael-*-atomic` element-type naming convention so every EA
	 * atomic widget picks it up automatically.
	 */
	public function enqueue_editor_styles(): void {
		if ( ! $this->is_atomic_active() ) {
			return;
		}

		$selector = '.elementor-element[data-library-element-type^="eael-"][data-library-element-type$="-atomic"]';

		$css = $selector . '::before{'
			. 'content:"\ebae";'            // eicon-atomic glyph
			. 'font-family:eicons;'
			. 'position:absolute;'
			. 'inset-block-start:5px;'
			. 'inset-inline-start:5px;'
			. 'font-size:13px;line-height:1;'
			. 'color:var(--e-a-color-txt);'
			. 'pointer-events:none;'
			. '}';

		wp_add_inline_style( 'elementor-editor', $css );
	}

	/**
	 * Register the "Atomic Essential Addons" panel category so EA's atomic
	 * widgets group under their own section instead of Elementor's native
	 * "Atomic Elements" (v4-elements).
	 *
	 * @param \Elementor\Elements_Manager $elements_manager
	 */
	public function register_categories( $elements_manager ): void {
		if ( ! $this->is_atomic_active() ) {
			return;
		}

		$elements_manager->add_category(
			self::CATEGORY,
			[
				'title'       => esc_html__( 'Atomic Essential Addons', 'essential-addons-for-elementor-lite' ),
				'icon'        => 'eaicon-logo',
				'hideIfEmpty' => true,
			]
		);
	}

	private function is_atomic_active(): bool {
		$module = '\Elementor\Modules\AtomicWidgets\Module';

		return class_exists( $module ) && $module::is_active();
	}

	public function register_widgets( $widgets_manager ): void {
		if ( ! $this->is_atomic_active() ) {
			return;
		}

		$widgets_manager->register( new Creative_Button() );
	}

	public function register_assets(): void {
		// Shared effect mechanics (keyframes, transitions). Same file the classic
		// widget uses, but registered under its own handle so the atomic widget
		// does not depend on the classic widget being present on the page.
		wp_register_style(
			'eael-cb-atomic-base',
			EAEL_PLUGIN_URL . 'assets/front-end/css/view/creative-btn.min.css',
			[],
			EAEL_PLUGIN_VERSION
		);

		// Atomic-only default reveal colors, layered on top of the mechanics.
		wp_register_style(
			'eael-cb-atomic',
			EAEL_PLUGIN_URL . 'assets/front-end/css/view/creative-btn-atomic.min.css',
			[ 'eael-cb-atomic-base' ],
			EAEL_PLUGIN_VERSION
		);
	}
}

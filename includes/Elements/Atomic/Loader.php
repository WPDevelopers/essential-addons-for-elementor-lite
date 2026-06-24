<?php

namespace Essential_Addons_Elementor\Elements\Atomic;

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
class Loader {

	public function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_assets' ] );
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

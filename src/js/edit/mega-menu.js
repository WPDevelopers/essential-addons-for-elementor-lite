/**
 * EA Mega Menu — editor element type registration.
 *
 * Runs in the Elementor editor window (not the preview iframe).
 *
 * Returning `support_nesting` from the widget's PHP config is not enough on its
 * own: Elementor only treats a widget as nestable once an element type for it is
 * registered with `elementor.elementsManager`. Without this registration the
 * widget falls back to the plain widget model, so `NestedModelBase.initialize()`
 * never runs, the default child containers are never created, and the panels
 * placeholder is never used — the widget renders but has nowhere to drop widgets.
 *
 * `NestedElementBase` already supplies the view, the empty view and the model;
 * only `getType()` has to be implemented, and it must match the widget name.
 *
 * The base class is loaded asynchronously by Elementor's nested-elements module,
 * which dispatches `elementor/nested-element-type-loaded` once it is ready.
 */
(function () {
	"use strict";

	function registerMegaMenuElementType() {
		if (
			"undefined" === typeof window.elementor ||
			!elementor.elementsManager ||
			!elementor.modules ||
			!elementor.modules.elements ||
			!elementor.modules.elements.types ||
			!elementor.modules.elements.types.NestedElementBase
		) {
			return;
		}

		class EaelMegaMenuElementType extends elementor.modules.elements.types
			.NestedElementBase {
			getType() {
				return "eael-mega-menu";
			}
		}

		elementor.elementsManager.registerElementType(new EaelMegaMenuElementType());
	}

	if ("undefined" === typeof window.elementorCommon || !elementorCommon.elements) {
		return;
	}

	elementorCommon.elements.$window.on(
		"elementor/nested-element-type-loaded",
		registerMegaMenuElementType
	);
})();

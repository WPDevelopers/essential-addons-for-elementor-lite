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

		const GuardedView = buildGuardedView();

		class EaelMegaMenuElementType extends elementor.modules.elements.types
			.NestedElementBase {
			getType() {
				return "eael-mega-menu";
			}

			getView() {
				return GuardedView || super.getView();
			}
		}

		elementor.elementsManager.registerElementType(new EaelMegaMenuElementType());
	}

	/**
	 * Null-guard Elementor's nested element click handler.
	 *
	 * `modules/nested-elements/assets/js/editor/views/view.js` does:
	 *
	 *     events.click = ( event ) => {
	 *         if ( … === event.target.closest( '.elementor' ).dataset.elementorId ) {
	 *
	 * `closest()` returns null whenever the clicked node has already been
	 * detached — which is exactly what happens when you click "+" inside an empty
	 * nested container and pick a layout: creating the container tears down the
	 * empty view that owned the clicked node, and the click then bubbles to this
	 * handler with a target that is no longer in the document.
	 *
	 * The result is an uncaught TypeError in the console on every layout insert.
	 * It is harmless — the handler only selects an element — but it is noise, and
	 * it is reproducible on Elementor's own Nested Tabs, so it is not ours to fix
	 * upstream. Subclassing the view lets us skip the handler in exactly that
	 * detached case for this widget only.
	 *
	 * @return {Function|null} Guarded view class, or null to keep the default.
	 */
	function buildGuardedView() {
		const component =
			"undefined" !== typeof $e && $e.components ? $e.components.get("nested-elements") : null;
		const BaseView = component && component.exports ? component.exports.NestedView : null;

		if ("function" !== typeof BaseView) {
			return null;
		}

		return class EaelMegaMenuView extends BaseView {
			events() {
				const events = super.events();
				const original = events.click;

				if ("function" !== typeof original) {
					return events;
				}

				events.click = function (event) {
					const target = event ? event.target : null;

					if (
						!target ||
						"function" !== typeof target.closest ||
						!target.closest(".elementor")
					) {
						return;
					}

					return original.apply(this, arguments);
				};

				return events;
			}
		};
	}

	if ("undefined" === typeof window.elementorCommon || !elementorCommon.elements) {
		return;
	}

	elementorCommon.elements.$window.on(
		"elementor/nested-element-type-loaded",
		registerMegaMenuElementType
	);
})();

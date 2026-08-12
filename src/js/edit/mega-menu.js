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

	const WIDGET_TYPE = "eael-mega-menu";

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
				return WIDGET_TYPE;
			}

			getView() {
				return GuardedView || super.getView();
			}
		}

		elementor.elementsManager.registerElementType(new EaelMegaMenuElementType());

		patchContainerPresets();
	}

	/**
	 * Restore the structure presets inside a submenu panel.
	 *
	 * Picking a structure in an empty nested container routes through
	 * `elementor.helpers.container.createContainerFromPreset( preset, target,
	 * { createWrapper: false } )` — "use this container as the preset's parent
	 * instead of creating one". Editor V4 (`e_opt_in_v4`, on by default for sites
	 * installed on Elementor 4.0+) reimplemented that helper in
	 * `v4-flexbox-preset.js`, and its `createWrapper: false` branch reuses the
	 * target *without ever applying the preset's parent props*:
	 *
	 *     const reuseTarget = isRoot && false === options.createWrapper;
	 *     const node = reuseTarget ? target : createFlexboxElement( target, buildModel( parentProps … ) );
	 *
	 * So the row direction that makes a multi column preset a *row* of columns is
	 * dropped — "50 / 50" builds two full width children that stack — and the two
	 * direction-only presets (`c100`, `r100`), which carry no children at all,
	 * become complete no-ops: clicking them does nothing.
	 *
	 * This is upstream behaviour shared by every nested element, Elementor's own
	 * Nested Tabs included, so the repair is scoped as tightly as possible: it
	 * only runs for a target that is a direct child of a Mega Menu widget, only
	 * while V4 is on, and only for the `createWrapper: false` call shape. Every
	 * other element keeps whatever the installed Elementor does.
	 */
	function patchContainerPresets() {
		const helper = elementor.helpers ? elementor.helpers.container : null;

		if (
			!helper ||
			helper.eaelMegaMenuPresetsPatched ||
			"function" !== typeof helper.createContainerFromPreset
		) {
			return;
		}

		const createContainerFromPreset = helper.createContainerFromPreset;

		helper.createContainerFromPreset = function (preset, target, options) {
			const created = createContainerFromPreset.apply(this, arguments);

			try {
				applyPresetParent(preset, target, options);
			} catch (e) {
				// A repair is never worth breaking the insert that just succeeded.
			}

			return created;
		};

		helper.eaelMegaMenuPresetsPatched = true;
	}

	/**
	 * Apply the parent half of a preset that V4 dropped.
	 *
	 * @param {string} preset  Preset id, e.g. `c100` or `50-50`.
	 * @param {Object} target  Container the preset was applied to.
	 * @param {Object} options Command options the preset was called with.
	 */
	function applyPresetParent(preset, target, options) {
		if (!isV4OptIn() || !options || false !== options.createWrapper) {
			return;
		}

		if (!isMegaMenuPanel(target)) {
			return;
		}

		const settings = getPresetParentSettings(preset);

		if (!settings) {
			return;
		}

		// `c100` and `r100` describe a bare container with no children. Applying
		// the direction to the panel itself would leave the click with nothing to
		// show for it — the panel is still empty, so the picker just stays open.
		// Creating the container the pre-V4 helper created keeps the visible
		// result users expect from those two tiles.
		if ("c100" === preset || "r100" === preset) {
			elementor.helpers.container.createContainer(settings, target, {});

			return;
		}

		elementor.helpers.container.setContainerSettings(settings, target);
	}

	/**
	 * The flex settings a preset's parent carries.
	 *
	 * Mirrors `PRESET_DEFINITIONS` and `rowOfSizes()` in Elementor's
	 * `v4-flexbox-preset.js`, including its rule that sizes summing past 100%
	 * wrap.
	 *
	 * @param {string} preset Preset id.
	 *
	 * @return {Object|null} Container settings, or null when the id is unknown.
	 */
	function getPresetParentSettings(preset) {
		if ("c100" === preset) {
			return { flex_direction: "column" };
		}

		// The one preset whose id mixes prefixes and sizes; its parent is a row.
		if ("r100" === preset || "c100-c50-50" === preset) {
			return { flex_direction: "row" };
		}

		const sizes = String(preset).split("-").map(Number);

		if (!sizes.length || sizes.some((size) => !size || isNaN(size))) {
			return null;
		}

		const settings = { flex_direction: "row" };

		if (sizes.reduce((sum, size) => sum + size, 0) > 100) {
			settings.flex_wrap = "wrap";
		}

		return settings;
	}

	/**
	 * Is this container one of our submenu panels — a direct child of a Mega Menu?
	 *
	 * @param {Object} container Elementor container object.
	 *
	 * @return {boolean} True for a Mega Menu panel.
	 */
	function isMegaMenuPanel(container) {
		const parent = container ? container.parent : null;

		if (!parent || !parent.model || "function" !== typeof parent.model.get) {
			return false;
		}

		return WIDGET_TYPE === parent.model.get("widgetType");
	}

	/**
	 * Is the V4 editor active? Its preset helper is the one with the gap above.
	 *
	 * @return {boolean}
	 */
	function isV4OptIn() {
		const features =
			window.elementorCommon && elementorCommon.config
				? elementorCommon.config.experimentalFeatures
				: null;

		return !!(features && features.e_opt_in_v4);
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

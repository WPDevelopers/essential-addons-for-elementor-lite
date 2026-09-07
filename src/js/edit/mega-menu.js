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

	/* ------------------------------------------------------------------
	 * Presets.
	 * --------------------------------------------------------------- */

	/**
	 * True from the moment a tile is acted on until the swap is done.
	 *
	 * An apply spans a network round trip and a deliberate wait, and the tiles
	 * stay clickable throughout. Without a lock, an impatient second click starts
	 * a second apply against a container the first one is about to delete.
	 *
	 * @type {boolean}
	 */
	let applyingPreset = false;

	/**
	 * How long to let Elementor's own history entry land first, in ms.
	 *
	 * Clicking a tile runs `document/elements/settings`, and Elementor records
	 * that entry on a *debounced* timer — 800ms, so a run of keystrokes in a text
	 * control collapses into one undo step. A swap that beat the timer put the
	 * setting's entry on top of the delete and the create that followed it, aimed
	 * at a widget those two had already replaced: undoing it changed nothing
	 * visible, and redoing it built a second header beside the first.
	 *
	 * Waiting the timer out orders the stack the way the user performed it —
	 * choose, then rebuild — so undo walks back through it in the same order.
	 */
	const SETTINGS_HISTORY_DEBOUNCE = 850;

	/**
	 * The Mega Menu the panel reported opening, as a fallback.
	 *
	 * @type {Object|null}
	 */
	let openMenu = null;

	/**
	 * Selector for one tile of the Preset control.
	 */
	const PRESET_TILE = ".elementor-control-eael_mega_menu_preset .elementor-choices-label";

	/**
	 * The tile the pointer is over, if any.
	 *
	 * Held because a tooltip has to be closed through the element it belongs to,
	 * and by the time the panel has been replaced that element is no longer
	 * findable in the document — only still referenced from here.
	 *
	 * @type {Element|null}
	 */
	let hoveredTile = null;

	/**
	 * Wire the Preset control up.
	 *
	 * Driven by clicks on the tiles rather than by watching the setting change,
	 * because a tile is a switch and a switch has to answer every press. Backbone
	 * only fires `change` when the value actually moves, so a model-bound handler
	 * silently ignored the two presses users make most: choosing the tile that is
	 * already lit, to start the design over, and choosing Custom, which is a
	 * choice like any other and now rebuilds the widget as the plain menu it
	 * ships as.
	 *
	 * One delegated listener for the whole editor, and the widget is resolved from
	 * the panel at click time. Binding per panel-open meant tracking and
	 * unbinding handlers across views that Elementor tears down underneath us,
	 * and the view the hook hands over is only reliable when a *person* opened
	 * the panel — after the reopen an apply performs itself, it arrives empty,
	 * which stranded every click after the first.
	 */
	function bindPresetControl() {
		if (!window.elementor || !elementor.hooks || !presetConfig()) {
			return;
		}

		elementor.hooks.addAction(
			"panel/open_editor/widget/" + WIDGET_TYPE,
			function (panel, model, view) {
				const settings = model.get("settings");

				openMenu = settings ? { view, settings } : null;
			}
		);

		jQuery(document).on("click", PRESET_TILE, onPresetTileClick);

		// Which tile the pointer is on, so a bubble can still be closed after
		// the tile itself has been taken out of the document — see
		// {@see hidePresetTooltip}.
		jQuery(document).on("mouseenter", PRESET_TILE, function () {
			hoveredTile = this;
		});

		jQuery(document).on("mouseleave", PRESET_TILE, function () {
			hoveredTile = null;
		});

		// Any panel opening means the control that owned the bubble has been
		// re-rendered or replaced. Both shapes are registered because selecting
		// a container fires the first and a widget the second, and a stale
		// tooltip does not care which the user clicked.
		elementor.hooks.addAction("panel/open_editor/widget", hidePresetTooltip);
		elementor.hooks.addAction("panel/open_editor/container", hidePresetTooltip);

		bindOutsidePress();
	}

	/**
	 * A press anywhere else closes a bubble the tiles left behind.
	 *
	 * The panel hooks above only fire when the click lands on something that
	 * opens an editor. Plenty of the editor is not that — the panel's own
	 * chrome, the tab bar, the top bar, an empty stretch of canvas — and a
	 * stale bubble sat over the panel through all of it, which is what the user
	 * sees: a tooltip that will not go away wherever they click.
	 *
	 * `mousedown` in the capture phase, so it runs before any handler can stop
	 * the event, and on a press that turns into a drag as much as on a full
	 * click. Both documents, because the preview is an iframe and a press
	 * inside it never reaches the editor's own.
	 */
	function bindOutsidePress() {
		listenForOutsidePress(document);

		// The preview's document is replaced on every reload, so the listener
		// is attached again each time rather than once.
		try {
			elementor.on("preview:loaded", function () {
				const preview = elementor.$previewContents;

				if (preview && preview.length) {
					listenForOutsidePress(preview[0]);
				}
			});
		} catch (error) {
			// Without the preview listener a bubble survives a press inside the
			// canvas — the next one anywhere else still closes it.
		}
	}

	/**
	 * Attach the outside-press handler to one document.
	 *
	 * @param {Document} doc Document to listen on.
	 */
	function listenForOutsidePress(doc) {
		if (!doc || !doc.addEventListener) {
			return;
		}

		doc.addEventListener("mousedown", onOutsidePress, true);
	}

	/**
	 * A press landed somewhere that is not a preset tile.
	 *
	 * @param {Object} event Native mousedown event.
	 */
	function onOutsidePress(event) {
		// Nothing is open: the common case, and this runs on every press in the
		// editor, so it is answered before touching anything else.
		if (!hoveredTile && !document.querySelector(".tipsy")) {
			return;
		}

		const target = event.target;

		// A press on a tile is the tile's own business — its handler closes the
		// bubble and opens the confirmation.
		if (
			target &&
			"function" === typeof target.closest &&
			target.closest(PRESET_TILE)
		) {
			return;
		}

		hidePresetTooltip();
	}

	/**
	 * The data the editor was handed for this feature.
	 *
	 * @return {Object|null} Localised config, or null when it is absent.
	 */
	function presetConfig() {
		const config = window.eaelMegaMenuEditor;

		return config && config.ajaxurl && config.action ? config : null;
	}

	/**
	 * A tile was pressed.
	 *
	 * The handler runs during the click, before the browser's post-click
	 * activation has moved the radio — so the model still holds the value being
	 * switched away from, which is exactly what Cancel needs to put back.
	 *
	 * @param {Object} event jQuery click event.
	 */
	function onPresetTileClick(event) {
		hidePresetTooltip();

		const config = presetConfig();
		const slug = tileSlug(event.currentTarget);
		const editedMenu = currentMenu();

		if (applyingPreset || !config || !slug || !editedMenu) {
			return;
		}

		const container = getContainer(editedMenu.view);

		if (!container) {
			return;
		}

		const fallback =
			editedMenu.settings.get("eael_mega_menu_preset") || config.custom;
		const target = presetTarget(container);
		const changedAt = Date.now();

		if (!needsConfirm(container, target)) {
			applyPreset(slug, container, target, fallback, changedAt);

			return;
		}

		confirmApply(
			config,
			slug,
			target,
			() => applyPreset(slug, container, target, fallback, changedAt),
			() => revertPreset(container, fallback)
		);
	}

	/**
	 * The Mega Menu the panel is editing right now.
	 *
	 * Asked of the panel rather than remembered, because the panel is the only
	 * thing that always knows: an apply reopens it on the widget it just built,
	 * and a remembered view from before that swap points at a torn-down element.
	 * The recorded one is kept as a fallback for an Elementor that ever stops
	 * exposing the page view.
	 *
	 * @return {Object|null} `{ view, settings }`, or null.
	 */
	function currentMenu() {
		let view = null;

		try {
			const page = elementor.getPanelView().getCurrentPageView();

			view = page && "function" === typeof page.getOption ? page.getOption("editedElementView") : null;
		} catch (error) {
			view = null;
		}

		if (!view || !view.model || "function" !== typeof view.model.get) {
			return openMenu;
		}

		if (WIDGET_TYPE !== view.model.get("widgetType")) {
			return openMenu;
		}

		const settings = view.model.get("settings");

		return settings ? { view, settings } : openMenu;
	}

	/**
	 * Close the tiles' tooltips.
	 *
	 * Elementor shows these through tipsy, which appends the bubble to the
	 * **body** and takes it down again on the target's `mouseleave`. Applying a
	 * preset rebuilds the header and reopens the panel on whatever it built, so
	 * the tile the pointer is sitting on is removed from the document without
	 * ever being left — no `mouseleave`, and a bubble reading "Agency Services"
	 * stays on top of the panel through every widget the user selects next.
	 *
	 * Elementor's own control view hides its tooltips on `onAfterExternalChange`
	 * and nowhere else; there is no teardown hook to lean on. So this is called
	 * from the moments that bracket the problem: the click that is about to
	 * replace the panel, the rebuild settling, any panel opening afterwards, and
	 * a press anywhere else in the editor.
	 *
	 * The tiles are looked up **and** the remembered one is added, because by
	 * the later of those moments the tile is out of the document and a selector
	 * cannot reach it. The bubble is closed through the element either way:
	 * tipsy keeps it on the target's own data, which outlives the detach.
	 *
	 * Every tile, not just the one under the pointer: a bubble left open by an
	 * earlier interrupted hover costs nothing to close. Guarded because tipsy is
	 * Elementor's plugin, not ours — an editor that ever stops shipping it
	 * should cost a missing tooltip, not a broken preset.
	 */
	function hidePresetTooltip() {
		const $tiles = jQuery(PRESET_TILE + ".tooltip-target").add(hoveredTile || []);

		hoveredTile = null;

		if ("function" !== typeof jQuery.fn.tipsy) {
			return;
		}

		// One tile at a time. Called on a set, tipsy reads its instance off the
		// *first* element and closes that one only — so hiding every tile in a
		// single call closed the first tile's bubble, which is never the one
		// that is open, and left the bubble the pointer had actually raised
		// sitting over the panel.
		$tiles.each(function () {
			try {
				jQuery(this).tipsy("hide");
			} catch (error) {
				// A tooltip that will not close is not a reason to refuse the
				// click.
			}
		});

		// A bubble whose tile has already left the document cannot be reached
		// through the tile at all, and the remembered one only covers the tile
		// the pointer happened to be on when the panel went. tipsy appends its
		// bubbles to the body, where they outlive the control that opened them,
		// and stamps each with the element it points at — this is the plugin's
		// own sweep for exactly that, and it removes every bubble now pointing
		// at nothing.
		try {
			if ("function" === typeof jQuery.fn.tipsy.revalidate) {
				jQuery.fn.tipsy.revalidate();
			}
		} catch (error) {
			// As above — cosmetic.
		}
	}

	/**
	 * The preset a tile stands for.
	 *
	 * The value lives on the radio the tile labels, which the control renders
	 * immediately before it.
	 *
	 * @param {Element} tile Clicked label.
	 *
	 * @return {string} Preset slug, or an empty string.
	 */
	function tileSlug(tile) {
		const previous = tile.previousElementSibling;

		if (previous && "INPUT" === previous.tagName) {
			return previous.value || "";
		}

		const input = tile.htmlFor ? document.getElementById(tile.htmlFor) : null;

		return input ? input.value || "" : "";
	}

	/**
	 * What the preset replaces, and therefore what it has to build.
	 *
	 * A preset is a finished header, and a header on an Elementor page is a
	 * top-level block: the container the document holds directly. So the target
	 * is found by climbing from the widget to the last container before the
	 * document — which lands on the right thing in both situations that matter.
	 *
	 * Dropping the widget on the canvas puts it in a container of its own, and
	 * that container is already top-level; it becomes the header bar. Re-applying
	 * a preset later starts from a menu that is now two deep — inside the header's
	 * navigation column — and climbing past it reaches the header itself. Taking
	 * the immediate parent instead would have swapped the navigation column for a
	 * whole second header nested inside the first.
	 *
	 * A menu somewhere with no container above it at all — inside a legacy column,
	 * or directly in the document — has no block to take over, and falls back to
	 * replacing the widget alone.
	 *
	 * @param {Object} container Widget container.
	 *
	 * @return {Object} `{ container, mode }` — what to replace, and what to ask for.
	 */
	function presetTarget(container) {
		let node = container;
		let block = null;

		// Bounded: a corrupted parent chain is not worth hanging the editor over.
		for (let depth = 0; depth < 32; depth++) {
			const parent = node.parent;

			if (
				!parent ||
				!parent.model ||
				"function" !== typeof parent.model.get ||
				"container" !== parent.model.get("elType")
			) {
				break;
			}

			block = parent;
			node = parent;
		}

		return block ? { container: block, mode: "header" } : { container, mode: "widget" };
	}

	/**
	 * Is there anything here worth asking about before it is replaced.
	 *
	 * Panel content always counts: menu labels and colours are a minute's work to
	 * redo, a panel somebody laid out is not. In header mode the block being
	 * replaced counts too, from its second child onwards — a block holding only
	 * the menu is the container the widget arrived in, and turning that into a
	 * header is the whole point rather than something to warn about.
	 *
	 * @param {Object} container Widget container.
	 * @param {Object} target    Result of presetTarget().
	 *
	 * @return {boolean} True when the user should be asked first.
	 */
	function needsConfirm(container, target) {
		if (hasPanelContent(container)) {
			return true;
		}

		if ("header" !== target.mode) {
			return false;
		}

		const siblings = target.container.children;

		return !!(siblings && siblings.length > 1);
	}

	/**
	 * Has the user built anything inside the panels yet.
	 *
	 * Only panel content counts. Menu labels and colours are a minute's work to
	 * redo; a panel somebody laid out is not, and it is the only part of the
	 * widget a preset destroys rather than overwrites.
	 *
	 * @param {Object} container Widget container.
	 *
	 * @return {boolean} True when at least one panel holds an element.
	 */
	function hasPanelContent(container) {
		const children = container.children || [];

		return children.some((child) => {
			const elements = child && child.model ? child.model.get("elements") : null;

			return !!(elements && elements.length);
		});
	}

	/**
	 * Ask before overwriting work.
	 *
	 * @param {Object}   config    Localised config.
	 * @param {string}   slug      Preset being switched to; picks the wording.
	 * @param {Object}   target    Result of presetTarget(); picks the wording.
	 * @param {Function} onConfirm Apply callback.
	 * @param {Function} onCancel  Revert callback.
	 */
	function confirmApply(config, slug, target, onConfirm, onCancel) {
		const dialogs =
			window.elementorCommon && elementorCommon.dialogsManager
				? elementorCommon.dialogsManager
				: null;

		if (!dialogs) {
			onConfirm();

			return;
		}

		// Opened on the next tick, not inside the click. DialogsManager closes a
		// dialog on a click outside it, and the press that asked for this one is
		// still bubbling towards the document — show it here and it is dismissed
		// by the very gesture that opened it, which reads as the tile doing
		// nothing at all.
		setTimeout(function () {
			dialogs
				.createWidget("confirm", {
					headerMessage: config.i18n.title,
					message: confirmMessage(config, slug, target),
					strings: {
						confirm: config.i18n.apply,
						cancel: config.i18n.cancel,
					},
					onConfirm,
					onCancel,
				})
				.show();
		}, 0);
	}

	/**
	 * The right warning for what is about to happen.
	 *
	 * @param {Object} config Localised config.
	 * @param {string} slug   Preset being switched to.
	 * @param {Object} target Result of presetTarget().
	 *
	 * @return {string} Message for the dialog.
	 */
	function confirmMessage(config, slug, target) {
		if (config.custom === slug) {
			return config.i18n.confirmCustom;
		}

		return "header" === target.mode
			? config.i18n.confirmHeader
			: config.i18n.confirm;
	}

	/**
	 * Put the control back where it was, without applying anything.
	 *
	 * `external: true` is what makes the tile in the panel move back: without it
	 * the value changes underneath a control that keeps showing the old one.
	 *
	 * @param {Object} container Widget container.
	 * @param {string} slug      Value to restore.
	 */
	function revertPreset(container, slug) {
		try {
			$e.run("document/elements/settings", {
				container,
				settings: { eael_mega_menu_preset: slug },
				options: { external: true },
			});
		} catch (error) {
			// The control is cosmetic at this point — nothing was applied.
		}
	}

	/**
	 * Put the preset where the menu was.
	 *
	 * A preset is the whole widget — its repeater rows, its styling and one
	 * nested container per row — and in header mode the bar around it as well.
	 * Elementor keeps that repeater and those children in a strict 1:1 index
	 * mapping and syncs them through the repeater commands, so writing a new row
	 * set into the settings would leave the old panels behind it, misaligned, one
	 * per row that no longer exists.
	 *
	 * Swapping the element sidesteps that entirely: the new widget arrives with
	 * its rows and its children already in agreement, which is the same route the
	 * Theme Builder presets take to insert a Mega Menu in the first place.
	 *
	 * Both commands run inside one history entry, so a preset the user did not
	 * mean to apply is one Undo away rather than two.
	 *
	 * @param {string} slug      Preset slug.
	 * @param {Object} container Widget container.
	 * @param {Object} target    Result of presetTarget().
	 * @param {string} fallback  Value to put back if nothing gets applied.
	 * @param {number} changedAt When the tile was clicked, per Date.now().
	 */
	function applyPreset(slug, container, target, fallback, changedAt) {
		const config = presetConfig();

		applyingPreset = true;

		fetchPreset(slug, target.mode)
			.then((element) => settleHistory(changedAt).then(() => element))
			.then((element) => {
				const host = target.container.parent;

				if (!host || !host.model || !element) {
					throw new Error("nowhere to apply");
				}

				// Only the widget path carries the Advanced tab across — margins,
				// custom classes, the element ID, responsive visibility. In header
				// mode the widget is being moved into a bar it has never been in,
				// where a width or a margin set for a standalone menu is not
				// positioning any more, it is a leftover.
				const model =
					"widget" === target.mode
						? withCommonSettings(element, container)
						: element;

				const at = host.model.get("elements").indexOf(target.container.model);

				const historyId = startHistory(config);

				try {
					$e.run("document/elements/delete", { container: target.container });

					const created = $e.run("document/elements/create", {
						container: host,
						model,
						options: at < 0 ? {} : { at },
					});

					openPanel(findMenu(created));
				} finally {
					endHistory(historyId);
				}
			})
			.catch(() => {
				// The tile moved the moment it was clicked. Leaving it on a preset
				// that never landed would tell the user their menu is something it
				// is not, so it goes back with the design it describes.
				revertPreset(container, fallback);
				notifyFailure(config);
			})
			.then(() => {
				applyingPreset = false;

				// The panel has just been rebuilt under the pointer, which is
				// the one way a tile leaves the document without a
				// `mouseleave`. Swept once the rebuild has settled, so the
				// bubble goes on its own rather than waiting for the click that
				// makes the user notice it is stuck.
				setTimeout(hidePresetTooltip, 0);
			});
	}

	/**
	 * The Mega Menu inside whatever the preset just created.
	 *
	 * In header mode it is two containers down; in widget mode it is the thing
	 * itself. Finding it is what lets the panel reopen on the control the user
	 * just clicked, rather than on the bar that now surrounds it.
	 *
	 * @param {Object} created Container returned by the create command.
	 *
	 * @return {Object|null} The menu's container, or null.
	 */
	function findMenu(created) {
		if (!created || !created.model || "function" !== typeof created.model.get) {
			return null;
		}

		if (WIDGET_TYPE === created.model.get("widgetType")) {
			return created;
		}

		const children = created.children || [];

		for (let index = 0; index < children.length; index++) {
			const found = findMenu(children[index]);

			if (found) {
				return found;
			}
		}

		return null;
	}

	/**
	 * The preset's widget, wearing the old one's Advanced-tab settings.
	 *
	 * @param {Object} element   Element the preset built.
	 * @param {Object} container Widget container being replaced.
	 *
	 * @return {Object} Element model for the create command.
	 */
	function withCommonSettings(element, container) {
		return Object.assign({}, element, {
			settings: Object.assign(
				{},
				commonSettings(container),
				element.settings || {}
			),
		});
	}

	/**
	 * The widget's own Advanced-tab settings, carried across the swap.
	 *
	 * Elementor prefixes every control it adds to all widgets with an underscore,
	 * which is what makes "everything the widget owns rather than everything the
	 * Mega Menu owns" a rule this can express in one line.
	 *
	 * @param {Object} container Widget container.
	 *
	 * @return {Object} Settings to preserve.
	 */
	function commonSettings(container) {
		const preserved = {};

		if (!container.settings) {
			return preserved;
		}

		let current;

		try {
			// `remove: default` so an untouched Advanced tab contributes nothing.
			current = container.settings.toJSON({ remove: ["default"] });
		} catch (error) {
			current = container.settings.toJSON();
		}

		Object.keys(current || {}).forEach((key) => {
			if (0 === key.indexOf("_")) {
				preserved[key] = current[key];
			}
		});

		return preserved;
	}

	/**
	 * Wait for Elementor to have logged the tile click.
	 *
	 * See {@see SETTINGS_HISTORY_DEBOUNCE}. Usually the fetch has already covered
	 * most of it, and a confirm dialog covers all of it.
	 *
	 * @param {number} changedAt When the tile was clicked, per Date.now().
	 *
	 * @return {Promise} Resolves once the window has passed.
	 */
	function settleHistory(changedAt) {
		const left = SETTINGS_HISTORY_DEBOUNCE - (Date.now() - changedAt);

		if (left <= 0) {
			return Promise.resolve();
		}

		return new Promise((resolve) => setTimeout(resolve, left));
	}

	/**
	 * Fetch the element one preset applies.
	 *
	 * @param {string} slug Preset slug.
	 * @param {string} mode `header` or `widget`.
	 *
	 * @return {Promise<Object>} Resolves with one Elementor element.
	 */
	function fetchPreset(slug, mode) {
		const config = presetConfig();

		return new Promise((resolve, reject) => {
			jQuery
				.post(config.ajaxurl, {
					action: config.action,
					security: config.nonce,
					preset: slug,
					mode,
				})
				.done((response) => {
					if (response && response.success && response.data) {
						resolve(response.data);

						return;
					}

					reject(new Error("preset unavailable"));
				})
				.fail(() => reject(new Error("request failed")));
		});
	}

	/**
	 * Open the panel on the widget that replaced the old one.
	 *
	 * @param {Object} created Container returned by the create command.
	 */
	function openPanel(created) {
		if (!created || !created.view || !created.model) {
			return;
		}

		try {
			$e.run("panel/editor/open", {
				model: created.model,
				view: created.view,
			});
		} catch (error) {
			// Cosmetic: the widget is already on the canvas either way.
		}
	}

	/**
	 * Group the delete and the create into one undo step.
	 *
	 * @param {Object} config Localised config.
	 *
	 * @return {number|null} Log id to hand back to endHistory().
	 */
	function startHistory(config) {
		try {
			return $e.internal("document/history/start-log", {
				type: "change",
				title: config.i18n.title,
			});
		} catch (error) {
			// Without the transaction the two commands are two undo steps, which
			// is worse but not wrong.
			return null;
		}
	}

	/**
	 * Close the history transaction opened by startHistory().
	 *
	 * The id matters. `end-log` called without one closes whichever log the
	 * history happens to have open, which is not necessarily this one — and a log
	 * left open swallows every later entry, so a second apply and everything
	 * between the two collapse into a single undo step that walks the user all
	 * the way back past work they meant to keep.
	 *
	 * @param {number|null} id Value startHistory() returned.
	 */
	function endHistory(id) {
		try {
			$e.internal("document/history/end-log", null === id ? {} : { id });
		} catch (error) {
			// Mirrors startHistory(): if the log never opened there is nothing to
			// close, and throwing here would swallow the apply that just worked.
		}
	}

	/**
	 * Tell the user the apply did not happen.
	 *
	 * @param {Object} config Localised config.
	 */
	function notifyFailure(config) {
		if (window.elementor && elementor.notifications) {
			elementor.notifications.showToast({ message: config.i18n.failed });

			return;
		}

		window.alert(config.i18n.failed); // eslint-disable-line no-alert
	}

	/**
	 * The Container object behind a widget view.
	 *
	 * @param {Object} view Widget view.
	 *
	 * @return {Object|null} Container, or null.
	 */
	function getContainer(view) {
		if (!view) {
			return null;
		}

		if ("function" === typeof view.getContainer) {
			return view.getContainer();
		}

		return view.container || null;
	}

	if ("undefined" === typeof window.elementorCommon || !elementorCommon.elements) {
		return;
	}

	elementorCommon.elements.$window.on(
		"elementor/nested-element-type-loaded",
		registerMegaMenuElementType
	);

	// `elementor.hooks` is built inside `elementor.init()`, which has not
	// necessarily run by the time this file executes — the script's dependency
	// chain guarantees the global exists, not that it is initialised.
	if (window.elementor && elementor.hooks) {
		bindPresetControl();
	} else {
		elementorCommon.elements.$window.on("elementor:init", bindPresetControl);
	}
})();

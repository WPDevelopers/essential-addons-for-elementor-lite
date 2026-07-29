/**
 * EA Mega Menu — desktop behaviour.
 *
 * What this file does, in order:
 *   1. Reads the settings PHP put on the wrapper as data-* attributes.
 *   2. Decides how a panel opens — on hover, or on click.
 *   3. Works out how wide each panel should be and where to place it.
 *   4. Closes panels again on mouse-out, outside click, Escape, or tab-out.
 */
// Editor only. Keyed by widget id, so a widget can drop its own listener
// before registering a fresh one. See the editor block further down.
var editorRefreshListeners = {};

// Front end only. The media-query listener that watches for the menu crossing
// its breakpoint. Keyed by widget id for the same reason as above — this file
// can run again for the same widget, and a stale listener would keep firing.
var breakpointWatchers = {};

var MegaMenu = function ($scope, $) {
	// -----------------------------------------------------------------
	// 1. Find the elements we need
	// -----------------------------------------------------------------

	var $container = $scope.find(".eael-mega-menu-container").eq(0);

	// Nothing to do if the widget did not render (e.g. no menu items).
	if (!$container.length) {
		return;
	}

	// The plain DOM node. jQuery objects are handy for events, but plain
	// nodes are easier for measuring and for class checks.
	var container = $container[0];

	// Only items that actually have a dropdown need any behaviour.
	var $panelItems = $container.find(".eael-mega-menu__item--has-panel");

	// Every link, dropdown or not. The mobile menu needs them all — a plain
	// link has to be able to close the menu behind it.
	var $links = $container.find(".eael-mega-menu__item-link");

	// Present only when the menu is set to collapse at some width.
	var toggleButton = container.querySelector(".eael-mega-menu__toggle");
	var nav = container.querySelector(".eael-mega-menu__nav");

	// A menu with no dropdowns and no hamburger has nothing to do.
	if (!$panelItems.length && !toggleButton) {
		return;
	}

	// -----------------------------------------------------------------
	// 2. Read the settings PHP passed down as data-* attributes
	// -----------------------------------------------------------------

	var trigger = container.dataset.trigger || "hover";
	var animation = container.dataset.animation || "fade";
	var duration = parseInt(container.dataset.duration, 10);

	// parseInt gives NaN for an empty or broken value, so fall back to 300ms.
	if (isNaN(duration)) {
		duration = 300;
	}

	// The width below which the hamburger takes over, in pixels. PHP sends 0
	// when the user chose never to collapse.
	var breakpoint = parseInt(container.dataset.breakpoint, 10);

	if (isNaN(breakpoint)) {
		breakpoint = 0;
	}

	// Should tapping a plain link close the collapsed menu behind it?
	var closeOnClick = container.dataset.closeOnClick === "yes";

	// Does the collapsed menu slide in from the side, or unroll downwards?
	var isOffcanvas = container.dataset.mobileType === "offcanvas";

	// "hide" keeps linked sections out of the page entirely on small screens.
	var panelBehavior = container.dataset.mobilePanel || "accordion";

	// Should the page behind an open drawer stop scrolling?
	var scrollLockEnabled = container.dataset.scrollLock === "yes";

	// How long to wait before closing after the mouse leaves. Without this
	// small delay the panel flickers shut while moving towards it.
	var CLOSE_DELAY = 150;

	// How long to wait after a resize before measuring again. Resize fires
	// many times per second, and measuring on every one of them is wasteful.
	var RESIZE_DELAY = 150;

	// Editor only: how long to wait after an edit before rebuilding the
	// linked-section previews. Typing in a field fires many changes.
	var RECLONE_DELAY = 300;

	// Holds the pending "close this panel" timer, so we can cancel it.
	var closeTimer = null;

	// Elementor's id for this widget. PHP used it to name the temporary
	// stylesheet, and we use it to keep our event handlers separate.
	var widgetId = $scope.data("id") || "";

	// Every widget gets its own event namespace. We attach some handlers to
	// document/window, and the Elementor editor re-runs this file each time
	// the widget is edited — without a namespace to remove first, those
	// handlers would pile up on every edit.
	var eventNs = ".eaelMegaMenu" + widgetId;

	// A second namespace for the handlers that belong to ONE layout — desktop
	// or collapsed. Crossing the breakpoint drops everything in this namespace
	// and binds the other set, which is what stops handlers stacking up when
	// the window is resized back and forth.
	//
	// Mode handlers carry both namespaces, so the blanket `.off(eventNs)` above
	// still clears them when this file runs again for the same widget.
	var modeNs = ".eaelMegaMenuMode" + widgetId;

	// -----------------------------------------------------------------
	// 3. Apply the animation settings
	// -----------------------------------------------------------------

	// The SCSS reads both of these; the class picks which animation runs.
	container.style.setProperty("--eael-mm-duration", duration + "ms");
	container.classList.add("eael-mega-menu--anim-" + animation);

	// -----------------------------------------------------------------
	// 4. Small helpers
	// -----------------------------------------------------------------

	// Get the panel that belongs to a menu item.
	function getPanel(item) {
		return item.querySelector(".eael-mega-menu__panel");
	}

	// Get the <a> that belongs to a menu item.
	function getLink(item) {
		return item.querySelector(".eael-mega-menu__item-link");
	}

	// Is this item's panel currently open?
	function isOpen(item) {
		return item.classList.contains("is-active");
	}

	// -----------------------------------------------------------------
	// 5. Sizing and placing a panel
	// -----------------------------------------------------------------

	/**
	 * Give the panel the right width and horizontal position.
	 *
	 * The panel is positioned relative to its menu item, so every offset
	 * below is "how far from the menu item" — which is why we subtract the
	 * item's own position from whatever we are lining up with.
	 */
	function positionPanel(item) {
		var panel = getPanel(item);

		if (!panel) {
			return;
		}

		// Wipe the previous run's values first, otherwise we would measure
		// the panel while it is still wearing its old size.
		panel.style.left = "";
		panel.style.right = "";
		panel.style.width = "";
		panel.style.transform = "";

		// clientWidth is the viewport WITHOUT the scrollbar.
		// window.innerWidth includes it and would make the panel too wide.
		var viewportWidth = document.documentElement.clientWidth;
		var itemBox = item.getBoundingClientRect();

		// Option A — stretch across the whole viewport.
		if (panel.classList.contains("eael-mega-menu__panel--full")) {
			panel.style.width = viewportWidth + "px";
			panel.style.left = -itemBox.left + "px";
			panel.style.right = "auto";
			return;
		}

		// Option B — match the Elementor container the menu sits in.
		if (panel.classList.contains("eael-mega-menu__panel--container")) {
			var parentBox = getParentContainerBox(item);

			if (parentBox) {
				panel.style.width = parentBox.width + "px";
				panel.style.left = parentBox.left - itemBox.left + "px";
				panel.style.right = "auto";
			}

			return;
		}

		// Option C — a fixed width the user typed in. The CSS already applied
		// that width, so here we only rescue it if it hangs off the screen.
		keepPanelOnScreen(panel, viewportWidth);
	}

	// Find the Elementor container/section wrapping this menu, and return its
	// size and position. Falls back to the widget's own parent element.
	function getParentContainerBox(item) {
		var parent = item.closest(".e-con, .elementor-container, .elementor-widget-wrap");

		if (!parent) {
			parent = container.parentElement;
		}

		if (!parent) {
			return null;
		}

		return parent.getBoundingClientRect();
	}

	// If a fixed-width panel sticks out past either edge of the screen, pin it
	// to that edge instead.
	function keepPanelOnScreen(panel, viewportWidth) {
		var panelBox = panel.getBoundingClientRect();

		// A centred panel is centred with translateX(-50%). Once we pin it to
		// an edge that shift is wrong, so we clear it.
		if (panelBox.right > viewportWidth) {
			panel.style.left = "auto";
			panel.style.right = "0";
			panel.style.transform = "none";
		} else if (panelBox.left < 0) {
			panel.style.left = "0";
			panel.style.right = "auto";
			panel.style.transform = "none";
		}
	}

	// -----------------------------------------------------------------
	// 6. Opening and closing
	// -----------------------------------------------------------------

	function openPanel(item) {
		if (isOpen(item)) {
			return;
		}

		// Nothing to show — see isPanelEmpty().
		if (isPanelEmpty(getPanel(item))) {
			return;
		}

		// Only one panel may be open at a time.
		closeAllPanels();

		// Measure before showing, so the panel does not visibly jump.
		positionPanel(item);

		item.classList.add("is-active");
		getPanel(item).classList.add("is-open");
		getLink(item).setAttribute("aria-expanded", "true");
	}

	function closePanel(item) {
		item.classList.remove("is-active");
		getPanel(item).classList.remove("is-open");
		getLink(item).setAttribute("aria-expanded", "false");
	}

	function closeAllPanels() {
		$panelItems.each(function () {
			closePanel(this);
		});
	}

	function togglePanel(item) {
		if (isOpen(item)) {
			closePanel(item);
		} else {
			openPanel(item);
		}
	}

	// Cancel a close that was scheduled but has not happened yet.
	function cancelScheduledClose() {
		clearTimeout(closeTimer);
		closeTimer = null;
	}

	// Close this item shortly from now, unless something cancels it first.
	function scheduleClose(item) {
		cancelScheduledClose();

		closeTimer = setTimeout(function () {
			closePanel(item);
		}, CLOSE_DELAY);
	}

	// -----------------------------------------------------------------
	// 7. Linked sections
	// -----------------------------------------------------------------
	//
	// A panel can pull in a Container that lives elsewhere on the page,
	// matched by its CSS ID. We physically MOVE that Container into the
	// panel, so it still exists only once in the page — good for SEO and
	// for screen readers. PHP hid it with a small stylesheet meanwhile.

	// Mark a panel whose linked section could not be used. An unresolved
	// panel never opens, so the menu item behaves as an ordinary link.
	function markUnresolved(panel) {
		panel.classList.add("is-unresolved");
	}

	// Is the viewport currently narrow enough for the collapsed menu?
	function isMobileWidth() {
		return !!mobileQuery && mobileQuery.matches;
	}

	// A panel with nothing inside it: either its CSS ID never resolved, or the
	// "Hide" mobile setting deliberately left it empty. Either way the item has
	// to behave as an ordinary link rather than opening an empty box.
	function isPanelEmpty(panel) {
		return (
			panel.classList.contains("is-unresolved") ||
			panel.classList.contains("eael-mega-menu__panel--mobile-hidden")
		);
	}

	// Move one item's linked section into its panel.
	function adoptLinkedSection(item) {
		var panel = getPanel(item);
		var sectionId = panel.getAttribute("data-mega-section");

		// This panel uses a saved template, not a linked section.
		if (!sectionId) {
			return;
		}

		var section = document.getElementById(sectionId);

		// Nothing on the page has that CSS ID.
		if (!section) {
			markUnresolved(panel);
			return;
		}

		// Already moved in by an earlier run.
		if (panel.contains(section)) {
			return;
		}

		// This menu sits inside the section it points at. Moving the section
		// would put the menu inside its own panel, so refuse.
		if (section.contains(container)) {
			markUnresolved(panel);
			return;
		}

		// Another menu item already claimed this section.
		if (section.dataset.eaelAdopted) {
			markUnresolved(panel);
			return;
		}

		// The "Hide" setting keeps linked sections out of the collapsed menu, so
		// a phone never downloads and lays out a dropdown it will not show. The
		// section stays where it is, hidden, and the item becomes a plain link.
		if (isMobileWidth() && panelBehavior === "hide") {
			section.classList.add("eael-mega-menu__src-hidden");
			panel.classList.add("eael-mega-menu__panel--mobile-hidden");
			return;
		}

		section.dataset.eaelAdopted = "1";
		section.classList.remove("eael-mega-menu__src-hidden");
		section.classList.add("eael-mega-menu__adopted");
		panel.appendChild(section);
	}

	// Pull in whatever the "Hide" setting kept out while the menu was
	// collapsed. Without this a visitor who widens the window would be left
	// with dropdowns that never open.
	function adoptSectionsHeldBackOnMobile() {
		$panelItems.each(function () {
			var panel = getPanel(this);

			if (!panel.classList.contains("eael-mega-menu__panel--mobile-hidden")) {
				return;
			}

			panel.classList.remove("eael-mega-menu__panel--mobile-hidden");
			adoptLinkedSection(this);
		});
	}

	function adoptAllLinkedSections() {
		$panelItems.each(function () {
			adoptLinkedSection(this);
		});

		// The sections are inside their panels now, so drop the stylesheet that
		// was hiding them — otherwise they would stay hidden in there too.
		var hideStyle = document.getElementById("eael-mm-hide-" + widgetId);

		if (hideStyle) {
			hideStyle.remove();
		}

		// Anything inside a moved section (sliders, lazy images) measured itself
		// while hidden and got zero. Ask everything to measure again.
		elementorFrontend.elements.$window.trigger("resize");
	}

	// Place every panel held open by the "Keep Panel Open" switch, so the user
	// styles it at the same size it will have on the live site.
	function positionEditorOpenPanels() {
		$panelItems.each(function () {
			var panel = getPanel(this);

			if (panel.classList.contains("eael-mega-menu__panel--editor-open")) {
				positionPanel(this);
			}
		});
	}

	// -----------------------------------------------------------------
	// 8. Editor preview for linked sections
	// -----------------------------------------------------------------
	//
	// On the front end we MOVE the linked section into the panel. In the
	// editor we must not: moving it would take it out of Elementor's own
	// element tree, and you could no longer click or edit it. So the editor
	// shows a throwaway COPY inside the panel, and the real section stays
	// exactly where it is.

	// Put a "⇢ Dropdown for Shop" marker on the real section, so it is obvious
	// in the editor why that section is sitting there on its own.
	function tagLinkedSource(section, label) {
		section.classList.add("eael-mega-menu__linked-src");
		section.dataset.eaelMegaLabel = label;
	}

	// Clean a freshly made copy.
	//
	// Two jobs:
	//  1. Remove every attribute Elementor uses to recognise one of its own
	//     elements. A copy that kept them would look to the editor like a
	//     second, duplicate element.
	//  2. Remove our own source marker, or the copy would show the
	//     "⇢ Dropdown for" label on itself, inside the panel.
	function stripEditorAttributes(node) {
		var attributes = [
			"id",
			"data-id",
			"data-element_type",
			"data-model-cid",
			"data-widget_type",
			"data-eael-mega-label",
		];

		// The copy's own attributes, then every descendant's.
		var nodes = [node].concat(
			Array.prototype.slice.call(node.querySelectorAll("*"))
		);

		for (var i = 0; i < nodes.length; i++) {
			for (var a = 0; a < attributes.length; a++) {
				nodes[i].removeAttribute(attributes[a]);
			}

			nodes[i].classList.remove("eael-mega-menu__linked-src");
			nodes[i].classList.remove("eael-mega-menu__preview-clone");
		}
	}

	// Put a read-only copy of the section inside the panel, so the dropdown can
	// be seen and styled where it will actually appear. Only ever called when
	// the user switched the preview on for that item.
	function showSectionCopyInPanel(panel, section) {
		var copy = section.cloneNode(true);

		stripEditorAttributes(copy);
		copy.classList.add("eael-mega-menu__preview-clone");

		// Replace whatever the previous run put here.
		panel.innerHTML = "";
		panel.appendChild(copy);
	}

	// Is this item's preview switched on?
	function isPreviewRequested(panel) {
		return panel.classList.contains("eael-mega-menu__panel--editor-open");
	}

	// Check one menu item's linked section, label it in the page, and copy it
	// into the panel IF the user asked for a preview.
	//
	// The copy is deliberately not made by default. The panel is an absolute
	// overlay, so an open preview covers the page — including the very section
	// it points at. Left off, the real section stays visible and editable right
	// where the user built it.
	//
	// Returns what happened, so the note can say what needs fixing:
	//   "ok"        — section found and labelled
	//   "missing"   — no element on the page has that CSS ID
	//   "duplicate" — an earlier item already uses that section
	//   "self"      — this menu is inside the section it points at
	function buildEditorPreview(item, claimedIds) {
		var panel = getPanel(item);
		var sectionId = panel.getAttribute("data-mega-section");

		if (!sectionId) {
			return "skip";
		}

		var section = document.getElementById(sectionId);

		// Nothing on the page has that CSS ID.
		if (!section) {
			markUnresolved(panel);
			return "missing";
		}

		// This menu lives inside the section it points at.
		if (section.contains(container)) {
			markUnresolved(panel);
			return "self";
		}

		// An earlier menu item already claimed this section.
		if (claimedIds.indexOf(sectionId) !== -1) {
			markUnresolved(panel);
			return "duplicate";
		}

		claimedIds.push(sectionId);

		// The label only, not the whole link — otherwise a badge would be read
		// as part of the name and the tag would say "Products New".
		var labelText = item.querySelector(".eael-mega-menu__item-text");

		tagLinkedSource(section, labelText ? labelText.textContent.trim() : "");

		if (isPreviewRequested(panel)) {
			showSectionCopyInPanel(panel, section);
		}

		return "ok";
	}

	// Editor-only note above the menu. It appears only when there is something
	// to fix, or something the user probably does not know yet — never just to
	// confirm that things worked. Never rendered on the live site.
	function renderDiagnostics(result) {
		var existing = container.querySelector(".eael-mega-menu__diagnostics");

		if (existing) {
			existing.remove();
		}

		var problems = [];

		// Each problem is worded as the thing the user needs to change.
		if (result.missing.length) {
			problems.push(
				"No element on this page has the CSS ID " + result.missing.join(" or ") +
					". Open the Container you want as the dropdown and set Advanced → CSS ID."
			);
		}

		if (result.duplicate.length) {
			problems.push(
				"Two menu items point at " + result.duplicate.join(", ") +
					". A section can only feed one dropdown — give the second one its own Container."
			);
		}

		if (result.self.length) {
			problems.push(
				"This menu sits inside " + result.self.join(", ") +
					", so that section cannot also be its dropdown. Move the menu outside it."
			);
		}

		// Nothing wrong — say nothing. The dropdown shows itself, so there is
		// no longer anything the user needs to be told.
		if (problems.length === 0) {
			return;
		}

		var box = document.createElement("div");

		box.className = "eael-mega-menu__diagnostics has-problem";

		// textContent, not innerHTML — the IDs come from user input.
		box.textContent = problems.join("  ");

		container.insertBefore(box, container.firstChild);
	}

	// Rebuild every linked-section preview and refresh the diagnostics note.
	function refreshEditorPreviews() {
		var claimedIds = [];
		var result = {
			found: 0,
			missing: [],
			duplicate: [],
			self: [],
		};

		$panelItems.each(function () {
			var panel = getPanel(this);
			var sectionId = panel.getAttribute("data-mega-section");

			if (!sectionId) {
				return;
			}

			// Start clean so a section that has since appeared can resolve.
			panel.classList.remove("is-unresolved");

			var status = buildEditorPreview(this, claimedIds);

			if (status === "ok") {
				result.found++;
			} else if (result[status]) {
				result[status].push(sectionId);
			}
		});

		renderDiagnostics(result);
		positionEditorOpenPanels();
	}

	// -----------------------------------------------------------------
	// 9. The collapsed (mobile) menu
	// -----------------------------------------------------------------
	//
	// Below the chosen breakpoint the menu turns into a hamburger button plus a
	// list that pushes the page down, and each dropdown becomes an accordion.
	//
	// Heights are measured rather than guessed. A fixed max-height would either
	// cut off a long menu or leave the animation stalling on a short one.

	// Wait for a height animation to finish. A timer is used instead of the
	// `transitionend` event because that event never fires when the duration is
	// 0, which the Animation Duration control allows.
	function afterHeightAnimation(done) {
		setTimeout(done, duration);
	}

	function isMobileNavOpen() {
		return container.classList.contains("is-mobile-open");
	}

	// Where the visitor was reading before the drawer took the scrollbar away,
	// and whether this menu is the one holding the lock.
	var lockedScrollTop = 0;
	var holdsScrollLock = false;

	function lockPageScroll() {
		if (!scrollLockEnabled || holdsScrollLock) {
			return;
		}

		lockedScrollTop = window.pageYOffset || document.documentElement.scrollTop || 0;

		// Both elements — see the stylesheet. <html> is what actually scrolls.
		document.documentElement.classList.add("eael-mega-menu-locked");
		document.body.classList.add("eael-mega-menu-locked");
		holdsScrollLock = true;
	}

	function unlockPageScroll() {
		// Only the menu that took the lock may give it back, so a second menu
		// on the page cannot unlock while this drawer is still open.
		if (!holdsScrollLock) {
			return;
		}

		document.documentElement.classList.remove("eael-mega-menu-locked");
		document.body.classList.remove("eael-mega-menu-locked");

		// Reading a layout value forces the browser to work out the page height
		// again right now. Without it the page is still measured as unscrollable
		// from a moment ago, and the scroll below would be clamped to the top.
		void document.documentElement.offsetHeight;

		// Taking the scrollbar away collapses the page to the top, so the
		// reading position has to be put back by hand.
		window.scrollTo(0, lockedScrollTop);
		holdsScrollLock = false;
	}

	function openMobileNav() {
		container.classList.add("is-mobile-open");
		toggleButton.setAttribute("aria-expanded", "true");

		// A drawer slides in from the side, which is pure CSS — there is no
		// height to measure.
		if (isOffcanvas) {
			lockPageScroll();
			return;
		}

		nav.style.maxHeight = nav.scrollHeight + "px";

		// Once the menu has finished opening, stop constraining it. An
		// accordion opening inside it would otherwise be cut off at the height
		// measured a moment ago.
		afterHeightAnimation(function () {
			if (isMobileNavOpen()) {
				nav.style.maxHeight = "none";
			}
		});
	}

	function closeMobileNav() {
		if (isOffcanvas) {
			container.classList.remove("is-mobile-open");
			toggleButton.setAttribute("aria-expanded", "false");
			unlockPageScroll();
			return;
		}

		// The menu is sitting at `none` while open, and there is nothing to
		// animate from. Put a real number back first and let the browser apply
		// it before switching to 0.
		nav.style.maxHeight = nav.scrollHeight + "px";
		void nav.offsetHeight;

		container.classList.remove("is-mobile-open");
		toggleButton.setAttribute("aria-expanded", "false");
		nav.style.maxHeight = "0px";
	}

	// Focusing an element normally scrolls it into view. Right after closing a
	// drawer that would undo the reading position we have just handed back, so
	// the scroll is asked to stay put — and put back anyway on the older
	// browsers that ignore the request.
	function focusWithoutScrolling(element) {
		var top = window.pageYOffset;

		element.focus({ preventScroll: true });

		if (window.pageYOffset !== top) {
			window.scrollTo(0, top);
		}
	}

	function toggleMobileNav() {
		if (isMobileNavOpen()) {
			closeMobileNav();
		} else {
			openMobileNav();
		}
	}

	// An accordion's height goes through a custom property rather than
	// `style.maxHeight`. The stylesheet needs `!important` there to beat the
	// per-panel Max Height style control, and an inline value would lose to it.
	function setAccordionHeight(panel, height) {
		panel.style.setProperty("--eael-mm-accordion-height", height);
	}

	function closeMobilePanel(item) {
		item.classList.remove("is-active");
		setAccordionHeight(getPanel(item), "0px");
		getLink(item).setAttribute("aria-expanded", "false");
	}

	function openMobilePanel(item) {
		var panel = getPanel(item);

		if (isPanelEmpty(panel)) {
			return;
		}

		// Same rule as the desktop menu: one dropdown open at a time.
		$panelItems.each(function () {
			if (this !== item && isOpen(this)) {
				closeMobilePanel(this);
			}
		});

		item.classList.add("is-active");
		setAccordionHeight(panel, panel.scrollHeight + "px");
		getLink(item).setAttribute("aria-expanded", "true");
	}

	function toggleMobilePanel(item) {
		if (isOpen(item)) {
			closeMobilePanel(item);
		} else {
			openMobilePanel(item);
		}
	}

	// -----------------------------------------------------------------
	// 10. Switching between the two layouts
	// -----------------------------------------------------------------

	// Which set of handlers is currently bound: "desktop" or "mobile".
	var currentMode = "";

	var mobileQuery = breakpoint > 0
		? window.matchMedia("(max-width:" + breakpoint + "px)")
		: null;

	// Clear the placement the desktop code wrote onto a panel. The collapsed
	// menu lays panels out itself, and a leftover inline width would win.
	function clearPanelPlacement(panel) {
		panel.style.left = "";
		panel.style.right = "";
		panel.style.width = "";
		panel.style.transform = "";
	}

	function enterDesktopMode() {
		container.classList.remove("eael-mega-menu--is-mobile");
		container.classList.remove("is-mobile-open");

		// A drawer left open across the breakpoint would otherwise keep the
		// page locked with no way to release it.
		unlockPageScroll();

		if (toggleButton) {
			toggleButton.setAttribute("aria-expanded", "false");
		}

		if (nav) {
			nav.style.maxHeight = "";
		}

		$panelItems.each(function () {
			setAccordionHeight(getPanel(this), "");
			closePanel(this);
		});

		adoptSectionsHeldBackOnMobile();
		bindDesktopHandlers();
	}

	function enterMobileMode() {
		// Anything the desktop layout left open would be stuck open here.
		$panelItems.each(function () {
			closePanel(this);
			clearPanelPlacement(getPanel(this));
			setAccordionHeight(getPanel(this), "0px");
		});

		container.classList.add("eael-mega-menu--is-mobile");
		container.classList.remove("is-mobile-open");
		toggleButton.setAttribute("aria-expanded", "false");

		// A drawer is placed by CSS; only the unrolling menu needs a height.
		nav.style.maxHeight = isOffcanvas ? "" : "0px";

		bindMobileHandlers();
	}

	// Drop every handler belonging to the layout we are leaving. Handlers that
	// must survive — the breakpoint watcher, the late-content retry — use
	// `eventNs` alone and are untouched by this.
	function unbindModeHandlers() {
		$panelItems.off(modeNs);
		$links.off(modeNs);
		$container.off(modeNs);
		$(document).off(modeNs);
		$(window).off(modeNs);

		if (toggleButton) {
			$(toggleButton).off(modeNs);
		}
	}

	// Bind the layout that matches the current width, but only when it is not
	// the one already bound. Without that check a resize would re-bind the same
	// handlers over and over.
	function applyMode() {
		var next = mobileQuery && mobileQuery.matches ? "mobile" : "desktop";

		if (next === currentMode) {
			return;
		}

		unbindModeHandlers();
		currentMode = next;

		if (next === "mobile") {
			enterMobileMode();
		} else {
			enterDesktopMode();
		}
	}

	// Editor only. Open/close is never wired up there (see the editor block
	// below), so a collapsed menu would be stuck shut and the Mobile Menu style
	// controls impossible to preview. Below the breakpoint it is shown already
	// expanded instead, with the button left inert.
	function showEditorMobilePreview() {
		var mobile = !!mobileQuery && mobileQuery.matches;

		container.classList.toggle("eael-mega-menu--is-mobile", mobile);
		container.classList.toggle("is-mobile-open", mobile);
		nav.style.maxHeight = mobile ? "none" : "";
	}

	// Safari only grew addEventListener on media queries in version 14, so the
	// old method is kept as a fallback.
	function listenToQuery(query, handler) {
		if (query.addEventListener) {
			query.addEventListener("change", handler);
		} else {
			query.addListener(handler);
		}
	}

	function stopListeningToQuery(query, handler) {
		if (query.removeEventListener) {
			query.removeEventListener("change", handler);
		} else {
			query.removeListener(handler);
		}
	}

	// -----------------------------------------------------------------
	// 7. Wire up the events
	// -----------------------------------------------------------------

	// Remove this widget's old document/window handlers before adding new
	// ones. See the eventNs comment above for why this matters.
	$(document).off(eventNs);
	$(window).off(eventNs);

	// Inside the Elementor editor a panel is opened by the "Keep Panel Open"
	// switch instead of by hovering — otherwise the panel would vanish the
	// moment you moved the mouse towards the style controls. So we skip all
	// the open/close wiring here and only keep the panels correctly sized.
	if (window.isEditMode) {
		refreshEditorPreviews();

		// Editing the linked section re-renders it, which leaves our copy
		// stale. Elementor fires this action for every element it re-renders,
		// so we rebuild shortly after any edit anywhere on the page.
		var onElementRendered = eael.debounce(refreshEditorPreviews, RECLONE_DELAY);

		// Remove the previous run's listener first. This file runs again every
		// time the widget is edited, and these listeners are global — without
		// this they would build up one per edit.
		if (editorRefreshListeners[widgetId]) {
			elementorFrontend.hooks.removeAction(
				"frontend/element_ready/global",
				editorRefreshListeners[widgetId]
			);
		}

		editorRefreshListeners[widgetId] = onElementRendered;
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/global",
			onElementRendered
		);

		$(window).on(
			"resize" + eventNs,
			eael.debounce(positionEditorOpenPanels, RESIZE_DELAY)
		);

		// Switching the editor's device preview resizes the canvas, so the
		// collapsed view has to follow it.
		showEditorMobilePreview();

		if (mobileQuery) {
			if (breakpointWatchers[widgetId]) {
				stopListeningToQuery(
					breakpointWatchers[widgetId].query,
					breakpointWatchers[widgetId].handler
				);
			}

			breakpointWatchers[widgetId] = {
				query: mobileQuery,
				handler: showEditorMobilePreview,
			};
			listenToQuery(mobileQuery, showEditorMobilePreview);
		}

		return;
	}

	adoptAllLinkedSections();

	// Some content arrives late (lazy loading, other widgets rendering). Give
	// any section we could not find one more chance once the page has loaded.
	$(window).on("load" + eventNs, function () {
		$panelItems.each(function () {
			var panel = getPanel(this);

			if (panel.classList.contains("is-unresolved")) {
				panel.classList.remove("is-unresolved");
				adoptLinkedSection(this);
			}
		});
	});

	function bindDesktopHandlers() {
		if (trigger === "click") {
			$panelItems
				.children(".eael-mega-menu__item-link")
				.on("click" + eventNs + modeNs, function (event) {
					var item = $(this).closest(".eael-mega-menu__item")[0];

					// An empty panel has nothing to show, so let the link behave
					// like a normal link instead of opening an empty box.
					if (isPanelEmpty(getPanel(item))) {
						return;
					}

					// Stop the browser following the link's href.
					event.preventDefault();

					togglePanel(item);
				});
		} else {
			$panelItems.on("mouseenter" + eventNs + modeNs, function () {
				cancelScheduledClose();
				openPanel(this);
			});

			$panelItems.on("mouseleave" + eventNs + modeNs, function () {
				scheduleClose(this);
			});
		}

		// Clicking anywhere outside this menu closes it.
		$(document).on("click" + eventNs + modeNs, function (event) {
			if (!container.contains(event.target)) {
				cancelScheduledClose();
				closeAllPanels();
			}
		});

		// Escape closes the menu and puts focus back on the item that was open.
		$(document).on("keydown" + eventNs + modeNs, function (event) {
			if (event.key !== "Escape" && event.key !== "Esc") {
				return;
			}

			var openItem = container.querySelector(".eael-mega-menu__item.is-active");

			if (!openItem) {
				return;
			}

			cancelScheduledClose();
			closeAllPanels();
			getLink(openItem).focus();
		});

		// Tabbing out of the menu closes it. relatedTarget is whatever received
		// focus next; if that is outside the menu, we are done here.
		$container.on("focusout" + eventNs + modeNs, function (event) {
			if (!event.relatedTarget || !container.contains(event.relatedTarget)) {
				cancelScheduledClose();
				closeAllPanels();
			}
		});

		// A resized window changes every measurement, so re-place whatever is
		// still open. eael.debounce waits until resizing stops.
		var repositionOpenPanel = eael.debounce(function () {
			$panelItems.each(function () {
				if (isOpen(this)) {
					positionPanel(this);
				}
			});
		}, RESIZE_DELAY);

		$(window).on("resize" + eventNs + modeNs, repositionOpenPanel);
		$(window).on("orientationchange" + eventNs + modeNs, repositionOpenPanel);
	}

	function bindMobileHandlers() {
		$(toggleButton).on("click" + eventNs + modeNs, function (event) {
			event.preventDefault();
			toggleMobileNav();
		});

		// The drawer's own close button and the dimmed page behind it. Both are
		// only in the markup when the off-canvas style is chosen.
		var drawerClose = container.querySelector(".eael-mega-menu__close");
		var overlay = container.querySelector(".eael-mega-menu__overlay");

		if (drawerClose) {
			$(drawerClose).on("click" + eventNs + modeNs, function (event) {
				event.preventDefault();
				closeMobileNav();
				// Focus goes back to the control that opened the drawer, not to
				// a button that has just slid off screen.
				focusWithoutScrolling(toggleButton);
			});
		}

		if (overlay) {
			$(overlay).on("click" + eventNs + modeNs, function () {
				closeMobileNav();
			});
		}

		// One handler for every link. An item that owns a dropdown opens it
		// instead of navigating — on a touch screen there is no hover, so the
		// tap has to do both jobs.
		$links.on("click" + eventNs + modeNs, function (event) {
			var item = $(this).closest(".eael-mega-menu__item")[0];
			var panel = getPanel(item);

			if (panel && !isPanelEmpty(panel)) {
				event.preventDefault();
				toggleMobilePanel(item);
				return;
			}

			if (closeOnClick && isMobileNavOpen()) {
				closeMobileNav();
			}
		});

		// Escape closes the menu and hands focus back to the button that opened it.
		$(document).on("keydown" + eventNs + modeNs, function (event) {
			if (event.key !== "Escape" && event.key !== "Esc") {
				return;
			}

			if (isMobileNavOpen()) {
				closeMobileNav();
				focusWithoutScrolling(toggleButton);
			}
		});
	}

	// Pick the layout that matches the width we loaded at, then follow the
	// breakpoint from here on.
	applyMode();

	if (mobileQuery) {
		// Drop the watcher left behind by an earlier run of this file for the
		// same widget, or the old one would keep switching modes as well.
		if (breakpointWatchers[widgetId]) {
			stopListeningToQuery(
				breakpointWatchers[widgetId].query,
				breakpointWatchers[widgetId].handler
			);
		}

		breakpointWatchers[widgetId] = { query: mobileQuery, handler: applyMode };
		listenToQuery(mobileQuery, applyMode);
	}
};

jQuery(window).on("elementor/frontend/init", function () {
	// Runs this widget's setup only once, even if Elementor fires init again.
	if (eael.elementStatusCheck("eaelMegaMenuLoad")) {
		return false;
	}

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-mega-menu.default",
		MegaMenu
	);
});

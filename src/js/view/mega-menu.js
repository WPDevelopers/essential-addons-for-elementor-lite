/**
 * EA Mega Menu — desktop behaviour.
 *
 * What this file does, in order:
 *   1. Reads the settings PHP put on the wrapper as data-* attributes.
 *   2. Decides how a panel opens — on hover, or on click.
 *   3. Works out how wide each panel should be and where to place it.
 *   4. Closes panels again on mouse-out, outside click, Escape, or tab-out.
 */
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

	if (!$panelItems.length) {
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

	// How long to wait before closing after the mouse leaves. Without this
	// small delay the panel flickers shut while moving towards it.
	var CLOSE_DELAY = 150;

	// How long to wait after a resize before measuring again. Resize fires
	// many times per second, and measuring on every one of them is wasteful.
	var RESIZE_DELAY = 150;

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

		// Nothing to show — see markUnresolved().
		if (getPanel(item).classList.contains("is-unresolved")) {
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

		section.dataset.eaelAdopted = "1";
		section.classList.add("eael-mega-menu__adopted");
		panel.appendChild(section);
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
		positionEditorOpenPanels();

		$(window).on(
			"resize" + eventNs,
			eael.debounce(positionEditorOpenPanels, RESIZE_DELAY)
		);

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

	if (trigger === "click") {
		$panelItems
			.children(".eael-mega-menu__item-link")
			.on("click" + eventNs, function (event) {
				var item = $(this).closest(".eael-mega-menu__item")[0];

				// An unresolved panel has nothing to show, so let the link
				// behave like a normal link instead of opening an empty box.
				if (getPanel(item).classList.contains("is-unresolved")) {
					return;
				}

				// Stop the browser following the link's href.
				event.preventDefault();

				togglePanel(item);
			});
	} else {
		$panelItems.on("mouseenter" + eventNs, function () {
			cancelScheduledClose();
			openPanel(this);
		});

		$panelItems.on("mouseleave" + eventNs, function () {
			scheduleClose(this);
		});
	}

	// Clicking anywhere outside this menu closes it.
	$(document).on("click" + eventNs, function (event) {
		if (!container.contains(event.target)) {
			cancelScheduledClose();
			closeAllPanels();
		}
	});

	// Escape closes the menu and puts focus back on the item that was open.
	$(document).on("keydown" + eventNs, function (event) {
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
	$container.on("focusout" + eventNs, function (event) {
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

	$(window).on("resize" + eventNs, repositionOpenPanel);
	$(window).on("orientationchange" + eventNs, repositionOpenPanel);
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

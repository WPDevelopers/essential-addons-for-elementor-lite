/**
 * EA Mega Menu — frontend + editor handler.
 *
 * Implemented as an `elementorModules.frontend.handlers.Base` subclass rather
 * than a plain jQuery callback because a nested widget has to react to editor
 * events (`activeItemIndex`) so that selecting a repeater row in the panel
 * switches the visible submenu in the preview.
 */

const DEVICE_ORDER_FALLBACK = [
	"mobile",
	"mobile_extra",
	"tablet",
	"tablet_extra",
	"laptop",
	"desktop",
	"widescreen",
];

const getMegaMenuHandler = () =>
	class EaelMegaMenuHandler extends elementorModules.frontend.handlers.Base {
		getDefaultSettings() {
			return {
				selectors: {
					root: ".eael-mega-menu",
					toggle: ".eael-mega-menu__toggle",
					container: ".eael-mega-menu__container",
					list: ".eael-mega-menu__list",
					item: ".eael-mega-menu__item",
					link: ".eael-mega-menu__link",
					disclosure: ".eael-mega-menu__disclosure",
					panels: ".eael-mega-menu__panels",
					panel: ".eael-mega-menu__panel",
				},
				classes: {
					mobile: "eael-mega-menu--mobile",
					menuOpen: "eael-mega-menu--menu-open",
					active: "eael-mega-menu--active",
					itemActive: "eael-mega-menu__item--active",
					panel: "eael-mega-menu__panel",
					panelActive: "eael-mega-menu__panel--active",
					hasSubmenu: "eael-mega-menu__item--has-submenu",
				},
			};
		}

		getDefaultElements() {
			const selectors = this.getSettings("selectors");
			// Scoped to direct descendants so a mega menu nested inside another
			// one's panel never steals its parent's elements.
			const $root = this.$element.find(selectors.root).first();

			return {
				$root,
				$toggle: $root.children(selectors.toggle),
				$container: $root.children(selectors.container),
				$items: $root
					.children(selectors.container)
					.children(selectors.list)
					.children(selectors.item),
				$panelsWrapper: $root.children(selectors.container).children(selectors.panels),
				$panels: jQuery(),
			};
		}

		onInit(...args) {
			this.activeIndex = null;
			this.closeTimer = null;

			// ViewModule.onInit() runs initElements() and then bindEvents().
			super.onInit(...args);

			if (!this.elements.$root.length) {
				return;
			}

			this.normalizePanels();
			this.updateDeviceMode();
			this.setTouchMode();

			if (this.isEdit) {
				// Editing mode drops the overlay positioning so the panel of the
				// selected item sits in flow right under the bar and is an obvious,
				// easy-to-hit drop target.
				this.elements.$root.addClass("eael-mega-menu--editing");
				this.activatePanel(this.getInitialEditIndex(), false);
			}
		}

		/**
		 * Which panel the editor should reveal first. A freshly dropped widget has
		 * no edit settings entry yet, hence the guard.
		 */
		getInitialEditIndex() {
			try {
				return this.getEditSettings("activeItemIndex") || 1;
			} catch (e) {
				return 1;
			}
		}

		bindEvents() {
			if (!this.elements.$root.length) {
				return;
			}

			const selectors = this.getSettings("selectors");
			const $root = this.elements.$root;

			this.onDocumentClick = this.handleDocumentClick.bind(this);
			this.onWindowResize = this.handleResize.bind(this);

			$root.on("click", selectors.toggle, this.handleToggleClick.bind(this));
			$root.on("click", selectors.link, this.handleLinkClick.bind(this));
			$root.on("click", selectors.disclosure, this.handleDisclosureClick.bind(this));
			$root.on("keydown", this.handleKeydown.bind(this));
			$root.on("mouseenter", selectors.item, this.handleItemEnter.bind(this));
			$root.on("mouseleave", selectors.item, this.handleItemLeave.bind(this));
			$root.on("mouseenter", selectors.panel, this.handlePanelEnter.bind(this));
			$root.on("mouseleave", selectors.panel, this.handlePanelLeave.bind(this));
			$root.on("focusout", this.handleFocusOut.bind(this));

			elementorFrontend.elements.$document.on("click", this.onDocumentClick);
			elementorFrontend.elements.$window.on(
				"resize.eaelMegaMenu orientationchange.eaelMegaMenu",
				this.onWindowResize
			);
		}

		unbindEvents() {
			this.clearCloseTimer();

			if (this.elements.$root) {
				this.elements.$root.off();
			}

			// Guarded — passing an undefined handler to .off() would strip every
			// click listener on the document.
			if (this.onDocumentClick) {
				elementorFrontend.elements.$document.off("click", this.onDocumentClick);
			}

			if (this.onWindowResize) {
				elementorFrontend.elements.$window.off(
					"resize.eaelMegaMenu orientationchange.eaelMegaMenu",
					this.onWindowResize
				);
			}
		}

		/**
		 * Is this delegated event coming from our own menu rather than a mega menu
		 * nested inside one of our panels?
		 */
		isOwnEvent(event) {
			const $owner = jQuery(event.currentTarget).closest(
				this.getSettings("selectors").root
			);

			return $owner.length > 0 && $owner[0] === this.elements.$root[0];
		}

		/* ------------------------------------------------------------------ */
		/* Data helpers                                                       */
		/* ------------------------------------------------------------------ */

		getMenuItems() {
			const items = this.getElementSettings("eael_mega_menu_items");

			return Array.isArray(items) ? items : [];
		}

		getItemSetting(index, key, fallback) {
			const item = this.getMenuItems()[index - 1];

			if (!item || undefined === item[key] || "" === item[key]) {
				return fallback;
			}

			return item[key];
		}

		getTrigger() {
			return "click" === this.getElementSettings("eael_mega_menu_trigger")
				? "click"
				: "hover";
		}

		getCloseDelay() {
			const delay = this.getElementSettings("eael_mega_menu_hover_delay");

			return delay && undefined !== delay.size ? parseInt(delay.size, 10) : 150;
		}

		closesOnOutsideClick() {
			return "yes" === this.getElementSettings("eael_mega_menu_close_on_outside_click");
		}

		getBreakpoint() {
			return this.getElementSettings("eael_mega_menu_breakpoint") || "tablet";
		}

		/**
		 * Panels are rendered by PHP on the frontend but mounted as bare
		 * containers by the editor — normalise both into the same shape so the
		 * rest of the handler has a single code path.
		 */
		normalizePanels() {
			const { classes } = this.getSettings();
			const widgetNumber = this.elements.$root.attr("data-widget-number");

			this.elements.$panels = this.elements.$panelsWrapper.children();

			this.elements.$panels.each((index, node) => {
				const $panel = jQuery(node);
				const position = index + 1;

				$panel.addClass(classes.panel);

				if (!$panel.attr("data-item-index")) {
					$panel.attr("data-item-index", position);
				}

				if (!$panel.attr("id")) {
					$panel.attr("id", `eael-mega-menu-panel-${widgetNumber}-${position}`);
				}

				if (!$panel.attr("data-width-mode")) {
					$panel.attr(
						"data-width-mode",
						this.getItemSetting(position, "eael_mega_menu_item_submenu_width", "full")
					);
				}

				$panel[0].style.setProperty("--eael-mm-order", String(position * 2 + 1));
			});
		}

		getPanelByIndex(index) {
			return this.elements.$panels.filter(`[data-item-index="${index}"]`);
		}

		getItemByIndex(index) {
			return this.elements.$items.filter(`[data-item-index="${index}"]`);
		}

		itemHasSubmenu(index) {
			return this.getItemByIndex(index).hasClass(
				this.getSettings("classes").hasSubmenu
			);
		}

		/* ------------------------------------------------------------------ */
		/* Responsive                                                         */
		/* ------------------------------------------------------------------ */

		getDeviceOrder() {
			try {
				const list = elementorFrontend.breakpoints.getActiveBreakpointsList({
					withDesktop: true,
				});

				return list && list.length ? list : DEVICE_ORDER_FALLBACK;
			} catch (e) {
				return DEVICE_ORDER_FALLBACK;
			}
		}

		isMobileMode() {
			const breakpoint = this.getBreakpoint();

			if ("none" === breakpoint) {
				return false;
			}

			const order = this.getDeviceOrder();
			const current = elementorFrontend.getCurrentDeviceMode();
			const currentIndex = order.indexOf(current);
			const breakpointIndex = order.indexOf(breakpoint);

			if (-1 === currentIndex || -1 === breakpointIndex) {
				return false;
			}

			return currentIndex <= breakpointIndex;
		}

		updateDeviceMode() {
			const { classes } = this.getSettings();
			const isMobile = this.isMobileMode();

			this.elements.$root.toggleClass(classes.mobile, isMobile);

			if (!isMobile) {
				this.elements.$root.removeClass(classes.menuOpen);
				this.elements.$toggle.attr("aria-expanded", "false");
			}

			if (null !== this.activeIndex) {
				this.positionPanel(this.activeIndex);
			}
		}

		handleResize() {
			this.updateDeviceMode();
		}

		setTouchMode() {
			const isTouch =
				window.matchMedia("(hover: none)").matches ||
				"ontouchstart" in window ||
				navigator.maxTouchPoints > 0;

			this.elements.$root.attr("data-touch-mode", isTouch ? "true" : "false");
		}

		isTouchMode() {
			return "true" === this.elements.$root.attr("data-touch-mode");
		}

		usesClickTrigger() {
			return (
				this.isEdit ||
				this.isMobileMode() ||
				this.isTouchMode() ||
				"click" === this.getTrigger()
			);
		}

		/* ------------------------------------------------------------------ */
		/* Panel positioning                                                  */
		/* ------------------------------------------------------------------ */

		isRtl() {
			return !!(elementorFrontend.config && elementorFrontend.config.is_rtl);
		}

		positionPanel(index) {
			const $panel = this.getPanelByIndex(index);

			if (!$panel.length) {
				return;
			}

			const panel = $panel[0];

			// The collapsed layout is a plain vertical stack — nothing to place.
			if (this.isMobileMode()) {
				panel.style.removeProperty("--eael-mm-panel-inset-start");
				panel.style.removeProperty("--eael-mm-panel-width");

				return;
			}

			const mode = $panel.attr("data-width-mode") || "full";
			const $item = this.getItemByIndex(index);
			const container = this.elements.$container[0];

			if (!container) {
				return;
			}

			if ("full" === mode) {
				panel.style.setProperty("--eael-mm-panel-inset-start", "0px");
				panel.style.setProperty("--eael-mm-panel-width", "100%");

				return;
			}

			if ("viewport" === mode) {
				const rect = container.getBoundingClientRect();
				const start = this.isRtl()
					? document.documentElement.clientWidth - rect.right
					: rect.left;

				panel.style.setProperty("--eael-mm-panel-inset-start", `${-start}px`);
				panel.style.setProperty(
					"--eael-mm-panel-width",
					`${document.documentElement.clientWidth}px`
				);

				return;
			}

			// "item" and "custom" are anchored to the menu item that owns them.
			const offset = $item.length ? this.getItemInlineOffset($item[0], container) : 0;

			panel.style.setProperty("--eael-mm-panel-inset-start", `${offset}px`);

			if ("custom" === mode) {
				const width = this.getItemSetting(
					index,
					"eael_mega_menu_item_submenu_custom_width",
					{ size: 480, unit: "px" }
				);

				panel.style.setProperty(
					"--eael-mm-panel-width",
					`${width.size}${width.unit || "px"}`
				);
			} else {
				panel.style.setProperty("--eael-mm-panel-width", "max-content");
			}
		}

		getItemInlineOffset(item, container) {
			const itemRect = item.getBoundingClientRect();
			const containerRect = container.getBoundingClientRect();

			return this.isRtl()
				? containerRect.right - itemRect.right
				: itemRect.left - containerRect.left;
		}

		/* ------------------------------------------------------------------ */
		/* Open / close                                                       */
		/* ------------------------------------------------------------------ */

		activatePanel(index, focusPanel = false) {
			index = parseInt(index, 10);

			if (!index || !this.itemHasSubmenu(index)) {
				return;
			}

			const { classes } = this.getSettings();

			this.clearCloseTimer();

			if (this.activeIndex && this.activeIndex !== index) {
				this.deactivatePanel(this.activeIndex);
			}

			const $item = this.getItemByIndex(index);
			const $panel = this.getPanelByIndex(index);

			this.positionPanel(index);

			$item.addClass(classes.itemActive);
			$item.find("[aria-expanded]").attr("aria-expanded", "true");
			$panel.addClass(classes.panelActive);
			this.elements.$root.addClass(classes.active);

			this.activeIndex = index;

			if (focusPanel) {
				const focusable = $panel
					.find("a[href], button, input, select, textarea, [tabindex]")
					.filter(":visible")
					.first();

				if (focusable.length) {
					focusable.trigger("focus");
				}
			}
		}

		deactivatePanel(index) {
			index = parseInt(index, 10);

			if (!index) {
				return;
			}

			const { classes } = this.getSettings();
			const $item = this.getItemByIndex(index);

			$item.removeClass(classes.itemActive);
			$item.find("[aria-expanded]").attr("aria-expanded", "false");
			this.getPanelByIndex(index).removeClass(classes.panelActive);

			if (this.activeIndex === index) {
				this.activeIndex = null;
				this.elements.$root.removeClass(classes.active);
			}
		}

		closeAll() {
			if (this.activeIndex) {
				this.deactivatePanel(this.activeIndex);
			}
		}

		togglePanel(index, focusPanel = false) {
			if (this.activeIndex === parseInt(index, 10)) {
				this.deactivatePanel(index);
			} else {
				this.activatePanel(index, focusPanel);
			}
		}

		clearCloseTimer() {
			if (this.closeTimer) {
				clearTimeout(this.closeTimer);
				this.closeTimer = null;
			}
		}

		scheduleClose(index) {
			this.clearCloseTimer();

			this.closeTimer = setTimeout(() => {
				this.deactivatePanel(index);
			}, this.getCloseDelay());
		}

		/* ------------------------------------------------------------------ */
		/* Event handlers                                                     */
		/* ------------------------------------------------------------------ */

		handleToggleClick(event) {
			if (!this.isOwnEvent(event)) {
				return;
			}

			event.preventDefault();

			const { classes } = this.getSettings();
			const isOpen = this.elements.$root.hasClass(classes.menuOpen);

			this.elements.$root.toggleClass(classes.menuOpen, !isOpen);
			this.elements.$toggle.attr("aria-expanded", isOpen ? "false" : "true");

			if (isOpen) {
				this.closeAll();
			}
		}

		handleLinkClick(event) {
			if (!this.isOwnEvent(event)) {
				return;
			}

			const $item = jQuery(event.currentTarget).closest(
				this.getSettings("selectors").item
			);
			const index = parseInt($item.attr("data-item-index"), 10);

			if (!index) {
				return;
			}

			if (this.isEdit) {
				event.preventDefault();
				this.activatePanel(index);

				return;
			}

			if (!this.itemHasSubmenu(index)) {
				return;
			}

			const hasHref = "A" === event.currentTarget.tagName && event.currentTarget.getAttribute("href");

			// A linked item keeps its link; its dedicated disclosure button owns
			// the submenu. Unlinked items toggle from the item itself.
			if (hasHref && !this.isMobileMode() && !this.isTouchMode()) {
				return;
			}

			// On touch / mobile the first tap reveals the submenu, a second tap
			// follows the link.
			if (hasHref && this.activeIndex === index) {
				return;
			}

			event.preventDefault();
			this.togglePanel(index);
		}

		handleDisclosureClick(event) {
			if (!this.isOwnEvent(event)) {
				return;
			}

			event.preventDefault();
			event.stopPropagation();

			const $item = jQuery(event.currentTarget).closest(
				this.getSettings("selectors").item
			);

			this.togglePanel($item.attr("data-item-index"));
		}

		handleItemEnter(event) {
			if (!this.isOwnEvent(event) || this.usesClickTrigger()) {
				return;
			}

			const index = jQuery(event.currentTarget).attr("data-item-index");

			this.clearCloseTimer();
			this.activatePanel(index);
		}

		handleItemLeave(event) {
			if (!this.isOwnEvent(event) || this.usesClickTrigger()) {
				return;
			}

			this.scheduleClose(jQuery(event.currentTarget).attr("data-item-index"));
		}

		handlePanelEnter(event) {
			if (!this.isOwnEvent(event) || this.usesClickTrigger()) {
				return;
			}

			this.clearCloseTimer();
		}

		handlePanelLeave(event) {
			if (!this.isOwnEvent(event) || this.usesClickTrigger()) {
				return;
			}

			this.scheduleClose(jQuery(event.currentTarget).attr("data-item-index"));
		}

		handleKeydown(event) {
			if ("Escape" !== event.key && "Esc" !== event.key) {
				return;
			}

			if (!this.activeIndex) {
				return;
			}

			const index = this.activeIndex;

			this.deactivatePanel(index);

			const $trigger = this.getItemByIndex(index).find("[aria-expanded]").first();

			if ($trigger.length) {
				$trigger.trigger("focus");
			}
		}

		handleFocusOut(event) {
			if (this.isEdit || !this.activeIndex) {
				return;
			}

			const root = this.elements.$root[0];
			const next = event.relatedTarget;

			if (next && root.contains(next)) {
				return;
			}

			this.closeAll();
		}

		handleDocumentClick(event) {
			if (this.isEdit || !this.closesOnOutsideClick()) {
				return;
			}

			const root = this.elements.$root[0];

			if (root.contains(event.target)) {
				return;
			}

			const { classes } = this.getSettings();

			this.closeAll();
			this.elements.$root.removeClass(classes.menuOpen);
			this.elements.$toggle.attr("aria-expanded", "false");
		}

		/* ------------------------------------------------------------------ */
		/* Editor integration                                                 */
		/* ------------------------------------------------------------------ */

		onEditSettingsChange(propertyName, value) {
			if ("activeItemIndex" === propertyName) {
				this.activatePanel(value);
			}
		}

		onElementChange(propertyName) {
			if (0 === propertyName.indexOf("eael_mega_menu_breakpoint")) {
				this.updateDeviceMode();
			}

			// Adding, removing or reordering rows re-mounts the child containers,
			// so the cached collections have to be rebuilt.
			if ("eael_mega_menu_items" === propertyName) {
				this.refreshElements();
			}
		}

		refreshElements() {
			this.initElements();

			if (!this.elements.$root.length) {
				return;
			}

			this.activeIndex = null;
			this.normalizePanels();
			this.updateDeviceMode();

			if (this.isEdit) {
				this.activatePanel(this.getInitialEditIndex(), false);
			}
		}
	};

jQuery(window).on("elementor/frontend/init", function () {
	if (eael.elementStatusCheck("eaelMegaMenu")) {
		return false;
	}

	elementorFrontend.elementsHandler.attachHandler(
		"eael-mega-menu",
		getMegaMenuHandler
	);
});

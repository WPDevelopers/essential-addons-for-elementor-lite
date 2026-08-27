/**
 * EA Mega Menu — frontend + editor handler.
 *
 * Implemented as an `elementorModules.frontend.handlers.Base` subclass rather
 * than a plain jQuery callback because a nested widget has to react to editor
 * events (`activeItemIndex`) so that selecting a repeater row in the panel
 * switches the visible submenu in the preview.
 */

/** Animation keys, mirroring Manager::get_animation_options(). */
const ANIMATIONS = ["none", "fade", "slide-down", "slide-up", "zoom"];

const DEVICE_ORDER_FALLBACK = [
	"mobile",
	"mobile_extra",
	"tablet",
	"tablet_extra",
	"laptop",
	"desktop",
	"widescreen",
];

/**
 * Rendered saved templates, keyed by template id.
 *
 * A panel is re-mounted on every settings change, so without this the editor
 * would re-request the same template on every keystroke in the repeater.
 */
const templatePreviewCache = {};

/**
 * Ask the server for a saved template's markup (its CSS comes inlined).
 *
 * @param {number} templateId Elementor library post id.
 * @param {number} pageId     Document being edited, so the server can refuse to
 *                            render it into itself.
 *
 * @return {Promise<string>} Rendered markup.
 */
const fetchTemplatePreview = (templateId, pageId) => {
	if (templatePreviewCache[templateId]) {
		return templatePreviewCache[templateId];
	}

	// `localize` is printed by Asset_Builder alongside the widget's own script.
	if ("undefined" === typeof localize || !localize.ajaxurl) {
		return Promise.reject(new Error("eael: no ajax config"));
	}

	templatePreviewCache[templateId] = fetch(localize.ajaxurl, {
		method: "POST",
		credentials: "same-origin",
		headers: { "Content-Type": "application/x-www-form-urlencoded" },
		body: new URLSearchParams({
			action: "eael_mega_menu_template_preview",
			security: localize.nonce,
			template_id: templateId,
			page_id: pageId || 0,
		}).toString(),
	})
		.then((response) => response.json())
		.then((response) => {
			if (!response || !response.success || !response.data || !response.data.html) {
				throw new Error("eael: empty template preview");
			}

			return response.data.html;
		});

	// A failed request must not poison the cache for the rest of the session —
	// the template may simply not be published yet.
	templatePreviewCache[templateId].catch(() => {
		delete templatePreviewCache[templateId];
	});

	return templatePreviewCache[templateId];
};

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
					panelInline: "eael-mega-menu__panel--inline",
					panelTemplate: "eael-mega-menu__panel--template",
					panelSection: "eael-mega-menu__panel--section",
					templatePreview: "eael-mega-menu__template-preview",
					sectionSource: "eael-mega-menu__section-source",
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
			this.mountSectionPanels();
			this.updateDeviceMode();
			this.setTouchMode();

			if (this.isEdit) {
				// Editing mode drops the overlay positioning so the panel of the
				// selected item sits in flow right under the bar and is an obvious,
				// easy-to-hit drop target.
				this.elements.$root.addClass("eael-mega-menu--editing");
				this.setEditActiveItem(this.getInitialEditIndex());
			}
		}

		/**
		 * Which repeater row the editor has selected. A freshly dropped widget has
		 * no edit settings entry yet, hence the guard.
		 */
		getInitialEditIndex() {
			try {
				return parseInt(this.getEditSettings("activeItemIndex"), 10) || 1;
			} catch (e) {
				return 1;
			}
		}

		/**
		 * Mirror the editor's selected repeater row in the preview.
		 *
		 * Only the submenu types own a panel. Selecting a link-only row therefore
		 * has to *close* whatever was open: leaving the previous item's panel on
		 * screen would offer a drop target that belongs to a different menu item,
		 * and anything dropped there would silently land under that other item.
		 *
		 * @param {number|string} index Menu item position.
		 */
		setEditActiveItem(index) {
			index = parseInt(index, 10);

			if (index && this.itemHasSubmenu(index)) {
				this.activatePanel(index);

				return;
			}

			this.closeAll();
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

		/**
		 * Normalised item type, mirroring the PHP Menu_Items trait (including the
		 * fallback for rows saved before the type control existed).
		 */
		getItemType(index) {
			const item = this.getMenuItems()[index - 1];

			if (!item) {
				return "mega";
			}

			if (item.eael_mega_menu_item_type) {
				return item.eael_mega_menu_item_type;
			}

			return undefined === item.eael_mega_menu_item_has_submenu ||
				"yes" === item.eael_mega_menu_item_has_submenu
				? "mega"
				: "link";
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

				// The menu item this panel belongs to — NOT its position in the
				// collection. Only submenu items own a panel, so on the front end a
				// link-only item earlier in the bar leaves a gap: the third panel
				// can belong to the fifth menu item. The renderer stamps the real
				// index; the collection index is only a fallback for the editor,
				// where every row is mounted and the two happen to agree.
				const position = parseInt($panel.attr("data-item-index"), 10) || index + 1;

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

				if (this.isEdit) {
					// The editor mounts bare containers, so the type modifiers the
					// PHP renderer prints have to be recreated here — including
					// `--inline`, which carries the panel's default surface.
					const type = this.getItemType(position);

					if ("template" === type) {
						$panel.addClass(classes.panelTemplate);
						this.renderTemplatePreview($panel, position);
					} else if ("section" === type) {
						$panel.addClass(classes.panelSection);
					} else {
						$panel.addClass(classes.panelInline);
					}
				}

				$panel[0].style.setProperty("--eael-mm-order", String(position * 2 + 1));
			});
		}

		/**
		 * Show a Saved Template item's template inside its panel, in the editor.
		 *
		 * The panel itself is the item's (unused) nested container, so the preview
		 * goes into a wrapper of our own rather than among the container's child
		 * views, where Elementor would treat it as content it owns.
		 *
		 * @param {Object} $panel   jQuery panel element.
		 * @param {number} position Menu item position.
		 */
		renderTemplatePreview($panel, position) {
			const { classes } = this.getSettings();
			const templateId = parseInt(
				this.getItemSetting(position, "eael_mega_menu_item_template", 0),
				10
			);
			const $current = $panel.children(`.${classes.templatePreview}`);

			if (!templateId) {
				$current.remove();

				return;
			}

			// Already showing this template — a re-mount must not refetch or flash.
			if ($current.length && String(templateId) === $current.attr("data-template-id")) {
				return;
			}

			$current.remove();

			const $preview = jQuery("<div>", {
				class: classes.templatePreview,
				"data-template-id": templateId,
			});

			$panel.append($preview);

			fetchTemplatePreview(templateId, this.getEditedPostId())
				.then((html) => {
					// The row may have changed type, or the panel may have been
					// re-mounted, while the request was in flight.
					if (!$preview.closest("body").length) {
						return;
					}

					$preview.html(html);

					// Give widgets inside the template a chance to initialise, so a
					// carousel previews as a carousel. Failures are not fatal: the
					// markup is already on screen either way.
					try {
						elementorFrontend.elementsHandler.runReadyTrigger($preview);
					} catch (e) {}
				})
				.catch(() => {
					$preview.remove();
				});
		}

		/**
		 * Id of the document being edited, when it can be determined.
		 */
		getEditedPostId() {
			try {
				return parseInt(elementorFrontend.config.post.id, 10) || 0;
			} catch (e) {
				return 0;
			}
		}

		/**
		 * Move each referenced section into its panel.
		 *
		 * A move rather than a clone, so the CSS ID stays unique in the document.
		 * Never runs in the editor: relocating a container there would fight
		 * Elementor's own views, which re-render elements into their original
		 * parents.
		 */
		mountSectionPanels() {
			if (this.isEdit) {
				return;
			}

			const { classes } = this.getSettings();
			const root = this.elements.$root[0];

			this.elements.$panels.each((i, node) => {
				const sectionId = node.getAttribute("data-section-id");

				if (!sectionId) {
					return;
				}

				const source = document.getElementById(sectionId);

				if (!source) {
					return;
				}

				// Mark it before deciding anything else. The renderer prints a rule
				// that hides the referenced section until this class appears, so
				// that it never flashes in its authored place before being moved —
				// which makes the class mean "the handler has dealt with this
				// element", not "the move succeeded". Every bail-out below has to
				// leave it set, or a section we deliberately refuse to move would
				// stay hidden for the life of the page.
				source.classList.add(classes.sectionSource);

				if (source === node || node.children.length) {
					return;
				}

				// Refuse to swallow one of our own ancestors — that would detach
				// the menu from the document along with it.
				if (source.contains(root) || source.contains(node)) {
					return;
				}

				node.appendChild(source);
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

			this.positionDropdown();

			if (null !== this.activeIndex) {
				this.positionPanel(this.activeIndex);
			}
		}

		/**
		 * Span the collapsed dropdown across the viewport.
		 *
		 * In a "logo left, hamburger right" header the widget shrinks to the
		 * toggle, and an overlay dropdown anchored to that box would be a narrow
		 * strip. Measuring the nav and offsetting by its distance from the
		 * viewport edge gives a full-bleed sheet regardless of widget width.
		 */
		positionDropdown() {
			const container = this.elements.$container[0];

			if (!container) {
				return;
			}

			const overlays = this.elements.$root.hasClass("eael-mega-menu--stretch-dropdown");

			if (!this.isMobileMode() || !overlays) {
				container.style.removeProperty("--eael-mm-dropdown-inset-start");
				container.style.removeProperty("--eael-mm-dropdown-width");

				return;
			}

			const rect = this.elements.$root[0].getBoundingClientRect();
			const docWidth = document.documentElement.clientWidth;
			const start = this.isRtl() ? docWidth - rect.right : rect.left;

			container.style.setProperty("--eael-mm-dropdown-inset-start", `${-start}px`);
			container.style.setProperty("--eael-mm-dropdown-width", `${docWidth}px`);
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
				panel.style.removeProperty("--eael-mm-panel-offset-y");

				return;
			}

			// Per item vertical nudge, applied in every width mode.
			panel.style.setProperty("--eael-mm-panel-offset-y", `${this.getItemOffset(index, "y")}px`);

			const mode = $panel.attr("data-width-mode") || "full";
			const $item = this.getItemByIndex(index);
			const container = this.elements.$container[0];

			if (!container) {
				return;
			}

			const offsetX = this.getItemOffset(index, "x");

			if ("full" === mode) {
				panel.style.setProperty("--eael-mm-panel-inset-start", `${offsetX}px`);
				panel.style.setProperty("--eael-mm-panel-width", "100%");

				return;
			}

			if ("viewport" === mode) {
				const rect = container.getBoundingClientRect();
				const start = this.isRtl()
					? document.documentElement.clientWidth - rect.right
					: rect.left;

				panel.style.setProperty("--eael-mm-panel-inset-start", `${-start + offsetX}px`);
				panel.style.setProperty(
					"--eael-mm-panel-width",
					`${document.documentElement.clientWidth}px`
				);

				return;
			}

			// "item" and "custom" are anchored to the menu item that owns them.
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
				// Fit to content, capped at the room the panel actually has — see
				// the `[data-width-mode="item"]` rule for why this is not
				// `max-content`.
				panel.style.setProperty("--eael-mm-panel-width", "fit-content");
			}

			const itemOffset = $item.length ? this.getItemInlineOffset($item[0], container) : 0;
			const align = this.getItemSetting(index, "eael_mega_menu_item_panel_align", "start");

			let start = itemOffset;

			if ("start" !== align && $item.length) {
				// Measure from the menu's own edge rather than wherever the last
				// pass left the panel: a fit-content width is a function of the
				// inline offset, so measuring against a stale one would feed that
				// offset back into the width it is about to be used to compute.
				panel.style.setProperty("--eael-mm-panel-inset-start", "0px");

				// The width was just applied, so the panel can be measured now.
				const panelWidth = panel.getBoundingClientRect().width;
				const itemWidth = $item[0].getBoundingClientRect().width;

				start =
					"center" === align
						? itemOffset + itemWidth / 2 - panelWidth / 2
						: itemOffset + itemWidth - panelWidth;
			}

			panel.style.setProperty("--eael-mm-panel-inset-start", `${start + offsetX}px`);
		}

		/**
		 * Per item panel nudge, in pixels.
		 *
		 * @param {number} index Menu item position.
		 * @param {string} axis  "x" or "y".
		 */
		getItemOffset(index, axis) {
			const value = this.getItemSetting(
				index,
				`eael_mega_menu_item_panel_offset_${axis}`,
				null
			);

			return value && undefined !== value.size ? parseFloat(value.size) || 0 : 0;
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

			// Open first, place second. A closed panel is `display: none` so that
			// it stops inflating the page's scroll height, and a display-none box
			// measures zero — so the alignment maths in positionPanel() has to run
			// on a panel that is already rendered. Both happen in this one task,
			// before the browser paints, so nothing is seen out of place.
			$panel.addClass(classes.panelActive);
			this.positionPanel(index);

			$item.addClass(classes.itemActive);
			$item.find("[aria-expanded]").attr("aria-expanded", "true");
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
				this.setEditActiveItem(index);

				return;
			}

			if (!this.itemHasSubmenu(index)) {
				return;
			}

			const hasHref = "A" === event.currentTarget.tagName && event.currentTarget.getAttribute("href");

			// A linked item keeps its link and lets its dedicated disclosure button
			// own the submenu — but only while the submenu opens on hover, where
			// the panel is already open by the time a click lands, so the click has
			// nothing left to do but navigate.
			//
			// Wherever the menu opens on click instead — by setting, by touch, or
			// in the collapsed layout — the label is the submenu control: it opens
			// the panel and closes it again, exactly like the indicator icon beside
			// it. The trade is deliberate: an item that owns a submenu does not
			// navigate on click, so its own URL is only followed in hover mode.
			if (hasHref && !this.usesClickTrigger()) {
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
				this.setEditActiveItem(value);
			}
		}

		onElementChange(propertyName) {
			if (0 === propertyName.indexOf("eael_mega_menu_breakpoint")) {
				this.updateDeviceMode();
			}

			if (
				"eael_mega_menu_animation" === propertyName ||
				"eael_mega_menu_trigger" === propertyName
			) {
				this.syncModifierClasses();
			}

			// Adding, removing or reordering rows re-mounts the child containers,
			// so the cached collections have to be rebuilt.
			if ("eael_mega_menu_items" === propertyName) {
				this.refreshElements();
			}
		}

		/**
		 * Re-apply the root modifiers that used to arrive via a full re-render.
		 *
		 * Animation and Trigger carry `render_type: ui`, so Elementor refreshes the
		 * element's CSS but leaves the markup — and therefore these classes —
		 * untouched. Swapping two classes here costs nothing next to re-mounting
		 * every panel and its contents, which is what the default render would do.
		 *
		 * Values are checked against the known keys before being written into a
		 * class name; anything else falls back the way the PHP renderer does.
		 */
		syncModifierClasses() {
			const root = this.elements.$root[0];

			if (!root) {
				return;
			}

			const animation = this.getElementSettings("eael_mega_menu_animation");
			const settled = -1 !== ANIMATIONS.indexOf(animation) ? animation : "none";

			Array.from(root.classList).forEach((name) => {
				if (
					0 === name.indexOf("eael-mega-menu--anim-") ||
					0 === name.indexOf("eael-mega-menu--trigger-")
				) {
					root.classList.remove(name);
				}
			});

			root.classList.add(`eael-mega-menu--anim-${settled}`);
			root.classList.add(`eael-mega-menu--trigger-${this.getTrigger()}`);
			root.setAttribute("data-trigger", this.getTrigger());
		}

		refreshElements() {
			this.initElements();

			if (!this.elements.$root.length) {
				return;
			}

			this.activeIndex = null;
			this.normalizePanels();
			this.mountSectionPanels();
			this.updateDeviceMode();

			if (this.isEdit) {
				this.setEditActiveItem(this.getInitialEditIndex());
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

/**
 * The "Display Conditions" entry in the editor's save menu.
 *
 * Conditions are asked for once, at publish time — but they are not a decision
 * made once. Changing where a header appears used to mean leaving the editor for
 * the dashboard; this puts the same builder one click away, in the menu behind
 * the publish button that first asked for it.
 *
 * Elementor draws that menu in two different places depending on which editor
 * chrome is active, and both are supported here:
 *
 * - the top bar, a React app whose menu takes registered actions through
 *   `elementorV2.editorAppBar.documentOptionsMenu`;
 * - the classic panel footer, a Marionette view with `addSubMenuItem()`.
 *
 * Only one of them exists in a given editor session, so both registrations are
 * attempted and whichever finds its host wins. Like the preset button, the click
 * only *reports*: it dispatches an event on the window, and the React app opens
 * the modal.
 */

export const OPEN_CONDITIONS_EVENT = 'eael/theme-builder/open-conditions';

const ITEM_ID = 'eael-theme-builder-conditions';

// Elementor's own icon for display conditions. An EA mark would need one asset
// per editor theme; this is a font glyph and inherits the menu's own colour.
const ICON_CLASS = 'eicon-flow';

let topBarDone = false;

/**
 * Tell the app the user asked for the condition builder.
 */
function open() {
	window.dispatchEvent( new CustomEvent( OPEN_CONDITIONS_EVENT ) );
}

/**
 * Add the item to the top bar's publish dropdown.
 *
 * @param {string} label Item title.
 */
function registerTopBarItem( label ) {
	if ( topBarDone ) {
		return;
	}

	const menu = window.elementorV2 &&
		window.elementorV2.editorAppBar &&
		window.elementorV2.editorAppBar.documentOptionsMenu;

	// The top bar renders with Elementor's own copy of React, and the icon it is
	// handed is built with the same one — this side of the boundary is theirs.
	const createElement = window.React && window.React.createElement;

	if ( ! menu || ! createElement ) {
		return;
	}

	// Defined once per registration: a component identity that changed between
	// renders would unmount and remount the icon every time the menu updates.
	const Icon = () => createElement( 'i', {
		className: `elementor-icon ${ ICON_CLASS }`,
		'aria-hidden': 'true',
	} );

	// The `save` group holds Save Draft (10) and Save as Template (20); View Page
	// sits in the default group, which renders after it. 30 puts the item at the
	// end of the save block, where Elementor's own conditions item sits.
	menu.registerAction( {
		group: 'save',
		id: ITEM_ID,
		priority: 30,
		useProps: () => ( {
			icon: Icon,
			title: label,
			onClick: open,
		} ),
	} );

	topBarDone = true;
}

/**
 * Add the item to the classic panel footer's save menu.
 *
 * @param {string} label Item title.
 */
function registerPanelItem( label ) {
	let footer = null;

	try {
		// `getPanelView()` is not a safe getter: called before the panel region has
		// been built — which is where this runs on first load — it reaches into an
		// undefined region and throws.
		const panel = window.elementor && window.elementor.getPanelView && window.elementor.getPanelView();

		footer = panel && panel.footer && panel.footer.currentView;
	} catch ( error ) {
		return;
	}

	// The footer is rebuilt as the user moves around the editor, so the guard is
	// kept on the view rather than in this module: a fresh view needs the item
	// added again, the same one must not get it twice.
	if ( ! footer || ! footer.addSubMenuItem || ! footer.ui || footer.ui.eaelThemeBuilderConditions ) {
		return;
	}

	footer.ui.eaelThemeBuilderConditions = footer.addSubMenuItem( 'saver-options', {
		name: ITEM_ID,
		icon: ICON_CLASS,
		title: label,
		callback: open,
	} );
}

/**
 * Put the condition builder in the editor's save menu.
 *
 * @param {Object} settings
 * @param {string} settings.label Item title.
 */
export function registerConditionsMenuItem( { label } ) {
	let listening = false;

	const attach = () => {
		registerTopBarItem( label );
		registerPanelItem( label );

		// The footer view does not survive a panel rebuild, so the item is re-added
		// every time the panel is built.
		if ( ! listening && window.elementor && window.elementor.on ) {
			window.elementor.on( 'panel:init', () => registerPanelItem( label ) );
			listening = true;
		}
	};

	// The listener goes on first: the bundle is enqueued in the footer, which can
	// be before either host has been built, and `elementor/init` is the point
	// where both are reachable.
	window.addEventListener( 'elementor/init', attach, { once: true } );

	attach();
}

/**
 * The EA button in the editor's "add new element" row.
 *
 * Elementor builds that row in the preview iframe from its own Marionette view
 * and exposes one supported way in: `views/add-section/behaviors`, the filter it
 * applies to the view's behaviours. A behaviour gets the view's lifecycle, so
 * the button is re-injected every time the row re-renders — which it does on
 * every layout change — and it inherits the row's own button styling instead of
 * fighting it.
 *
 * The click only *reports*: it dispatches an event on the top window, where the
 * React app lives. Nothing about the picker or the insert happens here.
 */

export const OPEN_PRESETS_EVENT = 'eael/theme-builder/open-presets';

const BUTTON_CLASS = 'eael-add-preset-button';

/**
 * What the button sits after, best anchor first.
 *
 * Elementor's own buttons in reverse row order: Build with AI, Add Template, Add
 * Section. The first of these that is present wins, so the button lands at the
 * end of Elementor's own run whichever of them the editor is showing — the AI
 * button is absent when the feature is off, and the template button on some
 * document types.
 */
const ANCHORS = [
	'.e-ai-layout-button',
	'.elementor-add-template-button',
	'.elementor-add-section-button',
];

/**
 * Whether the editor exposes the pieces the button needs.
 *
 * @return {boolean} True when the behaviour can be registered.
 */
function canRegister() {
	return !! (
		window.elementor &&
		window.elementor.hooks &&
		window.Marionette &&
		window.Marionette.Behavior
	);
}

/**
 * Build the button element.
 *
 * @param {string} icon  Icon URL.
 * @param {string} label Accessible name and tooltip.
 *
 * @return {HTMLElement} The button.
 */
function createButton( icon, label ) {
	const button = document.createElement( 'div' );

	button.className = `elementor-add-section-area-button ${ BUTTON_CLASS }`;
	button.title = label;
	button.dataset.tooltip = label;
	button.setAttribute( 'role', 'button' );
	button.setAttribute( 'tabindex', '0' );
	button.setAttribute( 'aria-label', label );

	// Inline, not a stylesheet: this element lives in the preview iframe, which
	// has its own document and does not load the admin styles this app ships in.
	//
	// The padding is ours rather than Elementor's 12px, which leaves a 16px box —
	// too small for a mark that carries its own rounded plate, where the artwork
	// stops short of the edge and reads smaller than the neighbouring glyphs.
	button.style.cssText = 'background-color:#6F0AF2;color:#fff;display:flex;align-items:center;justify-content:center;padding:3px;';

	if ( icon ) {
		const image = document.createElement( 'img' );

		image.src = icon;
		image.alt = '';
		image.setAttribute( 'aria-hidden', 'true' );
		image.style.cssText = 'width:40px;height:40px;flex-shrink:0;display:block;pointer-events:none;';
		button.appendChild( image );
	}

	return button;
}

/**
 * Add the EA button to the editor's add-element row.
 *
 * @param {Object} settings
 * @param {string} settings.icon  Icon URL.
 * @param {string} settings.label Accessible name and tooltip.
 */
export function registerPresetButton( { icon, label } ) {
	const attach = () => {
		if ( ! canRegister() ) {
			return;
		}

		const Behavior = window.Marionette.Behavior.extend( {
			ui() {
				return { presetButton: `.${ BUTTON_CLASS }` };
			},

			events() {
				return { 'click @ui.presetButton': 'onPresetButtonClick' };
			},

			// Marionette runs every behaviour's `onRender` in one unprotected loop
			// (`_triggerEventOnBehaviors`), so a throw here would silently skip the
			// behaviours registered after ours — other plugins' buttons in this
			// same row included. Nothing below is expected to throw; the guard is
			// there so that if it ever does, it costs this button and no one
			// else's.
			onRender() {
				try {
					this.addPresetButton();
				} catch ( error ) {
					// Swallowed on purpose: see above.
				}
			},

			addPresetButton() {
				const inner = this.el.querySelector( '.elementor-add-section-inner' );

				if ( ! inner || inner.querySelector( `.${ BUTTON_CLASS }` ) ) {
					return;
				}

				const button = createButton( icon, label );
				// Tried in order rather than as one selector list: a list matches in
				// document order, which would return the *first* button in the row
				// instead of the last one we want to sit behind.
				const anchor = ANCHORS.reduce(
					( found, selector ) => found || inner.querySelector( selector ),
					null
				);

				// Anchored to Elementor's own last button rather than dropped at
				// the end of the row. Every plugin that adds a button here inserts
				// it before the "drag widget here" caption, so the row's tail order
				// is decided by which behaviour Marionette happens to render last —
				// which in turn depends on script order, and moves. Sitting on the
				// end of Elementor's own run keeps this button in one place.
				if ( anchor ) {
					anchor.after( button );

					return;
				}

				const title = inner.querySelector( '.elementor-add-section-drag-title' );

				if ( title ) {
					title.before( button );
				} else {
					inner.append( button );
				}
			},

			onPresetButtonClick( event ) {
				event.preventDefault();
				event.stopPropagation();

				window.dispatchEvent( new CustomEvent( OPEN_PRESETS_EVENT, {
					// The view knows where in the document it sits; the insert
					// needs that index to land where the user clicked.
					detail: { options: this.view ? this.view.options : {} },
				} ) );
			},
		} );

		window.elementor.hooks.addFilter( 'views/add-section/behaviors', ( behaviors ) => {
			behaviors.eaelPresets = { behaviorClass: Behavior };

			return behaviors;
		} );
	};

	if ( canRegister() ) {
		attach();

		return;
	}

	window.addEventListener( 'elementor/init', attach, { once: true } );
}

/**
 * Insert a preset's elements into the document.
 *
 * @param {Array}  content Elementor elements.
 * @param {Object} options Options captured from the add-section view.
 *
 * @return {boolean} True when something was inserted.
 */
export function insertPreset( content, options = {} ) {
	if ( ! window.$e || ! window.elementor || ! Array.isArray( content ) || ! content.length ) {
		return false;
	}

	const container = window.elementor.getPreviewContainer();
	const at = options && 'undefined' !== typeof options.at ? options.at : undefined;
	let inserted = 0;

	content.forEach( ( model, index ) => {
		try {
			// The command returns the new container, or false when a hook blocked
			// it — and it throws outright if the model is not something the editor
			// can build. All three have to be caught: the picker has already
			// closed by now, so this return value is the only way the user hears
			// about a failure.
			if ( window.$e.run( 'document/elements/create', {
				container,
				model,
				options: 'undefined' === typeof at ? {} : { at: at + index },
			} ) ) {
				inserted++;
			}
		} catch ( error ) {
			// Counted as not inserted; a later element may still succeed.
		}
	} );

	// Anything on the canvas counts as inserted: a preset is a single top level
	// element today, and telling a user nothing happened while part of it is
	// sitting in front of them would be worse than saying nothing at all.
	return inserted > 0;
}

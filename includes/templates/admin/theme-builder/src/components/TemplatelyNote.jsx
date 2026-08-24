import { useState } from 'react';
import { errorMessage, request, settings, strings } from '../utils/api';

/**
 * The library route Templately opens on.
 */
const LIBRARY_ROUTE = 'elementor/pages';

/**
 * Where to land once Templately is installed and active.
 *
 * This page, not Templately's admin screen: the user is in the middle of
 * building a header, and the reason they clicked was to pick a template for it.
 * Sending them to a list screen in the admin abandons the editor they were in.
 *
 * The two parameters are Templately's own — its bootstrap opens the library on
 * arrival when it sees them, then strips them with `replaceState`, so reloading
 * afterwards does not reopen the modal.
 *
 * @param {string} fallback Where to go if the current URL cannot be parsed.
 *
 * @return {string} URL to navigate to.
 */
function returnUrl( fallback ) {
	try {
		const url = new URL( window.location.href );

		url.searchParams.set( 'templately_open_modal', '1' );
		url.searchParams.set( 'path', LIBRARY_ROUTE );

		return url.toString();
	} catch ( error ) {
		return fallback;
	}
}

/**
 * The way out of the preset picker and into Templately's library.
 *
 * The preset library is a handful of starting points, not a catalogue, so the
 * modal ends by pointing at the catalogue. Templately can be in three states and
 * the action has to work from all of them:
 *
 * - **active**   — a plain anchor, so the browser treats it like any other link
 *                  (middle click, new tab, status bar preview all behave).
 * - **inactive** — a button: activate, then go.
 * - **missing**  — a button: install, activate, then go.
 *
 * A fourth state, `blocked`, means Templately is not usable *and* this user
 * cannot change that. Nothing renders then — an action that is going to be
 * refused is worse than no offer at all.
 *
 * @param {Object}   props
 * @param {boolean}  props.busy    True while a preset is being inserted.
 * @param {Function} props.onError Receives a message when enabling fails.
 * @param {Function} props.onLeave Dismisses the picker, for the hand-off to a
 *                                 library that opens in this same editor.
 */
export default function TemplatelyNote( { busy, onError, onLeave } ) {
	// The editor copy is kept as a fallback for a page still running an older
	// bundle against a newer PHP payload, or the reverse.
	const templately = settings.templately || ( settings.editor || {} ).templately || {};
	const [ working, setWorking ] = useState( false );

	if ( ! templately.state || 'blocked' === templately.state ) {
		return null;
	}

	const name = strings.templatelyName || 'Templately';
	const note = strings.templatelyNote || 'Access 6,500+ cloud templates with %s.';

	const enable = async ( event ) => {
		event.preventDefault();

		if ( working || busy ) {
			return;
		}

		onError( '' );
		setWorking( true );

		try {
			const response = await request( 'eael_theme_builder_enable_templately' );

			if ( ! response || ! response.success ) {
				onError( errorMessage( response ) );

				return;
			}

			// Same tab, and back to this page: the plugin set just changed
			// underneath it, so the scripts it loaded no longer match the site and
			// a reload is owed either way. Elementor's own unsaved-changes prompt
			// still gets its say on the way out.
			window.location.href = returnUrl( response.data.url );
		} catch ( requestError ) {
			onError( strings.genericError || 'Something went wrong. Please try again.' );
		} finally {
			setWorking( false );
		}
	};

	/**
	 * Open the library where the user already is.
	 *
	 * With Templately active its modal is already on the page — the same one its
	 * button in the add-element row opens — so there is nothing to navigate to.
	 * The `href` is left in place and only a plain click is taken over, so a
	 * middle click or a modified one still opens the admin screen in a new tab.
	 *
	 * @param {Object} event Click event.
	 */
	const browse = ( event ) => {
		if ( event.metaKey || event.ctrlKey || event.shiftKey || event.altKey ) {
			return;
		}

		if ( 'function' !== typeof window.templatelyModal ) {
			return;
		}

		event.preventDefault();

		// Templately's library is a dialog in this same editor, and it opens
		// underneath this one — so the picker has to get out of the way, or the
		// user is left closing it by hand to reach what they just asked for.
		// Opened first, and only then dismissed: if the call fails there is still
		// something on screen rather than nothing.
		window.templatelyModal( { route: LIBRARY_ROUTE }, null );
		onLeave();
	};

	const label = () => {
		if ( ! working ) {
			return strings.templatelyAction || 'Connect Templately';
		}

		return 'missing' === templately.state
			? ( strings.templatelyInstalling || 'Installing Templately…' )
			: ( strings.templatelyActivating || 'Activating Templately…' );
	};

	return (
		<div className="eatb-templately">
			<div className="eatb-templately__copy">
				<p className="eatb-templately__title">
					{ strings.templatelyTitle || 'Want more designs?' }
				</p>

				{ /*
				  * The product name is substituted rather than concatenated, so a
				  * translation can put it anywhere in its own sentence instead of
				  * being forced into English word order.
				  */ }
				<p className="eatb-templately__note">{ note.replace( '%s', name ) }</p>
			</div>

			{ 'active' === templately.state ? (
				<a className="eatb-button eatb-button--primary eatb-templately__action" href={ templately.url } onClick={ browse }>
					{ strings.templatelyAction || 'Connect Templately' }
				</a>
			) : (
				<button
					type="button"
					className="eatb-button eatb-button--primary eatb-templately__action"
					onClick={ enable }
					disabled={ working || busy }
				>
					{ label() }
				</button>
			) }
		</div>
	);
}

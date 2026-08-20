import { useState } from 'react';
import { errorMessage, request, settings, strings } from '../utils/api';

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
 */
export default function TemplatelyNote( { busy, onError } ) {
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

			// Same tab, deliberately: the plugin set just changed underneath this
			// page, so its loaded scripts no longer match the site. Leaving is the
			// honest next step, and Elementor's own unsaved-changes prompt still
			// gets its say on the way out.
			window.location.href = response.data.url;
		} catch ( requestError ) {
			onError( strings.genericError || 'Something went wrong. Please try again.' );
		} finally {
			setWorking( false );
		}
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
				<a className="eatb-templately__action" href={ templately.url }>
					{ strings.templatelyAction || 'Connect Templately' }
				</a>
			) : (
				<button
					type="button"
					className="eatb-templately__action"
					onClick={ enable }
					disabled={ working || busy }
				>
					{ label() }
				</button>
			) }
		</div>
	);
}

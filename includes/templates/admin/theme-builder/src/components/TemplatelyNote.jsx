import { useState } from 'react';
import { errorMessage, request, settings, strings } from '../utils/api';

/**
 * The way out of the preset picker and into Templately's library.
 *
 * The preset library is a handful of starting points, not a catalogue, so the
 * modal ends by pointing at the catalogue. Templately can be in three states and
 * the link has to work from all of them:
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
	const editor = settings.editor || {};
	const templately = editor.templately || {};
	const [ working, setWorking ] = useState( false );

	if ( ! templately.state || 'blocked' === templately.state ) {
		return null;
	}

	const name = strings.templatelyName || 'Templately';
	const note = strings.templatelyNote || 'For more templates, use %s.';

	// Split rather than concatenate, so a translation can put the link anywhere in
	// its own sentence instead of being forced into English word order.
	const [ before, after ] = note.split( '%s' );

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
			// editor, so its loaded scripts no longer match the site. Leaving is
			// the honest next step, and Elementor's own unsaved-changes prompt
			// still gets its say on the way out.
			window.location.href = response.data.url;
		} catch ( requestError ) {
			onError( strings.genericError || 'Something went wrong. Please try again.' );
		} finally {
			setWorking( false );
		}
	};

	const label = () => {
		if ( ! working ) {
			return name;
		}

		return 'missing' === templately.state
			? ( strings.templatelyInstalling || 'Installing Templately…' )
			: ( strings.templatelyActivating || 'Activating Templately…' );
	};

	return (
		<p className="eatb-presets__note">
			{ before }
			{ 'active' === templately.state ? (
				<a className="eatb-presets__link" href={ templately.url }>{ name }</a>
			) : (
				<button
					type="button"
					className="eatb-presets__link"
					onClick={ enable }
					disabled={ working || busy }
				>
					{ label() }
				</button>
			) }
			{ after }
		</p>
	);
}

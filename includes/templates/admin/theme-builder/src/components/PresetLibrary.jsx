import { useEffect, useMemo, useState } from 'react';
import Loader from './Loader';
import Modal from './Modal';
import Notice from './Notice';
import TemplatelyNote from './TemplatelyNote';
import { errorMessage, request, settings, strings } from '../utils/api';

/**
 * How long the loader stays up at the least, and at the most, in milliseconds.
 *
 * The floor is deliberately longer than the work behind it. On a fast site the
 * thumbnails are four small local SVGs and decode in a frame or two, so the
 * loader would appear and vanish as a flicker — which reads as a glitch rather
 * than as loading, and leaves the wait feeling less deliberate than it is.
 *
 * The ceiling is the escape hatch: a thumbnail that never resolves must not
 * hold the picker shut. It has to stay clear of the floor, or the two would
 * race and the floor would win by default.
 */
const MIN_LOADER_MS = 3000;
const MAX_LOADER_MS = 6000;

/**
 * The header and footer preset picker.
 *
 * Opened from two places, and scoped differently in each:
 *
 * - From the EA button in the editor's add-element row, with no `type`. Both
 *   kinds are offered under tabs.
 * - On arrival from the creation flow, with the `type` that was chosen there.
 *   There is only one kind worth showing then, so the tabs go and the title
 *   names the type instead.
 *
 * Either way the chosen preset's elements are handed back to the caller, which
 * drops them onto the canvas.
 *
 * A card carries its own preview so the choice is made by looking rather than by
 * reading names — which is also why the dialog carries no explainer beyond its
 * title: the previews are the content.
 *
 * The elements are fetched when a card is chosen, not with the page: a preset is
 * built server side around the site's own name and menu, and every element needs
 * a fresh ID, so the payload cannot be cached in the page.
 *
 * Opening shows the EA loader while the thumbnails decode. The cards are nothing
 * but previews, so a grid rendered ahead of its images is a grid of empty frames
 * that fill in one by one — the loader turns that into one deliberate wait.
 *
 * @param {Object}   props
 * @param {string}   [props.type]     Show only this type, and drop the tabs.
 * @param {Function} props.onClose    Dismiss handler.
 * @param {Function} props.onInsert   Receives the fetched elements.
 */
export default function PresetLibrary( { type = '', onClose, onInsert } ) {
	const editor = settings.editor || {};

	// Memoized because the loading effect keys off it: the fallback would be a
	// fresh array on every render and would restart the loader forever.
	const presets = useMemo(
		() => ( Array.isArray( settings.presets ) ? settings.presets : [] ),
		[]
	);

	// Open on the tab matching the template being edited — a footer template
	// should not open on headers. Scoped to one type there is no tab to pick.
	const [ tab, setTab ] = useState( () => {
		if ( type ) {
			return type;
		}

		return 'footer' === editor.type ? 'footer' : 'header';
	} );
	const [ busy, setBusy ] = useState( '' );
	const [ error, setError ] = useState( '' );
	const [ ready, setReady ] = useState( false );

	useEffect( () => {
		const timers = [];
		let settled = false;

		const finish = () => {
			if ( settled ) {
				return;
			}

			settled = true;
			setReady( true );
		};

		// Both tabs are warmed, not just the open one: they are four small SVGs,
		// and switching tabs after the loader has gone should not stutter.
		const sources = presets.map( ( preset ) => preset.thumbnail ).filter( Boolean );
		const started = Date.now();
		let pending = sources.length;

		// Failures count as settled: a missing thumbnail is the card's problem to
		// show, not a reason to sit on the loader until the timeout.
		const onSettled = () => {
			pending -= 1;

			if ( pending > 0 ) {
				return;
			}

			timers.push( setTimeout( finish, Math.max( 0, MIN_LOADER_MS - ( Date.now() - started ) ) ) );
		};

		if ( ! pending ) {
			timers.push( setTimeout( finish, MIN_LOADER_MS ) );
		}

		sources.forEach( ( source ) => {
			const image = new Image();

			image.onload = onSettled;
			image.onerror = onSettled;
			image.src = source;
		} );

		timers.push( setTimeout( finish, MAX_LOADER_MS ) );

		return () => timers.forEach( clearTimeout );
	}, [ presets ] );

	const tabs = useMemo( () => ( [
		{ slug: 'header', label: strings.headerPresets || 'Header Presets' },
		{ slug: 'footer', label: strings.footerPresets || 'Footer Presets' },
	] ), [] );

	const visible = presets.filter( ( preset ) => preset.type === tab );

	const choose = async ( preset ) => {
		if ( busy ) {
			return;
		}

		setError( '' );
		setBusy( preset.slug );

		try {
			const response = await request( 'eael_theme_builder_get_preset', { preset: preset.slug } );

			if ( ! response || ! response.success ) {
				setError( errorMessage( response ) );

				return;
			}

			onInsert( response.data.content );
		} catch ( requestError ) {
			setError( strings.genericError || 'Something went wrong. Please try again.' );
		} finally {
			setBusy( '' );
		}
	};

	const scoped = tabs.find( ( item ) => item.slug === type );
	const title = scoped ? scoped.label : ( strings.presetsTitle || 'Choose a Header & Footer Preset' );

	// Headed by the mark rather than a bare title, the way the creation dialog
	// that leads into it is.
	const brand = { icon: settings.icon };

	// The same Modal element in both branches, so the loader hands over to the
	// grid inside a dialog that is already open — swapping in a second Modal here
	// would replay the opening animation behind the cards.
	if ( ! ready ) {
		return (
			<Modal title={ title } brand={ brand } size="wide" onClose={ onClose } bodyClass="eatb-modal__body--presets">
				<Loader />
			</Modal>
		);
	}

	return (
		<Modal title={ title } brand={ brand } size="wide" onClose={ onClose } bodyClass="eatb-modal__body--presets">
			{ type ? null : (
			<div className="eatb-presets__tabs" role="tablist">
				{ tabs.map( ( item ) => (
					<button
						key={ item.slug }
						type="button"
						role="tab"
						aria-selected={ tab === item.slug }
						className={ `eatb-presets__tab ${ tab === item.slug ? 'is-active' : '' }` }
						onClick={ () => setTab( item.slug ) }
					>
						{ item.label }
					</button>
				) ) }
			</div>
			) }

			{ error ? <Notice type="error">{ error }</Notice> : null }

			{ visible.length ? (
				<div className="eatb-presets__grid">
					{ visible.map( ( preset ) => (
						<button
							key={ preset.slug }
							type="button"
							className={ `eatb-preset ${ busy === preset.slug ? 'is-busy' : '' }` }
							onClick={ () => choose( preset ) }
							disabled={ !! busy }
							title={ preset.description }
						>
							<span className="eatb-preset__preview">
								{ preset.thumbnail ? (
									<img src={ preset.thumbnail } alt="" aria-hidden="true" />
								) : null }

								<span className="eatb-preset__overlay">
									<span className="eatb-preset__insert">
										{ busy === preset.slug
											? ( strings.inserting || 'Inserting…' )
											: ( strings.clickToInsert || 'Click to Insert' ) }
									</span>
								</span>
							</span>

							<span className="eatb-preset__footer">
								<span className="eatb-preset__name">{ preset.title }</span>
								{ preset.badge ? <span className="eatb-preset__badge">{ preset.badge }</span> : null }
							</span>
						</button>
					) ) }
				</div>
			) : (
				<p className="eatb-presets__empty">
					{ 'footer' === tab
						? ( strings.noFooterPresets || 'Footer presets are on their way. Build yours from scratch in the meantime.' )
						: ( strings.noHeaderPresets || 'No header presets are available yet.' ) }
				</p>
			) }

			<TemplatelyNote busy={ !! busy } onError={ setError } onLeave={ onClose } />
		</Modal>
	);
}

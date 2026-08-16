import { useMemo, useState } from 'react';
import Modal from './Modal';
import Notice from './Notice';
import TemplatelyNote from './TemplatelyNote';
import { errorMessage, request, settings, strings } from '../utils/api';

/**
 * The header and footer preset picker.
 *
 * Opened from the EA button in the editor's add-element row. Cards are grouped
 * by template type into two tabs, and a card carries its own preview so the
 * choice is made by looking rather than by reading names — which is also why the
 * dialog carries no explainer beyond its title: the previews are the content.
 *
 * The elements are fetched when a card is chosen, not with the page: a preset is
 * built server side around the site's own name and menu, and every element needs
 * a fresh ID, so the payload cannot be cached in the page.
 *
 * @param {Object}   props
 * @param {Function} props.onClose  Dismiss handler.
 * @param {Function} props.onInsert Receives the fetched elements.
 */
export default function PresetLibrary( { onClose, onInsert } ) {
	const editor = settings.editor || {};
	const presets = Array.isArray( editor.presets ) ? editor.presets : [];

	// Open on the tab matching the template being edited — a footer template
	// should not open on headers.
	const [ tab, setTab ] = useState( () => ( 'footer' === editor.type ? 'footer' : 'header' ) );
	const [ busy, setBusy ] = useState( '' );
	const [ error, setError ] = useState( '' );

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

	return (
		<Modal
			title={ strings.presetsTitle || 'Choose a Header & Footer Preset' }
			size="wide"
			onClose={ onClose }
		>
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

			<TemplatelyNote busy={ !! busy } onError={ setError } />
		</Modal>
	);
}
